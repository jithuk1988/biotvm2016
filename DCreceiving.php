<?php
  $PageSecurity = 2;
$PricesSecurity = 9;

include('includes/session.inc');

$title = _('Receiving Stock');

include('includes/header.inc');
$pagetype=2;
include('includes/sidemenu.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
include('includes/DefineSerialItems.php');
include('includes/DefineStockTransfers.php');

if ( isset($_SESSION['Transfer']) and $_SESSION['Transfer']->TrfID == ''){
    unset($_SESSION['Transfer']);
}

?>
<script type="text/javascript">

$(document).ready(function(){  
 
  $("#selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

});

function viewGRNS(str)     {
    
str=document.getElementById("item").value;


myRef = window.open('DCreceiving-viewGRNs.php?id='+ str,'estr1');    
    
}

function GRNreportpanel(str1)
{

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("dcreceiving-right_panel_1").innerHTML=xmlhttp.responseText;  
    document.getElementById('mahasaritem').focus(); 
    }
  } 
xmlhttp.open("GET","DCreceiving-grnreportpanel.php?p=" + str1,true);
xmlhttp.send();    
}

function viewGRNSreport(str)     {
    
str=document.getElementById("dcreceivingsupplier").value;
str2=document.getElementById("dcreceivingitem").value;

myRef = window.open('DCreceiving-viewGRNs-report.php?supplier='+ str + '&item=' + str2,'estr1');    
    
}





function showDispathes(str1)
{
//   alert(str1);
 
if (str1=="")
  {
  document.getElementById("left_panel_1").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("left_panel_1").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","DCreceivingdetails.php?p=" + str1,true);
xmlhttp.send();    
}


</script>

<?php
//$fieldid=get_fieldid('stockid','dev_donatingmaterials',$db);
echo '<p class="page_title_text">' . ' ' . _('Receiving Stock') . '';

function clearfields()      {

 unset($_POST['DespatchedQty']);
 unset($_POST['QuantityReceived']);
 unset($_POST['Item']);
 unset($_POST['DCNo']);
 unset($_POST['StockID']); 
 unset($_POST['BatchNo']);
 unset($_POST['Date']);
 unset($_POST['Quantity']);
 unset($PeriodNo);
 unset($grn_number);
 unset($balance_qty);
 unset($DCNo);
 unset($batch_no);
 unset($receiving_date);
 unset($receiving_quantity);
 unset($itemcode);
// unset();
// unset();
// unset();
// unset();
// unset();
// unset();
// unset();
 
}

//if (isset($_POST['submit'])) {
//    
//    $despatched_qty=$_POST['DespatchedQty'];
//    $received_qty=$_POST['QuantityReceived'];
//    $itemcode=$_POST['Item'];
//    $DCNo=$_POST['DCNo'];
//    $batch_no=$_POST['BatchNo'];
//    $receiving_date=FormatDateForSQL($_POST['Date']);
//    $receiving_quantity=$_POST['Quantity'];
//    $PeriodNo = GetPeriod ($receiving_date, $db);
//    
//    $SQL="SELECT locstock.quantity
//                    FROM locstock
//                    WHERE locstock.stockid='" . $itemcode . "'
//                    AND loccode= '" . 1 . "'";
//                    
//    $Result = DB_query($SQL, $db,  _('Could not retrieve the quantity on hand at the location being transferred to') );
//                if (DB_num_rows($Result)==1){
//                    $LocQtyRow = DB_fetch_row($Result);
//                    $QtyOnHandPrior = $LocQtyRow[0];
//                } else {
                    // There must actually be some error this should never happen
//                    $QtyOnHandPrior = 0;
//                }
//    $sql1="SELECT grnno
//       FROM grns
//       ORDER BY grnno
//       DESC LIMIT 1";  
//    $result1=DB_query($sql1,$db);
//    $value1=DB_fetch_array($result1);   
//    $grn_number=$value1[0]+1;
//    
//    $sql2="SELECT description FROM stockmaster WHERE stockid='".$itemcode."'";
//    $result2 = DB_query($sql2,$db);
//    $myrow2=DB_fetch_array($result2);
//    $item_description=$myrow2['description'];
//    
//    $sql3="SELECT purchorderdetails.podetailitem
//            FROM purchorderdetails, dispatchclearance
//            WHERE purchorderdetails.orderno = dispatchclearance.pono
//            AND dispatchclearance.dispatchclrno =".$DCNo;
//    $result3 = DB_query($sql3,$db);
//    $myrow3=DB_fetch_array($result3);
//    $podetal_item=$myrow3['podetailitem'];
//    
//    $sql4="SELECT purchorders.supplierno
//            FROM purchorders, dispatchclearance
//            WHERE purchorders.orderno = dispatchclearance.pono
//            AND dispatchclearance.dispatchclrno =".$DCNo;
//    $result4 = DB_query($sql4,$db);
//    $myrow4=DB_fetch_array($result4);
//    $supplierno=$myrow4['supplierno'];
//    
//   
//    $SQL = "INSERT INTO grns (
//                        grnbatch,
//                        grnno,
//                        podetailitem,
//                        itemcode,
//                        deliverydate,
//                        itemdescription,
//                        qtyrecd,
//                        supplierid)
//                    VALUES (
//                        0,
//                        ".$grn_number.",
//                        " . $podetal_item . ",
//                        '" . $itemcode . "',
//                        '" . $receiving_date . "',
//                        '" . $item_description . "',
//                        '" . $receiving_quantity ."',
//                        " . $supplierno . ")";
//   
//   $Result=DB_query($SQL,$db);
//  
//   
   //$sql_trans="SELECT grns.grnno,grns.podetailitem 
//            FROM grns, purchorderdetails, dispatchclearance
//            WHERE grns.podetailitem = purchorderdetails.podetailitem 
//            AND dispatchclearance.pono = purchorderdetails.orderno
//            AND dispatchclearance.dispatchclrno=".$DCNo;
//    
//    echo"no=". $myrow_trans['grnno'];
//    
//    $SQL = "INSERT INTO stockmoves (
//                        stockid,
//                        type,
//                        transno,
//                        loccode,
//                        trandate,
//                        prd,
//                        reference,
//                        qty,
//                        newqoh)
//                    VALUES (
//                        '" . $itemcode . "',
//                        25,
//                        " . $grn_number . ",
//                        '" . 1 . "',
//                        '" . $receiving_date . "',
//                        " . $PeriodNo . ",
//                        '" . 0 ."',
//                        " . $receiving_quantity . ",
//                         " . ($QtyOnHandPrior + $receiving_quantity) . "
//                        )";
//                        
//    $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
//    
//    $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');
//                echo $StkMoveNo;
//     $SQL = "INSERT INTO stockserialitems (stockid,
//                                            loccode,
//                                            serialno,
//                                            quantity,
//                                            qualitytext)
//                                VALUES ('" . $itemcode . "',
//                                '" . 1 . "',
//                                '" .$batch_no . "',
//                                " .$receiving_quantity . ",
//                                '')";
//    $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
//    
//     $SQL = "INSERT INTO stockserialmoves (stockmoveno,
//                                            stockid,
//                                            serialno,
//                                            moveqty)
//                                VALUES (" . $StkMoveNo . ",
//                                    '" . $itemcode . "',
//                                    '" . $batch_no . "',
//                                    " .$receiving_quantity . ")";
//    $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
//    
//    $sql_purch="UPDATE purchorderdetails SET
//                                        quantityrecd=".$receiving_quantity."
//                                        WHERE podetailitem=".$podetal_item;
//    $Result_purch= DB_query($sql_purch, $db, $ErrMsg, $DbgMsg, true);
//    $balance_qty=0;
//    $balance_qty=$despatched_qty-$received_qty;
//    
//    if($despatched_qty==$receiving_quantity){
//        echo"haii";
//       $sql_status="UPDATE dispatchclearance SET
//                                        qtyrecd=".$receiving_quantity.", 
//                                        dcstatusid=3 
//                              WHERE dispatchclrno=".$DCNo;
//       $Result_status= DB_query($sql_status, $db, $ErrMsg, $DbgMsg, true); 
//    }elseif($balance_qty==$receiving_quantity){
//        echo"hhhhh";
//        $received_qty=$received_qty+$receiving_quantity;
//        $sql_status="UPDATE dispatchclearance SET
//                                        qtyrecd=".$received_qty.", 
//                                        dcstatusid=3 
//                              WHERE dispatchclrno=".$DCNo;
//        $Result_status= DB_query($sql_status, $db, $ErrMsg, $DbgMsg, true);
//    }else{
//        echo"dddd";
//        $received_qty=$received_qty+$receiving_quantity;
//        $sql_status="UPDATE dispatchclearance SET
//                                        qtyrecd=".$received_qty.", 
//                                        dcstatusid=2 
//                              WHERE dispatchclrno=".$DCNo;
//        $Result_status= DB_query($sql_status, $db, $ErrMsg, $DbgMsg, true);
//    }
//    echo '<br><a href=PDFSrecn.php?slno=' . $StkMoveNo .'>'. _('Print this Stock Transfer Report').$DCNo.'</a><br><br>';
//    prnMsg('Stock received successfully','success');                                   
//    clearfields();
//}







 if (isset($_POST['submit'])) {
    
    $despatched_qty=$_POST['DespatchedQty'];
    $received_qty=$_POST['QuantityReceived'];
    $itemcode=$_POST['Item'];
    $DCNo=$_POST['DCNo'];
    $batch_no=$_POST['BatchNo'];
    $receiving_date=FormatDateForSQL($_POST['Date']);
    $receiving_quantity=$_POST['Quantity'];
    $PeriodNo = GetPeriod ($receiving_date, $db);
    
    $SQL="SELECT locstock.quantity
                    FROM locstock
                    WHERE locstock.stockid='" . $itemcode . "'
                    AND loccode= '" . 1 . "'";
                    
    $Result = DB_query($SQL, $db,  _('Could not retrieve the quantity on hand at the location being transferred to') );
                if (DB_num_rows($Result)==1){
                    $LocQtyRow = DB_fetch_row($Result);
                    $QtyOnHandPrior = $LocQtyRow[0];
                } else {
                    // There must actually be some error this should never happen
                    $QtyOnHandPrior = 0;
                }
                
    if(!isset($_POST['GRNO']))      {
    $sql1="SELECT grnno
       FROM grns
       ORDER BY grnno
       DESC LIMIT 1";  
    $result1=DB_query($sql1,$db);
    $value1=DB_fetch_array($result1);   
    $grn_number=$value1[0]+1;
    
    }else       {
        
     $grn_number=$_POST['GRNO'];   
        
    }
    $sql2="SELECT * FROM stockmaster WHERE stockid='".$itemcode."'";
    $result2 = DB_query($sql2,$db);
    $myrow2=DB_fetch_array($result2);
    $item_description=$myrow2['description'];
    $controlled=$myrow2['controlled'];
    
    $sql3="SELECT purchorderdetails.podetailitem,
                  purchorderdetails.quantityrecd,
                  purchorderdetails.unitprice
            FROM purchorderdetails, dispatchclearance
            WHERE purchorderdetails.orderno = dispatchclearance.pono
            AND dispatchclearance.itemcode=purchorderdetails.itemcode
            AND dispatchclearance.dispatchclrno =".$DCNo;
    $result3 = DB_query($sql3,$db);
    $myrow3=DB_fetch_array($result3);
    $podetal_item=$myrow3['podetailitem'];
    $purch_qtyrecd=$myrow3['quantityrecd'];
    $purch_price=$myrow3['unitprice'];
    
    
    $sql4="SELECT purchorders.supplierno
            FROM purchorders, dispatchclearance
            WHERE purchorders.orderno = dispatchclearance.pono
            AND dispatchclearance.dispatchclrno =".$DCNo;
    $result4 = DB_query($sql4,$db);
    $myrow4=DB_fetch_array($result4);
    $supplierno=$myrow4['supplierno'];
    
   
    $SQL = "INSERT INTO grns (
                        grnbatch,
                        grnno,
                        podetailitem,
                        itemcode,
                        deliverydate,
                        itemdescription,
                        qtyrecd,
                        supplierid)
                    VALUES (".$grn_number.",
                        ".$grn_number.",
                        " . $podetal_item . ",
                        '" . $itemcode . "',
                        '" . $receiving_date . "',
                        '" . $item_description . "',
                        '" . $receiving_quantity ."',
                        " . $supplierno . ")";
   
   $Result=DB_query($SQL,$db);
  
   
   //$sql_trans="SELECT grns.grnno,grns.podetailitem 
//            FROM grns, purchorderdetails, dispatchclearance
//            WHERE grns.podetailitem = purchorderdetails.podetailitem 
//            AND dispatchclearance.pono = purchorderdetails.orderno
//            AND dispatchclearance.dispatchclrno=".$DCNo;
    
//    echo"no=". $myrow_trans['grnno'];
    $price=$receiving_quantity*$purch_price;
   
    $SQL = "INSERT INTO stockmoves (
                        stockid,
                        type,
                        transno,
                        loccode,
                        trandate,
                        price,
                        prd,
                        reference,
                        qty,
                        newqoh)
                    VALUES (
                        '" . $itemcode . "',
                        25,
                        " . $grn_number . ",
                        '" . 1 . "',
                        '" . $receiving_date . "',
                        " .$price . ",
                        " . $PeriodNo . ",
                        '" . 0 ."',
                        " . $receiving_quantity . ",
                         " . ($QtyOnHandPrior + $receiving_quantity) . "
                        )";
                        
    $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
    
    $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');
//                echo $StkMoveNo;

     if ($controlled ==1){
         /*We need to add or update the StockSerialItem record and
                    The StockSerialMoves as well */

                        /*First need to check if the serial items already exists or not in the location to */
                     $SQL = "SELECT COUNT(*)
                            FROM stockserialitems
                            WHERE stockid='" . $itemcode . "'
                            AND loccode='" . 1 . "'
                            AND serialno='" . $batch_no . "'";

                        $Result = DB_query($SQL,$db,'<br>'. _('Could not determine if the serial item exists') );
                        $SerialItemExistsRow = DB_fetch_row($Result);


                        if ($SerialItemExistsRow[0]==1){

                            $SQL = "UPDATE stockserialitems SET
                                quantity= quantity + " .$receiving_quantity . "
                                WHERE stockid='" . $itemcode . "'
                                AND loccode='" . 1 . "'
                                AND serialno='" . $batch_no . "'";

                            $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated for the quantity coming in because');
                            $DbgMsg =  _('The following SQL to update the serial stock item record was used');
                            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        } else {
                            /*Need to insert a new serial item record */
                            $SQL = "INSERT INTO stockserialitems (stockid,
                                                                  loccode,
                                                                  serialno,
                                                                  quantity,
                                                                  qualitytext)
                                                          VALUES ('" . $itemcode . "',
                                                                  '" . 1 . "',
                                                                  '" . $batch_no . "',
                                                                  " . $receiving_quantity . ",
                                                                  '')";

                            $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record for the stock coming in could not be added because');
                            $DbgMsg =  _('The following SQL to update the serial stock item record was used');
                            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        }
                        
                        /* now insert the serial stock movement */

                        $SQL = "INSERT INTO stockserialmoves (stockmoveno,
                                            stockid,
                                            serialno,
                                            moveqty)
                                VALUES (" . $StkMoveNo . ",
                                    '" . $itemcode . "',
                                    '" . $batch_no . "',
                                    " .$receiving_quantity . ")";
                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                        $DbgMsg =  _('The following SQL to insert the serial stock movement records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
     } /*end if the transfer item is a controlled item */
     
     
     $SQL = "UPDATE locstock
                    SET quantity = quantity + " . $receiving_quantity . "
                    WHERE stockid='" . $itemcode . "'
                    AND loccode='" . 1 . "'";

                $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
                $DbgMsg =  _('The following SQL to update the stock record was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                
                 //$SQL = "UPDATE loctransferstatus
//                    SET status ='1'
//                    WHERE reference='" .  $_SESSION['Transfer']->TrfID . "'
//                   ";

//                $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
//                $DbgMsg =  _('The following SQL to update the stock record was used');
//                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                
                
                
                //$query="SELECT  requestno
//                          FROM loctransferstatus
//                          WHERE reference='" .  $_SESSION['Transfer']->TrfID . "'
//                  " ;
//                 
//        $ErrMsg = _('Could not retrieve the details of the selected item');   
//        $result=DB_query($query,$db,$ErrMsg);
//$myrow=DB_fetch_array($result);
//$slno=$myrow[0];
                
                
                  //$sql="UPDATE stocktransfer
//                       SET reced=1
//                        WHERE slno='$slno'
//                       AND itemcode='$TrfLine->StockID '
//                       ";
//           
//                 $ErrMsg =  _('The status could not be updated because');
//                 $DbgMsg = _('The SQL statement used to process the request that failed was');
//                 $result =DB_query($sql,$db,$ErrMsg,$DbgMsg);
     
     
 $purchasedquantity=$purch_qtyrecd+$receiving_quantity;     
  $sql_purch="UPDATE purchorderdetails SET
                                        quantityrecd=".$purchasedquantity."
                                        WHERE podetailitem=".$podetal_item;
    $Result_purch= DB_query($sql_purch, $db, $ErrMsg, $DbgMsg, true);
//    $balance_qty=0;
    $balance_qty=$despatched_qty-$received_qty;
    
    if($despatched_qty==$receiving_quantity){
//        echo"haii";
 $sql_status="UPDATE dispatchclearance SET
                                        qtyrecd=".$receiving_quantity.", 
                                        dcstatusid=3 
                              WHERE dispatchclrno=".$DCNo;
       $Result_status= DB_query($sql_status, $db, $ErrMsg, $DbgMsg, true); 
    }elseif($balance_qty==$receiving_quantity){
//        echo"hhhhh";
        $received_qty=$received_qty+$receiving_quantity;
  $sql_status="UPDATE dispatchclearance SET
                                        qtyrecd=".$received_qty.", 
                                        dcstatusid=3 
                              WHERE dispatchclrno=".$DCNo;
        $Result_status= DB_query($sql_status, $db, $ErrMsg, $DbgMsg, true);
    }else{
//        echo"dddd";
        $received_qty=$received_qty+$receiving_quantity;
       $sql_status="UPDATE dispatchclearance SET
                                        qtyrecd=".$received_qty.", 
                                        dcstatusid=2 
                              WHERE dispatchclrno=".$DCNo;
        $Result_status= DB_query($sql_status, $db, $ErrMsg, $DbgMsg, true);
    }
    echo '<br><a href=PDFSrecn.php?slno=' . $StkMoveNo .'&grnno='.$grn_number.'>'. _('Print this Stock Receiving Note ').$DCNo.'</a>';
    echo '<br><a href=PDFBillpayment.php?slno=' . $StkMoveNo .'&dcno='.$DCNo.'>'. _('Print this Bill Payment Note ').$DCNo.'</a><br><br>';
    prnMsg('Stock received successfully','success');                                   
    clearfields();

 }



echo"<div id=fullbody>";
echo '<form name="ReceivingStockForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';

echo"<div class=panels id=panel_dcreceiving>";
panel($db); 
echo"</div>";

echo"<p><div class='centre' id='buttons'>";

buttons($db);

echo '</div></form>';

echo"<div id='selectiondetails'>";

selectiondetails($db);

echo "</div>";

echo"<div class='Datagrid' >";

datagrid($db);

echo "</div>";


echo"</div>";

function panel(&$db){
    
//--------------------------------------------------------------Start of Left Panel1
echo '<table width=100%><tr>';
echo'<td width=50%>';
echo'<div id=left_panel_1>';
echo"<fieldset id='left_panel_1' style='height:180px;'>"; 
echo"<legend><h3>Receive Stock</h3>";
echo"</legend>";
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
    if (isset($_GET['Item']) and $myrow['description']==$_GET['Item']) {
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
    <td><input type='text' name='DCNo' id='dcno'></td>
    </tr>";
    


//$sql1="SELECT stkmoveno
//       FROM stockmoves
//       ORDER BY stkmoveno
//       DESC LIMIT 1";  
//$result1=DB_query($sql1,$db);
// $value1=DB_fetch_array($result1);   
// $batch_no=$value1[0]+1;
 
 $batch_no=1;
 
  
    
    
echo"<tr>
    <td>". _('Batch No') .":</td>
    <td><input type='text' name='BatchNo' id='batchno' value='".$batch_no."'></td>
    </tr>";
    
echo"<tr>
    <td>". _('Date') .":</td>
    <td><input type='Text' name='Date' id='date' class=date alt='".$_SESSION['DefaultDateFormat']. "' ></td>
    </tr>";
    
echo"<tr>
    <td>". _('Quantity') .":</td>
    <td><input type='text' name='Quantity' id='quantity'></td>
    </tr>";


echo"</table>";
echo"</fieldset>";
echo"</td>";

echo'<td width=50% id="dcreceiving-right_panel_1">';    
//echo "<fieldset id='right_panel_1' style='height:370px;'>";     
//echo"<legend><h3>Add Inventory Item</h3>";
//echo"</legend>";
//echo'<table>';


//echo "</table>";
//echo "</fieldset>"; 
echo"</td></tr></table>"; 

}

function buttons(&$db){
    
    echo "<table ><tr>";
    echo "<td><input type='Submit' name='submit' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
//    echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "'></td>";
    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "</tr></table>";
    
}

function selectiondetails(&$db){
    
     echo '<table width=100% colspan=2 border=2 cellpadding=4>
    <tr>
        <th width="33%">' . _('Item Inquiries') . '</th>
        <th width="33%">' . _('Item Transactions') . '</th>
        <th width="33%">' . _('Item Maintenance') . '</th>
    </tr>';
echo"<tr><td  VALIGN=TOP class='menu_group_items'>";
echo "<a style='cursor:pointer;' id='1' onclick='viewGRNS(this.id)'>" . _('View GRNs') . '</a><br>';
echo "<a style='cursor:pointer;' id='2' onclick='GRNreportpanel(this.id)'>" . _('View GRNs-Report') . '</a><br>';
echo"</td></tr>";


echo'</table>';
}


function datagrid(&$db){

    
    
    echo '<table width=100%>
    <tr>
        <th width="10%">' . _('Sl.No') . '</th>
        <th width="33%">' . _('Supplier') . '</th>
        <th width="33%">' . _('Item') . '</th>
        <th width="33%">' . _('Qty Received') . '</th>
        <th width="33%">' . _('Balance Qty') . '</th>
        <th width="33%">' . _('Date') . '</th>
        
    </tr>';
    
    $sql_list='SELECT * 
               FROM dispatchclearance,purchorders,suppliers
               WHERE dcstatusid=2       AND
                     dispatchclearance.pono=purchorders.orderno     AND
                     purchorders.supplierno=suppliers.supplierid
                     ORDER BY suppliers.supplierid';
    $result_list = DB_query($sql_list,$db);
    
    $k=0; //row colour counter 
    $slno=0;
    
    while ($myrow = DB_fetch_array($result_list)) {
        if ($k==1){
            echo '<tr class="EvenTableRows" id="'.$myrow['dispatchclrno'].'" onclick=showDispathes(this.id)>';
            $k=0;
        } else {
            echo '<tr class="OddTableRows" id="'.$myrow['dispatchclrno'].'" onclick=showDispathes(this.id)>';
            $k=1;
        }
        
        $date=ConvertSQLDate($myrow['deliverydate']);
        $sql2="SELECT description FROM stockmaster WHERE stockid='".$myrow['itemcode']."'";
        $ErrMsg = _('The SQL to find the parts selected failed with the message');
        $result2 = DB_query($sql2,$db,$ErrMsg);
        $myrow2=DB_fetch_array($result2);
        $balanceqty=$myrow['quantity']-$myrow['qtyrecd'];
    
        $slno++;
    
        printf("<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $slno,
            $myrow['suppname'],
            $myrow2['description'],
            $myrow['qtyrecd'],
            $balanceqty,
            $date);
    }
    
    
echo'<tfoot><tr>';
echo'<td colspan=10>Number of records:'.$slno.'</td>';
echo'</tr></tfoot>';    
echo'</table>'; 
}


?>
