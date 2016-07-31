<?php
$PageSecurity = 20; 
include('includes/session.inc'); 
$title = _('Task Escalation Report');
 
$status=$_GET['status'];

/*$sql_user="SELECT www_users.empid
          FROM www_users
          WHERE userid='".$_SESSION['UserID']."'";
    $result_user=DB_query($sql_user,$db);
    $row_user=DB_fetch_array($result_user);
    $emp_ID=$row_user['empid']; 
                                 */
$DateString = Date($_SESSION['DefaultDateFormat']);
$currentdate=FormatDateForSQL($DateString);

$emp_ID=$_SESSION['empid'];
if($emp_ID==1){
   $emp_ID=2; 
}
    
    $sql_team="SELECT bio_teammembers.teamid,
                      bio_leadteams.teamname 
                 FROM bio_teammembers,bio_leadteams 
                WHERE empid=".$emp_ID."  AND
                      bio_teammembers.teamid=bio_leadteams.teamid";
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid']; 


    $sql3="SELECT bio_task.task,
                  bio_leadtask.leadid,
                  bio_leadtask.tid,
                  bio_leadteams.teamname,
                  bio_leadtask.assigneddate,
                  bio_leadtask.duedate,
                  bio_cust.custname,
                  bio_cust.contactperson
             FROM bio_leadtask,
                  bio_task,
                  bio_leadteams,
                  bio_leads,
                  bio_cust 
            WHERE bio_task.taskid=bio_leadtask.taskid
              AND bio_leadtask.leadid=bio_leads.leadid      
              AND bio_leads.cust_id=bio_cust.cust_id
              AND bio_leadteams.teamid=bio_leadtask.teamid
              AND bio_leadtask.assigned_from=$assignedfrm
              AND bio_leadtask.taskcompletedstatus=$status
              AND bio_leads.enqtypeid!=1
              AND bio_leadtask.duedate<'$currentdate'";


if(!isset($_POST['excel']))  
{       
include('includes/header.inc');  

      if($status==1){
          $heading="COMPLETED TASK";
      }elseif($status==0){
          $heading="ESCALATED PENDING TASK";
      }
      
      
  echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">'.$heading.'</font></center>';    
 
echo "<table style='width:70%'><tr><td>";        
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<br><input type="hidden" name="Status" value='.$status.' >';


echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:1 solid #F0F0F0;width:100%'>";
echo "<tr><td style=width:5%><b>Sl.No</b></td>
          <td style=width:20%><b>Organisation Name</b></td>
          <td style=width:20%><b>Contact Person</b></td>
          <td style=width:25%><b>Task Name</b></td>
          <td style=width:15%><b>Team Name</b></td>
          <td style=width:15%><b>Assigned Date</b></td>
          <td style=width:15%><b>Due Date</b></td></tr>";
          
              
    
    if (($_GET['df']!="") && ($_GET['dt']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_GET['df']);   
    $sourcetypeto=FormatDateForSQL($_GET['dt']);
    $sql3 .=" AND bio_leadtask.assigneddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    echo'<input type="hidden" name="from" value='.$sourcetypefrom.' >';
    echo'<input type="hidden" name="to" value='.$sourcetypeto.' >';
    }  
   
    if (($_GET['task']!='') && ($_GET['task']!='0'))  {
    $sql3 .=" AND bio_task.taskid='".$_GET['task']."'";
    echo'<input type="hidden" name="task" value='.$_GET['task'].' >';
    }

    if (($_GET['off']!='') && ($_GET['off']!='0'))  {
    $sql3 .=" AND bio_leadteams.office_id='".$_GET['off']."'";
    echo'<input type="hidden" name="off" value='.$_GET['off'].' >'; 
    }

    if (($_GET['team']!='') && ($_GET['team']!='0'))  {
    $sql3 .=" AND bio_leadtask.teamid='".$_GET['team']."'"; 
    echo'<input type="hidden" name="team" value='.$_GET['team'].' >';    
    }
                  
    $result3=DB_query($sql3,$db);
    $i=1;
    while($row3=DB_fetch_array($result3)) 
    {      
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
        echo "<td>$i</td><td>".$row3['custname']."</td>
                         <td>".$row3['contactperson']."</td> 
                         <td>".$row3['task']."</td>
                         <td>".$row3['teamname']."</td>
                         <td>".ConvertSQLDate($row3['assigneddate'])."</td>
                         <td>".ConvertSQLDate($row3['duedate'])."</td>"; 
        echo "</tr>";       
     $i++;    
    }  
                 
echo "</table>";
echo"</div>";
echo '<br><input type="submit" name="excel" value="View as Excel">';  
echo"</form>";
echo"</td></tr></table>";


include('includes/footer.inc'); 
}

if(isset($_POST['excel']))  
{ 
$sql3="SELECT bio_task.task,
                  bio_leadtask.leadid,
                  bio_leadtask.tid,
                  bio_leadteams.teamname,
                  bio_leadtask.assigneddate,
                  bio_leadtask.duedate,
                  bio_cust.custname,
                  bio_cust.contactperson
             FROM bio_leadtask,
                  bio_task,
                  bio_leadteams,
                  bio_leads,
                  bio_cust 
            WHERE bio_task.taskid=bio_leadtask.taskid
              AND bio_leadtask.leadid=bio_leads.leadid      
              AND bio_leads.cust_id=bio_cust.cust_id
              AND bio_leadteams.teamid=bio_leadtask.teamid
              AND bio_leadtask.assigned_from=$assignedfrm
               AND bio_leads.enqtypeid!=1
               AND bio_leadtask.taskcompletedstatus=".$_POST['Status'];
    
      $filename="EscalatedTaskReport.csv";
      $header= "Slno".","."Organisation Name".","."Contact Person".","."Task Name".","."Team Name".","."Assigned Date".","."Due Date"."\n";"\n";
      $data='';
      $i=1;
      
    if (($_POST['from']!="") && ($_POST['to']!=""))  { 
    $sql3 .=" AND bio_leadtask.assigneddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";
    }  
   
    if (($_POST['task']!='') && ($_POST['task']!='0'))  {
    $sql3 .=" AND bio_task.taskid='".$_POST['task']."'";
    }

    if (($_POST['off']!='') && ($_POST['off']!='0'))  {
    $sql3 .=" AND bio_leadteams.office_id='".$_POST['off']."'";
    }

    if (($_POST['team']!='') && ($_POST['team']!='0'))  {
    $sql3 .=" AND bio_leadtask.teamid='".$_POST['team']."'";    
    }
                  
    $result3=DB_query($sql3,$db);
      
      while($row3=DB_fetch_array($result3))     
      {
           $data= $data.$i.",".$row3['custname'].",".$row3['contactperson'].",".$row3['task'].",".$row3['teamname'].",".ConvertSQLDate($row3['assigneddate']).",".ConvertSQLDate($row3['duedate'])."\n";    
           $i++;    
      } 
      
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";   
}
?>
