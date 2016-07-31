<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 
if($_GET['grama']!=''){
    
    $grame= $_GET['grama'];
   $sql="SELECT block FROM bio_panchayat WHERE id=".$grame;
          $result=DB_query($sql,$db);
        $myrow1=DB_fetch_array($result);
       echo $block=$myrow1[0]; 

}  
?>
