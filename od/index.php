<?php
    $selected = 1;

    $title = "Open Data London trains buses parkings information Christian Transfers";
    $breadcrumb = "Open Data";
	$description = "Open Data - London transport information about trains buses arrivals, parkings, air pollution Christian Transfers";
    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

    <br><br>
    <h1 align="center">London transport information</h1>
    <p>Choose from various informations like: Stations real time info about arrivals & departure, Bus stations Timetable, Journey Planner, road updates on disruption, restrictions and constructions.</p>
    
	<!--<p class="lead"> to improve the text in this area ...</p> <h2> to improve the text in this area ...</h2> -->

<?php
    $modes = $db->get_results("SELECT `modeName`,count(id) as cnt FROM `tfl_lines` group by `modeName` ");
    if (empty($modes)) die("error with db 1");

    echo "<div class=\"mode_nav\">\r\n ";
    echo "<a class=\"btn btn-info btn-sm\" href=\"/od/lines/\"><b>ALL</b></a>";
        foreach ($modes as $mode) {
            echo "<a class=\"btn btn-success  btn-sm\" href=\"/od/lines/$mode->modeName\">$mode->modeName <em>($mode->cnt)</em></a>\r\n";
        }
    echo "<a class=\"btn btn-black  btn-sm\" href=\"/od/lines/night\"><b>only night</b></a>";

    echo "</div>\r\n";
?>
    
	<br><br>
    <h2 align="center">London points of interest</h2>
	<p>Many options like taxi ranks, minicabs offices, car parks, bike parkings, charge points, water fronts or speed cams.</p>

<?php
    echo "<div class=\"mode_nav\">\r\n ";
        foreach ($placeTypes as $type) {
            echo "<a class=\"btn btn-success btn-sm ml-1 mt-1\" href=\"/od/places/$type->name\">$type->name <em>($type->cnt)</em></a>\r\n";
        }
    echo "</div>\r\n";
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->
    
	<br><br>
    <h2 align="center">London air quality - Current and future forecast</h2>
	<p>Informations about warm weather, speed of the wind, level and dispersion of pollution, hourly updates.</p>
	
<?php
$last = $db->get_row("SELECT * FROM tfl_air_quality order by id desc limit 2");
if (!empty($last)) {
    $last->currentSummary = html_entity_decode($last->currentSummary);$last->currentForecast = html_entity_decode($last->currentForecast);$bandInfo1 = $arrForecastBand[$last->currentBand];
    $last->futureSummary = html_entity_decode($last->futureSummary);$last->futureForecast = html_entity_decode($last->futureForecast);$bandInfo2 = $arrForecastBand[$last->futureBand];
    echo "<div class=\"currentForecast\">\r\n";


    echo "<div class=\"row\">\r\n ";
        echo "<div class=\"col\">\r\n ";
            echo "<h2>Current forecast</h2>\r\n";
            echo "<p class=\"currentForecastP\">$last->currentForecast</p>\r\n";
        echo "</div>\r\n";

        echo "<div class=\"col\">\r\n ";
            echo "<h2>Future forecast</h2>\r\n";
            echo "<p class=\"currentForecastP\">$last->futureForecast</p>\r\n";
        echo "</div>\r\n";
    echo "</div>\r\n";


    echo "</div>\r\n";
}

?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->  
	
<?php include("include/_footer.php"); ?>