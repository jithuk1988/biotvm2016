<?php
  $PageSecurity = 11;

 include('includes/session.inc');
  $_POST['SupplierID']=$_GET['p'];  
  unset($_POST['New']); 
  
         $sql = "SELECT suppliers.bankpartics,
                        suppliers.bankref,
                        suppliers.bankact,
                        suppliers.remittance,
                        suppliers.taxgroupid,
                        suppliers.taxref
                        
                   FROM suppliers
                   WHERE suppliers.supplierid = '".$_POST['SupplierID']."' ";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        
        $_POST['BankPartics']    = stripcslashes($myrow['bankpartics']);
        $_POST['Remittance']  = $myrow['remittance'];
        $_POST['BankRef']  = stripcslashes($myrow['bankref']);
        $_POST['BankAct']  = $myrow['bankact'];
        $_POST['TaxGroup'] = $myrow['taxgroupid'];
        $_POST['TaxRef'] = $myrow['taxref'];

         
  echo "<fieldset class='right_panel_1'>";     
  echo"<legend><h3>Bank Details</h3>";
  echo"</legend>";
  echo'<table>';
    echo'<input type="hidden" name=Bankdetails value=1>';
    echo '<tr><td>' . _('Bank Particulars') . ":</td><td><input type='text' name='BankPartics' value='".$_POST['BankPartics']."' size=13 maxlength=12></td></tr>";
    echo '<tr><td>' . _('Bank reference') . ":</td><td><input type='text' name='BankRef' VALUE='".$_POST['BankRef']."' size=13 maxlength=12></td></tr>";
    echo '<tr><td>' . _('Bank Account No') . ":</td><td><input type='text' name='BankAct' value='".$_POST['BankAct']."' size=31 maxlength=30></td></tr>";
    echo '<tr><td>' . _('Remittance Advice') . ":</td><td><select name='Remittance'>";
    echo '<option VALUE=0>' . _('Not Required');
    echo '<option VALUE=1>' . _('Required');

    echo '</select></td></tr>';

    echo '<tr><td>' . _('Tax Group') . ":</td><td><select name='TaxGroup'>";

    DB_data_seek($result, 0);

    $sql = 'SELECT taxgroupid, taxgroupdescription FROM taxgroups';
    $result = DB_query($sql, $db);

    while ($myrow = DB_fetch_array($result)) {
        if (isset($_POST['TaxGroup']) and $_POST['TaxGroup'] == $myrow['taxgroupid']){
            echo '<option selected VALUE=' . $myrow['taxgroupid'] . '>' . $myrow['taxgroupdescription'];
        } else {
            echo '<option VALUE=' . $myrow['taxgroupid'] . '>' . $myrow['taxgroupdescription'];
        }
    } //end while loop

    echo "</select></td></tr>";
  echo"</table>";
  echo "</fieldset>";
 
?>
