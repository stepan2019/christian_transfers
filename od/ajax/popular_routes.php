<?php
  include("../dbconfig.php");

$rows2 = $db->get_results("SELECT `station_code`,count(`station_code`) as cnt FROM `tfl_station_lines` group by `station_code` order by cnt desc limit 40");

foreach ($rows2 as $row2) {
    $details = $db->get_row("select * from tfl_stop_points WHERE code='$row2->station_code' ");
    if (!empty($details)) {
        $row2->name = $details->name;
        $row2->lat = $details->lat;
        $row2->lon = $details->lon;
    }
}


$data = array();
foreach ($rows2 as $row2) {
    $lines = "";
    $rows4 = $db->get_results("select * from tfl_station_lines WHERE station_code = '$row2->station_code' order by line_tid asc ");

    if (!empty($rows4)) {
        foreach ($rows4 as $row4) {
            $lines .= "$row4->line_tid, ";
        }
    }


    $data[] = array('id'=> $row2->station_code , 'title' => $row2->name , 'longitude' => $row2->lat, 'latitude' => $row2->lon, 'lines' => $lines);
}

header('Content-type: application/json');
die(json_encode($data));

?>