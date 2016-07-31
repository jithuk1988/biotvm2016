<?php
$PageSecurity = 80;
include('includes/session.inc');
$pagetype=3;

$title = _('Feasibility study entry');  
include('includes/header.inc');
include('includes/sidemenu.php');


$office=$_SESSION['officeid']; 
$leadid=$_GET['lead'];
$currenttask=$_GET['taskid'];
$taskid=$_GET['tid'];


if(!isset($_POST['submit'])){
    $tempdrop="DROP TABLE IF EXISTS bio_feeddetailstemp";
    DB_query($tempdrop,$db);
    $temptable="CREATE TABLE bio_feeddetailstemp (
                             temp_id INT NOT NULL AUTO_INCREMENT ,
                             feedstockid INT NULL ,
                             weight DECIMAL NULL ,
                             feedstocksourceid INT NULL ,
                             number_source INT NULL ,
                             gas_out INT NULL,
               PRIMARY KEY ( temp_id ))";
    DB_query($temptable,$db);

    $sql="ALTER TABLE `bio_feeddetailstemp` 
                  ADD `status` INT 
                               NOT 
                               NULL 
                               DEFAULT 
                               '0'" ; 
    DB_query($sql,$db);
    
    
    $sql_del="DELETE FROM bio_institution_temp";
    DB_query($sql_del,$db);
    
    
    $sql_del="DELETE FROM bio_plant_temp";
    DB_query($sql_del,$db);
    
    
}

    if (isset($_POST['submit'])) {
        unset($_SESSION['feedstockanalysis']);
      // exit;
        
        
        
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
        
        
        
        $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
        
        
        $user_ID=$_SESSION['UserID'];
        $InputError=0;
        $currentdate=Date($_SESSION['DefaultDateFormat']);
        $currentdateSQL=FormatDateForSQL($currentdate);
        $lead_ID=$_POST['LeadID'];
        $teamID=$_POST['TeamID'];
        $easily_deg=$_POST['edegradable'];
        $slowly_deg=$_POST['sdegradable'];
        $vslowly_deg=$_POST['vsdegradable'];
        $none_deg=$_POST['nondegradable'];
        $solid_waste=$easily_deg+$slowly_deg+$vslowly_deg+$none_deg;
        $liquid_waste=$_POST['WasteWater'];
        
        $regstr_type=$_POST['Regtype'];
        
        $insttype="";
        $planttype=$_POST['PlantType'];
        $budget=$_POST['Budget'];
        if($_POST['Budget']==""){
           $budget=0; 
        }
    
    $sql_Del="DELETE FROM bio_fs_feedstockdetails WHERE leadid=$lead_ID";
    $result_Del= DB_query($sql_Del,$db,$ErrMsg,$DbgMsg); 
        
        
    $sql5="SELECT * FROM bio_feeddetailstemp";
    $result5=DB_query($sql5,$db);
    $count=DB_num_rows($result5);
    if($count>0)
    {
    while($myrow5=DB_fetch_array($result5))     {
        $feedstock=$myrow5['feedstockid'];
        $feedstock_source=$myrow5['feedstocksourceid'];
        $no_of_source=$myrow5['number_source'];
        $weight=$myrow5['weight'];
        $gasout=$myrow5['gas_out'];
   
       $sql6="INSERT INTO bio_fs_feedstockdetails(feedstockid,
                                                  weight,
                                                  feedstocksourceid,
                                                  number_source,
                                                  leadid,
                                                  gasout)
                                         VALUES(".$feedstock.",
                                                ".$weight.",
                                                ".$feedstock_source.",
                                                ".$no_of_source.",
                                                ".$lead_ID.",
                                                ".$gasout.")";
       $result6=DB_query($sql6,$db);
    }
    $sql_wt="SELECT SUM(weight),SUM(gasout)
                    FROM bio_fs_feedstockdetails
                    WHERE leadid     =".$lead_ID;
    $result_wt=DB_query($sql_wt,$db);
    $myrow_wt=DB_fetch_array($result_wt);
    $total_wt=$myrow_wt[0];
    $total_gas=$myrow_wt[1];
    }
    
    if($_POST['FSID']!=''){ 
        $InputError=1;
        
            $sql_fs="UPDATE bio_fs_entrydetails SET edegradable='".$easily_deg."',
                                                    sdegradable='".$slowly_deg."',
                                                    vsdegradable='".$vslowly_deg."',
                                                    ndegradable='".$none_deg."',
                                                    solid_waste='".$solid_waste."',
                                                    liquid_waste='".$liquid_waste."',
                                                    inst_type='".$insttype."',
                                                    plant_type='".$planttype."',
                                                    budget='".$budget."',
                                                    reg_type='".$regstr_type."' 
                                              WHERE fsentry_id=".$_POST['FSID'];
     

         $result_fs = DB_query($sql_fs,$db,_('The update/addition  failed because')); 

        
        
    }else{
$sql_fsentry="INSERT INTO bio_fs_entrydetails(leadid,
                                            teamid,
                                            edegradable,
                                            sdegradable,
                                            vsdegradable,
                                            ndegradable,
                                            solid_waste,
                                            liquid_waste,
                                            inst_type,
                                            plant_type,
                                            created_on,
                                            created_by,
                                            total_gas,
                                            budget,
                                            reg_type)
                                  VALUES('".$lead_ID."',
                                         '".$teamID."',
                                         '".$easily_deg."',
                                         '".$slowly_deg."',
                                         '".$vslowly_deg."',
                                         '".$none_deg."',
                                         '".$solid_waste."',
                                         '".$liquid_waste."',
                                         '".$insttype."',
                                         '".$planttype."',
                                         '".$currentdateSQL."',
                                         '".$user_ID."',
                                         '".$total_gas."',
                                         '".$budget."',
                                         '".$regstr_type."')";
        $msg = _('A Feasibility Study detailes are entered Successfully');
        $result_fs = DB_query($sql_fsentry,$db,_('The update/addition  failed because'));

        $fsentry_id=DB_Last_Insert_ID($Conn,'bio_fs_entrydetails','fsentry_id');
        
         $sql_lead="UPDATE bio_leads
                        SET leadstatus=10
                        WHERE leadid='$lead_ID'";
         $result_lead= DB_query($sql_lead,$db);
         
         $taskid=14;
         
         $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                          taskcompleteddate='".$currentdateSQL."' 
                   WHERE bio_leadtask.leadid=$lead_ID 
                     AND bio_leadtask.taskid=14 
                     AND taskcompletedstatus!=2
                     AND teamid=$assignedfrm";   
    DB_query($sql_flag,$db);
         
         
         
         $tempdrop="DROP TABLE IF EXISTS bio_feeddetailstemp";
         DB_query($tempdrop,$db);
         $temptable="CREATE TABLE bio_feeddetailstemp (
                             temp_id INT NOT NULL AUTO_INCREMENT ,
                             feedstockid INT NULL ,
                             weight DECIMAL NULL ,
                             feedstocksourceid INT NULL ,
                             number_source INT NULL ,
                             gas_out INT NULL,
               PRIMARY KEY ( temp_id ))";
         DB_query($temptable,$db);

         $sql="ALTER TABLE `bio_feeddetailstemp` 
                  ADD `status` INT 
                               NOT 
                               NULL 
                               DEFAULT 
                               '0'" ; 
         DB_query($sql,$db);
         
         
         

//-------------------------------------------------------- 

/*$sql_task="SELECT bio_target.taskid,bio_leadtasktarget.task_count
                                FROM bio_target,bio_leadtasktarget
                                WHERE assigneddate <= '$currentdateSQL'
                                AND duedate >= '$currentdateSQL'
                                AND officeid =".$office."
                                AND team_id =".$teamID." 
                                AND bio_target.taskid=bio_leadtasktarget.taskid
                                AND bio_leadtasktarget.target=14";    
                $result_task=DB_query($sql_task, $db);     
                $myrow_task=DB_fetch_array($result_task);
                $myrow_count1 = DB_num_rows($result_task);
                $task=$myrow_task[0];
                $target=$myrow_task[1];
                $target=$target+1; 
                if($myrow_task[0]>0){
                    $sql_leadtask="UPDATE bio_leadtasktarget
                        SET task_count=".$target."
                        WHERE taskid='$task'";
                    $result_leadtask= DB_query($sql_leadtask,$db);
                }    */
        
        
        ?>
      <script>
      
      var lead=<?php echo $lead_ID; ?>;
      window.opener.location='bio_instTaskview.php?lead='+ lead;
      window.close();

      </script>
      <?php
        
    }
}


    if (isset($_POST['customersubmit'])) {
        
      // exit;
        
        
        
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
           
           
      $sql_fs="UPDATE bio_fs_entrydetails SET reg_type='".$_POST['Regtype']."' WHERE leadid=".$_POST['LeadID']; 
      $msg = _('A Feasibility Study detailes are entered Successfully'); 
      $result_fs = DB_query($sql_fs,$db,_('The update/addition  failed because')); 
  
  
}

           




unset($_POST['Regtype']);
 


echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">FEASIBILITY STUDY ENTRY</font></center>';
    
    
echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style="width:70%"><tr><td>';
echo"<div id=fullpanel>";
echo"<div id=editpanel>"; 
echo"<div id=panel>";
 
echo '<table><tr>';



//==========================================  Panel Begins

echo'<td>';

echo"<fieldset style='width:835px;height:400px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";
echo"<div id=cus_details>";
echo"<table width=100% border=0>";

if($_GET['lead']!=''){
    
    $leadid=$_GET['lead'];
  $sql="SELECT bio_leads.leadid,
               bio_cust.cust_id,
               bio_cust.custname,
               bio_cust.contactperson,
               bio_cust.custphone,
               bio_cust.custmob,
               bio_cust.area1,
               bio_cust.state,
               bio_cust.houseno,
               bio_cust.housename,
               bio_cust.pin,
               bio_cust.custmob,
               bio_cust.custmail,
               bio_cust.cust_id,
               bio_cust.nationality,
               bio_cust.LSG_type,
               bio_cust.LSG_name,
               bio_cust.block_name,
               bio_cust.LSG_ward,
               bio_cust.village,
               bio_cust.taluk,
               bio_leads.leaddate,
               bio_leads.teamid, 
               bio_district.district,   
               bio_leads.investmentsize,
               bio_leadteams.teamname,
               bio_leadtask.teamid,
               bio_cust.contact_desig,
               bio_cust.nature_org,
               bio_cust.head_org,
               bio_cust.headdesig,
               bio_cust.headphone,
               bio_cust.headmob,
               bio_cust.headmail,
               bio_cust.district AS dist
         FROM  bio_leads,bio_cust,bio_district,bio_leadtask,bio_leadteams
         WHERE bio_leads.cust_id=bio_cust.cust_id
           AND bio_district.did=bio_cust.district
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality 
           AND bio_leadtask.leadid=bio_leads.leadid
           AND bio_leadtask.taskid=14
           AND bio_leadtask.taskcompletedstatus=0
           AND bio_leadtask.viewstatus=1
           AND bio_leadteams.teamid=bio_leadtask.teamid
           AND bio_leads.leadid=".$leadid;
// echo'sql= '.$sql;          
 $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result); 

 $no=0; 
 $k=0; 
 $custid=$myrow['cust_id'];
 $cperson=$myrow['contactperson'];
 $cname=$myrow['custname'];  
// if($myrow[custmob]!=''){
//     $cph=$myrow['custmob']; 
// }else{
//     $cph=$myrow['custphone']; 
// }
 
 $mobile=$myrow['custmob'];
 $code=$myrow['custphone'];
 $instsize=$myrow['investmentsize'];
 $team=$myrow['teamid'];
 $place=$myrow['area1'];
 $ste=$myrow['state'];
 $hno=$myrow['houseno'];
 $hname=$myrow['housename'];
 $pin=$myrow['pin'];
 $mail=$myrow['custmail'];  
 $cust_ID=$myrow['cust_id'];
 $teamid=$myrow['teamid'];
 $teamname=$myrow['teamname'];
 $leaddate=ConvertSQLDate($myrow['leaddate']);
 $cntdesig=$myrow['contact_desig'];
 $headorg=$myrow['head_org'];
 $hdesign=$myrow['headdesig'];
 $hmob=$myrow['headmob'];
 $hphe=$myrow['headphone'];
 $hmail=$myrow['headmail'];
 $orgnature=$myrow['nature_org'];
 $nationality=$myrow['nationality'];
 $state=$myrow['state'];
 $district=$myrow['dist'];                                
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
      
// $result1=DB_query($sql1,$db);
// $myrow=DB_fetch_array($result1);
 
//$ctry=$myrow['nationality'];  
//$dist=$myrow['district']; 
// $sql="SELECT * FROM bio_country WHERE bio_country.cid=$ctry";
//  $result=DB_query($sql,$db);
//  while($myrow=DB_fetch_array($result))
//  {    //echo $myrow[0]; 
//  $nid=$myrow[0]; 
//  $ctry=$myrow[1];    
//  } 
//  
//  
//  $sql="SELECT * FROM bio_state WHERE stateid=$ste AND bio_state.cid=$nid";
//  $result=DB_query($sql,$db);
//  while($myrow=DB_fetch_array($result))
//  {    //echo $myrow[0]; 
//  $sid=$myrow[0]; 
//  $ste=$myrow[3];    
//  $steid1=$myrow[2];   
//  } 
 
 
 
 
 $sql_fsteam="SELECT bio_leadteams.teamname,
                     bio_leadtask.taskcompleteddate                    
               FROM  bio_leads,bio_leadtask,
                     bio_leadteams
               WHERE bio_leadtask.leadid=bio_leads.leadid
                 AND bio_leadtask.taskid=2
                 AND bio_leadtask.taskcompletedstatus=1
                 AND bio_leadtask.viewstatus=1
                 AND bio_leadteams.teamid=bio_leadtask.teamid
                 AND bio_leads.leadid=".$leadid;
// echo 'sql_fsteam='.$sql_fsteam;
 $result_fsteam=DB_query($sql_fsteam,$db);
 $myrow_fsteam=DB_fetch_array($result_fsteam);   
 $fs_team=$myrow_fsteam['teamname'];
 
 if($myrow_fsteam['taskcompleteddate']!=""){
    $fs_coducteddate=ConvertSQLDate($myrow_fsteam['taskcompleteddate']); 
 }else{
    $fs_coducteddate=""; 
 }
}


echo"<tr width=100%>";  

echo"<td width=36%>";
echo"<table border=0>";



echo'<tr>';
echo'<td width=50%>Registration Type</td>';
echo'<td><select name="Regtype" id="regtype"  style="width:100%">';

$sql1="SELECT * FROM bio_fs_regtype";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['regid']==$_POST['Regtype'])
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
    echo $myrow1['regid'] . '">'.$myrow1['regname'];  
    echo '</option>';
  }
echo'</select></td></tr>';







echo'<tr>';
echo'<td width=50%>Nature of Organisation</td>';
echo'<td><select name="Nature" id="nature"  style="width:100%">';

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
echo"<td><input type='text' name='Custname' id='custname' value='$cname' style='width:98%'></td></tr>";
echo"<tr></tr>";


echo"<tr><td>Building Name/No:</td>";
echo"<td><input type='text' name='Houseno' id='Houseno' value='$hno' style='width:98%'></td></tr>";
    
echo "<tr><td>Organisation Street:</td><td>";
echo"<input type='text' name='HouseName' id='HouseName' value='$hname' style='width:98%'></td></tr>";

echo "<tr><td>Organisation Area:</td>";
echo"<td><input type='text' name='Area1' id='Area1' value='$place' style='width:98%'></td></tr>";

echo "<tr><td>Post Office:</td>";
echo"<td><input type='text' name='Area2' id='Area2' value='$po' style='width:98%'></td></tr>";

echo" <tr><td>Pin:</td>";
echo"<td><input type='text' name='Pin' id='Pin' value='$pin' style='width:98%'></td></tr>"; 

  

//---------country--------------//    
    
    echo"<tr ><td width=50%>Country</td><td>";
    echo '<select name="country" id="country" onchange="showstate(this.value)" style="width:100%">';
    
    $sql="SELECT * FROM bio_country ORDER BY cid";     
    $result=DB_query($sql,$db);
    
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
 
 echo"<tr id='showstate' width=50%><td>State</td><td>";
 echo '<select name="State" id="state" style="width:100%" onchange="showdistrict(this.value)">';
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


echo"<tr id='showdistrict' width=50%>";

echo"<td>District</td><td>";
echo '<select name="District" id="Districts" style="width:100%" onchange="showtaluk(this.value)">';          
     
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
    echo '<td><select name="lsgType" id="lsgType" style="width:100%" onchange=showblock(this.value)>';             
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
                    echo '<td width=50%><select name="lsgName" readonly id="lsgName" style="width:190px">';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td></tr>';    
                    echo '</table>';      
        }
    }elseif($LSGtypeid==2){
            //echo"2222222";
        echo '<table align=left >';
        echo'<tr><td width=50%>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:100%">';
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
        echo'<tr><td>' . _('Block Name') . ':</td>';    
        $sql="SELECT * FROM bio_block WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:100%">';
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
      
      echo '<tr><td>' . _('Panchayat Name') . ':</td>';         
         
         $sql="SELECT * FROM bio_panchayat 
                        WHERE country=$nationality 
                            AND state=$state 
                            AND district=$district 
                            AND id=$block_name";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:100%">';
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
      <td><input type="Text" name="lsgWard" id="lsgWard" style=width:98% maxlength=15 value="'.$LSG_ward.'"></td></tr>';

      if($district!="")  {  
    echo"<tr><td>Taluk</td><td>";
    $sql="SELECT * FROM bio_taluk WHERE country=$nationality AND state=$state AND district=$district";
    $result=DB_query($sql,$db);
    echo '<select name="taluk" id="taluk" style="width:100%" onchange="showvillage(this.value)">';
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

//if($eid!="")  { 
if($taluk!="")  { 
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
  echo '<select name="village" id="village" style="width:100%">';
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
echo"</td>";

echo"<td width=33%>";
echo"<table  border=0>";

echo "<tr><td width=50%>Contact Person</td>";
echo "<td><input type='text' name='contactPerson' id='contactPerson' value='$cperson' style='width:96%'></td></tr>";

echo"<tr><td width=50%>Designation</td>";
echo"<td><input type='text' name='Designation' id='designation' value='$cntdesig' style='width:96%'></td></tr>";

echo"<tr><td width=50%>Mobile number</td>";
echo"<td><input type='text' name='mobile' id='mobile' value='$mobile' style='width:96%'></td></tr>";

echo"<tr><td>Phone Number</td>";
echo"<td><input type='text' name='code' id='code' value='$code' style='width:96%'></td></tr>"; 

echo"<tr><td width=50%>Email</td>";
echo"<td><input type='text' name='email' id='email' value='$mail' style='width:96%'></td></tr>";

echo "<tr><td>Head of Organisation</td>";
echo "<td><input type='text' name='Orghead' id='orghead' value='$headorg' style='width:96%'></td></tr>";

echo"<tr><td width=50%>Designation</td>";
echo"<td><input type='text' name='Hdesig' id='hdesig' value='$hdesign' style='width:96%'></td></tr>";

echo"<tr><td width=50%>Mobile number</td>";
echo"<td><input type='text' name='Hmobile' id='hmobile' value='$hmob' style='width:96%'></td></tr>";

echo"<tr><td>Phone Number</td>";
echo"<td><input type='text' name='hphone' id='hphone' value='$hphe' style='width:96%'></td></tr>"; 

echo"<tr><td width=50%>Email</td>";
echo"<td><input type='text' name='Hmail' id='hmail' value='$hmail' style='width:96%'></td></tr>";

echo"<input type='hidden' name='customerid' id='custid' value='$cust_ID' style='width:96%'>";
 




echo"</table>";
echo"</td>";

echo"<td width=30%>";
echo"<table border=0>";

echo"<tr><td width=50%>Lead Date</td>";
echo"<td><input style='width:96%' type='text' name='Leaddate' id='leaddate' value='$leaddate'></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%>Feasibility study conducted by</td>";
echo"<td><input style='width:96%' type='text' name='Team' id='team' value='$fs_team'></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%>Conducted date</td>";
echo"<td><input style='width:96%' type='text' name='ConductedDate' id='conducteddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' value='".$fs_coducteddate."'></td></tr>";

/*echo"<tr><td width=50%>  End date</td>";
echo"<td><input style='width:96%' type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "'></td></tr>";*/

$sql="SELECT categoryid,categorydescription,maincatid from stockcategory,
             bio_maincat_subcat
             WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid
             AND bio_maincat_subcat.maincatid =1";
      $result=DB_query($sql,$db);

echo '<tr><td id="subcat">Plant type</td>';
echo'<td><select name="PlantType" id="planttype" style="width:100%">';
//      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          if($myrow['maincatid']==1){
             echo "<option selected value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }else{
             echo "<option value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }
          
      }
      echo '</select></td></tr>';

 

echo"<tr><td width=50%>Budget</td>";
echo"<td><input style='width:96%' type='text' name='Budget' id='budget' value='$instsize'></td></tr>";
echo"<tr></tr>";

/*echo"<tr><td width=50%></td>";
echo"<td></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%></td>";
echo"<td></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%></td>";
echo"<td></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%></td>";
echo"<td></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%></td>";
echo"<td></td></tr>";
echo"<tr></tr>";*/



echo"</table>";
echo"</td>";

echo"</tr>";

  
echo"</table>";
echo"</div>";
 echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
echo"<input type='hidden' name='TeamID' id='teamid' value='$teamid'>";


echo"</fieldset>";   
echo"</td></tr></table>";
echo"</div>";// panel div

//========================================== Buttons

echo "<div>";
echo"<fieldset style='width:835px;height:65px'>"; 
echo"<legend>Select</legend>";
echo"</legend>";
echo"<table width=100%>";

echo'<tr><td colspan=2><p><div class="centre">
      <input type=button name=Basicdata value="' . _('Basic Data') . '" onclick="basicdatapanel()">';
 
//echo '<input type=button name=Orgdata value="' . _('Organisation Data') . '" onclick="organisationdata()">'; 
//echo '<input type=button name=Powergeneration value="' . _('Power Generation') . '" onclick="powergeneration()">';
//echo '<input type=button name=Integratedwaste value="' . _('Integrated Waste Treatment') . '" onclick="intwastetreat()">';
//echo '<input type=button name=beneficiary value="' . _('Beneficiary') . '" onclick="Beneficiary()">'; 

echo '<input type=button name=Soilcondition value="' . _('Outputtype') . '" onclick="outputtypepanel()">'; 
echo '<input type=button name=Wasteanalysis value="' . _('Waste Analysis') . '" onclick="Wasteanalysis1()">'; 

      
echo'</div>';
echo'</td></tr>';



echo"</table>";
echo"</fieldset>";
echo "</div>";
echo "</div>";// editpanel div 


//========================================== Change Panels

echo"<table>";
echo'<tr><td colspan=2><p><div id="changepanels">';


echo'</div>';

echo'</td></tr>';
//echo"</table>";
echo'</div>'; // full panel
echo"</td></tr></table>";


//========================================== Submit Buttons

echo"<table>";
//echo'<tr><td colspan=2><p><div class="centre">
//      <input type=submit name=submit value="' . _('Submit') . '" onclick="if(log_in()==1)return false;">';
echo  '<tr><td colspan=2><p><div class="centre">
      <input name="clear" type="submit" value=Clear >
      <input id="shwprint" type="button" name="shwprint" value="view/hide proposals">
      <input type="Button" class=button_details_show name=details VALUE="' . _('Details') . '">';

echo'</div>';
echo'</td></tr>';

echo'</div>'; 
echo"</td></tr></table>";
echo'</div>';

echo'</form>';


//========================================== Buttons Ends


echo"<div id='selectiondetails'>";

echo"<fieldset style='width:835px; overflow:auto;'>";
echo"<legend>All Links</legend>";
echo '<table width="100%">
    <tr>
        <th width="50%">' . _('Masters') . '</th>
        <th width="50%">' . _('Reports') . '</th>
   
    </tr>';
echo"<tr><td  VALIGN=TOP >";
//echo '<a href="bio_activefeasibilitystudyproposals.php" style=cursor:pointer; >' . _('Active FS') . '</a><br>';
//echo '<a href="bio_passivefeasibilitystudyproposals.php" style=cursor:pointer; >' . _('Passive FS') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cutomer Ledger') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cash Book') . '</a><br>';


echo"</td><td  VALIGN=TOP >";
//echo '<a href="bio_dfeaprint_A5p.php" style=cursor:pointer; onclick=()>' . _('Print Feasibility Reciept') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Print Covering Letter') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=addNewSeasonName()>' . _('Add New Season Name') . '</a><br>';
echo"</td></tr>";
echo'</table>';
echo"</fieldset>";

echo "</div>";


//========================================== Grid for pending feasibilities
if(!isset($_GET['tid'])){
echo'<div id="leadgrid">';
echo"<fieldset style='width:835px'><legend>Lead Details</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>"; 
echo"<tr>";
echo'<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo'<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname1' id='byname1'></td>";
echo"<td><input type='text' name='byplace1' id='byplace1'></td>";
echo'<td><select name="off1" id="off1" style="width:100px">';
echo'<option value=0></option>';
 
 $sql1="select * from bio_office";
 $result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo '</select></td>';
echo '<td><select name="leadsrc1" id="leadsrc1" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';

    $sql1="select * from bio_leadsources";
    $result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
}                                                             
echo '</select></td>';      

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='search'></td>";
echo"</tr>";
echo"</table>";


echo "<div style='height:200px; overflow:scroll;'>"; 
echo"<table style='width:100%'> ";

echo"<tr><th>Slno</th><th>Name</th><th>Date</th><th>Output</th><th>Team</th></tr>";

    $office=$_SESSION['UserStockLocation'];   
    $empid=$_SESSION['empid'];
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
   
    $team=array();  
   $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
   $result6=DB_query($sql6,$db);
   while($row6=DB_fetch_array($result6))
   {
        $team[]=$row6['teamid'];
   }


   $team_array=join(",", $team);

  $sql="SELECT bio_cust.cust_id AS custid,
               bio_cust.custname AS custname,               
               bio_cust.area1 AS place,
               bio_enquirytypes.enquirytype AS enqtype,
               bio_outputtypes.outputtype AS outputtype,
               bio_leadtask.leadid AS leadid, 
               bio_leadtask.duedate AS duedate,
               bio_leadteams.teamname AS teamname,
               bio_leadsources.sourcename AS sourcename,
               bio_office.id AS officeid
          FROM bio_cust,
               bio_leads,
               bio_leadtask,
               bio_leadteams,
               bio_leadsources,
               bio_enquirytypes,
               bio_office,
               bio_outputtypes   
         WHERE bio_cust.cust_id=bio_leads.cust_id 
           AND bio_leads.leadstatus=3
           AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
           AND bio_leadsources.id=bio_leads.sourceid  
           AND bio_office.id=bio_leadsources.officeid
           AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid 
           AND bio_leadtask.taskid=14 
           AND bio_leadtask.taskcompletedstatus=0
           AND bio_leadtask.viewstatus=1
           AND bio_leadtask.leadid=bio_leads.leadid
           AND bio_leadteams.teamid=bio_leadtask.teamid
           AND bio_leadtask.teamid IN ($team_array)
";     


 //echo $sql5;
 if(isset($_POST['filterbut1']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off1'];
  //  echo $officeid;
    if (isset($_POST['byname1']))  {        
    if ($_POST['byname1']!='')   
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname1']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace1'])) {
    if ($_POST['byplace1']!='') 
    $sql .= " AND bio_cust.area1 LIKE '%".$_POST['byplace1']."%'"; 
    }
    
    if (isset($_POST['off1']))    {
    if (($_POST['off1']!='')&&($_POST['off1']!='0'))
    $sql .=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc1'])) {
    if (($_POST['leadsrc1']!='ALL') && ($_POST['leadsrc1']!=0))
    $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc1']."'";
    }
 }      
$result=DB_query($sql,$db);
echo '<tbody>';
echo '<tr>';                                       
$no=0; 
$k=0; 
while($myrow=DB_fetch_array($result))
{
    $no++;
    if ($k==1)
    {
        echo '<tr class="EvenTableRows">';
        $k=0;
    }else 
    {
        echo '<tr class="OddTableRows">';
//                    $k=1;     
    }
    $leadid=$myrow['leadid'];
    $custid=$myrow['custid']; 
    
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td></tr>",
            $no,
            $myrow['custname'],
            ConvertSQLDate($myrow['duedate']),
            $myrow['outputtype'],
            $myrow['teamname']);
             
}
//echo"<input type='text' name='custid' id='custid' value='$custid'>$custid";
echo"</td>";
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";
echo"</div>";
}

//========================================== Grid for Proposal Details

echo'<div id="proposalgrid">';
echo"<fieldset style='width:835px'><legend>Proposal Details</legend>"; 
   
 
echo"<table style='width:100%' border=0> ";

echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>"; 
echo"<tr>";
echo'<td><input type="text" style="width:100px" id="df2" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df2" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo'<td><input type="text" style="width:100px" id="dt2" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt2" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname2' id='byname2'></td>";
echo"<td><input type='text' name='byplace2' id='byplace2'></td>";
echo'<td><select name="off2" id="off2" style="width:100px">';
echo'<option value=0></option>'; 

    $sql1="select * from bio_office";
    $result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo'</select></td>';
echo'<td><select name="leadsrc2" id="leadsrc2" style="width:100px">';
echo'<option value=0></option>';
echo'<option value="ALL">Select ALL</option>';

    $sql1="select * from bio_leadsources";
    $result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
}                                                             
echo '</select></td>';      

echo"<td><input type='submit' name='filterbut2' id='filterbut2' value='search'></td>";
echo"</tr>";

echo"</table>";
   
echo "<div style='height:200px; width:100%; overflow:scroll;'>";
echo"<table style='width:100%'> ";
echo"<tr><th>Slno</th>
         <th>Name</th>
         <th>Solid Feedstock</th>
         <th>Organic waste water</th>
         <th>Team</th></tr>";

  $sql8="SELECT bio_cust.custname AS custname,               
                bio_cust.area1 AS place,
                bio_leads.leadid AS leadid,
                bio_leads.leaddate,  
                bio_leadteams.teamname AS teamname,
                bio_leadsources.sourcename AS sourcename,
                bio_office.id AS officeid,
                bio_fs_entrydetails.fsentry_id,
                bio_fs_entrydetails.solid_waste,
                bio_fs_entrydetails.liquid_waste 
           FROM bio_cust,
                bio_leads,
                bio_leadteams,
                bio_leadsources,
                bio_office,
                bio_fs_entrydetails  
          WHERE bio_cust.cust_id=bio_leads.cust_id 
            AND bio_leadteams.teamid=bio_fs_entrydetails.teamid
            AND bio_leadsources.id=bio_leads.sourceid  
            AND bio_office.id=bio_leadsources.officeid
            AND bio_fs_entrydetails.leadid=bio_leads.leadid  
            AND bio_fs_entrydetails.created_by='".$_SESSION['UserID']."'";

if(isset($_POST['filterbut2']))
 {  
    if ((isset($_POST['df2'])) && (isset($_POST['dt2'])))   {
    if (($_POST['df2']!="") && ($_POST['dt2']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df2']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt2']);
    $sql8.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off2'];
  //  echo $officeid;
    if (isset($_POST['byname2']))  {        
    if ($_POST['byname2']!='')   
    $sql8 .= " AND bio_cust.custname LIKE '%".$_POST['byname2']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace2'])) {
    if ($_POST['byplace2']!='') 
    $sql8 .= " AND bio_cust.area1 LIKE '%".$_POST['byplace2']."%'"; 
    }
    
    if (isset($_POST['off2']))    {
    if (($_POST['off2']!='')&&($_POST['off2']!='0'))
    $sql8.=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc2'])) {
    if (($_POST['leadsrc2']!='ALL') && ($_POST['leadsrc2']!=0))
    $sql8.=" AND bio_leads.sourceid='".$_POST['leadsrc2']."'";
    }
 }   
  $sql8.=" ORDER BY leadid DESC";
//$sql8="SELECT bio_feasibilitystudy.feasibilitystudy_id,
//              bio_feasibilitystudy.leadid,
//              bio_feasibilitystudy.teamid,
//              bio_feasibilitystudy.feasibilitystudy_charge,
//              bio_feasibilitystudy.feasibilitystudy_startdate,
//              bio_feasibilitystudy.feasibilitystudy_enddate,
//              bio_leads.cust_id,
//              bio_cust.custname,
//              bio_leadteams.teamname
//         FROM bio_feasibilitystudy,bio_leads,bio_cust,bio_leadteams
//         WHERE bio_feasibilitystudy.leadid=bio_leads.leadid
//         AND bio_leads.cust_id=bio_cust.cust_id
//         AND bio_leadteams.teamid=bio_feasibilitystudy.teamid";


$result8=DB_query($sql8,$db);
$k=0;
$no=0;
while($myrow8=DB_fetch_array($result8))     {
    $fs_entryid=$myrow8['fsentry_id'];
    $leadid=$myrow8['leadid'];
        
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    }else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
    $no++;
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a  style='cursor:pointer;'  onclick=FsEdit('$fs_entryid','$leadid')>" . _('Edit') . "</a></td></tr>",
            $no,
            $myrow8['custname'],
            $myrow8['solid_waste'],
            $myrow8['liquid_waste'],
            $myrow8['teamname']);
            
}
echo '<tbody>';
echo"</tr></tbody>
</table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 
echo"</div>";



?>


<script type="text/javascript">



$(document).ready(function() {
    $("#basicdata").hide();
    $("#latrinedata").hide(); 
    $("#organisationaldata").hide(); 
    $("#selectiondetails").hide();

    
    
    $('.showbasicdata').click(function() {
  $('#basicdata').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

//    $('.showsoilcondition').click(function() {
//  $('#soilcondition').slideToggle('slow', function() {
    // Animation complete.
//  });
//});

    $('.showorganisationaldata').click(function() {
  $('#organisationaldata').slideToggle('slow', function() {
    // Animation complete.
  });
});
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 
    
    
    
    $("#error").fadeOut(3000);
    $("#warn").fadeOut(8000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);
    
    
    
    $("#proposalgrid").hide();

    //$('#shwlead').click(function() {
//        $('#leadgrid').slideToggle('slow',function(){});
//        $('#proposalgrid').slideToggle('slow',function(){});
//    });

    $('#shwprint').click(function() {
        $('#proposalgrid').slideToggle('slow',function(){});
       $('#leadgrid').slideToggle('slow',function(){});
    });
    
    
    
}); 


function log_in()
{
var f=0;
var p=0;
if(f==0){f=common_error('custname','Please select a lead');  if(f==1){return f; }  }
if(f==0){f=common_error('regtype','Please select Registration Type');  if(f==1){return f; }  }  
//if(f==0){f=common_error('inst','Please select institution type ');  if(f==1){return f; }  }  
//if(f==0){f=common_error('feedsource','Please select feedstock source ');  if(f==1){return f; }  } 
//if(f==0){f=common_error('feedstock','Please select feedstock ');  if(f==1){return f; }  } 


}




function passid(str1,str2){
  // alert (str1) ;
   //alert (str2) ;
   
$("#feedstockdetails").show();
//alert(str1);
if (str1=="")
  {
  document.getElementById("panel").innerHTML="";
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
    document.getElementById("panel").innerHTML=xmlhttp.responseText;  
     
    }
  } 
xmlhttp.open("GET","bio_customerdetailsfsentry.php?p=" + str1,true);
xmlhttp.send(); 

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
  }

xmlhttp2.onreadystatechange=function()
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
 
    document.getElementById("changepanels").innerHTML=xmlhttp2.responseText;  
        $("#latrinedata").hide(); 
    }
  } 
xmlhttp2.open("GET","bio_feasibilitystudyentry-basicdatapanel.php?leadid="+ str1,true);
xmlhttp2.send();
 
}






function basicdatapanel(){

var leadid=document.getElementById("leadid").value;
if(leadid==''){
    alert("Please select a lead");
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
 
    document.getElementById("changepanels").innerHTML=xmlhttp.responseText;  
        $("#latrinedata").hide(); 
    }
  } 
xmlhttp.open("GET","bio_feasibilitystudyentry-basicdatapanel.php?leadid="+ leadid,true);
xmlhttp.send();
 
}


function basicdatasubmit(){
    
var chks2 = document.getElementsByName('WasteDisposal');      
var hasChecked = false;
var wastedisp='';
for (var i = 0; i < chks2.length; i++)
{
if (chks2[i].checked)
{
wastedisp= wastedisp + "," + chks2[i].value;
hasChecked = true;

}
}
if (hasChecked == false)
{
f=1;
alert("Please select at least one WasteDisposal system.");
return f; 
} 

var chks3 = document.getElementsByName('WaterSource');      
var hasChecked = false;
var watersource=''; 
for (var i = 0; i < chks3.length; i++)
{
if (chks3[i].checked)
{
watersource= watersource + "," + chks3[i].value;
hasChecked = true;

}
}
if (hasChecked == false)
{
f=1;
alert("Please select at least one WaterSource.");
return f; 
} 


var watercollect=document.getElementById("watercollection").value;
var beneficiary=document.getElementById("beneficiary").value;
var watercons=document.getElementById("consumption").value;
var waterdisc=document.getElementById("waterdischarge").value;
var lpg=document.getElementById("lpg").value;
var firewood=document.getElementById("firewood").value;            
var others=document.getElementById("others").value;
var leadid=document.getElementById("leadid").value;
var wateravailability=document.getElementById("wateravailability").value;
var waterlevel=document.getElementById("waterlevel").value;
var soilnature=document.getElementById("soilnature").value;
var wastewatertreat=document.getElementById("wastewatertreat").value;
var latrineconnected=document.getElementById("latrineconnected").value;
var latrine=document.getElementById("latrine").value; 
var distance=document.getElementById("distance").value;
var space=document.getElementById("plantspace").value;

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
 
    document.getElementById("changepanels").innerHTML=xmlhttp.responseText;  

    }
  } 
xmlhttp.open("GET","bio_feasibilitystudyentry-basicdatapanel-submit.php?watercollect=" + watercollect + "&beneficiary=" + beneficiary + "&wastedisp=" + wastedisp + "&watercons=" + watercons + "&waterdisc=" + waterdisc + "&lpg=" + lpg + "&firewood=" + firewood + "&others=" + others + "&leadid=" + leadid+ "&wateravailability=" + wateravailability + "&waterlevel="+ waterlevel +"&watersource=" + watersource + "&soilnature=" + soilnature +"&wastewatertreat=" + wastewatertreat + "&latrineconnected=" + latrineconnected + "&latrine=" + latrine + "&distance=" + distance  + "&space=" + space,true);
xmlhttp.send(); 


if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
  }

xmlhttp2.onreadystatechange=function()
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
 
    document.getElementById("changepanels").innerHTML=xmlhttp2.responseText;  
        $("#latrinedata").hide(); 
    }
  } 
xmlhttp2.open("GET","bio_feasibilitystudyentry-outputpanel.php?leadid="+ leadid,true);
xmlhttp2.send();


}




function outputtypepanel(){

var leadid=document.getElementById("leadid").value;
if(leadid==''){
    alert("Please select a lead");
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
 
    document.getElementById("changepanels").innerHTML=xmlhttp.responseText;  

    }
  } 
xmlhttp.open("GET","bio_feasibilitystudyentry-outputpanel.php?leadid="+ leadid,true);
xmlhttp.send(); 
}




function outputsubmit(){
    
var chks2 = document.getElementsByName('outputtype[]');      
var hasChecked = false;
var outputtype='';
for (var i = 0; i < chks2.length; i++)
{
if (chks2[i].checked)
{
outputtype= outputtype + "," + chks2[i].value;

hasChecked = true;

}
}
if (hasChecked == false)
{
f=1;
alert("Please select at least one Output type.");
return f; 
}     

 var leadid=document.getElementById("leadid").value;  

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
 
    document.getElementById("changepanels").innerHTML=xmlhttp.responseText;  

    }
  } 
xmlhttp.open("GET","bio_feasibilitystudyentry-outputpanel-submit.php?type=" + outputtype + "&leadid=" + leadid,true);
xmlhttp.send(); 


if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
  }

xmlhttp2.onreadystatechange=function()
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
 
    document.getElementById("changepanels").innerHTML=xmlhttp2.responseText;  
        $("#latrinedata").hide(); 
    }
  } 
xmlhttp2.open("GET","bio_feasibilitystudyentry-wasteanalysis.php?leadid="+ leadid,true);
xmlhttp2.send();


}






function Wasteanalysis1(){
//alert("kjkjkjk");
var leadid=document.getElementById("leadid").value;
if(leadid==''){
    alert("Please select a lead");
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
 
    document.getElementById("changepanels").innerHTML=xmlhttp.responseText;  

    }
  }    
xmlhttp.open("GET","bio_feasibilitystudyentry-wasteanalysis.php?leadid=" + leadid,true);
xmlhttp.send(); 
}

function Wasteanalysis2(){
//alert("kjkjkjk");
var leadid=document.getElementById("leadid").value;
if(leadid==''){
    alert("Please select a lead");
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
 
    document.getElementById("changepanels").innerHTML=xmlhttp.responseText;  

    }
  }    
xmlhttp.open("GET","bio_feasibilitystudyentry-wasteanalysis2.php?leadid=" + leadid,true);
xmlhttp.send(); 
}



function showInputs(str1){
var feedsource=document.getElementById("feedsource").value;
//alert(str1);
//alert(feedsource);

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
 
    document.getElementById("showinputfields").innerHTML=xmlhttp.responseText;  

    }
  } 
xmlhttp.open("GET","bio_feasibilitystudyentry-input.php?feed=" + str1 + "&feedsource="+feedsource,true);
xmlhttp.send(); 
}






function showCD4()
{
    var leadid=document.getElementById("leadid").value;
    var str1=document.getElementById("feedsource").value;
    var str3=document.getElementById("feedstock").value;
    if(str1==""){
        alert("Select Feedstock source"); 
        document.getElementById("feedsource").focus();  return false;  

    }
    if(str3==""){
        alert("Select Feedstock"); 
        document.getElementById("feedstock").focus();  return false;
    }
    var unit=document.getElementById("selectedunit").value;
//    alert(unit);
    if(unit==1 || unit==2){
       var str4=document.getElementById("weight").value;
       if(str4==""){
            alert("Please enter weight"); 
            document.getElementById("weight").focus();  return false;  
       }
       str2=0; 
    }else{
           var str2=document.getElementById("numsource").value;
           if(str2==""){
               alert("Please enter No. of sources"); 
               document.getElementById("numsource").focus();  return false;  
           }
           str4=0; 
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

    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    document.getElementById('feedstock').value="";       
    
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails-conceptpro.php?feedsource=" + str1  + "&numsource=" + str2 + "&feedstock=" + str3 + "&weight=" + str4 + "&unit=" + unit + "&leadid="+ leadid,true);
xmlhttp.send();
   
//wasteCatagory();

}
function showFeeds(str1,str2){
//alert(str1);
if (str1=="")
  {
  document.getElementById("feeds").innerHTML="";
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
    document.getElementById("feeds").innerHTML=xmlhttp.responseText;  
     
    }
  } 
xmlhttp.open("GET","bio_showfeeds.php?p=" + str1,true);
xmlhttp.send(); 
}



function showFeedstocksource(str1){
//alert(str1);
if (str1=="")
  {
  document.getElementById("showfeedsource").innerHTML="";
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
    document.getElementById("showfeedsource").innerHTML=xmlhttp.responseText;  
     
    }
  } 
xmlhttp.open("GET","bio_showfeedstocksource.php?p=" + str1,true);
xmlhttp.send(); 
}

function customerpanelsubmit(){
   //alert (str1) ;
   
var leadid=document.getElementById("leadid").value;
 //alert (leadid) ;
var custid=document.getElementById("custid").value;
//alert (custid);
var custname=document.getElementById("custname").value;
//alert (custname);
var contactPerson=document.getElementById("contactPerson").value;
//alert (contactPerson); 
var Houseno=document.getElementById("Houseno").value;
//alert (Houseno);
var HouseName=document.getElementById("HouseName").value;
//alert (HouseName);
var Area1=document.getElementById("Area1").value;
//alert (Area1);
var Area2=document.getElementById("Area2").value;
//alert (Area2);
var Pin=document.getElementById("Pin").value;
//alert (Pin);
var country=document.getElementById("country").value;
//alert (country);
var state=document.getElementById("state").value;
//alert (state);
var Districts=document.getElementById("Districts").value;
//alert (Districts);
var taluk=document.getElementById("taluk").value;
//alert (taluk);
var lsgType=document.getElementById("lsgType").value;
//alert (lsgType);
var lsgName=document.getElementById("lsgName").value;
//alert (lsgName);
var lsgWard=document.getElementById("lsgWard").value;
//alert (lsgWard);
var village=document.getElementById("village").value;
//alert (village);
var designation=document.getElementById("designation").value;
//alert (designation);
var mobile=document.getElementById("mobile").value;
//alert (mobile);
var code=document.getElementById("code").value;
//alert (code);
var email=document.getElementById("email").value;
//alert (email);
var orghead=document.getElementById("orghead").value;
//alert (orghead);
var hdesig=document.getElementById("hdesig").value;
//alert (hdesig);
var hmobile=document.getElementById("hmobile").value;
//alert (hmobile);
var hphone=document.getElementById("hphone").value;
//alert (hphone);
var hmail=document.getElementById("hmail").value;
//alert (hmail);
var budget=document.getElementById("budget").value;
//alert ();
//var gramaPanchayath=document.getElementById("").value;
//alert (gramaPanchayath);

 var custarray = new Array(leadid,custid,custname,contactPerson,Houseno,
                           HouseName,Area1,Area2,Pin,country,
                           state,Districts,taluk,lsgType,lsgName,
                           lsgWard,village,designation,mobile,code,
                           email,orghead,hdesig,hmobile,hphone,
                           hmail,budget);  


 
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
    document.getElementById("cus_details").innerHTML=xmlhttp.responseText;  
     
    }
  } 
xmlhttp.open("GET","bio_feasibilityentry-customerpanel-submit.php?custarray=" + custarray,true);
                                                                 
xmlhttp.send();
}




function FsEdit(str1,str2){ 
//alert(str1);
alert(str2);

if (str1=="")
  {
  document.getElementById("editpanel").innerHTML="";
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
    document.getElementById("editpanel").innerHTML=xmlhttp.responseText;  
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_editFSentrydetails.php?p=" + str1 + "&q=" + str2,true);
xmlhttp.send();    
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
alert(str1);   alert(str2);       alert(str3);
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
xmlhttp.open("GET","bio_CustlsgSelection_fsentry.php?taluk=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
//xmlhttp.open("GET","bio_CustlsgSelection_fsentry.php?country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
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
xmlhttp.open("GET","bio_CustlsgSelection_fsentry.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
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
xmlhttp.open("GET","bio_CustlsgSelection_fsentry.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
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
xmlhttp.open("GET","bio_CustlsgSelection_fsentry.php?village=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

function latrineview(str){
    //alert("qqqqqqqqqqq");
    if(str==1){
    $("#latrinedata").show();      
    }
    else
    {
    $("#latrinedata").hide();
    
}
}  

function editfeedstok(str)
{
//alert(str);
//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;
 if (str=="")
  {
  document.getElementById("editfeed").innerHTML="";
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
    document.getElementById("editfeed").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails-conceptpro.php?upfeedstockid=" + str,true);
xmlhttp.send();    

}

function doedit()
{
//   alert("hii");
//   alert(str);

var source=document.getElementById("sourceid").value;    
var feedstock=document.getElementById("h1feedstock").value;
var tempid=document.getElementById("tempid").value;
var feedunit=document.getElementById("unitid").value;
// alert(source); alert(feedstock);     alert(tempid);
if(feedunit==1){
    var weight=document.getElementById("h1feedswt").value;
    
}else if(feedunit==2){
    var weight=document.getElementById("h1feedswt").value;
    
}else if(feedunit==3){
    var numsource=document.getElementById("h1feedsno").value;
    
}
 
if (tempid=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails-conceptpro.php?edid=" + tempid + "&feedstock=" + feedstock + "&source=" + source + "&feedunit=" +feedunit +"&weight="+weight +"&numsource="+numsource,true);
xmlhttp.send();

}  

function deletfeedstok(str)
{
//   alert("hii");
//   alert(str);


// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails-conceptpro.php?delet=" + str,true);
xmlhttp.send();    

}  





</script>