<?php

	require_once("edit_functions.php"); //see on configi fail, mis viitab tabelile, ja edit kasutab seda.
	//edit.php

	
	if(isset($_GET["update"])){
		updateCarData($_GET["car_id"], $_GET["gps_point"], $_GET["location"]);
		
	}
	//trükin aadressirealt muutuja
	
	//siis ei anna errorit kui minna edit.php lehele ja pole lõpus kirjas edit_id
	if(isset($_GET["edit_id"])){
		
		echo $_GET["edit_id"];
		
		//küsin andmeid.
		//muutuja "car" saab andmed ühe auto kohta ja siis hoiab neid kõiki.
		//getSingleCarData tuleb kõik edit_functions lehelt. Seal on selle sisu
		$car = getSingleCarData($_GET["edit_id"]);
		var_dump ($car);
		
	}else{
		
		//kui muutujat pole (on nt juba enne kustutatud)
		
		//suunan tagasi lehele table.php
		header("Location: table.php");
	}
	

?>

<!--Salvestamiseks kasutan table.php rida updateCarData($_GET["car_id"], $_GET["gps_point"], $_GET["location"]); updateCar() -->

<form action="edit.php" method="get">
	<input name="car_id" type="hidden" value="<?=$_GET["edit_id"];?>">
	<input name="gps_point" type="text" value="<?=$car->gps_point;?>"><br> <!--siit läheb reale updateCarData. Siis läheb edit_functions.php. $stmt->bind_param ja siis $stmt = $mysqli->prepare-->
	<input name="location" type="text" value="<?=$car->location;?>"><br>
	<input name="update" type="submit"><br>
	

</form>