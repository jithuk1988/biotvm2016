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
?>
