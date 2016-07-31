<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Dealer View');
include('includes/header.inc');
include('includes/sidemenu.php');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Dealer view</font></center>';
    if($_GET['lead']!=''){
    
    $leadid=$_GET['lead'];    
       $sql="SELECT bio_leads.leadid, 
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_district.district,
                  
                   
                    bio_emp.empname,
                    bio_cust.cust_id,
                    bio_leads.enqtypeid,
                    bio_leads.remarks,
                    SUM( bio_advance.amount) AS amount 
              FROM  bio_leads,bio_cust,bio_district,bio_emp,www_users,bio_advance
              WHERE bio_leads.cust_id=bio_cust.cust_id
                
                AND bio_district.did=bio_cust.district
                AND bio_district.stateid=bio_cust.state
                AND bio_district.cid=bio_cust.nationality
                
                AND bio_leads.leadid=bio_advance.leadid
                AND bio_leads.created_by=www_users.userid
                AND www_users.empid=bio_emp.empid
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
 $sourcename=$myrow['sourcename']; */  //style='width:800px'
 
 
 $amount=$myrow['amount']; 
 $createdby=$myrow['empname']; 
 $remarks=$myrow['remarks'];  
} 
echo"<fieldset ><legend>Customer details</legend>";
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
echo "<a style='cursor:pointer;' id='$ticketno' onclick='viewMessage(this.id)'>View Mail</a><td></tr>";
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
echo"<td><textarea rows=2 cols=30 name=remarks id=remarks style=resize:none; value='$remarks' onchange=updateDetails('$leadid',this.value)>$remarks</textarea></td></tr>";
}
echo"<tr></tr>";
echo"<tr><td>Created By:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$createdby'>$createdby</td></tr>";  
echo"<tr></tr>";
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
echo "</table>";
echo "</fieldset>";

//--------------------------------
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

              
        echo"</tr>";        
     $no++;
  }
          
echo"</table>";
echo"</fieldset>"; 
echo'<div class="centre">';   
echo'</div>';
}  

?>
<script type="text/javascript">
function selectTask(str1,str2,str3,str4,str5)
{
     controlWindow=window.open("dealercontact.php?leadid=" + str1 + "&tid=" + str2 + "&enqid=" + str4,"RecreateSchedule","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=980,height=500");
}
</script>