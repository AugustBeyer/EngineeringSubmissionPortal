<?php
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 0))
{
    die("Unauthorized access. Please return to the login page.");
} 
require "../../backend/db_config.php";

$student_id = $_SESSION["user_id"];
$project_name = null;
$assignment_info_array = array();

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get junction_team_id from teams_advisors_junction table using advisor_id
    $stmt = $dbh->prepare("SELECT students_team_id FROM students WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);
    $stmt -> execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!(is_null($result["students_team_id"])))
        {
            $stmt = $dbh -> prepare("SELECT project_name FROM teams WHERE primary_team_id = :students_team_id");
            $stmt->bindParam(':students_team_id', $result["students_team_id"]);
            $stmt->execute();
            $project_name = $stmt ->fetch(PDO::FETCH_ASSOC)["project_name"];
            //Get assignment_info array using junction_team_id from assignments table
            $stmt = $dbh->prepare("SELECT * FROM assignments WHERE assignment_team_id = :team_id");
            $stmt->bindParam(':team_id', $result["students_team_id"]);
            $stmt -> execute();
            $assignment_info_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

function printAssignmentTable()
{
	global $project_name;
    global $assignment_info_array;

	
    //echo out html
   if($project_name == "")
   {
       
   }
    else if(count($assignment_info_array) == 0)
    {
        echo "<h4>". $project_name . "</h4>";
        echo "<p><i>--- No Assignments To View ---</i></p>";
    }
    else{
        echo "<h1>Assignments</h1><br><p>Click on any assignment to view more information</p>";
        echo "<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->";
        echo "<h4>". $project_name . "</h4>";
        echo "<thead>";
            echo "<tr>";
                echo "<th>Assignment</th>";
                echo "<th>Due Date</th>";
                echo "<th>Submitted</th>";
                echo "<th>Grade</th>";
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        //Iterate through project_name_array for the size of both arrays
        if(is_null($project_name)) 
        {   
            echo "</tbody>";
            echo "</table>";
        }
        else
        {
            for ($i =0; $i < count($assignment_info_array); $i++)
            {
            	if($i % 2 == 0)
                	echo "<tr class = \"even\">";
                else
                	echo "<tr>";
                    //Assignment Link
                    echo "<td><a href=assignmentdetail.php?table_index=" . $i . ">";

                    //Assignment Name
                    echo $assignment_info_array[$i]["name"];
                    echo "</a></td>";

                    //Due Date
                    echo "<td><i>" . $assignment_info_array[$i]["due_date"] . "</i></td>";

                    //Submitted Time
                    if(is_null($assignment_info_array[$i]["submitted_time"]))
                    	echo "<td> --- </td>";
                    else
                    	echo "<td><i>" . $assignment_info_array[$i]["submitted_time"] . "</i></td>";

                    //Grade
                    if(is_null($assignment_info_array[$i]["point_total"]))
                        echo "<td><i>---</i></td>";
                    else if (is_null($assignment_info_array[$i]["points_given"]))
                        echo "<td><i> ---/" . $assignment_info_array[$i]["point_total"] . "</i></td>";
                    else
                        echo "<td><i>" . $assignment_info_array[$i]["points_given"] . "/" . $assignment_info_array[$i]["point_total"] . "</i></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
    }

}

function printAssignmentDetail($i)
{
	global $assignment_info_array;
	$current_assignment = $assignment_info_array[$i];
    //print_r($current_assignment);

    //Assignment Name
    echo "<h1>" . $current_assignment["name"] . "</h1>";
    echo "<br>";

    //Due Date
    echo "<p>Due Date: <i>" . $current_assignment["due_date"] . "</i></p>";
    
    //Submitted date
    if (is_null($current_assignment["submitted_time"]))
     	echo "<p>Submitted on: <i>---</i></p>";
    else
     	echo "<p>Submitted on: <i>" . $current_assignment["submitted_time"] . "</i></p>";
    
//Grade
    if(is_null($current_assignment["point_total"]))
        echo "<p>Grade: <i>---</i></p>";
    else if (is_null($current_assignment["points_given"]))
        echo "<p>Grade: <i> ---/" . $current_assignment["point_total"] . "</i></p>";
    else
        echo "<p>Grade: <i>" . $current_assignment["points_given"] . "/" . $current_assignment["point_total"] . "</i></p>";

    //Assignment Description
    echo "<br>";
    echo "<h3>Assignment Description</h3>";
    echo "<hr class= \"detailLine\">";
    echo "<p class= \"assignDetail\">". $current_assignment["description"];
    
    //File Formats
    echo "<p>Accepted File Formats: " . $current_assignment["file_format"] . "</p>";

    //If advisor included a reference file display it here
    if (!empty(($current_assignment["reference_file_name"])))
    {
        echo "<h3>Download Reference</h3>";
        echo "<hr class=\"detailLine\">"; 

        echo "<form action=\"../../backend/downloadfile.php?tid=" . $current_assignment["assignment_team_id"] . "&name=" . $current_assignment["reference_file_name"] . "\" method=\"post\" enctype=\"multipart/form-data\">";
        echo "<p>Download file</p>";
        echo "<input class=\"newAssignmentButton\" type=\"submit\" name=\"Download\" value=\"Download\" >";
        echo "</form>";
    }

    //If an assignment was already uploaded
    if (!empty(($current_assignment["submitted_file_name"])))
    {
        echo "<h3>Download Your File</h3>";
        echo "<hr class=\"detailLine\">"; 

        echo "<form action=\"../../backend/downloadfile.php?tid=" . $current_assignment["assignment_team_id"] . "&name=" . $current_assignment["submitted_file_name"] . "\" method=\"post\" enctype=\"multipart/form-data\">";
        echo "<p>Download file</p>";
        echo "<input class=\"newAssignmentButton\" type=\"submit\" name=\"Download\" value=\"Download\" >";
        echo "</form>";
    }

    //Upload deliverable 
    echo "<h3>Upload Your Assignment</h3>";
    echo "<hr class=\"detailLine\">";
    echo "<form action=\"uploadassignment.php?assignment_id=".$current_assignment["primary_assignment_id"]." \" method=\"post\" enctype=\"multipart/form-data\">";
    echo "<p>Select a file to upload:</p>";
    echo "<input class=\"newAssignmentButton\" type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\">";
    echo "<input class=\"newAssignmentButton\" type=\"submit\" value=\"TurnIn\" name=\"submit\">";
}
