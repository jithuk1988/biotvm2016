

<?php 
$_SESSION["close_compl"]="";
$PageSecurity=80;
$pagetype=1;
include('includes/session.inc');
$title = _('Cmplient Register'); 
include('includes/header.inc');
//include('includes/sidemenu2.php');

//cust_typ,warr_type,chargable,


if(isset($_POST['submit2']))
{
    $collectedBy=$_SESSION['UserID']; 
 $createdate=date("Y-m-d"); 
    $duedate='0000-00-00';
  if(isset($_POST['orderno']))
  {      
    
  
 $sql1 = "INSERT INTO bio_incidents(orderno,
                                           type,
                                         
                                           enqtypeid,
                                           title,
                                           description,
                                           priority,
                                           expected_duedate,
                                           
                                           
                                           createdon,
                                           createdby,
                                           status,
                                           remarks,
                                           mainmailcategory,
                                           submailcategory,debtorno
                                      )
                               VALUES ('". $_POST['orderno'] . "',
                                       '". $_POST['type'] . "',
                                     
                                       '". $_POST['enquiry'] . "',
                                       '". $_POST['complaint'] . "', 
                                       '". $_POST['description'] . "',
                                       '". $_POST['priority'] . "',
                                       '". $duedate . "',
                                      
                                        
                                      
                                       '". $createdate . "',
                                       '". $collectedBy . "',
                                       1,
                                       '". $_POST['remarks'] . "',5,1,'". $_POST['debtorno4'] . "')";
        $result1 = DB_query($sql1,$db); 
        $ticket=DB_Last_Insert_ID($Conn,'bio_incidents','ticketno'); 
        $msg1= 'Incident has been created succesfully. Your Ticket no is <b>'.$ticket.'</b>';      
        prnMsg($msg1,'success'); 
  $sqlup="   UPDATE `bio_installation_status` SET `plant_status`=2 WHERE `orderno` like '" .$_POST['orderno']. " ' ";
      $result1 = DB_query($sqlup,$db);    

  }
  else{
   
     $duedate='0000-00-00';
        
 $sql1 = "INSERT INTO bio_incidents(cust_id,
                                           type,
                                         
                                           enqtypeid,
                                           title,
                                           description,
                                           priority,
                                           expected_duedate,
                                          
                                           
                                           createdon,
                                           createdby,
                                           status,
                                           remarks,
                                           mainmailcategory,
                                           submailcategory
                                      )
                               VALUES ('".$_POST['cust_id']  . "',
                                       '". $_POST['type'] . "',
                                     
                                       '". $_POST['enquiry'] . "',
                                       '". $_POST['complaint'] . "', 
                                       '". $_POST['description'] . "',
                                       '". $_POST['priority'] . "',
                                       '". $duedate . "',
                                      
                                        
                                      
                                     '". $createdate . "',
                                       '". $collectedBy . "',
                                       1,
                                       '". $_POST['remarks'] . "',5,1)";
        $result1 = DB_query($sql1,$db); 
        $ticket=DB_Last_Insert_ID($Conn,'bio_incidents','ticketno'); 
        $msg1= 'Incident has been created succesfully. Your Ticket no is <b>'.$ticket.'</b>';      
        prnMsg($msg1,'success'); 


  }
  
  
}



if(isset($_POST['update']))
{$createdate=date("d/m/Y"); 
     $ticketno=$_POST['ticketno'];
     $Compremark=$_POST['Compremark'];
     $oldremark=$_POST['oldremark'];
     $priority=$_POST['up_priority'];
     $descriptio="(update on ".$createdate.")  ".$Compremark." \n \n  ".$oldremark." ";
   $sql=" UPDATE `bio_incidents` SET `description`='".$descriptio."',`priority`='".$priority."' WHERE `ticketno`=$ticketno ";
   $result = DB_query($sql,$db); 
    $ticket=DB_Last_Insert_ID($Conn,'bio_incidents','ticketno'); 
        $msg1= 'Incident has been Update succesfully. Your Ticket no is <b>'.$ticketno.'</b>';      
        prnMsg($msg1,'success'); 
   
   
}
if(isset($_POST['reopen']))
{
    $createdate=date("d/m/Y"); 
     $ticketno=$_POST['ticketno'];
     $Compremark=$_POST['Compremark'];
     $oldremark=$_POST['oldremark'];
     $priority=$_POST['up_priority'];
     $descriptio=" (Reopn on ".$createdate.")  ".$Compremark." \n \n ".$oldremark." ";
echo $sql=" UPDATE `bio_incidents` SET `description`='".$descriptio."',`priority`='".$priority."',status='5' WHERE `ticketno`=$ticketno ";
   $result = DB_query($sql,$db); 
      $ticket=DB_Last_Insert_ID($Conn,'bio_incidents','ticketno'); 
        $msg1= 'Incident has been Reopen succesfully. Your Ticket no is <b>'.$ticketno.'</b>';      
        prnMsg($msg1,'success'); 
}

if (isset($_POST['submit']))
{ 
    $collectedBy=$_SESSION['UserID']; 
       $createdate=date("Y-m-d"); 
       $landno=$_POST['landno_no'];

        if($_POST['landno_std']!="" && $_POST['landno_no']!='')
        {
     $landno=$_POST['landno_std']."-".$landno;
        
      }
    
 $sql = "INSERT INTO bio_incident_cust(custname,
                                              custphone,
                                              landline,
                                              custmail,
                                              houseno,
                                              housename,
                                              area1,
                                              area2,
                                              pin,
                                              nationality,
                                              state,
                                              district,
                                              debtorno,
                                              taluk,
                                              LSG_type, 
                                              LSG_name,
                                              block_name, 
                                              LSG_ward, 
                                              village)
                                  VALUES ('".$_POST['custnam'] . "',
                                          '".$_POST['phno'] . "',
                                          '".$landno."', 
                                         '".$_POST['email'] . "',
                                         '".$_POST['Houseno'] . "',
                                         '".$_POST['HouseName'] . "',
                                         '".$_POST['Area1'] . "',
                                         '".$_POST['Area2'] . "',
                                         '".$_POST['Pin'] . "',
                                         '".$_POST['country'] . "',
                                         '".$_POST['State'] . "',
                                         '".$_POST['Districts'] . "',         
                                         '".$_POST['debtorno4'] . "',
                                         '".$_POST['taluk']."',
                                         '".$_POST['lsgType']."',
                                         '".$_POST['lsgName']."', 
                                         '".$_POST['gramaPanchayath']."',
                                         '".$_POST['lsgWard']."',
                                         '".$_POST['village']."')";                                        
        $result = DB_query($sql,$db);
      $cust_id=DB_Last_Insert_ID($Conn,'bio_incident_cust','cust_id');
     $duedate='0000-00-00';
        
        

  
 $sql1 = "INSERT INTO bio_incidents(cust_id,
                                           type,
                                         
                                           enqtypeid,
                                           title,
                                           description,
                                           priority,
                                           expected_duedate,
                                          
                                           
                                           createdon,
                                           createdby,
                                           status,
                                           remarks,
                                           mainmailcategory,
                                           submailcategory,debtorno
                                      )
                               VALUES ('". $cust_id . "',
                                       '". $_POST['type'] . "',
                                     
                                       '". $_POST['enquiry'] . "',
                                       '". $_POST['complaint'] . "', 
                                       '". $_POST['description'] . "',
                                       '". $_POST['priority'] . "',
                                       '". $duedate . "',
                                    
                                        
                                      
                                       '". $createdate . "',
                                       '". $collectedBy . "',
                                       1,
                                       '". $_POST['remarks'] . "',5,1,0)";
        $result1 = DB_query($sql1,$db); 
        $ticket=DB_Last_Insert_ID($Conn,'bio_incidents','ticketno'); 
        $msg1= 'Incident has been created succesfully. Your Ticket no is <b>'.$ticket.'</b>';      
        prnMsg($msg1,'success'); 




}






 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Complaint Register </font></center>';
    

    

 echo'<table  width="95%"  border="0"><tr><td>';// Master table Start border="2"
echo "<form  name='form1' method='post' action='" . $_SERVER['PHP_SELF'] . "'>"; 
  //////////--------Start simple Search--------------\\\\\\\\\\\\\\\   
 echo'
 <table class=selection >
<tr>
<td><b>Customer ID</td>
<td><b>Phone No</td>
<td><b>Tickt No</td>
<td><b>EBC No</td>
</tr>
<tr>
<td><input type="text" name="custid" id="custid" onChange="searchCustomer(this.value)"></td>
<td><input type="text" name="phone" id="phone" onChange="searchphone(this.value)" ></td>
<td><input type="text" name="ticket" id="ticket" onChange="searchticket(this.value)" ></td>
<td><input type="text" name="" id=""></td>
</tr>
<tr ><td><a id="viewads"  name="viewads" >Advance Search</a><br></td></tr>';//onblur="adsearchCustomer()"
 echo"<div  style='margin:auto;'>";
echo'<tr  ><td colspan="4" ><div id="adsearch"><table ><tr ><td ><tr >

<td><b>Client name<input type="text" name="custname" id="custname" onChange="adsearchCustomer()"></td>';









echo"<td>Country<select name='country_s' id='country_s' onchange='showstate_s(this.value)' style='width:100px'>";
$sql="SELECT * FROM bio_country ORDER BY cid";
$result=DB_query($sql,$db);
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
  echo '</select></td>';
  echo '<td id="showstate_s">State<select name="State_s" id="state_s" style="width:100px">';
  $sql="SELECT * FROM bio_state ORDER BY stateid";
  $result=DB_query($sql,$db);
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
 echo '<td id="showdistrict_s">District<select name="Districts_s" id="Districts_s" style="width:100px"  >'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['District'])
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
    
   
      /*echo '<td id=showtaluk>Taluk<select name="Taluk" id="taluk" style="width:100px" tabindex=11 onchange="showVillage(this.value)">';
      $sql_taluk="SELECT * FROM bio_taluk ORDER BY bio_taluk.taluk ASC";
      $result_taluk=DB_query($sql_taluk,$db);
      $f=0;
      while($myrow7=DB_fetch_array($result_taluk))
      {
      if ($myrow7['id']==$_POST['taluk'])
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
      echo $myrow7['id'] . '">'.$myrow7['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td>';     
echo"<td id=showvillage>Village<select name='Village' id='village' style='width:100px'>";      
   $sql_taluk="SELECT * FROM bio_village ORDER BY bio_village.village ASC";
      $result_taluk=DB_query($sql_taluk,$db);
      $f=0;
      while($myrow7=DB_fetch_array($result_taluk))
      {
      if ($myrow7['id']==$_POST['village'])
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
      echo $myrow7['id'] . '">'.$myrow7['village'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td>';*/
      echo '<td>LSG Type<select name="lsgType_s" id="lsgType_s" style="width:100px">';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td>'; 
  
        echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block_s>';             
        echo'</div>';
        echo'</td>';
      
        echo'<td><div id=showpanchayath_s></div></td>';














echo '</tr>


<tr><td colspan="4" ><center><input type="button" name="Search" id="Search" value="Search"  onclick="adsearchCustomer()"></center></td>
</tr></td></tr></table></div></td></tr>


</table>';





//<input type="submit" style="height: 0px; width: 0px; border: none; padding: 0px;" name="custidee" value="' ._('Select') . '">   onChange="ReloadForm(form1.custidee) "

//onChange="searchCustomer()
 echo "<div id='incidentdiv' style=' width:50% ;'> </div>";    


//////////////--------body table start-----------
echo '<div id="doc"></div>'; 

echo "<div id='body'>"; //</div>
echo'<table width=98% border=2 bordercolor="green">';
  echo'   <tr>
        <th><font color=blue size=3><b>' . _('Customer  Details') . '</b></font></th>
          
            <th><font color=blue size=3><b>' . _('Complaint on') . '</b></font></th>
        </tr>';
echo'<tr><td  valign=top>';//border="1" 
//////////////--------left(details) table start-----------
echo "<div id='detailsdivajx'></div>";  
echo "<div id='detailsdivhome'>";
//echo'New Details';




//echo '<fieldset style="width:380px; height:auto">';  
//echo '<legend><b>Customer Details</b></legend>';
echo'<table width="80%"  >';

echo"<tr><td width=18%>Customer Name</td>";
echo"<td><input type='text' name='custnam' id='custnam' tabindex=1 value='".$custname."' style='width:220px'  ></td></tr>";//onChange='adsearchCustomer()'

echo"<tr><td width=17%>Email id</td>";
echo"<td><input type='email' name='email' id='email' value='".$mailid."' tabindex=2 style='width:220px' ></td></tr>";//onBlur='adsearchCustomer()'
 if(isset($_GET['debtorno']))
 {
echo"<tr><td width=17%>Mobile No</td>";
echo"<td><input type='text' name='phno' id='phno' required style='width:220px' tabindex=3 value='".$phno."' ></td></tr>";//onBlur='adsearchCustomer()'
 }
 else
 {
echo"<tr><td width=17%>Mobile No</td>";
echo"<td><input type='text' name='phno' id='phno' style='width:220px' tabindex=3 value='".$phno."' ></td></tr>";//onBlur='adsearchCustomer()'
 }
echo"<tr><td width=17%>Phone No</td>";
echo"<td><input type='text' name='landno_std' id='landno_std' style='width:60px' tabindex=3 value='".$landno_std."''>&nbsp;&nbsp;<input type='text' name='landno_no' id='landno_no' style='width:145px' tabindex=3 value='".$landno_no."''></td></tr>";


if($eid!="")  {   
    $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
    
echo"<tr><td width=17%>Country</td><td>";
echo '<select name="country" id="country" onchange="showstate(this.value)" tabindex=4 style="width:222px">';
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {  
        if ($myrow1['cid']==$nationality)  
        {         //echo $myrow1['cid'];     
            echo '<option selected value="';
        } else {
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
  


$sql="SELECT * FROM bio_state ORDER BY stateid";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showstate' width=17%><td>State</td><td>";
 echo '<select name="State" id="state" style="width:222px" tabindex=5 onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==$state AND $myrow1['cid']==$nationality)
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
 
echo"<tr id='showdistrict' width=17%><td>District</td><td>";
   $sql="SELECT * FROM bio_district WHERE cid='$nationality'  AND stateid='$state' ORDER BY did";
   $result=DB_query($sql,$db);
    
 echo '<select name="Districts" id="Districts" style="width:222px" tabindex=6 onchange="showtaluk(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ( $myrow1['did']==$district )
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
}else{
//---------country--------------//    
    
    echo"<tr ><td width=17%>Country</td><td>";
    echo '<select name="country" id="country" tabindex=4 onchange="showstate(this.value)" style="width:222px">';
    
    $sql="SELECT * FROM bio_country ORDER BY cid";     $result=DB_query($sql,$db);
    
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {  
        if ($myrow1['cid']==1)  
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
  
//--------------state-----------------//


     echo"<tr id='showstate' width=17%>";
     echo"<td>State</td><td>";
     echo '<select name="State" id="state" style="width:222px" tabindex=5 onchange="showdistrict(this.value)">';
     
     $sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";        $result=DB_query($sql,$db);   
 
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
   echo '</select></td>'; 
   echo'</tr>';

//-------------District---------------//  

 
     echo"<tr id='showdistrict' width=17%>";
     echo"<td>District</td><td>";
     
     echo '<select name="Districts" id="Districts" style="width:222px" tabindex=6 onchange="showtaluk(this.value)">';          
     
     $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";      $result=DB_query($sql,$db);   
 
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
   echo '</select></td>';
   echo'</tr>'; 
   
   
 
}
if(!isset($_GET['edit'])){      
    
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" tabindex=7 style="width:222px" onchange=showblock(this.value)>';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td></tr>'; 
    echo '<tr><td align=left colspan=2>';
echo'<div style="align:right" id=block>';
echo'</div>';
echo'</td></tr>';
echo"<tr id='showgramapanchayath'></tr>";
    
    
}



//echo '<tr><td>' . _('Village Name'). ':</td>
//          <td><input tabindex=9 type="Text" name="village" id="village" style=width:190px maxlength=15 value="'.$village.'"></td></tr>';  
   
echo"<tr><td ></td>";


  // echo"<div id='LSG_div'>";
      
  // echo "</div>";
                  echo "</br>";
echo"<div id='addressdiv'>";

//echo"</div>" ;    



echo"<tr><td width=33%>Houseno / Buildingno</td>";  
echo "<td><input type='text' name='Houseno' id='Houseno' tabindex=14 style='width:220px' value='".$houseno."'></td>";    
echo"<tr><td>HouseName / Org Street</td>"; 
echo "<td><input type='text' name='HouseName' id='HouseName' tabindex=15 style='width:220px' value='".$housename."' ></td>";
echo"<tr><td>Residential / Org Area</td>"; 
echo "<td><input type='text' name='Area1' id='Area1' value='".$area1."' tabindex=16 style='width:220px'></td>";
echo"<tr><td>Post office</td>"; 
echo "<td><input type='text' name='Area2' id='Area2' value='".$area2."' tabindex=17  style='width:220px'></td>";
echo"<tr><td>Pin</td>"; 
echo" <td><input type='text' name='Pin' id='Pin' value='".$pin."' tabindex=18 style='width:220px'></td></tr>";

echo'</table>';




//echo'</fieldset>';









echo'</div>';//end details
echo'</td><td valign=top>';//body table 2 cell
//////////////--------Right(Complaint ) table start-----------

//echo '<fieldset style="width:380px; height:auto">';  
//echo '<legend><b>Complaint Details</b></legend>';
echo'<div id="new_comp">';
echo'<table width="80%" ><tr><td>';

//Echo'Complaint Enter area';

echo '<tr><td width=33%>Complaint Type</td>';
echo '<td><select name="type" id="type" style="width:222px" tabindex=20>';
$sql1="select * from bio_incidenttype";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$type) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['type'];
    echo '</option>';

}  

echo '<tr><td style="width:33%">Customer Type</td>';
    echo  '<td>';
    echo '<select name="enquiry" id="enquiry" style="width:222px"   onchange="showinstitute(this.value)">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$cust_type) 
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
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }
     
    echo '</select>';    
    echo '</td></tr>';
    
    echo"<tr><td width=33%>Complaint On</td>";
echo"<td><select name='complaint' id='complaint'  style='width:219px' >";

$sql55="SELECT * FROM bio_complainttypes order by complaint asc"; 
    $result15=DB_query($sql55,$db);
    $f=0;
    while($myrow15=DB_fetch_array($result15))
    { 
    if ($myrow15['id']==$complaint) 
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
    echo $myrow15['id'] . '">'.$myrow15['complaint'];
    echo '</option>';
    }


echo "</select></td></tr>"; 

echo '<tr><td width=33%>Complaint Description</td><td><textarea name="description"   id="description" rows="2" cols="31">'.$description.'</textarea> </td></tr>';
echo '<tr><td width=33%>Remarks</td><td><textarea name="remarks" id="remarks"  rows="2" cols="31">'.$remarks.'</textarea> </td></tr>';

echo '<tr><td width=33%>Priority</td>'; 
echo '<td><select name="priority" id="priority" tabindex=25  style="width:222px" onchange="addPriority(this.value)">';
 

$sql1="select * from bio_priority";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$priority) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['priority'];
    echo '</option>';
}

   
echo '</select></td></tr>';

echo'<tr><td colspan="2" id="addcom"><center><input type="submit" name="reload" value="Reload"><input type="submit" name="submit" id="submit" value="Add Complaint" onclick=" if(validate()==1)return false"><input type=reset value="Clear"></center></td></tr>';

//echo'<iframe src="' . $rootpath . '/bio_purchase_select.php" width="100%" height="100%"></iframe> ';
//////////////--------Right(Complaint ) table End-----------

//echo '</fieldset>';
echo'</td></tr></table>';
echo'</div>';//end of div id body
//////////////--------body table End-----------
echo'</td></tr></table>'; 
//////////--------End simple Search--------------\\\\\\\\\\\\\\\ 
/*echo'<table><tr>

<td><input type=submit name="history" id="history" value="Full history"></td>

</tr></table>';*///<td><input type=submit name="" id="" value="Pending"></td><td><input type=submit name="" id="" value="Edit"></td>
echo'</div>';

 
echo'</td></tr></table>';// Master table end






?>

 <script type="">
//$('#new_comp').hide();
$('#adsearch').hide();



 $('#viewads').click(function() {  
/*$('#views2').hide(); 
$('#views3').hide(); 
$('#views4').hide(); */
  $('#adsearch').slideToggle('slow',function(){});

});


function searchCustomer()
{
   // $('#body').show();
   $('#detailsdivhome').hide();
 var custid= document.getElementById("custid").value;//alert(custid);
//var str1= document.getElementById("SubCategoryID").value;
//var str2= document.getElementById("CategoryID").value;
   
if (custid=="")
  {
       
  document.getElementById("custid").innerHTML="";
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
  {// alert("gdhdd");
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      // 
    document.getElementById("detailsdivajx").innerHTML=xmlhttp.responseText;
// $('#dinhide').show(); 3
var varname = '<?php echo $_SESSION["close_compl"]; ?>';

changebutten(varname);

    }
  } 
 // alert("hvhjjvhjhvj");
xmlhttp.open("GET","bio_complaint_register_ajx.php?custid="+custid,true);
xmlhttp.send(); 
    
}
function searchphone()
{
    $('#detailsdivhome').hide();
 var custid= document.getElementById("phone").value;//alert(custid);
   
if (custid=="")
  {
       
  document.getElementById("phone").innerHTML="";
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
  {// alert("gdhdd");
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      // 
    document.getElementById("detailsdivajx").innerHTML=xmlhttp.responseText;
// $('#dinhide').show(); 
   var varname = '<?php echo $_SESSION["close_compl"]; ?>';

changebutten(varname);
    }
  } 
 // alert("hvhjjvhjhvj");
xmlhttp.open("GET","bio_complaint_register_ajx.php?phone="+custid,true);
xmlhttp.send(); 




}
function changebutten(str)
{
    
  // alert(str);
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {// alert("gdhdd");
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      // 
    document.getElementById("addcom").innerHTML=xmlhttp.responseText;
// $('#dinhide').show(); 
    }
  } 
 // alert("hvhjjvhjhvj");
xmlhttp.open("GET","bio_complaint_register_ajx3.php?close_com="+str,true);
xmlhttp.send();  
    
}

function searchticket(str11)
{
    $('#detailsdivhome').hide();
 
 //var ticket= document.getElementById("ticket").value;//alert(custid);

var ticket=str11;
//alert(str11);
if (ticket=="")
  {
       
  document.getElementById("ticket").innerHTML="";
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
  {// 
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      // 
// var responseTextVar = xmlhttp.responseText;
   
    document.getElementById("detailsdivajx").innerHTML=xmlhttp.responseText;
    var varname = '<?php echo $_SESSION["close_compl"]; ?>';

changebutten(varname);
   // alert(responseTextVar );
// $('#dinhide').show(); 
      }  
     
    
  } 
 // alert("hvhjjvhjhvj");
xmlhttp.open("GET","bio_complaint_register_ajx.php?ticket="+ticket,true);
xmlhttp.send(); 
}


function adsearchCustomer(){
   // $('#incidentgrid').hide();    
 
    var str=document.getElementById("custname").value;
    var str1=document.getElementById("country_s").value;
    var str2=document.getElementById("state_s").value;
    var str3=document.getElementById("Districts_s").value;
    var str4=document.getElementById("lsgType_s").value;
    //var str=document.getElementById("custname").value;
    // var str5=document.getElementById("lsgName").value;
// var str6=document.getElementById("gramaPanchayath").value;
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
             
            document.getElementById("incidentdiv").innerHTML=xmlhttp.responseText;
        }
    } 
    
   // alert(str3);

    xmlhttp.open("GET","bio_complaint_register_ajx2.php?name="+str+"&country=" + str1+"&state=" + str2+"&Districts=" + str3+"&lsgType=" + str4,true);
    xmlhttp.send();   
    
/*    
    xmlhttp.open("GET","bio_complaint_register_ajx2.php?name="+str+"&country=" + str1+"&state=" + str2+"&Districts=" + str3+"&lsgType=" + str4+"&lsgName=" + str5+"&gramaPanchayath=" + str6,true);
    */
    
/*    
 if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp1.onreadystatechange=function()
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    var message=document.getElementById("alert").value=xmlhttp1.responseText;       // alert(message);
    if(message>0)
    {   
        alert("Complaint already registered");
    }
    }
  }
//  alert(str);
xmlhttp1.open("GET","bio_selectIncidents_alert.php?alertmessage="+id1+id);
xmlhttp1.send();
     */
}

function showinstitute(str)
  {
      
   if (str=="")
  {
  document.getElementById("complaint").innerHTML="";
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
    document.getElementById("complaint").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_complt.php?enq=" + str,true);
xmlhttp.send();      
      
      
  }
  function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
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

    }
  }
xmlhttp.open("GET","bio_showstate_int.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
function showblock(str){   
      document.getElementById("lsgType").disabled=false;    
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
if (str3=="")
  {
      alert("Select for  district ");
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
xmlhttp.open("GET","bio_CustlsgSelection_int.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 
function newvalidate()
{
      var f=0; 
if(f==0){f=common_error('type','Please select the Incident Type');  if(f==1){return f; }  }
if(f==0){f=common_error('complaint','Please select a Compalint');  if(f==1){return f; }  } 
if(f==0){f=common_error('description','Please enter the Incident Description');  if(f==1){return f; }  } 
if(f==0){f=common_error('complaint_item','Please select the Item');  if(f==1){return f; }  }    
if(f==0){f=common_error('priority','Please select the Priority');  if(f==1){return f; }  }
    
}
function validate_reopen()
{
  var f=0;  
  if(f==0){f=common_error('Compremark','Please enter Complaint Remarks');  if(f==1){return f; }  } 
  if(f==0){f=common_error('up_priority','Please select Complaint priority');  if(f==1){return f; }  } 
}
function validate_update()
{
  var f=0;  
  if(f==0){f=common_error('Compremark','Please enter Complaint Remarks');  if(f==1){return f; }  } 
  if(f==0){f=common_error('up_priority','Please select Complaint priority');  if(f==1){return f; }  } 
}

 
function validate()
{     

var f=0;          
/*var bio= document.sub.cust_typ.value;
     var war = document.sub.warr_type.value;
       var cha = document.sub.chargable.value;   
  //if(document.sub.cust_typ[0].checked =="" && document.sub.cust_typ[1].checked =="" ){common_error('bio','Please enter the Biotech Type'); }  
  if(war==null){alert ('select Warranty type'); if(war==null){f=1; return f}}  
  if(cha==null){alert ('select chargable type'); }  */ 
if(f==0){f=common_error('custnam','Please enter your Name');  if(f==1){return f; }  }
if(f==0)
{
    var y=document.getElementById('phno').value; 
    var x=document.getElementById('email').value;    
    var z=document.getElementById('landno_no').value;  
    
    var lsgtype=document.getElementById('lsgType').value;  

    if(x=="" && y=="" && z=="" ){ alert("Please enter atleast one contact number");f=1;} 
    if(f==1) { document.getElementById('phno').focus();return f; } }

if(f==0){f=common_error('Districts','Please select the District');  if(f==1){return f; }  } 
if(f==0){f=common_error('lsgType','Please select a LSG Type');  if(f==1){return f; }  }  
if(f==0){f=common_error('lsgName','Please select a LSG Name');  if(f==1){return f; }  }  
if(lsgtype==3){
 if(f==0){f=common_error('gramaPanchayath','Please select a Panchayath');  if(f==1){return f; }  }    
}


//if(f==0){f=common_error('taluk','Please select a taluk');  if(f==1){return f; }  } 
//if(f==0){f=common_error('village','Please select a Village');  if(f==1){return f; }  }  

   

if(f==0){f=common_error('type','Please select the Incident Type');  if(f==1){return f; }  }

if(f==0){f=common_error('complaint','Please select a Compalint');  if(f==1){return f; }  } 
//if(f==0){f=common_error('title','Please enter the Incident Titile');  if(f==1){return f; }  }  
if(f==0){f=common_error('description','Please enter the Incident Description');  if(f==1){return f; }  } 
  
/*if(f==0){f=common_error('cust_typ','Please select the biotype');  if(f==1){return f; }  }   
if(f==0){f=common_error('warr_type','Please select the warranty');  if(f==1){return f; }  }     */
if(f==0){f=common_error('complaint_item','Please select the Item');  if(f==1){return f; }  }    

//if(f==0){f=common_error('status','Please select the Status');  if(f==1){return f; }  }  
 if(f==0){f=common_error('priority','Please select the Priority');  if(f==1){return f; }  }      
}  

  function selectOrder(debtor)
  {
//alert("xcbxcvb");
   // 
    $('#detailsdivhome').hide();
 
 //var ticket= document.getElementById("ticket").value;//alert(custid);

var debtorno=debtor;
//alert(str11);
if (debtorno=="")
  {
       
  document.getElementById("ticket").innerHTML="";
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
  {// 
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      // 
// var responseTextVar = xmlhttp.responseText;
   
    document.getElementById("detailsdivajx").innerHTML=xmlhttp.responseText;
    var varname = '<?php echo $_SESSION["close_compl"]; ?>';

changebutten(varname);
   // alert(responseTextVar );
// $('#dinhide').show(); 
      }  
     
    
  } 
 // alert("hvhjjvhjhvj");
xmlhttp.open("GET","bio_complaint_register_ajx.php?custid="+debtorno,true);
xmlhttp.send(); 

 /*location.href="?selectOrder=" +order; 
  $('#new_comp').show(); */
   }  
   
   
   function docview(order)
  {
   var ord=order;
    if (ord=="")
  {
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
   document.getElementById("doc").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","bio_com_reg_docviewajax.php?order=" +ord ,true);
xmlhttp.send();                                                         
  }
  
  function Customer_Maintenance(dern)
{
   var derno=dern; 
   // window.open("call_log.php");
  controlWindow=window.open("Customers.php?DebtorNo="+derno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
  function history(dern)
{
   var derno=dern; 
   // window.open("call_log.php");
  controlWindow=window.open("bio_custdetails.php?debtorno="+derno,"Client History", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=600,height=700");    
}
</script>


