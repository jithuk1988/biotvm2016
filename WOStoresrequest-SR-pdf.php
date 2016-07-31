<?php
$PageSecurity = 11; 
include('includes/session.inc');

include('includes/PDFStarter.php');



$pdf->addinfo('Title', _('Goods Received Note') );
$pdf->setFont('','');

$FontSize=12;
$PageNumber=1;
$line_height=12;

$srno=$_GET['id'];

$sql9="SELECT womaterialrequest.reqdate
       FROM womaterialrequest
       WHERE womaterialrequest.reqno=$srno";
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);



$issuedate1=split('-',$myrow9['reqdate']); 
$month=$issuedate1[1];
$day=$issuedate1[2];
$year=$issuedate1[0];

$issuedate=$day.'-'.$month.'-'.$year;

//$indentdate=split('-',$myrow9[3]);
//$month2=$indentdate[1];
//$day2=$indentdate[2];
//$year2=$indentdate[0];

//$indentdate=$day2.'-'.$month2.'-'.$year2;


include('includes/PDFSinHeader.inc');
 


$sql='SELECT womaterialrequest.wono,
             womaterialrequest.statusid,
             womaterialrequest.loccode,
             womaterialrequest.reqty,
             womaterialrequest.srtype,
             womaterialrequestdetails.qtyrequest,
             womaterialrequestdetails.qtyissued,
             stockmaster.longdescription,
             stockmaster.units,
             woitems.stockid
             FROM womaterialrequest,womaterialrequestdetails,stockmaster,woitems 
             WHERE womaterialrequest.reqno='.$srno.'      AND
                   womaterialrequest.reqno=womaterialrequestdetails.reqno       AND
                   womaterialrequestdetails.stockid=stockmaster.stockid     AND
                   womaterialrequest.wono=woitems.wo';
                   
$result=DB_query($sql, $db);

$slno=0;
$counter=0;

 
$LeftOvers = $pdf->addTextWrap($Left_Margin+20,$YPos+95,300,$FontSize, 'STORES REQUEST NOTE','center');
$LeftOvers = $pdf->addTextWrap($Left_Margin+230,$YPos+65,100-$Left_Margin,$FontSize, $issuedate,'right');
$LeftOvers = $pdf->addTextWrap($Left_Margin+80,$YPos+37,85,$FontSize, $srno);

$LeftOvers = $pdf->addTextWrap($Left_Margin+340,$YPos+95,300,$FontSize, 'STORES REQUEST NOTE','center');
$LeftOvers = $pdf->addTextWrap($Left_Margin+540,$YPos+65,95-$Left_Margin,$FontSize, $issuedate,'right');
$LeftOvers = $pdf->addTextWrap($Left_Margin+390,$YPos+37,85,$FontSize, $srno);

$LeftOvers = $pdf->addTextWrap($Left_Margin+640,$YPos+95,300,$FontSize, 'STORES REQUEST NOTE','center');
$LeftOvers = $pdf->addTextWrap($Left_Margin+865,$YPos+65,82-$Left_Margin,$FontSize, $issuedate,'right');
$LeftOvers = $pdf->addTextWrap($Left_Margin+695,$YPos+37,85,$FontSize, $srno);


while($myrow=DB_fetch_array($result))       {
    
    
    $Itemname=$myrow[7];
    $Quantity=$myrow['qtyrequest'];
    $Units=$myrow[8];
    
    
    
    $slno++; 

    $FGitem=$myrow[9];
    

    $LeftOvers = $pdf->addTextWrap($Left_Margin+55,$YPos-(10*$counter+10),60,$FontSize, $slno);
    
    $LeftOvers = $pdf->addTextWrap($Left_Margin+85,$YPos-(10*$counter+10),210,$FontSize, $Itemname);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+215,$YPos-(10*$counter+10),220-$Left_Margin,$FontSize, $Units);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+230,$YPos-(10*$counter+10),100-$Left_Margin,$FontSize, $Quantity,'right');
    
    $LeftOvers = $pdf->addTextWrap($Left_Margin+365,$YPos-(10*$counter+10),510,$FontSize, $slno);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+395,$YPos-(10*$counter+10),210,$FontSize, $Itemname);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+520,$YPos-(10*$counter+10),560-$Left_Margin,$FontSize, $Units);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+540,$YPos-(10*$counter+10),95-$Left_Margin,$FontSize, $Quantity,'right');
    
    $LeftOvers = $pdf->addTextWrap($Left_Margin+670,$YPos-(10*$counter+10),790,$FontSize, $slno);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+700,$YPos-(10*$counter+10),210,$FontSize, $Itemname);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+830,$YPos-(10*$counter+10),560-$Left_Margin,$FontSize, $Units);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+865,$YPos-(10*$counter+10),80-$Left_Margin,$FontSize, $Quantity,'right');
    

    

    
    $counter = $counter + 1.5;

}

    $sql45="SELECT longdescription
          FROM stockmaster
          WHERE stockid='$FGitem'";
    $result45=DB_query($sql45,$db);
    $myrow45=DB_fetch_array($result45);
    
    $FGitemname=$myrow45[0];

    $LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos-290,300,$FontSize, 'Sp. officer &nbsp;'.$FGitemname.'','left');
    $LeftOvers = $pdf->addTextWrap($Left_Margin+360,$YPos-290,300,$FontSize, 'Sp. officer &nbsp;'.$FGitemname.'','left');
    $LeftOvers = $pdf->addTextWrap($Left_Margin+665,$YPos-290,300,$FontSize, 'Sp. officer &nbsp;'.$FGitemname.'','left');

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
  
?>
