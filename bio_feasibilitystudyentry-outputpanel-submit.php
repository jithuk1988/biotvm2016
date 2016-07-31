<?php
$PageSecurity = 80;
include('includes/session.inc');


$outputtype=$_GET['type'];
//print_r($outputtype);
$_POST['Leadid']=$_GET["leadid"]; 
 
$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_output
       WHERE leadid=".$_POST['Leadid'];
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 

if($myrow9[0] > 0)      { 
    
$sql1="UPDATE bio_fsentry_output SET output='".$outputtype."' 
                               WHERE bio_fsentry_output.leadid ='".$_POST['Leadid']."'";  
                               
    
$result1=DB_query($sql1,$db);     
    
    
}else       {

 $sql = "INSERT INTO bio_fsentry_output(leadid,output)
                                  VALUES ('" . $_POST['Leadid'] . "',
                                          '".$outputtype."')";                                           
 $result = DB_query($sql,$db);

} 
?>
