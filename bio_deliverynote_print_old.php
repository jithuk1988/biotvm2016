<?php

$PageSecurity = 80;
/* $Id: InventoryQuantities.php 4555 2011-04-19 10:18:49Z daintree $ */

// InventoryQuantities.php - Report of parts with quantity. Sorts by part and shows
// all locations where there are quantities of the part

include('includes/session.inc');
/*if($_GET['adv_id']==""){
$_GET['adv_id']=$_SESSION['adv_id'];

unset($_SESSION['adv_id']);
} */

$PaperSize ='A4';

if ($_GET['orderno']!="") {

    $orderno=$_GET['orderno'];
    $plant=$_GET['plant'];  
 
 
   /* $sql_adv="UPDATE bio_advance SET bio_advance.status=1 WHERE bio_advance.adv_id=$adv_id";                         
    $result_adv = DB_query($sql_adv,$db);  */
       
    //$adv_id=$_GET['adv_id'];
    include('includes/PDFStarter.php');
    $pdf->addInfo('Title',_('Inventory Quantities Report'));
    $pdf->addInfo('Subject',_('Parts With Quantities'));
    $FontSize=9;
    $PageNumber=1;
    $line_height=15;
                                                                                                                
    $Xpos = $Left_Margin+1;
    $WhereCategory = " ";
    $CatDescription = " ";

   /*$sql_new1="SELECT * FROM salesorderdetails WHERE orderno=$orderno AND stkcode='$plant'";
    $result1=DB_query($sql_new1,$db);
    $row1=DB_fetch_array($result1);  
    $quantity=$row1['quantity'];     */
                                            
/*$num_rows = $result->num_rows;
if ($num_rows > 0) {
                    */                                                                         //  stockcode='$plant' AND
  $sql_newcheck="SELECT * FROM bio_deliverynote WHERE 
  orderno='$orderno' AND despatch_id='".$_GET['desid']."' ";
 $result_newcheck=DB_query($sql_newcheck,$db);
 $row_newcheck=DB_fetch_array($result_newcheck); 
 $num_rows = $result_newcheck->num_rows;           //echo $num_rows; 
 if($num_rows==0)
 {
        $sql_des="SELECT * FROM bio_despatchClearence WHERE orderno='$orderno' AND despatch_id='".$_GET['desid']."'";
       $result_des=DB_query($sql_des,$db);
       while($row_des=DB_fetch_array($result_des))
       {            
           $sql_new1="SELECT * FROM salesorderdetails WHERE orderno=$orderno AND stkcode='".$row_des['stockid']."'";
    $result1=DB_query($sql_new1,$db);
    $row1=DB_fetch_array($result1);  
    $quantity=$row1['quantity'];
           
         //echo $row1['orderlineno']; 
         $myDate = date('Y-m-d');
     $sql_new="INSERT INTO bio_deliverynote (despatch_id,orderno,stockcode,lineno,quantity_ordered,this_delivery,delivery_date,ack_recieved)
     VALUES (".$_GET['desid'].",".$orderno.",'".$row_des['stockid']."','".$row1['orderlinno']."', ".$row1['quantity'].", ".$row_des['dc_qty']." 
     ,'".$myDate."', 0)";
     $result_new=DB_query($sql_new,$db);  
       }                                                                  
     
     
     
       
 }
    
    /*$sql_new2="SELECT description FROM stockmaster WHERE stockmaster.stockid='$plant'";
     $result2=DB_query($sql_new2,$db);
     $row2=DB_fetch_array($result2);  */
    // $plantdesription=$row2['description'];
     
     
      $sql_new3="SELECT custbranch.brname, custbranch.phoneno,custbranch.braddress1,
      custbranch.braddress2,custbranch.braddress3,custbranch.braddress4 ,custbranch.did,custbranch.stateid,custbranch.cid,bio_district.district
      from custbranch,salesorders,salesorderdetails,bio_district
      WHERE salesorders.debtorno=custbranch.debtorno 
      AND salesorders.orderno=salesorderdetails.orderno 
      AND bio_district.did=custbranch.did
              AND    bio_district.stateid=custbranch.stateid
              AND    bio_district.cid=custbranch.cid
      AND salesorderdetails.orderno=".$orderno;
           $result3=DB_query($sql_new3,$db);
     $row3=DB_fetch_array($result3); 
       $name=$row3['brname'];
       $custphone=$row3['phoneno'];
    
 $stock=array(); 
 $description=array();  
 $serialno=array(); 
 $quantity=array();                                                           //   stockcode='$plant' AND
 $quantityfordel=array();
 $deldate=array();  
        $sql_new4="SELECT * FROM bio_deliverynote WHERE  orderno=$orderno AND despatch_id='".$_GET['desid']."' ";
       $result4=DB_query($sql_new4,$db);
       while($row4=DB_fetch_array($result4))
       {      //echo   $row4['stockcode'];
       
       
       $sql_new2="SELECT description FROM stockmaster WHERE stockmaster.stockid='".$row4['stockcode']."' ";
     $result2=DB_query($sql_new2,$db);
     $row2=DB_fetch_array($result2); 
     
            $sql_new5="SELECT serialno FROM bio_despatchClearence WHERE stockid='".$row4['stockcode']."' AND orderno='".$row4['orderno']."' AND despatch_id='".$row4['despatch_id']."' ";
     $result5=DB_query($sql_new5,$db);         
     $row5=DB_fetch_array($result5); 
           $ackno=$row4['despatch_id']; 
           $stock[]=$row4['stockcode'];
           $description[]=$row2['description'];
           $serialno[]=$row5['serialno']; 
           $quantity[]=$row4['quantity_ordered'];
           $quantityfordel[]=$row4['this_delivery'];
           $deldate[]=$row4['delivery_date'];
       } 
       $item_count=sizeof($stock); 
       
     
    /* $sql_new5="SELECT * FROM bio_despatchClearence WHERE stockid='$plant' AND orderno=$orderno AND id='".$_GET['desid']."' ";
     $result5=DB_query($sql_new5,$db);         
     $row5=DB_fetch_array($result5); 
                                                */    
    
//--------------------------------------------------------



    PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                $Page_Width,$Right_Margin,$CatDescription,$currentstatus);

    $FontSize=8;
       
  $YPos-=100;                                  
    
$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize,$ackno,'',0);
$pdf->addTextWrap($Left_Margin+475,$YPos-=$line_height-15,100,$FontSize,convertSQLDate($deldate[0]),'',0);       
//$pdf->addTextWrap($Page_Width-$Right_Margin-40,$YPos-80,100,$FontSize,$paydate, 'left'); 
$string="Received from Mr / Mrs. ".$name.", ".$myrow1['area1'].", the Sum of Rs. ".$myrow2['amount']."/- towards ".$myrow4['heads'];    
$string2.=" by ".$myrow3['modes']." No. ".$myrow2['serialnum']." of ".$myrow2['bankname']." dated ".$clearencedate.".";

$pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,60,$FontSize+4,_('To'),'left'); 
$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+3,$name,'',0);
$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+3,$row3['braddress1'],'',0);  
$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+3,$row3['braddress2'],'',0);
$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+3,$row3['braddress3'],'',0);
//$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+3,$row3['braddress4'],'',0);
$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+3,$row3['district'],'',0);



$pdf->addTextWrap($Left_Margin+2,$YPos-=$line_height+25,60,$FontSize+2,_('Item Code'),'left');
$pdf->addTextWrap($Left_Margin+80,$YPos-=$line_height-15,60,$FontSize+2,_('Item Description'),'left');
$pdf->addTextWrap($Left_Margin+240,$YPos-=$line_height-15,60,$FontSize+2,_('Serial No. of items'),'left');    
$pdf->addTextWrap($Left_Margin+360,$YPos-=$line_height-15,60,$FontSize+2,_('Qty. ordered'),'left');  
$pdf->addTextWrap($Left_Margin+450,$YPos-=$line_height-15,60,$FontSize+2,_('Qty. for delivery'),'left'); 
/*   $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height+20,100,$FontSize+3,$plant,'',0); 
$pdf->addTextWrap($Left_Margin+80,$YPos-=$line_height-15,100,$FontSize+3,$plantdesription,'',0);

$slno=explode(",",$row5['serialno']);
$sl_size=sizeof($slno);
if($sl_size==1)
{
   $pdf->addTextWrap($Left_Margin+240,$YPos-=$line_height-15,100,$FontSize+3,$row5['serialno'],'',0);  
}else{                    $temp=$YPos;  
    for($i==0;$i<$sl_size;$i++) {
                         
         $pdf->addTextWrap($Left_Margin+240,$temp,100,$FontSize+3,$slno[$i],'',0);
         $temp-=$line_height;
    }
} 


$pdf->addTextWrap($Left_Margin+360,$YPos-=$line_height-15,100,$FontSize+3,$quantity,'',0); 
$pdf->addTextWrap($Left_Margin+450,$YPos-=$line_height-15,100,$FontSize+3,$_GET['thisdel'],'',0); */            





           $no=1;
    for($i=0;$i<$item_count;$i++){
        $pdf->addTextWrap($Left_Margin+15,$YPos-=$line_height+10,140,$FontSize+1,$stock[$i],'',0);
        $pdf->addTextWrap($Left_Margin+60,$YPos,140,$FontSize+1,$description[$i],'',0);  
       $pdf->addTextWrap($Left_Margin+233,$YPos,140,$FontSize+1,$serialno[$i],'',0);
        $pdf->addTextWrap($Left_Margin+380,$YPos,140,$FontSize+1,$quantityfordel[$i],'',0);
        $pdf->addTextWrap($Left_Margin+480,$YPos,140,$FontSize+1,$quantity[$i],'',0);    
        $no++;
    }








//$pdf->addTextWrap($Left_Margin,$YPos-75,800,$FontSize+2,$string,'',0); 
//$pdf->addTextWrap($Left_Margin,$YPos-140,800,$FontSize+2,$string2,'',0);

// echo $YPos;


 

                                      
                
    if ($YPos < $Bottom_Margin + $line_height){
       PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                   $Right_Margin,$CatDescription);
    }
 //   } /*end while loop */

    if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
/*Print out the grand totals */

        $pdf->OutputD($_SESSION['DatabaseName'] . '_DeliveryNote_' . Date('Y-m-d') . '.pdf');
        $pdf->__destruct();
   

    
} 


function PrintHeader(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription,$currentstatus) {

    /*PDF page header for Reorder Level report */
    if ($PageNumber>1){
        $pdf->newPage();
    }
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;
    $img1='companies/'.$_SESSION['DatabaseName'].'/PNG/logo PNG.png';
    $pdf->addJpegFromFile($img1,$XPos+375,$YPos-47,0,55);
   
    $img2='companies/'.$_SESSION['DatabaseName'].'/PNG/B ONLY.png';
    $pdf->addJpegFromFile($img2,$XPos+40,$YPos-47,0,55);
      $img3='companies/'.$_SESSION['DatabaseName'].'/PNG/FOOTER.png';
    $pdf->addJpegFromFile($img3,$XPos+40,$YPos-780,0,40);
    
//    $pdf->addJpegFromFile($_SESSION['LogoFile'],$XPos+375,$YPos-50,0,60);
    $pdf->line($Left_Margin,$YPos-100,570,$YPos-100);  
    $pdf->addTextWrap(270,$YPos-80,60,$FontSize+4,_('Delivery Note'),'center'); 
    $pdf->addTextWrap($Left_Margin,$YPos-115,90,$FontSize,_('No :'), 'left'); 
    $pdf->addTextWrap($Left_Margin+450,$YPos-115,90,$FontSize,_('Date :'), 'left');                                            
    $pdf->addTextWrap($Page_Width-$Right_Margin-80,$YPos-690,600,$FontSize,_('Authorised signature'),'left'); 
     $pdf->addTextWrap($Left_Margin,$YPos-690,600,$FontSize,_('Signature of customer'),'left');
    



    if($currentstatus!=0){
        $pdf->addTextWrap($Left_Margin+30,$YPos-55,150,$FontSize+2,_('Copy of'),'center');
        
    }
       
//$pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos+10,90,$FontSize,_('Page').$PageNumber,'right' ); 
//         $pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos-295,90,$FontSize,Date($_SESSION['DefaultDateFormat']), 'right');

    $pdf->addTextWrap(160,$YPos,150,$FontSize,$CatDescription,'left');


    /*set up the headings */
    $Xpos = $Left_Margin+1;
    
    if($currentstatus!=0){
        $pdf->addTextWrap($Left_Margin+30,$YPos-300,150,$FontSize+2,_('Copy of'),'center');       
    }

       $pdf->line($Left_Margin,560,$Left_Margin+530,560); 
       $pdf->line($Left_Margin,540,$Left_Margin+530,540);
       $pdf->line($Left_Margin,200,$Left_Margin+530,200);
       $pdf->line($Left_Margin,200,$Left_Margin,560);
       $pdf->line($Left_Margin+55,200,$Left_Margin+55,560);
       $pdf->line($Left_Margin+230,200,$Left_Margin+230,560);              //$pdf->line($Left_Margin+230,200,$Left_Margin+230,560);    
       $pdf->line($Left_Margin+350,200,$Left_Margin+350,560);
       $pdf->line($Left_Margin+440,200,$Left_Margin+440,560);
       $pdf->line($Left_Margin+530,200,$Left_Margin+530,560); 
       $pdf->line($Left_Margin,80,$Left_Margin+530,80);
    $PageNumber++;
} // End of PrintHeader() function


?>

