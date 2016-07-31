<?php
  $PageSecurity = 11;

 include('includes/session.inc');
 
 $_POST['SupplierID']=$_GET['p']; 
 
 $_SESSION['SupplierID'] = $_POST['SupplierID'];  

 unset($_POST['New']);
         $sql = "SELECT suppliers.supplierid,
                        suppliers.suppname,
                        suppliers.address1,
                        suppliers.address2,
                        suppliers.address3,
                        suppliers.address4,
                        suppliers.currcode,
                        suppliers.suppliersince,
                        suppliers.paymentterms,
                        suppliers.bankpartics,
                        suppliers.bankref,
                        suppliers.bankact,
                        suppliers.remittance,
                        suppliers.taxgroupid,
                        suppliers.factorcompanyid,
                        suppliers.taxref,
                        suppliercontacts.contact,
                        suppliercontacts.tel,
                        suppliercontacts.fax,
                        suppliercontacts.mobile,
                        suppliercontacts.email
                        
                   FROM suppliers,suppliercontacts
                   WHERE suppliers.supplierid = '".$_POST['SupplierID']."' AND
                         suppliercontacts.supplierid=suppliers.supplierid";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        $_POST['SuppName'] = stripcslashes($myrow['suppname']);
        $_POST['Address1']  = stripcslashes($myrow['address1']);
        $_POST['Address2']  = stripcslashes($myrow['address2']);
        $_POST['Address3']  = stripcslashes($myrow['address3']);
        $_POST['Address4']  = stripcslashes($myrow['address4']);
        $_POST['CurrCode']  = stripcslashes($myrow['currcode']);
        $_POST['SupplierSince']  = ConvertSQLDate($myrow['suppliersince']);
        $_POST['PaymentTerms']  = $myrow['paymentterms'];
        $_POST['BankPartics']    = stripcslashes($myrow['bankpartics']);
        $_POST['Remittance']  = $myrow['remittance'];
        $_POST['BankRef']  = stripcslashes($myrow['bankref']);
        $_POST['BankAct']  = $myrow['bankact'];
        $_POST['TaxGroup'] = $myrow['taxgroupid'];
        $_POST['FactorID'] = $myrow['factorcompanyid'];
        $_POST['TaxRef'] = $myrow['taxref'];
        $_POST['ContactName']=$myrow['contact'];
        $_POST['Phone']=$myrow['tel'];
        $_POST['Mobile']=$myrow['mobile'];
        $_POST['Fax']=$myrow['fax'];
        $_POST['Email']=$myrow['email'];
        
        echo'<input type="hidden" id="suppselected" name="SuppSelected" value=1>';
       
 echo '<table width=100%><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
  echo "<fieldset id='left_panel_1'>";     
  echo"<legend><h3>General Details</h3>";
  echo"</legend>"; 
    echo'<table>'; 
    include('SupplierMaster-leftpanel1.php');    
    echo'</table>'; 
       echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px>';
   echo "<div  id='seas_mas_rp1' style='height:270px;overflow:auto;'>";    
  echo "<fieldset id='right_panel_1' id='suppliermaster_right_panel_1'>";     
  echo"<legend><h3>Contact Details</h3>";
  echo"</legend>";
    echo'<table>';
include('SupplierMaster-rightpanel1.php'); 
echo"</table>";  

    echo"</td></tr></table>";
    echo "</fieldset>"; 
    echo '</div>';

    
    echo"</td></tr></table>";   
?>
