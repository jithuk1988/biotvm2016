<?php
  $PageSecurity = 80;
include('includes/session.inc');
$fs_entryID=$_GET['p'];
$leadid=$_GET['q'];

    $sql_p="DELETE FROM bio_feeddetailstemp";
    DB_query($sql_p,$db);

     /*  $sql="SELECT bio_leads.leadid,
                    bio_cust.cust_id,
                    bio_cust.custname,
                    bio_cust.contactperson,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.area1,
                    bio_cust.state,
                    bio_cust.houseno,
                    bio_cust.housename,
                    bio_cust.area2, 
                    bio_cust.pin,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.cust_id,
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
                    bio_leads.leaddate,
                    bio_leads.teamid, 
                    bio_district.district,   
                    bio_leads.investmentsize,
                    bio_leadtask.teamid,
                    bio_leadteams.teamname,
                    bio_fs_entrydetails.plant_type,
                    bio_fs_entrydetails.budget,
                    bio_fs_entrydetails.reg_type,
                    bio_cust.district AS dist
              FROM  bio_leads,bio_cust,
                    bio_district,
                    bio_leadtask,
                    bio_fs_entrydetails,
                    bio_leadteams
              WHERE bio_leads.cust_id=bio_cust.cust_id
                AND bio_district.did=bio_cust.district
                AND bio_district.stateid=bio_cust.state
                AND bio_district.cid=bio_cust.nationality 
                AND bio_leadtask.leadid=bio_leads.leadid
                AND bio_leadteams.teamid=bio_leadtask.teamid
                AND bio_leads.leadid=bio_fs_entrydetails.leadid
                AND bio_leadtask.taskid=14
                AND bio_leadtask.taskcompletedstatus=1
                AND bio_leadtask.viewstatus=1
                AND bio_leads.leadid=".$leadid;     */
                
                $sql="SELECT
    `bio_leads`.`leadid`
    , `bio_cust`.`custname`
    , `bio_cust`.`contactperson`
    , `bio_cust`.`custphone`
    , `bio_cust`.`custmob`
    , `bio_cust`.`area1`
    , `bio_outputtypes`.`outputtype`
    , `bio_leadteams`.`teamname`
    , `bio_cust`.`custmail`
    , `bio_cust`.`cust_id`
    , `bio_cust`.`houseno`
        , `bio_cust`.`nationality`
    , `bio_cust`.`state`
    , `bio_cust`.`district`
    , `bio_cust`.`housename`
    , `bio_cust`.`pin`
    , `bio_fs_entrydetails`.`teamid`
    , `bio_fs_entrydetails`.`budget`
    , `bio_fs_entrydetails`.`reg_type`
    , `bio_fs_entrydetails`.`plant_type`
    , `bio_leads`.`investmentsize`
    , `bio_leads`.`leaddate`
    , `bio_cust`.`area2`
    , `bio_cust`.`contactperson`
    , `bio_cust`.`careof`
    , `bio_cust`.`taluk`
    , `bio_cust`.`LSG_type`
    , `bio_cust`.`LSG_name`
    , `bio_cust`.`block_name`
    , `bio_cust`.`LSG_ward`
    , `bio_cust`.`village`
    , `bio_cust`.`contact_desig`
    , `bio_cust`.`nature_org`
    , `bio_cust`.`custtype`
    , `bio_cust`.`head_org`
    , `bio_cust`.`headdesig`
    , `bio_cust`.`headphone`
    , `bio_cust`.`headmob`
    , `bio_cust`.`headmail`
FROM
    `bio_leads`
    LEFT JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    LEFT JOIN `bio_outputtypes` 
        ON (`bio_leads`.`outputtypeid` = `bio_outputtypes`.`outputtypeid`)
    LEFT JOIN `bio_fs_entrydetails` 
        ON (`bio_leads`.`leadid` = `bio_fs_entrydetails`.`leadid`)
    LEFT JOIN `bio_leadtask` 
        ON (`bio_leads`.`leadid` = `bio_leadtask`.`leadid`)
  LEFT JOIN `bio_leadteams` 
        ON (`bio_leadtask`.`teamid` = `bio_leadteams`.`teamid`)
        WHERE
                    bio_leadtask.taskid=14
                AND bio_leadtask.taskcompletedstatus=1
                AND bio_leadtask.viewstatus=1
                AND bio_leads.leadid=".$leadid;
             //echo $sql;   
 $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result); 

 $no=0; 
 $k=0; 
 $custid=$myrow['cust_id'];
 $cperson=$myrow['contactperson'];
 $cname=$myrow['custname'];  
 if($myrow[custmob]!=''){
     $cph=$myrow['custmob']; 
 }else{
     $cph=$myrow['custphone']; 
 }
 $instsize=$myrow['investmentsize'];
 $team=$myrow['teamid'];
 $place=$myrow['area1'];
 //$dist=$myrow['district'];
 $ste=$myrow['state'];
 $hno=$myrow['houseno'];
 $hname=$myrow['housename'];
 $po=$myrow['area2'];  
 $pin=$myrow['pin'];
 $mail=$myrow['custmail'];  
 $cust_ID=$myrow['cust_id'];
// $ctry=$myrow['nationality']; 
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
 
 $regstr_type=$myrow['reg_type'];
 
 $planttype=$myrow['plant_type'];
 $budget=$myrow['budget'];  
 
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
      
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 
 
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
               FROM  bio_leads,bio_leadtask,bio_leadteams
               WHERE bio_leadtask.leadid=bio_leads.leadid
                 AND bio_leadtask.taskid=2
                 AND bio_leadtask.taskcompletedstatus=1
                 AND bio_leadtask.viewstatus=1
                 AND bio_leadteams.teamid=bio_leadtask.teamid
                 AND bio_leads.leadid=".$leadid;
 
 $result_fsteam=DB_query($sql_fsteam,$db);
 $myrow_fsteam=DB_fetch_array($result_fsteam);   
 $fs_team=$myrow_fsteam['teamname'];
 if($myrow_fsteam['taskcompleteddate']!=""){
    $fs_coducteddate=ConvertSQLDate($myrow_fsteam['taskcompleteddate']); 
 }else{
    $fs_coducteddate=""; 
 }



 echo"<div id=panel>";  
echo '<table><tr>';
echo'<td valign=top>';

echo"<div id=cus_details>";               
          
echo"<fieldset style='width:835px;height:445px'>";
echo"<legend>Customer Details</legend>";
echo"<table width=100%>";

echo"<tr width=100%>";  

echo"<td width=36%>";
echo"<table width=100%>";


echo'<tr>';
echo'<td width=50%>Registration Type</td>';
echo'<td><select name="Regtype" id="regtype"  style="width:100%">';

$sql1="SELECT * FROM bio_fs_regtype";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['regid']==$regstr_type)
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
echo'<td>Nature of Organisation</td>';
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
echo"<td><input type='text' name='Custname' id='custname' value='$cname' style='width:96%'></td></tr>";
echo"<tr></tr>";


echo"<tr><td>Building Name/No:</td>";
echo"<td><input type='text' name='Houseno' id='Houseno' value='$hno' style='width:96%'></td></tr>";
    
echo "<tr><td>Organisation Street:</td><td>";
echo"<input type='text' name='HouseName' id='HouseName' value='$hname' style='width:96%'></td></tr>";

echo "<tr><td>Organisation Area:</td>";
echo"<td><input type='text' name='Area1' id='Area1' value='$place' style='width:96%'></td></tr>";

echo "<tr><td>Post Office:</td>";
echo"<td><input type='text' name='Area2' id='Area2' value='$po' style='width:96%'></td></tr>";

echo" <tr><td>Pin:</td>";
echo"<td><input type='text' name='Pin' id='Pin' value='$pin' style='width:96%'></td></tr>"; 

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
       
        if($cid==1 && $sid==14)  
        {
              if($district==12){$distname='Thiruvananthapuram';}
              if($district==6){$distname='Kollam';} 
              if($district==2){$distname='Eranakulam';} 
              if($district==13){$distname='Thrissur';} 
              if($district==8){$distname='Kozhikode';} 
                    
                    echo '<table width=100%>';
                    echo'<tr><td>' . _('Corporation Name') . ':</td>';
                    echo '<td width=50%><select name="lsgName" readonly id="lsgName" style="width:100%">';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td></tr>';    
                    echo '</table>';      
        }
        
    }elseif($LSGtypeid==2){
            //echo"2222222";
        echo '<table align=left  width=100%>';
        echo'<tr><td>' . _('Municipality Name') . ':</td>';    
        
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
        echo '<table align=left  width=100%>';
        echo'<tr><td>' . _('Block Name') . ':</td>';    
        $sql="SELECT * FROM bio_block WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
         
         echo '<td width=50%><select name="lsgName" id="lsgName" style="width:100%">';
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
        echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:100%">';         
              if($block_name!=0 or $block_name!='')
              {
         $sql="SELECT * FROM bio_panchayat WHERE country=$nationality AND state=$state AND district=$district AND id=$block_name";
         $result=DB_query($sql,$db);
              
         
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
         }  }

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
        if($district!='')
        {
    $sql="SELECT * FROM bio_village WHERE bio_village.country=$nationality AND bio_village.state=$state AND bio_village.district=$district";
        }
    if($taluk!='' OR $taluk!=0){

            $sql.=" AND bio_village.taluk=$taluk";
        
        
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
echo"<table>";

echo "<tr><td>Contact Person</td>";
echo "<td><input type='text' name='contactPerson' id='contactPerson' value='$cperson' style='width:96%'></td></tr>";

echo"<tr><td width=50%>Designation</td>";
echo"<td><input type='text' name='Designation' id='designation' value='$cntdesig' style='width:96%'></td></tr>";

echo"<tr><td width=50%>Mobile number</td>";
echo"<td><input type='text' name='mobile' id='mobile' value='$cph' style='width:96%'></td></tr>";

echo"<tr><td>Phone Number</td>";
echo"<td><input type='text' name='code' id='code' value='$cph' style='width:96%'></td></tr>"; 

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
echo"<table>";

echo"<tr><td width=50%>Lead Date</td>";
echo"<td><input style='width:96%' type='text' name='Leaddate' readonly id='leaddate' value='$leaddate'></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%>Feasibility study conducted by</td>";
echo"<td><input style='width:96%' type='text' name='Team' readonly id='team' value='$fs_team'></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=50%>Conducted date</td>";
echo"<td><input style='width:96%' type='text' name='ConductedDate' id='conducteddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' value='".$fs_coducteddate."'></td></tr>";

$sql="SELECT categoryid,
             categorydescription,
             maincatid 
        FROM stockcategory,
             bio_maincat_subcat
       WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid
         AND bio_maincat_subcat.maincatid =1";
         
$result=DB_query($sql,$db);
echo '<tr><td id="subcat">Plant type</td>';
echo"<td><select name='PlantType' id='planttype' style='width:100%'>";




//      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          
      if($myrow['maincatid']==1){
          if($myrow['categoryid']==$planttype){
               echo "<option selected value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>";
              
          }else{
             echo "<option selected value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }
       
      }else{
          if($myrow['categoryid']==$planttype){
               echo "<option selected value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>";
              
          }else{
      echo "<option value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }
          }
          
      }
      echo '</select></td></tr>';





/*echo"<tr><td width=50%>  End date</td>";
echo"<td><input style='width:96%' type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "'></td></tr>";*/
 

echo"<tr><td width=50%>Budget</td>";
echo"<td><input style='width:96%' type='text' name='Budget' id='budget' value='$budget'></td></tr>";
echo"<tr></tr>";

echo"</table>";
echo"</td>";

echo"</tr>";

echo"</table>";

//============================================
echo"<table>";
echo'<tr><td colspan=2><p><div class="centre">
      <input type=submit name=customersubmit value="' . _('Update') . '" onclick="if(log_in()==1)return false;">';
//echo '<input name="clear" type="submit" value=Clear >
//      <input id="shwprint" type="button" name="shwprint" value="view/hide proposals">
//      <input type="Button" class=button_details_show name=details VALUE="' . _('Details') . '">';

echo'</div>';
echo'</td></tr>';
echo"</table>";

//===============================================







echo"</fieldset>";
echo"</div>";
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
echo '<input type=button name=Wasteanalysis value="' . _('Waste Analysis') . '" onclick="Wasteanalysis2()">'; 

      
echo'</div>';
echo'</td></tr>';



echo"</table>";
echo"</fieldset>";
echo "</div>";



echo"<input type='hidden' name='TeamID' id='teamid' value='$teamid'>";
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
echo"<input type='hidden' name='FSID' id='leadid' value='$fs_entryID'>";
echo'<input type="hidden" name="custid" value='.$cust_id.'>';  
  
?>
