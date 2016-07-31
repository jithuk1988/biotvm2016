<?php

/* $Id: Customers.php 4594 2011-06-11 07:26:47Z daintree $ */
$PageSecurity = 80;      
include('includes/session.inc');

$title = _('Customer Maintenance');

include('includes/header.inc');
include('includes/formload.inc'); 
include('includes/SQL_CommonFunctions.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Customer') .
    '" alt="" />' . ' ' . _('Customer Maintenance') . '</p>';

if (isset($Errors)) {
    unset($Errors);
}
$Errors = array();

if (isset($_POST['submit'])) {

    //initialise no input errors assumed initially before we test
    $InputError = 0;
    $i=1;

    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible

    $_POST['DebtorNo'] = strtoupper($_POST['DebtorNo']);

    $sql="SELECT COUNT(debtorno) FROM debtorsmaster WHERE debtorno='".$_POST['DebtorNo']."'";
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_row($result);
    if ($myrow[0]>0 and isset($_POST['New'])) {
        $InputError = 1;
        prnMsg( _('The customer number already exists in the database'),'error');
        $Errors[$i] = 'DebtorNo';
        $i++;
    }elseif (strlen($_POST['CustName']) > 40 OR strlen($_POST['CustName'])==0) {
        $InputError = 1;
        prnMsg( _('The customer name must be entered and be forty characters or less long'),'error');
        $Errors[$i] = 'CustName';
        $i++;
    } elseif ($_SESSION['AutoDebtorNo']==0 AND strlen($_POST['DebtorNo']) ==0) {
        $InputError = 1;
        prnMsg( _('The debtor code cannot be empty'),'error');
        $Errors[$i] = 'DebtorNo';
        $i++;
    } elseif ($_SESSION['AutoDebtorNo']==0 AND (ContainsIllegalCharacters($_POST['DebtorNo']) OR strpos($_POST['DebtorNo'], ' '))) {
        $InputError = 1;
        prnMsg( _('The customer code cannot contain any of the following characters') . " . - ' & + \" " . _('or a space'),'error');
        $Errors[$i] = 'DebtorNo';
        $i++;
    } elseif (strlen($_POST['Address1']) >40) {
        $InputError = 1;
        prnMsg( _('The Line 1 of the address must be forty characters or less long'),'error');
        $Errors[$i] = 'Address1';
        $i++;
    } elseif (strlen($_POST['Address2']) >40) {
        $InputError = 1;
        prnMsg( _('The Line 2 of the address must be forty characters or less long'),'error');
        $Errors[$i] = 'Address2';
        $i++;
    } elseif (strlen($_POST['Address3']) >40) {
        $InputError = 1;
        prnMsg( _('The Line 3 of the address must be forty characters or less long'),'error');
        $Errors[$i] = 'Address3';
        $i++;
    } elseif (strlen($_POST['Address4']) >50) {
        $InputError = 1;
        prnMsg( _('The Line 4 of the address must be fifty characters or less long'),'error');
        $Errors[$i] = 'Address4';
        $i++;
    } elseif (strlen($_POST['Address5']) >20) {
        $InputError = 1;
        prnMsg( _('The Line 5 of the address must be twenty characters or less long'),'error');
        $Errors[$i] = 'Address5';
        $i++;
    } elseif (strlen($_POST['Address6']) >15) {
        $InputError = 1;
        prnMsg( _('The Line 6 of the address must be fifteen characters or less long'),'error');
        $Errors[$i] = 'Address6';
        $i++;
    }
    elseif (strlen($_POST['Phone']) >25) {
        $InputError = 1;
        prnMsg(_('The telephone number must be 25 characters or less long'),'error');
        $Errors[$i] = 'Telephone';
        $i++;
    }
    elseif (strlen($_POST['Fax']) >25) {        
        $InputError = 1;
        prnMsg(_('The fax number must be 25 characters or less long'),'error');
        $Errors[$i] = 'Fax';
        $i++;
    }
    elseif (strlen($_POST['Email']) >55) {
        $InputError = 1;
        prnMsg(_('The email address must be 55 characters or less long'),'error');
        $Errors[$i] = 'Email';
        $i++;
    }
    elseif (strlen($_POST['Email'])>0 and !IsEmailAddress($_POST['Email'])) {      
        echo $a=strlen($_POST['Email']);
        echo"ggg". $b=!IsEmailAddress($_POST['Email']);
        $InputError = 1;
        prnMsg(_('The email address is not correctly formed'),'error');
        $Errors[$i] = 'Email';
        $i++;
    }
    elseif (!is_numeric($_POST['CreditLimit'])) {
        $InputError = 1;
        prnMsg( _('The credit limit must be numeric'),'error');
        $Errors[$i] = 'CreditLimit';
        $i++;
    } elseif (!is_numeric($_POST['PymtDiscount'])) {
        $InputError = 1;
        prnMsg( _('The payment discount must be numeric'),'error');
        $Errors[$i] = 'PymtDiscount';
        $i++;
    } elseif (!Is_Date($_POST['ClientSince'])) {
        $InputError = 1;
        prnMsg( _('The customer since field must be a date in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
        $Errors[$i] = 'ClientSince';
        $i++;
    } elseif (!is_numeric($_POST['Discount'])) {
        $InputError = 1;
        prnMsg( _('The discount percentage must be numeric'),'error');
        $Errors[$i] = 'Discount';
        $i++;
    } elseif ((double) $_POST['CreditLimit'] <0) {
        $InputError = 1;
        prnMsg( _('The credit limit must be a positive number'),'error');
        $Errors[$i] = 'CreditLimit';
        $i++;
    } elseif (((double) $_POST['PymtDiscount']> 10) OR ((double) $_POST['PymtDiscount'] <0)) {
        $InputError = 1;
        prnMsg( _('The payment discount is expected to be less than 10% and greater than or equal to 0'),'error');
        $Errors[$i] = 'PymtDiscount';
        $i++;
    } elseif (((double) $_POST['Discount']> 100) OR ((double) $_POST['Discount'] <0)) {
        $InputError = 1;
        prnMsg( _('The discount is expected to be less than 100% and greater than or equal to 0'),'error');
        $Errors[$i] = 'Discount';
        $i++;
    }

    if ($InputError !=1){

        $SQL_ClientSince = FormatDateForSQL($_POST['ClientSince']);

        if (!isset($_POST['New'])) {

            $sql = "SELECT count(id)
                      FROM debtortrans
                    where debtorno = '" . $_POST['DebtorNo'] . "'";
            $result = DB_query($sql,$db);
            $myrow = DB_fetch_array($result);

            if ($myrow[0] == 0) {
              $sql = "UPDATE debtorsmaster SET
                    name='" . $_POST['CustName'] . "',
                    address1='" . $_POST['Address1'] . "',
                    address2='" . $_POST['Address2'] . "',
                    address3='" . $_POST['Address3'] ."',
                    address4='" . $_POST['Address4'] . "',
                    address5='" . $_POST['Address5'] . "',
                    address6='" . $_POST['Address6'] . "',
                    currcode='" . $_POST['CurrCode'] . "',
                    clientsince='" . $SQL_ClientSince. "',
                    holdreason='" . $_POST['HoldReason'] . "',
                    paymentterms='" . $_POST['PaymentTerms'] . "',
                    discount='" . ($_POST['Discount'])/100 . "',
                    discountcode='" . $_POST['DiscountCode'] . "',
                    pymtdiscount='" . ($_POST['PymtDiscount'])/100 . "',
                    creditlimit='" . $_POST['CreditLimit'] . "',
                    salestype = '" . $_POST['SalesType'] . "',
                    invaddrbranch='" . $_POST['AddrInvBranch'] . "',
                    taxref='" . $_POST['TaxRef'] . "',
                    customerpoline='" . $_POST['CustomerPOLine'] . "',
                    typeid='" . $_POST['typeid'] . "'
                  WHERE debtorno = '" . $_POST['DebtorNo'] . "'";
            } else {

              $currsql = "SELECT currcode
                              FROM debtorsmaster
                            where debtorno = '" . $_POST['DebtorNo'] . "'";
              $currresult = DB_query($currsql,$db);
              $currrow = DB_fetch_array($currresult);
              $OldCurrency = $currrow[0];

              $sql = "UPDATE debtorsmaster SET
                    name='" . $_POST['CustName'] . "',
                    address1='" . $_POST['Address1'] . "',
                    address2='" . $_POST['Address2'] . "',
                    address3='" . $_POST['Address3'] ."',
                    address4='" . $_POST['Address4'] . "',
                    address5='" . $_POST['Address5'] . "',
                    address6='" . $_POST['Address6'] . "',
                    clientsince='" . $SQL_ClientSince . "',
                    holdreason='" . $_POST['HoldReason'] . "',
                    paymentterms='" . $_POST['PaymentTerms'] . "',
                    discount='" . ($_POST['Discount'])/100 . "',
                    discountcode='" . $_POST['DiscountCode'] . "',
                    pymtdiscount='" . ($_POST['PymtDiscount'])/100 . "',
                    creditlimit='" . $_POST['CreditLimit'] . "',
                    salestype = '" . $_POST['SalesType'] . "',
                    invaddrbranch='" . $_POST['AddrInvBranch'] . "',
                    taxref='" . $_POST['TaxRef'] . "',
                    customerpoline='" . $_POST['CustomerPOLine'] . "',
                    typeid='" . $_POST['typeid'] . "'
                  WHERE debtorno = '" . $_POST['DebtorNo'] . "'";

              if ($OldCurrency != $_POST['CurrCode']) {
                  prnMsg( _('The currency code cannot be updated as there are already transactions for this customer'),'info');
              }
            }

            $ErrMsg = _('The customer could not be updated because');
            $result = DB_query($sql,$db,$ErrMsg);
            prnMsg( _('Customer updated'),'success');
            echo '<br />';

        } else { //it is a new customer
            /* set the DebtorNo if $AutoDebtorNo in config.php has been set to
            something greater 0 */
            if ($_SESSION['AutoDebtorNo'] > 0) {
                /* system assigned, sequential, numeric */
                if ($_SESSION['AutoDebtorNo']== 1) {
                    $_POST['DebtorNo'] = GetNextTransNo(500, $db);
                }
            }

            if($_POST['Address1']==''){
                $_POST['Address1']=$_POST['Address2'];
                if($_POST['Address2']==''){
                $_POST['Address1']=$_POST['Address3'];
                }
            }
$_SESSION['Phone']=$_POST['Phone'];
$_SESSION['Fax']=$_POST['Fax'];                  
$_SESSION['Email']=$_POST['Email']; 

            $sql = "INSERT INTO debtorsmaster (
                            debtorno,
                            name,
                            address1,
                            address2,
                            address3,
                            address4,
                            address5,
                            address6,
                            currcode,
                            clientsince,
                            holdreason,
                            paymentterms,
                            discount,
                            discountcode,
                            pymtdiscount,
                            creditlimit,
                            salestype,
                            invaddrbranch,
                            taxref,
                            customerpoline,
                            typeid,
                            cid,
                            stateid,
                            did,
                            taluk,
                            LSG_type,
                            LSG_name,
                            block_name,
                            LSG_ward,
                            village)
                VALUES ('" . $_POST['DebtorNo'] ."',
                    '" . $_POST['CustName'] ."',
                    '" . $_POST['Address1'] ."',
                    '" . $_POST['Address2'] ."',
                    '" . $_POST['Address3'] . "',
                    '" . $_POST['Address4'] . "',
                    '" . $_POST['Address5'] . "',
                    '" . $_POST['Address6'] . "',
                    '" . $_POST['CurrCode'] . "',
                    '" . $SQL_ClientSince . "',
                    '" . $_POST['HoldReason'] . "',
                    '" . $_POST['PaymentTerms'] . "',
                    '" . ($_POST['Discount'])/100 . "',
                    '" . $_POST['DiscountCode'] . "',
                    '" . ($_POST['PymtDiscount'])/100 . "',
                    '" . $_POST['CreditLimit'] . "',
                    '" . $_POST['SalesType'] . "',
                    '" . $_POST['AddrInvBranch'] . "',
                    '" . $_POST['TaxRef'] . "',
                    '" . $_POST['CustomerPOLine'] . "',
                    '" . $_POST['typeid'] . "',
                    '" . $_POST['country'] . "',
                    '" . $_POST['State'] . "',
                    '" . $_POST['District'] . "',
                    '" . $_POST['taluk'] . "',                          
                    '" . $_POST['lsgType'] . "',
                    '" . $_POST['lsgName'] . "',
                    '" . $_POST['gramaPanchayath'] . "',
                    '" . $_POST['lsgWard'] . "',
                    '" . $_POST['village'] . "' 
                    )";

            $ErrMsg = _('This customer could not be added because');
            $result = DB_query($sql,$db,$ErrMsg);
            
            
            
          echo  $sql_cust="UPDATE bio_cust SET debtorno='" . $_POST['DebtorNo'] ."' WHERE cust_id=" . $_POST['custid'] . " ";
            $result_cust=DB_query($sql_cust,$db);

            $BranchCode = substr($_POST['DebtorNo'],0,4);
            
            if($_POST['enqtype']==3)
            {
                $_SESSION['CounterSale']='countersale';
            }
            
            echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath .'/dealercustbranch.php?DebtorNo=' . $_POST['DebtorNo'] . '&flag=' .$_POST['flag'] . '&leadid=' .$_POST['leadid'] . '">';        

            echo '<div class="centre">' . _('You should automatically be forwarded to the entry of a new Customer Branch page') .
            '. ' . _('If this does not happen') .' (' . _('if the browser does not support META Refresh') . ') ' .
            '<a href="' . $rootpath . '/dealercustbranch.php?DebtorNo=' . $_POST['DebtorNo']  . '&flag=' .$_POST['flag'] . '&leadid=' .$_POST['leadid'] . '"></a></div>';

            include('includes/footer.inc');
            exit;
        }
    } else {
        prnMsg( _('Validation failed') . '. ' . _('No updates or deletes took place'),'error');
    }

} elseif (isset($_POST['delete'])) {

//the link to delete a selected record was clicked instead of the submit button

    $CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS IN 'DebtorTrans'

    $sql= "SELECT COUNT(*) FROM debtortrans WHERE debtorno='" . $_POST['DebtorNo'] . "'";
    $result = DB_query($sql,$db);
    $myrow = DB_fetch_row($result);
    if ($myrow[0]>0) {
        $CancelDelete = 1;
        prnMsg( _('This customer cannot be deleted because there are transactions that refer to it'),'warn');
        echo '<br /> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('transactions against this customer');

    } else {
        $sql= "SELECT COUNT(*) FROM salesorders WHERE debtorno='" . $_POST['DebtorNo'] . "'";
        $result = DB_query($sql,$db);
        $myrow = DB_fetch_row($result);
        if ($myrow[0]>0) {
            $CancelDelete = 1;
            prnMsg( _('Cannot delete the customer record because orders have been created against it'),'warn');
            echo '<br /> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('orders against this customer');
        } else {
            $sql= "SELECT COUNT(*) FROM salesanalysis WHERE cust='" . $_POST['DebtorNo'] . "'";
            $result = DB_query($sql,$db);
            $myrow = DB_fetch_row($result);
            if ($myrow[0]>0) {
                $CancelDelete = 1;
                prnMsg( _('Cannot delete this customer record because sales analysis records exist for it'),'warn');
                echo '<br /> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('sales analysis records against this customer');
            } else {
                $sql= "SELECT COUNT(*) FROM custbranch WHERE debtorno='" . $_POST['DebtorNo'] . "'";
                $result = DB_query($sql,$db);
                $myrow = DB_fetch_row($result);
                if ($myrow[0]>0) {
                    $CancelDelete = 1;
                    prnMsg(_('Cannot delete this customer because there are branch records set up against it'),'warn');
                    echo '<br /> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('branch records relating to this customer');
                }
            }
        }

    }
    if ($CancelDelete==0) { //ie not cancelled the delete as a result of above tests
        $sql="DELETE FROM custcontacts WHERE debtorno='" . $_POST['DebtorNo'] . "'";
        $result = DB_query($sql,$db);
        $sql="DELETE FROM debtorsmaster WHERE debtorno='" . $_POST['DebtorNo'] . "'";
        $result = DB_query($sql,$db);
        prnMsg( _('Customer') . ' ' . $_POST['DebtorNo'] . ' ' . _('has been deleted - together with all the associated contacts') . ' !','success');
        include('includes/footer.inc');
        unset($_SESSION['CustomerID']);
        exit;
    } //end if Delete Customer
}

if(isset($_POST['Reset'])){
    unset($_POST['CustName']);
    unset($_POST['Address1']);
    unset($_POST['Address2']);
    unset($_POST['Address3']);
    unset($_POST['Address4']);
    unset($_POST['Address5']);
    unset($_POST['Address6']);
    unset($_POST['Phone']);
    unset($_POST['Fax']);
    unset($_POST['Email']);
    unset($_POST['HoldReason']);
    unset($_POST['PaymentTerms']);
    unset($_POST['Discount']);
    unset($_POST['DiscountCode']);
    unset($_POST['PymtDiscount']);
    unset($_POST['CreditLimit']);
// Leave Sales Type set so as to faciltate fast customer setup
//    unset($_POST['SalesType']);
    unset($_POST['DebtorNo']);
    unset($_POST['InvAddrBranch']);
    unset($_POST['TaxRef']);
    unset($_POST['CustomerPOLine']);
// Leave Type ID set so as to faciltate fast customer setup
//    unset($_POST['typeid']);
}

/*DebtorNo could be set from a post or a get when passed as a parameter to this page */

if (isset($_POST['DebtorNo'])){
    $DebtorNo = $_POST['DebtorNo'];
} elseif (isset($_GET['DebtorNo'])){
    $DebtorNo = $_GET['DebtorNo'];
}
if (isset($_POST['ID'])){
    $ID = $_POST['ID'];
} elseif (isset($_GET['ID'])){
    $ID = $_GET['ID'];
} else {
    $ID='';
}
if (isset($_POST['ws'])){
    $ws = $_POST['ws'];
} elseif (isset($_GET['ws'])){
    $ws = $_GET['ws'];
}
if (isset($_POST['Edit'])){
    $Edit = $_POST['Edit'];
} elseif (isset($_GET['Edit'])){
    $Edit = $_GET['Edit'];
} else {
    $Edit='';
}
if (isset($_POST['Add'])){
    $Add = $_POST['Add'];
} elseif (isset($_GET['Add'])){
    $Add = $_GET['Add'];
}

// This link is already on menu bar
//echo "<a href='" . $rootpath . '/SelectCustomer.php?' . SID . "'>" . _('Back to Customers') . '</a><br />';

if (!isset($DebtorNo)) {

/*If the page was called without $_POST['DebtorNo'] passed to page then assume a new customer is to be entered show a form with a Debtor Code field other wise the form showing the fields with the existing entries against the customer will show for editing with only a hidden DebtorNo field*/

/* First check that all the necessary items have been setup */

    $SetupErrors=0; //Count errors
    $sql="SELECT COUNT(typeabbrev)
                FROM salestypes";
    $result=DB_query($sql, $db);
    $myrow=DB_fetch_row($result);
    if ($myrow[0]==0) {
        prnMsg( _('In order to create a new customer you must first set up at least one sales type/price list').'<br />'.
            _('Click').' '.'<a target="_blank" href="' . $rootpath . '/SalesTypes.php">' . _('here').' ' . '</a>'._('to set up your price lists'),'warning').'<br />';
        $SetupErrors += 1;
    }
    $sql="SELECT COUNT(typeid)
                FROM debtortype";
    $result=DB_query($sql, $db);
    $myrow=DB_fetch_row($result);
    if ($myrow[0]==0) {
        prnMsg( _('In order to create a new customer you must first set up at least one customer type').'<br />'.
            _('Click').' '.'<a target="_blank" href="' . $rootpath . '/CustomerTypes.php">' . _('here').' ' . '</a>'._('to set up your customer types'),'warning');
        $SetupErrors += 1;
    }

    if ($SetupErrors>0) {
        echo '<br /><div class=centre><a href="'.$_SERVER['PHP_SELF'] .'" >'._('Click here to continue').'</a></div>';
        include('includes/footer.inc');
        exit;
    }

//    $lead=$_GET['lead'];
//    $sql="SELECT cust_id FROM bio_leads WHERE leadid=$lead";
//    $result=DB_query($sql,$db);
//    $row=DB_fetch_array($result);

//if(($_GET['custid']!="") && ($_GET['enquiryid']!=''))    
//{    
if($_GET['custid']!=""){
  $custid=$_GET['custid'];  
}elseif($_SESSION['custid']!=""){
  $custid=$_SESSION['custid'];  
}
if($_GET['enquiryid']!="")   {
  $enqtype=$_GET['enquiryid'];  
}
elseif($_SESSION['enquiryid']!=""){
  $enqtype=$_SESSION['enquiryid'];  
}
$tid=$_GET['tid']; 
$leadid=$_GET['leadid'];  
//}else
//{
//$custid=$row['cust_id'];   
//}

$sql_select="SELECT bio_cust.cust_id, 
                    bio_cust.custname, 
                    bio_cust.custphone,
                    bio_cust.custmob, 
                    bio_cust.custmail, 
                    bio_cust.houseno, 
                    bio_cust.housename,
                    bio_cust.area1,
                    bio_cust.area2,
                    bio_cust.pin,
                    bio_cust.nationality,
                    bio_cust.state AS cust_state,
                    bio_cust.district AS cust_district,
                    bio_leads.leadid,
                    bio_leads.leaddate, 
                    bio_district.district, 
                    bio_state.state,  
                    bio_country.country,
                    bio_cust.pin,
                    bio_cust.LSG_type,
                    bio_cust.LSG_name, 
                    bio_cust.block_name, 
                    bio_cust.LSG_ward,
                    bio_cust.village,
                    bio_cust.taluk
               FROM bio_leads, bio_cust, bio_country, bio_state, bio_district
              WHERE bio_leads.cust_id = bio_cust.cust_id
                AND bio_district.did = bio_cust.district
                AND bio_district.stateid = bio_cust.state
                AND bio_district.cid = bio_cust.nationality
                AND bio_state.stateid = bio_cust.state
                AND bio_country.cid = bio_cust.nationality
                AND bio_cust.cust_id=$custid";
$result_select=DB_query($sql_select,$db);
$row_select=DB_fetch_array($result_select);

             $country=$row_select['nationality'];  
              $state=$row_select['cust_state']; 
              $district=$row_select['cust_district'];
              
              $LSGtypeid = $row_select['LSG_type'];   
                    
              if($LSGtypeid==1){$lsgtype="Corporation";}
          elseif($LSGtypeid==2){$lsgtype="Municipality";}
          elseif($LSGtypeid==3){$lsgtype="Panchayath";}   
          
              $LSG_name = $row_select['LSG_name'];  
              $block_name = $row_select['block_name']; 
              $LSG_ward = $row_select['LSG_ward'];
              $taluk = $row_select['taluk'];
              $village = $row_select['village']; 
              
//$sql_autofill="SELECT ";              
              
    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    
    echo"<input type='hidden' name='flag' id='flag' value='$tid'>";
    echo"<input type='hidden' name='leadid' id='leadid' value='$leadid'>";
     echo"<input type='hidden' name='custid' value='".$row_select['cust_id']."'>";  
     echo"<input type='hidden' name='enqtype' value='".$enqtype."'>";
    echo '<input type="hidden" name="New" value="Yes" />';

    $DataError =0;

    echo '<table class="selection" cellspacing=4>
            <tr><td valign=top><table class="selection">';

    /* if $AutoDebtorNo in config.php has not been set or if it has been set to a number less than one,
    then provide an input box for the DebtorNo to manually assigned */
    
    $DebtorNo=customerCodeGenaration($enqtype,$db);
    //if ($_SESSION['AutoDebtorNo']==0)  {
    echo '<tr><td>' . _('Customer Code') . ':</td><td><input tabindex=1 type="text" name="DebtorNo" value="'.$DebtorNo.'" size=11 maxlength=10></td></tr>';
    //}

    echo '<tr><td>' . _('Customer Name') . ':</td>
        <td><input tabindex=2 type="Text" name="CustName" value="'.$row_select['custname'].'" size=42 maxlength=100></td></tr>';
    echo '<tr><td>' . _('Phone Number') . ':</td>
        <td><input tabindex=2 type="Text" name="Phone" value="'.$row_select['custphone'].'" size=30 maxlength=40></td></tr>';
    echo '<tr><td>' . _('Mobile Number') . ':</td>
        <td><input tabindex=2 type="Text" name="Fax" value="'.$row_select['custmob'].'" size=30 maxlength=40></td></tr>';
    echo '<tr><td>' . _('Email ID') . ':</td>
        <td><input tabindex=2 type="Text" name="Email" value="'.$row_select['custmail'].'" size=30 maxlength=100></td></tr>';
    echo '<tr><td width=200px>' . _('House/ Building No, House/Org Name') . ':</td>
        <td><input tabindex=3 type="Text" name="Address1" value="'.$row_select['houseno'].','.$row_select['housename'].'" size=42 maxlength=200></td></tr>';
    echo '<tr><td>' . _('House / Org Area') . ':</td>
        <td><input tabindex=4 type="Text" name="Address2" value="'.$row_select['area1'].'" size=42 maxlength=200></td></tr>';
    echo '<tr><td>' . _('Post Office, Pin No') . ':</td>
        <td><input tabindex=5 type="Text" name="Address3" value="'.$row_select['area2'].','.$row_select['pin'].'" size=42 maxlength=200></td></tr>';

//---------country--------------//    
    
    echo"<tr><td style='width:50%'>Country*</td><td>";
    echo '<select name="country" id="country" tabindex=6 onchange="showstate(this.value)" style="width:190px">';
    
    $sql="SELECT * FROM bio_country ORDER BY cid";     $result=DB_query($sql,$db);
    
    $f=0;
    while($myrow1=DB_fetch_array($result))    
    {                                              
        if ($myrow1['cid']==$row_select['nationality'])  
        {         
        echo '<option selected value="';
        } else 
        {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        }
        echo $myrow1['cid'] . '">'.$myrow1['country'];
        echo '</option>';
        $f++;
   } 
   echo '</select></td></tr>';
  
//--------------state-----------------//


     echo"<tr id='showstate'><td>State*</td><td>";
     echo '<select name="State" id="state" style="width:190px" tabindex=7 onchange="showdistrict(this.value)">';
     
     $sql="SELECT * FROM bio_state WHERE cid=".$row_select['nationality']." ORDER BY stateid";        $result=DB_query($sql,$db);   
 
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {
        if ($myrow1['stateid']==$row_select['cust_state'])
        {
        echo '<option selected value="';
        } else
        {
        if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
        }
        echo $myrow1['stateid'] . '">'.$myrow1['state'];
        echo '</option>';
        $f++;
   }
   echo '</select></td></tr>';

//-------------District---------------//  

 
     echo"<tr id='showdistrict'><td>District*</td><td>";
     echo '<select name="District" id="Districts" style="width:190px" tabindex=8>';                   
 
     $sql="SELECT * FROM bio_district WHERE cid=".$row_select['nationality']." AND stateid=".$row_select['cust_state']." ORDER BY did";   $result=DB_query($sql,$db);   
 
     $f=0;
     while($myrow1=DB_fetch_array($result))
     {
        if ($myrow1['did']==$row_select['cust_district'])
        {
        echo '<option selected value="';
        } else
        {
        if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
        }
        echo $myrow1['did'] . '">'.$myrow1['district'];
        echo '</option>';
        $f++;
   }
   echo '</select></td></tr>'; 
   
      //--------Corporation/Muncipality/Panchayath-------------------//         

if($LSGtypeid!=0){    

    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:190px" tabindex=9 onchange=showblock(this.value)>';             
    echo '<option value='.$LSGtypeid.'>'.$lsgtype.'</option>'; 
    if($LSGtypeid==1){   
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
    }elseif($LSGtypeid==2){
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
    }elseif($LSGtypeid==3){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
    }      
    echo '</select></td></tr>';      
    
}else{
    
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:190px" tabindex=9 onchange=showblock(this.value)>';               
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td></tr>';
    
    echo '<tr><td align=left colspan=2>';
    echo '<div style="align:left" id=block>'; 
    
    echo"<tr id='showgramapanchayath'></tr>"; 
    echo '</div>';     
    
}
       
    echo '<tr><td align=left colspan=2>';

                                                      
        if($LSGtypeid==1) 
        {
        
        $sql="SELECT * FROM bio_corporation WHERE country=$country AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];


              //echo"11111111"; 
          if($country==1 && $state==14)  
          {
              if($district==12){$distname='Thiruvananthapuram';}
              if($district==6){$distname='Kollam';} 
              if($district==2){$distname='Eranakulam';} 
              if($district==13){$distname='Thrissur';} 
              if($district==8){$distname='Kozhikode';} 
                    echo '<table align=left ><tr><td width=200px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:190px">';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td>';    
                    echo '</tr></table>';      
          }
        
        }elseif($LSGtypeid==2) 
        {
            //echo"2222222";
        echo '<table align=left ><tr><td width=200px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country=$country AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:190px">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
         {
         echo '<option selected value="';
         } else
         {
         if ($f==0)
         {
         echo '<option>';
         }
         echo '<option value="';
         }
         echo $myrow1['id'] . '">'.$myrow1['municipality'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</td></tr></table>'; 
        
        }elseif($LSGtypeid==3) 
        {
            //echo"3333333"; 
         echo '<table align=left ><tr><td width=200px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country=$country AND state=$state AND district=$district";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:190px" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
         {
         echo '<option selected value="';
         } else
         {
         if ($f==0)
         {
         echo '<option>';
         }
         echo '<option value="';
         }
         echo $myrow1['id'] . '">'.$myrow1['block'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr>';
      
      echo '<tr><td width=200px>' . _('Panchayat Name') . ':</td>';         
         
         $sql="SELECT * FROM bio_panchayat WHERE country=$country AND state=$state AND district=$district AND id=$block_name";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:190px" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$block_name)
         {
         echo '<option selected value="';
         } else
         {
         if ($f==0)
         {
         echo '<option>';
         }
         echo '<option value="';
         }
         echo $myrow1['id'] . '">'.$myrow1['name'];
         echo '</option>';
         $f++;
         }

      echo '</select></td></tr>';                    
      echo'</table>';  
        }        
                            
        echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
                  <td><input tabindex=9 type="Text" name="lsgWard" id="lsgWard" style=width:190px maxlength=15 value='.$LSG_ward.'></td></tr>';     
                  
      echo"<tr><td>Taluk*</td><td>";
      $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country=$country AND bio_taluk.state=$state AND bio_taluk.district=$district";
      $result=DB_query($sql,$db);
      echo '<select name="taluk" id="taluk" style="width:190px" tabindex=11>';
      $f=0;
      while($myrow1=DB_fetch_array($result))
      {
      if ($myrow1['id']==$taluk)
      {
      echo '<option selected value="';
      } else
      {
      if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
        }
      echo $myrow1['id'] . '">'.$myrow1['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td></tr>';         
      
      echo '<tr><td>' . _('Village Name'). ':</td>
                <td><input tabindex=9 type="Text" name="village" id="village" style=width:190px maxlength=100 value='.$village.'></td></tr>';        
   
/*   
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:190px" tabindex=9 onchange=showblock(this.value)>';               
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td></tr>'; 

                   
        echo '<tr><td align=left colspan=2>';
        echo'<div style="align:left" id=block>';
                    
        echo'</div>'; 
        echo'</td></tr>';
        
        echo"<tr id='showgramapanchayath'></tr>"; 
        
        echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
                  <td><input tabindex=9 type="Text" name="lsgWard" id="lsgWard" style=width:190px maxlength=15 value=""></td></tr>';  
            
      //      echo"<tr id='showtaluk'>";      
      //      echo '<tr><td>' . _('Taluk') . ':</td>';
      
      echo"<td>Taluk*</td><td>";
      $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country=$country AND bio_taluk.state=$state AND bio_taluk.district=$district";
      $result=DB_query($sql,$db);
      echo '<select name="taluk" id="taluk" style="width:190px" tabindex=11>';
      $f=0;
      while($myrow1=DB_fetch_array($result))
      {
      if ($myrow1['id']==$_POST['taluk'])
      {
      echo '<option selected value="';
      } else
      {
      if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
        }
      echo $myrow1['id'] . '">'.$myrow1['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td>'; 
       

       
       echo"</tr>";  
       
              echo '<tr><td>' . _('Village Name'). ':</td>
                  <td><input tabindex=9 type="Text" name="village" id="village" style=width:190px maxlength=15 value=""></td></tr>';  
                                                                                                                                         */

// Show Sales Type drop down list
    $result=DB_query("SELECT typeabbrev, sales_type FROM salestypes ",$db);
    if (DB_num_rows($result)==0){
        $DataError =1;
        echo '<tr><td colspan=2>' . prnMsg(_('No sales types/price lists defined'),'error') . '<br /><a href="SalesTypes.php?" target="_parent">' . _('Setup Types') . '</a></td></tr>';
    } else {
        echo '<tr><td>' . _('Sales Type/Price List') . ':</td>
               <td><select tabindex=9 name="SalesType">';

        while ($myrow = DB_fetch_array($result)) {
           echo '<option value="'. $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
        } //end while loopre
        DB_data_seek($result,0);
        echo '</select></td></tr>';
    }

// Show Customer Type drop down list
    $result=DB_query("SELECT typeid, typename FROM debtortype", $db);
    if (DB_num_rows($result)==0){
       $DataError =1;
       echo '<a href="SalesTypes.php?" target="_parent">' . _('Setup Types') . '</a>';
       echo '<tr><td colspan=2>' . prnMsg(_('No Customer types/price lists defined'),'error') . '</td></tr>';
    } else {
        echo '<tr><td>' . _('Customer Type') . ':</td>
                <td><select tabindex=9 name="typeid">';

        while ($myrow = DB_fetch_array($result)) {
            echo '<option value="'. $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
        } //end while loop
        DB_data_seek($result,0);
        echo '</select></td></tr>';
    }

    $DateString = Date($_SESSION['DefaultDateFormat']);
    echo '<tr><td>' . _('Customer Since') . ' (' . $_SESSION['DefaultDateFormat'] . '):</td>
                <td><input tabindex=10 type="text" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" name="ClientSince" value="' . $DateString . '" size=12 maxlength=10></td></tr>';
    
    echo '</table></td>
            <td><table class="selection">';
            
    echo '<tr><td>' . _('Discount Percent') . ':</td>
        <td><input tabindex=11 type="textbox" class="number" name="Discount" value=0 size=5 maxlength=4></td></tr>';
    echo '<tr><td>' . _('Discount Code') . ':</td>
        <td><input tabindex=12 type="text" name="DiscountCode" size=3 maxlength=2></td></tr>';
    echo '<tr><td>' . _('Payment Discount Percent') . ':</td>
        <td><input tabindex=13 type="textbox" class ="number" name="PymtDiscount" value=0 size=5 maxlength=4></td></tr>';
    echo '<tr><td>' . _('Credit Limit') . ':</td>
        <td><input tabindex=14 type="text" class="number" name="CreditLimit" value=' . $_SESSION['DefaultCreditLimit'] . ' size=16 maxlength=14></td></tr>';
    echo '<tr><td>' . _('Tax Reference') . ':</td>
        <td><input tabindex=15 type="text" name="TaxRef" size=22 maxlength=20></td></tr>';

    $result=DB_query("SELECT terms, termsindicator FROM paymentterms",$db);
    if (DB_num_rows($result)==0){
        $DataError =1;
        echo '<tr><td colspan=2>' . prnMsg(_('There are no payment terms currently defined - go to the setup tab of the main menu and set at least one up first'),'error') . '</td></tr>';
    } else {

        echo '<tr><td>' . _('Payment Terms') . ':</td>
            <td><select tabindex=15 name="PaymentTerms">';

        while ($myrow = DB_fetch_array($result)) {
            echo '<option value="'. $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
        } //end while loop
        DB_data_seek($result,0);

        echo '</select></td></tr>';
    }
    echo '<tr><td>' . _('Credit Status') . ':</td><td><select tabindex=16 name="HoldReason">';

    $result=DB_query('SELECT reasoncode, reasondescription FROM holdreasons',$db);
    if (DB_num_rows($result)==0){
        $DataError =1;
        echo '<tr><td colspan=2>' . prnMsg(_('There are no credit statuses currently defined - go to the setup tab of the main menu and set at least one up first'),'error') . '</td></tr>';
    } else {
        while ($myrow = DB_fetch_array($result)) {
            echo '<option value="'. $myrow['reasoncode'] . '">' . $myrow['reasondescription'] . '</option>';
        } //end while loop
        DB_data_seek($result,0);
        echo '</select></td></tr>';
    }

    $result=DB_query("SELECT currency, currabrev FROM currencies",$db);
    if (DB_num_rows($result)==0){
        $DataError =1;
        echo '<tr><td colspan=2>' . prnMsg(_('There are no currencies currently defined - go to the setup tab of the main menu and set at least one up first'),'error') . '</td></tr>';
    } else {
        if (!isset($_POST['CurrCode'])){
            $CurrResult = DB_query("SELECT currencydefault FROM companies WHERE coycode=1",$db);
            $myrow = DB_fetch_row($CurrResult);
            $_POST['CurrCode'] = $myrow[0];
        }
        echo '<tr><td>' . _('Customer Currency') . ':</td>
                <td><select tabindex=17 name="CurrCode">';
        while ($myrow = DB_fetch_array($result)) {
            if ($_POST['CurrCode']==$myrow['currabrev']){
                echo '<option selected value='. $myrow['currabrev'] . '>' . $myrow['currency'] . '</option>';
            } else {
                echo '<option value='. $myrow['currabrev'] . '>' . $myrow['currency'] . '</option>';
            }
        } //end while loop
        DB_data_seek($result,0);

        echo '</select></td></tr>';
    }

    echo '<tr><td>' . _('Customer PO Line on SO') . ':</td>
            <td><select tabindex="18" name="CustomerPOLine">
                <option selected value=0>' . _('No') . '</option>
                <option value=1>' . _('Yes') . '</option>
                </select>
            </td>
        </tr>';

    echo '<tr><td>' . _('Invoice Addressing') . ':</td>
            <td><select tabindex="19" name="AddrInvBranch">
                <option selected VALUE=0>' . _('Address to HO') . '</option>
                <option VALUE=1>' . _('Address to Branch') . '</option>
                </select>
            </td>
        </tr>';

    echo'</table></td></tr></table>';
    if ($DataError ==0){
        echo '<br /><div class="centre"><input tabindex=20 type="Submit" name="submit" value="' . _('Add New Customer') . '">&nbsp;<input tabindex=21 type="submit" action="Reset" value="' . _('Reset') . '"></div>';
        
    }
    echo '</form>';

} else {

//DebtorNo exists - either passed when calling the form or from the form itself

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<table class=selection cellspacing=4>
            <tr><td valign=top><table class=selection>';

    if (!isset($_POST['New'])) {
        $sql = "SELECT debtorsmaster.debtorno,
                name,
                address1,
                address2,
                address3,
                address4,
                address5,
                address6,
                currcode,
                salestype,
                clientsince,
                holdreason,
                paymentterms,
                discount,
                discountcode,
                pymtdiscount,
                creditlimit,
                invaddrbranch,
                taxref,
                customerpoline,
                typeid
                FROM debtorsmaster
            WHERE debtorsmaster.debtorno = '" . $DebtorNo . "'";

        $ErrMsg = _('The customer details could not be retrieved because');
        $result = DB_query($sql,$db,$ErrMsg);

        $myrow = DB_fetch_array($result);
        /* if $AutoDebtorNo in config.php has not been set or if it has been set to a number less than one,
        then display the DebtorNo */
        if ($_SESSION['AutoDebtorNo']== 0 )  {
            echo '<tr>
                    <td>' . _('Customer Code') . ':</td>
                    <td>' . $DebtorNo. '</td>
                </tr>';
        }
        $_POST['CustName'] = $myrow['name'];
        $_POST['Address1']  = $myrow['address1'];
        $_POST['Address2']  = $myrow['address2'];
        $_POST['Address3']  = $myrow['address3'];
        $_POST['Address4']  = $myrow['address4'];
        $_POST['Address5']  = $myrow['address5'];
        $_POST['Address6']  = $myrow['address6'];
        $_POST['SalesType'] = $myrow['salestype'];
        $_POST['CurrCode']  = $myrow['currcode'];
        $_POST['ClientSince'] = ConvertSQLDate($myrow['clientsince']);
        $_POST['HoldReason']  = $myrow['holdreason'];
        $_POST['PaymentTerms']  = $myrow['paymentterms'];
        $_POST['Discount']  = $myrow['discount'] * 100; 
        $_POST['DiscountCode']  = $myrow['discountcode'];
        $_POST['PymtDiscount']  = $myrow['pymtdiscount'] * 100; 
        $_POST['CreditLimit']    = $myrow['creditlimit'];
        $_POST['InvAddrBranch'] = $myrow['invaddrbranch'];
        $_POST['TaxRef'] = $myrow['taxref'];
        $_POST['CustomerPOLine'] = $myrow['customerpoline'];
        $_POST['typeid'] = $myrow['typeid'];

        echo '<input type=hidden name="DebtorNo" value="' . $DebtorNo . '" />';

    } else {
    // its a new customer being added
        echo '<input type=hidden name="New" value="Yes" />';

        /* if $AutoDebtorNo in config.php has not been set or if it has been set to a number less than one,
        then provide an input box for the DebtorNo to manually assigned */
        if ($_SESSION['AutoDebtorNo']== 0 )  {
            echo '<tr><td>' . _('Customer Code') . ':</td>
                <td><input ' . (in_array('DebtorNo',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="DebtorNo" value="' . $DebtorNo . '" size=12 maxlength=10></td></tr>';
        }
    }
    if (isset($_GET['Modify'])) {
        echo '<tr><td>' . _('Customer Name') . ':</td><td>' . $_POST['CustName'] . '</td></tr>';
        echo '<tr><td>' . _('Address Line 1 (Street)') . ':</td><td>' . $_POST['Address1'] . '</td></tr>';
        echo '<tr><td>' . _('Address Line 2 (Suburb/City)') . ':</td><td>' . $_POST['Address2'] . '</td></tr>';
        echo '<tr><td>' . _('Address Line 3 (State/Province)') . ':</td><td>' . $_POST['Address3'] . '</td></tr>';
        echo '<tr><td>' . _('Address Line 4 (Postal Code)') . ':</td><td>' . $_POST['Address4'] . '</td></tr>';
        echo '<tr><td>' . _('Address Line 5') . ':</td><td>' . $_POST['Address5'] . '</td></tr>';
        echo '<tr><td>' . _('Address Line 6') . ':</td><td>' . $_POST['Address6'] . '</td></tr>';
        echo '</table></td><td><table class=selection>';
    } else {
        echo '<tr><td>' . _('Customer Name') . ':</td>
            <td><input ' . (in_array('CustName',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="CustName" value="' . $_POST['CustName'] . '" size=42 maxlength=100></td></tr>';
        echo '<tr><td>' . _('Address Line 1 (Street)') . ':</td>
            <td><input ' . (in_array('Address1',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="Address1" size=42 maxlength=200 value="' . $_POST['Address1'] . '"></td></tr>';
        echo '<tr><td>' . _('Address Line 2 (Suburb/City)') . ':</td>
            <td><input ' . (in_array('Address2',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="Address2" size=42 maxlength=200 value="' . $_POST['Address2'] . '"></td></tr>';
        echo '<tr><td>' . _('Address Line 3 (State/Province)') . ':</td>
            <td><input ' . (in_array('Address3',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="Address3" size=42 maxlength=200 value="' . $_POST['Address3'] . '"></td></tr>';
        echo '<tr><td>' . _('Address Line 4 (Postal Code)') . ':</td>
            <td><input ' . (in_array('Address4',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="Address4" size=42 maxlength=40 value="' . $_POST['Address4'] . '"></td></tr>';
        echo '<tr><td>' . _('Address Line 5') . ':</td>
            <td><input ' . (in_array('Address5',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="Address5" size=42 maxlength=40 value="' . $_POST['Address5'] . '"></td></tr>';
        echo '<tr><td>' . _('Address Line 6') . ':</td>
            <td><input ' . (in_array('Address6',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="Address6" size=42 maxlength=40 value="' . $_POST['Address6'] . '"></td></tr>';
        
    }
// Select sales types for drop down list
    if (isset($_GET['Modify'])) {
        $result=DB_query("SELECT sales_type FROM salestypes WHERE typeabbrev='".$_POST['SalesType']."'",$db);
        $myrow=DB_fetch_array($result);
        echo '<tr><td>' . _('Sales Type') . ':</td><td>' . $myrow['sales_type'] . '</td></tr>';
    } else {
        $result=DB_query("SELECT typeabbrev, sales_type FROM salestypes",$db);
        echo '<tr><td>' . _('Sales Type') . '/' . _('Price List') . ':</td>
            <td><select name="SalesType">';
        while ($myrow = DB_fetch_array($result)) {
            if ($_POST['SalesType']==$myrow['typeabbrev']){
                echo '<option selected value="' . $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
            } else {
                echo '<option value="'. $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
            }
        } //end while loop
        DB_data_seek($result,0);
    }

// Select Customer types for drop down list for SELECT/UPDATE
    if (isset($_GET['Modify'])) {
        $result=DB_query("SELECT typename FROM debtortype WHERE typeid='".$_POST['typeid']."'",$db);
        $myrow=DB_fetch_array($result);
        echo '<tr><td>' . _('Customer Type') . ':</td>
                <td>'.$myrow['typename'] . '</td>
            </tr>';
    } else {
        $result=DB_query("SELECT typeid, typename FROM debtortype ORDER BY typename",$db);
        echo '<tr><td>' . _('Customer Type') . ':</td>
                <td><select name="typeid">';
        while ($myrow = DB_fetch_array($result)) {
                if ($_POST['typeid']==$myrow['typeid']){
                    echo '<option selected value="' . $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
                } else {
                    echo '<option value="'. $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
                }
        } //end while loop
        DB_data_seek($result,0);
    }

    if (isset($_GET['Modify'])) {
        echo '</select></td></tr>
            <tr><td>' . _('Customer Since') . ' (' . $_SESSION['DefaultDateFormat'] . '):</td>
                <td>' . $_POST['ClientSince'] . '</td></tr>';
        
        echo '</table></td><td><table class=selection>';
        
        echo '<tr><td>' . _('Discount Percent') . ':</td>
                <td>' . $_POST['Discount'] . '</td></tr>';
        echo '<tr><td>' . _('Discount Code') . ':</td>
                <td>' . $_POST['DiscountCode'] . '</td></tr>';
        echo '<tr><td>' . _('Payment Discount Percent') . ':</td>
                <td>' . $_POST['PymtDiscount'] . '</td></tr>';
        echo '<tr><td>' . _('Credit Limit') . ':</td>
                <td>' . number_format($_POST['CreditLimit'],2) . '</td></tr>';
        echo '<tr><td>' . _('Tax Reference') . ':</td>
                <td>' . $_POST['TaxRef'] . '</td></tr>';
    } else {
        echo '</select></td></tr>
            <tr><td>' . _('Customer Since') . ' (' . $_SESSION['DefaultDateFormat'] . '):</td>
                <td><input ' . (in_array('ClientSince',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" name="ClientSince" size=11 maxlength=10 value=' . $_POST['ClientSince'] . '></td></tr>';
        
        echo '</table></td><td><table class="selection">';
        
        echo '<tr><td>' . _('Discount Percent') . ':</td>
            <td><input type="Text" name="Discount" class=number size=5 maxlength=4 value=' . $_POST['Discount'] . '></td></tr>';
        echo '<tr><td>' . _('Discount Code') . ':</td>
            <td><input ' . (in_array('DiscountCode',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="DiscountCode" size=3 maxlength=2 value="' . $_POST['DiscountCode'] . '"></td></tr>';
        echo '<tr><td>' . _('Payment Discount Percent') . ':</td>
        <td><input ' . (in_array('PymtDiscount',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" class=number name="PymtDiscount" size=5 maxlength=4 value=' . $_POST['PymtDiscount'] . '></td></tr>';
        echo '<tr><td>' . _('Credit Limit') . ':</td>
            <td><input ' . (in_array('CreditLimit',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" class=number name="CreditLimit" size=16 maxlength=14 value=' . $_POST['CreditLimit'] . '></td></tr>';
        echo '<tr><td>' . _('Tax Reference') . ':</td>
            <td><input type="Text" name="TaxRef" size=22 maxlength=20  value="' . $_POST['TaxRef'] . '"></td></tr>';
    }

    if (isset($_GET['Modify'])) {
        $result=DB_query("SELECT terms FROM paymentterms WHERE termsindicator='".$_POST['PaymentTerms']."'",$db);
        $myrow=DB_fetch_array($result);
        echo '<tr><td>' . _('Payment Terms') . ':</td><td>' . $myrow['terms'] . '</td></tr>';
    } else {
        $result=DB_query("SELECT terms, termsindicator FROM paymentterms",$db);
        echo '<tr><td>' . _('Payment Terms') . ':</td>
            <td><select name="PaymentTerms">';
        while ($myrow = DB_fetch_array($result)) {
            if ($_POST['PaymentTerms']==$myrow['termsindicator']){
                echo '<option selected value="'. $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
            } else {
                echo '<option value="'. $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
            }
        } //end while loop
        DB_data_seek($result,0);
        echo '</select></td></tr>';
    }

    if (isset($_GET['Modify'])) {
        $result=DB_query("SELECT reasondescription FROM holdreasons WHERE reasoncode='".$_POST['HoldReason']."'",$db);
        $myrow=DB_fetch_array($result);
        echo '<tr><td>' . _('Credit Status') . ':</td>
                <td>'.$myrow['reasondescription'] . '</td></tr>';
    } else {
        $result=DB_query("SELECT reasoncode, reasondescription FROM holdreasons",$db);
        echo '<tr><td>' . _('Credit Status') . ':</td>
            <td><select name="HoldReason">';
        while ($myrow = DB_fetch_array($result)) {
            if ($_POST['HoldReason']==$myrow['reasoncode']){
                echo '<option selected value="'. $myrow['reasoncode'] . '">' . $myrow['reasondescription'] . '</option>';
            } else {
                echo '<option value="'. $myrow['reasoncode'] . '">' . $myrow['reasondescription'] . '</option>';
            }
        } //end while loop
        DB_data_seek($result,0);
        echo '</select></td></tr>';
    }

    if (isset($_GET['Modify'])) {
        $result=DB_query("SELECT currency FROM currencies WHERE currabrev='".$_POST['CurrCode']."'",$db);
        $myrow=DB_fetch_array($result);
        echo '<tr><td>' . _('Credit Status') . ':</td>
                <td>' . $myrow['currency'] . '</td></tr>';
    } else {
        $result=DB_query("SELECT currency, currabrev FROM currencies",$db);
        echo '<tr><td>' . _('Customers Currency') . ':</td>
            <td><select name="CurrCode">';
        while ($myrow = DB_fetch_array($result)) {
            if ($_POST['CurrCode']==$myrow['currabrev']){
                echo '<option selected value="'. $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
            } else {
                echo '<option value="'. $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
            }
        } //end while loop
        DB_data_seek($result,0);
        echo '</select></td></tr>';
    }
    /*added lines 8/23/2007 by Morris Kelly to get po line parameter Y/N*/
    if (isset($_GET['Modify'])) {
        if ($_POST['CustomerPOLine']==0){
            echo '<tr><td>' . _('Credit Status') . ':</td>
                    <td>'._('No') . '</td>
                </tr>';
        } else {
            echo '<tr><td>' . _('Credit Status') . ':</td>
                    <td>'._('Yes') . '</td>
                    </tr>';
        }
    } else {
        echo '<tr><td>' . _('Require Customer PO Line on SO') . ':</td>
            <td><select name="CustomerPOLine">';
        if ($_POST['CustomerPOLine']==0){
            echo '<option selected value=0>' . _('No') . '</option>';
            echo '<option value=1>' . _('Yes') . '</option>';
        } else {
            echo '<option value=0>' . _('No') . '</option>';
            echo '<option selected value=1>' . _('Yes') . '</option>';
        }
        echo '</select></td></tr>';
    }

    if (isset($_GET['Modify'])) {
        if ($_POST['CustomerPOLine']==0){
            echo '<tr><td>' . _('Invoice Addressing') . ':</td><td>'._('Address to HO').'</td></tr>';
        } else {
            echo '<tr><td>' . _('Invoice Addressing') . ':</td><td>'._('Address to Branch').'</td></tr>';
        }
    } else {
        echo '<tr><td>' . _('Invoice Addressing') . ':</td>
            <td><select name="AddrInvBranch">';
        if ($_POST['InvAddrBranch']==0){
            echo '<option selected value=0>' . _('Address to HO') . '</option>';
            echo '<option value=1>' . _('Address to Branch') . '</option>';
        } else {
            echo '<option value=0>' . _('Address to HO') . '</option>';
            echo '<option selected value=1>' . _('Address to Branch') . '</option>';
        }
    }

    echo '</select></td></tr>
        </table></td></tr>';
    echo '<tr><td colspan=2>';

      $sql = "SELECT * FROM custcontacts where debtorno='".$DebtorNo."' ORDER BY contid";
    $result = DB_query($sql,$db);

    echo '<table class=selection>';
    if (isset($_GET['Modify'])) {
        echo '<tr>
            <th>' . _('Name') . '</th>
            <th>' . _('Role') . '</th>
            <th>' . _('Phone Number') . '</th>
            <th>' . _('Notes') . '</th></tr>';
    } else {
        echo '<tr>
            <th>' . _('Name') . '</th>
            <th>' . _('Role') . '</th>
            <th>' . _('Phone Number') . '</th>
            <th>' . _('Notes') . '</th>
            <th>' . _('Edit') . '</th>
            <th colspan=2><input type="Submit" name="AddContact" value="Add Contact"></th></tr>';
    }
    $k=0; //row colour counter

    while ($myrow = DB_fetch_array($result)) {
        if ($k==1){
            echo '<tr class="OddTableRows">';
            $k=0;
        } else {
            echo '<tr class="EvenTableRows">';
            $k=1;
        }

        if (isset($_GET['Modify'])) {
            printf('<td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                </tr>',
                $myrow[2],
                $myrow[3],
                $myrow[4],
                $myrow[5],
                $myrow[0],
                $myrow[1],
                $myrow[1]);
        } else {
            printf('<td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td><a href="AddCustomerContacts.php?Id=%s&DebtorNo=%s">'. _('Edit'). '</a></td>
                <td><a href="%sID=%s&DebtorNo=%s&delete=1" onclick="return confirm(\'' . _('Are you sure you wish to delete this customer contact?') . '\');">'. _('Delete'). '</a></td>
                </tr>',
                $myrow[2],
                $myrow[3],
                $myrow[4],
                $myrow[5],
                $myrow[0],
                $myrow[1],
                $_SERVER['PHP_SELF'] . '?',
                $myrow[0],
                $myrow[1]);
        }
    }//END WHILE LIST LOOP
    echo '</table>';
    
    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?DebtorNo="'.$DebtorNo.'"&ID='.$ID.'&Edit'.$Edit.'">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    if (isset($Edit) and $Edit!='') {
        $SQLcustcontacts="SELECT * from custcontacts
                            WHERE debtorno='".$DebtorNo."'
                            and contid='".$ID."'";
        $resultcc = DB_query($SQLcustcontacts,$db);
        $myrowcc = DB_fetch_array($resultcc);
        $_POST['custname']=$myrowcc['contactname'];
        $_POST['role']=$myrowcc['role'];
        $_POST['phoneno']=$myrowcc['phoneno'];
        $_POST['notes']=$myrowcc['notes'];
        echo '<table class=selection>';
        echo '<tr><td>' . _('Name') . '</td>
                <td><input type=text name="custname" value="'.$_POST['custname'].'"></td></tr>
            <tr><td>' . _('Role') . '</td>
                <td><input type=text name="role" value="'.$_POST['role'].'"></td></tr>
            <tr><td>' . _('Phone no') . '</td>
                <td><input type="text" name="phoneno" value="'.$_POST['phoneno'].'"></td></tr>
            <tr><td>' . _('Notes') . '</td>
                <td><textarea name="notes">'.$_POST['notes'].'</textarea></td></tr>
            <tr><td colspan=2><input type=submit name=update value=update></td>
            </tr>
            </table>';

        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?DebtorNo='.$DebtorNo.'&ID'.$ID.'">';
        echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


    }
    if (isset($_POST['update'])) {

            $SQLupdatecc="UPDATE custcontacts
                            SET contactname='".$_POST['custname']."',
                            role='".$_POST['role']."',
                            phoneno='".$_POST['phoneno']."',
                            notes='".DB_escape_string($_POST['notes'])."'
                            Where debtorno='".$DebtorNo."'
                            and contid='".$Edit."'";
            $resultupcc = DB_query($SQLupdatecc,$db);
            echo '<br />'.$SQLupdatecc;
            echo '<meta http-equiv="Refresh" content="0; url="' . $_SERVER['PHP_SELF'] . '?DebtorNo='.$DebtorNo.'&ID='.$ID.'">';
        }
    if (isset($_GET['delete'])) {
        $SQl="DELETE FROM custcontacts where debtorno='".$DebtorNo."'
                and contid='".$ID."'";
        $resultupcc = DB_query($SQl,$db);
        echo '<meta http-equiv="Refresh" content="0; url=' . $_SERVER['PHP_SELF'] . '?DebtorNo='.$DebtorNo.'">';
        prnmsg('Contact Deleted','success');
    }


    echo'</td></tr></table>';

    if (isset($_POST['New']) and $_POST['New']) {
        echo '<div class="centre"><input type="submit" name="submit" value="' . _('Add New Customer') .
            '">&nbsp;<input type=submit name="Reset" value="' . _('Reset') . '"></div></form>';
    } else if (!isset($_GET['Modify'])){
        echo '<br /><div class="centre"><input type="submit" name="submit" value="' . _('Update Customer') . '">';
        echo '&nbsp;<input type="Submit" name="delete" value="' . _('Delete Customer') . '" onclick="return confirm(\'' . _('Are You Sure?') . '\');">';
    }
    if(isset($_POST['AddContact']) AND (isset($_POST['AddContact'])!=''))
    {
        echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath . '/AddCustomerContacts.php?DebtorNo=' .$DebtorNo.'">';
    }
    echo '</div>';
} // end of main ifs


function customerCodeGenaration($enquirytype,$db)
{
    if($enquirytype==1){
        $type="D";
    }elseif($enquirytype==2){
       $type="C"; 
    }elseif($enquirytype==3){
       $type="L";  
    }elseif($enquirytype==8){
        $type="DL";    
    }
    
    $sql_code="SELECT * FROM bio_clientidgeneration WHERE enquirytype='".$enquirytype."'";   
    $result_code = DB_query($sql_code,$db);
    $myrow_count = DB_num_rows($result_code);
    $myrow_code = DB_fetch_array($result_code); 
    if($myrow_count>0){
        $code=$myrow_code['code'];
        $code++;
        $sql="UPDATE bio_clientidgeneration  SET code=".$code." WHERE enquirytype='".$enquirytype."'";
        $ID=$type.$code;
    }
    else{
        $code=1;
        $sql="INSERT INTO bio_clientidgeneration(
                                         enquirytype,
                                        code) 
                                  VALUES('".$enquirytype."',
                                         ".$code.")";
        $ID=$type.$code;
    }
    $result = DB_query($sql,$db); 
    return($ID) ;
}
include('includes/footer.inc');
?>




<script type="text/javascript">  

 function showstate(str){ 
  //alert(str);
if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
  //show_progressbar('showstate');

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
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;
    document.getElementById("state").focus();
    }
  }
xmlhttp.open("GET","bio_CustlsgSelection.php?country=" + str,true);
xmlhttp.send();
}

function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;
           document.getElementById('Districts').focus();

    }
  }
xmlhttp.open("GET","bio_CustlsgSelection.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();
}

 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
  //alert(str);  alert(str1);   alert(str2);       alert(str3);   
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==11 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }

if (str=="")
  {
  document.getElementById("block").innerHTML="";
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
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

 function showgramapanchayath(str){   
   //alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showgramapanchayath").innerHTML="";
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
     document.getElementById("showpanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}        

 function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str1=="")
  {
  document.getElementById("showtaluk").innerHTML="";
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
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?taluk=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

</script>