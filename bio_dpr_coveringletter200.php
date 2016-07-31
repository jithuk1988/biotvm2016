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
    $leadid=$_POST['LeadID'];
    $dprID=$_POST['DprID'];
    
    
    $sql1="select bio_leads.leadid,
                bio_leads.cust_id,
                bio_leads.leaddate,
                bio_leads.enqtypeid,
                bio_leads.outputtypeid,
                bio_enquirytypes.enquirytype,
                bio_outputtypes.outputtype,
                bio_dpr.dpr_id,
                bio_dpr.wages,
                bio_dpr.amcexpense,
                bio_dpr.totalcost,
                bio_dpr.completiondate,
                bio_conceptproposal.plant,
                bio_dpr.depreciation,
                bio_dpr.created_by,
                bio_dpr.approved_by,
                bio_dpr.signatory_by
        from bio_leads,bio_dpr,bio_conceptproposal,bio_enquirytypes,bio_outputtypes 
        where bio_leads.leadid=bio_dpr.leadid
        and bio_dpr.leadid=bio_conceptproposal.lead_id
        and bio_enquirytypes.enqtypeid=bio_leads.enqtypeid
        and bio_outputtypes.outputtypeid=bio_leads.outputtypeid
        and bio_dpr.dpr_id=".$dprID;
        

  $result1=DB_query($sql1,$db);
  while($row1=DB_fetch_array($result1))
  {
      
      
      $leadid=$row1['leadid'];
      $leaddate=ConvertSQLDate($row1['leaddate']);
      $custid=$row1['cust_id'];
      $wages=$row1['wages'];
      $expenceamc=$row1['amcexpense'];
      $depreciation1=$row1['depreciation'];
      $total_dpr=$row1['totalcost'];
      $completiondate=ConvertSQLDate($row1['completiondate']);
      $plantid=$row1['plant'];
      $enqtype=$row1['enquirytype'];
      $outtype=$row1['outputtype'];
      $id=$row1['dpr_id'];
      $outputtypeid=$row1['outputtypeid'];
      $created_by=$row1['created_by'];
      $approved_by=$row1['approved_by'];
      $signatory_by=$row1['signatory_by'];
      
      
  }
  $sql_emp="SELECT  bio_emp.empname
        FROM  bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid='".$signatory_by."'";
  $result_emp=DB_query($sql_emp,$db);
  $myrow_emp=DB_fetch_array($result_emp);
  $empname=$myrow_emp['empname'];     
     $sql2="select bio_cust.custname,
          bio_cust.houseno,
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
      $houseno=$row2['houseno'];
      $area=$row2['area1'];
      $housename=$row2['housename'];
      $district=$row2['district'];
  }    
      
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
      
      $item=array();
      $item_name=array();
      $quantity=array();
      $unit_price=array();
      $amount=array();
      $sql_items="SELECT bio_dprsubassemblies.subassembly,
                         bio_dprsubassemblies.quantity,
                         bio_dprsubassemblies.price,
                         stockmaster.longdescription
                  FROM bio_dprsubassemblies,stockmaster 
                  WHERE dpr_id=".$dprID."
                  AND stockmaster.stockid=bio_dprsubassemblies.subassembly";
      $result_items=DB_query($sql_items,$db);
      while($myrow_items=DB_fetch_array($result_items)){
           $item[]=$myrow_items['subassembly'];
           $item_name[]=$myrow_items['longdescription'];
           $quantity[]=$myrow_items['quantity'];
           $unit_price[]=$myrow_items['price'];
           $amount[]=$myrow_items['price']*$myrow_items['quantity'];
           $totalprice+=$myrow_items['price']*$myrow_items['quantity'];
          
      }
      
     $item_count=sizeof($item); 
      
      
     $total=$totalprice+$wages+$expenceamc; 
     $subsidy=0;
     $net_amount=$total-$subsidy; 
    
     $plant_value=array();
     $sql_1="SELECT stockmaster.stockid,
                     stockcatproperties.label,
                     stockitemproperties.value
              FROM stockmaster,stockcatproperties,stockitemproperties
              WHERE stockmaster.stockid='$plantid2[0]'
              AND stockmaster.stockid = stockitemproperties.stockid
              AND stockmaster.categoryid = stockcatproperties.categoryid
              AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid";
      $result_1=DB_query($sql_1,$db);
      while($myrow_1=DB_fetch_array($result_1)){
          $plant_value[]=$myrow_1['value'];
           
      }
  
  
      $size=$plant_value[1];
      $organic_waste_capacity=$plant_value[6];
      $edeg_waste_capacity=$plant_value[7];
      $sdeg_waste_capacity=$plant_value[8]; 
      $organic_water_capacity=$plant_value[9];
      $biogas=$plant_value[10];
      
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
         
//         $total_annual_income=$annual_biogas+$annual_organic_fertilizer;
         
         $annual_carbon_credit=$carbon_credit*365;
             
         if($outputtypeid==3){
             $total_annual_income=$annual_autofuel_income+$annual_organic_fertilizer;
        }
        elseif($outputtypeid==2){
              $total_annual_income=$annual_electricity_income+$annual_organic_fertilizer;
        }
        elseif($outputtypeid==1){
             $total_annual_income=$annual_lpg_income+$annual_organic_fertilizer;
        }
    
    
     
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $Xpos = $Left_Margin+5;
    $YPos -=(2*$line_height);
    
    
    $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
    $pdf->addJpegFromFile($img1,$XPos+392,$YPos-=$line_height,0,55);
//    echo"1st".$YPos;
    $img2= 'companies/'.$_SESSION['DatabaseName'].'/logof.jpg';
    $pdf->addJpegFromFile($img2,$XPos+35,$YPos,0,55);  
    $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);
    
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+12,100,$FontSize,$a, 'left');
    $pdf->addTextWrap($Page_Width-$Right_Margin-80,$YPos,90,$FontSize,Date($_SESSION['DefaultDateFormat']), 'right');
    
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,100,$FontSize,_('To'), 'left');
    if($name!=""){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize,$name,'',0);
    }
    
    if($housename!=""){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-2,100,$FontSize,$housename,'',0);
    }
    
    if($area!=""){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-2,100,$FontSize,$area,'',0);
    } 
   
    
    if($district!=""){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-2,100,$FontSize,$district,'',0);
    }
    
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,100,$FontSize,_('Dear Sir'), 'left');
    $subject='Sub: Our detailed project proposal for implementing solid waste management plant';
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,800,$FontSize,$subject,'',0);
    $subject='to convert solid waste to '.$outtype.'.';
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,800,$FontSize,$subject,'',0);
    $reference='Ref:';
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,800,$FontSize,$reference,'',0);
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,800,$FontSize,_('We are pleased to submit our detailed project proposal that consists of '), 'left');
    
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height+5,800,$FontSize,_('1.  General information about the project'), 'left');        
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height-2,200,$FontSize,_('2.  Technical details of the plant and associated equipments'), 'left');   
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height-2,200,$FontSize,_('3.  Worksheet of the return of investment '), 'left');
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height-2,200,$FontSize,_('4.  Detailed estimate of the plant with BOQ'), 'left');        
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height-2,200,$FontSize,_('5.  Process diagram of the plant'), 'left');             
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height-2,200,$FontSize,_('6.  Block diagram of the plant'), 'left');  
    $pdf->addTextWrap($Left_Margin+30,$YPos-=$line_height-2,200,$FontSize,_('7.  Commercial terms and condition'), 'left');
    
    $cost_string='The total cost for implementing this project is Rs.'.$net_amount.' /-';
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,800,$FontSize,$cost_string,'',0);

    $roi='The annual return from the plant is Rs.'.' /-';
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-2,800,$FontSize,$roi,'',0);
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,800,$FontSize,_('In case of any clarification required, please feel free to get in touch with us.'), 'left'); 
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,800,$FontSize,_('We are sure you will find our project proposal technically and financially suiting your requirement'), 'left');            
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height-2,800,$FontSize,_('and look forward to receive your contract for the plant at the earliest.'), 'left');      
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+15,300,$FontSize,_('Thanking you'), 'left');      
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,300,$FontSize,_('Yours faithfully'), 'left');
     
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,800,$FontSize,$empname,'',0);  
//    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,800,$FontSize,,'',0);


    $YPos=$Bottom_Margin + $line_height;
    $page='Page No '.$PageNumber;
    $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
    
    $PageNumber++; 
    
    $pdf->newPage();
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $Xpos = $Left_Margin+5;
    $YPos -=(2*$line_height);
//    $pdf->addTextWrap($Left_Margin,$YPos+=10,800,$FontSize,44444444444444444444,'',0);
//    echo"2nd".$YPos;
    
    $pdf->addTextWrap($Left_Margin+140,$YPos+=10,140,$FontSize+3,_('Detailed Estimate'),'center');
    $pdf->line($Left_Margin+160,$YPos-2,$Left_Margin+260,$YPos-2);
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+20,140,$FontSize+2,_('No. '),'left');
    $pdf->addTextWrap($Left_Margin+45,$YPos,140,$FontSize+2,_('Description'),'left');
    $pdf->addTextWrap($Left_Margin+220,$YPos,140,$FontSize+2,_('Quantity'),'left');
    $pdf->addTextWrap($Left_Margin+300,$YPos,140,$FontSize+2,_('Unit price'),'left');   
    $pdf->addTextWrap($Left_Margin+380,$YPos,140,$FontSize+2,_('Amount'),'left');
    $no=1;
    for($i=0;$i<$item_count;$i++){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,140,$FontSize,$no,'',0);
        $pdf->addTextWrap($Left_Margin+45,$YPos,140,$FontSize,$item_name[$i],'',0);  
        $pdf->addTextWrap($Left_Margin+230,$YPos,140,$FontSize,$quantity[$i],'',0);
        $pdf->addTextWrap($Left_Margin+310,$YPos,140,$FontSize,$unit_price[$i],'',0);
        $pdf->addTextWrap($Left_Margin+390,$YPos,140,$FontSize,$amount[$i],'',0);
        $no++;
        
        if ($YPos < $Bottom_Margin + $line_height+40){
            $YPos=$Bottom_Margin + $line_height;
            $page='Page No '.$PageNumber;
            $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
            $PageNumber++; 
            $pdf->newPage();
            $FontSize=9;
            $YPos= $Page_Height-$Top_Margin;   
            $line_height=15;        
            $Xpos = $Left_Margin+5;
            $YPos -=(2*$line_height);
            $YPos+=30;      
        }
    }
 
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,140,$FontSize,$no++,'',0);
    $pdf->addTextWrap($Left_Margin+45,$YPos,140,$FontSize,'Wages of Operation','',0);
    $pdf->addTextWrap($Left_Margin+230,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+310,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+390,$YPos,140,$FontSize,$wages,'',0);
    
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,140,$FontSize,$no++,'',0);
    $pdf->addTextWrap($Left_Margin+45,$YPos,140,$FontSize,'AMC Expenses','',0);
    $pdf->addTextWrap($Left_Margin+230,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+310,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+390,$YPos,140,$FontSize,$expenceamc,'',0);
    
    $pdf->addTextWrap($Left_Margin+45,$YPos-=$line_height+10,140,$FontSize,_('Total Price '),'left');
    $pdf->addTextWrap($Left_Margin+230,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+310,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+390,$YPos,140,$FontSize,$total,'',0);
    
    $pdf->addTextWrap($Left_Margin+45,$YPos-=$line_height+10,140,$FontSize,_('Subsidy available from '),'left');
    $pdf->addTextWrap($Left_Margin+230,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+310,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+390,$YPos,140,$FontSize,$subsidy,'',0);
    
    $pdf->addTextWrap($Left_Margin+45,$YPos-=$line_height+10,140,$FontSize,_('Net amount after Subsidy '),'left');
    $pdf->addTextWrap($Left_Margin+230,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+310,$YPos,140,$FontSize,'-------','',0);
    $pdf->addTextWrap($Left_Margin+390,$YPos,140,$FontSize,$net_amount,'',0);
 
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,140,$FontSize+2,_('For Biotech I Pvt Ltd'),'left');
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+2,140,$FontSize+2,_('Marketing manager'),'left');

    $YPos=$Bottom_Margin + $line_height;
    $page='Page No '.$PageNumber;
    $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
 
    $PageNumber++; 
    $pdf->newPage();
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $Xpos = $Left_Margin+5;
    $YPos -=(2*$line_height);
    
    $pdf->addTextWrap($Left_Margin,$YPos+=10,800,$FontSize+3,_('Return on investment(ROI)of the project'),'Left');
    $pdf->line($Left_Margin,$YPos-2,$Left_Margin+210,$YPos-2);
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,150,$FontSize,_('Waste treatment capacity of plant per day'),'left');                 $pdf->addTextWrap($Left_Margin+240,$YPos,50,$FontSize,_('-'),'left');               $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,$organic_waste_capacity,'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,200,$FontSize,_('Treatment of easily degradable waste per day'),'left');              $pdf->addTextWrap($Left_Margin+240,$YPos,50,$FontSize,_('-'),'left');               $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($edeg_waste_capacity),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,200,$FontSize,_('Treatment of slowly degradable waste per day'),'left');              $pdf->addTextWrap($Left_Margin+240,$YPos,50,$FontSize,_('-'),'left');               $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($sdeg_waste_capacity),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Treatment of organic waste water per day'),'left');                  $pdf->addTextWrap($Left_Margin+240,$YPos,50,$FontSize,_('-'),'left');               $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($organic_water_capacity),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Generation of biogas per day'),'left');                              $pdf->addTextWrap($Left_Margin+240,$YPos,50,$FontSize,_('-'),'left');              $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($biogas),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Generataion of solid fertilizer per day '),'left');                  $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($solid_fertilizer),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Generation of liquid fertilizer per day'),'left');                   $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($liquid_fertilizer),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,140,$FontSize,_('Cost of 1 Cum biogas '),'left');                                     $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($cost_biogas),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Cost of 1 Kg. solid manure'),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($cost_solid),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Cost of 1 litter liquid manure'),'left');                            $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($cost_liquid),'left');
    if($outputtypeid==3){
        $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Cost of 1 Kg.Auto fuel'),'left');                                    $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($cost_fuel),'left');
        $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,140,$FontSize,_('Annual income as auto fuel '),'left');                               $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($annual_autofuel_income),'left');
    }
    elseif($outputtypeid==2){
        $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Cost of 1 unit Electricity'),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($cost_electricity),'left');
        $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,140,$FontSize,_('Annual income as electricity '),'left');                             $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($annual_electricity_income),'left');
    }
    elseif($outputtypeid==1){
        $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Cost of 1 Kg LPG'),'left');                                          $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($cost_LPG),'left');
        $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,140,$FontSize,_('Annual income from biogas '),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($annual_biogas_income),'left');
    }
           
//           $pdf->addTextWrap($Left_Margin,$YPos-=$line_height,140,$FontSize,_('1 Cum biogas as cooking gas'),'left');                               $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-           Kg  LPG'),'left'); $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($gas),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Annual income-solid fertilizer'),'left');                            $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($annual_solidfert_income),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Annual income-liquid fertilizer'),'left');                           $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($annual_liquidfert_income),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Total income'),'left');                                              $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($total_annual_income),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+20,140,$FontSize,_('Less wages for operation '),'left');                                 $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($wages),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('AMC expenses '),'left');                                             $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($expenceamc),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Depreciation '),'left');                                             $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($depreciation1),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Net income '),'left');                                               $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($a),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Net investment'),'left');                                            $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($total),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('Income tax depreciation benefits'),'left');                          $pdf->addTextWrap($Left_Margin+240,$YPos,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos,50,$FontSize,_($a),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize,_('(80% of investment)'),'left');           
                  
    $YPos=$Bottom_Margin + $line_height;
    $page='Page No '.$PageNumber;
    $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
    
    $PageNumber++; 
    $pdf->newPage();
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $Xpos = $Left_Margin+5;
    $YPos -=(2*$line_height);
    
    $pdf->addTextWrap($Left_Margin+140,$YPos+=10,140,$FontSize+3,_('Technical Details of the Project'),'center');
    $pdf->line($Left_Margin+140,$YPos-2,$Left_Margin+300,$YPos-2);
    
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,500,$FontSize,_('Type of waste (a) easily degradable'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos,50,$FontSize,_('-                Kg'),'left');                       $pdf->addTextWrap($Left_Margin+210,$YPos,50,$FontSize,$a,'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,500,$FontSize,_('Type of waste (b) slow degradable'),'left');  $pdf->addTextWrap($Left_Margin+200,$YPos,50,$FontSize,_('-                Kg'),'left');                     $pdf->addTextWrap($Left_Margin+210,$YPos,50,$FontSize,$a,'left');
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,400,$FontSize,_('Organic waste water'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos,100,$FontSize,_('-                Litres'),'left');                                $pdf->addTextWrap($Left_Margin+210,$YPos,50,$FontSize,$a,'left');                    
                                                                                                                                                                                                                                
    $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,400,$FontSize,_('Total quantity of Biogas generation'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos,100,$FontSize,_('-                Cum'),'left');                  $pdf->addTextWrap($Left_Margin+210,$YPos,50,$FontSize,$a,'left');
  if($outputtypeid==1){                                                                                                                                                                                                                               
         $pdf->addTextWrap($Left_Margin+60,$YPos-=$line_height+2,500,$FontSize,_('                      Cum Biogas'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos,100,$FontSize,_('-                LPG'),'left');                  $pdf->addTextWrap($Left_Margin+210,$YPos,50,$FontSize,$a,'left');    $pdf->addTextWrap($Left_Margin+40,$YPos,50,$FontSize,$a,'left');
  }elseif($outputtypeid==2){
         $pdf->addTextWrap($Left_Margin+60,$YPos-=$line_height+2,500,$FontSize,_('                      Cum Biogas'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos,100,$FontSize,_('-                Electricity'),'left');          $pdf->addTextWrap($Left_Margin+210,$YPos,50,$FontSize,$a,'left');    $pdf->addTextWrap($Left_Margin+40,$YPos,50,$FontSize,$a,'left');
  }elseif($outputtypeid==3){     
         $pdf->addTextWrap($Left_Margin+60,$YPos-=$line_height+2,500,$FontSize,_('                      Cum Biogas'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos,100,$FontSize,_('-                Auto Fuel'),'left');            $pdf->addTextWrap($Left_Margin+210,$YPos,50,$FontSize,$a,'left');    $pdf->addTextWrap($Left_Margin+40,$YPos,50,$FontSize,$a,'left');
  }                    

 
 
 
 
 
 
 
    $YPos=$Bottom_Margin + $line_height;
    $page='Page No '.$PageNumber;
    $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 /*
 function createNewPage($pdf,$PageNumber,$Page_Height,$Top_Margin,$line_height,$Left_Margin){
     $PageNumber++; 
           $pdf->newPage();
           $FontSize=9;
           $YPos= $Page_Height-$Top_Margin;   
           $line_height=15;        
           $Xpos = $Left_Margin+5;
           $YPos -=(2*$line_height);
     
 }
 
 */
 
 $pdf->OutputD($_SESSION['DatabaseName'] . '_Detailed Project Proposal_' . Date('Y-m-d') . '.pdf');
 $pdf->__destruct();     
?>
