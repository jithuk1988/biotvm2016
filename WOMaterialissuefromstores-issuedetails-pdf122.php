<?php
$PageSecurity = 11; 
include('includes/session.inc');

include('includes/PDFStarter.php');



$pdf->addinfo('Title', _('Goods Received Note') );
$pdf->setFont('','');

$FontSize=8;
$PageNumber=1;
$line_height=12;

$mino=$_GET['id']; 

$sql7="SELECT m_season.start_malyear,
              m_sub_season.seasonname     
       FROM m_season,m_sub_season
       WHERE m_season.is_current=1      AND
             m_season.season_sub_id=m_sub_season.season_sub_id";
$result7=DB_query($sql7,$db);
$myrow7=DB_fetch_array($result7);
$malyear=$myrow7[0];
$seasonname=$myrow7[1];

$sql9="SELECT dev_materialissue.srno,
              dev_materialissue.date,
              womaterialrequest.wono,
              womaterialrequest.reqdate,
              woitems.stockid,
              stockmaster.longdescription
       FROM dev_materialissue,womaterialrequest,woitems,stockmaster
       WHERE dev_materialissue.mino=$mino       AND
             dev_materialissue.srno=womaterialrequest.reqno      AND
             womaterialrequest.wono=woitems.wo      AND
             woitems.stockid=stockmaster.stockid";
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);
$srno=$myrow9[0];

$item=$myrow9[5];

$issuedate=split('/',$myrow9[1]); 
$month=$issuedate[1];
$day=$issuedate[2];
$year=$issuedate[0];

$issuedate=$day.'-'.$month.'-'.$year;

$indentdate=split('-',$myrow9[3]);
$month2=$indentdate[1];
$day2=$indentdate[2];
$year2=$indentdate[0];

$indentdate=$day2.'-'.$month2.'-'.$year2;


include('includes/PDFSinHeader.inc');
 

$sql="SELECT dev_materialissuedetails.stockid,     
             dev_materialissuedetails.quantity,
             stockmoves.trandate,
             stockmaster.description,
             stockmaster.longdescription,
             stockmaster.units,
             locations.locationname 
      FROM dev_materialissuedetails,stockmoves,stockmaster,locations
      WHERE dev_materialissuedetails.mino=$mino      AND
            dev_materialissuedetails.stockmoveno=stockmoves.stkmoveno   AND
            dev_materialissuedetails.stockid=stockmaster.stockid        AND
            stockmoves.loccode=locations.loccode";
$result=DB_query($sql,$db);

$slno=0;
$counter=0;
while($myrow=DB_fetch_array($result))       {
$slno++; 

    $Itemname=$myrow['longdescription'];                                                                                
    $Quantity=$myrow['quantity'];
    $Units=$myrow['units']; 
    

    $LeftOvers = $pdf->addTextWrap($Left_Margin+1,$YPos-(10*$counter+10),80,$FontSize, $slno);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+75,$YPos-(10*$counter+10),175,$FontSize, $Itemname);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+380,$YPos-(10*$counter+10),330-$Left_Margin,$FontSize, $Units);
    $LeftOvers = $pdf->addTextWrap($Left_Margin+480,$YPos-(10*$counter+10),85-$Left_Margin,$FontSize, $Quantity,'right');
    

    

    
    $counter = $counter + 1;

}



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
