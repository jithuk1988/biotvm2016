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

$PaperSize ='A4_Landscape';

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
    $Left_Margin=20;                                                                                                          
  //  $Xpos = $Left_Margin+1;
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

 $pdf->line($Left_Margin+270,0,$Left_Margin+270,565);
$pdf->line($Left_Margin+270+270,0,$Left_Margin+270+270,565);
 $nextp=0;
 $Xpos = $Left_Margin+1;
    for($j=0;$j<3;$j++)
    {
        
 // $pdf->line(40+270+$j,0,40+270,565);

    PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin+$nextp,
                $Page_Width,$Right_Margin,$CatDescription,$currentstatus);

    $FontSize=8;
       
  $YPos-=71;
                                  
    
$pdf->addTextWrap($Left_Margin+$nextp+18,$YPos-=$line_height-4,100,$FontSize,$ackno,'',0);
$pdf->addTextWrap($Left_Margin+$nextp+200,$YPos-=$line_height-17,100,$FontSize,convertSQLDate($deldate[0]),'',0);       
//$pdf->addTextWrap($Page_Width-$Right_Margin-40,$YPos-80,100,$FontSize,$paydate, 'left'); 
$string="Received from Mr / Mrs. ".$name.", ".$myrow1['area1'].", the Sum of Rs. ".$myrow2['amount']."/- towards ".$myrow4['heads'];    
$string2.=" by ".$myrow3['modes']." No. ".$myrow2['serialnum']." of ".$myrow2['bankname']." dated ".$clearencedate.".";
$pdf->addTextWrap($Left_Margin+$nextp,$YPos-=$line_height+14,60,$FontSize+2,_('To'),'left'); 
$pdf->addTextWrap($Left_Margin+$nextp+6,$YPos-=$line_height,100,$FontSize+1,$name,'',0);
$pdf->addTextWrap($Left_Margin+$nextp+6,$YPos-=$line_height,100,$FontSize+1,$row3['braddress1'],'',0); 
$pdf->addTextWrap($Left_Margin+$nextp+6,$YPos-=$line_height,100,$FontSize+1,$row3['braddress2'],'',0);
$pdf->addTextWrap($Left_Margin+$nextp+6,$YPos-=$line_height,100,$FontSize+1,$row3['braddress3'],'',0);
//$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+3,$row3['braddress4'],'',0);
$pdf->addTextWrap($Left_Margin+$nextp+6,$YPos-=$line_height,100,$FontSize+1,$row3['district'],'',0);





$thl=355;
$pdf->addTextWrap($Left_Margin+$nextp+2,$thl,80,$FontSize+2,_('Item'),'left');
$pdf->addTextWrap($Left_Margin+$nextp+2,$thl-10,80,$FontSize+2,_('Code'),'left');
$pdf->addTextWrap($Left_Margin+$nextp+26,$thl,80,$FontSize+2,_('Item '),'left');
$pdf->addTextWrap($Left_Margin+$nextp+26,$thl-10,80,$FontSize+2,_('Description'),'left');
$pdf->addTextWrap($Left_Margin+$nextp+140,$thl,80,$FontSize+2,_('Serial No.  '),'left');    
$pdf->addTextWrap($Left_Margin+$nextp+140,$thl-10,80,$FontSize+2,_('of items'),'left');    
$pdf->addTextWrap($Left_Margin+$nextp+180,$thl,80,$FontSize+2,_('Qty.'),'left');  
$pdf->addTextWrap($Left_Margin+$nextp+180,$thl-10,80,$FontSize+2,_('ordered'),'left');  
$pdf->addTextWrap($Left_Margin+$nextp+215,$thl,80,$FontSize+2,_('Qty. for '),'left'); 
$pdf->addTextWrap($Left_Margin+$nextp+215,$thl-10,80,$FontSize+2,_('delivery'),'left');
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
        $pdf->addTextWrap($Left_Margin+$nextp+2,$YPos-=$line_height+60,135,$FontSize+1,$stock[$i],'',0);
        $pdf->addTextWrap($Left_Margin+$nextp+26,$YPos,135,$FontSize+1,$description[$i],'',0);  
       $pdf->addTextWrap($Left_Margin+$nextp+141,$YPos,135,$FontSize+1,$serialno[$i],'',0);
        $pdf->addTextWrap($Left_Margin+$nextp+181,$YPos,135,$FontSize+1,$quantityfordel[$i],'',0);
        $pdf->addTextWrap($Left_Margin+$nextp+216,$YPos,135,$FontSize+1,$quantity[$i],'',0);    
        $no++;
    }








//$pdf->addTextWrap($Left_Margin,$YPos-75,800,$FontSize+2,$string,'',0); 
//$pdf->addTextWrap($Left_Margin,$YPos-140,800,$FontSize+2,$string2,'',0);

// echo $YPos;


 

                                      
                
    if ($YPos < $Bottom_Margin + $line_height){
       PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin+$nextp,$Page_Width,
                   $Right_Margin,$CatDescription);
    }
 //   } /*end while loop */

    if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin+$nextp,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
/*Print out the grand totals */
$nextp=$nextp+280;
//$Xpos=$Xpos+250;
    }
        $pdf->OutputD($_SESSION['DatabaseName'] . '_DeliveryNote_' . Date('Y-m-d') . '.pdf');
        $pdf->__destruct();
   
 
     
 
}


function PrintHeader(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription,$currentstatus) {

    /*PDF page header for Reorder Level report */
    if ($PageNumber>1){
        //$pdf->newPage();
    }
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;
   // echo "f".$YPos;
    $img1='companies/'.$_SESSION['DatabaseName'].'/PNG/logo PNG.png';
/* $x=   $XPos+125;
 echo "x".$x;
 $y= $YPos-47;
echo "y".$y;*/
    $pdf->addJpegFromFile($img1,$Left_Margin+100,$YPos-47,0,40);
   
    $img2='companies/'.$_SESSION['DatabaseName'].'/PNG/B ONLY.png';
    $pdf->addJpegFromFile($img2,$Left_Margin+10,$YPos-47,0,40);
      $img3='companies/'.$_SESSION['DatabaseName'].'/PNG/FOOTER.png';
    $pdf->addJpegFromFile($img3,$Left_Margin+10,$YPos-540,0,20);
    
//    $pdf->addJpegFromFile($_SESSION['LogoFile'],$XPos+375,$YPos-50,0,60);
    $pdf->line($Left_Margin,$YPos-71,$Left_Margin+260,$YPos-71);  
    $pdf->addTextWrap($Left_Margin+100,$YPos-65,60,$FontSize+3,_('Delivery Note'),'center'); 
    $pdf->addTextWrap($Left_Margin,$YPos-82,90,$FontSize,_('No :'), 'left'); 
    $pdf->addTextWrap($Left_Margin+170,$YPos-82,90,$FontSize,_('Date :'), 'left');                                            
    $pdf->addTextWrap($Left_Margin+170,$YPos-500,600,$FontSize,_('Authorised signature'),'left'); 
     $pdf->addTextWrap($Left_Margin,$YPos-500,600,$FontSize,_('Signature of customer'),'left');
    



  /*  if($currentstatus!=0){
        $pdf->addTextWrap($Left_Margin+10,$YPos-39,150,$FontSize+2,_('Copy of'),'center');
        
    }
      */ 
//$pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos+10,90,$FontSize,_('Page').$PageNumber,'right' ); 
//         $pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos-295,90,$FontSize,Date($_SESSION['DefaultDateFormat']), 'right');

    $pdf->addTextWrap(160,$YPos,150,$FontSize,$CatDescription,'left');


    /*set up the headings */
   // $Xpos = $Left_Margin+1;
    
    if($currentstatus!=0){
       // $pdf->addTextWrap($Left_Margin+10,$YPos-153,150,$FontSize+2,_('Copy of'),'center');       
    }

       $pdf->line($Left_Margin,366,$Left_Margin+250,366); 
       $pdf->line($Left_Margin,330,$Left_Margin+250,330);
       $pdf->line($Left_Margin,143,$Left_Margin+250,143);
       $pdf->line($Left_Margin,143,$Left_Margin,366);//
       $pdf->line($Left_Margin+25,143,$Left_Margin+25,366);//
       $pdf->line($Left_Margin+140,143,$Left_Margin+140,366);  //            //$pdf->line($Left_Margin+230,200,$Left_Margin+230,560);    
       $pdf->line($Left_Margin+180,143,$Left_Margin+180,366);//
       $pdf->line($Left_Margin+215,143,$Left_Margin+215,366);//
       $pdf->line($Left_Margin+250,143,$Left_Margin+250,366);// 
       $pdf->line($Left_Margin,57,$Left_Margin+250,57);
    $PageNumber++;
} // End of PrintHeader() function


?>

