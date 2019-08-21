<?php
    $title = "Bike points " ; $selected = 8;
    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<h2>London Bike points</h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->


<?php


    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h1>Bike points</h1></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

    $q = "SELECT * FROM tfl_bike_points order by name asc limit 5000";

    $rows = $db->get_results($q);
    if (!empty($rows)) {
        echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableBikePoints\">\r\n";
            echo "<thead><tr><th>Name</th><th>Map</th></tr></thead>\r\n";
            echo "<tbody>\r\n";
            foreach ($rows as $row) {
                $map = "";
                if (!empty($row->lat)) {$map_url = "https://maps.google.com/maps?f=d&ie=UTF8&msa=0&ll=$row->lat,$row->lon&zoom=12&daddr=$row->lat,$row->lon"; $map = "<a class=\"btn btn-info btn-sm\" href=\"$map_url\" target=\"_blank\" title=\"$row->lon , $row->lat\"><i class=\"fas fa-map-marker-alt\"></i> $row->lat, $row->lon</a>"; }
                echo "<tr><td>$row->name</td><td>$map</td></tr>\r\n";
            }
            echo "</tbody>\r\n";
        echo "</table>\r\n";
    }//!empty($rows)

        echo "</div>\r\n";
    echo "</div>\r\n";

?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<?php include("include/_footer.php"); ?>