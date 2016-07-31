<?php
  $PageSecurity = 80;
  include('includes/session.inc');
//  $title = _('Institution Task View');  
//  include('includes/header.inc');
  
$leadid=$_GET['leadid'];  

if(isset($_POST['escalate']))
{
    //$count=$_SESSION['count']; 
//    for($j=1;$j<$count;$j++)
//    {
    $duedate=$_POST['dued'.$j];
   
//    echo$duedate=$_POST['arr'];
$j=1;
  $leadid=$_POST['leadid']; echo"<input type=hidden name=leadid id=leadid value='$leadid'>"; 
    $sql_compare="SELECT * FROM bio_leadtask 
                      where leadid=$leadid 
                      AND taskcompletedstatus!=3 
                      AND taskcompletedstatus!=2
                      ORDER BY bio_leadtask.duedate ASC";
    $result_compare=DB_query($sql_compare,$db);
    while($myrow=DB_fetch_array($result_compare))
    {
        $olddate=ConvertSQLDate($myrow['duedate']);                                                             //  
        $newdate=$_POST['dued'.$j];
        //echo$newdate=ConvertSQLDate($newdate);
        if($olddate!=$newdate){
             
            $sql="INSERT INTO bio_extendedduedate(tid,newduedate)
                                        VALUES(".$myrow['tid'].",'".FormatDateForSQL($newdate)."')";
            
            DB_query($sql,$db);
        }
        $j++;
    }       
 ?>   
    <script>
  var str=document.getElementById("leadid").value;   
//  alert(str);  
//window.opener.location='bio_domListTask.php'; 
//window.location = "bio_domListTask.php?leadid=" +str;  
//location.reload(true); 
//window.opener.location='bio_domListTask.php?leadid='+ str; 
window.location = "bio_instTaskview.php"; 
</script>

<?php
  
} 
   
if(!isset($_POST['escalate']))  
{
    
echo"<fieldset style='width:800px'><legend>Pending Tasks</legend>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
echo"<table style='border:1px solid #F0F0F0;width:100%'>";
//echo"<table style='width:100%'> "; 
echo"<tr><th>Task</th>
         <th>Assigned Date</th>
         <th>Due Date</th>

    </tr>";  
  
  $sql="SELECT bio_cust.cust_id,
               bio_leads.enqtypeid,
               bio_leadtask.leadid,
               bio_task.task,
               bio_leadtask.taskid, 
               bio_leadtask.assigneddate,
               bio_leadtask.duedate,
               bio_leadtask.taskcompletedstatus,
               bio_leadtask.tid
          FROM bio_leads,bio_leadtask,bio_cust,bio_task 
         WHERE bio_leadtask.leadid=$leadid
           AND bio_leads.leadid=bio_leadtask.leadid
           AND bio_leads.cust_id=bio_cust.cust_id
           AND bio_task.taskid=bio_leadtask.taskid
           AND bio_leadtask.taskcompletedstatus!=2 AND bio_leadtask.taskcompletedstatus!=3 
           ORDER BY bio_leadtask.duedate ASC           
           ";                                                     
  $result=DB_query($sql,$db);
  $no=1;
  $k=0;           
  $j=1;   
  $dueD_arr=array();   
  while($row=DB_fetch_array($result)) 
  {
     $leadid=$row['leadid'];        echo"<input type=hidden name=leadid value='$leadid'>";
     $tid=$row['tid'];              
     $custid=$row['cust_id'];        
     $enqid=$row['enqtypeid'];      
     $taskid=$row['taskid'];
     $taskcompletedstatus=$row['taskcompletedstatus'];
     $assignDate=ConvertSQLDate($row['assigneddate']);
     
     $duedate=ConvertSQLDate($row['duedate']);
     $dueD_arr[]=$duedate;

          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
             $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
             $k=1;     
          }
          
              echo"<td><a style='cursor:pointer;' id='$leadid' onclick='selectTask(this.id,$taskid,$tid,$enqid)'>".$row['task']."</a></td>";    
              echo"<td><input type='text' readonly name='assignd' id='assignd' value='$assignDate' ></td>";   
              
              echo'<td><input type=text name="dued'.$j.'" id="dued" value="'.$duedate.'" ></td>';                        
        echo"</tr>";        
     $no++;
     $j++;     
  }
  $_SESSION['count']=$j--; 
  $dueD_arr=join(",",$dueD_arr);
  echo"<input type=hidden name=arr value='$dueD_arr'>"; 
  
  echo"<tr><td></td><td></td><td><input type='submit' name='escalate' id='escalate' value='Escalate Date'></td></tr>"; 
  echo"</form>";
          
echo"</table>";
echo"</fieldset>";     
}  
 
?>
