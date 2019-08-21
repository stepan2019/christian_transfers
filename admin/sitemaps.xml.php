<?php
include("../functions.php");
	$q=new Cdb;
	$q2=new Cdb;
	$q3=new Cdb;
	$t=new Cdb;
	$sitemap="";
	$q->query("select * from TARA t join AEROPORT a on t.id_tara=a.id_tara where a.status='1' order by t.id_tara asc");
	while ($q->next_record()){
		$id_aeroport=$q->f("id_aeroport");
		$q2->query("select * from LEGATURI l join DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_aeroport='$id_aeroport'");
		while ($q2->next_record()){
			$sitemap.="	<url>
		<loc>https://www.christiantransfers.eu/airport_transfer/".strtolower($q->f("nume_tara"))."-".$q->f("url_aeroport")."-".$q2->f("url_destinatie")."</loc>
		<lastmod>2019-06-20T18:23:31+00:00</lastmod>
		<priority>0.90</priority>
		<changefreq>daily</changefreq>
	</url>\n";
		}
	}
	echo $sitemap;
	