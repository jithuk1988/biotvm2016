<?php
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
    
//     echo '<a href="'.$rootpath.'/StockLocTransfer.php?' . SID .'">' ._('Back to Store Requistion') . '</a><br>';



    echo '<input type=hidden name="Trf_ID" VALUE="' . $Trf_ID . '">';
    

      
   
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
   
       


               $myrow2=DB_fetch_array($result2);
               
               $controlled=$myrow2[0];
               $serialised=$myrow2[1];
               $itemcode=$myrow2[2];
               $totalqty=$myrow2[3];
               $fromstore=$myrow2[4];
               $tostore=$myrow2[5];
               
      
      echo'<input type="hidden" name="id1" value='.$fromstore.'>';   
      echo'<input type="hidden" name="id2" value='.$tostore.'>'; 
      echo'<input type="hidden" name="itemcode" value='.$itemcode.'>'; 
                     
if (($controlled==1) AND ($serialised==0))     { 
      
      $sql=" SELECT  stockserialitems.serialno,
                     stockserialitems.quantity
             FROM stockserialitems
             WHERE stockserialitems.stockid='$itemcode' AND
                   stockserialitems.loccode=$fromstore
             AND stockserialitems.quantity>0"; 
             
    $ErrMsg = _('Could not retrieve the details of the selected item');          
    $result=DB_query($sql,$db,$ErrMsg);         
    
     
     if (DB_num_rows($result)==0 ){
        prnMsg (_('No quantity available to be issued'),'info');
        
     }else{

     echo '<tr><th>' . _('Batch no:') . '</th><th>' . _('Quantity') . '</th></tr>'; 

     $f=0;
     $t=0;
     $PropertyCounter =0;  
     $pending=$totalqty; 
     $issueqty=0; 
          
while ($myrow=DB_fetch_array($result)) {  

    $batchqty=$myrow[1];  

if($t==0)       {
    
    if($myrow[1]<=$pending)    {
    
$issueqty=$issueqty+$batchqty;  
}     else  {
     $batchqty=$pending; 
     $issueqty=$issueqty+$batchqty; 
     $f=1; 
} 
echo '<tr><td>
      <input type="text" name='."BatchRef".$PropertyCounter.' size="20" maxlength="100" value="' . $myrow[0] . '">';

echo '</td>
      <td><input type="textbox" name='."Qty".$PropertyCounter.' value='.$batchqty.'>
      </td></tr>';    
      $PropertyCounter++;        
}       
if($f==1)       {
    
    $t=1;
}    
    if($batchqty!=$pending)     {       
    $pending=$pending-$myrow[1]; 
    }         
    }//-----------batch 
   
   
          echo'<input type=hidden name="lineqty" value='.$totalqty.'>';
          echo'<tr><td>Available Qty</td><td><input type=hidden name="totalqty" value='.$issueqty.'>'.$issueqty.'</td></tr>';  
          echo '<input type=hidden name="PropertyCounter" value=' . $PropertyCounter . '>';   
          echo '<tr><td></td><td><input type=submit name="batch" value="Issue Batch"></td></tr>';       
        
 }  
       //echo '<td><a href="StockLocTransferBatch.php?qty='.$Qty.'&itemcode='.$itemcode.''. SID .'">'._('Enter Batches'). '</a></td>';
                }else       {
      
                    echo '<tr><td></td><td><input type=submit name="issue" value="Issue"></td></tr>';    
                    
                }




}   
?>
