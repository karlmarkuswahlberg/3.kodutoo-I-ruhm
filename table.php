
<?php
	require_once("functions.php"); //selleks, et leiaks üles getAllData fn.
	//kõik mis functions.php tehti, kuvab siia.
	if(!isset($_SESSION['logged_in_user_id'])){
		header("Location: login.php");
	}
	//aadressireale tekib ?logout=1, seega kasutame GET funktsiooni. erinevus GET ja PUSH oli see, et PUSH polndu nähtav adre real
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php"); //see on kuhu suunab peale klõpsu
	}
	
	//kuulan, kas kasutaja tahab kustutada. aadressiribal ?delete=2 nt. selle järgi.
	if(isset($_GET["delete"])){
		
		//saadan kustutatava auto id
		deleteCarData($_GET["delete"]);
	}
	if(isset($_GET["update"])){
		updateCarData($_GET["car_id"], $_GET["gps_point"], $_GET["location"]);
	}
	
	
	//saadan return andmed siia. kõik autod objektide kujul massiivis. 
	$car_array = getAllData();
	$keyword = "";
	
	if(isset($_GET["keyword"])){
		
		$keyword = $_GET["keyword"];
		//otsime
		$car_array = getAllData($keyword);
		
	}else{
		
		//näitame kõiki tulemusi
		//kõik aut od objektide kujul massiivis
		$car_array = getAllData();
		
	}
	
?>

<h1>Elupaikade tabel</h1>

<a href="data.php">Sisesta uus elupaik</a><br>
<br>
<form action="table.php" method="get">
	<input name="keyword" type="search" value="<?=$keyword?>">
	<input type="submit" value="otsi">
</form>

<table border=1>
<tr>
	<th>ID</th>
	<th>User ID</th>
	<th>GPS point</th>
	<th>Location</th>
	<th>Habitat code</th>
	<th>Habitat name</th>
	<th>Delete</th>
	<th>Edit</th>
	<th>Edit separately</th>
	
</tr>
<?php
	
	//autod ükshaaval läbi käia.
	for($i = 0; $i < count($car_array); $i++){
		
		//trükib nr lauad välja.
		//lihtsalt muutujad saab echoga ka jutumärkide sees. Aga kui juba klassid ja objektid, siis on vaja lõpetada ära ja punktide vahele.
		
		//kasutaja saab rida muuta, kui aadressireale tekib edit.
		if(isset($_GET["edit"]) && $_GET["edit"] ==  $car_array[$i]->id){
			
			echo "<tr>";
			echo "<form action='table.php' method='get'>";
			//input mida välja ei näidata. hidden.
			echo "<input type='hidden' name='car_id' value='".$car_array[$i]->id."'>";
			echo "<td>".$car_array[$i]->id."</td>";
			echo "<td>".$car_array[$i]->user_id."</td>";
			echo "<td><input name='gps_point' value='".$car_array[$i]->gps_point."'></td>";
			echo "<td><input name='location' value='".$car_array[$i]->location."'></td>";
			echo "<td><input name='habitat_name' value='".$car_array[$i]->habitat_name."'></td>";
			echo "<td><input name='habitat_code' value='".$car_array[$i]->habitat_code."'></td>";
			echo "<td><input name='update' type='submit'></td>";
			echo "<td><a href='table.php'>cancel</a></td>";
			echo"</tr>";
			
		}else{
			echo "<tr><td>".$car_array[$i]->id."</td>";
			echo "<td>".$car_array[$i]->user_id."</td>";
			echo "<td>".$car_array[$i]->gps_point."</td>";
			echo "<td>".$car_array[$i]->location."</td>";
			echo "<td>".$car_array[$i]->habitat_name."</td>";
			echo "<td>".$car_array[$i]->habitat_code."</td>";
			echo "<td><a href='?delete=".$car_array[$i]->id."'>X</a></td>";
			echo "<td><a href='?edit=".$car_array[$i]->id."'>edit</a></td>";
			//lisan tulba, mis viib edit.php lehele.
			echo "<td><a href='edit.php?edit_id=".$car_array[$i]->id."'>edit</a></td>";
			echo "</tr>";
			//echo $car_array[$i]->id."<br>";
			//echo $car_array[$i]->gps_point."<br>";
		}
		
	}
	
?>
</table>