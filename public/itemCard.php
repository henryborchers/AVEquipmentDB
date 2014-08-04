<?php include("../includes/header.php"); ?>
<?php include ("../includes/fuctions.php");?>
<?php
//---------
	if(!isset($_GET["card"])){
		$_GET["card"] = 40;
	}
	// TODO: figure out why I can't remove the following line without breaking it

	$dummy = $_GET["card"];
	
	$cardResult = getCardInfo($_GET["card"]);
	$card = mysqli_fetch_assoc($cardResult);
	mysqli_free_result($cardResult);
	
	$cardFeaturesResults = getCardFeatures($_GET["card"]);
	$features = array();
	while($row = mysqli_fetch_array($cardFeaturesResults)){
		$features[] = $row[0];
	}
	mysqli_free_result($cardFeaturesResults);
	
// get query of connections
	$connectionResult = getConnections($_GET["card"]);
// Get Queury of the documentation
	$documentationResult = getDocumentation($_GET["card"]);
// Get a query of all attachements
	$attachmentsResult = getAttachements($_GET["card"],"public");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Equipment: card view</title>
<link rel="stylesheet" type="text/css" href="../includes/mainDataStyle.css">
</head>

<body>
<div id="card" style="width:800px">
<h1>Equipment Record</h1>

<table id = "dataItemList" border="1">
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
			echo "<a href=\"../files/" . $document["type"] . "/" .  $document["fileName"] . "\" target=\"_blank\"> ";
			echo $document["documentName"] . "</a>";
			echo "</li>";
			}
		?>
        </ul>
		</td>
    </tr>
    <tr>
      	<th> Attachments </th>
       	<td> 
        	<ul>
            	<?php
					while($attachement = mysqli_fetch_assoc($attachmentsResult)) {
						
						echo "<li>";
						echo ucwords($attachement["class"]) . ": ";
						echo "<a href=\"../files/" . $attachement["class"] . "/". $attachement["filename"] . "\" target=\"_blank\">". $attachement["attachmentName"] . "</a>";
						echo "</li>";
					}
					mysqli_free_result($attachmentsResult);
				?>
            
            </ul>
        </td> 
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