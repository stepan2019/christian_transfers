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
			
function hmacmd5($key,$data) {
   $blocksize = 64;
   $hashfunc  = 'md5';
   if(strlen($key) > $blocksize)
     $key = pack('H*', $hashfunc($key));
   $key  = str_pad($key, $blocksize, chr(0x00));
   $ipad = str_repeat(chr(0x36), $blocksize);
   $opad = str_repeat(chr(0x5c), $blocksize);
   $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));
   return bin2hex($hmac);
}
function euplatesc_mac($data, $key)
{
  $str = NULL;
  foreach($data as $d)
  {
   	if($d === NULL || strlen($d) == 0)
  	  $str .= '-'; // valorile nule sunt inlocuite cu -
  	else
  	  $str .= strlen($d) . $d;
  }
  $key = pack('H*', $key); // convertim codul secret intr-un string binar
  return hmacmd5($key, $str);
}

$key="7D16C9A9D9B3F486E67BC710A4A4B1F025A28391";
		$zcrsp =  array (
		'amount'     => addslashes(trim(@$_POST['amount'])),  //original amount
		'curr'       => addslashes(trim(@$_POST['curr'])),    //original currency
		'invoice_id' => addslashes(trim(@$_POST['invoice_id'])),//original invoice id
		'ep_id'      => addslashes(trim(@$_POST['ep_id'])), //Euplatesc.ro unique id
		'merch_id'   => addslashes(trim(@$_POST['merch_id'])), //your merchant id
		'action'     => addslashes(trim(@$_POST['action'])), // if action ==0 transaction ok
		'message'    => addslashes(trim(@$_POST['message'])),// transaction responce message
		'approval'   => addslashes(trim(@$_POST['approval'])),// if action!=0 empty
		'timestamp'  => addslashes(trim(@$_POST['timestamp'])),// meesage timestamp
		'nonce'      => addslashes(trim(@$_POST['nonce'])),
		);
		 
		$zcrsp['fp_hash'] = strtoupper(euplatesc_mac($zcrsp, $key));
			
				//var_dump($data);
				switch($zcrsp["action"]) {
					case "0":
					$mesaj = 'Your payment of '.$zcrsp['amount'].' '.$zcrsp['curr'].' was approved<br /><br />We thank you for your choice to be our customer - Christian Transfers';
					break;
					case "1":
					$mesaj = 'Your payment of '.$zcrsp['amount'].' '.$zcrsp['curr'].' is duplicated';
					break;
					case "2":
					$mesaj = 'Your payment of '.$zcrsp['amount'].' '.$zcrsp['curr'].' was rejected: '.$zcrsp['message'].'<br />';
					break;
					case "3":
					$mesaj = 'Your payment of '.$zcrsp['amount'].' '.$zcrsp['curr'].' could not be approved. The following error occured: '.$lperrors[$_GET["RC"]].'<br />';
					break;
					default:
					$mesaj = 'Your payment of '.$zcrsp['amount'].' '.$zcrsp['curr'].' could not be approved. The following error occured: '.$lperrors[$_GET["RC"]].'<br />';
					break;
				}
				if($zcrsp["action"]=='3' || $zcrsp["action"]=='2') {
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
					
					
					$q->query("select arrival, destination from ORDERS where id_order='".$zcrsp['invoice_id']."'");
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
					
				}

				
				$this->view->assign("mesaj",$mesaj);
				echo $this->view->render('confirmare.phtml');
				mail("contact@christiantransfers.eu","Confirmare plata online www.christiantransfers.eu ".$data["ORDER"],$mesaj,"From:<contact@christiantransfers.eu>\r\nContent-type: text/html; charset=us-ascii");
			
			/*
			Mesajele/flag-urile din raspuns care stabilesc felul in care sa considerati tranzatia sunt:
			ACTION String(1)
			â_¢	0 - tranzactie aprobata
			â_¢	1 - tranzactie duplicata
			â_¢	2 - tranzatie respinsa
			â_¢	3 - eroare de procesare
			RC String(2) Valoare generata de banca emitenta conform standardului ISO8583. Puteti descarca o lista cu coduri posibile aici. (https://www.activare3dsecure.ro/teste3d/error.txt)
			MESSAGE String(1-50) Descrierea campului RC, ex: Approved/Transaction declined/Authentication failed.
			*/
		}
	}