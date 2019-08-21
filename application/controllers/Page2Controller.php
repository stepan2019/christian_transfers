<?php
/*
 * Created by Valizr on 23-08-2011
 * Reason: Select Car page controller
 */
require_once 'Zend/Controller/Action.php';

class Page2Controller extends Zend_Controller_Action
{
    public $view;

    public function init()
    {
        $this->view = Zend_Registry::get('VIEW');
        $this->view->setScriptPath('application/views/scripts/page2');
    }

    public function indexAction()
    {

        $translation = Zend_Registry::get('TRANSLATIONS');
        $titluri = Zend_Registry::get('TITLURI');

        include("include/vars.php");


        //$_SESSION["id_destinatie"]=$identificator22;
        //echo  "x".$_SESSION["pickup_tara"]." ".$_SESSION["pickup_locatie"]." ".$_SESSION["id_destinatie"];

        $javascript = '';
        $javascript_dep = '';
        $pret_autobuz = 0;
        $statii_shuttle_arrival = '';
        $statii_shuttle_dep = '';
        $cel_mai_mic_pret = '';
        $cel_mai_mic_pret_dep = '';
        $uk = '';
        if (!isset($id_tara)) {
            $catreindex = ezmakeUrl("index.php", end(explode("/", $_SERVER['REQUEST_URI'])));
            header("Location:" . $catreindex);
            die();
        }
        $currency = ($id_tara == 44 ? '&pound;' : '&euro;');
        if ($id_tara == 34) {
            $uk = '_UK';
            $currency = '&pound;';
        }

        $_SESSION["country"] = $id_tara;

        $query = "select * from LEGATURI$uk l join PRETURI$uk p ON (l.id_legaturi=p.id_legaturi) where l.id_aeroport='$id_aeroport' and l.id_destinatie='$id_destinatie' order by p.pret asc";
        $rez = makeQuery($query);
        $temp_rez = array();
        $autocounter = 0;
        $selected_arrival_auto = 0;
        $selected_date = '';
        $days_array = array();
        //if (!isset($_SESSION["arrival_month"]) || !isset($_SESSION["arrival_day"])){
        if (isset($_POST['oneway'])) {
            $_SESSION['oneway'] = $_POST['oneway'];
        }
        if (!isset($_SESSION["dep-date"]) || (isset($_POST["dep-date"]) && $_POST["dep-date"] != $_SESSION["dep-date"])) {//in case the dates are the last fields selected and session is not done on index for dep-date
            if (isset($_POST["dep-date"]) && $_POST["dep-date"] != '') {
                $_SESSION["dep-date"] = $_POST["dep-date"];
            } else {
                $_SESSION["dep-date"] = date('d M Y H:i', strtotime("+2 days"));
            }
        }
        //print_r($_POST);
        $selectedDate = strtotime($_SESSION["dep-date"]);
        $monthArrival = $month = date("m", $selectedDate);
        $now = time();
        $dateDiff = $selectedDate - $now;
        $daysArrival = floor($dateDiff / (60 * 60 * 24));
        $numberToWord = array(0 => 'unu', 1 => 'doi', 2 => 'sapte', 3 => 'sapte', 4 => 'sapte', 5 => 'sapte', 6 => 'sapte', 7 => 'sapte');
        $monthToWord = array('01' => 'ianuarie', '02' => 'februarie', '03' => 'martie', '04' => 'aprilie', '05' => 'mai', '06' => 'iunie', '07' => 'iulie', '08' => 'august', '09' => 'septembrie', '10' => 'octombrie', '11' => 'noiembrie', '12' => 'decembrie');

        if (in_array($daysArrival, [0, 1, 2, 3, 4, 5, 6, 7])) {
            $q->query('select ' . $numberToWord[$daysArrival] . ', ' . $monthToWord[$monthArrival] . '  from AEROPORT' . $uk . ' where id_aeroport = ' . $id_aeroport);
            $q->next_record();
            $q2->query('select ' . $numberToWord[$daysArrival] . ', ' . $monthToWord[$monthArrival] . '  from TARA where id_tara = ' . $id_tara);
            $q2->next_record();
            $percentAdded = ($q->f($numberToWord[$daysArrival]) + $q->f($monthToWord[$monthArrival]) + $q2->f($numberToWord[$daysArrival]) + $q2->f($monthToWord[$monthArrival])) / 100;
        } else {
            $q->query('select ' . $monthToWord[$monthArrival] . '  from AEROPORT' . $uk . ' where id_aeroport = ' . $id_aeroport);
            $q->next_record();
            $q2->query('select ' . $monthToWord[$monthArrival] . '  from TARA where id_tara = ' . $id_tara);
            $q2->next_record();
            $percentAdded = ($q->f($monthToWord[$monthArrival]) + $q2->f($monthToWord[$monthArrival])) / 100;
        }
        $percentAddedCountry = 0; //calculated above.
//        $q->query('select '. $monthToWord[$monthArrival].'  from TARA where id_tara = '.$id_tara);
//        $q->next_record();
//        $percentAddedCountry = $q->f($monthToWord[$monthArrival])/100;

        foreach ($rez as $item) {
            if (!isset($_SESSION["arrival_passengers"]) || (isset($_POST["arrival_passengers"]) && $_POST["arrival_passengers"] != $_SESSION["arrival_passengers"])) {//echo "Your session has expired, please redo your order. <a href=\"/index.php\" title=\"transfer from airport to destination\">Click here</a>";
                if (isset($_POST["arrival_passengers"])) {
                    $_SESSION["arrival_passengers"] = $_POST["arrival_passengers"];
                } else {
                    $_SESSION["arrival_passengers"] = 1;
                }
                //die();
            }
            $id_legaturi = $item[0];

            //daca id_auto = 0
            if ($item[8] == 0) {
                $q->query('select * from shuttle_run' . $uk . ' where idLegatura="' . $item[0] . '"');
                $days = '';
                while ($q->next_record()) {
                    $days .= ' day != ' . $q->f("zi") . ' &&';
                    $days_array[] = $q->f("zi");
                }
                if ($days != '') {
                    $days = substr($days, 0, -3);
                    if ($_SESSION["dep-date"]) {
                        $selected_date = date('N', $selectedDate);
                        if (in_array($selected_date, $days_array)) {
                            $javascript = '<script language=\'JavaScript\' type=\'text/javascript\'>
                            $(document).ready(function () { 
                                $.ajax({
                                   url: "/application/search_table.php",
                                   type: "get",
                                   dataType: "html",
                                   data: {"days_arrival" : "' . $days . '"},
                                   success: function(returnData){
                                     $("#booking").html(returnData);
                             alert(\'Shuttle runs only on specific dates\');
                                   },
                                   error: function(e){
                                     alert(e);
                                   }
                                });
                                $(\'#form2 input\').live(\'change\', function () { 
                                    if ($(\'input[name=arrival_auto]:checked\', \'#form2\').val()!=\'9999\'){
                             $.ajax({
                               url: "/application/search_table.php",
                               type: "get",
                               dataType: "html",
                                       success: function(returnData){
                                         $("#booking").html(returnData);
                                       },
                                       error: function(e){
                                         alert(e);
                                       }
                                    });
                                    }else{
                                        $.ajax({
                                       url: "/application/search_table.php",
                                       type: "get",
                                       dataType: "html",
                               data: {"days_arrival" : "' . $days . '"},
                               success: function(returnData){
                                 $("#booking").html(returnData);
                                         alert(\'Shuttle runs only on specific dates\');
                               },
                               error: function(e){
                                 alert(e);
                               }
                            });
                                    }
                                });
                            });
                            </script>';
                        }
                    }
                    //$weekday = date('l', strtotime($));\
                }
                $item[0] = 9999;
                $item[1] = "Shuttle";
                $item[2] = "-";
                $item[7] = "/images/auto$uk/shuttle_tn.jpg";
                $q2->query("select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII$uk ls JOIN STATII$uk s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII$uk s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legaturi='$id_legaturi' and ls.retur='0' and ls.status='1' order by ls.ora_pornire asc");
                $statii_shuttle_arrival = '<table class="responsive" cellpadding="0" cellspacing="3" style="width:100%;max-height:240px;overflow-y: scroll;">';
                $contor_statii = 0;
                while ($q2->next_record()) {
                    if ($contor_statii == 0) {
                        $cel_mai_mic_pret = $q2->f("pret") * (1 + $percentAdded + $percentAddedCountry);
                    }
                    $statii_shuttle_arrival .= '<tr><td style="text-align:center;width:60px;vertical-align:middle;"><strong>Start ' . substr($q2->f("ora_pornire"), 0, -3) . '</strong></td>
                    <td><strong>' . $q2->f("statie_pornire") . '</strong></td>
                    <!--<td style="min-width:140px;">' . $q2->f("descriere_pornire") . '</td>-->
                    <td style="padding-left:5px;text-align:center;width:60px;vertical-align:middle;"><b>Arrival ' . substr($q2->f("ora_sosire"), 0, -3) . '</b></td>
                    <td><strong>' . $q2->f("statie_sosire") . '</strong></td>
                    <!--<td style="min-width:140px;">' . $q2->f("descriere_sosire") . '</td>-->
                    <td style="text-align:right;width:85px;vertical-align:middle;">' . $_SESSION["arrival_passengers"] * $q2->f("pret") * (1 + $percentAdded + $percentAddedCountry) . ' &euro;</td>
                    <td style="vertical-align:middle;"><input type="radio" value="' . $q2->f("id_legatura_statie") . '" name="arrival_shuttle" ' . (($contor_statii == 0) ? "checked=\"checked\"" : "") . '></td></tr>';
                    $contor_statii++;
                }
                $statii_shuttle_arrival .= '</table>';
                $item[10] = $_SESSION["arrival_passengers"] * $cel_mai_mic_pret;//era *$item[10]
                array_push($temp_rez, $item);
            } else {
                $q->query("select * from AUTO$uk where id_auto='" . $item[8] . "' and nr_pasageri>=" . $_SESSION["arrival_passengers"]);
                if ($q->next_record()) {
                    $autocounter++;
                    $item[0] = $q->f("id_auto");
                    if ($autocounter == 1) $selected_arrival_auto = $q->f("id_auto");
                    $item[1] = $q->f("nume_auto");
                    $item[2] = $q->f("nr_pasageri");
                    $item[7] = $q->f("poza1");

                    if ($id_tara == 44)
                        $item[10] = $item[11] * (1 + $percentAdded + $percentAddedCountry);
                    else
                        $item[10] = $item[10] * (1 + $percentAdded + $percentAddedCountry);
                    array_push($temp_rez, $item);


                }
            }
            $rez = $temp_rez;
        }

        $this->view->assign('autoa', $rez);
        $this->view->assign('nrAutoa', count($rez));
        $title = $item[5];
        $meta = $item[6];

        if (count($rez) == 0) {
            header("Location:/tailor-made");
            exit();
        }
        if (!isset($_SESSION["oneway"])) {
            $_SESSION["oneway"] = 0;
            //echo "Your session has expired, please redo your order. <a href=\"/index.php\" title=\"transfer from airport to destination\">Click here</a>";
            //die();
        }
        if ($_SESSION["oneway"] != 1) {
            if (!isset($_SESSION["ret-date"]) || (isset($_POST["ret-date"]) && $_POST["ret-date"] != $_SESSION["ret-date"])) {//in case the dates are the last fields selected and session is not done on index for ret-date
                if (isset($_POST["ret-date"]) && $_POST["ret-date"] != '') {
                    $_SESSION["ret-date"] = $_POST["ret-date"];
                } else {
                    $_SESSION["ret-date"] = date('d M Y H:i', strtotime("+3 days"));
                }
            }
            $id_aeroport_dep = isset($_SESSION["pickup_locatie_return"]) ? $_SESSION["pickup_locatie_return"] : $_POST["pickup_locatie_return"];
            $id_destinatie_dep = isset($_SESSION["id_destinatie_return"]) ? $_SESSION["id_destinatie_return"] : $_POST["id_destinatie_return"];
//            echo "aeroport departure ".$id_aeroport_dep;
//            die();
            $pret_autobuz = 0;
            $query = "select * from LEGATURI$uk l join PRETURI$uk p ON (l.id_legaturi=p.id_legaturi) where l.id_aeroport='$id_aeroport_dep' and l.id_destinatie='$id_destinatie_dep' order by p.pret asc";
            //die($query);
            $rez = makeQuery($query);
            $temp_rez = array();
            $autocounterdep = 0;
            $selected_dep_auto = 0;

            $selectedDateDep = strtotime($_SESSION["ret-date"]);
            $monthDep = $month = date("m", $selectedDateDep);
            $nowDep = time();
            $dateDiffDep = $selectedDateDep - $nowDep;
            $daysDep = floor($dateDiffDep / (60 * 60 * 24));

            if (in_array($daysDep, [0, 1, 2, 3, 4, 5, 6, 7])) {
                $q->query('select ' . $numberToWord[$daysDep] . ', ' . $monthToWord[$monthDep] . '  from AEROPORT' . $uk . ' where id_aeroport = ' . $id_aeroport_dep);
                $q->next_record();
                $q2->query('select ' . $numberToWord[$daysDep] . ', ' . $monthToWord[$monthDep] . '  from TARA where id_tara = ' . $id_tara);
                $q2->next_record();
                $percentAddedDep = ($q->f($numberToWord[$daysDep]) + $q->f($monthToWord[$monthDep]) + $q2->f($numberToWord[$daysDep]) + $q2->f($monthToWord[$monthDep])) / 100;
            } else {
                $q->query('select ' . $monthToWord[$monthDep] . '  from AEROPORT' . $uk . ' where id_aeroport = ' . $id_aeroport_dep);
                $q->next_record();
                $q2->query('select ' . $monthToWord[$monthDep] . '  from TARA where id_tara = ' . $id_tara);
                $q2->next_record();
                $percentAddedDep = ($q->f($monthToWord[$monthDep]) + $q2->f($monthToWord[$monthDep])) / 100;
            }
            $percentAddedDepCountry = 0; //calculated above
//            $q->query('select '. $monthToWord[$monthDep].'  from TARA where id_tara = '.$id_tara);
//            $q->next_record();
//            $percentAddedDepCountry = $q->f($monthToWord[$monthDep])/100;
            foreach ($rez as $item) {
                if (!isset($_SESSION["dep_passengers"]) || (isset($_POST["dep_passengers"]) && $_POST["dep_passengers"] != $_SESSION["dep_passengers"])) {//echo "Your session has expired, please redo your order. <a href=\"/index.php\" title=\"transfer from airport to destination\">Click here</a>";
                    if (isset($_POST["dep_passengers"])) {
                        $_SESSION["dep_passengers"] = $_POST["dep_passengers"];
                    } else {
                        $_SESSION["dep_passengers"] = 1;
                    }
                    //die();
                }
                $id_legaturi = $item[0];

                //DACA auto_id == 0 la retur
                if ($item[8] == 0) {
                    $q->query("select id_legaturi from LEGATURI$uk where id_aeroport='$id_aeroport_dep' and id_destinatie='$id_destinatie_dep'");
                    if ($q->next_record()) {
                        $idLegaturaRetur = $q->f("id_legaturi");
                    } else {
                        $idLegaturaRetur = $item[0];
                    }
                    $q->query('select * from shuttle_run' . $uk . ' where idLegatura="' . $idLegaturaRetur . '"');
                    $days_dep = '';
                    $days_array_dep = array();
                    while ($q->next_record()) {
                        $days_dep .= ' day != ' . $q->f("zi") . ' &&';
                        $days_array_dep[] = $q->f("zi");
                    }
                    if ($days_dep != '') {
                        $days_dep = substr($days_dep, 0, -3);
                        if ($_SESSION["ret-date"]) {
                            $selected_date_dep = date('N', $selectedDateDep);
                            if (in_array($selected_date_dep, $days_array_dep) || in_array($selected_date, $days_array)) {
                                $javascript_dep = '<script language=\'JavaScript\' type=\'text/javascript\'>
                                $(document).ready(function () { 
                                    $.ajax({
                                       url: "/application/search_table.php",
                                       type: "get",
                                       dataType: "html",
                                       data: {"days_arrival" : "' . $days . '","days_dep" : "' . $days_dep . '"},
                                       success: function(returnData){
                                         $("#booking").html(returnData);
                                 alert(\'Shuttle runs only on specific dates\');
                                       },
                                       error: function(e){
                                         alert(e);
                                       }
                                    });
                                    $(\'#form2 input\').live(\'change\', function () { 
                                        if ($(\'input[name=dep_auto]:checked\', \'#form2\').val()!=\'9999\'){
                                            if ($(\'input[name=arrival_auto]:checked\', \'#form2\').val()!=\'9999\'){
                                 $.ajax({
                                   url: "/application/search_table.php",
                                   type: "get",
                                   dataType: "html",
                                               success: function(returnData){
                                                 $("#booking").html(returnData);
                                               },
                                               error: function(e){
                                                 alert(e);
                                               }
                                            });
                                            }else{
                                            $.ajax({
                                           url: "/application/search_table.php",
                                           type: "get",
                                           dataType: "html",
                                           data: {"days_arrival" : "' . $days . '"},
                                           success: function(returnData){
                                             $("#booking").html(returnData);
                                             alert(\'Shuttle runs only on specific dates\');
                                           },
                                           error: function(e){
                                             alert(e);
                                           }
                                        });
                                        }
                                        }else{
                                            if ($(\'input[name=arrival_auto]:checked\', \'#form2\').val()!=\'9999\'){
                                            $.ajax({
                                           url: "/application/search_table.php",
                                           type: "get",
                                           dataType: "html",
                                           data: {"days_dep" : "' . $days_dep . '"},
                                           success: function(returnData){
                                             $("#booking").html(returnData);
                                             alert(\'Shuttle runs only on specific dates\');
                                           },
                                           error: function(e){
                                             alert(e);
                                           }
                                        });
                                        }else{
                                            $.ajax({
                                           url: "/application/search_table.php",
                                           type: "get",
                                           dataType: "html",
                                   data: {"days_arrival" : "' . $days . '","days_dep" : "' . $days_dep . '"},
                                   success: function(returnData){
                                     $("#booking").html(returnData);
                                             alert(\'Shuttle runs only on specific dates\');
                                   },
                                   error: function(e){
                                     alert(e);
                                   }
                                });
                                        }
                                    }
                                    });
                                });
                                </script>';
                            }
                        }
                        //$weekday = date('l', strtotime($));\
                    }
                    $item[0] = 9999;
                    $item[1] = "Shuttle";
                    $item[2] = "-";
                    $item[7] = "/images/auto$uk/shuttle_tn.jpg";
                    $q2->query("select s1.nume_statie as statie_pornire, s2.nume_statie as statie_sosire,ls.* from LEGATURI_STATII$uk ls JOIN STATII$uk s1 on ls.id_statie_pornire=s1.id_statie JOIN STATII$uk s2 on ls.id_statie_sosire=s2.id_statie where ls.id_legaturi='$id_legaturi' and ls.retur='0' and ls.status='1' order by ls.ora_pornire asc");
                    $statii_shuttle_dep = '<table class="responsive" cellpadding="0" cellspacing="3" style="width:100%;max-height:240px;display: block;overflow-y: scroll;">';
                    $contor_statii_dep = 0;
                    while ($q2->next_record()) {
                        if ($contor_statii_dep == 0) {
                            $cel_mai_mic_pret_dep = $q2->f("pret") * (1 + $percentAddedDep + $percentAddedDepCountry);
                        }
                        $statii_shuttle_dep .= '<tr><td style="text-align:center;width:60px;vertical-align:middle;"><strong>Start ' . substr($q2->f("ora_pornire"), 0, -3) . '</strong></td>
                        <td><strong>' . $q2->f("statie_pornire") . '</strong></td>
                        <!--<td style="min-width:140px;">' . $q2->f("descriere_pornire") . '</td>-->
                        <td style="padding-left:5px;text-align:center;width:60px;vertical-align:middle;"><strong>Arrival ' . substr($q2->f("ora_sosire"), 0, -3) . '</strong></td>
                        <td><strong>' . $q2->f("statie_sosire") . '</strong></td>
                        <!--<td style="min-width:140px;">' . $q2->f("descriere_sosire") . '</td>-->
                        <td style="text-align:right;width:85px;vertical-align:middle;">' . $_SESSION["dep_passengers"] * $q2->f("pret") * (1 + $percentAddedDep + $percentAddedDepCountry) . ' &euro;</td>
                        <td style="vertical-align:middle;"><input type="radio" value="' . $q2->f("id_legatura_statie") . '" name="dep_shuttle"' . (($contor_statii_dep == 0) ? "checked=\"checked\"" : "") . '></td></tr>';
                        $contor_statii_dep++;
                    }
                    $statii_shuttle_dep .= '</table>';
                    $item[10] = $_SESSION["dep_passengers"] * $cel_mai_mic_pret_dep;//era *$item[10]
                    array_push($temp_rez, $item);
                } else {
                    $q->query("select * from AUTO$uk where id_auto='" . $item[8] . "' and nr_pasageri>=" . $_SESSION["dep_passengers"]);
                    //echo "select * from AUTO where id_auto='".$item[8]."' and nr_pasageri>=".$_SESSION["dep_passengers"];
                    if ($q->next_record()) {
                        $autocounterdep++;
                        $item[0] = $q->f("id_auto");
                        if ($autocounterdep == 1) $selected_dep_auto = $q->f("id_auto");
                        $item[1] = $q->f("nume_auto");
                        $item[2] = $q->f("nr_pasageri");
                        $item[7] = $q->f("poza1");

                        if ($id_tara == 44)
                            $item[10] = $item[11] * (1 + $percentAddedDep + $percentAddedDepCountry);
                        else
                            $item[10] = $item[10] * (1 + $percentAddedDep + $percentAddedDepCountry);
                        array_push($temp_rez, $item);

                    }
                }

                $rez = $temp_rez;
            }
            $this->view->assign('autod', $rez);
            $this->view->assign('nrAutod', count($rez));
            $title = $item[5];
            $meta = $item[6];

            if (count($rez) == 0) {
                header("Location:/tailor-made");
                exit();
            }
            $this->view->assign("selected_dep_auto", (isset($_SESSION["rezultat_array"][11])) ? $_SESSION["rezultat_array"][11] : $selected_dep_auto);
            $this->view->assign("return", "Return");
        }
        $this->view->assign("selected_arrival_auto", (isset($_SESSION["rezultat_array"][10])) ? $_SESSION["rezultat_array"][10] : $selected_arrival_auto);

        $this->view->assign("tara", $identificator2);
        $this->view->assign("url_aeroport", $identificator21);
        $this->view->assign("url_destinatie", $identificator22);

        $this->view->assign("nume_tara", $nume_tara);
        $this->view->assign("nume_aeroport", $nume_aeroport);
        $this->view->assign("nume_destinatie", $nume_destinatie);

        $this->view->assign("nume_tara_return", $nume_tara_return);
        $this->view->assign("nume_aeroport_return", $nume_aeroport_return);
        $this->view->assign("nume_destinatie_return", $nume_destinatie_return);

        $this->view->assign("statii_shuttle_arrival", $statii_shuttle_arrival);
        $this->view->assign("statii_shuttle_dep", $statii_shuttle_dep);//
        $this->view->assign("extra_info", $extra_info);
        $this->view->assign('currency', $currency);
        if ($_SESSION["oneway"] != 1) {
            $this->view->assign("javascript", $javascript_dep);
        } else {
            $this->view->assign("javascript", $javascript);
        }
        $this->view->assign("extra_info1", $extra_info1);
        $this->view->assign("extra_info2", $extra_info2);
        if ($id_tara == 44) {
            //uk
            if ($title == "")
                $title = "" . $nume_aeroport . " - " . $nume_destinatie . " private taxi transfer mincab service in " . $nume_tara . "";
            if ($meta == "")
                $meta = "Christian Transfers offer " . $nume_aeroport . " - " . $nume_destinatie . ", airport taxi transfer service, minicab transfer or private minivan hire in " . $nume_tara . "";
        } else {
            //europa
            if ($title == "")
                $title = "" . $nume_aeroport . " to " . $nume_destinatie . " airport transfers taxi minivan minibus in " . $nume_tara . "";
            if ($meta == "")
                $meta = "Christian Transfers offer " . $nume_aeroport . " - " . $nume_destinatie . ", airport transfers with private taxi minivan or minibus shuttle bus service in " . $nume_tara . "";
        }

        $this->view->assign("title", $title);
        $this->view->assign("description", $meta);

        if ($id_tara == 34) {
            echo $this->view->render('page2_uk.phtml');
        } else {
            echo $this->view->render('page2.phtml');
        }//print_r ($_SESSION);
    }
}
