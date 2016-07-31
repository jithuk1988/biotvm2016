<?php
$PageSecurity = 80;
include('includes/session.inc');

$_POST['Leadid']=$_GET["leadid"];
$_POST['WaterCollection']=$_GET["watercollect"]; 
$_POST['Beneficiary']=$_GET["beneficiary"];
$_POST['WasteDisposal']=$_GET["wastedisp"];
$_POST['Consumption']=$_GET["watercons"];
$_POST['WaterDischarge']=$_GET["waterdisc"];
$_POST['Lpg']=$_GET["lpg"];
$_POST['Firewood']=$_GET["firewood"];
$_POST['Others']=$_GET["others"];

$_POST['SoilNature']=$_GET["soilnature"]; 
$_POST['WaterAvailability']=$_GET["wateravailability"];  
$_POST['WaterSource']=$_GET["watersource"];
$_POST['WaterLevel']=$_GET["waterlevel"];

$_POST['WastewaterTreat']=$_GET["wastewatertreat"]; 
$_POST['LatrineConnected']=$_GET["latrineconnected"]; 
$_POST['Latrine']=$_GET["latrine"]; 
$_POST['Distance']=$_GET["distance"];  
$_POST['PlantSpace']=$_GET["space"]; 
 


$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_basic
       WHERE leadid=".$_POST['Leadid'];
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 

if($myrow9[0] > 0)      { 
    
$sql1="UPDATE bio_fsentry_basic SET waste_collection='" . $_POST['WaterCollection'] . "',
                                      bin_collection='" . $_POST['Beneficiary'] . "',
                                      disposal_system='" . $_POST['WasteDisposal'] . "',     
                                      water_con='" . $_POST['Consumption'] . "',  
                                      water_discharge= '" . $_POST['WaterDischarge'] . "', 
                                      lpg= '" . $_POST['Lpg'] ."',
                                      fire_wood= '" . $_POST['Firewood'] ."',
                                      others= '" . $_POST['Others'] ."',
                                      water_level= '" . $_POST['WaterLevel'] ."',
                                      water_source= '" . $_POST['WaterSource'] ."',
                                      aval_water= '" . $_POST['WaterAvailability'] ."',
                                      nature_soil= '" . $_POST['SoilNature'] ."',
                                      organic_water= '" . $_POST['WastewaterTreat'] ."',
                                      latrine_con= '" . $_POST['LatrineConnected'] ."',
                                      no_latrine= '" . $_POST['Latrine'] ."',
                                      distance_site= '" . $_POST['Distance'] ."',
                                      space_plant= '" . $_POST['PlantSpace'] ."'  
                       WHERE bio_fsentry_basic.leadid ='".$_POST['Leadid']."'";  
                               
    $result1=DB_query($sql1,$db);     
    
    
}else       {

 $sql = "INSERT INTO bio_fsentry_basic     (leadid,
                                               waste_collection,
                                               bin_collection,
                                               disposal_system,
                                               water_con,
                                               water_discharge,
                                               lpg,
                                               fire_wood,
                                               others,
                                               water_level,
                                               water_source,
                                               aval_water,
                                               nature_soil,
                                               organic_water,
                                               latrine_con,
                                               no_latrine,
                                               distance_site,
                                               space_plant)
                                  VALUES ('" . $_POST['Leadid'] . "',
                                          '" . $_POST['WaterCollection'] . "',
                                          '" . $_POST['Beneficiary'] . "',
                                          '" . $_POST['WasteDisposal'] . "',   
                                          '" . $_POST['Consumption'] . "',  
                                          '" . $_POST['WaterDischarge'] . "',
                                          '" . $_POST['Lpg'] ."',
                                          '" . $_POST['Firewood'] ."',
                                          '" . $_POST['Others'] ."',
                                          '" . $_POST['WaterLevel'] ."',
                                          '" . $_POST['WaterSource'] ."',
                                          '" . $_POST['WaterAvailability'] ."',
                                          '" . $_POST['SoilNature'] ."',
                                          '" . $_POST['WastewaterTreat'] ."',
                                          '" . $_POST['LatrineConnected'] ."',
                                          '" . $_POST['Latrine'] ."',
                                          '" . $_POST['Distance'] ."',
                                          '" . $_POST['PlantSpace'] ."'
                                          )";                                           
 $result = DB_query($sql,$db);

} 
?>
