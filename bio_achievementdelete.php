<?php

$PageSecurity = 80;  
include('includes/session.inc');
  $tsk=$_GET['task'];
  $desig=$_GET['designation']; 
  $date=$_GET['date'];

 $sql= "DELETE FROM bio_achievementpolicy WHERE designation=".$desig." 
                                            AND taskid=".$tsk."
                                            AND effectivedate='".$date."'";
$result=DB_query($sql,$db); 
 
prnMsg(_('deleted') ,'success');   






?>
