<?php

$PageSecurity = 2;


include('includes/session.inc');

echo "<fieldset id='right_panel_1' style='height:180px;'>";     
echo"<legend><h3>Add Inventory Item</h3>";
echo"</legend>";
echo'<table>';

echo'<tr>';
echo'<td>Supplier</td>';
echo'<td><select name="Dcreceivingsupplier" id="dcreceivingsupplier">';

            $sql = "SELECT suppliers.supplierid,
                           suppliers.suppname                                        
                FROM suppliers
                ORDER BY suppliers.supplierid";
            $result = DB_query($sql,$db); 
$f=0; 
        
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['supplierid']==$_SESSION['supplierid']) {
                echo "<option selected value='" . $myrow['supplierid'] . "'>" . $myrow['suppname']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['supplierid'] . "'>" . $myrow['suppname']; 
          
        
        $f++;    
        } //end while loop
            
        echo '</select>'; 
        
echo'</td>'; 
echo'</tr>';

echo'<tr>';
echo'<td>Item</td>';
echo'<td><select name="Dcreceivingitem" id="dcreceivingitem">';

            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description
                                        
                FROM stockmaster
                WHERE mbflag='B'
                ORDER BY stockmaster.stockid";
            $result = DB_query($sql,$db); 
$f=0; 
        
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['stockid']==$_SESSION['StockID']) {
                echo "<option selected value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
          
        
        $f++;    
        } //end while loop
            
        echo '</select>'; 
        
echo'</td>'; 
echo'</tr>';

echo'<tr>';
echo'<td></td>
<td>
<input type="button" name="Dcreceivingbutton" id="dcreceivingbutton" value="Show" onclick="viewGRNSreport()">
</td>';
echo'</tr>';

echo "</table>";
echo "</fieldset>";  
?>
