<?php
$PageSecurity = 80;    
include('includes/session.inc'); 
$plantid=$_GET['id'];
echo "<br>";
$plantid2=explode(',',$plantid);
$n=sizeof($plantid2);
$plants="";
for($i=0;$i<$n;$i++)        {

$sql="SELECT longdescription
      FROM stockmaster
      WHERE stockid='$plantid2[$i]'";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$plants=$myrow[0].",".$plants;

} 
echo $plants;

echo'<input type="hidden" id="plantid" name="Plantid" value="'.$plantid.'">'; 

?>
