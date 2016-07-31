<?php
  $PageSecurity = 80;
include('includes/session.inc');
    $lead=$_GET['lead'];
             $fs=$_GET['fs']; 
               $amt= $_GET['amt'];
               $dat = $_GET['dat'];   
             /*    $sql_sou="SELECT bio_cust.custname, 
                 bio_district.district,
                 bio_leads.sourceid,
                 bio_leads.leadid, 
                 bio_cust.nationality,
                 bio_cust.state 
            FROM 
                 bio_cust
       LEFT JOIN 
                 bio_leads ON bio_cust.cust_id=bio_leads.cust_id 
       LEFT JOIN 
                 bio_district ON bio_district.did = bio_cust.district
             AND bio_cust.nationality = bio_district.cid
             AND bio_cust.state = bio_district.stateid
           WHERE
                 bio_leads.leadid=".$leadid;  
           


  $result_sou=DB_query($sql_sou,$db);    
  $myrow_sou=DB_fetch_array($result_sou);
  if($myrow_sou['nationality']==1 AND $myrow_sou['state']==14){
          $custtype=1;
      }elseif($myrow_sou['nationality']==1 AND $myrow_sou['state']!=14){
          $custtype=2;
      }elseif($myrow_sou['nationality']!=1){
          $custtype=3;
      }
 
 $sql1="SELECT bio_changepolicy.value
          FROM bio_changepolicy       
         WHERE bio_changepolicy.policyname='Institution FS Charge' 
           AND bio_changepolicy.enquirytype=".$custtype;
 
  $result1=DB_query($sql1,$db);    
  $myrow2=DB_fetch_array($result1); 
 
 $fs_amount=$myrow2['value'];
                                */
               
               $sql27="update bio_fsproposal set fp_amount=$amt, fp_expapproval='".$dat."', fp_createdby='".$_SESSION['UserID']."' where leadid=$lead and fs_propono=$fs";
                $rst=DB_query($sql27,$db);
               
                 

?>
