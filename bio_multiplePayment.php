<?php
$PageSecurity = 80;
include('includes/session.inc');


$title = _('Receive Payment');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Receive Payment</font></center>';

    $office=$_SESSION['UserStockLocation'];

if (isset($_POST['submit'])) {
    
    if(!isset($_POST['LeadID'])){
      prnMsg('Select lead details from the grid and then assign the team','warn');  
        
    }
    else{
        $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
        
        
    $lead_ID=$_POST['LeadID'];
    $head_ID=$_POST['head'];
    $mode_ID=$_POST['mode'];
    $payment_date=$_POST['amtdate'];
    $receipt_no=$_POST['amtno']; 
    $payment_bank=$_POST['amtbank'];
    $DateString = Date($_SESSION['DefaultDateFormat']);
    $DateString=FormatDateForSQL($DateString);
    $amount=$_POST['advanceamount'];
    $team_ID=$_POST['team'];
    $status=$_POST['status'];
    $flag=$_POST['flag'];
    $leadtype1=$_POST['LeadType'];
    $currenttask=$_POST['Task'];
    echo"<input type='hidden' name='flag' id='flag' value=$flag>";
    echo"<input type='hidden' name='LeadID' id='leadid' value='$lead_ID'>";
    
    $sql="INSERT INTO bio_advance( leadid,
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
                            VALUES ('".$lead_ID."',
                                    '".$head_ID."',
                                    '".$mode_ID."',
                                    '".$payment_date."',
                                    '".$receipt_no."',
                                    '".$payment_bank."',
                                    '".$DateString."',
                                    '".$amount."',
                                    '".$_SESSION['officeid']."',
                                    '".$status."',
                                    '".$_SESSION[UserID]."')";
    $result=DB_query($sql,$db);        
    
    
   
   $adv=DB_Last_Insert_ID($Conn,'bio_advance','adv_id'); 
   $_SESSION['adv_id']=$adv;
   $_SESSION['lead']=$lead_ID;
   
   $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                          taskcompleteddate='".$DateString."' 
                   WHERE bio_leadtask.leadid=$lead_ID 
                     AND bio_leadtask.taskid=$currenttask 
                     AND taskcompletedstatus!=2";//           AND teamid=$assignedfrm   
    DB_query($sql_flag,$db);
                                  
   
 // ----------------------------------------------------------- 
   
      if($head_ID==2){
       
      
       
       $sqlr="SELECT fp_amount
                    FROM bio_fsproposal
                    WHERE leadid =".$lead_ID;    
            $resulte=DB_query($sqlr, $db);     
            $mr=DB_fetch_array($resulte);
            $fixedfs=$mr[0];
            
            if($amount<$fixedfs){
                $empid=$_SESSION['empid'];
$sql_rep="SELECT reportto FROM bio_emp WHERE empid=$empid";
$result_rep=DB_query($sql_rep,$db);
$row_rep=DB_fetch_array($result_rep);
$emp_repoff=$row_rep['reportto'];
if($emp_repoff==0){
    $emp_repoff=1;
}

$emp_repoff;
$sql_user="SELECT www_users.userid
        FROM www_users
        WHERE www_users.empid=".$emp_repoff;
$result_user=DB_query($sql_user,$db);
$row_user=DB_fetch_array($result_user);
$approval_by=$row_user['userid'];
 
 
 
  
    $task_ID=7;
    $submitted_by=$_SESSION['UserID'];
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
                                    '".$DateString."',
                                    '".$duedate."',
                                    '".$date1."',
                                    '".$status."')";
    $result=DB_query($sql,$db);
    if($result){
        prnMsg('The feasibility charge is less than the fixed charge so it is transferred to higher authority for approval','warn');

    }
    }
            
            
   
   
   
   
   
   
  // -------------------------------------------------------
   
 print'<script>

   var answer = confirm("Do you want to PRINT the Advance Receipt?")

   if (answer){
   myRef=window.open("bio_print_A5p.php");
   }
   </script> '; 
                 
 } 
 
  elseif($head_ID==8){  
 print'<script>

   var answer = confirm("Do you want to PRINT the Advance Receipt?")

   if (answer){
   myRef=window.open("bio_print_A5p.php");
   }
   </script> ';
 } 
/*    
  print'<script>

   var answer = confirm("Do you want to PRINT the Advance Receipt?")

   if (answer){
   myRef=window.open("bio_print_A5p.php");
   }
   </script> '; 
                 */
 
 
 
   
   unset($_POST['team']);
    unset($_POST['StartDate']);
    unset($_POST['EndDate']);
    }
 if(isset($_POST['flag']))
 {
     if($leadtype1==2){
       ?>
     <script>
     var lead=<?php echo $lead_ID; ?>;
      window.opener.location='bio_instTaskview.php?lead='+ lead;
      window.close();
     
     </script>
     <?php  
     }
     else{
         
     ?>
     <script>
     var flag=document.getElementById('flag').value;
     var lead=document.getElementById('leadid').value;
     window.opener.location='bio_domTaskview.php?flag=' + flag + '&lead='+ lead;
     window.close();
     </script>
     <?php
     }
 }
    
}
    

echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style="width:70%"><tr><td>';
echo"<div id=panel>";
echo '<table><tr>';

//========================================== Left Panel Begins

echo'<td>';

echo"<div id=cus_details>";
echo"<fieldset style='width:380px;height:150px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";
echo"<table width=100%>";

$tid=$_GET['tid'];
$leadtype=$_GET['leadtype'];
$currenttask=$_GET['task'];

if($_GET['lead']!=''){
    $leadid=$_GET['lead'];    
/*    $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality,
                    bio_status.biostatus  
              FROM  bio_leads,bio_cust,bio_status
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_status.statusid=bio_leads.leadstatus
              AND bio_leads.leadid=".$leadid; */
              $sql="SELECT
    `bio_cust`.`custname`
     , `bio_cust`.`custphone` 
    , `bio_cust`.`custmob`
    , `bio_cust`.`custmail` 
    , `bio_cust`.`area1`
    , `bio_district`.`district`
    , `bio_status`.`biostatus`
FROM
    `bio_leads`
    LEFT JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    LEFT JOIN `bio_district` 
        ON (`bio_cust`.`nationality` = `bio_district`.`cid`) AND (`bio_cust`.`state` = `bio_district`.`stateid`) AND (`bio_cust`.`district` = `bio_district`.`did`)
    LEFT JOIN `bio_status` 
        ON (`bio_leads`.`leadstatus` = `bio_status`.`statusid`)
              WHERE 
               bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);

 $myrow=DB_fetch_array($result); 

 $no=0; 
 $k=0; 
 
  
 $cname=$myrow['custname'];  
 if($myrow['custphone']!='-'){
     $cph=$myrow['custphone']; 
 }else{
     $cph=$myrow['custmob']; 
 }
 $email=$myrow['custmail']; 
 $place=$myrow['area1'];
 $district=$myrow['district'];
 $status=$myrow['biostatus'];
 /*$sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];   */
}

echo"<tr><td width=50%>Customer Name</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district'>$district</td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
echo"<input type='hidden' name='status' id='status' value='$status'>"; 
echo"<input type='hidden' name='flag' id='flag' value='$tid'>";
echo"<input type='hidden' name='LeadType' id='leadtype' value='$leadtype'>"; 
echo"<input type='hidden' name='Task' id='task' value='$currenttask'>";
echo'</div>';
echo"</td>";
//========================================== Left Panel Ends 

//========================================== Right Panel Begins

echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:380px;height:150px; overflow:auto;'>";
echo"<legend>Payment Details</legend>";
echo"<table>";
echo"<tr><td width=50%>Head</td>";
echo"<td><select name='head' id='head' style='width:150px'>";
$sql="SELECT * FROM bio_cashheads";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['head_id']==$_POST['head'])  
    {   
    echo '<option selected value="';
    }
     else 
    {
    if ($f==0) 
        {
        echo '<option value=-1>';
        }
        echo '<option value="';
    }
    echo $myrow1['head_id'] . '">'.$myrow1['heads'];
    echo '</option>';
    $f++;
   }   
echo"</select></td></tr>";

echo"<tr><td>Advance Amount*</td>";
echo"<td><input type='text' name='advanceamount' id='advanceamount' style='width:150px'></td></tr>";

echo'<tr>';
echo'<td width=50%>Mode of payment:</td>';
echo'<td><select name="mode" id="paymentmode" style="width:150px" onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$_POST['modes'])
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
    echo $myrow1['id'] . '">'.$myrow1['modes'];  
    echo '</option>';
  }
echo'</select></td></tr>';

echo"</table>";

echo"<table id='modeamt'>";
echo"</table>"; 
echo"<table id='amt'>";
echo"</table>"; 

echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>";
echo"</div>";


//========================================== Buttons 
echo"<table>";
echo'<tr><td colspan=2><p><div class="centre">
<input type=submit name=submit value="' . _('Submit') . '" onclick="if(log_in()==1)return false;">';
echo '<input name="clear" type="submit" value=Clear >';

echo'</div>';
echo'</td></tr>';

echo'</div>'; 
echo"</td></tr></table>";
echo'</form>';

//========================================== Buttons Ends

//========================================== Grid - from leads table
if(!isset($_GET['task']))
{  
echo'<div id="leadgrid">';
echo"<fieldset style='width:760px'><legend>Lead Details</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>"; 
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname1' id='byname1'></td>";
echo"<td><input type='text' name='byplace1' id='byplace1'></td>";
echo '<td><select name="off1" id="off1" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_office";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo '</select></td>';
echo '<td><select name="leadsrc1" id="leadsrc1" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';
$sql1="select * from bio_leadsources";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
}                                                             
echo '</select></td>';      

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='search' onclick='if(log_in()==1)return false'></td>";
echo"</tr>";
echo"</table>";


echo "<div style='height:200px; overflow:scroll;'>"; 
echo"<table style='width:100%'> ";

echo"<tr><th>Slno</th><th>Leadid</th><th>Name</th><th>Place</th><th>Date</th><th>Enquiry Type</th><th>Status</th><th>Team</th></tr>";
$office=$_SESSION['UserStockLocation'];   
//---------------------------------------------------------------------------------------------------------------------------------------------------------
    $s_userid=$_SESSION['UserID'];
    $s_offid=$_SESSION['officeid'];
    
            $office_arr=array();
        $office_arr[]=$s_offid;
           
     $sql2="SELECT reporting_off,id
            FROM bio_office
            WHERE reporting_off=$s_offid
            ";
              
     $result2=DB_query($sql2,$db);
     $myrow_count = DB_num_rows($result2);
     
     if($myrow_count>0){
     while($row2=DB_fetch_array($result2)){
         $office_arr[]=$row2['id'];   
        
     $sql3="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row2['id']."";
        $result3=DB_query($sql3,$db);
        $myrow_count1 = DB_num_rows($result3);
     if($myrow_count1>0){
     while($row3=DB_fetch_array($result3)){
               $office_arr[]=$row3['id'];       
       
     $sql4="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row3['id']."";
        $result4=DB_query($sql4,$db);
        $myrow_count2 = DB_num_rows($result4);
     if($myrow_count2>0){
     while($row4=DB_fetch_array($result4)){
               $office_arr[]=$row4['id'];        
//         echo $row3['id'];
            
        }
        }  
        }   
     }
     }
     }
     
     $office_array=join(",", $office_arr);
     $sql5="SELECT *  
                FROM bio_emp
                WHERE offid IN ($office_array)";
     $result5=DB_query($sql5,$db);
     while($row5=DB_fetch_array($result5)){
//         $row5['empid'];
    
    $sql6="SELECT userid FROM www_users WHERE empid=".$row5['empid'];
    $result6=DB_query($sql6,$db);
    $row6=DB_fetch_array($result6);
    $userid[]="'".$row6[0]."'";     
    $user_array=join(",", $userid); 
               
     }                      
      
  $sql="SELECT bio_leads.leadid AS leadid,
  bio_cust.cust_id AS custid,  
  bio_cust.custname AS custname,               
  bio_cust.area1 AS place,
  bio_leads.leaddate AS leaddate,
                      bio_status.biostatus,  


  bio_enquirytypes.enqtypeid AS enqtypeid,
  bio_enquirytypes.enquirytype AS enqtype, 
  bio_leadteams.teamname AS teamname,
  bio_leadsources.sourcename AS sourcename,
  bio_office.id AS officeid,
  bio_office.office AS office
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_status,
bio_enquirytypes,
bio_leadsources,
bio_office  
WHERE bio_cust.cust_id=bio_leads.cust_id 
AND bio_leadteams.teamid=bio_leads.teamid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid

              AND bio_status.statusid=bio_leads.leadstatus
AND bio_leadsources.id=bio_leads.sourceid
AND bio_office.id=bio_leadsources.officeid  
 
";                            

 if(isset($_POST['filterbut1']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off1'];
  //  echo $officeid;
    if (isset($_POST['byname1']))  {        
    if ($_POST['byname1']!='')   
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname1']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace1'])) {
    if ($_POST['byplace1']!='') 
    $sql .= " AND bio_cust.area1 LIKE '%".$_POST['byplace1']."%'"; 
    }
    
    if (isset($_POST['off1']))    {
    if (($_POST['off1']!='')&&($_POST['off1']!='0'))
    $sql .=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc1'])) {
    if (($_POST['leadsrc1']!='ALL') && ($_POST['leadsrc1']!=0))
    $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc1']."'";
    }
 }
 $sql .=" ORDER BY leadid ASC";      
$result=DB_query($sql,$db);
echo '<tbody>';
echo '<tr>';                                       
$no=0; 
$k=0; 
while($myrow=DB_fetch_array($result))
{
    
    $no++;
    if ($k==1)
    {
        echo '<tr class="EvenTableRows">';
        $k=0;
    }else 
    {
        echo '<tr class="OddTableRows">';
//                    $k=1;     
    }
    $leadid=$myrow['leadid'];
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td> 
            <td>%s</td> 
            <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td></tr>",
            $no,
            $leadid,
            $myrow['custname'],
            $myrow['place'],
            ConvertSQLDate($myrow['leaddate']),
            $myrow['enqtype'],
            $myrow['biostatus'],
            $myrow['teamname']);
           
}
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';
}
echo"</td></tr></table>"; 
echo"</div>";
include('includes/footer.inc'); 
?>

<script type="text/javascript">     

function log_in()
{
//    document.getElementById('phone').focus();
     var h=document.getElementById('head').value;

var f=0;
var p=0;  
       if(h==-1){f=common_error('Head','Please Select a Payment head');f=1; if(f==1){return f; }  }  

if(f==0){f=common_error('custname','Please select a lead');  if(f==1){return f; }  }  
if(f==0){f=common_error('team','Please select a team');  if(f==1){return f; }  } 
if(f==0){f=common_error('startdate','Please enter start date');  if(f==1){return f; }  }  
if(f==0){f=common_error('enddate','Please enter end date');  if(f==1){return f; }  } 

}

 function san()
{
    alert("sdf");
   myRef=window.open("bio_print_A5p.php");
      
    
}

function passid(str1,str2){
//alert(str1);
if (str1=="")
  {
  document.getElementById("panel").innerHTML="";
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
    document.getElementById("panel").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_multiplePaydetails.php?p=" + str1,true);
xmlhttp.send(); 
}



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
xmlhttp.open("GET","bio_fsamountdetails.php?adv=" + str,true);
xmlhttp.send();    
}


</script>