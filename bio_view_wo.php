<?php
  $PageSecurity=80;
include('includes/session.inc');
 $title = _('WO From Demand'); 
 include('includes/header.inc');
 
 echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Work Orders') . '</p>';
    $sql="SELECT woitems.wo,woitems.stockid,woitems.qtyreqd,woitems.qtyrecd,stockmaster.description 
FROM woitems,stockmaster 
WHERE woitems.wo IN (SELECT wo FROM workorders WHERE startdate = '".$_GET['y']."-".$_GET['m']."-".$_GET['d']."' AND loccode=".$_GET['loc']." )
AND woitems.stockid=stockmaster.stockid
 
ORDER BY woitems.wo";
$result=DB_query($sql,$db);

echo"<table style='border:1px solid #F0F0F0;width:70%'; >";   
echo"<tr>";
echo "<tr><th>Sl No.</th><th>WO Number</th><th width=185px>Item</th><th>Qty Required</th><th>Qty Recieved</th></tr>";
$k=0;
$i=1;
while($Row=DB_fetch_array($result))
{
    if ($k==1)
    {
    echo '<tr class="EvenTableRows">';
    $k=0;
    }else 
    {
    echo '<tr class="OddTableRows">';
    $k=1;     
    }
    echo "<td>".$i."</td>";
    echo "<td>".$Row['wo']."</td>";
    echo "<td>".$Row['description']."</td>";
    echo "<td>".$Row['qtyreqd']."</td>";
    echo "<td>".$Row['qtyrecd']."</td></tr>";
    $i++;
}
echo "</table>";

?>
