<?php
$PageSecurity = 80;
include('includes/session.inc');

$_POST['Leadid']=$_GET["leadid"];
$_POST['SoilNature']=$_GET["soilnature"]; 
$_POST['WaterAvailability']=$_GET["wateravailability"];  
$_POST['WaterSource']=$_GET["watersource"];  

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_soil
       WHERE leadid=".$_POST['Leadid'];
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 

if($myrow9[0] > 0)      { 

 $sql = "UPDATE    bio_fsentry_soil
               SET 
                                  nature_soil='".$_POST['SoilNature']."' ,
                            aval_water='".$_POST['WaterAvailability']."',
                            water_source='".$_POST['WaterSource']."'  
                        
              WHERE        leadid =" .$_POST['Leadid'];
            
  $result=DB_query($sql,$db);    
    
    
}else  {

 $sql = "INSERT INTO bio_fsentry_soil(leadid,
                                      nature_soil,
                                      aval_water,
                                      water_source)
                              VALUES ('" . $_POST['Leadid'] . "',
                                      '" . $_POST['SoilNature'] . "',
                                      '" . $_POST['WaterAvailability'] . "',
                                      '" . $_POST['WaterSource'] . "')";                                           
 $result = DB_query($sql,$db);
 
}
?>
