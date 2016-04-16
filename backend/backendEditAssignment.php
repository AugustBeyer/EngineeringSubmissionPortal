<?php
session_start();
require "db_config.php";

$current_year_path = "/DCNFS/web/esp/2016/";

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST')
{
    $assignment_name = $_POST["assignment_name"];
    $due_date = $_POST["due_date"];
    $description = $_POST["description"];
    $point_total = $_POST["point_total"];
    $reference_file_name = $_FILES["fileToUpload"]["name"];
}

$aid = htmlspecialchars($_GET["aid"]);
print_r($_POST);
echo $aid;
/*
try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $target_file = $reference_file_location . "/" . basename($_FILES["fileToUpload"]["name"]);
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
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) 
    {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) 
    {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) 
    {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } 
    // Check if teams array is empty
    if(empty($teams_array))
    {
        echo "No team selected";
    }
    else 
    {
        echo $target_file;
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
        {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } 
        else 
        {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    //insert entry into assignments table
    $stmt = $dbh->prepare("INSERT INTO assignments (name, due_date, description, point_total, reference_location, reference_file_name, assignment_team_id)
VALUES (:name, :due_date, :description, :point_total, :reference_location, :reference_file_name, :assignment_team_id)");
    $stmt->bindParam(':name', $assignment_name);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':point_total', $point_total);
    $stmt->bindParam(':reference_location', $reference_file_location);
    $stmt->bindParam(':reference_file_name', $reference_file_name);
    $stmt->bindParam(':assignment_team_id', $assignment_team_id);
    $stmt->execute();

    echo "New records created successfully\r\n";
    header('Location: ../web_front/advisor/home.php'); //FuckPHP5
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

*/
?>