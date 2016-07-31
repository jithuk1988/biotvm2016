<?php
  $PageSecurity = 80;
  include('includes/session.inc'); 

  $leadid=$_GET['q'];
//   if(isset($_POST['edaddfeedstock'])){echo $sql="INSERT INTO bio_leadfeedstocks VALUES ('".$leadid."','".$_GET['feedstock']."','".$_GET['weight']."')";
  //$result=DB_query($sql, $db);  
//  exit;
//             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
//           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
//           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);  }
  //echo "hiiii";
 if($leadid!='')
 {   // echo  $leadid; 
    $sql="SELECT * FROM bio_leads WHERE leadid=".$leadid;
 
    $result=DB_query($sql,$db);
      $myrow=DB_fetch_array($result); 
  
      $rmdailykg=$myrow['rmdailykg'];
      $advanceamount=$myrow['advanceamount'];
      $productservicesid=$myrow['productservicesid'];
      $enqtypeid=$myrow['enqtypeid']; 
  //    $feedstockid=$myrow['feedstockid']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $schemeid=$myrow['schemeid'];
      $teamid=$myrow['teamid'];
      $sourceid=$myrow['sourceid'];
      $investmentsize=$myrow['investmentsize'];
      $remarks=$myrow['remarks'];
      $status=$myrow['status']; 
      
              echo "<input type='hidden' name='customerid' value='".$myrow['cust_id']."'>";      
          $sqledt="SELECT * FROM bio_cust WHERE bio_cust.cust_id=".$myrow['cust_id'];
     $result1=DB_query($sqledt,$db);
     $myrow1=DB_fetch_array($result1); 
      $custname=$myrow1['custname'];    
      $homno=$myrow1['houseno'];
      $housename=$myrow1['housename']; 
      $area1=$myrow1['area1'];
      $area2=$myrow1['area2'];       //echo "ssssssssss".$area2."sssssssssssnn";    
      $pin=$myrow1['pin'];
      $nationality=$myrow1['nationality']; 
      $state=$myrow1['state'];
      $district=$myrow1['district'];   
      //echo $custaddress;  $custphone
    $pieces=$myrow1['custphone'];  $phpieces = split("-", $pieces,2);  $custcode=$phpieces[0];if($custcode==0 || $custcode==""){$custcode=0;}  $custphone=$phpieces[1];
    $custmobile=$myrow1['custmob'];
    $custemail=$myrow1['custmail'];
//--------------------------------------------------------
if($custphone==""){$custphone=0;}if($custmobile==""){$custmobile=0;} if($custemail==""){$custemail=0;}


//==========================================================bio_feedstocks.feedstocks, AND bio_feedstocks.id=$feedstockid
       
        $sq4="SELECT bio_enquirytypes.enquirytype,
         
        
        bio_outputtypes.outputtype,
        bio_schemes.scheme,
        bio_productservices.productservices,
        bio_leadsources.sourcetypeid,
        bio_leadsourcetypes.leadsourcetype
        FROM bio_enquirytypes, 
        bio_feedstocks, 
        bio_outputtypes,         
        bio_schemes,
        bio_productservices,
        bio_leadsources,
        bio_leadsourcetypes
        WHERE bio_enquirytypes.enqtypeid=$enqtypeid  AND bio_outputtypes.outputtypeid=$outputtypeid AND bio_productservices.id=$productservicesid AND bio_leadsources.id=$sourceid AND bio_leadsourcetypes.id=bio_leadsources.sourcetypeid";
      $result4=DB_query($sq4,$db); 
      $myrow4=DB_fetch_array($result4);
//       echo $myrow4[0];
//       echo $myrow4[6];
      // echo  $custname;
//       echo '<center><h2>Leads</h2></center>';
     echo "<table border=0 style='width:100%;height:150%;'>";

    echo "<tr><td style='width:50%'>";
      echo "<fieldset style='float:left;width:95%;height:auto'>";     
    echo "<legend><h3>Customer Details</h3>";
    echo "</legend>";
    echo "<table>";  
    //Customer Details
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type=text name=custname id=custid value='$custname' readonly style='width:96%'></td>";
        echo "<tr><td>House No:</td><td><input type='text' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>House Name</td><td><input type='text' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>Residential Area:</td><td><input type='text' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>Post Box:</td><td><input type='text' name='Area2' value='$area2' id='Area2' onkeyup='' style='width:99%'></td></tr>";    
                echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' value='$pin' onkeyup='' style='width:99%'></td></tr>";  
            $sql="SELECT * FROM bio_country WHERE bio_country.cid=$nationality";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $nid=$myrow[0]; 
          $nationality=$myrow[1];    
  
                 }   
        echo "<tr><td>Country:</td><td><input type='hidden' name='country' value='$nid' id='Country' onkeyup='' style='width:99%'>$nationality</td></tr>"; 
            $sql="SELECT * FROM bio_state WHERE stateid=$state";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $sid=$myrow[0]; 
          $state=$myrow[2];    
    
                 }
           
        echo "<tr><td>State:</td><td><input type='hidden' name='State' id='State' value='$sid' onkeyup='' style='width:99%'>$state</td></tr>"; 
        
          $sql="SELECT * FROM bio_district WHERE did=$district";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $sid=$myrow[0];    $did=$myrow[1];
          $district=$myrow[2];    
                        
                 }    
        echo "<tr><td>District:</td><td><input type='hidden' name='District' id='District' value='$did' onkeyup='' style='width:99%'>$district</td>";       
    echo '</tr>';    
echo '<tr><td>Phone number</td>';
    echo "<td><table><td><input type='text' name='code' id='code' value='$custcode' style='width:50px'></td>
    <td><input type=text name=phone id=phone value='$custphone' style='width:96%'></td></table></td></tr>";
    echo '<tr><td>Mobile Number</td>';
    echo "<td><input type=text name=mobile id=mobile value='$custmobile' style='width:96%'></td></tr>";
    echo '<tr><td>Email id</td>';
    echo "<td><input type=text name=email id=email value='$custemail' style='width:96%'></td></tr>";
    //Product Sevices
      echo '<tr><td>Product Services</td><td>';  
  $sql1="SELECT * FROM bio_productservices";
  $result1=DB_query($sql1, $db);
  echo '<select name=productservices style="width:190px">';
  while($myrow1=DB_fetch_array($result1))
  {     
  if ($myrow1['id']==$productservicesid)  
    { 
    echo '<option selected value="';
    echo $productservicesid . '">'.$myrow4['productservices'];
    } else {
        echo '<option value="';
        echo $myrow1['id'] . '">'.$myrow1['productservices'];
    }
    
    echo '</option>';
   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';

   
  //Enquiry type
    echo '<tr><td>Enquiry Type</td>';
    echo  '<td>';
    echo '<select name="enquiry" style="width:190px">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$enqtypeid) 
    {
       
    echo '<option selected value="';
    echo $enqtypeid . '">'.$myrow4['enquirytype'];

    } else {
        echo '<option value="';
        echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    }
    
    echo '</option>';
    }
     
    echo '</select>';    
    echo '</td></tr>';

    
    
      //Feedstock
//    echo '<tr><td>Feed Stock</td><td>';
//     
//  $sql1="SELECT * FROM bio_feedstocks";
//  $result1=DB_query($sql1, $db);
//  echo '<select name=feedstock style="width:190px">';
//  while($myrow1=DB_fetch_array($result1))
//  {  
//  if ($myrow1['id']==$feedstockid) 
//    {
//    echo '<option selected value="';
//    echo $feedstockid . '">'.$myrow4['feedstocks'];
//    } 
//    else
//     {
//        echo '<option value="';
//        echo $myrow1['id'] . '">'.$myrow1['feedstocks'];
//    }
//     
//    echo '</option>' ;
//   }
//  echo '</select></td>';
//  echo '</tr>';

   //output types
  echo '<tr><td>Output Type</td><td>';
  $sql1="SELECT * FROM bio_outputtypes";
  $result1=DB_query($sql1, $db);
  echo '<select name=outputtype style="width:190px">';
  while($myrow1=DB_fetch_array($result1))
  {  
  if($myrow1['outputtypeid']==$outputtypeid) 
    {
    echo '<option selected value="';
    echo $outputtypeid . '">'.$myrow4['outputtype'];
    
    } else 
    {
        echo '<option value="';
        echo $myrow1['outputtypeid'] . '">'.$myrow1['outputtype'];
    }
      
    echo '</option>';
  }
  echo '</select></td>';
  echo '</tr>';

    //Raw Materials
//  echo '<tr><td>Raw Materials(Kg.)</td>';
//  echo "<td><input type=text name=rmkg value='$rmdailykg' style='width:96%'></td></tr>"; 
  //Advance amount
  echo '<tr><td>Advance Amount</td>';
    echo "<td><input type=text name=advanceamt id=advance value='$advanceamount' style='width:96%'></td></tr>";
  echo "</table>";  
  echo "</fieldset>"; 
  ///////////////
   // echo '</div>';
  echo "</td>";
  
  
   //Leads details fieldset .................................Leads details fieldset.....................Leads details fieldset 
   
   
  echo "<td style='width:55%'>";
 //echo "<div>";
  echo "<fieldset style='float:left;width:95%;height:453px'>";       
  echo "<legend><h3>Leads Details</h3>";
  echo "</legend>";
  
  echo "<table border=0 style='width:100%'>";
   echo '<tr><td>LeadSource Type</td>';
  echo '<td>'; 
  
//   $sql6="SELECT bio_leadsources.id, bio_sourcedetails.propertyvalue, bio_sourceproperty.property, bio_leadsourcetypes.id, bio_leadsourcetypes.leadsourcetype
//FROM `bio_leadsources` , bio_sourcedetails, bio_sourceproperty, bio_leadsourcetypes
//WHERE bio_leadsources.id = bio_leadsourcetypes.id
//AND bio_sourceproperty.sourcetypeid = bio_leadsources.id
//AND bio_sourceproperty.sourcepropertyid = bio_sourcedetails.sourcepropertyid
//AND bio_sourceproperty.sourcepropertyid = bio_leadsourcetypes.id
//AND bio_leadsources.teamid =".$teamid;
//  $result6=DB_query($sql6,$db);
//  $myrow6 = DB_fetch_array($result6);
  echo '<select name=sourcetype id="sourcetype" style="width:192px" onkeyup=showCD(this.value) onchange=showCD(this.value)>';
  $sql="SELECT * FROM `bio_leadsourcetypes`";
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
 //echo   $teamid;
  
  while ($myrow = DB_fetch_array($result)) {
     $c=0;
    if ($myrow['id']==$myrow4['sourcetypeid']) 
    {
    echo '<option selected value="';
    echo $myrow4['sourcetypeid'] . '">'.$myrow4['leadsourcetype'];
    } else {
        echo '<option value="';
        echo $myrow['id'] . '">'.$myrow['leadsourcetype'];
    }
         
   echo '</option>';
   $c++;
    }         
    echo $c;
    echo '</select></td></tr>';

   $d=9; 
   
//   echo '<tr><td colspan=2><div  id=hidetr>' ;
   echo '<tr id=showsource>'; 
   
 //--------------------------------------------------------------------------------  
//         echo $sourcetypeid=$myrow4['sourcetypeid'] ; echo $sourceid ; 
     echo '<td>LeadSource</td><td>'; 
   
   echo '<select name=source id=source style="width:192px" onkeyup=showCD1(this.value) onchange=showCD1(this.value) onclick=showCD1(this.value)>';

   $sql1="SELECT id,sourcename, sourcetypeid
FROM bio_leadsources
WHERE bio_leadsources.id=".$sourceid;
   $result1=DB_query($sql1,$db) ;

   while ($myrow1 = DB_fetch_array($result1)) {
    if ($myrow1['id']==$_POST['source']) 
    {
    echo '<option selected value="';
    echo $myrow1['id'] .'">'.$myrow1['sourcename'];
    
    } else {


        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['sourcename'];
        
    }
    

    echo  '</option>';
    }
    echo '</select></td>';
   
   
   
//-----------------------------------------------------------------------------------------------   
   
   
   echo '</tr>';
   echo "<tr><td colspan=2 style='width:44%;align=left;'>";
   

   echo '<div id="dinhide">';
   
   echo '<div id=sourcedetails class=sourcedetails>';    
   echo '</div>'; 
   
   echo '</div>'; 
//   echo '</td></tr>';  
    echo '</tr>';
     
   //  echo '</div></tr>' ;
   // echo '<td>';
     


    echo '<tr><td>Investment Size</td>';
    echo "<td><input type=text name=investmentsize id=invest value='$investmentsize'  style='width:90%'></td>";
    echo '</tr>';
    
     //Scheme
    
    echo '<tr><td>Scheme</td><td>';
     $sql1="SELECT scheme
FROM bio_schemes,bio_leads
WHERE bio_schemes.schemeid=bio_leads.schemeid AND bio_leads.leadid=".$leadid;
   $result1=DB_query($sql1,$db) ;
//    echo $myrow1 = DB_fetch_array($result1);
    split(",", $passwd_line, 5);
//$sql="SELECT * FROM bio_schemes";
//  $result=DB_query($sql, $db);
//  echo '<select name=scheme style="width:192px">';
//  
//  while($myrow=DB_fetch_array($result))
//  {  
//  if ($myrow['schemeid']==$schemeid)
//    {
//        echo '<option selected value="';
//        echo $schemeid . '">'.$myrow4['scheme'];
//    }
//  else
//   {       
//        echo '<option value="';
//        echo $myrow['schemeid'] . '">'.$myrow['scheme'];
//   }
//          
//     echo '</option>';
//  }
//    echo '</select></td>';
//    echo '</tr>'; 
//  
      //Status 
    echo '<tr><td>Status</td>';
    echo "<td><input type=text name='status' value='$status' style='width:90%'></td>";
    echo '</tr>';
    //Remarks
    echo '<tr><td>Remarks</td>'; 
    echo "<td><textarea name='remarks' rows=4 cols=25 style='resize:none';>$remarks</textarea></td>"; 
    echo '</tr>';
    
    echo "</table>"; 
  
  //echo '</form>'; 
  echo "<input type=hidden name='leadid' id='leadid' value='$leadid'>";
//     echo  $leadid;
     echo "</fieldset>";   
     echo '</td>';  
     echo '</tr>';
     echo"</table>";
 //--------------------------------------------------------------------------------------------------------    
echo"<div id='shadd'>";
     echo "<table style='align:left'  border=0  >";
  echo "<tr><td>Feed Stock</td>";
//Feedstock
    echo '<td>';

 $sql1="SELECT * FROM bio_feedstocks";
  $result1=DB_query($sql1, $db);
  echo '<select name="feedstockad" id="feedstockad" style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['feedstock']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
  echo "</td>";
  echo "<td>Weight in Kg</td>";
  echo "<td><input type=text name='weightad' id='weightad' style='width:83%'></td>";
  echo "<td>";
 echo '<input type="button" name="edaddfeedstock" id="edaddfeedstock" value="Add" onclick="showCD9()">';
//  echo '<input type="button" name="addfeedstock" id="addfeedstock" value=Add>';
  echo "</td>";
  
  echo "</tr>";
  
  

echo "</table>"; 
//----------------------------
     
     
     

  echo"<table id='editact' style='width:65%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Feed Stock</td><td>Weight</td><td>Update</td></tr>";
      
$sql="SELECT bio_feedstocks.feedstocks,
             bio_leadfeedstocks.weight,bio_leadfeedstocks.feedstockid
      FROM bio_leadfeedstocks,bio_feedstocks 
      WHERE bio_leadfeedstocks.leadid=$leadid AND
            bio_leadfeedstocks.feedstockid=bio_feedstocks.id";
$result1=DB_query($sql, $db);    
$n=1;
while($myrow=DB_fetch_array($result1)){
  echo "<tr style='background:#000080;color:white' >
  <td>$n</td>
  <td>$myrow[0]</td>
  <td>$myrow[1]</td>
  <td><a  style='color:white;cursor:pointer' name='".$leadid."' id='$myrow[2]' onclick='feedupdte1(this.id,this.name)'>Edit</a></td>
  </tr>";
 $n++;
}   echo "<tr id='edittedsho'></tr>";
echo"</table>";

echo"<table>";echo"</div>";
     echo '<tr><td colspan="2"><center><input type="submit" name="edit" id="editleads" value="Update"  onclick="return log_in()"></center></td></tr></table>';





  


  
   
  
 }
 
?>
