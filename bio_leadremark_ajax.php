

<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 
 
$enqid=$_GET['enqid'];

if(isset($enqid)) {

            echo '<tr id="showremarks"><td  width=45%>Select a Remark</td>';
    echo'<td  width=55%>';
   
        $sql1="SELECT remark FROM bio_remarks where enqtypeid in ($enqid)";
    
        
        $result1=DB_query($sql1, $db);
       echo $count=DB_fetch_row($sql1,$db);
     echo '<select name="RemarkList" id="remarklist" tabindex=31 style="width:174px">';
        echo '<option value="" selected></option>';

  while($myrow=DB_fetch_array($result1))
  {  
  
    echo '<option value="';
    echo $myrow['remark'] . '">'.$myrow['remark'];     
    echo '</option>';
  } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';
}
?>
