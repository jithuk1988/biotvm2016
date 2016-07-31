<?php
 $PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Schedule'); 
 $pagetype=3; 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">SCHEDULE</font></center>';

                           $office=$_SESSION['UserStockLocation'];
 
 if(isset($_GET['delete'])){
  $id=$_GET['delete'];
$sql="DELETE FROM bio_schedule WHERE schedule_id=$id";
$result=DB_query($sql,$db); 
}                          


 if(!isset($_POST['submit'])){ 

 $tempdrop="DROP TABLE IF EXISTS bio_schedule_temp";
 DB_query($tempdrop,$db);

 $temptable="CREATE TABLE `bio_schedule_temp` (
  `temp_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_master_id` int(11) NOT NULL,
  `actual_task_day` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`temp_schedule_id`))";
DB_query($temptable,$db);

}


if(isset($_POST['submit']))     {
        
        $schedule=$_POST['Schedule'];
        
        
        $sql_2="SELECT schedule
                FROM bio_schedule_master
                WHERE master_schedule_id=".$schedule;
        $result_2=DB_query($sql_2,$db);
        $myrow_2=DB_fetch_array($result_2);     
        $name=$myrow_2[0];                 
        
        
        $sql_count="SELECT COUNT(*) FROM bio_schedule
                WHERE schedule_master_id=".$schedule;
        $result_count=DB_query($sql_count,$db);
        $myrow_count=DB_fetch_array($result_count);
        
         if($myrow_count[0]>0){
            prnMsg(('Schedule ').' '.$name.' is already assigned','warn'); 
         }
        
         else{                         
        
        $sql_1="SELECT schedule_days
                FROM bio_schedule_master
                WHERE master_schedule_id=".$schedule;
        $result_1=DB_query($sql_1,$db);
        $myrow_1=DB_fetch_array($result_1);     
        $days=$myrow_1[0];                 
        
        $sql_day="SELECT SUM(actual_task_day)
                FROM bio_schedule_temp";
        $result_day=DB_query($sql_day,$db);
        $myrow_day=DB_fetch_array($result_day);     
        $assigned_days=$myrow_day[0];            
        
        if($assigned_days>$days){
            prnMsg(('Assigning days must be less than').' '.$days.' days','warn');
            
        }
        else{
        $sql5="SELECT *
                FROM bio_schedule_temp";
        $result5=DB_query($sql5,$db);
    
        while($myrow5=DB_fetch_array($result5))     {
            $task2=$myrow5['task_master_id'];
            $num=$myrow5['actual_task_day'];
            $sql6="INSERT INTO bio_schedule(schedule_master_id,
                                            task_master_id,
                                            actual_task_day)
                                     VALUES(".$schedule.",
                                           '".$task2."',
                                            ".$num.")";
            $result6=DB_query($sql6,$db);
        } 
        
        $tempdrop="DROP TABLE IF EXISTS bio_schedule_temp";
        DB_query($tempdrop,$db);

        $temptable="CREATE TABLE `bio_schedule_temp` (
                    `temp_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
                    `task_master_id` int(11) NOT NULL,
                    `actual_task_day` int(11) NOT NULL,
                    `status` int(11) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`temp_schedule_id`))";
        DB_query($temptable,$db); 
                                   
        }
  }    
}
  
 
    
echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table><tr><td>';
echo"<div id=panel1>";
echo"<div id=panel>";
echo"</div>";

//========================================== 
echo"<table>";
echo'<tr><td colspan=2>';
echo'<div id="subassembly_select">';
echo "<fieldset style='width:500px'>";   
echo "<legend><h3>Create Schedule</h3>";
echo "</legend>"; 
echo "<table style='align:left' border=0>";
                           
echo "<tr><td>Schedule</td>";
echo '<td>';

 $sql1="SELECT * FROM bio_schedule_master";
  $result1=DB_query($sql1, $db);
  echo '<select name="Schedule" id="Schedule" style="width:210px">';
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


echo "<tr><td>Task Name</td>";
echo '<td>';

 $sql1="SELECT * FROM bio_task";
  $result1=DB_query($sql1, $db);
  echo '<select name="Task" id="task" style="width:210px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['taskid']==$_POST['task']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['taskid'] . '">'.$myrow1['task']; 
    echo '</option>' ;
   $f++; 
   }
 echo '</select>';
 echo "</td></tr>";
 
 echo "<tr><td id='getunit'>Number of days</td>";
 echo "<td><input type=text name='Number' id='number' style='width:100px'>";
 //echo "<td>";
 echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="AddTask" id="addtask" value="Add" onclick="addSubAssemblies()">';
echo "</td>";
 echo "</tr>";
  
 echo "</table>";
 echo"<div id='editfdstok'></div>";

echo"<div id='taskdiv'></div>";
echo"</fieldset>"; 
echo"</td></tr>"; 


echo'</div>';
echo'</td></tr>';
 
//========================================== Buttons 
echo'<table>';
echo'<tr><td colspan=2><p><div class="centre">
<input type=submit name=submit value="' . _('Assign') . '" onclick="if(log_in()==1)return false;">';
echo'</td></tr>';
echo'</div>'; 
echo"</td></tr></table>";
echo'</form>';
echo'</div>';  

//========================================== End of Details

echo'<div id="schedulegrid">';
echo"<fieldset style='width:550px'><legend><h3>Created Schedule</h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>";   
 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
        <th>Schedule</th>";


 $sql8="SELECT bio_schedule.schedule_id,
               bio_schedule_master.schedule,
               bio_schedule_master.schedule_days,
               bio_schedule.schedule_master_id
          FROM bio_schedule,
               bio_schedule_master
         WHERE bio_schedule.schedule_master_id = bio_schedule_master.master_schedule_id
                GROUP BY bio_schedule.schedule_master_id";
 
 $resultl= DB_query($sql8,$db);
  
  
  $k=0 ;$slno=0;
  while($myrow = DB_fetch_array($resultl))
   {
       $schedule=$myrow[1]." - ".$myrow[2]." days";
       $schedule_id=$myrow[3];
       if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  
      $slno++;
      printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td><a  style='cursor:pointer;'  onclick=ViewSchedule('$schedule_id')>" . _('View Schedule') . "</a></td> 
            <td><a href='#' id='$schedule_id' onclick='dlt(this.id)'>Delete</a></td>    
            </tr>",
        $slno,
        $schedule);
      
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
     $("#warn").fadeOut(10000);  

   });
 

function log_in()
{
 var task=document.getElementById('task').focus();
var f=0;
var p=0;
if(f==0){f=common_error('Schedule','Please select the Schedule');  if(f==1){return f; } }  
if(f==0){f=common_error('task','Please select the Task Name');  if(f==1){return f; }  }  
if(f==0){f=common_error('number','Please enter the Number of days');  if(f==1){return f; }  }  

}

function dlt(str){
    alert("hii"); 
location.href="?delete=" +str;         
 
}


function addSubAssemblies()
{

    var str1=document.getElementById("task").value;
    var str2=document.getElementById("number").value;
   //alert(str1);
   //alert(str2);
if(str1=="")
{
alert("Select Task Name"); document.getElementById("taskid").focus();  return false;  
}

if(str2=="")
{
alert("Select Quantity"); document.getElementById("number").focus();  return false;  
}
if (str1=="")
  {
  document.getElementById("taskdiv").innerHTML="";     //editleads
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
    document.getElementById("taskdiv").innerHTML=xmlhttp.responseText;
    document.getElementById('taskid').value="";       document.getElementById('number').value="";
    }
  } 
xmlhttp.open("GET","bio_schedule_target.php?taskid=" + str1  + "&number=" + str2 ,true);
xmlhttp.send();    

}

function editsubassembly(str)
{
   //alert("hii");
//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;
//alert(str);

if (str=="")
  {
  document.getElementById("editsub").innerHTML="";
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
    document.getElementById("editsub").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_schedule_target.php?tempid=" + str,true);
xmlhttp.send();    

}

function doedit()
{
//   alert("hii");
//   alert(str);

var str=document.getElementById("subasstempid").value;    
var str1=document.getElementById("subassemblyid").value;
var str2=document.getElementById("subquantity").value;
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("taskdiv").innerHTML="";
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
    document.getElementById("taskdiv").innerHTML=xmlhttp.responseText;
    $('#subquantity').focus(); 
    }
  } 
xmlhttp.open("GET","bio_schedule_target.php?editid=" + str + "&editsub=" + str1 + "&editqty=" + str2,true);
xmlhttp.send();    

}  

function deletsubassembly(str)
{
//   alert("hii");
//   alert(str);
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("taskdiv").innerHTML="";
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
    document.getElementById("taskdiv").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_schedule_target.php?deletid=" + str,true);
xmlhttp.send();    

}




function showSubassemblies(str){ //var a="#"+str;
//$(a).hide();

str=2;

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
    
    document.getElementById("subassembly_select").innerHTML=xmlhttp.responseText; 
     
    }
  }

xmlhttp.open("GET","bio_schedule_target.php" ,true);
xmlhttp.send();
}

function subname(str)
{
var subname=document.getElementById("subassemblyname").value; 
if(subname!="no")       {
var newstr=subname + ',' + str;  
   
}else   {
    var newstr=str; ;
}
if (str=="")
  {
  document.getElementById("subassemblies").innerHTML="";
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
    document.getElementById("subassemblies").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_schedule_target.php?id=" + newstr,true);
xmlhttp.send();    

}  


function ViewSchedule(str1)
{
//alert(str1);
if (str1=="")
  {
  document.getElementById("schedulegrid").innerHTML="";     //editleads
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
  {//alert(str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {     //alert("sss");
    document.getElementById("schedulegrid").innerHTML=xmlhttp.responseText;

//             $('#ViewSchedule').show(); 

//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_viewschedule.php?id=" + str1,true);
xmlhttp.send();    

}


</script>

                                  

