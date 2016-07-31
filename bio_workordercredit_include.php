<?php
function recieve_sub($db,$substock,$wono,$subqty,$locn,$type)
  { 
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
                            WHERE woitems.stockid='" . $substock . "'
                            AND workorders.wo='".$wono . "'",
                            $db,
                            $ErrMsg);

    if (DB_num_rows($WOResult)==0){
        prnMsg(_('The selected work order item cannot be retrieved from the database'),'info');
        include('includes/footer.inc');
        exit;
    }
    $WORow = DB_fetch_array($WOResult);

    $QuantityReceived = 0;

        if (!is_numeric($subqty)){
            $InputError=true;
            prnMsg(_('The quantity entered is not numeric - a number is expected'),'error');
        } else {
            $QuantityReceived = $subqty;
        }
  

    if ($QuantityReceived + $WORow['qtyrecd'] > $WORow['qtyreqd'] *(1+$_SESSION['OverReceiveProportion'])){
        prnMsg(_('The quantity received is greater than the quantity required even after allowing for the configured allowable over-receive proportion. If this is correct then the work order must be modified first.'),'error');
        $InputError=true;
    }



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
                          WHERE worequirements.wo='" . $wono . "'
                          AND worequirements.parentstockid='" .$substock . "'
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

        if (!isset($_POST['ReceivedDate'])){
            $_POST['ReceivedDate'] = Date($_SESSION['DefaultDateFormat']);
        }

        $SQLReceivedDate = FormatDateForSQL($_POST['ReceivedDate']);
        $StockGLCode = GetStockGLCode($substock,$db);

    //Recalculate the standard for the item if there were no items previously received against the work order
        if ($WORow['qtyrecd']==0){
            $CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost
                                    FROM stockmaster INNER JOIN bom
                                    ON stockmaster.stockid=bom.component
                                    WHERE bom.parent='" . $substock . "'
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
                                            WHERE wo='" . $wono . "'
                                            AND parentstockid='" . $substock . "'",
                                            $db);

            //Recursively insert real component requirements
            WoRealRequirements($db, $wono, $WORow['loccode'], $substock);

            //Need to check this against the current standard cost and do a cost update if necessary
            $sql = "SELECT materialcost+labourcost+overheadcost AS cost,
                          sum(quantity) AS totalqoh,
                          labourcost,
                          overheadcost
                    FROM stockmaster INNER JOIN locstock
                        ON stockmaster.stockid=locstock.stockid
                    WHERE stockmaster.stockid='" . $substock . "'
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
                                '" . $CostUpdateNo . "',
                                '" . Date('Y-m-d') . "',
                                '" . $PeriodNo . "',
                                '" . $StockGLCode['adjglact'] . "',
                                '" . _('Cost roll on release of WO') . ': ' . $wono . ' - ' . $substock . ' ' . _('cost was') . ' ' . $ItemCostRow['cost'] . ' ' . _('changed to') . ' ' . $Cost . ' x ' . _('Quantity on hand of') . ' ' . $ItemCostRow['totalqoh'] . "',
                                '" . (-$ValueOfChange) . "')";

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
                                '" . $CostUpdateNo . "',
                                '" . Date('Y-m-d') . "',
                                '" . $PeriodNo . "',
                                '" . $StockGLCode['stockact'] . "',
                                '" . _('Cost roll on release of WO') . ': ' . $wono . ' - ' . $substock . ' ' . _('cost was') . ' ' . $ItemCostRow['cost'] . ' ' . _('changed to') . ' ' . $Cost . ' x ' . _('Quantity on hand of') . ' ' . $ItemCostRow['totalqoh'] . "',
                                '" . $ValueOfChange . "')";

                    $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL debit for stock cost adjustment posting could not be inserted because');
                    $DbgMsg = _('The following SQL to insert the GLTrans record was used');
                    $Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
                }

                $SQL = "UPDATE stockmaster SET
                            materialcost='" . $Cost . "',
                            labourcost='" . $ItemCostRow['labourcost'] . "',
                            overheadcost='" . $ItemCostRow['overheadcost'] . "',
                            lastcost='" . $ItemCostRow['cost'] . "'
                        WHERE stockid='" . $substock . "'";

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
                                          WHERE wo='" . $wono . "'
                                          AND parentstockid='" .$substock . "'
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
                            '" . $WOIssueNo . "',
                            '" . $WORow['loccode'] . "',
                            '" . Date('Y-m-d') . "',
                            '" . $PeriodNo . "',
                            '" . $wono . "',
                            '" . $AutoIssueCompRow['cost'] . "',
                            '" . -($AutoIssueCompRow['qtypu'] * $QuantityReceived) . "',
                            '" . $AutoIssueCompRow['cost'] . "',
                            '" . $NewQtyOnHand . "')";

            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement record could not be inserted for an auto-issue component because');
            $DbgMsg =  _('The following SQL to insert the stock movement records was used');
            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

            //Update the workorder record with the cost issued to the work order
            $SQL = "UPDATE workorders SET
                        costissued = costissued+" . ($AutoIssueCompRow['qtypu'] * $QuantityReceived * $AutoIssueCompRow['cost']) ."
                    WHERE wo='" . $wono . "'";
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
                                '" . $WOIssueNo . "',
                                '" . Date('Y-m-d') . "',
                                '" . $PeriodNo . "',
                                '" . $StockGLCode['wipact'] . "',
                                '" . $wono . ' - ' . $substock . ' ' . _('Component') . ': ' . $AutoIssueCompRow['stockid'] . ' - ' . $QuantityReceived . ' x ' . $AutoIssueCompRow['qtypu'] . ' @ ' . number_format($AutoIssueCompRow['cost'],2) . "',
                                '" . ($AutoIssueCompRow['qtypu'] * $QuantityReceived * $AutoIssueCompRow['cost']) . "')";

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
                                '" . $WOIssueNo . "',
                                '" . Date('Y-m-d') . "',
                                '" . $PeriodNo . "',
                                '" . $AutoIssueCompRow['stockact'] . "',
                                '" . $wono . ' - ' . $substock . ' -> ' . $AutoIssueCompRow['stockid'] . ' - ' . $QuantityReceived . ' x ' . $AutoIssueCompRow['qtypu'] . ' @ ' . number_format($AutoIssueCompRow['cost'],2) . "',
                                '" . -($AutoIssueCompRow['qtypu'] * $QuantityReceived * $AutoIssueCompRow['cost']) . "')";

                    $ErrMsg =   _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock side of the work order issue GL posting could not be inserted because');
                    $DbgMsg =  _('The following SQL to insert the WO issue GLTrans record was used');
                    $Result = DB_query($SQL,$db, $ErrMsg, $DbgMsg, true);
            }//end GL-stock linked

        } //end of auto-issue loop for all components set to auto-issue


        /* Need to get the current location quantity will need it later for the stock movement */
        $SQL = "SELECT locstock.quantity
                FROM locstock
                WHERE locstock.stockid='" . $substock . "'
                AND loccode= '" . $locn . "'";

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
                WHERE locstock.stockid = '" . $substock . "'
                AND loccode = '" . $locn . "'";

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
                    VALUES ('" . $substock . "',
                            26,
                            '" . $WOReceiptNo . "',
                            '" . $locn . "',
                            '" . Date('Y-m-d') . "',
                            '" . $WORow['stdcost'] . "',
                            '" . $PeriodNo . "',
                            '" . $wono . "',
                            '" . $QuantityReceived . "',
                            '" . $WORow['stdcost'] . "',
                            '" . ($QtyOnHandPrior + $QuantityReceived) . "')";

        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted when processing the work order receipt because');
        $DbgMsg =  _('The following SQL to insert the stock movement records was used');
        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

        /*Get the ID of the StockMove... */
        $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');


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
                                '" . $WOReceiptNo . "',
                                '" . Date('Y-m-d') . "',
                                '" . $PeriodNo . "',
                                '" . $StockGLCode['stockact'] . "',
                                '" . $wono . " " . $substock . " - " . $WORow['description'] . ' x ' . $QuantityReceived . " @ " . number_format($WORow['stdcost'],2) . "',
                                '" . ($WORow['stdcost'] * $QuantityReceived) . "')";

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
                                '" . $WOReceiptNo . "',
                                '" . Date('Y-m-d') . "',
                                '" . $PeriodNo . "',
                                '" . $StockGLCode['wipact'] . "',
                                '" . $wono . " " . $substock . " - " . $WORow['description'] . ' x ' . $QuantityReceived . " @ " . number_format($WORow['stdcost'],2) . "',
                                '" . -($WORow['stdcost'] * $QuantityReceived) . "')";

            $ErrMsg =   _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The WIP credit on receipt of finished items from a work order GL posting could not be inserted because');
            $DbgMsg =  _('The following SQL to insert the WIP GLTrans record was used');
            $Result = DB_query($SQL,$db, $ErrMsg, $DbgMsg,true);

        } /* end of if GL and stock integrated and standard cost !=0 */

        if (!isset($LastRef)) {
            $LastRef = '';
        }
        //update the wo with the new qtyrecd
        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('Could not update the work order item record with the total quantity received because');
        $DbgMsg = _('The following SQL was used to update the work order');
        $UpdateWOResult =DB_query("UPDATE woitems
                                    SET qtyrecd=qtyrecd+" . $QuantityReceived . ",
                                        nextlotsnref='" . $LastRef . "'
                                    WHERE wo='" . $wono . "'
                                    AND stockid='" . $substock . "'",
                                    $db,$ErrMsg,$DbgMsg,true);


        $Result = DB_Txn_Commit($db);

        prnMsg(_('The receipt of') . ' ' . $QuantityReceived . ' ' . $WORow['units'] . ' ' . _('of')  . ' ' . $substock . ' - ' . $WORow['description'] . ' ' . _('against work order') . ' '. $wono . ' ' . _('has been processed'),'info');
       /* echo '<a href="' . $rootpath . '/SelectWorkOrder.php">' . _('Select a different work order for receiving finished stock against'). '</a>';*/
        /*unset($_POST['WO']);
        unset($_POST['StockID']);
        unset($_POST['IntoLocation']);
        unset($_POST['Process']);*/
     
      
    } //end if there were not input errors reported - so the processing was allowed to continue
    //function recieve_sub($db,$substock,$wono,$subqty,$locn,$type)
 if($type==2)
 {
 /* echo   $StkMoveN = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');*/
    $sqll=" SELECT `serialno` FROM `serialno`  ";
   $Resu = DB_query($sqll, $db);
   $serialno= DB_fetch_array($Resu); 
   for($i=$serialno[0]+1;$i<=$serialno[0]+$subqty;$i++)
   {
      $sqlmove="INSERT INTO `stockserialmoves`( `stockmoveno`, `stockid`, `serialno`, `moveqty`) VALUES ('" . $StkMoveNo. "','" . $substock . "','" . $substock . "-".$i."',1)";
       $Result = DB_query($sqlmove,$db);
      
      $sqlin=" INSERT INTO `stockserialitems`(`stockid`, `loccode`, `serialno`, `quantity`)
                     VALUES ('" . $substock . "','" . $locn . "','" . $substock . "-".$i."',1)";
                     $serial=$i;
                     
   $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('plant not credit');
                    $DbgMsg = _('Plant is credit');
                    $Result = DB_query($sqlin,$db,$ErrMsg,$DbgMsg,true);
   }
   $sq="UPDATE `serialno` SET `serialno`='".$serial."'";
   $Result = DB_query($sq,$db);
 }
  }
?>
