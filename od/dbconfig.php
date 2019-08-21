<?php
error_reporting(0);ini_set("display_errors",0);
//error_reporting(E_ALL);ini_set("display_errors",1);

$dbhost="localhost";
$dbuser="brgambal_tfl";
$dbpass="TflDB123@@";
$dbname="brgambal_tfl";

define('APP_ID', 'ddbff9a7');
define('APP_KEY','58b08fffb7b2f5e9bd3629a0f19db77a');

define('ROOT', '/home/brgambal/public_html/christiantransfers.eu/od');
define('URL', 'https://www.christiantransfers.eu/od');

define('GMAP_KEY', 'AIzaSyDhzq0vdhpQKEy4OoB81xqyGcVJw-3_LaA');

/*
every hour php /home/brgambal/public_html/christiantransfers.eu/od/import/air-quality.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1
01,12  php /home/brgambal/public_html/christiantransfers.eu/od/import/places.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1
10min php /home/brgambal/public_html/christiantransfers.eu/od/import/car-park.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1
02 php /home/brgambal/public_html/christiantransfers.eu/od/import/bike-points.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1

1hour php /home/brgambal/public_html/christiantransfers.eu/od/import/line-routes.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1
1hour php /home/brgambal/public_html/christiantransfers.eu/od/import/line-routes.php night >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1

1hour php /home/brgambal/public_html/christiantransfers.eu/od/import/stop-points.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1

*/



	include_once "ezsql/shared/ez_sql_core.php";
	include_once "ezsql/mysql/ez_sql_mysql.php";
	$db = new ezSQL_mysql($dbuser,$dbpass,$dbname,$dbhost);
    //**************************************************************
//$db->debug_all = true;
    $db->query("SET NAMES utf8");


$arrForecastBand = array(
    'Low'       => array('class' => 'band-low', 'atRisk' => "<b>Enjoy</b> your usual outdoor activities.", 'general' => "<b>Enjoy</b> your usual outdoor activities."),
    'Moderate'  => array('class' => 'band-moderate', 'atRisk' => "Adults and children with lung problems, and adults with heart problems, <b>who experience symptoms</b>, should consider reducing strenuous physical activity, particularly outdoors. ", 'general' => "<b>Enjoy</b> your usual outdoor activities."),
    'High'      => array('class' => 'band-high', 'atRisk' => "Adults and children with lung problems, and adults with heart problems, should <b>reduce</b> strenuous physical exertion, particularly outdoors, and particularly if they experience symptoms. People with asthma may find they need to use their reliever inhaler more often. Older people should also <b>reduce</b> physical exertion. ", 'general' => "Anyone experiencing discomfort such as sore eyes, cough or sore throat should <b>consider reducing</b> activity, particularly outdoors. "),
    'Very High' => array('class' => 'band-very-high', 'atRisk' => "Adults and children with lung problems, adults with heart problems, and older people, should <b>avoid</b> strenuous physical activity. People with asthma may find they need to use their reliever inhaler more often. ", 'general' => "<b>Reduce</b> physical exertion, particularly outdoors, especially if you experience symptoms such as cough or sore throat."),

);

$filter = array(',','-','.','_');

    $placeTypes = $db->get_results("SELECT id,name FROM `tfl_place_types` WHERE disabled='0' order by name asc ");

    foreach ($placeTypes as $type) {
        $cnt = $db->get_var("select count(id) from tfl_places WHERE placeType='$type->name' ");
        $type->cnt = $cnt;
    }




function forecastBand($str) {
    switch ($str) {
    case 'Low':
        return  "";
        break;
    case 'Moderate':
        return  "";
        break;
    case 'High':
        return  "";
        break;
    case ' Very High ':
        return  "";
        break;
    default:
        return  $str;
        break;
    }
}



function stopTypePrint($str) {
    switch ($str) {
    case 'NaptanBusCoachStation':
        return  "Coach-Station";
        break;
    case 'NaptanCoachBay':
        return  "Coach-Bay";
        break;
    case 'NaptanFerryPort':
        return  "Ferry-Port";
        break;
    case 'NaptanMetroStation':
        return  "Metro-Station";
        break;
    case 'NaptanOnstreetBusCoachStopCluster':
        return  "Coach-StopCluster";
        break;
    case 'NaptanOnstreetBusCoachStopPair':
        return  "Coach-StopPair";
        break;
    case 'NaptanPublicBusCoachTram':
        return  "Coach-Tram";
        break;
    case 'NaptanRailStation':
        return  "Rail-Station";
        break;
    case '':
        return  "Station";
        break;
    default:
        return  $str;
        break;
    }
}

function placeTypePrint($str) {
    switch ($str) {
    case 'BikePoint':
        return  "Bike Point";
        break;
    case 'CarPark':
        return  "Car Park";
        break;
    case 'ChargeConnector':
        return  "Charge Connector";
        break;
    case 'ChargeStation':
        return  "Charge Station";
        break;
    case 'CoachBay':
        return  "Coach Bay";
        break;
    case 'CoachPark':
        return  "Coach Park";
        break;
    case 'CyclePark':
        return  "Cycle Park";
        break;
    case 'OtherCoachParking':
        return  "Other Coach Parking";
        break;
    case 'TaxiRank':
        return  "Taxi Rank";
        break;
    default:
        return  $str;
        break;
    }
}




function safe_json_encode($value, $options = 0, $depth = 512, $utfErrorFlag = false) {
    $encoded = json_encode($value, $options, $depth);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            return $encoded;
        case JSON_ERROR_DEPTH:
            return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_STATE_MISMATCH:
            return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_CTRL_CHAR:
            return 'Unexpected control character found';
        case JSON_ERROR_SYNTAX:
            return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_UTF8:
            $clean = utf8ize($value);
            if ($utfErrorFlag) {
                return 'UTF8 encoding error'; // or trigger_error() or throw new Exception()
            }
            return safe_json_encode($clean, $options, $depth, true);
        default:
            return 'Unknown error'; // or trigger_error() or throw new Exception()

    }
}

function safe_json_decode($value, $options = 0, $depth = 512, $utfErrorFlag = false) {
    $encoded = json_decode($value, $options, $depth);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            return $encoded;
        case JSON_ERROR_DEPTH:
            return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_STATE_MISMATCH:
            return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_CTRL_CHAR:
            return 'Unexpected control character found';
        case JSON_ERROR_SYNTAX:
            return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_UTF8:
            $clean = utf8ize($value);
            if ($utfErrorFlag) {
                return 'UTF8 encoding error'; // or trigger_error() or throw new Exception()
            }
            return safe_json_decode($clean, $options, $depth, true);
        default:
            return 'Unknown error'; // or trigger_error() or throw new Exception()

    }
}


function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } else if (is_string ($mixed)) {
        return utf8_encode($mixed);
    }
    return $mixed;
}

// Define the errors.
$constants = get_defined_constants(true);
$json_errors = array();
foreach ($constants["json"] as $name => $value) {
    if (!strncmp($name, "JSON_ERROR_", 11)) {
        $json_errors[$value] = $name;
    }
}


function dateFix($str){
    //20190421 -> 2019-04-21
    if (empty($str)) return "";
    return date("d.m.Y",strtotime($str));
}

function timeFix($str){
    //2010 -> 20:10
    if (empty($str)) return "";
    return substr($str,0,2) . ":" . substr($str,2,2);
}


function Location2map($lat,$lon,$width = 470,$height = 300) {
    if (empty($lat) OR empty($lon)) return "";
    $href = 'https://maps.google.com/maps?' .'f=d&' .'ie=UTF8&' .'msa=0&' ."ll={$lat},{$lon}&" ."zoom=12&" ."daddr={$lat},{$lon}";

    $src = "https://maps.googleapis.com/maps/api/staticmap?time=" . time() . "&amp;" ."center={$lat},{$lon}&amp;" ."zoom=12&amp;" ."markers=" . urlencode("color:green|{$lat},{$lon}") . "&amp;" ."size=" . $width . "x" . $height . "&amp;" ."maptype=roadmap&amp;" ."sensor=false&amp;" ."key=" . GMAP_KEY;
    return "<a href=\"" . $href . "\" rel=\"nofollow\" class=\"gmaps_url\" target=\"_blank\"><img src=\"$src\" width=\"$width\" height=\"$height\" alt=\"Google map\" /></a>";
}

function Location2href($lat,$lon) {
    if (empty($lat) OR empty($lon)) return "";

    $href = 'https://maps.google.com/maps?' .'f=d&' .'ie=UTF8&' .'msa=0&' ."ll={$lat},{$lon}&" ."zoom=12&" ."daddr={$lat},{$lon}";
    return "<a href=\"" . $href . "\" rel=\"nofollow\"  class=\"gmaps_url2\" target=\"_blank\">View Google maps</a>";
}


function slug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    $slug = trim($slug,"-");
    return $slug;
}

?>