<?php
$PageSecurity = 80;
include('includes/session.inc'); 

$_POST['Leadid']=$_GET["leadid"]; 
$_POST['Supervision']=$_GET["supervision"];
$_POST['Fertilizer']=$_GET["fertilizer"]; 
$_POST['Civilworks']=$_GET["civilworks"]; 
$_POST['Beneficiary']=$_GET["beneficiary"]; 
$_POST['Support']=$_GET["support"]; 
$_POST['Amc']=$_GET["amc"]; 
$_POST['Supportreq']=$_GET["supportreq"]; 
$_POST['Special']=$_GET["special"]; 

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_ben
       WHERE leadid=".$_POST['Leadid'];
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 

if($myrow9[0] > 0)      { 

  $sql = "UPDATE   bio_fsentry_ben
           SET 
                    liquid_fer='".$_POST['Supervision']."' ,
                    civil_work='".$_POST['Fertilizer']."',
                    materials_civil='".$_POST['Civilworks']."' ,
                    operation='".$_POST['Beneficiary']."' ,
                    amc='".$_POST['Support']."' ,
                    implement='".$_POST['Amc']."' ,
                    support='".$_POST['Supportreq']."' ,
                    instruction='".$_POST['Special']."' 
           WHERE    leadid =" .$_POST['Leadid'];
            
     $result=DB_query($sql,$db);    
    
}else       {

 $sql = "INSERT INTO bio_fsentry_ben(leadid,
                                     supervision,
                                     liquid_fer,
                                     civil_work,
                                     materials_civil,
                                     operation,
                                     amc,
                                     support,
                                     instruction)
                             VALUES ('" . $_POST['Leadid'] . "',
                                     '" . $_POST['Supervision'] . "',
                                     '" . $_POST['Fertilizer'] . "',
                                     '" . $_POST['Civilworks'] . "',   
                                     '" . $_POST['Beneficiary'] . "',  
                                     '" . $_POST['Support'] . "',
                                     '" . $_POST['Amc'] ."',
                                     '" . $_POST['Supportreq'] ."',
                                     '" . $_POST['Special'] ."' )";                                           
 $result = DB_query($sql,$db);  

}
?>
