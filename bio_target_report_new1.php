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
     echo '<option value=2>BDE level report</option>'; 
    // echo '<option value=3>BDM Office level report</option>';           
//     echo '<option value=4>SO register</option>';        
//     echo '<option value=5>Collection statement-Corporate level</option>'; 
//     echo '<option value=6>Collection statement-BDM level</option>';
//     echo '<option value=7>Collection statement-BDE level</option>';                                                                                           
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
    $month1=Date("m");
    $month=$month1-1;
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
echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
echo"</tr>";
echo"</table>";
echo"</form>";
echo"</td></tr>" ;

   if($enqtype==2){
    $sql_report="SELECT * FROM bio_reportda1 WHERE reporttype=$type  AND enqtype=$enqtype AND year=\"$year\"  AND month=\"$month\"  ";
 
         if(isset($_POST['filterbut'])){ 
         if (isset($_POST['Enq']))    {      
         if ($_POST['Enq']!=''  && $_POST['Enq']!=0){
         $sql_report.=" AND bio_reportda1.enqtype=".$_POST['Enq'];
       } 
    }
         if (isset($_POST['Year']))    {      
         if ($_POST['Year']!=''  && $_POST['Enq']!=0){
        $sql_report.=" AND bio_reportda1.year=".$_POST['Year'];
       } 
    }
         if (isset($_POST['From']))    {      
         if ($_POST['From']!=''  && $_POST['From']!=0){
        $sql_report.=" AND bio_reportda1.month=".$_POST['From'];
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
        $sql_report.=" AND bio_target.team_id IN ($team_array1)";  
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
}   
  if(isset($_POST['Report'])){ 
  if($_POST['Report']==1){  
 // $team_arr=array();      
  $startdate1=FormatDateForSQL($startdate);
  $Currentdate1=FormatDateForSQL($Currentdate);
  $result_report=DB_query($sql_report,$db);   
  $row_r=DB_fetch_array($result_report);
  $leads=$row_r['leads_target'];
  $lead_achievmnt=$row_r['leads_perf'];
 if ($leads!=0) {
      $leads_per=round($lead_achievmnt/$leads*100,2);}
      else {$leads_per ='';}
 $meetings=$row_r['mtg_target'];
 $meeting_achievmnt=$row_r['mtg_perf'];
 if ($meetings!=0) {
      $meeting_per=round($meeting_achievmnt/$meetings*100,2);}
      else {$meeting_per ='';}
 $feasibility=$row_r['fs_target'];
 $fs_achmnt=$row_r['fs_perf'];
 if ($feasibility!=0) {
      $feasibility_per=round($fs_achmnt/$feasibility*100,2);}
      else {$feasibility_per ='';}
 $conceptprop=$row_r['cp_target'];
 $cp_achmnt=$row_r['cp_perf'];
 if ($conceptprop!=0) {
      $cp_per=round($cp_achmnt/$conceptprop*100,2);}
      else {$cp_per ='';}
 $saleorder=$row_r['so_target'];
 $so_achmnt=$row_r['so_perf'];
 if ($saleorder!=0) {
      $saleorder_per=round($so_achmnt/$saleorder*100,2);}
      else {$saleorder_per ='';}
 $fscharge=$row_r['fscollection_target'];
 $FS_achmnt=$row_r['fscollection_perf'];
 if ($fscharge!=0) {
      $fscharge_per=round($FS_achmnt/$fscharge*100,2);}
      else {$fscharge_per ='';}
 $SOcollect=$row_r['so_adv_coll_target'];
 $SO_achmnt=$row_r['so_adv_coll_perf'];
 if ($SOcollect!=0) {
      $SOcollect_per=round($SO_achmnt/$SOcollect*100,2);}
      else {$SOcollect_per ='';}
 $payment=$row_r['supply_pymt_coll_target'];
 $pay_achmnt=$row_r['supply_pymt_coll_perf'];
 if ($payment!=0) {
      $payment_per=round($pay_achmnt/$payment*100,2);}
      else {$payment_per ='';}
 $total_collection_target=round($fscharge+$SOcollect+$payment,2);
 $total_achvmnt=round($FS_achmnt+$SO_achmnt+$pay_achmnt,2);
 if ($total_collection_target!=0) {
 $total_achvmntper=round($total_achvmnt/$total_collection_target*100,2);
 } else {$total_achvmntper='';}
  //$employee_arr=join(",", $employee_arr);
echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>";
echo '<div id="corporate">';
echo"<fieldset style='width:90%;'>";
echo"<legend><h3>Corporate level Report</h3></legend>";
echo "<table class='selection' style='width:90%' border=1>";
echo '<tr><td></td>';
//echo '<td><b>' . $office1 . '</b></td>';
echo '<td colspan=4 align=center><b>' . date("F", strtotime("2001-$month-1")) .'   ' .$year .'</b></td>
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
        echo"<td align=center>".$leads_per."</td>";

        echo'<tr><td align=center>2</td>';
        echo'<td align=center>Number of meetings conducted</td>';
        echo"<td align=center>".$meetings."</td>";
        echo"<td align=center>".$meeting_achievmnt."</td>";
        echo"<td align=center>".$meeting_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>3</td>';
        echo'<td align=center>Number of feasibility study conducted</td>';
        echo"<td align=center>".$feasibility."</td>";
        echo"<td align=center>".$fs_achmnt."</td>";
        echo"<td align=center>".$feasibility_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>4</td>';
        echo'<td align=center>Number of concept proposal given</td>';
        echo"<td align=center>".$conceptprop."</td>";
        echo"<td align=center>".$cp_achmnt."</td>";
        echo"<td align=center>".$cp_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>5</td>';
        echo'<td align=center>Number of sale order registered</td>';
        echo"<td align=center>".$saleorder."</td>";
        echo"<td align=center>".$so_achmnt."</td>";
        echo"<td align=center>".$saleorder_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>6</td>';
        echo'<td align=center>Feasibility study charges collected</td>';
        echo"<td align=center>".$fscharge."</td>";
        echo"<td align=center>".$FS_achmnt."</td>";
        echo"<td align=center>".$fscharge_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>7</td>';
        echo'<td align=center>SO advance collected</td>';
        echo"<td align=center>".$SOcollect."</td>";
        echo"<td align=center>".$SO_achmnt."</td>";
        echo"<td align=center>".$SOcollect_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>8</td>';
        echo'<td align=center>Payment against supply collected</td>';
        echo"<td align=center>".$payment."</td>";
        echo"<td align=center>".$pay_achmnt."</td>";
        echo"<td align=center>".$payment_per."</td>";
        echo"</tr>";


        echo'<tr><td align=center></td>';
        echo'<td align=center><b>Total Collection</b></td>';
        echo"<td align=center><b>".$total_collection_target."</b></td>";
        echo"<td align=center><b>".$total_achvmnt."</b></td>";
        echo"<td align=center><b>".$total_achvmntper."</b></td>";
        echo"</tr>";

        echo"</table>";
        echo"</fieldset>";
        echo"</div>";
        echo"</tr></td>";
    } 
   if($_POST['Report']==2){  
   if($_POST['Office']==0){
                              $sql8="select count(*) AS Count from bio_emp where offid IN(select id from bio_office)";
                 
             }    else{            
   $sql8="select bio_emp.empname,
                 bio_emp.empid,
                 bio_teammembers.teamid 
            from bio_emp,
                 bio_teammembers 
           where offid=".$_POST['Office'].
           " AND designationid=9 
             AND bio_teammembers.empid=bio_emp.empid";
   $result8=DB_query($sql8,$db);
   $myrow8=DB_fetch_array($result8);}
   $BDE_count1=$myrow8['empname'];             
   $BDE_count=DB_num_rows($result8);
   $team_id=$myrow8['teamid'];
echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>";
  echo"<fieldset style='width:90%;'>";
echo"<legend><h3>BDE level Report</h3></legend>";
   while($myrow8=DB_fetch_array($result8)) {
   echo '<tr><td>';
   echo "<table class='selection' style='width:90%' border=1>";     
   $sql_bde="SELECT * FROM bio_reportda1 WHERE reporttype=$type AND teamid=".$myrow8['teamid']." AND office=".$_POST['Office']." AND enqtype=$enqtype AND year=\"$year\"  AND month=\"$month\" ";
   $result_bde=DB_query($sql_bde,$db);
   $row_bde=DB_fetch_array($result_bde);
   
   $leads=$row_bde['leads_target'];
   $lead_achievmnt=$row_bde['leads_perf'];
  
 if ($leads!=0) {
      $leads_per=round($lead_achievmnt/$leads*100,2);}
      else {$leads_per ='';}
 $meetings=$row_bde['mtg_target'];
 $meeting_achievmnt=$row_bde['mtg_perf'];
 if ($meetings!=0) {
      $meeting_per=round($meeting_achievmnt/$meetings*100,2);}
      else {$meeting_per ='';}
 $feasibility=$row_bde['fs_target'];
 $fs_achmnt=$row_bde['fs_perf'];
 if ($feasibility!=0) {
      $feasibility_per=round($fs_achmnt/$feasibility*100,2);}
      else {$feasibility_per ='';}
 $conceptprop=$row_bde['cp_target'];
 $cp_achmnt=$row_bde['cp_perf'];
 if ($conceptprop!=0) {
      $cp_per=round($cp_achmnt/$conceptprop*100,2);}
      else {$cp_per ='';}
 $saleorder=$row_bde['so_target'];
 $so_achmnt=$row_bde['so_perf'];
 if ($saleorder!=0) {
      $saleorder_per=round($so_achmnt/$saleorder*100,2);}
      else {$saleorder_per ='';}
 $fscharge=$row_bde['fscollection_target'];
 $FS_achmnt=$row_bde['fscollection_perf'];
 if ($fscharge!=0) {
      $fscharge_per=round($FS_achmnt/$fscharge*100,2);}
      else {$fscharge_per ='';}
 $SOcollect=$row_bde['so_adv_coll_target'];
 $SO_achmnt=$row_bde['so_adv_coll_perf'];
 if ($SOcollect!=0) {
      $SOcollect_per=round($SO_achmnt/$SOcollect*100,2);}
      else {$SOcollect_per ='';}
 $payment=$row_bde['supply_pymt_coll_target'];
 $pay_achmnt=$row_bde['supply_pymt_coll_perf'];
 if ($payment!=0) {
      $payment_per=round($pay_achmnt/$payment*100,2);}
      else {$payment_per ='';}
 $total_collection_target=round($fscharge+$SOcollect+$payment,2);
 $total_achvmnt=round($FS_achmnt+$SO_achmnt+$pay_achmnt,2);
 if ($total_collection_target!=0) {
 $total_achvmntper=round($total_achvmnt/$total_collection_target*100,2);
 } else {$total_achvmntper='';} 
 
       

echo '<div id="corporate">';

echo "<table class='selection' style='width:90%' border=1>";
echo '<tr><td></td>';
echo '<td colspan=4 align=center><b>' .$myrow8['empname'].'      -' . date("F", strtotime("2001-$month-1")) .'    ' .$year .'</b></td>
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
        echo"<td align=center>".$leads_per."</td>";
        
        echo'<tr><td align=center>2</td>';
        echo'<td align=center>Number of meetings conducted</td>';
        echo"<td align=center>".$meetings."</td>";
        echo"<td align=center>".$meeting_achievmnt."</td>";
        echo"<td align=center>".$meeting_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>3</td>';
        echo'<td align=center>Number of feasibility study conducted</td>';
        echo"<td align=center>".$feasibility."</td>";
        echo"<td align=center>".$fs_achmnt."</td>";
        echo"<td align=center>".$feasibility_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>4</td>';
        echo'<td align=center>Number of concept proposal given</td>';
        echo"<td align=center>".$conceptprop."</td>";
        echo"<td align=center>".$cp_achmnt."</td>";
        echo"<td align=center>".$cp_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>5</td>';
        echo'<td align=center>Number of sale order registered</td>';
        echo"<td align=center>".$saleorder."</td>";
        echo"<td align=center>".$so_achmnt."</td>";
        echo"<td align=center>".$saleorder_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>6</td>';
        echo'<td align=center>Feasibility study charges collected</td>';
        echo"<td align=center>".$fscharge."</td>";
        echo"<td align=center>".$FS_achmnt."</td>";
        echo"<td align=center>".$fscharge_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>7</td>';
        echo'<td align=center>SO advance collected</td>';
        echo"<td align=center>".$SOcollect."</td>";
        echo"<td align=center>".$SO_achmnt."</td>";
        echo"<td align=center>".$SOcollect_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center>8</td>';
        echo'<td align=center>Payment against supply collected</td>';
        echo"<td align=center>".$payment."</td>";
        echo"<td align=center>".$pay_achmnt."</td>";
        echo"<td align=center>".$payment_per."</td>";
        echo"</tr>";

        echo'<tr><td align=center></td>';
        echo'<td align=center><b>Total Collection</b></td>';
        echo"<td align=center><b>".$total_collection_target."</b></td>";
        echo"<td align=center><b>".$total_achvmnt."</b></td>";
        echo"<td align=center><b>".$total_achvmntper."</b></td>";
        echo"</tr>";

        echo"</table>";
        
        echo"</div>";
        echo"</tr></td>";
        echo"<tr></tr>" ;
    }  
    echo'</td></tr>';                              
   echo'</table>';
      
//   }
       echo"</fieldset>"; 
    
    }     
  }
}
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