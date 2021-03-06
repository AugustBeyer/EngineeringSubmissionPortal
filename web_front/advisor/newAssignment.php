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
  <title>Create an Assignment</title>
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
    <h1>Create an Assignment</h1>
    <form action="../../backend/createassignment.php" method="post" id="usrform" enctype="multipart/form-data">
	
    Assignment Name: *<input class="form_field" type = "text" name = "assignment_name"> <br><br>
	Due date: *<input class="form_field" type = "date" name = "due_date" placeholder="YYYY-MM-DD"> <br><br>
    Due time: *<input class="form_field" type = "time" name="due_time" placeholder="HH:MM"> <br><br>    
    Point total: <input class="form_field" type = "number" name = "point_total"> <br><br>
    Assign to teams: * <br><br>
    all teams: <input type="checkbox" onclick="checkAll(this)"><br>
     <!--<input type = "text" name="Teamname"> --> <?php printTeamCheckboxes(); ?><br><br>
    <label>File Formats: </label><input class="form_field" type = "text" name = "file_format" placeholder="zip, pdf, docx"> <br><br>
	Reference file: <input class="form_file" type="file" name="fileToUpload" id="fileToUpload"/> <!--<input class="inputfile" type="file" name="fileToUpload" id="fileToUpload" data-multiple-caption="{count} files selected" multiple /><label for="fileToUpload">Choose a file</label> -->
                     <br><br>
	
    </form>
        Description:*<br>
    <textarea rows="4" cols="50" name="description" form="usrform" class="form_field">
Enter description here...</textarea><br>
        <p style="font-size: 8pt;"><i>a * indicates a required field</i></p>
        <br>
    <input type = "submit" value = "submit" form="usrform" class="form_button">
    </div>
    </div>

<!-- main body wrapper div -->
    </div>
<script src="../js/file.js"></script>    
    <!-- notification div -->
    <div id="notificationWrapper">
    <br><br><br><br>
        <p class="titleStyle">Notifications</p>
    <hr>
    <?php displayAdvisorNotifcations(); ?> 
    </div>
    </body>
</html>
