<?php

$PageSecurity = 40;  
include('includes/session.inc');
$title = _('Incentive Calculation');  
include('includes/header.inc'); 


if(isset($_POST['close']))
{
     $count=$_POST['count'];
     $month=$_POST['month'];
     $year=$_POST['year'];
     
     for($i=1;$i<=$count;$i++)
     {
         $sql_insert="INSERT INTO bio_incentive_office(officeid,month,year,target,total_cum,ach_pol,incentive) VALUES('".$_POST['office'.$i]."','".$month."','".$year."',
         '".$_POST['target'.$i]."','".$_POST['total'.$i]."','".$_POST['eligible'.$i]."','".$_POST['incentive'.$i]."') " ;
         $row_insert=DB_query($sql_insert,$db);
     }
     
   $sql_closingdate="INSERT INTO bio_closingdate (type,enqtype,closedate) VALUES (2,1,'".$_POST['close_date']."')";
    $result_closedate=DB_query($sql_closingdate,$db);  
}



//////////////////CREATING TTEMPORARY TABLE/////////////////////////////IF NOT EXISTS        
/*$sql_tmptable="CREATE  TABLE IF NOT EXISTS bio_temptarget_office(teamid int,task int,month int,year int,ordervalue double,target double,achivement_p double,eligible_p double,incentive double,level int)";
                          $result_tmptable=DB_query($sql_tmptable,$db);
                          $sql_create_instl1="DELETE FROM bio_temptarget_office";
                          $result_create_instl=DB_query($sql_create_instl1,$db);
*/





           
                      ///    TYPE  (bio_ closingdate)
           //--------------
           //type=1 , closedate for marketing staff
           //  type=2 , for office
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" style="background:#EBEBEB;">';
           echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";     
     echo "<legend><h3>OFFICE INCENTIVE CALCULATION</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";
 $closedate=DB_query("SELECT MAX(closedate) as lastclose,month(MAX(closedate)) as lastmonth,year(MAX(closedate)) as lastyear FROM  bio_closingdate WHERE enqtype=1 AND type=2",$db);
 $closedate_row=DB_fetch_array($closedate);
  $last_closedate=ConvertSQLDate($closedate_row['lastclose']);
   $closedate_row['lastmonth'];
   ///////GET CURRENT DATE//////////////////////////// 
   
   if(isset($_POST['submit']))
   {
       $nowFormat=FormatDateForSQL($_POST['closing']);
                $heading='<tr><td><b>Last Close Date :'.$last_closedate.'</b></td><td><b>Close Date :'.ConvertSQLDate($nowFormat).'</b></td>
  
  <td><input type=submit name=close value="CLOSE" onclick= ""></td></tr>';
       
   }else{
       $nowFormat = date('Y-m-d'); 
         $heading='<tr><td><b>Last Close Date :'.$last_closedate.'</b></td><td><b>Current Date :'.ConvertSQLDate($nowFormat).'</b></td>
  
  
  <td width=150px><b>Closing Date:</b></td><td><input type=text name=closing id=closing class=date alt="'.$_SESSION['DefaultDateFormat'].'" value='.date("d/m/Y").'></td>
  <td><input type=submit name=submit value="VIEW UP TO CLOSE DATE" onclick= "if(validate()==1)return false;"></td></tr>';
    
   }
   
$monthName = date("F", mktime(0, 0, 0, $closedate_row['lastmonth'], 10));
  
    echo $heading;
    
     echo '<tr style=background:#A8A4DB>
     <td style="border-bottom:1px solid #0000FF">Name</td>   
                <td style="border-bottom:1px solid #0000FF">Target Month/Year</td>
                <td style="border-bottom:1px solid #000000">Target Amount</td>
                <td style="border-bottom:1px solid #000000">No. of CUM</td>
                <td style="border-bottom:1px solid #000000">Eligible Unit</td>
                <td style="border-bottom:1px solid #000000">incentive</td>
                </tr>';
                echo"<tr><td><b>OFFICE INCENTIVE</b></td></tr>";
                $sql_iinnce="SELECT DISTINCT bio_office.office,bio_office.id FROM bio_office INNER JOIN bio_emp ON (bio_office.id=bio_emp.offid)
INNER JOIN www_users ON (bio_emp.empid=www_users.empid)
INNER JOIN bio_leads ON (www_users.userid=bio_leads.created_by)
INNER JOIN salesorders ON (bio_leads.leadid=salesorders.leadid)
order by bio_office.id  
        ";

              $result6=DB_query($sql_iinnce,$db); 
              $i=0;
              while($row=DB_fetch_array($result6)){
                  
                  
                  $teams=DB_query("SELECT teamname FROM  bio_leadteams WHERE teamid='".$row['teamid']."'",$db);
                  $row_team=DB_fetch_array($teams);
                 
                
              $sql_k="SELECT SUM(stockitemproperties.value * salesorderdetails.quantity) AS totalcum
FROM stockitemproperties,salesorders,salesorderdetails,bio_leads,www_users,bio_emp,bio_office
WHERE stockitemproperties.stockid=salesorderdetails.stkcode
AND stockitemproperties.stkcatpropid=48
AND salesorderdetails.orderno=salesorders.orderno
AND salesorders.leadid=bio_leads.leadid
AND bio_leads.created_by=www_users.userid
AND www_users.empid=bio_emp.empid
AND bio_emp.offid=bio_office.id
AND bio_office.id='".$row['id']."'
AND orddate between '".$closedate_row['lastclose']."' AND'".$nowFormat."' 
   ";
   $result_k=DB_query($sql_k,$db);
      $my_row_k=DB_fetch_array($result_k); 
      
            $sql_ip="SELECT incentive FROM bio_achievement_office WHERE office='".$row['id']."' AND bio_achievement_office.from < '".$my_row_k[0]."' AND bio_achievement_office.to >= '".$my_row_k[0]."' and e_date in (select max(e_date) FROM bio_achievement_office WHERE bio_achievement_office.office='".$row['id']."'  AND bio_achievement_office.from < '".$my_row_k[0]."' AND bio_achievement_office.to >= '".$my_row_k[0]."')";
          $result_ip=DB_query($sql_ip,$db); 
          $row_ip=DB_fetch_array($result_ip);
          if($row_ip['incentive']!=""){$policy=$row_ip['incentive'];}else{$policy="Not defined";} 
          
          $sql_target="SELECT bio_target_office.target FROM bio_target_office
          WHERE office='".$row['id']."' AND    month='".$closedate_row['lastmonth']."'  AND  year='".$closedate_row['lastyear']."'";
          $result_target=DB_query($sql_target,$db);
          $row_target=DB_fetch_array($result_target);
          
          if($row_target{'target'}==""){$tgt="Not Defined";}else{$tgt=$row_target{'target'};}
   
                printf("<tr style='background:#F0D1B2'>
            <td cellpading=2 width='150px'>%s</td>
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td>
             <td width='100px'>%s</td>     
                        </tr>",
        
        $row['office'],
        $monthName."/".$closedate_row['lastyear'],
        $tgt,
        $my_row_k[0],
        $policy,
        $incentive=($policy*$my_row_k[0])
        ); 
     $i++;
/*Month/Year     Target Amount     No. of CUM     Eligible Unit     incentive*/
           
          echo"<input type=hidden name='incentive".$i."' id='incentive".$i."' value=".$incentive.">";
          echo"<input type=hidden name='eligible".$i."' id='eligible".$i."' value=".$row_ip['incentive'].">";
          echo"<input type=hidden name='total".$i."' id='total".$i."' value=".$my_row_k[0].">";
          echo"<input type=hidden name='target".$i."' id='target".$i."' value=".$row_target['target'].">";
          echo"<input type=hidden name='office".$i."' id='office".$i."' value=".$row['id'].">";
          
               
              } 
      echo'<input type=hidden name=count id=count value="'.$i.'">';
      echo'<input type=hidden name=year id=year value="'.$closedate_row['lastyear'].'">';
      echo'<input type=hidden name=month id=month value="'.$closedate_row['lastmonth'].'">';
          echo "<input type=hidden name=close_date value=".$nowFormat.">";   
          
          
          
          
          
               echo "</table></fieldset>";       
    echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";   
       
       echo '<table class="selection" style="background:#EBEBEB;width:500px;">'; 
echo '<tr id="tabl" name="tabl"><td>Closing Month*</td><td>Closing Year*</td></tr>';
echo '<tr><td><select name="monthfilter" id="monthfilter" class style="width:146px" >';
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
echo '<td><input type="text" name="yearfilter" id="yearfilter" class style="width:146px" ></td><td><input type="submit" name="search" id="search" value="Search"></td></tr>';
echo '</table>';
echo '</form>' ;

  
     echo "<legend><h3>Previous Incentives</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";

     echo '<tr>
     <td style="border-bottom:1px solid #000000">Office</td>   
                <td style="border-bottom:1px solid #000000">month</td>
                <td style="border-bottom:1px solid #000000">year</td>
                <td style="border-bottom:1px solid #000000">CUM</td>
                <td style="border-bottom:1px solid #000000">Eligible</td>
                <td style="border-bottom:1px solid #000000">incentive</td>
                </tr>';
                $sql_iinnce="SELECT  bio_incentive_office.officeid,bio_incentive_office.month,bio_incentive_office.year, bio_incentive_office.target, 
bio_incentive_office.total_cum, bio_incentive_office.ach_pol, bio_incentive_office.incentive,bio_office.office
FROM  bio_incentive_office,bio_office
WHERE bio_office.id=bio_incentive_office.officeid ";

                    if($_POST['monthfilter']!="" || $_POST['monthfilter']!=0)
                    {
                        $sql_iinnce.=" AND bio_incentive_office.month='".$_POST['monthfilter']."' ";
                    }
                    if($_POST['yearfilter']!="" || $_POST['yearfilter']!=0)
                    {
                        $sql_iinnce.=" AND bio_incentive_office.year='".$_POST['yearfilter']."' ";
                    }
       
                $sql_iinnce.=" ORDER BY month DESC";
              //$sql_iinnce="SELECT teamid,month,year,ordervalue,achivement_p,target,eligible_P,incentive FROM bio_incentive ORDER BY month DESC"; 
              $result6=DB_query($sql_iinnce,$db); 
              while($row=DB_fetch_array($result6)){
                printf("<tr style='background:#A8A4DB'>
            <td cellpading=2 width='150px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>    
                        </tr>",
        $row['office'],
        date("F", mktime(0, 0, 0, $row['month'], 10)),
        $row['year'],
        $row['total_cum'],
        $row['ach_pol'],
        $row['incentive']
        ); 

           }   
          
          
          
          
          
          
          
            
?>

<script>
function validate()
{ 
 var f=0;
 if(f==0){f=common_error('closing','Please Select Closing Date');  if(f==1) { return f; }}
}
</script>