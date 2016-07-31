<?php

$PeriodNo = GetPeriod ($_SESSION['Transfer']->TranDate, $db);
 $sql = "INSERT INTO loctransfers (reference,
                                stockid,
                                shipqty,
                                shipdate,
                                shiploc,
                                recloc)
                        VALUES ('" . $_SESSION['trf_id'] . "',
                            '" . $itemcode . "',
                            '" . $_POST['totalqty'] . "',
                            '" . Date('Y-m-d') . "',
                            '" . $_POST['id1']  ."',
                            '" . $_POST['id2']. "')";

            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('Unable to enter Location Transfer record for'). ' '.$_POST['StockID' . $i];
            $resultLocShip = DB_query($sql,$db, $ErrMsg);
            
                if($_POST['totalqty']=$_POST['lineqty'])        {
                $sql="UPDATE stocktransfer
                       SET done=1
                        WHERE slno='$_SESSION[id1]'
                       AND itemcode='$itemcode'
                       ";
           
                 $ErrMsg =  _('The status could not be updated because');
                 $DbgMsg = _('The SQL statement used to process the request that failed was');
                 $result =DB_query($sql,$db,$ErrMsg,$DbgMsg);   
           }      
                 
            $sql = "INSERT INTO loctransferstatus 
                                (reference,
                                requestno)
                        VALUES ('" . $_SESSION['trf_id'] . "' ,                
                           $_SESSION[id1] )";
            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('Unable to enter Location Transfer record for'). ' '.$_POST['StockID' . $i];
            $resultLocShip = DB_query($sql,$db, $ErrMsg);
            
                 
                /* Need to get the current location quantity will need it later for the stock movement */
                $SQL="SELECT locstock.quantity
                        FROM locstock
                        WHERE locstock.stockid='" . $itemcode . "'
                        AND loccode= '" .$frmstore  . "'";

                $Result = DB_query($SQL, $db, _('Could not retrieve the stock quantity at the dispatch stock location prior to this transfer being processed') );
                if (DB_num_rows($Result)==1){
                    $LocQtyRow = DB_fetch_row($Result);
                    $QtyOnHandPrior = $LocQtyRow[0];
                } else {
                    /* There must actually be some error this should never happen */
                    $QtyOnHandPrior = 0;
                }
                 
                 
                /* Insert the stock movement for the stock going out of the from location */ 
                 $SQL = "INSERT INTO stockmoves (
                            stockid,
                            type,
                            transno,
                            loccode,
                            trandate,
                            prd,
                            reference,
                            qty,
                            newqoh)
                    VALUES (
                        '" . $itemcode . "',
                        16,
                        " . $_SESSION['trf_id'] . ",
                        '" . $frmstore  . "',
                        '" . Date('Y-m-d'). "',
                        " . $PeriodNo . ",
                        '" . _('To') . ' ' . $tostore . "',
                        '" . -$_POST['totalqty'] . "',
                        " . ($QtyOnHandPrior -$_POST['totalqty']  ) . "
                    )";
                 
                 
                    $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock movement record for the incoming stock cannot be added because');
                $DbgMsg =  _('The following SQL to insert the stock movement record was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                
       
                /*Get the ID of the StockMove... */
                $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');

                
for ($i=0;$i<$_POST['PropertyCounter'];$i++){
                 
                 
                  $sql = "INSERT INTO loctransferbatch (reference,
                                itemcode,
                                batchref,
                                qty)
                        VALUES ('" . $_SESSION['trf_id'] . "',
                            '" . $itemcode . "',
                            '" . $_POST['BatchRef'. $i] . "',
                            '". $_POST['Qty'. $i]."')";
            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('Unable to enter Location Transfer record for'). ' '.$_POST['StockID' . $i];
            $resultLocShip = DB_query($sql,$db, $ErrMsg);
            
          
                                           /*First need to check if the serial items already exists or not in the location from */
                        $SQL = "SELECT COUNT(*)
                            FROM stockserialitems
                            WHERE
                            stockid='" . $itemcode . "'
                            AND loccode='" . $frmstore . "'
                            AND serialno='" . $_POST['BatchRef'. $i] . "'";

                        $Result = DB_query($SQL,$db,'<br>' . _('Could not determine if the serial item exists') );
                        $SerialItemExistsRow = DB_fetch_row($Result);

                        if ($SerialItemExistsRow[0]==1){

                            $SQL = "UPDATE stockserialitems SET
                                quantity= quantity - " . $_POST['Qty'. $i] . "
                                WHERE
                                stockid='" . $itemcode . "'
                                AND loccode='" . $frmstore . "'
                                AND serialno='" . $_POST['BatchRef'. $i] . "'";

                            $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated because');
                            $DbgMsg = _('The following SQL to update the serial stock item record was used');
                            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        } 


                        $SQL = "INSERT INTO stockserialmoves (
                                stockmoveno,
                                stockid,
                                serialno,
                                moveqty
                            ) VALUES (
                                " . $StkMoveNo . ",
                                '" . $itemcode . "',
                                '" . $_POST['BatchRef'. $i] . "',
                                " . -$_POST['Qty'. $i]. "
                            )";
                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                        $DbgMsg =  _('The following SQL to insert the serial stock movement records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                   



                  
             }
             
 $SQL = "UPDATE locstock
                    SET quantity = quantity - " .$_POST['totalqty'] . "
                    WHERE stockid='" .  $itemcode . "'
                    AND loccode='" . $frmstore . "'";

                $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
                $DbgMsg =  _('The following SQL to update the stock record was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);             
             
                 prnMsg( _('The inventory transfer records have been created successfully'),'success');
    echo '<p><a href="'.$rootpath.'/PDFSin.php?' . SID . 'TransferNo=' . $_SESSION['trf_id'] . '">'.
        _('Print the Transfer Docket'). '</a>';
        echo "<br>";
   echo '<a href="'.$rootpath.'/StockLocTransfer.php?' . SID .'">' ._('Back to Store Requistion') . '</a><br>';
                 
?>
