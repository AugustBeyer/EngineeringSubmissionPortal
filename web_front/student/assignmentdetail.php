<?php
session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 0))
{
    die("Unauthorized access. Please return to the login page.");
} 
include "assignmentinfo.php";
?>

<html lang="en">
<head>
  <title>Student Homepage</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="../images/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/advisor.css">
    <link rel="stylesheet" type="text/css" href="../css/tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script type="text/javascript" src="../js/student.js"></script>
    <script type="text/javascript" src="../js/slideIn.js"></script>
    <script type="text/javascript" src="../js/autoScroll.js"></script>
    <script type="text/javascript" src="../js/konami.js"></script>
    <script type="text/javascript">
        var show = false;
        
        
        function kool() {
    var easter_egg = new Konami(function() { if(!show){slideIn(watermark); show = true;} else {flipArrow('#watermark')}});}</script>
</head>
<body onload="kool();">

<!--nav-->
<div id="icon">
    <ul class="icon">
        <li>
            <a href="http://www.scu.edu/engineering">
            <img src="../images/logo.png" alt="logo" class="icon">
            </a>
        </li>
        <li id="ESPName"><a href="../../index.html"> SCU Engineering Submission Portal</a></li>
    </ul>
</div>
<div id="nav">
    <ul class="nav">
        
        <li><a href="home.php" class="link active" onclick="return false">HOME</a>
        </li>
        <li><a href="settings.php" class="link">SETTINGS</a>
        </li>
    </ul>
</div>

<div id="mainPageWrapper">
<!--Assignments-->
<br><br>
<div id="assignments" class="contentbox">

    <?php
        $i = htmlspecialchars($_GET["table_index"]);
        printAssignmentDetail($i);
    ?>
    <!-- Assignment Template -->
    
    
    <div class="spaceMedium"></div>
</div> 

<!-- main body wrapper div -->
    </div>
    
    <!-- notification div -->
    <div id="notificationWrapper">
    <br><br><br><br>
        <p class="titleStyle">Notifications</p>
    <hr>
    <ul>
        <li  id="notif1">
            <div class="sampleNotification">
                <p class="bodyStyle">Sample notification title</p>
                <p class="bodyDetail">Sample detail</p>
            </div>
            <div class="sampleNotificationMenu">
                <p><a class="eraseNotification" onmousedown="removeNotification('#notif1');">x</a></p>
            </div>
        </li>   
        <li  id="notif2">
            <div class="sampleNotification">
                <p class="bodyStyle">Sample notification title</p>
                <p class="bodyDetail">Sample detail</p>
            </div>
            <div class="sampleNotificationMenu">
                <p><a class="eraseNotification" onmousedown="removeNotification('#notif2');">x</a></p>
            </div>
        </li>   
        <li  id="notif3">
            <div class="sampleNotification">
                <p class="bodyStyle">Sample notification title</p>
                <p class="bodyDetail">Sample detail</p>
            </div>
            <div class="sampleNotificationMenu">
                <p><a class="eraseNotification" onmousedown="removeNotification('#notif3');">x</a></p>
            </div>
        </li>   
    </ul>  
    </div>
    </body>
</html>
