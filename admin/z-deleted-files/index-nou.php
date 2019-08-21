<?php

include ("../functions.php");
$q = new Cdb;
$q2 = new Cdb;
$q3 = new Cdb;
$t = new Cdb;
$serverpath = $_SERVER['DOCUMENT_ROOT'];
$sitename = "http://" . $_SERVER['SERVER_NAME'];
$sitename_title = "Admin Transfer Aeroport";
$current_date = date('Y-m-d H:i:s');

if (!isset($_GET["action"]))
    $_GET["action"] = "tari";

switch ($_GET["action"]) {
    case "tari":
        FFileRead("template.tari.html", $content);
        $query = "select * from TARA";
        if (isset($_GET["order"]))
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_tara";
        if (isset($_GET["rule"]) != "")
            $query.=" " . $_GET["rule"];
        else
            $query.=" asc";
        $q->query($query);

        $rows = "";
        $i = 0;
        while ($q->next_record()) {
            $i++;
            if ($q->f("status") == 1) {
                $status = "Activ";
                $bg_status = "";
            } else {
                $status = "Inactiv";
                $bg_status = "bgcolor=\"000000\"";
            }
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=edit_tara&id_tara=" . $q->f("id_tara") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv&id_tara=" . $q->f("id_tara") . "'>" . $q->f("id_tara") . "&nbsp;$status</a></td>
	<td>" . $q->f("nume_tara") . "</td>
	<td><input type=checkbox name=check[" . $q->f("id_tara") . "]></td>
	</tr>";
        }
        $query = "select * from TARA where id_tara>0 and status='1'";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "add_tara":
        FFileRead("template.add_tara.html", $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_tara":
        $q->query("select * from TARA where nume_tara='" . $_POST["tara"] . "'");
        if ($q->next_record()) {
            $err = "Aceasta tara exista deja in lista";
        } elseif ($_POST["tara"] != "") {
            $q->query("insert into TARA set nume_tara='" . $_POST["tara"] . "', status='1'");
            $err = "Tara adaugata cu succes";
        }
        header("Location:index.php?action=add_tara&err=$err");
        break;
    case "edit_tara":
        FFileRead("template.edit_tara.html", $content);
        $q->query("select * from TARA where id_tara='" . $_GET["id_tara"] . "'");
        $q->next_record();
        $content = str_replace("{nume_tara}", $q->f("nume_tara"), $content);
        $content = str_replace("{id_tara}", $q->f("id_tara"), $content);
        $content = str_replace("{error}", isset($_GET["err"]) ? $_GET["err"] : '', $content);
        break;
    case "do_edit_tara":
        $q->query("update TARA set nume_tara='" . $_POST["tara"] . "', status='1' where id_tara='" . $_POST["id_tara"] . "'");
        $err = "Tara modificata cu succes";

        header("Location:index.php?action=edit_tara&err=$err");
        break;
    case "delete_tara":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $query = "select id_aeroport from AEROPORT where id_tara='$x'";
                $q->query($query);
                while ($q->next_record()) {
                    $id_aeroport = $q->f("id_aeroport");
                    $q->query("delete from LEGATURI where id_aeroport='$id_aeroport'");
                }
                $query = "delete from TARA where id_tara='$x'";
                $q->query($query);
                $query = "delete from AEROPORT where id_tara='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=tari");
        break;
    case "ao":
        FFileRead("template.ao.html", $content);
        $query = "select * from AEROPORT";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_aeroport";
        if ($_GET["rule"] != "")
            $query.=" " . $_GET["rule"];
        else
            $query.=" asc";
        $q->query($query);

        $rows = "";
        $i = 0;
        while ($q->next_record()) {
            $i++;
            if ($q->f("status") == 1) {
                $status = "Activ";
                $bg_status = "";
            } else {
                $status = "Inactiv";
                $bg_status = "bgcolor=\"000000\"";
            }
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=edit_ao&id_aeroport=" . $q->f("id_aeroport") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_ao&id_aeroport=" . $q->f("id_aeroport") . "'>" . $q->f("id_aeroport") . "&nbsp;$status</a></td>
	<td>" . $q->f("label") . "</td><td>" . $q->f("nume_aeroport") . "</td><td>" . $q->f("url_aeroport") . "</td>	
	<td><input type=checkbox name=check[" . $q->f("id_aeroport") . "]></td>
	</tr>";
        }
        $query = "select * from AEROPORT where id_aeroport>0 and status='1'";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "add_ao":
        FFileRead("template.add_ao.html", $content);
        $q->query("select * from TARA order by nume_tara asc");
        $tari = "";
        while ($q->next_record())
            $tari.="<option value=\"" . $q->f("id_tara") . "\">" . $q->f("nume_tara") . "</option>";
        $content = str_replace("{tari}", $tari, $content);
        $q->query("select distinct label from DESTINATIE order by label asc");
        $label = "";
        while ($q->next_record())
            $label.="<option value=\"" . $q->f("label") . "\">" . $q->f("label") . "</option>";
        $content = str_replace("{label}", $label, $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_ao":
        $label = ($_POST["label"] != "") ? $_POST["label"] : $_POST["labelnou"];
        if ($label == "")
            $err = "Labelul nu poate lipsi";
        else {
            $q->query("select * from AEROPORT where nume_aeroport='" . $_POST["nume_aeroport"] . "' and id_tara='" . $_POST["id_tara"] . "'");
            if ($q->next_record()) {
                $err = "Acest aeroport / oras exista deja in lista";
            } elseif ($_POST["nume_aeroport"] == "" || $_POST["url_aeroport"] == "")
                $err = "Nume aeroport si url aeroport nu pot lipsi.";
            else {
                $q->query("insert into AEROPORT set id_tara='" . $_POST["tara"] . "',label='" . $_POST["label"] . "',nume_aeroport='" . $_POST["nume_aeroport"] . "', url_aeroport='" . $_POST["url_aeroport"] . "', status='1'");
                $err = "Aeroport / Oras adaugat cu succes";
            }
        }
        header("Location:index.php?action=add_ao&err=$err");
        break;
    case "edit_ao":
        FFileRead("template.edit_ao.html", $content);
        $q->query("select * from AEROPORT where id_aeroport='" . $_GET["id_aeroport"] . "'");
        $q->next_record();
        $q2->query("select * from TARA order by nume_tara asc");
        while ($q2->next_record()) {
            if ($q->f("id_tara") == $q2->f("id_tara"))
                $selected = "selected";
            else
                $selected = "";
            $tari.="<option $selected value=\"" . $q2->f("id_tara") . "\">" . $q2->f("nume_tara") . "</option>";
        }
        $content = str_replace("{tari}", $tari, $content);
        $content = str_replace("{label}", $q->f("label"), $content);
        $content = str_replace("{nume_aeroport}", $q->f("nume_aeroport"), $content);
        $content = str_replace("{url_aeroport}", $q->f("url_aeroport"), $content);
        $content = str_replace("{id_aeroport}", $q->f("id_aeroport"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_ao":
        $q->query("select * from AEROPORT where nume_aeroport='" . $_POST["nume_aeroport"] . "' and id_tara='" . $_POST["id_tara"] . "'");
        if ($q->next_record()) {
            $err = "Acest aeroport / oras exista deja in lista";
        } elseif ($_POST["nume_aeroport"] == "" || $_POST["url_aeroport"] == "" || $_POST["label"] == "")
            $err = "Label, nume aeroport si url aeroport nu pot lipsi.";
        else {
            $q->query("update AEROPORT set id_tara='" . $_POST["tara"] . "',label='" . $_POST["label"] . "',nume_aeroport='" . $_POST["nume_aeroport"] . "', url_aeroport='" . $_POST["url_aeroport"] . "', status='1' where id_aeroport='" . $_POST["id_aeroport"] . "'");
            $err = "Aeroport / Oras modificat cu succes";
        }
        header("Location:index.php?action=add_ao&err=$err");
        break;
    case "delete_ao":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI where id_aeroport='$x'");
                $query = "delete from AEROPORT where id_aeroport='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=ao");
        break;
    case "destinatie":
        FFileRead("template.destinatie.html", $content);
        $query = "select * from DESTINATIE";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_destinatie";
        if ($_GET["rule"] != "")
            $query.=" " . $_GET["rule"];
        else
            $query.=" asc";
        $q->query($query);

        $rows = "";
        $i = 0;
        while ($q->next_record()) {
            $i++;
            if ($q->f("status") == 1) {
                $status = "Activ";
                $bg_status = "";
            } else {
                $status = "Inactiv";
                $bg_status = "bgcolor=\"000000\"";
            }
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=edit_destinatie&id_destinatie=" . $q->f("id_destinatie") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_destinatie&id_destinatie=" . $q->f("id_destinatie") . "'>" . $q->f("id_destinatie") . "&nbsp;$status</a></td>
	
	<td>" . $q->f("label") . "</td><td>" . $q->f("nume_destinatie") . "</td><td>" . $q->f("url_destinatie") . "</td>	
    <td><input type=checkbox name=check[" . $q->f("id_destinatie") . "]></td>
	</tr>";
        }
        $query = "select * from DESTINATIE where id_destinatie>0 and status='1'";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "add_destinatie":
        FFileRead("template.add_destinatie.html", $content);
        $q->query("select distinct label from DESTINATIE order by label asc");
        $label = "";
        while ($q->next_record())
            $label.="<option value=\"" . $q->f("label") . "\">" . $q->f("label") . "</option>";
        $content = str_replace("{label}", $label, $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_destinatie":
        $label = ($_POST["label"] != "") ? $_POST["label"] : $_POST["labelnou"];
        if ($label == "")
            $err = "Labelul nu poate lipsi";
        else {
            $q->query("select * from DESTINATIE where nume_destinatie='" . $_POST["nume_destinatie"] . "'");
            if ($q->next_record()) {
                $err = "Aceasta destinatie exista deja in lista";
            } elseif ($_POST["nume_destinatie"] == "" || $_POST["url_destinatie"] == "")
                $err = "Nume destinatie si url destinatie nu pot lipsi.";
            else {
                $q->query("insert into DESTINATIE set label='" . $label . "',nume_destinatie='" . $_POST["nume_destinatie"] . "', url_destinatie='" . $_POST["url_destinatie"] . "', status='1'");
                $err = "Destinatie adaugata cu succes";
            }
        }
        header("Location:index.php?action=add_destinatie&err=$err");
        break;
    case "edit_destinatie":
        FFileRead("template.edit_destinatie.html", $content);
        $q->query("select * from DESTINATIE where id_destinatie='" . $_GET["id_destinatie"] . "'");
        $q->next_record();

        $content = str_replace("{label}", $q->f("label"), $content);
        $content = str_replace("{nume_destinatie}", $q->f("nume_destinatie"), $content);
        $content = str_replace("{extra_info}", $q->f("extra_info"), $content);
        $content = str_replace("{url_destinatie}", $q->f("url_destinatie"), $content);
        $content = str_replace("{id_destinatie}", $q->f("id_destinatie"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_destinatie":
        if ($_POST["label"] == "")
            $err = "Labelul nu poate lipsi";
        else {
            if ($_POST["nume_destinatie"] == "" || $_POST["url_destinatie"] == "")
                $err = "Nume destinatie si url destinatie nu pot lipsi.";
            else {
                $q->query("update DESTINATIE set label='" . $_POST["label"] . "',nume_destinatie='" . $_POST["nume_destinatie"] . "', url_destinatie='" . $_POST["url_destinatie"] . "', extra_info='" . $_POST["extra_info"] . "', status='1' where id_destinatie='" . $_POST["id_destinatie"] . "'");
                $err = "Destinatie modificata cu succes";
            }
        }
        header("Location:index.php?action=destinatie&err=$err");
        break;
    case "delete_destinatie":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI where id_destinatie='$x'");
                $query = "delete from DESTINATIE where id_destinatie='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=destinatie");
        break;
    case "legaturi":
        $title = $sitename_title . " Legaturi";
        FFileRead("template.legaturi.html", $content);
        $q->query("select id_aeroport,nume_aeroport from AEROPORT where status='1'");
        $aeroporturi = "";
        while ($q->next_record())
            $aeroporturi.="<option value=\"" . $q->f("id_aeroport") . "\">" . $q->f("nume_aeroport") . "</option>";

        $content = str_replace("{aeroporturi}", $aeroporturi, $content);
        if ($_POST["cauta"] != "" || $_GET["cauta"] != "") {
            $id_aeroport = (isset($_POST["cauta"])) ? $_POST["cauta"] : $_GET["cauta"];
            FFileRead("template.legaturi2.html", $content2);
            $q->query("select nume_aeroport from AEROPORT where id_aeroport='$id_aeroport'");
            $q->next_record();
            $nume_aeroport = $q->f("nume_aeroport");
            $content.=$content2;
            $content = str_replace("{error}", $_GET["err"], $content);
            if ($_GET["cauta"] != "")
                $query = "select * from LEGATURI l join AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_aeroport='" . $_GET["cauta"] . "'";
            else {
                if ($_POST["cauta"] != "")
                    $query = "select * from LEGATURI l join AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_aeroport='" . $_POST["cauta"] . "'";
            }
            if ($_GET["order1"])
                $query.=" order by " . $_GET["order1"];
            else
                $query.=" order by nume_destinatie";
            if ($_GET["rule1"])
                $query.=" " . $_GET["rule1"];
            else
                $query.=" asc";
            $q->query($query);
            $rows = "";
            $legaturi_existente = array();
            $i = 0;
            while ($q->next_record()) {
                $i++;
                $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
			<td align=center><a href='index.php?action=edit_legaturi&id_legaturi=" . $q->f("id_legaturi") . "'>Edit</a></td>
			<td align=center><a href='index.php?action=preturi&id_legaturi=" . $q->f("id_legaturi") . "'>Preturi</a></td>
			<td>" . $q->f("nume_aeroport") . "</td><td>" . $q->f("nume_destinatie") . "</td><td>" . $q->f("km") . "</td>
			<td>" . $q->f("timp") . "</td><td>" . $q->f("titlu_pagina") . "</td><td>" . $q->f("meta_pagina") . "</td>
			<td><input type=checkbox name=check[" . $q->f("id_legaturi") . "]></td></tr>";
                $legaturi_existente[] = $q->f("id_destinatie");
            }
            $query = "select * from DESTINATIE";
            if ($_GET["order2"])
                $query.=" order by " . $_GET["order2"];
            else
                $query.=" order by nume_destinatie";

            if ($_GET["rule2"])
                $query.=" " . $_GET["rule2"];
            else
                $query.=" asc";
            $q2->query($query);
            $rows2 = "";
            $j = 0;
            while ($q2->next_record()) {
                $j++;
                if (!in_array($q2->f("id_destinatie"), $legaturi_existente)) {
                    $rows2.="<tr id=\"celll$j\" onMouseOver=\"document.all.celll$j.bgColor = '#0099ff'\" onMouseOut=\"document.all.celll$j.bgColor ='#ffffff'\">
		<td>" . $q2->f("nume_destinatie") . "</td><td><input type=checkbox name=check[" . $q2->f("id_destinatie") . "]></td></tr>";
                }
            }
            $content = str_replace("{nume_aeroport}", $nume_aeroport, $content);
            $content = str_replace("{id_aeroport}", $id_aeroport, $content);
            $content = str_replace("{rows}", $rows, $content);
            $content = str_replace("{rows2}", $rows2, $content);
        }
        break;
    case "do_add_legaturi":
        foreach ($_POST["check"] as $z => $value) {
            if ($z > 0)
                $q->query("insert into LEGATURI set id_aeroport='" . $_POST["id_aeroport"] . "',id_destinatie='$z'");
        }
        header("Location:index.php?action=legaturi&cauta=" . $_POST["id_aeroport"]);
        break;
    case "edit_legaturi":
        FFileRead("template.edit_legaturi.html", $content);
        $title = $sitename_title . " - Editeaza legatura";
        $q->query("select * from LEGATURI l join AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d on l.id_destinatie=d.id_destinatie where l.id_legaturi='" . $_GET["id_legaturi"] . "'");
        $q->next_record();

        $content = str_replace("{nume_aeroport}", $q->f("nume_aeroport"), $content);
        $content = str_replace("{nume_destinatie}", $q->f("nume_destinatie"), $content);
        $content = str_replace("{km}", $q->f("km"), $content);
        $content = str_replace("{timp}", $q->f("timp"), $content);
        $content = str_replace("{titlu_pagina}", $q->f("titlu_pagina"), $content);
        $content = str_replace("{meta_pagina}", $q->f("meta_pagina"), $content);
        $content = str_replace("{id_legaturi}", $q->f("id_legaturi"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_legaturi":
        $q->query("update LEGATURI set km='" . $_POST["km"] . "',timp='" . $_POST["timp"] . "', titlu_pagina='" . $_POST["titlu_pagina"] . "', meta_pagina='" . $_POST["meta_pagina"] . "' where id_legaturi='" . $_POST["id_legaturi"] . "'");
        $err = "Date legatura modificate cu succes";

        header("Location:index.php?action=edit_legaturi&err=$err");
        break;
    case "delete_legaturi":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI where id_legaturi='$x'");
                $q->query($query);
            }
        }
        header("Location:index.php?action=legaturi&cauta=" . $_POST["id_aeroport"]);
        break;
    case "preturi":
        $title = $sitename_title . " Preturi";
        FFileRead("template.preturi.html", $content);

        $id_legaturi = $_REQUEST["id_legaturi"];
        $q->query("select a.id_aeroport, a.nume_aeroport,d.nume_destinatie from LEGATURI l JOIN AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d ON l.id_destinatie=d.id_destinatie where id_legaturi='$id_legaturi'");
        $q->next_record();
        $content = str_replace("{nume_aeroport}", $q->f("nume_aeroport"), $content);
        $content = str_replace("{id_aeroport}", $q->f("id_aeroport"), $content);
        $content = str_replace("{nume_destinatie}", $q->f("nume_destinatie"), $content);
        $q->query("select * from PRETURI where id_legaturi='$id_legaturi'");

        $content = str_replace("{error}", $_GET["err"], $content);
        $rows = "";
        $preturi_existente = array();
        $i = 0;
        $shuttle_exists = 0;
        while ($q->next_record()) {
            if ($q->f("id_auto") != 0) {
                $q2->query("select nume_auto from AUTO where id_auto='" . $q->f("id_auto") . "'");
                $q2->next_record();
                $nume_auto = $q2->f("nume_auto");
            } else {
                $shuttle_exists = 1;
                $nume_auto = "Shuttle";
                $preturi_existente[] = 0;
            }
            $i++;
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
			<td align=center><a href='index.php?action=edit_preturi&id_legaturi=" . $q->f("id_legaturi") . "&id_auto=" . $q->f("id_auto") . "'>Edit</a></td>
			<td>" . $nume_auto . "</td><td>" . $q->f("pret") . "</td><td><input type=checkbox name=check[" . $q->f("id_auto") . "]></td></tr>";
            $preturi_existente[] = $q->f("id_auto");
        }
        $q2->query("select * from AUTO order by id_auto asc");
        $rows2 = "";
        $j = 0;
        while ($q2->next_record()) {
            $j++;
            if (!in_array($q2->f("id_auto"), $preturi_existente)) {
                $rows2.="<tr id=\"celll$j\" onMouseOver=\"document.all.celll$j.bgColor = '#0099ff'\" onMouseOut=\"document.all.celll$j.bgColor ='#ffffff'\">
		<td>" . $q2->f("nume_auto") . "</td><td><input type=checkbox name=check[" . $q2->f("id_auto") . "]></td></tr>";
            }
        }
        if ($shuttle_exists == 0) {//daca nu exista shuttle pt. destinatia respectiva, sa se poata selecta shuttle
            $rows2.="<tr id=\"celll$j\" onMouseOver=\"document.all.celll$j.bgColor = '#0099ff'\" onMouseOut=\"document.all.celll$j.bgColor ='#ffffff'\">
		<td>Shuttle</td><td><input type=checkbox name=check[0]></td></tr>";
        }
//$content=str_replace("{nume_aeroport}",$nume_aeroport,$content);
        $content = str_replace("{id_legaturi}", $id_legaturi, $content);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{rows2}", $rows2, $content);
        break;

    case "do_add_preturi":
        foreach ($_POST["check"] as $z => $value) {
            $query = "insert into PRETURI set id_legaturi='" . $_POST["id_legaturi"] . "',id_auto='$z'";
            //echo $query."<br>";
            $q->query($query);
        }
        header("Location:index.php?action=preturi&id_legaturi=" . $_POST["id_legaturi"]);
        break;
    case "edit_preturi":
        FFileRead("template.edit_preturi.html", $content);
        if ($_GET["id_auto"] == 0) {
            $query = "select * from PRETURI where id_auto='" . $_GET["id_auto"] . "' AND id_legaturi='" . $_GET["id_legaturi"] . "'";
            $content = str_replace("{nume_auto}", "Shuttle", $content);
            $q->query($query);
            $q->next_record();
            $q2->query("select * from shuttle_run where idLegatura='" . $_GET["id_legaturi"] . "'");
            $days='';
            while ($q2->next_record()){
                $content = str_replace("{checked".$q2->f("zi")."}", 'checked', $content);
                $days.=' day != '.$q2->f("zi").' &&';
            }
            $days=substr($days,0,-3);
            $content = str_replace("{removed_days}", $days, $content);
            $content = str_replace("{zile_nu_circula}", '', $content);
        } else {
            $query = "select * from PRETURI p JOIN AUTO a ON p.id_auto=a.id_auto where a.id_auto='" . $_GET["id_auto"] . "' AND p.id_legaturi='" . $_GET["id_legaturi"] . "'";
            $q->query($query);
            $q->next_record();
            $content = str_replace("{nume_auto}", $q->f("nume_auto"), $content);
            $content = str_replace("{zile_nu_circula}", 'display:none;', $content);
        }
        $content = str_replace("{pret}", $q->f("pret"), $content);
        $content = str_replace("{id_auto}", $q->f("id_auto"), $content);
        $content = str_replace("{id_legaturi}", $q->f("id_legaturi"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_preturi":
        $q->query("update PRETURI set pret='" . $_POST["pret"] . "' where id_legaturi='" . $_POST["id_legaturi"] . "' and id_auto='" . $_POST["id_auto"] . "'");
        
        $q->query("delete from shuttle_run where idLegatura='" . $_POST["id_legaturi"] . "'");
        for ($i=0;$i<7;$i++){
            if (isset($_POST["day".$i])){
                $q->query("insert into shuttle_run set idLegatura='" . $_POST["id_legaturi"] . "', zi='$i'");
            }
        }
        $err = "Date legatura modificate cu succes";

        header("Location:index.php?action=preturi&id_legaturi=" . $_POST["id_legaturi"] . "&err=$err");
        break;
    case "delete_preturi":
        if (isset($_POST["check"])) {
            foreach ($_POST["check"] as $x => $value) {
                $q->query("delete from PRETURI where id_auto='$x' and id_legaturi='" . $_POST["id_legaturi"] . "'");
                if ($x==0) {
                    $q->query("delete from shuttle_run where idLegatura='" . $_POST["id_legaturi"] . "'");
                }
            }
        }
        header("Location:index.php?action=preturi&id_legaturi=" . $_POST["id_legaturi"]);
        break;
    case "delete_legaturi":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI where id_legaturi='$x'");
                $q->query($query);
            }
        }
        header("Location:index.php?action=legaturi&id_legaturi=" . $_POST["id_legaturi"]);
        break;
    case "auto":
        FFileRead("template.auto.html", $content);
        $query = "select * from AUTO";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_auto";
        if ($_GET["rule"] != "")
            $query.=" " . $_GET["rule"];
        else
            $query.=" asc";
        $q->query($query);

        $rows = "";
        $i = 0;
        while ($q->next_record()) {
            $i++;
            if ($q->f("status") == 1) {
                $status = "Activ";
                $bg_status = "";
            } else {
                $status = "Inactiv";
                $bg_status = "bgcolor=\"000000\"";
            }
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=edit_auto&id_auto=" . $q->f("id_auto") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_auto&id_auto=" . $q->f("id_auto") . "'>" . $q->f("id_auto") . "&nbsp;$status</a></td>
	<td>" . $q->f("nume_auto") . "</td><td>" . $q->f("nr_pasageri") . "</td>
	<td><input type=checkbox name=check[" . $q->f("id_auto") . "]></td>
	</tr>";
        }
        $query = "select * from AUTO where id_auto>0 and status='1'";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "add_auto":
        FFileRead("template.add_auto.html", $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_auto":
        $q->query("select * from AUTO where nume_auto='" . $_POST["nume_auto"] . "'");
        if ($q->next_record()) {
            $err = "Acest auto exista deja in lista";
        } elseif ($_POST["nume_auto"] != "") {
            $file_dir = $serverpath . "/images/auto/";
            $query = "select id_auto from AUTO order by id_auto desc limit 0,1";
            $q->query($query);
            $q->next_record();
            $ii = $q->f("id_auto");
            $ii++;
            if (isset($_FILES["poza1"]) && $_FILES["poza1"]['size'] > 1) {
                if (trim($_FILES["poza1"]['name']) != "") {
                    $newfile = $file_dir . $ii . "_01.jpg";
                    move_uploaded_file($_FILES["poza1"]['tmp_name'], $newfile);
                    $j = 1;
                    if (isset($j) && $j == 1) {
                        $poza1 = ", poza1='/images/auto/" . $ii . "_01.jpg'";
                    }
                } else {
                    $poza1 = "";
                    $err = "Sorry, there was a problem uploading your picture.";
                }
                $pics = $_SERVER['DOCUMENT_ROOT'] . "/images/auto/" . $ii . "_01.jpg";
                createthumb($pics, substr($pics, 0, -4) . "_tn.jpg", 124, 93);
                $pics = ditchtn($pics, "tnn_");
                createnormal($pics, $pics, 580, 435);
            }
            $q->query("insert into AUTO set nume_auto='" . $_POST["nume_auto"] . "',nr_pasageri='" . $_POST["nr_pasageri"] . "' $poza1, status='1'");
            $err = "Auto adaugat cu succes";
        }
        header("Location:index.php?action=add_auto&err=$err");
        break;
    case "edit_auto":
        FFileRead("template.edit_auto.html", $content);
        $q->query("select * from AUTO where id_auto='" . $_GET["id_auto"] . "'");
        $q->next_record();
        $content = str_replace("{nume_auto}", $q->f("nume_auto"), $content);
        $content = str_replace("{nr_pasageri}", $q->f("nr_pasageri"), $content);
        $poza1 = ($q->f("poza1") != "") ? "<img src=\"/images/auto/" . $q->f("id_auto") . "_01_tn.jpg\"><br>" : "";
        $content = str_replace("{poza1}", $poza1, $content);
        $content = str_replace("{id_auto}", $q->f("id_auto"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_auto":
        if ($_POST["nume_auto"] == "" || $_POST["nr_pasageri"] == "")
            $err = "Nume auto si nr. pasageri nu pot lipsi.";
        else {
            $file_dir = $serverpath . "/images/auto/";
            if (isset($_FILES["poza1"]) && $_FILES["poza1"]['size'] > 1) {
                if (trim($_FILES["poza1"]['name']) != "") {
                    $newfile = $file_dir . $_POST["id_auto"] . "_01.jpg";
                    move_uploaded_file($_FILES["poza1"]['tmp_name'], $newfile);
                    $j = 1;
                }
                if (isset($j) && $j == 1) {
                    $poza1 = ", poza1='/images/auto/" . $_POST["id_auto"] . "_01.jpg'";
                } else {
                    if ($q->f("poza1") == "")
                        $poza1 = "";
                    $err = "Sorry, there was a problem uploading your picture.";
                }
                $pics = $_SERVER['DOCUMENT_ROOT'] . "/images/auto/" . $_POST["id_auto"] . "_01.jpg";
                createthumb($pics, substr($pics, 0, -4) . "_tn.jpg", 124, 93);

                $pics = ditchtn($pics, "tnn_");
                createnormal($pics, $pics, 580, 435);
            }
            $q->query("update AUTO set nume_auto='" . $_POST["nume_auto"] . "',nr_pasageri='" . $_POST["nr_pasageri"] . "' $poza1, status='1' where id_auto='" . $_POST["id_auto"] . "'");
            $err = "Auto modificat cu succes";
        }
        header("Location:index.php?action=edit_auto&err=$err");
        break;
    case "delete_auto":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from AUTO where id_auto='$x'");
                $query = "delete from PRETURI where id_auto='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=auto");
        break;
    case "comenzi":
        FFileRead("template.comenzi.html", $content);
        $title = $sitename_title . " - Comenzi";
        $query = "select * from ORDERS";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_order";
        if ($_GET["rule"] != "")
            $query.=" " . $_GET["rule"];
        else
            $query.=" asc";
        $q->query($query);

        $rows = "";
        $i = 0;
        while ($q->next_record()) {
            $i++;
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=detalii_comanda&id_order=" . $q->f("id_order") . "'>Detalii</a></td>
	<td align=center>" . $q->f("id_order") . "</td>
	<td>" . $q->f("numedefamilie") . " " . $q->f("prenume") . "</td>
	<td>" . (($q->f("sex") == 0) ? "m" : "f") . "</td>
	<td>" . $q->f("telefon") . "</td>
	<td>" . $q->f("email") . "</td>
	<td>" . $q->f("arrival") . "</td>
	<td>" . $q->f("passengers") . "</td>
	<td>" . $q->f("destination") . "</td>
	<td>" . (($q->f("passengers2") > 0) ? "Dus-Intors" : "One Way") . "</td>
	<td>" . $q->f("flight_departure_to") . "</td>
	<td>" . $q->f("passengers2") . "</td>
	<td>" . $q->f("price") . "</td>
	<td><input type=checkbox name=check[" . $q->f("id_order") . "]></td>
	</tr>";
        }
        $query = "select * from ORDERS where id_order>0";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "detalii_comanda":
        FFileRead("template.detalii_comanda.html", $content);
        $title = $sitename_title . " - Detalii comanda";
        $q->query("select * from ORDERS where id_order='" . $_GET["id_order"] . "'");
        $q->next_record();
        $pickup_extra = explode("|", $q->f("pickup_extra"));
        $pickup_extra2 = explode("|", $q->f("pickup_extra2"));

        $replace_array = array("id_order", "numedefamilie", "prenume", "telefon", "email", "arrival", "flight_arrival", "flight_departure_from", "flight_number", "passengers", "pickup_time", "pickup_location", "pickup_auto", "destination", "flight_departure", "flight_departure_to", "flight_number2", "passengers2", "pickup_time2", "pickup_location2", "pickup_auto2", "price");
        foreach ($replace_array as $replace) {
            $content = str_replace("{" . $replace . "}", $q->f("$replace"), $content);
        }
        $j = 0;
        for ($i = 0; $i < 9; $i++) {
            $j = $i + 1;
            $content = str_replace("{extra$j}", $pickup_extra[$i], $content);
            $content = str_replace("{extra2$j}", $pickup_extra2[$i], $content);
        }
        break;
    case "delete_comenzi":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from ORDERS where id_order='$x'");
                $q->query($query);
            }
        }
        header("Location:index.php?action=comenzi");
        break;
    case "set_activ_inactiv":
        $q->query("update TARA set status=NOT status where id_tara='" . $_GET["id_tara"] . "'");
        header("Location:index.php?action=tari");
        break;
    case "set_activ_inactiv_ao":
        $q->query("update AEROPORT set status=NOT status where id_aeroport='" . $_GET["id_aeroport"] . "'");
        header("Location:index.php?action=ao");
        break;
    case "set_activ_inactiv_destinatie":
        $q->query("update DESTINATIE set status=NOT status where id_destinatie='" . $_GET["id_destinatie"] . "'");
        header("Location:index.php?action=destinatie");
        break;
    case "set_activ_inactiv_auto":
        $q->query("update AUTO set status=NOT status where id_auto='" . $_GET["id_auto"] . "'");
        header("Location:index.php?action=auto");
        break;
}
FFileRead("template.main.htm", $main);
$main = str_replace("{content}", $content, $main);
$main = str_replace("{title}", isset($title) ? $title : 'Admin area', $main);
$main = str_replace("{sitename}", $sitename, $main);
//$main=str_replace("{webmasteremail}",$webmasteremail,$main);
echo $main;
?>