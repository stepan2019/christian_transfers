<?php
  include("/home/brgambal/public_html/christiantransfers.eu/od/dbconfig.php");
// php /home/brgambal/public_html/christiantransfers.eu/od/import/bike-points.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1


//  $serviceTypes = "Regular";
//  if (!empty($argv[1])) $serviceTypes = $argv[1];

  $dd = date("Y-m-d H:i:s");

  echo "*** Start cron for: Place/Meta/PlaceTypes on $dd *** \r\n";

      $url = 'https://api.tfl.gov.uk/Place/Meta/PlaceTypes/' .'?app_id=' . APP_ID . '&app_key=' . APP_KEY;
      //echo $url; die();

      $output = file_get_contents($url);
      $data = json_decode($output);

      if (!$data) { echo("invalid response. \r\n" ); echo "<pre>"; print_r($output); echo "</pre>";  }

      //echo "<pre>"; print_r($data); echo "</pre>";  die();

        foreach ($data as $row) {
            $name = $db->escape($row);


            $exist = $db->get_row("select * from tfl_place_types WHERE name = '$name' limit 1 ");
            if (empty($exist)) {
                $db->query("INSERT INTO tfl_place_types(name) VALUES('$name') ");
                echo "** SAVED place type: $name \r\n";
            }
        }



  $dd2 = date("Y-m-d H:i:s");
  echo "*** END cron for place type on $dd2 *** \r\n";

?>