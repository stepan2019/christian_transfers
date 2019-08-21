<?php
session_start();
include("../functions.php");
include("../translations_new/RO.php");
$q=new CDb();
$q2=new CDb();
$cars='';
if (isset($_REQUEST["pasageri"]) && $_REQUEST["pasageri"]!=''){
    $q->query("select * from AUTO where nr_pasageri>=".$_REQUEST["pasageri"]." order by nr_pasageri asc" );
    while ($q->next_record()){
        $cars .= "<option value=\"".$q->f("id_auto")."-".$q->f("nume_auto")."\">".$q->f("nume_auto")."</option>";
    }
    if (isset($_REQUEST["selected_car"]) && $_REQUEST["selected_car"]!=''){
        $cars = str_replace("value=\"".$_REQUEST["selected_car"]."\"","value=\"".$_REQUEST["selected_car"]."\" selected",$cars);
    }
} else {
    $cars = '';
}
print $cars;