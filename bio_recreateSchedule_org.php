<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $title = _('Task Schedule'); 
  include('includes/header.inc'); 
  
  $leadid=$_GET['leadid'];        
  $taskid=$_GET['tid'];
  $crdt=date("Y-m-d H:i:s");

if(isset($_GET['leadid']))
{
$sql_mail="SELECT bio_cust.custmail FROM bio_cust,bio_leads
                    WHERE bio_cust.cust_id=bio_leads.cust_id and bio_leads.leadid=".$leadid; 
  $result_mail=DB_query($sql_mail,$db);
  $myrow_mail=DB_fetch_array($result_mail);
  $mail_msg=$myrow_mail[0]; 
                }            
  if(isset($_POST['submit']))
  {

      $leadid=$_POST['leadid'];
      $taskid=$_POST['taskid'];
      $remarks=$_POST['remarks'];
      $status=$_POST['status'];
      $assigned_date=$_POST['date'];
      $assigned_date=FormatDateForSQL($assigned_date);          
      
      $sql_remark="SELECT remarks FROM bio_leads WHERE leadid=$leadid"; 
      $result_remark=DB_query($sql_remark,$db);
      $row=DB_fetch_array($result_remark);
      $rem1=$row['remarks'];
      $rem1=str_replace("'","-",$rem1); 
      $remark=$rem1."\r\n".date("Y-m-d").":".$remarks;
      
       $sql_append='UPDATE bio_leads SET remarks="'.$remark.'" WHERE leadid='.$leadid;   DB_query($sql_append,$db);
       $sql_remark='UPDATE bio_leadtask SET remarks="'.$remarks.'" WHERE leadid='.$leadid.' AND taskid='.$taskid;   DB_query($sql_remark,$db);
      
      if($status==1)
      {
          $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=$taskid ";      DB_query($sql1,$db);       
      }
      elseif($status==2)
      { 
          $sql2="UPDATE bio_leadtask SET taskcompletedstatus=2 WHERE leadid=$leadid";    DB_query($sql2,$db);     
          $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=$taskid ";     DB_query($sql1,$db);          
          $sql3="UPDATE bio_leads SET leadstatus=20 WHERE leadid=$leadid";         DB_query($sql3,$db);          
      }
      elseif($status==3)
      {     
          $sql2="UPDATE bio_leadtask SET taskcompletedstatus=2 WHERE leadid=$leadid";    DB_query($sql2,$db); 
          $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=$taskid ";     DB_query($sql1,$db);
       
          
    $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=18
 ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
    $emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid']; 
    
    //$assigned_date=date("Y-m-d"); 
    
    while($row_schedule1=DB_fetch_array($result_schedule1))
    {       
        $taskid=$row_schedule1['task_master_id'];
        $date_interval=$row_schedule1['actual_task_day'];
        
        $date = strtotime("+$date_interval day", strtotime($assigned_date));
        $date=date("Y-m-d", $date); 
  
        $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                 leadid,
                                                 teamid,
                                                 assigneddate,
                                                 duedate,
                                                 assigned_from,
                                                 viewstatus)
                                     VALUES('".$taskid."',
                                            '".$leadid."',
                                            '".$assignedfrm."',
                                            '".$assigned_date."',
                                            '".$date."',
                                            '".$assignedfrm."',
                                            1)";
        $result_leadTask=DB_query($sql_leadTask,$db); 
        
        $assigned_date=strtotime("+1 day", strtotime($date)); 
        $assigned_date=date("Y-m-d", $assigned_date);                                 
    }         
 }
      ?>
      <script>
      window.close();
      </script>
      <?php
  }

if(!isset($_POST['submit'])){
    
echo'<br>';    
    
echo'<div id="leadgrid">';       
echo'</div>';       
      
      
echo'<div>';  
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
 
 echo"<input type='hidden' name='leadid' id='leadid' value='$leadid'>"; 
 echo"<input type='hidden' name='taskid' id='taskid' value='$taskid'>";
 

echo"<table style='border:1px solid #F0F0F0;width:100%'><tr><td>";

      echo"<fieldset style='width:450px'><legend>Perform Action</legend>";  
      echo"<table><tr>";
                     
              echo '<td>Select Action</td>';
              echo '<td><select name="status" id="status" style="width:245px" onchange="selection()">';
              echo '<option value="0"></option>';
              echo '<option value="1">Proceed to next task</option>';
              echo '<option value="2">Delete enquiry and Cancel all tasks</option>';
              echo '<option value="3">Add new Contact date</option>';  
              echo '</select></td></tr>';
     
     echo"<tr id='newTask'><td>Next Contact Date</td>";
     echo'<td><input type="text" style="width:240px" id="date" class=date alt='.$_SESSION['DefaultDateFormat'].' name="date" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';
     echo"<tr><td>Remarks:</td>"; 
     echo"<td><textarea rows=2 cols=35 name=remarks id=remarks style=resize:none; value='$remarks' onchange=updateDetails('$cid','$remarks')>$remarks</textarea></td></tr>";     
           
    echo"<tr><td></td><td align=right><input type='submit' name='submit' id='submit' value='Submit' onclick='if(validation()==1)return false;;'></td>";  
    echo"</tr></table>";
    echo"</fieldset>";
echo"</td></tr>";
if($mail_msg!=''){
echo"<tr>"; 
echo"</td>";
echo"<td align=center><a href='#' id='".$leadid."' onclick='sendMail(this.id)'>Send Mail</a></td></tr>";   
echo"</td>";
echo"</tr>"; 
}

echo"<tr><td>";
echo"<fieldset style='width:800px'><legend>Edit Scheduled Date</legend>"; 
echo"<table style='border:1px solid #F0F0F0;width:auto'>";
echo"<table style='border:1px solid #F0F0F0;width:auto'>";
//echo"<table style='width:100%'> "; 
echo"<tr><th>Sl.no</th>
         <th>Task</th>
         <th>Assigned Date</th>
         <th>Due Date</th>
         <th>Status</th>
     </tr>"; 
  
  $sql="SELECT bio_leadtask.leadid,
               bio_task.task,
               bio_leadtask.taskid, 
               bio_leadtask.assigneddate,
               bio_leadtask.duedate,
               bio_leadtask.taskcompletedstatus
          FROM bio_leadtask,bio_task 
         WHERE bio_leadtask.leadid=$leadid
           AND bio_task.taskid=bio_leadtask.taskid
           AND bio_leadtask.taskcompletedstatus!=2
           AND bio_leadtask.taskcompletedstatus!=3
           ORDER BY bio_leadtask.duedate ASC";
  $result=DB_query($sql,$db);
  $no=1;
  $k=0;           
     
  while($row=DB_fetch_array($result)) 
  {    
     $taskid=$row['taskid'];
     $taskcompletedstatus=$row['taskcompletedstatus'];
       
     $assignDate=ConvertSQLDate($row['assigneddate']);
     $duedate=ConvertSQLDate($row['duedate']);
     
          if ($taskcompletedstatus==1)
          {
            echo '<tr class="EvenTableRows">';
             $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
             $k=1;     
          }     
          if($taskid==18 || $taskcompletedstatus==1)
          { 
              echo"<td width=15px>$no</td>
               <td width=300px>".$row['task']."</td>        
               <td width=200px><input type='text' name='assignd' id='assignd' readonly value='$assignDate' onchange=updateDate(this.value,'$taskid','$leadid',1)></td>   
               <td width=200px><input type='text' name='dued' id='dued' value='".$duedate."' readonly onchange=updateDate(this.value,'$taskid','$leadid',2)></td>"; 
               
              if($taskcompletedstatus==0){  echo"<td>Pending</td>";    }
          elseif($taskcompletedstatus==1){  echo"<td>Done</td>";       }
              
          }  
          else
          { 
          echo"<td width=15px>$no</td>
               <td width=300px>".$row['task']."</td>        
               <td width=200px><input type='text' name='assignd' readonly id='assignd'  value='$assignDate' onchange=updateDate(this.value,'$taskid','$leadid',1)></td>   
               <td width=200px><input type='text' name='dued' id='dued' value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>"; 
               
              if($taskcompletedstatus==0){  echo"<td>Pending</td>";    }
          elseif($taskcompletedstatus==1){  echo"<td>Done</td>";       }
         // elseif($taskcompletedstatus==2){  echo"<td>Cancelled</td>"; } 
          }
         //echo"<td><input type='submit' name='date' value='Edit Date'></td>";
          
          echo"</tr>"; 
    $no++;       
   }         
        
      echo"</table>";     
echo"</fieldset>"; 
      
echo"</td></tr></table>";  
 
echo"</form>";
echo'</div>';       
}

?>

<script type="text/javascript"> 

function validation()
{
    var f=0;
    var p=0;
    var status=document.getElementById('status').value;
    if(status==0)
    {
    if(f==0){f=common_error('status','Please select an Action');  if(f==1){return f; }  } 
    }
    if(f==0){f=common_error('remarks','Please enter any remarks');  if(f==1){return f; }  }
}

function selection()
{
    var status=document.getElementById('status').value;         
    if(status==3){      
    $('#newTask').show();      
    }else{   
     $('#newTask').hide();
    }    
}

  function sendMail(str){//alert(str);

controlWindow=window.open("bio_sendmail.php?leadid="+str,"sendmail","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1300,height=650");


} 
function updateDate(str1,str2,str3,str4){   
//alert(str1);   alert(str2);      alert(str3);     alert(str4);
if (str1=="")
  {
  document.getElementById("leadgrid").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("leadgrid").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_updateDate.php?date=" + str1 + "&tid=" + str2 + "&leadid=" + str3 + "&choosedate=" + str4 ,true);
xmlhttp.send(); 
} 

</script>