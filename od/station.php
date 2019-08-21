<?php
    include_once("dbconfig.php");
    if (!empty($_GET['id'])) {
        $stationCode = $_GET['id'];
        $station = $db->get_row("SELECT * FROM tfl_stations WHERE code = '$stationCode' limit 1 ");
    }

    $selected = 3;
    $title = "" . $station->name . " - " . stopTypePrint($station->stopType) . " - London" ;
    $breadcrumb = $station->name;
    $description = $station->name . " " . $stationCode . " - " . stopTypePrint($station->stopType) . " - London - Christian Transfers" ;

    include("include/_header.php");

$lead = $station->name . " / " . $stationCode .  " / " . stopTypePrint($station->stopType);

?>

<h2><?=$station->name ?></h2>
<p class="lead"><?=$lead?></p>

<?php

$lines = $db->get_results("select * from tfl_station_lines WHERE station_code = '$stationCode' order by line_tid asc ");
echo "<div class=\"popup_stations\">\r\n ";
    if (!empty($lines)) {
        echo "<h5>Lines for this station/stop-point</h5>\r\n";
        foreach ($lines as $row) {
                echo "<span>$row->line_tid</span> \r\n";
        }
    } else {
        echo "<span class=\"badge badge-danger\">Not found lines for this station/stop-point </span>\r\n";
    }
echo "</div>\r\n";


/* **************** station timetable ************************************************ */
//if (!empty($lines) and 1==1 AND $station->code == '490003037E')
if (!empty($lines)) {

    $cached = $db->get_row("select * from tfl_timetable_station WHERE station='$station->code' limit 1 ");
    if (!empty($cached)) {
        $min_ago = round(abs(time() - strtotime($cached->updated)) / 60);
        if ($min_ago > 15) {
            echo "<small>cached version on $cached->updated , $min_ago minutes ago, updated now!</small><br>\r\n";
            $db->query("delete from tfl_timetable_station WHERE station='$station->code' limit 1");
        } else {
            //echo "<small>GET cached version on $cached->updated , $min_ago minutes ago</small><br>\r\n";
            $stationTimetableData = $cached->data;
        }
    }
    $url = 'https://api.tfl.gov.uk/Line/' . $row->line_tid . '/Timetable/' . $station->code . '/' . '?app_id=' . APP_ID . '&app_key=' . APP_KEY;

    if (empty($stationTimetableData)) {
        $now = date("Y-m-d H:i:s"); $data = array();
        foreach ($lines as $row) {
            $lineInfo = $db->get_row("select originationName,destinationName,line_modeName,originator,destination from tfl_line_routes where line_tid = '$row->line_tid' limit 1");
            //echo "<span>find timetable for line: $row->line_tid</span> \r\n";
            $output = file_get_contents($url);
            if (!empty($output)){
                $result = json_decode($output) ;
                $routes = $result->timetable->routes[0];
                $stationIntervals = $routes->stationIntervals[0]->intervals;
                //echo "<pre>"; print_R($stationIntervals); echo "</pre>";
                //die();
                foreach ($stationIntervals as $si) {
                    $stationName = $db->get_var("select name from tfl_stop_points where code='$si->stopId' limit 1");
                    $data[$row->line_tid][] = array('stopId' => $si->stopId, 'timeToArrival' => $si->timeToArrival, 'stationName' => $stationName, 'lineFrom' => $lineInfo->originationName,'lineTo' => $lineInfo->destinationName, 'line_modeName' => $lineInfo->line_modeName, 'originator' => $lineInfo->originator, 'destination' => $lineInfo->destination);
                }
            }

        } // foreach($lines as $row)
        //echo "<pre>"; print_R($data); echo "</pre>";
        $dataEncoded = json_encode($data);$stationTimetableData = $dataEncoded; $dataEncoded = $db->escape($dataEncoded);
        $db->query("INSERT INTO tfl_timetable_station(station,updated,data) VALUES('$station->code','$now','$dataEncoded') ");

    } // if (empty($stationTimetableData))

    if (!empty($stationTimetableData)) {
        echo "<div class=\"card\">\r\n ";
            echo "<div class=\"card-header\"><h1>Timetable for ALL lines on station: $station->name </h1></div>\r\n ";
            echo "<div class=\"card-body\">\r\n ";
                        //echo $url;

                $decoded = json_decode($stationTimetableData);
                if (!empty($decoded)) {
                    //echo "<pre>"; print_R($decoded); echo "</pre>";
                        $formated = array();
                        foreach ($decoded as $key => $value) {
                            $rows = $decoded->$key ;
                            foreach ($rows as $item) {
                                $formated[$item->timeToArrival][] = array('line' => $key, 'station' => $item->stationName, 'code' => $item->stopId, 'lineFrom' => $item->lineFrom,'lineTo' => $item->lineTo, 'mode' => $item->line_modeName , 'originator' => $item->originator, 'destination' => $item->destination);
                            }
                        }
                        //echo "<pre>"; print_R($formated); echo "</pre>";
                        sort($formated);

                        echo "<table class=\"table table-sm table-striped table-hovered table-responsive\" >\r\n";
                        echo "<thead><tr><th>time to<br>Arrival</th><th>type</th><th>Line / Start - End</th><th>Stop point</th><th></th></tr></thead>\r\n";
                        $k=1;
                        foreach ($formated as $time => $value) {
                            $rows = $formated[$time];
                            //echo "$time min<br>\r\n";

                            foreach ($rows as $item) {
                                if ($k < 15) {
                                    $slug1 = slug($item[lineFrom]); $slug2 = slug($item[lineTo]); $slugStopPoint = slug($item[station]);
                                    echo "<tr><td><b>$time" . " min</b></td><td>$item[mode]</td><td><b>#$item[line]</b> <small><a href=\"/od/station/$item[originator]/$slug1\">$item[lineFrom]</a> - <a href=\"/od/station/$item[destination]/$slug2\">$item[lineTo]</a></small></td><td><a href=\"/od/stop-point/$item[code]/$slugStopPoint\" style=\"color:#000;\">$item[station]</a> </td><td><a class=\"btn btn-danger btn-sm\" href=\"timetable.php?station=$stationCode&line=$row->line_tid\" ><i class=\"far fa-clock\"></i> Details</a></td></tr>\r\n";
                                }
                                $k++;
                            }

                        }
                        echo "</table>\r\n";

                }
            echo "</div>\r\n";
        echo "</div>\r\n";
    } // $stationTimetableData


} // if (!empty($lines))
/* **************** station timetable ************************************************ */




    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h1>Routes for: $station->name </h1></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

        $lines = $db->get_results("SELECT * FROM tfl_line_routes WHERE originator = '$stationCode' OR destination='$stationCode' ");
        if (!empty($lines)) {
            echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableLines\">\r\n";
                echo "<thead><tr><th>Type</th><th>Line</th><th>Name</th><th>Direction</th><th>Valid</th><th></th></tr></thead>\r\n";
                foreach ($lines as $row) {
                    $validFrom = date("d.m.Y",strtotime($row->validFrom));$validTo = date("d.m.Y",strtotime($row->validTo));
                    $night = ""; if ($row->serviceType == 'Night') $night = " <b>[night]</b>";
                    echo "<tr><td>$row->line_modeName</td><td title=\"$row->line_tid\">$row->line_name $night</td><td title=\"$row->origination - $row->destination\">$row->name</td><td>$row->direction</td><td>$validFrom<br>$validTo</td><td><a class=\"btn btn-danger btn-sm\" href=\"timetable.php?station=$stationCode&line=$row->line_tid\" ><i class=\"far fa-clock\"></i> Timetable</a></td></tr>\r\n";
                }
            echo "</table>\r\n";
        }
       echo "</div>\r\n";
    echo "</div>\r\n";



echo "<div class=\"row\">\r\n ";
    echo "<div class=\"col\">\r\n ";

        echo "<div class=\"card\">\r\n ";
            echo "<div class=\"card-header\">Map informations</div>\r\n ";
            echo "<div class=\"card-body\">\r\n ";
                echo "<h6>Location: London - $station->name</h6>\r\n";
                echo "<h6>Lat: $station->lat <br>Lon: $station->lon</h6>\r\n";
                $stopType = stopTypePrint($station->stopType);
                echo "<h6>Type: $stopType</h6>\r\n";

                echo Location2map($station->lat,$station->lon);
                echo Location2href($station->lat,$station->lon);

            echo "</div>\r\n";
        echo "</div>\r\n";

echo "</div><!--/col -->\r\n";


//*************** taxi api ***********************************************************************************************
if (!empty($station->lat) AND !empty($station->lon)) {
    echo "<div class=\"col\">\r\n ";

            $cached = $db->get_row("select * from tfl_cabwise WHERE station='$stationCode' limit 1 ");
            if (!empty($cached)) {
                $ago = round(abs(time() - strtotime($cached->updated)) / 86400); $ago = $ago - 1;
                if ($ago < 10) {
                    $output = $cached->data;
                } else {
                    //update cache
                    $url = 'https://api.tfl.gov.uk/Cabwise/search?lat=' . $station->lat . '&lon=' . $station->lon . '&app_id=' . APP_ID . '&app_key=' . APP_KEY;
                    $output = file_get_contents($url);

                    $outputDB = $db->escape(utf8ize($output));
                    $db->query("UPDATE tfl_cabwise SET data='$outputDB',updated='$now' WHERE id='$cached->id' limit 1  ");
                }
            } else {
                $url = 'https://api.tfl.gov.uk/Cabwise/search?lat=' . $station->lat . '&lon=' . $station->lon . '&app_id=' . APP_ID . '&app_key=' . APP_KEY;
                $output = file_get_contents($url);

                if (!empty($output)){
                    $outputDB = $db->escape(utf8ize($output));
                    $db->query("INSERT INTO tfl_cabwise(station,lat,lon,updated,data) VALUES('$stationCode','$station->lat','$station->lon','$now','$outputDB') ");
                    $lastID = $db->insert_id;
                    if (!$lastID) die( "** ERROR with db. " . $db->last_error);
                }
            }

            $data = json_decode(utf8ize($output));
            if (!$data) { echo "not found data!"; }
            //echo "<pre>"; print_r($data);  echo "</pre>"; die();

            $OperatorList  = $data->Operators->OperatorList;
                echo "<div class=\"card\">\r\n ";
                    echo "<div class=\"card-header\">$station->name nearest minicab</div>\r\n ";
                    echo "<div class=\"card-body\">\r\n ";
                    echo "<a target=\"_blank\" class=\"btn btn-primary btn-sm mt-1 ml-1\" href=\"cabwise.php?station=$stationCode\">Find minicab offices</a><br><br>" ;

                    if (!empty($OperatorList)) {
                        echo "<table class=\"table table-sm table-striped table-hover\" id=\"\">\r\n";
                            echo "<thead><tr><th>Address/Phone</th><th>Map</th></tr></thead>\r\n";
                            echo "<tbody>\r\n"; $max=5; $i=0;
                            foreach ($OperatorList as $row) {
                                if ($i<$max) {
                                    echo "<tr><td><b>$row->TradingName</b><br>";
                                    echo "$row->AddressLine1 $row->AddressLine2<br>$row->Postcode <a href=\"tel:$row->BookingsPhoneNumber\" class=\"taxi_phone\"><i class=\"fas fa-phone-square\"></i> $row->BookingsPhoneNumber</a></td>";
                                    echo "<td>" . $row->Distance ."km<br>";
                                    if (!empty($row->Longitude)) {
                                        $map_url = "https://maps.google.com/maps?f=d&ie=UTF8&msa=0&ll=$row->Latitude,$row->Longitude&zoom=12&daddr=$row->Latitude,$row->Longitude";
                                        echo "<a class=\"btn btn-info btn-sm\" href=\"$map_url\" target=\"_blank\" title=\"$row->Longitude , $row->Latitude\"><i class=\"fas fa-map-marked-alt\"></i></a>";
                                    }
                                    echo "</td>\r\n";
                                echo "</tr>\r\n";
                                }
                            $i++;
                            }
                            echo "</tbody>\r\n";
                    echo "</table>\r\n";

                    }//!empty($OperatorList)
                    echo "</div>\r\n";
                echo "</div>\r\n";

echo "</div><!--/col -->\r\n";

}
//*************** /taxi api ***********************************************************************************************

echo "</div><!-- /row -->";

    $q = "SELECT * FROM tfl_stations order by name asc limit 5000";
    $rows = $db->get_results($q);
    if (!empty($rows)) {
    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h1>All Stations</h1></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

        echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableStations\">\r\n";
            echo "<thead><tr><th>Name</th><th>Type</th><th>Lines</th><th>Action</th></tr></thead>\r\n";
            echo "<tbody>\r\n";
            foreach ($rows as $row) {
                $stopType = stopTypePrint($row->stopType);
                $slug = slug($row->name);
                    $lines3 = "";
                    $rows4 = $db->get_results("select * from tfl_station_lines WHERE station_code = '$row->code' order by line_tid asc ");

                    if (!empty($rows4)) {
                        foreach ($rows4 as $row4) {
                            $lines3 .= "$row4->line_tid, ";
                        }
                        $lines3 = trim($lines3,", ");
                    }

                echo "<tr><td><b>$row->name</b></td><td>$stopType</td>";
                echo "<td><p class=\"maxlines\">$lines3</p></td>";
                echo "<td>";
                echo "<a href=\"javascript:PopupStationLines('$row->code')\" class=\"btn btn-black btn-sm mt-1 ml-1\" title=\"Lines / Map\"><i class=\"fas fa-bus-alt\"></i></a>";
                echo "<a class=\"btn btn-black btn-sm mt-1 ml-1\" href=\"/od/cabwise/$row->code\" title=\"Taxi / Cab wise\" ><i class=\"fas fa-taxi\"></i></a>";
                echo "<a class=\"btn btn-sm btn-primary mt-1 ml-1\" href=\"/od/station/$row->code/$slug\" title=\"More details\">Details</a>";
                echo "</td>";
                echo "</tr>\r\n";
            }
            echo "</tbody>\r\n";
        echo "</table>\r\n";


        echo "</div>\r\n";
    echo "</div>\r\n";
    }//!empty($rows)




?>

<?php include("include/_footer.php"); ?>