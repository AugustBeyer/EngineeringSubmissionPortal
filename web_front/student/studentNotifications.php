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

try 
{
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Get all notifications for student, ordered by creation date
    $stmt = $dbh->prepare("SELECT * FROM notifications WHERE notification_student_id = :notification_student_id");
    $stmt->bindParam(':notification_student_id', $student_id);
    $stmt->execute();
    $notifications = $stmt->fetch(PDO::FETCH_ASSOC);

}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}

$dbh = null;

function displayStudentNotifcations()
{
	global $notifications
	echo "<ul>";
    for ($i = 0; $i < count($notifications); $i++)
    {
        echo "<li id=\"notif\"" . $notifications[$i]["notification_id"] . ">";
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