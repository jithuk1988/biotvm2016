<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $leadid=$_GET['p'];
       $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.contactperson,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality,
                    bio_outputtypes.outputtype,
                    bio_leadteams.teamname,
                    bio_cust.custmail,
                    bio_cust.custmob,
                    bio_cust.cust_id,
                    bio_cust.houseno,
                    bio_cust.housename,
                    bio_cust.pin,
                    bio_fs_entrydetails.teamid,
                    bio_fs_entrydetails.budget   
              FROM  bio_leads,bio_cust,bio_outputtypes,bio_leadteams,bio_fs_entrydetails
              WHERE bio_leads.cust_id=bio_cust.cust_id
                AND bio_leads.leadid=".$leadid."  
                AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid
                AND bio_fs_entrydetails.teamid=bio_leadteams.teamid
                AND bio_fs_entrydetails.leadid=bio_leads.leadid";
              
 $result=DB_query($sql,$db);
  $myrow=DB_fetch_array($result); 
//print_r($count);
 $no=0; 
 $k=0; 
 
 $mob=$myrow['custmob'];
 $cname=$myrow['custname'];  
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
 $contactperson=$myrow['contactperson']; 
 $district=$myrow['district']; 
 $pin=$myrow['pin'];
 $mail=$myrow['custmail'];  
 $cust_ID=$myrow['cust_id'];
 $ctry=$myrow['nationality']; 
 $asgnteam_id=$myrow['teamid'];
 $asgnteam=$myrow['teamname'];
 $budget=$myrow['budget'];
 
 $sql1="SELECT bio_district.district 
          FROM bio_district
         WHERE bio_district.stateid=".$ste."  
           AND bio_district.cid=".$ctry;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0]; 
 
 
//==========================================Customer Details 

echo"<fieldset style='width:820px;height:160px'><legend>Customer Details</legend>";
echo"<table width=100%>"; 
 
echo"<div id=cus_details>"; 
echo"<tr><td width=50%>Organisation Name</td>";
echo"<td><input type='text' name='Custname' id='custname' value='$cname' style=width:175px></td></tr>";

echo"<tr></tr>";
echo"<tr><td width=50%>Contact Person</td>";
echo"<td><input type='text' name='Contactperson' id='contactperson' value='$contactperson' style=width:175px></td></tr>"; 

echo"<tr></tr>";
echo"<tr><td width=50%>Mobile Number</td>";
echo"<td><input type='text' name='mobile' id='custmob' value='$mob' style=width:175px></td></tr>";

echo"<tr></tr>";
echo"<tr><td>Phone Number</td>"; 
echo"<td><input type='text' name='phone' id='custph' value='$cph' style=width:175px></td></tr>";
 
echo"<tr><td width=50%>Budget Amount</td>";  
echo "<td><input type=text readonly name='Budget' id='budget' value='$budget' style=width:175px></td></tr>";      
echo"</div>";

echo"<tr><td width=50%>Plant</td>";
echo "<td id='plantname'><input type='hidden' name='Plantid' id='plantid'>
      <a onclick='selectplant($leadid,1,$budget)'>Select</a></td></tr>";
            
echo"</table>"; 
echo"</fieldset>"; 
//================================================= Plant Details

echo"<table>";
echo'<tr><td>';
echo'<div id="plant_select"></div>';
echo"</td></tr></table>";

//==================================================Feasibility Study Details

echo "<fieldset style='width:785px'>";   
echo "<legend><h3>Feasibility Study Details</h3>";
echo "</legend>"; 
    

$sql_fsedit="SELECT * FROM bio_fs_entrydetails
                        WHERE leadid=".$leadid;
$result_fsedit=DB_query($sql_fsedit,$db);
$myrow_fsedit=DB_fetch_array($result_fsedit);
$fsentr_ID=$myrow_fsedit['fsentry_id'];




echo"<div id='editfdstok'></div>";

echo"<div id='feedstockdiv'></div>";


echo"<table  style='width:85%;' border=0>";
echo"<tr style='background:#D50000;color:white'>";
echo"<td>Slno</td>
          <td>Feed Stock Source</td>
     <td>Feed Stock</td>
     <td>Weight/Ltr</td></tr>";
 $n=1;
$sql_fstock="SELECT bio_feedstocks.feedstocks,
                     bio_fs_feedstockdetails.gasout,
                     bio_fs_feedstockdetails.weight,
                     bio_fssources.source
                FROM bio_fs_feedstockdetails,
                     bio_feedstocks,
                     bio_fssources
               WHERE bio_feedstocks.id=bio_fs_feedstockdetails.feedstockid
                 AND bio_fs_feedstockdetails.feedstocksourceid=bio_fssources.id
                 AND bio_fs_feedstockdetails.leadid=".$leadid;
                 
 $result_fstock=DB_query($sql_fstock, $db);    
 while($myrow=DB_fetch_array($result_fstock)){
    echo "<tr style='background:#000080;color:white'>
        <td>$n</td>
        <td>$myrow[3]<input type='hidden' id='hfeedstock' value='$myrow[3]'></td>
        <td>$myrow[0]<input type='hidden' id='hfeedsource' value='$myrow[0]'></td>
        <td>$myrow[2]<input type='hidden' id='hfeedweight' value='$myrow[2]'></td>
        
        ";
 $n++; 
  //<td><a style='cursor:pointer;color:white;' id='$myrow[1]' onclick='editfeedstok(this.id)'>Edit</a ></td>
//        <td><a style='cursor:pointer;color:white' id='$myrow[1]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>
}

echo"</table>";   
echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";    

echo"<table  style='width:90%;' border=0>";
echo"<tr><td style='width:45%;'>";
echo"<table border=0 style='width:90%;'>";
echo"<tr><td>Easily Degradable</td>";
echo"<td>:<input type='hidden' name='edegradable' id='edegradable' value='".$myrow_fsedit['edegradable']."'>".$myrow_fsedit['edegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Slow Degradable</td>";
echo"<td>:<input type='hidden' name='sdegradable' id='sedegradable' value='".$myrow_fsedit['sdegradable']."'>".$myrow_fsedit['sdegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Very Slow Degradable</td>";
echo"<td>:<input type='hidden' name='vsdegradable' id='vsedegradable' value='".$myrow_fsedit['vsdegradable']."'>".$myrow_fsedit['vsdegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Non Degradable</td>";
echo"<td>:<input type='hidden' name='nondegradable' id='nonedegradable' value='".$myrow_fsedit['ndegradable']."'>".$myrow_fsedit['ndegradable']." Kg</td></tr>";
echo"<tr></tr>";



$myrow_fsedit['actual_feedstock']= $myrow_fsedit['edegradable'] + $myrow_fsedit['sdegradable'] + $myrow_fsedit['vsdegradable'] + $myrow_fsedit['ndegradable'];

echo"<tr><td>Total Feedstocks</td>";
echo"<td>:<input type='hidden' name='actual' id='actual' value='".$myrow_fsedit['actual_feedstock']."'>".$myrow_fsedit['actual_feedstock']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Organic waste water</td>";
echo"<td>:<input type='hidden' name='orgwastewater' id='orgwastewater' value='".$myrow_fsedit['liquid_waste']."'>".$myrow_fsedit['liquid_waste']." Ltr</td></tr>";
echo"<tr></tr>";
/*echo"<tr><td>Easily Degradable</td>";
echo"<td>:<input type='hidden' name='edegradable' id='edegradable' value='".$myrow_fsedit['edegradable']."'>".$myrow_fsedit['edegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Slow Degradable</td>";
echo"<td>:<input type='hidden' name='sdegradable' id='sedegradable' value='".$myrow_fsedit['sdegradable']."'>".$myrow_fsedit['sdegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Very Slow Degradable</td>";
echo"<td>:<input type='hidden' name='vsdegradable' id='vsedegradable' value='".$myrow_fsedit['vsdegradable']."'>".$myrow_fsedit['vsdegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Non Degradable</td>";
echo"<td>:<input type='hidden' name='nondegradable' id='nonedegradable' value='".$myrow_fsedit['ndegradable']."'>".$myrow_fsedit['ndegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Total Feedstocks</td>";
echo"<td>:<input type='hidden' name='actual' id='actual' value='".$myrow_fsedit['actual_feedstock']."'>".$myrow_fsedit['actual_feedstock']." Kg</td></tr>";
echo"<tr></tr>";*/

echo"<tr><td>Total Gas</td>";
echo"<td>:<input type='hidden' name='TotalGas' id='totalgas' value='".$myrow_fsedit['total_gas']."'>".$myrow_fsedit['total_gas']."</td></tr>";
echo"</table>";
echo"</td>";

echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>"; 
echo"<input type='hidden' name='FSEntryID' id='fsentryid' value='$fsentr_ID'>"; 

?>