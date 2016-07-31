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
  
  // if ($YPos < $Bottom_Margin + $line_height){                                                       //------if data exeeds page height------//
//        PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
//        $Right_Margin,$CatDescription);
//    }
//  
    
    
    
    
//-------------------------------------------------------------------//
   if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
           $pdf->newPage(); 
           
     $pdf->addTextWrap($Left_Margin+200,$YPos-4,300,$FontSize+4,_('FACILITIES REQUIRED'),'centre');                         
              
     $pdf->addTextWrap($Left_Margin+120,$YPos-60,300,$FontSize+2,_('All'),'left');     
     $pdf->rect(190,70,80,35); 
     $pdf->addTextWrap($Left_Margin+260,$YPos-60,300,$FontSize+2,_('Selected'),'left');  
     $pdf->rect(360,70,80,35);    
     $pdf->rect(52,145,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-122,300,$FontSize,_('Treatment plant'),'left');     
     $pdf->rect(52,165,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-142,300,$FontSize,_('Gas Connection Work'),'left');     
     $pdf->rect(52,185,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-162,700,$FontSize,_('Stove ....................................Numbers'),'left');
     $pdf->rect(52,205,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-182,400,$FontSize,_('Initial Feeding'),'left');
     $pdf->rect(52,225,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-202,500,$FontSize,_('Water Storage Tank'),'left');
     $pdf->rect(52,245,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-222,500,$FontSize,_('Treated Slurry Recycling System'),'left');
     $pdf->rect(52,265,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-242,500,$FontSize,_('Leach Beds'),'left');
     $pdf->rect(52,285,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-262,500,$FontSize,_('Gas Scrubber'),'left');  
     $pdf->rect(52,305,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-282,500,$FontSize,_('De humidifier'),'left');
     $pdf->rect(52,325,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-302,500,$FontSize,_('Covers for all Tanks'),'left');                
     $pdf->rect(52,345,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-322,500,$FontSize,_('Generators'),'left');                
     $pdf->rect(160,345,13,8);   $pdf->addTextWrap($Left_Margin+150,$YPos-322,500,$FontSize,_('Numbers'),'left');     
     $pdf->rect(52,365,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-342,500,$FontSize,_('Biocinarator'),'left');     
     $pdf->rect(52,385,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-362,500,$FontSize,_('UASB Reactor'),'left');     
     $pdf->rect(52,405,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-382,500,$FontSize,_('Blood and Waste Water Recycling System'),'left');     
     $pdf->rect(52,425,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-402,500,$FontSize,_('Plastic Collection Chamber'),'left');     
     $pdf->rect(52,445,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-422,500,$FontSize,_('Glass Collection Chamber'),'left');     
     $pdf->rect(52,465,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-442,500,$FontSize,_('Metal Collection Chamber'),'left');     
     $pdf->rect(52,485,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-462,500,$FontSize,_('Digging of Pit'),'left');     
     $pdf->rect(52,505,13,8);   $pdf->addTextWrap($Left_Margin+60,$YPos-482,500,$FontSize,_('Materials for civil work'),'left');
  
  $pdf->newPage();                     
                        
       $pdf->addTextWrap($Left_Margin+200,$YPos-20,500,$FontSize+4,_('DATA SHEET'),'centre');                           
       $pdf->addTextWrap($Left_Margin+20,$YPos-60,500,$FontSize+2,_('TOTAL QUANTITY OF WASTE '),'left');                           
       $pdf->addTextWrap($Left_Margin+120,$YPos-100,800,$FontSize+1,_('Actual...........................................................................................................................'),'left');                           
       $pdf->addTextWrap($Left_Margin+120,$YPos-120,800,$FontSize+1,_('Projected......................................................................................................................'),'left');                          
       $pdf->addTextWrap($Left_Margin+20,$YPos-160,800,$FontSize+2,_('CLASSIFICATION OF WASTE'),'left');                          
                        
       $pdf->addTextWrap($Left_Margin+120,$YPos-180,800,$FontSize+1,_('Easiliy Degradable...........................................................................................kg'),'left');                    
       $pdf->addTextWrap($Left_Margin+120,$YPos-200,800,$FontSize+1,_('Slow Degradable..............................................................................................kg'),'left');                   
       $pdf->addTextWrap($Left_Margin+120,$YPos-220,800,$FontSize+1,_('Very slow Degradable......................................................................................kg'),'left');                   
       $pdf->addTextWrap($Left_Margin+120,$YPos-240,800,$FontSize+1,_('Non Degradable...............................................................................................kg'),'left');                      
                     
     
       $pdf->addTextWrap($Left_Margin+20,$YPos-280,800,$FontSize+2,_('PRESENT WASTE DISPOSAL SYSTEM'),'left');     
       $pdf->addTextWrap($Left_Margin+20,$YPos-320,800,$FontSize+2,_('WASTE COLLECTION SYSTEM'),'left');    
     
       $pdf->addTextWrap($Left_Margin+120,$YPos-340,800,$FontSize+1,_('Collection of waste in single Bin / Seperate Bin'),'left');    
       $pdf->addTextWrap($Left_Margin+120,$YPos-360,800,$FontSize,_('Whether beneficiary is ready for Two Bin collection Yes / No'),'left');   
     
       $pdf->addTextWrap($Left_Margin+120,$YPos-380,800,$FontSize,_('Present consumption of LPG / Firewood per month.........................kg'),'left');   
       $pdf->addTextWrap($Left_Margin+20,$YPos-420,800,$FontSize,_('USE OF GAS'),'left');   
       
       $pdf->rect(150,485,13,8); $pdf->addTextWrap($Left_Margin+140,$YPos-460,800,$FontSize,_('Cooking'),'left');   
       $pdf->rect(150,505,13,8); $pdf->addTextWrap($Left_Margin+140,$YPos-480,800,$FontSize,_('Water Heating'),'left');                     
       $pdf->rect(150,525,13,8); $pdf->addTextWrap($Left_Margin+140,$YPos-500,800,$FontSize,_('Power Generation'),'left');                  
       $pdf->rect(150,545,13,8); $pdf->addTextWrap($Left_Margin+140,$YPos-520,800,$FontSize,_('Biocinarator / Incinerator'),'left');               
                            
       $pdf->newPage();
      
       $pdf->addTextWrap($Left_Margin+200,$YPos-20,500,$FontSize+4,_('GENERAL'),'centre');                           
       $pdf->addTextWrap($Left_Margin,$YPos-40,900,$FontSize,_('Date of application with No :..................................................................................................................................................'),'left');               
       $pdf->addTextWrap($Left_Margin,$YPos-60,900,$FontSize,_('Date of Study :.......................................................................................................................................................................'),'left');        
       $pdf->addTextWrap($Left_Margin,$YPos-80,900,$FontSize,_('Name of the Officer conducted the feasibility study'),'left');  
       $pdf->addTextWrap($Left_Margin+80,$YPos-100,900,$FontSize,_('1 ............................................................................................................................................................'),'left');             
       $pdf->addTextWrap($Left_Margin+80,$YPos-120,900,$FontSize,_('2 . ..........................................................................................................................................................'),'left');        
       $pdf->addTextWrap($Left_Margin,$YPos-140,900,$FontSize,_('Time spent for the study : .....................................................................................................................................................'),'left');        
       $pdf->addTextWrap($Left_Margin,$YPos-160,900,$FontSize,_('Name of Officer represented by the Institution'),'left');        
       $pdf->addTextWrap($Left_Margin+80,$YPos-180,900,$FontSize,_('1 . ..........................................................................................................................................................'),'left');        
       $pdf->addTextWrap($Left_Margin+80,$YPos-200,900,$FontSize,_('2 . ...........................................................................................................................................................'),'left');        
       $pdf->rect(40,250,520,150);  
       $pdf->addTextWrap($Left_Margin+10,$YPos-230,800,$FontSize,_('GENERAL OBSERVATION'),'left');               
       $pdf->line($Left_Margin+10,$YPos-232,$Left_Margin+125,$YPos-232);  
       $pdf->addTextWrap($Left_Margin,$YPos-400,800,$FontSize,_('UNDERTAKING OF THE BENEFICIARY'),'left');               
        $pdf->addTextWrap($Left_Margin+20,$YPos-420,1000,$FontSize,_('The above said details are true and correct.BIOTECH official has given a detailed explanation about the project.I understand '),'left');               
         $pdf->addTextWrap($Left_Margin,$YPos-440,1000,$FontSize,_('about the technology, merits and draw backs and implementation procedure of the project.'),'left');               
      
        $pdf->addTextWrap($Left_Margin,$YPos-480,800,$FontSize,_('Name ............................................................'),'left');               
          $pdf->addTextWrap($Left_Margin,$YPos-500,800,$FontSize,_('Destination ....................................................'),'left');               
          $pdf->addTextWrap($Left_Margin,$YPos-520,800,$FontSize,_('Signature .......................................................'),'left');               
      
           $pdf->addTextWrap($Left_Margin+450,$YPos-510,800,$FontSize,_('Office Seal'),'left');               
           $pdf->addTextWrap($Left_Margin+450,$YPos-520,800,$FontSize,_('of the Organisation'),'left');               
      
            $pdf->addTextWrap($Left_Margin,$YPos-560,1000,$FontSize,_('Date of submission of feasibility study........................................................ Date of Despatch of DPR.......................................................'),'left');               
              $pdf->addTextWrap($Left_Margin,$YPos-580,1000,$FontSize,_('Date on which detailed project preffered (DPR) .............................................................Action Taken on DPR.........................................'),'left');               
      
      
$pdf->newPage();
      $pdf->addTextWrap($Left_Margin+150,$YPos,500,$FontSize+3,_('DETAILS ABOUT THE SITE PROPOSED'),'centre');
      $pdf->addTextWrap($Left_Margin,$YPos-40,500,$FontSize+2,_('NATURE OF SOIL'),'left');
      $pdf->addTextWrap($Left_Margin+30,$YPos-70,500,$FontSize+1,_('Rocky'),'left');    $pdf->rect(110,92,15,8);
      $pdf->addTextWrap($Left_Margin+130,$YPos-70,500,$FontSize+1,_('Hard'),'left');    $pdf->rect(200,92,15,8);    
      $pdf->addTextWrap($Left_Margin+230,$YPos-70,500,$FontSize+1,_('Loose'),'left');    $pdf->rect(310,92,15,8);
      $pdf->addTextWrap($Left_Margin+330,$YPos-70,500,$FontSize+1,_('Marshy'),'left');    $pdf->rect(410,92,15,8);
      
      $pdf->addTextWrap($Left_Margin,$YPos-100,500,$FontSize+2,_('WATER LEVEL'),'left');  
            $pdf->addTextWrap($Left_Margin+130,$YPos-130,500,$FontSize,_('____________________________________ meter'),'left');
            
      $pdf->addTextWrap($Left_Margin,$YPos-160,500,$FontSize+2,_('AVAILABILITY OF WATER'),'left');
      $pdf->addTextWrap($Left_Margin+30,$YPos-180,500,$FontSize+1,_('Easily'),'left');    $pdf->rect(110,202,15,8);
      $pdf->addTextWrap($Left_Margin+130,$YPos-180,500,$FontSize+1,_('Not easily'),'left');    $pdf->rect(230,202,15,8);    
      $pdf->addTextWrap($Left_Margin+230,$YPos-180,500,$FontSize+1,_('Shortage'),'left');    $pdf->rect(330,202,15,8);
      
      $pdf->addTextWrap($Left_Margin,$YPos-220,500,$FontSize+2,_('PRESENT WATER SOURCE'),'left');
      $pdf->addTextWrap($Left_Margin+30,$YPos-240,500,$FontSize+1,_('Pipe'),'left');    $pdf->rect(110,260,15,8);
      $pdf->addTextWrap($Left_Margin+130,$YPos-240,500,$FontSize+1,_('Bore well'),'left');    $pdf->rect(230,260,15,8);    
      $pdf->addTextWrap($Left_Margin+230,$YPos-240,500,$FontSize+1,_('Pond'),'left');    $pdf->rect(330,260,15,8);
      $pdf->addTextWrap($Left_Margin+330,$YPos-240,500,$FontSize+1,_('Water Supply'),'left');    $pdf->rect(440,260,15,8);
      
      $pdf->addTextWrap($Left_Margin,$YPos-280,500,$FontSize+2,_('DISTANCE BETWEEN GAS UTILIZATION POINT AND PLANT SITE'),'left');  
            $pdf->addTextWrap($Left_Margin+130,$YPos-310,500,$FontSize,_('____________________________________ meter'),'left');
            
      $pdf->addTextWrap($Left_Margin,$YPos-340,500,$FontSize+2,_('PROPOSED SITE PLAN'),'left');   
      $pdf->rect(38,380,500,350);   
      $pdf->addTextWrap($Left_Margin,$YPos-370,500,$FontSize+1,_('Rough plan shown from the proposed site to gas utilisation point'),'left');
      
           
           
    $pdf->OutputD($_SESSION['DatabaseName'] . '_Feasibility Report_' . Date('Y-m-d') . '.pdf');
    $pdf->__destruct();
    
   //
    function PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription) {
  
  
      if ($PageNumber>1){
        $pdf->newPage();
    }
    
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
     //$pdf->addJpegFromFile($_SESSION['LogoFile'] ,$XPos+20,$YPos-50,0,60);

    $pdf->addTextWrap($Left_Margin,$YPos,100,$FontSize+4,_('BIOTECH'),'left'); 
        
        $pdf->addTextWrap($Left_Margin,$YPos-10,100,$FontSize,_('Post Box. No 520, M. P. Appan Road'),'left');
        $pdf->addTextWrap($Left_Margin,$YPos-20,100,$FontSize,_('Vazhuthacadu, Thycaud P.O.'),'left');
        $pdf->addTextWrap($Left_Margin,$YPos-30,100,$FontSize,_('Thiruvananthapuram-695 014, KERALA'),'left');        
        
        $pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos-25 ,90,$FontSize,Date($_SESSION['DefaultDateFormat']), 'right');
        
        $pdf->line($Left_Margin,$YPos-32,$Left_Margin+514,$YPos-32); 
        
         $pdf->addTextWrap($Left_Margin+200,$YPos-60,200,$FontSize+4,_('Feasibility Study Report'),'centre');   
          $pdf->line($Left_Margin+200,$YPos-62,$Left_Margin+340,$YPos-62);     
  
          $pdf->rect(38,114,15,7); 
          $pdf->addTextWrap($Left_Margin+20,$YPos-90,200,$FontSize,_('INTEGRATED WASTE MANAGEMENT PROGRAMME(IWMP)'),'left');
          $pdf->rect(38,114,15,7);  
             $pdf->addTextWrap($Left_Margin+20,$YPos-110,300,$FontSize,_('WASTE TO ENERGY PROGRAMME(WEP)'),'left');
              $pdf->rect(38,114,15,7);  
             $pdf->addTextWrap($Left_Margin+20,$YPos-130,300,$FontSize,_('INSTITUTIONAL BIOGAS PROGRAMME(IBP)'),'left'); 
             
             $pdf->line($Left_Margin,$YPos-135,$Left_Margin+516,$YPos-135);     
             
             
              $pdf->addTextWrap($Left_Margin+50,$YPos-160,700,$FontSize,_('NAME OF THE INSTITUTION      : .........................................................................................'),'left');  
              $pdf->addTextWrap($Left_Margin+50,$YPos-180,700,$FontSize,_('TITLE OF PROJECT                     : ........................................................................................'),'left');  
              $pdf->addTextWrap($Left_Margin+50,$YPos-200,700,$FontSize,_('Postal Address                              : ........................................................................................'),'left');  
              $pdf->addTextWrap($Left_Margin+185,$YPos-220,400,$FontSize,_('                                                                                   : ........................................................................................'),'left');  
              $pdf->addTextWrap($Left_Margin+185,$YPos-240,400,$FontSize,_('                                                                                   : ........................................................................................'),'left');  
              $pdf->addTextWrap($Left_Margin+185,$YPos-260,400,$FontSize,_('                                                                                    : ........................................................................................'),'left');  
              $pdf->addTextWrap($Left_Margin+50,$YPos-280,400,$FontSize,_('Contact Telephone Nos'),'left');  
              $pdf->addTextWrap($Left_Margin+185,$YPos-300,400,$FontSize,_('1 .........................................................................................'),'left');
              $pdf->addTextWrap($Left_Margin+185,$YPos-320,400,$FontSize,_('2 .........................................................................................'),'left');   
              $pdf->addTextWrap($Left_Margin+50,$YPos-340,400,$FontSize,_('Head of the Organisation'),'left');   
              $pdf->addTextWrap($Left_Margin+185,$YPos-360,700,$FontSize,_('Name  .................................................................................'),'left');   
              $pdf->addTextWrap($Left_Margin+185,$YPos-380,700,$FontSize,_('Designation   ......................................................................'),'left');   
              $pdf->addTextWrap($Left_Margin+50,$YPos-450,700,$FontSize,_('ROUTE TO THE ORGANISATION FROM THE NEAREST TOWN'),'left');   
              $pdf->line($Left_Margin+50,$YPos-453,$Left_Margin+314,$YPos-453);
               
               
               
                      
           
        $PageNumber++; 
}
     
  
  
  
  
?>
