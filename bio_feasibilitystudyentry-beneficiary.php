<?php
$PageSecurity = 80;
include('includes/session.inc');

$leadid=$_GET['leadid'];

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_ben
       WHERE leadid=$leadid";
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9);

$supervision1='';
$supervision2=''; 
$supervision3=''; 
$supervision4=''; 



$fertilizer1='';
$fertilizer2='';

$civilworks1='';
$civilworks2='';

$beneficiary1='';
$beneficiary2='';

$support1='';
$support2='';

$amc1='';
$amc2='';

$supportreq1='';
$supportreq2='';
$supportreq3='';
$supportreq4='';
$supportreq5='';
$supportreq6='';
$special='';

if($myrow9[0] > 0)      {
    
    
$sql="SELECT * 
      FROM bio_fsentry_ben
      WHERE leadid=$leadid";  
$result=DB_query($sql,$db); 

while($myrow=DB_fetch_array($result))       {

if($myrow['liquid_fer']== 1) {
    
$fertilizer1='checked="checked"'; 
    
}elseif($myrow['liquid_fer']== 2) {
    
$fertilizer1='checked="checked"'; 
    
}


if($myrow['civil_work']== 1) {
    
$civilworks1='checked="checked"'; 
    
}elseif($myrow['civil_work']== 2) {
    
$civilworks2='checked="checked"'; 
    
}

if($myrow['materials_civil']== 1) {
    
$beneficiary1='checked="checked"'; 
    
}elseif($myrow['materials_civil']== 2) {
    
$beneficiary2='checked="checked"'; 
    
}

if($myrow['operation']== 1) {
    
$support1='checked="checked"'; 
    
}elseif($myrow['operation']== 2) {
    
$support2='checked="checked"'; 
    
}

if($myrow['amc']== 1) {
    
$amc1='checked="checked"'; 
    
}elseif($myrow['amc']== 2) {
    
$amc2='checked="checked"'; 
    
} 

if($myrow['support']== 1) {
    
$supportreq1='checked="checked"'; 
    
}elseif($myrow['support']== 2) {
    
$supportreq1='checked="checked"'; 
    
}elseif($myrow['support']== 3) {
    
$supportreq3='checked="checked"'; 
                                            
}elseif($myrow['support']== 4) {  
    
$supportreq4='checked="checked"'; 
    
}elseif($myrow['support']== 5) {
    
$supportreq5='checked="checked"'; 
    
}elseif($myrow['support']== 6) {
    
$supportreq6='checked="checked"'; 
    
}  
$supervision=explode(',',$myrow['supervision']); 
$supportreq=explode(',',$myrow['support']);

$_POST['Special']=$myrow['instruction'];

}


for($i=1;$i<=sizeof($supervision);$i++)     {
    
if($supervision[$i]==1)        {
    
$supervision1='checked="checked"';     
    
}
if($supervision[$i]==2)        {
    
$supervision2='checked="checked"';     
    
}
if($supervision[$i]==3)        {
    
$supervision3='checked="checked"';     
    
}
if($supervision[$i]==4)        {
    
$supervision4='checked="checked"';     
    
}
    
}

for($i=1;$i<=sizeof($supportreq);$i++)     { 

if($supportreq[$i]==1)        {
    
$supportreq1='checked="checked"';     
    
} 
if($supportreq[$i]==2)        {
    
$supportreq2='checked="checked"';     
    
}
if($supportreq[$i]==3)        {
    
$supportreq3='checked="checked"';     
    
}
if($supportreq[$i]==4)        {
    
$supportreq4='checked="checked"';     
    
}
if($supportreq[$i]==5)        {
    
$supportreq5='checked="checked"';     
    
}
if($supportreq[$i]==6)        {
    
$supportreq6='checked="checked"';     
    
}   
    
    
}
    
}




echo "<fieldset style='width:825px'>";   
echo "<legend><h3>Beneficiary</h3>";
echo "</legend>"; 

  
echo"<div id='siteplan'>";
echo "<table style='align:left;width:85%' border=0>";  

echo "<tr><td>Whether the beneficiary is able to undertake the following works under our supervision:</td>"; 
echo "<td align=right>Civil</td>";
echo "<td><input type='checkbox' $supervision1 name='Supervision' id='supervision' value='1'></td>";             
echo "<td align=right>Electrical</td>";
echo "<td><input type='checkbox' $supervision2 name='Supervision' id='supervision' value='2'></td>";
echo"</tr>" ;    
echo "<tr><td><td align=right>Plumping</td></td>";
echo "<td><input type='checkbox' $supervision3 name='Supervision' id='supervision' value='3'></td>"; 
echo "<td align=right>Intial feeding</td>";
echo "<td><input type='checkbox' $supervision4 name='Supervision' id='supervision' value='4'></td>";            
echo "</tr>";

echo"<tr></tr>" ;
echo"<tr></tr>" ;
echo"<tr></tr>" ;
echo"<tr></tr>" ;

echo "<tr><td>Whether the treated slurry is proposed to be utilized as liquid fertilizer:</td>"; 
echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $fertilizer1 name='Fertilizer' id='fertilizer' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $fertilizer2 name='Fertilizer' id='fertilizer' value='2'></td>";  
echo "</tr>";

echo"<tr></tr>" ;
echo"<tr></tr>" ;

echo "<tr><td>Is the beneficiary prepared to take up the required civil works:</td>"; 
echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $civilworks1 name='Civilworks' id='civilworks' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $civilworks2 name='Civilworks' id='civilworks' value='2'></td>";  
echo "</tr>";

echo"<tr></tr>" ;
echo"<tr></tr>" ;

echo "<tr><td>Whether materials required for civil works can be supplied by the beneficiary:</td>"; 
echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $beneficiary1 name='Beneficiary' id='beneficiary' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $beneficiary2 name='Beneficiary' id='beneficiary' value='2'></td>";  
echo "</tr>";

echo"<tr></tr>" ;
echo"<tr></tr>" ;

echo "<tr><td>Is any operational support is required from Biotech:</td>"; 
echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $support1 name='Support' id='support' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $support2 name='Support' id='support' value='2'></td>";  
echo "</tr>";

echo"<tr></tr>" ;
echo"<tr></tr>" ;

echo "<tr><td>Is any Annual maintenance contract(AMC) required:</td>"; 
echo "<td align=right>Yes</td>";
echo "<td><input type='radio' $amc1 name='Amc' id='amc' value='1'></td>";             
echo "<td align=right>No</td>";
echo "<td><input type='radio' $amc2 name='Amc' id='amc' value='2'></td>";  
echo "</tr>"; 

echo"<tr></tr>" ;
echo"<tr></tr>" ; 
echo"<tr></tr>" ; 
echo"<tr></tr>" ;
echo"<tr></tr>" ;

echo "<tr><td>What are the supports required from us specify</td>";
echo "<td align=right>Consultancy</td>";
echo "<td><input type='checkbox' $supportreq1 name='Supportreq' id='supportreq' value='1'></td>";             
echo "<td align=right>Feasibility&nbspStudy</td>";
echo "<td><input type='checkbox' $supportreq2 name='Supportreq' id='supportreq' value='2'></td>";
echo "</tr>"; 
 
echo "<tr><td><td align=right>Project&nbspPreparation</td></td>";
echo "<td><input type='checkbox' $supportreq3 name='Supportreq' id='supportreq' value='3'></td>"; 
echo "<td align=right>Implementation</td>";
echo "<td><input type='checkbox' $supportreq4 name='Supportreq' id='supportreq' value='4'></td>"; 
echo "</tr>";
           
echo "<tr><td><td align=right>Operation</td></td>";
echo "<td><input type='checkbox' $supportreq5 name='Supportreq' id='supportreq' value='5'></td>";            
echo "<td align=right>AMC</td>";
echo "<td><input type='checkbox' $supportreq6 name='Supportreq' id='supportreq' value='6'></td>";            
echo "</tr>";

echo"<tr></tr>";
echo"<tr></tr>";  
echo "<tr><td>Is anyother relevent information/Special instruction</td>"; 
echo "<td></td>";
echo "<td>
<textarea rows=2 cols=20
name='Special' id='special' style=resize:none;>".$_POST['Special']."</textarea></td></tr>"; 




echo "</table>";

echo"<div align=center>";
echo"<input type='button' value='submit' onclick='beneficiarysubmit()'>"; 
echo"</div>";

echo "</div>";
   
echo"</fieldset>";   
?>
