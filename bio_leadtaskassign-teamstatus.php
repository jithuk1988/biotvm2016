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
    text-shadow: 1px 1px 1px #666;">Pending Tasks Of The Team</font></center>';

$teamid=$_GET['teamid']; 
echo'<div id="teamgrid">';
echo"<fieldset style='width:550px'><legend><h3></h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>";   
 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
        <th>Task Name</th>
        <th>Duedate</th>";


 $sql8="SELECT bio_leadtask.tid,
               bio_leadtask.taskid,
               bio_leadtask.duedate,
               bio_task.task
          FROM bio_leadtask,
               bio_task
         WHERE bio_leadtask.teamid = $teamid        AND
               bio_leadtask.taskcompletedstatus=0   AND
               bio_leadtask.taskid!=0               AND 
               bio_task.taskid=bio_leadtask.taskid";
  
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
      
      $duedate=ConvertSQLDate($myrow[2]);
      
      printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
        $slno,
        $myrow[3],
        $duedate
        );
      
      }    
echo '<tbody>';
echo"</tr></tbody>
</table>";  
echo"</div>";
echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 


?>


                                  

