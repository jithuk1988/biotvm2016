<?php
    $PageSecurity = 80;
  include('includes/session.inc');

  if($_GET['alertmessage']!=""){
          
     $name=$_GET['name'];     
     $phone=$_GET['pno']; 
     $email=$_GET['email']; 
     
     
     
     if($phone!="" || $email!="")
     {
         
    
$sql_alertmessage="SELECT COUNT(*)
                   FROM   bio_incidents,bio_incident_cust
                   WHERE  bio_incident_cust.cust_id=bio_incidents.cust_id";
  
        if($name!=""){           
        $sql_alertmessage.=" AND bio_incident_cust.custname like '%$name%'";
        }
        if($phone!=""){
        $sql_alertmessage.=" AND bio_incident_cust.custphone=$phone";  
        }
        if($email!=""){
        $sql_alertmessage.=" AND bio_incident_cust.custmail='$email'";  
        } 
        
        $result_alertmessage=DB_query($sql_alertmessage,$db);
        $myrow_alertmessage=DB_fetch_array($result_alertmessage);
        
        echo $myrow_alertmessage[0];
     }
  
  }
?>
