<?php
// confirm Query check to see if the result is valid
	function confirmQuery($resultSet) {
		if(!$resultSet) {
			die("Database error: " . mysqli_connect_error() . " (" . mysqli_connect_errno(). ")") ;
		}
	}
	
//-------------------
	function redirect_to($newLocation) {
		header("Location: ". $newLocation);
		exit;
	}
	
//--------------------
 	function mysql_prep($string) {
		global $db;
		$escapedString = mysqli_real_escape_string($db, $string); 
		echo $escapedString;
		return $escapedString;
		
 }

//-----------------------
	function getCardInfo() {
		global $db;
		$cardQuery = "SELECT ";
		$cardQuery .= "Friendly_name, ";
		$cardQuery .= "Company_Name, ";
		$cardQuery .= "Model, ";
		$cardQuery .= "Serial_Number, ";
		$cardQuery .= "ManufactureID, ";
		$cardQuery .= "displayPicture, ";
		$cardQuery .= "equipmentTypes.Item AS EquipType, ";
		$cardQuery .= "LocationName, ";
		$cardQuery .= "Location_LocationID, ";
		$cardQuery .= "working, ";
		$cardQuery .= "Consumer_Professional ";
		$cardQuery .= "FROM Item ";
		$cardQuery .=  "INNER JOIN Manufacture ON Manufacture.ManufactureID = Item.Manufacture_ManufactureID ";
		$cardQuery .=  "INNER JOIN Location ON Location.LocationID = Item.Location_LocationID ";
		$cardQuery .= "INNER JOIN equipmentTypes ON equipmentTypes.idequipmentTypes = Item.EquipType ";
		$cardQuery .= "WHERE ";
		$cardQuery .= "ItemID LIKE " . $_GET["card"] . " ";
		$cardResult = mysqli_query($db, $cardQuery);
		confirmQuery($cardResult);
		return($cardResult);

	}
//----------------------------	
	function getCardFeatures($itemID) {
		global $db;
		$cardFeaturesQuery = "SELECT ";
		$cardFeaturesQuery .= "CONCAT_WS(' ', Class , ': ', Value, Unit) ";
		$cardFeaturesQuery .= "FROM ";
		$cardFeaturesQuery .= "Atributes_has_Item ";
		$cardFeaturesQuery .= "INER JOIN ";
		$cardFeaturesQuery .= "Atributes ";
		$cardFeaturesQuery .= "ON ";
		$cardFeaturesQuery .= "Atributes_id = idAtributes ";
		$cardFeaturesQuery .= "WHERE ";
		$cardFeaturesQuery .= "Item_ItemID LiKE " . $itemID . " ";
		$cardFeaturesResults = mysqli_query($db, $cardFeaturesQuery);
		confirmQuery($cardFeaturesResults);
		return($cardFeaturesResults);
	}
//------------------------------
	function getConnections($itemID) {
		global $db;
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
		$connectionQuery .=$itemID . " ";
		$connectionQuery .="ORDER BY Class, ConnectorType";
		$connectionResult = mysqli_query($db, $connectionQuery);
		confirmQuery($connectionResult);
		return($connectionResult);
	}
//-------------------------------
	function getDocumentation($itemID) {
		global $db;
		$documentationQuery = "SELECT ";
		$documentationQuery .= "type, ";
		$documentationQuery .= "fileName, ";
		$documentationQuery .= "documentName ";
		$documentationQuery .= "FROM Documentation_has_Item ";
		$documentationQuery .= "INNER JOIN Documentation ";
		$documentationQuery .= "ON document_id = idDocumentation ";
		$documentationQuery .= "WHERE ";
		$documentationQuery .= "item_id = " . $itemID . " ";
		$documentationResult = mysqli_query($db, $documentationQuery);
		confirmQuery($documentationResult);
		return($documentationResult);
	}
	
//-----------------------------------
// reduce the amount of items returned to what's needed
	function getAttachements($itemID, $access) {
		global $db;
		$attachmentsQuery = "SELECT ";
		$attachmentsQuery .= "* " ;
		$attachmentsQuery .= "FROM ";
		$attachmentsQuery .= "attachmentsJoinTable ";
		$attachmentsQuery .= "INNER JOIN attachments ";
		$attachmentsQuery .= "ON attachmentsJoinTable.attachmentsID = attachments.attachmentID ";
		$attachmentsQuery .= "WHERE ";
		$attachmentsQuery .= "visibility like ";
		$attachmentsQuery .= "\"" . $access . "\" AND ";
		$attachmentsQuery .= "itemID=";
		$attachmentsQuery .= $itemID;
//		echo " <br />: ". $attachmentsQuery;
		$attachmentsResults = mysqli_query($db, $attachmentsQuery);
		confirmQuery($attachmentsResults);
		return($attachmentsResults);
	}
//-----------------------------------
	function getItemNotes($itemID) {
		global $db;
		$notesQuery = "SELECT ";
		$notesQuery .= "Date, ";
		$notesQuery .= "Notes ";
		$notesQuery .= "FROM ";
		$notesQuery .= "Notes ";
		$notesQuery .= "INNER JOIN itemToNotesJoinTable ";
		$notesQuery .= "ON Notes.idNotes = itemToNotesJoinTable.noteID ";
		$notesQuery .= "WHERE ";
		$notesQuery .= "itemToNotesJoinTable.ItemID=";
		$notesQuery .= $itemID;
//		echo "<br /> Notes Query: " . $notesQuery;
		$notesResult = mysqli_query($db, $notesQuery);
		confirmQuery($notesResult);
		return($notesResult);
		
	}


//-----------------------------------
	function getLocationNotes($locationID) {
		global $db;
		$notesQuery = "SELECT ";
		$notesQuery .= "Date, ";
		$notesQuery .= "Notes ";
		$notesQuery .= "FROM ";
		$notesQuery .= "Notes ";
		$notesQuery .= "INNER JOIN LocationToNoteJoinTable ";
		$notesQuery .= "ON Notes.idNotes = LocationToNoteJoinTable.noteID ";
		$notesQuery .= "WHERE ";
		$notesQuery .= "LocationToNoteJoinTable.locationID=";
		$notesQuery .= $locationID;
		$notesResult = mysqli_query($db, $notesQuery);
		confirmQuery($notesResult);
		return($notesResult);
		
	}

//----------------
	function getDisplayPicture($imageID) {
		global $db;
		$imageQuery = "SELECT ";
		$imageQuery .= "filename ";
		$imageQuery .= "FROM ";
		$imageQuery .= "attachments ";
		$imageQuery .= "WHERE ";
		$imageQuery .= "attachmentID=";
		$imageQuery .= $imageID;
		$imageResult = mysqli_query($db, $imageQuery);
		if(!$imageResult) {
			return;
		} else {
			$imageArray = mysqli_fetch_row($imageResult);
			mysqli_free_result($imageResult);
			return($imageArray[0]);
		}
	}
//---------------------
	function getCompanyInformation($companyID) {
		global $db;
		$companyInfoQuery = "SELECT ";
		$companyInfoQuery .= "Company_Name, ";
		$companyInfoQuery .= "Website, ";
		$companyInfoQuery .= "Status, ";
		$companyInfoQuery .= "Notes ";
		$companyInfoQuery .= "FROM ";
		$companyInfoQuery .= "Manufacture ";
		$companyInfoQuery .= "WHERE ";
		$companyInfoQuery .= "ManufactureID=";
		$companyInfoQuery .= $companyID;

		$companyInfoResult = mysqli_query($db, $companyInfoQuery);
		confirmQuery($companyInfoResult);
		$companyReturn = mysqli_fetch_assoc($companyInfoResult);
		return($companyReturn);
		}
		//----------------
		function getCompanyNotes($CompanyID) {
		global $db;
		$notesQuery = "SELECT ";
		$notesQuery .= "Date, ";
		$notesQuery .= "Notes ";
		$notesQuery .= "FROM ";
		$notesQuery .= "Notes ";
		$notesQuery .= "INNER JOIN manufactureToNotesJoinTable ";
		$notesQuery .= "ON Notes.idNotes = manufactureToNotesJoinTable.NotesID ";
		$notesQuery .= "WHERE ";
		$notesQuery .= "manufactureToNotesJoinTable.ManufactureID=";
		$notesQuery .= "$CompanyID";
//		echo "<br /> Notes Query: " . $notesQuery;
		$notesResult = mysqli_query($db, $notesQuery);
		
		confirmQuery($notesResult);
		return($notesResult);
		}
		
//-------------------------
		function GetInventoryByCompany($CompanyID) {
			global $db;
			$inventoryQuery = "SELECT ";
			$inventoryQuery .= "ItemID, ";
			$inventoryQuery .= "Friendly_Name, ";
			$inventoryQuery .= "Company_Name, ";
			$inventoryQuery .= "Model ";
			$inventoryQuery .= "FROM ";
			$inventoryQuery .= "Item ";
			$inventoryQuery .= "INNER JOIN Manufacture ";
			$inventoryQuery .= "ON Item.Manufacture_ManufactureID = Manufacture.ManufactureID ";
			$inventoryQuery .= "WHERE ";
			$inventoryQuery .= "ManufactureID=";
			$inventoryQuery .= $CompanyID . " ";
			$inventoryQuery .= "ORDER BY ";
			$inventoryQuery .= "Model";
			$inventoryResults = mysqli_query($db, $inventoryQuery);
			confirmQuery($inventoryResults);
			return($inventoryResults);
		}
//----------------------
		function GetInventoryByLocation($locationID) {
			global $db;
			$inventoryQuery = "SELECT ";
			$inventoryQuery .= "ItemID, ";
			$inventoryQuery .= "Friendly_Name, ";
			$inventoryQuery .= "Company_Name, ";
			$inventoryQuery .= "Model ";
			$inventoryQuery .= "FROM ";
			$inventoryQuery .= "Item ";
			$inventoryQuery .= "INNER JOIN Location ";
			$inventoryQuery .= "ON Item.Location_LocationID = Location.LocationID ";
			$inventoryQuery .= "INNER JOIN Manufacture ON Manufacture.ManufactureID = Item.Manufacture_ManufactureID ";
			$inventoryQuery .= "WHERE ";
			$inventoryQuery .= "LocationID=";
			$inventoryQuery .= $locationID . " ";
			$inventoryQuery .= "ORDER BY ";
			$inventoryQuery .= "Company_Name, Model";
			$inventoryResults = mysqli_query($db, $inventoryQuery);
			
			confirmQuery($inventoryResults);
			return($inventoryResults);
		}
//---------------------
	function getLocationInformation($LocationID) {
		global $db;
		$locationInfoQuery = "SELECT ";
		$locationInfoQuery .= "LocationName, ";
		$locationInfoQuery .= "Building, ";
		$locationInfoQuery .= "Address, ";
		$locationInfoQuery .= "Room ";
		$locationInfoQuery .= "FROM ";
		$locationInfoQuery .= "Location ";
		$locationInfoQuery .= "WHERE ";
		$locationInfoQuery .= "LocationID=";
		$locationInfoQuery .= $LocationID;

		$locationInfoResult = mysqli_query($db, $locationInfoQuery);
		confirmQuery($locationInfoResult);
		$locatiomReturn = mysqli_fetch_assoc($locationInfoResult);
		return($locatiomReturn);
		}

?>