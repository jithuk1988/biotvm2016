<?php
/*$PageSecurity = 80;
include('includes/session.inc');
$title = _('Leads Report');  
include('includes/header.inc');
//$office=$_SESSION['officeid'];   
    $createdate=date("Y-m-d");
  echo "<legend><h3>Previous Incentives</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";

     echo '<tr>
     <td style="border-bottom:1px solid #000000">Name</td> 
     <td style="border-bottom:1px solid #000000">office</td>  
                <td style="border-bottom:1px solid #000000">month</td>
                <td style="border-bottom:1px solid #000000">year</td>
                <td style="border-bottom:1px solid #000000">ordervalue</td>
                <td style="border-bottom:1px solid #000000">achivement%</td>
                <td style="border-bottom:1px solid #000000">target</td>
                <td style="border-bottom:1px solid #000000">eligible%</td>
                <td style="border-bottom:1px solid #000000">incentive</td>
                </tr>';
                $sql_iinnce="SELECT c.empname,c.offid,a.month,a.year,a.ordervalue,a.achivement_p,a.target,a.eligible_p,a.incentive FROM bio_incentive a,bio_emp c,bio_teammembers b WHERE a.teamid=b.teamid AND b.empid=c.empid AND a.task=71  AND a.month=7 ORDER BY month DESC ";
                

       
       
              //$sql_iinnce="SELECT teamid,month,year,ordervalue,achivement_p,target,eligible_P,incentive FROM bio_incentive ORDER BY month DESC"; 
              $result6=DB_query($sql_iinnce,$db); 
              while($row=DB_fetch_array($result6)){
                $sqloff=" SELECT `office` FROM `bio_office` WHERE `id`=".$row['offid'];
                  $resultoff=DB_query($sqloff,$db);
                  $rowoff=DB_fetch_array($resultoff) ;
                printf("<tr style='background:#A8A4DB'>
            <td cellpading=2 width='150px'>%s</td>
              <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>          
                        </tr>",
        $row['empname'],
        $rowoff['office'],
        date("F", mktime(0, 0, 0, $row['month'], 10)),
        $row['year'],
        $row['ordervalue'],
        $row['achivement_p'],
        $row['target'],
        $row['eligible_p'],
        $row['incentive']
        ); 

           }          */
$PageSecurity = 80;  
include('includes/session.inc');
$title = _('Incentive Calculation');  
include('includes/header.inc'); 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.style6 {color: #FF3399}
.style7 {color: #FF0000}
.style8 {color: #FF9933}
.style9 {color: #FF00CC}
.style10 {color: #CC0099}
-->
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">



<?php
$year =date('Y');
 $month=date('m');
 $period=$year."-".$month ;

if($_POST['submit'])
{
    $office=$_POST['office'];
    $year=$_POST['year'];
    $month=$_POST['month'];
   $period=$year."-".$month ; 
}
 $monthName = date("F", mktime(0, 0, 0, $month, 10));

 
  echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";     
     echo "<legend><h3>OFFICE INCENTIVE REPORT</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";
     echo "<tr><td>Office</td>";
     $sql="SELECT * FROM `bio_office` where id in(2,3,4) ";
    
     echo "<td><select name='office'>";
      $result=DB_query($sql,$db);
    //  echo "<option value='all'>ALL</option>";
   echo "<option selected value=100>All</option>";
      while($row=DB_fetch_array($result))
      {
           if($_POST['office']==$row['id'])
           {
             echo "<option selected value='".$row['id']."'>".$row['office']."</option>";  
           }else
           {
               
         echo "<option value='".$row['id']."'>".$row['office']."</option>";
           }
         
      }
     echo "</select></td><td>Month</td>";
     echo '<td><select name="month" id="close" class style="width:146px" >';
echo '<option value=""></option>';
echo '<option value="1">Jan</option>';
echo '<option value="2">Feb</option>';
echo '<option value="3">Mar</option>';
echo '<option value="4">April</option>';
echo '<option value="5">May</option>';
echo '<option value="6">June</option>';
echo '<option value="7">July</option>';
echo '<option value="8">Aug</option>';
echo '<option value="9">Sept</option>';
echo '<option value="10">Oct</option>';
echo '<option value="11">Nov</option>';
echo '<option value="12">Dec</option>';
echo '</select></td>';
echo "<td>Year</td><td><input type=text name=year value='".$_POST['year']."' />";
echo'</td><td><input type="submit" name="submit" id="submit" value="search" /></td></tr>';
echo "</table>";
echo "</fieldset>";   
    
?>
<table width="79%" height="228" border="1">
  <tr>
  <td height="63" colspan="9"><h2 align="center"><strong>Incentive  for the month of <?php echo "".$monthName ."&nbsp;". $year;  ?> </strong></h2>
    <h2 align="center">&nbsp;</h2>  </td>
 </tr>
    <tr>
      <th><strong><center>No</center></strong><label><strong><center></center></strong></label><label><strong></strong></label></th>
      <th width="13%"><strong>Office  name</strong></th>
      <th width="13%"><strong>Staff  name</strong>
      <label><strong><center></center></strong></label></th>
      <th width="11%"><strong><center>
        <strong>Lead  incentive</strong>
      </center></strong><label><strong><center></center></strong></label><label><strong></strong></label></th>
      <th width="10%"><strong>Order  incentive</strong>
      <label><strong><center></center></strong></label></th>
      <th width="11%"><strong><center>
        <strong>Collect  incentive</strong>
      </center></strong>
        <label><strong><center></center></strong></label><label><strong></strong></label></th>
      <th width="12%"><strong><center>
        <strong>AMC  incentive</strong>
      </center></strong>
        <label><strong><center></center></strong></label>
        <label><strong><center></center></strong></label><label><strong></strong></label></th>
      <th width="12%"><strong>TOTAL  incentive</strong></th>
      <th width="14%"><strong>Signature</strong></th>
    </tr>
  <?php
/*$sql="SELECT bio_office.`bio_office`, 
    bio_leadteams.teamname, 
    bio_leadteams.teamid
FROM `bio_office` inner join bio_leadteams on ( bio_leadteams.office_id=bio_office.`id`)
where bio_office.id in(4) 
order by office desc ,teamname asc ";*/ 
$sql=" SELECT bio_office.office,
            www_users.`userid`,
            bio_teammembers.teamid,
            bio_emp.designationid,
            bio_emp.empid
 FROM www_users 
inner join  bio_emp on (www_users.`empid`= bio_emp.empid )
inner join  bio_office on (bio_office.id = bio_emp.offid)
inner join bio_teammembers on (bio_teammembers.empid = bio_emp.empid)
WHERE  bio_emp.`deptid` = 2 AND  bio_emp.`offid` IN (4,2,3) ";
$leadsum=0;
$ordersum=0;
if($_POST['office'])
{
    
if($_POST['office']!=100)
{
    $sql.= "AND bio_emp.`offid` IN ($office)";
}
}
          

 
// echo $sql;
$result=DB_query($sql,$db);  

$slno=1;
while($row=DB_fetch_array($result)){
    
   $sql_k="SELECT SUM(stockitemproperties.value * salesorderdetails.quantity) 
    FROM salesorderdetails,stockitemproperties 
    WHERE stockitemproperties.stockid=salesorderdetails.stkcode 
    AND stockitemproperties.stkcatpropid=48 
    AND salesorderdetails.orderno IN (SELECT orderno FROM salesorders WHERE leadid IN (SELECT DISTINCT bio_leadtask.leadid
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid='".$row['teamid']."'
AND bio_leadtask.viewstatus=1
AND bio_leads.enqtypeid=1
AND bio_leads.leadstatus NOT IN (20)
AND bio_leads.leadid=bio_leadtask.leadid  )
 AND orddate between '$period-01' AND'$period-31' 

) GROUP BY stockitemproperties.stkcatpropid";
//AND orddate between '2010-01-01' AND'2013-08-31'  

//AND bio_leads.enqtypeid=2
//AND orddate between '2012-08-01' AND'2013-08-31' 
//AND bio_leads.leadstatus IN (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
   
   /* SELECT MAX(closedate) FROM bio_closingdate WHERE type=2*/
/*month( closedate ) ='".$_POST['close']."' AND year( closedate ) = '".$_POST['closeyear']."*/
   /*(SELECT closedate FROM bio_closingdate WHERE month( closedate ) =".$cl." AND year( closedate ) = '".$year."')
)*/
//echo $sql_k;
     $result_k=DB_query($sql_k,$db);
      $my_row_k=DB_fetch_array($result_k);  
       
       
       
       
       
       ////GET INCENTIVE POLICY domestic//////
       
       
      $sql_ip="SELECT incentive FROM bio_achievementpolicy 
      WHERE designation='".$row['designationid']."'
      AND enquiry_type=1 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' AND taskid=71
      AND taskid=71
      and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy
       WHERE designation='".$row['designationid']."'
      AND enquiry_type=1 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' and taskid=71 group by taskid)";
     
          $result_ip=DB_query($sql_ip,$db); 
          $row_ip=DB_fetch_array($result_ip);

          ///CALCULATE INCENTIVE for domestic
          
          $incentive1=($my_row_k[0]*$row_ip['incentive']); 

          
          $sql_k2="SELECT SUM(stockitemproperties.value * salesorderdetails.quantity) 
    FROM salesorderdetails,stockitemproperties 
    WHERE stockitemproperties.stockid=salesorderdetails.stkcode 
    AND stockitemproperties.stkcatpropid=48 
    AND salesorderdetails.orderno IN (SELECT orderno FROM salesorders WHERE leadid IN (SELECT DISTINCT bio_leadtask.leadid
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid='".$row['teamid']."'
AND bio_leadtask.viewstatus=1
AND bio_leads.enqtypeid=2
AND bio_leads.leadstatus NOT IN (20)
AND bio_leads.leadid=bio_leadtask.leadid  )
AND orddate between '$period-01' AND'$period-31'  

) GROUP BY stockitemproperties.stkcatpropid";
 

         $result_k2=DB_query($sql_k2,$db);
      $my_row_k2=DB_fetch_array($result_k2);      
           
             $sql_ip2="SELECT incentive FROM bio_achievementpolicy 
      WHERE designation='".$row['designationid']."'
      AND enquiry_type=2 AND rangefrom < '".$my_row_k2[0]."' AND rangeto >= '".$my_row_k2[0]."' AND taskid=71
      and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy
       WHERE designation='".$row['designationid']."'
      AND enquiry_type=2 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' AND taskid=71 group by taskid)
      ";
    
          $result_ip2=DB_query($sql_ip2,$db); 
          $row_ip2=DB_fetch_array($result_ip2);

          
          ///CALCULATE INCENTIVE
          
        $incentive2=($my_row_k2[0]*$row_ip2['incentive']); 

       $totalincentive=$incentive1+ $incentive2;
       

       
       
       
       

 
 
 $sqllead1="SELECT count(`leadid`)  FROM `bio_leads` WHERE `created_by`='".$row['userid']."'and enqtypeid=1 
            and leaddate between '$period-01' AND'$period-31'  ";// 
 $result_lead1=DB_query($sqllead1,$db); 
          $row_leadcont1=DB_fetch_array($result_lead1);
 $leadcont1= $row_leadcont1[0];
  
 $sql_lead_ach1="SELECT incentive FROM bio_achievementpolicy 
      WHERE designation='".$row['designationid']."'
      AND enquiry_type=1 AND rangefrom < '".$row_leadcont1[0]."' AND rangeto >= '".$row_leadcont1[0]."' AND taskid=0 
      and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy
       WHERE designation='".$row['designationid']."'
      AND enquiry_type=1 AND rangefrom < '".$row_leadcont1[0]."' AND rangeto >= '".$row_leadcont1[0]."' and taskid=0 group by taskid)";
 $sql_lead_ach1=DB_query($sql_lead_ach1,$db); 
          $row_ach1=DB_fetch_array($sql_lead_ach1);
  $incentivedom=($row_leadcont1[0]*$row_ach1['incentive']); 

  
  
  
  
  $sqllead2="SELECT count(`leadid`)  FROM `bio_leads` WHERE `created_by`='".$row['userid']."'and enqtypeid=2 
                and leaddate between '$period-01' AND'$period-31'";//and leaddate between 
 $result_lead2=DB_query($sqllead2,$db); 
          $row_leadcont2=DB_fetch_array($result_lead2);
 $leadcont2= $row_leadcont2[0];
  
 $sql_lead_ach2="SELECT incentive FROM bio_achievementpolicy 
      WHERE designation='".$row['designationid']."'
      AND enquiry_type=2 AND rangefrom < '".$row_leadcont2[0]."' AND rangeto >= '".$row_leadcont2[0]."' AND taskid=0 
       and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy
       WHERE designation='".$row['designationid']."'
      AND enquiry_type=1 AND rangefrom < '".$row_leadcont2[0]."' AND rangeto >= '".$row_leadcont2[0]."' and taskid=0 group by taskid)";
 $sql_lead_ach2=DB_query($sql_lead_ach2,$db); 
          $row_ach2=DB_fetch_array($sql_lead_ach2);
  $incentive_inc=($row_leadcont2[0]*$row_ach2['incentive']); 
 
 
 
 $total_lead_incentive=$incentivedom+$incentive_inc;
 
 $leadsum=$leadsum+$total_lead_incentive;
$ordersum=$ordersum+$totalincentive;
echo'
<tr>
        <td><div align="center">'.$slno.'</div></td>
      <td>'.$row['office'].'</td>
      <td>'.$row['userid'].' </td>
      <td><div align="center"> '.$total_lead_incentive. '</div>    </td>
      <td><div align="center">'.$totalincentive.'</div></td>
      <td><div align="center"></div> </td>
      <td><div align="center"></div>        <div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>';   
     $slno++;                 
}            ?> 
    
      <td height="20" colspan="3"><div align="center"><strong>T O T A L</strong></div></td>
    
      <td><div align="center"><strong><?php echo $leadsum; ?></strong></div></td>
      <td><div align="center"><strong><?php echo $ordersum; ?></strong></div></td>
      <td><div align="center"><strong></strong></div></td>
      <td><div align="center"><strong></strong></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <label></label>
</form>
</body>
</html>
