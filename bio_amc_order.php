<?php
/* $Id: SelectOrderItems.php 4571 2011-05-16 10:46:50Z daintree $*/
 $PageSecurity = 80;    
include('includes/DefineCartClass.php');

/* Session started in session.inc for password checking and authorisation level check
config.php is in turn included in session.inc*/

include('includes/session.inc');

if (isset($_GET['ModifyOrderNumber'])) {
	$title = _('Modifying Order') . ' ' . $_GET['ModifyOrderNumber'];
} else {
	$title = _('Select Order Items');
}

include('includes/header.inc');
include('includes/GetPrice.inc');
include('includes/SQL_CommonFunctions.inc');

if (isset($_POST['QuickEntry'])){
	unset($_POST['PartSearch']);
}

if (isset($_POST['order_items'])){
	foreach ($_POST as $key => $value) {
		if (strstr($key,'itm')) {
			$NewItem_array[substr($key,3)] = trim($value);
		}
	}
}

if (isset($_GET['NewItem'])){
	$NewItem = trim($_GET['NewItem']);
}


if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other order entry sessions on the same machine  */
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

if (isset($_GET['NewOrder'])){
  /*New order entry - clear any existing order details from the Items object and initiate a newy*/
	 if (isset($_SESSION['Items'.$identifier])){
		unset ($_SESSION['Items'.$identifier]->LineItems);
		$_SESSION['Items'.$identifier]->ItemsOrdered=0;
		unset ($_SESSION['Items'.$identifier]);
	}

	$_SESSION['ExistingOrder']=0;
	$_SESSION['Items'.$identifier] = new cart;

	if (count($_SESSION['AllowedPageSecurityTokens'])==1){ //its a customer logon
		$_SESSION['Items'.$identifier]->DebtorNo=$_SESSION['CustomerID'];
		$_SESSION['RequireCustomerSelection']=0;
	} else {
		$_SESSION['Items'.$identifier]->DebtorNo='';
		$_SESSION['RequireCustomerSelection']=1;
	}

}

if (isset($_GET['ModifyOrderNumber'])
	AND $_GET['ModifyOrderNumber']!=''){

/* The delivery check screen is where the details of the order are either updated or inserted depending on the value of ExistingOrder */

	if (isset($_SESSION['Items'.$identifier])){
		unset ($_SESSION['Items'.$identifier]->LineItems);
		unset ($_SESSION['Items'.$identifier]);
	}
	$_SESSION['ExistingOrder']=$_GET['ModifyOrderNumber'];
	$_SESSION['RequireCustomerSelection'] = 0;
	$_SESSION['Items'.$identifier] = new cart;

/*read in all the guff from the selected order into the Items cart  */

	$OrderHeaderSQL = "SELECT salesorders.debtorno,
 				  debtorsmaster.name,
				  salesorders.branchcode,
				  salesorders.customerref,
				  salesorders.comments,
				  salesorders.orddate,
				  salesorders.ordertype,
				  salestypes.sales_type,
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
				  currencies.decimalplaces,
				  paymentterms.terms,
				  salesorders.fromstkloc,
				  salesorders.printedpackingslip,
				  salesorders.datepackingslipprinted,
				  salesorders.quotation,
				  salesorders.deliverblind,
				  debtorsmaster.customerpoline,
				  locations.locationname,
				  custbranch.estdeliverydays,
				  custbranch.salesman
				FROM salesorders 
				INNER JOIN debtorsmaster 
				ON salesorders.debtorno = debtorsmaster.debtorno
				 INNER JOIN salestypes 
				 ON salesorders.ordertype=salestypes.typeabbrev
				 INNER JOIN custbranch 
				 ON salesorders.debtorno = custbranch.debtorno 
				 AND salesorders.branchcode = custbranch.branchcode
				 INNER JOIN paymentterms
				 ON debtorsmaster.paymentterms=paymentterms.termsindicator 
				 INNER JOIN locations  
				 ON locations.loccode=salesorders.fromstkloc 
				 INNER JOIN currencies
				 ON debtorsmaster.currcode=currencies.currabrev 
				WHERE salesorders.orderno = '" . $_GET['ModifyOrderNumber'] . "'";

	$ErrMsg =  _('The order cannot be retrieved because');
	$GetOrdHdrResult = DB_query($OrderHeaderSQL,$db,$ErrMsg);

	if (DB_num_rows($GetOrdHdrResult)==1) {

		$myrow = DB_fetch_array($GetOrdHdrResult);
		if ($_SESSION['SalesmanLogin']!='' AND $_SESSION['SalesmanLogin']!=$myrow['salesman']){
			prnMsg(_('Your account is set up to see only a specific salespersons orders. You are not authorised to modify this order'),'error');
			include('includes/footer.inc');
			exit;
		}
		$_SESSION['Items'.$identifier]->OrderNo = $_GET['ModifyOrderNumber'];
		$_SESSION['Items'.$identifier]->DebtorNo = $myrow['debtorno'];
/*CustomerID defined in header.inc */
		$_SESSION['Items'.$identifier]->Branch = $myrow['branchcode'];
		$_SESSION['Items'.$identifier]->CustomerName = $myrow['name'];
		$_SESSION['Items'.$identifier]->CustRef = $myrow['customerref'];
		$_SESSION['Items'.$identifier]->Comments = stripcslashes($myrow['comments']);
		$_SESSION['Items'.$identifier]->PaymentTerms =$myrow['terms'];
		$_SESSION['Items'.$identifier]->DefaultSalesType =$myrow['ordertype'];
		$_SESSION['Items'.$identifier]->SalesTypeName =$myrow['sales_type'];
		$_SESSION['Items'.$identifier]->DefaultCurrency = $myrow['currcode'];
		$_SESSION['Items'.$identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];
		$_SESSION['Items'.$identifier]->ShipVia = $myrow['shipvia'];
		$BestShipper = $myrow['shipvia'];
		$_SESSION['Items'.$identifier]->DeliverTo = $myrow['deliverto'];
		$_SESSION['Items'.$identifier]->DeliveryDate = ConvertSQLDate($myrow['deliverydate']);
		$_SESSION['Items'.$identifier]->DelAdd1 = $myrow['deladd1'];
		$_SESSION['Items'.$identifier]->DelAdd2 = $myrow['deladd2'];
		$_SESSION['Items'.$identifier]->DelAdd3 = $myrow['deladd3'];
		$_SESSION['Items'.$identifier]->DelAdd4 = $myrow['deladd4'];
		$_SESSION['Items'.$identifier]->DelAdd5 = $myrow['deladd5'];
		$_SESSION['Items'.$identifier]->DelAdd6 = $myrow['deladd6'];
		$_SESSION['Items'.$identifier]->PhoneNo = $myrow['contactphone'];
		$_SESSION['Items'.$identifier]->Email = $myrow['contactemail'];
		$_SESSION['Items'.$identifier]->Location = $myrow['fromstkloc'];
		$_SESSION['Items'.$identifier]->LocationName = $myrow['locationname'];
		$_SESSION['Items'.$identifier]->Quotation = $myrow['quotation'];
		$_SESSION['Items'.$identifier]->FreightCost = $myrow['freightcost'];
		$_SESSION['Items'.$identifier]->Orig_OrderDate = $myrow['orddate'];
		$_SESSION['PrintedPackingSlip'] = $myrow['printedpackingslip'];
		$_SESSION['DatePackingSlipPrinted'] = $myrow['datepackingslipprinted'];
		$_SESSION['Items'.$identifier]->DeliverBlind = $myrow['deliverblind'];
		$_SESSION['Items'.$identifier]->DefaultPOLine = $myrow['customerpoline'];
		$_SESSION['Items'.$identifier]->DeliveryDays = $myrow['estdeliverydays'];

		//Get The exchange rate used for GPPercent calculations on adding or amending items
		if ($_SESSION['Items'.$identifier]->DefaultCurrency != $_SESSION['CompanyRecord']['currencydefault']){
			$ExRateResult = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['Items'.$identifier]->DefaultCurrency . "'",$db);
			if (DB_num_rows($ExRateResult)>0){
				$ExRateRow = DB_fetch_row($ExRateResult);
				$ExRate = $ExRateRow[0];
			} else {
				$ExRate =1;
			}
		} else {
			$ExRate = 1;
		}

/*need to look up customer name from debtors master then populate the line items array with the sales order details records */

			$LineItemsSQL = "SELECT salesorderdetails.orderlineno,
									salesorderdetails.stkcode,
									stockmaster.description,
									stockmaster.volume,
									stockmaster.kgs,
									stockmaster.units,
									stockmaster.serialised,
									stockmaster.nextserialno,
									stockmaster.eoq,
									salesorderdetails.unitprice,
									salesorderdetails.quantity,
									salesorderdetails.discountpercent,
									salesorderdetails.actualdispatchdate,
									salesorderdetails.qtyinvoiced,
									salesorderdetails.narrative,
									salesorderdetails.itemdue,
									salesorderdetails.poline,
									locstock.quantity as qohatloc,
									stockmaster.mbflag,
									stockmaster.discountcategory,
									stockmaster.decimalplaces,
									stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standardcost,
									salesorderdetails.completed
									FROM salesorderdetails INNER JOIN stockmaster
									ON salesorderdetails.stkcode = stockmaster.stockid
									INNER JOIN locstock ON locstock.stockid = stockmaster.stockid
									WHERE  locstock.loccode = '" . $myrow['fromstkloc'] . "'
									AND salesorderdetails.orderno ='" . $_GET['ModifyOrderNumber'] . "'
									ORDER BY salesorderdetails.orderlineno";

		$ErrMsg = _('The line items of the order cannot be retrieved because');
		$LineItemsResult = db_query($LineItemsSQL,$db,$ErrMsg);
		if (db_num_rows($LineItemsResult)>0) {

			while ($myrow=db_fetch_array($LineItemsResult)) {
					if ($myrow['completed']==0){
						$_SESSION['Items'.$identifier]->add_to_cart($myrow['stkcode'],
														$myrow['quantity'],
														$myrow['description'],
														$myrow['unitprice'],
														$myrow['discountpercent'],
														$myrow['units'],
														$myrow['volume'],
														$myrow['kgs'],
														$myrow['qohatloc'],
														$myrow['mbflag'],
														$myrow['actualdispatchdate'],
														$myrow['qtyinvoiced'],
														$myrow['discountcategory'],
														0,	/*Controlled*/
														$myrow['serialised'],
														$myrow['decimalplaces'],
														$myrow['narrative'],
														'No', /* Update DB */
														$myrow['orderlineno'],
														0,
														'',
														ConvertSQLDate($myrow['itemdue']),
														$myrow['poline'],
														$myrow['standardcost'],
														$myrow['eoq'],
														$myrow['nextserialno'],
														$ExRate );

				/*Just populating with existing order - no DBUpdates */
					}
					$LastLineNo = $myrow['orderlineno'];
			} /* line items from sales order details */
			 $_SESSION['Items'.$identifier]->LineCounter = $LastLineNo+1;
		} //end of checks on returned data set
	}
}


if (!isset($_SESSION['Items'.$identifier])){
	/* It must be a new order being created $_SESSION['Items'.$identifier] would be set up from the order
	modification code above if a modification to an existing order. Also $ExistingOrder would be
	set to 1. The delivery check screen is where the details of the order are either updated or
	inserted depending on the value of ExistingOrder */

	$_SESSION['ExistingOrder']=0;
	$_SESSION['Items'.$identifier] = new cart;
	$_SESSION['PrintedPackingSlip'] =0; /*Of course cos the order aint even started !!*/

	if (in_array(2,$_SESSION['AllowedPageSecurityTokens']) AND ($_SESSION['Items'.$identifier]->DebtorNo=='' OR !isset($_SESSION['Items'.$identifier]->DebtorNo))){

	/* need to select a customer for the first time out if authorisation allows it and if a customer
	 has been selected for the order or not the session variable CustomerID holds the customer code
	 already as determined from user id /password entry  */
		$_SESSION['RequireCustomerSelection'] = 1;
	} else {
		$_SESSION['RequireCustomerSelection'] = 0;
	}
}

if (isset($_POST['ChangeCustomer']) AND $_POST['ChangeCustomer']!=''){

	if ($_SESSION['Items'.$identifier]->Any_Already_Delivered()==0){
		$_SESSION['RequireCustomerSelection']=1;
	} else {
		prnMsg(_('The customer the order is for cannot be modified once some of the order has been invoiced'),'warn');
	}
}

//Customer logins are not allowed to select other customers henc in_array(2,$_SESSION['AllowedPageSecurityTokens'])
if (isset($_POST['SearchCust']) AND $_SESSION['RequireCustomerSelection']==1 AND in_array(2,$_SESSION['AllowedPageSecurityTokens'])){

	if (($_POST['CustKeywords']!='') AND (($_POST['CustCode']!='') OR ($_POST['CustPhone']!=''))) {
		prnMsg( _('Customer Branch Name keywords have been used in preference to the Customer Branch Code or Branch Phone Number entered'), 'warn');
	}
	if (($_POST['CustCode']!='') AND ($_POST['CustPhone']!='')) {
		prnMsg(_('Customer Branch Code has been used in preference to the Customer Branch Phone Number entered'), 'warn');
	}
	if (($_POST['CustKeywords']=='') AND ($_POST['CustCode']=='')  AND ($_POST['CustPhone']=='')) {
		prnMsg(_('At least one Customer Branch Name keyword OR an extract of a Customer Branch Code or Branch Phone Number must be entered for the search'), 'warn');
	} else {
		if (strlen($_POST['CustKeywords'])>0) {
		//insert wildcard characters in spaces
			$_POST['CustKeywords'] = strtoupper(trim($_POST['CustKeywords']));
			$SearchString = '%' . str_replace(' ', '%', $_POST['CustKeywords']) . '%';

			$SQL = "SELECT custbranch.brname,
							custbranch.contactname,
							custbranch.phoneno,
							custbranch.faxno,
							custbranch.branchcode,
							custbranch.debtorno,
							debtorsmaster.name
						FROM custbranch
						LEFT JOIN debtorsmaster
						ON custbranch.debtorno=debtorsmaster.debtorno
						WHERE custbranch.brname " . LIKE . " '$SearchString'";

			if ($_SESSION['SalesmanLogin']!=''){
				$SQL .= " AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "'";
			}
			$SQL .=	" AND custbranch.disabletrans=0
						ORDER BY custbranch.debtorno, custbranch.branchcode";

		} elseif (strlen($_POST['CustCode'])>0){

			$_POST['CustCode'] = strtoupper(trim($_POST['CustCode']));

			$SQL = "SELECT custbranch.brname,
							custbranch.contactname,
							custbranch.phoneno,
							custbranch.faxno,
							custbranch.branchcode,
							custbranch.debtorno,
							debtorsmaster.name
						FROM custbranch
						LEFT JOIN debtorsmaster
						ON custbranch.debtorno=debtorsmaster.debtorno
						WHERE custbranch.debtorno " . LIKE . " '%" . $_POST['CustCode'] . "%' OR custbranch.branchcode " . LIKE . " '%" . $_POST['CustCode'] . "%'";

			if ($_SESSION['SalesmanLogin']!=''){
				$SQL .= " AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "'";
			}
			$SQL .=	" AND custbranch.disabletrans=0
						ORDER BY custbranch.debtorno";
		} elseif (strlen($_POST['CustPhone'])>0){
			$SQL = "SELECT custbranch.brname,
							custbranch.contactname,
							custbranch.phoneno,
							custbranch.faxno,
							custbranch.branchcode,
							custbranch.debtorno,
							debtorsmaster.name
						FROM custbranch
						INNER JOIN debtorsmaster
						ON custbranch.debtorno=debtorsmaster.debtorno
						WHERE custbranch.phoneno " . LIKE . " '%" . $_POST['CustPhone'] . "%'";

			if ($_SESSION['SalesmanLogin']!=''){
				$SQL .= " AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "'";
			}

			$SQL .=	" AND custbranch.disabletrans=0
					ORDER BY custbranch.debtorno";
		}

		$ErrMsg = _('The searched customer records requested cannot be retrieved because');
		$result_CustSelect = DB_query($SQL,$db,$ErrMsg);

		if (DB_num_rows($result_CustSelect)==1){
			$myrow=DB_fetch_array($result_CustSelect);
			$SelectedCustomer = $myrow['debtorno'];
                        $SelectedBranch = $myrow['branchcode'];
		} elseif (DB_num_rows($result_CustSelect)==0){
			prnMsg(_('No Customer Branch records contain the search criteria') . ' - ' . _('please try again') . ' - ' . _('Note a Customer Branch Name may be different to the Customer Name'),'info');
		}
	} /*one of keywords or custcode was more than a zero length string */
} /*end of if search for customer codes/names */

if (isset($_POST['JustSelectedACustomer'])){
	/*Need to figure out the number of the form variable that the user clicked on */
	for ($i=1;$i<count($_POST);$i++){ //loop through the returned customers
		if(isset($_POST['SubmitCustomerSelection'.$i])){
			break;
		}
	}
	if ($i==count($_POST)){
			prnMsg(_('Unable to identify the selected customer'),'error');
	} else {
		$SelectedCustomer = $_POST['SelectedCustomer'.$i];  
          
		$SelectedBranch = $_POST['SelectedBranch'.$i];
	}
}

       $SelectedCustomer=$_GET['amcdebt'];
           $SelectedBranch=$_GET['amcdebt'];

/* will only be true if page called from customer selection form or set because only one customer
 record returned from a search so parse the $SelectCustomer string into customer code and branch code */
if (isset($SelectedCustomer)) {

	$_SESSION['Items'.$identifier]->DebtorNo  = trim($SelectedCustomer);
	$_SESSION['Items'.$identifier]->Branch = trim($SelectedBranch);

	// Now check to ensure this account is not on hold */
	$sql = "SELECT debtorsmaster.name,
					holdreasons.dissallowinvoices,
					debtorsmaster.salestype,
					salestypes.sales_type,
					debtorsmaster.currcode,
					debtorsmaster.customerpoline,
					paymentterms.terms,
					currencies.decimalplaces
			FROM debtorsmaster INNER JOIN holdreasons
			ON debtorsmaster.holdreason=holdreasons.reasoncode
			INNER JOIN salestypes 
			ON debtorsmaster.salestype=salestypes.typeabbrev
			INNER JOIN paymentterms 
			ON debtorsmaster.paymentterms=paymentterms.termsindicator
			INNER JOIN currencies 
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE debtorsmaster.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo. "'";

	$ErrMsg = _('The details of the customer selected') . ': ' .  $_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('cannot be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the customer details and failed was') . ':';
	$result =DB_query($sql,$db,$ErrMsg,$DbgMsg);

	$myrow = DB_fetch_array($result);
	if ($myrow[1] != 1){
		if ($myrow[1]==2){
			prnMsg(_('The') . ' ' . $myrow[0] . ' ' . _('account is currently flagged as an account that needs to be watched. Please contact the credit control personnel to discuss'),'warn');
		}

		$_SESSION['RequireCustomerSelection']=0;
		$_SESSION['Items'.$identifier]->CustomerName = $myrow['name'];

# the sales type determines the price list to be used by default the customer of the user is
# defaulted from the entry of the userid and password.

		$_SESSION['Items'.$identifier]->DefaultSalesType = $myrow['salestype'];
		$_SESSION['Items'.$identifier]->SalesTypeName = $myrow['sales_type'];
		$_SESSION['Items'.$identifier]->DefaultCurrency = $myrow['currcode'];
		$_SESSION['Items'.$identifier]->DefaultPOLine = $myrow['customerpoline'];
		$_SESSION['Items'.$identifier]->PaymentTerms = $myrow['terms'];
		$_SESSION['Items'.$identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];

# the branch was also selected from the customer selection so default the delivery details from the customer branches table CustBranch. The order process will ask for branch details later anyway

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
						custbranch.estdeliverydays,
						locations.locationname,
						custbranch.salesman
					FROM custbranch
					INNER JOIN locations
					ON custbranch.defaultlocation=locations.loccode
					WHERE custbranch.branchcode='" . $_SESSION['Items'.$identifier]->Branch . "'
					AND custbranch.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo . "'";
	
		$ErrMsg = _('The customer branch record of the customer selected') . ': ' . $_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('cannot be retrieved because');
		$DbgMsg = _('SQL used to retrieve the branch details was') . ':';
		$result =DB_query($sql,$db,$ErrMsg,$DbgMsg);

		if (DB_num_rows($result)==0){

			prnMsg(_('The branch details for branch code') . ': ' . $_SESSION['Items'.$identifier]->Branch . ' ' . _('against customer code') . ': ' . $_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('could not be retrieved') . '. ' . _('Check the set up of the customer and branch'),'error');

			if ($debug==1){
				prnMsg( _('The SQL that failed to get the branch details was') . ':<br />' . $sql . 'warning');
			}
			include('includes/footer.inc');
			exit;
		}
		// add echo
		echo '<br />';
		$myrow = DB_fetch_row($result);
		if ($_SESSION['SalesmanLogin']!='' AND $_SESSION['SalesmanLogin']!=$myrow[15]){
			prnMsg(_('Your login is only set up for a particular salesperson. This customer has a different salesperson.'),'error');
			include('includes/footer.inc');
			exit;
		}

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
		$_SESSION['Items'.$identifier]->LocationName = $myrow[14];

		if ($_SESSION['Items'.$identifier]->SpecialInstructions)
		  prnMsg($_SESSION['Items'.$identifier]->SpecialInstructions,'warn');

		if ($_SESSION['CheckCreditLimits'] > 0){  /*Check credit limits is 1 for warn and 2 for prohibit sales */
			$_SESSION['Items'.$identifier]->CreditAvailable = GetCreditAvailable($_SESSION['Items'.$identifier]->DebtorNo,$db);

			if ($_SESSION['CheckCreditLimits']==1 AND $_SESSION['Items'.$identifier]->CreditAvailable <=0){
				prnMsg(_('The') . ' ' . $myrow[0] . ' ' . _('account is currently at or over their credit limit'),'warn');
			} elseif ($_SESSION['CheckCreditLimits']==2 AND $_SESSION['Items'.$identifier]->CreditAvailable <=0){
				prnMsg(_('No more orders can be placed by') . ' ' . $myrow[0] . ' ' . _(' their account is currently at or over their credit limit'),'warn');
				include('includes/footer.inc');
				exit;
			}
		}

	} else {
		prnMsg(_('The') . ' ' . $myrow[0] . ' ' . _('account is currently on hold please contact the credit control personnel to discuss'),'warn');
	}

} elseif (!$_SESSION['Items'.$identifier]->DefaultSalesType OR $_SESSION['Items'.$identifier]->DefaultSalesType=='')	{

#Possible that the check to ensure this account is not on hold has not been done
#if the customer is placing own order, if this is the case then
#DefaultSalesType will not have been set as above

	$sql = "SELECT debtorsmaster.name,
					holdreasons.dissallowinvoices,
					debtorsmaster.salestype,
					debtorsmaster.currcode,
					currencies.decimalplaces,
					debtorsmaster.customerpoline
			FROM debtorsmaster 
			INNER JOIN holdreasons
			ON debtorsmaster.holdreason=holdreasons.reasoncode
			INNER JOIN currencies 
			ON debtorsmaster.currcode=currencies.currabrev
			AND debtorsmaster.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo . "'";

	$ErrMsg = _('The details for the customer selected') . ': ' .$_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('cannot be retrieved because');
	$DbgMsg = _('SQL used to retrieve the customer details was') . ':<br />' . $sql;
	$result =DB_query($sql,$db,$ErrMsg,$DbgMsg);

	$myrow = DB_fetch_array($result);
	if ($myrow[1] == 0){

		$_SESSION['Items'.$identifier]->CustomerName = $myrow[0];

# the sales type determines the price list to be used by default the customer of the user is
# defaulted from the entry of the userid and password.

		$_SESSION['Items'.$identifier]->DefaultSalesType = $myrow['salestype'];
		$_SESSION['Items'.$identifier]->DefaultCurrency = $myrow['currcode'];
		$_SESSION['Items'.$identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];
		$_SESSION['Items'.$identifier]->Branch = $_SESSION['UserBranch'];
		$_SESSION['Items'.$identifier]->DefaultPOLine = $myrow['customerpoline'];

	// the branch would be set in the user data so default delivery details as necessary. However,
	// the order process will ask for branch details later anyway

		 $sql = "SELECT custbranch.brname,
						custbranch.branchcode,
						custbranch.braddress1,
						custbranch.braddress2,
						custbranch.braddress3,
						custbranch.braddress4,
						custbranch.braddress5,
						custbranch.braddress6,
						custbranch.phoneno,
						custbranch.email,
						custbranch.defaultlocation,
						custbranch.deliverblind,
						custbranch.estdeliverydays,
						locations.locationname
				FROM custbranch INNER JOIN locations
				ON custbranch.defaultlocation=locations.loccode
				WHERE custbranch.branchcode='" . $_SESSION['Items'.$identifier]->Branch . "'
				AND custbranch.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo . "'";

		$ErrMsg = _('The customer branch record of the customer selected') . ': ' . $_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('cannot be retrieved because');
		$DbgMsg = _('SQL used to retrieve the branch details was');
		$result =DB_query($sql,$db,$ErrMsg, $DbgMsg);

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
		$_SESSION['Items'.$identifier]->DeliverBlind = $myrow[10];
		$_SESSION['Items'.$identifier]->DeliveryDays = $myrow[11];
		$_SESSION['Items'.$identifier]->LocationName = $myrow[12];
	} else {
		prnMsg(_('Sorry, your account has been put on hold for some reason, please contact the credit control personnel.'),'warn');
		include('includes/footer.inc');
		exit;
	}
}

if ($_SESSION['RequireCustomerSelection'] ==1
	OR !isset($_SESSION['Items'.$identifier]->DebtorNo)
	OR $_SESSION['Items'.$identifier]->DebtorNo=='') {

	echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' .
	' ' . _('Enter an Order or Quotation') . ' : ' . _('Search for the Customer Branch.') . '</p>';
	echo '<div class="page_help_text">' . _('Orders/Quotations are placed against the Customer Branch. A Customer may have several Branches.') . '</div>';
	echo '<form action="' . $_SERVER['PHP_SELF'] . '?identifier='.$identifier . '" name="SelectCustomer" method="post">
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<table cellpadding="3" colspan="4" class="selection">
			<tr>
			<td><h5>' . _('Part of the Customer Branch Name') . ':</h5></td>
			<td><input tabindex=1 type="Text" name="CustKeywords" size=20	maxlength=25></td>
			<td><h2><b>' . _('OR') . '</b></h2></td>
			<td><h5>' . _('Part of the Customer Branch Code') . ':</h5></td>
			<td><input tabindex=2 type="text" name="CustCode" size=15	maxlength=18></td>
			<td><h2><b>' . _('OR') . '</b></h2></td>
			<td><h5>' . _('Part of the Branch Phone Number') . ':</h5></td>
			<td><input tabindex=3 type="text" name="CustPhone" size=15 maxlength=18></td>
			</tr>
		</table>
	<br /><div class="centre"><input tabindex=4 type="submit" name="SearchCust" value="' . _('Search Now') . '" />
	<input tabindex=5 type="submit" action="reset" value="' .  _('Reset') . '"></div>';

	if (isset($result_CustSelect)) {

		echo '<table cellpadding=2 colspan=7>';

		$TableHeader = '<br /><tr>
				<th>' . _('Customer') . '</th>
				<th>' . _('Branch') . '</th>
				<th>' . _('Contact') . '</th>
				<th>' . _('Phone') . '</th>
				<th>' . _('Fax') . '</th>
				</tr>';
		echo $TableHeader;

		$j = 1;
		$k = 0; //row counter to determine background colour
		$LastCustomer='';
		while ($myrow=DB_fetch_array($result_CustSelect)) {

			if ($k==1){
				echo '<tr class="EvenTableRows">';
				$k=0;
			} else {
				echo '<tr class="OddTableRows">';
				$k=1;
			}
			echo '<form action="' . $_SERVER['PHP_SELF'] . '?identifier='.$identifier . '" name="SelectParts" method="post">';
			echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
			if ($LastCustomer != $myrow['name']) {
				echo '<td>'.$myrow['name'].'</td>';
			} else {
				echo '<td></td>';
			}
			echo '<td><input tabindex='.($j+5).' type=submit name="SubmitCustomerSelection' . $j .'" value="' . htmlentities($myrow['brname'], ENT_QUOTES,'UTF-8'). '"></td>
					<input type="hidden" name="SelectedCustomer' . $j .'" value="'.$myrow['debtorno'].'"><input type="hidden" name="SelectedBranch' . $j .'" value="'. $myrow['branchcode'].'" />
					<td>'.$myrow['contactname'].'</td>
					<td>'.$myrow['phoneno'].'</td>
					<td>'.$myrow['faxno'].'</td>
					</tr>';
			$LastCustomer=$myrow['name'];
			$j++;
//end of page full new headings if
		}
//end of while loop
		echo '<input type="hidden" name="JustSelectedACustomer" value="Yes">';
		echo '</table></form>';

	}//end if results to show

//end if RequireCustomerSelection
} else { //dont require customer selection
// everything below here only do if a customer is selected

 	if (isset($_POST['CancelOrder'])) {
		$OK_to_delete=1;	//assume this in the first instance

		if($_SESSION['ExistingOrder']!=0) { //need to check that not already dispatched

			$sql = "SELECT qtyinvoiced
							FROM salesorderdetails
							WHERE orderno='" . $_SESSION['ExistingOrder'] . "'
							AND qtyinvoiced>0";

			$InvQties = DB_query($sql,$db);

			if (DB_num_rows($InvQties)>0){

				$OK_to_delete=0;

				prnMsg( _('There are lines on this order that have already been invoiced. Please delete only the lines on the order that are no longer required') . '<p>' . _('There is an option on confirming a dispatch/invoice to automatically cancel any balance on the order at the time of invoicing if you know the customer will not want the back order'),'warn');
			}
		}

		if ($OK_to_delete==1){
			if($_SESSION['ExistingOrder']!=0){

				$SQL = "DELETE FROM salesorderdetails WHERE salesorderdetails.orderno ='" . $_SESSION['ExistingOrder'] . "'";
				$ErrMsg =_('The order detail lines could not be deleted because');
				$DelResult=DB_query($SQL,$db,$ErrMsg);

				$SQL = "DELETE FROM salesorders WHERE salesorders.orderno='" . $_SESSION['ExistingOrder'] . "'";
				$ErrMsg = _('The order header could not be deleted because');
				$DelResult=DB_query($SQL,$db,$ErrMsg);

				$_SESSION['ExistingOrder']=0;
			}

			unset($_SESSION['Items'.$identifier]->LineItems);
			$_SESSION['Items'.$identifier]->ItemsOrdered=0;
			unset($_SESSION['Items'.$identifier]);
			$_SESSION['Items'.$identifier] = new cart;

			if (in_array(2,$_SESSION['AllowedPageSecurityTokens'])){
				$_SESSION['RequireCustomerSelection'] = 1;
			} else {
				$_SESSION['RequireCustomerSelection'] = 0;
			}
			echo '<br /><br />';
			prnMsg(_('This sales order has been cancelled as requested'),'success');
			include('includes/footer.inc');
			exit;
		}
	} else { /*Not cancelling the order */

		echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/inventory.png" title="' . _('Order') . '" alt="" />' . ' ';

		if ($_SESSION['Items'.$identifier]->Quotation==1){
			echo _('Quotation for customer') . ' ';
		} else {
			echo _('Order for customer') . ' ';
		}

		echo ':<b> ' . $_SESSION['Items'.$identifier]->DebtorNo  . ' ' . _('Customer Name') . ': ' . $_SESSION['Items'.$identifier]->CustomerName;
		echo '</b></p><div class="page_help_text">' . '<b>' . _('Default Options (can be modified during order):') . '</b><br />' . _('Deliver To') . ':<b> ' . $_SESSION['Items'.$identifier]->DeliverTo;
		echo '</b>&nbsp;' . _('From Location') . ':<b> ' . $_SESSION['Items'.$identifier]->LocationName;
		echo '</b><br />' . _('Sales Type') . '/' . _('Price List') . ':<b> ' . $_SESSION['Items'.$identifier]->SalesTypeName;
		echo '</b><br />' . _('Terms') . ':<b> ' . $_SESSION['Items'.$identifier]->PaymentTerms;
		echo '</b></div>';
	}
	$msg ='';
	if (isset($_POST['Search']) or isset($_POST['Next']) or isset($_POST['Prev'])){

		if ($_POST['Keywords']!=='' AND $_POST['StockCode']=='') {
			$msg='</b><div class="page_help_text">' . _('Order Item description has been used in search') . '.</div>';
		} elseif ($_POST['StockCode']!=='' AND $_POST['Keywords']=='') {
			$msg='</b><div class="page_help_text">' . _('Stock Code has been used in search') . '.</div>';
		} elseif ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
			$msg='</b><div class="page_help_text">' . _('Stock Category has been used in search') . '.</div>';
		}
		if (isset($_POST['Keywords']) AND strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$_POST['Keywords'] = strtoupper($_POST['Keywords']);
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			if ($_POST['StockCat']=='All'){
				$SQL = "SELECT stockmaster.stockid,
								stockmaster.description,
								stockmaster.units
						FROM stockmaster,
								stockcategory
						WHERE stockmaster.categoryid=stockcategory.categoryid
						AND (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
						AND stockmaster.mbflag <>'G'
						AND stockmaster.description " . LIKE . " '" . $SearchString . "'
						AND stockmaster.discontinued=0
						ORDER BY stockmaster.stockid";
			} else {
				$SQL = "SELECT stockmaster.stockid,
								stockmaster.description,
								stockmaster.units
						FROM stockmaster, stockcategory
						WHERE  stockmaster.categoryid=stockcategory.categoryid
						AND (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
						AND stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND stockmaster.description " . LIKE . " '" . $SearchString . "'
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
						ORDER BY stockmaster.stockid";
			}

		} elseif (strlen($_POST['StockCode'])>0){

			$_POST['StockCode'] = strtoupper($_POST['StockCode']);
			$SearchString = '%' . $_POST['StockCode'] . '%';

			if ($_POST['StockCat']=='All'){
				$SQL = "SELECT stockmaster.stockid,
								stockmaster.description,
								stockmaster.units
						FROM stockmaster, stockcategory
						WHERE stockmaster.categoryid=stockcategory.categoryid
						AND (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
						AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
						AND stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						ORDER BY stockmaster.stockid";
			} else {
				$SQL = "SELECT stockmaster.stockid,
								stockmaster.description,
								stockmaster.units
						FROM stockmaster, stockcategory
						WHERE stockmaster.categoryid=stockcategory.categoryid
						AND (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
						AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
						AND stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
						ORDER BY stockmaster.stockid";
			}

		} else {
			if ($_POST['StockCat']=='All'){
				$SQL = "SELECT stockmaster.stockid,
								stockmaster.description,
								stockmaster.units
						FROM stockmaster, stockcategory
						WHERE  stockmaster.categoryid=stockcategory.categoryid
						AND (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
						AND stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						ORDER BY stockmaster.stockid";
			} else {
				$SQL = "SELECT stockmaster.stockid,
								stockmaster.description,
								stockmaster.units
						FROM stockmaster, stockcategory
						WHERE stockmaster.categoryid=stockcategory.categoryid
						AND (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
						AND stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
						ORDER BY stockmaster.stockid";
			  }
		}

		if (isset($_POST['Next'])) {
			$Offset = $_POST['nextlist'];
		}
		if (isset($_POST['Prev'])) {
			$Offset = $_POST['previous'];
		}
		if (!isset($Offset) or $Offset<0) {
			$Offset=0;
		}
		$SQL = $SQL . " LIMIT " . $_SESSION['DefaultDisplayRecordsMax'] . " OFFSET " . number_format($_SESSION['DefaultDisplayRecordsMax']*$Offset);

		$ErrMsg = _('There is a problem selecting the part records to display because');
		$DbgMsg = _('The SQL used to get the part selection was');
		$SearchResult = DB_query($SQL,$db,$ErrMsg, $DbgMsg);

		if (DB_num_rows($SearchResult)==0 ){
			prnMsg (_('There are no products available meeting the criteria specified'),'info');
		}
		if (DB_num_rows($SearchResult)==1){
			$myrow=DB_fetch_array($SearchResult);
			$NewItem = $myrow['stockid'];
			DB_data_seek($SearchResult,0);
		}
		if (DB_num_rows($SearchResult)<$_SESSION['DisplayRecordsMax']){
			$Offset=0;
		}

	} //end of if search

#Always do the stuff below if not looking for a customerid
                                                                          
	echo '<form action="' . $_SERVER['PHP_SELF'] . '?identifier='.$identifier . '" name="SelectParts" method="post">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

//Get The exchange rate used for GPPercent calculations on adding or amending items
	if ($_SESSION['Items'.$identifier]->DefaultCurrency != $_SESSION['CompanyRecord']['currencydefault']){
		$ExRateResult = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['Items'.$identifier]->DefaultCurrency . "'",$db);
		if (DB_num_rows($ExRateResult)>0){
			$ExRateRow = DB_fetch_row($ExRateResult);
			$ExRate = $ExRateRow[0];
		} else {
			$ExRate =1;
		}
	} else {
		$ExRate = 1;
	}

	/*Process Quick Entry */
	/* If enter is pressed on the quick entry screen, the default button may be Recalculate */
	 if (isset($_POST['order_items'])
			OR isset($_POST['QuickEntry'])
			OR isset($_POST['Recalculate'])){

		 /* get the item details from the database and hold them in the cart object */

		 /*Discount can only be set later on  -- after quick entry -- so default discount to 0 in the first place */
		 $Discount = 0;

		 $i=1;
		  while ($i<=$_SESSION['QuickEntries'] and isset($_POST['part_' . $i]) and $_POST['part_' . $i]!='') {
			$QuickEntryCode = 'part_' . $i;
			$QuickEntryQty = 'qty_' . $i;
			$QuickEntryPOLine = 'poline_' . $i;
			$QuickEntryItemDue = 'itemdue_' . $i;

			$i++;

			if (isset($_POST[$QuickEntryCode])) {
				$NewItem = strtoupper($_POST[$QuickEntryCode]);
			}
			if (isset($_POST[$QuickEntryQty])) {
				$NewItemQty = $_POST[$QuickEntryQty];
			}
			if (isset($_POST[$QuickEntryItemDue])) {
				$NewItemDue = $_POST[$QuickEntryItemDue];
			} else {
				$NewItemDue = DateAdd (Date($_SESSION['DefaultDateFormat']),'d', $_SESSION['Items'.$identifier]->DeliveryDays);
			}
			if (isset($_POST[$QuickEntryPOLine])) {
				$NewPOLine = $_POST[$QuickEntryPOLine];
			} else {
				$NewPOLine = 0;
			}

			if (!isset($NewItem)){
				unset($NewItem);
				break;	/* break out of the loop if nothing in the quick entry fields*/
			}

			if(!Is_Date($NewItemDue)) {
					prnMsg(_('An invalid date entry was made for ') . ' ' . $NewItem . ' ' . _('The date entry') . ' ' . $NewItemDue . ' ' . _('must be in the format') . ' ' . $_SESSION['DefaultDateFormat'],'warn');
				//Attempt to default the due date to something sensible?
				$NewItemDue = DateAdd (Date($_SESSION['DefaultDateFormat']),'d', $_SESSION['Items'.$identifier]->DeliveryDays);
			}
			/*Now figure out if the item is a kit set - the field MBFlag='K'*/
			$sql = "SELECT stockmaster.mbflag
							FROM stockmaster
							WHERE stockmaster.stockid='". $NewItem ."'";

			$ErrMsg = _('Could not determine if the part being ordered was a kitset or not because');
			$DbgMsg = _('The sql that was used to determine if the part being ordered was a kitset or not was ');
			$KitResult = DB_query($sql, $db,$ErrMsg,$DbgMsg);


			if (DB_num_rows($KitResult)==0){
				prnMsg( _('The item code') . ' ' . $NewItem . ' ' . _('could not be retrieved from the database and has not been added to the order'),'warn');
			} elseif ($myrow=DB_fetch_array($KitResult)){
				if ($myrow['mbflag']=='K'){	/*It is a kit set item */
					$sql = "SELECT bom.component,
							bom.quantity
							FROM bom
							WHERE bom.parent='" . $NewItem . "'
							AND bom.effectiveto > '" . Date('Y-m-d') . "'
							AND bom.effectiveafter < '" . Date('Y-m-d') . "'";

					$ErrMsg =  _('Could not retrieve kitset components from the database because') . ' ';
					$KitResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

					$ParentQty = $NewItemQty;
					while ($KitParts = DB_fetch_array($KitResult,$db)){
						$NewItem = $KitParts['component'];
						$NewItemQty = $KitParts['quantity'] * $ParentQty;
						$NewPOLine = 0;
						include('includes/SelectOrderItems_IntoCart.inc');
					}

				} elseif ($myrow['mbflag']=='G'){
					prnMsg(_('Phantom assemblies cannot be sold, these items exist only as bills of materials used in other manufactured items. The following item has not been added to the order:') . ' ' . $NewItem, 'warn');
				} else { /*Its not a kit set item*/
					include('includes/SelectOrderItems_IntoCart.inc');
				}
			}
		 }
		 unset($NewItem);
	 } /* end of if quick entry */

	if (isset($_POST['AssetDisposalEntered'])){ //its an asset being disposed of
		if ($_POST['AssetToDisposeOf'] == 'NoAssetSelected'){ //don't do anything unless an asset is disposed of
			prnMsg(_('No asset was selected to dispose of. No assets have been added to this customer order'),'warn');
		} else { //need to add the asset to the order
			/*First need to create a stock ID to hold the asset and record the sale - as only stock items can be sold 
			 * 		and before that we need to add a disposal stock category - if not already created 
			 * 		first off get the details about the asset being disposed of */
			 $AssetDetailsResult = DB_query("SELECT  fixedassets.description,
												fixedassets.longdescription,
												fixedassets.barcode,
												fixedassetcategories.costact,
												fixedassets.cost-fixedassets.accumdepn AS nbv
										FROM fixedassetcategories INNER JOIN fixedassets
										ON fixedassetcategories.categoryid=fixedassets.assetcategoryid
										WHERE fixedassets.assetid='" . $_POST['AssetToDisposeOf'] . "'",$db);
			$AssetRow = DB_fetch_array($AssetDetailsResult);
			
			/* Check that the stock category for disposal "ASSETS" is defined already */
			$AssetCategoryResult = DB_query("SELECT categoryid FROM stockcategory WHERE categoryid='ASSETS'",$db);
			if (DB_num_rows($AssetCategoryResult)==0){
				/*Although asset GL posting will come from the asset category - we should set the GL codes to something sensible 
				 * based on the category of the asset under review at the moment - this may well change for any other assets sold subsequentely */
				 
				/*OK now we can insert the stock category for this asset */
				$InsertAssetStockCatResult = DB_query("INSERT INTO stockcategory ( categoryid,
																				categorydescription,
																				stockact)
														VALUES ('ASSETS',
																'" . _('Asset Disposals') . "',
																'" . $AssetRow['costact'] . "')",$db);
			} 
			
			/*First check to see that it doesn't exist already assets are of the format "ASSET-" . $AssetID
			 */
			 $TestAssetExistsAlreadyResult = DB_query("SELECT stockid 
														FROM stockmaster 
														WHERE stockid ='ASSET-" . $_POST['AssetToDisposeOf']  . "'",
														$db);
			 $j=0;
			while (DB_num_rows($TestAssetExistsAlreadyResult)==1) { //then it exists already ... bum
				$j++;
				$TestAssetExistsAlreadyResult = DB_query("SELECT stockid 
														FROM stockmaster 
														WHERE stockid ='ASSET-" . $_POST['AssetToDisposeOf']  . '-' . $j . "'",
														$db);
			}
			if ($j>0){
				$AssetStockID = 'ASSET-' . $_POST['AssetToDisposeOf']  . '-' . $j;
			} else {
				$AssetStockID = 'ASSET-' . $_POST['AssetToDisposeOf'];
			}
			if ($AssetRow['nbv']==0){
				$NBV = 0.001; /* stock must have a cost to be invoiced if the flag is set so set to 0.001 */
			} else {
				$NBV = $AssetRow['nbv'];
			}
			/*OK now we can insert the item for this asset */
			$InsertAssetAsStockItemResult = DB_query("INSERT INTO stockmaster ( stockid,
																				description,
																				longdescription,
																				categoryid,
																				mbflag,
																				controlled,
																				serialised,
																				taxcatid,
																				materialcost)
										VALUES ('" . $AssetStockID . "',
														'" . $AssetRow['description'] . "',
														'" . $AssetRow['longdescription'] . "',
														'ASSETS',
														'D',
														'0',
														'0',
														'" . $_SESSION['DefaultTaxCategory'] . "',
														'". $NBV . "')" , $db);
			/*not forgetting the location records too */
			$InsertStkLocRecsResult = DB_query("INSERT INTO locstock (loccode,
																	stockid)
											SELECT loccode, '" . $AssetStockID . "' 
											FROM locations",$db);
			/*Now the asset has been added to the stock master we can add it to the sales order */
			$NewItemDue = date($_SESSION['DefaultDateFormat']);
			if (isset($_POST['POLine'])){
				$NewPOLine = $_POST['POLine'];
			} else {
				$NewPOLine = 0;
			}
			$NewItem = $AssetStockID;
			include('includes/SelectOrderItems_IntoCart.inc');
		} //end if adding a fixed asset to the order
	} //end if the fixed asset selection box was set

	 /*Now do non-quick entry delete/edits/adds */

	if ((isset($_SESSION['Items'.$identifier])) OR isset($NewItem)){

		if(isset($_GET['Delete'])){
			//page called attempting to delete a line - GET['Delete'] = the line number to delete
			$QuantityAlreadyDelivered = $_SESSION['Items'.$identifier]->Some_Already_Delivered($_GET['Delete']);
			if($QuantityAlreadyDelivered == 0){
				$_SESSION['Items'.$identifier]->remove_from_cart($_GET['Delete'], 'Yes');  /*Do update DB */
			} else {
				$_SESSION['Items'.$identifier]->LineItems[$_GET['Delete']]->Quantity = $QuantityAlreadyDelivered;
			}
		}
                      
		foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine) {
                                                                     
			if (isset($_POST['Quantity_' . $OrderLine->LineNumber])){
                                                                       
			    $Quantity = $_POST['Quantity_' . $OrderLine->LineNumber];

				if (ABS($OrderLine->Price - $_POST['Price_' . $OrderLine->LineNumber])>0.01){
					$Price = $_POST['Price_' . $OrderLine->LineNumber];
					$_POST['GPPercent_' . $OrderLine->LineNumber] = (($Price*(1-($_POST['Discount_' . $OrderLine->LineNumber]/100))) - $OrderLine->StandardCost*$ExRate)/($Price *(1-$_POST['Discount_' . $OrderLine->LineNumber])/100);
				} elseif (ABS($OrderLine->GPPercent - $_POST['GPPercent_' . $OrderLine->LineNumber])>=0.001) {
					//then do a recalculation of the price at this new GP Percentage
					$Price = ($OrderLine->StandardCost*$ExRate)/(1 -(($_POST['GPPercent_' . $OrderLine->LineNumber] + $_POST['Discount_' . $OrderLine->LineNumber])/100));
				} else {
					$Price = $_POST['Price_' . $OrderLine->LineNumber];
				}
				$DiscountPercentage = $_POST['Discount_' . $OrderLine->LineNumber];
				if ($_SESSION['AllowOrderLineItemNarrative'] == 1) {
					$Narrative = $_POST['Narrative_' . $OrderLine->LineNumber];
				} else {
					$Narrative = '';
				}

				if (!isset($OrderLine->DiscountPercent)) {
					$OrderLine->DiscountPercent = 0;
				}

				if(!Is_Date($_POST['ItemDue_' . $OrderLine->LineNumber])) {
					prnMsg(_('An invalid date entry was made for ') . ' ' . $NewItem . ' ' . _('The date entry') . ' ' . $ItemDue . ' ' . _('must be in the format') . ' ' . $_SESSION['DefaultDateFormat'],'warn');
					//Attempt to default the due date to something sensible?
					$_POST['ItemDue_' . $OrderLine->LineNumber] = DateAdd (Date($_SESSION['DefaultDateFormat']),'d', $_SESSION['Items'.$identifier]->DeliveryDays);
				}
				if ($Quantity<0 OR $Price <0 OR $DiscountPercentage >100 OR $DiscountPercentage <0){
					prnMsg(_('The item could not be updated because you are attempting to set the quantity ordered to less than 0 or the price less than 0 or the discount more than 100% or less than 0%'),'warn');
				} elseif($_SESSION['Items'.$identifier]->Some_Already_Delivered($OrderLine->LineNumber)!=0 AND $_SESSION['Items'.$identifier]->LineItems[$OrderLine->LineNumber]->Price != $Price) {
					prnMsg(_('The item you attempting to modify the price for has already had some quantity invoiced at the old price the items unit price cannot be modified retrospectively'),'warn');
				} elseif($_SESSION['Items'.$identifier]->Some_Already_Delivered($OrderLine->LineNumber)!=0 AND $_SESSION['Items'.$identifier]->LineItems[$OrderLine->LineNumber]->DiscountPercent != ($DiscountPercentage/100)) {

					prnMsg(_('The item you attempting to modify has had some quantity invoiced at the old discount percent the items discount cannot be modified retrospectively'),'warn');

				} elseif ($_SESSION['Items'.$identifier]->LineItems[$OrderLine->LineNumber]->QtyInv > $Quantity){
					prnMsg( _('You are attempting to make the quantity ordered a quantity less than has already been invoiced') . '. ' . _('The quantity delivered and invoiced cannot be modified retrospectively'),'warn');
				} elseif ($OrderLine->Quantity !=$Quantity
							OR $OrderLine->Price != $Price
							OR ABS($OrderLine->DiscountPercent -$DiscountPercentage/100) >0.001
							OR $OrderLine->Narrative != $Narrative
							OR $OrderLine->ItemDue != $_POST['ItemDue_' . $OrderLine->LineNumber]
							OR $OrderLine->POLine != $_POST['POLine_' . $OrderLine->LineNumber]) {

					$_SESSION['Items'.$identifier]->update_cart_item($OrderLine->LineNumber,
										$Quantity,
										$Price,
										($DiscountPercentage/100),
										$Narrative,
										'Yes', /*Update DB */
										$_POST['ItemDue_' . $OrderLine->LineNumber],
										$_POST['POLine_' . $OrderLine->LineNumber],
										$_POST['GPPercent_' . $OrderLine->LineNumber]);
				}
			} //page not called from itself - POST variables not set
		}
	}
	if (isset($_POST['DeliveryDetails'])){
		echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath . '/bio_amc_DeliveryDetails.php?' . SID .'identifier='.$identifier . '">';
		prnMsg(_('You should automatically be forwarded to the entry of the delivery details page') . '. ' . _('if this does not happen') . ' (' . _('if the browser does not support META Refresh') . ') ' .
		   '<a href="' . $rootpath . '/DeliveryDetails.php?' . SID .'identifier='.$identifier . '">' . _('click here') . '</a> ' . _('to continue'), 'info');
	   	exit;
	}


	if (isset($NewItem)){
/* get the item details from the database and hold them in the cart object make the quantity 1 by default then add it to the cart */
/*Now figure out if the item is a kit set - the field MBFlag='K'*/
		$sql = "SELECT stockmaster.mbflag
		   		FROM stockmaster
				WHERE stockmaster.stockid='". $NewItem ."'";

		$ErrMsg =  _('Could not determine if the part being ordered was a kitset or not because');

		$KitResult = DB_query($sql, $db,$ErrMsg);

		$NewItemQty = 1; /*By Default */
		$Discount = 0; /*By default - can change later or discount category override */

		if ($myrow=DB_fetch_array($KitResult)){
		   	if ($myrow['mbflag']=='K'){	/*It is a kit set item */
				$sql = "SELECT bom.component,
							bom.quantity
						FROM bom
						WHERE bom.parent='" . $NewItem . "'
						AND bom.effectiveto > '" . Date('Y-m-d') . "'
						AND bom.effectiveafter < '" . Date('Y-m-d') . "'";

				$ErrMsg = _('Could not retrieve kitset components from the database because');
				$KitResult = DB_query($sql,$db,$ErrMsg);

				$ParentQty = $NewItemQty;
				while ($KitParts = DB_fetch_array($KitResult,$db)){
					$NewItem = $KitParts['component'];
					$NewItemQty = $KitParts['quantity'] * $ParentQty;
					$NewPOLine = 0;
					$NewItemDue = date($_SESSION['DefaultDateFormat']);
					include('includes/SelectOrderItems_IntoCart.inc');
				}

			} else { /*Its not a kit set item*/
				$NewItemDue = date($_SESSION['DefaultDateFormat']);
				$NewPOLine = 0;

				include('includes/SelectOrderItems_IntoCart.inc');
			}

		} /* end of if its a new item */

	} /*end of if its a new item */

	if (isset($NewItem_array) AND isset($_POST['order_items'])){
/* get the item details from the database and hold them in the cart object make the quantity 1 by default then add it to the cart */
/*Now figure out if the item is a kit set - the field MBFlag='K'*/
		foreach($NewItem_array as $NewItem => $NewItemQty) {
				if($NewItemQty > 0)	{
					$sql = "SELECT stockmaster.mbflag
									FROM stockmaster
									WHERE stockmaster.stockid='". $NewItem ."'";

					$ErrMsg =  _('Could not determine if the part being ordered was a kitset or not because');

					$KitResult = DB_query($sql, $db,$ErrMsg);

					//$NewItemQty = 1; /*By Default */
					$Discount = 0; /*By default - can change later or discount category override */

					if ($myrow=DB_fetch_array($KitResult)){
						if ($myrow['mbflag']=='K'){	/*It is a kit set item */
							$sql = "SELECT bom.component,
														bom.quantity
											FROM bom
											WHERE bom.parent='" . $NewItem . "'
											AND bom.effectiveto > '" . Date('Y-m-d') . "'
											AND bom.effectiveafter < '" . Date('Y-m-d') . "'";

							$ErrMsg = _('Could not retrieve kitset components from the database because');
							$KitResult = DB_query($sql,$db,$ErrMsg);

							$ParentQty = $NewItemQty;
							while ($KitParts = DB_fetch_array($KitResult,$db)){
								$NewItem = $KitParts['component'];
								$NewItemQty = $KitParts['quantity'] * $ParentQty;
								$NewItemDue = date($_SESSION['DefaultDateFormat']);
								$NewPOLine = 0;
								include('includes/SelectOrderItems_IntoCart.inc');
							}

						} else { /*Its not a kit set item*/
							$NewItemDue = date($_SESSION['DefaultDateFormat']);
							$NewPOLine = 0;
							include('includes/SelectOrderItems_IntoCart.inc');
						}

					} /* end of if its a new item */

				} /*end of if its a new item */

		}

	}

	/* Run through each line of the order and work out the appropriate discount from the discount matrix */
	$DiscCatsDone = array();
	$counter =0;
	foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine) {

		if ($OrderLine->DiscCat !="" AND ! in_array($OrderLine->DiscCat,$DiscCatsDone)){
			$DiscCatsDone[$counter]=$OrderLine->DiscCat;
			$QuantityOfDiscCat =0;

			foreach ($_SESSION['Items'.$identifier]->LineItems as $StkItems_2) {
				/* add up total quantity of all lines of this DiscCat */
				if ($StkItems_2->DiscCat==$OrderLine->DiscCat){
					$QuantityOfDiscCat += $StkItems_2->Quantity;
				}
			}
			$result = DB_query("SELECT MAX(discountrate) AS discount
								FROM discountmatrix
								WHERE salestype='" .  $_SESSION['Items'.$identifier]->DefaultSalesType . "'
								AND discountcategory ='" . $OrderLine->DiscCat . "'
								AND quantitybreak <" . $QuantityOfDiscCat,$db);
			$myrow = DB_fetch_row($result);
			if ($myrow[0]!=0){ /* need to update the lines affected */
				foreach ($_SESSION['Items'.$identifier]->LineItems as $StkItems_2) {
					/* add up total quantity of all lines of this DiscCat */
					if ($StkItems_2->DiscCat==$OrderLine->DiscCat AND $StkItems_2->DiscountPercent == 0){
						$_SESSION['Items'.$identifier]->LineItems[$StkItems_2->LineNumber]->DiscountPercent = $myrow[0];
					}
				}
			}
		}
	} /* end of discount matrix lookup code */

	if (count($_SESSION['Items'.$identifier]->LineItems)>0){ /*only show order lines if there are any */

/* This is where the order as selected should be displayed  reflecting any deletions or insertions*/

		echo '<br />
				<table width="90%" cellpadding="2" colspan="7">
				<tr bgcolor=#800000>';
		if($_SESSION['Items'.$identifier]->DefaultPOLine == 1){
			echo '<th>' . _('PO Line') . '</th>';
		}
		echo '<div class="page_help_text">' . _('Quantity (required) - Enter the number of units ordered.  Price (required) - Enter the unit price.  Discount (optional) - Enter a percentage discount.  GP% (optional) - Enter a percentage Gross Profit (GP) to add to the unit cost.  Due Date (optional) - Enter a date for delivery.') . '</div><br />';
		echo '<th>' . _('Item Code') . '</th>
				<th>' . _('Item Description') . '</th>
				<th>' . _('Quantity') . '</th>
				<th>' . _('QOH') . '</th>
				<th>' . _('Unit') . '</th>
				<th>' . _('Price') . '</th>';

		if (in_array(2,$_SESSION['AllowedPageSecurityTokens'])){
			echo '<th>' . _('Discount') . '</th>
						<th>' . _('GP %') . '</th>';
		}
		echo '<th>' . _('Total') . '</th>
			<th>' . _('Due Date') . '</th></tr>';

		$_SESSION['Items'.$identifier]->total = 0;
		$_SESSION['Items'.$identifier]->totalVolume = 0;
		$_SESSION['Items'.$identifier]->totalWeight = 0;
		$k =0;  //row colour counter
		foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine) {
            

            $result_subsidy=DB_query("SELECT SUM(subsidy_amount) AS sum FROM bio_subsidymaster WHERE subsidy_plant_id ='$OrderLine->StockID'",$db);
            $row_subsidy=DB_fetch_array($result_subsidy);
            
            $subsidized_price=$OrderLine->Price-$row_subsidy[0];

                                               //$OrderLine->Price
			$LineTotal = $OrderLine->Quantity * $subsidized_price * (1 - $OrderLine->DiscountPercent);
			$DisplayLineTotal = number_format($LineTotal,$_SESSION['Items'.$identifier]->CurrDecimalPlaces);
			$DisplayDiscount = number_format(($OrderLine->DiscountPercent * 100),2);
			$QtyOrdered = $OrderLine->Quantity;
			$QtyRemain = $QtyOrdered - $OrderLine->QtyInv;

			if ($OrderLine->QOHatLoc < $OrderLine->Quantity AND ($OrderLine->MBflag=='B' OR $OrderLine->MBflag=='M')) {
				/*There is a stock deficiency in the stock location selected */
				$RowStarter = '<tr bgcolor="#EEAABB">'; //rows show red where stock deficiency
			} elseif ($k==1){
				$RowStarter = '<tr class="OddTableRows">';
				$k=0;
			} else {
				$RowStarter = '<tr class="EvenTableRows">';
				$k=1;
			}

			echo $RowStarter;
			if($_SESSION['Items'.$identifier]->DefaultPOLine ==1){ //show the input field only if required
				echo '<td><input tabindex=1 type=text name="POLine_' . $OrderLine->LineNumber . '" size=20 maxlength=20 value=' . $OrderLine->POLine . '></td>';
			} else {
				echo '<input type="hidden" name="POLine_' .	 $OrderLine->LineNumber . '" value="">';
			}

			echo '<td><a target="_blank" href="' . $rootpath . '/StockStatus.php?identifier='.$identifier . '&StockID=' . $OrderLine->StockID . '&DebtorNo=' . $_SESSION['Items'.$identifier]->DebtorNo . '">' . $OrderLine->StockID . '</a></td>
				<td>' . $OrderLine->ItemDescription . '</td>';

			echo '<td><input class="number" tabindex=2 type=tect name="Quantity_' . $OrderLine->LineNumber . '" size=6 maxlength=6 value=' . $OrderLine->Quantity . '>';
			if ($QtyRemain != $QtyOrdered){
				echo '<br />'.$OrderLine->QtyInv.' of '.$OrderLine->Quantity.' invoiced';
			}
			echo '</td>
					<td class="number">' . $OrderLine->QOHatLoc . '</td>
					<td>' . $OrderLine->Units . '</td>';

			if (in_array(2,$_SESSION['AllowedPageSecurityTokens'])){
				/*OK to display with discount if it is an internal user with appropriate permissions */
				echo '<td><input class="number" type=text name="Price_' . $OrderLine->LineNumber . '" size=16 maxlength=16 value=' . $OrderLine->Price . '></td>
					<td><input class="number" type=text name="Discount_' . $OrderLine->LineNumber . '" size=5 maxlength=4 value=' . ($OrderLine->DiscountPercent * 100) . '></td>
					<td><input class="number" type=text name="GPPercent_' . $OrderLine->LineNumber . '" size=3 maxlength=40 value=' . $OrderLine->GPPercent . '></td>';
			} else {
				echo '<td class=number>' . $OrderLine->Price . '</td><td></td>';
				echo '<input type=hidden name="Price_' . $OrderLine->LineNumber . '" value=' . $OrderLine->Price . '>';
			}
			if ($_SESSION['Items'.$identifier]->Some_Already_Delivered($OrderLine->LineNumber)){
				$RemTxt = _('Clear Remaining');
			} else {
				$RemTxt = _('Delete');
			}
			echo '</td><td class=number>' . $DisplayLineTotal . '</td>';
			$LineDueDate = $OrderLine->ItemDue;
			if (!Is_Date($OrderLine->ItemDue)){
				$LineDueDate = DateAdd (Date($_SESSION['DefaultDateFormat']),'d', $_SESSION['Items'.$identifier]->DeliveryDays);
				$_SESSION['Items'.$identifier]->LineItems[$OrderLine->LineNumber]->ItemDue= $LineDueDate;
			}

			echo '<td><input type=text class="date" alt="'.$_SESSION['DefaultDateFormat'].'" name="ItemDue_' . $OrderLine->LineNumber . '" size=10 maxlength=10 value=' . $LineDueDate . '></td>';

			echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?identifier=' . $identifier . '&Delete=' . $OrderLine->LineNumber . '" onclick="return confirm(\'' . _('Are You Sure?') . '\');">' . $RemTxt . '</a></td></tr>';

			if ($_SESSION['AllowOrderLineItemNarrative'] == 1){
				echo $RowStarter;
				echo '<td colspan=10>' . _('Narrative') . ':<textarea name="Narrative_' . $OrderLine->LineNumber . '" cols="100%" rows="1">' . stripslashes(AddCarriageReturns($OrderLine->Narrative)) . '</textarea><br /></td></tr>';
			} else {
				echo '<input type=hidden name="Narrative" value="">';
			}

			$_SESSION['Items'.$identifier]->total = $_SESSION['Items'.$identifier]->total + $LineTotal;
			$_SESSION['Items'.$identifier]->totalVolume = $_SESSION['Items'.$identifier]->totalVolume + $OrderLine->Quantity * $OrderLine->Volume;
			$_SESSION['Items'.$identifier]->totalWeight = $_SESSION['Items'.$identifier]->totalWeight + $OrderLine->Quantity * $OrderLine->Weight;

		} /* end of loop around items */

		$DisplayTotal = number_format($_SESSION['Items'.$identifier]->total,$_SESSION['Items'.$identifier]->CurrDecimalPlaces);
		if (in_array(2,$_SESSION['AllowedPageSecurityTokens'])){
			$ColSpanNumber = 3;
		} else {
			$ColSpanNumber = 1;
		}
		echo '<tr class="EvenTableRows"><td class="number" colspan=7><b>' . _('TOTAL Excl Tax/Freight') . '</b></td>
							<td colspan="' . $ColSpanNumber . '" class=number>' . $DisplayTotal . '</td></tr></table>';

		$DisplayVolume = number_format($_SESSION['Items'.$identifier]->totalVolume,2);
		$DisplayWeight = number_format($_SESSION['Items'.$identifier]->totalWeight,2);
		echo '<table><tr class="EvenTableRows"><td>' . _('Total Weight') . ':</td>
						 <td>' . $DisplayWeight . '</td>
						 <td>' . _('Total Volume') . ':</td>
						 <td>' . $DisplayVolume . '</td>
					   </tr></table>';


		echo '<br /><div class="centre"><input type=submit name="Recalculate" Value="' . _('Re-Calculate') . '">
				<input type=submit name="DeliveryDetails" value="' . _('Enter Delivery Details and Confirm Order') . '"></div><hr>';
	} # end of if lines

/* Now show the stock item selection search stuff below */

	 if ((!isset($_POST['QuickEntry'])  
			AND !isset($_POST['SelectAsset']))){

		echo '<input type="hidden" name="PartSearch" value="' .  _('Yes Please') . '">';

		if ($_SESSION['FrequentlyOrderedItems']>0){ //show the Frequently Order Items selection where configured to do so

// Select the most recently ordered items for quick select
			$SixMonthsAgo = DateAdd (Date($_SESSION['DefaultDateFormat']),'m',-6);

			$SQL="SELECT stockmaster.units, 
						stockmaster.description, 
						stockmaster.stockid, 
						salesorderdetails.stkcode,
						SUM(qtyinvoiced) salesqty 
					FROM `salesorderdetails`INNER JOIN `stockmaster`
					ON  salesorderdetails.stkcode = stockmaster.stockid
					WHERE ActualDispatchDate >= '" . FormatDateForSQL($SixMonthsAgo) . "'
					GROUP BY stkcode
					ORDER BY salesqty DESC
					LIMIT " . $_SESSION['FrequentlyOrderedItems'];
				  
			$result2 = DB_query($SQL,$db);
			echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ';
			echo _('Frequently Ordered Items') . '</p><br />';
			echo '<div class="page_help_text">' . _('Frequently Ordered Items') . _(', shows the most frequently ordered items in the last 6 months.  You can choose from this list, or search further for other items') . '.</div><br />';
			echo '<table class="table1">';
			$TableHeader = '<tr><th>' . _('Code') . '</th>
								<th>' . _('Description') . '</th>
								<th>' . _('Units') . '</th>
								<th>' . _('On Hand') . '</th>
								<th>' . _('On Demand') . '</th>
								<th>' . _('On Order') . '</th>
								<th>' . _('Available') . '</th>
								<th>' . _('Quantity') . '</th></tr>';
			echo $TableHeader;
			$j = 1;
			$k=0; //row colour counter

			while ($myrow=DB_fetch_array($result2)) {
// This code needs sorting out, but until then :
				$ImageSource = _('No Image');
// Find the quantity in stock at location
				$QOHSQL = "SELECT sum(locstock.quantity) AS qoh,
									stockmaster.decimalplaces
							   FROM locstock INNER JOIN stockmaster
							   ON locstock.stockid=stockmaster.stockid
							   WHERE locstock.stockid='" .$myrow['stockid'] . "' AND
								loccode = '" . $_SESSION['Items'.$identifier]->Location . "'";
				$QOHResult =  DB_query($QOHSQL,$db);
				$QOHRow = DB_fetch_array($QOHResult);
				$QOH = $QOHRow['qoh'];

				// Find the quantity on outstanding sales orders
				$sql = "SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
							FROM salesorderdetails,
								 salesorders
							WHERE salesorders.orderno = salesorderdetails.orderno 
							AND salesorders.fromstkloc='" . $_SESSION['Items'.$identifier]->Location . "' 
							AND salesorderdetails.completed=0 
							AND salesorders.quotation=0 
							AND salesorderdetails.stkcode='" . $myrow['stockid'] . "'";

				$ErrMsg = _('The demand for this product from') . ' ' . $_SESSION['Items'.$identifier]->Location . ' ' .
					 _('cannot be retrieved because');
				$DemandResult = DB_query($sql,$db,$ErrMsg);

				$DemandRow = DB_fetch_row($DemandResult);
				if ($DemandRow[0] != null){
				  $DemandQty =  $DemandRow[0];
				} else {
				  $DemandQty = 0;
				}
				// Find the quantity on purchase orders
				$sql = "SELECT SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qoo
						FROM purchorderdetails INNER JOIN purchorders
						WHERE purchorderdetails.completed=0
						AND purchorders.status<> 'Completed'
						AND purchorders.status<> 'Rejected'
						AND purchorderdetails.itemcode='" . $myrow['stockid'] . "'";

				$ErrMsg = _('The order details for this product cannot be retrieved because');
				$PurchResult = db_query($sql,$db,$ErrMsg);

				$PurchRow = db_fetch_row($PurchResult);
				if ($PurchRow[0]!=null){
				  $PurchQty =  $PurchRow[0];
				} else {
				  $PurchQty = 0;
				}

				// Find the quantity on works orders
				$sql = "SELECT SUM(woitems.qtyreqd - woitems.qtyrecd) AS qwo
						   FROM woitems
						   WHERE stockid='" . $myrow['stockid'] ."'";
				$ErrMsg = _('The order details for this product cannot be retrieved because');
				$WoResult = db_query($sql,$db,$ErrMsg);
				$WoRow = db_fetch_row($WoResult);
				if ($WoRow[0]!=null){
					$WoQty =  $WoRow[0];
				} else {
					$WoQty = 0;
				}

				if ($k==1){
						echo '<tr class="EvenTableRows">';
						$k=0;
				} else {
						echo '<tr class="OddTableRows">';
						$k=1;
				}
				$OnOrder = $PurchQty + $WoQty;

				$Available = $QOH - $DemandQty + $OnOrder;

				printf('<td>%s</font></td>
						<td>%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td><font size=1><input class="number"  tabindex='.number_format($j+7).' type="textbox" size=6 name="itm'.$myrow['stockid'].'" value=0>
						</td>
						</tr>',
						$myrow['stockid'],
						$myrow['description'],
						$myrow['units'],
						number_format($QOH, $QOHRow['decimalplaces']),
						number_format($DemandQty, $QOHRow['decimalplaces']),
						number_format($OnOrder, $QOHRow['decimalplaces']),
						number_format($Available, $QOHRow['decimalplaces']) );
				if ($j==1) {
					$jsCall = '<script  type="text/javascript">if (document.SelectParts) {defaultControl(document.SelectParts.itm'.$myrow['stockid'].');}</script>';
				}
				$j++;
#end of page full new headings if
			}
#end of while loop for Frequently Ordered Items
			echo '<td style="text-align:center" colspan=8><input type="hidden" name="order_items" value=1><input tabindex='.number_format($j+8).' type="submit" value="'._('Add to Sales Order').'"></td>';
			echo '</table>';
		} //end of if Frequently Ordered Items > 0
		echo '<p><div class="centre"><b><p>' . $msg . '</b></p>';
		echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ';
		echo _('Search for Order Items') . '</p>';
		echo '<div class="page_help_text">' . _('Search for Order Items') . _(', Searches the database for items, you can narrow the results by selecting a stock category, or just enter a partial item description or partial item code') . '.</div><br />';
		echo '<table class="selection"><tr><td><b>' . _('Select a Stock Category') . ': </b><select tabindex=1 name="StockCat">';
            $_POST['StockCat']="AMC";
		if (!isset($_POST['StockCat'])){
			/*echo '<option selected value="All">' . _('All'); */
			$_POST['StockCat'] ='All';
		} else {
/*			echo '<option value="All">' . _('All');*/
		}
		$SQL="SELECT categoryid,
						categorydescription
				FROM stockcategory
				WHERE 
                 categoryid='".$_POST['StockCat']."'
				ORDER BY categorydescription";
						
		$result1 = DB_query($SQL,$db);
		while ($myrow1 = DB_fetch_array($result1)) {
			if ($_POST['StockCat']==$myrow1['categoryid']){
				echo '<option selected value=' . $myrow1['categoryid'] . '>' . $myrow1['categorydescription'] . '</option>';
			} else {
				echo '<option value='. $myrow1['categoryid'] . '>' . $myrow1['categorydescription'] . '</option>';
			}
		}

		echo '</select></td>
			<td><b>' . _('Enter partial Description') . ':</b><input tabindex=2 type="Text" name="Keywords" size=20 maxlength=25 value="' ;

		if (isset($_POST['Keywords'])) {
			 echo$_POST['Keywords'] ;
		}
		echo '"></td>';

		echo '<td align="right"><b>' . _('OR') .  ' ' . _('Enter extract of the Stock Code') . ':</b><input tabindex=3 type="Text" name="StockCode" size=15 maxlength=18 value="';
		if (isset($_POST['StockCode'])) {
			echo  $_POST['StockCode'];
		}
		echo '"></td></tr>';
		
		echo '<tr>
			<td style="text-align:center" colspan=1><input tabindex=4 type=submit name="Search" value="' . _('Search Now') . '"></td>
			<td style="text-align:center" colspan=1><input tabindex=5 type=submit name="QuickEntry" value="' .  _('Use Quick Entry') . '"></td>';
		
		if (!isset($_POST['PartSearch'])) {
			echo '<script  type="text/javascript">if (document.SelectParts) {defaultControl(document.SelectParts.Keywords);}</script>';
		}
		if (in_array(2,$_SESSION['AllowedPageSecurityTokens'])){ //not a customer entry of own order
			/*echo '<td style="text-align:center" colspan=1><input tabindex=6 type="submit" name="ChangeCustomer" value="' . _('Change Customer') . '"></td>    */
			/*<td style="text-align:center" colspan=1><input tabindex=7 type="submit" name="SelectAsset" value="' . _('Fixed Asset Disposal') . '"></td>*/
			echo'</tr></table><br />';
		}

		if (isset($SearchResult)) {
			echo '<br />';
			echo '<div class="page_help_text">' . _('Select an item by entering the quantity required.  Click Order when ready.') . '</div>';
			echo '<br />';
			$j = 1;
			echo '<form action="' . $_SERVER['PHP_SELF'] . '?identifier='.$identifier . '" method=post name="orderform">';
			echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
			echo '<table class="table1">';
			echo '<tr><td colspan=><input type="hidden" name="previous" value='.number_format($Offset-1).'><input tabindex='.number_format($j+8).' type="submit" name="Prev" value="'._('Prev').'"></td>';
			echo '<td style="text-align:center" colspan=6><input type="hidden" name="order_items" value=1><input tabindex='.number_format($j+9).' type="submit" value="'._('Add to Sales Order').'"></td>';
			echo '<td colspan=><input type="hidden" name="nextlist" value='.number_format($Offset+1).'><input tabindex='.number_format($j+10).' type="submit" name="Next" value="'._('Next').'"></td></tr>';
			$TableHeader = '<tr><th>' . _('Code') . '</th>
					   			<th>' . _('Description') . '</th>
					   			<th>' . _('Units') . '</th>
					   			<th>' . _('On Hand') . '</th>
					   			<th>' . _('On Demand') . '</th>
					   			<th>' . _('On Order') . '</th>
					   			<th>' . _('Available') . '</th>
					   			<th>' . _('Quantity') . '</th></tr>';
			echo $TableHeader;
			$ImageSource = _('No Image');
			
			$k=0; //row colour counter

			while ($myrow=DB_fetch_array($SearchResult)) {

				// Find the quantity in stock at location
				$QOHSQL = "SELECT quantity AS qoh,
									stockmaster.decimalplaces
							   FROM locstock INNER JOIN stockmaster
							   ON locstock.stockid = stockmaster.stockid
							   WHERE locstock.stockid='" .$myrow['stockid'] . "' AND
							   loccode = '" . $_SESSION['Items'.$identifier]->Location . "'";
				$QOHResult =  DB_query($QOHSQL,$db);
				$QOHRow = DB_fetch_array($QOHResult);
				$QOH = $QOHRow['qoh'];

				// Find the quantity on outstanding sales orders
				$sql = "SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
						FROM salesorderdetails INNER JOIN salesorders 
						ON salesorders.orderno = salesorderdetails.orderno 
						 WHERE  salesorders.fromstkloc='" . $_SESSION['Items'.$identifier]->Location . "' 
						 AND salesorderdetails.completed=0 
						 AND salesorders.quotation=0 
						 AND salesorderdetails.stkcode='" . $myrow['stockid'] . "'";

				$ErrMsg = _('The demand for this product from') . ' ' . $_SESSION['Items'.$identifier]->Location . ' ' . _('cannot be retrieved because');
				$DemandResult = DB_query($sql,$db,$ErrMsg);

				$DemandRow = DB_fetch_row($DemandResult);
				if ($DemandRow[0] != null){
					$DemandQty =  $DemandRow[0];
				} else {
					$DemandQty = 0;
				}

				// Find the quantity on purchase orders
				$sql = "SELECT SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qoo
						 FROM purchorderdetails INNER JOIN purchorders 
						 ON purchorderdetails.orderno=purchorders.orderno
						 WHERE purchorderdetails.completed=0 
						 AND purchorders.status<>'Cancelled'
						 AND purchorders.status<>'Rejected'
						 AND purchorders.status<>'Pending'
						AND purchorderdetails.itemcode='" . $myrow['stockid'] . "'";

				$ErrMsg = _('The order details for this product cannot be retrieved because');
				$PurchResult = db_query($sql,$db,$ErrMsg);

				$PurchRow = db_fetch_row($PurchResult);
				if ($PurchRow[0]!=null){
					$PurchQty =  $PurchRow[0];
				} else {
					$PurchQty = 0;
				}

				// Find the quantity on works orders
				$sql = "SELECT SUM(woitems.qtyreqd - woitems.qtyrecd) AS dedm
					   FROM woitems
					   WHERE stockid='" . $myrow['stockid'] ."'";
				$ErrMsg = _('The order details for this product cannot be retrieved because');
				$WoResult = db_query($sql,$db,$ErrMsg);

				$WoRow = db_fetch_row($WoResult);
				if ($WoRow[0]!=null){
					$WoQty =  $WoRow[0];
				} else {
					$WoQty = 0;
				}

				if ($k==1){
					echo '<tr class="EvenTableRows">';
					$k=0;
				} else {
					echo '<tr class="OddTableRows">';
					$k=1;
				}
				$OnOrder = $PurchQty + $WoQty;
				$Available = $QOH - $DemandQty + $OnOrder;

				printf('<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td><font size=1><input class="number"  tabindex='.number_format($j+7).' type="textbox" size=6 name="itm'.$myrow['stockid'].'" value=0>
						</td>
						</tr>',
						$myrow['stockid'],
						$myrow['description'],
						$myrow['units'],
						number_format($QOH,$QOHRow['decimalplaces']),
						number_format($DemandQty,$QOHRow['decimalplaces']),
						number_format($OnOrder,$QOHRow['decimalplaces']),
						number_format($Available,$QOHRow['decimalplaces']) );
				if ($j==1) {
					$jsCall = '<script  type="text/javascript">if (document.SelectParts) {defaultControl(document.SelectParts.itm'.$myrow['stockid'].');}</script>';
				}
				$j++;
	#end of page full new headings if
			}
	#end of while loop
			echo '<tr><td><input type="hidden" name="previous" value='.number_format($Offset-1).'><input tabindex='.number_format($j+7).' type="submit" name="Prev" value="'._('Prev').'"></td>';
			echo '<td style="text-align:center" colspan=6><input type="hidden" name="order_items" value=1><input tabindex='.number_format($j+8).' type="submit" value="'._('Add to Sales Order').'"></td>';
			echo '<td><input type="hidden" name="nextlist" value='.number_format($Offset+1).'><input tabindex='.number_format($j+9).' type="submit" name="Next" value="'._('Next').'"></td></tr>';
			echo '</table></form>';
			echo $jsCall;

		}#end if SearchResults to show
	} /*end of PartSearch options to be displayed */
	   elseif( isset($_POST['QuickEntry'])) { /* show the quick entry form variable */
		  /*FORM VARIABLES TO POST TO THE ORDER  WITH PART CODE AND QUANTITY */
	   	echo '<div class="page_help_text"><b>' . _('Use this screen for the '). _('Quick Entry')._(' of products to be ordered') . '</b></div><br />
		 			<table border=1>
					<tr>';
			/*do not display colum unless customer requires po line number by sales order line*/
		 	if($_SESSION['Items'.$identifier]->DefaultPOLine ==1){
				echo	'<th>' . _('PO Line') . '</th>';
			}
			echo '<th>' . _('Part Code') . '</th>
				  <th>' . _('Quantity') . '</th>
				  <th>' . _('Due Date') . '</th>
				  </tr>';
			$DefaultDeliveryDate = DateAdd(Date($_SESSION['DefaultDateFormat']),'d',$_SESSION['Items'.$identifier]->DeliveryDays);
			for ($i=1;$i<=$_SESSION['QuickEntries'];$i++){

		 		echo '<tr class="OddTableRow">';
		 		/* Do not display colum unless customer requires po line number by sales order line*/
		 		if($_SESSION['Items'.$identifier]->DefaultPOLine > 0){
					echo '<td><input type="text" name="poline_' . $i . '" size=21 maxlength=20></td>';
				}
				echo '<td><input type="text" name="part_' . $i . '" size=21 maxlength=20></td>
						<td><input type="text" name="qty_' . $i . '" size=6 maxlength=6></td>
						<td><input type="text" class="date" name="itemdue_' . $i . '" size=25 maxlength=25
						alt="'.$_SESSION['DefaultDateFormat'].'" value="' . $DefaultDeliveryDate . '"></td></tr>';
	   		}
			echo '<script  type="text/javascript">if (document.SelectParts) {defaultControl(document.SelectParts.part_1);}</script>';

		 	echo '</table><br /><div class="centre"><input type="submit" name="QuickEntry" value="' . _('Quick Entry') . '">
					 <input type="submit" name="PartSearch" value="' . _('Search Parts') . '"></div>';

	  	} elseif (isset($_POST['SelectAsset'])){
		
			echo '<div class="page_help_text"><b>' . _('Use this screen to select an asset to dispose of to this customer') . '</b></div><br />
		 			<table border=1>';
			/*do not display colum unless customer requires po line number by sales order line*/
		 	if($_SESSION['Items'.$identifier]->DefaultPOLine ==1){
				echo	'<tr><td>' . _('PO Line') . '</td>	
							<td><input type="text" name="poline" size=21 maxlength=20></td></tr>';
			}
			echo '<tr><td>' . _('Asset to Dispose Of') . ':</td>
						<td><select name="AssetToDisposeOf">';
			$AssetsResult = DB_query("SELECT assetid, description FROM fixedassets WHERE disposaldate='0000-00-00'",$db);
			echo '<option selected value="NoAssetSelected">' . _('Select Asset To Dispose of From the List Below') . '</option>';
			while ($AssetRow = DB_fetch_array($AssetsResult)){
				echo '<option value="' . $AssetRow['assetid'] . '">' . $AssetRow['assetid'] . ' - ' . $AssetRow['description'] . '</option>';
			}
			echo '</select></td></tr></table>
						<br /><div class="centre"><input type="submit" name="AssetDisposalEntered" value="' . _('Add Asset To Order') . '">
					 <input type="submit" name="PartSearch" value="' . _('Search Parts') . '"></div>';
		
		} //end of if it is a Quick Entry screen/part search or asset selection form to display
		
		if ($_SESSION['Items'.$identifier]->ItemsOrdered >=1){
	  		echo '<br /><div class="centre"><input type=submit name="CancelOrder" value="' . _('Cancel Whole Order') . '" onclick="return confirm(\'' . _('Are you sure you wish to cancel this entire order?') . '\');"></div>';
		}
	}#end of else not selecting a customer

echo '</form>';

if (isset($_GET['NewOrder']) and $_GET['NewOrder']!='') {
	echo '<script  type="text/javascript">if (document.SelectParts) {defaultControl(document.SelectCustomer.CustKeywords);}</script>';
}
include('includes/footer.inc');
?>