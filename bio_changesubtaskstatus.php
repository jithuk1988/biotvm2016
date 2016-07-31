<?php
$PageSecurity = 80;
include('includes/session.inc');
include('includes/header.inc');
$tid=$_GET['tid'];
$subtask_id=$_GET['subid']; 
if(isset($_POST['tid']))        {
    
$tid=$_POST['tid'];
$subtask_id1=$_POST['subtid']; 

$status=$_POST['Status'];

$sql11="UPDATE bio_subtask_master
        SET    bio_subtask_master.status=$status
        WHERE  bio_subtask_master.leadtask_id=$tid
        AND bio_subtask_master.subtask_master_id=".$subtask_id1; 
        
$result11=DB_query($sql11,$db);           
}


echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<fieldset style="height:225px">';
echo '<legend><b>Change Status</b></legend>';

echo '<br><br><table class="selection"  style="width:250px;height:160px">';  
  
echo'<input type="hidden" name="tid" id="tid" value='.$tid.'>';
echo'<input type="hidden" name="subtid" id="subtid" value='.$subtask_id.'>';  

echo"<tr><td width=70px>Change Status</td>";
echo"<td>";

$sql1="SELECT * FROM bio_subtaskstatus";
  $result1=DB_query($sql1, $db);
  echo '<select name="Status" id="status" style="width:130px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['Status']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['status']; 
    echo '</option>' ;
   $f++; 
   }
 echo '</select>';



echo"</td></tr>"; 

/*echo"<tr><td><td width=70px>Done</td>";
echo"<td><input type='checkbox' name='done' id='done'></td></td></tr>";

echo"<tr><td><td width=70px>Pending</td>";
echo"<td><input type='checkbox' name='pending' id='pending'></td></td></tr>";     */


echo'<td>';
echo'<tr><td></td><td><input type="submit" name="Changestatus" id="changestatus" value="Submit"></td></tr></td>';  

echo '</table>';
echo '</fieldset>';
 echo'</form>';
?>
