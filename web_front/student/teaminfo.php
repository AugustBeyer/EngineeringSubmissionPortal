<?php
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 0))
{
    die("Unauthorized access. Please return to the login page.");
}  
require "../../backend/db_config.php";

$student_id = $_SESSION["user_id"];
$students_name_array = array();
$advisors_array = array();
$project_name = null;

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get the student's team
    $stmt = $dbh->prepare("SELECT students_team_id FROM students WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $students_team_id = $stmt->fetch(PDO::FETCH_ASSOC)["students_team_id"];
    //print_r($students_team_id);

    //Get the project name
    $stmt = $dbh->prepare("SELECT project_name FROM teams WHERE primary_team_id = :primary_team_id");
    $stmt->bindParam(':primary_team_id', $students_team_id);
    $stmt->execute();
    $project_name = $stmt->fetch(PDO::FETCH_ASSOC)["project_name"];

    //Get everyone else on the same team as this student
    $stmt = $dbh->prepare("SELECT scu_username FROM students WHERE students_team_id = :team_id");
    $stmt->bindParam(':team_id', $students_team_id);
    $stmt->execute();
    $students_name_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($students_name_array);

    //Get the student's advisor(s)
    $stmt = $dbh->prepare("SELECT junction_advisor_id FROM teams_advisors_junction WHERE junction_team_id = :junction_team_id");
    $stmt->bindParam(':junction_team_id', $students_team_id);
    $stmt->execute();
    $advisor_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    for ($i=0; $i < count($advisor_ids); $i++) 
    {

        //check if result is null
        if(is_null($advisor_ids[$i]))
            continue;

        //Get scu_username of each advisor 
        $stmt = $dbh->prepare("SELECT advisor_username FROM advisors WHERE primary_advisor_id = :primary_advisor_id");
        $stmt->bindParam(':primary_advisor_id', $advisor_ids[$i]);
        $stmt->execute();
        $advisor_name_result = $stmt->fetch(PDO::FETCH_ASSOC)["advisor_username"];
        array_push($advisors_array, $advisor_name_result);
    }
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;


function printTeamInfo()
{
    global $project_name;
    global $students_name_array;
    global $advisors_array;

    echo "<h4><i>" . $project_name . "</i></h4>";
    echo "<br>";
    echo "<h2>About Your Team</h2>";
    echo "<h4>Student Team Members:</h4>";
    //echo "<ul class=\"studentTeamList\">";
    for ($i=0; $i < count($students_name_array); $i++) 
    { 
        echo "<p>" . $students_name_array[$i]["scu_username"] . "</p>";
    }
    //echo "</ul>";
    echo "<br>";
    echo "<h4>Team Advisor(s):</h4>";
    for ($i=0; $i < count($advisors_array); $i++) 
    { 
        echo "<p>" . $advisors_array[$i] . "</p>";
    }
    //echo "</ul>";
    echo "<br>";
    //echo "<button class=\"newAssignmentButton\"><a href=\"http://www.niceme.me\">Edit This Meme</a></button>";
}

?>