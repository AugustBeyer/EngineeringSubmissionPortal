<?php
session_start();
require "db_config.php";
require "system_config.php";

$tid = htmlspecialchars($_GET["tid"]);

try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Delete entry from team_advisors_junction
    $stmt = $dbh->prepare("DELETE FROM teams_advisors_junction WHERE junction_team_id = :junction_team_id");
    $stmt->bindParam(':junction_team_id', $tid);
    $stmt->execute();

    //Delete entry from students table (set their team to null)
    $null = NULL;
    $stmt = $dbh->prepare("UPDATE students SET students_team_id = :new_students_team_id WHERE students_team_id = :old_students_team_id");
    $stmt->bindParam(':new_students_team_id', $null);
    $stmt->bindParam(':old_students_team_id', $tid);
    $stmt->execute();

    //Delete entry from teams
    $stmt = $dbh->prepare("DELETE FROM teams WHERE primary_team_id = :primary_team_id");
    $stmt->bindParam(':primary_team_id', $tid);
    $stmt->execute();

    //Remove the teams folder in the top-level directory 
    //chdir("/opt/web/esp/" . $current_year);
    system('rm -R ' . "/opt/web/esp/" . $current_year . "/" . $tid);

    echo "New records created successfully\r\n";
    header('Location: ../web_front/advisor/home.php');
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }

$dbh = null;
?>