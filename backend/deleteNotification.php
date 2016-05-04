<?php
session_start();

require "db_config.php";

$full_notification_id = $_POST["notif_id"];

//Get rid of initial notif string, e.g., notif23 should just be 23
$notification_id = filter_var($full_notification_id, FILTER_SANITIZE_NUMBER_INT);

//echo $notification_id;

try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Delete entry from teams
    $stmt = $dbh->prepare("DELETE FROM notifications WHERE notification_id = :notification_id");
    $stmt->bindParam(':notification_id', $notification_id);
    $stmt->execute();

    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }

$dbh = null;

?>