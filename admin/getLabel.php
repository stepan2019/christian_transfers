<?php

include ("../functions.php");
$q = new Cdb;

        $uk='';
        if ($_GET['tara']=='34') $uk='_UK';
        
        $q->query("select distinct label from AEROPORT$uk order by label asc");
        $label = "";
        while ($q->next_record())
            $label.="<option value=\"" . $q->f("label") . "\">" . $q->f("label") . "</option>";
        $content = '<td>Selecteaza label</td><td><select name="label"><option selected value="">Selecteaza</option>'.$label.'</select></td>';
        echo $content;

