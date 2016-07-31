<?php

/* $Id: SelectCompletedOrder.php 4551 2011-04-16 06:20:56Z daintree $*/

include('includes/session.inc');

$title = _('Search All Sales Orders');

include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
	'" alt="" />' . ' ' . _('Search Sales Orders') . '</p>';

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/*if (isset($_POST['completed'])) {
	$Completed="=1";
	$ShowChecked="checked='checked'";
} else {
	$Completed=">=0";
	$ShowChecked='';
}  */


    if($_POST['completed']==0){
     $Completed=">=0";  
    }elseif($_POST['completed']==1) {
      $Completed="=0";   
     }elseif($_POST['completed']==2) {
      $Completed="=1";   
     }



if (isset($_GET['SelectedStockItem'])){
	$SelectedStockItem = $_GET['SelectedStockItem'];
} elseif (isset($_POST['SelectedStockItem'])){
	$SelectedStockItem = $_POST['SelectedStockItem'];
}
if (isset($_GET['OrderNumber'])){
	$OrderNumber = $_GET['OrderNumber'];
} elseif (isset($_POST['OrderNumber'])){
	$OrderNumber = $_POST['OrderNumber'];
}
if (isset($_GET['CustomerRef'])){
	$CustomerRef = $_GET['CustomerRef'];
} elseif (isset($_POST['CustomerRef'])){
	$CustomerRef = $_POST['CustomerRef'];
}
if (isset($_GET['SelectedCustomer'])){
	$SelectedCustomer = $_GET['SelectedCustomer'];
} elseif (isset($_POST['SelectedCustomer'])){
	$SelectedCustomer = $_POST['SelectedCustomer'];
}

        if (isset($_POST['enqtype'])){
            if($_POST['enqtype']==2){
                $enqtype='D';
            }elseif($_POST['enqtype']==3){
                $enqtype='C';
            }elseif($_POST['enqtype']==4){
                $enqtype='L';
            }elseif($_POST['enqtype']==1){  
                $enqtype='';
            } 
        }

if (isset($SelectedStockItem) and $SelectedStockItem==''){
	unset($SelectedStockItem);
}
if (isset($OrderNumber) and $OrderNumber==''){
	unset($OrderNumber);
}
if (isset($CustomerRef) and $CustomerRef==''){
	unset($CustomerRef);
}
if (isset($SelectedCustomer) and $SelectedCustomer==''){
	unset($SelectedCustomer);
}
if (isset($_POST['ResetPart'])) {
		unset($SelectedStockItem);
}

if (isset($OrderNumber)) {
	echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/sales.png" title="' . _('Sales Order') .
		'" alt="" />' . ' ' . _('Order Number') . ' - ' . $OrderNumber . '</p>';
	if (strlen($_SESSION['UserBranch'])>1){
   	   echo _('For customer') . ': ' . $SelectedCustomer;
	   echo '<input type="hidden" name="SelectedCustomer" value="' . $SelectedCustomer .'" />';
        }
} elseif (isset($CustomerRef)) {
	echo _('Customer Ref') . ' - ' . $CustomerRef;
	if (strlen($_SESSION['UserBranch'])>1){
   	   echo ' ' . _('and for customer') . ': ' . $SelectedCustomer .' ' . _('and') . ' ';
	   echo '<input type=hidden name="SelectedCustomer" value="' .$SelectedCustomer .'" />';
        }
} else {
	if (isset($SelectedCustomer)) {
		echo _('For customer') . ': ' . $SelectedCustomer .' ' . _('and') . ' ';
		echo '<input type=hidden name="SelectedCustomer" value="'.$SelectedCustomer.'" />';
	}

	if (isset($SelectedStockItem)) {

		$PartString = _('for the part') . ': <b>' . $SelectedStockItem . '</b> ' . _('and') . ' ' .
			'<input type=hidden name="SelectedStockItem" value="'.$SelectedStockItem.'" />';

	}
}

if (isset($_POST['SearchParts']) and $_POST['SearchParts']!=''){

	if ($_POST['Keywords']!='' AND $_POST['StockCode']!='') {
		echo _('Stock description keywords have been used in preference to the Stock code extract entered');
	}
	if ($_POST['Keywords']!='') {
		//insert wildcard characters in spaces
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		if (isset($_POST['completed'])) {
			$SQL = "SELECT stockmaster.stockid,
				stockmaster.description,
				SUM(locstock.quantity) AS qoh,
				SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qoo,
				stockmaster.units,
				SUM(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) AS qdem
			FROM (((stockmaster LEFT JOIN salesorderdetails on stockmaster.stockid = salesorderdetails.stkcode)
				 LEFT JOIN locstock ON stockmaster.stockid=locstock.stockid)
				 LEFT JOIN purchorderdetails on stockmaster.stockid = purchorderdetails.itemcode)
			WHERE salesorderdetails.completed =1
			AND stockmaster.description " . LIKE . " '" . $SearchString. "'
			AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
			GROUP BY stockmaster.stockid,
				stockmaster.description,
				stockmaster.units
			ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
				stockmaster.description,
				SUM(locstock.quantity) AS qoh,
				SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qoo,
				stockmaster.units,
				SUM(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) AS qdem
			FROM (((stockmaster LEFT JOIN salesorderdetails on stockmaster.stockid = salesorderdetails.stkcode)
				 LEFT JOIN locstock ON stockmaster.stockid=locstock.stockid)
				 LEFT JOIN purchorderdetails on stockmaster.stockid = purchorderdetails.itemcode)
			WHERE stockmaster.description " . LIKE . " '" . $SearchString. "'
			AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
			GROUP BY stockmaster.stockid,
				stockmaster.description,
				stockmaster.units
			ORDER BY stockmaster.stockid";
		}

	} elseif ($_POST['StockCode']!=''){

		if (isset($_POST['completed'])) {
			$SQL = "SELECT stockmaster.stockid,
				stockmaster.description,
				SUM(locstock.quantity) AS qoh,
				SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qoo,
				SUM(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) AS qdem,
				stockmaster.units
			FROM (((stockmaster LEFT JOIN salesorderdetails on stockmaster.stockid = salesorderdetails.stkcode)
				 LEFT JOIN locstock ON stockmaster.stockid=locstock.stockid)
				 LEFT JOIN purchorderdetails on stockmaster.stockid = purchorderdetails.itemcode)
			WHERE salesorderdetails.completed =1
			AND stockmaster.stockid " . LIKE . " '%" . $_POST['StockCode'] . "%'
			AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
			GROUP BY stockmaster.stockid,
				stockmaster.description,
				stockmaster.units
			ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
				stockmaster.description,
				SUM(locstock.quantity) AS qoh,
				SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qoo,
				SUM(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) AS qdem,
				stockmaster.units
			FROM (((stockmaster LEFT JOIN salesorderdetails on stockmaster.stockid = salesorderdetails.stkcode)
				 LEFT JOIN locstock ON stockmaster.stockid=locstock.stockid)
				 LEFT JOIN purchorderdetails on stockmaster.stockid = purchorderdetails.itemcode)
			WHERE stockmaster.stockid " . LIKE  . " '%" . $_POST['StockCode'] . "%'
			AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
			GROUP BY stockmaster.stockid,
				stockmaster.description,
				stockmaster.units
			ORDER BY stockmaster.stockid";
		}

	} elseif ($_POST['StockCode']=='' AND $_POST['Keywords']=='' AND $_POST['StockCat']!='') {

		if (isset($_POST['completed'])) {
			$SQL = "SELECT stockmaster.stockid,
				stockmaster.description,
				SUM(locstock.quantity) AS qoh,
				SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qoo,
				SUM(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) AS qdem,
				stockmaster.units
			FROM (((stockmaster LEFT JOIN salesorderdetails on stockmaster.stockid = salesorderdetails.stkcode)
				 LEFT JOIN locstock ON stockmaster.stockid=locstock.stockid)
				 LEFT JOIN purchorderdetails on stockmaster.stockid = purchorderdetails.itemcode)
			WHERE salesorderdetails.completed=1
			AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
			GROUP BY stockmaster.stockid,
				stockmaster.description,
				stockmaster.units
			ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
				stockmaster.description,
				SUM(locstock.quantity) AS qoh,
				SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qoo,
				SUM(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) AS qdem,
				stockmaster.units
			FROM (((stockmaster LEFT JOIN salesorderdetails on stockmaster.stockid = salesorderdetails.stkcode)
				 LEFT JOIN locstock ON stockmaster.stockid=locstock.stockid)
				 LEFT JOIN purchorderdetails on stockmaster.stockid = purchorderdetails.itemcode)
			WHERE stockmaster.categoryid='" . $_POST['StockCat'] . "'
			GROUP BY stockmaster.stockid,
				stockmaster.description,
				stockmaster.units
			ORDER BY stockmaster.stockid";
		}
	}
    

	if (strlen($SQL)<2){
		prnMsg(_('No selections have been made to search for parts') . ' - ' . _('choose a stock category or enter some characters of the code or description then try again'),'warn');
	} else {

		$ErrMsg = _('No stock items were returned by the SQL because');
		$DbgMsg = _('The SQL used to retrieve the searched parts was');
		$StockItemsResult = DB_query($SQL,$db);

		if (DB_num_rows($StockItemsResult)==1){
		  	$myrow = DB_fetch_row($StockItemsResult);
		  	$SelectedStockItem = $myrow[0];
			$_POST['SearchOrders']='True';
		  	unset($StockItemsResult);
		  	echo '<br />' . _('For the part') . ': ' . $SelectedStockItem . ' ' . _('and') . ' <input type="hidden" name="SelectedStockItem" value="' . $SelectedStockItem . '">';
		}
	}
} else if (isset($_POST['SearchOrders']) AND ((Is_Date($_POST['OrdersAfterDate1'])==1) AND (Is_Date($_POST['OrdersAfterDate2'])==1))) {

	//figure out the SQL required from the inputs available
	if (isset($OrderNumber)) {                       
          if (isset($SelectedCustomer)) {            
          		$SQL = "SELECT salesorders.orderno,
					debtorsmaster.name,
					custbranch.brname,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.deliverydate,
					salesorders.deliverto, SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue
				FROM salesorders,
					salesorderdetails,
					debtorsmaster,
					custbranch
				WHERE salesorders.orderno = salesorderdetails.orderno
				AND salesorders.branchcode = custbranch.branchcode
				AND salesorders.debtorno = debtorsmaster.debtorno
				AND debtorsmaster.debtorno = custbranch.debtorno
				AND salesorders.debtorno='" . $SelectedCustomer ."'
				AND salesorders.orderno='". $OrderNumber ."'
				AND salesorders.quotation=0
				AND salesorderdetails.completed".$Completed."
                AND salesorders.debtorno like '%". $enqtype."%'
				GROUP BY salesorders.orderno,
					debtorsmaster.name,
					custbranch.brname,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.deliverydate,
					salesorders.deliverto
				ORDER BY salesorders.orderno";
           } else { //Set order number and SelectedCustomer not set
			$SQL = "SELECT salesorders.orderno,
					debtorsmaster.name,
                    debtorsmaster.address1,
                    debtorsmaster.address2,
                    debtorsmaster.address3,
					custbranch.brname,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.deliverydate,
					salesorders.deliverto, SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue
				FROM salesorders,
					salesorderdetails,
					debtorsmaster,
					custbranch
				WHERE salesorders.orderno = salesorderdetails.orderno
				AND salesorders.branchcode = custbranch.branchcode
				AND salesorders.debtorno = debtorsmaster.debtorno
				AND debtorsmaster.debtorno = custbranch.debtorno
				AND salesorders.orderno='". $OrderNumber ."'
				AND salesorders.quotation=0
				AND salesorderdetails.completed " . $Completed ."
                AND salesorders.debtorno like '%". $enqtype."%' 
				GROUP BY salesorders.orderno,
					debtorsmaster.name,
					custbranch.brname,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.deliverydate,
					salesorders.deliverto
				ORDER BY salesorders.orderno";
              }
	} elseif (isset($CustomerRef)) {
                    if (isset($SelectedCustomer)) {
			$SQL = "SELECT salesorders.orderno,
					debtorsmaster.name,
					custbranch.brname,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.deliverydate,
					salesorders.deliverto, SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue
				FROM salesorders,
					salesorderdetails,
					debtorsmaster,
					custbranch
				WHERE salesorders.orderno = salesorderdetails.orderno
				AND salesorders.branchcode = custbranch.branchcode
				AND salesorders.debtorno = debtorsmaster.debtorno
				AND debtorsmaster.debtorno = custbranch.debtorno
				AND salesorders.debtorno='" . $SelectedCustomer ."'
				AND salesorders.customerref like '%". $CustomerRef."%'
				AND salesorders.quotation=0
				AND salesorderdetails.completed".$Completed."
                AND salesorders.debtorno like '%". $enqtype."%'
				GROUP BY salesorders.orderno,
					debtorsmaster.name,
					custbranch.brname,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.deliverydate,
					salesorders.deliverto
				ORDER BY salesorders.orderno";
                       } else { //customer not selected
                         $SQL = "SELECT salesorders.orderno,
					debtorsmaster.name,
					custbranch.brname,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.deliverydate,
					salesorders.deliverto, SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue
				FROM salesorders,
					salesorderdetails,
					debtorsmaster,
					custbranch
				WHERE salesorders.orderno = salesorderdetails.orderno
				AND salesorders.branchcode = custbranch.branchcode
				AND salesorders.debtorno = debtorsmaster.debtorno
				AND debtorsmaster.debtorno = custbranch.debtorno
				AND salesorders.customerref like '%". $CustomerRef."%'
				AND salesorders.quotation=0
				AND salesorderdetails.completed".$Completed."
                AND salesorders.debtorno like '%". $enqtype."%'
				GROUP BY salesorders.orderno,
					debtorsmaster.name,
					custbranch.brname,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.deliverydate,
					salesorders.deliverto
				ORDER BY salesorders.orderno";
                       }

	} else {
		$DateAfterCriteria1 = FormatDateforSQL($_POST['OrdersAfterDate1']);
        $DateAfterCriteria2 = FormatDateforSQL($_POST['OrdersAfterDate2']);
                                                                            
		if (isset($SelectedCustomer) AND !isset($OrderNumber) AND !isset($CustomerRef)) {
                                                                            
			if (isset($SelectedStockItem)) {
				$SQL = "SELECT salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverydate,
						salesorders.deliverto, SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue
					FROM salesorders,
						salesorderdetails,
						debtorsmaster,
						custbranch
					WHERE salesorders.orderno = salesorderdetails.orderno
					AND salesorders.branchcode = custbranch.branchcode
					AND salesorders.debtorno = debtorsmaster.debtorno
					AND debtorsmaster.debtorno = custbranch.debtorno
					AND salesorderdetails.stkcode='". $SelectedStockItem ."'
					AND salesorders.debtorno='" . $SelectedCustomer ."'
					AND salesorders.orddate BETWEEN '" . $DateAfterCriteria1 ."' AND '" . $DateAfterCriteria2 ."'
					AND salesorders.quotation=0
					AND salesorderdetails.completed".$Completed."
                    AND salesorders.debtorno like '%". $enqtype."%'
					GROUP BY salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverydate,
						salesorders.deliverto
					ORDER BY salesorders.orderno";
			} else {                                              
				$SQL = "SELECT salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverto,
						salesorders.deliverydate, SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue
					FROM salesorders,
						salesorderdetails,
						debtorsmaster,
						custbranch
					WHERE salesorders.orderno = salesorderdetails.orderno
					AND salesorders.debtorno = debtorsmaster.debtorno
					AND salesorders.branchcode = custbranch.branchcode
					AND debtorsmaster.debtorno = custbranch.debtorno
					AND salesorders.debtorno='" . $SelectedCustomer . "'
					AND salesorders.orddate BETWEEN '" . $DateAfterCriteria1 ."' AND '" . $DateAfterCriteria2 ."'
					AND salesorders.quotation=0
					AND salesorderdetails.completed".$Completed."
                    AND salesorders.debtorno like '%". $enqtype."%'
					GROUP BY salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverydate,
						salesorders.deliverto
					ORDER BY salesorders.orderno";
			}
		} else { //no customer selected
			if (isset($SelectedStockItem)) {
				$SQL = "SELECT salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverto,
						salesorders.deliverydate, SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue
					FROM salesorders,
						salesorderdetails,
						debtorsmaster,
						custbranch
					WHERE salesorders.orderno = salesorderdetails.orderno
					AND salesorders.debtorno = debtorsmaster.debtorno
					AND salesorders.branchcode = custbranch.branchcode
					AND debtorsmaster.debtorno = custbranch.debtorno
					AND salesorderdetails.stkcode='". $SelectedStockItem ."'
					AND salesorders.orddate BETWEEN '" . $DateAfterCriteria1 ."' AND '" . $DateAfterCriteria2 ."'
					AND salesorders.quotation=0
					AND salesorderdetails.completed".$Completed."
                    AND salesorders.debtorno like '%". $enqtype."%'
					GROUP BY salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverydate,
						salesorders.deliverto
					ORDER BY salesorders.orderno";
			} else {                                       
				$SQL = "SELECT salesorders.orderno,
						debtorsmaster.name,
                        debtorsmaster.address1,
                        debtorsmaster.address2,
                        debtorsmaster.address3,
						custbranch.brname,
                        salesorders.debtorno,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverto,
						salesorders.deliverydate, SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue
					FROM salesorders,
						salesorderdetails,
						debtorsmaster,
						custbranch
					WHERE salesorders.orderno = salesorderdetails.orderno
					AND salesorders.debtorno = debtorsmaster.debtorno
					AND salesorders.branchcode = custbranch.branchcode
					AND debtorsmaster.debtorno = custbranch.debtorno
					AND salesorders.orddate BETWEEN '" . $DateAfterCriteria1 ."' AND '" . $DateAfterCriteria2 ."'
					AND salesorders.quotation=0
					AND salesorderdetails.completed".$Completed."
                    AND salesorders.debtorno like '%". $enqtype."%'";
             if($_POST['office']!=0 )  
             {     
            $SQL.=" AND salesorders.leadid IN (SELECT leadid FROM bio_leadtask WHERE taskid=5 AND taskcompletedstatus=1 AND viewstatus=1 AND teamid IN (SELECT teamid FROM bio_leadteams WHERE office_id='".$_POST['office']."'))"; 
			 }
            $SQL.=" GROUP BY salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverydate,
						salesorders.deliverto
					ORDER BY salesorders.orderno";
			}       
		} //end selected customer
	} //end not order number selected
  //  echo $SQL;
	$SalesOrdersResult = DB_query($SQL,$db);

	if (DB_error_no($db) !=0) {
		prnMsg( _('No orders were returned by the SQL because') . ' ' . DB_error_msg($db), 'info');
		echo "<br />$SQL";
	}

}//end of which button clicked options

if ((!isset($_POST['OrdersAfterDate1']) OR $_POST['OrdersAfterDate1'] == '' OR ! Is_Date($_POST['OrdersAfterDate1'])) AND (!isset($_POST['OrdersAfterDate2']) OR $_POST['OrdersAfterDate2'] == '' OR ! Is_Date($_POST['OrdersAfterDate2']))){
	
    if(date('n')>=4){
    $_POST['OrdersAfterDate1'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,04,01,Date('Y')));  
    }else{
    $month_diff = 12 - abs((date('n')-4)); 
    $_POST['OrdersAfterDate1'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m')-$month_diff,01,Date('Y'))); 
    }
    $_POST['OrdersAfterDate2'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m'),Date('d'),Date('Y')));        
}
echo '<table class="selection">';     
  
if (isset($PartString)) {
	echo '<tr><td>' . $PartString . '</td>';
} else {
	echo '<tr><td></td>';
}
/*
echo '<td>' . _('Order Number') . ':</td>
	<td><input type="text" name="OrderNumber" maxlength =8 size=9 value ="' . $_POST['OrderNumber'] . '"></td>
	<td>' . _('for all orders placed after') . ': </td>
	<td><input type="text" class="date" alt="' . $_SESSION['DefaultDateFormat'] .'"  name="OrdersAfterDate" maxlength =10 size=11 value="' . $_POST['OrdersAfterDate'] . '"></td>
	<td><input type="submit" name="SearchOrders" value="' . _('Search Orders') . '"></td></tr>';
echo '<tr>
		<td></td>
		<td>' . _('Customer Ref') . ':</td><td><input type="text" name="CustomerRef" maxlength =8 size=9></td>
		<td></td><td colspan=2><input type="checkbox" ' . $ShowChecked . ' name="completed" />' . _('Show Completed orders only') . '</td></tr>';
    
 
*/                      
/*      echo '<td>' . _('Order Number') . ':</td>
      <td><input type="text" name="OrderNumber" maxlength =8 size=9 value ="' . $_POST['OrderNumber'] . '"></td>
      <td>' . _('for all orders placed between') . ': </td>
      <td><input type="text" class="date" alt="' . $_SESSION['DefaultDateFormat'] .'"  name="OrdersAfterDate1" maxlength =10 size=11 value="' . $_POST['OrdersAfterDate1'] . '"></td>  
      <td><input type="text" class="date" alt="' . $_SESSION['DefaultDateFormat'] .'"  name="OrdersAfterDate2" maxlength =10 size=11 value="' . $_POST['OrdersAfterDate2'] . '"></td>
      <td><input type="submit" name="SearchOrders" value="' . _('Search Orders') . '"></td></tr>';
echo '<tr>
      <td></td> 
      <td>' . _('Enquiry Type') . ':</td>
          <td><select name=enqtype>
              <option value=0></option>
              <option value=1>ALL</option>
              <option value=2>Domestic</option>
              <option value=3>Institution</option>
          </select></td>      
      <td></td><td colspan=2><input type="checkbox" ' . $ShowChecked . ' name="completed" />' . _('Show Completed orders only') . '</td></tr>';         
  */
       //<td><input type="text" name="OrderNumber" maxlength =8 size=9 value ="' . $_POST['OrderNumber'] . '"></td>
 echo'
      
      <td>' . _('Period From') . ' </td>
      <td><input type="text" class="date" alt="' . $_SESSION['DefaultDateFormat'] .'"  name="OrdersAfterDate1" maxlength =10 size=11 value="' . $_POST['OrdersAfterDate1'] . '"></td>  
      <td>To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="date" alt="' . $_SESSION['DefaultDateFormat'] .'"  name="OrdersAfterDate2" maxlength =10 size=11 value="' . $_POST['OrdersAfterDate2'] . '"></td>  
      <td>' . _('Register Type') . ' </td>';
 if((isset($_POST['regtype'])) AND ($_POST['regtype']!=0))
 {
 echo'<td><select name=regtype>';
     if($_POST['regtype']==1){
        echo'<option value=1>Consolidated</option>';
        echo'<option value=2>Detailed</option>'; 
     }elseif($_POST['regtype']==2){  
        echo'<option value=2>Detailed</option>';
        echo'<option value=1>Consolidated</option>';  
     }
 echo'</select></td>';     
 }else{     
 echo'<td><select name=regtype>
              <option value=2>Detailed</option>
              <option value=1>Consolidated</option>
      </select></td>';
 }  
      echo'<td align=center><input type="submit" name="SearchOrders" value="' . _('Search Orders') . '"></td></tr>';
      
echo '<tr>
      <td></td> 
      <td>' . _('Enquiry Type') . ':</td>
          <td><select name=enqtype>
              <option value=1>ALL</option>
              <option value=2>Domestic</option>
              <option value=3>Institution</option>
              <option value=4>LSGD</option>
          </select></td>';  
          
   echo '<td>Office<select name="office" id="office" style="width:120px">';
   echo '<option value=0>ALL</option>'; 
   $sql1="select * from bio_office";
   $result1=DB_query($sql1,$db);
   while($row1=DB_fetch_array($result1))
   {
       if ($row1['id']==$_POST['office'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['id'] . '">'.$row1['office'];
       echo '</option>';
   }
   echo '</select></td>';           
          
                 
echo'     <td>' . _('Outstanding Status') . ':</td> 
          <td><select name=completed>
              <option value=0>ALL</option>
              <option value=1>with outstanding</option>
              <option value=2>without outstanding</option>
          </select></td>';   
          
//   echo '<td>Project Status<select name="plantstatus" id="plantstatus" style="width:120px">';
//   echo '<option value=0>ALL</option>'; 
//   $sql1="select * from bio_projectStatus";
//   $result1=DB_query($sql1,$db);
//   while($row1=DB_fetch_array($result1))
//   {
//       if ($row1['id']==$_POST['plantstatus'])
//       {
//         echo '<option selected value="';
//       } else 
//       { 
//           echo '<option value="'; 
//       }
//       echo $row1['id'] . '">'.$row1['plant_status'];
//       echo '</option>';
//   }
//   echo '</select></td>';            
                             
                          
echo '</table>';

if (!isset($SelectedStockItem)) {
	$result1 = DB_query("SELECT categoryid, 
							categorydescription 
						FROM stockcategory 
						ORDER BY categorydescription",$db);

   echo '<br />';
   echo '<div style="display:none" class="page_help_text"><font size=1>' . _('To search for sales orders for a specific part use the part selection facilities below') . '   </font></div>';
   echo '<br /><table style="display:none" class=selection>';
   echo '<tr><td><font size=1>' . _('Select a stock category') . ':</font>';
   echo '<select name="StockCat">';

	while ($myrow1 = DB_fetch_array($result1)) {
		if (isset($_POST['StockCat']) and $myrow1['categoryid'] == $_POST['StockCat']){
			echo '<option selected value="' .  $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
		} else {
			echo '<option value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
		}
	}

   echo '</select>';
   echo '<td><font size=1>' . _('Enter text extracts in the description') . ':</font></td>';
   echo '<td><input type="text" name="Keywords" size=20 maxlength=25></td></tr>';
   echo '<tr><td></td>';
   echo '<td><font size=3><b> ' ._('OR') . ' </b></font><font size=1>' . _('Enter extract of the Stock Code') . ':</font></td>';
   echo '<td><input type="text" name="StockCode" size=15 maxlength=18></td>';
   echo '</tr>';
   echo '<tr><td colspan=4><div class=centre><input type="submit" name="SearchParts" value="' . _('Search Parts Now') . '">';

   if (count($_SESSION['AllowedPageSecurityTokens'])>1){
		echo '<input type=submit name="ResetPart" value="' . _('Show All') . '"></div>';
   }
   echo '</td></tr></table>';

}

If (isset($StockItemsResult)) {

	echo '<br /><table cellpadding=2 colspan=7 class=selection>';

	$TableHeadings = '<tr><th>' . _('Code') . '</th>
						<th>' . _('Description') . '</th>
						<th>' . _('On Hand') . '</th>
						<th>' . _('Purchase Orders') . '</th>
						<th>' . _('Sales Orders') . '</th>
						<th>' . _('Units') . '</th></tr>';

	echo $TableHeadings;

	$j = 1;
	$k=0; //row colour counter

	while ($myrow=DB_fetch_array($StockItemsResult)) {

		if ($k==1){
			echo '<tr class="EvenTableRows">';
			$k=0;
		} else {
			echo '<tr class="OddTableRows">';
			$k++;
		}

		printf('<td><font size=1><input type="submit" name="SelectedStockItem" value="%s" /></font></td>
			<td><font size=1>%s</font></td>
			<td class=number><font size=1>%s</font></td>
			<td class=number><font size=1>%s</font></td>
			<td class=number><font size=1>%s</font></td>
			<td><font size=1>%s</font></td></tr>',
			$myrow['stockid'],
			$myrow['description'],
			$myrow['qoh'],
			$myrow['qoo'],
			$myrow['qdem'],
			$myrow['units']);

//end of page full new headings if
	}
//end of while loop

	echo '</table>';

}
//end if stock search results to show

If (isset($SalesOrdersResult)) {

/*show a table of the orders returned by the SQL */

	echo '<br /><table cellpadding=2 colspan=6 width=65% class=selection>';
        $title="Sale order ";
        if($_POST['OrdersAfterDate1']!=NULL){
        $title.=' from '.$_POST['OrdersAfterDate1'];
        }
        if($_POST['OrdersAfterDate2']!=NULL){
        $title.=' to '.$_POST['OrdersAfterDate2'];        
        }
if($_POST['enqtype']==1){$title.=' of ALL';}
elseif($_POST['enqtype']==2){$title.=' of Domestic';}
elseif($_POST['enqtype']==3){$title.=' of Istitutional'; }
elseif($_POST['enqtype']==4){$title.=' of LSGD';}         
if($_POST['office']!=NULL){
        $sql="SELECT * FROM bio_office where id=".$_POST['office'];
        $result=DB_query($sql,$db);
        $myrow1=DB_fetch_array($result);
        $title.=' , '.$myrow1['office'];
        }

        echo "<tr><td colspan='8'><font size='-1'>".$title."</font></td></tr>";
if((isset($_POST['regtype'])) AND ($_POST['regtype']==2))
{  
	$tableheader = '<tr><th style=width:50px;>' . _('Sl.No') . '</th>
                        <th style=width:100px;>' . _('Order Date') . '</th>  
                        <th style=width:300px;>' . _('Customer - Address') . '</th>
						<th style=width:300px;>' . _('plant name') . '</th>

						<th style=width:100px;>' . _('Order Value') . '</th>
                        <th style=width:100px;>' . _('Amount Received') . '</th>
                        <th style=width:100px;>' . _('Balance') . '</th>
						</tr>';
}elseif((isset($_POST['regtype'])) AND ($_POST['regtype']==1)) 
{
       $tableheader = '<tr><th>' . _('No: of orders') . '</th>
                        <th>' . _('Total value') . '</th>  
                        <th>' . _('Total amount Received') . '</th>
                        <th>' . _('Balance') . '</th>
                        </tr>'; 
}
	echo $tableheader;

	$j = 1;
	$k=0; //row colour counter
    $slno=0;
	while ($myrow=DB_fetch_array($SalesOrdersResult)) {          
   $address1 = preg_replace("/,+/i", "   ", $myrow['address1']);                                    
  // echo $address1 = preg_replace('^,',$myrow['address1']);     
  
                                                     

//       $result_select=DB_query('SELECT SUM(amount) AS advancepaid FROM bio_advance WHERE leadid=(SELECT leadid FROM salesorders WHERE orderno="'.$myrow['orderno'].'")',$db);    
//       $myrow_select=DB_fetch_array($result_select); 
       
       $result_select=DB_query('SELECT SUM(ovamount) AS advancepaid FROM debtortrans WHERE debtorno="'.$myrow['debtorno'].'" AND type=12',$db);    
       $myrow_select=DB_fetch_array($result_select); 
if($_POST['regtype']==2) {       
		if ($k==1){
			echo '<tr class="EvenTableRows">';
			$k=0;
		} else {
			echo '<tr class="OddTableRows">';
			$k=1;
		}
}        
 $sql=" SELECT stockmaster.description 
FROM `salesorders`
inner join debtorplant_new on (debtorplant_new.debtorno=salesorders.`debtorno`)
inner join stockmaster on (debtorplant_new.stkcode=stockmaster.stockid)
where salesorders.orderno ='".$myrow['orderno']."'";
$result_plant=DB_query($sql,$db);
 $plant_name=DB_fetch_array($result_plant);

   $result_invoiced=DB_query("SELECT salesorderdetails.completed 
                                FROM salesorderdetails,salesorders 
                               WHERE salesorders.orderno=salesorderdetails.orderno
                                 AND salesorders.orderno='".$myrow['orderno']."'
                                 AND salesorderdetails.completed=1",$db);   
   $count_invoiced=DB_num_rows($result_invoiced);
   if($count_invoiced>=1)
   {
       $sql_invoicetax="SELECT ovamount+ovgst AS ordervalue FROM debtortrans WHERE order_='".$myrow['orderno']."' AND type=10";
       $result_invoicetax=DB_query($sql_invoicetax,$db);
       $myrow_invoicetax=DB_fetch_array($result_invoicetax);  
   }  


		$ViewPage = $rootpath . '/SelectOrderItems.php?ModifyOrderNumber=' . $myrow['orderno'];
		$FormatedDelDate = ConvertSQLDate($myrow['deliverydate']);
		$FormatedOrderDate = ConvertSQLDate($myrow['orddate']);  
   if($count_invoiced>=1)
   {
      $ordervalue=$myrow_invoicetax['ordervalue']; 
//        $FormatedOrderValue = number_format($myrow_invoicetax['ordervalue'],2);
        $Balance= $myrow_invoicetax['ordervalue']+$myrow_select['advancepaid'];
//        $FormatedBalance=number_format($Balance,2); 
   }else{  
      $ordervalue=$myrow['ordervalue']; 
//		$FormatedOrderValue = number_format($myrow['ordervalue'],2);          
        $Balance= $myrow['ordervalue']+$myrow_select['advancepaid'];
         
   }
        $FormatedAdvance = number_format(abs($myrow_select['advancepaid']),2);
        $FormatedOrderValue = number_format($ordervalue,2); 
        $FormatedBalance=number_format($Balance,2);
        
         $sumValue+=$ordervalue;  
         $sumAdvance+=$myrow_select['advancepaid'];
         $sumBalance+=$Balance;
                                        
      
 $slno++;         
         
if((isset($_POST['regtype'])) AND ($_POST['regtype']==2))
{       
		printf('<td><a href="%s">%s</a></td>
			<td>%s</td>
            <td>%s</td>
			<td>%s</td>
            <td class=number>%s</td>
			<td class=number>%s</td>
			<td class=number>%s</td>   
			</tr>',
			$ViewPage,
			$slno, 
            $FormatedOrderDate,  
			"<b>".$myrow['name']."</b><br />".$address1."<br />".$myrow['address2']."<br />".$myrow['address3'],
            $plant_name['0'],
			$FormatedOrderValue,
            $FormatedAdvance,
            $FormatedBalance);
                          // $myrow['orderno']
}  

//end of page full new headings if
	}
//end of while loop



if((isset($_POST['regtype'])) )
{
     echo'<tr>';
     echo'<td><b>'.$slno.'</b></td>';
if($_POST['regtype']!=1){     
     echo'<td></td><td></td>';
}
     echo'<td class=number><b>'.number_format($sumValue,2).'</b></td>';
     echo'<td class=number><b>'.number_format(abs($sumAdvance),2).'</b></td>';
     echo'<td class=number><b>'.number_format($sumBalance,2).'</b></td>';
     echo'</tr>';
}

	echo '</table>';

}

echo '</form>';
include('includes/footer.inc');

?>