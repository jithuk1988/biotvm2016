<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  
  $date=$_GET['date'];
  $date=FormatDateForSQL($date);
  $taskid=$_GET['tid'];         
  $leadid=$_GET['leadid'];
  $chooseDate=$_GET['choosedate']; 
/*
  $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$leadid)";  
  $result_schedule1=DB_query($sql_schedule1,$db);
  while($row=DB_fetch_array($result_schedule1))
  {
       
  }
*/       
      $sql_backup="SELECT * FROM bio_leadtask WHERE leadid=$leadid AND taskid=$taskid AND taskcompletedstatus!=2 AND taskcompletedstatus!=3";
      $result_backup=DB_query($sql_backup,$db);
      $row_backup=DB_fetch_array($result_backup);
          
          $teamid=$row_backup['teamid'];
          $assigndate=$row_backup['assigneddate'];
          $duedate=$row_backup['duedate'];
          $status=$row_backup['taskcompletedstatus'];
          $assignedfrom=$row_backup['assigned_from'];
          $viewstatus=$row_backup['viewstatus'];

      $sql_changestatus="UPDATE bio_leadtask SET taskcompletedstatus=3 WHERE leadid=$leadid AND taskid=$taskid";     DB_query($sql_changestatus,$db);
      
  if($chooseDate==1){
            
  $sql_newdate="INSERT INTO bio_leadtask (taskid,leadid,teamid,assigneddate,duedate,taskcompletedstatus,assigned_from,viewstatus)
                               VALUES ('$taskid','$leadid','$teamid','$date','$duedate','$status','$assignedfrom','$viewstatus') ";
                                DB_query($sql_newdate,$db);
 
//  $sql_updatedate="UPDATE bio_leadtask SET assigneddate='".$date."'
//                                     WHERE leadid=$leadid AND taskid=$taskid";     
  }elseif($chooseDate==2){
      
    $sql_newdate="INSERT INTO bio_leadtask (taskid,leadid,teamid,assigneddate,duedate,taskcompletedstatus,assigned_from,viewstatus)
                                 VALUES ('$taskid','$leadid','$teamid','$assigndate','$date','$status','$assignedfrom','$viewstatus') ";
                                DB_query($sql_newdate,$db);    
      
//  $sql_updatedate="UPDATE bio_leadtask SET duedate='".$date."'
//                                     WHERE leadid=$leadid AND taskid=$taskid";   
  }  
//  $result_updatedate=DB_query($sql_updatedate,$db);
    
?>
