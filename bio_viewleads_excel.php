<?php
  $PageSecurity = 80;
include('includes/session.inc');

if($_POST['excel'])
{
        
 $sql=$_SESSION[$viewleads_sql]; 
// unset($_SESSION[$viewleads_sql]);

$sql=stripslashes($sql);
//echo$sql;

$result=DB_query($sql,$db); 
  
      $filename="LeadDetails.csv";
      $header= "slno".","."Customer Name-Contact".","."District".","."Contact No".","."Date".","."Team".","."Status".","."\n";"\n";
      $data='';
      $slno=1;
  while($myrow=DB_fetch_array($result))     {
                 $custname=$myrow['custname'];  
     if($myrow['enqtypeid']==2){
          if($myrow['contactperson']!="" OR $myrow['contactperson']!=0){
             $custname=$myrow['custname']." - ".$myrow['contactperson']; 
          }else{
             $custname=$myrow['custname']; 
          }
      }
     $custmob=$myrow['custmob'];
     if($custmob=="" OR $custmob==0){
          $custmob=$myrow['custphone'];
          if($custmob=="" OR $custmob==0){
              $custmob="";
          }      } 
       
     $leaddate=ConvertSQLDate($myrow['leaddate']);
     $teamname=$myrow['teamname']; 
     if($teamname=="" OR $teamname=='0'){
          $teamname='Not Assigned';
     }
     
      
     $data= $data.$slno.",".$custname.",".$myrow['district'].",".$custmob.",".$leaddate.",".$teamname.",".$myrow['biostatus']."\n";    
     $slno++;    
     }
      
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";
}

?>
