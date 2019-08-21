<?php
    $selected = 5;

    $title = "London stop points Christian Transfers";
    $breadcrumb = "London Stop Points";
    $description = "London stop points Christian Transfers";
    include("include/_header.php");
?>

<h2>London stop points</h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->



<?php
    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h1>Stop Points</h1></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

    $q = "SELECT * FROM tfl_stop_points order by name asc limit 5000";

    $rows = $db->get_results($q);
    if (!empty($rows)) {
        echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableStopPoints\">\r\n";
            echo "<thead><tr><th>Name</th><th>Type</th><th>Lines</th><th>Map</th></tr></thead>\r\n";
            echo "<tbody>\r\n";

            echo "</tbody>\r\n";
        echo "</table>\r\n";
    }//!empty($rows)

        echo "</div>\r\n";
    echo "</div>\r\n";

?>

<?php include("include/_footer.php"); ?>