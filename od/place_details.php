<?php
    include_once("dbconfig.php");
    $id = $_REQUEST['id'] ;
    if (empty($id)) die("Invalid request");

    $row = $db->get_row("SELECT * FROM tfl_places WHERE code = '$id' OR id='$id' limit 1");
    if (empty($row)) die("Invalid data");

    $selected = 11;

    $title = (!empty($row->nameFormated)) ? $row->nameFormated : $row->name . " - " . placeTypePrint($row->placeType) . ' - London ' ;
    $breadcrumb = $row->name;

        $additional = "";
        if (!empty($row->additionalProperties)){
            $additionalPropertiesEncoded = json_decode($row->additionalProperties);
            foreach ($additionalPropertiesEncoded as $tmp) {
                $tmp->key = trim($tmp->key);$tmp->value = trim($tmp->value);
                if ($tmp->key == 'Address1') $additional .= " - $tmp->value ";
                if ($tmp->key == 'Address2') $additional .= " $tmp->value ";
                if ($tmp->key == 'PostCode') $additional .= " $tmp->value ";
            }
        }
    $description =  $row->name . " " . placeTypePrint($row->placeType) . $additional . ' - London ';

    include("include/_header.php");
?>



<?php
echo "<div class=\"row\">\r\n ";
    echo "<div class=\"col\">\r\n ";
        echo "<h1>" . placeTypePrint($row->placeType) . " - " . $row->name . "</h1>\r\n";
        echo "<h6>Location: Lat $row->lat / Lon $row->lon</h6>\r\n";

        $address = "";
        if (!empty($row->OrganisationName)) $address .= "$row->OrganisationName<br>";
        if (!empty($row->AddressLine1)) $address .= "$row->AddressLine1, $row->AddressLine2<br>";
        if (!empty($row->Postcode)) $address .= "$row->Postcode";
        if (!empty($address)) echo "<p>$address</p>\r\n";

        echo Location2map($row->lat,$row->lon);
        echo Location2href($row->lat,$row->lon);


    echo "</div><!--/col -->\r\n";

    if (!empty($row->additionalProperties)) {
    echo "<div class=\"col\">\r\n ";
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
    echo "</div>" ;
    }

echo "</div><!--/row-->\r\n";


    if (!empty($row->placeType)) {
        $type = $row->placeType;
        echo "<div class=\"card\">\r\n ";
            echo "<div class=\"card-header\">\r\n ";
                echo "<h2>" . placeTypePrint($type) . "</h2>";
            echo "</div>";

        $q = "SELECT * FROM tfl_places WHERE placeType='$type' AND name != '' order by name asc ";

        echo "<div class=\"card-body\">\r\n ";
        $rows = $db->get_results($q);
        if (!empty($rows)) {
            echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTablePlaces\">\r\n";
                echo "<thead><tr><th>Name</th><th>Info</th></tr></thead>\r\n";
                echo "<tbody>\r\n";
                foreach ($rows as $row) {
                    $slug = slug($row->name); $row->code = strtolower($row->code);    
                    echo "<tr><td><b>$row->name</b></td><td><a class=\"btn btn-success btn-sm ml-1\" href=\"/od/place/$row->code/$slug\"><i class=\"fas fa-info-circle\"></i></a></td></tr>\r\n";
                }
                echo "</tbody>\r\n";
            echo "</table>\r\n";
        }//!empty($rows)
        echo "</div>\r\n";
    echo "</div>\r\n";
    }// action == lines



?>
<?php include("include/_footer.php"); ?>