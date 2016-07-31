<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $edeg=$_GET['ed'];
$sdeg=$_GET['sd'];
$vsdeg=$_GET['vsd'];
$nonedeg=$_GET['nd'];
  $totalfeed=$edeg+$sdeg+$vsdeg+$nonedeg;
  echo"<input type='hidden' name='actual' id='actual' value='$totalfeed' style='width:150px'>$totalfeed";
?>
