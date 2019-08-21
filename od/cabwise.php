<?php
    $title = "Gets taxis and minicabs contact information " ;$selected = 7; //čćžšđ
    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<h2>London taxis and minicabs</h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<form action="" method="get">
    <div class="form-row">
        <div class="col">
            <input type="text" class="form-control" placeholder="Station" name="station"  required="required" value="<?=$_GET['station']?>">
            <small>490014493E, 490005646F, 490004046W / "West Middlesex Hospital", "County Hall", "Fyfield Road"</small>
        </div>

        <div class="col">
           <button type="submit" class="btn btn-success" name="sbm" value="1"><i class="fas fa-taxi"></i> Get cabwise</button>
        </div>
        </div>
</form>

<?php

    if (!empty($_GET['station'])) {
        echo "<hr/>\r\n";
        $station = trim($_GET['station']);
//Schedule	"Twice a day at 02:00 and 14:00"

        $exist_station = $db->get_row("select * from tfl_stations WHERE code = '$station' OR name like '%$station%' limit 1 ");
        if (empty($exist_station)) {
            echo "<div class=\"alert alert-warning\">Not found station: <b>$station</b></div>\r\n ";
        }

        if (!empty($exist_station))  {
            $now = date("Y-m-d");
            echo "<h2>Taxis and minicabs for station: <em>#$exist_station->name</em> </h2> \r\n";

            if (empty($exist_station->lat) OR empty($exist_station->lon)) die("not exist location for station");

            $cached = $db->get_row("select * from tfl_cabwise WHERE station='$station' limit 1 ");
            if (!empty($cached)) {
                $ago = round(abs(time() - strtotime($cached->updated)) / 86400); $ago = $ago - 1;
                if ($ago < 10) {
                    echo "<small>cached version on $cached->updated , $ago days ago</small><br>\r\n";
                    $output = $cached->data;
                } else {
                    //update cache
                    $url = 'https://api.tfl.gov.uk/Cabwise/search?lat=' . $exist_station->lat . '&lon=' . $exist_station->lon . '&app_id=' . APP_ID . '&app_key=' . APP_KEY;
                    $output = file_get_contents($url);

                    $outputDB = $db->escape(utf8ize($output));
                    $db->query("UPDATE tfl_cabwise SET data='$outputDB',updated='$now' WHERE id='$cached->id' limit 1  ");
                    echo "<small>cached version on $cached->updated , UPDATED now</small><br>\r\n";
                }
            } else {
                $url = 'https://api.tfl.gov.uk/Cabwise/search?lat=' . $exist_station->lat . '&lon=' . $exist_station->lon . '&app_id=' . APP_ID . '&app_key=' . APP_KEY;
                //echo $url; die();
                $output = file_get_contents($url);

                if (!empty($output)){
                    $outputDB = $db->escape(utf8ize($output));
                    $db->query("INSERT INTO tfl_cabwise(station,lat,lon,updated,data) VALUES('$station','$exist_station->lat','$exist_station->lon','$now','$outputDB') ");
                    $lastID = $db->insert_id;
                    if (!$lastID) die( "** ERROR with db. " . $db->last_error);
                }
            }


            $data = json_decode(utf8ize($output));
            if (!$data) { echo "not found data!"; }
            //echo "<pre>"; print_r($data);  echo "</pre>"; die();

            $OperatorList  = $data->Operators->OperatorList;

echo "<div class=\"row\">\r\n";

            if (!empty($OperatorList)) {
                echo "<div class=\"col\">\r\n ";
                echo "<div class=\"card\">\r\n ";
                    echo "<div class=\"card-header\"><h1>Taxis and minicabs information</h1></div>\r\n ";
                    echo "<div class=\"card-body\">\r\n ";
                    echo "<table class=\"table table-sm table-striped table-hover\" id=\"\">\r\n";
                            echo "<thead><tr><th>Organisation</th><th>Address/Phone</th><th>Dist.</th><th>Map</th></tr></thead>\r\n";
                            echo "<tbody>\r\n";
                            foreach ($OperatorList as $row) {
                                $operatorTypes = "";if (!empty($row->OperatorTypes))$operatorTypes = implode(", ", array($row->OperatorTypes)) ;
                                $timeMonThu = ""; $timeFri = ""; $timeSat = ""; $timeSun = ""; $timePubHol = "";
                                if (!empty($row->StartTimeMonThu)) $timeMonThu = "<span><b>MonThu:</b> $row->StartTimeMonThu - $row->EndTimeMonThu</span>";
                                if (!empty($row->StartTimeFri)) $timeFri = "<span><b>Friday:</b> $row->StartTimeFri - $row->EndTimeFri</span>";
                                if (!empty($row->StartTimeSat)) $timeSat = "<span><b>Saturday:</b> $row->StartTimeSat - $row->EndTimeSat</span>";
                                if (!empty($row->StartTimeSun)) $timeSun = "<span><b>Sunday:</b> $row->StartTimeSun - $row->EndTimeSun</span>";
                                //if (!empty($row->StartTimePubHol)) $timePubHol = "<span><b>Holidays:</b> $row->StartTimePubHol - $row->EndTimePubHol</span>";

                                    echo "<tr><td><b>$row->TradingName</b><br><em>$row->OrganisationName</em>";
                                        if (!empty($timeMonThu)) {
                                            echo "<p class=\"cabwise_time\">\r\n";
                                                if (!empty($timeMonThu)) echo $timeMonThu; if (!empty($timeFri)) echo $timeFri;   if (!empty($timeSat)) echo $timeSat;   if (!empty($timeSun)) echo $timeSun;   if (!empty($timePubHol)) echo $timePubHol;
                                            echo "</p>";
                                        }
                                    echo "</td>\r\n";
                                    echo "<td>$row->AddressLine1 $row->AddressLine2<br>$row->Postcode <a href=\"tel:$row->BookingsPhoneNumber\" class=\"taxi_phone\"><i class=\"fas fa-phone-square\"></i> $row->BookingsPhoneNumber</a></td>
                                    <td>$row->Distance km</td>";

                                if (!empty($row->Longitude)) {
                                    $map_url = "https://maps.google.com/maps?f=d&ie=UTF8&msa=0&ll=$row->Latitude,$row->Longitude&zoom=12&daddr=$row->Latitude,$row->Longitude";
                                    echo "<td><a class=\"btn btn-info btn-sm\" href=\"$map_url\" target=\"_blank\" title=\"$row->Longitude , $row->Latitude\"><i class=\"fas fa-map-marked-alt\"></i></a></td>";

                                } else {
                                    echo "<td>&nbsp;</td>";
                                }
                                echo "</tr>\r\n";


                            }
                            echo "</tbody>\r\n";
                    echo "</table>\r\n";
                    echo "</div>\r\n";
                echo "</div>\r\n";
                echo "</div><!-- /col -->\r\n";
            }//!empty($OperatorList)

echo "</div><!-- /row -->";

        } //if (!empty($exist_station)

    } // cabwise ,if (!empty($_GET['station']))


?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<?php include("include/_footer.php"); ?>