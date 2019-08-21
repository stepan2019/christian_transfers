<?php



include("../functions.php");

//include('date_calculation.php');



function insert_pending() {

	global $subject,$body;



	$max_level=10;

	$q = new Cdb;

	$t = new Cdb;

	$query="select * from contact where deacord='0'";

	$q->query($query);

	while ($q->next_record())

	{

		$subject1=$_POST["subject"];

		$body1=$_POST["body"];

		$fromemail="office@cazaremaramures.com";

		$mname=$q->f("nume");
		$mpers=$q->f("pers_contact");
		$toemail=$q->f("email");

		

		$subject1=str_replace("[[nume]]",$mname,$subject1);
		$subject1=str_replace("[[pers_contact]]",$mpers,$subject1);
		$subject1=str_replace("[[email]]",$toemail,$subject1);

		//$subject1=str_replace("'","\'",$subject1);



		$body1=str_replace("[[nume]]",$mname,$body1);
		$body1=str_replace("[[pers_contact]]",$mpers,$body1);
		$body1=str_replace("[[email]]",$toemail,$body1);

		//$body1=str_replace("'","\'",$body1);

		

		//echo $subject1." ".$body1;

		$query="insert into pending (id,fromemail,toemail,subject,body) values (NULL,'$fromemail','$toemail','".addslashes($subject1)."','".addslashes($body1)."');";

		$t->query($query);

	}

}

$t=new Cdb;

	

	insert_pending();

	header("Location: index.php?action=massemail");



?>