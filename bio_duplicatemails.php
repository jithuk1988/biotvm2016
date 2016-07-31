<?php
    $PageSecurity = 80;
include('includes/session.inc');


if($_GET['mail'])
{
      $from=$_GET['mail'];         
      $id=$_GET['emailid'];

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
                 
                   }
    echo "</table></div>";
      echo "</fieldset>";  
  } 

}
?>
