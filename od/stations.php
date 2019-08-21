<?php
    include_once("dbconfig.php");
    $selected = 3;

    $title = "London train dlr national-rail tram overground and bus stations";
    $breadcrumb = "Train & Bus stations";
    $description = "London train dlr national-rail tram overground and bus stations - Christian Transfers";

    include("include/_header.php");
?>

<h2>London train dlr national-rail tram overground and bus stations</h2>
<p class="lead"><?=$lead ?></p>


<?php

    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h1>London Stations list</h1></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

    $q = "SELECT * FROM tfl_stations order by name asc limit 5000";

    $rows = $db->get_results($q);
    if (!empty($rows)) {
        echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableStations\">\r\n";
            echo "<thead><tr><th>Name</th><th>Type</th><th>Lines</th><th>Action</th></tr></thead>\r\n";
            echo "<tbody>\r\n";
            foreach ($rows as $row) {
                $stopType = stopTypePrint($row->stopType);
                $slug = slug($row->name);
                    $lines = "";
                    $rows4 = $db->get_results("select * from tfl_station_lines WHERE station_code = '$row->code' order by line_tid asc ");

                    if (!empty($rows4)) {
                        foreach ($rows4 as $row4) {
                            $lines .= "$row4->line_tid, ";
                        }
                        $lines = trim($lines,", ");
                    }


                echo "<tr><td><b>$row->name</b></td><td>$stopType</td>";
                echo "<td><p class=\"maxlines\">$lines</p></td>";
                echo "<td width=180>";
                echo "<a href=\"javascript:PopupStationLines('$row->code')\" class=\"btn btn-black btn-sm mt-1 ml-1\" title=\"Lines / Map\"><i class=\"fas fa-bus-alt\"></i></a>";
                echo "<a class=\"btn btn-black btn-sm mt-1 ml-1\" href=\"/od/cabwise/$row->code\" title=\"Taxi / Cab wise\" ><i class=\"fas fa-taxi\"></i></a>";
                echo "<a class=\"btn btn-sm btn-primary mt-1 ml-1\" href=\"/od/station/$row->code/$slug\" title=\"More details\">Details</a>";
                echo "</td>";
                echo "</tr>\r\n";
            }
            echo "</tbody>\r\n";
        echo "</table>\r\n";
    }//!empty($rows)

        echo "</div>\r\n";
    echo "</div>\r\n";

?>

<?php include("include/_footer.php"); ?>