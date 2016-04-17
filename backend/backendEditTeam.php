<?php
session_start();
require "db_config.php";

$current_year_path = "/DCNFS/web/esp/2016/";

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST')
{
    $advisor_id = $_SESSION["user_id"];
    $project_name = $_POST["Teamname"];
    $oldstudents = $_POST["oldstudents"];
    $oldadvisors = $_POST["oldadvisors"];
    if (!empty($_POST["students"]))
        $students = $_POST["students"];
    if (!empty($_POST["advisors"]))
        $advisors = $_POST["advisors"];
}

$tid = htmlspecialchars($_GET["tid"]);

try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh -> prepare("UPDATE teams SET project_name = :project_name WHERE primary_team_id = :primary_team_id");
    $stmt->bindParam(':project_name', $project_name);
    $stmt->bindParam(':primary_team_id', $tid);
    $stmt->execute();

    //Delete existing advisor from team
    for ($i=0; $i < count($oldadvisors); $i++) 
    { 
        //Name was checked - don't delete
        if(array_count_values($oldadvisors)[$oldadvisors[$i]] == 2)
        {
            continue;
        }
        else
        {
            $stmt = $dbh->prepare("SELECT primary_advisor_id FROM advisors WHERE advisor_username = :advisor_username");
            $stmt->bindParam(':advisor_username', $oldadvisors[$i]);
            $stmt->execute();
            $additional_advisor_id = $stmt->fetch(PDO::FETCH_COLUMN);

            $stmt = $dbh->prepare("DELETE FROM teams_advisors_junction WHERE junction_advisor_id = :junction_advisor_id AND junction_team_id = :junction_team_id");
            $stmt->bindParam(':junction_advisor_id', $additional_advisor_id);
            $stmt->bindParam(':junction_team_id', $tid);
            $stmt->execute();
        }
    }

    //Delete existing student from team
    for ($i=0; $i < count($oldstudents); $i++) 
    { 
        //Name was checked - don't delete
        if(array_count_values($oldstudents)[$oldstudents[$i]] == 2)
        {
            continue;
        }
        else
        {
            $null = NULL;
            $stmt = $dbh->prepare("UPDATE students SET students_team_id = :students_team_id WHERE scu_username = :scu_username");
            $stmt->bindParam(':students_team_id', $null);
            $stmt->execute();
        }
    }

    if(!empty($_POST["advisors"]))
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
            $stmt->bindParam(':junction_team_id', $tid);
            $stmt->bindParam(':junction_advisor_id', $additional_advisor_id);
            $stmt->execute();
        }
    }

    //insert entry in students table that has student id, team id
    if(!empty($_POST["students"]))
    {
        for ($i=0; $i < count($students); $i++) 
        { 
            $stmt = $dbh->prepare("UPDATE students SET students_team_id = :students_team_id WHERE scu_username = :scu_username");
            $stmt->bindParam(':students_team_id', $tid);
            $stmt->bindParam(':scu_username', $students[$i]);
            $stmt->execute();
        }
    }

    echo "New records created successfully\r\n";
    header('Location: ../web_front/advisor/home.php');
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }

$dbh = null;
?>