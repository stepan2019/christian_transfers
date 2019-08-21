<?php
  include("/home/brgambal/public_html/christiantransfers.eu/od/dbconfig.php");
// php /home/brgambal/public_html/christiantransfers.eu/od/import/stop-points.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1

//  $serviceTypes = "Regular";
//  if (!empty($argv[1])) $serviceTypes = $argv[1];

  $dd = date("Y-m-d H:i:s");

  echo "*** Start cron for: stop-points on $dd *** \r\n";
  $rows = $db->get_results("select * from tfl_lines WHERE batched='0' limit 1000 ");
  if (empty($rows)) die("no rows \r\n");

  foreach ($rows as $line) {
      $lineID = $line->tid;
      $url = 'https://api.tfl.gov.uk/Line/' . $lineID . '/StopPoints' .'?app_id=' . APP_ID . '&app_key=' . APP_KEY;
      //echo $url; die();

      echo "get stop points for line: $lineID \r\n ";

      $output = file_get_contents($url);
      $data = json_decode($output);

      if (!$data) { echo("invalid response. \r\n" ); echo "<pre>"; print_r($output); echo "</pre>";  }

      //echo "<pre>"; print_r($data); echo "</pre>";  die();

        foreach ($data as $row) {
            $code = $row->id;
            $row->commonName = $db->escape($row->commonName);
            $lat = floatval($row->lat);
            $lon = floatval($row->lon);
            $indicator = $row->indicator;
            $status = $row->status;
            $stopType = $row->stopType;
            $stationNaptan = $row->stationNaptan;

            $exist = $db->get_row("select * from tfl_stop_points WHERE code='$code' limit 1 ");
            if (empty($exist)) {
                $db->query("INSERT INTO tfl_stop_points(code,name,status,placeType,lat,lon,indicator,stopType,stationNaptan) VALUES('$code','$row->commonName','$status','$row->placeType','$lat','$lon','$indicator','$stopType','$stationNaptan') ");
                echo "** SAVED line: $lineID / $code / $row->commonName / $lat $lon \r\n";
            } else {
                $db->query("UPDATE tfl_stop_points SET status='$status',placeType='$row->placeType',lat='$lat',lon='$lon',indicator='$indicator',stopType='$stopType',stationNaptan='$stationNaptan' WHERE code='$code' limit 1");
            }


            $exist2 = $db->get_row("select * from tfl_stations WHERE code='$code' limit 1 ");
            if (!empty($exist2)) {
                $db->query("UPDATE tfl_stations SET status='$status',placeType='$row->placeType',lat='$lat',lon='$lon',indicator='$indicator',stopType='$stopType',stationNaptan='$stationNaptan' WHERE code='$code' limit 1");
            }


            if (!empty($row->lines)) {
                //$db->query("DELETE FROM tfl_line_routes WHERE line_tid = '$row->id' ");
                foreach ($row->lines as $rl) {
                    $check = $db->get_row("select * from tfl_station_lines WHERE station_code='$code' AND line_tid='$lineID' limit 1");
                    if (empty($check)) {
                        $db->query("INSERT into tfl_station_lines(station_code,line_tid) VALUES('$code','$lineID') ");
                    }
                }
            }


        }
        $db->query("update tfl_lines SET batched='1' WHERE id='$line->id' limit 1 ");
        sleep(5);
  }// foreach $rows
  $dd2 = date("Y-m-d H:i:s");
  echo "*** END cron for stop-points on $dd2 *** \r\n";

?>