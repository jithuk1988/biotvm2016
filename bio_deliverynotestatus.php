<?php
$PageSecurity = 80;  
include('includes/session.inc'); 

        // echo"jjj";
if($_GET['no']){    
if($_GET['status']){
    if($_GET['recdate']!=NULL){
 $sql="UPDATE bio_deliverynote SET recieve_date='".FormatDateForSQL($_GET['recdate'])."' , ack_recieved=1 WHERE orderno=".$_GET['ord']." 
AND despatch_id=".$_GET['no']."";
  $result=DB_query($sql,$db);
  
  $sql1="UPDATE  bio_installationstatus SET delivery_date='".FormatDateForSQL($_GET['recdate'])."'  WHERE orderno=".$_GET['ord']." 
AND despatch_id=".$_GET['no']."";
  $result1=DB_query($sql1,$db);
  echo"<div class=success>Delivery note for DN No. :".$_GET['no']." recieved successfully</div>";
    }
    else
    {
      echo"<div class=warn>Select recieved date</div>";    
    }
}
  else{
      $sql="UPDATE bio_deliverynote SET  recieve_date='0000-00-00', ack_recieved=0 WHERE orderno='".$_GET['ord']."' 
AND despatch_id='".$_GET['no']."'";
$result=DB_query($sql,$db);
      $sql1="UPDATE bio_installationstatus SET  delivery_date='0000-00-00'WHERE orderno='".$_GET['ord']."' 
AND despatch_id='".$_GET['no']."'";
$result1=DB_query($sql1,$db);
  echo"<div class=warn>Recieved status for DN No. :".$_GET['no']." removed</div>";
      
  }
    
}

?>
