<?php
$PageSecurity = 80;
include('includes/session.inc');
   
$sql1="SELECT * FROM  bio_businessassociates_enq";
    $result1=DB_query($sql1, $db);
    $count=DB_num_rows($result1);
       while($row1=DB_fetch_array($result1))
         {
              $row1['cust_id']; 
              $row1['description'];  
              $row1['createdby'];  
              $row1['createddate'];  
              $row1['mainmailcategory'];  
              $row1['submailcategory'];      
              $row1['emailtype'];       
                         
              

  $sql2="SELECT * FROM bio_businessassodetails_enq WHERE cust_id='".$row1['cust_id']."'";
   $result2=DB_query($sql2, $db);
   $row2=DB_fetch_array($result2);
        
        $row2['buss_id'];   $row2['cust_id'];   $row2['custname'];  $row2['custphone'];  $row2['custmob'];  $row2['custmail'];  
          $row2['houseno'];   $row2['housename'];  $row2['area1'];  $row2['area2'];  $row2['pin']; 
          $row2['nationality'];   $row2['state'];  $row2['district']; $row2['taluk'];  $row2['LSG_type']; 
          $row2['LSG_name'];  $row2['block_name']; $row2['LSG_ward'];   $row2['village'];
         
         
         
         
    $sql_cust = "INSERT INTO bio_incident_cust(custname,landline,custphone,custmail,
                                       houseno,housename,area1,area2,pin,nationality,state,district,
                                       taluk,LSG_type, LSG_name,block_name, LSG_ward, village)
                                  VALUES ('" . $row2['custname'] . "','" . $row2['custphone'] . "','" . $row2['custmob'] . "',
                                  '" . $row2['custmail'] . "','" . $row2['houseno'] . "','" . $row2['housename'] . "',
                                  '" . $row2['area1'] . "','" . $row2['area2'] . "','" . $row2['pin'] . "',
                                  '" . $row2['nationality'] . "','" . $row2['state'] . "','" . $row2['district'] . "',
                                   '".$row2['taluk']."','".$row2['LSG_type']."','".$row2['LSG_name']."', 
                                   '".$row2['block_name']."', '".$row2['LSG_ward']."', '".$row2['village']."')";                                           
       $result_cust = DB_query($sql_cust,$db);
       
               $cust_id=DB_Last_Insert_ID($Conn,'bio_incident_cust','cust_id');
              $duedate='0000-00-00';


if($row2['district']!=""){
       if($row2['district']!=" "){
            if($row2['district']!=0){

    $sql_2="SELECT *  FROM 
              bio_incidenthandlingoffcr 
              WHERE bio_incidenthandlingoffcr.types=2
              AND   bio_incidenthandlingoffcr.nationality=".$row2['nationality']."
              AND   bio_incidenthandlingoffcr.state=".$row2['state']."
              AND   bio_incidenthandlingoffcr.district=".$row2['district'];
    
  $result_2=DB_query($sql_2, $db);
  $count=DB_num_rows($result_2); 
  $row_2=DB_fetch_array($result_2) ;   
        $dept=$row2['department'];
  if($count>0){
$officer=$row_2['officer'];
  $dept=$row_2['department']; 
  }else{
      $officer=1;   $dept=$row_2['department']; 
  }
  }               
       }               
}else{
     $officer=1;
}              

  $dept=$row_2['department']; 
  
                  if($row1['enqtypeid']==""){$row1['enqtypeid']=""; }
                  if($row1['title']==""){$row1['title']=""; } 
                  
 $sql1_insert = "INSERT INTO bio_incidents(cust_id,type,source,enqtypeid,title,description,priority,
                                    expected_duedate,handling_officer,createdon,createdby,status,
                                    mainmailcategory,submailcategory,emailtype,buss_id,email_message)
                    VALUES ('" . $cust_id . "',2,2,'" . $row1['enqtypeid'] . "',
                                  '" . $row1['title'] . "', '" . $row1['description'] . "',2,
                                    '" . $duedate . "','" . $officer ."','" .  $row1['createddate'] . "',
                                    '" .  $row1['createdby'] ."',    1,'" . $row1['mainmailcategory'] . "',
                                    '" . $row1['submailcategory'] . "', '" .  $row1['emailtype']. "','" .  $row1['buss_id']. "', '" . $row1['description'] . "')";                                           
       $result1_insert  = DB_query($sql1_insert ,$db); 

         
  }
   
?>
