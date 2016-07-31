<?php 

$PageSecurity = 80;
include('includes/session.inc');
//

$title = _('Lead Details');
 
if(!isset($_POST['excel']))
{
  include('includes/header.inc');     
  include('includes/sidemenu.php');
     $empid=$_SESSION['empid'];
     $sql_emp1="SELECT * FROM bio_emp
                WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
 $employee_arr=array();   
     $sql_drop="DROP TABLE `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db,$y)         
                  {  
                      $sql3="SELECT empid FROM bio_emp WHERE reportto='".$empid."'";
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
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($_SESSION[empid])";
                      $result_insert2=DB_query($sql_insert1,$db);
     
     $sql2="SELECT empid,reportto FROM bio_emp WHERE reportto=$empid";
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
    while($row6=DB_fetch_array($result6)){
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);
      
  
  echo"<div id='editleads'>";
  echo "<table style='width:75%'><tr><td>";
  
 /* echo "<fieldset style='width:20%;height:440px'>";     
  echo "<legend><h3>Search By</h3>";     
  echo "</legend>";   */

    echo '<form name="leadsfrom"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    echo '<form name="leadsfrom"  method="post" action="leadDetailsXL.php">';
  
  echo "<td>"; 
  echo "<div id='printandshow' style='margin:auto;'>";
  echo "<fieldset style='width:100%;height:440px;'>";     
  echo "<legend><h3>Lead Details</h3>";     
  echo "</legend>";
  echo "<table style='border:1px solid #F0F0F0;width:100%'>";
   
   echo '<tr><td class="actiondate">Created On</td>
             <td class="byname">Name</td>
             <td class="bydistrict">District</td>
             <td class="bycusttype">Customer Type</td>
             <td class="byteam">Team</td>
             <td class="byoffice">Office</td>
             <td class="leadsource">Lead Source</td>
             <td class="advance">Advance</td> 
             <td class="status">Lead Status</td>  
             </tr>';
             
   echo '<tr><td class="actiondate"><select name="Actiondate" id="actiondate" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="1">Today</option>';
   //echo '<option value="2">Tommorrow</option>';
   echo '<option value="3">Yesterday</option>';
   echo '<option value="4">Overdue</option>';
   echo '<option value="5">ALL</option>';
   echo '<option selected='.$_POST['Actiondate'].'>'.$actiondatedesc.'</option>'; 
   echo '</select></td>';
   
   
   //  echo '<tr><td><select name="Amount" id="amount" style="width:120px">';
//   echo '<option value=0></option>';
//   echo '<option value="1">Paid</option>';
   //echo '<option value="2">Tommorrow</option>';
//   echo '<option value="2">Not paid</option>';
//   
//   echo '<option selected='.$_POST['Amount'].'></option>'; 
//   echo '</select></td>';
   
   
   echo '<td class="byname"><input type="text" name="byname" id="byname" style="width:120px"></td>';
   echo '<td  class="bydistrict"><input type="text" name="byplace" id="byplace" style="width:120px"></td>';
    
   echo '<td class="bycusttype"><select name="enquiry" id="enquiry" style="width:120px">';
   echo '<option value=0></option>';  
   $sql2="select * from bio_enquirytypes";
   $result2=DB_query($sql2,$db);
   while($row2=DB_fetch_array($result2))
   {
       if ($row2['enqtypeid']==$_POST['enquiry'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row2['enqtypeid'] . '">'.$row2['enquirytype'];
       echo '</option>';
   } 
                                                            
   echo '</select></td>';      
   
   echo '<td class="byteam"><select name="team" id="team" style="width:120px">';
   echo '<option value=0></option>'; 
   $sql1="select * from bio_leadteams";
   $result1=DB_query($sql1,$db);
   while($row1=DB_fetch_array($result1))
   {
       if ($row1['teamid']==$_POST['team'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['teamid'] . '">'.$row1['teamname'];
       echo '</option>';
   }
   echo '</select></td>';
   
   echo '<td class="byoffice"><select name="off" id="off" style="width:120px">';
   echo '<option value=0></option>'; 
   $sql1="select * from bio_office";
   $result1=DB_query($sql1,$db);
   while($row1=DB_fetch_array($result1))
   {
       if ($row1['id']==$_POST['off'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['id'] . '">'.$row1['office'];
       echo '</option>';
   }
   echo '</select></td>';  
   
   echo '<td class="leadsource"><select name="leadsrc" id="leadsrc" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="ALL">Select ALL</option>';
   $sql1="select * from bio_leadsources";
   $result1=DB_query($sql1,$db);
   while($row1=DB_fetch_array($result1))
   {
       if ($row1['id']==$_POST['leadsrc'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['id'] . '">'.$row1['sourcename'];
       echo '</option>';
   }                                                             
   echo '</select></td>';
   
   
   
    
     echo '<td class="advance"><select name="Amount" id="Amount" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="1">Paid</option>';
   echo '<option value="2">Not paid</option>';
   
   echo '<option selected='.$_POST['Amount'].'></option>'; 
   echo '</select></td>';
   
   
   echo'<td class="status"><select name="LeadStatus" id="leadstatus" style="width:150px">';
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
   
   
   
   
   /*echo '<td><select name="Created" id="created" style="width:120px">';
   echo '<option></option>';
   $sql1="SELECT www_users.userid, bio_emp.empname
        FROM bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid IN ($user_array)";
   $result1=DB_query($sql1,$db);     
   while($row1=DB_fetch_array($result1))
   {
       if ($row1['userid']==$_POST['Created'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['userid'] . '">'.$row1['empname'];
       echo '</option>';
   }                                                             
   echo '</select></td>';*/
   echo '</tr>';
   
  
    
   echo '<td><input type="submit" name="filterbut" id="filterbut" value="search"></td></tr>';
      echo '</table>';

      echo "<div  overflow:auto;'>";
      echo "<table style='border:1px solid #F0F0F0;width:100%'>"; 
       
      echo "<table  width=100% id='leadreport'>";      //grid head
      echo "<thead>
         <tr BGCOLOR =#800000>
         <th width='23%'>" . _('Institution Name/<br>Customer Name') . "</th>
         <th width='11%'>" . _('Contact No:') . "</th>
         <th width='15%'>" . _('District') . "</th>
         <th width='13%'>" . _('Next Action') . "</th> 
         <th width='9%'>" . _('Action Date') . "</th>
         <th width='18%'>" . _('Status') . "</th> 
         <th width='6%'>" . _('Advance Amount') . "</th> 
         <th width='5%'>" . _('View') . "</th>  
         </tr></thead>";
         
      echo "</table>";
      echo "<div style='height:300px; overflow:auto;'>";
      echo "<table  style='width:100%;' id='leaddetails'>";       //grid data    
         
  $enquiry=0;
  $usr=0;                          
  $sql="SELECT bio_cust.cust_id AS custid,  
               bio_cust.custname AS custname,               
               bio_cust.houseno AS houseno,               
               bio_cust.housename AS housename,
               bio_cust.area1 AS place,
               bio_cust.custphone AS custphone,
               bio_cust.custmob AS custmob,
               bio_cust.contactperson,
               bio_enquirytypes.enqtypeid AS enqtypeid,
               bio_enquirytypes.enquirytype AS enqtype,
               bio_leads.outputtypeid AS outputtypeid,
               bio_outputtypes.outputtypeid,
               bio_outputtypes.outputtype AS outputtype,
               bio_cust.district AS districtid,
               bio_leadtask.leadid AS leadid, 
               bio_leads.leaddate AS leaddate,
               bio_leadteams.teamname AS teamname,
               bio_leadsources.sourcename AS sourcename,
               bio_cust.custmail AS custmail,
               bio_cust.custmob AS custmob,
               bio_leads.advanceamount AS advance,
               bio_cust.state AS state,
               bio_district.district AS district,
               bio_office.id AS officeid,
               bio_office.office AS office,
               bio_leads.remarks AS remarks,
               bio_status.biostatus,
               bio_leads.created_by
          FROM bio_cust,
               bio_leads,
               bio_leadteams,
               bio_leadsources,
               bio_enquirytypes,
               bio_district,
               bio_office,
               bio_outputtypes,
               bio_status,
               bio_leadtask
         WHERE bio_cust.cust_id=bio_leads.cust_id 
           AND bio_leadteams.teamid=bio_leads.teamid 
           AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
           AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
           AND bio_leadsources.id=bio_leads.sourceid  
           AND bio_district.did=bio_cust.district
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality
           AND bio_office.id=bio_leadsources.officeid
           AND bio_status.statusid=bio_leads.leadstatus
           AND bio_leadtask.teamid IN ($team_array) 
           AND bio_leadtask.leadid=bio_leads.leadid  
           
       
"; 
/*  AND bio_leadtask.taskid!=0   
           AND bio_leadtask.taskcompletedstatus=0
           */
           $Currentdate=FormatDateForSQL(Date("d/m/Y"));
                           
if(isset($_POST['filterbut']))
 { 
 
    if (isset($_POST['Actiondate']))  {        
    if ($_POST['Actiondate']!='')   {  
    if ($_POST['Actiondate']==1) {
        
    $sql.=" AND bio_leads.leaddate = '".$Currentdate."'";
        
    }
    if ($_POST['Actiondate']==2) {
        
    $date=explode("-",$Currentdate);

  $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
  $Tommorrow1 = strtotime($startdate2 . " +1 day");
  $Tommorrow=date("d/m/Y",$Tommorrow1);

  $Tommorrow2=FormatDateForSQL($Tommorrow);    
        
    $sql.=" AND bio_leads.leaddate = '".$Tommorrow2."'";
        
    }
    if ($_POST['Actiondate']==3) {
        
  $date=explode("-",$Currentdate);

  $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
  $Yesterday1 = strtotime($startdate2 . " -1 day");
  $Yesterday=date("d/m/Y",$Yesterday1);

  $Yesterday2=FormatDateForSQL($Yesterday);    
        
    $sql.=" AND bio_leads.leaddate = '".$Yesterday2."'";
        
    }
    if ($_POST['Actiondate']==4) {
        
    $sql.=" AND bio_leads.leaddate < '".$Currentdate."'";
        
    }
    if ($_POST['Actiondate']==5) {
        
    
        
    }   
    }
    } 
/*    
  if (isset($_POST['Amount']))  {        
     if ($_POST['Amount']!='') {
      if ($_POST['Amount']==1) {  
     
   $sql.="AND bio_advance.amount>0 AND head_id=8";                
      }  
         elseif ($_POST['Amount']==2) {
               
//         $sql.="AND (bio_advance.amount=0 AND head_id=8) OR (bio_advance.amount>=0 AND head_id!=8)";
             
             
         } 
     }          
    } */ 
    if ((isset($_POST['datef'])) && (isset($_POST['datet'])))   {
    if (($_POST['datef']!="") && ($_POST['datet']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['datef']);   
    $sourcetypeto=FormatDateForSQL($_POST['datet']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off'];
  //  echo $officeid;
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql.= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql.= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }
    
    if (isset($_POST['team']))    {
    if (($_POST['team']!='')&&($_POST['team']!='0'))
    $sql.=" AND bio_leadtask.teamid='".$_POST['team']."'";    
    }
    
    if (isset($_POST['off']))    {
    if (($_POST['off']!='')&&($_POST['off']!='0'))
    $sql.=" AND bio_leadsources.officeid=".$_POST['off'];
    }
    
    if (isset($_POST['enquiry'])) {
    if (($_POST['enquiry']!='ALL') && ($_POST['enquiry']!=0))
    $sql.=" AND bio_enquirytypes.enqtypeid='".$_POST['enquiry']."'";
    $enquiry=1;
    } 
    
    if (isset($_POST['leadsrc'])) {
    if (($_POST['leadsrc']!='ALL') && ($_POST['leadsrc']!=0))
    $sql.=" AND bio_leads.sourceid='".$_POST['leadsrc']."'";
    }
    if (isset($_POST['Created'])) {   
    if ($_POST['Created']!=''){
       $sql.=" AND bio_leads.created_by='".$_POST['Created']."'";
       $usr=1; 
    }
    }
    
    if (isset($_POST['LeadStatus'])) {   
    if ($_POST['LeadStatus']!=''){
       $sql.=" AND bio_status.statusid='".$_POST['LeadStatus']."'";
      
    } 
    
    } 
    
    
    
    
}else{
    
    
    /*$sql.=" AND bio_leads.created_by IN ($user_array)";
    $usr=1;*/
    
    if($myrow_emp1['designationid']<=8){
    if($enquiry==0){
       $sql.="AND bio_leads.enqtypeid=2";
       $enquiry=1; 
    }
    }
    
}
     



//if($myrow_emp1['designationid']<=8){
//    if($enquiry==0){
//       $sql.="AND bio_leads.enqtypeid=2";
//       $enquiry=1;
//    }
//}elseif($usr==0){
//    $sql.=" AND bio_leads.created_by IN ($user_array)";
//    $usr=1;
//    
//}
    $sql.=" GROUP BY bio_leadtask.leadid ORDER by bio_leads.leadid desc,bio_leadtask.assigneddate ASC";
    
   
// echo$sql;       
         
         
         
         

function convertsqldatefordis($d) 
{
    $array=explode('-',$d);
    $dd="$array[2]/$array[1]/$array[0]";
    return $dd;        
}  


   $result=DB_query($sql,$db);  
   
      
   echo '<tbody>';
   echo '<tr>';                                       
     
      $no=0;
      $k=0; 
      while($myrow=DB_fetch_array($result))
      {    
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;                   
                }
                 else 
                {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                }
      $no++;
      $leadid=$myrow['leadid']; 
      $enq=$myrow['enqtypeid']; 
      $created_by=$myrow['created_by'];
      $sql_emp="SELECT  bio_emp.empname
        FROM  bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid='".$created_by."'";
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
      $empname=$myrow_emp['empname'];
      $custname=$myrow['custname'];
      
      $contact_person=$custname;
      
      $custmob=$myrow['custmob'];
     if($custmob=="" OR $custmob==0){
          $custmob=$myrow['custphone'];
          if($custmob=="" OR $custmob==0){
              $custmob="";
          }
      } 
      if($myrow['enqtypeid']==2){
          if($myrow['contactperson']!="" OR $myrow['contactperson']!=0){
             $custname=$myrow['custname']."<br /> - ".$myrow['contactperson']; 
          }else{
             $custname=$myrow['custname']; 
          }
      }
      $sql_adv="SELECT SUM(amount) FROM bio_advance
                WHERE leadid=".$leadid."
                AND head_id=8";
      $result_adv=DB_query($sql_adv,$db);
      $myrow_adv=DB_fetch_array($result_adv);
      $advance_amount=$myrow_adv[0];
      if($advance_amount==0){
         $advance_amount='Not paid';
      }
      $sql_action="SELECT * FROM bio_leadtask,bio_task
                    WHERE bio_leadtask.leadid=".$leadid."
                    AND bio_leadtask.taskcompletedstatus=0
                    AND bio_task.taskid=bio_leadtask.taskid
                    ORDER BY bio_leadtask.assigneddate ASC";
      $result_action=DB_query($sql_action,$db);
      $myrow_action=DB_fetch_array($result_action);
      $next_action=$myrow_action['task']; 
      $action_date=$myrow_action['assigneddate'];
      if($action_date!="" AND $next_action!==""){
         $action_date=ConvertSQLDate($action_date);
      }else{
         $next_action='Not assigned';
         $action_date='Not assigned'; 
      }

      echo "<input type='hidden' id='leadid' name='leadid' value='$leadid'>";    
printf("<td cellpading=2>%s</td>

        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td><a  style='cursor:pointer;'  onclick=showCD2('$leadid','$enq')>" . _('View') . "</a></td> 
        <td><a  style='cursor:pointer;'  onclick=viewdetails('$leadid','$enq')>" . _('Edit') . "</a></td>    
        </tr>",
        $custname,
        $custmob,
        $myrow['district'],
        $next_action, 
        $action_date,
        $myrow['biostatus'],
        $advance_amount,
        $_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]);
        }                
                     
              
   echo '</tbody>'; 
   echo '</table>';  
   echo '<input type="submit" name="excel" value="View as Excel">';       
   echo '</div>'; 
   
   
      echo '</table>';  
      echo '</div>'; 
      
      echo "</fieldset>";
      echo '</div>';
      echo '</form>';
      
      echo "</td></tr></table>"; 
      echo "</div>";
                                      
?> 

<script>

$(document).ready(function(){   
    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);
         
//  $('.actionon').hide();     
        
//      $(".skip").hide();   
<?php if($_SESSION['filters']!=""){ ?>    
var xx = new Array("<?php echo implode(",",$_SESSION['filters']);?>");

var mySplitResult = xx[0].split(",");  

for(var i=0; i<mySplitResult.length;i++)        {

var filter='.'+mySplitResult[i]
//alert(filter);    
$(filter).hide();   
}
<?php } ?>    
});          

//function actionon(str1){
//    if(str1==2){
//      $('.actionon').show();  
//    }
//}


function showCD2(str1,str2){
       
//         window.location="bio_viewleaddetails.php?q=" + str1 + "&en=" + str2;
         myRef = window.open("bio_viewleaddetails.php?q=" + str1 + "&en=" + str2);
    }

  function viewdetails(str1,str2){
       
//         window.location="bio_viewleaddetails.php?q=" + str1 + "&en=" + str2;
         myRef = window.open("bio_editlead.php?q=" + str1 + "&en=" + str2);
    }

</script>

<?php

} 
 
if(isset($_POST['excel']))
{
               $empid=$_SESSION['empid'];

         $employee_arr1=array();
 $sql_drop="DROP TABLE `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                        $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db,$y)         
                  {  
                      $sql3="SELECT empid FROM bio_emp WHERE reportto='".$empid."'";
                      $result3=DB_query($sql3,$db);
                      

                      
                      $employee_arr=array();
                      while($row3=DB_fetch_array($result3))
                      {
                      $empid=$row3['empid'];
                      $sql_insert="INSERT INTO emptable (empid) VALUES ($empid)";
                      $result_insert=DB_query($sql_insert,$db);
                      
                     $employee_arr1[]=$empid;

                      showemp($empid,$db,$y);    
                                            
                      } 
                                      
                  } 
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($_SESSION[empid])";
                      $result_insert2=DB_query($sql_insert1,$db);
     
     $sql2="SELECT empid,reportto FROM bio_emp WHERE reportto=$empid";
     $result2=DB_query($sql2,$db);   
  
     while($row2=DB_fetch_array($result2))
     { 

        $empid=$row2['empid'];
        $employee_arr1[]=$empid;
        $y=$empid;
        showemp($empid,$db,$y);
         
     } 
       
     $sql_select="SELECT empid FROM emptable";
     $result_select=DB_query($sql_select,$db);
     
     while($row_select=DB_fetch_array($result_select))
     {
          $employee_arr1[]=$row_select['empid'];
     }
     
    $employee_arr1=join(",", $employee_arr1);
     
     $sql5="SELECT userid FROM www_users WHERE empid IN ($employee_arr1)";
     $result5=DB_query($sql5,$db);
     while($row5=DB_fetch_array($result5))
     {
    
    $userid[]="'".$row5['userid']."'";     
    
     }
         $filename="LeadDetails.csv";
     $user_array=join(",", $userid);         
               $header= "slno".","."Lead id".","."Name".","."District".","."Contact No".","."Date".","."Enquiry Type".","."Output Type".","."\n";"\n";
    $data='';
    $no=1;

   $sql="SELECT bio_cust.cust_id AS custid,  
                bio_cust.custname AS custname,               
                bio_cust.houseno AS houseno,               
                bio_cust.housename AS housename,
                bio_cust.area1 AS place,
                bio_cust.custphone AS custphone,
                bio_cust.custmob AS custmob,
                bio_enquirytypes.enqtypeid AS enqtypeid,
                bio_enquirytypes.enquirytype AS enqtype,
                bio_leads.outputtypeid AS outputtypeid,
                bio_outputtypes.outputtypeid,
                bio_outputtypes.outputtype AS outputtype,
                bio_cust.district AS districtid,
                bio_leads.leadid AS leadid, 
                bio_leads.leaddate AS leaddate,
                bio_leadteams.teamname AS teamname,
                bio_leadsources.sourcename AS sourcename,
                bio_cust.custmail AS custmail,
                bio_leads.advanceamount AS advance,
                bio_cust.state AS state,
                bio_district.district AS district,
                bio_office.id AS officeid,
                bio_office.office AS office,
                bio_leads.remarks AS remarks
           FROM bio_cust,
                bio_leads,
                bio_leadteams,
                bio_leadsources,
                bio_enquirytypes,
                bio_district,
                bio_office,
                bio_outputtypes   
          WHERE bio_cust.cust_id=bio_leads.cust_id 
            AND bio_leadteams.teamid=bio_leads.teamid 
            AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
            AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
            AND bio_leadsources.id=bio_leads.sourceid  
            AND bio_district.did=bio_cust.district
            AND bio_district.stateid=bio_cust.state
            AND bio_district.cid=bio_cust.nationality
            AND bio_office.id=bio_leadsources.officeid 
            AND bio_leads.created_by IN ($user_array)
            ORDER by leadid desc";                
 
    $result=DB_query($sql,$db);
    $slno=1;
    while($myrow=DB_fetch_array($result))
    {
     
        //$data= $data.$no.",".$myrow['leadid'].",".$myrow['custname'].",".$myrow['area1'].",".convertsqldatefordis($myrow['leaddate']).",".$myrow['enqtype'].",".$myrow['outputtype'].","."\n";    
//                 $no++;

        $data= $data.$slno.",".$myrow['leadid'].",".$myrow['custname'].",".$myrow['area1'].",".$myrow['custmob'].",".ConvertSQLDate($myrow['leaddate']).",".$myrow['enqtype'].",".$myrow['outputtype']."\n";    
    $slno++;
    }
    include('includes/footer.inc'); 
      
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";
}   

?>

