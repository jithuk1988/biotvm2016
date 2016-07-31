<?php
     $PageSecurity = 80;
     include('includes/session.inc');
     
     $title = _('Dropped team');  
include('includes/header.inc');

echo '<center><font style="color: #333;
                background:#fff;
                font-weight:bold;
                letter-spacing:0.10em;
                font-size:16px;
                font-family:Georgia;
                text-shadow: 1px 1px 1px #666;">Dropped team</font></center>';
     

$find_drop_team_arr=array();     
     
$sql="SELECT leadid
FROM `bio_leads`
WHERE `enqtypeid` =2
AND leadstatus
IN ( 20, 21, 22, 23, 24 )";
          $i=0;
$result=DB_query($sql,$db);

while($myrow=DB_fetch_array($result))  
{
    
    $find_drop_team_arr[]=$myrow['leadid'];
    
}

           foreach($find_drop_team_arr as $lead)
           {
                      $i++;
                   $sql_tid="SELECT max(tid) FROM bio_leadtask WHERE leadid =".$lead;
                   $result_tid=DB_query($sql_tid,$db);
                   $myrow_tid=DB_fetch_array($result_tid);
                    
                   $sql_update="UPDATE bio_leadtask SET viewstatus = 2 WHERE tid = '".$myrow_tid[0]."'";
                   DB_query($sql_update,$db);
                echo  $lead. '<br>';
                   
           } 
           
            echo '<br><br><br>'.$i;
                          
    
?>
