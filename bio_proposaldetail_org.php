<?php
  $PageSecurity = 80;
  include('includes/session.inc');
 $lead_ID=$_GET['p'];
 $TID=$_GET['id']; 
  
$sql="SELECT bio_cust.custname,
               bio_leadtask.leadid,
               bio_cust.contactperson,
               bio_leadtask.duedate,
               bio_leadtask.assigneddate,
               bio_leadtask.teamid,
               bio_cust.custmob, 
               bio_district.district,
                bio_leads.remarks,
               bio_changepolicy.value 
        FROM   bio_cust,
               bio_leads,
               bio_leadtask,
               bio_changepolicy,
               bio_district  
        WHERE  bio_cust.cust_id=bio_leads.cust_id 
          AND  bio_leadtask.leadid=bio_leads.leadid 
          AND bio_district.did=bio_cust.district
          AND bio_district.stateid=bio_cust.state
          AND bio_district.cid=bio_cust.nationality
          AND  bio_changepolicy.policyname='Institution FS Charge' 
          AND  bio_leadtask.leadid=".$lead_ID."
          AND  bio_leadtask.teamid=".$TID;

   $result=DB_query($sql,$db);    
   $myrow=DB_fetch_array($result);  
   
  $leadid=$myrow['leadid'];  
  $cname=$myrow['custname'];  
  $orgname=$myrow['contactperson'];
  $number=$myrow['custmob'];  
  $date=ConvertSQLDate($myrow['duedate']); 
  $amount=$myrow['value'];
  $startdate=ConvertSQLDate($myrow['assigneddate']);
  $district=$myrow['district'];
  $remark=$myrow['remarks'];
     
  echo '<table><tr>';  
 echo'<td valign=top>';  
 echo"<div id=cus_details>"; 
  
 echo"<fieldset style='width:300px;height:150px'><legend>Customer Details</legend>";
echo"<table width=100%>"; 

 echo"<tr><td width=50%>Customer name :</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td width=50%>Contact Person :</td>";
echo"<td><input type='hidden' name='Housename' id='housename' value='$orgname'>$orgname</td></tr>";


 echo"<tr><td width=50%>Contact number :</td>";
echo"<td><input type='hidden' name='number' id='number' value='$number'>$number</td></tr>";
echo"<tr></tr>";

 echo"<tr><td width=50%>District:</td>";
echo"<td><input type='hidden' name='district' id='district' value='$district'>$district</td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>Remarks</td>";
echo"<td><input type='hidden' name='remark' id='remark' value='$remark'>$remark</td></tr>";
echo"<tr></tr>";


 echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";  


echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo'</div>';
echo"</td>"; 
//----------------------------------------------------------------------------------------------------------//
 echo'<td valign=top>';    
echo'<div id=right_panel_1>';
 echo"<fieldset style='width:300px;height:150px; overflow:auto;'>";
echo"<legend>Feasibility Proposal</legend>";
echo"<table>";

 //$DateString = Date($_SESSION['DefaultDateFormat']); 

echo"<tr><td>Start date :</td>";
echo"<td><input type='hidden' name='StartDate' id='startdate'  style='width:135px' value='".$startdate."'>$startdate</td></tr>";

echo"<tr></tr>";
echo"<tr><td>Due Date :</td>";
echo"<td><input type='hidden' name='Date' id='date1' value='".$date."'>".$date."</td></tr>";

echo"<tr><td>Expected Date For Approval* :</td>";
echo"<td><input type='text' name='ExpectedDate'  id='date' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:135px'></td></tr>";
echo"<tr></tr>";
 
echo"<tr><td>FS Charge :</td>";       
echo"<td><input type='text' name='Amount' id='amount' value='$amount'></td></tr>";

echo"<input type='hidden' name='Team' id='team' value='".$myrow['teamid']."'>";

echo"</table>"; 

echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>"; 
?>
