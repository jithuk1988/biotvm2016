<?php

  $PageSecurity = 80;
  include('includes/session.inc');
  $leadid=$_GET['p'];
  $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality,
                    bio_outputtypes.outputtype,
                    bio_leadteams.teamname,
                    bio_conceptproposal.plant,
                    bio_conceptproposal.conceptproposal_id,
                    bio_conceptproposal.team_id,
                    bio_leads.leadstatus 
              FROM  bio_leads,bio_cust,bio_outputtypes,bio_leadteams,bio_conceptproposal
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid."
              AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid
              AND bio_conceptproposal.team_id=bio_leadteams.teamid
              AND bio_conceptproposal.lead_id=bio_leads.leadid";
 $result=DB_query($sql,$db);
// $count=DB_fetch_row($result);
 $myrow=DB_fetch_array($result); 
//print_r($count);
 $no=0; 
 $k=0; 
 
 $cname=$myrow[1];  
 if($myrow[2]!='-'){
     $cph=$myrow[2]; 
 }else{
     $cph=$myrow[3]; 
 }
 $lead_sta=$myrow['leadstatus']; 
 $place=$myrow[4];
 $dist=$myrow[5];
 $ste=$myrow[6];
 $ctry=$myrow[7];
 $outputtype=$myrow[8];
 $asgnteam=$myrow[9];
 $asgnteam_id=$myrow['team_id'];
 $plantid=$myrow[10];
 $cpid1=$myrow['conceptproposal_id'];
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];     

 
 $plantid2=explode(',',$plantid);
// print_r($plantid2);
$n=sizeof($plantid2);
$plants="";
$plant_id="";
$plantprice=0;
for($i=0;$i<$n;$i++)        {

$sql="SELECT longdescription,materialcost,stockid
      FROM stockmaster
      WHERE stockid='$plantid2[$i]'";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$plant_id=$myrow['stockid'].",".$plant_id;
$plants=$myrow[0].",".$plants;

$sql_price="SELECT price
              FROM prices
             WHERE stockid='".$plantid2[$i]."'";
$result_price=DB_query($sql_price,$db);
$myrow_price=DB_fetch_array($result_price);
$plantprice=$plantprice+$myrow_price['price'];
}
 
$plant_id = substr($plant_id,0,-1); 
$plants = substr($plants,0,-1); 
             
echo '<table><tr>';

//========================================== Left Panel Begins

echo'<td>';
echo"<div id=CPdetails>";          
echo"<fieldset style='width:335px;height:225px'><legend>Customer Details</legend>";
echo"<table width=100%>";

echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Assigned Team:</td>";
echo"<td><input type='hidden' name='asgnteam' id='asgnteam' value='$asgnteam' style=width:170px>$asgnteam</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Output Type:</td>";
echo"<td><input type='hidden' name='OutputType' id='outputtype' value='$outputtype' style=width:170px>$outputtype</td></tr>";

echo"<tr><td width=50%>Plants</td>";
echo"<td><input type='hidden' name='Plant' id='plant' value='$plant_id'>$plants</td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%>Plants Price</td>";
echo"<td><input type='hidden' name='PlantsPrice' id='plantsprice' value='$plantprice'>$plantprice</td></tr>";
echo"<tr></tr>";

echo"</table>";
echo"</fieldset>";

echo'</div>';
echo"</td>";
//========================================== Left Panel Ends 

//========================================== Right Panel Begins
echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:335px;height:225px; overflow:auto;'>";
echo"<legend>Add Subassemblies</legend>";
echo"<table>";

//echo"<tr><td width=50%>Subassemblies*</td>";
//echo"<td id='subassemblies'><input type='hidden' name='SubAssemblyName' id='subassemblyname' value='no'>
//  <a onclick='showSubassemblies(this.id)'>Select</a></td></tr>";
$DateString = Date($_SESSION['DefaultDateFormat']); 

//echo"<tr><td>Start date*</td>";
//echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";

//echo"<tr><td>End date*</td>";
//echo"<td><input type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";

echo"<tr><td width=50%>DPR Team</td>";
echo"<td><input type='hidden' name='fteam' id='fteam' value='$asgnteam_id' style=width:170px>$asgnteam</td></tr>";

//echo"<tr><td>Wages for Operation*</td>";
echo"<td><input type='hidden' name='Wages' id='wages' style='width:150px'></td></tr>";
//echo"<tr><td>AMC Expenses*</td>";
echo"<td><input type='hidden' name='AMCExpense' id='amcexpense' style='width:150px'></td></tr>";
//echo"<tr><td>Depreciation*</td>";
echo"<td><input type='hidden' name='Depreciation' id='depreciation' style='width:150px'></td></tr>";
echo"<tr><td>Completion Date*</td>";
echo"<td><input type='text' name='CompletionDate' id='completiondate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";

echo"<tr><td>Advance Amount*</td>";
echo"<td><input type='text' name='AdvanceAmount' id='advanceamount' style='width:150px'></td></tr>";

echo'<tr>';
echo'<td width=50%>Mode of payment*</td>';
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
echo'</div>';
echo"</td></tr></table>";




echo "<fieldset style='width:690px'>";   
echo "<legend><h3>Bill of Items</h3>";
echo "</legend>";
echo"<table  style='width:75%;' border=0>";
echo"<tr style='background:#D50000;color:white'>";
echo"<td>Slno</td>
     <td>Item</td>
     <td>Quantity</td>
     <td>Price</td></tr>";

$n=1;
$sql_bom="SELECT bom.component,
                 bom.quantity
                     FROM bom, stockmaster
                     WHERE bom.parent = stockmaster.stockid
                     AND bom.parent='$plantid2[0]'";
         $result_bom=DB_query($sql_bom,$db);
         while($myrow_bom=DB_fetch_array($result_bom)){
             $component=$myrow_bom['component'];
             $quantity=$myrow_bom['quantity'];
             $sql_des="SELECT stockmaster.longdescription,
                   stockmaster.materialcost,
                   stockmaster.stockid,
                   prices.price
                   FROM stockmaster,prices
              WHERE stockmaster.stockid='$component'
              AND prices.stockid=stockmaster.stockid";
              $result_des=DB_query($sql_des,$db);
              $myrow=DB_fetch_array($result_des);
              $component_des=$myrow['longdescription'];
              $component_price=$myrow['price'];
              
              $total_plantprice+=$myrow['price'];
         
         
         echo "<tr style='background:#000080;color:white'>
        <td>$n</td>
        <td>$component_des<input type='hidden' name='Subassembly' id='subassembly' value='$component'></td>
        <td>$quantity<input type='hidden' name='Quantity' id='quantity' value='$quantity'></td>
        <td>$component_price<input type='hidden' name='Price' id='price' value='$component_price'></td>
        <td><a style='cursor:pointer;color:white;' id='$component' onclick=addSubAssemblies('$component','$quantity','$component_price')>Add</a ></td>";
 $n++; 
  }echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";    

echo"<table  style='width:75%;' border=0>";
         
 
    
    
    
echo"</fieldset>"; 

echo"<input type='hidden' name='LeadID' id='custdist' value='$leadid'>";
echo"<input type='hidden' name='CPID' id='cpid' value='".$cpid1."'>";
echo"<input type='hidden' name='Status' id='status' value='".$lead_sta."'>";
?>
