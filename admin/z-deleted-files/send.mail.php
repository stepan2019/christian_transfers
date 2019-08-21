<?php
include ("../vars.inc.php");
	$q=new Cdb;
	$q2=new Cdb;
	$q3=new Cdb;
$days_until_suspend1 = 21;
$days_until_suspend2 = 14;
$days_until_suspend3 =  7;
$query="select * from UNITATE join MEMBRII where UNITATE.vip='1' and UNITATE.id_user=MEMBRII.id_user";
$q->query($query);
while ($q->next_record()){
	
	$id_user=$q->f("id_user");
	$date = $q->f("end_vip");
	$avertizare=$q->f("avertizare");
	$timeStmp1 = strtotime($date) - $days_until_suspend1 * 24 * 60 * 60; //primul email, inainte cu 3 saptamani
	$timeStmp2 = strtotime($date) - $days_until_suspend2 * 24 * 60 * 60; //al doilea email, inainte cu 2 saptamani.
	$timeStmp3 = strtotime($date) - $days_until_suspend2 * 24 * 60 * 60; //al treilea email, inainte cu 1 saptamana.
		
	$final_date1 = gmdate ('Y-m-d H:i:s', $timeStmp1);
	$final_date2 = gmdate ('Y-m-d H:i:s', $timeStmp2);
	$final_date3 = gmdate ('Y-m-d H:i:s', $timeStmp3);	
	
	$current_date = date('Y-m-d H:i:s');
	if ($current_date >= $final_date1 && $current_date <= $final_date2 && $avertizare==0){//expira in mai putin de 3 sapt,dar nu mai putin de 2 sapt
		$message1="Contul Dumneavoastra de VIP expira in 3 saptamani. Pentru a nu va pierde pozitia pe care o aveti pe site, va rugam sa efectuati plata cat mai curand.";
@mail($q->f("email"),"Contul Dumneavoastra de VIP expira in 3 saptamani!",$message1, "From: $site_name <$webmasteremail>");
	$q->query("update MEMBRII set avertizare='1' where id_user='$id_user'");	
	}
	
	if ($current_date >= $final_date2 && $current_date <= $final_date3 && $avertizare==1){ //expira in mai putin de 2 saptamani, dar nu mai putin de 1 saptamana
	$message2="Contul Dumneavoastra de VIP expira in 2 sapt. Pentru a nu va pierde pozitia pe care o aveti pe site, va rugam sa efectuati plata cat mai curand.";
	@mail($q->f("email"),"Contul Dumneavoastra de VIP expira in 2 saptamani!",$message2, "From: $site_name <$webmasteremail>");
	$q->query("update MEMBRII set avertizare='2' where id_user='$id_user'");
	}
	
	if ($current_date >= $final_date3 && $avertizare==2){ //expira in mai putin de 1 saptamana
	$message3="Contul Dumneavoastra de VIP expira peste 7 zile. Pentru a nu va pierde pozitia pe care o aveti pe site, va rugam sa efectuati plata cat mai curand.";
	@mail($q->f("email"),"Contul Dumneavoastra de VIP expira in 7 zile!",$message3, "From: $site_name <$webmasteremail>");
	$q->query("update MEMBRII set avertizare='0' where id_user='$id_user'");
	}
}

//mailuri pentru expirarea contului anual (la toate unitatile de cazare).

$query="select * from UNITATE join MEMBRII where UNITATE.id_user=MEMBRII.id_user";
$q->query($query);
while ($q->next_record()){
	
	$id_user=$q->f("id_user");
	$data_expirare = $q->f("data_inscriere");
	$avertizare=$q->f("avertizare");
	
	$days_to_add = 365;
	$timeStmp = strtotime($data_expirare) + $days_to_add * 24 * 60 * 60;
	$date = gmdate ('Y-m-d H:i:s', $timeStmp);
	
	$timeStmp1 = strtotime($date) - $days_until_suspend1 * 24 * 60 * 60; //primul email, inainte cu 3 saptamani
	$timeStmp2 = strtotime($date) - $days_until_suspend2 * 24 * 60 * 60; //al doilea email, inainte cu 2 saptamani.
	$timeStmp3 = strtotime($date) - $days_until_suspend2 * 24 * 60 * 60; //al treilea email, inainte cu 1 saptamana.
		
	$final_date1 = gmdate ('Y-m-d H:i:s', $timeStmp1);
	$final_date2 = gmdate ('Y-m-d H:i:s', $timeStmp2);
	$final_date3 = gmdate ('Y-m-d H:i:s', $timeStmp3);	
	
	$current_date = date('Y-m-d H:i:s');
	if ($current_date >= $final_date1 && $current_date <= $final_date2 && $avertizare==0){//expira in mai putin de 3 sapt,dar nu mai putin de 2 sapt
		$message1="Contul Dumneavoastra expira in 3 saptamani. Pentru a nu va pierde pozitia pe care o aveti pe site, va rugam sa efectuati plata cat mai curand.";
		@mail($q->f("email"),"Contul Dumneavoastra expira in 3 saptamani!",$message1, "From: ".$site_name." <".$webmasteremail.">");
	$q->query("update MEMBRII set avertizare='1' where id_user='$id_user'");	
	}
	
	if ($current_date >= $final_date2 && $current_date <= $final_date3 && $avertizare==1){ //expira in mai putin de 2 saptamani, dar nu mai putin de 1 saptamana
		$message2="Contul Dumneavoastra expira in 2 saptamani. Pentru a nu va pierde pozitia pe care o aveti pe site, va rugam sa efectuati plata cat mai curand.";
		@mail($q->f("email"),"Contul Dumneavoastra expira in 2 saptamani!",$message2, "From: $site_name <$webmasteremail>");
	$q->query("update MEMBRII set avertizare='2' where id_user='$id_user'");
	}
	
	if ($current_date >= $final_date3 && $avertizare==2){ //expira in mai putin de 1 saptamana
		$message3="Contul Dumneavoastra expira peste 7 zile. Pentru a nu va pierde pozitia pe care o aveti pe site, va rugam sa efectuati plata cat mai curand.";
		@mail($q->f("email"),"Contul Dumneavoastra expira in 7 zile!",$message3, "From: $site_name <$webmasteremail>");
	$q->query("update MEMBRII set avertizare='0' where id_user='$id_user'");
	}
}
?>