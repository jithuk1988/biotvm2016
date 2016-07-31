<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  if(isset($_GET['taskid'])){
      $taskid=$_GET['taskid'];
      $number=$_GET['number'];
      if($taskid==""){
          $taskid=0;
      }
      if($number==""){
          $number=0;
      }
      $sql_count="SELECT COUNT(*) FROM bio_schedule_temp
                WHERE task_master_id=".$taskid;
      $result_count=DB_query($sql_count,$db);
      $myrow_count=DB_fetch_array($result_count);
      if($myrow_count[0]>0){
          prnMsg(('This task is already assigned'),'warn');
      }
      
      else{
          
     
      $sql_sub1="INSERT INTO bio_schedule_temp(task_master_id,
                                          actual_task_day) 
                                 VALUES ('".$taskid."',
                                         '".$number."')";
      $result_sub1 = DB_query($sql_sub1,$db);
      
      $tempflg=DB_Last_Insert_ID($Conn,'bio_schedule_temp','temp_schedule_id');        
      echo"<input type='hidden' id='subtempid' value='".$tempflg."'>";
      } 
      echo"<table  style='width:65%;' border=0>";
      echo"<tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Task</td>
      <td>Number of days</td>
      </tr>";
      
      $sql_sub2="SELECT bio_task.taskid,
                        bio_task.task,
                        bio_schedule_temp.actual_task_day,
                        bio_schedule_temp.temp_schedule_id 
                   FROM bio_schedule_temp,bio_task 
                  WHERE bio_task.taskid=bio_schedule_temp.task_master_id";
      $result_sub2=DB_query($sql_sub2, $db);
      $n=1;
      while($myrow=DB_fetch_array($result_sub2)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['task']."<input type='hidden' id='subid' value='".$myrow['task_master_id ']."'></td>
          <td>".$myrow['actual_task_day']."<input type='hidden' id='number' value='".$myrow['actual_task_day']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_schedule_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_schedule_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editsub'></tr></table>";
     
  }
  
  
  if(isset($_GET['tempid'])|| $_GET['tempid']!=""){
      $tempID=$_GET['tempid'];
      $sql_sub3="SELECT bio_task.taskid,
                        bio_task.task,
                        bio_schedule_temp.actual_task_day,
                        bio_schedule_temp.temp_schedule_id, 
                        bio_schedule_temp.task_master_id  
                   FROM bio_schedule_temp,bio_task 
                  WHERE bio_task.taskid=bio_schedule_temp.task_master_id
                   AND bio_schedule_temp.temp_schedule_id=".$tempID;
      $result1=DB_query($sql_sub3, $db);
      while($myrow=DB_fetch_array($result1)){
          echo"<td>Edit</td>
               <td colspan='2'><input type='hidden' id='subassemblyid' name='SubAssemblyID' value='".$myrow['task_master_id']."'>".$myrow['task']."</td>
               <td><input type='text' id='subquantity' style='width:90px' name='SubQuantity' value='".$myrow['actualvalue']."'></td>
               <input type='hidden' id='subasstempid' name='SubAssTempID' value='".$myrow['temp_schedule_id']."'>".$myrow['temp_schedule_id']." 
               <td><input type='button' id='updatesub' name='UpdateSub' value='edit' onclick='doedit()'></td>";
      }
  }
  
  if(isset($_GET['editid'])){
      $number1=$_GET['editqty'];
      $tempID1=$_GET['editid'];
      
      $sql_edit="UPDATE bio_schedule_temp
                     SET actual_task_day=".$number1." 
                     WHERE temp_schedule_id=".$tempID1;
      $result_edit=DB_query($sql_edit, $db); 

      echo"<table  style='width:65%;' border=0>
      <tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Task</td>
      <td>Numbe of daysr</td></tr>";
      
      $sql="SELECT bio_task.taskid,
                        bio_task.task,
                        bio_schedule_temp.actual_task_day,
                        bio_schedule_temp.temp_schedule_id
                   FROM bio_schedule_temp,bio_task 
                  WHERE bio_task.taskid=bio_schedule_temp.task_master_id";
      $result1=DB_query($sql, $db);    
      $n=1;
      while($myrow=DB_fetch_array($result1)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['task']."<input type='hidden' id='subid' value='".$myrow['task_master_id']."'></td>
          <td>".$myrow['actual_task_day']."<input type='hidden' id='quantity' value='".$myrow['actual_task_day']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_schedule_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_schedule_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editsub'></tr></table>";
  }
  
  if(isset($_GET['deletid'])){
      $tempID2=$_GET['deletid'];
      $sql="DELETE FROM `bio_schedule_temp` WHERE `temp_schedule_id` = $tempID2 ";
      $result1=DB_query($sql, $db);
      
      echo"<table  style='width:65%;' border=0>";
      echo"<tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Task</td>
      <td>Number of days</td>
      </tr>";
      
      $sql_sub2="SELECT bio_task.taskid,
                        bio_task.task,
                        bio_schedule_temp.actual_task_day,
                        bio_schedule_temp.temp_schedule_id 
                   FROM bio_schedule_temp,bio_task 
                  WHERE bio_task.taskid=bio_schedule_temp.task_master_id";
      $result_sub2=DB_query($sql_sub2, $db);
      $n=1;
      while($myrow=DB_fetch_array($result_sub2)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['task']."<input type='hidden' id='subid' value='".$myrow['task_master_id']."'></td>
          <td>".$myrow['actual_task_day']."<input type='hidden' id='quantity' value='".$myrow['actual_task_day']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_schedule_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_schedule_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }
      
      echo"</table>";;
  } 

?>

<script type="text/javascript">
  
$(document).ready(function() {   
     $("#warn").fadeOut(3000);  

   });
</script> 