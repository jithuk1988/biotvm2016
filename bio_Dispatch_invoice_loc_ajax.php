<?php
  $PageSecurity=80;
include('includes/session.inc');

 $location=$_GET['loc'];
  $orderno=$_GET['ordno'];
 if($_GET['ordno']!="")
 {

 $SQL='UPDATE salesorders SET fromstkloc="'.$location.'" WHERE orderno="'.$orderno.'"';

$result = DB_query($SQL,$db,true);
 }

?>
