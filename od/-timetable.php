<?php
    include_once("dbconfig.php");
    $selected = 4;

    $title = "Timetable " . $_GET['station'] . " - " . $_GET['line'] ;
    $breadcrumb = "Timetable";
    $description = "Timetable " . $_GET['station'] . " - " . $_GET['line'] ;             

    include("include/_header.php");
?>

<h2>Timetable for Station Line</h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->


<form action="" method="get">
    <div class="form-row">
        <div class="col">
            <input type="text" class="form-control" placeholder="Station" name="station"  required="required" value="<?=$_GET['station']?>">
            <small>490014493E, 490005646F, 490004046W / "West Middlesex Hospital", "County Hall", "Fyfield Road"</small>
        </div>
        <div class="col">
            <input type="text" class="form-control" name="line" placeholder="Line" required="required" value="<?=$_GET['line'] ?>">
            <small>110, 381, 230</small>
        </div>
        <div class="col">
           <button type="submit" class="btn btn-success" name="sbm" value="1"><i class="far fa-clock"></i> Get timetable</button>
        </div>
        </div>
</form>

<?php

    if (!empty($_GET['station']) AND !empty($_GET['line'])) {
        echo "<hr/>\r\n";
        $station = trim($_GET['station']);
        $line    = trim($_GET['line']);

        $exist_station = $db->get_row("select * from tfl_stations WHERE code = '$station' OR name like '%$station%' limit 1 ");
        if (empty($exist_station)) {
            echo "<div class=\"alert alert-warning\">Not found station: <b>$station</b></div>\r\n ";
        }

        $exist_line = $db->get_row("select * from tfl_lines WHERE tid = '$line' limit 1 ");
        if (empty($exist_line)) {
            echo "<div class=\"alert alert-warning\">Not found line: <b>$line</b></div>\r\n ";
        }

        $exist_route = $db->get_row("select * from tfl_line_routes WHERE line_tid = '$line' AND (originator = '$station' OR destination = '$station' ) limit 1 ");
        if (empty($exist_route)) {
            echo "<div class=\"alert alert-danger\">Not found line: <b>$line</b> for station: <b>$station</b></div>\r\n ";
        }


        if (!empty($exist_station) AND !empty($exist_line))  {
            $now = date("Y-m-d");
            echo "<h2>Timetable for station: <em>#$exist_station->name</em> on line: <u>$line</u> </h2> \r\n";

            $cached = $db->get_row("select * from tfl_timetable WHERE station='$station' AND line='$line' limit 1 ");
            if (!empty($cached)) {
                $ago = round(abs(time() - strtotime($cached->updated)) / 86400); $ago = $ago - 1;
                if ($ago < 3) {
                    //echo "<small>cached version on $cached->updated , $ago days ago</small><br>\r\n";
                    $output = $cached->data;
                } else {
                    //update cache
                    $url = 'https://api.tfl.gov.uk/Line/' . $line . '/Timetable/' . $station . '/' . '?app_id=' . APP_ID . '&app_key=' . APP_KEY;
                    $output = file_get_contents($url);

                    $outputDB = $db->escape($output);
                    $db->query("UPDATE tfl_timetable SET data='$outputDB',updated='$now' WHERE id='$cached->id' limit 1  ");
                    //echo "<small>cached version on $cached->updated , UPDATED now</small><br>\r\n";
                }
            } else {
                $url = 'https://api.tfl.gov.uk/Line/' . $line . '/Timetable/' . $station . '/' . '?app_id=' . APP_ID . '&app_key=' . APP_KEY;
                $output = file_get_contents($url);

                if (!empty($output)){
                    $outputDB = $db->escape($output);
                    $db->query("INSERT INTO tfl_timetable(station,line,updated,data) VALUES('$station','$line','$now','$outputDB') ");
                }
            }

            $data = json_decode($output);


            if (!$data) { echo "not found timetable.."; }

            $stations  = $data->stations;
            $stops     = $data->stops;
            $timetable =  $data->timetable;

            $routes = $timetable->routes[0];

            $stationIntervals = $routes->stationIntervals[0]->intervals;
            $schedules = $routes->schedules;

            //echo "<pre>"; print_r($schedules);  echo "</pre>";   die();


    if (!empty($stationIntervals)) {
echo "<div class=\"row\">\r\n";
                echo "<div class=\"col\">\r\n ";
                echo "<div class=\"card\">\r\n ";
                    echo "<div class=\"card-header\">Interval for station: <em>#$exist_station->name</em> on line: <u>$line</u> </h2></div>\r\n ";
                    echo "<div class=\"card-body\">\r\n ";
                    echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableStationsInterval\">\r\n";
                            echo "<thead><tr><th>Station</th><th>time To Arrival</th></tr></thead>\r\n";
                            echo "<tbody>\r\n";
                            foreach ($stationIntervals as $row) {
                                $stop = $db->get_row("select name,lat,lon from tfl_stop_points WHERE code='$row->stopId' limit 1 ");
                                $map = "";
                                if (!empty($stop->lat)) {
                                    $marUrl = 'https://maps.google.com/maps?' .'f=d&' .'ie=UTF8&' .'msa=0&' ."ll=$stop->lat,$stop->lon&" ."zoom=12&" ."daddr=$stop->lat,$stop->lon";
                                    $map =  "<a href=\"" . $marUrl . "\" rel=\"nofollow\"  class=\"gmaps_url_inline\" title=\"view map\" target=\"_blank\"><i class=\"fas fa-map-marker-alt\"></i></a>";
                                }

                                echo "<tr><td title=\"$row->stopId\">$stop->name $map</td><td data-sort=\"$row->timeToArrival\">$row->timeToArrival min</td></tr>\r\n";
                            }
                            echo "</tbody>\r\n";
                    echo "</table>\r\n";
                    echo "</div>\r\n";
                echo "</div>\r\n";
                echo "</div><!-- /col -->\r\n";
echo "</div><!-- /row-->\r\n";
}//!empty($stationIntervals)




if (!empty($schedules)) {
echo "<div class=\"row\">\r\n";
                echo "<div class=\"col\">\r\n ";
                echo "<div class=\"card\">\r\n ";
                echo "<div class=\"card-header\">Schedules for station: <em>#$exist_station->name</em> on line: <u>$line</u></div>\r\n ";
                echo "<div class=\"card-body\">\r\n ";

$i=1; $j=1;
echo "<ul class=\"nav nav-tabs\" id=\"myTab\" role=\"tablist\">\r\n";
foreach ($schedules as $row) {
    if ($i == 1) echo "<li class=\"nav-item\"><a class=\"nav-link active\" role=\"tab\" data-toggle=\"tab\" href=\"#s$i\">$row->name</a></li>\r\n"; else  echo "<li class=\"nav-item\"><a  class=\"nav-link\" role=\"tab\" data-toggle=\"tab\" href=\"#s$i\">$row->name</a></li>\r\n";
    $i++;
}
echo "</ul>\r\n";

echo "<div class=\"tab-content\" id=\"myTabContent\">\r\n";
                foreach ($schedules as $row) {
                    if ($j == 1) echo "<div id=\"s$j\" class=\"tab-pane fade show active\">\r\n"; else echo "<div id=\"s$j\" class=\"tab-pane fade\">\r\n";
                        $hh = $row->firstJourney->hour;$mm = $row->firstJourney->minute; $hh2 = $row->lastJourney->hour; $mm2 = $row->lastJourney->minute;
                        echo "<p><b>firstJourney:</b> $hh:$mm / <b>lastJourney:</b> $hh2:$mm2 </p>\r\n";

                        if (!empty($row->periods)) {
                            echo "<div class=\"card\">\r\n ";
                            echo "<div class=\"card-header\"><h1>Periods</h1></div>\r\n ";
                            echo "<div class=\"card-body\">\r\n ";
                            echo "<table class=\"table table-sm table-striped table-hover myDataTable\" id=\"\">\r\n";
                                    echo "<thead><tr><th>Type</th><th>fromTime</th><th>toTime</th></tr></thead>\r\n";
                                    echo "<tbody>\r\n";
                                    foreach ($row->periods as $p) {
                                            $h = $p->fromTime->hour; $m = $p->fromTime->minute; $h2 = $p->toTime->hour; $m2 = $p->toTime->minute;
                                            if (!empty($p->frequency)) {
                                                    $p1 = $p->frequency->highestFrequency; $p2 = $p->frequency->lowestFrequency;
                                                    echo "<tr><td>$p->type<br>$p1-$p2 min</td><td>$h:$m</td><td>$h2:$m2</td></tr>\r\n";
                                            } else {
                                                    echo "<tr><td>$p->type</td><td>$h:$m</td><td>$h2:$m2</td></tr>\r\n";
                                            }
                                    }
                                    echo "</tbody>\r\n";
                            echo "</table>\r\n";
                            echo "</div></div>\r\n";
                        }// periods

                        if (!empty($row->knownJourneys)) {
                            echo "<div class=\"card\">\r\n ";
                            echo "<div class=\"card-header\"><h1>known Journeys</h1></div>\r\n ";
                            echo "<div class=\"card-body knownJourneys\">\r\n ";
                                    foreach ($row->knownJourneys as $kj) {
                                            echo "<span>$kj->hour:$kj->minute</span>, ";
                                    }
                            echo "</div></div>\r\n";
                        }// knownJourneys
                echo "</div>\r\n";
                $j++;
                } //foreach schedules
echo "</div><!-- tab content -->\r\n" ;

                echo "</div></div>\r\n";
                echo "</div><!-- /col --> \r\n";
echo "</div><!-- /row -->";
}//!empty($schedules)

        } //if (!empty($exist_station) AND !empty($exist_line))

    } // timetable ,if (!empty($_GET['station']) AND !empty($_GET['line']))


?>

<?php include("include/_footer.php"); ?>