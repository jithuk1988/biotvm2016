<?php
  $PageSecurity = 80;
include('includes/session.inc');
//$title = _('Incidents');   

if($_POST['excel'])
{
        
 $sql=$_SESSION[$inc_sql]; 
// unset($_SESSION[$inc_sql]);

$sql=stripslashes($sql);
//echo$sql;

$result=DB_query($sql,$db); 
  
      $filename="Incidentdepartment.csv";
      $header= "Slno".","."Ticket No".","."Customer Name".","."Phone No".","."Place".","."LSG".","."Complaint".","."Priority".","."Date"."\n";"\n";
      $data='';
      $slno=1;
  while($myrow3=DB_fetch_array($result))     {
      
      if($myrow3['LSG_type']==1){
         $LSG_name=$myrow3['corporation']."(C)";
     }elseif($myrow3['LSG_type']==2){
         $LSG_name=$myrow3['municipality']."(M)";
     }elseif($myrow3['LSG_type']==3){
         if($myrow3['block_name']!=0 || $myrow3['LSG_name']!=0){
         $LSG_name=$myrow3['name']."(P)";
         } 
     }else{
         $LSG_name="";
     }
      
      
      
      $custname=$myrow3['custname'];
      $custname=str_replace(",",' ',$custname); 
      $desc=$myrow3[description]; 
      $desc=str_replace("\r\n",' ',$desc);
             $ph= $myrow3['custphone'] ;
             if($ph=='' ||$ph==' ')                                                                                                   
             {
                 $ph=$myrow3['landline'] ;
             }
     $data= $data.$slno.",".$myrow3['ticketno'].",".$custname.",".$ph.",".$myrow3['area1'].",".$LSG_name.",".$myrow3['complaint'].",".$myrow3['priority'].",".$myrow3['createdon']."\n";    
     $slno++;    
     }
      
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";
}

?>
