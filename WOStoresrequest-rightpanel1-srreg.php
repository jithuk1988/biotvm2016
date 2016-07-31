<?php
$PageSecurity = 11; 
include('includes/session.inc');
    
    $reportof=$_GET['p'];
    $leg_r1="Reports";
    echo "<fieldset class='right_panel_1'>";     
    echo"<legend><h3>$leg_r1</h3>";
    echo"</legend>"; 
    echo '<table width=100% class=sortable>';
    include('WOStoresrequest-rightpanel1-srregload.php');
    echo"</table>";
    echo "</fieldset>";   
    
?>
