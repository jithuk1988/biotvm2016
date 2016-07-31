<?php
    $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('GRN');
include ('includes/header.inc');
echo '<head><link href="menu_assets/styles.css" rel="stylesheet" type="text/css"></head>';
include ('includes/SQL_CommonFunctions.inc');
echo "<div id='cssmenu'>
<ul>
    <li > <a href='bio_nwinstallationstatus.php'><span>Post installation</span></a></li>
   <li ><a href='bio_marketingtask.php'><span>Marketing task</span></a></li>
 <li><a href='bio_warranty_amc.php'><span>AMC / Warrenty</span></a></li>
   <li><a href='bio_paypending.php'><span>Payment pending</span></a></li>
   <li><a href='bio_cdmsurvey.php'><span>CDM survey</span></a></li>
   <li><a href='bio_complaintfollow.php'><span>Complaint followup</span></a></li>
</ul>
</div>";

//>
?>
