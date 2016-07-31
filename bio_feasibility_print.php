<?php
$PageSecurity = 80;
/* $Id: InventoryQuantities.php 4555 2011-04-19 10:18:49Z daintree $ */

// InventoryQuantities.php - Report of parts with quantity. Sorts by part and shows
// all locations where there are quantities of the part

include('includes/session.inc');
$PaperSize ='A4';  
include('includes/PDFStarter.php');   
    $FontSize=12; 
    $PageNumber=1; 
    $line_height=16;
    $Xpos = $Left_Margin+5;  
    $YPos= $Page_Height-$Top_Margin;   
    $YPos -=(2*$line_height); 
    $WhereCategory = " ";
    $CatDescription = " ";      
 
 
if($_POST['PdfFormat']==1){    
//=================================================kuiiiii====================================================================//  
   
    $img1='companies/'.$_SESSION['DatabaseName'].'/PNG/logo PNG.png';
    $pdf->addJpegFromFile($img1,$XPos+380,$YPos-=$line_height,0,55);
    $img2= 'companies/'.$_SESSION['DatabaseName'].'/PNG/B ONLY.png';
    $pdf->addJpegFromFile($img2,$XPos+40,$YPos,0,55);  
    $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);

  //==========================================================================================================================//
}    
    

//If ($_GET['leadid']!="") {  
//    $leadid=$_GET['leadid'];
//    $printtype=$_GET['printtype']; 
//    $email=$_GET['email'];  


If ($_POST['LeadID']!="") {  
    $leadid=$_POST['LeadID'];
    $printtype=$_POST['printtype']; 
    $email=$_POST['submit'];
    $fid=$_POST['FSID'];
    
    
    $sql_update="UPDATE bio_fsproposal SET fp_refno='".$_POST['refno']."', printtype='".$_POST['printtype']."',printdate='".Date('Y-m-d')."' WHERE leadid='".$leadid."' AND fs_propono='".$_POST['FSID']."'";
    DB_query($sql_update,$db);
    $sql_cust="UPDATE bio_cust SET bio_cust.custname='".$_POST['orgname']."', bio_cust.contactperson='".$_POST['cont']."',bio_cust.area1='".$_POST['area1']."',bio_cust.headdesig='".$_POST['desg']."' WHERE cust_id='".$_POST['custid']."'";
    DB_query($sql_cust,$db);  


 $sql="SELECT * FROM bio_leads WHERE leadid=".$leadid;
      $result=DB_query($sql,$db);
      $myrow=DB_fetch_array($result);
      $custid=$myrow['cust_id'];  
      $date=ConvertSQLDate($myrow['leaddate']) ;
      $rmdailykg=$myrow['rmdailykg'];
      $reference= "Ref: -  Enquiry date on  ".$date;
      $sqlr="select fp_amount,
                    fp_date,
                    printdate,
                    leadid,
                    fs_propono,
                    fp_refno      
              from  bio_fsproposal 
              where leadid=".$leadid;    
      $result=DB_query($sqlr, $db);     
      $myrow6=DB_fetch_array($result);
      $custid=$myrow['cust_id'];  
      $fpamount=$myrow6['fp_amount'];
      $date1=ConvertSQLDate($myrow6['fp_date']);
      $letterdate=ConvertSQLDate($myrow6['printdate']); 
      $number=$myrow6['fs_propono']; 
      $refno=$myrow6['fp_refno'];  
      
      $sql_cust="SELECT * FROM bio_cust WHERE cust_id=".$custid;
      $result_cust=DB_query($sql_cust,$db);
      $myrow_cust=DB_fetch_array($result_cust); 
      if($myrow_cust['nationality']==1)
      {   
          $sql_policy="SELECT value FROM bio_changepolicy WHERE policyid=46"; 
          if($myrow_cust['state']==14)
          {
          $sql_policy="SELECT value FROM bio_changepolicy WHERE policyid=48";    
          } 
          
          $curr="INR";
      }else{
          $sql_policy="SELECT value FROM bio_changepolicy WHERE policyid=47";  
          $curr="USD";
      }
      
        $result_policy=DB_query($sql_policy,$db);
        $row_policy=DB_fetch_array($result_policy);
        $value=$row_policy['value'];
        $value=number_format($value,2);
      
        
 //   $string= "A sum of ".$curr." ".number_format($fsamount,2)." /- may be paid in advance for the visit, data collection and preparation of feasibility study proposal.  ";  
      $productservicesid=$myrow['productservicesid'];
      $enqtypeid=$myrow['enqtypeid']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $schemeid=$myrow['schemeid'];
      $teamid=$myrow['teamid'];
      $sourceid=$myrow['sourceid'];
      $investmentsize=$myrow['investmentsize'];
      $remarks=$myrow['remarks'];
      $status=$myrow['status']; 


             
$sql1="  SELECT
      `bio_cust`.`custname`
    , `bio_cust`.`contactperson`    
    , `bio_cust`.`area1`    
    , `bio_cust`.`headdesig`
    , `bio_cust`.`housename`
    , `bio_leads`.`leaddate`
    , `bio_district`.`district`
FROM
    bio_cust
    LEFT JOIN `bio_district` 
        ON (`bio_cust`.`nationality` = `bio_district`.`cid`) AND (`bio_cust`.`state` = `bio_district`.`stateid`) AND (`bio_cust`.`district` = `bio_district`.`did`)
    LEFT JOIN `bio_leads` 
        ON (`bio_cust`.`cust_id` = `bio_leads`.`cust_id`)
WHERE bio_leads.leadid=" .$leadid;

             


 $result1=DB_query($sql1,$db);
 $row1=DB_fetch_array($result1);  
 $cname=$row1['custname'];
 $contactperson=$row1['contactperson'];
 $designation1=$row1['headdesig'];   
 $area=$row1['area1'];      
 $hname=$row1['housename'];      
 $district=$row1['district'];        
 $pieces=$myrow1['custphone'];  $phpieces = explode("-", $pieces,2);  $custcode=$phpieces[0];if($custcode==0 || $custcode==""){$custcode=0;}  $custphone=$phpieces[1];
 $custmobile=$myrow1['custmob'];
 $custemail=$myrow1['custmail'];
       
      $sql_emp="SELECT bio_emp.empname,
                           bio_designation.designation
                      FROM bio_emp,
                           www_users,
                           bio_designation
                     WHERE www_users.empid=bio_emp.empid 
                       AND bio_emp.designationid=bio_designation.desgid 
                       AND www_users.userid='".$_SESSION['UserID']."'"; 
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
      $empname=$myrow_emp['empname'];
      $designation=$myrow_emp['designation'];    
      
         
//--------------------------------------------------------
if($custphone==""){$custphone=0;}if($custmobile==""){$custmobile=0;} if($custemail==""){$custemail=0;}


//==========================================================bio_feedstocks.feedstocks, AND bio_feedstocks.id=$feedstockid
       
 $sq4="SELECT bio_enquirytypes.enquirytype,    
        bio_outputtypes.outputtype,
        bio_schemes.scheme,
        bio_productservices.productservices,
        bio_leadsources.sourcetypeid,
        bio_leadsourcetypes.leadsourcetype,
        stockcategory.categorydescription
        FROM bio_enquirytypes, bio_proposal,
        bio_feedstocks, 
        bio_outputtypes,         
        bio_schemes,
        bio_productservices,
        bio_leadsources,
        bio_leadsourcetypes,stockcategory
        WHERE bio_enquirytypes.enqtypeid=$enqtypeid  AND bio_outputtypes.outputtypeid='$outputtypeid' AND stockcategory.categoryid='$productservicesid' AND bio_leadsources.id=$sourceid AND bio_leadsourcetypes.id=bio_leadsources.sourcetypeid";
      $result4=DB_query($sq4,$db); 
      $myrow4=DB_fetch_array($result4);
//    PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
//                $Page_Width,$Right_Margin,$CatDescription);
//    $FontSize=8;

     $inter= $_GET['advance']; 
     

   
   
   
//-----------------------------Online feasibility study----------------------------------------   

if($printtype==7)
{
    
      $FontSize=11;  $line_height=15;  
      
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,50,$FontSize+1,_('No :'), 'left');$pdf->addTextWrap($Left_Margin+50,$YPos,100,$FontSize,$refno,'',0);        
   $pdf->addTextWrap($Left_Margin+430,$YPos,100,$FontSize+1,date('d/m/Y'), 'left');  
      
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+20,50,$FontSize+1,_('To,'), 'left');     
            $string_person='The'." ".$designation1; 
            $string='Kind Attention:'." ".$contactperson;
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+15,300,$FontSize,$string_person,'',0);                              
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,300,$FontSize,$cname,'',0);    
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,300,$FontSize,$area,'',0);                                                                                
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,300,$FontSize,$district,'',0);    
            $pdf->addTextWrap($Left_Margin+200,$YPos,300,$FontSize,$string,'',0);  
     
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+20,50,$FontSize,_('Dear Sir,'), 'left');
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+15,800,$FontSize,_('Sub: - BIOTECH - Feasibility study for the Installation of Bio Gas Plant reg.'), 'left');
if($letterdate!="00/00/0000")  { 
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height,800,$FontSize,_('Ref: -  Your letter of interest '), 'left');  
   $pdf->addTextWrap($Left_Margin+200,$YPos,100,$FontSize,$letterdate,'',0);
}else{
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height,500,$FontSize,$reference,'',0);  
}
 
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+20,900,$FontSize,_('Thank  you  for  your  interest  in our  projects.  We  are very  much interested to associate'), 'left');
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('ourselves with you for the effective implementation of Waste to Energy projects.'), 'left'); 


   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+15,900,$FontSize,_('Before  the installation  of the  plant, a site  visit and  a feasibility  study has  necessarily be'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('conducted. This process is essential for collecting the required data for preparing feasibility report'), 'left');    
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('which includes estimate, technical details such as size and appropriate model of the plant.'), 'left');     
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('This will also help for availing maximum eligible subsidy / grant for the effective implementation of'), 'left');   
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('the project with minimum investment.'), 'left');  
   
   
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+20,900,$FontSize,_('We  have  the  best  in  class  ERP  system  to  facilitate  online  feasibility  study  at  client’s '), 'left');   
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('premises. We could provide online help to your staff for collecting  all required data and register it'), 'left');                                                                                           
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('in the  ERP system through a user-friendly web page of the ERP. It minimizes the effort, time and'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('money required for the feasibility study to offer you cost-effective project proposal.'), 'left'); 
   
   $string= "feasibility study charges amounting to ".$fpamount."/-";  
   $string2= "by way of bank transfer. The date of conducting the";
   
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+20,900,$FontSize,_('For   conducting   an   online   feasibility   study,   you   are   requested  to  pay   an  upfront '), 'left');   
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,$string,'',0);    $pdf->addTextWrap($Left_Margin+242,$YPos,900,$FontSize,$string2,'',0);                                                                                       
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('online  feasibility  study  will be  conveyed to  you  soon on  receipt  your  reply  together with  the'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('payment   details.    This    amount    is   payable    by    way    of    bank    transfer   in   favor    of'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('M/S.  BIOTECH  Renewable  Energy  Pvt.Lt.,  Trivandrum.'), 'left'); 
   
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+15,900,$FontSize,_('Preliminary information  about  our project is enclosed for your kind information and'), 'left');  
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('decision.'), 'left');  
   
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+15,900,$FontSize,_('Please feel free to contact us for any further information if any.'), 'left'); 
   
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+17,900,$FontSize,_('Yours Sincerely'), 'left');
   
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+28,100,$FontSize+2,$empname,'',0);    
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,140,$FontSize+1,$designation,'',0);   
   
   
 }else{
     
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,50,$FontSize+1,_('No :'), 'left');$pdf->addTextWrap($Left_Margin+50,$YPos,100,$FontSize,$refno,'',0);        
   $pdf->addTextWrap($Left_Margin+430,$YPos,100,$FontSize+1,date('d/m/Y'), 'left');  

   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+30,50,$FontSize+1,_('To,'), 'left');     
            $string_person='The'." ".$designation1; 
            $string='Kind Attention:'." ".$contactperson;
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+15,300,$FontSize,$string_person,'',0);                              
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,300,$FontSize,$cname,'',0);    
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,300,$FontSize,$area,'',0);                                                                                
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,300,$FontSize,$district,'',0);    
            $pdf->addTextWrap($Left_Margin+200,$YPos,300,$FontSize,$string,'',0); 
  
            
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+30,50,$FontSize,_('Dear Sir,'), 'left');
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+15,800,$FontSize,_('Sub: - BIOTECH - Feasibility study for the Installation of Bio Gas Plant reg.'), 'left');
if($letterdate!="00/00/0000")  { 
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height,800,$FontSize,_('Ref: -  Your letter of interest '), 'left');  
   $pdf->addTextWrap($Left_Margin+200,$YPos,100,$FontSize,$letterdate,'',0);
}else{
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height,500,$FontSize,$reference,'',0);  
}
 
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+25,900,$FontSize,_(' Thank  you  for  your   interest  in  our  projects.   We  are  very  much  interested  to'), 'left');
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('associate ourselves with you for the effective implementation of Waste to Energy projects.'), 'left'); 


   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+15,900,$FontSize,_(' Before the installation of the plant,  a site visit and a feasibility study has necessarily'), 'left'); 
   if($printtype==3 || $printtype==4){ 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('be  conducted.   This  study  is  essential  for  collecting  the  required  data  for  preparing '), 'left');    
   }elseif($printtype==1 || $printtype==2 || $printtype==5 || $printtype==6){
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('be conducted.   This  process is  essential  for  collecting  the  required data  for preparing '), 'left');    
   }
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('feasibility   report   which  includes  estimate,   technical   details    such    as    size   and '), 'left');     
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('appropriate   model  of   the  plant.  This   will  also  help   for  availing   maximum   eligible '), 'left');   
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('subsidy / grant  for the  effective  implementation  of  the project with minimum investment.'), 'left');  
   
   
if($printtype==1 || $printtype==2 ){
   $string= "upfront feasibility  study  charge  amounting  to    ".number_format($fpamount,2)." /-";
   $string2= "together   with   local   travel";
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+20,900,$FontSize,_('For conducting  the  feasibility  study  and  site  visit,  you  are  requested  to pay  an'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,$string,'',0); $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize,$string2,'',0);
   if($myrow_cust['nationality']==1){
   $img6='companies/'.$_SESSION['DatabaseName'].'/rupee_symbol.jpg';   
   }else{
   $img6='companies/'.$_SESSION['DatabaseName'].'/dollar_symbol.jpg';  
   }   
   $pdf->addJpegFromFile($img6,$Left_Margin+271,$YPos,0,9); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('arrangements for  our technical team.  The date of our  visit will  be conveyed to you soon'), 'left');
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('on receipt of  your reply  along  with the feasibility study charges.  This  amount is payable'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('by way of bank transfer in favor of M/S. BIOTECH Renewable Energy Pvt.Lt., Trivandrum.'), 'left');   
}elseif($printtype==3 || $printtype==4 ){
   $string= "upfront   feasibility  study  charge  amounting  to    ".$fpamount."/-";
   $string2= "along  with  to  and  fro  travel";   
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+20,900,$FontSize,_('For  conducting  the  feasibility  study  and  site  visit,  you  are requested  to pay  an '), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,$string,'',0);  $pdf->addTextWrap($Left_Margin+340,$YPos,900,$FontSize,$string2,'',0);
   if($myrow_cust['nationality']==1){
   $img5='companies/'.$_SESSION['DatabaseName'].'/rupee_symbol.jpg';   
   }else{
   $img5='companies/'.$_SESSION['DatabaseName'].'/dollar_symbol.jpg';    
   }  
   $pdf->addJpegFromFile($img5,$Left_Margin+280,$YPos,0,9); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('expenses  and accommodation  arrangements  for  our  technical team  from  Trivandrum.'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('The date  of  our visit  will  be  conveyed  to  you  on receipt of  your reply  along  with  the'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('feasibility study  charges.  This  amount is  payable  by  way  of bank  transfer  in favor  of'), 'left');
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('M/S. BIOTECH Renewable Energy Pvt.Lt., Trivandrum.'), 'left'); 
}elseif($printtype==5 || $printtype==6){
   $string= "For  purposes of conducting the  feasibility study, we  charge a sum of    ".$value."/-" ;   
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+20,900,$FontSize,$string,'',0);
   $img4='companies/'.$_SESSION['DatabaseName'].'/rupee_symbol.jpg';     
   $pdf->addJpegFromFile($img4,$Left_Margin+425,$YPos,0,9); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('Considering  your  special  request and  commitment  for  preserving the environment, we'), 'left'); 
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_(' shall do the  feasibility  study  with out  upfront payment.  You are,  however, requested to'), 'left');  
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('make travel and accommodation arrangements for our technical team from Trivandrum.'), 'left'); 
}
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+15,900,$FontSize,_('Preliminary information  about  our project is enclosed for your kind information and'), 'left');  
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,900,$FontSize,_('decision.'), 'left');  
   
   $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+15,900,$FontSize,_('Please feel free to contact us for any further information if any.'), 'left'); 
   
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+17,900,$FontSize,_('Yours Sincerely'), 'left');
            
                                           
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+28,100,$FontSize+2,$empname,'',0);    
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,140,$FontSize+1,$designation,'',0);   
    
 }
     
if($_POST['PdfFormat']==1){   
             //  echo"gggg";
    $pdf->line($Left_Margin,$YPos-=$line_height,$Left_Margin+534,$YPos-1); 
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,480,$FontSize-1,_('www.biotech-india.org'), 'center');   
       
    $img3='companies/'.$_SESSION['DatabaseName'].'/PNG/FOOTER.png';
    $pdf->addJpegFromFile($img3,$XPos+55,$YPos-=$line_height+30,500,30);  
}     

    $filename="Feasibility_Request_".Date('Y-m-d')."_".$number.".pdf"; 
    
    if($email==1)
    {

    $pdf->Output("fproposals/" .$filename,'F');  
    
    
            
            echo"<input type=hidden name=filename id=filename value='$filename'>";
            ?>
     <script>  
                      var leadid=<?php echo $leadid;?>;
                      var fid=<?php echo $fid;?>;       
                      var filename=document.getElementById("filename").value;     // alert(filename);
                      window.location='bio_send_fp_attachment.php?leadid=' +leadid+ '&filename='+filename+'&fsid='+fid;
     </script>
<?php    
    }  
    else
    {
    $pdf->OutputD($_SESSION['DatabaseName'] .$filename);         
    }
   $pdf->__destruct();
   
   
   
} 
?>
