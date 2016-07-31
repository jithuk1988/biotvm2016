<?php
     $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('CDM');
include ('includes/header.inc');
echo '<head><link href="menu_assets/styles.css" rel="stylesheet" type="text/css"></head>';
include ('includes/SQL_CommonFunctions.inc');
echo "<div id='cssmenu'>
<ul>
   <li > <a href='bio_nwinstallationstatuses.php'><span>Post installation</span></a></li>
   <li ><a href='bio_marketingtask.php'><span>Marketing task</span></a></li>
 <li><a href='bio_warranty_amc.php'><span>AMC / Warrenty</span></a></li>
   <li><a href='bio_paypending.php'><span>Payment pending</span></a></li>
   <li class='active '><a href='bio_cdmsurvey.php'><span>CDM survey</span></a></li>
   <li><a href='bio_complaintfollow.php'><span>Complaint followup</span></a></li>
</ul>
</div>";
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">CDM</font></center>';
     echo'<table width=98% ><tr><td>'; 
    echo'<div >'; 
    echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>";  
    echo"<fieldset style='width:400px;height:155px'; overflow:auto;'>";
    echo"<legend><h3>CDM SURVEY</h3></legend>";
    echo'</div>';
    echo'</fieldset>';
    echo'</table>';
?>
