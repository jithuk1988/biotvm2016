<?php

$PageSecurity = 80;
include('includes/session.inc'); 
$leadid=$_GET['q'];

 if($leadid!='')
 {   // echo  $leadid; 
    $sql="SELECT * FROM bio_leads WHERE leadid=".$leadid;
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_array($result); 
  
      $rmdailykg=$myrow['rmdailykg'];
      $idtype=$myrow['id_type'];
      $idno=$myrow['id_no'];
      $advanceamount=$myrow['advanceamount'];
      $productservicesid=$myrow['productservicesid'];
      $enqtypeid=$myrow['enqtypeid']; 
  //    $feedstockid=$myrow['feedstockid']; 
      $outputtypeid=$myrow['outputtypeid']; 
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
      $contactperson=$myrow1['contactperson'];     
      $homno=$myrow1['houseno'];
      $housename=$myrow1['housename'];
      $area1=$myrow1['area1'];
      $area2=$myrow1['area2'];       //echo "ssssssssss".$area2."sssssssssssnn";    
      $pin=$myrow1['pin'];
      $_POST['nationality']=$myrow1['nationality']; 
      $_POST['state']=$myrow1['state'];
      $_POST['district']=$myrow1['district'];   
      $careof=$myrow1['careof']; 
      $taluk=$myrow1['taluk'];
      $LSGtypeid=$myrow1['LSG_type'];
      $LSG_name=$myrow1['LSG_name'];
      $block_name=$myrow1['block_name'];
      $ward=$myrow1['LSG_ward'];
      $village=$myrow1['village']; 
      //echo $custaddress;  $custphone
      
      if($homno == '0'){
         $homno=" "; 
      }
      if($housename == '0'){
         $housename=" ";
      }
      if($area1 == '0'){
         $area1=" "; 
      }
      if($area2 == '0'){
         $area2=" "; 
      }
      if($pin == '0'){
         $pin=" "; 
      }

    $pieces=$myrow1['custphone'];  $phpieces = explode("-", $pieces,2);  $custcode=$phpieces[0];  
     if($custcode==0){$custcode="";}

    $custphone=$phpieces[1];
    $custmobile=$myrow1['custmob'];
    $custemail=$myrow1['custmail'];
//--------------------------------------------------------
    if($custphone==0){$custphone="";}if($custmobile==0){$custmobile="";} if($custemail== '0'){$custemail="";}


//==========================================================bio_feedstocks.feedstocks, AND bio_feedstocks.id=$feedstockid
       
        $sq4="SELECT bio_enquirytypes.enquirytype,
                     bio_outputtypes.outputtype,
                     bio_schemes.scheme,
                     bio_productservices.productservices,

                     bio_leadsourcetypes.leadsourcetype,
                     stockcategory.categorydescription
                FROM bio_enquirytypes, 
                     bio_feedstocks, 
                     bio_outputtypes,         
                     bio_schemes,
                     bio_productservices,

                     bio_leadsourcetypes,stockcategory
               WHERE bio_enquirytypes.enqtypeid=$enqtypeid  
                 AND bio_outputtypes.outputtypeid='$outputtypeid' 
                 AND stockcategory.categoryid='$productservicesid' 

";
                 
      $result4=DB_query($sq4,$db); 
      $myrow4=DB_fetch_array($result4);
//       echo $myrow4[0];                     bio_leadsources,                 AND bio_leadsources.id=$sourceid                 AND bio_leadsourcetypes.id=bio_leadsources.                     bio_leadsources.sourcetypeid,sourcetypeid 
//       echo $myrow4[6];
      // echo  $custname;
//       echo '<center><h2>Leads</h2></center>';

    echo "<table border=0 style='width:100%;height:150%;'>";
    echo "<tr><td style='width:47%'>";
    echo "<fieldset style='float:left;width:95%;height:100%' valign='top'>";     
    echo "<legend><h3>Customer Details</h3>";
    echo "</legend>";
    echo "<table>";  
    //Customer Details

 if($_GET['en']==1){
    echo "<tr><td>Customer Type</td><td><input type='hidden' name='enquiry'  value=".$_GET['en']."  style='width:99%'>Domestic</td></tr>";
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type='text' value='$custname' name=custname id=custid onkeyup='caps1()' style='width:96%'></td>";
    echo "<tr><td style='width:50%'>Father's/ Husbands Name</td>";   
    echo "<td><input type='text' name='careof' id='careof' tabindex=2 onkeyup='caps1()' value='$careof' style='width:190px'></td>";
    echo "<tr><td>House No:</td><td><input type='text' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
    echo "<tr><td>House Name</td><td><input type='text' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";    
    echo "<tr><td>Residential Area</td><td><input type='text' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";  }
    //echo "<tr><td></td><td><input type='hidden' name='contactPerson' id='contactPerson' value='$contactperson' onkeyup='' style='width:99%'></td></tr>";  
   
        
 if($_GET['en']==2){$enqtypeid=$_GET['en'];
    echo "<tr><td>Customer Type</td><input type='hidden' name='enquiry'  value=".$_GET['en']."  style='width:99%'><td>Institution</td></tr>";
    echo "<tr><td style='width:50%'>Org Name</td>";
    echo "<td><input type='text' name='custname' id='custid' value='$custname' onkeyup='caps1()'  style='width:190px'></td>";
    echo "<tr><td>Contact Person</td><td><input type='text' name='contactPerson' id='contactPerson' value='$contactperson' onkeyup='' style='width:99%'></td></tr>";  
    echo "<tr><td>Building Name/No:</td><td><input type='text' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";
  //echo "<tr><td>Building Name/No:</td><td><input type='text' name='Houseno' id='Houseno' value='$homno' style='width:99%'></td></tr>";    
    echo "<tr><td>Org Street:</td><td><input type='text' name='HouseName' id='HouseName' value='$housename' onkeyup='' style='width:99%'></td></tr>";   
    echo "<tr><td>Org Area:</td><td><input type='text' name='Area1' id='Area1' onkeyup='' value='$area1' style='width:99%'></td></tr>";
  }
  
 if($_GET['en']==3){
    echo "<tr><td>Customer Type</td><input type='hidden' name='enquiry'  value=".$_GET['en']."  style='width:99%'><td>LSGD</td></tr>";
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type=text name=custname id=custid value='$custname' readonly style='width:96%'></td>";
    echo "<tr><td>House No:</td><td><input type='text' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
    echo "<tr><td>House Name</td><td><input type='text' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";    
    echo "<tr><td>Residential Area</td><td><input type='text' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";  
 }
//    echo "<tr><td></td><td><input type='hidden' name='contactPerson' id='contactPerson' value='$contactperson' onkeyup='' style='width:99%'></td></tr>";  

    echo "<tr><td>Post Box</td><td><input type='text' name='Area2' value='$area2' id='Area2' onkeyup='' style='width:99%'></td></tr>";    
    echo" <tr><td>Pin</td><td><input type='text' name='Pin' id='Pin' value='$pin' onkeyup='' style='width:99%'></td></tr>";  
    
    $sql="SELECT * FROM bio_country"; 
    $result=DB_query($sql,$db);
    
    echo"<tr><td style='width:50%'>Country*</td><td>";
    echo '<select name="country" id="country" tabindex=9 onchange="showstate(this.value)" style="width:190px">';
    
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==$_POST['nationality'])  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td></tr>';
           
           
   $sql="SELECT * FROM bio_state WHERE cid='".$_POST['nationality']."' ORDER BY stateid";
   $result=DB_query($sql,$db);
 
 echo"<tr id='showstate'><td>State*</td><td>";
 echo '<select name="State" id="state" style="width:190px" tabindex=10 onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==$_POST['state'])
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
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
  echo'</tr>';   
  
  
     echo"<tr id='showdistrict'>";
     echo"<td>District*</td><td>"; 
     echo '<select name="District" id="Districts" style="width:190px" tabindex=8 onchange="showtaluk(this.value)">';          
     $sql="SELECT * FROM bio_district WHERE cid='".$_POST['nationality']."' AND stateid='".$_POST['state']."' ORDER BY did";      $result=DB_query($sql,$db);   
 
     $f=0;
     while($myrow1=DB_fetch_array($result))
     {
        if ($myrow1['did']==$_POST['district'])
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
        echo $myrow1['did'] . '">'.$myrow1['district'];
        echo '</option>';
        $f++;
   }
   echo '</select></td>';
   echo'</tr>'; 
   
         //--------Corporation/Muncipality/Panchayath-------------------//
   
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    
        if($LSGtypeid==1){$lsgtype="Corporation";}
        elseif($LSGtypeid==2){$lsgtype="Municipality";}
        elseif($LSGtypeid==3){$lsgtype="Panchayath";}   
    
    echo '<td><select name="lsgType" id="lsgType" style="width:190px" tabindex=9 onchange=showblock(this.value)>';             
    echo '<option value='.$LSGtypeid.'>'.$lsgtype.'</option>'; 
    if($LSGtypeid==1){   
        echo '<option value=2>Municipality</option>';
        echo '<option value=3>Panchayat</option>';     
    }elseif($LSGtypeid==2){
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
    }elseif($LSGtypeid==3){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
    }elseif($LSGtypeid == NULL){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
        echo '<option value=3>Panchayat</option>'; 
    }
    echo '</select></td></tr>'; 
                                         
    echo '<tr><td align=left colspan=2>';
    echo'<div style="align:left" id=block>';
    
        if($LSGtypeid==1) 
        {
        
        $sql="SELECT * FROM bio_corporation WHERE country='".$_POST['nationality']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];


              //echo"11111111"; 
          if($_POST['nationality']==1 && $_POST['state']==14)  
          {
              if($_POST['district']==12){$distname='Thiruvananthapuram';}
              if($_POST['district']==6){$distname='Kollam';} 
              if($_POST['district']==2){$distname='Eranakulam';} 
              if($_POST['district']==13){$distname='Thrissur';} 
              if($_POST['district']==8){$distname='Kozhikode';} 
                    echo '<table align=left ><tr><td width=200px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:190px">';
                    echo "<option value='".$_POST['district']."'>".$distname."</option>"; 
                    echo '</select></td>';    
                    echo '</tr></table>';      
          }
        
        }elseif($LSGtypeid==2) 
        {
            //echo"2222222";
        echo '<table align=left ><tr><td width=200px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='".$_POST['nationality']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:190px">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
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
         echo $myrow1['id'] . '">'.$myrow1['municipality'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</td></tr></table>'; 
        
        }elseif($LSGtypeid==3) 
        {
            //echo"3333333"; 
         echo '<table align=left ><tr><td width=200px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country='".$_POST['nationality']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:190px" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
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
         echo $myrow1['id'] . '">'.$myrow1['block'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr>'; 
      
      echo '<tr><td>' . _('Panchayat Name') . ':</td>';         //grama panchayath
         
         $sql="SELECT * FROM bio_panchayat WHERE country='".$_POST['nationality']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:190px" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$block_name)
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
         echo $myrow1['id'] . '">'.$myrow1['name'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr></table>';      
            
        }
                    
        echo'</div>';
        echo'</td></tr>';
        
        
        
               echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
                  <td><input tabindex=9 type="Text" name="lsgWard" id="lsgWard" style=width:190px maxlength=15 value="'.$ward.'"></td></tr>';  
                  
      echo"<tr><td>Taluk*</td><td>";
      $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country='".$_POST['nationality']."' AND bio_taluk.state='".$_POST['state']."' AND bio_taluk.district='".$_POST['district']."'";
      $result=DB_query($sql,$db);
      echo '<select name="taluk" id="taluk" style="width:190px" tabindex=11>';
      $f=0;
      while($myrow1=DB_fetch_array($result))
      {
      if ($myrow1['id']==$taluk)
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
      echo $myrow1['id'] . '">'.$myrow1['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td></tr>';     
      
            echo"<tr><td>Village</td><td>";
//      $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country='".$_POST['nationality']."' AND bio_taluk.state='".$_POST['state']."' AND bio_taluk.district='".$_POST['district']."'";
      $sql="SELECT * FROM bio_village WHERE bio_village.country='".$_POST['nationality']."' AND bio_village.state='".$_POST['state']."' AND bio_village.district='".$_POST['district']."' AND bio_village.taluk=$taluk"; 
      $result=DB_query($sql,$db);
      echo '<select name="village" id="village" style="width:190px" tabindex=11>';
      $f=0;
      while($myrow1=DB_fetch_array($result))
      {
      if ($myrow1['id']==$village)
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
      echo $myrow1['id'] . '">'.$myrow1['village'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td></tr>'; 
      
//      echo '<tr><td>' . _('Village Name'). ':</td>
//                <td><input tabindex=9 type="Text" name="village" id="village" style=width:190px maxlength=15 value="'.$village.'"></td></tr>';    
   
   
   //----------------------------------------------   
    
   echo '<tr><td>Phone number</td>';
   echo "<td><table><td><input type='text' name='code' id='code' value='$custcode' style='width:50px'></td>
         <td><input type=text name=phone id=phone value='$custphone' style='width:96%'></td></table></td></tr>";
   echo '<tr><td>Mobile Number</td>';
   echo "<td><input type=text name=mobile id=mobile value='$custmobile' style='width:96%'></td></tr>";
   echo '<tr><td>Email id</td>';
   echo "<td><input type=text name=email id=email value='$custemail' style='width:96%'></td></tr>";
   
   echo '<tr><td>Identity</td><td>';  
        $sql1="SELECT * FROM bio_identity";
        $result1=DB_query($sql1, $db);
    echo '<select name=identity style="width:190px">';
    while($myrow1=DB_fetch_array($result1))
    {     
    if ($myrow1['ID_no']==$idtype)  
    { 
    echo '<option selected value="';
    echo $idtype . '">'.$myrow1['ID_type'];
    } else {
        echo '<option value="';
        echo $myrow1['ID_no'] . '">'.$myrow1['ID_type'];
    }
    
    echo '</option>';
   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';
    
    echo '<tr><td>Identity No</td>';
    echo "<td><input type=text name=identityno id=identityno value='$idno' style='width:96%'></td></tr>";
   
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
    echo $productservicesid . '">'.$myrow1['productservices'];
    } else {
    echo '<option value="';
    echo $myrow1['id'] . '">'.$myrow1['productservices'];
    }
    
  echo '</option>';
   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';



  echo "</table>";  
  
  echo "</fieldset>"; 
  ///////////////
   // echo '</div>';
  echo "</td>";
   //Leads details fieldset .................................Leads details fieldset.....................Leads details fieldset 
   
  echo "<td style='width:70%'>";
 //echo "<div>";
  echo "<fieldset style='float:left;width:95%;height:453px'>";       
  echo "<legend><h3>Leads Details</h3>";
  echo "</legend>";
  echo "<div style='height:400px;overflow:scroll'>";
  echo "<table border=0 style='width:100%'>";  
  
  echo '<tr><td>Advance Amount</td>';
  echo "<td><input type=text name=advanceamt id=advance value='$advanceamount' readonly style='width:90%'></td></tr>";
  echo"<div style='height:75px'></div>"; 
  
    $outputtype_id2=explode(',',$outputtypeid);
    $n=sizeof($outputtype_id2);
    
  echo '<tr><td>Output Type</td>';
    $sql_out="SELECT * FROM bio_outputtypes";
    $result_out=DB_query($sql_out,$db);
    $j=1;
    $f=0;
    
  echo'<td><table><tr>';  
  while($mysql_out=DB_fetch_array($result_out)){
      $f=1;
      for($i=0;$i<$n;$i++)        {
        if($mysql_out[0]==$outputtype_id2[$i]){
           echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].' checked>'.$mysql_out[1].'</td>';
           $j++; 
         $f=0;
        }
      }
      if($f==1){
         echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
         $j++;
         $f=0; 
      }  
        if( ($j%2)-1==0 ){
            echo'</tr><tr>';
        }
  }
  echo"</tr>";  
  echo"</table></td></tr>";  
    
//    echo"</tr></table></td></tr>";
    echo"</tr>";
/*    
    $sql="SELECT bio_leadsourcetypes.id,
                 bio_leadsourcetypes.leadsourcetype 
           FROM `bio_leadsourcetypes`,bio_leadsources
            WHERE bio_leadsources.id=$sourceid
            AND bio_leadsources.sourcetypeid=bio_leadsourcetypes.id";
    $result=DB_query($sql,$db); 
    $myrow = DB_fetch_array($result);
    */
    
/*   echo "<tr><td>LeadSource Type</td><td><input type='hidden' id='sourcetype' value=".$myrow['id']." onkeyup='' style='width:99%'>".$myrow['leadsourcetype']."</td></tr>";
   echo '<tr id=showsource>'; 
   
 //--------------------------------------------------------------------------------  
//         echo $sourcetypeid=$myrow4['sourcetypeid'] ; echo $sourceid ; 


   echo '<td>LeadSource</td><td>'; 
   echo '<select name=source id=source style="width:192px" onkeyup=showCD1(this.value) onchange=showCD1(this.value) onclick=showCD1(this.value)>';
    $sql1="SELECT id,sourcename, 
                  sourcetypeid
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
   */
   
   
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

    $schemeid2=explode(',',$schemeid);
    $n=sizeof($schemeid2);
    
    echo '<tr><td>Scheme</td>';
    $sql1="SELECT * FROM bio_schemes";
    $result1=DB_query($sql1, $db); 
    $j=1;
    $f=0;
    echo'<td>';
    while ($taskdetails=DB_fetch_array($result1)){
        $f=1;
      for($i=0;$i<$n;$i++)        {
        if($taskdetails[0]==$schemeid2[$i]){
           echo'<input type="checkbox" id="schm"'.$j.' name="schm[]" value='.$taskdetails[0].' checked>'.$taskdetails[1].'';
           $j++; 
         $f=0;
        }
      }
      if($f==1){
         echo'<input type="checkbox" id="schm"'.$j.' name="schm[]" value='.$taskdetails[0].'>'.$taskdetails[1].'';
         $j++;
         $f=0; 
      }  
        
        $j++;         
    } 
                
          echo"</td></tr>"; 

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
     echo "<input type=hidden name='leadid1' id='leadid' value='$leadid'>";
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
                   bio_leadfeedstocks.weight,
                   bio_leadfeedstocks.feedstockid
              FROM bio_leadfeedstocks,
                   bio_feedstocks 
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
        }   
    echo "<tr id='edittedsho'></tr>";
    echo"</table>";

//echo"<table>";echo"</div>";
    echo '<tr><td colspan="2"><center><input type="submit" name="edit" id="editleads" value="Update"  onclick="return log_in()"></center></td></tr></table>';

 }
 
?>
