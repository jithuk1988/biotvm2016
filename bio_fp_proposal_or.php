<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('feasibility proposal Details');  
$pagetype=1;
include('includes/header.inc');
include('bio_generateleadtask.php');
 
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
     
         $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
     

        $InputError=0;                       
       // $startdate=FormatDateForSQL($_POST['ExpectedDate']);
        
        
        $Expected_date=FormatDateForSQL($_POST['ExpectedDate']);   
        //$subtask_date=FormatDateForSQL($_POST['SubtaskDate']);                  
        $datearr1 = split("-",$Expected_date); 
        //$datearr2 = split("-",$subtask_date); 
        $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]);
        if($date_diff>30){
            $InputError=1;
            prnMsg('The End date should be with in 30 days from Expected date','warn');
        }
        $sqlr="SELECT value
        FROM   bio_changepolicy
         WHERE  policyname ='Institution FS Charge'";    
            $resulte=DB_query($sqlr, $db);     
            $mr=DB_fetch_array($resulte);          
  $fs_amount=$mr[0];   
  $amount=$_POST['Amount'];
 $leadid=$_POST['LeadID'];
  $teamid=$_POST['Team'];
  $approvalby="businesshead";
  if($userid=="businesshead"){
    $approvalby="admin";  
  } 
   if($amount==$fs_amount)
   {
   $sql6= "INSERT INTO bio_fsproposal(leadid,
                                      fp_date,
                                      fp_amount,
                                      fp_createdby,
                                      fp_approvalby,
                                      fp_expapproval,
                                      teamid,
                                      fp_approvalstatus )
                          VALUES ('".$leadid."',
                                 '".$DateString."',
                                 '".$amount."',        
                                 '".$userid."',
                                 '".$approvalby."',
                                 '".$Expected_date."',
                                 '".$teamid."',
                                 4)";                                           
      $result = DB_query($sql6,$db);
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
                                      fp_approvalstatus )
                          VALUES ('".$leadid."',
                                 '".$DateString."',
                                 '".$amount."',        
                                 '".$userid."',
                                 '".$approvalby."',
                                 '".$Expected_date."', 
                                 '".$teamid."',
                                 1)";                                           
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
      window.opener.location='bio_instTaskview.php?lead='+ lead;
      window.close();

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
//------------------------------------------------------------------------------------------------------------------------//
 if($_GET['lead']!=''){
    $lead_ID=$_GET['lead'];
   $TID=$_GET['tid']; 
   
$sql="SELECT bio_cust.custname,
               bio_leadtask.leadid,
               bio_cust.contactperson,
               bio_leadtask.duedate,
               bio_leadtask.assigneddate,
               bio_leadtask.teamid,
               bio_cust.custmob, 
               bio_district.district,
               bio_leads.remarks,
               bio_changepolicy.value 
          FROM bio_cust,
               bio_leads,
               bio_leadtask,
               bio_changepolicy,
               bio_district  
         WHERE bio_cust.cust_id=bio_leads.cust_id 
           AND bio_leadtask.leadid=bio_leads.leadid 
           AND bio_district.did=bio_cust.district
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality
           AND bio_changepolicy.policyname='Institution FS Charge' 
           AND bio_leadtask.leadid=".$lead_ID."
           AND bio_leadtask.tid=".$TID;

   $result=DB_query($sql,$db);    
   $myrow=DB_fetch_array($result);  
   
  $leadid=$myrow['leadid'];  
  $cname=$myrow['custname'];  
  $orgname=$myrow['contactperson'];
  $number=$myrow['custmob'];  
  $date=ConvertSQLDate($myrow['duedate']); 
  $amount=$myrow['value'];
  $startdate=ConvertSQLDate($myrow['assigneddate']);
  $district=$myrow['district'];
  $remark=$myrow['remarks'];
    
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
//echo"<tr><td width=50%>Feasibility Team*</td>";
//echo"<td><select name='fteam' id='fteam' style='width:150px'>";
//$sql="SELECT bio_leadteams.teamid,bio_leadteams.teamname FROM bio_leadteams";
//$result=DB_query($sql,$db);

//    $f=0;
//  while($myrow1=DB_fetch_array($result))
//  {  
//  if ($myrow1['teamid']==$_POST['fteam'])  
//    {   
//    echo '<option selected value="';
//    } else 
//    {
//    if ($f==0) 
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//    }
//    echo $myrow1['teamid'] . '">'.$myrow1['teamname'];
//    echo '</option>';
//    $f++;
//   }   
//    

//   
//echo"</select></td></tr>";

 $DateString = Date($_SESSION['DefaultDateFormat']); 
 
 echo"<tr><td>Start date :</td>";
 echo"<td><input type='hidden' name='StartDate' id='startdate'  style='width:135px' value='".$startdate."'>$startdate</td></tr>";

 echo"<tr></tr>";
 echo"<tr><td>Due Date :</td>";
 echo"<td><input type='hidden' name='Date' id='date1' value='".$date."'>".$date."</td></tr>";

 echo"<tr><td>Expected Date For Approval* :</td>";
 echo"<td><input type='text' name='ExpectedDate'  id='date' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:135px'></td></tr>";
 echo"<tr></tr>";
 
 echo"<tr><td>FS Charge :</td>";       
 echo"<td><input type='text' name='Amount' id='amount' value='$amount'></td></tr>";

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
      <input type=submit name=submit value="' . _('Submit') . '" onclick="if(log_in()==1)return false;">';
 echo '<input name="clear" type="submit" value=Clear ><input id="shwprint" type="button" name="shwprint" value="view/hide proposals">';
 echo'</div>';
 echo'</td></tr>';
 echo'</div>'; 
 echo"</td></tr></table>";
 echo'</form>';
                 if(!isset($_GET['tid']))
                 {

 echo "<div id=leadgrid'>";
 echo"<fieldset style='width:760px'><legend>Lead Details</legend>";
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
// $sql="select bio_leadtask.leadid,
//             bio_cust.housename,
//             bio_leadtask.duedate,
//             bio_cust.custname,
//             bio_emp.empname
//                        
//      from   bio_leadtask,
//             bio_cust,
//             bio_leads,
//             bio_emp
//      where  bio_leadtask.leadid=bio_leads.leadid
//      AND bio_leads.cust_id=bio_cust.cust_id
//      AND bio_emp.empid=bio_leadtask.assigned_from
//        AND  bio_leads.enqtypeid=2 
//        AND   bio_leadtask.viewstatus=1"; 
//    
//    $result=DB_query($sql,$db);

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
               bio_district.district,
               bio_cust.custmob AS custmob,   
               bio_leadtask.assigned_from,
               bio_leadtask.duedate,
               bio_district.district AS district, 
               bio_leadtask.teamid,
               bio_office.id AS officeid,
               bio_office.office AS office,
               bio_status.biostatus
          FROM bio_cust,
               bio_leads,
               bio_leadteams,
               bio_leadsources,
               bio_enquirytypes,
               bio_office,
               bio_outputtypes,
               bio_status,
               bio_district,
               bio_leadtask
         WHERE bio_cust.cust_id=bio_leads.cust_id  
           AND bio_leads.enqtypeid=2
           AND bio_leadsources.id=bio_leads.sourceid  
           AND bio_cust.district=bio_district.did
           AND bio_district.stateid=bio_cust.state
           AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
           AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
           AND bio_leadsources.id=bio_leads.sourceid  
           AND bio_district.did=bio_cust.district
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality
           AND bio_office.id=bio_leadsources.officeid
           AND bio_status.statusid=bio_leads.leadstatus
           AND bio_leadtask.taskid=15 
           AND bio_leadtask.leadid=bio_leads.leadid
           AND bio_leadteams.teamid=bio_leadtask.teamid
           AND bio_leadtask.teamid IN ($team_array)
           AND bio_leadtask.viewstatus=1";  
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
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql.= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql.= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }
        
  }
     $sql.= " GROUP BY bio_leadtask.tid desc";
 $result=DB_query($sql,$db);
   echo '<tbody>';
   echo '<tr>';                                         
      $no=0;
      $k=0;
         
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
                
//  $sql_action="SELECT * FROM bio_leadtask,bio_task
            //        WHERE bio_leadtask.leadid=".$leadid."
//                    AND bio_leadtask.taskcompletedstatus=0
//                    AND bio_task.taskid=bio_leadtask.taskid
//                    ORDER BY bio_leadtask.assigneddate ASC";
//      $result_action=DB_query($sql_action,$db);
//      $myrow_action=DB_fetch_array($result_action);
//      $next_action=$myrow_action['task']; 
//      $action_date=$myrow_action['assigneddate'];
//      if($action_date!=""){
//         $action_date=ConvertSQLDate($action_date);
//      }else{
//         $next_action='Not assigned';
//         $action_date='Not assigned'; 
//      }           
      $no++;
        
         echo'<td align=center>'.$myrow['custname'].'<br>'."-".$myrow['orgname'].'</td>';
        // echo'<td align=center>'.$myrow['orgname'].'</td>';
         echo'<td align=center>'.$myrow['custmob'].'</td>';  
         echo'<td align=center>'.$myrow['district'].'</td>';      
         echo'<td>'.ConvertSQLDate($myrow['duedate']).'</td>';
         echo'<td>'.$myrow['biostatus'].'</td>';           
         echo'<td align=center>'.$myrow['teamname'].'</td>';
        echo'<td align=center>'.$myrow4['ast'].'</td>';     
         echo "<td><a style=cursor:pointer; id=".ConvertSQLDate($myrow['duedate'])." onclick=viewDetails('$lead_ID','$teamid')>Select</a></td>";      
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
echo"<fieldset style='width:760px'><legend>Proposal Details</legend>"; 
   
 
 echo"<table style='width:100%' border=0> ";
 echo'<th class="viewheader">Institution Name/<br>Customer Name</th>'; 
 echo'<th class="viewheader">Contact Number</th>'; 
 echo'<th class="viewheader">District</th>';    
 echo'<th class="viewheader">Assign Date</th>';
 echo'<th class="viewheader">Team</th>';
 echo'<th class="viewheader"> &nbsp;</th>';    
 echo'</tr>';
 

 

  //SELECT bio_cust.custname AS custname,               
//  bio_cust.housename AS orgname,
//  bio_leads.leadid AS leadid, 
// 
//  bio_leadteams.teamname AS teamname,
//  
//  bio_leadtask.assigned_from,
//  bio_leadtask.duedate,
//  bio_leadtask.teamid,
//  bio_leadtask.tid,
// 
//FROM bio_cust,
//bio_leads,
//bio_leadteams,
//bio_leadtask,  
//WHERE bio_cust.cust_id=bio_leads.cust_id  

//AND bio_leads.leadstatus=0
//AND bio_leadtask.taskid=15
//AND  bio_changepolicy.policyname='Institution FS Charge'   
//AND bio_leadtask.leadid=bio_leads.leadid
//AND bio_leadteams.teamid=bio_leadtask.teamid
//AND bio_leadtask.teamid IN ($team_array)

//AND bio_leadtask.viewstatus=1"; 

$sql5="SELECT bio_fsproposal.leadid,
              bio_fsproposal.fs_propono,
              bio_cust.custname,
              bio_cust.custmob,
              bio_cust.contactperson,
              bio_district.district,  
              bio_fsproposal.fp_date,
              bio_fsproposal.fp_createdby,
              bio_fsproposal.fp_approvalstatus,
              bio_leads.leadid
        FROM  bio_fsproposal,bio_cust,bio_leads,bio_district
        WHERE bio_fsproposal.leadid=bio_leads.leadid
          AND bio_cust.cust_id=bio_leads.cust_id
          AND bio_district.did=bio_cust.district
          AND bio_district.stateid=bio_cust.state
          AND bio_district.cid=bio_cust.nationality
          AND bio_fsproposal.fp_createdby='".$_SESSION['UserID']."'";
          
 $result=DB_query($sql5,$db);

   echo '<tbody>';
   echo '<tr>';                                       
     
      $no=0;
      $k=0; 
      while($myrow=DB_fetch_array($result))
      {  
      
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
  
  
        
         echo'<td align=center>'.$myrow['contactperson']."<br>"."-".$myrow['custname'].'</td>';
         echo'<td align=center>'.$myrow['custmob'].'</td>'; 
         echo'<td align=center>'.$myrow['district'].'</td>';  
         echo'<td>'.ConvertSQLDate($myrow['fp_date']).'</td>';
         echo'<td align=center>'.$myrow['fp_createdby'].'</td>';
         
 /* if($myrow['fp_approvalstatus']==1){     
        echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=FsEdit('$fs_id','$leadid')>" . _('Edit') . "</a></td></tr>";
   }
   elseif($myrow['fp_approvalstatus']==2){
       echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=FsEdit('$fs_id','$leadid')>" . _('Select') . "</a></td></tr>";
   }
   elseif($myrow['fp_approvalstatus']==3){
       echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=FsEdit('$fs_id','$leadid')>" . _('Edit') . "</a></td></tr>";
   }
   else     */
   if($myrow['fp_approvalstatus']==4){
       echo"<td><a  style='cursor:pointer;'  onclick=FsPrint('$fs_id','$leadid')>" . _('Print') . "</a></td>";                
      } 
      
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
//  function log_in()
//{
// var f=0;
//var p=0;
//if(f==0){f=common_error('date','Please enter the date');  if(f==1){return f; }  }
// 
//}
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

 function FsPrint(str1,str2){
       // alert(str1);
       //alert(str2);
      controlWindow=window.open("bio_selectfsproposalprint.php?leadid=" + str2 + "&fs_propono=" +str1,"selectproposalpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400")   
//          window.location="bio_selectfsproposalprint.php?leadid=" + str2; 
    }

//function FsPrint(str1,str2)

//{ 
//     myRef = window.open("bio_feasibility_print.php?leadid=" + str2);

//}
</script>