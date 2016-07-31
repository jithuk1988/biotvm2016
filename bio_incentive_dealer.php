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
       echo  $sql_insert="INSERT INTO bio_incentive_leadsource(type,enqtype,sourceid,month,year,target,achieved,eligible,incentive)VALUES(2,'".$_POST['enq']."',
       '".$_POST['sourceid'.$i]."','".$month."','".$year."',
         '".$_POST['target'.$i]."','".$_POST['total'.$i]."','".$_POST['eligible'.$i]."','".$_POST['incentive'.$i]."') " ;
         $row_insert=DB_query($sql_insert,$db);
     }
     
   $sql_closingdate="INSERT INTO bio_closingdate (type,enqtype,closedate) VALUES (3,'".$enq."','".$_POST['close_date']."')";
    $result_closedate=DB_query($sql_closingdate,$db);  
}


echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" style="background:#EBEBEB;">';
echo '<br /><br />&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp<a href="bio_incentive_leadsource.php"><b>CHANGE LEADSOURCE OR ENQUIRY TYPE</b></a>';
echo '<input type=hidden name=leadsource value="'.$leadsource.'">';
echo '<input type=hidden name=enq value="'.$enq.'">';
    
    $closedate=DB_query("SELECT MAX(closedate) as lastclose,month(MAX(closedate)) as lastmonth,year(MAX(closedate)) as lastyear FROM  bio_closingdate WHERE type=3 AND  enqtype='".$enq."' ",$db);
 $closedate_row=DB_fetch_array($closedate);
  $last_closedate=ConvertSQLDate($closedate_row['lastclose']);
   $closedate_row['lastmonth'];
   $monthName = date("F", mktime(0, 0, 0, $closedate_row['lastmonth'], 10));                
  
echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";
        echo "<table style='border:1px solid #F0F0F0;width:800px'>";
          if($enq==1){ $d="<legend><h3>DEALER/DOMESTIC INCENTIVES</h3>";} 
      elseif($enq==2){ $d="<legend><h3>DEALER/INSTITUTION INCENTIVES</h3>";}
       elseif($enq==3){ $d="<legend><h3>DEALER/LSGD INCENTIVES</h3>";} 
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
        
 $sql="SELECT custbranch.brname,custbranch.debtorno,SUM(salesorderdetails.quantity * stockitemproperties.value) 
FROM custbranch
INNER JOIN bio_leadsource_dealstaff ON bio_leadsource_dealstaff.sourceid=custbranch.debtorno
INNER JOIN salesorders ON (bio_leadsource_dealstaff.leadid=salesorders.leadid)
INNER JOIN salesorderdetails ON (salesorders.orderno=salesorderdetails.orderno)
INNER JOIN stockitemproperties ON (stockitemproperties.stockid=salesorderdetails.stkcode)
INNER JOIN bio_leads ON (salesorders.leadid=bio_leads.leadid)
WHERE bio_leadsource_dealstaff.sourcetype=15
AND stockitemproperties.stkcatpropid=48
AND bio_leads.enqtypeid='".$enq."'
AND orddate between '".$closedate_row['lastclose']."' AND'".$nowFormat."'
GROUP BY custbranch.brname";
$result=DB_query($sql,$db);


     
     echo $dhead;
          echo '<tr style=background:#A8A4DB>
     <td style="border-bottom:1px solid #0000FF">Name</td>   
                <td style="border-bottom:1px solid #0000FF">Target Month/Year</td>
                <td style="border-bottom:1px solid #0000FF">Target</td>
                <td style="border-bottom:1px solid #000000">No. of CUM</td>
       
                <td style="border-bottom:1px solid #000000">Eligible Unit</td>
                <td style="border-bottom:1px solid #000000">Incentive</td>
                </tr>';  
  $i=0;   
while($row=DB_fetch_array($result)){
    
    /// GET TARGET
    
    $result_target=DB_query("SELECT target FROM  bio_target_leadsources WHERE leadsource=2 AND   enq_type='".$enq."'   AND   month='".$closedate_row['lastmonth']."'      
    AND year='".$closedate_row['lastyear']."' ",$db);
    $target_row=DB_fetch_array($result_target);
    if($target_row['target']!=""){$tgt=$target_row['target'];}else{$tgt="Not Defined";}
    
    //////GET ACHIEVENENT POLICY
    $resul_achp=DB_query("SELECT  bio_achievement_outpolicy.incentive FROM bio_achievement_outpolicy 
WHERE bio_achievement_outpolicy.enq_type='".$enq."' 
AND bio_achievement_outpolicy.leadsource=2 
AND bio_achievement_outpolicy.from< '".$row[2]."'
AND bio_achievement_outpolicy.to>='".$row[2]."'
AND bio_achievement_outpolicy.e_date IN(SELECT MAX(e_date) FROM bio_achievement_outpolicy
WHERE bio_achievement_outpolicy.enq_type='".$enq."' AND bio_achievement_outpolicy.leadsource=2 
AND bio_achievement_outpolicy.from< '".$row[2]."'
AND bio_achievement_outpolicy.to>='".$row[2]."')",$db);
$incentive_policy=DB_fetch_array($resul_achp);

//////////INCENTIVE CALCULATION

$incentive=$incentive_policy[0]*$row[2];

                        printf("<tr style='background:#F0D1B2'>
            <td cellpading=2 width='150px'>%s</td>
             <td width='100px'>%s</td>
             <td width='100px'>%s</td>
               <td width='100px'>%s</td>
               <td width='100px'>%s</td>
                              <td width='100px'>%s</td>
                        </tr>",
        $row['brname'],
        $tgt,
        $monthName."/".$closedate_row['lastyear'],
        $row[2],
        $incentive_policy[0],
        $incentive
        ); 
    $row['debtorno'];
    
    $i++;
    
    echo"<input type=hidden name='incentive".$i."' id='incentive".$i."' value=".$incentive.">";
          echo"<input type=hidden name='eligible".$i."' id='eligible".$i."' value=".$incentive_policy[0].">";
          echo"<input type=hidden name='total".$i."' id='total".$i."' value=".$row[2].">";
          echo"<input type=hidden name='target".$i."' id='target".$i."' value=".$target_row['target'].">";
          echo"<input type=hidden name='sourceid".$i."' id='sourceid".$i."' value=".$row['debtorno'].">";
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
     <td style="border-bottom:1px solid #000000">Name</td>                   
     <td style="border-bottom:1px solid #000000">Contact No.</td>
                <td style="border-bottom:1px solid #000000">month/year</td>              
                <td style="border-bottom:1px solid #000000">Target</td>
                <td style="border-bottom:1px solid #000000">Achieved CUM</td>

                <td style="border-bottom:1px solid #000000">Eligible Unit</td>
                <td style="border-bottom:1px solid #000000">Incentive</td>
                </tr>';
                $sql_iinnce="SELECT bio_incentive_leadsource.sourceid,bio_incentive_leadsource.month,bio_incentive_leadsource.year,bio_incentive_leadsource.target, bio_incentive_leadsource.achieved,bio_incentive_leadsource.eligible,bio_incentive_leadsource.incentive,custbranch.brname,custbranch.phoneno,custbranch.faxno
                from  bio_incentive_leadsource,custbranch
                WHERE bio_incentive_leadsource.enqtype='".$enq."'
                AND bio_incentive_leadsource.type=2
                AND custbranch.debtorno=bio_incentive_leadsource.sourceid
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
        $row['brname'],
        $row['phoneno']."<br />".$row['faxno'],
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
