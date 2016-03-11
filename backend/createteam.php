<?php
session_start();
require "db_config.php";

$current_year_path = "/DCNFS/web/esp/2016/";

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST')
{
    $advisor_id = $_SESSION["user_id"];
    $project_name = $_POST["Teamname"];
    $student1 = $_POST["student1"];
    $student2 = $_POST["student2"];
    $student3 = $_POST["student3"];
}

try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //insert entry into teams table
    $stmt = $dbh->prepare("INSERT INTO teams (project_name, cumulative_grade)
VALUES (:project_name, :cumulative_grade)");
    $stmt->bindParam(':project_name', $project_name);
    $stmt->bindParam(':cumulative_grade', $cumulative_grade);
    $cumulative_grade = NULL;
    $stmt->execute();

    $team_id = $dbh->lastInsertId(); //May create race condition?
    //insert entry in junction table that has advisor id, team id
    $stmt = $dbh->prepare("INSERT INTO teams_advisors_junction (junction_team_id, junction_advisor_id)
    VALUES (:junction_team_id, :junction_advisor_id)");
    $stmt->bindParam(':junction_team_id', $team_id);
    $stmt->bindParam(':junction_advisor_id', $advisor_id);
    $stmt->execute();

    //insert entry in students table that has student id, team id
    $stmt = $dbh->prepare("UPDATE students SET students_team_id = :students_team_id WHERE scu_username = :scu_username");
    $stmt->bindParam(':students_team_id', $team_id);
    $stmt->bindParam(':scu_username', $student1);
    $stmt->execute();
    $stmt->bindParam(':scu_username', $student2);
    $stmt->execute();
    $stmt->bindParam(':scu_username', $student3);
    $stmt->execute();

    //create new directory for the team
    mkdir($current_year_path . $team_id, 0777);

    echo "New records created successfully\r\n";
    header('Location: ../web_front/advisor/home.php');
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }

$dbh = null;

?>
