<?php
$PageSecurity = 5;

include('includes/session.inc');

$title = _('FG Crediting to stores');

include('includes/header.inc');

$pagetype=2;
include('includes/sidemenu.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript"> 
$(document).ready(function(){
document.forms[0].wo.focus();  
    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
        
  $(".selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('.selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

         
});

 function reports(str1)
{

var wono=document.getElementById("wo").value;    
if(wono=="")      {
    
alert("Select/Enter a WO no:");
return;    
}
if(str1==1)         {
myRef = window.open('WOFgcrediting-reports-creditnotes.php?id='+ wono,'estr1');        
}  
if(str1==2)         {
myRef = window.open('WOFgcrediting-reports-rmbatches.php?id='+ wono,'estr1');        
}   
}

 function loadfromgrid(str1,str2,str3,str4,str5,str6,str7,str8)
{

if (str1=="")
  {
  document.getElementById("left_panel_1_WOFgcrediting").innerHTML="";
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
                                                                                   
    document.getElementById("left_panel_1_WOFgcrediting").innerHTML=xmlhttp.responseText;
    document.forms[0].Srqty.focus(); 
    $(".selectiondetails").hide(); 
    }
  }

xmlhttp.open("GET","WOFgcrediting-leftpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}


 function wosearch(str1,str2,str3,str4,str5,str6,str7,str8)
{
var str1=document.getElementById("wo").value;
//alert(str1);  
if (str1=="")
  {
      alert("Enter a WO number");
      document.forms[0].Wono.focus();  
//  document.getElementById("left_panel_1_WOStoresrequest").innerHTML="";
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
                                                                                     
    document.getElementById("left_panel_1_WOFgcrediting").innerHTML=xmlhttp.responseText;
    document.forms[0].Srqty.focus(); 
    }
  }

xmlhttp.open("GET","WOFgcrediting-leftpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}

function san(str)   {
    
myRef = window.open('WOFgcrediting-FGcredit.php?id='+ str,'estr1');    
    
}
 function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str1); 
if (str1=="")
  {
  document.getElementById("left_panel_1_WOStoresrequest").innerHTML="";
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
                                                                                     
    document.getElementById("left_panel_1_WOStoresrequest").innerHTML=xmlhttp.responseText;
    document.forms[0].Srqty.focus(); 
    }
  }

xmlhttp.open("GET","WOStoresrequest-leftpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}

 function datagridload(str1,str2,str3,str4,str5,str6,str7,str8)
{

if (str1=="")
  {
  document.getElementById("fullbody_woentry").innerHTML="";
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
    document.getElementById("fullbody_woentry").innerHTML=xmlhttp.responseText;
    if(document.getElementById("reqd").value=="")        {
    document.forms[0].Reqd.focus();     
    }else       {
    document.forms[0].Quantity.focus();  
    }  
    hidegrid();

    }
  }

xmlhttp.open("GET","WOEntry-itemchange.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}
</script>
<?php
//--------------------display 
$panel_id='panel_wofgcrediting';
$leg_l1='WO details';
$leg_r1="Item's details";
//..........................

$fieldid=get_fieldid('wo','workorders',$db);

if (isset($_GET['StockID'])){
    $StockID = strtoupper($_GET['StockID']);
} elseif (isset($_POST['SupplierID'])){
   $StockID = strtoupper($_POST['StockID']);
} else {
    unset($StockID);
}
if (!isset($_POST['StockLocation'])){
    if (isset($_SESSION['UserStockLocation'])){
        $_POST['StockLocation']=$_SESSION['UserStockLocation'];
    }
}  
 
// This is aleady linked from this page
//echo "<a href='" . $rootpath . '/SelectSupplier.php?' . SID . "'>" . _('Back to Suppliers') . '</a><br>';
echo '<p class="page_title_text">' . ' ' . _('Receive Finish Goods') . '';

$InputError = 0;

if (isset($Errors)) {
    unset($Errors);
}

$Errors=Array();
if(isset($_POST['clear']))       {

    clearfields();
   unset($StockID); 
}
//if(isset($_POST['save']))       {

//    submit(&$db,&$StockID,&$DemandID);
//}
function clearfields()      {
    
 unset($_POST['Wono']);
 unset($_POST['Duedate']);
 unset($_POST['Reqd']);
 unset($_POST['demandno']);
 unset($_POST['StockID']); 
 unset($_POST['StockLocation']);
 unset($_POST['Quantity']);
 unset($_POST['Batch']);  
}

if (isset($_POST['save'])){ //user hit the process the work order receipts entered.


    $InputError = false; //ie assume no problems for a start - ever the optimist
    $ErrMsg = _('Could not retrieve the details of the selected work order item');
    $WOResult = DB_query("SELECT workorders.loccode,
                             locations.locationname,
                             workorders.requiredby,
                             workorders.startdate,
                             workorders.closed,
                             stockmaster.description,
                             stockmaster.controlled,
                             stockmaster.serialised,
                             stockmaster.decimalplaces,
                             stockmaster.units,
                             woitems.qtyreqd,
                             woitems.qtyrecd,
                             woitems.stdcost,
                             stockcategory.wipact,
                             stockcategory.stockact
                            FROM workorders INNER JOIN locations
                            ON workorders.loccode=locations.loccode
                            INNER JOIN woitems
                            ON workorders.wo=woitems.wo
                            INNER JOIN stockmaster
                            ON woitems.stockid=stockmaster.stockid
                            INNER JOIN stockcategory
                            ON stockmaster.categoryid=stockcategory.categoryid
                            WHERE woitems.stockid='" . $_POST['StockID'] . "'
                            AND workorders.wo=".$_POST['WO'],
                            $db,
                            $ErrMsg);

    if (DB_num_rows($WOResult)==0){
        prnMsg(_('The selected work order item cannot be retrieved from the database'),'info');
        include('includes/footer.inc');
        exit;
    }
    $WORow = DB_fetch_array($WOResult);

    $QuantityReceived = 0;

    if($WORow['controlled']==1){ //controlled
        if ($WORow['serialised']==1){ //serialised
            for ($i=0;$i<$_POST['CountOfInputs'];$i++){
                if ($_SESSION['DefineControlledOnWOEntry']==1){
                    if (isset($_POST['CheckItem' . $i])){
                        $QuantityReceived ++;
                    }
                } else { //not predefined serial numbers
                    if (strlen($_POST['SerialNo' . $i])>0){
                        $QuantityReceived ++;
                    }
                }
            }
        } else { //controlled but not serialised - just lot/batch control
            for ($i=0;$i<15;$i++){
                if (strlen($_POST['BatchRef' . $i])>0){
                    $QuantityReceived += $_POST['Qty' .$i];
                }
            }
        } //end of lot/batch control
    } 
    if ($QuantityReceived==0)       {
    
   ?>
   <script>alert("Quantity cannot be null");</script>
   <?php     
        
    }else if($QuantityReceived>$_POST['maxqty'])      {
      echo  $_POST['maxqty']; 
       ?>
   <script>alert("Quantity cannot exceed the WO qty");</script>
   <?php     
    }else   {
//    else { //not controlled - an easy one!
//        if (!is_numeric($_POST['Qty'])){
//            $InputError=true;
//            prnMsg(_('The quantity entered is not numeric - a number is expected'),'error');
//        } else {
//            $QuantityReceived = $_POST['Qty'];
//        }
//    }

    if ($QuantityReceived + $WORow['qtyrecd'] > $WORow['qtyreqd'] *(1+$_SESSION['OverReceiveProportion'])){
        prnMsg(_('The quantity received is greater than the quantity required even after allowing for the configured allowable over-receive proportion. If this is correct then the work order must be modified first.'),'error');
        $InputError=true;
    }

    if ($WORow['serialised']==1){
        /* serialised items form has a possible $_POST['CountOfInputs'] fields for entry of serial numbers - 12 rows x 5 per row
         * if serial numbers are defined at the time of work order entry $_SESSION['DefineControlledOnWOEntry']==1 then possibly more 
         * need to inspect $_POST['CountOfInputs']
         */
        for($i=0;$i<$_POST['CountOfInputs'];$i++){
        //need to test if the serialised item exists first already
            if (trim($_POST['SerialNo' .$i]) != "" AND  ($_SESSION['DefineControlledOnWOEntry']==0
                    OR ($_SESSION['DefineControlledOnWOEntry']==1 AND $_POST['CheckedItem'.$i]==true))){
                    $SQL = "SELECT COUNT(*) FROM stockserialitems
                            WHERE stockid='" . $_POST['StockID'] . "'
                            AND loccode = '" . $_POST['IntoLocation'] . "'
                            AND serialno = '" . $_POST['SerialNo' .$i] . "'";
                    $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not check if a serial number for the stock item already exists because');
                    $DbgMsg =  _('The following SQL to test for an already existing serialised stock item was used');
                    $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                    $AlreadyExistsRow = DB_fetch_row($Result);

                    if ($AlreadyExistsRow[0]>0){
                        prnMsg(_('The serial number entered already exists. Duplicate serial numbers are prohibited. The duplicate item is:') . ' ' . $_POST['SerialNo'.$i] ,'error');
                        $InputError = true;
                    }
            }
        } //end loop throught the 60 fields for serial number entry
    }//end check on pre-existing serial numbered items


    if ($_SESSION['ProhibitNegativeStock']==1){
        /*Now look for autoissue components that would go negative */
                $SQL = "SELECT worequirements.stockid,
                               stockmaster.description,
                               locstock.quantity-(" . $QuantityReceived  . "*worequirements.qtypu) AS qtyleft
                          FROM worequirements
                          INNER JOIN stockmaster
                            ON worequirements.stockid=stockmaster.stockid
                          INNER JOIN locstock
                            ON worequirements.stockid=locstock.stockid
                          WHERE worequirements.wo=" . $_POST['WO'] . "
                          AND worequirements.parentstockid='" .$_POST['StockID'] . "'
                          AND locstock.loccode='" . $WORow['loccode'] . "'
                          AND stockmaster.mbflag <>'D'
                          AND worequirements.autoissue=1";

        $ErrMsg = _('Could not retrieve the component quantity left at the location once the component items are issued to the work order (for the purposes of checking that stock will not go negative) because');
        $Result = DB_query($SQL,$db,$ErrMsg);
        while ($NegRow = DB_fetch_array($Result)){
            if ($NegRow['qtyleft']<0){
                prnMsg(_('Receiving the selected quantity against this work order would result in negative stock for a component. The system parameters are set to prohibit negative stocks from occurring. This manufacturing receipt cannot be created until the stock on hand is corrected.'),'error',_('Component') . ' - ' .$NegRow['component'] . ' ' . $NegRow['description'] . ' - ' . _('Negative Stock Prohibited'));
                $InputError = true;
            } // end if negative would result
        } //loop around the autoissue requirements for the work order
    }

    if ($InputError==false){
/************************ BEGIN SQL TRANSACTIONS ************************/

        $Result = DB_Txn_Begin($db);
        /*Now Get the next WOReceipt transaction type 26 - function in SQL_CommonFunctions*/
        $WOReceiptNo = GetNextTransNo(26, $db);

        $PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']), $db);
        $SQLReceivedDate = FormatDateForSQL($_POST['ReceivedDate']);
        $StockGLCode = GetStockGLCode($_POST['StockID'],$db);

    //Recalculate the standard for the item if there were no items previously received against the work order
        if ($WORow['qtyrecd']==0){
            $CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost
                                    FROM stockmaster INNER JOIN bom
                                    ON stockmaster.stockid=bom.component
                                    WHERE bom.parent='" . $_POST['StockID'] . "'
                                    AND bom.loccode='" . $WORow['loccode'] . "'",
                                    $db);
            $CostRow = DB_fetch_row($CostResult);
            if (is_null($CostRow[0]) OR $CostRow[0]==0){
                    $Cost =0;
            } else {
                    $Cost = $CostRow[0];
            }
            //Need to refresh the worequirments with the bom components now incase they changed
            $DelWORequirements = DB_query("DELETE FROM worequirements
                                            WHERE wo=" . $_POST['WO'] . "
                                            AND parentstockid='" . $_POST['StockID'] . "'",
                                            $db);

            //Recursively insert real component requirements
            WoRealRequirements($db, $_POST['WO'], $WORow['loccode'], $_POST['StockID']);

            //Need to check this against the current standard cost and do a cost update if necessary
            $sql = "SELECT materialcost+labourcost+overheadcost AS cost,
                          sum(quantity) AS totalqoh,
                          labourcost,
                          overheadcost
                    FROM stockmaster INNER JOIN locstock
                        ON stockmaster.stockid=locstock.stockid
                    WHERE stockmaster.stockid='" . $_POST['StockID'] . "'
                    GROUP BY
                        materialcost,
                        labourcost,
                        overheadcost";
            $ItemResult = DB_query($sql,$db);
            $ItemCostRow = DB_fetch_array($ItemResult);

            if (($Cost + $ItemCostRow['labourcost'] + $ItemCostRow['overheadcost']) != $ItemCostRow['cost']){ //the cost roll-up cost <> standard cost

                if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $ItemCostRow['totalqoh']!=0){

                    $CostUpdateNo = GetNextTransNo(35, $db);
                    $PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']), $db);

                    $ValueOfChange = $ItemCostRow['totalqoh'] * (($Cost + $ItemCostRow['labourcost'] + $ItemCostRow['overheadcost']) - $ItemCostRow['cost']);

                    $SQL = "INSERT INTO gltrans (type,
                                typeno,
                                trandate,
                                periodno,
                                account,
                                narrative,
                                amount)
                            VALUES (35,
                                " . $CostUpdateNo . ",
                                '" . Date('Y-m-d') . "',
                                " . $PeriodNo . ",
                                " . $StockGLCode['adjglact'] . ",
                                '" . _('Cost roll on release of WO') . ': ' . $_POST['WO'] . ' - ' . $_POST['StockID'] . ' ' . _('cost was') . ' ' . $ItemCostRow['cost'] . ' ' . _('changed to') . ' ' . $Cost . ' x ' . _('Quantity on hand of') . ' ' . $ItemCostRow['totalqoh'] . "',
                                " . (-$ValueOfChange) . ")";

                    $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL credit for the stock cost adjustment posting could not be inserted because');
                    $DbgMsg = _('The following SQL to insert the GLTrans record was used');
                    $Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

                    $SQL = "INSERT INTO gltrans (type,
                                typeno,
                                trandate,
                                periodno,
                                account,
                                narrative,
                                amount)
                            VALUES (35,
                                " . $CostUpdateNo . ",
                                '" . Date('Y-m-d') . "',
                                " . $PeriodNo . ",
                                " . $StockGLCode['stockact'] . ",
                                '" . _('Cost roll on release of WO') . ': ' . $_POST['WO'] . ' - ' . $_POST['StockID'] . ' ' . _('cost was') . ' ' . $ItemCostRow['cost'] . ' ' . _('changed to') . ' ' . $Cost . ' x ' . _('Quantity on hand of') . ' ' . $ItemCostRow['totalqoh'] . "',
                                " . $ValueOfChange . ")";

                    $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL debit for stock cost adjustment posting could not be inserted because');
                    $DbgMsg = _('The following SQL to insert the GLTrans record was used');
                    $Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
                }

                $SQL = "UPDATE stockmaster SET
                            materialcost=" . $Cost . ",
                            labourcost=" . $ItemCostRow['labourcost'] . ",
                            overheadcost=" . $ItemCostRow['overheadcost'] . ",
                            lastcost=" . $ItemCostRow['cost'] . "
                        WHERE stockid='" . $_POST['StockID'] . "'";

                $ErrMsg = _('The cost details for the stock item could not be updated because');
                $DbgMsg = _('The SQL that failed was');
                $Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
            } //cost as rolled up now <> current standard cost  so do adjustments
        }   //qty recd previously was 0 so need to check costs and do adjustments as required

        //Do the issues for autoissue components in the worequirements table
        $AutoIssueCompsResult = DB_query("SELECT worequirements.stockid,
                                                 qtypu,
                                                 materialcost+labourcost+overheadcost AS cost,
                                                 stockcategory.stockact,
                                                 stockcategory.stocktype
                                          FROM worequirements
                                          INNER JOIN stockmaster
                                          ON worequirements.stockid=stockmaster.stockid
                                          INNER JOIN stockcategory
                                          ON stockmaster.categoryid=stockcategory.categoryid
                                          WHERE wo=" . $_POST['WO'] . "
                                          AND parentstockid='" .$_POST['StockID'] . "'
                                          AND autoissue=1",
                                          $db);

        $WOIssueNo = GetNextTransNo(28,$db);
        while ($AutoIssueCompRow = DB_fetch_array($AutoIssueCompsResult)){

            //Note that only none-controlled items can be auto-issuers so don't worry about serial nos and batches of controlled ones
            /*Cost variances calculated overall on close of the work orders so NO need to check if cost of component has been updated subsequent to the release of the WO
            */
            if ($AutoIssueCompRow['stocktype']!='L'){
                //Need to get the previous locstock quantity for the component at the location where the WO manuafactured
                $CompQOHResult = DB_query("SELECT locstock.quantity
                                        FROM locstock
                                        WHERE locstock.stockid='" . $AutoIssueCompRow['stockid'] . "'
                                        AND loccode= '" . $WORow['loccode'] . "'",
                                        $db);
                if (DB_num_rows($CompQOHResult)==1){
                            $LocQtyRow = DB_fetch_row($CompQOHResult);
                            $NewQtyOnHand = $LocQtyRow[0] - ($AutoIssueCompRow['qtypu'] * $QuantityReceived);
                } else {
                            /*There must actually be some error this should never happen */
                            $NewQtyOnHand = 0;
                }

                $SQL = "UPDATE locstock
                            SET quantity = quantity - " . ($AutoIssueCompRow['qtypu'] * $QuantityReceived). "
                            WHERE locstock.stockid = '" . $AutoIssueCompRow['stockid'] . "'
                            AND loccode = '" . $WORow['loccode'] . "'";

                $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated by the issue of stock to the work order from an auto issue component because');
                $DbgMsg =  _('The following SQL to update the location stock record was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
            } else {
                $NewQtyOnHand =0;
            }
            $SQL = "INSERT INTO stockmoves (stockid,
                                            type,
                                            transno,
                                            loccode,
                                            trandate,
                                            prd,
                                            reference,
                                            price,
                                            qty,
                                            standardcost,
                                            newqoh)
                        VALUES ('" . $AutoIssueCompRow['stockid'] . "',
                            28,
                            " . $WOIssueNo . ",
                            '" . $WORow['loccode'] . "',
                            '" . Date('Y-m-d') . "',
                            " . $PeriodNo . ",
                            '" . $_POST['WO'] . "',
                            " . $AutoIssueCompRow['cost'] . ",
                            " . -($AutoIssueCompRow['qtypu'] * $QuantityReceived) . ",
                            " . $AutoIssueCompRow['cost'] . ",
                            " . $NewQtyOnHand . ")";

            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement record could not be inserted for an auto-issue component because');
            $DbgMsg =  _('The following SQL to insert the stock movement records was used');
            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

            //Update the workorder record with the cost issued to the work order
            $SQL = "UPDATE workorders SET
                        costissued = costissued+" . ($AutoIssueCompRow['qtypu'] * $QuantityReceived * $AutoIssueCompRow['cost']) ."
                    WHERE wo=" . $_POST['WO'];
            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not be update the work order cost for an auto-issue component because');
            $DbgMsg =  _('The following SQL to update the work order cost was used');
            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

            if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND ($AutoIssueCompRow['qtypu'] * $QuantityReceived * $AutoIssueCompRow['cost'])!=0){
            //if GL linked then do the GL entries to DR wip and CR stock

                $SQL = "INSERT INTO gltrans (type,
                                    typeno,
                                    trandate,
                                    periodno,
                                    account,
                                    narrative,
                                    amount)
                            VALUES (28,
                                " . $WOIssueNo . ",
                                '" . Date('Y-m-d') . "',
                                " . $PeriodNo . ",
                                " . $StockGLCode['wipact'] . ",
                                '" . $_POST['WO'] . ' - ' . $_POST['StockID'] . ' ' . _('Component') . ': ' . $AutoIssueCompRow['stockid'] . ' - ' . $QuantityReceived . ' x ' . $AutoIssueCompRow['qtypu'] . ' @ ' . number_format($AutoIssueCompRow['cost'],2) . "',
                                " . ($AutoIssueCompRow['qtypu'] * $QuantityReceived * $AutoIssueCompRow['cost']) . ")";

                    $ErrMsg =   _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The WIP side of the work order issue GL posting could not be inserted because');
                    $DbgMsg =  _('The following SQL to insert the WO issue GLTrans record was used');
                    $Result = DB_query($SQL,$db, $ErrMsg, $DbgMsg, true);

                $SQL = "INSERT INTO gltrans (type,
                                    typeno,
                                    trandate,
                                    periodno,
                                    account,
                                    narrative,
                                    amount)
                            VALUES (28,
                                " . $WOIssueNo . ",
                                '" . Date('Y-m-d') . "',
                                " . $PeriodNo . ",
                                " . $AutoIssueCompRow['stockact'] . ",
                                '" . $_POST['WO'] . ' - ' . $_POST['StockID'] . ' -> ' . $AutoIssueCompRow['stockid'] . ' - ' . $QuantityReceived . ' x ' . $AutoIssueCompRow['qtypu'] . ' @ ' . number_format($AutoIssueCompRow['cost'],2) . "',
                                " . -($AutoIssueCompRow['qtypu'] * $QuantityReceived * $AutoIssueCompRow['cost']) . ")";

                    $ErrMsg =   _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock side of the work order issue GL posting could not be inserted because');
                    $DbgMsg =  _('The following SQL to insert the WO issue GLTrans record was used');
                    $Result = DB_query($SQL,$db, $ErrMsg, $DbgMsg, true);
            }//end GL-stock linked

        } //end of auto-issue loop for all components set to auto-issue


        /* Need to get the current location quantity will need it later for the stock movement */
        $SQL = "SELECT locstock.quantity
                FROM locstock
                WHERE locstock.stockid='" . $_POST['StockID'] . "'
                AND loccode= '" . $_POST['IntoLocation'] . "'";

        $Result = DB_query($SQL, $db);
        if (DB_num_rows($Result)==1){
            $LocQtyRow = DB_fetch_row($Result);
            $QtyOnHandPrior = $LocQtyRow[0];
        } else {
        /*There must actually be some error this should never happen */
            $QtyOnHandPrior = 0;
        }

        $SQL = "UPDATE locstock
                SET quantity = locstock.quantity + " . $QuantityReceived . "
                WHERE locstock.stockid = '" . $_POST['StockID'] . "'
                AND loccode = '" . $_POST['IntoLocation'] . "'";

        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
        $DbgMsg =  _('The following SQL to update the location stock record was used');
        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

        $WOReceiptNo = GetNextTransNo(26,$db);
        /*Insert stock movements - with unit cost */

        $SQL = "INSERT INTO stockmoves (stockid,
                                        type,
                                        transno,
                                        loccode,
                                        trandate,
                                        price,
                                        prd,
                                        reference,
                                        qty,
                                        standardcost,
                                        newqoh)
                    VALUES ('" . $_POST['StockID'] . "',
                            26,
                            " . $WOReceiptNo . ",
                            '" . $_POST['IntoLocation'] . "',
                            '" . Date('Y-m-d') . "',
                            " . $WORow['stdcost'] . ",
                            " . $PeriodNo . ",
                            '" . $_POST['WO'] . "',
                            " . $QuantityReceived . ",
                            " . $WORow['stdcost'] . ",
                            " . ($QtyOnHandPrior + $QuantityReceived) . ")";

        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted when processing the work order receipt because');
        $DbgMsg =  _('The following SQL to insert the stock movement records was used');
        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

        /*Get the ID of the StockMove... */
        $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');
        /* Do the Controlled Item INSERTS HERE */

        if ($WORow['controlled'] ==1){
            //the form is different for serialised items and just batch/lot controlled items
            if ($WORow['serialised']==1){
                //serialised items form has a possible 60 fields for entry of serial numbers - 12 rows x 5 per row
                for($i=0;$i<$_POST['CountOfInputs'];$i++){
                /*  We need to add the StockSerialItem record and
                    The StockSerialMoves as well */
                    if (trim($_POST['SerialNo' .$i]) != ""){
                        if ($_SESSION['DefineControlledOnWOEntry']==0 OR 
                            ($_SESSION['DefineControlledOnWOEntry']==1 AND $_POST['CheckItem'.$i]==true)){
            
                            $LastRef = trim($_POST['SerialNo' .$i]);
                            //already checked to ensure there are no duplicate serial numbers entered
                            if (isset($_POST['QualityText'.$i])){
                                $QualityText = $_POST['QualityText'.$i];
                            } else {
                                $QualityText ='';
                            }
                            
                            $SQL = "INSERT INTO stockserialitems (stockid,
                                                                    loccode,
                                                                    serialno,
                                                                    quantity,
                                                                    qualitytext)
                                            VALUES ('" . $_POST['StockID'] . "',
                                                    '" . $_POST['IntoLocation'] . "',
                                                    '" . DB_escape_string($_POST['SerialNo' . $i]) . "',
                                                    1,
                                                    '" . DB_escape_string($QualityText) . "')";
                            $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be inserted because');
                            $DbgMsg =  _('The following SQL to insert the serial stock item records was used');
                            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

                            /** end of handle stockserialitems records */

                            /** now insert the serial stock movement **/
                            $SQL = "INSERT INTO stockserialmoves (stockmoveno,
                                                                    stockid,
                                                                    serialno,
                                                                    moveqty)
                                        VALUES (" . $StkMoveNo . ",
                                                '" . $_POST['StockID'] . "',
                                                '" . DB_escape_string($_POST['SerialNo' .$i]) . "',
                                                1)";
                            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                            $DbgMsg = _('The following SQL to insert the serial stock movement records was used');
                            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                            
                            if ($_SESSION['DefineControlledOnWOEntry']==1){
                                //need to delete the item from woserialnos
                                $SQL = "DELETE FROM    woserialnos 
                                            WHERE wo=" . $_POST['WO'] . "
                                            AND stockid='" . $_POST['StockID'] ."' 
                                            AND serialno='" . DB_escape_string($_POST['SerialNo'.$i]) . "'";
                                $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The predefined serial number record could not be deleted because');
                                $DbgMsg = _('The following SQL to delete the predefined work order serial number record was used');
                                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);            
                            }
                        }//end prefined controlled items or not
                    } //non blank SerialNo
                } //end for all of the potential serialised fields received
            } else { //the item is just batch/lot controlled not serialised
            /*the form for entry of batch controlled items is only 15 possible fields */
                for($i=0;$i<$_POST['CountOfItems'];$i++){
                /*  We need to add the StockSerialItem record and
                    The StockSerialMoves as well */
                //need to test if the batch/lot exists first already
                    if (trim($_POST['BatchRef' .$i]) != ""){
                        $LastRef = trim($_POST['BatchRef' .$i]);
                        $SQL = "SELECT COUNT(*) FROM stockserialitems
                                WHERE stockid='" . $_POST['StockID'] . "'
                                AND loccode = '" . $_POST['IntoLocation'] . "'
                                AND serialno = '" . $_POST['BatchRef' .$i] . "'";
                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not check if a serial number for the stock item already exists because');
                        $DbgMsg =  _('The following SQL to test for an already existing serialised stock item was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        $AlreadyExistsRow = DB_fetch_row($Result);
                        if (isset($_POST['QualityText'.$i])){
                            $QualityText = $_POST['QualityText'.$i];
                        } else {
                            $QualityText ='';
                        }    
                        if ($AlreadyExistsRow[0]>0){
                            $SQL = 'UPDATE stockserialitems SET quantity = quantity + ' . $_POST['Qty' . $i] . ",
                                                                qualitytext = '" . DB_escape_string($QualityText) . "'
                                        WHERE stockid='" . $_POST['StockID'] . "'
                                        AND loccode = '" . $_POST['IntoLocation'] . "'
                                        AND serialno = '" . $POST['BatchRef' .$i] . "'";
                        } else {
                            $SQL = "INSERT INTO stockserialitems (stockid,
                                                                loccode,
                                                                serialno,
                                                                quantity,
                                                                qualitytext)
                                        VALUES ('" . $_POST['StockID'] . "',
                                                '" . $_POST['IntoLocation'] . "',
                                                '" . DB_escape_string($_POST['BatchRef' . $i]) . "',
                                                " . $_POST['Qty'.$i] . ",
                                                '" . DB_escape_string($_POST['QualityText']) . "')";
                        }
                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be inserted because');
                        $DbgMsg =  _('The following SQL to insert the serial stock item records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

                        /** end of handle stockserialitems records */

                        /** now insert the serial stock movement **/
                        $SQL = "INSERT INTO stockserialmoves (stockmoveno,
                                                        stockid,
                                                        serialno,
                                                        moveqty)
                                    VALUES (" . $StkMoveNo . ",
                                            '" . $_POST['StockID'] . "',
                                            '" . DB_escape_string($_POST['BatchRef'.$i])  . "',
                                            " . $_POST['Qty'.$i]  . ")";
                        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                        $DbgMsg = _('The following SQL to insert the serial stock movement records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        
                        if ($_SESSION['DefineControlledOnWOEntry']==1){
                            //check how many of the batch/bundle/lot has been received
                            $SQL = "SELECT sum(moveqty) FROM stockserialmoves 
                                        INNER JOIN stockmoves ON stockserialmoves.stockmoveno=stockmoves.stkmoveno
                                        WHERE stockmoves.type=26
                                        AND stockserialmoves.stockid='" . $_POST['StockID'] . "' 
                                        AND stockserialmoves.serialno='" .     DB_escape_string($_POST['BatchRef'.$i]) . "'";
                            
                            $BatchTotQtyResult = DB_query($SQL,$db);
                            $BatchTotQtyRow = DB_fetch_row($BatchTotQtyResult);
                            if ($BatchTotQtyRow[0] >= $_POST['QtyReqd'.$i]){
                                //need to delete the item from woserialnos
                                $SQL = "DELETE FROM    woserialnos 
                                        WHERE wo=" . $_POST['WO'] . "
                                        AND stockid='" . $_POST['StockID'] ."' 
                                        AND serialno='" . DB_escape_string($_POST['BatchRef'.$i]) . "'";
                                $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The predefined batch/lot/bundle record could not be deleted because');
                                $DbgMsg = _('The following SQL to delete the predefined work order batch/bundle/lot record was used');
                                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);            
                            }
                        }
                    }//non blank BundleRef
                } //end for all of the potential batch/lot fields received
            } //end of the batch controlled stuff
        } //end if the woitem received here is a controlled item


        /* If GLLink_Stock then insert GLTrans to debit the GL Code  and credit GRN Suspense account at standard cost*/
        if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND ($WORow['stdcost']*$QuantityReceived)!=0){
        /*GL integration with stock is activated so need the GL journals to make it so */

        /*first the debit the finished stock of the item received from the WO
          the appropriate account was already retrieved into the $StockGLCode variable as the Processing code is kicked off
          it is retrieved from the stock category record of the item by a function in SQL_CommonFunctions.inc*/

            $SQL = "INSERT INTO gltrans (type,
                                    typeno,
                                    trandate,
                                    periodno,
                                    account,
                                    narrative,
                                    amount)
                            VALUES (26,
                                " . $WOReceiptNo . ",
                                '" . Date('Y-m-d') . "',
                                " . $PeriodNo . ",
                                " . $StockGLCode['stockact'] . ",
                                '" . $_POST['WO'] . " " . $_POST['StockID'] . " - " . $WORow['description'] . ' x ' . $QuantityReceived . " @ " . number_format($WORow['stdcost'],2) . "',
                                " . ($WORow['stdcost'] * $QuantityReceived) . ")";

            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The receipt of work order finished stock GL posting could not be inserted because');
            $DbgMsg = _('The following SQL to insert the work order receipt of finished items GLTrans record was used');
            $Result = DB_query($SQL,$db,$ErrMsg, $DbgMsg, true);

        /*now the credit WIP entry*/
            $SQL = "INSERT INTO gltrans (type,
                                    typeno,
                                    trandate,
                                    periodno,
                                    account,
                                    narrative,
                                    amount)
                            VALUES (26,
                                " . $WOReceiptNo . ",
                                '" . Date('Y-m-d') . "',
                                " . $PeriodNo . ",
                                " . $StockGLCode['wipact'] . ",
                                '" . $_POST['WO'] . " " . $_POST['StockID'] . " - " . $WORow['description'] . ' x ' . $QuantityReceived . " @ " . number_format($WORow['stdcost'],2) . "',
                                " . -($WORow['stdcost'] * $QuantityReceived) . ")";

            $ErrMsg =   _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The WIP credit on receipt of finished items from a work order GL posting could not be inserted because');
            $DbgMsg =  _('The following SQL to insert the WIP GLTrans record was used');
            $Result = DB_query($SQL,$db, $ErrMsg, $DbgMsg,true);

        } /* end of if GL and stock integrated and standard cost !=0 */


        //update the wo with the new qtyrecd
        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('Could not update the work order item record with the total quantity received because');
        $DbgMsg = _('The following SQL was used to update the work order');
        $UpdateWOResult =DB_query("UPDATE woitems
                                    SET qtyrecd=qtyrecd+" . $QuantityReceived . ",
                                        nextlotsnref='" . $LastRef . "'
                                    WHERE wo=" . $_POST['WO'] . "
                                    AND stockid='" . $_POST['StockID'] . "'",
                                    $db,$ErrMsg,$DbgMsg,true);


        $Result = DB_Txn_Commit($db);

        prnMsg(_('The receipt of') . ' ' . $QuantityReceived . ' ' . $WORow['units'] . ' ' . _('of')  . ' ' . $_POST['StockID'] . ' - ' . $WORow['description'] . ' ' . _('against work order') . ' '. $_POST['WO'] . ' ' . _('has been processed'),'info');

        unset($_POST['WO']);
        unset($_POST['StockID']);
        unset($_POST['IntoLocation']);
        unset($_POST['Process']);
        for ($i=1;$i<$_POST['CountOfInputs'];$i++){
            unset($_POST['SerialNo'.$i]);
            unset($_POST['BatchRef'.$i]);
            unset($_POST['Qty'.$i]);
            unset($_POST['QualityText'.$i]);
            unset($_POST['QtyReqd'.$i]);
        }
        /*end of process work order goods received entry */
        clearfields();

    } //end if there were not input errors reported - so the processing was allowed to continue
    
 print'<script>

     var answer = confirm("Do you want to PRINT the FG Credit Note Now?")

   if (answer){
san("'.$WOReceiptNo.'");

   }
 
 </script> ';    
}
} //end of if the user hit the process button // End of function submit()

    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
    
    echo "<div id='fullbody_woentry'>";


//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class='panels' id='$panel_id'>";
    echo '<table width=100% ><tr><td width=50% valign="top" height=270px >';   
    
//--------------------------------------------------------------Start of Left Panel1  
    echo "<fieldset  class='left_panel_1'>";     
    echo"<legend><h3>$leg_l1</h3>";
    echo"</legend>"; 
    echo'<div style="height:230px;overflow:auto;">';
    echo'<table id="left_panel_1_WOFgcrediting" width=100%>'; 
    include('WOFgcrediting-leftpanel1.php');
    echo'</table>'; 
    echo'</div>';
    echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px>';
    
//    echo "<fieldset class='right_panel_1'>";     
//    echo"<legend><h3>$leg_r1</h3>";
//    echo"</legend>";
//    echo '<table width=100% class=sortable>';
//    include('WOStoresrequest-rightpanel1.php');
//    echo"</table>";
//    echo "</fieldset>"; 
    

    
    echo"</td></tr></table>";
    echo"</div>";  
//--------------------------------------------------------------End of Panels

    echo"<div class=buttons>";
    echo "<table width=50%><tr>";
    echo "<td><input type='Submit' name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='skip' VALUE='" . _('Skip') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
    echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "'></td>";
    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "<td><input type='Button' name='search' VALUE='" . _('Search') . "' onclick='wosearch()'></td>";
    echo "</tr></table>";  
    echo"</div>";
    
//--------------------------------------------------------------End of Buttons       

    
    echo"<div class='selectiondetails'>"; 
    selectiondetails($rootpath,$db);
    echo"</div>";
    function selectiondetails($rootpath,$db)     {  

    echo '<table width=100% colspan=2 border=0 cellpadding=4>';
    echo "<tr>
        <th width=33%>" . _('Inquiries') . "</th>
        <th width=33%>". _('Transactions') . "</th>
        <th width=33%>" . _('Maintenance') . "</th>
    </tr>";
    echo '<tr><td VALIGN=TOP class="menu_group_items">';    /* Inquiry Options */
    echo "<a style='cursor:pointer;' id='2' onclick='reports(this.id)'>" . _('Raw Material batches') . '</a><br>';
    echo'<br>'; 
    echo'<td VALIGN=TOP class="menu_group_items"></td>';
    echo '<td VALIGN=TOP class="menu_group_items">';
    echo "<a style='cursor:pointer;' id='1' onclick='reports(this.id)'>" . _('FG Credit notes issued') . '</a><br>';
    echo'<br>';
    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
    
    }
//-----------------------------------------------------------------------End of Details   
      

//-----------------------------------------------------------------------Data Grid     
    echo"<div class='Datagrid' id=Datagrid_wofgcrediting>";
    include('WOFgcrediting-datagrid.php');  
    echo"</div>";
//-----------------------------------------------------------------------End of Data Grid    
 
    echo '</form>';        
    echo "</div>";   
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

 function log_in()
{  //  alert("sss"); alert(mail);
var f=0;       


f=common_error('wo','Please Select\Enter a WO no:');  if(f==1) { return f;} 
             
//if(f==0){f=quantitycheck();  if(f==1) {return f; }}
if(f==0){f=common_error('duedate','Enter the Duedate');  if(f==1) {return f; }}                  
if(f==0){f=common_error('fgqty2','Enter the quantity, to skip a date use the skip button');  if(f==1) { document.forms[0].skip.focus(); return f; }}   
 
//function quantitycheck()

//{  var f=0;
//    var a=new Array();
//    a=document.getElementsByName("Qty[]");

//    var p=0;
//    for(i=0;i<=a.length;i++){

//        p=0;
//    }
//    if (p==0){ var f=1;
//        alert('please select at least one Scheme');
//        return f;
//    }

//}
}   
function hidegrid(){
  $(".selectiondetails").hide(); 
  $('.button_details_show').click(function() {
  $('.selectiondetails').slideToggle('slow', function() {

  });
}); 
 }   
</script>