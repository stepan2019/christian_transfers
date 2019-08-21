<?php
    include_once("dbconfig.php");

    $selected = 11;
    $placeType = $db->get_row("select * from tfl_place_types WHERE name='$_GET[type]' limit 1 ");
        $headline = (!empty($placeType->headline)) ? $placeType->headline : placeTypePrint($_GET['type']);

    $title = "London - $headline - Christian Transfers";
    $breadcrumb = $headline;
    $description = "London - $headline - Christian Transfers";

    include("include/_header.php");
?>

<h2>London <?=$headline ?></h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->

<?php


    if (!empty($_GET['type'])) {
        $type = $_GET['type'];
        echo "<div class=\"card\">\r\n ";
            echo "<div class=\"card-header\">\r\n ";
                echo "<h2>" . $headline . "</h2>";
            echo "</div>";

        if ($type == 'CoachPark')
            $q = "SELECT * FROM tfl_places WHERE (placeType='$type' OR placeType='OtherCoachParking' ) AND name != '' order by name asc ";
        else
            $q = "SELECT * FROM tfl_places WHERE placeType='$type' AND name != '' order by name asc ";


        echo "<div class=\"card-body\">\r\n ";
        $rows = $db->get_results($q);
        if (!empty($rows)) {
            echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTablePlaces\">\r\n";
                echo "<thead><tr><th>Name</th><th>Info</th></tr></thead>\r\n";
                echo "<tbody>\r\n";
                foreach ($rows as $row) {
                    $slug = slug($row->name);$row->code = strtolower($row->code);
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