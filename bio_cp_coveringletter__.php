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
//=================================================kuiiiii====================================================================//  
  
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $Xpos = $Left_Margin+5;
    $YPos -=(2*$line_height);       
    $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
    $pdf->addJpegFromFile($img1,$XPos+392,$YPos-=$line_height,0,55);
    $img2= 'companies/'.$_SESSION['DatabaseName'].'/logof.jpg';
    $pdf->addJpegFromFile($img2,$XPos+35,$YPos,0,55);  
    $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);
    
  //==========================================================================================================================//
    
    
    
    $leadid=$_POST['LeadID'];
    $cpID=$_POST['CpID'];
    
    
   $sql1="select bio_leads.leadid,
                bio_leads.cust_id,
                bio_leads.leaddate,
                bio_leads.outputtypeid,  
                bio_conceptproposal.total_price,
                bio_conceptproposal.created_on,
                bio_conceptproposal.letter_no,
                 bio_conceptproposal.letter_date,            
                bio_conceptproposal.created_by,
                bio_conceptproposal.approved_by,
                bio_conceptproposal.signatory_by     
        from bio_leads,bio_conceptproposal 
        where bio_leads.leadid=bio_conceptproposal.lead_id
        and bio_conceptproposal.cp_id =".$cpID;
  $result1=DB_query($sql1,$db);
  while($row1=DB_fetch_array($result1))
  {
      $leadid=$row1['leadid'];
      $leaddate=ConvertSQLDate($row1['leaddate']);
      $outputtypeid=$row1['outputtypeid'];
      $custid=$row1['cust_id'];
      $budget=$row1['budget'];
      $createdon=$row1['createdon'];
      $createdby=$row1['cp_created'];
      $approvedby=$row1['cp_approved'];
      $signatoryby=$row1['signatory_by'];
      $total_plantprice=$row1['total_price'];  
      $letterno=$row1['letter_no'];
      $letterdate=ConvertSQLDate($row1['letter_date']);     
      $sql_emp="SELECT bio_emp.empname,
                           bio_designation.designation
                      FROM bio_emp,
                           www_users,
                           bio_designation
                     WHERE www_users.empid=bio_emp.empid 
                       AND bio_emp.designationid=bio_designation.desgid 
                       AND www_users.userid='".$signatoryby."'";
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
      $empname=$myrow_emp['empname'];
      $designation=$myrow_emp['designation'];  
      
      $plants="";
      
      
    
      $sql_fswaste="SELECT * FROM bio_fs_entrydetails
                  WHERE leadid='$leadid'";
      $result_fswaste=DB_query($sql_fswaste,$db);
      $myrow_fswaste=DB_fetch_array($result_fswaste);
      $edeg=$myrow_fswaste['edegradable'];
      $sdeg=$myrow_fswaste['sdegradable'];
      $vsdeg=$myrow_fswaste['vsdegradable'];
      $ndeg=$myrow_fswaste['ndegradable'];
      $org_waste=$myrow_fswaste['liquid_waste'];    
      $dry_waste=$myrow_fswaste['solid_waste'];    
      
      $actual_feed=$myrow_fswaste['actual_feedstock'];
      $projected_feed=$myrow_fswaste['projected_feedstock'];
      $total_gas_generated=$myrow_fswaste['total_gas'];
  }
  
  $biogas=$total_gas_generated;
  
  
  
  $sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
$result_cat=DB_query($sql_cat,$db);
$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $plant_array=join(",", $cat_arr); 
}
  
$sql_plantlist="SELECT bio_conceptproposaldetails.stockid,
                       stockmaster.longdescription 
            FROM   bio_conceptproposaldetails,
                   stockmaster
            WHERE  bio_conceptproposaldetails.stockid=stockmaster.stockid
             AND   stockmaster.categoryid IN($plant_array)  
             AND   bio_conceptproposaldetails.cp_id=".$cpID;
 $result_plantlist=DB_query($sql_plantlist,$db);           
 $myrow_plantlist=DB_fetch_array($result_plantlist);
 $plant_id=$myrow_plantlist['stockid'];
 $plants=$myrow_plantlist['longdescription'];  
  
 
  
  
      $plant_value=array();
     $sql_1="SELECT stockmaster.stockid,
                     stockcatproperties.label,
                     stockitemproperties.value
              FROM stockmaster,stockcatproperties,stockitemproperties
              WHERE stockmaster.stockid='$plant_id'
              AND stockmaster.stockid = stockitemproperties.stockid
              AND stockmaster.categoryid = stockcatproperties.categoryid
              AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid";
      $result_1=DB_query($sql_1,$db);
      while($myrow_1=DB_fetch_array($result_1)){
          $plant_value[]=$myrow_1['value'];
           
      }
  
  
      $size=$plant_value[1];
      $organic_waste_capacity=$plant_value[6];
      $organic_water_capacity=$plant_value[9];
//      $biogas=$plant_value[10];
      
      $solid_fertilizer=$plant_value[11];
      $liquid_fertilizer=$plant_value[12];
      
  
      $LPG=$biogas*(0.5);
      $electricity=$biogas*(1.5);
      $autofuel=$biogas*(0.6);
      $carbon_credit=$biogas*(3.5);
  
  
  $costs=array(); 
         $sql_cost="SELECT bio_commercialproduct.commercialproduct, 
                           bio_costbenefitmaster.unitprice
                      FROM bio_commercialproduct, bio_costbenefitmaster
                      WHERE bio_costbenefitmaster.commercialproduct_id = bio_commercialproduct.commercialproduct_id";
         $result_cost=DB_query($sql_cost,$db);
         while($myrow_cost=DB_fetch_array($result_cost)){
//             $costs=$costs.",".$myrow_cost['unitprice'];
               $costs[]=$myrow_cost['unitprice'];
//               $costs_array=join(",", $costs);
             
         }
         
         //print_r($costs);
//         echo"<br />cost";
//         echo$costs[0];
         $cost_biogas=$costs[0];
         $cost_LPG=$costs[1];
         $cost_electricity=$costs[2];
         $cost_carbon=$costs[3];
         $cost_fuel=$costs[4];
         $cost_solid=$costs[5];
         $cost_liquid=$costs[6];
         
         $annual_biogas=$biogas*365;
         $annual_biogas_income=$annual_biogas*$cost_biogas;
         
         $annual_lpg=$LPG*365;
         $annual_lpg_income=$annual_lpg*$cost_LPG;
         
         $annual_electricity=$electricity*365;
         $annual_electricity_income=$annual_electricity*$cost_electricity;
  
         $annual_autofuel=$autofuel*365;
         $annual_autofuel_income=$annual_autofuel*$cost_fuel;
         
         $annual_solidfert=$solid_fertilizer*365;
         $annual_solidfert_income=$annual_solidfert*$cost_solid;
         
         $annual_liquidfert=$liquid_fertilizer*365;
         $annual_liquidfert_income=$annual_liquidfert*$cost_liquid;
         
         $annual_organic_fertilizer=$annual_solidfert_income+$annual_liquidfert_income;
         
         $total_annual_income=$annual_biogas+$annual_organic_fertilizer;
         
         $annual_carbon_credit=$carbon_credit*365;
  
$item_id=array();
$items=array();
$item_price=array();
  $sql_itemlist="SELECT bio_conceptproposaldetails.stockid,
                        bio_conceptproposaldetails.tprice,
                       stockmaster.longdescription 
            FROM   bio_conceptproposaldetails,
                   stockmaster
            WHERE  bio_conceptproposaldetails.stockid=stockmaster.stockid
             AND   bio_conceptproposaldetails.cp_id=".$cpID;
 $result_itemlist=DB_query($sql_itemlist,$db);           
 while($myrow_itemlist=DB_fetch_array($result_itemlist)){
    $item_id[]=$myrow_itemlist['stockid'];
    $items[]=$myrow_itemlist['longdescription'];
    $item_price[]=$myrow_itemlist['tprice']; 
 }
 
 $item_count=count($item_id);
 //echo"<br />";
// print_r($items);
// 
 $total_plantprice_sub=$total_plantprice;
 $itemid_sub=array();
$item_array=array();
   $scheme=array();
   $sub_amount=array();
   $item_des=array();
   $sql_subsidy="SELECT bio_cpsubsidy.stockid,
                        bio_cpsubsidy.amount,
                        bio_schemes.scheme,
                        stockmaster.longdescription
                 FROM bio_cpsubsidy,bio_schemes,stockmaster,bio_conceptproposaldetails
                 WHERE bio_cpsubsidy.cp_id=".$cpID."
                 AND bio_cpsubsidy.scheme=bio_schemes.schemeid
                 AND bio_cpsubsidy.stockid=stockmaster.stockid
                 AND bio_conceptproposaldetails.stockid=bio_cpsubsidy.stockid
			AND bio_conceptproposaldetails.cp_id=bio_cpsubsidy.cp_id";
   $result_subsidy=DB_query($sql_subsidy,$db);
   while($myrow_subsidy=DB_fetch_array($result_subsidy))
   {
       $itemid_sub[]=$myrow_subsidy['stockid'];
       $item_array[]="'".$myrow_subsidy['stockid']."'";
       $scheme[]=$myrow_subsidy['scheme'];
       $sub_amount[]=$myrow_subsidy['amount'];
       $item_des[]=$myrow_subsidy['longdescription'];
       $total_plantprice_sub=$total_plantprice_sub-$myrow_subsidy['amount'];
            
   }
   $subsidy_count=sizeof($itemid_sub);
   if($subsidy_count>0){
      $total_plantprice_sub=$total_plantprice_sub; 
   }
// print_r($sub_amount);
  
  
  //echo"sub cnt=".$subsidy_count;
  
  
  
    $sql2="select bio_cust.contactperson,
    bio_cust.custname,
          bio_cust.houseno,
            bio_cust.housename,  
          bio_cust.area1,
          bio_district.district 
          FROM bio_cust,bio_district 
          where bio_cust.cust_id=$custid   
          AND bio_district.did=bio_cust.district
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality";  
  $result2=DB_query($sql2,$db);
  while($row2=DB_fetch_array($result2))
  {
      $name=$row2['custname'];
      $contactperson=$row2['contactperson'];
      $houseno=$row2['houseno'];
      if($houseno=='0'){
        $houseno="";
      }elseif($houseno==" "){
        $houseno="";  
      }
      
      $place=$row2['area1'];
      if($place=='0'){
          $place="";
      }elseif($place==" "){
          $place="";
      }
      $housename=$row2['housename'];
      if($housename=='0'){
         $housename=""; 
      }elseif($housename==" "){
         $housename=""; 
      }
      $district=$row2['district'];
      if($district=='0'){
         $district=""; 
      }elseif($district==" "){
         $district=""; 
      }
  } 
  
  
  
  
  
   
  
  
   
    
 //========================================================================================================================//   
 
//=======================================================================================================================//


 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,100,$FontSize+2,$letterno, 'left');
 $pdf->addTextWrap($Left_Margin+450,$YPos-=$line_height-15,100,$FontSize+2,$letterdate, 'left');
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,200,$FontSize+1,$name, 'left');
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,200,$FontSize+1,$contactperson, 'left');
 if($houseno!="") {
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,200,$FontSize+1,$houseno, 'left'); 
 }
 if($housename!=""){
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,200,$FontSize+1,$housename, 'left'); 
 }
 if($place!=""){
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,200,$FontSize+1,$place, 'left'); 
 }
 if($district!=""){
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,200,$FontSize+1,$district, 'left'); 
 }   
                  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+15,200,$FontSize+1,_('Dear Sir,'), 'left');              
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,900,$FontSize+1,_('Sub: - BIOTECH Waste to Energy Project-submission of concept proposal report'), 'left');              
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-1,900,$FontSize+1,_('Ref: - Joint Feasibility study cum survey'), 'left');  
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_('We express our sincere thanks to you and your associates for the warm welcome accorded to us when we '), 'left');  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-3,900,$FontSize+1,_('called on you for a survey cum feasibility study.We deem it as highly viable to implement our eco-friendly Waste '), 'left');  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-3,900,$FontSize+1,_('to Energy Project In your esteemed Organisation.'), 'left');               
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_(' With the collected data, we have prepared a project concept proposal. Once the project concept proposal is '), 'left');                                                                                               
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-3,900,$FontSize+1,_('approved by you, we can go ahead for further necessary action for the effective implementation of the project.'), 'left'); 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('The total cost for implementing this project is estimated at Rs. '   ).$total_plantprice_sub);
 if($total_annual_income!=""){
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-3,900,$FontSize+1,_('The annual return from the project is expected to be about Rs. '   )  .$total_annual_income);  
 
 }  
  $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_('We are sending here with the concept proposal for the installation of waste treatment plant in your organisation, '), 'left');  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-3,900,$FontSize+1,_('for favor of your kind information and approval forenabling us to  take necessary further action.We are only glad to'), 'left');  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-3,900,$FontSize+1,_('furnish any further clarification in this mater if you  so require.   '), 'left');    
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,200,$FontSize+1,_('Thanking you'), 'left');      
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,200,$FontSize+1,_('Yours faithfully'), 'left');      
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,500,$FontSize+1,$empname, 'left');  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,200,$FontSize+1,$designation, 'left');
 
 $YPos=$Bottom_Margin-5;
  $page='Page No '.$PageNumber;
  $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
  
 //======================================================================================================================// 
 $PageNumber++;
 $pdf->newPage();
 $FontSize=9;
 $YPos= $Page_Height-$Top_Margin;   
 $line_height=15;        
 $Xpos = $Left_Margin+5;
 $YPos -=(2*$line_height);  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+3,_('Out come of feasibility study'), 'left'); 
 $pdf->line($Left_Margin,$YPos-=$line_height-13,$Left_Margin+150,$YPos);
 
 
  if($edeg!=0){  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Easily Degradable'), 'left');      
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left'); 
 $string=$edeg." Kg";
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,900,$FontSize+1,$string,'',0);  
  }
  if($sdeg!=0){             
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Slow Degradable'), 'left');         
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string1=$sdeg." Kg"; 
  $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,900,$FontSize+1,$string1,'',0);     
  }    
  
  
  if($vsdeg!=0){             
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Very Slow Degradable'), 'left');         
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string1=$vsdeg." Kg"; 
  $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,900,$FontSize+1,$string1,'',0);     
  }     
  
 if($ndeg!=0){             
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Non Degradable'), 'left');         
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string1=$ndeg." Kg"; 
  $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,900,$FontSize+1,$string1,'',0);     
  }      
  
 if($org_waste!=0){             
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Organic waste water'), 'left');         
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string1=$org_waste." Litter"; 
  $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,900,$FontSize+1,$string1,'',0);     
  }       
  
  
  if($dry_waste!=0){             
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Dry organic waste'), 'left');         
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string1=$dry_waste." Kg"; 
  $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,900,$FontSize+1,$string1,'',0);     
  }        
               
 

             
 //$pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('The space required for the installation of the plant  '), 'left');         
// $pdf->addTextWrap($Left_Margin+260,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
// $string4="".$space."     metre"; 
// $pdf->addTextWrap($Left_Margin+270,$YPos-=$line_height-14,1000,$FontSize+1,$string4,'',0);
                 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+3,_('Technical details of the proposed project'), 'left');     
 $pdf->line($Left_Margin,$YPos-=$line_height-13,$Left_Margin+218,$YPos);
 
       
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,1000,$FontSize+1,_('Plant proposed:'), 'left'); 
  $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 
  $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,900,$FontSize+1,$plants,'',0);     
 
    
 //$pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Plant proposed:     Portable Digester Ordinary 1 Cum  '), 'left');  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Size of the plant recommended  '), 'left');     
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');  
 $string5=$size." Cum";  
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string5,'',0);
 if($organic_waste_capacity!=""){
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Organic waste Treatment capacity of the plant '), 'left');     $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left'); 
    $string6="".$organic_waste_capacity." Kg/day."; 
    $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string6,'',0);
 } 
 if($organic_water_capacity!=""){
     $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Organic waste water Treatment capacity of the plant '), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string7="".$organic_water_capacity." Kg/day.";
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string7,'',0);

 }            
 if($biogas!=""){    
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('The expected gas generation  '), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left'); 
 $string8="".$biogas." cum/day.";  
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string8,'',0);
 
  
  if($outputtypeid==1){ 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('This gas is sufficient to replace/day'), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left'); 
 $string9="".$LPG." Kg of LPG "; 
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string9,'',0); 
  }
 elseif($outputtypeid==2){  
  $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('This gas is sufficient to replace/day'), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left'); 
 $string9="".$electricity." KW of Electricity "; 
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string9,'',0); 
 }
 elseif($outputtypeid=3){  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('This gas is sufficient to replace/day'), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left'); 
 $string9="".$autofuel." Litre of Auto fuel "; 
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string9,'',0);  
 }
 
     $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+3,_('Return on investment'), 'left'); 
 $pdf->line($Left_Margin,$YPos-=$line_height-13,$Left_Margin+118,$YPos);  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Annual income in the form of Bio gas  '), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string_annual="Rs.  ".$annual_biogas_income.".00"; 
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string_annual,'',0);      
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Annual income in the form of Organic fertilizer  '), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string_income="Rs.  ".$annual_organic_fertilizer.".00";                               
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string_income,'',0);    
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Total Annual income from the plant'), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string_income1="Rs.  ".$total_annual_income.".00";                               
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string_income1,'',0);
 }
  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+3,_('Approximate cost of the proposed project'), 'left');  
 $pdf->line($Left_Margin,$YPos-=$line_height-13,$Left_Margin+220,$YPos);
 for($i=0;$i<$item_count;$i++){
     
    if ($YPos < $Bottom_Margin + $line_height+30){
         $YPos=$Bottom_Margin - 5;
            $page='Page No '.$PageNumber;
            $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
            $PageNumber++; 
            $pdf->newPage();
            
            $YPos= $Page_Height-$Top_Margin;   
            $line_height=15;        
            $Xpos = $Left_Margin+5;
            $YPos -=(2*$line_height);
     }
     
     
     
              $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,1000,$FontSize+1,$items[$i],'',0); 
              $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
              $string_income1="Rs.  ".$item_price[$i].".00";   
              $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string_income1,'',0);               
}   
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+25,900,$FontSize+1,_('Total project cost'), 'left');  
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string_amount="Rs.  ".$total_plantprice.".00";                               
 $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string_amount,'',0);
 if($subsidy_count>0){
     
     if ($YPos < $Bottom_Margin + $line_height+30){
         $YPos=$Bottom_Margin - 5;
            $page='Page No '.$PageNumber;
            $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
            $PageNumber++; 
            $pdf->newPage();
            
            $YPos= $Page_Height-$Top_Margin;   
            $line_height=15;        
            $Xpos = $Left_Margin+5;
            $YPos -=(2*$line_height);
     }
     
     $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+3,_('Subsidy'), 'left');
     $pdf->line($Left_Margin,$YPos-=$line_height-13,$Left_Margin+42,$YPos);
     
     for($i=0;$i<$subsidy_count;$i++){
        $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,$scheme[$i],'',0);
        $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
        $string_subsidy="Rs.  ".$sub_amount[$i].".00";   
        $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$string_subsidy,'',0);
     }
     $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Total project cost after Subsidy'), 'left');  
        $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
        $total_amount="Rs.  ".$total_plantprice_sub.".00";                               
        $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,1000,$FontSize+1,$total_amount,'',0);
     
 }
 
 $YPos=$Bottom_Margin+80; 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,500,$FontSize+1,$empname, 'left');  
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,200,$FontSize+1,$designation, 'left');
   
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('BIOTECH Carbon credit'), 'left');                     
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('BIOTECH / MNRE subsidy'), 'left');                     
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Special discount'), 'left');

$YPos=$Bottom_Margin-5;
  $page='Page No '.$PageNumber;
  $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);

                     
 $pdf->OutputD($_SESSION['DatabaseName'] . 'CP Covering letter_' . Date('Y-m-d') . '.pdf');
 $pdf->__destruct();   
?>
