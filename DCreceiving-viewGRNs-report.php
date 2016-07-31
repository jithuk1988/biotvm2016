<?php
$PageSecurity = 11; 
include('includes/session.inc');

$supplier=$_GET['supplier']; 
$item=$_GET['item'];  

$sqlitm="SELECT description,
                units
         FROM stockmaster
         WHERE stockid='$item'";
$resultitm=DB_query($sqlitm,$db);
$myrowitm=DB_fetch_array($resultitm);

$sqlsup="SELECT suppname
         FROM suppliers
         WHERE supplierid=$supplier";
$resultsup=DB_query($sqlsup,$db);
$myrowsup=DB_fetch_array($resultsup);

    if (!headers_sent()){
        header('Content-type: text/html; charset=' . _('utf-8'));
    }                                                                                                                  
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';


    echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>' . $title . '</title>';
    echo '<link rel="shortcut icon" href="'. $rootpath.'/favicon.ico" />';
    echo '<link rel="icon" href="' . $rootpath.'/favicon.ico" />';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _('utf-8') . '" />';
    echo '<link href="'.$rootpath. '/css/'. $_SESSION['Theme'] .'/default.css" rel="stylesheet" type="text/css" />';   
    echo '<script type="text/javascript" src = "'.$rootpath.'/javascripts/MiscFunctions.js"></script>'; 
    echo '<script type="text/javascript" src = "'.$rootpath.'/javascripts/jquery.js"></script>';
    
    echo '</head>';

    echo '<body>';
    
echo'<br />';

echo '<font size=5><p align=center>STOCK RECEIPT DETAILS</p></font>';
echo '<h3 align=center>' . '<b>Item Name : '.$myrowitm[0].'</b>( ' . _($myrowitm[1]) . ' )</h3>';

echo '<h3 align=center>' . '<b>Supplier : '.$myrowsup[0].'</b></h3>';


echo '<table width=90% BORDER=1>';
$tableheader = "<tr>
        <th>" . _('Sl No') . "</th>                                                                           
        <th>" . _('Date') . "</th>
        <th>" . _('Receipt No') . "</th>
        <th>" . _('Quantity') . "</th>
        <th>" . _('Signature') . "</th>
        <th>" . _('Remarks') . "</th>
        </tr>";

echo $tableheader;

    



$sql3="SELECT grns.grnno,
             grns.deliverydate,
             grns.qtyrecd,
             stockmoves.stkmoveno
      FROM grns,stockmoves
      WHERE grns.itemcode='$item'       AND
            grns.supplierid=$supplier    AND
            stockmoves.type=25      AND
            grns.itemcode=stockmoves.stockid        AND
            grns.grnno=stockmoves.transno"; 
$result3=DB_query($sql3,$db);

$j = 1;
$k=0; //row colour counter
$slno2=0;
$value=0;

$totalqty=0;

while($myrow3=DB_fetch_array($result3) )        {

if($slno2==0)       {
    
$startmoveno=$myrow[2];    
}  

if($value==20)       {
    echo'</table>';
    echo '<table width=90% BORDER=0>';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr border=0>
<td width=35%>Signature of</td>
<td width=35%>Signature of</td>
<td width=35%>Signature of</td>
</tr>';


echo'<tr border=0><td><br /><br />Store Supt</td>
<td><br /><br />AEO</td>
<td><br /><br />EO</td>

</tr>';
echo'</table>';

echo '<font size=5>
<p align=center style="page-break-before: always">STOCK RECEIPT DETAILS</p>
</font>';
echo '<h3 align=center>' . '<b>Item Name : '.$myrowitm[0].'</b>( ' . _($myrowitm[1]) . ' )</h3>';

echo '<h3 align=center>' . '<b>Supplier : '.$myrowsup[0].'</b></h3>';

echo '<table width=90% BORDER=1 >';
$tableheader = "<tr>
        <th>" . _('Sl No') . "</th>                                                                           
        <th>" . _('Date') . "</th>
        <th>" . _('Receipt No') . "</th>
        <th>" . _('Quantity') . "</th>
        <th>" . _('Signature') . "</th>
        <th>" . _('Remarks') . "</th>
        </tr>";

echo $tableheader;
    $value=0;
}
$value++; 
    $slno2++;
    
    $Issueddate=$myrow3[1];
    $GRNno=$myrow3[0];
    $quantity=$myrow3[2];
    
    $totalqty=$totalqty+$quantity;

    
        printf("<td align=center>%s</td>
        <td align=center>%s</td>
        <td align=center>%s</td>
        <td align=center>%s</td>
        <td align=center>%s</td>
        <td align=center>%s</td>
        </tr>",
        $slno2,
        $Issueddate,
        $GRNno,
        $quantity,
        $signature,
        $remarks);    
    
$wono=$myrow3[0];


}

echo'<tr>
        <td colspan=3 align=center>Total</td>
        <td align=center>'.$totalqty.'</td>
        <td align=center></td>
        <td align=center></td>
</tr>';

    echo'</table>';
    echo '<table width=90% BORDER=0>';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr border=0>
<td width=35%>Signature of</td>
<td width=35%>Signature of</td>
<td width=35%>Signature of</td>
</tr>';


echo'<tr border=0><td><br /><br />Store Supt</td>
<td><br /><br />AEO</td>
<td><br /><br />EO</td>

</tr>';
echo'</table>';

?>
