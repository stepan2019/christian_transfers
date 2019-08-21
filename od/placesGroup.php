<?php
    include_once("dbconfig.php");
    if (!empty($_GET['ids'])) {
        $ids = $_GET['ids'];
        $arr = explode("-", $ids);
        $idsQ = implode(",", $arr);
        $placeTypesQ = $db->get_results("select * from tfl_place_types WHERE id IN ($idsQ) ");

    }

        $searches = array();
        foreach ($placeTypesQ as $type) {
            $searches[] = $type->name;
        }
        $headlineQ = implode(", ",$searches);



    $title = "London - " . $headlineQ;  $selected = 11;
    include("include/_header.php");



?>

<h2>London - <?=$headlineQ?></h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->
<p class="lead">
    <?php
        $searches = array();
        foreach ($placeTypesQ as $type) {
            echo "<span>$type->nameFormated, </span>";
            $searches[] = $type->name;
        }
        $searchesQ = implode("','",$searches);
    ?>
</p>


<?php

        if (!empty($searchesQ)) {
            $q = "SELECT * FROM tfl_places WHERE placeType IN ('$searchesQ') AND name != '' order by name asc ";
            //echo $q; die();
            $rows = $db->get_results($q);
            if (!empty($rows)) {
            echo "<div class=\"card-body\">\r\n ";
                echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTablePlaces\">\r\n";
                    echo "<thead><tr><th>Type</th><th>Name</th><th>Info</th></tr></thead>\r\n";
                    echo "<tbody>\r\n";
                    foreach ($rows as $row) {
                        $slug = slug($row->name);
                        echo "<tr><td>$row->placeType</td><td><b>$row->name</b></td><td><a class=\"btn btn-success btn-sm ml-1\" href=\"/od/place/$row->code/$slug\"><i class=\"fas fa-info-circle\"></i></a></td></tr>\r\n";
                    }
                    echo "</tbody>\r\n";
                echo "</table>\r\n";
            echo "</div>\r\n";
            }//!empty($rows)
        } else {
            echo "invalid group" ;
        }

?>


      </div>
<?php include("include/_footer.php"); ?>