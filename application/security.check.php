<?php
session_start();
include("../functions.php");
$id_unitate = $_GET["id"];
$q=new CDb();
$q->query("select email_rezervari from UNITATE where id_unitate='$id_unitate'");
$q->next_record();
$email_pensiune_criptat=$q->f("email_rezervari");
// create an image object using the chosen background
$image = imagecreatefromjpeg("security_background.jpg");

$textColor = imagecolorallocate ($image, 0, 0, 0); 

if (strlen($email_pensiune_criptat)>25)
	imagestring ($image, 3, 5, 8,  $email_pensiune_criptat, $textColor); 

else
// write the code on the background image
imagestring ($image, 5, 5, 8,  $email_pensiune_criptat, $textColor); 

// create the hash for the verification code
// and put it in the session
$_SESSION['image_random_value'] = md5($email_pensiune_criptat);
	
// send several headers to make sure the image is not cached	
// taken directly from the PHP Manual
	
// Date in the past 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 

// always modified 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 

// HTTP/1.1 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false); 

// HTTP/1.0 
header("Pragma: no-cache"); 	


// send the content type header so the image is displayed properly
header('Content-type: image/jpeg');

// send the image to the browser
imagejpeg($image);

// destroy the image to free up the memory
imagedestroy($image);
?>