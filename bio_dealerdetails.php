<?php
$PageSecurity = 80; 
include('includes/session.inc'); 

     if($_GET['dealerdebtor']!=""){
     
    $sql="SELECT  bio_dealer_aof.areaofop,
                  areas.areadescription,
                  locations.locationname   
            FROM  bio_dealerterritory,bio_businessassociates_enq,bio_dealer_aof,areas,locations 
           WHERE  bio_dealerterritory.debtorno='".$_GET['dealerdebtor']."'
             AND  bio_businessassociates_enq.buss_id=bio_dealerterritory.buss_id
             AND  bio_dealer_aof.id=bio_businessassociates_enq.areaofop
             AND  areas.areacode=bio_dealerterritory.areacode
             AND  locations.loccode=bio_dealerterritory.locationcode";
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_array($result);
    
//    echo'<br />';
//    echo'<table>';
//    echo'<tr><td>Area of Operation</td><td><input type="text" name="aof" id="aof" align="left" value="'.$myrow['areaofop'].'"  readonly></td></tr>';
//    echo'<tr><td>Area    </td><td><input type="text" name="area" id="area" align="left" value="'.$myrow['areadescription'].'"  readonly></td></tr>';  
//    echo'<tr><td>Location</td><td><input type="text" name="area" id="area" align="left" value="'.$myrow['areadescription'].'"  readonly></td></tr>'; 
//    echo'</table>';
    echo'<br />';  
    
    
    $sql_order="SELECT orderno,orddate FROM salesorders WHERE debtorno='".$_GET['dealerdebtor']."'";
    $result_order=DB_query($sql_order,$db);
    
    echo '<tr><td>Dealer Orders</td><td> <select name="order" id="order" onchange=selectplant(this.value)>'; 
    echo '<option value=""></option>';
    while($myrow_order=DB_fetch_array($result_order))
    {
      echo "<option value=".$myrow_order['orderno'].">".$myrow_order['orderno']." - ".ConvertSQLDate($myrow_order['orddate'])."</option>";  
    }           

    echo'<br />';    
    
}            


     if($_GET['dealersorder']!=""){ 
      
    echo"<br />";
    echo"<table  style='width:100%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Plant</td><td>Total Ordered</td><td>Remaining</td></tr>";  
         
     $sql_plant="SELECT stockmaster.description,salesorderdetails.stkcode,salesorderdetails.quantity,salesorderdetails.orderlineno 
                   FROM salesorderdetails,stockmaster 
                  WHERE stockmaster.stockid=salesorderdetails.stkcode
                    AND salesorderdetails.orderno=".$_GET['dealersorder']."";  
     $result_plant=DB_query($sql_plant,$db);
     $slno=0;
     while($myrow_plant=DB_fetch_array($result_plant)) 
     {
        $orderno=$_GET['dealersorder'];
        $stkcode=$myrow_plant['stkcode']; 
        $orderline=$myrow_plant['orderlineno'];
        $quantity=$myrow_plant['quantity'];  

        $sql_remaining="SELECT lineno,SUM(quantity) AS qty FROM bio_childcustomerdetails WHERE orderno=$orderno";
        $result_remaining=DB_query($sql_remaining,$db);
        $myrow_remaining=DB_fetch_array($result_remaining);
        
        $lineno=$myrow_remaining['lineno'];
        $sumqty=$myrow_remaining['qty']; 
               
        if($orderline==$lineno){
           $rem=$quantity-$sumqty; 
        }else{
           $rem=$quantity; 
        }
        
        
         
        echo "<tr style='background:#000080;color:white'><td>".++$slno."
                                                         <input type=hidden name=qtyedit id=qtyedit value=".$myrow_plant['orderlineno']."></td>
                                                         <td>".$myrow_plant['description']."</td>
                                                         <td>".$myrow_plant['quantity']."</td>
                                                         <td>".$rem."</td>
                                                         <td><input type=text size=3px name=qtyedit id=qtyedit value='0' onchange=selectedqty(this.value,$orderline,$orderno,'$stkcode')></td>
                                                  </tr>"; 
     }

    echo'</table>'; 
        echo "<br /><br /><tr><td colspan=5; align='right'><input type='button' name='showhide1' id='showhide1' value='Create Child Customer' onclick=showhide();></td></tr>";       
}   



if($_GET['selectedqty']!=""){ 
    
     $sql_create=" CREATE TABLE IF NOT EXISTS temp_dealerqty (orderno INT,orderline INT,qty INT,stockid varchar(10))";
     DB_query($sql_create,$db);
     
     $sql_insert="INSERT INTO temp_dealerqty VALUES(".$_GET['orderno'].",".$_GET['orderline'].",".$_GET['selectedqty'].",'".$_GET['stockid']."')";
     DB_query($sql_insert,$db);  
}

        
        
if($_GET['did']!="")
{
    

       $sql_dealers=" SELECT debtorsmaster.debtorno,debtorsmaster.name
                      FROM   debtorsmaster
                      WHERE   did=".$_GET['did']."";                                          

//                    cid=".$_GET['cid']." AND stateid=".$_GET['sid']." AND
//                      AND    LSG_type=".$_GET['lsg']." AND LSG_name=".$_GET['nam']."";
//if(isset($_GET['blc'])){
//      $sql_dealers.=" AND    block_name=".$_GET['blc']."";
//      }            
      $sql_dealers.=" AND    debtorno LIKE 'DL%'";
                   
  $result_dealers=DB_query($sql_dealers,$db);
   
  echo '<td>Dealer</td><td> <select name="dealer" id="dealer" style="width:200px" onchange=dealerdetails(this.value)>';
//  echo '<option value="">Select Dealer</option>';
   $f=0;
  while($myrow_dealers=DB_fetch_array($result_dealers))
  {         
  if ($myrow_dealers['debtorno']==$dealercode) 
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
    echo $myrow_dealers['debtorno'] . '">'.$myrow_dealers['debtorno']." - ".$myrow_dealers['name'];
    echo '</option>';
    $f++;
   }
  echo '</select>'; 
   
  echo "</td>"; 
    
}          

?>
