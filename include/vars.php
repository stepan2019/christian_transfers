<?php

$browser = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$pos = strpos($browser, "Opera");
if ($pos === false) {
    $pos2 = strpos($browser, "Firefox");
    if ($pos2 === false)
        $this->view->assign("mozilla", "");
    else
        $this->view->assign("mozilla", "_mozilla");
} else
    $this->view->assign("mozilla", "_mozilla");
$serverpath = $_SERVER['DOCUMENT_ROOT'];
$webmasteremail = "contact@christiantransfers.eu";
$ldcu = $_SESSION["ldcu"];
//$varptcautare='body onload='."\"initte();\"";
$varptcautare = "body";
$identificator2 = "";
$identificator21 = "";
$identificator22 = "";

$this->view->assign("varptcautare", $varptcautare);
$identificator_url = (isset($_SESSION['identificator_url'])) ? $_SESSION['identificator_url'] : "";
//$identificator = $_SESSION['identificator'];
$identificator_id = (isset($_SESSION['identificator_id'])) ? $_SESSION['identificator_id'] : "";
if (isset($_SESSION['identificator_id2']))
    $identificator_id2 = $_SESSION['identificator_id2'];
if (isset($_SESSION['identificator2'])) {
    $identificator2 = $_SESSION['identificator2'];
    $identificator21 = $_SESSION['identificator21'];
    $identificator22 = $_SESSION['identificator22'];
}
$language = $_SESSION['language'];
($language != "ro") ? $db_lang = "_" . $language : $db_lang = "";
/* if ($language=="ro") $new_lang="www";
  if ($language=="en") $new_lang="en"; */
$sitename = "http://www.christiantransfers.eu";
$sitename_footer = "ChristianTransfers.eu";
$this->view->assign("sitename", $sitename);
$this->view->assign("sitename_footer", $sitename_footer);
$this->view->assign('en_link', changeLanguageUrl('en', $language));
$this->view->assign('ro_link', changeLanguageUrl('ro', $language));

$script_name = $_SERVER['REQUEST_URI'];
$script_name = substr($script_name, 1);
//echo $script_name;
$this->view->assign("change_lang_page", $script_name);
//$language = (isset($_SESSION['language']))?$_SESSION['language']:"ro";
//$_SESSION['language']=$language;
$repl_ch = array("_", "-");
$replace_ch = array(" ", "-", "&");
$replace_ch1 = array(" ?", "'");
$q = new Cdb;
$q2 = new Cdb;
$q3 = new Cdb;
$t = new Cdb;
$stele = "";
$err_en = "";
$err_ro = "";
$contor_color = "";
$ok = "";
$l = "";
$k = "";
$ok1 = "";
$ok2 = "";
$ok3 = "";
$ok4 = "";
$ok5 = "";
$ok6 = "";
$ok7 = "";
$ok8 = "";
$ok9 = "";
$oocontent = "";
$ocontent = "";
$contor = 0;
$facilitati = "";
$pi = 0;
$contorp = 0;
$poza = "";
$poza1 = "";
$poza2 = "";
$poza3 = "";
$poza4 = "";
$poza5 = "";
$poza6 = "";
$poza7 = "";
$stel8e = "";
$start = 0;
$limit = 10;
$id_order = 200000;

$this->view->assign("acasa", $sitename);
$this->view->assign("language", $language);
$cautare_rapida = ezmakeUrl("cautare", "christian-transfers");
$this->view->assign("cautare_rapida", $cautare_rapida);

$contact = ezmakeUrl("contact", "christian_transfers");
$this->view->assign("contact", $contact);

$docontact = ezmakeUrl("docontact", "christian_transfers");
$this->view->assign("docontact", $docontact);

$docontactreservation = ezmakeUrl("dotailormade", "christian_transfers");
$this->view->assign("docontactreservation", $docontactreservation);
/* 		$termeni = makeUrl("termeni","Conditii_Cazare_Hotel_Pensiuni",$ldcu);
  $this->view->assign("termeni",$termeni); */

/* $query="select * from TARA order by nume_tara asc";

  $rez = makeQuery($query);

  $temp_rez = array();
  $item[1] = "<option value=\"\" selected>Choose</option>";
  array_push($temp_rez,$item);
  foreach($rez as $item)
  {
  $selected="";
  //if ($item[1]=="Romania") $selected=" selected";
  //else $selected="";
  $item[1] = "<option value=\"".$item[0]."\"$selected>".$item[1]."</option>";
  array_push($temp_rez,$item);
  }
  $rez = $temp_rez;

  $this->view->assign('tara',$rez);
  $this->view->assign('nrTara',count($rez)); */

$year = date('Y');
$this->view->assign("year", $year);
foreach ($translation as $replace => $value) {
    if (isset($replace))
        $this->view->assign($replace, $value);
}
/* 	for ($st==1;$st<11;$st++){	
  FFileRead("$st.txt",$text);
  $nr_texte=substr($text,-6,5);
  $nr_texte=explode("/",$nr_texte);
  $x=rand(1,$nr_texte[1]);
  $text1provizoriu=explode("<$x>",$text);
  $text=$text1provizoriu[1];
  $text1provizoriu=explode("</$x>",$text);
  $text=$text1provizoriu[0];
  $this->view->assign("text".$st."_seo",$text);
  } */
$title = $titluri["indexTitle"];
$meta = $titluri["indexMeta"];
$cuvinte_rezervate = array("Christian Transfers", "Gatwick airport transfers", "Heathrow airport transfers", "Bucharest Veliko Sofia Varna transfers", "Bucharest airport transfers", "Budapest Cluj Oradea Timisoara Arad shuttle", "Bucharest Constanta Mamaia shuttle", "Dusseldorf airport transfers", "Munich airport transfers");

$latime_tabel_dreapta = "cinci87"; //default sa fie 587px
/* $cuvinte_rezervate_boolean=true;
  foreach ($cuvinte_rezervate as $cuv_rezervate) {
  if (strpos($identificator,$cuv_rezervate)!==false)
  $cuvinte_rezervate_boolean=false;
  } */

if (isset($bread_crumbs))
    $this->view->assign('bread_crumbs', $bread_crumbs);
$this->view->assign("ldcu", $ldcu);
$rez = "";
if ($identificator2) {
    $q->query("select id_tara,nume_tara from TARA where nume_tara='" . ucfirst($identificator2) . "'");
    $q->next_record();
    $id_tara = $q->f("id_tara");
    $uk = '';
    if ($id_tara == 34){
        $uk = '_UK';
    }
    $nume_tara = $q->f("nume_tara");

    $_SESSION["pickup_tara"] = $id_tara;
    //echo "tara ". $_SESSION["pickup_tara"];
    $q->query("select id_aeroport,nume_aeroport from AEROPORT$uk where url_aeroport='" . $identificator21 . "'");
    $q->next_record();
    $id_aeroport = $q->f("id_aeroport");
    $nume_aeroport = $q->f("nume_aeroport");
    $_SESSION["pickup_locatie"] = $id_aeroport;

    
    if (isset($_SESSION["pickup_locatie_return"])) {
        $id_aeroport_return = $_SESSION["pickup_locatie_return"];
        $q->query("select id_aeroport, nume_aeroport, nume_tara from AEROPORT$uk a join TARA t on a.id_tara = t.id_tara where id_aeroport='" . $id_aeroport_return . "'");
        $q->next_record();
        $id_aeroport_return = $q->f("id_aeroport");
        $nume_aeroport_return = $q->f("nume_aeroport");
        $nume_tara_return = $q->f("nume_tara");
    } else {
        $q->query("select id_aeroport,nume_aeroport from AEROPORT$uk a join TARA t on a.id_tara = t.id_tara where url_aeroport='" . $identificator22 . "'");
        $q->next_record();
        $id_aeroport_return = $q->f("id_aeroport");
        $nume_aeroport_return = $q->f("nume_aeroport");
        $nume_tara_return = $q->f("nume_tara");
        $_SESSION["pickup_locatie_return"] = $id_aeroport_return;//dest primului transfer e pornirea celui de-al doilea transfer
    }

    $q->query("select id_destinatie,nume_destinatie,extra_info from DESTINATIE$uk where url_destinatie='" . $identificator22 . "'");
    $q->next_record();
    $id_destinatie = $q->f("id_destinatie");
    $nume_destinatie = $q->f("nume_destinatie");
    
    if (isset($_SESSION["id_destinatie_return"])) {
        $id_destinatie_return = $_SESSION["id_destinatie_return"];
        $q->query("select id_destinatie,nume_destinatie,extra_info from DESTINATIE$uk where id_destinatie='" . $id_destinatie_return . "'");
        $q->next_record();
        $nume_destinatie_return = $q->f("nume_destinatie");
    } else {
        $q->query("select id_destinatie,nume_destinatie,extra_info from DESTINATIE$uk where url_destinatie='" . $identificator21 . "'");
        $q->next_record();
        $id_destinatie_return = $q->f("id_destinatie");
        $nume_destinatie_return = $q->f("nume_destinatie");
    }
    
    $extra_info = $q->f("extra_info");
    $_SESSION["id_destinatie"] = $id_destinatie;
    $_SESSION["id_destinatie_return"] = $id_destinatie_return;
    $_SESSION["extra_info"] = $extra_info;

    $q2->query("select nume_destinatie, url_destinatie from DESTINATIE$uk d join LEGATURI$uk l on d.id_destinatie=l.id_destinatie where l.id_aeroport = '$id_aeroport' ORDER BY RAND() LIMIT 10"); //destinatii diferite, acelasi aeroport
    $extra_info1 = '';
    while ($q2->next_record()) {
        $extra_info1.='<a href="/airport_transfer/' . $identificator2 . '-' . $identificator21 . '-' . $q2->f("url_destinatie") . '">' . $nume_aeroport . ' - ' . $q2->f("nume_destinatie") . '</a><br>';
    }
    $_SESSION["extra_info1"] = $extra_info1;

    $q2->query("select nume_aeroport, url_aeroport, nume_tara from AEROPORT$uk a join LEGATURI$uk l on a.id_aeroport=l.id_aeroport JOIN TARA t on a.id_tara=t.id_tara where l.id_destinatie = '$id_destinatie' ORDER BY RAND() LIMIT 10"); //aeroporturi diferite, aceeasi destinatie
    $extra_info2 = '';
    while ($q2->next_record()) {
        $extra_info2.='<a href="/airport_transfer/' . strtolower($q2->f("nume_tara")) . '-' . $q2->f("url_aeroport") . '-' . $identificator22 . '">' . $q2->f("nume_aeroport") . ' - ' . $nume_destinatie . '</a><br>';
    }
    $_SESSION["extra_info2"] = $extra_info2;
}

$q->query("select * from testimonials where id_campaign='1' and checked='1' order by RAND() limit 0,1");
$q->next_record();
$this->view->assign('testimonial_name', $q->f("member_name"));
$this->view->assign('testimonial_text', $q->f("member_text"));
$this->view->assign('transfers_active', ' id="menu_active"');
$this->view->assign('coaches_active', '');
$this->view->assign('vip_active', '');
$this->view->assign('contact_active', '');
$this->view->assign('tours_active', '');
?>