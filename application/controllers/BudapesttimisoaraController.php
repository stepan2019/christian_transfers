<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
	require_once 'Zend/Controller/Action.php';
	class BudapesttimisoaraController extends Zend_Controller_Action
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
		
		$title=$titluri["budapesttimisoaraTitle"];
		$meta=$titluri["budapesttimisoaraMeta"];
		
$this->view->assign("title",$title);
$this->view->assign("description",$meta);
$this->view->assign('title_breadcrumb','Shuttle Budapest Timisoara');

	$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);
	echo $this->view->render('budapesttimisoara.phtml');
	//print_r ($_SESSION);

    }
}
?>