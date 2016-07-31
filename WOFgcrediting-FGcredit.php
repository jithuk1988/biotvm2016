<?php
$PageSecurity = 5;

include('includes/session.inc');

include('includes/PDFStarter.php');

$FontSize=8;
$pdf->addinfo('Title', _('FG Credit Note') );

$PageNumber=1;
$line_height=12;


include('includes/PDFFgcHeader.inc');

$FontSize =8;
/*Print out the category totals */
//$pono=$_GET['PONo'];
$transno=$_GET['id'];  

       $sql='SELECT stockmoves.trandate,
                    stockmoves.qty,
                    stockmoves.reference,
                    woitems.qtyreqd,
                    woitems.qtyrecd,
                    stockmaster.description,
                    stockmaster.longdescription
             FROM   stockmoves,woitems,stockmaster
             WHERE stockmoves.type=26       AND
                   stockmoves.transno='.$transno.'      AND
                   stockmoves.reference=woitems.wo      AND
                   stockmoves.stockid=stockmaster.stockid';
$result=DB_query($sql, $db);
$counter=1;
while ($myrow=DB_fetch_array($result)) {
    $StockID=$myrow[0];
    $Itemname=$myrow[6];
    $wono=$myrow[2];
    $Quantity=$myrow[1];
    $woqty=$myrow[3];
    $woreceived=$myrow[4];
    $balance=$woqty-$woreceived;
    
    $LeftOvers = $pdf->addTextWrap($Left_Margin+1,$YPos-(10*$counter),70,$FontSize, 1);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+75,$YPos-(10*$counter),175,$FontSize, $Itemname);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+228,$YPos-(10*$counter),230-$Left_Margin,$FontSize, $wono);

    $LeftOvers = $pdf->addTextWrap($Left_Margin+280,$YPos-(10*$counter),310-$Left_Margin,$FontSize, $Quantity);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+350,$YPos-(10*$counter),400-$Left_Margin,$FontSize, $woqty);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+390,$YPos-(10*$counter),430-$Left_Margin,$FontSize, $woreceived);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos-(10*$counter),470-$Left_Margin,$FontSize, $balance);
    
    $counter = $counter + 1;
    break;
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
    header('Content-Disposition: inline; filename=FGCredit.pdf');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    $pdf->Stream();
}


 /*end of else not PrintPDF */
?>


?>
