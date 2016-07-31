<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Task Report');  
include('includes/header.inc');

echo '<center><font style="color: #333;
                background:#fff;
                font-weight:bold;
                letter-spacing:0.10em;
                font-size:16px;
                font-family:Georgia;
                text-shadow: 1px 1px 1px #666;">Institution Task Report</font></center>';



     $empid=$_SESSION['empid'];
     $sql_emp1="SELECT * FROM bio_emp
                WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
 $employee_arr=array();   
     $sql_drop="DROP TABLE `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db,$y)         
                  {  
                      $sql3="SELECT empid FROM bio_emp WHERE reportto='".$empid."'";
                      $result3=DB_query($sql3,$db);
                      

                      
                      $employee_arr=array();
                      while($row3=DB_fetch_array($result3))
                      {
                      $empid=$row3['empid'];
                      $sql_insert="INSERT INTO emptable (empid) VALUES ($empid)";
                      $result_insert=DB_query($sql_insert,$db);
                      
                      $employee_arr[]=$empid;

                      showemp($empid,$db,$y);    
                                            
                      } 
                                      
                  } 
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($_SESSION[empid])";
                      $result_insert2=DB_query($sql_insert1,$db);
     
     $sql2="SELECT empid,reportto FROM bio_emp WHERE reportto=$empid";
     $result2=DB_query($sql2,$db);   
  
     while($row2=DB_fetch_array($result2))
     { 

        $empid=$row2['empid'];
        $employee_arr[]=$empid;
        $y=$empid;
        showemp($empid,$db,$y);
         
     } 
       

     $sql_select="SELECT empid FROM emptable";
     $result_select=DB_query($sql_select,$db);
     
     while($row_select=DB_fetch_array($result_select))
     {
          $employee_arr[]=$row_select['empid'];
     }
     
     $employee_arr=join(",", $employee_arr);
   
   $team_arr=array();
     // $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";

   $sql6="SELECT bio_teammembers.teamid FROM bio_teammembers,bio_emp WHERE bio_teammembers.empid=bio_emp.empid and bio_teammembers.empid IN ($employee_arr) and bio_emp.designationid in(4,5,9)";
    $result6=DB_query($sql6,$db);
    while($row6=DB_fetch_array($result6)){
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);
    
//echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
    
$date=date("Y-m-d");
$sql="SELECT bio_leadtask.teamid,
             bio_leadteams.teamname
        FROM bio_leadtask,bio_leadteams
       WHERE bio_leadtask.viewstatus=1
         AND bio_leadteams.teamid=bio_leadtask.teamid
         AND bio_leadtask.teamid IN ($team_array)
    GROUP BY bio_leadtask.teamid
         ";
         
$result=DB_query($sql,$db);
       

echo '<table style=width:1000px><tr><td>';  
echo '<fieldset style="height:400px; width:985px">';
echo '<legend><b>Team Tasks</b></legend>'; 
echo "<font size='+1' style='margin-left:25%'>Consolidated  Daily task Report on\t".date('Y-F j')." </font> ";
echo "<div style='height:390px; overflow:scroll;'>";      

echo "<table style='width:950px' border=1 cellpadding=3>"; 
echo "<tr><th width=100px>Team</th><th style='width:140px' colspan=3>Lead Registration</th><th style='width:140px' colspan=3>FS Request Submittion</th><th style='width:140px' colspan=3>Feasibility Study</th><th style='width:140px' colspan=3>Concept Proposal</th><th style='width:140px' colspan=3>Detailed Project Report</th><th style='width:140px' colspan=3>Sale Order</th></tr>";
echo "<tr><th></th><th width=40px><b>A</b></th><th width=40px><b>D</b></th><th width=40px><b>P</b></th><th width=40px><b>A</b></th><th width=40px><b>D</b></th><th width=40px><b>P</b></th><th width=40px><b>A</b></th><th width=40px><b>D</b></th><th width=40px><b>P</b></th><th width=40px><b>A</b></th><th width=40px><b>D</b></th><th width=40px><b>P</b></th><th width=40px><b>A</b></th><th width=40px><b>D</b></th><th width=40px><b>P</b></th><th width=40px><b>A</b></th><th width=40px><b>D</b></th><th width=40px><b>P</b></th></tr>";
//echo "</table>";       
//echo "<table style='width:935px' border=1 >";

$task_array=array(0,15,14,3,4,5);
$task_count=sizeof($task_array);

$sum_A=array();
$sum_D=array();
$sum_P=array();

while($row=DB_fetch_array($result))
{
    
    $teamid=$row['teamid'];
    
    if($teamid==8 || $teamid==9 || $teamid==24 || $teamid==25 || $teamid==35 || $teamid==36 || $teamid==40 || $teamid==41 || $teamid==47)
    {
        
    }else
    {

    echo'<tr onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)">';
    echo'<td width=100px>'.$row['teamname'].'</td>';
    
    for($i=0;$i<$task_count;$i++){
        
    $date1=$date." ".'00:00:00';
    $date2=$date." ".'23:59:59';     
   
    $sql_assigned="SELECT COUNT(*) AS count 
                     FROM bio_leadtask,bio_leads 
                    WHERE bio_leadtask.teamid=$teamid 
                      AND taskid=".$task_array[$i]." 
                      AND taskcompletedstatus=1 
                      AND duedate >'$date'
                      AND taskcompleteddate BETWEEN '$date1' AND '$date2' 
                      AND bio_leads.leadid=bio_leadtask.leadid 
                      AND bio_leads.enqtypeid=2 
                      AND bio_leadtask.viewstatus=1";
    $result_assigned=DB_query($sql_assigned,$db); 
    $row_assigned=DB_fetch_array($result_assigned);    
                 
                 
    $sql_TC="SELECT COUNT(*) AS count 
               FROM bio_leadtask,bio_leads 
              WHERE bio_leadtask.teamid=$teamid 
                AND taskid=".$task_array[$i]." 
                AND taskcompletedstatus=1 
                AND taskcompleteddate BETWEEN '$date1' AND '$date2' 
                AND bio_leads.leadid=bio_leadtask.leadid 
                AND bio_leads.enqtypeid=2 
                AND bio_leadtask.viewstatus=1";
    $result_TC=DB_query($sql_TC,$db);
    $row_TC=DB_fetch_array($result_TC);            
                                                             
                                                              
    $sql_TP="SELECT COUNT(*) AS count 
               FROM bio_leadtask,bio_leads 
              WHERE bio_leadtask.teamid=$teamid 
                AND taskid=".$task_array[$i]."
                AND taskcompletedstatus=0 
                AND duedate<='$date' 
                AND bio_leads.leadid=bio_leadtask.leadid 
                AND bio_leads.enqtypeid=2 
                AND bio_leadtask.viewstatus=1";
    $result_TP=DB_query($sql_TP,$db);
    $row_TP=DB_fetch_array($result_TP);
                                                                
    //echo",".$row_assigned['count'];
    $assigned=$row_TP['count']+($row_TC['count']-$row_assigned['count']);
                                                                                     
    if($task_array[$i]==0)
    {                                                           
        if($i%2==0){
            
        $sql_TT="SELECT COUNT(*) AS count 
                   FROM bio_leads,www_users,bio_teammembers 
                  WHERE bio_leads.created_by=www_users.userid
                    AND www_users.empid=bio_teammembers.empid
                    AND bio_teammembers.teamid=$teamid
                    AND bio_leads.leaddate='$date'";  
        $result_TT=DB_query($sql_TT,$db);
        $row_TT=DB_fetch_array($result_TT); 
        
        echo'<td width=40px bgcolor="#909090"> - </td><td width=40px bgcolor="#909090">'.$row_TT['count'].'</td><td width=40px bgcolor="#909090"> - </td>';     
        }
       
    }else{
         if($i%2==0){
    echo'<td width=40px bgcolor="#909090">'.$assigned.'</td><td width=40px bgcolor="#909090">'.$row_TC['count'].'</td><td width=40px bgcolor="#909090">'.$row_TP['count'].'</td>';     
    }else{
      echo'<td width=40px>'.$assigned.'</td><td width=40px>'.$row_TC['count'].'</td><td width=40px>'.$row_TP['count'].'</td>';       
    }
    }       
    $sum_TC[$i]+=$row_TT['count'];
    $sum_A[$i]+=$assigned;        $sum_D[$i]+=$row_TC['count'];       $sum_P[$i]+=$row_TP['count'];        
    }
    echo'</tr>';
    } 
   
}  
//echo "</table>";

echo "<tr><th width=100px><b>Total</b></th>";
 for($i=0;$i<$task_count;$i++){
    if($task_array[$i]==0)
    {  
     echo"<th width=40px><b> - </b></th><th width=40px><b>".$sum_TC[$i]."</b></th><th width=40px><b> - </b></th>";   
    }else{
     echo"<th width=40px><b>".$sum_A[$i]."</b></th><th width=40px><b>".$sum_D[$i]."</b></th><th width=40px><b>".$sum_P[$i]."</b></th>";     
 }
 }
 echo"</tr>";

 
echo "</fieldset>"; 
echo "</td></tr></table>";
echo "<h4> A - Assigned   D - Done   P - Pending </h4>";
echo "</div>";
echo "</form>";      
include('includes/footer.inc'); 
         
?>

<script type="text/javascript">

function ChangeBackgroundColor(row) { row.style.backgroundColor = "#9999ff"; }
function RestoreBackgroundColor(row) { row.style.backgroundColor = "#ffffff"; }

</script>