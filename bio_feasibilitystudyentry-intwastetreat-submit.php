<?php
$PageSecurity = 80;
include('includes/session.inc');  

$_POST['Leadid']=$_GET["leadid"];
$_POST['Wastequn']=$_GET["wastequn"];
$_POST['Waterown']=$_GET["waterown"]; 
$_POST['Waterout']=$_GET["waterout"]; 
$_POST['Easilydegradable']=$_GET["easilydeg"]; 
$_POST['Slowlydegradable']=$_GET["slowlydeg"]; 
$_POST['Veryslowlydegradable']=$_GET["veryslowlydeg"]; 
$_POST['Roadsweeping']=$_GET["roadsweep"]; 
$_POST['Market']=$_GET["market"]; 
$_POST['Natureofwaste']=$_GET["natureofwaste"]; 

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_intwaste
       WHERE leadid=".$_POST['Leadid'];
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 

if($myrow9[0] > 0)      { 
    
  $sql2="UPDATE bio_fsentry_intwaste SET quantity_waste='" . $_POST['Wastequn'] . "',
                                      source_own='" . $_POST['Waterown'] . "',
                                      source_outside='" . $_POST['Waterout'] . "',     
                                      easily_deg='" . $_POST['Easilydegradable'] . "',  
                                      slowly_deg= '" . $_POST['Slowlydegradable'] . "', 
                                      vslowly_deg= '" . $_POST['Veryslowlydegradable'] ."',
                                      waste_road= '" . $_POST['Roadsweeping'] ."',
                                      waste_market= '" . $_POST['Market'] ."', 
                                      supply_waste= '" . $_POST['Natureofwaste'] ."' 
                       WHERE bio_fsentry_intwaste.leadid ='".$_POST['Leadid']."'";  
                                  
    
    
    
 $result2=DB_query($sql2,$db);     
    
}else       {

 $sql = "INSERT INTO bio_fsentry_intwaste(leadid,
                                          quantity_waste,
                                          source_own,
                                          source_outside,
                                          easily_deg,
                                          slowly_deg,
                                          vslowly_deg,
                                          waste_road,
                                          waste_market,
                                          supply_waste)
                                  VALUES ('" . $_POST['Leadid'] . "',
                                          '" . $_POST['Wastequn'] . "',
                                          '" . $_POST['Waterown'] . "',
                                          '" . $_POST['Waterout'] . "',   
                                          '" . $_POST['Easilydegradable'] . "',  
                                          '" . $_POST['Slowlydegradable'] . "',
                                          '" . $_POST['Veryslowlydegradable'] ."',
                                          '" . $_POST['Roadsweeping'] ."',
                                          '" . $_POST['Market'] ."',
                                          '" . $_POST['Natureofwaste'] ."' )";                                           
 $result = DB_query($sql,$db);
 
}
?>
