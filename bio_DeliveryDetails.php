<?php
$PageSecurity = 80; 
/* $Id: DeliveryDetails.php 4555 2011-04-19 10:18:49Z daintree $ */

/*
This is where the delivery details are confirmed/entered/modified and the order committed to the database once the place order/modify order button is hit.
*/

include('includes/DefineCartClass.php');

/* Session started in header.inc for password checking the session will contain the details of the order from the Cart class object. The details of the order come from SelectOrderItems.php 			*/

include('includes/session.inc');
$title = _('Order Delivery Details');
include('includes/header.inc');
include('includes/FreightCalculation.inc');
include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['identifier'])) {
	$identifier=$_GET['identifier'];
}

unset($_SESSION['WarnOnce']);
if (!isset($_SESSION['Items'.$identifier]) OR !isset($_SESSION['Items'.$identifier]->DebtorNo)){
	prnMsg(_('This page can only be read if an order has been entered') . '. ' . _('To enter an order select customer transactions then sales order entry'),'error');
	include('includes/footer.inc');
	exit;
}

if ($_SESSION['Items'.$identifier]->ItemsOrdered == 0){
	prnMsg(_('This page can only be read if an there are items on the order') . '. ' . _('To enter an order select customer transactions then sales order entry'),'error');
	include('includes/footer.inc');
	exit;
}

/*Calculate the earliest dispacth date in DateFunctions.inc */

$EarliestDispatch = CalcEarliestDispatchDate();

if (isset($_POST['ProcessOrder']) OR isset($_POST['MakeRecurringOrder'])) {

	/*need to check for input errors in any case before order processed */
	$_POST['Update']='Yes rerun the validation checks';

	/*store the old freight cost before it is recalculated to ensure that there has been no change - test for change after freight recalculated and get user to re-confirm if changed */

	$OldFreightCost = round($_POST['FreightCost'],2);

}

if (isset($_POST['Update'])
	or isset($_POST['BackToLineDetails'])
	or isset($_POST['MakeRecurringOrder']))   {

	$InputErrors =0;
	if (strlen($_POST['DeliverTo'])<=1){
		$InputErrors =1;
		prnMsg(_('You must enter the person or company to whom delivery should be made'),'error');
	}
	if (strlen($_POST['BrAdd1'])<=1){
		$InputErrors =1;
		prnMsg(_('You should enter the street address in the box provided') . '. ' . _('Orders cannot be accepted without a valid street address'),'error');
	}
//	if (strpos($_POST['BrAdd1'],_('Box'))>0){
//		prnMsg(_('You have entered the word') . ' "' . _('Box') . '" ' . _('in the street address') . '. ' . _('Items cannot be delivered to') . ' ' ._('box') . ' ' . _('addresses'),'warn');
//	}
	if (!is_numeric($_POST['FreightCost'])){
		$InputErrors =1;
		prnMsg( _('The freight cost entered is expected to be numeric'),'error');
	}
	if (isset($_POST['MakeRecurringOrder']) AND $_POST['Quotation']==1){
		$InputErrors =1;
		prnMsg( _('A recurring order cannot be made from a quotation'),'error');
	}
	if (($_POST['DeliverBlind'])<=0){
		$InputErrors =1;
		prnMsg(_('You must select the type of packlist to print'),'error');
	}

/*	If (strlen($_POST['BrAdd3'])==0 OR !isset($_POST['BrAdd3'])){
		$InputErrors =1;
		echo "<br />A region or city must be entered.<br />";
	}

	Maybe appropriate in some installations but not here
	If (strlen($_POST['BrAdd2'])<=1){
		$InputErrors =1;
		echo "<br />You should enter the suburb in the box provided. Orders cannot be accepted without a valid suburb being entered.<br />";
	}

*/
// Check the date is OK
	if(isset($_POST['DeliveryDate']) and !Is_Date($_POST['DeliveryDate'])) {
		$InputErrors =1;
		prnMsg(_('An invalid date entry was made') . '. ' . _('The date entry must be in the format') . ' ' . $_SESSION['DefaultDateFormat'],'warn');
	}
// Check the date is OK
	if(isset($_POST['QuoteDate']) and !Is_Date($_POST['QuoteDate'])) {
		$InputErrors =1;
		prnMsg(_('An invalid date entry was made') . '. ' . _('The date entry must be in the format') . ' ' . $_SESSION['DefaultDateFormat'],'warn');
	}
// Check the date is OK
	if(isset($_POST['ConfirmedDate']) and !Is_Date($_POST['ConfirmedDate'])) {
		$InputErrors =1;
		 prnMsg(_('An invalid date entry was made') . '. ' . _('The date entry must be in the format') . ' ' . $_SESSION['DefaultDateFormat'],'warn');
	}

	 /* This check is not appropriate where orders need to be entered in retrospectively in some cases this check will be appropriate and this should be uncommented

	 elseif (Date1GreaterThanDate2(Date($_SESSION['DefaultDateFormat'],$EarliestDispatch), $_POST['DeliveryDate'])){
		$InputErrors =1;
		echo '<br /><b>' . _('The delivery details cannot be updated because you are attempting to set the date the order is to be dispatched earlier than is possible. No dispatches are made on Saturday and Sunday. Also, the dispatch cut off time is') .  $_SESSION['DispatchCutOffTime']  . _(':00 hrs. Orders placed after this time will be dispatched the following working day.');
	}

	*/

	if ($InputErrors==0){

		if ($_SESSION['DoFreightCalc']==True){
			list ($_POST['FreightCost'], $BestShipper) = CalcFreightCost($_SESSION['Items'.$identifier]->total, $_POST['BrAdd2'], $_POST['BrAdd3'], $_SESSION['Items'.$identifier]->totalVolume, $_SESSION['Items'.$identifier]->totalWeight, $_SESSION['Items'.$identifier]->Location, $db);
			if ( !empty($BestShipper) ){
				$_POST['FreightCost'] = round($_POST['FreightCost'],2);
				$_POST['ShipVia'] = $BestShipper;
			} else {
				prnMsg(_($_POST['FreightCost']),'warn');
			}
		}
		$sql = "SELECT custbranch.brname,
				custbranch.braddress1,
				custbranch.braddress2,
				custbranch.braddress3,
				custbranch.braddress4,
				custbranch.braddress5,
				custbranch.braddress6,
				custbranch.phoneno,
				custbranch.email,
				custbranch.defaultlocation,
				custbranch.defaultshipvia,
				custbranch.deliverblind,
				custbranch.specialinstructions,
				custbranch.estdeliverydays
			FROM custbranch
			WHERE custbranch.branchcode='" . $_SESSION['Items'.$identifier]->Branch . "'
			AND custbranch.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo . "'";

		$ErrMsg = _('The customer branch record of the customer selected') . ': ' . $_SESSION['Items'.$identifier]->CustomerName . ' ' . _('cannot be retrieved because');
		$DbgMsg = _('SQL used to retrieve the branch details was') . ':';
		$result =DB_query($sql,$db,$ErrMsg,$DbgMsg);
		if (DB_num_rows($result)==0){

			prnMsg(_('The branch details for branch code') . ': ' . $_SESSION['Items'.$identifier]->Branch . ' ' . _('against customer code') . ': ' . $_POST['Select'] . ' ' . _('could not be retrieved') . '. ' . _('Check the set up of the customer and branch'),'error');

			if ($debug==1){
				echo '<br />' . _('The SQL that failed to get the branch details was') . ':<br />' . $sql;
			}
			include('includes/footer.inc');
			exit;
		}
		if (!isset($_POST['SpecialInstructions'])) {
			$_POST['SpecialInstructions']='';
		}
		if (!isset($_POST['DeliveryDays'])){
			$_POST['DeliveryDays']=0;
		}
		if (!isset($_SESSION['Items'.$identifier])) {
			$myrow = DB_fetch_row($result);
			$_SESSION['Items'.$identifier]->DeliverTo = $myrow[0];
			$_SESSION['Items'.$identifier]->DelAdd1 = $myrow[1];
			$_SESSION['Items'.$identifier]->DelAdd2 = $myrow[2];
			$_SESSION['Items'.$identifier]->DelAdd3 = $myrow[3];
			$_SESSION['Items'.$identifier]->DelAdd4 = $myrow[4];
			$_SESSION['Items'.$identifier]->DelAdd5 = $myrow[5];
			$_SESSION['Items'.$identifier]->DelAdd6 = $myrow[6];
			$_SESSION['Items'.$identifier]->PhoneNo = $myrow[7];
			$_SESSION['Items'.$identifier]->Email = $myrow[8];
			$_SESSION['Items'.$identifier]->Location = $myrow[9];
			$_SESSION['Items'.$identifier]->ShipVia = $myrow[10];
			$_SESSION['Items'.$identifier]->DeliverBlind = $myrow[11];
			$_SESSION['Items'.$identifier]->SpecialInstructions = $myrow[12];
			$_SESSION['Items'.$identifier]->DeliveryDays = $myrow[13];
			$_SESSION['Items'.$identifier]->DeliveryDate = $_POST['DeliveryDate'];
			$_SESSION['Items'.$identifier]->QuoteDate = $_POST['QuoteDate'];
			$_SESSION['Items'.$identifier]->ConfirmedDate = $_POST['ConfirmedDate'];
			$_SESSION['Items'.$identifier]->CustRef = $_POST['CustRef'];
			$_SESSION['Items'.$identifier]->Comments = $_POST['Comments'];
			$_SESSION['Items'.$identifier]->FreightCost = round($_POST['FreightCost'],2);
			$_SESSION['Items'.$identifier]->Quotation = $_POST['Quotation'];
		} else {
			$_SESSION['Items'.$identifier]->DeliverTo = $_POST['DeliverTo'];
			$_SESSION['Items'.$identifier]->DelAdd1 = $_POST['BrAdd1'];
			$_SESSION['Items'.$identifier]->DelAdd2 = $_POST['BrAdd2'];
			$_SESSION['Items'.$identifier]->DelAdd3 = $_POST['BrAdd3'];
			$_SESSION['Items'.$identifier]->DelAdd4 = $_POST['BrAdd4'];
			$_SESSION['Items'.$identifier]->DelAdd5 = $_POST['BrAdd5'];
			$_SESSION['Items'.$identifier]->DelAdd6 = $_POST['BrAdd6'];
			$_SESSION['Items'.$identifier]->PhoneNo = $_POST['PhoneNo'];
			$_SESSION['Items'.$identifier]->Email = $_POST['Email'];
			$_SESSION['Items'.$identifier]->Location = $_POST['Location'];
			$_SESSION['Items'.$identifier]->ShipVia = $_POST['ShipVia'];
			$_SESSION['Items'.$identifier]->DeliverBlind = $_POST['DeliverBlind'];
			$_SESSION['Items'.$identifier]->SpecialInstructions = $_POST['SpecialInstructions'];
			$_SESSION['Items'.$identifier]->DeliveryDays = $_POST['DeliveryDays'];
			$_SESSION['Items'.$identifier]->DeliveryDate = $_POST['DeliveryDate'];
			$_SESSION['Items'.$identifier]->QuoteDate = $_POST['QuoteDate'];
			$_SESSION['Items'.$identifier]->ConfirmedDate = $_POST['ConfirmedDate'];
			$_SESSION['Items'.$identifier]->CustRef = $_POST['CustRef'];
			$_SESSION['Items'.$identifier]->Comments = $_POST['Comments'];
			$_SESSION['Items'.$identifier]->FreightCost = round($_POST['FreightCost'],2);
			$_SESSION['Items'.$identifier]->Quotation = $_POST['Quotation'];
		}
		/*$_SESSION['DoFreightCalc'] is a setting in the config.php file that the user can set to false to turn off freight calculations if necessary */


		/* What to do if the shipper is not calculated using the system
		- first check that the default shipper defined in config.php is in the database
		if so use this
		- then check to see if any shippers are defined at all if not report the error
		and show a link to set them up
		- if shippers defined but the default shipper is bogus then use the first shipper defined
		*/
		if ((isset($BestShipper) and $BestShipper=='') AND ($_POST['ShipVia']=='' || !isset($_POST['ShipVia']))){
			$sql =  "SELECT shipper_id
						FROM shippers
						WHERE shipper_id='" . $_SESSION['Default_Shipper']."'";
			$ErrMsg = _('There was a problem testing for the default shipper');
			$DbgMsg = _('SQL used to test for the default shipper') . ':';
			$TestShipperExists = DB_query($sql,$db,$ErrMsg,$DbgMsg);

			if (DB_num_rows($TestShipperExists)==1){

				$BestShipper = $_SESSION['Default_Shipper'];

			} else {

				$sql = "SELECT shipper_id
							FROM shippers";
				$TestShipperExists = DB_query($sql,$db,$ErrMsg,$DbgMsg);

				if (DB_num_rows($TestShipperExists)>=1){
					$ShipperReturned = DB_fetch_row($TestShipperExists);
					$BestShipper = $ShipperReturned[0];
				} else {
					prnMsg(_('We have a problem') . ' - ' . _('there are no shippers defined'). '. ' . _('Please use the link below to set up shipping or freight companies') . ', ' . _('the system expects the shipping company to be selected or a default freight company to be used'),'error');
					echo '<a href="' . $rootpath . 'Shippers.php">'. _('Enter') . '/' . _('Amend Freight Companies') .'</a>';
				}
			}
			if (isset($_SESSION['Items'.$identifier]->ShipVia) AND $_SESSION['Items'.$identifier]->ShipVia!=''){
				$_POST['ShipVia'] = $_SESSION['Items'.$identifier]->ShipVia;
			} else {
				$_POST['ShipVia']=$BestShipper;
			}
		}
	}
}

if(isset($_POST['MakeRecurringOrder']) AND ! $InputErrors){

	echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath . '/RecurringSalesOrders.php?identifier='.$identifier   .  '&NewRecurringOrder=Yes">';
	prnMsg(_('You should automatically be forwarded to the entry of recurring order details page') . '. ' . _('If this does not happen') . '(' . _('if the browser does not support META Refresh') . ') ' ."<a href='" . $rootpath . '/RecurringOrders.php?identifier='.$identifier  . "&NewRecurringOrder=Yes'>". _('click here') .'</a> '. _('to continue'),'info');
	include('includes/footer.inc');
	exit;
}


if (isset($_POST['BackToLineDetails']) and $_POST['BackToLineDetails']==_('Modify Order Lines')){

	echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath . '/SelectOrderItems.php?identifier='.$identifier   . '">';
	prnMsg(_('You should automatically be forwarded to the entry of the order line details page') . '. ' . _('If this does not happen') . '(' . _('if the browser does not support META Refresh') . ') ' .'<a href="' . $rootpath . '/SelectOrderItems.php?identifier='.$identifier  . '">'. _('click here') .'</a> '. _('to continue'),'info');
	include('includes/footer.inc');
	exit;

}

If (isset($_POST['ProcessOrder'])) {
	/*Default OK_to_PROCESS to 1 change to 0 later if hit a snag */
	if ($InputErrors ==0) {
		$OK_to_PROCESS = 1;
	}
	If ($_POST['FreightCost'] != $OldFreightCost && $_SESSION['DoFreightCalc']==True){
		$OK_to_PROCESS = 0;
		prnMsg(_('The freight charge has been updated') . '. ' . _('Please reconfirm that the order and the freight charges are acceptable and then confirm the order again if OK') .' <br /> '. _('The new freight cost is') .' ' . $_POST['FreightCost'] . ' ' . _('and the previously calculated freight cost was') .' '. $OldFreightCost,'warn');
	} else {

/*check the customer's payment terms */
		$sql = "SELECT daysbeforedue,
				dayinfollowingmonth
			FROM debtorsmaster,
				paymentterms
			WHERE debtorsmaster.paymentterms=paymentterms.termsindicator
			AND debtorsmaster.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo . "'";

		$ErrMsg = _('The customer terms cannot be determined') . '. ' . _('This order cannot be processed because');
		$DbgMsg = _('SQL used to find the customer terms') . ':';
		$TermsResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);


		$myrow = DB_fetch_array($TermsResult);
		if ($myrow['daysbeforedue']==0 && $myrow['dayinfollowingmonth']==0){

/* THIS IS A CASH SALE NEED TO GO OFF TO 3RD PARTY SITE SENDING MERCHANT ACCOUNT DETAILS AND CHECK FOR APPROVAL FROM 3RD PARTY SITE BEFORE CONTINUING TO PROCESS THE ORDER

UNTIL ONLINE CREDIT CARD PROCESSING IS PERFORMED ASSUME OK TO PROCESS

		NOT YET CODED     */

			$OK_to_PROCESS =1;


		} #end if cash sale detected

	} #end if else freight charge not altered
} #end if process order

if (isset($OK_to_PROCESS) and $OK_to_PROCESS == 1 && $_SESSION['ExistingOrder']==0){

/* finally write the order header to the database and then the order line details */


$sql_team="SELECT teamid FROM bio_leadtask WHERE leadid='".$_SESSION['leadid']."' AND taskid=5 AND viewstatus=1 AND taskcompletedstatus=0";
$result_team=DB_query($sql_team,$db);
$row_team=DB_fetch_array($result_team);
$teamid=$row_team['teamid'];



	$DelDate = FormatDateforSQL($_SESSION['Items'.$identifier]->DeliveryDate);
	$QuotDate = FormatDateforSQL($_SESSION['Items'.$identifier]->QuoteDate);
	$ConfDate = FormatDateforSQL($_SESSION['Items'.$identifier]->ConfirmedDate);

	$Result = DB_Txn_Begin($db);

	$OrderNo = GetNextTransNo(30, $db);

	$HeaderSQL = "INSERT INTO salesorders (
								orderno,
								debtorno,
								branchcode,
								customerref,
								comments,
								orddate,
								ordertype,
								shipvia,
								deliverto,
								deladd1,
								deladd2,
								deladd3,
								deladd4,
								deladd5,
								deladd6,
								contactphone,
								contactemail,
								freightcost,
								fromstkloc,
								deliverydate,
								quotedate,
								confirmeddate,
								quotation,
                                leadid,
                                team_id,
                                approval_by,     
								deliverblind,
                                currentstatus)
							VALUES (
								'". $OrderNo . "',
								'" . $_SESSION['Items'.$identifier]->DebtorNo . "',
								'" . $_SESSION['Items'.$identifier]->Branch . "',
								'". DB_escape_string($_SESSION['Items'.$identifier]->CustRef) ."',
								'". DB_escape_string($_SESSION['Items'.$identifier]->Comments) ."',
								'" . Date("Y-m-d H:i") . "',
								'" . $_SESSION['Items'.$identifier]->DefaultSalesType . "',
								'" . $_POST['ShipVia'] ."',
								'". DB_escape_string($_SESSION['Items'.$identifier]->DeliverTo) . "',
								'" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd1) . "',
								'" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd2) . "',
								'" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd3) . "',
								'" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd4) . "',
								'" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd5) . "',
								'" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd6) . "',
								'" . $_SESSION['Items'.$identifier]->PhoneNo . "',
								'" . $_SESSION['Items'.$identifier]->Email . "',
								'" . $_SESSION['Items'.$identifier]->FreightCost ."',
								'" . $_SESSION['Items'.$identifier]->Location ."',
								'" . $DelDate . "',
								'" . $QuotDate . "',
								'" . $ConfDate . "',
								0,
                                '" . $_SESSION['leadid'] ."',          
                                '" . $teamid ."', 
                                '" . $_POST['ApprovalBy'] ."',
								'" . $_SESSION['Items'.$identifier]->DeliverBlind ."',
                                1
								)";

	$ErrMsg = _('The order cannot be added because');
	$InsertQryResult = DB_query($HeaderSQL,$db,$ErrMsg);
    
    
    
    $order_no=DB_Last_Insert_ID($Conn,'salesorders','orderno');
    
	$StartOf_LineItemsSQL = "INSERT INTO salesorderdetails (
											orderlineno,
											orderno,
											stkcode,
											unitprice,
											quantity,
											discountpercent,
											narrative,
											poline,
											itemdue)
										VALUES (";
	$DbgMsg = _('The SQL that failed was');
	foreach ($_SESSION['Items'.$identifier]->LineItems as $StockItem) {

		$LineItemsSQL = $StartOf_LineItemsSQL ."
					'" . $StockItem->LineNumber . "',
					'" . $OrderNo . "',
					'" . $StockItem->StockID . "',
					'" . $StockItem->Price . "',
					'" . $StockItem->Quantity . "',
					'" . floatval($StockItem->DiscountPercent) . "',
					'" . DB_escape_string($StockItem->Narrative) . "',
					'" . $StockItem->POLine . "',
					'" . FormatDateForSQL($StockItem->ItemDue) . "'
				)";
		$ErrMsg = _('Unable to add the sales order line');
		$Ins_LineItemResult = DB_query($LineItemsSQL,$db,$ErrMsg,$DbgMsg,true); 
        
		/*Now check to see if the item is manufactured
		 * 			and AutoCreateWOs is on
		 * 			and it is a real order (not just a quotation)*/
         



         

		if ($StockItem->MBflag=='M'
			AND $_SESSION['AutoCreateWOs']==1
			AND $_SESSION['Items'.$identifier]->Quotation!=1){ //oh yeah its all on!

			echo '<br />';

			//now get the data required to test to see if we need to make a new WO
			$QOHResult = DB_query("SELECT SUM(quantity) FROM locstock WHERE stockid='" . $StockItem->StockID . "'",$db);
			$QOHRow = DB_fetch_row($QOHResult);
			$QOH = $QOHRow[0];

			$SQL = "SELECT SUM(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) AS qtydemand
					FROM salesorderdetails
					WHERE salesorderdetails.stkcode = '" . $StockItem->StockID . "'
					AND salesorderdetails.completed = 0";
			$DemandResult = DB_query($SQL,$db);
			$DemandRow = DB_fetch_row($DemandResult);
			$QuantityDemand = $DemandRow[0];

			$SQL = "SELECT SUM((salesorderdetails.quantity-salesorderdetails.qtyinvoiced)*bom.quantity) AS dem
					FROM salesorderdetails,
						bom,
						stockmaster
					WHERE salesorderdetails.stkcode=bom.parent
					AND salesorderdetails.quantity-salesorderdetails.qtyinvoiced > 0
					AND bom.component='" . $StockItem->StockID . "'
					AND stockmaster.stockid=bom.parent
					AND stockmaster.mbflag='A'
					AND salesorderdetails.completed=0";
			$AssemblyDemandResult = DB_query($SQL,$db);
			$AssemblyDemandRow = DB_fetch_row($AssemblyDemandResult);
			$QuantityAssemblyDemand = $AssemblyDemandRow[0];

			$SQL = "SELECT SUM(purchorderdetails.quantityord - purchorderdetails.quantityrecd) as qtyonorder
					FROM purchorderdetails,
						purchorders
					WHERE purchorderdetails.orderno = purchorders.orderno
					AND purchorderdetails.itemcode = '" . $StockItem->StockID . "'
					AND purchorderdetails.completed = 0";
			$PurchOrdersResult = DB_query($SQL,$db);
			$PurchOrdersRow = DB_fetch_row($PurchOrdersResult);
			$QuantityPurchOrders = $PurchOrdersRow[0];

			$SQL = "SELECT SUM(woitems.qtyreqd - woitems.qtyrecd) as qtyonorder
					FROM woitems INNER JOIN workorders
					ON woitems.wo=workorders.wo
					WHERE woitems.stockid = '" . $StockItem->StockID . "'
					AND woitems.qtyreqd > woitems.qtyrecd
					AND workorders.closed = 0";
			$WorkOrdersResult = DB_query($SQL,$db);
			$WorkOrdersRow = DB_fetch_row($WorkOrdersResult);
			$QuantityWorkOrders = $WorkOrdersRow[0];

			//Now we have the data - do we need to make any more?
			$ShortfallQuantity = $QOH-$QuantityDemand-$QuantityAssemblyDemand+$QuantityPurchOrders+$QuantityWorkOrders;

			if ($ShortfallQuantity < 0) { //then we need to make a work order
				//How many should the work order be for??
				if ($ShortfallQuantity + $StockItem->EOQ < 0){
					$WOQuantity = -$ShortfallQuantity;
				} else {
					$WOQuantity = $StockItem->EOQ;
				}

				$WONo = GetNextTransNo(40,$db);
				$ErrMsg = _('Unable to insert a new work order for the sales order item');
				$InsWOResult = DB_query("INSERT INTO workorders (wo,
												 loccode,
												 requiredby,
												 startdate)
								 VALUES ('" . $WONo . "',
										'" . $_SESSION['DefaultFactoryLocation'] . "',
										'" . Date('Y-m-d') . "',
										'" . Date('Y-m-d'). "')",
										$db,$ErrMsg,$DbgMsg,true);
				//Need to get the latest BOM to roll up cost
				$CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost
													FROM stockmaster INNER JOIN bom
													ON stockmaster.stockid=bom.component
													WHERE bom.parent='" . $StockItem->StockID . "'
													AND bom.loccode='" . $_SESSION['DefaultFactoryLocation'] . "'",
										$db);
				$CostRow = DB_fetch_row($CostResult);
				if (is_null($CostRow[0]) OR $CostRow[0]==0){
					$Cost =0;
					prnMsg(_('In automatically creating a work order for') . ' ' . $StockItem->StockID . ' ' . _('an item on this sales order, the cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
				} else {
					$Cost = $CostRow[0];
				}

				// insert parent item info
				$sql = "INSERT INTO woitems (wo,
											 stockid,
											 qtyreqd,
											 stdcost)
								 VALUES ( '" . $WONo . "',
										 '" . $StockItem->StockID . "',
										 '" . $WOQuantity . "',
										 '" . $Cost . "')";
				$ErrMsg = _('The work order item could not be added');
				$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);

				//Recursively insert real component requirements - see includes/SQL_CommonFunctions.in for function WoRealRequirements
				WoRealRequirements($db, $WONo, $_SESSION['DefaultFactoryLocation'], $StockItem->StockID);

				$FactoryManagerEmail = _('A new work order has been created for') .
									":\n" . $StockItem->StockID . ' - ' . $StockItem->Descr . ' x ' . $WOQuantity . ' ' . $StockItem->UOM .
									"\n" . _('These are for') . ' ' . $_SESSION['Items'.$identifier]->CustomerName . ' ' . _('there order ref') . ': '  . $_SESSION['Items'.$identifier]->CustRef . ' ' ._('our order number') . ': ' . $OrderNo;

				if ($StockItem->Serialised AND $StockItem->NextSerialNo>0){
						//then we must create the serial numbers for the new WO also
						$FactoryManagerEmail .= "\n" . _('The following serial numbers have been reserved for this work order') . ':';

						for ($i=0;$i<$WOQuantity;$i++){

							$result = DB_query("SELECT serialno FROM stockserialitems
												WHERE serialno='" . ($StockItem->NextSerialNo + $i) . "'
												AND stockid='" . $StockItem->StockID ."'",$db);
							if (DB_num_rows($result)!=0){
								$WOQuantity++;
								prnMsg(($StockItem->NextSerialNo + $i) . ': ' . _('This automatically generated serial number already exists - it cannot be added to the work order'),'error');
							} else {
								$sql = "INSERT INTO woserialnos (wo,
																stockid,
																serialno)
													VALUES ('" . $WONo . "',
															'" . $StockItem->StockID . "',
															'" . ($StockItem->NextSerialNo + $i) . "')";
								$ErrMsg = _('The serial number for the work order item could not be added');
								$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);
								$FactoryManagerEmail .= "\n" . ($StockItem->NextSerialNo + $i);
							}
						} //end loop around creation of woserialnos
						$NewNextSerialNo = ($StockItem->NextSerialNo + $WOQuantity +1);
						$ErrMsg = _('Could not update the new next serial number for the item');
						$UpdateNextSerialNoResult = DB_query("UPDATE stockmaster SET nextserialno='" . $NewNextSerialNo . "' WHERE stockid='" . $StockItem->StockID . "'",$db,$ErrMsg,$DbgMsg,true);
				} // end if the item is serialised and nextserialno is set

				$EmailSubject = _('New Work Order Number') . ' ' . $WONo . ' ' . _('for') . ' ' . $StockItem->StockID . ' x ' . $WOQuantity;
				//Send email to the Factory Manager
				mail($_SESSION['FactoryManagerEmail'],$EmailSubject,$FactoryManagerEmail);
			} //end if with this sales order there is a shortfall of stock - need to create the WO
		}//end if auto create WOs in on
	} /* end inserted line items into sales order details */

	$result = DB_Txn_Commit($db);
    
    
    
    
              $sql_drop="DROP TABLE if exists tempdocs";
              $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE tempdocs ( docno INT )";
                      $result_create=DB_query($sql_create,$db);   
                      
                      $sql1="SELECT enqtypeid FROM bio_leads WHERE leadid=".$_SESSION['leadid'];
                      $result1=DB_query($sql1,$db);
                      $row1=DB_fetch_array($result1);
                      $enqtypeid=$row1['enqtypeid'];
                      
                      
                      if($row1['enqtypeid']==1) {
                          $sql2="SELECT doc_no FROM bio_document_master WHERE enqtypeid=".$row1['enqtypeid'];
                      }elseif($row1['enqtypeid']==2)    {
                          $sql2="SELECT doc_no FROM bio_document_master WHERE enqtypeid=".$row1['enqtypeid'];
                      }elseif($row1['enqtypeid']==3)    {
                          $sql2="SELECT doc_no FROM bio_document_master WHERE enqtypeid=".$row1['enqtypeid'];
                      }
                          $result2=DB_query($sql2,$db);
                          
                          while($row2=DB_fetch_array($result2))
                          {
                              $sql_temp="INSERT INTO tempdocs values ('".$row2['doc_no']."')";    
                              DB_query($sql_temp,$db);
                          }
                      
         $sql_select_temp="SELECT * FROM tempdocs"; 
         $result_select_temp=DB_query($sql_select_temp,$db);
         while($row_select_temp=DB_fetch_array($result_select_temp)) 
         {
                       
                        $sql_documents="INSERT INTO bio_documentlist (
                                        leadid,
                                        orderno,
                                        docno,
                                        status)
                                     VALUES (
                                        '" . $_SESSION['leadid'] ."',
                                        '". $OrderNo . "',
                                        '". $row_select_temp['docno'] . "',
                                        0 
                                        )   ";
                        DB_query($sql_documents,$db);                
         } 
         
   
       if($enqtypeid==3)
       {
           $sql_proplead="INSERT INTO bio_lsg_proplead VALUES (".$_SESSION['leadid'].",".$_SESSION['propid'].")";
           DB_query($sql_proplead,$db);
       }
   
   
   
    
            
    $sql_advance="SELECT SUM(amount) AS advance FROM bio_advance WHERE leadid='".$_SESSION['leadid']."'";
    $result_advance=DB_query($sql_advance,$db);
    $myrow_advance=DB_fetch_array($result_advance);
    $advancePaid=$myrow_advance['advance'];   
       
   if($advancePaid!=0 || $advancePaid!='') 
   {
                 $transno = GetNextTransNo(12,$db);               // 12  Sales Receipt     11  Credit Notes
                 $PeriodNo=0;
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
                                            rate,
                                            ovamount,
                                            ovdiscount,
                                            invtext)
                    VALUES (
                        '" . $transno . "',
                        12,
                        '" . $_SESSION['Items'.$identifier]->DebtorNo . "',
                        '',
                        '" . date('Y-m-d'). "',
                        '" . date('Y-m-d H-i-s') . "',
                        '" . $PeriodNo . "',
                        ' Advance amount transfered ',
                        '',
                        $OrderNo,
                        1,
                        '" . -$advancePaid . "',
                        '" . -$ReceiptItem->Discount . "',
                        '" . $ReceiptItem->Narrative. "'
                    )";
                    
//rate ->   '" . ($_SESSION['ReceiptBatch']->ExRate/$_SESSION['ReceiptBatch']->FunctionalExRate) . "', 
            $DbgMsg = _('The SQL that failed to insert the customer receipt transaction was');
            $ErrMsg = _('Cannot insert a receipt transaction against the customer because') ;
            $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);        
                  
                  
                  
            $date=date("Y-m-d");          
            
            $SQL="INSERT INTO banktrans (type,
                                        transno,
                                        bankact,
                                        exrate,
                                        functionalexrate,
                                        transdate,
                                        banktranstype,
                                        amount,
                                        currcode)
                VALUES (
                    12,
                    '" . $transno . "',
                    '1020',
                    1,
                    1,
                    '" . $date . "',
                    'Cash',
                    '" . $advancePaid . "',
                    'INR'
                )";
            $DbgMsg = _('The SQL that failed to insert the bank account transaction was');
            $ErrMsg = _('Cannot insert a bank transaction');
            $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
    
   }
    
    
    
    
     $sql_orderApproval="INSERT INTO bio_orderapproval (
                                        orderno,
                                        submitted_by,
                                        approval_by,
                                        assigned_date,
                                        taskcomplted_status)
                                     VALUES(
                                        '". $OrderNo . "',
                                        '". $_SESSION['UserID'] . "',
                                        '" . $_POST['ApprovalBy'] ."', 
                                        '" . Date("Y-m-d H:i") . "',
                                        0)";
     $result_orderApproval=DB_query($sql_orderApproval,$db);
     
        $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
     
       
     
     $sql_leadStatus="UPDATE bio_leads SET leadstatus=6 WHERE leadid='" . $_SESSION['leadid'] ."' ";                          
     $result_leadStatus=DB_query($sql_leadStatus,$db);
     
     $sql2="SELECT tid FROM bio_leadtask WHERE leadid='$_SESSION[leadid]' 
            AND taskid=5  
            AND taskcompletedstatus=0 
            AND teamid=$assignedfrm";
             $result_task=DB_query($sql2,$db);
             $myrow2=DB_fetch_array($result_task);
             $tid=$myrow2['tid'];
             
             $sql3="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='" . Date("Y-m-d H:i") . "' where tid='$tid'";     
             DB_query($sql3,$db);
             // window.opener.location='bio_domTaskview.php?flag=' + flag + '&lead='+ lead;   
             
             
    $_SESSION['OrderNo']=$OrderNo;
    
    
      print'<script>
          myRef = window.open("bio_createdocument.php","documentcreation","toolbar=yes,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,resizable=no,width=600,height=400");
          </script> ';
    
	echo '<br />';
	if ($_SESSION['Items'.$identifier]->Quotation==1){
		prnMsg(_('Quotation Number') . ' ' . $OrderNo . ' ' . _('has been entered'),'success');
	} else {
		prnMsg(_('Order Number') . ' ' . $OrderNo . ' ' . _('has been entered'),'success');
	}

	if (count($_SESSION['AllowedPageSecurityTokens'])>1){
		/* Only allow print of packing slip for internal staff - customer logon's cannot go here */

		if ($_POST['Quotation']==0) { /*then its not a quotation its a real order */

			echo '<br /><table class=selection>
					<tr>
						<td><img src="'.$rootpath.'/css/'.$theme.'/images/printer.png" title="' . _('Print') . '" alt=""></td>
						<td>' . ' ' . '<a target="_blank" href="' . $rootpath . '/PrintCustOrder.php?identifier='.$identifier . '&TransNo=' . $OrderNo . '">'. _('Print packing slip') . ' (' . _('Preprinted stationery') . ')' .'</a></td>
					</tr>';
			echo '<tr>
					<td><img src="'.$rootpath.'/css/'.$theme.'/images/printer.png" title="' . _('Print') . '" alt=""></td>
					<td>' . ' ' . '<a  target="_blank" href="' . $rootpath . '/PrintCustOrder_generic.php?identifier='.$identifier . '&TransNo=' . $OrderNo . '">'. _('Print packing slip') . ' (' . _('Laser') . ')' .'</a></td>
				</tr>';

			echo '<tr>
					<td><img src="'.$rootpath.'/css/'.$theme.'/images/reports.png" title="' . _('Invoice') . '" alt=""></td>
					<td>' . ' ' . '<a href="' . $rootpath . '/ConfirmDispatch_Invoice.php?identifier='.$identifier . '&OrderNumber=' . $OrderNo .'">'. _('Confirm Dispatch and Produce Invoice') .'</a></td>
				</tr>';
			
			echo '</table>';

		} else {
			/*link to print the quotation */
			echo '<br /><table class=selection>
					<tr>
						<td><img src="'.$rootpath.'/css/'.$theme.'/images/reports.png" title="' . _('Order') . '" alt=""></td>
						<td>' . ' ' . '<a href="' . $rootpath . '/PDFQuotation.php?' . SID .'identifier='.$identifier . '&QuotationNo=' . $OrderNo . '">'. _('Print Quotation (Landscape)') .'</a></td>
					</tr>
					</table>';
			echo '<br /><table class=selection>
					<tr>
						<td><img src="'.$rootpath.'/css/'.$theme.'/images/reports.png" title="' . _('Order') . '" alt=""></td>
						<td>' . ' ' . '<a href="' . $rootpath . '/PDFQuotationPortrait.php?' . SID .'identifier='.$identifier . '&QuotationNo=' . $OrderNo . '">'. _('Print Quotation (Portrait)') .'</a></td>
					</tr>
					</table>';
		}
		echo '<br /><table class=selection>
				<tr>
					<td><img src="'.$rootpath.'/css/'.$theme.'/images/sales.png" title="' . _('Order') . '" alt=""></td>
					<td>' . ' ' . '<a href="'. $rootpath .'/SelectOrderItems.php?identifier='.$identifier . '&NewOrder=Yes">'. _('Add Another Sales Order') .'</a></td>
				</tr>
				</table>';
	} else {
		/*its a customer logon so thank them */
		prnMsg(_('Thank you for your business'),'success');
	}
    
    if($_SESSION['enqid']==1){
        ?>
     <script>
     var lead=<?php echo $_SESSION['leadid']; ?>;
     var flag=5;
     window.opener.location='bio_domTaskview.php?flag=' + flag + '&lead='+ lead;
     window.close();
     </script>
     <?php
        
    }
    elseif($_SESSION['enqid']==2){
        ?>
     <script>
     var lead=<?php echo $_SESSION['leadid']; ?>;
      window.opener.location='bio_instTaskview.php?lead='+ lead;
      window.close();
     
     </script>
     <?php
        
    }
    elseif($_SESSION['CounterSale']=='countersale'){
     ?>
     <script>
     window.close();
     </script>
     <?php
    }
    
unset($_SESSION['leadid']);
	unset($_SESSION['Items'.$identifier]->LineItems);
	unset($_SESSION['Items'.$identifier]);
	include('includes/footer.inc');
	exit;

} elseif (isset($OK_to_PROCESS) and $OK_to_PROCESS == 1 && $_SESSION['ExistingOrder']!=0){

/* update the order header then update the old order line details and insert the new lines */

	$DelDate = FormatDateforSQL($_SESSION['Items'.$identifier]->DeliveryDate);
	$QuotDate = FormatDateforSQL($_SESSION['Items'.$identifier]->QuoteDate);
	$ConfDate = FormatDateforSQL($_SESSION['Items'.$identifier]->ConfirmedDate);

	$Result = DB_Txn_Begin($db);

	/*see if this is a contract quotation being changed to an order? */
	if ($_SESSION['Items'.$identifier]->Quotation==0) { //now its being changed? to an order
		$ContractResult = DB_query("SELECT contractref,
											requireddate
									FROM contracts WHERE orderno='" .  $_SESSION['ExistingOrder'] ."'
									AND status=1",$db);
		if (DB_num_rows($ContractResult)==1){ //then it is a contract quotation being changed to an order
			$ContractRow = DB_fetch_array($ContractResult);
			$WONo = GetNextTransNo(40,$db);
			$ErrMsg = _('Could not update the contract status');
			$DbgMsg = _('The SQL that failed to update the contract status was');
			$UpdContractResult=DB_query("UPDATE contracts SET status=2,
																wo='" . $WONo . "'
										WHERE orderno='" .$_SESSION['ExistingOrder'] . "'", $db,$ErrMsg,$DbgMsg,true);
			$ErrMsg = _('Could not insert the contract bill of materials');
			$InsContractBOM = DB_query("INSERT INTO bom (parent,
														 component,
														 workcentreadded,
														 loccode,
														 effectiveafter,
														 effectiveto)
											SELECT contractref,
													stockid,
													workcentreadded,
												'" . $_SESSION['Items'.$identifier]->Location ."',
												'" . Date('Y-m-d') . "',
												'2037-12-31'
											FROM contractbom
											WHERE contractref='" . $ContractRow['contractref'] . "'",$db,$ErrMsg,$DbgMsg);

			$ErrMsg = _('Unable to insert a new work order for the sales order item');
			$InsWOResult = DB_query("INSERT INTO workorders (wo,
															 loccode,
															 requiredby,
															 startdate)
											 VALUES ('" . $WONo . "',
													'" . $_SESSION['Items'.$identifier]->Location ."',
													'" . $ContractRow['requireddate'] . "',
													'" . Date('Y-m-d'). "')",
													$db,$ErrMsg,$DbgMsg);
			//Need to get the latest BOM to roll up cost but also add the contract other requirements
			$CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*contractbom.quantity) AS cost
									FROM stockmaster INNER JOIN contractbom
									ON stockmaster.stockid=contractbom.stockid
									WHERE contractbom.contractref='" .  $ContractRow['contractref'] . "'",
									$db);
			$CostRow = DB_fetch_row($CostResult);
			if (is_null($CostRow[0]) OR $CostRow[0]==0){
				$Cost =0;
				prnMsg(_('In automatically creating a work order for') . ' ' . $ContractRow['contractref'] . ' ' . _('an item on this sales order, the cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
			} else {
				$Cost = $CostRow[0]; //cost of contract BOM
			}
			$CostResult = DB_query("SELECT SUM(costperunit*quantity) AS cost
									FROM contractreqts
									WHERE contractreqts.contractref='" .  $ContractRow['contractref'] . "'",
									$db);
			$CostRow = DB_fetch_row($CostResult);
			//add other requirements cost to cost of contract BOM
			$Cost += $CostRow[0];

			// insert parent item info
			$sql = "INSERT INTO woitems (wo,
										 stockid,
										 qtyreqd,
										 stdcost)
							 VALUES ( '" . $WONo . "',
									 '" . $ContractRow['contractref'] . "',
									 '1',
									 '" . $Cost . "')";
			$ErrMsg = _('The work order item could not be added');
			$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);

			//Recursively insert real component requirements - see includes/SQL_CommonFunctions.in for function WoRealRequirements
			WoRealRequirements($db, $WONo, $_SESSION['Items'.$identifier]->Location, $ContractRow['contractref']);

		} //end processing if the order was a contract quotation being changed to an order
	} //end test to see if the order was a contract quotation being changed to an order


	$HeaderSQL = "UPDATE salesorders
			SET debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo . "',
				branchcode = '" . $_SESSION['Items'.$identifier]->Branch . "',
				customerref = '". DB_escape_string($_SESSION['Items'.$identifier]->CustRef) ."',
				comments = '". DB_escape_string($_SESSION['Items'.$identifier]->Comments) ."',
				ordertype = '" . $_SESSION['Items'.$identifier]->DefaultSalesType . "',
				shipvia = '" . $_POST['ShipVia'] . "',
				deliverydate = '" . FormatDateForSQL(DB_escape_string($_SESSION['Items'.$identifier]->DeliveryDate)) . "',
				quotedate = '" . FormatDateForSQL(DB_escape_string($_SESSION['Items'.$identifier]->QuoteDate)) . "',
				confirmeddate = '" . FormatDateForSQL(DB_escape_string($_SESSION['Items'.$identifier]->ConfirmedDate)) . "',
				deliverto = '" . DB_escape_string($_SESSION['Items'.$identifier]->DeliverTo) . "',
				deladd1 = '" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd1) . "',
				deladd2 = '" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd2) . "',
				deladd3 = '" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd3) . "',
				deladd4 = '" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd4) . "',
				deladd5 = '" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd5) . "',
				deladd6 = '" . DB_escape_string($_SESSION['Items'.$identifier]->DelAdd6) . "',
				contactphone = '" . $_SESSION['Items'.$identifier]->PhoneNo . "',
				contactemail = '" . $_SESSION['Items'.$identifier]->Email . "',
				freightcost = '" . $_SESSION['Items'.$identifier]->FreightCost ."',
				fromstkloc = '" . $_SESSION['Items'.$identifier]->Location ."',
				printedpackingslip = '" . $_POST['ReprintPackingSlip'] . "',
				quotation = '" . $_SESSION['Items'.$identifier]->Quotation . "',
				deliverblind = '" . $_SESSION['Items'.$identifier]->DeliverBlind . "'
			WHERE salesorders.orderno='" . $_SESSION['ExistingOrder'] ."'";

	$DbgMsg = _('The SQL that was used to update the order and failed was');
	$ErrMsg = _('The order cannot be updated because');
	$InsertQryResult = DB_query($HeaderSQL,$db,$ErrMsg,$DbgMsg,true);


	foreach ($_SESSION['Items'.$identifier]->LineItems as $StockItem) {

		/* Check to see if the quantity reduced to the same quantity
		as already invoiced - so should set the line to completed */
		if ($StockItem->Quantity == $StockItem->QtyInv){
			$Completed = 1;
		} else {  /* order line is not complete */
			$Completed = 0;
		}

		$LineItemsSQL = "UPDATE salesorderdetails SET unitprice='"  . $StockItem->Price . "',
													quantity='" . $StockItem->Quantity . "',
													discountpercent='" . floatval($StockItem->DiscountPercent) . "',
													completed='" . $Completed . "',
													poline='" . $StockItem->POLine . "',
													itemdue='" . FormatDateForSQL($StockItem->ItemDue) . "'
						WHERE salesorderdetails.orderno='" . $_SESSION['ExistingOrder'] . "'
						AND salesorderdetails.orderlineno='" . $StockItem->LineNumber . "'";

		$DbgMsg = _('The SQL that was used to modify the order line and failed was');
		$ErrMsg = _('The updated order line cannot be modified because');
		$Upd_LineItemResult = DB_query($LineItemsSQL,$db,$ErrMsg,$DbgMsg,true);

	} /* updated line items into sales order details */

	$Result=DB_Txn_Commit($db);

	unset($_SESSION['Items'.$identifier]->LineItems);
	unset($_SESSION['Items'.$identifier]);

	prnMsg(_('Order Number') .' ' . $_SESSION['ExistingOrder'] . ' ' . _('has been updated'),'success');

	echo '<br />
			<table class="selection">
			<tr>
				<td><img src="'.$rootpath.'/css/'.$theme.'/images/printer.png" title="' . _('Print') . '" alt=""></td><td><a href="' . $rootpath . '/PrintCustOrder.php?identifier='.$identifier  . '&TransNo=' . $_SESSION['ExistingOrder'] . '">'. _('Print packing slip - pre-printed stationery') .'</a></td>
			</tr>';
	echo '<tr>
			<td><img src="'.$rootpath.'/css/'.$theme.'/images/printer.png" title="' . _('Print') . '" alt=""></td>
			<td><a  target="_blank" href="' . $rootpath . '/PrintCustOrder_generic.php?identifier='.$identifier  . '&TransNo=' . $_SESSION['ExistingOrder'] . '">'. _('Print packing slip') . ' (' . _('Laser') . ')' .'</a></td>
		</tr>';
	echo '<tr>
			<td><img src="'.$rootpath.'/css/'.$theme.'/images/reports.png" title="' . _('Invoice') . '" alt=""></td>
			<td><a href="' . $rootpath .'/ConfirmDispatch_Invoice.php?identifier='.$identifier  . '&OrderNumber=' . $_SESSION['ExistingOrder'] . '">'. _('Confirm Order Delivery Quantities and Produce Invoice') .'</a></td>
		</tr>';
	echo '<tr>
			<td><img src="'.$rootpath.'/css/'.$theme.'/images/sales.png" title="' . _('Order') . '" alt=""></td>
			<td><a href="' . $rootpath .'/SelectSalesOrder.php?identifier='.$identifier   . '">'. _('Select A Different Order') .'</a></td>
		</tr>
		</table>';
	include('includes/footer.inc');
	exit;
}


if (isset($_SESSION['Items'.$identifier]->SpecialInstructions) and strlen($_SESSION['Items'.$identifier]->SpecialInstructions)>0) {
	prnMsg($_SESSION['Items'.$identifier]->SpecialInstructions,'info');
}
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/inventory.png" title="' . _('Delivery') . '" alt="" />' . ' ' . _('Delivery Details');
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Customer') . '" alt="" />' . ' ' . _('Customer Code') . ' :<b> ' . $_SESSION['Items'.$identifier]->DebtorNo;
echo '</b>&nbsp;' . _('Customer Name') . ' :<b> ' . $_SESSION['Items'.$identifier]->CustomerName . '</p>';

echo '<form action="' . $_SERVER['PHP_SELF'] . '?identifier='.$identifier  . '" method=post>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


/*Display the order with or without discount depending on access level*/
if (in_array(2,$_SESSION['AllowedPageSecurityTokens'])){

	echo '<div class="centre"><b>';

	if ($_SESSION['Items'.$identifier]->Quotation==1){
		echo _('Quotation Summary');
	} else {
		echo _('Order Summary');
	}
	echo '</b></div>
	<table cellpading=2 colspan=7>
	<Tr>
		<th>'. _('Item Code') .'</th>
		<th>'. _('Item Description') .'</th>
		<th>'. _('Quantity') .'</th>
		<th>'. _('Unit') .'</th>
		<th>'. _('Price') .'</th>
		<th>'. _('Discount') .' %</th>
		<th>'. _('Total') .'</th>
	</tr>';

	$_SESSION['Items'.$identifier]->total = 0;
	$_SESSION['Items'.$identifier]->totalVolume = 0;
	$_SESSION['Items'.$identifier]->totalWeight = 0;
	$k = 0; //row colour counter

	foreach ($_SESSION['Items'.$identifier]->LineItems as $StockItem) {

		$LineTotal = $StockItem->Quantity * $StockItem->Price * (1 - $StockItem->DiscountPercent);
		$DisplayLineTotal = number_format($LineTotal,2);
		$DisplayPrice = number_format($StockItem->Price,2);
		$DisplayQuantity = number_format($StockItem->Quantity,$StockItem->DecimalPlaces);
		$DisplayDiscount = number_format(($StockItem->DiscountPercent * 100),2);


		if ($k==1){
			echo '<tr class="EvenTableRows">';
			$k=0;
		} else {
			echo '<tr class="OddTableRows">';
			$k=1;
		}

		echo '<td>'.$StockItem->StockID.'</td>
			<td>'.$StockItem->ItemDescription.'</td>
			<td class=number>'.$DisplayQuantity.'</td>
			<td>'.$StockItem->Units.'</td>
			<td class=number>'.$DisplayPrice.'</td>
			<td class=number>'.$DisplayDiscount.'</td>
			<td class=number>'.$DisplayLineTotal.'</td>
		</tr>';

		$_SESSION['Items'.$identifier]->total = $_SESSION['Items'.$identifier]->total + $LineTotal;
		$_SESSION['Items'.$identifier]->totalVolume = $_SESSION['Items'.$identifier]->totalVolume + ($StockItem->Quantity * $StockItem->Volume);
		$_SESSION['Items'.$identifier]->totalWeight = $_SESSION['Items'.$identifier]->totalWeight + ($StockItem->Quantity * $StockItem->Weight);
	}

	$DisplayTotal = number_format($_SESSION['Items'.$identifier]->total,2);
	echo '<tr class="EvenTableRows">
		<td colspan=6 class=number><b>'. _('TOTAL Excl Tax/Freight') .'</b></td>
		<td class=number>'.$DisplayTotal.'</td>
	</tr></table>';

	$DisplayVolume = number_format($_SESSION['Items'.$identifier]->totalVolume,2);
	$DisplayWeight = number_format($_SESSION['Items'.$identifier]->totalWeight,2);
	echo '<br /><table><tr class="EvenTableRows">
		<td>'. _('Total Weight') .':</td>
		<td>'.$DisplayWeight.'</td>
		<td>'. _('Total Volume') .':</td>
		<td>'.$DisplayVolume.'</td>
	</tr></table>';

} else {

/*Display the order without discount */

	echo '<div class="centre"><b>' . _('Order Summary') . '</b></div>
	<table cellpadding=2 colspan=7 border=1><tr>
		<th>'. _('Item Description') .'</th>
		<th>'. _('Quantity') .'</th>
		<th>'. _('Unit') .'</th>
		<th>'. _('Price') .'</th>
		<th>'. _('Total') .'</th>
	</tr>';

	$_SESSION['Items'.$identifier]->total = 0;
	$_SESSION['Items'.$identifier]->totalVolume = 0;
	$_SESSION['Items'.$identifier]->totalWeight = 0;
	$k=0; // row colour counter
	foreach ($_SESSION['Items'.$identifier]->LineItems as $StockItem) {

		$LineTotal = $StockItem->Quantity * $StockItem->Price * (1 - $StockItem->DiscountPercent);
		$DisplayLineTotal = number_format($LineTotal,2);
		$DisplayPrice = number_format($StockItem->Price,2);
		$DisplayQuantity = number_format($StockItem->Quantity,$StockItem->DecimalPlaces);

		if ($k==1){
			echo '<tr class="OddTableRows">';
			$k=0;
		} else {
			echo '<tr class="EvenTableRows">';
			$k=1;
		}
		echo '<td>'.$StockItem->ItemDescription.'</td>
			<td class=number>'. $DisplayQuantity.'</td>
			<td>'.$StockItem->Units.'</td>
			<td class=number>'. $DisplayPrice.'</td>
			<td class=number>'. $DisplayLineTotal .'</font></td>
		</tr>';

		$_SESSION['Items'.$identifier]->total = $_SESSION['Items'.$identifier]->total + $LineTotal;
		$_SESSION['Items'.$identifier]->totalVolume = $_SESSION['Items'.$identifier]->totalVolume + $StockItem->Quantity * $StockItem->Volume;
		$_SESSION['Items'.$identifier]->totalWeight = $_SESSION['Items'.$identifier]->totalWeight + $StockItem->Quantity * $StockItem->Weight;

	}

	$DisplayTotal = number_format($_SESSION['Items'.$identifier]->total,2);
	echo '<table class=selection><tr>
		<td>'. _('Total Weight') .':</td>
		<td>'.$DisplayWeight .'</td>
		<td>'. _('Total Volume') .':</td>
		<td>'.$DisplayVolume .'</td>
	</tr></table>';

	$DisplayVolume = number_format($_SESSION['Items'.$identifier]->totalVolume,2);
	$DisplayWeight = number_format($_SESSION['Items'.$identifier]->totalWeight,2);
	echo '<table class=selection><tr>
		<td>'. _('Total Weight') .':</td>
		<td>'. $DisplayWeight .'</td>
		<td>'. _('Total Volume') .':</td>
		<td>'. $DisplayVolume .'</td>
	</tr></table>';

}

echo '<br /><table  class=selection><tr>
	<td>'. _('Deliver To') .':</td>
	<td><input type=text size=42 maxlength=40 name="DeliverTo" value="' . $_SESSION['Items'.$identifier]->DeliverTo . '"></td>
</tr>';

echo '<tr>
	<td>'. _('Deliver from the warehouse at') .':</td>
	<td><select name="Location">';

if ($_SESSION['Items'.$identifier]->Location=='' OR !isset($_SESSION['Items'.$identifier]->Location)) {
	$_SESSION['Items'.$identifier]->Location = $DefaultStockLocation;
}

$ErrMsg = _('The stock locations could not be retrieved');
$DbgMsg = _('SQL used to retrieve the stock locations was') . ':';
$StkLocsResult = DB_query("SELECT locationname,loccode
							FROM locations",$db, $ErrMsg, $DbgMsg);

while ($myrow=DB_fetch_row($StkLocsResult)){
	if ($_SESSION['Items'.$identifier]->Location==$myrow[1]){
		echo '<option selected value="' . $myrow[1] . '">' . $myrow[0] . '</option>';
	} else {
		echo '<option value="'.$myrow[1].'">'.$myrow[0] . '</option>';
	}
}

echo '</select></td></tr>';

// Set the default date to earliest possible date if not set already
if (!isset($_SESSION['Items'.$identifier]->DeliveryDate)) {
	$_SESSION['Items'.$identifier]->DeliveryDate = Date($_SESSION['DefaultDateFormat'],$EarliestDispatch);
}
if (!isset($_SESSION['Items'.$identifier]->QuoteDate)) {
	$_SESSION['Items'.$identifier]->QuoteDate = Date($_SESSION['DefaultDateFormat'],$EarliestDispatch);
}
if (!isset($_SESSION['Items'.$identifier]->ConfirmedDate)) {
	$_SESSION['Items'.$identifier]->ConfirmedDate = Date($_SESSION['DefaultDateFormat'],$EarliestDispatch);
}

// The estimated Dispatch date or Delivery date for this order
echo '<tr>
	<td>'. _('Estimated Delivery Date') .':</td>
	<td><input class="date" alt="'.$_SESSION['DefaultDateFormat'].'" type="Text" size=15 maxlength=14 name="DeliveryDate" value="' . $_SESSION['Items'.$identifier]->DeliveryDate . '"></td>
	</tr>';
// The date when a quote was issued to the customer
echo '<tr>
	<td>'. _('Quote Date') .':</td>
	<td><input class="date" alt="'.$_SESSION['DefaultDateFormat'].'" type="Text" size=15 maxlength=14 name="QuoteDate" value="' . $_SESSION['Items'.$identifier]->QuoteDate . '"></td>
	</tr>';
// The date when the customer confirmed their order
echo '<tr>
	<td>'. _('Confirmed Order Date') .':</td>
	<td><input class="date" alt="'.$_SESSION['DefaultDateFormat'].'" type="Text" size=15 maxlength=14 name="ConfirmedDate" value="' . $_SESSION['Items'.$identifier]->ConfirmedDate . '"></td>
	</tr>';

echo '<tr>
	<td>'. _('Delivery Address 1') . ':</td>
	<td><input type=text size=42 maxlength=40 name="BrAdd1" value="' . $_SESSION['Items'.$identifier]->DelAdd1 . '"></td>
</tr>';

echo '<tr>
	<td>'. _('Delivery Address 2') . ':</td>
	<td><input type=text size=42 maxlength=40 name="BrAdd2" value="' . $_SESSION['Items'.$identifier]->DelAdd2 . '"></td>
</tr>';

echo '<tr>
	<td>'. _('Delivery Address 3') . ':</td>
	<td><input type=text size=42 maxlength=40 name="BrAdd3" value="' . $_SESSION['Items'.$identifier]->DelAdd3 . '"></td>
</tr>';

echo '<tr>
	<td>'. _('Delivery Address 4') . ':</td>
	<td><input type=text size=42 maxlength=40 name="BrAdd4" value="' . $_SESSION['Items'.$identifier]->DelAdd4 . '"></td>
</tr>';

echo '<tr>
	<td>'. _('Delivery Address 5') . ':</td>
	<td><input type=text size=42 maxlength=40 name="BrAdd5" value="' . $_SESSION['Items'.$identifier]->DelAdd5 . '"></td>
</tr>';

echo '<tr>
	<td>'. _('Delivery Address 6') . ':</td>
	<td><input type=text size=42 maxlength=40 name="BrAdd6" value="' . $_SESSION['Items'.$identifier]->DelAdd6 . '"></td>
</tr>';

echo '<tr>
	<td>'. _('Contact Phone Number') .':</td>
	<td><input type=text size=25 maxlength=25 name="PhoneNo" value="' . $_SESSION['Items'.$identifier]->PhoneNo . '"></td>
</tr>';

echo '<tr><td>' . _('Contact Email') . ':</td><td><input type=text size=40 maxlength=38 name="Email" value="' . $_SESSION['Items'.$identifier]->Email . '"></td></tr>';

echo '<tr><td>'. _('Customer Reference') .':</td>
	<td><input type=text size=25 maxlength=25 name="CustRef" value="' . $_SESSION['Items'.$identifier]->CustRef . '"></td>
</tr>';

echo '<tr>
	<td>'. _('Comments') .':</td>
	<td><textarea name=Comments cols=31 rows=5>' . $_SESSION['Items'.$identifier]->Comments .'</textarea></td>
</tr>';

	/* This field will control whether or not to display the company logo and
	address on the packlist */

	echo '<tr><td>' . _('Packlist Type') . ':</td>
			<td><select name="DeliverBlind">';
			
	if ($_SESSION['Items'.$identifier]->DeliverBlind ==2){
		echo '<option value="1">' . _('Show Company Details/Logo') . '</option>';
		echo '<option selected value="2">' . _('Hide Company Details/Logo') . '</option>';
	} else {
		echo '<option selected value="1">' . _('Show Company Details/Logo') . '</option>';
		echo '<option value="2">' . _('Hide Company Details/Logo') . '</option>';
	}
	echo '</select></td></tr>';

if (isset($_SESSION['PrintedPackingSlip']) and $_SESSION['PrintedPackingSlip']==1){

	echo '<tr>
		<td>'. _('Reprint packing slip') .':</td>
		<td><select name="ReprintPackingSlip">';
	echo '<option value=0>' . _('Yes') . '</option>';
	echo '<option selected value=1>' . _('No') . '</option>';
	echo '</select>	'. _('Last printed') .': ' . ConvertSQLDate($_SESSION['DatePackingSlipPrinted']) . '</td></tr>';
} else {
	echo '<input type=hidden name="ReprintPackingSlip" value=0>';
}

echo '<tr><td>'. _('Charge Freight Cost inc tax') .':</td>';
echo '<td><input type=text class="number" size=10 maxlength=12 name="FreightCost" value="' . $_SESSION['Items'.$identifier]->FreightCost . '"></td>';

if ($_SESSION['DoFreightCalc']==true){
	echo '<td><input type=submit name="Update" value="' . _('Recalc Freight Cost') . '"></td></tr>';
}

if ((!isset($_POST['ShipVia']) OR $_POST['ShipVia']=='') AND isset($_SESSION['Items'.$identifier]->ShipVia)){
	$_POST['ShipVia'] = $_SESSION['Items'.$identifier]->ShipVia;
}

echo '<tr><td>'. _('Freight/Shipper Method') .':</td>
			<td><select name="ShipVia">';

$ErrMsg = _('The shipper details could not be retrieved');
$DbgMsg = _('SQL used to retrieve the shipper details was') . ':';

$sql = "SELECT shipper_id, shippername FROM shippers";
$ShipperResults = DB_query($sql,$db,$ErrMsg,$DbgMsg);
while ($myrow=DB_fetch_array($ShipperResults)){
	if ($myrow['shipper_id']==$_POST['ShipVia']){
			echo '<option selected value=' . $myrow['shipper_id'] . '>' . $myrow['shippername'] . '</option>';
	}else {
		echo '<option value=' . $myrow['shipper_id'] . '>' . $myrow['shippername'] . '</option>';
	}
}

echo '</select></td></tr>';



$empid=$_SESSION['empid'];


$employee_arr=array();
 
$sql_drop="DROP TABLE IF EXISTS `emptable`";
$result_drop=DB_query($sql_drop,$db);
 
$sql_create="CREATE TABLE emptable (empid int)";
$result_create=DB_query($sql_create,$db);   

function showemp($empid,$db,$y)
{  
    $sql3="SELECT reportto FROM bio_emp WHERE empid='".$empid."'";
    $result3=DB_query($sql3,$db);
    $employee_arr=array();
    while($row3=DB_fetch_array($result3)){
        $empid=$row3['reportto'];
        $sql_insert="INSERT INTO emptable (empid) VALUES ($empid)";
        $result_insert=DB_query($sql_insert,$db);
        $employee_arr[]=$empid;
        if($empid>1){
            showemp($empid,$db,$y);  
        }
        elseif($empid==0){
            $employee_arr[]=1;  
        }
                                            
    } 
                                      
} 
                      
                      //$sql_insert1="INSERT INTO emptable (empid) VALUES ($_SESSION[empid])";
                     //$result_insert2=DB_query($sql_insert1,$db);
     
$sql2="SELECT reportto FROM bio_emp WHERE empid=$empid";
$result2=DB_query($sql2,$db);   
while($row2=DB_fetch_array($result2))
{ 
    $empid=$row2['reportto'];
//    $employee_arr[]=$empid;
    $y=$empid;
    if($empid>1){
      showemp($empid,$db,$y);  
    }
    elseif($empid==0){
      $employee_arr[]=1;  
    }elseif($empid==1){
      $employee_arr[]=1;  
    }
    
} 
$sql_select="SELECT empid FROM emptable";
$result_select=DB_query($sql_select,$db);
while($row_select=DB_fetch_array($result_select)){
    $employee_arr[]=$row_select['empid'];
}
$employee_arr=join(",", $employee_arr);
$sql5="SELECT userid FROM www_users WHERE empid IN ($employee_arr)";
$result5=DB_query($sql5,$db);
while($row5=DB_fetch_array($result5)){
    $userid[]="'".$row5['userid']."'";     
}
$user_array=join(",", $userid);

$sql_approvalby="SELECT www_users.userid, bio_emp.empname
        FROM bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid IN ($user_array)";
$result_approvalby=DB_query($sql_approvalby,$db);
//$row_approvalby=DB_fetch_array($result_approvalby);
//echo$row_approvalby['userid'];                 

echo '<tr><td width=50%>Approval By</td>';
echo"<td><select name='ApprovalBy' id='approvalby' style='width:150px'>";
   echo'<option value=0></option>';
        while($row_approvalby=DB_fetch_array($result_approvalby))
        {       
        if ($row_approvalby['userid']==$_POST['ApprovalBy'])
        {
         echo '<option selected value="';
        } else {
         echo '<option value="';
        }
        echo $row_approvalby['userid'] . '">'.$row_approvalby['empname'];
        echo '</option>';
        }
echo"</select></td></tr>";


echo '</table>';

echo '<br /><div class="centre"><input type=submit name="BackToLineDetails" value="' . _('Modify Order Lines') . '"><br />';

if ($_SESSION['ExistingOrder']==0){
	echo '<br /><br /><input type=submit name="ProcessOrder" value="' . _('Place Order') . '">';
	echo '<br /><br /><input type=submit name="MakeRecurringOrder" value="' . _('Create Recurring Order') . '">';
} else {
	echo '<br /><input type=submit name="ProcessOrder" value="' . _('Commit Order Changes') . '">';
}

echo '</div></form>';
include('includes/footer.inc');
?>