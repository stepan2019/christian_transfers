<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
	require_once 'Zend/Controller/Action.php';
	class Page3Controller extends Zend_Controller_Action
	{
		public $view;
		public function init()
		{
			$this->view = Zend_Registry::get('VIEW');
			$this->view->setScriptPath(BASE_PATH.'application/views/scripts/page3');
		}
	    public function indexAction()
	    {
	    	$translation = Zend_Registry::get('TRANSLATIONS');
	    	$titluri = Zend_Registry::get('TITLURI');
			include("include/vars.php");
			if (!isset($_SESSION["rezultat_array"])){
				$catreindex=ezmakeUrl("index.php",end(explode("/",$_SERVER['REQUEST_URI'])));
				header("Location:".$catreindex);
				die();
			}
			$rezultat_array=$_SESSION["rezultat_array"];

        if (!isset($rezultat_array[2])) {
            unset($_SESSION["id_destinatie"]);
            $catreindex = ezmakeUrl("index.php", end(explode("/", $_SERVER['REQUEST_URI'])));
            header("Location:" . $catreindex);
            die();
        }
        
        $arrival_duration = isset($rezultat_array[35]) ? $rezultat_array[35] : '';
        $dep_duration = isset($rezultat_array[38]) ? $rezultat_array[38] : '';
        /* $pickup_time="<select name=\"pickup_time\" id=\"pickup_time\">";
for ($i=0;$i<1440;$i+=15){
	$etime = strtotime("+$i minutes", $rezultat_array[7]);
	$selected_pickup_time="";
	if (isset($_SESSION["rezultat_array"][23])) {
		if (date('d-m-Y H:i:s', $etime)==$_SESSION["rezultat_array"][23]) $selected_pickup_time="selected";
	}
	else if ($i==0) $selected_pickup_time="selected";
	$pickup_time.="<option ".$selected_pickup_time." value=\"".date('d-m-Y H:i:s', $etime)."\">".date('d-M-Y H:i', $etime)."</option>";
}
$pickup_time.="</select>";

$pickup_time2="<select name=\"pickup_time2\" id=\"pickup_time2\">";
for ($i=0;$i<1440;$i+=15){
	$etime = strtotime("+$i minutes", $rezultat_array[9]);
	$selected_pickup_time2="";
	if (isset($_SESSION["rezultat_array"][29])) {
		if (date('d-m-Y H:i:s', $etime)==$_SESSION["rezultat_array"][29]) $selected_pickup_time2="selected";
	}
	else if ($i==0) $selected_pickup_time2="selected";
	$pickup_time2.="<option ".$selected_pickup_time2." value=\"".date('d-m-Y H:i:s', $etime)."\">".date('d-M-Y H:i', $etime)."</option>";
}
$pickup_time2.="</select>";*/

    //This is a PHP(4/5) script example on how eurofxref-daily.xml can be parsed
    //Read eurofxref-daily.xml file in memory 
    //For this command you will need the config option allow_url_fopen=On (default)

    //print_r($_SESSION["rezultat_array"]);

    $this->view->assign("arrival_auto",$rezultat_array[2]);
    $this->view->assign("arrival_duration",$rezultat_array[35]);	
     if ($identificator2 == 'uk') {
        $this->view->assign("currency", '&pound;');
        $this->view->assign("paypalCurrency", 'GBP');
        $final_price_lei = number_format($rezultat_array[5] * 1.05, 2, '.', '');
//        $jsonurl = "http://api.fixer.io/latest?base=GBP&symbols=EUR";
//        $json = file_get_contents($jsonurl);
//        $gbp_to_eur = json_decode($json);
//                                  $final_price_lei = number_format($gbp_to_eur->rates->EUR * $rezultat_array[5] * 1.05, 2, '.', '');
    } else {
        $this->view->assign("currency", '&euro;');
        $final_price_lei = number_format($rezultat_array[5] * 1.05, 2, '.', '');
        $this->view->assign("paypalCurrency", 'EUR');
    }
	$this->view->assign("tara",$identificator2);
	$this->view->assign("url_aeroport",$identificator21);
	$this->view->assign("url_destinatie",$identificator22);
	
	$this->view->assign("arrival_airport",$rezultat_array[0]);
	$this->view->assign("destination",$rezultat_array[1]);
	$this->view->assign("arrival_time",$rezultat_array[6]);
	if (!isset($_SESSION["arrival_passengers"])) {
		header("Location:http://www.christiantransfers.eu");
		die();
	}
	$this->view->assign("arrival_passengers",$_SESSION["arrival_passengers"]);
	$this->view->assign("pickup_auto_name",$rezultat_array[12]);
	$this->view->assign("pickup_auto_seats",$rezultat_array[31]);
	
	$this->view->assign("first_name",(isset($_SESSION["rezultat_array"][14]))?stripslashes($_SESSION["rezultat_array"][14]):"");
	$this->view->assign("last_name",(isset($_SESSION["rezultat_array"][15]))?stripslashes($_SESSION["rezultat_array"][15]):"");
	if (isset($_SESSION["rezultat_array"][16])) {if ($_SESSION["rezultat_array"][16]==0){
			$this->view->assign("gender_checked0","checked");
			$this->view->assign("gender_checked1","");
		} else {
			$this->view->assign("gender_checked1","checked");
			$this->view->assign("gender_checked0","");
		}
	}else {
        $this->view->assign("gender_checked0","checked");
		$this->view->assign("gender_checked1","");
    }
	
	$this->view->assign("gender",(isset($_SESSION["rezultat_array"][16]))?$_SESSION["rezultat_array"][16]:"");
	$this->view->assign("telephone",(isset($_SESSION["rezultat_array"][17]))?$_SESSION["rezultat_array"][17]:"");
	$this->view->assign("email",(isset($_SESSION["rezultat_array"][18]))?$_SESSION["rezultat_array"][18]:"");

        $this->view->assign("flight_departure_from",(isset($_SESSION["rezultat_array"][20]))?stripslashes($_SESSION["rezultat_array"][20]):((isset($_SESSION["rezultat_array"][33]))?stripslashes($_SESSION["rezultat_array"][33]):""));
	$this->view->assign("flight_number",(isset($_SESSION["rezultat_array"][21]))?$_SESSION["rezultat_array"][21]:"");
	$this->view->assign("address",(isset($_SESSION["rezultat_array"][24]))?stripslashes($_SESSION["rezultat_array"][24]):((isset($_SESSION["rezultat_array"][34]))?stripslashes($_SESSION["rezultat_array"][34]):""));
	$this->view->assign("pickup_auto",(isset($_SESSION["rezultat_array"][10]))?$_SESSION["rezultat_array"][10]:"");
	
	if (isset($_SESSION["rezultat_array"]["extra1"]))
	$this->view->assign("extra1",extraOption(50,$_SESSION["rezultat_array"]["extra1"]));
	else { $this->view->assign("select_extra1","selected");
		   $this->view->assign("extra1",extraOption(50));
	}
	
	if (isset($_SESSION["rezultat_array"]["extra2"]))
	$this->view->assign("extra2",extraOption(50,$_SESSION["rezultat_array"]["extra2"]));
	else { $this->view->assign("select_extra2","selected");
		   $this->view->assign("extra2",extraOption(50));
	}
	
	if (isset($_SESSION["rezultat_array"]["extra3"]))
	$this->view->assign("extra3",extraOption(10,$_SESSION["rezultat_array"]["extra3"]));
	else { $this->view->assign("select_extra3","selected");
		   $this->view->assign("extra3",extraOption(10));
	}
	
	if (isset($_SESSION["rezultat_array"]["extra4"]))
	$this->view->assign("extra4",extraOption(10,$_SESSION["rezultat_array"]["extra4"]));
	else { $this->view->assign("select_extra4","selected");
		   $this->view->assign("extra4",extraOption(10));
	}
	
	if (isset($_SESSION["rezultat_array"]["extra5"]))
	$this->view->assign("extra5",extraOption(30,$_SESSION["rezultat_array"]["extra5"]));
	else { $this->view->assign("select_extra5","selected");
		   $this->view->assign("extra5",extraOption(30));
	}
	
	if (isset($_SESSION["rezultat_array"]["extra6"]))
	$this->view->assign("extra6",extraOption(10,$_SESSION["rezultat_array"]["extra6"]));
	else { $this->view->assign("select_extra6","selected");
		   $this->view->assign("extra6",extraOption(10));
	}
	
	if (isset($_SESSION["rezultat_array"]["extra7"]))
	$this->view->assign("extra7",extraOption(10,$_SESSION["rezultat_array"]["extra7"]));
		else { $this->view->assign("select_extra7","selected");
		   $this->view->assign("extra7",extraOption(10));
	}
	
	if (isset($_SESSION["rezultat_array"]["extra8"]))
	$this->view->assign("extra8",extraOption(5,$_SESSION["rezultat_array"]["extra8"]));
		else { $this->view->assign("select_extra8","selected");
		   $this->view->assign("extra8",extraOption(5));
	}
	
if ($rezultat_array[3]==0){

	$this->view->assign("departure_time",(isset($_SESSION["rezultat_array"][26]))?$_SESSION["rezultat_array"][26]:"");
	$this->view->assign("flight_departure_to",(isset($_SESSION["rezultat_array"][27]))?stripslashes($_SESSION["rezultat_array"][27]):((isset($_SESSION["rezultat_array"][37]))?stripslashes($_SESSION["rezultat_array"][37]):""));
	$this->view->assign("flight_number2",(isset($_SESSION["rezultat_array"][28]))?$_SESSION["rezultat_array"][28]:"");
	$this->view->assign("address2",(isset($_SESSION["rezultat_array"][30]))?stripslashes($_SESSION["rezultat_array"][30]):((isset($_SESSION["rezultat_array"][36]))?stripslashes($_SESSION["rezultat_array"][36]):""));
	$this->view->assign("pickup_auto2",(isset($_SESSION["rezultat_array"][11]))?$_SESSION["rezultat_array"][11]:"");
			
	$this->view->assign("pickup_auto2_name",$rezultat_array[13]);
	$this->view->assign("pickup_auto2_seats",$rezultat_array[32]);
	$this->view->assign("dep_auto",$rezultat_array[4]);
	$this->view->assign("dep_airport",$rezultat_array['nume_aeroport_return']);
	$this->view->assign("dep_destination",$rezultat_array['nume_destinatie_return']);
	$this->view->assign("dep_time",$rezultat_array[8]);
	$this->view->assign("dep_duration",$rezultat_array[38]);
	$this->view->assign("dep_passengers",$_SESSION["dep_passengers"]);
	
	if (isset($_SESSION["rezultat_array"]["extra21"]))
	$this->view->assign("extra21",extraOption(50,$_SESSION["rezultat_array"]["extra21"]));
	else { $this->view->assign("select_extra21","selected");
		   $this->view->assign("extra21",extraOption(50));
	}	
	if (isset($_SESSION["rezultat_array"]["extra22"]))
	$this->view->assign("extra22",extraOption(50,$_SESSION["rezultat_array"]["extra22"]));
	else { $this->view->assign("select_extra22","selected");
		   $this->view->assign("extra22",extraOption(50));
	}	
	if (isset($_SESSION["rezultat_array"]["extra23"]))
	$this->view->assign("extra23",extraOption(10,$_SESSION["rezultat_array"]["extra23"]));
	else { $this->view->assign("select_extra23","selected");
		   $this->view->assign("extra23",extraOption(10));
	}	
	if (isset($_SESSION["rezultat_array"]["extra24"]))
	$this->view->assign("extra24",extraOption(10,$_SESSION["rezultat_array"]["extra24"]));
	else { $this->view->assign("select_extra24","selected");
		   $this->view->assign("extra24",extraOption(10));
	}	
	if (isset($_SESSION["rezultat_array"]["extra25"]))
	$this->view->assign("extra25",extraOption(30,$_SESSION["rezultat_array"]["extra25"]));
	else { $this->view->assign("select_extra25","selected");
		   $this->view->assign("extra25",extraOption(30));
	}	
	if (isset($_SESSION["rezultat_array"]["extra26"]))
	$this->view->assign("extra26",extraOption(10,$_SESSION["rezultat_array"]["extra26"]));
	else { $this->view->assign("select_extra26","selected");
		   $this->view->assign("extra26",extraOption(10));
	}	
	if (isset($_SESSION["rezultat_array"]["extra27"]))
	$this->view->assign("extra27",extraOption(10,$_SESSION["rezultat_array"]["extra27"]));
	else { $this->view->assign("select_extra27","selected");
		   $this->view->assign("extra27",extraOption(10));
	}	
	if (isset($_SESSION["rezultat_array"]["extra28"]))
	$this->view->assign("extra28",extraOption(5,$_SESSION["rezultat_array"]["extra28"]));
		else { $this->view->assign("select_extra28","selected");
		   $this->view->assign("extra28",extraOption(5));
	}	
}
	$this->view->assign("return_journey",$_SESSION["oneway"]);
	$this->view->assign("final_price_euro",$rezultat_array[5]);
	$this->view->assign("final_price_lei",$final_price_lei);
	$this->view->assign("country",$rezultat_array[99]);
	$this->view->assign("error",(isset($_GET["error"]))?$_GET["error"]:"");
	
	$title="".$nume_aeroport." - ".$nume_destinatie." = personal details for your booking";
    $meta="".$nume_aeroport." - ".$nume_destinatie." = personal details for your booking";
    $this->view->assign("title",$title);
    $this->view->assign("description",$meta);


	
	$_SESSION["final_price_lei"]=$final_price_lei;
	$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);
	if ($id_tara == 34){
        echo $this->view->render('page3_uk.phtml');
    } else {
        echo $this->view->render('page3.phtml');
    }
    }
}
?>