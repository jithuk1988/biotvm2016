<?php
$PageSecurity = 80; 
include('includes/session.inc'); 

if($_GET['status']){
    
    $actualdt=FormatDateForSQL($_GET['calno']);
 $sql_in="SELECT COUNT(*) as orderno FROM bio_calllog WHERE orderno=".$_GET['order']." AND
                                                                    callno=".$_GET['fdcal'];                                                                                                                                 
                                                                                                                                             
                                                                                                                                             $result_in=DB_query($sql_in,$db);
                  $actual_in=DB_fetch_array($result_in);
                   $actual_in['orderno'];
         if($actual_in['orderno']==0) {                 
 $sql="INSERT INTO bio_calllog (orderno,callno,call_date,remark,status) 
                            VALUES (".$_GET['order'].",
                            ".$_GET['fdcal'].",
                            '".$actualdt."',
                            '".$_GET['remark']."',
                            ".$_GET['status'].")";
                            $result=DB_query($sql,$db);  


         }else{
             $sql4="UPDATE  bio_calllog 
                                     SET call_date='".$actualdt."',
                                     status='".$_GET['status']."',
                                     remark='".$_GET['remark']."'                                                                                              WHERE orderno=".$_GET['order']."
                                     AND callno=".$_GET['fdcal']."
                                     AND status!=1";                     
                                     $result4=DB_query($sql4,$db);                                                          
         }
            $sql_actual="SELECT COUNT(*) as orderno FROM bio_calllog WHERE orderno=".$_GET['order'];                                                       $result_actual=DB_query($sql_actual,$db);
                                 $actual_dt=DB_fetch_array($result_actual);
         if($actual_dt['orderno']==1) { 
echo"<td><input type='text' name='actualdate1' id='actualdate1' value='".$_GET['calno']."'><td>";           
         }  
          if($actual_dt['orderno']==2) { 
echo"<td><input type='text' name='actualdate2' id='actualdate2' value='".$_GET['calno']."' ><td>";              
         } 
         if($actual_dt['orderno']>2) { 
echo"<td><input type='text' name='actualdate3' id='actualdate3' value='".$_GET['calno']."' ><td>";
             
         }                       
}
?>
