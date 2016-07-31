<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Add Beneficiary');  
include('includes/header.inc');

echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">ADD BENEFICIARY</font></center>';
          
   


 if(isset($_POST['submit']))  
 {
      $parent_leadid=$_POST['leadid'];
      $_SESSION['leadid']=$parent_leadid;
      
     $scheme=$_POST['schm'];
      
 if($scheme!=""){
 foreach($scheme as $id){
 $sourcescheme.=$id.",";
 } 
 $schemeid=substr($sourcescheme,0,-1);   
 } else{
      $schemeid="";
      }   
      
      
        
       $cust_name= $_POST['custname'];
       $House_no= $_POST['Houseno'];
       $House_name= $_POST['HouseName'] ;
       $lsg_area1= $_POST['Area1'];
       $lsg_area2= $_POST['Area2'];
       $lsg_Pin  = $_POST['Pin'];         
       $lsg_ward=$_POST['lsgWard'];  
       $lsg_code=$_POST['code'];
       $lsg_phone= $_POST['phone'];     
        if($lsg_code!=''&&$lsg_phone!='')
       {
       $phone= $lsg_code."-".$lsg_phone;
       }
       else if($lsg_code!=''&&$lsg_phone=='')
       {
         $phone='';  
       }
         else if($lsg_code==''&&$lsg_phone=='')
       {
         $phone='';  
       }
       else
       {
          $phone= $lsg_phone;
       }
       $lsg_mobile     =$_POST['mobile'];
       $lsg_email      =$_POST['email'];          
       $idtype         =$_POST['identity'];
       $idno           =$_POST['identityno'];
     $advance_amount   =$_POST['advanceamt']; 
      $mode_payment    =$_POST['mode']; 
            //$source_id =$_POST['sourcetype'];
       //$investment_size=$_POST['investmentsize'];        
             $remark  =  $_POST['remarks'];
       
        
 $sql_cust1="UPDATE bio_leads SET parent_leadid='$leadid' WHERE leadid='".$leadid."'";
 $result=DB_query($sql_cust1,$db); 
 
  
  
  $sql_cust= "INSERT INTO bio_cust(custname,houseno,housename,area1,area2,pin,LSG_Ward,custphone,custmob,custmail)             
                 VALUES ('".$cust_name."',
                        '".$House_no."',
                        '".$House_name."',
                        '".$lsg_area1."',
                        '".$lsg_area2."', 
                        '".$lsg_Pin."', 
                        '".$lsg_ward."',
                        '".$phone."',  
                        '".$lsg_mobile."',
                        '".$lsg_email."')";
                        
                          
  $result=DB_query($sql_cust,$db);          
  
  
   $sql_cust= "SELECT LAST_INSERT_ID()" ; 
   $result5=DB_query($sql_cust,$db); 
   $checkresult5=DB_fetch_array($result5);
   $cust_id=$checkresult5[0];  
        
 $date=date("Y-m-d");        
   
  $sql_lead="INSERT INTO bio_leads(cust_id,leaddate,id_type,id_no,schemeid,remarks,created_by,parent_leadid,leadstatus)
                                    VALUES('".$cust_id."', 
                                            '".$date."',                                     
                                            '".$idtype."',
                                            '". $idno."',                                               
                                              '$schemeid', 
                                                '$remark',                                                                                                     
                                              '".$_SESSION['UserID']."',                                             
                                              '".$parent_leadid."',
                                              0)";     
                                             
                                            
                                            
                                    
  $sql_lead=DB_query($sql_lead,$db); 
  
  
  
   $sql_lead= "SELECT LAST_INSERT_ID()" ; 
   $result6=DB_query($sql_lead,$db); 
   $checkresult5=DB_fetch_array($result6);
   $leadid=$checkresult5[0];
  
    
  
  $sql_advance="INSERT INTO bio_advance(leadid,amount,mode_id)
                  VALUES( $leadid, 
                         '".$advance_amount."', 
                         '".$mode_payment."')";                     
                        
  $result=DB_query($sql_advance,$db);
  
  
  
    $main_share=$_POST['main_share'];  
           $block_share=$_POST['block_share'];
           $district_share=$_POST['district_share'];
       if(!$_POST['block_share'])
       {
         $block_share='';  
       }
          if(!$_POST['district_share'])
       {
         $district_share='';  
       }  
       
       
       
  $sql= "INSERT INTO bio_lsgsubsidyshare(leadid,                              
                                 main_share,
                                block_share,
                                district_share)
                       VALUES ($leadid,     
                                '".$main_share."',
                             '".$block_share."', 
                             '".$district_share."')";
  
 $result=DB_query($sql,$db);  
}                                      
      


  if($_GET['leadid']==''){
   $_GET['leadid']=$_SESSION['leadid'];

} 

         $parent_leadid=$_GET['leadid'];
   $lsgid=$_GET['id']; 
   
  
   
    $sql_cust="SELECT 
    `bio_cust`.`custname` 
    , `bio_cust`.`houseno`  
    , `bio_cust`.`housename`
    , `bio_cust`.`area1`
    , `bio_cust`.`area2`
    , `bio_cust`.`pin`      
    ,`bio_cust`.`nationality` 
    ,`bio_cust`.`state`          
    , `bio_cust`.`district`                              
    , `bio_cust`.`LSG_type` 
    , `bio_cust`.`LSG_Ward`   
    , `bio_cust`.`LSG_name`  
    , `bio_cust`.`block_name`     
    , `bio_cust`.`taluk`  
    , `bio_cust`.`village`      
    , `bio_cust`.`custphone`
    , `bio_cust`.`custmob`     
    , `bio_cust`.`custmail`         
    , `bio_leads`.`leadid`
    , `bio_leads`.`schemeid`
    , `bio_leads`.`remarks`                           
   
    

FROM
 bio_cust,bio_leads
 WHERE 

 bio_leads.cust_id=bio_cust.cust_id
 AND bio_leads.leadid=".$parent_leadid;
 
 $result = DB_query($sql_cust, $db);
 $myrow = DB_fetch_array($result);
 
 
 
     $cust_name     = $myrow['custname'];
     $House_no      = $myrow['houseno'];
     $House_name    = $myrow['housename'];
     $lsg_area1     = $myrow['area1'];  
     $lsg_area2     = $myrow['area2'];  
     $lsg_Pin       = $myrow['pin'];    
     $lsg_country   = $myrow['nationality'];
     $lsg_state     = $myrow['state'];       
     $lsg_district  = $myrow['district'];
     $lsg_lsgtype   = $myrow['LSG_type'];
     if($lsg_lsgtype==1){$lsgtype="Corporation";}
     elseif($lsg_lsgtype==2){$lsgtype="Municipality";}
     elseif($lsg_lsgtype==3){$lsgtype="Panchayath";} 
     $lsg_ward      = $myrow['LSG_Ward'];
     $lsg_lsgname   = $myrow['LSG_name'];
     $lsg_panchayat = $myrow['block_name'];  
     $lsg_taluk     = $myrow['taluk'];
     $lsg_village   = $myrow['village'];
     $lsg_code      = $myrow['custphone'];  
     $lsg_mobile    = $myrow['custmob'];  
       $lsg_email   = $myrow['custmail'];  
        $idtype     = $myrow['id_type'];
         $idno      =  $myrow['id_no'];
        $main_share =$myrow['main_share'];
    $block_share    =$myrow['block_share'];
    $district_share =$myrow['district_share'];
 $advance_amount    =$myrow['amount'];
  $mode_payment     =$myrow['mode_id'];    
          $schemeid =$myrow['schemeid'];          
           $remark  = $myrow['remarks'] ;
                                                    
      $landphn=explode("-",$lsg_code);
     $code=$landphn[0]; 
     $phn=$landphn[1];  
    
  
      
                
 echo '<div>';  
 echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
 
 echo"<input type='hidden' name='leadid' id='leadid' value='$parent_leadid'>";
 echo"<input type='hidden' name='id' id='id' value='$lsgid'>";
  
  
  
  
 
 echo"<table style='width:95%;height:100%;><tr><td>";    
 echo "<table border=0 style='width:50%;height:100%;'>"; 
 echo "<tr><td style='width:50%' valign=top>";
  
  
  
 //Customer Details Fieldset..............................Customer Details Fieldset......................................Customer Details Fieldset 
 
 echo "<fieldset style='float:left;width:90%;height:auto'>";     
 echo "<legend><h3>Customer Details</h3>";
 echo "</legend>";                             
 echo "<table style='height:100%' width:50%>";  
 
 echo "<tr><td style='width:50%'>Customer Name*</td>";
 echo "<td><input type='text' name='custname' id='custid'  tabindex=1 onkeyup='caps1()' style='width:200px'></td>";

 echo "<tr><td>House No:</td><td><input type='text' name='Houseno' id='Houseno'  tabindex=2 onkeyup='' style='width:99%'></td></tr>";    
 echo "<tr><td>House / Organizational Name</td><td><input type='text' name='HouseName' id='HouseName'  tabindex=3 onkeyup='' style='width:99%'></td></tr>";
 echo "<tr><td>Residential Area:</td><td><input type='text' name='Area1' id='Area1'  tabindex=4 onkeyup='' style='width:99%'></td></tr>";
 echo "<tr><td>Post Office:</td><td><input type='text' name='Area2' id='Area2'  tabindex=5 onkeyup='' style='width:99%'></td></tr>";
 echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin'  tabindex=6 onkeyup='' style='width:99%'></td></tr>";    

     
    $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
    
 echo"<tr><td style='width:50%'>Country*</td><td>";
 echo '<select name="country" id="country"  onchange="showstate(this.value)" style="width:200px">';
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
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
   
    
    
   $sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showstate'><td>State*</td><td>";
 echo '<select name="state" id="state" style="width:200px"  onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
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
  
  
   $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
  $result=DB_query($sql,$db);
 
 echo"<tr id='showdistrict'><td>District*</td><td>";
 echo '<select name="District" id="District" style="width:200px"  onchange="showtaluk(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$lsg_district)
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
 echo '</select>';
 echo'</td>'; 
 echo'</tr>';
  
    
    
echo '<tr><td>' . _('LSG Type') . ':</td>';
echo '<td><select name="lsgType" id="lsgType" style="width:200px"  onchange=showblock(this.value)>';             
echo '<option value='.$lsg_lsgtype.'>'.$lsgtype.'</option>';   
    
  if($lsg_lsgtype==1){   
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
    }elseif($lsg_lsgtype==2){
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
    }elseif($lsg_lsgtype==3){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
    }         
      echo '</select></td></tr>';
      
    echo '<tr><td align=left colspan=2>';
    echo '<div style="align:left" id=block>';
    

 if($lsg_lsgtype==1) 
        {
        
  $sql="SELECT * FROM bio_corporation WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."'";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];
   //
          if($lsg_country==1 && $lsg_state==14)  
          {              
              if($lsg_district==12){$distname='Thiruvananthapuram';}
              if($lsg_district==6){$distname='Kollam';} 
              if($lsg_district==2){$distname='Eranakulam';} 
              if($lsg_district==13){$distname='Thrissur';} 
              if($lsg_district==8){$distname='Kozhikode';} 
                    echo '<table align=left ><tr><td width=200px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:200px">';
                    echo "<option value='".$lsg_district."'>".$distname."</option>"; 
                    echo '</select></td>';    
                    echo '</tr></table>';      
          }
        
        }
        elseif($lsg_lsgtype==2) 
        {
            //echo"2222222";
        echo '<table align=left ><tr><td width=200px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:200px">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$lsg_lsgname)
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
        
        }
        
        elseif($lsg_lsgtype==3) 
        {
            //echo"3333333"; 
         echo '<table align=left ><tr><td width=200px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:200px" >';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$lsg_lsgname)
         {
         echo '<option selected value=">';
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
      echo '</tr></table>'; 
      
      
      echo '<tr><td>' . _('Panchayat Name') . ':</td>';         //grama panchayath
         
         $sql="SELECT * FROM bio_panchayat WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."' AND block='".$lsg_lsgname."' ";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:200px" >';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$lsg_panchayat)
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
      echo '</tr>';      
  } 
 
//  }
        
         
        
    echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td> 
         <td><input type=text tabindex=7 type="text" name="lsgWard" id="lsgWard" value="'.$lsg_ward.'" style="width:200px" maxlength=15 value=""></td></tr>';       
              
    echo"<tr id='showtaluk'></tr>";     
    echo"<tr id='showvillage'></tr>"; 
    echo '</tr>'; 
        
        
   $sql="SELECT * FROM bio_taluk";
  $result=DB_query($sql,$db);
 
 echo"<tr id='showtaluk'><td>Taluk*</td><td>";
 echo '<select name="Taluk" id="Taluk" style="width:200px"  onchange="showvillage(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['id']==$lsg_taluk) 
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
  echo '</td>'; 
  echo '</tr>';   
  
  
  $sql="SELECT * FROM bio_village ";
  $result=DB_query($sql,$db);
 
 echo"<tr id='showvillage'><td>Village*</td><td>";
 echo '<select name="Village" id="Village" style="width:200px"  onchange="showvillage(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['id']==$lsg_village) 
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
  echo '</td>'; 
  echo '</tr>';  
  
  
    echo '<tr><td>Phone number*</td>';
    echo "<td><table><td><input type=text name='code' id='code' style='width:50px' tabindex=8></td><td><input type=text name=phone  tabindex=9 id=phone style='width:100%' onchange=checkLandline()></td></table></td></tr>";
    
    echo '<tr><td>Mobile Number</td>';
    echo "<td><input type=text name=mobile id=mobile  style='width:98%' tabindex=10 ></td><td id=mob></td></tr>"; 
    echo '<tr><td>Email id</td>';
    echo "<td><input type=text name='email' id='email'  style='width:98%' tabindex=11 ></td></tr>";    
    
    
        
     echo '<tr><td>Identity Type</td>';
     echo'<td><select name="identity" id="identity" style="width:200px" tabindex=12>';
    $sql1="SELECT * FROM bio_identity";
    $result1=DB_query($sql1,$db);
    $f=0;
    while($myrow1=DB_fetch_array($result1))
   {
    if ($myrow1['ID_no']==$idtype)
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
    echo $myrow1['ID_no'] . '">'.$myrow1['ID_type'];  
    echo '</option>';
  }
echo'</select></td></tr>';




    echo '<tr><td>Identity No</td>'; 
    echo '<td><input type="text" style="width:200px" name="identityno" id="identityno" value="'.$idno.'" tabindex=13></td></tr>'; 
    
    
    
      
      echo '</table>';       
      echo "</fieldset>";
      echo "</td>"; 
  
      
   //Leads Details Fieldset........................Leads Details Fieldset...............................Leads Details Fieldset    
    
    
    
  echo "<td style='width:50%' valign=top>";
  echo "<fieldset style='float:right;width:100%;height:515px'>";         
  echo "<legend><h3>Leads Details</h3>";
  echo "</legend>";
  echo "<table border=0 style='width:100%' height:100%>";
  
    
  echo '<tr><td>Advance Amount</td>';
  echo "<td><input type=text name=advanceamt id='advance' style='width:190px' tabindex=14 onblur='amntmode(this.value)'></td></tr>";
  
  
  echo'<tr>';
  echo'<td>Mode of payment:</td>';
  echo'<td><select name="mode" id="paymentmode" style="width:190px" tabindex=15 onchange="advdetail(this.value)">';
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
  
   
  
 echo "<tr><td colspan=2><table id='block1'>";
 echo'</table></td></tr>';
  
   if($lsg_lsgtype==1)
 {   
    echo '<tr><td width=30%>Corporation</td>';
    echo "<td><input type=text name='main_share' id='main_share' value='$main_share' style='width:190px' tabindex=16></td></tr>";                 
 }
    
  elseif($lsg_lsgtype==2)
  {
     echo '<tr><td>Municipality</td>';
     echo "<td><input type=text name='main_share' id='main_share' value='$main_share' style='width:190px' tabindex=17'></td></tr>"; 
     
     echo '<tr><td>District/Corporation</td>';
     echo "<td><input type=text name='district_share' id='district_share' value='$district_share' style='width:190px' tabindex=18'></td></tr>";            
        
   }
    elseif($lsg_lsgtype==3)
    {
      echo '<tr><td>Grama Panchayat</td>';
      echo "<td><input type=text name='main_share' id='main_share' value='$main_share' style='width:190px' tabindex=19'></td></tr>";
 
      echo '<tr><td>Block Panchayat</td>';
      echo "<td><input type=text name='block_share' id='block_share' value='$block_share' style='width:190px' tabindex=20'></td></tr>"; 
 
      echo '<tr><td>District</td>';
      echo "<td><input type=text name='district_share' id='district_share' value='$district_share' style='width:190px' tabindex=21'></td></tr>";             
    
    }          
  
  


 echo '<tr><td>Output Type*</td>';
 $sql_out="SELECT * FROM bio_outputtypes";
    $result_out=DB_query($sql_out, $db);
    $j=1;
    echo'<td><table><tr>';
    while($mysql_out=DB_fetch_array($result_out)){
        $outputtypeid=$mysql_out['outputtypeid'];
        
      if($outputtypeid==1)
      { 
     echo  '<td><input type="checkbox" checked id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
      }
      else
      {   
    echo'<td><input type="checkbox"  id="outputtype"'.$j.' name="outputtype[]" tabindex=23 value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
      }   
      
       $j++; 
    
            
        if( ($j%2)-1==0 ){
            echo'</tr><tr>';
        }
               
    } 
    echo"</tr>";  
    echo"</table></td></tr>";        
    echo"<input type='hidden' name='houttype' id='houttype' value='$j'>";
  
  
  
  /*echo '<tr><td>LeadSource Type*</td>';
  echo '<td>'; 

  echo '<select name="sourcetype" id="sourcetype" style="width:192px" tabindex=24 onchange=showCD(this.value)>';
  $sql="SELECT * FROM `bio_leadsourcetypes`";
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
    $c=0;  
  while ($myrow = DB_fetch_array($result)) {
     
    if ($myrow['id']==$_POST['sourcetype']) 
    {
    echo '<option selected value="';
    } else if($c==0){echo '<option>';  }
        echo '<option value="';
    
    echo $myrow['id'] . '">'.$myrow['leadsourcetype'];     
   echo '</option>';
   $c++;
    }         
    echo $c;
    echo '</select></td></tr>';
    
    
    
   echo '<tr><td>Investment Size</td>';
   echo "<td><input type=text name=investmentsize id=invest style='width:190px' tabindex=28></td>";
   echo '</tr>';  
               */
  
    
    $schemeid2=explode(',',$schemeid);
    $n=sizeof($schemeid2);
    
    echo '<tr style=display:none><td>Scheme</td>';           
    $sql1="SELECT * FROM bio_schemes";
    $result1=DB_query($sql1, $db); 
    $j=1;
    $f=0;
     echo'<td colspan=3>';
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
    
    }  
    echo'</td>';
     
    
    
    
  /*echo '<tr><td>Select a Remark</td>';
  echo '<td>';
  echo '<select name="RemarkList" id="remarklist" tabindex=34 style="width:192px">';
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
    echo'</tr>';   */
    
    
  
  echo '<tr><td>Remarks</td>';     
  echo "<td><textarea name='remarks' id='remarks' rows=4 cols=26 tabindex=22 style=resize:none;></textarea></td>"; 
  echo '</tr>';
  
  
  
  
   echo "</td></tr></table>";
   echo "</td></tr>";
   echo '<tr><td colspan=2 align=center><input type=submit name="submit" id="reg"  value="Submit" onclick="if(validate()==1)return false;"></td></tr>';  
   echo "</td></tr></table>"; 
   echo "</fieldset>";                    
   echo '</form>';
 ?> 
  
  
<script type="text/javascript"> 
  
 /*function validate()
{

   var f=0;  
    if(f==0){f=common_error('custid','Please enter the customer name');  if(f==1) { return f; }} 
   // if(f==0){f=common_error('mobile','Please enter your mobile number');  if(f==1) { return f; }} 
   if(f==0)
{
     
    var x=document.getElementById('phone').value;         
    var y=document.getElementById('mobile').value;
    if(x!=""){
       var l=x.length;
    
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Enter a numeric value"); document.getElementById('phone').focus();
              if(x=""){f=0;}
              return f; 
           }
           if(l>8 || l<6)
           {
               
             f=1;  
              alert("Please enter valid phone number"); document.getElementById('phone').value=""; 
              document.getElementById('phone').focus();
              return f;
           } 
    }
   /* if(y!=""){
         var l=y.length;
    
            if(isNaN(y)||y.indexOf(" ")!=-1)
           {  f=1;
              alert("Enter a numeric value"); document.getElementById('mobile').focus();
              if(y=""){f=0;}
              return f; 
           }
           if(l>11 || l<10)
           {
               
             f=1;  
              alert("Please enter valid mobile number"); document.getElementById('mobile').value=""; 
              document.getElementById('mobile').focus();
              return f;
           } 
    }         */
    
}            



  /*function address()
    {     
 
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    var address = document.getElementById('email').value; 
    if(address!='')
    {
           if(reg.test(address) == false) {
     alert('Invalid Email Address');  
     document.getElementById('email').focus();  

                         
      return false;
   }   
    }
    else
    {
                       alert('Enter your email address');
    
   }

}    */
      

/*   if(f==0){f=common_error('email','Please enter your valid email address');  if(f==1) { return f; }} 
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.getElementById('email').value;
   if(reg.test(address) == false) {
     alert('Invalid Email Address');  
     document.getElementById('email').focus();  

                         
      return false;
      }      */
  
  
}                   */
 </script> 

   
       
      

 
 