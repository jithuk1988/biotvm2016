<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Domestic Task View');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Domestic Task View</font></center>';

    $office=$_SESSION['UserStockLocation']; 

  if($_GET['flag']!='')
  {
      $crdt=date("Y-m-d H:i:s");
      $taskid=$_GET['flag'];
      $leadid=$_GET['leadid'];
      if($taskid==20){
          $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='".$crdt."' WHERE leadid=$leadid AND taskid=$taskid AND taskcompletedstatus!=2";    DB_query($sql_flag,$db);
      }
      if($taskid==21){
          $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='".$crdt."' WHERE leadid=$leadid AND taskid=$taskid AND taskcompletedstatus!=2";    DB_query($sql_flag,$db); 
          $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus=1 WHERE leadid=$leadid AND taskid=20 AND taskcompletedstatus!=2";    DB_query($sql_flag,$db);
      }
  }  
    
if (isset($_POST['submit'])) {
    
    if(!isset($_POST['LeadID'])){
      prnMsg('Select lead details from the grid and then assign the team','warn');         
    }
    else{
    $lead_ID=$_POST['LeadID'];
    $task_ID=$_POST['task'];
    $team_ID=$_POST['team'];
    $startdate=FormatDateForSQL($_POST['StartDate']);
    $enddate=FormatDateForSQL($_POST['EndDate']);
    
    $emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid']; 

    $sql_st="UPDATE bio_leadtask SET viewstatus=0 
                WHERE leadid=".$lead_ID;
    
    $result_st=DB_query($sql_st,$db);
    
    $scheduleid=$_POST['Schedule'];
    
    $sql="INSERT INTO bio_leadtask(taskid,
                                   leadid,
                                   teamid,
                                   assigneddate,
                                   duedate,
                                   assigned_from,
                                   viewstatus) 
                            VALUES ('".$task_ID."',                                        
                                    '".$lead_ID."',
                                    '".$team_ID."',
                                    '".$startdate."',
                                    '".$enddate."',
                                    '".$assignedfrm."',
                                    1)";
    $result=DB_query($sql,$db);
    
    $sql22="INSERT INTO bio_leadschedule(leadid,
                                       scheduleid)
          VALUES('".$lead_ID."',
                 '".$scheduleid."')";
    $result22=DB_query($sql22,$db);                                
       
    }

    unset($_POST['team']);
    unset($_POST['StartDate']);
    unset($_POST['EndDate']);
}

 
echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style="width:70%"><tr><td>';
echo"<div id=panel>";
echo '<table><tr>';

//========================================== Left Panel Begins

echo'<td>';
echo"<div id=msg>";
echo"<div id=cus_details>";
echo"<fieldset style='width:600px;height:250px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";

if($_GET['lead']!=''){
    
    $leadid=$_GET['lead'];    
       $sql="SELECT bio_leads.leadid, 
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_district.district,
                    bio_leadteams.teamname,
                    bio_leadsources.sourcename,
                    bio_emp.empname,
                    bio_cust.cust_id,
                    bio_leads.enqtypeid,
                    bio_leads.remarks,
                    SUM( bio_advance.amount) AS amount 
              FROM  bio_leads,bio_cust,bio_leadteams,bio_leadsources,bio_district,bio_emp,www_users,bio_advance
              WHERE bio_leads.cust_id=bio_cust.cust_id
                AND bio_leadteams.teamid=bio_leads.teamid
                AND bio_district.did=bio_cust.district
                AND bio_district.stateid=bio_cust.state
                AND bio_district.cid=bio_cust.nationality
                AND bio_leadsources.id=bio_leads.sourceid
                AND bio_leads.leadid=bio_advance.leadid
                AND bio_leads.created_by=www_users.userid
                AND www_users.empid=bio_emp.empid
                AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result); 
 
 $cid=$myrow['cust_id'];
 $cname=$myrow['custname']; 
 $enqid=$myrow['enqtypeid'];  
 if($myrow['custmob']!=''){
     $cph=$myrow['custmob']; 
 }else{
     $cph=$myrow['custphone']; 
 }
 $email=$myrow['custmail'];  
 $place=$myrow['area1'];
 $district=$myrow['district'];
 $team=$myrow['teamname']; 
 $sourcename=$myrow['sourcename']; 
 $amount=$myrow['amount']; 
 $createdby=$myrow['empname']; 
 $remarks=$myrow['remarks'];  

}    
echo"<table width=60%>"; 
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname' onchange=updateDetails('$cid','$cname')>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Contact:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place:</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place' onchange=updateDetails('$cid','$place')>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district' onchange=updateDetails('$cid','$district')>$district</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source Team:</td>";
echo"<td><input type='hidden' name='team' id='team' value='$team'>$team</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source:</td>";
echo"<td><input type='hidden' name='source' id='source' value='$sourcename'>$sourcename</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Remarks:</td>"; 
echo"<td><textarea rows=2 cols=30 name=remarks id=remarks style=resize:none; value='$remarks' onchange=updateDetails('$cid','$remarks')>$remarks</textarea></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Total Amount Paid:</td>";
echo"<td><input type='hidden' name='amount' id='amount' value='$amount'>$amount</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Created By:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$createdby'>$createdby</td></tr>";  
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
echo"<input type='hidden' name='custid' id='custid' value='$cid'>";
echo"<input type='hidden' name='enqid' id='enqid' value='$enqid'>";

echo'</div>';
echo'</div>';
echo"</td>";
//========================================== Left Panel Ends 

echo"</div>";
echo"</td></tr></table>";

echo"<div id=pendingTask>";
echo"</div>";

if($_GET['lead']!='')
{ 
echo"<fieldset style='width:800px'><legend>Pending Tasks</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
//echo"<table style='width:100%'> "; 
echo"<tr><th>Task</th>
         <th>Assigned Date</th>
         <th>Due Date</th>
         <th>Status</th> 
      </tr>";  
  
  $sql="SELECT bio_cust.cust_id,
               bio_leads.enqtypeid,
               bio_leadtask.leadid,
               bio_task.task,
               bio_leadtask.taskid, 
               bio_leadtask.assigneddate,
               bio_leadtask.duedate,
               bio_leadtask.taskcompletedstatus
          FROM bio_leads,bio_leadtask,bio_cust,bio_task 
         WHERE bio_leadtask.leadid=$leadid
           AND bio_leads.leadid=bio_leadtask.leadid
           AND bio_leads.cust_id=bio_cust.cust_id
           AND bio_task.taskid=bio_leadtask.taskid
           AND bio_leadtask.taskcompletedstatus=0
           ORDER BY bio_leadtask.duedate ASC           
           ";                                                     //AND bio_leadtask.duedate > '$date'
  $result=DB_query($sql,$db);
  $no=1;
  $k=0;           
     
  while($row=DB_fetch_array($result)) 
  {
     $custid=$row['cust_id'];
     $enqid=$row['enqtypeid']; 
     $taskid=$row['taskid']; 
     $taskcompletedstatus=$row['taskcompletedstatus'];
     $assignDate=ConvertSQLDate($row['assigneddate']);
     $duedate=ConvertSQLDate($row['duedate']);

          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
             $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
             $k=1;     
          }
    
              //echo"<td>$taskid</td>";
              if($taskcompletedstatus!=0){
              echo"<td>".$row['task']."</td>";
              }else{
              echo"<td><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$custid,$enqid)'>".$row['task']."</a></td>";    
              }
              echo"<td><input type='text' name='assignd' id='assignd' value='$assignDate' onchange=updateDate(this.value,'$taskid','$leadid',1)></td>   
                   <td><input type='text' name='dued' id='dued' value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>";     
                   
              if($taskcompletedstatus==0){  echo"<td>Pending</td>";    }
          elseif($taskcompletedstatus==1){  echo"<td>Done</td>";       }
          //elseif($taskcompletedstatus==2){  echo"<td>Cancelled</td>";  }      
    
     $no++;
  }
          
echo"</table>";
echo"</fieldset>"; 
echo'<div class="centre">';   
echo "<input type=\"button\" value=\"Refresh this page\" onClick='window.location=\"bio_domTaskview.php\";'>";
echo'</div>';
}
//echo"</div>"; 
echo"</div>";
echo'</form>';

//========================================== Grid - from leads table
if(!$_GET['lead'])
{ 

$empid=$_SESSION['empid'];
$sql9="SELECT teamid FROM bio_teammembers WHERE empid=$empid";
$result9=DB_query($sql9,$db);
$row9=DB_fetch_array($result9);
$teamid=$row9['teamid'];     

$date=date("Y-m-d");

$sql="SELECT bio_cust.cust_id,
             bio_leads.enqtypeid, 
             bio_leadtask.leadid,
             bio_cust.custname,
             bio_cust.custmob,
             bio_leadtask.assigneddate,
             bio_leadtask.duedate,
             bio_task.taskid,
             bio_task.task,
             bio_leadteams.teamname,
             bio_district.district,
             bio_status.biostatus          
        FROM bio_leads,bio_leadtask,bio_cust,bio_task,bio_leadteams,bio_status,bio_district
       WHERE bio_leads.leadid=bio_leadtask.leadid
         AND bio_leads.cust_id=bio_cust.cust_id 
         AND bio_leads.enqtypeid=1
         AND bio_cust.cust_id=bio_leads.cust_id
         AND bio_task.taskid=bio_leadtask.taskid
         AND bio_leadtask.teamid=$teamid
         AND bio_district.did=bio_cust.district
         AND bio_district.stateid=bio_cust.state
         AND bio_district.cid=bio_cust.nationality
         AND bio_leadteams.teamid=bio_leadtask.teamid
         AND bio_status.statusid=bio_leads.leadstatus
         AND bio_leadtask.taskcompletedstatus=0                     
         ";  
         
 if(isset($_POST['filter']))
 {
    if (isset($_POST['Actiondate']))  {
    if ($_POST['Actiondate']!='')   {
    
        if ($_POST['Actiondate']==1) 
        {    
              $sql.=" AND bio_leadtask.duedate = '".$date."'";   
        } 
        if ($_POST['Actiondate']==2) 
        {        
              $date=explode("-",$date);
              $startdate2=$date[1]."/".$date[2]."/".$date[0];
              $Tommorrow1 = strtotime($startdate2 . " +1 day");
              $Tommorrow=date("d/m/Y",$Tommorrow1);
              $Tommorrow2=FormatDateForSQL($Tommorrow);    
                    
              $sql.=" AND bio_leadtask.duedate = '".$Tommorrow2."'";
        }
        if ($_POST['Actiondate']==3) 
        {   
              $sql.=" AND bio_leadtask.duedate < '".$date."'";      
        }   
        if ($_POST['Actiondate']==4) 
        {   
              $sql.=" AND bio_leadtask.duedate !=''";      
        }   
    }       
    }
     
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql .=" AND bio_cust.custname LIKE '%".$_POST['byname']."%'";
    } 
    
    if (isset($_POST['phone']))  {        
    if ($_POST['phone']!='')   
    $sql .=" AND bio_cust.custmob LIKE '%".$_POST['phone']."%'";
    } 
    
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql.=" AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }
        
 }
 else
 {
      $sql.=" AND bio_leadtask.duedate <= '$date'";
      $_POST['Actiondate']=1;
      $actiondatedesc="Today"; 
 }
$sql.=" ORDER BY bio_leadtask.leadid DESC,bio_leadtask.taskid ASC"; 
//echo $sql;       
$result=DB_query($sql,$db);

echo'<div id="leadgrid">';
echo"<fieldset style='width:800px'><legend>Domestic Task List</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>Action Date</td>      
         <td>Name</td>
         <td>Phone</td>
         <td>District</td>
     </tr>"; 
echo"<tr>";

echo '<td><select name="Actiondate" id="actiondate" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="1">Today</option>';
echo '<option value="2">Tommorrow</option>';
echo '<option value="3">Overdue</option>';
echo '<option value="4">ALL</option>'; 
echo '<option selected='.$_POST['Actiondate'].'>'.$actiondatedesc.'</option>';                                                            
echo '</select></td>';    

echo"<td class='byname'><input type='text' name='byname' id='byname'></td>";
echo"<td><input type='text' name='phone' id='phone'></td>"; 
echo"<td><input type='text' name='byplace' id='byplace'></td>";   

echo"<td><input type='submit' name='filter' id='filter' value='search'></td>";
echo"</tr>";
echo"</table>";


echo"<table style='width:800px'> ";
                                                                
echo"<tr>
<th width='119px'>Name</th>
<th width='91px'>Phone</th>
<th width='135px'>District</th> 
<th width='126px'>Task</th>
<th width='84px'>Due Date</th> 
<th width='131px'>Lead Status</th>  
<th width='58px'>Select</th>  
</tr>"; 

echo"</table>";
$office=$_SESSION['UserStockLocation'];   
//---------------------------------------------------------------------------------------------------------------------------------------------------------

echo "<div style='height:200px; overflow:scroll;'>";
echo"<table style='width:785px'> ";  
 
//echo '<tbody>';
//echo '<tr>';                                       
                                      
while($myrow=DB_fetch_array($result))
{
    $no++;    
    $leadid=$myrow['leadid'];
    $custid=$myrow['cust_id'];
    $enqid=$myrow['enqtypeid']; 
    $taskid=$myrow['taskid'];
    $duedate=$myrow['duedate'];
    
    if(date("Y-m-d") > $duedate AND $duedate!='0000-00-00 00:00:00')
    {   
        echo '<tr bgcolor=#FF8080>';  
        $datedue=ConvertSQLDate($myrow['duedate']);
    }else{   
        echo '<tr class="OddTableRows">';   
        $datedue='';
    }
    
    echo"<td width='150px'>".$myrow['custname']."</td>";
    echo"<td width='100px'>".$myrow['custmob']."</td>";
    echo"<td width='150px'>".$myrow['district']."</td>";
    echo"<td width='200px'>".$myrow['task']."</td>"; 
    echo"<td width='100px'>".ConvertSQLDate($myrow['duedate'])."</td>"; 
    echo"<td width='100px'>".$myrow['biostatus']."</td>";
    if($taskid==21){
    echo"<td width='50px'><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$custid,$enqid)'>" . _('Select') . "</a></td>";   
    }else{
    echo"<td width='50px'><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid);'>" . _('Select') . "</a></td>";       
    }
    
/*  
    printf("<td cellpading=2 width='150px'>%s</td>
            <td width='100px'>%s</td>
            <td width='150px'>%s</td> 
            <td width='200px'>%s</td>
            <td width='100px'>%s</td> 
            <td width='100px'>%s</td>
            <td width='50px'><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid)'>" . _('Select') . "</a></td>",             
            $myrow['custname'],
            $myrow['custmob'],  
            $myrow['district'],
            $myrow['task'],   
            ConvertSQLDate($myrow['duedate']),
            $myrow['biostatus']); 
*/
}

echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';
}
echo"</td></tr></table>"; 
echo"</div>";
?>

<script type="text/javascript">

$(document).ready(function(){   
    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
//      $(".skip").hide();   
    
var xx = new Array("<?php echo implode(",",$_SESSION['filters']);?>");

var mySplitResult = xx[0].split(",");  

for(var i=0; i<mySplitResult.length;i++)        {

var filter='.'+mySplitResult[i]
//alert(filter);    
$(filter).hide();   
}    
});

function log_in()
{
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('custname','Please select a lead');  if(f==1){return f; }  }  
if(f==0){f=common_error('team','Please select a team');  if(f==1){return f; }  } 
if(f==0){f=common_error('startdate','Please enter start date');  if(f==1){return f; }  }  
if(f==0){f=common_error('enddate','Please enter end date');  if(f==1){return f; }  } 
}

function selectTask(str1,str2,str3,str4){
/*   
//var str1=document.getElementById('custplace').value;                       
//var str2=document.getElementById('custdist').value;       
//var str3=document.getElementById('remarks').value;
//var str4=document.getElementById('leadid').value; 
//var str5=document.getElementById('custname').value;    
 //alert(str4);       alert(str3);
 //alert(str1);       alert(str2);      
if (str1=="")
  {
  document.getElementById("cus_details").innerHTML="";
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
    document.getElementById("cus_details").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  }  */
      location.href="?lead=" + str1; 
        
      if(str2==18){ 
      controlWindow=window.open("bio_recreateSchedule.php?leadid=" + str1 + "&tid=" + str2,"RecreateSchedule","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=400");
      }
      if(str2==20){ 
      controlWindow=window.open("bio_multiplePayment.php?lead=" + str1 + "&tid=" + str2,"Payment","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=850,height=400");
      }
      if(str2==1){
      controlWindow=window.open("bio_proposal.php?lead=" + str1 + "&taskid=" + str2,"so_cp","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1300,height=460");    
      }
      if(str2==21){
//      var str3=document.getElementById('custid').value;     
//      var str4=document.getElementById('enqid').value;           //alert(str3);alert(str4);
      controlWindow=window.open("LeadsCustomers.php?leadid=" + str1 + "&tid=" + str2 + "&custid=" + str3 + "&enquiryid=" + str4,"CreateCustomer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=900");    
      }
      if(str2==5){
      controlWindow=window.open("bio_createOrderfromleads.php?NewOrder=Yes?lead=" + str1 + "&taskid=" + str2,"so_cp","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1500,height=600");    
      }   //*/
//xmlhttp.open("GET","bio_domListTask.php?leadid=" + str1 + "&tid=" + str2,true);
//xmlhttp.send(); 
}

function listTask(str1){
//alert(str1);   alert(str2);
window.location="bio_domListTask.php?leadid=" + str1;  
}

function updateDate(str1,str2,str3,str4){   
//alert(str1);   alert(str2);      alert(str3);     alert(str4);
if (str1=="")
  {
  document.getElementById("pendingTask").innerHTML="";
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
    document.getElementById("pendingTask").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_updateDate.php?date=" + str1 + "&tid=" + str2 + "&leadid=" + str3 + "&choosedate=" + str4 ,true);
xmlhttp.send(); 
} 

</script>