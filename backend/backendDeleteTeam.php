<?php
session_start();
require "db_config.php";

$current_year_path = "/DCNFS/web/esp/2016/";

$tid = htmlspecialchars($_GET["tid"]);

try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Delete entry from team_advisors_junction
    $stmt = $dbh->prepare("DELETE FROM teams_advisors_junction WHERE junction_team_id = :junction_team_id");
    $stmt->bindParam(':junction_team_id', $tid);
    $stmt->execute();

    //Delete entry from students table (set their team to null)
    $null = NULL;
    $stmt = $dbh->prepare("UPDATE students SET new_students_team_id = :new_students_team_id WHERE students_team_id = :students_team_id");
    $stmt->bindParam(':new_students_team_id', $null);
    $stmt->bindParam(':students_team_id', $tid);
    $stmt->execute();

    //Delete entry from teams
    $stmt = $dbh->prepare("DELETE FROM teams WHERE primary_team_id = :primary_team_id");
    $stmt->bindParam(':primary_team_id', $tid);
    $stmt->execute();

    //Remove the teams folder in the top-level directory 
    $stmt = $dbh->prepare("SELECT primary_advisor_id FROM advisors WHERE advisor_username = :advisor_username");
    $stmt->bindParam(':advisor_username', $oldadvisors[$i]);
    $stmt->execute();
    $additional_advisor_id = $stmt->fetch(PDO::FETCH_COLUMN);

    echo "New records created successfully\r\n";
    header('Location: ../web_front/advisor/home.php');
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }

$dbh = null;
?>