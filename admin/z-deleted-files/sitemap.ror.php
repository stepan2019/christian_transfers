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
			$sitemap.="	
	<item>
		<link>http://www.christiantransfers.eu/airport_transfer/".strtolower($q->f("nume_tara"))."-".$q->f("url_aeroport")."-".$q2->f("url_destinatie")."</link>
		<title>Taxi transfer ".$q->f("nume_aeroport")." to ".$q2->f("nume_destinatie")." | Private transport ".$q->f("nume_aeroport")." - ".$q2->f("nume_destinatie")." | Car hire ".$q->f("nume_aeroport")." ".$q->f("nume_tara")." - ChristianTransfers</title>  
        <description>".$q->f("nume_aeroport")." taxi transfers offer ".$q->f("nume_aeroport")." - ".$q2->f("nume_destinatie")." airport transportation, chauffer driven taxis or minivan ".$q->f("nume_aeroport")." - ".$q2->f("nume_destinatie")." and to all hotels in ".$q->f("nume_tara").", car taxi hire and hotel shuttle bus service ".$q->f("nume_aeroport")." to ".$nume_destinatie.", car minibus minivan coach hire and chauffer driven in ".$q->f("nume_aeroport")." ".$q->f("nume_tara")." with ChristianTransfers</description>
		<ror:updatePeriod>daily</ror:updatePeriod>
        <ror:sortOrder>0.9</ror:sortOrder>
        <ror:resourceOf>sitemap</ror:resourceOf>
	</item>\n";
		}
	}
	echo $sitemap;
	