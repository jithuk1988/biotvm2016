<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  
  $lead=$_GET['lead'];
  $taskid=$_GET['task'];
   echo"<table><tr>";
                     
               
               echo '<td>Select Action</td>';
              echo '<td><select name="status" id="status" style="width:245px" onchange="selection()">';
              echo '<option value="0"></option>';
              echo '<option value="1">Proceed to next task</option>';
             // echo '<option value="2">Delete enquiry and Cancel all tasks</option>';
              echo '<option value="3">Add new Contact date</option>';  
              echo '</select></td></tr>';
               
             echo"<tr id='newTask'><td>Next Contact Date</td>";
     echo'<td><input type="text" style="width:240px" id="date" class=date alt='.$_SESSION['DefaultDateFormat'].' name="date" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';       
                     
          
     
          echo"<tr><td>Remarks:</td>"; 
     echo"<td><textarea rows=2 cols=35 name=remarks id=remarks style=resize:none; value='$remarks'>$remarks</textarea></td></tr>";     
           
    echo"<tr><td></td><td align=right><input type='submit' name='changestatus' id='submit' value='Change Status' onclick='if(statusvalidation()==1)return false;;'></td>";  
    echo"</tr></table>";
?>
