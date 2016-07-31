<?php
  $PageSecurity = 80;  
include('includes/session.inc');
$title = _('Incentive Calculation');  
include('includes/header.inc'); 

if($_GET['ls'])
{
    $leadsource=$_GET['ls'];
    $enq=$_GET['enq'];
}
else
{
    $leadsource=$_POST['leadsource'];
    $enq=$_POST['enq'];
    
}

if(isset($_POST['close_incentive']))
{
     $count=$_POST['count'];
     $month=$_POST['month'];
     $year=$_POST['year'];
     
     for($i=1;$i<=$count;$i++)
     {
         $sql_insert="INSERT INTO bio_incentive_leadsource(type,enqtype,sourceid,month,year,target,achieved,eligible,incentive)VALUES(4,'".$_POST['enq']."',
       '".$_POST['sourceid'.$i]."','".$month."','".$year."',
         '".$_POST['target'.$i]."','".$_POST['total'.$i]."','".$_POST['eligible'.$i]."','".$_POST['incentive'.$i]."') " ;
         $row_insert=DB_query($sql_insert,$db);
     }
     
   $sql_closingdate="INSERT INTO bio_closingdate (type,enqtype,closedate) VALUES (6,'".$enq."','".$_POST['close_date']."')";
    $result_closedate=DB_query($sql_closingdate,$db);  
}


echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" style="background:#EBEBEB;">';
echo '<br /><br />&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp<a href="bio_incentive_leadsource.php"><b>CHANGE LEADSOURCE OR ENQUIRY TYPE</b></a>';
echo '<input type=hidden name=leadsource value="'.$leadsource.'">';
echo '<input type=hidden name=enq value="'.$enq.'">';
    
    $closedate=DB_query("SELECT MAX(closedate) as lastclose,month(MAX(closedate)) as lastmonth,year(MAX(closedate)) as lastyear FROM  bio_closingdate WHERE type=6 AND  enqtype='".$enq."' ",$db);
 $closedate_row=DB_fetch_array($closedate);
  $last_closedate=ConvertSQLDate($closedate_row['lastclose']);
   $closedate_row['lastmonth'];
   $monthName = date("F", mktime(0, 0, 0, $closedate_row['lastmonth'], 10));                
  
echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";
        echo "<table style='border:1px solid #F0F0F0;width:800px'>";
       if($enq==1){ $d="<legend><h3>PROMOTION ORDER/DOMESTIC INCENTIVES</h3>";} 
      elseif($enq==2){ $d="<legend><h3>PROMOTION ORDER/INSTITUTION INCENTIVES</h3>";}
       elseif($enq==3){ $d="<legend><h3>PROMOTION ORDER/LSGD INCENTIVES</h3>";} 
   echo $d;
       if($_POST['closing']!="")
    {
        $nowFormat=FormatDateForSQL($_POST['closing']); 
        $dhead='<tr><td width=150px><b>Last Close Date :</b></td><td><b>'.$last_closedate.'</b></td><td width=150px><b>Closing Date:</b></td><td><b>'.ConvertSQLDate($nowFormat).'</b></td>
  <td><input type=submit name=close_incentive value="CLOSE"></td></tr>' ;
    
    }
    else
    {
         $nowFormat = date('Y-m-d');
         $dhead='<tr><td width=150px><b>Last Close Date :</b></td><td><b>'.$last_closedate.'</b></td><td width=150px><b>Current Date:</b></td><td><b>'.ConvertSQLDate($nowFormat).'</b></td></tr><tr><td width=150px><b>Closing Date:</b></td><td><input type=text name=closing id=closing class=date alt="'.$_SESSION['DefaultDateFormat'].'"></td>
  <td><input type=submit name=submit value="VIEW UP TO CLOSE DATE" onclick= "if(validate()==1)return false;"></td></tr>' ;
    }
    echo "<input type=hidden name=close_date id=close_date value=".$nowFormat.">";
    echo "<input type=hidden name=last_close_date id=last_close_date value=".$last_closedate.">";
        
/*  $sql="SELECT bio_leadsources.teamid,bio_leadteams.teamname,SUM(salesorderdetails.quantity * stockitemproperties.value)
FROM salesorderdetails
INNER JOIN salesorders ON (salesorderdetails.orderno=salesorders.orderno)
INNER JOIN bio_leads ON (salesorders.leadid=bio_leads.leadid)
INNER JOIN stockitemproperties ON (stockitemproperties.stockid=salesorderdetails.stkcode)
INNER JOIN  bio_leadsources ON (bio_leads.sourceid=bio_leadsources.id)
INNER JOIN bio_leadteams ON(bio_leadsources.teamid=bio_leadteams.teamid)
WHERE bio_leadsources.teamid IN (SELECT DISTINCT bio_leadsources.teamid FROM bio_leadsources  WHERE bio_leadsources.sourcetypeid IN (2,3)) 
AND bio_leadsources.sourcetypeid IN(2,3)
AND bio_leads.leadid NOT IN (SELECT bio_leadsource_dealstaff.leadid FROM bio_leadsource_dealstaff)
AND stockitemproperties.stkcatpropid=48
AND stockitemproperties.stockid=salesorderdetails.stkcode
AND bio_leads.enqtypeid='".$enq."'
AND bio_leads.leaddate between '".$closedate_row['lastclose']."' AND'".$nowFormat."'
GROUP BY bio_leadsources.teamid";
$result=DB_query($sql,$db);
*///
$sql="SELECT bio_leads.teamid,bio_leadteams.teamname 
FROM bio_leads,bio_leadsources,bio_leadteams
WHERE bio_leadsources.id=bio_leads.sourceid
AND bio_leads.leadstatus!=20
AND bio_leadsources.sourcetypeid IN (2,3)
AND bio_leads.enqtypeid='".$enq."'
AND bio_leads.leadid NOT IN (SELECT leadid FROM bio_leadsource_dealstaff)
AND bio_leads.leaddate between '".$closedate_row['lastclose']."' AND'".$nowFormat."'
AND bio_leadteams.teamid=bio_leads.teamid
GROUP BY bio_leads.teamid";/**/
$result=DB_query($sql,$db);

     
     echo $dhead;
          echo '<tr style=background:#A8A4DB>
     <td style="border-bottom:1px solid #0000FF">Name</td>   
                <td style="border-bottom:1px solid #0000FF">Target Month/Year</td>
                <td style="border-bottom:1px solid #0000FF">Target</td>
                <td style="border-bottom:1px solid #000000">ORDERED CUM</td>
       
                <td style="border-bottom:1px solid #000000">Eligible Unit</td>
                <td style="border-bottom:1px solid #000000">Order Incentive</td>
                <td style="border-bottom:1px solid #000000">No of leads(Not ordered)</td>
                <td style="border-bottom:1px solid #000000">Lead Incentive</td>
                 <td style="border-bottom:1px solid #000000">Total Incentive</td>
                </tr>';  
  $i=0;   
while($row=DB_fetch_array($result)){
    
    
    $sql1="SELECT SUM(salesorderdetails.quantity * stockitemproperties.value)
FROM salesorderdetails
INNER JOIN salesorders ON (salesorderdetails.orderno=salesorders.orderno)
INNER JOIN bio_leads ON (salesorders.leadid=bio_leads.leadid)
INNER JOIN stockitemproperties ON (stockitemproperties.stockid=salesorderdetails.stkcode)
INNER JOIN  bio_leadsources ON (bio_leads.sourceid=bio_leadsources.id)
WHERE bio_leadsources.teamid IN (SELECT DISTINCT bio_leadsources.teamid FROM bio_leadsources  WHERE bio_leadsources.sourcetypeid IN (2,3)) 
AND bio_leadsources.teamid='".$row['teamid']."'
AND bio_leadsources.sourcetypeid IN(2,3)
AND bio_leads.leadid NOT IN (SELECT bio_leadsource_dealstaff.leadid FROM bio_leadsource_dealstaff)
AND stockitemproperties.stkcatpropid=48
AND stockitemproperties.stockid=salesorderdetails.stkcode
AND bio_leads.enqtypeid='".$enq."'
AND bio_leads.leaddate BETWEEN '".$closedate_row['lastclose']."' AND'".$nowFormat."'";
$result1=DB_query($sql1,$db);
    
   $row1=DB_fetch_array($result1); 
    
    
    
 /*        GET TARGET                             */
    
    $result_target=DB_query("SELECT target FROM  bio_target_leadsources WHERE leadsource=4 AND   enq_type='".$enq."'   AND   month='".$closedate_row['lastmonth']."'      
    AND year='".$closedate_row['lastyear']."' ",$db);
    $target_row=DB_fetch_array($result_target);
    if($target_row['target']!=""){$tgt=$target_row['target'];}else{$tgt="Not Defined";}
    
/*          GET ACHIEVENENT POLICY                                    */
    $resul_achp=DB_query("SELECT  bio_achievement_outpolicy.incentive FROM bio_achievement_outpolicy 
WHERE bio_achievement_outpolicy.enq_type='".$enq."' 
AND bio_achievement_outpolicy.leadsource=4 
AND bio_achievement_outpolicy.from< '".$row1[0]."'
AND bio_achievement_outpolicy.to>='".$row1[0]."'
AND bio_achievement_outpolicy.e_date IN(SELECT MAX(e_date) FROM bio_achievement_outpolicy
WHERE bio_achievement_outpolicy.enq_type='".$enq."' AND bio_achievement_outpolicy.leadsource=4
AND bio_achievement_outpolicy.from< '".$row1[0]."'
AND bio_achievement_outpolicy.to>='".$row1[0]."')",$db);
$incentive_policy=DB_fetch_array($resul_achp);
if($incentive_policy[0]!=""){$ap=$incentive_policy[0];}else{$ap="Not Defined";}

/*INCENTIVE CALCULATION*/
$incentive=$incentive_policy[0]*$row1[0];

/*       LEADS WITHOUT ORDER                                                                                   */
$lead_ins=DB_query("SELECT COUNT(bio_leads.leadid) FROM bio_leads
inner join bio_leadsources on (bio_leads.sourceid=bio_leadsources.id)
inner join bio_cust on bio_leads.cust_id=bio_cust.cust_id
left join debtortrans on bio_cust.debtorno=debtortrans.debtorno
WHERE bio_leads.leadid NOT IN (SELECT leadid FROM salesorders)
AND bio_leads.leadid NOT IN (SELECT leadid FROM bio_leadsource_dealstaff)
AND bio_leads.teamid='".$row['teamid']."'
AND bio_leads.leadstatus!=20  
AND bio_leadsources.sourcetypeid in (2,3)
AND bio_leads.enqtypeid='".$enq."'
AND bio_leads.leaddate between '".$closedate_row['lastclose']."' AND'".$nowFormat."'
AND (bio_leads.advanceamount>=1000 or debtortrans.ovamount<=(-1000))",$db);/**/
$l_inc=DB_fetch_array($lead_ins);

/*     POLICY FOR LEAD WITHOUT ORDER                                                         */

$sql_pwor=DB_query("SELECT  bio_achievement_outpolicy.incentive FROM bio_achievement_outpolicy 
WHERE bio_achievement_outpolicy.enq_type='".$enq."' 
AND bio_achievement_outpolicy.leadsource=5
AND bio_achievement_outpolicy.from< '".$l_inc[0]."'
AND bio_achievement_outpolicy.to>='".$l_inc[0]."'
AND bio_achievement_outpolicy.e_date IN(SELECT MAX(e_date) FROM bio_achievement_outpolicy
WHERE bio_achievement_outpolicy.enq_type='".$enq."' AND bio_achievement_outpolicy.leadsource=5
AND bio_achievement_outpolicy.from< '".$l_inc[0]."'
AND bio_achievement_outpolicy.to>='".$l_inc[0]."')  ",$db);

$pwor=DB_fetch_array($sql_pwor);
$amt=$l_inc[0]*$pwor[0];
$total=$amt + $incentive;

                        printf("<tr style='background:#F0D1B2'>
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
        $row['teamname'],
        $monthName."/".$closedate_row['lastyear'],
        $tgt,
        $row1[0],
        $ap,
        $incentive,
        $l_inc[0],
        $amt,
        $total
        ); 
    //$row['debtorno'];
    
    $i++;
    
    echo"<input type=hidden name='incentive".$i."' id='incentive".$i."' value=".$total.">";
          echo"<input type=hidden name='eligible".$i."' id='eligible".$i."' value=".$incentive_policy[0].">";
          echo"<input type=hidden name='total".$i."' id='total".$i."' value=".$row1[0].">";
          echo"<input type=hidden name='target".$i."' id='target".$i."' value=".$target_row['target'].">";
          echo"<input type=hidden name='sourceid".$i."' id='sourceid".$i."' value=".$row['teamid'].">";
}

      echo'<input type=hidden name=count id=count value="'.$i.'">';
      echo'<input type=hidden name=year id=year value="'.$closedate_row['lastyear'].'">';
      echo'<input type=hidden name=month id=month value="'.$closedate_row['lastmonth'].'">';
          echo "<input type=hidden name=close_date value=".$nowFormat.">"; 


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

  
     echo "<legend><h3>Previous Incentives</h3>"; /* <td style="border-bottom:1px solid #000000">target</td>*/
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";

     echo '<tr>
     <td style="border-bottom:1px solid #000000">Team</td>                   
     <td style="border-bottom:1px solid #000000">Office</td>
                <td style="border-bottom:1px solid #000000">month/year</td>              
                <td style="border-bottom:1px solid #000000">Target</td>
                <td style="border-bottom:1px solid #000000">Achieved CUM</td>

                <td style="border-bottom:1px solid #000000">Eligible Unit</td>
                <td style="border-bottom:1px solid #000000">Incentive</td>
                </tr>';
                $sql_iinnce="SELECT bio_incentive_leadsource.sourceid,bio_incentive_leadsource.month,bio_incentive_leadsource.year,bio_incentive_leadsource.target, bio_incentive_leadsource.achieved,bio_incentive_leadsource.eligible,bio_incentive_leadsource.incentive,bio_leadteams.teamname, bio_office.office
                from  bio_incentive_leadsource,bio_leadteams,bio_office
                WHERE bio_incentive_leadsource.enqtype='".$enq."'
                AND bio_incentive_leadsource.type=4
                AND bio_leadteams.teamid=bio_incentive_leadsource.sourceid
                AND bio_office.id=bio_leadteams.office_id

                ";

                    if($_POST['close']!="" || $_POST['close']!=0)
                    {
                        $sql_iinnce.=" AND bio_incentive_leadsource.month='".$_POST['close']."' ";
                    }
                    if($_POST['closeyear']!="" || $_POST['closeyear']!=0)
                    {
                        $sql_iinnce.=" AND bio_incentive_leadsource.year='".$_POST['closeyear']."' ";
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
                        </tr>",
        $row['teamname'],
        $row['office'],
        date("F", mktime(0, 0, 0, $row['month'], 10))."/".$row['year'],
        $row['target'],
        $row['achieved'],
        $row['eligible'],
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
