<?php
  $PageSecurity = 80;      
  include('includes/session.inc');
  
  
  $sql="SELECT DISTINCT bio_leads.leadid
FROM bio_leads, bio_leadtask, bio_cust
WHERE bio_leads.leadstatus NOT
IN ( 15, 20, 43, 46, 39, 40 )
AND bio_leads.cust_id = bio_cust.cust_id
AND bio_cust.nationality =1
AND bio_cust.state =14
AND bio_cust.district
IN ( 1,2,3,7,13 )
AND bio_leads.leadid NOT
IN (SELECT leadid
FROM bio_leadtask
)";
  $result=DB_query($sql,$db);
  while($myrow=DB_fetch_array($result)){
      
      
   $teamid=8;
   
   $sql_schedule="INSERT INTO bio_leadschedule VALUES(".$myrow['leadid'].",17)"; 
   $result_schedule=DB_query($sql_schedule,$db);
   
   $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=".$myrow['leadid'].")";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
    $assigned_date=date("Y-m-d");
    $date_interval=0;
    while($row_schedule1=DB_fetch_array($result_schedule1))
    {       
        $taskid=$row_schedule1['task_master_id'];
        $date_interval+=$row_schedule1['actual_task_day'];
        
        //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
        
        $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                 leadid,
                                                 teamid,
                                                 assigneddate,
                                                 duedate,
                                                 assigned_from,
                                                 viewstatus)
                                     VALUES('".$taskid."',
                                            '".$myrow['leadid']."',
                                            '".$teamid."',
                                            '".$assigned_date."',
                                            '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                            '".$assignedfrm."',
                                            1)";
         $result_leadTask=DB_query($sql_leadTask,$db); 
        
        $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
        $date_interval+=1;                                   
    }            
      
      
  }
  

?>
