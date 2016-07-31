<?php
$PageSecurity = 80;  
include('includes/session.inc');  


echo "<table style='align:left;width:85%' border=0>";
if ($_GET['output']==1){

     
echo "<tr><td style='width:50%'>Output Devices:</td>";
echo "<td><select id='outputtype1' name='Outputtype1' style='width:190px'>";
echo'<option value=""></option>';
echo'<option value="1">Burner1</option>';
echo'<option value="1">Burner2</option>';
echo'<option value="1">Burner3</option>';
echo'<option value="1">Burner4</option>';
echo'<option value="1">Burner5</option>';
echo"</select></td></tr>";
}

else if($_GET['output']==2){
echo "<tr><td style='width:50%'>Output Type:</td>";
echo "<td><select id='outputtype2' name='Outputtype2' style='width:190px'>";
echo'<option value=""></option>';
echo'<option value="1">Generator1</option>';
echo'<option value="1">Generator2</option>';
echo'<option value="1">Generator3</option>';
echo'<option value="1">Generator4</option>';
echo'<option value="1">Generator5</option>';
echo"</select></td></tr>"; 


echo"<tr></tr>";
echo"<tr></tr>";

echo "<tr><td>Proposed&nbspquantity&nbspof&nbsppower&nbspto&nbspbe&nbspgenerated&nbsp(KW)</td>"; 
echo "<td><input type='text' value='".$_POST['Powergenerated']."' name='Powergenerated' id='powergenerated' style='width:150px'></td></tr>";

echo "<tr><td>Proposed&nbsptime&nbspfor&nbsppower&nbspgeneration(24 Hrs or&nbspMorning</td>"; 
echo "<td><input type='text' value='".$_POST['Powergenmorning']."' name='Powergenmorning' id='powergenmorning' style='width:50px'> Hrs, Evening </td>";
echo "<td><input type='text' value='".$_POST['Powergenevening']."' name='Powergenevening' id='powergenevening' style='width:50px'> Hrs)</td></tr>";

echo "<tr><td>Whether&nbspthere&nbspis&nbspany existing generator</td>"; 
echo "<td>Yes</td>";
echo "<td><input type='radio' $genexist1 name='Genexist' id='yes' value='1'></td>";             
echo "<td>No</td>";
echo "<td><input type='radio' $genexist2 name='Genexist' id='no' value='2'></td>";  
echo "</tr>";

echo "<tr><td>Capacity</td>"; 
echo "<td><input type='text' value='".$_POST['Capacity']."' name='Capacity' id='capacity' style='width:50px'> Kw</td></tr>";

echo "<tr><td>Operation&nbspfuel&nbspof&nbspthe existing generator</td>"; 
echo "<td><td>Diesel</td>";
echo "<td><input type='radio' $diesel name='fuel' id='diesel' value='1'></td>";             
echo "<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspLPG</td>";
echo "<td><input type='radio' $lpg name='fuel' id='lpg' value='2'></td>";  
echo "<td>CNG</td>";
echo "<td><input type='radio' $cng name='fuel' id='cng' value='3'></td>";
echo "</tr>";

echo "<tr><td>Proposed&nbspuse&nbspof&nbspelectricity</td>"; 
echo "<td><td>Street&nbsplighting</td>";
echo "<td><input type='radio' $useofelectricity1 name='useofelectricity' id='useofelectricity' value='1'></td>";             
echo "<td>All&nbspelectrical&nbsppurpose</td>";
echo "<td><input type='radio' $useofelectricity2 name='useofelectricity' id='useofelectricity' value='2'></td>";  
echo "</tr>";   
    
    
}

 echo "</table>";
  
?>

