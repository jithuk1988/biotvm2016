<?php
$PageSecurity = 11; 
include('includes/session.inc');    

$leg_r1="Line Items";

$Srno=$_GET['q'];
$sql6="SELECT womaterialrequestdetails.stockid,
              womaterialrequestdetails.qtyrequest,
              stockmaster.description
       FROM womaterialrequestdetails,stockmaster
       WHERE reqno=".$Srno."    AND
             womaterialrequestdetails.stockid=stockmaster.stockid";
$result6=DB_query($sql6,$db);


//--------------------------------------------------------------Start of right Panel1  

    echo "<fieldset class='right_panel_1'>";     
    echo"<legend><h3>$leg_r1</h3>";
    echo"</legend>";    
    echo'<div style="height:210px; width:350px;overflow:auto;">';
    echo '<table width=100% class=sortable>';
    include('WOMaterialissuefromstores-rightpanel1-lineitems.php');
    echo"</table>";
    echo'</div>';
    echo "</fieldset>"; 
    
//--------------------------------------------------------------End of Left Panel1

?>
