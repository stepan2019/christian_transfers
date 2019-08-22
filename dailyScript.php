<?php

// This script is to extract daily info from Distribution and to actualize the data in our data base for Stations, Companies. 
// Connections are made from API.

set_time_limit(0);
$host = "localhost";
$username = "brgambal_eu";
$password = "brgambal20!^";
$dbname = "brgambal_eu";
// Create connection
$conn = new mysqli($host, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "DELETE FROM aaCompanyStations, aaCompaniesName, aaBusStations, aaTrainStations, aaCities";
$result = mysqli_query($conn,$sql);

#Cities & Stations
$url = "https://api-demo.distribusion.com/retailers/v4/stations?locale=en";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type:application/json',
    'api-key: 0BmjQA8xWawvaJp91drMt766JyIfRJQ3Q60yeqzj',
    'affiliate_partner_number: 733284',
));
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
$result = curl_exec($ch);
$error = curl_error($ch);
if ($error) echo $error;
else{
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($result, 0, $header_size);
    $body = substr($result, $header_size);
    $result1 = json_decode($body,true);
    #Cities
    foreach ($result1["included"] as $res){  	
        $id = $res["attributes"]["code"];
        $name = $res["attributes"]["name"];
        $sql = "INSERT INTO aaCities (id, name) VALUES ('$id', '$name')";
        $result = mysqli_query($conn,$sql);
    }
    #Stations
    foreach ($result1["data"] as $data){  	
        $id = $data["id"];
        $station_type = $data["attributes"]["station_type"];
        $name = $data["attributes"]["name"];
		$description = $data["attributes"]["description"];
		$street_and_number = $data["attributes"]["street_and_number"];
		$zip_code = $data["attributes"]["zip_code"];
		$longitude = $data["attributes"]["longitude"];
		$latitude = $data["attributes"]["latitude"];
		$time_zone = $data["attributes"]["time_zone"];
        $city = $data["relationships"]["city"]["data"]["id"];
        $type = $data["attributes"]["station_type"];
        if($type == "bus_station"){
            $sql = "INSERT INTO aaBusStations (id, name, description, street_and_number, zip_code, longitude, latitude, time_zone, station_type, city_id) VALUES ('$id', '$name', '$description', '$street_and_number', '$zip_code', '$longitude', '$latitude', '$time_zone', '$station_type', '$city')";
            $result = mysqli_query($conn,$sql);
        }
        if($type == "train_station"){
            $sql = "INSERT INTO aaTrainStations (id, name, description, street_and_number, zip_code, longitude, latitude, time_zone, station_type, city_id) VALUES ('$id', '$name', '$description', '$street_and_number', '$zip_code', '$longitude', '$latitude', '$time_zone', '$station_type', '$city')";
            $result = mysqli_query($conn,$sql);
        }

    }
}

#Companies & CompanyStations
$url = "https://api-demo.distribusion.com/retailers/v4/marketing_carriers";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type:application/json',
    'api-key: 0BmjQA8xWawvaJp91drMt766JyIfRJQ3Q60yeqzj',
    'affiliate_partner_number: 733284',
));
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
$result = curl_exec($ch);
$error = curl_error($ch);
if ($error) echo $error;
else{
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($result, 0, $header_size);
    $body = substr($result, $header_size);
    $result1 = json_decode($body,true);

    foreach ($result1["data"] as $companies){
        $id = $companies["attributes"]["code"];
        $name = $companies["attributes"]["trade_name"];
        $sql3 = "INSERT INTO aaCompaniesName (id, name) VALUES ('$id', '$name')";
        $result = mysqli_query($conn,$sql3);   

        $companyCode = $companies["attributes"]["code"];
        #CompanyStations
        $url = "https://api-demo.distribusion.com/retailers/v4/marketing_carriers/".$companyCode."/stations";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'api-key: 0BmjQA8xWawvaJp91drMt766JyIfRJQ3Q60yeqzj',
            'affiliate_partner_number: 733284',
        ));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $result = curl_exec($ch);
        $error = curl_error($ch);
        if ($error) echo $error;
        else{
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($result, 0, $header_size);
            $body = substr($result, $header_size);
            $result2 = json_decode($body,true);
            if(array_key_exists("data", $result2)){
                foreach ($result2["data"] as $companyStation){
                    $station = $companyStation["id"];
                    $sql = "INSERT INTO aaCompanyStations (companyID, stationID) VALUES ('$companyCode', '$station')";
                    $result = mysqli_query($conn,$sql); 
                }
            }
        }
    }
}

?>