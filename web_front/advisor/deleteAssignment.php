<?php 
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 1))
{
    die("Unauthorized access. Please return to the login page.");
}
include "teaminfo.php";
include "assignmentinfo.php";

$i = htmlspecialchars($_GET["index1"]);
$j = htmlspecialchars($_GET["index2"]);

deleteAssignment($i, $j);
?>