<?php include("../includes/header.php"); ?>
<?php include_once("../includes/functions.php");?>
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
		$whereClause[] = 'working = ' . urldecode($_GET["condition"]);
	
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
					"Condition" => "working",
					"Location" => "LocationName"
					];

	
	// get query of Equipment	
	
	$equipmentQuery = "SELECT ";
	foreach($queryArray as $displayName => $SQLName) {
		$equipmentQuery .= $SQLName . ", ";	
	};
	$equipmentQuery = substr($equipmentQuery, 0, -2);
	$equipmentQuery .= ", ";
	$equipmentQuery .= "Manufacture_ManufactureID, ";
	$equipmentQuery .= "Location_LocationID ";
	$equipmentQuery .= "FROM Item "; 
	$equipmentQuery .=  "INNER JOIN Manufacture ON Manufacture.ManufactureID = Item.Manufacture_ManufactureID ";
	$equipmentQuery .=  "INNER JOIN Location ON Location.LocationID = Item.Location_LocationID ";
	$equipmentQuery .= "INNER JOIN equipmentTypes ON equipmentTypes.idequipmentTypes = Item.EquipType ";
	// Build the where clause
		if(count($whereClause) > 0 ){
		$equipmentQuery .= "WHERE ";
		$equipmentQuery .= $whereClause[0] . " ";
	}
	if(count($whereClause) >1) { 
		for($i=1; $i < count($whereClause); $i++)
		{
			$equipmentQuery .= "AND ";
			$equipmentQuery .= $whereClause[$i] . " ";
		}
	}



	$equipmentQuery .= "ORDER BY $sortBy ASC";
	//echo $equipmentQuery;
	$result = mysqli_query($db, $equipmentQuery);
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
<div class="background">
<h1>Equipment Inventory</h1>
<div class="searchBox">
    <form action="mainMenu.php" method="get">
        <div class="searchBox">
            <h2>Search</h2>
            <table class="dataTable">
                <tr>
                    <th class="dataTable" width="100px"> Type: </th>
                    <td class="dataTable" width="200px"> 
                        <select class="dataTable" name="type">
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
                    <th class="dataTable">Condition: </th>
                    <td class="dataTable">
                        <select class="dataTable"name="condition">
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
                    <th class="dataTable">Location:</th>
                    <td class="dataTable">
                        <select class="dataTable" name ="location">
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
                    <th class="dataTable">Sort by:</th>
                    <td class="dataTable">
                        <select class="dataTable" name="sortBy">
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
                    <th class="dataTable"> </th>
                    <td>
                        <input type="submit" name="submit" value="Search">
                    </td>
                </tr>
            </table>
        </div>
    </form>

<br />
<div\>
  <h2>Results</h2>
<table class="dataTable">
	<colgroup>
    	<col class="dataTable">
        <col class="dataTable">
        <col class="dataTable">
        <col class="dataTable">
        <col class="dataTable">
        <col class="dataTable">
        <col class="dataTable">
    </colgroup>
  <thead>
  <tr>
    <?php
		foreach($queryArray as $displayName => $SQLName) {
			echo "<th class=\"dataTable\">";
			echo $displayName;
//			}
			echo "</th> \n";
		}
	?>
	<th class="dataTable">More Information </th>
    </tr>
 </thead>
 <tbody>

	<?php
//Build the table
		while($row = mysqli_fetch_assoc($result)) {
			?>
            
            <?php
			echo "<tr class=\"dataTable\">";
				echo "<td class=\"dataTable\" >" . sprintf('%05d', $row["ItemID"]) . "</td>";
				echo "<td class=\"dataTable\">" .$row["Friendly_Name"] . "</td>";
				echo "<td class=\"dataTable\">";
				echo "<a href=\"companyCard.php?company=";
				echo $row["Manufacture_ManufactureID"];
				echo "\"> ";
				echo $row["Company_Name"] . "</a></td>";
				echo "<td class=\"dataTable\">" .$row["Model"] . "</td>";
				echo "<td class=\"dataTable\">" .$row["EquipType"] . "</td>";
				echo "<td class=\"dataTable\">" . $row["working"]. "</td>";
//				echo "<td>" .$row["Serial_Number"] . "</td>";
				echo "<td class=\"dataTable\">";
				echo "<a href=\"locationCard.php?Location=" . $row["Location_LocationID"] . "\" >";
				echo $row["LocationName"] . "</a> </td>";
				echo "<td class=\"dataTable\"> <a href=\"itemCard.php?card=" . $row["ItemID"] . "\" . > More info </a> </td>";
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