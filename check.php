<?php
  $PageSecurity = 80;      
  include('includes/session.inc');
  
  $sql="SELECT * from bio_leads,bio_cust where bio_leads.enqtypeid=1
        and bio_leads.leadstatus not in(15,20,43,46,39,40)
        and bio_leads.cust_id=bio_cust.cust_id";
  $result=DB_query($sql,$db);
  while($myrow=DB_fetch_array($result)){
      
      $sql_cce="SELECT www_users.empid,
                     bio_teammembers.teamid 
                FROM www_users,bio_teammembers 
               WHERE bio_teammembers.empid=www_users.empid";
               
   if($myrow['nationality']==1 && $myrow['state']==14)        //KERALA
   {                  
       if( $myrow['district']==6 || $myrow['district']==11 || $myrow['district']==12 )    //KLM-PTA-TVM
       {
           $sql_cce.=" AND www_users.userid='".ccetvm1."'";
       }
       elseif( $myrow['district']==1 || $myrow['district']==2 || $myrow['district']==3 || $myrow['district']==7 || $myrow['district']==13 ) //ALP-EKM-IDK-KTM-TRS
       {
           $sql_cce.=" AND www_users.userid='".cceeklm1."'";                    
       }
       elseif( $myrow['district']==4 || $myrow['district']==5 || $myrow['district']==8 || $myrow['district']==9 || $myrow['district']==10 || $myrow['district']==14 ) //KNR-KSR-KZH-MLP-PLK-WND
       {
           $sql_cce.=" AND www_users.userid='".ccekoz1."'";
       }
   } 
   elseif($myrow['nationality']==1 && $myrow['state']!=14)     //OUTSIDE KERALA
   {
       $sql_cce.=" AND www_users.userid='".cccmho."'";
   }
   
   $result_cce=DB_query($sql_cce,$db);
   $row_cce=DB_fetch_array($result_cce);
   $teamid=$row_cce['teamid'];
   
   $sql_schedule="INSERT INTO bio_leadschedule VALUES(".$myrow['leadid'].",17)"; 
   $result_schedule=DB_query($sql_schedule,$db);
   
   $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=".$myrow['leadid'].")";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
    $assigned_date=date("Y-m-d");
     $date_interval=0;                                 

    while($row_schedule1=DB_fetch_array($result_schedule1))
    {       
       $taskid=$row_schedule1['task_master_id'];
        $date_interval+=$row_schedule1['actual_task_day'];
        
        //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
        
        $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                 leadid,
                                                 teamid,
                                                 assigneddate,
                                                 duedate,
                                                 assigned_from,
                                                 viewstatus)
                                     VALUES('".$taskid."',
                                            '".$myrow['leadid']."',
                                            '".$teamid."',
                                            '".$assigned_date."',
                                            '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                            '".$assignedfrm."',
                                            1)";
         $result_leadTask=DB_query($sql_leadTask,$db); 
        
        $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
        $date_interval+=1;                                   
    }            
      
      
  }
  

?>
