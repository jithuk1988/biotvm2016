<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('district');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('District Maintenance')
	. '" alt="" />' . _('District Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / delete District') . '</div><br />';

if(isset($_GET['delete'])){ $id=$_GET['delete'];
$sql="DELETE FROM bio_district WHERE id = $id";
$result=DB_query($sql,$db);
}
if (isset($_POST['distsub'] )){
  $distsub=$_POST['District'];   $stateid=$_POST['State'];   $cid=$_POST['country'];//exit;
  $sql2="select max(did)+1 as nextid from bio_district where cid=$cid and stateid=$stateid";
echo $sql;
$result=DB_query($sql2,$db);
$myrow=DB_fetch_array($result);
$next=$myrow[0];
if ($next) {$nextid=$next;} else {$nextid=1;};

     //$nextid=getnextid('bio_district','did','where cid='.$cid.' and stateid='.$stateid);
    $sql="INSERT INTO bio_district (cid,stateid,did,district) VALUES($cid,$stateid,$nextid,'$distsub')";
    //echo $sql;
    $result=DB_query($sql,$db);
unset($_POST['country']); 
}
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";

 echo"<table width='700px'><tr><td>";
 echo"<fieldset><legend>Add District</legend>";
  echo"<table>";



     $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);

    echo"<tr><td>Country</td><td>";

  echo '<select name="country" id="country" onchange="showstate(this.value)" style="width:190px">';


  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['cid']==$_POST['country'])
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

  if (!isset($_POST['country'])) {$cid=1;};
  //else {$cid=0;};
  //echo 'cid='.$cid;



    echo"<tr id='showstate'><td>State</td><td>";
            $sql="SELECT * FROM bio_state where cid=$cid ORDER BY stateid";
   // $result=DB_query($sql,$db);
//  echo '<select name="state" id="state" style="width:190px">';
//   $f=0;
//  while($myrow1=DB_fetch_array($result))
//  {
//  if ($myrow1['stateid']==$_POST['state'])
//    {
//    echo '<option selected value="';
//    } else
//    {
//    if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//    }
//    echo $myrow1['stateid'] . '">'.$myrow1['state'];
//    echo '</option>';
//    $f++;
//   }
//  echo '</select>';

  echo'</td>'; echo'</tr>';

   echo"<tr><td>District</td><td><input type='text' name='District' id='District' ></td></tr>";
  echo"<tr><td></td><td><input type='submit' name='distsub' id='distsub' value='submit'></td></tr>";
  echo"</td></tr></table>";
  echo"</fieldset>";

    echo"<tr><td colspan='3'>";   echo"<fieldset><legend>Districts</legend><table width='700px'>";echo"<tr style='background:#585858;color:white'><td>Country</td><td>State</td>
   <td>District id</td><td>District name</td></tr>";
    $sql="SELECT bio_district.id AS id, bio_district.did AS did, district, bio_state.stateid as stateid, bio_state.state AS state, bio_country.cid as cid, bio_country.country AS country
                 FROM bio_district, bio_country, bio_state
                 WHERE bio_country.cid = bio_state.cid
                 AND bio_state.stateid = bio_district.stateid
                 AND bio_country.cid = bio_district.cid
                 ORDER BY cid, stateid, did;";

    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];
          $did=$myrow['did']; $district=$myrow['district']; $stateid=$myrow['stateid'];
          $state=$myrow['state']; $country=$myrow['country'];$cid=$myrow['cid']; $id=$myrow['id'];
          echo"<tr style='background:#A8A4DB'>
          <td>$country</td><td>$state</td><td>$did</td><td>$district</td><td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td></tr>";
                 }
             echo"</table></fieldset>";
    echo"</td></tr>";
 echo"</table>
 </form>";

function getnextid($table,$field,$condition)   {
$sql2="select max($field)+1 as nextid from $table $condition";
//echo $sql;
$result=DB_query($sql2,$db);
$myrow=DB_fetch_array($result);
$nextid=$myrow[0];
echo 'nnnn'.$nextid;
return $nextid;
}
?>
<script>
function dlt(str){
location.href="?delete=" +str;



}

function replace_html(id, content) {
	document.getElementById(id).innerHTML = content;
}

var progress_bar = new Image();
progress_bar.src = '4.gif';

function show_progressbar(id) {
	replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}

function showstate(str){

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');
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
