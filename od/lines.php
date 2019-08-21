<?php
    $selected = 2;

    $title ="London lines - Christian Transfers " . $_GET['mode'];
	$breadcrumb = "London lines " . $_GET['mode'];
	$description = "London lines - Christian Transfers - " . $_GET['mode'];

    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

<h2>London Lines</h2>
<!--<p class="lead"> ... to improve the text in this area ... </p>
<p>... to improve the text in this area ...</p> -->


<?php
    $modes = $db->get_results("SELECT `modeName`,count(id) as cnt FROM `tfl_lines` group by `modeName` ");
    if (empty($modes)) die("error with db 1");

    echo "<div class=\"mode_nav\">\r\n ";
    if (empty($_GET['mode'])) 
	    
		echo "<a class=\"btn btn-black btn-sm ml-1 mt-1\" href=\"/od/lines/\"><b>ALL</b></a>"; 
		
		else echo "<a class=\"btn btn-success btn-sm ml-1 mt-1\" href=\"/od/lines/\"><b>ALL</b></a>";
        foreach ($modes as $mode) {
            $class = 'btn-success'; if ($_GET['mode'] == $mode->modeName) $class = 'btn-black';
            echo "<a class=\"btn $class btn-sm ml-1 mt-1\" href=\"/od/lines/$mode->modeName\">$mode->modeName <em>($mode->cnt)</em></a>\r\n";
        }
    echo "<a class=\"btn btn-success btn-night btn-sm ml-1 mt-1\" href=\"/od/lines/night\"><i class=\"far fa-moon\"></i> only night</a>";

    echo "</div>\r\n";

    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\">\r\n ";
        if (!empty($_GET['mode'])) {
            $modeName = trim($_GET['mode'],'/');
            if ($modeName == 'night') {
                echo "<h2>ONLY night lines</h2>";
                $q = "SELECT * FROM tfl_line_routes WHERE serviceType='Night' GROUP BY line_name order by line_name asc";
            } else {
                echo "<h2>$modeName</h2>";
                $q = "SELECT * FROM tfl_line_routes WHERE line_modeName='$modeName' GROUP BY line_name order by line_name asc ";
            }
        } else {
            echo "<h2>ALL lines for ALL modes</h2>";
            $q = "SELECT * FROM tfl_line_routes GROUP BY line_name order by line_name asc";
        }


        echo "</div>";
        echo "<div class=\"card-body\">\r\n ";
        $rows = $db->get_results($q);
        if (!empty($rows)) {
            echo "<table class=\"table table-sm table-striped table-hover\" id=\"dataTableLines\">\r\n";
                echo "<thead><tr><th>Type</th><th>Line</th><th>Origination</th><th>Destination</th><th>Valid</th><th></th></tr></thead>\r\n";
                echo "<tbody>\r\n";
                foreach ($rows as $row) {
                    $validFrom = date("d.m.Y",strtotime($row->validFrom));$validTo = date("d.m.Y",strtotime($row->validTo));
                    $night = ""; if ($row->serviceType == 'Night') $night = " <b>[night]</b>";
                    $slug1 = slug($row->originationName);
                    $slug2 = slug($row->destinationName);
                    echo "<tr><td>$row->line_modeName</td><td title=\"$row->line_tid\"><b>$row->line_name</b> $night</td><td title=\"$row->originator\">$row->originationName<br><a title=\"Lines for stations\" href=\"/od/station/$row->originator/$slug1\">#$row->originator</a></td><td title=\"$row->destination\">$row->destinationName<br><a title=\"Lines for stations\" href=\"/od/station/$row->destination/$slug2\">#$row->destination</a></td><td>$validFrom<br>$validTo</td><td><a class=\"btn btn-info btn-sm\" href=\"/od/line/$row->line_tid\" >Details</a></td></tr>\r\n";
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