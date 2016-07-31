<?php
$PageSecurity = 80;  
include('includes/session.inc');  


if($_GET['dom']==1){
    
    echo "<tr><td style='width:45%'>Customer Name*</td>";   
    echo "<td style='width:55%'><input type='text' name='custname' id='custid' tabindex=2 style='width:160px;text-transform:capitalize;' onchange=duplicatename()></td>";
    echo "<tr><td style='width:45%'>Father's/ Husbands Name</td>";   
    echo "<td style='width:55%'><input type='text' name='careof' id='careof' tabindex=2 onkeyup='capitaliseFirstLetter(this.value,1)'  style='width:160px'></td>";
                                                   //  careof
        echo "<tr><td style='width:45%'>House No:</td><td style='width:55%'><input type='text' tabindex=3 name='Houseno' id='Houseno' onkeyup='' style='width:160px'></td></tr>";    
        echo "<tr><td style='width:45%'>House Name:</td><td style='width:55%'><input type='text' tabindex=4 name='HouseName' id='HouseName' onkeyup='capitaliseFirstLetter(this.value,2)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Residencial Area:</td><td style='width:55%'><input type='text' tabindex=5 name='Area1' id='Area1' onkeyup='capitaliseFirstLetter(this.value,3)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Post Office:</td><td style='width:55%'><input type='text' name='Area2' tabindex=6 id='Area2' onkeyup='capitaliseFirstLetter(this.value,4)' style='width:160px'></td></tr>";
        echo" <tr><td style='width:45%'>Pin:</td><td style='width:55%'><input type='text' name='Pin' id='Pin' tabindex=7 onkeyup='' style='width:160px'></td></tr>"; 
} 
        
else if($_GET['dom']==2){
            
    echo "<tr><td style='width:45%'>Org Name:*</td>";
    echo "<td style='width:55%'><input type='text' name='custname' id='custid'  tabindex=2  style='width:160px;text-transform:capitalize;' onchange=duplicatename()></td>";
    echo "<tr><td style='width:45%'>Contact Person:*</td><td style='width:55%'><input type='text' name='contactPerson' tabindex=3 id='contactPerson' onkeyup='capitaliseFirstLetter(this.value,5)' style='width:160px' ></td></tr>";  
   
        echo "<tr><td style='width:45%'>Building Name/No:</td><td style='width:55%'><input type='text' tabindex=4 name='Houseno' id='Houseno' onkeyup='capitaliseFirstLetter(this.value,6)' style='width:160px'></td></tr>";    
        echo "<tr><td style='width:45%'>Org Street:</td><td style='width:55%'><input type='text' name='HouseName' tabindex=5 id='HouseName' onkeyup='capitaliseFirstLetter(this.value,2)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Org Area:</td><td style='width:55%'><input type='text' name='Area1' tabindex=6 id='Area1' onkeyup='capitaliseFirstLetter(this.value,3)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Post Office:</td><td style='width:55%'><input type='text' name='Area2' tabindex=7 id='Area2' onkeyup='capitaliseFirstLetter(this.value,4)' style='width:160px'></td></tr>";
        echo" <tr><td style='width:45%'>Pin:</td><td style='width:55%'><input type='text' name='Pin' id='Pin' tabindex=8 onkeyup='' style='width:160px'></td></tr>";   
        }
        
else if($_GET['dom']==3){
   
   echo "<tr><td>Project Name*</td><td style='width:55%'><input type='text' name='Project' id='project' onkeyup='' style='width:160px'></td></tr>";             
   echo "<tr><td style='width:45%'>LSGD Name:*</td>";
   echo "<td style='width:55%'><input type='text' name='custname' id='custid' onkeyup='caps1()' tabindex=2  style='width:160px' onchange=duplicatename()></td>";
   
   echo "<tr><td>Contact Person:*</td><td style='width:55%'><input type='text' name='contactPerson' tabindex=3 id='contactPerson' onkeyup='' style='width:160px' ></td></tr>";  
                
        echo "<tr><td style='width:45%'>Building Name:/No:</td><td style='width:55%'><input type='text' tabindex=4 name='Houseno' id='Houseno' onkeyup='' style='width:160px'></td></tr>";    
        echo "<tr><td style='width:45%'>LSGD Street:</td><td style='width:55%'><input type='text' name='HouseName' tabindex=5 id='HouseName' onkeyup='' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>LSGD Area:</td><td style='width:55%'><input type='text' name='Area1' tabindex=6 id='Area1' onkeyup='' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Post Office:</td><td style='width:55%'><input type='text' name='Area2' tabindex=7 id='Area2' onkeyup='' style='width:160px'></td></tr>";
        echo" <tr><td style='width:45%'>Pin:</td><td style='width:55%'><input type='text' name='Pin' id='Pin' tabindex=8 onkeyup='' style='width:160px'></td></tr>";   
        
        }
        
else if($_GET['dom']==4){
    echo "<tr><td>Project Name*</td><td><input type='text' name='Project' id='project' onkeyup='' style='width:99%'></td></tr>"; 
 
    echo "<tr><td style='width:50%'>LSGD Name*</td>";
    echo "<td><input type='text' name='custname' id='custid' onkeyup='caps1()'  style='width:190px' onchange=duplicatename()></td>";
        echo "<tr><td>Contact Person:*</td><td><input type='text' name='contactPerson'  id='contactPerson' onkeyup='' style='width:99%' ></td></tr>";  

        echo "<tr><td>Building Name/No:</td><td><input type='text' tabindex=4 name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";   
         echo "<tr><td>LSGD Street:</td><td><input type='text' name='HouseName' tabindex=5 id='HouseName' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>LSGD Area:</td><td><input type='text' name='Area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Post Office:</td><td><input type='text' name='Area2' id='Area2' onkeyup='' style='width:99%'></td></tr>";
        echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' onkeyup='' style='width:99%'></td></tr>";   
        }
        
        
else if($_GET['dom']==5){
    
    $sql1="SELECT * FROM bio_lsgdproject";
    $result1=DB_query($sql1,$db);
    echo '<tr><td>Project Name:*</td>';
    echo'<td><select name="Parent" id="project" tabindex=2 style="width:190px">';
    
     $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['leadid']==$_POST['project'])  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['leadid'] . '">'.$myrow1['project_name'];
    echo '</option>';
    $f++;
   } 
echo'</select></td></tr>'; 
    
    
    echo "<tr><td style='width:50%'>Customer Name*</td>";   
    echo "<td><input type='text' name='custname' id='custid' tabindex=3 onkeyup='caps1()'  style='width:190px' onchange=duplicatename()></td>";
    echo "<tr><td style='width:50%'>Father's/ Husbands Name</td>";   
    echo "<td><input type='text' name='careof' id='careof' tabindex=3 onkeyup='caps1()'  style='width:190px'></td>";
    
        echo "<tr><td>House No:</td><td><input type='text' tabindex=3 name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>House Name:</td><td><input type='text' tabindex=4 name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Residencial Area:</td><td><input type='text' tabindex=5 name='Area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Post Office:</td><td><input type='text' name='Area2' tabindex=6 id='Area2' onkeyup='' style='width:99%'></td></tr>";
        echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' tabindex=7 onkeyup='' style='width:99%'></td></tr>"; 
} 

else if($_GET['dom']==6){
    
    echo "<tr><td style='width:50%'>Project Name*</td>"; 
    echo "<td><input type='text' name='Project' id='project' onkeyup='' style='width:99%'></td></tr>"; 
    echo "<tr><td style='width:50%'>Customer Name*</td>";   
    echo "<td><input type='text' name='custname' id='custid' tabindex=2 onkeyup='caps1()'  style='width:190px' onchange=duplicatename()></td>";
    echo "<tr><td style='width:50%'>Father's/ Husbands Name</td>";   
    echo "<td><input type='text' name='careof' id='careof' tabindex=2 onkeyup='caps1()'  style='width:190px'></td>";
    
        echo "<tr><td>House No:</td><td><input type='text' tabindex=3 name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>House Name:</td><td><input type='text' tabindex=4 name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Residencial Area:</td><td><input type='text' tabindex=5 name='Area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Post Office:</td><td><input type='text' name='Area2' tabindex=6 id='Area2' onkeyup='' style='width:99%'></td></tr>";
        echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' tabindex=7 onkeyup='' style='width:99%'></td></tr>"; 
}
else if($_GET['dom']==8) 
{
     echo "<tr><td style='width:45%'>Dealer Name:*</td>";
    echo "<td style='width:55%'><input type='text' name='custname' id='custid'  tabindex=2  style='width:160px;text-transform:capitalize;' onchange=duplicatename()></td>";
    echo "<tr><td style='width:45%'>Contact Person:*</td><td style='width:55%'><input type='text' name='contactPerson' tabindex=3 id='contactPerson' onkeyup='capitaliseFirstLetter(this.value,5)' style='width:160px' ></td></tr>";  
   
        echo "<tr><td style='width:45%'>Building Name/No:</td><td style='width:55%'><input type='text' tabindex=4 name='Houseno' id='Houseno' onkeyup='capitaliseFirstLetter(this.value,6)' style='width:160px'></td></tr>";    
        echo "<tr><td style='width:45%'>Dealer Street:</td><td style='width:55%'><input type='text' name='HouseName' tabindex=5 id='HouseName' onkeyup='capitaliseFirstLetter(this.value,2)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Dealer Area:</td><td style='width:55%'><input type='text' name='Area1' tabindex=6 id='Area1' onkeyup='capitaliseFirstLetter(this.value,3)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Post Office:</td><td style='width:55%'><input type='text' name='Area2' tabindex=7 id='Area2' onkeyup='capitaliseFirstLetter(this.value,4)' style='width:160px'></td></tr>";
        echo" <tr><td style='width:45%'>Pin:</td><td style='width:55%'><input type='text' name='Pin' id='Pin' tabindex=8 onkeyup='' style='width:160px'></td></tr>";   
}
else if($_GET['dom']==7) 
{
     echo "<tr><td style='width:45%'>Joint Venture Name:*</td>";
    echo "<td style='width:55%'><input type='text' name='custname' id='custid'  tabindex=2  style='width:160px;text-transform:capitalize;' onchange=duplicatename()></td>";
    echo "<tr><td style='width:45%'>Contact Person:*</td><td style='width:55%'><input type='text' name='contactPerson' tabindex=3 id='contactPerson' onkeyup='capitaliseFirstLetter(this.value,5)' style='width:160px' ></td></tr>";  
   
        echo "<tr><td style='width:45%'>Building Name/No:</td><td style='width:55%'><input type='text' tabindex=4 name='Houseno' id='Houseno' onkeyup='capitaliseFirstLetter(this.value,6)' style='width:160px'></td></tr>";    
        echo "<tr><td style='width:45%'>Joint Venture Street:</td><td style='width:55%'><input type='text' name='HouseName' tabindex=5 id='HouseName' onkeyup='capitaliseFirstLetter(this.value,2)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Joint Venture Area:</td><td style='width:55%'><input type='text' name='Area1' tabindex=6 id='Area1' onkeyup='capitaliseFirstLetter(this.value,3)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Post Office:</td><td style='width:55%'><input type='text' name='Area2' tabindex=7 id='Area2' onkeyup='capitaliseFirstLetter(this.value,4)' style='width:160px'></td></tr>";
        echo" <tr><td style='width:45%'>Pin:</td><td style='width:55%'><input type='text' name='Pin' id='Pin' tabindex=8 onkeyup='' style='width:160px'></td></tr>";   
}  
else if($_GET['dom']==11) 
{
     echo "<tr><td style='width:45%'> Name:*</td>";
    echo "<td style='width:55%'><input type='text' name='custname' id='custid'  tabindex=2  style='width:160px;text-transform:capitalize;' onchange=duplicatename()></td>";
    echo "<tr><td style='width:45%'>Contact Person:*</td><td style='width:55%'><input type='text' name='contactPerson' tabindex=3 id='contactPerson' onkeyup='capitaliseFirstLetter(this.value,5)' style='width:160px' ></td></tr>";  
   
        echo "<tr><td style='width:45%'>Building Name/No:</td><td style='width:55%'><input type='text' tabindex=4 name='Houseno' id='Houseno' onkeyup='capitaliseFirstLetter(this.value,6)' style='width:160px'></td></tr>";    
        echo "<tr><td style='width:45%'> Street:</td><td style='width:55%'><input type='text' name='HouseName' tabindex=5 id='HouseName' onkeyup='capitaliseFirstLetter(this.value,2)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'> Area:</td><td style='width:55%'><input type='text' name='Area1' tabindex=6 id='Area1' onkeyup='capitaliseFirstLetter(this.value,3)' style='width:160px'></td></tr>";
        echo "<tr><td style='width:45%'>Post Office:</td><td style='width:55%'><input type='text' name='Area2' tabindex=7 id='Area2' onkeyup='capitaliseFirstLetter(this.value,4)' style='width:160px'></td></tr>";
        echo" <tr><td style='width:45%'>Pin:</td><td style='width:55%'><input type='text' name='Pin' id='Pin' tabindex=8 onkeyup='' style='width:160px'></td></tr>";   
}      
         
?>
