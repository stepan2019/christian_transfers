<?php

include("../functions.php");
	$q=new Cdb;	
	$t=new Cdb;
	$q2=new Cdb;
	$query="select * from pending limit 0,500";
    $q->query($query);
	//if ($q->nf()!=0) @mail("sqepteeq@gmail.com","optinwizard.net",$q->nf());
	while ($q->next_record())
	{		
    $query="select id from contact where email='".$q->f("toemail")."'";
	$q2->query($query);
	$q2->next_record();
	$session_id=$q2->f("id");
	$fromemail=$q->f("fromemail");
	$subject=stripslashes($q->f("subject"));
    $body=stripslashes($q->f("body"));
	/*$body.="\n If you want to delete your account, please click on this link:
http://www.optinwizard.net/member.area.delete.account.php?sess_id=$session_id";*/

	$toemail=$q->f("toemail");
	mail($toemail, $subject, $body, "From: <$fromemail>");
	$query="update contact set mail_trimis=mail_trimis+1 where email='$toemail'";
	$t->query($query);
	$query="delete FROM pending where toemail='$toemail'";
	$t->query($query);
	}

?>
