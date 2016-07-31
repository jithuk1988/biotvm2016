<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  
  $cp_ID=$_GET['p'];
  $_SESSION['cpid']=$cp_ID;
  $leadid=$_GET['q'];
  $_SESSION['leadid']=$leadid; 
  
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
                    bio_fs_entrydetails.fsentry_id  
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
 $fsentryid=$myrow['fsentry_id'];
 
 $_SESSION['fsentry_id']=$fsentryid;
 
echo '<table><tr>';    
echo'<td>'; 
 
echo"<div id=cus_details>";
echo"<fieldset style='width:820px;height:160px'><legend>Customer Details</legend>";
echo"<table width=100%>";

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
echo "<td><input type=text name='Budget' id='budget' value='' style=width:175px></td></tr>";
echo"</div>";


echo'<input type="hidden" name="LeadID" value='.$leadid.'>';
echo'<input type="hidden" name="custid" value='.$cust_id.'>';

 //=================================================================  

//echo"<tr><td width=50%>Plant</td>";
//echo "<td id='plantname'><input type='hidden' name='Plantid' id='plantid'>
//      <a onclick='selectplant($leadid,1)'>Select</a></td></tr>";
  
echo"</table>";
echo"</td></tr";
echo"</fieldset>"; 

//=================================================================



echo"<tr><td>" ;
echo"<div>";
echo "<fieldset style='width:785px'>";   
echo "<legend><h3>Feasibility Study Details</h3>";
echo "</legend>";



echo"<table  style='width:85%;' border=0>";
echo"<tr style='background:#D50000;color:white'>";
echo"<td>Slno</td>
     <td>Feed Stock</td>
     <td>Feed Stock Source</td>
     <td>No of Sources</td>
     <td>Weight</td></tr>";
 $n=1;
 $sql_fstock="SELECT bio_feedstocks.feedstocks,
                     bio_fs_feedstockdetails.gasout,
                     bio_fs_feedstockdetails.weight,
                     bio_fs_feedstockdetails.number_source,
                     bio_feedstocksources.feedstocksourcename,
                     bio_fssources.source 
                FROM bio_fs_feedstockdetails,
                     bio_feedstocks,
                     bio_feedstocksources,
                     bio_fssources
               WHERE bio_feedstocks.id=bio_fs_feedstockdetails.feedstockid
                 AND bio_fs_feedstockdetails.feedstocksourceid=bio_feedstocksources.feedstocksourceid
                 AND bio_fssources.id=bio_fs_feedstockdetails.feedstocksourceid
                 AND bio_fs_feedstockdetails.leadid=".$leadid;
                 
 $result_fstock=DB_query($sql_fstock, $db);    
 while($myrow=DB_fetch_array($result_fstock)){
    echo "<tr style='background:#000080;color:white'>
        <td>$n</td>
        <td>$myrow[0]<input type='hidden' id='hfeedstock' value='$myrow[0]'></td>
        <td>$myrow[5]<input type='hidden' id='hfeedsource' value='$myrow[5]'></td>
        <td>$myrow[3]<input type='hidden' id='hfeedsourceno' value='$myrow[3]'></td>
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

echo"<tr><td>Total Feedstocks</td>";
echo"<td>:<input type='hidden' name='actual' id='actual' value='".$myrow_fsedit['actual_feedstock']."'>".$myrow_fsedit['actual_feedstock']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Total Gas</td>";
echo"<td>:<input type='hidden' name='TotalGas' id='totalgas' value='".$myrow_fsedit['total_gas']."'>".$myrow_fsedit['total_gas']."</td></tr>";
echo"</table>";
echo"</fieldset>";
echo"</div>";
echo"</td></tr>"; 


  $sql_proptmp="DELETE FROM bio_temppropitems WHERE leadid=".$leadid;
  $result_proptmp=DB_query($sql_proptmp,$db);
  
  
  $sql_prop="SELECT * FROM bio_conceptproposaldetails
             WHERE cp_id=".$cp_ID;
  $result_prop=DB_query($sql_prop,$db);
  while($myrow_prop=DB_fetch_array($result_prop)){
      $sql_sub="SELECT SUM(amount) FROM bio_cpsubsidy
                WHERE cp_id=$cp_ID
                AND leadid=$leadid
                AND stockid='".$myrow_prop['stockid']."'";
      $result_sub=DB_query($sql_sub,$db);
      $myrow_sub=DB_fetch_array($result_sub);
      if($myrow_sub[0]>0){
          
      $subsidy=$myrow_sub[0];
      $netprice=$myrow_prop['price']-$subsidy;
      }else{
          $subsidy=0;
          $netprice=$myrow_prop['price'];
          
      }
      
      
      
      $sql3="INSERT INTO bio_temppropitems (stockid,
                                            description,
                                            qty,
                                            price,
                                            tprice,
                                            leadid,
                                            subsidy,
                                            netprice) 
                                 VALUES ('".$myrow_prop['stockid']."',
                                         '".$myrow_prop['description']."',
                                         '".$myrow_prop['qty']."',
                                         '".$myrow_prop['price']."',
                                         '".$myrow_prop['tprice']."',
                                          ".$leadid.",
                                          ".$subsidy.",
                                          ".$netprice.")";
      $result3=DB_query($sql3,$db);
      
  }
  
$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$leadid;
$result4=DB_query($sql4,$db);
//$count=DB_fetch_row($result4);
echo "<table width=700px>";
echo "<tr><td colspan='2'>";
echo "<div id='sellist' style='background: #D6DEF7;'>";
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$leadid."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Price</th><th>Total Price</th><th></th></tr><tbody>";
$k=0;

while ($myrow4=DB_fetch_array($result4))   {
$tprice=$myrow4['qty']*$myrow4['price'];
$k++;
$stock[$k]=$myrow4['stockid'];
$item=$myrow4['stockid'];
//$item=urlencode($myrow4['stockid']);
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$leadid.",\"$stock[$k]\")></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$leadid.",\"$stock[$k]\")></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." readonly></td>
       <td align=center><a  style='cursor:pointer;'  id='$leadid' onclick='addSubsidy($leadid,\"$item\",2,1)'>" . _('Manage Subsidy') . "</a></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='removeitm($leadid,\"$item\",0,1)'>" . _('Remove') . "</a></td>


       </tr>";
}

echo"<input type='hidden' name='CpID' id='cpid' value='$cp_ID'>"; 

echo "<tr><td colspan=2>
          <input type='button' id=\"".$leadid."\" value='Add Item'  onclick='selectplant(this.id,0);'>
       </td></tr>";
//echo "<td colspan=3>
//          <input type='submit' name='Editcp' id=\"".$leadid."\"  value='Save Concept Proposal'>
//       </td></tr>";

       
 echo "</tbody></table>";
 
 echo "</div>";
echo "</td></tr>";
echo"<table>";

?>
