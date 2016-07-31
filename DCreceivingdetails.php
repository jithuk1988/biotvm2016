<?php
  $PageSecurity = 2;
$PricesSecurity = 9;

include('includes/session.inc');

$DC_no=$_GET['p'];
$sql_edit="SELECT * FROM dispatchclearance WHERE dispatchclrno=".$DC_no;
$result_edit=DB_query($sql_edit,$db);
$myrow_edit=DB_fetch_array($result_edit);
$delivery_date=ConvertSQLDate($myrow_edit['deliverydate']);
$quantity=$myrow_edit['quantity'];

$quantity_received=$myrow_edit['qtyrecd'];
$bal_qty=$quantity-$quantity_received;
echo"<table>";

echo"<tr>
    <td>". _('Item') .":</td>
    <td><select name='Item' id='item' style='width:190px;'>";

$sql_item = "SELECT stockmaster.stockid,
                    stockmaster.description,
                    stockmaster.units
                FROM stockmaster INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                WHERE stockmaster.mbflag!='D'
                AND stockmaster.mbflag!='A'
                AND stockmaster.mbflag!='K'
                AND stockmaster.mbflag!='M'
                and stockmaster.discontinued!=1
                ORDER BY stockmaster.stockid";
$result_item = DB_query($sql_item,$db); 
while ($myrow = DB_fetch_array($result_item)) {
    if ($myrow['stockid']==$myrow_edit['itemcode']) {
         echo "<option selected value='" .$myrow['stockid'] . "'>" . $myrow['description'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description'];
    }
    
} //end while loop
     
echo"</select>
    </td>
    </tr>"; 
    
echo"<tr>
    <td>". _('Dispatch Clearence No') .":</td>
    <td><input type='hidden' name='DCNo' id='dcno' value='".$DC_no."'>$DC_no</td>
    </tr>";

     $sql1="SELECT grnno
       FROM grns
       ORDER BY grnno
       DESC LIMIT 1";  
    $result1=DB_query($sql1,$db);
    $value1=DB_fetch_array($result1);   
    $grn_number=$value1[0]+1;     
    
echo"<tr>
    <td>". _('Receipt no:') .":</td>
    <td><input type='text' name='GRNO' id='grno' value='".$grn_number."'></td>
    </tr>";    


$sql1="SELECT serialno
       FROM stockserialmoves
       WHERE stockid='".$myrow_edit['itemcode']."'";  
$result1=DB_query($sql1,$db);
 $value1=DB_fetch_array($result1);   
 $batch_no=$value1[0];
    
 $DateString = Date($_SESSION['DefaultDateFormat']);   
    
echo"<tr>
    <td>". _('Batch No') .":</td>
    <td><input type='text' name='BatchNo' id='batchno' value='".$batch_no."'></td>
    </tr>";
    
echo"<tr>
    <td>". _('Date') .":</td>
    <td><input type='Text' name='Date' id='date' class=date alt='".$_SESSION['DefaultDateFormat']. "' value='".$DateString."'></td>
    </tr>";
    
echo"<tr>
    <td>". _('Quantity') .":</td>
    <td><input type='text' name='Quantity' id='quantity' value='".$bal_qty."'></td>
    </tr>";


echo"</table>";
echo"</fieldset>";
echo"</td>";

echo'<td width=50%>';    
//echo "<fieldset id='right_panel_1' style='height:370px;'>";     
//echo"<legend><h3>Add Inventory Item</h3>";
//echo"</legend>";
//echo'<table>';


//echo "</table>";
//echo "</fieldset>"; 
echo"</td></tr></table>";

echo"<input type='hidden' name='DespatchedQty' id='qty' value='".$quantity."'>";
echo"<input type='hidden' name='QuantityReceived' id='quantityrecd' value='".$quantity_received."'>";

?>
