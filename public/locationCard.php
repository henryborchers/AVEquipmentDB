<?php include("../includes/header.php"); ?>
<?php include_once ("../includes/functions.php");?>
<?php
	if(!isset($_GET["Location"])) { $_GET["Location"] = 1; }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../includes/mainDataStyle.css">
</head>

<body>
<div class="background" width="600px">
<h1>Location Information</h1>
<div class="card">
	<?php
		$LocationInfoResults = getLocationInformation($_GET["Location"]);
	?>
	<table class="dataTable">
    <colgroup>
    	<col class="cardNameWidth">
    	<col class="cardRecordWidth">
    </colgroup>
    	<tbody>
        	<tr class="dataTable">
	        	<th class="dataTable"> Location Name: </th>
                <td class="dataTable">  
                	<?php
						echo $LocationInfoResults["LocationName"];
					?>
                </td>
            </tr>
            <tr class="dataTable">
	        	<th class="dataTable"> Building: </th>
                <td class="dataTable"> 
                	<?php
						echo $LocationInfoResults["Building"];
					?>
                </td>
            </tr>
            <tr class="dataTable">
	        	<th class="dataTable"> Address: </th>
                <td class="dataTable"> 
                	<?php
						{
							echo $LocationInfoResults["Address"];
						}
					?>
                </td>
            </tr>
             <tr class="dataTable">
            	<th class="dataTable">
                	Room
                </th>
                <td class="dataTable">
                <?php
						{
							echo $LocationInfoResults["Room"];
						}
					?>
                </td>
              </tr>
              <tr class="dataTable">
              	<th class="dataTable"> Inventory </th>
                <td>
                	<ul>
                	<?php
						$inventoryResults = GetInventoryByLocation($_GET["Location"]);
						while($inventory = mysqli_fetch_assoc($inventoryResults)){
							echo "<li>";
							echo "<a href=\"itemCard.php?card=";
							echo $inventory["ItemID"] . "\">";
							echo sprintf('%05d', $inventory["ItemID"]) . ": " 
								. $inventory["Company_Name"] . " " 
								. $inventory["Model"];
								if(isset($inventory["Friendly_Name"])) {
									echo " \"" . $inventory["Friendly_Name"] . "\"";
								}
							echo "</a>";
							echo "</li>";
						}
					?>
                    </ul>
                </td>
                </tr>
            <tr class="dataTable">
	        	<th class="dataTable"> Notes: </th>
                <td class="dataTable"> 
        			<table>
                	<?php
					$notesResults = getLocationNotes($_GET["Location"]);
                    while($companyNote = mysqli_fetch_assoc($notesResults)){
						echo "<tr> ";
						echo "<td class=\"noteDate\">"; 
						echo $companyNote["Date"] . " :";
						echo "</td>";
						echo "<td>";
						echo $companyNote["Notes"];
						echo "</td> ";
						echo "</td> </tr>";	
					}
					?>
                	</table>
                </td>
            </tr>

        </tbody>
    </table>

</div>
</div>
</body>
</html>

<?php
	mysqli_close($db);

?>