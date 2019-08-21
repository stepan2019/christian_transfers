<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "brgambal_eu";
$password = "brgambal20!^";
$dbname = "brgambal_eu";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error)     die("Connection failed: " . $conn->connect_error);

/*
SELECT: AEROPORTURI, AUTO, DESTINATIE
*/

$aeroporturi = array();
	$sql = "SELECT * FROM AEROPORT_UK";
	$result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
		$sql =	$conn->query('INSERT INTO AEROPORT 
			(label, nume_aeroport,url_aeroport,id_tara,
			ianuarie,februarie,martie,aprilie,mai,iunie,iulie,august,septembrie,octombrie,noiembrie,decembrie,unu,doi,sapte,
			status) 
			VALUES
			("'.$row['label'].'", "'.$row['nume_aeroport'].'", "'.$row['url_aeroport'].'","44",
			"'.$row['ianuarie'].'","'.$row['februarie'].'","'.$row['martie'].'","'.$row['aprilie'].'","'.$row['mai'].'","'.$row['iunie'].'","'.$row['iulie'].'","'.$row['august'].'","'.$row['septembrie'].'","'.$row['octombrie'].'",
			"'.$row['noiembrie'].'","'.$row['decembrie'].'","'.$row['unu'].'","'.$row['doi'].'","'.$row['sapte'].'",
			"'.$row['status'].'")') or die($conn->error);
			$insert_id = $conn->insert_id;
			
        $aeroporturi[$row['id_aeroport']] = $insert_id;
    }
	
$auto = array();
	$sql = "SELECT * FROM AUTO_UK";
	$result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
		$sql =	$conn->query('INSERT INTO AUTO 
			(nume_auto,nr_pasageri,poza1,status) 
			VALUES
			("'.$row['nume_auto'].'", "'.$row['nr_pasageri'].'", "'.$row['poza1'].'","'.$row['status'].'")');
			$insert_id = $conn->insert_id;
			
        $auto[$row['id_auto']] = $insert_id;
    }
	
$destinatii = array();
	$sql = "SELECT * FROM DESTINATIE_UK";
	$result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
		$sql =	$conn->query('INSERT INTO DESTINATIE 
			(label, nume_destinatie,url_destinatie,extra_info,status) 
			VALUES
			("'.$row['label'].'", "'.$row['nume_destinatie'].'", "'.$row['url_destinatie'].'","'.$row['extra_info'].'","'.$row['status'].'")');
			$insert_id = $conn->insert_id;
			
        $destinatii[$row['id_destinatie']] = $insert_id;
    }	
	

	//LEGATURI
	
$legaturi = array();
	$sql = "SELECT * FROM LEGATURI_UK";
	$result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
		$sql =	$conn->query('INSERT INTO LEGATURI 
			(id_aeroport,id_destinatie,km,timp,titlu_pagina,meta_pagina) 
			VALUES
			("'.$aeroporturi[$row['id_aeroport']].'", "'.$destinatii[$row['id_destinatie']].'", "'.$row['km'].'","'.$row['timp'].'","'.$row['titlu_pagina'].'","'.$row['meta_pagina'].'")');
			$insert_id = $conn->insert_id;
			
        $legaturi[$row['id_legaturi']] = $insert_id;
    }

	//PRETURI
	
$preturi = array();
	$sql = "SELECT * FROM PRETURI_UK";
	$result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
		$sql =	$conn->query('INSERT INTO PRETURI 
			(id_legaturi,id_auto,id_autobuz,pret_uk) 
			VALUES
			("'.$legaturi[$row['id_legaturi']].'", "'.$auto[$row['id_auto']].'", "0","'.$row['pret'].'")');						
			$insert_id = $conn->insert_id;
			
        $preturi[$insert_id] = $insert_id;
    }

	
	
$conn->close();
	
	echo '<br />Aeroporturi:';	print_r($aeroporturi);
	echo '<br />Auto:';	print_r($auto);
	echo '<br />Destinatii:';	print_r($destinatii);
	echo '<br />Legaturi:';	print_r($legaturi);
	echo '<br />Preturi:';	print_r($preturi);
?>
