<?php
 

 
 $PageSecurity = 80;
  include('includes/session.inc'); 
  
  $PaperSize ='A4';  
  
  include('includes/PDFStarter.php');
               $lead=$_GET['lead'];

  $FontSize=9;
    $PageNumber=1;
    $line_height=12;
    $Xpos = $Left_Margin+1;
    $WhereCategory = " ";
    $CatDescription = " ";  
  
 //------------------------------------------------------------------------------// 


      
      //unset($_SESSION['leadid']);  
  
  
$sql1="select 
               bio_cust.custname,
               bio_cust.houseno,
               bio_cust.housename,
               bio_district.district
    from       bio_cust,
               bio_district, 
               bio_leads
               where bio_cust.cust_id= bio_leads.cust_id
               AND bio_leads.leadid=".$lead;
   $result1=DB_query($sql1,$db);
   $row1=DB_fetch_array($result1);   
   $name=$row1['custname']; 
   $house=$row1['houseno']; 
   $hname=$row1['housename'];  
   $district=$row1['district'];
   
     
    //---------------------------------------------------------------------------------------//
      PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);

     $FontSize=8; 
    $pdf->addTextWrap($Left_Margin+20,$YPos-130,80,$FontSize,$name,'',0);
     $pdf->addTextWrap($Left_Margin+20,$YPos-140,80,$FontSize,$house,'',0);
     $pdf->addTextWrap($Left_Margin+20,$YPos-150,80,$FontSize,$hname,'',0);
     $pdf->addTextWrap($Left_Margin+20,$YPos-160,80,$FontSize,$district,'',0);
     

     if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
    
    $pdf->OutputD($_SESSION['DatabaseName'] . '_Reminder letter for inactive CP_' . Date('Y-m-d') . '.pdf');
    $pdf->__destruct();
    function PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription) {
                         
    //-----------------------------------------------------------------------------------//
  
  
     if ($PageNumber>1){
        $pdf->newPage();
    }
    
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    
    $pdf->addTextWrap($Left_Margin,$YPos,100,$FontSize+3,_('BIOTECH'),'left');  
    $pdf->addTextWrap($Left_Margin,$YPos-10,100,$FontSize-2,_('Post Box. No 520, M. P. Appan Road'),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-20,100,$FontSize-2,_('Vazhuthacadu, Thycaud P.O.'),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-30,100,$FontSize-2,_('Thiruvananthapuram-695 014, KERALA'),'left');        
    $pdf->line($Left_Margin,$YPos-32,600,$YPos-32);
 
      $YPos -=$line_height;  
        $pdf->addTextWrap($Left_Margin+220,$YPos-60,100,$FontSize+4,_('Reminder Letter'),'centre');        
        
        $pdf->addTextWrap($Left_Margin,$YPos-100,100,$FontSize,_('To,'),'left'); 
        
               
         $pdf->addTextWrap($Left_Margin,$YPos-180,400,$FontSize,_('Dear Sir,'),'left');      
      
         $pdf->addTextWrap($Left_Margin,$YPos-190,100,$FontSize,_('Reference'),'left');      
         $pdf->addTextWrap($Left_Margin,$YPos-210,700,$FontSize,_('Please accept our proposal'),'left');      
         $pdf->addTextWrap($Left_Margin+400,$YPos-280,200,$FontSize,_('Yours faithfully'),'left');      
         $pdf->addTextWrap($Left_Margin+400,$YPos-300,200,$FontSize,_('Signature'),'left');      
 
                     }                     
   
   
                     
?>

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
  

