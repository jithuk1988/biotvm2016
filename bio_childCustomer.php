<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Create Dealers Customer');  
include('includes/header.inc');

include('includes/sidemenu.php');



echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">DEALERS CUSTOMER</font></center>';
          
           $phone= $_POST['code']."-".$_POST['phone'];  
           
 if ((isset($_POST['SelectedType'])) && ($_POST['SelectedType']!="")){
    $SelectedType = strtoupper($_POST['SelectedType']);
} elseif ((isset($_GET['SelectedType'])) && ($_GET['SelectedType']!="")){
    $SelectedType = strtoupper($_GET['SelectedType']);
}          
           
       
 if(isset($_POST['submit']))  
 {

     
       $country= $_POST['country'];
       $state= $_POST['state'];
       $district= $_POST['District'] ;
       $lsgtype= $_POST['lsgType'];
       $taluk=$_POST['taluk'];
       $village=$_POST['village'];
       $lsgname=$_POST['lsgName'];  
       
       $panchayat=$_POST['gramaPanchayath'];  
       if(!$_POST['gramaPanchayath'])
       {
         $panchayat='';  
       }
       $contact=$_POST['contact'];
       $building=$_POST['building_name'];
       $area1=$_POST['Area1'];
       $area2=$_POST['Area2'];
       $Pin=$_POST['Pin'];
       $code=$_POST['code'];
       $phone= $_POST['phone'];
       if($code!=''&&$phone!='')
       {
       $phone= $code."-".$phone;
       }
       else if($code!=''&&$phone=='')
       {
         $phone='';  
       }
         else if($code==''&&$phone=='')
       {
         $phone='';  
       }
       else
       {
          $phone= $phone;
       }           
       $mobile=$_POST['mobile'];
       $email=$_POST['email'];          
       
//       $enqtype=$_POST['enqtype'];
       $dealer=$_POST['dealer']; 
       $stockCategory=$_POST['StockCat'];   
       
       $orderno=$_POST['order']; 
         
          
       if (isset($SelectedType)) 
       {    

   $sql_cust = "UPDATE bio_childcustomer SET cid = '".$country."',
                                             stateid =  '".$state."',
                                             did = '".$district."',
                                             LSG_type = '".$lsgtype ."',
                                             LSG_name = '".$lsgname."',
                                             block_name = '".$panchayat ."', 
                                             taluk = '".$taluk ."',
                                             village = '".$village."',
                                             name= '".$contact."',
                                             houseno = '".$building."',   
                                             area1 = '".$area1."',
                                             area2 = '".$area2."',
                                             pin = '".$Pin."',
                                             phoneno = '".$phone."',
                                             mobileno = '".$mobile."',
                                             email = '".$email."'
                                       WHERE id='".$SelectedType."'";  
            DB_query($sql_cust,$db);           
                       

                                                   
      $msg = _('Debtors customer details') . ' ' . $SelectedType . ' ' .  _(' has been updated');     
       
      }else{  
      
      $result=DB_query("SELECT stockid FROM temp_dealerqty WHERE orderno=$orderno",$db);
      $myrow=DB_fetch_array($result); 

     $sql="INSERT INTO bio_childcustomer   (     name, 
                                                 phoneno,  
                                                 mobileno, 
                                                 email,  
                                                 houseno,
                                                 area1,
                                                 area2,
                                                 pin,
                                                 cid,
                                                 stateid,
                                                 did,
                                                 LSG_type,
                                                 LSG_name,
                                                 block_name,
                                                 taluk,
                                                 village,
                                                 stockid,
                                                 dealercode,
                                                 orderno)             
                                  VALUES  (   '".$contact."',
                                              '".$phone."',  
                                              '".$mobile."',  
                                              '".$email."',
                                              '".$building."',
                                              '".$area1."',
                                              '".$area2."',
                                              '".$Pin."',
                                              '".$country."',
                                              '".$state."',
                                              '".$district."',
                                              '".$lsgtype."',
                                              '".$lsgname."', 
                                              '".$panchayat."', 
                                              '".$taluk."',
                                              '".$village."',
                                              '".$myrow['stockid']."', 
                                              '".$dealer."',
                                              '".$orderno."')";
                        
       $result=DB_query($sql,$db);  
       
         
       $childid=DB_Last_Insert_ID($Conn,'bio_childcustomer','id');   
       
   
      $sql2="INSERT INTO bio_childcustomerdetails (childid,orderno,lineno,quantity) SELECT $childid,$orderno,orderline,qty FROM temp_dealerqty WHERE orderno=$orderno"; 
      DB_query($sql2,$db);                                                      
                                                          
      DB_query("DROP table temp_dealerqty",$db);       

     $sql_documents="INSERT INTO bio_dealersclient_doc (
                                        childid,
                                        docno,
                                        status)
                                     SELECT $childid,doc_no,0 from bio_document_master where enqtypeid=1";
     DB_query($sql_documents,$db);            
             
 } 
          
 }    
                    
 
echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post name='form'>";
   
    
   echo"<table style='width:100%;height:100%;><tr><td>";
   
   echo "<table border=0 style='width:55%;height:100%;'>"; 
   echo "<tr><td style='width:50%' valign=top>";
 

 

 //Basic Details fieldset................................Basic Details Fieldset.............................Basic Details Fieldset                  
 

 
 echo "<fieldset style='float:left;width:90%;height:auto'>";     
 echo "<legend><h3>Customer Details</h3>";
 echo "</legend>";                             
 echo "<table style='height:100%'>";  
 
 
 
 if ( isset($SelectedType) && $SelectedType!="")
 {

                        
                                      
     $sql_cust="SELECT `bio_childcustomer`.`cid`,
                       `bio_childcustomer`.`stateid`,
                       `bio_childcustomer`.`did`,  
                       `bio_childcustomer`.`phoneno`, 
                       `bio_childcustomer`.`mobileno`, 
                       `bio_childcustomer`.`name`, 
                       `bio_childcustomer`.`houseno`, 
                       `bio_childcustomer`.`area1`, 
                       `bio_childcustomer`.`area2`,                       
                       `bio_childcustomer`.`pin`, 
                       `bio_childcustomer`.`email`,  
                       `bio_childcustomer`.`LSG_type`, 
                       `bio_childcustomer`.`LSG_name`, 
                       `bio_childcustomer`.`block_name`, 
                       `bio_childcustomer`.`taluk`, 
                       `bio_childcustomer`.`village`,
                       `bio_childcustomer`.`dealercode`
   
                   FROM bio_childcustomer
                  WHERE bio_childcustomer.id=".$SelectedType;

     $result = DB_query($sql_cust, $db);
     $myrow = DB_fetch_array($result);
     
     $country   = $myrow['cid'];
     $state     = $myrow['stateid'];
     $district  = $myrow['did'];
     $lsgtype   = $myrow['LSG_type'];
     if($lsgtype==1){$lsgtypename="Corporation";}
     elseif($lsgtype==2){$lsgtypename="Municipality";}
     elseif($lsgtype==3){$lsgtypename="Panchayath";} 
     $lsgname   = $myrow['LSG_name'];
     $panchayat = $myrow['block_name'];
     $taluk     = $myrow['taluk'];
     $village   = $myrow['village'];  
     $cont      = $myrow['name'];  
     $building  = $myrow['houseno'];  
     $area1     = $myrow['area1'];  
     $area2     = $myrow['area2'];  
     $Pin       = $myrow['pin']; 
     $code      = $myrow['phoneno'];  
     $mobile     = $myrow['mobileno'];  
     $email      = $myrow['email'];  
     $contact    = $myrow['name'];  
    
//    $enqtypeid     =  $myrow['enqtype'];  
    $dealercode    =  $myrow['dealercode']; 
                
     $landphn=explode("-",$code);
     $code=$landphn[0]; 
     $phn=$landphn[1];  
    
   echo"<input type=hidden name=SelectedType id=SelectedType value=$SelectedType>";   

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
  if ($myrow1['did']==$district)
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
    echo '<option value='.$lsgtype.'>'.$lsgtypename.'</option>';   
    
  if($lsgtype==1){   
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
    }elseif($lsgtype==2){
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
    }elseif($lsgtype==3){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
    }         
      echo '</select></td></tr>';
      
    echo '<tr><td align=left colspan=2>';
    echo'<div style="align:left" id=block>';
    

 if($lsgtype==1) 
 {
        
  $sql="SELECT * FROM bio_corporation WHERE country='".$country."' AND state='".$state."' AND district='".$district."'";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];
 
          if($country==1 && $state==14)  
          {              
              if($district==12){$distname='Thiruvananthapuram';}
              if($district==6){$distname='Kollam';} 
              if($district==2){$distname='Eranakulam';} 
              if($district==13){$distname='Thrissur';} 
              if($district==8){$distname='Kozhikode';} 
                    echo '<table align=left ><tr><td width=100px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" tabindex=5 style="width:200px">';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td>';    
                    echo '</tr></table>';      
          }
        
        }
        elseif($lsgtype==2) 
        {
            //echo"2222222";
        echo '<table align=left ><tr><td width=100px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='".$country."' AND state='".$state."' AND district='".$district."'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" tabindex=5 style="width:200px">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$lsgname)
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
        
        elseif($lsgtype==3) 
        {
            //echo"3333333"; 
         echo '<table align=left ><tr><td width=100px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country='".$country."' AND state='".$state."' AND district='".$district."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:200px" tabindex=5>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$lsgname)
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
         
         $sql="SELECT * FROM bio_panchayat WHERE country='".$country."' AND state='".$state."' AND district='".$district."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:200px" tabindex=6>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$panchayat)
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
        
    $sql="SELECT * FROM bio_taluk WHERE country='".$country."' AND state='".$state."' AND district='".$district."'"; 
  $result=DB_query($sql,$db);
 
 echo"<tr id='showtaluk'><td>Taluk*</td><td>";
 echo '<select name="taluk" id="taluk" style="width:200px" tabindex=7 onchange="showvillage(this.value)">';
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
  echo'</td>'; 
  echo'</tr>';   
  
  
       //Village
       
       
  $sql="SELECT * FROM bio_village WHERE country='".$country."' AND state='".$state."' AND district='".$district."'";
  $result=DB_query($sql,$db);
 
 echo "<tr id='showvillage'><td>Village*</td><td>";
 echo '<select name="village" id="village" tabindex=8 style="width:200px"  >';
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
  echo'</td>'; 
  echo'</tr>';  
  
  
       //Contact Person
  
echo '<tr><td>Contact Person</td>';
echo "<td><input type=text name='contact' id='contact' value='$cont' tabindex=9 style='width:98%' onblur=dealersarea();></td></tr>";   

      // Building Name
    
echo '<tr><td>Building Name</td>';
echo "<td><input type=text name='building_name' id='building_name' value='$building' tabindex=10 style='width:98%' ></td></tr>";      
              
     //Area ,  post_office , pin
    
 echo "<tr><td>Area:</td><td><input type='text' name='Area1' id='Area1' value='$area1' tabindex=11 onkeyup='' style='width:99%'></td></tr>";
 echo "<tr><td>Post Office:</td><td><input type='text' name='Area2' id='Area2' value='$area2' tabindex=12 onkeyup='' style='width:99%'></td></tr>";
 echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' value='$Pin' tabindex=13 onkeyup='' style='width:99%'></td></tr>";  
 
   // Phone_num , Mobile , Email 
   
  echo '<tr><td>Phone number*</td>';
  echo "<td><table><td><input type=text name='code' id='code' value='$code' style='width:50px' tabindex=14></td><td><input type=text name=phone tabindex=15 id=phone value='$phn' style='width:100%'></td></table></td></tr>";
    
  echo '<tr><td>Mobile Number</td>';
  echo "<td><input type=text name=mobile id=mobile  style='width:98%' value='$mobile' tabindex=16 ></td><td id=mob></td></tr>"; 
  echo '<tr><td>Email id</td>';
  echo "<td><input type=text name='email' id='email' value='$email' style='width:98%' tabindex=17 onchange='address()'></td></tr>"; 
  
    
  echo'</table>';       
  echo"</fieldset>";
  echo "</td>";
  
  
  
 
   //Dealer Details Fieldset........................Dealer Details Fieldset...............................Dealer Details Fieldset    
    
    
    
  echo "<td style='width:72%' valign=top>";
  echo "<fieldset style='float:right;width:100%;height:385px'>";         
  echo "<legend><h3>Dealer Selection</h3>";
  echo "</legend>";
  
  
  
  echo "<table style='margin-left:10%'>"; 
  echo "<tr id=showdealer>"; 
  
  echo '<td>Dealer</td><td> <select name="dealer" id="dealer">'; 
  echo '<option value="">Select Dealer</option>'; 
  echo '</select></td></tr>';
  

                 
echo"<tr><td colspan=2>";    
echo"<div id=dealerdetail>";

echo"</div>";
echo"</td></tr>"; 

echo"<tr><td colspan=2>";  
echo"<div id=plantselection>"; 
echo"</div>";
echo"</td></tr>"; 
    
    
    
    echo"</table>";
    
   
   echo "</td></tr>";        
   echo '<tr><td colspan=2 align=center><input type=submit name="submit" id="reg"  value="Submit" onclick="if(validate()==1)return false;"></td></tr>';  
   echo "</td></tr></table>";
   echo "</fieldset>";                     
  // echo '</form>';

      
  
     echo "<fieldset style='float:center;width:90%;'>";     
     echo "<legend><h3>Child Customer details</h3>";
     echo "</legend>";
     

                     
    echo "<div style='height:200px; overflow:auto;'>";  
       
    echo "<table style='border:1px solid #F0F0F0;width:865px;' id='leadreport'>";
    echo "<thead>
                <tr BGCOLOR =#800000>
                <th >" . _('SL No') . "</th>
                <th >" . _('Orderno & Date') . "</th>  
                <th >" . _('Customer Name') . "</th>
                <th >" . _('Contact Number') . "</th>
                <th >" . _('District') . "</th>  
                </tr>
           </thead>";       
//    echo "</table>";            
                
 
//  echo "<table style='border:1px solid #F0F0F0;width:830px;'>";              
          
  $sql="SELECT `bio_childcustomer`.`id`, 
               `bio_childcustomer`.`name`,  
               `bio_childcustomer`.`phoneno`,  
               `bio_childcustomer`.`mobileno`, 
               `bio_childcustomer`.`LSG_type`,
               `bio_childcustomer`.`orderno`,
                salesorders.orddate,
               `bio_district`.`district`,
               `bio_corporation`.`corporation`, 
               `bio_municipality`.`municipality`, 
               `bio_panchayat`.`name` AS panchayath,
                debtorsmaster.name AS dealername,
                debtorsmaster.debtorno 

          FROM `bio_childcustomer`
     INNER JOIN debtorsmaster ON bio_childcustomer.dealercode=debtorsmaster.debtorno     
     INNER JOIN salesorders ON salesorders.orderno=bio_childcustomer.orderno     
      LEFT JOIN bio_district ON bio_district.did = bio_childcustomer.did
            AND bio_district.stateid = bio_childcustomer.stateid
            AND bio_district.cid = bio_childcustomer.cid 
      LEFT JOIN bio_corporation ON bio_corporation.district = bio_childcustomer.LSG_name 
            AND bio_corporation.district = bio_childcustomer.did 
            AND bio_corporation.state = bio_childcustomer.stateid 
            AND bio_corporation.country = bio_childcustomer.cid
      LEFT JOIN bio_municipality ON bio_municipality.id = bio_childcustomer.LSG_name 
            AND bio_municipality.district = bio_childcustomer.did 
            AND bio_municipality.state = bio_childcustomer.stateid 
            AND bio_municipality.country = bio_childcustomer.cid
      LEFT JOIN bio_panchayat ON bio_panchayat.id = bio_childcustomer.block_name 
            AND bio_panchayat.block = bio_childcustomer.LSG_name 
            AND bio_panchayat.district = bio_childcustomer.did 
            AND bio_panchayat.state = bio_childcustomer.stateid 
            AND bio_panchayat.country = bio_childcustomer.cid 
      ORDER BY  bio_childcustomer.dealercode,bio_childcustomer.orderno";
/* //     WHERE bio_cust.cust_id=bio_leads.cust_id
   

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
    

 }             */
 //echo$sql;
 $result=DB_query($sql,$db);  

$k=0; //row colour counter
$slno=0;  
$f=0;
 while ($myrow = DB_fetch_array($result)) {
     
   
       
   if($f==0){
       $temp=$myrow['dealername']; 
       echo"<tr><td><b>".$myrow['dealername']."</b></td></tr>"; $f++; 
   }
   if($temp!=$myrow['dealername']){
       echo"<tr><td><b>".$myrow['dealername']."</b></td></tr>";
       $temp=$myrow['dealername'];      
       $slno=0;  
   }
     
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
        $lsg_name=$myrow['panchayath']."(P)";
    }
    $id=$myrow['id'];

   printf("<td cellpading=2 >%s</td> 
                        <td >%s - %s</td>             
                        <td >%s</td>
                        <td >%s</td> 
                        <td >%s</td> 
                        <td ><a href='%sSelectedType=%s'>Edit</td>  
                        <td><a style='cursor:pointer;' id='$id' onclick='inspection(this.id)'>" . _('Select') . "</a></td> 
                        </tr>",                                                                                                                                                                                                                                                   
                        $slno,
                        $myrow['orderno'],
                        ConvertSQLDate($myrow['orddate']),  
                        $myrow['name'], 
                        $myrow['phoneno'],
                        $myrow['district'],  
                        $_SERVER['PHP_SELF'] . '?',$id);
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
  

  function dealerdetails(str){   
// alert(str);

if (str=="")
  {
  document.getElementById("dealerdetail").innerHTML="";
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
     document.getElementById("dealerdetail").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_dealerdetails.php?dealerdebtor=" + str ,true);
xmlhttp.send(); 
}    

function selectplant(str){   
 //alert(str);

if (str=="")
  {
  document.getElementById("plantselection").innerHTML="";
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
     document.getElementById("plantselection").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_dealerdetails.php?dealersorder=" + str ,true);
xmlhttp.send(); 
}   


function selectedqty(str,str2,str3,str4){   
// alert(str);
// alert(str2);
if (str=="")
  {
  document.getElementById("plantselection").innerHTML="";
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
//    document.getElementById("plantselection").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_dealerdetails.php?selectedqty=" + str + "&orderline=" + str2 + "&orderno=" + str3 + "&stockid=" + str4,true);
xmlhttp.send(); 
}   



function inspection(str)
{              
controlWindow=window.open("bio_dealerinspection.php?childid="+str,"inspection","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=400");
}
      


function dealersarea(){   
    
     cid=document.getElementById("country").value;           
     sid=document.getElementById('state').value;             
     did=document.getElementById('District').value;          
     lsg=document.getElementById('lsgType').value;            
     nam=document.getElementById('lsgName').value;               
     
     lsgdetail="&lsg=" + lsg + "&nam=" +nam;
     if(lsg==3 && nam!=""){        
     blc=document.getElementById('gramaPanchayath').value; 
     lsgdetail+="&blc=" +blc;      
     }
     
if (cid=="")
  {
  document.getElementById("showdealer").innerHTML="";
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
    document.getElementById("showdealer").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_dealerdetails.php?cid=" + cid + "&sid=" + sid + "&did=" + did+lsgdetail,true);
xmlhttp.send(); 
}   
      
</script>   
    
    
    
    
       
   
    
    
    
  
       
 
   
   
 