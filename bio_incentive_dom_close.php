<?php

$PageSecurity = 80;  
include('includes/session.inc');
$title = _('Incentive Calculation');  
include('includes/header.inc'); 

if($_POST['close_incentive'])
{
    $sql_getfromtemp="SELECT *FROM bio_temptarget_dom";
    $rseult_gettemp=DB_query($sql_getfromtemp,$db);
    while($row_fromtemp=DB_fetch_array($rseult_gettemp))
    {
        $sql_incentive="INSERT INTO bio_incentive(teamid,task,month,year,ordervalue,achivement_p,target,eligible_p,incentive)
        VALUES ('".$row_fromtemp['teamid']."','".$row_fromtemp['task']."','".$row_fromtemp['month']."','".$row_fromtemp['year']."','".$row_fromtemp['ordervalue']."','".$row_fromtemp['achivement_p']."','".$row_fromtemp['target']."','".$row_fromtemp['eligible_p']."','".$row_fromtemp['incentive']."')";
        $result_incentive=DB_query($sql_incentive,$db);
        
    }    
    $sql_closingdate="INSERT INTO bio_closingdate (type,enqtype,closedate) VALUES (1,1,'".$_POST['close_date']."')";
    $result_closedate=DB_query($sql_closingdate,$db);
    
}



//////////////////CREATING TTEMPORARY TABLE/////////////////////////////IF NOT EXISTS        
$sql_tmptable="CREATE  TABLE IF NOT EXISTS bio_temptarget_dom(teamid int,task int,month int,year int,ordervalue int,target int,achivement_p int,eligible_p int,incentive double,level int)";
                          $result_tmptable=DB_query($sql_tmptable,$db);
                          $sql_create_instl1="DELETE FROM bio_temptarget_dom";
                          $result_create_instl=DB_query($sql_create_instl1,$db);


echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" style="background:#EBEBEB;">';



           
           ///    TYPE  (bio_ closingdate)
           //--------------
           //type=1 , closedate for marketing staff
           //type=2 , for leadsource
           //  type=3 , for office
           
           echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";     
     echo "<legend><h3>CCE/BDM(D)/BH Incentive</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";
 $closedate=DB_query("SELECT MAX(closedate) as lastclose,month(MAX(closedate)) as lastmonth,year(MAX(closedate)) as lastyear FROM  bio_closingdate WHERE type=1 AND  enqtype=1 ",$db);
 $closedate_row=DB_fetch_array($closedate);
  $last_closedate=ConvertSQLDate($closedate_row['lastclose']);
   $closedate_row['lastmonth'];
   ///////GET CURRENT DATE//////////////////////////// 
       if($_POST['closing']!="")
    {
        $nowFormat=FormatDateForSQL($_POST['closing']); 
        $dhead='<tr><td width=150px><b>Last Close Date :</b></td><td><b>'.$last_closedate.'</b></td><td width=150px><b>Closing Date:</b></td><td><b>'.ConvertSQLDate($nowFormat).'</b></td>
  <td><input type=submit name=close_incentive value="CLOSE"></td></tr>' ;
    
    }
    else
    {
         $nowFormat = date('Y-m-d');
         $dhead='<tr><td width=150px><b>Last Close Date :</b></td><td><b>'.$last_closedate.'</b></td><td width=150px><b>Current Date:</b></td><td><b>'.ConvertSQLDate($nowFormat).'</b></td></tr><tr><td width=150px><b>Closing Date:</b></td><td><input type=text name=closing id=closing class=date alt="'.$_SESSION['DefaultDateFormat'].'" value='.date("d/m/Y").'></td>
  <td><input type=submit name=submit value="VIEW UP TO CLOSE DATE" onclick= "if(validate()==1)return false;"></td></tr>' ;
    }
    echo "<input type=hidden name=close_date id=close_date value=".$nowFormat.">";
    echo "<input type=hidden name=last_close_date id=last_close_date value=".$last_closedate.">";
$monthName = date("F", mktime(0, 0, 0, $closedate_row['lastmonth'], 10));
  echo $dhead;
     echo '<tr style=background:#A8A4DB>
     <td style="border-bottom:1px solid #0000FF">Name</td>   
                <td style="border-bottom:1px solid #0000FF">Target Month/Year</td>

                <td style="border-bottom:1px solid #000000">No. of CUM</td>
       
                <td style="border-bottom:1px solid #000000">eligible%</td>
                <td style="border-bottom:1px solid #000000">incentive</td>
                </tr>';       /*  <td style="border-bottom:1px solid #000000">achivement%</td>*/                /*<td style="border-bottom:1px solid #000000">Target Amount</td>*/
                echo"<tr><td><b>CCE</b></td></tr>";
                $sql_iinnce="SELECT DISTINCT bio_leadtask.teamid FROM bio_leadtask 
            WHERE bio_leadtask.leadid IN ( SELECT DISTINCT bio_leadtask.leadid FROM bio_leadtask,bio_leads 
            WHERE bio_leadtask.teamid IN (SELECT teamid FROM bio_leadteams) 
            AND bio_leadtask.viewstatus=1
            AND bio_leads.enqtypeid=1
            AND bio_leads.leadstatus in (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
            AND bio_leads.leadid=bio_leadtask.leadid)
            AND teamid in(SELECT
            DISTINCT bio_leadteams.teamid
FROM
    bio_designation
    bio_office, 
    bio_leadteams
    INNER JOIN bio_teammembers 
        ON (bio_leadteams.teamid=bio_teammembers.teamid)
    INNER JOIN bio_emp 
        ON (bio_teammembers.empid=bio_emp.empid)
        WHERE bio_emp.designationid IN (19))
        
        ";/*AND bio_leadtask.teamid IN ($team_array)*/
              //$sql_iinnce="SELECT teamid,month,year,ordervalue,achivement_p,target,eligible_P,incentive FROM bio_incentive ORDER BY month DESC"; 
              $result6=DB_query($sql_iinnce,$db); 
              while($row=DB_fetch_array($result6)){
                  $teams=DB_query("SELECT teamname FROM  bio_leadteams WHERE teamid='".$row['teamid']."'",$db);
                  $row_team=DB_fetch_array($teams);
                 
                
/*              $sql_k="SELECT SUM(salesorderdetails.quantity) as qty,SUM(stockitemproperties.value) as cum FROM salesorderdetails,stockitemproperties 
              WHERE stockitemproperties.stockid=salesorderdetails.stkcode
              AND stockitemproperties.stkcatpropid=48
              AND orderno IN ( SELECT orderno FROM salesorders WHERE leadid IN (SELECT DISTINCT bio_leadtask.leadid
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid='".$row['teamid']."'
AND bio_leadtask.viewstatus=1
AND bio_leads.enqtypeid=1 
AND bio_leads.leadstatus IN (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
AND bio_leads.leadid=bio_leadtask.leadid) 
AND orddate between '".$closedate_row['lastclose']."' AND'".$nowFormat."' 
   )";
   */
   
   
   
   $sql_k="SELECT SUM(stockitemproperties.value * salesorderdetails.quantity) FROM salesorderdetails,stockitemproperties WHERE stockitemproperties.stockid=salesorderdetails.stkcode AND stockitemproperties.stkcatpropid=48 AND salesorderdetails.orderno IN (SELECT orderno FROM salesorders WHERE leadid IN (SELECT DISTINCT bio_leadtask.leadid
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid='".$row['teamid']."'
AND bio_leadtask.viewstatus=1
AND bio_leads.enqtypeid=1 
AND bio_leads.leadstatus IN (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
AND bio_leads.leadid=bio_leadtask.leadid) 
AND orddate between '".$closedate_row['lastclose']."' AND'".$nowFormat."' ) GROUP BY stockitemproperties.stkcatpropid";

     $result_k=DB_query($sql_k,$db);
      $my_row_k=DB_fetch_array($result_k);  
      
      
      ////GET TARGET OF EACH EMPLOYEe/////////////

      $sql_tgt="SELECT target,month,year FROM bio_target WHERE  month=".$closedate_row['lastmonth']." AND year= ".$closedate_row['lastyear']."
AND enqid=1
AND task=71
AND team_id=$row[teamid]
"; 
 $result_tgt=DB_query($sql_tgt,$db); 
 $target=DB_fetch_array($result_tgt); 
        
     if($target['target']==""){
         $tgt="Target not defined";
     }else{
         $tgt=$target['target'];
     }  
     
     
     ////GET INCENTIVE POLICY//////
      $sql_ip="SELECT incentive FROM bio_achievementpolicy WHERE designation=19 AND enquiry_type=1 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy WHERE designation=19 AND enquiry_type=1 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' group by taskid)";
          $result_ip=DB_query($sql_ip,$db); 
          $row_ip=DB_fetch_array($result_ip);
          if($row_ip['incentive']!=""){$policy=$row_ip['incentive'];}else{$policy="Not defined";}
          
          ///CALCULATE INCENTIVE
          
          $incentive=($my_row_k[0]*$row_ip['incentive']);      
                printf("<tr style='background:#F0D1B2'>
            <td cellpading=2 width='150px'>%s</td>
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td>   
                        </tr>",
        
        $row_team['teamname'],
        $monthName."/".$closedate_row['lastyear'],
        $my_row_k[0],
        $policy,
        $incentive
        ); 

        $sql_temp_insert="INSERT INTO bio_temptarget_dom (teamid,task,month,year,ordervalue,target,achivement_p,eligible_p,incentive,level) VALUES ('".$row['teamid']."',71,
        '".$closedate_row['lastmonth']."','".$closedate_row['lastyear']."','".$my_row_k[0]."','".$tgt."','".$ach_p."','".$row_ip['incentive']."','".$incentive."',1) ";
        $result_temp=DB_query($sql_temp_insert,$db);
        
           } 

               echo "<tr><td><b>BDM(D)</b></td></tr>";
               
               $sql_get_ro="SELECT  bio_temptarget_dom.teamid,bio_emp.reportto FROM  bio_temptarget_dom,bio_teammembers,bio_emp 
               WHERE bio_teammembers.teamid=bio_temptarget_dom.teamid
AND bio_teammembers.empid=bio_emp.empid 

AND bio_temptarget_dom.level=1  
GROUP BY bio_emp.reportto   
";/*AND bio_teammembers.teamid IN ($team_array)*/
$result_get_ro=DB_query($sql_get_ro,$db);
while($rep_off=DB_fetch_array($result_get_ro))
{
    $get_bdm=DB_query("SELECT bio_leadteams.teamname,bio_leadteams.teamid FROM bio_leadteams,bio_teammembers,bio_emp
WHERE bio_leadteams.teamid=bio_teammembers.teamid
AND bio_teammembers.empid=bio_emp.empid
AND bio_emp.empid='".$rep_off['reportto']."'",$db);
$team_bdm=DB_fetch_array($get_bdm);

                  $sql_bdmtotal=" SELECT SUM(ordervalue)FROM  bio_temptarget_dom WHERE teamid IN (SELECT bio_teammembers.teamid FROM bio_teammembers WHERE bio_teammembers.empid IN (SELECT bio_emp.empid FROM `bio_emp` WHERE reportto IN (SELECT bio_emp.empid FROM bio_emp,bio_teammembers  WHERE bio_teammembers.empid=bio_emp.empid AND bio_teammembers.teamid=$team_bdm[teamid] ))
AND bio_teammembers.teamid IN (SELECT teamid FROM  bio_temptarget_dom WHERE LEVEL=1) )";
$result_bdmtotal=DB_query($sql_bdmtotal,$db);
$row_bdmtotal=DB_fetch_array($result_bdmtotal);

/*
                  $sql_bdmtotal="SELECT teamid,SUM(ordervalue)AS sum_order,SUM(target) AS sum_target,COALESCE(SUM(ordervalue)/SUM(target)*100,0) AS ach_p 
FROM bio_temptarget
WHERE month=".$closedate_row['lastmonth']."
AND year=".$closedate_row['lastyear']."
AND teamid IN(SELECT bio_teammembers.teamid FROM bio_teammembers,bio_emp 
WHERE bio_emp.empid=bio_teammembers.empid 
AND bio_emp.reportto IN(SELECT empid FROM bio_teammembers WHERE teamid='".$team_bdm['teamid']."'))";
$result_bdmtotal=DB_query($sql_bdmtotal,$db);*/


////  ACHIEVEMENT PERCENTAGE
          /*$ach_p1=($row_bdmtotal['sum_order']/$row_bdmtotal['sum_target'])*100;*/
          ////GET INCENTIVE POLICY//////
          $sql_ip1="SELECT incentive FROM bio_achievementpolicy WHERE designation=16 AND enquiry_type=1 AND rangefrom < '".$row_bdmtotal[0]."' AND rangeto >= 
          '".$row_bdmtotal[0]."' and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy WHERE designation=16 AND enquiry_type=1 AND rangefrom
           < '".$row_bdmtotal[0]."' AND rangeto >= '".$row_bdmtotal[0]."' group by taskid)";
          $result_ip1=DB_query($sql_ip1,$db); 
          $row_ip1=DB_fetch_array($result_ip1);
          if($row_ip1['incentive']!=""){$policy=$row_ip1['incentive'];}else{$policy="Not defined";}
          
                    ///CALCULATE INCENTIVE
          
          $incentive_bdm=$row_ip1['incentive']*$row_bdmtotal[0];  

                    printf("<tr style='background:#F0D1B2'>
            <td cellpading=2 width='150px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td>     
                        </tr>",
        $team_bdm['teamname'],
        $monthName."/".$closedate_row['lastyear'],
        $row_bdmtotal[0],
        $policy,
        $incentive_bdm
        ); 
        $sql_temp_insert1="INSERT INTO bio_temptarget_dom (teamid,task,month,year,ordervalue,eligible_p,incentive,level) VALUES ('".$team_bdm['teamid']."',71,'".$closedate_row['lastmonth']."','".$closedate_row['lastyear']."','".$row_bdmtotal[0]."',
        '".$row_ip1['incentive']."','".$incentive_bdm."',2) ";
        $result_temp1=DB_query($sql_temp_insert1,$db);
}


echo "<tr><td>BH</td></tr>";
               $sql_get_ro="SELECT  bio_temptarget_dom.teamid,bio_emp.reportto FROM  bio_temptarget_dom,bio_teammembers,bio_emp WHERE bio_teammembers.teamid=bio_temptarget_dom.teamid
AND bio_teammembers.empid=bio_emp.empid  AND  bio_temptarget_dom.level=2
   GROUP BY bio_emp.reportto 
";/*AND bio_teammembers.teamid IN ($team_array)*/
$result_get_ro=DB_query($sql_get_ro,$db);
while($rep_off=DB_fetch_array($result_get_ro))
{        $get_bdm="SELECT bio_leadteams.teamname,bio_leadteams.teamid FROM bio_leadteams,bio_teammembers,bio_emp
WHERE bio_leadteams.teamid=bio_teammembers.teamid
AND bio_teammembers.empid=bio_emp.empid
AND bio_emp.empid='".$rep_off['reportto']."'";
     $result_bdm=DB_query($get_bdm,$db);
      $team_bdm=DB_fetch_array($result_bdm);
      $team_bdm['teamname'];
                  $sql_bdmtotal=" SELECT SUM(ordervalue)FROM  bio_temptarget_dom WHERE teamid IN (SELECT bio_teammembers.teamid FROM bio_teammembers WHERE bio_teammembers.empid IN (SELECT bio_emp.empid FROM `bio_emp` WHERE reportto IN (SELECT bio_emp.empid FROM bio_emp,bio_teammembers  WHERE bio_teammembers.empid=bio_emp.empid AND bio_teammembers.teamid=$team_bdm[teamid] ))
AND bio_teammembers.teamid IN (SELECT teamid FROM  bio_temptarget_dom WHERE level=2) )";
$result_bdmtotal=DB_query($sql_bdmtotal,$db);
$row_bdmtotal=DB_fetch_array($result_bdmtotal);


////  ACHIEVEMENT PERCENTAGE
          /*$ach_p1=($row_bdmtotal['sum_order']/$row_bdmtotal['sum_target'])*100;*/
          ////GET INCENTIVE POLICY//////
          $sql_ip1="SELECT incentive FROM bio_achievementpolicy WHERE designation=4 AND enquiry_type=1 AND rangefrom < '".$row_bdmtotal[0]."' AND rangeto >= '".$row_bdmtotal[0]."' and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy WHERE designation=4 AND enquiry_type=1 AND rangefrom < '".$row_bdmtotal[0]."' AND rangeto >= '".$row_bdmtotal[0]."' group by taskid)";
          $result_ip1=DB_query($sql_ip1,$db); 
          $row_ip1=DB_fetch_array($result_ip1);
          if($row_ip1['incentive']!=""){$policy=$row_ip1['incentive'];}else{$policy="Not defined";}
          
                    ///CALCULATE INCENTIVE
          
          $incentive_bdm=$row_ip1['incentive']*$row_bdmtotal[0];  

                    printf("<tr style='background:#F0D1B2'>
            <td cellpading=2 width='150px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td>  
                        </tr>",
        $team_bdm['teamname'],
        $monthName."/".$closedate_row['lastyear'],
        $row_bdmtotal[0],
        $policy,
        $incentive_bdm
        ); 
        $sql_temp_insert1="INSERT INTO bio_temptarget_dom (teamid,task,month,year,ordervalue,eligible_p,incentive,level) VALUES ('".$team_bdm['teamid']."',71,
        '".$closedate_row['lastmonth']."','".$closedate_row['lastyear']."','".$row_bdmtotal[0]."','".$row_ip1['incentive']."','".$incentive_bdm."',3) ";
        $result_temp1=DB_query($sql_temp_insert1,$db);
}
               
     echo "</table></fieldset>";       
    echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";   
       
       echo '<table class="selection" style="background:#EBEBEB;width:500px;">'; 
echo '<tr id="tabl" name="tabl"><td>Closing Month*</td><td>Closing Year*</td></tr>';
echo '<tr><td><select name="close" id="close" class style="width:146px" >';
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
echo '<td><input type="text" name="closeyear" id="closeyear" class style="width:146px" ></td><td><input type="submit" name="search" id="search" value="Search"></td></tr>';
echo '</table>';
echo '</form>' ;

  
     echo "<legend><h3>Previous Incentives</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";

     echo '<tr>
     <td style="border-bottom:1px solid #000000">Name</td>   
                <td style="border-bottom:1px solid #000000">month</td>
                <td style="border-bottom:1px solid #000000">year</td>
                <td style="border-bottom:1px solid #000000">ordervalue</td>
                <td style="border-bottom:1px solid #000000">achivement%</td>
                <td style="border-bottom:1px solid #000000">target</td>
                <td style="border-bottom:1px solid #000000">eligible%</td>
                <td style="border-bottom:1px solid #000000">incentive</td>
                </tr>';
                $sql_iinnce="SELECT c.empname,a.month,a.year,a.ordervalue,a.achivement_p,a.target,a.eligible_p,a.incentive FROM bio_incentive a,bio_emp c,bio_teammembers b WHERE a.teamid=b.teamid AND b.empid=c.empid AND a.task=71 ";

                    if($_POST['close']!="" || $_POST['close']!=0)
                    {
                        $sql_iinnce.=" AND a.month='".$_POST['close']."' ";
                    }
                    if($_POST['closeyear']!="" || $_POST['closeyear']!=0)
                    {
                        $sql_iinnce.=" AND a.year='".$_POST['closeyear']."' ";
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
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>          
                        </tr>",
        $row['empname'],
        date("F", mktime(0, 0, 0, $row['month'], 10)),
        $row['year'],
        $row['ordervalue'],
        $row['achivement_p'],
        $row['target'],
        $row['eligible_p'],
        $row['incentive']
        ); 

           }          
           
?>
<script>
function validate()
{ 
    
var date1=document.getElementById("last_close_date").value; 
var date2=document.getElementById("closing").value;
    function process(date){
   var parts = date.split("/");
   var date = new Date(parts[1] + "/" + parts[0] + "/" + parts[2]);
   return date.getTime();
}


 var f=0;
 if(f==0){f=common_error('closing','Please Select Closing Date');  if(f==1) { return f; }}
 
/* var date1 = '25/02/1985';*/  /*february 25th*/
/*var date2 = '26/02/1985'; */ /*february 26th*/

if(process(date2)<process(date1)){
    var f=1;
          alert("Closing date should grater than last closing date");document.getElementById('closing').focus(); 
          return f;
    }
}


</script>