<?php

      
//    echo '<input type="hidden" name="CurrCode" value="INR">';
    echo '<input type="hidden" name="FactorID" value="4">';
    if(isset($_POST['SupplierID']))     {
     
     $fieldid=$_POST['SupplierID'];   
    }
    echo '<tr><td>' . _('Supplier Code') . "*</td><td><input type='hidden' id='supplierid' name='SupplierID' value=$fieldid>$fieldid</td></tr>";
    echo '<tr><td>' . _('Contact Name') . "*</td><td><input type='text' id='contactname' name='ContactName' value='".$_POST['ContactName']."' size=25 maxlength=40></td></tr>";
    echo '<tr><td>' . _('Company name') . "*</td><td><input type='text' id='suppliername' name='SuppName' value='".$_POST['SuppName']."' size=25 maxlength=10></td></tr>"; 
    
    $_POST['CurrCode']='INR';
    $result=DB_query('SELECT currency, currabrev FROM currencies', $db);

    echo '<tr><td>' . _('Supplier Currency') . ":</td><td><select name='CurrCode' style='width:180px;'>";
    while ($myrow = DB_fetch_array($result)) {
        if ($_POST['CurrCode'] == $myrow['currabrev']){
            echo '<option selected VALUE=' . $myrow['currabrev'] . '>' . $myrow['currency'];
        } else {
            echo '<option VALUE=' . $myrow['currabrev'] . '>' . $myrow['currency'];
        }
    } //end while loop
    echo '</select></td></tr>';
    //-----------------Additionalllllll .................
        $result=DB_query('SELECT terms, termsindicator FROM paymentterms', $db);

    echo '<tr><td>' . _('Payment Terms') . ":</td><td><select name='PaymentTerms' style='width:180px;'>";

    while ($myrow = DB_fetch_array($result)) {
        if ($_POST['PaymentTerms'] == $myrow['termsindicator']){
        echo '<option selected VALUE=' . $myrow['termsindicator'] . '>' . $myrow['terms'];
        } else {
        echo '<option VALUE=' . $myrow['termsindicator'] . '>' . $myrow['terms'];
        }
    } //end while loop
    

     
    //-----------------Additionalllllll .................   


    $DateString = Date($_SESSION['DefaultDateFormat']);
    echo '<tr><td>' . _('Supplier Since') . ' (' . $_SESSION['DefaultDateFormat'] . "):</td><td><input type='text' 
    class='date' alt='".$_SESSION['DefaultDateFormat']."' name='SupplierSince' VALUE=$DateString size=25 maxlength=10></td></tr>"; 
?>
