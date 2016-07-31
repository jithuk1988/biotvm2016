<?php
  $PageSecurity = 80;  
  include('includes/session.inc');
  include('includes/bio_GetPrice.inc');  
  $PaperSize ='A4';
  include('includes/PDFStarter.php');  
  $FontSize=9;
  $PageNumber=1;
  $line_height=12;
  $Xpos = $Left_Margin+1;
  $WhereCategory = " ";
  $CatDescription = " ";
//=================================================kuiiiii====================================================================//  
  
    $FontSize=10;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $Xpos = $Left_Margin+5;
    $YPos -=(2*$line_height);       
    $img1='companies/'.$_SESSION['DatabaseName'].'/PNG/logo PNG.png';
    $pdf->addJpegFromFile($img1,$XPos+380,$YPos-=$line_height,0,55);
    $img2= 'companies/'.$_SESSION['DatabaseName'].'/PNG/B ONLY.png';
    $pdf->addJpegFromFile($img2,$XPos+40,$YPos,0,55);  
    $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);
    
  //==========================================================================================================================//
      
    
    $leadid=$_POST['LeadID'];
    $cpID=$_POST['CpID'];
    $feacondate  =$_POST['feacondate'];
    
   $sql1="select bio_leads.leadid,
                bio_leads.cust_id,
                bio_leads.leaddate,
                bio_leads.outputtypeid,
                bio_conceptproposal.cp_id,  
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
      $cpid=$row1['cp_id'];  
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
     
      $space=$myrow_fswaste['space_plant'];
      $actual_feed=$myrow_fswaste['actual_feedstock'];
      $projected_feed=$myrow_fswaste['projected_feedstock'];
      $total_gas_generated=$myrow_fswaste['total_gas'];
      
      $sql_nolatrine="SELECT no_latrine FROM bio_fsentry_basic
                  WHERE leadid='$leadid'";
      $result_nolatrine=DB_query($sql_nolatrine,$db);
      $myrow_nolatrine=DB_fetch_array($result_nolatrine); 
      $no_latrine=$myrow_nolatrine['no_latrine'];           
                  
  }      
  
  $biogas=$total_gas_generated;
  
  
  /*$sql_fsdate="SELECT taskcompleteddate FROM bio_leadtask WHERE leadid=$leadid AND taskid=2 AND viewstatus=1 AND taskcompletedstatus!=2";
  $result_fsdate=DB_query($sql_fsdate,$db);
  $row_fsdate=DB_fetch_array($result_fsdate);
  $fsdate=ConvertSQLDate($row_fsdate['taskcompleteddate']);  */
  $fsdate=$feacondate;
  
  
$sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
$result_cat=DB_query($sql_cat,$db);
$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $plant_array=join(",", $cat_arr); 
}
  
$sql_plantlist="SELECT bio_conceptproposaldetails.stockid,
                       stockcategory.categorydescription, 
                       stockmaster.longdescription 
            FROM   bio_conceptproposaldetails,stockmaster,stockcategory                   
            WHERE  bio_conceptproposaldetails.stockid=stockmaster.stockid
             AND   stockmaster.categoryid=stockcategory.categoryid
             AND   stockmaster.categoryid IN ($plant_array)  
             AND   bio_conceptproposaldetails.cp_id=".$cpID;
 $result_plantlist=DB_query($sql_plantlist,$db);           
 $myrow_plantlist=DB_fetch_array($result_plantlist);
 $plant_id=$myrow_plantlist['stockid'];
 $plants=$myrow_plantlist['categorydescription'];  
 
 /*$components=array();
 $sql_bom="SELECT bom.component,
                  stockmaster.description
                   
           FROM   bom,stockmaster
           WHERE  bom.component=stockmaster.stockid 
           AND    bom.parent='$plant_id'";
 $result_bom=DB_query($sql_bom,$db);
 while($row_bom=DB_fetch_array($result_bom)) 
 {
     $components[]=$row_bom['description'];
     $stockid=$row_bom['component'];
     $price=GetPrice($stockid,$db);
     $price1[]=$price;
 }         
 $components_count=count($components);            
  

  */
  
     $plant_value=array();
     $sql_1="SELECT stockmaster.stockid,
                    stockcatproperties.label,
                    stockitemproperties.value
              FROM  stockmaster,stockcatproperties,stockitemproperties
              WHERE stockmaster.stockid='$plant_id'
              AND   stockmaster.stockid = stockitemproperties.stockid
              AND   stockmaster.categoryid = stockcatproperties.categoryid
              AND   stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid";
      $result_1=DB_query($sql_1,$db);
      while($myrow_1=DB_fetch_array($result_1))
      {
          $plant_value[]=$myrow_1['value'];
                         //  echo$myrow_1['value'];
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
         
         $annual_biogas=$biogas*365;   $annual_lpg=$LPG*365;     
         $annual_biogas_income=$annual_lpg*$cost_LPG;
         
         
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
         
         $total_annual_income=$annual_biogas_income+$annual_organic_fertilizer;
         
         $annual_carbon_credit=$carbon_credit*365;
  
$cat_id=array();
$item_id=array();
$items=array();
$item_price=array();
  $sql_itemlist="SELECT bio_conceptproposaldetails.stockid,
                        bio_conceptproposaldetails.tprice,
                        stockmaster.description,
                        stockmaster.categoryid 
                 FROM   bio_conceptproposaldetails,stockmaster
                 WHERE  bio_conceptproposaldetails.stockid=stockmaster.stockid
                 AND    bio_conceptproposaldetails.cp_id=".$cpID;
 $result_itemlist=DB_query($sql_itemlist,$db);           
 while($myrow_itemlist=DB_fetch_array($result_itemlist))
 {
    $item_id[]=$myrow_itemlist['stockid'];
    $cat_id[]="'".$myrow_itemlist['categoryid']."'"; 
    $cat_id1=join(",",$cat_id); 
    $items[]=$myrow_itemlist['description'];   
    $item_price[]=$myrow_itemlist['tprice']; 
 }
 
 $item_count=count($item_id);
 $total_plantprice_sub=$total_plantprice;    
 $vat=0; $servicetax=0;$cess=0;
 
  $sql_plant="SELECT bio_conceptproposaldetails.stockid,bio_conceptproposaldetails.tprice AS plantprice,
                        stockmaster.description AS plantdesc,
                        stockmaster.taxcatid  AS  taxcatid
                FROM   bio_conceptproposaldetails,stockmaster 
                WHERE  bio_conceptproposaldetails.stockid=stockmaster.stockid
                AND    bio_conceptproposaldetails.stockid IN 
               (SELECT stockmaster.stockid FROM bio_maincat_subcat,stockmaster 
                 WHERE bio_maincat_subcat.subcatid IN ($cat_id1) 
                 AND   bio_maincat_subcat.maincatid=1 
                 AND   stockmaster.categoryid=bio_maincat_subcat.subcatid 
                 AND   stockmaster.stockid IN 
               (SELECT bio_conceptproposaldetails.stockid       
                 FROM   bio_conceptproposaldetails
                 WHERE  bio_conceptproposaldetails.cp_id=$cpID)) AND bio_conceptproposaldetails.cp_id=$cpID";  
  $result_plant=DB_query($sql_plant,$db);
  $row_plant=DB_fetch_array($result_plant);
  
  $plantdesc=$row_plant['plantdesc'];
  $plantprice=$row_plant['plantprice'];
  $taxcatid=$row_plant['taxcatid'];
 // $vat1=get_vat($taxcatid,$plantprice);
  // $vat=$tax[0];
                
                                    
 $optionalitem=array(); 
 $optionalprice=array(); 
 $sql_notplant="SELECT bio_conceptproposaldetails.stockid,bio_conceptproposaldetails.tprice,
                    stockmaster.description,stockmaster.taxcatid  AS  taxcatid 
                FROM   bio_conceptproposaldetails,stockmaster 
                WHERE  bio_conceptproposaldetails.stockid=stockmaster.stockid
                AND    bio_conceptproposaldetails.stockid NOT IN 
               (SELECT stockmaster.stockid FROM bio_maincat_subcat,stockmaster 
                 WHERE bio_maincat_subcat.subcatid IN ($cat_id1) 
                 AND   bio_maincat_subcat.maincatid=1 
                 AND   stockmaster.categoryid=bio_maincat_subcat.subcatid 
                 AND   stockmaster.stockid IN 
               (SELECT bio_conceptproposaldetails.stockid       
                 FROM   bio_conceptproposaldetails
                 WHERE  bio_conceptproposaldetails.cp_id=$cpID)) AND bio_conceptproposaldetails.cp_id=$cpID";        
 $result_notplant=DB_query($sql_notplant,$db);
  $tax_option=array();
  
 while($row_notplant=DB_fetch_array($result_notplant))
 
 {
    $optionalitem[]=$row_notplant['description']; 
    $optionalprice[]=$row_notplant['tprice'];
    $optionaltaxcatid[]=$row_notplant['taxcatid'];
                     
       $tax_option=get_tax($row_notplant['tprice'],$row_notplant['taxcatid'],$db);
       $taxp+= $tax_option[0]; 
    $servicetaxp+=$tax_option[1];
     $cessp+=$tax_option[2];
          
 } 
                   
 
 $optionalitem_count=count($optionalitem);
 
 $sql_outputtype="SELECT output FROM bio_fsentry_output WHERE leadid=$leadid";
 $result_outputtype=DB_query($sql_outputtype,$db);
 $row_outputtype=DB_fetch_array($result_outputtype);
 $outputtype=$row_outputtype['output'];
 
 $output=explode(",",$outputtype); 
 $output1=$output[1]; 
 $output2=$output[2];
 
         
    function get_tax($plantprice,$taxcatid,$db)
   {
       
   
 $sql_tax = "SELECT taxgrouptaxes.calculationorder, taxauthorities.description, taxgrouptaxes.taxauthid, 
 taxauthorities.taxglcode, taxgrouptaxes.taxontax, taxauthrates.taxrate
FROM taxauthrates
INNER JOIN taxgrouptaxes ON taxauthrates.taxauthority = taxgrouptaxes.taxauthid
INNER JOIN taxauthorities ON taxauthrates.taxauthority = taxauthorities.taxid
WHERE taxgrouptaxes.taxgroupid =1
AND taxauthrates.dispatchtaxprovince =1
AND taxauthrates.taxrate !=0
ORDER BY taxgrouptaxes.calculationorder";

    $ErrMsg = _('The taxes and rate for this tax group could not be retrieved because');
    $result_tax = DB_query($sql_tax,$db,$ErrMsg);

    if (DB_num_rows($result_tax)>=1) {
         $tax_res=array();
    $tax_res[0]=$tax_res[1]= $tax_res[2]=0;

        while ($taxes=DB_fetch_array($result_tax))
        {
        // echo $taxes[0].'&nbsp;&nbsp;'.$taxes[1].'&nbsp;&nbsp;'.$taxes[2].'&nbsp;&nbsp;'.$taxes[3].'&nbsp;&nbsp;'.$taxes[4].'&nbsp;&nbsp;'.$taxes[5].'<br>';

         if  ($taxes[2]==5 AND $taxcatid==1) {
              
       $tax_res[0]= $plantprice*$taxes[5];
         }

         if  ($taxes[2]==14 AND $taxcatid==2) {
                                                
        $tax_res[1]=$plantprice*$taxes[5];
         }

         if ($taxes[2]==15 AND $taxcatid==2){
                          
        $tax_res[2]=$tax_res[1]*$taxes[5];
        }
      }
 }
  return $tax_res;

   }

 $tax=array();
 $tax=get_tax($plantprice,$taxcatid,$db); 
 
 $tax[0];$tax[1];$tax[2]; 
  
         
 
                     
     $vat= $tax[0]+$taxp;
     $servicetax= $tax[1]+ $servicetaxp;
    $cess=$tax[2]+$cessp;    
                                         
       
 
  //echo"<br />";
 // print_r($items);
// 
 
 

   
   $sql_subsidy1="SELECT amount FROM bio_cpsubsidy WHERE cp_id=$cpID AND scheme=1";  $result_subsidy1=DB_query($sql_subsidy1,$db);
   $sql_subsidy2="SELECT amount FROM bio_cpsubsidy WHERE cp_id=$cpID AND scheme=2";  $result_subsidy2=DB_query($sql_subsidy2,$db);
   $sql_subsidy3="SELECT amount FROM bio_cpsubsidy WHERE cp_id=$cpID AND scheme=3";  $result_subsidy3=DB_query($sql_subsidy3,$db);
   
   $row_subsidy1=DB_fetch_array($result_subsidy1);
   $biosub=$row_subsidy1['amount'];
   $row_subsidy2=DB_fetch_array($result_subsidy2);
   $mnresub=$row_subsidy2['amount'];
   $row_subsidy3=DB_fetch_array($result_subsidy3);
   $othersub=$row_subsidy3['amount'];
   
   $total_sub=$biosub+$mnresub+$othersub;
   
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
       
       $scheme[]=$myrow_subsidy['scheme'];
       $sub_amount[]=$myrow_subsidy['amount'];
       $item_des[]=$myrow_subsidy['longdescription'];
       $total_plantprice_sub=$total_plantprice_sub-$myrow_subsidy['amount'];
            
   }
   $subsidy_count=sizeof($itemid_sub);
   if($subsidy_count>0){
      $total_plantprice_sub=$total_plantprice_sub; 
   }                             //////////////////////tax////////////////////
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
           
   $letterno="No. BTR/WTP/".date("y")."/".$cpid;

 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+20,100,$FontSize+2,$letterno, 'left'); 
 $pdf->addTextWrap($Left_Margin+430,$YPos,100,$FontSize+2,date('d/m/Y'), 'left');
 
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+20,200,$FontSize+1,_('To,'), 'left');  
 $string_person='M/s.'." ".$name;    
  $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height+10,200,$FontSize+1,$string_person); 
//  $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height+1,200,$FontSize+1,$name, 'left');  
 //$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,200,$FontSize+1,$contactperson, 'left');
// if($houseno!="") {
//    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,200,$FontSize+1,$houseno, 'left'); 
// }
// if($housename!=""){
//    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,200,$FontSize+1,$housename, 'left'); 
// }
 if($place!=""){
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height,200,$FontSize+1,$place, 'left'); 
 }
 if($district!=""){
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height,200,$FontSize+1,$district, 'left'); 
 }   
                  
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+40,200,$FontSize+1,_('Sir,'), 'left');              
 $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+10,900,$FontSize+1,_('Sub: - BIOTECH – Waste to Energy Project '), 'left');
                                                   
// if($letterno!="NULL"){
    $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+5,900,$FontSize+1,_('Ref: -  Our joint feasibility study cum survey on '), 'left'); 
    $pdf->addTextWrap($Left_Margin+290,$YPos,200,$FontSize+1,$fsdate);  
//    $pdf->addTextWrap($Left_Margin+78,$YPos-=$line_height+5,900,$FontSize+1,_('2. Our joint feasibility study cum survey on ________________________'), 'left');    
// }else{
//    $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+10,900,$FontSize+1,_('1. Our joint feasibility study cum survey on ________________________'), 'left'); 
// } 
// 
  
            
 
 $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+30,900,$FontSize+1,_('We express our sincere thanks to you and your associates for the warm welcome  accorded'), 'left');  
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_('to us  when we  called on  you for a  survey  cum  feasibility study.  We deem it  as highly viable to'), 'left');  
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_('implement our eco-friendly Waste to Energy Project in your esteemed Organisation.'), 'left');               
 $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+25,900,$FontSize+1,_('On  the basis  of the  data collected  and  as  per the  discussion  we  had  with your officials'), 'left');                                                                                               
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_('during  the meeting,  we have  prepared a  concept  project report.  We are  sending here  with the '), 'left'); 
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_('same  to you, for  favor of  your  kind  information  and  necessary  further action. Please  feel free'), 'left');
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_('to contact  us for any  further clarification in the matter.'), 'left'); 
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,900,$FontSize+1,_('The total cost for implementing this project is estimated at Rs. '   ).$total_plantprice_sub);
// if($total_annual_income!=""){
//    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+1,_('The annual return from the project is expected to be about Rs. '   )  .$total_annual_income);  
// 
// }  
 $pdf->addTextWrap($Left_Margin+50,$YPos-=$line_height+25,900,$FontSize+1,_('We  look  forward  for  your  continued  cooperation and valuable  guidance for  accelerating'), 'left');  
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_('the process  of  protecting our environment, for which we are deeply involved. '), 'left');  
   
//$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+50,200,$FontSize+1,_('With warm regards,'), 'left');      
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+60,200,$FontSize+1,_('Yours faithfully'), 'left');      
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+70,500,$FontSize+1,$empname, 'left');  
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,200,$FontSize+1,$designation, 'left');
 
 $pdf->line($Left_Margin,$YPos-=$line_height+30,$Left_Margin+534,$YPos-1); 
 $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,480,$FontSize-1,_('www.biotech-india.org'), 'center');
 
    $img3='companies/'.$_SESSION['DatabaseName'].'/PNG/FOOTER.png';
    $pdf->addJpegFromFile($img3,$XPos+55,$YPos-=$line_height+30,500,30); 
 
  $YPos=$Bottom_Margin-5;
//  $page='Page No '.$PageNumber;
 // $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
  
 //======================================================================================================================// 
 $PageNumber++;
 $pdf->newPage();
 $FontSize=9;
 $YPos= $Page_Height-$Top_Margin;   
 $line_height=15;        
 $Xpos = $Left_Margin+5;
 $YPos -=(2*$line_height);  
 
 $Left_Margin=50;
 
 $i=0; 
  
 $pdf->addTextWrap($Left_Margin,$YPos+=15,500,$FontSize+1,_('Feasibility study report of M/s '.$name.','.$district), 'center');  
  
 if($output1==1){ 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+2,_('Project Name: Waste to cooking gas'), 'center');
 }elseif($output1==2){
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+2,_('Project Name: Waste to Electricity'), 'center');
 }elseif($output1==5){
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+2,_('Project Name: Bio Incinerator Project'), 'center');     
 }else{
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+2,_('Project Name: Waste to cooking gas'), 'center');    
 }
   
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+40,20,$FontSize+1,_('No'), 'center');
 $pdf->addTextWrap($Left_Margin+20,$YPos,200,$FontSize+1,_('Description'), 'center'); 
 $pdf->addTextWrap($Left_Margin+300,$YPos,100,$FontSize+1,_('Unit'), 'center');
 $pdf->addTextWrap($Left_Margin+400,$YPos,100,$FontSize+1,_('Quantity'), 'center');
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;

 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i,'',0); 
 $pdf->addTextWrap($Left_Margin+30,$YPos,300,$FontSize+1,_('Easily Degradable waste to be treated / day'), 'left');      
 $pdf->addTextWrap($Left_Margin+345,$YPos,100,$FontSize+1,_('Kg'), 'left'); 
 $pdf->addTextWrap($Left_Margin+435,$YPos,100,$FontSize+1,$edeg,'',0);  
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
         
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i,'',0);
 $pdf->addTextWrap($Left_Margin+30,$YPos,300,$FontSize+1,_('Slowly Degradable waste to be treated / day'), 'left');         
 $pdf->addTextWrap($Left_Margin+345,$YPos,100,$FontSize+1,_('Kg'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,100,$FontSize+1,$sdeg,'',0);  
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);  
 $i++;
 
 if($output1==5 || $output2==5)  
 { 
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i,'',0);      
 $pdf->addTextWrap($Left_Margin+30,$YPos,300,$FontSize+1,_('Very Slow Degradable waste to be treated / day'), 'left');         
 $pdf->addTextWrap($Left_Margin+345,$YPos,100,$FontSize+1,_('Kg'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,100,$FontSize+1,$vsdeg,'',0);   
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);  
 $i++;
 }   
    
// if($no_latrine!=0){  
// $i++;           
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,300,$FontSize+1,_('No. of peoples using toilet'), 'left');          
//  $pdf->addTextWrap($Left_Margin+435,$YPos-=$line_height-14,100,$FontSize+1,$no_latrine,'',0);   
//  $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);   
//  } 
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i,'',0);
 $pdf->addTextWrap($Left_Margin+30,$YPos,300,$FontSize+1,_('Organic waste water to be treated / day'), 'left');         
 $pdf->addTextWrap($Left_Margin+345,$YPos,100,$FontSize+1,_('Litre'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,100,$FontSize+1,$org_waste,'',0);   
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;   

 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i,'',0);
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('The space available for installation of plant'), 'left');     
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Sq. Meter'), 'left'); 
 $pdf->addTextWrap($Left_Margin+435,$YPos,1000,$FontSize+1,$space,'',0); 
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);
 $i++; 
 
 $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*$i));

 if($output1==1 || $output2==1)
 {
 $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*($i-1)));  
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i,'',0);    
 $pdf->addTextWrap($Left_Margin+30,$YPos,300,$FontSize+1,_('Proposed use of Gas'), 'left');  
 $pdf->addTextWrap($Left_Margin+340,$YPos,300,$FontSize+1,_('Cooking gas'), 'left');      
 //$pdf->addTextWrap($Left_Margin+435,$YPos,100,$FontSize+1,$total_gas_generated,'',0);  
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
 }
 elseif($output1==2 || $output2==2)
 {
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i,'',0);    
 $pdf->addTextWrap($Left_Margin+30,$YPos,300,$FontSize+1,_('Proposed use of EI'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('KWh'), 'left');  
 $pdf->addTextWrap($Left_Margin+340,$YPos,300,$FontSize+1,_('Electricty generation'), 'left');      
 //$pdf->addTextWrap($Left_Margin+435,$YPos,100,$FontSize+1,$total_gas_generated,'',0);  
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++; 
 }
 elseif($output1==5 || $output2==5)
 {
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i,'',0);    
 $pdf->addTextWrap($Left_Margin+30,$YPos,300,$FontSize+1,_('Proposed use of Gas'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Lr'), 'left'); 
 $pdf->addTextWrap($Left_Margin+340,$YPos,300,$FontSize+1,_('Bio Incinerator'), 'left');      
 //$pdf->addTextWrap($Left_Margin+435,$YPos,100,$FontSize+1,$total_gas_generated,'',0);  
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
 }
 $k=$i;
 
 $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*$i));
 $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+(17*$i));
 
// $RY=70;
// $Rheight=(17*($i+1));
// $pdf->rect($Left_Margin-2,$RY,500,$Rheight);    
// $pdf->rect($Left_Margin+250,$RY,25,$Rheight);    
  
  /*if($dry_waste!=0){             
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('Dry organic waste'), 'left');         
 $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
 $string1=$dry_waste." Kg"; 
  $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-14,900,$FontSize+1,$string1,'',0);     
  }        
               */
 

             
 //$pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,900,$FontSize+1,_('The space required for the installation of the plant  '), 'left');         
// $pdf->addTextWrap($Left_Margin+260,$YPos-=$line_height-14,900,$FontSize+1,_('-'), 'left');
// $string4="".$space."     metre"; 
// $pdf->addTextWrap($Left_Margin+270,$YPos-=$line_height-14,1000,$FontSize+1,$string4,'',0);


 $i=0;
                
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Technical details of the proposed project'), 'center'); 
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;  
 

 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,20,$FontSize+1,_('No'), 'center');
 $pdf->addTextWrap($Left_Margin+20,$YPos,200,$FontSize+1,_('Description'), 'center'); 
 $pdf->addTextWrap($Left_Margin+300,$YPos,100,$FontSize+1,_('Unit'), 'center');
 $pdf->addTextWrap($Left_Margin+400,$YPos,100,$FontSize+1,_('Quantity'), 'center');
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+$line_height+2); 
 $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+$line_height+2);
 $i++;
 
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);      
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Model of the Plant proposed'), 'left'); 
 $pdf->addTextWrap($Left_Margin+340,$YPos,900,$FontSize+1,$plants,'',0);   
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);
 $i++;
 
 $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+$line_height+2);
                                                                                                  
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Size of the plant proposed'), 'left');     
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Cum'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$size,'',0);                                                                             
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
 
//$i=1; 
// if($organic_waste_capacity!=""){
//     $i++;
    $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);
    $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Maximum Waste Treatment capacity of the plant'), 'left');     
    $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Kg'), 'left');
    $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$organic_waste_capacity,'',0);
    $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
    $i++;
// } 
// if($organic_water_capacity!=""){
//     $i++;
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Organic water Treatment capacity of the plant'),'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Litre'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$organic_water_capacity,'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
// }            
// if($biogas!=""){


if($output1==2 || $output2==2)  
{
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);  
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('The expected electricty generation /day'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('KWh'), 'left'); 
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$biogas,'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
}  
elseif($output1==5 || $output2==5)
{
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);   
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Quantity of waste to be treated using Bio Incinerator'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Kg'), 'left'); 
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$biogas,'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
    
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('The expected gas generation /day'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('KW'), 'left'); 
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$biogas,'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
}
else
{
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);   
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('The expected gas generation /day'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Cum'), 'left'); 
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$biogas,'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);    
 $i++;
}
// if($outputtypeid==1){ 
//     $i++;
if($output1==1 || $output2==1)
{
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);   
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Replace LPG/day'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Kg'), 'left'); 
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$LPG,'',0); 
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
}
$k=$k+$i;
//  }
// elseif($outputtypeid==2){
//    $i++;  
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+1,_('Replace LPG/day'), 'left');  
// $pdf->addTextWrap($Left_Margin+170,$YPos,900,$FontSize+1,_('Kg'), 'left');  
// $pdf->addTextWrap($Left_Margin+210,$YPos,900,$FontSize+1,$electricity,'',0); 
// $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+1,_('Electricty generation'), 'left');  
// $pdf->addTextWrap($Left_Margin+300,$YPos,900,$FontSize+1,_('Kw'), 'left'); 
// $pdf->addTextWrap($Left_Margin+310,$YPos,900,$FontSize+1,$electricity,'',0); 
// }
// elseif($outputtypeid=3){ 
//    $i++; 
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+1,_('Replace LPG/day'), 'left');    
// $pdf->addTextWrap($Left_Margin+170,$YPos,900,$FontSize+1,_('Kg'), 'left');  
// $pdf->addTextWrap($Left_Margin+210,$YPos,900,$FontSize+1,$autofuel,'',0);  
// $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
// }
 
         $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*($i-1)));
         $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+(17*($i-2)));
         $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*($i-3)));
 
 
// $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+1,_('Treatment capacity of Incinerator'), 'left');  
// $pdf->addTextWrap($Left_Margin+170,$YPos,900,$FontSize+1,_('Kg'), 'left'); 
// $pdf->addTextWrap($Left_Margin+210,$YPos,900,$FontSize+1,$LPG,'',0); 
// $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 

// $Rheight=0;
// $Rheight=(17*($i+4)); 
// $Rheight1=(17*($i+2));
// $pdf->rect($Left_Margin-2,$RY+=$line_height-8+15,500,$Rheight);   
// $pdf->rect($Left_Margin+166,$RY+=34,35,$Rheight1);
// $pdf->rect($Left_Margin+410,$RY,35,$Rheight1);   
  

   
 
//       $YPos+=(17*($i+1));  
//       for($j=0;$j<$optionalitem_count;$j++)
//       {                    
//                $pdf->addTextWrap($Left_Margin+255,$YPos,900,$FontSize+1,$optionalitem[$j],'',0);      
//                $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);
//                $YPos-=$line_height;
//       }  
//       $YPos-=(17*(($i+1)-$j));
 
 $i=0;
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Annual Return on Investment'), 'center'); 
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,20,$FontSize+1,_('No'), 'center');
 $pdf->addTextWrap($Left_Margin+20,$YPos,200,$FontSize+1,_('Description'), 'center'); 
 $pdf->addTextWrap($Left_Margin+300,$YPos,100,$FontSize+1,_('Currency'), 'center');
 $pdf->addTextWrap($Left_Margin+400,$YPos,100,$FontSize+1,_('Amount'), 'center');
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
 
 if($output1==5 || $output2==5){ 
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);    
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Annual income in the form of gas'), 'left'); 
 }elseif($output1==2 || $output1==2){
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);    
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Annual income in the form of Electricty'), 'left');    
 }else{
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);    
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Annual income in the form of Bio gas'), 'left');    
 } 
 
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$annual_biogas_income.".00",'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);
 $i++;

    $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);
    $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Annual income in the form of Organic fertilizer  '), 'left'); 
    $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left');
    $pdf->addTextWrap($Left_Margin+435,$YPos,35,$FontSize+1,$annual_organic_fertilizer.".00",'right',0);   
    $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);      
    $i++;                       
    
// $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);    
 $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height,900,$FontSize+1,_('Total Annual income from the plant'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left');                              
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$total_annual_income.".00",'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);   
 $i++;

 $k=$k+$i;
         $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*($i-1)));
         $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+(17*($i-1)));
         $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*($i-1)));
 

 
 $i=0;
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Subsidy available'), 'center'); 
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,20,$FontSize+1,_('No'), 'center');
 $pdf->addTextWrap($Left_Margin+20,$YPos,200,$FontSize+1,_('Description'), 'center'); 
 $pdf->addTextWrap($Left_Margin+300,$YPos,100,$FontSize+1,_('Currency'), 'center');
 $pdf->addTextWrap($Left_Margin+400,$YPos,100,$FontSize+1,_('Amount'), 'center');
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
  
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('BIOTECH Carbon credit'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$biosub.".00",'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);
 $i++;

 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('BIOTECH / MNRE subsidy'), 'left'); 
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$mnresub.".00",'',0);   
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);     
 $i++;
 
 if($othersub!="")
 {
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0); 
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,_('Special discount'), 'left'); 
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left');
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$othersub.".00",'',0);   
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);      
 $i++;                   
 }
 else
 {
 $YPos-=$line_height;
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);   
 $i++; 
 }    
      
 $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height,900,$FontSize+1,_('Total subsidy'), 'left');  
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left');                              
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,$total_sub.".00",'',0);
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);   
 $i++;
 
 $k=$k+$i;
 
         $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*($i-1)));
         $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+(17*($i-1)));
         $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*($i-1)));
 
 
 $i=0;
 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Total cost of the proposed project'), 'center'); 
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,20,$FontSize+1,_('No'), 'center');
 $pdf->addTextWrap($Left_Margin+20,$YPos,200,$FontSize+1,_('Description'), 'center'); 
 $pdf->addTextWrap($Left_Margin+300,$YPos,100,$FontSize+1,_('Currency'), 'center');
 $pdf->addTextWrap($Left_Margin+400,$YPos,100,$FontSize+1,_('Amount'), 'center');
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
 
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height,100,$FontSize+1,$i-1,'',0);                                                                                    
 $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,$plantdesc,'',0);      
 $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left');   
 $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,number_format($plantprice,2),'',0); 
 $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
 $i++;
       $m=2;
                      
       for($j=0;$j<12;$j++)
       {  
             
          $tot_optprice+=$optionalprice[$j];
          
                $YPos-=$line_height;
                if($optionalitem[$j]!="") 
                { 
                  $pdf->addTextWrap($Left_Margin+10,$YPos,900,$FontSize+1,$m,'',0); 
                $pdf->addTextWrap($Left_Margin+30,$YPos,900,$FontSize+1,$optionalitem[$j],'',0); 
                $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left'); 
                $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,number_format($optionalprice[$j],2),'',0);
               
                }
                
                $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);
                   
                     
                  $m++;
      
                          
        $total_price=$plantprice+$tot_optprice+$vat+$servicetax+$cess; 
       
       
    
       }    
      if($vat!=0)
      {
             
                
        $pdf->addTextWrap($Left_Margin+40,$YPos+=2,200,$FontSize+1,_('VAT'), 'center');
                $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left'); 
                $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,number_format($vat,2),'',0);  
                
                      $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*10));          
              
       } 
       
              
                   
         $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*10));
         $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+(17*10));
         $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*10));
         if($servicetax!=0)
         {
             
         
                 $YPos-=$line_height;
                 $i++;
            $pdf->addTextWrap($Left_Margin+40,$YPos+=2,200,$FontSize+1,_('ServiceTax'), 'center');
                $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left'); 
                $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,number_format($servicetax,2),'',0); 
                 $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*10));
         $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+(17*10));
         $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*10)); 
                           $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);  
         }    
              if($cess!=0)
              {
                  
                           
                            $YPos-=$line_height;
                 $i++;
            $pdf->addTextWrap($Left_Margin+40,$YPos+=2,200,$FontSize+1,_('Cess'), 'center');
                $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left'); 
                $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,number_format($cess,2),'',0); 
                 $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*10));
         $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+(17*10));
         $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*10)); 
                           $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);                        
              }                 
               $YPos-=$line_height;      
          $i++; 
        $pdf->addTextWrap($Left_Margin+50,$YPos+=2,200,$FontSize+1,_('Total project cost'), 'center');   
        $pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+1,_('Rs.'), 'left'); 
        $pdf->addTextWrap($Left_Margin+435,$YPos,900,$FontSize+1,number_format($total_price,2),'',0);  
        
        $pdf->line($Left_Margin+20,$YPos,$Left_Margin+20,$YPos+(17*10));
         $pdf->line($Left_Margin+320,$YPos,$Left_Margin+320,$YPos+(17*10));
         $pdf->line($Left_Margin+400,$YPos,$Left_Margin+400,$YPos+(17*10));
       // $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);  
        $i=$i+12; 
           
 

    $k=$k+$i;         
       $pdf->rect($Left_Margin-2,100,500,(17*$k));
      
/*   
$pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,500,$FontSize+2,_('Project Cost'), 'left'); 
$pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+2,_('Rs'), 'left');  
$pdf->addTextWrap($Left_Margin+435,$YPos,50,$FontSize+2,$total_plantprice.".00",'right',0); 
$pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);     
//$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Tax'), 'left');  
//$pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+2,_('Rs'), 'left');   
//$tax="0.00";
//$pdf->addTextWrap($Left_Margin+435,$YPos,50,$FontSize+2,$tax,'right',0); 
//$pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);   
//$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Price including Tax'), 'left');  
//$pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+2,_('Rs'), 'left'); 
//$taxprice=$total_plantprice+$tax;
//$pdf->addTextWrap($Left_Margin+435,$YPos,50,$FontSize+2,$taxprice.".00",'right',0);
//$pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+2,_('Subsidy'), 'left');    
$pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+2,_('Rs'), 'left');     
$pdf->addTextWrap($Left_Margin+435,$YPos,50,$FontSize+2,$total_sub.".00",'right',0);  
$pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Net Price'), 'left');    
$pdf->addTextWrap($Left_Margin+345,$YPos,900,$FontSize+2,_('Rs'), 'left');     
$pdf->addTextWrap($Left_Margin+435,$YPos,50,$FontSize+2,$total_plantprice_sub.".00",'right',0); 

      $pdf->rect($Left_Margin-2,700,500,(17*3)); 
      $pdf->line($Left_Margin+320,$YPos-2,$Left_Margin+320,$YPos+(17*3)-2);
      $pdf->line($Left_Margin+400,$YPos-2,$Left_Margin+400,$YPos+(17*3)-2);
                                                                              */
        
   $pdf->addTextWrap($Left_Margin+360,$YPos-=$line_height+50,200,$FontSize,$empname, 'left');     
    $pdf->addTextWrap($Left_Margin+360,$YPos-=$line_height,200,$FontSize,$designation, 'left');  
                   
       
//  $YPos-=10;

/*if($components_count!=0)
{
 
  $pdf->addTextWrap($Left_Margin+165,$YPos-=$line_height,900,$FontSize+1,_('Rs'), 'left'); 
  $pdf->addTextWrap($Left_Margin+300,$YPos,900,$FontSize+1,_('Optional items'), 'left');
  $pdf->addTextWrap($Left_Margin+415,$YPos,900,$FontSize+1,_('Rs'), 'left'); 
  $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
  for($i=0;$i<$components_count;$i++)
  {                                                                                       
     $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+1,$components[$i],'',0);      
     $pdf->addTextWrap($Left_Margin+165,$YPos,900,$FontSize+1,_('Rs.'), 'left');   
     $pdf->addTextWrap($Left_Margin+200,$YPos,900,$FontSize+1,number_format($price1[$i],2),'',0); 
$pdf->addTextWrap($Left_Margin+255,$YPos,900,$FontSize+1,$items[$i],'',0);
$pdf->addTextWrap($Left_Margin+440,$YPos,900,$FontSize+1,$item_price[$i],'',0);     
     $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
  }
  
  
  $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+2,_('Total'), 'left');        
  $pdf->addTextWrap($Left_Margin+415,$YPos,900,$FontSize+2,_('Rs.'), 'left');
  $pdf->addTextWrap($Left_Margin+440,$YPos,900,$FontSize+2,$total_plantprice.".00",'',0); 
  
   $Rheight=0;
   $Rheight=17*($components_count+2);
   $pdf->rect($Left_Margin-2,$RY+=$line_height+7,500,$Rheight); 
   $pdf->rect($Left_Margin+160,$RY,25,$Rheight);
   $pdf->rect($Left_Margin+410,$RY,25,$Rheight);
   $pdf->line($Left_Margin+250,$YPos-2,$Left_Margin+250,$YPos+$Rheight-2);
}else
{  

      $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height,900,$FontSize+1,_('Optional Items'), 'left');
      $pdf->addTextWrap($Left_Margin+415,$YPos,900,$FontSize+1,_('Rs'), 'left'); 
      $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
      
      $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+1,$plantdesc,'',0); 
      $pdf->addTextWrap($Left_Margin+165,$YPos,900,$FontSize+1,_('Rs.'), 'left');
      $pdf->addTextWrap($Left_Margin+200,$YPos,900,$FontSize+1,$plantprice,'',0);
       for($i=0;$i<$optionalitem_count;$i++)
       {        
          $tot_optprice+=$optionalprice[$i]; 
                $pdf->addTextWrap($Left_Margin+255,$YPos,900,$FontSize+1,$optionalitem[$i],'',0);      
                $pdf->addTextWrap($Left_Margin+415,$YPos,900,$FontSize+1,_('Rs.'), 'left'); 
                $pdf->addTextWrap($Left_Margin+440,$YPos,900,$FontSize+1,$optionalprice[$i],'',0);
                $pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);
                $YPos-=$line_height;
       }  
       $YPos+=$line_height; 
       $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,900,$FontSize+2,_('Total'), 'left'); 
       $pdf->addTextWrap($Left_Margin+165,$YPos,900,$FontSize+2,_('Rs.'), 'left'); 
       $pdf->addTextWrap($Left_Margin+200,$YPos,900,$FontSize+1,$plantprice,'',0);        
       $pdf->addTextWrap($Left_Margin+415,$YPos,900,$FontSize+2,_('Rs.'), 'left');
       $pdf->addTextWrap($Left_Margin+440,$YPos,900,$FontSize+2,$tot_optprice,'',0);
       
//   $Rheight=0;
//   $Rheight=17*($item_count+1);
//   $pdf->rect($Left_Margin-2,$RY+=$line_height+7,500,$Rheight); 
//   $pdf->rect($Left_Margin+160,$RY,25,$Rheight);
//   $pdf->rect($Left_Margin+410,$RY,25,$Rheight);
//   $pdf->line($Left_Margin+250,$YPos-2,$Left_Margin+250,$YPos+$Rheight-2);       
//}   
 
 $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,500,$FontSize+3,_('Subsidy available'), 'center'); 
// $pdf->rect($Left_Margin-2,$RY+=$Rheight+$line_height-4,500,15);  
 
 $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height+5,900,$FontSize+2,_('BIOTECH'), 'left');  
 $pdf->addTextWrap($Left_Margin+150,$YPos,900,$FontSize+2,_('MNRE'), 'left');                                             
 $pdf->addTextWrap($Left_Margin+265,$YPos,900,$FontSize+2,_('Others'), 'left');                                             
 $pdf->addTextWrap($Left_Margin+395,$YPos,900,$FontSize+2,_('Total'), 'left'); 
                            

                                                                        
 $pdf->addTextWrap($Left_Margin+75,$YPos,900,$FontSize+1,$biosub,'',0); 
 $pdf->addTextWrap($Left_Margin+200,$YPos,900,$FontSize+1,$mnresub,'',0);
 $pdf->addTextWrap($Left_Margin+320,$YPos,900,$FontSize+1,$othersub,'',0); 

 $pdf->addTextWrap($Left_Margin+440,$YPos,900,$FontSize+1,$total_sub,'',0); 
 
//    $pdf->rect($Left_Margin-2,$RY+=$line_height+5,500,15);
//    $pdf->rect($Left_Margin+70,$RY,70,15);
//    $pdf->rect($Left_Margin+190,$RY,70,15);
//    $pdf->rect($Left_Margin+310,$RY,70,15);               
    

$pdf->addTextWrap($Left_Margin,$YPos-=$line_height+30,500,$FontSize+3,_('Project Cost'), 'left'); 
$pdf->addTextWrap($Left_Margin+400,$YPos,900,$FontSize+2,_('Rs'), 'left');  
$pdf->addTextWrap($Left_Margin+440,$YPos,50,$FontSize+2,$total_plantprice.".00",'right',0); 
$pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);     
//$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Tax'), 'left');  
//$pdf->addTextWrap($Left_Margin+400,$YPos,900,$FontSize+2,_('Rs'), 'left');   
//$tax="0.00";
//$pdf->addTextWrap($Left_Margin+440,$YPos,50,$FontSize+2,$tax,'right',0); 
//$pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos);   
//$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Price including Tax'), 'left');  
//$pdf->addTextWrap($Left_Margin+400,$YPos,900,$FontSize+2,_('Rs'), 'left'); 
//$taxprice=$total_plantprice+$tax;
//$pdf->addTextWrap($Left_Margin+440,$YPos,50,$FontSize+2,$taxprice.".00",'right',0);
//$pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Subsidy'), 'left');    
$pdf->addTextWrap($Left_Margin+400,$YPos,900,$FontSize+2,_('Rs'), 'left');     
$pdf->addTextWrap($Left_Margin+440,$YPos,50,$FontSize+2,$total_sub.".00",'right',0);  
$pdf->line($Left_Margin-2,$YPos-=2,$Left_Margin+498,$YPos); 
$pdf->addTextWrap($Left_Margin,$YPos-=$line_height,500,$FontSize+3,_('Net Price'), 'left');    
$pdf->addTextWrap($Left_Margin+400,$YPos,900,$FontSize+2,_('Rs'), 'left');     
$pdf->addTextWrap($Left_Margin+440,$YPos,50,$FontSize+2,$total_plantprice_sub.".00",'right',0);      
    
    
//      $pdf->rect($Left_Margin-2,$RY+=$line_height+27,500,17*3);
//      $pdf->rect($Left_Margin+395,$RY,25,17*3);
 //     $pdf->line($Left_Margin+160,$YPos-2,$Left_Margin+160,$YPos+(17*3)-2); 
 /*                                         
     
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
                                                                                            */
                     
 $pdf->OutputD($_SESSION['DatabaseName'] . 'CP Covering letter_' . Date('Y-m-d') . '.pdf');
 $pdf->__destruct();   
?>
