<?php

/*
 * Created on 21 dec, 2009
 *
 * Created by Valizr
 * Reason: Signup page controller
 */

	require_once 'Zend/Controller/Action.php';

	class TailormadeController extends Zend_Controller_Action
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
	$title=$titluri["tailorTitle"];
	$meta=$titluri["tailorMeta"];
	
	$this->view->assign("title",$title);
	$this->view->assign("description",$meta);
	$this->view->assign("banners",$banners);
	$this->view->assign("value_pasageri_t",'');
	$this->view->assign("value_pasageri_r",'');
	$this->view->assign("return_selected",'nu');

	echo $this->view->render('tailormade.phtml');
	}
}