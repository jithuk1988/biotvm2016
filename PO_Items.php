<?php

/* $Id PO_Items.php 4183 2010-12-14 09:30:20Z daintree $ */
//$PageSecurity = 80;
include('includes/DefinePOClass.php');
include('includes/SQL_CommonFunctions.inc');

/* Session started in header.inc for password checking
 * and authorisation level check
 */
include('includes/session.inc');
$title = _('Purchase Order Items');

$identifier=$_GET['identifier'];


if(isset($_GET['SupplierID']))
{
    $identifier=date('U');
   $_POST['supplierid']=$_GET['SupplierID']; 
   
  $_SESSION['PO'.$identifier]->SupplierID = $_POST['supplierid']; 
   $myrow['stockid']='PA-PW1';
  


        $sql7 = "SELECT suppliers.suppname,
                    suppliers.currcode,
                    currencies.rate,
                    currencies.decimalplaces,
                    suppliers.paymentterms,
                    suppliers.address1,
                    suppliers.address2,
                    suppliers.address3,
                    suppliers.address4,
                    suppliers.address5,
                    suppliers.address6,
                    suppliers.telephone,
                    suppliers.port
                FROM suppliers INNER JOIN currencies
                ON suppliers.currcode=currencies.currabrev
                WHERE supplierid='" . $_GET['SupplierID'] . "'";

        $LocnAddrResult = DB_query($sql7,$db);
        if (DB_num_rows($LocnAddrResult)==1){
            $LocnRow = DB_fetch_array($LocnAddrResult);
            
            
            
                $_POST['SupplierName'] = $LocnRow['suppname'];
        $_POST['CurrCode'] = $LocnRow['currcode'];
        $_POST['CurrDecimalPlaces'] = $LocnRow['decimalplaces'];
        $_POST['ExRate'] = $LocnRow['rate'];
        $_POST['PaymentTerms']=    $LocnRow['paymentterms'];
        $_POST['SuppTel'] = $LocnRow['telephone'];
        $_POST['Port'] = $LocnRow['port'];
        $_POST['SuppDelAdd1'] = $LocnRow['address1'];
        $_POST['SuppDelAdd2'] = $LocnRow['address2'];
        $_POST['SuppDelAdd3'] = $LocnRow['address3'];
        $_POST['SuppDelAdd4'] = $LocnRow['address4'];
        $_POST['SuppDelAdd5'] = $LocnRow['address5'];
        $_POST['SuppDelAdd6'] = $LocnRow['address6'];
        
        $_SESSION['RequireSupplierSelection'] = 0;
        $_SESSION['PO'.$identifier]->SupplierName = $_POST['SupplierName'];
        $_SESSION['PO'.$identifier]->CurrCode = $_POST['CurrCode'];
        $_SESSION['PO'.$identifier]->CurrDecimalPlaces = $_POST['CurrDecimalPlaces'];
        $_SESSION['PO'.$identifier]->ExRate = $_POST['ExRate'];
        $_SESSION['PO'.$identifier]->PaymentTerms = $_POST['PaymentTerms'];
        $_SESSION['PO'.$identifier]->SuppTel = $_POST['SuppTel'];
        $_SESSION['PO'.$identifier]->Port = $_POST['Port'];
      //  $_SESSION['PO'.$identifier]->SupplierID= $_POST['supplierid'];
        $_SESSION['PO'.$identifier]->SupplierContact= $_POST['SupplierContact'];
        $_SESSION['PO'.$identifier]->SuppDelAdd1 = $_POST['SuppDelAdd1'];
        $_SESSION['PO'.$identifier]->SuppDelAdd2 = $_POST['SuppDelAdd2'];
        $_SESSION['PO'.$identifier]->SuppDelAdd3 = $_POST['SuppDelAdd3'];
        $_SESSION['PO'.$identifier]->SuppDelAdd4 = $_POST['SuppDelAdd4'];
        $_SESSION['PO'.$identifier]->SuppDelAdd5 = $_POST['SuppDelAdd5'];
        $_SESSION['PO'.$identifier]->SuppDelAdd6 = $_POST['SuppDelAdd6'];
     

        
    }
 
}


if (empty($_GET['identifier'])) {
    $identifier=date('U');
} else {
    $identifier=$_GET['identifier'];
}
/*unset($_SESSION['PO'.$identifier]);
    $_SESSION['ExistingOrder']=0;*/

/* If a purchase order header doesn't exist, then go to
 * PO_Header.php to create one
 */
/*
if (!isset($_SESSION['PO'.$identifier])){
	header('Location:' . $rootpath . '/PO_Header.php');
	exit;
} *///end if (!isset($_SESSION['PO'.$identifier]))

include('includes/header.inc');

$Maximum_Number_Of_Parts_To_Show=50;






if (isset($_POST['Search']) or  isset($_GET['SupplierID'])){  /*ie seach for stock items */

//////////////////////////////////////////////////


    $_SESSION['ExistingOrder']=0;
    unset($_SESSION['PO'.$identifier]);
        /* initialise new class object */
        $_SESSION['PO'.$identifier] = new PurchOrder;
        /*
        * and fill it with essential data
        */
        $_SESSION['PO'.$identifier]->AllowPrintPO = 1; /* Of course 'cos the order aint even started !!*/
        $_SESSION['PO'.$identifier]->GLLink = $_SESSION['CompanyRecord']['gllink_stock'];
        /* set the SupplierID we got */
  echo"search now".      $_SESSION['PO'.$identifier]->SupplierID = $_POST['supplierid'];
        $_SESSION['PO'.$identifier]->DeliveryDate = DateAdd(date($_SESSION['DefaultDateFormat']), 'd',1);
        $_SESSION['RequireSupplierSelection'] = 0;
       // $_POST['Select'] = $_GET['SelectedSupplier'];
        

        /*
        * the item (it's item code) that should be purchased
        */
       // $Purch_Item = $_GET['StockID'];




$_SESSION['PO'.$identifier]->Location=$_POST['StkLocation'];
    $_SESSION['PO'.$identifier]->SupplierContact=$_POST['SupplierContact'];
    $_SESSION['PO'.$identifier]->DelAdd1 = $_POST['DelAdd1'];
    $_SESSION['PO'.$identifier]->DelAdd2 = $_POST['DelAdd2'];
    $_SESSION['PO'.$identifier]->DelAdd3 = $_POST['DelAdd3'];
    $_SESSION['PO'.$identifier]->DelAdd4 = $_POST['DelAdd4'];
    $_SESSION['PO'.$identifier]->DelAdd5 = $_POST['DelAdd5'];
   $_SESSION['PO'.$identifier]->DelAdd6 = $_POST['DelAdd6'];
    $_SESSION['PO'.$identifier]->SuppDelAdd1 = $_POST['SuppDelAdd1'];
    $_SESSION['PO'.$identifier]->SuppDelAdd2 = $_POST['SuppDelAdd2'];
   $_SESSION['PO'.$identifier]->SuppDelAdd3 = $_POST['SuppDelAdd3'];
     $_SESSION['PO'.$identifier]->SuppDelAdd4 = $_POST['SuppDelAdd4'];
    $_SESSION['PO'.$identifier]->SuppDelAdd5 = $_POST['SuppDelAdd5'];
  $_SESSION['PO'.$identifier]->SuppTel= $_POST['SuppTel'];
   $_SESSION['PO'.$identifier]->Initiator = $_SESSION['UserID'];
    $_SESSION['PO'.$identifier]->RequisitionNo ='';
    $_SESSION['PO'.$identifier]->Version = '1';
    $_SESSION['PO'.$identifier]->DeliveryDate = $_POST['DeliveryDate'];
    $_SESSION['PO'.$identifier]->Revised = $_POST['Revised'];
    $_SESSION['PO'.$identifier]->ExRate = $_POST['ExRate'];
    $_SESSION['PO'.$identifier]->Comments = $_POST['Comments'];
    $_SESSION['PO'.$identifier]->DeliveryBy = $_POST['DeliveryBy'];
    $_SESSION['PO'.$identifier]->StatusMessage = $_POST['StatusComments'];
    $_SESSION['PO'.$identifier]->PaymentTerms = $_POST['PaymentTerms'];
    $_SESSION['PO'.$identifier]->Contact = $_POST['Contact'];
    $_SESSION['PO'.$identifier]->Tel = $_POST['Tel'];
    $_SESSION['PO'.$identifier]->Port = $_POST['Port'];
   
   

    if (isset($_POST['RePrint']) and $_POST['RePrint']==1){

        $_SESSION['PO'.$identifier]->AllowPrintPO=1;

        $sql = "UPDATE purchorders
                SET purchorders.allowprint='1'
                WHERE purchorders.orderno='" . $_SESSION['PO'.$identifier]->OrderNo ."'";

        $ErrMsg = _('An error occurred updating the purchase order to allow reprints') . '. ' . _('The error says');
        $UpdateResult = DB_query($sql,$db,$ErrMsg);
    } else {
        $_POST['RePrint'] = 0;
    }



/////////////////////////////////////////

    if ($_POST['Keywords'] AND $_POST['StockCode']) {
        prnMsg( _('Stock description keywords have been used in preference to the Stock code extract entered'), 'info' );
    }
    if ($_POST['Keywords']) {
        //insert wildcard characters in spaces
        $SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

        if ($_POST['StockCat']=='All'){
 $sql = "SELECT stockmaster.stockid,
                            stockmaster.description,
                            stockmaster.units
                FROM bio_supplieritems
                INNER JOIN stockmaster
                ON stockmaster.stockid=bio_supplieritems.itemid
                WHERE bio_supplieritems.supplierid='".$_POST['Keywords']."' 
                ORDER BY stockmaster.stockid";
        } else {
            $sql = "SELECT stockmaster.stockid,
                            stockmaster.description,
                            stockmaster.units
                FROM bio_supplieritems
                INNER JOIN stockmaster
                ON stockmaster.stockid=bio_supplieritems.itemid
                WHERE bio_supplieritems.supplierid='".$_POST['Keywords']."' 
                ORDER BY stockmaster.stockid
                LIMIT " .$_SESSION['DefaultDisplayRecordsMax'];
        }

    } else {
       
       $sql = "SELECT stockmaster.stockid, stockmaster.description, stockmaster.units 
FROM stockmaster,bio_supplieritems 
WHERE bio_supplieritems.supplierid='".$_GET['SupplierID']."' AND stockmaster.stockid='".$_GET['stockid']."' Group BY stockmaster.stockid";
        
    }

    $ErrMsg = _('There is a problem selecting the part records to display because');
    $DbgMsg = _('The SQL statement that failed was');
    $SearchResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

    if (DB_num_rows($SearchResult)==0 AND $debug==1){
        prnMsg( _('There are no products to display matching the criteria provided'),'warn');
    }
    if (DB_num_rows($SearchResult)==1){

        $myrow=DB_fetch_array($SearchResult);
        $_GET['NewItem'] = $myrow['stockid'];
        DB_data_seek($SearchResult,0);
    }

} //end of if search

















if (!isset($_POST['Commit'])) {
	//echo '<a href="'.$rootpath.'/PO_Header.php?identifier=' . $identifier. '">' ._('Back To Purchase Order Header') . '</a><br />';
}

if (isset($_POST['UpdateLines']) OR isset($_POST['Commit'])) { 
	foreach ($_SESSION['PO'.$identifier]->LineItems as $POLine) {
		if ($POLine->Deleted == false) {
			if (!is_numeric(str_replace($locale_info['thousands_sep'],'',$_POST['ConversionFactor'.$POLine->LineNo]))){
				prnMsg(_('The conversion factor is expected to be numeric - the figure which converts from our units to the supplier units. e.g. if the supplier units is a tonne and our unit is a kilogram then the conversion factor that converts our unit to the suppliers unit is 1000'),'error');
				$_SESSION['PO'.$identifier]->LineItems[$POLine->LineNo]->ConversionFactor = 1;
			} else { //a valid number for the conversion factor is entered
				$_SESSION['PO'.$identifier]->LineItems[$POLine->LineNo]->ConversionFactor = doubleval(str_replace($locale_info['thousands_sep'],'',$_POST['ConversionFactor'.$POLine->LineNo]));
			}
			if (!is_numeric(str_replace($locale_info['thousands_sep'],'',$_POST['SuppQty'.$POLine->LineNo]))){
				prnMsg(_('The quantity in the supplier units is expected to be numeric. Please re-enter as a number'),'error');
			} else { //ok to update the PO object variables
				$_SESSION['PO'.$identifier]->LineItems[$POLine->LineNo]->Quantity=round(doubleval(str_replace($locale_info['thousands_sep'],'',$_POST['SuppQty'.$POLine->LineNo]))*doubleval(str_replace($locale_info['thousands_sep'],'',$_SESSION['PO'.$identifier]->LineItems[$POLine->LineNo]->ConversionFactor)),$_SESSION['PO'.$identifier]->LineItems[$POLine->LineNo]->DecimalPlaces);
			}
			if (!is_numeric(str_replace($locale_info['thousands_sep'],'',$_POST['SuppPrice'.$POLine->LineNo]))){
				prnMsg(_('The supplier price is expected to be numeric. Please re-enter as a number'),'error');
			} else { //ok to update the PO object variables
				$_SESSION['PO'.$identifier]->LineItems[$POLine->LineNo]->Price=(doubleval(str_replace($locale_info['thousands_sep'],'',$_POST['SuppPrice'.$POLine->LineNo]))/$_SESSION['PO'.$identifier]->LineItems[$POLine->LineNo]->ConversionFactor);
			}
			$_SESSION['PO'.$identifier]->LineItems[$POLine->LineNo]->ReqDelDate=$_POST['ReqDelDate'.$POLine->LineNo];
		}
	}
}

if (isset($_POST['Commit'])){ /*User wishes to commit the order to the database */

/*First do some validation
 *Is the delivery information all entered
 */
	$InputError=0; /*Start off assuming the best */
	if ($_SESSION['PO'.$identifier]->DelAdd1=='' or strlen($_SESSION['PO'.$identifier]->DelAdd1)<3){
		prnMsg( _('The purchase order cannot be committed to the database because there is no delivery street address specified'),'error');
		$InputError=1;
	} elseif ($_SESSION['PO'.$identifier]->Location=='' or ! isset($_SESSION['PO'.$identifier]->Location)){
		prnMsg( _('The purchase order can not be committed to the database because there is no location specified to book any stock items into'),'error');
		$InputError=1;
	} elseif ($_SESSION['PO'.$identifier]->LinesOnOrder <=0){
		prnMsg( _('The purchase order can not be committed to the database because there are no lines entered on this order'),'error');
		$InputError=1;
	}
	
/*If all clear then proceed to update the database
 */
	if ($InputError!=1){
		
		$result = DB_Txn_Begin($db);

		/*figure out what status to set the order to */
		if (IsEmailAddress($_SESSION['UserEmail'])){
			$UserDetails  = ' <a href="mailto:' . $_SESSION['UserEmail'] . '">' . $_SESSION['UsersRealName']. '</a>';
		} else {
			$UserDetails  = ' ' . $_SESSION['UsersRealName'] . ' ';
		}
		if ($_SESSION['AutoAuthorisePO']==1) { //if the user has authority to authorise the PO then it will automatically be authorised
			$AuthSQL ="SELECT authlevel
						FROM purchorderauth
						WHERE userid='".$_SESSION['UserID']."'
						AND currabrev='".$_SESSION['PO'.$identifier]->CurrCode."'";

			$AuthResult=DB_query($AuthSQL,$db);
			$AuthRow=DB_fetch_array($AuthResult);
			
			if (DB_num_rows($AuthResult) > 0 AND $AuthRow['authlevel'] > $_SESSION['PO'.$identifier]->Order_Value()) { //user has authority to authrorise as well as create the order
				$StatusComment=date($_SESSION['DefaultDateFormat']).' - ' . _('Order Created and Authorised by') . $UserDetails . ' - '.$_SESSION['PO'.$identifier]->StatusMessage.'<br />';
				$_SESSION['PO'.$identifier]->AllowPrintPO=1;
				$_SESSION['PO'.$identifier]->Status = 'Authorised';
			} else { // no authority to authorise this order
				if (DB_num_rows($AuthResult) ==0){
					$AuthMessage = _('Your authority to approve purchase orders in') . ' ' . $_SESSION['PO'.$identifier]->CurrCode . ' ' . _('has not yet been set up') . '<br />';
				} else {
					$AuthMessage = _('You can only authorise up to').' '.$_SESSION['PO'.$identifier]->CurrCode.' '.$AuthRow['authlevel'] .'.<br />';
				}
				
				prnMsg( _('You do not have permission to authorise this purchase order').'.<br />'. _('This order is for').' '.
					$_SESSION['PO'.$identifier]->CurrCode . ' '. $_SESSION['PO'.$identifier]->Order_Value() .'. '.
					$AuthMessage .
					_('If you think this is a mistake please contact the systems administrator') . '<br />'.
					_('The order will be created with a status of pending and will require authorisation'), 'warn');
					
				$_SESSION['PO'.$identifier]->AllowPrintPO=0;
				$StatusComment=date($_SESSION['DefaultDateFormat']).' - ' . _('Order Created by') . $UserDetails . ' - '.$_SESSION['PO'.$identifier]->StatusMessage.'<br />';
				$_SESSION['PO'.$identifier]->Status = 'Authorised';
			}
		} else { //auto authorise is set to off
			$_SESSION['PO'.$identifier]->AllowPrintPO=0;
			$StatusComment=date($_SESSION['DefaultDateFormat']).' - ' . _('Order Created by') . $UserDetails . ' - '.$_SESSION['PO'.$identifier]->StatusMessage.'<br />';
			$_SESSION['PO'.$identifier]->Status = 'Authorised';
		}

		if ($_SESSION['ExistingOrder']==0){ /*its a new order to be inserted */

//Do we need to check authorisation to create - no because already trapped when new PO session started
			
			/*Get the order number */
			$_SESSION['PO'.$identifier]->OrderNo =  GetNextTransNo(18, $db);

			/*Insert to purchase order header record */
			$sql = "INSERT INTO purchorders ( orderno,
											supplierno,
											comments,
											orddate,
											rate,
											initiator,
											requisitionno,
											intostocklocation,
											deladd1,
											deladd2,
											deladd3,
											deladd4,
											deladd5,
											deladd6,
											tel,
											suppdeladdress1,
											suppdeladdress2,
											suppdeladdress3,
											suppdeladdress4,
											suppdeladdress5,
											suppdeladdress6,
											suppliercontact,
											supptel,
											contact,
											version,
											revised,
											deliveryby,
											status,
											stat_comment,
											deliverydate,
											paymentterms,
											allowprint)
										VALUES(	'" . $_SESSION['PO'.$identifier]->OrderNo . "',
												'" . $_SESSION['PO'.$identifier]->SupplierID . "',
												'" . $_SESSION['PO'.$identifier]->Comments . "',
												'" . Date('Y-m-d') . "',
												'" . $_SESSION['PO'.$identifier]->ExRate . "',
												'" . $_SESSION['PO'.$identifier]->Initiator . "',
												'" . $_SESSION['PO'.$identifier]->RequisitionNo . "',
												'" . $_SESSION['PO'.$identifier]->Location . "',
												'" . $_SESSION['PO'.$identifier]->DelAdd1 . "',
												'" . $_SESSION['PO'.$identifier]->DelAdd2 . "',
												'" . $_SESSION['PO'.$identifier]->DelAdd3 . "',
												'" . $_SESSION['PO'.$identifier]->DelAdd4 . "',
												'" . $_SESSION['PO'.$identifier]->DelAdd5 . "',
												'" . $_SESSION['PO'.$identifier]->DelAdd6 . "',
												'" . $_SESSION['PO'.$identifier]->Tel . "',
												'" . $_SESSION['PO'.$identifier]->SuppDelAdd1 . "',
												'" . $_SESSION['PO'.$identifier]->SuppDelAdd2 . "',
												'" . $_SESSION['PO'.$identifier]->SuppDelAdd3 . "',
												'" . $_SESSION['PO'.$identifier]->SuppDelAdd4 . "',
												'" . $_SESSION['PO'.$identifier]->SuppDelAdd5 . "',
												'" . $_SESSION['PO'.$identifier]->SuppDelAdd6 . "',
												'" . $_SESSION['PO'.$identifier]->SupplierContact . "',
												'" . $_SESSION['PO'.$identifier]->SuppTel. "',
												'" . $_SESSION['PO'.$identifier]->Contact . "',
												'" . $_SESSION['PO'.$identifier]->Version . "',
												'" . Date('Y-m-d') . "',
												'" . $_SESSION['PO'.$identifier]->DeliveryBy . "',
												'" . $_SESSION['PO'.$identifier]->Status . "',
												'" . $StatusComment . "',
												'" . FormatDateForSQL($_SESSION['PO'.$identifier]->DeliveryDate) . "',
												'" . $_SESSION['PO'.$identifier]->PaymentTerms. "',
												'1'
											)";
	
			$ErrMsg =  _('The purchase order header record could not be inserted into the database because');
			$DbgMsg = _('The SQL statement used to insert the purchase order header record and failed was');
			$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);

		     /*Insert the purchase order detail records */
			foreach ($_SESSION['PO'.$identifier]->LineItems as $POLine) {
				if ($POLine->Deleted==False) {
					$sql = "INSERT INTO purchorderdetails ( orderno,
														itemcode,
														deliverydate,
														itemdescription,
														glcode,
														unitprice,
														quantityord,
														shiptref,
														jobref,
														suppliersunit,
														suppliers_partno,
														assetid,
														conversionfactor )
												VALUES (
														'" . $_SESSION['PO'.$identifier]->OrderNo . "',
														'" . $POLine->StockID . "',
														'" . FormatDateForSQL($POLine->ReqDelDate) . "',
														'" . $POLine->ItemDescription . "',
														'" . $POLine->GLCode . "',
														'" . $POLine->Price . "',
														'" . $POLine->Quantity . "',
														'" . $POLine->ShiptRef . "',
														'" . $POLine->JobRef . "',
														'" . $POLine->SuppliersUnit . "',
														'" . $POLine->Suppliers_PartNo . "',
														'" . $POLine->AssetID . "',
														'" . $POLine->ConversionFactor . "')";
					$ErrMsg =_('One of the purchase order detail records could not be inserted into the database because');
					$DbgMsg =_('The SQL statement used to insert the purchase order detail record and failed was');
					
					$result =DB_query($sql,$db,$ErrMsg,$DbgMsg,true);
				}
			} /* end of the loop round the detail line items on the order */
			echo '<p />';
			prnMsg(_('Purchase Order') . ' ' . $_SESSION['PO'.$identifier]->OrderNo . ' ' . _('on') . ' ' .
		     	$_SESSION['PO'.$identifier]->SupplierName . ' ' . _('has been created'),'success');
		} else { /*its an existing order need to update the old order info */

		     /*Update the purchase order header with any changes */

			$sql = "UPDATE purchorders SET supplierno = '" . $_SESSION['PO'.$identifier]->SupplierID . "' ,
										comments='" . $_SESSION['PO'.$identifier]->Comments . "',
										rate='" . $_SESSION['PO'.$identifier]->ExRate . "',
										initiator='" . $_SESSION['PO'.$identifier]->Initiator . "',
										requisitionno= '" . $_SESSION['PO'.$identifier]->RequisitionNo . "',
										version= '" .  $_SESSION['PO'.$identifier]->Version . "',
										deliveryby='" . $_SESSION['PO'.$identifier]->DeliveryBy . "',
										deliverydate='" . FormatDateForSQL($_SESSION['PO'.$identifier]->DeliveryDate) . "',
										revised= '" . Date('Y-m-d') . "',
										intostocklocation='" . $_SESSION['PO'.$identifier]->Location . "',
										deladd1='" . $_SESSION['PO'.$identifier]->DelAdd1 . "',
										deladd2='" . $_SESSION['PO'.$identifier]->DelAdd2 . "',
										deladd3='" . $_SESSION['PO'.$identifier]->DelAdd3 . "',
										deladd4='" . $_SESSION['PO'.$identifier]->DelAdd4 . "',
										deladd5='" . $_SESSION['PO'.$identifier]->DelAdd5 . "',
										deladd6='" . $_SESSION['PO'.$identifier]->DelAdd6 . "',
										deladd6='" . $_SESSION['PO'.$identifier]->Tel . "',
										suppdeladdress1='" . $_SESSION['PO'.$identifier]->SuppDelAdd1 . "',
										suppdeladdress2='" . $_SESSION['PO'.$identifier]->SuppDelAdd2 . "',
										suppdeladdress3='" . $_SESSION['PO'.$identifier]->SuppDelAdd3 . "',
										suppdeladdress4='" . $_SESSION['PO'.$identifier]->SuppDelAdd4 . "',
										suppdeladdress5='" . $_SESSION['PO'.$identifier]->SuppDelAdd5 . "',
										suppdeladdress6='" . $_SESSION['PO'.$identifier]->SuppDelAdd6 . "',
										suppliercontact='" . $_SESSION['PO'.$identifier]->SupplierContact . "',
										supptel='" . $_SESSION['PO'.$identifier]->SuppTel . "',
										contact='" . $_SESSION['PO'.$identifier]->Contact . "',
										paymentterms='" . $_SESSION['PO'.$identifier]->PaymentTerms . "',
										allowprint='1',
										status = '" . $_SESSION['PO'.$identifier]->Status . "'
										WHERE orderno = '" . $_SESSION['PO'.$identifier]->OrderNo ."'";

			$ErrMsg =  _('The purchase order could not be updated because');
			$DbgMsg = _('The SQL statement used to update the purchase order header record, that failed was');
			$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);

			/*Now Update the purchase order detail records */
			foreach ($_SESSION['PO'.$identifier]->LineItems as $POLine) {
				$result=DB_query($sql,$db,'','',true);
				if ($POLine->Deleted==true) {
					if ($POLine->PODetailRec!='') {
						$sql="DELETE FROM purchorderdetails WHERE podetailitem='" . $POLine->PODetailRec . "'";
						$ErrMsg =  _('The purchase order could not be deleted because');
						$DbgMsg = _('The SQL statement used to delete the purchase order header record, that failed was');
						$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);
					}
				} else if ($POLine->PODetailRec=='') {

					$sql = "INSERT INTO purchorderdetails ( orderno,
														itemcode,
														deliverydate,
														itemdescription,
														glcode,
														unitprice,
														quantityord,
														shiptref,
														jobref,
														suppliersunit,
														suppliers_partno,
														assetid,
														conversionfactor)
													VALUES (
														'" . $_SESSION['PO'.$identifier]->OrderNo . "',
														'" . $POLine->StockID . "',
														'" . FormatDateForSQL($POLine->ReqDelDate) . "',
														'" . $POLine->ItemDescription . "',
														'" . $POLine->GLCode . "',
														'" . $POLine->Price . "',
														'" . $POLine->Quantity . "',
														'" . $POLine->ShiptRef . "',
														'" . $POLine->JobRef . "',
														'" . $POLine->SuppliersUnit . "',
														'" . $POLine->Suppliers_PartNo . "',
														'" . $POLine->AssetID . "',
														'" . $POLine->ConversionFactor . "')";

				} else {
					if ($POLine->Quantity==$POLine->QtyReceived){
						$sql = "UPDATE purchorderdetails SET itemcode='" . $POLine->StockID . "',
															deliverydate ='" . FormatDateForSQL($POLine->ReqDelDate) . "',
															itemdescription='" . $POLine->ItemDescription . "',
															glcode='" . $POLine->GLCode . "',
															unitprice='" . $POLine->Price . "',
															quantityord='" . $POLine->Quantity . "',
															shiptref='" . $POLine->ShiptRef . "',
															jobref='" . $POLine->JobRef . "',
															suppliersunit='" . $POLine->SuppliersUnit . "',
															suppliers_partno='" . $POLine->Suppliers_PartNo . "',
															completed=1,
															assetid='" . $POLine->AssetID . "',
															conversionfactor = '" . $POLine->ConversionFactor . "' 
						WHERE podetailitem='" . $POLine->PODetailRec . "'";
					} else {
						$sql = "UPDATE purchorderdetails SET itemcode='" . $POLine->StockID . "',
															deliverydate ='" . FormatDateForSQL($POLine->ReqDelDate) . "',
															itemdescription='" . $POLine->ItemDescription . "',
															glcode='" . $POLine->GLCode . "',
															unitprice='" . $POLine->Price . "',
															quantityord='" . $POLine->Quantity . "',
															shiptref='" . $POLine->ShiptRef . "',
															jobref='" . $POLine->JobRef . "',
															suppliersunit='" . $POLine->SuppliersUnit . "',
															suppliers_partno='" . $POLine->Suppliers_PartNo . "',
															assetid='" . $POLine->AssetID . "',
															conversionfactor = '" . $POLine->ConversionFactor . "'
								WHERE podetailitem='" . $POLine->PODetailRec . "'";
					}
				}

				$ErrMsg = _('One of the purchase order detail records could not be updated because');
				$DbgMsg = _('The SQL statement used to update the purchase order detail record that failed was');
				$result =DB_query($sql,$db,$ErrMsg,$DbgMsg,true);
			} /* end of the loop round the detail line items on the order */
			echo '<br /><br />';
			prnMsg(_('Purchase Order') . ' ' . $_SESSION['PO'.$identifier]->OrderNo . ' ' . _('has been updated'),'success');
			if ($_SESSION['PO'.$identifier]->AllowPrintPO==1 
					AND ($_SESSION['PO'.$identifier]->Status=='Authorised'
								OR $_SESSION['PO'.$identifier]->Status=='Printed')){
				echo '<br /><a target="_blank" href="'.$rootpath.'/PO_PDFPurchOrder.php?OrderNo=' . $_SESSION['PO'.$identifier]->OrderNo . '">' . _('Print Purchase Order') . '</a>';
			}
		} /*end of if its a new order or an existing one */


		$Result = DB_Txn_Commit($db);
		unset($_SESSION['PO'.$identifier]); /*Clear the PO data to allow a newy to be input*/
		echo '<br /><a href="' . $rootpath . '/PO_SelectOSPurchOrder.php">' . _('Return To PO List') . '</a>';
		include('includes/footer.inc');
		exit;
	} /*end if there were no input errors trapped */
} /* end of the code to do transfer the PO object to the database  - user hit the place PO*/


/* Always do the stuff below if not looking for a supplierid */

if(isset($_GET['Delete'])){
	if($_SESSION['PO'.$identifier]->Some_Already_Received($_GET['Delete'])==0){
		$_SESSION['PO'.$identifier]->LineItems[$_GET['Delete']]->Deleted=True;
		$_SESSION['PO'.$identifier]->LinesOnOrder --;
		include ('includes/PO_UnsetFormVbls.php');
	} else {
		prnMsg( _('This item cannot be deleted because some of it has already been received'),'warn');
	}
}

if (isset($_POST['EnterLine'])){ /*Inputs from the form directly without selecting a stock item from the search */

	$AllowUpdate = true; /*always assume the best */
	if (!is_numeric(doubleval(str_replace($locale_info['thousands_sep'],'',$_POST['Qty'])))){
		$AllowUpdate = false;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The quantity of the order item must be numeric'),'error');
	}
	if ($_POST['Qty']<0){
		$AllowUpdate = false;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The quantity of the ordered item entered must be a positive amount'),'error');
	}
	if (!is_numeric(doubleval(str_replace($locale_info['thousands_sep'],'',$_POST['Price'])))){
		$AllowUpdate = false;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The price entered must be numeric'),'error');
	}
	if (!Is_Date($_POST['ReqDelDate'])){
		$AllowUpdate = False;
		prnMsg( _('Cannot Enter this order line') . '</b><br />' . _('The date entered must be in the format') . ' ' . $_SESSION['DefaultDateFormat'], 'error');
	}

//	include ('PO_Chk_ShiptRef_JobRef.php');

 /*It's not a stock item */

		/*need to check GL Code is valid if GLLink is active */
	if ($_SESSION['PO'.$identifier]->GLLink==1){

		$sql = "SELECT accountname
						FROM chartmaster
						WHERE accountcode ='" . (int) $_POST['GLCode'] . "'";
		$ErrMsg =  _('The account details for') . ' ' . $_POST['GLCode'] . ' ' . _('could not be retrieved because');
		$DbgMsg =  _('The SQL used to retrieve the details of the account, but failed was');
		$GLValidResult = DB_query($sql,$db,$ErrMsg,$DbgMsg,false,false);
		if (DB_error_no($db) !=0) {
			$AllowUpdate = false;
			prnMsg( _('The validation process for the GL Code entered could not be executed because') . ' ' . DB_error_msg($db), 'error');
			if ($debug==1){
				prnMsg (_('The SQL used to validate the code entered was') . ' ' . $sql,'error');
			}
			include('includes/footer.inc');
			exit;
		}
		if (DB_num_rows($GLValidResult) == 0) { /*The GLCode entered does not exist */
			$AllowUpdate = false;
			prnMsg( _('Cannot enter this order line') . ':<br />' . _('The general ledger code') . ' - ' . $_POST['GLCode'] . ' ' . _('is not a general ledger code that is defined in the chart of accounts') . ' . ' . _('Please use a code that is already defined') . '. ' . _('See the Chart list from the link below'),'error');
		} else {
			$myrow = DB_fetch_row($GLValidResult);
			$GLAccountName = $myrow[0];
		}
	} /* dont bother checking the GL Code if there is no GL code to check ie not linked to GL */
	else {
		$_POST['GLCode']=0;
	}
	if ($_POST['AssetID'] !='Not an Asset'){
		$ValidAssetResult = DB_query("SELECT assetid,
											description,
											costact
								FROM fixedassets
								INNER JOIN fixedassetcategories
								ON fixedassets.assetcategoryid=fixedassetcategories.categoryid
								WHERE assetid='" . $_POST['AssetID'] . "'",$db);
		if (DB_num_rows($ValidAssetResult)==0){ // then the asset id entered doesn't exist
			$AllowUpdate = false;
			prnMsg(_('An asset code was entered but it does not yet exist. Only pre-existing asset ids can be entered when ordering a fixed asset'),'error');
		} else {
			$AssetRow = DB_fetch_array($ValidAssetResult);
			$_POST['GLCode'] = $AssetRow['costact'];
			if ($_POST['ItemDescription']==''){
				$_POST['ItemDescription'] = $AssetRow['description'];
			}
		}
	} /*end if an AssetID is entered */
	  else {
		  $_POST['AssetID'] = 0; // cannot commit a string to an integer field so make it 0 if AssetID = 'Not an Asset'
	}
	if (strlen($_POST['ItemDescription'])<=3){
		$AllowUpdate = false;
		prnMsg(_('Cannot enter this order line') . ':<br />' . _('The description of the item being purchased is required where a non-stock item is being ordered'),'warn');
	}

	if ($AllowUpdate == true){
	//adding the non-stock item
		$_POST['Price'] = doubleval(str_replace($locale_info['thousands_sep'],'',$_POST['Price']));
		$_POST['Qty'] = doubleval(str_replace($locale_info['thousands_sep'],'',$_POST['Qty']));
		
		$_SESSION['PO'.$identifier]->add_to_order ($_SESSION['PO'.$identifier]->LinesOnOrder+1,
												'',
												0, /*Serialised */
												0, /*Controlled */
												$_POST['Qty'],
												$_POST['ItemDescription'],
												$_POST['Price'],
												$_POST['SuppliersUnit'],
												$_POST['GLCode'],
												$_POST['ReqDelDate'],
												'',
												0,
												'',
												0,
												0,
												$GLAccountName,
												2,
												$_POST['SuppliersUnit'],
												1,
												'',
												$_POST['AssetID']);
	//   include ('includes/PO_UnsetFormVbls.php');
	}
}
 /*end if Enter line button was hit - adding non stock items */


if (isset($_POST['NewItem'])){ 
    
    //echo "bcgnhcg". $_SESSION['PO'.$identifier]->LineItems;
  
	/* NewItem is set from the part selection list as the part code selected 
	* take the form entries and enter the data from the form into the PurchOrder class variable 
	* A series of form variables of the format "Qty" with the ItemCode concatenated are created on the search for adding new 
	* items for each of these form variables need to parse out the items and look up the details to add them to the purchase
	* order  $_POST is of course the global array of all posted form variables */

	foreach ($_POST as $FormVariableName => $Quantity) {

		if (substr($FormVariableName, 0, 6)=='NewQty') { //if the form variable represents a Qty to add to the order

		$ItemCode = substr($FormVariableName, 6, strlen($FormVariableName)-6);
			$AlreadyOnThisOrder = 0;

			if ($_SESSION['PO_AllowSameItemMultipleTimes'] ==false){
				if (count($_SESSION['PO'.$identifier]->LineItems)!=0){

					foreach ($_SESSION['PO'.$identifier]->LineItems AS $OrderItem) {

					/* do a loop round the items on the order to see that the item is not already on this order */
						if (($OrderItem->StockID == $ItemCode) and ($OrderItem->Deleted==false)) {
							$AlreadyOnThisOrder = 1;
							prnMsg( _('The item') . ' ' . $ItemCode . ' ' . _('is already on this order') . '. ' . _('The system will not allow the same item on the order more than once') . '. ' . _('However you can change the quantity ordered of the existing line if necessary'),'error');
						}
					} /* end of the foreach loop to look for preexisting items of the same code */
				}
            }
			
			if ($AlreadyOnThisOrder!=1 AND $Quantity > 0){
                 
				$sql = "SELECT description,
							stockid,
							units,
							decimalplaces,
							stockact,
							accountname
					FROM 	stockmaster INNER JOIN stockcategory
					ON stockcategory.categoryid = stockmaster.categoryid
					INNER JOIN chartmaster
					ON chartmaster.accountcode = stockcategory.stockact
					WHERE  stockmaster.stockid = '". $ItemCode . "'";

				$ErrMsg = _('The item details for') . ' ' . $ItemCode . ' ' . _('could not be retrieved because');
				$DbgMsg = _('The SQL used to retrieve the item details but failed was');
				$ItemResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);
				if (DB_num_rows($ItemResult)==1){
					$ItemRow = DB_fetch_array($ItemResult);
					
					$sql = "SELECT price,
								conversionfactor,
								supplierdescription,
								suppliersuom,
								suppliers_partno,
								leadtime,
								MAX(purchdata.effectivefrom) AS latesteffectivefrom
					FROM purchdata 
					WHERE purchdata.supplierno = '" . $_SESSION['PO'.$identifier]->SupplierID . "'
					AND purchdata.effectivefrom <='" . Date('Y-m-d') . "'
					AND purchdata.stockid = '". $ItemCode . "'
					GROUP BY 	purchdata.price,
								purchdata.conversionfactor,
								purchdata.supplierdescription,
								purchdata.suppliersuom,
								purchdata.suppliers_partno,
								purchdata.leadtime";
												
					$ErrMsg = _('The purchasing data for') . ' ' . $ItemCode . ' ' . _('could not be retrieved because');
					$DbgMsg = _('The SQL used to retrieve the purchasing data but failed was');
					$PurchDataResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);
					if (DB_num_rows($PurchDataResult)==1){ //the purchasing data is set up
						$PurchRow = DB_fetch_array($PurchDataResult);
						$PurchPrice = $PurchRow['price']/$PurchRow['conversionfactor'];
						$ConversionFactor = $PurchRow['conversionfactor'];
						$SupplierDescription = $PurchRow['suppliers_partno'] .' - ';
						if (strlen($PurchRow['supplierdescription'])>2){
							$SupplierDescription .= $PurchRow['supplierdescription'];
						} else {
							$SupplierDescription .= $ItemRow['description'];
						}
					$SuppliersUnitOfMeasure = $PurchRow['suppliersuom'];
						$SuppliersPartNo = $PurchRow['suppliers_partno'];
						$LeadTime = $PurchRow['leadtime'];
					} else { // no purchasing data setup
						$PurchPrice = 0; 
						$ConversionFactor = 1;
						$SupplierDescription = 	$ItemRow['description'];
						$SuppliersUnitOfMeasure = $ItemRow['unitname'];
						$SuppliersPartNo = '';
						$LeadTime = 1;
					}
	
					$_SESSION['PO'.$identifier]->add_to_order ($_SESSION['PO'.$identifier]->LinesOnOrder+1,
															$ItemCode,
															0, /*Serialised */
															0, /*Controlled */
															$Quantity*$ConversionFactor, /* Qty */
															$SupplierDescription,
															$PurchPrice,
															$ItemRow['units'],
															$ItemRow['stockact'],
															$_SESSION['PO'.$identifier]->DeliveryDate,
															0,
															0,
															0,
															0,
															0,
															$Itemrow['accountname'],
															$ItemRow['decimalplaces'],
															$SuppliersUnitOfMeasure,
															$ConversionFactor,
															$LeadTime,
															$SuppliersPartNo
															);
				} else { //no rows returned by the SQL to get the item
                
					prnMsg (_('The item code') . ' ' . $ItemCode . ' ' . _('does not exist in the database and therefore cannot be added to the order'),'error');
					if ($debug==1){
						echo '<br / >'.$sql;
					}
					include('includes/footer.inc');
					exit;
				} 
			} /* end of if not already on the order */
		} /* end if the $_POST has NewQty in the variable name */
	} /* end loop around the $_POST array */
} /* end of if its a new item */

/* This is where the order as selected should be displayed  reflecting any deletions or insertions*/

echo '<form name="form1" action="' . $_SERVER['PHP_SELF'] . '?identifier=' . $identifier . '" method=post>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';//count($_SESSION['PO'.$identifier]->LineItems)>0


if ( count($_SESSION['PO'.$identifier]->LineItems)>0 and !isset($_GET['Edit'])){
    echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/supplier.png" title="' .
        _('Purchase Order') . '" alt="">  '.$_SESSION['PO'.$identifier]->SupplierName;

    if (isset($_SESSION['PO'.$identifier]->OrderNo)) {
        echo  ' ' . _('Purchase Order') .' '. $_SESSION['PO'.$identifier]->OrderNo ;
    }
    echo '<br /><b>'._(' Order Summary') . '</b>';
    echo '<table cellpadding=2 colspan=7 class=selection>';
    echo '<tr>
            <th>' . _('Item Code') . '</th>
            <th>' . _('Description') . '</th>
            <th>' . _('Quantity Our Units') . '</th>
            <th>' . _('Our Unit') .'</th>
            <th>' . _('Price Our Units') .' ('.$_SESSION['PO'.$identifier]->CurrCode.  ')</th>
            <th>' . _('Unit Conversion Factor') . '</th>
            <th>' . _('Order Quantity') . '<br />' . _('Supplier Units') . '</th>
            <th>' .  _('Supplier Unit') . '</th>
            <th>' . _('Order Price') . '<br />' . _('Supp Units') . ' ('.$_SESSION['PO'.$identifier]->CurrCode.  ')</th>
            <th>' . _('Sub-Total') .' ('.$_SESSION['PO'.$identifier]->CurrCode.  ')</th>
            <th>' . _('Deliver By') .'</th>
            </tr>';
    
    $_SESSION['PO'.$identifier]->Total = 0;
    $k = 0;  //row colour counter

    foreach ($_SESSION['PO'.$identifier]->LineItems as $POLine) {

        if ($POLine->Deleted==False) {
            $LineTotal = $POLine->Quantity * $POLine->Price;
            $DisplayLineTotal = number_format($LineTotal,$_SESSION['PO'.$identifier]->CurrDecimalPlaces);
            // Note if the price is greater than 1 use 2 decimal place, if the price is a fraction of 1, use 4 decimal places
            // This should help display where item-price is a fraction
            if ($POLine->Price > 1) {
                $DisplayPrice = number_format($POLine->Price,$_SESSION['PO'.$identifier]->CurrDecimalPlaces,'.','');
            } else {
                $DisplayPrice = number_format($POLine->Price,4,'.','');
            }

            if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                echo '<tr class="OddTableRows">';
                $k=1;
            }

            echo '<td>' . $POLine->StockID  . '</td>
                <td>' . $POLine->ItemDescription . '</td>
                <td class="number">' . number_format($POLine->Quantity,$POLine->DecimalPlaces) . '</td>
                <td>' . $POLine->Units . '</td>
                <td class="number">' . $DisplayPrice . '</td>
                <td><input type="text" class="number" name="ConversionFactor' . $POLine->LineNo .'" size="8" value="' . $POLine->ConversionFactor . '"></td>
                <td><input type="text" class="number" name="SuppQty' . $POLine->LineNo .'" size="10" value="' . number_format($POLine->Quantity/$POLine->ConversionFactor,$POLine->DecimalPlaces) . '"></td>
                <td>' . $POLine->SuppliersUnit . '</td>
                <td><input type="text" class="number" name="SuppPrice' . $POLine->LineNo . '" size="10" value="' .number_format(($POLine->Price *$POLine->ConversionFactor),$_SESSION['PO'.$identifier]->CurrDecimalPlaces) .'"></td>
                <td class="number">' . $DisplayLineTotal . '</td>
                <td><input type="text" class="date" alt="' .$_SESSION['DefaultDateFormat'].'" name="ReqDelDate' . $POLine->LineNo.'" size="10" value="' .$POLine->ReqDelDate .'"></td>
                <td><a href="' . $_SERVER['PHP_SELF'] . '?identifier='.$identifier. '&Delete=' . $POLine->LineNo . '">' . _('Delete') . '</a></td></tr>';
            $_SESSION['PO'.$identifier]->Total = $_SESSION['PO'.$identifier]->Total + $LineTotal;
        }
    }

    $DisplayTotal = number_format($_SESSION['PO'.$identifier]->Total,$_SESSION['PO'.$identifier]->CurrDecimalPlaces);
    echo '<tr><td colspan="10" class=number>' . _('TOTAL') . _(' excluding Tax') . '</td>
                        <td class=number><b>' . $DisplayTotal . '</b></td>
            </tr></table>';
    echo '<br /><div class="centre"><input type="submit" name="UpdateLines" value="Update Order Lines">';
    
    echo '&nbsp;<input type="submit" name="Commit" value="Process Order"></div>';
    
} /*Only display the order line items if there are any !! */








echo '<table width=80%>
        <tr>
        <th><font color=blue size=4><b>' . _('Warehouse Info') . '</b></font></th>
        <!--    <th><font color=blue size=4><b>' . _('Delivery To') . '</b></font></th> -->
            <th><font color=blue size=4><b>' . _('Supplier Info') . '</b></font></th>
        </tr>
        <tr><td valign=top>';
    /*nested table level1 */

    echo '<table class=selection width=100%><tr><td>' . _('Warehouse') . ':</td>
            <td><select name=StkLocation >';//onChange="ReloadForm(form1.LookupDeliveryAddress)"
if(isset($_GET['locat']))
{$_SESSION['UserStockLocation']=$_GET['locat'];
$ss=$_SESSION['UserStockLocation'];
}else{
    $ss=$_SESSION['UserStockLocation'];
}
    $sql = "SELECT loccode,
                    locationname
            FROM locations where loccode='".$ss."'" ;//
    $LocnResult = DB_query($sql,$db);

    while ($LocnRow=DB_fetch_array($LocnResult)){
        if ((isset($_POST['StkLocation']) and ($_POST['StkLocation'] == $LocnRow['loccode'] OR
                ($_POST['StkLocation']=='' AND $LocnRow['loccode']==$_SESSION['UserStockLocation'])))
                 or ($_GET['locat'] == $LocnRow['loccode'] or ($LocnRow['loccode']==$_SESSION['UserStockLocation'])) ){
            echo '<option selected value="' . $LocnRow['loccode'] . '">' . $LocnRow['locationname'] . '</option>';
        } else {
            echo '<option value="' . $LocnRow['loccode'] . '">' . $LocnRow['locationname'] . '</option>';
        }
    }

    echo '</select>
      <input type="submit"  style="display:none" name="LookupDeliveryAddress" value="' ._('Select') . '"></td>
        </tr>';//

/* If this is the first time
 * the form loaded set up defaults */

    if (!isset($_POST['StkLocation']) OR $_POST['StkLocation']=='' or $_GET['locat']){

        $_POST['StkLocation'] = $_SESSION['UserStockLocation'];

        $sql = "SELECT deladd1,
                         deladd2,
                        deladd3,
                        deladd4,
                        deladd5,
                        deladd6,
                        tel,
                        contact
                    FROM locations
                    WHERE loccode='" . $_POST['StkLocation'] . "'";

        $LocnAddrResult = DB_query($sql,$db);
        if (DB_num_rows($LocnAddrResult)==1){
            $LocnRow = DB_fetch_array($LocnAddrResult);
            $_POST['DelAdd1'] = $LocnRow['deladd1'];
            $_POST['DelAdd2'] = $LocnRow['deladd2'];
            $_POST['DelAdd3'] = $LocnRow['deladd3'];
            $_POST['DelAdd4'] = $LocnRow['deladd4'];
            $_POST['DelAdd5'] = $LocnRow['deladd5'];
            $_POST['DelAdd6'] = $LocnRow['deladd6'];
            $_POST['Tel'] = $LocnRow['tel'];
            $_POST['Contact'] = $LocnRow['contact'];

            $_SESSION['PO'.$identifier]->Location= $_POST['StkLocation'];
//            $_SESSION['PO'.$identifier]->SupplierContact= $_POST['SupplierContact'];
            $_SESSION['PO'.$identifier]->DelAdd1 = $_POST['DelAdd1'];
            $_SESSION['PO'.$identifier]->DelAdd2 = $_POST['DelAdd2'];
            $_SESSION['PO'.$identifier]->DelAdd3 = $_POST['DelAdd3'];
            $_SESSION['PO'.$identifier]->DelAdd4 = $_POST['DelAdd4'];
            $_SESSION['PO'.$identifier]->DelAdd5 = $_POST['DelAdd5'];
            $_SESSION['PO'.$identifier]->DelAdd6 = $_POST['DelAdd6'];
            $_SESSION['PO'.$identifier]->Tel = $_POST['Tel'];
            $_SESSION['PO'.$identifier]->Contact = $_POST['Contact'];

        } else {
             /*The default location of the user is crook */
            prnMsg(_('The default stock location set up for this user is not a currently defined stock location') .
                '. ' . _('Your system administrator needs to amend your user record'),'error');
        }


    } elseif (isset($_POST['LookupDeliveryAddress'])){

        $sql = "SELECT deladd1,
                        deladd2,
                        deladd3,
                        deladd4,
                        deladd5,
                        deladd6,
                        tel,
                        contact
                    FROM locations
                    WHERE loccode='" . $_POST['StkLocation'] . "'";

        $LocnAddrResult = DB_query($sql,$db);
        if (DB_num_rows($LocnAddrResult)==1){
            $LocnRow = DB_fetch_array($LocnAddrResult);
            $_POST['DelAdd1'] = $LocnRow['deladd1'];
            $_POST['DelAdd2'] = $LocnRow['deladd2'];
            $_POST['DelAdd3'] = $LocnRow['deladd3'];
            $_POST['DelAdd4'] = $LocnRow['deladd4'];
            $_POST['DelAdd5'] = $LocnRow['deladd5'];
            $_POST['DelAdd6'] = $LocnRow['deladd6'];
            $_POST['Tel'] = $LocnRow['tel'];
            $_POST['Contact'] = $LocnRow['contact'];

            $_SESSION['PO'.$identifier]->Location= $_POST['StkLocation'];
            $_SESSION['PO'.$identifier]->DelAdd1 = $_POST['DelAdd1'];
            $_SESSION['PO'.$identifier]->DelAdd2 = $_POST['DelAdd2'];
            $_SESSION['PO'.$identifier]->DelAdd3 = $_POST['DelAdd3'];
            $_SESSION['PO'.$identifier]->DelAdd4 = $_POST['DelAdd4'];
            $_SESSION['PO'.$identifier]->DelAdd5 = $_POST['DelAdd5'];
            $_SESSION['PO'.$identifier]->DelAdd6 = $_POST['DelAdd6'];
            $_SESSION['PO'.$identifier]->Tel = $_POST['Tel'];
            $_SESSION['PO'.$identifier]->Contact = $_POST['Contact'];
        }
    }


    echo '<tr><td>' . _('Delivery Contact') . ':</td>
        <td><input type="text" readonly name="Contact" size="41"  value="' . $_SESSION['PO'.$identifier]->Contact . '"></td>
        </tr>';
    echo '<tr><td>' . _('Address') . '  :</td>
        <td><input type="text" name="DelAdd1" size="41" maxlength="40" readonly value="' . $_POST['DelAdd1'] . '"></td>
        </tr>';
    echo '<input type="hidden" name="DelAdd2" size="41" maxlength="40" value="' . $_POST['DelAdd2'] . '">';
    echo '<input type="hidden" name="DelAdd3" size="41" maxlength="40" value="' . $_POST['DelAdd3'] . '">';
    echo '<input type="hidden" name="DelAdd4" size="21" maxlength="20" value="' . $_POST['DelAdd4'] . '">';
    echo '<input type="hidden" name="DelAdd5" size="16" maxlength="15" value="' . $_POST['DelAdd5'] . '">';
    echo '<input type="hidden" name="DelAdd6" size="16" maxlength=15 value="' . $_POST['DelAdd6'] . '">';
    echo '<input type="hidden" name="Tel" size="31" maxlength="30" value="' . $_SESSION['PO'.$identifier]->Tel . '">';

    echo '<tr><td>' . _('Delivery By') . ':</td><td><select name="DeliveryBy">';

    $ShipperResult = DB_query("SELECT shipper_id, shippername FROM shippers",$db);
    
while ($ShipperRow=DB_fetch_array($ShipperResult)){
        if (isset($_POST['DeliveryBy']) and ($_POST['DeliveryBy'] == $ShipperRow['shipper_id'])) {
            echo '<option selected value="' . $ShipperRow['shipper_id'] . '">' . $ShipperRow['shippername'] . '</option>';
        } else {
            echo '<option value="' . $ShipperRow['shipper_id'] . '">' . $ShipperRow['shippername'] . '</option>';
        }
    }

    echo '</select></tr></table>';
      /* end of sub table */

    echo '</td><td>'; /*sub table nested */
    
    

    echo '<table class=selection width=100%><tr><td>' . _('Supplier Selection') . ':</td><td>
        <select name=Keywords >';//onChange="ReloadForm(form1.SearchSuppliers)"
if(isset($_GET['SupplierID']))
{$ss=$_GET['SupplierID'];

}else{
    $ss=$_SESSION['PO'.$identifier]->SupplierID;
}
    $SuppCoResult = DB_query("SELECT supplierid, suppname FROM suppliers  where supplierid='".$ss."' ORDER BY suppname",$db);

    while ( $SuppCoRow=DB_fetch_array($SuppCoResult)){
        if (($SuppCoRow['supplierid'] ==  $_POST['Keywords'] ) 
        or ($SuppCoRow['supplierid'] ==$_SESSION['PO'.$identifier]->SupplierID) 
        or ($SuppCoRow['supplierid'] ==  $_GET['SupplierID'] )) {
            echo '<option selected value="' . $SuppCoRow['supplierid'] . '">' . $SuppCoRow['suppname'] . '</option>';
        } else {
            echo '<option value="' . $SuppCoRow['supplierid'] . '">' . $SuppCoRow['suppname'] . '</option>';
        }
    }

    echo '</select> ';

        echo '<input type="submit" style="display:none" name="SearchSuppliers" value=' . _('Select Now') . '"></td></tr>';//<input type="submit" name="SearchSuppliers" value=' . _('Select Now') . '">

   
        
         
     if (!isset($_POST['Keywords']) OR $_POST['Keywords']=='' ){

      //  $_POST['Keywords'] = $_SESSION['UserStockLocation'];

        $sql = "SELECT * FROM suppliers";

        $LocnAddrResult = DB_query($sql,$db);
        if (DB_num_rows($LocnAddrResult)==1){
            $LocnRow = DB_fetch_array($LocnAddrResult);     
            $_POST['supplierid']=$LocnRow['supplierid'];
            $_POST['SuppDelAdd1'] = $LocnRow['address1'];
            $_POST['SuppDelAdd2'] = $LocnRow['address2'];
            $_POST['SuppDelAdd3'] = $LocnRow['address3'];
            $_POST['SuppDelAdd4'] = $LocnRow['address4'];
            $_POST['SuppDelAdd5'] = $LocnRow['address5'];
            $_POST['SuppDelAdd6'] = $LocnRow['address6'];
       //     $_POST['Tel'] = $LocnRow['tel'];
         //   $_POST['Contact'] = $LocnRow['contact'];

         //   $_SESSION['PO'.$identifier]->Location= $_POST['StkLocation'];
     echo"select from table".    $_SESSION['PO'.$identifier]->SupplierID= $_POST['supplierid'];
          $_SESSION['PO'.$identifier]->SupplierContact= $_POST['SupplierContact'];
            $_SESSION['PO'.$identifier]->SuppDelAdd1 = $_POST['SuppDelAdd1'];
            $_SESSION['PO'.$identifier]->SuppDelAdd2 = $_POST['SuppDelAdd2'];
            $_SESSION['PO'.$identifier]->SuppDelAdd3 = $_POST['SuppDelAdd3'];
            $_SESSION['PO'.$identifier]->SuppDelAdd4 = $_POST['SuppDelAdd4'];
            $_SESSION['PO'.$identifier]->SuppDelAdd5 = $_POST['SuppDelAdd5'];
            $_SESSION['PO'.$identifier]->SuppDelAdd6 = $_POST['SuppDelAdd6'];
//$_SESSION['PO'.$identifier]->Tel = $_POST['Tel'];
         //   $_SESSION['PO'.$identifier]->Contact = $_POST['Contact'];

        } 


    } elseif (isset($_POST['SearchSuppliers'])){

        $sql = "SELECT suppliers.supplierid, suppliers.suppname,
                    suppliers.currcode,
                    currencies.rate,
                    currencies.decimalplaces,
                    suppliers.paymentterms,
                    suppliers.address1,
                    suppliers.address2,
                    suppliers.address3,
                    suppliers.address4,
                    suppliers.address5,
                    suppliers.address6,
                    suppliers.telephone,
                    suppliers.port
                FROM suppliers INNER JOIN currencies
                ON suppliers.currcode=currencies.currabrev
                WHERE supplierid='" . $_POST['Keywords'] . "'";

        $LocnAddrResult = DB_query($sql,$db);
        if (DB_num_rows($LocnAddrResult)==1){
            $LocnRow = DB_fetch_array($LocnAddrResult);
            
            
            $_POST['supplierid']=$LocnRow['supplierid'];
                $_POST['SupplierName'] = $LocnRow['suppname'];
        $_POST['CurrCode'] = $LocnRow['currcode'];
        $_POST['CurrDecimalPlaces'] = $LocnRow['decimalplaces'];
        $_POST['ExRate'] = $LocnRow['rate'];
        $_POST['PaymentTerms']=    $LocnRow['paymentterms'];
        $_POST['SuppTel'] = $LocnRow['telephone'];
        $_POST['Port'] = $LocnRow['port'];
        $_POST['SuppDelAdd1'] = $LocnRow['address1'];
        $_POST['SuppDelAdd2'] = $LocnRow['address2'];
        $_POST['SuppDelAdd3'] = $LocnRow['address3'];
        $_POST['SuppDelAdd4'] = $LocnRow['address4'];
        $_POST['SuppDelAdd5'] = $LocnRow['address5'];
        $_POST['SuppDelAdd6'] = $LocnRow['address6'];
        
        $_SESSION['RequireSupplierSelection'] = 0;
        $_SESSION['PO'.$identifier]->SupplierName = $_POST['SupplierName'];
        $_SESSION['PO'.$identifier]->CurrCode = $_POST['CurrCode'];
        $_SESSION['PO'.$identifier]->CurrDecimalPlaces = $_POST['CurrDecimalPlaces'];
        $_SESSION['PO'.$identifier]->ExRate = $_POST['ExRate'];
        $_SESSION['PO'.$identifier]->PaymentTerms = $_POST['PaymentTerms'];
        $_SESSION['PO'.$identifier]->SuppTel = $_POST['SuppTel'];
        $_SESSION['PO'.$identifier]->Port = $_POST['Port'];
        $_SESSION['PO'.$identifier]->SupplierID= $_POST['supplierid'];
        $_SESSION['PO'.$identifier]->SupplierContact= $_POST['SupplierContact'];
        $_SESSION['PO'.$identifier]->SuppDelAdd1 = $_POST['SuppDelAdd1'];
        $_SESSION['PO'.$identifier]->SuppDelAdd2 = $_POST['SuppDelAdd2'];
        $_SESSION['PO'.$identifier]->SuppDelAdd3 = $_POST['SuppDelAdd3'];
        $_SESSION['PO'.$identifier]->SuppDelAdd4 = $_POST['SuppDelAdd4'];
        $_SESSION['PO'.$identifier]->SuppDelAdd5 = $_POST['SuppDelAdd5'];
        $_SESSION['PO'.$identifier]->SuppDelAdd6 = $_POST['SuppDelAdd6'];
     

        }
    }

    

   
  echo '<tr><td>' . _('Supplier Contact') . ':</td><td><input type="text" readonly name="SuppTel" size="31" maxlength="30" value="' . $_SESSION['PO'.$identifier]->SuppTel  . '"></td></tr>';
    echo '<tr><td>' . _('Address') . '  :</td>
        </td><td><input type="text" name="SuppDelAdd1" size="31" maxlength="40" readonly value="' . $_POST['SuppDelAdd1'] . '"></td>
        </tr>';
    echo '<input type="hidden" name="supplierid" size="41" maxlength="40" value="' . $_POST['supplierid'] . '">';
    echo '<input type="hidden" name="SuppDelAdd2" size="41" maxlength="40" value="' . $_POST['SuppDelAdd2'] . '">';
    echo '<input type="hidden" name="SuppDelAdd3" size="41" maxlength="40" value="' . $_POST['SuppDelAdd3'] . '">';
    echo '<input type="hidden" name="SuppDelAdd5" size="21" maxlength="20" value="' . $_POST['SuppDelAdd5'] . '">';
    echo '<input type="hidden" name="SuppDelAdd4" size="41" maxlength="40" value="' . $_POST['SuppDelAdd4'] . '">';
    echo '<input type="hidden" name="SuppTel" size="31" maxlength="30" value="' . $_SESSION['PO'.$identifier]->SuppTel  . '">';

    $result=DB_query("SELECT terms, termsindicator FROM paymentterms", $db);

    echo '<tr><td>' . _('Payment Terms') . ':</td>
            <td><select name="PaymentTerms">';

    while ($myrow = DB_fetch_array($result)) {
        if ($myrow['termsindicator']==$_SESSION['PO'.$identifier]->PaymentTerms) {
            echo '<option selected value="'. $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
        } else {
            echo '<option value="' . $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
        } //end while loop
    }
    DB_data_seek($result, 0);
    echo '</select></td></tr>';

    $result=DB_query("SELECT loccode, 
                            locationname 
                        FROM locations WHERE loccode='" . $_SESSION['PO'.$identifier]->Port."'", $db);
    $myrow = DB_fetch_array($result);
    $_POST['Port'] = $myrow['locationname'];

    echo '<input type="hidden" name="Port" size="31" value="' . $_POST['Port'] . '">';

    if ($_SESSION['PO'.$identifier]->CurrCode != $_SESSION['CompanyRecord']['currencydefault']) {
        echo '<input type=hidden name="ExRate" value="'.$_POST['ExRate'].'" class="number" size=11>';
    } else {
        echo '<input type=hidden name="ExRate" value="1">';
    }
    echo '</td></tr></table>'; /*end of sub table */
 
   

    echo '</table>';

    echo '</td></tr></table><br />'; /* end of main table */

   // echo '<div class="centre"><input type="submit" name="EnterLines" value="' . _('Enter Line Items') . '"></div>';






if (isset($_POST['NonStockOrder'])) {

	echo '<br /><table class="selection"><tr>
				<td>' . _('Item Description') . '</td>';
	echo '<td><input type=text name=ItemDescription size=40></td></tr>';
	echo '<tr><td>' . _('General Ledger Code') . '</td>';
	echo '<td><select name="GLCode">';
	$sql="SELECT accountcode,
				  accountname
				FROM chartmaster
				ORDER BY accountcode ASC";

	$result=DB_query($sql, $db);
	while ($myrow=DB_fetch_array($result)) {
		echo '<option value="'.$myrow['accountcode'].'">'.$myrow['accountcode'].' - '.$myrow['accountname'].'</option>';
	}
	echo '</select></td></tr>';
	echo '<tr><td>'._('OR Asset ID'). '</td>
						<td><select name="AssetID">';
	$AssetsResult = DB_query("SELECT assetid, description, datepurchased FROM fixedassets ORDER BY assetid DESC",$db);
	echo '<option selected value="Not an Asset">' . _('Not an Asset') . '</option>';
	while ($AssetRow = DB_fetch_array($AssetsResult)){
		if ($AssetRow['datepurchased']=='0000-00-00'){
			$DatePurchased = _('Not yet purchased');
		} else {
			$DatePurchased = ConvertSQLDate($AssetRow['datepurchased']);
		}
		echo '<option value="' . $AssetRow['assetid'] . '">'  . $AssetRow['assetid'] . ' - '.  $DatePurchased . ' - ' . $AssetRow['description'] . '</option>';
	}

	echo'</select><a href="FixedAssetItems.php" target=_blank>'. _('New Fixed Asset') . '</a></td>
				<tr><td>'._('Quantity to purchase').'</td>
						<td><input type="text" class="number" name="Qty" size="10" value="1"></td></tr>
				<tr><td>'._('Price per item').'</td>
						<td><input type="text" class="number" name="Price" size="10"></td></tr>
				<tr><td>'._('Unit').'</td>
						<td><input type="text" name="SuppliersUnit" size="10" value="' . _('each') . '"></td></tr>
				<tr><td>'._('Delivery Date').'</td>
						<td><input type="text" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" name="ReqDelDate" size=11 value="'.$_SESSION['PO'.$identifier]->DeliveryDate .'"></td></tr>';
	echo '</table>';
	//echo '<div class=centre><input type=submit name="EnterLine" value="Enter Item"></div>';
}

/* Now show the stock item selection search stuff below */


if (!isset($_GET['Edit'])) {
	$sql="SELECT categoryid,
				categorydescription
		FROM stockcategory
		WHERE stocktype<>'L'
		AND stocktype<>'D'
		ORDER BY categorydescription";
	$ErrMsg = _('The supplier category details could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the category details but failed was');
	$result1 = DB_query($sql,$db,$ErrMsg,$DbgMsg);
echo'<input type=hidden name="StockCat" value="All" ';
	
	echo'	<br />
		
		<div class="centre"><input type="submit" name="Search" value="' . _('Search Now') . '">
	
		</div><br />';   /* <input type="submit" name="NonStockOrder" value="' . _('Order a non stock item') . '">
        <a target="_blank" href="'.$rootpath.'/Stocks.php">' . _('Create a New Stock Item') . '</a>
*/
	$PartsDisplayed =0;//
}

if (isset($SearchResult)) {

	echo '<table cellpadding="1" colspan="7" class="selection">';

	$TableHeader = '<tr>
				<th>' . _('Code')  . '</th>
				<th>' . _('Description') . '</th>
				<th>' . _('Our Units') . '</th>
				<th>' . _('Conversion') . '<br />' ._('Factor') . '</th>
				<th>' . _('Supplier/Order') . '<br />' .  _('Units') . '</th>
				<th colspan=2><a href="#end">'._('Go to end of list').'</a></th>
				</tr>';
	echo $TableHeader;

	$j = 1;
	$k=0; //row colour counter

	while ($myrow=DB_fetch_array($SearchResult)) {

		if ($k==1){
			echo '<tr class="EvenTableRows">';
			$k=0;
		} else {
			echo '<tr class="OddTableRows">';
			$k=1;
		}

		$FileName = $myrow['stockid'] . '.jpg';
		if (file_exists( $_SESSION['part_pics_dir'] . '/' . $FileName) ) {
			$ImageSource = '<img src="'.$rootpath . '/' . $_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.jpg" width="50" height="50">';
		} else {
			$ImageSource = '<i>'._('No Image').'</i>';
		}

		/*Get conversion factor and supplier units if any */
		$sql =  "SELECT purchdata.conversionfactor,
						purchdata.suppliersuom
					FROM purchdata
					WHERE purchdata.supplierno='" . $_SESSION['PO'.$identifier]->SupplierID . "' 
                    AND purchdata.stockid='" . $myrow['stockid'] . "'";//" . $myrow['stockid'] . "
		$ErrMsg = _('Could not retrieve the purchasing data for the item');
		$PurchDataResult = DB_query($sql,$db,$ErrMsg);
		
		if (DB_num_rows($PurchDataResult)>0) {
			$PurchDataRow = DB_fetch_array($PurchDataResult);
			$OrderUnits=$PurchDataRow['suppliersuom'];
			$ConversionFactor =$PurchDataRow['conversionfactor'];
		} else {
			$OrderUnits=$myrow['units'];
			$ConversionFactor =1;
		}
		echo '<td>' . $myrow['stockid'] .'</td>
			<td>' . $myrow['description'] .'</td>
			<td>' . $myrow['units'] .'</td>
			<td class="number">' . $ConversionFactor .'</td>
			<td>' . $OrderUnits . '</td>
			<td>' . $ImageSource . '</td>
			<td><input class="number" type="text" size="6" value="0" name="NewQty' . $myrow['stockid'] . '"></td>
			</tr>';
			
		$PartsDisplayed++;
		if ($PartsDisplayed == $Maximum_Number_Of_Parts_To_Show){
			break;
		}
#end of page full new headings if
	}
#end of while loop
	echo '</table>';
	if ($PartsDisplayed == $Maximum_Number_Of_Parts_To_Show){
		/*$Maximum_Number_Of_Parts_To_Show defined in config.php */
		prnMsg( _('Only the first') . ' ' . $Maximum_Number_Of_Parts_To_Show . ' ' . _('can be displayed') . '. ' .
			_('Please restrict your search to only the parts required'),'info');
	}
	echo '<a name="end"></a><br /><div class="centre"><input type="submit" name="NewItem" value="Order some"></div>';
}#end if SearchResults to show

echo '</form>';
include('includes/footer.inc');
?>