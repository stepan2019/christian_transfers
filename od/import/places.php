<?php
  include("/home/brgambal/public_html/christiantransfers.eu/od/dbconfig.php");
// php /home/brgambal/public_html/christiantransfers.eu/od/import/places.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1

  if (!empty($argv[1])) {
      $q = $argv[1];
      $type_rows = $db->get_results("select * from tfl_place_types WHERE name='$q' limit 1");
      if (empty($type_rows)) die("invalid type");
  } else {
    //$type_rows = $db->get_results("select * from tfl_place_types WHERE batched='0' AND disabled='0' limit 100");
    $type_rows = $db->get_results("select * from tfl_place_types WHERE disabled='0' limit 100");
  }


  if (empty($type_rows)) die("nothing for places cron \r\n");

  foreach ($type_rows as $type_row){
      $type = $type_row->name;

      $dd = date("Y-m-d H:i:s");
      echo "*** Start cron for: Places / $type on $dd *** \r\n";

          $url = 'https://api.tfl.gov.uk/Place/Type/' . $type . '/' .'?app_id=' . APP_ID . '&app_key=' . APP_KEY;
          //echo $url; die();

          $output = file_get_contents($url);
          $data = json_decode($output);

          if (!$data) { echo("invalid response. \r\n" ); echo "<pre>"; print_r($output); echo "</pre>";  }

          //echo "<pre>"; print_r($data); echo "</pre>";  die();

            foreach ($data as $row) {
                $name = $db->escape($row->commonName);
                $code = $db->escape($row->id);
                $placeType = $db->escape($row->placeType);
                $lat = $db->escape(floatval($row->lat));
                $lon = $db->escape(floatval($row->lon));

                if (!empty($row->additionalProperties)) {
                    $additionalProperties = $row->additionalProperties; $additionalPropertiesDB = $db->escape(json_encode($additionalProperties));
                    foreach ($additionalProperties as $ap) {
                        if ($ap->key == 'OrganisationName') $OrganisationName = $db->escape($ap->value);
                        if ($ap->key == 'AddressLine1') $AddressLine1 = $db->escape($ap->value);
                        if ($ap->key == 'AddressLine2') $AddressLine2 = $db->escape($ap->value);
                        if ($ap->key == 'Postcode') $Postcode = $db->escape($ap->value);
                        if ($ap->key == 'TelephoneNumber') $TelephoneNumber = $db->escape($ap->value);
                        if ($ap->key == 'NumberOfSpaces') $NumberOfSpaces = $db->escape($ap->value);
                        if ($ap->key == 'OpeningHours') $OpeningHours = $db->escape($ap->value);
                    }
                }

                $exist = $db->get_row("select * from tfl_places WHERE placeType='$placeType' AND code = '$code' limit 1 ");
                if (empty($exist)) {
                    $db->query("INSERT INTO tfl_places(placeType,code,name,lat,lon,additionalProperties,OrganisationName,AddressLine1,AddressLine2,Postcode) VALUES('$placeType','$code','$name','$lat','$lon','$additionalPropertiesDB','$OrganisationName','$AddressLine1','$AddressLine2','$Postcode') ");
                    echo "** SAVED place: $code / $name \r\n";
                }
                if ($placeType == 'CarPark' AND !empty($lat))  {
                    echo "UPDATED carPark table \r\n";
                    $db->query("UPDATE tfl_car_park SET lat='$lat', lon='$lon',TelephoneNumber='$TelephoneNumber',NumberOfSpaces='$NumberOfSpaces',OpeningHours='$OpeningHours' WHERE code='$code' limit 1");
                    //echo("UPDATE tfl_car_park SET lat='$lat', lon='$lon',TelephoneNumber='$TelephoneNumber',NumberOfSpaces='$NumberOfSpaces',OpeningHours'$OpeningHours' WHERE code='$code' limit 1");
                }


            }

      echo "*** END cron for: Places / $type on $dd *** \r\n";
      $db->query("UPDATE tfl_place_types SET batched='1' WHERE name='$type' limit 1");
      sleep(10);
  } //foreach

  $dd2 = date("Y-m-d H:i:s");
  echo "*** END cron for places on $dd2 *** \r\n";

?>