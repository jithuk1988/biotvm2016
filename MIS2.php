<?php
  $PageSecurity = 80;
 include ('includes/session.inc');
$title = _('Leadsource Report');  
include('includes/header.inc'); 
    if($_POST['submit'])
  {
     $create=$_POST['caldate'];
     $createdate=FormatDateForSQL($create);
  
  }    else
  {
      $createdate=date("Y-m-d"); 
  }
    //echo '<table>';
 echo '<form id="form1" name="form1" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
 echo '<fieldset><legend>Date searching</legend>
 <table  align="center"  width="70%">
<tr><td><b>Enter date</b></td><td>';
if($_POST['submit'])
{
    echo '<input  style="width:170px" type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.$_POST['caldate'].'>'; 
}     else
{
 echo '<input  style="width:170px" type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.date("d/m/Y").'>'; }
 echo'</td><td><input type="submit" name="submit"/></td> </table> </fieldset>';
 
  
 
$sql="SELECT * FROM `bio_leadsourcetypes` where rowstatus!=1";//
  $result=DB_query($sql,$db); 
$tvm=array();
$Ekm=array();
$Kok=array();
$Nati=array();
$Inter=array();
$allname=array();
$tvm2=array();
$Ekm2=array();
$Kok2=array();
$Nati2=array();
$Inter2=array();
$allname2=array();

  while ($myrow = DB_fetch_array($result)) 
  {
      
 $id=$myrow['id'];
 $allname[$id]=$myrow['leadsourcetype'];
 $tvm[$id]=0;
 $Ekm[$id]=0;
 $Kok[$id]=0;
 $Nati[$id]=0;
 $Inter[$id]=0;
  $allname2[$id]=$myrow['leadsourcetype'];
 $tvm2[$id]=0;
 $Ekm2[$id]=0;
 $Kok2[$id]=0;
 $Nati2[$id]=0;
 $Inter2[$id]=0;
 
 }
 $sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) and bio_leads.leaddate != '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $tvm2[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) and bio_leads.leaddate != '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $tvm2[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) and bio_leads.leaddate != '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $tvm2[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------//ALP-EKM-IDK-KTM-TRS---------
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (1,3,7,13) and bio_leads.leaddate != '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Ekm2[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (1,3,7,13) and bio_leads.leaddate != '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Ekm2[$id]=$myrow['counts'];
 //$i++;
}


//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (1,3,7,13) and bio_leads.leaddate != '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Ekm2[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------

$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (4,5,8,9,10,14) and bio_leads.leaddate != '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Kok2[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (4,5,8,9,10,14) and bio_leads.leaddate != '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Kok2[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (4,5,8,9,10,14) and bio_leads.leaddate != '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Kok2[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality=1 AND bio_cust.state !=14  and bio_leads.leaddate != '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Nati2[$id]=$myrow['counts'];
 //$i++;
}




//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state !=14  and bio_leads.leaddate != '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Nati2[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state !=14  and bio_leads.leaddate != '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Nati2[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality !=1 and bio_leads.leaddate != '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Inter2[$id]=$myrow['counts'];
 //$i++;
}

//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality !=1 and bio_leads.leaddate != '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Inter2[$id]=$myrow['counts'];
 //$i++;
}


//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality !=1 and bio_leads.leaddate != '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Inter2[$id]=$myrow['counts'];
 //$i++;
}

 
//not in sourcetypeid 16,15,14
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) and bio_leads.leaddate like '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $tvm[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) and bio_leads.leaddate like '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $tvm[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) and bio_leads.leaddate like '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $tvm[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------//ALP-EKM-IDK-KTM-TRS---------
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (1,3,7,13) and bio_leads.leaddate like '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Ekm[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (1,3,7,13) and bio_leads.leaddate like '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Ekm[$id]=$myrow['counts'];
 //$i++;
}


//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (1,3,7,13) and bio_leads.leaddate like '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Ekm[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------

$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (4,5,8,9,10,14) and bio_leads.leaddate like '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Kok[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (4,5,8,9,10,14) and bio_leads.leaddate like '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Kok[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (4,5,8,9,10,14) and bio_leads.leaddate like '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Kok[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality=1 AND bio_cust.state !=14  and bio_leads.leaddate like '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Nati[$id]=$myrow['counts'];
 //$i++;
}




//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state !=14  and bio_leads.leaddate like '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Nati[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality=1 AND bio_cust.state !=14  and bio_leads.leaddate like '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Nati[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_cust.nationality !=1 and bio_leads.leaddate like '$createdate'

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Inter[$id]=$myrow['counts'];
 //$i++;
}

//-------------Dealers
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality !=1 and bio_leads.leaddate like '$createdate'
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Inter[$id]=$myrow['counts'];
 //$i++;
}


//-------------Biotech Staff
$sql=" SELECT 
    IFNULL(COUNT(`bio_leads`.`leadid`),0) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_cust.nationality !=1 and bio_leads.leaddate like '$createdate'
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Inter[$id]=$myrow['counts'];
 //$i++;
}
  
  
 /*
     $sql="select count(debtorsmaster.debtorno),bio_installation_status.installed_date
,stockitemproperty.model,stockitemproperty.capacity,orderplant.stkcode as 'stockid'
from salesorders 
left join bio_installation_status on salesorders.orderno=bio_installation_status.orderno
left join debtorsmaster on salesorders.debtorno=debtorsmaster.debtorno
left join orderplant on salesorders.orderno=orderplant.orderno
left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
 where salesorders.debtorno not like '0000' AND stockitemproperty.capacity=1 AND bio_installation_status.installed_date between '2012-1-1' and '2013-1-1'
union 
SELECT  count(`bio_oldorders`.`debtorno`), `bio_oldorders`.`installationdate`,stockitemproperty.model, stockitemproperty.capacity,bio_oldorders.plantid as 'stockid'
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )

left JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid
where bio_oldorders.debtorno not like '00000' AND stockitemproperty.capacity =1
AND bio_oldorders.installationdate between '2012-1-1' and '2013-1-1'
";*/
?>
<table width="94%" border="1">
  <tr>
    <th rowspan="2" scope="col">slno</th>
    <th rowspan="2" scope="col">Lead category</th>
    <th colspan="6" scope="col">Lead registered</th>
    <th colspan="6" scope="col">Existing lead</th>
    <th colspan="6" scope="col">Cumilative lead</th>
  </tr>
  <tr bgcolor="#9D8787" style="font-weight: bold;">
  <?php
  for($a=0;$a<3;$a++)
  {?>
   <td width="6%">TVM</td>
      <td width="6%">EKM</td>
    <td width="6%">KOK</td>
        <td width="6%">NAT</td>
    <td width="6%">INTL</td>
    <td width="6%">TOTAL</td>
  <?php } ?> 
  </tr><tr bgcolor="#CC99CC">
    <?php
    $sql="SELECT * FROM `bio_leadsourcetypes` where rowstatus!=1";
      $result=DB_query($sql,$db);
      $s=0; 
      $k=0;
      $stvm=0;
 $sekm=0;
 $skok=0;
 $snat=0;
 $sint=0;
 $stot=0;
  $stvm2=0;
 $sekm2=0;
 $skok2=0;
 $snat2=0;
 $sint2=0;
 $stot2=0;
 $stvmt=0;
 $sekmt=0;
 $skokt=0;
 $snatt=0;
 $sintt=0;
 $stot3=0;
     while ($myrow = DB_fetch_array($result)) 
  {
      
 $id=$myrow['id'];
 $allname=$myrow['leadsourcetype'];
 $total=$tvm[$id]+$Ekm[$id]+$Kok[$id]+$Nati[$id]+$Inter[$id];
  $total2=$tvm2[$id]+$Ekm2[$id]+$Kok2[$id]+$Nati2[$id]+$Inter2[$id];
  $tvmt=$tvm[$id]+$tvm2[$id];
  $ekmt=$Ekm[$id]+$Ekm2[$id];
  $kokt=$Kok[$id]+$Kok2[$id];
  $natit=$Nati[$id]+$Nati2[$id];
  $intert=$Inter[$id]+$Inter2[$id];
  $total3=$total+$total2;
  if($tvm[$id]==0){$tvm[$id]='';}if($Ekm[$id]==0){$Ekm[$id]='';}if($Kok[$id]==0){$Kok[$id]='';}if($Nati[$id]==0){$Nati[$id]='';}if($Inter[$id]==0){$Inter[$id]='';}
  if($total==0){$total='';}
    if($tvm2[$id]==0){$tvm2[$id]='';}if($Ekm2[$id]==0){$Ekm2[$id]='';}if($Kok2[$id]==0){$Kok2[$id]='';}if($Nati2[$id]==0){$Nati2[$id]='';}if($Inter2[$id]==0){$Inter2[$id]='';}
  if($total2==0){$total2='';}
   if($tvmt==0){$tvmt='';}if($ekmt==0){$ekmt='';}if($kokt==0){$kokt='';}if($natit==0){$natit='';}if($intert==0){$intert='';}
  if($total3==0){$total3='';}
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  
 echo "<td>".$s."</td>";
 echo"<td>".$allname."</td>";
 echo"<td>".$tvm[$id]."</td>";
  echo"<td>".$Ekm[$id]."</td>";
 echo"<td>". $Kok[$id]."</td>";
 echo"<td>".$Nati[$id]."</td>";
 echo"<td>".$Inter[$id]."</td>";
  echo"<td>".$total."</td>";
   echo"<td>".$tvm2[$id]."</td>";
  echo"<td>".$Ekm2[$id]."</td>";
 echo"<td>". $Kok2[$id]."</td>";
 echo"<td>".$Nati2[$id]."</td>";
 echo"<td>".$Inter2[$id]."</td>";
   echo"<td>".$total2."</td>";
   echo"<td>".$tvmt."</td>";
  echo"<td>".$ekmt."</td>";
 echo"<td>". $kokt."</td>";
 echo"<td>".$natit."</td>";
 echo"<td>".$intert."</td>";
    echo"<td>".$total3."</td>";
 $s++;
 $stvm=$stvm+$tvm[$id];
 $sekm=$sekm+$Ekm[$id];
 $skok=$skok+$Kok[$id];
 $snat=$snat+$Nati[$id];
 $sint=$sint+$Inter[$id];
 $stot=$stot+$total;
  $stvm2=$stvm2+$tvm2[$id];
 $sekm2=$sekm2+$Ekm2[$id];
 $skok2=$skok2+$Kok2[$id];
 $snat2=$snat2+$Nati2[$id];
 $sint2=$sint2+$Inter2[$id];
 $stot2=$stot2+$total2;
 $stvmt=$stvmt+$tvmt;
 $sekmt=$sekmt+$ekmt;
 $skokt=$skokt+$kokt;
 $snatt=$snatt+$natit;
 $sintt=$sintt+$intert;
 $stot3=$stot3+$total3;
 echo "</tr>";
 
  }
  
  echo '<tr><td>'.($s+1).'</td>';
  echo '<td>Total</td>';
  echo "<td>$stvm</td>";
  echo "<td>$sekm</td>";
   echo "<td>$skok</td>";
    echo "<td>$snat</td>";
     echo "<td>$sint</td>";
      echo "<td>$stot</td>";
       echo "<td>$stvm2</td>";
        echo "<td>$sekm2</td>";
         echo "<td>$skok2</td>";
          echo "<td>$snat2</td>";
           echo "<td>$sint2</td>";
            echo "<td>$stot2</td>";
             echo "<td>$stvmt</td>";
              echo "<td>$sekmt</td>";
                  echo "<td>$skokt</td>";
                      echo "<td>$snatt</td>";
                          echo "<td>$sintt</td>";
                           echo "<td>$stot3</td></tr>";
  
    

 
  
?>
    
</table>

