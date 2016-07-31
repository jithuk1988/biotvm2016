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
                    bio_cust.nationality  
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);

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
 $place=$myrow[4];
 $ste=$myrow[6];
 $ctry=$myrow[7]; 
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];     
               
          
echo"<fieldset style='width:300px;height:180px'><legend>Customer Details</legend>";
echo"<table>";

echo"<tr><td>Customer Name</td>";
echo"<td><input type='text' name='custname' id='custname' value='$cname' style=width:170px></td></tr>";

echo"<tr><td>Assigned Team</td>";
echo"<td><input type='text' name='asgnteam' id='asgnteam' value='$asgnteam' style=width:170px></td></tr>";

echo '<tr><td>Feed Stock Type</td>'; 
echo "<td><input type='text' name='feedstock' id='feedstock' style=width:170px></tr></td>";

echo"<tr><td>Output Type</td>";
echo"<td><input type='text' name='OutputType' id='outputtype' style=width:170px></td></tr>";

echo"<tr><td>Total Feed Stock</td>";
echo"<td><input type='text' name='TotalFeedStock' id='totalfeedstock' style=width:170px></td></tr>";


echo"</table>";
echo"</fieldset>";
?>
