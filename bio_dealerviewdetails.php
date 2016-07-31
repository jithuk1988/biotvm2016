<?php
  $PageSecurity = 80;
include('includes/session.inc');

$busid=$_GET[bussid];

$sql="SELECT bio_businessassodetails_enq.custname, 
             bio_businessassodetails_enq.custphone, 
             bio_businessassodetails_enq.custmob,
             bio_businessassodetails_enq.custmail,
             bio_businessassodetails_enq.area1,  
             bio_district.district, 
             bio_businessassociates_enq.source, 
             bio_businessassociates_enq.description, 
             bio_businessassociates_enq.remark
       FROM  bio_businessassociates_enq,bio_businessassodetails_enq,bio_district 
       WHERE bio_businessassodetails_enq.cust_id=bio_businessassociates_enq.cust_id
       AND   bio_district.did=bio_businessassodetails_enq.district 
       AND   bio_district.stateid=bio_businessassodetails_enq.state   
       AND   bio_district.cid=bio_businessassodetails_enq.nationality 
       AND   bio_businessassociates_enq.buss_id=".$_GET['bussid']."";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);


echo"<table><tr><td>"; 

echo"<fieldset style='width:700px;height:auto'>"; 
echo"<legend>Dealer Details</legend>";
echo"</legend>";  



echo"<table width=70%>"; 
echo"<tr><td width=50%>Dealer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$myrow[custname]'>$myrow[custname]</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Dealer Contact:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$myrow[custmob]'>$myrow[custmob]</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Dealer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$myrow[custmail]'>$myrow[custmail]</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Dealer Place:</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$myrow[area1]'>$myrow[area1]</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Dealer District:</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$myrow[district]'>$myrow[district]</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Remarks:</td>"; 
if($myrow[source]==2){
$remark=$myrow[description];    
}else{
$remark=$myrow[remark];    
}
echo"<td><textarea rows=2 cols=50 name=remarks id=remarks style=resize:none;>$remark</textarea></td></tr>";
echo"<tr></tr>";
echo"</table>";

echo"</fieldset>";

echo"</td><td>";

echo"<fieldset style='width:350px;height:175px'>"; 
echo"<legend>Links</legend>";
echo"</legend>";  
echo"<table>";

echo"<br /><br />";
echo"<tr><td><a href='Areas.php?bussid=$busid'>Confirm as a dealer</a></td></tr>";
//echo"<tr><td><a style='cursor:pointer;' onclick='dealerpay($_GET[bussid])'>Payment Collection</a></td></tr>";
//echo"<tr><td><a href='bio_dealerDocument.php'>Document Collection</a></td></tr>";

echo"</table>";
echo"</td></tr>";
        
echo"</table>";
?>
