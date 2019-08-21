<?php
include('../dbconfig.php'); //čćžšđ

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";


echo "<url>
<loc>https://www.christiantransfers.eu/od/</loc>
<priority>0.2</priority>
<changefreq>monthly</changefreq>
</url>
";

echo "<url>
<loc>https://www.christiantransfers.eu/od/journey-planning/</loc>
<priority>1.0</priority>
<changefreq>daily</changefreq>
</url>
";

echo "<url>
<loc>https://www.christiantransfers.eu/od/timetable/</loc>
<priority>0.1</priority>
<changefreq>monthly</changefreq>
</url>
";

echo "<url>
<loc>https://www.christiantransfers.eu/od/map/</loc>
<priority>0.5</priority>
<changefreq>monthly</changefreq>
</url>
";

echo "<url>
<loc>https://www.christiantransfers.eu/od/air-quality/</loc>
<priority>0.5</priority>
<changefreq>daily</changefreq>
</url>
";


echo '</urlset>';

?>