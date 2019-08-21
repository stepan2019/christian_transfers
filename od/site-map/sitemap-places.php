<?php
include('../dbconfig.php'); //čćžšđ

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";


echo "<url>
<loc>https://www.christiantransfers.eu/od/stop-points/</loc>
<priority>0.2</priority>
<changefreq>monthly</changefreq>
</url>
";

foreach ($placeTypes as $type) {
    $link = URL . "/places/" . $type->name ;

echo "<url>
<loc>$link</loc>
<priority>0.5</priority>
<changefreq>daily</changefreq>
</url>
" ;

}



$rows = $db->get_results("SELECT * from tfl_places WHERE name != '' AND placeType NOT in (select name from tfl_place_types WHERE disabled='1') order by name asc ");

foreach ($rows as $row) {
    $link = URL . "/place/" . $row->code . "/" . slug($row->name) ;

echo "<url>
<loc>$link</loc>
<priority>0.5</priority>
<changefreq>daily</changefreq>
</url>
" ;
}

echo '</urlset>';

?>