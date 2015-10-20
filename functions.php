<?php //Kõik andmebaasidega seoduv AB
		//lisa, kuva, muuda - kodus
	//loome AB ühenduse
    require_once("../config_global.php");
    $database = "if15_skmw";
   
	//selleks, et kuvada tabel lehel vĆ¤lja. 
	
	//vaikeväärtus on keywordil, et vältida errorit, mis tekiks real $habitat_array = getAllData(); table.php's
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
		$stmt = $mysqli->prepare("SELECT id, user_id, gps_point, location, habitat_name, habitat_code FROM habitat_data WHERE deleted IS NULL AND (gps_point LIKE ? OR location LIKE ? OR habitat_name LIKE ? OR habitat_code LIKE ?)");
		$stmt->bind_param("ssss", $search, $search, $search, $search);
	//kuna küsimärke pole, siis bind_param jääb vahele.
	
	//seob selle, mis tabelist saadud, nende muutujatega bind result.
		$stmt->bind_result($id_from_db, $user_id_from_db, $gps_point_from_db, $location_from_db, $habitat_name_from_db, $habitat_code_from_db);
		$stmt->execute();
		
		//tekitame massiivi, kus hoiame auto nr KUHU.
		$array = array();
		$array = array();
		$array = array();
		$array = array();
		
		//iga rea kohta mid on ab's
        while($stmt->fetch()){
			
           //suvaline muutuja, kus hoiame auto andmeid selle hetkeni kui lisame massiivi
		   
		   //StdClass on tühi objekt, kus hoiame väärtusi. MIDA
		   $habitat = new StdClass();
		   
		   $habitat->id = $id_from_db;
		   $habitat->user_id = $user_id_from_db;
		   $habitat->gps_point = $gps_point_from_db;
		   $habitat->location = $location_from_db;
		   $habitat->habitat_name = $habitat_name_from_db;
		   $habitat->habitat_code = $habitat_code_from_db;
		   
		   
		   
		   
		   //lisan massiivi. esiteks, KUHU lisame, teiseks, MIDA.
		   
		   array_push($array, $habitat);
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
	function deleteHabitat($habitat_id){
		 $mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		 
		 //uuendan välja deleted, lisan praeguse date'i
		 $stmt = $mysqli->prepare("UPDATE habitat_data SET deleted=NOW() WHERE id=?");
		 $stmt->bind_param("i", $habitat_id);
		 $stmt->execute();
		 
		 //tühjendame aadressiriba, siis ei jää sinna ?deleted=10 rida.
		 header("Location: table.php");
		 
		 $stmt->close();
		 $mysqli->close();
	}
	
	//uuendab muudatused ja salvestab ära.
	function updateHabitat($habitat_id, $gps_point, $location, $habitat_name, $habitat_code){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("UPDATE habitat_data SET gps_point=?, location=?, habitat_name=?, habitat_code=? WHERE id=?");
		$stmt->bind_param("issii", $gps_point, $location, $habitat_name, $habitat_code, $habitat_id);
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
	
	
	function createHabitat($habitat_plate, $location, $habitat_name, $habitat_code){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO habitat_data (user_id, gps_point, location, habitat_name, habitat_code) VALUES (?,?,?,?,?)");
		echo $mysqli->error;
		//i on int user id jaoks.
		$stmt->bind_param("iissi", $_SESSION['logged_in_user_id'], $habitat_plate, $location, $habitat_name, $habitat_code);
		
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