<?php

$PageSecurity = 80;  
include('includes/session.inc');
$title = _('Incentive Calculation');  
include('includes/header.inc'); 
if(isset($_POST['submit']))
    {
            
  $sql="SELECT DISTINCT bio_leadtask.teamid FROM bio_leadtask 
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
        WHERE bio_emp.designationid IN (4,5,9))";
    
    $result=DB_query($sql,$db); 
       
    $cl=$_POST['close']+1;
    $bdmordervalue=0;
    $bdmtarget=0;

while($myrow = DB_fetch_array($result)){ 
    
  $sql1="SELECT SUM(unitprice*quantity) FROM salesorderdetails WHERE orderno IN ( SELECT orderno FROM salesorders WHERE leadid IN (SELECT DISTINCT bio_leadtask.leadid
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid=$myrow[0]
AND bio_leadtask.viewstatus=1
AND bio_leads.enqtypeid=2 
AND bio_leads.leadstatus IN (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
AND bio_leads.leadid=bio_leadtask.leadid) 
AND orddate between (SELECT closedate FROM bio_closingdate WHERE month( closedate ) ='".$_POST['close']."' AND year( closedate ) = '".$_POST['closeyear']."')
AND 
(SELECT closedate FROM bio_closingdate WHERE month( closedate ) =".$cl." AND year( closedate ) = '".$_POST['closeyear']."')
) " ;



//echo"<br>";
 $result1=DB_query($sql1,$db); 
 
  $sql_tgt="SELECT target,month,year FROM bio_target,bio_leadtask WHERE  month=".$_POST['close']." AND year= ".$_POST['closeyear']."
AND enqid=2
AND task=20
AND bio_leadtask.teamid=$myrow[0]
"; 
 $result2=DB_query($sql_tgt,$db); 
 $myrow2 = DB_fetch_array($result2); 
 $myrow1 = DB_fetch_array($result1);
 if($myrow2[0]!=0)
 {
$achieve_p=$myrow1[0]*100/$myrow2[0];
 //$sql_rng='SELECT MAX(rangefrom) FROM bio_achievementpolicy WHERE rangefrom<$achieve_p';
     //    echo "<br>";
 }
 else
 {
     $achieve_p=0;
 }
 
  $sql_incrange="SELECT incentive FROM bio_achievementpolicy WHERE rangefrom=(SELECT COALESCE(MAX(rangefrom),0) FROM bio_achievementpolicy WHERE rangefrom<$achieve_p) AND designation=9 AND taskid=1 AND month(effectivedate)=".$_POST['close']." AND year(effectivedate)=".$_POST['closeyear']."";
 //echo"<br>";
 $result3=DB_query($sql_incrange,$db);
$myrow3 = DB_fetch_array($result3);

if($myrow3['incentive']!=null){

 $incentive=$myrow1[0]*$myrow3['incentive']/100;
//echo "<br>";echo $myrow[0].":\t";echo "rangefrom:\t".$achieve_p;echo "\tincentive:\t".$myrow3[incentive]."% \t incentive". $incentive;
$sql_tmid="SELECT * FROM bio_incentive WHERE month=$myrow2[month] AND year=$myrow2[year] AND teamid=$myrow[0]";
$result5=DB_query($sql_tmid,$db);
$myrow5= DB_num_rows($result5);
if($myrow1[0]==NULL)
{
    $orvalue=0;
}
else
{
   $orvalue=$myrow1[0];   
}
if($myrow5>0 || $orvalue==0){}else{ 
    //$bdeeligible_p=COALESCE($myrow3['incentive'],0);
 $sql_incentive="INSERT INTO 
                            bio_incentive (teamid,month,year,ordervalue,achivement_p,target,eligible_P,incentive) 
                            VALUES (".$myrow[0].",
                            ".$myrow2['month'].",
                            ".$myrow2['year'].",
                            ".$orvalue.",                            
                            ".$achieve_p.",
                            ".$myrow2[0].",                            
                            ".$myrow3['incentive'].",
                             ".$incentive.")";
                            $result4=DB_query($sql_incentive,$db);                        
                                                                           
} 
 //new editing for BDM incentive
 $sql_team="SELECT empid FROM bio_teammembers WHERE teamid=$myrow[0]";
 $team=DB_query($sql_team,$db);
  $row0 = DB_fetch_array($team);
 $sql_bdm="SELECT  a.empid,a.empname,a.reportto,b.empname AS BDM 
FROM bio_emp a,bio_emp b WHERE a.reportto=b.empid 
AND a.reportto IN (SELECT empid FROM bio_emp WHERE designationid=5)
AND a.empid=$row0[0]
ORDER BY a.reportto,a.empid";  
     $bdm=DB_query($sql_bdm,$db); 
while($row1 = DB_fetch_array($bdm)) 
   {      
    $empid_arr=Array($row0[0]=>$row1['reportto']); 
     $name=$empid_arr[$row0[0]]; 
     $sql_team_id="SELECT DISTINCT teamid FROM bio_teammembers WHERE empid=$name"; 
     $team_id=DB_query($sql_team_id,$db);
     $row2 = DB_fetch_array($team_id);     
     $sql_bdmntnull="SELECT * FROM bio_incentive WHERE month=$myrow2[month] AND year=$myrow2[year] AND teamid=$row2[0]";
  $bdmntnul=DB_query($sql_bdmntnull,$db);
  $row3= DB_num_rows($bdmntnul);
     
              $sql_bdmtotal="SELECT teamid,SUM(ordervalue)AS sum_order,SUM(target) AS sum_target,COALESCE(SUM(ordervalue)/SUM(target)*100,0) AS ach_p 
FROM bio_incentive
WHERE month=".$_POST['close']."
AND year=".$_POST['closeyear']."
AND teamid IN(select teamid from bio_teammembers where empid in (select empid from bio_emp where reportto in(select empid from bio_teammembers where teamid=$row2[0])))";
$bdmtotal=DB_query($sql_bdmtotal,$db);
    $row4 = DB_fetch_array($bdmtotal);
  // $bdmteamid=$row2[0]."<br>";
  // $row4['teamid']."<br>";
          $bdmordervalue=$row4['sum_order'];
               $bdmtarget=$row4['sum_target'];
            $bdmachievement_p=$row4['ach_p'];
           $bdmmonth=$_POST['close']."<br>";
           $bdmyear=$_POST['closeyear'];
          if($bdmachievement_p<=0 || $bdmachievement_p==NULL){}else{
               $bdmteamid=$row2[0];
              $sql_range="SELECT  MAX(rangefrom) FROM bio_achievementpolicy WHERE rangefrom<".$row4[ach_p]."";      
    $sql_bdmincrange="SELECT incentive FROM bio_achievementpolicy WHERE rangefrom =($sql_range) AND designation=5 AND taskid=1 AND month(effectivedate)=".$_POST['close']." AND year(effectivedate)=".$_POST['closeyear']."";
   
$bdmincetiverange=DB_query($sql_bdmincrange,$db);
$rowbdm= DB_fetch_array($bdmincetiverange);
if($rowbdm['incentive']!=NULL){
   $bdmincentive_p=$rowbdm['incentive']; 
}else{$bdmincentive_p=0;}
   

                                    }    
                      
   }








}
}

if($row3>0 || $bdmincentive_p==0){}else{
    
    $bdmincentive=$bdmordervalue*$rowbdm['incentive']/100; 
     $sql_bdmincentive="INSERT INTO 
                            bio_incentive (teamid,month,year,ordervalue,achivement_p,target,eligible_P,incentive) 
                            VALUES (".$bdmteamid.",
                            ".$_POST['close'].",
                            ".$_POST['closeyear'].",
                            ".$bdmordervalue.",                            
                            ".$bdmachievement_p.",
                            ".$bdmtarget.",                            
                            ".$bdmincentive_p.",
                             ".$bdmincentive.")";
                            $bdminc=DB_query($sql_bdmincentive,$db);                         

}
}      


echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" style="background:#EBEBEB;">';
echo '<table class="selection" style="background:#EBEBEB;width:500px;">'; 
echo '<tr id="tabl" name="tabl"><td>Closing Month*</td><td>Closing Year*</td></tr>';
echo '<tr><td><select name="close" id="close" class style="width:146px" >';
echo '<option value="0"></option>';
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
echo '<td><input type="text" name="closeyear" id="closeyear" class style="width:146px" ></td><td><input type="submit" name="submit" id="submit" value="submit"></td></tr>';
echo '</table>';
echo '</form>' ;

echo "<fieldset style='float:center;width:50%;'>";     
     echo "<legend><h3>BDE Incentive</h3>";
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
                $sql_iinnce="SELECT c.empname,a.month,a.year,a.ordervalue,a.achivement_p,a.target,a.eligible_p,a.incentive FROM bio_incentive a,bio_emp c,bio_teammembers b WHERE a.teamid=b.teamid AND b.empid=c.empid ORDER BY month DESC";
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
        $row['month'],
        $row['year'],
        $row['ordervalue'],
        $row['achivement_p'],
        $row['target'],
        $row['eligible_p'],
        $row['incentive']
        ); 

           }
?>