<?php
session_start();
include("../functions.php");
include("../translations_new/RO.php");
$q=new CDb();
$q2=new CDb();
$q3=new CDb();
//echo "sesiune<br>";
//print_r($_SESSION);
//echo "post<br>";
//print_r($_POST);
//die();
if ($_SESSION["pickup_tara"]!="" || $_POST["pickup_tara"]!=""){
    $q->query("select nume_tara from TARA where id_tara='".(($_SESSION["pickup_tara"])?$_SESSION["pickup_tara"]:$_POST["pickup_tara"])."'");
    $q->next_record();
    $tara=strtolower($q->f("nume_tara"));
    $nume_tara=$tara;
}
if ($_SESSION["pickup_locatie"]!="" || $_POST["pickup_locatie"]!=""){
    $uk='';
    if ($_SESSION["pickup_tara"]==34 || $_POST["pickup_tara"]==34)
        $uk = '_UK';
    $q->query("select url_aeroport from AEROPORT$uk where id_aeroport='".(($_POST["pickup_locatie"])?$_POST["pickup_locatie"]:$_SESSION["pickup_locatie"])."'");
    $q->next_record();
    $url_aeroport=$q->f("url_aeroport");
}
$destination_or_pickup_return = 2;
if ($_POST["id_destinatie"]!="") {
    //echo "uite".$_SESSION["id_destinatie"]." ".$_POST["id_destinatie"];
    if ($_SESSION["id_destinatie"] != $_POST["id_destinatie"]){
        $destination_or_pickup_return = 1;
    }
    $_SESSION["id_destinatie"]=$_POST["id_destinatie"];
    if (isset($_SESSION["rezultat_array"])) unset($_SESSION["rezultat_array"]);//08.12.13
}        
if ($_POST["oneway"]!="" && $_POST["oneway"]!=$_SESSION["oneway"])
    $_SESSION["oneway"]=$_POST["oneway"];
if ($_SESSION["oneway"]=="1") {
    $selected0="";
    $selected1="checked";
}else{
    $selected0="checked";
    $selected1="";
}
$url_destinatie = '';
if ($_SESSION["id_destinatie"]!=""){
    $uk='';
    if ($_SESSION["pickup_tara"]==34 || $_POST["pickup_tara"]==34)
        $uk = '_UK';
    $q->query("select url_destinatie from DESTINATIE$uk where id_destinatie='".$_SESSION["id_destinatie"]."'");
    $q->next_record();
    $url_destinatie=$q->f("url_destinatie");
}
if (isset($_POST["dep-date"]) && $_POST["dep-date"]!=""){
    $_SESSION["dep-date"] = $_POST["dep-date"];
    
}
if (isset($_POST["ret-date"]) && $_POST["ret-date"]!=""){
    $_SESSION["ret-date"] = $_POST["ret-date"];
}
if (!isset($_SESSION["arrival_passengers"])) $_SESSION["arrival_passengers"]=1;
if (isset($_POST["arrival_passengers"]) && $_POST["arrival_passengers"]!="" && $_SESSION["arrival_passengers"]!=$_POST["arrival_passengers"]){
    $_SESSION["arrival_passengers"] = $_POST["arrival_passengers"];
}
if (!isset($_SESSION["dep_passengers"])) $_SESSION["dep_passengers"]=1;
if (isset($_POST["dep_passengers"]) && $_POST["dep_passengers"]!="" && $_SESSION["dep_passengers"]!=$_POST["dep_passengers"]){
    $_SESSION["dep_passengers"] = $_POST["dep_passengers"];
}
?>
<script src="/js/jquery.uniform.min.js"></script>
<script src="/js/wow.min.js"></script>
<script src="/js/jquery-ui-sliderAccess.js"></script>
<script src="/js/jquery.slicknav.min.js"></script>
<script src="/js/scripts.js"></script>
<link href="/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
        $('#pickup_locatie').select2();
        $('#id_destinatie').select2();
        $('#pickup_locatie_return').select2();
        $('#id_destinatie_return').select2();
</script>
<div class="wrap">
    <form id="search_transfer" role="form" method='post' action='/airport_transfer/<?php print $nume_tara."-".$url_aeroport."-".$url_destinatie; ?>'>
    <!-- Row -->
    <div class="f-row">
        <div class="form-group datepicker one-fourth">
            <label for="dep-date">Departure date</label>
            <input type="text" id="dep-date" name="dep-date" value="<?php echo $_SESSION['dep-date'];?>" required/>
        </div>
        
        <div class="form-group select one-fourth">
            <label>Pick up location</label>
            <select class="select-wo" id="pickup_locatie" name="pickup_locatie" required onchange="searchdb();"><option value=""></option>
            <?php 
            // start PICKUP LOCATIE ----------------------------------------------------------------------------------------------------------------------------
            $orase = '';
            $orase_return = '';
            $uk='';
            $q->query("select * from TARA where status = '1' order by nume_tara asc");

            while ($q->next_record())
            {
                $pickup_tara=$q->f("id_tara");
                $orase.= "<optgroup label=\"".$q->f("nume_tara")."\">";//country name
                $orase_return.= "<optgroup label=\"".$q->f("nume_tara")."\">";//country name
                
                if ($pickup_tara==34)
                    $uk = '_UK';
                $q2->query("select * from AEROPORT$uk where id_tara='".$pickup_tara."' order by label asc, nume_aeroport asc");
                $label = '';

                while ($q2->next_record()) {
                    $orase.="<option value=\"".$q2->f("id_aeroport")."\">".$q2->f("nume_aeroport")."</option>";
                    $orase_return.="<option value=\"".$q2->f("id_aeroport")."\">".$q2->f("nume_aeroport")."</option>";
                }
            }
            if ((isset($_POST["pickup_locatie"]) && !empty($_POST["pickup_locatie"])) || (isset($_SESSION["pickup_locatie"]) && !empty($_SESSION["pickup_locatie"]))) {        
                if ($_SESSION["pickup_locatie"]!=$_POST["pickup_locatie"] && !empty($_POST["pickup_locatie"])) {            
                    $_SESSION["pickup_locatie"]=$_POST["pickup_locatie"];
                    $q3->query("select id_tara from AEROPORT$uk where id_aeroport = '".$_SESSION["pickup_locatie"]."'");
                    $q3->next_record();
                    $_SESSION["pickup_tara"]=$q3->f("id_tara");
                    if (isset($_SESSION["rezultat_array"])) unset($_SESSION["rezultat_array"]);//08.12.13
                }
                $orase = str_replace("value=\"".$_SESSION["pickup_locatie"]."\"","value=\"".$_SESSION["pickup_locatie"]."\" selected",$orase);
            }         
            print $orase;
            // end PICKUP LOCATIE ----------------------------------------------------------------------------------------------------------------------------
            ?>
            </select>
        </div>
        
        <div class="form-group select one-fourth">
            <label>Drop off location</label>
            <select class="<?php echo (isset($_POST["pickup_locatie"]) || isset($_SESSION["pickup_locatie"]))?'select-wo':'';?>" id='id_destinatie' name='id_destinatie' class='search_select1' required onchange="searchdb();"><option value=''></option>
            <?php
            // start ID_DESTINATIE ----------------------------------------------------------------------------------------------------------------------------
            if ((isset($_POST["pickup_locatie"]) && $_POST["pickup_locatie"]!="")||(isset($_SESSION["pickup_locatie"]) && $_SESSION["pickup_locatie"]!="")){
                $pickup_locatie=($_POST["pickup_locatie"]!="")?$_POST["pickup_locatie"]:$_SESSION["pickup_locatie"];
                $uk = '';
                if (isset($pickup_tara))
                    if ($pickup_tara==34)
                        $uk = '_UK';
                $q->query("select * from DESTINATIE$uk d join LEGATURI$uk l where d.id_destinatie=l.id_destinatie and l.id_aeroport ='".$pickup_locatie."' order by label asc, nume_destinatie asc ");	
                $label="";
                $destinatie = '';
                while ($q->next_record()){
                    if ($label!=$q->f("label")){
                        $label=$q->f("label");
                        $destinatie.="<optgroup label=\"$label\">";
                    }
                    $selected = "";
                    if (isset($_POST["id_destinatie"]) && $_POST["id_destinatie"]==$q->f("id_destinatie")) $selected=" selected";
                    elseif (isset($_SESSION["id_destinatie"]) && $_SESSION["id_destinatie"]==$q->f("id_destinatie")) $selected=" selected";
                    $destinatie.="<option value=\"".$q->f("id_destinatie")."\"$selected>".$q->f("nume_destinatie")."</option>";
                }
            }
            print $destinatie;
            // end ID_DESTINATIE ----------------------------------------------------------------------------------------------------------------------------
            ?>
            </select>
        </div>
        
        <div class="form-group select one-fourth">
            <label for="people">How many people?</label>
            <input placeholder="including children" type="number" name="arrival_passengers" id="arrival_passengers"  value="<?php echo $_SESSION['arrival_passengers'];?>" min="1" required  onchange="searchdb();"/>
        </div>
    </div>

    <div class="f-row" <?php echo (isset($_SESSION["oneway"]) && $_SESSION["oneway"]==1)?'style="display:none;"':""; ?>>
        <div class="form-group datepicker one-fourth">
            <label for="ret-date">Return date</label>
            <input type="text" id="ret-date" name="ret-date" value="<?php echo $_SESSION['ret-date'];?>" required />
        </div>
        <div class="form-group select one-fourth">
            <label>Pick up location</label>
            <?php
            // start PICKUP_LOCATIE_RETURN ---------------------------------------------------------------------------------------------------------------------------
            if ($url_destinatie != '') {
                if ($_SESSION["pickup_locatie_return"]!=$_POST["pickup_locatie_return"] && !empty($_POST["pickup_locatie_return"])){
                    $_SESSION["pickup_locatie_return"]=$_POST["pickup_locatie_return"];
                }
                $q3->query("select id_aeroport from AEROPORT$uk where url_aeroport = '".$url_destinatie."'");
                $q3->next_record();
                if ($destination_or_pickup_return == 1){
                    $id_aeroport_return = $q3->f("id_aeroport");
                    $_SESSION["pickup_locatie_return"] = $q3->f("id_aeroport");
                }
                else
                    $id_aeroport_return = $_SESSION["pickup_locatie_return"];
                    //if (($id_aeroport_return == $_SESSION["id_destinatie"]) || 
                        //(isset($_POST["pickup_locatie_return"]) && $_POST["pickup_locatie_return"]==$q2->f("id_aeroport") && $destination_or_pickup_return == 2)) 
                $orase_return = str_replace("value=\"".$id_aeroport_return."\"","value=\"".$id_aeroport_return."\" selected",$orase_return);

            } else $orase_return = ''; 
            ?>
            <select class="<?php echo (!empty($orase_return))?'select-wo':'';?>" id="pickup_locatie_return" name="pickup_locatie_return" required onchange="searchdb();"><option value=""></option>
            <?php echo $orase_return;
            // end PICKUP_LOCATIE_RETURN ----------------------------------------------------------------------------------------------------------------------------
            ?>
            </select>
            <?php //echo "x".$destination_or_pickup_return.' y'.$url_destinatie.' z'.$id_aeroport_return." t".$_SESSION["id_destinatie"];?>
        </div>
        
        <div class="form-group select one-fourth">
            <label>Drop off location</label>
            <?php 
            $show_select_wo_class = '';
            if (isset($_POST["pickup_locatie_return"])){
                if ($_POST["pickup_locatie_return"]!='') $show_select_wo_class = 'da';
            }
            if (isset($_SESSION["pickup_locatie_return"])){
                if ($_SESSION["pickup_locatie_return"]!='') $show_select_wo_class = 'da';
            }            
            ?>
            <select class="<?php echo (!empty($show_select_wo_class))?'select-wo':'';?>" id='id_destinatie_return' name='id_destinatie_return' required onchange="searchdb();"><option value=""></option>
            <?php
            // start ID_DESTINATIE_RETURN ----------------------------------------------------------------------------------------------------------------------------
            if ((isset($_POST["pickup_locatie_return"]) && $_POST["pickup_locatie_return"]!="")||(isset($_SESSION["pickup_locatie_return"]) && $_SESSION["pickup_locatie_return"]!="")){

                $pickup_locatie_return=($_SESSION["pickup_locatie_return"]!="")?$_SESSION["pickup_locatie_return"]:$_POST["pickup_locatie_return"];
                $uk = '';
                if (isset($pickup_tara))
                    if ($pickup_tara==34)
                        $uk = '_UK';
                $destinatie_return = '';

                $q->query("select * from DESTINATIE$uk d join LEGATURI$uk l where d.id_destinatie=l.id_destinatie and l.id_aeroport ='".$pickup_locatie_return."' order by label asc, nume_destinatie asc ");
                $label="";
                while ($q->next_record()){
                    if ($label!=$q->f("label")){
                        $label=$q->f("label");
                        $destinatie_return.="<optgroup label=\"$label\">";
                    }
                    $destinatie_return.="<option value=\"".$q->f("id_destinatie")."\">".$q->f("nume_destinatie")."</option>";
                }
                if (isset($_POST["id_destinatie_return"])){
                    $destinatie_return = str_replace("value=\"".$_POST["id_destinatie_return"]."\"","value=\"".$_POST["id_destinatie_return"]."\" selected",$destinatie_return);
                    $_SESSION["id_destinatie_return"] = $_POST["id_destinatie_return"];                
                }elseif (isset($_SESSION["id_destinatie_return"]))
                    $destinatie_return = str_replace("value=\"".$_SESSION["id_destinatie_return"]."\"","value=\"".$_SESSION["id_destinatie_return"]."\" selected",$destinatie_return);

            }
            print $destinatie_return;
            // end ID_DESTINATIE_RETURN ----------------------------------------------------------------------------------------------------------------------------
            ?>
            </select>
        </div>
   
        <div class="form-group select one-fourth">
            <label for="people">How many people?</label>
            <input placeholder="including children" type="number" name="dep_passengers" id="dep_passengers"  value="<?php echo $_SESSION['dep_passengers'];?>" min="1" required onchange="searchdb();"/>
        </div>
    </div>
    
    <!-- Row -->
    <div class="f-row">
        <div class="form-group radios">
            <div>
                <div class="radio" id="uniform-return"><span><input type="radio" name="oneway" id="return" value="0" <?php echo $selected0;?>/></span></div>
                    <label for="return">Return</label>
            </div>
            <div style="padding-left:30px;">
                <div class="radio" id="uniform-oneway"><span><input type="radio" name="oneway" id="oneway" value="1" <?php echo $selected1;?>/></span></div>
                    <label for="oneway">One way</label>
            </div>
        </div>
        <div class="form-group right">
            <button type="button" name="sendbtn" onclick="javascript:void(0);" id="sendbtn" class="btn large black">Find a transfer</button>            
<!--document.location.href='/airport_transfer/<?php print $nume_tara."-".$url_aeroport."-".$url_destinatie; ?>'-->
        </div>
        <div style="display:none">
            <input type="submit" name="submit" value="submit">
        </div>
    <script type="text/javascript">
    $('input[type=radio]#oneway').click(function() {
        $('.f-row:nth-child(2)').hide(500);
        searchdb();
    });
    $('input[type=radio]#return').click(function() {
        $('.f-row:nth-child(2)').slideToggle(500);
        searchdb();
    });
    if($('#oneway').is(':checked')){
        $('#dep-date').datetimepicker({
            minDate: 0,
            hour:10,
            minute:00,
            showMillisec: false,
            showMicrosec: false,
            showTimezone: false,
            numberOfMonths: 1,
            addSliderAccess: true, 
            dateFormat: 'dd M yy', 
            timeFormat: 'HH:mm',
            sliderAccessArgs: { touchonly: false }                
            });
    }else{
        var startDateTextBox = $('#dep-date');
        var endDateTextBox = $('#ret-date');

        $.timepicker.datetimeRange(
            startDateTextBox,
            endDateTextBox,
            {
                minDate: 0,
                hour:10,
                minute:00,
                showTime: true,
                timeOnlyShowDate: true,
                alwaysSetTime: true,
                addSliderAccess: true, 
                minInterval: (1000*60*60), // 1hr
                dateFormat: 'dd M yy', 
                timeFormat: 'HH:mm',
                sliderAccessArgs: { touchonly: false },
                beforeShowDay: function(date) {
                    var day = date.getDay();
                    return [(<?php if (isset($_GET['days_arrival'])) echo $_GET['days_arrival'];else echo "true";?>)];
                },
                start: {}, // start picker options
                end: {} // end picker options
            }
        );
    };
    var $form = $('#search_transfer');
    if($('#oneway').is(':checked')){//disable return drop off location if oneway, so form doesnt check that field
        $('#ret-date').prop('disabled',true);
        $('#id_destinatie_return').prop('disabled',true);
    }else{
        $('#ret-date').prop('disabled',false);
        $('#id_destinatie_return').prop('disabled',false);
    }
    var submit_form_btn = document.querySelector('#sendbtn');

    submit_form_btn.addEventListener('click', function () {
      if ($form[0].checkValidity()) {
          $form.find(':submit').click();
        //window.location.replace('/airport_transfer/<?php print $nume_tara."-".$url_aeroport."-".$url_destinatie; ?>');
      }
      else {
          //alert('merge');
       //$form.querySelector('input[type="submit"]').click();
       $form.find(':submit').click();
      }
    }, false);
        </script>
    </div>
    </form>
</div>