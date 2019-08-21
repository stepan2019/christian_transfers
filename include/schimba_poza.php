<?

include("../functions.php");

$q=new CDb();

if (isset($_GET["id"])){

	$id_poza=substr($_GET["id"],-1,1);
	$id=explode("_",$_GET["id"]);
	$id_unitate=$id[0];
	$tabel=$_GET["tabel"];
	
	$query="select poza$id_poza from $tabel where id_unitate='$id_unitate'";
	$q->query($query);
	$q->next_record();

$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL,$q->f("poza$id_poza"));
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
$size = curl_exec($curl_handle);
curl_close($curl_handle);
	
	
//$size=getimagesize($q->f("poza$id_poza"));
if ($size[0]>=640) $width="width=600"; else
if ($size[1]>=480) $height="height=445";
else $size="";
	echo "<table valign=\"top\" style=\"border: 1px solid rgb(191, 191, 191); padding: 2px;\" bgcolor=\"#ffffff\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
    <tr><td align=\"center\"><img src=\"".$q->f("poza$id_poza")."\" $width $height></td></tr>
    </table>";
}
?>