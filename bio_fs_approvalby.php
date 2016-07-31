<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('FS Approval');
include('includes/header.inc');
$fstudyID=$_SESSION['FS_ID'];
unset($_SESSION['FS_ID']);   
    


echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">APPROVAL AUTHORITY</font></center>';


if (isset($_POST['assignvalue'])) {
    $sql_app="UPDATE bio_feasibilitystudy
                        SET approved_by='".$_POST['ApprovalBy']."'
                        WHERE feasibilitystudy_id=".$_POST['FSTUDYID'];
    $result_app= DB_query($sql_app,$db);
    
    $sql1="SELECT * FROM bio_feasibilitystudy
                   WHERE feasibilitystudy_id=".$_POST['FSTUDYID'];
    $result1=DB_query($sql1,$db);
    $row1=DB_fetch_array($result1);
    
    
    
    
    $lead_ID=$row1['leadid'];
    $task_ID=7;
    $submitted_by=$_SESSION['UserID'];
    $approval_by=$_POST['ApprovalBy'];
    $assigndate=$row1['proposal_date'];
    $duedate="0000-00-00";
    $date1="0000-00-00";
    $status=0;
    
    $sql="INSERT INTO bio_approval(taskid,
                                   leadid,
                                   submitted_user,
                                   approval_user,
                                   assigneddate,
                                   duedate,
                                   taskcompleteddate,
                                   taskcompletedstatus) 
                            VALUES ('".$task_ID."',
                                    '".$lead_ID."',
                                    '".$submitted_by."',
                                    '".$approval_by."',
                                    '".$assigndate."',
                                    '".$duedate."',
                                    '".$date1."',
                                    '".$status."')";
    $result=DB_query($sql,$db);
   /* 
    $sql2="INSERT INTO bio_accessrights(leadid,
                                        userid,
                                        r,
                                        w) 
                            VALUES ('".$lead_ID."',
                                    '".$approval_by."',
                                    1,
                                    1)";
    $result2=DB_query($sql2,$db);
   */ 
    ?>
    <script language="JavaScript">
    window.opener.location='bio_feasibilitystudy.php';
    window.close();
    </script>
  <?php  

}    
        
echo "<table style='width:70%'><tr><td>";
echo "<fieldset style='width:80%;height=300px'>";     
echo "<legend><h3>Select Approval Authority</h3>";
echo "</legend>";   
echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';

echo '<br><table width=70%>';
echo"<td><input type='hidden' name='FSTUDYID' id='fstudyid' value='$fstudyID'></td></tr>"; 
echo '<tr><td width=50%>Approval By</td>';
echo"<td><select name='ApprovalBy' id='approvalby</' style='width:150px'>";

$empid=$_SESSION['empid'];
$sql_rep="SELECT reportto FROM bio_emp WHERE empid=$empid";
$result_rep=DB_query($sql_rep,$db);
$row_rep=DB_fetch_array($result_rep);
$emp_repoff=$row_rep['reportto'];

$employee_arr=array();
 
$sql_drop="DROP TABLE IF EXISTS `emptable`";
$result_drop=DB_query($sql_drop,$db);
 
$sql_create="CREATE TABLE emptable (empid int)";
$result_create=DB_query($sql_create,$db);   

function showemp($empid,$db,$y)
{  
    $sql3="SELECT reportto FROM bio_emp WHERE empid='".$empid."'";
    $result3=DB_query($sql3,$db);
    $employee_arr=array();
    while($row3=DB_fetch_array($result3)){
        $empid=$row3['reportto'];
        $sql_insert="INSERT INTO emptable (empid) VALUES ($empid)";
        $result_insert=DB_query($sql_insert,$db);
        $employee_arr[]=$empid;
        if($empid>1){
            showemp($empid,$db,$y);  
        }
        elseif($empid==0){
            $employee_arr[]=1;  
        }
                                            
    } 
                                      
} 
                      
                      //$sql_insert1="INSERT INTO emptable (empid) VALUES ($_SESSION[empid])";
                     //$result_insert2=DB_query($sql_insert1,$db);
     
$sql2="SELECT reportto FROM bio_emp WHERE empid=$empid";
$result2=DB_query($sql2,$db);   
while($row2=DB_fetch_array($result2))
{ 
    $empid=$row2['reportto'];
//    $employee_arr[]=$empid;
    $y=$empid;
    if($empid>1){
      showemp($empid,$db,$y);  
    }
    elseif($empid==0 OR $empid==1){
      $employee_arr[]=1;  
    }
    
} 
$sql_select="SELECT empid FROM emptable";
$result_select=DB_query($sql_select,$db);
while($row_select=DB_fetch_array($result_select)){
    $employee_arr[]=$row_select['empid'];
}
$employee_arr=join(",", $employee_arr);
$sql5="SELECT userid FROM www_users WHERE empid IN ($employee_arr)";
$result5=DB_query($sql5,$db);
while($row5=DB_fetch_array($result5)){
    $userid[]="'".$row5['userid']."'";     
}
$user_array=join(",", $userid);



$sql="SELECT www_users.userid, bio_emp.empname
        FROM bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid IN ($user_array)";
$result=DB_query($sql,$db);

   echo'<option value=0></option>';
        while($row=DB_fetch_array($result))
        {       
        if ($row['userid']==$_POST['ApprovalBy'])
        {
         echo '<option selected value="';
        } else {
         echo '<option value="';
        }
        echo $row['userid'] . '">'.$row['empname'];
        echo '</option>';
        }
echo"</select></td></tr>";
echo'<tr><td colspan=2><p><div class="centre">
<input type=submit name="assignvalue" value="Assign">';
echo'</div>';
echo'</td></tr>';
echo '</table>';

echo'</form>';
echo "</fieldset></td></tr></table>";
?>
