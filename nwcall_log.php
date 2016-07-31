<?php
$PageSecurity = 80;  
include('includes/session.inc');
 $ord=$_GET['order'];
 $work_states=$_GET['status'];
if($work_states==2)
{
echo '<a style=cursor:pointer; onclick=showcalstatus1(1)>' . _('REGISTER COMPLAINT') . '</a>'; 
}
?>