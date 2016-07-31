<?php
$PageSecurity = 5;

include('includes/session.inc');

$suppcode=$_GET['id'];
$sql3="SELECT grns.grnno,
              grns.deliverydate,
              grns.qtyrecd,
              purchorderdetails.orderno,
              purchorderdetails.unitprice,
              stockmaster.description
       FROM grns,purchorderdetails,stockmaster
       WHERE grns.supplierid=$suppcode      AND
             grns.podetailitem=purchorderdetails.podetailitem   AND
             grns.itemcode=stockmaster.stockid";
$result3=DB_query($sql3,$db);

      echo '<table width=100% border=1 class=sortable>';
      $tableheader = "<thead><tr>
        <th>" . _('Sl no') . "</th>
        <th>" . _('Date') . "</th>
        <th>" . _('Description') . "</th>
        <th>" . _('Unit') . "</th>
        <th>" . _('Rate') . "</th>
        <th>" . _('Amount') . "</th>
        <th>" . _('GRN') . "</th>
        <th>" . _('Bill no & Date') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
$slno=0;      
while($myrow3=DB_fetch_array($result3))       {

$amount=$myrow3[2]*$myrow3[4];
$slno++;
echo'<tr>';  
echo'<td>'.$slno.'</td>';
echo'<td>'.$myrow3[1].'</td>'; 
echo'<td>Supply of '.$myrow3[5].'<br>PO No '.$myrow3[3].'</td>'; 
echo'<td>'.$myrow3[2].'</td>'; 
echo'<td>'.$myrow3[4].'</td>'; 
echo'<td>'.$amount.'</td>'; 
echo'<td>'.$myrow3[0].'</td>'; 
echo'<td></td>'; 
echo'</tr>'; 
    
}
echo'</table>';
?>
