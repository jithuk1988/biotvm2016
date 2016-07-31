<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Collection');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Document View</font></center>';
    
    
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
    
    
    
    
      echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>"; 
      
echo"<div id=grid>";
echo"<fieldset style='width:70%;'>";
echo"<legend><h3>Sale Order Received Customers</h3></legend>";

echo"<table style='border:1px solid #F0F0F0;width:90%'>"; 

echo"<tr><td>Customer Type</td><td>Name</td><td>Contact No</td><td>District</td></tr>";

echo"<tr>"; 

    echo '<td><select name="enq" id="enq" style="width:135px" onchange=showdocs(this.value)>';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$_POST['enq'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
           echo '</option>';  
    }

    echo '</select></td>';


    echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 
    echo "<td><input style=width:150px type='text' name='district' id='district' style='width:100px'></td>";
      
    
echo"</tr>";

echo"<tr><td>Date From</td><td>Date To</td><td>Plan Type</td></tr>";    
echo"<tr>";


    echo "<td><input type=text id='periodfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodfrm' value='$_POST[periodfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
    echo "<td><input type=text id='periodto' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodto' value='$_POST[periodto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
    echo "<td><input style=width:150px type='text' name='plantype' id='plantype' style='width:100px'></td>";
    echo '<td></td><td><input type=submit name=filter value=Search></td>';
    
echo"</tr>"; 
 








echo"</table>";
echo"<br />";

echo "<table class='selection' style='width:90%'>";
        
  $title="<b>Document Collection:</b>";          
        
if($_POST['enq']!=0){
       $result=DB_query("SELECT * FROM bio_enquirytypes where enqtypeid=".$_POST['enq'],$db);
       $myrow1=DB_fetch_array($result);
       $title.=' Enquiry Type:<b><i>'.$myrow1['enquirytype'].'</i></b>';
} 
if($_POST['district']!=NULL){
       $title1.=' District:<b><i> '.$_POST['district'].'</i></b>';   
}    
if($_POST['periodfrm']!=NULL){
$title.=' Date from <b><i>'.$_POST['periodfrm'].' </i></b>';
}
if($_POST['periodto']!=NULL){
 $title.=' to <b><i>'.$_POST['periodto'].' </i></b>';   
}        
        
        
        
  echo "<tr><td colspan='8'><font size='-1'>".$title."</font></td></tr>";         
        
        echo '<tr>  <th>' . _('Slno') . '</th>
                    <th>' . _('Orderno') . '</th>
                    <th>' . _('Customer Name') . '</th>  
                    <th>' . _ ('SaleOrder Date') . '</th>  
                    <th class="enqtype">' . _ ('Enquiry Type') . '</th> 
                    <th>' . _('Team') . '</th>
                    <th>' . _('Office') . '</th>
              </tr>';

//$sql_so="SELECT bio_leads.leadid,
//                salesorders.orderno,
//                bio_cust.custname,
//                salesorders.orddate,
//                bio_enquirytypes.enquirytype,
//                bio_leadteams.teamname,
//                bio_office.office
//                 
//         FROM   bio_leads,salesorders,bio_cust,bio_enquirytypes,bio_leadteams,bio_leadtask,bio_office,bio_district
//         WHERE  salesorders.leadid=bio_leads.leadid
//         AND    bio_office.id=bio_leadteams.office_id
//         AND    bio_leadtask.taskid=25
//         AND    bio_leadtask.taskcompletedstatus=0 
//         AND    bio_leadtask.viewstatus=1
//         AND    bio_leadtask.teamid=bio_leadteams.teamid
//         AND    bio_leadtask.leadid=bio_leads.leadid
//         AND    bio_enquirytypes.enqtypeid=bio_leads.enqtypeid
//         AND    bio_cust.cust_id=bio_leads.cust_id
//         AND    bio_district.did=bio_cust.district
//         AND    bio_district.stateid=bio_cust.state
//         AND    bio_district.cid=bio_cust.nationality   
//         AND    bio_leads.leadstatus!=20";

$sql_so="SELECT bio_leads.leadid,salesorders.orderno,bio_cust.custname,salesorders.orddate,bio_enquirytypes.enquirytype,bio_leadteams.teamname,bio_office.office    
FROM bio_documentlist
INNER JOIN bio_leads ON (bio_documentlist.leadid=bio_leads.leadid AND bio_leads.leadstatus!=20)"; 
if($_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='BDM' OR $_SESSION['designationcode'] =='BDE') 
{
 $sql_so .=" AND bio_leads.enqtypeid=2";        
}
$sql_so .=" INNER JOIN bio_cust ON bio_leads.cust_id=bio_cust.cust_id    
INNER JOIN salesorders ON bio_leads.leadid=salesorders.leadid
INNER JOIN salesorderdetails ON salesorderdetails.orderno=salesorders.orderno
INNER JOIN stockmaster ON stockmaster.stockid=salesorderdetails.stkcode
LEFT JOIN bio_enquirytypes ON bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
LEFT JOIN bio_leadtask ON (bio_leads.leadid=bio_leadtask.leadid AND bio_leadtask.taskid=25 AND bio_leadtask.viewstatus=1 AND bio_leadtask.taskcompletedstatus=0 AND bio_leadtask.teamid IN ($team_array))
LEFT JOIN bio_leadteams ON bio_leadtask.teamid=bio_leadteams.teamid  
LEFT JOIN bio_office ON bio_office.id=bio_leadteams.office_id 
LEFT JOIN bio_district ON (bio_cust.district=bio_district.did AND bio_district.stateid=bio_cust.state AND bio_district.cid=bio_cust.nationality)  
WHERE bio_documentlist.status!=8"; 
         
         if(isset($_POST['filter']))
         {
                 
                 if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql_so .= " AND bio_leadtask.duedate BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
                 }
                 
                 if($_POST['enq']!=0)
                 {
                     $sql_so .= " AND bio_leads.enqtypeid='".$_POST['enq']."'"; 
                 }
                 
                 if($_POST['name']!="")
                 {
                     $sql_so .= " AND bio_cust.custname LIKE '".$_POST['name']."%'"; 
                 }
                 
                 if($_POST['contno']!="")
                 {
                     $sql_so .= " AND bio_cust.custmob LIKE '".$_POST['contno']."%'"; 
                 }
                 
                 if($_POST['district']!="")
                 {
                     $sql_so .= " AND bio_district.district LIKE '".$_POST['district']."%'"; 
                 }
                  if($_POST['plantype'])
                 {
                      $sql_so .= " AND (stockmaster.description LIKE '".$_POST['plantype']."%' OR 
                      stockmaster.longdescription LIKE '".$_POST['plantype']."%')" ; 
                 }
                              
         }
//if($_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='BDM' OR $_SESSION['designationcode'] =='BDE') 
//{
// $sql_so .=" AND bio_leads.enqtypeid=2 AND bio_leadtask.teamid IN ($team_array)";        
//}
//else
//{
// $sql_so .=" AND bio_leadtask.teamid IN ($team_array)"; 
//}

$sql_so .=" GROUP BY bio_documentlist.orderno"; 

//echo$sql_so; 
$result_so=DB_query($sql_so,$db);

$k=0;
$slno=1;
while($row_so=DB_fetch_array($result_so))
{
    $leadid=$row_so['leadid'];
    $orderno=$row_so['orderno']; 
    
    
          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          
    
                       echo"<td>$slno</td>
                                     <td>".$row_so['orderno']."</td> 
                                     <td>".$row_so['custname']."</td>
                                     <td>".ConvertSQLDate($row_so['orddate'])."</td> 
                                     <td class='enqtype'>".$row_so['enquirytype']."</td> 
                                     <td>".$row_so['teamname']."</td> 
                                     <td>".$row_so['office']."</td>
                                     <td width='50px'><a style='cursor:pointer;' id='$orderno' onclick='viewdocs(this.id);'>" . _('View ') . "</a></td>  
                                </tr>";
                                
 $slno++;                               
    
}



echo"</table>";

echo"</fieldset>";
echo"</div>";
echo"</form>";
      
      


?>


<script type="text/javascript">  


var enqtype=document.getElementById('enq').value; 

if(enqtype!=0)
{
    $(".enqtype").hide(); 
}

function viewdocs(str)
{
    //alert(str);
location.href="bio_documentmanagement.php?orderno=" + str;   

}

function showdocs(str1){   

//alert(str1); 

if (str1=="")
  {
  document.getElementById("showdocument").innerHTML="";
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
         document.getElementById("showdocument").innerHTML=xmlhttp.responseText; 
    }
  } 
xmlhttp.open("GET","bio_docCustSelection.php?enqid=" + str1,true);
xmlhttp.send(); 
} 

</script>