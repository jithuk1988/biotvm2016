<?php

$PageSecurity = 80;  
include('includes/session.inc');
$title = _('Negotiation Incentive Calculation');  
include('includes/header.inc'); 
    
if(isset($_POST['submit']))
{
    
  //-----BDE-----  
  
  $designationid=9;  
  $sql="SELECT DISTINCT bio_leadtask.teamid 
                   FROM bio_leadtask 
                  WHERE bio_leadtask.leadid IN ( SELECT DISTINCT bio_leadtask.leadid FROM bio_leadtask,bio_leads 
                  WHERE bio_leadtask.teamid IN (SELECT teamid FROM bio_leadteams) 
                    AND bio_leadtask.viewstatus=1
                    AND bio_leads.enqtypeid=2 
                    AND bio_leads.leadstatus in (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
                    AND bio_leads.leadid=bio_leadtask.leadid)
                    AND teamid in(SELECT DISTINCT bio_leadteams.teamid FROM bio_designation,bio_office,bio_leadteams
             INNER JOIN bio_teammembers ON (bio_leadteams.teamid=bio_teammembers.teamid)
             INNER JOIN bio_emp ON (bio_teammembers.empid=bio_emp.empid)
                  WHERE bio_emp.designationid IN (9))";
 
    $result=DB_query($sql,$db);     
  
    $cl=$_POST['close']+1;
 if($cl==13)
    {
        $cl=1;
        $year=$_POST['closeyear']+1;
    }else{
        $year=$_POST['closeyear'];}


        
        
 while($myrow = DB_fetch_array($result)){ 
    
       $sql1="SELECT DISTINCT b.teamid, 
                              f.designationid,
                              a.orderno,
                              c.stkcode,
                              c.quantity,
                              c.unitprice*c.quantity as SP,
                              d.price*c.quantity as AP,
                              c.unitprice*c.quantity-d.price*c.quantity as diff  
                         FROM salesorders a,
                              bio_leadtask b, 
                              salesorderdetails c, 
                              prices d, 
                              bio_teammembers e,
                              bio_emp f, 
                              bio_leads g  
                        WHERE a.leadid=b.leadid 
                          AND a.orderno=c.orderno 
                          AND c.stkcode=d.stockid 
                          AND b.teamid=e.teamid
                          AND e.empid=f.empid
                          AND a.leadid=g.leadid
                          AND g.enqtypeid=2
                          AND a.orddate > d.startdate AND (a.orddate < d.enddate OR d.enddate='0000-00-00') 
                          AND a.orddate BETWEEN (SELECT closedate FROM bio_closingdate 
                        WHERE month( closedate ) ='".$_POST['close']."' 
                          AND year( closedate ) = '".$_POST['closeyear']."') 
                         AND (SELECT closedate FROM bio_closingdate 
                        WHERE month( closedate ) =".$cl." 
                          AND year( closedate ) ='".$year."') group by b.teamid,a.orderno,c.stkcode";

       $result1=DB_query($sql1,$db);  
       $myrow1 = DB_fetch_array($result1);
       
while($myrow1 = DB_fetch_array($result1)){ 
      
       $teamid=$myrow1[teamid];
       $designationid=$myrow1[designationid];
       $orderno=$myrow1[orderno];
       $stockcode=$myrow1[stkcode];
       $quantity=$myrow1[quantity];
       $sellingprice=$myrow1[SP];
       $actualprice=$myrow1[AP];
       $diffprice=$myrow1[diff];
      
      
       $sql_1="SELECT * FROM bio_negotiation_detail WHERE stkcode='".$stockcode."'";
       $result_1=DB_query($sql_1,$db);
       $myrow_1 = DB_num_rows($result_1);
       
       if($myrow_1==0)
       {  
       $sql_d="INSERT INTO bio_negotiation_detail(year,
                                                  month,
                                                  teamid,
                                                  desigid,
                                                  orderno,
                                                  stkcode,
                                                  quantity,
                                                  sellingprice,
                                                  actualprice,
                                                  diffprice) 
                                        VALUES (".$_POST['closeyear'].",
                                                ".$_POST['close'].",
                                                ".$teamid.",
                                                ".$designationid.",
                                                ".$orderno.",
                                                '".$stockcode."',
                                                ".$quantity.",
                                                ".$sellingprice.",
                                                ".$actualprice.",
                                                ".$diffprice.")";
        $result_d=DB_query($sql_d,$db);          
       
 }   
 
 } 
 
       $sql_2="SELECT DISTINCT teamid, 
                               SUM(sellingprice),
                               SUM(actualprice),
                               SUM(diffprice)       
                          FROM bio_negotiation_detail
                         WHERE month=".$_POST['close']."
                           AND year=".$_POST['closeyear']."";
 
       $result_2=DB_query($sql_2,$db);  
       $myrow2 = DB_fetch_array($result_2); 
       
     
       
      $totalsp=$myrow2['SUM(sellingprice)'];
      $totalap=$myrow2['SUM(actualprice)']; 
      $totaldiff=$myrow2['SUM(diffprice)'];

      
      
      $sql_3="SELECT * FROM bio_negotiation WHERE month=".$_POST['close']." AND year=".$_POST['closeyear']."";
      $result_3=DB_query($sql_3,$db);
      $myrow3 = DB_num_rows($result_3);
       
       if($myrow3==0)
       {    
 
       $sql_neg="INSERT INTO bio_negotiation(year,
                                             month,
                                             teamid,
                                             total_sp,
                                             total_ap,
                                             total_diff,
                                             incentive)
                                    VALUES(".$_POST['closeyear'].",
                                           ".$_POST['close'].",
                                           ".$teamid.",
                                           ".$totalsp.",
                                           ".$totalap.",
                                           ".$totaldiff.",
                                           ".$totaldiff."*(SELECT incentive_perc FROM bio_negotiationpolicy 
                                                            WHERE desigid=".$designationid.")/100)";      
                                     
         $result_neg=DB_query($sql_neg,$db);
 
       }

 
 }//-----BDE---whileloop----- 
 
  //-----BDM-----      
  
     $designationidm=5;
    
     $sqlm="SELECT DISTINCT bio_leadtask.teamid 
                       FROM bio_leadtask 
                      WHERE bio_leadtask.leadid IN ( SELECT DISTINCT bio_leadtask.leadid FROM bio_leadtask,bio_leads 
                      WHERE bio_leadtask.teamid IN (SELECT teamid FROM bio_leadteams) 
                        AND bio_leadtask.viewstatus=1
                        AND bio_leads.enqtypeid=2 
                        AND bio_leads.leadstatus in (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
                        AND bio_leads.leadid=bio_leadtask.leadid)
                        AND teamid in(SELECT DISTINCT bio_leadteams.teamid FROM bio_designation,bio_office,bio_leadteams
                 INNER JOIN bio_teammembers ON (bio_leadteams.teamid=bio_teammembers.teamid)
                 INNER JOIN bio_emp ON (bio_teammembers.empid=bio_emp.empid)
                      WHERE bio_emp.designationid IN (5))";
 
      $resultm=DB_query($sqlm,$db); 
 //    echo $countm=DB_num_rows ($resultm);
 
      $c2=$_POST['close']+1;
   if($c2==13)
     {
      $c2=1;
      $year2=$_POST['closeyear']+1;
     }else{
      $year2=$_POST['closeyear'];}
      
    
while($myrowbdm = DB_fetch_array($resultm)){ 


         
     
          $sql_bdmtotal="SELECT teamid,
                                SUM(sellingprice)AS bdesellingprice,
                                SUM(actualprice)AS bdeactualprice,
                                SUM(diffprice) AS bdediffprice
                           FROM bio_negotiation_detail
                          WHERE month=".$_POST['close']."
                            AND year=".$_POST['closeyear']."
                            AND teamid IN(SELECT bio_teammembers.teamid FROM bio_teammembers,bio_emp 
                          WHERE bio_emp.empid=bio_teammembers.empid 
                            AND bio_emp.reportto IN(SELECT empid FROM bio_teammembers WHERE teamid=$myrowbdm[teamid]))";
     
      $result_bdm=DB_query($sql_bdmtotal,$db);
      $myrow9 = DB_fetch_array($result_bdm);
    
      $bdmteamid=$myrowbdm[teamid];
      
      $bdesell=$myrow9['bdesellingprice']; 
      $bdeactual=$myrow9['bdeactualprice'];
      $bdediff=$myrow9['bdediffprice'];
      
      
      
      
      $sql_33="SELECT * FROM bio_negotiation WHERE month=".$_POST['close']." AND year=".$_POST['closeyear']."
      AND teamid=".$bdmteamid."";
      $result_33=DB_query($sql_33,$db);
      $myrow33 = DB_num_rows($result_33);
      
      
      
       if($bdesell!=null AND $bdeactual!=null AND $myrow33==0)
       {    
 
       $sql_bde="INSERT INTO bio_negotiation(year,
                                             month,
                                             teamid,
                                             total_sp,
                                             total_ap,
                                             total_diff,
                                             incentive)
                                    VALUES(".$_POST['closeyear'].",
                                           ".$_POST['close'].",
                                           ".$bdmteamid.",
                                           ".$bdesell.",
                                           ".$bdeactual.",
                                           ".$bdediff.",
                                           ".$bdediff."*(SELECT incentive_perc FROM bio_negotiationpolicy 
                                                            WHERE desigid=".$designationidm.")/100)";      
                                     
         $result_bde=DB_query($sql_bde,$db);    
    
       }
       
       
       
       
      $sql12="SELECT DISTINCT b.teamid, 
                              f.designationid,
                              a.orderno,
                              c.stkcode,
                              c.quantity,
                              c.unitprice*c.quantity as SP,
                              d.price*c.quantity as AP,
                              c.unitprice*c.quantity-d.price*c.quantity as diff  
                         FROM salesorders a,
                              bio_leadtask b, 
                              salesorderdetails c, 
                              prices d, 
                              bio_teammembers e,
                              bio_emp f, 
                              bio_leads g  
                        WHERE a.leadid=b.leadid 
                          AND a.orderno=c.orderno 
                          AND c.stkcode=d.stockid 
                          AND b.teamid=e.teamid
                          AND e.empid=f.empid
                          AND a.leadid=g.leadid
                          AND g.enqtypeid=2
                          AND a.orddate > d.startdate AND (a.orddate < d.enddate OR d.enddate='0000-00-00') 
                          AND a.orddate BETWEEN (SELECT closedate FROM bio_closingdate 
                        WHERE month( closedate ) ='".$_POST['close']."' 
                          AND year( closedate ) = '".$_POST['closeyear']."') 
                          AND (SELECT closedate FROM bio_closingdate 
                        WHERE month( closedate ) =".$c2." 
                          AND year( closedate ) ='".$year2."') group by b.teamid,a.orderno,c.stkcode";

       $result12=DB_query($sql12,$db);  
       $myrow12 = DB_fetch_array($result12);
       

       
 
       
while($myrow12 = DB_fetch_array($result12)){ 
      
       //$teamid1=$myrow12[teamid];
       $designationid1=$myrow12[designationid];
       $orderno1=$myrow12[orderno];
       $stockcode1=$myrow12[stkcode];
       $quantity1=$myrow12[quantity];
       $sellingprice1=$myrow12[SP];
       $actualprice1=$myrow12[AP];
       $diffprice1=$myrow12[diff];
      
      
       $sql_m="SELECT * FROM bio_negotiation_detail_bdm WHERE stkcode='".$stockcode1."'";
       $result_m=DB_query($sql_m,$db);
       $myrow_m = DB_num_rows($result_m);
       
       if($myrow_m==0)
       {  
       $sql_dm="INSERT INTO  bio_negotiation_detail_bdm(year,
                                                        month,
                                                        teamid,
                                                        desigid,
                                                        orderno,
                                                        stkcode,
                                                        quantity,
                                                        sellingprice,
                                                        actualprice,
                                                        diffprice) 
                                              VALUES (".$_POST['closeyear'].",
                                                      ".$_POST['close'].",
                                                      ".$bdmteamid.",
                                                      ".$designationid1.",
                                                      ".$orderno1.",
                                                      '".$stockcode1."',
                                                      ".$quantity1.",
                                                      ".$sellingprice1.",
                                                      ".$actualprice1.",
                                                      ".$diffprice1.")";
        $result_dm=DB_query($sql_dm,$db);          
       
       }   
 
 }       
    
    

 
  }//-----BDM whileloop----- 

    //-----BH-----  
  
     $designationidh=4;
    
     $sqlh="SELECT DISTINCT bio_leadtask.teamid 
                       FROM bio_leadtask 
                      WHERE bio_leadtask.leadid IN ( SELECT DISTINCT bio_leadtask.leadid FROM bio_leadtask,bio_leads 
                      WHERE bio_leadtask.teamid IN (SELECT teamid FROM bio_leadteams) 
                        AND bio_leadtask.viewstatus=1
                        AND bio_leads.enqtypeid=2 
                        AND bio_leads.leadstatus in (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
                        AND bio_leads.leadid=bio_leadtask.leadid)
                        AND teamid in(SELECT DISTINCT bio_leadteams.teamid FROM bio_designation,bio_office,bio_leadteams
                 INNER JOIN bio_teammembers ON (bio_leadteams.teamid=bio_teammembers.teamid)
                 INNER JOIN bio_emp ON (bio_teammembers.empid=bio_emp.empid)
                      WHERE bio_emp.designationid IN (4))";
 
      $resulth=DB_query($sqlh,$db);
      
      
while($myrowbh = DB_fetch_array($resulth)){  
     
      $sql_bhtotal="SELECT teamid,
                           SUM(sellingprice)AS bhsellingprice,
                           SUM(actualprice)AS bhactualprice,
                           SUM(diffprice) AS bhdiffprice
                      FROM bio_negotiation_detail_bdm
                     WHERE month=".$_POST['close']."
                       AND year=".$_POST['closeyear']."
                       AND teamid IN(SELECT bio_teammembers.teamid FROM bio_teammembers,bio_emp 
                     WHERE bio_emp.empid=bio_teammembers.empid 
                       AND bio_emp.reportto IN(SELECT empid FROM bio_teammembers WHERE teamid=$myrowbh[teamid]))";
     
      $result_bh=DB_query($sql_bhtotal,$db);
      $myrow8 = DB_fetch_array($result_bh);
      
      $bhteamid=$myrowbh[teamid];
      
      $bhsell=$myrow8['bhsellingprice']; 
      $bhactual=$myrow8['bhactualprice'];
      $bhdiff=$myrow8['bhdiffprice']; 
      
      $sql_5="SELECT * FROM bio_negotiation WHERE month=".$_POST['close']." AND year=".$_POST['closeyear']."
                                                                            AND teamid=".$bhteamid."";
      $result_5=DB_query($sql_5,$db);
      $myrow5 = DB_num_rows($result_5);
       
       if($bhsell!=null AND $bhactual!=null AND $myrow5==0)
       {    
 
       $sql_bh="INSERT INTO bio_negotiation(year,
                                            month,
                                            teamid,
                                            total_sp,
                                            total_ap,
                                            total_diff,
                                            incentive)
                                   VALUES(".$_POST['closeyear'].",
                                          ".$_POST['close'].",
                                          ".$bhteamid.",
                                          ".$bhsell.",
                                          ".$bhactual.",
                                          ".$bhdiff.",
                                          ".$bhdiff."*(SELECT incentive_perc FROM bio_negotiationpolicy 
                                                            WHERE desigid=".$designationidh.")/100)";      
                                     
         $result_bh=DB_query($sql_bh,$db);    
    
       }
                
      
                           
      
}//-----BH while loop-----         
                           
}//-----submit----   

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
     echo "<legend><h3>Negotiation Incentive</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";

     echo '<tr>
     <td style="border-bottom:1px solid #000000">Name</td>   
                <td style="border-bottom:1px solid #000000">Month</td>
                <td style="border-bottom:1px solid #000000">Year</td>
                <td style="border-bottom:1px solid #000000">Incentive</td>
                </tr>';
                $sql_iinnce="SELECT c.empname,a.month,
                                    a.year,a.incentive 
                               FROM bio_negotiation a,bio_emp c,bio_teammembers b 
                              WHERE a.teamid=b.teamid AND b.empid=c.empid ORDER BY month DESC";
              //$sql_iinnce="SELECT teamid,month,year,ordervalue,achivement_p,target,eligible_P,incentive FROM bio_incentive ORDER BY month DESC"; 
              $result6=DB_query($sql_iinnce,$db); 
              while($row=DB_fetch_array($result6)){
                printf("<tr style='background:#A8A4DB'>
            <td cellpading=2 width='150px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            </tr>",   
            
        $row['empname'],
        $row['month'],
        $row['year'],
        $row['incentive']); 

           }
    
?>