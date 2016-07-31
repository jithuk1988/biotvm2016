<?php
  $PageSecurity = 80;
include('includes/session.inc');
$fs_ID=$_GET['p'];
$leadid=$_GET['q'];

$sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.houseno,
                    bio_cust.housename,
                    bio_cust.pin,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.nationality,
                    bio_cust.cust_id  
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);
// $count=DB_fetch_row($result);
 $myrow=DB_fetch_array($result); 
//print_r($count);
 $no=0; 
 $k=0; 
 $mob=$myrow['custmob'];
 $cname=$myrow['custname'];;  
 if($myrow[2]!='-'){
     $cph=$myrow['custphone']; 
 }else{
     $cph=$myrow['custmob']; 
 }
  
 $place=$myrow['area1'];
 $dist=$myrow['district'];
 $ste=$myrow['state'];
 $hno=$myrow['houseno'];
 $hname=$myrow['housename'];
 $pin=$myrow['pin'];
 $mail=$myrow['custmail'];  
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

echo"<fieldset style='width:380px;height:200px'><legend>Customer Details</legend>";
echo"<table width=100%>";
echo"<tr><td width=50%>Customer Name</td>";
echo"<td><input type='hidden' name='Custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td width=50%>House no</td>";
echo"<td><input type='hidden' name='Houseno' id='houseno' value='$hno'>$hno</td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>House name</td>";
echo"<td><input type='hidden' name='Housename' id='housename' value='$hname'>$hname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place</td>";
echo"<td><input type='hidden' name='Area1' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>Pincode</td>";
echo"<td><input type='hidden' name='Pin' id='housename' value='$pin'>$pin</td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>Cust Mobile no</td>";
echo"<td><input type='hidden' name='mobile' id='custmob' value='$mob'>$mob</td></tr>";
echo"<tr></tr>";

 echo"<tr><td width=50%>Email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$mail'>$mail</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone</td>";
echo"<td><input type='hidden' name='phone' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";

echo'</div>';
echo"</td>"; 

$sql_fsedit="SELECT * FROM bio_feasibilitystudy
                        WHERE feasibilitystudy_id=".$fs_ID;
$result_fsedit=DB_query($sql_fsedit,$db);
$myrow_fsedit=DB_fetch_array($result_fsedit);
$fsstatus=$myrow_fsedit['feasibility_status'];
echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:380px;height:200px; overflow:auto;'>";
echo"<legend>Edit Details</legend>";
echo"<table>";
echo"<tr><td width=50%>Feasibility Team*</td>";
echo"<td><select name='fteam' id='fteam' style='width:150px'>";
$sql="SELECT bio_leadteams.teamid,bio_leadteams.teamname FROM bio_leadteams";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['teamid']==$myrow_fsedit['teamid'])  
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
echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px' value='".ConvertSQLDate($myrow_fsedit['feasibilitystudy_startdate'])."'></td></tr>";

echo"<tr><td>End date*</td>";
echo"<td><input type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px' value='".ConvertSQLDate($myrow_fsedit['feasibilitystudy_enddate'])."'></td></tr>";
 
echo"<tr><td>Feasibility Study Amount</td>";
echo"<td><input type='text' name='AdvanceAmount' id='advanceamount' style='width:150px' value='".$myrow_fsedit['feasibilitystudy_charge']."' readonly></td></tr>";


$sql_fsmode="SELECT * FROM bio_advance
                        WHERE leadid=".$leadid." AND head_id=2";
$result_mode=DB_query($sql_fsmode,$db);
$myrow_mode=DB_fetch_array($result_mode);

//$ref=$myrow_mode['reference'];
//$ref1=explode(',',$ref);

$amtdate=$myrow_mode['date'];
$reno=$myrow_mode['serialnum'];
$bank=$myrow_mode['bankname'];

if($myrow_mode['mode_id']!=""){
    $sql1="SELECT * FROM bio_paymentmodes
        WHERE id=".$myrow_mode['mode_id'];
$result1=DB_query($sql1, $db);
$myrow_mode1=DB_fetch_array($result1);
echo'<tr>';
echo'<td width=50%>Mode of payment:</td>';
echo'<td><select name="mode" id="paymentmode" style="width:150px" onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$myrow_mode['mode_id'])
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
/*
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$myrow_mode['mode'])
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
*/
echo"</table>";
echo"<table>";

if($myrow_mode['mode']==2){
    echo'<tr><td style="width:50%">Cheque Date</td><td><input type="text" name="amtdate" id="amtdate" style="width:150px" value="'.$amtdate.'"></td></tr>';    
    echo'<tr><td>Cheque no:</td><td><input type="text" name="amtno" id="amtno" style="width:150px" value="'.$reno.'"></td></tr>';  
    echo'<tr><td>Bank name:</td><td><input type="text" name="amtbank" id="amtbank" style="width:150px" value="'.$bank.'"></td></tr>';
    
}elseif($myrow_mode['mode']==3){
    echo'<tr><td style="width:50%">DD date</td><td><input type="text" name="amtdate" id="amtdate" style="width:150px" value="'.$amtdate.'"></td></tr>';    
    echo'<tr><td>DD no:</td><td><input type="text" name="amtno" id="amtno" style="width:150px" value="'.$reno.'"></td></tr>';  
    echo'<tr><td>Bank name:</td><td><input type="text" name="amtbank" id="amtbank" style="width:150px" value="'.$bank.'"></td></tr>';
}

echo"</table>";

echo"<table id='modeamt'>";
echo"</table>"; 
echo"<table id='amt'>";
echo"</table>";
}
else{
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
}

 

echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>";

echo"<input type='hidden' name='LeadID' id='custdist' value='$leadid'>";
echo"<input type='hidden' name='FSID' id='custdist' value='$fs_ID'>";
echo"<input type='hidden' name='Status' id='status' value='$fsstatus'>";
?>
