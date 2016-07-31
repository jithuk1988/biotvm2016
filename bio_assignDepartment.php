<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Assigning Department');
include('includes/header.inc');
echo '<a href="index.php">Back to Home</a>';  



 if(isset($_GET['delete']))
  { 
      $did=$_GET['delete'];    
      $sql= "delete from bio_incidenthandlingoffcr where bio_incidenthandlingoffcr.offcr_id = $did";
     $result=DB_query($sql,$db); 
}
if(isset($_GET['edit'])){
       $eid=$_GET['edit']; 
$sql="SELECT * FROM bio_incidenthandlingoffcr  WHERE bio_incidenthandlingoffcr.offcr_id = $eid";
$result=DB_query($sql,$db);
$myrow2=DB_fetch_array($result);

$types=$myrow2['types']; 
$nationality=$myrow2['nationality'];
$state=$myrow2['state'];
$district=$myrow2['district'];
$department=$myrow2['department']; 
$officer=$myrow2['officer'];

}






  if (isset($_POST['submit'])){
 $_POST['SelectedType'];
    
    if ($_POST['SelectedType']!=""){
     $sql = "UPDATE bio_incidenthandlingoffcr
                    SET 
            types='".$_POST['type']."' ,nationality='".$_POST['country']."',state='".$_POST['State']."',
            district='".$_POST['District']."' ,department='".$_POST['dept']."',officer='".$_POST['empname']."'
            WHERE offcr_id =" .$_POST['SelectedType'];
     $result=DB_query($sql,$db);
 }
 else {
 $sql = "INSERT INTO bio_incidenthandlingoffcr ( types,
                                                 nationality, 
                                                 state, 
                                                 district,
                                               department,
                                               officer)
                                  VALUES ('" . $_POST['type'] . "',
                                          '" . $_POST['country'] . "',
                                          '" . $_POST['State'] . "',
                                          '" . $_POST['District'] . "',
                                          '" . $_POST['dept'] . "',
                                          '" . $_POST['empname'] . "')";                                           
        $result = DB_query($sql,$db);
        
}   }
  
echo '<table style=width:30%><tr><td>';
echo '<fieldset style="height:250px">';
echo '<legend><b>Assigning Department</b></legend>';

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo '<br><br><table>';
                                                            
 echo '<tr><td>Incident Type</td>';
echo '<td><select name="type" id="type">';
$sql1="select * from bio_incidentType";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$types) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['type'];
    echo '</option>';

} 
   echo '</select></td></tr>';   
   $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
    
    echo"<tr><td>Country</td><td>";
    echo '<select name="country" id="country" onchange="showstate(this.value)" style="width:170px">';
    
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
  echo '</select></td></tr>';
  


$sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showstate'><td>State</td><td>";
 echo '<select name="State" id="state" style="width:170px" onchange="showdistrict(this.value)">';
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
  echo'</tr>';
 
  
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showdistrict'><td>District</td><td>";
 echo '<select name="District" id="Districts" style="width:170px">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$district)
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
  echo'</tr>';  
 
    
  echo '<tr><td>Incident Handiling Department</td>';
echo '<td><select name="dept" id="dept" style="width:170px"  onblur="showempname(this.value)">';
$sql1="select * from bio_dept";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{        
    if ($row1['deptid']==$department) 
    {    
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['deptid'].'">'.$row1['deptname'];
    echo '</option>';
     
} 
   echo '</select></td></tr>';  
   



 $sql="SELECT * FROM bio_leadteams";
    $result=DB_query($sql,$db); 
       echo '<tr><td>Officer</td><td id="empdiv">';   
   echo '<select name="empname" id="empname" style="width:170px">';    
  
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['teamid']==$officer)  
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
    echo $myrow1['teamid'] . '">'.$myrow1['teamname'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td></tr>';
  echo '<tr><td><input type="hidden" name="SelectedType" value='.$eid.'>&nbsp;</td></tr>'; 
    
  echo'<tr><td></td><td><input type="Submit" name="submit" value="' . _('Save') . '" onclick=" if(validate()==1)return false"></td></tr>'; 
      echo '</table>';
      echo '</form></fieldset>';


      echo "<fieldset style='width:600px'>";
      echo "<legend><h3>Assigning Department</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr>  <th>' . _('Slno') . '</th>  
           <th>' . _('Incident Type') . '</th> 
           <th>' . _('Officer') . '</th>   <th>' . _('Handiling Department') . '</th>     
           </tr>';
               
$sql1="SELECT bio_incidenthandlingoffcr.offcr_id,
              bio_incidenttype.id, bio_incidenttype.type,bio_incidenthandlingoffcr.types,
              bio_incidenthandlingoffcr.officer,bio_leadteams.teamid,bio_leadteams.teamname,
              bio_dept.deptid,bio_dept.deptname,bio_incidenthandlingoffcr.department
       FROM 
              bio_incidenthandlingoffcr,bio_incidenttype,bio_leadteams,bio_dept
              WHERE bio_incidenthandlingoffcr.officer=bio_leadteams.teamid
              AND   bio_incidenthandlingoffcr.department=bio_dept.deptid
              AND   bio_incidenttype.id=bio_incidenthandlingoffcr.types";
  
  
  
  
  
  $result1=DB_query($sql1, $db);  
 $k=0 ;$slno=0; 
  while($row1=DB_fetch_array($result1) )
  
  { 
          if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  
      $slno++;
  $id=$row1['offcr_id'];
  $type=$row1['type'];
  $teamname=$row1['teamname'];
  $deptname=$row1['deptname'];
 
        

echo"<tr style='background:#A8A4DB'><td>$slno</td>
                                    <td>$type </td> <td>$teamname </td><td>$deptname </td>
                                    <td><a href='#' id='$row1[offcr_id]' onclick='edit(this.id)'>Edit</a></td>
                                    
                                    <td><a href='#' id='$row1[offcr_id]' onclick='dlt(this.id)'>Delete</a></td>      
                                    
                                    </tr>";     

}                           
 
?>
      
<script>

function dlt(str){
        //alert(str);
location.href="?delete=" +str;         
 
}

 function edit(str)
 {
  // alert("yyyyyyy");  
location.href="?edit=" +str;         
 
}

  function showstate(str){ 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
xmlhttp.open("GET","bio_showstate.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();
}
function showempname(){
 str1=document.getElementById("country").value;   
 str2=document.getElementById("state").value;   
 str3=document.getElementById("Districts").value;
 str=document.getElementById("dept").value; 
 //alert(str3);  
// alert(str);   
// str1=document.getElementById("office_id").value;   
   
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
    document.getElementById("empdiv").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showempname.php?dept=" + str +"&country="+str1+"&state="+str2+"&district="+str3,true);
xmlhttp.send();
}


  function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('type','Please select the Incident Type');  if(f==1){return f; }  }
if(f==0){f=common_error('Districts','Please select the District');  if(f==1){return f; }  }  
if(f==0){f=common_error('dept','Please select a Incident Handiling Department');  if(f==1){return f; }  }  
if(f==0){f=common_error('empname','Please select the Officer');  if(f==1){return f; }  }  
  

}




</Script>  