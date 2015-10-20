
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
		deleteHabitat($_GET["delete"]);
	}
	if(isset($_GET["update"])){
		updateHabitat($_GET["habitat_id"], $_GET["gps_point"], $_GET["location"],  $_GET["habitat_name"],  $_GET["habitat_code"]);
	}
	
	
	//saadan return andmed siia. kõik autod objektide kujul massiivis. 
	$habitat_array = getAllData();
	$keyword = "";
	
	if(isset($_GET["keyword"])){
		
		$keyword = $_GET["keyword"];
		//otsime
		$habitat_array = getAllData($keyword);
		
	}else{
		
		//näitame kõiki tulemusi
		//kõik aut od objektide kujul massiivis
		$habitat_array = getAllData();
		
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
	<th>Habitat name</th>
	<th>Habitat code</th>
	<th>Delete</th>
	<th>Edit</th>
	<th>Edit separately</th>
	
</tr>
<?php
	
	//autod ükshaaval läbi käia.
	for($i = 0; $i < count($habitat_array); $i++){
		
		//trükib nr lauad välja.
		//lihtsalt muutujad saab echoga ka jutumärkide sees. Aga kui juba klassid ja objektid, siis on vaja lõpetada ära ja punktide vahele.
		
		//kasutaja saab rida muuta, kui aadressireale tekib edit.
		if(isset($_GET["edit"]) && $_GET["edit"] && $_GET["edit"] && $_GET["edit"] ==  $habitat_array[$i]->id){
			
			echo "<tr>";
			echo "<form action='table.php' method='get'>";
			//input mida välja ei näidata. hidden.
			echo "<input type='hidden' name='habitat_id' value='".$habitat_array[$i]->id."'>";
			
			echo "<td>".$habitat_array[$i]->id."</td>";
			echo "<td>".$habitat_array[$i]->user_id."</td>";
			echo "<td><input name='gps_point' value='".$habitat_array[$i]->gps_point."'></td>";
			echo "<td><input name='location' value='".$habitat_array[$i]->location."'></td>";
			echo "<td><input name='habitat_name' value='".$habitat_array[$i]->habitat_name."'></td>";
			echo "<td><input name='habitat_code' value='".$habitat_array[$i]->habitat_code."'></td>";
			echo "<td><input name='update' type='submit'></td>";
			echo "<td><a href='table.php'>cancel</a></td>";
			echo"</tr>";
			
		}else{
			echo "<tr><td>".$habitat_array[$i]->id."</td>";
			echo "<td>".$habitat_array[$i]->user_id."</td>";
			echo "<td>".$habitat_array[$i]->gps_point."</td>";
			echo "<td>".$habitat_array[$i]->location."</td>";
			echo "<td>".$habitat_array[$i]->habitat_name."</td>";
			echo "<td>".$habitat_array[$i]->habitat_code."</td>";
			echo "<td><a href='?delete=".$habitat_array[$i]->id."'>X</a></td>";
			echo "<td><a href='?edit=".$habitat_array[$i]->id."'>edit</a></td>";
			//lisan tulba, mis viib edit.php lehele.
			echo "<td><a href='edit.php?edit_id=".$habitat_array[$i]->id."'>edit</a></td>";
			echo "</tr>";
			//echo $habitat_array[$i]->id."<br>";
			//echo $habitat_array[$i]->gps_point."<br>";
		}
		
	}
	
?>
</table>