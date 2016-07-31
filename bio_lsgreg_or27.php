<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Proposal Template');  
include('includes/header.inc');
include('includes/bio_GetPrice.inc'); 
include('includes/sidemenu.php');
 global $first;
global $flag2;


echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">LSG REGISTER</font></center>';
           $phone= $_POST['code']."-".$_POST['phone'];  
           
 if (isset($_POST['SelectedType'])){
    $SelectedType = strtoupper($_POST['SelectedType']);
} elseif (isset($_GET['SelectedType'])){
    $SelectedType = strtoupper($_GET['SelectedType']);
}          
           
       
 if(isset($_POST['submit']))  
 {
    
     $scheme=$_POST['schm'];
      
 if($scheme!=""){
 foreach($scheme as $id){
 $sourcescheme.=$id.",";
 } 
 $schemeid=substr($sourcescheme,0,-1);   
 } else{
      $schemeid="";
      } 
     
     
     
     
 /*if($scheme!=""){
 foreach($scheme as $id){
 $sourcescheme.=$id.",";
 } 
 $schemeid=substr($sourcescheme,0,-1);   
 }
 else{
      $schemeid="";
      }          */
     
       $lsg_country= $_POST['country'];
       $lsg_state= $_POST['state'];
       $lsg_district= $_POST['District'] ;
       $lsg_lsgtype= $_POST['lsgType'];
       $lsg_taluk=$_POST['taluk'];
       $lsg_village=$_POST['village'];
       $lsg_lsgname=$_POST['lsgName'];  
       
       $lsg_panchayat=$_POST['gramaPanchayath'];  
       if(!$_POST['gramaPanchayath'])
       {
         $lsg_panchayat='';  
       }
       $lsg_contact=$_POST['contact'];
       $lsg_building=$_POST['building_name'];
       $lsg_area1=$_POST['Area1'];
       $lsg_area2=$_POST['Area2'];
       $lsg_Pin=$_POST['Pin'];
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
       $lsg_mobile=$_POST['mobile'];
       $lsg_email=$_POST['email'];          
          
          
       if (isset($SelectedType)) 
       {    
          
        $sql_cust = "UPDATE bio_cust SET nationality = '".$lsg_country."',
                                           state =  '".$lsg_state."',
                                         district = '".$lsg_district."',
                                         LSG_type = '".$lsg_lsgtype ."',
                                         LSG_name = '".$lsg_lsgname."',
                                         block_name = '".$lsg_panchayat ."', 
                                         taluk = '".$lsg_taluk ."',
                                         village = '".$lsg_village."',
                                         contactperson= '".$lsg_contact."',
                                         housename = '".$lsg_building."',   
                                         area1 = '".$lsg_area1."',
                                         area2 = '".$lsg_area2."',
                                         pin = '".$lsg_Pin."',
                                         custphone = '".$phone."',
                                         custmob = '".$lsg_mobile."',
                                         custmail = '".$lsg_email."'
                                   WHERE cust_id='".$SelectedType."'";  
                       
                       
       $sql_leads ="UPDATE bio_leads SET custid=".$cust_id.",            
                                         leaddate='".date("Y-m-d")."',
                                         schemeid='".$_POST['schm']."',  
                                         remarks= '".$_POST['remarks']."',  
                                         enqtypeid=3,
                                   WHERE cust_id='".$SelectedType ."'";                   
                        
                                
                               
          $sql =  "UPDATE bio_lsgreg SET leadid = $leadid,                  
                                         project_name = '".$_POST['project_name'] . "', 
                                         stock_cat = '".$_POST['Plant'] ."',
                                         total_projectcost = '".$_POST['project_cost']."',
                                         main_share ='".$main_share."',
                                         block_share ='".$block_share."', 
                                         district_share = '".$district_share."',
                                         beneficiary_share = '".$_POST['beneficiary_share'] . "',
                                         num_beneficiaries = '".$_POST['num_beneficiary'] ."')";
                     
                        
      $msg = _('The LSG register details') . ' ' . $SelectedType . ' ' .  _(' has been updated');     
       
      }else{    
                  
     $sql_cust="INSERT INTO bio_cust(nationality,
                                     state,
                                     district,
                                     LSG_type,
                                     LSG_name,
                                     block_name,
                                     taluk,
                                     village,
                                     contactperson,
                                     housename,
                                     area1,
                                     area2,
                                     pin,
                                     custphone,
                                     custmob,
                                     custmail,
                                     custname)             
                          VALUES ('".$lsg_country."',
                                  '".$lsg_state."',
                                  '".$lsg_district."',
                                  '".$lsg_lsgtype."',
                                  '".$lsg_lsgname."', 
                                  '".$lsg_panchayat."', 
                                  '".$lsg_taluk."',
                                  '".$lsg_village."',
                                  '".$lsg_contact."',
                                  '".$lsg_building."',
                                  '".$lsg_area1."',
                                  '".$lsg_area2."',
                                  '".$lsg_Pin."',
                                  '".$phone."',
                                  '".$lsg_mobile."',  
                                  '".$lsg_email."',
                                  '".$lsg_contact."')";
                        
       $result=DB_query($sql_cust,$db);          
       
       $sql_cust= "SELECT LAST_INSERT_ID()" ; 
       $result5=DB_query($sql_cust,$db); 
       $checkresult5=DB_fetch_array($result5);
       $cust_id=$checkresult5[0]; 
             
       $sql_lead="INSERT INTO bio_leads(cust_id,leaddate,schemeid,remarks,enqtypeid,created_by)
                       VALUES(".$cust_id.",' ".date("Y-m-d")."',' ".$schemeid."',
                             '".$_POST['remarks'] ."',3,'".$_SESSION['UserID']."')";  
     $result=DB_query($sql_lead,$db);              
                
   $sql_leadid= "SELECT LAST_INSERT_ID()" ; 
   $result=DB_query($sql_leadid,$db); 
   $checkresult=DB_fetch_array($result);
   $leadid=$checkresult[0];
     
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
          

  $sql= "INSERT INTO bio_lsgreg(leadid,
                                project_name,
                                stock_cat,
                                total_projectcost,
                                main_share,
                                block_share,
                                district_share,
                                beneficiary_share,
                                num_beneficiaries,
                                remarks,
                                status)
                        VALUES ($leadid, 
                             '".$_POST['project_name']."',
                             '".$_POST['Plant']."',
                             '".$_POST['total_projectcost']."',
                             '".$main_share."',
                             '".$block_share."', 
                             '".$district_share."', 
                             '".$_POST['beneficiary_share']."',
                             '".$_POST['num_beneficiary']."',
                             '".$_POST['remarks'] ."',
                            0)"; 
  
  $result=DB_query($sql,$db);
 
 }          
 }                       
 
   echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post name='form'>";
   echo"<table style='width:100%;height:100%;><tr><td>";
   
   echo "<table border=0 style='width:55%;height:100%;'>"; 
   echo "<tr><td style='width:50%' valign=top>";
 

 

 //Basic Details fieldset................................Basic Details Fieldset.............................Basic Details Fieldset                  
 

 
 echo "<fieldset style='float:left;width:90%;height:auto'>";     
 echo "<legend><h3>Basic Details</h3>";
 echo "</legend>";                             
 echo "<table style='height:100%'>";  
 
 
 
  if ( isset($SelectedType))
 {

                        
     $sql_cust="SELECT `bio_cust`.`nationality`,
                       `bio_cust`.`state`,
                       `bio_lsgreg`.`project_name`, 
                       `bio_cust`.`custphone`, 
                       `bio_cust`.`custmob`, 
                       `bio_cust`.`contactperson`, 
                       `bio_cust`.`housename`, 
                       `bio_cust`.`area1`, 
                       `bio_cust`.`area2`, 
                       `bio_cust`.`district`, 
                       `bio_cust`.`pin`, 
                       `bio_cust`.`custmail`, 
                       `bio_lsgreg`.`remarks`, 
                       `bio_cust`.`LSG_type`, 
                       `bio_cust`.`LSG_name`, 
                       `bio_cust`.`block_name`, 
                       `bio_cust`.`taluk`, 
                       `bio_cust`.`village`, 
                       `bio_leads`.`leadid`, 
                       `bio_lsgreg`.`remarks`, 
                       `bio_lsgreg`.`stock_cat`, 
                       `bio_lsgreg`.`total_projectcost`, 
                       `bio_lsgreg`.`main_share`, 
                       `bio_lsgreg`.`block_share`, 
                       `bio_lsgreg`.`district_share`, 
                       `bio_lsgreg`.`beneficiary_share`, 
                       `bio_leads`.`schemeid`, 
                       `bio_lsgreg`.`num_beneficiaries`, 
                       `bio_lsgreg`.`status`       
                   FROM bio_cust,
                        bio_lsgreg,
                        bio_leads
                  WHERE bio_leads.leadid=bio_lsgreg.leadid
                    AND bio_leads.cust_id=bio_cust.cust_id
                    AND bio_lsgreg.id=".$SelectedType;

     $result = DB_query($sql_cust, $db);
     $myrow = DB_fetch_array($result);
     
     $lsg_country   = $myrow['nationality'];
     $lsg_state     = $myrow['state'];
     $lsg_district  = $myrow['district'];
     $lsg_lsgtype   = $myrow['LSG_type'];
     if($lsg_lsgtype==1){$lsgtype="Corporation";}
     elseif($lsg_lsgtype==2){$lsgtype="Municipality";}
     elseif($lsg_lsgtype==3){$lsgtype="Panchayath";} 
     $lsg_lsgname   = $myrow['LSG_name'];
     $lsg_panchayat = $myrow['block_name'];
     $lsg_taluk     = $myrow['taluk'];
     $lsg_village   = $myrow['village'];  
     $lsg_cont      = $myrow['contactperson'];  
     $lsg_building  = $myrow['housename'];  
     $lsg_area1     = $myrow['area1'];  
     $lsg_area2     = $myrow['area2'];  
     $lsg_Pin       = $myrow['pin']; 
     $lsg_code      = $myrow['custphone'];  
     $lsg_mobile     = $myrow['custmob'];  
     $lsg_email      = $myrow['custmail'];  
     $lsg_contact    = $myrow['custname'];  
    
    $project_name   = $myrow['project_name'];
    $stock_cat      =  $myrow['stock_cat'];
    $total_projectcost =$myrow['total_projectcost'];
    $main_share        =$myrow['main_share'];
    $block_share       =$myrow['block_share'];
    $district_share    =$myrow['district_share'];
    $beneficiary_share =$myrow['beneficiary_share'];  
    $schemeid  =$myrow['schemeid'];
    $num_beneficiaries = $myrow['num_beneficiaries'];  
   $remarks= $myrow['remarks'] ;
                
     $landphn=explode("-",$lsg_code);
     $code=$landphn[0]; 
     $phn=$landphn[1];  
    
   
 }        
 
 
  
                //Country
                
    $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
    
    echo"<tr><td>Country*</td><td>";
    echo '<select name="country" id="country" tabindex=1  onchange="showstate(this.value)" style="width:200px">';
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
 
  
  
  
         //State
         

   $sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";
    $result=DB_query($sql,$db);
    
 
 echo"<tr id='showstate'><td>State*</td><td>";
 echo '<select name="state" id="state" style="width:200px" tabindex=2  onchange="showdistrict(this.value)">';
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
  
  
  
            //District
            
            
     
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
  $result=DB_query($sql,$db);
 
 echo"<tr id='showdistrict'><td>District*</td><td>";
 echo '<select name="District" id="District" style="width:200px" tabindex=3  onchange="showtaluk(this.value)">';
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

  
  
           //LSG Type
  
if(!isset($SelectedType)) {
    
 
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:200px" tabindex=4  onchange="showblock(this.value);">';
    
    echo '<option value=0></option>';
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';       
    echo '</select></td></tr>'; 
    
    echo "<tr><td colspan=2><table id='lsg'>";
    
    echo'</tr></td></table>';  
    
  echo '<tr><td align=left colspan=2>';
        echo'<div style="align:left" id=block>';
                    
        echo'</div>';
        echo'</td></tr>';
        
        echo"<tr id='showgramapanchayath'></tr>";  
     }
else
{
    
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
    echo'<div style="align:left" id=block>';
    

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
                    echo '<table align=left ><tr><td width=100px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" tabindex=5 style="width:200px">';
                    echo "<option value='".$lsg_district."'>".$distname."</option>"; 
                    echo '</select></td>';    
                    echo '</tr></table>';      
          }
        
        }
        elseif($lsg_lsgtype==2) 
        {
            //echo"2222222";
        echo '<table align=left ><tr><td width=100px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" tabindex=5 style="width:200px">';
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
         echo '<table align=left ><tr><td width=100px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:200px" tabindex=5>';
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
      echo'</tr>'; 
      
      
      echo '<tr><td>' . _('Panchayat Name') . ':</td>';         //grama panchayath
         
         $sql="SELECT * FROM bio_panchayat WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:200px" tabindex=6>';
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
      echo'</tr></table>';      
} 
 
}
       //Taluk
        
    $sql="SELECT * FROM bio_taluk WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."'"; 
  $result=DB_query($sql,$db);
 
 echo"<tr id='showtaluk'><td>Taluk*</td><td>";
 echo '<select name="taluk" id="taluk" style="width:200px" tabindex=7 onchange="showvillage(this.value)">';
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
  echo'</td>'; 
  echo'</tr>';   
  
  
       //Village
       
       
      $sql="SELECT * FROM bio_village WHERE country='".$lsg_country."' AND state='".$lsg_state."' AND district='".$lsg_district."'";
  $result=DB_query($sql,$db);
 
 echo "<tr id='showvillage'><td>Village*</td><td>";
 echo '<select name="village" id="village" tabindex=8 style="width:200px"  >';
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
  echo'</td>'; 
  echo'</tr>';  
  
  
       //Contact Person
  
echo '<tr><td>Contact Person</td>';
echo "<td><input type=text name='contact' id='contact' value='$lsg_cont' tabindex=9 style='width:98%' ></td></tr>";   

      // Building Name
    
echo '<tr><td>Building Name</td>';
echo "<td><input type=text name='building_name' id='building_name' value='$lsg_building' tabindex=10 style='width:98%' ></td></tr>";      
              
    //Area ,  post_office , pin
    
 echo "<tr><td>Area:</td><td><input type='text' name='Area1' id='Area1' value='$lsg_area1' tabindex=11 onkeyup='' style='width:99%'></td></tr>";
 echo "<tr><td>Post Office:</td><td><input type='text' name='Area2' id='Area2' value='$lsg_area2' tabindex=12 onkeyup='' style='width:99%'></td></tr>";
 echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' value='$lsg_Pin' tabindex=13 onkeyup='' style='width:99%'></td></tr>";  
 
   // Phone_num , Mobile , Email 
   
  echo '<tr><td>Phone number*</td>';
  echo "<td><table><td><input type=text name='code' id='code' value='$code' style='width:50px' tabindex=14></td><td><input type=text name=phone tabindex=15 id=phone value='$phn' style='width:100%'></td></table></td></tr>";
    
  echo '<tr><td>Mobile Number</td>';
  echo "<td><input type=text name=mobile id=mobile  style='width:98%' value='$lsg_mobile' tabindex=16 ></td><td id=mob></td></tr>"; 
  echo '<tr><td>Email id</td>';
  echo "<td><input type=text name='email' id='email' value='$lsg_email' style='width:98%' tabindex=17 onchange='address()'></td></tr>"; 
  
  
  echo'</table>';       
  echo"</fieldset>";
  echo "</td>";
  
  
  
 
   //Project Details Fieldset........................Project Details Fieldset...............................Project Details Fieldset    
    
    
    
  echo "<td style='width:72%' valign=top>";
  echo "<fieldset style='float:right;width:100%;height:385px'>";         
  echo "<legend><h3>Project Details</h3>";
  echo "</legend>";
  
  echo "<table border=0 style='width:100%'>";  
  
   //Project Name
   
  
  echo '<tr><td width=30%>Project Name</td>';
  echo "<td><input type=text name='project_name' id='project_name' value='$project_name' style='width:92%' tabindex=18 onblur='project_name(this.value)'></td></tr>";    
  
  //Model of Plant
  
  
  
 /*$sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
$result_cat=DB_query($sql_cat,$db);
$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $plant_array=join(",", $cat_arr); 
} 
  
  
  
   $sql="SELECT categoryid,categorydescription from stockcategory
                    WHERE stockcategory.categoryid IN ($plant_array)";
   $result=DB_query($sql,$db);
      
//echo "<table border=0 style='align:left'>";
   echo "<tr><td>Model of Plant</td>";
   echo '<td><select name="Plant" id="Plant"  style="width:280px" tabindex=19>';
   $f=0;
      echo '<option value=0>Select category</option>';
      /*while ( $myrow = DB_fetch_array ($result) ) {
         echo "<option value=".$myrow[categoryid].">".$myrow[categorydescription]."</option>";
      }    
      echo '</select></td></tr>';  */
      
       /*while( $myrow=DB_fetch_array($result)) 
       {
      if ($myrow['categoryid']==$stock_cat) 
    {
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow['categoryid'] . '">'.$myrow['categorydescription'];
    echo '</option>';
    
       }
    echo '</select></td></tr>';     */
      
      
      
     
    //Total Project Cost
   
  
echo '<tr><td>Total Project Cost</td>';
echo "<td><input type=text name='total_projectcost' id='total_projectcost' value='$total_projectcost' style='width:280px' tabindex=20'></td></tr>"; 
  
echo "<tr><td colspan=2><table id='block1'>";
echo'</table></td></tr>';
  

 if($lsg_lsgtype==1)
 {   
    echo '<tr><td width=30%>Corporation</td>';
    echo "<td><input type=text name='main_share' id='main_share' value='$main_share' style='width:280px' tabindex=21></td></tr>";                 
 }
    
  elseif($lsg_lsgtype==2)
  {
     echo '<tr><td>Municipality</td>';
     echo "<td><input type=text name='main_share' id='main_share' value='$main_share' style='width:280px' tabindex=21'></td></tr>"; 
     
     echo '<tr><td>District/Corporation</td>';
     echo "<td><input type=text name='district_share' id='district_share' value='$district_share' style='width:280px' tabindex=21'></td></tr>";            
        
   }
    elseif($lsg_lsgtype==3)
    {
      echo '<tr><td>Grama Panchayat</td>';
      echo "<td><input type=text name='main_share' id='main_share' value='$main_share' style='width:280px' tabindex=21'></td></tr>";
 
      echo '<tr><td>Block Panchayat</td>';
      echo "<td><input type=text name='block_share' id='block_share' value='$block_share' style='width:280px' tabindex=21'></td></tr>"; 
 
      echo '<tr><td>District</td>';
      echo "<td><input type=text name='district_share' id='district_share' value='$district_share' style='width:280px' tabindex=21'></td></tr>";             
    
    }          
  
  
    
  
  //Beneficiary Share
  
      
/*echo '<tr><td>Beneficiary Share</td>';
echo "<td><input type=text name='beneficiary_share' id='beneficiary_share' value='$beneficiary_share' style='width:280px' tabindex=22 ></td></tr>";*/ 
   
 


     //Subsidy
     
 if(!isset($SelectedType)) {     
     
    echo '<tr><td>Subsidy</td>'; 
    $sql1="SELECT * FROM bio_schemes";
    $result1=DB_query($sql1, $db);
     $j=1;
    echo'<td>';
    while ($taskdetails=DB_fetch_array($result1))
    {
    echo'<input type="checkbox" id="schm"'.$j.' name="schm[]" tabindex=23 value='.$taskdetails[0].'>'.$taskdetails[1].'';       
    $j++;         
    } 
                               
    echo"</td></tr>"; 
 }else{   
    
    
    $schemeid2=explode(',',$schemeid);
    $n=sizeof($schemeid2);
    
    echo '<tr><td>Scheme</td>';
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
 }    
    //No: of Beneficiaries
                  
echo '<tr><td>No: of Beneficiaries</td>';
echo "<td><input type=text name='num_beneficiary' id='num_beneficiary' value='$num_beneficiaries' style='width:93%' tabindex=24 ></td></tr>";       
    
    
    //Remark

    echo'<tr><td>Remarks</td>'; 
    echo "<td><textarea name='remarks' id='remarks' rows=4 cols=42 tabindex=25 style=resize:none;>$remarks</textarea></td>"; 
    echo '</tr>'; 
    
   
   
  
    echo "</td></tr></table>";
    
   
   echo "</td></tr>";        
   echo '<tr><td colspan=2 align=center><input type=submit name="submit" id="reg"  value="Submit" onclick="if(validate()==1)return false;"></td></tr>';  
   echo "</td></tr></table>";
   echo "</fieldset>";                     
  // echo '</form>';
  
  
 //Proposal template fieldset............................proposal template fieldset...............................Proposal template fieldset                                                                                          
   
 
   /*echo "<fieldset style='width:870px'>";
   echo "<legend><h3>Proposal Template</h3>";     
   echo "</legend>";*/
 /*  
   $sql4="DELETE FROM bio_temppropitemslead where userid='".$_SESSION['UserID']."'";
   $result4=DB_query($sql4,$db);
   $sql_4="DELETE FROM bio_temppropsubsidyleads where userid='".$_SESSION['UserID']."'";
   $result_4=DB_query($sql_4,$db); 
   
   $sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
   $result_cat=DB_query($sql_cat,$db);
   $cat_arr=array();
   while($row_cat=DB_fetch_array($result_cat)) 
   {  
      $cat_arr[]="'".$row_cat['subcatid']."'";
      $plant_array=join(",", $cat_arr); 
   }
   
   $sql="SELECT categoryid,categorydescription from stockcategory
                    WHERE stockcategory.categoryid IN ($plant_array)";
   $result=DB_query($sql,$db);
      
   echo "<table border=0 style='align:left'>";
   echo "<tr><td>Catagory</td>";
   echo '<td><select name="caty" id="caty" style="width:300px" onchange=showcatitems(this.value)>';
      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[categoryid].">".$myrow[categorydescription]."</option>";
      }
      echo '</select></td>'; */
      //echo '</tr></table>';
      
     /*$sql="SELECT stockid,longdescription from stockmaster";
     $result=DB_query($sql,$db);
      
      echo '<td>Item</td>';
      echo '<td id=items><select name="Item" id="item" style="width:300px">';
      echo '<option value=0>Select Item</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[stockid].">".$myrow[longdescription]."</option>";
      }
      echo '</select></td>';
      
      echo '<td><input type="button" name="search" id="search" value="Add" style="width:100px" onclick="showItems()"></td></tr></table>';
      echo"<br />";
      echo"<div id='selecteditems'></div>";
      
     $first=$_GET['first'];                    // 1 -> from approval page   2 -> from cp page
     $budgetinitial=$_GET['budgetinitial'];        
     echo "<input type='hidden' name='Plantid' id='plantid' value='yes'>"; 
     echo '<input type="hidden" name="flag2" id="flag2" value='.$flag2.'>';
     echo '<input type="hidden" name="first" id="first" value='.$first.'>'; 
 */     
     /*       
     echo "<table border=1 >";
     echo "<tr>";
     echo '<td>Main Category<select name="MainCategoryID" id="maincatid" onchange="Filtercategory()">';   
     
     
     
     $sql = "SELECT maincatid, 
               maincatname 
        FROM bio_maincategorymaster
        ORDER BY maincatid DESC";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

while ($myrow=DB_fetch_array($result)){
    if ($myrow['maincatid']==1){
        echo '<option selected value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    } else {
        echo '<option value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    }
    $MainCategory=$myrow['maincatid'];
}
if (!isset($_POST['MainCategoryID'])) {
    $_POST['MainCategoryID']=$MainCategory;
}

echo '</select></td>';  */




 /*$sql="SELECT categoryid,categorydescription,maincatid from stockcategory,
             bio_maincat_subcat
             WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid
             AND bio_maincat_subcat.maincatid =1
             order by stockcategory.categorydescription asc";
      $result=DB_query($sql,$db);
echo '<td id="subcat">Sub Category<select name="caty" id="caty" style="width:200px">';
//      echo '<option value=0>Select category</option>';   

    $f=0;
  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['categoryid']==$planttype)  
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
    echo $myrow['categoryid'] . '">'.$myrow['categorydescription'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td>';  

  echo '<td><input type="button" name="search" id="search" value="Search" style="width:100px" onclick="showItems()"></td></tr></table>';  
 
if(isset($_GET['stockid'])) 
{
$stockid=$_GET['stockid'];

$sql_des="SELECT longdescription FROM stockmaster WHERE stockid='".$stockid."'";
$result_des=DB_query($sql_des,$db);
$myrow_des=DB_fetch_array($result_des);
$longdes=$myrow_des['longdescription'];

echo $price=GetPrice($stockid,$db); 

  echo $sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,subsidy,netprice) VALUES ('".$stockid."','".$longdes."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",'".$subsidy."',".$netprice.")";
 $result3=DB_query($sql3,$db);  
}
 
 
 
  echo "</fieldset>";*/   
  
           
 

            


/*echo "<form name=\"prop\" >";
$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$lead;
$result4=DB_query($sql4,$db);
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Unit Price</th><th>Gross Price</th><th>Subsidy</th><th>Net Price</th></tr><tbody>";
$k=0;

while ($myrow4=DB_fetch_array($result4))   {
  $tprice=$myrow4['qty']*$myrow4['price'];
$k++;
$stock[$k]=$myrow4['stockid'];
$item=$stock[$k];
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stockid."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." readonly></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='addSubsidy($lead,\"$item\",0,1)'>" . _('Manage Subsidy') . "</a></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='removeitm($lead,\"$item\",4,1)'>" . _('Remove') . "</a></td>
       
       </tr>";
}          */
 


/*echo "</fieldset>";
echo "</div>"; */    

//}      
   
  
    //LSG Register Details Fieldset........................LSG Register Details Fieldset...............................LSG Register Details Fieldset    
    
      
     echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";     
     echo "<fieldset style='float:center;width:90%;'>";     
     echo "<legend><h3>LSG REgister Details</h3>";
     echo "</legend>";
     
//=======================================================Search     
     echo "<table style='border:1px solid #F0F0F0;width:100%'>";
   
     echo"<tr><td>Date From</td>
              <td>Date To</td>
              <td>Project Name</td>
              <td>District</td>
              <td>Phone Number</td></tr>"; 
              
echo "<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo "<td><input type='text' name='byname' id='byname'></td>";
echo "<td><input type='text' name='byplace' id='byplace'></td>"; 
echo "<td><input type='text' name='mob' id='mob'></td>";

        

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='Search'></td>";
echo"</tr>";          
echo'</table>';          
                     
       
    echo "<table style='border:1px solid #F0F0F0;width:865px;' id='leadreport'>";
     echo "<thead>
                <tr BGCOLOR =#800000>
                <th width='50px'>" . _('SL No') . "</th>
                <th width='100px'>" . _('Project Name:') . "</th>
                <th width='170px'>" . _('LSG Name') . "</th>
                <th width='150px'>" . _('Contact Number') . "</th>
                <th width='85px'>" . _('Project Cost') . "</th>  
                <th width='85px'>" . _('Number of Beneficiaries') . "</th> 
                <th width='85px'>" . _('Status') . "</th>
                <th width='110px'>" . _(' ') . "</th> 
                <th width='110px'>" . _(' ') . "</th> 
                <th width='110px'>" . _(' ') . "</th> 
                </tr></thead>";
          
                
                  
    echo "</table>";            
                
  echo "<div style='height:500px; overflow:auto;'>";   
  echo "<table  style='border:1px solid #F0F0F0;width:830px;' id='LSGregisterdetailshead'>";              
          
  $sql="SELECT `bio_lsgreg`.`id`, 
               `bio_lsgreg`.`project_name`,  
               `bio_corporation`.`corporation`, 
               `bio_municipality`.`municipality`, 
               `bio_panchayat`.`name`, 
               `bio_cust`.`custphone`,  
               `bio_cust`.`custmob`, 
               `bio_cust`.`contactperson`, 
               `bio_cust`.`LSG_type`,
               `bio_cust`.`district`, 
               `bio_leads`.`leaddate`, 
               `bio_leads`.`leadid`,  
               `bio_lsgreg`.`total_projectcost`, 
               `bio_lsgreg`.`num_beneficiaries`,   
               `bio_lsgstatus`.`status`
          FROM `bio_leads`
    INNER JOIN `bio_cust` ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_lsgreg` ON (`bio_leads`.`leadid` = `bio_lsgreg`.`leadid`)
    INNER JOIN `bio_lsgstatus` ON (`bio_lsgreg`.`status` = `bio_lsgstatus`.`id`)
      LEFT JOIN bio_district ON bio_district.did = bio_cust.district
            AND bio_district.stateid = bio_cust.state
            AND bio_district.cid = bio_cust.nationality 
      LEFT JOIN bio_corporation ON bio_corporation.district = bio_cust.LSG_name 
            AND bio_corporation.district = bio_cust.district 
            AND bio_corporation.state = bio_cust.state 
            AND bio_corporation.country = bio_cust.nationality
      LEFT JOIN bio_municipality ON bio_municipality.id = bio_cust.LSG_name 
            AND bio_municipality.district = bio_cust.district 
            AND bio_municipality.state = bio_cust.state 
            AND bio_municipality.country = bio_cust.nationality
      LEFT JOIN bio_panchayat ON bio_panchayat.id = bio_cust.block_name 
            AND bio_panchayat.block = bio_cust.LSG_name 
            AND bio_panchayat.district = bio_cust.district 
            AND bio_panchayat.state = bio_cust.state 
            AND bio_panchayat.country = bio_cust.nationality 
          WHERE bio_cust.cust_id=bio_leads.cust_id";
   

 if(isset($_POST['filterbut1']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off1'];
  //  echo $officeid;
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql .= " AND bio_lsgreg.project_name LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql .= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }
    
    if (isset($_POST['mob'])) {
    if ($_POST['mob']!='') 
    $sql .= " AND bio_cust.custmob LIKE '%".$_POST['mob']."%'"; 
    }
    

 }
 //echo$sql;
 $result=DB_query($sql,$db);  

$k=0; //row colour counter
$slno=0;  
 while ($myrow = DB_fetch_array($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
   $slno++;
       $LSG_type=$myrow['LSG_type'];   
       

   if($LSG_type==1){
    $lsg_name=$myrow['corporation']."(C)";   
   } 
    else if($LSG_type==2){  
      $lsg_name=$myrow['municipality']."(M)";
        
    }
    else if($LSG_type==3)
    {
        $lsg_name=$myrow['name']."(P)";
    }
    $id=$myrow['id'];
    $leadid=$myrow['leadid']; 
   printf("<td cellpading=2 width='55px'>%s</td> 
            <td width='90px' >%s</td>
            <td width='100px'>%s</td>             
            <td width='90px'>%s</td>
            <td width='90px'>%s</td> 
            <td width='90px'>%s</td> 
            <td width='90px'>%s</td> 
            <td width='80px'><a href='%sSelectedType=%s'>Edit</td>    
            <td width='90px' ><a  style='cursor:pointer;'  id='$id' onclick='passid($leadid,$id)'>" . _('Status change') . "</a></td>  
            <td width='90px' ><a  style='cursor:pointer;'  id='$id' onclick='passid2($leadid,$id)'>" . _('Add beneficiary') . "</a></td>
            <td width='90px'><a  style='cursor:pointer;'  id='$id' onclick='passid3($leadid,$id)'>" . _('View') . "</a></td>  
            <td width='90px'><a  style='cursor:pointer;'  id='$id' onclick='passid4($leadid,$id)'>" . _('Add Proposal') . "</a></td></tr>",                                                                                                                                                                                                                                                   
            $slno,
            $myrow['project_name'], 
            $lsg_name,
            $myrow['custmob'],
            $myrow['total_projectcost'],   
            $myrow['num_beneficiaries'],
            $myrow['status'],$_SERVER['PHP_SELF'] . '?', 
            $id ,
            $id,
            $id);
}  
echo'</div>';
echo'</table>';       
echo"</fieldset>";
echo"</form>";
?> 
  
 <script type="text/javascript">     
 
 
  function replace_html(id, content) {
    document.getElementById(id).innerHTML = content;
}
var progress_bar = new Image();
progress_bar.src = '4.gif';
function show_progressbar(id) {
    replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}


function validate()
{
     
 var f=0; 
if(f==0){f=common_error('District','Please Select a District');  if(f==1) { return f; }}     
if(f==0){f=common_error('lsgType','Please Select a lsgType');  if(f==1) { return f; }}   

if(f==0){f=common_error('taluk','Please Select a taluk');  if(f==1) { return f; }}     
if(f==0){f=common_error('village','Please Select a village');  if(f==1) { return f; }}          
if(f==0){f=common_error('contact','Please enter your contact');  if(f==1) { return f; }}          
if(f==0){f=common_error('Area1','Please enter your area');  if(f==1) { return f; }} 
    //if(f==0){f=common_error('mobile','Please enter your mobile number');  if(f==1) { return f; }}  
    
  if(f==0)
{
    var y=document.getElementById('phone').value; 
    var x=document.getElementById('mobile').value;
    if(x=="" && y==""){ alert("Please enter atleast one contact number");f=1;} 
    if(f==1) { document.getElementById('phone').focus();return f; } }  
 
/*if(f==0)
{
    var y=document.getElementById('phone').value; 
    var x=document.getElementById('mobile').value;
    if(x=="" && y==""){ alert("Please enter atleast one contact number");f=1;} 
    if(f==1) { document.getElementById('phone').focus();return f; } }*/     

    
   if(f==0)
{

    
    var x=document.getElementById('phone').value;         
    var y=document.getElementById('mobile').value;
   // alert(x);
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
    
    if(y!=""){             
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
    }
    
}
}                        

 
   function address()
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

}     
    
 
  function showstate(str){ 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {             
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;
    document.getElementById("state").focus();
    }
  }
xmlhttp.open("GET","bio_showstate_lsg.php?country=" + str,true);
xmlhttp.send();
}  



 function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
show_progressbar('showdistrict');
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;
           document.getElementById('District').focus();

    }
  }
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

} 


function showblock(str){   
    
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;              
     str3=document.getElementById('District').value;                 
     if (str3=="")
     {
     alert("Please select a district");    
     document.getElementById("District").focus();
     document.getElementById("lsgType").value=0;
     return;
     }
     

     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("block").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
  
xmlhttp.open("GET","bio_CustlsgSelection_lsg.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}  


 
function showamt(str){   
       //   alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;              
     str3=document.getElementById('District').value;                 
     if (str3=="")
     {
     alert("Please select a district");    
     document.getElementById("District").focus();
     document.getElementById("lsgType").value=0;
     return;
     }
     

     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         
         alert("No Corporation for this district");
         document.getElementById("block1").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("block1").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("block1").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgregistration.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}  

  
  function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('District').value;
//alert(str1);   alert(str2);       alert(str3);
if (str1=="")
  {
  document.getElementById("showtaluk").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_lsg.php?taluk=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 


/*function getresult(choose,first){
//    alert(choose);   alert(first); 
    if(first!=5){                                                                                             // for cp
window.opener.location='bio_conceptproposal.php?stockid='+choose+'&lead='+<?php echo $lead;?>+'&first='+first;
    }
    else
    {                                                                                                        // for approval
     window.opener.location='bio_approval.php?stockid='+choose+'&lead='+<?php echo $lead;?>;   
    }
window.close();
}          */


    function passid(str1,str2)
    {
             myRef = window.open("bio_lsgstatuschange.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
    }
    function passid2(str1,str2)
    {
             myRef = window.open("bio_lsgaddbeneficiary.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=700");
    } 
    function passid3(str1,str2)
    {
             myRef = window.open("bio_viewlsgben.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=700");    
    } 
    function passid4(str1,str2)
    {
             myRef = window.open("bio_lsgproposal.php?leadid=" + str1 + "&id=" + str2 ,"lead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=700");       
    }   
      


function showgramapanchayath(str){   
   //alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('District').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showgramapanchayath").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("showgramapanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_lsg.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}   



     

  function showvillage(str){   
  // alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('District').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showvillage").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("showvillage").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_lsg.php?village=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}    
  
function showItems()
{               
    var str=document.getElementById("caty").value; 
   myRef = window.open("bio_lsgplantselect.php?categoryid=" +str ,"lsgplant","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
   
}
/*  
 function showItems()
{
      
var str1=document.getElementById("maincatid").value;

alert(str1);
 alert(str2); 
if(str1==0){
alert("select a Catagory"); document.getElementById("caty").focus();  return false;  }
if(str2==0){
alert("select an Item"); document.getElementById("item").focus();  return false;  }
if (str1==0)
  {
  document.getElementById("selecteditems").innerHTML="";     //editleads
  return;
  }
 if (str2==0)
  {
  document.getElementById("selecteditems").innerHTML="";     //editleads
  return;
  }        
  
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("grid").innerHTML=xmlhttp.responseText;
//    document.getElementById('caty').value="";       document.getElementById('item').value="";
    }
  } 
xmlhttp.open("GET","bio_additemsforproptemplate.php?cat=" + str1  + "&item=" + str2 ,true);
xmlhttp.send();    

} 
   */

 function showcatitems(str1){
if (str1=="")
  {
  document.getElementById("items").innerHTML="";     //editleads
  return;
  }  

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("items").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_selectplantforlead.php?catid="+str1,true);
xmlhttp.send();
  
}

 
 function Filtercategory()       {

var str= document.getElementById("maincatid").value;
    
if (str=="")
  {
  document.getElementById("propertyvalue").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        
    document.getElementById("subcat").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_maincatsubcatcp.php?maincatid="+str,true);
xmlhttp.send(); 
    

}


 function addfund(str) 
 {
           //alert(str);
  controlWindow=window.open("bio_addfundingagency.php?stockid="+str,"addfund","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
  
 }  




</script>   
    
    
    
    
       
   
    
    
    
  
       
 
   
   
 