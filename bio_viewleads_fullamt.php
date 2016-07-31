<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Full Amount Paid Customers');
 
if(!isset($_POST['excel']))
{
  include('includes/header.inc');     
  
  $empid=$_SESSION['empid'];
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
     
     $sql5="SELECT userid FROM www_users WHERE empid IN ($employee_arr)";
     $result5=DB_query($sql5,$db);
     while($row5=DB_fetch_array($result5))
     {
    
    $userid[]="'".$row5['userid']."'";     
    
     }
     $user_array=join(",", $userid);         
  
  
  echo"<div id='editleads'>";
  echo "<table style='width:80%'><tr><td>";
  echo "<fieldset style='width:20%;'>";     
  echo "<legend><h3>Search By</h3>";     
  echo "</legend>"; 

    echo '<form name="leadsfrom"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    //echo '<form name="leadsfrom"  method="post" action="leadDetailsXL.php">';
  
  echo "<table style='border:1px solid #F0F0F0;width:100%'>";
 
   echo '<tr><td>From Date</td></tr>';             
   echo '<tr><td><input type="text" style="width:120px" id="datef" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datef" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';  
   echo '<tr><td>To Date</td></tr>';  
   echo '<tr><td><input type="text" style="width:120px" id="datet" class=date alt='.$_SESSION['DefaultDateFormat'].' name="datet" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';   
   echo '<tr><td>Name</td></tr>';
   echo '<tr><td><input type="text" name="byname" id="byname" style="width:120px"></td></tr>';
   echo '<tr><td>Place</td></tr>';
   echo '<tr><td><input type="text" name="byplace" id="byplace" style="width:120px"></td></tr>';
   echo '<tr><td>Enquiry Type</td></tr>';
   echo '<tr><td><select name="enquiry" id="enquiry" style="width:120px">';
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
                                                            
   echo '</select></td></tr>';      
   echo '<tr><td>Team</td></tr>';
   echo '<tr><td><select name="team" id="team" style="width:120px">';
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
   echo '</select></td></tr>';
   echo '<tr><td>Office</td></tr>';
   echo '<tr><td><select name="off" id="off" style="width:120px">';
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
   echo '</select></td></tr>';  
   echo '<tr><td>Lead Sourse</td></tr>';
   echo '<tr><td><select name="leadsrc" id="leadsrc" style="width:120px">';
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
   echo '</select></td></tr>';
   
   echo '<tr><td>Created by</td></tr>';
   echo '<tr><td><select name="Created" id="created" style="width:120px">';
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
   echo '</select></td></tr>';
   
         
   echo '<td><input type="submit" name="filterbut" id="filterbut" value="search"></td></tr>';
   echo '</table>';
   echo '</fiieldset>';
   echo '</td>';          
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------   
 
  $usr=0;    
  
  $sql=="SELECT
   bio_proposal.leadid,
   bio_proposal.totprice, 
   sum(bio_advance.amount) 
   from bio_proposal,bio_advance where bio_proposal.propid=15 AND bio_proposal.leadid=bio_advance.leadid 
AND bio_proposal.totprice<=(select sum(bio_advance.amount))";   
                   
$sql="SELECT DISTINCT bio_cust.cust_id AS custid,  
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
bio_proposal,
bio_advance 
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
AND bio_proposal.leadid=bio_leads.leadid
AND bio_advance.leadid=bio_proposal.leadid
AND bio_proposal.totprice <= (select SUM(bio_advance.amount) )   
AND bio_proposal.status=0
"; 
         
if(isset($_POST['filterbut']))
 {    
     
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
    $sql.= " AND bio_cust.area1 LIKE '%".$_POST['byplace']."%'"; 
    }
    
    if (isset($_POST['team']))    {
    if (($_POST['team']!='')&&($_POST['team']!='0'))
    $sql.=" AND bio_leadteams.teamid='".$_POST['team']."'";    
    }
    
    if (isset($_POST['off']))    {
    if (($_POST['off']!='')&&($_POST['off']!='0'))
    $sql.=" AND bio_leadsources.officeid=".$_POST['off'];
    }
    
    if (isset($_POST['enquiry'])) {
    if (($_POST['enquiry']!='ALL') && ($_POST['enquiry']!=0))
    $sql.=" AND bio_enquirytypes.enqtypeid='".$_POST['enquiry']."'";
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
    
    
}else{
    $sql.=" AND bio_leads.created_by IN ($user_array)";
    $usr=1;
}

if($usr==0){
    $sql.=" AND bio_leads.created_by IN ($user_array)";
    $usr=1;
    
}

        $sql.=" ORDER by leadid desc";

   
// echo $sql;
      echo "<td>"; 
      echo "<div id='printandshow' style='margin:auto;'>";
      echo "<fieldset style='width:98%;height:440px;'>";     
  echo "<legend><h3>Full Amount Paid Customers</h3>";     
  echo "</legend>";
      echo "<div style='height:350px; overflow:auto;'>"; 
      echo "<table  style='width:100%;' id='leadreport'>"; 
      echo "<thead>
        <tr BGCOLOR =#800000><th>" . _('Sl no:') . "</th> 
         <th>" . _('Lead id') . "</th>
         <th>" . _('Customer Name/<br>Institution Name') . "</th>
         <th>" . _('Contact Person') . "</th>
         <th>" . _('Place') . "</th>
         <th>" . _('Date') . "</th>
         <th>" . _('Enquiry Type') . "</th>
         <th>" . _('Created by') . "</th>
         <th>" . _('Status') . "</th>
         
        </tr></thead>";

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
      $leadid=$myrow['leadid']; $enq=$myrow['enqtypeid']; 
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
      if($myrow['enqtypeid']==2){
            $custname=$myrow['housename'];
            $contact_person=$myrow['custname'];
      }
      
      echo "<input type='hidden' id='leadid' name='leadid' value='$leadid'>";    
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td> 
        <td><a  style='cursor:pointer;'  onclick=showCD2('$leadid','$enq')>" . _('View') . "</a></td>  
        </tr>",
        $no,
        $myrow['leadid'],
        $custname,
        $contact_person,
        $myrow['place'],
        convertsqldatefordis($myrow['leaddate']),
        $myrow['enqtype'],
        $empname,
        $myrow['biostatus'],
        $_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]);
        }                
                     
              
   echo '</tbody>';
   
      echo '</table>';  
      echo '</div>'; 
      echo '<input type="submit" name="excel" value="View as Excel">'; 
      echo "</fieldset>";
      echo '</div>';
      echo '</form>';
      
      echo "</td></tr></table>"; 
      echo "</div>";
   include('includes/footer.inc'); 
?> 

<script>
function showCD2(str1,str2){
       
//         window.location="bio_viewleaddetails.php?q=" + str1 + "&en=" + str2;
         myRef = window.open("bio_viewleaddetails.php?q=" + str1 + "&en=" + str2);
    }

    
/*
function showCD2(str1,str2)
{
//   alert("hii");
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
xmlhttp.open("GET","bio_viewleaddetails.php?q=" + str1 + "&en=" + str2,true);
xmlhttp.send();    

}

*/
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
               $header= "slno".","."Lead id".","."Name".","."Place".","."Contact No".","."Date".","."Enquiry Type".","."Output Type".","."\n";"\n";
    $data='';
    $no=1;

  $sql="SELECT DISTINCT bio_cust.cust_id AS custid,  
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
bio_proposal,
bio_advance 
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
AND bio_proposal.leadid=bio_leads.leadid
AND bio_advance.leadid=bio_proposal.leadid
AND bio_proposal.totprice <= (select SUM(bio_advance.amount) )   
AND bio_proposal.status=0
ORDER by leadid desc";                
 
    $result=DB_query($sql,$db);
    $slno=1;
    while($myrow=DB_fetch_array($result))
    {
     
        //$data= $data.$no.",".$myrow['leadid'].",".$myrow['custname'].",".$myrow['area1'].",".convertsqldatefordis($myrow['leaddate']).",".$myrow['enqtype'].",".$myrow['outputtype'].","."\n";    
//                 $no++;

        $data= $data.$slno.",".$myrow['leadid'].",".$myrow[''].",".$myrow['area1'].",".$myrow['custmob'].",".ConvertSQLDate($myrow['leaddate']).",".$myrow['enqtype'].",".$myrow['outputtype']."\n";    
    $slno++;
    }
       
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";
}   

?>

