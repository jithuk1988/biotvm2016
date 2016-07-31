<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $title = _('Domestic Task View');  
    
       $leadid=$_GET['leadid'];           
       $sql="SELECT bio_leads.leadid, 
                    bio_cust.custname,
                    bio_cust.contactperson,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_district.district,
                    bio_leadteams.teamname,
                    bio_leadsources.sourcename,
                    bio_emp.empname,
                    bio_cust.cust_id,
                    bio_leads.enqtypeid,
                    bio_leads.remarks,
                    SUM( bio_advance.amount) AS amount 
              FROM  bio_leads,bio_cust,bio_leadteams,bio_leadsources,bio_district,bio_emp,www_users,bio_advance
              WHERE bio_leads.cust_id=bio_cust.cust_id
                AND bio_leadteams.teamid=bio_leads.teamid
                AND bio_district.did=bio_cust.district
                AND bio_district.stateid=bio_cust.state
                AND bio_district.cid=bio_cust.nationality
                AND bio_leadsources.id=bio_leads.sourceid
                AND bio_leads.leadid=bio_advance.leadid
                AND bio_leads.created_by=www_users.userid
                AND www_users.empid=bio_emp.empid
                AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result); 
 
 $cid=$myrow['cust_id'];
 $cname=$myrow['custname']; 
 $cperson=$myrow['contactperson'];
 $enqid=$myrow['enqtypeid'];  
 if($myrow['custmob']!=''){
     $cph=$myrow['custmob']; 
 }else{
     $cph=$myrow['custphone']; 
 }
 $email=$myrow['custmail'];  
 $place=$myrow['area1'];
 $district=$myrow['district'];
 $team=$myrow['teamname']; 
 $sourcename=$myrow['sourcename']; 
 $amount=$myrow['amount']; 
 $createdby=$myrow['empname']; 
 $remarks=$myrow['remarks'];  
    
echo"<table width=70%><tr><td>";
echo"<fieldset style='width:600px'><legend>Pending Tasks</legend>";
echo"<table width=100%>"; 
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname' onchange=updateDetails('$cid','$cname')>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td width=50%>Contact Person:</td>";
echo"<td><input type='hidden' name='contactPerson' id='contactPerson' value='$cperson' onchange=updateDetails('$cid','$cperson')>$cperson</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Contact:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place:</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place' onchange=updateDetails('$cid','$place')>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district' onchange=updateDetails('$cid','$district')>$district</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source Team:</td>";
echo"<td><input type='hidden' name='team' id='team' value='$team'>$team</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source:</td>";
echo"<td><input type='hidden' name='source' id='source' value='$sourcename'>$sourcename</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Total Amount Paid:</td>";
echo"<td><input type='hidden' name='amount' id='amount' value='$amount'>$amount</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Remarks:</td>"; 
echo"<td><textarea rows=2 cols=30 name=remarks id=remarks style=resize:none; value='$remarks' onchange=updateDetails('$cid','$remarks')>$remarks</textarea></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Created By:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$createdby'>$createdby</td></tr>";  
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
echo"<input type='hidden' name='custid' id='custid' value='$cid'>";
echo"<input type='hidden' name='enqid' id='enqid' value='$enqid'>";

echo'</div>';
//echo"</td></tr>";  
//echo"</table>";
  
$date=date("Y-m-d"); 
echo"<tr><td>";             
//echo'<div id="leadgrid">'; 
echo"<fieldset style='width:800px'><legend>Pending Tasks</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
//echo"<table style='width:100%'> "; 
echo"<tr><th>Task</th>
         <th>Assigned Date</th>
         <th>Due Date</th>
         <th>Status</th> 
    </tr>";  
  
  $sql="SELECT bio_cust.cust_id,
               bio_leads.enqtypeid,
               bio_leadtask.leadid,
               bio_task.task,
               bio_leadtask.taskid, 
               bio_leadtask.assigneddate,
               bio_leadtask.duedate,
               bio_leadtask.taskcompletedstatus,
               bio_leadtask.tid
          FROM bio_leads,bio_leadtask,bio_cust,bio_task 
         WHERE bio_leadtask.leadid=$leadid
           AND bio_leads.leadid=bio_leadtask.leadid
           AND bio_leads.cust_id=bio_cust.cust_id
           AND bio_task.taskid=bio_leadtask.taskid 
           AND bio_leadtask.taskcompletedstatus!=2 AND bio_leadtask.taskcompletedstatus!=3 
           ORDER BY bio_leadtask.duedate ASC           
           ";                                                     
  $result=DB_query($sql,$db);
  $no=1;
  $k=0;           
     
  while($row=DB_fetch_array($result)) 
  {
     $tid=$row['tid']; 
     $custid=$row['cust_id'];
     $enqid=$row['enqtypeid']; 
     $taskid=$row['taskid']; 
     $taskcompletedstatus=$row['taskcompletedstatus'];
     $assignDate=ConvertSQLDate($row['assigneddate']);
     $duedate=ConvertSQLDate($row['duedate']);

          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
             $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
             $k=1;     
          }
              if($taskcompletedstatus==1 && ($taskid==28 OR $taskid==29 OR $taskid==26 OR $taskid==2)){ 
              echo"<td><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$tid,$enqid)'>".$row['task']."</a></td>";    
              echo"<td><input type='text' name='assignd' id='assignd'readonly value='$assignDate' onchange=updateDate(this.value,'$taskid','$leadid',1)></td>   
                   <td><input type='text' name='dued' id='dued' value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>";           
              echo"<td>Done</td>";
              //echo"<td><a  style='cursor:pointer;' id='$leadid' onclick=subtask1(this.id,$tid)>" . _('AddSubTask') . "</a></td>";
//          
//              echo"<td><a  style='cursor:pointer;' id='$leadid' onclick=viewsubtasks($tid)>" . _('ViewSubTask') . "</a></td></tr>";       
//          
              } elseif($taskcompletedstatus==0){
              echo"<td><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$tid,$enqid)'>".$row['task']."</a></td>";    
              echo"<td><input type='text' name='assignd' id='assignd' readonly value='$assignDate' onchange=updateDate(this.value,'$taskid','$leadid',1)></td>   
                   <td><input type='text' name='dued' id='dued' value='".$duedate."' onchange=updateDate(this.value,'$taskid','$leadid',2)></td>";          
              echo"<td>Pending</td>";
                  
             // echo"<td><a  style='cursor:pointer;' id='$leadid' onclick=subtask1(this.id,$tid)>" . _('AddSubTask') . "</a></td>";
          
             // echo"<td><a  style='cursor:pointer;' id='$leadid' onclick=viewsubtasks($tid)>" . _('ViewSubTask') . "</a></td></tr>";    
              }
                           
        echo"</tr>";        
     $no++;
  }

          
echo"</table>";
echo"</fieldset>";
   
echo'<div class="centre">';   
echo "<input type=\"button\" value=\"Refresh this page\" onClick='window.location=\"bio_instTaskview.php\";'>";
  echo"<input type='submit' name='date' id='date' value='Edit Date' onclick='compareDate($leadid)'>";
echo'</div>';

//echo"</div>"; 

echo'</form>';
echo"</div>";  
echo"</table></td></tr>"; 


 ?>

 <script type="text/javascript"> 
 
function updateDetails(str){
   
var str1=document.getElementById('custplace').value;                       
var str2=document.getElementById('custdist').value;       
var str3=document.getElementById('remarks').value;
var str4=document.getElementById('leadid').value; 
var str5=document.getElementById('custname').value;    
 //alert(str4);       alert(str3);
 //alert(str1);       alert(str2);      
if (str1=="")
  {
  document.getElementById("msg").innerHTML="";
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
    document.getElementById("msg").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_updateDetails.php?custid=" + str + "&place=" + str1 + "&dist=" + str2 + "&remarks=" + str3 + "&leadid=" + str4 + "&name=" + str5 ,true);
xmlhttp.send(); 
}

function updateDate(str1,str2,str3,str4){
 //   alert("tttttttt");
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


</script>


