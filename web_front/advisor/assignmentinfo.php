<?php
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 1))
{
    die("Unauthorized access. Please return to the login page.");
} 
require "../../backend/db_config.php";

$advisor_id = $_SESSION["user_id"];
$project_name_array = array();
$assignment_info_array = array();

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get junction_team_id from teams_advisors_junction table using advisor_id
    $stmt = $dbh->prepare("SELECT junction_team_id FROM teams_advisors_junction WHERE junction_advisor_id = :advisor_id");
    $stmt->bindParam(':advisor_id', $advisor_id);
    $stmt -> execute();
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

    for ($i=0; $i < count($result); $i++) 
    {

        //check if result is null
        if(is_null($result[$i]))
            continue;

        //Get project_name using junction_team_id from teams table
        $stmt = $dbh->prepare("SELECT project_name FROM teams WHERE primary_team_id = :team_id");
        $stmt->bindParam(':team_id', $result[$i]);
        $stmt -> execute();
        $project_name_result = $stmt->fetch(PDO::FETCH_ASSOC);
        array_push($project_name_array, $project_name_result["project_name"]);

        //Get assignment_info array using junction_team_id from assignments table
        $stmt = $dbh->prepare("SELECT * FROM assignments WHERE assignment_team_id = :team_id");
        $stmt->bindParam(':team_id', $result[$i]);
        $stmt -> execute();
        $assignments_info_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        array_push($assignment_info_array, $assignments_info_result);
    }
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

function printSingleAssignmentTable($i)
{
    global $project_name_array;
    global $assignment_info_array;

    //check if project_name_array index is null
        if( (is_null($project_name_array[$i])) )
            return;
        //echo out html
        if(count($assignment_info_array[$i]) == 0)
        {
            echo "<h4>". $project_name_array[$i] . "</h4>";
            echo "<p><i>--- No Assignments To View ---</i></p>";
        }
        else
        {
        echo "<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->";
            echo "<h4>". $project_name_array[$i] . "</h4>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>Assignment</th>";
                    echo "<th>Due Date</th>";
                    echo "<th>Submitted</th>";
                    echo "<th>Grade</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            for ($j =0; $j < count($assignment_info_array[$i]); $j++){
                if($j % 2 == 0)
                    echo "<tr class = \"even\">";
                else
                    echo "<tr>";
                    echo "<td><a href=assignmentdetail.php?index1=" . $i;
                    echo "&index2=". $j . ">";
                    echo $assignment_info_array[$i][$j]["name"];
                    echo "</a></td>";
                    echo "<td><i>" . $assignment_info_array[$i][$j]["due_date"] . "</i></td>";
                    if(is_null($assignment_info_array[$i][$j]["submitted_time"]))
                        echo "<td> --- </td>";
                    else
                        echo "<td><i>" . $assignment_info_array[$i][$j]["submitted_time"] . "</i></td>";
                    echo "<td>" . $assignment_info_array[$i][$j]["point_total"] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
}

function printAssignmentTable()
{
    global $project_name_array;
    global $assignment_info_array;

    
    //Iterate through project_name_array for the size of both arrays
    for ($i=0; $i < count($project_name_array); $i++) { 

        printSingleAssignmentTable($i);
        
    }
}

function printAssignmentDetail($i, $j)
{
	global $assignment_info_array;
	$current_assignment = $assignment_info_array[$i][$j];

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
    else
        echo "<p>Grade: <i>" . $current_assignment["point_total"] . "</i></p>";

    //Assignment Description
    echo "<br>";
    echo "<h3>Assignment Description</h3>";
    echo "<hr class= \"detailLine\">";
    echo "<p class= \"assignDetail\">". $current_assignment["description"];
    echo "<br>";

    /*
    echo "<h3>Additional Files</h3>";
    echo "<hr class=\"detailLine\">";
    echo "<br>";
    */

    //If advisor included a reference file display it here for download
    if (!empty(($current_assignment["reference_file_name"])))
    {
        echo "<h3>Download Reference</h3>";
        echo "<hr class=\"detailLine\">"; 

        echo "<form action=\"../../backend/downloadfile.php?tid=" . $current_assignment["assignment_team_id"] . "&name=" . $current_assignment["reference_file_name"] . "\" method=\"post\" enctype=\"multipart/form-data\">";
        echo "<p>Download file</p>";
        echo "<input class=\"newAssignmentButton\" type=\"submit\" name=\"Download\" value=\"Download\" >";
        echo "</form>";
    }

    //If assignment has been submitted display it here for download
    if (!(empty($current_assignment["submitted_file_name"])))
    {
        echo "<h3>Download Deliverable</h3>";
        echo "<hr class=\"detailLine\">"; 

        echo "<form action=\"../../backend/downloadfile.php?tid=" . $current_assignment["assignment_team_id"] . "&name=" . $current_assignment["submitted_file_name"] . "\" method=\"post\" enctype=\"multipart/form-data\">";
        echo "<p>Download file</p>";
        echo "<input class=\"newAssignmentButton\" type=\"submit\" name=\"Download\" value=\"Download\" >";
        echo "</form>";
    }
}

function printEditAssignmentDetail($i, $j)
{
    global $assignment_info_array;
    $current_assignment = $assignment_info_array[$i][$j];

    echo "<form action=\"../../backend/backendEditAssignment.php?aid=". $current_assignment["primary_assignment_id"] ."\" method=\"post\" id=\"usrform\" enctype=\"multipart/form-data\">";

    echo "<h1>Edit an Assignment</h1>";

    //Assignment Name
    echo "Assignment Name: *<input class=\"form_field\" type = \"text\" name = \"assignment_name\" value = \"" . $current_assignment["name"] . "\"<br><br>";

    //Due Date
    echo "Due Date: *<input class=\"form_field\" type = \"date\" name = \"due_date\"value = \"" . $current_assignment["due_date"] . "\"> <br><br>";

    //Due Time
    echo "Due Time: *<input class=\"form_field\" type = \"time\" name=\"due_time\"> <br><br>";
    
    //Point Total
    echo "Point Total: *<input class=\"form_field\" type = \"number\" name = \"point_total\"value = \"" . $current_assignment["point_total"] . "\"> <br><br>";

    //Reference File
    echo "Reference File: <input class=\"form_file\" type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\" value = \"" . $current_assignment["reference_file_name"] . "\"> <br><br>";

    //Description
    echo "Description:*<br>";
    echo "<textarea  class=\"form_field\" rows=\"4\" cols=\"50\" name=\"description\" form=\"usrform\">";
    echo $current_assignment["description"] . "</textarea><br>";

    echo "</form>";
        echo "<p style=\"font-size: 8pt;\"><i>a * indicates a required field</i></p>";
        echo "<br>";
    echo "<input type = \"submit\" value = \"update\" class=\"form_button\" form=\"usrform\">";

}

function deleteAssignment($i, $j)
{
    global $assignment_info_array;
    $current_assignment = $assignment_info_array[$i][$j];
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/advisor.css\">";
    echo "<p>Are you sure you want to delete this assignment</p>";
    echo "<form action=\"../../backend/backendDeleteAssignment.php?aid=". $current_assignment["primary_assignment_id"] ."\" method=\"post\" id=\"usrform\" enctype=\"multipart/form-data\">";
    echo "</form>";
    echo "<input type = \"submit\" value = \"update\" form=\"usrform\" class=\"form_button\">";
}

?>