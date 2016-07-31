<?php
$PageSecurity = 80;
include('includes/session.inc');
$stock=$_GET['stock'];
$qty=$_GET['qty'];
$price=$_GET['price'];
$tprice=$_GET['tprice'];
$lead=$_GET['lead'];
$subsidy=$_GET['subsidy'];
$netprice=$_GET['nprice'];


$sql="UPDATE bio_temppropitems SET qty=".$qty.",price=".$price.",tprice=".$tprice.",netprice=".$netprice.",subsidy=".$subsidy." WHERE leadid=".$lead." AND stockid='".$stock."'";
$result=DB_query($sql,$db);
echo $tprice;
?>