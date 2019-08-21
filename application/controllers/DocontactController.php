<?php

/*
 * Created on Aug 06, 2008
 *
 * Created by Valizr
 * Reason: Do contact page controller
 */

require_once 'Zend/Controller/Action.php';

class DocontactController extends Zend_Controller_Action {

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

        require_once("IndexController.php");
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
            
            $this->view->assign('value_nume',$_POST["nume"]);
            $this->view->assign('value_email',$_POST["email"]);
            $this->view->assign('value_companie',$_POST["companie"]);
            $this->view->assign('value_mobil',$_POST["mobil"]);
            $this->view->assign('value_subiect',$_POST["subiect"]);
            $this->view->assign('value_mesaj',$_POST["mesaj"]);

        } else {
            $id_ticket = '"CT' . substr(md5(microtime()), rand(0, 26), 8) . '"';
            $nume = '"' . mysqli_real_escape_string($q->connect(), $_POST["nume"]) . '"';
            $email = '"' . mysqli_real_escape_string($q->connect(), $_POST["email"]) . '"';
            $companie = '"' . mysqli_real_escape_string($q->connect(), $_POST["companie"]) . '"';
            $mobil = '"' . mysqli_real_escape_string($q->connect(), ("+".$_POST["ccode"].$_POST["mobil"])) . '"';
            $subiect = '"' . mysqli_real_escape_string($q->connect(), $_POST["subiect"]) . '"';
            $mesaj = '"' . mysqli_real_escape_string($q->connect(), $_POST["mesaj"]) . '"';

            $q->query("INSERT INTO contact (id_ticket, nume, email, companie, mobil, subiect, mesaj) "
                    . "VALUES($id_ticket, $nume, $email, $companie, $mobil, $subiect, $mesaj)");
            //mail to admin
            $q->query("SELECT LAST_INSERT_ID() as id");
            $q->next_record();
            $id = $q->f("id");
            
            mail($webmasteremail,"Christian Transfers ".$_POST["subiect"]." - ".$id,
            "
            Name: " . $_POST["nume"] . " 
            Company: " . $_POST["companie"] . "
            Mobile: " . ("+".$_POST["ccode"].$_POST["mobil"]) . "            
            E-Mail: " . $_POST["email"] . " 
            Message: " . $_POST["mesaj"] . "

            ", "From: ".$_POST["nume"]." <".$_POST["email"].">");

            //mail to client
            mail($_POST["email"], "Christian Transfers ".$_POST["subiect"]." - " . $id, "
            Name: " . $_POST["nume"] . "
            Cmpany: " . $_POST["companie"] . "
            Mobile: " . ("+".$_POST["ccode"].$_POST["mobil"]) . "
            E-Mail: " . $_POST["email"] . " 
            Message: " . $_POST["mesaj"] . "

            ", "From: Christian Transfers <$webmasteremail>");
            $this->view->assign("error", "<font color=green>".$translation["mail_trimis"]."</font>");
        }
        echo $this->view->render('contact.phtml');
    }
}
