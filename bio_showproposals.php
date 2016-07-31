<?php
$PageSecurity = 80;    
include('includes/session.inc');    

include('includes/header.inc');

echo"<fieldset style='background: #D6DEF7;width:95%'><legend>Proposed Products</legend>"; 
echo"<div style='width:500px; overflow:auto;'>"; 
$lead=$_GET['ledid'];
$_SESSION['lead']=$lead;
 $sql="SELECT enqtypeid from bio_leads WHERE bio_leads.leadid=$lead";
      $result=DB_query($sql,$db); 
      $myrow=DB_fetch_array($result);
      $enqtype=$myrow[0]; 
$sql1="SELECT SUM(weight) from bio_leadfeedstocks  WHERE bio_leadfeedstocks.leadid=$lead";  
      $result1=DB_query($sql1,$db); 
      $myrow1=DB_fetch_array($result1); 
      $weight= $myrow1[0];

//$sql1="SELECT SUM(weight) from bio_leadfeedstocks  WHERE bio_leadfeedstocks.leadid=$lead";  
//      $result1=DB_query($sql1,$db); 
//      $myrow1=DB_fetch_array($result1); 
//      $weight= $myrow1[0];




$sql="SELECT stockmaster.description, 
stockmaster.stockid,stockmaster.kgs
from bio_catenq,
stockmaster
WHERE 
bio_catenq.enqid=$enqtype AND 
bio_catenq.catid=stockmaster.categoryid";  
  $result=DB_query($sql,$db); 
  $num_rows = DB_num_rows($result); //exit;
  if($num_rows=="" || $num_rows==0)
  {
$sql="SELECT stockmaster.description, 
stockmaster.stockid,stockmaster.kgs
from bio_catenq,
stockmaster
WHERE 
bio_catenq.catid=stockmaster.categoryid";  
  $result=DB_query($sql,$db); 
  
  $sqlapp="UPDATE bio_leadfeedstocks SET status = 1 WHERE bio_leadfeedstocks.leadid =$lead";                              
$resultapp=DB_query($sqlapp,$db); 
  
  }
  while($myrow=DB_fetch_array($result))     { 
  $kg1=$myrow[2]; 
  $kg=explode("-",$kg1);
  if($kg[0]<=$weight)
  { 
  if($kg[1]>=$weight)   { 
  $stockid=$myrow[1];   
  $kgrange=$kg1; 
  break;
  }
  }
  } 
  $stockid;  
 
   $sqlw="SELECT
distinct(stockcatproperties.label)
from stockcatproperties,stockmaster
WHERE
stockcatproperties.categoryid =stockmaster.categoryid
AND stockmaster.kgs='$kgrange'";
$resultw=DB_query($sqlw,$db);

  $sqlnw="SELECT stockitemproperties.value,
  stockcatproperties.label,stockmaster.stockid
from stockitemproperties,stockcatproperties,stockmaster
WHERE
stockcatproperties.stkcatpropid=stockitemproperties.stkcatpropid
AND stockmaster.kgs='$kgrange'
AND stockmaster.stockid=stockitemproperties.stockid";   
$resultnw=DB_query($sqlnw,$db);

echo"<table border=1><tr>";         
$th=0;
while($myrow123=DB_fetch_array($resultw)){
    if($th==0){
        echo"<th></th>";
    }
    echo"<th style='color:white'>$myrow123[0]</th>";          
    $th++;}
    echo"</tr><tr>";

 
$r=1;
while($myrownw=DB_fetch_array($resultnw)){   
    $nid=$myrownw[2];
    $property=$myrownw[1];
    $values=$myrownw[0]; 
    if($r==1){  
        $a=1;
        printf('<td style="background:#000080;color:white"><input type="checkbox" name="schm[]" value="%s"></td>',
        $nid); 
    }
    print"<td style='background:#C0C0FF;color:#800000;'>$values</td>";
    $r++;
    if($r==7)   {
        echo"</tr>";$r=1;
    }
}
if($a==1){
    echo"<tr><td colspan=2><input type='submit' id='$flg' value='Create Proposal' name='choose' onclick='if(checkCheckBoxes(this.id)==1)return false'></td></tr>";
}
echo"</table>";
echo"</div>";
echo"</fieldset>";           
?>
