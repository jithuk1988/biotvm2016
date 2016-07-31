<?php
  $PageSecurity = 80;
include('includes/session.inc');

$leadid=$_GET['leadid'];
$leadstatus=$_GET['status'];

 $sql2="UPDATE bio_leads SET leadstatus=$leadstatus WHERE leadid=".$leadid;     DB_query($sql2,$db);
 
?>
