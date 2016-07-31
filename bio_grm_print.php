<?php

$PageSecurity = 80;
include('includes/session.inc');
$PaperSize ='A4';
include('includes/PDFStarter.php');

if (isset($_GET['orderno']) and $_GET['orderno']!="") {   
$sql = "SELECT 
   grns.grnbatch,
   grns.itemdescription,
   stockmaster.units,
purchorderdetails.quantityord,
grns.qtyrecd,
purchorderdetails.quantityrecd,
grns.deliverydate
FROM purchorderdetails
inner join grns on grns.podetailitem=purchorderdetails.podetailitem
left join stockmaster on grns.itemcode=stockmaster.stockid

where purchorderdetails.orderno='" . $_GET['orderno'] ."' AND  grns.grnbatch='" . $_GET['grnbatch'] ."'";
                
$result=DB_query($sql,$db, $ErrMsg);
$row=DB_fetch_row($result);
$sql3="SELECT `recieveby` , `varifyby` , `acceptby`
FROM `bio_grn_invoice`
WHERE `grnbatch` ='" . $_GET['grnbatch'] ."'";
$result3=DB_query($sql3,$db, $ErrMsg);
$sup3=DB_fetch_row($result3);

 $sql2="select suppliers.suppname,suppliers.address1,suppliers.address2,suppliers.address3,suppliers.address4,locations.locationname from purchorders 
inner join suppliers on purchorders.supplierno=suppliers.supplierid
inner join locations on locations.loccode=purchorders.intostocklocation
where purchorders.orderno='" . $_GET['orderno'] ."'";
$result2=DB_query($sql2,$db, $ErrMsg);
$sup=DB_fetch_row($result2);
       
    $pdf->addInfo('Title',_('GRM Report'));
    $pdf->addInfo('Subject',_('GRM '));
$FontSize=9;
$line_height=10;
    $tab=100;
$Xpos =$Left_Margin+1;
$YPos= $Page_Height-$Top_Margin-80; 
 //------------------------Start of heard--------------------\\
$img2='companies/'.$_SESSION['DatabaseName'].'/PNG/B ONLY.png';
    $pdf->addJpegFromFile($img2,$Xpos,$YPos+40,0,40);
    $order= $_GET['orderno'];

    
//$pdf->addText( $Page_Width/2-100,$YPos+30,$FontSize+3,$heardtitile);
$pdf->addTextWrap($Page_Width/2-75,$YPos+20,120,$FontSize+3,'Goods Reciepts Note','center'); 
//$pdf->addTextWrap($Page_Width/2-100,$YPos+15,120,$FontSize+3,'Date :'.ConvertSQLDate($row[9]),'center'); 
$img1='companies/'.$_SESSION['DatabaseName'].'/PNG/logo PNG.png';
    $pdf->addJpegFromFile($img1,$Xpos+($tab*3),$YPos+40,0,55);
$pdf->line($Left_Margin,$YPos+40,$Page_Width-$Right_Margin,$YPos+40); 
 
 // $pdf->addTextWrap($Left_Margin+100,$YPos-65,60,$FontSize+3,_('Delivery Note'),'center'); 
   // $pdf->addTextWrap($Left_Margin,$YPos-82,90,$FontSize,_('No :'), 'left'); 
  //  $pdf->addTextWrap($Left_Margin+170,$YPos-82,90,$FontSize,_('Date :'), 'left');  
//PrintHeader($pdf,$YPos,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,$Right_Margin,$tab,$line_height);   
//PrintHeader();.

 
 //------------------------End of heard--------------------\\
 

 
//------------------------Start of BODY--------------------\\
   
$pdf->RoundRectangle($Left_Margin,$YPos-10,250,60,3);
//$pdf->addTextWrap($Left_Margin+10,$YPos-20,30,$FontSize,_(''),'left');
$pdf->addTextWrap($Left_Margin+10+$Left_Margin,$YPos-($line_height*2)-10,100,$FontSize,$sup[0],'left');
$pdf->addTextWrap($Left_Margin+10+$Left_Margin,$YPos-($line_height*3)-10,100,$FontSize,$sup[1],'left');
$pdf->addTextWrap($Left_Margin+10+$Left_Margin,$YPos-($line_height*4)-10,100,$FontSize,$sup[2],'left');
$pdf->addTextWrap($Left_Margin+10+$Left_Margin,$YPos-($line_height*5)-10,100,$FontSize,$sup[3],'left');
$pdf->RoundRectangle(($Page_Width/2),$YPos-10,250,60,3);  
//$pdf->addTextWrap(($Page_Width/2)+10,$YPos-20,30,$FontSize,_(''),'left');
  $order= $_GET['orderno'];
  $grnno= $row[0];
   $lo=$sup[5];
$pdf->addTextWrap(($Page_Width/2)+10+$Left_Margin,$YPos-($line_height*2)-10,50,$FontSize,'Location','left');
$pdf->addTextWrap(($Page_Width/2)+80+$Left_Margin,$YPos-($line_height*2)-10,100,$FontSize,': '.$lo,'left');
$pdf->addTextWrap(($Page_Width/2)+10+$Left_Margin,$YPos-($line_height*3)-10,30,$FontSize,'GRN Number ','left');
$pdf->addTextWrap(($Page_Width/2)+80+$Left_Margin,$YPos-($line_height*3)-10,40,$FontSize,': '.$grnno.'','left');
$pdf->addTextWrap(($Page_Width/2)+10+$Left_Margin,$YPos-($line_height*4)-10,30,$FontSize,'PO Number ','left');
$pdf->addTextWrap(($Page_Width/2)+80+$Left_Margin,$YPos-($line_height*4)-10,40,$FontSize,': '.$order.'','left');
$pdf->addTextWrap(($Page_Width/2)+10+$Left_Margin,$YPos-($line_height*5)-10,30,$FontSize,'Date ','left');
$pdf->addTextWrap(($Page_Width/2)+80+$Left_Margin,$YPos-($line_height*5)-10,80,$FontSize,': '.ConvertSQLDate($row[6]),'left');


//Code        Item Description             Quantity  Unit   Date Reqd         Price 
$pdf->Rectangle($Left_Margin,$YPos-80,$Page_Width-($Left_Margin*2),140);

$pdf->line($Left_Margin,$YPos-110,$Page_Width-$Right_Margin-10,$YPos-110);

$pdf->addTextWrap($Left_Margin,$YPos-95,30,$FontSize,_('Slno'),'center');
$pdf->addTextWrap($Left_Margin,$YPos-150,30,$FontSize,1,'center');
$pdf->line($Left_Margin+40,$YPos-80,$Left_Margin+40,$YPos-220);
$pdf->addTextWrap($Left_Margin+40,$YPos-95,100,$FontSize,_('Item Description'),'center');
$pdf->addTextWrap($Left_Margin+45,$YPos-150,100,$FontSize,$row[1],'left');
$pdf->line($Left_Margin+250,$YPos-80,$Left_Margin+250,$YPos-220);
$pdf->addTextWrap($Left_Margin+250,$YPos-95,30,$FontSize,_('Unit'),'center');
$pdf->addTextWrap($Left_Margin+250,$YPos-150,30,$FontSize,$row[2],'center');
$pdf->line($Left_Margin+280,$YPos-80,$Left_Margin+280,$YPos-220);
$pdf->addTextWrap($Left_Margin+280,$YPos-95,50,$FontSize,_('Qty ordered'),'center');
$pdf->addTextWrap($Left_Margin+300,$YPos-150,30,$FontSize,$row[3],'center');
$pdf->line($Left_Margin+330,$YPos-80,$Left_Margin+330,$YPos-220);
$pdf->addTextWrap($Left_Margin+330,$YPos-95,50,$FontSize,_('Recieved '),'center');
$pdf->addTextWrap($Left_Margin+330,$YPos-150,50,$FontSize,$row[4],'center');
$pdf->line($Left_Margin+380,$YPos-80,$Left_Margin+380,$YPos-220);
$pdf->addTextWrap($Left_Margin+380,$YPos-95,70,$FontSize,_('Already recieved'),'center');
$pdf->addTextWrap($Left_Margin+400,$YPos-150,30,$FontSize,$row[5],'center');
$pdf->line($Left_Margin+450,$YPos-80,$Left_Margin+450,$YPos-220);
$pdf->addTextWrap($Left_Margin+450,$YPos-95,60,$FontSize,_('Balance'),'center');
$pdf->addTextWrap($Left_Margin+450,$YPos-150,60,$FontSize,$row[3]-$row[5],'center');
  
//------------------------End of BODY--------------------\\


//------------------------Start of FOOTER--------------------\\
        //    function addTextWrap($x, $yb, $w, $h, $txt, $align='J', $border=0, $fill=0) { 
$pdf->addTextWrap($Left_Margin+30,$YPos-250,60,$FontSize,'Approved by '.$sup3[0],'left');     
//$pdf->addTextWrap($Left_Margin+75,$YPos-250,60,$FontSize,$sup3[0],'left');   
$pdf->addTextWrap($Page_Width/2-30,$YPos-250,60,$FontSize,'Verified by '.$sup3[1],'center');  
//$pdf->addTextWrap($Page_Width/2+15,$YPos-250,60,$FontSize,,'center');                                 
$pdf->addTextWrap($Left_Margin+400,$YPos-250,60,$FontSize,'Accepted by '.$sup3[2],'left'); 
//$pdf->addTextWrap($Left_Margin+450,$YPos-250,60,$FontSize,,'left'); 



$img3='companies/'.$_SESSION['DatabaseName'].'/PNG/FOOTER.png';
$pdf->addJpegFromFile($img3,$Left_Margin,($Page_Height/2)+$line_height,$Page_Width-($Right_Margin*3),$line_height*3);
   
   
//------------------------End of FOOTER--------------------\\

//end line off page
$pdf->line($Left_Margin,$Page_Height/2,$Page_Width-$Right_Margin,$Page_Height/2); 
$pdf->OutputD($_SESSION['DatabaseName'] . 'Purchage' . Date('Y-m-d') . '.pdf');
  $pdf->__destruct();
  
  
/*function PrintHeader(&$pdf,&$YPos,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,$Right_Margin,$tab,$line_height)
{
    $YPos= $Page_Height-$Top_Margin-40; 
$img2='companies/'.$_SESSION['DatabaseName'].'/PNG/B ONLY.png';
    $pdf->addJpegFromFile($img2,$Xpos,$YPos,0,40);
$img1='companies/'.$_SESSION['DatabaseName'].'/PNG/logo PNG.png';
    $pdf->addJpegFromFile($img1,$Xpos+($tab*3),$YPos,0,55);
$pdf->line($Left_Margin,$YPos,$Page_Width-$Right_Margin,$YPos); 
$pdf->line($Left_Margin,$Page_Height/2,$Page_Width-$Right_Margin,$Page_Height/2);  
}*/

 }
?>

