<?php

  $PageSecurity = 80;
  include('includes/session.inc');
  $leadid=$_GET['lead'];
  $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality,
                    bio_leads.leadstatus  
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);

 $myrow=DB_fetch_array($result); 
 $sqlhd="SELECT `head_id` FROM `bio_advance` WHERE `leadid`=".$leadid;
  $resulthd=DB_query($sqlhd,$db);
   $myrowhd=DB_fetch_array($resulthd); 
   $headid=$myrowhd[0];
   echo"<td><input type='hidden' name='head' id='head' value='$headid'>";

 $no=0; 
 $k=0; 
 
 $cname=$myrow[1];  
 if($myrow[2]!='-'){
     $cph=$myrow[2]; 
 }else{
     $cph=$myrow[3]; 
 }
 $email=$myrow['custmail']; 
 $place=$myrow[5];
 $dist=$myrow[6];
 $ste=$myrow[7];
 $ctry=$myrow[8]; 
 $status=$myrow['leadstatus'];
 $sqladv="Select sum(amount) from bio_advance where leadid=".$leadid;
 $resultadv=DB_query($sqladv,$db);
  $myrowadv=DB_fetch_array($resultadv);
  $advance= $myrowadv[0];
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];     
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
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district'>$district</td></tr>";
echo"<tr><td><b>Advance amount:</b></td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$advance'><b>$advance</b></td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>"; 
echo"<input type='hidden' name='status' id='status' value='$status'>";  
echo"<input type='hidden' name='firstamount' id='firstamount' value='$advance'>";
echo'</div>';
echo"</td>"; 

echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:380px;height:200px; overflow:auto;'>";
echo"<legend>Payment Details</legend>";
echo"<table>";

echo"<tr><td>Repay Amount*</td>";
echo"<td><input type='text' name='advanceamount' id='advanceamount' style='width:150px'></td></tr>";
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
echo"<input type='hidden' name='stop' id='stop' value='2'>";  


?>
