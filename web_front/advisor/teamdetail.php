<?php 
session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 1))
{
    die("Unauthorized access. Please return to the login page.");
}  
include "assignmentinfo.php";
include "teaminfo.php";
?>

<html lang="en">
<head>
  <title>Teams</title>
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
        <li class="dropdown"><a href="home.php" class="link" onmousedown="//switchView('assignments');">ASSIGNMENTS</a>
            <div class="dropdown-content">
                <a href="home.php">View Assignments</a>
                <a href="newAssignment.php">Create an Assignment</a>
            </div>
        </li>
        <li class="dropdown"><a href="teams.php" class="link active" onmousedown="//switchView('teams');">TEAMS</a>
            <div class="dropdown-content">
                <a href="createteam.php">Create a Team</a>
                <hr>
                <?php
                printTeamNavBar();
                ?>
            </div>
        </li>

      <!--  <li><a href="grades.php" class="link" onmousedown="//switchView('grades');">GRADES</a>
        </li>-->
        <li><a href="settings.php" class="link">SETTINGS</a>
        </li>
    </ul>
</div>




<div id="mainPageWrapper">
    <div id="assignments" class="contentbox">
    <p>Click on any assignment to view more information</p>
    <?php

        $i = htmlspecialchars($_GET["index1"]);
        printSingleAssignmentTable($i);

    ?>
   
    
    <!-- Assignment Template -->
    </div>
<!--About-->
<div id="about" class="contentbox">  

<?php

    $i = htmlspecialchars($_GET["index1"]);
    printTeamInfo($i) 
?>

</div>
<!--extra space--> 

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
