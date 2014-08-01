<?php
	// make connections

	$dbHost = "localhost";
	$dbUser = "equipment";
	$dbPass = "equipment";
	$dbName = "Equipment";
	$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
	
	if(mysqli_connect_errno()) {
		die("Error connecting to database: " . mysqli_connect_error . 
		mysqli_connect_errno);	
	}; 
?>