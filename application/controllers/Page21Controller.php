<?php

/*
 * Created by Valizr Oct 20, 2011
 * Reason: pickup car form controller
 */
require_once 'Zend/Controller/Action.php';

class Page21Controller extends Zend_Controller_Action {

    public $view;

    public function init() {
        $this->view = Zend_Registry::get('VIEW');
        $this->view->setScriptPath(BASE_PATH . 'application/views/scripts/page3');
    }

    public function indexAction() {
        $translation = Zend_Registry::get('TRANSLATIONS');
        $titluri = Zend_Registry::get('TITLURI');

        require_once("IndexController.php");
        //IndexController::assignDayMonthYear();
        //assigning flags change
        include("include/vars.php");

        $rezultat_array = Array();
        $rezultat_array[0] = $nume_aeroport;
        $rezultat_array[1] = $nume_destinatie;
        
        $rezultat_array['nume_aeroport_return'] = $nume_aeroport_return;
        $rezultat_array['nume_destinatie_return'] = $nume_destinatie_return;

        $arrival_shuttle_time = '';
        $dep_shuttle_time = '';

        $arrival_shuttle_station1 = '';
        $arrival_shuttle_station2 = '';
        $arrival_duration = '';

        $dep_shuttle_station1 = '';
        $dep_shuttle_station2 = '';
        $dep_duration = '';

		
        if (isset($_POST["arrival_auto"])) {
            ($_POST["arrival_auto"] == 9999) ? $arrival_auto = 0 : $arrival_auto = $_POST["arrival_auto"];
			
            $numberToWord = array(0 => 'unu', 1 => 'doi', 2 => 'sapte', 3 => 'sapte', 4 => 'sapte', 5 => 'sapte', 6 => 'sapte', 7 => 'sapte');
            $monthToWord = array('01' => 'ianuarie', '02' => 'februarie', '03' => 'martie', '04' => 'aprilie', '05' => 'mai', '06' => 'iunie', '07' => 'iulie', '08' => 'august', '09' => 'septembrie', '10' => 'octombrie', '11' => 'noiembrie', '12' => 'decembrie');

				
			//DACA E SHUTTLE
            if ($arrival_auto == 0) {
				
				$selectedDate = strtotime($_SESSION["dep-date"]);
				$monthArrival = $month = date("m",$selectedDate);
				$now = time();
				$dateDiff = $selectedDate - $now;
				$daysArrival = floor($dateDiff / (60 * 60 * 24));
				$numberToWord = array(0 =>'unu', 1 =>'doi', 2 =>'sapte', 3 => 'sapte', 4 => 'sapte', 5 => 'sapte', 6 => 'sapte', 7 => 'sapte');
				$monthToWord = array('01' => 'ianuarie', '02' => 'februarie', '03' => 'martie', '04' => 'aprilie', '05' => 'mai', '06' => 'iunie', '07' => 'iulie', '08' => 'august', '09' => 'septembrie','10' => 'octombrie','11' => 'noiembrie','12' => 'decembrie');

				if (in_array($daysArrival, [0,1,2,3,4,5,6,7])) {
					$q->query('select '. $numberToWord[$daysArrival] .', '.$monthToWord[$monthArrival].'  from AEROPORT'.$uk.' where id_aeroport = '.$id_aeroport);
					$q->next_record();
					$q2->query('select '. $numberToWord[$daysArrival] .', '.$monthToWord[$monthArrival].'  from TARA where id_tara = '.$id_tara);
					$q2->next_record();
					$percentAdded = ($q->f($numberToWord[$daysArrival]) + $q->f($monthToWord[$monthArrival]) + $q2->f($numberToWord[$daysArrival]) + $q2->f($monthToWord[$monthArrival]))/100;
				} else {
					$q->query('select '. $monthToWord[$monthArrival].'  from AEROPORT'.$uk.' where id_aeroport = '.$id_aeroport);
					$q->next_record();
					$q2->query('select '. $monthToWord[$monthArrival].'  from TARA where id_tara = '.$id_tara);
					$q2->next_record();
					$percentAdded = ($q->f($monthToWord[$monthArrival]) + $q2->f($monthToWord[$monthArrival]))/100;
				}
				$percentAddedCountry = 0; //calculated above.
				
                $arrival_shuttle = strip_tags($_POST["arrival_shuttle"]);
                $q->query("select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII ls JOIN STATII s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legatura_statie='$arrival_shuttle'");
                $q->next_record();

                $arrival_shuttle_time = substr($q->f("ora_pornire"), 0, -3);
                $arrival_duration = ($q->f("ora_pornire") > $q->f("ora_sosire")) ? (($q->f("ora_sosire") + 24 - $q->f("ora_pornire")) * 60) : (($q->f("ora_sosire") - $q->f("ora_pornire")) * 60);
                $arrival_shuttle_station1 = ($q->f("statie_pornire") == "Your Address") ? '' : $q->f("statie_pornire");
                $arrival_shuttle_station2 = ($q->f("statie_sosire") == "Your Address") ? '' : $q->f("statie_sosire");
                $arrival_pret = $q->f("pret") * (1 + $percentAdded + $percentAddedCountry);
				
				
            }
			
            $q2->query("select p.pret,p.pret_uk,l.timp from LEGATURI$uk l join PRETURI$uk p ON (l.id_legaturi=p.id_legaturi) where l.id_aeroport='$id_aeroport' and l.id_destinatie='$id_destinatie' and p.id_auto='$arrival_auto'");
            $q2->next_record();
            $arrival_duration = $q2->f("timp");
           
			//DACA E MASINA
            if ($arrival_auto != 0) {
				$numberToWord = array(0 =>'unu', 1 =>'doi', 2 =>'sapte', 3 => 'sapte', 4 => 'sapte', 5 => 'sapte', 6 => 'sapte', 7 => 'sapte');
				$monthToWord = array('01' => 'ianuarie', '02' => 'februarie', '03' => 'martie', '04' => 'aprilie', '05' => 'mai', '06' => 'iunie', '07' => 'iulie', '08' => 'august', '09' => 'septembrie','10' => 'octombrie','11' => 'noiembrie','12' => 'decembrie');

			    $selectedDate = strtotime($_SESSION["dep-date"]);
                $monthArrival = $month = date("m", $selectedDate);
                $now = time();
                $dateDiff = $selectedDate - $now;
                $daysArrival = floor($dateDiff / (60 * 60 * 24));
                if (in_array($daysArrival, [0, 1, 2, 3, 4, 5, 6, 7])) {
					

                    $q->query('select ' . $numberToWord[$daysArrival] . ', ' . $monthToWord[$monthArrival] . '  from AEROPORT' . $uk . ' where id_aeroport = ' . $id_aeroport);
                    $q->next_record();
                    $q3->query('select '. $numberToWord[$daysArrival] .', '.$monthToWord[$monthArrival].'  from TARA where id_tara = '.$id_tara);
                    $q3->next_record();
                    $percentAdded = (intVal($q->f($numberToWord[$daysArrival])) + intVal($q->f($monthToWord[$monthArrival])) + intVal($q3->f($numberToWord[$daysArrival])) + intVal($q3->f($monthToWord[$monthArrival])))/100;
					
                } else {
                    $q->query('select ' . $monthToWord[$monthArrival] . '  from AEROPORT' . $uk . ' where id_aeroport = ' . $id_aeroport);
                    $q->next_record();
                    $q3->query('select '. $monthToWord[$monthArrival].'  from TARA where id_tara = '.$id_tara);
                    $q3->next_record();
					
                    $percentAdded = (intVal($q->f($monthToWord[$monthArrival]) + intVal($q3->f($monthToWord[$monthArrival]))))/100;
					//die($percentAdded);
                }
                $percentAddedCountry = 0; // calculated above

//                $q->query('select ' . $monthToWord[$monthArrival] . '  from TARA where id_tara = ' . $id_tara);
//                $q->next_record();
//                $percentAddedCountry = $q->f($monthToWord[$monthArrival]) / 100;
				if($_SESSION["country"] == 44)
					$arrival_pret = $q2->f("pret_uk") * (1 + $percentAdded + $percentAddedCountry);
				else
					$arrival_pret = $q2->f("pret") * (1 + $percentAdded + $percentAddedCountry);
            }
			
			//die($percentAdded .'_+'. $percentAddedCountry);
			
            $rezultat_array[2] = ($arrival_auto == 0) ? $arrival_pret * $_SESSION["arrival_passengers"] : $arrival_pret;
            $rezultat_array[10] = $arrival_auto;

            $q->query("select nume_auto,nr_pasageri from AUTO$uk where id_auto='$arrival_auto'");
            $q->next_record();
            $rezultat_array[12] = ($arrival_auto == 0) ? "Shuttle" : $q->f("nume_auto");
            $rezultat_array[31] = $q->f("nr_pasageri");

            $rezultat_array[33] = ($arrival_auto == 0) ? $arrival_shuttle_station1 : '';
            $rezultat_array[34] = ($arrival_auto == 0) ? $arrival_shuttle_station2 : '';
            $rezultat_array[35] = $arrival_duration;

            $final_price_euro = $rezultat_array[2];
        }
        $rezultat_array[3] = $_SESSION["oneway"];
        if (isset($_POST["dep_auto"]) && $_SESSION["oneway"] == 0) {
            ($_POST["dep_auto"] == 9999) ? $dep_auto = 0 : $dep_auto = $_POST["dep_auto"];
			
			//SHUTTLE
            if ($dep_auto == 0) {
				            
				$selectedDateDep = strtotime($_SESSION["ret-date"]);
				$monthDep = $month = date("m",$selectedDateDep);
				$nowDep = time();
				$dateDiffDep = $selectedDateDep - $nowDep;
				$daysDep = floor($dateDiffDep / (60 * 60 * 24));

                if (in_array($daysDep, [0,1,2,3,4,5,6,7])) {
                    $q->query('select '. $numberToWord[$daysDep] .', '.$monthToWord[$monthDep].'  from AEROPORT'.$uk.' where id_aeroport = '.$id_aeroport);
                    $q->next_record();
                    $q2->query('select '. $numberToWord[$daysDep] .', '.$monthToWord[$monthDep].'  from TARA where id_tara = '.$id_tara);
                    $q2->next_record();
                    $percentAddedDep = ($q->f($numberToWord[$daysDep]) + $q->f($monthToWord[$monthDep]) + $q2->f($numberToWord[$daysDep]) + $q2->f($monthToWord[$monthDep]))/100;
                } else {
                    $q->query('select '. $monthToWord[$monthDep].'  from AEROPORT'.$uk.' where id_aeroport = '.$id_aeroport);
                    $q->next_record();
                    $q2->query('select '. $monthToWord[$monthDep].'  from TARA where id_tara = '.$id_tara);
                    $q2->next_record();
                    $percentAddedDep = ($q->f($monthToWord[$monthDep]) + $q2->f($monthToWord[$monthDep]))/100;
                }
                $percentAddedDepCountry = 0; //calculated above
				
                $dep_shuttle = strip_tags($_POST["dep_shuttle"]);
                $q->query("select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII$uk ls JOIN STATII$uk s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII$uk s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legatura_statie='$dep_shuttle'");
                $q->next_record();
                $dep_shuttle_time = substr($q->f("ora_pornire"), 0, -3);
                //$dep_duration=($q->f("ora_pornire")>$q->f("ora_sosire"))?(($q->f("ora_sosire")+24-$q->f("ora_pornire"))*60):(($q->f("ora_sosire")-$q->f("ora_pornire"))*60);
                $dep_shuttle_station1 = ($q->f("statie_pornire") == "Your Address") ? '' : $q->f("statie_pornire");
                $dep_shuttle_station2 = ($q->f("statie_sosire") == "Your Address") ? '' : $q->f("statie_sosire");
                $dep_pret = $q->f("pret") * (1 + $percentAddedDep + $percentAddedDepCountry);
            }//else{
            $q2->query("select p.pret,p.pret_uk,l.timp from LEGATURI$uk l join PRETURI$uk p ON (l.id_legaturi=p.id_legaturi) where l.id_aeroport='$id_aeroport_return' and l.id_destinatie='$id_destinatie_return' and p.id_auto='$dep_auto'");
            $q2->next_record();
            $dep_duration = $q2->f("timp");
            //}
			
			//AUTO
            if ($dep_auto != 0) {
				
				
                $selectedDateDep = strtotime($_SESSION["ret-date"]);
                $monthDep = $month = date("m", $selectedDateDep);
                $nowDep = time();
                $dateDiffDep = $selectedDateDep - $nowDep;
                $daysDep = floor($dateDiffDep / (60 * 60 * 24));

                if (in_array($daysDep, [0, 1, 2, 3, 4, 5, 6, 7])) {
                    $q->query('select ' . $numberToWord[$daysDep] . ', ' . $monthToWord[$monthDep] . '  from AEROPORT' . $uk . ' where id_aeroport = ' . $id_aeroport);
                    $q->next_record();
                    $q3->query('select ' . $numberToWord[$daysDep] . ', ' . $monthToWord[$monthDep] . '  from TARA where id_tara = ' . $id_tara);
                    $q3->next_record();
                    $percentAddedDep = (intVal($q->f($numberToWord[$daysDep])) + intVal($q->f($monthToWord[$monthDep])) + intVal($q3->f($numberToWord[$daysDep])) + intVal($q3->f($monthToWord[$monthDep]))) / 100;
                } else {
                    $q->query('select ' . $monthToWord[$monthDep] . '  from AEROPORT' . $uk . ' where id_aeroport = ' . $id_aeroport);
                    $q->next_record();
                    $q3->query('select '. $monthToWord[$monthDep].'  from TARA where id_tara = '.$id_tara);
                    $q3->next_record();
                    $percentAddedDep = (intVal($q->f($monthToWord[$monthDep])) + intVal($q3->f($monthToWord[$monthDep]))) / 100;
                }
                $percentAddedDepCountry = 0; //calculated above
//                $q->query('select ' . $monthToWord[$monthDep] . '  from TARA where id_tara = ' . $id_tara);
//                $q->next_record();
//                $percentAddedDepCountry = $q->f($monthToWord[$monthDep]) / 100;
				if($_SESSION["country"] == 44)
					$dep_pret = $q2->f("pret_uk") * (1 + $percentAddedDep + $percentAddedDepCountry);
				else
					$dep_pret = $q2->f("pret") * (1 + $percentAddedDep + $percentAddedDepCountry);
            }
            //$this->view->assign("dep_auto",$dep_pret);
            $rezultat_array[4] = ($dep_auto == 0) ? $dep_pret * $_SESSION["dep_passengers"] : $dep_pret;
            $rezultat_array[11] = $dep_auto;

            $q->query("select nume_auto,nr_pasageri from AUTO$uk where id_auto='$dep_auto'");
            $q->next_record();
            $rezultat_array[13] = ($dep_auto == 0) ? "Shuttle" : $q->f("nume_auto");
            $rezultat_array[32] = $q->f("nr_pasageri");

            $rezultat_array[36] = ($dep_auto == 0) ? $dep_shuttle_station1 : '';
            $rezultat_array[37] = ($dep_auto == 0) ? $dep_shuttle_station2 : '';
            $rezultat_array[38] = $dep_duration;

            $final_price_euro += $rezultat_array[4];
        }
        $rezultat_array[5] = $final_price_euro;

		//die(print_r($rezultat_array));
		
//$year=substr($_SESSION["arrival_month"],0,4);
//$month=(substr($_SESSION["arrival_month"],4,1)==0)?substr($_SESSION["arrival_month"],5,1):substr($_SESSION["arrival_month"],4,2);
//$arrival_time=((strlen($_SESSION["arrival_day"])==1)?"0":"").$_SESSION["arrival_day"]."-".date('M', mktime(0,0,0,$month,1))."-".$year
//." ".(($arrival_shuttle_time!='')?$arrival_shuttle_time:($_SESSION["arrival_hour"].":".(strlen($_SESSION["arrival_min"])==1?"0".$_SESSION["arrival_min"]:$_SESSION["arrival_min"])));


        $arrival_time = date('d-m-Y H:i', strtotime(($arrival_shuttle_time != '') ? substr($_SESSION["dep-date"], 0, -5) . $arrival_shuttle_time : $_SESSION["dep-date"]));
        $rezultat_array[6] = $arrival_time;

        $timestamp = strtotime($arrival_time);
        $rezultat_array[7] = $timestamp;

//$year2=substr($_SESSION["dep_month"],0,4);
//$month2=(substr($_SESSION["dep_month"],4,1)==0)?substr($_SESSION["dep_month"],5,1):substr($_SESSION["dep_month"],4,2);
//$dep_time=((strlen($_SESSION["dep_day"])==1)?"0":"").$_SESSION["dep_day"]."-".date('M', mktime(0,0,0,$month2,1))."-".$year2." ".(($dep_shuttle_time!='')?$dep_shuttle_time:($_SESSION["dep_hour"].":".(strlen($_SESSION["dep_min"])==1?"0".$_SESSION["dep_min"]:$_SESSION["dep_min"])));

        $dep_time = date('d-m-Y H:i', strtotime(($dep_shuttle_time != '') ? substr($_SESSION["ret-date"], 0, -5) . $dep_shuttle_time : $_SESSION["ret-date"]));
        $rezultat_array[8] = $dep_time;

        $timestamp2 = strtotime($dep_time);
        $rezultat_array[9] = $timestamp2;
		
		$rezultat_array[99] = $_SESSION['country'];

        $_SESSION["rezultat_array"] = $rezultat_array;
//print_r ($_SESSION["rezultat_array"]);
        $pagina3 = ezmakeUrl("transfer_details", $identificator2 . "-" . $identificator21 . "-" . $identificator22);
//echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=$pagina3\">";
        header("Location:$pagina3");
    }

}
