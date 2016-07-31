<?php
 $PageSecurity = 80;  
include('includes/session.inc'); 


 
 if(isset($_GET['mod'])){ 
     
$sql1="SELECT * FROM bio_paymentmodes";
  $result1=DB_query($sql1, $db); 
echo'<tr><td>';
echo'Mode of payment:</td>';
echo'<td><select name="mode" id="mode" onchange="advdetail(this.value)">';

  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['modes']) 
    {
    echo '<option selected value="';
    
    } else 
    {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['id'] . '">'.$myrow1['modes'];  
    echo '</option>';
  }
echo'</select></td></tr>';     
     
 }
 
 
  if(isset($_GET['adv'])){ 
      if($_GET['adv']==3){
 echo'<tr><td style="width:50%">DD date</td><td><input type="text" tabindex=20 name="amtdate" id="amtdate"></td></tr>';    
  echo'<tr><td>DD no:</td><td><input type="text" name="amtno" tabindex=21 id="amtno"></td></tr>';  
   echo'<tr><td>Bank name:</td><td><input type="text" tabindex=22 name="amtbank" id="amtbank"></td></tr>';  
   }else if($_GET['adv']==2){
       
  echo'<tr><td style="width:50%">Cheque Date</td><td><input type="text" tabindex=20 name="amtdate" id="amtdate"></td></tr>';    
  echo'<tr><td>Cheque no:</td><td><input type="text" name="amtno" id="amtno" tabindex=21></td></tr>';  
   echo'<tr><td>Bank name:</td><td><input type="text" name="amtbank" id="amtbank" tabindex=22></td></tr>';       
       
   }
     
 }
 

?>

