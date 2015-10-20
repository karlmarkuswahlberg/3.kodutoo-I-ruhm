<?php
	require_once("functions.php");
	
	//kui kasutaja ei ole sisselogitu, suuna teisele lehele. Ei saaks login.php lehele uuesti
	//kontrollin kas sessioonimuutuja on olemas? 
	if(!isset($_SESSION['logged_in_user_id'])){
		header("Location: table.php");
	}
	//aadressireale tekib ?logout=1, seega kasutame GET funktsiooni. erinevus GET ja PUSH oli see, et PUSH polndu nähtav adre real
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php"); //see on kuhu suunab peale klõpsu
	}
	//lühend <?php > on <?= //>?
	$m = "";
	$car_plate = $location = "";
	$car_plate_error = $location_error = "";
	
	$gps_point = $habitat_name = $habitat_code = "";
	$gps_point_error = $habitat_code_error = $habitat_name_error = "";
	
	//lisada kasutaja id, numbrilaud ja värv.
	
	//kontrollime välju.
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if(isset($_POST["add_car_plate"])){
		
		if ( empty($_POST["car_plate"]) ) {
				$car_plate_error = "See väli on kohustuslik";
			}else{
				$car_plate = cleanInput($_POST["car_plate"]);
			}	
		if ( empty($_POST["location"]) ) {
				$location_error = "See väli on kohustuslik";
			}else{
				$location = cleanInput($_POST["location"]);
			}	
	}
	
	//erroreid ei olnud, käivitan funktsiooni mis sisestab andmebaasi need 2 väärtust. functions.php
	if($car_plate_error == "" && $location_error ==""){
		//m on message, mille saadame functions.php failist.
		$m = createCarPlate($car_plate, $location);
		
		if($m != ""){
			//teeme vormi tühjaks
			$car_plate = "";
			$location = "";
			}
		}
	}
	
	

	
	function cleanInput($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
  }
  
  //küsime tabeli kujul andmed. kõige lõpus seepärast, et see on kõige viimasem versioon.
  getAllData();
  
	
  
?>

Tere, <?=$_SESSION['logged_in_user_email'];?> <a href="?logout=1">Logi välja!</a><br>
<a href="table.php">Vaata tabelit</a>

<h2>Lisa uus</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
    <label for="car_plate"> GPS punkt </label>
  	<input id="car_plate" name="car_plate" type="text" value="<?=$car_plate;?>"> <?=$car_plate_error;?><br><br>
	<label for="location"> Asukoht </label>
    <input id="location" name="location" type="text" value="<?=$location;?>"> <?=$location_error;?><br><br>
	<input type="submit" name="add_car_plate" value="Lisa">
	<p style="location:green;"><?=$m;?></p>
  </form>
