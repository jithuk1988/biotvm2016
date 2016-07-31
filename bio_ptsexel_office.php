<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Target');

$office=$_POST['Office']; 
$sql1="SELECT bio_target.taskid AS taskid, 
              bio_target.team_id AS team_id, 
              bio_target.assigneddate AS assigneddate, 
              bio_target.duedate AS duedate, 
              bio_leadtasktarget.target AS target, 
              bio_leadtasktarget.actualvalue AS actualvalue, 
              bio_leadtasktarget.task_count AS task_count, 
              bio_target.officeid AS officeid, 
              bio_leadteams.teamname, 
              bio_office.office, 
              bio_taskmaster.taskname, 
              SUM( actualvalue ) AS avalue , 
              SUM( task_count ) AS  tcount
         FROM bio_target, bio_leadtasktarget, 
              bio_leadteams, bio_taskmaster, 
              bio_office
        WHERE bio_target.team_id = bio_leadteams.teamid
          AND bio_target.taskid = bio_leadtasktarget.taskid
          AND bio_taskmaster.taskname_id = bio_leadtasktarget.target
          AND bio_target.officeid = bio_office.id
          AND bio_target.officeid=$office 
     GROUP BY bio_target.officeid, 
              bio_leadtasktarget.target";
          
$result1=DB_query($sql1,$db);
$filename="Performance.csv";

    $header= "Slno".","."Office".","."Start Date".","."End Date".","."Task".","."Target".","."Acheivment"."\n";"\n";
    $data='';
    $slno=1;
    
    while($myrow3=DB_fetch_array($result1,$db))     {
    $data= $data.$slno.",".$myrow3['office'].",".ConvertSQLDate($myrow3['assigneddate']).",".ConvertSQLDate($myrow3['duedate']).",".$myrow3['taskname'].",".$myrow3['avalue'].",".$myrow3['tcount']."\n";    
    $slno++;    
      } 
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";  
   

?>

