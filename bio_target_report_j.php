<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Target Report');     
include('includes/header.inc'); 
echo '<center><font style="color: #333;
                background:#fff;
                font-weight:bold;
                letter-spacing:0.10em;
                font-size:16px;
                font-family:Georgia;                             
                text-shadow: 1px 1px 1px #666;">Daily Activity Report</font></center>'; 
                
  
                
 $empid=$_SESSION['empid'];
     $sql_emp1="SELECT * FROM bio_emp
                WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     $employee_arr=array();   
     $sql_drop="DROP TABLE `emptable`";
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
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($_SESSION[empid])";
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
echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
echo '<div id="panel"></div>';  
echo"<fieldset style='width:70%;'>";
echo"<legend><h3>Target Report</h3></legend>";
echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>"; 

  
   echo '<td>Enquiry Type<select name="enquiry" id="enquiry" style="width:120px">';
   echo '<option value=0></option>';  
   $sql_enq="select * from bio_enquirytypes";
   $result_enq=DB_query($sql_enq,$db);
   
   
   while($row2=DB_fetch_array($result_enq))
   {   
       if ($row2['enqtypeid']==$_POST['enquiry'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row2['enqtypeid'] . '">'.$row2['enquirytype'];
       echo '</option>';
   } 
                                                           
   echo '</select></td>';      

    $enqtype=$_POST['enquiry'];
    
    
    
    echo '<td>Report type<select name="Report" id="report" style="width:100px" onchange=reporttype(this.value)>'; 
     echo '<option value=0></option>'; 
     echo '<option value=1>Corporate level report</option>';
     echo '<option value=2>BDM Office level report</option>';
     echo '<option value=3>BDE-Lead generated report</option>';
     echo '<option value=4>BDE-Meeting conducted </option>';
     echo '<option value=5>BDE-Feasibility study conducted</option>'; 
     echo '<option value=6>BDE-Concept proposal submitted</option>'; 
     echo '<option value=7>BDE-Sale order registered</option>';
     echo '<option value=8>BDE-FS charge collected</option>';
     echo '<option value=9>BDE-SO advance collected</option>';
     echo '<option value=10>BDE-Paymanet against supply collected</option>';        
     echo '<option value=11>SO register</option>';        
     echo '<option value=12>Collection statement-Corporate level</option>'; 
     echo '<option value=13>Collection statement-BDM level</option>';
     echo '<option value=14>Collection statement-BDE level</option>';                                                                                           
     echo '</select></td>';
      
     $type=$_POST['Report']; 

  
    //echo"enq=".$enqtype;     
 
echo '<td id=off >Office<select name="Office" id="office" style="width:120px" >';
  echo '<option value=0></option>'; 
   $sql1="select * from bio_office";
   $result1=DB_query($sql1,$db);
  while($row1=DB_fetch_array($result1))
   { 
       if ($row1['id']==$_POST['Office'])
       {
         echo '<option selected value="';
       } else 
       { 
         echo '<option value="'; 
       }
         echo $row1['id'] . '">'.$row1['office'];
         echo '</option>';
   }
    
  $year=Date("Y");   
   $month=Date("m");  
   echo '<td>Year<select name="Year" id="year"  style="width:90px">';  
   echo '<option value=0></option>'; 
 
  for($i=2012;$i<=2050;$i++)
  {
      
      
      if ($i==$year)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $i . '">'.$i;
        echo '</option>';
     
   
  } echo'</select></td>';                              

    echo '<td>Month<select name="From" id="from"  style="width:100px" onchange="showMonth(this.value)">';
    $sql="select * from m_sub_season";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['season_sub_id']==$month)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['season_sub_id'] . '">'.$row['seasonname'];
        echo '</option>';
    }
     echo'</select></td>';
     
    // echo '<td>Report type<select name="Report" id="report" style="width:100px" onchange="showPeriod(this.value)" >'; 
//     echo '<option value=0></option>'; 
//     echo '<option value=1>Corporate level report</option>';
//     echo '<option value=2>BDM Office level report</option>';
//     echo '<option value=3>BDE-Lead generated report</option>';
//     echo '<option value=4>BDE-Meeting conducted </option>';
//     echo '<option value=5>BDE-Feasibility study conducted</option>'; 
//     echo '<option value=6>BDE-Concept proposal submitted</option>'; 
//     echo '<option value=7>BDE-Sale order registered</option>';
//     echo '<option value=8>BDE-FS charge collected</option>';
//     echo '<option value=9>BDE-SO advance collected</option>';
//     echo '<option value=10>BDE-Paymanet against supply collected</option>';        
//     echo '<option value=11>SO register</option>';        
//     echo '<option value=12>Collection statement-Corporate level</option>'; 
//     echo '<option value=13>Collection statement-BDM level</option>';
//     echo '<option value=14>Collection statement-BDE level</option>';                                                                                           
//     echo '</select></td>';
//      
//     $type=$_POST['Report']; 

// 
     echo"<td id=showperiod>";    
     echo'</td>';                 
echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
echo"</tr>";
echo"</table>";
echo"</form>";
echo"</td></tr>" ;

   if($enqtype==2){

 $sql8="SELECT bio_target.team_id,
               bio_target.task,
               bio_target.year,
               bio_target.month,
               bio_target.target,
               bio_designation.designation,
               bio_task.taskid,
               m_sub_season.seasonname,
               bio_leadteams.teamname
         FROM  bio_target,
               bio_teammembers,
               bio_designation,
               bio_emp,
               m_sub_season,
               bio_task,
               bio_leadteams
         WHERE bio_teammembers.empid=bio_emp.empid
           AND bio_target.team_id=bio_teammembers.teamid
           AND bio_target.month=m_sub_season.season_sub_id  
           AND bio_emp.designationid=bio_designation.desgid  
           AND bio_target.task=bio_task.taskid     
           AND bio_target.month=m_sub_season.season_sub_id
           AND bio_target.team_id=bio_leadteams.teamid
           AND bio_target.enqid=2  
           AND bio_target.team_id IN ($team_array) "; 
           
  
   
           
           
           
           
   //  $sql8="SELECT bio_target.team_id,
//               bio_target.task,
//               bio_target.year,
//               bio_target.month,
//               bio_target.target,
//               bio_designation.designation,
//               bio_task.taskid,
//               m_sub_season.seasonname,
//               bio_leadteams.teamname
//         FROM  bio_target,
//               bio_teammembers,
//               bio_designation,
//               bio_emp,
//               m_sub_season,
//               bio_task,
//               bio_leadteams
//         WHERE bio_teammembers.empid=bio_emp.empid
//           AND bio_target.team_id=bio_teammembers.teamid
//           AND bio_target.month=m_sub_season.season_sub_id 
//           AND bio_emp.designationid=bio_designation.desgid 
//           AND bio_target.task=bio_task.taskid    
//           AND bio_target.month=m_sub_season.season_sub_id
//           AND bio_target.team_id=bio_leadteams.teamid
//           AND bio_target.enqid=2 
//           AND bio_target.team_id IN
//           (SELECT teamid FROM bio_teammembers WHERE empid IN
//           (SELECT empid FROM emptable))";    

         if(isset($_POST['filterbut'])){ 
         if (isset($_POST['Enq']))    {      
         if ($_POST['Enq']!=''  && $_POST['Enq']!=0){
         $sql8.=" AND bio_target.enqid=".$_POST['Enq'];
       } 
    }
         if (isset($_POST['Year']))    {      
         if ($_POST['Year']!=''  && $_POST['Enq']!=0){
        $sql8.=" AND bio_target.year=".$_POST['Year'];
       } 
    }
         if (isset($_POST['From']))    {      
         if ($_POST['From']!=''  && $_POST['From']!=0){
        $sql8.=" AND bio_target.month=".$_POST['From'];
       } 
    }
       
      if($_POST['Report']!=1){
       if (isset($_POST['Office']))    { 
       if (($_POST['Office']!='')&&($_POST['Office']!='0')){
        $team_arr1=array(); 
        $sql_team="SELECT bio_teammembers.teamid 
                      FROM bio_teammembers,bio_emp 
                     WHERE bio_emp.designationid=9";
                     
        if($_POST['Office']!=1){
      $sql_team.=" AND bio_emp.offid=".$_POST['Office'];
      }  
      $sql_team.=" AND bio_teammembers.empid=bio_emp.empid";                   
        $result_team=DB_query($sql_team,$db);
        while($row_team=DB_fetch_array($result_team)){
        $team_arr1[]=$row_team['teamid'];  
          }  
        $team_array1=join(",", $team_arr1);  
        $sql8.=" AND bio_target.team_id IN ($team_array1)";  
        $sql_off="SELECT * FROM bio_office WHERE bio_office.id=".$_POST['Office']; 
        $result_off=DB_query($sql_off,$db);
        $myrow_off=DB_fetch_array($result_off);
        $office1=$myrow_off['office'];
        $office=$_POST['Office']; 
        $Currentdate=Date("d/m/Y");
        $datesplit=explode('/',$Currentdate);
        $startdate="01/".$datesplit[1]."/".$datesplit[2]; 
        
        }
    }  
  }    
    if ($_POST['Period']==0 OR $_POST['Period']=='') {
           $Currentdate=Date("d/m/Y");
           $datesplit=explode('/',$Currentdate);
           $startdate="01/".$datesplit[1]."/".$datesplit[2];
           $sql8.= " AND bio_target.year=".$datesplit[2]."
                     AND bio_target.month=".$datesplit[1];
  $heading=" Team from ".$startdate." to ".$Currentdate;         
      }
  if(isset($_POST['Report'])){ 
  if($_POST['Report']==1){       
//   echo$sql8;
  $resultl= DB_query($sql8,$db);
  $leads=0;
  $meetings=0;
  $feasibility=0;
  $conceptprop=0;
  $saleorder=0;
  $fscharge=0;
  $SOcollect=0;
  $payment=0;
//==============================================================================================================//
  $fs_achmnt1=0;
  $cp_achmnt=0;
  $so_achmnt=0;
  $FS_achmnt=0; 
  $SO_achmnt1=0;
  
//================================================================================================================//  
  $leads_per=0;
  $meeting_per=0;
  $feasibility_per=0;
  $cp_per=0;
  $saleorder_per=0;
  $fscharge_per=0;
  $SOcollect_per=0;
  $payment_per=0;
//  
//===================================================================================================================//  





     $employee_arr=join(",", $employee_arr);
     $team_arr=array();
   
   $startdate1=FormatDateForSQL($startdate);
   $Currentdate1=FormatDateForSQL($Currentdate);
 
$myrow=DB_fetch_array($resultl);
     
  $teamid=$myrow['team_id'];
$office1=$myrow_off['office'];         
if($myrow['task']=='0'){
$leads+=$myrow['target'];
$sql4="select userid
          from www_users,
               bio_teammembers 
         where bio_teammembers.teamid in (SELECT teamid from bio_leadteams) 
         AND www_users.empid=bio_teammembers.empid";         
  $result4=DB_query($sql4,$db);
  $myrow4=DB_fetch_array($result4);
  $user=$myrow4['userid'];
//  $sql_leads="select count(*) AS Count from bio_leads 
//              where created_by='".$user."'
//                AND leaddate between '".$startdate1."' AND '".$Currentdate1."'";            /////Error.....
//  $result_leads=DB_query($sql_leads,$db);
//  $myrow_leads=DB_fetch_array($result_leads);
//  $lead_achievmnt+=$myrow_leads['Count'];
 // $lead_achievmnt_arr[]=$myrow_leads['Count']; 
// 
   //$leads_per=$lead_achievmnt/$leads*100;   
// $leads_per=round($lead_achievmnt/$leads*100,2); 

  $sql_leads="select count(*) AS Count from bio_leadtask
                where taskid=".$myrow['task']." 
                 AND viewstatus=1
                 AND taskcompletedstatus=1
                 AND teamid in (SELECT teamid from bio_leadteams)
                  AND assigneddate between '".$startdate1."' AND '".$Currentdate1."'";  
                
 $result_leads=DB_query($sql_leads,$db);
  $myrow_leads=DB_fetch_array($result_leads);
  $lead_achievmnt+=$myrow_leads['Count'];
   $lead_achievmnt_arr[]=$myrow_leads['Count']; 
 
   $leads_per=$lead_achievmnt/$leads*100;   
   $leads_per=round($lead_achievmnt/$leads*100,2); 
  }    
if($myrow['task']==27){    
  $meetings+=$myrow['target'];
  $sql_mtng="select count(*) AS Count from bio_leadtask 
              where taskid=".$myrow['task']."
              AND viewstatus=1
              AND taskcompletedstatus=1
              AND teamid in (SELECT teamid from bio_leadteams)  AND taskcompleteddate between '".$startdate1."' AND '".$Currentdate1."'";  
  $result_mtng=DB_query($sql_mtng,$db);
  $myrow_mtng=DB_fetch_array($result_mtng);
  $meeting_achievmnt+=$myrow_mtng['Count'];   
  $meeting_per=round($meeting_achievmnt/$meetings*100,2);    
  } 
 if($myrow['task']==2){
  $feasibility+=$myrow['target'];
  $sql_fs1="select count(*)AS Count  from bio_leadtask 
              where taskid=".$myrow['task']."
              AND viewstatus=1
              AND taskcompletedstatus=1
              AND teamid in (SELECT teamid from bio_leadteams) 
               AND taskcompleteddate between '".$startdate1."' AND '".$Currentdate1."'";  
 $result_fs1=DB_query($sql_fs1,$db);
 $myrow_fs=DB_fetch_array($result_fs1);
 $fs_achmnt1+=$myrow_fs['Count'];
 $feasibility_per=round($fs_achmnt1/$feasibility*100,2);           
  }      
 if($myrow['task']==3){
  $conceptprop+=$myrow['target'];   
  $sql_cp="select count(*) AS Count from bio_leadtask 
              where taskid=".$myrow['task']."
              AND viewstatus=1
              AND taskcompletedstatus=1
              AND teamid in (SELECT teamid from bio_leadteams) 
               AND taskcompleteddate between '".$startdate1."' AND '".$Currentdate1."'";  ;
  $result_cp=DB_query($sql_cp,$db);
  $myrow_cp=DB_fetch_array($result_cp);
  $cp_achmnt+=$myrow_cp['Count']; 
  $cp_per=round($cp_achmnt/$conceptprop*100,2);                 
  }      
 if($myrow['task']==5){
  $saleorder+=$myrow['target'];  
  $sql_so="select count(*) AS Count from bio_leadtask 
              where taskid=".$myrow['task']."
              AND viewstatus=1
              AND taskcompletedstatus=1
              AND teamid in (SELECT teamid from bio_leadteams) 
               AND taskcompleteddate between '".$startdate1."' AND '".$Currentdate1."'";  
  $result_so=DB_query($sql_so,$db);
  $myrow_so=DB_fetch_array($result_so);
  $so_achmnt+=$myrow_so['Count'];                     
  $saleorder_per=round($so_achmnt/$saleorder*100,2);          
  }        
 if($myrow['task']==28){
  $fscharge+=$myrow['target'];       
  echo$sql_chrge="select Sum(amount) as Amount from bio_advance where paydate between '".$startdate1.
            "' AND '".$Currentdate1."' AND leadid IN(select bio_leads.leadid from bio_leadtask,bio_leads 
              where bio_leadtask.viewstatus=1 and bio_leads.leadid=bio_leadtask.leadid and bio_leads.enqtypeid=2)
              AND (head_id=2 OR head_id=0)                   
               ";           
  $result_ch=DB_query($sql_chrge,$db);
  $myrow_ch=DB_fetch_array($result_ch);
  $FS_achmnt+=$myrow_ch['Amount'];           
  $fscharge_per1=round($FS_achmnt/$fscharge*100,2);
  }    
 if($myrow['task']==20){
  $SOcollect+=$myrow['target'];  
  $sql_SO="select Sum(amount) as Amount  from bio_advance where paydate between '".$startdate1.
            "' AND '".$Currentdate1."' AND leadid IN(select leadid from bio_leadtask 
              where viewstatus=1)
              AND head_id=9
               ";            
  $result_SO=DB_query($sql_SO,$db);
  $myrow_SO=DB_fetch_array($result_SO);
  $SO_achmnt1+=$myrow_SO['Count'];           
  $SOcollect_per=round($SO_achmnt1/$SOcollect*100,2);
  }     
 if($myrow['task']==26){
  $payment+=$myrow['target'];     
  $sql_payment="select Sum(amount) as Amount  from bio_advance where paydate between '".$startdate1.
            "' AND '".$Currentdate1."' AND leadid IN(select leadid from bio_leadtask 
               where viewstatus=1)
                 AND head_id=10
                ";             
  $result_pay=DB_query($sql_payment,$db);
  $myrow_pay=DB_fetch_array($result_pay);
  $pay_achmnt+=$myrow_pay['Count'];          
  $payment_per=round($pay_achmnt/$payment*100,2);           
  }     
  $total_collection=$fscharge+$SOcollect+$payment;  
  $total_achvmnt=$FS_achmnt+$SO_achmnt1+$pay_achmnt;
  if ($total_collection!=0) {
  $total_achvmntper=round($total_achvmnt/$total_collection*100,2);    
 } 

    
 echo "<div id=achievementgrid ><br />";
 echo"<tr><td>";
 echo '<div id="corporate">';  
 echo"<fieldset style='width:90%;'>";
 echo"<legend><h3>Corporate level Report</h3></legend>";
 echo "<table class='selection' style='width:90%' border=1>";
 
 echo '<tr>  <td>'._. '</td>
              <td><b>' . $office1 . '</b></td>
              <td><b>' . $startdate . '</b></td>
              <td><b>' . $Currentdate .'</b></td>
       </tr>';
 
 
  
  echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
        <td align=center><b>' . _('Task description') . '</b></td> 
        <td align=center><b>' . _('Target') . '</b></td>
        <td align=center><b>' . _('Achievement') . '</b></td>
        <td align=center><b>' . _('%Achieved') .'</b></td> 
              </tr>'; 
        echo'<td align=center>1</td>';
        echo'<td align=center>Number of leads generated</td>';
        echo"<td align=center>".$leads."</td>"; 
        echo"<td align=center>".$lead_achievmnt."</td>";  
        echo"<td align=center>".$leads_per.'%'."</td>";  
        
        echo'<tr><td align=center>2</td>';
        echo'<td align=center>Number of meetings conducted</td>';
        echo"<td align=center>".$meetings."</td>";
        echo"<td align=center>".$meeting_achievmnt."</td>";
        echo"<td align=center>".$meeting_per.'%'."</td>";           
        echo"</tr>";
        
        echo'<tr><td align=center>3</td>';
        echo'<td align=center>Number of feasibility study conducted</td>';
        echo"<td align=center>".$feasibility."</td>";
        echo"<td align=center>".$fs_achmnt1."</td>";
        echo"<td align=center>".$feasibility_per.'%'."</td>";     
        echo"</tr>";
        
        echo'<tr><td align=center>4</td>';
        echo'<td align=center>Number of concept proposal given</td>';
        echo"<td align=center>".$conceptprop."</td>";
        echo"<td align=center>".$cp_achmnt."</td>";
        echo"<td align=center>".$cp_per.'%'."</td>";    
        echo"</tr>";
        
        echo'<tr><td align=center>5</td>';
        echo'<td align=center>Number of sale order registered</td>';
        echo"<td align=center>".$saleorder."</td>";
        echo"<td align=center>".$so_achmnt."</td>";
        echo"<td align=center>".$saleorder_per.'%'."</td>";        
        echo"</tr>";
        
        echo'<tr><td align=center>6</td>';
        echo'<td align=center>Feasibility study charges collected</td>'; 
        echo"<td align=center>".$fscharge."</td>"; 
        echo"<td align=center>".$FS_achmnt."</td>"; 
        echo"<td align=center>".$fscharge_per1.'%'."</td>";  
        echo"</tr>";
          
        echo'<tr><td align=center>7</td>';
        echo'<td align=center>SO advance collected</td>';
        echo"<td align=center>".$SOcollect."</td>";
        echo"<td align=center>".$SO_achmnt1."</td>";
        echo"<td align=center>".$SOcollect_per.'%'."</td>";     
        echo"</tr>";
        
        echo'<tr><td align=center>8</td>';
        echo'<td align=center>Payment against supply collected</td>';
        echo"<td align=center>".$payment."</td>"; 
        echo"<td align=center>".$pay_achmnt."</td>";
        echo"<td align=center>".$payment_per.'%'."</td>";                
        echo"</tr>";
        
          
        echo'<tr><td align=center></td>';
        echo'<td align=center><b>Total Collection</b></td>';
        echo"<td align=center><b>".$total_collection."</b></td>";
         echo"<td align=center><b>".$total_achvmnt."</b></td>";
        echo"<td align=center><b>".$total_achvmntper.'%'."</b></td>";                
        echo"</tr>";
                         
        echo"</table>";   
        echo"</fieldset>";
        echo"</div>";
        echo"</tr></td>";
       }
       
       
  if($_POST['Report']==2){ 
       
  $resultl= DB_query($sql8,$db);
  $leads=0;
  $meetings=0;
  $feasibility=0;
  $conceptprop=0;
  $saleorder=0;
  $fscharge=0;
  $SOcollect=0;
  $payment=0;
//==============================================================================================================//
  $fs_achmnt1=0;
  $cp_achmnt=0;
  $so_achmnt=0;
  $FS_achmnt=0; 
  $SO_achmnt1=0;
$meeting_achievmnt=0;



//================================================================================================================//  
  $leads_per=0;
  $meeting_per=0;
  $feasibility_per=0;
  $cp_per=0;
  $saleorder_per=0;
  $fscharge_per=0;
  $SOcollect_per=0;
  $payment_per=0;
  
//===================================================================================================================// 

   $leads_arr=array();
   $lead_achievmnt_arr=array();
  
   $meetings_arr=array();
   $meeting_achvmnt_arr=array();
  
   $feasibility_arr=array();
   $feasibility_achmt_arr=array();
  
   $cp_arr=array();
   $cp_achmt_arr=array();
   
   $saleorder_arr=array();   
   $saleorder_achmt_arr=array();    
  
   $fscharge_arr=array();   
   $fscharge_achmt_arr=array();
   
   $SOcollect_arr=array();   
   $SOcollect_achmt_arr=array(); 
   
   $payment_arr=array();   
   $payment_achmt_arr=array();        
//===================================================================================================================//

 
  
   
   $startdate1=FormatDateForSQL($startdate);
   $Currentdate1=FormatDateForSQL($Currentdate);
 
while($myrow=DB_fetch_array($resultl))
  {
      
$office1=$myrow_off['office'];         
if($myrow['task']=='0'){
  $leads+=$myrow['target'];
  $leads_arr[]=$myrow['target'];
  $sql4="select userid
          from www_users,
               bio_teammembers 
         where bio_teammembers.teamid=".$myrow['team_id'].
         " AND www_users.empid=bio_teammembers.empid";         
  $result4=DB_query($sql4,$db);
  $myrow4=DB_fetch_array($result4);
  $user=$myrow4['userid'];
  $sql_leads="select count(*) AS Count from bio_leads 
              where created_by='".$user."'
                AND leaddate between '".$startdate1."' AND '".$Currentdate1."'";
  $result_leads=DB_query($sql_leads,$db);
  $myrow_leads=DB_fetch_array($result_leads);
  $lead_achievmnt+=$myrow_leads['Count'];
  $lead_achievmnt_arr[]=$myrow_leads['Count']; 
 
   $leads_per=$lead_achievmnt/$leads*100;   
   $leads_per=round($lead_achievmnt/$leads*100,2); 

 
       
  }    
if($myrow['task']==27){    
  $meetings+=$myrow['target'];
  $meetings_arr[]=$myrow['target'];    
 $sql_mtng="select count(*) AS Count from bio_leadtask 
              where taskid=".$myrow['task']."
              AND viewstatus=1
              AND taskcompletedstatus=1
              AND teamid=".$myrow['team_id'].
              " AND taskcompleteddate between '".$startdate1."' AND '".$Currentdate1."'";  
  $result_mtng=DB_query($sql_mtng,$db);
  $myrow_mtng=DB_fetch_array($result_mtng);
  $meeting_achievmnt+=$myrow_mtng['Count'];   
  $meeting_achvmnt_arr[]=$myrow_mtng['Count'];
 $meeting_per=$meeting_achievmnt/$meetings*100; 
  $meeting_per=round($meeting_achievmnt/$meetings*100,2);    
  } 
 if($myrow['task']==2){
  $feasibility+=$myrow['target'];
  $feasibility_arr[]=$myrow['target'];  
  $sql_fs1="select count(*)AS Count  from bio_leadtask 
              where taskid=".$myrow['task']."
              AND viewstatus=1
              AND taskcompletedstatus=1
              AND teamid=".$myrow['team_id']. 
              " AND taskcompleteddate between '".$startdate1."' AND '".$Currentdate1."'";  
 $result_fs1=DB_query($sql_fs1,$db);
 $myrow_fs=DB_fetch_array($result_fs1);
 $fs_achmnt1+=$myrow_fs['Count'];
 $feasibility_achmt_arr[]=$myrow_fs['Count']; 
 $feasibility_per=$fs_achmnt1/$feasibility*100;
 $feasibility_per=round($fs_achmnt1/$feasibility*100,2);           
  }
        
 if($myrow['task']==3){
  $conceptprop+=$myrow['target']; 
  $cp_arr[]=$myrow['target'];   
  $sql_cp="select count(*) AS Count from bio_leadtask 
              where taskid=".$myrow['task']."
              AND viewstatus=1
              AND taskcompletedstatus=1
              AND teamid=".$myrow['team_id']. 
              " AND taskcompleteddate between '".$startdate1."' AND '".$Currentdate1."'";  ;
  $result_cp=DB_query($sql_cp,$db);
  $myrow_cp=DB_fetch_array($result_cp);
  $cp_achmnt+=$myrow_cp['Count']; 
  $cp_achmt_arr[]=$myrow_cp['Count'];
  $cp_per=$cp_achmnt/$conceptprop*100;                  
  $cp_per=round($cp_achmnt/$conceptprop*100,2);                 
  }      
 if($myrow['task']==5){
  $saleorder+=$myrow['target']; 
  $saleorder_arr[]=$myrow['target'];    
  $sql_so="select count(*) AS Count from bio_leadtask 
              where taskid=".$myrow['task']."
              AND viewstatus=1
              AND taskcompletedstatus=1
              AND teamid=".$myrow['team_id']. 
              " AND taskcompleteddate between '".$startdate1."' AND '".$Currentdate1."'";  
  $result_so=DB_query($sql_so,$db);
  $myrow_so=DB_fetch_array($result_so);
  $so_achmnt+=$myrow_so['Count'];           
  $saleorder_achmt_arr[]=$myrow_cp['Count']; 
  $saleorder_per=$so_achmnt/$saleorder*100;           
  $saleorder_per=round($so_achmnt/$saleorder*100,2);          
  }        
 if($myrow['task']==28){
  $fscharge+=$myrow['target'];     
  $fscharge_arr[]=$myrow['target'];     
  
  $sql_chrge="select Sum(amount) as Amount  from bio_advance where paydate between '".$startdate1.
            "' AND '".$Currentdate1."' AND leadid IN(select leadid from bio_leadtask 
              where viewstatus=1
              AND (head_id=2 OR head_id=0)    
              AND teamid=".$myrow['team_id'].")";           
  $result_ch=DB_query($sql_chrge,$db);
  $myrow_ch=DB_fetch_array($result_ch);
  $FS_achmnt+=$myrow_ch['Amount'];           
  $fscharge_achmt_arr[]=$myrow_ch['Amount'];  
  $fscharge_per1=round($FS_achmnt/$fscharge*100,2);
}     
 if($myrow['task']==20){
  $SOcollect+=$myrow['target'];
  $SOcollect_arr[]=$myrow['target'];      
  $sql_SO="select Sum(amount) as Amount  from bio_advance where paydate between '".$startdate1.
            "' AND '".$Currentdate1."' AND leadid IN(select leadid from bio_leadtask 
              where viewstatus=1
               AND head_id=9    
              AND teamid=".$myrow['team_id'].")";            
  $result_SO=DB_query($sql_SO,$db);
  $myrow_SO=DB_fetch_array($result_SO);
  $SO_achmnt1+=$myrow_SO['Amount'];           
  $SOcollect_achmt_arr[]=$myrow_SO['Amount'];
  $SOcollect_per=round($SO_achmnt1/$SOcollect*100,2);
  
   $SOcollect_per=$SO_achmnt1/$SOcollect*100;                                
  }     
 if($myrow['task']==26){
  $payment+=$myrow['target'];
  $payment_arr[]=$myrow['target'];      
  $sql_payment="select Sum(amount) as Amount  from bio_advance where paydate between '".$startdate1.
            "' AND '".$Currentdate1."' AND leadid IN(select leadid from bio_leadtask 
               where viewstatus=1
                AND head_id=10    
               AND teamid=".$myrow['team_id'].")";             
  $result_pay=DB_query($sql_payment,$db);
  $myrow_pay=DB_fetch_array($result_pay);
  $pay_achmnt+=$myrow_pay['Amount'];           
  $payment_achmt_arr[]=$myrow_pay['Amount'];
  
  $payment_per=round($pay_achmnt/$payment*100,2);           
   $payment_per=$pay_achmnt/$payment*100;                  
  }     
 //$total_collection1=$fscharge+$SOcollect+$payment;  
// $total_achvmnt1=$FS_achmnt+$SO_achmnt1+$pay_achmnt;
// 
//  if ($total_collection!=0) {
// 
// $total_achvmntper1=round($total_achvmnt1/$total_collection1*100,2);    
// }

}
     
 echo"<tr><td>";
 echo '<div id="officelevel"></div>';  
 echo"<fieldset style='width:90%;'>";
 echo"<legend><h3>BDM office level Report</h3></legend>";

 echo"<table style='border:1px solid #F0F0F0;width:100%'>";
 echo "<table class='selection' style='width:90%' border=1>";
 
 echo '<tr>  <td>'._. '</td>
              <td><b>' .'BDM' .'-'  . $office1 . '</b></td>
              <td><b>' .$startdate . '</b></td>
              <td><b>' . $Currentdate .'</b></td>
  </tr>'; 
   
             if($_POST['Office']==0){
                              $sql8="select count(*) AS Count from bio_emp where offid IN(select id from bio_office)";
                 
             }    else{
//   $sql8="select count(*) AS Count from bio_emp where offid=".$_POST['Office'].
//                 " AND designationid=9";               } 
//   $result8=DB_query($sql8,$db);
//   $myrow8=DB_fetch_array($result8);
//   $BDE_count=$myrow8['Count'];             
 
   $sql8="select empname from bio_emp where offid=".$_POST['Office']." AND designationid=9";
   $result8=DB_query($sql8,$db);                                                              }
 
   $BDE_count1=$myrow8['empname'];             
   $BDE_count=DB_num_rows($result8);
                                        

   
    

 echo ' <tr>  <td align=center rowspan=2><b>' . _('Slno') . '</b></td> 
       <td align=center rowspan=2><b>' . _('Task description') . '</b></td>';
 //for($i=1;$i<=$BDE_count;$i++){   
 while($myrow8=DB_fetch_array($result8))             {
 echo '<td align=center colspan=2><b>' .$myrow8['empname']. '</b></td>';      
 }
              
 echo ' </tr>';
             
             
 echo'<tr>';
 
 for($i=1;$i<=$BDE_count;$i++){            
 echo'<td><b>' . _('Target'). '</b></td><td><b>'. _('Achieved') .'</b></td>';              
 }
 echo ' </tr>';         
                                    
 
                             
                           
 print_r($leads_arr); 
 print_r($meetings_arr);      
 print_r($feasibility_arr);       
 print_r($cp_arr);        
 print_r($saleorder_arr); 
 print_r($fscharge_arr);   
 print_r($SOcollect_arr);
 print_r($payment_arr);        
             
            
  echo'<tr><td align=center>1</td>';
  echo'<td align=center>Number of leads generated</td>';
  for($i=0;$i<$BDE_count;$i++){ 
  echo"<td align=center>".$leads_arr[$i]."</td>";
  echo"<td align=center>".$lead_achievmnt_arr[$i]."</td>";
  $target_leads+=$leads_arr[$i];
  $achvmnt_leads+=$lead_achievmnt_arr[$i];
       }
  $acvmnt_per_leads=$achvmnt_leads/$target_leads*100; 
       echo"<td align=center>".$acvmnt_per_leads.'%'."</td>";
       
       
       echo'<tr><td align=center>2</td>';
       echo'<td align=center>Number of meetings conducted</td>'; 
       for($i=0;$i<$BDE_count;$i++){ 
       echo"<td align=center>".$meetings_arr[$i]."</td>";
       echo"<td align=center>".$meeting_achvmnt_arr[$i]."</td>";
      $target_meetings+=$meetings_arr[$i];
      $achvmnt_meeting+=$meeting_achvmnt_arr[$i]; 
       }
      $acvmnt_per_meeting=$achvmnt_meeting/$target_meetings*100;
      echo"<td align=center>".$acvmnt_per_meeting.'%'."</td>";
        
   
       echo'<tr><td align=center>3</td>';
       echo'<td align=center>Number of feasibility study conducted</td>';    
       for($i=0;$i<$BDE_count;$i++){ 
       echo"<td align=center>".$feasibility_arr[$i]."</td>";
       echo"<td align=center>".$feasibility_achmt_arr[$i]."</td>";
      $target_feasibility+=$feasibility_arr[$i];
      $achvmnt_feasibility+=$feasibility_achmt_arr[$i]; 
       }
      $acvmnt_per_feasibility=$achvmnt_feasibility/$target_feasibility*100;  
       echo"<td align=center>".$acvmnt_per_feasibility.'%'."</td>"; 
        
 
       echo'<tr><td align=center>4</td>';
       echo'<td align=center>Number of concept proposal given</td>';  
       for($i=0;$i<$BDE_count;$i++){ 
       echo"<td align=center>".$cp_arr[$i]."</td>";
       echo"<td align=center>".$cp_achmt_arr[$i]."</td>";
       $target_cp+=$cp_arr[$i];
       $achvmnt_cp+=$cp_achmt_arr[$i]; 
       }
      $acvmnt_per_cp=$achvmnt_cp/$target_cp*100;         
      echo"<td align=center>".$acvmnt_per_cp.'%'."</td>";
         
       echo'<tr><td align=center>5</td>';
       echo'<td align=center>Number of sale order registered</td>';
       for($i=0;$i<$BDE_count;$i++){ 
       echo"<td align=center>".$saleorder_arr[$i]."</td>";
       echo"<td align=center>".$saleorder_achmt_arr[$i]."</td>";
      $target_so+=$saleorder_arr[$i];
      $achvmnt_so+=$saleorder_achmt_arr[$i];    
       }
        $acvmnt_per_so=$achvmnt_so/$target_so*100;           
       echo"<td align=center>".$acvmnt_per_so.'%'."</td>";
                                                                                          
       echo'<tr><td align=center>6</td>';
       echo'<td align=center>Feasibility study charges collected</td>';   
       for($i=0;$i<$BDE_count;$i++){ 
       echo"<td align=center>".$fscharge_arr[$i]."</td>";
       echo"<td align=center>".$fscharge_achmt_arr[$i]."</td>";
       
      $target_fs1+=$fscharge_arr[$i];
      $achvmnt_fs+=$fscharge_achmt_arr[$i];     
       
       }
      $acvmnt_per_fs=$achvmnt_fs/$target_fs1*100;            
       
       echo"<td align=center>".$acvmnt_per_fs.'%'."</td>";
        
       echo'<tr><td align=center>7</td>';
       echo'<td align=center>SO advance collected</td>';   
       for($i=0;$i<$BDE_count;$i++){ 
       echo"<td align=center>".$SOcollect_arr [$i]."</td>";
       echo"<td align=center>".$SOcollect_achmt_arr[$i]."</td>";
        $target_SOR+=$SOcollect_arr[$i];
      $achvmnt_SOR+=$SOcollect_achmt_arr[$i];
       }
         $acvmnt_per_SOR=$achvmnt_SOR/$target_SOR*100;             
       echo"<td align=center>".$acvmnt_per_SOR.'%'."</td>"; 
        
          
       echo'<tr><td align=center>8</td>';
       echo'<td align=center>Payment against supply collected</td>';
       for($i=0;$i<$BDE_count;$i++){ 
       echo"<td align=center>".$payment_arr [$i]."</td>";
       echo"<td align=center>".$payment_achmt_arr[$i]."</td>";
       $target_payment+=$payment_arr[$i];
       $achvmnt_payment+=$payment_achmt_arr[$i]; 
       }
      
       $acvmnt_per_payment=$achvmnt_payment/$target_payment*100;               
       echo"<td align=center>".$acvmnt_per_payment.'%'."</td>"; 
       echo'<tr><td align=center></td>';
       echo'<td align=center><b>Total Collection</b></td>';
       echo'<td align=center colspan=4><b>Total Target</b>'; 
       $targettotal=$target_fs1+$target_SOR+$target_payment; 
       echo'<b>'.'-'.$targettotal.'</b></td>';
       echo'<td align=center colspan=4><b>Total Achievement</b>';         
       $achmnttotal1=$achvmnt_fs+$achvmnt_SOR+$achvmnt_payment;                 
       echo'<b>'.'-'.$achmnttotal1.'</b></td>';
       echo'<td align=center colspan=6><b>Total Achievement Percentage</b>';          
       if($targettotal!=0){     
      $collection=round($achmnttotal1/$targettotal*100,2);        
      } 
       echo'<b>'.'-'.$collection.'%'.'</b></td>';                     
       echo"</tr>";
 
echo"</table>";   
echo"</fieldset>";
echo"</div>";
echo"</tr></td>";       
   } 
   
if($_POST['Report']==3){
 
  
 $startdate1=FormatDateForSQL($startdate);
 $Currentdate1=FormatDateForSQL($Currentdate);
 $resultl= DB_query($sql8,$db);   

echo"<tr><td>"; 
echo"<fieldset style='width:90%;'>";      
echo"<legend><h3>BDE level Report</h3></legend>";    
  $sql9="select count(*) AS Count from bio_emp where offid=".$office.
                 " AND designationid=9";
  $result9=DB_query($sql9,$db);
  $myrow8=DB_fetch_array($result9);
  $BDE_count=$myrow8['Count'];                
 echo "<table class='selection' style='width:90%' border=1>";       
 echo '<tr>  <td>'._. '</td>
              <td><b>' .'BDE' .'-'  . $office1 . '</b></td>
              <td><b>' .$startdate . '</b></td>
              <td><b>' . $Currentdate .'</b></td>
       </tr>';  
       
             if($_POST['Office']==0){
                              $sql8="select count(*) AS Count from bio_emp where offid IN(select id from bio_office)";
                 
             }    else {
                 
   $sql_task="select count(*) AS Count from bio_emp where offid=".$_POST['Office']." AND designationid=9";  
   $result_task=DB_query($sql_task,$db); 
   $myrow_task=DB_fetch_array($result_task);
   $BDE_count=$myrow_task['Count']; 
 



  echo $sql_cust="select bio_cust.contactperson,
                bio_cust.custmob,
                bio_cust.area1,
                bio_cust.custname,
                bio_leadtask.duedate,
                bio_leadtask.remarks,
                bio_enquirytypes.enquirytype   
            from bio_leads,
                 bio_cust,
                 bio_leadtask,
                 bio_enquirytypes 
            where bio_leadtask.leadid=bio_leads.leadid 
              AND bio_cust.cust_id=bio_leads.cust_id 
              AND bio_leadtask.taskid=18
              AND bio_leadtask.viewstatus=1
              AND bio_leadtask.taskcompletedstatus=0 
              AND bio_enquirytypes.enqtypeid=bio_leads.enqtypeid 
              AND bio_leadtask.teamid IN ($team_array1)
              AND bio_leadtask.assigneddate between '".$startdate1."' AND '".$Currentdate1."'";   } 
  $result_cust=DB_query($sql_cust,$db);     
                                        
  $custname=$myrow_cust['contactperson'];
  $phone=$myrow_cust['custmob'];
  $place=$myrow_cust['area1']; 
  $nextmeeting1=ConvertSQLDate($myrow_cust['duedate']);
  $clienttype=$myrow_cust['enquirytype'];
  $remarks=$myrow_cust['remarks'];             
                                  
   echo "<table class='selection' style='width:90%'>";       
   echo '<tr><td align=center><b>' . _('Lead Generated') . '</b></td></tr>';     
   echo'</table>';    
   echo "<table class='selection' style='width:90%' border=1>";
   echo '<td align=center><b>' . _('Slno') . '</b></td>    
        <td align=center><b>' . _('Customer name') . '</b></td> 
        <td align=center><b>' . _('Place') . '</b></td>        
        <td align=center><b>' . _('Phone No') . '</b></td>
        <td align=center><b>' . _('Client Type') . '</b></td>
        <td align=center><b>' . _('Next meeting') .'</b></td> 
        <td align=center><b>' . _('Remark') .'</b></td>   
        </tr>';                
 
     $i=1;
//  for($i=1;$i<=$BDE_count;$i++) {         
//  echo '<tr><td align=center><b>' . _($i) . '</b></td>';   

  while($myrow_cust=DB_fetch_array($result_cust))     
  {
     
  echo '<td>'.$i++.'</td>';     
 echo '<td align=center><b>' .$myrow_cust['contactperson']. '</b></td>'; 
 echo '<td align=center><b>' .$place. '</b></td>'; 
  echo '<td align=center><b>' .$myrow_cust['custmob']. '</b></td>';
  echo '<td align=center><b>' .$myrow_cust['enquirytype']. '</b></td>'; 
  echo '<td align=center><b>' .ConvertSQLDate($myrow_cust['duedate']). '</b></td>';
  echo '<td align=center><b>' .$myrow_cust['remarks']. '</b></td>'; 
                  
 echo'</tr>';
       
 }
//    } 
    
    
   echo"</tr>" ;        
      }     
    }           
 }


//echo '<div id="BDElevel"></div>';  

// 
//    
// 
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        
//        
// echo "<table class='selection' style='width:90%'>";      
// echo '<tr><td align=center><b>' . _('Meeting conducted') . '</b></td></tr>';     
// echo'</table>';
//    
// echo "<table class='selection' style='width:90%' border=1>";           
// echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Customer name') . '</b></td> 
//        <td align=center><b>' . _('Target date') . '</b></td>
//        <td align=center><b>' . _('Status') . '</b></td>
//        <td align=center><b>' . _('Next action') .'</b></td> 
//        <td align=center><b>' . _('Remark') .'</b></td>   
//        </tr>';  
//    
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>'; 
//        
// echo "<table class='selection' style='width:90%'>";       
// echo '<tr><td align=center><b>' . _('Feasibility study conducted') . '</b></td></tr>';     
// echo'</table>';
//    
// echo "<table class='selection' style='width:90%' border=1>";           
// echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Customer name') . '</b></td> 
//        <td align=center><b>' . _('Target date') . '</b></td>
//        <td align=center><b>' . _('Status') . '</b></td>
//        <td align=center><b>' . _('Budget amount') .'</b></td> 
//        <td align=center><b>' . _('Remark') .'</b></td>   
//        </tr>';  
//    
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';  
//            
//  echo "<table class='selection' style='width:90%'>";       
//  echo '<tr><td align=center><b>' . _('Concept proposal submitted') . '</b></td></tr>';     
//  echo'</table>';
//    
//  echo "<table class='selection' style='width:90%' border=1>";           
//  echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Customer name') . '</b></td> 
//        <td align=center><b>' . _('Plant type') . '</b></td>
//        <td align=center><b>' . _('Value') . '</b></td>
//        <td align=center><b>' . _('Next meeting') .'</b></td> 
//        <td align=center><b>' . _('Remark') .'</b></td>   
//        </tr>';  
//    
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';             
//        
// echo "<table class='selection' style='width:90%'>";       
// echo '<tr><td align=center><b>' . _('Sale order registered') . '</b></td></tr>';     
// echo'</table>';
//    
//  echo "<table class='selection' style='width:90%' border=1>";           
//  echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Customer name') . '</b></td> 
//        <td align=center><b>' . _('Ph No') . '</b></td>
//        <td align=center><b>' . _('Order value') . '</b></td>
//        <td align=center><b>' . _('Plant type') .'</b></td> 
//        <td align=center><b>' . _('Discount%') .'</b></td>   
//        </tr>';  
//    
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';   
//        
// echo "<table class='selection' style='width:90%'>";       
// echo '<tr><td align=center><b>' . _('Feasibility study charges collected') . '</b></td></tr>';     
// echo'</table>';
//    
// echo "<table class='selection' style='width:90%' border=1>";           
// echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Customer name') . '</b></td> 
//        <td align=center><b>' . _('Target date') . '</b></td>
//        <td align=center><b>' . _('Target amount') . '</b></td>
//        <td align=center><b>' . _('Collection amount') .'</b></td> 
//        <td align=center><b>' . _('Remark') .'</b></td>   
//        </tr>';  
//    
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';       
//                              
// echo "<table class='selection' style='width:90%'>";       
// echo '<tr><td align=center><b>' . _('Sale order advance collected') . '</b></td></tr>';     
// echo'</table>';
//    
// echo "<table class='selection' style='width:90%' border=1>";           
// echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Customer name') . '</b></td> 
//        <td align=center><b>' . _('Target date') . '</b></td>
//        <td align=center><b>' . _('Target amount') . '</b></td>
//        <td align=center><b>' . _('Collection amount') .'</b></td> 
//        <td align=center><b>' . _('Remark') .'</b></td>   
//        </tr>';  
//    
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>'; 
//        
// echo "<table class='selection' style='width:90%'>";       
// echo '<tr><td align=center><b>' . _('Payment against supply collected') . '</b></td></tr>';     
// echo'</table>';
//    
//    echo "<table class='selection' style='width:90%' border=1>";           
//    echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Customer name') . '</b></td> 
//        <td align=center><b>' . _('Target date') . '</b></td>
//        <td align=center><b>' . _('Target amount') . '</b></td>
//        <td align=center><b>' . _('Collection amount') .'</b></td> 
//        <td align=center><b>' . _('Remark') .'</b></td>   
//        </tr>';  
//    
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';               
//  
//        echo"</table>";   
//        echo"</fieldset>";
//        echo"</div>";
//        echo"</tr></td>";
//        
//  echo"<tr><td>";
//  echo '<div id="SO report"></div>';  
//  echo"<fieldset style='width:90%;'>";
//  echo"<legend><h3>Sale order register</h3></legend>";

//   echo "<table class='selection' style='width:90%'>";       
//   echo '<tr><td align=center><b>' . _('Sale order register') . '</b></td></tr>';     
//   echo'</table>';
//    
//   echo "<table class='selection' style='width:90%' border=1>";       
//   echo '<tr> <td>'._. '</td>
//              <td><b>' .'BDM' .'-'  . $office1 . '</b></td>
//              <td><b>' .$startdate . '</b></td>
//              <td><b>' . $Currentdate .'</b></td>            
//              </tr>'; 
//  
//   echo "<table class='selection' style='width:90%' border=1>";           

//   echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Customer name') . '</b></td> 
//        <td align=center><b>' . _('Phone') . '</b></td>
//        <td align=center><b>' . _('Value') . '</b></td>
//        <td align=center><b>' . _('Plant ') .'</b></td> 
//        <td align=center><b>' . _('Discount%') .'</b></td>   
//        <td align=center><b>' . _('BDE/CCE') .'</b></td>   
//        </tr>';  
//  
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';      
//        echo'<td align=center></td>';          
//        echo'<tr><td align=center><b>'.'</b></td> 
//             <td align=center><b>' . _('TOTAL value') .'</b></td></tr>';    
//                      
//        echo"</table>";   
//        echo"</fieldset>";
//        echo"</div>";
//        echo"</tr></td>";


//echo"<tr><td>";
//echo '<div id="Collection report"></div>';  
//echo"<fieldset style='width:90%;'>";
//echo"<legend><h3>Collection statement</h3></legend>";     
//             
//    echo "<table class='selection' style='width:90%'>";       
//    echo '<tr><td align=center><b>' . _('Collection statement') . '</b></td></tr>';     
//    echo'</table>';

//   echo "<table class='selection' style='width:90%' border=1>";       
//   echo '<tr>  <td>'._. '</td>
//              <td><b>'. $office1 . '</b></td>
//              <td><b>' .$startdate . '</b></td>
//              <td><b>' . $Currentdate .'</b></td>             
//  </tr>'; 
//    echo "<table class='selection' style='width:90%'>";       
//    echo '<tr><td align=center><b>' . _('Daily demand and collection statement') . '</b></td></tr>';     
//    echo'</table>';  
//    
//    
//   echo "<table class='selection' style='width:90%' border=1>";           
//   echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td>                                    
//        <td align=center><b>' . _('Collection head name') . '</b></td> 
//        <td align=center><b>' . _('Total due') . '</b></td>
//        <td align=center><b>' . _('Collected') . '</b></td>
//        <td align=center><b>' . _('Balance ') .'</b></td> 
//        <td align=center><b>' . _('% achieved') .'</b></td>   
//        </tr>';  
//   
//        echo'<tr><td align=center>1</td>';
//        echo'<td align=center>Feasibility study charges </td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';

//        
//        echo'<tr><td align=center>2</td>';
//        echo'<td align=center>Sale order advance  </td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//       
//         
//        echo'<tr><td align=center>3</td>';
//        echo'<td align=center>Payment against supply of plant  </td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//       
//        
//        echo'<tr><td align=center>4</td>';
//        echo'<td align=center>Service charge</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//      
//             
//        echo'<tr><td align=center>5</td>';
//        echo'<td align=center>Service charge</td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//  
//   echo"</table>";   
//   echo"</fieldset>";
//   echo"</div>";
//   echo"</tr></td>";
//        
// echo"<tr><td>";
// echo '<div id="Collection report"></div>';  
// echo"<fieldset style='width:90%;'>";
// echo"<legend><h3>Collection statement</h3></legend>";             

// echo "<table class='selection' style='width:90%'>";       
// echo '<tr><td align=center><b>' . _('Collection statement') . '</b></td></tr>';     
// echo'</table>';       
// 
// echo "<table class='selection' style='width:90%' border=1>";       
// echo '<tr>  <td>'._. '</td>
//              <td><b>'.'BDM' .'-'  . $office1 . '</b></td>
//              <td><b>' .$startdate . '</b></td>
//              <td><b>' . $Currentdate .'</b></td>
//              
//  </tr>'; 
//  echo "<table class='selection' style='width:90%'>";       
//  echo '<tr><td align=center><b>' . _('Daily demand and collection statement') . '</b></td></tr>';     
//  echo'</table>'; 
//  echo "<table class='selection' style='width:90%' border=1>";           
//  echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Collection head name') . '</b></td> 
//        <td align=center><b>' . _('Total due') . '</b></td>
//        <td align=center><b>' . _('Collected') . '</b></td>
//        <td align=center><b>' . _('Balance ') .'</b></td> 
//        <td align=center><b>' . _('% achieved') .'</b></td>   
//        </tr>';  
//  
//     echo'<tr><td align=center>1</td>';
//     echo'<td align=center>Feasibility study charges </td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//            
//     echo'<tr><td align=center>2</td>';
//     echo'<td align=center>Sale order advance  </td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//     
//     echo'<tr><td align=center>3</td>';
//     echo'<td align=center>Payment against supply of plant  </td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//            
//     echo'<tr><td align=center>4</td>';
//     echo'<td align=center>Service charge</td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//                   
//     echo'<tr><td align=center>5</td>';
//     echo'<td align=center>Service charge</td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//      
// echo"</table>";   
// echo"</fieldset>";
// echo"</div>";
// echo"</tr></td>";
//        
// echo"<tr><td>";
// echo '<div id="Collection report"></div>';  
// echo"<fieldset style='width:90%;'>";
// echo"<legend><h3>Collection statement</h3></legend>";             
// echo "<table class='selection' style='width:90%'>";       
// echo '<tr><td align=center><b>' . _('Collection statement') . '</b></td></tr>';     
// echo'</table>';       
// 
// echo "<table class='selection' style='width:90%' border=1>";       

// echo '<tr>  <td>'._. '</td>
//              <td><b>'.'BDE' .'-'  . $office1 . '</b></td>
//              <td><b>' .$startdate . '</b></td>
//              <td><b>' . $Currentdate .'</b></td>
//              
//  </tr>'; 
//    echo "<table class='selection' style='width:90%'>";       
//    echo '<tr><td align=center><b>' . _('Daily demand and collection statement') . '</b></td></tr>';     
//    echo'</table>'; 
//    echo "<table class='selection' style='width:90%' border=1>";           

//    echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//                <td align=center><b>' . _('Collection head name') . '</b></td> 
//                <td align=center><b>' . _('Total due') . '</b></td>
//                <td align=center><b>' . _('Collected') . '</b></td>
//                <td align=center><b>' . _('Balance ') .'</b></td> 
//                <td align=center><b>' . _('% achieved') .'</b></td>   
//         </tr>';  
//          
//    
//     echo'<tr><td align=center>1</td>';
//     echo'<td align=center>Feasibility study charges </td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//       
//     echo'<tr><td align=center>2</td>';
//     echo'<td align=center>Sale order advance  </td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//      
//        
//     echo'<tr><td align=center>3</td>';
//     echo'<td align=center>Payment against supply of plant  </td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//      
//        
//     echo'<tr><td align=center>4</td>';
//     echo'<td align=center>Service charge</td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
//      
//             
//     echo'<tr><td align=center>5</td>';
//     echo'<td align=center>Service charge</td>';
//     echo'<td align=center></td>';
//     echo"<td align=center></td>";    
//     echo'<td align=center></td>';
//     echo'<td align=center></td>';
       

  //   echo"<tr><td>";
//echo '<div id="Collection report"></div>';  
//echo"<fieldset style='width:90%;'>";
//echo"<legend><h3>Collection statement</h3></legend>";     
//        
//        
//        
//        
//        
//        
// echo '<tr>  <td>'._. '</td>
//              <td><b>' .'BDM' .'-'  . $office1 . '</b></td>
//              <td><b>' .$startdate . '</b></td>
//              <td><b>' . $Currentdate .'</b></td>
//              
//  </tr>';  
//      echo "<table class='selection' style='width:90%'>";       
//    echo '<tr><td align=center><b>' . _('Daily demand and collection statement') . '</b></td></tr>';     
//    echo'</table>';  
//   
//    echo "<table class='selection' style='width:90%' border=1>";           

//   echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Collection head name') . '</b></td> 
//        <td align=center><b>' . _('Total due') . '</b></td>
//        <td align=center><b>' . _('Collected') . '</b></td>
//        <td align=center><b>' . _('Balance ') .'</b></td> 
//        <td align=center><b>' . _('% achieved') .'</b></td>   
//        </tr>';  
//    
//   
//    echo'<tr><td align=center>1</td>';
//        echo'<td align=center>Feasibility study charges </td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        
//    echo'<tr><td align=center>2</td>';
//        echo'<td align=center>Sale order advance  </td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";         
//        
//        
//   echo'<tr><td align=center>3</td>';
//        echo'<td align=center>Payment against supply of plant  </td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";      
//        
//   echo'<tr><td align=center>4</td>';
//        echo'<td align=center>Service charge</td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>"; 
//             
//  echo'<tr><td align=center>5</td>';
//        echo'<td align=center></td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";       
//        

//   echo"</table>";   
//        echo"</fieldset>";
//        echo"</div>";
//        echo"</tr></td>";
//   
   
   
   
   //     
//   echo"<tr><td>";
//echo '<div id="Collection report"></div>';  
//echo"<fieldset style='width:90%;'>";
//echo"<legend><h3>Collection statement</h3></legend>";     
//  
//        
// echo '<tr>  <td>'._. '</td>
//              <td><b>' .'BDE' .'-'  . $office1 . '</b></td>
//              <td><b>' .$startdate . '</b></td>
//              <td><b>' . $Currentdate .'</b></td>
//              
//  </tr>';  
//  
//    echo "<table class='selection' style='width:90%'>";       
//    echo '<tr><td align=center><b>' . _('Daily demand and collection statement') . '</b></td></tr>';     
//    echo'</table>';  
//  
//   
//   
//    echo "<table class='selection' style='width:90%' border=1>";           

//   echo '<tr>  <td align=center><b>' . _('Slno') . '</b></td> 
//        <td align=center><b>' . _('Collection head name') . '</b></td> 
//        <td align=center><b>' . _('Total due') . '</b></td>
//        <td align=center><b>' . _('Collected') . '</b></td>
//        <td align=center><b>' . _('Balance ') .'</b></td> 
//        <td align=center><b>' . _('% achieved') .'</b></td>   
//        </tr>';  
//    
//   
//    echo'<tr><td align=center>1</td>';
//        echo'<td align=center>Feasibility study charges </td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        
//    echo'<tr><td align=center>2</td>';
//        echo'<td align=center>Sale order advance  </td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";         
//        
//        
//   echo'<tr><td align=center>3</td>';
//        echo'<td align=center>Payment against supply of plant  </td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";      
//        
//   echo'<tr><td align=center>4</td>';
//        echo'<td align=center>Service charge</td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>"; 
//             
//  echo'<tr><td align=center>5</td>';
//        echo'<td align=center></td>';
//     echo'<td align=center></td>';
//        echo"<td align=center></td>";    
//        echo'<td align=center></td>';
//        echo'<td align=center></td>';
//        echo"<td align=center></td>";       
//        

//   echo"</table>";   
//        echo"</fieldset>";
//        echo"</div>";
//        echo"</tr></td>";
//   
//   
         
        
          
          
//echo"</div>";

 }                 
echo"</fieldset>";  
echo"</table>"; 
echo"</div>";
 
?>
<script type="text/javascript"> 
function reporttype()
{   
    var status=document.getElementById('report').value;         
  
      if(status==1){       
    $('#off').hide();      
       }
}
</script>