<?php
    $PageSecurity = 80;
  include('includes/session.inc');
  
  $assigned_off=$_GET['off'];
  $cur_off=$_SESSION['officeid'];
  $leadid=$_GET['leadid'];
  $taskid=$_GET['task'];
  
  $sql_status="SELECT COUNT(*) FROM bio_leadschedule
                WHERE leadid=".$leadid;
  $result_status=DB_query($sql_status,$db);
  $myrow_status=DB_fetch_array($result_status);
  $status_count=$myrow_status[0];
  

//    $sql1="SELECT officetype FROM bio_office WHERE id=$assigned_off";
//    $result1=DB_query($sql1,$db);
//    $row1=DB_fetch_array($result1);
//    $assigned_off=$row1['officetype'];
//    
//    $sql2="SELECT id FROM bio_office WHERE id=".$_SESSION['officeid'];
//    $result2=DB_query($sql2,$db);
//    $row2=DB_fetch_array($result2);
//    $cur_off=$row2['id'];
    
    if( $assigned_off >= $cur_off )
    {
        echo"<tr><td width=50%>Assign Team</td>";
        echo"<td><select name='team' id='team' style='width:150px'>";
        $sql="SELECT * FROM bio_leadteams WHERE office_id=".$assigned_off;
        $result=DB_query($sql,$db);

            $f=0;
          while($myrow1=DB_fetch_array($result))
          {  
          if ($myrow1['teamid']==$_POST['teamname'])  
            {   
            echo '<option selected value="';
            } else 
            {
            if ($f==0) 
                {
                echo '<option>';
                }
                echo '<option value="';
            }
            echo $myrow1['teamid'] . '">'.$myrow1['teamname'];
            echo '</option>';
            $f++;
           }     
            

           
        echo"</select></td></tr>";
        
echo'<tr><td></td>
<td><a style="cursor:pointer;" onclick="teamstatus()">View team status</a></td></tr>'; 
        

       
           
        
        $DateString = Date($_SESSION['DefaultDateFormat']); 

        echo"<tr><td>Start date*</td>";
        echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px' value='".$DateString."'></td></tr>";
       
       
       
    echo "<tr><td>Schedule</td>";
    echo '<td>';
              
        $sql8="SELECT enqtypeid FROM bio_leads WHERE leadid=$leadid";
        $result8=DB_query($sql8,$db);
        $row=DB_fetch_array($result8);
        $enqid=$row['enqtypeid'];

    if($enqid==1) {      
             $sql1="SELECT * FROM bio_schedule_master WHERE master_schedule_id IN (17,18,20,21,22,23,24)";             
    }elseif($enqid==2) {        
            $sql1="SELECT * FROM bio_schedule_master WHERE master_schedule_id IN (19,25,26,27,28,29)";              
    }elseif($enqid==3) {       
             $sql1="SELECT * FROM bio_schedule_master";               
    }                
               
              $result1=DB_query($sql1, $db);
              echo '<select name="Schedule" id="Schedule" style="width:150px">';
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
        
        
        
echo"<tr><td><td>";
echo '<a  style=cursor:pointer; onclick="viewschedule()">' . _('View Schedule') . '</a><br>'; 
echo"</tr></td></td>";


if($status_count>0){
    echo"<tr><td width=50%>Task*</td>";
        echo"<td><select name='task' id='task' style='width:150px'>";
        $sql="SELECT * FROM bio_task";
        $result=DB_query($sql,$db);

            $f=0;
          while($myrow1=DB_fetch_array($result))
          {  
          if ($myrow1['taskid']==$taskid)  
            {   
            echo '<option selected value="';
            } else 
            {
            if ($f==0) 
                {
                echo '<option>';
                }
                echo '<option value="';
            }
            echo $myrow1['taskid'] . '">'.$myrow1['task'];
            echo '</option>';
            $f++;
           } 
        echo"</select></td></tr>";            
}

 
        /*echo"<tr><td>End date*</td>";
        echo"<td id='enddatetd'><input type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";*/

    }
    
    elseif($assigned_off < $cur_off)
    {
          if($assigned_off==1)
          {
              $designid=4;
          }
          else
          {
              $designid=5;
          }
          
          
        
        $sql="SELECT bio_emp.empid, bio_emp.empname, bio_teammembers.teamid
              FROM bio_emp,bio_teammembers
              WHERE bio_emp.offid = $assigned_off
              AND bio_emp.designationid = $designid
              AND bio_teammembers.empid=bio_emp.empid
              ";
        $result=DB_query($sql,$db);
          echo"<tr><td width=50%>Assign Officer</td>";
        echo"<td><select name='team' id='team' style='width:150px'>";
            $f=0;
          while($myrow1=DB_fetch_array($result))
          {  
          if ($myrow1['teamid']==$_POST['teamname'])  
            {   
            echo '<option selected value="';
            } else 
            {
            if ($f==0) 
                {
                echo '<option>';
                }
                echo '<option value="';
            }
            echo $myrow1['teamid'] . '">'.$myrow1['empname'];
            echo '</option>';
            $f++;
           }     
            

           
        echo"</select></td></tr>";
    }
 
//  $sql_cur="SELECT officetype FROM bio_office WHERE id=$cur_office";
//  $result_cur=DB_query($sql_cur,$db);
//  $row_cur=DB_fetch_array($result_cur);
// 
//  $sql_assign="SELECT officetype FROM bio_office WHERE id=$assigned_office"; 
//  $result_assign=DB_query($sql_assign,$db);
//  $row_assign=DB_fetch_array($result_assign); 
//  
//  $cur_off=$row_cur['officetype'];
//  $assigned_off=$row_assign['officetype'];
  

  

  
  
?>
      