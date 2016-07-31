<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  include('includes/SQL_CommonFunctions.inc');  
  
  $stdcode=$_GET['std'];
  $mobile=$_GET['mobile'];  
  $enquiry=$_GET['enquiry'];
  
         // echo "gggggg";
if($_GET['mobile'])
{

  $InputError=0;
 $sql_check="SELECT * FROM bio_cust,bio_leads
                WHERE bio_cust.custmob='".$mobile."'
                AND bio_leads.enqtypeid=".$enquiry."
                AND bio_leads.cust_id=bio_cust.cust_id";
  $result_check=DB_query($sql_check,$db);
  $myrow_check=DB_fetch_array($result_check);
  $count=DB_num_rows($result_check);
  if($count>0){
      echo$myrow_check['leadid'];
  }
  else{
      echo"0";
  }  
}  

if($_GET['std'])
{
  $sql_check="SELECT * FROM bio_cust,bio_leads
                WHERE bio_cust.custphone='".$stdcode."'
                AND bio_leads.enqtypeid=".$enquiry."
                AND bio_leads.cust_id=bio_cust.cust_id";
  $result_check=DB_query($sql_check,$db);
  $myrow_check=DB_fetch_array($result_check);
  $count=DB_num_rows($result_check);
  if($count>0){
      echo$myrow_check['leadid'];
  }
  else{
      echo"0";
  }  
}
   

?>