<?php
$PageSecurity = 5;

include('includes/session.inc');

    echo "<fieldset class='right_panel_1'>";     
    echo"<legend><h3>PO stock status</h3>";
    echo"</legend>";
    echo '<table width=100% id="right_panel_1_DCcreation">';
    include('DCcreation-POstatus-form.php');
    echo"</table>";
    echo "</fieldset>";   
?>
