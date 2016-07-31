<?php 
    $PageSecurity = 11;
    include('includes/session.inc');
    
    $leg_r1="BOM list";   
    
    $_REQUEST['WO']=$_GET['q'];
    $_REQUEST['StockID']=$_GET['r'];
    $Woqty=$_GET['s']; 
    
    echo "<fieldset class='right_panel_1'>";     
    echo"<legend><h3>$leg_r1</h3>";
    echo"</legend>";
    echo'<div style="height:230px;overflow:auto;">'; 
    echo '<table width=100% class=sortable>';
    if($_GET['p']==1)       {
    
    include('WOStoresrequest-rightpanel-bomlist.php');     
        
    }
//    include('WOStoresrequest-rightpanel-rec.php');  
    echo"</table>";
    echo"</div>";
    echo "</fieldset>"; 
?>
