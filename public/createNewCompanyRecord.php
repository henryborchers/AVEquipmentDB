<?php session_start();?>
<?php include("../includes/header.php"); ?>
<?php include_once("../includes/functions.php");?>
<?php require_once("../includes/validations.php");?>
<html>
<?php
	
	if(isset($_POST['submit'])) {
		$locationName = mysql_prep($_POST["locationName"]);
		$buildingName = mysql_prep($_POST["buildingName"]);
		$buildingAddress = mysql_prep($_POST["buildingAddress"]);
		$roomNumber = $_POST["roomNumber"];
	} else {
		//redirect_to("addNewLocation.php");
		$_SESSION["message"] = "Failure: Unable to new location to database.";
	}
	$errors = array();
	//validations
	if(!validatePresence($locationName)) {
		$errors = "Location Name field cannot be empty";
	}
	// tally the errors and if any, send back to user
	if (!empty($errors)){
		$_SESSION['errors'] = $errors;
		redirect_to("addNewLocation.php");
	}
	else {
		// perform the database query
		$query = "INSERT INTO Location (";
		$query .= "LocationName, Building, Address, Room";
		$query .= ") VALUES (";
		$query .= "\"$locationName\", \"$buildingName\", \"$buildingAddress\", \"$roomNumber\"";
		$query .= ")";
		$result = mysqli_query($db, $query);
		echo $query;
		if ($result) {
			$_SESSION["message"] = "Success: Added new location to database.";
			//redirect_to("mainMenu.php");
		}
		else {
			$_SESSION["message"] = "Failure: Unable to new location to database.";
			
		}
	}

	mysqli_close($db);

?>

</html>