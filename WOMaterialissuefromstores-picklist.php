<?php
$PageSecurity = 11; 
include('includes/session.inc');
$title = _('Materials issue');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');

if(isset($_POST['Mrequest']))       {  
$fieldid=$_POST['Minumber'];

$_POST['FromLocation']=$_SESSION['UserStockLocation']; 

if($_POST['Itemcount']==0)       {
    
echo'<script>alert("no quantities were entered");</script>';
  
}
for($i=0;$i<$_POST['Itemcount'];$i++)       {   

    if($_POST['Qty'.$i]=='')     {
        
        $_POST['Qty'.$i]=0;
    }



$QuantityIssued=$_POST['Qty'.$i];



        $SQL1="SELECT qtyrequest,
                      qtyissued
           FROM womaterialrequestdetails
           WHERE reqno=".$_POST['Srno']." AND
                 stockid='".$_POST['Itemcode'.$i]."'";
    $result1=DB_query($SQL1,$db);
    $myrow1=DB_fetch_array($result1);

          $EffQty=$myrow1[1] + $_POST['Qty'.$i];

//          if ($myrow1[0] < $QuantityIssued){
//            $InputError = true;
//            
//            prnMsg(_('This issue cannot be processed because the issued quantity is greater than the request quantity'),'error');
//            
//            

//        } else if ($myrow1[0] < $EffQty){
//            $InputError = true;
//            prnMsg(_('This issue cannot be processed because the sum of quantity entered and quanity already issued exeeds the request quantity'),'error');

//        }
        
    $SQL = "SELECT materialcost+labourcost+overheadcost AS cost,
            controlled,
            serialised,
            mbflag
        FROM stockmaster
        WHERE stockid='" .$_POST['Itemcode'.$i] . "'";
    $Result = DB_query($SQL,$db);
    $IssueItemRow = DB_fetch_array($Result);

//    if ($IssueItemRow['cost']==0){
//        
//        echo "dfghgffgfgh".$_POST['Itemcode'.$i];
//        prnMsg(_('The item being issued has a zero cost. Zero cost items cannot be issued to work orders'),'error');
//        $InputError=1;
//    }   
 
     if ($_SESSION['ProhibitNegativeStock']==1
            AND ($IssueItemRow['mbflag']=='M' OR $IssueItemRow['mbflag']=='B')){
                                            //don't need to check labour or dummy items
        $SQL = "SELECT quantity FROM locstock
                WHERE stockid ='" . $_POST['Itemcode'.$i] . "'
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

//        $Result = DB_Txn_Begin($db);
        /*Now Get the next WO Issue transaction type 28 - function in SQL_CommonFunctions*/
        $WOIssueNo = GetNextTransNo(28, $db);

        $PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']), $db);
        $SQLIssuedDate = FormatDateForSQL($_POST['IssuedDate']);
        $StockGLCode = GetStockGLCode($_POST['Itemcode'.$i],$db);        
 
 
                 if ($IssueItemRow['mbflag']=='M' OR $IssueItemRow['mbflag']=='B'){
                     
            /* Need to get the current location quantity will need it later for the stock movement */
            $SQL="SELECT locstock.quantity
                FROM locstock
                WHERE locstock.stockid='" . $_POST['Itemcode'.$i] . "'
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
                WHERE locstock.stockid = '" . $_POST['Itemcode'.$i] . "'
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
                    VALUES ('" . $_POST['Itemcode'.$i] . "',
                            28,
                            " . $WOIssueNo . ",
                            '" . $_POST['FromLocation'] . "',
                            '" . $SQLIssuedDate . "',
                            " . $IssueItemRow['cost'] . ",
                            " . $PeriodNo . ",
                            '" . $_POST['Wono'] . "',
                            " . -$QuantityIssued . ",
                            " . $IssueItemRow['cost'] . ",
                            " . $NewQtyOnHand . ")";     
                            

        $Result = DB_query($SQL,$db);

        /*Get the ID of the StockMove... */
        $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');      
        
                $sql="INSERT INTO dev_materialissuedetails(mino,stockid,quantity,stockmoveno)
                      VALUES(".$fieldid.",
                             '".$_POST['Itemcode'.$i]."',
                             " .$QuantityIssued . ",
                             " . $StkMoveNo . "
                             )"; 
               $result=DB_query($sql,$db);
               
if ($IssueItemRow['controlled'] ==1){
    
                    if (trim($_POST['BatchRef' .$i]) != ""){
                    
                         $SQL = "SELECT COUNT(*) FROM stockserialitems
                                WHERE stockid='" .$_POST['Itemcode'.$i] . "'
                                AND loccode = '" . $_POST['FromLocation'] . "'
                                AND serialno = '" . $_POST['BatchRef' .$i] . "'";
                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not check if a batch/lot reference for the item already exists because');
                        $DbgMsg =  _('The following SQL to test for an already existing controlled item was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        $AlreadyExistsRow = DB_fetch_row($Result);       
                        
                                        
                        
                        if ($AlreadyExistsRow[0]>0){
                            $SQL = 'UPDATE stockserialitems SET quantity = quantity - ' . $_POST['Qty' . $i] . "
                                        WHERE stockid='" . $_POST['Itemcode'.$i] . "'
                                        AND loccode = '" . $_POST['FromLocation'] . "'
                                        AND serialno = '" . $_POST['BatchRef' .$i] . "'";
                        } else {
                            $SQL = "INSERT INTO stockserialitems (stockid,
                                                loccode,
                                                serialno,
                                                qualitytext,
                                                quantity)
                                                VALUES ('" . $_POST['Itemcode'.$i] . "',
                                                '" . $_POST['FromLocation'] . "',
                                                '" . $_POST['BatchRef' . $i] . "',
                                                '',
                                                " . -($_POST['Qty'.$i]) . ")";
                        }

                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The batch/lot item record could not be inserted because');
                        $DbgMsg =  _('The following SQL to insert the batch/lot item records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);   
                        

    

   
    
    
                            $SQL = "INSERT INTO stockserialmoves (stockmoveno,
                                            stockid,
                                            serialno,
                                            moveqty)
                                    VALUES (" . $StkMoveNo . ",
                                            '" . $_POST['Itemcode'.$i] . "',
                                            '" . $_POST['BatchRef'.$i]  . "',
                                            " . $_POST['Qty'.$i]  . ")";
                        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                        $DbgMsg = _('The following SQL to insert the serial stock movement records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);   
                                             
                        
                    }
}
   

          

    $sql="UPDATE womaterialrequestdetails
          SET qtyissued=qtyissued+".$_POST['Qty'.$i]."
          WHERE reqno=".$_POST['Srno']."    AND
                stockid='".$_POST['Itemcode'.$i]."'";
    $result=DB_query($sql,$db);        
        
        
        
    } 

}

   $sql4="INSERT INTO dev_materialissue(mino,
                                        srno,
                                        date
                                        )
          VALUES(".$fieldid.",
                 ".$_POST['Srno'].",
                 '".$SQLIssuedDate."'
                )"; 
   $result4=DB_query($sql4,$db);
                
   $sql1="SELECT qtyrequest,     
                qtyissued
         FROM   womaterialrequestdetails
         WHERE  reqno=".$_POST['Srno']."";
   $result1=DB_query($sql1,$db);
   while($myrow1=DB_fetch_array($result1))      {
   
   if($myrow1['qtyrequest']==$myrow1['qtyissued'])      {
   
   $srcomplete=1;    
       
   }else  {
       
   $srcomplete=0;     
   }    
       
   }
if($srcomplete==1)      {
    
$sql="UPDATE womaterialrequest
      SET statusid=4
      WHERE reqno=".$_POST['Srno']."";   
$result=DB_query($sql,$db);
}
//echo'<script>window.close();</script>';
exit;
}
else if(isset($_POST['batchessub']))       {

    
if(!isset($_POST['FromLocation']))      {
    
$_POST['FromLocation']=$_SESSION['UserStockLocation'];     
}
$issueqty=0;
for($i=0;$i<$_POST['Batchcount'];$i++)       {

$issueqty+=$_POST['Batchqty'.$i];  
    
}    

if($issueqty>$_POST['Srqty'])       {

echo"total quantity exceeds the requested qty";    
exit;    
}else       {

    $SQL = "SELECT materialcost+labourcost+overheadcost AS cost,
            controlled,
            serialised,
            mbflag
        FROM stockmaster
        WHERE stockid='" .$_POST['StockID'] . "'";
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
                WHERE stockid ='" . $_POST['StockID'] . "'
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

//        $Result = DB_Txn_Begin($db);
        /*Now Get the next WO Issue transaction type 28 - function in SQL_CommonFunctions*/
        $WOIssueNo = GetNextTransNo(28, $db);

        $PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']), $db);
        $SQLIssuedDate = FormatDateForSQL($_POST['IssuedDate']);
        $StockGLCode = GetStockGLCode($_POST['StockID'],$db);        
 
 
                 if ($IssueItemRow['mbflag']=='M' OR $IssueItemRow['mbflag']=='B'){
                     
            /* Need to get the current location quantity will need it later for the stock movement */
            $SQL="SELECT locstock.quantity
                FROM locstock
                WHERE locstock.stockid='" . $_POST['StockID'] . "'
                AND loccode= '" . $_POST['FromLocation'] . "'";

            $Result = DB_query($SQL, $db);
            if (DB_num_rows($Result)==1){
                $LocQtyRow = DB_fetch_row($Result);
                $NewQtyOnHand = ($LocQtyRow[0] - $issueqty);
            } else {
            /*There must actually be some error this should never happen */
                $NewQtyOnHand = 0;
            }

            $SQL = "UPDATE locstock
                SET quantity = locstock.quantity - " . $issueqty . "
                WHERE locstock.stockid = '" . $_POST['StockID'] . "'
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
                    VALUES ('" . $_POST['StockID'] . "',
                            28,
                            " . $WOIssueNo . ",
                            '" . $_POST['FromLocation'] . "',
                            '" .$SQLIssuedDate. "',
                            " . $IssueItemRow['cost'] . ",
                            " . $PeriodNo . ",
                            '" . $_POST['Wono'] . "',
                            " . -$issueqty . ",
                            " . $IssueItemRow['cost'] . ",
                            " . $NewQtyOnHand . ")";     
                            

        $Result = DB_query($SQL,$db);

        /*Get the ID of the StockMove... */
        $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');         
   
if ($IssueItemRow['controlled'] ==1){
    
for($i=0;$i<$_POST['Batchcount'];$i++)       {    
    
                    if (trim($_POST['Batchno' .$i]) != ""){
                    
                         $SQL = "SELECT COUNT(*) FROM stockserialitems
                                WHERE stockid='" .$_POST['StockID'] . "'
                                AND loccode = '" . $_POST['FromLocation'] . "'
                                AND serialno = '" . $_POST['Batchno' .$i] . "'";
                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not check if a batch/lot reference for the item already exists because');
                        $DbgMsg =  _('The following SQL to test for an already existing controlled item was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                        $AlreadyExistsRow = DB_fetch_row($Result);       
                        
                                        
                        
                        if ($AlreadyExistsRow[0]>0){
                            $SQL = 'UPDATE stockserialitems SET quantity = quantity - ' . $_POST['Batchqty' . $i] . "
                                        WHERE stockid='" . $_POST['StockID'] . "'
                                        AND loccode = '" . $_POST['FromLocation'] . "'
                                        AND serialno = '" . $_POST['Batchno' .$i] . "'";
                        } else {
                            $SQL = "INSERT INTO stockserialitems (stockid,
                                                loccode,
                                                serialno,
                                                qualitytext,
                                                quantity)
                                                VALUES ('" . $_POST['StockID'] . "',
                                                '" . $_POST['FromLocation'] . "',
                                                '" . $_POST['Batchno' . $i] . "',
                                                '',
                                                " . -($_POST['Batchqty'.$i]) . ")";
                        }

                        $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The batch/lot item record could not be inserted because');
                        $DbgMsg =  _('The following SQL to insert the batch/lot item records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);   
                        
    $sql="UPDATE womaterialrequestdetails
          SET qtyissued=qtyissued+".$_POST['Batchqty'.$i]."
          WHERE reqno=".$_POST['Srno']."    AND
                stockid='".$_POST['StockID']."'";
    $result=DB_query($sql,$db);
    

   
    
    
                            $SQL = "INSERT INTO stockserialmoves (stockmoveno,
                                            stockid,
                                            serialno,
                                            moveqty)
                                    VALUES (" . $StkMoveNo . ",
                                            '" . $_POST['StockID'] . "',
                                            '" . $_POST['Batchno'.$i]  . "',
                                            " . $_POST['Batchqty'.$i]  . ")";
                        $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                        $DbgMsg = _('The following SQL to insert the serial stock movement records was used');
                        $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);   
                                             
                        
                    }
}
}   

   $sql1="SELECT qtyrequest,     
                qtyissued
         FROM   womaterialrequestdetails
         WHERE  reqno=".$_POST['Srno']."";
   $result1=DB_query($sql1,$db);
   while($myrow1=DB_fetch_array($result1))      {
   
   if($myrow1['qtyrequest']==$myrow1['qtyissued'])      {
   
   $srcomplete=1;    
       
   }else  {
       
   $srcomplete=0;     
   }    
       
   }
if($srcomplete==1)      {
    
$sql="UPDATE womaterialrequest
      SET statusid=4
      WHERE reqno=".$_POST['Srno']."";   
$result=DB_query($sql,$db);
}
//echo'<script>window.close();</script>';
exit;
        
    }    

    
    
    
}

    
}

$Srno=$_GET['id'];
$sql6="SELECT womaterialrequestdetails.stockid,
              womaterialrequestdetails.qtyrequest,
              womaterialrequestdetails.qtyissued,
              stockmaster.description,
              stockmaster.controlled,
              locstock.quantity
       FROM womaterialrequestdetails,stockmaster,locstock
       WHERE reqno=".$Srno."    AND
             womaterialrequestdetails.stockid=stockmaster.stockid       AND
             locstock.stockid=womaterialrequestdetails.stockid      AND
             locstock.loccode=".$_SESSION['UserStockLocation'];
$result6=DB_query($sql6,$db);

echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo'<table><tr>';
echo"<td>Issue date:</td><td><input type='text' name='IssuedDate' id='issuedate' size=25
            maxlength=12 ></td></tr>";

echo"<tr><td>Issue number:</td><td><input type='text' name='Minumber' id='minumber' size=25
maxlength=12 ></td></tr>";
echo'<tr>';

echo'<th>slno</th>';
echo'<th>Item</th>';
echo'<th>Qty pending</th>';
echo'<th>SR Quantity</th>';
echo'<th>Qty already issued</th>';
echo'<th>Quantity On Hand</th>';
echo'<th>Batch</th>';
echo'</tr>';


$slno=1;
$i=0;
while($myrow6=DB_fetch_array($result6))     {

$qtypending=$myrow6['qtyrequest']-$myrow6['qtyissued'];      
$sql11="SELECT quantity,
               serialno 
        FROM   stockserialitems
        WHERE stockid='".$myrow6['stockid']."'";
$result11=DB_query($sql11,$db);

while($myrow11=DB_fetch_array($result11))       {

if($qtypending<=$myrow11['quantity'])        {
    
$batchno=$myrow11['serialno'];   
$batchflag=1;
break;
}    
   
}

    
       If($myrow6['quantity']<$myrow6['qtyrequest'])        {
           
       echo '<tr bgcolor="#FF6A6A">';    
       } 
      elseif ($k==1)
        {
            echo '<tr class="EvenTableRows">';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows">';
            $k++;
        }  

echo'<td>'.$slno.'</td>';

            $part=$myrow6['stockid'];
            $quantity=$myrow6['qtyrequest'];
            
            if($myrow6['qtyrequest']==$myrow6['qtyissued'])         {
            $disabled="disabled='disabled'";    
            echo'<td>'.$myrow6['description'].'</td>';
            echo'<td>'.$qtypending.'</td>';
            echo'<td>'.$myrow6['qtyrequest'].'</td>
                 <td>'.$myrow6['qtyissued'].'</td>
                 <td>'.$myrow6['quantity'].'</td>';
            echo'<td></td>'; 
            }else       {
            $disabled="";
            echo'<td><input type="hidden"  name="Itemcode'.$i.'" id="itemcode'.$i.'" value="'.$myrow6['stockid'].'">'.$myrow6['description'].'</td>';
            echo'<td><input type="text"  name="Qty'.$i.'" value="'.$qtypending.'"></td>';
            echo'<input type="hidden"  name="Srqty2'.$i.'" id="srqty2'.$i.'">';
            echo'<td>'.$myrow6['qtyrequest'].'</td>
                 <td>'.$myrow6['qtyissued'].'</td>
                 <td>'.$myrow6['quantity'].'</td>';
       
       if($myrow6['controlled']==1)       {    
       if($batchflag==1)        {
           
           echo'<td><input type="text" '.$disabled.' name="BatchRef'.$i.'" value="'.$batchno.'"></td>';    
       }else{
           
           echo'<td><a onclick="batches('.$myrow6['stockid'].','.$qtypending.','.$Srno.')">Batches</a></td>';
           echo'<td><input type="hidden" id="multbatchcheck" value="yes"></td>';
       
       }
            
            }
            else       {
                
             echo'<td></td>';   
                
            } 
            
            $i++;
            }  
       
       
       
       

            
           

       echo'</tr>';

          
$slno++;  

}
 echo'<input type="hidden" name="Itemcount" id="itemcount_mi" value="'.$i.'">';
 echo'<input type="hidden" name="Srno" value="'.$Srno.'">';
 echo'<tr><td><input type="submit" name="Mrequest" id="mrequest" value="Submit" onclick="if(submitcheck()==1){return false;}"></td></tr>';
 echo'</form>';
 echo'</table>';
?>
<script>
calenderr("issuedate");

function calenderr(str){
        new JsDatePick({
            useMode:2,
            target:str,
            dateFormat:"%d/%m/%Y"
            /*selectedDate:{                This is an example of what the full configuration offers.
                day:5,                        For full documentation about these settings please see the full version of the code.
                month:9,
                year:2006
            },
            yearsRange:[1978,2020],
            limitToToday:false,
            cellColorScheme:"beige",
            dateFormat:"%m-%d-%Y",
            imgPath:"img/",
            weekStartDay:1*/
        });
    };

function bomdetailsofitem(str1)     {
    
myRef.close();
}
function batches(str1,str2,str3)      {

myRef2 = window.open('WOMaterialissuefromstores-popup-batches.php?id='+ str1+ '&qty='+str2 + '&srno='+str3,'estr1',
'left=20,top=20,width=400,height=500,toolbar=1,scrollbars=1,dependent=yes');
} 
function submitcheck()      {
    
var f=0;
f=common_error('issuedate','Date field cannot be empty');  if(f==1) { return f;}
if(f==0){f=common_error('minumber','Enter the issuenumber');  if(f==1) {return f; }} 

var batchcheck=document.getElementById("multbatchcheck").value;
if (batchcheck=="yes")      {

f=1;
alert("some items are to be issued from multiple batches");
return f;    
}  
}   
 
</script>
