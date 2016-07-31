<?php

  $PageSecurity = 80;
include('includes/session.inc');
 $mailid=$_GET['mail'];
 $mob=$_GET['mobile'];     
 $code=$_GET['code'];  
   $eid=$_GET['edit'];  
  $phoneno=$_GET['phoneno']; 
  if($_GET['phoneno']!="")  
{
   $sql_dup = "SELECT `id` FROM `bio_network_cust` WHERE `phoneno`='".$phoneno."'  "; 
        if($eid!=null)
        {
           $sql_dup .=" AND `id` NOT IN ($eid) "; 
        }
        $result_dup=DB_query($sql_dup,$db);
              $count_dup=DB_num_rows($result_dup);
                        if($count_dup!=0)
             {

                 echo "<div class=warn>This Phone Number already exist for another Customer</div>"  ;   
                 echo"<input type='hidden' name='stop' id='stop' value='3'>";   
             } 
              
} 
if($_GET['mobile']!="")  
{
   $sql_dup = "SELECT `id` FROM `bio_network_cust` WHERE `mobileno`=$mob  "; 
        if($eid!=null)
        {
           $sql_dup .=" AND `id` NOT IN ($eid) "; 
        }
        $result_dup=DB_query($sql_dup,$db);
              $count_dup=DB_num_rows($result_dup);
                        if($count_dup!=0)
             {

                 echo "<div class=warn>This Mobile Number already exist for another Customer</div>"  ; 
                 echo"<input type='hidden' name='stop' id='stop' value='4'>";        
             } 
              
}
if($_GET['code']!="")  
{
  $sql_dup = "SELECT `id` FROM `bio_network_cust` WHERE `cust_code` = '".$code."'";
         if($eid!=null)
        {
           $sql_dup .=" AND `id` NOT IN ('".$eid."') "; 
        }
        $result_dup=DB_query($sql_dup,$db);
        $count_dup=DB_num_rows($result_dup);
           if($count_dup!=0)
             {
                 echo "<div class=warn>This Customer code already exist for another Customer</div>"  ; 
                 echo"<input type='hidden' name='stop' id='stop' value='1'>";     
             }
              
}
if($_GET['mail']!="")  
{
 $sql_dup = "SELECT `id` FROM `bio_network_cust` WHERE `mailid` = '".$mailid."'";
         if($eid!=null)
        {
           $sql_dup .=" AND `id` NOT IN ('".$eid."') "; 
        }
        $result_dup=DB_query($sql_dup,$db);
        $count_dup=DB_num_rows($result_dup);
           if($count_dup!=0)
             {
                 echo "<div class=warn>This Mail ID already exist for another Customer</div>"  ;
            echo"<input type='hidden' name='stop' id='stop' value='5'>";                 
             } 
              
}
 
             
?>

