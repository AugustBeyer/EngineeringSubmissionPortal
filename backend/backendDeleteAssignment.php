<?php
session_start();
require "db_config.php";
require "system_config.php";

$aid = htmlspecialchars($_GET["aid"]);
try 
{

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare("SELECT reference_location, reference_file_name, submitted_file_name FROM assignments WHERE primary_assignment_id = :aid");
    $stmt->bindParam(':aid', $aid);
    $stmt->execute();

    $reference_file_location = $stmt->fetch(PDO::FETCH_ASSOC)["reference_location"];
    $reference_file_name = $stmt->fetch(PDO::FETCH_ASSOC)["reference_file_name"];
    $submitted_file_name = $stmt->fetch(PDO::FETCH_ASSOC)["submitted_file_name"];

    $target_file = $reference_file_location . "/" . $reference_file_name;
    $submitted_file = $reference_file_location . "/" . $submitted_file_name;
    if(file_exists($target_file))
        unlink($target_file);
    if(file_exists($submitted_file))
        unlink($submitted_file);

    $stmt = $dbh -> prepare("DELETE FROM assignments WHERE primary_assignment_id = :current_assignment_id");
    $stmt -> bindParam(':current_assignment_id' , $aid);
    $stmt -> execute();
    
    //delete the corresponding notification
    $stmt = $dbh -> prepare("DELETE FROM notifications WHERE notification_assignment_id = :current_assignment_id");
    $stmt -> bindParam(':current_assignment_id' , $aid);
    $stmt -> execute();

    //print_r($_FILES);
    header('Location: ../web_front/advisor/home.php');
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;
?>