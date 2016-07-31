<?php

$PageSecurity = 80;
include('includes/session.inc');
$PaperSize ='A4';
include('includes/PDFStarter.php');
if ($_GET['orderno']!="") {
    
    
    $sql = "SELECT purchorders.supplierno, 
    suppliers.suppname,
 suppliers.address1,
 suppliers.address2,
  suppliers.address3, 
suppliers.address4,
stockmaster.units,
purchorderdetails.unitprice,
purchorderdetails.quantityord,
purchorders.orddate, 
locations.deladd1,
locations.deladd2, 
locations.deladd3,
 locations.deladd4,
 purchorders.dateprinted,  
suppliers.currcode,
purchorders.status, 
purchorders.stat_comment ,
  purchorderdetails.itemdescription,
  purchorderdetails.deliverydate,
  purchorders.orderno,
locations.locationname
FROM purchorders 
INNER JOIN suppliers ON purchorders.supplierno = suppliers.supplierid 
inner join locations on purchorders.intostocklocation =locations.loccode 
inner join purchorderdetails on purchorderdetails.orderno='" . $_GET['orderno'] ."'
inner join stockmaster on purchorderdetails.itemcode=stockmaster.stockid
WHERE purchorders.orderno='" . $_GET['orderno'] ."'";
                
$result=DB_query($sql,$db, $ErrMsg);
$row=DB_fetch_row($result);
       
    $pdf->addInfo('Title',_('Purchage Report'));
    $pdf->addInfo('Subject',_('Purchage '));
$FontSize=9;
$line_height=10;
    $tab=100;
$Xpos =$Left_Margin+1;
$YPos= $Page_Height-$Top_Margin-40; 

 //------------------------Start of heard--------------------\\
$img2='companies/'.$_SESSION['DatabaseName'].'/PNG/B ONLY.png';
    $pdf->addJpegFromFile($img2,$Xpos,$YPos,0,40);
    $order= $_GET['orderno'];
    //$heardtitile="Purchase Order Number ".$order."";
    
//$pdf->addText( $Page_Width/2-100,$YPos+30,$FontSize+3,$heardtitile);
$pdf->addTextWrap($Page_Width/2-100,$YPos+30,120,$FontSize+3,'Purchase Order Number '.$order.'','center'); 
$pdf->addTextWrap($Page_Width/2-100,$YPos+15,120,$FontSize+3,'Date :'.ConvertSQLDate($row[9]),'center'); 
$img1='companies/'.$_SESSION['DatabaseName'].'/PNG/logo PNG.png';
    $pdf->addJpegFromFile($img1,$Xpos+($tab*3),$YPos,0,55);
$pdf->line($Left_Margin,$YPos,$Page_Width-$Right_Margin,$YPos); 
 
 // $pdf->addTextWrap($Left_Margin+100,$YPos-65,60,$FontSize+3,_('Delivery Note'),'center'); 
   // $pdf->addTextWrap($Left_Margin,$YPos-82,90,$FontSize,_('No :'), 'left'); 
  //  $pdf->addTextWrap($Left_Margin+170,$YPos-82,90,$FontSize,_('Date :'), 'left');  
//PrintHeader($pdf,$YPos,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,$Right_Margin,$tab,$line_height);   
//PrintHeader();.

 
 //------------------------End of heard--------------------\\
 

 
//------------------------Start of BODY--------------------\\
   
$pdf->RoundRectangle($Left_Margin,$YPos-10,250,60,3);
$pdf->addTextWrap($Left_Margin+10,$YPos-20,30,$FontSize,_('Order To'),'left');
$pdf->addTextWrap($Left_Margin+10+$Left_Margin,$YPos-($line_height*2)-10,100,$FontSize,$row[1],'left');
$pdf->addTextWrap($Left_Margin+10+$Left_Margin,$YPos-($line_height*3)-10,100,$FontSize,$row[2],'left');
$pdf->addTextWrap($Left_Margin+10+$Left_Margin,$YPos-($line_height*4)-10,100,$FontSize,$row[3],'left');
$pdf->addTextWrap($Left_Margin+10+$Left_Margin,$YPos-($line_height*5)-10,100,$FontSize,$row[4],'left');
$pdf->RoundRectangle(($Page_Width/2),$YPos-10,250,60,3);  
$pdf->addTextWrap(($Page_Width/2)+10,$YPos-20,30,$FontSize,_('Deliver To'),'left');
$pdf->addTextWrap(($Page_Width/2)+10+$Left_Margin,$YPos-($line_height*2)-10,100,$FontSize,$row[21],'left');
$pdf->addTextWrap(($Page_Width/2)+10+$Left_Margin,$YPos-($line_height*3)-10,100,$FontSize,$row[10],'left');
$pdf->addTextWrap(($Page_Width/2)+10+$Left_Margin,$YPos-($line_height*4)-10,100,$FontSize,$row[11],'left');
$pdf->addTextWrap(($Page_Width/2)+10+$Left_Margin,$YPos-($line_height*5)-10,100,$FontSize,$row[12],'left');


//Code        Item Description             Quantity  Unit   Date Reqd         Price 
$pdf->Rectangle($Left_Margin,$YPos-80,$Page_Width-($Left_Margin*2),140);

$pdf->line($Left_Margin,$YPos-110,$Page_Width-$Right_Margin-10,$YPos-110);

$pdf->addTextWrap($Left_Margin,$YPos-95,30,$FontSize,_('Slno'),'center');
$pdf->addTextWrap($Left_Margin,$YPos-150,30,$FontSize,1,'center');
$pdf->line($Left_Margin+40,$YPos-80,$Left_Margin+40,$YPos-220);
$pdf->addTextWrap($Left_Margin+40,$YPos-95,100,$FontSize,_('Item Description'),'center');
$pdf->addTextWrap($Left_Margin+45,$YPos-150,100,$FontSize,$row[18],'left');
$pdf->line($Left_Margin+250,$YPos-80,$Left_Margin+250,$YPos-220);
$pdf->addTextWrap($Left_Margin+250,$YPos-95,50,$FontSize,_('Quantity'),'center');
$pdf->addTextWrap($Left_Margin+250,$YPos-150,50,$FontSize,$row[8],'center');
$pdf->line($Left_Margin+300,$YPos-80,$Left_Margin+300,$YPos-220);
$pdf->addTextWrap($Left_Margin+300,$YPos-95,30,$FontSize,_('Unit'),'center');
$pdf->addTextWrap($Left_Margin+300,$YPos-150,30,$FontSize,$row[6],'center');
$pdf->line($Left_Margin+330,$YPos-80,$Left_Margin+330,$YPos-220);
$pdf->addTextWrap($Left_Margin+330,$YPos-95,50,$FontSize,_('Date Reqd '),'center');
$pdf->addTextWrap($Left_Margin+330,$YPos-150,50,$FontSize,$row[19],'center');
$pdf->line($Left_Margin+400,$YPos-80,$Left_Margin+400,$YPos-220);
$pdf->addTextWrap($Left_Margin+400,$YPos-95,40,$FontSize,_('Unit price'),'center');
$pdf->addTextWrap($Left_Margin+400,$YPos-150,30,$FontSize,$row[7],'center');
$pdf->line($Left_Margin+450,$YPos-80,$Left_Margin+450,$YPos-220);
$pdf->addTextWrap($Left_Margin+450,$YPos-95,60,$FontSize,_('Total Amount'),'center');
$pdf->addTextWrap($Left_Margin+450,$YPos-150,60,$FontSize,$row[7]*$row[8],'center');
  
//------------------------End of BODY--------------------\\


//------------------------Start of FOOTER--------------------\\
        //    function addTextWrap($x, $yb, $w, $h, $txt, $align='J', $border=0, $fill=0) {                                      
    $pdf->addTextWrap($Left_Margin+370,$YPos-250,60,$FontSize,_('Authorised signature'),'left'); 
     $pdf->addTextWrap($Left_Margin,$YPos-250,60,$FontSize,_('Signature of supplier'),'left');

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


$sql = "UPDATE purchorders    SET    allowprint =  0,
                                        dateprinted  = '" . Date('Y-m-d') . "',
                                        status = 'Printed',
                                        
                WHERE purchorders.orderno = '" .$order."'";
        $result = DB_query($sql,$db);
}
?>

