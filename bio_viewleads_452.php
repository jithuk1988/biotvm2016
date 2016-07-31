<?php 

$PageSecurity = 80;
include('includes/session.inc');
$title = _('Lead Details');
include('includes/removespecialcharacters.php');  
if(!isset($_POST['excel']))

  include('includes/header.inc');
           {        include('includes/sidemenu.php');

 if(isset($_GET['team']))
  {

   $team_arr=array();
   $team=$_GET['team'];    
      $sql2="SELECT * FROM bio_teammembers WHERE teamid=".$team;
      $result2=DB_query($sql2,$db);
      $row2=DB_fetch_array($result2) ;
   $empid=$row2['empid'];
   $team_arr[]=$team;
   $team_array=join(",", $team_arr);   
   } 
       
  else {
     $empid=$_SESSION['empid'];   
  

$sql_emp1="SELECT * FROM bio_emp WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
       
     
 $employee_arr=array();   
     $sql_drop="DROP TABLE if exists `emptable`";
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
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($empid)";
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
    while($row6=DB_fetch_array($result6))
    {
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);
    
  }
    
      
       echo '<br />';
  echo"<div id='editleads'>";
  echo "<table style='width:80%'><tr><td>";
  
 /* echo "<fieldset style='width:20%;height:440px'>";     
  echo "<legend><h3>Search By</h3>";     
  echo "</legend>";   */

    echo '<form name="leadsfrom"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    echo '<form name="leadsfrom"  method="post" action="leadDetailsXL.php">';
  
  echo "<td>"; 
  echo "<div id='printandshow' style='margin:auto;'>";
  echo "<fieldset style='width:90%;height:440px;'>";     
  echo "<legend><h3>Lead Details</h3>";     
  echo "</legend>";  
  echo "<table style='border:1px solid #F0F0F0; width:100%'>";
  $lead_status1=array(0,26,10,4,5,6);
   $lead_status_arrays=join(",", $lead_status1);
  
  
  
     if(!isset($_GET['team']))
  { 
     echo '<tr>
             <td class="datefrom">Date From</td>
             <td class="dateto">To Date</td>
             <td class="byname">Customer Name</td>
             <td class="bydistrict">District</td>
             <td class="bycusttype">Customer Type</td>
             <td class="byteam">Office</td>
             <td class="byoffice">Team</td>
             <td class="status">Lead Status</td>
             <td class="status">Lead Source</td>  
           </tr>';
             
//  <td class="advance">Advance</td>       <td class="bycusttype">Customer Name</td>        
             
/*   echo '<tr><td class="actiondate"><select name="Actiondate" id="actiondate" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="1">Today</option>';
   //echo '<option value="2">Tommorrow</option>';
   echo '<option value="3">Yesterday</option>';
   echo '<option value="4">Overdue</option>';
   echo '<option value="5">ALL</option>';
   echo '<option selected='.$_POST['Actiondate'].'>'.$actiondatedesc.'</option>'; 
   echo '</select></td>';
                          
   
   echo '<tr><td><select name="Amount" id="amount" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="1">Paid</option>';
   echo '<option value="2">Tommorrow</option>';
   echo '<option value="2">Not paid</option>';
   
   echo '<option selected='.$_POST['Amount'].'></option>'; 
   echo '</select></td>';
 */  
// if(isset($_POST['filterbut'])) 
// {
//    $_POST['byname'];
// }
 
 
 
echo'<td class="datefrom"><input type="text"  id="datefrm" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datef" value="'.$_POST['datef'].'" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' style="width:80px">';    


echo'<td class="dateto"><input type="text"  id="dateto" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datet" value="'.$_POST['datet'].'" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' style="width:80px">';    
echo"</td>";  
 echo '<td class="byname"><input type="text" name="byname" id="byname" value="'.$_POST['byname'].'" style="width:120px" ></td>';
 echo '<td  class="bydistrict"><input type="text" name="byplace" id="byplace" value="'.$_POST['byplace'].'" style="width:120px"></td>';
    
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
   
      echo '<td id="byoffice"><select name="off" id="off" onchange="officeteam(this.value)" style="width:120px">';
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
                         
   echo '<td class="byteam" id="byteam"><select name="team" id="team" style="width:120px">';                            
   echo '<option value=0></option>'; 
   $sql1="select * from bio_leadteams WHERE teamid IN ($team_array) order by teamname asc";
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
   

   
  
   
   
   
    
   /*echo '<td class="advance"><select name="Amount" id="Amount" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="1">Paid</option>';
   echo '<option value="2">Not paid</option>';
   
   echo '<option selected='.$_POST['Amount'].'></option>'; 
   echo '</select></td>';           */
   
       
   
   echo'<td class="status"><select name="LeadStatus" id="leadstatus" style="width:150px">'; 
          echo'<option value=""></option>';        
          echo'<option value="4">Concept Proposal Submitted</option>';   
          echo'<option value="5">DPR Submitted</option>';
          echo'<option value="0">Enquriy Registered</option>';  
          echo'<option value="26">Feasibility Request Submitted</option>';
          echo'<option value="10">Feasibility Study</option>';           
          echo'<option value="6">Sale Order Registered</option>'; 
  /*  $sql1="SELECT * FROM bio_status WHERE statusid IN ($lead_status_arrays)";
    $result1=DB_query($sql1, $db); 
    $f=0;
    while($myrow1=DB_fetch_array($result1))
  {
    if ($myrow1['statusid']==$_POST['LeadStatus'])
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
  }                     */
echo'</select></td>';

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
   echo '</select></td>';
   echo '</tr>';  */
   
    echo '<td class="leadsource"><select name="leadsrc" id="leadsrc" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="ALL">Select ALL</option>';
   $sql1="select * from bio_leadsources order by sourcename asc";
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
   echo '</select></td></tr>';
  
    
   echo '<tr><td><input type="submit" name="filterbut" id="filterbut" value="search"></td>';
   echo '<td colspan=3><b>';
   
   if((isset($_POST['team'])) && ($_POST['team']!=0)){
       
       $sql="select teamname from bio_leadteams WHERE teamid =". $_POST['team'];  
       $result=DB_query($sql,$db);
       $row=DB_fetch_array($result);
       echo "Selected Team -".$row['teamname'];
   }
   
   echo '</b></td>';
   echo '</tr>';
   
      echo '</table>';    
  }

      echo "<div  overflow:auto;'>";
      echo "<table style='border:1px solid #F0F0F0;width:100%'>"; 
       
      echo "<table  width=100% id='leadreport'>";      //grid head
      echo "<thead>
         <tr BGCOLOR =#800000>
         <th width='4%'>" . _('Sl no') . "</th>  
         <th width='14%'>" . _('Institution Name/<br>Customer Name') . "</th>
         <th width='13%'>" . _('Contact No') . "</th>
         <th width='14%'>" . _('District') . "</th> ";
         
       if(!isset($_GET['team']))
        {    
      echo"   <th width='10%' class='team'>" . _('Team') . "</th> ";  
        }
      echo"   <th width='9%'>" . _('Status') . "</th>"; 
              

if($_SESSION['UserID']=='bdm_national' || $_SESSION['UserID']=='bde_national1' ||$_SESSION['UserID']=='bdm_international' ||$_SESSION['UserID']=='bde_international1') 
{
echo "<th width='13%'>" . _('Email Status') . "</th>";
  }echo "<th width='24%'>" . _('Remarks') . "</th>";
              echo"<th width='10%'>" . _('Next Task') . "</th>  
              <th width='8%'>" . _('View') . "</th>
              </tr></thead>";
         
      echo "</table>";
      echo "<div style='height:300px; overflow:auto;'>";
      echo "<table  style='width:100%;' id='leaddetails'>";       //grid data    
   
   $lead_status=array(15,16,17,18,19,20,21,22,23,24,30,31,45,46);
   $lead_status_array=join(",", $lead_status);  
       
  $enquiry=0;
  $usr=0;
  $status=0;                          
/*  $sql="SELECT bio_cust.cust_id AS custid,  
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
               bio_outputtypes.outputtype AS outputtype,
               bio_cust.district AS districtid,
               bio_leadtask.leadid AS leadid, 
               bio_leads.leaddate AS leaddate,
               bio_leads.remarks AS remarks,
               bio_advance.amount AS amount,
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
               bio_leadtask,
               bio_advance
         WHERE bio_cust.cust_id=bio_leads.cust_id 
           AND bio_leadteams.teamid=bio_leadtask.teamid 
           AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
           AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
           AND bio_leadsources.id=bio_leads.sourceid 
           AND bio_advance.leadid=bio_leads.leadid   
           AND bio_district.did=bio_cust.district
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality
           AND bio_office.id=bio_leadsources.officeid
           AND bio_status.statusid=bio_leads.leadstatus
           AND bio_leadtask.teamid IN ($team_array) 
           AND bio_leadtask.leadid=bio_leads.leadid  
           AND bio_leadtask.taskcompletedstatus=0    */

$sql="
SELECT DISTINCT  bio_leadtask.leadid AS leadid, bio_cust.cust_id AS custid, bio_cust.custname AS custname, 
    bio_status.biostatus, bio_cust.houseno AS houseno, bio_cust.housename AS housename, bio_cust.area1 AS place, 
    bio_cust.custphone AS custphone, bio_cust.custmob AS custmob, bio_cust.contactperson, bio_enquirytypes.enqtypeid AS enqtypeid, 
    bio_enquirytypes.enquirytype AS enqtype, bio_leads.outputtypeid AS outputtypeid, bio_outputtypes.outputtype AS outputtype, 
    bio_cust.district AS districtid, bio_leads.leaddate AS leaddate, bio_leads.remarks AS remarks, bio_advance.amount AS amount, 
    bio_leadteams.teamname AS teamname, bio_leadsources.sourcename AS sourcename, bio_cust.custmail AS custmail, 
    bio_cust.custmob AS custmob, bio_leads.advanceamount AS advance, bio_cust.state AS state, bio_district.district AS district, 
    bio_office.id AS officeid, bio_office.office AS office, bio_leads.remarks AS remarks, bio_leads.created_by, bio_emailstatus.emailstatus
FROM bio_leads
LEFT JOIN bio_leadtask ON bio_leadtask.leadid=bio_leads.leadid 
LEFT JOIN bio_enquirytypes ON bio_leads.enqtypeid=bio_enquirytypes.enqtypeid 
LEFT JOIN bio_leadteams ON bio_leadteams.teamid=bio_leadtask.teamid
LEFT JOIN bio_outputtypes ON bio_leads.outputtypeid=bio_outputtypes.outputtypeid 
LEFT JOIN bio_cust ON bio_cust.cust_id=bio_leads.cust_id
LEFT JOIN bio_district ON bio_district.cid=bio_cust.nationality AND bio_district.stateid=bio_cust.state
AND bio_district.did=bio_cust.district
LEFT JOIN bio_leadsources ON bio_leadsources.id=bio_leads.sourceid
LEFT JOIN bio_office ON bio_office.id=bio_leadsources.officeid 
LEFT JOIN bio_status ON bio_status.statusid=bio_leads.leadstatus 
LEFT JOIN bio_advance ON bio_advance.leadid=bio_leads.leadid
LEFT JOIN bio_incidents on bio_incidents.leadid=bio_leads.leadid
LEFT JOIN  bio_emailstatus on bio_emailstatus.id= bio_incidents.emailstatus

WHERE 
bio_leadtask.teamid IN ($team_array)  
AND bio_leadtask.taskcompletedstatus=0 
AND bio_leadtask.viewstatus=1

";                           //   bio_status WHERE statusid IN($lead_status_arrays
/*  AND bio_leadtask.taskid!=0   
           AND bio_leadtask.taskcompletedstatus=0
           */
           $Currentdate=FormatDateForSQL(Date("d/m/Y"));
                           
if(isset($_POST['filterbut']))
 { 
 
/*    if (isset($_POST['Actiondate']))  {        
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
    }*/ 
    
                  /*if ((isset($_POST['datef'])) && (isset($_POST['datet'])))   {
    if (($_POST['datef']!="") && ($_POST['datet']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['datef']);   
    $sourcetypeto=FormatDateForSQL($_POST['datet']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off']; */
    
  //    if (isset($_POST['byplace'])) {
//    if ($_POST['byplace']!='') 
//    $sql.= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
//    }

        if (isset($_POST['leadsrc'])) {
    if (($_POST['leadsrc']!='ALL') && ($_POST['leadsrc']!=0))
    $sql.=" AND bio_leads.sourceid='".$_POST['leadsrc']."'";
    }
    /* if (isset($_POST['Created'])) {   
    if ($_POST['Created']!=''){
       $sql.=" AND bio_leads.created_by='".$_POST['Created']."'";
       $usr=1; 
    }
    }
     */
     if ((isset($_POST['datef'])) && (isset($_POST['datet'])))   {
    if (($_POST['datef']!="") && ($_POST['datet']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['datef']);   
    $sourcetypeto=FormatDateForSQL($_POST['datet']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    /*$datef=$_POST['datef'];
    $datet=$_POST['datet'];
    echo"<td>Leads From : $datef  To : $datet</td>";*/
    }  } 
    
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql.= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }
     
      
    if (isset($_POST['LeadStatus'])) {   
    if ($_POST['LeadStatus']!=''){
          $leadstatus=$_POST['LeadStatus'];
          if($leadstatus==0)
          {
     $sql.=" AND bio_leads.leadstatus IN (0,2,7,45,46,47,15)"; 
                  
          }
          
          if($leadstatus==26)
          {
     $sql.=" AND bio_leads.leadstatus IN (7,26,47)";  
                  
          } 
          
         
           
          if($leadstatus==10)
          {
     $sql.=" AND bio_leads.leadstatus IN (3)";  
                  
          } 
          
            
          if($leadstatus==4)
          {
     $sql.=" AND bio_leads.leadstatus IN (10,11,30,4,27,2,1)";  
                  
          } 
          
            
          if($leadstatus==5)
          {
     $sql.=" AND bio_leads.leadstatus IN (13,5,28,31)";  
                  
          }  
          
           
          if($leadstatus==6)
          {
     $sql.=" AND bio_leads.leadstatus IN (6,25,29,34,35,36,37,38,39,40,42,43)";  
                  
          }  
          
      
    } 
    
    }        
    
    
  if (isset($_POST['Amount']))  {        
     if ($_POST['Amount']!='') {
      if ($_POST['Amount']==1) {  
     
   $sql.="AND bio_advance.amount>0 AND head_id=8";                
      }  
         elseif ($_POST['Amount']==2) {
               
//  $sql.="AND (bio_advance.amount=0 AND head_id=8) OR (bio_advance.amount>=0 AND head_id!=8)";
             
             
         } 
     }          
    }  
    

    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='') 
     $name2=removeCharacters($_POST['byname']);      
    $sql.= " AND (bio_cust.custname LIKE '%".$_POST['byname']."%'
    OR bio_cust.custname SOUNDS LIKE '%".$_POST['byname']."%'
    OR bio_cust.custname LIKE '%".$name2."%')";   
    }                                                                    
            
/*     if (isset($_POST['off']))    {
    if (($_POST['off']!='')&&($_POST['off']!='0')){
        $sql.=" AND bio_leadsources.officeid=".$_POST['off'];
    }
    else */
         if (isset($_POST['off']))    { 
     if (($_POST['off']!='')&&($_POST['off']!='0') && (($_POST['team']=='')||($_POST['team']=='0')))
    {
        
               $sql.=" AND bio_leadtask.teamid in (Select teamid from bio_leadteams where office_id=".$_POST['off'].") ";    

        
    }
    else      if (($_POST['off']!='')&&($_POST['off']!='0') && ($_POST['team']!='')||($_POST['team']!='0'))
                     {
                                     $sql.=" AND bio_leadtask.teamid in ($team_array)"; 
                         
                     }
    
    
    }
    if (isset($_POST['team']))    {
    if (($_POST['team']!='')&&($_POST['team']!='0'))
    $sql.=" AND bio_leadtask.teamid='".$_POST['team']."'";    
    }
    
    
    
    if (isset($_POST['enquiry'])) {
    if (($_POST['enquiry']!='ALL') && ($_POST['enquiry']!=0))
    $sql.=" AND bio_enquirytypes.enqtypeid='".$_POST['enquiry']."'";
    $enquiry=1;
    } 
    
 
    
    
    
}else{
    
        if(!isset($_GET['team']))
  { 
    /*$sql.=" AND bio_leads.created_by IN ($user_array)";
    $usr=1;*/
    
    if($myrow_emp1['designationid']<=8){
    if($enquiry==0){
       $sql.="AND bio_leads.enqtypeid=2 AND bio_leads.leadstatus!=20 ";
       $enquiry=1; 
    }
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
     $sql.=" And bio_leads.leadstatus not in(20,21,22,23,24) GROUP BY bio_leadtask.leadid ORDER by bio_leads.leadid desc,bio_leadtask.assigneddate ASC";
    
  
 //echo$sql;       
         
         
         
         

function convertsqldatefordis($d) 
{
    $array=explode('-',$d);
    $dd="$array[2]/$array[1]/$array[0]";
    return $dd;        
}  


   $result=DB_query($sql,$db);  
   $count=DB_num_rows($result);
      
   echo '<tbody>';
   echo '<tr>';                                       
     
        $slno=$count+1; 
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
      $slno--;         
      $teamname=$myrow['teamname']; 
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
          }      }
          
          
    if($teamname=="" OR $teamname=='0'){
          $teamname='Not Assigned';
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
      
      if($advance_amount=="" OR $advance_amount=='0'){
         $advance_amount='Not paid';
      } 
      
      $sql_action="SELECT * FROM        bio_leadtask,bio_task
                            WHERE       bio_leadtask.leadid=".$leadid."
                            AND         bio_leadtask.taskcompletedstatus=0
                            AND         bio_task.taskid=bio_leadtask.taskid
                            ORDER BY    bio_leadtask.assigneddate ASC";
                            
      $result_action=DB_query($sql_action,$db);
      $myrow_action=DB_fetch_array($result_action);
      $next_action=$myrow_action['task'];
      $next_action_id=$myrow_action['taskid']; 
      
      $action_date=$myrow_action['assigneddate'];
      if($action_date!="" AND $next_action!==""){
         $action_date=ConvertSQLDate($action_date);
      }else{
         $next_action='Not assigned';
         $action_date='Not assigned'; 
      }

      $remarks=$myrow['remarks']; 
      
      echo "<input type='hidden' id='leadid' name='leadid' value='$leadid'>";    
echo "<td width=4%'>$slno</td>";

        echo "<td width='15%'>$custname</td>";           
        echo"<td width='14%'>$custmob</td>";
        echo"<td width='15%'>".$myrow['district']."</td>";
    if(!isset($_GET['team']))
  {      
        echo"<td width='10%' class='team'>$teamname</td>";  }
        echo"<td width='10%'>".$myrow['biostatus']."</td>";
if($_SESSION['UserID']=='bdm_national' || $_SESSION['UserID']=='bde_national1' ||$_SESSION['UserID']=='bdm_international' ||$_SESSION['UserID']=='bde_international1') 
{
  echo"<td width='10%'>".$myrow['emailstatus']."</td>";

}
        echo"<td width='25%'>$remarks</td>";    
         
         if($next_action_id==0){
            echo"<td>Not Assigned</td>"; 
             
         } else{
             
           echo"<td ><a  style='cursor:pointer;'  onclick=showNextAction('$leadid','$enq')>$next_action</a></td>";  
             
         }   
         
          
       
        echo"<td ><a  style='cursor:pointer;'  onclick=showCD2('$leadid','$enq')>" . _('View') . "</a></td>"; 
        echo"<td><a  style='cursor:pointer;'  onclick=viewdetails('$leadid','$enq')>" . _('Edit') . "</a></td>" ;   
        echo"</tr>";
      }                
                     
              
   echo '</tbody>'; 
   echo '</table>';
   echo '</form>';
   echo "<form action='bio_viewleads_excel.php' method=POST>";
   $_SESSION[$viewleads_sql]=$sql;   
   echo '<input type="submit" name="excel" value="View as Excel">';
   
          
   echo '</div>'; 
   
   
      echo '</table>';  
      echo '</div>'; 
      
      echo "</fieldset>";
      echo '</div>';
      
      
      echo "</td></tr></table>"; 
      echo "</div>";
                                      
   
?> 

<script>

    var team=document.getElementById('team').value;     
    if(team!=0)
    {
        $(".team").hide();
    }  
           


function showNextAction(str1,str2)
{
   if(str2==1){
        myRef = window.open("bio_domTaskview.php?lead=" + str1);
       
   }else
   if(str2==2){
       myRef = window.open("bio_instTaskview.php?lead=" + str1); 
   } 
             
}


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
    
    
function officeteam(str)
{
           //  alert(str);
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
    document.getElementById("byteam").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_taskExport_officeteam.php?officeid=" + str,true);
xmlhttp.send();

}


</script>

<?php

} 

?>

