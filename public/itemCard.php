<?php include("../includes/header.php"); ?>
<?php include_once ("../includes/functions.php");?>
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
// Get Item Notes
	$itemNotesResults = getItemNotes($_GET["card"],"public");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Equipment: card view</title>
<link rel="stylesheet" type="text/css" href="../includes/mainDataStyle.css">
</head>

<body>
<div class="card">
<h1>Equipment Record</h1>

<table class="dataTable">
  <tbody>
    <tr class="dataTable">
      <th class="dataTable">Item Number:</td>
      <td class="dataTable"><?php echo $_GET["card"]?></td>
    </tr>
    <tr class="dataTable">
      <th class="dataTable">Friendly Name:</td>
      <td class="dataTable"> <?php echo $card["Friendly_name"]; ?> </td>
    </tr>
    <tr class="dataTable">
      <th class="dataTable">Manufacture:</td>
      <td class="dataTable"><?php echo $card["Company_Name"]; ?></td>
    </tr>
    
    <tr class="dataTable">
      <th class="dataTable">Model Name:</td>
      <td class="dataTable"><?php echo $card["Model"]; ?> </td>
    </tr>
     <tr class="dataTable">
      <th class="dataTable">Serial Number:</td>
      <td class="dataTable"><?php echo $card["Serial_Number"]; ?> </td>
    </tr>
     <tr class="dataTable">
      <th class="dataTable">Type:</td>
      <td class="dataTable"> <?php echo $card["EquipType"]; ?> </td>
    </tr>
    <tr class="dataTable">
      <th class="dataTable">Professional/Consumer:</td>
      <td class="dataTable"><?php echo $card["Consumer_Professional"]; ?> </td>
    </tr>
    <tr class="dataTable">
      <th class="dataTable">Current Location:</td>
      <td class="dataTable"><?php echo $card["LocationName"]; ?> </td>
    </tr>
    <tr class="dataTable">
      <th class="dataTable">Working Status:</td>
      <td class="dataTable"><?php echo ($card["Working"]==0) ? "Not Working": "Working"; ?> </td>
    </tr>
    <tr class="dataTable">
    	<th class="dataTable">Connections:</th>
        <td class="dataTable"> 
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
    <tr class="dataTable">
      <th class="dataTable">Features:</td>
      <td> 
      <ul>
	  <?php 
	  	foreach($features as $feature) {
			echo "<li> $feature </li>";	
		}
	  ?> 
      </ul>
      </td>
      <tr class="dataTable">
      	<th class="dataTable">Documentation:</td>
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
    <tr class="dataTable">
      	<th class="dataTable"> Attachments: </th>
       	<td class="dataTable"> 
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
    <tr class="dataTable">
    	<th class="dataTable">Notes:</th>
        <td class="dataTable">
        	<table class="notesTable">
            	  <colgroup
                	<col class="noteDate">
                </colgroup>
                <tbody>
              
			<?php
				while($itemNotes = mysqli_fetch_assoc($itemNotesResults)) {
				echo "<tr> ";
				echo "<td class=\"noteDate\">"; 
				echo $itemNotes["Date"] . " :";
				echo "</td>";
				echo "<td>";
				echo $itemNotes["Notes"];
				echo "</td> ";
				echo "</td> </tr>";
				
				}
			?>
			</tbody>
		</table>
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