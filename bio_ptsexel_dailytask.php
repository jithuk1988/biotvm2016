<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Daily Task Report');

$emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT bio_teammembers.teamid,
                      bio_leadteams.teamname 
               FROM bio_teammembers,bio_leadteams 
               WHERE empid=".$emp_ID."  AND
                     bio_teammembers.teamid=bio_leadteams.teamid";
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid']; 
    $assignedname=$row_team['teamname'];

$currentdate=Date("Y-m-d");

$sql13="SELECT bio_leadtask.leadid,
                  bio_leadtask.tid,
                  bio_leadtask.assigneddate,
                  bio_leadtask.duedate,
                  bio_task.task,
                  bio_leadteams.teamname,
                  bio_cust.custname              
         FROM bio_leadtask,
              bio_task,
              bio_leadteams,
              bio_leads,
              bio_cust
       WHERE  bio_leadtask.teamid=$assignedfrm
         AND (bio_leadtask.duedate>='$currentdate' AND bio_leadtask.assigneddate<='$currentdate') 
         AND  bio_leadtask.taskcompletedstatus=0        
         AND  bio_leadtask.taskid=bio_task.taskid       
         AND  bio_leadtask.teamid=bio_leadteams.teamid  
         AND  bio_leadtask.leadid=bio_leads.leadid      
         AND  bio_leads.cust_id=bio_cust.cust_id";
          
$result1=DB_query($sql13,$db);
$filename="Daily_Task.csv";

    $header= "Slno".","."Leadid".","."Customer Name".","."Task".","."Team Name".","."Assigned Date".","."Due Date"."\n";"\n";
    $data='';
    $slno=1;
    
        while($myrow3=DB_fetch_array($result1,$db))     {
    $data= $data.$slno.",".$myrow3['leadid'].",".$myrow3['custname'].",".$myrow3['task'].",".$myrow3['teamname'].",".ConvertSQLDate($myrow3['assigneddate']).",".ConvertSQLDate($myrow3['duedate'])."\n";    
    $slno++;    
      }  
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";  
   

?>

