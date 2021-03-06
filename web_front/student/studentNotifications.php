<?php
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 0))
{
    die("Unauthorized access. Please return to the login page.");
} 
require "../../backend/db_config.php";

$student_id = $_SESSION["user_id"];
$notifications = array();
$notifications_hyperlink_array = array();
$assignments_id_results = array();

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get all notifications for student, ordered by creation date
    $stmt = $dbh->prepare("SELECT * FROM notifications WHERE notification_student_id = :notification_student_id");
    $stmt->bindParam(':notification_student_id', $student_id);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //Get student team
    $stmt = $dbh->prepare("SELECT students_team_id FROM students WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $assignment_team_id = $stmt->fetchColumn();
        

    for ($i = 0; $i < count($notifications); $i++)
    {
        $notification_assignment_id = $notifications[$i]["notification_assignment_id"];
        //Get all assignments for a team using assignment_team_id
        $stmt = $dbh->prepare("SELECT primary_assignment_id FROM assignments WHERE assignment_team_id = :team_id");
        $stmt->bindParam(':team_id', $assignment_team_id);
        $stmt -> execute();
        $assignments_id_results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        for($j = 0; $j < count($assignments_id_results); $j++)
        {   
            if ($assignments_id_results[$j] == $notification_assignment_id)
            {
                $notification_index = $j;
                $notification_hyperlink = "assignmentdetail.php?table_index=" . $notification_index;
                array_push($notifications_hyperlink_array, $notification_hyperlink);
                break;
            }
        }
    }  

}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

function displayStudentNotifcations()
{
	global $notifications;
    global $notifications_hyperlink_array;

	echo "<ul>";
    for ($i = count($notifications) -1; $i >= 0; $i--)
    {
        echo "<li id=\"notif" . $notifications[$i]["notification_id"] . "\">";
        echo "<div class=\"sampleNotification\">";
            echo "<a href=\"" . $notifications_hyperlink_array[$i] . "\" >";
            echo "<p class=\"bodyStyle\">" . $notifications[$i]["notification_title"] . "</p>";
            echo "<p class=\"bodyDetail\">" . $notifications[$i]["notification_text"] . "</p>";
            echo "</a>";
        echo "</div>";
        echo "<div class=\"sampleNotificationMenu\">";
            echo "<p><a class=\"eraseNotification\" onmousedown=\"removeNotification('#notif" . $notifications[$i]["notification_id"] . "');\">x</a></p>";
        echo "</div>";
        echo "</li>";
    }  
    echo "</ul>";  
}

?> 