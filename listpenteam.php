<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('List Pending Team');

if(isset($_POST['team']))
{
    echo$team=$_POST['team'];
}

?>
