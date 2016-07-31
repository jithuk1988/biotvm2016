<?php
$PageSecurity = 80;
include('includes/session.inc');

$_POST['Leadid']=$_GET["leadid"];
$_POST['Powergenerated']=$_GET["powergen"];
$_POST['Powergenmorning']=$_GET["powergenmorn"];
$_POST['Powergenevening']=$_GET["powergenevn"];
$_POST['Genexist']=$_GET["genexist"];
$_POST['Capacity']=$_GET["capacity"];
$_POST['fuel']=$_GET["fuel"];
$_POST['useofelectricity']=$_GET["useofelectricity"];

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_powergen
       WHERE leadid=".$_POST['Leadid'];
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 

if($myrow9[0] > 0)      { 
    
  $sql3="UPDATE bio_fsentry_powergen SET quantity_power='" . $_POST['Powergenerated'] . "',
                                      
                                      power_gen_morn='" . $_POST['Powergenmorning'] . "',     
                                      power_gen_even='" . $_POST['Powergenevening'] . "',  
                                      generator= '" . $_POST['Genexist'] . "', 
                                      gen_capacity= '" . $_POST['Capacity'] ."',
                                      fuel_generator= '" . $_POST['fuel'] ."',
                                      electricity= '" . $_POST['useofelectricity'] ."' 
                                      
                       WHERE bio_fsentry_powergen.leadid ='".$_POST['Leadid']."'";  
                                  
    
    
    
 $result3=DB_query($sql3,$db);    
    
    
}else       {

 $sql = "INSERT INTO bio_fsentry_powergen(leadid,
                                          quantity_power,
                                          power_gen_morn,
                                          power_gen_even,
                                          generator,
                                          gen_capacity,
                                          fuel_generator,
                                          electricity)
                                  VALUES ('" . $_POST['Leadid'] . "', 
                                          '" . $_POST['Powergenerated'] . "',
                                          '" . $_POST['Powergenmorning'] . "',
                                          '" . $_POST['Powergenevening'] . "',   
                                          '" . $_POST['Genexist'] . "',  
                                          '" . $_POST['Capacity'] . "',
                                          '" . $_POST['fuel'] ."',
                                          '" . $_POST['useofelectricity'] ."' )";                                           
 $result = DB_query($sql,$db);  
 
}
?>
