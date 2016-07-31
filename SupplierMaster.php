<?php
$PageSecurity = 5;

include('includes/session.inc');
$title = _('Supplier Master');
include('includes/header.inc');

$pagetype=3;
include('includes/sidemenu.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript">
 
$(document).ready(function(){
 document.forms[0].ContactName.focus();  
   
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
        
  $("#selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {

  });
}); 

         
}); //--------ready()

function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{

if (str1=="")
  {
  document.getElementById("panel_suppmas").innerHTML="";
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

    document.getElementById("panel_suppmas").innerHTML=xmlhttp.responseText;
    document.forms[0].contactname.focus(); 
    }
  }

xmlhttp.open("GET","SupplierMaster-panels.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
} //-------------------showdetails()

function supplieritemledger()       {
    
var suppcode=document.getElementById("supplierid").value;
   
}

 function supplierreports(str1,str2,str3,str4,str5,str6,str7,str8)
{
var suppname=document.getElementById("suppliername").value;
var suppcode=document.getElementById("supplierid").value;
if (suppname=="")
  {
  alert("Select a supplier from the Data Grid");
  return;
  }
myRef = window.open('SupplierMaster-reports-ledger.php?id='+ suppcode,'estr1');
} //-------------------showdetails()


function bankdetails(str1)      {
var v=document.getElementById("supplierid").value;  
     
if (v=="")
  {
  document.getElementById("seas_mas_rp1").innerHTML="";
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

    document.getElementById("seas_mas_rp1").innerHTML=xmlhttp.responseText;
//    document.forms[0].taskname.focus(); 
    }
  }

xmlhttp.open("GET","Supplier-Bankdetails.php?p=" + v  ,true);     
xmlhttp.send();     
    
}  //------------------bankdetails()                supplieritems

function supplieritems(str1,itemselected)      {
var v=document.getElementById("supplierid").value; 
var itemsel=1;      
if (v=="")
  {
  document.getElementById("seas_mas_rp1").innerHTML="";
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

    document.getElementById("seas_mas_rp1").innerHTML=xmlhttp.responseText;
//    document.forms[0].taskname.focus(); 
    }
  }

xmlhttp.open("GET","SupplierMaster-PurchData.php?p=" + v + "&itemselection=" + itemsel,true);     
xmlhttp.send();     
    
}  //------------------supplieritems()   suppieritemselected

function suppieritemselected(str1)      {
var v=document.getElementById("supplierid").value; 
var itemsel=1;      
if (v=="")
  {
  document.getElementById("SupplierMaster_right_itemselected").innerHTML="";
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

    document.getElementById("SupplierMaster_right_itemselected").innerHTML=xmlhttp.responseText;
//    document.forms[0].taskname.focus(); 
    }
  }

xmlhttp.open("GET","SupplierMaster-PurchData-itemselected.php?SupplierID=" + v + "&StockID=" + str1,true);     
xmlhttp.send();     
    
}  //------------------suppieritemselected()
</script>
<?php
if(isset($_POST['clear']))     {  

unset ($_POST['SuppName']);
unset ($_POST['Address1']);
unset ($_POST['Address2']);
unset ($_POST['Address3']);
unset ($_POST['Address4']);
unset ($_POST['CurrCode']);
unset ($_POST['SupplierSince']);
unset ($_POST['PaymentTerms']);
unset ($_POST['BankPartics']);
unset ($_POST['Remittance']);
unset ($_POST['BankRef']);
unset ($_POST['BankAct']);
unset ($_POST['TaxGroup']);
unset ($_POST['FactorID']);
unset ($_POST['TaxRef']);
unset ($_POST['ContactName']);
unset ($_POST['Phone']);
unset ($_POST['Mobile']);
unset ($_POST['Fax']);
unset ($_POST['Email']);                    
unset ($_POST['SupplierID']); 
unset ($_SESSION['SupplierID']);

} //-----------------clear
unset($_SESSION['SupplierID']);
if (isset($_GET['SupplierID'])){
    $SupplierID = strtoupper($_GET['SupplierID']);
} elseif (isset($_POST['SupplierID'])){
   $SupplierID = strtoupper($_POST['SupplierID']);
} else {
    unset($SupplierID);
}

if (isset($_GET['StockID'])){
    $StockID = trim(strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
    $StockID = trim(strtoupper($_POST['StockID']));
}
// This is aleady linked from this page
//echo "<a href='" . $rootpath . '/SelectSupplier.php?' . SID . "'>" . _('Back to Suppliers') . '</a><br>';
echo '<p class="page_title_text">' . ' ' . _('Suppliers') . '';

$InputError = 0;

if (isset($Errors)) {
    unset($Errors);
}

$Errors=Array();

if(isset($_POST['delete']))     {

    delete($SupplierID,$db);
}
if ((isset($_POST['save']))  AND (!isset($_POST['suppitems']))) {

    //initialise no input errors assumed initially before we test
    $i=1;
    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible
    $sql="SELECT COUNT(supplierid) FROM suppliers WHERE supplierid='".$SupplierID."'";
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_row($result);
    if ($myrow[0]>0 and isset($_POST['New'])) {
        $InputError = 1;
        prnMsg( _('The supplier number already exists in the database'),'error');
        $Errors[$i] = 'ID';
        $i++;
    }
    if (strlen($_POST['SuppName']) > 40 or strlen($_POST['SuppName']) == 0 or $_POST['SuppName'] == '') {
        $InputError = 1;
        prnMsg(_('The supplier name must be entered and be forty characters or less long'),'error');
        $Errors[$i]='Name';
        $i++;
    }
    if (strlen($SupplierID) == 0) {
        $InputError = 1;
        prnMsg(_('The Supplier Code cannot be empty'),'error');
        $Errors[$i]='ID';
        $i++;
    }
    if (ContainsIllegalCharacters($SupplierID)) {
        $InputError = 1;
        prnMsg(_('The supplier code cannot contain any of the following characters') . " - . ' & + \" \\" . ' ' ._('or a space'),'error');
        $Errors[$i]='ID';
        $i++;
    }
    if (strlen($_POST['BankRef']) > 12) {
        $InputError = 1;
        prnMsg(_('The bank reference text must be less than 12 characters long'),'error');
        $Errors[$i]='BankRef';
        $i++;
    }
    if (!is_date($_POST['SupplierSince'])) {
        $InputError = 1;
        prnMsg(_('The supplier since field must be a date in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
        $Errors[$i]='SupplierSince';
        $i++;
    }

    /*
    elseif (strlen($_POST['BankAct']) > 1 ) {
        if (!Is_ValidAccount($_POST['BankAct'])) {
            prnMsg(_('The bank account entry is not a valid New Zealand bank account number. This is (of course) no concern if the business operates outside of New Zealand'),'warn');
        }
    }
    */

    if ($InputError != 1){

        $SQL_SupplierSince = FormatDateForSQL($_POST['SupplierSince']);

        $latitude = 0;
        $longitude = 0;
        if ($_SESSION['geocode_integration']==1 ){
            // Get the lat/long from our geocoding host
            $sql = "SELECT * FROM geocode_param WHERE 1";
            $ErrMsg = _('An error occurred in retrieving the information');
            $resultgeo = DB_query($sql, $db, $ErrMsg);
            $row = DB_fetch_array($resultgeo);
            $api_key = $row['geocode_key'];
            $map_host = $row['map_host'];
            define("MAPS_HOST", $map_host);
            define("KEY", $api_key);
            // check that some sane values are setup already in geocode tables, if not skip the geocoding but add the record anyway.
            if ($map_host=="") {
            echo '<div class="warn">' . _('Warning - Geocode Integration is enabled, but no hosts are setup.  Go to Geocode Setup') . '</div>';
            } else {
            $address = $_POST["Address1"] . ", " . $_POST["Address2"] . ", " . $_POST["Address3"] . ", " . $_POST["Address4"];

            $base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
            $request_url = $base_url . "&q=" . urlencode($address);

            $xml = simplexml_load_string(utf8_encode(file_get_contents($request_url))) or die("url not loading");
//            $xml = simplexml_load_file($request_url) or die("url not loading");

            $coordinates = $xml->Response->Placemark->Point->coordinates;
            $coordinatesSplit = split(",", $coordinates);
            // Format: Longitude, Latitude, Altitude
            $latitude = $coordinatesSplit[1];
            $longitude = $coordinatesSplit[0];

            $status = $xml->Response->Status->code;
            if (strcmp($status, "200") == 0) {
            // Successful geocode
                $geocode_pending = false;
                $coordinates = $xml->Response->Placemark->Point->coordinates;
                $coordinatesSplit = split(",", $coordinates);
                // Format: Longitude, Latitude, Altitude
                $latitude = $coordinatesSplit[1];
                $longitude = $coordinatesSplit[0];
            } else {
            // failure to geocode
                $geocode_pending = false;
                echo "<p>Address: " . $address . " failed to geocode.\n";
                echo "Received status " . $status . "\n</p>";
            }
            }
        }
        if (!isset($_POST['New'])) {

            $supptranssql = "SELECT COUNT(supplierno)
                                                    FROM supptrans
                                                    WHERE supplierno='".$SupplierID ."'";
            $suppresult = DB_query($supptranssql, $db);
            $supptrans = DB_fetch_row($suppresult);

            $suppcurrssql = "SELECT currcode
                                                    FROM suppliers
                                                    WHERE supplierid='".$SupplierID ."'";
            $currresult = DB_query($suppcurrssql, $db);
            $suppcurr = DB_fetch_row($currresult);

            if ($supptrans == 0) {
                $sql = "UPDATE suppliers SET suppname='" . $_POST['SuppName'] . "',
                            address1='" . $_POST['Address1'] . "',
                            address2='" . $_POST['Address2'] . "',
                            address3='" . $_POST['Address3'] . "',
                            address4='" . $_POST['Address4'] . "',
                            currcode='" . $_POST['CurrCode'] . "',
                            suppliersince='$SQL_SupplierSince',
                            paymentterms='" . $_POST['PaymentTerms'] . "',
                            bankpartics='" . $_POST['BankPartics'] . "',
                            bankref='" . $_POST['BankRef'] . "',
                            bankact='" . $_POST['BankAct'] . "',
                            remittance=" . $_POST['Remittance'] . ",
                            taxgroupid=" . $_POST['TaxGroup'] . ",
                            factorcompanyid=" . $_POST['FactorID'] .",
                            lat='" . $latitude ."',
                            lng='" . $longitude ."',
                            taxref='". $_POST['TaxRef'] ."'
                        WHERE supplierid = '$SupplierID'";
            } else {
                if ($suppcurr[0] != $_POST['CurrCode']) {
                    prnMsg( _('Cannot change currency code as transactions already exist'), info);
                }
                
                if(!isset($_POST['Bankdetails']))       {
                $sql = "UPDATE suppliers SET suppname='" . $_POST['SuppName'] . "',
                            address1='" . $_POST['Address1'] . "',
                            address2='" . $_POST['Address2'] . "',
                            address3='" . $_POST['Address3'] . "',
                            address4='" . $_POST['Address4'] . "',
                            suppliersince='$SQL_SupplierSince'
                        WHERE supplierid = '$SupplierID'";
                    unset($_POST['bankdetails']);    
                }else       {
                    
 $sql = "UPDATE suppliers SET suppname='" . $_POST['SuppName'] . "', 
                            currcode='" . $_POST['CurrCode'] . "',
                            suppliersince='$SQL_SupplierSince',
                            paymentterms='" . $_POST['PaymentTerms'] . "',
                            bankpartics='" . $_POST['BankPartics'] . "',
                            bankref='" . $_POST['BankRef'] . "',
                            bankact='" . $_POST['BankAct'] . "',
                            remittance=" . $_POST['Remittance'] . ",
                            taxgroupid=" . $_POST['TaxGroup'] . ",
                            factorcompanyid=" . $_POST['FactorID'] .",
                            lat='" . $latitude ."',
                            lng='" . $longitude ."',
                            taxref='". $_POST['TaxRef'] ."'
                        WHERE supplierid = '$SupplierID'";
                    
                }           
                        
            }

            $ErrMsg = _('The supplier could not be updated because');
            $DbgMsg = _('The SQL that was used to update the supplier but failed was');

            $result = DB_query($sql, $db, $ErrMsg, $DbgMsg);

            prnMsg(_('The supplier master record for') . ' ' . $SupplierID . ' ' . _('has been updated'),'success');

        } else { //its a new supplier


            $sql = "INSERT INTO suppliers (supplierid,
                            suppname,
                            address1,
                            address2,
                            address3,
                            address4,
                            currcode,
                            suppliersince,
                            paymentterms,
                            factorcompanyid
)
                     VALUES ('$SupplierID',
                         '" . $_POST['SuppName'] . "',
                        '" . $_POST['Address1'] . "',
                        '" . $_POST['Address2'] . "',
                        '" . $_POST['Address3'] . "',
                        '" . $_POST['Address4'] . "',
                        '" . $_POST['CurrCode'] . "',
                        '" . $SQL_SupplierSince . "',
                        '" . $_POST['PaymentTerms'] . "',
                        " . $_POST['FactorID'] . ")";

            $ErrMsg = _('The supplier') . ' ' . $_POST['SuppName'] . ' ' . _('could not be added because');
            $DbgMsg = _('The SQL that was used to insert the supplier but failed was');

            $result = DB_query($sql, $db, $ErrMsg, $DbgMsg);
            
            
            
            $sql2="INSERT INTO suppliercontacts (supplierid,
                          contact,fax,email,tel,mobile)
                   VALUES ('$SupplierID',
                        '" . $_POST['ContactName'] . "',
                        '" . $_POST['Fax'] . "',
                        '" . $_POST['Email'] . "',
                        '" . $_POST['Phone'] . "',
                        '" . $_POST['Mobile'] . "')";
            $result2=DB_query($sql2,$db);

            prnMsg(_('A new supplier for') . ' ' . $_POST['SuppName'] . ' ' . _('has been added to the database'),'success');



        }

    } else {

        prnMsg(_('Validation failed') . _('no updates or deletes took place'),'warn');

    }                                     
    
            unset ($SupplierID);
            unset ($_POST['SupplierID']);
            unset ($_POST['ContactName']);
            unset($_POST['SuppName']);
            unset($_POST['Address1']);                                   
            unset($_POST['Address2']);
            unset($_POST['Address3']);                                  
            unset($_POST['Address4']);
            unset($_POST['CurrCode']); 
            unset($_POST['Phone']);
            unset($_POST['Fax']);
            unset($_POST['Email'] );                                   
            unset($SQL_SupplierSince);
            unset($_POST['PaymentTerms']);
            unset($_POST['BankPartics']);
            unset($_POST['BankRef']);
            unset($_POST['BankAct']);
            unset($_POST['Remittance']);
            unset($_POST['TaxGroup']);
            unset($_POST['FactorID']);
            unset($_POST['TaxRef']);

} //---------------------sace supplier
else if ((isset($_POST['save']))  AND (isset($_POST['suppitems']))) {          /*Validate Inputs */
   $InputError = 0; /*Start assuming the best */
   if ($StockID=='' OR !isset($StockID)){
      $InputError=1;
      prnMsg( _('There is no stock item set up enter the stock code or select a stock item using the search page'),'error');
   }
   
   if (! is_numeric($_POST['Price']) OR $_POST['Price']==0){
      $InputError =1;
      unset($_POST['Price']);
      prnMsg( _('The price entered was not numeric') . ' (' . _('a number is expected') . ') - ' . _('no changes have been made to the database'),'error');
   }
   if (! is_numeric($_POST['LeadTime'])){
      $InputError =1;
      unset($_POST['LeadTime']);
      prnMsg( _('The lead time entered was not numeric') . ' (' . _('a number is expected') . ') - ' . _('no changes have been made to the database'),'error');
   }
   if ($_POST['MinOQ']=='')     {
       
       $_POST['MinOQ']=0; 
   }
      if ($_POST['MaxOQ']=='')     {
          
       $_POST['MaxOQ']=0; 
   }
   if (($_POST['MinOQ']!='') AND ($_POST['MaxOQ']!='')){ 
    if (($_POST['MinOQ']!=0) AND ($_POST['MaxOQ']!=0)){
    if ($_POST['MinOQ'] >=$_POST['MaxOQ'] ){
      $InputError =1;
      unset($_POST['MinOQ']);
      prnMsg( _('The min order quantity entered was greater than max order quantity') . '  - ' . _('no changes have been made to the database'),'error');
   }
   }
   }
   
   if (!is_numeric($_POST['ConversionFactor'])){
      $InputError =1;
      unset($_POST['ConversionFactor']);
      prnMsg( _('The conversion factor entered was not numeric') . ' (' . _('a number is expected') . '). ' . _('The conversion factor is the number which the price must be divided by to get the unit price in our unit of measure') . '. <br>' . _('E.g.') . ' ' . _('The supplier sells an item by the tonne and we hold stock by the kg') . '. ' . _('The suppliers price must be divided by 1000 to get to our cost per kg') . '. ' . _('The conversion factor to enter is 1000') . '. <br><br>' . _('No changes will be made to the database'),'error');
   }

   if ($InputError==0 AND isset($_POST['save']) AND(!isset($_POST['UpdateRecord']))){

   $sql = "INSERT INTO purchdata (supplierno,
                    stockid,
                    price,
                    effectivefrom,
                    suppliersuom,
                    conversionfactor,
                    supplierdescription,
                    leadtime,
                    preferred)
            VALUES ('" . $SupplierID . "',
                '" . $StockID . "',
                " . $_POST['Price'] . ",
                '" . FormatDateForSQL($_POST['EffectiveFrom']) . "',
                '" . $_POST['SuppliersUOM'] . "',
                " . $_POST['ConversionFactor'] . ",
                '" . $_POST['ContactName'] . "',
                " . $_POST['LeadTime'] . ",
                " . $_POST['Preferred'] . ')';

    $ErrMsg = _('The supplier purchasing details could not be added to the database because');
    $DbgMsg = _('The SQL that failed was');
    $AddResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

    prnMsg( _('This supplier purchasing data has been added to the database'),'success');

    
    $sql = "INSERT INTO supconstraints (stockid,
                    supcode,
                    supname,
                    minordqty,
                    maxordqty,
                    leadtime)
            VALUES ('" . $StockID . "',
                '" . $SupplierID . "',
                '" . $_POST['ContactName'] . "',
                 " . $_POST['MinOQ'] . ",
                 " . $_POST['MaxOQ'] . " ,
                " . $_POST['LeadTime'] . ")";
    
    $ErrMsg = _('The supplier purchasing details could not be added to the database because');
    $DbgMsg = _('The SQL that failed was');
    $AddResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

    //prnMsg( _('This supplier purchasing data has been added to the database'),'success');
    
    }
   if ($InputError==0 AND isset($_POST['UpdateRecord'])){

$sql = "UPDATE purchdata SET
                price=" . $_POST['Price'] . ",
                effectivefrom='" . FormatDateForSQL($_POST['EffectiveFrom']) . "',
                suppliersuom='" . $_POST['SuppliersUOM'] . "',
                conversionfactor=" . $_POST['ConversionFactor'] . ",
                supplierdescription='" . $_POST['ContactName'] . "',
                leadtime=" . $_POST['LeadTime'] . ",
                preferred=" . $_POST['Preferred'] . "
        WHERE purchdata.stockid='$StockID'
        AND purchdata.supplierno='$SupplierID'
        AND purchdata.effectivefrom='" . FormatDateForSQL($_POST['EffectiveFrom']) . "'";


     $ErrMsg = _('The supplier purchasing details could not be update because');
     $DbgMsg = _('The SQL that failed was');

     $UpdResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

     prnMsg (_('Supplier purchasing data has been updated'),'success');

     
$sql = "UPDATE supconstraints SET
                 supname='" . $_POST['ContactName'] . "',
                 minordqty=" . $_POST['MinOQ'] . ",
                 maxordqty=" . $_POST['MaxOQ'] . ",
                 leadtime=" . $_POST['LeadTime'] . "
                
        WHERE supconstraints.stockid='$StockID'
        AND supconstraints.supcode='$SupplierID'";
        


     $ErrMsg = _('The supplier purchasing details could not be update because');
     $DbgMsg = _('The SQL that failed was');

     $UpdResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

     prnMsg (_('Supplier purchasing data has been updated'),'success');
     
     
     
   }

   if ($InputError==0 AND (isset($_POST['UpdateRecord']) OR isset($_POST['AddRecord']))){
      /*update or insert took place and need to clear the form  */
      unset($SupplierID);
      unset($_POST['Price']);
      unset($CurrCode);
      unset($_POST['SuppliersUOM']);
      unset($_POST['EffectiveFrom']);
      unset($_POST['ConversionFactor']);
      unset($_POST['SupplierDescription']);
      unset($_POST['LeadTime']);
      unset($_POST['MinOQ']);
      unset($_POST['MaxOQ']);
      unset($_POST['Preferred']);
   }
}
$fieldid=get_fieldid('supplierid','suppliers',$db);

  echo "<div id=fullbody>";
    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class=panels id=panel_suppmas>";
    echo '<table width=100%><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
  echo "<fieldset class='left_panel_1'>";     
  echo"<legend><h3>General Details</h3>";
  echo"</legend>"; 
  echo'<table>'; 
  echo '<input type="hidden" name="New" value="1">';  
  include('SupplierMaster-leftpanel1.php');
  echo'</table>'; 
  echo "</fieldset>"; 
  echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px>';
//  echo "<div  id='seas_mas_rp1'>";  
  echo "<fieldset class='right_panel_1' id='suppliermaster_right_panel_1'>";     
  echo"<legend><h3>Contact Details</h3>";
  echo"</legend>";
  echo'<table>';
  include('SupplierMaster-rightpanel1.php');
  echo"</table>";
  echo "</fieldset>";
//  echo"</div>"; 
//--------------------------------------------------------------End of right Panel1     

    
    echo"</td></tr></table>";        
 
    echo"</div>";  
//--------------------------------------------------------------End of Panels

    echo"<div class=buttons>";
    echo "<table width=50%><tr>";
    echo "<td><input type='Submit' name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
    echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "' onclick='if(log_del()==1)return false;'></td>";
    echo "<td><input type='Button' class='button_details_show' onclick=selectiondetails() name='details'  VALUE='" . _('Details') . "'></td>";
    echo "</tr></table>";  
    echo"</div>";

//--------------------------------------------------------------End of Buttons      
    echo '</form>'; 
    
    echo"<div id='selectiondetails'>"; 
     
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
    echo "<a style='cursor:pointer;' onclick='supplierreports()'>" . _('Supplier Ledger') . '</a><br>';

    echo '</td><td VALIGN=TOP class="menu_group_items">'; /* Supplier Transactions */

    echo '</td><td VALIGN=TOP class="menu_group_items">'; /* Supplier Maintenance */
//        echo '<a href="' . $rootpath . '/Suppliers.php?">' . _('Add a New Supplier') . '</a><br>';
    echo "<a style='cursor:pointer;' onclick='supplieritems()'>" . _('Add/Modify Item Details') . '</a><br>'; 
    echo "<a style='cursor:pointer;' onclick='bankdetails()'>" . _('Add/Modify Bank Details') . '</a>';  
    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
    echo"</div>";
    
//-----------------------------------------------------------------------End of Details  
    
//-----------------------------------------------------------------------End of Details    
      
      echo "</div>";
    
  echo"<div id='Datagrid' class='Datagrid'>";
  datagrid($db);
  echo"</div>";
 
  function datagrid($db)    {
      
        echo "<br>";
        $sql = 'SELECT supplierid,
                    suppname,
                    currcode,
                    address1,
                    address2,
                    address3,
                    address4
                FROM suppliers
                ORDER BY suppname';      
    $result=DB_query($sql,$db);
 
       echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
          <th>" . _('Sl no:') . "</th>
        <th>" . _('Company Name') . "</th>
        <th>" . _('Currency') . "</th>
        <th>" . _('Street') . "</th>
        <th>" . _('Suburb/City') . "</th>
        <th>" . _('State/Province') . "</th>
        <th>" . _('Pincode') . "</th>
        </tr></thead>";
    echo $tableheader;
    $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
    while ($myrow=DB_fetch_array($result))   {

                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['supplierid'].'" onclick=showdetails(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['supplierid'].'" onclick=showdetails(this.id)>';
            $k++;
        }  
        
       $slno++; 
        printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow['suppname'],
            $myrow['currcode'],
            $myrow['address1'],
            $myrow['address2'],
            $myrow['address3'],
            $myrow['address4']);

    $RowIndex = $RowIndex + 1;
//end of page full new headings if
    }
//end of while loop
    echo'<tfoot><tr>';
    echo'<td colspan=7>Number of records:'.$slno.'</td>';
    echo'</tr></tfoot>';
    echo'</tbody>';
    echo '</table>';     
  }

  function delete($SupplierID,$db)      {
  
  //the link to delete a selected record was clicked instead of the submit button

    $CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS IN 'SuppTrans' , PurchOrders, SupplierContacts

    $sql= "SELECT COUNT(*) FROM supptrans WHERE supplierno='$SupplierID'";
    $result = DB_query($sql, $db);
    $myrow = DB_fetch_row($result);
    if ($myrow[0] > 0) {
        $CancelDelete = 1;
        prnMsg(_('Cannot delete this supplier because there are transactions that refer to this supplier'),'warn');
        echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('transactions against this supplier');

    } else {
        $sql= "SELECT COUNT(*) FROM purchorders WHERE supplierno='$SupplierID'";
        $result = DB_query($sql, $db);
        $myrow = DB_fetch_row($result);
        if ($myrow[0] > 0) {
            $CancelDelete = 1;
            prnMsg(_('Cannot delete the supplier record because purchase orders have been created against this supplier'),'warn');
//            echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('orders against this supplier');
        } 
        
//        else {
//            $sql= "SELECT COUNT(*) FROM suppliercontacts WHERE supplierid='$SupplierID'";
//            $result = DB_query($sql, $db);
//            $myrow = DB_fetch_row($result);
//            if ($myrow[0] > 0) {
//                $CancelDelete = 1;
//                prnMsg(_('Cannot delete this supplier because there are supplier contacts set up against it') . ' - ' . _('delete these first'),'warn');
//                echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('supplier contacts relating to this supplier');

//            }  
//        }


    }
            $sql= "SELECT COUNT(*) FROM purchdata WHERE supplierno='$SupplierID'";
        $result = DB_query($sql, $db);
        $myrow = DB_fetch_row($result);
        if ($myrow[0] > 0) {
            $CancelDelete = 1;
            prnMsg(_('Cannot delete the supplier record because purchase orders have been created against this supplier'),'warn');
//            echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('orders against this supplier');
        } 
    if ($CancelDelete == 0) {
        
        $sql="DELETE FROM suppliercontacts WHERE supplierid='$SupplierID'";
        $result = DB_query($sql, $db);
        
        $sql="DELETE FROM suppliers WHERE supplierid='$SupplierID'";
        $result = DB_query($sql, $db);
        
        
        
        prnMsg(_('Supplier record for') . ' ' . $SupplierID . ' ' . _('has been deleted'),'success');
        unset($SupplierID);
        unset($_SESSION['SupplierID']);
    } //end if Delete supplier    
      
  }
  echo "<br>";
 include('includes/smenufooter.inc'); 
?>
<script language="javascript">
 document.getElementById('custid').focus(); 
  $(document).ready(function() {
    $("#notice").fadeOut(3000);

 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
   }); 
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});
function caps1()
{
//   alert("sss");
UCWords('custid','Name should be begin with capital letter');
}
 function log_del()
{  //  alert("sss"); alert(mail);
var f=1;

var del=document.getElementById("suppselected").value;       


if(del==0)      {
alert("Select a supplier");     
    return f;
}
}


 function log_in()
{  //  alert("sss"); alert(mail);
var f=0;       


f=common_error('supplierid','Please Enter Supplierid');  if(f==1) { return f;} 
             


if(f==0){f=common_error('contactname','Please Enter contact name');  if(f==1) { return f; }}                  
if(f==0){f=common_error('suppliername','Please Enter company name');  if(f==1) { return f; }}     
if(f==0){f=common_error('outputtype','Please Select an Output Type');  if(f==1) { return f; }} 

if(f==0){f=common_error('sourcetype','Please Select a LeadSource Type');  if(f==1) { return f; }}
if(f==0){f=common_error('source','Please Select a LeadSource');  if(f==1) { return f; }} 
if(f==0){f=common_error('Scheme','Please Select a Scheme');  if(f==1) { return f; }}     
if(f==0){f=common_error('feedstock','Please Select a Fead Stock');  if(f==1) { return f; }}     }
    
</script>