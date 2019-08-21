<?php

/*
 * Created by Valizr on 23-08-2011
 * Reason: Articol page controller
 */
require_once 'Zend/Controller/Action.php';

class PlataController extends Zend_Controller_Action {

    public $view;

    public function init() {
        $this->view = Zend_Registry::get('VIEW');
        $this->view->setScriptPath('application/views/scripts/plata');
    }

    public function indexAction() {
        $translation = Zend_Registry::get('TRANSLATIONS');
        $titluri = Zend_Registry::get('TITLURI');
        include("include/vars.php");
        if (!isset($_SESSION["oneway"])) {
            $catreindex = ezmakeUrl("airport_transfer", end(explode("/", $_SERVER['REQUEST_URI'])));
            unset($_SESSION["id_destinatie"]);
            header("Location:" . $catreindex);
            die();
        }
        $uk='';
        if (!isset($id_tara)) {
            $catreindex = ezmakeUrl("index.php", end(explode("/", $_SERVER['REQUEST_URI'])));
            header("Location:" . $catreindex);
            die();
        }
        if ($id_tara == 44) {
            $currency = 'GBP';
            $this->view->assign('currency', "&pound;");
            $this->view->assign('paypalCurrency', "GBP");
            $this->view->assign('worldpay_currency', "GBP");
            $this->view->assign('worldpay_id', "1163261");
            //$uk='_UK';
        } else {
            $currency = 'EUR';
            $this->view->assign('currency', "&euro;");
            $this->view->assign('paypalCurrency', "EUR");
            $this->view->assign('worldpay_currency', "EUR");
            $this->view->assign('worldpay_id', "1147626");
        }
        $_SESSION["pickup_tara"] = $id_tara;
        $q->query("select id_aeroport, nume_aeroport from AEROPORT$uk where url_aeroport='" . $identificator21 . "'");
        $q->next_record();
        $id_aeroport = $q->f("id_aeroport");
        $nume_aeroport = $q->f("nume_aeroport");

        $_SESSION["pickup_locatie"] = $id_aeroport;

        $q->query("select id_destinatie,nume_destinatie from DESTINATIE$uk where url_destinatie='" . $identificator22 . "'");
        $q->next_record();
        $id_destinatie = $q->f("id_destinatie");
        $nume_destinatie = $q->f("nume_destinatie");
        $_SESSION["id_destinatie"] = $id_destinatie;

		if (isset($_SESSION["rezultat_array"][18]) && $_SESSION["rezultat_array"][18]!='') {//mailul
		
        $q->query("select id_order from ORDERS order by id_order desc limit 0,1");
        if ($q->next_record())
            $id_order = $q->f("id_order") + 1;
        else
            $id_order = 200000;

			$pickup_extra = isset($_SESSION["rezultat_array"]["extra1"])?$_SESSION["rezultat_array"]["extra1"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra2"])?$_SESSION["rezultat_array"]["extra2"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra3"])?$_SESSION["rezultat_array"]["extra3"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra4"])?$_SESSION["rezultat_array"]["extra4"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra5"])?$_SESSION["rezultat_array"]["extra5"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra6"])?$_SESSION["rezultat_array"]["extra6"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra7"])?$_SESSION["rezultat_array"]["extra7"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra8"])?$_SESSION["rezultat_array"]["extra8"] : '0';
			if ($_SESSION["oneway"] == 0) {
				$departure_fields = "flight_departure,flight_departure_to,flight_number2,passengers2,pickup_time2,pickup_location2,pickup_auto2,pickup_extra2,";

				$pickup_extra2 = isset($_SESSION["rezultat_array"]["extra21"])?$_SESSION["rezultat_array"]["extra21"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra22"])?$_SESSION["rezultat_array"]["extra22"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra23"])?$_SESSION["rezultat_array"]["extra23"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra24"])?$_SESSION["rezultat_array"]["extra24"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra25"])?$_SESSION["rezultat_array"]["extra25"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra26"])?$_SESSION["rezultat_array"]["extra26"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra27"])?$_SESSION["rezultat_array"]["extra27"] : '0' . "|"
				. isset($_SESSION["rezultat_array"]["extra28"])?$_SESSION["rezultat_array"]["extra28"] : '0';

            $departure_values = "'" . $_SESSION["rezultat_array"][26] . "','" . $_SESSION["rezultat_array"][27] . "','" . $_SESSION["rezultat_array"][28] . "','" . $_SESSION["dep_passengers"] . "','" . $_SESSION["rezultat_array"][29] . "','" . $_SESSION["rezultat_array"][30] . "','" . $_SESSION["rezultat_array"][13] . "','$pickup_extra2',";
            $return_transfer = '<tr><td>Date return</td><td><input size="50" readonly type="text" name="" value="' . $_SESSION["rezultat_array"][26] . '" /></td></tr>';
            $return_transfer2 = ' / ' . $nume_aeroport_return.' - '.$nume_destinatie_return;
        } else {
            $departure_fields = "";
            $departure_values = "";
            $pickup_extra2 = "";
            $return_transfer = '';
            $return_transfer2 = '';
        }
        
            $query = "insert into ORDERS (id_order,prenume,numedefamilie,sex,telefon,email,arrival,flight_arrival,flight_departure_from,flight_number,passengers,pickup_time,pickup_location,pickup_auto,pickup_extra,destination,$departure_fields price,price_currency) values 
			('$id_order','" . $_SESSION["rezultat_array"][14] . "','" . $_SESSION["rezultat_array"][15] . "','" . $_SESSION["rezultat_array"][16] . "','" . $_SESSION["rezultat_array"][17] . "','" . $_SESSION["rezultat_array"][18] . "','$nume_aeroport','" . $_SESSION["rezultat_array"][6] . "','" . $_SESSION["rezultat_array"][20] . "','" . $_SESSION["rezultat_array"][21] . "','" . $_SESSION["arrival_passengers"] . "','" . $_SESSION["rezultat_array"][23] . "','" . $_SESSION["rezultat_array"][24] . "','" . $_SESSION["rezultat_array"][12] . "','$pickup_extra','$nume_destinatie',$departure_values '" . $_SESSION["final_price_lei"] . "','" . ($id_tara == 44 ? '2' : '1') . "')";
            $q->query($query);

            $message = '<style>
td {
border-bottom:1px solid #ababab;
}
</style>
<table align="center" class="campuri" border="0" width="590" cellspacing="5" cellpadding="3">
<tr bgcolor="#FF8181" height="21"><td style="border:0;padding-top:10px;" colspan="2" align="right" valign="top"><a href="http://www.christiantransfers.eu" TARGET="_BLANK"><font face="Arial" size="2" color="#1D1C1C"><b>Christian Transfers</b> &nbsp;&nbsp;</font></a></td></tr>
<tr><td style="border:0;padding-top:10px;" colspan="2"><p>Dear <b>' . $_SESSION["rezultat_array"][14] . '</b><br>Thank you for choosing our website <b>ChristianTransfers.eu</b></p>
<p>This is <b>Not</b> a final confirmation, this is the email with your booking and we will process it as soon as possible.</p>
<p>Please carefully check if the dates and hours are correct and confirm in a reply to this email (dont change the title or body email).</p>
<p>If you did not completed the online payment, we will <b>Cannot</b> send the order to our local subcontractors. It will have to be deleted from our systems.</p></td></tr>

<tr><td style="border:0;padding-top:10px;" colspan="2"><b>Order Details ' . $id_order . '</b></td></tr>

<tr><td align="left" width="200">Name</td><td align="left"><b>' . $_SESSION["rezultat_array"][14] . '</b></td></tr>
<tr><td align="left">Company</td><td align="left"><b>' . $_SESSION["rezultat_array"][15] . '</b></td></tr>
<tr><td align="left">Phone</td><td align="left"><b>' . $_SESSION["rezultat_array"][17] . '</b></td></tr>
<tr><td align="left">Email</td><td align="left"><b>' . $_SESSION["rezultat_array"][18] . '</b></td></tr>

<tr><td style="border:0;padding-top:10px;" colspan="2"><b>First transfer</b></td></tr>

<tr><td align="left">Pickup</td><td align="left"><b>' . $nume_aeroport . '</b></td></tr>
<tr><td align="left">Pickup address/Flight from</td><td align="left"><b>' . $_SESSION["rezultat_array"][20] . '</b></td></tr>
<tr><td align="left">Flight number/Company</td><td align="left"><b>' . $_SESSION["rezultat_array"][21] . '</b></td></tr>
<tr><td align="left">Pickup time</td><td align="left"><b>' . $_SESSION["rezultat_array"][6] . '</b></td></tr>
<tr><td align="left">Travel time</td><td align="left"><b> Approximate ' . $_SESSION["rezultat_array"][35] . ' Minutes</b></td></tr>
<tr><td align="left">Destination</td><td align="left"><b>' . $nume_destinatie . '</b></td></tr>
<tr><td align="left">Destination adress</td><td align="left"><b>' . $_SESSION["rezultat_array"][24] . '</b></td></tr>
<tr><td align="left">Number of passengers</td><td align="left"><b>' . $_SESSION["arrival_passengers"] . '</b></td></tr>
<tr><td align="left">Car type</td><td align="left"><b>' . $_SESSION["rezultat_array"][12] . '</b></td></tr>

<tr><td style="border:0;padding-top:10px;" colspan="2"><b>Extra info First transfer</b></td></tr>

<tr><td align="left">Big bags (13-25 kg)</td><td align="left">' . $_SESSION["rezultat_array"]["extra1"] . '</td></tr>
<tr><td align="left">Normal bags (2-12 kg)</td><td align="left">' . $_SESSION["rezultat_array"]["extra2"] . '</td></tr>
<tr><td align="left">Bicycle</td><td align="left">' . $_SESSION["rezultat_array"]["extra3"] . '</td></tr>
<tr><td align="left">Ski equipment</td><td align="left">' . $_SESSION["rezultat_array"]["extra5"] . '</td></tr>
<tr><td align="left">Pets</td><td align="left">' . $_SESSION["rezultat_array"]["extra8"] . '</td></tr>';

            if ($_SESSION["oneway"] == 0) {
                $message.='<tr><td style="border:0;padding-top:10px;" colspan="2"><b>Return transfer</b></td></tr>

<tr><td align="left">Pickup</td><td align="left"><b>' . $nume_aeroport_return . '</b></td></tr>
<tr><td align="left">Pickup adress/Flight from</td><td align="left"><b>' . $_SESSION["rezultat_array"][30] . '</b></td></tr>
<tr><td align="left">Flight number/Company</td><td align="left"><b>' . $_SESSION["rezultat_array"][28] . '</b></td></tr>
<tr><td align="left">Pickup time</td><td align="left"><b>' . $_SESSION["rezultat_array"][26] . '</b></td></tr>
<tr><td align="left">Travel time</td><td align="left"><b> Approximate ' . $_SESSION["rezultat_array"][38] . ' Minutes</b></td></tr>
<tr><td align="left">Destination</td><td align="left"><b>' . $nume_destinatie_return . '</b></td></tr>
<tr><td align="left">Destination adress</td><td align="left"><b>' . $_SESSION["rezultat_array"][27] . '</b></td></tr>
<tr><td align="left">Number of passengers</td><td align="left"><b>' . $_SESSION["dep_passengers"] . '</b></td></tr>
<tr><td align="left">Car type</td><td align="left"><b>' . $_SESSION["rezultat_array"][13] . '</b></td></tr>

<tr><td style="border:0;padding-top:10px;" colspan="2"><b>Extra info Return transfer</b></td></tr>

<tr><td align="left">Big bags (13-25 kg)</td><td align="left">' . $_SESSION["rezultat_array"]["extra21"] . '</td></tr>
<tr><td align="left">Normal bags (2-12 kg)</td><td align="left">' . $_SESSION["rezultat_array"]["extra22"] . '</td></tr>
<tr><td align="left">Bicycle</td><td align="left">' . $_SESSION["rezultat_array"]["extra23"] . '</td></tr>
<tr><td align="left">Ski equipment</td><td align="left">' . $_SESSION["rezultat_array"]["extra25"] . '</td></tr>
<tr><td align="left">Pets</td><td align="left">' . $_SESSION["rezultat_array"]["extra28"] . '</td></tr>';
            }
            $message.='<tr><td style="border:0;padding-top:10px;" colspan="2"></td></tr>
<tr bgcolor="#FF8181"><td align="left"><b>Total Price</b></td><td align="left"><b>' . $_SESSION["final_price_lei"] . ' '.$currency.'</b></td></tr>
<tr>
<td style="border:0;padding-top:10px;" colspan="2"><font color="#C43D28">
<p><b>Important</b>: to <b>confirm, change or cancel</b> this booking please make a reply to this email with your message, <b>without changing the title or the email body.</b></p></font>
<p>Bookings, payments, confirmations, changes or cancellations have to be made only online; best is at least 48 hours before your travel time.</p>
<p>We process your bookings daily 08:00 - 20:00, our subcontractors have their own programme.</p>
<p>Adress London, UK; Phone: +447463 715859 <a href="http://www.Facebook.com/ChristianTransfers" TARGET="_BLANK">Facebook.com/ChristianTransfers</a></p>
<p><b>Christian Transfers</b> = <b>Christian Transfers LTD</b></p>
</td>
</tr>
</table>';

            mail($_SESSION["rezultat_array"][18], "" . $nume_aeroport . " to " . $nume_destinatie . " = booking No " . $id_order . " - Christian Transfers", $message, "From:Christian Transfers<contact@christiantransfers.eu>\r\nReply-to: contact@christiantransfers.eu\r\nContent-type: text/html; charset=us-ascii");
            mail("contact@christiantransfers.eu", "" . $nume_aeroport . " to " . $nume_destinatie . " = booking No " . $id_order . " - Christian Transfers", $message, "From:<" . $_SESSION["rezultat_array"][18] . ">\r\nContent-type: text/html; charset=us-ascii");

//$this->view->assign('description', ps_clean(ucwords($identificator2).' -  '.$nume_aeroport.' to '.$nume_destinatie));

            $this->view->assign('description', ps_clean($nume_aeroport . ' - ' . $nume_destinatie . $return_transfer2));
            $this->view->assign('name', $_SESSION["rezultat_array"][14]);
            $this->view->assign('company', $_SESSION["rezultat_array"][15]);
            $this->view->assign('phone', $_SESSION["rezultat_array"][17]);
            $this->view->assign('email', $_SESSION["rezultat_array"][18]);
            
            $this->view->assign('date_transfer', $_SESSION["rezultat_array"][6]);
            $this->view->assign('nume_aeroport_transfer', $nume_aeroport);
            $this->view->assign('aeroport_transfer', $_SESSION["rezultat_array"][20]);
            $this->view->assign('flight_number_transfer', $_SESSION["rezultat_array"][21]);
            $this->view->assign('destinatie_transfer', $nume_destinatie);
            $this->view->assign('adresa_destinatie_transfer', $_SESSION["rezultat_array"][24]);
            $this->view->assign('pasageri_transfer', $_SESSION["arrival_passengers"]);
            $this->view->assign('vehicle_transfer', $_SESSION["rezultat_array"][12]);
            $this->view->assign('big_transfer', $_SESSION["rezultat_array"]["extra1"]);
            $this->view->assign('normal_transfer', $_SESSION["rezultat_array"]["extra2"]);
            $this->view->assign('bicycle_transfer', $_SESSION["rezultat_array"]["extra3"]);
            $this->view->assign('ski_transfer', $_SESSION["rezultat_array"]["extra5"]);
            $this->view->assign('pet_transfer', $_SESSION["rezultat_array"]["extra8"]);
            
    if ($_SESSION["oneway"] == 0) {
            $this->view->assign('date_return', $_SESSION["rezultat_array"][26]);
            $this->view->assign('nume_aeroport_return', $nume_aeroport_return);
            $this->view->assign('aeroport_return', $_SESSION["rezultat_array"][30]);
            $this->view->assign('flight_number_return', $_SESSION["rezultat_array"][28]);
            $this->view->assign('destinatie_return', $nume_destinatie_return);
            $this->view->assign('adresa_destinatie_return', $_SESSION["rezultat_array"][27]);
            $this->view->assign('pasageri_return', $_SESSION["dep_passengers"]);
            $this->view->assign('vehicle_return', $_SESSION["rezultat_array"][13]);
            $this->view->assign('big_return', $_SESSION["rezultat_array"]["extra21"]);
            $this->view->assign('normal_return', $_SESSION["rezultat_array"]["extra22"]);
            $this->view->assign('bicycle_return', $_SESSION["rezultat_array"]["extra23"]);
            $this->view->assign('ski_return', $_SESSION["rezultat_array"]["extra25"]);
            $this->view->assign('pet_return', $_SESSION["rezultat_array"]["extra28"]);
    }
            
            $this->view->assign('price', $_SESSION["final_price_lei"]);
            $this->view->assign('oneway', $_SESSION["oneway"]);
            $this->view->assign('id_order', $id_order);
            $this->view->assign('cui', "34116655");
            $this->view->assign('trtype', '0');
            $this->view->assign('return_url', "http://www.christiantransfers.eu/test_response.html");
            $this->view->assign('time', time());

            $title = "Payment for " . $nume_aeroport . " to " . $nume_destinatie;
            $meta = "Payment for " . $nume_aeroport . " to " . $nume_destinatie . "";

            $this->view->assign("title", $title);
            $this->view->assign("latime_tabel_dreapta", $latime_tabel_dreapta);
			
			//if($_SERVER['REMOTE_ADDR'] == '92.84.50.46' || $_SERVER['REMOTE_ADDR'] == '95.151.23.102') {
				
			if ($id_tara == 44) {	
				echo $this->view->render('plata_stripe.phtml');
			} else
				echo $this->view->render('platapaypal.phtml');
        }else {
            header("Location:$sitename/index.php");
            die();
        }
    }

}