<?php
 $PageSecurity = 80;
 include('includes/session.inc');
$title = _('Sale Order Approval Details');
include('includes/header.inc');
$orderid=$_GET['order'];

//$orderno=$_GET['orderno'];

if(isset($_POST['approve']))     {
 //$orderid=$_GET['order'];
     
$sql2="UPDATE salesorders SET so_status=1 where orderno=".$_POST['OrderNo']; 
$result2 = DB_query($sql2,$db); 
$sql4="UPDATE bio_orderapproval SET taskcomplted_status =1 WHERE orderno=".$_POST['OrderNo']; 
$result4 = DB_query($sql4,$db);
$sql6="SELECT leadid FROM salesorders WHERE orderno=".$_POST['OrderNo'];
$result6=DB_query($sql6,$db);
$row6=DB_fetch_array($result6);
$sql5="UPDATE bio_leads SET leadstatus=29 WHERE leadid='".$row6[leadid]."'"; 
$result5 = DB_query($sql5,$db);
    
 }
 
 
 if(isset($_POST['reject']))   {
 
 $sql3="UPDATE salesorders SET so_status=2 where orderno=".$_POST['OrderNo']; 
  $result3= DB_query($sql3,$db);     
 }


echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">ORDER DETAILS</font></center>';

     
       
//echo $_SESSION['ord']=$_GET['order'];
    
 
echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo"<fieldset style='width:700px;height:215px'>";
//echo"<legend>Customer Details</legend>";
echo '<table width="90%" >';
echo'<tr style=bgcolor:#3300FF;color:white>'; 
               
echo '<th>' . _('Item Code') . '</th>
                <th>' . _('Item Description') . '</th>
                <th>' . _('Quantity') . '</th>
                <th>' . _('Subsidy') . '</th>  
                <th>' . _('Unit Price') . '</th>';
echo '<th>' . _('Total') . '</th>
           </tr>'; 
  
       
  if($orderid!=""){         
   $SQL="SELECT salesorderdetails.stkcode, 
                        stockmaster.longdescription,  
                        salesorderdetails.quantity,
                        salesorderdetails.unitprice,
                        salesorders.leadid 
                    FROM salesorderdetails,stockmaster,salesorders
                    WHERE salesorderdetails.stkcode = stockmaster.stockid
                    AND   salesorders.orderno=salesorderdetails.orderno
                    AND salesorderdetails.orderno=".$orderid;
                           
     $result = DB_query($SQL,$db);     
     while($myrow=DB_fetch_array($result)){
 
         $k==1;
         if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                 echo '<tr class="OddTableRows">';
                $k=1;
            }  
                                
     $total=$myrow['unitprice']* $myrow['quantity'];
           
     $leadid=$myrow['leadid'];   
     $item=$myrow['stkcode'];   
            
     $result_enq=DB_query("SELECT enqtypeid FROM bio_leads WHERE leadid=$leadid",$db);
     $myrow_enq=DB_fetch_array($result_enq);
     
           
    if($myrow_enq['enqtypeid']==1){
        $sql_subsidy="SELECT SUM(amount) AS subsidy FROM bio_propsubsidy WHERE leadid=$leadid AND stockid='$item'";   
    }elseif($myrow_enq['enqtypeid']==2){
        $sql_subsidy="SELECT SUM(amount) AS subsidy FROM bio_cpsubsidy WHERE leadid=$leadid AND stockid='$item'";
    }       
    $result_subsidy=DB_query($sql_subsidy,$db);
    $count_subsidy=DB_num_rows($result_subsidy);
    if($count_subsidy>=1){
    $myrow_subsidy=DB_fetch_array($result_subsidy);
    $subsidy=$myrow_subsidy['subsidy']; 
    }          
           
                printf('<td>%s</td>
                        <td>%s</td>
                        <td align="center">%s</td>
                        <td align="center">%s</td>
                        <td class="number">%s</td>
                        <td class="number">%s</td> 
                        </tr>',                                                                           
                        $myrow['stkcode'],
                        $myrow['longdescription'],
                        $myrow['quantity'], 
                        $subsidy,                       
                        $myrow['unitprice'],   
                        $total); 
          $grandtotal+=$total;              
     }
     
     echo '<tr><td colspan=4></td>';
     echo '<td>Grand Total</td>';
     echo '<td>'.$grandtotal.'</td></tr>';  
    
echo '</td></tr>';                   
echo '</table>';
}         

echo"<table>";
echo'<tr><td colspan=2><div class="centre">';

echo '<input name="OrderNo" type="hidden" value='.$orderid.' >';
echo '<input name="approve" type="submit" value=Approve ><input name="reject" type="submit" value=Reject>';
echo '</div>';
echo '</td></tr>'; 
echo '</table>';

echo '</fieldset>';

echo '</form>';

echo '</div>';

include('includes/footer.inc'); 
?>
