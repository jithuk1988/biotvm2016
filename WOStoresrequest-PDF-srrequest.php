<?php
/* $Revision: 1.4 $ */

$PageSecurity = 2;
include('includes/session.inc');


include('includes/PDFStarter.php');

$FontSize=8;
$pdf->addinfo('Title', _('Stores Request note') );

$PageNumber=1;
$line_height=12;


include('includes/PDFWosrnHeader.inc');

$FontSize =8;
/*Print out the category totals */
//$pono=$_GET['PONo'];
$srno=$_GET['id'];
$sql='SELECT womaterialrequest.wono,
             womaterialrequest.statusid,
             womaterialrequest.loccode,
             womaterialrequest.reqty,
             womaterialrequest.srtype,
             womaterialrequestdetails.qtyrequest,
             womaterialrequestdetails.qtyissued,
             stockmaster.description,
             stockmaster.units
             FROM womaterialrequest,womaterialrequestdetails,stockmaster 
             WHERE womaterialrequest.reqno='.$srno.'      AND
                   womaterialrequest.reqno=womaterialrequestdetails.reqno       AND
                   womaterialrequestdetails.stockid=stockmaster.stockid';
$result=DB_query($sql, $db);
$counter=1;
$slno=0;
while ($myrow=DB_fetch_array($result)) {
    $slno++;
    $Itemname=$myrow[7];
    $Quantity=$myrow[1];
    $units=$myrow[8];
    $requested=$myrow[5];
    $issued=$myrow[6];
    $ordered=$myrow[6];
    $porecieved=$myrow[7];
    $Suppliername=$myrow[8];
    $address1=$myrow[9];
    $address2=$myrow[10];
    $Itemname=$myrow[15];
    
    $balance=$requested-($issued);
    $LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos+(70),550,$FontSize3, $slno);
    $LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos+(60),550,$FontSize3, $address1);
    $LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos+(50),550,$FontSize3, $address2);
    
    $LeftOvers = $pdf->addTextWrap($Left_Margin+1,$YPos-(10*$counter),70,$FontSize, $slno);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+75,$YPos-(10*$counter),175,$FontSize, $Itemname);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+208,$YPos-(10*$counter),230-$Left_Margin,$FontSize, $units);

    $LeftOvers = $pdf->addTextWrap($Left_Margin+280,$YPos-(10*$counter),310-$Left_Margin,$FontSize, $requested);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos-(10*$counter),400-$Left_Margin,$FontSize, $issued);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+390,$YPos-(10*$counter),430-$Left_Margin,$FontSize, $balance);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos-(10*$counter),470-$Left_Margin,$FontSize, '');
    $LeftOvers = $pdf->addTextWrap($Left_Margin+470,$YPos-(10*$counter),510-$Left_Margin,$FontSize, $eda);
    
    $counter = $counter + 1;

}

//$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos-(10*$counter+80),300-$Left_Margin,$FontSize, _('Date of Receipt: ').$Date);

$LeftOvers = $pdf->addTextWrap(360+$Left_Margin,$YPos-(10*$counter+140),300-$Left_Margin,$FontSize, _('Signature of Special Officer for Store '));

$pdfcode = $pdf->output();
$len = strlen($pdfcode);

if ($len<=20){
    $title = _('Print Price List Error');
    include('includes/header.inc');
    prnMsg(_('There were no stock transfer details to print'),'warn');
    echo '<br><a href="'.$rootpath.'/index.php?' . SID . '">'. _('Back to the menu').'</a>';
    include('includes/footer.inc');
    exit;
} else {
    header('Content-type: application/pdf');
    header('Content-Length: ' . $len);
    header('Content-Disposition: inline; filename=GRN.pdf');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    $pdf->Stream();
}


 /*end of else not PrintPDF */
?>
