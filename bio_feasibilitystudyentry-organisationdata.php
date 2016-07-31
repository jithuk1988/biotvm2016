<?php
$PageSecurity = 80;
include('includes/session.inc');

$leadid=$_GET['leadid'];

 $sql_out="SELECT outputtypeid FROM bio_leads WHERE leadid=$leadid";
    $result_out=DB_query($sql_out, $db);
    $myrow=DB_fetch_array($result_out); 
    $outputtypeid=$myrow['outputtypeid'];
    
$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_orgdata
       WHERE leadid=$leadid";
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 

$canteen1='';
$canteen2=''; 

$food1='';
$food2='';

$function1='';
$function2='';

$wastewatertreat1='';
$wastewatertreat2='';

$latrineconnected1='';
$latrineconnected2='';

if($myrow9[0] > 0)      {
    
    
$sql="SELECT * 
      FROM bio_fsentry_orgdata
      WHERE leadid=$leadid";  
$result=DB_query($sql,$db); 

while($myrow=DB_fetch_array($result))       {
if($myrow['canteen']== 1) {
    
$canteen1='checked="checked"'; 
    
}elseif($myrow['canteen']== 2) {
    
$canteen2='checked="checked"'; 
    
}

$food=explode(',',$myrow['food_served']); 


if($myrow['special_fun']== 1) {
    
$function1='checked="checked"'; 
    
}elseif($myrow['special_fun']== 2) {
    
$function2='checked="checked"'; 
    
}

if($myrow['organic_water']== 1) {
    
$wastewatertreat1='checked="checked"'; 
    
}elseif($myrow['organic_water']== 2) {
    
$wastewatertreat2='checked="checked"'; 
    
}

if($myrow['latrine_con']== 1) {
    
$latrineconnected1='checked="checked"'; 
    
}elseif($myrow['latrine_con']== 2) {
    
$latrineconnected2='checked="checked"'; 
    
}  

$watersource=explode(',',$myrow['output']); 

$_POST['Inmates']=$myrow['no_inmates'];
$_POST['Staff']=$myrow['no_staff'];
$_POST['Workers']=$myrow['no_workers'];
$_POST['Morning']=$myrow['no_morn'];
$_POST['Lunch']=$myrow['no_lunch'];
$_POST['Evening']=$myrow['no_eve'];
$_POST['Dinner']=$myrow['no_dinner'];
$_POST['Visitors']=$myrow['visitors'];
$_POST['Between']=$myrow['no_part_between'];
$_POST['And']=$myrow['no_part_and'];
$_POST['Funmonth']=$myrow['no_fun_month'];
$_POST['Latrine']=$myrow['no_latrine'];
$_POST['Distance']=$myrow['distance_site'];

}
$a=1;
$outputtype.$a='checked="checked"'; 
for($i=1;$i<=sizeof($food);$i++)     {  
    
if($food[$i]==1)        {
    
$food1='checked="checked"';     
    
} 
if($food[$i]==1)        {
    
$food2='checked="checked"';     
    
} 
}
    
}

echo "<fieldset style='width:825px'>";   
echo "<legend><h3>Organisational Data</h3>";
echo "</legend>";

//echo"<div align=right>";
//echo"<a style='cursor:pointer;'  class='showorganisationaldata'>View/Hide</a>";
//echo"</div>"; 
  
echo"<div id='organisationaldata'>";
echo "<table style='align:left;width:100%' border=0>";  
echo "<tr><td width=30%>Number of Inmates:</td>"; 
echo "<td width=15%><input type='text' value='".$_POST['Inmates']."' name='Inmates' id='inmates' style='width:60px'></td>";             
echo "<td align=right width=15%>Staff</td>";
echo "<td width=15%><input type='text' value='".$_POST['Staff']."' name='Staff' id='staff' style='width:60px'></td>"; 
echo "<td align=right  width=15%>Workers</td>";
echo "<td  width=15%><input type='text' value='".$_POST['Workers']."' name='Workers' id='workers' style='width:60px'></td>";  
echo "</tr>"; 

echo "<tr><td>Is there any canteen:</td>"; 
echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $canteen1 name='Canteen' id='canteen' value='1' onclick='canteenview(this.value)'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $canteen2 name='Canteen' id='canteen' value='2' onclick='canteenview(this.value)'></td>";  
echo "</tr>";
echo"</table>"; 


echo "<table id='canteendata' border=0>"; 
echo "<tr><td width=30%>Types of food served:</td>"; 
echo "<td align=right>Veg</td>";
echo "<td><input type='checkbox' $food1 name='Food' id='food' value='1'></td>";             
echo "<td align=right>Non Veg</td>";
echo "<td><input type='checkbox' $food2 name='Food' id='food' value='2'></td>";  
echo "</tr>";

echo "<tr><td>Number of people using canteen facility:</td>"; 
echo "<td align=right>Morning</td>"; 
echo "<td><input type='text' value='".$_POST['Morning']."' name='Morning' id='morning' style='width:60px'></td>";  
echo "<td align=right>Lunch</td>"; 
echo "<td><input type='text' value='".$_POST['Lunch']."' name='Lunch' id='lunch' style='width:60px'></td>";           
echo "<td align=right>Evening</td>";
echo "<td><input type='text' value='".$_POST['Evening']."' name='Evening' id='evening' style='width:60px'></td>"; 
echo "<td align=right>Dinner</td>";
echo "<td><input type='text' value='".$_POST['Dinner']."' name='Dinner' id='dinner' style='width:60px'></td>";  
echo "</tr>";

echo "<tr><td>Expected number of visitors per day:</td>";
echo "<td><input type='text' value='".$_POST['Visitors']."' name='Visitors' id='visitors' style='width:60px'></td>";  
echo "</tr>";

echo "<tr><td>Is there any special function conducted every week/month:</td>"; 
echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $function1 name='Function' id='function' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $function2 name='Function' id='function' value='2'></td>";  
echo "</tr>";

echo "<tr><td>Number of participants for each function between</td>"; 
echo "<td><input type='text' value='".$_POST['Between']."' name='Between' id='between' style='width:60px'></td>";  
echo "<td align=center>and</td>";
echo "<td><input type='text' value='".$_POST['And']."' name='And' id='and' style='width:60px'></td>"; 
echo "</tr>";

echo "<tr><td>Expected number of functions in a month:</td>"; 
echo "<td><input type='text' value='".$_POST['Funmonth']."' name='Funmonth' id='funmonth' style='width:60px'></td></tr>";  
echo "</table>"; 


echo"<table width=100% border=0>";
echo "<tr><td width=30%>Whether Organic waste water is to be treated in the plant:</td>"; 
echo "<td align=right>Yes</td>";
echo "<td width=15%><input type='radio' $wastewatertreat1 name='WastewaterTreat' id='wastewatertreat' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $wastewatertreat2 name='WastewaterTreat' id='wastewatertreat' value='2'></td>";

echo "</tr>";

echo "<tr><td>Whether latrine is proposed to be connected to the plant (For toilet linked projects only):</td>"; 
echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $latrineconnected1 name='LatrineConnected' id='latrineconnected' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $latrineconnected2 name='LatrineConnected' id='latrineconnected' value='2'></td>";  
echo "</tr>";

echo "<tr><td>Number of persons using latrine:</td>"; 
echo "<td><input type='text' value='".$_POST['Latrine']."' name='Latrine' id='latrine' style='width:60px'></td></tr>";
    
echo "</table>";

echo "<table style='align:left;width:100%' border=0>";


 $outputtype_id2=explode(',',$outputtypeid);
    $n=sizeof($outputtype_id2);

echo'<tr><td>Output Type:</td>'; 
    $sql_out="SELECT * FROM bio_outputtypes";
    $result_out=DB_query($sql_out, $db);
    $j=1;
    while($mysql_out=DB_fetch_array($result_out)){ 
$f=1;
      for($i=0;$i<$n;$i++)        {
        if($mysql_out[0]==$outputtype_id2[$i]){
           echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].' checked>'.$mysql_out[1].'</td>';
           $j++; 
         $f=0;
        }
      }
      if($f==1){
         echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
         $j++;
         $f=0; 
      }  
    
    } 
echo"</tr></table>";
echo "<table style='align:left;width:100%' border=0>";
echo "<tr><td width=36%>Distance between gas utilization point to plant site:</td>"; 
echo "<td><input type='text' value='".$_POST['Distance']."' name='Distance' id='distance' style='width:60px'>    Meters</td></tr>";
echo"</table>"; 


echo"<div align=center>";
echo"<input type='button' value='Submit' onclick='organisationdatasubmit()'>"; 
echo"</div>";

echo "</div>"; 
   
echo"</fieldset>";  
?>
