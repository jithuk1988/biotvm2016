<?php
$PageSecurity = 30;
include('includes/session.inc');
$title = _('Approval');  
include('includes/header.inc');
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Pending Approvals</font></center>';
    $office=$_SESSION['UserStockLocation'];
    
if(isset($_GET['remove']))      {

$stockid=$_GET['stockid'];    
$leadid=$_GET['lead'];
    
$sql="DELETE
      FROM bio_temppropitems
      WHERE leadid= $leadid AND
            stockid='$stockid'";    
$result=DB_query($sql,$db);

$sql_delete="DELETE FROM bio_temppropsubsidy
                   WHERE bio_temppropsubsidy.leadid=".$leadid."
                   AND bio_temppropsubsidy.stockid='".$stockid."'";
$result_delete = DB_query($sql_delete,$db);
    
}
    
    
if (isset($_POST['submit'])) {
    if(!isset($_POST['LeadID'])){   
      prnMsg('Select approval details from the grid','warn');  
    }
    else{
    $lead_ID=$_POST['LeadID'];
    $app_status=$_POST['app_status'];
    $amount=$_POST['advanceamount'];
    $team_ID=$_POST['team'];
    $task_ID=$_POST['TaskID']; 
    $proposal_ID=$_POST['ProposalID'];
    
if($app_status==1){
$sql="UPDATE bio_approval SET taskcompletedstatus =1,taskcompleteddate=".date("Y-m-d")." 
                                     WHERE leadid =$lead_ID
                                     AND proposal_no=$proposal_ID";
}
elseif($app_status==2){
$sql="UPDATE bio_approval SET taskcompletedstatus = 2 
                                     WHERE leadid =$lead_ID
                                     AND proposal_no=$proposal_ID";  
}
$result=DB_query($sql,$db);
if(($app_status==1) AND ($task_ID==6)){
$sql1="UPDATE bio_proposal SET status =4 
                         WHERE leadid =$lead_ID
                         AND propid=$proposal_ID";
$sql_lead="UPDATE bio_leads
                        SET leadstatus=1
                        WHERE leadid='$lead_ID'";
$result_lead= DB_query($sql_lead,$db);
}

elseif(($app_status==1) AND ($task_ID==7)){
$sql1="UPDATE bio_feasibilitystudy SET feasibility_status = 4 WHERE leadid =$lead_ID";
$sql_lead="UPDATE bio_leads
                        SET leadstatus=3
                        WHERE leadid='$lead_ID'";
$result_lead= DB_query($sql_lead,$db);
}
elseif(($app_status==2) AND ($task_ID==7)){
$sql1="UPDATE bio_feasibilitystudy SET feasibility_status = 3 WHERE leadid =$lead_ID";
}



if(($app_status==1) AND ( $task_ID==10)){
    
         $sql_item_delt="DELETE FROM bio_conceptproposaldetails WHERE cp_id=".$proposal_ID;
         $result_item_delt=DB_query($sql_item_delt,$db); 
    
$sql_select="SELECT stockid,description,qty,price,tprice 
             FROM   bio_temppropitems 
             WHERE  leadid=".$lead_ID;
$result_select=DB_query($sql_select,$db);

$i=0;
while($row_select=DB_fetch_array($result_select))
{
    $i++;
               $sql4="INSERT INTO bio_conceptproposaldetails (cp_id,
                                                        slno,
                                                        stockid,
                                                        description,
                                                        qty,
                                                        price,
                                                        tprice) 
                                              VALUES (".$proposal_ID.",
                                                      ".$i.",
                                                     '".$row_select['stockid']."',
                                                     '".$row_select['description']."',
                                                      ".$row_select['qty'].",
                                                      ".$row_select['price'].",
                                                      ".$row_select['tprice'].")";
                $result4=DB_query($sql4,$db);
}

$sql1="SELECT SUM(tprice) FROM bio_temppropitems WHERE leadid=".$lead_ID;
$result1=DB_query($sql1,$db);
$row1=DB_fetch_array($result1);
$sum=$row1[0];

            $sql1="UPDATE bio_conceptproposal SET total_price=$sum,status = 4 WHERE lead_id =$lead_ID";
            $sql_lead="UPDATE bio_leads
                                    SET leadstatus=4
                                    WHERE leadid='$lead_ID'";
            $result_lead= DB_query($sql_lead,$db);

}elseif(($app_status==2) AND ($task_ID==10)){
            $sql1="UPDATE bio_conceptproposal SET status = 3 WHERE lead_id = $lead_ID";
}

      
        if(($app_status==1) AND ($task_ID==9)){  
        $sql1="UPDATE bio_conceptproposal SET status = 4 WHERE lead_id =$lead_ID";
        $sql_lead="UPDATE bio_leads
                                SET leadstatus=4
                                WHERE leadid='$lead_ID'";
        $result_lead= DB_query($sql_lead,$db);
        }
        elseif(($app_status==2) AND ($task_ID==9)){
        $sql1="UPDATE bio_conceptproposal SET status = 3 WHERE lead_id = $lead_ID";
        }  






if(($app_status==1) AND ($task_ID==11)){
$sql1="UPDATE bio_dpr SET dpr_status = 4 WHERE leadid =$lead_ID";
$sql_lead="UPDATE bio_leads 
                        SET leadstatus=5
                        WHERE leadid='$lead_ID'";
$result_lead= DB_query($sql_lead,$db);
}
elseif(($app_status==2) AND ($task_ID==11)){
$sql1="UPDATE bio_dpr SET dpr_status = 3 WHERE leadid =$lead_ID";
}
if(($app_status==1) AND ($task_ID==16)){
$sql1="UPDATE bio_fsproposal SET fp_approvalstatus = 4 WHERE leadid =$lead_ID";

$sql_cur_st="SELECT leadstatus FROM bio_leads
                WHERE leadid=$lead_ID";
$result_cur_st= DB_query($sql_cur_st,$db);
$myrow_cur_st=DB_fetch_array($result_cur_st);
$current_st=$myrow_cur_st['leadstatus'];

if($current_st==47){
    $sql_lead="UPDATE bio_leads
                        SET leadstatus=26
                        WHERE leadid='$lead_ID'";
$result_lead= DB_query($sql_lead,$db);
}
}
elseif(($app_status==2) AND ($task_ID==16)){
$sql1="UPDATE bio_fsproposal SET fp_approvalstatus = 3 WHERE leadid =$lead_ID";
}
$result1=DB_query($sql1,$db);  
    unset($_POST['team']);
    unset($_POST['StartDate']);
    unset($_POST['EndDate']);
 }    
if(isset($_POST['Reason']))
{
$reason=$_POST['Reason'];   
$sql_reject="INSERT INTO bio_rejectedproposal(leadid,
                                               task,
                                               reason)
                                  VALUES ('".$lead_ID."',
                                          '".$task_ID."',
                                          '".$reason."')";                                           
        $result = DB_query($sql_reject,$db);    
}
}   

echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style="width:65%"><tr><td>';
echo"<div id=panel>";
echo '<table><tr>';
//========================================== Left Panel Begins

echo'<td>';
echo"<div id=cus_details>";
echo"<fieldset style='width:800px;height:150px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";
echo"<table width=100%>";
if($_GET['lead']!=''){
    $leadid=$_GET['lead'];    
    $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality  
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result); 
 $no=0; 
 $k=0; 
 $cname=$myrow[1];  
 if($myrow[2]!='-'){
     $cph=$myrow[2]; 
 }else{
     $cph=$myrow[3]; 
 }
 $email=$myrow['custmail'];  
 $place=$myrow[5];
 $dist=$myrow[6];
 $ste=$myrow[7];
 $ctry=$myrow[8]; 
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];
 
 $sql2="SELECT taskid FROM bio_approval WHERE leadid=$leadid";
 $result2=DB_query($sql2,$db);
 $row2=DB_fetch_array($result2);
 $taskid=$row2['taskid'];       
}
echo"<tr><td width=50%>Customer Name</td>";
echo"<td><input type='text' name='custname' id='custname' value='$cname'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone</td>";
echo"<td><input type='text' name='custph' id='custph' value='$cph'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='text' name='email' id='email' value='$email'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place</td>";
echo"<td><input type='text' name='custplace' id='custplace' value='$place'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District</td>";
echo"<td><input type='text' name='custdist' id='custdist' value='$district'></td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>"; 
echo"<input type='hidden' name='TaskID' id='taskid' value='$taskid'>";
echo'</div>';
echo"</td>";
//========================================== Left Panel Ends 
/*
    //========================================== Right Panel Begins
    echo'<td valign=top>';    
    echo'<div id=right_panel_1>';
    echo"<fieldset style='width:400px;height:150px; overflow:auto;'>";
    echo"<legend>Proposal Details</legend>";
    echo"<table>";
    echo"<tr><td>Change Status</td>";
    echo"<td><select name='app_status' id='app_status' style='width:190px'>";
    $sql="SELECT * FROM bio_approvalstatus";
    $result=DB_query($sql,$db);
        $f=0;
      while($myrow1=DB_fetch_array($result))
      {  
      if ($myrow1['app_statusid']==$_POST['app_status'])  
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
        echo $myrow1['app_statusid'] . '">'.$myrow1['app_status'];
        echo '</option>';
        $f++;
       }   
    echo"</select></td></tr>";
    echo"</table>";
    echo"</fieldset>";
    echo"</div>";
    echo"</td>";*/
    
    echo"</tr></table>";    
    echo"</div>";               
//========================================== Buttons 
echo"<table>";
echo'<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Submit') . '" onclick="if(log_in()==1)return false;">';
echo '<input name="clear" type="submit" value=Clear >';
echo'</div>';
echo'</td></tr>';
echo'</table>';
//========================================== Buttons Ends   
 
echo"</td></tr></table>";       
echo'</form>';
echo'</div>';
/*
function displayremove($stockid,$leadid,$db)
{
    
}

//-----------------------select plant----------------------//

if($_GET['first']==4)
{
    $stockid=$_GET['stockid'];
    $leadid=$_GET['lead'];
    displayremove($stockid,$leadid,$db); 
     
}
  */


//========================================== Grid - from leads table
echo'<div id="leadgrid">';
echo"<fieldset style='width:830px'><legend>Pending for Approval</legend>";
echo'<div style="height:200px;overflow:scroll">';
echo"<table style='width:100%'> ";
echo"<tr><th>Slno</th><th>Leadid</th><th>Name</th><th>Place</th><th>Date</th><th>Task</th><th>Submitted By</th></tr>";
$office=$_SESSION['UserStockLocation'];   
//====================================================================================================================================================//
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
  bio_approval.assigneddate AS assigneddate,
  bio_approval.submitted_user AS submittedby,
  bio_task.task AS task,  
  bio_task.task AS taskid,
  bio_approval.taskcompletedstatus AS taskstatus,
  bio_approval.tid
FROM bio_cust,
bio_leads,
bio_approval,
bio_task
WHERE bio_approval.approval_user='$_SESSION[UserID]'
AND bio_leads.leadid=bio_approval.leadid
AND bio_approval.taskcompletedstatus=0 
AND bio_task.taskid=bio_approval.taskid 
AND bio_cust.cust_id=bio_leads.cust_id  
ORDER BY assigneddate ASC
";                                   
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
        $k=1;     
    }
    $leadid=$myrow['leadid'];
    $id=$myrow['tid'];
    $submitted_by=$myrow['submittedby'];
    $sql_emp="SELECT  bio_emp.empname
        FROM  bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid='".$submitted_by."'";
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
      $empname=$myrow_emp['empname']; 
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td> 
            <td><a  style='cursor:pointer;'  id='$id' onclick='passid($leadid,$id)'>" . _('select') . "</a></td></tr>",
            $no,
            $leadid,
            $myrow['custname'],
            $myrow['place'],
            ConvertSQLDate($myrow['assigneddate']),
            $myrow['task'],
            $empname);          
}
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 
echo"</div>";
?>
<script type="text/javascript">     
function log_in()
{
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('custname','Please select a lead');  if(f==1){return f; }  }  
if(f==0){f=common_error('team','Please select a team');  if(f==1){return f; }  } 
if(f==0){f=common_error('startdate','Please enter start date');  if(f==1){return f; }  }  
if(f==0){f=common_error('enddate','Please enter end date');  if(f==1){return f; }  } 
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
    }
  } 
xmlhttp.open("GET","bio_approvalDetails.php?p=" + str1 + "&q=" + str2,true);
xmlhttp.send(); 
}
function enterreason(str1){  
//var f=0;
//var p=0; 
//    
// if(f==0)  {
// f=common_error('reason','Please enter the reason');  if(f==1){return f; } 
// }     
//alert(str1);
if (str1!=2)
  {
  document.getElementById("reject").innerHTML="";
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
    document.getElementById("reject").innerHTML=xmlhttp.responseText;  
    }
  } 
xmlhttp.open("GET","bio_rejectedtask.php?p=" + str1,true);
xmlhttp.send(); 
}

function updatetotalitemprice(k,lead,stock){ //var a="#"+str;
//$(a).hide();
// alert(str);
//$("#grid").hide();
if (stock=="")
  {
  document.getElementById("tprice").value="";
  return;
  }
 var s=stock;
 var q=document.getElementById('qty'+k).value;
 var p=document.getElementById('price'+k).value;
 var t=document.getElementById('tprice'+k).value=q*p;
 var sub1=document.getElementById('subsidy'+k).value;
 var sub=q*sub1;
 var n=document.getElementById('netprice'+k).value=t-sub;


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
    document.getElementById("tprice").value=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","bio_updateproptempprice.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp.send();

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp2.onreadystatechange=function()
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
    document.getElementById("subsidy").value=xmlhttp2.responseText;
    }
  }
xmlhttp2.open("GET","bio_updateproptempsubsidy.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp2.send();


if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp1.onreadystatechange=function()
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    document.getElementById("netprice").value=xmlhttp1.responseText;
    }
  }
xmlhttp1.open("GET","bio_updateproptempnetprice.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp1.send();

}

//function addSubsidy(lead,item,first,add)  {
//  controlWindow=window.open("bio_biocpaddsubsidy.php?ledid="+lead+"&item="+item+"&first="+first+"&add="+add,"addsubsidy","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=500");
//}

function removeitm(lead,item,first,remove)  {
 //   alert(first);
    
//    first=3;
//    var remove=1;
location.href = "bio_approval.php?stockid=" + item + "&lead=" + lead + "&first=" + first + "&remove=" + remove;
}

function selectplant(str,str2,str3){
controlWindow=window.open("bio_selectplantfor_cp.php?lead="+str+"&first="+str2+"&budgetinitial="+str3,"selplant","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}

</script>