<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $title = _('View Lead Details');  
include('includes/header.inc');
include('includes/sidemenu.php'); 

  $leadid=$_GET['q'];
//   if(isset($_POST['edaddfeedstock'])){echo $sql="INSERT INTO bio_leadfeedstocks VALUES ('".$leadid."','".$_GET['feedstock']."','".$_GET['weight']."')";
  //$result=DB_query($sql, $db);  
//  exit;
//             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
//           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
//           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);  }
  //echo "hiiii";
  
 if(isset($_POST['edit']) and isset($_POST['leadid']))
 {    // $_POST['code'];
//    $sourcetype=$_POST['sourcetype'];
   $leadid= $_POST['leadid']; 
// $_POST['customerid'];
 $leadstatus=$_POST['ChangeStatus'];
 $remarks=$_POST['remarks'];
 if($remarks==""){
    //$remarks=$_POST['oldremarks'];
     //if($remarks==""){
        $remarks=="";
     //}
 }
    
    // echo "hi";

  //echo $_POST['advanceamt'];

   //$sql="UPDATE `bio_cust` SET`custname` = '".$_POST['custname']."',
//        `houseno` = '".$_POST['Houseno']."',      
//        `housename` ='".$_POST['HouseName']."',
//        `area1` = '".$_POST['Area1']."',      
//        `area2` ='".$_POST['Area2']."',
//        `pin` = '".$_POST['Pin']."',      
//        `custphone` = '".$_POST['code']."-".$_POST['phone']."',     
//        `custmob` = ".$_POST['mobile'].",
//        `custmail` = '".$_POST['email']."' WHERE `bio_cust`.`cust_id` ='".$_POST['customerid']."'";         
//           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);


$sql="UPDATE `bio_leads` SET
            remarks='".$remarks."',  
            leadstatus = '".$leadstatus."' WHERE `bio_leads`.`leadid` =$leadid";
      //  $result=DB_query($sql,$db);
                    $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been Updated'),'success');
      // display($db);
       unset($leadid);
//unset($_POST['enquiry']);
//unset($_POST['outputtype']);
//unset($_POST['sourcetype']);
//unset($_POST['printsource']);
//unset($_POST['feedstock']);     unset($_POST['productservices']);             unset($_POST['District']);
//  header("Location: bio_viewleads.php");
 } 
  
  
  
  
  
  
  
 if($leadid!='')
 {   // echo  $leadid; 
    $sql="SELECT * FROM bio_leads WHERE leadid=".$leadid;
 
    $result=DB_query($sql,$db);
      $myrow=DB_fetch_array($result); 
  
      $rmdailykg=$myrow['rmdailykg'];
      $advanceamount=$myrow['advanceamount'];
      $productservicesid=$myrow['productservicesid'];
      $enqtypeid=$myrow['enqtypeid']; 
      $leadstatus=$myrow['leadstatus']; 
  //    $feedstockid=$myrow['feedstockid']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $outputtypeid=split(',',$outputtypeid);
      $typecount=count($outputtypeid);
      
      $schemeid=$myrow['schemeid'];
       $schemeid=split(',',$schemeid);
       $count99=count($schemeid);
//      for($i=0;$i<=$count-1;$i++){
//            $sch=$schemeid[$i];
//      $sql99="SELECT scheme FROM bio_schemes WHERE schemeid=$sch";
//      $result99=DB_query($sql99,$db);  
//      while($myrow99=DB_fetch_array($result99)){
//          echo $a[$i]=$myrow99[0].",";
//      }
//       
//          
//      }
//      $schemeid=$myrow['schemeid'];              
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
if($custphone==""){$custphone='';}if($custmobile==""){$custmobile=0;} if($custemail==""){$custemail=0;}


//==========================================================bio_feedstocks.feedstocks, AND bio_feedstocks.id=$feedstockid
       
       
        $sq4="SELECT bio_enquirytypes.enquirytype,
         
        
        bio_outputtypes.outputtype,
        bio_schemes.scheme,
        bio_productservices.productservices,
        bio_leadsources.sourcetypeid,
        bio_leadsourcetypes.leadsourcetype,
        stockcategory.categorydescription
        FROM bio_enquirytypes, 
        bio_feedstocks, 
        bio_outputtypes,         
        bio_schemes,
        bio_productservices,
        bio_leadsources,
        bio_leadsourcetypes,stockcategory
        WHERE bio_enquirytypes.enqtypeid=$enqtypeid  AND bio_outputtypes.outputtypeid='$outputtypeid' AND stockcategory.categoryid='$productservicesid' AND bio_leadsources.id=$sourceid AND bio_leadsourcetypes.id=bio_leadsources.sourcetypeid";
      $result4=DB_query($sq4,$db); 
      $myrow4=DB_fetch_array($result4);
//       echo $myrow4[0];
//       echo $myrow4[6];
      // echo  $custname;
//       echo '<center><h2>Leads</h2></center>';

    echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">LEAD DETAILS</font></center>';
    
    
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
     
     echo"<table><tr><td>";
     echo "<div id=editleads>";  



     echo "<table border=0 style='width:785px;height:200%;'>";

    echo "<tr><td style='width:50%'>";
      echo "<fieldset style='float:left;width:95%;height:450px' valign='top'>";     
    echo "<legend><h3>Customer Details</h3>";
    echo "</legend>";
    echo "<table>";  
    //Customer Details
   echo"<b>";
    if($_GET['en']==1){
        echo "<tr><td>Enquiry Type</td><td>:Domestic</td></tr>";
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type=hidden name=custname id=custid value='$custname'  style='width:96%'>:$custname</td>";
        echo "<tr><td>House No:</td><td><input type='hidden' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'>:$homno</td></tr>";
    echo"<tr></tr>";
        echo "<tr><td>House Name</td><td><input type='hidden' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'>:$housename</td></tr>";
    echo"<tr></tr>";        
        echo "<tr><td>Residential Area:</td><td><input type='hidden' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'>:$area1</td></tr>";  }
        
        
            if($_GET['en']==2){$enqtypeid=$_GET['en'];
              echo "<tr><td>Enquiry Type</td><td>:Institution</td></tr>";
                  echo"<tr></tr>";
             echo "<tr><td style='width:50%'>Contact Name</td>";
    echo "<td><input type='hidden' name='custname' id='custid' value='$custname' readonly onkeyup='caps1()'  style='width:190px'>:$custname</td></tr>";
    echo"<tr></tr>";

 //       echo "<tr><td>House No:</td><td><input type='text' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>Org Name:</td><td><input type='hidden' name='HouseName' id='HouseName' value='$housename' onkeyup='' style='width:99%'>:$housename</td></tr>";
        echo"<tr></tr>";
        echo "<tr><td>Org Area:</td><td><input type='hidden' name='Area1' id='Area1' onkeyup='' value='$area1' style='width:99%'>:$area1</td></tr>";
        echo"<tr></tr>";
  }
  
      if($_GET['en']==3){
            echo "<tr><td>Enquiry Type</td><td>LSGD</td></tr>";
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type=hidden name=custname id=custid value='$custname' readonly style='width:96%'>$custname:</td>";
        echo "<tr><td>House No:</td><td><input type='hidden' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'>:$homno</td></tr>";    
        echo "<tr><td>House Name</td><td><input type='hidden' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'>:$housename</td></tr>";    
        echo "<tr><td>Residential Area:</td><td><input type='hidden' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'>:$area1</td></tr>";  }
        echo"<tr></tr>";  
        echo "<tr><td>Post Box:</td><td><input type='hidden' name='Area2' value='$area2' id='Area2' onkeyup='' style='width:99%'>:$area2</td></tr>";
        echo"<tr></tr>";    
                echo" <tr><td>Pin:</td><td><input type='hidden' name='Pin' id='Pin' value='$pin' onkeyup='' style='width:99%'>:$pin</td></tr>";  
            $sql="SELECT * FROM bio_country WHERE bio_country.cid=$nationality";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $nid=$myrow[0]; 
          $nationality=$myrow[1];    
  
                 }
         echo"<tr></tr>";           
        echo "<tr><td>Country:</td><td><input type='hidden' name='country' value='$nid' id='Country' onkeyup='' style='width:99%'>:$nationality</td></tr>"; 
            $sql="SELECT * FROM bio_state WHERE stateid=$state AND bio_state.cid=$nid";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $sid=$myrow[0]; 
          $state=$myrow[3];    
    $stateid1=$myrow[2];   
                 }
        echo"<tr></tr>";   
        echo "<tr><td>State:</td><td><input type='hidden' name='State' id='State' value='$sid' onkeyup='' style='width:99%'>:$state</td></tr>"; 
        
          $sql="SELECT * FROM bio_district WHERE did=$district AND stateid=$stateid1 AND cid=$nid";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $sid=$myrow[2];    $did=$myrow[3];
          $district=$myrow[4];    
                        
                 }
        echo"<tr></tr>";             
        echo "<tr><td>District:</td><td><input type='hidden' name='District' id='District' value='$did' onkeyup='' style='width:99%'>:$district</td>";       
    echo '</tr>';
    echo"<tr></tr>";    
echo '<tr><td>Phone number</td>';
    echo "<td><input type='hidden' name='code' id='code' value='$custcode' style='width:50px'>:$custcode<input type=hidden name=phone id=phone value='$custphone' style='width:96%'> - $custphone</td></tr>";
//    <td><input type=hidden name=phone id=phone value='$custphone' style='width:96%'>$custphone</td></table></td></tr>";
    echo"<tr></tr>";
    echo '<tr><td>Mobile Number</td>';
    echo "<td><input type=hidden name=mobile id=mobile value='$custmobile' style='width:96%'>:$custmobile</td></tr>";
    if($custemail==0){
      $custemail="";
    }
    echo"<tr></tr>";
    echo '<tr><td>Email id</td>';
    echo "<td><input type=hidden name=email id=email value='$custemail' style='width:96%'>:$custemail</td></tr>";
    //Product Sevices
    echo"<tr></tr>";
      echo '<tr><td>Product Services</td><td>';  
  $sql1="SELECT * FROM bio_productservices WHERE id=".$productservicesid;
  $result1=DB_query($sql1, $db);
  $myrow1=DB_fetch_array($result1);
  echo '<input type="hidden" name=productservices style="width:190px" value='.$productservicesid.'>:'.$myrow1['productservices'].'';
  /*
  echo '<select name=productservices style="width:190px">';
  while($myrow1=DB_fetch_array($result1))
  {     
  if ($myrow1['id']==$productservicesid)  
    { 
    echo '<option selected value="';
    echo $productservicesid . '">'.$myrow1['productservices'];
    } else {
        echo '<option value="';
        echo $myrow1['id'] . '">'.$myrow1['productservices'];
    }
    
    echo '</option>';
   } 
  echo '</select>'; 
  */
  
  echo '</td>'; 
  echo '</tr>';

   
  //Enquiry type
//    echo '<tr><td>Enquiry Type</td>';
//    echo  '<td>';
//    echo '<select name="enquiry" style="width:190px">';
//    $sql1="SELECT * FROM bio_enquirytypes"; 
//    $result1=DB_query($sql1,$db);
//    while($myrow1=DB_fetch_array($result1))
//    { 
//    if ($myrow1['enqtypeid']==$enqtypeid) 
//    {
//       
//    echo '<option selected value="';
//    echo $enqtypeid . '">'.$myrow4['enquirytype'];

//    } else {
//        echo '<option value="';
//        echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
//    }
//    
//    echo '</option>';
//    }
//     
//    echo '</select>';    
//    echo '</td></tr>';

    
    
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

  

    //Raw Materials
//  echo '<tr><td>Raw Materials(Kg.)</td>';
//  echo "<td><input type=text name=rmkg value='$rmdailykg' style='width:96%'></td></tr>"; 
  //Advance amount
  echo"<tr></tr>";
  echo '<tr><td>Advance Amount</td>';
    echo "<td><input type=hidden name=advanceamt id=advance value='$advanceamount'  style='width:96%'>:$advanceamount</td></tr>";
  echo "</table>";  
  echo"<div style='height:75px'></div>";
  echo "</fieldset>"; 
  ///////////////
   // echo '</div>';
  echo "</td>";
  
  
   //Leads details fieldset .................................Leads details fieldset.....................Leads details fieldset 
   
   
  echo "<td style='width:55%'>";
 //echo "<div>";
  echo "<fieldset style='float:left;width:95%;height:450px'>";       
  echo "<legend><h3>Leads Details</h3>";
  echo "</legend>";
  
  echo "<table border=0 style='width:100%'>";
  
   //output types
  echo"<tr></tr>";
  
  for($i=0;$i<=$typecount-1;$i++){
        $outputtypeid1=$outputtypeid[$i];
        $sql1="SELECT * FROM bio_outputtypes WHERE outputtypeid=".$outputtypeid1;
        $result1=DB_query($sql1, $db);
        $myrow1=DB_fetch_array($result1);
        $output=$myrow1[1].",".$output;
    }
    $output = substr($output,0,-1);  
  echo '<tr><td>Output Type</td><td>:'.$output.'</td></tr>';
  
 /* $sql1="SELECT * FROM bio_outputtypes WHERE outputtypeid=".$outputtypeid;
  $result1=DB_query($sql1, $db);
  $myrow1=DB_fetch_array($result1);
  echo '<input type="hidden" name=outputtype style="width:190px" value='.$outputtypeid.'>:'.$myrow1['outputtype'].'';
  echo '</td>';
  echo '</tr>';*/
  
  
  
  
 /*   echo '<tr><td>LeadSource Type</td>';
  echo '<td>';
 
  $sql="SELECT * FROM `bio_leadsourcetypes`";
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
  $myrow = DB_fetch_array($result);
  echo "<input type=hidden name=sourcetype id=sourcetype value='$sourceid'  style='width:90%'>:".$myrow1['sourcename']."</td>";
 
  
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
    echo '</select>
    */
    echo'</td></tr>';

   $d=9; 
   
//   echo '<tr><td colspan=2><div  id=hidetr>' ;
   echo '<tr id=showsource>'; 
   
 //--------------------------------------------------------------------------------  
//         echo $sourcetypeid=$myrow4['sourcetypeid'] ; echo $sourceid ; 
    echo"<tr></tr>"; 
    echo '<td>LeadSource</td><td>';
    $sql1="SELECT id,sourcename, sourcetypeid
FROM bio_leadsources
WHERE bio_leadsources.id=".$sourceid;
   $result1=DB_query($sql1,$db) ;

   $myrow1 = DB_fetch_array($result1); 
    echo "<input type=hidden name=source id=source value='$sourceid'  style='width:90%'>:".$myrow1['sourcename']."</td>";
 
  /* 
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
    echo '</select>';
    */
    //echo '</td>';
    echo '</tr>';
   
   
//-----------------------------------------------------------------------------------------------   
   
   

   
   echo "<tr><td colspan=2 style='width:44%;align=left;'>";
   

   echo '<div id="dinhide">';
   
   echo '<div id=sourcedetails class=sourcedetails>';    
   echo '</div>'; 
   
   echo '</div>'; 
//   echo '</td></tr>';  
    echo '</tr>';
     
   //  echo '</div></tr>' ;
   // echo '<td>';
     

    echo"<tr></tr>";
    echo '<tr><td>Investment Size</td>';
    echo "<td><input type=hidden name=investmentsize id=invest value='$investmentsize'  style='width:90%'>:$investmentsize</td>";
    echo '</tr>';
    
     //Scheme
     if($schemeid[0]!=""){
      for($i=0;$i<=$count99-1;$i++){
        $sch=$schemeid[$i];
        $sql99="SELECT scheme FROM bio_schemes WHERE schemeid=$sch";
        $result99=DB_query($sql99,$db);  
        $myrow99=DB_fetch_array($result99);
        $schm=$myrow99[0].",".$schm;
    }
    $schm = substr($schm,0,-1);   
     }else{
        $schm='No Schemes.'; 
     }
    
    echo"<tr></tr>";
    echo '<tr><td>Scheme</td><td>:'.$schm.'</td>';   
//     $sql1="SELECT scheme
//FROM bio_schemes,bio_leads
//WHERE bio_schemes.schemeid=bio_leads.schemeid AND bio_leads.leadid=".$leadid;
//   $result1=DB_query($sql1,$db) ;
//    echo $myrow1 = DB_fetch_array($result1);
//    split(",", $passwd_line, 5);    
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
    /*  
    echo '<tr><td>Status</td>';
    echo "<td><input type=text name='status' value='$status' style='width:90%'></td>";
    echo '</tr>';
    */
    //Remarks
    echo"<tr></tr>";
    //echo '<tr><td>Remarks</td>'; 
//    echo "<td><input type='hidden' name='remarks' value='$remarks'>:$remarks</td>"; 
//    echo '</tr>';
 /* 
  echo '<tr><td>Remarks</td>'; 
    echo "<td><textarea name='oldremarks' id='oldremarks' rows=4 cols=25 style='resize:none';>$remarks</textarea></td>"; 
    echo '</tr>';
  
   */
   
    echo '<tr><td>Remark</td>'; 
    echo "<td><textarea name='remarks' id='remarks' rows=4 cols=25 style='resize:none';>$remarks</textarea></td>"; 
    echo '</tr>';  
     
    
    echo"<tr></tr>";
    echo '<tr><td>Change Status</td>'; 
    echo'<td><select name="ChangeStatus" id="changestatus" style="width:192px">';
    $sql1="SELECT * FROM bio_status";
    $result1=DB_query($sql1, $db); 
    $f=0;
    while($myrow1=DB_fetch_array($result1))
    {
    if ($myrow1['statusid']==$leadstatus)
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
    echo $myrow1['statusid'] . '">'.$myrow1['biostatus'];  
    echo '</option>';
  }
  echo'</select></td></tr>';
 /*   
  echo '<tr><td>Select a Remark for Status change</td>';
    echo'<td>';
    echo '<select name="RemarkList" id="remarklist" style="width:192px">';
        echo '<option value="" selected></option>';
  $sql="SELECT * FROM bio_remarks";
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
      
  while ($myrow = DB_fetch_array($result)) {

    echo '<option value="';
    echo $myrow['remark'] . '">'.$myrow['remark'];     
    echo '</option>';

    }         
    echo '</select>';
    
    echo'</td>';
    echo'</tr>';
   */
    
   
    echo "</table>"; 
  
  //echo '</form>'; 
  echo "<input type=hidden name='leadid' id='leadid' value='$leadid'>";
//     echo  $leadid;
     echo "</fieldset>";   
     echo '</td>';  
     echo '</tr>';
     echo"</b>";

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
echo"</div>";
//echo"<table>";
    echo '<tr><td colspan="2"><center><input type="submit" name="edit" id="editleads" value="Enter"  onclick="return log_in()"></center></td></tr></table>';


echo "</td></tr></table></td></tr></table>";
echo '</form>';         

}


 
?>
<script type="text/javascript">
/*function displayVals() {

//     alert("sss");
      var multipleValues = document.getElementById("remarklist").value;
      if(multipleValues!=""){
          $('#oldremarks').hide(); 
      }
//      alert(multipleValues);
        document.getElementById("remarks").value=multipleValues;

    }
   
    
  $("#remarklist").change(displayVals);
    displayVals();
*/
</script>