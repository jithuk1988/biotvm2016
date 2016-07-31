<?php
  $PageSecurity = 80;  
  include('includes/session.inc');
  $PaperSize ='A5';    
  
  include('includes/PDFStarter.php');
//    $pdf->addInfo('Title',_('Inventory Quantities Report'));
//    $pdf->addInfo('Subject',_('Parts With Quantities'));
    $FontSize=9;
    $PageNumber=1;
    $line_height=12;

    $Xpos = $Left_Margin+1;
    $WhereCategory = " ";
    $CatDescription = " ";
    
 //-------------Data Retreival---------------------//     

  if($_GET['cpid']==""){
      $_GET['cpid']=$_SESSION['cpid'];
      unset($_SESSION['cpid']);  
  }
  $sql1="select bio_leads.leadid,
                bio_leads.cust_id,
                bio_leads.leaddate,
                bio_conceptproposal.plant 
        from bio_leads,bio_conceptproposal 
        where bio_leads.leadid=bio_conceptproposal.lead_id
        and bio_conceptproposal.conceptproposal_id=".$_GET['cpid'];
  $result1=DB_query($sql1,$db);
  while($row1=DB_fetch_array($result1))
  {
      $leadid=$row1['leadid'];
      $leaddate=$row1['leaddate'];
      $custid=$row1['cust_id'];
      $plantid=$row1['plant'];

      $plantid2=explode(',',$plantid);
      $n=sizeof($plantid2);
      $plants="";
      $plant_id="";
      $plantprice=0;
      for($i=0;$i<$n;$i++)        {

      $sql="SELECT description,materialcost,stockid
      FROM stockmaster
      WHERE stockid='$plantid2[$i]'";
      $result=DB_query($sql,$db);
      $myrow=DB_fetch_array($result);
      $plants=$myrow['description'].",".$plants;
      $plant_id=$myrow['stockid'].",".$plant_id;
      
      $sql_price="SELECT price
              FROM prices
             WHERE stockid='".$plantid2[$i]."'";
      $result_price=DB_query($sql_price,$db);
      $myrow_price=DB_fetch_array($result_price);
      $plantprice=$plantprice+$myrow_price['price'];
      

  }
  $plant_id = substr($plant_id,0,-1); 
  $plants = substr($plants,0,-1);
  
  $sql2="select * from bio_cust where bio_cust.cust_id=$custid";  
  $result2=DB_query($sql2,$db);
  while($row2=DB_fetch_array($result2))
  {
      $name=$row2['custname'];
      $houseno=$row2['houseno'];
      $area=$row2['area1'];
      $housename=$row2['housename'];
      
  }
  
  }   
       
//-------------------------------------------------
            
       PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                $Page_Width,$Right_Margin,$CatDescription);

    $FontSize=8; 
    
//---------------Display data Retrieved----------// 

              $pdf->addTextWrap($Left_Margin+20,$YPos-100,100,$FontSize,$name,'',0);
              $pdf->addTextWrap($Left_Margin+20,$YPos-110,100,$FontSize,$housename,'',0); 
              $pdf->addTextWrap($Left_Margin+20,$YPos-120,100,$FontSize,$area,'',0); 
              $pdf->addTextWrap($Left_Margin+218,$YPos-180,100,$FontSize,$housename,'',0); 
              $pdf->addTextWrap($Left_Margin+88,$YPos-190,100,$FontSize,$leaddate,'',0);
                        
                     
   if ($YPos < $Bottom_Margin + $line_height){                                                       //------if data exeeds page height------//
        PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
        $Right_Margin,$CatDescription);
    }
          
           $pdf->newPage();
           $pdf->addTextWrap(140,$YPos+10,140,$FontSize+3,_('Quotation'),'center');
          
           $pdf->addTextWrap($Left_Margin+20,$YPos-50,140,$FontSize+3,_('Description'),'left');
           $pdf->addTextWrap($Left_Margin+140,$YPos-50,140,$FontSize+3,_('Unit price'),'left');
           $pdf->addTextWrap($Left_Margin+190,$YPos-50,140,$FontSize+3,_('Quantity'),'left');   
           $pdf->addTextWrap($Left_Margin+240,$YPos-50,140,$FontSize+3,_('Total'),'left');  
           
           $pdf->addTextWrap($Left_Margin+20,$YPos-90,140,$FontSize+3,_('Total Price'),'left');

           $pdf->addTextWrap($Left_Margin+20,$YPos-450,140,$FontSize+3,_('For Biotech I Pvt Ltd'),'left');
           $pdf->addTextWrap($Left_Margin+20,$YPos-500,140,$FontSize+3,_('Marketing manager'),'left');

           
           
           $pdf->addTextWrap($Left_Margin+20,$YPos-70,140,$FontSize,$plants,'',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-70,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-70,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-70,140,$FontSize,$plantprice,'',0);
           
           //$pdf->addTextWrap($Left_Margin+20,$YPos-90,140,$FontSize,1,'',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-90,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-90,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-90,140,$FontSize,$plantprice,'',0);
           
      
           $pdf->rect(58,90,300,60); 
           $pdf->rect(58,110,300,20); 
           
           $pdf->rect(178,90,50,60);
           $pdf->rect(278,90,80,60);
 
//-----------------------------------------------

  if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
    
    $pdf->OutputD($_SESSION['DatabaseName'] . '_Concept Proposal_' . Date('Y-m-d') . '.pdf');
    $pdf->__destruct();
    
        function PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription) {
                         
          if ($PageNumber>1){
          $pdf->newPage();
          
    }
    
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
        
         $pdf->addTextWrap(150,$YPos,140,$FontSize+3,_('Concept Proposal'),'center');  
        
         $Xpos = $Left_Margin+5;
         $YPos -=(2*$line_height); 
         
         
         $pdf->addTextWrap($Left_Margin,$YPos-10,100,$FontSize,_('From'), 'left');
         
         $pdf->addTextWrap($Left_Margin+20,$YPos-30,100,$FontSize+1,_('BIOTECH'),'left'); 
         $pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos-20 ,90,$FontSize,Date($_SESSION['DefaultDateFormat']), 'right');
         
        $pdf->addTextWrap($Left_Margin+20,$YPos-40,100,$FontSize-2,_('Post Box. No 520, M. P. Appan Road'),'left');
        $pdf->addTextWrap($Left_Margin+20,$YPos-50,100,$FontSize-2,_('Vazhuthacadu, Thycaud P.O.'),'left');
        $pdf->addTextWrap($Left_Margin+20,$YPos-60,100,$FontSize-2,_('Thiruvananthapuram-695 014, KERALA'),'left');         
         
         $pdf->addTextWrap($Left_Margin,$YPos-90,100,$FontSize,_('To'), 'left');  
      
         $pdf->addTextWrap($Left_Margin,$YPos-160,100,$FontSize,_('Dear Sir'), 'left');
         $pdf->addTextWrap($Left_Margin,$YPos-180,300,$FontSize,_('Sub:    Project proposal for solid waste processing plant for'), 'left');             
         $pdf->addTextWrap($Left_Margin,$YPos-190,300,$FontSize,_('Ref.:   Your enquiry on '), 'left');             
            
         $pdf->addTextWrap($Left_Margin,$YPos-210,800,$FontSize,_('Based on the enquiry, we are pleased to submit our project proposal for installing a solid waste'), 'left');            
         $pdf->addTextWrap($Left_Margin,$YPos-220,800,$FontSize,_('treatment plant for your house. The concept project proposal attached consists of'), 'left');
         
         $pdf->addTextWrap($Left_Margin+30,$YPos-260,200,$FontSize,_('1.    Technical details and costing of the project'), 'left');   
           
                                          
                     
//         $pdf->addTextWrap($Left_Margin,$YPos-400,200,$FontSize,_('We shall implement the system within  _______________.'), 'left'); 
         $pdf->addTextWrap($Left_Margin,$YPos-420,200,$FontSize,_('Looking forward to receive your valuable order. '), 'left');            
         $pdf->addTextWrap($Left_Margin,$YPos-470,100,$FontSize,_('For Biotech Pvt Ltd '), 'left'); 
         $pdf->addTextWrap($Left_Margin,$YPos-520,100,$FontSize,_('Marketing manager '), 'left');            

         
         $PageNumber++;
         
         }
                     
         
?>
