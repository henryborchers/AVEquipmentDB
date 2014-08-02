<?php include("../includes/header.php"); ?>
<?php
//---------
// TODO: figure out why I can't remove the following line without breaking it
	$dummy = $_GET["card"];
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
	$cardFeaturesResults = mysqli_query($db, $cardFeaturesQuery);
	if(!$cardFeaturesResults) {
		die("Database error");	
	}
	$features = array();
	while($row = mysqli_fetch_array($cardFeaturesResults)){
		$features[] = $row[0];
	}
	mysqli_free_result($cardFeaturesResults);
	
// get query of connections
	$connectionQuery = "SELECT ";
	$connectionQuery .="ItemID, ";
	$connectionQuery .="Class, ";
	$connectionQuery .="ConnectorType, ";
	$connectionQuery .="CONCAT_WS(' ', '(',Quanity,')', ConnectorType) AS connections ";
	$connectionQuery .="FROM ";
	$connectionQuery .="ConnectorJoinTable ";
	$connectionQuery .="INNER JOIN ";
	$connectionQuery .="Connectors ";
	$connectionQuery .="ON ";
	$connectionQuery .="Connectors.Connectors_ID = ConnectorJoinTable.ConnectorID " ;
	$connectionQuery .="WHERE ";
	$connectionQuery .="ItemID = ";
	$connectionQuery .=$_GET["card"] . " ";
	$connectionQuery .="ORDER BY Class, ConnectorType";
	echo $connectionQuery;
	
	$connectionResult = mysqli_query($db, $connectionQuery);
	if(!$connectionResult) {
		die("Database error");
	}
	
// Get Queury of the documentation
	$documentationQuery = "SELECT ";
	$documentationQuery .= "type, ";
	$documentationQuery .= "fileName, ";
	$documentationQuery .= "documentName ";
	$documentationQuery .= "FROM Documentation_has_Item ";
	$documentationQuery .= "INNER JOIN Documentation ";
	$documentationQuery .= "ON document_id = idDocumentation ";
	$documentationQuery .= "WHERE ";
	$documentationQuery .= "item_id = " . $_GET["card"] . " ";
	$documentationResult = mysqli_query($db, $documentationQuery);
	if(!$documentationResult) {
		die("Database error");
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Equipment: card view</title>
<link rel="stylesheet" type="text/css" href="../includes/mainDataStyle.css">
</head>

<body>
<div id="card">
<h1>Equipment Record</h1>
<table id = "dataitemList" border="1">
  <tbody>
    <tr>
      <th>Item Number:</td>
      <td><?php echo $_GET["card"]?></td>
    </tr>
    <tr>
      <th>Friendly Name:</td>
      <td> <?php echo $card["Friendly_name"]; ?> </td>
    </tr>
    <tr>
      <th>Manufacture:</td>
      <td><?php echo $card["Company_Name"]; ?></td>
    </tr>
    
    <tr>
      <th>Model Name:</td>
      <td><?php echo $card["Model"]; ?> </td>
    </tr>
     <tr>
      <th>Serial Number:</td>
      <td><?php echo $card["Serial_Number"]; ?> </td>
    </tr>
     <tr>
      <th>Type:</td>
      <td> <?php echo $card["EquipType"]; ?> </td>
    </tr>
    <tr>
      <th>Professional/Consumer:</td>
      <td><?php echo $card["Consumer_Professional"]; ?> </td>
    </tr>
    <tr>
      <th>Current Location:</td>
      <td><?php echo $card["LocationName"]; ?> </td>
    </tr>
    <tr>
      <th>Working Status:</td>
      <td><?php echo ($card["Working"]==0) ? "Not Working": "Working"; ?> </td>
    </tr>
    <tr>
    	<th>Connections</th>
        <td> 
        	<ul>
        		<?php
					while($connection = mysqli_fetch_assoc($connectionResult)){
					echo "<li>";
					echo $connection["Class"]. ": " . $connection["connections"];
					echo "</li>";
					}
				?>
        	</ul>
        </tr>
    </tr>
    <tr>
      <th>Features:</td>
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
      	<th>Documentation</td>
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