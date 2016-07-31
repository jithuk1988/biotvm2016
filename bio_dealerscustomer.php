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

echo"<tr><td>Dealer</td><td>Name</td><td>Contact No</td><td>District</td></tr>";

echo"<tr>"; 

  $sql_dealers="SELECT debtorsmaster.debtorno,debtorsmaster.name 
                  FROM debtorsmaster,bio_dealerterritory,bio_businessassociates_enq 
                 WHERE bio_dealerterritory.debtorno=debtorsmaster.debtorno
                   AND bio_businessassociates_enq.buss_id=bio_dealerterritory.buss_id
                   AND debtorsmaster.salestype=06 
                   AND debtorsmaster.debtorno LIKE 'DL%'";
                   
  $result_dealers=DB_query($sql_dealers,$db);
  echo '<td> <select name="dealer" id="dealer" onchange=dealerdetails(this.value)>';
   $f=0;
  while($myrow_dealers=DB_fetch_array($result_dealers))
  {         
  if ($myrow_dealers['debtorno']==$dealercode) 
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
    echo $myrow_dealers['debtorno'] . '">'.$myrow_dealers['debtorno']." - ".$myrow_dealers['name'];
    echo '</option>';
    $f++;
   }
  echo '</select>'; 

    echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 
    echo "<td><input style=width:150px type='text' name='district' id='district' style='width:100px'></td>";
      
    
echo"</tr>";

echo"<tr><td>Date From</td><td>Date To</td></tr>";    
echo"<tr>";


    echo "<td><input type=text id='periodfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodfrm' value='$_POST[periodfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
    echo "<td><input type=text id='periodto' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodto' value='$_POST[periodto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";

    echo '<td></td><td><input type=submit name=filter value=Search></td>';
    
echo"</tr>"; 
 








echo"</table>";
echo"<br />";
        echo "<table class='selection' style='width:90%'>";
        echo '<tr>  <th>' . _('Slno') . '</th>
                    <th>' . _('Id') . '</th>
                    <th>' . _('Name') . '</th>  
                    <th>' . _('Contact No') . '</th>
                    <th>' . _('District') . '</th>  
                    <th>' . _('Plant') . '</th>   
                    <th>' . _('Dealer') . '</th> 

              </tr>';

  $sql="SELECT `bio_childcustomer`.`id`, 
               `bio_childcustomer`.`name`,  
               `bio_childcustomer`.`phoneno`,  
               `bio_childcustomer`.`mobileno`, 
               `bio_childcustomer`.`LSG_type`,
               `bio_district`.`district`,
               `bio_corporation`.`corporation`, 
               `bio_municipality`.`municipality`, 
               `bio_panchayat`.`name` AS panchayath,
                debtorsmaster.name AS dealername,
                stockmaster.description 

          FROM `bio_childcustomer`
     INNER JOIN debtorsmaster ON bio_childcustomer.dealercode=debtorsmaster.debtorno        
     INNER JOIN stockmaster ON bio_childcustomer.stockid=stockmaster.stockid
      LEFT JOIN bio_district ON bio_district.did = bio_childcustomer.did
            AND bio_district.stateid = bio_childcustomer.stateid
            AND bio_district.cid = bio_childcustomer.cid 
      LEFT JOIN bio_corporation ON bio_corporation.district = bio_childcustomer.LSG_name 
            AND bio_corporation.district = bio_childcustomer.did 
            AND bio_corporation.state = bio_childcustomer.stateid 
            AND bio_corporation.country = bio_childcustomer.cid
      LEFT JOIN bio_municipality ON bio_municipality.id = bio_childcustomer.LSG_name 
            AND bio_municipality.district = bio_childcustomer.did 
            AND bio_municipality.state = bio_childcustomer.stateid 
            AND bio_municipality.country = bio_childcustomer.cid
      LEFT JOIN bio_panchayat ON bio_panchayat.id = bio_childcustomer.block_name 
            AND bio_panchayat.block = bio_childcustomer.LSG_name 
            AND bio_panchayat.district = bio_childcustomer.did 
            AND bio_panchayat.state = bio_childcustomer.stateid 
            AND bio_panchayat.country = bio_childcustomer.cid 
          WHERE bio_childcustomer.inspectionStatus!=3     
      ";
         
         if(isset($_POST['filter']))
         {
                 
                 if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql .= " AND bio_leadtask.duedate BETWEEN '".FormatDateForSQL($_POST['datefrm'])."' AND '".FormatDateForSQL($_POST['dateto'])."'"; 
                 }
                 
                 if($_POST['dealer']!="")
                 {
                     $sql .= " AND bio_childcustomer.dealercode= '".$_POST['dealer']."'"; 
                 }
                 
                 
                 if($_POST['name']!="")
                 {
                     $sql .= " AND bio_childcustomer.name LIKE '".$_POST['name']."%'"; 
                 }
                 
                 if($_POST['contno']!="")
                 {
                     $sql .= " AND bio_childcustomer.mobileno LIKE '".$_POST['contno']."%'"; 
                 }
                 
                 if($_POST['district']!="")
                 {
                     $sql .= " AND bio_district.district LIKE '".$_POST['district']."%'"; 
                 }
                              
         }
        $sql.=" ORDER BY  bio_childcustomer.dealercode";
//if($_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='BDM' OR $_SESSION['designationcode'] =='BDE') 
//{
// $sql_so .=" AND bio_leads.enqtypeid=2 AND bio_leadtask.teamid IN ($team_array)";        
//}
//else
//{
// $sql_so .=" AND bio_leadtask.teamid IN ($team_array)"; 
//}

//$sql .=" GROUP BY bio_documentlist.orderno"; 

//echo$sql_so; 
$result=DB_query($sql,$db);

$k=0;
$slno=1;
while($myrow=DB_fetch_array($result))
{
    $id=$myrow['id'];
    
    
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
                                     <td>".$myrow['id']."</td> 
                                     <td>".$myrow['name']."</td>
                                     <td>".$myrow['phoneno']."</td> 
                                     <td>".$myrow['district']."</td> 
                                     <td>".$myrow['description']."</td>
                                     <td>".$myrow['dealername']."</td>  
                                     <td width='50px'><a style='cursor:pointer;' id='$id' onclick='viewdocs(this.id);'>" . _('View ') . "</a></td>  
                                </tr>";
                                
 $slno++;                               
    
}



echo"</table>";

echo"</fieldset>";
echo"</div>";
echo"</form>";
      
      


?>


<script type="text/javascript">  

function viewdocs(str)
{
    //alert(str);
location.href="bio_dealerdocmanagement.php?childid=" + str;   

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