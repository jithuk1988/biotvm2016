<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
if($_GET['dom']==1){
    echo "<tr><td style='width:50%'>Customer Name</td>";
    echo "<td><input type='text' name='custname' id='custid' onkeyup='caps1()'  style='width:190px'></td>";
    
        echo "<tr><td>House No:</td><td><input type='text' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>House Name:</td><td><input type='text' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Residencial Area:</td><td><input type='text' name='Area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Post Box:</td><td><input type='text' name='Area2' id='Area2' onkeyup='' style='width:99%'></td></tr>";
        echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' onkeyup='' style='width:99%'></td></tr>";  } 
        
else if($_GET['dom']==2){
            
             echo "<tr><td style='width:50%'>Contact Name</td>";
    echo "<td><input type='text' name='custname' id='custid' onkeyup='caps1()'  style='width:190px'></td>";
    
 //       echo "<tr><td>House No:</td><td><input type='text' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>Org Name:</td><td><input type='text' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Org Area:</td><td><input type='text' name='Area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Post Box:</td><td><input type='text' name='Area2' id='Area2' onkeyup='' style='width:99%'></td></tr>";
        echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' onkeyup='' style='width:99%'></td></tr>";   
        }
else if($_GET['dom']==3){
            
             echo "<tr><td style='width:50%'>LSGD Type:</td>";
    echo "<td><select id='lsgd' name='lsgd' onchange='showinstitute(this.value)' style='width:190px'>";
    echo'<option value=""></option>';
    echo'<option value="1">Domestic</option>';
    echo'<option value="2">Institute</option>';
    echo"</select></td></tr>";
    
  
        }
?>
