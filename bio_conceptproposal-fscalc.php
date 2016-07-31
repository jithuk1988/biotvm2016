<?php
$PageSecurity = 80;
include('includes/session.inc');

$source=$_GET['source'];
$fsqty=$_GET['fsqty'];


$sql9="SELECT generatingamount
       FROM bio_feedstocksources
       WHERE feedstocksourceid=$source"; 
$result9=DB_query($sql9,$db);

$myrow9=DB_fetch_array($result9);

$totalfsqty=$fsqty*$myrow9[0];

echo $totalfsqty;
?>
