<?php                                          
   $PageSecurity = 81;
    include ('includes/session.inc');
   $title = _('Complient list');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'Classes/PHPExcel.php';
$rowCount=2;
 /* $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
$cacheSettings = array( 'memoryCacheSize' => '32MB');
PHPExcel_Settings::etCacheStorageMethod($cacheMethod, $cacheSettings);   */


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()
    ->getStyle('A1:L1')
    ->getFont()
    ->applyFromArray(
        array(
            'name' => 'Arial',
            'color' => array(
                'rgb' => 'FF0000'
            )
        )
    );

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                             ->setLastModifiedBy("Maarten Balliauw")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");


// Add some data
/*
  $sql2="SELECT debtorsmaster.name,CONCAT(custbranch.braddress1,',',custbranch.braddress2) AS address,
  bio_district.district,debtorsmaster.debtorno AS 'debtorno',
CONCAT(custbranch.phoneno,',',custbranch.faxno) AS mobile,
DATE_FORMAT(bio_installation_status.installed_date,'%d-%m-%Y') AS insdate,stockitemproperty.model,
stockitemproperty.capacity, bio_plantstatus.plantstatus ,orderplant.stkcode AS 'stockid', 
CASE WHEN bio_past_fuel.firewood>0 THEN 'FIREWOOD' WHEN bio_past_fuel.lpg>0 THEN 'LPG' WHEN bio_past_fuel.grid>0 THEN 'GRID' END AS 'Replacing'
FROM salesorders 
LEFT JOIN bio_installation_status ON salesorders.orderno=bio_installation_status.orderno
LEFT JOIN debtorsmaster ON salesorders.debtorno=debtorsmaster.debtorno
LEFT JOIN custbranch ON debtorsmaster.debtorno=custbranch.debtorno
LEFT JOIN bio_district ON bio_district.cid=custbranch.cid AND bio_district.stateid=custbranch.stateid AND bio_district.did=custbranch.did

LEFT JOIN orderplant ON salesorders.orderno=orderplant.orderno
LEFT JOIN stockitemproperty ON orderplant.stkcode=stockitemproperty.stockid
LEFT JOIN bio_plantstatus ON bio_installation_status.plant_status=bio_plantstatus.id AND salesorders.debtorno NOT IN (SELECT debtorno FROM cdmlist) 

INNER JOIN cdmlist ON   salesorders.debtorno= cdmlist.debtorno
LEFT JOIN bio_past_fuel ON  bio_past_fuel.debtorno=cdmlist.debtorno
union

SELECT debtorsmaster.name,concat(custbranch.braddress1,',',custbranch.braddress2) as address,`bio_district`.`district` ,`bio_oldorders`.`debtorno` as 'debtorno' ,  concat( `custbranch`.`phoneno` , ',', `custbranch`.`faxno` ) AS 'mobile',date_format( `bio_oldorders`.`installationdate` , '%d-%m-%Y' ) AS insdate ,stockitemproperty.model, stockitemproperty.capacity, bio_plantstatus.plantstatus,bio_oldorders.plantid as 'stockid' , CASE WHEN bio_past_fuel.firewood>0 THEN 'FIREWOOD' WHEN bio_past_fuel.lpg>0 THEN 'LPG' WHEN bio_past_fuel.grid>0 THEN 'GRID' END AS 'Replacing'
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
INNER JOIN `custbranch` ON ( `debtorsmaster`.`debtorno` = `custbranch`.`debtorno` )
LEFT JOIN `bio_district` ON ( `custbranch`.`did` = `bio_district`.`did` )
AND (
`bio_district`.`cid` = `custbranch`.`cid`
)
AND (
`custbranch`.`stateid` = `bio_district`.`stateid`
)
LEFT JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid
LEFT JOIN bio_plantstatus ON bio_oldorders.currentstatus = bio_plantstatus.id
inner join cdmlist on   bio_oldorders.debtorno= cdmlist.debtorno
LEFT JOIN bio_past_fuel ON  bio_past_fuel.debtorno=cdmlist.debtorno
"; 
$result=DB_query($sql2,$db);*/

$sql3=$_SESSION['qrycomp'];

       $result=DB_query($sql3,$db);  
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SL No');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Ticket No');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Customer Code');
     $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'LSG');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Complient Date');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Category');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Type');
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Status');  
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Closed Date');  
      

while($row = DB_fetch_array($result)){
    if($row['LSG_type']==1){
         $LSG_name=$row['corporation']."(C)";
     }elseif($row['LSG_type']==2){
         $LSG_name=$row['municipality']."(M)";
     }elseif($row['LSG_type']==3){
         if($row['block_name']!=0){
         $LSG_name=$row['pname']."(P)";
         }
     }elseif($row['LSG_type']==0){
         $LSG_name="";
     }
                                       
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $rowCount-1);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['ticketno']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['debtorno']);
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['name']);

    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $LSG_name);
    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['createdon']);
    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['impact']);
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['complaint']);
    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['status']);
    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['manual_closedate']);
   
    
    $rowCount++;
}
for ($col = 'A'; $col != 'I'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
}

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0);
           
$name='COMPLIENT LIST-'.date("d-m-Y");
$fnae=$name.'.xlsx';
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($name);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a clientâ€™s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="COMPLIENT-LIST.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>

