<?php
    $PageSecurity = 80;
 include ('includes/session.inc');
$title = _('Capacity Report');  
include('includes/header.inc'); 
  echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>";  
  if($_POST['submit'])
  {
    $sql_sel="SELECT `bio_oldorders`.`orderno` , `bio_oldorders`.`client_id` , 'PO-1' AS `plantid` , 'Portable Digester Ordinary 1 Cum XX' AS 'description', '1' AS quantity, `bio_oldorders`.`createdby` , `bio_oldorders`.`createdon` , `bio_oldorders`.`currentstatus` ,  month( `bio_oldorders`.`installationdate` ) as month , DAY( `bio_oldorders`.`installationdate` ) as day
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
INNER JOIN `custbranch` ON ( `debtorsmaster`.`debtorno` = `custbranch`.`debtorno` )
LEFT JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid
WHERE bio_oldorders.installationdate
BETWEEN '2010-1-1'
AND '2010-12-31'
ORDER BY RAND()";
$result=DB_query($sql_sel,$db);
$type="D";
$i=0;
  $sql_code="SELECT * FROM bio_clientidgeneration WHERE enquirytype=1";   
    $result_code = DB_query($sql_code,$db);
    $myrow_count = DB_num_rows($result_code);
    $myrow_code = DB_fetch_array($result_code); 
    if($myrow_count>0){
        $code=$myrow_code['code'];
    } 
  while(($row=DB_fetch_array($result)) && $i<900)
  {
      
      
        $mon=$row['month'];
        $day=$row['day'];
       
            $code++;     
        $ID=$type.$code;
             $sql_ins=" INSERT INTO `bio_oldorders`( `debtorno`, `client_id`, `plantid`, `description`, `quantity`,  `currentstatus`, `installationdate`, `createdby`, `createdon`) VALUES ('".$ID."','".$row['client_id']."','".$row['plantid']."','".$row['description']."','1','".$row['currentstatus']."','2009-".$mon."-".$day."',
  '".$row['createdby']."','".$row['createdon']."')";
  $result_ins = DB_query($sql_ins,$db); 
  $i++;   
        
  //$result_ins = DB_query($sql_ins,$db); 
  echo "new";
  }
 $sql="UPDATE bio_clientidgeneration  SET code=".$code." WHERE enquirytype=1";
      $result = DB_query($sql,$db); 
  }
  if($_POST['submit1'])
  {
  $sql_cr="DROP TEMPORARY TABLE IF EXISTS shuffle1";
   $resultcr = DB_query($sql_cr,$db); 
  $sql_cr2="DROP TEMPORARY TABLE IF EXISTS shuffle2";
  $resultcr2 = DB_query($sql_cr2,$db); 
  $sql_cr2="DROP TEMPORARY TABLE IF EXISTS shuffle3";
   $resultcr2 = DB_query($sql_cr2,$db); 
   $sql_new="CREATE TEMPORARY TABLE shuffle1 (id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), original_values varchar(255), key original_values(original_values) )";
  $result_new=DB_query($sql_new,$db); 
   $sql_new2="CREATE TEMPORARY TABLE shuffle2 (id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(50) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(15) NOT NULL DEFAULT '',
  `currcode` char(3) NOT NULL DEFAULT '',
  `salestype` char(2) NOT NULL DEFAULT '',
  `clientsince` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `holdreason` smallint(6) NOT NULL DEFAULT '0',
  `paymentterms` char(2) NOT NULL DEFAULT 'f',
  `discount` double NOT NULL DEFAULT '0',
  `pymtdiscount` double NOT NULL DEFAULT '0',
  `lastpaid` double NOT NULL DEFAULT '0',
  `lastpaiddate` datetime DEFAULT NULL,
  `creditlimit` double NOT NULL DEFAULT '1000',
  `invaddrbranch` tinyint(4) NOT NULL DEFAULT '0',
  `discountcode` char(2) NOT NULL DEFAULT '',
  `ediinvoices` tinyint(4) NOT NULL DEFAULT '0',
  `ediorders` tinyint(4) NOT NULL DEFAULT '0',
  `edireference` varchar(20) NOT NULL DEFAULT '',
  `editransport` varchar(5) NOT NULL DEFAULT 'email',
  `ediaddress` varchar(50) NOT NULL DEFAULT '',
  `ediserveruser` varchar(20) NOT NULL DEFAULT '',
  `ediserverpwd` varchar(20) NOT NULL DEFAULT '',
  `taxref` varchar(20) NOT NULL DEFAULT '',
  `customerpoline` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` tinyint(4) NOT NULL DEFAULT '1',
  `cid` int(11) DEFAULT NULL,
  `stateid` int(11) DEFAULT NULL,
  `did` int(11) DEFAULT NULL,
  `taluk` int(11) DEFAULT NULL,
  `LSG_type` int(11) DEFAULT NULL,
  `LSG_name` int(11) DEFAULT NULL,
  `block_name` int(11) DEFAULT NULL,
  `LSG_ward` varchar(50) DEFAULT NULL,
  `village` varchar(50) DEFAULT NULL,
  `toiletlink` int(11) NOT NULL,
  `careof` varchar(50) DEFAULT NULL,
  `fileno` varchar(70) DEFAULT NULL,
  `docloc` varchar(50) DEFAULT NULL,
  `ebno` varchar(50) DEFAULT NULL) ";
$result_new2=DB_query($sql_new2,$db); 
 $sql_new3="CREATE TEMPORARY TABLE shuffle3 (id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), original_values varchar(255), key original_values(original_values) )";
  $result_new3=DB_query($sql_new3,$db); 
     $sql1="INSERT INTO shuffle1 (id, original_values) SELECT NULL, debtorsmaster.name FROM debtorsmaster
     INNER JOIN `bio_oldorders` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
INNER JOIN `custbranch` ON ( `debtorsmaster`.`debtorno` = `custbranch`.`debtorno` )
where  bio_oldorders.installationdate between '2011-1-1' and '2011-12-31'
      ORDER BY rand()";
    $result1 = DB_query($sql1,$db); 
     
     
     
$sql2="INSERT INTO shuffle2 (id, `address1`, `address2`, `address3`, `address4`, `address5`, `address6`, `currcode`, `salestype`, `clientsince`, `holdreason`, `paymentterms`, `discount`, `pymtdiscount`, `lastpaid`, `lastpaiddate`, `creditlimit`, `invaddrbranch`, `discountcode`, `ediinvoices`, `ediorders`, `edireference`, `editransport`, `ediaddress`, `ediserveruser`, `ediserverpwd`, `taxref`, `customerpoline`, `typeid`, `cid`, `stateid`, `did`, `taluk`, `LSG_type`, `LSG_name`, `block_name`, `LSG_ward`, `village`, `toiletlink`, `careof`, `fileno`, `docloc`, `ebno`) SELECT NULL,`debtorsmaster`.`address1`, `debtorsmaster`.`address2`, `debtorsmaster`.`address3`, `debtorsmaster`.`address4`,
 `debtorsmaster`.`address5`, `debtorsmaster`.`address6`, `debtorsmaster`.`currcode`, `debtorsmaster`.`salestype`, `debtorsmaster`.`clientsince`, 
 `debtorsmaster`.`holdreason`, `debtorsmaster`.`paymentterms`, `debtorsmaster`.`discount`, `debtorsmaster`.`pymtdiscount`, `debtorsmaster`.`lastpaid`,
  `debtorsmaster`.`lastpaiddate`, `debtorsmaster`.`creditlimit`, `debtorsmaster`.`invaddrbranch`, `debtorsmaster`.`discountcode`, `debtorsmaster`.`ediinvoices`, 
  `debtorsmaster`.`ediorders`, `debtorsmaster`.`edireference`, `debtorsmaster`.`editransport`, `debtorsmaster`.`ediaddress`, `debtorsmaster`.`ediserveruser`, 
  `debtorsmaster`.`ediserverpwd`, `debtorsmaster`.`taxref`, `debtorsmaster`.`customerpoline`, `debtorsmaster`.`typeid`, 
  `debtorsmaster`.`cid`, `debtorsmaster`.`stateid`, `debtorsmaster`.`did`, `debtorsmaster`.`taluk`, `debtorsmaster`.`LSG_type`, `debtorsmaster`.`LSG_name`, 
  `debtorsmaster`.`block_name`, `debtorsmaster`.`LSG_ward`, `debtorsmaster`.`village`, `debtorsmaster`.`toiletlink`, `debtorsmaster`.`careof`, `debtorsmaster`.`fileno`, 
  `debtorsmaster`.`docloc`, `debtorsmaster`.`ebno`
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
INNER JOIN `custbranch` ON ( `debtorsmaster`.`debtorno` = `custbranch`.`debtorno` )
where  bio_oldorders.installationdate between '2011-1-1' and '2011-12-31' ORDER BY RAND() "; 
  $result2 = DB_query($sql2,$db); 
  $sql3="INSERT INTO shuffle3 (id, original_values) SELECT NULL, debtorno
FROM `bio_oldorders`
WHERE `description` LIKE '%xx%'
AND bio_oldorders.installationdate
BETWEEN '2012-1-1'
AND '2012-12-31'";
 $result1 = DB_query($sql3,$db); 
       $sql_las="INSERT INTO `debtorsmaster`(`debtorno`, `name`, `address1`, `address2`, `address3`, `address4`, `address5`, `address6`, `currcode`, `salestype`, `clientsince`, `holdreason`, `paymentterms`, `discount`, `pymtdiscount`, `lastpaid`, `lastpaiddate`, `creditlimit`, `invaddrbranch`, `discountcode`, `ediinvoices`, `ediorders`, `edireference`, `editransport`, `ediaddress`, `ediserveruser`, `ediserverpwd`, `taxref`, `customerpoline`, `typeid`, `cid`, `stateid`, `did`, `taluk`, `LSG_type`, `LSG_name`, `block_name`, `LSG_ward`, `village`, `toiletlink`, `careof`, `fileno`, `docloc`, `ebno`) SELECT shuffle3.original_values as debtorno,shuffle1.original_values as name, 
     shuffle2.`address1`, shuffle2.`address2`, shuffle2.`address3`, shuffle2.`address4`, shuffle2.`address5`, shuffle2.`address6`, shuffle2.`currcode`, shuffle2.`salestype`,
      shuffle2.`clientsince`, shuffle2.`holdreason`, shuffle2.`paymentterms`, shuffle2.`discount`, shuffle2.`pymtdiscount`, shuffle2.`lastpaid`, shuffle2.`lastpaiddate`,
       shuffle2.`creditlimit`, shuffle2.`invaddrbranch`, shuffle2.`discountcode`, shuffle2.`ediinvoices`, shuffle2.`ediorders`, shuffle2.`edireference`, shuffle2.`editransport`, shuffle2.`ediaddress`, shuffle2.`ediserveruser`, shuffle2.`ediserverpwd`, shuffle2.`taxref`, shuffle2.`customerpoline`, shuffle2.`typeid`, shuffle2.`cid`, shuffle2.`stateid`, shuffle2.`did`, shuffle2.`taluk`, shuffle2.`LSG_type`, shuffle2.`LSG_name`, shuffle2.`block_name`, shuffle2.`LSG_ward`, shuffle2.`village`, shuffle2.`toiletlink`, shuffle2.`careof`, shuffle2.`fileno`, shuffle2.`docloc`, shuffle2.`ebno`  FROM shuffle1 
      INNER JOIN shuffle2 ON shuffle2.id = shuffle1.id 
      INNER JOIN  shuffle3 ON shuffle3.id=shuffle1.id ";
    $result_ins = DB_query($sql_las,$db); 
    echo "inserted";
  }
  if($_POST['submit2'])
  {
        $sql_cr="DROP TEMPORARY TABLE IF EXISTS shuffle1";
   $resultcr = DB_query($sql_cr,$db); 
   $sql_cr2="DROP TEMPORARY TABLE IF EXISTS shuffle2";
  $resultcr2 = DB_query($sql_cr2,$db); 
  
    $sql_new="CREATE TEMPORARY TABLE shuffle1 (id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
    `phoneno` varchar(40) NOT NULL,
  `faxno` varchar(40) NOT NULL )";
  $result_new=DB_query($sql_new,$db); 
  $sql_new2="CREATE TEMPORARY TABLE shuffle2 (id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), `branchcode` varchar(10) NOT NULL DEFAULT '',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `brname` varchar(40) NOT NULL DEFAULT '',
  `braddress1` varchar(40) NOT NULL DEFAULT '',
  `braddress2` varchar(40) NOT NULL DEFAULT '',
  `braddress3` varchar(40) NOT NULL DEFAULT '',
  `braddress4` varchar(50) NOT NULL DEFAULT '',
  `braddress5` varchar(20) NOT NULL DEFAULT '',
  `braddress6` varchar(15) NOT NULL DEFAULT '',
  `lat` float(10,6) NOT NULL DEFAULT '0.000000',
  `lng` float(10,6) NOT NULL DEFAULT '0.000000',
  `estdeliverydays` smallint(6) NOT NULL DEFAULT '1',
  `area` char(3) NOT NULL,
  `salesman` varchar(4) NOT NULL DEFAULT '',
  `fwddate` smallint(6) NOT NULL DEFAULT '0',
  `contactname` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `defaultlocation` varchar(5) NOT NULL DEFAULT '',
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '1',
  `defaultshipvia` int(11) NOT NULL DEFAULT '1',
  `deliverblind` tinyint(1) DEFAULT '1',
  `disabletrans` tinyint(4) NOT NULL DEFAULT '0',
  `brpostaddr1` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr2` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr3` varchar(30) NOT NULL DEFAULT '',
  `brpostaddr4` varchar(20) NOT NULL DEFAULT '',
  `brpostaddr5` varchar(20) NOT NULL DEFAULT '',
  `brpostaddr6` varchar(15) NOT NULL DEFAULT '',
  `specialinstructions` text NOT NULL,
  `custbranchcode` varchar(30) NOT NULL DEFAULT '',
  `cid` int(11) DEFAULT NULL,
  `stateid` int(11) DEFAULT NULL,
  `did` int(11) DEFAULT NULL,
  `taluk` int(11) DEFAULT NULL,
  `LSG_type` int(11) DEFAULT NULL,
  `LSG_name` int(11) DEFAULT NULL,
  `block_name` int(11) DEFAULT NULL,
  `LSG_ward` varchar(50) DEFAULT NULL,
  `village` varchar(50) DEFAULT NULL  ) ";
$result_new2=DB_query($sql_new2,$db);
  
     $sql1="INSERT INTO shuffle1 (id,phoneno,faxno) SELECT  NULL,`phoneno`,`faxno` FROM `custbranch` 
      ORDER BY rand()";
    $result1 = DB_query($sql1,$db); 
    
    
  
    $sql2="INSERT INTO shuffle2(id,`branchcode`, `debtorno`, `brname`, `braddress1`, `braddress2`, `braddress3`, `braddress4`, `braddress5`, `braddress6`, `lat`, `lng`, `estdeliverydays`, `area`, `salesman`, `fwddate`, `contactname`, `email`, `defaultlocation`, `taxgroupid`, `defaultshipvia`, `deliverblind`, `disabletrans`, `brpostaddr1`, `brpostaddr2`, `brpostaddr3`, `brpostaddr4`, `brpostaddr5`, `brpostaddr6`, `specialinstructions`, `custbranchcode`, `cid`, `stateid`, `did`, `taluk`, `LSG_type`, `LSG_name`, `block_name`, `LSG_ward`, `village`)SELECT NULL,bio_oldorders.debtorno,debtorsmaster.debtorno, `name`, `address1`, `address2`, `address3`, `address4`, `address5`, `address6`,'0.000000','0.000000','0','TVM','1','0','','','1','1','1','1','0','','','','','','','','',`cid`, `stateid`, `did`, `taluk`, `LSG_type`, `LSG_name`, `block_name`, `LSG_ward`, `village`
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
WHERE bio_oldorders.description LIKE '%xx%'";
    $result2 = DB_query($sql2,$db);   
      $sql_las="INSERT INTO `custbranch`(`branchcode`, `debtorno`, `brname`, `braddress1`, `braddress2`, `braddress3`, `braddress4`, `braddress5`, `braddress6`, `lat`, `lng`, `estdeliverydays`, `area`, `salesman`, `fwddate`, `phoneno`, `faxno`, `contactname`, `email`, `defaultlocation`, `taxgroupid`, `defaultshipvia`, `deliverblind`, `disabletrans`, `brpostaddr1`, `brpostaddr2`, `brpostaddr3`, `brpostaddr4`, `brpostaddr5`, `brpostaddr6`, `specialinstructions`, `custbranchcode`, `cid`, `stateid`, `did`, `taluk`, `LSG_type`, LSG_name, block_name, LSG_ward, village) SELECT shuffle2.branchcode, shuffle2.debtorno, shuffle2.brname, shuffle2.braddress1, shuffle2.braddress2, 
      shuffle2.braddress3, shuffle2.braddress4, shuffle2.braddress5, shuffle2.braddress6, shuffle2.lat, shuffle2.lng, shuffle2.estdeliverydays, shuffle2.area
      , shuffle2.salesman, shuffle2.fwddate, shuffle1.phoneno, shuffle1.faxno, shuffle2.contactname, shuffle2.email, shuffle2.defaultlocation, shuffle2.taxgroupid, 
      shuffle2.defaultshipvia, shuffle2.deliverblind, shuffle2.disabletrans, shuffle2.brpostaddr1, shuffle2.brpostaddr2, shuffle2.brpostaddr3, 
      shuffle2.brpostaddr4, shuffle2.brpostaddr5, shuffle2.brpostaddr6, shuffle2.specialinstructions, shuffle2.custbranchcode, shuffle2.cid, shuffle2.stateid, shuffle2.did, shuffle2.taluk, shuffle2.LSG_type, shuffle2.LSG_name, shuffle2.block_name, shuffle2.LSG_ward,
       shuffle2.village  FROM shuffle1 
      INNER JOIN shuffle2 ON shuffle2.id = shuffle1.id";
    $result_ins = DB_query($sql_las,$db); 
    
  }
  
 echo "<fieldset style='float:center;width:80%;overflow:scroll;'>";     
     echo "<legend><h3>Capacity/Yearly-REPORT</h3>";
     echo "</legend>";
   echo '<input type="submit" name="submit2">';
   echo "</form>";  
     
?>
