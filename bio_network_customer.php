<?php
$PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Register Network Group'); 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">NETWORK GROUP REGISTER</font></center>';
 $cc=1;    
 $state=14;   
 if($_GET['edit']!="")
  {   
      $rid=$_GET['edit']; 
      $eid=$_GET['edit'];  
      $sql4="SELECT * from  bio_network_cust where  bio_network_cust.id=$rid";
     $result4=DB_query($sql4,$db); 
      $myrow4=DB_fetch_array($result4); 
      $cust_co=$myrow4['cust_code'] ; 
      $cust_na=$myrow4['cust_name'] ; 
      $hno=$myrow4['houseno'] ;
      $hna=$myrow4['housename'] ; 
      $area=$myrow4['area'] ; 
      $poff=$myrow4['post_office'] ; 
      $pin=$myrow4['pin'] ; 
      $cc=$myrow4['nationality'] ; 
      $state=$myrow4['state'] ; 
      $dis=$myrow4['district'] ; 
      $lsgnam=$myrow4['LSG_name'] ; 
      $bnam=$myrow4['block_name'] ;
      $lsg_type=$myrow4['LSG_type'] ; 
    if($lsg_type==1)
     {
     $lsgtype="Corporation";}
     elseif($lsg_type==2){
         $lsgtype="Municipality";
/*     $sql5="SELECT id,municipality from bio_municipality where  bio_municipality.id=$lsgnam"; 
     $result5=DB_query($sql5,$db); 
      $myrow5=DB_fetch_array($result5);
      $lsgname=$myrow5['municipality'] ; 
      $lsgid=$myrow5['id'] ;  */ 
      
     }
     elseif($lsg_type==3){
     $lsgtype="Panchayath";
 /*    $sql5="SELECT bio_block.id as bid ,bio_block.block,bio_panchayat.id as pid,bio_panchayat.name from bio_block,bio_panchayat where  bio_block.id=$lsgnam AND bio_panchayat.id=$bnam"; 
     $result5=DB_query($sql5,$db); 
      $myrow5=DB_fetch_array($result5);
      $lsgname=$myrow5['block'] ; 
      $lsgid=$myrow5['bid'] ; 
      $bname=$myrow5['name'] ; 
      $pname=$myrow5['pid'] ;  */ 
     }              
  
      if($myrow4['phoneno']!=0){$phno=explode("-",$myrow4['phoneno']) ;$pcode=$phno[0]; $pno=$phno[1];}      
      if($myrow4['mobileno']!=0){ $mobno=$myrow4['mobileno'] ; }
      if($myrow4['mailid']!=null){  $mailid=$myrow4['mailid'] ; } 
      $idtype=$myrow4['identity_type'] ;
      $idno=$myrow4['identity_no'] ;          
        
      
  } 
if(isset($_POST['submit']))  
 {  
          $ss=$_POST['stop']; 
         if($_POST['code']!="")
          { 
        $phone=$_POST['code']."-".$_POST['phone'];    
         } 
  $sql="INSERT INTO `bio_network_cust`(`cust_code`,
                                            `cust_name`,
                                            `houseno`, 
                                            `housename`,
                                            `area`,
                                            `post_office`, 
                                            `pin`,
                                            `nationality`,
                                            `state`,
                                            `district`,
                                            `LSG_type`,
                                            `LSG_name`, 
                                            `block_name`,
                                            `phoneno`, 
                                            `mobileno`, 
                                            `mailid`, 
                                            `identity_type`, 
                                            `identity_no`) 
                                 VALUES ('".$_POST['custcode']."',
                                 '".$_POST['custname']."',
                                 '".$_POST['Houseno']."',
                                 '".$_POST['HouseName']."',
                                 '".$_POST['Area1']."',
                                 '".$_POST['Area2']."',
                                 '".$_POST['Pin']."',
                                 '".$_POST['country']."',
                                 '".$_POST['State']."',
                                 '".$_POST['District']."',
                                 '".$_POST['lsgType']."',
                                 '".$_POST['lsgName']."',
                                 '".$_POST['gramaPanchayath']."',
                                 '".$phone."',      
                                 '".$_POST['mobile']."',
                                 '".$_POST['email']."',
                                 '".$_POST['identity']."',
                                 '".$_POST['identityno']."')";

if($ss==2)
{
DB_query($sql,$db);  
  echo "<div class=success>NETWORK CUSTOMER REGISTER SUCCRSSFULL </div>"  ;                     
}
else
{
echo "<div class=error><b>Duplicate Entery</b></div>"  ; 
}
} 
  if(isset($_POST['edit']))
 {   
          if($_POST['code']!="")
          { 
        $phone=$_POST['code']."-".$_POST['phone'];    
         } 
$upsql="  UPDATE `bio_network_cust` 
SET 
`cust_name`='".$_POST['custname']."',
`houseno`='".$_POST['Houseno']."',
`housename`='".$_POST['HouseName']."',
`area`='".$_POST['Area1']."',
`post_office`='".$_POST['Area2']."',
`pin`='".$_POST['Pin']."',
`nationality`='".$_POST['country']."',
`state`='".$_POST['State']."',
`district`='".$_POST['District']."',
`LSG_type`='".$_POST['lsgType']."',
`LSG_name`='".$_POST['lsgName']."',
`block_name`='".$_POST['gramaPanchayath']."',
`phoneno`='".$phone."',
`mobileno`='".$_POST['mobile']."',
`mailid`='".$_POST['email']."',
`identity_type`='".$_POST['identity']."',
`identity_no`='".$_POST['identityno']."' WHERE id='".$_POST['eid']."' ";
     DB_query($upsql,$db); 
 }

echo'<table width=98% ><tr><td>'; 
echo'<div>';                                                                       

echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
echo"<fieldset >";
     echo'<div id="dup"></div>';   
echo"<legend><h3>Customer Details</h3></legend>";
 echo'<table  ><tr><td>Customer code:</td>'; 
 if($rid==null){ echo "<td><input type='text' name='custcode' id='custcode' value='".$cust_co."'  style='width:190px;text-transform:uppercase;' onchange='duplicate()'></td>";}
 else{echo "<td><input type='text' name='custcode' readonly id='custcode' value='".$cust_co."'  style='width:190px;text-transform:uppercase;' onchange='duplicate()'></td>";}
 echo'<tr><td>Customer Name:</td>';  
 echo "<td><input type='text' name='custname' id='custname' value='".$cust_na."'  style='width:190px;text-transform:capitalize;'></td>";
 echo "<tr><td>House No:</td><td><input type='text' name='Houseno' id='Houseno'  value='".$hno."'  style=width:190px></td></tr>";    
    echo "<tr><td>House Name</td><td><input type='text' name='HouseName' id='HouseName' value='".$hna." '  style='width:190px;text-transform:capitalize;'></td></tr>";
    echo "<tr><td>Residencial Area:</td><td><input type='text' name='Area1' id='Area1'  value='".$area."'  style='width:190px;text-transform:capitalize;'></td></tr>";
    echo "<tr><td>Post Office:</td><td><input type='text' name='Area2' id='Area2'  value='".$poff."'  style='width:190px;text-transform:capitalize;'></td></tr>";
    echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin'  value='".$pin." ' style=width:190px></td></tr>";    
   $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
    
    echo"<tr><td style='width:50%'>Country*</td><td>";
    echo '<select name="country" id="country"  onchange="showstate(this.value)" style="width:190px">';
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==$cc)  
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
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td></tr>';
 
    $sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showstate'><td>State*</td><td>";
 echo '<select name="State" id="state" style="width:190px"  onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==$state)
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
 echo '<select name="District" id="Districts" style="width:190px"  onchange="showtaluk(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$dis)
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

/*  echo '<tr><td>' . _('LSG Type') . ':</td>';
echo '<td><select name="lsgType" id="lsgType" style="width:190px"  onchange=showblock(this.value)>';    
if($lsg_type!=null) {        
echo '<option value='.$lsg_type.'>'.$lsgtype.'</option>';   
}    
  if($lsg_type==1){   
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
    }elseif($lsg_type==2){
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
    }elseif($lsg_type==3){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
    }else{
           echo '<option value=0></option>';
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>'; 
    }      
     echo '</select></td></tr>';  
        echo '<tr><td align=left colspan=2>';
     echo'<div style="align:left" id=block><table><tr><td><input type="hidden" name="lsgName" value='.$lsgid.'></td><td>'.$lsgname.'</td><tr><td><input type="hidden" name="gramaPanchayath" value='.$pname.'></td><td>'.$bname.'</td></tr></table>';
                    
        echo'</div>';    
        echo'</td></tr>';    */  
      // echo'<tr><td></td><td id=block>'.$lsgname.'</td>';
       //echo"<tr id='showgramapanchayath'></tr>";  
        
   // echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
           //   <td><input  type="Text" name="lsgWard" id="lsgWard" style=width:190px maxlength=15 value=""></td></tr>';       
              
      //  echo"<tr id='showtaluk'></tr>";   
if(!isset($eid)) {
    
 
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:190px"  onchange="showblock(this.value);">';
    
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
    echo '<td><select name="lsgType" id="lsgType" style="width:190px"  onchange=showblock(this.value)>';             
       
echo '<option value='.$lsg_type.'>'.$lsgtype.'</option>';   
  
    
  if($lsg_type==1){   
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
    }elseif($lsg_type==2){
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
    }elseif($lsg_type==3){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
    }         
      echo '</select></td></tr>';
      
   echo '<tr><td align=left colspan=2>';
    echo'<div style="align:left" id=block>';                           
    

 if($lsg_type==1) 
        {
        
  $sql="SELECT * FROM bio_corporation WHERE country='".$cc."' AND state='".$state."' AND district='".$dis."'";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];
 
          if($cc==1 && $state==14)  
          {              
              if($dis==12){$distname='Thiruvananthapuram';}
              if($dis==6){$distname='Kollam';} 
              if($dis==2){$distname='Eranakulam';} 
              if($dis==13){$distname='Thrissur';} 
              if($dis==8){$distname='Kozhikode';} 
                    echo '<table  align=left style="width:100%" ><tr><td width=50%>' . _('Corporation Name') . ':</td><td></td>';
                    echo '<td><select name="lsgName" readonly id="lsgName"  style="width:190px">';
                    echo "<option value='".$dis."'>".$distname."</option>"; 
                    echo '</select></td>';    
                    echo '</tr></table>';      
          }
        
        }
        elseif($lsg_type==2) 
        {

            //echo"2222222";
        echo '<table  align=left style="width:100%" ><tr><td width=50%>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='".$cc."' AND state='".$state."' AND district='".$dis."'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName"  style="width:190px">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$lsgnam)
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
        
        elseif($lsg_type==3) 
        {
                        
            //echo"3333333"; 
         echo '<table  align=left style="width:100%" ><tr><td width=50%>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country='".$cc."' AND state='".$state."' AND district='".$dis."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:190px" >';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$lsgnam)
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
         
         $sql="SELECT * FROM bio_panchayat WHERE country='".$cc."' AND state='".$state."' AND district='".$dis."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:190px" >';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$bnam)
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
        echo"<tr id='showvillage'></tr>"; 
        echo '</tr>';                                                                                                                            
    echo '<tr><td>Phone number*</td>'; 
    echo "<td><table><td><input type=text name='code' id='code' placeholder='STD' value='".$pcode."' onkeyup='focuschange(this.value)' style='width:50px'  ></td><td><input type=text name=phone placeholder='NUMBER'  value='".$pno."' onkeyup='focusback(this.value)' id=phone style='width:95%' onchange='duplicate()'></td></table></td></tr>";
    echo '<tr><td>Mobile Number</td>';
    echo "<td><input type=text name=mobile id=mobile style=width:190px  value='".$mobno."' onchange='duplicate()'></td><td id=mob></td></tr>"; 
    echo '<tr><td>Email id</td>';
    echo "<td><input type=text name='email' id='email' style=width:190px  value='".$mailid."' onblur='validate()'  onchange='duplicate()'></td></tr>";
    //Product Sevices
   echo  "<input type='hidden' name='stop' id='stop' value='2'>";       
     echo "<input type='hidden' name='eid' id='eid' value=$rid >";      
    echo '<tr><td>Identity Type</td>';
    echo'<td><select name="identity" id="identity" style="width:190px" >';
    $sql1="SELECT * FROM bio_identity";
    $result1=DB_query($sql1,$db);
$f=0;                                     
 

     
        
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['ID_no']==      $idtype)
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
    echo '<td><input type="text" style="width:190px" name="identityno" id="identityno" value="'.$idno.'" ></td></tr>'; 
    if($rid!=null)
    {
       echo'<tr><td></td><td><input type="submit" name="edit" value="Edit" onclick="if(log_in()==1)return false;" >&nbsp&nbsp&nbsp&nbsp<input type="submit" name="clear" value="Clear" ></td></tr>';       
    }
    else{
  echo'<tr><td></td><td><input type="submit" name="submit" value="Submit" onclick="if(log_in()==1)return false;" >&nbsp&nbsp&nbsp&nbsp<input type="submit" name="clear" value="Clear" ></td></tr>';
    }
        
         
  
echo'</table>';
 echo"</fieldset>";         
echo"</form>";
echo"</div></td></tr><tr><td> ";
echo"<div >";
echo"<fieldset style='width:90%'><legend><h3>Network Customer Details</h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>"; 
echo"<table style='width:100%'>";
echo"<tr><th>Customer code</th><th>Customer Name</th><th>District</th><th>Contact Number</th><th>Edit</th></tr>";
  $sql = "SELECT bio_network_cust.id,`cust_code`,`cust_name`,bio_district.district,`phoneno`,`mobileno`
    FROM `bio_network_cust`
    INNER JOIN `bio_district` ON ( bio_district.did = bio_network_cust.`district`
    AND bio_district.stateid = bio_network_cust.`state`
    AND bio_district.cid = bio_network_cust.`nationality`)  ";
 $result=DB_query($sql,$db);  
    $k=1;  
    while($row=DB_fetch_array($result))
    {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
         $id=$row[id];  
echo"<td align=center>".$row['cust_code']."</td>
<td align=center>".$row['cust_name']."</td>   
<td align=center>".$row['district']."</td>
<td align=center>".$row['phoneno'].",".$row['mobileno']."</td>  ";
   
echo'<td align=center><a href="#" id='.$id.' onclick="edit(this.id)">Edit</a></td></tr>'; 


    }
echo '<tbody>';
echo"</tr></tbody></table>";   

 echo"</table></div>";
echo"</td></tr></table>";
?>

<script>

 function edit(str)
 {  // alert("fdgslg");
 location.href="?edit=" +str;         
 }
  function focuschange(str) 
  {              //alert(str);
  if(str!=""){
       var ls=str.length; //alert(ls);
            if(ls>=4)
                {
               // f=1;  
               document.getElementById('phone').focus();
              //return f;
              }    
           } 
  }
 function focusback(str) 
  {              //alert(str);
  if(str==""){document.getElementById('code').focus();} 
  }
function log_in()
{   //alert("hello");   

var f=0;
if(f==0){f=common_error('custcode','Enter select the Customer Code');  if(f==1){return f; } } 

if(f==0){
var ss=document.getElementById('stop').value; 
if(ss==1){
    document.getElementById("custcode").focus();f=true; }else{f=false;} 
 if(f==1){return f; }
 if(ss==3){
    document.getElementById("phone").focus();f=true; }else{f=false;} 
 if(f==1){return f; } 
  if(ss==4){
    document.getElementById("mobile").focus();f=true; }else{f=false;} 
 if(f==1){return f; }
   if(ss==5){
    document.getElementById("email").focus();f=true; }else{f=false;} 
 if(f==1){return f; }
} 
/*if(f==0){ 
var ss=document.getElementById('stop').value; 
if(ss==1){
    document.getElementById("custcode").focus();f=true; }
else if(ss==3){
    document.getElementById("phone").focus();f=true; } 
else if(f==4){return f; } 
 if(ss==1){
    document.getElementById("mobile").focus();f=true; }
else if(ss==5){
    document.getElementById("email").focus();f=true; } else{f=false;}
 if(f==1){return f; } 
}  */

if(f==0){f=common_error('custname','Please enter the Customer Name');  if(f==1){return f; } }
if(f==0)
{
    var h1=document.getElementById('Houseno').value; 
    var h2=document.getElementById('HouseName').value;
    if(h1==""&&h2=="")
    {f=common_error('HouseName','Please enter the HouseName');  if(f==1){return f; } }
}  
if(f==0)
{   
    var a1=document.getElementById('Area1').value; 
    var a2=document.getElementById('Area2').value;
    if(a1==""&&a2=="")
    {f=common_error('Area1','Please enter the Residencial Area');  if(f==1){return f; }}
    
}  
if(f==0){f=common_error('Pin','Please enter the PIN Number');  if(f==1){return f; } } 
/*if(f==0)
{
     
    var x=document.getElementById('Pin').value;
   
    if(x!=""){
       var l=x.length;
                           
    
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Enter a Vaild PIN Number"); document.getElementById('Pin').focus();
              if(x=""){f=0;}
              return f; 
           }

    }
}  */
if(f==0){f=common_error('country','Please Select a Country');  if(f==1) { return f; }}
if(document.getElementById('country').value==1)
{                     
if(f==0){f=common_error('state','Please Select a State');  if(f==1) { return f; }}
if(document.getElementById('state').value==14){
if(f==0){f=common_error('Districts','Please Select a District');  if(f==1) { return f; }}
}
}        
 if(f==0){f=common_error('lsgType','Please Select the LSG Type');  if(f==1){return f; } }    
 if(f==0){f=common_error('lsgName','Please Select the LSG Name');  if(f==1){return f; } }
//  if(f==0){f=common_error('code','Please Enter the Code');  if(f==1){return f; } }     
if(f==0)
{
    var y=document.getElementById('phone').value; 
    var x=document.getElementById('mobile').value;
    if(x=="" && y==""){ alert("Please enter atleast one contact number");f=1;} 
    if(f==1) { document.getElementById('phone').focus();return f; } }
    
if(f==0)
{
    var cod=document.getElementById('code').value;  
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

if(f==0){f=common_error('identity','Please select the Identity Type ');  if(f==1){return f; } }  
if(f==0){f=common_error('identityno','Please select the Identity NO ');  if(f==1){return f; } }
}

function validate() 
{
 
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.getElementById('email').value;
   if(reg.test(address) == false) {
     alert('Invalid Email Address');  
     document.getElementById('email').focus();  
return false;
   }
}
function showstate(str){ 
         
if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {   // alert(str);              
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {//  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;
    document.getElementById("state").focus();
    }
  }
xmlhttp.open("GET","bio_showstate.php?country=" + str,true);
xmlhttp.send();
}
function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;    

if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
           document.getElementById('Districts').focus();

    }
  }
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
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
xmlhttp.open("GET","bio_CustlsgSelection.php?taluk=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 
function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if (str3=="")
     {
     alert("Please select a district");    
     document.getElementById("Districts").focus();
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
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 
 function duplicate()
{
      //alert('fdsghfd');   
      var code=document.getElementById('code').value;  

    var phone=document.getElementById('phone').value;  
        var eid=document.getElementById('eid').value;      
     var pp=code+"-"+phone; //alert(pp);
var mail=document.getElementById('email').value;  
    var mobile=document.getElementById('mobile').value; 
    var cust_code=document.getElementById('custcode').value;   // alert(cust_code);       
    //var mobile=document.getElementById('mobile').value;   

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {   // alert(str);              
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {//  alert(str); 
    document.getElementById("dup").innerHTML=xmlhttp.responseText;
    //document.getElementById("mobile").focus();
    }
  }
xmlhttp.open("GET","bio_duplicate_mob.php?mobile=" + mobile+"&code="+cust_code+"&mail="+mail+"&phoneno="+pp+"&edit="+eid,true);
xmlhttp.send(); 
           
 
}
</script>
