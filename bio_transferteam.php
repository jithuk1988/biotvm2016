<?php
    $PageSecurity = 80;
  include('includes/session.inc');
  
  $assigned_off=$_GET['off'];
  $cur_off=$_SESSION['officeid'];
  $leadid=$_GET['leadid'];
  $taskid=$_GET['task'];
  
  $leadarray=explode(",",$leadid);
  $taskarray=explode(",",$taskid);
  
  $leadcount=count($leadarray);
  
           ///////////   JK
           
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
                      $sql3="SELECT empid FROM bio_emp WHERE reportto='".$empid."' and deptid=2 and rowstatus!=2";
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
     
     $sql2="SELECT empid,reportto FROM bio_emp WHERE reportto=$empid and deptid=2 and rowstatus!=2";
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
           
           
           //////////////
  
  

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
              $sql="SELECT * FROM bio_leadteams WHERE teamid in (".$team_array.") and office_id=".$assigned_off;
        echo"<td><select name='team' id='team' style='width:150px'>";
    
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
     if($leadcount==1){         
        $sql8="SELECT enqtypeid FROM bio_leads WHERE leadid=$leadid";
        $result8=DB_query($sql8,$db);
        $row=DB_fetch_array($result8);
        $enqid=$row['enqtypeid'];
     }else{
         $sql8="SELECT enqtypeid FROM bio_leads WHERE leadid=".$leadarray[0];
        $result8=DB_query($sql8,$db);
        $row=DB_fetch_array($result8);
        $enqid=$row['enqtypeid'];
         
     }

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

if($leadcount==1){
$sql_status="SELECT COUNT(*) FROM bio_leadschedule
                WHERE leadid=".$leadid;
  $result_status=DB_query($sql_status,$db);
  $myrow_status=DB_fetch_array($result_status);
  $status_count=$myrow_status[0];

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
      