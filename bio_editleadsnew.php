<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Leads');  
include('includes/header.inc');
include('includes/sidemenu.php');
$office=$_SESSION['officeid'];   

//================================================   

if(!isset($_POST['save'])){  
$tempdrop="DROP TABLE IF EXISTS bio_feedtemp";
DB_query($tempdrop,$db);   
$temptable="CREATE TABLE bio_feedtemp (
temp_id INT NOT NULL AUTO_INCREMENT ,
feedstockid INT NULL ,
weight DECIMAL NULL ,
PRIMARY KEY ( temp_id )
)";
DB_query($temptable,$db);  $sql="ALTER TABLE `bio_feedtemp` ADD `status` INT NOT NULL DEFAULT '0'" ; DB_query($sql,$db);
 }

if(isset($_POST['save']))
 {      
      $scheme=$_POST['schm'];
      foreach($scheme as $id){
          $sourcescheme.=$id.",";
      } 
      $schemeid=substr($sourcescheme,0,-1);
      $outputtype=$_POST['outputtype'];
      foreach($outputtype as $id1){
          $sourcetype1.=$id1.",";
      } 
      $outputtypeid=substr($sourcetype1,0,-1);             
        unset($delleadid); 
      $date=date("Y-m-d");
      $custname=$_POST['custname'];
      $address=$_POST['address'];
        if($_POST['code']!=""){
      $phone=$_POST['code']."-".$_POST['phone'];   
      }
      $mobile=$_POST['mobile'];  
      $email=$_POST['email'];
      $teamid=$_SESSION['teamid'];
      $productservicesid=$_POST['productservices'];
      $enquiryid=$_POST['enquiry'];
      $investmentsize=$_POST['investmentsize']; $schemeid;
//      $schemeid=$_POST['scheme'];
      $idtype=$_POST['identity'];
      $idno=$_POST['identityno'];
      $feedstockid=$_POST['feedstock'];
//      $rmkg=$_POST['rmkg'];  
//      $outputtypeid=$_POST['outputtype'];
      $sourcetype=$_POST['sourcetype'];
      $sourceid=$_POST['source'];
//      echo $POST_['Houseno'];
//      exit; 
   $urgency_level=$_POST['UrgencyLevel'];
      if($_POST['advanceamt']=='')
      {
          $advanceamt=0; 
      }
      else
      {
          $advanceamt=$_POST['advanceamt'];
      }
      if($_POST['Houseno']==""){$_POST['Houseno']=" ";}    
      if($_POST['HouseName']==""){$_POST['HouseName']=" ";}
      if($_POST['Area1']==""){$_POST['Area1']=" ";} 
      if($_POST['Area2']==""){$_POST['Area2']=" ";} 
      if($_POST['Pin']==""){$_POST['Pin']=" ";}
      if($_POST['country']==""){$_POST['country']=" ";}    
      if($_POST['State']==""){$_POST['State']=" ";}
      if($_POST['District']==""){$_POST['District']=" ";} 
      
      if($_POST['contactPerson']==""){$_POST['contactPerson']=$_POST['custname'];}  
            if($phone==""){$_POST['phone']=" ";}      
                  if($mobile==""){$_POST['mobile']=" ";}      
                        if($email==""){$_POST['email']=" ";}                     
      $status=$_POST['status'] ;
      $remarks=$_POST['remarks'];
       //echo "sssssssssss".$_POST['Area2']."nnnnnnnnnnnnnnnn";
       
 $InputError=0;
 $count=0;
 $sql="INSERT INTO `bio_cust` (
                           `custname`, 
                           `custphone`, 
                           `custmob`, 
                           `custmail`, 
                           `houseno`,
                           `housename`,
                           `area1`,
                           `area2`,
                           `pin`,                                                                                                                                                                                                                             
                           `nationality`,
                           `state`,
                           `district`,
                           `contactperson`,
                           careof,
                           taluk,
                           LSG_type,
                           LSG_name,
                           block_name,
                           LSG_ward,
                           village) 
                   VALUES ('$custname',
                           '$phone',
                           '$mobile',
                           '$email',
                           '".$_POST['Houseno']."',
                           '".$_POST['HouseName']."',
                           '".$_POST['Area1']."',
                           '".$_POST['Area2']."',
                           '".$_POST['Pin']."',
                           ".$_POST['country'].",
                           ".$_POST['State'].",
                           ".$_POST['District'].",
                           '".$_POST['contactPerson']."',
                           '".$_POST['careof']."',
                           '".$_POST['taluk']."',
                           '".$_POST['lsgType']."',
                           '".$_POST['lsgName']."',
                           '".$_POST['gramaPanchayath']."',
                           '".$_POST['lsgWard']."',
                           '".$_POST['village']."')";
                  
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
           $custid=DB_Last_Insert_ID($Conn,'bio_cust','cust_id');
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------     
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
$sql="INSERT INTO `bio_leads` (
                  `leaddate`, 
                  `teamid`,
                  `sourceid`, 
                  `productservicesid`, 
                  `enqtypeid`, 
                  `investmentsize`, 
                  `schemeid`, 
                  `outputtypeid`, 
                  `advanceamount`, 
                  `remarks`, 
                  `status`,
                  `cust_id`,
                  `created_by`,
                  ID_type,ID_no) 
          VALUES ('$date',
                  '$teamid',
                   $sourceid,
                  '".$productservicesid."',
                  ".$enquiryid.",
                  '$investmentsize',
                  '$schemeid',
                  '".$outputtypeid."',
                  ".$advanceamt.",
                  '$remarks',
                  '$status',
                   '$custid',
                  '$_SESSION[UserID]',
                  '$idtype',
                  '$idno')";        // exit;
  //$result=DB_query($sql, $db);  
//  exit;
//echo$teamid;
//echo$sourceid;//
             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been added'),'success');
  $lead=DB_Last_Insert_ID($Conn,'bio_leads','leadid');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
     //exit;                                                                                                  
 $custid=DB_Last_Insert_ID($Conn,'bio_feedtemp','temp_id');
 $leadprint=$custid;

$sql="INSERT INTO bio_leadfeedstocks(SELECT $custid,bio_feedtemp.feedstockid,bio_feedtemp.weight,bio_feedtemp.status FROM bio_feedtemp)";
$result1=DB_query($sql, $db);   
 $tempdrop="DROP TABLE IF EXISTS bio_feedtemp";
DB_query($tempdrop,$db); 


$temptable="CREATE TABLE bio_feedtemp (
temp_id INT NOT NULL AUTO_INCREMENT ,
feedstockid INT NULL ,
weight DECIMAL NULL ,
PRIMARY KEY ( temp_id )
)";
DB_query($temptable,$db);  $sql="ALTER TABLE `bio_feedtemp` ADD `status` INT NOT NULL DEFAULT '0'" ; DB_query($sql,$db); 
 $custid;
 
 $mode=$_POST['mode'];
$amountno=$_POST['amtno'];$amountbank=$_POST['amtbank'];$advanceamt; $offic=$_SESSION['UserStockLocation'];
$amtdate=FormatDateForSQL($_POST['amtdate']);
if($amtdate=='--'){$amtdate="0000-00-00";}
if($advanceamt>0){
     $sql="INSERT INTO bio_advance (leadid, 
                                head_id,
                                mode_id, 
                                date, 
                                serialnum,
                                bankname,
                                paydate,
                                amount,
                                officid,
                                status,
                                collected_by) 
                    VALUES ('$lead',
                            0,
                            '".$mode."',
                            '".$amtdate."',
                            '".$amountno."',
                            '".$amountbank."',
                            '".FormatDateForSQL(Date($_SESSION[DefaultDateFormat]))."',
                            '$advanceamt',
                            '$offic',
                            0,
                            '".$_SESSION['UserID']."'
                            )"; 
                              
    $result1=DB_query($sql, $db); 
    $adv_id=DB_Last_Insert_ID($Conn,'bio_advance','adv_id');
} 
  
         
$emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid'];
    
    $sql_desg="SELECT designationid FROM bio_emp WHERE empid=".$emp_ID;
    $result_desg=DB_query($sql_desg,$db);
    $row_desg=DB_fetch_array($result_desg);
    $designation=$row_desg['designationid'];
     

//----------------------------------------Assigning Domestic customers to CCE/BH-------------------------------------------// 
if($enquiryid==1)
{
    $sql_cce="SELECT www_users.empid,
                     bio_teammembers.teamid 
                FROM www_users,bio_teammembers 
               WHERE bio_teammembers.empid=www_users.empid";
               
   if($_POST['country']==1 && $_POST['State']==14)        //KERALA
   {                  
       if( $_POST['District']==6 || $_POST['District']==11 || $_POST['District']==12 )    //KLM-PTA-TVM
       {
           $sql_cce.=" AND www_users.userid='".ccetvm1."'";
       }
       elseif( $_POST['District']==1 || $_POST['District']==2 || $_POST['District']==3 || $_POST['District']==7 || $_POST['District']==13 ) //ALP-EKM-IDK-KTM-TRS
       {
           $sql_cce.=" AND www_users.userid='".cceeklm1."'";                    
       }
       elseif( $_POST['District']==4 || $_POST['District']==5 || $_POST['District']==8 || $_POST['District']==9 || $_POST['District']==10 || $_POST['District']==14 ) //KNR-KSR-KZH-MLP-PLK-WND
       {
           $sql_cce.=" AND www_users.userid='".ccekoz1."'";
       }
   } 
   elseif($_POST['country']==1 && $_POST['State']!=14)     //OUTSIDE KERALA
   {
       $sql_cce.=" AND www_users.userid='".cccmho."'";
   }
   
   $result_cce=DB_query($sql_cce,$db);
   $row_cce=DB_fetch_array($result_cce);
   $teamid=$row_cce['teamid'];   
   
   $assigned_date=date("Y-m-d");
   
    
   if($_POST['country']==1 && $_POST['State']!=14)
   {
       $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                         leadid,
                                         teamid,
                                         assigneddate,
                                         assigned_from,
                                         viewstatus)
                                 VALUES(0,
                                       '".$lead."',
                                       '".$teamid."',
                                       '".$assigned_date."',
                                       '".$assignedfrm."',
                                       1)";
       $result_leadTask=DB_query($sql_leadTask,$db);
       
   }  
   else  
   { 
    
    $sql_schedule="INSERT INTO bio_leadschedule VALUES($lead,$urgency_level)"; 
    $result_schedule=DB_query($sql_schedule,$db);

    $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$lead) ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
    
        while($row_schedule1=DB_fetch_array($result_schedule1))
        {       
                $taskid=$row_schedule1['task_master_id'];
                $date_interval+=$row_schedule1['actual_task_day'];
                
                //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
                
                $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                         leadid,
                                                         teamid,
                                                         assigneddate,
                                                         duedate,
                                                         assigned_from,
                                                         viewstatus)
                                             VALUES('".$taskid."',
                                                    '".$lead."',
                                                    '".$teamid."',
                                                    '".$assigned_date."',
                                                    '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                                    '".$assignedfrm."',
                                                    1)";
                 $result_leadTask=DB_query($sql_leadTask,$db); 
                
                $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
                $date_interval+=1;                                   
        } 
    }        
}
elseif($enquiryid==2 || $enquiryid==3)
{

//--------------------------------------PTS------------------------------------     
if($designation==4 OR $designation==5 OR $designation==9){
        $sql_schedule="INSERT INTO bio_leadschedule VALUES($lead,$urgency_level)"; 
    $result_schedule=DB_query($sql_schedule,$db);

    $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$lead) ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
    $assigned_date=date("Y-m-d");
    
    while($row_schedule1=DB_fetch_array($result_schedule1))
    {       
        $taskid=$row_schedule1['task_master_id'];
        $date_interval+=$row_schedule1['actual_task_day'];
        
        //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
        
        $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                 leadid,
                                                 teamid,
                                                 assigneddate,
                                                 duedate,
                                                 assigned_from,
                                                 viewstatus)
                                     VALUES('".$taskid."',
                                            '".$lead."',
                                            '".$assignedfrm."',
                                            '".$assigned_date."',
                                            '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                            '".$assignedfrm."',
                                            1)";
         $result_leadTask=DB_query($sql_leadTask,$db); 
        
        $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
        $date_interval+=1;                                   
    }         
    
    
    
    
}
else{
    
$sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                         leadid,
                                         teamid,
                                         assigneddate,
                                         viewstatus)
                                VALUES(0,
                                       '".$leadprint."',
                                       '".$assignedfrm."',
                                       '".$date."',
                                       1)";
$result_leadTask=DB_query($sql_leadTask,$db);
}
//----------------------------------------------------------------------------- 
/*
$sql_task="SELECT bio_target.taskid,bio_leadtasktarget.task_count
                                FROM bio_target,bio_leadtasktarget
                                WHERE assigneddate <= '$date'
                                AND duedate >= '$date'
                                AND officeid =".$office."
                                AND team_id =".$teamid." 
                                AND bio_target.taskid=bio_leadtasktarget.taskid
                                AND bio_leadtasktarget.target=1";    
                $result_task=DB_query($sql_task, $db);     
                $myrow_task=DB_fetch_array($result_task);
                $myrow_count1 = DB_num_rows($result_task);
                $task=$myrow_task[0];
                $target=$myrow_task[1];
                $target=$target+1; 
                if($myrow_task[0]>0){
                    $sql_leadtask="UPDATE bio_leadtasktarget
                        SET task_count=".$target."
                        WHERE taskid='$task'";
                    $result_leadtask= DB_query($sql_leadtask,$db);
                }   
                       */
}
//---------------------------------------------------------------------------------------------------
 

if($advanceamt>0){
 $_SESSION['adv_id']=$adv_id;
 print'<script>

     var answer = confirm("Do you want to PRINT the Advance Receipt?")

   if (answer){
san();

   }
 </script> ';

}
if(($enquiryid==1) && ($advanceamt>0)){
     $_SESSION['lead']=$leadprint;
 print'<script>

     var answer = confirm("Do you want to Create the Proposals?")

   if (answer){
prop();

   }
 </script> ';

    
} 
$_GET['adv'];
//exit;
    

unset($_POST['feedstock']);
unset($_POST['enquiry']);
unset($_POST['identity']);
unset($_POST['outputtype']);
unset($_POST['sourcetype']);
unset($_POST['printsource']);
unset($_POST['feedstock']);
unset($_POST['productservices']);
unset($_POST['District']); 
unset($_POST['country']);


 
 }   



  
 
if(isset($_POST['edit']) and isset($_POST['leadid1']))
 {     $_POST['code'];
    $sourcetype=$_POST['sourcetype'];
   $leadid= $_POST['leadid1']; 
 //echo$_POST['customerid'];   
    // echo "hi";
  //echo $_POST['advanceamt'];
 $outputtype=$_POST['outputtype'];
 
      foreach($outputtype as $id2){
          $otype.=$id2.",";
      }
      
      $sourcetype;
      $outputtypeid=substr($otype,0,-1);
      
     $scheme=$_POST['schm'];
      foreach($scheme as $id){
          $sourcescheme.=$id.",";
      } 
      $schemeid=substr($sourcescheme,0,-1);           
  

   $sql="UPDATE `bio_cust` 
             SET `custname` = '".$_POST['custname']."',
                 `contactperson` = '".$_POST['contactPerson']."',
                 `houseno` = '".$_POST['Houseno']."',      
                 `housename` ='".$_POST['HouseName']."',
                 `area1` = '".$_POST['Area1']."',      
                 `area2` ='".$_POST['Area2']."',
                 `pin` = '".$_POST['Pin']."',      
                 `custphone` = '".$_POST['code']."-".$_POST['phone']."',     
                 `custmob` = '".$_POST['mobile']."',
                 `custmail` = '".$_POST['email']."',
                 `careof` = '".$_POST['careof']."',
                 `taluk` = '".$_POST['taluk']."',
                 `LSG_type` = '".$_POST['lsgType']."', 
                 `LSG_name` = '".$_POST['lsgName']."',
                 `block_name` = '".$_POST['gramaPanchayath']."',
                 `LSG_ward` = '".$_POST['lsgWard']."',
                 `village` = '".$_POST['village']."'
           WHERE `bio_cust`.`cust_id` ='".$_POST['customerid']."'";  
                  
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

   $sql="UPDATE `bio_leads` SET  
                `sourceid` = ".$_POST['source'].",
                `productservicesid` ='".$_POST['productservices']."',
                `schemeid` ='".$schemeid."',
                `outputtypeid` = '".$outputtypeid."',
                `advanceamount` = ".$_POST['advanceamt'].",
                `investmentsize` = '".$_POST['investmentsize']."',
                `remarks` = '".$_POST['remarks']."',
                `id_type` = '".$_POST['identity']."', 
                `id_no` = '".$_POST['identityno']."',
                `status` = '".$_POST['status']."' 
          WHERE `bio_leads`.`leadid` =$leadid";
        
      //  $result=DB_query($sql,$db);
           $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
           prnMsg( _('The Sales Leads record has been Updated'),'success');
      // display($db);
unset($_POST['feedstock']);
unset($_POST['enquiry']);
unset($_POST['outputtype']);
unset($_POST['sourcetype']);
unset($_POST['printsource']);
unset($_POST['feedstock']);     
unset($_POST['productservices']);             
unset($_POST['District']);
 } 

  
  
  
 //        echo $_GET['q'];  echo$_GET['en'] ;

  $leadid=$_GET['q'];
  if($_GET['q']==""){
     $leadid=$_SESSION['lead'];
     unset($_SESSION['lead']);
     $_GET['en']=$_SESSION['enquiry'];
     unset($_SESSION['enquiry']);
  }
//   if(isset($_POST['edaddfeedstock'])){echo $sql="INSERT INTO bio_leadfeedstocks VALUES ('".$leadid."','".$_GET['feedstock']."','".$_GET['weight']."')";
  //$result=DB_query($sql, $db);  
//  exit;
//             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
//           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
//           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);  }
  //echo "hiiii";
 if($leadid!='')
 {  
     $sqlst="select leadstatus from bio_leads where leadid=$leadid";
     $rst=DB_query($sqlst,$db);
      $mrow=DB_fetch_array($rst);
      $sts=$mrow[0];
     $sqlteam=""; 
       $sqlstatus="Select biostatus from bio_status where statusid=$sts";
       $result_st=DB_query($sqlstatus,$db);
      $myrow_st=DB_fetch_array($result_st);
      $statusn=$myrow_st['biostatus'];
      $sql_lteam="Select bio_leadteams.teamname as taskteam from bio_leadtask,bio_leadteams where bio_leadtask.leadid=$leadid and bio_leadtask.viewstatus=1 and bio_leadtask.taskid=5 AND bio_leadteams.teamid=bio_leadtask.teamid";
      
      $result_lteam=DB_query($sql_lteam,$db);
      $myrow_lteam=DB_fetch_array($result_lteam); 
       $rows=DB_num_rows($result_lteam);
       
      
    echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">LEADS</font></center>'; 
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
     
     echo"<table><tr><td>";
     echo "<div id=editleads>";  
         // echo "<b><font color=red>This is a dropped Lead! Register this lead as new</font></b>";

     if(sts==20 || sts==21 || sts==22|| sts==23|| sts==24)
     {
               echo "<b><font color=red>This is a dropped Lead! Register this lead as new</font></b>";
     }     
      else
     {
         echo "Current status of the lead:<b>$statusn</b><br>";
     if($rows>0)
     {
         
     echo "Team Currently handling the lead:<b>$myrow_lteam[taskteam]</b>";    
         
     }
     
     }
     echo "<table border=0 style='width:100%;height:150%;'>";
     echo "<tr><td style='width:50%'>";
     echo "<fieldset style='float:left;width:95%;height:100%' valign='top'>";     
     echo "<legend><h3>Customer Details</h3>";
     echo "</legend>";
     echo "<table>";
 
 
    $sql="SELECT * FROM bio_leads WHERE leadid=".$leadid;
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_array($result); 
  
      $rmdailykg=$myrow['rmdailykg'];
      $idtype=$myrow['id_type'];
      $idno=$myrow['id_no'];
      $advanceamount=$myrow['advanceamount'];
      $productservicesid=$myrow['productservicesid'];
      $enqtypeid=$myrow['enqtypeid']; 
  //    $feedstockid=$myrow['feedstockid']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $schemeid=$myrow['schemeid'];             
      $teamid=$myrow['teamid'];
      $sourceid=$myrow['sourceid'];
      $investmentsize=$myrow['investmentsize'];
      $remarks=$myrow['remarks'];
      $status=$myrow['status']; 
      $created_by=$myrow['created_by'];
      $leaddate=ConvertSQLDate($myrow['leaddate']);
      $leadstatus=$myrow['leadstatus']; 
      
      
 echo "<input type='hidden' name='customerid' value='".$myrow['cust_id']."'>";      
      $sqledt="SELECT * FROM bio_cust WHERE bio_cust.cust_id=".$myrow['cust_id'];
      $result1=DB_query($sqledt,$db);
      $myrow1=DB_fetch_array($result1); 
      $custname=$myrow1['custname'];
      $contactperson=$myrow1['contactperson'];     
      $homno=$myrow1['houseno'];
      $housename=$myrow1['housename'];
      $area1=$myrow1['area1'];
      $area2=$myrow1['area2'];       //echo "ssssssssss".$area2."sssssssssssnn";    
      $pin=$myrow1['pin'];
      $_POST['nationality']=$myrow1['nationality']; 
      $_POST['state']=$myrow1['state'];
      $_POST['district']=$myrow1['district'];  
      $careof=$myrow1['careof']; 
      $taluk=$myrow1['taluk'];
      $LSGtypeid=$myrow1['LSG_type'];
      $LSG_name=$myrow1['LSG_name'];
      $block_name=$myrow1['block_name'];
      $ward=$myrow1['LSG_ward'];
      $village=$myrow1['village']; 
      //echo $custaddress;  $custphone
      
      if($homno == '0'){
         $homno=" "; 
      }
      if($housename == '0'){
         $housename=" ";
      }
      if($area1 == '0'){
         $area1=" "; 
      }
      if($area2 == '0'){
         $area2=" "; 
      }
      if($pin == '0'){
         $pin=" "; 
      }
      
    $pieces=$myrow1['custphone'];  $phpieces = explode("-", $pieces,2);  $custcode=$phpieces[0];
    if($custcode==0){$custcode="";}  
    
    $custphone=$phpieces[1];
    $custmobile=$myrow1['custmob'];
    $custemail=$myrow1['custmail'];
//--------------------------------------------------------
    if($custphone==0){$custphone="";}
    if($custmobile==0){$custmobile="";} 
    if($custemail=='0'){$custemail="";} 
    

//==========================================================bio_feedstocks.feedstocks, AND bio_feedstocks.id=$feedstockid
       
        $sq4="SELECT bio_enquirytypes.enquirytype,
                     bio_outputtypes.outputtype,
                     bio_schemes.scheme,
                     bio_productservices.productservices,
                     
                     bio_leadsourcetypes.leadsourcetype,
                     stockcategory.categorydescription
                FROM bio_enquirytypes, 
                     bio_feedstocks, 
                     bio_outputtypes,         
                     bio_schemes,
                     bio_productservices,
                     
                     bio_leadsourcetypes,stockcategory
               WHERE bio_enquirytypes.enqtypeid=$enqtypeid  
                 AND bio_outputtypes.outputtypeid='$outputtypeid' 
                 AND stockcategory.categoryid='$productservicesid' 
                 ";/*bio_leadsources.sourcetypeid,bio_leadsources,
                 AND bio_leadsources.id=$sourceid 
                 AND bio_leadsourcetypes.id=bio_leadsources.sourcetypeid*/
      $result4=DB_query($sq4,$db); 
      $myrow4=DB_fetch_array($result4);
//       echo $myrow4[0];
//       echo $myrow4[6];
      // echo  $custname;
//       echo '<center><h2>Leads</h2></center>';

    $sql_emp="SELECT  bio_emp.empname
                FROM  bio_emp,www_users
                WHERE www_users.empid = bio_emp.empid
                  AND www_users.userid='".$created_by."'";
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
      $empname=$myrow_emp['empname'];
      
      $sql_urgency="SELECT scheduleid FROM bio_leadschedule WHERE leadid=$leadid";
      $result_urgency=DB_query($sql_urgency,$db);
      $row_urgency=DB_fetch_array($result_urgency);
      $urgency=$row_urgency['scheduleid'];
     
    echo "<tr><td>Created By</td><td><input type='hidden' value='$created_by' name='Created' id='Created' onkeyup='' style='width:99%'>$empname</td></tr>"; 
    echo "<tr><td>Created On</td><td><input type='hidden' value='$leaddate' name='Leaddate' id='Leaddate' onkeyup='' style='width:99%'>$leaddate</td></tr>"; 
    


     
    //Customer Details
    
 if($_GET['en']==1){
    
    echo "<tr><td>Customer Type</td><td><input type='hidden' name='enquiry'  value=".$_GET['en']."  style='width:99%'>Domestic</td></tr>";
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type='text' value='$custname' name=custname id=custid onkeyup='caps1()' style='width:96%'></td>";
    echo "<tr><td style='width:50%'>Father's/ Husbands Name</td>";   
    echo "<td><input type='text' name='careof' id='careof' tabindex=2 onkeyup='caps1()' value='$careof' style='width:190px'></td>";
    echo "<tr><td>House No:</td><td><input type='text' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
    echo "<tr><td>House Name</td><td><input type='text' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";    
    echo "<tr><td>Residential Area</td><td><input type='text' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";  }
    //echo "<tr><td></td><td><input type='hidden' name='contactPerson' id='contactPerson' value='$contactperson' onkeyup='' style='width:99%'></td></tr>";  
     
        
 if($_GET['en']==2){$enqtypeid=$_GET['en'];  
    echo "<tr><td>Customer Type</td><input type='hidden' name='enquiry'  value=".$_GET['en']."  style='width:99%'><td>Institution</td></tr>";
    echo "<tr><td style='width:50%'>Org Name</td>";
    echo "<td><input type='text' name='custname' id='custid' value='$custname' onkeyup='caps1()'  style='width:190px'></td>";
    echo "<tr><td>Contact Person</td><td><input type='text' name='contactPerson' id='contactPerson' value='$contactperson' onkeyup='' style='width:99%'></td></tr>";  
    echo "<tr><td>Building Name/No:</td><td><input type='text' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";
  //echo "<tr><td>Building Name/No:</td><td><input type='text' name='Houseno' id='Houseno' value='$homno' style='width:99%'></td></tr>";    
    echo "<tr><td>Org Street:</td><td><input type='text' name='HouseName' id='HouseName' value='$housename' onkeyup='' style='width:99%'></td></tr>";   
    echo "<tr><td>Org Area:</td><td><input type='text' name='Area1' id='Area1' onkeyup='' value='$area1' style='width:99%'></td></tr>";
  }
  
 if($_GET['en']==3){
    echo "<tr><td>Customer Type</td><input type='hidden' name='enquiry'  value=".$_GET['en']."  style='width:99%'><td>LSGD</td></tr>";
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type=text name=custname id=custid value='$custname' readonly style='width:96%'></td>";
    echo "<tr><td>House No:</td><td><input type='text' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
    echo "<tr><td>House Name</td><td><input type='text' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";    
    echo "<tr><td>Residential Area</td><td><input type='text' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";  }
    //echo "<tr><td></td><td><input type='hidden' name='contactPerson' id='contactPerson' value='$contactperson' onkeyup='' style='width:99%'></td></tr>";  

    echo "<tr><td>Post Box</td><td><input type='text' name='Area2' value='$area2' id='Area2' onkeyup='' style='width:99%'></td></tr>";    
    echo" <tr><td>Pin</td><td><input type='text' name='Pin' id='Pin' value='$pin' onkeyup='' style='width:99%'></td></tr>";  


    
    $sql="SELECT * FROM bio_country"; 
    $result=DB_query($sql,$db);
    
    echo"<tr><td style='width:50%'>Country*</td><td>";
    echo '<select name="country" id="country" tabindex=9 onchange="showstate(this.value)" style="width:190px">';
    
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==$_POST['nationality'])  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td></tr>';
          

   $sql="SELECT * FROM bio_state WHERE cid='".$_POST['nationality']."' ORDER BY stateid";
   $result=DB_query($sql,$db);
 
 echo"<tr id='showstate'><td>State*</td><td>";
 echo '<select name="State" id="state" style="width:190px" tabindex=10 onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==$_POST['state'])
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
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
  echo'</tr>';

  
     echo"<tr id='showdistrict'>";
     echo"<td>District*</td><td>"; 
     echo '<select name="District" id="Districts" style="width:190px" tabindex=8 onchange="showtaluk(this.value)">';          
     $sql="SELECT * FROM bio_district WHERE cid='".$_POST['nationality']."' AND stateid='".$_POST['state']."' ORDER BY did";      $result=DB_query($sql,$db);   
 
     $f=0;
     while($myrow1=DB_fetch_array($result))
     {
        if ($myrow1['did']==$_POST['district'])
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
        echo $myrow1['did'] . '">'.$myrow1['district'];
        echo '</option>';
        $f++;
   }
   echo '</select></td>';
   echo'</tr>'; 
   
      //--------Corporation/Muncipality/Panchayath-------------------//
   
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    
        if($LSGtypeid==1){$lsgtype="Corporation";}
        elseif($LSGtypeid==2){$lsgtype="Municipality";}
        elseif($LSGtypeid==3){$lsgtype="Panchayath";}   
    
    echo '<td><select name="lsgType" id="lsgType" style="width:190px" tabindex=9 onchange=showblock(this.value)>';             
    echo '<option value='.$LSGtypeid.'>'.$lsgtype.'</option>'; 
    if($LSGtypeid==1){   
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
    }elseif($LSGtypeid==2){
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
    }elseif($LSGtypeid==3){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
    }elseif($LSGtypeid == NULL){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
        echo '<option value=3>Panchayat</option>'; 
    }      
    echo '</select></td></tr>'; 
                                         
    echo '<tr><td align=left colspan=2>';
    echo'<div style="align:left" id=block>';
    
        if($LSGtypeid==1) 
        {
        
        $sql="SELECT * FROM bio_corporation WHERE country='".$_POST['nationality']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];


              //echo"11111111"; 
          if($_POST['nationality']==1 && $_POST['state']==14)  
          {
              if($_POST['district']==12){$distname='Thiruvananthapuram';}
              if($_POST['district']==6){$distname='Kollam';} 
              if($_POST['district']==2){$distname='Eranakulam';} 
              if($_POST['district']==13){$distname='Thrissur';} 
              if($_POST['district']==8){$distname='Kozhikode';} 
                    echo '<table align=left ><tr><td width=200px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:190px">';
                    echo "<option value='".$_POST['district']."'>".$distname."</option>"; 
                    echo '</select></td>';    
                    echo '</tr></table>';      
          }
        
        }elseif($LSGtypeid==2) 
        {
            //echo"2222222";
        echo '<table align=left ><tr><td width=200px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='".$_POST['nationality']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:190px">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
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
         echo $myrow1['id'] . '">'.$myrow1['municipality'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</td></tr></table>'; 
        
        }elseif($LSGtypeid==3) 
        {
            //echo"3333333"; 
         echo '<table align=left ><tr><td width=200px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country='".$_POST['nationality']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:190px" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
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
         echo $myrow1['id'] . '">'.$myrow1['block'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr>'; 
      
      echo '<tr><td>' . _('Panchayat Name') . ':</td>';         //grama panchayath
         
         $sql="SELECT * FROM bio_panchayat WHERE country='".$_POST['nationality']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:190px" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$block_name)
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
         echo $myrow1['id'] . '">'.$myrow1['name'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr></table>';      
            
        }
                    
        echo'</div>';
        echo'</td></tr>';
        
        
        
               echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
                  <td><input tabindex=9 type="Text" name="lsgWard" id="lsgWard" style=width:190px maxlength=15 value="'.$ward.'"></td></tr>';  
                  
      echo"<tr><td>Taluk*</td><td>";
      $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country='".$_POST['nationality']."' AND bio_taluk.state='".$_POST['state']."' AND bio_taluk.district='".$_POST['district']."'";
      $result=DB_query($sql,$db);
      echo '<select name="taluk" id="taluk" style="width:190px" tabindex=11>';
      $f=0;
      while($myrow1=DB_fetch_array($result))
      {
      if ($myrow1['id']==$taluk)
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
      echo $myrow1['id'] . '">'.$myrow1['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td></tr>';     
      
                    echo '<tr><td>' . _('Village Name'). ':</td>
                  <td><input tabindex=9 type="Text" name="village" id="village" style=width:190px maxlength=15 value="'.$village.'"></td></tr>';    
   
   
   //---               

     
    echo '<tr><td>Phone number</td>';
    echo "<td><table><td><input type='text' name='code' id='code' value='$custcode' style='width:50px'></td>
          <td><input type=text name=phone id=phone value='$custphone' style='width:96%'></td></table></td></tr>";
    echo '<tr><td>Mobile Number</td>';
    echo "<td><input type=text name=mobile id=mobile value='$custmobile' style='width:96%'></td></tr>";
    echo '<tr><td>Email id</td>';
    echo "<td><input type=text name=email id=email value='$custemail' style='width:96%'></td></tr>";
    
    echo '<tr><td>Identity</td><td>';  
        $sql1="SELECT * FROM bio_identity";
        $result1=DB_query($sql1, $db);
    echo '<select name=identity style="width:190px">';
    while($myrow1=DB_fetch_array($result1))
    {     
    if ($myrow1['ID_no']==$idtype)  
    { 
    echo '<option selected value="';
    echo $idtype . '">'.$myrow1['ID_type'];
    } else {
        echo '<option value="';
        echo $myrow1['ID_no'] . '">'.$myrow1['ID_type'];
    }
    
    echo '</option>';
   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';
    
    echo '<tr><td>Identity No</td>';
    echo "<td><input type=text name=identityno id=identityno value='$idno' style='width:96%'></td></tr>";
    //Product Sevices
    echo '<tr><td>Product Services</td><td>';  
        $sql1="SELECT * FROM bio_productservices";
        $result1=DB_query($sql1, $db);
    echo '<select name=productservices style="width:190px">';
  while($myrow1=DB_fetch_array($result1))
  {     
  if ($myrow1['id']==$productservicesid)  
    { 
    echo '<option selected value="';
    echo $productservicesid . '">'.$myrow1['productservices'];
    } else {
        echo '<option value="';
        echo $myrow1['id'] . '">'.$myrow1['productservices'];
    }
    
    echo '</option>';
   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';

  echo '<tr><td>Advance Amount</td>';
  echo "<td><input type=text name=advanceamt id=advance value='$advanceamount' readonly style='width:96%'></td></tr>";
  echo "</table>";  
 // echo"<div style='height:75px'></div>";
  echo "</fieldset>"; 
  echo "</td>";                                                                                                                                                                                                                           //Leads details fieldset .................................Leads details fieldset.....................Leads details fieldset 
  echo "<td >";
 //echo "<div>";
  echo "<fieldset style='float:left;width:95%;height:100%'>";       
  echo "<legend><h3>Leads Details</h3>";
  echo "</legend>";
  echo "<div style='height:100%;overflow:scroll'>";
  echo "<table border=0 style='width:100%'>";
    $outputtype_id2=explode(',',$outputtypeid);
    $n=sizeof($outputtype_id2);
  echo '<tr><td>Output Type</td>';
    $sql_out="SELECT * FROM bio_outputtypes";
    $result_out=DB_query($sql_out, $db);
    $j=1;
    $f=0;
  while($mysql_out=DB_fetch_array($result_out)){
      $f=1;
  for($i=0;$i<$n;$i++)        {
  if($mysql_out[0]==$outputtype_id2[$i]){
  echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].' checked>'.$mysql_out[1].'</td>';
         $j++; 
         $f=0;
        }
      }
      if($f==1){
         echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
         $j++;
         $f=0; 
      }  
      if($j>=2){
          echo'</tr><tr><td></td>';
      }
  } 
    
//    echo"</tr></table></td></tr>";
    echo"</tr>";
    
    
/*   $sql="SELECT bio_leadsourcetypes.id,
                 bio_leadsourcetypes.leadsourcetype 
           FROM `bio_leadsourcetypes`,bio_leadsources
            WHERE bio_leadsources.id=$sourceid
            AND bio_leadsources.sourcetypeid=bio_leadsourcetypes.id";
    $result=DB_query($sql,$db); 
    $myrow = DB_fetch_array($result);
    */
    
/*   echo "<tr><td>LeadSource Type</td><td><input type='hidden' id='sourcetype' value=".$myrow['id']." onkeyup='' style='width:99%'>".$myrow['leadsourcetype']."</td></tr>";
   echo '<tr id=showsource>'; 
   
 //--------------------------------------------------------------------------------  
//         echo $sourcetypeid=$myrow4['sourcetypeid'] ; echo $sourceid ; 


   echo '<td>LeadSource</td><td>'; 
   echo '<select name=source id=source style="width:192px" onkeyup=showCD1(this.value) onchange=showCD1(this.value) onclick=showCD1(this.value)>';
    $sql1="SELECT id,sourcename, 
                  sourcetypeid
             FROM bio_leadsources
            WHERE bio_leadsources.id=".$sourceid;
    $result1=DB_query($sql1,$db) ;

   while ($myrow1 = DB_fetch_array($result1)) {
    if ($myrow1['id']==$_POST['source']) 
    {
    echo '<option selected value="';
    echo $myrow1['id'] .'">'.$myrow1['sourcename'];
    
    } else {


        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['sourcename'];
        
    }
    echo  '</option>';
    }
    echo '</select></td>';
   */
   
   
//-----------------------------------------------------------------------------------------------   
   
   
   echo '</tr>';
   echo "<tr><td colspan=2 style='width:44%;align=left;'>";
   

   echo '<div id="dinhide">';
   
   echo '<div id=sourcedetails class=sourcedetails>';    
   echo '</div>'; 
   
   echo '</div>'; 
//   echo '</td></tr>';  
    echo '</tr>';
     
   //  echo '</div></tr>' ;
   // echo '<td>';
     


    echo '<tr><td>Investment Size</td>';
    echo "<td><input type=text name=investmentsize id=invest value='$investmentsize'  style='width:90%'></td>";
    echo '</tr>';
       
    $schemeid2=explode(',',$schemeid);
    $n=sizeof($schemeid2);
    
    echo '<tr><td>Scheme</td>';
    $sql1="SELECT * FROM bio_schemes";
    $result1=DB_query($sql1, $db); 
    $j=1;
    $f=0;
    while ($taskdetails=DB_fetch_array($result1)){
        $f=1;
      for($i=0;$i<$n;$i++)        {
        if($taskdetails[0]==$schemeid2[$i]){
           echo'<td><input type="checkbox" id="schm"'.$j.' name="schm[]" value='.$taskdetails[0].' checked>'.$taskdetails[1].'</td>';
           $j++; 
         $f=0;
        }
      }
      if($f==1){
         echo'<td><input type="checkbox" id="schm"'.$j.' name="schm[]" value='.$taskdetails[0].'>'.$taskdetails[1].'</td>';
         $j++;
         $f=0; 
      }  
        
        
       /* echo'<td><input type="checkbox" id="schm"'.$j.' name="schm[]" value='.$taskdetails[0].'>'.$taskdetails[1].'</td>';
        $j++;*/
        
        
        if($j>=2){
            echo'</tr><tr><td></td>';
        }         
                 
        $j++;         
    } 
    echo"</tr>";

      //Status 
    echo '<tr><td>Status</td>';
    echo "<td><input type=text name='status' value='$status' style='width:90%'></td>";
    echo '</tr>';
    //Remarks
    echo '<tr><td>Remarks</td>'; 
    echo "<td><textarea name='remarks' rows=4 cols=25 style='resize:none';>$remarks</textarea></td>"; 
    echo '</tr>';
    
    echo "<tr id='schedule'><td>Urgency Level</td>"; 
echo '<td>';

    if($_GET['en']==1) {      
             $sql1="SELECT * FROM bio_schedule_master WHERE master_schedule_id IN (17,18,20,21,22,23,24)";             
    }elseif($_GET['en']==2) {        
            $sql1="SELECT * FROM bio_schedule_master WHERE master_schedule_id IN (19,25,26,27,28,29)";              
    }elseif($_GET['en']==3 || $_GET['en']==4) {       
             $sql1="SELECT * FROM bio_schedule_master";               
    }           
  $result1=DB_query($sql1, $db);
  echo '<select name="UrgencyLevel" id="urgencylevel" style="width:192px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['master_schedule_id']==$urgency) 
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
    
    
    
    echo"<tr></tr>";
    echo '<tr><td>Lead Status</td>'; 
    echo'<td><select name="LeadStatus" id="leadstatus" readonly style="width:192px">';
    $sql1="SELECT * FROM bio_status";
    $result1=DB_query($sql1, $db); 
    $f=0;
    while($myrow1=DB_fetch_array($result1))
    {
    if ($myrow1['statusid']==$leadstatus)
    {
        echo '<option selected value="';
    } else 
    {
        if ($f==0) 
        {
            echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['statusid'] . '">'.$myrow1['biostatus'];  
    echo '</option>';
  }
  echo'</select></td></tr>';
    
    echo "</table>"; 
  
  //echo '</form>'; 
     echo "<input type=hidden name='leadid1' id='leadid' value='$leadid'>";
//     echo  $leadid;  
     echo "</fieldset>";   
     echo '</td>';  
     echo '</tr>';
     echo"</table>";
 //--------------------------------------------------------------------------------------------------------    
     echo"<div id='shadd'>";
     echo "<table style='align:left'  border=0  >";
     echo "<tr><td>Feed Stock</td>";
//Feedstock
     echo '<td>';

 $sql1="SELECT * FROM bio_feedstocks";
  $result1=DB_query($sql1, $db);
  echo '<select name="feedstockad" id="feedstockad" style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['feedstock']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
  echo "</td>";
  echo "<td>Weight in Kg</td>";
  echo "<td><input type=text name='weightad' id='weightad' style='width:83%'></td>";
  echo "<td>";
 echo '<input type="button" name="edaddfeedstock" id="edaddfeedstock" value="Add" onclick="showCD9()">';
//  echo '<input type="button" name="addfeedstock" id="addfeedstock" value=Add>';
  echo "</td>";
  
  echo "</tr>";
  
  

echo "</table>"; 
//----------------------------
     
     
     

  echo"<table id='editact' style='width:65%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Feed Stock</td><td>Weight</td><td>Update</td></tr>";
      
        $sql="SELECT bio_feedstocks.feedstocks,
                     bio_leadfeedstocks.weight,
                     bio_leadfeedstocks.feedstockid
                FROM bio_leadfeedstocks,
                     bio_feedstocks 
               WHERE bio_leadfeedstocks.leadid=$leadid AND
                     bio_leadfeedstocks.feedstockid=bio_feedstocks.id";
                     
        $result1=DB_query($sql, $db);    
        $n=1;
        
  while($myrow=DB_fetch_array($result1)){
  echo "<tr style='background:#000080;color:white' >
        <td>$n</td>
        <td>$myrow[0]</td>
        <td>$myrow[1]</td>
        <td><a  style='color:white;cursor:pointer' name='".$leadid."' id='$myrow[2]' onclick='feedupdte1(this.id,this.name)'>Edit</a></td>
        </tr>";
 $n++;
}   
    echo "<tr id='edittedsho'></tr>";
    echo"</table>";

//echo"<table>";echo"</div>";
    echo"<table>";
    echo"</div>";
    echo '<tr><td colspan="2"><center>';
    echo'<input type="submit" name="edit" id="editleads" value="Update"  onclick="return log_in()">';
    echo '<input name="save" type="submit" value="Create as new Lead" onclick="if(log_in()==1)return false;"></center></td></tr></table>';
    
    echo "</td></tr></table>";
    echo '</form>';

 } 
?>

<script type="text/javascript">


 document.getElementById('enquiry').focus(); 
  $(document).ready(function() {
  $('#district1').hide();
      $('#printgrid').hide();
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3500);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
                $("#db_message").fadeOut(3000);  
         
 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
 $('#shwprint').click(function() {
  $('#printgrid').slideToggle('slow',function(){});
});

$("#selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
});


 
   });


function feedupdte1(str,str1)
{
//  alert("hii"); 

//alert(str); 
//alert(str1);

//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;

if (str1=="")
  {
  document.getElementById("edittedsho").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {     // alert("ddd");
    document.getElementById("edittedsho").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus();
    }
  }
xmlhttp.open("GET","bio_sourcetypedetails.php?ledid=" + str1 + "&fed=" + str,true);
xmlhttp.send();

}
function dofeeedit(str1,str2,str3)
{
var str1=document.getElementById("feedleadid").value;
var str2=document.getElementById("biofeedstockid").value;
var str3=document.getElementById("fedwt").value;
// alert(str1);       alert(str2);        alert(str3);
if (str1=="")
  {

  document.getElementById("editact").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {

  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {      //alert("ddd");
    document.getElementById("editact").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus();
    }
  }
xmlhttp.open("GET","bio_sourcetypedetails.php?ediled=" + str1 + "&fedidedi=" + str2 + "&fedwt=" + str3,true);
xmlhttp.send();

}

function replace_html(id, content) {
    document.getElementById(id).innerHTML = content;
}
var progress_bar = new Image();
progress_bar.src = '4.gif';
function show_progressbar(id) {
    replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}


function showCD(str)
{
  // alert("hiii");
       // alert(str); 
//$(document).ready(function(){  

if (str=="")
  {
  document.getElementById("showsource").innerHTML="";
  return;
  }
show_progressbar('showsource');
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {     
    document.getElementById("showsource").innerHTML=xmlhttp.responseText;
    }
  } 
  //alert(str);

xmlhttp.open("GET","bio_getsource.php?q="+str,true);
xmlhttp.send(); 
$("#hidetable").hide();  
}

function showCD1(str)
{
   //  alert(str);
  // $("# sourcedetails").show(); 
// $("# hidetr").show();
$('#dinhide').show();
if (str=="")
  {
  document.getElementById("sourcedetails").innerHTML="";
  return;
  }
show_progressbar('sourcedetails');
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        
    document.getElementById("sourcedetails").innerHTML=xmlhttp.responseText;
      //$('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_sourcetypedetails.php?q="+str,true);
xmlhttp.send(); 
 
}

function showCD2(str1,str2)
{
   //alert("hii");
//   alert(str2);   alert(str1);
if (str1=="")
  {
  document.getElementById("editleads").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("editleads").innerHTML=xmlhttp.responseText;
//    document.getElementById('inputField').focus(); 
    



    }
  } 
xmlhttp.open("GET","bio_editleads.php?q=" + str1 + "&en=" + str2,true);
xmlhttp.send();    

}

function showFeeds(str1,str2){
//alert(str1);
if (str1=="")
  {
  document.getElementById("feeds").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("feeds").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_showfeeds.php?p=" + str1,true);
xmlhttp.send(); 
}


function showCD4()
{var str1=document.getElementById("feedstock").value;
var str2=document.getElementById("weight").value;
//   alert("hii");
//   alert(str1);
if(str1==""){
alert("select a Feedstock"); document.getElementById("feedstock").focus();  return false;  }
if (str1=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";     //editleads
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    document.getElementById('feedstock').value="";       document.getElementById('weight').value="";
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails.php?feedstock=" + str1  + "&weight=" + str2 ,true);
xmlhttp.send();    

}

function editfeedstok(str)
{
   //alert("hii");



//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;

if (str=="")
  {
  document.getElementById("editfeed").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("editfeed").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails.php?upfeedstockid=" + str,true);
xmlhttp.send();    

}


function doedit()
{
//   alert("hii");
//   alert(str);

var str=document.getElementById("fdstk").value;    
var str1=document.getElementById("h1feedstock").value;
var str2=document.getElementById("h1feedweight").value;
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    $('#h1feedweight').focus(); 
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails.php?edid=" + str + "&edfd=" + str1 + "&edwt=" + str2,true);
xmlhttp.send();    

}    
function deletfeedstok(str)
{
//   alert("hii");
//   alert(str);


// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails.php?delet=" + str,true);
xmlhttp.send();    

}

function san()
{
    
    myRef = window.open("bio_print_A5p.php");
    
}

function feasibilityP()
{
    myRef = window.open("bio_feasibility_print.php");
}

function prop(){window.location = "bio_proposal.php";}
//function amntmode(str){
//if (str=="")
//  {
//  document.getElementById("modeamt").innerHTML="";
//  return;
//  }
//if (window.XMLHttpRequest)
//  {// code for IE7+, Firefox, Chrome, Opera, Safari
//  xmlhttp=new XMLHttpRequest();
//  }
//else
//  {// code for IE6, IE5
//  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//  }
//xmlhttp.onreadystatechange=function()
//  {
//  if (xmlhttp.readyState==4 && xmlhttp.status==200)
//    {
//    document.getElementById("modeamt").innerHTML=xmlhttp.responseText;

//    }
//  } 
//xmlhttp.open("GET","bio_amountdetails.php?mod=" + str,true);
//xmlhttp.send();    
//}
function advdetail(str){
if (str=="")
  {
  document.getElementById("amt").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("amt").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_amountdetails.php?adv=" + str,true);
xmlhttp.send();    
}



function displayVals() {
//     alert("sss");
      var multipleValues = document.getElementById("remarklist").value;
//      alert(multipleValues);
        document.getElementById("remarks").value=multipleValues;

    }
   
    
  $("#remarklist").change(displayVals);
    displayVals();
    
 
    
   
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});
function caps1()
{
//   alert("sss");
UCWords('custid','Name should be begin with capital letter');
}

function log_in()
{  //  alert("sss"); alert(mail);
var f=0;                                                  //State

if(f==0){f=common_error('urgencylevel','Please Select urgency level');  if(f==1) { return f; }}
}


  function printshow()
{                               
 var str=document.getElementById('printnshow').value;   
 var str1=document.getElementById('datefrm').value;        
 var str2=document.getElementById('dateto').value;   
  var str3=document.getElementById('offic').value; 
  var str4=document.getElementById('place').value; 
var str5=document.getElementById('enquiry1').value; 
if (str=="")
  {
  document.getElementById("printandshow").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {           //alert(str);   
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {

    document.getElementById("printandshow").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_PrintLeadSource.php?id=" + str +  "&from=" + str1 + "&to=" + str2 + "&offic=" + str3 + "&place=" + str4 + "&etype=" + str5,true);
xmlhttp.send(); 
                                        
}
function showCD9(){                           
 var str=document.getElementById('feedstockad').value;   
 var str1=document.getElementById('weightad').value;        
 var str2=document.getElementById('leadid').value;   
   if(str==""){alert("please select a feadstock");document.getElementById("feedstockad").focus(); return false;}
//alert(str);alert(str1);alert(str2);
if (str2=="")
  {
  document.getElementById("shadd").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {       
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {    function message(){alert("sss");}     

    document.getElementById("shadd").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_add.php?feedstok=" + str +  "&weight=" + str1 + "&lead=" + str2,true);
xmlhttp.send(); 
 }
 function showstate(str){ 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {             
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
show_progressbar('showdistrict');
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}

 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if (str3=="")
     {
     alert("Please select a district");    
     document.getElementById("Districts").focus();
     document.getElementById("lsgType").value=0;
     return;
     }
     

     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("block").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

function addnew(str){ if(str=="New"){
$('#showdistrict').hide();
$('#district1').show();
  document.getElementById('District').focus();


  }
}



$(document).ready(function() {
      $('#lsgdid').hide(); 
    $('#loader').hide();
    $('#show_heading').hide();
    
    $('#Nationality').change(function(){
        $('#show_sub_categories').fadeOut();
        $('#loader').show();
        $.post("get_chid_categories.php", {
            parent_id: $('#Nationality').val(),
        }, function(response){
            
            setTimeout("finishAjax('show_sub_categories', '"+escape(response)+"')", 400);
        });
        return false;
    });
});

function finishAjax(id, response){
  $('#loader').hide();
  $('#show_heading').show();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} 

function alert_id()
{
    if($('#State').val() == '')
    alert('Please select a sub category.');
    else
    alert($('#State').val());
    return false;
}

function showinstitute(str){    // alert(str);
str1=document.getElementById("enquiry").value;
if(str1==3){ $('#lsgdid').show();   }
else{ $('#lsgdid').hide();   }  
if (str=="")
  {
  document.getElementById("instdom").innerHTML="";
  return;
  }
  show_progressbar("instdom");
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("instdom").innerHTML=xmlhttp.responseText;

 //   getgrid(str);
    }
  }
xmlhttp.open("GET","bio_showdom.php?dom=" + str + "&enq=" + str1,true);
xmlhttp.send();
//
}
function getgrid(){
//    alert(str);
str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("leaddetails").innerHTML="";
  return;
  }
  show_progressbar("leaddetails");
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("leaddetails").innerHTML=xmlhttp.responseText;
getservice(str);
    }
  }
xmlhttp.open("GET","bio_showgrid.php?enggrid=" + str,true);
xmlhttp.send();


}

function getservice(str){
//    alert(str);
//str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("showfeasibility").innerHTML="";
  return;
  }
  show_progressbar("showfeasibility");
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showfeasibility").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_showgrid.php?service=" + str,true);
xmlhttp.send();


}

function output(str1){
//    alert(str);
str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("showoutputtype").innerHTML="";
  return;
  }
  show_progressbar("showoutputtype");
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showoutputtype").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_showgrid.php?output=" + str + "&plant=" + str1,true);
xmlhttp.send();


}
//location.href="bio_print.php?lead=" + str;     
/*function schemecheck()
{  var f=0;
    var a=new Array();
    a=document.getElementsByName("schm[]");
//    alert("Length:"+a.length);
    var p=0;
    for(i=0;i<a.length;i++){
        if(a[i].checked){
//            alert(a[i].value);
            p=1;
        }
    }
    if (p==0){ var f=1;
        alert('please select at least one Scheme');
        return f;
    }

//    document.some_form.submitted.value='yes';
//    return true;
}  */
function checkMobile(){
        
    
    var mobile=document.getElementById('mobile').value;
    var enquiry=document.getElementById('enquiry').value;   
    /*if(enquiry==0){
        alert("Select enquiry type"); 
        document.getElementById("enquiry").focus();  
        return false;
    }   */
 //alert(mobile); 
 //alert(enquiry);     
           
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
      
  if (xmlhttp.readyState==4 && xmlhttp.status==200)        
  
    {
    var id=document.getElementById("mob").value=xmlhttp.responseText;
    if(id!=0){
        alert("Customer already exists");
        myRef = window.open("bio_editleadsnew.php?q=" + id + "&en=" + enquiry);
    } 
    }
  } 
xmlhttp.open("GET","bio_mobilecalc.php?mobile=" + mobile + "&enquiry=" + enquiry,true);  
xmlhttp.send();  
}


</script>
