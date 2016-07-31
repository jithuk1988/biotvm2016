<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  include('includes/SQL_CommonFunctions.inc');  
  
  $taskid=$_GET['taskid'];  
  $scheduleid=$_GET['scheduleid']; 
  $startdate=$_GET['startdate'];  
  
  $sql9="SELECT actual_task_day
         FROM bio_schedule
         WHERE task_master_id= $taskid     AND
               schedule_master_id= $scheduleid";
  $result9=DB_query($sql9,$db);
  $mysql9=DB_fetch_array($result9);
  $days=$mysql9[0];   
  
  $date=explode("/",$startdate);

  $startdate=$date[2]."/".$date[1]."/".$date[0];
  
  $enddate1 = strtotime($startdate . " +$days day");
  $enddate=date("d/m/Y",$enddate1);
  echo $enddate;
  
  echo"<input type='hidden' name='EndDate' id='enddate' value='$enddate' >";

?>
