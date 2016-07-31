<?php
  
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Sub Task Master');
include('includes/header.inc');  

$leadid=$_GET['leadid'];

if($_GET['taskid']!=""){
    $tid=$_GET['taskid'];
   $_SESSION['TID']=$_GET['taskid']; 
}

$scheduleid=$_GET['scheduleid']; 
$startdate=$_GET['assigneddate'];
$DateString = Date($_SESSION['DefaultDateFormat']);

                                 
/* if(isset($_GET['delete'])){
  $natid=$_GET['delete'];
$sql="DELETE FROM bio_subtask_master  WHERE subtask_master_id=$natid";
$result=DB_query($sql,$db); 
}

if(isset($_GET['edit'])){
  $tid=$_GET['edit'];
$sql="SELECT * FROM bio_subtask_master  WHERE subtask_master_id=$tid";
$result=DB_query($sql,$db);
$myrow2=DB_fetch_array($result);
$subtask_name=$myrow2['subtask_name']; 
}   */



echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Enquiry Types')
    . '" alt="" />' . _('Sub Task Setup') . '</p>';
  
 if (isset($_POST['submit'])){ 
  $tid=$_POST['TID'];  
  $subtask_name=$_POST['Subtask'];   
  $subtask_date=FormatDateForSQL($_POST['SubtaskDate']);
  $startdate=$_POST['StartDate'];
//edit  
  $InputError=0; 
  $sql_check="SELECT COUNT(*) FROM bio_subtask_master
                WHERE subtask_name='".$subtask_name."'";
  $result_check=DB_query($sql_check,$db);
  $myrow_check=DB_fetch_array($result_check);  
  
  
 /*$InputError=0;  
 echo $sql_date="SELECT assigneddate FROM bio_leadtask
                WHERE assigneddate='".$startdate."'";
  $result_date=DB_query($sql_date,$db);
  $myrow_date=DB_fetch_array($result_date);   
  
     
        $InputError=0; 
  echo$myrow_date=strtotime($startdate)-strtotime($subtask_date);
        if($myrow_date<0)     {
             
        prnMsg('Startdate is greater than due date','warn');
        }                                                    
        //exit;                 */              
        

        
                               
        /*     $InputError=0;                       
        $startdate=FormatDateForSQL($_POST['StartDate']);
        $subtask_date=FormatDateForSQL($_POST['SubtaskDate']);
         
        $datearr1 = split("-",$startdate); 
        $datearr2 = split("-",$subtask_date); 
        $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]);
        if($date_diff>30){
            $InputError=1;
            prnMsg('The End date should be with in 30 days from Start date','warn');
        }      */ 
        
   $InputError=0; 
   $date_check=strtotime(FormatDateForSQL($DateString))-strtotime($subtask_date);
   if($date_check>'0')     {
            $InputError=1;
            prnMsg('Subtask date is less than current date','warn');
        }                            
  
  
        
 
  if($InputError==0){    
  $sql = "INSERT INTO bio_subtask_master(leadtask_id,subtask_name,subtask_date) 
                                VALUES ('" .$tid. "','" .$subtask_name. "','".$subtask_date."')";                                           
 $result = DB_query($sql,$db);
 prnMsg('The Subtask is added successfully','success');
 
  }
  
  unset($_POST['Subtask']);
  unset($subtask_name);
  unset($_POST['SubtaskDate']);
  unset($subtask_date);   
 }        
//-----------------------------------------------------//    
echo '<table style=width:400px><tr><td>';
echo '<fieldset style="height:250px">';
echo '<legend><b>Sub Task Master</b></legend>';

//-----------------------------------------------------// 

   
 if($_GET['taskid']!="" AND $_GET['taskid']!=""){

       $sql="SELECT bio_leadtask.leadid,
                    bio_leadschedule.scheduleid,
                    bio_schedule_master.schedule, 
                    bio_leadtask.taskid,
                    bio_task.task,
                    bio_leadtask.assigneddate,
                    bio_leadtask.duedate  
              FROM  bio_leadschedule,
                    bio_leadtask,
                    bio_task,
                    bio_schedule_master
              WHERE bio_leadtask.leadid=bio_leadschedule.leadid
                AND bio_leadschedule.scheduleid=bio_schedule_master.master_schedule_id
                AND bio_leadtask.taskid=bio_task.taskid
                AND bio_leadtask.leadid=".$leadid."
                AND bio_leadtask.tid=".$tid;
 $result=DB_query($sql,$db);

 $myrow=DB_fetch_array($result); 

 $schedule=$myrow[2];  
 $task=$myrow[4];
 $startdate=ConvertSQLDate($myrow[5]);
 $enddate=ConvertSQLDate($myrow[6]);
 }
 //-----------------------------------------------------// 
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo '<br><br><table class="selection"  style="width:250px;height:160px">';  


echo"<tr><td width=150px>Schedule</td>";
echo"<td><input type='hidden' name='Schedule' id='Schedule' value='$schedule'>$schedule</td></tr>";

echo"<tr><td width=150px>Task</td>";
echo"<td><input type='hidden' name='Task' id='task' value='$task'>$task</td></tr>";

echo"<tr><td width=150px>Start date</td>";
echo"<td><input type='hidden' name='StartDate' id='startdate' value='$startdate'>$startdate</td></tr>";
      
//echo"<tr><td width=150px>End date</td>";
//echo"<td><input type='hidden' name='EndDate' id='EndDate' value='$enddate'>$enddate</td></tr>";
    
echo"<td><input type='hidden' name='TID' id='tid' value='$tid'></td></tr>";       

echo '<tr><td>Sub Task</td><td><input type="text" name="Subtask" id="subtask" style="width:150px" value="'.$subtask_name.'"></td></tr>';
 
$DateString = Date($_SESSION['DefaultDateFormat']);
 
echo"<tr><td>Date</td>";
echo"<td><input type='text' name='SubtaskDate' id='SubtaskDate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";

echo "<td>";  
echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Assign') . '" onclick=" if(validate()==1)return false"></td></tr></td>'; 
echo '</table>';
echo '</form></fieldset>';

  echo'<div id="subtask_grid">';
echo"<fieldset style='width:500'><legend><h3>Subtask Details</h3></legend>"; 
echo "<div style='height:250px; width:100%; overflow:scroll;'>";   
 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
        <th>Task Name</th>
        <th>Date</th>";

 
     
 $sql8="SELECT bio_subtask_master.subtask_master_id, 
               bio_subtask_master.subtask_name, 
               bio_leadtask.assigneddate,
               bio_subtask_master.subtask_date
          FROM bio_subtask_master,bio_leadtask
         WHERE bio_leadtask.tid=bio_subtask_master.leadtask_id
         And bio_subtask_master.leadtask_id=".$_SESSION['TID'];
 
 $resultl= DB_query($sql8,$db);
  
  
  $k=0 ;$slno=0;
  while($myrow = DB_fetch_array($resultl))
   {

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
            <td>%s</td>
            </tr>",
            $slno,
            $myrow['subtask_name'],
            ConvertSQLDate($myrow['subtask_date']),
            ConvertSQLDate($myrow['subtask_date']));         
   }    
echo '<tbody>';
echo"</tr></tbody>";

echo"</table>";  
echo"</div>";
echo"</fieldset>";  
echo'</div>';


echo '</td></tr></table>';  

 //include('includes/footer.inc');   
?>
   
   
  <script>


        //$(document).ready(function(){


        $("#error").fadeOut(1);
        $("#warn").fadeOut(5000);
        $("#success").fadeOut(5000);
//        $("#info").fadeOut(3000);
//        $(".db_message").fadeOut(1);




//        });

  
  
  
  
  
document.getElementById('task').focus();
  //delete
  function dlt(str){
location.href="?delete=" +str;         
 
}

function edit(str){
location.href="?edit=" +str;         
 
}


  function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('subtask','Please enter the task name');  if(f==1){return f; }  }

//if(f==0){f=common_error('category','Please select the task');  if(f==1){return f; }  }

}

</script>
                              

