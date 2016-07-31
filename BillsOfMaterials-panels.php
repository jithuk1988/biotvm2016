<?php
    $PageSecurity = 80;

    
 include('includes/session.inc');

 $Select=$_GET['p']; 
 
   $leg_l1='Add BOMs';
     echo '<table width=100%><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
  echo "<fieldset class='left_panel_1'>";     
  echo"<legend><h3>$leg_l1</h3>";
  echo"</legend>"; 
  echo'<table>'; 
  include('BillsOfMaterials-leftpanel1.php');
  echo'</table>'; 
  echo "</fieldset>"; 
  echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px>';
    
//  echo "<fieldset class='right_panel_1'>";     
//  echo"<legend><h3>$leg_r1</h3>";
//  echo"</legend>";
//  echo'<table>';
//  include('BillsOfMaterials-rightpanel1.php');
//  echo"</table>";
//  echo "</fieldset>"; 
    

    
    echo"</td></tr></table>"; 
?>
