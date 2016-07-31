<?php
$PageSecurity = 80;  
include('includes/session.inc');

if($_GET['date']!=NULL){
$due=FormatDateForSQL($_GET['date']);
$date = strtotime(date("Y-m-d", strtotime($due)) . " +3 day");
$date1=date('Y-m-d',$date);
echo '<td><input type="text" name="duedate1" id="duedate1" value="'.$date1.'"></td>';

}

?>
