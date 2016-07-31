<?php
  $PageSecurity = 80;
  include('includes/session.inc');
//  $title = _('Task Re-assigning'); 
  include('includes/header.inc'); 
  
  global $leadid;
  if($_GET['leadid']!=""){
     $leadid=$_GET['leadid'];
     $_SESSION['Leads']=$leadid; 
  }else{
     $leadid=$_SESSION['Leads']; 
  }
  if($_GET['tid']!=""){
     $taskid=$_GET['tid'];
     $_SESSION['Task']=$taskid; 
  }else{
     $taskid=$_SESSION['Task']; 
  }       
  
  $currenttask=$_GET['taskid'];
  if($_GET['answer']!=""){
     $option=$_GET['answer'];
     $_SESSION['Option']=$option; 
  }else{
     $option=$_SESSION['Option']; 
  }
  
  $crdt=date("Y-m-d H:i:s");
                            
  if(isset($_POST['submit']))
  {
      
      $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
      
      
      $leadid=$_POST['leadid'];
      $taskid=$_POST['taskid'];
      $remarks=$_POST['remarks'];
      $team_ID=$_POST['fteam'];
      $FS_startdate=FormatDateForSQL($_POST['StartDate']);
      $FS_enddate=FormatDateForSQL($_POST['EndDate']);  
      
      ?>
      <script>
      
      var lead=<?php echo $leadid; ?>;
      window.opener.location='bio_instTaskview.php?lead='+ lead;
      window.close();

      </script>
      <?php
  }
    if(isset($_POST['updatecust']))
  {
       $sql="UPDATE `bio_cust` 
                 SET`custname` = '".$_POST['Custname']."',
                    `contactperson` = '".$_POST['contactPerson']."',
                    `houseno` = '".$_POST['Houseno']."',      
                    `housename` ='".$_POST['HouseName']."',
                    `area1` = '".$_POST['Area1']."',      
                    `area2` ='".$_POST['Area2']."',
                    `pin` = '".$_POST['Pin']."',      
                    `custphone` = '".$_POST['code']."',     
                    `custmob` = '".$_POST['mobile']."',
                    `custmail` = '".$_POST['email']."',
                    `careof` = '".$_POST['careof']."',
                    `taluk` = '".$_POST['taluk']."',
                    `LSG_type` = '".$_POST['lsgType']."', 
                    `LSG_name` = '".$_POST['lsgName']."',
                    `block_name` = '".$_POST['gramaPanchayath']."',
                    `LSG_ward` = '".$_POST['lsgWard']."',
                    `village` = '".$_POST['village']."',
                    `contact_desig` = '".$_POST['Designation']."',
                    `nature_org` = '".$_POST['Nature']."',
                    `head_org` = '".$_POST['Orghead']."',
                    `headdesig` = '".$_POST['Hdesig']."',
                    `headphone` = '".$_POST['hphone']."',
                    `headmob` = '".$_POST['Hmobile']."',
                    `headmail` = '".$_POST['Hmail']."' 
              WHERE `bio_cust`.`cust_id` ='".$_POST['customerid']."'";  
                  
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);   
      
  }
  
  
  
  
  if(isset($_POST['changestatus']))
  {
      $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
      $assigned_date=$_POST['date'];
      $assigned_date=FormatDateForSQL($assigned_date); 
      $leadid=$_POST['leadid'];
      $taskid=$_POST['taskid'];
      $remarks=$_POST['remarks'];
      $status=$_POST['status'];
      $crdt=date("Y-m-d H:i:s");
      
      $sql_remark="SELECT remarks FROM bio_leads WHERE leadid=".$leadid; 
      $result_remark=DB_query($sql_remark,$db);
      $row=DB_fetch_array($result_remark);
      $rem1=$row['remarks'];
      $remark=$rem1."\r\n".date("Y-m-d").":".$remarks;
      $sql_append="UPDATE bio_leads SET remarks='".$remark."' WHERE leadid=".$leadid;   DB_query($sql_append,$db);
  $sql_remark="UPDATE bio_leadtask SET remarks='$remarks' WHERE leadid='".$leadid."' AND tid=".$taskid;   DB_query($sql_remark,$db);
      
       if($status==1)
      {
      $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                          taskcompleteddate='".$crdt."' 
                   WHERE bio_leadtask.leadid=".$leadid." 
                     AND bio_leadtask.taskid=2 
                     AND taskcompletedstatus!=2
                     AND teamid=$assignedfrm";   
    DB_query($sql_flag,$db);
      
      $sql_lead="UPDATE bio_leads
                        SET leadstatus=3
                        WHERE leadid='".$leadid."'";
      $result_lead= DB_query($sql_lead,$db);
      
      }
    elseif($status==3)
      {     
          $sql2="UPDATE bio_leadtask SET taskcompletedstatus=2 WHERE leadid=$leadid AND taskid NOT IN ( 27,15,28)";  
            DB_query($sql2,$db); 
          
          $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=2 ";     DB_query($sql1,$db);
       
          
    $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=19 AND task_master_id NOT IN (27,15,28) ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
    $emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid']; //
    
    //$assigned_date=date("Y-m-d"); 
    
    while($row_schedule1=DB_fetch_array($result_schedule1))
    {       
        $taskid=$row_schedule1['task_master_id'];
        $date_interval=$row_schedule1['actual_task_day'];
        
        $date = strtotime("+$date_interval day", strtotime($assigned_date));
        $date=date("Y-m-d", $date); 
  
     /*   $sql_leadTask="UPDATE  bio_leadtask SET taskid='".$taskid."' ,
                                                 leadid='".$leadid."',
                                                 teamid='".$assignedfrm."',
                                                 assigneddate='".$assigned_date."',
                                                 duedate='".$date."',
                                                 assigned_from='".$assignedfrm."',
                                                 viewstatus=1 WHERE leadid='".$leadid."' AND taskid='".$taskid."'";*/
                                                 
  
    
        $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                 leadid,
                                                 teamid,
                                                 assigneddate,
                                                 duedate,
                                                 assigned_from,
                                                 viewstatus)
                                     VALUES('".$taskid."',
                                            '".$leadid."',
                                            '".$assignedfrm."',
                                            '".$assigned_date."',
                                            '".$date."',
                                            '".$assignedfrm."',
                                            1)";
        $result_leadTask=DB_query($sql_leadTask,$db); 
        
        $assigned_date=strtotime("+1 day", strtotime($date)); 
        $assigned_date=date("Y-m-d", $assigned_date);                                 
    }         
 }
      
      ?>
      <script>
      
      var lead=<?php echo $leadid; ?>;
      window.opener.location='bio_instTaskview.php?lead='+ lead;
      window.close();

      </script>
      <?php
  }
  
echo'<div id="leadgrid">';       
echo'</div>';       
      
      
echo'<div>';  
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
 
 echo"<input type='hidden' name='leadid' id='leadid' value='".$leadid."'>"; 
 echo"<input type='hidden' name='taskid' id='taskid' value='".$taskid."'>";
 
 /*$sql_tid="SELECT * FROM bio_leadtask
            WHERE tid=$taskid";
 $result_tid=DB_query($sql_tid,$db);
 $myrow_tid=DB_fetch_array($result_tid);
 $fsteam=$myrow_tid['teamid'];
 $startdate=$myrow_tid['assigneddate'];
 $enddate=$myrow_tid['duedate'];*/
 
 
 
 echo"<table style='border:1px solid #F0F0F0;width:100%'>";
 
 
if($option=='true'){
    echo"<tr><td>";
      echo"<fieldset style='width:450px'><legend>Assign Team</legend>";  
      echo"<table>";
      $DateString = Date($_SESSION['DefaultDateFormat']);
      
      $cur_office=$_SESSION['officeid']; 
  $cur_level=$_SESSION['level'];
  
   $office_arr=array();
   $office_arr[]=$cur_office;
  
        $sql2="SELECT reporting_off
            FROM bio_office
            WHERE id=$cur_office
            ";
       $result2=DB_query($sql2,$db);
       $myrow_count = DB_num_rows($result2);

     
     if($myrow_count>0){    
     while($row2=DB_fetch_array($result2)){
         
         $office_arr[]=$row2['reporting_off'];   
     if($row2['reporting_off']!=1){    
     $sql3="SELECT reporting_off 
                FROM bio_office
                WHERE id=".$row2['reporting_off']."";
        $result3=DB_query($sql3,$db);
        $myrow_count1 = DB_num_rows($result3);
     if($myrow_count1>0){
     while($row3=DB_fetch_array($result3)){
        $office_arr[]=$row3['reporting_off'];
                      
     if($row2['reporting_off']!=1){   
     $sql4="SELECT reporting_off 
                FROM bio_office
                WHERE id=".$row3['reporting_off']."";
        $result4=DB_query($sql4,$db);
        $myrow_count2 = DB_num_rows($result4);
     if($myrow_count2>0){
     while($row4=DB_fetch_array($result4)){
               $office_arr[]=$row4['reporting_off'];        
            
        }
        }  
        }   
     }
     }
     }
     }
     }
         $office_array=join(",", $office_arr); 
     
    
     $sql5="SELECT reporting_off,id
            FROM bio_office
            WHERE reporting_off=$cur_office
            ";
       $result5=DB_query($sql5,$db);
       $myrow_count5 = DB_num_rows($result5);

     
     if($myrow_count5>0){    
     while($row5=DB_fetch_array($result5)){
         
         $office_arr[]=$row5['id'];   
     if($row5['id']!=1){    
     $sql6="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row5['id']."";
        $result6=DB_query($sql6,$db);
        $myrow_count6 = DB_num_rows($result6);
     if($myrow_count6>0){
     while($row6=DB_fetch_array($result6)){
               $office_arr[]=$row6['id'];
                      
     if($row6['id']!=1){   
     $sql7="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row6['id']."";
        $result7=DB_query($sql7,$db);
        $myrow_count7 = DB_num_rows($result7);
     if($myrow_count7>0){
     while($row7=DB_fetch_array($result7)){
               $office_arr[]=$row7['id'];        

            
        }
        }  
        }   
     }
     }
     }
     }
     } 
         
     $office_array=join(",", $office_arr);
      
      
      
      
      echo"<tr><td width=50%>Select Office</td>";

echo"<td><select name='office' id='office' style='width:245px' onchange=teamList(this.value)>";
$sql="SELECT * FROM bio_office WHERE id IN ($office_array)";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['id']==$_POST['office'])  
    {   
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['office'];
    echo '</option>';
    $f++;
   }          
echo "</select></td></tr>";
      
      echo"<tr><td width=50%>Select Team*</td>";
      echo"<td id=team><select name='fteam' id='fteam' style='width:245px'>";
      $sql="SELECT bio_leadteams.teamid,bio_leadteams.teamname FROM bio_leadteams";
      $result=DB_query($sql,$db);

      $f=0;
      while($myrow1=DB_fetch_array($result))
      {  
        if ($myrow1['teamid']==$fsteam)  
        {   
            echo '<option selected value="';
        } else 
            {
                if ($f==0) 
                {
                    echo '<option>';
                }
                echo '<option value="';
            }
    echo $myrow1['teamid'] . '">'.$myrow1['teamname'];
    echo '</option>';
    $f++;
   }   
   echo"</select></td></tr>";
      
   echo"<tr><td>Start date*</td>";
   echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:240px' value='".$startdate."'></td></tr>";

   echo"<tr><td>End date*</td>";
   echo"<td><input type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:240px' value='".$enddate."'></td></tr>";
   echo"<tr><td>Remarks:</td>"; 
     echo"<td><textarea rows=2 cols=35 name=remarks id=remarks style=resize:none; value='$remarks'>$remarks</textarea></td></tr>";
   
   
   echo"<tr><td></td><td align=right><input type='submit' name='submit' id='submit' value='Submit' onclick='if(validation()==1)return false;;'></td></tr>";                  
                
   echo"</table>";
   echo"</fieldset>";
   echo"</td></tr>";
   
}elseif($option=='false'){
    echo"<tr><td>";
    echo"<fieldset style='width:450px'><legend>Customer Details</legend>";
    
   
   echo"<div id=cus_details>"; 
   echo"<table width=100%>"; 
    if($_GET['leadid']==""){
        $leadid=$_SESSION['Leads'];
    }
   
    if($leadid!=''){
           
       $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.contactperson,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.houseno,
                    bio_cust.housename,
                    bio_cust.area2,
                    bio_cust.pin,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.contact_desig,
                    bio_cust.nature_org,
                    bio_cust.head_org,
                    bio_cust.headdesig,
                    bio_cust.headphone,
                    bio_cust.headmob,
                    bio_cust.headmail,
                    bio_cust.nationality,
                    bio_cust.LSG_type,
                    bio_cust.LSG_name,
                    bio_cust.block_name,
                    bio_cust.LSG_ward,
                    bio_cust.village,
                    bio_cust.taluk,
                    bio_cust.cust_id  
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);
// $count=DB_fetch_row($result);
 $myrow=DB_fetch_array($result); 
//print_r($count);
 $no=0; 
 $k=0; 
 $mob=$myrow['custmob'];
 $cname=$myrow['custname'];;  
 if($myrow[2]!='-'){
     $custcode=$myrow['custphone']; 
 }else{
     $custcode=''; 
 }
  
 $cntper=$myrow['contactperson']; 
 $place=$myrow['area1'];
 $hno=$myrow['houseno'];
 $hname=$myrow['housename'];
 $po=$myrow['area2'];
 $pin=$myrow['pin'];
 $mail=$myrow['custmail'];
 $cntdesig=$myrow['contact_desig'];
 $orgnature=$myrow['nature_org'];
 $headorg=$myrow['head_org'];
 $hdesign=$myrow['headdesig'];
 $hphe=$myrow['headphone'];
 $hmob=$myrow['headmob'];
 $hmail=$myrow['headmail'];  
 $cust_ID=$myrow['cust_id'];
 $nationality=$myrow['nationality'];
 $state=$myrow['state'];
 $district=$myrow['district'];                                
 $status=$myrow['status']; 
 $taluk=$myrow['taluk'];
 $LSGtypeid=$myrow['LSG_type'];
     
 if($LSGtypeid==1){$lsgtype="Corporation";}
 elseif($LSGtypeid==2){$lsgtype="Municipality";}
 elseif($LSGtypeid==3){$lsgtype="Panchayath";}
        
 $LSG_name=$myrow['LSG_name'];
 $block_name=$myrow['block_name'];
 $LSG_ward=$myrow['LSG_ward'];
 $village=$myrow['village'];    
 $remarks=$myrow['remarks'];
 $cust_type=$myrow['enqtypeid'];
      
 //$result1=DB_query($sql1,$db);
// $myrow=DB_fetch_array($result1);
 //$district=$myrow[0];
}
  

  
echo'<tr><td>';  
echo"<fieldset style='width:420px;height:350px'>";
echo"<table width=100%>";

echo'<tr>';
echo'<td>Nature of Organisation</td>';
echo'<td><select name="Nature" id="nature"  style="width:102%">';

$sql1="SELECT * FROM bio_inst_nature";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['nature_id']==$orgnature)
    {
        echo '<option selected value="';
    } else 
    {
        if ($f==0) 
        {
            echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['nature_id'] . '">'.$myrow1['nature'];  
    echo '</option>';
  }
echo'</select></td></tr>'; 

   
echo"<tr><td width=50%>Organisation Name</td>";
echo"<td><input type='text' name='Custname' id='custname' value='$cname' style='width:100%'></td></tr>";
echo"<tr></tr>";


echo"<tr><td>Building Name/No:</td>";
echo"<td><input type='text' name='Houseno' id='Houseno' value='$hno' style='width:100%'></td></tr>";
    
echo "<tr><td>Organisation Street:</td><td>";
echo"<input type='text' name='HouseName' id='HouseName' value='$hname' style='width:100%'></td></tr>";

echo "<tr><td>Organisation Area:</td>";
echo"<td><input type='text' name='Area1' id='Area1' value='$place' style='width:100%'></td></tr>";

echo "<tr><td>Post Office:</td>";
echo"<td><input type='text' name='Area2' id='Area2' value='$po' style='width:100%'></td></tr>";

echo" <tr><td>Pin:</td>";
echo"<td><input type='text' name='Pin' id='Pin' value='$pin' style='width:100%'></td></tr>"; 





//---------country--------------//    
    
    echo"<tr ><td width=17%>Country</td><td>";
    echo '<select name="country" id="country" onchange="showstate(this.value)" style="width:102%">';
    
    $sql="SELECT * FROM bio_country ORDER BY cid";     $result=DB_query($sql,$db);
    
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {  
        if ($myrow1['cid']==1)  
        {         
        echo '<option selected value="';
        } else 
        {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        }
        echo $myrow1['cid'] . '">'.$myrow1['country'];
        echo '</option>';
        $f++;
   } 
   echo '</select></td></tr>';
  
//--------------state-----------------//

$sql="SELECT * FROM bio_state ORDER BY stateid";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showstate' width=17%><td>State</td><td>";
 echo '<select name="State" id="state" style="width:102%" onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==$state AND $myrow1['cid']==$nationality)
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
  echo'</tr>';


echo"<tr id='showdistrict' width=17%>";

echo"<td>District</td><td>";
echo '<select name="District" id="Districts" style="width:102%" onchange="showtaluk(this.value)">';          
     
$sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";      
$result=DB_query($sql,$db);   
 
 $f=0;
 while($myrow1=DB_fetch_array($result))
 {
 if ($myrow1['did']==$district)
 {
 echo '<option selected value="';
 } else
 {
 if ($f==0)
 {
 echo '<option>';
 }
 echo '<option value="';
 }
 echo $myrow1['did'] . '">'.$myrow1['district'];
 echo '</option>';
 $f++;
 }
  echo '</select></td>';
  echo'</tr>'; 



    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:102%" onchange=showblock(this.value)>';             
    echo '<option value='.$LSGtypeid.'>'.$lsgtype.'</option>'; 
   
    if($LSGtypeid==1){   
   
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
    
    }elseif($LSGtypeid==2){
        
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
        
    }elseif($LSGtypeid==3){
        
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
        
    }else{
        
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Muncipality</option>';
        echo '<option value=3>Panchayat</option>';
        
    }
          
       echo '</select></td></tr>';
       echo'<tr><td align=left colspan=2>';
       echo'<div style="align:right" id=block>'; 
          
    if($LSGtypeid==1){
        
        $sql="SELECT * FROM bio_corporation WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];
       
        if($country==1 && $state==14)  
        {
              if($district==12){$distname='Thiruvananthapuram';}
              if($district==6){$distname='Kollam';} 
              if($district==2){$distname='Eranakulam';} 
              if($district==13){$distname='Thrissur';} 
              if($district==8){$distname='Kozhikode';} 
                    echo '<table align=left >';
                    echo'<tr><td>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:190px">';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td></tr>';    
                    echo '</table>';      
        }
    }elseif($LSGtypeid==2){
            //echo"2222222";
        echo '<table align=left >';
        echo'<tr><td width=202px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:181%">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
         {
         echo '<option selected value="';
         } else
         {
         if ($f==0)
         {
         echo '<option>';
         }
         echo '<option value="';
         }
         echo $myrow1['id'] . '">'.$myrow1['municipality'];
         echo '</option>';
         $f++;
         }

      echo '</select></td></tr>';
      echo'</table>';
       
    }elseif($LSGtypeid==3){
        echo '<table align=left >';
        echo'<tr><td width=202px>' . _('Block Name') . ':</td>';    
        $sql="SELECT * FROM bio_block WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:178%">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
         {
         echo '<option selected value="';
         } else
         {
         if ($f==0)
         {
         echo '<option>';
         }
         echo '<option value="';
         }
         echo $myrow1['id'] . '">'.$myrow1['block'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr>';
      
      echo '<tr><td width=202px>' . _('Panchayat Name') . ':</td>';         
         
         $sql="SELECT * FROM bio_panchayat WHERE country=$nationality AND state=$state AND district=$district AND id=$block_name";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:178%" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$block_name)
         {
         echo '<option selected value="';
         } else
         {
         if ($f==0)
         {
         echo '<option>';
         }
         echo '<option value="';
         }
         echo $myrow1['id'] . '">'.$myrow1['name'];
         echo '</option>';
         $f++;
         }

      echo '</select></td></tr>';                    
      echo'</table>';  
    }
    echo'</div>';
    echo'</td></tr>';
    
    echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
      <td><input type="Text" name="lsgWard" id="lsgWard" style=width:100% maxlength=15 value="'.$LSG_ward.'"></td></tr>';

      if($district!="")  {  
    echo"<tr><td>Taluk</td><td>";
    $sql="SELECT * FROM bio_taluk WHERE country=$nationality AND state=$state AND district=$district";
    $result=DB_query($sql,$db);
    echo '<select name="taluk" id="taluk" style="width:102%" onchange="showvillage(this.value)">';
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {
      if ($myrow1['id']==$taluk)
      {
      echo '<option selected value="';
      } else
      {
      if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
        }
      echo $myrow1['id'] . '">'.$myrow1['taluk'];
      echo '</option>';
      $f++;
    }
    echo '</select>';
    echo'</td>';        
    echo"</tr>";  
}else{
    echo"<tr id='showtaluk'></tr>";  
}

if($eid!="")  {  
    echo"<tr id='showvillage'>";
        echo"<td>Village</td><td>";
    $sql="SELECT * FROM bio_village WHERE bio_village.country=$nationality AND bio_village.state=$state AND bio_village.district=$district";
    if($taluk!="" OR $taluk!=NULL){
        if($taluk!=0){
            $sql.=" AND bio_village.taluk=$taluk";
        }
        
    }
//    echo$sql;
    $result=DB_query($sql,$db);
  echo '<select name="village" id="village" style="width:222px">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['id']==$village)
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['village'];
    echo '</option>';
    $f++;
   }

  echo '</select>';
  echo'</td>';
  echo"</tr>";  
  
}else{
    
   echo"<tr id='showvillage'></tr>";
}   
    
    


 



echo"</table>"; 
echo"</fieldset>";
echo"</td>";  


echo'<td>';  
echo"<fieldset style='width:400px;height:350px'>";
echo"<table width=100%>";
         
echo "<tr><td>Contact Person</td>";
echo "<td><input type='text' name='contactPerson' id='contactPerson' value='$cntper' style='width:100%'></td></tr>";

echo"<tr><td width=50%>Designation</td>";
echo"<td><input type='text' name='Designation' id='designation' value='$cntdesig' style='width:100%'></td></tr>";

echo"<tr><td width=50%>Mobile number</td>";
echo"<td><input type='text' name='mobile' id='mobile' value='$mob' style='width:100%'></td></tr>";

echo"<tr><td>Phone Number</td>";
echo"<td><input type='text' name='code' id='code' value='$custcode' style='width:100%'></td></tr>"; 

echo"<tr><td width=50%>Email</td>";
echo"<td><input type='text' name='email' id='email' value='$mail' style='width:100%'></td></tr>";

echo "<tr><td>Head of Organisation</td>";
echo "<td><input type='text' name='Orghead' id='orghead' value='$headorg' style='width:100%'></td></tr>";

echo"<tr><td width=50%>Designation</td>";
echo"<td><input type='text' name='Hdesig' id='hdesig' value='$hdesign' style='width:100%'></td></tr>";

echo"<tr><td width=50%>Mobile number</td>";
echo"<td><input type='text' name='Hmobile' id='hmobile' value='$hmob' style='width:100%'></td></tr>";

echo"<tr><td>Phone Number</td>";
echo"<td><input type='text' name='hphone' id='hphone' value='$hphe' style='width:100%'></td></tr>"; 

echo"<tr><td width=50%>Email</td>";
echo"<td><input type='text' name='Hmail' id='hmail' value='$hmail' style='width:100%'></td></tr>";
echo"<input type='hidden' name='customerid' id='custid' value='$cust_ID' style='width:100%'>";


echo"</table>"; 
echo"</fieldset>";
echo"</td></tr>"; 

echo'<tr><td colspan=2><p><div class="centre">
      <input type=submit name=updatecust value="' . _('Update') . '">';
echo '<input name="clear" type="submit" value=Clear >';

echo'</div>';
echo'</td></tr>';


 
   
echo"</table>"; 
echo'</div>';

    
    
    echo"<div id=changestatus>";
    echo"</div>";
    echo"<table>";
    
    echo"<tr><td><a style='cursor:pointer;' id='".$leadid."' onclick='FsPrint(this.id,".$taskid.");'>" . _('Print Feasibility study Report') . "</a></td></tr>";
    echo"<tr><td><a style='cursor:pointer;' id='".$leadid."' onclick='changeStatus(this.id,".$taskid.");'>" . _('Change Status') . "</a></td></tr>";
    
    echo"</table>";
    echo"</fieldset>";
    echo"</td></tr>";


}  
 

echo"</table>";  

echo"</form>";
echo'</div>';       


?>

<script type="text/javascript">

 


function validation()
{
    var f=0;
    var p=0;
if(f==0){f=common_error('office','Please select an office');  if(f==1){return f; }  }
if(f==0){f=common_error('fteam','Please select a team');  if(f==1){return f; }  } 
if(f==0){f=common_error('startdate','Please enter start date');  if(f==1){return f; }  }  
if(f==0){f=common_error('enddate','Please enter end date');  if(f==1){return f; }  } 
    
}
function statusvalidation()
{
    var f=0;
    var p=0;
if(f==0){f=common_error('status','Please select a status');  if(f==1){return f; }  }
if(f==0){f=common_error('remarks','Please enter remark');  if(f==1){return f; }  } 
}
   
function updateDate(str1,str2,str3,str4){   
//alert(str1);   alert(str2);      alert(str3);     alert(str4);
if (str1=="")
  {
  document.getElementById("leadgrid").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("leadgrid").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_updateDate.php?date=" + str1 + "&tid=" + str2 + "&leadid=" + str3 + "&choosedate=" + str4 ,true);
xmlhttp.send(); 
}


function teamList(str){
    
if (str=="")
  {
  document.getElementById("team").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("team").innerHTML=xmlhttp.responseText;

    }
  } 

xmlhttp.open("GET","bio_teamlist.php?off=" + str,true);
xmlhttp.send();    
}

function changeStatus(str,str1){
    
if (str=="")
  {
  document.getElementById("changestatus").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("changestatus").innerHTML=xmlhttp.responseText;
    document.getElementById('status').focus();

    }
  } 

xmlhttp.open("GET","bio_changefsstatus.php?lead=" + str +"&task="+str1,true);
xmlhttp.send();    
}

 
function FsPrint(str1,str2)

{
window.location="bio_fsRegister_report.php?lead=" + str1 +"&tid=" +str2;

/*controlWindow=window.open("bio_fsRegister_report.php?lead=" + str1 +"&tid=" +str2,"Payment","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=300,height=300");
window.opener.location='bio_instTaskview.php?lead='+ str1;
window.close();*/

}


function showstate(str){
   // alert(str); 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {             
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate.php?country=" + str,true);
xmlhttp.send();
}


function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
  
  
function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str1=="")
  {
  document.getElementById("showtaluk").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_fs.php?taluk=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

function showblock(str){ 
  
str1=document.getElementById("country").value;
str2=document.getElementById('state').value;
str3=document.getElementById('Districts').value;

if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("block").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_fs.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

function showgramapanchayath(str){   
   //alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showgramapanchayath").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("showgramapanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_fs.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

function showvillage(str){   
  // alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showvillage").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("showvillage").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_fs.php?village=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

function selection()
{
    var status=document.getElementById('status').value;         
    if(status==3){      
    $('#newTask').show();      
    }else{   
     $('#newTask').hide();
    }    
}
</script>