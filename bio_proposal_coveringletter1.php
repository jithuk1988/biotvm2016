<?php
  $PageSecurity = 80;  
  include('includes/session.inc');
  $PaperSize ='A4';
  include('includes/currencyintowords.php');  
// include('includes/priceintowords.php');
  include('includes/PDFStarter.php');
  
  $FontSize=9;
  $PageNumber=1;
  $line_height=12;

  $XPos = $Left_Margin+1;
  $WhereCategory = " ";
  $CatDescription = " ";
  if($_GET['leadid']!="" AND $_GET['propid']!=""){
     $lead=$_GET['leadid'];
     $pid=$_GET['propid'];
      
  }
  /*elseif($_SESSION['LeadID']!="" AND $_SESSION['ProposalID']!=""){
     $lead=$_SESSION['LeadID'];
     $pid=$_SESSION['ProposalID'];
     unset($_SESSION['LeadID']);
     unset($_SESSION['ProposalID']);
  }
  */
  
//  $lead=$_GET['leadid'];
        
 $sql="SELECT bio_cust.cust_id,  
                bio_cust.custname ,
                bio_cust.housename,               
                bio_cust.area1, 
                bio_leads.leadid,
                bio_leads.leaddate,
                bio_proposal.propid,
                bio_proposal.propdate,
                bio_proposal.createdby,     
                bio_proposal.letterno,
                bio_proposal.letterdate,
                bio_leads.remarks
       FROM  bio_cust,
             bio_leads,
             bio_proposal  
   WHERE bio_cust.cust_id=bio_leads.cust_id 
    AND  bio_proposal.leadid=bio_leads.leadid 
    
    AND bio_leads.leadid=".$lead."
    AND bio_proposal.propid=".$pid;
   
    
 $result=DB_query($sql,$db);
        
while($myrow=DB_fetch_array($result))
          
 {
    $name=$myrow['custname'];   
    $area=$myrow['area1'];
    $housename=$myrow['housename'];
    $letterno=$myrow['letterno'];
    $letterdate=ConvertSQLDate($myrow['letterdate']);
    $remarks=$myrow['remarks']; 
//    $pid= $myrow['propid']; 
    $pdate=ConvertSQLDate($myrow['propdate']);; 
    $created=$myrow['createdby'];
    $leaddate=ConvertSQLDate($myrow['leaddate']); 
 }
 
   
   $user_ID=$_SESSION['UserID'];
       
   $sql_emp="SELECT  bio_emp.empname,bio_designation.designation
        FROM  bio_emp,www_users,bio_designation
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid='".$user_ID."'
        AND bio_emp.designationid=bio_designation.desgid";
  $result_emp=DB_query($sql_emp,$db);
  $myrow_emp=DB_fetch_array($result_emp);
  $empname=$myrow_emp['empname'];
  $designation=$myrow_emp['designation'];    
  
 $sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
$result_cat=DB_query($sql_cat,$db);
$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $plant_array=join(",", $cat_arr); 
}

$sql_plantlist="SELECT bio_proposaldetails.stockid,
                       stockmaster.categoryid,
                       stockcategory.categorydescription 
            FROM   bio_proposaldetails,
                   stockmaster,stockcategory
            WHERE  bio_proposaldetails.stockid=stockmaster.stockid
             AND   stockmaster.categoryid IN($plant_array)
             AND   stockcategory.categoryid=stockmaster.categoryid  
             AND   bio_proposaldetails.propid=".$pid;
 $result_plantlist=DB_query($sql_plantlist,$db);           
 $myrow_plantlist=DB_fetch_array($result_plantlist);
 $id_plant=$myrow_plantlist['stockid'];
 $plant_cat=$myrow_plantlist['categorydescription'];
 
 
 $plant_value=array();
     $sql_1="SELECT stockmaster.stockid,
                     stockcatproperties.label,
                     stockitemproperties.value
              FROM stockmaster,stockcatproperties,stockitemproperties
              WHERE stockmaster.stockid='$id_plant'
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
 


/*
$itemid=array();
$item_array=array();
   $scheme=array();
   $sub_amount=array();
   $item_des=array();
   $item_totalcost=array();
   $amount_subsidy=0;
   $grossamount=0;
   $sql_subsidy="SELECT bio_propsubsidy.stockid,
                        bio_propsubsidy.scheme,
                        bio_propsubsidy.amount,
                        bio_schemes.scheme,
                        stockmaster.longdescription,
                        bio_proposaldetails.tprice
                 FROM bio_propsubsidy,bio_schemes,stockmaster,bio_proposaldetails
                 WHERE bio_propsubsidy.propid=".$pid."
                 AND bio_propsubsidy.scheme=bio_schemes.schemeid
                 AND bio_propsubsidy.stockid=stockmaster.stockid
                 AND bio_proposaldetails.propid=bio_propsubsidy.propid
                 AND bio_proposaldetails.stockid=bio_propsubsidy.stockid";
   $result_subsidy=DB_query($sql_subsidy,$db);
   while($myrow_subsidy=DB_fetch_array($result_subsidy))
   {
       $itemid[]=$myrow_subsidy['stockid'];
       $item_array[]="'".$myrow_subsidy['stockid']."'";
       $items=join(",", $item_array);
       $scheme[]=$myrow_subsidy['scheme'];
       $sub_amount[]=$myrow_subsidy['amount'];
       $item_des[]=$myrow_subsidy['longdescription'];
       
       $item_totalcost[]=$myrow_subsidy['tprice'];
       $sql_sub_amnt="SELECT SUM(amount)
                      FROM bio_propsubsidy
                      WHERE stockid=".;
       
       
       $amount_subsidy=$myrow_subsidy['tprice']-$myrow_subsidy['amount'];
       $grossamount+=$amount_subsidy;       
   }
   $subsidy_count=sizeof($itemid);





$stock=array();
$quantity=array();
$price=array();
$totalprice=array();
$description=array();
$totalcost=0;
$sql_plant="SELECT bio_proposaldetails.stockid,
                   bio_proposaldetails.qty,
                   bio_proposaldetails.price,
                   bio_proposaldetails.tprice,
                   stockmaster.longdescription,
                   stockmaster.stockid
            FROM   bio_proposaldetails,
                   stockmaster
            WHERE  bio_proposaldetails.stockid=stockmaster.stockid
             AND   stockmaster.stockid IN($items)    
             AND   bio_proposaldetails.propid=".$pid;         
                   
           $result_plant=DB_query($sql_plant,$db);           
   while($myrow_plant=DB_fetch_array($result_plant))
   {
    
   $stock[]=$myrow_plant['stockid'];
   $quantity[]=$myrow_plant['qty'];
   $price[]=$myrow_plant['price'];
   $totalprice[]=$myrow_plant['tprice'];
   $description[]=$myrow_plant['longdescription'];
   $totalcost+=$myrow_plant['tprice'];   
   }
 
    $item_count=sizeof($stock); 
      
$optional_item_stock=array();
$$optional_item_quantity=array();
$$optional_item_price=array();
$$optional_item_totalprice=array();
$$optional_item_description=array();
$totalcost_optitem=0;
$sql_optional="SELECT bio_proposaldetails.stockid,
                   bio_proposaldetails.qty,
                   bio_proposaldetails.price,
                   bio_proposaldetails.tprice,
                   stockmaster.longdescription,
                   stockmaster.stockid
            FROM   bio_proposaldetails,
                   stockmaster
            WHERE  bio_proposaldetails.stockid=stockmaster.stockid
             AND   stockmaster.stockid NOT IN($items)    
             AND   bio_proposaldetails.propid=".$pid;         
                   
           $result_optional=DB_query($sql_optional,$db);           
   while($myrow_optional=DB_fetch_array($result_optional))
   {
    
   $optional_item_stock[]=$myrow_optional['stockid'];
   $optional_item_quantity[]=$myrow_optional['qty'];
   $optional_item_price[]=$myrow_optional['price'];
   $optional_item_totalprice[]=$myrow_optional['tprice'];
   $optional_item_description[]=$myrow_optional['longdescription'];
   $totalcost_optitem+=$myrow_optional['tprice'];    
   } 
       $item_optional_count=sizeof($optional_item_stock);
       
       
*/
$itemid_all=array();
$item_all_array=array();
$sql_items="SELECT bio_proposaldetails.stockid
              FROM bio_proposaldetails,stockmaster,bio_proposal,bio_propsubsidy
             WHERE bio_proposaldetails.stockid=bio_propsubsidy.stockid
               AND bio_proposaldetails.propid=bio_proposal.propid
               AND bio_propsubsidy.stockid=stockmaster.stockid
               AND bio_proposaldetails.propid=".$pid."
               AND bio_propsubsidy.leadid=".$lead."
               GROUP BY bio_propsubsidy.stockid";
$result_items=DB_query($sql_items,$db);           
while($myrow_items=DB_fetch_array($result_items))
{
   $itemid_all[]=$myrow_items['stockid'];
   $item_all_array[]="'".$myrow_items['stockid']."'";
   $items_all=join(",", $item_all_array);
}
$items_all_count=sizeof($itemid_all);


$itemid=array();
$item_array=array();
   $scheme=array();
   $sub_amount=array();
   $item_des=array();
   $item_totalcost=array();
   $amount_subsidy=0;
   $grossamount=0;
   $sql_subsidy="SELECT bio_propsubsidy.stockid,
                        bio_propsubsidy.scheme,
                        bio_propsubsidy.amount,
                        bio_schemes.scheme,
                        stockmaster.longdescription,
                        bio_proposaldetails.tprice
                 FROM bio_propsubsidy,bio_schemes,stockmaster,bio_proposaldetails
                 WHERE bio_propsubsidy.propid=".$pid."
                 AND bio_propsubsidy.scheme=bio_schemes.schemeid
                 AND bio_propsubsidy.stockid=stockmaster.stockid
                 AND bio_proposaldetails.propid=bio_propsubsidy.propid
                 AND bio_proposaldetails.stockid=bio_propsubsidy.stockid";
   $result_subsidy=DB_query($sql_subsidy,$db);
   while($myrow_subsidy=DB_fetch_array($result_subsidy))
   {
       $itemid[]=$myrow_subsidy['stockid'];
       $item_array[]="'".$myrow_subsidy['stockid']."'";
       $items=join(",", $item_array);
       $scheme[]=$myrow_subsidy['scheme'];
       $sub_amount[]=$myrow_subsidy['amount'];
       $item_des[]=$myrow_subsidy['longdescription'];
            
   }
   $subsidy_count=sizeof($itemid);
   
   if($subsidy_count>0){
       for($i=0;$i<$items_all_count;$i++)
   {
       $sql_gross_amt="SELECT bio_proposaldetails.tprice,SUM(amount)
                        FROM bio_proposaldetails,bio_propsubsidy
                        WHERE bio_proposaldetails.stockid='".$itemid_all[$i]."'
                        AND  bio_proposaldetails.stockid=bio_propsubsidy.stockid
                        AND bio_propsubsidy.leadid=".$lead."
                        AND bio_proposaldetails.propid=".$pid;
       $result_gross_amt=DB_query($sql_gross_amt,$db);
       while($myrow_gross_amt=DB_fetch_array($result_gross_amt))
       {
           $sub_amt=$myrow_gross_amt['tprice']-$myrow_gross_amt[1];
//           echo"<br />";
           $grossamount+=$sub_amt;
       }
   }
   
$stock=array();
$quantity=array();
$price=array();
$totalprice=array();
$description=array();
$totalcost=0;
$sql_plant="SELECT bio_proposaldetails.stockid,
                   bio_proposaldetails.qty,
                   bio_proposaldetails.price,
                   bio_proposaldetails.tprice,
                   stockmaster.longdescription,
                   stockmaster.stockid
            FROM   bio_proposaldetails,
                   stockmaster
            WHERE  bio_proposaldetails.stockid=stockmaster.stockid
             AND   stockmaster.stockid IN($items)    
             AND   bio_proposaldetails.propid=".$pid;         
                   
           $result_plant=DB_query($sql_plant,$db);           
   while($myrow_plant=DB_fetch_array($result_plant))
   {
    
   $stock[]=$myrow_plant['stockid'];
   $quantity[]=$myrow_plant['qty'];
   $price[]=$myrow_plant['price'];
   $totalprice[]=$myrow_plant['tprice'];
   $description[]=$myrow_plant['longdescription'];
   $totalcost+=$myrow_plant['tprice'];   
   }
 
 $item_count=sizeof($stock); 
    

$optional_item_stock=array();
$optional_item_quantity=array();
$optional_item_price=array();
$optional_item_totalprice=array();
$optional_item_description=array();
$totalcost_optitem=0;
$sql_optional="SELECT bio_proposaldetails.stockid,
                   bio_proposaldetails.qty,
                   bio_proposaldetails.price,
                   bio_proposaldetails.tprice,
                   stockmaster.longdescription,
                   stockmaster.stockid
            FROM   bio_proposaldetails,
                   stockmaster
            WHERE  bio_proposaldetails.stockid=stockmaster.stockid
             AND   stockmaster.stockid NOT IN($items)    
             AND   bio_proposaldetails.propid=".$pid;         
                   
           $result_optional=DB_query($sql_optional,$db);
           $itemcount=DB_num_rows($result_optional);           
   while($myrow_optional=DB_fetch_array($result_optional))
   {
    
   $optional_item_stock[]=$myrow_optional['stockid'];
   $optional_item_quantity[]=$myrow_optional['qty'];
   $optional_item_price[]=$myrow_optional['price'];
   $optional_item_totalprice[]=$myrow_optional['tprice'];
   $optional_item_description[]=$myrow_optional['longdescription'];
   $totalcost_optitem+=$myrow_optional['tprice'];
   $netamount=$grossamount+$totalcost_optitem;    
   } 
       $item_optional_count=sizeof($optional_item_stock);
       if($itemcount==0){
           $netamount=$grossamount;
       }  
       
   }
   else{
       $optional_item_stock=array();
$optional_item_quantity=array();
$optional_item_price=array();
$optional_item_totalprice=array();
$optional_item_description=array();
$totalcost_optitem=0;
$sql_optional="SELECT bio_proposaldetails.stockid,
                   bio_proposaldetails.qty,
                   bio_proposaldetails.price,
                   bio_proposaldetails.tprice,
                   stockmaster.longdescription,
                   stockmaster.stockid
            FROM   bio_proposaldetails,
                   stockmaster
            WHERE  bio_proposaldetails.stockid=stockmaster.stockid    
             AND   bio_proposaldetails.propid=".$pid;         
                   
           $result_optional=DB_query($sql_optional,$db);           
   while($myrow_optional=DB_fetch_array($result_optional))
   {
    
   $optional_item_stock[]=$myrow_optional['stockid'];
   $optional_item_quantity[]=$myrow_optional['qty'];
   $optional_item_price[]=$myrow_optional['price'];
   $optional_item_totalprice[]=$myrow_optional['tprice'];
   $optional_item_description[]=$myrow_optional['longdescription'];
   $totalcost_optitem+=$myrow_optional['tprice'];
   $netamount=$totalcost_optitem;    
   } 
       $item_optional_count=sizeof($optional_item_stock);
       
       
   }
   $currency_object = new Currency();
   $netamount_words=$currency_object->get_bd_amount_in_text($netamount);

//   $netamount_words=no_to_words($netamount); 
//   echo $netamount_words;
   
//   print_r($netamount_words);
 
 //--------------------------------------------------------------------------------------------------------------------//   
    
  
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $XPos = $Left_Margin+5;
    $YPos -=(2*$line_height);   
        
    $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
    $pdf->addJpegFromFile($img1,$XPos+392,$YPos-=$line_height,0,55);

    
    $img2= 'companies/'.$_SESSION['DatabaseName'].'/logof.jpg';
    $pdf->addJpegFromFile($img2,$XPos+35,$YPos,0,55);  
    $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2); 
     
    
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,100,$FontSize,$letterno,'',0); 
    
     $pdf->addTextWrap($Left_Margin+390,$YPos-=$line_height-15,100,$FontSize,$letterdate,'left');
  
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,100,$FontSize,$name,'left'); 
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-5,100,$FontSize,$area,'left'); 
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-5,100,$FontSize,$housename,'left'); 

     
       
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,100,$FontSize+1,_('Dear Sir,'), 'left'); 
     
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,200,$FontSize+1,_('Sub:   Solid Waste to Energy Project'), 'left');
     
     $ref='Ref.:   Enquiry dated on '.$leaddate; 
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,300,$FontSize+1,$ref, 'left');
//     $pdf->addTextWrap($Left_Margin+45,$YPos-=$line_height-15,300,$FontSize+1, $remarks,'left');
     
      
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,1000,$FontSize+1,_('We are happy to associate with you for your solid waste management project. Our vast experience in implementing  '), 'left');    
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,1000,$FontSize+1,_('plant to process solid waste and generate gas prompt us to offer you a cost-effective proposal for '), 'left');
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,1000,$FontSize+1,_('Solid Waste to Energy Project.'), 'left'); 
     $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize+1,_(' 
Based on the discussion we had with your team, we have designed the plant with following specification.
'), 'left'); 
         
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,700,$FontSize+1,_('  Type of plant we propose'),'left');  
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(': '),'left');
         $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-15,700,$FontSize+1,$plant_cat,'left');
    if($organic_waste_capacity!=""){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,700,$FontSize+1,_('Quantum of feedstock/ solid waste treated per day'),'left');
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(': '),'left');
         $organic_waste_capacity=$organic_waste_capacity.' Kg';   
         $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-15,700,$FontSize+1,$organic_waste_capacity,'left');                   
    }
    if($organic_water_capacity!=""){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,700,$FontSize+1,_('Quantum of organic waste water treated per day'),'left');
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(': '),'left');
         $organic_water_capacity=$organic_water_capacity." Ltr"; 
         $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-15,700,$FontSize+1,$organic_water_capacity,'left');
    }
    if($size!=""){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,700,$FontSize+1,_('Size of the plant'),'left');
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(': '),'left');
          $size=$size." CUM";
         $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-15,700,$FontSize+1,$size,'left'); 
    }                                                                                         
    if($biogas!=""){
        $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,700,$FontSize+1,_('Expected gas generation per day'),'left');
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(': '),'left');
         $biogas=$biogas." CUM";
         $pdf->addTextWrap($Left_Margin+310,$YPos-=$line_height-15,700,$FontSize+1,$biogas,'left');
    }     
         
         
        /* $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,900,$FontSize+1,_('  The cost estimation of the project is given below (For single page quotation.) '),'left');  
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,700,$FontSize+1,_('Total cost of <xxx CUM Plant type>'),'left');
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(':'),'left');   
         $pdf->addTextWrap($Left_Margin+350,$YPos-=$line_height-15,700,$FontSize+1,_( 'Rs.xxxxxx.xx/-'),'left');                                                                                            
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,700,$FontSize+1,_('<Subsidy 1>'),'left');
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(':'),'left'); 
         $pdf->addTextWrap($Left_Margin+350,$YPos-=$line_height-15,700,$FontSize+1,_('Rs. Yyyy.yy/-'),'left');
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,700,$FontSize+1,_('<Subsidy 2>'),'left');
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(':'),'left'); 
         $pdf->addTextWrap($Left_Margin+350,$YPos-=$line_height-15,700,$FontSize+1,_('Rs. Zzz.ff/-'),'left');  
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,700,$FontSize+1,_('Net cost'),'left');
         $pdf->addTextWrap($Left_Margin+300,$YPos-=$line_height-15,700,$FontSize+1,_(':'),'left'); 
         $pdf->addTextWrap($Left_Margin+350,$YPos-=$line_height-15,700,$FontSize+1,_('Rs.xyxysy.vv/-'),'left');
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,1000,$FontSize+2,_('(OR Detailed price schedule is attached).'),'left');*/
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,900,$FontSize+1,_('The commercial terms and list of works to be done by you are given as annexure'),'left');
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,900,$FontSize+1,_('We are sure, you will find our project proposal the most cost-effective solution addressing your solid waste   '), 'left');      
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-2,900,$FontSize+1,_(' management issue and the return on investment most rewarding.'), 'left');      
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,900,$FontSize+1,_('We look forward to a long relationship with you'), 'left');      
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,900,$FontSize+1,_('Thanking you'), 'left');      
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-2,900,$FontSize+1,_('For Biotech'), 'left');    
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,140,$FontSize+1,$empname,'',0);
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+20,140,$FontSize+1,$designation,'',0);
         //$pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+15,900,$FontSize+1,_('<Name>'), 'left');    
//         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height-2,900,$FontSize+1,_('<Designation of authorized signatory>'), 'left');    
    $YPos=$Bottom_Margin + $line_height;
    $page='Page No '.$PageNumber;
    $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
    $PageNumber++;  
    $pdf->newPage();   
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $XPos = $Left_Margin+5;
    $YPos -=(2*$line_height); 
    
      $pdf->addTextWrap($Left_Margin+370,$YPos-=$line_height-45,100,$FontSize+4,_('Annexure'),'left'); 
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,200,$FontSize+3,_('Roles and responsibilities of biotech'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+8,300,$FontSize+1,_('1.    Supply and installation of waste digester and gas collector'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,300,$FontSize+1,_('2.    Installation of inlet chamber'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,900,$FontSize+1,_('3.    Telephonic guidance from our call center for plumping, earth excavation and construction of base platform. '),'left');
//      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,500,$FontSize+1,_('4.    <Task list based on the type of plant, accessories and capacity>'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,200,$FontSize+3,_('Roles and responsibilities of customer'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,800,$FontSize+1,_('1.    Plumping work for connecting drainage line, feed stock feed line and gas to output device'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,400,$FontSize+1,_('2.    Excavation of pit and construction of base platform for pre-digester'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,300,$FontSize+1,_('3.    Initial feeding  in the plant with cow dung'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,500,$FontSize+1,_('4.    Construction of retaining wall around the plant in case 7 feet pit is not possible'),'left');
      $pdf->addTextWrap($Left_Margin+32,$YPos-=$line_height+12,100,$FontSize+3,_('TERMS AND CONDITIONS'),'left');
      $pdf->line($Left_Margin+32,$YPos-2,$Left_Margin+180,$YPos-2);
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,800,$FontSize+1,_('1.    Installation will start within one week of receipt of work order and payment of total project cost.'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,800,$FontSize+1,_('2.    Work will be completed within one month of commencement of installation work and completion of all '),'left'); 
      $pdf->addTextWrap($Left_Margin+39,$YPos-=$line_height+3,800,$FontSize+1,_('plumping and civil work at site by beneficiary'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,800,$FontSize+1,_('3.    Beneficiary will have to handle and settle loading, unloading and any other out side interference, if any,'),'left'); 
      $pdf->addTextWrap($Left_Margin+39,$YPos-=$line_height+3,800,$FontSize+1,_('in connection with the installation work.'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,800,$FontSize+1,_('4.    The beneficiary will have to provide water and electricity free of cost for fabrication work if any for the'),'left'); 
      $pdf->addTextWrap($Left_Margin+39,$YPos-=$line_height+3,800,$FontSize+1,_('installation work of treatment plant.'),'left');
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,800,$FontSize+1,_('5.    Taxes and duties will be extra at rate prevailing at the time of delivery.'),'left'); 
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,800,$FontSize+1,_('6.    This offer is valid for 3 months from the date of the offer.'),'left'); 
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,800,$FontSize+1,_('7.    Transportation, insurance and packing charges will be extra at actuals'),'left'); 
      $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+3,800,$FontSize+1,_('8.    Civil work to be done by the beneficiary'),'left'); 
      
      $pdf->addTextWrap($Left_Margin+32,$YPos-=$line_height+12,200,$FontSize+3,_('Terms of Payment:'),'left');
      $pdf->line($Left_Margin+32,$YPos-2,$Left_Margin+135,$YPos-2);
      $pdf->addTextWrap($Left_Margin+35,$YPos-=$line_height+10,800,$FontSize+1,_('The total project cost has to be paid along with work order.'),'left');
      $pdf->addTextWrap($Left_Margin+35,$YPos-=$line_height+5,800,$FontSize+1,_('All payments to be made by DD in favor of Managing Director, BIOTECH Renewable Energy Pvt. Ltd.,'),'left');
    $pdf->addTextWrap($Left_Margin+35,$YPos-=$line_height+2,800,$FontSize+1,_('payable at Trivandrum '),'left');
    $pdf->addTextWrap($Left_Margin+35,$YPos-=$line_height+8,800,$FontSize+1,_('Yours faithfully,'),'left');
    $pdf->addTextWrap($Left_Margin+35,$YPos-=$line_height+10,140,$FontSize+1,$empname,'',0);
    $pdf->addTextWrap($Left_Margin+35,$YPos-=$line_height+20,140,$FontSize+1,$designation,'',0);
//    $pdf->addTextWrap($Left_Margin+35,$YPos-=$line_height+15,800,$FontSize+1,_('<Name and designation of authorized signatory> '),'left');
    $YPos=$Bottom_Margin + $line_height;
    $page='Page No '.$PageNumber;
    $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);   
    
    $PageNumber++;   
    $pdf->newPage();    
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $XPos = $Left_Margin+5;
    $YPos -=(2*$line_height);  
         $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
         $pdf->addJpegFromFile($img1,$XPos+392,$YPos-=$line_height,0,55);
         $img2= 'companies/'.$_SESSION['DatabaseName'].'/logof.jpg';
         $pdf->addJpegFromFile($img2,$XPos+35,$YPos,0,55);  
           $pdf->line($Left_Margin+10,$YPos=$YPos-2,$Left_Margin+534,$YPos);  
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+20,300,$FontSize+1,_('Ref. No.    '), 'left');
         $pdf->addTextWrap($Left_Margin+65,$YPos-=$line_height-15,300,$FontSize,$pid,'left');
         $pdf->addTextWrap($Left_Margin+400,$YPos-=$line_height-15,300,$FontSize,$pdate,'left');
         $pdf->addTextWrap($Left_Margin+90,$YPos-=$line_height+20,300,$FontSize+4,_('Price Schedule '), 'center');
  $pdf->line($Left_Margin+195,$YPos=$YPos-2,$Left_Margin+285,$YPos); 
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+1,100,$FontSize+1,$name,'left'); 
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+1,$area,'left'); 
         $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height,100,$FontSize+1,$housename,'left'); 
           $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos); 
         $pdf->addTextWrap($Left_Margin+10,$YPos-=$line_height+20,300,$FontSize+3,_('Sl. No'), 'left'); 
         $pdf->addTextWrap($Left_Margin+80,$YPos-=$line_height-15,300,$FontSize+3,_('Plant Description'), 'left');  
         $pdf->addTextWrap($Left_Margin+260,$YPos-=$line_height-15,300,$FontSize+3,_('Qty'), 'left');   
         $pdf->addTextWrap($Left_Margin+320,$YPos-=$line_height-25,300,$FontSize+3,_('Price per Unit'), 'left');  
         $pdf->addTextWrap($Left_Margin+420,$YPos-=$line_height-15,300,$FontSize+3,_('Amount'), 'left'); 
         $pdf->addTextWrap($Left_Margin+340,$YPos-=$line_height+10,300,$FontSize+3,_('Rs.'), 'left'); 
         $pdf->addTextWrap($Left_Margin+370,$YPos-=$line_height-15,300,$FontSize+3,_('Ps.'), 'left');   
         $pdf->addTextWrap($Left_Margin+420,$YPos-=$line_height-16,300,$FontSize+3,_('Rs.'), 'left');      
         $pdf->addTextWrap($Left_Margin+450,$YPos-=$line_height-15,300,$FontSize+3,_('Ps.'), 'left'); 
         $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos); 
    
    
    if($item_count>0){
        $no=1;
        for($i=0;$i<$item_count;$i++){
            $pdf->addTextWrap($Left_Margin+15,$YPos-=$line_height+10,140,$FontSize+1,$no,'',0);
            $pdf->addTextWrap($Left_Margin+50,$YPos,140,$FontSize+1,$description[$i],'',0);  
            $pdf->addTextWrap($Left_Margin+265,$YPos,140,$FontSize+1,$quantity[$i],'',0);
            $pdf->addTextWrap($Left_Margin+340,$YPos,140,$FontSize+1,$price[$i],'',0);
            $pdf->addTextWrap($Left_Margin+420,$YPos,140,$FontSize+1,$totalprice[$i],'',0);    
            $no++;
        }
        $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos);   
        $pdf->addTextWrap($Left_Margin+70,$YPos-=$line_height+5,300,$FontSize+1,_('Total cost'), 'left');     
        $pdf->addTextWrap($Left_Margin+420,$YPos,140,$FontSize+1,$totalcost,'',0);
        $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos);
    }
    if($subsidy_count>0){
        $pdf->addTextWrap($Left_Margin+80,$YPos-=$line_height+5,300,$FontSize+3,_('Subsidy description'), 'center');      
        $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos);
        $no=1;
        for($i=0;$i<$subsidy_count;$i++){
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,140,$FontSize+1,$no,'',0);
            $subsidy=$scheme[$i]." for ".$item_des[$i];
            $pdf->addTextWrap($Left_Margin+50,$YPos,500,$FontSize+1,$subsidy,'',0);
            $pdf->addTextWrap($Left_Margin+420,$YPos,500,$FontSize+1,$sub_amount[$i],'',0);
            $no++;
        }
        $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos);
        $pdf->addTextWrap($Left_Margin+80,$YPos-=$line_height+5,300,$FontSize+3,_('Gross amount after subsidy'), 'center');
        $pdf->addTextWrap($Left_Margin+420,$YPos,140,$FontSize+1,$grossamount,'',0);      
        $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos);
    }     
    if($item_optional_count){
        $no=1;
        for($i=0;$i<$item_optional_count;$i++){         
            $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,140,$FontSize+1,$no,'',0);
            $pdf->addTextWrap($Left_Margin+70,$YPos,140,$FontSize+1,$optional_item_description[$i],'',0);
            $pdf->addTextWrap($Left_Margin+265,$YPos,140,$FontSize+1,$optional_item_quantity[$i],'',0);
            $pdf->addTextWrap($Left_Margin+340,$YPos,140,$FontSize+1,$optional_item_price[$i],'',0); 
            $pdf->addTextWrap($Left_Margin+420,$YPos,140,$FontSize+1,$optional_item_totalprice[$i],'',0);
            $no++;    
        }   
        $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos);               
    }  
    $pdf->addTextWrap($Left_Margin+160,$YPos-=$line_height+2,300,$FontSize+3,_('Net Amount Payable'), 'left');
    $pdf->addTextWrap($Left_Margin+420,$YPos,140,$FontSize+1,$netamount,'',0);  
    $pdf->line($Left_Margin+10,$YPos=$YPos-10,$Left_Margin+470,$YPos);
    $amt_words="Rupees : ".$netamount_words;
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,300,$FontSize+1,$amt_words,'',0);  
//    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,300,$FontSize+3,_('Rupees :'), 'left');  
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,140,$FontSize+1,$empname,'',0);
    $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+20,140,$FontSize+1,$designation,'',0);
     
    $YPos=$Bottom_Margin + $line_height;
    $page='Page No '.$PageNumber;
    $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0); 
 $pdf->OutputD($_SESSION['DatabaseName'] . '_StdProposal_Covering letter_' . Date('Y-m-d') . '.pdf');
 $pdf->__destruct(); 
    
?>


       