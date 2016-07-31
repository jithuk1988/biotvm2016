<?php
 $PageSecurity = 40;
include('includes/session.inc');
 $id=$_GET['ticketno'];

    // echo"kuiiiiiiiiii" ;


echo '<table style=width:500px><tr><td>';
echo '<fieldset style="width:450px">';
echo '<legend><b>Merge Complaints</b></legend>';

echo"<table width=100%>";
echo"<tr><td >Ticket No to be merged</td>";

echo"<td><input type='text' name='ticketno1' ></td></tr>";

echo'<tr><td></td><td> <input type="Submit" name="submit1" value="' . _('Merge') . '" ></td></tr>'; 
 echo "<input type='hidden' name='ticket_id' id='ticket_id' value='".$id."'>";    

echo"</table>";
echo"</fieldset>";  
echo '</td></tr></table>';                      



?>


