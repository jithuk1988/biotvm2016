<?php
$PageSecurity = 80;  
include('includes/session.inc'); 

        // echo"jjj";
if($_GET['maincatid']){    
//$planttype=$_GET['maincatid'];
$sql="SELECT stockmaster.stockid,stockmaster.description FROM stockmaster WHERE stockmaster.categoryid='".$_GET['maincatid']."'";
  $result=DB_query($sql,$db);
  echo'<td><select id="plantname" name="plantname" onchange=showschedule(this.value);>';//
    $f=0;
  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['stockid']==$planttype)  
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
    echo $myrow['stockid'] . '">'.$myrow['description'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td>';
  if($_GET['type']==2){
      echo "<td><input type='button' value='New Plant' name='new_plant' id='new_plant' onclick=view_newplant();></td>";
  }


} 
if($_GET['ptype']){ 
    echo "<td><input type='text' name='plantname_dse' id='plantname'>";
}

?>
