<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  if($_GET['id']!=""){
  
     $name=$_GET['name'];     
     $phone=$_GET['pno']; 
     $email=$_GET['email']; 
//     $orderid=$_GET['order'];
   
                                                        
      if($name!=""){
        $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incidents.createdon,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.custmail
                  FROM bio_incidents,bio_incident_cust
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id";   
        $sql_insident.=" AND bio_incident_cust.custname like '%$name%'";
        if($phone!=""){
          $sql_insident.=" AND bio_incident_cust.custphone=$phone";  
        }
        if($email!=""){
          $sql_insident.=" AND bio_incident_cust.custmail='$email'";  
        }
     }elseif($phone!=""){
             $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incidents.createdon,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.custmail
                  FROM bio_incidents,bio_incident_cust
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id";   
         $sql_insident.=" AND bio_incident_cust.custphone=$phone";
         if($email!=""){
          $sql_insident.=" AND bio_incident_cust.custmail='$email'";  
        }
         }
      
      elseif($email!=""){
             $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incidents.createdon,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.custmail
                  FROM bio_incidents,bio_incident_cust
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id";   
         $sql_insident.=" AND bio_incident_cust.custmail='$email'"; 
         }
     
//      echo $sql_insident;
     $result_insident=DB_query($sql_insident,$db);
    $incident_count=DB_num_rows($result_insident);
     if($incident_count>0){
          echo"<div style='height:150px; width:100%; overflow:scroll;'>";     
     echo"<fieldset style='width:520px'><legend>Complaint Details</legend>";
     echo"<table style='width:30%'> ";
          
     echo"<th>Ticket No</th><th>Customer Name</th><th>Mobile</th><th>Email id</th><th>Created Date</th>";    
     while($row_insident=DB_fetch_array($result_insident)){
          $custid=$row_insident['cust_id'];  
          $ticketid=$row_insident['ticketno'];  
          $dat= ConvertSQLDate($row_insident['createdon']); 
            if ($k == 1) {
            echo '<tr class="EvenTableRows">';
            $k = 0;
            } else {
            echo '<tr class="OddTableRows">';
            $k++;
            } 
            
         echo"<td>".$row_insident['ticketno']."</td> 
              <td>".$row_insident['custname']."</td>
              <td>".$row_insident['custphone']."</td> 
              <td>".$row_insident['custmail']."</td> 
              <td>".ConvertSQLDate($row_insident['createdon'])."</td> 
              <td><a  style='cursor:pointer;' onclick=selectIncident('$ticketid')>" . _('Select') . "</a></td>";
     }
        
         echo"</tr>";
         echo"</table>";
         echo"</fieldset>";
         echo"</div>";
         
     }
   
                   
if($name!=""){
        $sql2="SELECT 
                   bio_leads.leadid,
                   bio_cust.custname,
                   bio_cust.custmob,
                   bio_cust.custmail,
                   bio_cust.cust_id
                  FROM bio_leads,bio_cust
                  WHERE bio_leads.cust_id=bio_cust.cust_id"; 
    
        $sql2.=" AND bio_cust.custname like '%$name%'";
        if($phone!=""){
          $sql2.=" AND bio_cust.custmob=$phone";  
        }
         if($email!=""){
          $sql_insident.=" AND bio_cust.custmail='$email'";  
        }
        
     }
     
     elseif($phone!=""){
        $sql2="SELECT 
                   bio_leads.leadid,
                   bio_cust.custname,
                   bio_cust.custmob,
                   bio_cust.custmail,
                   bio_cust.cust_id
                  FROM bio_leads,bio_cust  
                  WHERE bio_leads.cust_id=bio_cust.cust_id";
          $sql2.=" AND bio_cust.custmob=$phone";  
          
          if($email!=""){
          $sql_insident.=" AND bio_cust.custmail='$email'";  
        }
       
     }
      elseif($email!=""){
             $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.custmail
                  FROM bio_incidents
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id";   
         $sql_insident.=" AND bio_incident_cust.custmail='$email'"; 
         }
    
       
   $result2=DB_query($sql2,$db);
     $incident2_count=DB_num_rows($result2);
     if($incident2_count>0){
     echo"<div style='height:150px; width:100%; overflow:scroll;'>";     
     echo"<fieldset style='width:470px'><legend>Lead Details</legend>";
     echo"<table style='width:30%'> ";
     
      echo"<th>Lead id</th><th>Customer Name</th><th>Mobile</th><th>Email id</th>";  
         
      while($row_insident2=DB_fetch_array($result2)){
          $lead=$row_insident2['leadid'];  
         
            if ($k == 1) {
            echo '<tr class="EvenTableRows">';
            $k = 0;
            } else {
            echo '<tr class="OddTableRows">';
            $k++;
            } 
  
           echo"<td>".$row_insident2['leadid']."</td>
              <td>".$row_insident2['custname']."</td>
              <td>".$row_insident2['custmob']."</td>                                                                            
              <td>".$row_insident2['custmail']."</td>
              <td><a  style='cursor:pointer;' onclick=selectLead('$lead')>" . _('Select') . "</a></td>";
         } 
              echo"</tr>";
         echo"</table>";
         echo"</fieldset>";
         echo"</div>";
         
     
          
      }
  
       
                   
if($name!=""){
     $sql3="SELECT 
                   salesorders.orderno,
                   salesorders.debtorno,
                   debtorsmaster.debtorno,     
                   debtorsmaster.name,
                   custbranch.debtorno,
                   custbranch.phoneno,
                   custbranch.email
             FROM   debtorsmaster,salesorders,custbranch 
             WHERE  salesorders.debtorno=debtorsmaster.debtorno
             AND    debtorsmaster.debtorno=custbranch.debtorno
             AND    salesorders.branchcode=custbranch.branchcode"; 
        $sql3.=" AND debtorsmaster.name like '%$name%'";
        if($phone!=""){
          $sql3.=" AND custbranch.phoneno=$phone";  
        }
       if($email!=""){
          $sql_insident.=" AND custbranch.email='$email'";  
        } 
     }
     
     elseif($phone!=""){
          $sql3="SELECT 
                   salesorders.orderno,
                   salesorders.debtorno,
                   debtorsmaster.debtorno,     
                   debtorsmaster.name,
                   custbranch.debtorno,
                   custbranch.phoneno,
                   custbranch.email
             FROM   debtorsmaster,salesorders,custbranch 
             WHERE  salesorders.debtorno=debtorsmaster.debtorno
             AND    debtorsmaster.debtorno=custbranch.debtorno
             AND    salesorders.branchcode=custbranch.branchcode"; 
          $sql3.=" AND custbranch.phoneno=$phone";  
          if($email!=""){
          $sql_insident.=" AND custbranch.email='$email'";  
        } 
         }
          elseif($email!=""){
             $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.custmail
                  FROM bio_incidents
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id";   
         $sql_insident.=" AND bio_incident_cust.custmail='$email'"; 
         }
   
         
//   echo $sql3;   
   $result3=DB_query($sql3,$db);
     $incident3_count=DB_num_rows($result3);
     if($incident3_count>0){
     echo"<div style='height:150px; width:100%; overflow:scroll;'>";     
     echo"<fieldset style='width:470px'><legend>Order Details</legend>";
     echo"<table style='width:30%'> ";
     
      echo"<th>Order id</th><th>Customer Name</th><th>Mobile</th><th>Email id</th>";  
         
      while($row_insident3=DB_fetch_array($result3)){
         $order=$row_insident3['orderno'];  
                                                                                                        
            if ($k == 1) {
            echo '<tr class="EvenTableRows">';
            $k = 0;
            } else {
            echo '<tr class="OddTableRows">';
            $k++;
            }                                                                                                                                                                                                                
  
           echo"<td>".$row_insident3['orderno']."</td>
              <td>".$row_insident3['name']."</td>
              <td>".$row_insident3['phoneno']."</td> 
              <td>".$row_insident3['email']."</td>
              <td><a  style='cursor:pointer;' onclick=selectOrder('$order')>" . _('Select') . "</a></td>";
         }
              echo"</tr>";
         echo"</table>";
         echo"</fieldset>";
         echo"</div>";       
         
                  
          
      }
     
  
  }
                                                             
  
?>
