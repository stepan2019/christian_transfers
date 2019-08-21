<?php
  include("../dbconfig.php");

  $id = $_REQUEST['id'] ;

  if (empty($id)) die("Invalid request");

  $row = $db->get_row("SELECT * FROM tfl_stop_points WHERE code = '$id' limit 1");

  if (empty($row)) die("Invalid data");

  if (empty($row->lat) OR empty($row->lon)) die("Invalid GPS data");

$gmapsKey = 'AIzaSyDhzq0vdhpQKEy4OoB81xqyGcVJw-3_LaA';

  $src = "https://maps.googleapis.com/maps/api/staticmap?time=" . time() . "&amp;" .
						"center={$row->lat},{$row->lon}&amp;" .
						"zoom=12&amp;" .
						"markers=" . urlencode("color:green|{$row->lat},{$row->lon}") . "&amp;" .
						"size=470x300&amp;" .
						"maptype=roadmap&amp;" .
						"sensor=false&amp;" .
						"key=$gmapsKey";
  $href = 'https://maps.google.com/maps?' .
						'f=d&' .
						'ie=UTF8&' .
						'msa=0&' .
						"ll={$row->lat},{$row->lon}&" .
						"zoom=12&" .
						"daddr={$row->lat},{$row->lon}";


  $rows = $db->get_results("select * from tfl_station_lines WHERE station_code = '$id' order by line_tid asc ");


  echo "<div class=\"popup_map\">\r\n";
    echo "<h1>$row->name</h1>\r\n";
    echo "<h5>Code: $row->code</h5>\r\n";
    if ($row->status) echo "<span class=\"badge badge-success\">active</span>\r\n"; else echo "<span class=\"badge badge-danger\">inactive</span>\r\n";
    echo "<h6>Location: Lat $row->lat / Lon $row->lon</h6>\r\n";
    $stopType = stopTypePrint($row->stopType);
    echo "<h6>Type: $stopType</h6>\r\n";
    echo "<h6>stationNaptan: $row->stationNaptan</h6>\r\n";

if (!empty($rows)) {
    echo "<div class=\"popup_stations\">\r\n ";
    echo "<h5>Lines for this station/stop-point</h5>\r\n";
        foreach ($rows as $row) {
            echo "<span>$row->line_tid</span> \r\n";
        }
    echo "</div>\r\n";
} else {
    echo "<span class=\"badge badge-danger\">Not found lines for this station/stop-point </span>\r\n";
}


	echo "<a href=\"" . $href . "\" rel=\"nofollow\" target=\"_blank\"><img src=\"$src\" width=\"470\" height=\"300\" alt=\"Google map\" /></a>";
	echo "<a href=\"" . $href . "\" rel=\"nofollow\" target=\"_blank\">View Google maps</a>";

  echo "</div>\r\n";



?>