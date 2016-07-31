<?php
  $PageSecurity = 11;

 include('includes/session.inc');
$reqid=$_GET['id'];
echo'<input type="hidden" name="Reqno1" id="reqno1" value="'.$reqid.'">'; 

echo '<td >SR no:&nbsp;</td>
<td id="newid"><input type="text" name="Reqno" id="reqno" value="'.$reqid.'">';
echo'<a style="cursor:pointer;" id="'.$reqid.'"  onclick="updateid(this.id)">&nbsp;update</a>';
echo'</td>';

  
?>
