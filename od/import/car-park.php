<?php
  include("/home/brgambal/public_html/christiantransfers.eu/od/dbconfig.php");
// php /home/brgambal/public_html/christiantransfers.eu/od/import/car-park.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1


//  $serviceTypes = "Regular";
//  if (!empty($argv[1])) $serviceTypes = $argv[1];

  $dd = date("Y-m-d H:i:s");

  echo "*** Start cron for: car-park on $dd *** \r\n";

      $url = 'https://api.tfl.gov.uk/Occupancy/CarPark/' .'?app_id=' . APP_ID . '&app_key=' . APP_KEY;
      //echo $url; die();

      $output = file_get_contents($url);
      $data = json_decode($output);

      if (!$data) { echo("invalid response. \r\n" ); echo "<pre>"; print_r($output); echo "</pre>";  }

      //echo "<pre>"; print_r($data); echo "</pre>";  die();

        foreach ($data as $row) {
            $code = $row->id;
            $row->name = $db->escape($row->name);

            if (!empty($row->bays)) {
                foreach ($row->bays as $bay) {
                    if ($bay->bayType != 'Disabled') {
                        $bayType  = $bay->bayType;
                        $bayCount = intval($bay->bayCount);
                        $free     = intval($bay->free);
                        $occupied = intval($bay->occupied);
                    }
                }
            }



            $exist = $db->get_row("select * from tfl_car_park WHERE code='$code' limit 1 ");
            if (empty($exist)) {
                $db->query("INSERT INTO tfl_car_park(code,name,bayType,bayCount,free,occupied) VALUES('$code','$row->name ','$bayType','$bayCount','$free','$occupied') ");
                echo "** SAVED car park : $code / $row->name / $bayCount / $free / $occupied \r\n";
            } else {
                $db->query("UPDATE tfl_car_park SET bayType='$bayType',bayCount='$bayCount',free='$free',occupied='$occupied' WHERE id='$exist->id' limit 1 ");
                echo "** UPDATED car park : $code / $row->name / $bayCount / $free / $occupied \r\n";
            }
        }



  $dd2 = date("Y-m-d H:i:s");
  echo "*** END cron for stop-points on $dd2 *** \r\n";

?>