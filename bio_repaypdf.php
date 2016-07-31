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
    $adv_id=$_GET['adv_id'];
$PaperSize ='A4';

if ($adv_id!="") {
 
    $sql_adv="UPDATE bio_advance SET bio_advance.status=1 WHERE bio_advance.adv_id=$adv_id";                         
    $result_adv = DB_query($sql_adv,$db);
       
    //$adv_id=$_GET['adv_id'];
    include('includes/PDFStarter.php');
    $pdf->addInfo('Title',_('Inventory Quantities Report'));
    $pdf->addInfo('Subject',_('Parts With Quantities'));
    $FontSize=9;
    $PageNumber=1;
    $line_height=12;

    $Xpos = $Left_Margin+1;
    $WhereCategory = " ";
    $CatDescription = " ";

   $sql1="SELECT leadid FROM bio_advance WHERE adv_id=$adv_id";
    $result1=DB_query($sql1,$db);
    $row1=DB_fetch_array($result1);   
    
    $sql="SELECT * FROM bio_leads WHERE leadid=".$row1['leadid'];
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_array($result); 
    
      $rmdailykg=$myrow['rmdailykg'];
      $advanceamount=$myrow['advanceamount'];
      $productservicesid=$myrow['productservicesid'];
      $enqtypeid=$myrow['enqtypeid']; 
      
//      $billdate=ConvertSQLDate($fdate=$myrow['leaddate']);
      
      $outputtypeid=$myrow['outputtypeid']; 
      $schemeid=$myrow['schemeid'];
      $teamid=$myrow['teamid'];
      $sourceid=$myrow['sourceid'];
      $investmentsize=$myrow['investmentsize'];
      $remarks=$myrow['remarks'];
      $status=$myrow['status']; 

      $sqledt="SELECT * FROM bio_cust WHERE bio_cust.cust_id=".$myrow['cust_id'];
     $result1=DB_query($sqledt,$db);
     $myrow1=DB_fetch_array($result1); 
      $custname=$myrow1['custname'];    
      $homno=$myrow1['houseno'];
      $housename=$myrow1['housename']; 
      $area1=$myrow1['area1'];
      $area2=$myrow1['area2'];
      $pin=$myrow1['pin'];
      $nationality=$myrow1['nationality']; 
      $state=$myrow1['state'];
      $district=$myrow1['district'];   

    $pieces=$myrow1['custphone'];  $phpieces = explode("-", $pieces,2);  $custcode=$phpieces[0];if($custcode==0 || $custcode==""){$custcode=0;}  $custphone=$phpieces[1];
    $custmobile=$myrow1['custmob'];
    $custemail=$myrow1['custmail'];
    
    

   $sql2="select * from bio_advance where bio_advance.adv_id=$adv_id";
    $result2=DB_query($sql2,$db);
    $myrow2=DB_fetch_array($result2);
    
    $clearencedate=ConvertSQLDate($myrow2['date']);
    $paydate=ConvertSQLDate($myrow2['paydate']);
    $modeid=$myrow2['mode_id'];
    $no=$myrow2['serialnum'];
    $bank=$myrow2['bankname'];
    $amount=abs ($myrow2['amount']);
    //$amount=abs($amount1) ;
    $headid=$myrow2['head_id'];         
    $leafno=$myrow2['serialnum'];
    $bankname=$myrow2['bankname'];
    
    
    $sql4="select heads from bio_cashheads where head_id=".$headid; 
    $result4=DB_query($sql4,$db);
    $myrow4=DB_fetch_array($result4);
    $head=$myrow4['heads'];  
    
    if($modeid!='')
    {
    $sql3="select modes from bio_paymentmodes where id=".$modeid; 
    $result3=DB_query($sql3,$db);
    $myrow3=DB_fetch_array($result3);
    
    $mode=$myrow3['modes'];
    }

    
//--------------------------------------------------------
if($custphone==""){$custphone=0;}if($custmobile==""){$custmobile=0;} if($custemail==""){$custemail=0;}


    PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                $Page_Width,$Right_Margin,$CatDescription,$currentstatus);

    $FontSize=8;
    
                                  
    
$pdf->addTextWrap($Left_Margin+20,$YPos-80,100,$FontSize,$adv_id,'',0); 
$pdf->addTextWrap($Page_Width-$Right_Margin-40,$YPos-80,100,$FontSize,$paydate, 'left'); 

if($modeid==1)
{
$string="Repayed to  Mr / Mrs. ".$myrow1['custname'].", ".$myrow1['area1'].", the Sum of Rs. ".$amount."/- towards ".$myrow4['heads']." by ".$myrow3['modes'].".";    
$pdf->addTextWrap($Left_Margin,$YPos-120,800,$FontSize+2,$string,'',0);
$pdf->addTextWrap($Left_Margin,$YPos-530,800,$FontSize+2,$string,'',0);
}    
elseif($modeid==2 || $modeid==3)               //$myrow2['amount']
{
$string="Repayed to Mr / Mrs. ".$myrow1['custname'].", ".$myrow1['area1'].", the Sum of Rs. ".$amount."/- towards ".$myrow4['heads'];    
$string2.=" by ".$myrow3['modes']." No. ".$myrow2['serialnum']." of ".$myrow2['bankname']." dated ".$clearencedate.".";
$pdf->addTextWrap($Left_Margin,$YPos-120,800,$FontSize+2,$string,'',0); 
$pdf->addTextWrap($Left_Margin,$YPos-140,800,$FontSize+2,$string2,'',0); 
$pdf->addTextWrap($Left_Margin,$YPos-530,800,$FontSize+2,$string,'',0);  
$pdf->addTextWrap($Left_Margin,$YPos-550,800,$FontSize+2,$string2,'',0);                                   
}
 

$pdf->addTextWrap($Left_Margin+20,$YPos-490,100,$FontSize,$adv_id,'',0); 
$pdf->addTextWrap($Page_Width-$Right_Margin-40,$YPos-490,100,$FontSize,$paydate, 'left'); 
                                      
                
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
    if($currentstatus==0){
        $pdf->OutputD($_SESSION['DatabaseName'] . '_Receipt_' . Date('Y-m-d') . '.pdf');
        $pdf->__destruct();
    }
    else{
        $pdf->OutputD('Copy of '.$_SESSION['DatabaseName'] . '_Receipt_' . Date('Y-m-d') . '.pdf');
        $pdf->__destruct();
    }
    
} 


function PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription,$currentstatus) {

    /*PDF page header for Reorder Level report */
    if ($PageNumber>1){
        $pdf->newPage();
    }
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;
    $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
    $pdf->addJpegFromFile($img1,$XPos+392,$YPos-47,0,55);

    
//    $pdf->addJpegFromFile($_SESSION['LogoFile'],$XPos+375,$YPos-50,0,60);
    $pdf->line($Left_Margin,$YPos-52,570,$YPos-52);  
    $pdf->addTextWrap(270,$YPos-70,50,$FontSize+4,_('Receipt'),'center'); 
    $pdf->addTextWrap($Left_Margin,$YPos-80,90,$FontSize,_('No :'), 'left');                                            
    $pdf->addTextWrap($Page_Width-$Right_Margin-80,$YPos-220,600,$FontSize,_('Authorized Signature'),'left'); 
    
    $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
    $pdf->addJpegFromFile($img1,$XPos+392,$YPos-460,0,55);
    
//    $pdf->addJpegFromFile($_SESSION['LogoFile'],$XPos+375,$YPos-460,0,60);
    $pdf->line($Left_Margin,$YPos-462,570,$YPos-462);
    $pdf->addTextWrap(270,$YPos-480,50,$FontSize+4,_('Receipt'),'center'); 
    $pdf->addTextWrap($Left_Margin,$YPos-490,90,$FontSize,_('No :'), 'left');                                            
    $pdf->addTextWrap($Page_Width-$Right_Margin-80,$YPos-630,600,$FontSize,_('Authorized Signature'),'left'); 



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

       $pdf->line($Left_Margin-30,$Page_Height/2,570,$Page_Height/2); 

    $PageNumber++;
} // End of PrintHeader() function


  
?>
