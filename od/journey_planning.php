<?php
    $selected = 12;

    $title = "Journey planner London - Christian Transfers" ;
    $breadcrumb = "Journey planner tfl";
    $description = "Journey planner for Bus, Tube, London Overground, DLR, TfL Rail, National Rail, Tram, River Bus, Emirates Air Line or by coach, plan a journey across the TfL network";

    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<h2>London Journey Planner</h2>
<p class="lead">Journey planner for Bus, Tube, London Overground, DLR, TfL Rail, National Rail, Tram, River Bus, Emirates Air Line or by coach, plan a journey across the TfL network.</p>
<p>Complete your stations code in the spaces below.</p>




<form action="" method="get">
    <div class="form-row">
        <div class="col">
            <input type="text" class="form-control" placeholder="From" name="from"  required="required" value="<?=$_GET['from']?>">
            <small>Coordinates(Lat/Lon), Station Name</small><br>

        </div>
        <div class="col">
            <input type="text" class="form-control" name="to" placeholder="To" required="required" value="<?=$_GET['to'] ?>">
            <small>Coordinates(Lat/Lon), Station Name</small><br>
        </div>
        <div class="col">
           <button type="submit" class="btn btn-success" name="sbm" value="1"><i class="fas fa-route"></i> Get route</button>
        </div>
        </div>
</form>

<?php

    if (!empty($_GET['from']) AND !empty($_GET['to'])) {
        echo "<hr/>\r\n";
        $from = trim($_GET['from']);
        $to   = trim($_GET['to']);

        $from = stripslashes($from); $to = stripslashes($to);

            $now = date("Y-m-d");
            echo "<h3>Journey Planning from <em>$from</em> to: <u>$to</u> </h3> \r\n";

            $existStopPointFrom = $db->get_row("select * from tfl_stop_points WHERE name like '%$from%' limit 1 ");
            $existStopPointTo = $db->get_row("select * from tfl_stop_points WHERE name like '%$to%' limit 1");
            if (!empty($existStopPointFrom)) {
                $from = $existStopPointFrom->lat . ',' . $existStopPointFrom->lon;
                echo "<h4 class=\"foundh4\">Found FROM station: <b>$existStopPointFrom->name</b></h4>";
            }
            if (!empty($existStopPointTo)) {
                $to = $existStopPointTo->lat . ',' . $existStopPointTo->lon;
                echo "<h4 class=\"foundh4\">Found TO station: <b>$existStopPointTo->name</b></h4>";
            }


            $cached = $db->get_row("select * from tfl_journey WHERE `from`='$from' AND `to`='$to' limit 1 ");
            if (!empty($cached)) {
                $ago = round(abs(time() - strtotime($cached->updated)) / 86400); $ago = $ago - 1;
                if ($ago < 2) {
                    //echo "<small>cached version on $cached->updated , $ago days ago</small><br>\r\n";
                    $output = $cached->data;
                } else {
                    //update cache
                    $url = 'https://api.tfl.gov.uk/Journey/JourneyResults/' . $from . '/to/' . $to . '/' . '?app_id=' . APP_ID . '&app_key=' . APP_KEY;
                    $output = file_get_contents($url);

                    $outputDB = $db->escape($output);
                    $db->query("UPDATE tfl_journey SET data='$outputDB',updated='$now' WHERE id='$cached->id' limit 1  ");
                    echo "<small>cached version on $cached->updated , UPDATED now</small><br>\r\n";
                }
            } else {
                $url = 'https://api.tfl.gov.uk/Journey/JourneyResults/' . $from . '/to/' . $to . '/' . '?app_id=' . APP_ID . '&app_key=' . APP_KEY;
                //echo $url; die();
                $output = file_get_contents($url);

                if (!empty($output)){
                    $outputDB = $db->escape($output);
                    $db->query("INSERT INTO tfl_journey(`from`,`to`,updated,data) VALUES ('$from','$to','$now','$outputDB') ");
                    $lastID = $db->insert_id;
                    if (!$lastID) {echo "DB error: " . $db->last_error;}

                }
            }

            $data = json_decode($output);


            if (!$data) { echo "not found route!"; }
            //echo "<pre>"; print_r($data);  echo "</pre>";   die();

            $timeAdjustments  = $data->searchCriteria->timeAdjustments;
                $earliest     = $timeAdjustments->earliest; // date , time, timeIS
                $earlier      = $timeAdjustments->earlier;
                $later        = $timeAdjustments->later;
                $latest       = $timeAdjustments->latest;

           $stopMessages     = $data->stopMessages;
           $lines            = $data->lines;
           $journeys         = $data->journeys;

echo "<div class=\"row\">\r\n";
        echo "<div class=\"col timeIS\">\r\n ";
            if (!empty($earliest)) {echo "<b>Earliest:</b> " . dateFix($earliest->date) . " " . timeFix($earliest->time) . " / " . $earliest->timeIs . "<br>\r\n";}
            if (!empty($earlier)) {echo "<b>Earlier:</b> " . dateFix($earlier->date) . " " . timeFix($earlier->time) . " / " . $earlier->timeIs . "<br>\r\n";}
            if (!empty($later)) {echo "<b>Later:</b> " . dateFix($later->date) . " " . timeFix($later->time) . " / " . $later->timeIs . "<br>\r\n";}
            if (!empty($latest)) {echo "<b>Latest:</b> " . dateFix($latest->date) . " " . timeFix($latest->time) . " / " . $latest->timeIs . "<br>\r\n";}
        echo "</div><!-- /col -->\r\n";
echo "</div><!-- /row -->";

if (!empty($stopMessages)) {
echo "<div class=\"row\">\r\n";
        echo "<div class=\"col stopMessages\" >\r\n ";
        foreach ($stopMessages as $msg)
            echo "<span><i class=\"fas fa-exclamation-triangle\"></i> $msg</span>\r\n";
        echo "</div><!-- /col -->\r\n";
echo "</div><!-- /row -->";
}

if (!empty($lines)) {
echo "<div class=\"row\">\r\n";
                echo "<div class=\"col\">\r\n ";
                echo "<div class=\"card\">\r\n ";
                    echo "<div class=\"card-header\"><h1>Lines</h1></div>\r\n ";
                    echo "<div class=\"card-body\">\r\n ";
                    echo "<table class=\"table table-sm table-striped table-hover table-bordered\" id=\"\">\r\n";
                            echo "<thead><tr><th>Station</th><th>Mode</th><th>Status</th></tr></thead>\r\n";
                            echo "<tbody>\r\n";
                            foreach ($lines as $row) {
                                $lineStatus = "";
                                if (!empty($row->lineStatuses[0])) {
                                    $lineStatus = $row->lineStatuses[0]->statusSeverityDescription;
                                    if (!empty($row->lineStatuses[0]->reason)) $lineStatus .= " / " . $row->lineStatuses[0]->reason;
                                    if (!empty($row->lineStatuses[0]->disruption->additionalInfo)) $lineStatus .= "<br><i class=\"fas fa-exclamation-triangle\"></i>" . $row->lineStatuses[0]->disruption->additionalInfo;
                                }
                                echo "<tr><td>$row->name</td><td>$row->modeName</td><td>$lineStatus</td></tr>\r\n";
                            }
                            echo "</tbody>\r\n";
                    echo "</table>\r\n";
                    echo "</div>\r\n";
                echo "</div>\r\n";
                echo "</div><!-- /col -->\r\n";
echo "</div><!-- /row -->\r\n";
}//!empty($lines)


if (!empty($journeys)) {
echo "<div class=\"row\">\r\n";
                echo "<div class=\"col\">\r\n ";
                echo "<div class=\"card\">\r\n ";
                    echo "<div class=\"card-header\"><h1>Journeys</h1></div>\r\n ";
                    echo "<div class=\"card-body\">\r\n ";
                            foreach ($journeys as $row) {
                                    echo "<div class=\"journey\">\r\n";
                                    $start = date("d.m.Y H:i",strtotime($row->startDateTime)); $end = date("d.m.Y H:i",strtotime($row->arrivalDateTime));
                                    echo "<h5><b>$row->duration min</b> / $start - $end</h5>";
                                    if (!empty($row->fare)) {
                                        $fare = $row->fare;
                                        echo "<div class=\"fare\">\r\n ";
                                            echo "<b>Fares Total cost: $fare->totalCost EUR</b>\r\n";
                                            if (!empty($fare->fares[0]->chargeProfileName)) echo $fare->fares[0]->chargeProfileName;
                                            if (!empty($fare->fares[0]->taps[0]->atcoCode)) echo " / " . $fare->fares[0]->taps[0]->atcoCode;
                                            if (!empty($fare->fares[0]->taps[1]->atcoCode)) echo " / " . $fare->fares[0]->taps[1]->atcoCode;
                                            if (!empty($fare->caveats[0]->text)) echo "<br><i class=\"fas fa-info-circle\"></i> " . strip_tags($fare->caveats[0]->text);
                                            if (!empty($fare->caveats[1]->text)) echo "<br><i class=\"fas fa-info-circle\"></i> " . strip_tags($fare->caveats[1]->text);
                                        echo "</div>\r\n";
                                    }
                                    if (!empty($row->legs)) {
                                        echo "<div class=\"legs\">\r\n";
                                        echo "<h3>Legs</h3>\r\n";
                                        foreach ($row->legs as $leg) {
                                            echo "<div class=\"leg\">";
                                                $departure = date("d.m.Y H:i",strtotime($leg->departureTime)); $arrival = date("d.m.Y H:i",strtotime($leg->arrivalTime));

                                                echo "<h3><b>$leg->duration min</b> / Departure: $departure / Arrival: $arrival</h3>\r\n";
                                                echo "<p>Mode: <b>" . $leg->mode->name . "</b></p>";
                                                echo "<p>" . $leg->instruction->detailed . "</p>";
                                                $fromMap = "";
                                                if (!empty($leg->departurePoint->lat)) {$map_url = "https://maps.google.com/maps?f=d&ie=UTF8&msa=0&ll={$leg->departurePoint->lat},{$leg->departurePoint->lon}&zoom=12&daddr={$leg->departurePoint->lat},{$leg->departurePoint->lon}"; $fromMap = "<a class=\"btn btn-info btn-sm\" href=\"$map_url\" target=\"_blank\" title=\"{$leg->departurePoint->lat},{$leg->departurePoint->lon}\"><i class=\"fas fa-map-marker-alt\"></i> {$leg->departurePoint->lat},{$leg->departurePoint->lon}</a>"; }
                                                $toMap = "";
                                                if (!empty($leg->arrivalPoint->lat)) {$map_url = "https://maps.google.com/maps?f=d&ie=UTF8&msa=0&ll={$leg->arrivalPoint->lat},{$leg->arrivalPoint->lon}&zoom=12&daddr={$leg->arrivalPoint->lat},{$leg->arrivalPoint->lon}"; $toMap = "<a class=\"btn btn-info btn-sm\" href=\"$map_url\" target=\"_blank\" title=\"{$leg->arrivalPoint->lat},{$leg->arrivalPoint->lon}\"><i class=\"fas fa-map-marker-alt\"></i> {$leg->arrivalPoint->lat},{$leg->arrivalPoint->lon}</a>"; }

                                                echo "<p><b>From:</b> " . $leg->departurePoint->commonName . " <em>" . $leg->departurePoint->naptanId . "</em> $fromMap</p>";
                                                echo "<p><b>To:</b> " . $leg->arrivalPoint->commonName . " <em>" . $leg->arrivalPoint->naptanId . "</em> $fromMap</p>";
                                                if (!empty($leg->disruptions)) {
                                                    $disruptions1 = $leg->disruptions[0]->description;
                                                    $disruptions2 = "";
                                                    if (!empty($leg->disruptions[0]->additionalInfo)) $disruptions2 = "<br>" . str_replace('</p>n','</p>',$leg->disruptions[0]->additionalInfo)   ;
                                                    echo "<div class=\"leg_disruptions\"><i class=\"fas fa-exclamation-triangle\"></i> <b>Disruptions: </b> $disruptions1 $disruptions2</div>\r\n";

                                                }


                                            echo "</div>\r\n";
                                        }
                                        echo "</div><!--/legs -->\r\n";
                                    }
                                    echo "</div><!-- journey -->\r\n";
                            }
                    echo "</div>\r\n";
                echo "</div><!-- /card -->\r\n";
                echo "</div><!-- /col -->\r\n";
echo "</div><!-- /row -->";

}//!empty($journeys)



    } else {
            echo "<br><br>
			Examples: <br><br>
			<a target=\"_blank\" href=\"/od/journey-planning/?from=1000266&to=1000013\">1000266 - 1000013</a><br>
            <a target=\"_blank\" href=\"/od/journey-planning/?from=51.531330%2C-0.097910&to=51.561330%2C-0.087910&sbm=1\">51.531330,-0.097910</a><br>
            <a target=\"_blank\" href=\"/od/journey-planning/?from=Alscot+Road&to=Harris+Academy&sbm=1\">Alscot Road - Harris Academy</a><br>
            <a target=\"_blank\" href=\"/od/journey-planning/?from=Lonsdale+Avenue&to=Fairlawn+Avenue&sbm=1\">Lonsdale Avenue - Fairlawn Avenue</a><br>
            ";

    }


?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<?php include("include/_footer.php"); ?>