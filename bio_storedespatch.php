<?php
   $PageSecurity = 80;   
include('includes/session.inc');

$title = _('Stores Despatch');

include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Generate despatch Clearence') . '</p>';
    

if(isset($_POST['submit']))
{                       

   $orderno=array();
   $plantid=array();
                  
   for($i=1;$i<=$_POST['no'];$i++)
   {                                  
       
       if($_POST['selection'.$i]=='on')
       {
                   
          $order_plant=$_POST['val'.$i]; 
          $split=explode(',',$order_plant);
          
          $orderno[]=$split[0];
          $plantid[]="'$split[1]'"; 
          
       }
  
   }    
      $order=join(",",$orderno);    
      $plant=join(",",$plantid);        
            
      
      $sql_update="UPDATE salesorderdetails SET despatch=1 WHERE orderno IN ($order) AND stkcode IN ($plant)";
      DB_query($sql_update,$db);                    
}    
    
    
    
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">';

echo"<table width=100%><tr><td>";

echo"<div style='height:450px; overflow:scroll;'>";   
echo"<table style='border:1px solid #F0F0F0;width:80%'; >";   
echo"<tr>";
echo"<th>Order No & Date</th><th>Customer Name</th><th>District</th><th>Plant</th><th>Quantity</th><th>QOH</th><th>Pending Qty</th>";
                                                                                     //    salesorderdetails.despatchclearence,  
$sql="SELECT salesorderdetails.orderno,
             
             salesorders.orddate, 
             salesorders.debtorno,
             debtorsmaster.name,
             bio_district.district,
             salesorderdetails.stkcode,
             stockmaster.description,
             salesorderdetails.quantity
      FROM   salesorderdetails,salesorders,debtorsmaster,bio_district,stockmaster 
      WHERE  salesorders.orderno=salesorderdetails.orderno
      AND    debtorsmaster.debtorno=salesorders.debtorno
      AND    stockmaster.stockid=salesorderdetails.stkcode
      AND    bio_district.did=debtorsmaster.did AND bio_district.stateid=debtorsmaster.stateid AND bio_district.cid=debtorsmaster.cid
      AND    salesorderdetails.despatchclearence=1  
      AND    salesorderdetails.completed=0
      ORDER BY salesorderdetails.orderno";   
      $result=DB_query($sql,$db);  
      
      $j=0;
      $k=0;
      while($myrow=DB_fetch_array($result))
      {
               $j++;
               $orderno_stockid=$myrow['orderno'].",".$myrow['stkcode'];   
          
                      if ($myrow['despatchclearence']==1)
                      {
                        echo '<tr bgcolor="#FF6666">';
                                     $check="<input type=hidden value=%s>%s - %s";   
                      }else 
                      {
                        echo '<tr class="OddTableRows">';  
                         $check="<a href=ConfirmDispatch_Invoice.php?OrderNumber=%s>%s - %s</a>";
                      }

                $sql_qty="SELECT locstock.quantity FROM locstock WHERE locstock.stockid='".$myrow['stkcode']."' 
                                                                   AND locstock.loccode=(SELECT defaultlocation FROM custbranch WHERE debtorno='".$myrow['debtorno']."')";      
                $result_qty=DB_query($sql_qty,$db);
                $myrow_qty=DB_fetch_array($result_qty);      
                      
                $sql_sumqty="SELECT quantity,qtyinvoiced,SUM(quantity-qtyinvoiced) AS sumqty 
                                           FROM   salesorderdetails 
                                           WHERE  stkcode='".$myrow['stkcode']."' 
                                           AND    salesorderdetails.completed=0
                                            ";                                      // AND    salesorderdetails.actualdispatchdate='0000-00-00 00:00:00'
                $result_sumqty=DB_query($sql_sumqty,$db);
                $myrow_sumqty=DB_fetch_array($result_sumqty);
                
                
//                echo "<tr bgcolor='#FF6666'><a href=ConfirmDispatch_Invoice.php?OrderNumber=".$myrow['orderno'].">
//                      <td >".$myrow['orderno']." - ".ConvertSQLDate($myrow['orddate'])."</td>
//                      <td >".$myrow['name']."</td>
//                      <td >".$myrow['district']."</td> 
//                      <td >".$myrow['description']."</td> 
//                      <td >".$myrow['quantity']."</td> 
//                      <td >".$myrow_qty['quantity']."</td> 
//                      <td >".$myrow_sumqty['sumqty']."</td>  
//                      </a></tr>     
//                ";
             
                                                   
             printf("   <td>$check</td> 
                        <td >%s</td> 
                        <td >%s</td>
                        <td >%s</td> 
                        <td >%s</td> 
                        <td >%s</td>
                        <td >%s</td>                                                       
                        </tr>",                                                                                                                                                                                                                                                   
                        $myrow['orderno'],
                        $myrow['orderno'],
                        ConvertSQLDate($myrow['orddate']), 
                        $myrow['name'], 
                        $myrow['district'],
                        $myrow['description'],
                        $myrow['quantity'],
                        $myrow_qty['quantity'],
                        $myrow_sumqty['sumqty']);      
                                              
      }
      
      echo"<input type=hidden name=no id=no value=$j>";
      
      echo"</table></div>";
      
      echo"</td></tr>";
//      echo"<tr><td align=right><input type=submit name=submit value='Store Delivery';></td></tr>";
      
      echo"</table>";
      echo"</form>";
      
?>
