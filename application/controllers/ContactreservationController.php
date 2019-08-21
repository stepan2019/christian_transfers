<?php

/*
 * Created on 21 dec, 2009
 *
 * Created by Valizr
 * Reason: Signup page controller
 */

	require_once 'Zend/Controller/Action.php';

	class ContactreservationController extends Zend_Controller_Action
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

$this->view->assign('transfers_active','');
$this->view->assign('coaches_active','');
$this->view->assign('vip_active','');
$this->view->assign('contact_active',' id="menu_active"');
$this->view->assign('tours_active','');
	echo $this->view->render('contactreservation.phtml');
	}
}