<?php
$PageSecurity = 80;
include('includes/session.inc');

$lead=$_GET['lead'];

$sql_rem="SELECT sum(netprice) FROM bio_temppropitems where leadid= $lead";  
$result_rem=DB_query($sql_rem,$db);
$myrowrem=DB_fetch_array($result_rem);

$budgetrem=$_SESSION['budgetinitial']-$myrowrem[0]; 
echo $budgetrem;
  
?>
