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
			$sitemap.="<a href=\"http://www.christiantransfers.eu/airport_transfer/".strtolower($q->f("nume_tara"))."-".$q->f("url_aeroport")."-".$q2->f("url_destinatie")."\">
			<font color=\"#C90000\" face=\"Arial\" size=\"2\"><strong>Transfer ".$q->f("nume_tara")." ".$q->f("nume_aeroport")." ".$q2->f("nume_destinatie")."</strong></a><br>";
		}
	}
	echo $sitemap;	