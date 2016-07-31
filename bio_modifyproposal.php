<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  include('includes/bio_GetPrice.inc');
   $lead=$_GET['lead'];
  $prop_id=$_GET['propid'];
   
  $userid=$_SESSION['UserID'];
  
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
  
  
  if($prop_id==0){
     $cur_date=date("Y-m-d");
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
   $sql_del="DELETE FROM bio_temppropsubsidy WHERE leadid=".$lead;
//   $ErrMsg =  _('An error occurred while deleting temp proposal items');
   $result_del=DB_query($sql_del,$db); 
}
if ($result3 & $result4) {
$sql5="UPDATE bio_leads SET leadstatus = '25' WHERE bio_leads.leadid=".$lead;
$ErrMsg =  _('An error occurred while updating lead status to leads data');
$result5=DB_query($sql5,$db,$Errmsg);
// if ($result5) {
// echo "Updated lead status";
// }
$sql6="DELETE FROM bio_temppropitems WHERE leadid=".$lead;
$ErrMsg =  _('An error occurred while deleting temp proposal items');
$result6=DB_query($sql6,$db,$Errmsg);
//========================================================






//==========================================================
}  // end if result 3 and 4
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
    {
        echo "No items could be retrieved";
    } 
}else{
 
 $sql="SELECT stockid,description,qty,price,tprice FROM bio_temppropitems WHERE leadid=".$lead;
//echo "$sql";
$result=DB_query($sql,$db);
$num_rows = $result->num_rows;
//echo "num rows= ".$num_rows;

$sql1="SELECT * FROM bio_proposaldetails 
        WHERE propid=".$prop_id;
$result1=DB_query($sql1,$db);

if ($num_rows > 0) {
    
    $sql2="SELECT SUM(tprice) AS totalprice FROM bio_temppropitems WHERE leadid=".$lead;
//echo "$sql2";
$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_array($result2);
$totalprice=$myrow2[0];
//echo "total price=".$totalprice;
if ($totalprice > 0) {
   $sql3="UPDATE bio_proposal SET totprice=".$totalprice.",
                  status=1
           WHERE propid=".$prop_id."
           AND leadid=".$lead;
    $result3=DB_query($sql3,$db);
    while ($myrow=DB_fetch_array($result))   {
        
        while ($myrow1=DB_fetch_array($result1))   {
             
            if($myrow['stockid']==$myrow1['stockid']){
               $f=0; 
               break;
            }else{
                $f=1;
            }
        }
         if($f==1){
             $sql_no="SELECT MAX(slno) FROM bio_proposaldetails
                         WHERE propid=".$prop_id;
                $result_no=DB_query($sql_no,$db);
                $myrow_no=DB_fetch_array($result_no);
                $i=$myrow_no[0]+1;
                
                $sql4="INSERT INTO bio_proposaldetails (propid,
                                                        slno,
                                                        stockid,
                                                        description,
                                                        qty,
                                                        price,
                                                        tprice) 
                                              VALUES (".$prop_id.",
                                                      ".$i.",
                                                     '".$myrow['stockid']."',
                                                     '".$myrow['description']."',
                                                      ".$myrow['qty'].",
                                                      ".$myrow['price'].",
                                                      ".$myrow['tprice'].")";
                $result4=DB_query($sql4,$db);
         }elseif($f==0){
             $sql4="UPDATE bio_proposaldetails SET qty=".$myrow['qty'].",
                                                      price=".$myrow['price'].",
                                                      tprice=".$myrow['tprice']."
                       WHERE propid=".$prop_id."
                       AND stockid='".$myrow['stockid']."'";
                $result4=DB_query($sql4,$db);
         }
    }
    
    
    
    
    
    
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
    
  if($result_subsidy){
   $sql_del="DELETE FROM bio_temppropsubsidy WHERE leadid=".$lead;
//   $ErrMsg =  _('An error occurred while deleting temp proposal items');
   $result_del=DB_query($sql_del,$db); 
}

 if ($result3 & $result4) {
     $sql6="DELETE FROM bio_temppropitems WHERE leadid=".$lead;
     $ErrMsg =  _('An error occurred while deleting temp proposal items');
     $result6=DB_query($sql6,$db,$Errmsg);
}
}     
}

 echo"<div class=success>A new proposal created successfully</div>"; //echo $lead=echo $_GET['lead'];  
        
?>
