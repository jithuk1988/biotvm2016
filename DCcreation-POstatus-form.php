<?php
$PageSecurity = 5;

include('includes/session.inc');
echo'<tr><td>Show item demands</td><td></td></tr>';
echo'<tr><td>From:</td><td><input type="text" name="Fromdate" id="fromdate"></td></tr>';
echo'<tr><td>To:</td><td><input type="text" name="Todate" id="todate"></td></tr>';  
echo'<tr><td></td><td style="float:middle;"><input type="button" name="Selection" id="selection" value="View"></td></tr>';

echo"<td><input type='Text' class=date alt=".$_SESSION['DefaultDateFormat']." name='Duedate' value='".$_POST['Duedate']."' id='duedate' size=25
            maxlength=12 value=".$_POST['Duedate']."></td>";
?>
