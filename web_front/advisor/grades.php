<?php
if (!isset($_SESSION))
    session_start();
if (!(isset($_SESSION["user_id"]) && $_SESSION["isAdvisor"] == 1))
{
    die("Unauthorized access. Please return to the login page.");
} 
include "teaminfo.php";
?>

<html lang="en">
<head>
  <title>Advisor Homepage</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="../images/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/advisor.css">
    <link rel="stylesheet" type="text/css" href="../css/tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script type="text/javascript" src="../js/student.js"></script>
    <script type="text/javascript" src="../js/slideIn.js"></script>
</head>
<body>

<!--nav--><!--nav-->
<div id="icon">
    <ul class="icon">
        <li>
            <a href="http://www.scu.edu/engineering">
            <img src="../images/logo.png" alt="logo" class="icon">
            </a>
        </li>
        <li id="ESPName"><a href="../index.html"> SCU Engineering Submission Portal</a></li>
    </ul>
</div>
<div id="nav">
    <ul class="nav">
        <li class="dropdown"><a href="home.php" class="link active" onmousedown="//switchView('assignments');">ASSIGNMENTS</a>
            <div class="dropdown-content">
                <a href="home.php">View Assignments</a>
                <a href="newAssignment.php">Create an Assignment</a>
            </div>
        </li>
        <li class="dropdown"><a href="teams.php" class="link" onmousedown="//switchView('teams');">TEAMS</a>
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
<!--Grades-->
<div id="grades" class="contentbox">
    <div class="spaceMedium"></div>
    <h2>Grades</h2>
            <p>Click on an assignment's "Total Points" field to grade or regrade that assignment.</p>
        <p>To download a group's submission for an assignment, click on the name of that assignment.</p>
    <!-- Table -->
    <table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->

	<!-- Table Header -->
        <h4>Team 1</h4>
	<thead>
		<tr>
			<th>Assignment</th>
			<th>Percent</th>
			<th>Total points</th>
		</tr>
	</thead>
	<!-- Table Header -->

	<!-- Table Body -->
	<tbody>
		<tr>
            <td><a href="http://www.niceme.me">Assign. 1</a></td>
			<td>50%</td>
			<td>10/20</td>
		</tr><!-- Table Row -->

		<tr class="even">
            <td><a href="home.php">Assign. 2</a></td>
			<td>100%</td>
			<td>10/10</td>
		</tr><!-- Darker Table Row -->

		<tr>
            <td><a href="home.php">Assign. 3</a></td>
			<td>--</td>
			<td>--</td>
		</tr>

		<tr class="even">
            <td><a href="home.php">Assign. 4</a></td>
			<td>--</td>
			<td>--</td>
		</tr>

		<tr>
			<td>Total grade</td>
			<td>66.67%</td>
			<td>20/30</td>
		</tr>


	</tbody>
	<!-- Table Body -->

</table>
    <div class="spaceSmall"></div>
        <!-- Table -->
    <table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->

	<!-- Table Header -->
        <h4>Team 2</h4>

	<thead>
		<tr>
			<th>Assignment</th>
			<th>Percent</th>
			<th>Total points</th>
		</tr>
	</thead>
	<!-- Table Header -->

	<!-- Table Body -->
	<tbody>
		<tr>
            <td><a href="http://www.niceme.me">Assign. 1</a></td>
			<td>50%</td>
			<td>10/20</td>
		</tr><!-- Table Row -->

		<tr class="even">
            <td><a href="home.php">Assign. 2</a></td>
			<td>--</td>
			<td>--</td>
		</tr><!-- Darker Table Row -->

		<tr>
            <td><a href="home.php">Assign. 3</a></td>
			<td>--</td>
			<td>--</td>
		</tr>

		<tr class="even">
            <td><a href="home.php">Assign. 4</a></td>
			<td>--</td>
			<td>--</td>
		</tr>

		<tr>
			<td>Total grade</td>
			<td>50%</td>
			<td>10/20</td>
		</tr>


	</tbody>
	<!-- Table Body -->

</table>
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
