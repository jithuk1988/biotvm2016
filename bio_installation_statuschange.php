<?php
$PageSecurity = 80;  
include('includes/session.inc'); 

        // echo"jjj";
if($_GET['taskno']){
 
//echo$_GET[''];
$date=$_GET['date'];


  $user=$_SESSION['UserID'];
  date_default_timezone_set ("Asia/Calcutta"); 
   $curdate = date("Y-m-d  H:i:s", time()) ;
 
if($date==NULL){
     echo"<div class=warn>Select completed date</div>";
}
else{
    if($_GET['status']==1){
    $sql_stupdate="UPDATE bio_cstask SET bio_cstask.completeddate='".FormatDateForSQL($date)."' , bio_cstask.status=1 
                WHERE bio_cstask.cstype=1 
                AND bio_cstask.orderno='".$_GET['orderno']."'
                AND bio_cstask.taskno='".$_GET['taskno']."'
                AND bio_cstask.despatch_id='".$_GET['desid']."'";
                $result_stupdate=DB_query($sql_stupdate,$db);
               echo"<div class=success>Updated successfully. Completed Date='$date'</div>";
              //Get id to insert into task transaction table 
               $sql_get_maxid="SELECT id from bio_cstask WHERE cstype=1 AND orderno='".$_GET['orderno']."' AND  despatch_id='".$_GET['desid']."' AND taskno='".$_GET['taskno']."'";
               $result_get_maxid=DB_query($sql_get_maxid,$db);
               $max_id=DB_fetch_array($result_get_maxid);
               $maxid=$max_id['id'];
    
                $sql_trans="INSERT INTO bio_task_transaction (task_id,modified,date,user) VALUES ('".$maxid."',1,'".$curdate."','".$user."')" ;
                $result_trans=DB_query($sql_trans,$db);
          

               
               
                $sql_checkcompletion="select count(status) as count from bio_cstask where orderno='".$_GET['orderno']."' AND
                despatch_id='".$_GET['desid']."' AND status=0 AND cstype=1";
                
                
                if($_GET['delay']==1){
                    $sql_resch="SELECT * FROM bio_cstask 
                                                    where bio_cstask.despatch_id='".$_GET['desid']."' 
                                                    AND orderno='".$_GET['orderno']."'  AND cstype=1
                                                    ORDER BY bio_cstask.prevtask,bio_cstask.taskno";
                        $result_resch=DB_query($sql_resch,$db);              
                        
                        $sql_create_retemp="CREATE TEMPORARY TABLE IF NOT EXISTS bio_tempcsreschedule (orderno int,
                               despatch_id int,taskno int,taskdescription varchar(80),scheduleddate date,
                               daystocomplete int,prevtask int)";
                          $result_create_retemp=DB_query($sql_create_retemp,$db); 
                        
                        $i=0;
                        while($row_resch=DB_fetch_array($result_resch))
                        {
                            $i++;
                            if($i>$_GET['no'])
                            {  
                                $sql_reprev="SELECT prevtask from bio_cstask WHERE cstype=1 AND despatch_id='".$row_resch['despatch_id']."'
                                AND orderno='".$row_resch['orderno']."'  AND taskno='".$row_resch['taskno']."'";
                                  $result_reprev=DB_query($sql_reprev,$db);
                                $row_reprev=DB_fetch_array($result_reprev);  
                                     $sql_redate="SELECT taskno,scheduleddate, daystocomplete,completeddate from bio_cstask
                                     where cstype=1 AND despatch_id='".$row_resch['despatch_id']."' 
                                     AND orderno='".$row_resch['orderno']."' AND taskno='".$row_reprev['prevtask']."'  ";
                                       $result_redate=DB_query($sql_redate,$db);
                                $row_redate=DB_fetch_array($result_redate);
                                
                                if($row_redate['completeddate']=='0000-00-00')
                                {
                                     
                                    $sql_getscdate="SELECT scheduleddate from bio_tempcsreschedule WHERE taskno='".$row_redate['taskno']."' ";
                                      $result_getscdate=DB_query($sql_getscdate,$db);
                                $row_getscdate=DB_fetch_array($result_getscdate);
                                       $scdate;
                                  $scdate=$row_getscdate['scheduleddate'];
                                  $newdate2 = strtotime ('+'.$row_redate['daystocomplete'].' day' , strtotime ( $scdate));
                                    $newdate2 = date ( 'Y-m-d' , $newdate2 );
                                    
                                } 
                                else{
                                      $newdate = strtotime ('+'.$row_redate['daystocomplete'].' day' , strtotime ( $row_redate['scheduleddate']));
                                    $newdate = date ('Y-m-d',$newdate );
                                     $newdate; 
                                    if(strtotime($newdate2)< strtotime($row_redate['completeddate']))
                                    {
                                      $newdate2 = strtotime ('+1 day' , strtotime ( $row_redate['completeddate']));
                                    $newdate2 = date ('Y-m-d',$newdate2 );  
                                        
                                    } else{
                                       $newdate2=$newdate;
                                       
                                    }
                                    
                                    
                                }
                                    $newdate2;
                                   $sql_retempinsert="INSERT INTO bio_tempcsreschedule (taskno,daystocomplete,scheduleddate)
                                   VALUES ('".$row_resch['taskno']."' , '".$row_resch['daystocomplete']."' , '".$newdate2."') ";
                                      $resulet_retempinsert=DB_query($sql_retempinsert,$db);
                                  
                                    //  $newdate2 = strtotime ( '+'.$days1.' day' , strtotime ( $newdate1 ) );
    //$newdate2 = date ( 'Y-m-d' , $newdate2 );
                            
                             
                              //$sqi_re_temp="INSERT into bio_tempcsreschedule values";
                            }
                            
                            
                        }
                        
                        
                        $sql_getres_temp="SELECT *FROM bio_tempcsreschedule";
                        $result_getres_temp=DB_query($sql_getres_temp,$db);
                         
                        while($row_getres_temp=DB_fetch_array($result_getres_temp))
                        {     
                          $sql_reschedule="UPDATE bio_cstask SET scheduleddate='".$row_getres_temp['scheduleddate']."' 
                          WHERE cstype=1 AND orderno='".$_GET['orderno']."' AND despatch_id='".$_GET['desid']."' 
                          AND taskno='".$row_getres_temp['taskno']."' " ;
                              $result_reschedule=DB_query($sql_reschedule,$db);
                        }
                        
                              
                    
                }    
                
                
                
                $result_checkcompletion=DB_query($sql_checkcompletion,$db);
                                      $row_checkcompletion=DB_fetch_array($result_checkcompletion);
                if($row_checkcompletion['count']==0)
                {
                    $sql_inst_complete="UPDATE bio_installationstatus SET installation_date='".FormatDateForSQL($date)."' 
                    WHERE orderno='".$_GET['orderno']."'  AND despatch_id='".$_GET['desid']."' ";
                    $result_inst_complete=DB_query($sql_inst_complete,$db);
                     echo"<div class=success>Installation process completed.<a href=bio_pmscheduleassign.php?orderno=".$_GET['orderno']."&desid=".$_GET['desid']."> Click </a>here to assign warranty task</div>";
                    
                                       
                           $instdate=FormatDateForSQL($date);
    
                      $duedate=strtotime('+ 3 days',strtotime( $instdate));
                                    $duedate=date('Y-m-d',$duedate);   
                     $sql_followup="INSERT INTO bio_installation_status (orderno,installed_date,due_date1) VALUES (".$_GET['orderno'].",'".FormatDateForSQL($date)."','".$duedate."')";
                      $result_followup=DB_query($sql_followup,$db);  
                    /*$sql_inst_complete="UPDATE salesorders SET inst_completed=1 , inst_date='".FormatDateForSQL($date)."' 
                    WHERE orderno='".$_GET['orderno']."'  ";
                    $result_inst_complete=DB_query($sql_inst_complete,$db);
                     echo"<div class=success>Installation process completed.<a href=bio_pmscheduleassign.php?orderno=".$_GET['orderno']."&plant=".$_GET['plant']."> Click </a>here to assign warranty task</div>";*/
                    
                }
                
    }else{
        $sql_stupdate="UPDATE bio_cstask SET bio_cstask.completeddate='0000-00-00' , bio_cstask.status=0 
                WHERE bio_cstask.cstype=1 
                AND bio_cstask.orderno='".$_GET['orderno']."'
                AND bio_cstask.taskno='".$_GET['taskno']."'
                AND bio_cstask.despatch_id='".$_GET['desid']."'";  
                $result_stupdate=DB_query($sql_stupdate,$db);
               echo"<div class=success>Completed date removed, Status pending</div>";
               
               $sql_checkcompletion="select count(status) as count from bio_cstask where orderno='".$_GET['orderno']."' AND
                despatch_id='".$_GET['desid']."' AND status=0 AND cstype=1";
                
               $result_checkcompletion=DB_query($sql_checkcompletion,$db);
               $row_checkcompletion=DB_fetch_array($result_checkcompletion);
               if($row_checkcompletion['count']==0){
                   
                   $sql_inst_complete="UPDATE bio_installationstatus SET installation_date='0000-00-00' 
                    WHERE orderno='".$_GET['orderno']."'  AND despatch_id='".$_GET['desid']."' ";
                    $result_inst_complete=DB_query($sql_inst_complete,$db);
                   
                   /*$sql_inst_complete="UPDATE salesorders SET inst_completed=0 , inst_date='0000-00-00'
                    WHERE orderno='".$_GET['orderno']."'  ";
                    $result_inst_complete=DB_query($sql_inst_complete,$db);
                                                                                */
                    
                    
                    /*$sql_inst_complete="UPDATE bio_installationstatus SET installation_date='0000-00-00' 
                    WHERE orderno='".$_GET['orderno']."'  AND despatch_id='".$_GET['desid']."' ";
                    $result_inst_complete=DB_query($sql_inst_complete,$db);*/
                   echo"<div class=success>Installation completion updated as pending</div>";
                   
               }
               
        
    }
                
}
 

}

?>
