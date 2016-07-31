<?php

$PageSecurity = 5;

include('includes/session.inc');

if($_GET['u']!=1)       {
if($_GET['p']!='')      {    
$pono=$_GET['q'];
$item=$_GET['p'];
$maxdispatchqty=$_GET['r']-$_GET['s'];
}

}else       {
$flag=1;
$sql5="SELECT * 
      FROM dispatchclearance
      WHERE dispatchclrno=".$_GET['t']; 
$result5=DB_query($sql5,$db);
$myrow5=DB_fetch_array($result5);  
$item=$myrow5['itemcode'];
$pono=$myrow5['pono'];
$dcno=$_GET['t'];
$delivery=ConvertSQLDate($myrow5['deliverydate']);
$dcqty=$myrow5['quantity'];
$dcstatus=$myrow5['dcstatusid'];

$sql8="SELECT description 
       FROM stockmaster
       WHERE stockid='".$item."'";
$result8=DB_query($sql8,$db);
$myrow8=DB_fetch_array($result8);
$itemname=$myrow8[0];       
}


include('DCcreation-leftpanel1.php');


?>
