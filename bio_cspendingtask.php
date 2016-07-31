<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Pending Tasks');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Pending Task')
	. '" alt="" />' . _('Pending Task') . '</p>';
    if( $_POST['submit'] ){
        $sql_getorder="SELECT cstype,orderno,despatch_id FROM bio_cstask WHERE id=".$_POST['taskid']." ";                       //             orderno     
        $result_order=DB_query($sql_getorder,$db);
        $roworder=DB_fetch_array($result_order);  // echo $roworder['orderno'];
        
        $sql_update="UPDATE bio_cstask SET status=1 , completeddate='".FormatDateForSQL($_POST['completeddate'])."' WHERE orderno=".$roworder['orderno']." AND cstype=".$roworder['cstype']." 
         AND despatch_id=".$roworder['despatch_id']." AND id=".$_POST['taskid']."";
        $result_update=DB_query($sql_update,$db);  //$sql_completecheck
        echo"<div class=success>Updated the task as completed successfully</div>";
        
        // Task tarnsaction entry
         $user=$_SESSION['UserID'];
  date_default_timezone_set ("Asia/Calcutta"); 
   $curdate = date("Y-m-d  H:i:s", time()) ;
   $insert_task_trans="INSERT INTO bio_task_transaction (task_id,modified,date,user) VALUES ('".$_POST['taskid']."',1,'".$curdate."','".$user."') ";
   $result_task_trans=DB_query($insert_task_trans,$db); 
        
         $sql_completecheck="SELECT count(id) as count from bio_cstask WHERE orderno=".$roworder['orderno']." AND cstype=".$roworder['cstype']." AND status=0 
         AND despatch_id=".$roworder['despatch_id']."";
         $result_completecheck=DB_query($sql_completecheck,$db);
         $row_completed=DB_fetch_array($result_completecheck); //echo $row_completed['count'];
         if($row_completed['count']==0){ 
             if($roworder['cstype']==1){
       $sql_update_compl="UPDATE bio_installationstatus SET installation_date='".FormatDateForSQL($_POST['completeddate'])."' 
      WHERE orderno=".$roworder['orderno']."  AND despatch_id=".$roworder['despatch_id']."";
 $result_update_compl=DB_query($sql_update_compl,$db);
        
            $duedate = strtotime ('+3 day' , strtotime ( $_POST['completeddate']));
                                    $duedate = date ('Y-m-d',$duedate ); 
                  echo   $sql_followup="INSERT INTO bio_installation_status (orderno,installed_date,due_date1) VALUES (".$roworder['orderno'].",'".FormatDateForSQL($_POST['completeddate'])."','".$duedate."')";
                      $result_followup=DB_query($sql_followup,$db);
        
        
             echo"<div class=success>All installation tasks completed. Installation Date set as '".FormatDateForSQL($_POST['completeddate'])."'. Please assign warranty tasks</div>";
             }
                          elseif($roworder['cstype']==2){
                              //$sql_update_compl="UPDATE bio_installationstatus SET installation_date='".$_POST['completeddate']."' WHERE orderno=".$roworder['orderno']."  AND despatch_id=".$roworder['despatch_id']."";  
             echo"<div class=success>All warranty tasks completed. </div>";
             }
                                       elseif($roworder['cstype']==3){
                              //$sql_update_compl="UPDATE bio_installationstatus SET installation_date='".$_POST['completeddate']."' WHERE orderno=".$roworder['orderno']."  AND despatch_id=".$roworder['despatch_id']."";  
             echo"<div class=success>All AMC tasks completed. </div>";
             }
                                       elseif($roworder['cstype']==4){
                              //$sql_update_compl="UPDATE bio_installationstatus SET installation_date='".$_POST['completeddate']."' WHERE orderno=".$roworder['orderno']."  AND despatch_id=".$roworder['despatch_id']."";  
             echo"<div class=success>Complaint rectified successfully. </div>";
             }
              
        }  
        
/*         $sql_getunset="SELECT count(id) as count from bio_cstask WHERE orderno=".$roworder['orderno']." AND cstype=".$roworder['cstype']." AND team=0";
        $result_unset=DB_query($sql_getunset,$db);
         $row_unset=DB_fetch_array($result_unset);
         if($row_unset['count']!=0)
         {  echo"<div class=warn>Some task not assigned teams. Those task do not list here</div>";    
             
         }   */
    }
   
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
//echo $plant;
//echo $prev_task;
//echo $planttype;

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

 echo"<table width='1000px'><tr><td>";
 echo"<fieldset><legend></legend>";
 
 
  echo"<table>";
  echo"<tr><td>Type :<select name=type>";
    echo"<option></option>"; 
  echo"<option value=1>Installation</option>";
  echo"<option value=2>Warranyt</option>"; 
  echo"<option value=3>AMC</option>"; 
  echo"<option value=4>Complaints</option>";
  echo"</select></td>";
  echo"<td>Name :<input type=text name=custname></td>";  
            echo"<td>Date From :<input type=text name=frmdate class=date alt=' ". $_SESSION['DefaultDateFormat'] ."'></td>"; 
            echo"<td>Date To :<input type=text name=todate class=date alt=' ". $_SESSION['DefaultDateFormat'] ."'>"; 
            echo"<td><input type=submit name=search value=search></td></tr>";    
      echo"</table>";
      echo"</fieldset>";
    
 echo"<table width='1000px'><tr><td>";
 echo"<fieldset><legend>Pending Task List</legend>";

 echo "<div style='height:300px; overflow:scroll;'>";
 echo"<table width='1000px'>";  
 echo"<tr><th>Slno</th><th>Order No./Ticket No.</th><th>Date</th><th>Name</th><th>Type</th><th>Contact No.</th><th>Task</th><th>District</th><th>Team</th></tr>";  
  $sql="SELECT bio_cstask.id,
  bio_cstask.orderno,bio_cstask.ticketno,
  bio_cstask.cstype,
  bio_cstask.taskdescription,
  bio_cstask.scheduleddate,
  bio_leadteams.teamname
  FROM bio_cstask,bio_leadteams
   WHERE  bio_leadteams.teamid=bio_cstask.team
    AND bio_cstask.status=0
     AND bio_cstask.team IN  (".$team_array.")";
     if(isset($_POST['search']))
 {                                                                  //  AND stockmaster.stockid = bio_cstask.stockcode    stockmaster.description     AND bio_district.did=custbranch.did AND  bio_district.stateid=custbranch.stateid AND bio_district.cid=custbranch.cid   
    if($_POST['frmdate']!="" && $_POST['todate']!="") 
    {
         $sql.=" AND bio_cstask.scheduleddate BETWEEN '".FormatDateForSQL($_POST['frmdate'])."' AND '".FormatDateForSQL($_POST['todate'])."'";     
    }   
    
    if($_POST['custname']!="") 
    {
         $sql.=" AND custbranch.brname LIKE '%".$_POST['custname']."%'";    
    }  
    
    if($_POST['type']!="") 
    {
         $sql.=" AND bio_cstask.cstype LIKE '%".$_POST['type']."%'";    
    }  

 }
 
          $sql.=" ORDER BY bio_cstask.orderno,bio_cstask.taskno
          ";   // //  AND bio_cstask.scheduleddate >= 'date_add(bio_cstask.scheduleddate, INTERVAL 30 days)' 
                      // echo $sql;     
                  /*    custbranch.brname,
  custbranch.did,custbranch.stateid, custbranch.cid,  
  custbranch.phoneno,
  custbranch.faxno,bio_district.district  */
    /* bio_cstask.orderno=salesorders.orderno     
   AND salesorders.debtorno=custbranch.debtorno      */
                
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
                    $k=1;     
    }  //echo  "<td>".$myrow['orderno']."</td></tr>";
    if($myrow['orderno']==0){
        $get_details="SELECT bio_incident_cust.custname,bio_incident_cust.custphone,bio_district.district
        FROM  bio_incident_cust,bio_district,bio_incidents 
        WHERE bio_district.cid=bio_incident_cust.nationality  AND   bio_district.stateid=bio_incident_cust.state AND    bio_district.did=bio_incident_cust.district     
        AND bio_incident_cust.cust_id=bio_incidents.cust_id AND bio_incidents.ticketno='".$myrow['ticketno']."' ";
        $rsl_details=DB_query($get_details,$db);
        $row_details=DB_fetch_array($rsl_details);
        $name=$row_details['custname'];
        $contactno=$row_details['custphone'];
        $district=$row_details['district']; 
            $ord_tkt=$myrow['ticketno']."(TN)";
    }else{
                   $get_details="SELECT  custbranch.brname,custbranch.phoneno,custbranch.faxno,bio_district.district FROM  custbranch,bio_district,salesorders 
        WHERE bio_district.cid=custbranch.cid  AND   bio_district.stateid=custbranch.stateid AND    bio_district.did=custbranch.did     
        AND custbranch.debtorno=salesorders.debtorno AND salesorders.orderno='".$myrow['orderno']."' ";
        $rsl_details=DB_query($get_details,$db);
        $row_details=DB_fetch_array($rsl_details);
        $name=$row_details['brname'];
        $contactno=$row_details['phoneno']."</br>".$row_details['faxno'];
        $district=$row_details['district'];
        $ord_tkt=$myrow['orderno']."(ON)";
    }
    
    
    if($myrow['cstype']==1){$ctype="Installation"; }
    elseif($myrow['cstype']==2){$ctype="Warranty"; }
    elseif($myrow['cstype']==3){$ctype="AMC"; }
    elseif($myrow['cstype']==4){$ctype="Complaint"; }
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td> 
            <td>%s</td> 
            <td>%s</td> 
            <td>%s</td>  
            <td>%s</td>  
            <td>%s</td>",
            $no,
            $ord_tkt,
            ConvertSQLDate($myrow['scheduleddate']),
            $name,
            $ctype,
            $contactno,   
            $myrow['taskdescription'],
            $district,
            $myrow['teamname']);
            echo"<td><a style='cursor:pointer;' id='".$myrow['id']."' onclick='updatetask(this.id,".$myrow['cstype'].")'>" . _('Select') . "</a></td></tr>";
   }                   
                        // $myrow['description'],          $myrow['phoneno']."<br>".$myrow['faxno'],          $myrow['brname'],                       $myrow['district'], 
 echo"</td>";
echo"</tr></tbody>";
echo"</table>";
echo"</div>";

 if($_GET['task']){
     
                  //            custbranch   ,.  brname      custbranch          bio_cstask.orderno=salesorders.orderno     
  // AND salesorders.debtorno=custbranch.debtorno
     
     $sql_taskupdate="SELECT bio_cstask.taskdescription,bio_cstask.orderno,bio_cstask.ticketno,bio_leadteams.teamname 
     FROM bio_cstask,bio_leadteams 
     WHERE bio_leadteams.teamid=bio_cstask.team 
     AND bio_cstask.id='".$_GET['task']."' ";
     $result_taskupdate=DB_query($sql_taskupdate,$db);
    // $rowupdate=DB_fetch_array($result_taskupdate);   echo $rowupdate['orderno'];   echo  $_GET['task'];
   
echo"<table width='1200px'><tr><td>";
 echo"<fieldset><legend>Task Completion</legend>";
  echo"<table>";
  $rowupdate=DB_fetch_array($result_taskupdate);
  
   if($_GET['cstype']==4)
   {
                $get_details=DB_query("SELECT bio_incident_cust.custname from bio_incident_cust,bio_incidents WHERE bio_incidents.cust_id=bio_incident_cust.cust_id AND bio_incidents.ticketno='".$rowupdate['ticketno']."'",$db);  
     $rowdetails=DB_fetch_array($get_details);
     $name=$rowdetails['custname'];
   }   else
   {
        $get_details=DB_query("SELECT brname from custbranch,salesorders WHERE custbranch.debtorno=salesorders.debtorno AND salesorders.orderno='".$rowupdate['orderno']."'",$db);  
     $rowdetails=DB_fetch_array($get_details);
     $name=$rowdetails['brname'];
   }
     
 
 
  echo"<tr><td>Customer Name :</td><td><label><b>".$name."</b></label></td>";
  echo"<td>Task :</td><td><label><b>".$rowupdate['taskdescription']."</b></label></td>";
  echo"<td>Team :</td><td><label><b>".$rowupdate['teamname']."</b></label></td>"; 
            
echo"<td>Completed Date :</td><td><input type=text class=date alt=' ". $_SESSION['DefaultDateFormat'] ."'  name=completeddate id=completeddate></td>";
            echo"<td><input type=submit name=submit value=Update onclick=' if(validate()==1)return false;'></td></tr>";           
 echo "<input type=hidden id=tsakid name=taskid value=".$_GET['task'].">";
 }   
      echo"</table>"; 
                           
      echo"</fieldset>";
 echo"</table></form>";

/*function getnextid($table,$field,$condition)   {
$sql2="select max($field)+1 as nextid from $table $condition";
//echo $sql;
$result=DB_query($sql2,$db);
$myrow=DB_fetch_array($result);
$nextid=$myrow[0];
echo 'nnnn'.$nextid;
return $nextid;
}*/
?>
<script>
function updatetask(str,str1)
{     //  alert(str1) ;
    location.href="?task="+ str + "&cstype=" + str1; 
}

function edit_task(str)
{
    
    var str1= document.getElementById("maincatid").value;
    var str2= document.getElementById("plantname").value;
    //alert(str1);alert(str2);
    location.href="?main="+ str1 + "&sub=" + str2 + "&tno=" + str;
    
}

function Filtercategory()       {

var str= document.getElementById("maincatid").value;
    //alert(str);
if (str=="")
  {
  document.getElementById("maincatid").innerHTML="";
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
        
    document.getElementById("plant").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_installation_taskplant.php?maincatid="+str,true);
xmlhttp.send(); 
}

function showschedule(){
    var str= document.getElementById("plantname").value;
    //alert(str);
if (str=="")
  {
  document.getElementById("schedule").innerHTML="";
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
        
    document.getElementById("schedule").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
showschedule1(str); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_installation_taskplant.php?plantname="+str,true);
xmlhttp.send();   


}


function showschedule1(str)
{ //alert(str);
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
        
    document.getElementById("task1").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_installation_taskplant.php?plantname1="+str,true);
xmlhttp.send();
    
}


/*function validate()
{ 
 var f=0;
 if(f==0){f=common_error('completeddate','Please select completed date ');  if(f==1) { return f; }} //alert(f);  
} */
function validate()
{
     
 var f=0; 
if(f==0){f=common_error('completeddate','Please select date');  if(f==1) { return f; }}     
if(f==0){f=common_error('rack1','Please Enter rack');  if(f==1) { return f; }}          
if(f==0){f=common_error('bin1','Please Enter bin');  if(f==1) { return f; }}
if(f==0)
{ 
    var x=document.getElementById('lastfileno1').value;
    if(x!=""){
       var l=x.length;
            if(isNaN(x))
           {  f=1;
              alert("Enter a numeric value"); document.getElementById('lastfileno1').focus();
              if(x=""){f=0;}
              return f; 
           }  
    }     
}        
}

function editdocin(str){
    //alert("kkjk");
    var id='editdocumentin'+str;
    alert(str);
    //var docid='doc'+str;
   // var docno=document.getElementById(docid).value; 
   // var rdateid='date'+str;
    //var rdate=document.getElementById(rdateid).value;
//    alert(rdate);
   // var autoid='autono'+str;
   // var autono=document.getElementById(autoid).value;

    //alert(autono);
    //    alert(docno); 
 
//    alert(str);
//    alert(lead); alert(task);
if (str=="")
  {
  document.getElementById("status").innerHTML="";
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
    document.getElementById(id).innerHTML=xmlhttp.responseText;

    }
  } 

xmlhttp.open("GET","bio_documentedit.php?rdate=" + str ,true);
xmlhttp.send();    
}
function dlt(str){
location.href="?delete=" +str;
}
function custfilename_print(){
    str1=document.getElementById("lsgType").value;
    str2=document.getElementById("lsgName").value;
    str3=document.getElementById('Districts').value;
    str4=document.getElementById('year').value;
    str5=document.getElementById('loc').value;
    if(str1==3)  {str6=document.getElementById("gramaPanchayath").value;}
    controlWindow=window.open("bio_custfilename_print.php?lsgtype=" + str1 + "&lsgname1=" + str2 + "&dist=" + str3 + "&year1=" +str4 + "&location=" + str5 + "&grama2=" + str6 ,"idproof","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=900,height=400"); 
}


</script>
