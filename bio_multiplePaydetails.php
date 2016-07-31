<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $leadid=$_GET['p'];
  $sql="SELECT
    `bio_cust`.`custname`
     , `bio_cust`.`custphone` 
    , `bio_cust`.`custmob`
    , `bio_cust`.`custmail` 
    , `bio_cust`.`area1`
    , `bio_district`.`district`
    , `bio_status`.`biostatus`
FROM
    `bio_leads`
    LEFT JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    LEFT JOIN `bio_district` 
        ON (`bio_cust`.`nationality` = `bio_district`.`cid`) AND (`bio_cust`.`state` = `bio_district`.`stateid`) AND (`bio_cust`.`district` = `bio_district`.`did`)
    LEFT JOIN `bio_status` 
        ON (`bio_leads`.`leadstatus` = `bio_status`.`statusid`)
              WHERE 
               bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);

 $myrow=DB_fetch_array($result); 

 $no=0; 
 $k=0; 
 
 $cname=$myrow['custname'];  
 if($myrow['custphone']!='-'){
     $cph=$myrow['custphone']; 
 }else{
     $cph=$myrow['custmob']; 
 }
 $email=$myrow['custmail']; 
 $place=$myrow['area1'];
 $district=$myrow['district'];
 $status=$myrow['biostatus'];
/* $ctry=$myrow[8]; 
 $status=$myrow['leadstatus'];
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];  */   
echo '<table><tr>';
echo'<td valign=top>';

echo"<div id=cus_details>";               
          
echo"<fieldset style='width:380px;height:200px'><legend>Customer Details</legend>";
echo"<table width=100%>";
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place:</td>";
echo"<td><input type='text' name='custplace' id='custplace' value='$place'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='text' name='custdist' id='custdist' value='$district'></td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>"; 
echo"<input type='hidden' name='status' id='status' value='$status'>";  
echo'</div>';
echo"</td>"; 

echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:380px;height:200px; overflow:auto;'>";
echo"<legend>Task Details</legend>";
echo"<table>";
echo"<tr><td width=50%>Head</td>";
echo"<td><select name='head' id='head' style='width:150px'>";
$sql="SELECT * FROM bio_cashheads";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['head_id']==$_POST['head'])  
    {   
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['head_id'] . '">'.$myrow1['heads'];
    echo '</option>';
    $f++;
   }   
echo"</select></td></tr>";

echo"<tr><td>Advance Amount*</td>";
echo"<td><input type='text' name='advanceamount' required id='advanceamount' style='width:150px'></td></tr>";
echo'<tr>';

echo'<td width=50%>Mode of payment:</td>';
echo'<td><select name="mode" id="paymentmode" style="width:150px" onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$_POST['modes'])
    {
        echo '<option selected value="';
    } else 
    {
        if ($f==0) 
        {
            echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['id'] . '">'.$myrow1['modes'];  
    echo '</option>';
  }
echo'</select></td></tr>';
 

echo"</table>";

echo"<table id='modeamt'>";
echo"</table>"; 
echo"<table id='amt'>";
echo"</table>"; 

echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>";





echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";

?>
