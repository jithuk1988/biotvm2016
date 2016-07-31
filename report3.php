<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Leads Report');  
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
 
     if($_POST['submit'])
  {
     if($_POST['year']) 
     {
         $year=$_POST['year']; 
     }else
     {
         $year=date('Y');
     }
   if($_POST['month'])
   {
      $month=$_POST['month']; 
   }else
   {
     $month=date('m');  
   }
  
  $period=$year."-".$month ;
      
    $office=$_POST['office'];
   
 $sql="SELECT bio_office.office,
            www_users.`userid`,
            bio_teammembers.teamid,
            bio_emp.designationid,
            bio_emp.empid , bio_emp.empname
 FROM www_users 
inner join  bio_emp on (www_users.`empid`= bio_emp.empid) 
inner join  bio_office on (bio_office.id = bio_emp.offid)
inner join bio_teammembers on (bio_teammembers.empid = bio_emp.empid)
WHERE  bio_emp.empid='".$_POST['employee']."' ";
 $result=DB_query($sql,$db);
      $row=DB_fetch_array($result);
      $user=$row['userid'];
  // echo $sql."<br>";   
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
 $result_k=DB_query($sql_k,$db);
      $my_row_k=DB_fetch_array($result_k);  
      
   //   echo $sql_k."<br>";   
      
   //-------------------------------------------- 
   
       
      $sql_ip="SELECT incentive FROM bio_achievementpolicy 
      WHERE designation='".$row['designationid']."'
      AND enquiry_type=1 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' AND taskid=71
      AND taskid=71
      and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy
       WHERE designation='".$row['designationid']."'
      AND enquiry_type=1 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' and taskid=71 group by taskid)";
     
          $result_ip=DB_query($sql_ip,$db); 
          $row_ip=DB_fetch_array($result_ip);
       /*  echo $row_ip['incentive'];  
        echo $my_row_k[0]."<br>";   
        echo $row['designationid']."<br>"; */  
         
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
      
      
     //   echo $sql_k2."<br>";   
      //--------------------------------------
      
          $sql_ip2="SELECT incentive FROM bio_achievementpolicy 
      WHERE designation='".$row['designationid']."'
      AND enquiry_type=2 AND rangefrom < '".$my_row_k2[0]."' AND rangeto >= '".$my_row_k2[0]."' AND taskid=71
      and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy
       WHERE designation='".$row['designationid']."'
      AND enquiry_type=2 AND rangefrom < '".$my_row_k2[0]."' AND rangeto >= '".$my_row_k2[0]."' AND taskid=71 group by taskid)
      ";
    
          $result_ip2=DB_query($sql_ip2,$db); 
          $row_ip2=DB_fetch_array($result_ip2);
           $incentive2=($my_row_k2[0]*$row_ip2['incentive']); 
           
       //-----order incentive -----//    
$totalincentive=$incentive1+ $incentive2;

//------------------------archieve-------------//
  $achieve=$my_row_k2[0]+ $my_row_k[0];

          //   echo $sql_ip2."<br>";   
       
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
   //------------lead incentive----------------//
   $incentivedom=($row_leadcont1[0]*$row_ach1['incentive']); 
   $incentive_inc=($row_leadcont2[0]*$row_ach2['incentive']); 
 
   $total_lead_incentive=$incentivedom+$incentive_inc; 
   //------------------archive---------------
     $achieve2=$row_leadcont2[0]+$row_leadcont1[0];
  

    //---------------------------------------------------------
    //----------------------------------------------------------
    //------------------------------------------------------------
    
         $sql_tgt="SELECT target,month,year FROM bio_target WHERE  month=".$month." AND year= ".$year."
AND enqid=1
AND task=0
AND team_id='".$row['teamid']."'
"; 
 $result_tgt=DB_query($sql_tgt,$db); 
 $target=DB_fetch_array($result_tgt); 
  $target1=$target[0];
 //--------------------------------------
    $sql_tgt1="SELECT target,month,year FROM bio_target WHERE  month=".$month." AND year= ".$year."
AND enqid=2
AND task=0
AND team_id='".$row['teamid']."'
"; 
 $result_tgt1=DB_query($sql_tgt1,$db); 
 $target_tgt1=DB_fetch_array($result_tgt1); 
 $target2=$target_tgt1[0];
 
  //----------------------------------------------
  //-----------target first----------------------//
    $tottarget=$target1+$target2;
  
   $sql_sec="SELECT target,month,year FROM bio_target WHERE  month=".$month." AND year= ".$year."
AND enqid=1
AND task=71
AND team_id='".$row['teamid']."'
"; 
 $result_sec=DB_query($sql_sec,$db); 
 $targetsec=DB_fetch_array($result_sec); 
 $targetsecond=$targetsec[0];
  
  $sql_sec1="SELECT target,month,year FROM bio_target WHERE  month=".$month." AND year= ".$year."
AND enqid=2
AND task=71
AND team_id='".$row['teamid']."'
"; 
 $result_sec1=DB_query($sql_sec1,$db); 
 $targetsec1=DB_fetch_array($result_sec1); 
 $targetsecond1=$targetsec1[0];
 //-------------------tarjet second--------------------------//
  $totaltargetsec=$targetsecond1+$targetsecond;  

 
 
 //-------------------//-------------------------//------------
 
//-----------shortage in achievement--------------------//
 
$shortagelead=$tottarget-$achieve2;

  $shortageorder=$totaltargetsec-$achieve;

 //-----------------/lead/------------------------
 $targetdom=($target1*$row_ach1['incentive']); 
   $target_inc=($target2*$row_ach2['incentive']); 
  
  $totaltar_inc=$targetdom+$target_inc;
  $shortage_inc=$totaltar_inc-$total_lead_incentive;
  
  //-----------------------order-------------------------
  $target_inc=($targetsecond*$row_ip['incentive']); 
  $target_inc2=($targetsecond1*$row_ip2['incentive']); 
  $total_targ_inc=$target_inc+$target_inc2;
  $shortage_inc2=$total_targ_inc-$totalincentive;
  }  
  
?>
<?php
 

  echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";     
     echo "<legend><h3>OFFICE INCENTIVE REPORT</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";
     echo "<tr><td>Office</td>";
     $sql="SELECT * FROM `bio_office` where id in(2,3,4) ";
    
     echo "<td><select name='office' onchange='emp(this.value)'>";
      $result=DB_query($sql,$db);
    //  echo "<option value='all'>ALL</option>";
   echo "<option selected value=10>All</option>";
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
     echo '</select></td><td>Employee</td><td ><select name=employee id=employee>';  
       $sql="SELECT bio_office.office,
            www_users.`userid`,
            bio_teammembers.teamid,
            bio_emp.designationid,
            bio_emp.empid , bio_emp.empname
 FROM www_users 
inner join  bio_emp on (www_users.`empid`= bio_emp.empid) 
inner join  bio_office on (bio_office.id = bio_emp.offid)
inner join bio_teammembers on (bio_teammembers.empid = bio_emp.empid)
WHERE  `offid` in (2,4,3)"   ;

      $result=DB_query($sql,$db);
      while($row=DB_fetch_array($result))
      {
          if($_POST['employee']==$row['empid'])
          {
              echo "<option selected value='".$row['empid']."'>".$row['empname']."</option>";  
          } else
          {
                echo "<option value='".$row['empid']."'>".$row['empname']."</option>";  
          }
        //  echo "<input type=hidden name=userid value= '".$row['`userid`']."'  >"  ;
         // echo "<input type=hidden name=teamid value= '".$row['teamid']."'  >"  ;
      }

     echo '</td><td>Month</td>';
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
   
  $monthName = date("F", mktime(0, 0, 0, $month, 10)); 
?>
  <?php 
 
?>
  <table width="94%" height="414" border="1">
  <tr>
  <td height="63" colspan="7"><label><center>
  <?php
  if($_POST['submit'])
  {
 echo   '<h2 align="center"><strong>Report  of eligible incentive for  '.$user.' for  '.$monthName."-".$year.' </strong></h2>';   
  }
  ?>
   
    <h2 align="center">&nbsp;</h2>  </td>
 </tr>
    <tr>
      <th><strong><center>
        No
      </center></strong>
        <label><strong><center></center></strong></label><label><strong></strong></label></th>
      <th width="23%">Item name</th>
      <th width="11%"><strong><strong>Target </strong></strong>        <label><strong><center></center></strong></label></th>
      <th width="10%"><strong><center>
        <strong><strong>Achieved</strong></strong>
      </center></strong>
        <label><strong><center></center></strong></label><label><strong></strong></label></th>
      <th width="11%"><strong><strong>Incentive  due</strong></strong>        <label><strong><center></center></strong></label></th>
      <th width="18%"><strong><center>
        <strong><strong>Shortage  in achievement</strong></strong>
      </center></strong>
        <label><strong><center></center></strong></label><label><strong></strong></label></th>
      <th width="19%"><strong><center>
        <strong>Shortage  of incentive</strong>
      </center></strong>
        <label><strong><center></center></strong></label>
        <label><strong><center></center></strong></label><label><strong></strong></label></th>
    </tr>
    
    <tr>
      <td><div align="center">1</div>        <div align="center"></div>        <div align="center"></div></td>
      <td><div align="center">Leads</div></td>
      <td><div align="center"><?php echo $tottarget ;?></div></td>
      <td><div align="center"><span class="style6"><span class="style7"></span></span></div>        <div align="center"><?php echo $achieve2; ?>   </div>     <div align="center"><span class="style6"><span class="style7"></span></span></div></td>
      <td><div align="center"><?php echo $total_lead_incentive?></div></td>
      <td><div align="center"></div>        <div align="center"><?php echo $shortagelead; ?></div>        <div align="center"></div>        <div align="center"></div></td>
      <td><div align="center"></div>        <div align="center"></div>        <div align="center"><?php echo $shortage_inc; ?></div>        <div align="center"></div></td>
    </tr>
    <tr>
      <td bgcolor="#CC99CC"><div align="center">2</div>        <div align="center"><span class="style6"><span class="style7"></span></span></div>        <div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center">Order</div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $totaltargetsec; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div>        <div align="center"><?php echo $achieve; ?> </div>       <div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo  $totalincentive; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div>        <div align="center"><?php echo $shortageorder; ?></div>        <div align="center"></div>        <div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div>        <div align="center"></div>        <div align="center"><?php echo $shortage_inc2; ?></div>        <div align="center"></div></td>
    </tr>
    <tr>
      <td><div align="center"><span class="style6"><span class="style7"></span></span>3</div>        <div align="center"><span class="style6"><span class="style7"><span class="style8"></span></span></span></div>        <div align="center"></div></td>
      <td><div align="center">Collection</div></td>
      <td><div align="center"></div></td>
      <td><div align="center"><span class="style6"><span class="style7"></span></span></div>        <div align="center"></div>        <div align="center"><span class="style6"><span class="style7"></span></span></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div>        <div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
      <td><div align="center"></div>        <div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
    </tr>
    <tr>
      <td bgcolor="#CC99CC"><div align="center">4</div>        <div align="center"><span class="style8"></span></div>        <div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center">AMC</div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div>        <div align="center"></div>        <div align="center"><span class="style6"><span class="style7"></span></span></div>        <div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div>        <div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
    </tr>
    <tr>
      <td><div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
      <td><div align="center">Total</div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div>        <div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
      <td><div align="center"></div>        <div align="center"></div>        <div align="center"></div>        <div align="center"></div></td>
    </tr>
  </table>

  <label></label>
</form>
</body>
</html>
<script>
function emp(str)
{     
    if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
  else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function()
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("employee").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","report3ajax.php?offid="+str,true);//alert(str1);
xmlhttp.send();        
}

</script>