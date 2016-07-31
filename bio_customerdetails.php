<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $leadid=$_GET['p'];
  $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.area1,
bio_cust.area2,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.houseno,
                    bio_cust.housename,
                    bio_cust.pin,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.nationality,
                    bio_cust.cust_id,
                    bio_leads.teamid,
                    bio_leads.advanceamount     
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);

 $myrow=DB_fetch_array($result); 

 $no=0; 
 $k=0; 
 $cpm=$myrow['custmob'];
 $cname=$myrow['custname'];  
 if($myrow[custphone]=='-'){
     $cph='';
 }else{
     $cph=$myrow['custphone']; 
 }
 
 $team=$myrow['teamid'];
 $place=$myrow['area1'];
  $pb=$myrow['area2'];   
 $dist=$myrow['district'];
 $ste=$myrow['state'];
 //$hno=$myrow['houseno'];
 $hname=$myrow['housename'];
 $pin=$myrow['pin'];
 $email=$myrow['custmail'];  
 $cust_ID=$myrow['cust_id'];
 $ctry=$myrow['nationality']; 
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
          
echo"<fieldset style='width:380px;height:215px'><legend>Customer Details</legend>";
echo"<table width=100%>";
echo"<tr><td width=50%>Customer Name</td>";
echo"<td><input type='text' name='Custname' id='custname' value='$cname'></td></tr>";
echo"<tr></tr>";



 echo"<tr><td width=50%>Organization Name</td>";
echo"<td><input type='text' name='Housename' id='housename' value='$hname'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Organization Area</td>";
echo"<td><input type='text' name='Area1' id='custplace' value='$place'></td></tr>";
echo"<tr></tr>";
echo"<tr><td width=50%>Post Box</td>";
echo"<td><input type='text' name='post' id='post' value='$pb'></td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>Pincode</td>";
echo"<td><input type='text' name='Pin' id='housename' value='$pin'></td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>Cust Mobile no</td>";
echo"<td><input type='text' name='mobile' id='custmob' value='$cpm'></td></tr>";
echo"<tr></tr>";

 echo"<tr><td width=50%>Email</td>";
echo"<td><input type='text' name='email' id='email' value='$email'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone</td>";
echo"<td><input type='text' name='phone' id='custph' value='$cph'></td></tr>";
echo"<tr></tr>";echo"</table>";
echo"</fieldset>"; 

echo'</div>';
echo"</td>"; 

echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:380px;height:215px; overflow:auto;'>";
echo"<legend>Assign Team</legend>";
echo"<table>";
echo"<tr><td width=50%>Feasibility Team*</td>";
echo"<td><select name='fteam' id='fteam' style='width:150px'>";
$sql="SELECT bio_leadteams.teamid,bio_leadteams.teamname FROM bio_leadteams";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['teamid']==$team)  
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
    echo $myrow1['teamid'] . '">'.$myrow1['teamname'];
    echo '</option>';
    $f++;
   }   
    

   
echo"</select></td></tr>";
$DateString = Date($_SESSION['DefaultDateFormat']); 

echo"<tr><td>Start date*</td>";
echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px' value='".$DateString."'></td></tr>";

echo"<tr><td>End date*</td>";
echo"<td><input type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";
if($leadadvance>0){
echo"<tr><td>Lead Advance Amount</td>";
echo"<td><input type='hidden' name='LeadAdvance' id='leadadvance' value='$leadadvance' style='width:150px'>$leadadvance</td></tr>";
echo'<tr>';
 }
 else{
  echo"<td><input type='hidden' name='LeadAdvance' id='leadadvance' value='$leadadvance' style='width:150px'></td></tr>";    
 }
echo"<tr><td>Feasibility Study Amount</td>";
echo"<td><input type='text' name='AdvanceAmount' id='advanceamount' style='width:150px'></td></tr>";
echo'<tr>';
echo'<td width=50%>Mode of payment:</td>';
echo'<td><select name="mode" id="paymentmode" style="width:150px" onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$_POST['mode'])
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
echo"<input type='hidden' name='CustID' id='custid' value='$cust_ID'>";

?>
