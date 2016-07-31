<?php
$PageSecurity = 80;
include('includes/session.inc');
$stock=$_GET['stock'];
$qty=$_GET['qty'];
$price=$_GET['price'];
$tprice=$_GET['tprice'];
$user=$_GET['user'];
$subsidy=$_GET['subsidy'];
$netprice=$_GET['nprice'];

$sql="UPDATE bio_temppropitemslead SET qty=".$qty.",price=".$price.",tprice=".$tprice.",netprice=".$netprice.",subsidy=".$subsidy." WHERE userid='".$user."' AND stockid='".$stock."'";
$result=DB_query($sql,$db);
echo $tprice;
?>
