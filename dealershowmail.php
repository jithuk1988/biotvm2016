<?php

$PageSecurity = 80;  
include('includes/session.inc');
$title = _('Dealer mail');
include('includes/header.inc');
 $busid=$_GET['bussid'];

$sql="SELECT email_message FROM bio_incidents WHERE buss_id=".$busid; 
$result=DB_query($sql,$db);
$row=DB_fetch_array($result);
          $heading="MAIL DETAILS";
 
echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">'.$heading.'</font></center>';  
echo"<div id='dealer' style='background-color:#F0F0F0;margin-left:50px;margin-top:100px;width:80%;height:100px;'>"; 
 echo "<font size='+1' style='color:#000000'>".nl2br($row['email_message'])."</font>";  
echo "</div>";

?>
