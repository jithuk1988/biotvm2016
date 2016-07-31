<?php
$PageSecurity = 80;
include('includes/session.inc');

$leadid=$_GET['leadid'];

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_powergen
       WHERE leadid=$leadid";
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9);   

$genexist1='';
$genexist2='';

$diesel='';
$lpg='';
$cng='';

$useofelectricity1='';
$useofelectricity2='';


if($myrow9[0] > 0)      {
    
    
$sql="SELECT * 
      FROM bio_fsentry_powergen
      WHERE leadid=$leadid";  
$result=DB_query($sql,$db); 

while($myrow=DB_fetch_array($result))       {
if($myrow['generator']== 1) {
    
$genexist1='checked="checked"'; 
    
}elseif($myrow['generator']== 2) {
    
$genexist2='checked="checked"'; 
    
}

if($myrow['fuel_generator']== 1) {
    
$diesel='checked="checked"'; 
    
}elseif($myrow['fuel_generator']== 2) {
    
$lpg='checked="checked"'; 
    
}elseif($myrow['fuel_generator']== 3) {
    
$cng='checked="checked"'; 
    
}


if($myrow['electricity']== 1) {
    
$useofelectricity1='checked="checked"'; 
    
}elseif($myrow['electricity']== 2) {
    
$useofelectricity2='checked="checked"'; 
    
}

$_POST['Powergenerated']=$myrow['quantity_power'];
$_POST['Powergenmorning']=$myrow['power_gen_morn']; 
$_POST['Powergenevening']=$myrow['power_gen_even']; 
$_POST['Capacity']=$myrow['gen_capacity']; 


}
    
}

echo "<fieldset style='width:825px'>";   
echo "<legend><h3>For Power Generation Projects</h3>";
echo "</legend>";


echo"<div id='powergeneration'>";  
  

echo "<table style='align:left' border=0>";  

echo "<tr><td>Proposed quantity of power to be generated(in KW)</td>"; 
echo "<td><input type='text' value='".$_POST['Powergenerated']."' name='Powergenerated' id='powergenerated' style='width:150px'></td></tr>";

echo "<tr><td>Proposed time for power generation(24 Hrs or Morning</td>"; 
echo "<td><input type='text' value='".$_POST['Powergenmorning']."' name='Powergenmorning' id='powergenmorning' style='width:50px'> Hrs, Evening </td>";
echo "<td><input type='text' value='".$_POST['Powergenevening']."' name='Powergenevening' id='powergenevening' style='width:50px'> Hrs)</td></tr>";

echo "<tr><td>Whether there is any existing generator</td>"; 
echo "<td>Yes</td>";
echo "<td><input type='radio' $genexist1 name='Genexist' id='yes' value='1'></td>";             
echo "<td>No</td>";
echo "<td><input type='radio' $genexist2 name='Genexist' id='no' value='2'></td>";  
echo "</tr>";

echo "<tr><td>Capacity</td>"; 
echo "<td><input type='text' value='".$_POST['Capacity']."' name='Capacity' id='capacity' style='width:50px'> Kw</td></tr>";

echo "<tr><td>Operation fuel of the existing generator</td>"; 
echo "<td><td>Diesel</td>";
echo "<td><input type='radio' $diesel name='fuel' id='diesel' value='1'></td>";             
echo "<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspLPG</td>";
echo "<td><input type='radio' $lpg name='fuel' id='lpg' value='2'></td>";  
echo "<td>CNG</td>";
echo "<td><input type='radio' $cng name='fuel' id='cng' value='3'></td>";
echo "</tr>";

echo "<tr><td>Proposed use of electricity</td>"; 
echo "<td><td>Street&nbsplighting</td>";
echo "<td><input type='radio' $useofelectricity1 name='useofelectricity' id='useofelectricity' value='1'></td>";             
echo "<td>All&nbspelectrical&nbsppurpose</td>";
echo "<td><input type='radio' $useofelectricity2 name='useofelectricity' id='useofelectricity' value='2'></td>";  
echo "</tr>";

echo "</table>"; 

echo"<div align=center>";
echo"<input type='button' value='Submit' onclick='powergenerationsubmit()'>"; 
echo"</div>";

echo "</div>";  
echo"</fieldset>";  
?>
