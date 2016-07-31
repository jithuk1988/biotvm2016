<?php
      $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('Marketing Task');
include ('includes/header.inc');
echo '<head><link href="menu_assets/styles.css" rel="stylesheet" type="text/css">';

echo'</head>';
include ('includes/SQL_CommonFunctions.inc');
echo "<div id='cssmenu'>
<ul>
     <li > <a href='bio_nwinstallationstatuses.php'><span>Post installation</span></a></li>
   <li class='active '><a href='bio_marketingtask.php'><span>Marketing task</span></a></li>
   <li><a href='bio_warranty_amc.php'><span>AMC / Warrenty</span></a></li>
   <li><a href='bio_paypending.php'><span>Payment pending</span></a></li>
    <li><a href='bio_cdmsurvey.php'><span>CDM survey</span></a></li>
   <li><a href='bio_complaintfollow.php'><span>Complaint followup</span></a></li>
</ul>

</div>";

if(isset($_POST['submit']))
  {
   $crdt=date("Y-m-d H:i:s");
      $leadid=$_POST['leadid'];
      $taskid=$_POST['taskid'];
      $grad=$_POST['grade'];
      $remarks=$_POST['remarks'];
      $status=$_POST['status'];
      $assigned_date=$_POST['date'];
      $assigned_date=FormatDateForSQL($assigned_date);          
      $tim= $_POST['tim'];
      $sql_remark="SELECT remarks FROM bio_leads WHERE leadid=$leadid"; 
      $result_remark=DB_query($sql_remark,$db);
      $row=DB_fetch_array($result_remark);
      $rem1=$row['remarks'];
      $rem1=str_replace("'","-",$rem1); 
      $remark=$rem1."\r\n".date("Y-m-d").":".$remarks;
        $sql_tm='UPDATE bio_leads SET pref_time="'.$tim.'" WHERE leadid='.$leadid;   DB_query($sql_tm,$db);
       $sql_append='UPDATE bio_leads SET remarks="'.$remark.'",grade="'.$grad.'"  WHERE leadid='.$leadid;   DB_query($sql_append,$db);
       $sql_remark='UPDATE bio_leadtask SET remarks="'.$remarks.'" WHERE leadid='.$leadid.' AND taskid='.$taskid;   DB_query($sql_remark,$db);
      
      if($status==1)
      {
          $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=$taskid ";      DB_query($sql1,$db);       
      }
      elseif($status==2)
      { 
          $sql2="UPDATE bio_leadtask SET taskcompletedstatus=2 WHERE leadid=$leadid";    DB_query($sql2,$db);     
          $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=$taskid ";     DB_query($sql1,$db);          
          $sql3="UPDATE bio_leads SET leadstatus=20 WHERE leadid=$leadid";         DB_query($sql3,$db);          
      }
      elseif($status==3)
      {     
          $sql2="UPDATE bio_leadtask SET taskcompletedstatus=2 WHERE leadid=$leadid";    DB_query($sql2,$db); 
          $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=$taskid ";     DB_query($sql1,$db);
       
          
    $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=18
 ORDER BY schedule_id ASC";  
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
  }
  
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">MARKETING</font></center>';
     echo'<table width=98% ><tr><td>';  
      echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>";  
    //  if($leadid)    
 
 $a=0;
        echo '<table class="selection" style="width:70%;">'; 
        
          echo '<th colspan="2"><b><font size="+1" color="#000000">CUSTOMER DETAILS</font></b></th>' ; 
          
          echo '<tr><td align="left" id=first style="width:470px">';
           
          echo"<fieldset style='width:440px;height:280px '>"; 
          echo"<legend>Customer Details</legend>";
              $leadid=$_GET['leadid1'];  
          /* $sql="SELECT bio_leads.leadid, 
                    bio_cust.custname,
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
                 //$amount=$myrow['amount']; 
                 $createdby=$myrow['empname']; 
                 $remarks=$myrow['remarks'];          */
           echo"<table  width=100%>";
            /*echo "<tr><td><b>Customer name</b></td><td><b>:</b></td><td><b>".$cname."</b></td></tr>";
            echo "<tr><td><b>Customer contact</b></td><td><b>:</b></td><td><b>".$cph."</b></td></tr>";     
            echo "<tr><td><b>Customer email</b></td><td><b>:</b></td><td><b>".$email."</b></td></tr>";  
            echo "<tr><td><b>Customer place</b></td><td><b>:</b></td><td><b>".$place."</b></td></tr>";
            echo "<tr><td><b>Customer district</b></td><td><b>:</b></td><td><b>".$district."</b></td></tr>";   
            echo "<tr><td><b>Lead source[team]</b></td><td><b>:</b></td><td><b>".$sourcename."".$team."</b></td></tr>";
    
            echo "<tr><td><b>Created by</b></td><td><b>:</b></td><td><b>".$createdby."</b></td></tr>";   
            echo "<tr><td><b>Preferred time</b></td><td><b>:</b></td><td><b> </b></td></tr>";   
            echo "<tr><td><b>Remarks</b></td><td><b>:</b></td><td><b><textarea name='' cols='30'  rows='3' readonly='readonly' >".$remarks."</textarea></b></td></tr>";   */ 
         echo'</table>';
         echo '</fieldset>';
        echo '<td>';  
         echo"<fieldset style='width:440px;height:280px'>"; 
         echo"<legend>Call Details</legend>";
         echo"<br />";                                                            
          echo"<table width=100% id='cal_lgtb'>"; 
          /* echo ' <tr> <td>Grade</td>';
           echo '<td>
           
            <table width="100%" border="0">
             <tr>
             <td width="5" bgcolor="#339900"><label>
             <input type="radio" name="grade" id="grade" value="A" />
             A</label></td>
             <td width="5" bgcolor=orange><label>
             <input type="radio" name="grade" id="grade" value="B" />
             B</label></td>
             <td width="5" bgcolor="#0099CC"><label>
             <input type="radio" name="grade" id="grade" value="C" />
             C</label></td>
             <td width="5" bgcolor="#CCFF33"><label>
             <input type="radio" name="grade" id="grade" value="D" />
              D</label>
              <td width="5" bgcolor=Pink><label>
              <input type="radio" name="grade" id="grade" value="E" />
              E</label></td>
              </tr>
             </table>';
           echo  '</td>  </tr>'; 
              
              echo '<td >Select Action</td>';
              echo '<td colspan="5"><select name="status" id="status" style="width:245px" onchange="selection()">';
              echo '<option value="0"></option>';
              echo '<option value="1">Proceed to next task</option>';
              echo '<option value="2">Delete enquiry and Cancel all tasks</option>';
              echo '<option value="3">Add new Contact date</option>'; 
              echo '</select></td></tr>';
              echo"<tr id='newTask'><td>Next Contact Date</td>";
              echo'<td colspan="5"><input type="text" style="width:240px" id="date" class=date alt='.$_SESSION['DefaultDateFormat'].' name="date" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';
              echo"<tr><td>Remarks:</td>"; 
              echo"<td colspan'5'><textarea rows=2 cols=35 name=remarks id=remarks style=resize:none></textarea></td></tr>";     
              echo '<tr><td>Current preferred time</td><td><input type="text"></td></tr>';
              echo '<tr><td>Next preferred time</td><td><input type="text"></td></tr>';
              echo  '<tr><td></td><td><input type="submit"> </td></tr>'  ;
   */  
      echo"<table width=100%>";            
   echo ' <tr> <td>Grade</td><td>';
 echo '<table width="100%" border="0">';
   echo     '<tr>
    <td width="5" bgcolor="#339900"><label>
      <input type="radio" name="grade" id="grade" value="A" />
    A</label></td>
    <td width="5" bgcolor=orange><label>
      <input type="radio" name="grade" id="grade" value="B" />
    B</label></td>
    <td width="5" bgcolor="#0099CC"><label>
      <input type="radio" name="grade" id="grade" value="C" />
    C</label></td>
    <td width="5" bgcolor="#CCFF33"><label>
      <input type="radio" name="grade" id="grade" value="D" />
    D</label>
      <td width="5" bgcolor=Pink><label>
     <input type="radio" name="grade" id="grade" value="E" />
    E</label></td>
 </tr>
</table>

                  </td>  </tr>'; 
                  
              echo '<td >Select Action</td>';
              echo '<td colspan="5"><select name="status" id="status" style="width:245px" onchange="selection()">';
              echo '<option value="0"></option>';
              echo '<option value="1">Proceed to next task</option>';
              echo '<option value="2">Delete enquiry and Cancel all tasks</option>';
              echo '<option value="3">Add new Contact date</option>'; 
               echo '</select></td></tr>';
        
     
     echo'<tr id="newTask"><td>Next Contact Date</td>';
   echo'<td colspan="5"><input type="text" style="width:240px" id="date" class=date alt='.$_SESSION['DefaultDateFormat'].' name="date" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';
           echo"<tr id='pref'><td>New Prefered Time:</td>";
     echo'<td colspan="5"><input type="text" style="width:240px" id="tim" name="tim" ></td></tr>';
   
     echo"<tr><td>Remarks:</td>"; 
     echo"<td colspan'5'><textarea rows=2 cols=35 name=remarks id=remarks style=resize:none; value='$remarks' onchange=updateDetails('$cid','$remarks')>$remarks</textarea></td></tr>";     
           
    echo"<tr><td></td><td align=center colspan='5'><input type='submit' name='submit' id='submit' value='Submit' onclick='if(validation()==1)return false;;'></td>";
    echo"</tr></table>"; 
     echo"</fieldset>";
    echo"</td></tr>";
if($mail_msg!=''){
echo"<tr>"; 
echo"</td>";
echo"<td align=center><a href='#' id='".$leadid."' onclick='sendMail(this.id)'>Send Mail</a></td></tr>";   
echo"</td>";
echo"</tr>";   
          }
               
       echo '</table>' ;
           echo'</div>';
         
       echo"<div id=grid>";
       echo"<fieldset style='width:90%;'>";
       echo"<legend><h3></h3></legend>";
        echo"<table style='width:100%;'><tr><td>Name:</td><td><input type='text' name='custname'></td>";  
         echo"<td>Phone No</td><td><input type=text name=phone></td>";
        echo"<td>From Date:</td><td><input type='text' name='fr_date' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
        echo"<td>To Date:</td><td><input type='text' name='to_date' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
        echo"<td>Lead id.:</td><td><input type='text' name='leadid'></td>"; 
        echo"<td><input type='submit' name='view' id='submit' value=search></td></tr></table></div>";
        echo"<div id='close'>"; 
    
    
        echo '<center><font style="color: #333;
                           
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">MARKETING TASKS</font></center>';   
                          
 
      echo "<table style='width:90%' ><tr><td>";     
       echo "<div style='height:400px; overflow:auto;'>";
        echo "<table>";
          echo "<tr><th><b>SL No</b></th>
          <th><b>Name</b></th> 
          <th><b>CONTACT NUMBER</b></th> 
          <th><b>ADDRESS</b></th>    
          <th><b>DUE DATE</b></th>
          <th><b>SELECT</b></th></tr>"; 
/*
<th id='hd' class='thl'><b>DUE DATE3</b></th>style='border:1 solid #000000;width:100%'
<th  id='hd' class='thl'><b>ACTUAL DATE3</b></th>
<th  id='hd' class='thl'><b>PLANT STATUS</b></th>
<th  id='hd' class='thl'><b>CLOSE STATUS</b></th></th><th id='hd' class='thl'><b>ORDER No</b></th>
<th  id='hd' class='thl'><b>REMARKS</b></th>" */ 
 $SQL="SELECT bio_cust.custname, concat( bio_cust.custphone, '<br>', bio_cust.custmob ) AS 'Contno', bio_cust.area1, date_format( bio_leads.leaddate, '%d-%m-%Y' ) , 
date_format( bio_leadtask.duedate, '%d-%m-%Y' ) , bio_leads.leadid ,bio_cust.custphone,bio_cust.custmob
FROM bio_cust
INNER JOIN bio_leads ON bio_cust.cust_id = bio_leads.cust_id
INNER JOIN bio_leadtask ON bio_leads.leadid = bio_leadtask.leadid
WHERE bio_leadtask.taskid =18
AND bio_leadtask.taskcompletedstatus =0
AND bio_leadtask.viewstatus =1
AND bio_leads.leadstatus !=20
AND bio_leads.enqtypeid =1
AND bio_leadtask.duedate <= curdate( )";
if($_POST['view'])
              {
                   if($_POST['custname']!=NULL) {
                     $SQL.=" AND bio_cust.custname LIKE '".$_POST['custname']."%'";   
                  }
                  if($_POST['phone']!=NULL) 
                  {
              
                          $SQL.=" AND bio_cust.custphone LIKE '".$_POST['phone']."%' or bio_cust.custmob  LIKE '".$_POST['phone']."%'";                 
                  } 
                  
                  if($_POST['fr_date']!=NULL and $_POST['to_date']!=NULL) {
                     /*$sql_selall.=" AND bio_installation_status.brname BETWEEN '".$_POST['fr_date']."' AND '".$_POST['to_date']."'";  */ 
                      $SQL.=" AND bio_leadtask.duedate BETWEEN '".FormatDateForSQL($_POST['fr_date'])."' AND '".FormatDateForSQL($_POST['to_date'])."'";
                  }     
                 
                  if($_POST['leadid']!=NULL) {
                    $SQL.=" AND bio_leads.leadid LIKE '".$_POST['leadid']."%'"; 
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }
              }
$result=DB_query($SQL,$db);
$k=0;
$i=0;
   while($row=DB_fetch_array($result))
   {    $leadid=$row[5];
             if ($k==1)
             {
               echo '<tr class="EvenTableRows">';
                $k=0;
             }else 
              {
               echo '<tr class="OddTableRows">';
               $k=1;     
               }
       echo '<td>'.$i.'</td>';
       echo '<td>'.$row[0].'</td>';
       echo '<td>'.$row[1].'</td>';                                          
       echo '<td>'.$row[2].'</td>';
       echo '<td>'.$row[4].'</td>';
       echo '<input type="hidden" name=lead id=lead value='.$leadid.'>';
    echo"<td><input type='button'  value='select' onclick='dlt($leadid )'> </td></tr>";          
       echo'</tr>';                                            
       $i++;                             //             
   }                                                        
   echo '</table>';
?>
<script> function dlt(str){
 alert(str);
   // location.href="?leadid1="+str;    
  if (str=="")
  {
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("first").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_marketingajax.php?leadid1="+str,true); //alert(str);
xmlhttp.send();        
  
}  
function validation()
{
    var f=0;
    var p=0;
    var status=document.getElementById('status').value;
    if(status==0)
    {
    if(f==0){f=common_error('status','Please select an Action');  if(f==1){return f; }  } 
    }
    if(f==0){f=common_error('remarks','Please enter any remarks');  if(f==1){return f; }  }
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

  function sendMail(str){//alert(str);

controlWindow=window.open("bio_sendmail.php?leadid="+str,"sendmail","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1300,height=650");


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
</script>