<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('district');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('District Maintenance')
	. '" alt="" />' . _('District Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / delete District') . '</div><br />';

if(isset($_GET['delete'])){ $did=$_GET['delete'];
$sql="DELETE FROM bio_district WHERE bio_district.did = $did";
//$sql= "DELETE FROM bio_nation WHERE bio_nation.nid = $natid";
//$sql="DELETE bio_nation.nation FROM bio_nation WHERE bio_nation.nid=$natid";
$result=DB_query($sql,$db); 
}
if (isset($_POST['distsub'])){
     $distsub=$_POST['District'];   $state=$_POST['ste'];    
    $sql="INSERT INTO bio_district (bio_district.stateid,bio_district.district) VALUES($state,'$distsub')";
    $result=DB_query($sql,$db);   
    
}
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";   
        
 echo"<table><tr><td>";
 echo"<fieldset><legend>Add District</legend>";
  echo"<table>";   

     
     
     $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
   
    echo"<tr><td>Country</td><td>";

  echo '<select name="natio" id="natio" onchange="showstate(this.value)" style="width:190px">';

  
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==$_POST['natio'])  
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
  
//                                                if(!isset($_POST['natio'])){$f=1;}  
//              echo  $nat=$_POST['natio'];
     

   
    echo"<tr id='showstate'><td>State</td><td>";
            $sql="SELECT * FROM bio_state ORDER BY cid,stateid";
    $result=DB_query($sql,$db);
  echo '<select name="ste" id="ste" style="width:190px">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['stateid']==$_POST['ste'])  
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


  
  
  
  
  echo'</td>'; echo'</tr>'; 
  
  
   
   echo"<tr><td>District</td><td><input type='text' name='District' id='District' ></td></tr>";        
  echo"<tr><td></td><td><input type='submit' name='distsub' id='distsub' value='submit'></td></tr>";   echo"</td></tr></table>";  
  echo"</fieldset>";
  
    echo"<tr><td colspan='3'>";   echo"<fieldset><legend>Districts</legend><table>";echo"<tr style='background:#585858;color:white'><td>Country</td><td>State</td>
   <td>District id</td><td>District name</td></tr>";  
    $sql="SELECT bio_country.country,bio_state.state,bio_district.district
FROM bio_district, bio_country, bio_state
WHERE bio_country.cid = bio_state.cid
AND bio_state.stateid = bio_district.stateid
ORDER BY bio_district.did";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
         $did=$myrow[2]; $district=$myrow[3];
          $state=$myrow[1]; $country=$myrow[0];
                   echo"<tr style='background:#A8A4DB'><td>$country</td><td>$state</td><td>$did</td><td>$district</td><td><a href='#' id='$sid' onclick='dlt(this.id)'>delete</a></td></tr>";
                 }    
             echo"</table></fieldset>";
    echo"</td></tr>";         
 echo"</table>
 </form>";
?>
<script>
function dlt(str){
location.href="?delete=" +str;         
    
    
    
}
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
xmlhttp.open("GET","bio_showstate.php?country=" + str,true);
xmlhttp.send();
}
</script>
