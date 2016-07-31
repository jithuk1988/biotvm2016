<?php
/* $Revision: 1.25 $ */

$PageSecurity = 11;

include('includes/session.inc');
$title = _('Issue Raw Materials against a Stores Request');
include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

echo '<a href="'. $rootpath . '/index.php?' . SID . '&Application='.'manuf'.'">' . _('Back to Manufacturing page'). '</a><br>';


echo '<form action="' . $_SERVER['PHP_SELF'] . '?' . SID . '" method=post>'; 

if(isset($_POST['issue']))      {
    
$count=$_POST['count'] ; 
//echo $count;
 

     $InputError = false; //ie assume no problems for a start - ever the optomist
    $ErrMsg = _('Could not retrieve the details of the selected work order item');
    $WOResult = DB_query("SELECT workorders.loccode,
                                 locations.locationname,
                                 workorders.closed,
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
                            AND woitems.wo=" . $_POST['WO'],
                            $db,
                            $ErrMsg);

    if (DB_num_rows($WOResult)==0){
        prnMsg(_('The selected work order item cannot be retrieved from the database'),'info');
        include('includes/footer.inc');
        exit;
    }
    $WORow = DB_fetch_array($WOResult);

    if ($WORow['closed']==1){
        prnMsg(_('The work order is closed - no more materials or components can be issued to it.'),'error');
        $InputError=true;
    }
    $QuantityIssued =0;      
 

    if (is_array($_POST['SerialNos'])){ //then we are issuing a serialised item
        $QuantityIssued = count($_POST['SerialNos']); // the total quantity issued as 1 per serial no
    } elseif ( isset($_POST['Qty'])){ //then its a plain non-controlled item
        $QuantityIssued = $_POST['Qty'];
    } else { //it must be a batch/lot controlled item
        for ($i=0;$i<15;$i++){
            if (strlen($_POST['Qty'.$i])>0){
                if (!is_numeric($_POST['Qty'.$i])){
                    $InputError=1;
                } else {
                    $QuantityIssued += $_POST['Qty'.$i];
                } //end if the qty field is numeric
            } // end if the qty field is entered
        }//end for the 15 fields available for batch/lot entry
    }//end batch/lot controlled item

 
 for ($i=0;$i<$count;$i++){ 
    
echo"<br>".$_POST['Qty'.$i];
echo"...............".$_POST['Item'.$i]; 
echo"...............".$_POST['Batch'.$i]; 


        $SQL1="SELECT qtyrequest,
                      qtyissued
           FROM womaterialrequestdetails
           WHERE reqno=".$_POST['requestdetails']." AND
                 stockid='".$_POST['Item'.$i]."'";
    $result1=DB_query($SQL1,$db);
    $myrow1=DB_fetch_array($result1);
//          echo $QuantityIssued.".......".$myrow1[0]."......".$myrow1[1].".......<br>"; 
          $EffQty=$myrow1[1] + $QuantityIssued;
//           echo $EffQty."=".$myrow1[1]."+".$QuantityIssued;
//           exit;
          if ($myrow1[0] < $QuantityIssued){
            $InputError = true;
            prnMsg(_('This issue cannot be processed because the issued quantity is greater than the request quantity'),'error');

        } else if ($myrow1[0] < $EffQty){
            $InputError = true;
            prnMsg(_('This issue cannot be processed because the sum of quantity entered and quanity already issued exeeds the request quantity'),'error');

        }
        
    
}   

}  

if (isset($_POST['Process'])){ //user hit the process the work order issues entered.    

    $InputError = false; //ie assume no problems for a start - ever the optomist
    $ErrMsg = _('Could not retrieve the details of the selected work order item');
    $WOResult = DB_query("SELECT workorders.loccode,
                                 locations.locationname,
                                 workorders.closed,
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
                            AND woitems.wo=" . $_POST['WO'],
                            $db,
                            $ErrMsg);

    if (DB_num_rows($WOResult)==0){
        prnMsg(_('The selected work order item cannot be retrieved from the database'),'info');
        include('includes/footer.inc');
        exit;
    }
    $WORow = DB_fetch_array($WOResult);

    if ($WORow['closed']==1){
        prnMsg(_('The work order is closed - no more materials or components can be issued to it.'),'error');
        $InputError=true;
    }
    $QuantityIssued =0;
    if (is_array($_POST['SerialNos'])){ //then we are issuing a serialised item
        $QuantityIssued = count($_POST['SerialNos']); // the total quantity issued as 1 per serial no
    } elseif ( isset($_POST['Qty'])){ //then its a plain non-controlled item
        $QuantityIssued = $_POST['Qty'];
    } else { //it must be a batch/lot controlled item
        for ($i=0;$i<15;$i++){
            if (strlen($_POST['Qty'.$i])>0){
                if (!is_numeric($_POST['Qty'.$i])){
                    $InputError=1;
                } else {
                    $QuantityIssued += $_POST['Qty'.$i];
                } //end if the qty field is numeric
            } // end if the qty field is entered
        }//end for the 15 fields available for batch/lot entry
    }//end batch/lot controlled item

    //Need to get the current standard cost for the item being issued
//        echo $_POST['reqno']."......".$_POST['IssueItem'].".......";
        $SQL1="SELECT qtyrequest,
                      qtyissued
           FROM womaterialrequestdetails
           WHERE reqno=".$_POST['reqno']." AND
                 stockid='".$_POST['IssueItem']."'";
    $result1=DB_query($SQL1,$db);
    $myrow1=DB_fetch_array($result1);
//          echo $QuantityIssued.".......".$myrow1[0]."......".$myrow1[1].".......<br>"; 
          $EffQty=$myrow1[1] + $QuantityIssued;
//           echo $EffQty."=".$myrow1[1]."+".$QuantityIssued;
//           exit;
          if ($myrow1[0] < $QuantityIssued){
            $InputError = true;
            prnMsg(_('This issue cannot be processed because the issued quantity is greater than the request quantity'),'error');

        } else if ($myrow1[0] < $EffQty){
            $InputError = true;
            prnMsg(_('This issue cannot be processed because the sum of quantity entered and quanity already issued exeeds the request quantity'),'error');

        }
        
        
    $SQL = "SELECT materialcost+labourcost+overheadcost AS cost,
            controlled,
            serialised,
            mbflag
        FROM stockmaster
        WHERE stockid='" .$_POST['IssueItem'] . "'";
    $Result = DB_query($SQL,$db);
    $IssueItemRow = DB_fetch_array($Result);

    if ($IssueItemRow['cost']==0){
        prnMsg(_('The item being issued has a zero cost. Zero cost items cannot be issued to work orders'),'error');
        $InputError=1;
    }

    if ($_SESSION['ProhibitNegativeStock']==1
            AND ($IssueItemRow['mbflag']=='M' OR $IssueItemRow['mbflag']=='B')){
                                            //don't need to check labour or dummy items
        $SQL = "SELECT quantity FROM locstock
                WHERE stockid ='" . $_POST['IssueItem'] . "'
                AND loccode ='" . $_POST['FromLocation'] . "'";
        $CheckNegResult = DB_query($SQL,$db);
        $CheckNegRow = DB_fetch_row($CheckNegResult);
        if ($CheckNegRow[0]<$QuantityIssued){
            $InputError = true;
            prnMsg(_('This issue cannot be processed because the system parameter is set to prohibit negative stock and this issue would result in stock going into negative. Please correct the stock first before attempting another issue'),'error');
        }

    }
    

    if ($InputError==false){


/************************ BEGIN SQL TRANSACTIONS ************************/

        $Result = DB_Txn_Begin($db);
        /*Now Get the next WO Issue transaction type 28 - function in SQL_CommonFunctions*/
        $WOIssueNo = GetNextTransNo(28, $db);

        $PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']), $db);
        $SQLIssuedDate = FormatDateForSQL($_POST['IssuedDate']);
        $StockGLCode = GetStockGLCode($_POST['IssueItem'],$db);


        if ($IssueItemRow['mbflag']=='M' OR $IssueItemRow['mbflag']=='B'){
            /* Need to get the current location quantity will need it later for the stock movement */
            $SQL="SELECT locstock.quantity
                FROM locstock
                WHERE locstock.stockid='" . $_POST['IssueItem'] . "'
                AND loccode= '" . $_POST['FromLocation'] . "'";

            $Result = DB_query($SQL, $db);
            if (DB_num_rows($Result)==1){
                $LocQtyRow = DB_fetch_row($Result);
                $NewQtyOnHand = ($LocQtyRow[0] - $QuantityIssued);
            } else {
            /*There must actually be some error this should never happen */
                $NewQtyOnHand = 0;
            }

            $SQL = "UPDATE locstock
                SET quantity = locstock.quantity - " . $QuantityIssued . "
                WHERE locstock.stockid = '" . $_POST['IssueItem'] . "'
                AND loccode = '" . $_POST['FromLocation'] . "'";

            $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
            $DbgMsg =  _('The following SQL to update the location stock record was used');
            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
        } else {
            $NewQtyOnHand =0; //since we can't have stock of labour type items!!
        }
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
                    VALUES ('" . $_POST['IssueItem'] . "',
                            28,
                            " . $WOIssueNo . ",
                            '" . $_POST['FromLocation'] . "',
                            '" . Date('Y-m-d') . "',
                            " . $IssueItemRow['cost'] . ",
                            " . $PeriodNo . ",
                            '" . $_POST['WO'] . "',
                            " . -$QuantityIssued . ",
                            " . $IssueItemRow['cost'] . ",
                            " . $NewQtyOnHand . ")";

        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted when processing the work order issue because');
        $DbgMsg =  _('The following SQL to insert the stock movement records was used');
        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

        /*Get the ID of the StockMove... */
        $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');
        
       
        
        /* Do the Controlled Item INSERTS HERE */

        if ($IssueItemRow['controlled'] ==1){
            //the form is different for serialised items and just batch/lot controlled items
            if ($IssueItemRow['serialised']==1){
                //serialised items form has multi select box of serial numbers that contains all the available serial numbers at the location selected
                foreach ($_POST['SerialNos'] as $SerialNo){
                /*  We need to add the StockSerialItem record and
                    The StockSerialMoves as well */
                //need to test if the serialised item exists first already
                    if (trim($SerialNo) != ""){

                        $SQL = "UPDATE stockserialitems set quantity=0
                                        WHERE (stockid= '" . $_POST['IssueItem'] . "')
                                        AND (loccode = '" . $_POST['FromLocation'] . "')
                                        AND (serialno = '" . $SerialNo . "')";
                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be inserted because');
                        $DbgMsg =  _('The following SQL to insert the serial stock item records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

                        /** end of handle stockserialitems records */

                        /* now insert the serial stock movement */
                        $SQL = "INSERT INTO stockserialmoves (stockmoveno,
                                            stockid,
                                            serialno,
                                            moveqty)
                                    VALUES (" . $StkMoveNo . ",
                                            '" . $_POST['IssueItem'] . "',
                                            '" . $SerialNo . "',
                                            -1)";
                        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                        $DbgMsg = _('The following SQL to insert the serial stock movement records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                    }//non blank SerialNo
                } //end for all of the potential serialised entries in the multi select box
            } else { //the item is just batch/lot controlled not serialised
            /*the form for entry of batch controlled items is only 15 possible fields */
                for($i=0;$i<15;$i++){
                /*  We need to add the StockSerialItem record and
                    The StockSerialMoves as well */
                    //need to test if the batch/lot exists first already
                    if (trim($_POST['BatchRef' .$i]) != ""){

                        $SQL = "SELECT COUNT(*) FROM stockserialitems
                                WHERE stockid='" .$_POST['IssueItem'] . "'
                                AND loccode = '" . $_POST['FromLocation'] . "'
                                AND serialno = '" . $_POST['BatchRef' .$i] . "'";
                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not check if a batch/lot reference for the item already exists because');
                        $DbgMsg =  _('The following SQL to test for an already existing controlled item was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        $AlreadyExistsRow = DB_fetch_row($Result);

                        if ($AlreadyExistsRow[0]>0){
                            $SQL = 'UPDATE stockserialitems SET quantity = quantity - ' . $_POST['Qty' . $i] . "
                                        WHERE stockid='" . $_POST['IssueItem'] . "'
                                        AND loccode = '" . $_POST['FromLocation'] . "'
                                        AND serialno = '" . $_POST['BatchRef' .$i] . "'";
                        } else {
                            $SQL = "INSERT INTO stockserialitems (stockid,
                                                loccode,
                                                serialno,
                                                qualitytext,
                                                quantity)
                                                VALUES ('" . $_POST['IssueItem'] . "',
                                                '" . $_POST['FromLocation'] . "',
                                                '" . $_POST['BatchRef' . $i] . "',
                                                '',
                                                " . -($_POST['Qty'.$i]) . ")";
                        }

                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The batch/lot item record could not be inserted because');
                        $DbgMsg =  _('The following SQL to insert the batch/lot item records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        
//        $sql6="SELECT qtyissued 
//               FROM womaterialrequestdetails
//               WHERE reqno=".$_POST['reqno']." AND
//                     stockid='".$_POST['IssueItem']."'";                
//        $result6=DB_query($sql6,$db);
//        $myrow6=DB_fetch_array($result6);
        

        $sql7="UPDATE womaterialrequestdetails SET
               qtyissued=qtyissued + ". $_POST['Qty' . $i]." 
               WHERE reqno=".$_POST['reqno']." AND
                     stockid='".$_POST['IssueItem']."'";
        $result7=DB_query($sql7,$db); 
        
                        /** end of handle stockserialitems records */

                        /** now insert the serial stock movement **/
                        $SQL = "INSERT INTO stockserialmoves (stockmoveno,
                                            stockid,
                                            serialno,
                                            moveqty)
                                    VALUES (" . $StkMoveNo . ",
                                            '" . $_POST['IssueItem'] . "',
                                            '" . $_POST['BatchRef'.$i]  . "',
                                            " . $_POST['Qty'.$i]  . ")";
                        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                        $DbgMsg = _('The following SQL to insert the serial stock movement records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                    }//non blank BundleRef
                } //end for all 15 of the potential batch/lot fields received
            } //end of the batch controlled stuff
        } //end if the woitem received here is a controlled item


        if ($_SESSION['CompanyRecord']['gllink_stock']==1){
        /*GL integration with stock is activated so need the GL journals to make it so */

        /*first the debit the WIP of the item being manufactured from the WO
          the appropriate account was already retrieved into the $StockGLCode variable as the Processing code is kicked off
          it is retrieved from the stock category record of the item by a function in SQL_CommonFunctions.inc*/

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
                        " . $WORow['wipact'] . ",
                        '" . $_POST['WO'] . " " . $_POST['IssueItem'] . ' x ' . $QuantityIssued . " @ " . number_format($IssueItemRow['cost'],2) . "',
                        " . ($IssueItemRow['cost'] * $QuantityIssued) . ")";

            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The issue of the item to the work order GL posting could not be inserted because');
            $DbgMsg = _('The following SQL to insert the work order issue GLTrans record was used');
            $Result = DB_query($SQL,$db,$ErrMsg, $DbgMsg, true);

        /*now the credit Stock entry*/
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
                        " . $StockGLCode['stockact'] . ",
                        '" . $_POST['WO'] . " " . $_POST['IssueItem'] . ' x ' . $QuantityIssued . " @ " . number_format($IssueItemRow['cost'],2) . "',
                        " . -($IssueItemRow['cost'] * $QuantityIssued) . ")";

            $ErrMsg =   _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock account credit on the issue of items to a work order GL posting could not be inserted because');
            $DbgMsg =  _('The following SQL to insert the stock GLTrans record was used');
            $Result = DB_query($SQL,$db, $ErrMsg, $DbgMsg,true);

        } /* end of if GL and stock integrated and standard cost !=0 */


        //update the wo with the new qtyrecd
        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('Could not update the work order cost issued to the work order because');
        $DbgMsg = _('The following SQL was used to update the work order');
        $UpdateWOResult =DB_query("UPDATE workorders
                        SET costissued=costissued+" . ($QuantityIssued*$IssueItemRow['cost']) . "
                        WHERE wo=" . $_POST['WO'],
                    $db,$ErrMsg,$DbgMsg,true);


        $Result = DB_Txn_Commit($db);
            
               
        prnMsg(_('The issue of quantity:') . ' ' . $QuantityIssued . ' ' . _('of item:')  . ' ' . $_POST['IssueItem'] . ' ' . _('against work order:') . ' '. $_POST['WO'] . ' ' . _('has been processed'),'info');
        unset($_POST['WO']);
        unset($_POST['StockID']);
        unset($_POST['IssueItem']);
        unset($_POST['FromLocation']);
        unset($_POST['Process']);
        unset($_POST['SerialNos']);
        for ($i=0;$i<15;$i++){
            unset($_POST['BatchRef'.$i]);
            unset($_POST['Qty'.$i]);
        }
        unset($_POST['Qty']);
        /*end of process work order issues entry */
//        include('includes/footer.inc');
//        exit;
    } //end if there were not input errors reported - so the processing was allowed to continue  
} //end of if the user hit the process button  
if (!isset($_POST['IssueItem'])) {  
    /* This page can only be called with a purchase order number for invoicing*/
    echo '<div class="left"><strong>'.
        _('Select an SR to view its details').'</strong></div>'; 

if(isset($_GET['requestdetails'])) {
    
    $_POST['requestdetails'] = $_GET['requestdetails'];
}        
display($db,$Wono); 
    include ('includes/footer.inc');   
    exit;
} else { 
$reqno= $_POST['requestdetails'];
echo '<br><a href="'. $rootpath . '/WOMaterialIssue.php?' . SID . '&requestdetails='.$reqno.'">' . _('Back to previous page'). '</a><br>';
echo "<br>".$_POST['FromLocation1'].".........".$_POST['StockID']; 
     WOCheck($db,$Wono) ;
    echo '<hr>';
    //need to get some details about the item to issue
    $sql = "SELECT description,
            decimalplaces,
            units,
            controlled,
            serialised
        FROM stockmaster
        WHERE stockid='" . $_POST['IssueItem'] . "'";
    $ErrMsg = _('Could not get the detail of the item being issued because');
    $IssueItemResult = DB_query($sql,$db,$ErrMsg);
    $IssueItemRow = DB_fetch_array($IssueItemResult);

    echo '<table>
        <tr><td class="label">' . _('Issuing') . ':</td>
            <td>' . $_POST['IssueItem'] . ' - ' . $IssueItemRow['description'] .'</td>
            <td class="label">' . _('Units') . ':</td><td>' . $IssueItemRow['units'] .'</td></tr>
        </table>';

    echo '<table>';

    //Now Setup the form for entering quantities of the item to be issued to the WO
    if ($IssueItemRow['controlled']==1){ //controlled

        if ($IssueItemRow['serialised']==1){ //serialised
            echo '<tr><th>' . _('Serial Numbers Issued') . '</th></tr>';

            $SerialNoResult = DB_query("SELECT serialno
                            FROM stockserialitems
                            WHERE stockid='" . $_POST['IssueItem'] . "'
                            AND loccode='" . $_POST['FromLocation'] . "'",
                        $db,_('Could not retrieve the serial numbers available at the location specified because'));
            if (DB_num_rows($SerialNoResult)==0){
                echo '<tr><td>' . _('There are no serial numbers at this location to issue') . '</td></tr>';
                echo '<tr><td align="center"><input type=submit name="Retry" value="' . _('Reselect Location or Issued Item') . '"></td></tr>';
            } else {
                echo '<tr><td><select name="SerialNos[]" multiple>';
                while ($SerialNoRow = DB_fetch_array($SerialNoResult)){
                    if (in_array($SerialNoRow['serialno'],$_POST['SerialNos'])){
                        echo '<option selected value="' . $SerialNoRow['serialno'] . '">' . $SerialNoRow['serialno'] . '</option>';
                    } else {
                        echo '<option value="' . $SerialNoRow['serialno'] . '">' . $SerialNoRow['serialno'] . '</option>';
                    }
                }
                echo '</select></td></tr>';
                echo '<input type="hidden" name="IssueItem" value="' . $_POST['IssueItem'] . '">';
              
              echo "<input type=hidden name=reqno value=".$reqno.">";  
              echo "<input type=hidden name=FromLocation value='".$_POST['FromLocation1']."'>";   
              echo "<input type=hidden name=StockID value='".$_POST['StockID']."'>";   
              echo "<input type=hidden name=WO value='".$_POST['WO']."'>";                
                echo '<tr><td align="center"><input type=submit name="Process" value="' . _('Process Items Issued') . '"></td></tr>';
            }
        } else { //controlled but not serialised - just lot/batch control
            echo '<tr><th colspan="2">' . _('Batch/Lots Issued') . '</th></tr>';
            for ($i=0;$i<6;$i++){
                echo '<tr><td><input type="textbox" name="BatchRef' . $i .'" ';
                echo '></td>
                      <td><input type="textbox" name="Qty' . $i .'"></td></tr>';
            }
            echo '<input type="hidden" name="IssueItem" value="' . $_POST['IssueItem'] . '">';
            
              echo "<input type=hidden name=reqno value=".$reqno.">";
              echo "<input type=hidden name=FromLocation value='".$_POST['FromLocation1']."'>"; 
              echo "<input type=hidden name=StockID value='".$_POST['StockID']."'>";   
              echo "<input type=hidden name=WO value='".$_POST['WO']."'>";            
            echo '<tr><td align="center" colspan=2><input type=submit name="Process" value="' . _('Process Items Issued') . '"></td></tr>';
        } //end of lot/batch control
    } else { //not controlled - an easy one!
        echo '<input type="hidden" name="IssueItem" value="' . $_POST['IssueItem'] . '">';
        echo '<tr><td>' . _('Quantity Issued') . ':</td>
              <td><input type="textbox" name="Qty"></tr>';
              
              echo "<input type=hidden name=requestdetails value=".$reqno.">";
              echo "<input type=hidden name=FromLocation value='".$_POST['FromLocation1']."'>"; 
              echo "<input type=hidden name=StockID value='".$_POST['StockID']."'>";   
              echo "<input type=hidden name=WO value='".$_POST['WO']."'>";      
        echo '<tr><td align="center"><input type=submit name="Process" value="' . _('Process Items Issued') . '"></td></tr>';
    }   
} 
if (isset($_GET['IssueItem'])){
    $_POST['IssueItem']=$_GET['IssueItem'];
}
if (isset($_GET['FromLocation'])){
    $_POST['FromLocation'] =$_GET['FromLocation'];
}

    
if(isset($_POST['status']))     {
$count=$_POST['count'] ; 
for ($i=0;$i<$count;$i++){ 
    
//echo"<br>".$_POST['StatusID'.$i];

//echo"...............".$_POST['reqno'.$i];     
 $sql4="SELECT statusid
        FROM womaterialrequest
        WHERE reqno='".$_POST['reqno'.$i]."'"; 
        
 $result4=DB_query($sql4,$db); 
 $myrow4=DB_fetch_array($result4);  

 if($myrow4[0]!=$_POST['StatusID'.$i])   {
//     echo "<br>".$_POST['reqno'.$i];
//     echo "<br>".$myrow4[0];
//     echo"<br>".$_POST['StatusID'.$i];

    $sql5="UPDATE womaterialrequest
           SET statusid='".$_POST['StatusID'.$i]."'
           WHERE reqno='".$_POST['reqno'.$i]."'";
    $result5=DB_query($sql5,$db);       
 }    
}
    
}
if (isset($_POST['Request'])){

//echo"<br>".$_POST['WO'];
//echo"<br>".$_POST['StockID'];
$count=$_POST['count'] ; 
             $ReqID=0;
            $sql1="SELECT reqno
                   FROM womaterialrequestdetails";
            $result1 = DB_query($sql1, $db); 
            while($myrow1= DB_fetch_array($result1))    {
                
                $ReqID=$myrow1[0];
            }      
               $ReqID++;
               
                  $sql2="INSERT INTO womaterialrequest (reqno,
                  wono) VALUES ( ".$ReqID.",
                  ".$_POST['WO']."
                  )"; 
                  
                  $result2 = DB_query($sql2, $db);  
                 
for ($i=0;$i<$count;$i++){

//                  echo"<br>".$_POST['IssueItem'.$i];

//echo"...............".$_POST['StockID'.$i]; 
//break;
   
                  $sql3="INSERT INTO womaterialrequestdetails (reqno,
                  stockid,
                  qtyrequest) VALUES ( ".$ReqID.",
                  '".$_POST['StockID'.$i]."',
                  ".$_POST['IssueItem'.$i]."       
                  
                  )";
                  
              $result3 = DB_query($sql3, $db);    
            }
//    exit;
}



 
 
/*User hit the search button looking for an item to issue to the WO */

 
function WOCheck($db,$Wono)  {  
/* Always display quantities received and recalc balance for all items on the order */
//echo "<br>".$_POST['WO']."....".$_POST['StockID'];    
$ErrMsg = _('Could not retrieve the details of the selected work order item');
$WOResult = DB_query("SELECT workorders.loccode,
             locations.locationname,
             workorders.requiredby,
             workorders.startdate,
             workorders.closed,
             stockmaster.description,
              stockmaster.decimalplaces,
             stockmaster.units,
             woitems.qtyreqd,
             woitems.qtyrecd
            FROM workorders INNER JOIN locations
            ON workorders.loccode=locations.loccode
            INNER JOIN woitems
            ON workorders.wo=woitems.wo
            INNER JOIN stockmaster
            ON woitems.stockid=stockmaster.stockid
            WHERE woitems.stockid='" . $_POST['StockID'] . "'
            AND woitems.wo =" . $_POST['WO'],
            $db,
            $ErrMsg);

if (DB_num_rows($WOResult)==0){
    prnMsg(_('The selected work order item cannot be retrieved from the database'),'info');
    include('includes/footer.inc');
    exit;
}
$WORow = DB_fetch_array($WOResult);  

if ($WORow['closed']==1){
    prnMsg(_('The selected work order has been closed and variances calculated and posted. No more issues of materials and components can be made against this work order.'),'info');
    include('includes/footer.inc');
    exit;
}


echo '<table cellpadding=2 border=0>
    <tr><td class="label">' . _('Issue to work order') . ':</td><td>' . $_POST['WO'] .'</td><td class="label">' . _('Item') . ':</td><td>' . $_POST['StockID'] . ' - ' . $WORow['description'] . '</td></tr>
     <tr><td class="label">' . _('Manufactured at') . ':</td><td>' . $WORow['locationname'] . '</td><td class="label">' . _('Required By') . ':</td><td>' . ConvertSQLDate($WORow['requiredby']) . '</td></tr>
     <tr><td class="label">' . _('Quantity Ordered') . ':</td><td align=right>' . number_format($WORow['qtyreqd'],$WORow['decimalplaces']) . '</td><td colspan=2>' . $WORow['units'] . '</td></tr>
     <tr><td class="label">' . _('Already Received') . ':</td><td align=right>' . number_format($WORow['qtyrecd'],$WORow['decimalplaces']) . '</td><td colspan=2>' . $WORow['units'] . '</td></tr>
    <tr><td colspan=4><hr></td></tr>
     <tr><td class="label">' . _('Date Material Issued') . ':</td><td>' . Date($_SESSION['DefaultDateFormat']) . '</td>
    <td class="label">' . _('Issued From') . ':</td><td>';


echo '</td></tr>
    </table>
    <table>'; 


 }
echo '</table>'; 

function display($db,$Wono)  {
    

  
      $where = "WHERE statusid=2";
    if ($Wono) {
        $where = ' WHERE wono = $Wono AND 
                         statusid=2';
    }
    
    
  echo'<table width=100%><tr><td valign=top>';  
  
  echo'<table width=40% >';     
  
    echo '<tr><th colspan=7>' . _('Pending SRs For this Work Order') . '</th></tr>';
    echo '<tr><th colspan=2>' . _('SR No:') . '</th>
        <th>' . _('WOno') . '</th>
        <th>' . _('StockID') . '</th>
        <th>' . _('WOno') . '</th>
        <th>' . _('Location') . '</th>
        <th>' . _('Status') . '</th></tr>';
        
                  
        $sql4="SELECT * 
               FROM womaterialrequest
               $where";
        $result4 = DB_query($sql4, $db);
         $i=0;
        while ($myrow4 = DB_fetch_array($result4)){ 
                 
                                if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                echo '<tr class="OddTableRows">';
                $k++;
            }
          $where1 = "WHERE wo=$myrow4[1]";
    if ($Wono) {
        $where1 = ' WHERE wo = $Wono ';
    }
    
    $sql7="SELECT stockid
           FROM woitems
           $where1"; 
    $result7 = DB_query($sql7, $db);    
    $myrow7 = DB_fetch_array($result7) ;           
            
    $sql5="SELECT status
           FROM status
           WHERE statusid=$myrow4[2]";
    $result5 = DB_query($sql5, $db);    
    $myrow5 = DB_fetch_array($result5) ;
    
    $sql6="SELECT locationname 
           FROM locations
           WHERE loccode ='".$myrow4[3]."'";
    $result6 = DB_query($sql6, $db);
    $myrow6 = DB_fetch_array($result6) ;       
    
         echo'<input type=hidden name="reqno' . $i .'" value='.$myrow4[0].'></td>';   
         echo'<input type=hidden name="FromLocation' . $i .'" value='.$myrow6[0].'></td>';  
        echo'<td colspan=2> <input type=submit name=requestdetails value='.$myrow4[0].'></td>';
        echo'<td>'.$myrow4[1].'</td>';
        echo'<td>'.$myrow7[0].'</td>';
        echo'<td>'.$myrow4[1].'</td>';
        echo"<td>".$myrow6[0]."</td>"; 
        echo'<td align=right>';

        echo $myrow5[0];


    
        
        echo'</td></tr>'; 
         $i++;    
           
        } 
           echo'<input type=hidden name="count" value='.$i.'></td>';
           echo'</table>';  
           
 // end of table showing SRs for the selected WO         
    
echo '</form>';     
      echo' </td><td valign= top>';
      
        if(isset($_POST['requestdetails'])) {  
        $reqno=$_POST['requestdetails'];
        displaydetails($db,$reqno);
        
        }
 
      
       
      echo '</td></tr></table>';          
}

function displaydetails($db,$reqno)  {  

 echo '<form action="' . $_SERVER['PHP_SELF'] . '?' . SID . '" method=post>';     
        $sql3="SELECT stockid,
                  qtyrequest
           FROM womaterialrequestdetails
           WHERE reqno=$reqno";
    $result3 = DB_query($sql3, $db);  
    $i=0;
    
        $sql4="SELECT wono,
                      loccode
           FROM womaterialrequest
           WHERE reqno=$reqno";
    $result4 = DB_query($sql4, $db); 
    $myrow4 = DB_fetch_array($result4) ;
    
        $sql6="SELECT locationname 
           FROM locations
           WHERE loccode ='".$myrow4[1]."'";
    $result6 = DB_query($sql6, $db);
    $myrow6 = DB_fetch_array($result6); 
    
    
            $sql5="SELECT stockid,
                          qtyreqd
           FROM woitems
           WHERE wo='".$myrow4[0]."'";
    $result5 = DB_query($sql5, $db); 
    $myrow5 = DB_fetch_array($result5) ;
    
    
       echo'<table width=80% align=left>';
             echo '<tr><th colspan=8>' . _('SRs Details for SR No: ') .$reqno.'</th></tr>';
             echo '<tr><th colspan=8>' . _('Issued From: ') .$myrow6[0].'</th></tr>'; 
    echo '<tr><th>' . _('Item') . '</th>
        <th>' . _('WO Qty') . '</th>
        <th>' . _('Qty Already Issued against this WO') . '</th> 
        <th>' . _('Request Qty') . '</th>
        <th>' . _('Qty Already Issued against this SR') . '</th>
        <th>' . _('Qty On Hand') . '</th>
        <th>' . _('Qty to be issued now') . '</th>
        <th>' . _('Batch no:') . '</th></tr>';
        

 
            

  echo "<input type=hidden name=FromLocation1 value=".$myrow4[1].">";  

    while ($myrow3 = DB_fetch_array($result3)){
        
        
        
        $sql6="SELECT qtypu
               FROM worequirements
               WHERE wo=".$myrow4[0]." AND 
                     parentstockid= '".$myrow5[0]."' AND
                     stockid= '".$myrow3[0]."'";
        $result6=DB_query($sql6,$db);
        $myrow6=DB_fetch_array($result6);
        
        $WORequirement=$myrow6[0]*$myrow5[1] ;
    
      
              $IssuedAlreadyResult = DB_query("SELECT SUM(-qty) FROM stockmoves
                            WHERE stockmoves.type=28
                            AND stockid='" . $myrow3['stockid'] . "'
                            AND reference='" . $myrow4[0] . "'",
                        $db);
        $IssuedAlreadyRow = DB_fetch_row($IssuedAlreadyResult);
        
                    $QOHResult = DB_query("SELECT sum(quantity)
                        FROM locstock
                        WHERE stockid = '" . $myrow3['stockid'] . "'",
                                        $db);
            $QOHRow = DB_fetch_row($QOHResult);
            $QOH = $QOHRow[0];
            
            $sql7="SELECT  qtyissued
                   FROM womaterialrequestdetails
                   WHERE reqno= $reqno AND
                         stockid='" . $myrow3['stockid'] . "'";
            $result7=DB_query($sql7,$db);
            $myrow7=DB_fetch_array($result7); 
            
     $sql8="SELECT serialno
            FROM stockserialitems
            WHERE stockid='" . $myrow3['stockid'] . "'
            ORDER BY serialno";       
     $result8=DB_query($sql8,$db);
     $myrow8=DB_fetch_array($result8);
     $count8=DB_num_rows($result8); 
     
         $sql9 = "SELECT materialcost+labourcost+overheadcost AS cost,
            controlled,
            serialised,
            mbflag
        FROM stockmaster
        WHERE stockid='" .$myrow3['stockid'] . "'";
    $result9 = DB_query($sql9,$db);
    $myrow9 = DB_fetch_array($result9);
        
                    if ($k==1){
                $tr= '<tr class="EvenTableRows">';
                $k=0;
            } else {
                $tr= '<tr class="OddTableRows">';
                $k++;
            } 
            
        $BalanceQTY =$myrow3[1] - $myrow7[0];
        
        if($BalanceQTY <=0)  {
            
        $tr= '<tr bgcolor=#99FF66>';    
        }
        echo $tr;   
        if($BalanceQTY >0)   {  
        echo'<td><input type="submit" name="IssueItem" value="' .$myrow3[0] . '"></td>';
        }else {
            
            echo'<td align=center>' .$myrow3[0] . '</td>';
        }
        echo' <td>'.$WORequirement.'</td>
        <td>'.$IssuedAlreadyRow[0].'</td> 
              <td>'.$myrow3[1].'</td>
              <td>'.$myrow7[0].'</td>';
               if($QOH <$myrow3[1])  {
            
         echo'<td bgcolor=#FF6633>'.$QOH.'</td>'; 
           
        } else {
              echo'<td>'.$QOH.'</td>'; 
        
        }
       if($BalanceQTY >0)   {                  
        echo'<td><input type="text" name= "Qty' . $i .'" value="' .$BalanceQTY. '"></td> ';
        
          if($myrow9['controlled']==1)      {
              if ($count8>1) {
               echo'<td>Quantity has to be obtained from different batches. Click on the item code button to enter batches</td></tr>';   
                  
              }else {
                  
                  echo '<td><input type="text" name="Batch'.$i.'" value='.$myrow8[0].'></td></tr>';
              } 
          }else {
              
             echo '<td>non-controlled</td></tr>'; 
          }  
       }else {
        echo'<td colspan=2  align=center>Fully issued</td></tr>'; 
        echo'<input type="hidden" name="Qty'.$i.'" value=0></tr>';          
           
       }       
                                                                                              
 
              $i++; 
    }
    
              echo ' <input type=hidden name=StockID value='.$myrow5[0].'>';   
              echo ' <input type=hidden name=WO value='.$myrow4[0].'>';
              echo ' <input type=hidden name=requestdetails value='.$reqno.'>';               
               
              echo '<input type=hidden name=count value='.$i.'>';   
      echo '<tr><th align =right colspan=8><input type=submit name=issue value=Issue></th></tr>';
      echo '</table>';    
 
 echo'</form>';           
}
  


include('includes/footer.inc');
?>