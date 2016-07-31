<?php
  $PageSecurity =81;
include('includes/session.inc');

$title = _('Lead List');  
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





echo '<p class="page_title_text"><img src="' . $rootpath . '/css/' . $theme . '/images/inventory.png" title="' . _('Inventory Items') . '" alt="" />' . ' ' . _('Sales Leads') . '</p>';


echo '<table width="90%" colspan="2" cellpadding="4">';
echo '<tr>
        <th width="33%">' . _('Lead Inquiries') . '</th>
        <th width="33%">' . _('Lead Transactions') . '</th>
        <th width="33%">' . _('Lead Maintenance') . '</th>
    </tr>';
echo '<tr><td class="select">';
    /*Stock Inquiry Options */
echo '<a href="' . $rootpath . '/bio_leadreport.php?">' . _('List All Leads') . '</a><br />';
echo '</td><td class="select">';
    /* Stock Transactions */
echo '</td><td class="select">';
    /*Stock Maintenance Options */
echo '</td></tr></table>';
    
    
echo '<form action="bio_leadreport.php" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<p class="page_title_text"><img src="' . $rootpath . '/css/' . $theme . '/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ' . _('Search for Leads'). '</p>';

echo "<fieldset style='width:65%;'>";     
echo "<legend><h3>Search By</h3>";     
echo "</legend>";
 
echo"<table><tr><td>Date From";
echo'<input type="text"  id="datefrm" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datef" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' style="width:150px">';    

echo"</td><td>To Date";
echo'<input type="text"  id="dateto" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datet" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' style="width:150px">';    
echo"</td>";
      
echo"<td>Office";
echo '<select name="offic" id="offic" style="width:150px">';
   $sql1="SELECT bio_office.id,bio_office.office
FROM bio_office";
   $result1=DB_query($sql1,$db) ;
   $a=0;
      while ($myrow1 = DB_fetch_array($result1)) {
    if ($myrow1['id']==$_POST['office']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>";  }

        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['office'];
        echo  '</option>';
        $a++;
    }  
echo"</select>";    
echo"</td>";
/*     
echo '<td>Place';
echo '<input type="text" name="byplace" id="place"></td>';
*/
echo '<td>Enquiry Type';
echo '<select name="enquiry1" id="enquiry1" style="width:150px">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$_POST['enquiry1']) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }
     
echo '</select>';
echo '</td>';
     
echo "<td>";
echo 'LeadSource'; 
echo '<select name="leadsrc" id="printnshow" style="width:150px">';
$sql1="SELECT id,sourcename, sourcetypeid
FROM bio_leadsources";
   $result1=DB_query($sql1,$db) ;
       $a=0;
   while ($myrow1 = DB_fetch_array($result1)) {
    if ($myrow1['id']==$_POST['leadsrc']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>"; echo'<option value="ALL">---Select ALL---</option>';  }


        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['sourcename'];
        echo  '</option>';
        $a++;
    }  
echo '</select>';    
echo"</td>";
   
echo '<td>Lead Status'; 
echo'<select name="LeadStatus" id="leadstatus" style="width:150px">';
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
  
echo"<tr></tr>";
echo"<tr></tr>";
$sql="SELECT * FROM bio_country ORDER BY cid";
$result=DB_query($sql,$db);
echo"<tr><td style='width:50%'>Country";
    echo '<select name="country" id="country" onchange="showstate(this.value)" style="width:150px">';
    
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   }
echo '</select></td>';
  
$sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";
$result=DB_query($sql,$db);
//echo"<tr id='showstate'><td>State</td><td>";
echo"<td id='showstate'>State";
echo '<select name="State" id="state" style="width:150px" onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
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
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
echo '</select>';
echo'</td>'; 
//echo'</tr>';
  
$sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
$result=DB_query($sql,$db);
//echo"<tr id='showdistrict'><td>District</td><td>";
echo"<td id='showdistrict'>District";
echo '<select name="District" id="Districts" style="width:150px">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['district'])
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
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   }
echo '</select>';
echo'</td>';

echo '<td>Created by';
   echo '<select name="Created" id="created" style="width:150px">';
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


echo '<td>Team';
   echo '<select name="team" id="team" style="width:150px">';
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

  
echo '<tr>';
echo'<td></td>';
echo'<td></td>';
echo'<td></td>';
//    echo'<td></td>';
echo'<td><input type="submit" name="filterbut" id="filterbut" value="Search Now"></td></tr>';
echo"</table>";   
  
  
    
echo'</fieldset>';
 echo '</form><br />';   

    include ('includes/footer.inc');
?>

<script type="text/javascript">

//var str1=document.getElementById('country').value;
//alert(str1);


function showstate(str){

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
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
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate.php?countrysr=" + str,true);
xmlhttp.send();
}

function showdistrict(str){
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate.php?statesr=" + str + "&country1=" + str1,true);
xmlhttp.send();

}

</script>