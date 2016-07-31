<?php
     $PageSecurity = 80;   
include('includes/session.inc');

$title = _('Despatch Clearence');

include('includes/header.inc');
if(isset($_POST['submit']))
{ 
     $_POST['schdate'];    
    //echo $_POST['']; 
    
    
                      
    $sql_getdis="SELECT max(despatch_id) as dis_id FROM bio_despatchClearence";
   $result_getdis=DB_query($sql_getdis,$db);
   $row_des=DB_fetch_array($result_getdis);    // echo   $row_des['dis_id'];
    $new_des=$row_des['dis_id']+1;  
    $count=0;          
   for($i=1;$i<=$_POST['no'];$i++)
   {               
                                        
           if($_POST['selection'.$i]=='on')
           {
                   
          $order_plant=$_POST['val'.$i]; 
          $split=explode(',',$order_plant);
          
          $orderno=$split[0];
          $plantid="'$split[1]'"; 
          
          //$schdate=$_POST['schdate'.$i];
          $location=$_POST['loc'.$i];
              
          
          foreach ($_POST['serialno'.$i] as $serial)   {
          $serialno[]=$serial;   // echo $_POST['serialno'.$i];
          
          } 
            
           if($serialno==NULL || $serialno==0)
           {
                $dcqty=$_POST['dcqty'.$i]; 
           }
           else{
                $dcqty=sizeof($serialno);
           }   
               $serialno=join(",", $serialno);
//        echo $sql_update="UPDATE salesorderdetails SET despatchclearence=1,sched_instlln_date='".FormatDateForSQL($schdate)."',serialno='$serialno' WHERE orderno=$orderno AND stkcode=$plantid"; 
//          DB_query($sql_update,$db);

          $sql_update="INSERT INTO bio_despatchClearence (orderno,despatch_id,stockid,dc_qty,serialno,sched_instlln_date) VALUES($orderno,$new_des,$plantid,$dcqty,'$serialno','".FormatDateForSQL($_POST['schdate'])."')"; 
          DB_query($sql_update,$db); 
          $sql_inst="INSERT INTO bio_installationstatus (orderno,despatch_id,stockcode,serialno) VALUES  ($orderno,$new_des,$plantid,'".$serial."')";
         DB_query($sql_inst,$db); 
           $count++;
             // echo  $sql_update;
          unset($orderno); unset($plantid); unset($dcqty); unset($serialno);
          }   
   }
    //  echo"<div class=success>".$count." Items Cleared</div>";  
           ?> 
  <script type="text/javascript">
                  alert('Succesful');
  window.opener.location='bio_PendingdespatchClearence.php';

  
//  window.close();
  </script>
  <?php
       
    $order=$_POST['order'];
}
 if($_GET['order']){  $order=$_GET['order'];}
 else  { $order=$_POST['order'];   }
 

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Despatch Clearence') . '</p>';
   echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
   
     echo "<input type='hidden' name='order' id='order' value='".$order."'>";  
 
 $sql="SELECT salesorderdetails.orderno,
             salesorders.orddate, 
             salesorders.debtorno,
             salesorders.fromstkloc,   
             locations.locationname,
             debtorsmaster.name,
             debtorsmaster.did,
             bio_district.district,
             salesorderdetails.stkcode,
             stockmaster.description,
             salesorderdetails.quantity
      FROM   salesorderdetails,salesorders,debtorsmaster,bio_district,stockmaster,locations 
      WHERE  salesorders.orderno=salesorderdetails.orderno
      AND    debtorsmaster.debtorno=salesorders.debtorno
      AND    stockmaster.stockid=salesorderdetails.stkcode
      AND    locations.loccode=salesorders.fromstkloc 
      AND    bio_district.did=debtorsmaster.did AND bio_district.stateid=debtorsmaster.stateid AND bio_district.cid=debtorsmaster.cid
      AND    salesorderdetails.completed=0 
      AND    salesorderdetails.orderno=".$order; 
$result=DB_query($sql,$db);

echo "<div style='height:500px;  overflow:scroll;'>";
echo"<table>";
echo "<tr><td>Scheduled Delivery :</td><td><input type=text name='schdate' id='schdate' class=date alt=".$_SESSION['DefaultDateFormat']."></td><td></td>";
  echo "<td><input type=submit name=submit value='Send Despatch Clearence' onclick='if(validation()==1) return false;'></td></tr>"; 
  echo"</table></br>";
    
echo"<table width=600px>";            
echo "<tr><th width=125px>Order No & Date</th><th>Customer Name</th><th>District</th><th>Plant</th><th>Quantity</th><th>QOH</th><th>Select</th><th>Location</th><th>Serial No</th><th>Pending despatch</th><th>Despatch Qyt</th></tr>";
$j=0;
$k=0;
while($myrow=DB_fetch_array($result))
{            
    $j++;
                      if ($k==1)
                      {
                        echo '<tr class="EvenTableRows">';
                        $k=0;
                      }else 
                      {
                        echo '<tr class="OddTableRows">';
                        $k=1;     
                      }
                           
                $sql_qty="SELECT locstock.quantity FROM locstock WHERE locstock.stockid='".$myrow['stkcode']."' 
                                                                   AND locstock.loccode='".$myrow['fromstkloc']."'";      
                $result_qty=DB_query($sql_qty,$db);
                $myrow_qty=DB_fetch_array($result_qty);                               
                  
                      

            $sql_sumqty="SELECT SUM(salesorderdetails.quantity) as qty,
                                SUM(salesorderdetails.qtyinvoiced) AS qtyi,
                                (SUM(salesorderdetails.quantity)-SUM(salesorderdetails.qtyinvoiced)-SUM(bio_despatchClearence.dc_qty)) AS sumqty 
                                FROM salesorderdetails,salesorders,bio_despatchClearence 
                                WHERE stkcode='".$myrow['stkcode']."'
                                AND salesorderdetails.completed=0 
                                AND salesorders.orderno=salesorderdetails.orderno 
                                AND bio_despatchClearence.orderno=salesorderdetails.orderno 
                                AND salesorders.fromstkloc='".$myrow['fromstkloc']."'";                           
                $result_sumqty=DB_query($sql_sumqty,$db);
                $myrow_sumqty=DB_fetch_array($result_sumqty);
                
             $sql_notinvoiced="SELECT SUM(dc_qty) AS dcqty FROM bio_despatchClearence 
                                             WHERE  stockid='".$myrow['stkcode']."'
                                             ";               
              $result_notinvoiced=DB_query($sql_notinvoiced,$db);
              $row_notinvoiced=DB_fetch_array($result_notinvoiced);
              $count=$row_notinvoiced['dcqty'];
              

              $QOHcount=$myrow_qty['quantity']-$count;
              $PENcount=$myrow_sumqty['sumqty']-$count; 

                                                                   
             $orderno_stockid=$myrow['orderno'].",".$myrow['stkcode'];
 
    

                                     echo"<td width='50px'>" . $myrow['orderno']."-".ConvertSQLDate($myrow['orddate']) . "</td>"; 
                                    // echo"<td width='50px'><a style='cursor:pointer;' id=".$myrow['orderno']." onclick='viewdocs(this.id);'>" . $myrow['orderno']."-".ConvertSQLDate($myrow['orddate']) . "</a></td>";
                                  //   echo"<td cellpading=2>".$myrow['orderno']."-".ConvertSQLDate($myrow['orddate'])."</td>";
                                     echo"<td>".$myrow['name']."</td>";  
                                     echo"<td>".$myrow['district']."</td>";
                                     echo"<td>".$myrow['description']."</td>";  
                                     echo"<td><input type=hidden name='quantity".$j."' id='quantity".$j."' value=".$myrow['quantity'].">".$myrow['quantity']."</td>";   
                                     echo"<td>".$QOHcount."</td>"; 
                                     //echo"<td>".$PENcount."</td>"; 
                                     echo"<td align=center><input type=checkbox name='selection".$j."' id='selection".$j."' >
                                                           <input type=hidden name='val".$j."' id='val".$j."' value='$orderno_stockid'>
                                                           <input type=hidden name='loc".$j."' id='loc".$j."' value='".$myrow['fromstkloc']."'>  </td>";   
                                     echo'<td>'.$myrow[locationname].'</td>';
             
             
             
             $sql_remqty="SELECT SUM(dc_qty) AS remqty FROM bio_despatchClearence WHERE orderno=".$myrow['orderno']." AND stockid='".$myrow['stkcode']."'";
             $result_remqty=DB_query($sql_remqty,$db);
             $num_remqty=DB_num_rows($result_remqty);
             if($num_remqty!=0){
             $myrow_remqty=DB_fetch_array($result_remqty);
             $remqty= $myrow['quantity']-$myrow_remqty[0];
             }
                               
                                    
                                           
                                           
                  $sql_sernotin="SELECT serialno FROM bio_despatchClearence WHERE stockid='".$myrow['stkcode']."'";
                  $result_sernotin=DB_query($sql_sernotin,$db);
                  $count=DB_num_rows($result_sernotin); 
                  while($myrow_sernotin=DB_fetch_array($result_sernotin))
                  {
                      if($myrow_sernotin['serialno']!=NULL){
                               $serialno[]=$myrow_sernotin['serialno']; 
                      }
                     
                  }
                  //$serial[]=explode(",",$serialno); 
                  //print_r($serialno); 
                  if($serialno!=NULL) {
                     $serial=join(",", $serialno);   
                  }
                  $serial="'".$serial."'";
                          $serial=str_replace(",","','",$serial);
                                                    
                                 // echo $serial; 
                  $sql_serial="SELECT DISTINCT stockserialitems.serialno FROM stockserialitems 
                                WHERE stockserialitems.stockid='".$myrow['stkcode']."' 
                                  AND stockserialitems.loccode='".$myrow['fromstkloc']."'
                                  AND stockserialitems.quantity>0";
                  if($count!=0 && $serialno!=NULL){                   
                  $sql_serial.=" AND stockserialitems.serialno NOT IN ($serial)";
                  }     // echo  $sql_serial;
                       
                  $result_serial=DB_query($sql_serial,$db);                  // SELECT serialno FROM bio_despatchClearence WHERE stockid='".$myrow['stkcode']."'
                  $count=DB_num_rows($result_serial);         
                                      echo "<td id=seriallocation>";      
                                      echo "<select name='serialno".$j."[]' id='serialno".$j."' style='width:75px' multiple>";
                                      
                                      while ($myrow_serial=DB_fetch_array($result_serial)) {
                                          if($count==0)
                                          {  
                                            echo '<option value=0></option>';   
                                          }else{
                                            echo '<option value=' . $myrow_serial['serialno'] . '>' . $myrow_serial['serialno'] . '</option>';
                                          }
                                      }
                                      echo '</select>'; 
                                      
                                            
/*                                      echo"</td> ";
                                       if($count==0) { */
                                       
                                       echo"<td>".$remqty."</td>";  
                                       echo "<input type='hidden' name='dcqty1".$j."' id='dcqty1".$j."' value=".$remqty." >";
                                             echo"<td><input type=text name='dcqty".$j."' id='dcqty".$j."' value=".$remqty." size=5px></td></tr>";        
                          /*      }     */
                                  /*else{
                                            echo"<td><input type=text readonly name='dcqty".$j."' id='dcqty".$j."' value=".$remqty." size=5px></td></tr>";  
                                  } */ 
                                  
                                //  echo"<td ><input type=text name='schdate".$j."' id='schdate".$j."' class=date alt=".$_SESSION['DefaultDateFormat']."></td>  </tr> ";
          
          
              
     unset($serialno); 
} 
    echo"<input type=hidden name=no id=no value=$j>";
 
 echo"</table>";  
       
 echo"</div>";
 echo "</form>";
 
 
 
?>
<script>
function validation(){ 
    var f=0;  
 
if(f==0){f=common_error('schdate','Please select scheduled delivery date');  if(f==1){return f; }  }

   var no=document.getElementById("no").value;         
    var count1=0;
   //  alert(count);
     
    for( i=1; i<= no; i++)
    {
       var selection='selection'+i;  
                              
       if(document.getElementById(selection).checked==true)
       {       
               count1++;       
           /*var date='schdate'+i;           
           if(f==0){f=common_error(date,'Please Enter a date');  if(f==1) { return f; }}    */
           
           
           var serialno='serialno'+i;         //  alert(document.getElementById(serialno).value);  
           if(document.getElementById(serialno).options.length>0)  {
           if(f==0){f=common_error(serialno,'Please select a serial no');  if(f==1) { return f; }} 
           }
// To Get ALL Options from select
/*var x=document.getElementById('serialno'+i);            
var txt="All options: ";
var j;
for (j=0;j<x.length;j++)
  {
  txt=txt + "\n" + x.options[j].text;
  }
alert(txt);*/
                   var dcqty1='dcqty1'+i;
                          if(document.getElementById(dcqty1).value==0)
                          {
                             alert("All ordered quantity despatched for some selected item,"); 
                              return 1;  
                          }
                             
          // alert(selection.options[i].text);
           /*txt=selection.options[i].text; 
            alert(txt); */        
           
           
           
           
           var quantity='quantity'+i;      //alert quantity;
           var dcqty='dcqty'+i;   //alert(dcqty);               
           if(document.getElementById(dcqty).value > document.getElementById(quantity).value)
           {
               alert("Clearence quantity cannot be greater than Ordered quantity");
               return 1;
           }
 
           if(document.getElementById(serialno).value!=0)  {   
           var options = document.getElementById(serialno).options, count = 0;    
           for (var i=0; i < options.length; i++) {
           if (options[i].selected) count++;
           }  
           if(document.getElementById(dcqty).value != count) 
           {
               alert("Serial number count does not match ");
               return 1;
           }
           }
       }     
    }    
    
    
    
      if(count1==0) 
           {
               alert("Please select atleast one item");
               return 1;
           }
    
   // alert(count1);                                                                                              
     
}


</script>