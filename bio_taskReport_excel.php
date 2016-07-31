<?php
  $PageSecurity = 80;
include('includes/session.inc');

  if(isset($_POST['excel']))  
{ 
     $sql=$_SESSION[$inc_sql]; 
     unset($_SESSION[$inc_sql]);
    
     $result=DB_query($sql,$db);   
    
      $filename="TaskReport.csv";
      $header= "Slno".","."Customer Name".","."Contact No".","."Task Name".","."Team Name".","."Assigned Date".","."Due Date"."\n";"\n";
      $data='';
      $i=1;
      
      
      while($row=DB_fetch_array($result))     
      {
          
      if($row['enqtypeid']==2){
          if($row['contactperson']!="" OR $row['contactperson']!=0){
            $custname=$row['custname']." - ".$row['contactperson']; 
          }else{
            $custname=$row['custname']; 
          }
      }elseif($row['enqtypeid']==1){
            $custname=$row['custname'];
      }
      
    if($row['custmob']==""){
        $contactno=$row['custphone'];
    }else{
        $contactno=$row['custmob'];
    }    
          
           $data= $data.$i.",".$custname.",".$contactno.",".$row['task'].",".$row['teamname'].",".$row['assigneddate'].",".ConvertSQLDate($row['duedate'])."\n";    
           $i++;    
      } 
      
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";   
}
?>
