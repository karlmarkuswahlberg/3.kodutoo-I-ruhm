<?php	
	//edit_function.php,. siia tulevad funktsioonid.
	
	require_once("../config_global.php");
    $database = "if15_skmw";

		//leian ühe auto andmed, siis kogun need ja saadan need tagasi ¤car
	function getHabitatData($id){
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT gps_point, location, habitat_name, habitat_code FROM habitat_data WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $id);
		$stmt->bind_result($gps_point, $location, $habitat_name, $habitat_code);
		$stmt->execute();
		
		//auto objekt
		$habitat = new StdClass();
		
		
		//kas sain rea andmeid
		
		if($stmt->fetch()){
			
			$habitat->gps_point = $gps_point;
			$habitat->location = $location;
			$habitat->habitat_name = $habitat_name;
			$habitat->habitat_code = $habitat_code;
			
			
		}else{
			
			//ei tulnud rida, kui ID'd ei ole.,
			//või on kustutatud
			header("Location: table.php");
			
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $habitat;
	}
	
	
	function updateHabitat($habitat_id, $gps_point, $location, $habitat_name, $habitat_code){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("UPDATE habitat_data SET gps_point=?, location=?, habitat_name=?, habitat_code=? WHERE id=?");
		$stmt->bind_param("issii", $gps_point, $location, $habitat_name, $habitat_code, $habitat_id);
		$stmt->execute();
		header("Location: table.php");
		$stmt->close();
		$mysqli->close();
	}
?>