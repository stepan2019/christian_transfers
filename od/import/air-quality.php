<?php
  include("/home/brgambal/public_html/christiantransfers.eu/od/dbconfig.php");
// php /home/brgambal/public_html/christiantransfers.eu/od/import/air-quality.php >> /home/brgambal/public_html/christiantransfers.eu/od/import/log.txt 2>&1

//  $serviceTypes = "Regular";
//  if (!empty($argv[1])) $serviceTypes = $argv[1];

  $dd = date("Y-m-d H:i:s");

  echo "*** Start cron for: air-quality on $dd *** \r\n";

      $url = 'https://api.tfl.gov.uk/AirQuality/' .'?app_id=' . APP_ID . '&app_key=' . APP_KEY;
      //echo $url; die();

      $output = file_get_contents($url);
      $data = json_decode($output);

      if (!$data) { echo("invalid response. \r\n" ); echo "<pre>"; print_r($output); echo "</pre>";  }

      //echo "<pre>"; print_r($data); echo "</pre>";  die();

        foreach ($data->currentForecast as $row) {

            if ($row->forecastType == 'Current') {
                $currentBand     = trim($row->forecastBand);
                $currentSummary  = $row->forecastSummary;
                $currentForecast = $row->forecastText;
            }

            if ($row->forecastType == 'Future') {
                $futureBand       = trim($row->forecastBand);
                $futureSummary    = $row->forecastSummary;
                $futureForecast   = $row->forecastText;
            }

        }

        if (!empty($currentBand)) {
            $day = date("Y-m-d"); $hour = date("H");

            $exist = $db->get_row("select * from tfl_air_quality WHERE day = '$day' AND hour='$hour' limit 1 ");
            if (empty($exist)) {
                $currentSummary = $db->escape($currentSummary); $currentForecast = $db->escape($currentForecast); $futureSummary = $db->escape($futureSummary); $futureForecast = $db->escape($futureForecast);
                $db->query("INSERT INTO tfl_air_quality(day,hour,currentBand,currentSummary,currentForecast,futureBand,futureSummary,futureForecast) VALUES('$day','$hour','$currentBand','$currentSummary','$currentForecast','$futureBand','$futureSummary','$futureForecast') ");
                echo "** SAVED forecast, day: $day, hour: $hour / $currentSummary  \r\n";
            }
        }



  $dd2 = date("Y-m-d H:i:s");
  echo "*** END cron for air-quality on $dd2 *** \r\n";

?>