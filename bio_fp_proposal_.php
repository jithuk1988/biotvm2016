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
  $leadid=$_POST['Leadid'];
  $teamid=$_POST['Team'];
  $approvalby="admin"; 
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
      generatetask($leadid,$taskid,$teamid,$db);
     
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
    }       
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
   $TID=$_GET['id']; 
  $sql="SELECT bio_cust.custname,
               bio_leadtask.leadid,
               bio_cust.contactperson,
               bio_leadtask.duedate,
               bio_leadtask.assigneddate,
               bio_leadtask.teamid,
               bio_changepolicy.value 
        FROM   bio_cust,
               bio_leads,
               bio_leadtask,
               bio_changepolicy  
        WHERE  bio_cust.cust_id=bio_leads.cust_id 
          AND  bio_leadtask.leadid=bio_leads.leadid 
          AND  bio_changepolicy.policyname='Institution FS Charge' 
          AND  bio_leadtask.leadid=".$lead_ID;

   $result=DB_query($sql,$db);    
   $myrow=DB_fetch_array($result);  
   
  $leadid=$myrow['leadid'];  
  $cname=$myrow['custname'];  
  $orgname=$myrow['contactperson'];
  $date=ConvertSQLDate($myrow['duedate']); 
  $amount=$myrow['value'];
  $startdate=ConvertSQLDate($myrow['assigneddate']);
    
}






 echo"<tr><td width=50%>Lead no</td>";
 echo"<td><input type='hidden' name='Leadid' id='lead' value='$leadid'>$leadid</td></tr>";
 echo"<tr></tr>";
 echo"<tr><td width=50%>Customer name</td>";
 echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
 echo"<tr></tr>";
 echo"<tr><td width=50%>Contact Person</td>";
 echo"<td><input type='hidden' name='Housename' id='housename' value='$orgname'>$orgname</td></tr>";
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
 echo"<tr><td>Start date</td>";
 echo"<td><input type='hidden' name='StartDate' id='startdate' style='width:135px' value='$startdate'>$startdate</td></tr>";
 echo"<tr></tr>";
 echo"<tr><td>Due Date</td>";
 echo"<td><input type='hidden' name='Date' id='date' value='$date'>$date</td></tr>";
 echo"<tr></tr>";
 echo"<tr><td>Expected Date</td>";
 echo"<td><input type='text' name='ExpectedDate' id='date' value='$exdate'>$exdate</td></tr>";
 echo"<tr></tr>";
 echo"<tr><td>FS Charge</td>";
 echo"<td><input type='text' name='Amount' id='amount' value='$amount'></td></tr>";
 echo'<tr>';
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
<input type=submit name=submit value="' . _('Assign') . '" onclick="if(log_in()==1)return false;">';
 echo '<input name="clear" type="submit" value=Clear ><input id="shwprint" type="button" name="shwprint" value="view/hide proposals">';
 echo'</div>';
 echo'</td></tr>';
 echo'</div>'; 
 echo"</td></tr></table>";
 echo'</form>';
 echo "<div id=leadgrid'>";
 echo"<fieldset style='width:760px'><legend>Lead Details</legend>";          
 echo "<table style='border:0px solid #F0F0F0;width:100%'>";    
 echo'<tr><th class="viewheader">slno</th>';
 echo'<th class="viewheader">Lead no</th>';
 echo'<th class="viewheader">Customer Name</th>';  
 echo'<th class="viewheader">Contact Person</th>';   
 echo'<th class="viewheader">Due Date</th>';
 echo'<th class="viewheader">Assigned To</th>';
 echo'<th class="viewheader"> &nbsp;</th>';    
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
  bio_enquirytypes.enquirytype AS enqtype,
  bio_outputtypes.outputtype AS outputtype,
  bio_leads.leadid AS leadid, 
  bio_leads.leaddate AS leaddate,
  bio_leadteams.teamname AS teamname,
  bio_leadsources.sourcename AS sourcename,
  bio_office.id AS officeid,
  bio_office.office AS office,
  bio_district.district,
  bio_leadtask.assigned_from,
  bio_leadtask.duedate,
  bio_leadtask.teamid,
  bio_leadtask.tid      
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,
bio_enquirytypes,
bio_office,
bio_district,
bio_outputtypes,
bio_leadtask
WHERE bio_cust.cust_id=bio_leads.cust_id  
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.enqtypeid=2
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
AND bio_leadsources.id=bio_leads.sourceid  
AND bio_office.id=bio_leadsources.officeid
AND bio_cust.district=bio_district.did
AND bio_district.stateid=bio_cust.state
AND bio_district.cid=bio_cust.nationality
AND bio_leads.leadstatus=0
AND bio_leadtask.taskid=15 
AND bio_leadtask.leadid=bio_leads.leadid
AND bio_leadteams.teamid=bio_leadtask.teamid
AND bio_leadtask.teamid IN ($team_array)
AND bio_leadtask.viewstatus=1";   

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
       $teamid=$myrow['teamid'];
       echo$fs_amount=$myrow['value'];
// echo"<td><input type='text' name='Amount' id='amount' value='$amount'></td></tr>"; 
  $sql2="SELECT bio_leadtask.teamid,
                bio_teammembers.empid,
                bio_emp.empname  
          FROM  bio_leadtask,bio_emp,bio_teammembers 
          WHERE bio_teammembers.teamid= bio_leadtask.teamid
           AND  bio_emp.empid= bio_teammembers.empid      
           AND  bio_leadtask.leadid=".$lead_ID;
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
         echo'<td>'.$no.'</td>';
         echo'<td align=center>'.$myrow['leadid'].'</td>';
         echo'<td align=center>'.$myrow['custname'].'</td>';
         echo'<td align=center>'.$myrow['orgname'].'</td>';
         echo'<td>'.$myrow['duedate'].'</td>';
         echo'<td align=center>'.$myrow4['empname'].'</td>';
         echo "<td><a style=cursor:pointer; id=".$myrow['duedate']." onclick=viewDetails('$lead_ID','$tid')>Select</a></td>";      
      }             
                     
              
echo '<tbody>';
echo"</tr></tbody>
</table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';

//------------------------------------------------------------------------------------------------------------------------//
echo'<div id="proposalgrid">';
echo"<fieldset style='width:760px'><legend>Proposal Details</legend>"; 
   
 
echo"<table style='width:100%' border=0> ";

 echo'<tr><th class="viewheader">slno</th>';
 echo'<th class="viewheader">Lead no</th>';
 echo'<th class="viewheader">Customer Name</th>';  
 echo'<th class="viewheader">Contact Person</th>';   
 echo'<th class="viewheader">Due Date</th>';
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
              bio_cust.contactperson,
              bio_fsproposal.fp_date,
              bio_fsproposal.fp_createdby,
              bio_fsproposal.fp_approvalstatus,
              bio_leads.leadid
        FROM  bio_fsproposal,bio_cust,bio_leads
        WHERE bio_fsproposal.leadid=bio_leads.leadid
          AND bio_cust.cust_id=bio_leads.cust_id 
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
  
  
         echo'<td>'.$no.'</td>';
         echo'<td align=center>'.$myrow['leadid'].'</td>';
         echo'<td align=center>'.$myrow['custname'].'</td>';
         echo'<td align=center>'.$myrow['contactperson'].'</td>';

         echo'<td>'.$myrow['fp_date'].'</td>';
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


  
  
   
  function viewDetails(str1,str2){
alert(str1);
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