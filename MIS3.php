<?php
  $PageSecurity = 80;
 include ('includes/session.inc');
$title = _('MIS2');  
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
$tvm3=array();
$Ekm3=array();
$Kok3=array();
$Nati3=array();
$Inter3=array();
$allname3=array();

  while ($myrow = DB_fetch_array($result)) 
  {
      
$id=$myrow['id'];
 $allname[$id]=$myrow['leadsourcetype'];
 $tvm[$id]=0;
 $Ekm[$id]=0;
 $Kok[$id]=0;
 $Nati[$id]=0;
 $Inter[$id]=0;
  //$allname2[$id]=$myrow['leadsourcetype'];
 $tvm2[$id]=0;
 $Ekm2[$id]=0;
 $Kok2[$id]=0;
 $Nati2[$id]=0;
 $Inter2[$id]=0;
  $tvm3[$id]=0;
 $Ekm3[$id]=0;
 $Kok3[$id]=0;
 $Nati3[$id]=0;
 $Inter3[$id]=0;
 
 }


 
//not in sourcetypeid 16,15,14

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
        inner join bio_leadtask on (bio_leadtask.leadid= bio_leads.`leadid` ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
        
WHERE bio_leadtask.duedate <= '$createdate'  AND  bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
GROUP BY `bio_leadsourcetypes`.`id`";
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
          inner join bio_leadtask on (bio_leadtask.leadid= bio_leads.`leadid` ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE  bio_leadtask.duedate <= '$createdate '
AND  bio_cust.nationality=1 AND bio_cust.state=14 
AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE  bio_leadtask.duedate <= '$createdate ' 
 AND bio_cust.nationality=1 AND bio_cust.state=14
 AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_cust.nationality=1 AND bio_cust.state=14
 AND bio_cust.district IN (1,3,7,13) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_cust.nationality=1 
AND bio_leadtask.duedate <= '$createdate'
AND bio_cust.state=14 
AND bio_cust.district IN (1,3,7,13) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_cust.nationality=1 AND bio_cust.state=14 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
AND bio_cust.district IN (1,3,7,13) 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.duedate <= '$createdate ' 
 AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 

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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
 WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
       inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )  
WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.duedate <= '$createdate ' 
 AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
AND bio_cust.nationality=1 AND bio_cust.state !=14  

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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE  bio_leadtask.duedate <= '$createdate ' 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
AND bio_cust.nationality=1 AND bio_cust.state !=14  
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
 
 $id=15;
 $Nati[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
 AND bio_cust.nationality=1 AND bio_cust.state !=14  
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{

 $id=14;
 $Nati[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------


$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
 AND bio_cust.nationality !=1 

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{

 $id=$myrow['id'];
 $Inter[$id]=$myrow['counts'];
 //$i++;
}

//-------------Dealers


$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
 AND bio_cust.nationality !=1 
GROUP BY `custbranch`.`debtorno` ";

 $result=DB_query($sql, $db); 



 while($myrow=DB_fetch_array($result))
{
  
 $id=15;
 $Inter[$id]=$myrow['counts'];
 //$i++;
}


//-------------Biotech Staff
$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
      inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )   
WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
AND  bio_cust.nationality !=1 
GROUP BY `bio_emp`.`empid`";

 $result=DB_query($sql, $db); 



 while($myrow=DB_fetch_array($result))
{

 $id=14;
 $Inter[$id]=$myrow['counts'];

}
  
 



 $sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
        inner join bio_leadtask on (bio_leadtask.leadid= bio_leads.`leadid` ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
        
WHERE bio_leadtask.taskcompleteddate LIKE '$createdate %'
  AND  bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
GROUP BY `bio_leadsourcetypes`.`id`";
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
          inner join bio_leadtask on (bio_leadtask.leadid= bio_leads.`leadid` ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE  bio_leadtask.taskcompleteddate like '$createdate %'
AND  bio_cust.nationality=1 AND bio_cust.state=14 
AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
 AND bio_cust.nationality=1 AND bio_cust.state=14
 AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.nationality=1 AND bio_cust.state=14
 AND bio_cust.district IN (1,3,7,13) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_cust.nationality=1 
AND bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.state=14 
AND bio_cust.district IN (1,3,7,13) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.nationality=1 AND bio_cust.state=14 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_cust.district IN (1,3,7,13) 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
 AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1

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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
 WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
       inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )  
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1 
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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
 AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_cust.nationality=1 AND bio_cust.state !=14  

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
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_cust.nationality=1 AND bio_cust.state !=14  
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
 
 $id=15;
 $Nati2[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
 AND bio_cust.nationality=1 AND bio_cust.state !=14  
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{

 $id=14;
 $Nati2[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------


$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
 AND bio_cust.nationality !=1 

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{

 $id=$myrow['id'];
 $Inter2[$id]=$myrow['counts'];
 //$i++;
}

//-------------Dealers


$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
 AND bio_cust.nationality !=1 
GROUP BY `custbranch`.`debtorno` ";

 $result=DB_query($sql, $db); 



 while($myrow=DB_fetch_array($result))
{
  
 $id=15;
 $Inter2[$id]=$myrow['counts'];
 //$i++;
}


//-------------Biotech Staff
$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
      inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )   
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND  bio_cust.nationality !=1 
GROUP BY `bio_emp`.`empid`";

 $result=DB_query($sql, $db); 



 while($myrow=DB_fetch_array($result))
{

 $id=14;
 $Inter2[$id]=$myrow['counts'];

}
 

 
 $sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
        inner join bio_leadtask on (bio_leadtask.leadid= bio_leads.`leadid` ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
        
WHERE bio_leadtask.taskcompleteddate LIKE '$createdate %'
  AND  bio_cust.nationality=1 AND bio_cust.state=14 AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
GROUP BY `bio_leadsourcetypes`.`id`";
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $tvm3[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers
 
$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
          inner join bio_leadtask on (bio_leadtask.leadid= bio_leads.`leadid` ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE  bio_leadtask.taskcompleteddate like '$createdate %'
AND  bio_cust.nationality=1 AND bio_cust.state=14 
AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $tvm3[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
 AND bio_cust.nationality=1 AND bio_cust.state=14
 AND bio_cust.district IN (6,11,12) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1 
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $tvm3[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------//ALP-EKM-IDK-KTM-TRS---------

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `bio_leadsourcetypes`.`id`
    , `bio_leadsourcetypes`.`leadsourcetype`
FROM
    `bio_leads`
    inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `bio_leadsources` 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_leadsourcetypes` 
        ON (`bio_leadsources`.`sourcetypeid` = `bio_leadsourcetypes`.`id`)
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.nationality=1 AND bio_cust.state=14
 AND bio_cust.district IN (1,3,7,13) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1 
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Ekm3[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers


$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_cust.nationality=1 
AND bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.state=14 
AND bio_cust.district IN (1,3,7,13) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Ekm3[$id]=$myrow['counts'];
 //$i++;
}


//-------------Biotech Staff

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.nationality=1 AND bio_cust.state=14 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
AND bio_cust.district IN (1,3,7,13) 
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Ekm3[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
 AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Kok3[$id]=$myrow['counts'];
 //$i++;
}
//-------------Dealers

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
 WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=15;
 $Kok3[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
       inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )  
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_cust.nationality=1 
AND bio_cust.state=14 
AND bio_cust.district IN (4,5,8,9,10,14) 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1 
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=14;
 $Kok3[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
 AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
AND bio_cust.nationality=1 AND bio_cust.state !=14  

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
  // echo"<tr><td>".$myrow['leadsourcetype']."</td>";
  // echo"<td>".$myrow['counts']."</td></tr>";
 $id=$myrow['id'];
 $Nati3[$id]=$myrow['counts'];
 //$i++;
}




//-------------Dealers

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
AND bio_cust.nationality=1 AND bio_cust.state !=14  
GROUP BY `custbranch`.`debtorno` ";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{
 
 $id=15;
 $Nati3[$id]=$myrow['counts'];
 //$i++;
}

//-------------Biotech Staff

$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
     inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )    
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
 AND bio_cust.nationality=1 AND bio_cust.state !=14  
GROUP BY `bio_emp`.`empid`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{

 $id=14;
 $Nati3[$id]=$myrow['counts'];
 //$i++;
}

//////////////-------------KNR-KSR-KZH-MLP-PLK-WND---------


$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
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
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
 AND bio_cust.nationality !=1 

GROUP BY `bio_leadsourcetypes`.`id`";//
// echo $sql;
 $result=DB_query($sql, $db); 


//$i=0;
 while($myrow=DB_fetch_array($result))
{

 $id=$myrow['id'];
 $Inter3[$id]=$myrow['counts'];
 //$i++;
}

//-------------Dealers


$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts
    , `custbranch`.`debtorno`
    , `custbranch`.`brname`
FROM
    `bio_leads`
    INNER JOIN `custbranch` 
        ON (`bio_leads`.`sourceid` = `custbranch`.`debtorno`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
        inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
 AND bio_cust.nationality !=1 
GROUP BY `custbranch`.`debtorno` ";

 $result=DB_query($sql, $db); 



 while($myrow=DB_fetch_array($result))
{
  
 $id=15;
 $Inter3[$id]=$myrow['counts'];
 //$i++;
}


//-------------Biotech Staff
$sql=" SELECT 
    IFNULL( COUNT( DISTINCT (
`bio_leads`.`leadid`
) ) , 0 ) as counts,bio_leads.leaddate
    , `bio_emp`.`empid`
   
FROM
    `bio_leads`
    INNER JOIN `bio_emp` 
        ON (`bio_leads`.`sourceid` = `bio_emp`.`empid`)
    INNER JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
      inner join bio_leadtask on ( bio_leads.`leadid`=bio_leadtask.leadid )   
WHERE bio_leadtask.taskcompleteddate like '$createdate %'
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
AND  bio_cust.nationality !=1 
GROUP BY `bio_emp`.`empid`";

 $result=DB_query($sql, $db); 



 while($myrow=DB_fetch_array($result))
{

 $id=14;
 $Inter3[$id]=$myrow['counts'];

}
 ?>
<table width="94%" border="1">
  <tr>
    <th rowspan="2" scope="col">slno</th>
    <th rowspan="2" scope="col">Lead category</th>
    <th colspan="6" scope="col">Lead to contact</th>
    <th colspan="6" scope="col">Lead contacted</th>
    <th colspan="6" scope="col">Lead dropped</th>
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
  $total3=$tvm3[$id]+$Ekm3[$id]+$Kok3[$id]+$Nati3[$id]+$Inter3[$id];
  /*$tvmt=$tvm[$id]+$tvm2[$id];
  $ekmt=$Ekm[$id]+$Ekm2[$id];
  $kokt=$Kok[$id]+$Kok2[$id];
  $natit=$Nati[$id]+$Nati2[$id];
  $intert=$Inter[$id]+$Inter2[$id];
  $total3=$total+$total2;*/
 if($tvm[$id]==0){$tvm[$id]='';}if($Ekm[$id]==0){$Ekm[$id]='';}if($Kok[$id]==0){$Kok[$id]='';}if($Nati[$id]==0){$Nati[$id]='';}if($Inter[$id]==0){$Inter[$id]='';}
  if($total==0){$total='';}
    if($tvm2[$id]==0){$tvm2[$id]='';}if($Ekm2[$id]==0){$Ekm2[$id]='';}if($Kok2[$id]==0){$Kok2[$id]='';}if($Nati2[$id]==0){$Nati2[$id]='';}if($Inter2[$id]==0){$Inter2[$id]='';}
  if($total2==0){$total2='';}
   if($tvm3[$id]==0){$tvm3[$id]='';}if($Ekm3[$id]==0){$Ekm3[$id]='';}if($Kok3[$id]==0){$Kok3[$id]='';}if($Nati3[$id]==0){$Nati3[$id]='';}if($Inter3[$id]==0){$Inter3[$id]='';}
  if($total3==0){$total3='';}
  /* if($tvmt==0){$tvmt='';}if($ekmt==0){$ekmt='';}if($kokt==0){$kokt='';}if($natit==0){$natit='';}if($intert==0){$intert='';}
  if($total3==0){$total3='';}*/
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
   echo"<td>".$tvm3[$id]."</td>";
  echo"<td>".$Ekm3[$id]."</td>";
 echo"<td>". $Kok3[$id]."</td>";
 echo"<td>".$Nati3[$id]."</td>";
 echo"<td>".$Inter3[$id]."</td>";
  echo"<td>".$total3."</td>";
  /*  echo"<td>".$tvmt."</td>";
  echo"<td>".$ekmt."</td>";
 echo"<td>". $kokt."</td>";
 echo"<td>".$natit."</td>";
 echo"<td>".$intert."</td>";
    echo"<td>".$total3."</td>";*/
 $s++;
 
 echo "</tr>";
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
 $stvmt=$stvmt+$tvm3[$id];
 $sekmt=$sekmt+$Ekm3[$id];
 $skokt=$skokt+$Kok3[$id];
 $snatt=$snatt+$Nati3[$id];
 $sintt=$sintt+$Inter3[$id];
 $stot3=$stot3+$total3;
 
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

