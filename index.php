<?php

error_reporting(E_ERROR);
ini_set('display_errors', 'off');
ini_set('include_path', 'E:\workspace\Cristian Transfer\christiantransfers.eu\Zend\library');
//require 'Zend/Loader.php';
require_once 'config.php';
require_once 'Zend/Controller/Front.php';
require_once 'Zend/Controller/Router/Route/Regex.php';
//require_once 'Zend/Controller/Plugin/ErrorHandler.php';
require_once 'functions.php';
$q = new Cdb;
$q2 = new Cdb;
$q3 = new Cdb;
$identificator = "";
// session id
$SESS_ID = session_id();
unset($_SESSION["identificator2"]);
//if (!isset($_SESSION['language']))
$_SESSION['language'] = "en";
if (isset($_GET["lang"]) && $_GET["lang"] != "") {
    $_SESSION['language'] = $_GET["lang"];
}
($_SESSION['language'] != "ro") ? $db_lang = "_" . $_SESSION['language'] : $db_lang = "";

//include translations
require_once("./translations_new/" . strtoupper($_SESSION['language']) . ".php");
Zend_Registry::set('TRANSLATIONS', $translation);

//identificarea elementului pt query
//$q->query("select url_tip".$db_lang." from TIP");
$rez = array();
//while ($q->next_record()) $rez[]=$q->f("url_tip".$db_lang);
$ldcu = "Bucharest";
$_SESSION["ldcu"] = $ldcu;
$uri = $_SERVER['REQUEST_URI'];
$ident_array1 = explode("/", $uri);
$identificator1 = $ident_array1[1]; //e partea de dupa .ro exempluL /ro/(asta_de_aici)

$uri_temp = explode("/", $uri);
$ident_array1 = end($uri_temp);
$ident_array2 = explode("-", $ident_array1);
if (isset($ident_array2[2])) {//adica exista ceva id, si este cratima, si idul este dupa cratima, in dreapta.
    $identificator2 = $ident_array2[0]; //e partea de dupa .ro si dupa primul director exemplu: .ro/director/(asta_de_aici)
    $_SESSION['identificator2'] = $identificator2;
    $_SESSION['identificator21'] = $ident_array2[1];
    $_SESSION['identificator22'] = $ident_array2[2];
}
//include titluri
require_once("./titluri/" . strtoupper($_SESSION['language']) . ".php");
Zend_Registry::set('TITLURI', $titluri);

$view = Zend_Registry::get('VIEW');
//send language charset to templates
if (isset($languageCharset[$_SESSION['language']])) {
    $view->charset = $languageCharset[$_SESSION['language']];
    Zend_Registry::set('CHARSET', $languageCharset[$_SESSION['language']]);
} else {
    $view->charset = 'utf-8';
    Zend_Registry::set('CHARSET', 'utf-8');
}

//translation assignation for header and footer
$view = Zend_Registry::get('VIEW');
//end translation assignation

try {
    $ctrl = Zend_Controller_Front::getInstance();
//    $plugin = new Zend_Controller_Plugin_ErrorHandler();
//    $plugin->setErrorHandlerModule('default')
//       ->setErrorHandlerController('error')
//       ->setErrorHandlerAction('error');
//    $ctrl->registerPlugin($plugin);
    //disable the auto view renderer, views will be rendered manually
    $ctrl->setParam('noViewRenderer', true);
    $ctrl->setParam('useDefaultControllerAlways', true);

    $router = $ctrl->getRouter();

//    $route = new Zend_Controller_Router_Route(
//            '*', array(
//            'controller'=>'error',
//            'module'=>'default',
//            'action'=>'index'
//    ));
//    $router->addRoute('default', $route);
    $route = new Zend_Controller_Router_Route_Regex(
            '', array(
        'controller' => 'index',
        'action' => 'index'
    ));
    $router->addRoute('index', $route);

    $route = new Zend_Controller_Router_Route_Regex(
        'trains', array(
        'controller' => 'trains',
        'action' => 'index'
    ));
    $router->addRoute('trains', $route);

    $route = new Zend_Controller_Router_Route_Regex(
        'buses', array(
        'controller' => 'buses',
        'action' => 'index'
    ));
    $router->addRoute('buses', $route);
    
    $route = new Zend_Controller_Router_Route(
            '404', array(
        'controller' => 'error',
        'action' => 'index'
    ));
    $router->addRoute('404', $route);

    $route = new Zend_Controller_Router_Route(
            'main', array(
        'controller' => 'main',
        'action' => 'index'
    ));
    $router->addRoute('main', $route);

    $route = new Zend_Controller_Router_Route(
            'contact', array(
        'controller' => 'contact',
        'action' => 'index'
    ));
    $router->addRoute('contact', $route);
	
	$route = new Zend_Controller_Router_Route(
            'tailor-made', array(
        'controller' => 'tailormade',
        'action' => 'index'
    ));
    $router->addRoute('tailor-made', $route);
	
    $route = new Zend_Controller_Router_Route(
            'countries', array(
        'controller' => 'countries',
        'action' => 'index'
    ));
    $router->addRoute('countries', $route);
    
    $route = new Zend_Controller_Router_Route(
            'partners', array(
        'controller' => 'partners',
        'action' => 'index'
    ));
    $router->addRoute('partners', $route);
    
    $route = new Zend_Controller_Router_Route(
            'christian-transfers-uk', array(
        'controller' => 'uk',
        'action' => 'index'
    ));
    $router->addRoute('uk', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-germany', array(
        'controller' => 'germany',
        'action' => 'index'
    ));
    $router->addRoute('germany', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-croatia', array(
        'controller' => 'croatia',
        'action' => 'index'
    ));
    $router->addRoute('croatia', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-romania', array(
        'controller' => 'romania',
        'action' => 'index'
    ));
    $router->addRoute('romania', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-serbia', array(
        'controller' => 'serbia',
        'action' => 'index'
    ));
    $router->addRoute('serbia', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-hungary', array(
        'controller' => 'hungary',
        'action' => 'index'
    ));
    $router->addRoute('hungary', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-bulgaria', array(
        'controller' => 'bulgaria',
        'action' => 'index'
    ));
    $router->addRoute('bulgaria', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-austria', array(
        'controller' => 'austria',
        'action' => 'index'
    ));
    $router->addRoute('austria', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-slovakia', array(
        'controller' => 'slovakia',
        'action' => 'index'
    ));
    $router->addRoute('slovakia', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-slovenia', array(
        'controller' => 'slovenia',
        'action' => 'index'
    ));
    $router->addRoute('slovenia', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-macedonia', array(
        'controller' => 'macedonia',
        'action' => 'index'
    ));
    $router->addRoute('macedonia', $route);

    $route = new Zend_Controller_Router_Route(
            'christian-transfers-montenegro', array(
        'controller' => 'montenegro',
        'action' => 'index'
    ));
    $router->addRoute('montenegro', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-spain', array(
        'controller' => 'spain',
        'action' => 'index'
    ));
    $router->addRoute('spain', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-albania', array(
        'controller' => 'albania',
        'action' => 'index'
    ));
    $router->addRoute('albania', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-belgium', array(
        'controller' => 'belgium',
        'action' => 'index'
    ));
    $router->addRoute('belgium', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-cyprus', array(
        'controller' => 'cyprus',
        'action' => 'index'
    ));
    $router->addRoute('cyprus', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-czech', array(
        'controller' => 'czech',
        'action' => 'index'
    ));
    $router->addRoute('czech', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-france', array(
        'controller' => 'france',
        'action' => 'index'
    ));
    $router->addRoute('france', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-greece', array(
        'controller' => 'greece',
        'action' => 'index'
    ));
    $router->addRoute('greece', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-italy', array(
        'controller' => 'italy',
        'action' => 'index'
    ));
    $router->addRoute('italy', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-ireland', array(
        'controller' => 'ireland',
        'action' => 'index'
    ));
    $router->addRoute('ireland', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-netherlands', array(
        'controller' => 'netherlands',
        'action' => 'index'
    ));
    $router->addRoute('netherlands', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-poland', array(
        'controller' => 'poland',
        'action' => 'index'
    ));
    $router->addRoute('poland', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-switzerland', array(
        'controller' => 'switzerland',
        'action' => 'index'
    ));
    $router->addRoute('switzerland', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-china', array(
        'controller' => 'china',
        'action' => 'index'
    ));
    $router->addRoute('china', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-russia', array(
        'controller' => 'russia',
        'action' => 'index'
    ));
    $router->addRoute('russia', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-turkey', array(
        'controller' => 'turkey',
        'action' => 'index'
    ));
    $router->addRoute('turkey', $route);
	
	$route = new Zend_Controller_Router_Route(
            'christian-transfers-india', array(
        'controller' => 'india',
        'action' => 'index'
    ));
    $router->addRoute('india', $route);
	
	$route = new Zend_Controller_Router_Route(
            'airport-transfers', array(
        'controller' => 'transfers',
        'action' => 'index'
    ));
    $router->addRoute('transfers', $route);

    $route = new Zend_Controller_Router_Route(
            'minibus', array(
        'controller' => 'minibus',
        'action' => 'index'
    ));
    $router->addRoute('minibus', $route);

    $route = new Zend_Controller_Router_Route(
            'coaches', array(
        'controller' => 'coaches',
        'action' => 'index'
    ));
    $router->addRoute('coaches', $route);

    $route = new Zend_Controller_Router_Route(
            'couriers', array(
        'controller' => 'couriers',
        'action' => 'index'
    ));
    $router->addRoute('couriers', $route);
	
	$route = new Zend_Controller_Router_Route(
        'taxi', array(
        'controller' => 'taxi',
        'action' => 'index'
    ));
    $router->addRoute('taxi', $route);
    
    $route = new Zend_Controller_Router_Route(
            'recovery-car', array(
        'controller' => 'recoverycar',
        'action' => 'index'
    ));
    $router->addRoute('recoverycar', $route);
	
    $route = new Zend_Controller_Router_Route(
            'limousines', array(
        'controller' => 'limousines',
        'action' => 'index'
    ));
    $router->addRoute('limousines', $route);
	
	$route = new Zend_Controller_Router_Route(
            'timetable', array(
        'controller' => 'timetable',
        'action' => 'index'
    ));
    $router->addRoute('timetable', $route);
    
    $route = new Zend_Controller_Router_Route(
            'budapest-timisoara', array(
        'controller' => 'budapesttimisoara',
        'action' => 'index'
    ));
    $router->addRoute('budapesttimisoara', $route);

    $route = new Zend_Controller_Router_Route(
            'budapest-cluj', array(
        'controller' => 'budapestcluj',
        'action' => 'index'
    ));
    $router->addRoute('budapestcluj', $route);

    $route = new Zend_Controller_Router_Route(
            'timisoara-belgrade', array(
        'controller' => 'timisoarabelgrade',
        'action' => 'index'
    ));
    $router->addRoute('timisoarabelgrade', $route);

    $route = new Zend_Controller_Router_Route(
            'otopeni-constanta', array(
        'controller' => 'otopeniconstanta',
        'action' => 'index'
    ));
    $router->addRoute('otopeniconstanta', $route);

    $route = new Zend_Controller_Router_Route(
            'about-christian-transfers', array(
        'controller' => 'aboutus',
        'action' => 'index'
    ));
    $router->addRoute('aboutus', $route);
	
	$route = new Zend_Controller_Router_Route(
            'terms-conditions', array(
        'controller' => 'terms',
        'action' => 'index'
    ));
    $router->addRoute('terms', $route);

    $route = new Zend_Controller_Router_Route(
        'privacy-policy', array(
        'controller' => 'privacy',
        'action' => 'index'
    ));
    $router->addRoute('privacy', $route);

    $route = new Zend_Controller_Router_Route('how-it-works', array('controller' => 'howitworks','action' => 'index'));
    $router->addRoute('howitworks', $route);

    $route = new Zend_Controller_Router_Route('help', array('controller' => 'help','action' => 'index'));
    $router->addRoute('help', $route);


    $route = new Zend_Controller_Router_Route_Regex(
            'airport_transfer|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'page2',
        'action' => 'index'
    ));
    $router->addRoute('page2', $route);

    $route = new Zend_Controller_Router_Route_Regex(
        'airport_trains|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'page2trains',
        'action' => 'index'
    ));
    $router->addRoute('page2train', $route);

    $route = new Zend_Controller_Router_Route_Regex(
        'airport_buses|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'page2buses',
        'action' => 'index'
    ));
    $router->addRoute('page2buses', $route);



    $route = new Zend_Controller_Router_Route_Regex(
            'dotailormade|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'doreservation',
        'action' => 'index'
    ));
    $router->addRoute('dotailormade', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'dochanges|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'dochanges',
        'action' => 'index'
    ));
    $router->addRoute('dochanges', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'docontact|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'docontact',
        'action' => 'index'
    ));
    $router->addRoute('docontact', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'details_transfer|hmm', array(
        'controller' => 'page21',
        'action' => 'index'
    ));
    $router->addRoute('page21', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'transfer_details|hmm', array(
        'controller' => 'page3',
        'action' => 'index'
    ));
    $router->addRoute('page3', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'plata_transfer|payment_details|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'plata',
        'action' => 'index'
    ));
    $router->addRoute('plata', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'details_payment|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'page31',
        'action' => 'index'
    ));
    $router->addRoute('page31', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'test_response|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'response',
        'action' => 'index'
    ));
    $router->addRoute('response', $route);
	
    $route = new Zend_Controller_Router_Route_Regex(
            'success|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'success',
        'action' => 'index'
    ));
    $router->addRoute('success', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'contacteaza|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'contacteaza',
        'action' => 'index'
    ));
    $router->addRoute('contacteaza', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'articol|article|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'articol',
        'action' => 'index'
    ));
    $router->addRoute('articol', $route);

    $route = new Zend_Controller_Router_Route_Regex(
            'articole|articles|hmm+/[a-zA-Z0-9\/\-_\(\)]', array(
        'controller' => 'articole',
        'action' => 'index'
    ));
    $router->addRoute('articole', $route);
    $redirect = 'da';
    foreach($router->getRoutes() as $route)
    {
        if ($route->match($uri)) $redirect = 'nu';

    }

    if ($redirect=='da' && strpos($uri, 'admin')===false) {
        header("Location:/404");
        die();
    }


    //run default controller
    $router->addRoute('default', new Zend_Controller_Router_Route('', array('controller' => 'index', 'action' => 'index')));
    $ctrl->run('application/controllers/');
} catch (Exception $e) {
    echo "Caught exception: " . get_class($e) . "<br />\n";
    echo "Message: " . $e->getMessage() . "\n";
}
