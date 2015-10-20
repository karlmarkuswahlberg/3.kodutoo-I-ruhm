<?php

	require_once("edit_functions.php"); //see on configi fail, mis viitab tabelile, ja edit kasutab seda.
	//edit.php

	
	if(isset($_GET["update"])){
		updateHabitat($_GET["habitat_id"], $_GET["gps_point"], $_GET["location"], $_GET["habitat_name"], $_GET["habitat_code"]);
		
	}
	//trükin aadressirealt muutuja
	
	//siis ei anna errorit kui minna edit.php lehele ja pole lõpus kirjas edit_id
	if(isset($_GET["edit_id"])){
		
		echo $_GET["edit_id"];
		
		//küsin andmeid.
		//muutuja "car" saab andmed ühe auto kohta ja siis hoiab neid kõiki.
		//getHabitatData tuleb kõik edit_functions lehelt. Seal on selle sisu
		$habitat = getHabitatData($_GET["edit_id"]);
		
		
	}else{
		
		//kui muutujat pole (on nt juba enne kustutatud)
		
		//suunan tagasi lehele table.php
		header("Location: table.php");
	}
	

?>

<!--Salvestamiseks kasutan table.php rida updateHabitat($_GET["habitat_id"], $_GET["gps_point"], $_GET["location"]); updateCar() -->

<form action="edit.php" method="get">
	<input name="habitat_id" type="hidden" value="<?=$_GET["edit_id"];?>">
	<input name="gps_point" type="text" value="<?=$habitat->gps_point;?>"> GPS-PUNKT<br> <!--siit läheb reale updateHabitat. Siis läheb edit_functions.php. $stmt->bind_param ja siis $stmt = $mysqli->prepare-->
	<input name="location" type="text" value="<?=$habitat->location;?>"> Asukoht<br>
	<input name="habitat_name" type="text" value="<?=$habitat->habitat_name;?>"> Elupaiga nimi<br>
	<input name="habitat_code" type="text" value="<?=$habitat->habitat_code;?>"> Elupaiga kodeering<br>
	<input name="update" type="submit"><br>
	

</form>