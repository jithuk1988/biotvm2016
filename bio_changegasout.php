<?php
    $PageSecurity = 80;
  include('includes/session.inc');
  
 echo $_GET['gasout'];
  
  $sql="UPDATE bio_fs_entrydetails SET total_gas=".$_GET['gasout']." WHERE leadid=".$_GET['leadid']."";
  DB_query($sql,$db);
  
?>
