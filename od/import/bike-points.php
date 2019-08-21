<?php
  include("/home/brgambal/public_html/christiantransfers.eu/od/dbconfig.php");
// php /home/brgambal/public_html/christiantransfers.eu/od/import/bike-points.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1


//  $serviceTypes = "Regular";
//  if (!empty($argv[1])) $serviceTypes = $argv[1];

  $dd = date("Y-m-d H:i:s");

  echo "*** Start cron for: bike-points on $dd *** \r\n";

      $url = 'https://api.tfl.gov.uk/BikePoint/' .'?app_id=' . APP_ID . '&app_key=' . APP_KEY;
      //echo $url; die();

      $output = file_get_contents($url);
      $data = json_decode($output);

      if (!$data) { echo("invalid response. \r\n" ); echo "<pre>"; print_r($output); echo "</pre>";  }

      //echo "<pre>"; print_r($data); echo "</pre>";  die();

        foreach ($data as $row) {
            $code = $row->id;
            $row->commonName = $db->escape($row->commonName);
            $placeType = $row->placeType;
            $lat = floatval($row->lat);
            $lon = floatval($row->lon);


            $exist = $db->get_row("select * from tfl_bike_points WHERE code='$code' limit 1 ");
            if (empty($exist)) {
                $db->query("INSERT INTO tfl_bike_points(code,name,placeType,lat,lon) VALUES('$code','$row->commonName','$placeType','$lat','$lon') ");
                echo "** SAVED bike point: $code / $row->commonName / $lat $lon \r\n";
            }
        }



  $dd2 = date("Y-m-d H:i:s");
  echo "*** END cron for stop-points on $dd2 *** \r\n";

?>