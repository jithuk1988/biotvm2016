<?php
$PageSecurity=80;
include('includes/session.inc');
  if($_GET['loc']!="")
 {
 $location=$_GET['loc'];
   
     echo" <select name='orderno'> ";

     echo   $sql = 'SELECT workorders.wo,startdate
FROM workorders
INNER JOIN woitems ON workorders.wo=woitems.wo
INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
WHERE workorders.closed="0"
AND (woitems.qtyreqd-woitems.qtyrecd !=0)
AND workorders.loccode="'.$location.'"';
if($_GET['type']==1)
 {
$sql .='GROUP BY workorders.startdate';
 }
 else{$sql .='GROUP BY workorders.wo';}
        $resultStkLocs = DB_query($sql,$db);

        while ($myrow=DB_fetch_array($resultStkLocs))
        {
         
echo '<option Value="' . $myrow['wo'] . '">'.ConvertSQLDate($myrow['startdate']).'-'.date('l', strtotime($myrow['startdate'])).'('.$myrow['wo'].')</option>';
            
        }

        echo '</select></td></tr>';
 } 
 
     

?>
