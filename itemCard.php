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

//---------

	echo $_GET["card"];
	$cardQuery = "SELECT ";
	$cardQuery .= "Friendly_name, ";
	$cardQuery .= "Company_Name, ";
	$cardQuery .= "Model, ";
	$cardQuery .= "Serial_Number, ";
	$cardQuery .= "equipmentTypes.Item AS EquipType, ";
	$cardQuery .= "LocationName, ";
	$cardQuery .= "Working ";
	$cardQuery .= "FROM Item ";
	$cardQuery .=  "INNER JOIN Manufacture ON Manufacture.ManufactureID = Item.Manufacture_ManufactureID ";
	$cardQuery .=  "INNER JOIN Location ON Location.LocationID = Item.Location_LocationID ";
	$cardQuery .= "INNER JOIN equipmentTypes ON equipmentTypes.idequipmentTypes = Item.EquipType ";
	$cardQuery .= "WHERE ";
	$cardQuery .= "ItemID LIKE " . $_GET["card"] . " ";
	echo "$cardQuery <br> <br>";
	$cardResult= mysqli_query($db, $cardQuery);
	if(!$cardResult) {
		die("Database error");	
	}
	$card = mysqli_fetch_assoc($cardResult);
	mysqli_free_result($cardResult);
	echo "Hello <br>";
	print_r($card);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<h1>Equipment record</h1>
<div>
<table width="800" border="1">
  <tbody>
    <tr>
      <td>Item Number:</td>
      <td><?php echo $_GET["card"]?></td>
    </tr>
    <tr>
      <td>Friendly Name:</td>
      <td> <?php echo $card["Friendly_name"]; ?> </td>
    </tr>
    <tr>
      <td>Manufacture:</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td>Model Name:</td>
      <td><?php echo $card["Model"]; ?> </td>
    </tr>
     <tr>
      <td>Serial Number:</td>
      <td><?php echo $card["Serial_Number"]; ?> </td>
    </tr>
     <tr>
      <td>Type:</td>
      <td>&nbsp; </td>
    </tr>
    <tr>
      <td>Professional/Consumer:</td>
      <td>&nbsp; </td>
    </tr>
    <tr>
      <td>Current Location:</td>
      <td><?php echo $card["LocationName"]; ?> </td>
    </tr>
    <tr>
      <td>Working Status:</td>
      <td>&nbsp; </td>
    </tr>
    <tr>
      <td>Features:</td>
      <td>&nbsp; </td>
    </tr>
  </tbody>
</table>

 <br>


</div>
</body>
</html>

<?php
	mysqli_close($db);

?>