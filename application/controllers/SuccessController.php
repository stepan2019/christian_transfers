<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
	require_once 'Zend/Controller/Action.php';
	class SuccessController extends Zend_Controller_Action
	{
		public $view;
		public function init()
		{
			$this->view = Zend_Registry::get('VIEW');
			$this->view->setScriptPath('application/views/scripts/aboutus');
		}
	    public function indexAction()
	    {
	    	$translation = Zend_Registry::get('TRANSLATIONS');
	    	$titluri = Zend_Registry::get('TITLURI');

			include("include/vars.php");
		
		$title='Thank you';
		$meta='';
		
$this->view->assign("title",$title);
$this->view->assign("description",$meta);

	
	$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);
	echo $this->view->render('success.phtml');
	//print_r ($_SESSION);
    }
}
?>