<?php
  $PageSecurity = 80;
include('includes/session.inc');

$feedstock=$_GET['feed'];
$feedsource=$_GET['feedsource'];

$sql9="SELECT feedstock_unit
                 FROM bio_feedstocksources
                 WHERE feedstocksourcename=$feedsource
                 AND id=$feedstock"; 
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);
$unit=$myrow9['feedstock_unit'];

echo"<input type=hidden name='SectedUnit' id='selectedunit' value='$unit' style='width:40px'>";

if($unit==1 OR $unit==2){
    echo "<td>&nbspWeight in Kg/Ltr&nbsp&nbsp&nbsp&nbsp&nbsp</td>";
  echo "<td><input type=text name='Weight' id='weight' style='width:126px'></td>";
    
}else{
    echo "<td>&nbspNo. of sources&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>";
  echo "<td><input type=text name='NumSource' id='numsource' style='width:126px'></td>";
    
}


?>
