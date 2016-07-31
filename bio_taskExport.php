<?php
$PageSecurity = 80; 
include('includes/session.inc'); 
$title = _('Task Export');
 
$status=$_GET['status'];  

     $empid=$_SESSION['empid'];
     $employee_arr=array();  
     $team_arr=array();  
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
     
    $sql5="SELECT teamid     
            FROM bio_teammembers
            WHERE empid
            IN ($employee_arr)";
     $result5=DB_query($sql5,$db);
     
     while($row_select=DB_fetch_array($result5))
     {
          $team_arr[]=$row_select['teamid'];
     }
         
     $team_arr=implode(",", $team_arr);   

  $sql3="SELECT bio_task.task,
                  bio_leadteams.teamname,
                  bio_leadtask.assigneddate,
                  bio_leadtask.duedate,
                  bio_leadtask.taskcompleteddate, 
                  bio_leads.enqtypeid,
                  bio_cust.custname,
                  bio_cust.contactperson,
                  bio_cust.custphone,
                  bio_cust.custmob
             FROM bio_leadtask,
                  bio_task,
                  bio_leadteams,
                  bio_leads,
                  bio_cust 
            WHERE 
               bio_task.taskid=bio_leadtask.taskid 
              AND bio_leadtask.leadid=bio_leads.leadid                  
              AND bio_leads.cust_id=bio_cust.cust_id
              AND bio_leadteams.teamid=bio_leadtask.teamid
              AND bio_leadtask.taskid!=0
              AND bio_leadtask.taskcompletedstatus=$status";
                //AND bio_leads.enqtypeid=2


       
include('includes/header.inc'); 
include('includes/sidemenu.php');  

 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">TASK REPORT</font></center>';
    
//==========================================================================  
    
echo "<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table><tr>';
 
echo'<td valign=top>';    
echo"<fieldset style='width:700px;height:150px'; overflow:auto;'>";
echo"<legend><h3>Select Task</h3></legend>";
echo"<table>";

echo"<tr><td>Escalated</td><td>Date From</td><td>Date To</td><td>Enquiry Type</td><td>Task</td><td>Office</td><td>Team</td></tr>";
echo"<tr>";

echo'<td><input type="radio" name="escalated" id="escalated" style="width:60px" onchange="check();"></td>';      

$datefrm=$_GET['datefrm'];
$dateto=$_GET['dateto'];

 
   echo'<div id=date>';//value='.$_GET['datefrm'].' value='.$_GET['dateto'].'
   echo'<td><input type="text" id="datefrm"   class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datefrm" value="'.$datefrm.'" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
   echo'<td><input type="text" id="dateto"  class=date alt='.$_SESSION['DefaultDateFormat']. ' name="dateto"   value="'.$dateto.'" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
   echo'</div>';
      
    echo '<td class="bycusttype"><select name="enq" id="enq" style="width:100px" onchange=taskselection(this.value)>';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$_GET['enq'])
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

    echo '<td id=respectivetask><select name="task" id="task" style="width:100%">';  
    $sql="select * from bio_task";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['taskid']==$_GET['task'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['taskid'] . '">'.$row['task'];
        echo '</option>';
    }
echo'</select></td>';  
         
     
    echo '<td><select name="office" id="office" onchange="officeteam(this.value)" style="width:100%">';   
    echo'<option value=0></option>';
    $sql="SELECT * FROM bio_office";
    $result=DB_query($sql,$db);
    
    while($row=DB_fetch_array($result))
    {
        if ($row['id']==$_GET['office'] )
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['id'] . '">'.$row['office'];
        echo '</option>';
    }
    echo'</select></td>';    
    
    
    echo '<td id=leadteam1><select name="teamname" id="leadteam" style="width:100%">';
    $sql="select * from bio_leadteams";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['teamid']==$_GET['teamname'])
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $row['teamid'].'">'.$row['teamname'];
        echo '</option>';
    }
    echo'</select></td>';    

echo '</tr>';        
echo"</table>";


echo"<table>"; 
echo '<tr>';
echo '<td><a style=cursor:pointer; onclick=passid(1)>' . _('Task Completed') . '</a></td></tr>';  
echo '<tr><td><a style=cursor:pointer; onclick=passid(0)>' . _('Task Pending') . '</a></td>';
echo'</tr>';
echo"</table>";
echo"</fieldset>";     
     

      if($status==1){
          $heading="COMPLETED TASK";
      }elseif($status==0){
          $heading="PENDING TASK";
      }
      
echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">'.$heading.'</font></center>';    
 
echo "<table style='width:90%'><tr><td>";        
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<br><input type="hidden" name="Status" value='.$status.' >';

$title="<b>Task Report:</b>"; 

if($_GET['datefrm']!=NULL){
$title.=' Date from <b><i>'.$_GET['datefrm'].' </i></b>';
}
if($_GET['dateto']!=NULL){
 $title.=' to <b><i>'.$_GET['dateto'].' </i></b>';   
}   
if($_GET['enq']!=0){
       $result=DB_query("SELECT * FROM bio_enquirytypes where enqtypeid=".$_GET['enq'],$db);
       $myrow1=DB_fetch_array($result);
       $title.=' Enquiry Type:<b><i>'.$myrow1['enquirytype'].'</i></b>';
}
if($_GET['task']!=0){
       $result=DB_query("SELECT * FROM bio_task WHERE taskid=".$_GET['task'],$db);
       $myrow1=DB_fetch_array($result);
       $title.=' Task:<b><i>'.$myrow1['task'].'</i></b>';
}
if($_GET['office']!=0){    
       $result=DB_query("SELECT * FROM bio_office WHERE id=".$_GET['office'],$db);
       $myrow1=DB_fetch_array($result);
       $title.=' Office:<b><i>'.$myrow1['office'].'</i></b>';
}
if($_GET['teamname']!=0){    
       $result=DB_query("SELECT * FROM bio_leadteams WHERE teamid=".$_GET['teamname'],$db);
       $myrow1=DB_fetch_array($result);
       $title.=' Team:<b><i>'.$myrow1['teamname'].'</i></b>';
}

echo "<font size='-1'>".$title."</font>";   
echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:1 solid #F0F0F0;width:100%'>";

   

echo "<tr><th style=width:5%><b>Sl.No</b></th>
<th style=width:20%><b>Customer Name</b></th> 
<th style=width:20%><b>Phone number</b></th>    
<th class='task' style=width:20%><b>Task Name</b></th>
<th class='team' style=width:30%><b>Team Name</b></th>
<th style=width:20%><b>Assigned Date</b></th>
<th style=width:20%><b>Due Date</b></th>
<th style=width:20%><b>Completed Date</b></th></tr>";

$Currentdate=FormatDateForSQL(Date("d/m/Y"));   

/*if($_GET['df']==2)
{
    $sql3.=" AND bio_leadtask.taskcompleteddate > bio_leadtask.duedate";   
}  */          
    
/*    if ($_GET['df']!="")  { 
    if($status==0){
      $sql3.=" AND bio_leadtask.duedate < '".$Currentdate."'";  
    }elseif($status==1){
      $sql3.=" AND bio_leadtask.taskcompleteddate > bio_leadtask.duedate";  
    }  
    }      */
        
//        $no=$_GET['df'];
/*        if ($_GET['df']==1)    {     
        $sql3.=" AND bio_leadtask.duedate = '".$Currentdate."'"; 
        }
        if ($_GET['df']==2)    {     
          
        }
        if ($_GET['df']==3)    {     
        
        }

    echo'<input type="hidden" name="df" value='.$no.' >';   */
                                                         //echo$_GET['escalated'];                 
    if($_GET['escalated']==true)    {     
                                        
    if($status==0){
        
      $sql3.=" AND bio_leadtask.duedate < '".$Currentdate."'";  
    }elseif($status==1){
      $sql3.=" AND bio_leadtask.taskcompleteddate > bio_leadtask.duedate";  
    }      
    }
     
    
    if (($_GET['datefrm']!='') && ($_GET['dateto']!=''))  { 
    if ($status==0){
    $sql3 .=" AND bio_leadtask.duedate BETWEEN '".FormatDateForSQL($_GET['datefrm'])."' AND '".FormatDateForSQL($_GET['dateto'])."'";    
    }else{
        
    $datefrm=FormatDateForSQL($_GET['datefrm']);
    $dateto=FormatDateForSQL($_GET['dateto']);        
    $datefrm=$datefrm." ".'00:00:00';    
    $dateto=$dateto." ".'23:59:59';
    
    $sql3 .=" AND bio_leadtask.taskcompleteddate BETWEEN '".$datefrm."' AND '".$dateto."'";    
    }  
    }
   
    if (($_GET['task']!='') && ($_GET['task']!='0'))  {
    $sql3 .=" AND bio_task.taskid='".$_GET['task']."'";
    echo'<input type="hidden" name="task" value='.$_GET['task'].' >';
    }
    
    if (($_GET['enq']!='')&&($_GET['enq']!='0'))  {
    $sql3 .=" AND bio_leads.enqtypeid='".$_GET['enq']."'";    
    }else{
    $sql3 .=" AND bio_leads.enqtypeid=2";    
    }

    if (($_GET['office']!='') && ($_GET['office']!='0'))  {
    $sql3 .=" AND bio_leadteams.office_id='".$_GET['office']."'";
    echo'<input type="hidden" name="off" value='.$_GET['office'].' >'; 
    }

    if (($_GET['teamname']!='') && ($_GET['teamname']!='0'))  {
    $sql3 .=" AND bio_leadtask.teamid='".$_GET['teamname']."'"; 
    echo'<input type="hidden" name="teamname" value='.$_GET['teamname'].' >';    
    }
    else
    {
       $sql3 .=" AND bio_leadtask.teamid IN (".$team_arr.")";
    }
  $sql3 .=" GROUP BY bio_leads.leadid";
   //echo $sql3;              
            
    $result3=DB_query($sql3,$db);
  $count=DB_fetch_row($sql3,$db);
  
    $i=1;
    while($row3=DB_fetch_array($result3))    
    { 
        $curdate = Date("d/m/Y");
        $curdate2=explode("/",$curdate); 
            $curD= $curdate2[0];
            $curM= $curdate2[1];
            $curY= $curdate2[2];
        $curdate2=$curY."/".$curM."/".$curD; 
        $cudat=strtotime($curdate2);  
        
         
    $assigneddate=ConvertSQLDate($row3['assigneddate']);       
    $duedate=ConvertSQLDate($row3['duedate']);
    $completeddate=ConvertSQLDate($row3['taskcompleteddate']);
    
    $duedate2=explode("/",$duedate);
    
    $dueD= $duedate2[0];
    $dueM= $duedate2[1];
    $dueY= $duedate2[2];
    
    $duedate2=$dueY."/".$dueM."/".$dueD;
    
    
    $completeddate2=explode("/",$completeddate);
    
    $comD= $completeddate2[0];
    $comM= $completeddate2[1];
    $comY= $completeddate2[2];
    
    $completeddate2=$comY."/".$comM."/".$comD;
    
    $dudat=strtotime($duedate2);   
    $comdat=strtotime($completeddate2); 
    
    if($row3['enqtypeid']==2){
          if($row3['contactperson']!="" OR $row3['contactperson']!=0){
            $custname=$row3['custname']."<br /> - ".$row3['contactperson']; 
          }else{
            $custname=$row3['custname']; 
          }
      }elseif($row3['enqtypeid']==1){
            $custname=$row3['custname'];
      }
      
    if($row3['custmob']==""){
        $contactno=$row3['custphone'];
    }else{
        $contactno=$row3['custmob'];
    }  
    
    if($status==1){  
    if($comdat > $dudat)      {
        
        $k=1;
    }else {
        
        $k=0;
    }
    } elseif($status==0)  {      
//    echo Date("d/m/Y");echo $dudat;   
    if( $cudat > $dudat )       {                 
               
     $k=1;
     $completeddate ="Pending";    
    }else {
        $k=2; 
        $completeddate ="Pending";  
    }
    }
                if ($k==1)
                {
                    echo '<tr bgcolor="orange">';
                    //$k=0;          
                }
                elseif ($k==2)  {
                    
                    echo '<tr bgcolor="lightgrey">';
                    
                }  elseif($k==0)
                {
                    echo '<tr bgcolor="lightgreen">';
                    //$k=1;     
                }
        echo "<td>$i</td>
              <td>".$custname."</td>
              <td>".$contactno."</td>              
              <td class='task'>".$row3['task']."</td>    
              <td class='team'>".$row3['teamname']."</td>
              <td>".$assigneddate."</td>
              <td>".$duedate."</td>
              <td>".$completeddate."</td>"; 
        echo "</tr>";       
     $i++; 
        
    }  
     $count=$i-1;
    echo  "<b>Number of Tasks :".  $count."</b>";             
echo "</table>";
echo"</div>";
//echo '<br><input type="submit" name="excel" value="View as Excel">';  
echo"</form>";

 echo "<form action='bio_taskReport_excel.php' method=POST>"; 
 $_SESSION[$inc_sql]=$sql3;
 echo '<br /><input type=submit name=excel id=excel value="View as Excel" >';
 echo "</form>";  

echo"</td></tr></table>";

?>

<script type="text/javascript">

var team=document.getElementById('leadteam').value;        // alert(team);
var task=document.getElementById('task').value; 

if(team!=0)
{
    $(".team").hide(); 
}
if(task!=0)
{
    $(".task").hide();    
}

function check()
{

var str=document.getElementById('escalated').checked;
 if(str==true)
 {
//    $('#date').hide();     
   document.getElementById('datefrm').disabled=true;
   document.getElementById('dateto').disabled=true;

 }
 }
 
function officeteam(str)
{
       
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
    document.getElementById("leadteam1").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_taskExport_officeteam.php?officeid=" + str,true);
xmlhttp.send();

}

function taskselection(str)
{
    //document.getElementById("enqid").value=str;  
    //alert(str);
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
    document.getElementById("respectivetask").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_taskExport_officeteam.php?enqid=" + str,true);
xmlhttp.send();
}

function passid(str){
            
 var str1=document.getElementById('escalated').checked;     //alert(str1);      
 if(str1==true)
 {
     str1=1;   //alert(str1);
 }else{
     str1=0;   //alert(str1);
 }
 
 var str2=document.getElementById('task').value;   
 var str3=document.getElementById('office').value; 
 var str4=document.getElementById('leadteam').value;  
 var str5=document.getElementById('enq').value;
 var str6=document.getElementById('datefrm').value; 
 var str7=document.getElementById('dateto').value;  
// var str8=document.getElementById('leaddate').checked;   // alert(str8);
 
window.location="bio_taskExport.php?status=" + str + "&escalated=" + str1 + "&task=" + str2 + "&office=" + str3 + "&teamname=" + str4 + "&enq=" +str5 + "&datefrm=" + str6 + "&dateto=" +str7; 
}

</script>

