<?php
include("../functions.php");
	$q=new Cdb;
	$q2=new Cdb;
	$q3=new Cdb;
	$t=new Cdb;
	$sitemap_txt="https://www.christiantransfers.eu/
https://www.christiantransfers.eu/index.php
";
	$q->query("select * from TARA t join AEROPORT a on t.id_tara=a.id_tara where a.status='1' order by t.id_tara asc");
	while ($q->next_record()){
		$id_aeroport=$q->f("id_aeroport");
		$q2->query("select * from LEGATURI l join DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_aeroport='$id_aeroport'");
		while ($q2->next_record()){
			$sitemap_txt.="https://www.christiantransfers.eu/airport_transfer/".strtolower($q->f("nume_tara"))."-".$q->f("url_aeroport")."-".$q2->f("url_destinatie")."\n";
		}
	}

	
	//echo $sitemap_txt;
	file_put_contents('../site-map/urllists.txt', $sitemap_txt);
	echo '<br /> Txt generat cu succes.';
	
	$sitemap_xml='<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	<url>
		<loc>https://www.christiantransfers.eu/</loc>
		<lastmod>2019-07-15T18:23:31+00:00</lastmod>
		<priority>1.00</priority>
		<changefreq>daily</changefreq>
	</url>
	<url>
		<loc>https://www.christiantransfers.eu/index.php</loc>
		<lastmod>2019-07-15T18:23:31+00:00</lastmod>
		<priority>0.88</priority>
		<changefreq>daily</changefreq>
	</url>
	';
	$q->query("select * from TARA t join AEROPORT a on t.id_tara=a.id_tara where a.status='1' order by t.id_tara asc");
	while ($q->next_record()){
		$id_aeroport=$q->f("id_aeroport");
		$q2->query("select * from LEGATURI l join DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_aeroport='$id_aeroport'");
		while ($q2->next_record()){
			$sitemap_xml.="	<url>
		<loc>https://www.christiantransfers.eu/airport_transfer/".strtolower($q->f("nume_tara"))."-".$q->f("url_aeroport")."-".$q2->f("url_destinatie")."</loc>
		<lastmod>2019-07-20T18:23:31+00:00</lastmod>
		<priority>0.90</priority>
		<changefreq>daily</changefreq>
	</url>\n";
		}
	}
	
	$sitemap_xml .= '</urlset>';
	
	file_put_contents('../site-map/sitemaps.xml', $sitemap_xml);
	echo '<br /> Xml generat cu succes.';
	//echo $sitemap_xml;

?>
	