<?php session_start(); ?>
<?php include("../includes/header.php"); ?>
<?php include_once("../includes/functions.php");?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add New Location</title>
<link rel="stylesheet" type="text/css" href="../includes/mainDataStyle.css">
</head>

<body>
	<div class="admin">
    <br />
    	<div class="newInput" style="text-align:center">
            <div style="margin:30px; text-align:left" >
            
            <h1> Add New Location </h1>
            <?php
				if(isset($_SESSION['errors'])){
					echo "<div class=\"error\">";
					echo $_SESSION['errors'];
					echo "</div>";
					$_SESSION['errors'] = NULL;
					 
				}
				?>

            <form class="newInput" action="createNewLocationRecord.php" method="post">
            	<label class="newInput"> Location Name: </label>  <br />
                <input type="text" name="locationName" class="newInput"> <br />
                <label class="newInput">Building: </label> <br />
                <input type="text" name="buildingName" class="newInput"> <br />
                <label class="newInput"> Address: </label> <br />
                <input type="text" name="buildingAddress" class="newInput"> <br />
                <label class="newInput"> Room Number: </label> <br />
                <input type="text" name="roomNumber" class="newInput"> <br />
                <br />
                <input type="submit" name="submit" value="Submit" class="newInput">
            </form>
            </div>
        </div>
        <br />
    </div>
</body>
</html>