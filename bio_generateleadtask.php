<?php
$PageSecurity = 20;


function generatetask($leadid,$taskid,$teamid,$db)     {
    
$sql="SELECT scheduleid 
      FROM bio_leadschedule
      WHERE leadid=$leadid";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);

$scheduleid=$myrow[0];

$sql2="SELECT task_master_id,
              actual_task_day
       FROM bio_schedule
       WHERE schedule_id=(SELECT MIN(schedule_id) FROM bio_schedule where schedule_id > 
                         (SELECT schedule_id FROM bio_schedule WHERE task_master_id=$taskid AND schedule_master_id=$scheduleid))";  
$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_array($result2);

$newtaskid=$myrow2[0];  
$taskdays=$myrow2[1]; 

$currentdate=Date("Y/m/d");

$startdate1 = strtotime($currentdate . " +1 day");
$startdate=date("d/m/Y",$startdate1);

$startdatecalc=date("Y/m/d",$startdate1);
$enddate1 = strtotime($startdatecalc . " +$taskdays day");
$enddate=date("d/m/Y",$enddate1);


$emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid'];

    $sql_st="UPDATE bio_leadtask SET viewstatus=0,
                                     taskcompletedstatus=1
             WHERE leadid=".$leadid;
    
    $result_st=DB_query($sql_st,$db);
    
        
    $sql="INSERT INTO bio_leadtask(taskid,
                                   leadid,
                                   teamid,
                                   assigneddate,
                                   duedate,
                                   assigned_from,
                                   viewstatus) 
                            VALUES ('".$newtaskid."',                                        
                                    '".$leadid."',
                                    '".$teamid."',
                                    '".$startdate."',
                                    '".$enddate."',
                                    '".$assignedfrm."',
                                    1)";
    $result=DB_query($sql,$db);

}  

//$leadid=1239;
//$taskid=2;
//$teamid=5;
//generatetask($leadid,$taskid,$teamid,$db);

?>
