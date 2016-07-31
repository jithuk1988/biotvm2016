<?php
  $PageSecurity = 11;

 include('includes/session.inc');
if((isset($_GET['p'])) AND (($_GET['p']!="")) OR ($_GET['p']!="undefined"))       { 
 $splitter=explode('/',$_GET['p']);
 $demandid=$splitter[0];

 $wono=$splitter[1];
  
$_POST['demandno']=$demandid;

 $_POST['DemandID']=$demandid; 
 
 
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
          $_POST['DemandQuantity']=$myrow['quantity'];
          $_POST['Duedate']=ConvertSQLDate($myrow['duedate']);
          
if($wono!='')       {
$sql5="SELECT qtyreqd
       FROM woitems
       WHERE wo=".$wono;
$result5=DB_query($sql5,$db);
$myrow5=DB_fetch_array($result5); 
$_POST['Quantity']=$myrow5[0]; 

$sql5="SELECT statusid
       FROM wostatus
       WHERE wono=".$wono;
$result5=DB_query($sql5,$db);
$myrow5=DB_fetch_array($result5); 
$_POST['Wostatus']=$myrow5[0]; 


$sql2="SELECT serialno
       FROM woserialnos
       WHERE wo=".$wono;
$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_array($result2);
$_POST['Batch']=$myrow2[0];

echo'<input type="hidden" name="Wonumber" value='.$wono.'>';
     
}else   {       
       
$sql2="SELECT serialno
       FROM woserialnos
       WHERE stockid='".$_SESSION['StockID']."'
       ORDER BY serialno
       DESC LIMIT 1";
$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_array($result2);
$_POST['Batch']=$myrow2[0]+1;
}

}
 $leg_l1='Enter WO details'; 
// $leg_r1="Past season's details";      
 echo '<table width=100%><tr><td width=50% valign="top" height=270px id="WOEntry-left_panel_1">';   
    
//--------------------------------------------------------------Start of Left Panel1  
  echo "<fieldset class='left_panel_1'>";     
  echo"<legend><h3>$leg_l1</h3>";
  echo"</legend>"; 
    echo'<table>'; 
    include('WOEntry-leftpanel1.php');    
    echo'</table>'; 
       echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
  
  echo'<td valign="top" height=270px id="WOEntry-right_panel_1">';
  echo "<fieldset class='right_panel_1'>";     
  echo"<legend><h3>$leg_r1</h3>";
  echo"</legend>";
  echo'<table>';
  include('MasterSchedule-rightpanel1.php'); //----------M
  echo"</table>";
  echo "</fieldset>";  

    echo"</td></tr></table>";
    echo "</fieldset>"; 
    echo '</div>';

    
    echo"</td></tr></table>";   
?>
