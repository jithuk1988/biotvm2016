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

  if($_GET['dprid']==""){
      $_GET['dprid']=$_SESSION['dprid'];
      $dprID=$_GET['dprid'];
      unset($_SESSION['dprid']);  
  }
  $sql1="select bio_leads.leadid,
                bio_leads.cust_id,
                bio_leads.leaddate,
                bio_dpr.wages,
                bio_dpr.amcexpense,
                bio_dpr.totalcost,
                bio_dpr.completiondate,
                bio_conceptproposal.plant,
                bio_dpr.depreciation
        from bio_leads,bio_dpr,bio_conceptproposal 
        where bio_leads.leadid=bio_dpr.leadid
        and bio_dpr.leadid=bio_conceptproposal.lead_id
        and bio_dpr.dpr_id=".$_GET['dprid'];
  $result1=DB_query($sql1,$db);
  while($row1=DB_fetch_array($result1))
  {
      $leadid=$row1['leadid'];
      $leaddate=ConvertSQLDate($row1['leaddate']);
      $custid=$row1['cust_id'];
      $wages=$row1['wages'];
      $expenceamc=$row1['amcexpense'];
      $depreciation1=$row1['depreciation'];
      $total=$row1['totalcost'];
      $completiondate=ConvertSQLDate($row1['completiondate']);
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
  
      $sql_subas="SELECT subassembly,quantity
                  FROM bio_dprsubassemblies
                  WHERE dpr_id='$dprID'";
    $result_subas=DB_query($sql_subas,$db);
    $subas="";
    $subassemblyPrice=0;
    while($myrow_subas=DB_fetch_array($result_subas)){
        $subassembly1=$myrow_subas['subassembly'];
        $quantity=$myrow_subas['quantity'];
        $subassembly2=explode(',',$subassembly1);
        $n=sizeof($subassembly2);
        
        for($i=0;$i<$n;$i++)        {
            $sql="SELECT description,materialcost
                    FROM stockmaster
                    WHERE stockid='$subassembly2[$i]'";
            $result=DB_query($sql,$db);
            $myrow=DB_fetch_array($result);
            $subas=$myrow[0].",".$subas;
            
            $sql_price="SELECT price
                          FROM prices
                          WHERE stockid='".$subassembly2[$i]."'";
            $result_price=DB_query($sql_price,$db);
            $myrow_price=DB_fetch_array($result_price);
            $subPrice=$myrow_price['price'];
            $subassemblyPrice=$subassemblyPrice+($quantity*$subPrice);
            
        }
    }
    $sql_cost="SELECT unitprice
                FROM bio_costbenefitmaster
                WHERE commercialproduct_id=7";
    $result_cost=DB_query($sql_cost,$db);
    $myrow_cost=DB_fetch_array($result_cost);
    $cost_solid=$myrow_cost['unitprice'];
  }
  $subas = substr($subas,0,-1);
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
  
  $cost_biogas=15;
  
  $sql_cost="SELECT unitprice
                FROM bio_costbenefitmaster
                WHERE commercialproduct_id=5";
  $result_cost=DB_query($sql_cost,$db);
  $myrow_cost=DB_fetch_array($result_cost);
  $cost_solid=$myrow_cost['unitprice'];
  
  $sql_cost="SELECT unitprice
                FROM bio_costbenefitmaster
                WHERE commercialproduct_id=6";
  $result_cost=DB_query($sql_cost,$db);
  $myrow_cost=DB_fetch_array($result_cost);
  $cost_liquid=$myrow_cost['unitprice'];
  
  $sql_cost="SELECT unitprice
                FROM bio_costbenefitmaster
                WHERE commercialproduct_id=4";
  $result_cost=DB_query($sql_cost,$db);
  $myrow_cost=DB_fetch_array($result_cost);
  $cost_fuel=$myrow_cost['unitprice'];
  
  $sql_cost="SELECT unitprice
                FROM bio_costbenefitmaster
                WHERE commercialproduct_id=2";
  $result_cost=DB_query($sql_cost,$db);
  $myrow_cost=DB_fetch_array($result_cost);
  $cost_electricity=$myrow_cost['unitprice'];
  
  $sql_cost="SELECT unitprice
                FROM bio_costbenefitmaster
                WHERE commercialproduct_id=1";
  $result_cost=DB_query($sql_cost,$db);
  $myrow_cost=DB_fetch_array($result_cost);
  $cost_LPG=$myrow_cost['unitprice'];
  
  $sql_cost="SELECT quantity,product_unit
                FROM bio_costbenefitmaster
                WHERE commercialproduct_id=1";
  $result_cost=DB_query($sql_cost,$db);
  $myrow_cost=DB_fetch_array($result_cost);
  $gas=$myrow_cost['quantity'];
  
  
  $sql_cost="SELECT quantity,product_unit
                FROM bio_costbenefitmaster
                WHERE commercialproduct_id=1";
  $result_cost=DB_query($sql_cost,$db);
  $myrow_cost=DB_fetch_array($result_cost);
  $gas=$myrow_cost['quantity'];
  
  
  $sql_day="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockmaster.stockid = '".$plantid2[0]."'
               AND stockcatproperties.label='Waste Treatment capacity of Plant per day'";
  $result_day=DB_query($sql_day,$db);
  $myrow_day=DB_fetch_array($result_day);
  $waste_treatment=$myrow_day['value'];
  
  $sql_day="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockmaster.stockid = '".$plantid2[0]."'
               AND stockcatproperties.label='Treatment of easily degradable waste per day'";
  $result_day=DB_query($sql_day,$db);
  $myrow_day=DB_fetch_array($result_day);
  $waste_easdegradable=$myrow_day['value'];
  
  $sql_day="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockmaster.stockid = '".$plantid2[0]."'
               AND stockcatproperties.label='Treatment of slowly degradable waste per day'";
  $result_day=DB_query($sql_day,$db);
  $myrow_day=DB_fetch_array($result_day);
  $waste_slowdegradable=$myrow_day['value'];
  
  $sql_day="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockmaster.stockid = '".$plantid2[0]."'
               AND stockcatproperties.label='Treatment of Organic waste water per day'";
  $result_day=DB_query($sql_day,$db);
  $myrow_day=DB_fetch_array($result_day);
  $waste_organic=$myrow_day['value'];
  
  $sql_day="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockmaster.stockid = '".$plantid2[0]."'
               AND stockcatproperties.label='Generation of Biogas per day'";
  $result_day=DB_query($sql_day,$db);
  $myrow_day=DB_fetch_array($result_day);
  $biogas=$myrow_day['value'];
  
  $annual_biogas=$biogas*365;
  $annual_biogas_income=$annual_biogas*$cost_biogas;
  
  $annual_electricity=1.5*$biogas*365;
  $annual_electricity_income=$annual_electricity*$cost_electricity;
  
  $annual_autofuel=0.6*$biogas*365;
  $annual_autofuel_income=$annual_autofuel*$cost_fuel;
  
      
  $sql_day="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockmaster.stockid = '".$plantid2[0]."'
               AND stockcatproperties.label='Generation of Solid fertilizer per day'";
  $result_day=DB_query($sql_day,$db);
  $myrow_day=DB_fetch_array($result_day);
  $solid_fertilizer=$myrow_day['value'];
  $annual_solidfert=0.05*$solid_fertilizer*360;
  $annual_solidfert_income=$annual_solidfert*$cost_solid;
  
  $sql_day="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockmaster.stockid = '".$plantid2[0]."'
               AND stockcatproperties.label='Generation of Liquid fertilizer per day'";
  $result_day=DB_query($sql_day,$db);
  $myrow_day=DB_fetch_array($result_day);
  $liquid_fertilizer=$myrow_day['value'];
  $annual_liquidfert=$liquid_fertilizer*360;
  $annual_liquidfert_income=$annual_liquidfert*$cost_liquid;
  
  
       
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
              $pdf->addTextWrap($Left_Margin+150,$YPos-400,300,$FontSize,$completiondate,'',0); 
                        
                     
   if ($YPos < $Bottom_Margin + $line_height){                                                       //------if data exeeds page height------//
        PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
        $Right_Margin,$CatDescription);
    }
          
          
          
$pdf->newPage();
           $pdf->addTextWrap(140,$YPos+10,140,$FontSize+3,_('Quotation'),'center');
          
           $pdf->addTextWrap($Left_Margin+20,$YPos-50,140,$FontSize+2,_('Description'),'left');
           $pdf->addTextWrap($Left_Margin+140,$YPos-50,140,$FontSize+2,_('Unit price'),'left');
           $pdf->addTextWrap($Left_Margin+190,$YPos-50,140,$FontSize+2,_('Quantity'),'left');   
           $pdf->addTextWrap($Left_Margin+240,$YPos-50,140,$FontSize+2,_('Total'),'left');  
           
//           $pdf->addTextWrap($Left_Margin+20,$YPos-130,140,$FontSize+2,_('sdff'),'left');
           $pdf->addTextWrap($Left_Margin+20,$YPos-150,140,$FontSize+2,_('Total Price '),'left');
           $pdf->addTextWrap($Left_Margin+20,$YPos-170,140,$FontSize+2,_('Subsidy of '),'left');
           $pdf->addTextWrap($Left_Margin+20,$YPos-190,140,$FontSize+2,_('Net amount '),'left');

           $pdf->addTextWrap($Left_Margin+20,$YPos-450,140,$FontSize+2,_('For Biotech I Pvt Ltd'),'left');
           $pdf->addTextWrap($Left_Margin+20,$YPos-500,140,$FontSize+2,_('Marketing manager'),'left');
           
           
           
           $pdf->addTextWrap($Left_Margin+20,$YPos-70,140,$FontSize,$plants,'',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-70,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-70,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-70,140,$FontSize,$plantprice,'',0);
           
           $pdf->addTextWrap($Left_Margin+20,$YPos-90,140,$FontSize,$subas,'',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-90,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-90,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-90,140,$FontSize,$subassemblyPrice,'',0);
           
           $pdf->addTextWrap($Left_Margin+20,$YPos-110,140,$FontSize,'Wages of Operation','',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-110,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-110,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-110,140,$FontSize,$wages,'',0);
           
           $pdf->addTextWrap($Left_Margin+20,$YPos-130,140,$FontSize,'AMC Expenses','',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-130,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-130,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-130,140,$FontSize,$expenceamc,'',0);
           
            //           $pdf->addTextWrap($Left_Margin+20,$YPos-130,140,$FontSize,10,'',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-150,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-150,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-150,140,$FontSize,$total,'',0);
           
           //           $pdf->addTextWrap($Left_Margin+20,$YPos-130,140,$FontSize,10,'',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-170,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-170,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-170,140,$FontSize,$a,'',0);
           
           //           $pdf->addTextWrap($Left_Margin+20,$YPos-130,140,$FontSize,10,'',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-190,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-190,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-190,140,$FontSize,$a,'',0);
           
           //           $pdf->addTextWrap($Left_Margin+20,$YPos-130,140,$FontSize,10,'',0);
           $pdf->addTextWrap($Left_Margin+150,$YPos-210,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+200,$YPos-210,140,$FontSize,$a,'',0);
           $pdf->addTextWrap($Left_Margin+250,$YPos-210,140,$FontSize,$a,'',0);
           
           $pdf->rect(58,90,300,160); 
           $pdf->rect(58,110,300,120); 
           $pdf->rect(58,130,300,80);
           $pdf->rect(58,150,300,20);
           $pdf->rect(58,170,300,20);
           
           $pdf->rect(178,90,50,160);
           $pdf->rect(278,90,80,160);
           
$pdf->newPage(); 
           $pdf->addTextWrap($Left_Margin,$YPos+10,140,$FontSize+3,_('Return on investment(ROI)of the project'),'Left');
                                                                                                                                            
           $pdf->addTextWrap($Left_Margin,$YPos-20,150,$FontSize,_('Waste treatment capacity of plant per day'),'left');                  $pdf->addTextWrap($Left_Margin+240,$YPos-20,50,$FontSize,_('-'),'left');               $pdf->addTextWrap($Left_Margin+250,$YPos-20,50,$FontSize,$waste_treatment,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-40,200,$FontSize,_('Treatment of easily degradable waste per day'),'left');               $pdf->addTextWrap($Left_Margin+240,$YPos-40,50,$FontSize,_('-'),'left');               $pdf->addTextWrap($Left_Margin+250,$YPos-40,50,$FontSize,_($waste_easdegradable),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-60,200,$FontSize,_('Treatment of slowly degradable waste per day'),'left');               $pdf->addTextWrap($Left_Margin+240,$YPos-60,50,$FontSize,_('-'),'left');               $pdf->addTextWrap($Left_Margin+250,$YPos-60,50,$FontSize,_($waste_slowdegradable),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-80,140,$FontSize,_('Treatment of organic waste water per day'),'left');                   $pdf->addTextWrap($Left_Margin+240,$YPos-80,50,$FontSize,_('-'),'left');               $pdf->addTextWrap($Left_Margin+250,$YPos-80,50,$FontSize,_($waste_organic),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-100,140,$FontSize,_('Generation of biogas per day'),'left');                              $pdf->addTextWrap($Left_Margin+240,$YPos-100,50,$FontSize,_('-'),'left');              $pdf->addTextWrap($Left_Margin+250,$YPos-100,50,$FontSize,_($biogas),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-120,140,$FontSize,_('Generataion of solid fertilizer per day '),'left');                  $pdf->addTextWrap($Left_Margin+240,$YPos-120,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-120,50,$FontSize,_($solid_fertilizer),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-140,140,$FontSize,_('Generation of liquid fertilizer per day'),'left');                   $pdf->addTextWrap($Left_Margin+240,$YPos-140,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-140,50,$FontSize,_($liquid_fertilizer),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-160,140,$FontSize,_('Cost of 1 Cum biogas '),'left');                                     $pdf->addTextWrap($Left_Margin+240,$YPos-160,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-160,50,$FontSize,_($cost_biogas),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-180,140,$FontSize,_('Cost of 1 Kg. solid manure'),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos-180,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-180,50,$FontSize,_($cost_solid),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-200,140,$FontSize,_('Cost of 1 litter liquid manure'),'left');                            $pdf->addTextWrap($Left_Margin+240,$YPos-200,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-200,50,$FontSize,_($cost_liquid),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-220,140,$FontSize,_('Cost of 1 Kg.Auto fuel'),'left');                                    $pdf->addTextWrap($Left_Margin+240,$YPos-220,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-220,50,$FontSize,_($cost_fuel),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-240,140,$FontSize,_('Cost of 1 unit Electricity'),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos-240,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-240,50,$FontSize,_($cost_electricity),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-260,140,$FontSize,_('Cost of 1 Kg LPG'),'left');                                          $pdf->addTextWrap($Left_Margin+240,$YPos-260,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-260,50,$FontSize,_($cost_LPG),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-280,140,$FontSize,_('1 Cum biogas as cooking gas'),'left');                               $pdf->addTextWrap($Left_Margin+240,$YPos-280,140,$FontSize,_('-           Kg  LPG'),'left'); $pdf->addTextWrap($Left_Margin+250,$YPos-280,50,$FontSize,_($gas),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-300,140,$FontSize,_('Annual income from biogas '),'left');                                $pdf->addTextWrap($Left_Margin+240,$YPos-300,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-300,50,$FontSize,_($annual_biogas_income),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-320,140,$FontSize,_('Annual income as electricity '),'left');                             $pdf->addTextWrap($Left_Margin+240,$YPos-320,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-320,50,$FontSize,_($annual_electricity_income),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-340,140,$FontSize,_('Annual income as auto fuel '),'left');                               $pdf->addTextWrap($Left_Margin+240,$YPos-340,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-340,50,$FontSize,_($annual_autofuel_income),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-360,140,$FontSize,_('Annual income-solid fertilizer'),'left');                            $pdf->addTextWrap($Left_Margin+240,$YPos-360,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-360,50,$FontSize,_($annual_solidfert_income),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-380,140,$FontSize,_('Annual income-liquid fertilizer'),'left');                           $pdf->addTextWrap($Left_Margin+240,$YPos-380,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-380,50,$FontSize,_($annual_liquidfert_income),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-400,140,$FontSize,_('Total income'),'left');                                              $pdf->addTextWrap($Left_Margin+240,$YPos-400,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-400,50,$FontSize,_($a),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-420,140,$FontSize,_('Less wages for operation '),'left');                                 $pdf->addTextWrap($Left_Margin+240,$YPos-420,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-420,50,$FontSize,_($wages),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-440,140,$FontSize,_('AMC expenses '),'left');                                             $pdf->addTextWrap($Left_Margin+240,$YPos-440,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-440,50,$FontSize,_($expenceamc),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-460,140,$FontSize,_('Depreciation '),'left');                                             $pdf->addTextWrap($Left_Margin+240,$YPos-460,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-460,50,$FontSize,_($depreciation1),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-480,140,$FontSize,_('Net income '),'left');                                               $pdf->addTextWrap($Left_Margin+240,$YPos-480,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-480,50,$FontSize,_($a),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-500,140,$FontSize,_('Net investment'),'left');                                            $pdf->addTextWrap($Left_Margin+240,$YPos-500,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-500,50,$FontSize,_($total),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-520,140,$FontSize,_('Income tax depreciation benefits'),'left');                          $pdf->addTextWrap($Left_Margin+240,$YPos-520,140,$FontSize,_('-'),'left');             $pdf->addTextWrap($Left_Margin+250,$YPos-520,50,$FontSize,_($a),'left');
           $pdf->addTextWrap($Left_Margin,$YPos-530,140,$FontSize,_('(80% of investment)'),'left');           
                  

$pdf->newPage();
         $pdf->addTextWrap(150,$YPos-20,140,$FontSize+3,_('Technical Details of the Project'),'center');
         $pdf->line(145,$YPos-23,295,$YPos-23);  
        
         $Xpos = $Left_Margin+5;
         $YPos -=(2*$line_height); 
         
         
         $pdf->addTextWrap($Left_Margin,$YPos-30,100,$FontSize+2,_('A - Out come of the Project'), 'left');
         $pdf->line($Left_Margin,$YPos-33,$Left_Margin+125,$YPos-33);
         
         $pdf->addTextWrap($Left_Margin,$YPos-55,100,$FontSize+1,_('Quantity of waste input'),'left'); 
         $pdf->line($Left_Margin,$YPos-58,$Left_Margin+95,$YPos-58);

         
         
        $pdf->addTextWrap($Left_Margin,$YPos-80,500,$FontSize,_('Type of waste (a) easily degradable'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos-80,50,$FontSize,_('-                Kg'),'left');                       $pdf->addTextWrap($Left_Margin+210,$YPos-80,50,$FontSize,$a,'left');
        $pdf->addTextWrap($Left_Margin,$YPos-100,500,$FontSize,_('Type of waste (b) slow degradable'),'left');  $pdf->addTextWrap($Left_Margin+200,$YPos-100,50,$FontSize,_('-                Kg'),'left');                     $pdf->addTextWrap($Left_Margin+210,$YPos-100,50,$FontSize,$a,'left');
        $pdf->addTextWrap($Left_Margin,$YPos-120,400,$FontSize,_('Organic waste water'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos-120,100,$FontSize,_('-                Litter'),'left');                                $pdf->addTextWrap($Left_Margin+210,$YPos-120,50,$FontSize,$a,'left');                    
                                                                                                                                                                                                                                
         $pdf->addTextWrap($Left_Margin,$YPos-160,400,$FontSize,_('Total quantity of Biogas generation'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos-160,100,$FontSize,_('-                Cum'),'left');                  $pdf->addTextWrap($Left_Margin+210,$YPos-160,50,$FontSize,$a,'left');
                                                                                                                                                                                                                                
         $pdf->addTextWrap($Left_Margin+60,$YPos-180,500,$FontSize,_('                      Cum Biogas'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos-180,100,$FontSize,_('-                LPG'),'left');                  $pdf->addTextWrap($Left_Margin+210,$YPos-180,50,$FontSize,$a,'left');    $pdf->addTextWrap($Left_Margin+40,$YPos-180,50,$FontSize,$a,'left');
         $pdf->addTextWrap($Left_Margin+60,$YPos-200,500,$FontSize,_('                      Cum Biogas'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos-200,100,$FontSize,_('-                Electricity'),'left');          $pdf->addTextWrap($Left_Margin+210,$YPos-200,50,$FontSize,$a,'left');    $pdf->addTextWrap($Left_Margin+40,$YPos-200,50,$FontSize,$a,'left');
         $pdf->addTextWrap($Left_Margin+60,$YPos-220,500,$FontSize,_('                      Cum Biogas'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos-220,100,$FontSize,_('-                Auto Fuel'),'left');            $pdf->addTextWrap($Left_Margin+210,$YPos-220,50,$FontSize,$a,'left');    $pdf->addTextWrap($Left_Margin+40,$YPos-220,50,$FontSize,$a,'left');
            
         $pdf->addTextWrap($Left_Margin,$YPos-260,500,$FontSize,_('Anual waste treatement'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos-260,100,$FontSize,_('-                Kg'),'left');                                $pdf->addTextWrap($Left_Margin+210,$YPos-260,50,$FontSize,$a,'left');               
         $pdf->addTextWrap($Left_Margin,$YPos-280,500,$FontSize,_('Anual solid fertilizer generation'),'left'); $pdf->addTextWrap($Left_Margin+200,$YPos-280,100,$FontSize,_('-                Kg'),'left');                    $pdf->addTextWrap($Left_Margin+210,$YPos-280,50,$FontSize,$a,'left');
         
         $pdf->addTextWrap($Left_Margin,$YPos-300,500,$FontSize,_('Anual liquid fertilizer generation'),'left');$pdf->addTextWrap($Left_Margin+200,$YPos-300,100,$FontSize,_('-                Litters'),'left');               $pdf->addTextWrap($Left_Margin+210,$YPos-300,50,$FontSize,$a,'left');         


$pdf->newPage();
           $pdf->addTextWrap($Left_Margin,$YPos+20,140,$FontSize+3,_('B - Technical Details Of Project'),'Left');
           $pdf->line($Left_Margin,$YPos+18,$Left_Margin+145,$YPos+18); 
           
           $pdf->addTextWrap($Left_Margin,$YPos-10,140,$FontSize,_('Size of main digester'),'left');         $pdf->addTextWrap($Left_Margin+200,$YPos-10,140,$FontSize,_('-                Cum'),'left');                       $pdf->addTextWrap($Left_Margin+210,$YPos-10,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-30,140,$FontSize,_('Size of secondary digester'),'left');    $pdf->addTextWrap($Left_Margin+200,$YPos-30,140,$FontSize,_('-                Cum'),'left');                       $pdf->addTextWrap($Left_Margin+210,$YPos-30,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-50,140,$FontSize,_('Size of gas collector'),'left');         $pdf->addTextWrap($Left_Margin+200,$YPos-50,140,$FontSize,_('-                Cum'),'left');                       $pdf->addTextWrap($Left_Margin+210,$YPos-50,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-70,140,$FontSize,_('Waste processor'),'left');               $pdf->addTextWrap($Left_Margin+200,$YPos-70,140,$FontSize,_('-                Semi Automatic'),'left');            $pdf->addTextWrap($Left_Margin+210,$YPos-70,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-90,140,$FontSize,_('Treated slurry collector'),'left');     $pdf->addTextWrap($Left_Margin+200,$YPos-90,140,$FontSize,_('-                Cum'),'left');                        $pdf->addTextWrap($Left_Margin+210,$YPos-90,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-110,140,$FontSize,_('Nos. of pre-digesters '),'left');       $pdf->addTextWrap($Left_Margin+200,$YPos-110,140,$FontSize,_('-                Nos'),'left');                      $pdf->addTextWrap($Left_Margin+210,$YPos-110,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-130,140,$FontSize,_('Volume of each pre-digester'),'left');  $pdf->addTextWrap($Left_Margin+200,$YPos-130,140,$FontSize,_('-                Cum'),'left');                      $pdf->addTextWrap($Left_Margin+210,$YPos-130,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-150,140,$FontSize,_('Slurry loop system '),'left');          $pdf->addTextWrap($Left_Margin+200,$YPos-150,140,$FontSize,_('-                LPH'),'left');                      $pdf->addTextWrap($Left_Margin+210,$YPos-150,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-170,140,$FontSize,_('Fresh water storage'),'left');          $pdf->addTextWrap($Left_Margin+200,$YPos-170,140,$FontSize,_('-                LPD'),'left');                      $pdf->addTextWrap($Left_Margin+210,$YPos-170,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-190,140,$FontSize,_('Gas scrubber'),'left');                 $pdf->addTextWrap($Left_Margin+200,$YPos-190,140,$FontSize,_('-                Cum/hours'),'left');                $pdf->addTextWrap($Left_Margin+210,$YPos-190,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-210,140,$FontSize,_('De-Humidifier '),'left');               $pdf->addTextWrap($Left_Margin+200,$YPos-210,140,$FontSize,_('-                Cum/hours'),'left');                $pdf->addTextWrap($Left_Margin+210,$YPos-210,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-230,140,$FontSize,_('Volume of lagoon digester'),'left');    $pdf->addTextWrap($Left_Margin+200,$YPos-230,140,$FontSize,_('-                Cum'),'left');                      $pdf->addTextWrap($Left_Margin+210,$YPos-230,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-250,140,$FontSize,_('Outlet tank'),'left');                  $pdf->addTextWrap($Left_Margin+200,$YPos-250,140,$FontSize,_('-                Litter'),'left');                   $pdf->addTextWrap($Left_Margin+210,$YPos-250,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-270,140,$FontSize,_('Slurry storage Tank'),'left');          $pdf->addTextWrap($Left_Margin+200,$YPos-270,140,$FontSize,_('-                Litter'),'left');                   $pdf->addTextWrap($Left_Margin+210,$YPos-270,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-290,140,$FontSize,_('Main generator '),'left');              $pdf->addTextWrap($Left_Margin+200,$YPos-290,140,$FontSize,_('-                KW'),'left');                       $pdf->addTextWrap($Left_Margin+210,$YPos-290,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-310,140,$FontSize,_('Stand by generator '),'left');          $pdf->addTextWrap($Left_Margin+200,$YPos-310,140,$FontSize,_('-                KW'),'left');                       $pdf->addTextWrap($Left_Margin+210,$YPos-310,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-330,140,$FontSize,_('Control panel '),'left');               $pdf->addTextWrap($Left_Margin+200,$YPos-330,140,$FontSize,_('-                Model'),'left');                    $pdf->addTextWrap($Left_Margin+210,$YPos-330,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-350,140,$FontSize,_('No.of electric Posts'),'left');         $pdf->addTextWrap($Left_Margin+200,$YPos-350,140,$FontSize,_('-                Nos'),'left');                      $pdf->addTextWrap($Left_Margin+210,$YPos-350,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-370,140,$FontSize,_('No.of lights '),'left');                $pdf->addTextWrap($Left_Margin+200,$YPos-370,140,$FontSize,_('-                Nos'),'left');                      $pdf->addTextWrap($Left_Margin+210,$YPos-370,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-390,140,$FontSize,_('Distance of electrification'),'left');  $pdf->addTextWrap($Left_Margin+200,$YPos-390,140,$FontSize,_('-                Mtr'),'left');                      $pdf->addTextWrap($Left_Margin+210,$YPos-390,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-410,140,$FontSize,_('Excess gas flaring '),'left');          $pdf->addTextWrap($Left_Margin+200,$YPos-410,140,$FontSize,_('-                Cum/hour'),'left');                 $pdf->addTextWrap($Left_Margin+210,$YPos-410,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-430,140,$FontSize,_('Safety valve '),'left');                $pdf->addTextWrap($Left_Margin+200,$YPos-430,140,$FontSize,_('-                Model'),'left');                    $pdf->addTextWrap($Left_Margin+210,$YPos-430,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-450,140,$FontSize,_('Capacity of compressor '),'left');      $pdf->addTextWrap($Left_Margin+200,$YPos-450,140,$FontSize,_('-                Cum/hour'),'left');                 $pdf->addTextWrap($Left_Margin+210,$YPos-450,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-470,140,$FontSize,_('Gas Scrubber '),'left');                $pdf->addTextWrap($Left_Margin+200,$YPos-470,140,$FontSize,_('-                Cum/day'),'left');                  $pdf->addTextWrap($Left_Margin+210,$YPos-470,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-490,140,$FontSize,_('Auto fuel storage tanks'),'left');      $pdf->addTextWrap($Left_Margin+200,$YPos-490,140,$FontSize,_('-                Kg'),'left');                       $pdf->addTextWrap($Left_Margin+210,$YPos-490,50,$FontSize,$a,'left');
           $pdf->addTextWrap($Left_Margin,$YPos-510,140,$FontSize,_('Auto fuel dispensing unit'),'left');    $pdf->addTextWrap($Left_Margin+200,$YPos-510,140,$FontSize,_('-                Kg/day'),'left');                   $pdf->addTextWrap($Left_Margin+210,$YPos-510,50,$FontSize,$a,'left');
              
           
//-----------------------------------------------

  if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
    
    $pdf->OutputD($_SESSION['DatabaseName'] . '_Detailed Project Proposal_' . Date('Y-m-d') . '.pdf');
    $pdf->__destruct();
    
        function PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription) {
                         
          if ($PageNumber>1){
          $pdf->newPage();
          
    }
    
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
        
         $pdf->addTextWrap(150,$YPos,140,$FontSize+3,_('Detailed Project Proposal'),'center');  
        
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
         $pdf->addTextWrap($Left_Margin,$YPos-220,800,$FontSize,_('treatment plant for your house. The detailed project proposal attached consists of'), 'left');
         
         $pdf->addTextWrap($Left_Margin+30,$YPos-260,200,$FontSize,_('1.    Detailed costing of the project'), 'left');   
         //$pdf->addTextWrap($Left_Margin+30,$YPos-270,200,$FontSize,_('2.    Process diagram '), 'left');
//         $pdf->addTextWrap($Left_Margin+30,$YPos-280,200,$FontSize,_('3.    Block diagram of the plant '), 'left');        
//                     
//       $pdf->addTextWrap($Left_Margin+250,$YPos-90,140,$FontSize,$plantprice,'',0);              
                     

         
         $pdf->addTextWrap($Left_Margin,$YPos-400,200,$FontSize,_('We shall implement the system within '), 'left'); 
         $pdf->addTextWrap($Left_Margin,$YPos-420,200,$FontSize,_('Looking forward to receive your valuable order. '), 'left');            
         $pdf->addTextWrap($Left_Margin,$YPos-470,100,$FontSize,_('For Biotech Pvt Ltd '), 'left'); 
         $pdf->addTextWrap($Left_Margin,$YPos-520,100,$FontSize,_('Marketing manager '), 'left');            

         
         $PageNumber++;
         
         }
                     
         
?>
