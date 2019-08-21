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
		if (file_exists("$serverpath/images/revelion/".$_GET["delete"]) && $_GET["delete"]!=""){
		file_delete("$serverpath/images/revelion/".$_GET["delete"],$_GET["delete"],1);
		file_delete_thumb("$serverpath/images/revelion/".substr($_GET["delete"],0,-4)."_tn.jpg");
		$q->query("update REVELION set poza1 ='' where id_unitate='".$_GET["id_poza"]."'");
		}
		
}
$res_img=array();
$i=0;
if ($handle = opendir($serverpath.'/images/revelion/')) {
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
  			$poza.="<tr><td width='300'><img src='../images/revelion/".$res_img[$j]."'></td>
<td valign=\"bottom\">
	<a href='poze_revelion.php?id_poza=".$_GET["id_poza"]."&delete=".$res_img[$j]."' onClick=\"return Delete()\">Sterge</a>
</td></tr>";
	}
}
else echo "ok";
echo $poza;
?>
</table>
</html>