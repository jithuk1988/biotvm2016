<?php
  $PageSecurity = 80;
include('includes/session.inc');
    $lead=$_GET['lead'];
             $fs=$_GET['fs']; 
               $amt= $_GET['amt'];
               $dat = $_GET['dat'];   
             /*    $sql_sou="SELECT bio_cust.custname, 
                 bio_district.district,
                 bio_leads.sourceid,
                 bio_leads.leadid, 
                 bio_cust.nationality,
                 bio_cust.state 
            FROM 
                 bio_cust
       LEFT JOIN 
                 bio_leads ON bio_cust.cust_id=bio_leads.cust_id 
       LEFT JOIN 
                 bio_district ON bio_district.did = bio_cust.district
             AND bio_cust.nationality = bio_district.cid
             AND bio_cust.state = bio_district.stateid
           WHERE
                 bio_leads.leadid=".$leadid;  
           


  $result_sou=DB_query($sql_sou,$db);    
  $myrow_sou=DB_fetch_array($result_sou);
  if($myrow_sou['nationality']==1 AND $myrow_sou['state']==14){
          $custtype=1;
      }elseif($myrow_sou['nationality']==1 AND $myrow_sou['state']!=14){
          $custtype=2;
      }elseif($myrow_sou['nationality']!=1){
          $custtype=3;
      }
 
 $sql1="SELECT bio_changepolicy.value
          FROM bio_changepolicy       
         WHERE bio_changepolicy.policyname='Institution FS Charge' 
           AND bio_changepolicy.enquirytype=".$custtype;
 
  $result1=DB_query($sql1,$db);    
  $myrow2=DB_fetch_array($result1); 
 
 $fs_amount=$myrow2['value'];
                                */
   $userid=$_SESSION['UserID'];            
  $approvalby="businesshead";
  if($userid=="businesshead"){
    $approvalby="admin";  
  } 
   if($amount>=$fs_amount)
   {
   $sql6= "INSERT INTO bio_fsproposal(leadid,
                                      fp_date,
                                      fp_amount,
                                      fp_createdby,
                                      fp_approvalby,
                                      fp_expapproval,
                                      teamid,
                                      fp_approvalstatus,status )
                          VALUES ('".$lead."',
                                 '".date('Y-m-d')."',
                                 '".$amount."',        
                                 '".$userid."',
                                 '".$approvalby."',
                                 '".$dat."',
                                 '".$teamid."',
                                 4,2)";                                           
      $result = DB_query($sql6,$db);
      ////////////////////////////
      
      
   $sql_fsid= "SELECT LAST_INSERT_ID()" ; 
   $result5=DB_query($sql_fsid,$db); 
   $checkresult5=DB_fetch_array($result5);
   $fsid=$checkresult5[0];      
      
      
      
      
      
      
      
      
      
      
      
      /////////////////////////////
      $sql7="UPDATE bio_leads SET leadstatus=26 where leadid=".$lead;
      $result7=DB_query($sql7,$db);
      $taskid=15;
      
            
//      generatetask($leadid,$taskid,$teamid,$db);
      $msg = _('Feasibility Proposal is created succesfully');      
       prnMsg($msg,'success');
       
      $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                          taskcompleteddate='".date('Y-m-d')."' 
                   WHERE bio_leadtask.leadid=$lead 
                     AND bio_leadtask.taskid=15 
                     AND taskcompletedstatus!=2";
//                     AND teamid=$assignedfrm   
    DB_query($sql_flag,$db); 
       
          
   }   
    else{
    $sql6= "INSERT INTO bio_fsproposal(leadid,
                                      fp_date,
                                      fp_amount,
                                      fp_createdby,
                                      fp_approvalby,
                                      fp_expapproval,
                                      teamid,
                                      fp_approvalstatus,status )
                          VALUES ('".$lead."',
                                  '".date('Y-m-d')."',
                                  '".$amount."',        
                                  '".$userid."',
                                  '".$approvalby."',
                                  '".$Expected_date."', 
                                  '".$teamid."',
                                 1,1)";                                           
     $result = DB_query($sql6,$db);
     $proposal_no=DB_Last_Insert_ID($Conn,'bio_fsproposal','fs_propono'); 
     $task_ID=16;
     $duedate="0000-00-00";
     $date1="0000-00-00";
     $status=0; 
 $sql_approval="INSERT INTO bio_approval(taskid,
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
                                    '".$approvalby."',
                                    '".date('Y-m-d')."',
                                    '".$duedate."',
                                    '".$date1."',
                                    '".$status."',
                                    '".$proposal_no."')";
     $result_approval=DB_query($sql_approval,$db);       
 $sql7="UPDATE bio_leads SET leadstatus=47 where leadid=".$leadid;
     $result7=DB_query($sql7,$db);

$sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                   taskcompleteddate='".$DateString."' 
                             WHERE bio_leadtask.leadid=$leadid 
                               AND bio_leadtask.taskid=15 
                               AND taskcompletedstatus!=2
                               AND teamid=$assignedfrm";   
    DB_query($sql_flag,$db);
     
    // $msg1= _('Feasibility Proposal is created succesfully');      
//       prnMsg($msg1,'success');
     $msg = _('Feasibility Proposal is given for Approval');
     prnMsg($msg,'Warn');      
    } 
    
               
                 

?>
