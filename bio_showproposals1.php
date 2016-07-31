<?php
$PageSecurity = 80;    
include('includes/session.inc');    

include('includes/header.inc');
echo"<fieldset style='background: #D6DEF7;'><legend>Proposed Products</legend>";  
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
  while($myrow=DB_fetch_array($result)){ $kg1=$myrow[2]; $kg=split("-",$kg1);
  if($kg[0]<=$weight)
  { $kg[0];
  if($kg[1]>=$weight){ $stockid=$myrow[1];  $kgrange=$kg1; break;}}} 
  $stockid;  
 
  $sqlnw="SELECT stockitemproperties.value,
  stockcatproperties.label,stockmaster.stockid
from stockitemproperties,stockcatproperties,stockmaster
WHERE
stockcatproperties.stkcatpropid=stockitemproperties.stkcatpropid
AND stockmaster.kgs='$kgrange'
AND stockmaster.stockid=stockitemproperties.stockid";   
$resultnw=DB_query($sqlnw,$db);  
echo"<table style='border:1px solid #3C6A99'><tr>";            $r=1; $a=1;
while($myrownw=DB_fetch_array($resultnw)){   $nid=$myrownw[2]; $a=2;
    $property=$myrownw[1];
    $values=$myrownw[0]; 
    if($r==1){ 
        
                                printf(' 
                <td style="background:#3C6A99;color:white;width:10px"><input type="checkbox" name="schm[]" value="%s"></td>',
    
               
                  
                  $nid
               

                ); 
        
    }
    print"<td style='background:#EFF4F8;color:#3C6A99;'><b>$property</b></td><td style='background:#EFF4F8;color:#3C6A99;'>$values</td>";
$r++; if($r==7){echo"</tr>";$r=1;}}   
if($a==2){
echo"<tr><td colspan=2><input type='submit' value='Create Proposal' name='choose'></td></tr>";}
echo"</table></fieldset>";           
?>
