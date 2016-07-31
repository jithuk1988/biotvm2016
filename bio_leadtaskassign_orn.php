<?php
$PageSecurity = 80;
include('includes/session.inc');
include('includes/removespecialcharacters.php');  

$title = _('Team Task');  
include('includes/header.inc');

?>
<script type="text/javascript">


function log_in()
{
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('custname','Please select a task');  if(f==1){return f; }  }  
if(f==0){f=common_error('team','Please select a team');  if(f==1){return f; }  } 
if(f==0){f=common_error('startdate','Please enter start date');  if(f==1){return f; }  }  
if(f==0){f=common_error('Schedule','Please select a Schedule');  if(f==1){return f; }  } 

}


function passid(){
    
    var taskcnt=document.getElementById('taskcount').value;
    var checked_count=0;
    
 var k=0;
 var leadArray = new Array();
 var taskArray = new Array();   
 for (var i = 1; i <= taskcnt; i++)
{
    var leadtask='leadtask'+i;
if (document.getElementById(leadtask).checked)
{
    var leadtaskvalue=document.getElementById(leadtask).value;
//    alert(leadtaskvalue);
    var value_split=leadtaskvalue.split(",");
    leadArray[k]=value_split[0];
    taskArray[k]=value_split[1];
   k++;
   checked_count=1 
}

}

if(checked_count==0){
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
    document.getElementById('office').focus(); 
    }
  } 
xmlhttp.open("GET","bio_taskcustdetails.php?p=" + leadArray + "&q=" +taskArray,true);
xmlhttp.send(); 
}

function officeteam(str)
{
           //  alert(str);
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
    {     // alert("ddd");
    document.getElementById("byteam").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_taskExport_officeteam.php?officeid=" + str,true);
xmlhttp.send();

}

function leadstatus(str)

{
           //  alert(str);
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
    {     // alert("ddd");
    document.getElementById("statussrc").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_taskExport_leadstatus.php?enqid=" + str,true);
xmlhttp.send();

}
function transfer(str){
    
    var lead=document.getElementById('leadid').value;
    var task=document.getElementById('taskid').value;
//    alert(str);
//    alert(lead); alert(task);
if (str=="")
  {
  document.getElementById("transfer").innerHTML="";
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
    document.getElementById("transfer").innerHTML=xmlhttp.responseText;

    }
  } 

xmlhttp.open("GET","bio_transferteam.php?off=" + str + "&leadid=" + lead + "&task="+ task,true);
xmlhttp.send();    
}

function autoschedule(str,str2){
    
    
    
    var taskid=document.getElementById('task').value;
    var scheduleid=document.getElementById('Schedule').value;    
    var startdate=document.getElementById('startdate').value;       
     
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
    document.getElementById("enddatetd").innerHTML=xmlhttp.responseText;      

    }
  } 
xmlhttp.open("GET","bio_enddatecalc.php?taskid=" + taskid + "&scheduleid=" + scheduleid + "&startdate=" + startdate,true);  
xmlhttp.send();    
}

function subtask1(str1,str2){
//alert(str1); 
//alert(str2); 

//    var leadid=document.getElementById('leadid').value;
   /* var taskid=document.getElementById('task').value;
    var scheduleid=document.getElementById('Schedule').value;    
    var startdate=document.getElementById('startdate').value;       */
//    alert(leadid);
   // alert(taskid);
//    alert(scheduleid);
//    alert(startdate);
     
  
//         window.location="bio_sub_taskmaster.php";
myRef = window.open("bio_sub_taskmaster.php?leadid=" + str1 + "&taskid=" + str2,"subtaskassign","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=500");
    }
    
function viewschedule(str3)
{

   var scheduleid=document.getElementById('Schedule').value;
   if(scheduleid=="")       {
       
   alert("Select a Schedule");
   document.getElementById("Schedule").focus();
   return;
   }
   
   
   myRef = window.open("bio_view_assigned_schedule.php?id=" + scheduleid,"viewschedule","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=600,height=400");

//   myRef=window.open("bio_view_assigned_schedule.php");
         
}

function teamstatus()
{   

   var teamid=document.getElementById('team').value;
   
   if(teamid=="")       {
       
   alert("Select a Team");
   document.getElementById("team").focus();
   return;
   }
   myRef = window.open("bio_leadtaskassign-teamstatus.php?teamid=" + teamid,"viewschedule","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=600,height=400");

//   myRef=window.open("bio_view_assigned_schedule.php");
         
}

</script>
<?php
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Task Assigning</font></center>';

    $office=$_SESSION['UserStockLocation'];

if (isset($_POST['submit'])) {
    
    if(!isset($_POST['LeadID'])){
      prnMsg('Select lead details from the grid and then assign the team','warn');  
        
    }
    else{
    $lead_ID=$_POST['LeadID'];
    $task_ID=$_POST['TaskID'];
    
    $leadarray=explode(",",$lead_ID);
    $taskarray=explode(",",$task_ID);
  
    $leadcount=count($leadarray);
    $taskcount=count($taskarray);
    $team_ID=$_POST['team'];
    $startdate=FormatDateForSQL($_POST['StartDate']);
    $enddate=FormatDateForSQL($_POST['EndDate']);
    $scheduleid=$_POST['Schedule'];
    $emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid'];
    for($i=0;$i<$leadcount;$i++){
       
        $sql_status="SELECT COUNT(*) FROM bio_leadschedule
                WHERE leadid=".$leadarray[$i];
    $result_status=DB_query($sql_status,$db);
    $myrow_status=DB_fetch_array($result_status);
    $status_count=$myrow_status[0];
    
    $sql_st="UPDATE bio_leadtask SET viewstatus=0,
                                     taskcompletedstatus=2 
                WHERE leadid=".$leadarray[$i];
    
    $result_st=DB_query($sql_st,$db);
    
    
    
    if($status_count>0){
        $sql9="SELECT task_master_id,actual_task_day
         FROM bio_schedule
         WHERE schedule_master_id= $scheduleid
         AND schedule_id>=(SELECT schedule_id FROM bio_schedule WHERE task_master_id=".$taskarray[$i]." AND schedule_master_id=$scheduleid
          ORDER BY schedule_id ASC)ORDER BY schedule_id ASC
         ";
        $result9=DB_query($sql9,$db);
        while($mysql9=DB_fetch_array($result9)){
            
            $task_ID=$mysql9['task_master_id'];
      $date_interval+=$mysql9['actual_task_day'];
      
      
      $sql="INSERT INTO bio_leadtask(taskid,
                                   leadid,
                                   teamid,
                                   assigneddate,
                                   duedate,
                                   assigned_from,
                                   viewstatus) 
                            VALUES ('".$task_ID."',                                        
                                    '".$leadarray[$i]."',
                                    '".$team_ID."',
                                    '".$startdate."',
                                    '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                    '".$assignedfrm."',
                                    1)";
      $result=DB_query($sql,$db);
      $startdate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
      $date_interval+=1;
    }
    $sql22="UPDATE bio_leadschedule SET scheduleid='".$scheduleid."'
                    WHERE leadid='".$leadarray[$i]."'";
    $result22=DB_query($sql22,$db);
        
   }
   
   else{
        $sql_st="UPDATE bio_leadtask SET viewstatus=0 
                WHERE leadid=".$leadarray[$i];
    
    $result_st=DB_query($sql_st,$db);
    
    
    
    $sql9="SELECT task_master_id,actual_task_day
         FROM bio_schedule
         WHERE schedule_master_id= $scheduleid
          ORDER BY schedule_id ASC";
  $result9=DB_query($sql9,$db);
  while($mysql9=DB_fetch_array($result9)){
      $task_ID=$mysql9['task_master_id'];
      $date_interval+=$mysql9['actual_task_day'];
      
      
      $sql="INSERT INTO bio_leadtask(taskid,
                                   leadid,
                                   teamid,
                                   assigneddate,
                                   duedate,
                                   assigned_from,
                                   viewstatus) 
                            VALUES ('".$task_ID."',                                        
                                    '".$leadarray[$i]."',
                                    '".$team_ID."',
                                    '".$startdate."',
                                    '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                    '".$assignedfrm."',
                                    1)";
    $result=DB_query($sql,$db);
    $startdate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
    $date_interval+=1;
    
    }
    $sql22="INSERT INTO bio_leadschedule(leadid,
                                       scheduleid)
          VALUES('".$leadarray[$i]."',
                 '".$scheduleid."')";
    $result22=DB_query($sql22,$db);
    }
    
    
     $date_interval=0;
     $startdate=FormatDateForSQL($_POST['StartDate']);
    
    
    }
   
    
    
    
    
    
    
    
   
   
   
                                    
       
    }
/*    
$sql_rights2="SELECT empid FROM bio_teammembers WHERE teamid=".$team_ID;
$result_rights2=DB_query($sql_rights2,$db);
$emp_arr=array();
while($row_rights2=DB_fetch_array($result_rights2)) 
{  
  $emp_arr[]=$row_rights2['empid'];
  $employee_array=join(",", $emp_arr); 
}

$sql_accessRights2="UPDATE bio_accessrights SET w=0 WHERE leadid=$lead_ID";
$result_accessRights2=DB_query($sql_accessRights2,$db); 

$sql_rights3="SELECT userid FROM www_users WHERE empid IN ($employee_array)";
$result_rights3=DB_query($sql_rights3,$db);
//$userid_arr=array();
//$flg=0;
while($row_rights3=DB_fetch_array($result_rights3)) 
{  
    $sql_check="SELECT count(*) FROM bio_accessrights WHERE leadid=$lead_ID AND userid='".$row_rights3['userid']."'";
    $result_check=DB_query($sql_check,$db);
    $row_check=DB_fetch_array($result_check);
  
        if($row_check[0]==0)
        {
             $sql_accessRights1="INSERT INTO bio_accessrights (leadid,
                                                    userid,
                                                    teamid,
                                                    taskid,
                                                    r,
                                                    w)
                                            VALUES ('$lead_ID',
                                            '$row_rights3[userid]',
                                            '$team_ID',
                                            1,
                                            1,
                                            1
                                            )";   
         $result_accessRights1=DB_query($sql_accessRights1,$db);   
        }
        else
        {
            $sql_accessRights1="UPDATE bio_accessrights SET w=1 WHERE leadid=$lead_ID AND userid='".$row_rights3['userid']."'";
            $result_accessRights1=DB_query($sql_accessRights1,$db); 
        } 

}     
*/
    unset($_POST['team']);
    unset($_POST['StartDate']);
    unset($_POST['EndDate']);
}

echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style="width:70%"><tr><td>';
echo"<div id=panel>";
echo '<table><tr>';

//========================================== Left Panel Begins


echo'<td>';

echo"<div id=cus_details>";
echo"<fieldset style='width:460px;height:180px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";
echo"<table width=100%>";


if($_GET['lead']!=''){
    $leadid=$_GET['lead'];    
    $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
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
 $email=$myrow['custmail'];  
 $place=$myrow[5];
 $dist=$myrow[6];
 $ste=$myrow[7];
 $ctry=$myrow[8]; 
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
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Contact</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district'>$district</td></tr>";
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
echo"<fieldset style='width:460px;height:180px; overflow:auto;'>";

echo"<legend>Assign Team</legend>";
echo"<table>";

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

echo"<tr><td width=50%>Assign Office</td>";

echo"<td><select name='office' id='office' style='width:150px'>";
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
echo"</table>";                                                                                  
 //======
echo"<table id=transfer>";

echo"<tr><td width=50%>Assign Team</td>";
echo"<td><select name='team' id='team' style='width:150px'>";
$sql="SELECT * FROM bio_leadteams";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['teamid']==$_POST['teamname'])  
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
  echo"</td></tr>";

echo'<tr><td></td>
         <td><a style="cursor:pointer;" onclick="teamstatus()">View team status</a></td></tr>';   


$DateString = Date($_SESSION['DefaultDateFormat']); 

echo"<tr><td>Start date*</td>";
echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:146px' value='".$DateString."'></td></tr>";


echo "<tr><td>Schedule</td>";
echo '<td>';

 $sql1="SELECT * FROM bio_schedule_master";
  $result1=DB_query($sql1, $db);
  echo '<select name="Schedule" id="Schedule" style="width:150px" >';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['master_schedule_id']==$_POST['schedule']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['master_schedule_id'] . '">'.$myrow1['schedule']." - ".$myrow1['schedule_days']." days"; 
    echo '</option>' ;
   $f++; 
   }
 echo '</select>';
 echo "</td></tr>";
 
 echo"<tr><td>End date*</td>";
echo"<td><input type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:146px'></td></tr>";


echo"<tr><td width=50%>Assign Task</td>";
echo"<td><select name='task' id='task' style='width:150px'>";
$sql="SELECT * FROM bio_task";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['taskid']==$_POST['task'])  
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
    echo $myrow1['taskid'] . '">'.$myrow1['task'];
    echo '</option>';
    $f++;
   }   
echo"</select></td></tr>";


   
 
echo"</table>";  
//==============



echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>";
echo"</div>";


//========================================== Buttons 
echo"<table>";
echo'<tr><td colspan=2><p><div class="centre">
<input type=submit name=submit value="' . _('Assign') . '" onclick="if(log_in()==1)return false;">';
echo '<input name="clear" type="submit" value=Clear >';

echo'</div>';
echo'</td></tr>';

echo'</div>'; 
echo"</td></tr></table>";
echo'</form>';

//========================================== Buttons Ends

//========================================== Grid - from leads table   


$office=$_SESSION['UserStockLocation'];    
//---------------------------------------------------------------------------------------------------------------------------------------------------------

/* $empid=$_SESSION['empid'];
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
     while($row6=DB_fetch_array($result6)){
        $team[]=$row6['teamid'];
     }       
     $team_array=join(",", $team);
*/    


$empid=$_SESSION['empid'];   
  

$sql_emp1="SELECT * FROM bio_emp WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
       
     
 $employee_arr=array();   
     $sql_drop="DROP TABLE if exists `emptable`";
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
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($empid)";
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



      $schedule=array();
      $sql7="SELECT leadid FROM bio_leadschedule";
      $result7=DB_query($sql7,$db);
      while($row7=DB_fetch_array($result7)){
        $schedule[]=$row7['leadid'];  
      }  
      $schedule_arr=join(",", $schedule);
      
 /*$sql="SELECT bio_leadtask.leadid AS leadid,
              bio_task.task AS task,
              bio_cust.cust_id AS custid,  
              bio_cust.custname AS custname,               
              bio_cust.area1 AS place,
              bio_cust.custmob AS mob, 
              bio_leadtask.assigneddate AS assigneddate,
              bio_leadtask.duedate AS duedate,
              bio_leads.leadstatus AS leadstatus,
              bio_enquirytypes.enqtypeid AS enqtypeid,
              bio_enquirytypes.enquirytype AS enqtype, 
              bio_leadteams.teamname AS teamname,
              bio_leadsources.sourcename AS sourcename,
              bio_office.id AS officeid,
              bio_office.office AS office,
              bio_status.biostatus,
              bio_district.district,
              bio_leadtask.tid,
              bio_leadtask.taskid
         FROM bio_cust,
              bio_leadtask,
              bio_task,
              bio_leads,
              bio_leadteams,
              bio_enquirytypes,
              bio_leadsources,
              bio_office,
              bio_status,
              bio_district
        WHERE bio_leadtask.leadid=bio_leads.leadid
          AND bio_task.taskid=bio_leadtask.taskid
          AND bio_cust.cust_id=bio_leads.cust_id 
          AND bio_leadteams.teamid=bio_leadtask.teamid 
          AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid 
          AND bio_leadsources.id=bio_leads.sourceid
          AND bio_office.id=bio_leadsources.officeid
          AND bio_district.did=bio_cust.district
          AND bio_district.stateid=bio_cust.state
          AND bio_district.cid=bio_cust.nationality
          AND bio_leadtask.teamid IN ($team_array)         
          AND bio_status.statusid=bio_leads.leadstatus
          AND bio_leads.leadstatus NOT IN(20)
          AND bio_leadtask.viewstatus=1
          AND bio_leadtask.taskcompletedstatus=0         
          ";  */



$sql="SELECT bio_leadtask.leadid AS leadid,
              bio_task.task AS task,
              bio_cust.cust_id AS custid,  
              bio_cust.custname AS custname,   
              bio_cust.contactperson,            
              bio_cust.area1 AS place,
              bio_cust.custmob AS mob, 
              bio_leadtask.assigneddate AS assigneddate,
              bio_leadtask.duedate AS duedate,
              bio_leads.leadstatus AS leadstatus,
              bio_leads.enqtypeid,
              bio_enquirytypes.enqtypeid AS enqtypeid,
              bio_enquirytypes.enquirytype AS enqtype, 
              bio_leadteams.teamname AS teamname,
              bio_status.biostatus,
              bio_district.district,
              bio_leadtask.tid,
              bio_leadtask.taskid
         FROM bio_cust
         LEFT JOIN bio_leads on bio_cust.cust_id=bio_leads.cust_id 
         LEFT JOIN bio_district ON bio_district.cid=bio_cust.nationality 
                    AND bio_district.stateid=bio_cust.state
                    AND bio_district.did=bio_cust.district
         Left join bio_leadtask on bio_leadtask.leadid=bio_leads.leadid
	     Left join bio_leadsources on bio_leadsources.id=bio_leads.sourceid      
         LEft join bio_task on bio_task.taskid=bio_leadtask.taskid
         LEft join bio_leadteams on bio_leadteams.teamid=bio_leadtask.teamid 
         Left join bio_enquirytypes on bio_enquirytypes.enqtypeid=bio_leads.enqtypeid 
         LEft join bio_status on bio_status.statusid=bio_leads.leadstatus
where
  bio_leadtask.teamid IN ($team_array)         
                    AND bio_leads.leadstatus NOT IN(20)
                    AND bio_leadtask.viewstatus=1
                    AND bio_leadtask.taskcompletedstatus=0         
          ";
 
   $Currentdate=FormatDateForSQL(Date("d/m/Y"));
   

 if(isset($_POST['filterbut1']))
 {  
    $officeid=$_POST['off1'];
  //  echo $officeid;
  
    if (isset($_POST['Leaddate']))    {        
        if ($_POST['Leaddate']!='')      {  
            if ($_POST['Leaddate']==1)      {
        
                $sql.=" AND bio_leads.leaddate = '".$Currentdate."'";
        
            }
            
            if ($_POST['Leaddate']==2) {
        
                $date=explode("-",$Currentdate);

                $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
                $Yesterday1 = strtotime($startdate2 . " -1 day");
                $Yesterday=date("d/m/Y",$Yesterday1);

                $Yesterday2=FormatDateForSQL($Yesterday);    
        
                $sql.=" AND bio_leads.leaddate = '".$Yesterday2."'";
        
            }
            if ($_POST['Leaddate']==3) {
                
                $date=explode("-",$Currentdate);

                $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
                $lastweek1 = strtotime($startdate2 . " -7 day");
                $lastweek=date("d/m/Y",$lastweek1);

                $lastweek2=FormatDateForSQL($lastweek);
                
        
                $sql.=" AND bio_leads.leaddate BETWEEN '$lastweek2' AND '$Currentdate'";
        
            }
            if ($_POST['Leaddate']==4) {
            
            }   
        }
    }                                                                   
  
  
  
    if (isset($_POST['byname1']))  {        
        if ($_POST['byname1']!='')   
             $name2=removeCharacters($_POST['byname1']);      
    $sql.= " AND bio_cust.custname LIKE '%".$_POST['byname1']."%'";
/*    OR bio_cust.custname SOUNDS LIKE '%".$_POST['byname1']."%'
    OR bio_cust.custname LIKE '%".$name2."%')";   */

    } 
     if (isset($_POST['phonenum1'])) {
        if ($_POST['phonenum1']!='') 
            $sql .= " AND bio_cust.custmob like '%".$_POST['phonenum1']."%'"; 
    }
    
    if (isset($_POST['byplace1'])) {
        if ($_POST['byplace1']!='') 
            $sql .= " AND bio_district.district LIKE '%".$_POST['byplace1']."%'"; 
    }
        
    if (isset($_POST['Taskstatus']))  {        
        if ($_POST['Taskstatus']!='')    {  
            if ($_POST['Taskstatus']==1)    {
                $taskstatusdesc="Unassigned";  
                $sql.=" AND bio_leadtask.leadid  NOT IN (select leadid from bio_leadschedule)";    
            }
            if ($_POST['Taskstatus']==2) {
    
                $taskstatusdesc="Assigned"; 
                $sql.=" AND bio_leadtask.leadid IN (select leadid from bio_leadschedule)";    
        
            }
        }
    } 
    
    if (isset($_POST['Actiondate']))  {        
        if ($_POST['Actiondate']!='')    {  
            if ($_POST['Actiondate']==1)    {
        
                $sql.=" AND bio_leadtask.duedate = '".$Currentdate."'";
        
            }
            if ($_POST['Actiondate']==2) {
        
                $date=explode("-",$Currentdate);

                $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
                $Tommorrow1 = strtotime($startdate2 . " +1 day");
                $Tommorrow=date("d/m/Y",$Tommorrow1);

                $Tommorrow2=FormatDateForSQL($Tommorrow);    
        
                $sql.=" AND bio_leadtask.duedate = '".$Tommorrow2."'";
        
            }
            if ($_POST['Actiondate']==3) {
        
                $date=explode("-",$Currentdate);

                $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
                $Yesterday1 = strtotime($startdate2 . " -1 day");
                $Yesterday=date("d/m/Y",$Yesterday1);

                $Yesterday2=FormatDateForSQL($Yesterday);    
        
                $sql.=" AND bio_leadtask.duedate = '".$Yesterday2."'";
        
            }
            if ($_POST['Actiondate']==4) {
                
                $date=explode("-",$Currentdate);

                $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
                $lastweek1 = strtotime($startdate2 . " -7 day");
                $lastweek=date("d/m/Y",$lastweek1);

                $lastweek2=FormatDateForSQL($lastweek);
                
        
                $sql.=" AND bio_leadtask.duedate BETWEEN '$lastweek2' AND '$Currentdate'";
        
            }
            if ($_POST['Actiondate']==5) {
        
                $sql.=" AND bio_leadtask.duedate < '".$Currentdate."'";
        
            }
            if ($_POST['Actiondate']==6) {
            
            }   
        }
    }                                                                   
    
    if (isset($_POST['off1']))    {     
    
    
    //  if (($_POST['off1']!='')&&($_POST['off1']!='0') && (($_POST['byteam']=='')||($_POST['byteam']=='0')))
        if ($_POST['off1']==0 && $_POST['byteam']==0)            
   
    {
           $sql.=" AND bio_leadtask.teamid in ($team_array)";   
              // $sql.=" AND bio_leadtask.teamid in (Select teamid from bio_leadteams where office_id=".$_POST['off1'].") ";    

        
    }
    else if ($_POST['off1']==0 && $_POST['byteam']!=0)
    {
           $sql.=" AND bio_leadtask.teamid =".$_POST['byteam'];   
    }
          else if ($_POST['off1']!=0 && $_POST['byteam']==0)
                                   {
                                     $sql.=" AND bio_leadtask.teamid in (Select teamid from bio_leadteams where office_id=".$_POST['off1'].")"; 
                                   }
    
    else
    {
                   $sql.=" AND bio_leadtask.teamid =".$_POST['byteam'];   

    }
        }                                       
    /* if (isset($_POST['byteam']))    {
        if (($_POST['byteam']!='')&&($_POST['off1']!='0'))
            $sql .=" AND bio_leadtask.teamid='".$_POST['byteam']."'";    
    }    */
    
    if (isset($_POST['enq']))    {
        if (($_POST['enq']!='')&&($_POST['enq']!='0'))
            $sql .=" AND bio_leads.enqtypeid='".$_POST['enq']."'";    
    }
    if (isset($_POST['statussrc']))    {
        if ($_POST['statussrc']!='')
            $sql .=" AND bio_leads.leadstatus='".$_POST['statussrc']."'";    
    }
/*    if (isset($_POST['leadsrc1'])) {
        if (($_POST['leadsrc1']!='ALL') && ($_POST['leadsrc1']!=0))
            $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc1']."'";
    } */
 }else{
     
     $sql.=" AND bio_leadtask.taskid = 27"; 
   //  $sql.=" AND bio_leadtask.leadid NOT IN ($schedule_arr)";
     $sql.=" AND bio_leads.enqtypeid=2";
     $sql.=" AND bio_leads.leadstatus=0";  
     $_POST['enq']=2;
     $enquirytype="Institution";
     
     $_POST['Taskstatus']=2;
     $taskstatusdesc="Assigned";

     $_POST['Actiondate']=1;
     $actiondatedesc="Today";
 
    //$sql.=" AND bio_leads.leaddate = '".$Currentdate."'"; 
 
    //$_POST['Leaddate']=1;
    $leaddatedesc="Today"; 
     
 }
 $sql .=" GROUP BY leadid ORDER BY leadid DESC";      
$result=DB_query($sql,$db);
$currentDate=Date("d/m/Y");

echo'<div id="leadgrid">';
echo"<fieldset style='width:945px'><legend>Lead Details</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td class='frmdate'>Lead Registered on</td>
         <td class='byname'>Customer Name</td>
         <td class='phonenum'>Phone Number</td>
         <td>District</td>
         <td>Action</td>
         <td class='actionon'>Action On</td>
         </tr>";
         echo"<tr>";
echo'<td><select name="Leaddate" id="leaddate" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="1">Today</option>';
   echo '<option value="2">Yesterday</option>';
   echo '<option value="3">Last Week</option>';
   echo '<option value="4">ALL</option>';
   echo '<option selected='.$_POST['Leaddate'].'>'.$leaddatedesc.'</option>'; 
   echo '</select></td>';
   echo"<td class='byname'><input type='text' name='byname1' id='byname1' style='width:100px'></td>";
   echo"<td class='phonenum'><input type='text' name='phonenum1' id='phonenum1' style='width:100px'></td>";
   echo"<td><input type='text' name='byplace1' id='byplace1' style='width:100px'></td>";   
   echo '<td><select name="Taskstatus" id="taskstatus" style="width:100px" onchange="actionon(this.value)">';     
   echo '<option value="0"></option>';
   echo '<option value="1">Unassigned</option>';
   echo '<option value="2">Assigned</option>';
   echo '<option selected='.$_POST['Taskstatus'].'>'.$taskstatusdesc.'</option>';                                                        echo '</select></td>';
echo '<td class="actionon"><select name="Actiondate" id="actiondate" style="width:100px">';
    echo '<option value=0></option>';
    echo '<option value="1">Today</option>';
    echo '<option value="2">Tommorrow</option>';
    echo '<option value="3">Yesterday</option>';
    echo '<option value="4">Last Week</option>';
    echo '<option value="5">Overdue</option>';
    echo '<option value="6">Pending</option>';
    echo '<option selected='.$_POST['Actiondate'].'>'.$actiondatedesc.'</option>';                                                            
echo '</select></td>';  
echo"</tr>";    
         echo"<tr><td class='byoffice'>Office</td>  
         <td class='byteam'>Team</td>       
         <td class='bycusttype'>Enquiry Type</td>
         <td>Lead Status</td></tr>";


//echo '<td   class="frmdate"><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
//echo '<td  class="todate"><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';




  

echo '<td class="byoffice"><select name="off1" id="off1" onchange="officeteam(this.value)" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_office";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
//  echo "<option value=$row1[id]>$row1[office]</option>";
  
  if ($row1['id']==$_POST['off1'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['id'] . '">'.$row1['office'];
       echo '</option>';
}
echo '</select></td>';
//add team start
echo '<td class="byteam"><select name="byteam" id="byteam" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_leadteams";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
//  echo "<option value=$row1[id]>$row1[office]</option>";
  
  if ($row1['teamid']==$_POST['byteam'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['teamid'] . '">'.$row1['teamname'];
       echo '</option>';
}
echo '</select></td>';
//team end
echo '<td class="bycusttype"><select name="enq" id="enq" style="width:100px" onchange="leadstatus(this.value)">';
echo '<option value=0></option>';   
$sql1="select * from bio_enquirytypes";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
//  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
    if ($row1['enqtypeid']==$_POST['enq'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
       echo '</option>';  
}

echo '</select></td>';
echo '<td><select name="statussrc" id="statussrc" style="width:100px">';
     echo '<option></option>';
    echo '<option value=0>Enquiry Registered</option>';
    echo '<option value=1>Proposal Submitted</option>';
    echo '<option value=6>Sale Order Registered</option>';
    echo '<option value=26>Feasibility Proposal Submitted</option>';
    echo '<option value=10>Feasibility study completed</option>';
    echo '<option value=4>Concept Proposal Submitted</option>';
    echo '<option value=5>DPR Submitted</option>';
    echo '<option value=6>Sale Order Registered</option>';
       echo '</option>';                                                            
echo '</select></td>';  

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='search'></td>";
echo"</tr>";
echo "</table>";
echo "<table style='width:100%'>";
echo "";//echo $_POST['byteam'];


    if($_POST['byteam']!=0){$sqlterm="select teamname from bio_leadteams where teamid=".$_POST['byteam'];
$rst=DB_query($sqlterm,$db);
$row1=DB_fetch_array($rst);
echo "<tr><td width=40%><b>Selected Team:</b>" .$row1['teamname'];} 
echo "</td>";
echo "<td width=23%></td>";
if($_POST['statussrc']!=null){$sqlterm1="select biostatus from bio_status where statusid=".$_POST['statussrc'];
$rst1=DB_query($sqlterm1,$db);
$row2=DB_fetch_array($rst1);
echo "<td width=40% align='right'><b>Lead Status:</b>".$row2['biostatus'];}
echo "</td>";
echo "</tr>";

echo "</table>";
echo "<div style='height:200px;'>"; 
echo"<table style='width:100%'> ";

echo"<tr>
<th width=2%>Sl.No</th>
<th width=18%>Customer Name-<br/>Contact Person</th>
<th width=14%>District</th>
<th width=18%>Customer Phone</th>
<th width=11%>Task</th>";
$ss=$_POST['byteam'];
    if($ss==0)
  {  
echo "<th width=11%>Team</th>";
  }
echo "<th width=8%>Due Date</th>";
$ld=$_POST['statussrc'];
if($ld==null){
echo "<th width=10%>Lead Status</th>";
}
echo "<th width=10%>Assign Task</th>
</tr>";  
echo "</table>";
echo "<div style='height:200px; overflow:scroll;'>"; 
echo"<table style='width:100%'> ";
echo '<tbody>';
echo '<tr>';                                       
$no=0; 
$k=0; 
$j=0;



while($myrow=DB_fetch_array($result))
{
    $no++;
    $j++;
    $leadid=$myrow['leadid'];
    $tid=$myrow['tid'];
    $taskid=$myrow['taskid'];
    $duedate=$myrow['duedate'];
    date("Y-m-d");

    if(date("Y-m-d") > $duedate AND $duedate!='0000-00-00 00:00:00')
    {   
        echo '<tr bgcolor=#EF8BA1>';  
        $datedue=ConvertSQLDate($myrow['duedate']);
    }else{   
        echo '<tr class="OddTableRows">';   
        $datedue=ConvertSQLDate($myrow['duedate']);
    }
      if($myrow['enqtypeid']==1)
      {
          $custnme=$myrow['custname'];
      }
      else if($myrow['enqtypeid']==2)
      {
          $cstnm1=$myrow['custname'];
          $cstnm2=$myrow['contactperson'];
          if($cstnm2=='')
          {
            $custnme=$myrow['custname'];
          }
          else
          {
          $custnme=$myrow['custname']."-<br>".$myrow['contactperson'];
          }
          }
      else
      {
          $custnme=$myrow['custname'];
      }
printf("<td cellpading=2>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>",  
                $no,
                $custnme,
                $myrow['district'],
                $myrow['mob'],            
                $myrow['task']);
                //$sp=$_POST['byteam'];
                if($ss==0){
printf("<td cellpading=2>%s</td>",
                $myrow['teamname']);
                }
printf("<td cellpading=2>%s</td>",
                
                $datedue);
                if($ld==null){
printf("<td cellpading=2>%s</td>",                
                $myrow['biostatus']);            
                }
            
    $sql_1="SELECT COUNT(*) FROM bio_leadschedule,bio_leadtask
            WHERE bio_leadtask.leadid=bio_leadschedule.leadid
            AND bio_leadtask.leadid=".$leadid;
    $result_1=DB_query($sql_1,$db);
    $lead_task=$leadid.",".$taskid;
    
    
    $myrow_1=DB_fetch_array($result_1);
    if($myrow_1[0]>0){
        echo"<td><input type=checkbox id='leadtask".$j."' name='leadtask[]' value='$lead_task' onclick='passid()'>" . _('Reassign') . "</td>";
    }
    else{
       echo"<td><input type=checkbox id='leadtask".$j."' name='leadtask[]' value='$lead_task' onclick='passid()'>" . _('Assign') . "</a></td>"; 
    }
    
    
    
    
   /* 
    if($myrow['leadstatus']!=0){     
             echo"<td><a  style='cursor:pointer;' id='$leadid' onclick=subtask1(this.id,$tid)>" . _('AddSubTask') . "</a></td></tr>";
   }    */
  
}
echo"<input type=hidden name=TaskCount id=taskcount value='$j'>";


echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 
echo"</div>";

?>
<script type="text/javascript">

$(document).ready(function(){   
    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);
         
  $('.actionon').hide();     
        
//      $(".skip").hide();   
<?php if($_SESSION['filters']!=""){ ?>      
var xx = new Array("<?php echo implode(",",$_SESSION['filters']);?>");

var mySplitResult = xx[0].split(",");  

for(var i=0; i<mySplitResult.length;i++)        {

var filter='.'+mySplitResult[i]
//alert(filter);    
$(filter).hide();   
}
<?php } ?>     
});

function actionon(str1){
    if(str1==2){
      $('.actionon').show();  
    }
}



</script>