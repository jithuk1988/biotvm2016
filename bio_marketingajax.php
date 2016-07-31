<?php

$PageSecurity = 80;
include('includes/session.inc');
include ('includes/SQL_CommonFunctions.inc');
   if($_GET['leadid1'])
          {
              $leadid=$_GET['leadid1'];  
           $sql="SELECT bio_leads.leadid, bio_leads.pref_time,
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
                 //$amount=$myrow['amount']; 
                 $createdby=$myrow['empname']; 
                 $remarks=$myrow['remarks'];
                 $ptime=$myrow['pref_time'];
          
          echo '<td align="left" style="width:470px">';
             echo"<fieldset style='width:440px;height:280px '>"; 
          echo"<legend>Customer Details</legend>";
          
           echo"<table width=100%>";
            echo "<tr><td><b>Customer name</b></td><td><b>:</b></td><td><b>".$cname."</b></td></tr>";
            echo "<tr><td><b>Customer contact</b></td><td><b>:</b></td><td><b>".$cph."</b></td></tr>";     
            echo "<tr><td><b>Customer email</b></td><td><b>:</b></td><td><b>".$email."</b></td></tr>";  
            echo "<tr><td><b>Customer place</b></td><td><b>:</b></td><td><b>".$place."</b></td></tr>";
            echo "<tr><td><b>Customer district</b></td><td><b>:</b></td><td><b>".$district."</b></td></tr>";   
            echo "<tr><td><b>Lead source[team]</b></td><td><b>:</b></td><td><b>".$sourcename."[".$team."]</b></td></tr>";
    
            echo "<tr><td><b>Created by</b></td><td><b>:</b></td><td><b>".$createdby."</b></td></tr>";   
            echo "<tr><td><b>Preferred time</b></td><td><b>:</b></td><td><b>".$ptime." </b></td></tr>";   
            echo "<tr><td><b>Remarks</b></td><td><b>:</b></td><td><b><textarea name='' cols='30'  rows='3' readonly >".$remarks."</textarea></b></td></tr>";    
         echo'</table>';
          echo '</fieldset>';
        /*echo '<td>';  
         echo"<fieldset style='width:440px;height:280px'>"; 
         echo"<legend>Call Details</legend>";*/
         echo"<br />";  
  $leadid=$_GET['leadid1'];             
  $taskid=18;
  $crdt=date("Y-m-d H:i:s");
          $sqltim="select pref_time from bio_leads where leadid=".$_GET['leadid1'];
    $result_tim=DB_query($sqltim,$db);
    $myrow_tim=DB_fetch_array($result_tim);
     $time=$myrow_tim[0]; 
$sql_mail="SELECT bio_cust.custmail FROM bio_cust,bio_leads
                    WHERE bio_cust.cust_id=bio_leads.cust_id and bio_leads.leadid=".$_GET['leadid1']; 
  $result_mail=DB_query($sql_mail,$db);
  $myrow_mail=DB_fetch_array($result_mail);
  $mail_msg=$myrow_mail[0]; 
      echo"<table><tr>";
       echo"<input type='hidden' name='leadid' id='leadid' value='$leadid'>"; 
 echo"<input type='hidden' name='taskid' id='taskid' value='$taskid'>";   
   /*    echo ' <tr> <td>Grade</td>';
       echo '<td>

 <table width="100%" border="0">
  <tr>
    <td width="5" bgcolor="#339900"><label>
      <input type="radio" name="grade" id="grade" value="A" />
    A</label></td>
    <td width="5" bgcolor=orange><label>
      <input type="radio" name="grade" id="grade" value="B" />
    B</label></td>
    <td width="5" bgcolor="#0099CC"><label>
      <input type="radio" name="grade" id="grade" value="C" />
    C</label></td>
    <td width="5" bgcolor="#CCFF33"><label>
      <input type="radio" name="grade" id="grade" value="D" />
    D</label>
      <td width="5" bgcolor=Pink><label>
     <input type="radio" name="grade" id="grade" value="E" />
    E</label></td>
 </tr>
</table>

                  </td>  </tr>'; 
                  
              echo '<td >Select Action</td>';
              echo '<td colspan="5"><select name="status" id="status" style="width:245px" onchange="selection()">';
              echo '<option value="0"></option>';
              echo '<option value="1">Proceed to next task</option>';
              echo '<option value="2">Delete enquiry and Cancel all tasks</option>';
              echo '<option value="3">Add new Contact date</option>'; 
               echo '</select></td></tr>';
        
     
     echo'<tr id="newTask"><td>Next Contact Date</td>';
   echo'<td colspan="5"><input type="text" style="width:240px" id="date" class=date alt='.$_SESSION['DefaultDateFormat'].' name="date" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';
         echo"<tr id='pref'><td>Current Prefered Time:</td>";
     echo'<td colspan="5" style="font-weight:bold">'.$time.'</td></tr>';
           echo"<tr id='pref'><td>New Prefered Time:</td>";
     echo'<td colspan="5"><input type="text" style="width:240px" id="tim" name="tim" ></td></tr>';
   
     echo"<tr><td>Remarks:</td>"; 
     echo"<td colspan'5'><textarea rows=2 cols=35 name=remarks id=remarks style=resize:none; value='$remarks' onchange=updateDetails('$cid','$remarks')>$remarks</textarea></td></tr>";     
           
    echo"<tr><td></td><td align=center colspan='5'><input type='submit' name='submit' id='submit' value='Submit' onclick='if(validation()==1)return false;;'></td>";
    echo"</tr></table>"; 
     echo"</fieldset>";
    echo"</td></tr>";
if($mail_msg!=''){
echo"<tr>"; 
echo"</td>";
echo"<td align=center><a href='#' id='".$leadid."' onclick='sendMail(this.id)'>Send Mail</a></td></tr>";   
echo"</td>";
echo"</tr>";   
          }*/}
?>
