<?php
  $PageSecurity = 80;
 include ('includes/session.inc');
$title = _('District Report');  
include('includes/header.inc'); 
   
     echo '<form id="form1" name="form1" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
 ?>
  <fieldset><legend>Date searching</legend><table>
<tr><td><b>Enter date</b></td><td>

<?php
if($_POST['submit'])
{
$year= $_POST['year'];
} 
else
{ 
  $year=date ('Y');
 
}   

    echo "<select name='year'>";
    if($_POST['year'])
    {
        echo "<option selected value=".$year.">".$year."</option>";
    }
    echo "<option value=2013>2013</option><option value=2007>2007</option><option value=2008>2008</option><option value=2009>2009</option>
    <option value=2010>2010</option><option value=2011>2011</option><option value=2012>2012</option>
    <option value=2014>2014</option><option value=2015>2015</option></select>"; 
         ?>
                 </td><td><input type="submit" name="submit"/></td> </table> </fieldset>
                 <?php
    echo '</td></tr></table></fieldset>';


 echo "<fieldset style='float:center;width:80%;overflow:scroll;'>";     
     echo "<legend><h3>Capacity/Yearly-REPORT</h3>";
     echo "</legend>";
 $a=array("0.5","0.75","1","1.5","2","3","4","6","8","10","15","25","50","100","200"); 
 $z=array("Trivandrum","Kollam","Pathanamthitta","Alappuzha","Kottayam","Idukki","Ernakulam","Thrissur","Malappuram","Palakkaad","Kozhikode","Wayanad","Kannur","Kasargod"); 
 $b= array("12","6","11","1","7","3","2","13","9","10","8","14","4","5");
 $c=array("25","50","100","200","300","500");
  
  
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
<table width="94%" height="416" border="1">
  <tr>
    <th rowspan="2" scope="col">slno</th>
    <th rowspan="2" scope="col">District</th>
    <th colspan="8" scope="col">Domestic - Cooking</th>
    <th colspan="7" scope="col">Institution - Cooking</th>
    <th colspan="6" scope="col">Institution - Electricity</th>
     <th colspan="4" scope="col">BASELINE</th>
  </tr>
  <tr bgcolor="#9D8787" style="font-weight: bold;">
   <td width="6%">0.5</td>
      <td width="6%">0.75</td>
    <td width="6%">1</td>
        <td width="6%">1.5</td>
    <td width="6%">2</td>
    <td width="6%">3</td>
    <td width="6%">4</td>
    <td width="6%">6</td>
    <td width="6%">8</td>
    <td width="6%">10</td>
    <td width="6%">15</td>
    <td width="6%">25</td>
    <td width="6%">50</td>
    <td width="6%">100</td>
    <td width="6%">200</td>
    <td width="6%">25</td>
    <td width="6%">50</td>
    <td width="6%">100</td>
    <td width="6%">200</td>
    <td width="6%">300</td>
    <td width="6%">500</td>
      <td width="6%">SUM</td>
        <td width="6%">WOD</td>
              <td width="6%">LPG</td>
                 <td width="6%">ELE</td>
  </tr><tr bgcolor="#CC99CC">
    <?php
    $p=1;
    $k=1;
     $fs=0;
    $ls=0;
    $gs=0;
      for($j=0;$j<14;$j++)

  {
      $t=0;
      echo "<td>".$k."</td>";
      echo "<td bgcolor='#9D8787' style='font-weight: bold;'>".$z[$j]." </td>";
      
         $s=0;
       for($i=0;$i<15;$i++) 
      {      
        $sql="select count(debtorsmaster.debtorno),bio_installation_status.installed_date
,stockitemproperty.model,stockitemproperty.capacity,orderplant.stkcode as 'stockid'
from salesorders 
inner join  custbranch on salesorders.debtorno=custbranch.debtorno
left join bio_installation_status on salesorders.orderno=bio_installation_status.orderno
left join debtorsmaster on salesorders.debtorno=debtorsmaster.debtorno
left join orderplant on salesorders.orderno=orderplant.orderno
left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
 where salesorders.debtorno not like 'W%' AND stockitemproperty.capacity='".$a[$i]."' AND bio_installation_status.installed_date between '".$year."-1-1' and '".$year."-12-31'AND `custbranch`.`stateid`=14 AND `custbranch`.`cid`=1  AND `custbranch`.`did`='".$b[$j]."'";
 $result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$sql1="SELECT  count(`bio_oldorders`.`debtorno`), `bio_oldorders`.`installationdate`,stockitemproperty.model, stockitemproperty.capacity,bio_oldorders.plantid as 'stockid'
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
inner join  custbranch on bio_oldorders.debtorno=custbranch.debtorno
left JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid
where bio_oldorders.debtorno not like 'W%' AND stockitemproperty.capacity ='".$a[$i]."'
AND bio_oldorders.installationdate between '".$year."-1-1' and '".$year."-12-31' 
AND `custbranch`.`stateid`=14 AND `custbranch`.`cid`=1  AND `custbranch`.`did`='".$b[$j]."' "; 

      $result1=DB_query($sql1,$db);
$myrow1=DB_fetch_array($result1);   
 $last[$i][$j]=$myrow[0]+$myrow1[0]; 
 $n=$last[$i][$j];
   $s=$s+$n;
 if($n==0)
 {
     $n='';
 }
echo "<td>".$n."</td>";
         //  $t=$n+$t;
    }
    for($q=0;$q<6;$q++)
    {
       $sql5="select count(debtorsmaster.debtorno),bio_installation_status.installed_date
,stockitemproperty.model,stockitemproperty.capacity,orderplant.stkcode as 'stockid'
from salesorders 
inner join  custbranch on salesorders.debtorno=custbranch.debtorno
left join bio_installation_status on salesorders.orderno=bio_installation_status.orderno
left join debtorsmaster on salesorders.debtorno=debtorsmaster.debtorno
left join orderplant on salesorders.orderno=orderplant.orderno
left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
 where salesorders.debtorno  like 'W%' AND stockitemproperty.capacity='".$c[$q]."' AND bio_installation_status.installed_date between '".$year."-1-1' and '".$year."-12-31' 
 AND `custbranch`.`stateid`=14 AND `custbranch`.`cid`=1  AND `custbranch`.`did`='".$b[$j]."'";
 $result5=DB_query($sql5,$db);
$myrow5=DB_fetch_array($result5);
$sql6="SELECT  count(`bio_oldorders`.`debtorno`), `bio_oldorders`.`installationdate`,stockitemproperty.model, stockitemproperty.capacity,bio_oldorders.plantid as 'stockid'
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
inner join  custbranch on bio_oldorders.debtorno=custbranch.debtorno
left JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid
where bio_oldorders.debtorno  like 'W%' AND stockitemproperty.capacity ='".$c[$q]."'
AND bio_oldorders.installationdate between '".$year."-1-1' and '".$year."-12-31' 
AND `custbranch`.`stateid`=14 AND `custbranch`.`cid`=1  AND `custbranch`.`did`='".$b[$j]."'"; 

      $result6=DB_query($sql6,$db);
$myrow6=DB_fetch_array($result6);  
 
 $last1[$q][$j]=$myrow5[0]+$myrow6[0]; 
 $h=$last1[$q][$j];
 if($h==0)
 {
     $h='';
 }
echo "<td>".$h."</td>";
$s=$s+$h;
   
    }
     
     
     
      $k++;
       if($s==0)
      {
          $s='';
      }
      
   echo "<td>".$s."</td>";
   
    $sql_b="select count(debtorsmaster.debtorno),count(bio_cdmbase.firewood) as fire,count(bio_cdmbase.lpg) as lpg,count(bio_cdmbase.grid) as grid,bio_installation_status.installed_date
,stockitemproperty.model,stockitemproperty.capacity,orderplant.stkcode as 'stockid'
from salesorders 
inner join  custbranch on salesorders.debtorno=custbranch.debtorno
left join bio_installation_status on salesorders.orderno=bio_installation_status.orderno
left join debtorsmaster on salesorders.debtorno=debtorsmaster.debtorno
left join orderplant on salesorders.orderno=orderplant.orderno
left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
inner join bio_cdmbase on salesorders.debtorno=bio_cdmbase.debtorno
 where salesorders.debtorno not like 'W%'  AND bio_installation_status.installed_date between '".$year."-1-1' and '".$year."-12-31' 
 AND `custbranch`.`stateid`=14 AND `custbranch`.`cid`=1  AND `custbranch`.`did`='".$b[$j]."'";
 $result_b=DB_query($sql_b,$db);
$myrow_b=DB_fetch_array($result_b);
$sql_b2="SELECT  count(`bio_oldorders`.`debtorno`),count(bio_cdmbase.firewood) as fire,count(bio_cdmbase.lpg) as lpg,count(bio_cdmbase.grid) as grid, `bio_oldorders`.`installationdate`,stockitemproperty.model, stockitemproperty.capacity,bio_oldorders.plantid as 'stockid'
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
inner join  custbranch on bio_oldorders.debtorno=custbranch.debtorno
left JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid
inner join bio_cdmbase on `bio_oldorders`.`debtorno`=bio_cdmbase.debtorno
where bio_oldorders.debtorno not like 'W%' 
AND bio_oldorders.installationdate between '".$year."-1-1' and '".$year."-12-31' 
AND `custbranch`.`stateid`=14 AND `custbranch`.`cid`=1  AND `custbranch`.`did`='".$b[$j]."'"; 

      $result_b2=DB_query($sql_b2,$db);
$myrow_b2=DB_fetch_array($result_b2);  
$fire=$myrow_b['fire']+$myrow_b2['fire'];
 $lpg=$myrow_b['lpg']+$myrow_b2['lpg'];
$grid=$myrow_b['grid']+$myrow_b2['grid'];
$fs=$fs+$fire;
$ls=$ls+$lpg;
$gs=$gs+$grid;
if($fire==0)
{
    $fire='';
}
if($lpg==0)
{
    $lpg='';
}
if($grid==0)
{
    $grid='';
}

 echo "<td>".$fire."</td>";
  echo "<td>".$lpg."</td>";
   echo "<td>".$grid."</td>";
 echo "</tr>";
  if ($p==1)
             {
               echo '<tr class="EvenTableRows">';
                $p=0;
             }else 
              {
               echo '<tr bgcolor="#CC99CC">';
               $p=1;     
               }
  

//echo "<td>".$i."</td></tr><tr>";
  }
  $k=0; 
  
  echo '<tr bgcolor="#CC99CC"><td>15</td><td>SUM</td>'; 
  
  for($j=0;$j<15;$j++)
  {
      $t=0;
      for($i=0;$i<14;$i++)
      {
           $last[$j][$i];
          $t=$t+$last[$j][$i];
      }
      if($t==0)
      {
          $t='';
      }
      echo "<td>".$t."</td>";
      $k=$k+$t;
  }
    for($p=0;$p<6;$p++)
  {

      $t2=0;
      for($m=0;$m<14;$m++)
      {
          $f= $last1[$p][$m]."<br>";
         $t2=$t2+$f;
      }
     // echo $t2."<br>";
      //
     if($t2==0)
      {
          $t2='';
      }
      $k=$k+$t2;
      echo "<td>".$t2."</td>";
  }
      echo "<td>".$k."</td>";
    echo "<td>".$fs."</td>";
      echo "<td>".$ls."</td>";
       echo "<td>".$gs."</td>";

/* for($j=0;$j<15;$j++)
 {
     for($i=0;$i<8;$i++)
     {
      $summe=$summe+ $last[$i][$j] ;
      
     }
      echo $summe."<br>";
 }
 echo $summe;*/
 echo "</tr>";
 
   //echo'<tr bgcolor="#CC99CC"><td>'.$t.'</td></tr>';
  
?>
    
</table>

