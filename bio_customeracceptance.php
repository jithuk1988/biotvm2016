<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Customer Acceptance');  
include('includes/header.inc');

$leadid=$_GET['leadid'];

if(isset($_POST['submit']))
{
  $status=$_POST['status'];
  $taskid=$_POST['taskid'];
  $leadid=$_POST['leadid'];   
  if($taskid==15)
  {
      $sql1="SELECT max(fs_propono) from bio_fsproposal where leadid=$leadid";
              $rst1=DB_query($sql1,$db); 
               $myrow=DB_fetch_array($rst1);
                 $fsno=$myrow[0];
              

      $sql="update bio_fsproposal set status=$status, acc_rej_date='".date('Y-m-d')."' where leadid=$leadid and fs_propono=$fsno ";
       $rst=DB_query($sql,$db); 
  } 
      
    
    
    
}


echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
 
echo"<input type='hidden' name='leadid' id='leadid' value='$leadid'>";
echo"<table style='border:1px solid #F0F0F0;width:100%'><tr><td>";

      echo"<fieldset style='width:450px'><legend>Perform Action</legend>";  
      echo"<table>";
//     echo"<tr><td>Remarks:</td>"; 
//     echo"<td><textarea rows=2 cols=35 name=remarks id=remarks style=resize:none; value='$remarks' onchange=updateDetails('$cid','$remarks')>$remarks</textarea></td></tr>";     
 
echo"<tr><td>Select task</td>";   
echo '<td><select name="taskid" id="taskid" style="width:215px">';
echo '<option value=0></option>'; 
echo '<option value=15>Submit Feasibility Proposal</option>';
//echo '<option value=3>Submit Concept Proposal</option>';  
echo '</select></td>'; 
echo '</tr>';   

echo"<tr><td>Change Status</td>";    
echo '<td><select name="status" id="status" style="width:215px">';             //From bio_proposal_status table
echo '<option value=0></option>';  
echo '<option value=8>Customer Accept</option>'; 
echo '<option value=7>Customer Reject</option>';
echo '</select></td>'; 
echo '</tr>';   
     
     
           
    echo"<tr><td></td><td align=right><input type='submit' name='submit' id='submit' value='Submit' onclick='if(validation()==1)return false;;'></td>";  
    echo"</tr></table>";
    echo"</fieldset>";
    echo"</td></tr>";


echo"</table>";  
 
echo"</form>";
?>
