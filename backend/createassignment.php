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
    $teams_array = $_POST["teams"];
    $reference_file_name = $_FILES["fileToUpload"]["name"];
}

//print_r($_POST);

try 
{
    if(!empty($teams_array))
    {
        for ($i=0; $i < count($teams_array); $i++) 
        { 
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //get id from teams table
            $stmt = $dbh->prepare("SELECT primary_team_id FROM teams WHERE project_name = :project_name");
            $stmt->bindParam(':project_name', $teams_array[$i]);
            $stmt->execute();

            $assignment_team_id = $stmt->fetch(PDO::FETCH_ASSOC)["primary_team_id"];
            $reference_file_location = $current_year_path . $assignment_team_id;

            if (!empty($_FILES["fileToUpload"]["name"]))
            {
            	$uploadOk = 1;
            	$target_file = $reference_file_location . "/" . basename($_FILES["fileToUpload"]["name"]);
            	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

            	// Check if file already exists
	            if (file_exists($target_file)) 
	            {
	                echo "Sorry, your reference file already exists. Please name it something else.\n";
	                $uploadOk = 0;
	            }
	            // Check file size
	            if ($_FILES["fileToUpload"]["size"] > 500000) 
	            {
	                echo "Sorry, your file is too large.\n";
	                $uploadOk = 0;
	            }
	            if(empty($teams_array))
	            {
	                echo "No team selected\n";
	                $uploadOk = 0;
	            }

	            // Check if $uploadOk is set to 0 by an error
	            if ($uploadOk == 0) 
	            {
	                echo "Sorry, your file was not uploaded.\n";
	                break;
	            // if everything is ok, try to upload file
	            } 
	            else
	            {
	            	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
                	{
                    	echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                	}
	            }
            }
            //We're good to upload

            if (!empty($_FILES["fileToUpload"]["name"]))
            {
            	$stmt = $dbh->prepare("INSERT INTO assignments (name, due_date, description, point_total, reference_location, reference_file_name, assignment_team_id) VALUES (:name, :due_date, :description, :point_total, :reference_location, :reference_file_name, :assignment_team_id)");
            	$stmt->bindParam(':name', $assignment_name);
            	$stmt->bindParam(':due_date', $due_date);
            	$stmt->bindParam(':description', $description);
            	$stmt->bindParam(':point_total', $point_total);
            	$stmt->bindParam(':reference_location', $reference_file_location);
            	$stmt->bindParam(':reference_file_name', $reference_file_name);
            	$stmt->bindParam(':assignment_team_id', $assignment_team_id);
            	$stmt->execute();
            }
            else
            {
            	$stmt = $dbh->prepare("INSERT INTO assignments (name, due_date, description, point_total, assignment_team_id) VALUES (:name, :due_date, :description, :point_total, :assignment_team_id)");
            	$stmt->bindParam(':name', $assignment_name);
            	$stmt->bindParam(':due_date', $due_date);
            	$stmt->bindParam(':description', $description);
            	$stmt->bindParam(':point_total', $point_total);
            	$stmt->bindParam(':assignment_team_id', $assignment_team_id);
            	$stmt->execute();
            }

            $aid = $dbh->lastInsertId();

            /* Start notifications */

            $notification_title = "New Assignment";
            $notification_text = $assignment_name . " has been created!";
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
        }
    }
    //header('Location: ../web_front/advisor/home.php'); 
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

?>