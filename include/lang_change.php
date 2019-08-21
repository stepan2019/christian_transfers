<?
	session_start();
	if ($_POST["lang"]!=""){
		$language=$_POST["lang"];
		$_SESSION["language"]=$language;
	}
	else if ($_SESSION["language"]=="") {
		$_SESSION["language"]="ro";
	}
//echo $_POST["lang"].",".$_SESSION["lang"];die();
//echo $_SESSION["language"];

if ($_POST["change_lang_page"]=="")
header("Location:../index.php");
else
{
$pos=strpos($_POST["change_lang_page"],"dochanges");

if ($pos===false)
header("Location:../".$_POST["change_lang_page"]."");
else 
$location=str_replace("dochanges","modifica",$_POST["change_lang_page"]);
header("Location:../".$location."");
}
?>