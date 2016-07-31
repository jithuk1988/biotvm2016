<?php
   $PageSecurity = 80;
    include ('includes/session.inc');
   $title = _('ITEM LIST');
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
$objPHPExcel->getProperties()->setCreator("BiotechIN")
                             ->setLastModifiedBy("BiotechIN")
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

$sql3="SELECT
    bio_maincategorymaster.maincatname AS 'Main Category'
    , substockcategory.subcategorydescription AS 'Sub Category'
    , stockcategory.categorydescription AS 'Category' 
    , stockmaster.description AS 'Item'
    , IFNULL(stockitemproperty.capacity,'') AS 'Capacity'
FROM
   stockmaster
    INNER JOIN stockitemproperty 
        ON (stockmaster.stockid = stockitemproperty.stockid)
    INNER JOIN stockcategory 
        ON (stockmaster.categoryid = stockcategory.categoryid)
    INNER JOIN bio_maincat_subcat 
        ON (stockcategory.categoryid = bio_maincat_subcat.subsubcatid)
    INNER JOIN substockcategory 
        ON (bio_maincat_subcat.subcatid = substockcategory.subcategoryid) AND (substockcategory.maincatid = bio_maincat_subcat.maincatid) 
    INNER JOIN bio_maincategorymaster 
        ON (substockcategory.maincatid = bio_maincategorymaster.maincatid) WHERE stockitemproperty.capacity!='null' ORDER BY bio_maincategorymaster.maincatid,substockcategory.subcategoryid,stockcategory.categorydescription, stockitemproperty.capacity";

       $result=DB_query($sql3,$db);  
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SL No');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Main Category');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Sub Category');
     $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Category');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Item');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Capacity');
    
                $sl=1;
while($row = DB_fetch_array($result)){
                                  
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $sl);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['Main Category']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['Sub Category']);
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['Category']);
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['Item']);
     $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['Capacity']);
       $sl=$sl+1;
    $rowCount++;
   
}
for ($col = 'A'; $col != 'G'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
}

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0);
           
$name='FULL-ITEM-LIST-'.date("d-m-Y");
$fnae=$name.'.xlsx';
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($name);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$name.'.xlsx');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>

