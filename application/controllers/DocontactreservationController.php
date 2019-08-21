<?php

/*
 * Created on Aug 06, 2008
 *
 * Created by Valizr
 * Reason: Do contact page controller
 */

	require_once 'Zend/Controller/Action.php';

	class DocontactreservationController extends Zend_Controller_Action
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
			$this->view->setScriptPath(BASE_PATH.'application/views/scripts/contactreservation');

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
if ($_POST["nume"]=="") {
	$err_ro="Numele nu poate lipsi<br>";	$err_en="Name can not be empty<br>";
}
if ($_POST["email"]=="") {
	$err_ro.="Emailul nu poate lipsi<br>";	$err_en="Email can not be empty<br>";
}
if ($_POST["fix"]=="" && $_POST["mobil"]==""){
	$err_ro.="Cel putin un numar de telefon nu poate lipsi<br>";	$err_en.="At least a phone number is necessary<br>";
}
if ($_POST["subiect"]=="") {
	$err_ro.="Subiectul mesajului nu poate lipsi<br>";	$err_en.="The subject of the message can not be empty<br>";
}
if ($_POST["mesaj"]==""){
	$err_ro.="Mesajul nu poate lipsi<br>";	$err_en.="The message can not be empty<br>";
}
if (md5($_POST["verificare"])!=$_SESSION['image_random_value']) {
	$err_ro.="Textul de verificare e gresit<br>";	$err_en.="The verification text is wrong<br>";
}
if ($err_ro!=""){
	$err="<font color=red>${"err_".$language}</font>";
	$this->view->assign("error",$err);
	$title=$titluri["contactTitle"];
	$meta=$titluri["contactMeta"];
	$banners=$this->view->render('../common/banners.phtml');
	$this->view->assign("title",$title);
	$this->view->assign("description",$meta);
	$this->view->assign("banners",$banners);
	$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);
echo $this->view->render('contact.phtml');
die();
}
mail($webmasteremail,"Christian Transfers transfer request - ".$_POST["subiect"],
            "
             Name: ".$_POST["nume"]."  
             E-Mail: ".$_POST["email"]." 
			 Telefon fix: ".$_POST["fix"]."
			 Telefon mobil: ".$_POST["mobil"]."
             Subject: ".$_POST["subiect"]."
             Information: ".$_POST["mesaj"]."

            ", "From: ".$_POST["nume"]." <".$_POST["email"].">");          
	$this->view->assign("error",$translation["mail_trimis"]);	
	echo $this->view->render('../common/error.phtml');
	    }
	}

?>