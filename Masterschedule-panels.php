<?php
  $PageSecurity = 11;

 include('includes/session.inc');

 $_POST['DemandID']=$_GET['p']; 
 
// $_SESSION['SupplierID'] = $_POST['SupplierID'];  

 unset($_POST['New']);
 
    $where = ' WHERE mrpdemands.demandid =' . "'"  .  $_POST['DemandID'] . "'";
    $sql = 'SELECT mrpdemands.stockid,
                   mrpdemands.quantity,
                   mrpdemands.statusid,
                   mrpdemands.duedate,  
                   mrpdemands.season_id,               
                   stockmaster.description
        FROM mrpdemands
        LEFT JOIN stockmaster on mrpdemands.stockid = stockmaster.stockid' .
        $where    . ' ORDER BY mrpdemands.stockid, mrpdemands.duedate';      
        $result=DB_query($sql,$db);
         
        $myrow = DB_fetch_array($result);
          $_SESSION['StockID']=$myrow['stockid'];
          $_POST['Quantity']=$myrow['quantity'];
          $_POST['Duedate']=ConvertSQLDate($myrow['duedate']);
          $_POST['Statusid']=$myrow['statusid'];


 $leg_l1='Daily production plans'; 
// $leg_r1="Past season's details";      
 echo '<table width=100%><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
 echo "<fieldset class='left_panel_1'>";     
  echo"<legend><h3>$leg_l1</h3>";
  echo"</legend>"; 
    echo'<table>'; 
    include('MasterSchedule-leftpanel1.php');    
    echo'</table>'; 
       echo "</fieldset>";        
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
  
  echo'<td valign="top" height=270px>';
 /* echo "<fieldset class='right_panel_1'>";     
  echo"<legend><h3>$leg_r1</h3>";
  echo"</legend>";
  echo'<table>';
  //include('MasterSchedule-rightpanel1.php'); //----------M
  echo"</table>";
  echo "</fieldset>";  */

    echo"</td></tr></table>";
    echo "</fieldset>"; 
    echo '</div>';

    
    echo"</td></tr></table>";   
?>
