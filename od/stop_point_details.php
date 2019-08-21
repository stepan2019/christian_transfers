<?php
    include("dbconfig.php");
    $id = $_REQUEST['id'] ;
    if (empty($id)) die("Invalid request");

    $row = $db->get_row("SELECT * FROM tfl_stop_points WHERE code = '$id' limit 1");
    if (empty($row)) die("Invalid data");

    $title = "London " . $row->name;  $selected = 5;
	$breadcrumb =" $row->name";
	$description = "London stop points - $row->name - $row->stopType- Christian Transfers";
    include("include/_header.php");
?>



<?php
echo "<div class=\"row\">\r\n ";
    echo "<div class=\"col\">\r\n ";
        echo "<h1>$row->name</h1>\r\n";
        echo "<h5>Code: $row->code</h5>\r\n";
        echo "<h6>Location: Lat $row->lat / Lon $row->lon</h6>\r\n";

        $stopType = stopTypePrint($row->stopType);
        echo "<h6>Type: $stopType</h6>\r\n";
        echo "<h6>stationNaptan: $row->stationNaptan</h6>\r\n";


        echo Location2map($row->lat,$row->lon);
        echo Location2href($row->lat,$row->lon);


    echo "</div><!--/col -->\r\n";


    $rows = $db->get_results("select * from tfl_station_lines WHERE station_code = '$id' order by line_tid asc ");

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


echo "</div><!--/row-->\r\n";


    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h1>Stop Points</h1></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

    $q = "SELECT * FROM tfl_stop_points order by name asc limit 5000";

    $rows3 = $db->get_results($q);
    if (!empty($rows3)) {
        echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableStopPoints\">\r\n";
            echo "<thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Lines</th><th>Map</th></tr></thead>\r\n";
            echo "<tbody>\r\n";

            echo "</tbody>\r\n";
        echo "</table>\r\n";
    }//!empty($rows)

        echo "</div>\r\n";
    echo "</div>\r\n";





?>
<?php include("include/_footer.php"); ?>