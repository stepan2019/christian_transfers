<html>
<script language="JavaScript">
function Delete() { 
if (confirm("Esti sigur ca vrei sa stergi poza aceasta ?")) { 
return true; 
}else{ 
return false;
} 
} 
</script>

<table width="100%" cellpadding="0" cellspacing="0" align="center" border="0">
<?php
include ("../functions.php");
$q=new Cdb();
$serverpath = $_SERVER['DOCUMENT_ROOT'];
if (isset($_GET["delete"]) && $_GET["delete"]!=""){	
		if (file_exists("$serverpath/images/terase/".$_GET["delete"]) && $_GET["delete"]!=""){
		$image=explode("_0", $_GET["delete"]);		
		$image_number=$image[1]{0};//asa maxim 9 poze
		file_delete("$serverpath/images/terase/".$_GET["delete"],$_GET["delete"],$image_number);
		file_delete_thumb("$serverpath/images/terase/".substr($_GET["delete"],0,-4)."_tn.jpg");
		$q->query("update TERASE set poza".$image_number."='' where id_unitate='".$_GET["id_poza"]."'");
		}
		
}
$res_img=array();
$i=0;
if ($handle = opendir($serverpath.'/images/terase/')) {
    while (false !== ($file=readdir($handle)))
    {
    	if ($file != "." && $file != "..") { 
    	$res_img[$i]=$file;
    	$i++;
    	}
    }
    sort($res_img);
    $id_poza=$_GET["id_poza"];
	$file="^".$id_poza."_[0-9][0-9].jpg";
	$j=0;
  	for ($j=0;$j<$i;$j++){
  		if (ereg($file,$res_img[$j]))
  			$poza.="<tr><td width='300'><img src='../images/terase/".$res_img[$j]."'></td>
<td valign=\"bottom\">
	<a href='poze_terase.php?id_poza=".$_GET["id_poza"]."&delete=".$res_img[$j]."' onClick=\"return Delete()\">Sterge</a>
</td></tr>";
	}
}
else echo "ok";
echo $poza;
?>
</table>
</html>