<?php
session_start();

require "db_config.php";

$notification_id = $_POST["notif_id"];

echo $notification_id;

?>