<?php
  include("../dbconfig.php");

  $id = $_REQUEST['id'] ;

  if (empty($id)) die("Invalid request");

  $row = $db->get_row("SELECT * FROM tfl_places WHERE id = '$id' limit 1");

  if (empty($row)) die("Invalid data");

  if (!empty($row->lat) AND !empty($row->lon)) {
      $gmapsKey = 'AIzaSyDhzq0vdhpQKEy4OoB81xqyGcVJw-3_LaA';

      $src = "https://maps.googleapis.com/maps/api/staticmap?time=" . time() . "&amp;" .
    						"center={$row->lat},{$row->lon}&amp;" .
    						"zoom=12&amp;" .
    						"markers=" . urlencode("color:green|{$row->lat},{$row->lon}") . "&amp;" .
    						"size=470x300&amp;" .
    						"maptype=roadmap&amp;" .
    						"sensor=false&amp;" .
    						"key=$gmapsKey";
      $href = 'https://maps.google.com/maps?' .
    						'f=d&' .
    						'ie=UTF8&' .
    						'msa=0&' .
    						"ll={$row->lat},{$row->lon}&" .
    						"zoom=12&" .
    						"daddr={$row->lat},{$row->lon}";
   }



  echo "<div class=\"popup_place\">\r\n";
    echo "<h1>$row->name</h1>\r\n";
    echo "<h5>Type: $row->placeType / Code: $row->code</h5>\r\n";
    echo "<h6>Location: Lat $row->lat / Lon $row->lon</h6>\r\n";
    if (!empty($src)) echo "<a href=\"" . $href . "\" rel=\"nofollow\" class=\"gmaps_url\" target=\"_blank\"><img src=\"$src\" width=\"470\" height=\"300\" alt=\"Google map\" /></a>";
	if (!empty($href)) echo "<a href=\"" . $href . "\" rel=\"nofollow\"  class=\"gmaps_url2\" target=\"_blank\">View Google maps</a>";

    $address = "";
    if (!empty($row->OrganisationName)) $address .= "$row->OrganisationName<br>";
    if (!empty($row->AddressLine1)) $address .= "$row->AddressLine1, $row->AddressLine2<br>";
    if (!empty($row->Postcode)) $address .= "$row->Postcode";
    if (!empty($address)) echo "<p>$address</p>\r\n";

    if (!empty($row->additionalProperties)) {
        $additionalPropertiesEncoded = json_decode($row->additionalProperties);
        //echo "<pre>"; print_r($additionalPropertiesEncoded); echo "</pre>";
        if (!empty($additionalPropertiesEncoded)) {
            echo "<div class=\"additional\">\r\n ";
            echo "<table class=\"table table-striped table-sm\">\r\n";
                foreach ($additionalPropertiesEncoded as $tmp) {
                    $tmp->key = trim($tmp->key);$tmp->value = trim($tmp->value);
                    if (!empty($tmp->key) AND !empty($tmp->value)){
                        if (!in_array($tmp->value,$filter)) {
                            if (strtoupper($tmp->value) == 'TRUE' ) $tmp->value = "<span class=\"check_yes\"><i class=\"fas fa-check\"></i> yes</span>";
                            if (strtoupper($tmp->value) == 'FALSE' ) $tmp->value = "<span class=\"check_no\"><i class=\"fas fa-times\"></i> no</span>";
                            echo "<tr><td><b>$tmp->key</b></td><td>$tmp->value</td></tr>\r\n";
                        }
                    }
                }
            echo "</table>\r\n";
            echo "</div>\r\n";
        }
    }


  echo "</div>\r\n";



?>