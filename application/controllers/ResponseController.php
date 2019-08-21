<?php
/**
 * LIBRAPAY
 *
 * @Libra Bank SA - 2012
 * librapay@librabank.ro
 *
 * handler procesare plati online 3DSecure cu cardul
 *
*/

if(isset($_POST['ep_id'])) {
include(BASE_PATH.'application/controllers/ResponseController_EP.php');

}

else {

require_once 'Zend/Controller/Action.php';

	class ResponseController extends Zend_Controller_Action
	{
		
		public $view;
		
		public function init()
		{
			//get database and cache
			//$this->db = Zend_Registry::get('DB');
			//$this->dbw = Zend_Registry::get('DBW');

			//initialize Smarty templating class
			$this->view = Zend_Registry::get('VIEW');
			$this->view->setScriptPath(BASE_PATH.'application/views/scripts/confirmare');

			//language assignation
			//$this->view->assign("language",$_SESSION['language']);
		}
		public function indexAction() {
			$translation = Zend_Registry::get('TRANSLATIONS');
	    	$titluri = Zend_Registry::get('TITLURI');

			include("include/vars.php");
		
			$title=$titluri["aboutusTitle"];
			$meta=$titluri["aboutusMeta"];
		
			$this->view->assign("title",$title);
			$this->view->assign("description",$meta);
			
			//$data = $_GET;
			
			$librapay->trtype 		= $_GET["TRTYPE"];
			$librapay->desc 		= $_GET["DESC"];
			$librapay->action 		= $_GET["ACTION"];
			$librapay->amount 		= $_GET["AMOUNT"];
			$librapay->currency		= $_GET["CURRENCY"];
			$librapay->rc 			= $_GET["RC"];
			$librapay->message 		= $_GET["MESSAGE"];
			$librapay->rrn 		= $_GET["RRN"];
			$librapay->int_ref 		= $_GET["INT_REF"];
			$librapay->approval 	= $_GET["APPROVAL"]; 
			$librapay->psign    	= $_GET["P_SIGN"]; 
			
			$lperrors = array (00 => 'AUTHORIZED',
							  01 => 'REFER TO CARD ISSUER',
							  02 => 'REFERRAL-SPECIAL CONDITIONS',
							  03 => 'INVALID MERCHANT',
							  04 => 'PICK UP CARD',
							  05 => 'DO NOT HONOR',
							  06 => 'ERROR - RETRY',
							  07 => 'PICK UP !! FRAUD !!',
							  08 => 'HONOR WITH C/H IDENT',
							  09 => 'REQUEST IN PROGRESS',
							  10 => 'PARTIALLY APPROVED',
							  11 => 'VIP APPROVAL',
							  12 => 'INVALID TRANSACTION',
							  13 => 'INVALID AMOUNT',
							  14 => 'INVALID ACCOUNT NBR',
							  15 => 'NO SUCH ISSUER UNABLE TO ROUTE AT IEM',
							  16 => 'APPROVED',
							  19 => 'RE-ENTER REQUEST',
							  21 => 'NO ACTION TAKEN',
							  22 => 'SUSPECT MALFUNCTION',
							  30 => 'FORMAT ERROR',
							  31 => 'ISSUER SIGNED-OFF',
							  32 => 'PARTIALLY COMPLETTED',
							  33 => 'EXPIRED CARD',
							  36 => 'PICK UP-RESTRICTED',
							  37 => 'PICK UP-CALL ACQBANK',
							  38 => 'PICK UP-EXC PIN RETRY',
							  39 => 'NO CREDIT ACCOUNT',
							  41 => 'PICK UP- LOST CARD',
							  43 => 'PICK UP- STOLEN CARD',
							  51 => 'INSUFFICIENT FUNDS',
							  52 => 'NO CHECKING ACCOUNT',
							  53 => 'NO SAVINGS ACCOUNT',
							  54 => 'EXPIRED CARD',
							  55 => 'INCORRECT PIN',
							  57 => 'NOT PERMITTED TO C/H',
							  58 => 'NOT PERMITTED TO POS',
							  61 => 'EXCEEDS AMOUNT LIMIT',
							  62 => 'RESTRICTED CARD',
							  63 => 'SECURITY VIOLATION',
							  64 => 'ORIG.AMT INCORRECT',
							  65 => 'ACTIV.COUNT EXCEEDED',
							  75 => 'PIN RETRIES EXCEEDED',
							  76 => 'DIFFERENT FROM ORIG.  WRONG PIN',
							  79 => 'ALREADY REVERSED',
							  80 => 'INVALID DATE NETWORK ERROR',
							  81 => 'CRYPTOGTAPHIC ERROR FOREIGN NETWORK ERR',
							  82 => 'INCORRECT CVV TIMED-OUT AT IEM',
							  83 => 'UNABLE TO VERIFY PIN TRANSACTION FAILED',
							  84 => 'PRE-AUTH. TIMED OUT',
							  85 => 'ACCOUNT VERIFICATION',
							  86 => 'UNABLE TO VERIFY PIN',
							  88 => 'CRYPTOGTAPHIC ERROR',
							  91 => 'ISSUER UNAVAILABLE',
							  92 => 'ROUTER UNAVAILABLE',
							  93 => 'CANNOT COMPLETE TXN',
							  94 => 'DUPLICATE TXN',
							  95 => 'RECONCILE ERROR',
							  96 => 'SYSTEM MALFUNCTION',
							  99 => 'ABORTED',
							  -6 => 'BAD CGI REQUEST (CAMPUL ORDER INCORECT)',
							  -17 => 'ACCESS DENIED',
							  -19 => 'AUTHENTICATION FAILED',
							  -20 => 'SYSTEM ERROR (TIMESTAMP INCORECT)',
							  119 => 'INVALID CURRENCY',
							  120 => 'INVALID P_SIGN',
							  121 => 'INCORRECT BACKREF',
							  122 => 'INVALID/INACTIV TERMINAL',
							  123 => 'INVALID ORDER',
							  124 => 'EXPIRED TRANZACTION',
							  125 => 'INVALID RRN',
							  126 => 'INCORRECT INT_REF',
							  127 => 'ORIGINAL TRANZACTION WAS NOT APPROVED',
							  128 => 'ALREADY REVERSED',
							  129 => 'CAPTURE PERIOD EXPIRED',
							  130 => 'PARTIAL REFUND NOT PERMITED FOR TERMINAL',
							  131 => 'PARTIAL REFUND FAILED - NOT CAPTURED');
			
				
				//var_dump($data);
				$eroareLP = (isset($_GET["RC"]) && isset($lperrors[$_GET["RC"]])) ? $lperrors[$_GET["RC"]] : '';
				$this->view->assign('actionlp', $_GET["ACTION"]);
				switch($_GET["ACTION"]) {
					case "0":
					$mesaj = 'Your payment of '.$_GET["AMOUNT"].' '.$_GET["CURRENCY"].' was approved<br /><br />We thank you for your choice to be our customer - Christian Transfers';
					break;
					case "1":
					$mesaj = 'Your payment of '.$_GET["AMOUNT"].' '.$_GET["CURRENCY"].' is duplicated';
					break;
					case "2":
					$mesaj = 'Your payment of '.$_GET["AMOUNT"].' '.$_GET["CURRENCY"].' was rejected: '.$eroareLP.'<br />';
					break;
					case "3":
					$mesaj = 'Your payment of '.$_GET["AMOUNT"].' '.$_GET["CURRENCY"].' could not be approved. An error occured: '.$lperrors[$_GET["RC"]].'<br />';
					break;
					default:
					$mesaj = 'Your payment of '.$_GET["AMOUNT"].' '.$_GET["CURRENCY"].' could not be approved. An error occured: '.$lperrors[$_GET["RC"]].'<br />';
					break;
				}
				if($_GET["ACTION"]=='3' || $_GET["ACTION"]=='2') {
					$q->query("select id_tara from TARA where nume_tara='".ucfirst($identificator2)."'");
					$q->next_record();
					$id_tara=$q->f("id_tara");
		
					$_SESSION["pickup_tara"]=$id_tara;
					$q->query("select id_aeroport, nume_aeroport from AEROPORT where url_aeroport='".$identificator21."'");
					$q->next_record();
					$id_aeroport=$q->f("id_aeroport");
					$nume_aeroport=$q->f("nume_aeroport");
					$_SESSION["pickup_locatie"]=$id_aeroport;
		
					$q->query("select id_destinatie,nume_destinatie from DESTINATIE where url_destinatie='".$identificator22."'");
					$q->next_record();
					$id_destinatie=$q->f("id_destinatie");
					$nume_destinatie=$q->f("nume_destinatie");
					$_SESSION["id_destinatie"]=$id_destinatie;
					
					
					$q->query("select arrival, destination from ORDERS where id_order='".$_GET["ORDER"]."'");
					$q->next_record();
					$nume_aeroport   = $q->f("arrival");
					$nume_destinatie = $q->f("destination");
					
			
					$q->query("select id_order from ORDERS order by id_order desc limit 0,1");
					if ($q->next_record()) $id_order=$q->f("id_order")+1;
					else $id_order=100000;
					
					$pickup_extra=$_SESSION["rezultat_array"]["extra1"]."|".$_SESSION["rezultat_array"]["extra2"]."|".$_SESSION["rezultat_array"]["extra3"]."|".$_SESSION["rezultat_array"]["extra4"]."|".$_SESSION["rezultat_array"]["extra5"]."|".$_SESSION["rezultat_array"]["extra6"]."|".$_SESSION["rezultat_array"]["extra7"]."|".$_SESSION["rezultat_array"]["extra8"];
					if ($_SESSION["oneway"]==0){
							$departure_fields="flight_departure,flight_departure_to,flight_number2,passengers2,pickup_time2,pickup_location2,pickup_auto2,pickup_extra2,";
					$pickup_extra2=$_SESSION["rezultat_array"]["extra21"]."|".$_SESSION["rezultat_array"]["extra22"]."|".$_SESSION["rezultat_array"]["extra23"]."|".$_SESSION["rezultat_array"]["extra24"]."|".$_SESSION["rezultat_array"]["extra25"]."|".$_SESSION["rezultat_array"]["extra26"]."|".$_SESSION["rezultat_array"]["extra27"]."|".$_SESSION["rezultat_array"]["extra28"];
					$departure_values="'".$_SESSION["rezultat_array"][26]."','".$_SESSION["rezultat_array"][27]."','".$_SESSION["rezultat_array"][28]."','".$_SESSION["dep_passengers"]."','".$_SESSION["rezultat_array"][29]."','".$_SESSION["rezultat_array"][30]."','".$_SESSION["rezultat_array"][13]."','$pickup_extra2',";
					}
					else 
					{
						$departure_fields="";
						$departure_values="";
						$pickup_extra2="";	
					}
					
					$query="insert into ORDERS (id_order,prenume,numedefamilie,sex,telefon,email,arrival,flight_arrival,flight_departure_from,flight_number,passengers,pickup_time,pickup_location,pickup_auto,pickup_extra,destination,$departure_fields price) values ('$id_order','".$_SESSION["rezultat_array"][14]."','".$_SESSION["rezultat_array"][15]."','".$_SESSION["rezultat_array"][16]."','".$_SESSION["rezultat_array"][17]."','".$_SESSION["rezultat_array"][18]."','$nume_aeroport','".$_SESSION["rezultat_array"][6]."','".$_SESSION["rezultat_array"][20]."','".$_SESSION["rezultat_array"][21]."','".$_SESSION["arrival_passengers"]."','".$_SESSION["rezultat_array"][23]."','".$_SESSION["rezultat_array"][24]."','".$_SESSION["rezultat_array"][12]."','$pickup_extra','$nume_destinatie',$departure_values '".$_SESSION["final_price_lei"]."')";
					$q->query($query);
					
$message='<style>
td {
border-bottom:1px solid #ababab;
}
</style>
<table align="center" class="campuri" border="0" width="590" cellspacing="5" cellpadding="3">
<tr bgcolor="#FF8181" height="21"><td style="border:0;padding-top:10px;" colspan="2" align="right" valign="top"><a href="http://www.christiantransfers.eu" TARGET="_BLANK"><font face="Arial" size="2" color="#1D1C1C"><b>Christian Transfers</b> &nbsp;&nbsp;</font></a></td></tr>
<tr><td style="border:0;padding-top:10px;" colspan="2"><p>Dear <b>'.$_SESSION["rezultat_array"][14].'</b><br>Thank you for choosing our website <b>ChristianTransfers.eu</b></p><p>This is the email with your <b>Booking</b> and is not a final confirmation.</p><p> Please carefully check if the details from the order below are correct and <b>confirm with a short reply to this email</b>. Failure to confirm in a reply to this email any correction leads to processing order like the original.</p><p>If you have made the online payment you just have to wait for the confirmation that your order can be accomplished. If you have not completed the online payment we will <b>Cannot</b> send the order to our local subcontractors and it will have to deleted it from our systems.</p></td></tr>

<tr><td style="border:0;padding-top:10px;" colspan="2"><b>Order Details '.$id_order.'</b></td></tr>

<tr><td align="left" width="200">Name</td><td align="left"><b>'.$_SESSION["rezultat_array"][14].'</b></td></tr>
<tr><td align="left">Company</td><td align="left"><b>'.$_SESSION["rezultat_array"][15].'</b></td></tr>
<tr><td align="left">Phone</td><td align="left"><b>'.$_SESSION["rezultat_array"][17].'</b></td></tr>
<tr><td align="left">Email</td><td align="left"><b>'.$_SESSION["rezultat_array"][18].'</b></td></tr>

<tr><td style="border:0;padding-top:10px;" colspan="2"><b>First transfer</b></td></tr>

<tr><td align="left">Pickup</td><td align="left"><b>'.$nume_aeroport.'</b></td></tr>
<tr><td align="left">Pickup address/Flight from</td><td align="left"><b>'.$_SESSION["rezultat_array"][20].'</b></td></tr>
<tr><td align="left">Flight number/Company</td><td align="left"><b>'.$_SESSION["rezultat_array"][21].'</b></td></tr>
<tr><td align="left">Pickup time</td><td align="left"><b>'.$_SESSION["rezultat_array"][6].'</b></td></tr>
<tr><td align="left">Travel time</td><td align="left"><b> Approximate '.$_SESSION["rezultat_array"][35].' Minutes</b></td></tr>
<tr><td align="left">Destination</td><td align="left"><b>'.$nume_destinatie.'</b></td></tr>
<tr><td align="left">Destination adress</td><td align="left"><b>'.$_SESSION["rezultat_array"][24].'</b></td></tr>
<tr><td align="left">Number of passengers</td><td align="left"><b>'.$_SESSION["arrival_passengers"].'</b></td></tr>
<tr><td align="left">Car type</td><td align="left"><b>'.$_SESSION["rezultat_array"][12].'</b></td></tr>

<tr><td style="border:0;padding-top:10px;" colspan="2"><b>Extra info First transfer</b></td></tr>

<tr><td align="left">Big bags (13-25 kg)</td><td align="left">'.$_SESSION["rezultat_array"]["extra1"].'</td></tr>
<tr><td align="left">Normal bags (2-12 kg)</td><td align="left">'.$_SESSION["rezultat_array"]["extra2"].'</td></tr>
<tr><td align="left">Bicycle</td><td align="left">'.$_SESSION["rezultat_array"]["extra3"].'</td></tr>
<tr><td align="left">Sky equipment</td><td align="left">'.$_SESSION["rezultat_array"]["extra5"].'</td></tr>
<tr><td align="left">Pets</td><td align="left">'.$_SESSION["rezultat_array"]["extra8"].'</td></tr>';

if ($_SESSION["oneway"]==0){
	$message.='<tr><td style="border:0;padding-top:10px;" colspan="2"><b>Return transfer</b></td></tr>

<tr><td align="left">Pickup</td><td align="left"><b>'.$nume_destinatie.'</b></td></tr>
<tr><td align="left">Pickup adress/Flight from</td><td align="left"><b>'.$_SESSION["rezultat_array"][30].'</b></td></tr>
<tr><td align="left">Flight number/Company</td><td align="left"><b>'.$_SESSION["rezultat_array"][28].'</b></td></tr>
<tr><td align="left">Pickup time</td><td align="left"><b>'.$_SESSION["rezultat_array"][26].'</b></td></tr>
<tr><td align="left">Travel time</td><td align="left"><b> Approximate '.$_SESSION["rezultat_array"][38].' Minutes</b></td></tr>
<tr><td align="left">Destination</td><td align="left"><b>'.$nume_aeroport.'</b></td></tr>
<tr><td align="left">Destination adress</td><td align="left"><b>'.$_SESSION["rezultat_array"][27].'</b></td></tr>
<tr><td align="left">Number of passengers</td><td align="left"><b>'.$_SESSION["dep_passengers"].'</b></td></tr>
<tr><td align="left">Car type</td><td align="left"><b>'.$_SESSION["rezultat_array"][13].'</b></td></tr>

<tr><td style="border:0;padding-top:10px;" colspan="2"><b>Extra info Return transfer</b></td></tr>

<tr><td align="left">Big bags (13-25 kg)</td><td align="left">'.$_SESSION["rezultat_array"]["extra21"].'</td></tr>
<tr><td align="left">Normal bags (2-12 kg)</td><td align="left">'.$_SESSION["rezultat_array"]["extra22"].'</td></tr>
<tr><td align="left">Bicycle</td><td align="left">'.$_SESSION["rezultat_array"]["extra23"].'</td></tr>
<tr><td align="left">Sky equipment</td><td align="left">'.$_SESSION["rezultat_array"]["extra25"].'</td></tr>
<tr><td align="left">Pets</td><td align="left">'.$_SESSION["rezultat_array"]["extra28"].'</td></tr>';
}
$message.='<tr><td style="border:0;padding-top:10px;" colspan="2"></td></tr>
<tr bgcolor="#FF8181"><td align="left"><b>Total Price</b></td><td align="left"><b>'.$_SESSION["final_price_lei"].' Euro</b></td></tr>
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
					
mail($_SESSION["rezultat_array"][18],"".$nume_aeroport." to ".$nume_destinatie." = booking No ".$id_order." - Christian Transfers",$message,"From:Christian Transfers<contact@christiantransfers.eu>\r\nReply-to: contact@christiantransfers.eu\r\nContent-type: text/html; charset=us-ascii");
mail("contact@christiantransfers.eu","".$nume_aeroport." to ".$nume_destinatie." = booking No ".$id_order." - Christian Transfers",$message,"From:<".$_SESSION["rezultat_array"][18].">\r\nContent-type: text/html; charset=us-ascii");
					
					$key = '1C6D1A1E5B3F8F8A8B3CA00000000074';
					$data = array( 'cui' => '29157390',
								   'AMOUNT' =>	$librapay->amount,
								   'CURRENCY' => $librapay->currency,
								   'ORDER' =>	$id_order,
								   'DESC' =>	$librapay->desc,	 
								   'PENTRU' => $_SESSION["rezultat_array"][18],      
								   'TRTYPE' =>	$librapay->trtype,
								   'BACKREF' => 'http://www.christiantransfers.eu/test_response.html',	
								   'TIMESTAMP'=> time()  
								   );
					
					
					
					$this->view->assign('descrip', $librapay->desc);
					$this->view->assign('price', $librapay->amount);
					$this->view->assign('currency', $librapay->currency);
					$this->view->assign('id_order',$id_order);
					$this->view->assign('cui',"29157390");
					$this->view->assign('email', $_SESSION["rezultat_array"][18]);
					$this->view->assign('trtype',$librapay->trtype);
					$this->view->assign('return_url', "http://www.christiantransfers.eu/test_response.html");
					$this->view->assign('time', time());
					
					
$title="Payment for ".$nume_aeroport." to ".$nume_destinatie;
$meta="Payment for ".$nume_aeroport." to ".$nume_destinatie."";
					
					$this->view->assign("title",$title);
					//$this->view->assign("description",$meta);

					$this->view->assign("latime_tabel_dreapta",$latime_tabel_dreapta);
					
					$this->view->assign('eroarePlata', '1');
    				$this->view->assign("calculate_sign",calculateSign($data,$key));
					//$this->view->assign('string_plata', $librapay->string); // de scos pe LIVE
					//$this->view->assign('form_plata', $librapay->form);
				}
				
				$this->view->assign("mesaj",$mesaj);
				echo $this->view->render('confirmare.phtml');
				mail("contact@christiantransfers.eu","Confirmare plata online www.christiantransfers.eu ".$data["ORDER"],$mesaj,"From:<contact@christiantransfers.eu>\r\nContent-type: text/html; charset=us-ascii");
			
			/*
			Mesajele/flag-urile din raspuns care stabilesc felul in care sa considerati tranzatia sunt:
			ACTION String(1)
			•	0 - tranzactie aprobata
			•	1 - tranzactie duplicata
			•	2 - tranzatie respinsa
			•	3 - eroare de procesare
			RC String(2) Valoare generata de banca emitenta conform standardului ISO8583. Puteti descarca o lista cu coduri posibile aici. (https://www.activare3dsecure.ro/teste3d/error.txt)
			MESSAGE String(1-50) Descrierea campului RC, ex: Approved/Transaction declined/Authentication failed.
			*/
		}
	}
}