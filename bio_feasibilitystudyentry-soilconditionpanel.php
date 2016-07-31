<?php
$PageSecurity = 80;
include('includes/session.inc');

$leadid=$_GET['leadid'];

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_soil
       WHERE leadid=$leadid";
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9);   

$soilnature1='';
$soilnature2=''; 
$soilnature3=''; 
$soilnature4=''; 

$wateravailability1='';
$wateravailability2='';
$wateravailability3='';

$watersource1='';
$watersource2=''; 
$watersource3=''; 
$watersource4=''; 


if($myrow9[0] > 0)      {
    
    
$sql="SELECT * 
      FROM bio_fsentry_soil
      WHERE leadid=$leadid";
        
$result=DB_query($sql,$db); 

while($myrow=DB_fetch_array($result))       {
if($myrow['nature_soil']== 1) {
    
$soilnature1='checked="checked"'; 
    
}elseif($myrow['nature_soil']== 2) {
    
$soilnature2='checked="checked"'; 
    
}elseif($myrow['nature_soil']== 3) {
    
$soilnature3='checked="checked"'; 
    
}elseif($myrow['nature_soil']== 4) {
    
$soilnature4='checked="checked"'; 
    
}

if($myrow['aval_water']== 1) {
    
$wateravailability1='checked="checked"'; 
    
}elseif($myrow['aval_water']== 2) {
    
$wateravailability2='checked="checked"'; 
    
}elseif($myrow['aval_water']== 3) {
    
$wateravailability3='checked="checked"'; 
    
}

$watersource=explode(',',$myrow['water_source']); 

}

for($i=1;$i<=sizeof($watersource);$i++)     { 

if($watersource[$i]==1)        {
    
$watersource1='checked="checked"';     
    
} 
if($watersource[$i]==2)        {
    
$watersource2='checked="checked"';     
    
} 
if($watersource[$i]==3)        {
    
$watersource3='checked="checked"';     
    
} 
if($watersource[$i]==4)        {
    
$watersource4='checked="checked"';     
    
}    
    
    
}
    
}

echo "<fieldset style='width:825px'>";   
echo "<legend><h3>Soil Condition</h3>";
echo "</legend>"; 



echo"<div id='soilcondition'>"; 
echo "<table style='align:left;width:85%' border=0>";  
echo "<tr><td>Nature Of Soil:</td>"; 
echo "<td align=right>Rocky</td>";
echo "<td><input type='radio' $soilnature1 name='SoilNature' id='soilnature' value='1'></td>";             
echo "<td align=right>Hard</td>";
echo "<td><input type='radio' $soilnature2 name='SoilNature' id='soilnature' value='2'></td>";  
echo "<td align=right>Loose</td>";
echo "<td><input type='radio' $soilnature3 name='SoilNature' id='soilnature' value='3'></td>"; 
echo "<td align=right>Marshy</td>";
echo "<td><input type='radio' $soilnature4 name='SoilNature' id='soilnature' value='4'></td>";            
echo "</tr>";

echo"<tr></tr>" ;
echo"<tr></tr>" ;
/*echo "<tr><td>Water Level Metre</td>";        
echo "<td><input type='text' name='WaterLevel' id='waterlevel' style='width:150px'></td>"; 
echo "</tr>"; */

echo "<tr><td>Availability Of Water:</td>";  
echo "<td align=right>Easily</td>";
echo "<td><input type='radio' $wateravailability1 name='WaterAvailability' id='wateravailability' value='1'></td>";             
echo "<td align=right>Not easily</td>";
echo "<td><input type='radio' $wateravailability2 name='WaterAvailability' id='wateravailability' value='2'></td>";  
echo "<td align=right>Shortage</td>";
echo "<td><input type='radio' $wateravailability3 name='WaterAvailability' id='wateravailability' value='3'></td>";           
echo "</tr>";

echo"<tr></tr>" ;
echo"<tr></tr>" ;
 
echo "<tr><td>Present Water Source:</td>";  
echo "<td align=right>Pipe</td>";
echo "<td><input type='checkbox' $watersource1 name='WaterSource' id='watersource' value='1'></td>";             
echo "<td align=right>Bore well</td>";
echo "<td><input type='checkbox' $watersource2  name='WaterSource' id='watersource' value='2'></td>";  
echo "<td align=right>Pond</td>";
echo "<td><input type='checkbox' $watersource3 name='WaterSource' id='watersource' value='3'></td>"; 
echo "<td align=right>Water Supply</td>";
echo "<td><input type='checkbox' $watersource4 name='WaterSource' id='watersource' value='4'></td>";            
echo "</tr>";

//echo "<tr><td>Distance between Gas Utilisation Point And Plant Site in Metre</td>";        
//echo "<td><input type='text' name='Distance' id='distance' style='width:150px'></td>"; 
//echo "</tr>";

echo "</table>";

echo"<div align=center>";
echo"<input type='button' value='Submit' onclick='soilconditionsubmit()'>"; 
echo"</div>";

echo "</div>";  

 
echo"</fieldset>";  
?>
