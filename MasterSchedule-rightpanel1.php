<?php

 $pastyear1=$currentyear-1;  

$current=explode('/',$_POST['Duedate']);

 $pastdate1 = $current[0]."/".$current[1]."/".$pastyear1;
 $pastdatee1=ConvertSQLDate($pastdate1);
$sql2="SELECT quantity,demandid 
       FROM mrpdemands
       WHERE duedate='$pastdatee1'";
$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_row($result2);
$Quantity1= $myrow2[0];
$DemandID=$myrow2[1];
if($DemandID!='')       {
$sql="SELECT wo 
      FROM workorders
      WHERE demandid=$DemandID";
$result=DB_query($sql,$db);
$sum=0;
      $tableheader = "<thead><tr>
        <th>" . _('Year') . "</th>
        <th>" . _('Planned') . "</th>
        <th>" . _('Produced') . "</th>
        <th>" . _('Variation') . "</th>
        </tr></thead>";
echo $tableheader;
while($myrow=DB_fetch_array($result))       {
    
    $sql7="SELECT qtyreqd
           FROM woitems       
           WHERE  wo= $myrow[0]      
            ";
       $result7 = DB_query($sql7,$db,$ErrMsg); 
       $myrow7 = DB_fetch_row($result7); 

       $sum=$sum+$myrow7[0];
    
}


    $variation=$sum - $Quantity1;
    if($variation >0)       {
        
        $variation='+'.$variation;
        
    }
    echo "<tbody bgcolor=yellow>";
    echo '<tr>';
    echo '<td>'.$pastyear1.'</td>';
    echo '<td>'.$Quantity1.'</td>';
    echo '<td>'.$sum.'</td>';
    echo '<td>'.$variation.'</td>';
    echo '</tr>';
    echo '</tbody>';
}
?>
