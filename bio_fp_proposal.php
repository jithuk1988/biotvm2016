<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('feasibility proposal Details');  
$pagetype=1;
include('includes/header.inc');
include('bio_generateleadtask.php');
include('includes/removespecialcharacters.php'); 
 
  $userid=$_SESSION['UserID'];    
  $DateString1 = Date($_SESSION['DefaultDateFormat']);
  $DateString=FormatDateForSQL($DateString1); 
   
echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">Feasibility Proposal</font></center>';                                                      
 if(isset($_POST['submit'])) {
        
        $leadid=$_POST['LeadID'];
        
        
        $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
     
        $InputError=0;                       
       // $startdate=FormatDateForSQL($_POST['ExpectedDate']);
        
        
        $Expected_date=FormatDateForSQL($_POST['ExpectedDate']);   
        //$subtask_date=FormatDateForSQL($_POST['SubtaskDate']);                  
        $datearr1 = explode("-",$Expected_date); 
        //$datearr2 = explode("-",$subtask_date); 
        $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]);
        if($date_diff>30){
            $InputError=1;
            prnMsg('The End date should be with in 30 days from Expected date','warn');
        }
                   
           
$sql_sou="SELECT bio_cust.custname, 
                 bio_district.district,
                 bio_leads.sourceid,
                 bio_leads.leadid, 
                 bio_cust.nationality,
                 bio_cust.state 
            FROM 
                 bio_cust
       LEFT JOIN 
                 bio_leads ON bio_cust.cust_id=bio_leads.cust_id 
       LEFT JOIN 
                 bio_district ON bio_district.did = bio_cust.district
             AND bio_cust.nationality = bio_district.cid
             AND bio_cust.state = bio_district.stateid
           WHERE
                 bio_leads.leadid=".$leadid;  
           


  $result_sou=DB_query($sql_sou,$db);    
  $myrow_sou=DB_fetch_array($result_sou);
  
          

  $sourceid=$myrow_sou['sourceid'];
  $sql_src_count="SELECT COUNT(*) FROM bio_leadsources
                    WHERE sourcetypeid=13 
                      AND id=$sourceid";
  $result_src_count=DB_query($sql_src_count,$db);
  $myrow_src_count=DB_fetch_array($result_src_count);
  $leadsource_count=$myrow_src_count[0];
           /*
  if($leadsource_count>0){
      if($myrow_sou['nationality']==1 AND $myrow_sou['state']==14){
          $custtype=4;
      }elseif($myrow_sou['nationality']==1 AND $myrow_sou['state']!=14){
          $custtype=5;
      }elseif($myrow_sou['nationality']!=1){
          $custtype=6;
      }
      
  }else{
       if($myrow_sou['nationality']==1 AND $myrow_sou['state']==14){
          $custtype=1;
      }elseif($myrow_sou['nationality']==1 AND $myrow_sou['state']!=14){
          $custtype=2;
      }elseif($myrow_sou['nationality']!=1){
          $custtype=3;
      }
      
      
  }      */
   if($myrow_sou['nationality']==1 AND $myrow_sou['state']==14){
          $custtype=1;
      }elseif($myrow_sou['nationality']==1 AND $myrow_sou['state']!=14){
          $custtype=2;
      }elseif($myrow_sou['nationality']!=1){
          $custtype=3;
      }
 
 $sql1="SELECT bio_changepolicy.value
          FROM bio_changepolicy       
         WHERE bio_changepolicy.policyname='Institution FS Charge' 
           AND bio_changepolicy.enquirytype=".$custtype;
 
  $result1=DB_query($sql1,$db);    
  $myrow2=DB_fetch_array($result1); 
 
 $fs_amount=$myrow2['value'];
                                 
                    
 // $fs_amount=$mr[0];   
  $amount=$_POST['Amount'];
  $leadid=$_POST['LeadID'];
  $teamid=$_POST['Team'];
  $approvalby="businesshead";
  if($userid=="businesshead"){
    $approvalby="admin";  
  } 
   if($amount>=$fs_amount)
   {
   $sql6= "INSERT INTO bio_fsproposal(leadid,
                                      fp_date,
                                      fp_amount,
                                      fp_createdby,
                                      fp_approvalby,
                                      fp_expapproval,
                                      teamid,
                                      fp_approvalstatus,status )
                          VALUES ('".$leadid."',
                                 '".$DateString."',
                                 '".$amount."',        
                                 '".$userid."',
                                 '".$approvalby."',
                                 '".$Expected_date."',
                                 '".$teamid."',
                                 4,2)";                                           
      $result = DB_query($sql6,$db);
      ////////////////////////////
      
      
   $sql_fsid= "SELECT LAST_INSERT_ID()" ; 
   $result5=DB_query($sql_fsid,$db); 
   $checkresult5=DB_fetch_array($result5);
   $fsid=$checkresult5[0];      
      
      
      
      
      
      
      
      
      
      
      
      /////////////////////////////
      $sql7="UPDATE bio_leads SET leadstatus=26 where leadid=".$leadid;
      $result7=DB_query($sql7,$db);
      $taskid=15;
      
            
//      generatetask($leadid,$taskid,$teamid,$db);
      $msg = _('Feasibility Proposal is created succesfully');      
       prnMsg($msg,'success');
       
      $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                          taskcompleteddate='".$DateString."' 
                   WHERE bio_leadtask.leadid=$leadid 
                     AND bio_leadtask.taskid=15 
                     AND taskcompletedstatus!=2";
//                     AND teamid=$assignedfrm   
    DB_query($sql_flag,$db); 
       
          
   }   
    else{
    $sql6= "INSERT INTO bio_fsproposal(leadid,
                                      fp_date,
                                      fp_amount,
                                      fp_createdby,
                                      fp_approvalby,
                                      fp_expapproval,
                                      teamid,
                                      fp_approvalstatus,status )
                          VALUES ('".$leadid."',
                                  '".$DateString."',
                                  '".$amount."',        
                                  '".$userid."',
                                  '".$approvalby."',
                                  '".$Expected_date."', 
                                  '".$teamid."',
                                 1,1)";                                           
     $result = DB_query($sql6,$db);
     $proposal_no=DB_Last_Insert_ID($Conn,'bio_fsproposal','fs_propono'); 
     $task_ID=16;
     $duedate="0000-00-00";
     $date1="0000-00-00";
     $status=0; 
 $sql_approval="INSERT INTO bio_approval(taskid,
                                   leadid,
                                   submitted_user,
                                   approval_user,
                                   assigneddate,
                                   duedate,
                                   taskcompleteddate,
                                   taskcompletedstatus,
                                   proposal_no) 
                            VALUES ('".$task_ID."',
                                    '".$leadid."',
                                    '".$userid."',
                                    '".$approvalby."',
                                    '".$DateString."',
                                    '".$duedate."',
                                    '".$date1."',
                                    '".$status."',
                                    '".$proposal_no."')";
     $result_approval=DB_query($sql_approval,$db);       
 $sql7="UPDATE bio_leads SET leadstatus=47 where leadid=".$leadid;
     $result7=DB_query($sql7,$db);

$sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                   taskcompleteddate='".$DateString."' 
                             WHERE bio_leadtask.leadid=$leadid 
                               AND bio_leadtask.taskid=15 
                               AND taskcompletedstatus!=2
                               AND teamid=$assignedfrm";   
    DB_query($sql_flag,$db);
     
    // $msg1= _('Feasibility Proposal is created succesfully');      
//       prnMsg($msg1,'success');
     $msg = _('Feasibility Proposal is given for Approval');
     prnMsg($msg,'Warn');      
    } 
    
    
    
    ?>
      <script>
      
      var lead=<?php echo $leadid; ?>;
      //var fsid=<?php echo $fsid; ?>; 
     // window.location='bio_selectfsproposalprint.php?leadid='+lead+'&fs_propono='+$fsid;
     window.opener.location='bio_instTaskview.php?lead='+ lead;
   //   window.close();

      </script>
      <?php
    
          
  }            
            
 echo"<div id=fullbody>";
 echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
 echo '<table style="width:70%"><tr><td>';
 echo"<div id=panel>";
 echo '<table><tr>';
 echo'<td>';
 echo"<div id=cus_details>";
 echo"<fieldset style='width:300px;height:150px'>"; 
 echo"<legend>Customer Details</legend>";
 echo"</legend>";
 echo"<table width=100%>";
 
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
 
//------------------------------------------------------------------------------------------------------------------------//
 if($_GET['lead']!=''){
    $lead_ID=$_GET['lead'];
    $TID=$_GET['tid']; 
  
  $sql="SELECT bio_cust.custname, 
              bio_district.district,
              bio_cust.contactperson, 
              bio_cust.custmob, 
              bio_leads.remarks, 
              bio_leads.sourceid,
              bio_leadtask.leadid, 
              bio_leadtask.duedate, 
              bio_leadtask.assigneddate, 
              bio_leadtask.teamid,
              bio_cust.nationality,
              bio_cust.state 
         FROM 
              bio_cust
    LEFT JOIN 
              bio_leads ON bio_cust.cust_id=bio_leads.cust_id 
    LEFT JOIN 
              bio_leadtask ON bio_leadtask.leadid=bio_leads.leadid 
    LEFT JOIN 
              bio_district ON bio_district.did = bio_cust.district
          AND bio_cust.nationality = bio_district.cid
          AND bio_cust.state = bio_district.stateid
        WHERE
              bio_leadtask.leadid=".$lead_ID."  
          AND bio_leadtask.tid=".$TID; 
  
  $result=DB_query($sql,$db);    
  $myrow=DB_fetch_array($result);  
   
  $leadid=$myrow['leadid'];  
  $cname=$myrow['custname'];  
  $orgname=$myrow['contactperson'];
  $number=$myrow['custmob'];  
  $date=ConvertSQLDate($myrow['duedate']); 
  $startdate=ConvertSQLDate($myrow['assigneddate']);
  $district=$myrow['district'];
  $remark=$myrow['remarks'];
 /* 
  $sourceid=$myrow['sourceid'];
  $sql_src_count_p="SELECT COUNT(*) FROM bio_leadsources
                    WHERE sourcetypeid=13 
                      AND id=$sourceid";
  $result_src_count_p=DB_query($sql_src_count_p,$db);
  $myrow_src_count_p=DB_fetch_array($result_src_count_p);
  $leadsource_count=$myrow_src_count_p[0];*/
  
 /* if($leadsource_count>0){
      if($myrow['nationality']==1 AND $myrow['state']==14){
          $custtype=4;
      }elseif($myrow['nationality']==1 AND $myrow['state']!=14){
          $custtype=5;
      }elseif($myrow['nationality']!=1){
          $custtype=6;
      }
      
  }else{
       if($myrow['nationality']==1 AND $myrow['state']==14){
          $custtype=1;
      }elseif($myrow['nationality']==1 AND $myrow['state']!=14){
          $custtype=2;
      }elseif($myrow['nationality']!=1){
          $custtype=3;
      }
      
      
  }*/

 if($myrow['nationality']==1 AND $myrow['state']==14){
          $custtype=1;
      }elseif($myrow['nationality']==1 AND $myrow['state']!=14){
          $custtype=2;
      }elseif($myrow['nationality']!=1){
          $custtype=3;
      }
 
 $sql_1="SELECT bio_changepolicy.value
          FROM bio_changepolicy       
         WHERE bio_changepolicy.policyname='Institution FS Charge' 
           AND bio_changepolicy.enquirytype=".$custtype;
 
  $result_1=DB_query($sql_1,$db);    
  $myrow_1=DB_fetch_array($result_1); 
 
 $amount=$myrow_1['value'];  
  
  
     
    
} 
//------------------------------------------------------------------------------------------------------------------------//  

 
 echo"<tr><td width=50%>Customer name:</td>";
 echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
 echo"<tr></tr>";
 echo"<tr><td width=50%>Contact Person:</td>";
 echo"<td><input type='hidden' name='Housename' id='housename' value='$orgname'>$orgname</td></tr>";

 echo"<tr><td width=50%>Contact number:</td>";
 echo"<td><input type='hidden' name='number' id='number' value='$number'>$number</td></tr>";
 echo"<tr></tr>";
 echo"<tr><td width=50%>District:</td>";
 echo"<td><input type='hidden' name='district' id='district' value='$district'>$district</td></tr>";
 echo"<tr></tr>";
 echo"<tr><td width=50%>Remarks</td>";
 echo"<td><input type='hidden' name='remark' id='remark' value='$remark'>$remark</td></tr>"; 

 echo"</table>";
 echo"</fieldset>";
 echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
  
 echo'</div>';
 echo"</td>";
//-----------------------------------------------------------------------------------------------------------------//

 echo'<td valign=top>';    
 echo'<div id=right_panel_1>';
 echo"<fieldset style='width:300px;height:150px; overflow:auto;'>";
 echo"<legend>Feasibility Proposal</legend>";
 echo"<table>";


 $DateString = Date($_SESSION['DefaultDateFormat']); 
 
 echo"<tr><td>Start date :</td>";
 echo"<td><input type='hidden' name='StartDate' id='startdate'  style='width:135px' value='".$startdate."'>$startdate</td></tr>";

 echo"<tr></tr>";
 echo"<tr><td>Due Date :</td>";
 echo"<td><input type='hidden' name='Date' id='date1' value='".$date."'>".$date."</td></tr>";

 echo"<tr><td>Expected Date For Approval* :</td>";
 echo"<td><input type='text' name='ExpectedDate'  id='date' placeholder='DD/MM/YYYY' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:135px'></td></tr>";
 echo"<tr></tr>";
 
 echo"<tr><td>FS Charge :</td>";       
 echo"<td><input type='text' name='Amount' readonly id='amount' value='$amount'></td></tr>";

 echo"<input type='hidden' name='Team' id='team' value='".$myrow['teamid']."'>";
 echo"<table id='modeamt'>";
 echo"</table>"; 
 echo"<table id='amt'>";
 echo"</table>"; 
 echo"</fieldset>";
 echo"</div>";
 echo"</td></tr></table>";
 echo"</div>";
 //-------------------------------------------------------------------------------------------------------------------------------------------//
 echo"<table>";
 echo'<tr><td colspan=2><p><div class="centre">
      <input type=submit id=submit name=submit value="' . _('Submit') . '" onclick="if(log_in()==1)return false;">';
 echo '<input name="clear" id="clear" type="submit" value=Clear ><input id="shwprint" type="button" name="shwprint" value="view/hide proposals">';
 echo'</div>';
 echo'</td></tr>';
 echo'</div>'; 
 echo"</td></tr></table>";
 echo'</form>';
                 if(!isset($_GET['tid']))
                 {

 echo "<div id=leadgrid'>";
 echo"<fieldset style='width:900px'><legend>Lead Details</legend>";
 echo "<div style='height:200px; overflow:scroll;'>"; 
 echo "<table style='border:1px solid #F0F0F0;width:100%'>";             
 echo '<tr><td>Action Date</td>
             <td>Name</td>
             <td>District</td>';  
   echo '<tr><td><select name="Actiondate" id="actiondate" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="1">Today</option>';
   //echo '<option value="2">Tommorrow</option>';
   echo '<option value="2">Yesterday</option>';
   echo '<option value="3">ALL</option>';
   echo '<option selected='.$_POST['Actiondate'].'>'.$actiondatedesc.'</option>'; 
   echo '</select></td>';           
   echo '<td><input type="text" name="byname" id="byname" style="width:120px"></td>'; 
   echo '<td><input type="text" name="byplace" id="byplace" style="width:120px"></td>';   
   
     echo '<td><input type="submit" name="filterbut" id="filterbut" value="search"></td></tr>';
     echo '</table>';
 
           
 echo "<table style='border:0px solid #F0F0F0;width:100%'>";    
  echo'<th class="viewheader">SL No</th>';  
 echo'<th class="viewheader">Institution Name/<br>Customer Name</th>';  
 
 echo'<th class="viewheader">Contact Number</th>';
 echo'<th class="viewheader">District</th>';
   
 echo'<th class="viewheader">Due Date</th>';
 echo'<th class="viewheader">Status</th>'; 
 echo'<th class="viewheader">Assigned To</th>';
 echo'<th class="viewheader">Assigned From</th>';
 echo'<th class="viewheader"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>';    
 echo'</tr>';
 echo '<form name="leadsfrom"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';      

   /*
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
  
  */
  
 $sql="SELECT bio_cust.custname AS custname,               
               bio_cust.contactperson AS orgname,
               bio_leads.leadid AS leadid, 
               bio_leads.leaddate AS leaddate,
               bio_leadteams.teamname AS teamname,
               bio_leadsources.sourcename AS sourcename,
               bio_office.id AS officeid,
               bio_enquirytypes.enqtypeid AS enqtypeid,
               bio_enquirytypes.enquirytype AS enqtype,
               bio_office.office AS office,
               bio_cust.custmob AS custmob,   
               bio_leadtask.assigned_from,
               bio_leadtask.duedate,
               bio_district.district AS district, 
               bio_leadtask.teamid,
               bio_office.id AS officeid,
               bio_office.office AS office,
               bio_status.biostatus
          FROM 
               bio_leads,
               bio_leadteams,
               bio_leadsources,
               bio_enquirytypes,
               bio_office,
               bio_outputtypes,
               bio_status,
               bio_leadtask,
               bio_cust
     LEFT JOIN 
               bio_district ON bio_cust.district=bio_district.did 
           AND bio_cust.nationality=bio_district.cid
           AND bio_cust.state=bio_district.stateid 
         WHERE bio_cust.cust_id=bio_leads.cust_id  
           AND bio_leads.enqtypeid=2
           AND bio_leadsources.id=bio_leads.sourceid  
           AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
           AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
           AND bio_leadsources.id=bio_leads.sourceid  
           AND bio_office.id=bio_leadsources.officeid
           AND bio_status.statusid=bio_leads.leadstatus
           AND bio_leadtask.taskid=15 
           AND bio_leadtask.leadid=bio_leads.leadid
           AND bio_leadteams.teamid=bio_leadtask.teamid
           AND bio_leadtask.teamid IN ($team_array)
           AND bio_leadtask.viewstatus=1
           AND bio_leadtask.taskcompletedstatus=0";  
                  // echo $team_array;              
$Currentdate=FormatDateForSQL(Date("d/m/Y"));   
if(isset($_POST['filterbut']))

  {
 
    if (isset($_POST['Actiondate']))  {        
    if ($_POST['Actiondate']!='')   {       
    if ($_POST['Actiondate']==1) {     
      
    $sql.=" AND bio_leadtask.duedate ='".$Currentdate."'";           
     }
      
    if ($_POST['Actiondate']==2) {
        
  $date=explode("-",$Currentdate);

  $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
  $Yesterday1 = strtotime($startdate2 . " -1 day");
  $Yesterday=date("d/m/Y",$Yesterday1);

  $Yesterday2=FormatDateForSQL($Yesterday);    
               
    $sql.=" AND bio_leadtask.duedate='".$Yesterday2."'";   
        
    }   
    } 
    }   
      
   if ((isset($_POST['datef'])) && (isset($_POST['datet'])))   {
    if (($_POST['datef']!="") && ($_POST['datet']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['datef']);   
    $sourcetypeto=FormatDateForSQL($_POST['datet']);
    $sql.=" AND bio_leadtask.duedate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off'];
  //  echo $officeid;
//      if (isset($_POST['byname1']))  {        
//        if ($_POST['byname1']!='')   
//             $name2=removeCharacters($_POST['byname1']);      
//    $sql.= " AND (bio_cust.custname LIKE '%".$_POST['byname1']."%'
//             OR bio_cust.custname SOUNDS LIKE '%".$_POST['byname1']."%'
//             OR bio_cust.custname LIKE '%".$name2."%')";   


//    } 
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='') 
        $name2=removeCharacters($_POST['byname']);   
    $sql.= " AND (bio_cust.custname LIKE '%".$_POST['byname']."%' 
             OR bio_cust.custname SOUNDS LIKE '%".$_POST['byname']."%'
             OR bio_cust.custname LIKE '%".$name2."%')";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql.= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }
        
  }
  else 
  {
     $sql.=" AND bio_leads.leaddate >= DATE_ADD(CURDATE(), INTERVAL -30 DAY)";
  }
     $sql.= " GROUP BY bio_leadtask.tid desc";
//echo $sql;
$result=DB_query($sql,$db);
   echo '<tbody>';
   echo '<tr>';                                         
      $no=0;
      $k=0;
                  $slno=1;
      while($myrow=DB_fetch_array($result))
      {  
     $lead_ID=$myrow['leadid'];  
      $date=$myrow['duedate']; 
       $tid=$myrow['tid'];
       $teamid= $myrow['teamid'];
       $assigned_from=$myrow['assigned_from'];
      // echo$fs_amount=$myrow['value'];
// echo"<td><input type='text' name='Amount' id='amount' value='$amount'></td></tr>"; 
  $sql2="SELECT bio_leadteams.teamname as ast
                FROM  bio_leadtask,bio_leadteams
          WHERE bio_leadteams.teamid= ".$assigned_from;
          
          $result2=DB_query($sql2,$db);
          $myrow4=DB_fetch_array($result2);        
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;                   
                }
                 else 
                {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                }
                    $no++;
                  echo'<td align=center>'.$slno.'</td>';  

         echo'<td align=center>'.$myrow['custname'].'<br>'."-".$myrow['orgname'].'</td>';
        // echo'<td align=center>'.$myrow['orgname'].'</td>';
         echo'<td align=center>'.$myrow['custmob'].'</td>';  
         echo'<td align=center>'.$myrow['district'].'</td>';      
         echo'<td>'.ConvertSQLDate($myrow['duedate']).'</td>';
         echo'<td>'.$myrow['biostatus'].'</td>';           
         echo'<td align=center>'.$myrow['teamname'].'</td>';
        echo'<td align=center>'.$myrow4['ast'].'</td>';     
         echo "<td><a style=cursor:pointer; id=".ConvertSQLDate($myrow['duedate'])." onclick=viewDetails('$lead_ID','$teamid')>Select</a></td>";      
         $slno++;
      }             
                     
             
echo '<tbody>';
echo"</tr></tbody>
</table>";
echo"</div>";
                
echo"</fieldset>";  
echo'</div>';
         }  
//------------------------------------------------------------------------------------------------------------------------//
echo'<div id="proposalgrid">';
echo"<fieldset style='width:900px'><legend>Proposal Details</legend>"; 
    echo "<div style='height:200px; overflow:scroll;'>"; 
 
 echo"<table style='width:100%' border=0> ";
  echo'<th class="viewheader">Sl No</th>';
 echo'<th class="viewheader">Institution Name/<br>Customer Name</th>'; 
 echo'<th class="viewheader">Contact Number</th>'; 
 echo'<th class="viewheader">District</th>';    
 echo'<th class="viewheader">FR Date</th>';
  echo'<th class="viewheader">FR Amount</th>';

 echo'<th class="viewheader">Team</th>';
 echo'<th class="viewheader">Status</th>'; 
 echo'<th class="viewheader"> &nbsp;</th>';    
 echo'</tr>';
 
/*$sql5="SELECT bio_fsproposal.leadid,
              bio_fsproposal.fs_propono,
              bio_cust.custname,
              bio_cust.custmob,
              bio_cust.contactperson,
              bio_district.district,  
              bio_fsproposal.fp_date,
              bio_fsproposal.fp_createdby,
              bio_fsproposal.fp_approvalstatus,
              bio_leads.leadid
        FROM  bio_fsproposal,
              bio_leads,
              bio_cust
    LEFT JOIN 
              bio_district ON bio_cust.district=bio_district.did 
          AND bio_cust.nationality=bio_district.cid
          AND bio_cust.state=bio_district.stateid      
        WHERE bio_fsproposal.leadid=bio_leads.leadid
          AND bio_cust.cust_id=bio_leads.cust_id
          AND bio_fsproposal.fp_createdby='".$_SESSION['UserID']."'";  */
          

          
          
 $sql5="SELECT
    `bio_fsproposal`.`fs_propono`
    , `bio_fsproposal`.`fp_date`
    , `bio_fsproposal`.`fp_amount`
        , `bio_leads`.`leadid`

        , `bio_fsproposal`.`fp_approvalstatus`  
    , `bio_fsproposal`.`printtype`
    , `bio_cust`.`custname`
    , `bio_cust`.`custphone`
    , `bio_cust`.`custmob`
    , `bio_cust`.`contactperson`
    , `bio_leadteams`.`teamname`
    , `bio_district`.`district`
    , bio_proposal_status.status
    , bio_proposal_status.statusid 
FROM
    `bio_fsproposal`
    LEFT JOIN `bio_leadteams` 
        ON (`bio_fsproposal`.`teamid` = `bio_leadteams`.`teamid`)
    LEFT JOIN `bio_leads` 
        ON (`bio_fsproposal`.`leadid` = `bio_leads`.`leadid`)
    LEFT JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
         LEFT JOIN `bio_proposal_status` 
        ON (`bio_fsproposal`.`status` = bio_proposal_status.statusid)
    LEFT JOIN `bio_district` 
        ON (`bio_cust`.`nationality` = `bio_district`.`cid`) AND (`bio_cust`.`state` = `bio_district`.`stateid`) AND (`bio_cust`.`district` = `bio_district`.`did`)    
         WHERE bio_fsproposal.teamid in($team_array)    
         AND bio_leads.leadstatus!=20    
        ";         
          
          
          
 $result=DB_query($sql5,$db);

   echo '<tbody>';
   echo '<tr>';                                       
     
      $no=0;
      $k=0; 
      while($myrow=DB_fetch_array($result))
      {  
               $fp_amount= $myrow['fp_amount'];
       $fs_id=$myrow['fs_propono'];
       $leadid=$myrow['leadid'];    
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;                   
                }
                 else 
                {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                }
      $no++;
  
  
         echo'<td align=center>'.$no.'</td>'; 
         echo'<td align=center>'.$myrow['contactperson']."<br>"."-".$myrow['custname'].'</td>';
         echo'<td align=center>'.$myrow['custmob'].'</td>'; 
         echo'<td align=center>'.$myrow['district'].'</td>';  
         echo'<td>'.ConvertSQLDate($myrow['fp_date']).'</td>';
                  echo'<td><b>'.number_format($fp_amount,2).'</b></td>';

         echo'<td align=center>'.$myrow['teamname'].'</td>';
            echo'<td align=center>'.$myrow['status'].'</td>';  
            
            
            
            
         if($myrow['statusid']==2 || $myrow['statusid']==4 || $myrow['statusid']==8|| $myrow['statusid']==6){
                           if($myrow['printtype']==NULL)
                           {
                            echo"<td><a  style='cursor:pointer;'  onclick=FsPrint('$fs_id','$leadid')>" . _('Print') . "</a></td>";                

                           }
                           else
                           {
                            echo"<td><a  style='cursor:pointer;'  onclick=FsPrint('$fs_id','$leadid')>" . _('Reprint') . "</a></td>";                
                           }
                           
      } 
      else
      {
         echo"<td>&nbsp;</td>";                
  
      }      
            
 if($myrow['statusid']==1){     
        echo"
             <td><a  style='cursor:pointer;'  onclick=EditFR('$fs_id','$leadid')>" . _('Edit') . "</a></td>";
   }
    else
      {
         echo"<td>&nbsp;</td>";                
  
      }   
   
   
if($myrow['statusid']==3 || $myrow['statusid']==7  ){
       echo"
             <td><a  style='cursor:pointer;'  onclick=NewFR('$fs_id','$leadid')>" . _('Create New') . "</a></td></tr>";
   }   
       else
      {
         echo"<td>&nbsp;</td></tr>";                
  
      }   
  /* elseif($myrow['fp_approvalstatus']==3){
       echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=FsEdit('$fs_id','$leadid')>" . _('Edit') . "</a></td></tr>";
   }
   else     */

      
 }     
echo '<tbody>';
echo"</tr></tbody></table>";
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
    
    
    
 //   $("#error").fadeOut(3000);
    $("#warn").fadeOut(8000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
     //    $(".db_message").fadeOut(3200);
    
    
    
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
  //
  
  
  function noenter() { 
    return !(window.event && window.event.keyCode == 13); 
  }
  function log_in()
{
 var f=0;
var p=0;
if(f==0){f=common_error('date','Please enter the date');  if(f==1){return f; }  }
 
}
//   
  function viewDetails(str1,str2){
//alert(str1);
//   alert(str2);
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
xmlhttp.open("GET","bio_proposaldetail.php?p=" + str1 + "&id=" + str2,true);
xmlhttp.send(); 
} 



  function NewFR(str1,str2){
//alert(str1);
//   alert(str2);
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
        
          document.getElementById('submit').style.visibility='hidden';
          document.getElementById('clear').style.visibility='hidden';
          document.getElementById('shwprint').style.visibility='hidden'; 
    document.getElementById("panel").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_proposaldetail_new.php?p=" + str1 + "&id=" + str2,true);
xmlhttp.send(); 
} 




     function modify(){
//alert(str1);
//   alert(str2);
     var date11=document.getElementById("date").value;
      // alert(date11); 
   //  var date = "24/09/1977";
var datearray = date11.explode("/");

var newdate = datearray[2] + '-' + datearray[1] + '-' + datearray[0];
     //  alert(newdate);
     
     var amount11=document.getElementById("amount").value;
                      //alert(amount11);
       var fsid=document.getElementById("fsid").value;      
       var leadid=document.getElementById("leadid").value;                     
      // alert(fsid);
       //alert(leadid);               
                      
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
    //document.getElementById('loc').focus(); 
    window.location.reload();
    }
  } 
xmlhttp.open("GET","bio_updatefs.php?lead=" + leadid + "&fs=" + fsid +"&amt=" + amount11 +"&dat=" + newdate,true);
xmlhttp.send(); 
} 







     function newt() {
   // alert("jkjjj");
 // alert(str2);
    var date11=document.getElementById("date11").value;
      // alert(date11); 
   //  var date = "24/09/1977";
var datearray = date11.split("/");

var newdate = datearray[2] + '-' + datearray[1] + '-' + datearray[0];
     // alert(newdate);
     
     var amount11=document.getElementById("amount").value;
       //               alert(amount11);
       var fsid=document.getElementById("fsid").value;      
       var leadid=document.getElementById("leadid").value;                     
      //alert(fsid);
     //alert(leadid);               
                      
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
    //document.getElementById('loc').focus(); 
   // window.location.reload();
    }
  } 
xmlhttp.open("GET","bio_fpnew.php?lead=" + leadid + "&fs=" + fsid +"&amt=" + amount11 +"&dat=" + newdate,true);
xmlhttp.send(); 
} 













    function EditFR(str1,str2){
//alert(str1);
//   alert(str2);
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
     document.getElementById('submit').style.visibility='hidden';
          document.getElementById('clear').style.visibility='hidden';
          document.getElementById('shwprint').style.visibility='hidden'; 
    document.getElementById("panel").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_proposaldetail_edit.php?p=" + str1 + "&id=" + str2,true);
xmlhttp.send(); 
} 





 function FsPrint(str1,str2){
       // alert(str1);
       //alert(str2);
      controlWindow=window.open("bio_selectfsproposalprint.php?leadid=" + str2 + "&fs_propono=" +str1,"selectproposalpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=730,height=490")   
//          window.location="bio_selectfsproposalprint.php?leadid=" + str2; 
    }

//function FsPrint(str1,str2)

//{ 
//     myRef = window.open("bio_feasibility_print.php?leadid=" + str2);

//}
</script>
