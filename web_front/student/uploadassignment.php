<?php
session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 0))
{
    die("Unauthorized access. Please return to the login page.");
}  
require "../../backend/db_config.php";

$current_year_path = "/DCNFS/web/esp/2016/";

$student_id = $_SESSION["user_id"];
$assignment_id = htmlspecialchars($_GET["assignment_id"]);
echo $assignment_id;
$current_assignment = array();
date_default_timezone_set('America/Los_Angeles');

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get junction_team_id from teams_advisors_junction table using advisor_id
    $stmt = $dbh->prepare("SELECT * FROM assignments WHERE primary_assignment_id = :primary_assignment_id");
    $stmt->bindParam(':primary_assignment_id', $assignment_id);
    $stmt -> execute();
    $current_assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if($current_assignment["submitted_flag"]) //This was some serious bullshit
    {
        unlink($current_assignment["submitted_location"]."/".$current_assignment["submitted_file_name"]);
    }

    $fuckPHP1 = 1;
    $fuckPHP2 = $current_year_path . $current_assignment["assignment_team_id"];
    $fuckPHP3 = basename($_FILES["fileToUpload"]["name"]);
    $fuckPHP4 = date('Y/m/d H:i:s');

    $stmt = $dbh -> prepare("UPDATE assignments SET submitted_flag = :submitted_flag, submitted_location = :submitted_location, submitted_file_name = :submitted_file_name, submitted_time = :submitted_time WHERE primary_assignment_id = :current_assignment_id");
    $stmt -> bindParam(':submitted_flag', $fuckPHP1);
    $stmt -> bindParam(':submitted_location', $fuckPHP2);
    $stmt -> bindParam(':submitted_file_name', $fuckPHP3);
    $stmt -> bindParam(':submitted_time', $fuckPHP4);
    $stmt -> bindParam(':current_assignment_id' , $current_assignment["primary_assignment_id"]);
    $stmt -> execute();
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

$submitted_file_location = $current_year_path . $current_assignment["assignment_team_id"];

$target_file = $submitted_file_location . "/" . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) 
{
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) 
    {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } 
    else 
    {
        echo "File is not an image.\n";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) 
{
    echo "Sorry, file already exists.\n";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) 
{
    echo "Sorry, your file is too large.\n";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) 
{
    echo "Sorry, your file was not uploaded.\n";
// if everything is ok, try to upload file
} 
else 
{
    echo $target_file;
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
    {
        echo "Success!\n";
        header("Location: home.php");
    } 
    else 
    {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>