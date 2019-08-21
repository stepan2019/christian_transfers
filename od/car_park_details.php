<?php
    include("dbconfig.php");
    $id = $_REQUEST['id'] ;
    if (empty($id)) die("Invalid request");

    $row = $db->get_row("SELECT * FROM tfl_car_park WHERE code = '$id' limit 1");
    if (empty($row)) die("Invalid data");

    $title = "London - Car park - " . $row->name;  $selected = 5;
    $breadcrumb = $row->name;
    $description = "London - Car park - " . $row->name;
    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<?php
echo "<div class=\"row\">\r\n ";
    echo "<div class=\"col details7\">\r\n ";
        echo "<h1>London - Car park - $row->name</h1>\r\n";
        echo "<h5>Code: $row->code</h5>\r\n";
        echo "<h6><b>Location:</b> Lat $row->lat / Lon $row->lon</h6>\r\n";

        echo "<h6><b>Telephone:</b> $row->TelephoneNumber</h6>\r\n";
        echo "<h6><b>OpeningHours:</b> $row->OpeningHours</h6>\r\n";
        echo "<h6><b>Type:</b> $row->bayType</h6>\r\n";
        echo "<h6><b>bayCount:</b> $row->bayCount</h6>\r\n";
        echo "<h6><b>free:</b> $row->free</h6>\r\n";
        echo "<h6><b>occupied:</b> $row->occupied</h6>\r\n";

    echo "</div><div class=\"col\">\r\n" ;

        echo Location2map($row->lat,$row->lon);
        echo Location2href($row->lat,$row->lon);


    echo "</div><!--/col -->\r\n";


echo "</div><!--/row-->\r\n";


    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h1>Car Parkings</h1></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

    $q = "SELECT * FROM tfl_car_park order by name asc limit 5000";

    $rows = $db->get_results($q);
    if (!empty($rows)) {
        echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableCarPark\">\r\n";
            echo "<thead><tr><th>Name</th><th>Phone</th><th>Cnt</th><th>Free</th><th title=\"Occupied\"><i class=\"fas fa-ban\"></i></th><th>Map</th></tr></thead>\r\n";
            echo "<tbody>\r\n";
            foreach ($rows as $row) {
                $map = "";
                if (!empty($row->lat)) {$map_url = "https://maps.google.com/maps?f=d&ie=UTF8&msa=0&ll=$row->lat,$row->lon&zoom=12&daddr=$row->lat,$row->lon"; $map = "<a class=\"btn btn-info btn-sm\" href=\"$map_url\" target=\"_blank\" title=\"$row->lon , $row->lat\"><i class=\"fas fa-map-marker-alt\"></i></a>"; }

                $slug = slug($row->name); $row->code = strtolower($row->code);           
                echo "<tr><td>$row->name</td><td><a href=\"tel:$row->TelephoneNumber\"><i class=\"fas fa-phone-square\"></i> $row->TelephoneNumber</a></td><td>$row->bayCount</td><td>$row->free</td><td>$row->occupied</td><td><a class=\"btn btn-success btn-sm ml-1\" href=\"/od/car-park/$row->code/$slug\"><i class=\"fas fa-info-circle\"></i></a></td></tr>\r\n";
            }
            echo "</tbody>\r\n";
        echo "</table>\r\n";
    }//!empty($rows)

        echo "</div>\r\n";
    echo "</div>\r\n";

?>
<?php include("include/_footer.php"); ?>