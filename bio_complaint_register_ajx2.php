<?php
  $PageSecurity = 80;
  include('includes/session.inc');

      
      echo"<table><tr><td>";
  $debtorno=$_GET['debtorno']; 
     $name=$_GET['name'];     
     $phone=$_GET['pno']; 
     $email=$_GET['email']; 
     $ticketno=$_GET['tno'];
     $country=$_GET['country'];
     $state=$_GET['state'];
     $Districts=$_GET['Districts'];
     $lsgType=$_GET['lsgType'];
   $lsgName=$_GET['lsgName'];
   $gramaPanchayath=$_GET['gramaPanchayath'];

  
   if($_GET['Districts']!="")
   {
        $sql3="SELECT `debtorno` ,
         `brname` , `braddress1` ,
          concat (`phoneno` ,'/', `faxno`) as phone
          
          FROM `custbranch`
          WHERE 1
          "; 
        if($country!=""){
          $sql3.=" AND custbranch.`cid`='$country'";  
        }
       if($state!=""){
          $sql3.=" AND custbranch.`stateid`='$state'";  
        } 
          if($Districts!=""){
          $sql3.=" AND  custbranch.`did`='$Districts'";  
        }

    
   }else 
if($name!=""){      
     $sql3="SELECT `debtorno` ,
         `brname` , `braddress1` ,
          concat (`phoneno` ,'/', `faxno`) as phone
          FROM `custbranch`
          WHERE 1"; 
        $sql3.=" AND custbranch.brname like '%$name%'";
        if($phone!=""){
          $sql3.=" AND custbranch.phoneno=$phone";  
        }
       if($email!=""){
          $sql_insident.=" AND custbranch.email='$email'";  
        } 
     }
     
     elseif($phone!=""){
          $sql3="SELECT `debtorno` ,
         `brname` , `braddress1` ,
          concat (`phoneno` ,'/', `faxno`) as phone
          FROM `custbranch`
          WHERE 1"; 
          $sql3.=" AND custbranch.phoneno=$phone";  
          if($email!=""){
          $sql_insident.=" AND custbranch.email='$email'";  
        } 
         }
      
   
         
   // echo $sql3;   
   $result3=DB_query($sql3,$db);
     $incident3_count=DB_num_rows($result3);
     if($incident3_count>0){
     echo"<div style='height:150px; width:100%; overflow:scroll;'>";     
     echo"<fieldset style='width:600px' ><legend>Order Details</legend>";//style='width:1px'
     echo"<table style='width:100%'> ";
     
      echo"<th>Cust_id</th><th>Customer Name</th><th>Mobile</th><th>Address</th>";  
         
      while($row_insident3=DB_fetch_array($result3))
      
      {
         $debtor=$row_insident3['debtorno'];  
                                                                                                        
            if ($k == 1) {
            echo '<tr onclick=selectOrder("'.$debtor.'") class="EvenTableRows">';
            $k = 0;
            } else {
            echo '<tr onclick=selectOrder("'.$debtor.'") class="OddTableRows">';
            $k++;
            }                                                                                                                                                                                                                
  
         echo"<td> ".$row_insident3['debtorno']."</td>
              <td>".$row_insident3['brname']."</td>
              <td>".$row_insident3['phone']."</td> 
              <td>".$row_insident3['braddress1']."</td>
              
              <td><a  style='cursor:pointer;' onclick=selectOrder('$debtor')>" . _('Select') . "</a></td>";      
         echo"</tr>";
         }
         echo"</table>";
         echo"</fieldset>";
         echo"</div>";       
         
                  
          
      }

      echo"</td>";

echo"<td>";

  
   if($_GET['Districts']!="")
   {
      $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incidents.createdon,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.area1
                  FROM bio_incidents,bio_incident_cust
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id"; 
        if($country!=""){
          $sql_insident.=" AND bio_incident_cust.`nationality`='$country'";  
        }
       if($state!=""){
          $sql_insident.=" AND bio_incident_cust.`state`='$state'";  
        } 
          if($Districts!=""){
          $sql_insident.=" AND  bio_incident_cust.`district`='$Districts'";  
        }
          if($lsgName!=""){
          $sql_insident.=" AND bio_incident_cust.`LSG_name`='$lsgName'";  
        } 
          if($gramaPanchayath!=""){
          $sql_insident.=" bio_incident_cust.`block_name`='$gramaPanchayath'";  
        }
        
   
                 
       //   echo $sql3
        
     
       // and custbranch.`LSG_name`=
      //  and custbranch.`block_name`=

    
   }else 
if($debtorno!="")
{
    
    $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incidents.createdon,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.area1
                  FROM bio_incidents,bio_incident_cust
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id";
          $sql_insident.="           AND bio_incidents.orderno=salesorders.orderno
            AND salesorders.debtorno like '".$debtorno."' ";
            
         
      
        
}  
    else  if($name!=""){               
        $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incidents.createdon,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.area1
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
                   bio_incident_cust.area1
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
                   bio_incident_cust.area1
                  FROM bio_incidents,bio_incident_cust
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id";   
         $sql_insident.=" AND bio_incident_cust.custmail='$email'"; 
         }
         
      elseif($ticketno!=""){
             $sql_insident="SELECT 
                   bio_incidents.ticketno,
                   bio_incidents.createdon,
                   bio_incident_cust.custname,
                   bio_incident_cust.custphone,
                   bio_incident_cust.area1
                  FROM bio_incidents,bio_incident_cust
                  WHERE bio_incident_cust.cust_id=bio_incidents.cust_id";   
         $sql_insident.=" AND bio_incidents.ticketno='$ticketno'"; 
         }   
     
//      echo $sql_insident;
    $result_insident=DB_query($sql_insident,$db);
    $incident_count=DB_num_rows($result_insident);
     if($incident_count>0){
     echo"<div style='height:150px; width:100%; overflow:scroll;'>";     
     echo"<fieldset style='width:650px'><legend>Complaint Details</legend>";//style='width:450px'>
     echo"<table style='width:100%'> ";
          
     echo"<th>Ticket No</th><th>Customer Name</th><th>Mobile</th><th>Address</th><th>Created Date</th>";    
     while($row_insident=DB_fetch_array($result_insident)){
          $custid=$row_insident['cust_id'];  
          $ticketid=$row_insident['ticketno'];  
          $dat= ConvertSQLDate($row_insident['createdon']); 
            if ($k == 1) {
            echo '<tr  onclick=searchticket('.$ticketid.') class="EvenTableRows">';
            $k = 0;
            } else {
            echo '<tr  onclick=searchticket('.$ticketid.') class="OddTableRows">';
            $k++;
            } 
            
         echo"<td>".$row_insident['ticketno']."</td> 
              <td>".$row_insident['custname']."</td>
              <td>".$row_insident['custphone']."</td> 
              <td>".$row_insident['area1']."</td> 
              <td>".ConvertSQLDate($row_insident['createdon'])."</td> 
              <td><a  style='cursor:pointer;' onclick=searchticket('$ticketid')>" . _('Select') . "</a></td>";
     }
        
         echo"</tr>";
         echo"</table>";
         echo"</fieldset>";
         echo"</div>";
         
     }

     
   echo"</td></tr></table>";
  
                                                             
  
?>
