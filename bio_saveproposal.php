<?php
$PageSecurity = 80;
include('includes/session.inc');
//include('includes/header.inc');
include('includes/bio_GetPrice.inc');

$lead=$_GET['lead'];
$_SESSION['flag2']=$_GET['flag2'];
$flag2=$_SESSION['flag2'];

$cur_date=date("Y-m-d");
$userid=$_SESSION['UserID'];
$crdt=date("Y-m-d H:i:s");   


$empid=$_SESSION['empid'];
$sql_rep="SELECT reportto FROM bio_emp WHERE empid=$empid";
$result_rep=DB_query($sql_rep,$db);
$row_rep=DB_fetch_array($result_rep);
$emp_repoff=$row_rep['reportto'];
if($emp_repoff==0){
    $emp_repoff=1;
}

$sql_user="SELECT www_users.userid
        FROM www_users
        WHERE www_users.empid=".$emp_repoff;
$result_user=DB_query($sql_user,$db);
$row_user=DB_fetch_array($result_user);
$approval_by=$row_user['userid'];



$sql="SELECT stockid,description,qty,price,tprice FROM bio_temppropitems WHERE leadid=".$lead;
//echo "$sql";
$result=DB_query($sql,$db);
$num_rows = $result->num_rows;
//echo "num rows= ".$num_rows;

if ($num_rows > 0) {
$sql2="SELECT SUM(tprice) AS totalprice FROM bio_temppropitems WHERE leadid=".$lead;
//echo "$sql2";
$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_array($result2);
$totalprice=$myrow2[0];
//echo "total price=".$totalprice;
if ($totalprice > 0) {
$sql3="INSERT INTO bio_proposal (propdate, leadid, totprice, status,createdby) VALUES ('".$cur_date."',$lead,$totalprice,1,'$userid')";
//echo "$sql3";
$ErrMsg =  _('An error occurred while inserting proposal data');
$result3=DB_query($sql3,$db,$Errmsg);
// if ($result3) {echo "insert bioproposal done successfully";}
$sql7="SELECT LAST_INSERT_ID()";
$result7=DB_query($sql7,$db);
$myrow7=DB_fetch_array($result7);
$lastid=$myrow7[0];
$i=0;
while ($myrow=DB_fetch_array($result))   {
  $sql4="INSERT INTO bio_proposaldetails (propid,slno,stockid,description,qty,price,tprice) VALUES (".$lastid.",".++$i.",'".$myrow['stockid']."','".$myrow['description']."',".$myrow['qty'].",".$myrow['price'].",".$myrow['tprice'].")";
  $ErrMsg =  _('An error occurred while inserting proposal details data');
  $result4=DB_query($sql4,$db,$Errmsg);
}  // end $result while loop
  //if ($result4) {echo "insert bioproposal details done successfully";}
  
  $sql_sch="SELECT * FROM bio_temppropsubsidy
            WHERE leadid=".$lead;
  $result_sch=DB_query($sql_sch,$db);
  $row_count=DB_num_rows($result_sch);
  if($row_count>0){
    while($myrow_sch=DB_fetch_array($result_sch)){
        $sql_subsidy="INSERT INTO bio_propsubsidy(propid,leadid,stockid,scheme,amount)
                        VALUES('".$lastid."',
                               '".$myrow_sch['leadid']."',
                               '".$myrow_sch['stockid']."',
                               '".$myrow_sch['scheme']."',
                               '".$myrow_sch['amount']."')";
        $result_subsidy=DB_query($sql_subsidy,$db,$Errmsg);
        
    }  
  }
  
  $f=0;
  $sql_check="SELECT * FROM bio_proposaldetails 
        WHERE propid=".$lastid;
  $result_check=DB_query($sql_check,$db);
  while ($myrow_check=DB_fetch_array($result_check))   {
      $acual_price=GetPrice($myrow_check['stockid'],$db);
      $new_price=$myrow_check['price'];
      if($acual_price==$new_price){
          $f=0;
      }
      else{
          $f=1;
          break;
      }
      
  }
  
  if($f==1){
      
    $task_ID=6;
    $duedate="0000-00-00";
    $date1="0000-00-00";
    $status=0;
    
    
    $sql_appr="INSERT INTO bio_approval(taskid,
                                   leadid,
                                   submitted_user,
                                   approval_user,
                                   assigneddate,
                                   duedate,
                                   taskcompleteddate,
                                   taskcompletedstatus,
                                   proposal_no) 
                            VALUES ('".$task_ID."',
                                    '".$lead."',
                                    '".$userid."',
                                    '".$approval_by."',
                                    '".$cur_date."',
                                    '".$duedate."',
                                    '".$date1."',
                                    '".$status."',
                                    '".$lastid."')";
    $result_appr=DB_query($sql_appr,$db);
  }
  elseif($f==0){
      
    $sql_1="UPDATE bio_proposal SET status=4
            WHERE propid=".$lastid;
    $result_1r=DB_query($sql_1,$db);
    }
  
  
  
 
  
} // end if total price  >0
if($result_subsidy){
   $sql_del="DELETE FROM bio_temppropsubsidy";
//   $ErrMsg =  _('An error occurred while deleting temp proposal items');
   $result_del=DB_query($sql_del,$db); 
}
if ($result3 & $result4) {

if($flag2==6 || $flag2==1){
    
$sql5="UPDATE bio_leads SET leadstatus = '1' WHERE bio_leads.leadid=".$lead;
$ErrMsg =  _('An error occurred while updating lead status to leads data');     
$result5=DB_query($sql5,$db,$Errmsg);
$sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',taskcompleteddate='".$crdt."' WHERE bio_leadtask.leadid=$lead AND bio_leadtask.taskid=1 AND taskcompletedstatus!=2";   
DB_query($sql_flag,$db);
//$sql_flag="UPDATE bio_leadtask SET taskcompletedstatus=1 WHERE leadid=$lead AND taskid=20 AND taskcompletedstatus!=2";    DB_query($sql_flag,$db); 
//$sql_flag="UPDATE bio_leadtask SET taskcompletedstatus=1 WHERE leadid=$lead AND taskid=21 AND taskcompletedstatus!=2";    DB_query($sql_flag,$db); 
}
elseif($flag2==5)
{
$sql5="UPDATE bio_leads SET leadstatus = '25' WHERE bio_leads.leadid=".$lead; 
$result5=DB_query($sql5,$db,$Errmsg);
}


// if ($result5) {
// echo "Updated lead status";
// }
$sql6="DELETE FROM bio_temppropitems WHERE leadid=".$lead;
$ErrMsg =  _('An error occurred while deleting temp proposal items');
$result6=DB_query($sql6,$db,$Errmsg);
//========================================================
}  // end if result 3 and 4
                             
if($flag2==1){    
?>
<script>
//window.opener.location='bio_domListTask.php?flag2=' + flag; 
window.close();
</script>
<?php 
}
                             
if ($result5)  {
 //echo "Deleted bio_temppropitems for ".$lead;
    if ($_GET['lead']) {
   unset($_GET['lead']);
    }
   if (isset($_SESSION['leadid'])) {
    unset($_SESSION['leadid']);
   }
    unset ($lead);
    unset ($stockid);
    if ($_GET['stockid']) {
    unset($_GET['stockid']);
    }
    
    echo "<script type=\"text/javascript\">
        /* window.setTimeout('window.top.location.href = \"bio_proposal.php\"; ',999); */
        
        location.reload(true);
          </script>";
  }
    //prnMsg(_('Proposal for lead ') .  $lead . ' created' ,'success');
    //echo "</ BR>";
    //echo "<input type='button' value='Back' onClick='close();'>";
  //echo "<script>close();</script>";
  //echo "<script>parent.window.location.reload();</script>";
}


else
{echo "No items could be retrieved";} ;

//include('includes/footer.inc');
?>
<script type="JavaScript">
close() {
    //alert ('Updated');
   //window.opener.location="index.php";
 // parent.window.location.reload();
  //window.close();
  //window.opener.location.reload(true);
  //setTimeout("window.close()",15);
<!--


// -->
}
</script>