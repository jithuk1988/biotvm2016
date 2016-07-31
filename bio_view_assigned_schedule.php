<?php
 $PageSecurity = 80;
 include('includes/session.inc');        
 $title = _('Schedule'); 
 $pagetype=3; 
 include('includes/header.inc');
// include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;   
    text-shadow: 1px 1px 1px #666;">Assigned Schedule Days</font></center>';

$schedule_id=$_GET['id']; 
echo'<div id="schedulegrid">';
echo"<fieldset style='width:550px'><legend><h3></h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>";   
 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
        <th>Schedule</th>
        <th>Task Name</th>
        <th>Scheduled days</th>";


 $sql8="SELECT bio_schedule.schedule_id,
               bio_schedule_master.schedule,
               bio_schedule_master.schedule_days,
               bio_task.task,  
               bio_schedule.actual_task_day
          FROM bio_schedule,
               bio_schedule_master,
               bio_task
         WHERE bio_schedule.task_master_id = bio_task.taskid
           AND bio_schedule.schedule_master_id = bio_schedule_master.master_schedule_id
           AND bio_schedule.schedule_master_id =".$schedule_id;
  
$resultl= DB_query($sql8,$db);
  
  
  $k=0 ;$slno=0;
  while($myrow = DB_fetch_array($resultl))
   {
       $schedule=$myrow[1]." - ".$myrow[2]." days";
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
            <td>%s</td>
            </tr>",
        $slno,
        $schedule,
        $myrow[3],
        $myrow[4],
        $myrow[5]);
      
      }    
echo '<tbody>';
echo"</tr></tbody>
</table>";  
echo"</div>";
echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 


?>


                                  

