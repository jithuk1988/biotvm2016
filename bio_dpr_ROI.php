<?php
 
  
   $PageSecurity = 80;  
  include('includes/session.inc');
  $PaperSize ='A5';    
  
  include('includes/PDFStarter.php');
    $pdf->addInfo('Title',_('Inventory Quantities Report'));
    $pdf->addInfo('Subject',_('Parts With Quantities'));
    $FontSize=9;
    $PageNumber=1;
    $line_height=12;

    $Xpos = $Left_Margin+1;
    $WhereCategory = " ";
    $CatDescription = " ";
    
 //-------------Data Retreival---------------------//     
       
 // $sql1="select * from bio_leads where leadstatus=2";
//  $result1=DB_query($sql1,$db);
//  while($row1=DB_fetch_array($result1))
//  {
//      $leadid=$row1['leadid'];
//      $leaddate=$row1['leaddate'];
//      $custid=$row1['cust_id'];
//  }
//  
//  $sql2="select * from bio_cust where bio_cust.cust_id=$custid";  
//  $result2=DB_query($sql2,$db);
//  while($row2=DB_fetch_array($result2))
//  {
//      $name=$row2['custname'];
//      $houseno=$row2['houseno'];
//      $area=$row2['area1'];
//      $housename=$row2['housename'];
//      
//  }   
       
//-------------------------------------------------
            
       PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                $Page_Width,$Right_Margin,$CatDescription);

    $FontSize=8; 
    
//---------------Display data Retrieved----------// 

              $pdf->addTextWrap($Left_Margin+20,$YPos-100,100,$FontSize,$name,'',0);
              $pdf->addTextWrap($Left_Margin+20,$YPos-110,100,$FontSize,$houseno,'',0); 
              $pdf->addTextWrap($Left_Margin+20,$YPos-120,100,$FontSize,$area,'',0); 
              $pdf->addTextWrap($Left_Margin+280,$YPos-180,100,$FontSize,$housename,'',0); 
              $pdf->addTextWrap($Left_Margin+100,$YPos-190,100,$FontSize,$leaddate,'',0);
                        
                     
   if ($YPos < $Bottom_Margin + $line_height){                                                       //------if data exeeds page height------//
        PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
        $Right_Margin,$CatDescription);
    }
          
           $pdf->newPage();
                                             
          // $pdf->addTextWrap($Left_Margin,$YPos-560,140,$FontSize+2,_('Net income in the first year'),'left');                              $pdf->addTextWrap($Left_Margin+200,$YPos-530,140,$FontSize+2,_('-'),'left');
//           $pdf->addTextWrap($Left_Margin,$YPos-580,140,$FontSize+2,_('Income from the second year onwards'),'left');                       $pdf->addTextWrap($Left_Margin+200,$YPos-530,140,$FontSize+2,_('-'),'left');
//          
           
           
           
             
          // $pdf->addTextWrap($Left_Margin+240,$YPos-50,140,$FontSize+3,_('Total'),'left');  
//           
//           $pdf->addTextWrap($Left_Margin+20,$YPos-130,140,$FontSize+3,_('Total Price'),'left');
//           $pdf->addTextWrap($Left_Margin+20,$YPos-150,140,$FontSize+3,_('Subsidy of '),'left');
//           $pdf->addTextWrap($Left_Margin+20,$YPos-170,140,$FontSize+3,_('Net amount '),'left');

//           $pdf->addTextWrap($Left_Margin+20,$YPos-450,140,$FontSize+3,_('For Biotech I Pvt Ltd'),'left');
//           $pdf->addTextWrap($Left_Margin+20,$YPos-500,140,$FontSize+3,_('Marketing manager'),'left');

           
  //         $pdf->rect(60,90,300,140); 
//           $pdf->rect(60,110,300,100); 
//           $pdf->rect(60,130,300,60);
//           $pdf->rect(60,150,300,20);
//           
//           $pdf->rect(180,90,50,140);
//           $pdf->rect(280,90,80,140);
 
//-----------------------------------------------

  if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
    
    $pdf->OutputD($_SESSION['DatabaseName'] . '_DPR Covering letter_' . Date('Y-m-d') . '.pdf');
    $pdf->__destruct();
    
        function PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription) {
                         
          if ($PageNumber>1){
          $pdf->newPage();
          
    }
    
    $line_height=12                                                                                                   ;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
        
        // $pdf->addTextWrap(150,$YPos,140,$FontSize+3,_('DPR Covering letter'),'center');  
//        
//         $Xpos = $Left_Margin+5;
//         $YPos -=(2*$line_height); 
//         
//         
//         $pdf->addTextWrap($Left_Margin,$YPos-10,100,$FontSize,_('From'), 'left');
//         
//         $pdf->addTextWrap($Left_Margin+20,$YPos-30,100,$FontSize+1,_('BIOTECH'),'left'); 
//         $pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos-20 ,90,$FontSize,Date($_SESSION['DefaultDateFormat']), 'right');
//         
//        $pdf->addTextWrap($Left_Margin+20,$YPos-40,100,$FontSize-2,_('Post Box. No 520, M. P. Appan Road'),'left');
//        $pdf->addTextWrap($Left_Margin+20,$YPos-50,100,$FontSize-2,_('Vazhuthacadu, Thycaud P.O.'),'left');
//        $pdf->addTextWrap($Left_Margin+20,$YPos-60,100,$FontSize-2,_('Thiruvananthapuram-695 014, KERALA'),'left');         
//         
//         $pdf->addTextWrap($Left_Margin,$YPos-90,100,$FontSize,_('To'), 'left');  
//      
//         $pdf->addTextWrap($Left_Margin,$YPos-160,100,$FontSize,_('Dear Sir'), 'left');
//         $pdf->addTextWrap($Left_Margin,$YPos-180,300,$FontSize,_('Sub:    Project proposal for solid waste processing plant for your house'), 'left');             
//         $pdf->addTextWrap($Left_Margin,$YPos-190,300,$FontSize,_('Ref.:    Your enquiry on '), 'left');             
//            
//         $pdf->addTextWrap($Left_Margin,$YPos-210,800,$FontSize,_('Based on the enquiry, we are pleased to submit our project proposal for installing a solid waste treatment'), 'left');            
//         $pdf->addTextWrap($Left_Margin,$YPos-220,800,$FontSize,_('plant for your house.The detailed project proposal attached consists of'), 'left');
//         
//         $pdf->addTextWrap($Left_Margin+30,$YPos-260,200,$FontSize,_('1.    Detailed costing of the project'), 'left');   
//         $pdf->addTextWrap($Left_Margin+30,$YPos-270,200,$FontSize,_('2.    Process diagram <Only in case where it is applicable>'), 'left');
//         $pdf->addTextWrap($Left_Margin+30,$YPos-280,200,$FontSize,_('3.    Block diagram of the plant <only if applicable>'), 'left');        
//                     
//        
             
//                     
//         $pdf->addTextWrap($Left_Margin,$YPos-400,200,$FontSize,_('We shall implement the system within  _______________.'), 'left'); 
//         $pdf->addTextWrap($Left_Margin,$YPos-420,200,$FontSize,_('Looking forward to receive your valuable order. '), 'left');            
//         $pdf->addTextWrap($Left_Margin,$YPos-470,100,$FontSize,_('For Biotech I Pvt Ltd '), 'left'); 
//         $pdf->addTextWrap($Left_Margin,$YPos-520,100,$FontSize,_('Marketing manager '), 'left');            
           $pdf->addTextWrap($Left_Margin,$YPos,140,$FontSize+3,_('Return on investment(ROI)of the project'),'Left');
           
           $pdf->addTextWrap($Left_Margin,$YPos-30,150,$FontSize+2,_('Waste treatment capacity of plant per day'),'left');                  $pdf->addTextWrap($Left_Margin+240,$YPos-30,50,$FontSize+2,_('-'),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-50,200,$FontSize+2,_('Treatment of easily degradable waste per day'),'left');               $pdf->addTextWrap($Left_Margin+240,$YPos-50,50,$FontSize+2,_('-'),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-70,200,$FontSize+2,_('Treatment of slowly degradable waste per day'),'left');               $pdf->addTextWrap($Left_Margin+240,$YPos-70,50,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-90,140,$FontSize+2,_('Treatment of organic waste water per day'),'left');                   $pdf->addTextWrap($Left_Margin+240,$YPos-90,50,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-110,140,$FontSize+2,_('Generation of biogas per day'),'left');                              $pdf->addTextWrap($Left_Margin+240,$YPos-110,50,$FontSize+2,_('-'),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-130,140,$FontSize+2,_('Generataion of solid fertilizer per day '),'left');                  $pdf->addTextWrap($Left_Margin+240,$YPos-130,140,$FontSize+2,_('-'),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-150,140,$FontSize+2,_('Generation of liquid fertilizer per day'),'left');                   $pdf->addTextWrap($Left_Margin+240,$YPos-150,140,$FontSize+2,_('-'),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-170,140,$FontSize+2,_('Cost of 1 Cum biogas '),'left');                                     $pdf->addTextWrap($Left_Margin+240,$YPos-170,140,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-190,140,$FontSize+2,_('Cost of 1 Kg. solid manure'),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos-190,140,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-210,140,$FontSize+2,_('Cost of 1 litter liquid manure'),'left');                            $pdf->addTextWrap($Left_Margin+240,$YPos-210,140,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-230,140,$FontSize+2,_('Cost of 1 Kg.Auto fuel'),'left');                                    $pdf->addTextWrap($Left_Margin+240,$YPos-230,140,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-250,140,$FontSize+2,_('Cost of 1 unit Electricity'),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos-250,140,$FontSize+2,_('-'),'left');  
           $pdf->addTextWrap($Left_Margin,$YPos-270,140,$FontSize+2,_('Cost of 1 Kg LPG'),'left');                                          $pdf->addTextWrap($Left_Margin+240,$YPos-270,140,$FontSize+2,_('-'),'left');   
           $pdf->addTextWrap($Left_Margin,$YPos-290,140,$FontSize+2,_('1 Cum biogas as cooking gas'),'left');                               $pdf->addTextWrap($Left_Margin+240,$YPos-290,140,$FontSize+2,_('- ........ LPG'),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-310,140,$FontSize+2,_('Annual income from biogas '),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos-310,140,$FontSize+2,_('-'),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-330,140,$FontSize+2,_('Annual income as electricity '),'left');                             $pdf->addTextWrap($Left_Margin+240,$YPos-330,140,$FontSize+2,_('-'),'left');  
           $pdf->addTextWrap($Left_Margin,$YPos-350,140,$FontSize+2,_('Annual income as auto fuel '),'left');                               $pdf->addTextWrap($Left_Margin+240,$YPos-350,140,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-370,140,$FontSize+2,_('Annual income-solid fertilizer'),'left');                            $pdf->addTextWrap($Left_Margin+240,$YPos-370,140,$FontSize+2,_('-'),'left');    
           $pdf->addTextWrap($Left_Margin,$YPos-390,140,$FontSize+2,_('Annual income-liquid fertilizer'),'left');                           $pdf->addTextWrap($Left_Margin+240,$YPos-390,140,$FontSize+2,_('-'),'left');  
           $pdf->addTextWrap($Left_Margin,$YPos-410,140,$FontSize+2,_('Total income'),'left');                                              $pdf->addTextWrap($Left_Margin+240,$YPos-410,140,$FontSize+2,_('-'),'left');   
           $pdf->addTextWrap($Left_Margin,$YPos-430,140,$FontSize+2,_('Less wages for operation '),'left');                                 $pdf->addTextWrap($Left_Margin+240,$YPos-430,140,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-450,140,$FontSize+2,_('AMC expenses '),'left');                                             $pdf->addTextWrap($Left_Margin+240,$YPos-450,140,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-470,140,$FontSize+2,_('Depreciation '),'left');                                             $pdf->addTextWrap($Left_Margin+240,$YPos-470,140,$FontSize+2,_('-'),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-490,140,$FontSize+2,_('Net income '),'left');                                               $pdf->addTextWrap($Left_Margin+240,$YPos-490,140,$FontSize+2,_('-'),'left');  
           $pdf->addTextWrap($Left_Margin,$YPos-510,140,$FontSize+2,_('Net investment'),'left');                                            $pdf->addTextWrap($Left_Margin+240,$YPos-510,140,$FontSize+2,_('-'),'left');  
           $pdf->addTextWrap($Left_Margin,$YPos-530,140,$FontSize+2,_('Income tax depreciation benefits'),'left');                          $pdf->addTextWrap($Left_Margin+240,$YPos-530,140,$FontSize+2,_('-'),'left'); 
           $pdf->addTextWrap($Left_Margin,$YPos-550,140,$FontSize+2,_('(80% of investment)'),'left');           
                  

       }
                     
     
?>

  
  
  
  
  
  
  

