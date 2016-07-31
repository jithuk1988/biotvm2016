<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Proposal Approve');
$pagetype=1;
include('includes/header.inc'); 
include('includes/sidemenu.php');   



echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">PROPOSAL APPROVAL</font></center>'; 
echo"<table border=0  style='width:70%'>";
if(isset($_POST['updateprc']))
{$st="proposal approved";
    $sql="SELECT bio_status.statusid FROM bio_status WHERE bio_status.biostatus='$st'";
    $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result);
 $status=$myrow[0];     
$nme=$_POST['prc'];   
 for($i=0;$i<$nme;$i++){
$price = $_POST['price'.$i];  $lead=$_POST['hleads']; 
$productid = $_POST['prid'.$i];
$sql="UPDATE bio_proposals SET price = '$price' WHERE bio_proposals.productid='$productid'
AND bio_proposals.leadid=$lead";                              
$result=DB_query($sql,$db);     
        
 }  
    
$sql="UPDATE bio_leads SET leadstatus = $status WHERE bio_leads.leadid =$lead";                              
$result=DB_query($sql,$db);       
    
  
    
}
echo"<tr>";
if($_GET['leadid']!=''){     $flag=1;
$leadid=$_GET['leadid'];
$sql="SELECT 
bio_leads.leadid,
date_format(bio_leads.leaddate,'%d/%m/%Y') AS date,
bio_cust.area1,
bio_cust.custmob,
bio_cust.cust_id, 
bio_cust.custname,
bio_outputtypes.outputtype,
bio_enquirytypes.enquirytype,
bio_leadteams.teamname      
FROM bio_leads,
bio_cust,
bio_outputtypes,
bio_enquirytypes,
bio_leadteams 
WHERE 
bio_leads.cust_id=bio_cust.cust_id
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.teamid=bio_leadteams.teamid
AND bio_leads.leadid=$leadid";
      $result=DB_query($sql,$db);
     // $count=DB_fetch_row($result); 
     //print_r($count);
                                     
    $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $custid=$myrow['cust_id'];    
          $name=$myrow['custname'];
          $mob=$myrow[custmob];
          $place=$myrow[area1];
          $date=$myrow[date];     
                 } 
                 

}
echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>"; 
echo"<input type='hidden' name='hleads' id='hleads' value='$leadid'>";
echo"<td style='width:50%'>"; 
echo"<fieldset  style='height:162px'><legend>Customer Details</legend>"; 
echo"<table><tr><td>";
echo"Customer Id";
echo"</td>";
echo"<td>";
echo"<input type='text' id='custid' name='custid' value='$custid'>";
echo"</td></tr>";
                 
echo"<tr><td>";
echo"Customer Name";
echo"</td>";
echo"<td>";
echo"<input type='text' id='cust' name='cust' value='$name'>";
echo"</td></tr>";

echo"<tr><td>";
echo"Customer Mobile";
echo"</td>";
echo"<td>";
echo"<input type='text' id='mob' name='mob' value='$mob'>";
echo"</td></tr>";

echo"<tr><td>";
echo"Recidential Area";
echo"</td>";
echo"<td>";
echo"<input type='text' id='res' name='res' value='$place'>";
echo"</td></tr>";

echo"<tr><td>";
echo"Date";
echo"</td>";
echo"<td>";
echo"<input type='text' id='date' name='date' value='$date'>";
echo"</td></tr></table></fieldset></td>";

echo"<td valign=top>";
echo"<fieldset style='height:162px'><legend>Proposed Products</legend>";
echo "<table style='width:100%'>";
echo"<tr><th>Pid</th><th>Name</th><th>Weight</th><th>Price</th></tr>";
/*
if($leadid!=""){
 $sql="SELECT 
bio_proposals.productid,
bio_proposals.price,
stockmaster.description,
stockmaster.kgs
FROM 
bio_proposals,stockmaster 
WHERE 
bio_proposals.productid=stockmaster.stockid
AND bio_proposals.leadid=$leadid";
$result=DB_query($sql,$db);
 $i=0;
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $productid=$myrow[0];    
          $price=$myrow[1];
          $description=$myrow[2];
           $weight=$myrow[3];
//            $date=$myrow[1];     
                 
          echo'<tr><td>'.$productid.'</td><td>'.$description.'</td><td>'.$weight.'
          </td><td><input id="price'.$i.'" style="width:50px" name="price'.$i.'" value="'.$price.'"></td></tr>';
          echo '<input  type="hidden" id="prid'.$i.'" name="prid'.$i.'" value="'.$productid.'">';
          
          $i++;
          }
}
*/
echo"<input type='hidden' name='prc' value='$i'>";
if($i!=0){
echo"<tr><td colspan=2><input type='submit' name='updateprc' value='Approve'></td></tr>";}
echo"</table></fieldset>";

echo"</td>";

echo"</tr>";
echo"<tr>";
echo"<td colspan=2>";
echo"<fieldset style='width:780px'><legend>Proposals for Approval</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>";
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname' id='byname'></td>";
echo"<td><input type='text' name='byplace' id='byplace'></td>";
echo '<td><select name="off" id="off" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_office";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo '</select></td>';
echo '<td><select name="leadsrc" id="leadsrc" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';
$sql1="select * from bio_leadsources";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
}                                                             
echo '</select></td>';
echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
echo"</tr>";
echo"</table>"; 

echo "<div style='height:200px; overflow:scroll;'>";
echo"<table style='width:100%'> ";  
echo"<tr><th>Slno</th><th>Name</th><th>Date</th><th>Output</th><th>Cust type</th><th>Team</th></tr>";
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
//    $s_userid=$_SESSION['UserID'];
//    $s_offid=$_SESSION['officeid'];
//    
//            $office_arr=array();
//        $office_arr[]=$s_offid;
//           
//     $sql2="SELECT reporting_off,id
//            FROM bio_office
//            WHERE reporting_off=$s_offid
//            ";
//              
//     $result2=DB_query($sql2,$db);
//     $myrow_count = DB_num_rows($result2);
//     
//     if($myrow_count>0){
//     while($row2=DB_fetch_array($result2)){
//         $office_arr[]=$row2['id'];   
//        
//     $sql3="SELECT id,reporting_off 
//                FROM bio_office
//                WHERE reporting_off=".$row2['id']."";
//        $result3=DB_query($sql3,$db);
//        $myrow_count1 = DB_num_rows($result3);
//     if($myrow_count1>0){
//     while($row3=DB_fetch_array($result3)){
//               $office_arr[]=$row3['id'];       
//       
//     $sql4="SELECT id,reporting_off 
//                FROM bio_office
//                WHERE reporting_off=".$row3['id']."";
//        $result4=DB_query($sql4,$db);
//        $myrow_count2 = DB_num_rows($result4);
//     if($myrow_count2>0){
//     while($row4=DB_fetch_array($result4)){
//               $office_arr[]=$row4['id'];        
//         echo $row3['id'];
//            
//        }
//        }  
//        }   
//     }
//     }
//     }
//     
//     $office_array=join(",", $office_arr);
//     $sql5="SELECT *  
//                FROM bio_emp
//                WHERE offid IN ($office_array)";
//     $result5=DB_query($sql5,$db);
//     while($row5=DB_fetch_array($result5)){
//         $row5['empid'];
//    
//    $sql6="SELECT userid FROM www_users WHERE empid=".$row5['empid'];
//    $result6=DB_query($sql6,$db);
//    $row6=DB_fetch_array($result6);
//    $userid[]="'".$row6[0]."'";     
//    $user_array=join(",", $userid); 
//               
//     }                      
//     
//if($_SESSION[level]<=15)
//{ 
//  $sql="SELECT bio_cust.cust_id AS custid,  
//  bio_cust.custname AS custname,               
//  bio_cust.houseno AS houseno,               
//  bio_cust.housename AS housename,
//  bio_cust.area1 AS place,
//  bio_cust.custphone AS custphone,
//  bio_cust.custmob AS custmob,
//  bio_enquirytypes.enqtypeid AS enqtypeid,
//  bio_enquirytypes.enquirytype AS enqtype,
//  bio_leads.outputtypeid AS outputtypeid,
//  bio_outputtypes.outputtypeid,
//  bio_outputtypes.outputtype AS outputtype,
//  bio_cust.district AS districtid,
//  bio_leads.leadid AS leadid, 
//  bio_leads.leaddate AS leaddate,
//  bio_leadteams.teamname AS teamname,
//  bio_leadsources.sourcename AS sourcename,
//  bio_cust.custmail AS custmail,
//  bio_leads.advanceamount AS advance,
//  bio_cust.state AS state,
//  bio_district.district AS district,
//  bio_cust.area1 AS area1,
//  bio_cust.area2 AS area2,
//  bio_office.id AS officeid,
//  bio_office.office AS office,
//  bio_leads.remarks AS remarks
//FROM bio_cust,
//bio_leads,
//bio_leadteams,
//bio_leadsources,
//bio_enquirytypes,
//bio_district,
//bio_office,
//bio_outputtypes   
//WHERE bio_cust.cust_id=bio_leads.cust_id 
//AND bio_leadteams.teamid=bio_leads.teamid 
//AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
//AND bio_leads.enqtypeid=1 
//AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
//AND bio_leadsources.id=bio_leads.sourceid  
//AND bio_district.did=bio_cust.district
//AND bio_district.stateid=bio_cust.state
//AND bio_district.cid=bio_cust.nationality
//AND bio_office.id=bio_leadsources.officeid 
//AND bio_leads.leadstatus=1
//";  

//$sql_off="SELECT empid FROM bio_emp WHERE offid=".$_SESSION[officeid];
//$result_off=DB_query($sql_off,$db);
//$emp_arr=array();
//while($row_off=DB_fetch_array($result_off)) 
//{  
//  $emp_arr[]=$row_off['empid'];
//  $employee_array=join(",", $emp_arr); 
//} 

//$sql_users="SELECT userid FROM www_users WHERE empid IN ($employee_array)";
//$result_users=DB_query($sql_users,$db);
//$userid_arr=array();
//while($row_users=DB_fetch_array($result_users)) 
//{
//    $userid_arr[]="'".$row_users['userid']."'"; 
//    $users_array=join(",", $userid_arr);     
//}

 $empid=$_SESSION['empid'];
 $employee_arr=array();   
     $sql_drop="DROP TABLE `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db)         
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

                      showemp($empid,$db);    
                                            
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
        showemp($empid,$db); 
         
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
                           
 $sql="SELECT bio_cust.cust_id AS custid,  
  bio_cust.custname AS custname,               
  bio_cust.area1 AS place,
  bio_enquirytypes.enquirytype AS enqtype,
  bio_outputtypes.outputtype AS outputtype,
  bio_leads.leadid AS leadid, 
  bio_proposal.propid as proposalid,
  bio_leadteams.teamname AS teamname,
  bio_leadsources.sourcename AS sourcename,
  bio_proposal.propdate AS proposaldate,
  bio_office.id AS officeid,
  bio_office.office AS office
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,
bio_enquirytypes,
bio_office,
bio_proposal,
bio_outputtypes 
WHERE bio_cust.cust_id=bio_leads.cust_id 
AND bio_leadteams.teamid=bio_leads.teamid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.enqtypeid=1 
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid 
AND  bio_proposal.leadid=bio_leads.leadid
AND bio_leadsources.id=bio_leads.sourceid  
AND bio_office.id=bio_leadsources.officeid 
AND bio_leads.leadstatus=1
AND bio_leads.created_by IN ($user_array)
";                            
    //echo $users_array;
if(isset($_POST['filterbut']))
 {
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql .=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off'];
    echo $officeid;
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql .= " AND bio_cust.area1 LIKE '%".$_POST['byplace']."%'"; 
    }
    
    if (isset($_POST['off']))    {
    if (($_POST['off']!='')&&($_POST['off']!='0'))
    $sql .=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc'])) {
    if (($_POST['leadsrc']!='ALL') && ($_POST['leadsrc']!=0))
    $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc']."'";
    }
 }
 // $sql .=" ORDER BY bio_leads.leadid DESC";
      $result=DB_query($sql,$db);

    echo '<tbody>';
    echo '<tr>';                                       
    $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];   
         
         //  $leadid=$myrow['leadid'];
          
          $propid=$myrow['proposalid'];
          
          $no++;
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                    
                }
                 else 
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
        <td><a  style='cursor:pointer;'  id='".$myrow['leadid']."' onclick='passid(this.id,$propid)'>" . _('Print') . "</a></td>  
        </tr>",
        
        $no,
        $myrow['custname'],
        ConvertSQLDate($myrow['proposaldate']),
        $myrow['outputtype'],
        $myrow['enqtype'],
        $myrow['teamname']);

          }
          
echo"</table>";
echo"</div>";
echo"</fieldset>";
echo"</td></tr>";
echo"</form>"; 
echo"</table>";



if(isset($_GET['propid'])){
 $id=$_GET['propid'];
 $lead=$_GET['leadid'];
 echo"<input type='hidden' id='propid' name='propid' value='$id'>";
 
  echo"<input type='hidden' id='leadid' name='leadid' value='$lead'>";
  
      
 $sql5="SELECT letterno,letterdate FROM bio_proposal where propid=".$id; 
 $result=DB_query($sql5,$db);
 $myrow=DB_fetch_array($result);
  if(($myrow['letterno']==0 OR $myrow['letterno']=="") AND ($myrow['letterdate']==0 OR $myrow['letterdate']=="")){
 ?>
    
    <script>
    
         var str1=document.getElementById('propid').value;
         var str2=document.getElementById('leadid').value;
         controlWindow=window.open("bio_prop_letterdetails.php?propid=" + str1 + "&leadid=" + str2,"propletterdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400"); 
    

//          window.location="bio_prop_letterdetails.php?propid=" + str1 + "&leadid=" + str2;
          
    </script> 
    <?php
  
  } else{
 ?>     
    <script>
    
      var str1=document.getElementById('leadid').value; 
      controlWindow=window.open("bio_proposal_coveringletter.php?leadid=" + str1);
//         window.location="bio_proposal_coveringletter.php?leadid=" + str1; 
          </script>
          <?php    
  }
}

?>
<script>
function passid(str1,str2){
//    alert(str2);
location.href="?leadid=" + str1 + "&propid=" +str2; 



//window.location="bio_quotation.php?leadid=" + str1 + '&propid' +str2;  
} 
</script>




  
      