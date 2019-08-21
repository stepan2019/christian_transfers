<?php
  include("/home/brgambal/public_html/christiantransfers.eu/od/dbconfig.php");
// php /home/brgambal/public_html/christiantransfers.eu/od/import/line-routes.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1


  $serviceTypes = "Regular";

  if (!empty($argv[1])) $serviceTypes = $argv[1];

  $dd = date("Y-m-d H:i:s");

  echo "*** Start cron for line-routes: $serviceTypes on $dd *** \r\n";

  $url = 'https://api.tfl.gov.uk/Line/Route?serviceTypes=' . $serviceTypes . '&app_id=' . APP_ID . '&app_key=' . APP_KEY;
  //echo $url; die();

  $output = file_get_contents($url);
  $data = json_decode($output);

  if (!$data) { echo("invalid response. \r\n" ); echo "<pre>"; print_r($output); echo "</pre>"; die(); }

  $total = sizeof($data);
  echo "Total $serviceTypes lines: $total \r\n";
//  $db->query("truncate table tfl_line_routes; ");

    foreach ($data as $row) {

        $exist = $db->get_row("select * from tfl_lines WHERE tid='$row->id' limit 1 ");
        //echo("select * from tfl_lines WHERE tid='$row->id' limit 1 \r\n");
        if (!empty($exist)) {
            //echo "***/ exist line $row->id *** \r\n";
        } else {
            $db->query("INSERT INTO tfl_lines(tid,name,modeName) VALUES('$row->id','$row->name','$row->modeName') ");
            echo "line tID: $row->id / modeName: $row->modeName / Name: $row->name \r\n";
            //echo("INSERT INTO tfl_lines(tid,name,modeName) VALUES('$row->id','$row->name','$row->modeName') \r\n");

            echo "** SAVED line ** \r\n";
        }


        if (!empty($row->routeSections)) {
            //$db->query("DELETE FROM tfl_line_routes WHERE line_tid = '$row->id' ");
            foreach ($row->routeSections as $rs) {
                $rs->name = $db->escape($rs->name);  $rs->originationName = $db->escape($rs->originationName);$rs->destinationName = $db->escape($rs->destinationName);
                $validTo = date("Y-m-d  H:i:s", strtotime($rs->validTo)); $validFrom = date("Y-m-d  H:i:s",strtotime($rs->validFrom));

                $check1 = $db->get_row("select * from tfl_stations WHERE code='$rs->originator' limit 1");
                if (empty($check1)) {
                    $db->query("INSERT into tfl_stations(code,name) VALUES('$rs->originator','$rs->originationName') ");
                    echo "added stations: $rs->originator / $rs->originationName \r\n";
                }
                $check2 = $db->get_row("select * from tfl_stations WHERE code='$rs->destination' limit 1");
                if (empty($check2)) {
                    $db->query("INSERT into tfl_stations(code,name) VALUES('$rs->destination','$rs->destinationName') ");
                    echo "added stations: $rs->destination / $rs->destinationName \r\n";
                }


                $stop_point1 = $db->get_row("select * from tfl_stop_points WHERE code='$rs->originator' limit 1");
                if (!empty($stop_point1)) {
                    $db->query("UPDATE tfl_stations SET status='$stop_point1->status',placeType='$stop_point1->placeType',lat='$stop_point1->lat',lon='$stop_point1->lon',indicator='$stop_point1->indicator',stopType='$stop_point1->stopType',stationNaptan='$stop_point1->stationNaptan' WHERE code='$rs->originator' limit 1");
                }

                $stop_point1 = $db->get_row("select * from tfl_stop_points WHERE code='$rs->destination' limit 1");
                if (!empty($stop_point1)) {
                    $db->query("UPDATE tfl_stations SET status='$stop_point1->status',placeType='$stop_point1->placeType',lat='$stop_point1->lat',lon='$stop_point1->lon',indicator='$stop_point1->indicator',stopType='$stop_point1->stopType',stationNaptan='$stop_point1->stationNaptan' WHERE code='$rs->destination' limit 1");
                }


                $exist = $db->get_row("select * from tfl_line_routes WHERE line_tid='$row->id' AND originator='$rs->originator' AND destination ='$rs->destination' limit 1");
                if (empty($exist)) {
                    $q = "INSERT into tfl_line_routes(line_tid,line_name,line_modeName,name,direction,originationName,destinationName,originator,destination,serviceType,validTo,validFrom)
                    VALUES('$row->id','$row->name','$row->modeName','$rs->name','$rs->direction','$rs->originationName','$rs->destinationName','$rs->originator','$rs->destination','$rs->serviceType','$validTo','$validFrom') ";
                    $db->query($q);
                    $lastID = $db->insert_id;
                    echo "$lastID -> originator: $rs->originator / destionation: $rs->destination / $rs->direction / $rs->name \r\n";
                    if (empty($lastID)) {echo "** DB ERROR** " . $db->last_error . "\r\n";}
                }



            }
        }


    }

  $dd2 = date("Y-m-d H:i:s");
  echo "*** END cron for line-routes: $serviceTypes on $dd2 *** \r\n";

?>