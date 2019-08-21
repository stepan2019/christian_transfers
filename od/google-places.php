<?php
    $selected = 6;

    $title="London places";
    $breadcrumb = "London places";
    $description = "London places";

    include("include/_header.php");
?>

<h2>London Places</h2>
<form action="" method="get">
    <input type="text" placeholder="station code" name="q" value="<?=$_GET['q'] ?>"  />
</form><br>

<?php
  if (!empty($_GET['q'])) {

        $stationCode = $_GET['q'];
        $station = $db->get_row("SELECT * FROM tfl_stations WHERE code = '$stationCode' limit 1 ");
echo "<h1>Places nearby $station->name</h1>\r\n";
//    $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=" . GMAP_KEY . "&locationbias=circle:2000@47.6918452,-122.2226413&fields=photos,formatted_address,name,rating,opening_hours,geometry&inputtype=textquery&input=" . urlencode($q);
    $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=" . GMAP_KEY . "&location=$station->lat,$station->lon&radius=5000";
//    echo $url; die();

    $data = json_decode(file_get_contents($url));

    if (empty($data->results)){
        echo "nothing found";
    } else {
        $results = $data->results;
        //echo "<pre>"; print_r($results); echo "</pre>"; die();
        echo "<table>\r\n";

        foreach ($results as $row) {
            $photoReference = $row->photos[0]->photo_reference;
            $photos= $row->photos[0]->html_attributions[0];
            $types = implode(", ", $row->types);
            $thumb = "https://maps.googleapis.com/maps/api/place/photo?photoreference=$photoReference&sensor=false&maxheight=300&maxwidth=500&key=" . GMAP_KEY;
            //print_r($row->geometry->location);
            $lat = $row->geometry->location->lat;
            $lng = $row->geometry->location->lng;

            echo "<tr>
            <td><img src=\"$thumb\" height=200 /><br>Photos: $photos</td>
            <td title=\"$row->place_id\"><b>$row->name</b><br>
            <img src=\"$row->icon\" /><br>$lat , $lng<br>
            $row->vicinity<br>
            $types
            </td>
            </tr>\r\n";


        }

        echo "</table>\r\n";
    }


  }


?>


<div id="map222"></div>


<?php include("include/_footer.php"); ?>