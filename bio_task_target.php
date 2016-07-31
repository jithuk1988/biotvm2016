<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  if(isset($_GET['taskid'])){
      $taskid=$_GET['taskid'];
      $number=$_GET['number'];
      $enqid=$_GET['enquiry'];   
      
    
 $sql_sub1="INSERT INTO bio_targettemp(taskid,
                                            actualvalue) 
                                 VALUES ('".$taskid."',
                                         '".$number."')";
      $result_sub1 = DB_query($sql_sub1,$db);
      
      $tempflg=DB_Last_Insert_ID($Conn,'bio_targettemp','temp_id');        
      echo"<input type='hidden' id='subtempid' value='".$tempflg."'>";
      
      echo"<table  style='width:65%;' border=0>";
      echo"<tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Task</td>
      <td>Number / Value</td>
      </tr>";
     
            $sql_sub2="SELECT bio_targettemp.actualvalue,
                        bio_targettemp.temp_id,
                        bio_dominst_task.inst_task AS Task,
                        bio_targettemp.taskid 
                   FROM bio_targettemp,bio_dominst_task 
                  WHERE bio_dominst_task.inst_id=bio_targettemp.taskid";
          
      
      $result_sub2=DB_query($sql_sub2, $db);
      $n=1;
      while($myrow=DB_fetch_array($result_sub2)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['Task']."<input type='hidden' id='subid' value='".$myrow['taskid']."'></td>
          <td>".$myrow['actualvalue']."<input type='hidden' id='number' value='".$myrow['actualvalue']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editsub'></tr></table>";
  }
  
  
  if(isset($_GET['tempid'])|| $_GET['tempid']!=""){
       $enqid=$_GET['enquiry'];
      $tempID=$_GET['tempid'];
      
      
      $sql_sub3="SELECT bio_targettemp.actualvalue,
                        bio_targettemp.temp_id, 
                        bio_targettemp.taskid 
                   FROM bio_targettemp,bio_taskmaster 
                  WHERE bio_taskmaster.taskname_id=bio_targettemp.taskid
                   AND bio_targettemp.temp_id=".$tempID;  
      $result1=DB_query($sql_sub3, $db);
      while($myrow=DB_fetch_array($result1)){
          echo"<td>Edit</td>
               <td colspan='2'><input type='hidden' id='subassemblyid' name='SubAssemblyID' value='".$myrow['taskid']."'>".$taskname."</td>
               <td><input type='text' id='subquantity' style='width:90px' name='SubQuantity' value='".$myrow['actualvalue']."'>
               <input type='hidden' id='subasstempid' name='SubAssTempID' value='".$myrow['temp_id']."'></td> 
               <td><input type='button' id='updatesub' name='UpdateSub' value='edit' onclick='doedit()'></td>";
      }
  }
  
  if(isset($_GET['editid'])){
       $enqid=$_GET['enquiry'];
      $number1=$_GET['editqty'];
      $tempID1=$_GET['editid'];
      
      $sql_edit="UPDATE bio_targettemp
                     SET actualvalue=".$number1." 
                     WHERE temp_id=".$tempID1;
      $result_edit=DB_query($sql_edit, $db); 

     echo"<table  style='width:65%;' border=0>";
      echo"<tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Task</td>
      <td>Number / Value</td>
      </tr>";
      $sql_sub2="SELECT bio_targettemp.actualvalue,
                        bio_targettemp.temp_id,
                        bio_dominst_task.inst_task AS Task,
                        bio_targettemp.taskid 
                   FROM bio_targettemp,bio_dominst_task 
                  WHERE bio_dominst_task.inst_id=bio_targettemp.taskid";
      $result_sub2=DB_query($sql_sub2, $db);
      $n=1;
      while($myrow=DB_fetch_array($result_sub2)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['Task']."<input type='hidden' id='subid' value='".$myrow['taskid']."'></td>
          <td>".$myrow['actualvalue']."<input type='hidden' id='number' value='".$myrow['actualvalue']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editsub'></tr></table>";
   }
  
  if(isset($_GET['deletid'])){
       $enqid=$_GET['enquiry'];
      $tempID2=$_GET['deletid'];
      $sql="DELETE FROM `bio_targettemp` WHERE `temp_id` = $tempID2 ";
      $result1=DB_query($sql, $db);
      
      echo"<table  style='width:65%;' border=0>";
      echo"<tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Task</td>
      <td>Number / Value</td>
      </tr>";
      $sql_sub2="SELECT bio_targettemp.actualvalue,
                        bio_targettemp.temp_id,
                        bio_dominst_task.inst_task AS Task,
                        bio_targettemp.taskid 
                   FROM bio_targettemp,bio_dominst_task 
                  WHERE bio_dominst_task.inst_id=bio_targettemp.taskid";
      $result_sub2=DB_query($sql_sub2, $db);
      $n=1;
      while($myrow=DB_fetch_array($result_sub2)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['Task']."<input type='hidden' id='subid' value='".$myrow['taskid']."'></td>
          <td>".$myrow['actualvalue']."<input type='hidden' id='number' value='".$myrow['actualvalue']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editsub'></tr></table>";
 
  } 




  
?>
