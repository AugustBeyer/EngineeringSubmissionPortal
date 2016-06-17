<?php
session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 0))
{
    die("Unauthorized access. Please return to the login page.");
}  
require "../../backend/db_config.php";
require "../../backend/system_config.php";

$student_id = $_SESSION["user_id"];
$assignment_id = htmlspecialchars($_GET["assignment_id"]);
echo $assignment_id;
$current_assignment = array();
date_default_timezone_set('America/Los_Angeles');

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare("SELECT * FROM assignments WHERE primary_assignment_id = :primary_assignment_id");
    $stmt->bindParam(':primary_assignment_id', $assignment_id);
    $stmt -> execute();
    $current_assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    $submitted_file_location = $current_year_path . $current_assignment["assignment_team_id"];

    $target_file = $submitted_file_location . "/" . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

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
        chmod($target_file, 0755);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
        {
            echo "Success!\n";

            //Get rid of old assignment if resubmission
            if($current_assignment["submitted_flag"])
            {
                unlink($current_assignment["submitted_location"]."/".$current_assignment["submitted_file_name"]);
            }

            $submitted_flag = 1;
            $submitted_location = $current_year_path . $current_assignment["assignment_team_id"];
            $submitted_file_name = basename($_FILES["fileToUpload"]["name"]);
            $submitted_time = date('Y/m/d H:i:s');

            $stmt = $dbh -> prepare("UPDATE assignments SET submitted_flag = :submitted_flag, submitted_location = :submitted_location, submitted_file_name = :submitted_file_name, submitted_time = :submitted_time WHERE primary_assignment_id = :current_assignment_id");
            $stmt -> bindParam(':submitted_flag', $submitted_flag);
            $stmt -> bindParam(':submitted_location', $submitted_location);
            $stmt -> bindParam(':submitted_file_name', $submitted_file_name);
            $stmt -> bindParam(':submitted_time', $submitted_time);
            $stmt -> bindParam(':current_assignment_id' , $current_assignment["primary_assignment_id"]);
            $stmt -> execute();

            /* Start notifications */

            $assignment_team_id = $current_assignment["assignment_team_id"];
            $notification_title = "Assignment Uploaded";
            $notification_text = "Deliverable for " . $current_assignment["name"] . " has been uploaded!";
            $notification_assignment_id = $current_assignment["primary_assignment_id"];
            $notification_due_date = NULL;

            //advisors
            $notification_advisor_ids = array();
            $stmt = $dbh->prepare("SELECT junction_advisor_id FROM teams_advisors_junction WHERE junction_team_id = :junction_team_id");
            $stmt->bindParam(':junction_team_id', $assignment_team_id);
            $stmt->execute();
            $notification_advisor_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            for($i = 0; $i < count($notification_advisor_ids); $i++)
            {
                $notification_advisor_id = $notification_advisor_ids[$i];
                $stmt = $dbh->prepare("INSERT INTO notifications (notification_title, notification_text, notification_assignment_id, notification_advisor_id, notification_due_date) VALUES (:notification_title, :notification_text, :notification_assignment_id, :notification_advisor_id, :notification_due_date)");
                $stmt->bindParam(':notification_title', $notification_title);
                $stmt->bindParam(':notification_text', $notification_text);
                $stmt->bindParam(':notification_assignment_id', $notification_assignment_id);
                $stmt->bindParam(':notification_advisor_id', $notification_advisor_id);
                $stmt->bindParam(':notification_due_date', $notification_due_date);
                $stmt->execute();
            }

            //students
            $notification_student_ids = array();
            $stmt = $dbh->prepare("SELECT student_id FROM students WHERE students_team_id = :students_team_id");
            $stmt->bindParam(':students_team_id', $assignment_team_id);
            $stmt->execute();
            $notification_student_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            for($i = 0; $i < count($notification_student_ids); $i++)
            {
                $notification_student_id = $notification_student_ids[$i];
                $stmt = $dbh->prepare("INSERT INTO notifications (notification_title, notification_text, notification_assignment_id, notification_student_id, notification_due_date) VALUES (:notification_title, :notification_text, :notification_assignment_id, :notification_student_id, :notification_due_date)");
                $stmt->bindParam(':notification_title', $notification_title);
                $stmt->bindParam(':notification_text', $notification_text);
                $stmt->bindParam(':notification_assignment_id', $notification_assignment_id);
                $stmt->bindParam(':notification_student_id', $notification_student_id);
                $stmt->bindParam(':notification_due_date', $notification_due_date);
                $stmt->execute();
            }

            /* End notifications */

            header("Location: home.php");
        } 
        else 
        {
            echo "Sorry, there was an error uploading your file.\n";
        }
    }
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

?>