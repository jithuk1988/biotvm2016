<?php
/* $Id offices.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 15;
include('includes/session.inc');
$title = _('Offices ') . ' / ' . _('Maintenance');
include('includes/header.inc');

                                       //////////////////////////////////////
       /*
          error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

/** Include PHPExcel */
/*require_once 'Classes/PHPExcel.php';           */


// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
/*$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("JITHU K")
                             ->setLastModifiedBy("JITHU K")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("XLSX file");   */


// Create a first sheet, representing sales data
//echo date('H:i:s') , " Add some data" , EOL;

if (isset($_POST['SelectedType'])){
	$SelectedType = strtoupper($_POST['SelectedType']);
} elseif (isset($_GET['SelectedType'])){
	$SelectedType = strtoupper($_GET['SelectedType']);
}

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Offices')
	. '" alt="" />' . _('Offices Setup') . '</p>';
//echo '<div class="page_help_text">' . _('Add / edit / delete Offices') . '</div><br />';

if (isset($SelectedType)) {

	echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Offices Defined') . '</a></div><p>';
}
 // end if user wish to delete

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (strlen($_POST['offName']) >100) {
		$InputError = 1;
		echo prnMsg(_('The Office name description must be 100 characters or less long'),'error');
		$Errors[$i] = 'Office';
		$i++;
	}

	if (strlen($_POST['offName'])==0) {
		$InputError = 1;
		echo prnMsg(_('The Office name description must contain at least one character'),'error');
		$Errors[$i] = 'Office';
		$i++;
	}

	//$checksql = "SELECT count(*)
//		     FROM bio_office
//		     WHERE office = '" . $_POST['offName'] . "'";
//	$checkresult=DB_query($checksql, $db);
//	$checkrow=DB_fetch_row($checkresult);
//	if ($checkrow[0]>0) {
//		$InputError = 1;
//		echo prnMsg(_('You already have a Office called').' '.$_POST['offName'],'error');
//		$Errors[$i] = 'office';
//		$i++;
//	}

	if (isset($SelectedType)) {

		$sql = "UPDATE bio_office SET officetype = '" . $_POST['offtype'] . "',
                                      reporting_off='".$_POST['reportOff']."',
                                      location= '". $_POST['loc'] ."',
                                      address='".$_POST['add']."',
                                      c_person='".$_POST['cPerson']."',
                                      c_number='".$_POST['cNumber']."',
                                      email='".$_POST['email']."',
                                      fax='" . $_POST['fax'] . "'
                                      WHERE id = '" . $SelectedType . "'";

		$msg = _('The Office') . ' ' . $SelectedType . ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the type is not being duplicated

		$checksql = "SELECT count(*)
             FROM bio_office
             WHERE office = '" . $_POST['offName'] . "'";
    $checkresult=DB_query($checksql, $db);
    $checkrow=DB_fetch_row($checkresult);
    if ($checkrow[0]>0) {
        $InputError = 1;
        echo prnMsg(_('You already have a Office called').' '.$_POST['offName'],'warn');
        $Errors[$i] = 'office';
        $i++;
    } else {

			// Add new record on submit
            
            
            
			 $sql = "INSERT INTO bio_office(office, 
                                           officetype, 
                                           location, address, 
                                           reporting_off, 
                                           c_person, c_number, 
                                           email, fax)
					          VALUES ('" . $_POST['offName'] . "',
                                        '".$_POST['offtype']."',
                                        '". $_POST['loc'] ."',
                                        '".$_POST['add']."',
                                        '".$_POST['reportOff']."',
                                        '".$_POST['cPerson']."',
                                        '".$_POST['cNumber']."',
                                        '".$_POST['email']."',
                                        '" . $_POST['fax'] . "')";


			$msg = _('Office') . ' ' . $_POST['offName'] .  ' ' . _('has been created');
			$checkSql = "SELECT count(id)
			     FROM bio_office";
			$result = DB_query($checkSql, $db);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql,$db);



		prnMsg($msg,'success');

		unset($SelectedType);
		unset($_POST['OfficeID']);
		unset($_POST['offName']);
	}

} elseif ( isset($_GET['delete']) ) {

	/* $sql = "SELECT COUNT(*) FROM suppliers WHERE supptype='" . $SelectedType . "'";

	$ErrMsg = _('The number of suppliers using this Type record could not be retrieved because');
	$result = DB_query($sql,$db,$ErrMsg);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg (_('Cannot delete this type because suppliers are currently set up to use this type') . '<br />' .
			_('There are') . ' ' . $myrow[0] . ' ' . _('suppliers with this type code'));
	} else { */

		$sql="DELETE FROM bio_office WHERE id='" . $SelectedType . "'";
		$ErrMsg = _('The Office record could not be deleted because');
		$result = DB_query($sql,$db,$ErrMsg);
		prnMsg(_('Office ') . $SelectedType  . ' ' . _('has been deleted') ,'success');

		unset ($SelectedType);
		unset($_GET['delete']);

	/*}*/
}
echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:60%';><tr><td>";  
  echo "<fieldset style='width:100%;height:215px'>";
   echo "<legend><h3>Office Master</h3></legend>";

if (! isset($_GET['delete'])) {

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
   
    echo '<table>'; //Main table
    // The user wish to EDIT an existing type
    if ( isset($SelectedType) AND $SelectedType!='' ) {
                $sql = "SELECT *
                FROM bio_office
                WHERE id='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        $_POST['OfficeID'] = $myrow['id'];
        $_POST['offName']  = $myrow['office'];
        $_POST['offtype']  = $myrow['officetype'];
        $_POST['loc']  = $myrow['location'];
        $_POST['add']  = $myrow['address']; 
        $_POST['reportOff']  = $myrow['reporting_off'];
        $_POST['cPerson']  = $myrow['c_person'];
        $_POST['cNumber']  = $myrow['c_number'];  
        $_POST['email']  = $myrow['email'];  
        $_POST['fax']  = $myrow['fax'];
        

        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="OfficeID" value="' . $_POST['OfficeID'] . '">';

        // We dont allow the user to change an existing type code
           
    echo '<tr><td>' ._('Office ID') . ': </td>
                <td>' . $_POST['OfficeID'] . '</td>
            </tr>';
    }

    if (!isset($_POST['offName'])) {
        $_POST['offName']='';
    } 
    
        echo '<tr><td>' . _('Office Name') . ':</td>
            <td><input type="text" name="offName" value="' . $_POST['offName'] . '"></td>
        </tr>';
        
        echo '<tr><td>' . _('Office Type') . ':</td>';
        echo '<td><select name="offtype" id="offtype" style="width:100%" onchange="reportingOffice(this.value)">';
        $sql="select * from office_type";
        $result=DB_query($sql,$db);
        echo'<option value=0></option>';
        while($row=DB_fetch_array($result))
        {       
        if ($row['off_typid']==$_POST['offtype'])
        {
         echo '<option selected value="';
        } else {
         echo '<option value="';
        }
        echo $row['off_typid'] . '">'.$row['officetype'];
        echo '</option>';
        }
        echo'</select></td>';
        echo'</tr>';

        echo '<tr id="reptoff"><td>' . _('Reporting Office') . ':</td>';
         
         echo '<td><select name="reportOff" style="width:100%">';
            $sql="select * from bio_office";
             $result=DB_query($sql,$db);
             echo'<option value=0></option>';
             while($row=DB_fetch_array($result))
             {       
                 if ($row['id']==$_POST['reportOff'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row['id'] . '">'.$row['office'];
                 echo '</option>';
             }
            echo'</select></td>';
         echo'</tr>';
          echo'<tr id="reptoff2">';
     echo'</tr>';       
         
                  echo '<tr><td>' . _('Location') . ':</td>';
         echo '<td><select name=loc style="width:100%">';
            $sql="select * from locations";
             $result=DB_query($sql,$db);
             echo'<option value=0></option>';
             while($row=DB_fetch_array($result))
             {       
                 if ($row['loccode']==$_POST['loc'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row['loccode'] . '">'.$row['locationname'];
                 echo '</option>';
             }
            echo'</select></td>';
        echo'</tr>';
                
  //  echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '"></div>';
      
       echo '</table></fieldset></td><td>'; 
       
         echo "<fieldset style='width:80%;height:215px'>";
         echo "<legend><h3>Office Details</h3></legend>";
       
       echo '<table>';
       echo '<tr><td>' . _('Address') . ':</td>
            <td><textarea rows=3 cols=25 name=add value="'. $_POST['add'] .'"></textarea></td>
            </tr>';
              
       echo '<tr><td>' . _('Contact Person') . ':</td>
                <td><input type="text" name="cPerson" value="' . $_POST['cPerson'] . '"></td>
                </tr>';
                
          echo '<tr><td>' . _('Contact No') . ':</td>
            <td><input type="text" name="cNumber" value="' . $_POST['cNumber'] . '"></td>
        </tr>'; 
         echo '<tr><td>' . _('E-mail') . ':</td>
            <td><input type="text" name="email" value="' . $_POST['email'] . '"></td>
        </tr>'; 
         echo '<tr><td>' . _('Fax') . ':</td>
            <td><input type="text" name="fax" value="' . $_POST['fax'] . '"></td>
        </tr>'; 

     
        echo'</table></td></tr><tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '"></div></td></tr>'; // close main table

    echo '</form>';
        echo '<br />';
}

echo '</fieldset>'; 
echo "<tr><td colspan=2>";
    echo "<fieldset style='width:80%'>";
   echo "<legend><h3>Office Master Created</h3></legend>";

if (!isset($SelectedType)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedType will
 *  exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then
 * none of the above are true and the list of sales types will be displayed with links to delete or edit each. These will call
 * the same page again and allow update/input or deletion of the records
 */

display($db);
}
else
{
    display($db);
}
echo '</fieldset>';
echo "</td></tr></table>";
 $sql3 = "SELECT bio_office.id,
                   bio_office.office,
                   bio_office.officetype,
                   office_type.officetype AS offtype, 
                   bio_office.location,
                   locations.locationname, 
                   bio_office.c_number, 
                   bio_office.reporting_off 
            FROM bio_office,office_type,locations
            WHERE bio_office.officetype=office_type.off_typid
            AND locations.loccode=bio_office.location";
    $result3 = DB_query($sql3,$db);
//end of ifs and buts!
      /* $objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Office ID')
                              ->setCellValue('B1', 'Office')
                              ->setCellValue('C1', 'Type')
                              ->setCellValue('D1', 'Location')
                              ->setCellValue('E1', 'Contact No')
                              ;
                                  $slno=2;
                                   $objPHPExcel->getActiveSheet()->freezePane('A2');
                       //    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Description')   
                              while ($mr=DB_fetch_array($result3))
                              {
                                  $objPHPExcel->getActiveSheet()->setCellValue('A'.$slno, $mr['id'])   ;
                                  $objPHPExcel->getActiveSheet()->setCellValue('B'.$slno, $mr['office'])   ;
                                  $objPHPExcel->getActiveSheet()->setCellValue('C'.$slno, $mr['offtype'])   ;
                                  $objPHPExcel->getActiveSheet()->setCellValue('D'.$slno, $mr['locationname'])   ;
                                  $objPHPExcel->getActiveSheet()->setCellValue('E'.$slno, $mr['c_number'])   ;
                                 $slno++;
                              }
    
      //echo date('H:i:s') , " Set column widths" , EOL;
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

// Add conditional formatting
//echo date('H:i:s') , " Add conditional formatting" , EOL;
$objConditional1 = new PHPExcel_Style_Conditional();
$objConditional1->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_BETWEEN)
                ->addCondition('200')
                ->addCondition('400');
$objConditional1->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objConditional1->getStyle()->getFont()->setBold(true);
$objConditional1->getStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

$objConditional2 = new PHPExcel_Style_Conditional();
$objConditional2->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_LESSTHAN)
                ->addCondition('0');
$objConditional2->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objConditional2->getStyle()->getFont()->setItalic(true);
$objConditional2->getStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

$objConditional3 = new PHPExcel_Style_Conditional();
$objConditional3->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_GREATERTHANOREQUAL)
                ->addCondition('0');
$objConditional3->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_GREEN);
$objConditional3->getStyle()->getFont()->setItalic(true);
$objConditional3->getStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

//$conditionalStyles = $objPHPExcel->getActiveSheet()->getStyle('B')->getConditionalStyles();
/*array_push($conditionalStyles, $objConditional1);
array_push($conditionalStyles, $objConditional2);
array_push($conditionalStyles, $objConditional3);
$objPHPExcel->getActiveSheet()->getStyle('B2')->setConditionalStyles($conditionalStyles);
   */

//    duplicate the conditional styles across a range of cells
//echo date('H:i:s') , " Duplicate the conditional formatting across a range of cells" , EOL;
/*$objPHPExcel->getActiveSheet()->duplicateConditionalStyle(
                $objPHPExcel->getActiveSheet()->getStyle('B2')->getConditionalStyles(),
                'B3:B7'
              );    */

       /*
// Set fonts
//echo date('H:i:s') , " Set fonts" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('A7:B7')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);


// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
//echo date('H:i:s') , " Set header/footer" , EOL;
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BPersonal cash register&RPrinted on &D');
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');


// Set page orientation and size
//echo date('H:i:s') , " Set page orientation and size" , EOL;
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


// Rename worksheet
//echo date('H:i:s') , " Rename worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Office List');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('office.xlsx');       */
//echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;


// Echo memory peak usage
//echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
//echo date('H:i:s') , " Done writing file" , EOL;
//echo 'File has been created in ' , getcwd() , EOL;                        
                              
                              
                              //////////////////////////////////////////////////
function display($db)
{
    $sql = "SELECT bio_office.id,
                   bio_office.office,
                   bio_office.officetype,
                   office_type.officetype AS offtype, 
                   bio_office.location,
                   locations.locationname, 
                   bio_office.c_number, 
                   bio_office.reporting_off 
            FROM bio_office,office_type,locations
            WHERE bio_office.officetype=office_type.off_typid
            AND locations.loccode=bio_office.location";
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection">';
    echo '<tr>
        <th>' . _('Office ID') . '</th>
        <th>' . _('Office') . '</th>
         <th>' . _('Office Type') . '</th> 
         <th>' . _('Location') . '</th>
         <th>' . _('Reporting Office') . '</th>
         <th>' . _('Contact Number') . '</th>
        </tr>';

$k=0; //row colour counter

while ($myrow = DB_fetch_array($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
    
    
    $reportOff=$myrow['reporting_off'];
    //if($reportOff==1 || $reportOff==2 || $reportOff==3 || $reportOff==4 || $reportOff==5 || $reportOff==6 || $reportOff==7)
    //{
 $sql2="select office from bio_office where id=".$reportOff;
        $result2=DB_query($sql2,$db);
        $row2=DB_fetch_array($result2);
    //}

    $reportOff1=$row2['office'];

    printf('<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Office?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $myrow['id'],
        $myrow['office'],
        $myrow['offtype'],
        $myrow['locationname'],
        $reportOff1,
        $myrow['c_number'],        
        $_SERVER['PHP_SELF'] . '?',
        $myrow[0],
        $_SERVER['PHP_SELF'] . '?',
        $myrow[0]);
    }
    //END WHILE LIST LOOP
    echo '</table>';
    echo "</div>";
}
include('includes/footer.inc');
?>


            <script type="text/javascript">  

function reportingOffice1(str){
  //  alert ("dfffffff");
if (str=="")
  {
  document.getElementById("reptoff2").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("reptoff2").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_reportingOffice2.php?rept=" + str,true);
xmlhttp.send();    
}




function reportingOffice(str){
if (str=="")
  {
  document.getElementById("reptoff").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("reptoff").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_reportingOffice.php?rept=" + str,true);
xmlhttp.send();    
}


</script>