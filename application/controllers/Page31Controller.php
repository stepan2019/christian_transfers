<?php

/*
 * Created on Aug 06, 2008
 *
 * Created by Valizr
 * Reason: Signup page controller
 */

	require_once 'Zend/Controller/Action.php';

	class Page31Controller extends Zend_Controller_Action
	{
//		protected $db, $dbw;
		public $view;
		
		public function init()
		{
//			//get database and cache
//			$this->db = Zend_Registry::get('DB');
//			$this->dbw = Zend_Registry::get('DBW');

			//initialize Smarty templating class
			$this->view = Zend_Registry::get('VIEW');
			$this->view->setScriptPath(BASE_PATH.'application/views/scripts/plata');

			//language assignation
			//$this->view->assign("language",$_SESSION['language']);
		}

	    public function indexAction()
	    {
	    	
	    	//get Translation registry
	    	$translation = Zend_Registry::get('TRANSLATIONS');
	    	$titluri = Zend_Registry::get('TITLURI');
	    	//get prefixes registry
	    	//$prefixes = Zend_Registry::get('PREFIXES');

	    	require_once("IndexController.php");
			//IndexController::assignDayMonthYear();

	    	//assigning flags change
		    include("include/vars.php");

		    $rezultat_array=Array();

		    //$rezultat_array[14]=$_POST["first_name"];
//if (!isset($_SESSION["resultat_array"][16])){
$_SESSION["rezultat_array"][14]=$_POST["first_name"];
$_SESSION["rezultat_array"][15]=$_POST["last_name"];
$_SESSION["rezultat_array"][16]=$_POST["gender"];
$_SESSION["rezultat_array"][17]=($_POST["ccode"].$_POST["telephone"]);
$_SESSION["rezultat_array"][18]=$_POST["email"];
$_SESSION["rezultat_array"][20]=$_POST["flight_departure_from"];
$_SESSION["rezultat_array"][21]=$_POST["flight_number"];
$_SESSION["rezultat_array"][23]=$_POST["pickup_time"];
$_SESSION["rezultat_array"][24]=$_POST["address"];
$_SESSION["rezultat_array"]["extra1"]=$_POST["extra1"];
$_SESSION["rezultat_array"]["extra2"]=$_POST["extra2"];
$_SESSION["rezultat_array"]["extra3"]=$_POST["extra3"];
$_SESSION["rezultat_array"]["extra4"]=$_POST["extra4"];
$_SESSION["rezultat_array"]["extra5"]=$_POST["extra5"];
$_SESSION["rezultat_array"]["extra6"]=$_POST["extra6"];
$_SESSION["rezultat_array"]["extra7"]=$_POST["extra7"];
$_SESSION["rezultat_array"]["extra8"]=$_POST["extra8"];
$_SESSION["rezultat_array"]["country"]=$_POST["country"];
if ($_SESSION["oneway"]==0){
	$_SESSION["rezultat_array"][26]=$_POST["departure_time"];
	$_SESSION["rezultat_array"][27]=$_POST["flight_departure_to"];
	$_SESSION["rezultat_array"][28]=$_POST["flight_number2"];
	$_SESSION["rezultat_array"][29]=$_POST["pickup_time2"];
	$_SESSION["rezultat_array"][30]=$_POST["address2"];
	$_SESSION["rezultat_array"]["extra21"]=$_POST["extra21"];
	$_SESSION["rezultat_array"]["extra22"]=$_POST["extra22"];
	$_SESSION["rezultat_array"]["extra23"]=$_POST["extra23"];
	$_SESSION["rezultat_array"]["extra24"]=$_POST["extra24"];
	$_SESSION["rezultat_array"]["extra25"]=$_POST["extra25"];
	$_SESSION["rezultat_array"]["extra26"]=$_POST["extra26"];
	$_SESSION["rezultat_array"]["extra27"]=$_POST["extra27"];
	$_SESSION["rezultat_array"]["extra28"]=$_POST["extra28"];
}

$plata = ezmakeUrl("payment_details",$identificator2."-".$identificator21."-".$identificator22);

//$plata = ezmakeUrl("paypal_payment",$identificator2."-".$identificator21."-".$identificator22);	
	//$plata = ezmakeUrl("plata_transfer",$identificator2."-".$identificator21."-".$identificator22);	
	
	//echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=$plata\">";
	header("Location:$plata");
	}
}
