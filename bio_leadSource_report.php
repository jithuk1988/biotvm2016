<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('lead Source report');  
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

  while ($myrow = DB_fetch_array($result)) 
  {
      
 $id=$myrow['id'];
 $allname[$id]=$myrow['leadsourcetype'];
 $tvm[$id]=0;
 $Ekm[$id]=0;
 $Kok[$id]=0;
 $Nati[$id]=0;
 $Inter[$id]=0;
 
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
























 $sql="SELECT * FROM `bio_leadsourcetypes` where rowstatus!=1";//
  $result=DB_query($sql,$db); 

  echo '<table align="center"  width="80%">
<tr ><th align="center" colspan="6" ><h2>lead Source report</h2></th></tr>
<tr >
<th >Source</th><th>Tvm</th><th>Ekm</th><th>Kok</th><th>National</th><th>International</th></tr>

';
$k=0;  $total=0;$dis=0;
  while ($myrow = DB_fetch_array($result)) 
  {
  if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  
      
      
      $id=$myrow['id'];
    
      $total=$tvm[$id]+$Ekm[$id]+$Kok[$id]+$Nati[$id]+$Inter[$id];
      if($total>0)
      {
echo"<td>".$allname[$id]."</td>";
 echo"<td>".$tvm[$id]."</td>";
  echo"<td>".$Ekm[$id]."</td>";
 echo"<td>". $Kok[$id]."</td>";
 echo"<td>".$Nati[$id]."</td>";
 echo"<td>".$Inter[$id]."</td>";
 $dis=1;
      }

  }
  if($dis==0)
  {
    prnMsg(_('This date no lead register'),'warn');
  }

echo '
</tr>
</table>';
 
 
 
 
 
?>

