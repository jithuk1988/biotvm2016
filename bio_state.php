<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('state');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('States/Union Territory Maintenance')
	. '" alt="" />' . _('State / Union Territory Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / delete States') . '</div><br />';

if(isset($_GET['delete'])){ $id=$_GET['delete'];
$sql="DELETE FROM bio_state WHERE bio_state.id = $id";
//$sql= "DELETE FROM bio_nation WHERE bio_nation.nid = $natid";
//$sql="DELETE bio_nation.nation FROM bio_nation WHERE bio_nation.nid=$natid";
//echo $sql;
$result=DB_query($sql,$db);
}
if (isset($_POST['statesub'])){
     $state=$_POST['state'];   $cid=$_POST['country'];
     $sql2="SELECT max( stateid ) +1 AS nextid FROM bio_state WHERE cid =$cid";
     //echo $sql2;
     $result2=DB_query($sql2,$db);
     $myrow2=DB_fetch_array($result2);
     $next=$myrow2[0];
     if ($next) {$nextid=$next;} else {$nextid=1;};
    $sql="INSERT INTO bio_state (bio_state.cid,stateid,state) VALUES($cid,$nextid,'$state')";
    $result=DB_query($sql,$db);
    
}
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";   
        
 echo"<table width='400px'><tr><td>";
 echo"<fieldset><legend>Add State</legend>";
  echo"<table>";   

     
     
     $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
   
    echo"<tr><td>Country</td><td>";
    
  echo '<select name="country" id="country" style="width:190px">';

  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['cid']==$_POST['country'])
    { 
    echo '<option selected value="';
    } else 
    {
//    if ($f==0)
//        {
//        echo '<option>';
//        }
        echo '<option value="';
    }
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
   // $f++;
   } 
  echo '</select></td></tr>'; 
   echo"<tr><td>STATE</td><td><input type='text' name='state' id='state' ></td></tr>";        
  echo"<tr><td></td><td><input type='submit' name='statesub' id='statesub' value='submit'></td></tr>";   echo"</td></tr></table>";  
  echo"</fieldset>";

    echo"<tr><td colspan='2'>";   echo"<fieldset><legend>State / Union Territory</legend><table width='400px'>";echo"<tr style='background:#585858;color:white'>
    <td>Country</td><td>State id</td><td>State / UT name</td></tr>";
    $sql="SELECT bio_state.id AS id, bio_state.stateid AS stateid, bio_state.state AS state, bio_country.cid AS cid, bio_country.country AS country
                 FROM `bio_state` , bio_country
                 WHERE bio_country.cid = bio_state.cid
                 ORDER BY cid, stateid";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];
          $id=$myrow['id'];
          $country=$myrow['country'];
          $stateid=$myrow['stateid'];
          $state=$myrow['state'];
                   echo"<tr style='background:#A8A4DB'><td>$country</td><td>$stateid</td><td>$state</td><td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td></tr>";
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

</script>
