<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
	require_once 'Zend/Controller/Action.php';
	class RecoverycarController extends Zend_Controller_Action
	{
            public $view;
            public function init()
            {
                    $this->view = Zend_Registry::get('VIEW');
                    $this->view->setScriptPath('application/views/scripts/transfers');
            }
	    public function indexAction()
	    {
	    	$translation = Zend_Registry::get('TRANSLATIONS');
	    	$titluri = Zend_Registry::get('TITLURI');

			include("include/vars.php");
		
		$title=$titluri["recoverycarTitle"];
		$meta=$titluri["recoverycarMeta"];
		
                $this->view->assign("title",$title);
                $this->view->assign("description",$meta);

	echo $this->view->render('recoverycar.phtml');
	//print_r ($_SESSION);

    }
}
?>