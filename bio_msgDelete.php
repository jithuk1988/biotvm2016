<?php
//bio_msgDelete.php
$PageSecurity = 80;
include('includes/session.inc');
 $id=$_GET['del'];
 $sql1="update bio_email set status=2 WHERE bio_email.id=".$id;
 $result=DB_query($sql1,$db);
 echo 'Email id '.$id.' deleted.<br />';
?>