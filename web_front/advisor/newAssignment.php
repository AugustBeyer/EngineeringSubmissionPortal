<?php 
session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 1))
{
    die("Unauthorized access. Please return to the login page.");
} 
?>

<html lang="en">
<head>
  <title>Advisor Portal</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="../images/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/advisor.css">
    <link rel="stylesheet" type="text/css" href="../css/tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script type="text/javascript" src="../js/student.js"></script>
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
        <li id="ESPName">SCU Engineering Submission Portal</li>
    </ul>
</div>
<div id="nav">
    <ul class="nav">
        
        <li><a href="home.php" class="link">HOME</a>
        </li>
        <li><a href="#settings"  class="link active"onclick="return false"onmousedown="autoScrollTo('settings');">SETTINGS</a>
        </li>
    </ul>
</div>

<div id="mainPageWrapper">

<!--Settings-->
<div id="settings" class="contentbox">
    <div id="formCenter">
        <br>
    <form action="../../backend/createassignment.php" method="post" id="usrform" enctype="multipart/form-data">
	Assignment Name: *<input type = "text" name = "assignment_name"> <br><br>
	Due date: *<input type = "date" name = "due_date"> <br><br>
    Due time: *<input type = "time" name="due_time"> <br><br>    
    Point total: <input type = "number" name = "point_total"> <br><br>
    Assign to team: *<input type = "text" name="Teamname"><br><br>
	Reference file: <input class="newAssignmentButton" type="file" name="fileToUpload" id="fileToUpload"> <br><br>
	
    </form>
        Description:*<br>
    <textarea rows="4" cols="50" name="description" form="usrform">
Enter description here...</textarea><br>
        <p style="font-size: 8pt;"><i>a * indicates a required field</i></p>
        <br>
    <input type = "submit" value = "submit" form="usrform">
    </div>
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
