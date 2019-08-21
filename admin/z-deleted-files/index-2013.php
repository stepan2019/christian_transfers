<?php
	include ("../functions.php");
	$q=new Cdb;
	$q2=new Cdb;
	$q3=new Cdb;
	$t=new Cdb;
	$serverpath=$_SERVER['DOCUMENT_ROOT'];
	$sitename="http://".$_SERVER['SERVER_NAME'];
	$sitename_title="Admin Transfer Aeroport";
	$current_date = date('Y-m-d H:i:s');

	if (!isset($_GET["action"])) $_GET["action"]="tari";
		
	switch ($_GET["action"])
	{
		case "tari":
			FFileRead("template.tari.html",$content);
			$query="select * from TARA";
			if ($_GET["order"]!="") $query.=" order by ".$_GET["order"];
			else $query.=" order by id_tara";
			if ($_GET["rule"]!="") $query.=" ".$_GET["rule"]; else $query.=" asc";
			$q->query($query);

			$rows="";
			$i=0;
			while ($q->next_record()){
				$i++;
				if ($q->f("status")==1){
					$status="Activ";$bg_status="";
				}else{
					$status="Inactiv";
					$bg_status="bgcolor=\"000000\"";
				}
			$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=edit_tara&id_tara=".$q->f("id_tara")."'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv&id_tara=".$q->f("id_tara")."'>".$q->f("id_tara")."&nbsp;$status</a></td>
	<td>".$q->f("nume_tara")."</td>
	<td><input type=checkbox name=check[".$q->f("id_tara")."]></td>
	</tr>";}
			$query="select * from TARA where id_tara>0 and status='1'";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);
		break;
		case "add_tara":
			FFileRead("template.add_tara.html",$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_add_tara":
			$q->query("select * from TARA where nume_tara='".$_POST["tara"]."'");
			if ($q->next_record()){
				$err="Aceasta tara exista deja in lista";
			}
			elseif ($_POST["tara"]!=""){
				$q->query("insert into TARA set nume_tara='".$_POST["tara"]."', status='1'");
				$err="Tara adaugata cu succes";
			}
			header("Location:index.php?action=add_tara&err=$err");
		break;
		case "edit_tara":
			FFileRead("template.edit_tara.html",$content);
			$q->query("select * from TARA where id_tara='".$_GET["id_tara"]."'");
			$q->next_record();
			$content=str_replace("{nume_tara}",$q->f("nume_tara"),$content);
			$content=str_replace("{id_tara}",$q->f("id_tara"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_tara":
				$q->query("update TARA set nume_tara='".$_POST["tara"]."', status='1' where id_tara='".$_POST["id_tara"]."'");
				$err="Tara modificata cu succes";
			
			header("Location:index.php?action=edit_tara&err=$err");
		break;
		case "delete_tara":
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$query="select id_aeroport from AEROPORT where id_tara='$x'";
					$q->query($query);
					while ($q->next_record()){
						$id_aeroport=$q->f("id_aeroport");
						$q->query("delete from LEGATURI where id_aeroport='$id_aeroport'");
					}
					$query="delete from TARA where id_tara='$x'";
					$q->query($query);
					$query="delete from AEROPORT where id_tara='$x'";
					$q->query($query);
				}
			}
			header("Location:index.php?action=tari");
		break;
		//aeroporturi si orase
		case "ao":
			FFileRead("template.ao.html",$content);
			$query="select * from AEROPORT";
			if ($_GET["order"]!="") $query.=" order by ".$_GET["order"];
			else $query.=" order by id_aeroport";
			if ($_GET["rule"]!="") $query.=" ".$_GET["rule"]; else $query.=" asc";
			$q->query($query);
			
			$rows="";
			$i=0;
			while ($q->next_record()){
				$i++;
				if ($q->f("status")==1){
					$status="Activ";$bg_status="";
				}else{
					$status="Inactiv";
					$bg_status="bgcolor=\"000000\"";
				}
			$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
	<td align=center><a href='index.php?action=edit_ao&id_aeroport=".$q->f("id_aeroport")."'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_ao&id_aeroport=".$q->f("id_aeroport")."'>".$q->f("id_aeroport")."&nbsp;$status</a></td>
	<td>".$q->f("label")."</td><td>".$q->f("nume_aeroport")."</td><td>".$q->f("url_aeroport")."</td>	
	<td><input type=checkbox name=check[".$q->f("id_aeroport")."]></td>
	</tr>";}
			$query="select * from AEROPORT where id_aeroport>0 and status='1'";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);
		break;
		case "add_ao":
			FFileRead("template.add_ao.html",$content);
			$q->query("select * from TARA order by nume_tara asc");
			$tari="";
			while ($q->next_record())
				$tari.="<option value=\"".$q->f("id_tara")."\">".$q->f("nume_tara")."</option>";
			$content=str_replace("{tari}",$tari,$content);
			$q->query("select distinct label from DESTINATIE order by label asc");
			$label="";
			while ($q->next_record())
				$label.="<option value=\"".$q->f("label")."\">".$q->f("label")."</option>";
			$content=str_replace("{label}",$label,$content);			
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_add_ao":
			$label=($_POST["label"]!="")?$_POST["label"]:$_POST["labelnou"];
			if ($label=="") $err="Labelul nu poate lipsi";
			else{
				$q->query("select * from AEROPORT where nume_aeroport='".$_POST["nume_aeroport"]."' and id_tara='".$_POST["id_tara"]."'");
				if ($q->next_record()){
					$err="Acest aeroport / oras exista deja in lista";
				}
				elseif ($_POST["nume_aeroport"]=="" || $_POST["url_aeroport"]=="")
					$err="Nume aeroport si url aeroport nu pot lipsi.";
				else{
					$q->query("insert into AEROPORT set id_tara='".$_POST["tara"]."',label='".$_POST["label"]."',nume_aeroport='".$_POST["nume_aeroport"]."', url_aeroport='".$_POST["url_aeroport"]."', status='1'");
					$err="Aeroport / Oras adaugat cu succes";
				}
			}
			header("Location:index.php?action=add_ao&err=$err");
		break;
		case "edit_ao":
			FFileRead("template.edit_ao.html",$content);
			$q->query("select * from AEROPORT where id_aeroport='".$_GET["id_aeroport"]."'");
			$q->next_record();
			$q2->query("select * from TARA order by nume_tara asc");			
			while ($q2->next_record()){
				if ($q->f("id_tara")==$q2->f("id_tara")) $selected="selected";
					else $selected="";			
				$tari.="<option $selected value=\"".$q2->f("id_tara")."\">".$q2->f("nume_tara")."</option>";
			}
			$content=str_replace("{tari}",$tari,$content);
			$content=str_replace("{label}",$q->f("label"),$content);
			$content=str_replace("{nume_aeroport}",$q->f("nume_aeroport"),$content);
			$content=str_replace("{url_aeroport}",$q->f("url_aeroport"),$content);
			$content=str_replace("{id_aeroport}",$q->f("id_aeroport"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_ao":
			$q->query("select * from AEROPORT where nume_aeroport='".$_POST["nume_aeroport"]."' and id_tara='".$_POST["id_tara"]."'");
			if ($q->nf()>1){
				$err="Acest aeroport / oras exista deja in lista";
			}
			elseif ($_POST["nume_aeroport"]=="" || $_POST["url_aeroport"]=="" || $_POST["label"]=="")
				$err="Label, nume aeroport si url aeroport nu pot lipsi.";
			else{
				$q->query("update AEROPORT set id_tara='".$_POST["tara"]."',label='".$_POST["label"]."',nume_aeroport='".$_POST["nume_aeroport"]."', url_aeroport='".$_POST["url_aeroport"]."', status='1' where id_aeroport='".$_POST["id_aeroport"]."'");
				$err="Aeroport / Oras modificat cu succes";
			}
			header("Location:index.php?action=add_ao&err=$err");
		break;
		case "delete_ao":
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from LEGATURI where id_aeroport='$x'");					
					$query="delete from AEROPORT where id_aeroport='$x'";
					$q->query($query);
				}
			}
			header("Location:index.php?action=ao");
		break;
		//statii
		case "statii":
			FFileRead("template.statii.html",$content);
			$query="select * from STATII";
			if ($_GET["order"]!="") $query.=" order by ".$_GET["order"];
			else $query.=" order by id_statie";
			if ($_GET["rule"]!="") $query.=" ".$_GET["rule"]; else $query.=" asc";
			$q->query($query);
			
			$rows="";
			$i=0;
			while ($q->next_record()){
				$i++;
				if ($q->f("status")==1){
					$status="Activ";$bg_status="";
				}else{
					$status="Inactiv";
					$bg_status="bgcolor=\"000000\"";
				}
			$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
	<td align=center><a href='index.php?action=edit_statii&id_statie=".$q->f("id_statie")."'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_statii&id_statie=".$q->f("id_statie")."'>".$q->f("id_statie")."&nbsp;$status</a></td>
	<td>".$q->f("nume_statie")."</td>	
	<td><input type=checkbox name=check[".$q->f("id_statie")."]></td>
	</tr>";}
			$query="select * from STATII where id_statie>0 and status='1'";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);
		break;
		case "add_statii":
			FFileRead("template.add_statii.html",$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_add_statii":
				$q->query("select * from STATII where nume_statie='".$_POST["nume_statie"]."'");
				if ($q->next_record()){
					$err="Aceasta statie exista deja in lista";
				}
				elseif ($_POST["nume_statie"]=="")
					$err="Nume statie nu poate lipsi.";
				else{
					$q->query("insert into STATII set nume_statie='".$_POST["nume_statie"]."', status='1'");
					$err="Statie adaugata cu succes";
				}

			header("Location:index.php?action=add_statii&err=$err");
		break;
		case "edit_statii":
			FFileRead("template.edit_statii.html",$content);
			$q->query("select * from STATII where id_statie='".$_GET["id_statie"]."'");
			$q->next_record();
			
			$content=str_replace("{nume_statie}",$q->f("nume_statie"),$content);
			$content=str_replace("{id_statie}",$q->f("id_statie"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_statii":
			$q->query("select * from STATII where nume_statie='".$_POST["nume_statie"]."'");
			if ($q->nf()>1){
				$err="Aceasta statie exista deja in lista";
			}
			elseif ($_POST["nume_statie"]=="")
				$err="Nume statie nu poate lipsi.";
			else{
				$q->query("update STATII set nume_statie='".$_POST["nume_statie"]."', status='1' where id_statie='".$_POST["id_statie"]."'");
				$err="Statie modificata cu succes";
			}
			header("Location:index.php?action=add_statii&err=$err");
		break;
		case "delete_statii":
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from LEGATURI_STATII where id_statie_pornire='$x' OR id_statie_sosire='$x'");					
					$query="delete from STATII where id_statie='$x'";
					$q->query($query);
				}
			}
			header("Location:index.php?action=statii");
		break;
		//destinatie
		case "destinatie":
			FFileRead("template.destinatie.html",$content);
			$query="select * from DESTINATIE";
			if ($_GET["order"]!="") $query.=" order by ".$_GET["order"];
			else $query.=" order by id_destinatie";
			if ($_GET["rule"]!="") $query.=" ".$_GET["rule"]; else $query.=" asc";
			$q->query($query);
			
			$rows="";
			$i=0;
			while ($q->next_record()){
				$i++;
				if ($q->f("status")==1){
					$status="Activ";$bg_status="";
				}else{
					$status="Inactiv";
					$bg_status="bgcolor=\"000000\"";
				}			
			$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=edit_destinatie&id_destinatie=".$q->f("id_destinatie")."'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_destinatie&id_destinatie=".$q->f("id_destinatie")."'>".$q->f("id_destinatie")."&nbsp;$status</a></td>
	
	<td>".$q->f("label")."</td><td>".$q->f("nume_destinatie")."</td><td>".$q->f("url_destinatie")."</td>	
    <td><input type=checkbox name=check[".$q->f("id_destinatie")."]></td>
	</tr>";}
			$query="select * from DESTINATIE where id_destinatie>0 and status='1'";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);
		break;
		case "add_destinatie":
			FFileRead("template.add_destinatie.html",$content);
			$q->query("select distinct label from DESTINATIE order by label asc");
			$label="";
			while ($q->next_record())
				$label.="<option value=\"".$q->f("label")."\">".$q->f("label")."</option>";
			$content=str_replace("{label}",$label,$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_add_destinatie":
			$label=($_POST["label"]!="")?$_POST["label"]:$_POST["labelnou"];
			if ($label=="") $err="Labelul nu poate lipsi";
			else{			
				$q->query("select * from DESTINATIE where nume_destinatie='".$_POST["nume_destinatie"]."'");
				if ($q->next_record()){
					$err="Aceasta destinatie exista deja in lista";
				}
				elseif ($_POST["nume_destinatie"]=="" || $_POST["url_destinatie"]=="")
					$err="Nume destinatie si url destinatie nu pot lipsi.";
				else{
					$q->query("insert into DESTINATIE set label='".$label."',nume_destinatie='".$_POST["nume_destinatie"]."', url_destinatie='".$_POST["url_destinatie"]."', status='1'");
					$err="Destinatie adaugata cu succes";
			}
			}
			header("Location:index.php?action=add_destinatie&err=$err");
		break;
		case "edit_destinatie":
			FFileRead("template.edit_destinatie.html",$content);
			$q->query("select * from DESTINATIE where id_destinatie='".$_GET["id_destinatie"]."'");
			$q->next_record();
			
			$content=str_replace("{label}",$q->f("label"),$content);
			$content=str_replace("{nume_destinatie}",$q->f("nume_destinatie"),$content);
			$content=str_replace("{extra_info}",$q->f("extra_info"),$content);
			$content=str_replace("{url_destinatie}",$q->f("url_destinatie"),$content);
			$content=str_replace("{id_destinatie}",$q->f("id_destinatie"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_destinatie":			
			if ($_POST["label"]=="") $err="Labelul nu poate lipsi";
			else{			
				if ($_POST["nume_destinatie"]=="" || $_POST["url_destinatie"]=="")
					$err="Nume destinatie si url destinatie nu pot lipsi.";
				else{
					$q->query("update DESTINATIE set label='".$_POST["label"]."',nume_destinatie='".$_POST["nume_destinatie"]."', url_destinatie='".$_POST["url_destinatie"]."', extra_info='".$_POST["extra_info"]."', status='1' where id_destinatie='".$_POST["id_destinatie"]."'");
					$err="Destinatie modificata cu succes";
			}
			}
			header("Location:index.php?action=destinatie&err=$err");
		break;
		case "delete_destinatie":
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from LEGATURI where id_destinatie='$x'");					
					$query="delete from DESTINATIE where id_destinatie='$x'";
					$q->query($query);
				}
			}
			header("Location:index.php?action=destinatie");
		break;
		case "legaturi":
		$title=$sitename_title." Legaturi";
		FFileRead("template.legaturi.html",$content);
		$q->query("select id_aeroport,nume_aeroport from AEROPORT where status='1'");
		$aeroporturi="";
		while ($q->next_record())
			$aeroporturi.="<option value=\"".$q->f("id_aeroport")."\">".$q->f("nume_aeroport")."</option>";
		
			$content=str_replace("{aeroporturi}",$aeroporturi,$content);
		if ($_POST["cauta"]!="" || $_GET["cauta"]!=""){
		$id_aeroport=(isset($_POST["cauta"]))?$_POST["cauta"]:$_GET["cauta"];
		FFileRead("template.legaturi2.html",$content2);
			$q->query("select nume_aeroport from AEROPORT where id_aeroport='$id_aeroport'");
			$q->next_record();
			$nume_aeroport=$q->f("nume_aeroport");
			$content.=$content2;
			$content=str_replace("{error}",$_GET["err"],$content);
			if ($_GET["cauta"]!="") 
$query="select * from LEGATURI l join AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_aeroport='".$_GET["cauta"]."'";
			else {
			if ($_POST["cauta"]!="")
$query="select * from LEGATURI l join AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_aeroport='".$_POST["cauta"]."'";
			}
			if ($_GET["order1"]) $query.=" order by ".$_GET["order1"];
			else $query.=" order by nume_destinatie";
			if ($_GET["rule1"]) $query.=" ".$_GET["rule1"];
			else $query.=" asc";
$q->query($query);
$rows="";
$legaturi_existente=array();
$i=0;
while ($q->next_record()) {
$i++;
	$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
			<td align=center><a href='index.php?action=edit_legaturi&id_legaturi=".$q->f("id_legaturi")."'>Edit</a></td>
			<td align=center><a href='index.php?action=preturi&id_legaturi=".$q->f("id_legaturi")."'>Preturi</a></td>
			<td>".$q->f("nume_aeroport")."</td><td>".$q->f("nume_destinatie")."</td><td>".$q->f("km")."</td>
			<td>".$q->f("timp")."</td><td>".$q->f("titlu_pagina")."</td><td>".$q->f("meta_pagina")."</td>
			<td><input type=checkbox name=check[".$q->f("id_legaturi")."]></td></tr>";
	$legaturi_existente[]=$q->f("id_destinatie");
}
$query="select * from DESTINATIE";
if ($_GET["order2"]) $query.=" order by ".$_GET["order2"];
else $query.=" order by nume_destinatie";

if ($_GET["rule2"]) $query.=" ".$_GET["rule2"];
else $query.=" asc";
$q2->query($query);
$rows2="";
$j=0;
while ($q2->next_record()){
	$j++;
	if (!in_array($q2->f("id_destinatie"),$legaturi_existente)){
		$rows2.="<tr id=\"celll$j\" onMouseOver=\"document.all.celll$j.bgColor = '#0099ff'\" onMouseOut=\"document.all.celll$j.bgColor ='#ffffff'\">
		<td>".$q2->f("nume_destinatie")."</td><td><input type=checkbox name=check[".$q2->f("id_destinatie")."]></td></tr>";
	}
}
$content=str_replace("{nume_aeroport}",$nume_aeroport,$content);
$content=str_replace("{id_aeroport}",$id_aeroport,$content);
$content=str_replace("{rows}",$rows,$content);
$content=str_replace("{rows2}",$rows2,$content);
		}
		break;
		case "do_add_legaturi":
			foreach ($_POST["check"] as $z => $value)
			{
				if ($z>0)
					$q->query("insert into LEGATURI set id_aeroport='".$_POST["id_aeroport"]."',id_destinatie='$z'");
			}
			header("Location:index.php?action=legaturi&cauta=".$_POST["id_aeroport"]);
		break;
		case "edit_legaturi":
			FFileRead("template.edit_legaturi.html",$content);			
			$title=$sitename_title." - Editeaza legatura";
			$q->query("select * from LEGATURI l join AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_legaturi='".$_GET["id_legaturi"]."'");
			$q->next_record();
			
			$content=str_replace("{nume_aeroport}",$q->f("nume_aeroport"),$content);
			$content=str_replace("{nume_destinatie}",$q->f("nume_destinatie"),$content);
			$content=str_replace("{km}",$q->f("km"),$content);
			$content=str_replace("{timp}",$q->f("timp"),$content);
			$content=str_replace("{titlu_pagina}",$q->f("titlu_pagina"),$content);
			$content=str_replace("{meta_pagina}",$q->f("meta_pagina"),$content);
			$content=str_replace("{id_legaturi}",$q->f("id_legaturi"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_legaturi":
			$q->query("update LEGATURI set km='".$_POST["km"]."',timp='".$_POST["timp"]."', titlu_pagina='".$_POST["titlu_pagina"]."', meta_pagina='".$_POST["meta_pagina"]."' where id_legaturi='".$_POST["id_legaturi"]."'");
			$err="Date legatura modificate cu succes";

			header("Location:index.php?action=edit_legaturi&err=$err");
		break;
		case "delete_legaturi":
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from LEGATURI where id_legaturi='$x'");
					$q->query($query);
				}
			}
			header("Location:index.php?action=legaturi&cauta=".$_POST["id_aeroport"]);
		break;
		case "preturi":
			$title=$sitename_title." Preturi";
		FFileRead("template.preturi.html",$content);

		$id_legaturi=$_REQUEST["id_legaturi"];
			$q->query("select a.id_aeroport, a.nume_aeroport,d.nume_destinatie from LEGATURI l JOIN AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d ON l.id_destinatie=d.id_destinatie where id_legaturi='$id_legaturi'");
			$q->next_record();
			$content=str_replace("{nume_aeroport}",$q->f("nume_aeroport"),$content);
			$content=str_replace("{id_aeroport}",$q->f("id_aeroport"),$content);
			$content=str_replace("{nume_destinatie}",$q->f("nume_destinatie"),$content);	
			$q->query("select * from PRETURI where id_legaturi='$id_legaturi'");
			
			$content=str_replace("{error}",$_GET["err"],$content);			
$rows="";
$preturi_existente=array();
$i=0;
$shuttle_exists=0;
while ($q->next_record()) {
	if ($q->f("id_auto")!=0){
	$q2->query("select nume_auto from AUTO where id_auto='".$q->f("id_auto")."'");
	$q2->next_record();
	$nume_auto=$q2->f("nume_auto");
	}
	else {
		$shuttle_exists=1;
		$nume_auto="Shuttle";
		$preturi_existente[]=0;
	}
$i++;
	$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
			<td align=center><a href='index.php?action=edit_preturi&id_legaturi=".$q->f("id_legaturi")."&id_auto=".$q->f("id_auto")."'>Edit</a></td>
			<td>".$nume_auto."</td><td>".(($nume_auto=="Shuttle")?'VEZI TABEL STATII':$q->f("pret"))."</td><td><input type=checkbox name=check[".$q->f("id_auto")."]></td></tr>";
	$preturi_existente[]=$q->f("id_auto");
}
$q2->query("select * from AUTO order by id_auto asc");
$rows2="";
$j=0;
while ($q2->next_record()){
	$j++;
	if (!in_array($q2->f("id_auto"),$preturi_existente)){
		$rows2.="<tr id=\"celll$j\" onMouseOver=\"document.all.celll$j.bgColor = '#0099ff'\" onMouseOut=\"document.all.celll$j.bgColor ='#ffffff'\">
		<td>".$q2->f("nume_auto")."</td><td><input type=checkbox name=check[".$q2->f("id_auto")."]></td></tr>";
	}
}
if ($shuttle_exists==0) {//daca nu exista shuttle pt. destinatia respectiva, sa se poata selecta shuttle
		$rows2.="<tr id=\"celll$j\" onMouseOver=\"document.all.celll$j.bgColor = '#0099ff'\" onMouseOut=\"document.all.celll$j.bgColor ='#ffffff'\">
		<td>Shuttle</td><td><input type=checkbox name=check[0]></td></tr>";
	}
//$content=str_replace("{nume_aeroport}",$nume_aeroport,$content);
$content=str_replace("{id_legaturi}",$id_legaturi,$content);
$content=str_replace("{rows}",$rows,$content);
$content=str_replace("{rows2}",$rows2,$content);
		break;

		case "do_add_preturi":
			foreach ($_POST["check"] as $z => $value)
			{
					$query="insert into PRETURI set id_legaturi='".$_POST["id_legaturi"]."',id_auto='$z'";
					//echo $query."<br>";
					$q->query($query);
			}
			header("Location:index.php?action=preturi&id_legaturi=".$_POST["id_legaturi"]);
		break;
		//legaturi statii
		case "add_legaturi_statii":
			FFileRead("template.add_legaturi_statii.html",$content);
			$q->query("select * from STATII order by nume_statie asc");
			$statii="";
			while ($q->next_record())
				$statii.="<option value=\"".$q->f("id_statie")."\">".$q->f("nume_statie")."</option>";
			$content=str_replace("{statii}",$statii,$content);
			$content=str_replace("{id_legaturi}",$_GET["id_legaturi"],$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_add_legaturi_statii":
				/*$q->query("select * from LEGATURI_STATII where id_statie_pornire='".$_POST["statie_pornire"]."' and id_statie_sosire='".$_POST["statie_sosire"]."'");
				if ($q->next_record()){
					$err="Aceasta legatura intre statii exista deja in lista";
				}
				else*/if ($_POST["statie_pornire"]=="" || $_POST["statie_sosire"]=="")
					$err="Statia de pornire si statia de sosire nu pot lipsi.";
				else{
					$q->query("insert into LEGATURI_STATII set id_statie_pornire='".$_POST["statie_pornire"]."', id_statie_sosire='".$_POST["statie_sosire"]."', descriere_pornire='".$_POST["descriere_pornire"]."', descriere_sosire='".$_POST["descriere_sosire"]."', ora_pornire='".$_POST["ora_pornire"].":00',ora_sosire='".$_POST["ora_sosire"].":00',pret='".$_POST["pret"]."',  id_legaturi='".$_POST["id_legaturi"]."', status='1'");
					$err="Legatura intre statii adaugata cu succes";
				}

			header("Location:index.php?action=add_legaturi_statii&err=$err");
		break;
		case "edit_legaturi_statii":
			FFileRead("template.edit_legaturi_statii.html",$content);
			$q->query("select * from STATII order by nume_statie asc");
			$statii="";
			while ($q->next_record())
				$statii.="<option value=\"".$q->f("id_statie")."\">".$q->f("nume_statie")."</option>";
			$content=str_replace("{statii}",$statii,$content);
			
			$q->query("select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII ls JOIN STATII s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legatura_statie='".$_GET["id_legatura_statie"]."'");
			$q->next_record();
			
			$content=str_replace("{id_statie_pornire}",$q->f("id_statie_pornire"),$content);
			$content=str_replace("{statie_pornire}",$q->f("statie_pornire"),$content);
			$content=str_replace("{ora_pornire}",substr($q->f("ora_pornire"),0,-3),$content);
			$content=str_replace("{descriere_pornire}",$q->f("descriere_pornire"),$content);
			$content=str_replace("{id_statie_sosire}",$q->f("id_statie_sosire"),$content);
			$content=str_replace("{statie_sosire}",$q->f("statie_sosire"),$content);
			$content=str_replace("{ora_sosire}",substr($q->f("ora_sosire"),0,-3),$content);
			$content=str_replace("{descriere_sosire}",$q->f("descriere_sosire"),$content);
			$content=str_replace("{id_legatura_statie}",$q->f("id_legatura_statie"),$content);
			$content=str_replace("{pret}",$q->f("pret"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_legaturi_statii":
			/*$q->query("select * from LEGATURI_STATII where id_statie_pornire='".$_POST["id_statie_pornire"]."' and id_statie_sosire='".$_POST["id_statie_sosire"]."'");
			if ($q->nf()>1){
				$err="Aceasta statie exista deja in lista";
			}else{*/
				$q->query("update LEGATURI_STATII set id_statie_pornire='".$_POST["id_statie_pornire"]."', id_statie_sosire='".$_POST["id_statie_sosire"]."', descriere_pornire='".$_POST["descriere_pornire"]."', descriere_sosire='".$_POST["descriere_sosire"]."', ora_pornire='".$_POST["ora_pornire"].":00',ora_sosire='".$_POST["ora_sosire"].":00',pret='".$_POST["pret"]."' where id_legatura_statie='".$_POST["id_legatura_statie"]."'");
				$err="Legatura statie modificata cu succes";
			//}
			header("Location:index.php?action=edit_legaturi_statii&id_legatura_statie=".$_POST["id_legatura_statie"]."&err=$err");
		break;
		case "delete_legaturi_statii":
			if(isset($_POST["check"])){
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from LEGATURI_STATII where id_legatura_statie='$x'");
				}
			}
			}
			if(isset($_POST["checkretur"])){
			foreach ($_POST["checkretur"] as $y => $value)
			{	
				if ($y>0)
				{
					$q->query("update LEGATURI_STATII set retur=NOT retur where id_legatura_statie='$y'");
				}
			}
			}
			header("Location:index.php?action=edit_preturi&id_auto=0&id_legaturi=".$_POST["id_legaturi"]);
		break;		
		
		//preturi pentru diferite legaturi
		case "edit_preturi":
			if ($_GET["id_auto"]==0) {//shuttle
				FFileRead("template.legaturi_statii.html",$content);
				$query="select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII ls JOIN STATII s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legaturi='".$_GET["id_legaturi"]."'";
			$query.="order by retur asc,";
			if ($_GET["order"]!="") $query.=" ".$_GET["order"];
			else $query.=" ls.id_legatura_statie";
			if ($_GET["rule"]!="") $query.=" ".$_GET["rule"]; else $query.=" asc";
			$q->query($query);
			
			$rows="";
			$i=0;
			while ($q->next_record()){
				$i++;
				if ($q->f("status")==1){
					$status="Activ";$bg_status="";
				}else{
					$status="Inactiv";
					$bg_status="bgcolor=\"000000\"";
				}
				if ($q->f("retur")==1){
					$status_retur="Da";$bg_status_retur="bgcolor=\"949494\"";
				}else{
					$status_retur="";
					$bg_status_retur="";
				}
			$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
	<td align=center><a href='index.php?action=edit_legaturi_statii&id_legatura_statie=".$q->f("id_legatura_statie")."'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_legaturi_statii&id_legatura_statie=".$q->f("id_legatura_statie")."&id_legaturi=".$_GET["id_legaturi"]."'>".$q->f("id_legatura_statie")."&nbsp;$status</a></td>
	<td>".$q->f("ora_pornire")."</td><td>".$q->f("statie_pornire")."</td><td>".$q->f("descriere_pornire")."</td>
	<td>".$q->f("ora_sosire")."</td><td>".$q->f("statie_sosire")."</td><td>".$q->f("descriere_sosire")."</td>
	<td>".$q->f("pret")."</td>
	<td $bg_status_retur>$status_retur <input type=checkbox name=checkretur[".$q->f("id_legatura_statie")."]></td>
	<td><input type=checkbox name=check[".$q->f("id_legatura_statie")."]></td>
	</tr>";}
			$query="select * from STATII where id_statie>0 and status='1'";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);
			$content=str_replace("{id_legaturi}",$_GET["id_legaturi"],$content);
			
				$query="select * from LEGATURI_STATII where id_legaturi='".$_GET["id_legaturi"]."'";
				$content=str_replace("{nume_auto}","Shuttle",$content);
				$q->query($query);
				$q->next_record();				
			}else{
				FFileRead("template.edit_preturi.html",$content);
				$query="select * from PRETURI p JOIN AUTO a ON p.id_auto=a.id_auto where a.id_auto='".$_GET["id_auto"]."' AND p.id_legaturi='".$_GET["id_legaturi"]."'";
				$q->query($query);
				$q->next_record();
				$content=str_replace("{nume_auto}",$q->f("nume_auto"),$content);
				$content=str_replace("{pret}",$q->f("pret"),$content);
			}
			
			$content=str_replace("{id_auto}",$q->f("id_auto"),$content);
			$content=str_replace("{id_legaturi}",$q->f("id_legaturi"),$content);
			
			$q->query("select a.nume_aeroport,d.nume_destinatie from LEGATURI l JOIN AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d ON l.id_destinatie=d.id_destinatie where id_legaturi='".$_GET["id_legaturi"]."'");
			$q->next_record();
			$content=str_replace("{nume_aeroport}",$q->f("nume_aeroport"),$content);
			$content=str_replace("{nume_destinatie}",$q->f("nume_destinatie"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_preturi":
			$q->query("update PRETURI set pret='".$_POST["pret"]."' where id_legaturi='".$_POST["id_legaturi"]."' and id_auto='".$_POST["id_auto"]."'");
			$err="Date legatura modificate cu succes";

			header("Location:index.php?action=preturi&id_legaturi=".$_POST["id_legaturi"]."&err=$err");
		break;
		case "delete_preturi":
			if (isset($_POST["check"])){
				foreach ($_POST["check"] as $x => $value)
				{					
						$q->query("delete from PRETURI where id_auto='$x' and id_legaturi='".$_POST["id_legaturi"]."'");
						$q->query($query);
					
				}
			}
			header("Location:index.php?action=preturi&id_legaturi=".$_POST["id_legaturi"]);
		break;
		case "delete_legaturi":
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from LEGATURI where id_legaturi='$x'");
					$q->query($query);
				}
			}
			header("Location:index.php?action=legaturi&id_legaturi=".$_POST["id_legaturi"]);
		break;
		case "auto":
			FFileRead("template.auto.html",$content);
			$query="select * from AUTO";
			if ($_GET["order"]!="") $query.=" order by ".$_GET["order"];
			else $query.=" order by id_auto";
			if ($_GET["rule"]!="") $query.=" ".$_GET["rule"]; else $query.=" asc";
			$q->query($query);

			$rows="";
			$i=0;
			while ($q->next_record()){
				$i++;
				if ($q->f("status")==1){
					$status="Activ";$bg_status="";
				}else{
					$status="Inactiv";
					$bg_status="bgcolor=\"000000\"";
				}
			$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=edit_auto&id_auto=".$q->f("id_auto")."'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_auto&id_auto=".$q->f("id_auto")."'>".$q->f("id_auto")."&nbsp;$status</a></td>
	<td>".$q->f("nume_auto")."</td><td>".$q->f("nr_pasageri")."</td>
	<td><input type=checkbox name=check[".$q->f("id_auto")."]></td>
	</tr>";}
			$query="select * from AUTO where id_auto>0 and status='1'";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);		
		break;
		case "add_auto":
			FFileRead("template.add_auto.html",$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_add_auto":
			$q->query("select * from AUTO where nume_auto='".$_POST["nume_auto"]."'");
			if ($q->next_record()){
				$err="Acest auto exista deja in lista";
			}
			elseif ($_POST["nume_auto"]!=""){
				$file_dir = $serverpath."/images/auto/";
				$query="select id_auto from AUTO order by id_auto desc limit 0,1";
				$q->query($query);
				$q->next_record();
				$ii=$q->f("id_auto");
				$ii++;
				if (isset($_FILES["poza1"]) && $_FILES["poza1"]['size']>1)
					{
					    if (trim($_FILES["poza1"]['name'])!="") {  
					      $newfile = $file_dir.$ii."_01.jpg";
						  move_uploaded_file($_FILES["poza1"]['tmp_name'], $newfile);  
						  $j=1;
						if (isset($j)&&$j==1){$poza1=", poza1='/images/auto/".$ii."_01.jpg'";}
					 	} else {
					 		$poza1="";
							$err="Sorry, there was a problem uploading your picture.";							
						}
					$pics=$_SERVER['DOCUMENT_ROOT']."/images/auto/".$ii."_01.jpg";
					createthumb($pics,substr($pics,0,-4)."_tn.jpg",124,93);
					$pics=ditchtn($pics,"tnn_");	
					createnormal($pics,$pics,580,435);
					}
				$q->query("insert into AUTO set nume_auto='".$_POST["nume_auto"]."',nr_pasageri='".$_POST["nr_pasageri"]."' $poza1, status='1'");
				$err="Auto adaugat cu succes";
			}
			header("Location:index.php?action=add_auto&err=$err");
		break;
		case "edit_auto":
			FFileRead("template.edit_auto.html",$content);
			$q->query("select * from AUTO where id_auto='".$_GET["id_auto"]."'");
			$q->next_record();
			$content=str_replace("{nume_auto}",$q->f("nume_auto"),$content);
			$content=str_replace("{nr_pasageri}",$q->f("nr_pasageri"),$content);
			$poza1=($q->f("poza1")!="")?"<img src=\"/images/auto/".$q->f("id_auto")."_01_tn.jpg\"><br>":"";
			$content=str_replace("{poza1}",$poza1,$content);
			$content=str_replace("{id_auto}",$q->f("id_auto"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_auto":
			if ($_POST["nume_auto"]=="" || $_POST["nr_pasageri"]=="")
				$err="Nume auto si nr. pasageri nu pot lipsi.";
			else{
				$file_dir = $serverpath."/images/auto/";
				if (isset($_FILES["poza1"]) && $_FILES["poza1"]['size']>1)
					{
					    if (trim($_FILES["poza1"]['name'])!="") {  
					      $newfile = $file_dir.$_POST["id_auto"]."_01.jpg";
						  move_uploaded_file($_FILES["poza1"]['tmp_name'], $newfile);  
						  $j=1;
					 	}
					    if (isset($j)&&$j==1){$poza1=", poza1='/images/auto/".$_POST["id_auto"]."_01.jpg'";}
						else {
							if ($q->f("poza1")=="") $poza1="";
							$err="Sorry, there was a problem uploading your picture.";
						}
					$pics=$_SERVER['DOCUMENT_ROOT']."/images/auto/".$_POST["id_auto"]."_01.jpg";
					createthumb($pics,substr($pics,0,-4)."_tn.jpg",124,93);

					$pics=ditchtn($pics,"tnn_");	
					createnormal($pics,$pics,580,435);
					}
					$q->query("update AUTO set nume_auto='".$_POST["nume_auto"]."',nr_pasageri='".$_POST["nr_pasageri"]."' $poza1, status='1' where id_auto='".$_POST["id_auto"]."'");
					$err="Auto modificat cu succes";
				}
			header("Location:index.php?action=edit_auto&err=$err");
		break;
		case "delete_auto":
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from AUTO where id_auto='$x'");					
					$query="delete from PRETURI where id_auto='$x'";
					$q->query($query);
				}
			}
			header("Location:index.php?action=auto");
		break;
		case "comenzi":
			FFileRead("template.comenzi.html",$content);
			$title=$sitename_title." - Comenzi";
			$content=str_replace("{order}",(isset($_GET["order"])?$_GET["order"]:"id_order"),$content);
			$content=str_replace("{rule}",(isset($_GET["rule"])?$_GET["rule"]:"desc"),$content);
			$liste='<option value=""></option>';
			$q->query("select * from LISTE order by nume_lista asc");
			while ($q->next_record()){
				$liste.='<option value="'.$q->f("id_lista").'">'.$q->f("nume_lista").'</option>';
			}
			
			$query="select * from ORDERS";
			if ($_GET["order"]!="") $query.=" order by ".$_GET["order"];
			else $query.=" order by id_order";
			if ($_GET["rule"]!="") $query.=" ".$_GET["rule"]; else $query.=" desc";			
			$q3->query($query);
			$pages=round($q3->nf()/100);
			if ($q3->nf()/100-round($q->nf()/100)>0) $pages++;
			$content=str_replace("{pages}",$pages,$content);
			$page=$_GET["page"];
			if ($page=="") $page=1;
			if ($page<$pages) $next=$page+1; else $next=$page;
			if ($page>1) $previous=$page-1; else $previous=1;
			$content=str_replace("{page}",$page,$content);
			$content=str_replace("{next}",$next,$content);
			$content=str_replace("{previous}",$previous,$content);
			$content=str_replace("{last}",$pages,$content);
			$query.=" limit ".(($page-1)*100).", 100";//echo $query;
			$q->query($query);

			$rows="";
			$i=0;
			while ($q->next_record()){
				$i++;				
			$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=detalii_comanda&id_order=".$q->f("id_order")."'>Detalii</a></td>
	<td align='center'><input type=checkbox name=lista[".$q->f("id_order")."]></td>
	<td align=center>".$q->f("id_order")."</td>
	<td>".$q->f("numedefamilie")." ".$q->f("prenume")."</td>
	<td>".$q->f("telefon")."</td>
	<td>".$q->f("email")."</td>
	<td>".$q->f("arrival")."</td>
	<td>".$q->f("passengers")."</td>
	<td>".$q->f("destination")."</td>
	<td>".(($q->f("passengers2")>0)?"Dus-Intors":"One Way")."</td>
	<td>".$q->f("flight_departure_to")."</td>
	<td>".$q->f("passengers2")."</td>
	<td>".$q->f("price")."</td>
	<td><input type=checkbox name=check[".$q->f("id_order")."]></td>
	</tr>";}
			$query="select * from ORDERS where id_order>0";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{liste}",$liste,$content);
			$content=str_replace("{total}",$q->nf(),$content);			
		break;
		case "detalii_comanda":
			FFileRead("template.detalii_comanda.html",$content);
			$title=$sitename_title." - Detalii comanda";
			$q->query("select * from ORDERS where id_order='".$_GET["id_order"]."'");
			$q->next_record();
$pickup_extra=explode("|",$q->f("pickup_extra"));
$pickup_extra2=explode("|",$q->f("pickup_extra2"));

$replace_array=array("id_order","numedefamilie","prenume","telefon","email","arrival","flight_arrival","flight_departure_from","flight_number","passengers","pickup_time","pickup_location","pickup_auto","destination","flight_departure","flight_departure_to","flight_number2","passengers2","pickup_time2","pickup_location2","pickup_auto2","price");
			foreach ($replace_array as $replace) {
				$content=str_replace("{".$replace."}",$q->f("$replace"),$content);
			}
			$j=0;
		for ($i=0;$i<9;$i++) {
			$j=$i+1;
			$content=str_replace("{extra$j}",$pickup_extra[$i],$content);
			$content=str_replace("{extra2$j}",$pickup_extra2[$i],$content);
		}
		break;
		case "delete_comenzi":
			foreach ($_POST["check"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from ORDERS where id_order='$x'");
				}
			}
			foreach ($_POST["lista"] as $y => $value)
			{	
				if ($y>0)
				{
					$q->query("insert into LEGATURI_LISTE set id_lista = '".$_POST["liste"]."', id_comanda = '$y'");
				}
			}
			header("Location:index.php?action=comenzi");
		break;	
		case "listec":
			$liste='';
			FFileRead("template.listec.html",$content);
			$q->query("select * from LISTE order by nume_lista asc");
			while ($q->next_record()){
				$liste.='<option value="'.$q->f("id_lista").'">'.$q->f("nume_lista").'</option>';
			}
			$content=str_replace("{lista}",$liste,$content);
			if ($_POST["id_lista"]!="" || $_GET["id_lista"]!=""){
			FFileRead("template.listec2.html",$content2);
			$content.=$content2;
			$content=str_replace("{error}",$_GET["err"],$content);
			if ($_GET["id_lista"]!="") {
				$content=str_replace("{id_lista}",$_GET["id_lista"],$content);
				$query="select * from ORDERS c JOIN LEGATURI_LISTE lc ON c.id_order=lc.id_comanda where lc.id_lista ='".$_GET["id_lista"]."'";
			}elseif ($_POST["id_lista"]!=""){
				$content=str_replace("{id_lista}",$_POST["id_lista"],$content);
				$query="select * from ORDERS c JOIN LEGATURI_LISTE lc ON c.id_order=lc.id_comanda where lc.id_lista ='".$_POST["id_lista"]."'";
			}
			}
$q->query($query);
$total=$q->nf();
$rows="";
			$i=0;
			while ($q->next_record()){
				$i++;				
			$rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=detalii_comanda&id_order=".$q->f("id_order")."'>".$q->f("id_order")."</a></td>
	<td>".$q->f("arrival")."</td>
	<td>".$q->f("destination")."</td>
	<td>".$q->f("flight_arrival")."</td>
	<td>".$q->f("numedefamilie")." ".$q->f("prenume")."</td>
	<td>".$q->f("passengers")."</td>
	<td>".$q->f("passengers2")."</td>
	<td><input type=text name=obs[".$q->f("id_order")."] size='30' value=".$q->f("obs")."></td>
	</tr>";}
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{liste}",$liste,$content);
			$content=str_replace("{total}",$total,$content);			
		break;
		case "delete_comenzi_lista":
			foreach ($_POST["obs"] as $obs => $value)
			{
				if ($obs>0){
					$query="update LEGATURI_LISTE set obs='".stripslashes($value)."' where id_comanda='$obs'";	
					$q->query($query);
				}
			}
			header("Location:index.php?action=listec&id_lista=".$_POST["id_lista_hidden"]);
		break;	
		case "liste":
			FFileRead("template.liste.html",$content);
			$title=$sitename_title." - Unitati";
			if ($_POST["search"]=="") $query="select * from LISTE ";
			if ($_GET["order"]!="") $query.=" order by ".$_GET["order"];
			else $query.=" order by id_lista";
			if ($_GET["rule"]!="") $query.=" ".$_GET["rule"]; else $query.=" desc";			
			$q->query($query);

			while($q->next_record()){
				if ($q->f("status")==1){
					$status="Activ";$bg_status="";
				}else{
					$status="Inactiv";
					$bg_status="bgcolor=\"000000\"";
				}

			$rows.="<tr><td align=center $bg_status><a href='index.php?action=edit_lista&cauta=".$q->f("id_lista")."'>".$q->f("id_lista")."</a></td>
	<td>".$q->f("nume_lista")."</td>
	<td><input type=checkbox name=check3[".$q->f("id_lista")."]></td>
	</tr>";}
			$query="select * from LISTE where id_lista>0";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);
		break;
		case "delete_unitati":
			$q2=new Cdb;						
			if (isset($_POST["check2"]))
			foreach ($_POST["check2"] as $z => $value)
			{
				if ($z>0)
				{
					$query="select vip from UNITATE where id_unitate='$z'";
					$q->query($query);
					$q->next_record();
					if ($q->f("vip")==0){
					$days_to_add = 365;
					$current_date = date('Y-m-d H:i:s');
					$timeStmp = strtotime($current_date) + $days_to_add * 24 * 60 * 60;
					$final_date = gmdate ('Y-m-d H:i:s', $timeStmp);
					$query="update UNITATE set vip='1', start_vip=NOW(), end_vip='$final_date' where id_unitate='$z'";
					}else
					$query="update UNITATE set vip='0', start_vip='', end_vip='' where id_unitate='$z'";
					$q->query($query);
				}
			}
			if (isset($_POST["check4"]))
			foreach ($_POST["check4"] as $zz => $value)
			{
				if ($zz>0)
				{
					$query="select recomandari_vip from UNITATE where id_unitate='$zz'";
					$q->query($query);
					$q->next_record();					
					if ($q->f("recomandari_vip")==0){
					$days_to_add = 30;
					$current_date = date('Y-m-d H:i:s');
					$timeStmp = strtotime($current_date) + $days_to_add * 24 * 60 * 60;
					$final_date = gmdate ('Y-m-d H:i:s', $timeStmp);
					$query="update UNITATE set recomandari_vip='1', start_recomandari_vip=NOW(), end_recomandari_vip='$final_date' where id_unitate='$zz'";
					}else
					$query="update UNITATE set recomandari_vip='0', start_recomandari_vip='', end_recomandari_vip='' where id_unitate='$zz'";
					$q->query($query);
				}
			}
			if (isset($_POST["check3"]))
			
			if (isset($_POST["observatii"]))
			foreach ($_POST["observatii"] as $obs => $value)
			{
				if ($obs>0){
					$query="update UNITATE set observatii='$value' where id_unitate='$obs'";	
					$q->query($query);
				}
			}
			header("Location:index.php?action=unitati");
			break;
			
		case "add_lista":
			FFileRead("template.add_lista.html",$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_add_lista":		
			$q->query("select * from LISTE where nume_lista='".$_POST["nume_lista"]."'");
			if ($q->next_record()){
				$err="Aceasta lista exista deja";
			}
			elseif ($_POST["nume_lista"]=="")
				$err="Nume lista nu poate lipsi.";
			else{
				$q->query("insert into LISTE set nume_lista='".$_POST["nume_lista"]."', status='1'");
				$err="Lista adaugata cu succes";
			}
			
			header("Location:index.php?action=add_lista&err=$err");
		break;
		case "edit_lista":
			FFileRead("template.edit_lista.html",$content);
			$q->query("select * from LISTE where id_lista='".$_GET["cauta"]."'");
			$q->next_record();
			$content=str_replace("{id_lista}",$q->f("id_lista"),$content);
			$content=str_replace("{nume_lista}",$q->f("nume_lista"),$content);
			$content=str_replace("{error}",$_GET["err"],$content);
		break;
		case "do_edit_lista":		
			$q->query("select * from LISTE where nume_lista='".$_POST["nume_lista"]."' and id_lista<>'".$_POST["id_lista"]."'");
			if ($q->next_record()){
				$err="Aceasta lista exista deja";
			}
			elseif ($_POST["nume_lista"]=="")
				$err="Nume lista nu poate lipsi.";
			else{
				$q->query("update LISTE set nume_lista='".$_POST["nume_lista"]."', status='1' where id_lista='".$_POST["id_lista"]."'");
				$err="Lista editata cu succes";
			}
			
			header("Location:index.php?action=edit_lista&err=$err&id_liste=".$_POST["id_lista"]);
		break;
		case "delete_liste":
			foreach ($_POST["check3"] as $x => $value)
			{	
				if ($x>0)
				{
					$q->query("delete from LISTE where id_lista='$x'");					
					$query="delete from LEGATURI_LISTE where id_lista='$x'";
					$q->query($query);
				}
			}
			header("Location:index.php?action=liste");
			break;	
		case "set_activ_inactiv":
			$q->query("update TARA set status=NOT status where id_tara='".$_GET["id_tara"]."'");
			header("Location:index.php?action=tari");
		break;
		case "set_activ_inactiv_ao":
			$q->query("update AEROPORT set status=NOT status where id_aeroport='".$_GET["id_aeroport"]."'");
			header("Location:index.php?action=ao");
		break;
		case "set_activ_inactiv_statii":
			$q->query("update STATII set status=NOT status where id_statie='".$_GET["id_statie"]."'");
			header("Location:index.php?action=statii");
		break;
		case "set_activ_inactiv_legaturi_statii":
			$q->query("update LEGATURI_STATII set status=NOT status where id_legatura_statie='".$_GET["id_legatura_statie"]."'");
			header("Location:index.php?action=edit_preturi&id_legaturi=".$_GET["id_legaturi"]."&id_auto=0");
		break;
		case "set_activ_inactiv_destinatie":
			$q->query("update DESTINATIE set status=NOT status where id_destinatie='".$_GET["id_destinatie"]."'");
			header("Location:index.php?action=destinatie");
		break;
		case "set_activ_inactiv_auto":
			$q->query("update AUTO set status=NOT status where id_auto='".$_GET["id_auto"]."'");
			header("Location:index.php?action=auto");
		break;
			case "contacte":
			FFileRead("template.contacte.html",$content);
			$title=$sitename_title." - Contacte";
			if ($_POST["search"]=="") $query="select * from contact";
			else $query="select * from contact where ".$_POST["by"]." like '%".$_POST["search"]."%'";
			if ($_POST["order"]!="") $query.=" order by ".$_POST["order"];
			if ($_POST["rule"]!="") $query.=" ".$_POST["rule"];
			$q3->query($query);
			$pages=round($q3->nf()/100);
			if ($q3->nf()/100-round($q->nf()/100)>0) $pages++;
			$content=str_replace("{pages}",$pages,$content);			
			if ($page=="") $page=1;
			if ($page<$pages) $next=$page+1; else $next=$page;
			if ($page>1) $previous=$page-1; else $previous=1;
			$content=str_replace("{page}",$page,$content);
			$content=str_replace("{next}",$next,$content);
			$content=str_replace("{previous}",$previous,$content);
			$content=str_replace("{last}",$pages,$content);
			$query.=" limit ".(($page-1)*100).", 100 ";echo $query;
			$q->query($query);
			while($q->next_record()){
				if ($q->f("deacord")==1) $deacord="Da"; else $deacord="Nu";	
				$rows.="<tr bgcolor='#E2DCDE'>
							<td>".$q->f("id")."</td>
							<td>".$q->f("nume")."-".$q->f("pers_contact")."</td>
							<td>".$q->f("email")."</td>
							<td>".$q->f("adresa")."</td>
							<td>".$q->f("telefon")."</td>							
							<td>$deacord <input type=checkbox name=check1[".$q->f("id")."]></td>
							<td>".$q->f("mail_trimis")."</td>							
							<td><input type=checkbox name=check2[".$q->f("id")."]></td>
						</tr>";}
			$query="select * from contact where id>0";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);
		break;
		case "delete_contacte":
			$q2=new Cdb;
			if (isset($_POST["check1"]))			
			foreach ($_POST["check1"] as $y => $value)
			{
				if ($y>0)
				{
					$query="select deacord from contact where id='$y'";
					$q->query($query);
					$q->next_record();
					if ($q->f("deacord")==0) 
					$query="update contact set deacord='1' where id='$y'";
					else
					$query="update contact set deacord='0' where id='$y'";
					$q->query($query);								
				}
			}	
			if (isset($_POST["check2"]))
			foreach ($_POST["check2"] as $x => $value)
			{	
				if ($x>0)
				{
					$query="delete from contact where id='$x'";
					$q->query($query);
				}
			}
			header("Location:index.php?action=contacte");
		break;	
		case "massemail":
			FFileRead("template.admin.email.downline.html",$content);			
			break;			
		case "do_massemail":
			foreach ($check as $x => $value)
			{	
				$query="select * from MEMBRII where id = '$x'";
				$q->query($query);
				$q->next_record();
				$subjectx=str_replace("{name}",$q->f("name"),$subject);
				$subjectx=str_replace("{email}",$q->f("email"),$subjectx);
				$subjectx=str_replace("{id}",$q->f("id"),$subjectx);
				$messagex=str_replace("{name}",$q->f("name"),$message);
				$messagex=str_replace("{email}",$q->f("email"),$messagex);
				$messagex=str_replace("{id}",$q->f("id"),$messagex);
				@mail($q->f("email"),$subjectx,$messagex, "From: <$webmasteremail>");
				$sent.=$q->f("email")."<br>";
				$content="<h4> Message sent to: </h4> <br><br> ".$sent;
			}
			break;
			
		case "members":
			FFileRead("template.members.htm",$content);
			$content=str_replace("{search}",$search,$content);
			$content=str_replace("{by}",$by,$content);
			$content=str_replace("{start}",$start,$content);
			$content=str_replace("{end}",$end,$content);
			$content=str_replace("{order}",$order,$content);
			$content=str_replace("{rule}",$rule,$content);
			$title=$sitename_title." - Members";			
			if ($_POST["search"]=="")
				$query="select * from MEMBRII ";
			else
			{
				$search=$_POST["search"];
				$query="select * from MEMBRII where ".$_POST["by"]." like '%$search%'";
			}	
			if ($_POST["order"]!="") $query.=" order by ".$_POST["order"];
			if ($_POST["rule"]!="") $query.=$_POST["rule"];

			$q3=new Cdb;
			$q3->query($query);
			$pages=round($q3->nf()/100);
			if ($q3->nf()/100-round($q->nf()/100)>0) $pages++;
			$content=str_replace("{pages}",$pages,$content);

			if ($page=="") $page=1;
			if ($page<$pages) $next=$page+1; else $next=$page;
			if ($page>1) $previous=$page-1; else $previous=1;
			$content=str_replace("{page}",$page,$content);
			$content=str_replace("{next}",$next,$content);
			$content=str_replace("{previous}",$previous,$content);
			$content=str_replace("{last}",$pages,$content);
			$query.=" limit ".(($page-1)*100).", 100 ";
			echo $query;
			$q->query($query);
			while($q->next_record()){	
				$rows.="<tr bgcolor='#E2DCDE'>
					<td>".$q->f("id_user")."</a></td><td>".$q->f("nume_firma")."$paid</td><td>".$q->f("pers_contact")."</td>
					<td>".$q->f("tel_mobil")."</td><td>".$q->f("adresa")."</td><td>".$q->f("data_inscriere")."</td></tr>";					}
			$query="select * from MEMBRII where id_user>0";
			$q->query($query);
			$content=str_replace("{rows}",$rows,$content);
			$content=str_replace("{total}",$q->nf(),$content);
			break;	

		case "add_unitate":
			$title=$sitename_title." Adauga unitate";
			FFileRead("template.add_unitate.html",$content);

$q->query("select * from TIP order by id_tip asc");
while ($q->next_record()){
	$contor++;
	$tip.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_tip[]\" value=\"".$q->f("id_tip")."\"> ".$q->f("nume_tip")."</td>";
	($contor%3==0)?$tip.="</tr><tr>":"";}
$content=str_replace("{tip_unitate}",$tip,$content);
$contor=0;
$q->query("select * from ZONA order by nume_zona asc");
while ($q->next_record()){
	$contor++;
	$zona.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_zona[]\" value=\"".$q->f("id_zona")."\"> ".$q->f("nume_zona")."</td>";
	($contor%3==0)?$zona.="</tr><tr>":"";}
$content=str_replace("{zona}",$zona,$content);
$contor=0;
$q->query("select * from `SPECIFIC` order by nume_specific asc");
while ($q->next_record()){
	$contor++;
	$specific.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_specific[]\" value=\"".$q->f("id_specific")."\"> ".$q->f("nume_specific")."</td>";
	($contor%3==0)?$specific.="</tr><tr>":"";}
$content=str_replace("{specific}",$specific,$content);
$contor=0;
$q->query("select * from FACILITATI order by nume_facilitate asc");
while ($q->next_record()){
	$contor++;
	$facilitati.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_facilitate[]\" value=\"".$q->f("id_facilitate")."\"> ".$q->f("nume_facilitate")."</td>";
	($contor%3==0)?$facilitati.="</tr><tr>":"";}
$content=str_replace("{facilitati}",$facilitati,$content);
$q3->query("select nr_contract from UNITATE order by nr_contract desc");
if ($q3->next_record()) $nr_contract=$q3->f("nr_contract")+1;
else $nr_contract=1;
$content=str_replace("{nr_contract_hidden}",$nr_contract,$content);
$t->query("select nume from TAG where replace_array='1'");
	while ($t->next_record())
	$content=str_replace("{".$t->f("nume")."}","",$content);	
	$content=str_replace("{site_web}","http://",$content);
	$content=str_replace("{error}","",$content);
	break;	
	
	case "edit_unitate":
		$title=$sitename_title." Editeaza unitate";
		FFileRead("template.edit_unitate.html",$content);
		if ($_POST["cauta"]!="" || $_GET["cauta"]!=""){
			FFileRead("template.edit_unitate2.html",$content2);
			$content.=$content2;
			$content=str_replace("{error}",$_GET["err"],$content);
			if ($_GET["cauta"]!="") 
$query="select * from UNITATE join MEMBRII where UNITATE.id_unitate='".$_GET["cauta"]."' and UNITATE.id_user=MEMBRII.id_user";
			else {
			if ($_POST["dupa"]!="nume_firma")
$query="select * from UNITATE join MEMBRII where UNITATE.".$_POST["dupa"]."='".$_POST["cauta"]."' and UNITATE.id_user=MEMBRII.id_user";
			else
$query="select * from UNITATE join MEMBRII where MEMBRII.".$_POST["dupa"]."='".$_POST["cauta"]."' and UNITATE.id_user=MEMBRII.id_user";
			}
$q->query($query);
$q->next_record();
$id_unitate=$q->f("id_unitate");
$id_user=$q->f("id_user");
$data_expirare=$q->f("data_expirare");

$nr_contract=$q->f("nr_contract");

// POZE
for ($img_c=1;$img_c<10;$img_c++){
	if ($q->f("poza$img_c")!="") { $content=str_replace("{poza$img_c}","",$content);
	$content=str_replace("{poze_existente}","<tr><td colspan=\"2\" align=\"center\"><a href=\"poze.php?id_poza=$id_unitate\" target=\"_blank\">Poze existente</a></td></tr>",$content);
	}
	else $content=str_replace("{poza$img_c}","<tr><td align=\"right\">Imagine $img_c</td><td align=\"left\"><input type=\"file\" name=\"poza$img_c\" size=\"26\"></td></tr>",$content);
}
$content=str_replace("{poze_existente}","",$content);
/*$query="select * from ORAS join JUDET where id_oras='$id_oras' and JUDET.id_judet='$id_judet'";
$q2->query($query);
$q2->next_record();

$query="select id_oras, nume_oras_ro from ORAS where id_oras='$id_oras_unitate'";
$q3->query($query);
$q3->next_record();*/
$t->query("select nume from TAG where replace_array='1'");
	while ($t->next_record())
	$content=str_replace("{".$t->f("nume")."}",$q->f($t->f("nume")),$content);
$content=str_replace("{site_web}",$q->f("site_web"),$content);	
$content=str_replace("{id_unitate}",$id_unitate,$content);
$content=str_replace("{id_user}",$id_user,$content);
$content=str_replace("{nr_contract}",$nr_contract,$content);
$content=str_replace("{data_expirare}",ShowDate($data_expirare),$content);
$content=str_replace("{visa_checked}",($q->f("visa")=="1")?"checked":"",$content);
$content=str_replace("{mastercard_checked}",($q->f("mastercard")=="1")?"checked":"",$content);
/*$q->query("select id_oras,nume_oras_ro,id_judet from ORAS order by nume_oras_ro asc");
$localitate="<select name=\"id_oras\"><option selected value=\"".$q2->f("id_oras")."\">".$q2->f("nume_oras_ro")."</option>";
$localitate_pensiune="<select name=\"id_oras_unitate\"><option selected value=\"".$q3->f("id_oras")."\">".$q3->f("nume_oras_ro")."</option>";
while ($q->next_record()){
		$localitate.="<option value='".$q->f("id_oras")."'>".$q->f("nume_oras_ro")."</option>";
		if ($q->f("id_judet")==ID_JUDET) $localitate_pensiune.="<option value='".$q->f("id_oras")."'>".$q->f("nume_oras_ro")."</option>";
}
$localitate.="</select>";
$localitate_pensiune.="</select>";
$content=str_replace("{localitate}",$localitate,$content);
$content=str_replace("{localitate_pensiune}",$localitate_pensiune,$content);
$q->query("select id_judet,nume_judet from JUDET order by nume_judet asc");
$judet="<select name=\"id_judet\"><option selected value=\"".$q2->f("id_judet")."\">".$q2->f("nume_judet")."</option>";
while ($q->next_record())
		$judet.="<option value='".$q->f("id_judet")."'>".$q->f("nume_judet")."</option>";
$judet.="</select>";
$content=str_replace("{judet}",$judet,$content);*/

$query="select tip, zona, `specific`, facilitati from UNITATE where id_unitate='".$_GET["cauta"]."'";//toate datele la care trebuie explode, si care au setari de checkbox.
$q2->query($query);
$q2->next_record();
// TIP, ZONA, SPECIFIC, FACILITATI
$tip="";
$q3->query("select * from TIP order by id_tip asc");
while ($q3->next_record()){
	$contor++;
	$tip.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_tip[]\"".((strpos($q2->f("tip"),$q3->f("id_tip"))===false)?"":"checked")." value=\"".$q3->f("id_tip")."\"> ".$q3->f("nume_tip")."</td>";
	($contor%3==0)?$tip.="</tr><tr>":"";
}
$content=str_replace("{tip_unitate}",$tip,$content);
$contor=0;

$zona="";
$q3->query("select * from ZONA order by nume_zona asc");
while ($q3->next_record()){
	$contor++;
	$zona.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_zona[]\"".((strpos($q2->f("zona"),",".$q3->f("id_zona").",")===false)?"":"checked")." value=\"".$q3->f("id_zona")."\"> ".$q3->f("nume_zona")."</td>";
	($contor%3==0)?$zona.="</tr><tr>":"";
}
$content=str_replace("{zona}",$zona,$content);
$contor=0;

$q3->query("select * from `SPECIFIC` order by nume_specific asc");
while ($q3->next_record()){
	$contor++;
	$specific.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_specific[]\"".((strpos($q2->f("specific"),",".$q3->f("id_specific").",")===false)?"":"checked")."  value=\"".$q3->f("id_specific")."\"> ".$q3->f("nume_specific")."</td>";
	($contor%3==0)?$specific.="</tr><tr>":"";
}
$content=str_replace("{specific}",$specific,$content);
$contor=0;

$q3->query("select * from FACILITATI order by nume_facilitate asc");
while ($q3->next_record()){
	$contor++;
	$facilitati.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_facilitate[]\"".((strpos($q2->f("facilitati"),",".$q3->f("id_facilitate").",")===false)?"":"checked")." value=\"".$q3->f("id_facilitate")."\"> ".$q3->f("nume_facilitate")."</td>";
	($contor%3==0)?$facilitati.="</tr><tr>":"";
}
$content=str_replace("{facilitati}",$facilitati,$content);
$content=str_replace("{error}","",$content);
			}
		break;
		case "do_edit_unitate":
/*if ($_POST["nume_firma"]=="")$err_ro.="Numele firmei nu poate lipsi<br>";
if ($_POST["cod_unic"]=="")$err_ro.="Codul unic de inregistrare nu poate lipsi<br>";
if ($_POST["nr_reg_com"]=="")$err_ro.="Numarul Registrul Comertului nu poate lipsi<br>";
if ($_POST["pers_contact"]=="")$err_ro.="Persoana de contact nu poate lipsi<br>";
if ($_POST["functia"]=="")$err_ro.="Functia nu poate lipsi<br>";
if ($_POST["adresa_firma"]=="")$err_ro.="Adresa firmei nu poate lipsi<br>";
if ($_POST["tip_unitate"]=="") $err_ro.="Tip unitate nu poate lipsi<br>";*/
if ($_POST["nume_unitate"]=="")	$err_ro.="Nume unitate nu poate lipsi<br>";
if ($_POST["parola"]!=$_POST["parolar"])	$err_ro.="Parolele nu corespund.<br>";
//refac pagina de modificari
if ($err_ro!=""){
header("Location:index.php?action=edit_unitate&cauta=".$_POST["id_unitate"]."&err=$err_ro");
die();
}
// MEMBRII (do_edit_unitate)
$query="update MEMBRII set email='".$_POST["email"]."', parola='".$_POST["parola"]."', nume_firma='".$_POST["nume_firma"]."', cod_unic='".$_POST["cod_unic"]."',nr_reg_com='".$_POST["nr_reg_com"]."',pers_contact='".$_POST["pers_contact"]."',functia='".$_POST["functia"]."', tel_fix='".$_POST["tel_fix"]."',tel_mobil='".$_POST["tel_mobil"]."',fax_user='".$_POST["fax_user"]."', adresa_firma='".$_POST["adresa_firma"]."',data_expirare='".GettheDate($_POST["data_expirare"])."' where id_user='".$_POST["id_user"]."'";
$q->query($query);
$file_dir = $serverpath."/images/unitati/";
$j=0;
for ($i=1;$i<10;$i++){
	if (isset($_FILES["poza$i"]) && $_FILES["poza$i"]['size']>1)
	{
	    if (trim($_FILES["poza$i"]['name'])!="") {  
	      $newfile = $file_dir.$_POST["id_unitate"]."_0".$i.".jpg";
		  move_uploaded_file($_FILES["poza$i"]['tmp_name'], $newfile);  
		  $j=$i;
	 	}
	    if (isset($j)&&$j==$i){${"ok".$i}="ok";}
		else {
			$err="Sorry, there was a problem uploading your picture.";
		}
	$pics=$_SERVER['DOCUMENT_ROOT']."/images/unitati/".$_POST["id_unitate"]."_0".$i.".jpg";
	createthumb($pics,substr($pics,0,-4)."_tn.jpg",124,93);

	$pics=ditchtn($pics,"tnn_");	
	createnormal($pics,$pics,580,435);
	}
}
// UNITATI (do_edit_unitate)
$lista_facilitati="";
if($_POST["id_facilitate"]!=""){
	foreach ($_POST["id_facilitate"] as $facilitate) $lista_facilitati.=$facilitate.",";
	$lista_facilitati=",".$lista_facilitati;
}
$tip_unitate="";
if($_POST["id_tip"]!=""){
	foreach ($_POST["id_tip"] as $tipunitate) $tip_unitate.=$tipunitate.",";
	$tip_unitate=substr($tip_unitate,0,-1);
}
$lista_zone="";
if($_POST["id_zona"]!=""){
foreach ($_POST["id_zona"] as $listazone) $lista_zone.=$listazone.",";
		$lista_zone=",".$lista_zone;
}
$lista_specific="";
if($_POST["id_specific"]!=""){
	foreach ($_POST["id_specific"] as $listaspecific) $lista_specific.=$listaspecific.",";
	$lista_specific=",".$lista_specific;
}
if (isset($_POST["visa"])){ $visa=1;}
if (isset($_POST["mastercard"])){ $mastercard=1;}
$query="update UNITATE set zona='$lista_zone',tip='$tip_unitate',`specific`='$lista_specific', nume_unitate='".$_POST["nume_unitate"]."',latitudine='".$_POST["latitudine"]."',longitudine='".$_POST["longitudine"]."', email_rezervari='".$_POST["email_rezervari"]."', site_web='".$_POST["site_web"]."', stele='".$_POST["stele"]."', orar='".$_POST["orar"]."', capacitate='".$_POST["capacitate"]."', telefon1='".$_POST["telefon1"]."', telefon2='".$_POST["telefon2"]."', fax='".$_POST["fax"]."', adresa='".$_POST["adresa"]."',descriere_ro='".addslashes($_POST["descriere_ro"])."', descriere_en='".addslashes($_POST["descriere_en"])."', alte_carti_credit='".$_POST["alte_carti_credit"]."', comentarii_ro='".addslashes($_POST["comentarii_ro"])."', comentarii_en='".addslashes($_POST["comentarii_en"])."',vip='".$_POST["vip"]."', facilitati='$lista_facilitati',visa='$visa', mastercard='$mastercard'";

for ($k=1;$k<10;$k++)
	if (${"ok".$k}=="ok") $query.=", poza".$k."='$sitename/images/unitati/".$_POST["id_unitate"]."_0".$k.".jpg'";
$query.="where id_unitate='".$_POST["id_unitate"]."'";
$q->query($query);
$err_ro="Datele au fost modificate.";
header("Location:index.php?action=edit_unitate&cauta=".$_POST["id_unitate"]."&err=$err_ro");
		break;
			
		case "do_add_unitate":
if ($_POST["email"]!="") {
	$query="select email from MEMBRII where email='".$_POST["email"]."'";
	$q->query($query);
	if ($q->nf()) $err_ro="Acest email exista deja in baza noastra de date<br>";
	else{
//preg_match("^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$",$_POST['email'],$match);
	//	if(!@$match[0]) $err_ro = "Emailul nu este valid.<br>";	
	}
}else $err_ro="Emailul nu poate lipsi<br>";	
if ($_POST["parola"]=="")	$err_ro.="Parola nu poate lipsi<br>";
if ($_POST["parola"]!=$_POST["parolar"])	$err_ro.="Parolele nu coincid<br>";
/*if ($_POST["nume_firma"]=="")	$err_ro.="Numele firmei nu poate lipsi<br>";
if ($_POST["cod_unic"]=="")	$err_ro.="Codul unic de inregistrare nu poate lipsi<br>";
if ($_POST["nr_reg_com"]=="")	$err_ro.="Numarul Registrul Comertului nu poate lipsi<br>";
if ($_POST["pers_contact"]=="")	$err_ro.="Persoana de contact nu poate lipsi<br>";
if ($_POST["functia"]=="")	$err_ro.="Functia nu poate lipsi<br>";
if ($_POST["adresa_firma"]=="")	$err_ro.="Adresa firmei nu poate lipsi<br>";
if ($_POST["id_oras"]=="")	$err_ro.="Orasul nu poate lipsi<br>";
if ($_POST["id_judet"]=="")	$err_ro.="Judetul nu poate lipsi<br>";*/
if ($_POST["nume_unitate"]=="")	$err_ro.="Nume unitate nu poate lipsi<br>";
if ($_POST["nr_contract"]=="")	$err_ro.="Numarul contractului nu poate lipsi<br>";
if ($_POST["capacitate"]=="")	$err_ro.="Numarul de locuri din local nu poate lipsii<br>";
if ($err_ro!=""){
	FFileRead("template.add_unitate.html",$content);
$q->query("select * from TIP order by id_tip asc");
while ($q->next_record()){
	$contor++;
	$tip.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_tip[]\" value=\"".$q->f("id_tip")."\"> ".$q->f("nume_tip")."</td>";
	($contor%3==0)?$tip.="</tr><tr>":"";
}
$content=str_replace("{tip_unitate}",$tip,$content);
$contor=0;
$q->query("select * from ZONA order by nume_zona asc");
while ($q->next_record()){
	$contor++;
	$zona.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_zona[]\" value=\"".$q->f("id_zona")."\"> ".$q->f("nume_zona")."</td>";
	($contor%3==0)?$zona.="</tr><tr>":"";
}
$content=str_replace("{zona}",$zona,$content);
$contor=0;
$q->query("select * from `SPECIFIC` order by nume_specific asc");
while ($q->next_record()){
	$contor++;
	$specific.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_specific[]\" value=\"".$q->f("id_specific")."\"> ".$q->f("nume_specific")."</td>";
	($contor%3==0)?$specific.="</tr><tr>":"";
}
$content=str_replace("{specific}",$specific,$content);
$contor=0;
$q->query("select * from FACILITATI order by nume_facilitate asc");
while ($q->next_record()){
	$contor++;
	$facilitati.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_facilitate[]\" value=\"".$q->f("id_facilitate")."\"> ".$q->f("nume_facilitate")."</td>";
	($contor%3==0)?$facilitati.="</tr><tr>":"";
}
$content=str_replace("{facilitati}",$facilitati,$content);
		$err_ro="<font color=red>".$err_ro."</font>";
		$content=str_replace("{error}",$err_ro,$content);
$t->query("select nume from TAG where replace_array='1'");
	while ($t->next_record())
	$content=str_replace("{".$t->f("nume")."}",$_POST[$t->f("nume")],$content);	
} else {
// MEMBRII
$data_expirare= strtotime(date("Y-m-d", strtotime($current_date)) . " +".$_POST["data_expirare"]." month");
$data_expirare = gmdate ('Y-m-d H:i:s', $data_expirare);
$query="insert into MEMBRII (email, parola, nume_firma, cod_unic, nr_reg_com, pers_contact, functia, tel_fix, tel_mobil, adresa_firma, data_inscriere, data_expirare) values ('".$_POST["email"]."','".$_POST["parola"]."','".$_POST["nume_firma"]."','".$_POST["cod_unic"]."','".$_POST["nr_reg_com"]."','".$_POST["pers_contact"]."','".$_POST["functia"]."','".$_POST["tel_fix"]."','".$_POST["tel_mobil"]."','".$_POST["adresa_firma"]."', NOW(),'$data_expirare')";
$q->query($query);
$query="select id_unitate from UNITATE order by id_unitate desc limit 0,1";
	$q->query($query);
	$q->next_record();
	$ii=$q->f("id_unitate");
	$ii++;
$query="select id_user from MEMBRII order by id_user desc limit 0,1";
	$q->query($query);
	$q->next_record();
	$id_user=$q->f("id_user");
$file_dir = $serverpath."/images/unitati/";
$j=0;
for ($i=1;$i<10;$i++){
	if ($_FILES["poza$i"]['size']>1)
	{
	    if (trim($_FILES["poza$i"]['name'])!="") {
	      $newfile = $file_dir.$ii."_0".$i.".jpg";
		  move_uploaded_file($_FILES["poza$i"]['tmp_name'], $newfile);
		  $j=$i;
	 	}
	    if (isset($j)&&$j==$i){${"ok".$i}="ok";}
		else {
			$err="Sorry, there was a problem uploading your picture.";
		}		
	$pics=$_SERVER['DOCUMENT_ROOT']."/images/unitati/".$ii."_0".$i.".jpg";
	//$pics=ditchtn($pics,"_tn");
	createthumb($pics,substr($pics,0,-4)."_tn.jpg",124,93);
	
	$pics=ditchtn($pics,"tnn_");
	createnormal($pics,$pics,580,435);
	}
}
// FACILITATI
$lista_facilitati="";
if($_POST["id_facilitate"]!=""){
	foreach ($_POST["id_facilitate"] as $facilitate) $lista_facilitati.=$facilitate.",";
$lista_facilitati = ",".$lista_facilitati;
}
$tip_unitate="";
if($_POST["id_tip"]!=""){
	foreach ($_POST["id_tip"] as $tipunitate) $tip_unitate.=$tipunitate.",";
	$tip_unitate=substr($tip_unitate,0,-1);
}
$lista_zone="";
if($_POST["id_zona"]!=""){
	foreach ($_POST["id_zona"] as $listazone) 	$lista_zone.=$listazone.",";
	$lista_zone = ",".$lista_zone;	
}
$lista_specific="";
if($_POST["id_specific"]!=""){
	foreach ($_POST["id_specific"] as $listaspecific) $lista_specific.=$listaspecific.",";
	$lista_specific=",".$lista_specific;
}
// UNITATI
if (isset($_POST["visa"])){ $visa=1;}
if (isset($_POST["mastercard"])){ $mastercard=1;}
$query="insert into UNITATE (id_unitate, id_user,tip,zona,`specific`, latitudine, longitudine, nume_unitate, email_rezervari, site_web, capacitate, orar, telefon1, telefon2, fax, adresa,descriere_ro, descriere_en, alte_carti_credit, comentarii_ro, comentarii_en, facilitati,vip,nr_contract,visa,mastercard";
for ($l=1;$l<10;$l++)
	if (${"ok".$l}=="ok") $query.=", poza".$l;
$query.=") values ('$ii', '$id_user', '$tip_unitate', '$lista_zone', '$lista_specific', '".$_POST["latitudine"]."', '".$_POST["longitudine"]."', '".$_POST["nume_unitate"]."', '".$_POST["email_rezervari"]."', '".$_POST["site_web"]."','".$_POST["capacitate"]."','".$_POST["orar"]."', '".$_POST["telefon1"]."','".$_POST["telefon2"]."','".$_POST["fax"]."','".$_POST["adresa"]."','".$_POST["descriere_ro"]."','".$_POST["descriere_en"]."','".$_POST["alte_carti_credit"]."','".$_POST["comentarii_ro"]."','".$_POST["comentarii_en"]."','$lista_facilitati','".$_POST["vip"]."','".$_POST["nr_contract"]."','".$_POST["visa"]."','".$_POST["mastercard"]."'";
for ($k=1;$k<10;$k++)
	if (${"ok".$k}=="ok") $query.=", '$sitename/images/unitati/".$ii."_0".$k.".jpg'";
$query.=")";
$q->query($query);
$q->query("insert into EVENIMENTE (id_unitate) values ('$ii')");
header("Location: index.php?action=add_unitate");
}
	break;
	case "terase":
		FFileRead("template.add_terasa.html",$content);
		$id_unitate=$_GET["id_unitate"];
		$query="select nume_unitate from UNITATE where id_unitate='$id_unitate'";
		$q->query($query);
		$q->next_record();
		$nume_unitate=$q->f("nume_unitate");
		$query="select * from TERASE where id_unitate='$id_unitate'";
		$q->query($query);
		$q->next_record();
		$contor=0;
		$content=str_replace("{nume_unitate}",strtoupper($nume_unitate),$content);
		$content=str_replace("{capacitate_terasa}",$q->f("capacitate_terasa"),$content);
		$content=str_replace("{error}",$_GET["err"],$content);
		$content=str_replace("{descriere_ro}",$q->f("descriere_ro"),$content);
		$content=str_replace("{id_unitate}",$id_unitate,$content);	
		$q2->query("select * from FACILITATI_TERASE order by nume_facilitate asc");
while ($q2->next_record()){
	$contor++;
	$facilitati.="<td valign=\"top\"><input type=\"checkbox\" name=\"id_facilitate[]\"".((strpos($q->f("facilitati"),",".$q2->f("id_facilitate").",")===false)?"":"checked")." value=\"".$q2->f("id_facilitate")."\"> ".$q2->f("nume_facilitate")."</td>";
	($contor%3==0)?$facilitati.="</tr><tr>":"";
}
$content=str_replace("{facilitati}",$facilitati,$content);
		$content=str_replace("{error}","",$content);
		for ($img_c=1;$img_c<5;$img_c++){
	if ($q->f("poza$img_c")!="") { $content=str_replace("{poza$img_c}","",$content);
	$content=str_replace("{poze_existente}","<tr><td colspan=\"2\" align=\"center\"><a href=\"poze_terase.php?id_poza=$id_unitate\" target=\"_blank\">Poze existente</a></td></tr>",$content);
	}
	else $content=str_replace("{poza$img_c}","<tr><td align=\"right\">Imagine $img_c</td><td align=\"left\"><input type=\"file\" name=\"poza$img_c\" size=\"26\"></td></tr>",$content);
}
$content=str_replace("{poze_existente}","",$content);
	break;
	case "do_add_terase":
if ($_POST["capacitate_terasa"]=="")	$err_ro="Numarul de locuri din local nu poate lipsi<br>";
if ($_POST["descriere_ro"]=="")	$err_ro.="Descrierea terasei nu poate lipsi<br>";
if ($err_ro!=""){
header("Location:index.php?action=terase&id_unitate=".$_POST["id_unitate"]."&err=$err_ro");
die();
} else {
	$file_dir = $serverpath."/images/terase/";
$j=0;
for ($i=1;$i<5;$i++){
	if ($_FILES["poza$i"]['size']>1)
	{
	    if (trim($_FILES["poza$i"]['name'])!="") {
	      $newfile = $file_dir.$_POST["id_unitate"]."_0".$i.".jpg";
		  move_uploaded_file($_FILES["poza$i"]['tmp_name'], $newfile);
		  $j=$i;
	 	}
	    if (isset($j)&&$j==$i){${"ok".$i}="ok";}
		else {
			$err="Sorry, there was a problem uploading your picture.";
		}
	$pics=$_SERVER['DOCUMENT_ROOT']."/images/terase/".$_POST["id_unitate"]."_0".$i.".jpg";
	//$pics=ditchtn($pics,"_tn");
	createthumb($pics,substr($pics,0,-4)."_tn.jpg",124,93);
	
	$pics=ditchtn($pics,"tnn_");
	createnormal($pics,$pics,580,435);
	}
}
	$lista_facilitati_terasa="";
	if($_POST["id_facilitate"]!=""){
		foreach ($_POST["id_facilitate"] as $facilitate) $lista_facilitati_terasa.=$facilitate.",";
	$lista_facilitati_terasa = ",".$lista_facilitati_terasa;
	}
	$q->query("select * from TERASE where id_unitate='".$_POST["id_unitate"]."'");
	if ($q->next_record()){	
		$query="update TERASE set capacitate_terasa='".$_POST["capacitate_terasa"]."', descriere_ro='".$_POST["descriere_ro"]."', facilitati='$lista_facilitati_terasa'";
		for ($l==1;$l<5;$l++)
	if (${"ok".$l}=="ok") $query.=", poza$l='$sitename/images/terase/".$_POST["id_unitate"]."_0".$l.".jpg'";
$query.=" where id_unitate='".$_POST["id_unitate"]."'";
	}else{	
		$query="insert into TERASE (id_unitate, capacitate_terasa, descriere_ro, facilitati";
		for ($l==1;$l<5;$l++)
	if (${"ok".$l}=="ok") $query.=", poza".$l;
$query.=") values ('".$_POST["id_unitate"]."', '".$_POST["capacitate_terasa"]."', '".$_POST["descriere_ro"]."','$lista_facilitati_terasa'";
for ($k=1;$k<5;$k++)
	if (${"ok".$k}=="ok") $query.=", '$sitename/images/terase/".$_POST["id_unitate"]."_0".$k.".jpg'";
$query.=")";
	}
$q2->query($query);	
}
		header("Location:index.php?action=unitati");
		break;
	case "evenimente":
		FFileRead("template.add_eveniment.html",$content);
		$query="select * from EVENIMENTE where id_unitate='".$_GET["id_unitate"]."'";
		$q->query($query);
		$q->next_record();
		$content=str_replace("{titlu}",$q->f("titlu"),$content);
		$content=str_replace("{titlu_en}",$q->f("titlu_en"),$content);
		$content=str_replace("{mesaj}",$q->f("mesaj"),$content);
		$content=str_replace("{mesaj_en}",$q->f("mesaj_en"),$content);
		$content=str_replace("{eveniment_vip}",$q->f("eveniment_vip"),$content);
		$content=str_replace("{id_unitate}",$q->f("id_unitate"),$content);
		if ($q->f("eveniment_vip")==1)
		$content=str_replace("{end_eveniment_vip}",ShowDate($q->f("end_eveniment_vip")),$content);
		else 
		$content=str_replace("{end_eveniment_vip}","",$content);
		$content=str_replace("{error}","",$content);
		if ($q->f("poza1")!="") { $content=str_replace("{poza1}","",$content);
	$content=str_replace("{poze_existente}","<tr><td colspan=\"2\" align=\"center\"><a href=\"poze_evenimente.php?id_poza=".$_GET["id_unitate"]."\" target=\"_blank\">Imagine existenta</a></td></tr>",$content);
	}
	else $content=str_replace("{poza1}","<tr><td align=\"right\">Imagine 1</td><td align=\"left\"><input type=\"file\" name=\"poza1\" size=\"26\"></td></tr>",$content);
$content=str_replace("{poze_existente}","",$content);
	break;
	case "do_add_eveniment":
		//echo GettheDate($_POST["end_eveniment_vip"]);die();
		$end_eveniment_vip="";
		if ($_POST["eveniment_vip"]==1)
			$end_eveniment_vip=GettheDate($_POST["end_eveniment_vip"]);
		$file_dir = $serverpath."/images/evenimente/";
		$j=0;
			if ($_FILES["poza1"]['size']>1)
			{
			    if (trim($_FILES["poza1"]['name'])!="") {
			      $newfile = $file_dir.$_POST["id_unitate"]."_01.jpg";
				  move_uploaded_file($_FILES["poza1"]['tmp_name'], $newfile);
				  $ok="ok";
			 	} else {
					$err="Sorry, there was a problem uploading your picture.";
				}
			$pics=$_SERVER['DOCUMENT_ROOT']."/images/evenimente/".$_POST["id_unitate"]."_01.jpg";
			//$pics=ditchtn($pics,"_tn");
			createthumb($pics,substr($pics,0,-4)."_tn.jpg",124,93);
			
			$pics=ditchtn($pics,"tnn_");
			createnormal($pics,$pics,580,435);
			}
		$query="update EVENIMENTE set titlu='".$_POST["titlu"]."',titlu_en='".$_POST["titlu_en"]."',mesaj='".$_POST["mesaj"]."',mesaj_en='".$_POST["mesaj_en"]."',data=NOW(),eveniment_vip='".$_POST["eveniment_vip"]."',end_eveniment_vip='$end_eveniment_vip'";
		if ($ok=="ok") $query.=", poza1='$sitename/images/evenimente/".$_POST["id_unitate"]."_01.jpg'";
		$query.=" where id_unitate='".$_POST["id_unitate"]."'";
		$q->query($query);
		/*$q->query("select eveniment_vip from EVENIMENTE where id_unitate='".$_POST["id_unitate"]."'");
		$q->next_record();
		if ($q->f("evenimente_vip")==0){
					$days_to_add = 30;
					$current_date = date('Y-m-d H:i:s');
					$timeStmp = strtotime($current_date) + $days_to_add * 24 * 60 * 60;
					$final_date = gmdate ('Y-m-d H:i:s', $timeStmp);
					$query="update EVENIMENTE set eveniment_vip='1', start_eveniment_vip=NOW(), end_eveniment_vip='$final_date' where id_unitate='".$_POST["id_unitate"]."'";
					}else
					$query="update EVENIMENTE set eveniment_vip='0', start_eveniment_vip='', end_eveniment_vip='' where id_unitate='".$_POST["id_unitate"]."'";

		$q->query($query);*/
		header("Location:index.php?action=unitati");
	break;
	case "revelion":
		FFileRead("template.add_revelion.html",$content);
		$id_unitate=$_GET["id_unitate"];
		$query="select * from REVELION where id_unitate='".$_GET["id_unitate"]."'";
		$q->query($query);
		$q->next_record();
		$content=str_replace("{titlu}",$q->f("titlu"),$content);
		$content=str_replace("{mesaj}",$q->f("mesaj"),$content);
		$content=str_replace("{id_unitate}",$id_unitate,$content);		
		$content=str_replace("{error}","",$content);
	if ($q->f("poza1")!="") { $content=str_replace("{poza1}","",$content);
	$content=str_replace("{poze_existente}","<tr><td colspan=\"2\" align=\"center\"><a href=\"poze_revelion.php?id_poza=$id_unitate\" target=\"_blank\">Imagine existenta</a></td></tr>",$content);
	}
	else $content=str_replace("{poza1}","<tr><td align=\"right\">Imagine 1</td><td align=\"left\"><input type=\"file\" name=\"poza1\" size=\"26\"></td></tr>",$content);
$content=str_replace("{poze_existente}","",$content);
	break;
	case "do_add_revelion":
		$file_dir = $serverpath."/images/revelion/";
		$j=0;
			if ($_FILES["poza1"]['size']>1)
			{
			    if (trim($_FILES["poza1"]['name'])!="") {
			      $newfile = $file_dir.$_POST["id_unitate"]."_01.jpg";
				  move_uploaded_file($_FILES["poza1"]['tmp_name'], $newfile);
				  $ok="ok";
			 	} else {
					$err="Sorry, there was a problem uploading your picture.";
				}
			$pics=$_SERVER['DOCUMENT_ROOT']."/images/revelion/".$_POST["id_unitate"]."_01.jpg";
			//$pics=ditchtn($pics,"_tn");
			createthumb($pics,substr($pics,0,-4)."_tn.jpg",124,93);
			
			$pics=ditchtn($pics,"tnn_");
			createnormal($pics,$pics,580,435);
			}
			$q->query("select * from REVELION where id_unitate='".$_POST["id_unitate"]."'");
			if ($q->next_record()){	
				$query="update REVELION set titlu='".$_POST["titlu"]."',mesaj='".$_POST["mesaj"]."',data=NOW()";
				if ($ok=="ok") $query.=", poza1='$sitename/images/revelion/".$_POST["id_unitate"]."_01.jpg'";
				$query.=" where id_unitate='".$_POST["id_unitate"]."'";
			}else{	
				$query="insert into REVELION (id_unitate, titlu, mesaj, data";
				if ($ok=="ok") $query.=", poza1".$l;
					$query.=") values ('".$_POST["id_unitate"]."', '".$_POST["titlu"]."', '".$_POST["mesaj"]."',NOW()";
					if ($ok=="ok") $query.=", '$sitename/images/revelion/".$_POST["id_unitate"]."_01.jpg'";
					$query.=")";
			}
			$q2->query($query);	
		header("Location:index.php?action=unitati");
	break;
	case "vday":
		FFileRead("template.add_vday.html",$content);
		$id_unitate=$_GET["id_unitate"];
		$query="select * from VDAY where id_unitate='".$_GET["id_unitate"]."'";
		$q->query($query);
		$q->next_record();
		$content=str_replace("{titlu}",$q->f("titlu"),$content);
		$content=str_replace("{mesaj}",$q->f("mesaj"),$content);
		$content=str_replace("{titlu_en}",$q->f("titlu_en"),$content);
		$content=str_replace("{mesaj_en}",$q->f("mesaj_en"),$content);
		$content=str_replace("{id_unitate}",$id_unitate,$content);		
		$content=str_replace("{error}","",$content);	
	break;
	case "do_add_vday":
			$q->query("select * from VDAY where id_unitate='".$_POST["id_unitate"]."'");
			if ($q->next_record()){	
				$query="update VDAY set titlu='".addslashes($_POST["titlu"])."',mesaj='".addslashes($_POST["mesaj"])."', titlu_en='".addslashes($_POST["titlu_en"])."',mesaj_en='".addslashes($_POST["mesaj_en"])."',data=NOW() where id_unitate='".$_POST["id_unitate"]."'";
			}else{	
				$query="insert into VDAY (id_unitate, titlu, mesaj, titlu_en, mesaj_en, data) values ('".$_POST["id_unitate"]."', '".addslashes($_POST["titlu"])."', '".addslashes($_POST["mesaj"])."', '".addslashes($_POST["titlu_en"])."', '".addslashes($_POST["mesaj_en"])."',NOW())";
			}
			$q2->query($query);	
		header("Location:index.php?action=unitati");
	break;

	

	case "optm":
		FFileRead("template.add_optm.html",$content);
		$id_unitate=$_GET["id_unitate"];
		$query="select * from OPTMARTIE where id_unitate='".$_GET["id_unitate"]."'";
		$q->query($query);
		$q->next_record();
		$content=str_replace("{titlu}",$q->f("titlu"),$content);
		$content=str_replace("{mesaj}",$q->f("mesaj"),$content);
		$content=str_replace("{titlu_en}",$q->f("titlu_en"),$content);
		$content=str_replace("{mesaj_en}",$q->f("mesaj_en"),$content);
		$content=str_replace("{id_unitate}",$id_unitate,$content);		
		$content=str_replace("{error}","",$content);	
	break;
	case "do_add_optm":
			$q->query("select * from OPTMARTIE where id_unitate='".$_POST["id_unitate"]."'");
			if ($q->next_record()){	
				$query="update OPTMARTIE set titlu='".addslashes($_POST["titlu"])."',mesaj='".addslashes($_POST["mesaj"])."', titlu_en='".addslashes($_POST["titlu_en"])."',mesaj_en='".addslashes($_POST["mesaj_en"])."',data=NOW() where id_unitate='".$_POST["id_unitate"]."'";
			}else{	
				$query="insert into OPTMARTIE (id_unitate, titlu, mesaj, titlu_en, mesaj_en, data) values ('".$_POST["id_unitate"]."', '".addslashes($_POST["titlu"])."', '".addslashes($_POST["mesaj"])."', '".addslashes($_POST["titlu_en"])."', '".addslashes($_POST["mesaj_en"])."',NOW())";
			}
			$q2->query($query);	
		header("Location:index.php?action=unitati");
	break;
	case "anunturi":
		FFileRead("template.anunturi.html",$content);
			$title=$sitename_title." - Anunturi";			
			$query="select * from ANUNTURI a join TIP_ANUNTURI t where t.id_tip_anunt=a.tip_anunt order by  tip_anunt asc";
		
			$q3=new Cdb;
			$q3->query($query);
			$pages=round($q3->nf()/100);
			if ($q3->nf()/100-round($q->nf()/100)>0) $pages++;
			$content=str_replace("{pages}",$pages,$content);

			if ($page=="") $page=1;
			if ($page<$pages) $next=$page+1; else $next=$page;
			if ($page>1) $previous=$page-1; else $previous=1;
			$content=str_replace("{page}",$page,$content);
			$content=str_replace("{next}",$next,$content);
			$content=str_replace("{previous}",$previous,$content);
			$content=str_replace("{last}",$pages,$content);
			$query.=" limit ".(($page-1)*100).", 100 ";
			echo $query;
			$q->query($query);
			while($q->next_record()){	
			if ($q->f("status")==1){
					$status="Activ";$bg_status="";
				}else{
					$status="Inactiv";
					$bg_status="bgcolor=\"000000\"";
				}
				$rows.="<tr bgcolor='#E2DCDE'>
					<td align=center $bg_status><a href='index.php?action=add_anunt&id_anunt=".$q->f("id_anunt")."'>".$q->f("id_anunt")."</a>&nbsp;&nbsp;

	<a href='index.php?action=set_activ_inactiv_anunturi&id_anunt=".$q->f("id_anunt")."'>$status</a></td>
	
	<td>".$q->f("nume_tip_anunt")."</td><td>".$q->f("date_contact")."</td>

					<td>".$q->f("titlu_ro")."</td><td>".$q->f("text_ro")."</td><td><input type=checkbox name=checkc[".$q->f("id_anunt")."]></td></tr>";						}

			$query="select * from ANUNTURI";

			$q->query($query);

			$content=str_replace("{rows}",$rows,$content);

			$content=str_replace("{total}",$q->nf(),$content);

			break;
	case "add_anunt":
		FFileRead("template.add_anunt.html",$content);
		$id_anunt=$_GET["id_anunt"];
		$query="select * from TIP_ANUNTURI";
		$q2->query($query);
		$tip_anunt_row="";
		$query="select * from ANUNTURI where id_anunt='".$_GET["id_anunt"]."'";
		$q->query($query);
		$q->next_record();
		$content=str_replace("{date_contact}",$q->f("date_contact"),$content);

		$content=str_replace("{nr_contract}",$q->f("nr_contract"),$content);

		$content=str_replace("{titlu_ro}",$q->f("titlu_ro"),$content);

		$content=str_replace("{titlu_en}",$q->f("titlu_en"),$content);
		$content=str_replace("{text_ro}",$q->f("text_ro"),$content);
		$content=str_replace("{text_en}",$q->f("text_en"),$content);
		$content=str_replace("{id_anunt}",$id_anunt,$content);

		while ($q2->next_record()){		($q->f("tip_anunt")==$q2->f("id_tip_anunt"))?$selected="selected":$selected="";
			$tip_anunt_row.="<option $selected value=\"".$q2->f("id_tip_anunt")."\">".$q2->f("nume_tip_anunt")."</option>";
		}		$content=str_replace("{tip_anunt}",$tip_anunt_row,$content);
		$content=str_replace("{error}",$_GET["err"],$content);
	for ($img_c=1;$img_c<5;$img_c++){
	if ($q->f("poza$img_c")!="") { $content=str_replace("{poza$img_c}","",$content);
	$content=str_replace("{poze_existente}","<tr><td colspan=\"2\" align=\"center\"><a href=\"poze_anunturi.php?id_poza=$id_anunt\" target=\"_blank\">Poze existente</a></td></tr>",$content);
	}
	else $content=str_replace("{poza$img_c}","<tr><td align=\"right\">Imagine $img_c</td><td align=\"left\"><input type=\"file\" name=\"poza$img_c\" size=\"26\"></td></tr>",$content);
	}
	$content=str_replace("{poze_existente}","",$content);
$q3->query("select nr_contract from UNITATE order by nr_contract desc");
if ($q3->next_record()) $nr_contract=$q3->f("nr_contract")+1;
else $nr_contract=1;
$content=str_replace("{nr_contract_hidden}",$nr_contract,$content);	break;
	
	case "do_add_anunt":
	if ($_POST["titlu_ro"]=="")	$err_ro="Titlul nu poate lipsi<br>";
if ($_POST["text_ro"]=="")	$err_ro.="Textul anuntului nu poate lipsi<br>";

if ($_POST["nr_contract"]=="")	$err_ro.="Numarul contractului nu poate lipsi<br>";

if ($err_ro!=""){
header("Location:index.php?action=add_anunt&id_anunt=".$_POST["id_anunt"]."&err=$err_ro");
die();
} else {
	$file_dir = $serverpath."/images/nunti/";
$j=0;
$query="select id_anunt from ANUNTURI order by id_anunt desc limit 0,1";
$q->query($query);
$q->next_record();
$ii=$q->f("id_anunt");
$ii++;
$id_anunt_poza=($_POST["id_anunt"]!="")?$_POST["id_anunt"]:$ii;
for ($i=1;$i<5;$i++){
	if ($_FILES["poza$i"]['size']>1)
	{
	    if (trim($_FILES["poza$i"]['name'])!="") {
	      $newfile = $file_dir.$id_anunt_poza."_0".$i.".jpg";
		  move_uploaded_file($_FILES["poza$i"]['tmp_name'], $newfile);
		  $j=$i;
	 	}
	    if (isset($j)&&$j==$i){${"ok".$i}="ok";}
		else {
			$err="Sorry, there was a problem uploading your picture.";
		}
	$pics=$_SERVER['DOCUMENT_ROOT']."/images/nunti/".$id_anunt_poza."_0".$i.".jpg";
	//$pics=ditchtn($pics,"_tn");
	createthumb($pics,substr($pics,0,-4)."_tn.jpg",124,93);
	
	$pics=ditchtn($pics,"tnn_");
	createnormal($pics,$pics,580,435);
	}
}
	$q->query("select * from ANUNTURI where id_anunt='".$_POST["id_anunt"]."'");
	if ($q->next_record()){
	
	$query="update ANUNTURI set date_contact='".$_POST["date_contact"]."', titlu_ro='".addslashes($_POST["titlu_ro"])."', text_ro='".addslashes($_POST["text_ro"])."', titlu_en='".addslashes($_POST["titlu_en"])."', text_en='".addslashes($_POST["text_en"])."', tip_anunt='".$_POST["tip_anunt"]."'";
		for ($l==1;$l<5;$l++)
	if (${"ok".$l}=="ok") $query.=", poza$l='$sitename/images/nunti/".$_POST["id_anunt"]."_0".$l.".jpg'";
$query.=" where id_anunt='".$_POST["id_anunt"]."'";
	}else{
		
	$query="insert into ANUNTURI (date_contact, nr_contract, titlu_ro, titlu_en, text_ro, text_en, tip_anunt, status";
	for ($l==1;$l<5;$l++)
		if (${"ok".$l}=="ok") $query.=", poza".$l;
	$query.=") values ('".$_POST["date_contact"]."','".$_POST["nr_contract"]."','".addslashes($_POST["titlu_ro"])."', '".addslashes($_POST["titlu_en"])."', '".addslashes($_POST["text_ro"])."', '".addslashes($_POST["text_en"])."','".$_POST["tip_anunt"]."','1'";
	for ($k=1;$k<5;$k++)
		if (${"ok".$k}=="ok") $query.=", '$sitename/images/nunti/".$ii."_0".$k.".jpg'";
	$query.=")";
	}
$q2->query($query);

FFileRead("scroller5newsticie.html",$ocontent1ro);
FFileRead("scroller5newsticns6.html",$ocontent2ro);
$q->query("select id_anunt, titlu_ro, titlu_en, text_ro, text_en, tip_anunt, poza1 from ANUNTURI where status='1' order by id_anunt desc");
$contor=0;
$content_row="";
$content_init="";
while ($q->next_record()){
	$text=(($q->f("poza1")!="")?"<div style=\"float:left;margin-right:2px;\"><img src=\"".substr($q->f("poza1"),0,-4)."_tn.jpg\" width=\"50\"></div>":"")."<div style=\"float:left;width:235px;\">".shorten_string($q->f("text_ro"),13)."...</div>";
	$q2->query("select url_tip_anunt from TIP_ANUNTURI where id_tip_anunt='".$q->f("tip_anunt")."'");
	$q2->next_record();
	$url_tip_anunt=$q2->f("url_tip_anunt");
	$link="/servicii_nunti/".$url_tip_anunt."-".$q->f("id_anunt");
	$content_row.='titlea['.$contor.'] = "'.$q->f("titlu_ro").'";texta['.$contor.'] = \''.$text.'\';linka['.$contor.'] = "'.$link.'";trgfrma['.$contor.'] = "_parent";';
	$content_init.="cyposarr[$contor]=$contor;";
	$contor++;
}
$content_row=$content_init."\n".$content_row."\n"."var mc=$contor;";
$ocontent1ro=str_replace("{de_inlocuit}",$content_row,$ocontent1ro);
$ocontent2ro=str_replace("{de_inlocuit}",$content_row,$ocontent2ro);
$fp=fopen("$serverpath/fanunturi/scroller5newsticie.js","w");
fwrite($fp,$ocontent1ro);
fclose($fp);
chmod ("$serverpath/fanunturi/scroller5newsticie.js", 0777);

$fp=fopen("$serverpath/fanunturi/scroller5newsticns6.js","w");
fwrite($fp,$ocontent2ro);
fclose($fp);
chmod ("$serverpath/fanunturi/scroller5newsticns6.js", 0777);
}
		header("Location:index.php?action=anunturi");
		break;	
}
	FFileRead("template.main.htm",$main);
	$main=str_replace("{content}",$content,$main);
	$main=str_replace("{title}",$title,$main);
	$main=str_replace("{sitename}",$sitename,$main);
	$main=str_replace("{webmasteremail}",$webmasteremail,$main);
	echo $main;
?>