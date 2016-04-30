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

            /* Start notifications */

            //Get all assignments for a team using assignment_team_id
            $stmt = $dbh->prepare("SELECT primary_assignment_id FROM assignments WHERE assignment_team_id = :team_id");
            $stmt->bindParam(':team_id', $assignment_team_id);
            $stmt -> execute();
            $assignments_id_results = array();
            $assignments_id_results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $notification_index_2 = count($assignments_id_results) - 1;

            $notification_title = "New Assignment";
            $notification_text = $assignment_name . " has been created";

            //advisors
            $notification_advisor_ids = array();
            $stmt = $dbh->prepare("SELECT junction_advisor_id FROM teams_advisor_junction WHERE junction_team_id = :junction_team_id");
            $stmt->bindParam(':junction_team_id', $assignment_team_id);
            $stmt->execute();
            $notification_advisor_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            for($i = 0; $i < count($notification_advisor_ids); $i++)
            {

                //Get all junction_team_ids from teams_advisors_junction table using advisor_id
                $stmt = $dbh->prepare("SELECT junction_team_id FROM teams_advisors_junction WHERE junction_advisor_id = :advisor_id");
                $stmt->bindParam(':advisor_id', $advisor_id);
                $stmt -> execute();
                $advisor_teams = array();
                $advisor_teams = $stmt->fetchAll(PDO::FETCH_COLUMN);

                for($j = 0; $j < count($advisor_teams); $j++)
                {
                    if ($advisor_teams[$j] == $assignment_team_id)
                    {
                        $notification_index_1 = $j;
                        break;
                    }
                }

                $notification_hyperlink = "assignmentdetail.php?index1=" . $notification_index_1 . "&index2=" . $notification_index_2;
                $notification_advisor_id = $notification_advisor_ids[$i];
                $notification_due_date = NULL;
                $stmt = $dbh->prepare("INSERT INTO notifications (notification_title, notification_text, notification_hyperlink, notification_advisor_id, notification_due_date) VALUES (:notification_title, :notification_text, :notification_hyperlink, :notification_advisor_id, :notification_due_date)");
                $stmt->bindParam(':notification_title', $notification_title);
                $stmt->bindParam(':notification_text', $notification_text);
                $stmt->bindParam(':notification_hyperlink', $notification_hyperlink);
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
                $notification_hyperlink = "assignmentdetail.php?table_index=" . $notification_index_2;
                $notification_student_id = $notification_student_ids[$i];
                $notification_due_date = NULL;
                $stmt = $dbh->prepare("INSERT INTO notifications (notification_title, notification_text, notification_hyperlink, notification_advisor_id, notification_due_date) VALUES (:notification_title, :notification_text, :notification_hyperlink, :notification_advisor_id, :notification_due_date)");
                $stmt->bindParam(':notification_title', $notification_title);
                $stmt->bindParam(':notification_text', $notification_text);
                $stmt->bindParam(':notification_hyperlink', $notification_hyperlink);
                $stmt->bindParam(':notification_student_id', $notification_student_id);
                $stmt->bindParam(':notification_due_date', $notification_due_date);
                $stmt->execute();
            }

            /* End notifications */

            echo "New records created successfully\r\n";
        }
    }
    header('Location: ../web_front/advisor/home.php'); //FuckPHP5
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;


?>