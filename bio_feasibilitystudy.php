<?php
$PageSecurity = 80;
include('includes/session.inc');
$pagetype=3;


$title = _('Feasibility study');  
include('includes/header.inc');
include('includes/sidemenu.php');
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">FEASIBILITY STUDY</font></center>';

    $office=$_SESSION['UserStockLocation'];

if (isset($_POST['submit'])) {
    
    
    
    $InputError=0;
    if(!isset($_POST['LeadID'])){
      prnMsg('Select lead details from the grid and then assign the team','warn');  
        
    }
    else{
        
        $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
        
        $lead_ID=$_POST['LeadID'];
        $team_ID=$_POST['fteam'];
        $FS_charge=$_POST['AdvanceAmount'];
        $FS_startdate=FormatDateForSQL($_POST['StartDate']);
        $FS_enddate=FormatDateForSQL($_POST['EndDate']);
    
        $datearr1 = explode("-",$FS_startdate); 
        $datearr2 = explode("-",$FS_enddate); 
        $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]);
        if($date_diff>10){
            prnMsg('The End date should be with in 10 days from Start date','warn');
        }
        else{
            
            
            
            
            if($_POST['Custname']==""){
                $_POST['Custname']=""; 
            }
            if($_POST['Houseno']==""){
                $_POST['Houseno']="" ;
            }
            if($_POST['Housename']==""){
                $_POST['Housename']=""; 
            }
            if($_POST['Area1']==""){
                $_POST['Area1']="";
            }
            if($_POST['Pin']==""){
                $_POST['Pin']="";
            }
            if($_POST['phone']==""){
                $_POST['phone']="";
            }
            if($_POST['mobile']==""){
                $_POST['mobile']="";
            }
            if($_POST['email']==""){
                $_POST['email']="";
            }
            
            
            
            
            $sql_cust="UPDATE `bio_cust` SET `custname` = '".$_POST['Custname']."',
                                     `houseno` = '".$_POST['Houseno']."',      
                                     `housename` ='".$_POST['Housename']."',
                                     `area1` = '".$_POST['Area1']."',      
                                     `pin` = '".$_POST['Pin']."',      
                                     `custphone` = '".$_POST['phone']."',     
                                     `custmob` = '".$_POST['mobile']."',
                                     `custmail` = '".$_POST['email']."' 
                               WHERE `bio_cust`.`cust_id` ='".$_POST['CustID']."'";          
            $result_cust = DB_query($sql_cust,$db,$ErrMsg,$DbgMsg);
            
            
            $user_ID=$_SESSION['UserID'];
            $approved_by="";
            
            
            $DateString = Date($_SESSION['DefaultDateFormat']);
            $proposal_date=FormatDateForSQL($DateString);
    
            $payment_mode=$_POST['mode'];
            $receipt_no=$_POST['amtno'];
            $payment_bank=$_POST['amtbank'];
            $payment_date=$_POST['amtdate']; 
    
            $reference=$receipt_no.",".$payment_date.",".$payment_bank;
            $payment_date1=FormatDateForSQL($payment_date);
    
            $sqlr="SELECT value
                    FROM bio_changepolicy
                    WHERE policyname ='Institution FS Charge'";    
            $resulte=DB_query($sqlr, $db);     
            $mr=DB_fetch_array($resulte);
            $fixedfs=$mr[0];
         
            if ($FS_charge>=$fixedfs){
                if(isset($_POST['FSID'])){
                    $InputError=1;
                    $sql_fs="UPDATE bio_feasibilitystudy SET teamid=".$team_ID.",
                                                 feasibilitystudy_charge=".$FS_charge.",
                                                 feasibilitystudy_startdate='".$FS_startdate."',
                                                 feasibilitystudy_enddate='".$FS_enddate."',
                                                 created_by='".$user_ID."'
                                           WHERE feasibilitystudy_id=".$_POST['FSID'];
                                            
                    $msg = _('Feasibility Study Proposal is updated');
                    $result_fs = DB_query($sql_fs,$db,_('The update/addition  failed because'));
                    prnMsg($msg,'success');
                    
                    if($_POST['Status']==4){
                        if($payment_mode==1){
                        $sql_payment="UPDATE bio_advance SET
                                            head_id=2, 
                                            mode_id='".$payment_mode."',
                                            date='".$proposal_date."', 
                                            serialnum='',
                                            bankname='',
                                            paydate='".$proposal_date."',
                                            amount='".$FS_charge."',
                                            officid='".$office."',
                                            status=0,
                                            collected_by='".$user_ID."'
                                      WHERE leadid=$lead_ID";
                        }elseif($payment_mode==2){
                            $sql_payment="UPDATE bio_advance SET
                                            head_id=2, 
                                            mode_id='".$payment_mode."',
                                            date='".$proposal_date1."', 
                                            serialnum='".$receipt_no."',
                                            bankname='".$payment_bank."',
                                            paydate='".$proposal_date."',
                                            amount='".$FS_charge."',
                                            officid='".$office."',
                                            status=0,
                                            collected_by='".$user_ID."'
                                      WHERE leadid=$lead_ID"; 
                    }elseif($payment_mode==3){
                    $sql_payment="UPDATE bio_advance SET
                                            head_id=2, 
                                            mode_id='".$payment_mode."',
                                            date='".$proposal_date1."', 
                                            serialnum='".$receipt_no."',
                                            bankname='".$payment_bank."',
                                            paydate='".$proposal_date."',
                                            amount='".$FS_charge."',
                                            officid='".$office."',
                                            status=0,
                                            collected_by='".$user_ID."'
                                      WHERE leadid=$lead_ID";
                
                }
                $result1_payment=DB_query($sql_payment, $db);
                        
                    }
                    elseif($_POST['Status']==2){
                        if($payment_mode==1){
                        $sql_payment="INSERT INTO bio_advance (leadid, 
                                            head_id,
                                            mode_id, 
                                            date, 
                                            serialnum,
                                            bankname,
                                            paydate,
                                            amount,
                                            officid,
                                            status,
                                            collected_by) 
                                    VALUES ('$lead_ID',
                                                2,
                                            '".$payment_mode."',
                                            '".$proposal_date."',
                                            ' ',
                                            ' ',
                                            '".$proposal_date."',
                                            '".$FS_charge."',
                                            '".$office."',
                                            0,
                                            '".$user_ID."')";
                        }elseif($payment_mode==2){
                            $sql_payment="INSERT INTO bio_advance (leadid, 
                                            head_id,
                                            mode_id, 
                                            date, 
                                            serialnum,
                                            bankname,
                                            paydate,
                                            amount,
                                            officid,
                                            status,
                                            collected_by) 
                                    VALUES ('$lead_ID',
                                                2,
                                            '".$payment_mode."',
                                            '".$payment_date1."',
                                            '".$receipt_no."',
                                            '".$payment_bank."',
                                            '".$proposal_date."',
                                            '".$FS_charge."',
                                            '".$office."',
                                            0,
                                            '".$user_ID."')";     
                    }elseif($payment_mode==3){
                    $sql_payment="INSERT INTO bio_advance (leadid, 
                                            head_id,
                                            mode_id, 
                                            date, 
                                            serialnum,
                                            bankname,
                                            paydate,
                                            amount,
                                            officid,
                                            status,
                                            collected_by) 
                                    VALUES ('$lead_ID',
                                                2,
                                            '".$payment_mode."',
                                            '".$payment_date1."',
                                            '".$receipt_no."',
                                            '".$payment_bank."',
                                            '".$proposal_date."',
                                            '".$FS_charge."',
                                            '".$office."',
                                            0,
                                            '".$user_ID."')";
                
                }
                $result1_payment=DB_query($sql_payment, $db);
                
                $_SESSION['lead']=$lead_ID; 
                if($FS_charge>0){
                    print'<script>
                    var answer = confirm("Do you want to PRINT Feasibility Advance Receipt?");
                    if (answer){
                    myRef = window.open("bio_fea_print_A5p.php");
                    }
                    </script> ';
                }
                        
                    }
                   
                    /*
                    $sql_cashbook="UPDATE bio_cashbook SET amount=".$FS_charge.",
                                               mode=".$payment_mode.",
                                               reference='".$reference."'
                                         WHERE leadid=".$lead_ID." AND head_id=2"; 
                            
                    $result_cashbook = DB_query($sql_cashbook,$db);
                    */
                    
                }elseif($InputError==0){
                    $sql_fs="INSERT INTO bio_feasibilitystudy(leadid,
                                            teamid,
                                            feasibilitystudy_charge,
                                            feasibilitystudy_startdate,
                                            feasibilitystudy_enddate,
                                            feasibility_status,
                                            proposal_date,
                                            created_by,
                                            approved_by)
                                  VALUES('".$lead_ID."',
                                         '".$team_ID."',
                                         '".$FS_charge."',
                                         '".$FS_startdate."',
                                         '".$FS_enddate."',
                                         4,
                                         '".$proposal_date."',
                                         '".$user_ID."',
                                         '".$approved_by."')";
                    $msg = _('A Feasibility Study Proposal is given for a team');
                    $result_fs = DB_query($sql_fs,$db,_('The update/addition  failed because'));
                    prnMsg($msg,'success');
                    
                    if($payment_mode==1){
                        $sql_payment="INSERT INTO bio_advance (leadid, 
                                            head_id,
                                            mode_id, 
                                            date, 
                                            serialnum,
                                            bankname,
                                            paydate,
                                            amount,
                                            officid,
                                            status,
                                            collected_by) 
                                    VALUES ('$lead_ID',
                                            2,
                                            '".$payment_mode."',
                                            '".$proposal_date."',
                                            ' ',
                                            ' ',
                                            '".$proposal_date."',
                                            '".$FS_charge."',
                                            '".$office."',
                                            0,
                                            '".$user_ID."')";
                        }elseif($payment_mode==2){
                            $sql_payment="INSERT INTO bio_advance (leadid, 
                                            head_id,
                                            mode_id, 
                                            date, 
                                            serialnum,
                                            bankname,
                                            paydate,
                                            amount,
                                            officid,
                                            status,
                                            collected_by) 
                                    VALUES ('$lead_ID',
                                            2,
                                            '".$payment_mode."',
                                            '".$payment_date1."',
                                            '".$receipt_no."',
                                            '".$payment_bank."',
                                            '".$proposal_date."',
                                            '".$FS_charge."',
                                            '".$office."',
                                            0,
                                            '".$user_ID."')";     
                    }elseif($payment_mode==3){
                    $sql_payment="INSERT INTO bio_advance (leadid, 
                                            head_id,
                                            mode_id, 
                                            date, 
                                            serialnum,
                                            bankname,
                                            paydate,
                                            amount,
                                            officid,
                                            status,
                                            collected_by) 
                                    VALUES ('$lead_ID',
                                            2,
                                            '".$payment_mode."',
                                            '".$payment_date1."',
                                            '".$receipt_no."',
                                            '".$payment_bank."',
                                            '".$proposal_date."',
                                            '".$FS_charge."',
                                            '".$office."',
                                            0,
                                            '".$user_ID."')";
                
                }
                $result1_payment=DB_query($sql_payment, $db);
                $adv=DB_Last_Insert_ID($Conn,'bio_advance','adv_id'); 
         
                $sql_lead="UPDATE bio_leads
                        SET leadstatus=3
                        WHERE leadid='$lead_ID'";
                $result_lead= DB_query($sql_lead,$db);
                /*
                $sql_cashbook="INSERT INTO bio_cashbook (date,
                                        head_id,
                                        leadid,
                                        amount,
                                        mode,
                                        reference)  
                            VALUES ('$proposal_date',
                                     2,
                                    '$lead_ID',
                                    '$FS_charge',
                                    '$payment_mode',
                                    '$reference') "; 
                            
                $result_cashbook = DB_query($sql_cashbook,$db);
                
                $sql_task="SELECT bio_target.taskid,bio_leadtasktarget.task_count
                                FROM bio_target,bio_leadtasktarget
                                WHERE assigneddate <= '$proposal_date'
                                AND duedate >= '$proposal_date'
                                AND officeid =".$_SESSION['officeid']."
                                AND team_id =".$team_ID."
                                AND bio_target.taskid=bio_leadtasktarget.taskid
                                AND bio_leadtasktarget.target=4";    
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
                }
                */
                $_SESSION['lead']=$lead_ID;               
                $_SESSION['adv_id']=$adv;
                
                if($FS_charge>0){
                print'<script>
                var answer = confirm("Do you want to PRINT Feasibility Advance Receipt?");
                if (answer){
                myRef = window.open("bio_print_A5p.php");
                }
                </script> ';
                }
                print'<script>
                var answer = confirm("Do you want to PRINT Feasibility Study report format for collecting data?");
                if (answer){
                myRef = window.open("bio_fs_report.php");
                }
                </script> ';
                
               
         }
      }
      else{
          
          
          $approved_by="";
          $sql_fs="INSERT INTO bio_feasibilitystudy(leadid,
                                            teamid,
                                            feasibilitystudy_charge,
                                            feasibilitystudy_startdate,
                                            feasibilitystudy_enddate,
                                            feasibility_status,
                                            proposal_date,
                                            created_by,
                                            approved_by)
                                  VALUES('".$lead_ID."',
                                         '".$team_ID."',
                                         '".$FS_charge."',
                                         '".$FS_startdate."',
                                         '".$FS_enddate."',
                                         1,
                                         '".$proposal_date."',
                                         '".$user_ID."',
                                         '".$approved_by."')";
          
          
        $msg = _('A Feasibility Study Proposal is given for a team');
        $result_fs = DB_query($sql_fs,$db,_('The update/addition  failed because'));
        $fs_id=DB_Last_Insert_ID($Conn,'bio_feasibilitystudy','feasibilitystudy_id');
        $sql_lead="UPDATE bio_leads
                        SET leadstatus=7
                        WHERE leadid='$lead_ID'";
        $result_lead= DB_query($sql_lead,$db);
        
        
        $_SESSION['FS_ID']=$fs_id;
        prnMsg('The feasibility charge is less than the fixed charge so it is transferred to higher authority for approval','warn');
        print'<script>
          myRef = window.open("bio_fs_approvalby.php","fsapproval","toolbar=yes,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,resizable=no,width=600,height=400");
          </script> ';
        
      }
        
    }
//-------------------------------------------LeadTask-------------------------------------
 
 $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                    taskcompleteddate='".$proposal_date."' 
                              WHERE bio_leadtask.leadid=$lead_ID 
                              AND bio_leadtask.taskid=2 
                              AND taskcompletedstatus!=2
                              AND teamid=$assignedfrm";   
 DB_query($sql_flag,$db);

 
    
/*$sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                         leadid,
                                         teamid,
                                         assigneddate,
                                         duedate,
                                         assigned_by)
                                VALUES(2,
                                       '".$lead_ID."',
                                       '".$team_ID."',
                                       '".$FS_startdate."',
                                       '".$FS_enddate."',
                                       '".$_SESSION['UserID']."'
                                       )";
$result_leadTask=DB_query($sql_leadTask,$db); */

//---------------------------------------------------------------------------------------------
            
}
    
    unset($_POST['fteam']);
    unset($_POST['StartDate']);
    unset($_POST['EndDate']);
    unset($_POST['AdvanceAmount']);
    //unset($_POST['enquiry']);
//    unset($_POST['enquiry']);
//    unset($_POST['enquiry']);
//    unset($_POST['enquiry']);



}



echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style="width:70%"><tr><td>';
echo"<div id=panel>";
echo '<table><tr>';

//========================================== Left Panel Begins

echo'<td>';

echo"<div id=cus_details>";
echo"<fieldset style='width:380px;height:215px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";
echo"<table width=100%>";


if($_GET['lead']!=''){
    $leadid=$_GET['lead'];    
    $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality  
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);

 $myrow=DB_fetch_array($result); 

 $no=0; 
 $k=0; 
 
 $cname=$myrow[1];  
 if($myrow[2]!='-'){
     $cph=$myrow[2]; 
 }else{
     $cph=$myrow[3]; 
 }
  
 $place=$myrow[4];
 $dist=$myrow[5];
 $ste=$myrow[6];
 $ctry=$myrow[7]; 
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];
}

echo"<tr><td width=50%>Customer Name</td>";
echo"<td><input type='text' name='Custname' id='custname' value='$cname'></td></tr>";
echo"<tr></tr>";


echo"<tr><td width=50%>House no</td>";
echo"<td><input type='text' name='Houseno' id='houseno' value='$hno'></td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>House name</td>";
echo"<td><input type='text' name='Housename' id='housename' value='$hname'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place</td>";
echo"<td><input type='text' name='Area1' id='custplace' value='$place'></td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>Pincode</td>";
echo"<td><input type='text' name='Pin' id='housename' value='$pin'></td></tr>";
echo"<tr></tr>";
 echo"<tr><td width=50%>Cust Mobile no</td>";
echo"<td><input type='text' name='mobile' id='custmob' value='$mob'></td></tr>";
echo"<tr></tr>";

 echo"<tr><td width=50%>Email</td>";
echo"<td><input type='text' name='email' id='email' value='$email'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone</td>";
echo"<td><input type='text' name='phone' id='custph' value='$cph'></td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";

echo'</div>';
echo"</td>";
//========================================== Left Panel Ends 

//========================================== Right Panel Begins
echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:380px;height:215px; overflow:auto;'>";
echo"<legend>Assign Team</legend>";
echo"<table>";
echo"<tr><td width=50%>Feasibility Team*</td>";
echo"<td><select name='fteam' id='fteam' style='width:150px'>";
$sql="SELECT bio_leadteams.teamid,bio_leadteams.teamname FROM bio_leadteams";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['teamid']==$_POST['fteam'])  
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
$DateString = Date($_SESSION['DefaultDateFormat']); 

echo"<tr><td>Start date*</td>";
echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px' value='".$DateString."'></td></tr>";

echo"<tr><td>End date*</td>";
echo"<td><input type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";
 
echo"<tr><td>Advance Amount*</td>";
echo"<td><input type='text' name='AdvanceAmount' id='advanceamount' style='width:150px'></td></tr>";

echo'<tr>';
echo'<td width=50%>Mode of payment:</td>';
echo'<td><select name="mode" id="paymentmode" style="width:150px" onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$_POST['modes'])
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
    echo $myrow1['id'] . '">'.$myrow1['modes'];  
    echo '</option>';
  }
echo'</select></td></tr>';
echo"</table>";

echo"<table id='modeamt'>";
echo"</table>"; 
echo"<table id='amt'>";
echo"</table>"; 

echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>";
echo"</div>";


//========================================== Buttons 
echo"<table>";
echo'<tr><td colspan=2><p><div class="centre">
<input type=submit name=submit value="' . _('Assign') . '" onclick="if(log_in()==1)return false;">';
echo '<input name="clear" type="submit" value=Clear ><input id="shwprint" type="button" name="shwprint" value="view/hide proposals"><input type="Button" class=button_details_show name=details VALUE="' . _('Details') . '">';

echo'</div>';
echo'</td></tr>';

echo'</div>'; 
echo"</td></tr></table>";
echo'</form>';

//========================================== Buttons Ends

echo"<div id='selectiondetails'>";

echo"<fieldset style='width:780px; overflow:auto;'>";
echo"<legend>All Links</legend>";
echo '<table width="100%">
    <tr>
        <th width="50%">' . _('Masters') . '</th>
        <th width="50%">' . _('Reports') . '</th>
   
    </tr>';
echo"<tr><td  VALIGN=TOP >";
echo '<a href="bio_activefeasibilitystudyproposals.php" style=cursor:pointer; >' . _('Active FS') . '</a><br>';
echo '<a href="bio_passivefeasibilitystudyproposals.php" style=cursor:pointer; >' . _('Passive FS') . '</a><br>';
echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cutomer Ledger') . '</a><br>';
echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cash Book') . '</a><br>';


echo"</td><td  VALIGN=TOP >";
echo '<a href="bio_dfeaprint_A5p.php" style=cursor:pointer; onclick=()>' . _('Print Feasibility Reciept') . '</a><br>';
echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Print Covering Letter') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=addNewSeasonName()>' . _('Add New Season Name') . '</a><br>';
echo"</td></tr>";
echo'</table>';
echo"</fieldset>";

echo "</div>";







//========================================== Grid for pending feasibilities
echo'<div id="leadgrid">';
echo"<fieldset style='width:760px'><legend>Lead Details</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>"; 
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname1' id='byname1'></td>";
echo"<td><input type='text' name='byplace1' id='byplace1'></td>";
echo '<td><select name="off1" id="off1" style="width:100px">';
echo '<option value=0></option>'; 
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

echo"<tr><th>Slno</th><th>Name</th><th>Lead ID</th><th>Date</th><th>Output</th><th>Cust type</th><th>Team</th></tr>";
$office=$_SESSION['UserStockLocation'];   

 $empid=$_SESSION['empid'];
 $employee_arr=array();
 
  
     $sql_drop="DROP TABLE IF EXISTS `emptable`";
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
     
     $sql5="SELECT userid FROM www_users WHERE empid IN ($employee_arr)";
     $result5=DB_query($sql5,$db);
     while($row5=DB_fetch_array($result5))
     {
    
    $userid[]="'".$row5['userid']."'";     
    
     }
     $user_array=join(",", $userid);
   
//   $team=array();
//   $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
//    $result6=DB_query($sql6,$db);
//    while($row6=DB_fetch_array($result6)){
//        $team[]=$row6['teamid'];
//    }
//         
//    $team_array=join(",", $team);

  $sql="SELECT bio_cust.custname AS custname,               
  bio_cust.area1 AS place,
  bio_enquirytypes.enquirytype AS enqtype,
  bio_outputtypes.outputtype AS outputtype,
  bio_leads.leadid AS leadid, 
  bio_leads.leaddate AS leaddate,
  bio_leadteams.teamname AS teamname,
  bio_leadsources.sourcename AS sourcename,
  bio_office.id AS officeid,
  bio_office.office AS office
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,
bio_enquirytypes,
bio_office,
bio_outputtypes   
WHERE bio_cust.cust_id=bio_leads.cust_id 
AND bio_leadteams.teamid=bio_leads.teamid  
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.enqtypeid=2
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
AND bio_leadsources.id=bio_leads.sourceid  
AND bio_office.id=bio_leadsources.officeid
AND bio_leads.leadstatus=0
AND bio_leads.created_by IN ($user_array)
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
$sql.=" ORDER BY leadid DESC";      
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
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td></tr>",
            $no,
            $myrow['custname'],
            $leadid,
            ConvertSQLDate($myrow['leaddate']),
            $myrow['outputtype'],
            $myrow['enqtype'],
            $myrow['teamname']);
           
}
echo"</td>";
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";
echo"</div>";
//========================================== Grid for Proposal Details

echo'<div id="proposalgrid">';
echo"<fieldset style='width:760px'><legend>Proposal Details</legend>"; 
   
 
echo"<table style='width:100%' border=0> ";

echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>"; 
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df2" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df2" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt2" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt2" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname2' id='byname2'></td>";
echo"<td><input type='text' name='byplace2' id='byplace2'></td>";
echo '<td><select name="off2" id="off2" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_office";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo '</select></td>';
echo '<td><select name="leadsrc2" id="leadsrc2" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';
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
echo"<tr><th>Slno</th><th>Name</th><th>Leadid</th><th>FS Charge</th><th>Start Date</th><th>End Date</th><th>Team</th><th>Status</th></tr>";



$sql8="SELECT bio_cust.custname AS custname,               
  bio_cust.area1 AS place,
  bio_leads.leadid AS leadid, 
  bio_leadteams.teamname AS teamname,
  bio_office.id AS officeid,
  bio_feasibilitystudy.teamid AS teamid,
  bio_feasibilitystudy.feasibilitystudy_charge AS feastudycharge,
  bio_feasibilitystudy.feasibilitystudy_startdate AS feastartdate,
  bio_feasibilitystudy.feasibilitystudy_enddate AS feaenddate,
  bio_feasibilitystudy.feasibility_status,
  bio_proposal_status.status  
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,
bio_office,
bio_feasibilitystudy,
bio_proposal_status   
WHERE bio_cust.cust_id=bio_leads.cust_id 
AND bio_leadteams.teamid=bio_feasibilitystudy.teamid
AND bio_leadsources.id=bio_leads.sourceid  
AND bio_office.id=bio_leadsources.officeid
AND bio_feasibilitystudy.leadid=bio_leads.leadid
AND bio_proposal_status.statusid=bio_feasibilitystudy.feasibility_status 
AND bio_leads.created_by IN ($user_array) 
";

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
    $fsid=$myrow8['feasibilitystudy_id'];
    $leadid=$myrow8['leadid'];    
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    }else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
    $no++;
    
    echo"<td cellpading=2>".$no."</td>
         <td>".$myrow8['custname']."</td>
         <td>".$myrow8['leadid']."</td>
         <td>".$myrow8['feastudycharge']."</td>
         <td>".ConvertSQLDate($myrow8['feastartdate'])."</td>
         <td>".ConvertSQLDate($myrow8['feaenddate'])."</td>
         <td>".$myrow8['teamname']."</td>
         <td>".$myrow8['status']."</td>";
    if($myrow8['feasibility_status']==1){     
        echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=FsEdit('$fsid','$leadid')>" . _('Edit') . "</a></td></tr>";
   }
   elseif($myrow8['feasibility_status']==2){
       echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=FsEdit('$fsid','$leadid')>" . _('Select') . "</a></td></tr>";
   }
   elseif($myrow8['feasibility_status']==3){
       echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=FsEdit('$fsid','$leadid')>" . _('Edit') . "</a></td></tr>";
   }
   elseif($myrow8['feasibility_status']==4){
       echo"<td><a  style='cursor:pointer;'  onclick=FsPrint('$fsid','$leadid')>" . _('Print') . "</a></td>
            <td><a  style='cursor:pointer;'  onclick=FsEdit('$fsid','$leadid')>" . _('Edit') . "</a></td></tr>";
   }
   
            
//    printf("<td cellpading=2>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td></tr>",
//            $no,
//            $myrow['custname'],
//            ConvertSQLDate($myrow['leaddate']),
//            $myrow[3],
//            $myrow[2],
//            $myrow[4],
//            $myrow[4],
//            $myrow[0]);
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
    
     $("#selectiondetails").hide(); 
        
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
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('custname','Please select a lead');  if(f==1){return f; }  }

if(f==0){var x=document.getElementById('custmob').value;  
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric value for Mobile number"); document.getElementById('custmob').focus();
              if(x=""){f=0;}
              return f; 
           }
}

//if(f==0){f=space_check('custmob','Space is not allowed in Mobile number');  if(f==1){return f; }  }
 
/*
if(f==0){var x=document.getElementById('custph').value;  
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric value for Phone number"); document.getElementById('custph').focus();
              if(x=""){f=0;}
              return f; 
           }
}
*/
if(f==0){f=common_error('fteam','Please select a team');  if(f==1){return f; }  } 
if(f==0){f=common_error('startdate','Please enter start date');  if(f==1){return f; }  }  
if(f==0){f=common_error('enddate','Please enter end date');  if(f==1){return f; }  } 
if(f==0){f=common_error('advanceamount','Please enter Advance amount');  if(f==1){return f; }  }
if(f==0){var x=document.getElementById('advanceamount').value;  
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric value for Advance amount"); document.getElementById('advanceamount').focus();
              if(x=""){f=0;}
              return f; 
           }
} 
if(f==0){f=common_error('paymentmode','Please select Payment Mode');  if(f==1){return f; }  }
if(f==0){f=common_error('amtdate','Please enter Payment Date');  if(f==1){return f; }  }
if(f==0){f=common_error('amtno','Please enter Payment Number');  if(f==1){return f; }  }
if(f==0){f=common_error('amtbank','Please enter Payment Bank');  if(f==1){return f; }  }
}
function passid(str1,str2){
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
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_customerdetails.php?p=" + str1,true);
xmlhttp.send(); 
}



function advdetail(str){
if (str=="")
  {
  document.getElementById("amt").innerHTML="";
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
    document.getElementById("amt").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_fsamountdetails.php?adv=" + str,true);
xmlhttp.send();    
}

function FsEdit(str1,str2){ 
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
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_editFSdetails.php?p=" + str1 + "&q=" + str2,true);
xmlhttp.send(); 
}

function FsPrint(str1,str2)

{
window.location="bio_fea_print_A5p.php?leadid=" + str2;
}


</script>
