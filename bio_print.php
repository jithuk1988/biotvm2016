<?php
$PageSecurity = 80;
/* $Id: InventoryQuantities.php 4555 2011-04-19 10:18:49Z daintree $ */

// InventoryQuantities.php - Report of parts with quantity. Sorts by part and shows
// all locations where there are quantities of the part

include('includes/session.inc');
$_GET['leadid']=$_SESSION['lead'];
unset($_SESSION['lead']);
If ($_GET['leadid']!="") {
    $leadid=$_GET['leadid'];
    include('includes/PDFStarter.php');
    $pdf->addInfo('Title',_('Inventory Quantities Report'));
    $pdf->addInfo('Subject',_('Parts With Quantities'));
    $FontSize=9;
    $PageNumber=1;
    $line_height=12;

    $Xpos = $Left_Margin+1;
    $WhereCategory = " ";
    $CatDescription = " ";
  //  if ($_POST['StockCat'] != 'All') {
//        $WhereCategory = " AND stockmaster.categoryid='" . $_POST['StockCat'] . "'";
//        $sql= "SELECT categoryid, categorydescription FROM stockcategory WHERE categoryid='" . $_POST['StockCat'] . "' ";
//        $result = DB_query($sql,$db);
//        $myrow = DB_fetch_row($result);
//        $CatDescription = $myrow[1];
//    }

 //   if ($_POST['Selection'] == 'All') {
//        $sql = "SELECT locstock.stockid,
//                    stockmaster.description,
//                    locstock.loccode,
//                    locations.locationname,
//                    locstock.quantity,
//                    locstock.reorderlevel,
//                    stockmaster.decimalplaces,
//                    stockmaster.serialised,
//                    stockmaster.controlled
//                FROM locstock,
//                    stockmaster
//            LEFT JOIN stockcategory
//            ON stockmaster.categoryid=stockcategory.categoryid,
//                    locations
//                WHERE locstock.stockid=stockmaster.stockid
//                AND locstock.loccode=locations.loccode
//                AND locstock.quantity <> 0
//                AND (stockmaster.mbflag='B' OR stockmaster.mbflag='M') " .
//                $WhereCategory . " ORDER BY locstock.stockid,locstock.loccode";
//    } else {
        // sql to only select parts in more than one location
        // The SELECT statement at the beginning of the WHERE clause limits the selection to
        // parts with quantity in more than one location
//        $sql = "SELECT locstock.stockid,
//                    stockmaster.description,
//                    locstock.loccode,
//                    locations.locationname,
//                    locstock.quantity,
//                    locstock.reorderlevel,
//                    stockmaster.decimalplaces,
//                    stockmaster.serialised,
//                    stockmaster.controlled
//                FROM locstock,
//                    stockmaster,
//                    locations
//                WHERE (SELECT count(*)
//                      FROM locstock
//                      WHERE stockmaster.stockid = locstock.stockid
//                      AND locstock.quantity <> 0
//                      GROUP BY locstock.stockid) > 1
//                AND locstock.stockid=stockmaster.stockid
//                AND locstock.loccode=locations.loccode
//                AND locstock.quantity <> 0
//                AND (stockmaster.mbflag='B' OR stockmaster.mbflag='M') " .
//                $WhereCategory . " ORDER BY locstock.stockid,locstock.loccode";
//    }

    $sql="SELECT * FROM bio_leads WHERE leadid=".$leadid;
 
    $result=DB_query($sql,$db);
      $myrow=DB_fetch_array($result); 
  
      $rmdailykg=$myrow['rmdailykg'];
      $advanceamount=$myrow['advanceamount'];
      $productservicesid=$myrow['productservicesid'];
      $enqtypeid=$myrow['enqtypeid']; 
  //    $feedstockid=$myrow['feedstockid']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $schemeid=$myrow['schemeid'];
      $teamid=$myrow['teamid'];
      $sourceid=$myrow['sourceid'];
      $investmentsize=$myrow['investmentsize'];
      $remarks=$myrow['remarks'];
      $status=$myrow['status']; 

          $sqledt="SELECT * FROM bio_cust WHERE bio_cust.cust_id=".$myrow['cust_id'];
     $result1=DB_query($sqledt,$db);
     $myrow1=DB_fetch_array($result1); 
      $custname=$myrow1['custname'];    
      $homno=$myrow1['houseno'];
      $housename=$myrow1['housename']; 
      $area1=$myrow1['area1'];
      $area2=$myrow1['area2'];       //echo "ssssssssss".$area2."sssssssssssnn";    
      $pin=$myrow1['pin'];
      $nationality=$myrow1['nationality']; 
      $state=$myrow1['state'];
      $district=$myrow1['district'];   
      //echo $custaddress;  $custphone
    $pieces=$myrow1['custphone'];  $phpieces = explode("-", $pieces,2);  $custcode=$phpieces[0];if($custcode==0 || $custcode==""){$custcode=0;}  $custphone=$phpieces[1];
    $custmobile=$myrow1['custmob'];
    $custemail=$myrow1['custmail'];
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
        FROM bio_enquirytypes, 
        bio_feedstocks, 
        bio_outputtypes,         
        bio_schemes,
        bio_productservices,
        bio_leadsources,
        bio_leadsourcetypes,stockcategory
        WHERE bio_enquirytypes.enqtypeid=$enqtypeid  AND bio_outputtypes.outputtypeid=$outputtypeid AND stockcategory.categoryid='$productservicesid' AND bio_leadsources.id=$sourceid AND bio_leadsourcetypes.id=bio_leadsources.sourcetypeid";
      $result4=DB_query($sq4,$db); 
      $myrow4=DB_fetch_array($result4);
    
    $result = DB_query($sql,$db,'','',false,true);

    if (DB_error_no($db) !=0) {
      $title = _('Inventory Quantities') . ' - ' . _('Problem Report');
      include('includes/header.inc');
       prnMsg( _('The Inventory Quantity report could not be retrieved by the SQL because') . ' '  . DB_error_msg($db),'error');
       echo '<br /><a href="' .$rootpath .'/index.php">' . _('Back to the menu') . '</a>';
       if ($debug==1){
          echo '<br />' . $sql;
       }
       include('includes/footer.inc');
       exit;
    }
    if (DB_num_rows($result)==0){
            $title = _('Print Inventory Quantities Report');
            include('includes/header.inc');
            prnMsg(_('There were no items with inventory quantities'),'error');
            echo '<br /><a href="'.$rootpath.'/index.php">' . _('Back to the menu') . '</a>';
            include('includes/footer.inc');
            exit;
    }

    PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                $Page_Width,$Right_Margin,$CatDescription);

    $FontSize=8;

//    $holdpart = " ";
//    While ($myrow = DB_fetch_array($result,$db)){
//          if ($myrow['stockid'] != $holdpart) {
//              $YPos -=(2 * $line_height);
//              $holdpart = $myrow['stockid'];
//          } else {
//              $YPos -=($line_height);
//          }

            // Parameters for addTextWrap are defined in /includes/class.pdf.php
            // 1) X position 2) Y position 3) Width
            // 4) Height 5) Text 6) Alignment 7) Border 8) Fill - True to use SetFillColor
            // and False to set to transparent
                $pdf->addTextWrap(120,$YPos-30,100,$FontSize,$leadid,'',0);
                $pdf->addTextWrap(120,$YPos-40,100,$FontSize,$myrow1['custname'],'',0);
                $pdf->addTextWrap(120,$YPos-50,150,$FontSize,$myrow1['custmob'],'',0);
                $pdf->addTextWrap(120,$YPos-60,50,$FontSize,$myrow4[6],'',0);
                $pdf->addTextWrap(120,$YPos-70,50,$FontSize,$myrow4[1],'',0);
                $pdf->addTextWrap(120,$YPos-80,50,$FontSize,$myrow4[0],'',0);
                $pdf->addTextWrap(150,$YPos-103,50,$FontSize+1,_('Payment'), 'left');
                $pdf->addTextWrap(150,$YPos-118,50,$FontSize+1,$myrow['advanceamount'],'',0);
                                
                $pdf->addTextWrap(380,$YPos-30,100,$FontSize,$leadid,'',0);
                $pdf->addTextWrap(380,$YPos-40,100,$FontSize,$myrow1['custname'],'',0);
                $pdf->addTextWrap(380,$YPos-50,150,$FontSize,$myrow1['custmob'],'',0);
                $pdf->addTextWrap(380,$YPos-60,50,$FontSize,$myrow4[6],'',0);
                $pdf->addTextWrap(380,$YPos-70,50,$FontSize,$myrow4[1],'',0);
                $pdf->addTextWrap(380,$YPos-80,50,$FontSize,$myrow4[0],'',0);
                $pdf->addTextWrap(410,$YPos-103,50,$FontSize+1,_('Payment '), 'left');  
                $pdf->addTextWrap(410,$YPos-118,50,$FontSize+1,$myrow['advanceamount'],'',0);
                
            if ($YPos < $Bottom_Margin + $line_height){
               PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                           $Right_Margin,$CatDescription);
            }
 //   } /*end while loop */

    if ($YPos < $Bottom_Margin + $line_height){
           PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                       $Right_Margin,$CatDescription);
    }
/*Print out the grand totals */

    $pdf->OutputD($_SESSION['DatabaseName'] . '_Advance Receipt_' . Date('Y-m-d') . '.pdf');
    $pdf->__destruct();
} else { /*The option to print PDF was not hit so display form */

    $title=_('Inventory Quantities Reporting');
    include('includes/header.inc');
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/inventory.png" title="' . _('Inventory') . '" alt="" />' . ' ' . _('Inventory Quantities Report') . '</p>';
echo '<div class="page_help_text">' . _('Use this report to display the quantity of Inventory items in different categories.') . '</div><br />';


    echo '</br></br><form action="' . $_SERVER['PHP_SELF'] . '" method="post"><table>';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<table class="selection"><tr><td>';
    echo '<tr><td>' . _('Selection') . ':</td><td><select name="Selection">';
    echo '<option selected value="All">' . _('All') . '</option>';
    echo '<option value="Multiple">' . _('Only Parts With Multiple Locations') . '</option>';
    echo '</select></td></tr>';

    $SQL="SELECT categoryid, categorydescription FROM stockcategory ORDER BY categorydescription";
    $result1 = DB_query($SQL,$db);
    if (DB_num_rows($result1)==0){
        echo '</table></td></tr>
            </table>
            <p>';
        prnMsg(_('There are no stock categories currently defined please use the link below to set them up'),'warn');
        echo '<br /><a href="' . $rootpath . '/StockCategories.php">' . _('Define Stock Categories') . '</a>';
        include ('includes/footer.inc');
        exit;
    }

    echo '<tr><td>' . _('In Stock Category') . ':</td>
                <td><select name="StockCat">';
    if (!isset($_POST['StockCat'])){
        $_POST['StockCat']='All';
    }
    if ($_POST['StockCat']=='All'){
        echo '<option selected value="All">' . _('All') . '</option>';
    } else {
        echo '<option value="All">' . _('All') . '</option>';
    }
    while ($myrow1 = DB_fetch_array($result1)) {
        if ($myrow1['categoryid']==$_POST['StockCat']){
            echo '<option selected value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
        } else {
            echo '<option value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
        }
    }
    echo '</select></td></tr>';
    echo '</table><p><div class="centre"><input type=submit name="PrintPDF" value="' . _('Print PDF') . '"></div>';

    include('includes/footer.inc');

} /*end of else not PrintPDF */

function PrintHeader(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$CatDescription) {

    /*PDF page header for Reorder Level report */
    if ($PageNumber>1){
        $pdf->newPage();
    }
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;
    
       
    $pdf->addTextWrap($Left_Margin,$YPos,100,$FontSize+3,_('BIOTECH'),'left');
    $pdf->addTextWrap(300,$YPos,100,$FontSize+3,_('BIOTECH'),'left');
    
    $pdf->addTextWrap($Left_Margin,$YPos-10,100,$FontSize-2,_('Post Box. No 520,M. P. Appan Road'),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-20,100,$FontSize-2,_('Vazhuthacadu, Thycaud P.O.'),'left');
    $pdf->addTextWrap($Left_Margin,$YPos-30,100,$FontSize-2,_('Thiruvananthapuram-695 014, KERALA'),'left');        
    

    $pdf->addTextWrap(300,$YPos-10,100,$FontSize-2,_('Post Box. No 520,M. P. Appan Road'),'left');
    $pdf->addTextWrap(300,$YPos-20,100,$FontSize-2,_('Vazhuthacadu, Thycaud P.O.'),'left');
    $pdf->addTextWrap(300,$YPos-30,100,$FontSize-2,_('Thiruvananthapuram-695 014, KERALA'),'left');     
    $YPos -=$line_height;  
       
    $pdf->addTextWrap(70,$YPos-55,150,$FontSize+2,_('Advance Reciept'),'center');
    $pdf->addTextWrap(330,$YPos-55,150,$FontSize+2,_('Advance Reciept'),'center');
  
         //$pdf->addTextWrap($Page_Width-$Right_Margin-380,$YPos+10,90,$FontSize,_('Page').$PageNumber,'right' ); 
         $pdf->addTextWrap($Page_Width-$Right_Margin-380,$YPos,90,$FontSize,Date($_SESSION['DefaultDateFormat']), 'right');
        
         //$pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos+10,90,$FontSize,_('Page').$PageNumber,'right' ); 
         $pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos,90,$FontSize,Date($_SESSION['DefaultDateFormat']),'right');
         
    $YPos -= $line_height;
//    $pdf->addTextWrap($Left_Margin,$YPos,50,$FontSize,_('Category'));
//    $pdf->addTextWrap(95,$YPos,50,$FontSize,$_POST['StockCat']);
    $pdf->addTextWrap(160,$YPos,150,$FontSize,$CatDescription,'left');
    $YPos -=(2*$line_height);

    /*set up the headings */
    $Xpos = $Left_Margin+1;

    $pdf->addTextWrap($Left_Margin,$YPos-40,100,$FontSize,_('Lead Slno:'), 'left');
    $pdf->addTextWrap($Left_Margin,$YPos-50,100,$FontSize,_('Customer Name'), 'left');
    $pdf->addTextWrap($Left_Margin,$YPos-60,150,$FontSize,_('Phone'), 'left');
    $pdf->addTextWrap($Left_Margin,$YPos-70,50,$FontSize,_('Product Category'), 'left');
    $pdf->addTextWrap($Left_Margin,$YPos-80,50,$FontSize,_('Output'), 'left');
    $pdf->addTextWrap($Left_Margin,$YPos-90,50,$FontSize,_('Enquiry type'), 'left');
    $pdf->addTextWrap($Left_Margin,$YPos-115,100,$FontSize+1,_('Description of Reciepts '), 'left'); 
    $pdf->addTextWrap($Left_Margin,$YPos-130,50,$FontSize+1,_('Advance Payed '), 'left'); 
    $pdf->addTextWrap($Page_Width-$Right_Margin-380,$YPos-190,90,$FontSize,_('Customer Signature'),'right');
    $pdf->addTextWrap($Left_Margin,$YPos-190,180,$FontSize,_('Authorized Signature'),'left');
    $pdf->rect(38,180,150,30);  
    $pdf->rect(38,180,150,15);  
    $pdf->rect(38,180,110,30);  

 
    
    $pdf->addTextWrap(300,$YPos-40,100,$FontSize,_('Lead Slno:'), 'left');
    $pdf->addTextWrap(300,$YPos-50,100,$FontSize,_('Customer Name'), 'left');
    $pdf->addTextWrap(300,$YPos-60,150,$FontSize,_('Phone'), 'left');
    $pdf->addTextWrap(300,$YPos-70,50,$FontSize,_('Product Category'), 'left');
    $pdf->addTextWrap(300,$YPos-80,50,$FontSize,_('Output'), 'left');
    $pdf->addTextWrap(300,$YPos-90,50,$FontSize,_('Enquiry type'), 'left');
    $pdf->addTextWrap(300,$YPos-115,100,$FontSize+1,_('Description of Reciepts '), 'left'); 
    $pdf->addTextWrap(300,$YPos-130,50,$FontSize+1,_('Advance Payed'), 'left');
    $pdf->addTextWrap($Page_Width-$Right_Margin-100,$YPos-190,90,$FontSize,_('Customer Signature'),'right');
    $pdf->addTextWrap(300,$YPos-190,160,$FontSize,_('Authorized Signature'),'left');
    $pdf->rect(298,180,150,30);   
    $pdf->rect(298,180,150,15);  
    $pdf->rect(298,180,110,30);    
    $YPos -=$line_height;   
    
    $pdf->line(290,$YPos+70,290,$YPos-190);
//    $pdf->addTextWrap(415,$YPos,50,$FontSize,_('Level'), 'right');


    $FontSize=8;
//    $YPos =$YPos - (2*$line_height);
    $PageNumber++;
} // End of PrintHeader() function
?>
