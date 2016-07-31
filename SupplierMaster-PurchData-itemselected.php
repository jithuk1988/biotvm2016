<?php

/* $Revision: 1.23 $ */

$PageSecurity = 4;

include('includes/session.inc');



if (isset($_GET['SupplierID'])){
    $SupplierID = trim(strtoupper($_GET['SupplierID']));
} elseif (isset($_POST['SupplierID'])){
    $SupplierID = trim(strtoupper($_POST['SupplierID']));
}

if (isset($_GET['StockID'])){
    $StockID = trim(strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
    $StockID = trim(strtoupper($_POST['StockID']));
}


if( isset($_POST['SupplierDescription']) ) {
    $_POST['SupplierDescription'] = trim($_POST['SupplierDescription']);
}


if (isset($SupplierID) AND $SupplierID!=''){               /*NOT EDITING AN EXISTING BUT SUPPLIER selected OR ENTERED*/
   $sql = "SELECT suppliers.suppname, suppliers.currcode FROM suppliers WHERE supplierid='$SupplierID'";

   $ErrMsg = _('The supplier details for the selected supplier could not be retrieved because');
   $DbgMsg = _('The SQL that failed was');
   $SuppSelResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

   if (DB_num_rows($SuppSelResult) ==1){
        $myrow = DB_fetch_array($SuppSelResult);
        $SuppName = $myrow['suppname'];
        $CurrCode = $myrow['currcode'];
   } else {
        prnMsg( _('The supplier code') . ' ' . $SupplierID . ' ' . _('is not an existing supplier in the database') . '. ' . _('You must enter an alternative supplier code or select a supplier using the search facility below'),'error');
        unset($SupplierID);
   }
}



if (isset($_GET['Delete'])){

   $sql = "DELETE FROM purchdata 
                   WHERE purchdata.supplierno='$SupplierID' 
                   AND purchdata.stockid='$StockID'
                   AND purchdata.effectivefrom='" . $_GET['EffectiveFrom'] . "'";
   $ErrMsg =  _('The supplier purchasing details could not be deleted because');
   $DelResult=DB_query($sql,$db,$ErrMsg);

   prnMsg( _('This purchasing data record has been successfully deleted'),'success');
  // unset ($SupplierID);
   
   
   
   $sql = "DELETE FROM supconstraints 
                   WHERE supconstraints.stockid='$StockID' 
                   AND supconstraints.supcode='$SupplierID'";
                   
   $ErrMsg =  _('The supplier purchasing details could not be deleted because');
   $DelResult=DB_query($sql,$db,$ErrMsg);

   prnMsg( _('This purchasing data record has been successfully deleted'),'success');
   unset ($SupplierID);
   
}

if (isset($StockID)){
    $result = DB_query("SELECT stockmaster.description, 
                                stockmaster.units, 
                                stockmaster.mbflag 
                        FROM stockmaster 
                        WHERE stockmaster.stockid='$StockID'",$db);
    $myrow = DB_fetch_row($result);
    if (DB_num_rows($result)==1){
           if ($myrow[2]=='D' OR $myrow[2]=='A' OR $myrow[2]=='K'){
            prnMsg( $StockID . ' - ' . $myrow[0] . '<p> ' . _('The item selected is a dummy part or an assembly or kit set part') . ' - ' . _('it is not purchased') . '. ' . _('Entry of purchasing information is therefore inappropriate'),'warn');
            include('includes/footer.inc');
            exit;
        } else {
            echo '<tr><td colspan=2>'.$myrow[0] . ' ('.$myrow[1].')</td></tr>';
           }
    } else {
          prnMsg( _('Stock Item') . ' - ' . $StockID . ' ' . _('is not defined in the database'), 'warn');
    }
}

if (!isset($StockID)) {
    $StockID='';
}


//echo _('Stock Code') . ':<input type=text name="StockID" value="' . $StockID . '" size=21 maxlength=20>';
//echo '    <input type=submit name="ShowSupplierDetails" VALUE="' . _('Show Suppliers') . '">';
//echo '    <a href="' . $rootpath . '/SelectProduct.php?' . SID . '">' . _('Select Product') . '</a>';
//echo '<hr>';

if (!isset($_GET['Edit'])){
   $sql = "SELECT  purchdata.supplierno,
                    suppliers.suppname,
                    purchdata.price,
                    suppliers.currcode,
                    purchdata.effectivefrom,
                    purchdata.suppliersuom,
                    purchdata.supplierdescription,
                    purchdata.leadtime,
                     purchdata.preferred,
                     supconstraints.minordqty,
                     supconstraints.maxordqty
         FROM purchdata INNER JOIN suppliers
                ON purchdata.supplierno=suppliers.supplierid ,supconstraints
                WHERE purchdata.stockid = '" . $StockID . "' AND
                      purchdata.supplierno='".$SupplierID."'
                AND supconstraints.stockid=purchdata.stockid
                AND supconstraints.supcode=purchdata.supplierno
            ORDER BY purchdata.effectivefrom DESC";

   $ErrMsg =  _('The supplier purchasing details for the selected part could not be retrieved because');
   $PurchDataResult = DB_query($sql, $db,$ErrMsg);


   if (DB_num_rows($PurchDataResult)==0){
          prnMsg( _('There is no purchasing data set up for the part selected'),'info');
   } // end of there are purchsing data rows to show
   else {
       
       $_GET['Edit']=1;
   }
} /* Only show the existing purchasing data records if one is not being edited */


/*Show the input form for new supplier purchasing details */

if (isset($_GET['Edit'])){

    $myrow = DB_fetch_array($PurchDataResult);

    $SuppName = $myrow['suppname'];
    $_POST['Price'] = $myrow['price'];
    $_POST['EffectiveFrom']=ConvertSQLDate($myrow['effectivefrom']);
    $CurrCode = $myrow['currcode'];
    $_POST['SuppliersUOM'] = $myrow['suppliersuom'];
    $_POST['SupplierDescription'] = $myrow['supplierdescription'];
    $_POST['LeadTime'] = $myrow['leadtime'];
    $_POST['MinOQ'] = $myrow['minordqty'];
    $_POST['MaxOQ'] = $myrow['maxordqty'];
    $_POST['ConversionFactor'] = $myrow['conversionfactor'];
    $_POST['Preferred'] = $myrow['preferred'];

    echo'<input type="hidden" name="UpdateRecord" value=1>';
}

echo '<table width=100%>';

if (!isset($SupplierID)) {
    $SupplierID = '';
}



if (!isset($CurrCode)) {
    $CurrCode = '';
}

if (!isset($_POST['Price'])) {
    $_POST['Price'] = 0;
}
if (!isset($_POST['EffectiveFrom'])) {
    $_POST['EffectiveFrom'] = Date($_SESSION['DefaultDateFormat']);
}
if (!isset($_POST['SuppliersUOM'])) {
    $_POST['SuppliersUOM'] = '';
}

if (!isset($_POST['SupplierDescription'])) {
    $_POST['SupplierDescription'] = '';
}

echo '<tr><td width=50%>' . _('Currency') . ':</td>
    <td><input type=hidden name="CurrCode" . VALUE="' . $CurrCode . '">' . $CurrCode . '</td></tr>';
echo '<tr><td>' . _('Price') . ':</td>
    <td><input type=TEXT class=number name="Price" maxlength=40 size=25 VALUE=' . $_POST['Price'] . '></td></tr>';
echo '<tr><td>' . _('Date Updated') . ':</td>
    <td><input type=TEXT class=date alt="'.$_SESSION['DefaultDateFormat'].'" name="EffectiveFrom" maxlength=40 size=25 VALUE="' . $_POST['EffectiveFrom'] . '"></td></tr>';
echo '<tr><td>' . _('Suppliers Unit of Measure') . ':</td>
    <td><input type=TEXT name="SuppliersUOM" maxlength=40 size=25 VALUE="' . $_POST['SuppliersUOM'] . '"></td></tr>';
if (!isset($_POST['ConversionFactor']) OR $_POST['ConversionFactor']==""){
   $_POST['ConversionFactor']=1;
}
echo '<tr><td>' . _('Conversion Factor (to our UOM)') . ':</td>
    <td><input type=TEXT class=number name="ConversionFactor" maxlength=40 size=25 VALUE=' . $_POST['ConversionFactor'] . '></td></tr>';
//echo '<tr><td>' . _('Supplier Code or Description') . ':</td>
//    <td><input type=TEXT name="SupplierDescription" maxlength=40 size=25 VALUE="' . $_POST['SupplierDescription'] . '"></td></tr>';
if (!isset($_POST['LeadTime']) OR $_POST['LeadTime']==""){
   $_POST['LeadTime']=1;
}
echo '<tr><td>' . _('Lead Time') . ' (' . _('in days from date of order') . '):</td>
    <td><input type=TEXT class=number name="LeadTime" maxlength=40 size=25 VALUE=' . $_POST['LeadTime'] . '></td></tr>';

echo '<tr><td>' . _('Min Order Qty') . ' :</td>
    <td><input type=TEXT class=number name="MinOQ" maxlength=40 size=25 VALUE=' . $_POST['MinOQ'] . '></td></tr>';
    
echo '<tr><td>' . _('Max Order Qty') . ':</td>
    <td><input type=TEXT class=number name="MaxOQ" maxlength=40 size=25 VALUE=' . $_POST['MaxOQ'] . '></td></tr>';    
echo'<input type="hidden" name="suppitems" value="1">';      
echo '<tr><td>' . _('Preferred Supplier') . ':</td>
    <td><select name="Preferred">';

if ($_POST['Preferred']==1){
    echo '<option selected VALUE=1>' . _('Yes');
    echo '<option VALUE=0>' . _('No');
} else {
    echo '<option VALUE=1>' . _('Yes');
    echo '<option selected VALUE=0>' . _('No');
}
echo '</select></td></tr></table><div class="centre">';

//if (isset($_GET['Edit'])){
//   echo '<input type=submit name="UpdateRecord" VALUE="' . _('Update') . '">';
//} else {
//   echo '<input type=submit name="AddRecord" VALUE="' . _('Add') . '">';
//}

echo '</div>';

if (isset($_POST['SearchSupplier'])){

    If (isset($_POST['Keywords']) AND isset($_POST['SupplierCode'])) {
        $msg=_('Supplier Name keywords have been used in preference to the Supplier Code extract entered') . '.';
    }
    If ($_POST['Keywords']=="" AND $_POST['SupplierCode']=="") {
        $msg=_('At least one Supplier Name keyword OR an extract of a Supplier Code must be entered for the search');
    } else {
        If (strlen($_POST['Keywords'])>0) {
            //insert wildcard characters in spaces

            $i=0;
            $SearchString = '%';
            while (strpos($_POST['Keywords'], ' ', $i)) {
                $wrdlen=strpos($_POST['Keywords'],' ',$i) - $i;
                $SearchString=$SearchString . substr($_POST['Keywords'],$i,$wrdlen) . '%';
                $i=strpos($_POST['Keywords'],' ',$i) +1;
            }
            $SearchString = $SearchString. substr($_POST['Keywords'],$i).'%';

            $SQL = "SELECT suppliers.supplierid,
                    suppliers.suppname,
                    suppliers.currcode,
                    suppliers.address1,
                    suppliers.address2,
                    suppliers.address3
                    FROM suppliers WHERE suppliers.suppname " . LIKE . " '$SearchString'";

        } elseif (strlen($_POST['SupplierCode'])>0){
            $SQL = "SELECT suppliers.supplierid,
                    suppliers.suppname,
                    suppliers.currcode,
                    suppliers.address1,
                    suppliers.address2,
                    suppliers.address3
                FROM suppliers
                WHERE suppliers.supplierid " . LIKE . " '%" . $_POST['SupplierCode'] . "%'";
        }

        $ErrMsg = _('The suppliers matching the criteria entered could not be retrieved because');
        $DbgMsg =  _('The SQL to retrieve supplier details that failed was');
        $SuppliersResult = DB_query($SQL,$db,$ErrMsg,$DbgMsg);

    } //one of keywords or SupplierCode was more than a zero length string
} //end of if search

$msg = '';

if (strlen($msg)>1){
     prnMsg($msg,'warn');
}


If (isset($SuppliersResult)) {

    echo '<table cellpadding=2 colspan=7 BORDER=2>';
    $TableHeader = '<tr><th>' . _('Code') . '</th>
                        <th>' . _('Supplier Name') . '</th>
                <th>' . _('Currency') . '</th>
                <th>' . _('Address 1') . '</th>
                <th>' . _('Address 2') . '</th>
                <th>' . _('Address 3') . '</th>
            </tr>';
    echo $TableHeader;

    $j = 1;

    while ($myrow=DB_fetch_array($SuppliersResult)) {

        printf("<tr><td><font size=1><input type=submit name='SupplierID' VALUE='%s'</font></td>
                <td><font size=1>%s</font></td>
                <td><font size=1>%s</font></td>
                <td><font size=1>%s</font></td>
                <td><font size=1>%s</font></td>
                <td><font size=1>%s</font></td>
            </tr>",
            $myrow['supplierid'],
            $myrow['suppname'],
            $myrow['currcode'],
            $myrow['address1'],
            $myrow['address2'],
            $myrow['address3']
            );

        $j++;
        If ($j == 11){
            $j=1;
            echo $TableHeader;
        }
//end of page full new headings if
    }
//end of while loop

    echo '</table>';

}
//end if results to show




?>
