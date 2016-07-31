<?php

$PageSecurity = 80;
include('includes/session.inc');

$leadid=$_GET['leadid'];

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_basic
       WHERE leadid=$leadid";
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9);   


$wastedisposal1='';
$wastedisposal2='';
$wastedisposal3='';
$wastedisposal4='';

$watersource1='';
$watersource2=''; 
$watersource3=''; 
$watersource4='';


if($myrow9[0] > 0)      {
    
    
$sql="SELECT * 
      FROM bio_fsentry_basic
      WHERE leadid=$leadid";  
$result=DB_query($sql,$db); 

while($myrow=DB_fetch_array($result))       {

$wastedisposal=explode(',',$myrow['disposal_system']);
$watersource=explode(',',$myrow['water_source']);

$_POST['WaterCollection']=$myrow['waste_collection'];
$_POST['WaterCollection']=$myrow['waste_collection'];
$_POST['Beneficiary']=$myrow['bin_collection'];
$_POST['Consumption']=$myrow['water_con'];
$_POST['Discharge']=$myrow['water_discharge']; 
$_POST['Lpg']=$myrow['lpg']; 
$_POST['Firewood']=$myrow['fire_wood']; 
$_POST['Others']=$myrow['others']; 



$_POST['SoilNature']=$myrow['nature_soil']; 
$_POST['WaterAvailability']=$myrow['aval_water'];
$_POST['WaterLevel']=$myrow['water_level'];

$_POST['WastewaterTreat']=$myrow['organic_water']; 
$_POST['LatrineConnected']=$myrow['latrine_con']; 
$_POST['Latrine']=$myrow['no_latrine']; 
$_POST['Distance']=$myrow['distance_site'];  
$_POST['PlantSpace']=$myrow['space_plant'];



}

for($i=1;$i<=sizeof($wastedisposal);$i++)     { 

if($wastedisposal[$i]==1)        {
    
$wastedisposal1='checked="checked"';     
    
} 
if($wastedisposal[$i]==2)        {
    
$wastedisposal2='checked="checked"';     
    
}
if($wastedisposal[$i]==3)        {
    
$wastedisposal3='checked="checked"';     
    
}
if($wastedisposal[$i]==4)        {
    
$wastedisposal4='checked="checked"';     
    
}   
    
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

echo "<fieldset style='width:835px'>";   
echo "<legend><h3>Basic Data</h3>";
echo "</legend>"; 


  
echo"<div id='basicdata'>";
echo "<table style='align:left;' border=0>";
echo"<tr><td>";


echo"<table style='width:60%' border=0>";
echo "<tr><td>Waste collection system:</td>"; 
echo"<td>";
$sql1="SELECT * FROM bio_wastecollectionsystem";
$result1=DB_query($sql1, $db);
echo '<select name="WaterCollection" id="watercollection" style="width:150px">';
$f=0;
while($myrow1=DB_fetch_array($result1))
{  
  if ($myrow1['id']==$_POST['WaterCollection']) 
  {
    echo '<option selected value="';
    
  } else {
  if($f==0){echo '<option>';   }
    echo '<option value="';
  }
  echo $myrow1['id'] . '">'.$myrow1['system']; 
  echo '</option>' ;
  $f++; 
}
echo '</select>';
echo"</td></tr>";


/*echo "<td>Single&nbspbin</td>";
echo "<td><input type='radio' $watercollection1 name='WaterCollection' id='watercollection' value='1'></td>";
echo"<td></td>";
echo"<td></td>";
echo "<td>Seperate&nbspbin</td>";
echo "<td><input type='radio' $watercollection2 name='WaterCollection' id='watercollection' value='2'></td>";  
echo "</tr>";
*/
echo"<tr></tr>" ;
echo"<tr></tr>" ;

echo "<tr><td>Beneficiary&nbspis&nbspready&nbspfor&nbspBin collection:</td>";
echo"<td>";
$sql1="SELECT * FROM bio_yes_no";
$result1=DB_query($sql1, $db);
echo '<select name="Beneficiary" id="beneficiary" style="width:150px">';
$f=0;
while($myrow1=DB_fetch_array($result1))
{  
  if ($myrow1['id']==$_POST['Beneficiary']) 
  {
    echo '<option selected value="';
    
  } else {
  if($f==0){echo '<option>';   }
    echo '<option value="';
  }
  echo $myrow1['id'] . '">'.$myrow1['name']; 
  echo '</option>' ;
  $f++; 
}
echo '</select>';
echo"</td></tr>";




 
/*echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $beneficiary1 name='Beneficiary' id='beneficiary' value='1'></td>";
echo"<td></td>";
echo"<td></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $beneficiary2 name='Beneficiary' id='beneficiary' value='2'></td>";  
echo "</tr>";*/

echo"<tr></tr>" ;
echo"<tr></tr>" ;

echo "<tr><td>Present&nbspwaste&nbspdisposal&nbspsystem</td>";  
echo "<td align=left><input type='checkbox' $wastedisposal1 name='WasteDisposal' id='wastedisposal' value='1'>";
echo "Burial</td></tr>";             
echo "<tr><td></td><td align=left><input type='checkbox' $wastedisposal2 name='WasteDisposal' id='wastedisposal' value='2'>";
echo "Piggery</td></tr>";  
echo "<tr><td></td><td align=left><input type='checkbox' $wastedisposal3 name='WasteDisposal' id='wastedisposal' value='3'>";
echo "Composting</td></tr>"; 
echo "<tr><td></td><td align=left><input type='checkbox' $wastedisposal4 name='WasteDisposal' id='wastedisposal' value='4'>";
echo "Others</td>";            
echo "</tr>";
//-------------------------

echo "<tr><td>Availability Of Water:</td>";
echo"<td>";
$sql1="SELECT * FROM bio_wateravailability";
$result1=DB_query($sql1, $db);
echo '<select name="WaterAvailability" id="wateravailability" style="width:150px">';
$f=0;
while($myrow1=DB_fetch_array($result1))
{  
  if ($myrow1['id']==$_POST['WaterAvailability']) 
  {
    echo '<option selected value="';
    
  } else {
  if($f==0){echo '<option>';   }
    echo '<option value="';
  }
  echo $myrow1['id'] . '">'.$myrow1['availability']; 
  echo '</option>' ;
  $f++; 
}
echo '</select>';
echo"</td></tr>";
  
/*echo "<td align=right>Easily</td>";
echo "<td><input type='radio' $wateravailability1 name='WaterAvailability' id='wateravailability' value='1'></td>";             
echo "<td align=right>Not&nbspeasily</td>";
echo "<td><input type='radio' $wateravailability2 name='WaterAvailability' id='wateravailability' value='2'></td>";  
echo "<td align=right>Shortage</td>";
echo "<td><input type='radio' $wateravailability3 name='WaterAvailability' id='wateravailability' value='3'></td>";           
echo "</tr>";*/


echo "<tr><td>Water Level:</td>"; 
echo"<td><input type='text' name='WaterLevel' id='waterlevel' value='".$_POST['WaterLevel']."' style='width:106px'> Meter</td></tr>";

echo "<tr><td>Present Water Source:</td>";  
echo "<td align=left><input type='checkbox' $watersource1 name='WaterSource' id='watersource' value='1'>Pipe</td></tr>";
//echo "<td></td>";             
echo "<tr><td></td><td align=left><input type='checkbox' $watersource2  name='WaterSource' id='watersource' value='2'>Bore&nbspwell</td></tr>";
//echo "<td></td>";  
echo "<tr><td></td><td align=left><input type='checkbox' $watersource3 name='WaterSource' id='watersource' value='3'>Pond</td></tr>";
//echo "<td></td>"; 
echo "<tr><td></td><td align=left><input type='checkbox' $watersource4 name='WaterSource' id='watersource' value='4'>Water Supply</td></tr>";
//echo "<td></td>";            


echo"</table>";
echo"</td>";


//----------------------------
echo"<td>";
echo"<table style='width:40%' border=0>";

echo "<tr><td>Water Consumption per day:</td>"; 
echo "<td><input type='text' value='".$_POST['Consumption']."' name='Consumption' id='consumption' style='width:106px'> Litres</td></td></tr>";

echo "<tr><td>Waste&nbspwater&nbspdischarge:</td>";
echo"<td><input type='text' value='".$_POST['Discharge']."' name='WaterDischarge' id='waterdischarge' style='width:106px'> Litres</td></td></tr>";

echo "<tr><td>Cooking fuel</td></tr>";
echo "<tr><td align=right>LPG:</td>";
echo "<td><input type='text' name='Lpg' id='lpg' value='".$_POST['Lpg']."' style='width:106px'> Kg</td></tr>";
echo "<tr><td align=right>Fire&nbspwood:</td>";
echo"<td><input type='text' name='Firewood' id='firewood' value='".$_POST['Firewood']."' style='width:106px'> Kg</td></tr>";
echo "<tr><td align=right>Others:</td>";
echo"<td><input type='text' name='Others' id='others' value='".$_POST['Others']."' style='width:106px'> Kg</td></tr>";

//---------------------------

echo"<tr></tr>"; 

echo "<tr><td>Nature Of Soil:</td>";
echo"<td>";
$sql1="SELECT * FROM bio_soilnature";
$result1=DB_query($sql1, $db);
echo '<select name="SoilNature" id="soilnature" style="width:150px">';
$f=0;
while($myrow1=DB_fetch_array($result1))
{  
  if ($myrow1['id']==$_POST['SoilNature']) 
  {
    echo '<option selected value="';
    
  } else {
  if($f==0){echo '<option>';   }
    echo '<option value="';
  }
  echo $myrow1['id'] . '">'.$myrow1['soil_nature']; 
  echo '</option>' ;
  $f++; 
}
echo '</select>';
echo"</td></tr>";



 
/*echo "<td align=right>Rocky</td>";
echo "<td><input type='radio' $soilnature1 name='SoilNature' id='soilnature' value='1'></td>";             
echo "<td align=right>Hard</td>";
echo "<td><input type='radio' $soilnature2 name='SoilNature' id='soilnature' value='2'></td>";  
echo "<td align=right>Loose</td>";
echo "<td><input type='radio' $soilnature3 name='SoilNature' id='soilnature' value='3'></td>"; 
echo "<td align=right>Marshy</td>";
echo "<td><input type='radio' $soilnature4 name='SoilNature' id='soilnature' value='4'></td>";            
echo "</tr>";*/


//---------------------------

echo"<tr></tr>"; 

echo "<tr><td>Organic&nbspwaste&nbspwater&nbsptreatment:</td>"; 
echo"<td>";
$sql1="SELECT * FROM bio_yes_no";
$result1=DB_query($sql1, $db);
echo '<select name="WastewaterTreat" id="wastewatertreat" style="width:150px">';
$f=0;
while($myrow1=DB_fetch_array($result1))
{  
  if ($myrow1['id']==$_POST['WastewaterTreat']) 
  {
    echo '<option selected value="';
    
  } else {
  if($f==0){echo '<option>';   }
    echo '<option value="';
  }
  echo $myrow1['id'] . '">'.$myrow1['name']; 
  echo '</option>' ;
  $f++; 
}
echo '</select>';
echo"</td></tr>";

/*echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $wastewatertreat1 name='WastewaterTreat' id='wastewatertreat' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $wastewatertreat2 name='WastewaterTreat' id='wastewatertreat' value='2'></td>";

echo "</tr>";*/

echo"<tr></tr>"; 

echo "<tr><td>Latrine&nbspconnection:</td>"; 
echo"<td>";
$sql1="SELECT * FROM bio_yes_no";
$result1=DB_query($sql1, $db);
echo '<select name="LatrineConnected" id="latrineconnected" style="width:150px" onchange="latrineview(this.value)">';
$f=0;
while($myrow1=DB_fetch_array($result1))
{  
  if ($myrow1['id']==$_POST['LatrineConnected']) 
  {
    echo '<option selected value="';
    
  } else {
  if($f==0){echo '<option>';   }
    echo '<option value="';
  }
  echo $myrow1['id'] . '">'.$myrow1['name']; 
  echo '</option>' ;
  $f++; 
}
echo '</select>';
echo"</td></tr>";




/*echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $latrineconnected1 name='LatrineConnected' id='latrineconnected' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $latrineconnected2 name='LatrineConnected' id='latrineconnected' value='2'></td>";  
echo "</tr>";*/
//echo "<table id='latrinedata' border=0>";

echo "<tr id='latrinedata'><td>Number of persons using latrine:</td>"; 
echo "<td><input type='text' value='".$_POST['Latrine']."' name='Latrine' id='latrine' style='width:146px'></td></tr>";
//---------------------------
/*
echo "<tr><td>Proposed use of gas</td>";
 
echo "<td><td>Street&nbsplighting</td>";
echo "<td><input type='radio' $useofelectricity1 name='useofelectricity' id='useofelectricity' value='1'></td>";             
echo "<td>All&nbspelectrical&nbsppurpose</td>";
echo "<td><input type='radio' $useofelectricity2 name='useofelectricity' id='useofelectricity' value='2'></td>";  
echo "</tr>"; 
*/
//---------------------------

echo"<tr></tr>"; 

echo "<tr><td width=36%>Distance&nbspbetween&nbspgas&nbsputilization point&nbspto&nbspplant&nbspsite:</td>"; 
echo "<td><input type='text' value='".$_POST['Distance']."' name='Distance' id='distance' style='width:106px'> Meter</td></tr>";

echo "<tr><td>Space for Plant installation</td>";
echo "<td><input type='text' value='".$_POST['PlantSpace']."' name='PlantSpace' id='plantspace' style='width:106px'> Meter</td></tr>";

echo "</table>"; 
echo"</td></tr>";

//----------------------------- 
echo "</table>"; 
echo"<div align=center>";
echo"<input type='button' value='Submit' onclick='basicdatasubmit()'>"; 
echo"</div>";

echo "</div>";
   
echo"</fieldset>";  
?>
