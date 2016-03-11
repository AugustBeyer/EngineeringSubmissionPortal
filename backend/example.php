<?php
require "db_config.php";
 
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

if($method == 'GET') {
	$sql = "SELECT * FROM teams";
	$DBresult = $dbh->query($sql);
	$result = $DBresult->fetchAll();
	$output = array();
	foreach ($result as $row) {
		$output["teamname"][] = $row;
	}

	echo json_encode($output);
  	/* Get all of the teams from the database */
  	/* Encode the results as json */
}

?>