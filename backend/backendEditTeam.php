<?php
session_start();
require "db_config.php";

$current_year_path = "/DCNFS/web/esp/2016/";

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST')
{
    $advisor_id = $_SESSION["user_id"];
    $project_name = $_POST["Teamname"];
    $students = $_POST["students"];
    $advisors = $_POST["advisors"];
}

print_r($_POST);

/*
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

    if(!empty($advisors))
    {
        for ($i=0; $i < count($advisors); $i++)
        {
            //get advisor id from advisor username
            $stmt = $dbh->prepare("SELECT primary_advisor_id FROM advisors WHERE advisor_username = :advisor_username");
            $stmt->bindParam(':advisor_username', $advisors[$i]);
            $stmt->execute();
            $additional_advisor_id = $stmt->fetch(PDO::FETCH_ASSOC)["primary_advisor_id"];

            $stmt = $dbh->prepare("INSERT INTO teams_advisors_junction (junction_team_id, junction_advisor_id)
            VALUES (:junction_team_id, :junction_advisor_id)");
            $stmt->bindParam(':junction_team_id', $team_id);
            $stmt->bindParam(':junction_advisor_id', $additional_advisor_id);
            $stmt->execute();
        }
    }

    //insert entry in students table that has student id, team id
    if(!empty($students))
    {
        for ($i=0; $i < count($students); $i++) 
        { 
            $stmt = $dbh->prepare("UPDATE students SET students_team_id = :students_team_id WHERE scu_username = :scu_username");
            $stmt->bindParam(':students_team_id', $team_id);
            $stmt->bindParam(':scu_username', $students[$i]);
            $stmt->execute();
        }
    }

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
*/
?>