<?php
session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 0))
{
    die("Unauthorized access. Please return to the login page.");
}
include "studentNotifications.php";
?> 

<html lang="en">
<head>
  <title>Student Settings</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="../images/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/student.css">
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
        <li id="ESPName"><a href="../../index.html"> SCU Engineering Submission Portal</a></li>
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
    <h3>welcome to settings I guess</h3>
</div>

<!-- main body wrapper div -->
    </div>
    
    <!-- notification div -->
    <div id="notificationWrapper">
    <br><br><br><br>
        <p class="titleStyle">Notifications</p>
    <hr>

    <?php

        displayStudentNotifcations();

    ?>
    
    </div>
    </body>
</html>
