<?php 
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 1))
{
    die("Unauthorized access. Please return to the login page.");
}
include "teaminfo.php";
include "advisorNotifications.php"; 
?>

<html lang="en">
<head>
  <title>Create A Team</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="../images/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/advisor.css">
    <link rel="stylesheet" type="text/css" href="../css/tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script type="text/javascript" src="../js/advisor.js"></script>
    <script type="text/javascript" src="../js/slideIn.js"></script>
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
        <li id="ESPName">SCU ESP</li>
    </ul>
</div>
<div id="nav">
    <ul class="nav">
        <li class="dropdown"><a href="home.php" class="link">ASSIGNMENTS</a>
            <div class="dropdown-content">
                <a href="home.php">View Assignments</a>
                <a href="newAssignment.php">Create an Assignment</a>
            </div>
        </li>
        <li class="dropdown"><a href="teams.php" class="link active">TEAMS</a>
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
        <li><a href="../../index.html" class="link">LOG OUT</a>
        </li>
    </ul>
</div>


<div id="mainPageWrapper">
<!--Assignments-->
<br><br>
<div id="newTeamBox" class="contentbox teaminfobox">

    <form action="../../backend/createteam.php" method="post">
    <div id = "appendHerePlease">
	<label>Team Name</label>
    <input class="form_field" type = "text" name = "Teamname"><br><br>
    
	<label>Student 1 Name</label>
    <input class="form_field" type = "text" name = "students[]"> <br>
    
    </div>
    <div id = "appendAdvisorsHere">
    </div>
    <button type="button" id="moreStudents" class="form_button">Add more students</button>
    <button type="button" id="moreAdvisors" class="form_button">Add another advisor</button>
	<input type = "submit" value = "submit" class="form_button" id="createTeamSubmit">
    </form>
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
