<?php
$PageSecurity = 80;
include('includes/session.inc');
$value=$_GET['task'];

if($value==28 OR $value==26 OR $value==20) {
    echo '<td>Value</td>';
echo "<input type=text name='Number' id='number' style='width:90px' value=".$target."></td>";                     
    
}
else if($value==71) {
    echo '<td>Number of cubic meter</td>';
echo "<input type=text name='Number' id='number' style='width:90px' value=".$target."></td>";                     
    
}
 else{
   echo '<td>Number of task</td>';
echo "<input type=text name='Number' id='number' style='width:90px' value=".$task."></td>";                        
     
 }



?>
