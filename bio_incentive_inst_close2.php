<?php

$PageSecurity = 80;  
include('includes/session.inc');
$title = _('Incentive Calculation');  
include('includes/header.inc'); 

if($_POST['close_incentive'])
{
    $sql_getfromtemp="SELECT *FROM bio_temptarget";
    $rseult_gettemp=DB_query($sql_getfromtemp,$db);
    while($row_fromtemp=DB_fetch_array($rseult_gettemp))
    {
        $sql_incentive="INSERT INTO bio_incentive(teamid,task,month,year,ordervalue,achivement_p,target,eligible_p,incentive)
        VALUES ('".$row_fromtemp['teamid']."','".$row_fromtemp['task']."','".$row_fromtemp['month']."','".$row_fromtemp['year']."','".$row_fromtemp['ordervalue']."','".$row_fromtemp['achivement_p']."','".$row_fromtemp['target']."','".$row_fromtemp['eligible_p']."','".$row_fromtemp['incentive']."')";
        $result_incentive=DB_query($sql_incentive,$db);
        
    }    
    $sql_closingdate="INSERT INTO bio_closingdate (type,enqtype,closedate) VALUES (1,2,'".$_POST['close_date']."')";
    $result_closedate=DB_query($sql_closingdate,$db);
    
}                                                 
/*$empid=$_SESSION['empid'];   
  

$sql_emp1="SELECT * FROM bio_emp WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
$employee_arr=array();   
     $sql_drop="DROP TABLE if exists `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db,$y)         
                  {  
                      $sql3="SELECT empid FROM bio_emp WHERE reportto='".$empid."'";
                      $result3=DB_query($sql3,$db);
                      

                      
                      $employee_arr=array();
                      while($row3=DB_fetch_array($result3))
                      {
                      $empid=$row3['empid'];
                      $sql_insert="INSERT INTO emptable (empid) VALUES ($empid)";
                      $result_insert=DB_query($sql_insert,$db);
                      
                      $employee_arr[]=$empid;

                      showemp($empid,$db,$y);    
                                            
                      } 
                                      
                  } 
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($empid)";
                      $result_insert2=DB_query($sql_insert1,$db);
     
     $sql2="SELECT empid,reportto FROM bio_emp WHERE reportto=$empid";
     $result2=DB_query($sql2,$db);   
  
     while($row2=DB_fetch_array($result2))
     { 

        $empid=$row2['empid'];
        $employee_arr[]=$empid;
        $y=$empid;
        showemp($empid,$db,$y);
         
     } 
       

     $sql_select="SELECT empid FROM emptable";
     $result_select=DB_query($sql_select,$db);
     
     while($row_select=DB_fetch_array($result_select))
     {
          $employee_arr[]=$row_select['empid'];
     }
     
     $employee_arr=join(",", $employee_arr);
   
   $team_arr=array();
   $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
    $result6=DB_query($sql6,$db);
    while($row6=DB_fetch_array($result6))
    {
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);
*/




//////////////////CREATING TTEMPORARY TABLE/////////////////////////////IF NOT EXISTS        
$sql_tmptable="CREATE  TABLE IF NOT EXISTS bio_temptarget(teamid int,task int,month int,year int,ordervalue double,target double,achivement_p double,eligible_p double,incentive double,level int)";
                          $result_tmptable=DB_query($sql_tmptable,$db);
                          $sql_create_instl1="DELETE FROM bio_temptarget";
                          $result_create_instl=DB_query($sql_create_instl1,$db);

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" style="background:#EBEBEB;">';



/*if(isset($_POST['submit']))
{
    if($_POST['closing']=="")
    {
        echo"<div class=warn>Select a date</div>";
    }else
    {
        echo $_POST['closing'];
        
    }
}
 else{  */        
                      ///    TYPE  (bio_ closingdate)
           //--------------
           //type=1 , closedate for marketing staff
           //type=2 , for office
           //  type=3 , for office
           
           echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";     
     echo "<legend><h3>BDE/BDM/BH Incentive</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";
 $closedate=DB_query("SELECT MAX(closedate) as lastclose,month(MAX(closedate)) as lastmonth,year(MAX(closedate)) as lastyear FROM  bio_closingdate WHERE enqtype=2 AND type=1",$db);
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
    }   /*<td width=150px><b>Closing Date:</b></td><td><input type=text name=closing id=closing class=date alt="'.$_SESSION['DefaultDateFormat'].'"></td>
  <td><input type=submit name=submit value="VIEW UP TO CLOSE DATE" onclick= "if(validate()==1)return false;"></td>*/
    echo "<input type=hidden name=close_date id=close_date value=".$nowFormat.">";
    echo "<input type=hidden name=last_close_date id=last_close_date value=".$last_closedate.">";
$monthName = date("F", mktime(0, 0, 0, $closedate_row['lastmonth'], 10));
  echo $dhead;
     echo '<tr style=background:#A8A4DB>
     <td style="border-bottom:1px solid #0000FF">Name</td>   
                <td style="border-bottom:1px solid #0000FF">Target Month/Year</td>
                <td style="border-bottom:1px solid #000000">Achieved CUM</td>
                <td style="border-bottom:1px solid #000000">Eligible Unit</td>
                <td style="border-bottom:1px solid #000000">Incentive</td>
                </tr>';
                echo"<tr><td><b>BDE</b></td></tr>";
                $sql_iinnce="SELECT DISTINCT bio_leadtask.teamid FROM bio_leadtask 
            WHERE bio_leadtask.leadid IN ( SELECT DISTINCT bio_leadtask.leadid FROM bio_leadtask,bio_leads 
            WHERE bio_leadtask.teamid IN (SELECT teamid FROM bio_leadteams) 
            AND bio_leadtask.viewstatus=1
            AND bio_leads.enqtypeid=2 
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
        WHERE bio_emp.designationid IN (9))
        
        ";/*AND bio_leadtask.teamid IN ($team_array)*/
              //$sql_iinnce="SELECT teamid,month,year,ordervalue,achivement_p,target,eligible_P,incentive FROM bio_incentive ORDER BY month DESC"; 
              $result6=DB_query($sql_iinnce,$db); 
              while($row=DB_fetch_array($result6)){
                  $teams=DB_query("SELECT teamname FROM  bio_leadteams WHERE teamid='".$row['teamid']."'",$db);
                  $row_team=DB_fetch_array($teams);
                 
                
  $sql_k="SELECT SUM(stockitemproperties.value * salesorderdetails.quantity) FROM salesorderdetails,stockitemproperties WHERE stockitemproperties.stockid=salesorderdetails.stkcode AND stockitemproperties.stkcatpropid=48 AND salesorderdetails.orderno IN (SELECT orderno FROM salesorders WHERE leadid IN (SELECT DISTINCT bio_leadtask.leadid
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid='".$row['teamid']."'
AND bio_leadtask.viewstatus=1
AND bio_leads.enqtypeid=2
AND bio_leads.leadstatus IN (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
AND bio_leads.leadid=bio_leadtask.leadid) 
AND orddate between '".$closedate_row['lastclose']."' AND'".$nowFormat."' ) GROUP BY stockitemproperties.stkcatpropid";
   
   /* SELECT MAX(closedate) FROM bio_closingdate WHERE type=2*/
/*month( closedate ) ='".$_POST['close']."' AND year( closedate ) = '".$_POST['closeyear']."*/
   /*(SELECT closedate FROM bio_closingdate WHERE month( closedate ) =".$cl." AND year( closedate ) = '".$year."')
)*/
     $result_k=DB_query($sql_k,$db);
      $my_row_k=DB_fetch_array($result_k);  
      
      
      ////GET TARGET OF EACH EMPLOYEe/////////////

      $sql_tgt="SELECT target,month,year FROM bio_target WHERE  month=".$closedate_row['lastmonth']." AND year= ".$closedate_row['lastyear']."
AND enqid=2
AND task=20
AND team_id=$row[teamid]
"; 
 $result_tgt=DB_query($sql_tgt,$db); 
 $target=DB_fetch_array($result_tgt); 
        
     if($target['target']==""){
         $tgt="Target not defined";
     }else{
         $tgt=$target['target'];
         //$ach_p=($my_row_k[0]/$tgt)*100;
     }  
     
     
     ////GET INCENTIVE POLICY//////
      $sql_ip="SELECT incentive FROM bio_achievementpolicy WHERE designation=9 AND enquiry_type=2 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy WHERE designation=9 AND enquiry_type=2 AND rangefrom < '".$my_row_k[0]."' AND rangeto >= '".$my_row_k[0]."' group by taskid)";
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

        $sql_temp_insert="INSERT INTO bio_temptarget (teamid,task,month,year,ordervalue,eligible_p,incentive,level) VALUES ('".$row['teamid']."',20,
        '".$closedate_row['lastmonth']."','".$closedate_row['lastyear']."','".$my_row_k[0]."','".$row_ip['incentive']."','".$incentive."',1) ";
        

        
        $result_temp=DB_query($sql_temp_insert,$db);
        
           } 

               echo "<tr><td><b>BDM</b></td></tr>";

             /*echo  $sql_get_ro="SELECT bio_emp.reportto FROM  bio_emp,bio_temptarget,bio_teammembers,bio_leadteams WHERE bio_temptarget.teamid=bio_leadteams.teamid 
AND bio_teammembers.teamid=bio_leadteams.teamid GROUP BY bio_leadteams.teamid";*/

/*$sql_get_ro="SELECT bio_leadteams.teamname FROM bio_leadteams,bio_teammembers,bio_emp
WHERE bio_leadteams.teamid=bio_teammembers.teamid
AND bio_teammembers.empid=bio_emp.empid
AND bio_emp.empid=(SELECT bio_temptarget.teamid,bio_emp.reportto FROM  bio_temptarget,bio_teammembers,bio_emp WHERE bio_teammembers.teamid=bio_temptarget.teamid
AND bio_teammembers.empid=bio_emp.empid )     
";
$result_get_ro=DB_query($sql_get_ro,$db);
while($rep_off=DB_fetch_array($result_get_ro))
{
    echo "<tr><td>".$rep_off['teamname']."</td></tr>";
}*/
 
 
 $sql_get_ro="SELECT  bio_temptarget.teamid,bio_emp.reportto FROM  bio_temptarget,bio_teammembers,bio_emp WHERE bio_teammembers.teamid=bio_temptarget.teamid
AND bio_teammembers.empid=bio_emp.empid AND bio_temptarget.level=1  

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
/*COALESCE(SUM(ordervalue)/SUM(target)*100,0) AS ach_p */
                  $sql_bdmtotal="SELECT teamid,SUM(ordervalue) AS sum_order,SUM(target) AS sum_target
FROM bio_temptarget
WHERE month=".$closedate_row['lastmonth']."
AND year=".$closedate_row['lastyear']."
AND teamid IN(SELECT bio_teammembers.teamid FROM bio_teammembers,bio_emp 
WHERE bio_emp.empid=bio_teammembers.empid 
AND bio_emp.reportto IN(SELECT empid FROM bio_teammembers WHERE teamid='".$team_bdm['teamid']."'))";
$result_bdmtotal=DB_query($sql_bdmtotal,$db);
$row_bdmtotal=DB_fetch_array($result_bdmtotal);


////  ACHIEVEMENT PERCENTAGE
         // $ach_p1=($row_bdmtotal['sum_order']/$row_bdmtotal['sum_target'])*100;
          ////GET INCENTIVE POLICY//////
          $sql_ip1="SELECT incentive FROM bio_achievementpolicy WHERE designation=5 AND enquiry_type=2 AND rangefrom < '".$row_bdmtotal['sum_order']."' AND rangeto >= '".$row_bdmtotal['sum_order']."' and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy WHERE designation=5 AND enquiry_type=2 AND rangefrom < '".$row_bdmtotal['sum_order']."' AND rangeto >= '".$row_bdmtotal['sum_order']."' group by taskid)";
          $result_ip1=DB_query($sql_ip1,$db); 
          $row_ip1=DB_fetch_array($result_ip1);
          if($row_ip1['incentive']!=""){$policy=$row_ip1['incentive'];}else{$policy="Not defined";}
          
                    ///CALCULATE INCENTIVE
          
          $incentive_bdm=$row_ip1['incentive']*$row_bdmtotal['sum_order'];  

                    printf("<tr style='background:#F0D1B2'>
            <td cellpading=2 width='150px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td>    
                        </tr>",
        $team_bdm['teamname'],
        $monthName."/".$closedate_row['lastyear'],
        $row_bdmtotal['sum_order'],
        $policy,
        $incentive_bdm
        ); 
        $sql_temp_insert1="INSERT INTO bio_temptarget (teamid,task,month,year,ordervalue,eligible_p,incentive,level) VALUES ('".$team_bdm['teamid']."',20,'".$closedate_row['lastmonth']."','".$closedate_row['lastyear']."','".$row_bdmtotal['sum_order']."',
        '".$row_ip1['incentive']."','".$incentive_bdm."',2) ";
        $result_temp1=DB_query($sql_temp_insert1,$db);
}
 
 echo "<tr><td><b>BH</b></td></tr>";
 
 $sql_get_ro="SELECT  bio_temptarget.teamid,bio_emp.reportto FROM  bio_temptarget,bio_teammembers,bio_emp WHERE bio_teammembers.teamid=bio_temptarget.teamid
AND bio_teammembers.empid=bio_emp.empid  AND  bio_temptarget.level=2  
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

                  $sql_bdmtotal="SELECT teamid,SUM(ordervalue) AS sum_order
FROM bio_temptarget
WHERE month=".$closedate_row['lastmonth']."
AND year=".$closedate_row['lastyear']."
AND teamid IN(SELECT bio_teammembers.teamid FROM bio_teammembers,bio_emp 
WHERE bio_emp.empid=bio_teammembers.empid 
AND bio_emp.reportto IN(SELECT empid FROM bio_teammembers WHERE teamid='".$team_bdm['teamid']."'))";
$result_bdmtotal=DB_query($sql_bdmtotal,$db);
$row_bdmtotal=DB_fetch_array($result_bdmtotal);


////  ACHIEVEMENT PERCENTAGE
         // $ach_p1=($row_bdmtotal['sum_order']/$row_bdmtotal['sum_target'])*100;
          ////GET INCENTIVE POLICY//////
          $sql_ip1="SELECT incentive FROM bio_achievementpolicy WHERE designation=4 AND enquiry_type=2 AND rangefrom < '".$row_bdmtotal['sum_order']."' AND rangeto >= '".$row_bdmtotal['sum_order']."' and effectivedate in (select max(effectivedate) FROM bio_achievementpolicy WHERE designation=4 AND enquiry_type=2 AND rangefrom < '".$row_bdmtotal['sum_order']."' AND rangeto >= '".$row_bdmtotal['sum_order']."' group by taskid)";
          $result_ip1=DB_query($sql_ip1,$db); 
          $row_ip1=DB_fetch_array($result_ip1);
          if($row_ip1['incentive']!=""){$policy=$row_ip1['incentive'];}else{$policy="Not defined";}
          
                    ///CALCULATE INCENTIVE
          
          $incentive_bdm=$row_ip1['incentive']*$row_bdmtotal['sum_order'];  

                    printf("<tr style='background:#F0D1B2'>
            <td cellpading=2 width='150px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td> 
             <td width='100px'>%s</td>   
                        </tr>",
        $team_bdm['teamname'],
        $monthName."/".$closedate_row['lastyear'],
        $row_bdmtotal['sum_order'],
        $policy,
        $incentive_bdm
        ); 
        $sql_temp_insert1="INSERT INTO bio_temptarget (teamid,task,month,year,ordervalue,eligible_p,incentive,level) VALUES ('".$team_bdm['teamid']."',20,'".$closedate_row['lastmonth']."','".$closedate_row['lastyear']."','".$row_bdmtotal['sum_order']."',
        '".$row_ip1['incentive']."','".$incentive_bdm."',3) ";
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
                $sql_iinnce="SELECT c.empname,a.month,a.year,a.ordervalue,a.achivement_p,a.target,a.eligible_p,a.incentive FROM bio_incentive a,bio_emp c,bio_teammembers b WHERE a.teamid=b.teamid AND b.empid=c.empid AND a.task=20 ";

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