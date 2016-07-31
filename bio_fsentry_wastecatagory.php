<?php
  $PageSecurity = 80;
include('includes/session.inc');

$edeg=$_GET['ed'];
$sdeg=$_GET['sd'];
$vsdeg=$_GET['vsd'];
$nonedeg=$_GET['nd'];
$projected=$_GET['proj'];
$check=$_GET['check'];
$swaste=$_GET['swaste'];
$lqwaste=$_GET['lwaste'];
$lqprojected=$_GET['lproj'];

//if($edeg!="" OR $sdeg!="" OR $vsdeg!="" OR $nonedeg!="" OR $projected!=""){
 if($_GET['proj']!=""){
 if($check==0){   
//$actual_fs=$edeg+$sdeg+$vsdeg+$nonedeg;
$actual_fs=$swaste+$lqwaste;
       
echo"<fieldset style='width:400px;height:170px; overflow:auto;'>";
echo"<legend>Total Feedstock</legend>";
echo"<table>";
if($swaste>0){
echo"<tr><td>Solid Feedstock</td>";
echo"<td><input type='Text' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px></td></tr>";
echo"<tr></tr>";

echo"<tr><td>Projected Solid Feedstocks</td>";
echo"<td><input type='text' name='projected' id='projected' value='".$projected."' style='width:165px'></td></tr>";
echo"<tr></tr>";
}else{
   echo"<td><input type='hidden' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px></td></tr>";
   echo"<td><input type='hidden' name='projected' id='projected' value='".$projected."' style='width:165px'></td></tr>"; 
}
if($lqwaste>0){
echo"<tr><td>Liquid Feedstock</td>";
echo"<td><input type='Text' name='LiquidWaste' id='liquidwaste' value='".$lqwaste."' style=width:165px></td></tr>";
echo"<tr></tr>";

echo"<tr><td>Projected Liquid Feedstocks</td>";
echo"<td><input type='Text' name='ProjectedLiquid' id='projectedliquid' value='".$lqprojected."' style='width:165px'></td></tr>";
echo"<tr></tr>";
}else{
  echo"<td><input type='hidden' name='LiquidWaste' id='liquidwaste' value='".$lqwaste."' style=width:165px></td></tr>";
  echo"<td><input type='hidden' name='ProjectedLiquid' id='projectedliquid' value='".$lqprojected."' style='width:165px'></td></tr>";
  
}
echo"<tr><td width=50%>Actual Feedstocks</td>";
echo"<td><input type='hidden' name='actual' id='actual' value='".$actual_fs."' style='width:150px'>:$actual_fs</td></tr>";
echo"<tr></tr>";

echo"<tr>";
//echo"<tr><td>Easily Degradable</td>";
echo"<td><input type='hidden' name='edegradable' id='edegradable' value='".$edeg."' style=width:165px></td></tr>";
echo"<tr></tr>";

//echo"<tr><td>Slow Degradable</td>";
echo"<td><input type='hidden' name='sdegradable' id='sedegradable' value='".$sdeg."' style=width:165px></td></tr>";
echo"<tr></tr>";
echo"<tr>";
//echo"<tr><td>Very Slow Degradable</td>";
echo"<td><input type='hidden' name='vsdegradable' id='vsedegradable' value='".$vsdeg."' style=width:165px></td></tr>";
echo"<tr></tr>";
echo"<tr>";
//echo"<tr><td>Non Degradable</td>";
echo"<td><input type='hidden' name='nondegradable' id='nonedegradable' value='".$nonedeg."' style=width:165px></td></tr>";
echo"<tr></tr>";



echo"</table>";
echo"</fieldset>"; 
   
}
else{
$edeg=0;
$sdeg=0;
$vsdeg=0;
$nonedeg=0;
$swaste=0;
$lqwaste=0;

$sql5="SELECT * FROM bio_feeddetailstemp";
$result5=DB_query($sql5,$db);
//echo"ccccc:".$count=DB_num_rows($result5);
//echo"<br />";
//print_r($result5); 
   
while($myrow5=DB_fetch_array($result5))     {
//   echo"<br />"; 
     $feedstock=$myrow5['feedstockid'];
    
    $feedstock_source=$myrow5['feedstocksourceid'];
    $no_of_source=$myrow5['number_source'];
    $weight=$myrow5['weight'];
//    echo"<br />";
     $sql_wtype="SELECT waste_category FROM bio_feedstocks
                WHERE id=".$feedstock;
    $result_wtype=DB_query($sql_wtype,$db);
    $myrow_wtype=DB_fetch_array($result_wtype);
//    echo"<br />";
     $waste_type=$myrow_wtype['waste_category'];
    
    if($waste_type==1){
        $edeg=$edeg+$weight;
    }elseif($waste_type==2){
        $sdeg=$sdeg+$weight;
    }elseif($waste_type==3){
        $vsdeg=$vsdeg+$weight;
    }elseif($waste_type==4){
        $nonedeg=$nonedeg+$weight;
    }
   
    $sql_wtype="SELECT waste_type FROM bio_feedstocks
                WHERE id=".$feedstock;
    $result_wtype=DB_query($sql_wtype,$db);
    $myrow_wtype=DB_fetch_array($result_wtype);
     $waste_type1=$myrow_wtype['waste_type'];
    
    if($waste_type1==1){
        $lqwaste=$lqwaste+$weight;
    }elseif($waste_type1==2){
        $swaste=$swaste+$weight;
    }
    
}

$actual_fs=$lqwaste+$swaste;



echo"<fieldset style='width:400px;height:170px; overflow:auto;'>";
echo"<legend>Total Feedstock</legend>";
echo"<table>";
if($swaste>0){
echo"<tr><td>Solid Feedstock</td>";
echo"<td><input type='Text' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px></td></tr>";
echo"<tr></tr>";

echo"<tr><td>Projected Solid Feedstocks</td>";
echo"<td><input type='text' name='projected' id='projected' value='".$projected."' style='width:165px'></td></tr>";
echo"<tr></tr>";
}else{
   echo"<td><input type='hidden' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px></td></tr>";
   echo"<td><input type='hidden' name='projected' id='projected' value='".$projected."' style='width:165px'></td></tr>"; 
}
if($lqwaste>0){
echo"<tr><td>Liquid Feedstock</td>";
echo"<td><input type='Text' name='LiquidWaste' id='liquidwaste' value='".$lqwaste."' style=width:165px></td></tr>";
echo"<tr></tr>";

echo"<tr><td>Projected Liquid Feedstocks</td>";
echo"<td><input type='Text' name='ProjectedLiquid' id='projectedliquid' value='".$lqprojected."' style='width:165px'></td></tr>";
echo"<tr></tr>";
}else{
  echo"<td><input type='hidden' name='LiquidWaste' id='liquidwaste' value='".$lqwaste."' style=width:165px></td></tr>";
  echo"<td><input type='hidden' name='ProjectedLiquid' id='projectedliquid' value='".$lqprojected."' style='width:165px'></td></tr>";
  
}
echo"<tr><td width=50%>Actual Feedstocks</td>";
echo"<td><input type='hidden' name='actual' id='actual' value='".$actual_fs."' style='width:150px'>:$actual_fs</td></tr>";
echo"<tr></tr>";

echo"<tr>";
//echo"<tr><td>Easily Degradable</td>";
echo"<td><input type='hidden' name='edegradable' id='edegradable' value='".$edeg."' style=width:165px></td></tr>";
echo"<tr></tr>";
echo"<tr>";
//echo"<tr><td>Slow Degradable</td>";
echo"<td><input type='hidden' name='sdegradable' id='sedegradable' value='".$sdeg."' style=width:165px></td></tr>";
echo"<tr></tr>";
echo"<tr>";
//echo"<tr><td>Very Slow Degradable</td>";
echo"<td><input type='hidden' name='vsdegradable' id='vsedegradable' value='".$vsdeg."' style=width:165px></td></tr>";
echo"<tr></tr>";
echo"<tr>";
//echo"<tr><td>Non Degradable</td>";
echo"<td><input type='hidden' name='nondegradable' id='nonedegradable' value='".$nonedeg."' style=width:165px></td></tr>";
echo"<tr></tr>";



echo"</table>";
echo"</fieldset>";

echo"<td><input type='hidden' name='check' id='check' value='1' style='width:150px'></td></tr>"; 
}




}
else{
$edeg=0;
$sdeg=0;
$vsdeg=0;
$nonedeg=0;
$swaste=0;
$lqwaste=0;
$sql5="SELECT * FROM bio_feeddetailstemp";
$result5=DB_query($sql5,$db);
//echo"ccccc:".$count=DB_num_rows($result5);     
while($myrow5=DB_fetch_array($result5))     {
    $feedstock=$myrow5['feedstockid'];
    $feedstock_source=$myrow5['feedstocksourceid'];
    $no_of_source=$myrow5['number_source'];
    $weight=$myrow5['weight'];
    
    $sql_wtype="SELECT waste_category FROM bio_feedstocks
                WHERE id=".$feedstock;
    $result_wtype=DB_query($sql_wtype,$db);
    $myrow_wtype=DB_fetch_array($result_wtype);
    $waste_type=$myrow_wtype['waste_category'];
    
    if($waste_type==1){
        $edeg=$edeg+$weight;
    }elseif($waste_type==2){
        $sdeg=$sdeg+$weight;
    }elseif($waste_type==3){
        $vsdeg=$vsdeg+$weight;
    }elseif($waste_type==4){
        $nonedeg=$nonedeg+$weight;
    }
$sql_wtype="SELECT waste_type FROM bio_feedstocks
                WHERE id=".$feedstock;
    $result_wtype=DB_query($sql_wtype,$db);
    $myrow_wtype=DB_fetch_array($result_wtype);
     $waste_type1=$myrow_wtype['waste_type'];
    
    if($waste_type1==1){
        $lqwaste=$lqwaste+$weight;
    }elseif($waste_type1==2){
        $swaste=$swaste+$weight;
    }
    
}

$actual_fs=$lqwaste+$swaste;


echo"<fieldset style='width:400px;height:170px; overflow:auto;'>";
echo"<legend>Total Feedstock</legend>";
echo"<table>";
if($swaste>0){
echo"<tr><td>Solid Feedstock</td>";
echo"<td><input type='Text' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px></td></tr>";
echo"<tr></tr>";

echo"<tr><td>Projected Solid Feedstocks</td>";
echo"<td><input type='text' name='projected' id='projected' value='".$projected."' style='width:165px'></td></tr>";
echo"<tr></tr>";
}else{
   echo"<td><input type='hidden' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px></td></tr>";
   echo"<td><input type='hidden' name='projected' id='projected' value='".$projected."' style='width:165px'></td></tr>"; 
}

if($lqwaste>0){
echo"<tr><td>Liquid Feedstock</td>";
echo"<td><input type='Text' name='LiquidWaste' id='liquidwaste' value='".$lqwaste."' style=width:165px></td></tr>";
echo"<tr></tr>";

echo"<tr><td>Projected Liquid Feedstocks</td>";
echo"<td><input type='Text' name='ProjectedLiquid' id='projectedliquid' value='".$lqprojected."' style='width:165px'></td></tr>";
echo"<tr></tr>";
}else{
  echo"<td><input type='hidden' name='LiquidWaste' id='liquidwaste' value='".$lqwaste."' style=width:165px></td></tr>";
  echo"<td><input type='hidden' name='ProjectedLiquid' id='projectedliquid' value='".$lqprojected."' style='width:165px'></td></tr>";
  
}
echo"<tr><td width=50%>Actual Feedstocks</td>";
echo"<td><input type='hidden' name='actual' id='actual' value='".$actual_fs."' style='width:150px'>:$actual_fs</td></tr>";
echo"<tr></tr>";

echo"<tr>";
//echo"<tr><td>Easily Degradable</td>";
echo"<td><input type='hidden' name='edegradable' id='edegradable' value='".$edeg."' style=width:165px></td></tr>";
echo"<tr></tr>";
echo"<tr>";
//echo"<tr><td>Slow Degradable</td>";
echo"<td><input type='hidden' name='sdegradable' id='sedegradable' value='".$sdeg."' style=width:165px></td></tr>";
echo"<tr></tr>";
echo"<tr>";
//echo"<tr><td>Very Slow Degradable</td>";
echo"<td><input type='hidden' name='vsdegradable' id='vsedegradable' value='".$vsdeg."' style=width:165px></td></tr>";
echo"<tr></tr>";
echo"<tr>";
//echo"<tr><td>Non Degradable</td>";
echo"<td><input type='hidden' name='nondegradable' id='nonedegradable' value='".$nonedeg."' style=width:165px></td></tr>";
echo"<tr></tr>";



echo"</table>";
echo"</fieldset>";

echo"<td><input type='hidden' name='check' id='check' value='1' style='width:150px'></td></tr>";
}



?>
