<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Domestic Task View');  
include('includes/header.inc');
include('includes/sidemenu.php');


echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Domestic Task View</font></center>';

    $office=$_SESSION['UserStockLocation']; 

if($_GET['lead']!='')
{
 $leadid=$_GET['lead'];    
    
    $sql_e="SELECT ticketno,email_message from bio_incidents where leadid=".$leadid;
              $result=DB_query($sql_e,$db);

 $myrow6=DB_fetch_array($result); 
  $ticketno=$myrow6['ticketno'];
  $email_message=$myrow6['email_message']; 
}
/*else
{

  
        
    
}    */


  if($_GET['flag']!='')
  {
      $crdt=date("Y-m-d H:i:s");
      $taskid=$_GET['flag'];
      $lead=$_GET['lead'];
      if($taskid==20){
          $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='".$crdt."' WHERE leadid=$lead AND taskid=$taskid AND taskcompletedstatus!=2 AND taskcompletedstatus!=3";                  
          DB_query($sql_flag,$db);
      }
      if($taskid==21){
          $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='".$crdt."' WHERE leadid=$lead AND taskid=$taskid AND taskcompletedstatus!=2 AND taskcompletedstatus!=3";             
          DB_query($sql_flag,$db); 
          //$sql_flag="UPDATE bio_leadtask SET taskcompletedstatus=1 WHERE leadid=$leadid AND taskid=20 AND taskcompletedstatus!=2";    DB_query($sql_flag,$db);
      }
  }  
  
/*  if(isset($_POST['statusButton']))
  {     
      if($_POST['statusChange']==1)
      {
            $sql2="UPDATE bio_leads SET leadstatus=25 WHERE leadid=".$_POST['LeadID'];     DB_query($sql2,$db);
      }
  }  */

 
echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style="width:70%"><tr><td>';
echo"<div id=panel>";
echo '<table><tr>';

//========================================== Left Panel Begins

echo'<td>';
echo"<div id=msg>";
echo"<div id=cus_details>";
echo"<fieldset style='width:700px;height:auto'>"; 
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
                  
                   
                  
                    bio_cust.cust_id,
                    bio_leads.enqtypeid,
                    bio_leads.remarks,
                    SUM( bio_advance.amount) AS amount 
              FROM  bio_leads,bio_cust,bio_district,bio_advance
              WHERE bio_leads.cust_id=bio_cust.cust_id
                
                AND bio_district.did=bio_cust.district
                AND bio_district.stateid=bio_cust.state
                AND bio_district.cid=bio_cust.nationality
                
                AND bio_leads.leadid=bio_advance.leadid
                               
                AND bio_leads.leadid=".$leadid;  /*bio_leadteams.teamname,*/ /*bio_leadsources.sourcename,*///AND bio_leadteams.teamid=bio_leads.teamid AND bio_leadsources.id=bio_leads.sourceid                 //bio_leadteams,bio_leadsources
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
 
 // Check whether the leadsource is under Staff,Dealer,Network group OR Customer Reference
 $sql_checkleadsource="SELECT * FROM bio_leadsource_dealstaff WHERE leadid=".$leadid;
 $result_checkleadsource=DB_query($sql_checkleadsource,$db);
 $Row_leadsource=DB_fetch_array($result_checkleadsource);
 // DB_num_rows($result_checkleadsource);
 if(DB_num_rows($result_checkleadsource)==0){  // if Leadsource is not under Staff,Dealer,Network group OR Customer Reference
    // echo "first";
     
     $sql1=DB_query("SELECT bio_leadteams.teamname,bio_leadsources.sourcename FROM bio_leadteams,bio_leadsources,bio_leads
     WHERE bio_leadteams.teamid=bio_leads.teamid AND bio_leadsources.id=bio_leads.sourceid
     AND bio_leads.leadid=".$leadid."  ",$db);
     $get_team=DB_fetch_array($sql1);
     
     $team=$get_team['teamname']; 
     $sourcename=$get_team['sourcename']; 
 }
 else{  //// if Leadsource is under Staff,Dealer,Network group OR Customer Reference
     //echo "second";
    echo $Row_leadsource['sourcetype'];
     
     if($Row_leadsource['sourcetype']==4) //Customer Reference
     {//echo "second1";
         
         $Get_cust=DB_query("SELECT name FROM debtorsmaster WHERE debtorno='".$Row_leadsource['sourceid']."'",$db);
         $Row_cust=DB_fetch_array($Get_cust);
         $team="Customer Reference";
         $sourcename=$Row_cust['name'];  
     }
     elseif($Row_leadsource['sourcetype']==14) //Biotech Staff
     {//echo "second2";
              
         $Get_staff=DB_query("SELECT empname FROM bio_emp WHERE empid='".$Row_leadsource['sourceid']."'",$db);
         $Row_staff=DB_fetch_array($Get_staff);
         $team="Biotech Staff";
         $sourcename=$Row_staff['empname'];  
     }
     elseif($Row_leadsource['sourcetype']==15) //Dealer
     {
              echo $Row_leadsource['sourceid'];
         $Get_deal=DB_query("SELECT name FROM debtorsmaster WHERE debtorno='".$Row_leadsource['sourceid']."'",$db);
         $Row_deal=DB_fetch_array($Get_deal);
        echo $team="Dealer";
         $sourcename=$Row_deal['name'];  
     }
       elseif($Row_leadsource['sourcetype']==16) //Network Group
     {//echo "second4";
              
         $Get_nwcust=DB_query("SELECT cust_name FROM bio_network_cust WHERE id='".$Row_leadsource['sourceid']."'",$db);
         $Row_nwcust=DB_fetch_array($Get_nwcust);
         $team="Network Group";
         $sourcename=$Row_nwcust['cust_name'];  
     }
     
   
 }
 /*$team=$myrow['teamname']; 
 $sourcename=$myrow['sourcename']; */
 
 
 $amount=$myrow['amount']; 
// $createdby=$myrow['empname']; 
 $remarks=$myrow['remarks'];  
}    
echo"<table width=70%>"; 
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Contact:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email&nbsp;";
if ($ticketno!=0 or $ticketno!='')
{
 $_SESSION['email']=$email;
echo "<a style='cursor:pointer;' id='$ticketno' onclick='viewMessage()'>View Mail</a><td></tr>";
}
else
{
    
    echo "</td></tr>";
}
echo"<tr></tr>";

echo"<tr><td>Customer Place:</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district'>$district</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source Team:</td>";
echo"<td><input type='hidden' name='team' id='team' value='$team'>$team</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source:</td>";
echo"<td><input type='hidden' name='source' id='source' value='$sourcename'>$sourcename</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Total Amount Paid:</td>";
echo"<td><input type='hidden' name='amount' id='amount' value='$amount'>$amount</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Remarks:</td>"; 
if($_GET['lead']!=''){
echo"<td><textarea rows=5 cols=55 name=remarks id='remarks' value='$remarks' onchange=updateDetails('$leadid',this.value)>$remarks</textarea></td></tr>";
}
echo"<tr></tr>";    /*
echo"<tr><td>Created By:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$createdby'>$createdby</td></tr>";  
echo"<tr></tr>";    */
if($_GET['lead']!=''){ 
    
    
    $rst=DB_query("SELECT bio_installation_status.orderno from bio_installation_status,salesorders where salesorders.orderno=bio_installation_status.orderno and salesorders.leadid=$leadid",$db);
    $ff=DB_num_rows($rst);
    $view=$ff[0];
    $result_instdate=DB_query("SELECT taskcompletedstatus FROM bio_leadtask WHERE leadid=$leadid AND taskid=5 AND viewstatus=1 AND taskcompletedstatus!=2",$db);
$row_instdate=DB_fetch_array($result_instdate);
$result_instorder=DB_query("SELECT bio_installation_status.orderno FROM bio_installation_status,salesorders 
                            WHERE bio_installation_status.orderno=salesorders.orderno
                              AND salesorders.leadid=$leadid
                              ",$db);
$row_instorder=DB_fetch_array($result_instorder);  
if($row_instorder[0]!=null){
    $ordno=$row_instorder[0];
}else{
    $ordno=0; 
}
if($row_instdate[0]==1 && $ff!=1){
echo"<tr><td></td>"; 
echo"<td><a style='cursor:pointer;' id='$leadid' onclick='instdate(this.id,$ordno)'>Installation Date</a></td></tr>";  
echo"<tr></tr>";
}
}
if($_GET['lead']!=''){     
echo"<tr><td>Change Status</td>"; 
echo '<td><select name="statusChange" id="statusChange" style="width:215px">';
echo '<option value=0></option>'; 
echo '<option value=25>Sale Order Recieved</option>';
echo '<option value=43>Domestic Plant Supplied</option>';  
echo '</select></td>';
//echo '<td><input type=button name=statusButton value="Change Status" onclick=status("$leadid")></td>';
echo "<td>
          <input type='button' id=\"".$leadid."\" value='Change Status' onclick='status(this.id);' >
          
       </td>";
//echo '<td><input type="button" id=\"".$leadid."\" value="Change Status" onclick="status(this.id);"></td>';    
echo '</tr>';
echo"<tr><td>Drop Lead</td>";
echo"<td><a style='cursor:pointer;' id='$leadid' onclick='dropLead(this.id)'>Drop lead</a></td></tr>";

}
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
           AND bio_leadtask.taskcompletedstatus!=2 AND bio_leadtask.taskcompletedstatus!=3 
           ORDER BY bio_leadtask.duedate ASC           
           ";                                                     
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
          
       if($taskcompletedstatus==1 && $taskid==20){
            echo"<td><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$custid,$enqid)'>".$row['task']."</a></td>"; 
       }elseif($taskcompletedstatus==1)  {   
            echo"<td>".$row['task']."</td>";    
       }elseif($taskcompletedstatus==0)  {  
            echo"<td><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$custid,$enqid)'>".$row['task']."</a></td>";
       }
            echo"<td><input type='text' name='assignd' readonly id='assignd' value='$assignDate' onchange=updateDate(this.value,'$taskid','$leadid',1)></td>";
       if($taskid==18){
            echo "<td><input type='text' name='dued' id='dued' readonly value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>";
       }else{
            echo"<td><input type='text' name='dued' id='dued' value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>";    }
       if($taskcompletedstatus==1){
            echo"<td>Done</td>";
       }elseif($taskcompletedstatus==0){
            echo"<td>Pending</td>";    
       }
/*          
              if($taskcompletedstatus==1 && $taskid==20){ 
              echo"<td><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$custid,$enqid)'>".$row['task']."</a></td>";   
              echo"<td><input type='text' name='assignd' readonly id='assignd' value='$assignDate' onchange=updateDate(this.value,'$taskid','$leadid',1)></td>";              
              echo"<td><input type='text' name='dued' id='dued' value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>";                        
              echo"<td>Done</td>";       
          
              } elseif($taskcompletedstatus==0){
              echo"<td><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$custid,$enqid)'>".$row['task']."</a></td>";    
              echo"<td><input type='text' name='assignd' readonly id='assignd' value='$assignDate' onchange=updateDate(this.value,'$taskid','$leadid',1)></td>";   
              if($taskid==18){    
              echo "<td><input type='text' name='dued' id='dued' readonly value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>";          
              }else{
              echo "<td><input type='text' name='dued' id='dued' value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>";          
              }
              echo"<td>Pending</td>";     
              }  */  
              
        echo"</tr>";        
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

/*$sql="SELECT bio_cust.cust_id,
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
             bio_leadtask.tid,
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
         ";  */
         
$sql="SELECT bio_cust.cust_id,
             bio_leads.enqtypeid, 
             bio_leadtask.leadid,
             bio_cust.custname,
             bio_leads.grade,
             bio_cust.contactperson,
             bio_cust.custphone,                  
             bio_cust.custmob,
             bio_leadtask.assigneddate,
             bio_leadtask.duedate,
             bio_task.taskid,
             bio_task.task,
            bio_district.district,
             bio_status.biostatus,
             bio_leadtask.tid,
             bio_leadteams.teamname,         
 bio_emailstatus.emailstatus
        FROM bio_leads
LEFT JOIN bio_leadtask on bio_leadtask.leadid=bio_leads.leadid
LEFT JOIN bio_cust on bio_cust.cust_id=bio_leads.cust_id
LEFT JOIN bio_task on bio_leadtask.taskid=bio_task.taskid
LEFT JOIN bio_district ON bio_district.cid=bio_cust.nationality AND bio_district.stateid=bio_cust.state
AND bio_district.did=bio_cust.district
LEFT JOIN bio_leadschedule on bio_leadtask.leadid=bio_leadschedule.leadid
LEFT JOIN bio_status on bio_status.statusid=bio_leads.leadstatus
LEFT JOIN bio_leadteams on bio_leadteams.teamid=bio_leadtask.assigned_from
LEFT JOIN bio_incidents on bio_incidents.leadid=bio_leads.leadid
LEFT JOIN  bio_emailstatus on bio_emailstatus.id= bio_incidents.emailstatus
where bio_leads.enqtypeid=1
AND bio_leadtask.taskcompletedstatus=0
AND bio_leadtask.teamid=$teamid
and bio_leads.leadstatus not in (20)
and bio_leadtask.leadid not in (select leadid from bio_leadtask where taskid=5 and taskcompletedstatus=1 and teamid=$teamid)";


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
    
    if (isset($_POST['task']))  {   
    if ($_POST['task']!='')   {
        
    if ($_POST['task']!=0) {      
    $sql .=" AND bio_leadtask.taskid='".$_POST['task']."'";
    }else{
    $sql .=" AND bio_leadtask.taskid IN (18,20,21,1,5,24,25,26)";    
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
     if (isset($_POST['bycode'])) {
    if ($_POST['bycode']!='') 
    $sql.=" AND bio_cust.debtorno LIKE '%".$_POST['bycode']."%'"; 
    }   
 }
 else
 {
      $sql.=" AND bio_leadtask.duedate <= '$date' AND bio_leadtask.taskid=18";
      $_POST['Actiondate']=1;
      $actiondatedesc="Pending"; 
      $_POST['task']=18;
      $task="Contact Client";
 }
 if($_SESSION['UserID']=='bdm_national' || $_SESSION['UserID']=='bde_national1' ||$_SESSION['UserID']=='bdm_international' ||$_SESSION['UserID']=='bde_international1') 
{
 $sql.=" ORDER BY bio_leadtask.leadid DESC,bio_leadtask.taskid ASC limit 10000"; 
 }
 else
 {
  $sql.=" ORDER BY bio_leadtask.leadid DESC,bio_leadtask.taskid ASC limit 200"; 
 }
//echo $sql;       
$result=DB_query($sql,$db);

echo'<div id="leadgrid">';
echo"<fieldset style='width:800px'><legend>Domestic Task List</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>Action Date</td>    
<td>Code</td>  
         <td>Name</td>
         <td>Phone</td>
         <td>District</td>
         <td>Task</td>
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
echo"<td class='bycode'><input type='text' name='bycode' id='bycode'></td>";
echo"<td class='byname'><input type='text' name='byname' id='byname'></td>";

echo"<td><input type='text' name='phone' id='phone'></td>"; 
echo"<td><input type='text' name='byplace' id='byplace'></td>";   

echo '<td><select name="task" id="task" style="width:150px">';
echo '<option></option>';
echo '<option value=0>ALL</option>';
echo '<option value="18">Contact Client</option>';
echo '<option value="20">Collect Payment</option>';
echo '<option value="21">Create Customer</option>';
echo '<option value="1">Select Plant</option>'; 
echo '<option value="5">Sale Order</option>';
echo '<option value="24">Document Collection</option>'; 
echo '<option value="25">Generate Invioce</option>';
echo '<option value="28">Full Payment Collection</option>';
echo '<option selected='.$_POST['task'].'>'.$task.'</option>';                                                            
echo '</select></td>';

echo"<td><input type='submit' name='filter' id='filter' value='search'></td>";
echo"</tr>";
echo"</table>";

echo"<table style='width:100%'> ";
                                                                
echo"<tr>
<th width='101px'>Slno</th>
<th width='101px'>Name</th>
<th width='21px'>Grade</th>
<th width='73px'>Phone</th>
<th width='110px'>District</th> 
<th width='119px'>Task</th>
<th width='73px'>Due Date</th> 
<th width='68px'>Lead Status</th>";  
if($_SESSION['UserID']=='bdm_national' || $_SESSION['UserID']=='bde_national1' ||$_SESSION['UserID']=='bdm_international' ||$_SESSION['UserID']=='bde_international1') 
{
echo "<th width='68px'>Email Status</th>";  

}
echo "<th width='42px'>Select</th>  
</tr>"; 

echo"</table>";
$office=$_SESSION['UserStockLocation'];   
//---------------------------------------------------------------------------------------------------------------------------------------------------------

echo "<div style='height:200px; overflow:scroll;'>";
echo"<table style='width:100%'> ";                                      
$no=0;                                
while($myrow=DB_fetch_array($result))
{
    $no++;    
    $leadid=$myrow['leadid'];
    $custid=$myrow['cust_id'];
    $enqid=$myrow['enqtypeid']; 
    $taskid=$myrow['taskid'];
    $duedate=$myrow['duedate'];
    $tid=$myrow['tid'];

if($_SESSION['UserID']=='bdm_national' || $_SESSION['UserID']=='bde_national1' ||$_SESSION['UserID']=='bdm_international' ||$_SESSION['UserID']=='bde_international1') 
{
  
if ($myrow['emailstatus']=='Customer Replied')    
{
echo '<tr bgcolor=#81F781>';
}
else if($myrow['emailstatus']=='Greeting message send')
{
echo '<tr bgcolor=#F2F5A9>';

}
else if($myrow['emailstatus']=='Email received')
{
echo '<tr bgcolor=#F5A9A9>';
}
else
{
        echo '<tr class="OddTableRows">';   

}
}
else
{



    if(date("Y-m-d") > $duedate AND $duedate!='0000-00-00 00:00:00')
    {   
        echo '<tr bgcolor=#FF8080>';  
        $datedue=ConvertSQLDate($myrow['duedate']);
    }else{   
        echo '<tr class="OddTableRows">';   
        $datedue='';
    }
}
    echo"<td width='150px'>$no</td>";
      echo"<td width='150px'>".$myrow['custname']."</td>";
    $gra=$myrow['grade'];
if($gra==A)
{
echo "<td  bgcolor='#339900'>";
}
else if($gra==B)
{
 echo "<td  bgcolor=orange>";
}
 else if($gra==C)
{
 echo '<td  bgcolor="#0099CC">';
} 
else if($gra==D)
{
echo '<td  bgcolor="#CCFF33">';
}
else if($gra==E)
{
 echo '<td  bgcolor=Pink>';
} 
if($gra!=null) 
{
  echo"<b>".$gra."</b></td>"; 
   
}
else{
     echo"<td width='15px'>".$gra."</td>";
       
    }
  
  
    echo"<td width='100px'>".$myrow['custmob']."</td>";
    echo"<td width='150px'>".$myrow['district']."</td>";
    echo"<td width='200px'>".$myrow['task']."</td>"; 
    echo"<td width='100px'>".ConvertSQLDate($myrow['duedate'])."</td>"; 
    echo"<td width='100px'>".$myrow['biostatus']."</td>";
if($_SESSION['UserID']=='bdm_national' || $_SESSION['UserID']=='bde_national1' ||$_SESSION['UserID']=='bdm_international' ||$_SESSION['UserID']=='bde_international1') 
{
    echo"<td width='100px'>".$myrow['emailstatus']."</td>";

}
    if($taskid==21){
    echo"<td width='50px'><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$tid,$custid,$enqid)'>" . _('Select') . "</a></td>";   
    }else{
    echo"<td width='50px'><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$tid);'>" . _('Select') . "</a></td>";       
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
 include('includes/footer1.inc');
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

var mySplitResult = xx[0].explode(",");  

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


function instdate(str,str1)
{     
if(str1!=0){   
alert(str1);     
       alert("Already registered");
}else{
       controlWindow=window.open("bio_installationdate.php?leadid=" + str,"RecreateSchedule","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=980,height=500");                                        
}
}

function selectTask(str1,str2,str3,str4,str5){
    var leadtype=1;
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
      controlWindow=window.open("bio_recreateSchedule.php?leadid=" + str1 + "&tid=" + str2,"RecreateSchedule","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=980,height=500");
      }
      if(str2==20){ 
      controlWindow=window.open("bio_multiplePayment.php?lead=" + str1 +"&task="+str2+ "&tid=" + str3 +"&leadtype="+leadtype,"Payment","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=850,height=400");
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
      }  
  if(str2==24){
      controlWindow=window.open("bio_documentmanagement.php?lead=" + str1 + "&taskid=" + str2,"doc","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1000,height=800");    
      }  

 if(str2==25){
      controlWindow=window.open("bio_SelectSalesOrder.php?lead=" + str1 + "&taskid=" + str2,"invoice","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1100,height=600");    
      }
 //*/
//xmlhttp.open("GET","bio_domListTask.php?leadid=" + str1 + "&tid=" + str2,true);
//xmlhttp.send(); 
}
function dropLead(str1,str2){
myRef = window.open("bio_dropleadfromtask_dom.php?leadid=" + str1,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
    }
function listTask(str1){
//alert(str1);   alert(str2);
window.location="bio_domListTask.php?leadid=" + str1;  
}

function updateDate(str1,str2,str3,str4){   
//alert(str1);   alert(str2);      alert(str3);     alert(str4);
if (str1=="")
  {
  document.getElementById("fullbody").innerHTML="";
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
    document.getElementById("fullbody").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_updateDate.php?date=" + str1 + "&tid=" + str2 + "&leadid=" + str3 + "&choosedate=" + str4 ,true);
xmlhttp.send(); 
} 

function viewMessage(str1){
myRef = window.open("bio_viewMessage.php","Email Message","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=430");
    }

function status(str1){   
var str2=document.getElementById('statusChange').value;
//alert(str2); 
// alert(str2);      alert(str3);     alert(str4);
if (str1=="")
  {
  document.getElementById("fullbody").innerHTML="";
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
         alert("Status Changed");
    }
  } 
xmlhttp.open("GET","bio_domChangestatus.php?leadid=" + str1 +"&status="+str2,true);
xmlhttp.send(); 
} 

function updateDetails(str1,str2){
   
//var str1=document.getElementById('custplace').value;                       
//var str2=document.getElementById('custdist').value;       
//var str3=document.getElementById('remarks').value;
//var str4=document.getElementById('leadid').value; 
//var str5=document.getElementById('custname').value;    
 //alert(str4);       alert(str3);
 //alert(str1);       alert(str2);      
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
xmlhttp.open("GET","bio_updateDetails.php?leadid=" + str1 + "&remarks=" + str2 ,true);
xmlhttp.send(); 
}

</script>
