<?php include("../includes/header.php"); ?>
<?php
// check the data incoming from the Submit 
	$whereClause = array();
// Equipment class
	if(!isset($_GET["type"]) or $_GET["type"] == 'all') {
	}else{
		$whereClause[] = "Class = \"" . $_GET["type"] . "\"";		
	};	

// Condition
	if(!isset($_GET["condition"]) or $_GET["condition"] == 'all') {
	}else{
		$whereClause[] = 'Working = ' . ($_GET["condition"] == "not" ? '0' : '1');
		
	};	
// Location
	if(!isset($_GET["location"]) or $_GET["location"] == 'all') {
	} else {
		$whereClause[] = "LocationName = \"" . $_GET["location"] . "\"";
	};
//check if it's working
// TODO: Clean this up
	
		if(!isset($_GET["working"])) {
		$_GET["working"] = "off";

	};


	// get query of locations
	$locationQuery = "SELECT ";
	$locationQuery .= "LocationName ";
	$locationQuery .= "FROM Location";
	$allLocations = mysqli_query($db, $locationQuery);
	if(!$allLocations) {
		die("Database error: No locations found");
	}
	// get query of equipment types
	$equipmentClassQuery = "SELECT ";
	$equipmentClassQuery .= "DISTINCT(Class) ";
	$equipmentClassQuery .= "FROM equipmentTypes ";
	$allClassEquipment = mysqli_query($db, $equipmentClassQuery);

	
	// get query of Equipment	
	$query = "SELECT ";
	$query .= "ItemID, ";
	$query .= "Friendly_Name, ";
	$query .= "Company_Name, ";
	$query .= "Model, ";
//	$query .= "Serial_Number, ";
	$query .= "LocationName, ";
	$query .= "Working, ";
	$query .= "equipmentTypes.Item AS EquipType ";
	$query .= "FROM Item "; 
	$query .=  "INNER JOIN Manufacture ON Manufacture.ManufactureID = Item.Manufacture_ManufactureID ";
	$query .=  "INNER JOIN Location ON Location.LocationID = Item.Location_LocationID ";
	$query .= "INNER JOIN equipmentTypes ON equipmentTypes.idequipmentTypes = Item.EquipType ";
	// Build the where clause
		if(count($whereClause) > 0 ){
		$query .= "WHERE ";
		$query .= $whereClause[0] . " ";
	}
	if(count($whereClause) >1) { 
		for($i=1; $i < count($whereClause); $i++)
		{
			$query .= "AND ";
			$query .= $whereClause[$i] . " ";
		}
		echo "Large than 1";
	} else {
		echo "1 or less ";
	};
	echo "Here's the where clause <br>";
	print_r($whereClause);
	echo "<br>";


	$query .= "ORDER BY ItemID ASC";
	$result = mysqli_query($db, $query);
	echo $query;
	if(!$result) {
		die("Database error");	
	}
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Equipment Database</title>
</head>

<body>
<h1> Equipment List </h1>
<form action="mainMenu.php" method="get">
	Type: 
    <select name="type">
    	<option value="all" selected="selected"> All </option>
        <?php
        while($equipmenClasstType = mysqli_fetch_row($allClassEquipment)) { 
                				?>			
                <option value="<?php 
				echo "$equipmenClasstType[0]\" ";
				echo " \> $equipmenClasstType[0]"; 
				
				?></option> 
                <?php
            };
		mysqli_free_result($equipmentType);
        ?>
    

    </select>
    <br>
	Condition:
    <select name="condition">
    	 <option value="all" selected="selected"> All </option>
         <option value="not" > Not Working</option>
         <option value="working" > Working</option>
     </select>
    
    <br>

    Location:
    <select name ="location">
        <option value="all"> All </option>
        <?php
        while($location = mysqli_fetch_row($allLocations)) { 
                				?>			
                <option value="<?php 
				echo "$location[0]\" ";
				echo " \> $location[0]"; 
				
				?></option> 
                <?php
            };
		mysqli_free_result($location);
        ?>
    
    </select>
    <?php
        print_r($_GET);
    ?>
    <br>
    <input type="submit" name="submit" value="Search">

</form>
<h2>Results</h2>
<table width="1000" border="1">
  <tbody>
    <tr>
      <th>ID </th>

      <th>Friendly Name</th>
      <th>Manufacture</th>
      <th>Model</th>
      <th>Description</th>
      <th>Condition </th>
      <th>Location </th>
      <th>More Information </th>


	<?php
//Build the table
		while($row = mysqli_fetch_assoc($result)) {
			?>
            
            <?php
			echo "<tr>";
				echo "<td>" . $row["ItemID"] . "</td>";
				echo "<td>" .$row["Friendly_Name"] . "</td>";
				echo "<td>" .$row["Company_Name"] . "</td>";
				echo "<td>" .$row["Model"] . "</td>";
				echo "<td>" .$row["EquipType"] . "</td>";
				echo "<td>" . (($row["Working"]==1) ? "Working" : "Not Working"). "</td>";
//				echo "<td>" .$row["Serial_Number"] . "</td>";
				echo "<td>" .$row["LocationName"] . "</td>";
				echo "<td> <a href=\"itemCard.php?card=" . $row["ItemID"] . "\"> More info </a> </td>";
			echo "</tr>";	
			?> 
            <?php
		}
		mysqli_free_result($result);
	?>
      </tbody>

</table>
</body>
</html>

<?php
	mysqli_close($db);

?>