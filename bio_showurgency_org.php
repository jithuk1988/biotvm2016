<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 

$enqid=$_GET['enqid'];

        echo "<tr><td>Urgency Level</td>";
        echo '<td>';

     if($enqid==1) {      
             $sql1="SELECT * FROM bio_schedule_master WHERE master_schedule_id IN (20,21,22,23,24)";             
    }elseif($enqid==2) {        
            $sql1="SELECT * FROM bio_schedule_master WHERE master_schedule_id IN (25,26,27,28,29)";              
    }elseif($enqid==3 || $enqid==4) {       
             $sql1="SELECT * FROM bio_schedule_master";               
    }                
               
              $result1=DB_query($sql1, $db);
              echo '<select name="UrgencyLevel" id="urgencylevel" style="width:150px">';
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
?>
