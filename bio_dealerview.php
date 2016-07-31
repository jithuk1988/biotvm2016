<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Dealer View');
include('includes/header.inc');
/* $user_sql="SELECT empid  FROM www_users 
                WHERE userid ='".$_SESSION['UserID']."'";
                $user_db=DB_query($user_sql,$db);
                $row_user=DB_fetch_array($user_db);
$team_sql="SELECT teamid  FROM bio_teammembers 
                WHERE empid =".$row_user['empid'];
                $team_db=DB_query($team_sql,$db);
                $row_team=DB_fetch_array($team_db);
                echo $row_team['teamid'];*/
                
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
 /*           
   $sql="SELECT  bio_district.district,bio_businessassociates_enq.description, bio_businessassociates_enq.remark, bio_businessassociates_enq.buss_id, bio_businessassociates_enq.source, bio_businessassociates_enq.createddate, bio_businessassociates_enq.status, bio_businessassodetails_enq.custname, bio_enquirytypes.enquirytype
FROM bio_businessassociates_enq, bio_businessassodetails_enq, bio_enquirytypes,bio_district
LEFT JOIN bio_businessassodetails_enq ON bio_businessassociates_enq.enqtypeid = bio_enquirytypes.enqtypeid
LEFT JOIN bio_businessassociates_enq.cust_id = bio_businessassodetails_enq.cust_id
WHERE bio_businessassociates_enq.enqtypeid = bio_enquirytypes.enqtypeid
AND bio_businessassociates_enq.cust_id = bio_businessassodetails_enq.cust_id
       AND   bio_district.did=bio_businessassodetails_enq.district 
       AND   bio_district.stateid=bio_businessassodetails_enq.state   
       AND   bio_district.cid=bio_businessassodetails_enq.nationality 
AND bio_businessassociates_enq.buss_id IN (
                                            SELECT buss_id
                                            FROM bio_dealersteam
                                            WHERE teamid IN ($team_array))

" ;                                                                              */


   $sql="SELECT `bio_businessassociates_enq`.`buss_id`,
                `bio_businessassodetails_enq`.`custname`, 
                `bio_businessassociates_enq`.`source`, 
                `bio_businessassociates_enq`.`createddate`, 
                `bio_district`.`district`, 
                `bio_businessassodetails_enq`.`custphone`, 
                `bio_businessassodetails_enq`.`custmob`, 
                `bio_enquirytypes`.`enquirytype`, 
                `bio_businessassociates_enq`.`status`, 
                `bio_businessassociates_enq`.`remark`, 
                `bio_businessassociates_enq`.`description`
           FROM `bio_businessassociates_enq`
     INNER JOIN `bio_businessassodetails_enq` 
            ON (`bio_businessassociates_enq`.`cust_id` = `bio_businessassodetails_enq`.`cust_id`)
     INNER JOIN `bio_enquirytypes` 
            ON (`bio_businessassociates_enq`.`enqtypeid` = `bio_enquirytypes`.`enqtypeid`)
      LEFT JOIN `bio_district` 
            ON (`bio_businessassodetails_enq`.`district` = `bio_district`.`did`) 
           AND (`bio_businessassodetails_enq`.`state` = `bio_district`.`stateid`) 
           AND (`bio_businessassodetails_enq`.`nationality` = `bio_district`.`cid`)
           WHERE bio_businessassociates_enq.buss_id IN (
          SELECT buss_id FROM bio_dealersteam WHERE teamid IN ($team_array)) 
          union 
          SELECT
  bio_leads.leadid AS 'buss_id',
  bio_cust.custname AS 'custname',
  bio_leadsources.sourcename AS 'source',
  bio_leads.leaddate AS 'createddate',
  bio_district.`district` AS 'district',
  bio_cust.custphone AS 'custphone',
  bio_cust.custmob AS 'custmob',
  `bio_enquirytypes`.`enquirytype` AS 'enquirytype',
  bio_status.biostatus AS 'status',
  bio_leads.remarks AS 'remark',
  bio_leads.remarks AS 'description'
FROM bio_leads,
  bio_enquirytypes,
  bio_cust,
  bio_leadsources,
  bio_district,
  bio_status
WHERE bio_cust.cust_id = bio_leads.cust_id
    AND bio_leads.enqtypeid = bio_enquirytypes.enqtypeid
    AND bio_leads.sourceid = bio_leadsources.id
    AND bio_leads.status = bio_status.statusid
    AND bio_cust.nationality = bio_district.cid
    AND bio_cust.state = bio_district.stateid
    AND bio_cust.district = bio_district.did
    AND bio_leads.enqtypeid in (8,7)";


                                                     
if(isset($_POST['dealersearch']))
{ 
   $name=$_POST['name']; 
   $source=$_POST['source'];
   $createdtfrm=FormatDateForSQL($_POST['delverydatefrm']); 
    $createdtto=FormatDateForSQL($_POST['delverydateto']);                                                    
    $enqtype1=$_POST['enqtype'];                                                  
if($name!=''){ 
   
  $sql.=" AND bio_businessassodetails_enq.custname LIKE '%$name%'"; 
}
if($source!=NULL &&  $source!=0){ 
   
   $sql.=" AND bio_businessassociates_enq .source='".$source."'"; 
}
if($createdtfrm!=0 && $createdtfrm!=NULL && $createdtto!=0 && $createdtto!=NULL){ 
   
 $sql.=" AND bio_businessassociates_enq .createddate BETWEEN '".$createdtfrm."' AND '".$createdtto."'" ; 
}
if($enqtype1!=NULL && $enqtype1!=0){ 
   
   $sql.=" AND bio_businessassociates_enq .enqtypeid='".$enqtype1."'"; 
}
if ($_POST['byplace']!='') {
    $sql.= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }

}
//echo $sql;
$result=DB_query($sql,$db);
//$row1=DB_fetch_array($result);

if($source!=NULL &&  $source!=0){
   $phone_email=$source; 
}else{
$phone_email=$row1['source'];
}

echo"<div id='dealer' style='background-color:#F0F0F0;'>"; 
echo '<form name="dealerview"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';

echo"<div id=links>";
echo"<fieldset style='width:700px;height:auto'>"; 
echo"<legend>Dealer Details</legend>";
echo"</legend>";  

echo"<table width=70%>"; 
echo"<tr><td width=50%>Dealer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Dealer Contact:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Dealer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Dealer Place:</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Dealer District:</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district'>$district</td></tr>";
echo"<tr></tr>";
echo"</table>";
        
echo"</fieldset>";
echo"</div>";



 echo"<div id='dealer' style='background-color:#F0F0F0;'>"; 
echo '<form name="dealerview"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
echo "<fieldset style='width:90%;height:330px;background-color:#F0F0F0;'>";     
  echo "<legend><h3>Dealer View</h3>";     
  echo "</legend>";  
  echo "<table style='border:1px solid #F0F0F0; width:80%'>";
  
    if($_POST['byplace']!=NULL){
       $title1.=' : District '.$_POST['byplace'];   
       }        
    if($_POST['source']==1){
       $title1.=' : Source : Direct';   
    }elseif($_POST['source']==2){
       $title1.=' : Source : Email';     
    }elseif($_POST['source']==3){
       $title1.=' : Source : Phone';
    }elseif($_POST['source']==4){
       $title1.=' : Source : Others';                  
    }  
     
         
    if($_POST['delverydatefrm']!=NULL){
       $title1.=' : From '.$_POST['delverydatefrm'];
       }
       
    if($_POST['delverydateto']!=NULL){
       $title1.=' : To '.$_POST['delverydateto'];   
       }

       
    if($_POST['enqtype']==7){
       $title1.=' : Type : Joint Venture';   
    }elseif($_POST['enqtype']==8){
       $title1.=' : Type : Dealership';    
    }    
             
            
echo "<tr><td colspan='8'><font size='-1'>"."<b>Search Details".$title1."</b></font></td></tr>";     
    echo"</td></tr>";   
  
  
       echo '<tr>
             <td class="lname">Name</td>
             <td>Source</td>
             <td class="ldistrict">District</td>
             <td class="ldate">Date From</td>
             <td class="ldate">Date To</td>
             <td>Type</td>
             <td class="lstatus">Status</td> 
           </tr>';
      echo '<tr> 
           <td><input type="text"  id="name" name="name" style="width:120px"></td>
           <td><select  id="source" name="source" style="width:120px">';
           
      if(!isset($_POST['source'])){
       echo'<option value=0></option>
           <option value=1>Direct</option>
           <option value=2>Email</option>
           <option value=3>Phone</option>
           <option value=4>Others</option>';
       }else{
         if($_POST['source']==1){
             echo'<option selected value=1>Direct</option>
                  <option value=2>Email</option>
                  <option value=3>Phone</option>
                  <option value=4>Others</option>';
         }elseif($_POST['source']==2){
             echo'<option value=1>Direct</option>
                  <option selected value=2>Email</option>
                  <option value=3>Phone</option>
                  <option value=4>Others</option>';
         }elseif($_POST['source']==3){
             echo'<option value=1>Direct</option>
                  <option value=2>Email</option>
                  <option selected value=3>Phone</option>
                  <option value=4>Others</option>';
         }elseif($_POST['source']==4){
             echo'<option value=1>Direct</option>
                  <option value=2>Email</option>
                  <option value=3>Phone</option>
                  <option selected value=4>Others</option>';
         }   
       }    

      echo'</td>
           <td><input type="text" name="byplace" id="byplace" value="" style="width:120px"></td>
           <td><input type="text" id="delverydatefrm" name="delverydatefrm" style="width:120px" class=date alt='.$_SESSION['DefaultDateFormat']. '></td>
           <td><input type="text" id="delverydateto" name="delverydateto" style="width:120px" class=date alt='.$_SESSION['DefaultDateFormat']. '></td>
           <td><select  id="enqtype" name="enqtype" style="width:120px">';

      if(!isset($_POST['enqtype'])){
      echo'<option value=0></option>
           <option value=7>Joint Venture</option>
           <option value=8>Dealership</option>';
       }else{
         if($_POST['enqtype']==7){
             echo'<option selected value=7>Joint Venture</option>
                  <option value=8>Dealership</option>';
       }elseif($_POST['enqtype']==8){
             echo'<option value=7>Joint Venture</option>
                  <option selected value=8>Dealership</option>'; 
       }
  } 
  
  
  echo'</td>'; 
  echo'<td><input type="text"  id="" name="" style="width:120px"></td>  
       <td><input type="submit" name="dealersearch" id="dealersearch" value="search"></td>
       </tr>';
       
  echo "</table>";
 
echo '</form>';

echo '<br/>';
echo"<div id='dealergrid' style='width:100%;height:240px;background-color:#F0F0F0;overflow:scroll'>"; 
echo "<table style='border:1 solid #F0F0F0;width:80%;height:100px'>";

     echo '<tr>
     
               <td style="border-bottom:1px solid #000000">SL.NO</td>  
               <td style="border-bottom:1px solid #000000">Name</td> 
               <td style="border-bottom:1px solid #000000">Contact No</td>                  
               <td style="border-bottom:1px solid #000000" class="source">Source</td>
               <td style="border-bottom:1px solid #000000" class="district">District</td>
               <td style="border-bottom:1px solid #000000">Created Date</td>
               <td style="border-bottom:1px solid #000000" class="enqtype">Type</td>
               <td style="border-bottom:1px solid #000000">Status</td>                            
               <td style="border-bottom:1px solid #000000">Remarks</td>                               
               <td style="border-bottom:1px solid #000000"></td>   
               </tr>';
               
                $i=1;
                while($row=DB_fetch_array($result)){
                    $viewmail="";
                    if($row['source']==2){$viewmail="View Mail";}
                    $source1="#";                                       
                    if($row['source']==1){$source1="Direct";}if($row['source']==2){$source1="Email";
                    $desr=substr($row["description"],0,60);
                    }
                    if($row['source']==3){$source1="Phone";
                    $desr=substr($row["remark"],0,60);                    
                    }
                    if($row['source']==4){$source1="Other";} 
                    $createddate=ConvertSQLDate($row['createddate']);                  
            printf("<tr style='background:#A8A4DB'>                
            <td width='50px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s<br />%s</td>  
            <td width='100px' class='source'>%s</td>  
            <td width='100px' class='district'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px' class='enqtype'>%s</td>
            <td width='100px'>%s</td>            
            <td width='100px'>%s</td>
            <td width='100px'><a id=".$row['buss_id']."  href='' onclick='showmail(this.id);return false'>%s</a></td> 
            <td width='100px'><a id=".$row['buss_id']."  href='' onclick='selection(this.id);return false'>Select</a></td>          
                                   </tr>", 
                                  $i,                                  
                                  $row['custname'],
                                  $row['custphone'], 
                                  $row['custmob'], 
                                  $source1,
                                  $row['district'],
                                  $createddate,
                                  $row['enquirytype'],
                                  $row['status'],
                                  $desr,
                                  $viewmail
                                       );$i++;//
                                   
                }
 echo "</div>";               
echo "</table>";
echo "</div>";
 echo "</fieldset>";
?>
<script type="text/javascript">


var source=document.getElementById('source').value;   //  alert(source);
if(source!=0)
{
    $(".source").hide(); 
}

var enqtype=document.getElementById('enqtype').value;   // alert(enqtype);
if(enqtype!=0)
{
    $(".enqtype").hide(); 
}

var district=document.getElementById('byplace').value;   // alert(enqtype);
if(enqtype!="")
{
    $(".district").hide(); 
}

function selection(str){            
if (str=="")
  {
  document.getElementById("links").innerHTML="";
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
     document.getElementById("links").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_dealerviewdetails.php?bussid=" + str ,true);
xmlhttp.send(); 
} 


function dealerpay(str){ 
    controlWindow=window.open("bio_dealerPayment.php?bussid=" +str,"dealersPayment","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}



function showmail(str)
{
 controlWindow=window.open("dealershowmail.php?bussid="+str,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1000,height=600");    
}

function dealercontact(str1)
{
    myRef = window.open("bio_dealerview1.php?lead=" + str1);    
}


</script>