<?php

/*
 * Created on 21 dec, 2009
 *
 * Created by Valizr
 * Reason: Signup page controller
 */

	require_once 'Zend/Controller/Action.php';

	class ContactController extends Zend_Controller_Action
	{
//		protected $db, $dbw;
		public $view;
		
		public function init()
		{
			$this->view = Zend_Registry::get('VIEW');
			$this->view->setScriptPath(BASE_PATH.'application/views/scripts/contact');

			//language assignation
			//$this->view->assign("language",$_SESSION['language']);
		}

	    public function indexAction()
	    {
	$translation = Zend_Registry::get('TRANSLATIONS');
	$titluri = Zend_Registry::get('TITLURI');
	require_once("IndexController.php");

	include("include/vars.php");
	$banners=$this->view->render('../common/banners.phtml');
	$title=$titluri["contactTitle"];
	$meta=$titluri["contactMeta"];
	
	$this->view->assign("title",$title);
	$this->view->assign("description",$meta);
	$this->view->assign("banners",$banners);
	$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);

$this->view->assign('value_nume','');
$this->view->assign('value_email','');
$this->view->assign('value_companie','');
$this->view->assign('value_mobil','');
$this->view->assign('value_subiect','');
$this->view->assign('value_mesaj','');

	echo $this->view->render('contact.phtml');
	}
}