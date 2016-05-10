<?php
session_start();
require "db_config.php";
require "system_config.php";

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST')
{
    $assignment_name = $_POST["assignment_name"];
    $due_date = $_POST["due_date"];
    $description = $_POST["description"];
    $point_total = $_POST["point_total"];
    $points_given = $_POST["points_given"];
    $reference_file_name = $_FILES["fileToUpload"]["name"];
}

$aid = htmlspecialchars($_GET["aid"]);
try 
{

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare("SELECT reference_location, assignment_team_id FROM assignments WHERE primary_assignment_id = :aid");
    $stmt->bindParam(':aid', $aid);
    $stmt->execute();

    $assignment_team_id = $stmt->fetch(PDO::FETCH_ASSOC)["assignment_team_id"];
    $reference_file_location = $stmt->fetch(PDO::FETCH_ASSOC)["reference_location"];

    $uploadOk = 1;
    if(!empty($reference_file_name))
    {
        $target_file = $reference_file_location . "/" . basename($_FILES["fileToUpload"]["name"]);
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
        //Make sure at least one team is selected
        if(empty($teams_array))
        {
            echo "No team selected.\n";
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) 
        {
            echo "Sorry, your file was not uploaded.\n";
        }
        else 
        {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
            {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } 
        }
    }

    if (!empty($reference_file_name))
    {
        $stmt = $dbh -> prepare("UPDATE assignments SET name = :name, due_date = :due_date, description = :description, point_total = :point_total, points_given = :points_given, reference_file_name = :reference_file_name WHERE primary_assignment_id = :current_assignment_id");
        $stmt -> bindParam(':name', $assignment_name);
        $stmt -> bindParam(':due_date', $due_date);
        $stmt -> bindParam(':description', $description);
        $stmt -> bindParam(':point_total', $point_total);
        $stmt -> bindParam(':points_given', $points_given);
        $stmt -> bindParam(':reference_file_name' , $reference_file_name);
        $stmt -> bindParam(':current_assignment_id' , $aid);
        $stmt -> execute();
    }
    else
    {
        $stmt = $dbh -> prepare("UPDATE assignments SET name = :name, due_date = :due_date, description = :description, point_total = :point_total, points_given = :points_given WHERE primary_assignment_id = :current_assignment_id");
        $stmt -> bindParam(':name', $assignment_name);
        $stmt -> bindParam(':due_date', $due_date);
        $stmt -> bindParam(':description', $description);
        $stmt -> bindParam(':point_total', $point_total);
        $stmt -> bindParam(':points_given', $points_given);
        $stmt -> bindParam(':current_assignment_id' , $aid);
        $stmt -> execute();
    }

    /* Start notifications */

    $notification_title = "Assignment Changed / Graded";
    $notification_text = $assignment_name . " has been edited";
    $notification_assignment_id = $aid;
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

    echo "New records created successfully\r\n";
    //print_r($_FILES);
    header('Location: ../web_front/advisor/home.php');
    
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;
?>