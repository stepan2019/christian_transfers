<?php
    $title = "London Car Parkings" ; $selected = 10;
    $breadcrumb = "London Car Parkings";

    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->
	
<h2>London Car Parkings</h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->


<?php


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
                $slug = slug($row->name);$row->code = strtolower($row->code);
                echo "<tr><td>$row->name</td><td><a href=\"tel:$row->TelephoneNumber\"><i class=\"fas fa-phone-square\"></i> $row->TelephoneNumber</a></td><td>$row->bayCount</td><td>$row->free</td><td>$row->occupied</td><td><a class=\"btn btn-success btn-sm ml-1\" href=\"/od/car-park/$row->code/$slug\"><i class=\"fas fa-info-circle\"></i></a></td></tr>\r\n";
            }
            echo "</tbody>\r\n";
        echo "</table>\r\n";
    }//!empty($rows)

        echo "</div>\r\n";
    echo "</div>\r\n";

?>

<?php include("include/_footer.php"); ?>