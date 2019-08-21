<?php
include('../dbconfig.php'); //čćžšđ

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";


echo "<url>
<loc>https://www.christiantransfers.eu/od/od/stations/</loc>
<priority>0.2</priority>
<changefreq>monthly</changefreq>
</url>
";


$rows = $db->get_results("SELECT * from tfl_stations order by name asc ");

foreach ($rows as $row) {
    $link = URL . "/station/" . $row->code . "/" . slug($row->name) ;

echo "<url>
<loc>$link</loc>
<priority>0.5</priority>
<changefreq>daily</changefreq>
</url>
" ;
}

echo '</urlset>';

?>