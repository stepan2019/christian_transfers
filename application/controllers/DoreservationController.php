<?php

/*
 * Created on Aug 06, 2008
 *
 * Created by Valizr
 * Reason: Do contact page controller
 */

require_once 'Zend/Controller/Action.php';

class DoreservationController extends Zend_Controller_Action {
//		protected $db, $dbw;
    public $view;

    public function init() {
//			//get database and cache
//			$this->db = Zend_Registry::get('DB');
//			$this->dbw = Zend_Registry::get('DBW');
        //initialize Smarty templating class
        $this->view = Zend_Registry::get('VIEW');
        $this->view->setScriptPath(BASE_PATH . 'application/views/scripts/contact');

        //language assignation
        //$this->view->assign("language",$_SESSION['language']);
    }

    public function indexAction() {
        //get Translation registry
        $translation = Zend_Registry::get('TRANSLATIONS');
        $titluri = Zend_Registry::get('TITLURI');
        //get prefixes registry
        //$prefixes = Zend_Registry::get('PREFIXES');

        require_once("IndexController.php");
        //IndexController::assignDayMonthYear();
        //assigning flags change
        include("include/vars.php");
        if (md5($_POST["verificare"]) != $_SESSION['image_random_value']) {
            $err_ro .= "Textul de verificare e gresit<br>";
            $err_en .= "The verification text is wrong<br>";
        }
        if ($err_ro != "") {
            $err = "<font color=red>${"err_" . $language}</font>";
            $this->view->assign("error", $err);
            $title = $titluri["contactTitle"];
            $meta = $titluri["contactMeta"];
            $banners = $this->view->render('../common/banners.phtml');
            $this->view->assign("title", $title);
            $this->view->assign("description", $meta);
            $this->view->assign("banners", $banners);

            $this->view->assign('value_nume', $_POST["nume"]);
            $this->view->assign('value_companie', $_POST["companie"]);
            $this->view->assign('value_email', $_POST["email"]);
            $this->view->assign('value_mobil', $_POST["mobil"]);
            $this->view->assign('value_mesaj', $_POST["mesaj"]);

            $this->view->assign('value_pickup_date_t', $_POST["pickup_date_t"]);
            $this->view->assign('value_pasageri_t', $_POST["pasageri_t"]);
            $this->view->assign('value_pickup_t', $_POST["pickup_t"]);
            $this->view->assign('value_destination_t', $_POST["destination_t"]);
            $this->view->assign('value_pickup_date_r', $_POST["pickup_date_r"]);
            $this->view->assign('value_pasageri_r', $_POST["pasageri_r"]);
            $this->view->assign('value_pickup_r', $_POST["pickup_r"]);
            $this->view->assign('value_destination_r', $_POST["destination_r"]);
            $this->view->assign('vehicol_r', isset($_POST["vehicol_r"])?$_POST["vehicol_r"]:'');
            $this->view->assign('vehicol_t', isset($_POST["vehicol_t"])?$_POST["vehicol_t"]:'');
            $this->view->assign('return_selected', $_POST["return_selected"]);
        } else {
            $id_ticket = 'CT' . substr(md5(microtime()), rand(0, 26), 8);
            $nume = mysqli_real_escape_string($q->connect(), $_POST["nume"]);
            $companie = mysqli_real_escape_string($q->connect(), $_POST["companie"]);
            $email = mysqli_real_escape_string($q->connect(), $_POST["email"]);
            $mobil = mysqli_real_escape_string($q->connect(), ("+".$_POST["ccode"].$_POST["mobil"])) . '"';
            $mesaj = filter_var($_POST["mesaj"], FILTER_SANITIZE_STRING);

            $pickup_date_t = date('Y-m-d H:i',strtotime($_POST["pickup_date_t"]));
            $pasageri_t = mysqli_real_escape_string($q->connect(), $_POST["pasageri_t"]);
            $pickup_t = mysqli_real_escape_string($q->connect(), $_POST["pickup_t"]);
            $vehicol_t = mysqli_real_escape_string($q->connect(), $_POST["vehicol_t"]);
            $vehicol_nume_temp_t = explode("-", $vehicol_t);
            $vehicol_nume_t = $vehicol_nume_temp_t[1];
            $destination_t = mysqli_real_escape_string($q->connect(), $_POST["destination_t"]);
            if ($_POST["return_selected"] == 'da'){
                $pickup_date_r = date('Y-m-d H:i',strtotime($_POST["pickup_date_r"]));
                $pasageri_r = mysqli_real_escape_string($q->connect(), $_POST["pasageri_r"]);
                $pickup_r = mysqli_real_escape_string($q->connect(), $_POST["pickup_r"]);
                $vehicol_r = mysqli_real_escape_string($q->connect(), $_POST["vehicol_r"]);
                $vehicol_nume_temp_r = explode("-", $vehicol_r);
                $vehicol_nume_r = $vehicol_nume_temp_r[1];
                $destination_r = mysqli_real_escape_string($q->connect(), $_POST["destination_r"]);
                $return_email_data = "  
            Pick up date and time: " . $pickup_date_r . " 
            Passengers: " . $pasageri_r . " 
            Vehicle: " . $vehicol_nume_r . " 
            Pickup location: " . $pickup_r . " 
            Drop off location: " . $destination_r;
            } else {
                $return_email_data = '';
                $pickup_date_r='""';
                $pasageri_r='""';
                $pickup_r='""';
                $vehicol_r='""';
                $destination_r='""';
            }
            $q->query("INSERT INTO contact (id_ticket, nume, companie, email, mobil, mesaj, "
                    . "pickup_date_t, pasageri_t, pickup_t, vehicol_t, destination_t, "
                    . "pickup_date_r, pasageri_r, pickup_r, vehicol_r, destination_r) "
                    . "VALUES('$id_ticket', '$nume', '$companie', '$email', '$mobil', '$mesaj', '$pickup_date_t', '$pasageri_t', '$pickup_t', '$vehicol_t', '$destination_t', '$pickup_date_r', '$pasageri_r', '$pickup_r', '$vehicol_r', '$destination_r' )");
        $q->query("SELECT LAST_INSERT_ID() as id");
        $q->next_record();
        $id = $q->f("id");
        mail($webmasteremail,"Christian Transfers - $pickup_t - $destination_t - $id",
            "
            Name: $nume 
            Company: $companie
            Mobile: $mobil
            E-Mail: $email

            Pick up date and time: $pickup_date_t 
            Passengers: $pasageri_t 
            Vehicle: $vehicol_nume_t 
            Pickup location: $pickup_t 
            Drop off location: $destination_t
 
            $return_email_data

            Message: $mesaj

            ", "From: ".$nume." <".$email.">");

            //mail to client
            mail($_POST["email"], "Christian Transfers - $pickup_t - $destination_t - $id",
            "
            Name: $nume 
            Company: $companie
            Mobile: $mobil
            E-Mail: $email

            Pick up date and time: $pickup_date_t 
            Passengers: $pasageri_t 
            Vehicle: $vehicol_nume_t 
            Pickup location: $pickup_t 
            Drop off location: $destination_t

            $return_email_data

            Message: $mesaj

            ", "From: Christian Transfers <$webmasteremail>");
            $this->view->assign("error", "<font color=green>" . $translation["mail_trimis"] . "</font>");
        }
        echo $this->view->render('tailormade.phtml');
    }
}