<?php 
$PageSecurity = 80;  
include('includes/session.inc');


 $cdate=$_GET['cdate'];
 $calno=$_GET['calno'];
 $orderno=$_GET['ord'];
 $p_status=$_GET['p_ststus'];
 $status=$_GET['statuss'];
 $remark=$_GET['remarkk'];
  $x=$_GET['close'];
   $cdate=FormatDateForSQL($cdate);

     if($status==1)
{
   $sql_sel="SELECT * FROM bio_installation_status WHERE orderno=".$orderno;                        
     $result_all=DB_query($sql_sel,$db);
    $main_tb=DB_num_rows($result_all); 
    $main_tbl=DB_fetch_array($result_all);       
    $actualdate=$main_tbl['actual_date1']; 
  $actualdate2=$main_tbl['actual_date2']; 
  $actualdate3=$main_tbl['actual_date3']; 
 $duedate2=$main_tbl['due_date2'];
  $duedate3=$main_tbl['due_date3'];  

   if($calno==1)
   {
    
       if($cdate!='0000-00-00')
       {
        //echo $actualdate=FormatDateForSQL($actualdate);
           $date = strtotime(date("Y-m-d", strtotime($cdate)) . " +7 day");
            $duedate2=date('Y-m-d',$date);
      }
      $actualdate=$cdate;
      $x=0;
   }
   else if($calno==2) 
   {
       
       if($cdate!='0000-00-00'){
           $date = strtotime(date("Y-m-d", strtotime($cdate)) . " +11 day");
        $duedate3=date('Y-m-d',$date);
      }
      $actualdate2=$cdate;
            $x=0;
   }
   else if($calno==3)
   {
         $actualdate3=$cdate;
   }
 $sql4="UPDATE  bio_installation_status 
                                          SET actual_date1='".$actualdate."',
                                          actual_date2='".$actualdate2."',
                                          due_date2='".$duedate2."',
                                          actual_date3='".$actualdate3."',
                                          due_date3='".$duedate3."',
                                          plant_status='".$p_status."',
                                          close_status='".$x."',
                                          remarks='".$remark."' 
                                          WHERE orderno='".$orderno."'";                     
                                          $result4=DB_query($sql4,$db); 
                                       //   prnMsg(_('Updated') ,'success'); 

}

 $sql_in="SELECT COUNT(*) as orderno FROM bio_calllog WHERE orderno=".$orderno." AND callno=".$calno;                                                                                                                                                                                              $result_in=DB_query($sql_in,$db);
                  $actual_in=DB_fetch_array($result_in);
                 
               
 $sql="INSERT INTO bio_calllog (orderno,callno,call_date,remark,status) 
                            VALUES (".$orderno.",
                            ".$calno.",
                            '".date('Y-m-d H:i:s')."',
                            '".$remark."',
                            ".$status.")";
   
                           $result=DB_query($sql,$db);  
                           prnMsg(_('Updated') ,'success'); 
                           
                 // echo "<script>window.location =bio_installationstatus.php</scrit>";
   
?>
