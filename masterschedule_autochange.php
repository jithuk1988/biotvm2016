<?php

  $PageSecurity=80; 
include ('includes/session.inc');

  
  $_GET['dmid'];
   $at=$_GET['amt'];   


   
  $sql = "update mrpdemands set quantity=$at,statusid=4 WHERE demandid='".$_GET['dmid']."'";
            $result = DB_query($sql,$db); 
             
      echo"<input type='text' id='rquantity".$slno."' value='$at' readonly >";

?>
