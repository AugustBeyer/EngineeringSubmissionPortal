<?php 
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 1))
{
    die("Unauthorized access. Please return to the login page.");
}
include "teaminfo.php";
include "assignmentinfo.php";
include "advisorNotifications.php";

$i = htmlspecialchars($_GET["index1"]);
$j = htmlspecialchars($_GET["index2"]);
?>

<html lang="en">
<head>
  <title>Edit an Assignment</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="../images/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/advisor.css">
    <link rel="stylesheet" type="text/css" href="../css/tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script type="text/javascript" src="../js/advisor.js"></script>
<script type="text/javascript" src="../js/autoScroll.js"></script>
    <script type="text/javascript" src="../js/slideIn.js"></script>
</head>
<body>

<!--nav-->
<div id="icon">
    <ul class="icon">
        <li>
            <a href="http://www.scu.edu/engineering">
                <img src="../images/logo.png" alt="logo" class="icon">
            </a>
        </li>
        <li id="ESPName">SCU ESP</li>
    </ul>
</div>
<div id="nav">
    <ul class="nav">
        
        <li><a href="home.php" class="link">HOME</a>
        </li>
        <li><a href="../../index.html" class="link">LOG OUT</a>
        </li>
    </ul>
</div>

<div id="mainPageWrapper">

<!--Settings-->
<div id="settings" class="contentbox">
    <div id="formCenter">
        <br>
	<?php printEditAssignmentDetail($i, $j); ?>
    </div>
    </div>

<!-- main body wrapper div -->
    </div>
    
    <!-- notification div -->
    <div id="notificationWrapper">
    <br><br><br><br>
        <p class="titleStyle">Notifications</p>
    <hr>
    <?php displayAdvisorNotifcations(); ?> 
    </div>
    </body>
</html>
