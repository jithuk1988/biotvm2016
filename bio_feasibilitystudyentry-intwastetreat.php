<?php
$PageSecurity = 80;
include('includes/session.inc');

$leadid=$_GET['leadid'];

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_intwaste
       WHERE leadid=$leadid";
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9);   

$natureofwaste1='';
$natureofwaste2=''; 

if($myrow9[0] > 0)      {
    
    
$sql="SELECT * 
      FROM bio_fsentry_soil
      WHERE leadid=$leadid";  
$result=DB_query($sql,$db); 

while($myrow=DB_fetch_array($result))       {
    
if($myrow['supply_waste']== 1) {
    
$natureofwaste1='checked="checked"'; 
    
}elseif($myrow['supply_waste']== 2) {
    
$natureofwaste2='checked="checked"'; 
    
}

$_POST['Wastequn']=$myrow['quantity_waste'];
$_POST['Waterown']=$myrow['source_own'];
$_POST['Waterout']=$myrow['source_outside'];
$_POST['Easilydegradable']=$myrow['easily_deg'];
$_POST['Slowlydegradable']=$myrow['slowly_deg'];
$_POST['Veryslowlydegradable']=$myrow['vslowly_deg'];
$_POST['Roadsweeping']=$myrow['waste_road'];
$_POST['Market']=$myrow['waste_market'];

 
}

    
}

echo "<fieldset style='width:825px'>";   
echo "<legend><h3>For Integrated Waste Treatment Projects</h3>";
echo "</legend>"; 

  
echo"<div id='integratedwastetreatment'>";
  

echo "<table style='align:left' border=0>";  

echo "<tr><td>Total quantity of waste available</td>"; 
echo "<td><input type='text' value='".$_POST['Wastequn']."' name='Wastequn' id='wastequn' style='width:150px'></td></tr>";

echo "<tr><td>Source of waste: From own premises</td>"; 
echo "<td><input type='text' value='".$_POST['Waterown']."' name='Waterown' id='waterown' style='width:50px'> Kg,Procuring from outside</td>";
echo "<td><input type='text' value='".$_POST['Waterout']."' name='Waterout' id='waterout' style='width:50px'> Kg</td></tr>";

echo "<tr><td>Type and quantity of waste:</td></tr>";
echo "<tr><td>Easily degradable - Fish,meat,cooked food</td>";   
echo "<td><input type='text' value='".$_POST['Easilydegradable']."' name='Easilydegradable' id='easilydegradable' style='width:150px'> Kg</td></tr>";
echo "<tr><td>Slowly degradable - Fish,meat,cooked food</td>";   
echo "<td><input type='text' value='".$_POST['Slowlydegradable']."' name='Slowlydegradable' id='slowlydegradable' style='width:150px'> Kg</td></tr>";
echo "<tr><td>Very slowly degradable - Fish,meat,cooked food</td>";   
echo "<td><input type='text' value='".$_POST['Veryslowlydegradable']."' name='Veryslowlydegradable' id='veryslowlydegradable' style='width:150px'> Kg</td></tr>";

echo "<tr><td>Waste generated through road sweeping</td>";   
echo "<td><input type='text' value='".$_POST['Roadsweeping']."' name='Roadsweeping' id='roadsweeping' style='width:150px'> Kg</td></tr>";
echo "<tr><td>Waste generated through markets/slaughter house</td>";   
echo "<td><input type='text' value='".$_POST['Market']."' name='Market' id='market' style='width:150px'> Kg</td></tr>";

echo "<tr><td>Nature of waste:</td>"; 
echo "<td>Mixed";
echo "<input type='radio' $natureofwaste1 name='Natureofwaste' id='natureofwaste' value='1'></td>";             
echo "<td>Segregated</td>";
echo "<td><input type='radio' $natureofwaste2 name='Natureofwaste' id='natureofwaste' value='2'></td>";  
echo "</tr>";


echo "</table>";  

echo"<div align=center>";
echo"<input type='button' value='Submit' onclick='intwastetreatsubmit()'>"; 
echo"</div>";

echo "</div>"; 
echo"</fieldset>";   
?>
