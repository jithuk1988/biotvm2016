<?php
  $PageSecurity = 11;

 include('includes/session.inc');
 
 $SupplierID=$_SESSION['SupplierID']; 
 
 if ($SupplierID=='')       {
     
     echo "No supplier has been selected";
 }else {
      
       $supplierID;
        $SupplierName = '';
    $SQL = "SELECT suppliers.suppname
        FROM suppliers
        WHERE suppliers.supplierid ='" . $_SESSION['SupplierID'] . "'";

    $SupplierNameResult = DB_query($SQL,$db); 
    if (DB_num_rows($SupplierNameResult)==1){
       $myrow = DB_fetch_row($SupplierNameResult);
       $SupplierName = $myrow[0];
    }
    echo '<table width=100% colspan=2 border=0 cellpadding=4>';
    echo "<tr>
        <th width=33%>" . _('Inquiries') . "</th>
        <th width=33%>". _('Transactions') . "</th>
        <th width=33%>" . _('Maintenance') . "</th>
    </tr>";
    echo '<tr><td VALIGN=TOP class="menu_group_items">';    /* Inquiry Options */
    echo "<a href=\"$rootpath/SupplierInquiry.php?" . SID . '&SupplierID=' . $_SESSION['SupplierID'] . "\">" . _('Supplier Account Inquiry') . '</a>';
    //echo '<br>';
    echo "<br><a href='$rootpath/PO_SelectOSPurchOrder.php?" . SID . '&SelectedSupplier=' . $_SESSION['SupplierID'] . "'>" . _('Add / Receive / View Outstanding Purchase Orders') . '</a>';
    echo "<br><a href='$rootpath/PO_SelectPurchOrder.php?" . SID . '&SelectedSupplier=' . $_SESSION['SupplierID'] . "'>" . _('View All Purchase Orders') . '</a>';
    //wikiLink('Supplier', $_SESSION['SupplierID']);    
    //echo '<br>';
    echo "<br><a href='$rootpath/Shipt_Select.php?" . SID . '&SelectedSupplier=' . $_SESSION['SupplierID'] . "'>" . _('Search / Modify / Close Shipments') . '</a>';
    echo '</td><td VALIGN=TOP class="menu_group_items">'; /* Supplier Transactions */
    echo "<a href=\"$rootpath/SupplierInvoice.php?" . SID . '&SupplierID=' . $_SESSION['SupplierID'] . "\">" . _('Enter a Suppliers Invoice') . '</a><br>';
    echo "<a href=\"$rootpath/SupplierCredit.php?" . SID . '&SupplierID=' . $_SESSION['SupplierID'] . "\">" . _('Enter a Suppliers Credit Note') . '</a><br>';
    echo "<a href=\"$rootpath/Payments.php?" . SID . '&SupplierID=' . $_SESSION['SupplierID'] . "\">" . _('Enter a Payment to, or Receipt from the Supplier') .'</a>';
    //echo '<br>';
    echo "<br><a href='$rootpath/ReverseGRN.php?" . SID . '&SupplierID=' . $_SESSION['SupplierID'] . "'>" . _('Reverse an Outstanding Goods Received Note (GRN)') . '</a>';
    echo '</td><td VALIGN=TOP class="menu_group_items">'; /* Supplier Maintenance */
//        echo '<a href="' . $rootpath . '/Suppliers.php?">' . _('Add a New Supplier') . '</a><br>';
//    echo "<a href=\"$rootpath/Suppliers.php?" . SID . '&SupplierID=' . $_SESSION['SupplierID'] . "\">" . _('Modify Or Delete Supplier Details') . '</a>';
    echo "<a onclick=bankdetails('".$_SESSION['SupplierID']."')>" . _('Add/Modify Bank Details') . '</a>';
    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
   
//      echo "<div  id='seas_mas_rp1'>"; 
//      echo'</div>';
 }

?>
