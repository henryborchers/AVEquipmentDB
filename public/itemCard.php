<?php include("../includes/header.php"); ?>
<?php
//---------

	echo $_GET["card"];
	$cardQuery = "SELECT ";
	$cardQuery .= "Friendly_name, ";
	$cardQuery .= "Company_Name, ";
	$cardQuery .= "Model, ";
	$cardQuery .= "Serial_Number, ";
	$cardQuery .= "equipmentTypes.Item AS EquipType, ";
	$cardQuery .= "LocationName, ";
	$cardQuery .= "Working, ";
	$cardQuery .= "Consumer_Professional ";
	$cardQuery .= "FROM Item ";
	$cardQuery .=  "INNER JOIN Manufacture ON Manufacture.ManufactureID = Item.Manufacture_ManufactureID ";
	$cardQuery .=  "INNER JOIN Location ON Location.LocationID = Item.Location_LocationID ";
	$cardQuery .= "INNER JOIN equipmentTypes ON equipmentTypes.idequipmentTypes = Item.EquipType ";
	$cardQuery .= "WHERE ";
	$cardQuery .= "ItemID LIKE " . $_GET["card"] . " ";
	echo "$cardQuery <br> <br>";
	$cardResult = mysqli_query($db, $cardQuery);
	if(!$cardResult) {
		die("Database error");	
	}
	$card = mysqli_fetch_assoc($cardResult);
	mysqli_free_result($cardResult);
	
	$cardFeaturesQuery = "SELECT ";
	$cardFeaturesQuery .= "CONCAT_WS(' ', Class , ': ', Value, Unit) ";
	$cardFeaturesQuery .= "FROM ";
	$cardFeaturesQuery .= "Atributes_has_Item ";
	$cardFeaturesQuery .= "INER JOIN ";
	$cardFeaturesQuery .= "Atributes ";
	$cardFeaturesQuery .= "ON ";
	$cardFeaturesQuery .= "Atributes_id = idAtributes ";
	$cardFeaturesQuery .= "WHERE ";
	$cardFeaturesQuery .= "Item_ItemID LiKE " . $_GET["card"] . " ";
	
	echo "<br>Features: $cardFeaturesQuery <br>";
	$cardFeaturesResults = mysqli_query($db, $cardFeaturesQuery);
	if(!$cardFeaturesResults) {
		die("Database error");	
	}
	$features = array();
	while($row = mysqli_fetch_array($cardFeaturesResults)){
		$features[] = $row[0];
	}
	print_r($features);
	
	
	
	echo "Card Queury: $cardQuery <br>";
	print_r($card);
// Get Queury of the documentation
	$documentationQueury = "SELECT ";
	$documentationQueury .= "type, ";
	$documentationQueury .= "fileName, ";
	$documentationQueury .= "documentName ";
	$documentationQueury .= "FROM Documentation_has_Item ";
	$documentationQueury .= "INNER JOIN Documentation ";
	$documentationQueury .= "ON document_id = idDocumentation ";
	$documentationQueury .= "WHERE ";
	$documentationQueury .= "item_id = " . $_GET["card"] . " ";
	echo $documentationQueury;
	$documentationResult = mysqli_query($db, $documentationQueury);
	if(!$documentationResult) {
		die("Database error");
	}
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
      <td><?php echo $card["Company_Name"]; ?></td>
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
      <td> <?php echo $card["EquipType"]; ?> </td>
    </tr>
    <tr>
      <td>Professional/Consumer:</td>
      <td><?php echo $card["Consumer_Professional"]; ?> </td>
    </tr>
    <tr>
      <td>Current Location:</td>
      <td><?php echo $card["LocationName"]; ?> </td>
    </tr>
    <tr>
      <td>Working Status:</td>
      <td><?php echo $card["Working"]; ?> </td>
    </tr>
    <tr>
      <td>Features:</td>
      <td> 
      <ul>
	  <?php 
	  	foreach($features as $feature) {
			echo "<li> $feature </li>";	
		}
	  ?> 
      </ul>
      </td>
      <tr>
      	<td>Documentation</td>
        <td>
        <ul>
		<?php
			while($document = mysqli_fetch_assoc($documentationResult)){
			echo "<li>";
			echo "<a href=\"../files/" . $document["type"] . "/" .  $document["fileName"] . "\"> ";
			echo $document["documentName"] . "</a>";
			echo "</li>";
			}
		?>
        </ul>
		</td>
      </tr>
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