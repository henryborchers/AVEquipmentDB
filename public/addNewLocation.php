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
            
            <h1> Add New Company </h1>
            <?php
				if(isset($_SESSION['errors'])){
					echo "<div class=\"error\">";
					echo $_SESSION['errors'];
					echo "</div>";
					$_SESSION['errors'] = NULL;
					 
				}
				?>

            <form class="newInput" action="createNewCompanyRecord.php" method="post">
            	<label class="newInput"> Company Name: </label>  <br />
                <input type="text" name="companynName" class="newInput"> <br />
                <label class="newInput">Website: </label> <br />
                <input type="text" name="website" class="newInput"> <br />
 
                <label class="newInput"> Company Status: </label> <br />
                <select> name="roomNumber" class="newInput"> 
                	<option value = "Active"> Active  </option>
                    <option value = "No longer in business"> No longer in business </option>
                    <option value="Unkown"> Unknown </option>
                </select> <br />
               
                <br />
                <input type="submit" name="submit" value="Submit" class="newInput">
            </form>
            </div>
        </div>
        <br />
    </div>
</body>
</html>