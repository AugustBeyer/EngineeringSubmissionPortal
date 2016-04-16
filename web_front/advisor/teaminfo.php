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
$student_name_array = array();
$team_id_array = array();

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get junction_team_id from teams_advisors_junction table using advisor_id
    $stmt = $dbh->prepare("SELECT junction_team_id FROM teams_advisors_junction WHERE junction_advisor_id = :advisor_id");
    $stmt->bindParam(':advisor_id', $advisor_id);
    $stmt -> execute();
    $team_id_array = $stmt->fetchAll(PDO::FETCH_COLUMN);

    for ($i=0; $i < count($team_id_array); $i++) 
    {

        //check if result is null
        if(is_null($team_id_array[$i]))
            continue;

        //Get project_name using junction_team_id from teams table
        $stmt = $dbh->prepare("SELECT project_name FROM teams WHERE primary_team_id = :team_id");
        $stmt->bindParam(':team_id', $team_id_array[$i]);
        $stmt -> execute();
        $project_name_result = $stmt->fetch(PDO::FETCH_ASSOC);
        array_push($project_name_array, $project_name_result["project_name"]);

        //Get scu_username array using junction_team_id from students table
        $stmt = $dbh->prepare("SELECT scu_username FROM students WHERE students_team_id = :team_id");
        $stmt->bindParam(':team_id', $team_id_array[$i]);
        $stmt -> execute();
        $students_name_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        array_push($student_name_array, $students_name_result);
    }
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

function printTeamList()
{
    global $project_name_array;
    global $student_name_array;
     echo "<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->";
                echo "<thead>";
                echo "<tr>";
                    echo "<th>Team name</th>";
                    echo "<th>Team members</th>";
                    echo "<th>Edit this team</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
    //Iterate through project_name_array for the size of both arrays
    for ($i=0; $i < count($project_name_array); $i++) { 
        //check if project_name_array index is null
        if( (is_null($project_name_array[$i])) || (count($student_name_array[$i]) == 0) )
            continue;
        //echo out html
        echo "<tr><td><a href =\"teamdetail.php?index1=". $i ."\"><div class=\"tdlink\"> ". $project_name_array[$i] . "</div></a></td>";
        //echo "<ul class=\"studentTeamList\">";
        //echo out html for each student scu_username
        echo "<td>";
        for ($j =0; $j < count($student_name_array[$i]); $j++){
            if ($j!= 0)
                echo ", ";
            echo $student_name_array[$i][$j]["scu_username"];
        }
        echo "</td><td><a href=\"http://www.niceme.me\"><div class=\"tdlink\">click to edit</div></a></td></tr>";
        //echo "</ul>";
    }
    echo "</tbody></table>";
}

function printTeamNavBar()
{
    global $project_name_array;
    global $student_name_array;
    //Iterate through project_name_array for the size of both arrays
    for ($i=0; $i < count($project_name_array); $i++) { 
        
        //check if project_name_array index is null
        if( (is_null($project_name_array[$i])) || (count($student_name_array[$i]) == 0) )
            continue;
        //echo out html
        echo "<a href =\"teamdetail.php?index1=". $i ."\">". $project_name_array[$i] . "</a>";
    }
}

function printTeamCheckboxes()
{
    global $project_name_array;
    global $student_name_array;
    //Iterate through project_name_array for the size of both arrays
    for ($i=0; $i < count($project_name_array); $i++) { 
        
        //check if project_name_array index is null
        if( (is_null($project_name_array[$i])) || (count($student_name_array[$i]) == 0) )
            continue;
        //echo out html
        echo $project_name_array[$i] . ": <input type=\"checkbox\" id=\"myCheck" . $i ."\" name=\"teams[]\" value=\"" . $project_name_array[$i] ."\"><br>";
    }
}

function printTeamInfo($i)
{
    global $project_name_array;
    global $student_name_array;
    global $team_id_array;

    echo "<h4>Student Team Members:</h4>";
    //echo "<ul class=\"studentTeamList\">";
    for ($j=0; $j < count($student_name_array[$i]); $j++) { 
        echo "<p>" . $student_name_array[$i][$j]["scu_username"] . "</p>";
    }
    //echo "</ul>";
    echo "<br>";
     echo "<button class=\"form_button\"><a href=\"editTeam.php?tid=". $team_id_array[$i] . "\">Edit This Team</a></button>";
}

?>