<?php //Kõik andmebaasidega seoduv AB
		//lisa, kuva, muuda - kodus
	//loome AB ühenduse
    require_once("../config_global.php");
    $database = "if15_skmw";
   
	//selleks, et kuvada tabel lehel vĆ¤lja. 
	
	//vaikeväärtus on keywordil, et vältida errorit, mis tekiks real $car_array = getAllData(); table.php's
	function getAllData($keyword=""){
		
		if($keyword == ""){
			
			$search = "%%";
			//ei otsi
			
		}else{
			
			//otsime
			$search = "%".$keyword."%";
			
		}
	
		
		//deleted is NULL, ei ole kustutatud
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, user_id, number_plate, color FROM car_plates WHERE deleted IS NULL AND (number_plate LIKE ? OR color LIKE ?)");
		$stmt->bind_param("ss", $search, $search);
	//kuna küsimärke pole, siis bind_param jääb vahele.
	
	//seob selle, mis tabelist saadud, nende muutujatega bind result.
		$stmt->bind_result($id_from_db, $user_id_from_db, $number_plate_from_db, $color_from_db);
		$stmt->execute();
		
		//tekitame massiivi, kus hoiame auto nr KUHU.
		$array = array();
		$array = array();
		
		
		//iga rea kohta mid on ab's
        while($stmt->fetch()){
			
           //suvaline muutuja, kus hoiame auto andmeid selle hetkeni kui lisame massiivi
		   
		   //StdClass on tühi objekt, kus hoiame väärtusi. MIDA
		   $car = new StdClass();
		   
		   $car->id = $id_from_db;
		   $car->user_id = $user_id_from_db;
		   $car->number_plate = $number_plate_from_db;
		   $car->color = $color_from_db;
		   
		   
		   
		   //lisan massiivi. esiteks, KUHU lisame, teiseks, MIDA.
		   
		   array_push($array, $car);
		   //var_dump võib echo asemel kasutada kui error on. annab andmed välja
		   //echo "<pre>";
		   //var_dump($array);
		   //echo "</pre>"; //selleks, et korrastaks var_dump andmeid.

        }
		//peale while tsüklit tagastame, et saaks table.php kasutada seda.
		return $array;

        $stmt->close();
		$mysqli->close();
		
	}
	function deleteCarData($car_id){
		 $mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		 
		 //uuendan välja deleted, lisan praeguse date'i
		 $stmt = $mysqli->prepare("UPDATE car_plates SET deleted=NOW() WHERE id=?");
		 $stmt->bind_param("i", $car_id);
		 $stmt->execute();
		 
		 //tühjendame aadressiriba, siis ei jää sinna ?deleted=10 rida.
		 header("Location: table.php");
		 
		 $stmt->close();
		 $mysqli->close();
	}
	
	//uuendab muudatused ja salvestab ära.
	function updateCarData($car_id, $number_plate, $color){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("UPDATE car_plates SET number_plate=?, color=? WHERE id=?");
		$stmt->bind_param("ssi", $number_plate, $color, $car_id);
		$stmt->execute();
		header("Location: table.php");
		$stmt->close();
		$mysqli->close();
	}

		//paneme sessiooni serveris tööle, saame kasutada SESSION[] muutujaid. mysql
    session_start();
	
	
    //check connection
   
	
	
	function logInUser($email, $hash){
		
		//muutuja väljaspool funktsiooni. GLOBALS saab kätte kõik muutujad msi kasutusel.
		 $mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?"); 
        $stmt->bind_param("ss", $email, $hash);
        $stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
			if($stmt->fetch()){
                    echo "Kasutaja logis sisse id=".$id_from_db;
					
					//sessioon salvestatakse serveris
					$_SESSION['logged_in_user_id'] =  $id_from_db;
					$_SESSION['logged_in_user_email'] =  $email_from_db;
					//suuname kasutaja teisele lehele
					header("Location: data.php");
					
                }else{
                    echo "Wrong credentials!";
                }
                $stmt->close();
				$mysqli->close();
	}
	
	
	//siin võtan login.phpst vastu need muutujad.
	function createUser($create_email, $hash){
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
		$stmt->bind_param("ss", $create_email, $hash);
		$stmt->execute();
        $stmt->close();
		$mysqli->close();	
	}
	
	
	function createCarPlate($car_plate, $color){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO car_plates (user_id, number_plate, color) VALUES (?,?,?)");
		echo $mysqli->error;
		//i on int user id jaoks.
		$stmt->bind_param("iss", $_SESSION['logged_in_user_id'], $car_plate, $color);
		
		//muutuja selleks, mida ta Ć¼tleb.
		$message = "";
		
		//kui Ćµnnestus, siis tĆµene kui ei (viga), siis else.
		if($stmt->execute()){
			//Ćµnnestus
			$message = "Edukalt lisatud andmebaasi!";
		}else{
			//ei Ćµnnestunud
		}
		
        $stmt->close();
		$mysqli->close();
		
		//saadan selle Ćµnnestumise vĆµi mitteĆµnnestumise tagasi.
		return $message;
	}
	
	//selleks, et kuvada tabel lehel vĆ¤lja. 
	
?>