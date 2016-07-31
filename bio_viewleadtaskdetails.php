<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Task Details');  
include('includes/header.inc');
include('includes/sidemenu.php');


$leadid=$_GET['q'];
$taskid=$_GET['tid'];

echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">Task Details</font></center>';


echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";     

echo "<fieldset style='float:left;width:97%;height:150px' height:auto'>";     
//echo "<legend><h3>Contact Customer</h3>";
//echo "</legend>";

  echo "<div style='height:500px; overflow:auto;'>";   
  echo "<table  style='border:1px solid #F0F0F0;width:850px;height:100px;' id='contactcustomer'>";
  echo "<tr><th>Task</th>
            <th>Team</th>
            <th>Completed Date</th>
            <th>Remarks</th>
            <th>Status</th></tr>";  
            
    $sql_view="SELECT bio_task.task, 
                      bio_leadtask.taskcompleteddate,  
                      bio_leadtask.remarks,
                      bio_leadtask.taskcompletedstatus,
                      bio_leadteams.teamname
                 FROM bio_leadtask
            LEFT JOIN bio_leads ON (bio_leadtask.leadid = bio_leads.leadid)
            LEFT JOIN bio_task ON (bio_leadtask.taskid = bio_task.taskid)
            LEFT JOIN bio_leadteams ON (bio_leadtask.teamid = bio_leadteams.teamid)
                WHERE bio_leads.leadid=$leadid
                  
                  AND bio_leadtask.taskid=27";

                   
    $result_view=DB_query($sql_view,$db); 
    
    while($row_view=DB_fetch_array($result_view))  
    {
    $task=$row_view['task'];
     if($task=='')
     {
         $task="Lead Registered";
     }
    $team=$row_view['teamname'];
    $taskid=$row_view['taskid'];      
    
    $taskcompleteddate=convertSQLDate($row_view['taskcompleteddate']); 
    if($taskcompleteddate=='00/00/0000')  {$taskcompleteddate='-';} 
    
    $taskcompletedstatus=$row_view['taskcompletedstatus'];
    if($taskcompletedstatus=='0')  {$taskcompletedstatus='Pending';} 
    if($taskcompletedstatus=='1')  {$taskcompletedstatus='Completed';}
    if($taskcompletedstatus=='2')  {$taskcompletedstatus='Cancelled';}
   
    $remarks=$row_view['remarks']; 
   
   echo"<tr>";
   echo"<td>$task</td>";
   echo"<td>$team</td>";
   echo"<td>$taskcompleteddate</td>";  
   echo"<td>$remarks</td>";  
   echo"<td>$taskcompletedstatus</td>"; 

   echo"</tr>";
    
}    
    
echo'</div>';
echo'</table>';       
echo"</fieldset>";


echo "<fieldset style='float:left;width:100%;height:200px' height:auto'>";     
//echo "<legend><h3>Feasibility Proposal</h3>";
//echo "</legend>";

  echo "<div style='height:500px; overflow:auto;'>";   
  echo "<table  style='border:1px solid #F0F0F0;width:890px;height:100px;' id='feasibilityproposal'>";
  echo "<tr><th>Task</th>
            <th>Team</th>
            <th>Proposal Date</th>
            <th>Created By</th> 
            <th>Status</th>
            <th>Amount</th>
            <th>Email Status</th>
            <th>Print Status</th></tr>"; 
  
  $sql_fs="SELECT bio_task.task,  
                  bio_proposal_status.status, 
                  bio_fsproposal.fp_amount, 
                  bio_fsproposal.fp_date,
                  bio_fsproposal.fp_createdby,
                  bio_fsproposal.fp_approvalby,
                  bio_fsproposal.fp_approvalstatus,
                  bio_fsproposal.emailstatus,
                  bio_fsproposal.printtype, 
                  bio_leadtask.viewstatus, 
                  bio_leadteams.teamname 
             FROM bio_leadtask
        LEFT JOIN bio_leads ON (bio_leadtask.leadid = bio_leads.leadid)      
        LEFT JOIN bio_fsproposal ON (bio_leadtask.leadid = bio_fsproposal.leadid)
        LEFT JOIN bio_task ON (bio_leadtask.taskid = bio_task.taskid) 
        LEFT JOIN bio_proposal_status ON (bio_fsproposal.status = bio_proposal_status.statusid)
        LEFT JOIN bio_leadteams ON (bio_leadtask.teamid = bio_leadteams.teamid) 
            WHERE bio_leads.leadid=$leadid
              AND bio_task.taskid=15";

  $result_fs=DB_query($sql_fs,$db);
  
      while($row_fs=DB_fetch_array($result_fs))  
    {
    $task=$row_fs['task'];
    $team=$row_fs['teamname'];

    
    $taskcompletedstatus=$row_fs['taskcompletedstatus'];
    if($taskcompletedstatus=='0')  {$taskcompletedstatus='Pending';} 
    if($taskcompletedstatus=='1')  {$taskcompletedstatus='Completed';}
    if($taskcompletedstatus=='2')  {$taskcompletedstatus='Cancelled';}
    
    $viewstatus=$row_fs['viewstatus'];
    if($viewstatus=='0')  {$viewstatus='-';} 
    if($viewstatus=='1')  {$viewstatus=$team;} 
    
    $fp_amount=$row_fs['fp_amount'];
    if($taskcompletedstatus=='2')  {$fp_amount='-';}
    
    $fp_approvalstatus=$row_fs['fp_approvalstatus'];
    if($fp_approvalstatus=='1')  {$fp_approvalstatus='Not Approved';} 
    if($fp_approvalstatus=='2')  {$fp_approvalstatus='Approved';}
    if($fp_approvalstatus=='3')  {$fp_approvalstatus='Rejected';}
    if($fp_approvalstatus=='4')  {$fp_approvalstatus='Proposal Given';} 
    if($fp_approvalstatus=='5')  {$fp_approvalstatus='Cancelled';}
    if($fp_approvalstatus=='6')  {$fp_approvalstatus='Modified and Approved';}
    if($fp_approvalstatus=='7')  {$fp_approvalstatus='Customer Rejected';} 
    if($fp_approvalstatus=='8')  {$fp_approvalstatus='Customer Accepted';}
 //   if($taskcompletedstatus=='2')  {$fp_approvalstatus='-';} 
    
    $emailstatus=$row_fs['emailstatus'];
    if($emailstatus=='0')  {$emailstatus='Not Send';} 
    if($emailstatus=='1')  {$emailstatus='Email Send';}
    
    $fp_approvalby=$row_fs['fp_approvalby'];
    
    $fp_date=convertSQLDate($row_fs['fp_date']);
    if($fp_date=='00/00/0000')  {$fp_date='-';} 
    
   echo $printtype=$row_fs['printtype'];
    if($printtype=='1')  {$printtype='Local FSR with Conveyance';} 
    if($printtype=='2')  {$printtype='National FSR with Conveyance';}
    if($printtype=='3')  {$printtype='Local FSR with Conveyance and Accommodation';}
    if($printtype=='4')  {$printtype='National FSR with Conveyance and Accommodation';} 
    if($printtype=='5')  {$printtype='Local FSR with Conveyance and Accommodation Only';}
    if($printtype=='6')  {$printtype='National FSR with Conveyance and Accommodation Only';}
    if($printtype=='7')  {$printtype='Online FS Request';} 

   echo"<tr>";  
   echo"<td>$task</td>"; 
   echo"<td>$team</td>"; 
   echo"<td>$fp_date</td>"; 
   echo"<td>$viewstatus</td>"; 
   echo"<td>".$row_fs['status']."</td>"; 
   echo"<td>$fp_amount</td>";
   echo"<td>$emailstatus</td>";
   echo"<td>$printtype</td>"; 
   
   echo"<td></td>";
   echo"<td></td>";
   
   echo"</tr>";
     
    }

echo'</div>';
echo'</table>';       
echo"</fieldset>";





echo "<fieldset style='float:left;width:100%;height:150px' height:auto'>";     
//echo "<legend><h3>Feasibility Study Register</h3>";
//echo "</legend>";

  echo "<div style='height:500px; overflow:auto;'>";   
  echo "<table  style='border:1px solid #F0F0F0;width:890px;height:100px;' id='feasibilitystudyregister'>";
  echo "<tr><th>Team</th>
            <th>Easily Degradable</th>
            <th>Slowly Degradable</th>
            <th>Waste Water</th>
            <th>Total Gas</th>
            <th>Conducted Date</th>
            <th>Entry Date</th>
            <th>Created By</th></tr>"; 
  
 $sql_fse="SELECT bio_task.task,   
                  bio_fs_entrydetails.created_by,
                  bio_fs_entrydetails.edegradable,
                  bio_fs_entrydetails.sdegradable,
                  bio_fs_entrydetails.liquid_waste,
                  bio_fs_entrydetails.total_gas,
                  bio_fs_entrydetails.fs_date, 
                  bio_fs_entrydetails.created_on 
             FROM bio_leadtask
        LEFT JOIN bio_leads ON (bio_leadtask.leadid = bio_leads.leadid)      
        LEFT JOIN bio_fs_entrydetails ON (bio_leadtask.leadid = bio_fs_entrydetails.leadid)
        LEFT JOIN bio_task ON (bio_leadtask.taskid = bio_task.taskid) 
            WHERE bio_leads.leadid=$leadid
              AND bio_task.taskid=14
              AND bio_leadtask.viewstatus=1";

  $result_fse=DB_query($sql_fse,$db);
  
      while($row_fse=DB_fetch_array($result_fse))  
    {
    $task=$row_fse['task'];
    
    $edegradable=$row_fse['edegradable'];
    $sdegradable=$row_fse['sdegradable'];
    $liquid_waste=$row_fse['liquid_waste'];
    $total_gas=$row_fse['total_gas'];
    
    $viewstatus=$row_fse['viewstatus'];
    if($viewstatus=='0')  {$row_fse='Reassigned';} 
    if($viewstatus=='1')  {$row_fse='-';} 
    
    $created_on=convertSQLDate($row_fse['created_on']);
    if($created_on=='00/00/0000')  {$created_on='-';}  
    
    $fs_date=convertSQLDate($row_fse['fs_date']);
    if($fs_date=='00/00/0000')  {$fs_date='-';}  
    
    $created_by=$row_fse['created_by'];

   echo"<tr>";  
   echo"<td>$created_by</td>"; 
   echo"<td>$edegradable</td>"; 
   echo"<td>$sdegradable</td>"; 
   echo"<td>$liquid_waste</td>";
   echo"<td>$total_gas</td>";
   echo"<td>$fs_date</td>";
   echo"<td>$created_on</td>";   
   echo"<td>$created_by</td>";
   echo"<td>$viewstatus</td>";
   echo"</tr>";
     
    }

echo'</div>';
echo'</table>';       
echo"</fieldset>";


echo "<fieldset style='float:left;width:100%;height:150px' height:auto'>";     
//echo "<legend><h3>Concept Proposal</h3>";
//echo "</legend>";

  echo "<div style='height:500px; overflow:auto;'>";   
  echo "<table  style='border:1px solid #F0F0F0;width:890px;height:100px;' id='feasibilityproposal'>";
  echo "<tr><th>Task</th>
            <th>Team</th>
            <th>Proposal Date</th>
            <th>Created By</th> 
            <th>Status</th>
            <th>Amount</th>
            <th>Approved By</th>
            <th>Signatory By</th></tr>"; 
            
            
  $sql_cp="SELECT bio_task.task,
                  bio_leadtask.assigneddate,
                  bio_leadtask.duedate,
                  bio_leadtask.taskcompletedstatus,
                  bio_leadtask.taskcompleteddate,
                  bio_leadtask.viewstatus,   
                  bio_conceptproposal.created_on,
                  bio_conceptproposal.created_by,
                  bio_conceptproposal.approved_by, 
                  bio_conceptproposal.signatory_by, 
                  bio_conceptproposal.status,
                  bio_conceptproposal.total_price,  
                  bio_leadteams.teamname 
             FROM bio_leadtask
        LEFT JOIN bio_leads ON (bio_leadtask.leadid = bio_leads.leadid)      
        LEFT JOIN bio_conceptproposal ON (bio_leadtask.leadid = bio_conceptproposal.lead_id)
        LEFT JOIN bio_task ON (bio_leadtask.taskid = bio_task.taskid) 
        LEFT JOIN bio_leadteams ON (bio_leadtask.teamid = bio_leadteams.teamid) 
            WHERE bio_leads.leadid=$leadid
              AND bio_task.taskid=3";

  $result_cp=DB_query($sql_cp,$db); 
  
        while($row_cp=DB_fetch_array($result_cp))  
    {
    $task=$row_cp['task'];
    $team=$row_cp['teamname'];

    
    $viewstatus=$row_cp['viewstatus'];
    if($viewstatus=='0')  {$viewstatus='-';} 
    if($viewstatus=='1')  {$viewstatus=$team;} 
    
    $taskcompletedstatus=$row_cp['taskcompletedstatus'];
    if($taskcompletedstatus=='0')  {$taskcompletedstatus='Pending';} 
    if($taskcompletedstatus=='1')  {$taskcompletedstatus='Completed';}
    if($taskcompletedstatus=='2')  {$taskcompletedstatus='Cancelled';}
    
    $created_by=$row_cp['created_by'];
    
    $created_on=convertSQLDate($row_cp['created_on']);
    if($created_on=='00/00/0000')  {$created_on='-';} 
    
    $status=$row_cp['status'];
    if($status=='1')  {$status='Not Approved';} 
    if($status=='2')  {$status='Approved';}
    if($status=='3')  {$status='Rejected';}
    if($status=='4')  {$status='Proposal Given';} 
    if($status=='5')  {$status='Cancelled';}
    if($status=='6')  {$status='Modified and Approved';}
    if($status=='7')  {$status='Customer Rejected';} 
    if($status=='8')  {$status='Customer Accepted';}
    
    $total_price=$row_cp['total_price'];
    $approved_by=$row_cp['approved_by'];
    $signatory_by=$row_cp['signatory_by'];
    
   echo"<tr>";  
   echo"<td>$task</td>"; 
   echo"<td>$team</td>"; 
   echo"<td>$created_on</td>"; 
   echo"<td>$viewstatus</td>";
   echo"<td>$status</td>";
   echo"<td>$total_price</td>";
   echo"<td>$approved_by</td>"; 
   echo"<td>$signatory_by</td>";
 
   
   echo"<td></td>";
   echo"<td></td>";
   echo"<td></td>"; 
   echo"</tr>";
    }           

echo'</div>';
echo'</table>';       
echo"</fieldset>";
echo"</form>";
?> 
  
 
    
    
    
    
       
   
    
    
    
  
       
 
   
   
   
 