<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
	require_once 'Zend/Controller/Action.php';
	class TimisoarabelgradeController extends Zend_Controller_Action
	{
		public $view;
		public function init()
		{
			$this->view = Zend_Registry::get('VIEW');
			$this->view->setScriptPath('application/views/scripts/timetable');
		}
	    public function indexAction()
	    {
	    	$translation = Zend_Registry::get('TRANSLATIONS');
	    	$titluri = Zend_Registry::get('TITLURI');

			include("include/vars.php");
		
		$title=$titluri["timisoarabelgradeTitle"];
		$meta=$titluri["timisoarabelgradeMeta"];
		
$this->view->assign("title",$title);
$this->view->assign("description",$meta);
$this->view->assign('title_breadcrumb','Shuttle Timisoara Belgrade');

	echo $this->view->render('timisoarabelgrade.phtml');
	//print_r ($_SESSION);

    }
}
?>