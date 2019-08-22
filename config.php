<?php

	error_reporting (E_ALL);
	session_start();

	//all requires go here
	require_once 'basepath.php';
//	require_once 'Zend/Cache.php';
	require_once 'Zend/Db.php';
	require_once 'Zend/Debug.php';
	require_once 'Zend/Registry.php';
	require_once BASE_PATH.'application/libraries/Zend_View_Smarty.php';
//	require_once BASE_PATH.'application/libraries/functions.php';


	$languageCharset = array (
		'ar'=>'utf-8',
		//'cz'=>'utf-8',
		'cz'=>'utf-8',
		'de'=>'iso-8859-1',
		'dk'=>'iso-8859-1',
		'en'=>'iso-8859-1',
		'es'=>'iso-8859-1',
		'fi'=>'iso-8859-1',
		'fr'=>'iso-8859-1',
		'hu'=>'utf-8',
		'in'=>'utf-8',
		'it'=>'iso-8859-1',
		'jp'=>'utf-8',
		'kr'=>'utf-8',
		'nl'=>'iso-8859-1',
		'no'=>'iso-8859-1',
		'po'=>'iso-8859-1',
		'pt'=>'iso-8859-1',
		'ro'=>'iso-8859-1',
		'ru'=>'utf-8',
		'sc'=>'utf-8',
		'se'=>'iso-8859-1',
		'sk'=>'iso-8859-1',
		'tr'=>'iso-8859-1'
	);
	Zend_Registry::set('LANGUAGE_CHARSET', $languageCharset);


	//initialize Smarty templating class
	$view = new Zend_View_Smarty();
	Zend_Registry::set('VIEW', $view);
