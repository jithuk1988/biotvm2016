<?php
  $PageSecurity = 80;
  
  include('includes/DefineReceiptClass.php');
  include('includes/session.inc');
  $title = _('Receipt Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

  $id=$_GET['lead'];
  $advance=$_GET['advance'];
  $debtorno=$_GET['id'];
  
if($id!=''){
$sql2="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.area1,
                    bio_leads.leaddate,
                    bio_advance.amount,
                    bio_cashheads.heads,
                    bio_advance.date,
                    bio_advance.serialnum,
                    bio_advance.bankname 
           FROM     bio_leads,
                    bio_cust,
                    bio_advance,
                    bio_cashheads
         WHERE      bio_leads.leadid=bio_advance.leadid
          AND       bio_advance.head_id=bio_cashheads.head_id     
          AND       bio_leads.cust_id=bio_cust.cust_id
          AND       bio_advance.adv_id=".$advance;
         
       

$result=DB_query($sql2,$db);  
$row=DB_fetch_array($result);
$amount=$row['amount'];  
$bname=$row['bankname'];
}

if($debtorno!=''){
    $sql_debt="SELECT * FROM debtorsmaster
                WHERE debtorno='".$debtorno."'";
    $result_debt=DB_query($sql_debt,$db);  
    $myrow_debt=DB_fetch_array($result_debt);
    
}
  
  
  
  
  $SQL = "SELECT bankaccountname,
                 bankaccounts.accountcode,
                 bankaccounts.currcode
        FROM     bankaccounts,
                 chartmaster
        WHERE    bankaccounts.accountcode=chartmaster.accountcode";

$AccountsResults = DB_query($SQL,$db);
$row1=DB_fetch_array($AccountsResults);
$name=$row1['bankaccountname'];
/*
if (($_POST['BankAccount']=='')) {
    echo '<br />';
    prnMsg(_('A bank account must be selected for this receipt'), 'warn');
    $BankAccountEmpty=TRUE;
} else 
*/
if(isset($_POST['BankAccount'])) {
    $BankAccountEmpty=TRUE;
} else {
    $BankAccountEmpty=FALSE;
}

if (!isset($_GET['Delete']) AND isset($_SESSION['ReceiptBatch'])){ //always process a header update unless deleting an item


    $_SESSION['ReceiptBatch']->Account = $_POST['BankAccount'];
    /*Get the bank account currency and set that too */
/*
    $SQL = "SELECT bankaccountname, currcode FROM bankaccounts WHERE accountcode='" . $_POST['BankAccount']."'";
    $ErrMsg =_('The bank account name cannot be retrieved because');
    $result= DB_query($SQL,$db,$ErrMsg);

    if (DB_num_rows($result)==1){
        $myrow = DB_fetch_row($result);
        $_SESSION['ReceiptBatch']->BankAccountName = $myrow[0];
        $_SESSION['ReceiptBatch']->AccountCurrency=$myrow[1];
        unset($result);
    } elseif (DB_num_rows($result)==0 and !$BankAccountEmpty){
        prnMsg( _('The bank account number') . ' ' . $_POST['BankAccount'] . ' ' . _('is not set up as a bank account'),'error');
        include ('includes/footer.inc');
        exit;
    }
*/
    if (!Is_Date($_POST['DateBanked'])){
        $_POST['DateBanked'] = Date($_SESSION['DefaultDateFormat']);
    }
    $_SESSION['ReceiptBatch']->DateBanked = $_POST['DateBanked'];
    if (isset($_POST['ExRate']) and $_POST['ExRate']!=''){
        if (is_numeric($_POST['ExRate'])){
            $_SESSION['ReceiptBatch']->ExRate = $_POST['ExRate'];
        } else {
            prnMsg(_('The exchange rate entered should be numeric'),'warn');
        }
    }
    if (isset($_POST['FunctionalExRate']) and $_POST['FunctionalExRate']!=''){
        if (is_numeric($_POST['FunctionalExRate'])){
            $_SESSION['ReceiptBatch']->FunctionalExRate=$_POST['FunctionalExRate']; //ex rate between receipt currency and account currency
        } else {
            prnMsg(_('The functional exchange rate entered should be numeric'),'warn');
        }
    }
    $_SESSION['ReceiptBatch']->ReceiptType = $_POST['ReceiptType'];

    if (!isset($_POST['Currency'])){
        $_POST['Currency']=$_SESSION['CompanyRecord']['currencydefault'];
    }

    if ($_SESSION['ReceiptBatch']->Currency!=$_POST['Currency']){

        $_SESSION['ReceiptBatch']->Currency=$_POST['Currency']; //receipt currency
        /*Now customer receipts entered using the previous currency need to be ditched
        and a warning message displayed if there were some customer receipted entered */
        if (count($_SESSION['ReceiptBatch']->Items)>0){
            unset($_SESSION['ReceiptBatch']->Items);
            prnMsg(_('Changing the currency of the receipt means that existing entries need to be re-done - only customers trading in the selected currency can be selected'),'warn');
        }

    }

    /*Get the exchange rate between the functional currency and the receipt currency*/
    $result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['ReceiptBatch']->Currency . "'",$db);
    $myrow = DB_fetch_row($result);
    $tableExRate = $myrow[0]; //this is the rate of exchange between the functional currency and the receipt currency

    if ($_POST['Currency']==$_SESSION['ReceiptBatch']->AccountCurrency){
        $_SESSION['ReceiptBatch']->ExRate = 1; //ex rate between receipt currency and account currency
        $SuggestedExRate=1;
    }
    if ($_SESSION['ReceiptBatch']->AccountCurrency==$_SESSION['CompanyRecord']['currencydefault']){
        $_SESSION['ReceiptBatch']->FunctionalExRate = 1;
        $SuggestedFunctionalExRate =1;
        $SuggestedExRate = $tableExRate;

    } else if (!$BankAccountEmpty) {
        /*To illustrate the rates required
            Take an example functional currency NZD receipt in USD from an AUD bank account
            1 NZD = 0.80 USD
            1 NZD = 0.90 AUD
            The FunctionalExRate = 0.90 - the rate between the functional currency and the bank account currency
            The receipt ex rate is the rate at which one can sell the received currency and purchase the bank account currency
            or 0.8/0.9 = 0.88889
        */

        /*Get suggested FunctionalExRate */
        $result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['ReceiptBatch']->AccountCurrency . "'",$db);
        $myrow = DB_fetch_row($result);
        $SuggestedFunctionalExRate = $myrow[0];

        /*Get the exchange rate between the functional currency and the receipt currency*/
        $result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['ReceiptBatch']->Currency . "'",$db);
        $myrow = DB_fetch_row($result);
        $tableExRate = $myrow[0]; //this is the rate of exchange between the functional currency and the receipt currency
        /*Calculate cross rate to suggest appropriate exchange rate between receipt currency and account currency */
        $SuggestedExRate = $tableExRate/$SuggestedFunctionalExRate;
    } //end else account currency != functional currency

    $_SESSION['ReceiptBatch']->Narrative = $_POST['BatchNarrative'];

} elseif (isset($_GET['Delete'])) {
    /* User hit delete the receipt entry from the batch */
    $_SESSION['ReceiptBatch']->remove_receipt_item($_GET['Delete']);
} else { //it must be a new receipt batch
    $_SESSION['ReceiptBatch'] = new Receipt_Batch;
}


if (isset($_POST['Process'])){ //user hit submit a new entry to the receipt batch

    if (!isset($_POST['GLCode'])) {
        $_POST['GLCode']='';
    }
    if (!isset($_POST['tag'])) {
        $_POST['tag']='';
    }
    if (!isset($_POST['CustomerID'])) {
        $_POST['CustomerID']='';
    }
    if (!isset($_POST['CustomerName'])) {
        $_POST['CustomerName']='';
    }

    if ($_POST['GLCode'] == '' and $_GET['Type']=='GL') {
        prnMsg( _('No General Ledger code has been chosen') . ' - ' . _('so this GL analysis item could not be added'),'warn');
    } else {
        $_SESSION['ReceiptBatch']->add_to_batch($_POST['Amount'],
                                            $_POST['CustomerID'],
                                            $_POST['Discount'],
                                            $_POST['Narrative'],
                                            $_POST['GLCode'],
                                            $_POST['PayeeBankDetail'],
                                            $_POST['CustomerName'],
                                            $_POST['tag']);
                                            
        $_SESSION['AdvNo']=$_POST['AdvanceNo'];

        /*Make sure the same receipt is not double processed by a page refresh */
        $Cancel = 1;
    }
    
    
}

if (isset($Cancel)){
    unset($_SESSION['CustomerRecord']);
    unset($_POST['CustomerID']);
    unset($_POST['CustomerName']);
    unset($_POST['Amount']);
    unset($_POST['Discount']);
    unset($_POST['Narrative']);
    unset($_POST['PayeeBankDetail']);
}


if (isset($_POST['CommitBatch'])){

 /* once all receipts items entered, process all the data in the
  session cookie into the DB creating a single banktrans for the whole amount
  of all receipts in the batch and DebtorTrans records for each receipt item
  all DebtorTrans will refer to a single banktrans. A GL entry is created for
  each GL receipt entry and one for the debtors entry and one for the bank
  account debit

  NB allocations against debtor receipts are a separate exercice

  first off run through the array of receipt items $_SESSION['ReceiptBatch']->Items and
  if GL integrated then create GL Entries for the GL Receipt items
  and add up the non-GL ones for posting to debtors later,
  also add the total discount total receipts*/

    $PeriodNo = GetPeriod($_SESSION['ReceiptBatch']->DateBanked,$db);

    if ($_SESSION['CompanyRecord']==0){
        prnMsg(_('The company has not yet been set up properly') . ' - ' . _('this information is needed to process the batch') . '. ' . _('Processing has been cancelled'),'error');
        include('includes/footer.inc');
        exit;
    }

    /*Make an array of the defined bank accounts */
    $SQL = "SELECT accountcode FROM bankaccounts";
    $result = DB_query($SQL,$db);
    $BankAccounts = array();
    $i=0;
    while ($Act = DB_fetch_row($result)){
        $BankAccounts[$i]= $Act[0];
        $i++;
    }

    $_SESSION['ReceiptBatch']->BatchNo = GetNextTransNo(12,$db);
    /*Start a transaction to do the whole lot inside */
    $result = DB_Txn_Begin($db);

    $BatchReceiptsTotal = 0; //in functional currency
    $BatchDiscount = 0; //in functional currency
    $BatchDebtorTotal = 0; //in functional currency
    $k=0; //Table row counter for row styles
    $CustomerReceiptCounter=1; //Count lines of customer receipts in this batch

    echo '<br /><p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/money_add.png" title="' . _('Allocate') . '" alt="" />' . ' ' . _('Summary of Receipt Batch').'</p><br />';

    echo '<table class=selection><tr><th>'._('Batch Number').'</th>
                <th>' . _('Date Banked') . '</th>
                <th>' . _('Customer Name') . '</th>
                <th>' . _('GL Code') . '</th>
                <th>' . _('Amount of Receipt').'</th></tr>';

    foreach ($_SESSION['ReceiptBatch']->Items as $ReceiptItem) {

        if ($k==1){
            echo '<tr class="OddTableRows">';
            $k=0;
        } else {
            echo '<tr class="EvenTableRows">';
            $k=1;
        }

        $SQL = "SELECT accountname FROM chartmaster WHERE accountcode='" . $ReceiptItem->GLCode . "'";
        $Result=DB_query($SQL,$db);
        $myrow=DB_fetch_array($Result);

        echo '<td>'.$_SESSION['ReceiptBatch']->BatchNo.'</td>
                <td>'.$_SESSION['ReceiptBatch']->DateBanked.'</td>
                <td>'.$ReceiptItem->CustomerName.'</td>
                <td>'.$ReceiptItem->GLCode.' - '.$myrow['accountname'].'</td>
                <td class=number>'.number_format($ReceiptItem->Amount/$_SESSION['ReceiptBatch']->ExRate/$_SESSION['ReceiptBatch']->FunctionalExRate,2) .'</td>';

        if ($ReceiptItem->GLCode ==''){
            echo '<td><a target="_blank"  href="' . $rootpath . '/PDFReceipt.php?BatchNumber=' . $_SESSION['ReceiptBatch']->BatchNo. '&ReceiptNumber='.$CustomerReceiptCounter.'">'._('Print a Customer Receipt').'</a></td></tr>';
            $CustomerReceiptCounter += 1;
        }

        if ($ReceiptItem->GLCode !=''){ //so its a GL receipt
            if ($_SESSION['CompanyRecord']['gllink_debtors']==1){ /* then enter a GLTrans record */
                 $SQL = "INSERT INTO gltrans (type,
                                             typeno,
                                            trandate,
                                            periodno,
                                            account,
                                            narrative,
                                            amount,
                                            tag)
                    VALUES (
                        12,
                        '" . $_SESSION['ReceiptBatch']->BatchNo . "',
                        '" . FormatDateForSQL($_SESSION['ReceiptBatch']->DateBanked) . "',
                        '" . $PeriodNo . "',
                        '" . $ReceiptItem->GLCode . "',
                        '" . $ReceiptItem->Narrative . "',
                        '" . -($ReceiptItem->Amount/$_SESSION['ReceiptBatch']->ExRate/$_SESSION['ReceiptBatch']->FunctionalExRate) . "',
                        '" . $ReceiptItem->tag . "'" . "
                    )";
                $ErrMsg = _('Cannot insert a GL entry for the receipt because');
                $DbgMsg = _('The SQL that failed to insert the receipt GL entry was');
                $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
            }

            /*check to see if this is a GL posting to another bank account (or the same one)
            if it is then a matching payment needs to be created for this account too */

            if (in_array($ReceiptItem->GLCode, $BankAccounts)) {

            /*Need to deal with the case where the payment from one bank account could be to a bank account in another currency */

                /*Get the currency and rate of the bank account transferring to*/
                $SQL = "SELECT currcode, rate
                            FROM bankaccounts INNER JOIN currencies
                            ON bankaccounts.currcode = currencies.currabrev
                            WHERE accountcode='" . $ReceiptItem->GLCode."'";
                $TrfFromAccountResult = DB_query($SQL,$db);
                $TrfFromBankRow = DB_fetch_array($TrfFromAccountResult) ;
                $TrfFromBankCurrCode = $TrfFromBankRow['currcode'];
                $TrfFromBankExRate = $TrfFromBankRow['rate'];

                if ($_SESSION['ReceiptBatch']->AccountCurrency == $TrfFromBankCurrCode){
                    /*Make sure to use the same rate if the transfer is between two bank accounts in the same currency */
                    $TrfFromBankExRate = $_SESSION['ReceiptBatch']->FunctionalExRate;
                }

                /*Consider an example - had to be currencies I am familar with sorry so I could figure it out!!
                     functional currency NZD
                     bank account in AUD - 1 NZD = 0.90 AUD (FunctionalExRate)
                     receiving USD - 1 AUD = 0.85 USD  (ExRate)
                     from a bank account in EUR - 1 NZD = 0.52 EUR

                     oh yeah - now we are getting tricky!
                     Lets say we received USD 100 to the AUD bank account from the EUR bank account

                     To get the ExRate for the bank account we are transferring money from
                     we need to use the cross rate between the NZD-AUD/NZD-EUR
                     and apply this to the

                     the receipt record will read
                     exrate = 0.85 (1 AUD = USD 0.85)
                     amount = 100 (USD)
                     functionalexrate = 0.90 (1 NZD = AUD 0.90)

                     the payment record will read

                     amount 100 (USD)
                     exrate    (1 EUR =  (0.85 x 0.90)/0.52 USD  ~ 1.47
                                          (ExRate x FunctionalExRate) / USD Functional ExRate
                     Check this is 1 EUR = 1.47 USD
                     functionalexrate =  (1NZD = EUR 0.52)

                */

                $PaymentTransNo = GetNextTransNo( 1, $db);
                $SQL="INSERT INTO banktrans (transno,
                                            type,
                                            bankact,
                                            ref,
                                            exrate,
                                            functionalexrate,
                                            transdate,
                                            banktranstype,
                                            amount,
                                            currcode)
                        VALUES (
                            '" . $PaymentTransNo . "',
                            1,
                            '" . $ReceiptItem->GLCode . "',
                            '" . _('Act Transfer') ." - " . $ReceiptItem->Narrative . "',
                            '" . (($_SESSION['ReceiptBatch']->ExRate * $_SESSION['ReceiptBatch']->FunctionalExRate)/$TrfFromBankExRate). "',
                            '" . $TrfFromBankExRate . "',
                            '" . FormatDateForSQL($_SESSION['ReceiptBatch']->DateBanked) . "',
                            '" . $_SESSION['ReceiptBatch']->ReceiptType . "',
                            '" . -$ReceiptItem->Amount . "',
                            '" . $_SESSION['ReceiptBatch']->Currency . "'
                        )";

                $DbgMsg = _('The SQL that failed to insert the bank transaction was');
                $ErrMsg = _('Cannot insert a bank transaction using the SQL');
                $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
            } //end if an item is a transfer between bank accounts

        } else { //its not a GL item - its a customer receipt then
            /*Accumulate the total debtors credit including discount */
            $BatchDebtorTotal += (($ReceiptItem->Discount + $ReceiptItem->Amount)/$_SESSION['ReceiptBatch']->ExRate/$_SESSION['ReceiptBatch']->FunctionalExRate);
            /*Create a DebtorTrans entry for each customer deposit */

            $SQL = "INSERT INTO debtortrans (transno,
                                            type,
                                            debtorno,
                                            branchcode,
                                            trandate,
                                            inputdate,
                                            prd,
                                            reference,
                                            tpe,
                                            rate,
                                            ovamount,
                                            ovdiscount,
                                            invtext)
                    VALUES (
                        '" . $_SESSION['ReceiptBatch']->BatchNo . "',
                        12,
                        '" . $ReceiptItem->Customer . "',
                        '',
                        '" . FormatDateForSQL($_SESSION['ReceiptBatch']->DateBanked) . "',
                        '" . date('Y-m-d H-i-s') . "',
                        '" . $PeriodNo . "',
                        '" . $_SESSION['ReceiptBatch']->ReceiptType  . ' ' . $ReceiptItem->PayeeBankDetail . "',
                        '',
                        '" . ($_SESSION['ReceiptBatch']->ExRate/$_SESSION['ReceiptBatch']->FunctionalExRate) . "',
                        '" . -$ReceiptItem->Amount . "',
                        '" . -$ReceiptItem->Discount . "',
                        '" . $ReceiptItem->Narrative. "'
                    )";
            $DbgMsg = _('The SQL that failed to insert the customer receipt transaction was');
            $ErrMsg = _('Cannot insert a receipt transaction against the customer because') ;
            $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

            $SQL = "UPDATE debtorsmaster
                        SET lastpaiddate = '" . FormatDateForSQL($_SESSION['ReceiptBatch']->DateBanked) . "',
                        lastpaid='" . $ReceiptItem->Amount ."'
                    WHERE debtorsmaster.debtorno='" . $ReceiptItem->Customer . "'";

            $DbgMsg = _('The SQL that failed to update the date of the last payment received was');
            $ErrMsg = _('Cannot update the customer record for the date of the last payment received because');
            $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
            
            $sql_transfer="UPDATE bio_advance
                        SET ledgerstatus =1
                    WHERE bio_advance.adv_id='" . $_SESSION['AdvNo'] . "'";
            $DbgMsg = _('The SQL that failed to update the Advance id of the last payment received was');
            $ErrMsg = _('Cannot update the record for the Advance id of the last payment received because');
            $result = DB_query($sql_transfer,$db,$ErrMsg,$DbgMsg,true);
            unset($_SESSION['AdvNo']);

        } //end of if its a customer receipt
        $BatchDiscount += ($ReceiptItem->Discount/$_SESSION['ReceiptBatch']->ExRate/$_SESSION['ReceiptBatch']->FunctionalExRate);
        $BatchReceiptsTotal += ($ReceiptItem->Amount/$_SESSION['ReceiptBatch']->ExRate/$_SESSION['ReceiptBatch']->FunctionalExRate);

    } /*end foreach $ReceiptItem */
    echo '</table>';

    if ($_SESSION['CompanyRecord']['gllink_debtors']==1){ /* then enter GLTrans records for discount, bank and debtors */

        if ($BatchReceiptsTotal!=0){
            /* Bank account entry first */
            $SQL="INSERT INTO gltrans (type,
                                        typeno,
                                        trandate,
                                        periodno,
                                        account,
                                        narrative,
                                        amount)
                VALUES (
                    12,
                    '" . $_SESSION['ReceiptBatch']->BatchNo . "',
                    '" . FormatDateForSQL($_SESSION['ReceiptBatch']->DateBanked) . "',
                    '" . $PeriodNo . "',
                    '" . $_SESSION['ReceiptBatch']->Account . "',
                    '" . $_SESSION['ReceiptBatch']->Narrative . "',
                    '" . $BatchReceiptsTotal . "'
                )";
            $DbgMsg = _('The SQL that failed to insert the GL transaction fro the bank account debit was');
            $ErrMsg = _('Cannot insert a GL transaction for the bank account debit');
            $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

            /*now enter the BankTrans entry */

            $SQL="INSERT INTO banktrans (type,
                                        transno,
                                        bankact,
                                        ref,
                                        exrate,
                                        functionalexrate,
                                        transdate,
                                        banktranstype,
                                        amount,
                                        currcode)
                VALUES (
                    12,
                    '" . $_SESSION['ReceiptBatch']->BatchNo . "',
                    '" . $_SESSION['ReceiptBatch']->Account . "',
                    '" . $_SESSION['ReceiptBatch']->Narrative . "',
                    '" . $_SESSION['ReceiptBatch']->ExRate . "',
                    '" . $_SESSION['ReceiptBatch']->FunctionalExRate . "',
                    '" . FormatDateForSQL($_SESSION['ReceiptBatch']->DateBanked) . "',
                    '" . $_SESSION['ReceiptBatch']->ReceiptType . "',
                    '" . ($BatchReceiptsTotal * $_SESSION['ReceiptBatch']->FunctionalExRate * $_SESSION['ReceiptBatch']->ExRate) . "',
                    '" . $_SESSION['ReceiptBatch']->Currency . "'
                )";
            $DbgMsg = _('The SQL that failed to insert the bank account transaction was');
            $ErrMsg = _('Cannot insert a bank transaction');
            $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
        }
        if ($BatchDebtorTotal!=0){
            /* Now Credit Debtors account with receipts + discounts */
            $SQL="INSERT INTO gltrans ( type,
                                        typeno,
                                        trandate,
                                        periodno,
                                        account,
                                        narrative,
                                        amount)
                        VALUES (
                            12,
                            '" . $_SESSION['ReceiptBatch']->BatchNo . "',
                            '" . FormatDateForSQL($_SESSION['ReceiptBatch']->DateBanked) . "',
                            '" . $PeriodNo . "',
                            '". $_SESSION['CompanyRecord']['debtorsact'] . "',
                            '" . $_SESSION['ReceiptBatch']->Narrative . "',
                            '" . -$BatchDebtorTotal . "'
                            )";
            $DbgMsg = _('The SQL that failed to insert the GL transaction for the debtors account credit was');
            $ErrMsg = _('Cannot insert a GL transaction for the debtors account credit');
            $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

        } //end if there are some customer deposits in this batch

        if ($BatchDiscount!=0){
            /* Now Debit Discount account with discounts allowed*/
            $SQL="INSERT INTO gltrans ( type,
                                        typeno,
                                        trandate,
                                        periodno,
                                        account,
                                        narrative,
                                        amount)
                        VALUES (
                                12,
                                '" . $_SESSION['ReceiptBatch']->BatchNo . "',
                                '" . FormatDateForSQL($_SESSION['ReceiptBatch']->DateBanked) . "',
                                '" . $PeriodNo . "',
                                '" . $_SESSION['CompanyRecord']['pytdiscountact'] . "',
                                '" . $_SESSION['ReceiptBatch']->Narrative . "',
                                '" . $BatchDiscount . "'
                            )";
            $DbgMsg = _('The SQL that failed to insert the GL transaction for the payment discount debit was');
            $ErrMsg = _('Cannot insert a GL transaction for the payment discount debit');
            $result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
        } //end if there is some discount
    } //end if there is GL work to be done - ie config is to link to GL


    $ErrMsg = _('Cannot commit the changes');
    $DbgMsg = _('The SQL that failed was');
    $result = DB_Txn_Commit($db);
    echo '<br />';
    prnMsg( _('Receipt batch') . ' ' . $_SESSION['ReceiptBatch']->BatchNo . ' ' . _('has been successfully entered into the database'),'success');

    echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/printer.png" title="' . _('Print') . '" alt="">' . ' ' . '<a href="' . $rootpath . '/PDFBankingSummary.php?BatchNo=' . $_SESSION['ReceiptBatch']->BatchNo . '">' . _('Print PDF Batch Summary') . '</a></p>';
    echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/allocation.png" title="' . _('Allocate') . '" alt="">' . ' ' . '<a href="' . $rootpath . '/CustomerAllocations.php">' . _('Allocate Receipts') . '</a></p>';
    unset($_SESSION['ReceiptBatch']);
    include('includes/footer.inc');
    exit;

} /* End of commit batch */
 echo '<form action="' . $_SERVER['PHP_SELF'] . '?Type='.$_GET['Type'] . '" method="post" name="form1">';
 echo"<input type='hidden' name='CustomerID' id='debtorno' value='$debtorno'>";
 
 echo"<input type='hidden' name='AdvanceNo' id='advanceno' value='$advance'>";
  
 echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Customer') . '" alt="">' . ' ' . $myrow_debt['name'] . ' - (' . _('All amounts stated in') . ' ' . $_SESSION['CustomerRecord']['currency'] . ')' . _('Terms') . ': ' . $_SESSION['CustomerRecord']['terms'] . '<br/>' . _('Credit Limit') . ': ' . number_format($_SESSION['CustomerRecord']['creditlimit'],0) . '  ' . _('Credit Status') . ': ' . $_SESSION['CustomerRecord']['reasondescription'];

    if ($_SESSION['CustomerRecord']['dissallowinvoices']!=0){
echo '<br><font color=red size=4><b>' . _('ACCOUNT ON HOLD') . '</font></b><br/>';
    }
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/transactions.png" title="' . _('Enter Receipt') . '" alt="">' . ' ' . _('Enter Customer Receipt') . '</p>';

/*
echo '<div class="page_help_text">' . _('To enter a payment TO a customer (ie. to pay out a credit note), enter a negative payment amount.') . '</div>';
*/
echo '<table style=width:55%><tr><td>';
  
  
echo '<fieldset style="height:450px">';
    
  
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 

  

if (isset($_POST['GLEntry'])) {
   
 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/transactions.png" title="' . _('Enter Receipt') . '" alt="">' . ' ' . _('Enter Customer Receipt') . '</p>';
echo '<div class="page_help_text">' . _('To enter a payment TO a customer (ie. to pay out a credit note), enter a negative payment amount.') . '</div>';
}
echo '<br><table><tr><td>' . _('Bank Account') . ':</td>
                 <td><select tabindex=1 name="BankAccount">';

if (DB_num_rows($AccountsResults)==0){
    echo '</select></td></tr></table><p>';
    prnMsg(_('Bank Accounts have not yet been defined') . '. ' . _('You must first') . ' ' . '<a href="' . $rootpath . '/BankAccounts.php">' . _('define the bank accounts') . '</a>' . _('and general ledger accounts to be affected'),'info');
    include('includes/footer.inc');
     exit;
} else {
    echo '<option value=""></option>';
    while ($myrow=DB_fetch_array($AccountsResults)){
        /*list the bank account names */
        if ($_SESSION['ReceiptBatch']->Account==$myrow['accountcode']){
            echo '<option selected value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . ' - ' . $myrow['currcode'] . '</option>';
        } else {
            echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname']. ' - ' . $myrow['currcode'] . '</option>';
        }
    }
    echo '</select></td></tr>';
}

if (!Is_Date($_SESSION['ReceiptBatch']->DateBanked)){
    $_SESSION['ReceiptBatch']->DateBanked = Date($_SESSION['DefaultDateFormat']);
}

echo '<tr><td>' . _('Date Banked') . ':</td>
        <td><input tabindex=2 type="text" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" name="DateBanked" maxlength=10 size=10 onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' . $_SESSION['ReceiptBatch']->DateBanked . '"></td></tr>';
echo '<tr><td>' . _('Currency') . ':</td>
        <td><select tabindex=3 name="Currency">';

if (!isset($_SESSION['ReceiptBatch']->Currency)){
  $_SESSION['ReceiptBatch']->Currency=$_SESSION['CompanyRecord']['currencydefault'];
}

$SQL = 'SELECT currency, currabrev, rate FROM currencies';
$result=DB_query($SQL,$db);
if (DB_num_rows($result)==0){
    echo '</select></td></tr>';
    prnMsg(_('No currencies are defined yet') . '. ' . _('Receipts cannot be entered until a currency is defined'),'warn');

} else {
    while ($myrow=DB_fetch_array($result)){
        if ($_SESSION['ReceiptBatch']->Currency==$myrow['currabrev']){
            echo '<option selected value="' . $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
        } else {
            echo '<option value="' . $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
        }
    }
    echo '</select></td></tr>';
}


if (!isset($_SESSION['ReceiptBatch']->ExRate)){
    $_SESSION['ReceiptBatch']->ExRate=1;
}

if (!isset($_SESSION['ReceiptBatch']->FunctionalExRate)){
    $_SESSION['ReceiptBatch']->FunctionalExRate=1;
}
if ($_SESSION['ReceiptBatch']->AccountCurrency!=$_SESSION['ReceiptBatch']->Currency AND isset($_SESSION['ReceiptBatch']->AccountCurrency)){
    if (isset($SuggestedExRate)){
        $SuggestedExRateText = '<b>' . _('Suggested rate:') . ' ' . number_format($SuggestedExRate,4) . '</b>';
    } else {
        $SuggestedExRateText ='';
    }
    if ($_SESSION['ReceiptBatch']->ExRate==1 AND isset($SuggestedExRate)){
        $_SESSION['ReceiptBatch']->ExRate = $SuggestedExRate;
    }
    echo '<tr><td>' . _('Receipt Exchange Rate') . ':</td>
            <td><input tabindex=4 type="text" name="ExRate" maxlength=10 size=12 class=number value="' . $_SESSION['ReceiptBatch']->ExRate . '"></td>
            <td>' . $SuggestedExRateText . ' <i>' . _('The exchange rate between the currency of the bank account currency and the currency of the receipt') . '. 1 ' . $_SESSION['ReceiptBatch']->AccountCurrency . ' = ? ' . $_SESSION['ReceiptBatch']->Currency . '</i></td></tr>';
}

if ($_SESSION['ReceiptBatch']->AccountCurrency!=$_SESSION['CompanyRecord']['currencydefault']
                                            AND isset($_SESSION['ReceiptBatch']->AccountCurrency)){
    if (isset($SuggestedFunctionalExRate)){
        $SuggestedFunctionalExRateText = '<b>' . _('Suggested rate:') . ' ' . number_format($SuggestedFunctionalExRate,4) . '</b>';
    } else {
        $SuggestedFunctionalExRateText ='';
    }
    if ($_SESSION['ReceiptBatch']->FunctionalExRate==1 AND isset($SuggestedFunctionalExRate)){
        $_SESSION['ReceiptBatch']->FunctionalExRate = $SuggestedFunctionalExRate;
    }
    echo '<tr><td>' . _('Functional Exchange Rate') . ':</td><td><input tabindex=5 type="text" name="FunctionalExRate" class=number maxlength=10 size=12 value="' . $_SESSION['ReceiptBatch']->FunctionalExRate . '"></td>
            <td>' . ' ' . $SuggestedFunctionalExRateText . ' <i>' . _('The exchange rate between the currency of the business (the functional currency) and the currency of the bank account') .  '. 1 ' . $_SESSION['CompanyRecord']['currencydefault'] . ' = ? ' . $_SESSION['ReceiptBatch']->AccountCurrency . '</i></td></tr>';
}

echo '<tr><td>' . _('Receipt Type') . ':</td><td><select tabindex=6 name="ReceiptType">';

include('includes/GetPaymentMethods.php');
/* The array ReceiptTypes is defined from the setup tab of the main menu under payment methods - the array is populated from the include file GetPaymentMethods.php */

foreach ($ReceiptTypes as $RcptType) {
    if (isset($_POST['ReceiptType']) and $_POST['ReceiptType']==$RcptType){
        echo '<option selected value="' . $RcptType . '">' . $RcptType .'</option>';
    } else {
        echo '<option value="' .$RcptType . '">' . $RcptType .'</option>';
    }
}
echo '</select></td></tr>';
if (!isset($_SESSION['ReceiptBatch']->Narrative)) {
    $_SESSION['ReceiptBatch']->Narrative='';
}

echo '<tr><td>' . _('Narrative') . ':</td><td><input tabindex=7 type="text" name="BatchNarrative" maxlength=50 size=52 value="' . $type. '"></td></tr>';


//echo '<tr><td colspan=3><div class="centre"><input tabindex=8 type=submit name="BatchInput" Value="' . _('Accept') . '"></div></td></tr>';

 

//echo '</table><br />';


  
  

echo'<br>';


// echo '<table width="100%">';
 /*
            <tr>
                <th width=20%>' . _('Total Balance') . '</th>
                <th width=20%>' . _('Current') . '</th>
                <th width=20%>' . _('Now Due') . '</th>
                <th width=20%>' . $_SESSION['PastDueDays1'] . '-' . $_SESSION['PastDueDays2'] . ' ' . _('Days Overdue') . '</th>
                <th width=20%>' . _('Over') . ' ' . $_SESSION['PastDueDays2'] . ' ' . _('Days Overdue') . '</th>
            </tr>';


echo '<tr>
        <td align=center>' . number_format($_SESSION['CustomerRecord']['balance'],2) . '</td>
        <td align=center>' . number_format(($_SESSION['CustomerRecord']['balance'] - $_SESSION['CustomerRecord']['due']),2) . '</td>
        <td align=center>' . number_format(($_SESSION['CustomerRecord']['due']-$_SESSION['CustomerRecord']['overdue1']),2) . '</td>
        <td align=center>' . number_format(($_SESSION['CustomerRecord']['overdue1']-$_SESSION['CustomerRecord']['overdue2']) ,2) . '</td>
        <td align=center>' . number_format($_SESSION['CustomerRecord']['overdue2'],2) . '</td>
        </tr>';
*/
// echo '<tr><table>';

    $DisplayDiscountPercent = number_format($_SESSION['CustomerRecord']['pymtdiscount']*100,2) . '%';

echo '<br>';

echo '<tr><td>' . _('Amount of Receipt') . ':</td>
        <td><input tabindex=9 type="text" name="Amount" maxlength=12 size=16  value="'.$amount.'"></td>
    </tr>';

echo '<tr><td>' . _('Payee Bank Details') . ':</td>
        <td><input tabindex=12 type="text" name="PayeeBankDetail" maxlength=22 size=40 value="' . $name . '"></td></tr>';
echo '<tr><td>' . _('Narrative') . ':</td>';
    
  echo '  <td><input tabindex=12 type="text" name="Narrative" maxlength=22 size=40 value="' . $details . '"></td></tr>';    
    
 echo '<tr><td></td>';   
     echo '<td><input tabindex=14 type="submit" name="Process" value="' . _('Accept') . '">&nbsp;&nbsp;&nbsp;&nbsp;<input tabindex=14 type="submit" name="Cancel" value="' . _('Cancel') . '"></td></tr>';
 
 echo '</table>';
 if (isset($_SESSION['ReceiptBatch'])){
 
 if (!$BankAccountEmpty) {
        echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/transactions.png" title="' . _('Banked') .
            '" alt="">' . ' ' . $_SESSION['ReceiptBatch']->ReceiptType . ' - ' . _('Banked into the') . " " .
                $_SESSION['ReceiptBatch']->BankAccountName . ' ' . _('on') . ' ' . $_SESSION['ReceiptBatch']->DateBanked . '</p>';
    }
  
  
  
echo '<table width="100%" ><tr>
            <th>' . _('Amount') . ' ' . _('Received') . '</th>
            <th>' . _('Discount') . '</th>
            <th>' . _('Customer') . '</th>
            <th>' . _('GL Code') . '</th>
            <th>' . _('Narrative') . '</th>
            </tr>';

    $BatchTotal = 0;  
  
  
   foreach ($_SESSION['ReceiptBatch']->Items as $ReceiptItem) {

        $SQL = "SELECT accountname FROM chartmaster WHERE accountcode='" . $ReceiptItem->GLCode . "'";
        $Result=DB_query($SQL,$db);
        $myrow=DB_fetch_array($Result);

        echo '<tr>
                <td align=center>' . number_format($ReceiptItem->Amount,2) . '</td>
                <td align=center>' . number_format($ReceiptItem->Discount,2) . '</td>
                <td>' . stripslashes($ReceiptItem->CustomerName) . '</td>
                <td>'.$ReceiptItem->GLCode.' - '.$myrow['accountname'].'</td>
                <td>'.$ReceiptItem->Narrative . '</td>
                <td><a href="' . $_SERVER['PHP_SELF'] . '?Delete=' . $ReceiptItem->ID . '&Type=' . $_GET['Type']. '">' . _('Delete') . '</a></td>
            </tr>';
        $BatchTotal= $BatchTotal + $ReceiptItem->Amount;
        
        
         
    }
  

echo '<tr><td class=number><b>' . number_format($BatchTotal,2) . '</b></td></tr></table>';
  
} 
 
 echo'</table><br>';
 
if (isset($_SESSION['ReceiptBatch']->Items) and count($_SESSION['ReceiptBatch']->Items) > 0){
    echo '<div class="centre"><br/><input tabindex="13" type="submit" name="CommitBatch" value="' . _('Accept and Process Batch') . '"></div>';
} 

?>

<script type="text/javascript">
function showBankDetails(str1){
    alert(str1);
    window.href"?bankacc="+str1;
    
}


</script>

