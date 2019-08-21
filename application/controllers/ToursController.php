<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
	require_once 'Zend/Controller/Action.php';
	class ToursController extends Zend_Controller_Action
	{
		public $view;
		public function init()
		{
			$this->view = Zend_Registry::get('VIEW');
			$this->view->setScriptPath('application/views/scripts/tours');
		}
	    public function indexAction()
	    {
	    	$translation = Zend_Registry::get('TRANSLATIONS');
	    	$titluri = Zend_Registry::get('TITLURI');

			include("include/vars.php");
		
		$title=$titluri["toursTitle"];
		$meta=$titluri["toursMeta"];
		
$this->view->assign("title",$title);
$this->view->assign("description",$meta);
$this->view->assign('transfers_active','');
$this->view->assign('coaches_active','');
$this->view->assign('vip_active','');
$this->view->assign('contact_active','');
$this->view->assign('tours_active',' id="menu_active"');
	
	$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);
	echo $this->view->render('tours.phtml');
	//print_r ($_SESSION);
    }
}
?>