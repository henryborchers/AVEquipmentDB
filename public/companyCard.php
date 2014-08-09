<?php include("../includes/header.php"); ?>
<?php include_once ("../includes/functions.php");?>
<?php
    if(!isset($_GET["company"])){
		$_GET["company"] = 41;
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manufacture Record</title>
</head>

<link rel="stylesheet" type="text/css" href="../includes/mainDataStyle.css">
<body>

<h1>Manufacture Record</h1>
<div class="card">
	<?php
		$companyInfoResults = getCompanyInformation($_GET["company"]);
	?>
	<table class="dataTable">
    <colgroup>
    	<col class="cardNameWidth">
    	<col class="cardRecordWidth">
    </colgroup>
    	<tbody>
        	<tr class="dataTable">
	        	<th class="dataTable"> Company Name: </th>
                <td class="dataTable">  
                	<?php
						echo $companyInfoResults["Company_Name"];
					?>
                </td>
            </tr>
            <tr class="dataTable">
	        	<th class="dataTable"> Status: </th>
                <td class="dataTable"> 
                	<?php
						echo $companyInfoResults["Status"];
					?>
                </td>
            </tr>
            <tr class="dataTable">
	        	<th class="dataTable"> Website: </th>
                <td class="dataTable"> 
                	<?php
						if(isset($companyInfoResults["Website"])){
							echo "<a href=\"";
							echo $companyInfoResults["Website"];
							echo "\">";
							echo $companyInfoResults["Website"];
							echo "</a>";
						}
					?>
                </td>
            </tr>
            <tr class="dataTable">
	        	<th class="dataTable"> Notes: </th>
                <td class="dataTable"> 
        			<table>
                	<?php
					$notesResults = getCompanyNotes($_GET["company"]);
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
            <tr class="dataTable">
            	<th class="dataTable">
                	Inventory
                </th>
                <td>
                	<ul>
                	<?php
						$inventoryResults = GetInventoryByCompany($_GET["company"]);
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
        </tbody>
    </table>

</div>

</body>
</html>
<?php
	mysqli_close($db);

?>