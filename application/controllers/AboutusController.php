<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
	require_once 'Zend/Controller/Action.php';
	class AboutusController extends Zend_Controller_Action
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
		
		$title=$titluri["aboutusTitle"];
		$meta=$titluri["aboutusMeta"];
		
$this->view->assign("title",$title);
$this->view->assign("description",$meta);

	
	$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);
	echo $this->view->render('aboutus.phtml');
	//print_r ($_SESSION);
    }
}
?>