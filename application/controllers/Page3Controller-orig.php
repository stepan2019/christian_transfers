<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
	require_once 'Zend/Controller/Action.php';
	class Page3Controller extends Zend_Controller_Action
	{
		public $view;
		public function init()
		{
			$this->view = Zend_Registry::get('VIEW');
			$this->view->setScriptPath('application/views/scripts/page3');
		}
	    public function indexAction()
	    {
	    	$translation = Zend_Registry::get('TRANSLATIONS');
	    	$titluri = Zend_Registry::get('TITLURI');

			include("include/vars.php");
			$q->query("select id_tara from TARA where nume_tara='".ucfirst($identificator2)."'");
	$q->next_record();
	$id_tara=$q->f("id_tara");

			$_SESSION["pickup_tara"]=$id_tara;
			$q->query("select id_aeroport, nume_aeroport from AEROPORT where url_aeroport='".$identificator21."'");
			$q->next_record();
	$id_aeroport=$q->f("id_aeroport");
	$nume_aeroport=$q->f("nume_aeroport");
			$_SESSION["pickup_locatie"]=$id_aeroport;

			$q->query("select id_destinatie,nume_destinatie from DESTINATIE where url_destinatie='".$identificator22."'");
			$q->next_record();
	$id_destinatie=$q->f("id_destinatie");
	$nume_destinatie=$q->f("nume_destinatie");
			$_SESSION["id_destinatie"]=$id_destinatie;
			
	if (isset($_POST["arrival_auto"])) {
		($_POST["arrival_auto"]==9999)?$arrival_auto=0:$arrival_auto=$_POST["arrival_auto"];
		$q->query("select p.pret from LEGATURI l join PRETURI p ON (l.id_legaturi=p.id_legaturi) where l.id_aeroport='$id_aeroport' and l.id_destinatie='$id_destinatie' and p.id_auto='$arrival_auto'");
		$q->next_record();
		$arrival_pret=$q->f("pret");
		$final_price_euro=$arrival_pret;
	}
	if (isset($_POST["dep_auto"]) && $_SESSION["oneway"]==0) {
		($_POST["dep_auto"]==9999)?$dep_auto=0:$dep_auto=$_POST["dep_auto"];
		$q->query("select p.pret from LEGATURI l join PRETURI p ON (l.id_legaturi=p.id_legaturi) where l.id_aeroport='$id_aeroport' and l.id_destinatie='$id_destinatie' and p.id_auto='$dep_auto'");
		$q->next_record();
		$dep_pret=$q->f("pret");		
		$this->view->assign("dep_auto",$dep_pret);
		$final_price_euro+=$dep_pret;
	}
	
$year=substr($_SESSION["arrival_month"],0,4);
$month=(substr($_SESSION["arrival_month"],4,5)==0)?substr($_SESSION["arrival_month"],5,6):substr($_SESSION["arrival_month"],4,6);

$arrival_time=date("d-M-Y")." ".$_SESSION["arrival_hour"].":".(strlen($_SESSION["arrival_min"])==1?"0".$_SESSION["arrival_min"]:$_SESSION["arrival_min"]);

$timestamp = strtotime(date("d-M-Y ".$_SESSION["arrival_hour"].":".(strlen($_SESSION["arrival_min"])==1?"0".$_SESSION["arrival_min"]:$_SESSION["arrival_min"])));

$pickup_time="<select name=\"pickup_time\" id=\"pickup_time\">";
for ($i=0;$i<1440;$i+=15){
	$etime = strtotime("+$i minutes", $timestamp);
	$pickup_time.="<option ".(($i==0)?"selected":"")."value=".date('d-m-Y H:i:s', $etime).">".date('d-M-Y H:i', $etime)."</option>";
}
$pickup_time.="</select>";


$year2=substr($_SESSION["dep_month"],0,4);
$month2=(substr($_SESSION["dep_month"],4,5)==0)?substr($_SESSION["dep_month"],5,6):substr($_SESSION["dep_month"],4,6);

$dep_time=date("d-M-Y")." ".$_SESSION["dep_hour"].":".(strlen($_SESSION["dep_min"])==1?"0".$_SESSION["dep_min"]:$_SESSION["dep_min"]);

$timestamp2 = strtotime(date("d-M-Y ".$_SESSION["dep_hour"].":".(strlen($_SESSION["dep_min"])==1?"0".$_SESSION["dep_min"]:$_SESSION["dep_min"])));

$pickup_time2="<select name=\"pickup_time\" id=\"pickup_time\">";
for ($i=0;$i<1440;$i+=15){
	$etime = strtotime("+$i minutes", $timestamp2);
	$pickup_time2.="<option ".(($i==0)?"selected":"")."value=".date('d-m-Y H:i:s', $etime).">".date('d-M-Y H:i', $etime)."</option>";
}
$pickup_time2.="</select>";


    //This is a PHP(4/5) script example on how eurofxref-daily.xml can be parsed
    //Read eurofxref-daily.xml file in memory 
    //For this command you will need the config option allow_url_fopen=On (default)
    $XMLContent=file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
    //the file is updated daily between 2.15 p.m. and 3.00 p.m. CET
 
    foreach($XMLContent as $line){
        if(preg_match("/currency='([[:alpha:]]+)'/",$line,$currencyCode)){
            if(preg_match("/rate='([[:graph:]]+)'/",$line,$rate)){
                //Output the value of 1EUR for a currency code
                if ($currencyCode[1]=='RON') $final_price_lei=$rate[1]*$final_price_euro;
                //--------------------------------------------------
                //Here you can add your code for inserting
                //$rate[1] and $currencyCode[1] into your database
                //--------------------------------------------------
            }
        }
}

	$this->view->assign("arrival_auto",$arrival_pret);	
	$this->view->assign("tara",$identificator2);
	$this->view->assign("url_aeroport",$identificator21);
	$this->view->assign("url_destinatie",$identificator22);
	
	$this->view->assign("arrival_airport",$nume_aeroport);
	$this->view->assign("destination",$nume_destinatie);
	$this->view->assign("arrival_time",$arrival_time);
	$this->view->assign("arrival_passengers",$_SESSION["arrival_passengers"]);
	$this->view->assign("pickup_time",$pickup_time);
	
	$this->view->assign("extra1",extraOption(50));
	$this->view->assign("extra2",extraOption(50));
	$this->view->assign("extra3",extraOption(10));
	$this->view->assign("extra4",extraOption(10));
	$this->view->assign("extra5",extraOption(30));
	$this->view->assign("extra6",extraOption(10));
	$this->view->assign("extra7",extraOption(10));
	$this->view->assign("extra8",extraOption(5));
	
	$this->view->assign("dep_airport",$nume_destinatie);
	$this->view->assign("dep_destination",$nume_aeroport);
	$this->view->assign("dep_time",$dep_time);
	$this->view->assign("dep_passengers",$_SESSION["dep_passengers"]);
	$this->view->assign("dep_pickup_time",$pickup_time2);
	
	$this->view->assign("extra21",extraOption(50));
	$this->view->assign("extra22",extraOption(50));
	$this->view->assign("extra23",extraOption(10));
	$this->view->assign("extra24",extraOption(10));
	$this->view->assign("extra25",extraOption(30));
	$this->view->assign("extra26",extraOption(10));
	$this->view->assign("extra27",extraOption(10));
	$this->view->assign("extra28",extraOption(5));

	$this->view->assign("return_journey",$_SESSION["oneway"]);
	$this->view->assign("final_price_euro",$final_price_euro);
	$this->view->assign("final_price_lei",$final_price_lei);
	$_SESSION["final_price_lei"]=$final_price_lei;
	$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);
	echo $this->view->render('page3.phtml');
    }
}
?>