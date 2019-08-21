<?php
    $selected = 9;
    
    $title = "London air quality Christian Transfers" ;
    $breadcrumb = "London Air quality";
    $description = "London Air quality";

    include("include/_header.php");
?>

    <!-- Banner rotator top begin -->
	<div align="center">
        <script type="text/javascript">
	        show_banners('468');
	    </script>
	</div>
	<!-- Banner rotator top end -->

    <!-- Header begin -->
	<div align="center"><br><br>
        <h1>London air quality - Current and future forecast</h1>
            <p>Informations about warm weather, speed of the wind, level and dispersion of pollution, hourly updates.</p>
    
	<!--<p class="lead"> to improve the text in this area ...</p> <h2> to improve the text in this area ...</h2> -->
	</div>
	<!-- Header end -->

<?php

    echo "<div class=\"card\">\r\n ";
        echo "<div class=\"card-header\"><h2>General air quality and hour by hour</h2></div>\r\n ";
        echo "<div class=\"card-body\">\r\n ";

$last = $db->get_row("SELECT * FROM tfl_air_quality order by id desc limit 1");
if (!empty($last)) {
    $last->currentSummary = html_entity_decode($last->currentSummary);$last->currentForecast = html_entity_decode($last->currentForecast);$bandInfo1 = $arrForecastBand[$last->currentBand];
    $last->futureSummary = html_entity_decode($last->futureSummary);$last->futureForecast = html_entity_decode($last->futureForecast);$bandInfo2 = $arrForecastBand[$last->futureBand];
    echo "<div class=\"currentForecast\">\r\n";
        echo "<h2>Current forecast information</h2>\r\n";
    echo "<div class=\"row\">\r\n ";
        echo "<div class=\"col\">\r\n ";
            echo "<span class=\"$bandInfo1[class]\">$last->currentBand</span>\r\n";
            echo "<p class=\"bandInfo\"><b>General population</b><br>$bandInfo1[general]</p><p class=\"bandInfo\"><b>At-risk individuals</b><br>$bandInfo1[atRisk]</p>";
        echo "</div>\r\n";
        echo "<div class=\"col\">\r\n ";
            echo "<p class=\"currentForecastP\">$last->currentForecast</p>\r\n";
        echo "</div>\r\n";
    echo "</div>\r\n";

        echo "<h2>Future forecast information</h2>\r\n";
    echo "<div class=\"row\">\r\n ";
        echo "<div class=\"col\">\r\n ";
            echo "<span class=\"$bandInfo2[class]\">$last->futureBand</span>\r\n";
            echo "<p class=\"bandInfo\"><b>General population</b><br>$bandInfo2[general]</p><p class=\"bandInfo\"><b>At-risk individuals</b><br>$bandInfo2[atRisk]</p>";
        echo "</div>\r\n";
        echo "<div class=\"col\">\r\n ";
            echo "<p class=\"currentForecastP\">$last->futureForecast</p>\r\n";
        echo "</div>\r\n";
    echo "</div>\r\n";

    echo "</div>\r\n";
}


    $q = "SELECT * FROM tfl_air_quality order by day desc, hour desc limit 1,23";

    $rows = $db->get_results($q);
    if (!empty($rows)) {
        echo "<h5>Last 24hour</h5>\r\n";
        echo "<table class=\"table table-sm table-striped table-hover\" id=\"\">\r\n";
            echo "<thead><tr><th>Day/Hour</th><th>Band</th><th>Forecast</th></tr></thead>\r\n";
            echo "<tbody>\r\n";
            foreach ($rows as $row) {
                $row->currentSummary = html_entity_decode($row->currentSummary);
                $row->currentForecast = html_entity_decode($row->currentForecast);
                 $bandInfo = $arrForecastBand[$row->currentBand];

                echo "<tr><td><b>$row->day $row->hour:00</b><span class=\"$bandInfo[class]\">$row->currentBand</span></td>";
                echo "<td><p class=\"bandInfo\"><b>General population</b><br>$bandInfo[general]</p><p class=\"bandInfo\"><b>At-risk individuals</b><br>$bandInfo[atRisk]</p></td>";
                echo "<td class=\"currentForecast\">$row->currentForecast</td></tr>\r\n";
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