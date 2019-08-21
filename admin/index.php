<?php

include ("../functions.php");
$q = new Cdb;
$q2 = new Cdb;
$q3 = new Cdb;
$t = new Cdb;
$qauto = new Cdb;
$qauto2 = new Cdb;
$serverpath = $_SERVER['DOCUMENT_ROOT'];
$sitename = "http://" . $_SERVER['SERVER_NAME'];
$sitename_title = "Super-Admin Christian Transfer";
$current_date = date('Y-m-d H:i:s');

if (!isset($_GET["action"]))
    $_GET["action"] = "tari";

switch ($_GET["action"]) {
    //Index - works on Countries page = template.tari.html
		case "tari":
        FFileRead("template.tari.html", $content);
        $query = "select * from TARA";
        if (isset($_GET["order"]))
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_tara";
        if (isset($_GET["rule"]))
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
        $content = str_replace("{ianuarie}", $q->f("ianuarie"), $content);
        $content = str_replace("{februarie}", $q->f("februarie"), $content);
        $content = str_replace("{martie}", $q->f("martie"), $content);
        $content = str_replace("{aprilie}", $q->f("aprilie"), $content);
        $content = str_replace("{mai}", $q->f("aprilie"), $content);
        $content = str_replace("{iunie}", $q->f("iunie"), $content);
        $content = str_replace("{iulie}", $q->f("iulie"), $content);
        $content = str_replace("{august}", $q->f("august"), $content);
        $content = str_replace("{septembrie}", $q->f("septembrie"), $content);
        $content = str_replace("{octombrie}", $q->f("octombrie"), $content);
        $content = str_replace("{noiembrie}", $q->f("noiembrie"), $content);
        $content = str_replace("{decembrie}", $q->f("decembrie"), $content);
        $content = str_replace("{unu}", $q->f("unu"), $content);
        $content = str_replace("{doi}", $q->f("doi"), $content);
        $content = str_replace("{sapte}", $q->f("sapte"), $content);
        $content = str_replace("{id_tara}", $q->f("id_tara"), $content);
        $content = str_replace("{error}", isset($_GET["err"]) ? $_GET["err"] : '', $content);
        break;
    case "do_edit_tara":
        $q->query("update TARA set nume_tara='" . $_POST["tara"] . "', "
                . "ianuarie='" . $_POST["ian"] . "', "
                . "februarie='" . $_POST["feb"] . "', "
                . "martie='" . $_POST["mar"] . "', "
                . "aprilie='" . $_POST["apr"] . "', "
                . "mai='" . $_POST["mai"] . "', "
                . "iunie='" . $_POST["iun"] . "', "
                . "iulie='" . $_POST["iul"] . "', "
                . "august='" . $_POST["aug"] . "', "
                . "septembrie='" . $_POST["sep"] . "', "
                . "octombrie='" . $_POST["oct"] . "', "
                . "noiembrie='" . $_POST["noi"] . "', "
                . "decembrie='" . $_POST["dec"] . "', "
                . "unu='" . $_POST["unu"] . "', "
                . "doi='" . $_POST["doi"] . "', "
                . "sapte='" . $_POST["sapte"] . "', 
                    status='1' where id_tara='" . $_POST["id_tara"] . "'");
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
    //aeroporturi si orase
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
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
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
        $q->query("select * from TARA where id_tara<>34 order by nume_tara asc");
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
            $q->query("select * from AEROPORT where nume_aeroport='" . $_POST["nume_aeroport"] . "' and id_tara='" . $_POST["tara"] . "'");
            if ($q->next_record()) {
                $err = "Acest aeroport / oras exista deja in lista";
            } elseif ($_POST["nume_aeroport"] == "" || $_POST["url_aeroport"] == "")
                $err = "Nume aeroport si url aeroport nu pot lipsi.";
            else {
                $q->query("insert into AEROPORT set id_tara='" . $_POST["tara"] . "',label='" . $label . "',nume_aeroport='" . $_POST["nume_aeroport"] . "', url_aeroport='" . $_POST["url_aeroport"] . "', status='1', "
                . "ianuarie='" . $_POST["ian"] . "', "
                . "februarie='" . $_POST["feb"] . "', "
                . "martie='" . $_POST["mar"] . "', "
                . "aprilie='" . $_POST["apr"] . "', "
                . "mai='" . $_POST["mai"] . "', "
                . "iunie='" . $_POST["iun"] . "', "
                . "iulie='" . $_POST["iul"] . "', "
                . "august='" . $_POST["aug"] . "', "
                . "septembrie='" . $_POST["sep"] . "', "
                . "octombrie='" . $_POST["oct"] . "', "
                . "noiembrie='" . $_POST["noi"] . "', "
                . "decembrie='" . $_POST["dec"] . "', "
                . "unu='" . $_POST["unu"] . "', "
                . "doi='" . $_POST["doi"] . "', "
                . "sapte='" . $_POST["sapte"] . "'");
                $err = "Aeroport / Oras adaugat cu succes";
            }
        }
        header("Location:index.php?action=add_ao&err=$err");
        break;
    case "edit_ao":
        FFileRead("template.edit_ao.html", $content);
        $q->query("select * from AEROPORT where id_aeroport='" . $_GET["id_aeroport"] . "'");
        $q->next_record();
        $q2->query("select * from TARA where id_tara<>34 order by nume_tara asc");
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
		
		$content = str_replace("{ianuarie}", $q->f("ianuarie"), $content);
        $content = str_replace("{februarie}", $q->f("februarie"), $content);
        $content = str_replace("{martie}", $q->f("martie"), $content);
        $content = str_replace("{aprilie}", $q->f("aprilie"), $content);
        $content = str_replace("{mai}", $q->f("mai"), $content);
        $content = str_replace("{iunie}", $q->f("iunie"), $content);
        $content = str_replace("{iulie}", $q->f("iulie"), $content);
        $content = str_replace("{august}", $q->f("august"), $content);
        $content = str_replace("{septembrie}", $q->f("septembrie"), $content);
        $content = str_replace("{octombrie}", $q->f("octombrie"), $content);
        $content = str_replace("{noiembrie}", $q->f("noiembrie"), $content);
        $content = str_replace("{decembrie}", $q->f("decembrie"), $content);
        $content = str_replace("{unu}", $q->f("unu"), $content);
        $content = str_replace("{doi}", $q->f("doi"), $content);
        $content = str_replace("{sapte}", $q->f("sapte"), $content);
		
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_ao":
        $q->query("select * from AEROPORT where nume_aeroport='" . $_POST["nume_aeroport"] . "' and id_tara='" . $_POST["tara"] . "' and id_aeroport != '" . $_POST["id_aeroport"]."'");
        if ($q->next_record()) {
            $err = "Acest aeroport / oras exista deja in lista";
        } elseif ($_POST["nume_aeroport"] == "" || $_POST["url_aeroport"] == "" || $_POST["label"] == "")
            $err = "Label, nume aeroport si url aeroport nu pot lipsi.";
        else {
            $q->query("update AEROPORT set id_tara='" . $_POST["tara"] . "',label='" . $_POST["label"] . "',nume_aeroport='" . $_POST["nume_aeroport"] . "', url_aeroport='" . $_POST["url_aeroport"] . "', status='1', "
				. "ianuarie='" . $_POST["ian"] . "', "
                . "februarie='" . $_POST["feb"] . "', "
                . "martie='" . $_POST["mar"] . "', "
                . "aprilie='" . $_POST["apr"] . "', "
                . "mai='" . $_POST["mai"] . "', "
                . "iunie='" . $_POST["iun"] . "', "
                . "iulie='" . $_POST["iul"] . "', "
                . "august='" . $_POST["aug"] . "', "
                . "septembrie='" . $_POST["sep"] . "', "
                . "octombrie='" . $_POST["oct"] . "', "
                . "noiembrie='" . $_POST["noi"] . "', "
                . "decembrie='" . $_POST["dec"] . "', "
                . "unu='" . $_POST["unu"] . "', "
                . "doi='" . $_POST["doi"] . "', "
                . "sapte='" . $_POST["sapte"] . "' where id_aeroport='" . $_POST["id_aeroport"] . "'");
            $err = "Aeroport / Oras modificat cu succes";
        }
        //die();
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
        
        //uk part start
        
        case "ao_uk":
        FFileRead("template.ao.uk.html", $content);        
        $query = "select * from AEROPORT_UK";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_aeroport";
        if ($_GET["rule"] != "")
            $query.=" " . $_GET["rule"];
        else
            $query.=" asc";
        $q->query($query);

        while ($q->next_record()) {
            $i++;
            if ($q->f("status") == 1) {
                $status = "Activ";
                $bg_status = "";
            } else {
                $status = "Inactiv";
                $bg_status = "bgcolor=\"000000\"";
            }
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
	<td align=center><a href='index.php?action=edit_ao_uk&id_aeroport=" . $q->f("id_aeroport") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_ao_uk&id_aeroport=" . $q->f("id_aeroport") . "&id_tara=34'>" . $q->f("id_aeroport") . "&nbsp;$status</a></td>
	<td>" . $q->f("label") . "</td><td>" . $q->f("nume_aeroport") . "</td><td>" . $q->f("url_aeroport") . "</td>	
	<td><input type=checkbox name=check_uk[" . $q->f("id_aeroport") . "]></td>
	</tr>";
        }
        
        $query = "SELECT  COUNT( * ) AS TotalA FROM AEROPORT_UK where status = '1'";
        $q->query($query);
        $q->next_record();
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->f("TotalA"), $content);
        break;
   
        case "add_ao_uk":
        FFileRead("template.add_ao.uk.html", $content);
        $q->query("select * from TARA where id_tara=34");
        $tari = "";
        while ($q->next_record())
            $tari.="<option value=\"" . $q->f("id_tara") . "\">" . $q->f("nume_tara") . "</option>";
        $content = str_replace("{tari}", $tari, $content);
        $q->query("select distinct label from AEROPORT_UK order by label asc");
        $label = "";
        while ($q->next_record())
            $label.="<option value=\"" . $q->f("label") . "\">" . $q->f("label") . "</option>";
        $content = str_replace("{label}", $label, $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
        
    case "do_add_ao_uk":
        $label = ($_POST["label"] != "") ? $_POST["label"] : $_POST["labelnou"];
        if ($label == "")
            $err = "Labelul nu poate lipsi";
        else {

            $q->query("select * from AEROPORT_UK where nume_aeroport='" . $_POST["nume_aeroport"] . "' and id_tara='" . $_POST["tara"] . "'");
            if ($q->next_record()) {
                $err = "Acest aeroport / oras exista deja in lista";
            } elseif ($_POST["nume_aeroport"] == "" || $_POST["url_aeroport"] == "")
                $err = "Nume aeroport si url aeroport nu pot lipsi.";
            else {
                $q->query("insert into AEROPORT_UK set id_tara='" . $_POST["tara"] . "',label='" . $label . "',nume_aeroport='" . $_POST["nume_aeroport"] . "', url_aeroport='" . $_POST["url_aeroport"] . "', status='1', "
                    . "ianuarie='" . $_POST["ian"] . "', "
                    . "februarie='" . $_POST["feb"] . "', "
                    . "martie='" . $_POST["mar"] . "', "
                    . "aprilie='" . $_POST["apr"] . "', "
                    . "mai='" . $_POST["mai"] . "', "
                    . "iunie='" . $_POST["iun"] . "', "
                    . "iulie='" . $_POST["iul"] . "', "
                    . "august='" . $_POST["aug"] . "', "
                    . "septembrie='" . $_POST["sep"] . "', "
                    . "octombrie='" . $_POST["oct"] . "', "
                    . "noiembrie='" . $_POST["noi"] . "', "
                    . "decembrie='" . $_POST["dec"] . "', "
                    . "unu='" . $_POST["unu"] . "', "
                    . "doi='" . $_POST["doi"] . "', "
                    . "sapte='" . $_POST["sapte"] . "'");
                $err = "Aeroport / Oras adaugat cu succes";
            }
        }
        header("Location:index.php?action=add_ao_uk&err=$err");
        break;
        
    case "edit_ao_uk":
        FFileRead("template.edit_ao.uk.html", $content);
        $uk='';
        $q->query("select * from AEROPORT_UK where id_aeroport='" . $_GET["id_aeroport"] . "'");
        $q->next_record();
        $q2->query("select * from TARA where id_tara=34");
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
        
        $content = str_replace("{ianuarie}", $q->f("ianuarie"), $content);
        $content = str_replace("{februarie}", $q->f("februarie"), $content);
        $content = str_replace("{martie}", $q->f("martie"), $content);
        $content = str_replace("{aprilie}", $q->f("aprilie"), $content);
        $content = str_replace("{mai}", $q->f("mai"), $content);
        $content = str_replace("{iunie}", $q->f("iunie"), $content);
        $content = str_replace("{iulie}", $q->f("iulie"), $content);
        $content = str_replace("{august}", $q->f("august"), $content);
        $content = str_replace("{septembrie}", $q->f("septembrie"), $content);
        $content = str_replace("{octombrie}", $q->f("octombrie"), $content);
        $content = str_replace("{noiembrie}", $q->f("noiembrie"), $content);
        $content = str_replace("{decembrie}", $q->f("decembrie"), $content);
        $content = str_replace("{unu}", $q->f("unu"), $content);
        $content = str_replace("{doi}", $q->f("doi"), $content);
        $content = str_replace("{sapte}", $q->f("sapte"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_ao_uk":
        $q->query("select * from AEROPORT_UK where nume_aeroport='" . $_POST["nume_aeroport"] . "' and id_tara='" . $_POST["tara"] . "'");
        if ($q->next_record()) {
            $err = "Acest aeroport / oras exista deja in lista";
        } elseif ($_POST["nume_aeroport"] == "" || $_POST["url_aeroport"] == "" || $_POST["label"] == "")
            $err = "Label, nume aeroport si url aeroport nu pot lipsi.";
        else {
            $q->query("update AEROPORT_UK set id_tara='" . $_POST["tara"] . "',label='" . $_POST["label"] . "',nume_aeroport='" . $_POST["nume_aeroport"] . "', url_aeroport='" . $_POST["url_aeroport"] . "', status='1', "
                . "ianuarie='" . $_POST["ian"] . "', "
                . "februarie='" . $_POST["feb"] . "', "
                . "martie='" . $_POST["mar"] . "', "
                . "aprilie='" . $_POST["apr"] . "', "
                . "mai='" . $_POST["mai"] . "', "
                . "iunie='" . $_POST["iun"] . "', "
                . "iulie='" . $_POST["iul"] . "', "
                . "august='" . $_POST["aug"] . "', "
                . "septembrie='" . $_POST["sep"] . "', "
                . "octombrie='" . $_POST["oct"] . "', "
                . "noiembrie='" . $_POST["noi"] . "', "
                . "decembrie='" . $_POST["dec"] . "', "
                . "unu='" . $_POST["unu"] . "', "
                . "doi='" . $_POST["doi"] . "', "
                . "sapte='" . $_POST["sapte"] . "' where id_aeroport='" . $_POST["id_aeroport"] . "'");
            $err = "Aeroport / Oras modificat cu succes";
        }
        header("Location:index.php?action=add_ao_uk&err=$err");
        break;
        
    case "delete_ao_uk":
        foreach ($_POST["check_uk"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI_UK where id_aeroport='$x'");
                $query = "delete from AEROPORT_UK where id_aeroport='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=ao_uk");
        break;
        
        //uk part end
    //statii
    case "statii":
        FFileRead("template.statii.html", $content);
        $query = "select * from STATII";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_statie";
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
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
	<td align=center><a href='index.php?action=edit_statii&id_statie=" . $q->f("id_statie") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_statii&id_statie=" . $q->f("id_statie") . "'>" . $q->f("id_statie") . "&nbsp;$status</a></td>
	<td>" . $q->f("nume_statie") . "</td>	
	<td><input type=checkbox name=check[" . $q->f("id_statie") . "]></td>
	</tr>";
        }
        $query = "select * from STATII where id_statie>0 and status='1'";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "add_statii":
        FFileRead("template.add_statii.html", $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_statii":
        $q->query("select * from STATII where nume_statie='" . $_POST["nume_statie"] . "'");
        if ($q->next_record()) {
            $err = "Aceasta statie exista deja in lista";
        } elseif ($_POST["nume_statie"] == "")
            $err = "Nume statie nu poate lipsi.";
        else {
            $q->query("insert into STATII set nume_statie='" . $_POST["nume_statie"] . "', status='1'");
            $err = "Statie adaugata cu succes";
        }

        header("Location:index.php?action=add_statii&err=$err");
        break;
    case "edit_statii":
        FFileRead("template.edit_statii.html", $content);
        $q->query("select * from STATII where id_statie='" . $_GET["id_statie"] . "'");
        $q->next_record();

        $content = str_replace("{nume_statie}", $q->f("nume_statie"), $content);
        $content = str_replace("{id_statie}", $q->f("id_statie"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_statii":
        $q->query("select * from STATII where nume_statie='" . $_POST["nume_statie"] . "'");
        if ($q->nf() > 1) {
            $err = "Aceasta statie exista deja in lista";
        } elseif ($_POST["nume_statie"] == "")
            $err = "Nume statie nu poate lipsi.";
        else {
            $q->query("update STATII set nume_statie='" . $_POST["nume_statie"] . "', status='1' where id_statie='" . $_POST["id_statie"] . "'");
            $err = "Statie modificata cu succes";
        }
        header("Location:index.php?action=add_statii&err=$err");
        break;
    case "delete_statii":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI_STATII where id_statie_pornire='$x' OR id_statie_sosire='$x'");
                $query = "delete from STATII where id_statie='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=statii");
        break;
    //statii UK start
    
    case "statii_uk":
        FFileRead("template.statii.uk.html", $content);
        $query = "select * from STATII_UK";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_statie";
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
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
	<td align=center><a href='index.php?action=edit_statii_uk&id_statie=" . $q->f("id_statie") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_statii_uk&id_statie=" . $q->f("id_statie") . "'>" . $q->f("id_statie") . "&nbsp;$status</a></td>
	<td>" . $q->f("nume_statie") . "</td>	
	<td><input type=checkbox name=check_uk[" . $q->f("id_statie") . "]></td>
	</tr>";
        }
        $query = "select * from STATII_UK where id_statie>0 and status='1'";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "add_statii_uk":
        FFileRead("template.add_statii.uk.html", $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_statii_uk":
        $q->query("select * from STATII_UK where nume_statie='" . $_POST["nume_statie"] . "'");
        if ($q->next_record()) {
            $err = "Aceasta statie exista deja in lista";
        } elseif ($_POST["nume_statie"] == "")
            $err = "Nume statie nu poate lipsi.";
        else {
            $q->query("insert into STATII_UK set nume_statie='" . $_POST["nume_statie"] . "', status='1'");
            $err = "Statie adaugata cu succes";
        }

        header("Location:index.php?action=add_statii_uk&err=$err");
        break;
    case "edit_statii_uk":
        FFileRead("template.edit_statii.uk.html", $content);
        $q->query("select * from STATII_UK where id_statie='" . $_GET["id_statie"] . "'");
        $q->next_record();

        $content = str_replace("{nume_statie}", $q->f("nume_statie"), $content);
        $content = str_replace("{id_statie}", $q->f("id_statie"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_statii_uk":
        $q->query("select * from STATII_UK where nume_statie='" . $_POST["nume_statie"] . "'");
        if ($q->nf() > 1) {
            $err = "Aceasta statie exista deja in lista";
        } elseif ($_POST["nume_statie"] == "")
            $err = "Nume statie nu poate lipsi.";
        else {
            $q->query("update STATII_UK set nume_statie='" . $_POST["nume_statie"] . "', status='1' where id_statie='" . $_POST["id_statie"] . "'");
            $err = "Statie modificata cu succes";
        }
        header("Location:index.php?action=add_statii_uk&err=$err");
        break;
    case "delete_statii_uk":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI_STATII_UK where id_statie_pornire='$x' OR id_statie_sosire='$x'");
                $query = "delete from STATII_UK where id_statie='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=statii_uk");
        break;
        
    //statii UK end
        
    //destinatie
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
        
        //destinatie UK start
        
    case "destinatie_uk":
        FFileRead("template.destinatie.uk.html", $content);
        $query = "select * from DESTINATIE_UK";
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
	<td align=center><a href='index.php?action=edit_destinatie_uk&id_destinatie=" . $q->f("id_destinatie") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_destinatie_uk&id_destinatie=" . $q->f("id_destinatie") . "'>" . $q->f("id_destinatie") . "&nbsp;$status</a></td>
	
	<td>" . $q->f("label") . "</td><td>" . $q->f("nume_destinatie") . "</td><td>" . $q->f("url_destinatie") . "</td>	
    <td><input type=checkbox name=check_uk[" . $q->f("id_destinatie") . "]></td>
	</tr>";
        }
        $query = "select * from DESTINATIE_UK where id_destinatie>0 and status='1'";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "add_destinatie_uk":
        FFileRead("template.add_destinatie.uk.html", $content);
        $q->query("select distinct label from DESTINATIE_UK order by label asc");
        $label = "";
        while ($q->next_record())
            $label.="<option value=\"" . $q->f("label") . "\">" . $q->f("label") . "</option>";
        $content = str_replace("{label}", $label, $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_destinatie_uk":
        $label = ($_POST["label"] != "") ? $_POST["label"] : $_POST["labelnou"];
        if ($label == "")
            $err = "Labelul nu poate lipsi";
        else {
            $q->query("select * from DESTINATIE_UK where nume_destinatie='" . $_POST["nume_destinatie"] . "'");
            if ($q->next_record()) {
                $err = "Aceasta destinatie exista deja in lista";
            } elseif ($_POST["nume_destinatie"] == "" || $_POST["url_destinatie"] == "")
                $err = "Nume destinatie si url destinatie nu pot lipsi.";
            else {
                $q->query("insert into DESTINATIE_UK set label='" . $label . "',nume_destinatie='" . $_POST["nume_destinatie"] . "', url_destinatie='" . $_POST["url_destinatie"] . "', status='1'");
                $err = "Destinatie adaugata cu succes";
            }
        }
        header("Location:index.php?action=add_destinatie_uk&err=$err");
        break;
    case "edit_destinatie_uk":
        FFileRead("template.edit_destinatie.uk.html", $content);
        $q->query("select * from DESTINATIE_UK where id_destinatie='" . $_GET["id_destinatie"] . "'");
        $q->next_record();

        $content = str_replace("{label}", $q->f("label"), $content);
        $content = str_replace("{nume_destinatie}", $q->f("nume_destinatie"), $content);
        $content = str_replace("{extra_info}", $q->f("extra_info"), $content);
        $content = str_replace("{url_destinatie}", $q->f("url_destinatie"), $content);
        $content = str_replace("{id_destinatie}", $q->f("id_destinatie"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_destinatie_uk":
        if ($_POST["label"] == "")
            $err = "Labelul nu poate lipsi";
        else {
            if ($_POST["nume_destinatie"] == "" || $_POST["url_destinatie"] == "")
                $err = "Nume destinatie si url destinatie nu pot lipsi.";
            else {
                $q->query("update DESTINATIE_UK set label='" . $_POST["label"] . "',nume_destinatie='" . $_POST["nume_destinatie"] . "', url_destinatie='" . $_POST["url_destinatie"] . "', extra_info='" . $_POST["extra_info"] . "', status='1' where id_destinatie='" . $_POST["id_destinatie"] . "'");
                $err = "Destinatie modificata cu succes";
            }
        }
        header("Location:index.php?action=destinatie_uk&err=$err");
        break;
    case "delete_destinatie_uk":
        foreach ($_POST["check_uk"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI_UK where id_destinatie='$x'");
                $query = "delete from DESTINATIE_UK where id_destinatie='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=destinatie_uk");
        break;
        //destinatie UK end
        //legaturi
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
            $q->query("select nume_aeroport,id_tara from AEROPORT where id_aeroport='$id_aeroport'");
            $q->next_record();
            $nume_aeroport = $q->f("nume_aeroport");
            $id_tara = $q->f("id_tara");
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
                
                $qauto->query("select * from PRETURI where id_legaturi='".$q->f("id_legaturi")."' order by pret ASC");        
                $auto_legaturi = "";
                $shuttle_exists = 0;
                while ($qauto->next_record()) {
                    if ($qauto->f("id_auto") != 0) {
                        $qauto2->query("select nume_auto from AUTO where id_auto='" . $qauto->f("id_auto") . "'");
                        $qauto2->next_record();
                        $nume_auto = $qauto2->f("nume_auto");
                    } else {
                        $shuttle_exists = 1;
                        $nume_auto = "Shuttle";
                    }
					if($id_tara == 44) 
						$auto_legaturi .= $nume_auto . " ". (($nume_auto == "Shuttle") ? 'VEZI TABEL STATII' : '<input size = "5" type = "text" name = pret_uk[' . $q->f("id_legaturi") . '][' . $qauto->f("id_auto") . '] value = "'.$qauto->f("pret_uk").'">') . ". ";
                    else
						$auto_legaturi .= $nume_auto . " ". (($nume_auto == "Shuttle") ? 'VEZI TABEL STATII' : '<input size = "5" type = "text" name = pret[' . $q->f("id_legaturi") . '][' . $qauto->f("id_auto") . '] value = "'.$qauto->f("pret").'">') . ". ";
                }
                
                $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
                    <td align=center><a href='index.php?action=edit_legaturi&id_legaturi=" . $q->f("id_legaturi") . "'>Edit</a></td>
                    <td align=center><a href='index.php?action=preturi&id_legaturi=" . $q->f("id_legaturi") . "'>Preturi</a></td>
                    <td>" . $q->f("nume_aeroport") . "</td><td>" . $q->f("nume_destinatie") . "</td><td><input size='8' type='text' name = km[" . $q->f("id_legaturi") . "] value ='" . $q->f("km") . "'></td>
                    <td><input size='8'  type='text' name = timp[" . $q->f("id_legaturi") . "] value ='" . $q->f("timp") . "'></td>
                    <td>$auto_legaturi</td>			
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
            }
        }
        
         foreach ($_POST["timp"] as $timp => $value) {
            if ($timp != '') {
                $q->query("update LEGATURI set timp = '$value' where id_legaturi='$timp'");
            }
        }
        
        foreach ($_POST["km"] as $km => $value) {
            if ($km != '') {
                $q->query("update LEGATURI set km = '$value' where id_legaturi='$km'");
            }
        }
        
        foreach ($_POST["pret"] as $x => $value) {
            if ($x > 0) {
                foreach ($value as $price => $price_value) {
                    if ($price != '') {
                        $q->query("update PRETURI set pret = '$price_value' where id_auto = '$price' and  id_legaturi='" . $x . "'");
                    }
                }
            }
        }
        foreach ($_POST["pret_uk"] as $x => $value) {
            if ($x > 0) {
                foreach ($value as $price => $price_value) {
                    if ($price != '') {
                        $q->query("update PRETURI set pret_uk = '$price_value' where id_auto = '$price' and  id_legaturi='" . $x . "'");
                    }
                }
            }
        }
        
        header("Location:index.php?action=legaturi&cauta=" . $_POST["id_aeroport"]);
        break;
    //legaturi UK start
    
    case "legaturi_uk":
        $title = $sitename_title . " Legaturi";
        FFileRead("template.legaturi.uk.html", $content);
        $q->query("select id_aeroport,nume_aeroport from AEROPORT_UK where status='1'");
        $aeroporturi = "";
        while ($q->next_record())
            $aeroporturi.="<option value=\"" . $q->f("id_aeroport") . "\">" . $q->f("nume_aeroport") . "</option>";

        $content = str_replace("{aeroporturi}", $aeroporturi, $content);
        if ($_POST["cauta"] != "" || $_GET["cauta"] != "") {
            $id_aeroport = (isset($_POST["cauta"])) ? $_POST["cauta"] : $_GET["cauta"];
            FFileRead("template.legaturi2.uk.html", $content2);
            $q->query("select nume_aeroport from AEROPORT_UK where id_aeroport='$id_aeroport'");
            $q->next_record();
            $nume_aeroport = $q->f("nume_aeroport");
            $content.=$content2;
            $content = str_replace("{error}", $_GET["err"], $content);
            if ($_GET["cauta"] != "")
                $query = "select * from LEGATURI_UK l join AEROPORT_UK a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE_UK d on l.id_destinatie=d.id_destinatie where l.id_aeroport='" . $_GET["cauta"] . "'";
            else {
                if ($_POST["cauta"] != "")
                    $query = "select * from LEGATURI_UK l join AEROPORT_UK a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE_UK d on l.id_destinatie=d.id_destinatie where l.id_aeroport='" . $_POST["cauta"] . "'";
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
                
                $qauto->query("select * from PRETURI_UK where id_legaturi='".$q->f("id_legaturi")."'");        
                $auto_legaturi = "";
                $shuttle_exists = 0;
                while ($qauto->next_record()) {
                    if ($qauto->f("id_auto") != 0) {
                        $qauto2->query("select nume_auto from AUTO_UK where id_auto='" . $qauto->f("id_auto") . "'");
                        $qauto2->next_record();
                        $nume_auto = $qauto2->f("nume_auto");
                    } else {
                        $shuttle_exists = 1;
                        $nume_auto = "Shuttle";
                    }
                    $auto_legaturi .= $nume_auto . " ". (($nume_auto == "Shuttle") ? 'VEZI TABEL STATII' : '<input size = "5" type = "text" name = pret_uk[' . $q->f("id_legaturi") . '][' . $qauto->f("id_auto") . '] value = "'.$qauto->f("pret").'">') . ". ";
                }
                
                $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
                    <td align=center><a href='index.php?action=edit_legaturi_uk&id_legaturi=" . $q->f("id_legaturi") . "'>Edit</a></td>
                    <td align=center><a href='index.php?action=preturi_uk&id_legaturi=" . $q->f("id_legaturi") . "'>Preturi</a></td>
                    <td>" . $q->f("nume_aeroport") . "</td><td>" . $q->f("nume_destinatie") . "</td><td><input size='8' type='text' name = km_uk[" . $q->f("id_legaturi") . "] value ='" . $q->f("km") . "'></td>
                    <td><input size='8'  type='text' name = timp_uk[" . $q->f("id_legaturi") . "] value ='" . $q->f("timp") . "'></td>
                    <td>$auto_legaturi</td>
                    <td><input type=checkbox name=check_uk[" . $q->f("id_legaturi") . "]></td></tr>";
                $legaturi_existente[] = $q->f("id_destinatie");
            }
            $query = "select * from DESTINATIE_UK";
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
		<td>" . $q2->f("nume_destinatie") . "</td><td><input type=checkbox name=check_uk[" . $q2->f("id_destinatie") . "]></td></tr>";
                }
            }
            $content = str_replace("{nume_aeroport}", $nume_aeroport, $content);
            $content = str_replace("{id_aeroport}", $id_aeroport, $content);
            $content = str_replace("{rows}", $rows, $content);
            $content = str_replace("{rows2}", $rows2, $content);
        }
        break;
    case "do_add_legaturi_uk":
        foreach ($_POST["check_uk"] as $z => $value) {
            if ($z > 0)
                $q->query("insert into LEGATURI_UK set id_aeroport='" . $_POST["id_aeroport"] . "',id_destinatie='$z'");
        }
        header("Location:index.php?action=legaturi_uk&cauta=" . $_POST["id_aeroport"]);
        break;
    case "edit_legaturi_uk":
        FFileRead("template.edit_legaturi.uk.html", $content);
        $title = $sitename_title . " - Editeaza legatura UK";
        $q->query("select * from LEGATURI_UK l join AEROPORT_UK a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE_UK d on l.id_destinatie=d.id_destinatie where l.id_legaturi='" . $_GET["id_legaturi"] . "'");
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
    case "do_edit_legaturi_uk":
        $q->query("update LEGATURI_UK set km='" . $_POST["km"] . "',timp='" . $_POST["timp"] . "', titlu_pagina='" . $_POST["titlu_pagina"] . "', meta_pagina='" . $_POST["meta_pagina"] . "' where id_legaturi='" . $_POST["id_legaturi"] . "'");
        $err = "Date legatura modificate cu succes";

        header("Location:index.php?action=edit_legaturi_uk&err=$err");
        break;
    case "delete_legaturi_uk":
        foreach ($_POST["check_uk"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI_UK where id_legaturi='$x'");
                $q->query($query);
            }
        }
        
        foreach ($_POST["timp_uk"] as $timp => $value) {
            if ($timp != '') {
                $q->query("update LEGATURI_UK set timp = '$value' where id_legaturi='$timp'");
            }
        }
        
        foreach ($_POST["km_uk"] as $km => $value) {
            if ($km != '') {
                $q->query("update LEGATURI_UK set km = '$value' where id_legaturi='$km'");
            }
        }
        
        foreach ($_POST["pret_uk"] as $x => $value) {
            if ($x > 0) {
                foreach ($value as $price => $price_value) {
                    if ($price != '') {
                        $q->query("update PRETURI_UK set pret = '$price_value' where id_auto = '$price' and  id_legaturi='" . $x . "'");
                    }
                }
            }
        }
        
        header("Location:index.php?action=legaturi_uk&cauta=" . $_POST["id_aeroport"]);
        break;    
        
    //legaturi UK end
        
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
			<td>" . $nume_auto . "</td><td>" . (($nume_auto == "Shuttle") ? 'VEZI TABEL STATII' : '<input type = "text" name = pret[' . $q->f("id_auto") . '] value = "'.$q->f("pret").'">') . "</td><td><input type=checkbox name=check[" . $q->f("id_auto") . "]></td></tr>";
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
    //legaturi statii
    case "add_legaturi_statii":
        FFileRead("template.add_legaturi_statii.html", $content);
        $q->query("select * from STATII order by nume_statie asc");
        $statii = "";
        while ($q->next_record())
            $statii.="<option value=\"" . $q->f("id_statie") . "\">" . $q->f("nume_statie") . "</option>";
        $content = str_replace("{statii}", $statii, $content);
        $content = str_replace("{id_legaturi}", $_GET["id_legaturi"], $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_legaturi_statii":
        /* $q->query("select * from LEGATURI_STATII where id_statie_pornire='".$_POST["statie_pornire"]."' and id_statie_sosire='".$_POST["statie_sosire"]."'");
          if ($q->next_record()){
          $err="Aceasta legatura intre statii exista deja in lista";
          }
          else */if ($_POST["statie_pornire"] == "" || $_POST["statie_sosire"] == "")
            $err = "Statia de pornire si statia de sosire nu pot lipsi.";
        else {
            $q->query("insert into LEGATURI_STATII set id_statie_pornire='" . $_POST["statie_pornire"] . "', id_statie_sosire='" . $_POST["statie_sosire"] . "', descriere_pornire='" . $_POST["descriere_pornire"] . "', descriere_sosire='" . $_POST["descriere_sosire"] . "', ora_pornire='" . $_POST["ora_pornire"] . ":00',ora_sosire='" . $_POST["ora_sosire"] . ":00',pret='" . $_POST["pret"] . "',  id_legaturi='" . $_POST["id_legaturi"] . "', status='1'");
            $err = "Legatura intre statii adaugata cu succes";
        }

        header("Location:index.php?action=add_legaturi_statii&err=$err");
        break;
    case "edit_legaturi_statii":
        FFileRead("template.edit_legaturi_statii.html", $content);
        $q->query("select * from STATII order by nume_statie asc");
        $statii = "";
        while ($q->next_record())
            $statii.="<option value=\"" . $q->f("id_statie") . "\">" . $q->f("nume_statie") . "</option>";
        $content = str_replace("{statii}", $statii, $content);

        $q->query("select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII ls JOIN STATII s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legatura_statie='" . $_GET["id_legatura_statie"] . "'");
        $q->next_record();

        $content = str_replace("{id_statie_pornire}", $q->f("id_statie_pornire"), $content);
        $content = str_replace("{statie_pornire}", $q->f("statie_pornire"), $content);
        $content = str_replace("{ora_pornire}", substr($q->f("ora_pornire"), 0, -3), $content);
        $content = str_replace("{descriere_pornire}", $q->f("descriere_pornire"), $content);
        $content = str_replace("{id_statie_sosire}", $q->f("id_statie_sosire"), $content);
        $content = str_replace("{statie_sosire}", $q->f("statie_sosire"), $content);
        $content = str_replace("{ora_sosire}", substr($q->f("ora_sosire"), 0, -3), $content);
        $content = str_replace("{descriere_sosire}", $q->f("descriere_sosire"), $content);
        $content = str_replace("{id_legatura_statie}", $q->f("id_legatura_statie"), $content);
        $content = str_replace("{pret}", $q->f("pret"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_legaturi_statii":
        /* $q->query("select * from LEGATURI_STATII where id_statie_pornire='".$_POST["id_statie_pornire"]."' and id_statie_sosire='".$_POST["id_statie_sosire"]."'");
          if ($q->nf()>1){
          $err="Aceasta statie exista deja in lista";
          }else{ */
        $q->query("update LEGATURI_STATII set id_statie_pornire='" . $_POST["id_statie_pornire"] . "', id_statie_sosire='" . $_POST["id_statie_sosire"] . "', descriere_pornire='" . $_POST["descriere_pornire"] . "', descriere_sosire='" . $_POST["descriere_sosire"] . "', ora_pornire='" . $_POST["ora_pornire"] . ":00',ora_sosire='" . $_POST["ora_sosire"] . ":00',pret='" . $_POST["pret"] . "' where id_legatura_statie='" . $_POST["id_legatura_statie"] . "'");
        $err = "Legatura statie modificata cu succes";
        //}
        header("Location:index.php?action=edit_legaturi_statii&id_legatura_statie=" . $_POST["id_legatura_statie"] . "&err=$err");
        break;
    case "delete_legaturi_statii":
        if (isset($_POST["check"])) {
            foreach ($_POST["check"] as $x => $value) {
                if ($x > 0) {
                    $q->query("delete from LEGATURI_STATII where id_legatura_statie='$x'");
                }
            }
        }
        if (isset($_POST["checkretur"])) {
            foreach ($_POST["checkretur"] as $y => $value) {
                if ($y > 0) {
                    $q->query("update LEGATURI_STATII set retur=NOT retur where id_legatura_statie='$y'");
                }
            }
        }
        header("Location:index.php?action=edit_preturi&id_auto=0&id_legaturi=" . $_POST["id_legaturi"]);
        break;

    //preturi pentru diferite legaturi
    case "edit_preturi":
        if ($_GET["id_auto"] == 0) {//shuttle
            FFileRead("template.legaturi_statii.html", $content);
            $query = "select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII ls JOIN STATII s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legaturi='" . $_GET["id_legaturi"] . "'";
            $query.="order by retur asc,";
            if ($_GET["order"] != "")
                $query.=" " . $_GET["order"];
            else
                $query.=" ls.id_legatura_statie";
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
                if ($q->f("retur") == 1) {
                    $status_retur = "Da";
                    $bg_status_retur = "bgcolor=\"949494\"";
                } else {
                    $status_retur = "";
                    $bg_status_retur = "";
                }
                $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
	<td align=center><a href='index.php?action=edit_legaturi_statii&id_legatura_statie=" . $q->f("id_legatura_statie") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_legaturi_statii&id_legatura_statie=" . $q->f("id_legatura_statie") . "&id_legaturi=" . $_GET["id_legaturi"] . "'>" . $q->f("id_legatura_statie") . "&nbsp;$status</a></td>
	<td>" . $q->f("ora_pornire") . "</td><td>" . $q->f("statie_pornire") . "</td><td>" . $q->f("descriere_pornire") . "</td>
	<td>" . $q->f("ora_sosire") . "</td><td>" . $q->f("statie_sosire") . "</td><td>" . $q->f("descriere_sosire") . "</td>
	<td>" . $q->f("pret") . "</td>
	<td $bg_status_retur>$status_retur <input type=checkbox name=checkretur[" . $q->f("id_legatura_statie") . "]></td>
	<td><input type=checkbox name=check[" . $q->f("id_legatura_statie") . "]></td>
	</tr>";
            }
            $query = "select * from STATII where id_statie>0 and status='1'";
            $q->query($query);
            $content = str_replace("{rows}", $rows, $content);
            $content = str_replace("{total}", $q->nf(), $content);
            $content = str_replace("{id_legaturi}", $_GET["id_legaturi"], $content);

            $query = "select * from LEGATURI_STATII where id_legaturi='" . $_GET["id_legaturi"] . "'";
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
            FFileRead("template.edit_preturi.html", $content);
            $query = "select * from PRETURI p JOIN AUTO a ON p.id_auto=a.id_auto where a.id_auto='" . $_GET["id_auto"] . "' AND p.id_legaturi='" . $_GET["id_legaturi"] . "'";
            $q->query($query);
            $q->next_record();
            $content = str_replace("{nume_auto}", $q->f("nume_auto"), $content);
            $content = str_replace("{zile_nu_circula}", 'display:none;', $content);
            $content = str_replace("{pret}", $q->f("pret"), $content);
        }

        $content = str_replace("{id_auto}", $q->f("id_auto"), $content);
        $content = str_replace("{id_legaturi}", $q->f("id_legaturi"), $content);

        $q->query("select a.nume_aeroport,d.nume_destinatie from LEGATURI l JOIN AEROPORT a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE d ON l.id_destinatie=d.id_destinatie where id_legaturi='" . $_GET["id_legaturi"] . "'");
        $q->next_record();
        $content = str_replace("{nume_aeroport}", $q->f("nume_aeroport"), $content);
        $content = str_replace("{nume_destinatie}", $q->f("nume_destinatie"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_preturi":
        if (isset($_POST["pret"]))
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
        
        foreach ($_POST["pret"] as $pret => $value) {
            if ($pret != '') {
                $q->query("update PRETURI set pret = '$value' where id_auto = '$pret' and  id_legaturi='" . $_POST["id_legaturi"] . "'");
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
        
    //preturi UK start
        
    case "preturi_uk":
        $title = $sitename_title . " Preturi";
        FFileRead("template.preturi.uk.html", $content);

        $id_legaturi = $_REQUEST["id_legaturi"];
        $q->query("select a.id_aeroport, a.nume_aeroport,d.nume_destinatie from LEGATURI_UK l JOIN AEROPORT_UK a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE_UK d ON l.id_destinatie=d.id_destinatie where id_legaturi='$id_legaturi'");
        $q->next_record();
        $content = str_replace("{nume_aeroport}", $q->f("nume_aeroport"), $content);
        $content = str_replace("{id_aeroport}", $q->f("id_aeroport"), $content);
        $content = str_replace("{nume_destinatie}", $q->f("nume_destinatie"), $content);
        $q->query("select * from PRETURI_UK where id_legaturi='$id_legaturi'");

        $content = str_replace("{error}", $_GET["err"], $content);
        $rows = "";
        $preturi_existente = array();
        $i = 0;
        $shuttle_exists = 0;
        while ($q->next_record()) {
            if ($q->f("id_auto") != 0) {
                $q2->query("select nume_auto from AUTO_UK where id_auto='" . $q->f("id_auto") . "'");
                $q2->next_record();
                $nume_auto = $q2->f("nume_auto");
            } else {
                $shuttle_exists = 1;
                $nume_auto = "Shuttle";
                $preturi_existente[] = 0;
            }
            $i++;
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
                    <td align=center><a href='index.php?action=edit_preturi_uk&id_legaturi=" . $q->f("id_legaturi") . "&id_auto=" . $q->f("id_auto") . "'>Edit</a></td>
                    <td>" . $nume_auto . "</td><td>" . (($nume_auto == "Shuttle") ? 'VEZI TABEL STATII' : '<input type = "text" name = pret_uk[' . $q->f("id_auto") . '] value = "'.$q->f("pret").'">') . "</td><td><input type=checkbox name=check_uk[" . $q->f("id_auto") . "]></td></tr>";
            $preturi_existente[] = $q->f("id_auto");
        }
        $q2->query("select * from AUTO_UK order by id_auto asc");
        $rows2 = "";
        $j = 0;
        while ($q2->next_record()) {
            $j++;
            if (!in_array($q2->f("id_auto"), $preturi_existente)) {
                $rows2.="<tr id=\"celll$j\" onMouseOver=\"document.all.celll$j.bgColor = '#0099ff'\" onMouseOut=\"document.all.celll$j.bgColor ='#ffffff'\">
		<td>" . $q2->f("nume_auto") . "</td><td><input type=checkbox name=check_uk[" . $q2->f("id_auto") . "]></td></tr>";
            }
        }
        if ($shuttle_exists == 0) {//daca nu exista shuttle pt. destinatia respectiva, sa se poata selecta shuttle
            $rows2.="<tr id=\"celll$j\" onMouseOver=\"document.all.celll$j.bgColor = '#0099ff'\" onMouseOut=\"document.all.celll$j.bgColor ='#ffffff'\">
		<td>Shuttle</td><td><input type=checkbox name=check_uk[0]></td></tr>";
        }
//$content=str_replace("{nume_aeroport}",$nume_aeroport,$content);
        $content = str_replace("{id_legaturi}", $id_legaturi, $content);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{rows2}", $rows2, $content);
        break;

    case "do_add_preturi_uk":
        foreach ($_POST["check_uk"] as $z => $value) {
            $query = "insert into PRETURI_UK set id_legaturi='" . $_POST["id_legaturi"] . "',id_auto='$z'";
            //echo $query."<br>";
            $q->query($query);
        }
        header("Location:index.php?action=preturi_uk&id_legaturi=" . $_POST["id_legaturi"]);
        break;
    //legaturi statii
    case "add_legaturi_statii_uk":
        FFileRead("template.add_legaturi_statii.uk.html", $content);
        $q->query("select * from STATII_UK order by nume_statie asc");
        $statii = "";
        while ($q->next_record())
            $statii.="<option value=\"" . $q->f("id_statie") . "\">" . $q->f("nume_statie") . "</option>";
        $content = str_replace("{statii}", $statii, $content);
        $content = str_replace("{id_legaturi}", $_GET["id_legaturi"], $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_legaturi_statii_uk":
        /* $q->query("select * from LEGATURI_STATII where id_statie_pornire='".$_POST["statie_pornire"]."' and id_statie_sosire='".$_POST["statie_sosire"]."'");
          if ($q->next_record()){
          $err="Aceasta legatura intre statii exista deja in lista";
          }
          else */if ($_POST["statie_pornire"] == "" || $_POST["statie_sosire"] == "")
            $err = "Statia de pornire si statia de sosire nu pot lipsi.";
        else {
            $q->query("insert into LEGATURI_STATII_UK set id_statie_pornire='" . $_POST["statie_pornire"] . "', id_statie_sosire='" . $_POST["statie_sosire"] . "', descriere_pornire='" . $_POST["descriere_pornire"] . "', descriere_sosire='" . $_POST["descriere_sosire"] . "', ora_pornire='" . $_POST["ora_pornire"] . ":00',ora_sosire='" . $_POST["ora_sosire"] . ":00',pret='" . $_POST["pret"] . "',  id_legaturi='" . $_POST["id_legaturi"] . "', status='1'");
            $err = "Legatura intre statii adaugata cu succes";
        }

        header("Location:index.php?action=add_legaturi_statii_uk&err=$err");
        break;
    case "edit_legaturi_statii_uk":
        FFileRead("template.edit_legaturi_statii.uk.html", $content);
        $q->query("select * from STATII_UK order by nume_statie asc");
        $statii = "";
        while ($q->next_record())
            $statii.="<option value=\"" . $q->f("id_statie") . "\">" . $q->f("nume_statie") . "</option>";
        $content = str_replace("{statii}", $statii, $content);

        $q->query("select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII_UK ls JOIN STATII_UK s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII_UK s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legatura_statie='" . $_GET["id_legatura_statie"] . "'");
        $q->next_record();

        $content = str_replace("{id_statie_pornire}", $q->f("id_statie_pornire"), $content);
        $content = str_replace("{statie_pornire}", $q->f("statie_pornire"), $content);
        $content = str_replace("{ora_pornire}", substr($q->f("ora_pornire"), 0, -3), $content);
        $content = str_replace("{descriere_pornire}", $q->f("descriere_pornire"), $content);
        $content = str_replace("{id_statie_sosire}", $q->f("id_statie_sosire"), $content);
        $content = str_replace("{statie_sosire}", $q->f("statie_sosire"), $content);
        $content = str_replace("{ora_sosire}", substr($q->f("ora_sosire"), 0, -3), $content);
        $content = str_replace("{descriere_sosire}", $q->f("descriere_sosire"), $content);
        $content = str_replace("{id_legatura_statie}", $q->f("id_legatura_statie"), $content);
        $content = str_replace("{pret}", $q->f("pret"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_legaturi_statii_uk":
        /* $q->query("select * from LEGATURI_STATII where id_statie_pornire='".$_POST["id_statie_pornire"]."' and id_statie_sosire='".$_POST["id_statie_sosire"]."'");
          if ($q->nf()>1){
          $err="Aceasta statie exista deja in lista";
          }else{ */
        $q->query("update LEGATURI_STATII_UK set id_statie_pornire='" . $_POST["id_statie_pornire"] . "', id_statie_sosire='" . $_POST["id_statie_sosire"] . "', descriere_pornire='" . $_POST["descriere_pornire"] . "', descriere_sosire='" . $_POST["descriere_sosire"] . "', ora_pornire='" . $_POST["ora_pornire"] . ":00',ora_sosire='" . $_POST["ora_sosire"] . ":00',pret='" . $_POST["pret"] . "' where id_legatura_statie='" . $_POST["id_legatura_statie"] . "'");
        $err = "Legatura statie modificata cu succes";
        //}
        header("Location:index.php?action=edit_legaturi_statii_uk&id_legatura_statie=" . $_POST["id_legatura_statie"] . "&err=$err");
        break;
    case "delete_legaturi_statii_uk":
        if (isset($_POST["check_uk"])) {
            foreach ($_POST["check_uk"] as $x => $value) {
                if ($x > 0) {
                    $q->query("delete from LEGATURI_STATII_UK where id_legatura_statie='$x'");
                }
            }
        }
        if (isset($_POST["checkretur_uk"])) {
            foreach ($_POST["checkretur_uk"] as $y => $value) {
                if ($y > 0) {
                    $q->query("update LEGATURI_STATII_UK set retur=NOT retur where id_legatura_statie='$y'");
                }
            }
        }
        header("Location:index.php?action=edit_preturi_uk&id_auto=0&id_legaturi=" . $_POST["id_legaturi"]);
        break;

    //preturi pentru diferite legaturi
    case "edit_preturi_uk":
        if ($_GET["id_auto"] == 0) {//shuttle
            FFileRead("template.legaturi_statii.uk.html", $content);
            $query = "select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII_UK ls JOIN STATII_UK s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII_UK s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legaturi='" . $_GET["id_legaturi"] . "'";
            $query.="order by retur asc,";
            if ($_GET["order"] != "")
                $query.=" " . $_GET["order"];
            else
                $query.=" ls.id_legatura_statie";
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
                if ($q->f("retur") == 1) {
                    $status_retur = "Da";
                    $bg_status_retur = "bgcolor=\"949494\"";
                } else {
                    $status_retur = "";
                    $bg_status_retur = "";
                }
                $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff'\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\">
	<td align=center><a href='index.php?action=edit_legaturi_statii_uk&id_legatura_statie=" . $q->f("id_legatura_statie") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_legaturi_statii_uk&id_legatura_statie=" . $q->f("id_legatura_statie") . "&id_legaturi=" . $_GET["id_legaturi"] . "'>" . $q->f("id_legatura_statie") . "&nbsp;$status</a></td>
	<td>" . $q->f("ora_pornire") . "</td><td>" . $q->f("statie_pornire") . "</td><td>" . $q->f("descriere_pornire") . "</td>
	<td>" . $q->f("ora_sosire") . "</td><td>" . $q->f("statie_sosire") . "</td><td>" . $q->f("descriere_sosire") . "</td>
	<td>" . $q->f("pret") . "</td>
	<td $bg_status_retur>$status_retur <input type=checkbox name=checkretur_uk[" . $q->f("id_legatura_statie") . "]></td>
	<td><input type=checkbox name=check_uk[" . $q->f("id_legatura_statie") . "]></td>
	</tr>";
            }
            $query = "select * from STATII_UK where id_statie>0 and status='1'";
            $q->query($query);
            $content = str_replace("{rows}", $rows, $content);
            $content = str_replace("{total}", $q->nf(), $content);
            $content = str_replace("{id_legaturi}", $_GET["id_legaturi"], $content);

            $query = "select * from LEGATURI_STATII_UK where id_legaturi='" . $_GET["id_legaturi"] . "'";
            $content = str_replace("{nume_auto}", "Shuttle", $content);
            $q->query($query);
            $q->next_record();
            $q2->query("select * from shuttle_run_UK where idLegatura='" . $_GET["id_legaturi"] . "'");
            $days='';
            while ($q2->next_record()){
                $content = str_replace("{checked".$q2->f("zi")."}", 'checked', $content);
                $days.=' day != '.$q2->f("zi").' &&';
            }
            $days=substr($days,0,-3);
            $content = str_replace("{removed_days}", $days, $content);
            $content = str_replace("{zile_nu_circula}", '', $content);
        } else {
            FFileRead("template.edit_preturi.uk.html", $content);
            $query = "select * from PRETURI_UK p JOIN AUTO_UK a ON p.id_auto=a.id_auto where a.id_auto='" . $_GET["id_auto"] . "' AND p.id_legaturi='" . $_GET["id_legaturi"] . "'";
            $q->query($query);
            $q->next_record();
            $content = str_replace("{nume_auto}", $q->f("nume_auto"), $content);
            $content = str_replace("{zile_nu_circula}", 'display:none;', $content);
            $content = str_replace("{pret}", $q->f("pret"), $content);
        }

        $content = str_replace("{id_auto}", $q->f("id_auto"), $content);
        $content = str_replace("{id_legaturi}", $q->f("id_legaturi"), $content);

        $q->query("select a.nume_aeroport,d.nume_destinatie from LEGATURI_UK l JOIN AEROPORT_UK a on l.id_aeroport=a.id_aeroport JOIN DESTINATIE_UK d ON l.id_destinatie=d.id_destinatie where id_legaturi='" . $_GET["id_legaturi"] . "'");
        $q->next_record();
        $content = str_replace("{nume_aeroport}", $q->f("nume_aeroport"), $content);
        $content = str_replace("{nume_destinatie}", $q->f("nume_destinatie"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_preturi_uk":
        if (isset($_POST["pret"]))
        $q->query("update PRETURI_UK set pret='" . $_POST["pret"] . "' where id_legaturi='" . $_POST["id_legaturi"] . "' and id_auto='" . $_POST["id_auto"] . "'");
        
        $q->query("delete from shuttle_run_UK where idLegatura='" . $_POST["id_legaturi"] . "'");
        for ($i=0;$i<7;$i++){
            if (isset($_POST["day".$i])){
                $q->query("insert into shuttle_run_UK set idLegatura='" . $_POST["id_legaturi"] . "', zi='$i'");
            }
        }
        $err = "Date legatura modificate cu succes";

        header("Location:index.php?action=preturi_uk&id_legaturi=" . $_POST["id_legaturi"] . "&err=$err");
        break;
    case "delete_preturi_uk":
        if (isset($_POST["check_uk"])) {
            foreach ($_POST["check_uk"] as $x => $value) {
                $q->query("delete from PRETURI_UK where id_auto='$x' and id_legaturi='" . $_POST["id_legaturi"] . "'");
                if ($x==0) {
                    $q->query("delete from shuttle_run_UK where idLegatura='" . $_POST["id_legaturi"] . "'");
                }
            }
        }
        
         foreach ($_POST["pret_uk"] as $pret => $value) {
            if ($pret != '') {
                $q->query("update PRETURI_UK set pret = '$value' where id_auto = '$pret' and  id_legaturi='" . $_POST["id_legaturi"] . "'");
            }
        }
        
        header("Location:index.php?action=preturi_uk&id_legaturi=" . $_POST["id_legaturi"]);
        break;
    case "delete_legaturi_uk":
        foreach ($_POST["check_uk"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LEGATURI_UK where id_legaturi='$x'");
                $q->query($query);
            }
        }
        header("Location:index.php?action=legaturi_uk&id_legaturi=" . $_POST["id_legaturi"]);
        break;

    //preturi UK end
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
        
        //auto uk start
        case "auto_uk":
        FFileRead("template.auto.uk.html", $content);
        $query = "select * from AUTO_UK";
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
	<td align=center><a href='index.php?action=edit_auto_uk&id_auto=" . $q->f("id_auto") . "'>Edit</a></td>
	<td align=center $bg_status><a href='index.php?action=set_activ_inactiv_auto_uk&id_auto=" . $q->f("id_auto") . "'>" . $q->f("id_auto") . "&nbsp;$status</a></td>
	<td>" . $q->f("nume_auto") . "</td><td>" . $q->f("nr_pasageri") . "</td>
	<td><input type=checkbox name=check_uk[" . $q->f("id_auto") . "]></td>
	</tr>";
        }
        $query = "select * from AUTO_UK where id_auto>0 and status='1'";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "add_auto_uk":
        FFileRead("template.add_auto.uk.html", $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_auto_uk":
        $q->query("select * from AUTO_UK where nume_auto='" . $_POST["nume_auto"] . "'");
        if ($q->next_record()) {
            $err = "Acest auto exista deja in lista";
        } elseif ($_POST["nume_auto"] != "") {
            $file_dir = $serverpath . "/images/auto_UK/";
            $query = "select id_auto from AUTO_UK order by id_auto desc limit 0,1";
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
                        $poza1 = ", poza1='/images/auto_UK/" . $ii . "_01.jpg'";
                    }
                } else {
                    $poza1 = "";
                    $err = "Sorry, there was a problem uploading your picture.";
                }
                $pics = $_SERVER['DOCUMENT_ROOT'] . "/images/auto_UK/" . $ii . "_01.jpg";
                createthumb($pics, substr($pics, 0, -4) . "_tn.jpg", 124, 93);
                $pics = ditchtn($pics, "tnn_");
                createnormal($pics, $pics, 580, 435);
            }
            $q->query("insert into AUTO_UK set nume_auto='" . $_POST["nume_auto"] . "',nr_pasageri='" . $_POST["nr_pasageri"] . "' $poza1, status='1'");
            $err = "Auto adaugat cu succes";
        }
        header("Location:index.php?action=add_auto_uk&err=$err");
        break;
    case "edit_auto_uk":
        FFileRead("template.edit_auto.uk.html", $content);
        $q->query("select * from AUTO_UK where id_auto='" . $_GET["id_auto"] . "'");
        $q->next_record();
        $content = str_replace("{nume_auto}", $q->f("nume_auto"), $content);
        $content = str_replace("{nr_pasageri}", $q->f("nr_pasageri"), $content);
        $poza1 = ($q->f("poza1") != "") ? "<img src=\"/images/auto_UK/" . $q->f("id_auto") . "_01_tn.jpg\"><br>" : "";
        $content = str_replace("{poza1}", $poza1, $content);
        $content = str_replace("{id_auto}", $q->f("id_auto"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_auto_uk":
        if ($_POST["nume_auto"] == "" || $_POST["nr_pasageri"] == "")
            $err = "Nume auto si nr. pasageri nu pot lipsi.";
        else {
            $file_dir = $serverpath . "/images/auto_UK/";
            if (isset($_FILES["poza1"]) && $_FILES["poza1"]['size'] > 1) {
                if (trim($_FILES["poza1"]['name']) != "") {
                    $newfile = $file_dir . $_POST["id_auto"] . "_01.jpg";
                    move_uploaded_file($_FILES["poza1"]['tmp_name'], $newfile);
                    $j = 1;
                }
                if (isset($j) && $j == 1) {
                    $poza1 = ", poza1='/images/auto_UK/" . $_POST["id_auto"] . "_01.jpg'";
                } else {
                    if ($q->f("poza1") == "")
                        $poza1 = "";
                    $err = "Sorry, there was a problem uploading your picture.";
                }
                $pics = $_SERVER['DOCUMENT_ROOT'] . "/images/auto_UK/" . $_POST["id_auto"] . "_01.jpg";
                createthumb($pics, substr($pics, 0, -4) . "_tn.jpg", 124, 93);

                $pics = ditchtn($pics, "tnn_");
                createnormal($pics, $pics, 580, 435);
            }
            $q->query("update AUTO_UK set nume_auto='" . $_POST["nume_auto"] . "',nr_pasageri='" . $_POST["nr_pasageri"] . "' $poza1, status='1' where id_auto='" . $_POST["id_auto"] . "'");
            $err = "Auto modificat cu succes";
        }
        header("Location:index.php?action=edit_auto_uk&err=$err");
        break;
    case "delete_auto_uk":
        foreach ($_POST["check_uk"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from AUTO_UK where id_auto='$x'");
                $query = "delete from PRETURI_UK where id_auto='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=auto_uk");
        break;
        //auto uk end
        // bookings - comenzi
    case "comenzi":
        FFileRead("template.comenzi.html", $content);
        $title = $sitename_title . " - Comenzi";
        $content = str_replace("{order}", (isset($_GET["order"]) ? $_GET["order"] : "id_order"), $content);
        $content = str_replace("{rule}", (isset($_GET["rule"]) ? $_GET["rule"] : "desc"), $content);
        $liste = '<option value=""></option>';
        $q->query("select * from LISTE order by nume_lista asc");
        while ($q->next_record()) {
            $liste.='<option value="' . $q->f("id_lista") . '">' . $q->f("nume_lista") . '</option>';
        }

        $query = "select * from ORDERS";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_order";
        if ($_GET["rule"] != "")
            $query.=" " . $_GET["rule"];
        else
            $query.=" desc";
        $q3->query($query);
        $pages = round($q3->nf() / 100);
        if ($q3->nf() / 100 - round($q->nf() / 100) > 0)
            $pages++;
        $content = str_replace("{pages}", $pages, $content);
        $page = $_GET["page"];
        if ($page == "")
            $page = 1;
        if ($page < $pages)
            $next = $page + 1;
        else
            $next = $page;
        if ($page > 1)
            $previous = $page - 1;
        else
            $previous = 1;
        $content = str_replace("{page}", $page, $content);
        $content = str_replace("{next}", $next, $content);
        $content = str_replace("{previous}", $previous, $content);
        $content = str_replace("{last}", $pages, $content);
        $query.=" limit " . (($page - 1) * 100) . ", 100"; //echo $query;
        $q->query($query);

        $rows = "";
        $i = 0;
        while ($q->next_record()) {
            $i++;
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=detalii_comanda&id_order=" . $q->f("id_order") . "'>Detalii</a></td>
	<td align='center'><input type=checkbox name=lista[" . $q->f("id_order") . "]></td>
	<td align=center>" . $q->f("id_order") . "</td>
	<td>" . $q->f("numedefamilie") . " " . $q->f("prenume") . "</td>
	<td>" . $q->f("telefon") . "</td>
	<td>" . $q->f("email") . "</td>
	<td>" . $q->f("arrival") . "</td>
	<td>" . $q->f("passengers") . "</td>
	<td>" . $q->f("destination") . "</td>
	<td>" . (($q->f("passengers2") > 0) ? "Dus-Intors" : "One Way") . "</td>
	<td>" . $q->f("flight_departure_to") . "</td>
	<td>" . $q->f("passengers2") . "</td>
	<td>" . ($q->f("price_currency") == 2 ? '&pound;'.$q->f("price") : '&euro;'.$q->f("price")) . "</td>
	<td><input type=checkbox name=check[" . $q->f("id_order") . "]></td>
	</tr>";//era <td>" . (($q->f("sex") == 0) ? "m" : "f") . "</td> dupa nume de familie
        }
        $query = "select * from ORDERS where id_order>0";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{liste}", $liste, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "detalii_comanda":
	
        $title = $sitename_title . " - Detalii comanda";
        $q->query("select * from ORDERS where id_order='" . $_GET["id_order"] . "'");
        $q->next_record();
		
		if($q->f("passengers2") > 0)
			FFileRead("template.detalii_comanda.html", $content);
		else
			FFileRead("template.detalii_comanda_oneway.html", $content);
		
		
        $content = str_replace("{price_currency}", ($q->f("price_currency") == 1 ? 'Euro' : 'GBP'), $content);
		
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
            }
        }
        foreach ($_POST["lista"] as $y => $value) {
            if ($y > 0) {
                $q->query("insert into LEGATURI_LISTE set id_lista = '" . $_POST["liste"] . "', id_comanda = '$y'");
            }
        }
        header("Location:index.php?action=comenzi");
        break;
    case "listec":
        $liste = '';
        FFileRead("template.listec.html", $content);
        $q->query("select * from LISTE order by nume_lista asc");
        while ($q->next_record()) {
            $liste.='<option value="' . $q->f("id_lista") . '">' . $q->f("nume_lista") . '</option>';
        }
        $content = str_replace("{lista}", $liste, $content);
        if ($_POST["id_lista"] != "" || $_GET["id_lista"] != "") {
            FFileRead("template.listec2.html", $content2);
            $content.=$content2;
            $content = str_replace("{error}", $_GET["err"], $content);
            if ($_GET["id_lista"] != "") {
                $content = str_replace("{id_lista}", $_GET["id_lista"], $content);
                $query = "select * from ORDERS c JOIN LEGATURI_LISTE lc ON c.id_order=lc.id_comanda where lc.id_lista ='" . $_GET["id_lista"] . "'";
            } elseif ($_POST["id_lista"] != "") {
                $content = str_replace("{id_lista}", $_POST["id_lista"], $content);
                $query = "select * from ORDERS c JOIN LEGATURI_LISTE lc ON c.id_order=lc.id_comanda where lc.id_lista ='" . $_POST["id_lista"] . "'";
            }
        }
        $q->query($query);
        $total = $q->nf();
        $rows = "";
        $i = 0;
        while ($q->next_record()) {
            $i++;
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td align=center><a href='index.php?action=detalii_comanda&id_order=" . $q->f("id_order") . "'>" . $q->f("id_order") . "</a></td>
	<td>" . $q->f("arrival") . "</td>
	<td>" . $q->f("destination") . "</td>
	<td>" . $q->f("flight_arrival") . "</td>
	<td>" . $q->f("numedefamilie") . " " . $q->f("prenume") . "</td>
	<td>" . $q->f("passengers") . "</td>
	<td>" . $q->f("passengers2") . "</td>
	<td><input type=text name=obs[" . $q->f("id_order") . "] size='30' value=" . $q->f("obs") . "></td>
	</tr>";
        }
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{liste}", $liste, $content);
        $content = str_replace("{total}", $total, $content);
        break;
    case "delete_comenzi_lista":
        foreach ($_POST["obs"] as $obs => $value) {
            if ($obs > 0) {
                $query = "update LEGATURI_LISTE set obs='" . stripslashes($value) . "' where id_comanda='$obs'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=listec&id_lista=" . $_POST["id_lista_hidden"]);
        break;
    case "liste":
        FFileRead("template.liste.html", $content);
        $title = $sitename_title . " - Unitati";
        if ($_POST["search"] == "")
            $query = "select * from LISTE ";
        if ($_GET["order"] != "")
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_lista";
        if ($_GET["rule"] != "")
            $query.=" " . $_GET["rule"];
        else
            $query.=" desc";
        $q->query($query);

        while ($q->next_record()) {
            if ($q->f("status") == 1) {
                $status = "Activ";
                $bg_status = "";
            } else {
                $status = "Inactiv";
                $bg_status = "bgcolor=\"000000\"";
            }

            $rows.="<tr><td align=center $bg_status><a href='index.php?action=edit_lista&cauta=" . $q->f("id_lista") . "'>" . $q->f("id_lista") . "</a></td>
	<td>" . $q->f("nume_lista") . "</td>
	<td><input type=checkbox name=check3[" . $q->f("id_lista") . "]></td>
	</tr>";
        }
        $query = "select * from LISTE where id_lista>0";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
    case "delete_unitati":
        $q2 = new Cdb;
        if (isset($_POST["check2"]))
            foreach ($_POST["check2"] as $z => $value) {
                if ($z > 0) {
                    $query = "select vip from UNITATE where id_unitate='$z'";
                    $q->query($query);
                    $q->next_record();
                    if ($q->f("vip") == 0) {
                        $days_to_add = 365;
                        $current_date = date('Y-m-d H:i:s');
                        $timeStmp = strtotime($current_date) + $days_to_add * 24 * 60 * 60;
                        $final_date = gmdate('Y-m-d H:i:s', $timeStmp);
                        $query = "update UNITATE set vip='1', start_vip=NOW(), end_vip='$final_date' where id_unitate='$z'";
                    } else
                        $query = "update UNITATE set vip='0', start_vip='', end_vip='' where id_unitate='$z'";
                    $q->query($query);
                }
            }
        if (isset($_POST["check4"]))
            foreach ($_POST["check4"] as $zz => $value) {
                if ($zz > 0) {
                    $query = "select recomandari_vip from UNITATE where id_unitate='$zz'";
                    $q->query($query);
                    $q->next_record();
                    if ($q->f("recomandari_vip") == 0) {
                        $days_to_add = 30;
                        $current_date = date('Y-m-d H:i:s');
                        $timeStmp = strtotime($current_date) + $days_to_add * 24 * 60 * 60;
                        $final_date = gmdate('Y-m-d H:i:s', $timeStmp);
                        $query = "update UNITATE set recomandari_vip='1', start_recomandari_vip=NOW(), end_recomandari_vip='$final_date' where id_unitate='$zz'";
                    } else
                        $query = "update UNITATE set recomandari_vip='0', start_recomandari_vip='', end_recomandari_vip='' where id_unitate='$zz'";
                    $q->query($query);
                }
            }
        if (isset($_POST["check3"]))
            if (isset($_POST["observatii"]))
                foreach ($_POST["observatii"] as $obs => $value) {
                    if ($obs > 0) {
                        $query = "update UNITATE set observatii='$value' where id_unitate='$obs'";
                        $q->query($query);
                    }
                }
        header("Location:index.php?action=unitati");
        break;

    case "add_lista":
        FFileRead("template.add_lista.html", $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_add_lista":
        $q->query("select * from LISTE where nume_lista='" . $_POST["nume_lista"] . "'");
        if ($q->next_record()) {
            $err = "Aceasta lista exista deja";
        } elseif ($_POST["nume_lista"] == "")
            $err = "Nume lista nu poate lipsi.";
        else {
            $q->query("insert into LISTE set nume_lista='" . $_POST["nume_lista"] . "', status='1'");
            $err = "Lista adaugata cu succes";
        }

        header("Location:index.php?action=add_lista&err=$err");
        break;
    case "edit_lista":
        FFileRead("template.edit_lista.html", $content);
        $q->query("select * from LISTE where id_lista='" . $_GET["cauta"] . "'");
        $q->next_record();
        $content = str_replace("{id_lista}", $q->f("id_lista"), $content);
        $content = str_replace("{nume_lista}", $q->f("nume_lista"), $content);
        $content = str_replace("{error}", $_GET["err"], $content);
        break;
    case "do_edit_lista":
        $q->query("select * from LISTE where nume_lista='" . $_POST["nume_lista"] . "' and id_lista<>'" . $_POST["id_lista"] . "'");
        if ($q->next_record()) {
            $err = "Aceasta lista exista deja";
        } elseif ($_POST["nume_lista"] == "")
            $err = "Nume lista nu poate lipsi.";
        else {
            $q->query("update LISTE set nume_lista='" . $_POST["nume_lista"] . "', status='1' where id_lista='" . $_POST["id_lista"] . "'");
            $err = "Lista editata cu succes";
        }

        header("Location:index.php?action=edit_lista&err=$err&id_liste=" . $_POST["id_lista"]);
        break;
    case "delete_liste":
        foreach ($_POST["check3"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from LISTE where id_lista='$x'");
                $query = "delete from LEGATURI_LISTE where id_lista='$x'";
                $q->query($query);
            }
        }
        header("Location:index.php?action=liste");
        break;
    case "delete_contacte":
        foreach ($_POST["check"] as $x => $value) {
            if ($x > 0) {
                $q->query("delete from contact where id_contact='$x'");
            }
        }
        header("Location:index.php?action=contacte");
        break;
    case "set_activ_inactiv":
        $q->query("update TARA set status=NOT status where id_tara='" . $_GET["id_tara"] . "'");
        header("Location:index.php?action=tari");
        break;
    case "set_activ_inactiv_ao":
        $q->query("update AEROPORT set status=NOT status where id_aeroport='" . $_GET["id_aeroport"] . "'");
        header("Location:index.php?action=ao");
        break;
    case "set_activ_inactiv_statii":
        $q->query("update STATII set status=NOT status where id_statie='" . $_GET["id_statie"] . "'");
        header("Location:index.php?action=statii");
        break;
    case "set_activ_inactiv_legaturi_statii":
        $q->query("update LEGATURI_STATII set status=NOT status where id_legatura_statie='" . $_GET["id_legatura_statie"] . "'");
        header("Location:index.php?action=edit_preturi&id_legaturi=" . $_GET["id_legaturi"] . "&id_auto=0");
        break;
    case "set_activ_inactiv_destinatie":
        $q->query("update DESTINATIE set status=NOT status where id_destinatie='" . $_GET["id_destinatie"] . "'");
        header("Location:index.php?action=destinatie");
        break;
    
    case "set_activ_inactiv_ao_uk":
        $q->query("update AEROPORT_UK set status=NOT status where id_aeroport='" . $_GET["id_aeroport"] . "'");
        header("Location:index.php?action=ao_uk");
        break;
    case "set_activ_inactiv_statii_uk":
        $q->query("update STATII_UK set status=NOT status where id_statie='" . $_GET["id_statie"] . "'");
        header("Location:index.php?action=statii_uk");
        break;
    case "set_activ_inactiv_legaturi_statii_uk":
        $q->query("update LEGATURI_STATII_UK set status=NOT status where id_legatura_statie='" . $_GET["id_legatura_statie"] . "'");
        header("Location:index.php?action=edit_preturi_uk&id_legaturi=" . $_GET["id_legaturi"] . "&id_auto=0");
        break;
    case "set_activ_inactiv_destinatie_uk":
        $q->query("update DESTINATIE_UK set status=NOT status where id_destinatie='" . $_GET["id_destinatie"] . "'");
        header("Location:index.php?action=destinatie_uk");
        break;
    case "set_activ_inactiv_auto":
        $q->query("update AUTO set status=NOT status where id_auto='" . $_GET["id_auto"] . "'");
        header("Location:index.php?action=auto");
        break;
    case "set_activ_inactiv_auto_uk":
        $q->query("update AUTO_UK set status=NOT status where id_auto='" . $_GET["id_auto"] . "'");
        header("Location:index.php?action=auto_uk");
        break;
    // bookings - contact
	case "contacte":
        FFileRead("template.contacte.html", $content);
        $query = "select * from contact";
        if (isset($_GET["order"]))
            $query.=" order by " . $_GET["order"];
        else
            $query.=" order by id_contact";
        if (isset($_GET["rule"]))
            $query.=" " . $_GET["rule"];
        else
            $query.=" desc";
        $q->query($query);

        $rows = "";
        $i = 0;
        while ($q->next_record()) {
            $i++;
            $rows.="<tr id=\"cell$i\" onMouseOver=\"document.all.cell$i.bgColor = '#0099ff';\" onMouseOut=\"document.all.cell$i.bgColor ='#ffffff'\";>
	<td>" . $q->f("id_contact") . "</td>
	<td>" . $q->f("nume"). "<br>" . $q->f("companie") . "<br>" . $q->f("telefon") . "<br>" . $q->f("mobil") . "<br>" . $q->f("email") . "</td>
	<td>" . $q->f("subiect") . "</td>
	<td>" . $q->f("mesaj") . "</td>
	<td>" . $q->f("pickup_date_t") . "</td>
	<td>" . $q->f("pasageri_t") . "</td>
	<td>" . $q->f("vehicol_t") . "</td>
	<td>" . $q->f("pickup_t") . "</td>
	<td>" . $q->f("destination_t") . "</td>
	<td>" . $q->f("pickup_date_r") . "</td>
	<td>" . $q->f("pasageri_r") . "</td>
	<td>" . $q->f("vehicol_r") . "</td>
	<td>" . $q->f("pickup_r") . "</td>
	<td>" . $q->f("destination_r") . "</td>
	<td><input type=checkbox name=check[" . $q->f("id_contact") . "]></td>
	</tr>";
        }
        $query = "select * from contact";
        $q->query($query);
        $content = str_replace("{rows}", $rows, $content);
        $content = str_replace("{total}", $q->nf(), $content);
        break;
}
FFileRead("template.main.htm", $main);
$main = str_replace("{content}", $content, $main);
$main = str_replace("{title}", $title, $main);
$main = str_replace("{sitename}", $sitename, $main);
$main = str_replace("{webmasteremail}", $webmasteremail, $main);
echo $main;
?>