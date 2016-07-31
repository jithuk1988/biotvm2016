<?php
 $PageSecurity = 40;
include('includes/session.inc');

  $id=$_GET['mailid'];
  $sql1="SELECT  *  FROM   bio_email WHERE bio_email.id=".$id;
  $result1=DB_query($sql1,$db);  
 $myrow1=DB_fetch_array($result1); 
 $from=$myrow1['from_address'];
 



 $id=$_GET['mailid'];
    // $id=196;
  if (isset($_GET['mailid'])){  
  $sql="SELECT  bio_email.from_address,
                bio_email.from_name,
                bio_email.to_address,
                bio_email.maildate,
                bio_email.subject,
                bio_email.message
         FROM   bio_email WHERE bio_email.id=".$id;
 $result=DB_query($sql,$db);  
 $myrow_email=DB_fetch_array($result); 
 
     $from=$myrow_email['from_address'];
     $to=$myrow_email['to_address']; 
     $date=$myrow_email['maildate'];
     $message=$myrow_email['message'];
     $title1=$myrow_email['subject'];
     $custname2=$myrow_email['from_name'];
     $custname1=explode('<',$custname2);
     $custname=$custname1[0];
 
  echo'<div id="fullpanel">'; 
echo '<a href=bio_email.php>Goto Inbox</a>';
  echo '<fieldset style="width:950px">';  
  
    
  echo '<table style=width:500px><tr><td>';
  echo '<fieldset style="width:420px; height:800px">';  
  echo '<legend><b>Mail Details</b></legend><br />'; 
  echo'<input type="hidden" id="sub" name="sub" value="'.$title1.'">';
echo"<table width=100%>";

echo'<tr><td width=30%>Name</th><td><input type=text name=custname id=custname value="'.$custname.'" style="width:200px"></td></tr>';  
echo'<tr><td width=30%>Email</th><td><input type=text name=email id=email value="'.$from.'" style="width:200px" onchange="duplicatemail(this.value,'.$id.');"></td></tr>'; 
echo'<tr><td width=30%>Land line</th><td><input type=text name=landline id=landline style="width:200px"></td></tr>'; 
echo'<tr><td width=30%>Phone No</th><td><input type=text name=phno id=phno value="'.$phno.'" style="width:200px"></td></tr>';
echo"<tr><td width=30%>Houseno / Buildingno</td>";  
echo "<td><input type='text' name='Houseno' style='width:200px' id='Houseno' value='".$houseno."'></td>";    
echo"<tr><td width=30%>HouseName / Org Street</td>"; 
echo "<td><input type='text' name='HouseName' id='HouseName' style='width:200px' value='".$housename."' style='width:56%'></td>";
echo"<tr><td width=30%>Residential / Org Area</td>"; 
echo "<td><input type='text' name='Area1' id='Area1' value='".$area1."' style='width:200px'></td>";
echo"<tr><td width=30%>Post office</td>"; 
echo "<td><input type='text' name='Area2' id='Area2' value='".$area2."' style='width:200px'></td>";
echo"<tr><td width=30%>Pin</td>"; 
echo" <td><input type='text' name='Pin' id='Pin' value='".$pin."'  style='width:200px'></td></tr>";    
echo"<tr ><td width=17%>Country</td><td>";
echo '<select name="country" id="country" tabindex=6 onchange="showstate(this.value)" style="width:200px">';
    
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
     echo '<select name="State" id="state" style="width:200px" tabindex=7 onchange="showdistrict(this.value)">';
     
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
     echo '<select name="District" id="Districts" style="width:200px" tabindex=8 onchange="showtaluk(this.value)">';          // 
 
     $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";      $result=DB_query($sql,$db);   
 
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


  echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:200px" onchange=showblock(this.value)>';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td></tr>'; 
    
        echo '<tr><td align=left colspan=2>';
        echo'<div style="align:right" id=block>';
                    
        echo'</div>';
        echo'</td></tr>';
        
        echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
              <td><input tabindex=9 type="Text" name="lsgWard" id="lsgWard" style="width:200px" maxlength=15 value="'.$LSG_ward.'" style="width:56%"></td></tr>';      

    
    if($eid!="" && $district!="")  {  
          
      echo"<tr><td>Taluk*</td><td>";
      $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country=$nationality AND bio_taluk.state=$state AND bio_taluk.district=$district";
      $result=DB_query($sql,$db);
      echo '<select name="taluk" id="taluk" style="width:200px" tabindex=11>';
      $f=0;
      while($myrow1=DB_fetch_array($result))
      {
      if ($myrow1['id']==$_POST['taluk'])
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
       echo"</tr>";  
       
      }else{
          
        echo"<tr id='showtaluk'></tr>";  
        
      }
      
      echo '<tr><td>' . _('Village Name'). ':</td>
                  <td><input tabindex=9 type="Text" name="village" id="village" style=width:200px maxlength=15 value="'.$village.'" style="width:56%"></td></tr>';  





   
   
echo '<tr><td width=30%>Email Type</td>';    
echo '<td><select name="emailtype" id="emailtype" style="width:200px">';
$sql1="select * from bio_emailtypes";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['emailtype_id']==$priority) 
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
    echo $row1['emailtype_id'].'">'.$row1['category'];
    echo '</option>';

}    
     echo '</select></td></tr>';  
     
     echo '<tr><td width=30%>Email Source</td>';    
echo '<td><select name="emailsource" id="emailsource" style="width:200px">';
$sql1="select * from bio_leadsources where sourcetypeid=13";
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
    echo $row1['id'].'">'.$row1['sourcename'];
    echo '</option>';

}   
     echo '</select></td><td> <a style="cursor:pointer;" onclick="insdate1();">Add email source</a></td></tr>';            
   echo '<tr><td >Incident Type</td>';
echo '<td><select name="type" id="type" style="width:200px" onchange="showmain(this.value)">';
$sql1="select * from bio_incidenttype where id in (1,4)";
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
     
}    echo '</select></td><td> <a style="cursor:pointer;" onclick="insdate();">Add incident type</a></td></tr>';  

echo '<tr><td style="width:30%">Customer Type</td>';
    echo  '<td>';
    echo '<select name="enquiry" id="enquiry" style="width:200px" tabindex=1  onchange="showinstitute(this.value)">';
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
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }
     
    echo '</select>';    
    echo '</td></tr>';              
    echo '<tr id="maincat"></tr>';
/*echo '<tr><td width=30%>Email Category</td>';  
echo '<td><select name="mainmailcategory" id="mainmailcategory"  onchange="showsubmailcategory(this.value)" style="width:200px">';
$sql1="select * from bio_mainemailcategory";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['main_catid']==$priority) 
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
    echo $row1['main_catid'].'">'.$row1['main_catname'];
    echo '</option>';

}    
     echo '</select></td></tr>';    */
 echo"<tr id='showcatagry' width=30%>"; 
     
   
     echo '</tr></table>';               

 echo'<div id="emaildiv"></div><table>';
 //echo"<tr><td width=36%></td>";
echo"<input type='hidden' name='Mailid' id='mailid' value='".$id."'>";
 echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Save') . '" onclick=" if(validate()==1)return false">&nbsp;&nbsp;<input type="submit"  action="Reset" value="' . _('Clear') . '"></td></tr>'; 

echo"</table>";
echo"</fieldset>";  
echo '</td>';
 echo'<td>';echo '</td>'; 
 echo'<td>';    
 echo '<fieldset style="width:400px; height:800px">';
 echo '<legend><b>Message</b></legend><br />';
echo'<div style="height:780px;overflow:scroll">'; 
 echo"<table width=100% >"; 
 $sql_view_att="SELECT * FROM  bio_email_attachments
                        WHERE id=$id";
 $result_view_att=DB_query($sql_view_att,$db);
 $count_att=DB_num_rows($result_view_att);
 if($count_att>0){
    echo"<tr><th><b>Attachments</b></th></tr>";
     $no=1;
     while($row_view_att=DB_fetch_array($result_view_att)){
         $attachments1=explode("/",$row_view_att['attachment_path']);
         $attachments2=$attachments1[1];
         
         echo"<tr>
                     <td>$no.<a href='".$row_view_att['attachment_path']."' id='".$row_view_att['id']."'>".$attachments2."</a></td>    
                 </tr>";
         
         $no++;   
         
         
     }
 }
 //echo'<tr><td></td> </tr>';      echo'<tr><td></td> </tr>';         
                     if($from=='noreply@server.mailjol.net')
                     {
                          echo'<tr><td align=top>'.$message.'</td></tr>'; 

                     }
                     else
                     {
 echo'<tr><td valign="top">From:'.$from.'<br />To:'.$to.'<br />Date:'.$date.'<br />Subject:'.$title1.'<br />'.nl2br($message).'</td></tr>'; 
                     }
 
 echo"</table></div>";
 echo"</fieldset>";               
 echo'</td>';
    
echo'</tr></table></fieldset></div>';  
  } 
  
     
echo"<div id=duplicatemails>";
       if (isset($from)){  
        // echo    $from;  
        
//$sql2="SELECT   bio_email.id,
//                bio_email.from_address,
//                bio_email.from_name,
//                bio_email.to_address,
//                bio_email.date,
//                bio_email.subject,
//                bio_email.mainmailcategory,
//                bio_email.submailcategory,
//                bio_submailcategory.emailcategory,
//                bio_submailcategory.main_catid,
//                bio_submailcategory.sub_catid
//         FROM   bio_email,bio_mainemailcategory,bio_submailcategory 
//         WHERE  bio_email.status=1
//         AND    bio_email.mainmailcategory=bio_submailcategory.main_catid 
//         AND    bio_email.submailcategory=bio_submailcategory.sub_catid
//         AND    bio_email.from_address='".$from."'";

  $sql2="SELECT   bio_incident_cust.cust_id,
                  bio_incident_cust.custmail,
                  bio_incident_cust.custname,
                  bio_incidents.createdon,
                  bio_submailcategory.emailcategory
         FROM     bio_incident_cust,bio_incidents,bio_submailcategory
         WHERE    bio_incident_cust.cust_id=bio_incidents.cust_id 
         AND      bio_incidents.mainmailcategory=bio_submailcategory.main_catid
         AND      bio_incidents.submailcategory =bio_submailcategory.sub_catid
         AND      bio_incident_cust.custmail='$from'";  
         
  $result2=DB_query($sql2,$db);        
  $incident_count=DB_num_rows($result2);
     if($incident_count>0){
   
  echo "<fieldset style='width:95%;'>";     
  echo "<legend><h3>Duplicate Mails</h3></legend>";     

  echo "<div style='height:auto'>"; 
  echo "<table style='width:100%;' id='mail'>";     
  echo "<thead>
         <tr BGCOLOR =#800000>
         <th>" . _('Sl no') . "</th>   
         <th>" . _('Mailid') . "</th>  
         <th>" . _('From') . "</th>      
         <th>" . _('Email Category') . "</th>    
         <th>" . _('Date') . "</th>
              
         </tr></thead>"; 
        
    
    $no=1;      
  while($row=DB_fetch_array($result2))    {
     $cust_id=$row['cust_id']; 
     $from_mail=$row['custmail']; 
     $from_name=$row['custname'];
     
                echo"<tr style='background:#D0D0D0'>
                     <td>$no</td>  <td><b>".$from_mail."</b></td>
                     <td><b>".$from_name."</b></td>
                     <td><b>".$row['emailcategory']."</b></td> 
                     <td><b>".$row['createdon']."</b></td>
                     <td><a  style='cursor:pointer;' onclick=selectDetails('$cust_id','$id')>" . _('Select') . "</a></td>
                     </tr>";    
                                              
                 $no++;       
      
// $from_mail=$row['from_address']; 
// $title1=$row['subject'];                       
//  $id=$row['id'] ; 
//    $main_id= $row['main_catid'];   //echo'<br />';
//    $sub_id=$row['sub_catid'] ;      
//            echo"<tr style='background:#D0D0D0'>
//                     <td>$no</td>  <td><b>".$from_mail."</b></td>
//                     <td><b>".$row['from_name']."</b></td>
//                     <td><b>".$row['emailtype']."</b></td> 
//                     <td><b>".$title1."</b></td> 
//                     <td><b>".$row['date']."</b></td>
//                      <td><a  style='cursor:pointer;' onclick=selectDetails('$id','$main_id','$sub_id')>" . _('Select') . "</a></td>
//                      <td><a href='#' id='".$row['id']."' onclick='showMail(this.id)'>View</a></td>     
//                     </tr>";    
//                                              
//                 $no++;   

    
  }
    echo "</table></div>";
      echo "</fieldset>";  
  }                   
}

echo"</div>";
     
               

?>
