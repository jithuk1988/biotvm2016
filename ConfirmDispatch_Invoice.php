<?php

/* $Id: ConfirmDispatch_Invoice.php 4579 2011-05-29 04:07:16Z daintree $*/

$PageSecurity=80;

/* Session started in session.inc for password checking and authorisation level check */
include('includes/DefineCartClass.php');
include('includes/DefineSerialItems.php');
include('includes/session.inc');
$title = _('Confirm Dispatches and Invoice An Order');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
include('includes/FreightCalculation.inc');
include('includes/GetSalesTransGLCodes.inc');

if (!isset($_GET['OrderNumber']) AND !isset($_SESSION['ProcessingOrder'])) {
	/* This page can only be called with an order number for invoicing*/
	echo '<div class="centre"><a href="' . $rootpath . '/SelectSalesOrder.php">' . _('Select a sales order to invoice'). '</a></div>';
	echo '<br /><br />';
	prnMsg( _('This page can only be opened if an order has been selected Please select an order first from the delivery details screen click on Confirm for invoicing'), 'error' );
	include ('includes/footer.inc');
	exit;
} elseif (isset($_GET['OrderNumber']) and $_GET['OrderNumber']>0) {

	unset($_SESSION['Items']->LineItems);
	unset ($_SESSION['Items']);

	$_SESSION['ProcessingOrder']=(int)$_GET['OrderNumber'];
	$_GET['OrderNumber']=(int)$_GET['OrderNumber'];
	$_SESSION['Items'] = new cart;
    
    
//--------------------------------------------------------------------------------------------------

    $sql_leadid="SELECT leadid FROM salesorders WHERE orderno='".$_GET['OrderNumber']."'";
    $result_leadid=DB_query($sql_leadid,$db);
    $myrow_leadid=DB_fetch_array($result_leadid);
    $leadid=$myrow_leadid['leadid'];
    
    $sql_enqid="SELECT enqtypeid FROM bio_leads WHERE leadid='".$myrow_leadid['leadid']."'";
    $result_enqid=DB_query($sql_enqid,$db);
    $myrow_enqid=DB_fetch_array($result_enqid);
    $enqid=$myrow_enqid['enqtypeid'];
 
    
    if($enqid==1){
    $result_advance1=DB_query("SELECT amount FROM bio_propsubsidy WHERE leadid=$leadid AND scheme=1",$db);
    $result_advance2=DB_query("SELECT amount FROM bio_propsubsidy WHERE leadid=$leadid AND scheme=2",$db);
    $result_advance3=DB_query("SELECT amount FROM bio_propsubsidy WHERE leadid=$leadid AND scheme=3",$db);
    $myrow_advance1=DB_fetch_array($result_advance1);
    $myrow_advance2=DB_fetch_array($result_advance2);
    $myrow_advance3=DB_fetch_array($result_advance3);
    $advance1=$myrow_advance1['amount']; 
    $advance2=$myrow_advance2['amount']; 
    $advance3=$myrow_advance3['amount'];    
    }elseif($enqid==2){
    $result_advance1=DB_query("SELECT amount FROM bio_cpsubsidy WHERE leadid=$leadid AND scheme=1",$db);
    $result_advance2=DB_query("SELECT amount FROM bio_cpsubsidy WHERE leadid=$leadid AND scheme=2",$db);
    $result_advance3=DB_query("SELECT amount FROM bio_cpsubsidy WHERE leadid=$leadid AND scheme=3",$db);
    $myrow_advance1=DB_fetch_array($result_advance1);
    $myrow_advance2=DB_fetch_array($result_advance2);
    $myrow_advance3=DB_fetch_array($result_advance3);
    $advance1=$myrow_advance1['amount']; 
    $advance2=$myrow_advance2['amount']; 
    $advance3=$myrow_advance3['amount'];    
    }elseif($enqid==3){ 
    $arr_fundagency=array();
    $arr_fundamount=array();
    $sql_fund="SELECT funding_agency,amount FROM bio_fundingagency WHERE leadid=(SELECT parent_leadid FROM bio_leads WHERE leadid=$leadid) AND propid=(SELECT propid FROM bio_lsg_proplead WHERE leadid=$leadid)";
    $result_fund=DB_query($sql_fund,$db);
    while($myrow_fund=DB_fetch_array($result_fund))
    {
        $arr_fundagency[]=$myrow_fund[0];      
        $arr_fundamount[]=$myrow_fund[1];
    } 
    
$count_fundingagency=count($arr_fundagency); 

    }
    
    $sql_debtortrans="SELECT SUM(ovamount) AS amountpaid FROM debtortrans WHERE order_=".$_GET['OrderNumber']."";
    $result_debtortrans=DB_query($sql_debtortrans,$db);
    $row_debtortrans=DB_fetch_array($result_debtortrans);
    $amountpaid=$row_debtortrans['amountpaid'];
    
//---------------------------------------------------------------------------------------------------    


/*read in all the guff from the selected order into the Items cart  */

	$OrderHeaderSQL = "SELECT salesorders.orderno,
								salesorders.debtorno,
								debtorsmaster.name,
								salesorders.branchcode,
								salesorders.customerref,
								salesorders.comments,
								salesorders.orddate,
								salesorders.ordertype,
								salesorders.shipvia,
								salesorders.deliverto,
								salesorders.deladd1,
								salesorders.deladd2,
								salesorders.deladd3,
								salesorders.deladd4,
								salesorders.deladd5,
								salesorders.deladd6,
								salesorders.contactphone,
								salesorders.contactemail,
								salesorders.freightcost,
								salesorders.deliverydate,
								debtorsmaster.currcode,
								salesorders.fromstkloc,
								locations.taxprovinceid,
								custbranch.taxgroupid,
								currencies.rate as currency_rate,
								currencies.decimalplaces,
								custbranch.defaultshipvia,
								custbranch.specialinstructions
						FROM salesorders,
							debtorsmaster,
							custbranch,
							currencies,
							locations
						WHERE salesorders.debtorno = debtorsmaster.debtorno
						AND salesorders.branchcode = custbranch.branchcode
						AND salesorders.debtorno = custbranch.debtorno
						AND locations.loccode=salesorders.fromstkloc
						AND debtorsmaster.currcode = currencies.currabrev
						AND salesorders.orderno = '" . $_GET['OrderNumber']."'";

	$ErrMsg = _('The order cannot be retrieved because');
	$DbgMsg = _('The SQL to get the order header was');
	$GetOrdHdrResult = DB_query($OrderHeaderSQL,$db,$ErrMsg,$DbgMsg);

	if (DB_num_rows($GetOrdHdrResult)==1) {

		$myrow = DB_fetch_array($GetOrdHdrResult);

		$_SESSION['Items']->DebtorNo = $myrow['debtorno'];
		$_SESSION['Items']->OrderNo = $myrow['orderno'];
		$_SESSION['Items']->Branch = $myrow['branchcode'];
		$_SESSION['Items']->CustomerName = $myrow['name'];
		$_SESSION['Items']->CustRef = $myrow['customerref'];
		$_SESSION['Items']->Comments = $myrow['comments'];
		$_SESSION['Items']->DefaultSalesType =$myrow['ordertype'];
		$_SESSION['Items']->DefaultCurrency = $myrow['currcode'];
		$_SESSION['Items']->CurrDecimalPlaces = $myrow['decimalplaces'];
		$BestShipper = $myrow['shipvia'];
		$_SESSION['Items']->ShipVia = $myrow['shipvia'];

		if (is_null($BestShipper)){
		   $BestShipper=0;
		}
		$_SESSION['Items']->DeliverTo = $myrow['deliverto'];
		$_SESSION['Items']->DeliveryDate = ConvertSQLDate($myrow['deliverydate']);
		$_SESSION['Items']->BrAdd1 = $myrow['deladd1'];
		$_SESSION['Items']->BrAdd2 = $myrow['deladd2'];
		$_SESSION['Items']->BrAdd3 = $myrow['deladd3'];
		$_SESSION['Items']->BrAdd4 = $myrow['deladd4'];
		$_SESSION['Items']->BrAdd5 = $myrow['deladd5'];
		$_SESSION['Items']->BrAdd6 = $myrow['deladd6'];
		$_SESSION['Items']->PhoneNo = $myrow['contactphone'];
		$_SESSION['Items']->Email = $myrow['contactemail'];
		$_SESSION['Items']->Location = $myrow['fromstkloc'];
		$_SESSION['Items']->FreightCost = $myrow['freightcost'];
		$_SESSION['Old_FreightCost'] = $myrow['freightcost'];
//		$_POST['ChargeFreightCost'] = $_SESSION['Old_FreightCost'];
		$_SESSION['Items']->Orig_OrderDate = $myrow['orddate'];
		$_SESSION['CurrencyRate'] = $myrow['currency_rate'];
		$_SESSION['Items']->TaxGroup = $myrow['taxgroupid'];
		$_SESSION['Items']->DispatchTaxProvince = $myrow['taxprovinceid'];
		$_SESSION['Items']->GetFreightTaxes();
		$_SESSION['Items']->SpecialInstructions = $myrow['specialinstructions'];

		DB_free_result($GetOrdHdrResult);

/*now populate the line items array with the sales order details records */

		$LineItemsSQL = "SELECT stkcode,
								stockmaster.description,
								stockmaster.controlled,
								stockmaster.serialised,
								stockmaster.volume,
								stockmaster.kgs,
								stockmaster.units,
								stockmaster.decimalplaces,
								stockmaster.mbflag,
								stockmaster.taxcatid,
								stockmaster.discountcategory,
								salesorderdetails.unitprice,
								salesorderdetails.quantity,
								salesorderdetails.discountpercent,
								salesorderdetails.actualdispatchdate,
								salesorderdetails.qtyinvoiced,
								salesorderdetails.narrative,
								salesorderdetails.orderlineno,
								salesorderdetails.poline,
								salesorderdetails.itemdue,
								stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost AS standardcost
							FROM salesorderdetails INNER JOIN stockmaster
							 	ON salesorderdetails.stkcode = stockmaster.stockid
							WHERE salesorderdetails.orderno ='" . $_GET['OrderNumber'] . "'
							AND salesorderdetails.quantity - salesorderdetails.qtyinvoiced >0
							ORDER BY salesorderdetails.orderlineno";

		$ErrMsg = _('The line items of the order cannot be retrieved because');
		$DbgMsg = _('The SQL that failed was');
		$LineItemsResult = DB_query($LineItemsSQL,$db,$ErrMsg,$DbgMsg);

		if (db_num_rows($LineItemsResult)>0) {

			while ($myrow=db_fetch_array($LineItemsResult)) {

				$_SESSION['Items']->add_to_cart($myrow['stkcode'],
											$myrow['quantity'],
											$myrow['description'],
											$myrow['unitprice'],
											$myrow['discountpercent'],
											$myrow['units'],
											$myrow['volume'],
											$myrow['kgs'],
											0,
											$myrow['mbflag'],
											$myrow['actualdispatchdate'],
											$myrow['qtyinvoiced'],
											$myrow['discountcategory'],
											$myrow['controlled'],
											$myrow['serialised'],
											$myrow['decimalplaces'],
											htmlspecialchars_decode($myrow['narrative']),
											'No',
											$myrow['orderlineno'],
											$myrow['taxcatid'],
											'',
											$myrow['itemdue'],
											$myrow['poline'],
											$myrow['standardcost']);	/*NB NO Updates to DB */

				/*Calculate the taxes applicable to this line item from the customer branch Tax Group and Item Tax Category */

				$_SESSION['Items']->GetTaxes($myrow['orderlineno']);

			} /* line items from sales order details */
		} else { /* there are no line items that have a quantity to deliver */
			echo '<br />';
			prnMsg( _('There are no ordered items with a quantity left to deliver. There is nothing left to invoice'));
			include('includes/footer.inc');
			exit;

		} //end of checks on returned data set
		DB_free_result($LineItemsResult);

	} else { /*end if the order was returned sucessfully */

		echo '<br />'.
		prnMsg( _('This order item could not be retrieved. Please select another order'), 'warn');
		include ('includes/footer.inc');
		exit;
	} //valid order returned from the entered order number
} else {
    
    //--------------------------------------------------------------------------------------------------

    $sql_leadid="SELECT leadid FROM salesorders WHERE orderno='".$_GET['invoiceorderno']."'";
    $result_leadid=DB_query($sql_leadid,$db);
    $myrow_leadid=DB_fetch_array($result_leadid);
    $leadid=$myrow_leadid['leadid'];
    
    $sql_enqid="SELECT enqtypeid FROM bio_leads WHERE leadid='".$myrow_leadid['leadid']."'";
    $result_enqid=DB_query($sql_enqid,$db);
    $myrow_enqid=DB_fetch_array($result_enqid);
    $enqid=$myrow_enqid['enqtypeid'];
 
    
    if($enqid==1){
    $result_advance1=DB_query("SELECT amount FROM bio_propsubsidy WHERE leadid=$leadid AND scheme=1",$db);
    $result_advance2=DB_query("SELECT amount FROM bio_propsubsidy WHERE leadid=$leadid AND scheme=2",$db);
    $result_advance3=DB_query("SELECT amount FROM bio_propsubsidy WHERE leadid=$leadid AND scheme=3",$db);
    $myrow_advance1=DB_fetch_array($result_advance1);
    $myrow_advance2=DB_fetch_array($result_advance2);
    $myrow_advance3=DB_fetch_array($result_advance3);
    $advance1=$myrow_advance1['amount']; 
    $advance2=$myrow_advance2['amount']; 
    $advance3=$myrow_advance3['amount'];    
    }elseif($enqid==2){
    $result_advance1=DB_query("SELECT amount FROM bio_cpsubsidy WHERE leadid=$leadid AND scheme=1",$db);
    $result_advance2=DB_query("SELECT amount FROM bio_cpsubsidy WHERE leadid=$leadid AND scheme=2",$db);
    $result_advance3=DB_query("SELECT amount FROM bio_cpsubsidy WHERE leadid=$leadid AND scheme=3",$db);
    $myrow_advance1=DB_fetch_array($result_advance1);
    $myrow_advance2=DB_fetch_array($result_advance2);
    $myrow_advance3=DB_fetch_array($result_advance3);
    $advance1=$myrow_advance1['amount']; 
    $advance2=$myrow_advance2['amount']; 
    $advance3=$myrow_advance3['amount'];    
    }elseif($enqid==3){ 
    $arr_fundagency=array();
    $arr_fundamount=array();
    $sql_fund="SELECT funding_agency,amount FROM bio_fundingagency WHERE leadid=(SELECT parent_leadid FROM bio_leads WHERE leadid=$leadid) AND propid=(SELECT propid FROM bio_lsg_proplead WHERE leadid=$leadid)";
    $result_fund=DB_query($sql_fund,$db);
    while($myrow_fund=DB_fetch_array($result_fund))
    {
        $arr_fundagency[]=$myrow_fund[0];      
        $arr_fundamount[]=$myrow_fund[1];
    } 
    
$count_fundingagency=count($arr_fundagency); 

    }
    
    $sql_debtortrans="SELECT SUM(ovamount) AS amountpaid FROM debtortrans WHERE order_=".$_GET['invoiceorderno']."";
    $result_debtortrans=DB_query($sql_debtortrans,$db);
    $row_debtortrans=DB_fetch_array($result_debtortrans);
    $amountpaid=$row_debtortrans['amountpaid'];
    
//---------------------------------------------------------------------------------------------------   

/* if processing, a dispatch page has been called and ${$StkItm->LineNumber} would have been set from the post
set all the necessary session variables changed by the POST  */
	if (isset($_POST['ShipVia'])){
		$_SESSION['Items']->ShipVia = $_POST['ShipVia'];
	}
	if (isset($_POST['ChargeFreightCost'])){
		$_SESSION['Items']->FreightCost = $_POST['ChargeFreightCost'];
	}
	foreach ($_SESSION['Items']->FreightTaxes as $FreightTaxLine) {
		if (isset($_POST['FreightTaxRate'  . $FreightTaxLine->TaxCalculationOrder])){
			$_SESSION['Items']->FreightTaxes[$FreightTaxLine->TaxCalculationOrder]->TaxRate = $_POST['FreightTaxRate'  . $FreightTaxLine->TaxCalculationOrder]/100;
		}
	}

	foreach ($_SESSION['Items']->LineItems as $Itm) {
		if (sizeOf($Itm->SerialItems) > 0) {
			$_SESSION['Items']->LineItems[$Itm->LineNumber]->QtyDispatched = 0; //initialise QtyDispatched
			foreach ($Itm->SerialItems as $SerialItem) { //calculate QtyDispatched from bundle quantities
				$_SESSION['Items']->LineItems[$Itm->LineNumber]->QtyDispatched += $SerialItem->BundleQty;
			}
		} else if (is_numeric($_POST[$Itm->LineNumber .  '_QtyDispatched' ])AND $_POST[$Itm->LineNumber .  '_QtyDispatched'] <= ($_SESSION['Items']->LineItems[$Itm->LineNumber]->Quantity - $_SESSION['Items']->LineItems[$Itm->LineNumber]->QtyInv)){
			$_SESSION['Items']->LineItems[$Itm->LineNumber]->QtyDispatched = $_POST[$Itm->LineNumber  . '_QtyDispatched'];
		}
		foreach ($Itm->Taxes as $TaxLine) {
			if (isset($_POST[$Itm->LineNumber  . $TaxLine->TaxCalculationOrder . '_TaxRate'])){
				$_SESSION['Items']->LineItems[$Itm->LineNumber]->Taxes[$TaxLine->TaxCalculationOrder]->TaxRate = $_POST[$Itm->LineNumber  . $TaxLine->TaxCalculationOrder . '_TaxRate']/100;
			}
		}
	} //end foreach lineitem

}

/* Always display dispatch quantities and recalc freight for items being dispatched */

if ($_SESSION['Items']->SpecialInstructions) {
  prnMsg($_SESSION['Items']->SpecialInstructions,'warn');
}

 $ordno=$_SESSION['ProcessingOrder'];
echo'<input type=hidden name="orderno" id="orderno" value='.$ordno.'>';
echo'<div id="order_ch_lo"></div>';

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/inventory.png" title="' . _('Confirm Invoice') .
	'" alt="" />' . ' ' . _('Confirm Dispatch and Invoice'). '</p>';
echo '<table class="selection">
			<tr>
				<th><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Customer') . '" alt="" />' . ' ' . _('Customer Code') . ' :<b> ' . $_SESSION['Items']->DebtorNo.'</b></th>
				<th>' . _('Customer Name') . ' :<b> ' . $_SESSION['Items']->CustomerName. '</b></th>
			</tr>
			<tr>
				<th colspan ="2"><b>' . _('Invoice amounts stated in') . ' ' . $_SESSION['Items']->DefaultCurrency . '</b></th>
			</tr>';
			/*</table>
			<br />*/
            
            
echo "<tr>
        <th>"._('Location'). ":</th>
        <th><select tabindex=".$j." name='StockLocation' id='StockLocation' onchange='showitem()'  > ";

        $sql = 'SELECT loccode, locationname FROM locations ';//where loccode in (7,8)

        $resultStkLocs = DB_query($sql,$db);

        while ($myrow=DB_fetch_array($resultStkLocs))
        {
            if ( $myrow['loccode']==$_SESSION['Items']->Location ){
                 echo '<option selected value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
            } else {
                 echo '<option Value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
            }
        }

        echo '</select></th></table>';

echo '<form action="' . $_SERVER['PHP_SELF'] . '?OrderNumber='.$_SESSION['ProcessingOrder'].'" method=post>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<input type="hidden" name="OrderNumber" value="' . $_SESSION['ProcessingOrder'] . '" />';
echo '<input type=hidden name=enq id=enq value="'.$enqid.'">'; 
echo '<input type=hidden name=count id=count value="'.$count_fundingagency.'">'; 
/***************************************************************
	Line Item Display
***************************************************************/
echo '<table width="90%" cellpadding="2" colspan="7" class=selection>
	<tr>
		<th>' . _('Item Code') . '</th>
		<th>' . _('Item Description' ) . '</th>
		<th>' . _('Ordered') . '</th>
		<th>' . _('Units') . '</th>
		<th>' . _('Already') . '<br />' . _('Sent') . '</th>
		<th>' . _('This Dispatch') . '</th>
		<th>' . _('Price') . '</th>
		<th>' . _('Discount') . '</th>
		<th>' . _('Total') . '<br />' . _('Excl Tax') . '</th>
		<th>' . _('Tax Authority') . '</th>
		<th>' . _('Tax %') . '</th>
		<th>' . _('Tax') . '<br />' . _('Amount') . '</th>
		<th>' . _('Total') . '<br />' . _('Incl Tax') . '</th>
	</tr>';

$_SESSION['Items']->total = 0;
$_SESSION['Items']->totalVolume = 0;
$_SESSION['Items']->totalWeight = 0;
$TaxTotals = array();
$TaxGLCodes = array();
$TaxTotal =0;

/*show the line items on the order with the quantity being dispatched available for modification */

$k=0; //row colour counter
$j=0;
foreach ($_SESSION['Items']->LineItems as $LnItm) {
	$j++;
	if ($k==1){
		$RowStarter = '<tr class="EvenTableRows">';
		$k=0;
	} else {
		$RowStarter = '<tr class="OddTableRows">';
		$k=1;
	}
    
    
   
	$LineTotal = $LnItm->QtyDispatched * $LnItm->Price * (1 - $LnItm->DiscountPercent);

	$_SESSION['Items']->total += $LineTotal;
	$_SESSION['Items']->totalVolume += ($LnItm->QtyDispatched * $LnItm->Volume);
	$_SESSION['Items']->totalWeight += ($LnItm->QtyDispatched * $LnItm->Weight);

	echo '<td>'.$LnItm->StockID.'</td>
		<td>'.$LnItm->ItemDescription.'</td>
		<td class="number">' . number_format($LnItm->Quantity,$LnItm->DecimalPlaces) . '</td>
		<td>'.$LnItm->Units.'</td>
		<td class="number">' . number_format($LnItm->QtyInv,$LnItm->DecimalPlaces) . '</td>';
        
    $item=$LnItm->StockID;

    
    if($enqid==1){
        $sql_subsidy="SELECT SUM(amount) AS amount FROM bio_propsubsidy WHERE leadid=$leadid AND stockid='$item'";   
    }elseif($enqid==2){
        $sql_subsidy="SELECT SUM(amount) AS amount FROM bio_cpsubsidy WHERE leadid=$leadid AND stockid='$item'";    
    }elseif($enqid==3){
        $sql_subsidy="SELECT SUM(amount) AS amount FROM bio_fundingagency WHERE leadid=(SELECT parent_leadid FROM bio_leads WHERE leadid=$leadid) AND propid=(SELECT propid FROM bio_lsg_proplead WHERE leadid=$leadid)";
    }       
    $result_subsidy=DB_query($sql_subsidy,$db);
    $myrow_subsidy=DB_fetch_array($result_subsidy);
    $subsidy=$myrow_subsidy['amount'];    

                             
	if ($LnItm->Controlled==1){

		if (isset($_POST['ProcessInvoice'])) {
			echo '<td class="number">' . $LnItm->QtyDispatched . '</td>';
		} else {
			echo '<td class="number"><input type=hidden name="' . $LnItm->LineNumber . '_QtyDispatched"  value="' .
				$LnItm->QtyDispatched . '"><a href="' . $rootpath .'/ConfirmDispatchControlled_Invoice.php?LineNo='. $LnItm->LineNumber.'">' .$LnItm->QtyDispatched . '</a></td>';
		}
	} else {
		if (isset($_POST['ProcessInvoice'])) {
			echo '<td class="number">' .  $LnItm->QtyDispatched . '</td>';
		} else {
			echo '<td class="number"><input tabindex="'.$j.'" type="text" class="number" name="' . $LnItm->LineNumber .
				'_QtyDispatched" maxlength=12 size=12 value="' . $LnItm->QtyDispatched . '"></td>';
		}
	}
	$DisplayDiscountPercent = number_format($LnItm->DiscountPercent*100,2) . '%';
	$DisplayLineNetTotal = number_format($LineTotal,$_SESSION['Items']->CurrDecimalPlaces);
	$DisplayPrice = number_format($LnItm->Price,$_SESSION['Items']->CurrDecimalPlaces);
	echo '<td class="number">'.$DisplayPrice.'</td>
		<td class="number">'.$DisplayDiscountPercent.'</td>
		<td class="number">'.$DisplayLineNetTotal.'</td>';

	/*Need to list the taxes applicable to this line */
	echo '<td>';
	$i=0;           
	foreach ($_SESSION['Items']->LineItems[$LnItm->LineNumber]->Taxes AS $Tax) {
		if ($i>0){
			echo '<br />';
		}
		echo $Tax->TaxAuthDescription;
		$i++;
	}
	echo '</td>';
	echo '<td class="number">';

	$i=0; // initialise the number of taxes iterated through
	$TaxLineTotal =0; //initialise tax total for the line


	foreach ($LnItm->Taxes AS $Tax) {
		if (empty($TaxTotals[$Tax->TaxAuthID])) {
			$TaxTotals[$Tax->TaxAuthID]=0;
		}
		if ($i>0){
			echo '<br />';
		}
		if (isset($_POST['ProcessInvoice'])) {
			echo  $Tax->TaxRate*100;
		} else {
			echo '<input type="text" class="number" name="' . $LnItm->LineNumber . $Tax->TaxCalculationOrder .
				'_TaxRate" maxlength=4 size=4 value="' . $Tax->TaxRate*100 . '">';
		}
		$i++;
		if ($Tax->TaxOnTax ==1){
			$TaxTotals[$Tax->TaxAuthID] += ($Tax->TaxRate * ($LineTotal + $TaxLineTotal));
			$TaxLineTotal += ($Tax->TaxRate * ($LineTotal + $TaxLineTotal));
		} else {
			$TaxTotals[$Tax->TaxAuthID] += ($Tax->TaxRate * $LineTotal);
			$TaxLineTotal += ($Tax->TaxRate * $LineTotal);
		}
		$TaxGLCodes[$Tax->TaxAuthID] = $Tax->TaxGLCode;
	}
	echo '</td>';

    $TaxTotal += $TaxLineTotal;
           
   $Gross_subsidy += $subsidy;

	$DisplayTaxAmount = number_format($TaxLineTotal ,$_SESSION['Items']->CurrDecimalPlaces);
    
    $DisplayGrossLineTotal = number_format($LineTotal+ $TaxLineTotal,$_SESSION['Items']->CurrDecimalPlaces);
                                   
//    $DisplayGrossLineTotal = number_format($LineTotal+ $TaxLineTotal-$subsidy,$_SESSION['Items']->CurrDecimalPlaces);     
    
    $Displaysubsidy = number_format($subsidy,$_SESSION['Items']->CurrDecimalPlaces);   
    
    $Displayamountpaid = number_format($amountpaid,$_SESSION['Items']->CurrDecimalPlaces);
     
    
	echo '<td class="number">'.$DisplayTaxAmount.'</td><td class="number">'.$DisplayGrossLineTotal.'</td>';           // <td class="number">'.$Displaysubsidy.'</td>

	if ($LnItm->Controlled==1){
		if (!isset($_POST['ProcessInvoice'])) {
			echo '<td><a href="' . $rootpath . '/ConfirmDispatchControlled_Invoice.php?LineNo='. $LnItm->LineNumber.'">';
			if ($LnItm->Serialised==1){
				echo _('Enter Serial Numbers');
			} else { /*Just batch/roll/lot control */
				echo _('Enter Batch/Roll/Lot #');
			}
			echo '</a></td>';
		}
	}
	echo '</tr>';
	if (strlen($LnItm->Narrative)>1){
		$narrative=str_replace('\r\n','<br />', $LnItm->Narrative);
		echo $RowStarter . '<td colspan=12>' . stripslashes($narrative) . '</td></tr>';
	}
}//end foreach ($line)

/*Don't re-calculate freight if some of the order has already been delivered -
depending on the business logic required this condition may not be required.
It seems unfair to charge the customer twice for freight if the order
was not fully delivered the first time ?? */

if(!isset($_SESSION['Items']->FreightCost)) {
	if ($_SESSION['DoFreightCalc']==True){
		list ($FreightCost, $BestShipper) = CalcFreightCost($_SESSION['Items']->total,
														$_SESSION['Items']->BrAdd2,
														$_SESSION['Items']->BrAdd3,
														$_SESSION['Items']->totalVolume,
														$_SESSION['Items']->totalWeight,
														$_SESSION['Items']->Location,
														$db);
		$_SESSION['Items']->ShipVia = $BestShipper;
	}
  	if (is_numeric($FreightCost)){
		$FreightCost = $FreightCost / $_SESSION['CurrencyRate'];
  	} else {
		$FreightCost =0;
  	}
  	if (!is_numeric($BestShipper)){
  		$SQL =  "SELECT shipper_id FROM shippers WHERE shipper_id='" . $_SESSION['Default_Shipper'] . "'";
		$ErrMsg = _('There was a problem testing for a default shipper because');
		$TestShipperExists = DB_query($SQL,$db, $ErrMsg);
		if (DB_num_rows($TestShipperExists)==1){
			$BestShipper = $_SESSION['Default_Shipper'];
		} else {
			$SQL =  "SELECT shipper_id FROM shippers";
			$ErrMsg = _('There was a problem testing for a default shipper');
			$TestShipperExists = DB_query($SQL,$db, $ErrMsg);
			if (DB_num_rows($TestShipperExists)>=1){
				$ShipperReturned = DB_fetch_row($TestShipperExists);
				$BestShipper = $ShipperReturned[0];
			} else {
				prnMsg( _('There are no shippers defined') . '. ' . _('Please use the link below to set up shipping freight companies, the system expects the shipping company to be selected or a default freight company to be used'),'error');
				echo '<a href="' . $rootpath . 'Shippers.php">'. _('Enter') . '/' . _('Amend Freight Companies'). '</a>';
			}
		}
	}
}

if (isset($_POST['ChargeFreightCost']) and !is_numeric($_POST['ChargeFreightCost'])){
	$_POST['ChargeFreightCost'] =0;
}

echo '<tr>
	<td colspan="5" class="number">' . _('Order Freight Cost'). '</td>
	<td class="number">' . $_SESSION['Old_FreightCost'] . '</td>';

if ($_SESSION['DoFreightCalc']==True){
	echo '<td colspan="2" class="number">' ._('Recalculated Freight Cost'). '</td>
		<td class="number">' . $FreightCost . '</td>';
} else {
//	echo '<td colspan="1"></td>';
}
$j++;
if (!isset($_POST['ChargeFreightCost'])) {
	$_POST['ChargeFreightCost']=0;
}
if ($_SESSION['Items']->Any_Already_Delivered()==1 and (!isset($_SESSION['Items']->FreightCost) or $_POST['ChargeFreightCost']==0)) {
	echo '<td colspan=2 class=number>'. _('Charge Freight Cost inc Tax').'</td>
		<td><input tabindex='.$j.' type="text" class="number" size="10" maxlength="12" name="ChargeFreightCost" value="0"></td>';
	$_SESSION['Items']->FreightCost=0;
} else {
	echo '<td colspan=2 class=number>'. _('Charge Freight Cost inc Tax').'</td>';
	if (isset($_POST['ProcessInvoice'])) {
		echo '<td class=number>' . $_SESSION['Items']->FreightCost . '</td>';
	} else {
		echo '<td class=number><input tabindex='.$j.' type="text" class="number" size="10" maxlength="12"
				name="ChargeFreightCost" VALUE="' . $_SESSION['Items']->FreightCost . '"></td>';
	}
	$_GET['ChargeFreightCost'] = $_SESSION['Items']->FreightCost;
}

$FreightTaxTotal =0; //initialise tax total

echo '<td>';

$i=0; // initialise the number of taxes iterated through
foreach ($_SESSION['Items']->FreightTaxes as $FreightTaxLine) {
	if ($i>0){
		echo '<br />';
	}
	echo  $FreightTaxLine->TaxAuthDescription;
	$i++;
}

echo '</td><td class=number>';

$i=0;
foreach ($_SESSION['Items']->FreightTaxes as $FreightTaxLine) {
	if ($i>0){
		echo '<br />';
	}

	if (isset($_POST['ProcessInvoice'])) {
		echo   $FreightTaxLine->TaxRate * 100 ;
	} else {
		echo  '<input type="text" class="number" name="FreightTaxRate' . $FreightTaxLine->TaxCalculationOrder .
			'" maxlength="4" size="4" value="' . $FreightTaxLine->TaxRate * 100 . '">';
	}

	if ($FreightTaxLine->TaxOnTax ==1){
		$TaxTotals[$FreightTaxLine->TaxAuthID] += ($FreightTaxLine->TaxRate * ($_SESSION['Items']->FreightCost + $FreightTaxTotal));
		$FreightTaxTotal += ($FreightTaxLine->TaxRate * ($_SESSION['Items']->FreightCost + $FreightTaxTotal));
	} else {
		$TaxTotals[$FreightTaxLine->TaxAuthID] += ($FreightTaxLine->TaxRate * $_SESSION['Items']->FreightCost);
		$FreightTaxTotal += ($FreightTaxLine->TaxRate * $_SESSION['Items']->FreightCost);
	}
	$i++;
	$TaxGLCodes[$FreightTaxLine->TaxAuthID] = $FreightTaxLine->TaxGLCode;
}
echo '</td>';

echo '<td class="number">' . number_format($FreightTaxTotal,$_SESSION['Items']->CurrDecimalPlaces) . '</td>
	
    <td class="number">' . number_format($FreightTaxTotal+ $_POST['ChargeFreightCost'],$_SESSION['Items']->CurrDecimalPlaces) . '</td>
	</tr>';                                                                                                                           //  <td></td>

$TaxTotal += $FreightTaxTotal;

$DisplaySubTotal = number_format(($_SESSION['Items']->total + $_POST['ChargeFreightCost']),$_SESSION['Items']->CurrDecimalPlaces);


/* round the totals to avoid silly entries */
$TaxTotal = round($TaxTotal,2);
$_SESSION['Items']->total = round($_SESSION['Items']->total,$_SESSION['Items']->CurrDecimalPlaces);
$_POST['ChargeFreightCost'] = round($_POST['ChargeFreightCost'],$_SESSION['Items']->CurrDecimalPlaces);
//<td class="number"><hr><b>' . number_format($TaxTotal+($_SESSION['Items']->total + $_POST['ChargeFreightCost']),$_SESSION['Items']->CurrDecimalPlaces) . '</b><hr></td> 
//echo$total=$_SESSION['Items']->total;
//echo"<br>".$sub=$subsidy;

echo '<tr> 
	<td colspan="10" class="number">' . _('Invoice Totals'). '</td>
	<td class="number:><hr><b>'.$DisplaySubTotal.'</b><hr></td>
	<td colspan="2"></td>
	<td class="number"><hr><b>' . number_format($TaxTotal,$_SESSION['Items']->CurrDecimalPlaces) . '</b><hr></td>                        
	<td class="number"><hr><b>' . number_format((($TaxTotal+$_SESSION['Items']->total + $_POST['ChargeFreightCost'])),$_SESSION['Items']->CurrDecimalPlaces) . '</b><hr></td>
</tr>';      
             //<td class="number"></td> 
            // <hr><b>' . number_format($Gross_subsidy,$_SESSION['Items']->CurrDecimalPlaces) . '</b><hr>
            // <td class="number"><hr><b>' . number_format((($TaxTotal+$_SESSION['Items']->total + $_POST['ChargeFreightCost'])-$Gross_subsidy),$_SESSION['Items']->CurrDecimalPlaces) . '</b><hr></td>
if($advance1!=''){            
echo '<tr> 
     <td colspan="10" class="number">' . _('MNRE Subsidy'). '</td> 
     <td></td><td><input type=hidden name=subsidy1 id=subsidy1 value="'.$advance1.'"></td>
     <td class="number">'.number_format($advance1,$_SESSION['Items']->CurrDecimalPlaces).'</td>    
</tr>';  
}     
if($advance2!=''){            
echo '<tr> 
     <td colspan="10" class="number">' . _('Biotech Subsidy'). '</td> 
     <td></td><td><input type=hidden name=subsidy2 id=subsidy2 value="'.$advance2.'"></td>
     <td class="number">'.number_format($advance2,$_SESSION['Items']->CurrDecimalPlaces).'</td>    
</tr>';  
}   
if($advance3!=''){            
echo '<tr> 
     <td colspan="10" class="number">' . _('Special Discount'). '</td> 
     <td></td><td><input type=hidden name=subsidy3 id=subsidy3 value="'.$advance3.'"></td>
     <td class="number">'.number_format($advance3,$_SESSION['Items']->CurrDecimalPlaces).'</td>    
</tr>';  
}    

if($enqid==3)
{

    for($i=0;$i<$count_fundingagency;$i++)
    {
echo '<tr> 
      <td colspan="10" class="number">'.$arr_fundagency[$i].'</td>
      <td></td><td><input type=hidden name="fagency'.$i.'" id="fagency'.$i.'" value="'.$arr_fundagency[$i].'">
                   <input type=hidden name="famount'.$i.'" id="famount'.$i.'" value="'.$arr_fundamount[$i].'"></td>
      <td class="number">'.number_format($arr_fundamount[$i],$_SESSION['Items']->CurrDecimalPlaces).'</td>
      </tr>';    

    }
}     
    
    echo '<tr><br /><br /></tr>';        
            
echo '<tr> 
     <td colspan="10" class="number">' . _('Amount Paid'). '</td> 
     <td></td><td></td>
     <td class="number">'.$Displayamountpaid.'</td>    
</tr>';   
            
echo '<input type=text name=grosssubsidy style="display:none" id=grosssubsidy value='.$subsidy.'>';    // $Gross_subsidy     
                                                                           
 $total=($TaxTotal+$_SESSION['Items']->total + $_POST['ChargeFreightCost'])-$subsidy;     // echo $Gross_subsidy;
 $nettotal = $total-abs($amountpaid);
 $nettotal = number_format($nettotal,$_SESSION['Items']->CurrDecimalPlaces);
 
 
echo '<tr> 
     <td colspan="10" class="number"><b>' . _('Net Total'). '</b></td> 
     <td></td><td></td>
     <td class="number"><hr><b>'.$nettotal.'</b><hr></td>    
</tr>'; 
                        
if (! isset($_POST['DispatchDate']) OR  ! Is_Date($_POST['DispatchDate'])){
	$DefaultDispatchDate = Date($_SESSION['DefaultDateFormat'],CalcEarliestDispatchDate());
} else {
	$DefaultDispatchDate = $_POST['DispatchDate'];
}

echo '</table><br />';
                            
if (isset($_POST['ProcessInvoice']) AND $_POST['ProcessInvoice'] != ''){          
                 
                                                                                      
                                                                                      
                                                                                      
/* SQL to process the postings for sales invoices...                              

/*First check there are lines on the dipatch with quantities to invoice
invoices can have a zero amount but there must be a quantity to invoice */

	$QuantityInvoicedIsPositive = false;

	foreach ($_SESSION['Items']->LineItems as $OrderLine) {
		if ($OrderLine->QtyDispatched > 0){
			$QuantityInvoicedIsPositive =true;
		}
	}
	if (! $QuantityInvoicedIsPositive){
		prnMsg( _('There are no lines on this order with a quantity to invoice') . '. ' . _('No further processing has been done'),'error');
		include('includes/footer.inc');
		exit;
	}

	if ($_SESSION['ProhibitNegativeStock']==1){ // checks for negative stock after processing invoice
	//sadly this check does not combine quantities occuring twice on and order and each line is considered individually :-(
		$NegativesFound = false;
		foreach ($_SESSION['Items']->LineItems as $OrderLine) {
			$SQL = "SELECT stockmaster.description,
					   		locstock.quantity,
					   		stockmaster.mbflag
		 			FROM locstock
		 			INNER JOIN stockmaster
					ON stockmaster.stockid=locstock.stockid
					WHERE stockmaster.stockid='" . $OrderLine->StockID . "'
					AND locstock.loccode='" . $_SESSION['Items']->Location . "'";

			$ErrMsg = _('Could not retrieve the quantity left at the location once this order is invoiced (for the purposes of checking that stock will not go negative because)');
			$Result = DB_query($SQL,$db,$ErrMsg);
			$CheckNegRow = DB_fetch_array($Result);
			if (($CheckNegRow['mbflag']=='B' OR $CheckNegRow['mbflag']=='M') AND substr($OrderLine->StockID,0,4)!='ASSET'){
				if ($CheckNegRow['quantity'] < $OrderLine->QtyDispatched){
					prnMsg( _('Invoicing the selected order would result in negative stock. The system parameters are set to prohibit negative stocks from occurring. This invoice cannot be created until the stock on hand is corrected.'),'error',$OrderLine->StockID . ' ' . $CheckNegRow['description'] . ' - ' . _('Negative Stock Prohibited'));
					$NegativesFound = true;
				}
			} elseif ($CheckNegRow['mbflag']=='A') {

				/*Now look for assembly components that would go negative */
				$SQL = "SELECT bom.component,
							   stockmaster.description,
							   locstock.quantity-(" . $OrderLine->QtyDispatched  . "*bom.quantity) AS qtyleft
						FROM bom
						INNER JOIN locstock
						ON bom.component=locstock.stockid
						INNER JOIN stockmaster
						ON stockmaster.stockid=bom.component
						WHERE bom.parent='" . $OrderLine->StockID . "'
						AND locstock.loccode='" . $_SESSION['Items']->Location . "'
						AND effectiveafter <'" . Date('Y-m-d') . "'
						AND effectiveto >='" . Date('Y-m-d') . "'";

				$ErrMsg = _('Could not retrieve the component quantity left at the location once the assembly item on this order is invoiced (for the purposes of checking that stock will not go negative because)');
				$Result = DB_query($SQL,$db,$ErrMsg);
				while ($NegRow = DB_fetch_array($Result)){
					if ($NegRow['qtyleft']<0){
						prnMsg(_('Invoicing the selected order would result in negative stock for a component of an assembly item on the order. The system parameters are set to prohibit negative stocks from occurring. This invoice cannot be created until the stock on hand is corrected.'),'error',$NegRow['component'] . ' ' . $NegRow['description'] . ' - ' . _('Negative Stock Prohibited'));
						$NegativesFound = true;
					} // end if negative would result
				} //loop around the components of an assembly item
			}//end if its an assembly item - check component stock

		} //end of loop around items on the order for negative check

		if ($NegativesFound){
			echo '<div class="centre">
					<input type=submit name=Update Value=' . _('Update'). '></div>';
			include('includes/footer.inc');
			exit;
		}

	}//end of testing for negative stocks


/* Now Get the area where the sale is to from the branches table */

	$SQL = "SELECT area,
					defaultshipvia
			FROM custbranch
			WHERE custbranch.debtorno ='". $_SESSION['Items']->DebtorNo . "'
			AND custbranch.branchcode = '" . $_SESSION['Items']->Branch . "'";

	$ErrMsg = _('We were unable to load Area where the Sale is to from the BRANCHES table') . '. ' . _('Please remedy this');
	$Result = DB_query($SQL,$db, $ErrMsg);
	$myrow = DB_fetch_row($Result);
	$Area = $myrow[0];
	$DefaultShipVia = $myrow[1];
	DB_free_result($Result);

/*company record read in on login with info on GL Links and debtors GL account*/

	if ($_SESSION['CompanyRecord']==0){
		/*The company data and preferences could not be retrieved for some reason */
		prnMsg( _('The company information and preferences could not be retrieved') . ' - ' . _('see your system administrator'), 'error');
		include('includes/footer.inc');
		exit;
	}

/*Now need to check that the order details are the same as they were when they were read into the Items array. If they've changed then someone else may have invoiced them */

	$SQL = "SELECT stkcode,
					quantity,
					qtyinvoiced,
					orderlineno
				FROM salesorderdetails
				WHERE completed=0
				AND orderno = '" . $_SESSION['ProcessingOrder']."'";
	
	$Result = DB_query($SQL,$db);

	if (DB_num_rows($Result) != count($_SESSION['Items']->LineItems)){

	/*there should be the same number of items returned from this query as there are lines on the invoice - if  not 	then someone has already invoiced or credited some lines */

		if ($debug==1){
			echo '<br />'.$SQL;
			echo '<br />' . _('Number of rows returned by SQL') . ':' . DB_num_rows($Result);
			echo '<br />' . _('Count of items in the session') . ' ' . count($_SESSION['Items']->LineItems);
		}

		echo '<br />';
		prnMsg( _('This order has been changed or invoiced since this delivery was started to be confirmed') . '. ' . _('Processing halted') . '. ' . _('To enter and confirm this dispatch') . '/' . _('invoice the order must be re-selected and re-read again to update the changes made by the other user'), 'error');

		unset($_SESSION['Items']->LineItems);
		unset($_SESSION['Items']);
		unset($_SESSION['ProcessingOrder']);
		include('includes/footer.inc'); exit;
	}

	$Changes =0;

	while ($myrow = DB_fetch_array($Result)) {

		if ($_SESSION['Items']->LineItems[$myrow['orderlineno']]->Quantity != $myrow['quantity'] OR $_SESSION['Items']->LineItems[$myrow['orderlineno']]->QtyInv != $myrow['qtyinvoiced']) {

			echo '<br />'. _('Orig order for'). ' ' . $myrow['orderlineno'] . ' '. _('has a quantity of'). ' ' .
				$myrow['quantity'] . ' '. _('and an invoiced qty of'). ' ' . $myrow['qtyinvoiced'] . ' '.
				_('the session shows quantity of'). ' ' . $_SESSION['Items']->LineItems[$myrow['orderlineno']]->Quantity .
				' ' . _('and quantity invoice of'). ' ' . $_SESSION['Items']->LineItems[$myrow['orderlineno']]->QtyInv;

	                prnMsg( _('This order has been changed or invoiced since this delivery was started to be confirmed') . ' ' . _('Processing halted.') . ' ' . _('To enter and confirm this dispatch, it must be re-selected and re-read again to update the changes made by the other user'), 'error');
        	        echo '<br />';

                	echo '<div class="centre"><a href="'. $rootpath . '/SelectSalesOrder.php">'. _('Select a sales order for confirming deliveries and invoicing'). '</a></div>';

	                unset($_SESSION['Items']->LineItems);
        	        unset($_SESSION['Items']);
                	unset($_SESSION['ProcessingOrder']);
	                include('includes/footer.inc');
			exit;
		}
	} /*loop through all line items of the order to ensure none have been invoiced since started looking at this order*/

	DB_free_result($Result);

// *************************************************************************
//   S T A R T   O F   I N V O I C E   S Q L   P R O C E S S I N G
// *************************************************************************

/*Now Get the next invoice number - function in SQL_CommonFunctions*/

	$InvoiceNo = GetNextTransNo(10, $db);
	$PeriodNo = GetPeriod($DefaultDispatchDate, $db);

/*Start an SQL transaction */

	DB_Txn_Begin($db);

	if ($DefaultShipVia != $_SESSION['Items']->ShipVia){
		$SQL = "UPDATE custbranch
				SET defaultshipvia ='" . $_SESSION['Items']->ShipVia . "'
				WHERE debtorno='" . $_SESSION['Items']->DebtorNo . "'
					AND branchcode='" . $_SESSION['Items']->Branch . "'";
		$ErrMsg = _('Could not update the default shipping carrier for this branch because');
		$DbgMsg = _('The SQL used to update the branch default carrier was');
		$result = DB_query($SQL,$db, $ErrMsg, $DbgMsg, true);
	}

	$DefaultDispatchDate = FormatDateForSQL($DefaultDispatchDate);

/*Update order header for invoice charged on */
	$SQL = "UPDATE salesorders
			SET comments = CONCAT(comments,' Inv ','" . $InvoiceNo . "')
			WHERE orderno= '" . $_SESSION['ProcessingOrder']."'";

	$ErrMsg = _('CRITICAL ERROR') . ' ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales order header could not be updated with the invoice number');
	$DbgMsg = _('The following SQL to update the sales order was used');
	$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

/*Now insert the DebtorTrans */

	$SQL = "INSERT INTO debtortrans (transno,
									type,
									debtorno,
									branchcode,
									trandate,
									inputdate,
									prd,
									reference,
									tpe,
									order_,
									ovamount,
									ovgst,
									ovfreight,
									rate,
									invtext,
									shipvia,
									consignment )
								VALUES (
									'". $InvoiceNo . "',
									10,
									'" . $_SESSION['Items']->DebtorNo . "',
									'" . $_SESSION['Items']->Branch . "',
									'" . $DefaultDispatchDate . "',
									'" . date('Y-m-d H-i-s') . "',
									'" . $PeriodNo . "',
									'',
									'" . $_SESSION['Items']->DefaultSalesType . "',
									'" . $_SESSION['ProcessingOrder'] . "',
									'" . $_SESSION['Items']->total . "',
									'" . $TaxTotal . "',
									'" . $_POST['ChargeFreightCost'] . "',
									'" . $_SESSION['CurrencyRate'] . "',
									'" . $_POST['InvoiceText'] . "',
									'" . $_SESSION['Items']->ShipVia . "',
									'"  . $_POST['Consignment'] . "'	)";

	$ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction record could not be inserted because');
	$DbgMsg = _('The following SQL to insert the debtor transaction record was used');
 	$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

	$DebtorTransID = DB_Last_Insert_ID($db,'debtortrans','id');
    
 if($_POST['enq']==3)
 {
     
       for($j=0;$j<$_POST['count'];$j++)
       {   
           $DebitNo = GetNextTransNo(21, $db);
           
                   $SQL_subsidy1 = "INSERT INTO debtortrans (transno,
                                    type,
                                    debtorno,
                                    trandate,
                                    inputdate,
                                    prd,
                                    reference,
                                    tpe,
                                    order_,
                                    ovamount,
                                    ovfreight,
                                    rate,
                                    invtext,
                                    shipvia,
                                    consignment )
                                VALUES (
                                    '". $DebitNo . "',
                                    21,
                                    '".$_POST['fagency'.$j]."',
                                    '" . $DefaultDispatchDate . "',
                                    '" . date('Y-m-d H-i-s') . "',
                                    '" . $PeriodNo . "',
                                    '',
                                    '" . $_SESSION['Items']->DefaultSalesType . "',
                                    '" . $_SESSION['ProcessingOrder'] . "',
                                    '" . $_POST['famount'.$j] . "',
                                    '" . $_POST['ChargeFreightCost'] . "',
                                    '" . $_SESSION['CurrencyRate'] . "',
                                    '" . $_POST['InvoiceText'] . "',
                                    '" . $_SESSION['Items']->ShipVia . "',
                                    '"  . $_POST['Consignment'] . "'  )";

    $ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction record could not be inserted because');
    $DbgMsg = _('The following SQL to insert the debtor transaction record was used');
    $Result1 = DB_query($SQL_subsidy1,$db,$ErrMsg,$DbgMsg,true);
       }
       

       
 }   
  else
  {
   
if($_POST['subsidy1']!='') 
{  
   $DebitNo = GetNextTransNo(21, $db); 
    
        $SQL_subsidy1 = "INSERT INTO debtortrans (transno,
                                    type,
                                    debtorno,
                                    trandate,
                                    inputdate,
                                    prd,
                                    reference,
                                    tpe,
                                    order_,
                                    ovamount,
                                    ovfreight,
                                    rate,
                                    invtext,
                                    shipvia,
                                    consignment )
                                VALUES (
                                    '". $DebitNo . "',
                                    21,
                                    'MNRE',
                                    '" . $DefaultDispatchDate . "',
                                    '" . date('Y-m-d H-i-s') . "',
                                    '" . $PeriodNo . "',
                                    '',
                                    '" . $_SESSION['Items']->DefaultSalesType . "',
                                    '" . $_SESSION['ProcessingOrder'] . "',
                                    '" . $_POST['subsidy1'] . "',
                                    '" . $_POST['ChargeFreightCost'] . "',
                                    '" . $_SESSION['CurrencyRate'] . "',
                                    '" . $_POST['InvoiceText'] . "',
                                    '" . $_SESSION['Items']->ShipVia . "',
                                    '"  . $_POST['Consignment'] . "'  )";

    $ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction record could not be inserted because');
    $DbgMsg = _('The following SQL to insert the debtor transaction record was used');
    $Result1 = DB_query($SQL_subsidy1,$db,$ErrMsg,$DbgMsg,true);
}

if($_POST['subsidy2']!='') 
{     
       $DebitNo = GetNextTransNo(21, $db); 
    
        $SQL_subsidy2 = "INSERT INTO debtortrans (transno,
                                    type,
                                    debtorno,
                                    trandate,
                                    inputdate,
                                    prd,
                                    reference,
                                    tpe,
                                    order_,
                                    ovamount,
                                    ovfreight,
                                    rate,
                                    invtext,
                                    shipvia,
                                    consignment )
                                VALUES (
                                    '". $DebitNo . "',   
                                    21,
                                    'BCC',
                                    '" . $DefaultDispatchDate . "',
                                    '" . date('Y-m-d H-i-s') . "',
                                    '" . $PeriodNo . "',
                                    '',
                                    '" . $_SESSION['Items']->DefaultSalesType . "',
                                    '" . $_SESSION['ProcessingOrder'] . "',
                                    '" . $_POST['subsidy2'] . "',
                                    '" . $_POST['ChargeFreightCost'] . "',
                                    '" . $_SESSION['CurrencyRate'] . "',
                                    '" . $_POST['InvoiceText'] . "',
                                    '" . $_SESSION['Items']->ShipVia . "',
                                    '"  . $_POST['Consignment'] . "'    )";

    $ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction record could not be inserted because');
    $DbgMsg = _('The following SQL to insert the debtor transaction record was used');
    $Result2 = DB_query($SQL_subsidy2,$db,$ErrMsg,$DbgMsg,true);
}

if($_POST['subsidy3']!='') 
{      
    
           $DebitNo = GetNextTransNo(21, $db); 
    
        $SQL_subsidy3 = "INSERT INTO debtortrans (transno,
                                    type,
                                    debtorno,
                                    trandate,
                                    inputdate,
                                    prd,
                                    reference,
                                    tpe,
                                    order_,
                                    ovamount,
                                    ovfreight,
                                    rate,
                                    invtext,
                                    shipvia,
                                    consignment )
                                VALUES (
                                    '". $DebitNo . "',   
                                    21,
                                    'SD',
                                    '" . $DefaultDispatchDate . "',
                                    '" . date('Y-m-d H-i-s') . "',
                                    '" . $PeriodNo . "',
                                    '',
                                    '" . $_SESSION['Items']->DefaultSalesType . "',
                                    '" . $_SESSION['ProcessingOrder'] . "',
                                    '" . $_POST['subsidy3'] . "',
                                    '" . $_POST['ChargeFreightCost'] . "',
                                    '" . $_SESSION['CurrencyRate'] . "',
                                    '" . $_POST['InvoiceText'] . "',
                                    '" . $_SESSION['Items']->ShipVia . "',
                                    '"  . $_POST['Consignment'] . "'    )";

    $ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction record could not be inserted because');
    $DbgMsg = _('The following SQL to insert the debtor transaction record was used');
    $Result3 = DB_query($SQL_subsidy3,$db,$ErrMsg,$DbgMsg,true);
}    

  }
   $sql_totalfund="SELECT SUM(ovamount) FROM debtortrans WHERE type=21 AND order_='" . $_SESSION['ProcessingOrder'] . "'";
   $result_totalfund=DB_query($sql_totalfund,$db);
   $myrow_totalfund=DB_fetch_array($result_totalfund);
   
  
        $CreditNo = GetNextTransNo(11, $db); 
    
        $SQL_subsidy = "INSERT INTO debtortrans (transno,
                                    type,
                                    debtorno,
                                    trandate,
                                    inputdate,
                                    prd,
                                    reference,
                                    tpe,
                                    order_,
                                    ovamount,
                                    ovfreight,
                                    rate,
                                    invtext,
                                    shipvia,
                                    consignment )
                                VALUES (
                                    '". $CreditNo . "',   
                                    11,
                                    '" . $_SESSION['Items']->DebtorNo . "',  
                                    '" . $DefaultDispatchDate . "',
                                    '" . date('Y-m-d H-i-s') . "',
                                    '" . $PeriodNo . "',
                                    '',
                                    '" . $_SESSION['Items']->DefaultSalesType . "',
                                    '" . $_SESSION['ProcessingOrder'] . "',
                                    '" . -$myrow_totalfund[0] . "',
                                    '" . $_POST['ChargeFreightCost'] . "',
                                    '" . $_SESSION['CurrencyRate'] . "',
                                    '" . $_POST['InvoiceText'] . "',
                                    '" . $_SESSION['Items']->ShipVia . "',
                                    '"  .$_POST['Consignment'] . "'    )";

    $ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction record could not be inserted because');
    $DbgMsg = _('The following SQL to insert the debtor transaction record was used');
    $Result_subsidy = DB_query($SQL_subsidy,$db,$ErrMsg,$DbgMsg,true);
    



/* Insert the tax totals for each tax authority where tax was charged on the invoice */
	foreach ($TaxTotals AS $TaxAuthID => $TaxAmount) {

		$SQL = "INSERT INTO debtortranstaxes (debtortransid,
											taxauthid,
											taxamount)
								VALUES ('" . $DebtorTransID . "',
									'" . $TaxAuthID . "',
									'" . $TaxAmount/$_SESSION['CurrencyRate'] . "')";

		$ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction taxes records could not be inserted because');
		$DbgMsg = _('The following SQL to insert the debtor transaction taxes record was used');
 		$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
	}


/* If balance of the order cancelled update sales order details quantity. Also insert log records for OrderDeliveryDifferencesLog */

	foreach ($_SESSION['Items']->LineItems as $OrderLine) {

		/*Test to see if the item being sold is an asset */
		if (substr($OrderLine->StockID,0,6)=='ASSET-'){
			$IsAsset = true;
			$HyphenOccursAt = strpos($OrderLine->StockID,'-',6);
			if ($HyphenOccursAt == false){
				$AssetNumber =   intval(substr($OrderLine->StockID,6));
			} else {
				$AssetNumber =   intval(substr($OrderLine->StockID,6,strlen($OrderLine->StockID)-$HyphenOccursAt-1));
			}
			prnMsg (_('The asset number beind disposed of is:') . ' ' . $AssetNumber, 'info');
		} else {
			$IsAsset = false;
			$AssetNumber = 0;
		}

		if ($_POST['BOPolicy']=='CAN'){

			$SQL = "UPDATE salesorderdetails
					SET quantity = quantity - " . ($OrderLine->Quantity - $OrderLine->QtyDispatched) . "
					WHERE orderno = '" . $_SESSION['ProcessingOrder'] . " '
						AND stkcode = '" . $OrderLine->StockID . "'";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales order detail record could not be updated because');
			$DbgMsg = _('The following SQL to update the sales order detail record was used');
			$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);


			if (($OrderLine->Quantity - $OrderLine->QtyDispatched)>0){

				$SQL = "INSERT INTO orderdeliverydifferenceslog ( orderno,
															invoiceno,
															stockid,
															quantitydiff,
															debtorno,
															branch,
															can_or_bo )
														VALUES (
															'" . $_SESSION['ProcessingOrder'] . "',
															'" . $InvoiceNo . "',
															'" . $OrderLine->StockID . "',
															'" . ($OrderLine->Quantity - $OrderLine->QtyDispatched) . "',
															'" . $_SESSION['Items']->DebtorNo . "',
															'" . $_SESSION['Items']->Branch . "',
															'CAN')";

				$ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The order delivery differences log record could not be inserted because');
				$DbgMsg = _('The following SQL to insert the order delivery differences record was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
			}



		} elseif (($OrderLine->Quantity - $OrderLine->QtyDispatched) >0 && DateDiff(ConvertSQLDate($DefaultDispatchDate),$_SESSION['Items']->DeliveryDate,'d') >0) {

		/*The order is being short delivered after the due date - need to insert a delivery differnce log */

			$SQL = "INSERT INTO orderdeliverydifferenceslog (	orderno,
															invoiceno,
															stockid,
															quantitydiff,
															debtorno,
															branch,
															can_or_bo
														)
														VALUES (
															'" . $_SESSION['ProcessingOrder'] . "',
															'" . $InvoiceNo . "',
															'" . $OrderLine->StockID . "',
															'" . ($OrderLine->Quantity - $OrderLine->QtyDispatched) . "',
															'" . $_SESSION['Items']->DebtorNo . "',
															'" . $_SESSION['Items']->Branch . "',
															'BO'
														)";

			$ErrMsg =  '<br />' . _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The order delivery differences log record could not be inserted because');
			$DbgMsg = _('The following SQL to insert the order delivery differences record was used');
			$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
		} /*end of order delivery differences log entries */

/*Now update SalesOrderDetails for the quantity invoiced and the actual dispatch dates. */

		if ($OrderLine->QtyDispatched !=0 AND $OrderLine->QtyDispatched!='' AND $OrderLine->QtyDispatched) {

			// Test above to see if the line is completed or not
			if ($OrderLine->QtyDispatched>=($OrderLine->Quantity - $OrderLine->QtyInv) OR $_POST['BOPolicy']=='CAN'){
				$SQL = "UPDATE salesorderdetails	
							SET qtyinvoiced = qtyinvoiced + " . $OrderLine->QtyDispatched . ",
								actualdispatchdate = '" . $DefaultDispatchDate .  "',
								completed=1
							WHERE orderno = '" . $_SESSION['ProcessingOrder'] . "'
							AND orderlineno = '" . $OrderLine->LineNumber . "'";
			} else {
				$SQL = "UPDATE salesorderdetails 	
							SET qtyinvoiced = qtyinvoiced + " . $OrderLine->QtyDispatched . ",
								actualdispatchdate = '" . $DefaultDispatchDate .  "'
							WHERE orderno = '" . $_SESSION['ProcessingOrder'] . "'
							AND orderlineno = '" . $OrderLine->LineNumber . "'";

			}

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales order detail record could not be updated because');
			$DbgMsg = _('The following SQL to update the sales order detail record was used');
			$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

			 /* Update location stock records if not a dummy stock item
			 need the MBFlag later too so save it to $MBFlag */
			$Result = DB_query("SELECT mbflag
								FROM stockmaster
								WHERE stockid = '" . $OrderLine->StockID . "'",$db, _('Cannot retrieve the mbflag'));

			$myrow = DB_fetch_row($Result);
			$MBFlag = $myrow[0];

			if ($MBFlag=='B' OR $MBFlag=='M') {
				$Assembly = False;

				/* Need to get the current location quantity
				will need it later for the stock movement */
               	$SQL="SELECT locstock.quantity
				FROM locstock
				WHERE locstock.stockid='" . $OrderLine->StockID . "'
				AND loccode= '" . $_SESSION['Items']->Location . "'";
				$ErrMsg = _('WARNING') . ': ' . _('Could not retrieve current location stock');
				$Result = DB_query($SQL, $db, $ErrMsg);

				if (DB_num_rows($Result)==1){
                       			$LocQtyRow = DB_fetch_row($Result);
                       			$QtyOnHandPrior = $LocQtyRow[0];
				} else {
					/* There must be some error this should never happen */
					$QtyOnHandPrior = 0;
				}

				$SQL = "UPDATE locstock	SET quantity = locstock.quantity - " . $OrderLine->QtyDispatched . "
						WHERE locstock.stockid = '" . $OrderLine->StockID . "'
						AND loccode = '" . $_SESSION['Items']->Location . "'";
	
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Location stock record could not be updated because');
				$DbgMsg = _('The following SQL to update the location stock record was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

			} else if ($MBFlag=='A'){ /* its an assembly */
				/*Need to get the BOM for this part and make
				stock moves for the components then update the Location stock balances */
				$Assembly=True;
				$StandardCost =0; /*To start with - accumulate the cost of the comoponents for use in journals later on */
				$SQL = "SELECT bom.component,
								bom.quantity,
								stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standard
							FROM bom,
								stockmaster
							WHERE bom.component=stockmaster.stockid
							AND bom.parent='" . $OrderLine->StockID . "'
							AND bom.effectiveto >= '" . Date('Y-m-d') . "'
							AND bom.effectiveafter < '" . Date('Y-m-d') . "'";
		
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not retrieve assembly components from the database for'). ' '. $OrderLine->StockID . _('because').' ';
				$DbgMsg = _('The SQL that failed was');
				$AssResult = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

				while ($AssParts = DB_fetch_array($AssResult,$db)){

					$StandardCost += ($AssParts['standard'] * $AssParts['quantity']) ;
					/* Need to get the current location quantity
					will need it later for the stock movement */
	                  		$SQL="SELECT locstock.quantity
							FROM locstock
							WHERE locstock.stockid='" . $AssParts['component'] . "'
							AND loccode= '" . $_SESSION['Items']->Location . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Can not retrieve assembly components location stock quantities because ');
					$DbgMsg = _('The SQL that failed was');
					$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
	                  		if (DB_num_rows($Result)==1){
	                  			$LocQtyRow = DB_fetch_row($Result);
	                  			$QtyOnHandPrior = $LocQtyRow[0];
					} else {
						/*There must be some error this should never happen */
						$QtyOnHandPrior = 0;
					}
					if (empty($AssParts['standard'])) {
						$AssParts['standard']=0;
					}
					$SQL = "INSERT INTO stockmoves (	stockid,
													type,
													transno,
													loccode,
													trandate,
													debtorno,
													branchcode,
													prd,
													reference,
													qty,
													standardcost,
													show_on_inv_crds,
													newqoh) 
										VALUES ('" . $AssParts['component'] . "',
												 10,
												 '" . $InvoiceNo . "',
												 '" . $_SESSION['Items']->Location . "',
												 '" . $DefaultDispatchDate . "',
												 '" . $_SESSION['Items']->DebtorNo . "',
												 '" . $_SESSION['Items']->Branch . "',
												 '" . $PeriodNo . "',
												 '" . _('Assembly') . ': ' . $OrderLine->StockID . ' ' . _('Order') . ': ' . $_SESSION['ProcessingOrder'] . "',
												 '" . -$AssParts['quantity'] * $OrderLine->QtyDispatched . "',
												 '" . $AssParts['standard'] . "',
												 0,
												 '" . ($QtyOnHandPrior - $AssParts['quantity'] * $OrderLine->QtyDispatched) . "'	)";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records for the assembly components of'). ' '. $OrderLine->StockID . ' ' . _('could not be inserted because');
					$DbgMsg = _('The following SQL to insert the assembly components stock movement records was used');
					$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);


					$SQL = "UPDATE locstock
							SET quantity = locstock.quantity - " . $AssParts['quantity'] * $OrderLine->QtyDispatched . "
							WHERE locstock.stockid = '" . $AssParts['component'] . "'
							AND loccode = '" . $_SESSION['Items']->Location . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Location stock record could not be updated for an assembly component because');
					$DbgMsg = _('The following SQL to update the locations stock record for the component was used');
					$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
				} /* end of assembly explosion and updates */

				/*Update the cart with the recalculated standard cost from the explosion of the assembly's components*/
				$_SESSION['Items']->LineItems[$OrderLine->LineNumber]->StandardCost = $StandardCost;
				$OrderLine->StandardCost = $StandardCost;
			} /* end of its an assembly */

			// Insert stock movements - with unit cost
			$LocalCurrencyPrice= ($OrderLine->Price / $_SESSION['CurrencyRate']);

			if (empty($OrderLine->StandardCost)) {
				$OrderLine->StandardCost=0;
			}
			if ($MBFlag=='B' OR $MBFlag=='M'){
            			$SQL = "INSERT INTO stockmoves (	stockid,
												type,
												transno,
												loccode,
												trandate,
												debtorno,
												branchcode,
												price,
												prd,
												reference,
												qty,
												discountpercent,
												standardcost,
												newqoh,
												narrative )
											VALUES ('" . $OrderLine->StockID . "',
												10,
												'" . $InvoiceNo . "',
												'" . $_SESSION['Items']->Location . "',
												'" . $DefaultDispatchDate . "',
												'" . $_SESSION['Items']->DebtorNo . "',
												'" . $_SESSION['Items']->Branch . "',
												'" . $LocalCurrencyPrice . "',
												'" . $PeriodNo . "',
												'" . $_SESSION['ProcessingOrder'] . "',
												'" . -$OrderLine->QtyDispatched . "',
												'" . $OrderLine->DiscountPercent . "',
												'" . $OrderLine->StandardCost . "',
												'" . ($QtyOnHandPrior - $OrderLine->QtyDispatched) . "',
												'" . DB_escape_string($OrderLine->Narrative) . "' )";
			} else {
            // its an assembly or dummy and assemblies/dummies always have nil stock (by definition they are made up at the time of dispatch  so new qty on hand will be nil
				if (empty($OrderLine->StandardCost)) {
					$OrderLine->StandardCost=0;
				}
				$SQL = "INSERT INTO stockmoves (stockid,
												type,
												transno,
												loccode,
												trandate,
												debtorno,
												branchcode,
												price,
												prd,
												reference,
												qty,
												discountpercent,
												standardcost,
												narrative )
											VALUES ('" . $OrderLine->StockID . "',
												10,
												'" . $InvoiceNo . "',
												'" . $_SESSION['Items']->Location . "',
												'" . $DefaultDispatchDate . "',
												'" . $_SESSION['Items']->DebtorNo . "',
												'" . $_SESSION['Items']->Branch . "',
												'" . $LocalCurrencyPrice . "',
												'" . $PeriodNo . "',
												'" . $_SESSION['ProcessingOrder'] . "',
												'" . -$OrderLine->QtyDispatched . "',
												'" . $OrderLine->DiscountPercent . "',
												'" . $OrderLine->StandardCost . "',
												'" . DB_escape_string($OrderLine->Narrative) . "')";
			}


			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records could not be inserted because');
			$DbgMsg = _('The following SQL to insert the stock movement records was used');
			$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

/*Get the ID of the StockMove... */
			$StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');

/*Insert the taxes that applied to this line */
			foreach ($OrderLine->Taxes as $Tax) {

				$SQL = "INSERT INTO stockmovestaxes (stkmoveno,
													taxauthid,
													taxrate,
													taxcalculationorder,
													taxontax)
										VALUES ('" . $StkMoveNo . "',
											'" . $Tax->TaxAuthID . "',
											'" . $Tax->TaxRate . "',
											'" . $Tax->TaxCalculationOrder . "',
											'" . $Tax->TaxOnTax . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Taxes and rates applicable to this invoice line item could not be inserted because');
				$DbgMsg = _('The following SQL to insert the stock movement tax detail records was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
			}


/*Insert the StockSerialMovements and update the StockSerialItems  for controlled items*/

			if ($OrderLine->Controlled ==1){
				foreach($OrderLine->SerialItems as $Item){
                   /*We need to add the StockSerialItem record and the StockSerialMoves as well */

					$SQL = "UPDATE stockserialitems	SET quantity= quantity - " . $Item->BundleQty . "
							WHERE stockid='" . $OrderLine->StockID . "'
							AND loccode='" . $_SESSION['Items']->Location . "'
							AND serialno='" . $Item->BundleRef . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated because');
					$DbgMsg = _('The following SQL to update the serial stock item record was used');
					$Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

					/* now insert the serial stock movement */

					$SQL = "INSERT INTO stockserialmoves (stockmoveno,
														stockid,
														serialno,
														moveqty)
									VALUES ('" . $StkMoveNo . "',
											'" . $OrderLine->StockID . "',
											'" . $Item->BundleRef . "',
											'" . -$Item->BundleQty . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
					$DbgMsg = _('The following SQL to insert the serial stock movement records was used');
					$Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
				}/* foreach controlled item in the serialitems array */
			} /*end if the orderline is a controlled item */

/*Insert Sales Analysis records */

			$SQL="SELECT COUNT(*),
						salesanalysis.stockid,
						salesanalysis.stkcategory,
						salesanalysis.cust,
						salesanalysis.custbranch,
						salesanalysis.area,
						salesanalysis.periodno,
						salesanalysis.typeabbrev,
						salesanalysis.salesperson
					FROM salesanalysis,
						custbranch,
						stockmaster
					WHERE salesanalysis.stkcategory=stockmaster.categoryid
					AND salesanalysis.stockid=stockmaster.stockid
					AND salesanalysis.cust=custbranch.debtorno
					AND salesanalysis.custbranch=custbranch.branchcode
					AND salesanalysis.area=custbranch.area
					AND salesanalysis.salesperson=custbranch.salesman
					AND salesanalysis.typeabbrev ='" . $_SESSION['Items']->DefaultSalesType . "'
					AND salesanalysis.periodno='" . $PeriodNo . "'
					AND salesanalysis.cust " . LIKE . " '" . $_SESSION['Items']->DebtorNo . "'
					AND salesanalysis.custbranch " . LIKE . " '" . $_SESSION['Items']->Branch . "'
					AND salesanalysis.stockid " . LIKE . " '" . $OrderLine->StockID . "'
					AND salesanalysis.budgetoractual=1
					GROUP BY salesanalysis.stockid,
						salesanalysis.stkcategory,
						salesanalysis.cust,
						salesanalysis.custbranch,
						salesanalysis.area,
						salesanalysis.periodno,
						salesanalysis.typeabbrev,
						salesanalysis.salesperson";

			$ErrMsg = _('The count of existing Sales analysis records could not run because');
			$DbgMsg = '<br />'. _('SQL to count the no of sales analysis records');
			$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

			$myrow = DB_fetch_row($Result);

			if ($myrow[0]>0){  /*Update the existing record that already exists */

				$SQL = "UPDATE salesanalysis	SET amt=amt+" . ($OrderLine->Price * $OrderLine->QtyDispatched / $_SESSION['CurrencyRate']) . ",
												cost=cost+" . ($OrderLine->StandardCost * $OrderLine->QtyDispatched) . ",
												qty=qty +" . $OrderLine->QtyDispatched . ",
												disc=disc+" . ($OrderLine->DiscountPercent * $OrderLine->Price * $OrderLine->QtyDispatched / $_SESSION['CurrencyRate']) . "
								WHERE salesanalysis.area='" . $myrow[5] . "'
								AND salesanalysis.salesperson='" . $myrow[8] . "'
								AND typeabbrev ='" . $_SESSION['Items']->DefaultSalesType . "'
								AND periodno = '" . $PeriodNo . "'
								AND cust " . LIKE . " '" . $_SESSION['Items']->DebtorNo . "'
								AND custbranch " . LIKE . " '" . $_SESSION['Items']->Branch . "'
								AND stockid " . LIKE . " '" . $OrderLine->StockID . "'
								AND salesanalysis.stkcategory ='" . $myrow[2] . "'
								AND budgetoractual=1";

			} else { /* insert a new sales analysis record */

				$SQL = "INSERT INTO salesanalysis (typeabbrev,
												periodno,
												amt,
												cost,
												cust,
												custbranch,
												qty,
												disc,
												stockid,
												area,
												budgetoractual,
												salesperson,
												stkcategory )
								SELECT '" . $_SESSION['Items']->DefaultSalesType . "',
										'" . $PeriodNo . "',
										'" . ($OrderLine->Price * $OrderLine->QtyDispatched / $_SESSION['CurrencyRate']) . "',
										'" . ($OrderLine->StandardCost * $OrderLine->QtyDispatched) . "',
										'" . $_SESSION['Items']->DebtorNo . "',
										'" . $_SESSION['Items']->Branch . "',
										'" . $OrderLine->QtyDispatched . "',
										'" . ($OrderLine->DiscountPercent * $OrderLine->Price * $OrderLine->QtyDispatched / $_SESSION['CurrencyRate']) . "',
										'" . $OrderLine->StockID . "',
										custbranch.area,
										1,
										custbranch.salesman,
										stockmaster.categoryid
								FROM stockmaster,
									custbranch
								WHERE stockmaster.stockid = '" . $OrderLine->StockID . "'
								AND custbranch.debtorno = '" . $_SESSION['Items']->DebtorNo . "'
								AND custbranch.branchcode='" . $_SESSION['Items']->Branch . "'";
			}

			$ErrMsg = _('Sales analysis record could not be added or updated because');
			$DbgMsg = _('The following SQL to insert the sales analysis record was used');
			$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

/* If GLLink_Stock then insert GLTrans to credit stock and debit cost of sales at standard cost*/

			if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $OrderLine->StandardCost !=0 AND ! $IsAsset){

/*first the cost of sales entry - GL accounts are retrieved using the function GetCOGSGLAccount from includes/GetSalesTransGLCodes.inc  */

				$SQL = "INSERT INTO gltrans (  type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES (
										10,
										'" . $InvoiceNo . "',
										'" . $DefaultDispatchDate . "',
										'" . $PeriodNo . "',
										'" . GetCOGSGLAccount($Area, $OrderLine->StockID, $_SESSION['Items']->DefaultSalesType, $db) . "',
										'" . $_SESSION['Items']->DebtorNo . " - " . $OrderLine->StockID . " x " . $OrderLine->QtyDispatched . " @ " . $OrderLine->StandardCost . "',
										'" . $OrderLine->StandardCost * $OrderLine->QtyDispatched . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The cost of sales GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

/*now the stock entry - this is set to the cost act in the case of a fixed asset disposal */
				$StockGLCode = GetStockGLCode($OrderLine->StockID,$db);

				$SQL = "INSERT INTO gltrans (	type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES (
										10,
										'" . $InvoiceNo . "',
										'" . $DefaultDispatchDate . "',
										'" . $PeriodNo . "',
										'" . $StockGLCode['stockact'] . "',
										'" . $_SESSION['Items']->DebtorNo . " - " . $OrderLine->StockID . " x " . $OrderLine->QtyDispatched . " @ " . $OrderLine->StandardCost . "',
										'" . (-$OrderLine->StandardCost * $OrderLine->QtyDispatched) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock side of the cost of sales GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
			} /* end of if GL and stock integrated and standard cost !=0  and not an asset */

			if ($_SESSION['CompanyRecord']['gllink_debtors']==1 AND $OrderLine->Price !=0){

				if (!$IsAsset){ // its a normal stock item
					//Post sales transaction to GL credit sales
					$SalesGLAccounts = GetSalesGLAccount($Area, $OrderLine->StockID, $_SESSION['Items']->DefaultSalesType, $db);

					$SQL = "INSERT INTO gltrans (  type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount )
										VALUES (
											10,
											'" . $InvoiceNo . "',
											'" . $DefaultDispatchDate . "',
											'" . $PeriodNo . "',
											'" . $SalesGLAccounts['salesglcode'] . "',
											'" . $_SESSION['Items']->DebtorNo . " - " . $OrderLine->StockID . " x " . $OrderLine->QtyDispatched . " @ " . $OrderLine->Price . "',
											'" . (-$OrderLine->Price * $OrderLine->QtyDispatched/$_SESSION['CurrencyRate']) . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales GL posting could not be inserted because');
					$DbgMsg = '<br />' ._('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

					if ($OrderLine->DiscountPercent !=0){

						$SQL = "INSERT INTO gltrans ( type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount)
												VALUES (
													10,
													'" . $InvoiceNo . "',
													'" . $DefaultDispatchDate . "',
													'" . $PeriodNo . "',
													'" . $SalesGLAccounts['discountglcode'] . "',
													'" . $_SESSION['Items']->DebtorNo . " - " . $OrderLine->StockID . " @ " . ($OrderLine->DiscountPercent * 100) . "%',
													'" . ($OrderLine->Price * $OrderLine->QtyDispatched * $OrderLine->DiscountPercent/$_SESSION['CurrencyRate']) . "')";

						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales discount GL posting could not be inserted because');
						$DbgMsg = _('The following SQL to insert the GLTrans record was used');
						$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
					} /*end of if discount !=0 */

				} else {
					/* then the item being sold is an asset disposal
					 * the cost of sales account will be the gain or loss on disposal account
					 * from the fixed asset categories table */
					$SQL = "SELECT cost,
									accumdepn,
									costact,
									accumdepnact,
									disposalact
						FROM fixedassetcategories INNER JOIN fixedassets
						ON fixedassetcategories.categoryid = fixedassets.assetcategoryid
						WHERE assetid ='" . $AssetNumber . "'";
					$ErrMsg = _('The asset disposal GL posting details could not be retrieved because');
					$DbgMsg = _('The following SQL was used to get the asset posting details');
					$DisposalResult = DB_query( $SQL,$db,$ErrMsg,$DbgMsg);
					$DisposalRow = DB_fetch_array($DisposalResult);

				  /*Need to :
					 *  1.) debit accum depn  account with whole amount of accum depn
					 *  2.) credit cost account with the whole amount of the cost
					 *  3.) debit the disposal account with the NBV
					 *  4.) credit the disposal account with the sale proceeds net of discounts */

					/* 1 debit accum depn */
					if ($DisposalRow['accumdpen']!=0){
						$SQL = "INSERT INTO gltrans (  type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount)
											VALUES (
												10,
												'" . $InvoiceNo . "',
												'" . $DefaultDispatchDate . "',
												'" . $PeriodNo . "',
												'" . $DisposalRow['accumdepnact'] . "',
												'" . $_SESSION['Items']->DebtorNo . ' - ' . $OrderLine->StockID . ' ' . _('disposal') . "',
												'" . -$DisposalRow['accumdpen'] . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The reversal of accumulated depreciation GL posting on disposal could not be inserted because');
					$DbgMsg = _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
				}
				/* 2 credit cost  */
				if($DisposalRow['cost']!=0){
					$SQL = "INSERT INTO gltrans (	type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
										VALUES (
											10,
											'" . $InvoiceNo . "',
											'" . $DefaultDispatchDate . "',
											'" . $PeriodNo . "',
											'" . $DisposalRow['costact'] . "',
											'" . $_SESSION['Items']->DebtorNo . " - " . $OrderLine->StockID . ' ' . _('disposal') . "',
											'" . -$DisposalRow['cost'] . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The reversal of asset cost on dispoal GL posting could not be inserted because');
					$DbgMsg = _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
				}
				//3. Debit disposal account with NBV
				if($DisposalRow['cost']-$DisposalRow['accumdepn']!=0){
					$SQL = "INSERT INTO gltrans (  type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount )
										VALUES (
											10,
											'" . $InvoiceNo . "',
											'" . $DefaultDispatchDate . "',
											'" . $PeriodNo . "',
											'" . $DisposalRow['disposalact'] . "',
											'" . $_SESSION['Items']->DebtorNo . " - " . $OrderLine->StockID .  ' ' . _('disposal') . "',
											'" . ($DisposalRow['cost']-$DisposalRow['accumdepn']) . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The disposal net book value GL posting could not be inserted because');
					$DbgMsg = '<br />' ._('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
				}

				//4. Credit the disposal account with the proceeds
				$SQL = "INSERT INTO gltrans (  type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount )
									VALUES (
										10,
										'" . $InvoiceNo . "',
										'" . $DefaultDispatchDate . "',
										'" . $PeriodNo . "',
										'" . $DisposalRow['disposalact'] . "',
										'" . $_SESSION['Items']->DebtorNo . " - " . $OrderLine->StockID .  ' ' . _('disposal') . "',
										'" . (-$OrderLine->Price * $OrderLine->QtyDispatched* (1 - $OrderLine->DiscountPercent)/$_SESSION['CurrencyRate']) . "')";
	
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The disposal proceeds GL posting could not be inserted because');
				$DbgMsg = '<br />' ._('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

				} // end if the item being sold was an asset
			} /*end of if sales integrated with debtors */

			if ($IsAsset) {
				/* then the item being sold is an asset disposal
					* need to create fixedassettrans
					* set disposal date and proceeds
				 */
				$SQL = "INSERT INTO fixedassettrans (assetid,
													transtype,
													transno,
													periodno,
													inputdate,
													fixedassettranstype,
													amount,
													transdate)
										VALUES ('" . $AssetNumber . "',
												10,
												'" . $InvoiceNo . "',
												'" . $PeriodNo . "',
												'" . Date('Y-m-d') . "',
												'disposal',
												'" . ($OrderLine->Price * $OrderLine->QtyDispatched* (1 - $OrderLine->DiscountPercent)/$_SESSION['CurrencyRate']) . "',
												'" . $DefaultDispatchDate . "')";
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The fixed asset transaction could not be inserted because');
				$DbgMsg = '<br />' ._('The following SQL to insert the fixed asset transaction record was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

				$SQL = "UPDATE fixedassets 
						SET disposalproceeds ='" . ($OrderLine->Price * $OrderLine->QtyDispatched* (1 - $OrderLine->DiscountPercent)/$_SESSION['CurrencyRate']) . "',
							disposaldate ='" . $DefaultDispatchDate . "'
						WHERE assetid ='" . $AssetNumber . "'";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The fixed asset record could not be updated for the disposal because');
				$DbgMsg = '<br />' ._('The following SQL to update the fixed asset record was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

			}
		} /*Quantity dispatched is more than 0 */
	} /*end of OrderLine loop */


	if ($_SESSION['CompanyRecord']['gllink_debtors']==1){

/*Post debtors transaction to GL debit debtors, credit freight re-charged and credit sales */
		if (($_SESSION['Items']->total + $_SESSION['Items']->FreightCost + $TaxTotal) !=0) {
			$SQL = "INSERT INTO gltrans (	type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
									VALUES (
										10,
										'" . $InvoiceNo . "',
										'" . $DefaultDispatchDate . "',
										'" . $PeriodNo . "',
										'" . $_SESSION['CompanyRecord']['debtorsact'] . "',
										'" . $_SESSION['Items']->DebtorNo . "',
										'" . (($_SESSION['Items']->total + $_SESSION['Items']->FreightCost + $TaxTotal)/$_SESSION['CurrencyRate']) . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The total debtor GL posting could not be inserted because');
			$DbgMsg = _('The following SQL to insert the total debtors control GLTrans record was used');
			$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
		}

		/*Could do with setting up a more flexible freight posting schema that looks at the sales type and area of the customer branch to determine where to post the freight recovery */

		if ($_SESSION['Items']->FreightCost !=0) {
			$SQL = "INSERT INTO gltrans (
						type,
						typeno,
						trandate,
						periodno,
						account,
						narrative,
						amount	)
				VALUES (
					10,
					'" . $InvoiceNo . "',
					'" . $DefaultDispatchDate . "',
					'" . $PeriodNo . "',
					'" . $_SESSION['CompanyRecord']['freightact'] . "',
					'" . $_SESSION['Items']->DebtorNo . "',
					'" . (-($_SESSION['Items']->FreightCost)/$_SESSION['CurrencyRate']) . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The freight GL posting could not be inserted because');
			$DbgMsg = _('The following SQL to insert the GLTrans record was used');
			$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
		}
		foreach ( $TaxTotals as $TaxAuthID => $TaxAmount){
			if ($TaxAmount !=0 ){
				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
										VALUES (
											10,
											'" . $InvoiceNo . "',
											'" . $DefaultDispatchDate . "',
											'" . $PeriodNo . "',
											'" . $TaxGLCodes[$TaxAuthID] . "',
											'" . $_SESSION['Items']->DebtorNo . "',
											'" . (-$TaxAmount/$_SESSION['CurrencyRate']) . "')";
					
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The tax GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
			}
		}
	} /*end of if Sales and GL integrated */

	DB_Txn_Commit($db);
// *************************************************************************
//   E N D   O F   I N V O I C E   S Q L   P R O C E S S I N G
// *************************************************************************

	unset($_SESSION['Items']->LineItems);
	unset($_SESSION['Items']);
	unset($_SESSION['ProcessingOrder']);

	echo prnMsg( _('Invoice number'). ' '. $InvoiceNo .' '. _('processed'), 'success');

	echo '<br /><div class="centre">';

	if ($_SESSION['InvoicePortraitFormat']==0){
		echo '<img src="'.$rootpath.'/css/'.$theme.'/images/printer.png" title="' . _('Print') . '" alt="">' . ' ' . '<a target="_blank" href="'.$rootpath.'/PrintCustTrans.php?FromTransNo='.$InvoiceNo.'&InvOrCredit=Invoice&PrintPDF=True">'. _('Print this invoice'). ' (' . _('Landscape') . ')</a><br /><br />';
	} else {
		echo '<img src="'.$rootpath.'/css/'.$theme.'/images/printer.png" title="' . _('Print') . '" alt="">' . ' ' . '<a target="_blank" href="'.$rootpath.'/PrintCustTransPortrait.php?FromTransNo='.$InvoiceNo.'&InvOrCredit=Invoice&PrintPDF=True">'. _('Print this invoice'). ' (' . _('Portrait') . ')</a><br /><br />';
	}
	echo '<a href="'.$rootpath.'/SelectSalesOrder.php">'. _('Select another order for invoicing'). '</a><br /><br />';
	echo '<a href="'.$rootpath.'/SelectOrderItems.php?NewOrder=Yes">'._('Sales Order Entry').'</a></div><br />';
/*end of process invoice */


} else { /*Process Invoice not set so allow input of invoice data */

	if (!isset($_POST['Consignment'])) {
		$_POST['Consignment']='';
	}

	if (!isset($_POST['InvoiceText'])) {
		$_POST['InvoiceText']='';
	}
	$j++;
	echo '<table class=selection><tr>
		<td>' ._('Date On Invoice'). ':</td>
	<td><input tabindex='.$j.' type="text" maxlength="10" size="15"
	name="DispatchDate" value="'.$DefaultDispatchDate.'" id="datepicker"
	alt="'.$_SESSION['DefaultDateFormat'].'" class="date"></td>
	</tr>';
	$j++;
	echo '<tr>
		<td>' . _('Consignment Note Ref'). ':</td>
		<td><input tabindex='.$j.' type=text maxlength=15 size=15 name=Consignment value="' . $_POST['Consignment'] . '"></td>
	</tr>';
	$j++;
	echo '<tr>
		<td>'._('Action For Balance'). ':</td>
		<td><select tabindex='.$j.' name=BOPolicy><option selected Value="BO">'._('Automatically put balance on back order').'<option Value="CAN">'._('Cancel any quantities not delivered').'</select></td>
	</tr>';
    $j++;

	
	echo '<tr>
		<td>' ._('Invoice Text'). ':</td>
		<td><textarea tabindex="'.$j.'" name="InvoiceText" COLS="31" ROWS="5">' .$_POST['InvoiceText'] . '</textarea></td>  
	</tr>';
//  <td><textarea tabindex="'.$j.'" name="InvoiceText" COLS="31" ROWS="5">' . reverse_escape($_POST['InvoiceText']) . '</textarea></td>  
	$j++;
	echo '</table>
	<br /><div class="centre">
	<input type=submit tabindex='.$j.' name=Update Value=' . _('Update'). '><br />';

	$j++;
	echo '<br /><input type=submit tabindex='.$j.' name="ProcessInvoice" Value="'._('Process Invoice').'"</div>';
	echo '<input type=hidden name="ShipVia" VALUE="' . $_SESSION['Items']->ShipVia . '">';
}

echo '</form>';

include('includes/footer.inc');
?>
<script >
function reload(str)
{
location.href="?OrderNumber="+str;
}
function showitem()
{
      var locc=document.getElementById('StockLocation').value;//alert(locc);
    var orderno=document.getElementById('orderno').value;// alert(orderno);
  if(confirm("You want to change location"))
  {
   //alert(str);
    var locc=document.getElementById('StockLocation').value;//alert(locc);
    var orderno=document.getElementById('orderno').value;// alert(orderno);
//alert("gfdhg");
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
     // 
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("order_ch_lo").innerHTML=xmlhttp.responseText;
    reload(orderno);

    }
  } 
xmlhttp.open("GET","bio_Dispatch_invoice_loc_ajax.php?loc=" + locc + "&ordno=" +orderno  ,true);
xmlhttp.send();


  }
  else
  {
       reload(orderno);
  }
  
 
  
}

</script>
