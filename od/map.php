<?php
    $selected = 6;

    $title="London map Christian Transfers";
    $breadcrumb = "London map";
    $description = "London map";

    include("include/_header.php");
?>

<h2>London stations map</h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->


<div id="map"></div>


<?php

$rows1 = $db->get_results("SELECT `originator`,count(`originator`) as cnt FROM `tfl_line_routes` group by `originator` having cnt > 4 order by cnt desc limit 40");

foreach ($rows1 as $row1) {
    $details = $db->get_row("select * from tfl_stations WHERE code='$row1->originator' ");
    if (!empty($details)) {
        $row1->name = $details->name;
        $row1->lat = $details->lat;
        $row1->lon = $details->lon;
    }
}

echo "<div class=\"row\">\r\n ";
$max = 20; $i=1;
if (!empty($rows1)) {
        echo "<div class=\"col\">\r\n ";
        echo "<div class=\"card\">\r\n ";
            echo "<div class=\"card-header\">Popular Stations by routes</div>\r\n ";
            echo "<div class=\"card-body\">\r\n ";
            echo "<table class=\"table table-sm table-striped table-hover\">\r\n";
                    echo "<thead><tr><th>Station</th><th>Routes</th><th>Lines</th></tr></thead>\r\n";
                    echo "<tbody>\r\n";
                    foreach ($rows1 as $row) {
                        if ($i <= $max) {
                            $slug = slug($row->name);
                            echo "<tr><td title=\"$row->lat / $row->lon\"><b>$row->originator</b><br>$row->name</td><td>$row->cnt</td><td><a href=\"/od/station/$row->originator/$slug\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-bus-alt\"></i></a></td></tr>\r\n";
                        }
                        $i++;
                    }
                    echo "</tbody>\r\n";
            echo "</table>\r\n";
            echo "</div>\r\n";
        echo "</div>\r\n";
        echo "</div><!-- /col -->\r\n";
}

$i=1;
if (!empty($rows1)) {
        echo "<div class=\"col\">\r\n ";
        echo "<div class=\"card\">\r\n ";
            echo "<div class=\"card-header\">Popular Stations by routes</div>\r\n ";
            echo "<div class=\"card-body\">\r\n ";
            echo "<table class=\"table table-sm table-striped table-hover\">\r\n";
                    echo "<thead><tr><th>Station</th><th>Routes</th><th>Lines</th></tr></thead>\r\n";
                    echo "<tbody>\r\n";
                    foreach ($rows1 as $row) {
                        if ($i > $max) {
                            $slug = slug($row->name);
                            echo "<tr><td title=\"$row->lat / $row->lon\"><b>$row->originator</b><br>$row->name</td><td>$row->cnt</td><td><a href=\"/od/station/$row->originator/$slug\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-bus-alt\"></i></a></td></tr>\r\n";
                        }
                        $i++;
                    }
                    echo "</tbody>\r\n";
            echo "</table>\r\n";
            echo "</div>\r\n";
        echo "</div>\r\n";
        echo "</div><!-- /col -->\r\n";
}

echo "</div>\r\n";
//SELECT `line_tid`,count(`line_tid`) as cnt FROM `tfl_station_lines` group by `line_tid` order by cnt desc
//SELECT `station_code`,count(`station_code`) as cnt FROM `tfl_station_lines` group by `station_code` order by cnt desc



?>


<?php include("include/_footer.php"); ?>