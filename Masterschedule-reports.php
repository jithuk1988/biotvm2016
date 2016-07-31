<?php
 $PageSecurity = 80;
 require('includes/session.inc');
    $part=$_GET['q'];
    $leg_r1="Daily Schedules";
    

    
    
//-------------------------------------------------------------------------    
    echo "<fieldset class='right_panel_1'>";     
    echo"<legend><h3>$leg_r1</h3>";
    echo"</legend>";
    echo '<table width=100% class=sortable>';
    include('MasterSchedule-rightpanel1-report.php'); //----------M
    echo"</table>";
    echo "</fieldset>"; 
//------------------------------------------------------------------------- 
?>
