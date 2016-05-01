<?php
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 1))
{
    die("Unauthorized access. Please return to the login page.");
} 
require "../../backend/db_config.php";

$advisor_id = $_SESSION["user_id"];
$notifications = array();
$notifications_hyperlink_array = array();
$assignments_id_results = array();
$teams_id_results = array();

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get all notifications for student, ordered by creation date
    $stmt = $dbh->prepare("SELECT * FROM notifications WHERE notification_advisor_id = :notification_advisor_id");
    $stmt->bindParam(':notification_advisor_id', $advisor_id);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    
    for ($i = 0; $i < count($notifications); $i++)
    {
        $notification_assignment_id = $notifications[$i]["notification_assignment_id"];
        
        //Get assignment team
        $stmt = $dbh->prepare("SELECT assignment_team_id FROM assignments WHERE primary_assignment_id = :primary_assignment_id");
        $stmt->bindParam(':primary_assignment_id', $notification_assignment_id);
        $stmt->execute();
        $assignment_team_id = $stmt->fetchColumn();
        
        //Get all assignments for a team using assignment_team_id
        $stmt = $dbh->prepare("SELECT junction_team_id FROM teams_advisors_junction WHERE junction_advisor_id = :advisor_id");
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt -> execute();
        $teams_id_results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        for($k = 0; $k < count($teams_id_results); $k++)
        {   
            if ($teams_id_results[$k] == $assignment_team_id)
            {
                $notification_index1 = $k;
                $notification_hyperlink = "assignmentdetail.php?index1=" . $notification_index1;
                array_push($notifications_hyperlink_array, $notification_hyperlink);
                break;
            }
        }
        
        //Get all assignments for a team using assignment_team_id
        $stmt = $dbh->prepare("SELECT primary_assignment_id FROM assignments WHERE assignment_team_id = :team_id");
        $stmt->bindParam(':team_id', $teams_id_results[$k]);
        $stmt -> execute();
        $assignments_id_results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        for($j = 0; $j < count($assignments_id_results); $j++)
        {   
            if ($assignments_id_results[$j] == $notification_assignment_id)
            {
                $notification_index2 = $j;
                $notification_hyperlink = $notification_hyperlink . "&index2=" . $notification_index2;
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

function displayAdvisorNotifcations()
{
    global $notifications;
    echo "<ul>";
    for ($i = count($notifications) -1; $i >= 0; $i--)
    {
        echo "<li id=\"notif" . $notifications[$i]["notification_id"] . "\">";
        echo "<div class=\"sampleNotification\">";
            echo "<a href=\"" . $notifications[$i]["notification_hyperlink"] . "\" >";
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