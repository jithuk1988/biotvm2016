<?php
  $PageSecurity=5; 
include ('includes/session.inc');
 $_GET['month'];
 $_GET['year'];
  $sql = "SELECT stockmaster.stockid,
                    stockmaster.description                   
                FROM stockmaster
                WHERE stockmaster.stockid in (SELECT itemcode from seasondemands where seasonid=".$_GET['month']." AND year=".$_GET['year'].")";
            $result = DB_query($sql,$db); 
            
  echo "<option selected value=0>select</option>";   
  while ($myrow= DB_fetch_array($result)) 
  {
              
  // echo "<option selected value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
  echo "<option  value='" . $myrow['stockid'] . "'>" . $myrow['description'];
  
  } 


?>
