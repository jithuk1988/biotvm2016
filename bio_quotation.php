<?php
$PageSecurity = 80;
  include('includes/session.inc'); 
  
  $PaperSize ='A4';  
  
  include('includes/PDFStarter.php');
  

   
    $FontSize=9;
    $PageNumber=1;
    $line_height=12;
    $Xpos = $Left_Margin+1;
    $WhereCategory = " ";
    $CatDescription = " ";  
    
 //------------------------------------------------------------------------//  
 
  $year=date("Y");
   if($_GET['leadid']!=""){
     // $_GET['lead']=$_SESSION['leadid'];
      $lead=$_GET['leadid'];
     // unset($_SESSION['leadid']);  
  
      
   
   $sql1="SELECT 
               bio_cust.custname,
               bio_cust.houseno,
               bio_cust.housename,
               bio_leads.leaddate,
               bio_district.district
          FROM bio_cust,
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
   $date1=$row1['leaddate'];
   $date=ConvertSQLDate($fdate=$date1);
$sql2="select bio_proposal.totprice,bio_proposal.proposaldate,bio_proposal.fileno,bio_proposal.createdby
from bio_proposal,bio_leads where bio_proposal.leadid=bio_leads.leadid
        
       ";   
   $result2=DB_query($sql2,$db);
   $row2=DB_fetch_array($result2);
   
  // $price=$row2['totprice'];
   //$description=$row2['longdescription'];
   $pdate=$row2['proposaldate'];
   $file=$row2['fileno'];
   $created=$row2['createdby'];
     $string= "The total cost for implementing this project is Rs. " .$row2['totprice']."/-" ; 
   
   
      $sql_emp="SELECT  bio_emp.empname
        FROM  bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid='".$created."'";
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
      $empname=$myrow_emp['empname'];
   
   
   
   
   
//----------------------------------------------------------------------//  
  
   PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                $Page_Width,$Right_Margin,$CatDescription);

     $FontSize=8; 
     
       $pdf->addTextWrap($Left_Margin+122,$YPos-210,130,$FontSize+1,$date,'',0);
       
       
       
     
     // $pdf->addTextWrap($Left_Margin+30,$YPos-100,130,$FontSize+1,$year,'',0);
//        $pdf->addTextWrap($Left_Margin+49,$YPos-100,130,$FontSize+1,_('/'),'left'); 
//         $pdf->addTextWrap($Left_Margin+53,$YPos-100,130,$FontSize+1,$lead,'',0);
     $pdf->addTextWrap($Left_Margin,$YPos-120,80,$FontSize+1,$name,'',0);
     $pdf->addTextWrap($Left_Margin,$YPos-130,80,$FontSize+1,$house,'',0);
     $pdf->addTextWrap($Left_Margin,$YPos-140,80,$FontSize+1,$hname,'',0);
     $pdf->addTextWrap($Left_Margin,$YPos-150,80,$FontSize+1,$district,'',0);
     
      $pdf->addTextWrap($Left_Margin,$YPos-370,80,$FontSize+1,$empname,'',0); 
     
     //$pdf->addTextWrap($Left_Margin+70,$YPos-350,80,$FontSize,$description,'',0);
    // $pdf->addTextWrap($Left_Margin+250,$YPos-350,80,$FontSize,$row2['totprice'],'',0);
    
    
    
     $pdf->addTextWrap($Left_Margin,$YPos-270,400,$FontSize+1,$string,'',0);
     
     
     
     $pdf->addTextWrap($Left_Margin,$YPos-80,130,$FontSize,$row2['fileno'],'',0);
     
     $pdf->addTextWrap($Left_Margin+480,$YPos-80,130,$FontSize,$pdate,'',0);
     
     
    if ($YPos < $Bottom_Margin + $line_height){                                                       //------if data exeeds page height------//
        PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
        $Right_Margin,$CatDescription);
    }
  //---------------------------------------------------------------------//
    
    if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
    
    $pdf->OutputD($_SESSION['DatabaseName'] . '_Quotation with Price_' . Date('Y-m-d') . '.pdf');
    $pdf->__destruct();
   }   
   //
    function PrintHeader(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription) {
  
  
 //--------------------------------------------------------------------------------//
  
  if ($PageNumber>1){
        $pdf->newPage();
    }
    
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    
        //$pdf->addTextWrap($Left_Margin,$YPos,130,$FontSize+2,_('International Ashden Award Winner'),'left'); 
//        $pdf->line($Left_Margin,$YPos-4,$Left_Margin+175,$YPos-4); 
//        $pdf->addTextWrap($Left_Margin+20,$YPos-15,100,$FontSize,_('Waste to Electricity project,'),'left');
//        $pdf->addTextWrap($Left_Margin,$YPos-25,100,$FontSize,_('Domestic Biogas plants, Approved by'),'left');
//        $pdf->addTextWrap($Left_Margin,$YPos-35,100,$FontSize,_('Ministry of New and Renewable Energy'),'left'); 
//        $pdf->addTextWrap($Left_Margin+20,$YPos-45,100,$FontSize,_('(MNRE)   Govt. of India.'),'left'); 
        
       $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
       $pdf->addJpegFromFile($img1,$XPos+392,$YPos-47,0,55);
       $img2= 'companies/'.$_SESSION['DatabaseName'].'/logof.jpg';
       $pdf->addJpegFromFile($img2,$XPos+35,$YPos-47,0,55);  
        
//        $pdf->addJpegFromFile($_SESSION['LogoFile'],$XPos+392,$YPos-47,0,55);
        
      
        
         //$pdf->addJpegFromFile($_SESSION['LogoFile'],$XPos+355,$YPos-47,0,55);
         //$pdf->addTextWrap($Left_Margin+315,$YPos-0,100,$FontSize+3,_('BIOTECH'),'right');
//                 $pdf->addTextWrap($Left_Margin+352,$YPos-7,200,$FontSize-2,_('CENTRE FOR DEVELOPMENT OF BIOGAS TECHNOLOGY'),'right');
//        $pdf->addTextWrap($Left_Margin+402,$YPos-14,100,$FontSize-2,_('AND OTHER NON-CONVENSIONAL ENERGY SOURCES'),'right');
//        $pdf->addTextWrap($Left_Margin+379,$YPos-21,100,$FontSize-2,_('Post Box. No. 520, M.P. Appan Road, Vazzhuthacaud'),'right');        
//        $pdf->addTextWrap($Left_Margin+395,$YPos-28,100,$FontSize-2,_('Thycaud P.O. Trivandrum- 695 014, Kerala, S.India'),'right');         
//         $pdf->addTextWrap($Left_Margin+395,$YPos-35,100,$FontSize-2,_('Ph : 0471- 2321909, 2332179 Tele Fax : 0471-2331909'),'right');        
//         $pdf->addTextWrap($Left_Margin+395,$YPos-42,100,$FontSize-2,_('Email: biotechindia@eth.net, Web: www.biotech-india.org'),'right');        
        
        
        
        
        
        $pdf->line($Left_Margin,$YPos-50,$Left_Margin+534,$YPos-50);
        //$pdf->addTextWrap($Left_Margin,$YPos-100,130,$FontSize,_('No.BT/WTP/11'),'left'); 
       // $pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos-100,90,$FontSize,Date( $_SESSION['DefaultDateFormat']), 'right');
        $pdf->addTextWrap($Left_Margin,$YPos-100,100,$FontSize,_('To,'),'left');
        $pdf->addTextWrap($Left_Margin,$YPos-170,100,$FontSize,_('Dear Sir,'),'left');
        $pdf->addTextWrap($Left_Margin+25,$YPos-190,100,$FontSize,_('Sub :- BIOTECH :- Waste to Energy Project'),'left');
        $pdf->addTextWrap($Left_Margin+25,$YPos-210,100,$FontSize,_('Ref :- Proposal given on'),'left'); 
        $pdf->addTextWrap($Left_Margin,$YPos-230,1000,$FontSize,_('We express our sincere thanks to you for your interest in our project.We are sending here with the proposal for the installation '),'left');
        
        $pdf->addTextWrap($Left_Margin,$YPos-240,800,$FontSize,_('of waste treatment plants to your house, for favor of your kind information and necessary further action. Please feel free   '),'left');
      
        
        $pdf->addTextWrap($Left_Margin,$YPos-250,800,$FontSize,_('to contact us for any further clarification in this matter.  '),'left');
        
        
      //  $pdf->addTextWrap($Left_Margin,$YPos-310,900,$FontSize,_('The total cost for implementing this project is Rs.                /-  '),'left');
        //
//         $pdf->addTextWrap($Left_Margin+100,$YPos-350,800,$FontSize+1,_('PROJECT COST'),'left');  
//    
//        $pdf->addTextWrap($Left_Margin+70,$YPos-390,800,$FontSize+1,_('Item name'),'left');  
//        $pdf->addTextWrap($Left_Margin+190,$YPos-390,800,$FontSize+1,_('Price'),'left');
       // $pdf->line($Left_Margin+70,$YPos-422,$Left_Margin+280,$YPos-422);
       
        //$pdf->addTextWrap($Left_Margin+20,$YPos-520,800,$FontSize,_('Please feel free to contact us for any further clarification in the matter.'),'left'); 
        
        $pdf->addTextWrap($Left_Margin,$YPos-330,200,$FontSize,_(' With warm regards,'),'left');
        $pdf->addTextWrap($Left_Margin,$YPos-350,200,$FontSize,_(' Authorized Signatory'),'left');
        
        //$pdf->addTextWrap($Left_Margin+15,$YPos-470,100,$FontSize,_('DIRECTOR'),'left');
      //  $pdf->addTextWrap($Left_Margin+15,$YPos-490,100,$FontSize,_(' BIOTECH'),'left');  
//
//             $pdf->line($Left_Margin,$YPos-520,$Left_Margin+534,$YPos-520);
//     
//      $pdf->addTextWrap($Left_Margin+40,$YPos-540,900,$FontSize,_('ERNAKULAM : Desai Road, Vazhakkala, Kakkanad West P.O., Ernakulam - 682030, Ph : 0484-3253431, 2108279'),'left');  
//     
//      $pdf->addTextWrap($Left_Margin,$YPos-550,200,$FontSize,_(' REGIONAL OFFICES'),'left');  
//     
//      $pdf->addTextWrap($Left_Margin+40,$YPos-560,900,$FontSize,_(' KOZHIKODE :  North Pipe Line Road,  Thondayadu,  Chevarambalam P.O.,  Kozhikode,  Ph : 0495-2353887'),'left');  
//    $pdf->addTextWrap($Left_Margin+105,$YPos-580,900,$FontSize,_(' Helpline: Trivandrum- 9446000960 Ernakulam- 9446000961 Kozhikode- 9446000962 '),'left');    
        
 } 
  
?>
