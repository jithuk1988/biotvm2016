<?php
/* $Revision: 1.11 $ */
/* contributed by Chris Bice */

$PageSecurity = 11;
include('includes/session.inc');
$title = _('Stock Request Issue ');
include('includes/header.inc');
$pagetype=2;
include('includes/sidemenu.php');
include('includes/SQL_CommonFunctions.inc');


If (isset($_POST['Submit']) OR isset($_POST['EnterMoreItems'])){
/*Trap any errors in input */

	$InputError = False; /*Start off hoping for the best */
	$TotalItems = 0;
	//Make sure this Transfer has not already been entered... aka one way around the refresh & insert new records problem
	$result = DB_query("SELECT * FROM loctransfers WHERE reference='" . $_POST['Trf_ID'] . "'",$db);
	if (DB_num_rows($result)!=0){
		$InputError = true;
		$ErrorMessage = _('This transaction has already been entered') . '. ' . _('Please start over now').'<br>';
		unset($_POST['submit']);
		unset($_POST['EnterMoreItems']);

	}
	$ErrorMessage='';

}


$frmstore=$_GET['id1'];
$tostore=$_GET['id2'];
$itemcode=$_GET['itemcode'];


 if((isset ($_POST['issue'])) )       {
  
     
         $PeriodNo = GetPeriod ($_SESSION['Transfer']->TranDate, $db);
       
           
           foreach ($_POST as $key => $value) {
        if (substr($key,0,3)=='qty') {
            $ItemCode=substr($key,3, strlen($key)-3);
            $Quantity=$value;
           
           
        }
           }
             
          $sql = "INSERT INTO loctransfers (reference,
                                stockid,
                                shipqty,
                                shipdate,
                                shiploc,
                                recloc)
                        VALUES ('" . $_SESSION['trf_id'] . "',
                            '" . $ItemCode . "',
                            '" . $Quantity . "',
                            '" . Date('Y-m-d') . "',
                            '" . $frmstore  ."',
                            '" . $tostore. "')";
            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('Unable to enter Location Transfer record for'). ' '.$_POST['StockID' . $i];
            $resultLocShip = DB_query($sql,$db, $ErrMsg);
            
            
            $sql="UPDATE stocktransfer
                       SET done=1
                        WHERE slno='$_SESSION[id1]'
                       AND itemcode='$ItemCode'
                       ";
           
                 $ErrMsg =  _('The status could not be updated because');
                 $DbgMsg = _('The SQL statement used to process the request that failed was');
                 $result =DB_query($sql,$db,$ErrMsg,$DbgMsg);   
                 
                 
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
                        WHERE locstock.stockid='" . $ItemCode . "'
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
                        '" . $ItemCode . "',
                        16,
                        " . $_SESSION['trf_id'] . ",
                        '" . $frmstore  . "',
                        '" . Date('Y-m-d'). "',
                        " . $PeriodNo . ",
                        '" . _('To') . ' ' . $tostore . "',
                        " . -$Quantity  . ",
                        " . ($QtyOnHandPrior - $Quantity ) . "
                    )";
                 
                 
                    $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock movement record for the incoming stock cannot be added because');
                $DbgMsg =  _('The following SQL to insert the stock movement record was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                

 $SQL = "UPDATE locstock
                    SET quantity = quantity - " . $Quantity . "
                    WHERE stockid='" .  $ItemCode . "'
                    AND loccode='" . $frmstore . "'";

                $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
                $DbgMsg =  _('The following SQL to update the stock record was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

                 prnMsg( _('The inventory transfer records have been created successfully'),'success'); 
    echo '<p><a href="'.$rootpath.'/PDFSin.php?' . SID . 'TransferNo=' . $_SESSION['trf_id'] . '">'.
        _('Print the Transfer Docket'). '</a>';
        echo "<br>";
   echo '<a href="'.$rootpath.'/StockLocTransfer.php?' . SID .'">' ._('Back to Store Requistion') . '</a><br>';
   
        
    include('includes/footer.inc');
        exit;
   
         
       }//------multiple batches
       
       
       if (isset($_POST['batch'])){
           
           
              $PeriodNo = GetPeriod ($_SESSION['Transfer']->TranDate, $db);
       
           $frmstore= $_GET['id1'];
           $tostore= $_GET['id2'];
           
                       //  echo $itemcode;
//                         echo $_GET['qty'];


 echo$sql = "INSERT INTO loctransfers (reference,
                                stockid,
                                shipqty,
                                shipdate,
                                shiploc,
                                recloc)
                        VALUES ('" . $_SESSION['trf_id'] . "',
                            '" . $itemcode . "',
                            '" . $_GET['qty'] . "',
                            '" . Date('Y-m-d') . "',
                            '" . $_GET['id1']  ."',
                            '" . $_GET['id2']. "')";
                            
                            exit;
            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('Unable to enter Location Transfer record for'). ' '.$_POST['StockID' . $i];
            $resultLocShip = DB_query($sql,$db, $ErrMsg);
            
                $sql="UPDATE stocktransfer
                       SET done=1
                        WHERE slno='$_SESSION[id1]'
                       AND itemcode='$itemcode'
                       ";
           
                 $ErrMsg =  _('The status could not be updated because');
                 $DbgMsg = _('The SQL statement used to process the request that failed was');
                 $result =DB_query($sql,$db,$ErrMsg,$DbgMsg);   
                 
                 
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
                        '" . -$_GET['qty'] . "',
                        " . ($QtyOnHandPrior -$_GET['qty']  ) . "
                    )";
                 
                 
                    $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock movement record for the incoming stock cannot be added because');
                $DbgMsg =  _('The following SQL to insert the stock movement record was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                
       
                /*Get the ID of the StockMove... */
                $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');
                   
                         
             for ($i=0;$i<=$_POST['PropertyCounter'];$i++){
                 
                 
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
                   

 $SQL = "UPDATE locstock
                    SET quantity = quantity - " .$_GET['qty'] . "
                    WHERE stockid='" .  $itemcode . "'
                    AND loccode='" . $frmstore . "'";

                $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
                $DbgMsg =  _('The following SQL to update the stock record was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);

                  
             }
                
                 prnMsg( _('The inventory transfer records have been created successfully'),'success');
    echo '<p><a href="'.$rootpath.'/PDFSin.php?' . SID . 'TransferNo=' . $_SESSION['trf_id'] . '">'.
        _('Print the Transfer Docket'). '</a>';
        echo "<br>";
   echo '<a href="'.$rootpath.'/StockLocTransfer.php?' . SID .'">' ._('Back to Store Requistion') . '</a><br>';
   
           
           include('includes/footer.inc');
           exit;
           
           
       }//--------final submit
       

if(isset($_POST['Submit']) AND $InputError==False){
 
    
    if (isset($_GET['Trf_ID'])){
        $Trf_ID = $_GET['Trf_ID'];
    } elseif (isset($_POST['Trf_ID'])){
        $Trf_ID = $_POST['Trf_ID'];
    }

    if(!isset($Trf_ID)){
        $Trf_ID = GetNextTransNo(16,$db);
    }

    If (isset($InputError) and $InputError==true){
        echo '<br>';
        
        prnMsg($ErrorMessage, 'error');
        echo '<br>';

    }
    
     echo '<a href="'.$rootpath.'/StockLocTransfer.php?' . SID .'">' ._('Back to Store Requistion') . '</a><br>';

    echo '<form action="' . $_SERVER['PHP_SELF'] . '?'. SID . '" method=post>';

    echo '<div class="centre"><input type=hidden name="Trf_ID" VALUE="' . $Trf_ID . '"><h2>'. _('Store Request Issue ').'</h2>';
    
    echo "</form>";
      
   
    $_SESSION['trf_id']=$Trf_ID;
    
    
    $_SESSION['id1']=$_POST['reqstno'. $_POST['Submit']];
    
                
       $sql2='SELECT  stockmaster.controlled,
                      stockmaster.serialised,
                      stocktransfer.itemcode,
                      stocktransfer.quantity,
                      stocktransfer.storeid,
                      stocktransfer.tostore                                 
                      FROM stockmaster,stocktransfer WHERE stocktransfer.slno="'.$_SESSION['id1'].'"
                      AND stockmaster.stockid=stocktransfer.itemcode
                      AND stocktransfer.done=0
                      AND stocktransfer.itemcode="'.$_POST['itemcode'. $_POST['Submit']].'"
                       ';
                      $result2 = DB_query($sql2,$db);
   
       
              echo '<table cellpadding=2 colspan=7 border=0>
              <tr><th>' . _('Request no:') . '</th>  
              <th>' . _('Item Code') . '</th>
              <th>' . _('To store') .  '</th>
              <th>' . _('Quantity') .  '</th>
              ';


         echo '<td></td>
                </tr>';
               
                if ($k==1){
            echo '<tr class="EvenTableRows">';
            $k=0;
        } else {
            echo '<tr class="OddTableRows">';
            $k=1;
        }
                 
                 
               $myrow2=DB_fetch_array($result2);
               
               $controlled=$myrow2[0];
                $serialised=$myrow2[1];
                   $itemcode=$myrow2[2];
                   $Qty=$myrow2[3];
                  $fromstore=$myrow2[4];
                  $tostore=$myrow2[5];
               
         
      echo '<form action="' . $_SERVER['PHP_SELF'] . '?&id1='.$fromstore.'&id2='. $tostore.'&itemcode='.$itemcode.'&qty='.$Qty.''.SID.'" method=post>';          
       printf("<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><input class='number' type='text' size=6 value='$Qty' name='qty%s'></td>
           
             ",
            $_SESSION['id1'],
            $itemcode,
            $tostore,
            $itemcode
             );
             
                     
                if (($controlled==1) AND ($serialised==0))     { 
                    
                    
               echo '<table><tr><td><input type=hidden name="qtyreq" value="'.$Qty.'">
                     <input type=hidden name="stockid" value=" '.$itemcode.'"></td></tr></table>';
                     
                       echo '<form action="' . $_SERVER['PHP_SELF'] . '?itemcode='.$_POST['stockid'].''. SID . '" method=post>';
                    
        
    $sql=" SELECT   stockserialitems.serialno,
                   stockserialitems.quantity
             FROM stockserialitems
             WHERE stockserialitems.stockid='$itemcode'
            
             AND stockserialitems.quantity>0"; 
             
    $ErrMsg = _('Could not retrieve the details of the selected item');          
    $result=DB_query($sql,$db,$ErrMsg);         
    
     
     if (DB_num_rows($result)==0 ){
        prnMsg (_('There are no products available meeting the criteria specified'),'info');
        
     }else{
         
         
     echo '<br><table><tr><th colspan="2">' . _('Batch/Lots Issued') . '</th></tr></table>'; 
     
       echo "<br>";
    
      
      $PropertyCounter =0;         
     while($myrow=DB_fetch_array($result)) {  
         
      
        if($Qty<=$myrow[1]){
            
           echo '<table><tr><td><input type="textbox" name='."BatchRef".$PropertyCounter.' size="20" maxlength="100" value="' . $myrow[0] . '">';
           
              //  echo '<table><tr><td><input type="textbox" name="BatchRef" value="'.$myrow[0].'">';
           echo '</td>
                     <td><input type="textbox" name='."Qty".$PropertyCounter.' value='.$Qty.'>
                      </td>
                      </tr></table>';   
                      
                       echo '<input type=hidden name="PropertyCounter" value=' . $PropertyCounter . '>';   
               
          echo '<div class="centre"><input type=submit name="batch" value="Issue Batch"></div><br>';   
          include('includes/footer.inc');    
            exit;
                      
            
        } else{
                 $Qty=$Qty-$myrow[1];      
                 $batch=  $myrow[0] +1;     
                                       
                echo '<table><tr><td><input type="text" name='."BatchRef".$PropertyCounter.' value="'.$myrow[0].'">';
                echo '</td>
                      <td><input type="textbox" name='."Qty".$PropertyCounter.' value="'.$myrow[1].'">
                      </td>
                      </tr></table>';
                     
 
        }
        

        $PropertyCounter++;                     
    }//-----------batch 
   
       
        
 }  
       //echo '<td><a href="StockLocTransferBatch.php?qty='.$Qty.'&itemcode='.$itemcode.''. SID .'">'._('Enter Batches'). '</a></td>';
  
 
                }
              echo "</tr>";          
    
 
              echo "</table>";
              
                 echo "<br>";
               
               
                    echo '<div class="centre"><input type=submit name="issue" value="Issue"></div><br>';    
                      echo "</form>";
                

    
    include('includes/footer.inc');


} 
else {
    
    
    echo '<div class=centre><h2>'. _('Store Request Issue ').'</h2></div>';



	echo '<table>';

	$tableheader = '<tr><th>'. _('Select'). '</th><th>'. _('Request no'). '</th><th>'. _('Item Code'). '</th><th>'. _('Delivery date'). '</th><th>'. _('Delivery Time'). '</th><th>'. _('To store'). '</th><th>'. _('Quantity'). '</th</tr>';
	echo $tableheader;

       $sql2='SELECT  slno, 
                      itemcode,
                      deliverydate,
                      deliverytime,
                      tostore,
                      quantity                                  
                      FROM stocktransfer WHERE done=0
                      order by slno';
                      $result2 = DB_query($sql2,$db);
   
        
             $PropertyCounter=0;
                while($myrow2=DB_fetch_array($result2)) {
                    
                    
                  $qty=$myrow2[4];
                  $itemcode= $myrow2['1'];
                    

  echo '<form action="' . $_SERVER['PHP_SELF'] . '?'.SID.'" method=post>';
	   printf(" <input class='number' type='hidden' size=6 value='$myrow2[1]' name=".'itemcode' . $PropertyCounter." >
                <input class='number' type='hidden' size=6 value='$myrow2[0]' name=".'reqstno' . $PropertyCounter." >
                <input type=hidden name=".'PropertyCounter' . $PropertyCounter."  value='  $PropertyCounter  '>
            <td><input type=submit name='Submit' value='$PropertyCounter'</td>    
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow2['0'],
            $myrow2['1'],
            $myrow2['2'],
            $myrow2['3'],
            $myrow2['4'],
            $myrow2['5']
            
            );
            
            
            $PropertyCounter++;
                
	}
    

	echo '</table>
		<input type=hidden name="LinesCounter" value='. $i .'>';
            echo '</form></div>';
        echo '<br><hr>';
	echo '<script  type="text/javascript">defaultControl(document.forms[0].StockID0);</script>';


	include('includes/footer.inc');
}//-------datagrid
?>

