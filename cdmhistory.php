<?php
    $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('Survey History');
include ('includes/header.inc'); 
$debtorno=$_GET['debtorno'];
$sql="select surveyno,date_format(`currdate`,'%d-%m-%Y') as 'srdate',`vpano`, `firewood`, `lpg`, `grid`, `invoiceno`, date_format(`invoicedate`,'%d-%m-%Y') as 'invoicedate' from bio_present_fuel where debtorno='".$debtorno."'";
$res=DB_query($sql,$db);
echo "<table><tr><th>Survey No</th><th>Survey Date</th><th>Firewood</th><th>LPG</th><th>Grid Power</th><th>Invoice No</th><th>Invoice Date</th></tr>";
while($mr=DB_fetch_array($res))
{
    echo "<tr>";
    echo "<td>".$mr[0]."</td>".$mr[1]."</td>".$mr[3]."</td>".$mr[4]."</td>".$mr[5]."</td>".$mr[6]."</td>".$mr[7]."</td>";
echo "</tr>";
}
echo"</table>";
?>
