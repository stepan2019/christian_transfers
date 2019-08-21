<?php
    if (empty($_GET['id'])) die("invalid id");
    $line_tid = $_GET['id'];

    $selected = 2;

    $title = "London line: $line_tid - Christian Transfers";
    $breadcrumb = "London line: $line_tid";
    $description = "London line: $line_tid - Christian Transfers";              

    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<h2>London lines: <?=$line_tid ?></h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->

<?php



    $line = $db->get_row("SELECT * FROM tfl_lines WHERE tid='$line_tid' limit 1 ");

    if (empty($line)) die("not found line: $line_tid ");


    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h1>Line: $line->tid</h1></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

    echo "<h5>Name: $line->name - Type: $line->modeName</h5>\r\n";
    echo "<span class=\"badge badge-success\">Type: $line->modeName</span>\r\n";

    echo "<h2>Routes for Line <em>$line->name</em></h2>\r\n";

    $q = "SELECT * FROM tfl_line_routes WHERE line_tid='$line_tid' order by id asc";

    $rows = $db->get_results($q);
    if (!empty($rows)) {
        echo "<table class=\"table table-sm table-striped table-hover\">\r\n";
            echo "<thead><tr><th>Type</th><th>Line</th><th>Name</th><th>Direction</th><th>Valid</th></tr></thead>\r\n";
            foreach ($rows as $row) {
                $validFrom = date("d.m.Y",strtotime($row->validFrom));$validTo = date("d.m.Y",strtotime($row->validTo));
                $night = ""; if ($row->serviceType == 'Night') $night = " <b>[night]</b>";
                    $slug1 = slug($row->originationName);
                    $slug2 = slug($row->destinationName);
                echo "<tr><td>$row->line_modeName</td><td title=\"$row->line_tid\">$row->line_name $night</td><td title=\"$row->origination - $row->destionation\">$row->name<br><a title=\"Lines for stations\" href=\"/od/station/$row->originator/$slug1\">#$row->originator</a> -> <a title=\"Lines for stations\" href=\"/od/station/$row->destination/$slug2\">#$row->destination</a></td><td>$row->direction</td><td>$validFrom<br>$validTo</td></tr>\r\n";
            }
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