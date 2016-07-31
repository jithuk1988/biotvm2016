<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $fsid=$_GET['p'];
 $lead_ID=$_GET['id']; 
 
 
 $sql="SELECT bio_cust.custname, 
              bio_district.district,
              bio_cust.contactperson, 
              bio_cust.custmob, 
              bio_leads.remarks, 
              bio_leads.sourceid,
              bio_leadtask.leadid, 
              bio_leadtask.duedate, 
              bio_leadtask.assigneddate, 
              bio_leadtask.teamid,
              bio_cust.nationality,
              bio_cust.state 
         FROM 
              bio_cust
    LEFT JOIN 
              bio_leads ON bio_cust.cust_id=bio_leads.cust_id 
    LEFT JOIN 
              bio_leadtask ON bio_leadtask.leadid=bio_leads.leadid 
    LEFT JOIN 
              bio_district ON bio_district.did = bio_cust.district
          AND bio_cust.nationality = bio_district.cid
          AND bio_cust.state = bio_district.stateid
        WHERE
              bio_leadtask.leadid=".$lead_ID; 
  
  $result=DB_query($sql,$db);    
  $myrow=DB_fetch_array($result);  
   
  $leadid=$myrow['leadid'];  
  $cname=$myrow['custname'];  
  $orgname=$myrow['contactperson'];
  $number=$myrow['custmob'];  
  $date=ConvertSQLDate($myrow['duedate']); 
  
  $startdate=ConvertSQLDate($myrow['assigneddate']);
  $district=$myrow['district'];
  $remark=$myrow['remarks'];
 /* 
  $sourceid=$myrow['sourceid'];
  $sql_src_count="SELECT COUNT(*) FROM bio_leadsources
                    WHERE sourcetypeid=13 
                      AND id=$sourceid";
  $result_src_count=DB_query($sql_src_count,$db);
  $myrow_src_count=DB_fetch_array($result_src_count);          */
  $leadsource_count=$myrow_src_count[0];
  
 /* if($leadsource_count>0){
      if($myrow['nationality']==1 AND $myrow['state']==14){
          $custtype=4;
      }elseif($myrow['nationality']==1 AND $myrow['state']!=14){
          $custtype=5;
      }elseif($myrow['nationality']!=1){
          $custtype=6;
      }
      
  }else{
       if($myrow['nationality']==1 AND $myrow['state']==14){
          $custtype=1;
      }elseif($myrow['nationality']==1 AND $myrow['state']!=14){
          $custtype=2;
      }elseif($myrow['nationality']!=1){
          $custtype=3;
      }
      
      
  }     */
   if($myrow['nationality']==1 AND $myrow['state']==14){
          $custtype=1;
      }elseif($myrow['nationality']==1 AND $myrow['state']!=14){
          $custtype=2;
      }elseif($myrow['nationality']!=1){
          $custtype=3;
      }
  
  
 
 $sql1="SELECT bio_changepolicy.value
          FROM bio_changepolicy       
         WHERE bio_changepolicy.policyname='Institution FS Charge' 
           AND bio_changepolicy.enquirytype=".$custtype;
 
  $result=DB_query($sql1,$db);    
  $myrow1=DB_fetch_array($result); 
 
 $amount=$myrow1['value'];

     
 echo '<table><tr>';  
 echo'<td valign=top>';  
 echo"<div id=cus_details style='overflow:scroll;'>"; 
  
 echo"<fieldset style='width:300px;height:150px;overflow:scroll;'><legend>Customer Details</legend>";
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
echo"<input type='hidden' name='fsid' id='fsid' value='$fsid'>";

// echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";  
//echo"<input type='hidden' name='Amount' id='value' value='$amount'>";
 


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
echo"<td><input type='text' name='ExpectedDate' placeholder='DD/MM/YYYY'  id='date11' class=date alt='".$_SESSION['DefaultDateFormat']. "' onkeypress='return noenter()' style='width:135px'></td></tr>";
echo"<tr></tr>";
 
echo"<tr><td>FS Charge :</td>";       
echo"<td><input type='text' name='Amount' id='amount' value='$amount' onkeypress='return noenter()'></td></tr>";

echo"<input type='hidden' name='Team' id='team' value='".$myrow['teamid']."'>";

echo"</table>"; 

echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>"; 
echo '<center><input type="button" name="newfs" id="newfs" value="Create New" onClick="newt()"></center>';       

?>
