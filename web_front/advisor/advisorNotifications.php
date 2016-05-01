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

/* Start notifications */

    //Get all assignments for a team using assignment_team_id
    $stmt = $dbh->prepare("SELECT primary_assignment_id FROM assignments WHERE assignment_team_id = :team_id");
    $stmt->bindParam(':team_id', $assignment_team_id);
    $stmt -> execute();
    $assignments_id_results = array();
    $assignments_id_results = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //print_r($assignments_id_results);
    for($i = 0; $i < count($assignments_id_results); $i++)
    {
        if ($assignments_id_results[$i] == $aid)
        {
            $notification_index_2 = $i;
            break;
        }
    }

    $notification_title = "Assignment Changed / Graded";
    $notification_text = $assignment_name . " has been edited";

    //advisors
    $notification_advisor_ids = array();
    $stmt = $dbh->prepare("SELECT junction_advisor_id FROM teams_advisors_junction WHERE junction_team_id = :junction_team_id");
    $stmt->bindParam(':junction_team_id', $assignment_team_id);
    $stmt->execute();
    $notification_advisor_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    for($i = 0; $i < count($notification_advisor_ids); $i++)
    {

        //Get all junction_team_ids from teams_advisors_junction table using advisor_id
        $stmt = $dbh->prepare("SELECT junction_team_id FROM teams_advisors_junction WHERE junction_advisor_id = :advisor_id");
        $stmt->bindParam(':advisor_id', $notification_advisor_ids[$i]);
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

        $notification_hyperlink = "editAssignment.php?index1=" . $notification_index_1 . "&index2=" . $notification_index_2;
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
        $stmt = $dbh->prepare("INSERT INTO notifications (notification_title, notification_text, notification_hyperlink, notification_student_id, notification_due_date) VALUES (:notification_title, :notification_text, :notification_hyperlink, :notification_student_id, :notification_due_date)");
        $stmt->bindParam(':notification_title', $notification_title);
        $stmt->bindParam(':notification_text', $notification_text);
        $stmt->bindParam(':notification_hyperlink', $notification_hyperlink);
        $stmt->bindParam(':notification_student_id', $notification_student_id);
        $stmt->bindParam(':notification_due_date', $notification_due_date);
        $stmt->execute();
    }

    /* End notifications */

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get all notifications for student, ordered by creation date
    $stmt = $dbh->prepare("SELECT * FROM notifications WHERE notification_advisor_id = :notification_advisor_id");
    $stmt->bindParam(':notification_advisor_id', $advisor_id);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    for ($i = 0; $i < count($notifications); $i++)
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