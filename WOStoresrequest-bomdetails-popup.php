<?php
$PageSecurity = 11; 
include('includes/session.inc');
$passvar= $_GET['id'];
$passvar=split('/',$passvar);

echo $StockID=$passvar[0];
$Wono=$passvar[0]; 
 
?>
<form action="WOStoresrequest.php" method="post">
<input type="submit" name="def">
<form>
