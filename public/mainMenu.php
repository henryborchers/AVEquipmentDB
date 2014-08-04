<?php include("../includes/header.php"); ?>
<?php include("../includes/fuctions.php");?>
<?php
// check the data incoming from the Submit 
	$whereClause = array();
// Equipment class
	if(!isset($_GET["type"]) or $_GET["type"] == 'all') {
	}else{
		$whereClause[] = "Class = \"" . urldecode($_GET["type"]) . "\"";		
	};	

// Condition
	if(!isset($_GET["condition"]) or $_GET["condition"] == 'all') {
	}else{
//		$whereClause[] = 'Working = ' . ($_GET["condition"] == "not" ? '0' : '1');
		$whereClause[] = 'Working = ' . urldecode($_GET["condition"]);
	
	};	
// Location
	if(!isset($_GET["location"]) or $_GET["location"] == 'all') {
	} else {
		$whereClause[] = "LocationName = \"" . urldecode($_GET["location"]) . "\"";
	};
	
// Sort by
	if(!isset($_GET["sortBy"])) {
		$sortBy = 'ItemID';
	} else {
	$sortBy	= $_GET["sortBy"];
	}
	
	
		

//check if it's working
// TODO: Clean this up
	
		if(!isset($_GET["working"])) {
		$_GET["working"] = "all";

	};


	// get query of locations
	$locationQuery = "SELECT ";
	$locationQuery .= "LocationName ";
	$locationQuery .= "FROM Location";
	$allLocations = mysqli_query($db, $locationQuery);
	confirmQuery($allLocations);

	// get query of equipment types
	$equipmentClassQuery = "SELECT ";
	$equipmentClassQuery .= "DISTINCT(Class) ";
	$equipmentClassQuery .= "FROM equipmentTypes ";
	$allClassEquipment = mysqli_query($db, $equipmentClassQuery);
	$queryArray = [
					"ID" => "ItemID", 
					"Friendly Name" => "Friendly_Name",
					"Manufacture" => "Company_Name",
					"Model" => "Model",
					"Description" => "equipmentTypes.Item AS EquipType",
					"Condition" => "Working",
					"Location" => "LocationName"
					];

	
	// get query of Equipment	
	$query = "SELECT ";
	foreach($queryArray as $displayName => $SQLName) {
		$query .= $SQLName . ", ";	
	};
	$query = substr($query, 0, -2);
	$query .= " FROM Item "; 
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


	$query .= "ORDER BY $sortBy ASC";
	$result = mysqli_query($db, $query);
	echo $query;
	confirmQuery($result);
	?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Equipment Database</title>
<link rel="stylesheet" type="text/css" href="../includes/mainDataStyle.css">
</head>

<body>
<div id="background">
<h1> Equipment Inventory </h1>
    <form action="mainMenu.php" method="get" id="formStyle">
        <div id="searchBox">
            <h3>Search</h3>
            <table id= "search">
                <tr>
                    <th width="100"> Type: </th>
                    <td width="400"> 
                        <select name="type">
                            <option value="all"> All </option>
                            <?php
                                while($equipmenClasstType = mysqli_fetch_row($allClassEquipment)) { 
                                    echo "<option value=\"";
                                    echo "$equipmenClasstType[0]\"" ;
                                    if(isset($_GET["type"]) && $equipmenClasstType[0] == $_GET["type"]){echo "selected";};
                                    echo " \> $equipmenClasstType[0]"; 
                                    echo "</option>";
                                };
                                
                                mysqli_free_result($allClassEquipment);
                            ?>
                        </select> 
                    </td>
                </tr>
                <tr> 
                    <th>Condition: </th>
                    <td>
                        <select name="condition">
                             <option value="all"> All </option>
                             <?php
                                $conditionArray = ["Not Working" => 0 , "Working" => 1];
                                foreach($conditionArray as $key => $val) {
                                    echo "<option value=". $val;
                                    if(isset($_GET["condition"]) && ($_GET["condition"]) != 'all' && $val == $_GET["condition"]){ echo " selected";};
                                    echo "> $key </option>";
                                };
                            ?>
                        </select>        
                    </td>
                </tr>
                <tr>
                    <th>Location:</th>
                    <td>
                        <select name ="location">
                            <option value="all"> All </option>
                            <?php
                                while($location = mysqli_fetch_row($allLocations)) { 		
                                        echo "<option value=\"";
                                        echo "$location[0]\" ";
                                        if(isset($_GET["location"]) && $location[0] == $_GET["location"]){echo "selected";};
                                        echo " \> $location[0]"; 
                                        echo "</option>"; 
                                    };
                                mysqli_free_result($allLocations);
                            ?>
                            </select>
                    </td>
                </tr>
                <tr>
                    <th>Sort by:</th>
                    <td>
                        <select name="sortBy">
                        <?php
                            foreach($queryArray as $displayName => $SQLName){
                            echo "<option value=$SQLName";
                            if(isset($_GET["sortBy"]) && $SQLName == $_GET["sortBy"]){
                                echo " selected";
                            }
                            echo "> $displayName </option>";
                            }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th> </th>
                    <td>
                        <input type="submit" name="submit" value="Search">
                    </td>
                </tr>
            </table>
        </div>
    </form>

<br />
<div id="dataTable">-
  <h2>Results</h2>
<table id = "dataList">
  <tbody>
    <tr>
    <?php
		foreach($queryArray as $displayName => $SQLName) {
			echo "<th";
			if($displayName=='Location') {
				echo " style=\"width:200\">$displayName</div>"; 
			} elseif($displayName=='Friendly Name') {
				echo " style=\"width:100\"> $displayName</div>"; 

			} elseif($displayName=='ID') {
				echo " style=\"width:25\"> $displayName</div>"; 

			} else {
			echo ">". $displayName;
			}
			echo "</th> \n";
		}
	?>
  
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
				echo "<td> <a href=\"itemCard.php?card=" . $row["ItemID"] . "\" . > More info </a> </td>";
			echo "</tr>";	
			?> 
            <?php
		}
		mysqli_free_result($result);
	?>
      </tbody>

</table>
</div>
</div>
</body>
</html>

<?php
	mysqli_close($db);

?>