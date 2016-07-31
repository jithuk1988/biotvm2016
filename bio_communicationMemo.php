<?php
  $PageSecurity = 80;  
  include('includes/session.inc');
  $PaperSize ='A4';    
  
  include('includes/PDFStarter.php');
    $FontSize=9; 
    $PageNumber=1;                  
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $Xpos = $Left_Margin+5;
    $YPos -=(2*$line_height);
    $WhereCategory = " ";
    $CatDescription = " ";
    
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,525,$FontSize+8,_('BIOTECH'), 'center');  
    $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);
    
    
$pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,525,$FontSize+2,_('Inter office Communication Memo'), 'center'); 

//----------------------Side panel----------------------------------------------------------------------------

$sql_side1="SELECT bio_emp.empname,
                   bio_designation.designation,
                   bio_office.office 
            FROM   bio_emp,bio_designation,bio_office 
            WHERE  bio_designation.desgid=bio_emp.designationid
            AND    bio_office.id=bio_emp.offid 
            AND    bio_emp.empid='".$_SESSION['empid']."'";
$result_side1=DB_query($sql_side1,$db);        
$row_side1=DB_fetch_array($result_side1);

                $desgname=$row_side1['empname'];
                $designation=$row_side1['designation'];
                $office=$row_side1['office'];

 
$pdf->addTextWrap($Left_Margin,$YPos-=30,100,$FontSize,_('Orginator'), 'center'); 
$pdf->line($Left_Margin,$YPos-2,$Left_Margin+120,$YPos-2); 
            $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,100,$FontSize-1,$desgname,'',0);  
            $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-5,100,$FontSize-1,$designation,'',0);
            $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-5,100,$FontSize-1,$office,'',0);

$pdf->line($Left_Margin,$YPos-185,$Left_Margin+120,$YPos-185);             
$pdf->addTextWrap($Left_Margin,$YPos-=200,100,$FontSize,_('Action By'), 'center'); 
$pdf->line($Left_Margin,$YPos-2,$Left_Margin+120,$YPos-2);  
            $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,100,$FontSize-1,_('Program Officer'), 'left'); 
            $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-5,100,$FontSize-1,_('Corporate Office'), 'left');  
           
$pdf->line($Left_Margin,$YPos-185,$Left_Margin+120,$YPos-185);
$pdf->addTextWrap($Left_Margin,$YPos-=200,100,$FontSize,_('Information to'), 'center');  
$pdf->line($Left_Margin,$YPos-2,$Left_Margin+120,$YPos-2); 
            $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,100,$FontSize-1,_('Business Head'), 'left'); 
            $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-5,100,$FontSize-1,_('Director'), 'left');      
            
            $pdf->line(160,730,160,30);
 
//------------------------------------------------------------------------------------------------------------- 
        $Page_Height=790;
        $YPos= $Page_Height-$Top_Margin;   
        $line_height=15;        
        $Xpos = $Left_Margin+130;
        $Tab=40;
        $YPos -=(2*$line_height);

  $pdf->line($Left_Margin,$YPos,$Xpos+400,$YPos);
  $pdf->addTextWrap($Xpos,$YPos-=$line_height+10,300,$FontSize,_('Sub : Consolidated documents from Beneficiaries'), 'left'); 
//  $pdf->line($Xpos-10,$YPos-5,$Xpos+400,$YPos-5);  
  
  $pdf->addTextWrap($Xpos,$YPos-=$line_height+30,350,$FontSize,_('Please find the enclosed list of documents collected, verified and forwarded to you.'), 'left');  
  
  
  
  $pdf->addTextWrap($Xpos,$YPos-=$line_height+40,395,$FontSize,_('No'), 'left');   
  $pdf->addTextWrap($Xpos+=$Tab,$YPos-=0,395,$FontSize,_('Beneficiary Name'), 'left'); 
  $pdf->addTextWrap($Xpos+=$Tab+90,$YPos-=0,395,$FontSize,_('Documents'), 'left'); 
  $pdf->addTextWrap($Xpos+=$Tab+80,$YPos-=0,395,$FontSize,_('Remarks'), 'left'); 
  
  $pdf->line($Left_Margin+130,$YPos-5,$Left_Margin+525,$YPos-5);  
  
  
  
  $sql="SELECT DISTINCT bio_documentlist.leadid,
               bio_cust.custname
                              
        FROM   bio_documentlist,bio_leads,bio_cust 
        WHERE  bio_documentlist.leadid=bio_leads.leadid
        AND    bio_cust.cust_id=bio_leads.cust_id   
        AND    bio_documentlist.status=2";
  $result=DB_query($sql,$db);
  $i=0;

  while($row=DB_fetch_array($result))
  {
         
      $Xpos = $Left_Margin+130;  
       
      $i++;
      $custname=$row['custname'];  
      $leadid=$row['leadid']; 
      $docname=$row['document_name'];
      
      $pdf->addTextWrap($Xpos,$YPos-=$line_height+15,100,$FontSize-1,$i,'',0);   
      $pdf->addTextWrap($Xpos+=$Tab,$YPos-=0,100,$FontSize-1,$custname,'',0);  
       
      
      $sql_doc="SELECT bio_document_master.document_name 
                FROM   bio_documentlist,bio_document_master
                WHERE  bio_documentlist.status=2
                AND    bio_documentlist.leadid=$leadid
                AND    bio_document_master.doc_no=bio_documentlist.docno";
      $result_doc=DB_query($sql_doc,$db); 
      $YPos+=$line_height;
      

      
      
      while($row_doc=DB_fetch_array($result_doc))
      {
          $docname=$row_doc['document_name'];
          $pdf->addTextWrap($Xpos=340,$YPos-=$line_height,100,$FontSize-1,$docname,'',0); 
                    
                    if ($YPos < $Bottom_Margin + $line_height+30)
                    {
                            $YPos=$Bottom_Margin - 10;
                            $page='Page No '.$PageNumber;
                            $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
                            $PageNumber++; 
                            $pdf->newPage();
                            
                            $YPos= $Page_Height+15;             //$Page_Height-$Top_Margin
                            $line_height=15;        
                            $Xpos = $Left_Margin+130; 
                            //$YPos -=(2*$line_height);
                            
//                                $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,525,$FontSize+8,_('BIOTECH'), 'center');  
//                                $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);

                                      $pdf->addTextWrap($Xpos,$YPos-=$line_height+40,395,$FontSize,_('No'), 'left');   
                                      $pdf->addTextWrap($Xpos+=$Tab,$YPos-=0,395,$FontSize,_('Beneficiary Name'), 'left'); 
                                      $pdf->addTextWrap($Xpos+=$Tab+90,$YPos-=0,395,$FontSize,_('Documents'), 'left'); 
                                      $pdf->addTextWrap($Xpos+=$Tab+80,$YPos-=0,395,$FontSize,_('Remarks'), 'left'); 
                                      
                                      $pdf->line($Left_Margin+130,$YPos-5,$Left_Margin+525,$YPos-5);  
                    }
    }       
}   

  

          
 
  $pdf->OutputD($_SESSION['DatabaseName'] . '_Communication Memo_' . Date('Y-m-d') . '.pdf');
  $pdf->__destruct();     
  
  
  $sql_update="UPDATE bio_documentlist SET forwardedDate='".Date('Y-m-d')."', forwardedBy='".$_SESSION['UserID']."', status=3 WHERE status=2";
  DB_query($sql_update,$db);
  
?>
