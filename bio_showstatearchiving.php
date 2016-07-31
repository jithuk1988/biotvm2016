<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
if($_GET['country']){
         echo "<td>State*</td><td>";
     $cid=$_GET['country'];
     $state=$_GET['state'];
        $sql="SELECT * FROM bio_state WHERE cid=$cid ORDER BY stateid";
    $result=DB_query($sql,$db);
   

    
  echo '<select name="State" id="state" tabindex=10 style="width:190px" onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==$_POST['state'])
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
}

if($_GET['state']){      $state=$_GET['state']; $country1=$_GET['country1'];
         echo"<td>District*</td><td>";
            $sql="SELECT * FROM bio_district WHERE bio_district.stateid=$state 
            AND bio_district.cid=$country1
            ORDER BY did";
    $result=DB_query($sql,$db);
  echo '<select name="District" id="Districts" style="width:190px" tabindex=11 onchange="addnew(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['District'])
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
 //  if ($_POST['District'] == "select") {
//echo '<option selected value="New">' . _('--New--');
//}else {
//if($f==0){ echo '<option value="">' . _(' '); }
   // echo '<option value="New">' . _('--New--');
//}
  echo '</select>';






  echo'</td>';
}


//if($_GET['new']){echo"<td>Districts:</td><td><input type='text' name='Districts' id='District' ></td>"}





?>
