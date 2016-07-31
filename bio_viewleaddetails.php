<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $title = _('View Lead Details');  
  include('includes/header.inc');
  include('includes/sidemenu.php'); 

  $leadid=$_GET['q'];

 if(isset($_POST['edit']) and isset($_POST['leadid']))
 {
  $leadid= $_POST['leadid']; 

 $leadstatus=$_POST['ChangeStatus'];
 $remarks=$_POST['remarks'];
 if($remarks==""){
        $remarks=="";
 }
                     

$sql="UPDATE bio_leads SET
            remarks='".$remarks."', leadstatus=$leadstatus 
            WHERE leadid=".$leadid;
       $result=DB_query($sql,$db);
                    $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been Updated'),'success');
       unset($leadid);
 } 
 if($leadid!='')
 {    $sql="SELECT
    `bio_enquirytypes`.`enquirytype`
    , `bio_cust`.`custname`
    , `bio_cust`.`contactperson`
    , `bio_cust`.`area1`
    , `bio_cust`.`area2`
    , `bio_cust`.`pin`
    , `bio_country`.`country`
    , `bio_state`.`state` AS state
    , `bio_district`.`district`  as district
    , `bio_cust`.`LSG_type`
    , `bio_cust`.`taluk`
    , `bio_cust`.`LSG_name`
    , `bio_cust`.`housename` 
    , `bio_cust`.`houseno`  
    , `bio_cust`.`nationality`
    , `bio_cust`.`state` AS stateid
    , `bio_cust`.`district`  as did
    , `bio_cust`.`block_name`
    , `bio_cust`.`LSG_ward`
    , `bio_cust`.`village`
    , `bio_cust`.`custphone`
    , `bio_cust`.`custmob`
    , `bio_cust`.`custmail`
    ,  bio_leadsources.sourcename
    , `bio_productservices`.`productservices`
    , `bio_advance`.`amount`
    , `bio_leads`.`outputtypeid`
    , `bio_leads`.`sourceid`
    , `bio_leads`.`investmentsize`
    , `bio_leads`.`schemeid`
    , `bio_leads`.`remarks`
    , `bio_leads`.`leadstatus`
    , `bio_leads`.`created_by`
    , `bio_leads`.`rmdailykg` 
    , `bio_leads`.`enqtypeid`   
FROM
    `bio_cust`
    LEFT JOIN `bio_country` 
        ON (`bio_cust`.`nationality` = `bio_country`.`cid`)
    LEFT JOIN `bio_state` 
        ON (`bio_cust`.`nationality` = `bio_state`.`cid`) AND (`bio_cust`.`state` = `bio_state`.`stateid`)
    LEFT JOIN `bio_district` 
        ON (`bio_cust`.`district` = `bio_district`.`did`) AND (`bio_cust`.`nationality` = `bio_district`.`cid`) AND (`bio_cust`.`state` = `bio_district`.`stateid`)
    ,`bio_leads`
    LEFT JOIN `bio_enquirytypes` 
        ON (`bio_leads`.`enqtypeid` = `bio_enquirytypes`.`enqtypeid`)
    LEFT JOIN `bio_productservices` 
        ON (`bio_leads`.`productservicesid` = `bio_productservices`.`id`)
    LEFT JOIN `bio_advance` 
        ON (`bio_leads`.`leadid` = `bio_advance`.`leadid`)
    LEFT JOIN bio_leadsources 
        ON (`bio_leads`.`sourceid` = `bio_leadsources`.`id`)
    WHERE 
        `bio_leads`.`cust_id` = `bio_cust`.`cust_id` AND
         bio_leads.leadid=".$leadid;
      $result=DB_query($sql,$db);
      $myrow=DB_fetch_array($result); 
      $rmdailykg=$myrow['rmdailykg'];
      $advanceamount=$myrow['amount'];
      $productservice=$myrow['productservices'];
      $enqtypeid=$myrow['enqtypeid']; 
      $leadstatus=$myrow['leadstatus']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $outputtypeid=explode(',',$outputtypeid);
      $typecount=count($outputtypeid);
      $cid=$myrow['nationality'];
      $stateid=$myrow['stateid'];
      $did=$myrow['did'];
      $schemeid=$myrow['schemeid'];
      $schemeid=explode(',',$schemeid);
      $count99=count($schemeid);
      $country=$myrow['country'];
      $state=$myrow['state'];
      $district=$myrow['district'];
      $custname=$myrow['custname']; 
      $contact=$myrow['contactperson'];
      $teamid=$myrow['teamid'];
      $sourceid=$myrow['sourceid'];
      $sourc=$myrow['sourcename'];
      $investmentsize=$myrow['investmentsize'];
      $remarks=$myrow['remarks'];
      $status=$myrow['status']; 
      $createdby=$myrow['created_by'];
      
      $sql_emp="SELECT bio_emp.empname
                      FROM bio_emp,
                           www_users
                     WHERE www_users.empid=bio_emp.empid 
                       AND www_users.userid='".$createdby."'";
                       
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
     $sqlstatus="Select biostatus from bio_status where statusid=$leadstatus";
       $result_st=DB_query($sqlstatus,$db);
      $myrow_st=DB_fetch_array($result_st);
      $statusn=$myrow_st['biostatus'];
      $sql_lteam="Select bio_leadteams.teamname as taskteam from bio_leadtask,bio_leadteams where bio_leadtask.leadid=$leadid and bio_leadtask.viewstatus=1 and bio_leadtask.taskid=5 AND bio_leadteams.teamid=bio_leadtask.teamid";
      $result_lteam=DB_query($sql_lteam,$db);
      $myrow_lteam=DB_fetch_array($result_lteam);
      $teamhandling=$myrow_lteam['taskteam'];
      $empname=$myrow_emp['empname'];
      $custname=$myrow['custname'];    
      $homno=$myrow['houseno'];
      $housename=$myrow['housename']; 
      $area1=$myrow['area1'];
      $area2=$myrow['area2'];           
      $pin=$myrow['pin'];
      $lsgtype=$myrow['LSG_type'];
      $lsgid=$myrow['LSG_name'];
      $panchayath=$myrow['block_name'];     
      $pieces=$myrow1['custphone'];  $phpieces = explode("-", $pieces,2);  $custcode=$phpieces[0];if($custcode==0 || $custcode==""){$custcode=0;}  $custphone=$phpieces[1];
      $custmobile=$myrow1['custmob'];
      $custemail=$myrow1['custmail'];
//--------------------------------------------------------
if($custphone==""){$custphone=0;}if($custmobile==""){$custmobile=0;} if($custemail==""){$custemail=0;}  

    echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">LEAD DETAILS</font></center>';
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
echo"<table><tr><td>";
     echo "<div id=editleads>";  
     echo "Team Currrently handling the lead:<b>".$teamhandling."</b><br>";  
     echo "Current Status of the lead:<b>".$statusn."</b>";    
     echo "<table border=0 style='width:785px;height:200%;'>";
    echo "<tr><td style='width:50%'>";
      echo "<fieldset style='float:left;width:95%;height:450px' valign='top'>";     
    echo "<legend><h3>Customer Details</h3>";
    echo "</legend>";
    
    echo "<table>";  
   echo"<b>";
    if($_GET['en']==1){
        echo "<tr><td>Enquiry Type</td><td>:Domestic</td></tr>";
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type=hidden name=custname id=custid value='$custname' style='width:96%'>:$custname</td>";
        echo "<tr><td>House No:</td><td><input type='hidden' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'>:$homno</td></tr>";
    echo"<tr></tr>";
        echo "<tr><td>House Name</td><td><input type='hidden' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'>:$housename</td></tr>";
    echo"<tr></tr>";        
        echo "<tr><td>Residential Area:</td><td><input type='hidden' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'>:$area1</td></tr>";  }
        
        
            if($_GET['en']==2){$enqtypeid=$_GET['en'];
              echo "<tr><td>Enquiry Type</td><td>:Institution</td></tr>";
                  echo"<tr></tr>";
             echo "<tr><td style='width:50%'>Contact Name</td>";
    echo "<td><input type='hidden' name='custname' id='custid' value='$custname' readonly onkeyup='caps1()'  style='width:190px'>:$contact</td></tr>";
    echo"<tr></tr>";

        echo "<tr><td>Org Name:</td><td><input type='hidden' name='HouseName' id='HouseName' value='$housename' onkeyup='' style='width:99%'>:$custname</td></tr>";
        echo"<tr></tr>";
        echo "<tr><td>Org Area:</td><td><input type='hidden' name='Area1' id='Area1' onkeyup='' value='$area1' style='width:99%'>:$area1</td></tr>";
        echo"<tr></tr>";
  }
  
      if($_GET['en']==3){
            echo "<tr><td>Enquiry Type</td><td>LSGD</td></tr>";
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type=hidden name=custname id=custid value='$custname' readonly style='width:96%'>$custname:</td>";
        echo "<tr><td>House No:</td><td><input type='hidden' value='$homno' name='Houseno' id='Houseno' onkeyup='' style='width:99%'>:$homno</td></tr>";    
        echo "<tr><td>House Name</td><td><input type='hidden' value='$housename' name='HouseName' id='HouseName' onkeyup='' style='width:99%'>:$housename</td></tr>";    
        echo "<tr><td>Residential Area:</td><td><input type='hidden' name='Area1' value='$area1' id='Area1' onkeyup='' style='width:99%'>:$area1</td></tr>";  }
        echo"<tr></tr>";  
        echo "<tr><td>Post Box:</td><td><input type='hidden' name='Area2' value='$area2' id='Area2' onkeyup='' style='width:99%'>:$area2</td></tr>";
        echo"<tr></tr>";    
                echo" <tr><td>Pin:</td><td><input type='hidden' name='Pin' id='Pin' value='$pin' onkeyup='' style='width:99%'>:$pin</td></tr>";  
          
         echo"<tr></tr>";           
                      echo "<tr><td>Country:</td><td><input type='hidden' name='State' id='State' value='$country' onkeyup='' style='width:99%'>:$country</td></tr>"; 

        echo"<tr></tr>";   
        echo "<tr><td>State:</td><td><input type='hidden' name='State' id='State' value='$state' onkeyup='' style='width:99%'>:$state</td></tr>"; 
      
        echo"<tr></tr>";             
        echo "<tr><td>District:</td><td><input type='hidden' name='District' id='District' value='$district' onkeyup='' style='width:99%'>:$district</td>";       
    echo '</tr>';
    
    if($lsgtype!=NULL){
       echo"<tr></tr>"; 
        
      if($lsgtype==1) {
        if($lsgid==12){
         $lsgname='Thiruvananthapuram';
         }
       if($lsgid==6)
       {
           $lsgname='Kollam';
       }            
        if($lsgid==2)
        {
            $lsgname='Eranakulam';
        }
        if($lsgid==8)
        {
            $lsgname='Kozhikode';
        }
         if($lsgid==13)
         {
             $lsgname='Thrissur';
         } 

     echo '<tr><td>Corporation</td><td>:'.$lsgname.'</td></tr>';    
                                      
    }
   if($lsgtype==2) {      
   $sql="SELECT * FROM bio_municipality WHERE country=$cid AND state=$stateid AND district=$did AND id=$lsgid";
        $result=DB_query($sql,$db);  
    $myrow1=DB_fetch_array($result);
        
         $lsgname=$myrow1['municipality'];
         echo '<tr><td>Municipality</td><td>:'.$lsgname.'</td></tr>';
    }
    if($lsgtype==3) { 
        
        $sql="SELECT * FROM bio_block WHERE country=$cid AND state=$stateid AND district=$did AND id=$lsgid";
        $result=DB_query($sql,$db);
        $myrow1=DB_fetch_array($result);
        $lsgname=$myrow1['block'];
        echo '<tr><td>Block</td><td>:'.$lsgname.'</td></tr>';
        echo"<tr></tr>";
        $sql="SELECT * FROM bio_panchayat WHERE country=$cid AND state=$stateid AND district=$did AND id=$panchayath";
        $result=DB_query($sql,$db);
        $myrow1=DB_fetch_array($result);
        $panchayath_name=$myrow1['name'];
        echo '<tr><td>Panchayath</td><td>:'.$panchayath_name.'</td></tr>';      
    }  
}
    echo"<tr></tr>";    
echo '<tr><td>Phone number</td>';
    echo "<td><input type='hidden' name='code' id='code' value='$custcode' style='width:50px'>:$custcode<input type=hidden name=phone id=phone value='$custphone' style='width:96%'> - $custphone</td></tr>";
    echo"<tr></tr>";
    echo '<tr><td>Mobile Number</td>';
    echo "<td><input type=hidden name=mobile id=mobile value='$custmobile' style='width:96%'>:$custmobile</td></tr>";
    if($custemail==0){
      $custemail="";
    }
    echo"<tr></tr>";
    echo '<tr><td>Email id</td>';
    echo "<td><input type=hidden name=email id=email value='$custemail' style='width:96%'>:$custemail</td></tr>";
    echo"<tr></tr>";
      echo '<tr><td>Product Services</td><td>'; 
        echo '<input type="hidden" name=productservices style="width:190px" value='.$productservice.'>:'.$productservice.'';
   
  echo '</td>'; 
  echo '</tr>';


  echo"<tr></tr>";
  echo '<tr><td>Advance Amount</td>';
    echo "<td><input type=hidden name=advanceamt id=advance value='$amount'  style='width:96%'>:$amount</td></tr>";
  echo "</table>";  
  echo"<div style='height:75px'></div>";
  echo "</fieldset>"; 

  echo "</td>";
  
  
   //Leads details fieldset .................................Leads details fieldset.....................Leads details fieldset 
   
   
  echo "<td style='width:55%'>";
 //echo "<div>";
  echo "<fieldset style='float:left;width:95%;height:450px'>";       
  echo "<legend><h3>Leads Details</h3>";
  echo "</legend>";
  
  echo "<table border=0 style='width:100%'>";
  
   //output types
  echo"<tr></tr>";
  
  for($i=0;$i<=$typecount-1;$i++){
        $outputtypeid1=$outputtypeid[$i];
        $sql1="SELECT * FROM bio_outputtypes WHERE outputtypeid=".$outputtypeid1;
        $result1=DB_query($sql1, $db);
        $myrow1=DB_fetch_array($result1);
        $output=$myrow1[1].",".$output;
    }
    $output = substr($output,0,-1);  
  echo '<tr><td>Output Type</td><td>:'.$output.'</td></tr>';
  

    echo'</td></tr>';

   $d=9; 
    echo '<tr id=showsource>'; 
  
    echo"<tr></tr>"; 
    echo '<td>LeadSource</td><td>';      
           echo "<input type=hidden name=source id=source value='$sourc'  style='width:90%'>:".$sourc."</td>";

    echo '</tr>';
      echo "<tr><td colspan=2 style='width:44%;align=left;'>";
   
   echo '<div id="dinhide">';
   echo '<div id=sourcedetails class=sourcedetails>';    
   echo '</div>'; 
   echo '</div>'; 
    echo '</tr>';
    echo"<tr></tr>";
    echo '<tr><td>Investment Size</td>';
    echo "<td><input type=hidden name=investmentsize id=invest value='$investmentsize'  style='width:90%'>:$investmentsize</td>";
    echo '</tr>';
    if($schemeid[0]!=""){
      for($i=0;$i<=$count99-1;$i++){
        $sch=$schemeid[$i];
        $sql99="SELECT scheme FROM bio_schemes WHERE schemeid=$sch";
        $result99=DB_query($sql99,$db);  
        $myrow99=DB_fetch_array($result99);
        $schm=$myrow99[0].",".$schm;
    }
    $schm = substr($schm,0,-1);   
     }else{
        $schm='No Schemes.'; 
     }
    echo"<tr></tr>";
    echo '<tr><td>Scheme</td><td>:'.$schm.'</td>';   
    echo"<tr></tr>";
    echo '<tr><td>Remark</td>'; 
    echo "<td><textarea name='remarks' id='remarks' rows=15 cols=40 style='resize:none';>$remarks</textarea></td>"; 
    echo '</tr>';  
     
    
    echo"<tr></tr>";
    echo '<tr><td>Lead Status</td>'; 
    echo'<td><select name="ChangeStatus" id="changestatus" style="width:192px">';
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
  
  echo '<tr><td>Created By</td>'; 
    echo "<td>:$empname</td>"; 
    echo '</tr>';
  
  
    echo "</table>"; 
echo "<input type=hidden name='leadid' id='leadid' value='$leadid'>";
  //   echo  $leadid;
     echo "</fieldset>";   
     echo '</td>';  
     echo '</tr>';
     echo"</b>";

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
  echo "</td>";
  
  echo "</tr>";
  
  

echo "</table>"; 
//----------------------------
     
     
     

  echo"<table id='editact' style='width:65%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Feed Stock</td><td>Weight</td><td>Update</td></tr>";
      
$sql="SELECT bio_feedstocks.feedstocks,
             bio_leadfeedstocks.weight,bio_leadfeedstocks.feedstockid
      FROM bio_leadfeedstocks,bio_feedstocks 
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
}   echo "<tr id='edittedsho'></tr>";
echo"</table>";
echo"</div>";

echo '<table><tr><td colspan="2"><center><input type="submit" name="edit" id="editleads" value="Enter"  onclick="return log_in()"></center></td></tr></table>';

//---------------------Lead History-------------------------------//

  echo "<fieldset style='float:left;width:90%;height:220px;height:auto'>";       
  echo "<legend><h3>Task Details</h3>";
  echo "</legend>"; 
     
  echo "<table border=0 style='width:760px;'>";  
  
  $sql_history="SELECT bio_leads.leaddate
                  FROM bio_leads
                 WHERE bio_leads.leadid=$leadid
                   ";
  $result_history=DB_query($sql_history,$db);
  $myrow_history=DB_fetch_array($result_history);
  $leaddate=convertSQLDate($myrow_history['leaddate']);
  
   echo "<tr><td>Lead Date</td>";
   echo "<td><input type=hidden name=leaddate id=leaddate value=$leaddate style='width:96%'>:$leaddate</td></tr>";
 
  echo "<tr><td></td></tr>";
 
  echo "<tr><td>Amount Paid</td>"; 
  $sql_amount="SELECT bio_advance.amount,
                      bio_advance.head_id,
                      bio_advance.paydate,  
                      bio_cashheads.heads                     
                 FROM bio_advance,bio_cashheads   
                WHERE bio_advance.leadid=$leadid
                  AND bio_cashheads.head_id=bio_advance.head_id";
  $result_amount=DB_query($sql_amount,$db);

  while($myrow_amount=DB_fetch_array($result_amount))
  {
      $headid=$myrow_amount['head_id'];

      if($myrow_amount['amount']!=0)
      {
      echo "<td><input type=hidden value=".$myrow_amount['amount']." style='width:96%'>:".$myrow_amount['amount']."\t(".$myrow_amount['heads'].") - ".convertSQLDate($myrow_amount['paydate'])."</td></tr>"; 
      echo "<tr><td></td>";
      }
  }
  echo '</tr>'; 
  echo "<tr><td>Tasks:</td></tr>";  
  echo "</table>";
   
  echo "<div style='height:100px; overflow:scroll;'>"; 
  echo "<table border=0 cellpadding=3 style='width:740px;'>";
  echo "<tr><th>Task</th><th>Team</th><th>Assigned Date</th><th>Due Date</th><th>Completed Date</th></tr>";
  
  $sql_task="SELECT bio_task.task,
                    bio_leadteams.teamname, 
                    bio_leadtask.assigneddate, 
                    bio_leadtask.duedate,
                    bio_leadtask.taskcompleteddate,
                    bio_leadtask.taskcompletedstatus
               FROM bio_leadtask,bio_task,bio_leadteams 
              WHERE bio_leadtask.leadid=$leadid
                AND bio_task.taskid=bio_leadtask.taskid
                AND bio_leadtask.taskcompletedstatus!=2 AND bio_leadtask.taskcompletedstatus!=3
                AND bio_leadteams.teamid=bio_leadtask.teamid";
  $result_task=DB_query($sql_task,$db);   
  
            
  while($row_task=DB_fetch_array($result_task))  
  {
     $task=$row_task['task'];
     if($task=='')
     {
         $task="Lead Registered";
     }
     $team=$row_task['teamname']; 
     $assigneddate=convertSQLDate($row_task['assigneddate']);  
     $duedate=convertSQLDate($row_task['duedate']);       
     $taskcompleteddate=convertSQLDate($row_task['taskcompleteddate']); 
     if($taskcompleteddate=='00/00/0000')  {$taskcompleteddate='-';}
     $taskcompletedstatus=$row_task['taskcompletedstatus'];
  
     
           if($taskcompletedstatus==1)
           {
               echo '<tr bgcolor="lightgreen">';
           }elseif($taskcompletedstatus==0){
               echo '<tr bgcolor="orange">';
           }
 echo "<td>$task</td><td>$team</td><td>$assigneddate</td><td>$duedate</td><td>$taskcompleteddate</td>";
   echo"<td ><a  style='cursor:pointer;'  onclick=showtask('$leadid')>" . _('View') . "</a></td>"; 
   echo"</tr>";   
  }  
echo "</div>";                 
echo '</td></tr></table>';
echo "</td></tr></table>";
echo '</form>';         

}
?>
<script type="text/javascript">
function showtask(str1){
   // alert(str1);
 //   alert(str2);
       
//         window.location="bio_viewleaddetails.php?q=" + str1 + "&en=" + str2;
         myRef = window.open("bio_viewleadtaskdetails.php?q=" + str1);
    }


</script>