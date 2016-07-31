<?php
$PageSecurity = 80;
include('includes/session.inc');

$_POST['Leadid']=$_GET["leadid"];
$_POST['Inmates']=$_GET["inmates"];
$_POST['Staff']=$_GET["staff"]; 
$_POST['Workers']=$_GET["workers"]; 
$_POST['Canteen']=$_GET["canteen"]; 
$_POST['Food']=$_GET["food"]; 
$_POST['Morning']=$_GET["morning"]; 
$_POST['Lunch']=$_GET["lunch"]; 
$_POST['Evening']=$_GET["evening"]; 
$_POST['Dinner']=$_GET["dinner"]; 
$_POST['Visitors']=$_GET["visitors"]; 
$_POST['Function']=$_GET["spfunction"]; 
$_POST['Between']=$_GET["between"]; 
$_POST['And']=$_GET["and"]; 
$_POST['Funmonth']=$_GET["funmonth"]; 
$_POST['WastewaterTreat']=$_GET["wastewatertreat"]; 
$_POST['LatrineConnected']=$_GET["latrineconnected"]; 
$_POST['Latrine']=$_GET["latrine"]; 
$_POST['outputtype']=$_GET["outputtype"]; 
$_POST['Distance']=$_GET["distance"];

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_orgdata
       WHERE leadid=".$_POST['Leadid'];
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 

if($myrow9[0] > 0)      { 

$sql = "UPDATE   bio_fsentry_orgdata 
           SET 
                    no_inmates='".$_POST['Inmates']."' ,
                    no_staff='".$_POST['Staff']."',
                    no_workers='".$_POST['Workers']."' ,
                    canteen='".$_POST['Canteen']."' ,
                    food_served='".$_POST['Food']."' ,
                    no_morn='".$_POST['Morning']."' ,
                    no_lunch='".$_POST['Lunch']."' ,
                    no_eve='".$_POST['Evening']."' ,
                    no_dinner='".$_POST['Dinner']."' ,
                    visitors='".$_POST['Visitors']."',
                    special_fun='".$_POST['Function']."' ,
                    no_part_between='".$_POST['Between']."' ,
                    no_part_and='".$_POST['And']."' ,
                    no_fun_month='".$_POST['Funmonth']."' ,
                    organic_water='".$_POST['WastewaterTreat']."' ,
                    latrine_con='".$_POST['LatrineConnected']."',
                    no_latrine='".$_POST['Latrine']."' ,
                    output='".$_POST['outputtype']."' ,
                    distance_site='".$_POST['Distance']."'  
                    
           WHERE    leadid =" .$_POST['Leadid'];
            
     $result=DB_query($sql,$db);
              
    
}else       {

$sql = "INSERT INTO bio_fsentry_orgdata        (leadid,
                                                no_inmates,
                                                no_staff,
                                                no_workers,
                                                canteen,
                                                food_served,
                                                no_morn,
                                                no_lunch,
                                                no_eve,
                                                no_dinner,
                                                visitors,
                                                special_fun,    
                                                no_part_between,
                                                no_part_and,
                                                no_fun_month,
                                                organic_water,
                                                latrine_con,
                                                no_latrine,
                                                output,
                                                distance_site)
                                  VALUES ('" . $_POST['Leadid'] . "',
                                          '" . $_POST['Inmates'] . "',
                                          '" . $_POST['Staff'] . "',
                                          '" . $_POST['Workers'] . "',   
                                          '" . $_POST['Canteen'] . "',  
                                          '" . $_POST['Food'] . "',
                                          '" . $_POST['Morning'] ."',
                                          '" . $_POST['Lunch'] ."',
                                          '" . $_POST['Evening'] ."', 
                                          '" . $_POST['Dinner'] ."', 
                                          '" . $_POST['Visitors'] ."',
                                          '" . $_POST['Function'] ."',
                                          '" . $_POST['Between'] ."',
                                          '" . $_POST['And'] ."',
                                          '" . $_POST['Funmonth'] ."',
                                          '" . $_POST['WastewaterTreat'] ."',
                                          '" . $_POST['LatrineConnected'] ."',
                                          '" . $_POST['Latrine'] ."',
                                          '" . $_POST['outputtype'] ."',
                                          '" . $_POST['Distance'] ."')";                                           
 $result = DB_query($sql,$db);  

}
?>
