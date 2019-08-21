<?php
include('../dbconfig.php'); //čćžšđ

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";


echo "<url>
<loc>https://www.christiantransfers.eu/od/lines/</loc>
<priority>0.2</priority>
<changefreq>monthly</changefreq>
</url>
";

$modes = $db->get_results("SELECT `modeName`,count(id) as cnt FROM `tfl_lines` group by `modeName` ");
foreach ($modes as $mode) {
    $link = URL . "/lines/" . $mode->modeName ;

echo "<url>
<loc>$link</loc>
<priority>0.5</priority>
<changefreq>daily</changefreq>
</url>
" ;
}


$rows = $db->get_results("SELECT * from tfl_lines order by name asc ");

foreach ($rows as $row) {
    $link = URL . "/line/" . $row->tid ;

echo "<url>
<loc>$link</loc>
<priority>0.5</priority>
<changefreq>daily</changefreq>
</url>
" ;
}

echo '</urlset>';

?>