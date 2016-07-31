<?php
$PageSecurity = 80;  
include('includes/session.inc'); 

        // echo"jjj";
if($_GET['taskno']){    
//echo$_GET['plant'];
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
                WHERE bio_cstask.cstype=2 
                AND bio_cstask.orderno='".$_GET['orderno']."'
                AND bio_cstask.taskno='".$_GET['taskno']."'
                AND bio_cstask.despatch_id='".$_GET['desid']."'";
                $result_stupdate=DB_query($sql_stupdate,$db);
               echo"<div class=success>Updated successfully. Completed Date='$date'</div>";
               
                //task transaction
                
                 $sql_get_maxid="SELECT id from bio_cstask WHERE cstype=2 AND orderno='".$_GET['orderno']."' AND  despatch_id='".$_GET['desid']."' AND taskno='".$_GET['taskno']."'";
            $result_get_maxid=DB_query($sql_get_maxid,$db);
            $max_id=DB_fetch_array($result_get_maxid);
            $maxid=$max_id['id'];
    
    $sql_trans="INSERT INTO bio_task_transaction (task_id,modified,date,user) VALUES ('".$maxid."',1,'".$curdate."','".$user."')" ;
    $result_trans=DB_query($sql_trans,$db);
               
               
               
                $sql_checkcompletion="Select count(status) as count from bio_cstask where orderno='".$_GET['orderno']."' AND
                despatch_id='".$_GET['desid']."' AND status=0 AND cstype=2";
                $result_checkcompletion=DB_query($sql_checkcompletion,$db);
                                      $row_checkcompletion=DB_fetch_array($result_checkcompletion);
                if($row_checkcompletion['count']==0)
                {
                    /*$sql_inst_complete="UPDATE salesorders SET inst_completed=1 , inst_date='".FormatDateForSQL($date)."' 
                    WHERE orderno='".$_GET['orderno']."'  ";
                    $result_inst_complete=DB_query($sql_inst_complete,$db); */
                    // echo"<div class=success>Periodic maintenance process completed.<a href=?orderno=".$_GET['orderno']."&plant=".$_GET['plant']."> Click </a>here to AMC proposal</div>";
                    echo"<div class=success>Periodic maintenance process completed.<a href=> Click </a>here to AMC proposal</div>";  
                }
                
    }else{
        $sql_stupdate="UPDATE bio_cstask SET bio_cstask.completeddate='0000-00-00' , bio_cstask.status=0 
                WHERE bio_cstask.cstype=2
                AND bio_cstask.orderno='".$_GET['orderno']."'
                AND bio_cstask.taskno='".$_GET['taskno']."'
                AND bio_cstask.despatch_id='".$_GET['desid']."'";   
                $result_stupdate=DB_query($sql_stupdate,$db);
               echo"<div class=success>Completed date removed, Status pending</div>";
               
              /* $sql_checkcompletion="SELECT inst_completed,inst_date FROM salesorders WHERE orderno='".$_GET['orderno']."' ";
               $result_checkcompletion=DB_query($sql_checkcompletion,$db);
               $row_checkcompletion=DB_fetch_array($result_checkcompletion);
               if($row_checkcompletion['inst_completed']==1){
                   
                   $sql_inst_complete="UPDATE salesorders SET inst_completed=0 , inst_date='0000-00-00'
                    WHERE orderno='".$_GET['orderno']."'  ";
                    $result_inst_complete=DB_query($sql_inst_complete,$db);
                   echo"<div class=success>Installation completion updated as pending</div>";
                   
               } */
               
        
    }
                
}
 

}

?>
