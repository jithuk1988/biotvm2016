<?php
$PageSecurity = 80;
include('includes/session.inc');

$title = _('Alloted tasks');  
include('includes/header.inc');
include('includes/sidemenu.php');
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

  echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">DAILY TASKS</font></center>';   
    
 
echo "<table style='width:80%'><tr><td>";
echo "<fieldset style='width:97%;'>";     
echo "<legend><h3>Daily Tasks Assigned To".$assignedname." </h3>";
echo "</legend>";   


  

  


    echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    echo "<div style='height:350px; overflow:auto;'>";
    echo "<table style='border:0px solid #F0F0F0;width:100%'>";
    
    echo'<tr><th class="viewheader">slno</th>';
    echo'<th class="viewheader">Lead no</th>';
    echo'<th class="viewheader">Customer</th>'; 
    echo'<th class="viewheader">Task</th>';
    echo'<th class="viewheader">Team</th>'; 
    echo'<th class="viewheader">Assigned By</th>';
    echo'<th class="viewheader">Assigned date</th>';
    echo'<th class="viewheader">Due date</th>';
    echo'</tr>';
    $slno=1;
    $k=0;
    
    $currentdate=Date("Y-m-d");
   
    $sql3="SELECT bio_leadtask.leadid,
                  bio_leadtask.tid,
                  bio_leadtask.assigneddate,
                  bio_leadtask.duedate,
                  bio_leadtask.assigned_from,
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
              
    $result3=DB_query($sql3,$db);
    
    while($myrow3=DB_fetch_array($result3))     {
    
    $sql_t="SELECT teamname FROM bio_leadteams
                WHERE teamid=".$myrow3['assigned_from'];
        $result_t=DB_query($sql_t,$db);
        $myrow_t=DB_fetch_array($result_t);
        $assigned_frm=$myrow_t['teamname'];    
    
      
        if ($k==1)
    {
        echo '<tr class="EvenTableRows">';
        $k=0;          
    }
    else 
    {
        echo '<tr class="OddTableRows">';
        $k=1;     
    }
    
    $taskid=$myrow3['tid'];
    $assigned_frm=$myrow_t['teamname'];
    $assigneddate=ConvertSQLDate($myrow3['assigneddate']); 
    $duedate=ConvertSQLDate($myrow3['duedate']);
    echo'<td>'.$slno.'</td>';
//    echo'<td>'.$myrow3['reqno'].'</td>';         
    echo'<td>'.$myrow3['leadid'].'</td>';
    echo'<td>'.$myrow3['custname'].'</td>'; 
    echo'<td>'.$myrow3['task'].'</td>'; 
    echo'<td>'.$myrow3['teamname'].'</td>';
    echo'<td>'.$assigned_frm.'</td>'; 
    echo'<td>'.$assigneddate.'</td>'; 
    echo'<td>'.$duedate.'</td>';
    echo"<td><a style='cursor:pointer;' onclick=viewsubtasks(".$taskid.")>View Subtasks</a></td>";
    echo'</tr>';
    
    
    $slno++;
    }
     
//    echo'<tr><td><a href="bio_pendingleads_A5p?item='.$itemcode.' &view=1 &id=1 &season='.$season.'">view all WOs against this item</a></td></tr>'; 
    echo"</div>";
    echo'</table>';
    echo '<a href="bio_ptsexel_dailytask.php" style=cursor:pointer; >' . _('View As Excel') . '</a><br>';     
    echo "</td></tr></fieldset>";
    
    echo"</table>";    
   
?>
<script type="text/javascript"> 
function viewsubtasks(str)      {

myRef = window.open("bio_userallottedtasks-viewsubtasks.php?tid=" + str,true,"viewschedule1","scrollbars=yes,width=500,height=300");   
   
}
</script>