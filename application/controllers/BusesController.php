<?php

/*
ce face pagina:
	1 citeste zonele din tabela ZONA
	cat timp sunt zone, citeste id si stringul de valori ce reprezinta tipul, din tabela UNITATE
		cat timp exista unitati
			daca tipul cautat de vizitator se afla in stringul cu tipul
				selecteaza stringul cu zonele unitatii la care se afla pointerul, din tabela UNITATE
					daca idul zonei citite se afla in stringul cu zonele unitatii
					atunci mareste numarul de unitati din acea zona: nr_unitati++
			daca nu, atunci unitatea nu este de tipul cautat atunci nr_unitati este 0.		
					
 */

require_once 'Zend/Controller/Action.php';

class BusesController extends Zend_Controller_Action
{
    public $view;
    protected $db;
    public $sitename;

    public function init()
    {

        //initialize Smarty templating class
        $this->view = Zend_Registry::get('VIEW');
        $this->view->setScriptPath(BASE_PATH . 'application/views/scripts/buses');

        //language assignation
        $this->view->assign("language", $_SESSION["language"]);
    }

    public function indexAction()
    {

        //get Translation registry
        $translation = Zend_Registry::get('TRANSLATIONS');
        $titluri = Zend_Registry::get('TITLURI');
        //get prefixes registry
        //$prefixes = Zend_Registry::get('PREFIXES');
        //$this->assignDayMonthYear();

        //assigning flags change
        include("include/vars.php");
        /*	$varptcautare='body onload='."\"ajax_loadContent('zona','/application/schimba.php?zona=set');ajax_loadContent('specific','/application/schimba.php?specific=set');initte();\"";
            $this->view->assign("varptcautare",$varptcautare);
            $query="select u.id_unitate,u.nume_unitate,u.adresa,u.zona,u.descriere_".$language.",u.poza1,u.tip, u.icon from UNITATE u JOIN MEMBRII m where u.status='1' and u.id_user=m.id_user order by m.data_inscriere desc limit 0,10";
            $q->query($query);
            if ($q->nf()>$limit) {
                    $start_page=end(explode("_",$_SERVER['REQUEST_URI']));
                        if (is_numeric($start_page)) $start=($start_page-1)*$limit;
                        if (!isset($start)) $start="0";
                                        $this->view->assign("show_pages",show_pages($q->nf(),$limit));
                        $query.=" limit $start,$limit";
                }
            $rez=makeQuery($query);
                $temp_rez = array();
                $zonaunitate = array();
                    foreach($rez as $item)
                    {
                        $tip_de_unitate=substr($item[6],0,1);
                        $q2->query("select sg_nume_tip".$db_lang.", url_tip".$db_lang." from TIP where id_tip='$tip_de_unitate'");
                        $q2->next_record();
                        $item[1]=$q2->f("sg_nume_tip".$db_lang)." ".$item[1];

                        $zonele_unitate=explode(",",$item[3]);//aflu zonele in care e situata unitatea
                        $item[3]="";
                        $zonele_unitate=array_filter($zonele_unitate);//array filter removes empty values
                        $temp_zona = array();
                        foreach ($zonele_unitate as $zona_unitate){
                            $query2="select * from ZONA where id_zona='$zona_unitate'";
                            $q->query($query2);
                            $q->next_record();
                            $item[3].="<a href=\"".makeUrl($identificator_url,$q->f("id_zona"),$q->f("url_zona"))."\">".$q->f("nume_zona")."</a>, ";
                        }
                        $item[3] = substr($item[3],0,-2);
                        $item[5]=($item[5]!="")?substr($item[5],0,-4)."_tn.jpg":"/images/fara_poza.jpg";
                        $item[6] = makeUrl($translation["mm_detalii_small"].strtolower($translation["tr_".$q2->f("url_tip".$db_lang)]),$item[0],str_replace(" ","_",strtolower($item[1])));
                        $item[4] = shorten_string($item[4],10)."...";
                        array_push($temp_rez,$item);
                    }
                    $rez = $temp_rez;
                    $this->view->assign('rezultate',$rez);
                    $this->view->assign('tip_unitate',$translation["sg_".strtolower($translation["tr_".$q2->f("url_tip".$db_lang)])]);
                    $this->view->assign('nrRezultate',count($rez));
            */

        $this->view->assign("title", $title);
        $this->view->assign("description", $meta);
        $latime_tabel_dreapta = "patru40";//default sa fie 587px
        $banners = $this->view->render('../common/banners.phtml');
        $this->view->assign("banners", $banners);
        $this->view->assign("latime_tabel_dreapta", $latime_tabel_dreapta);
        echo $this->view->render('buses.phtml');
    }
}

?>
