<?php
$PageSecurity = 80;    
include('includes/session.inc'); 
$leadid=$_GET['p'];  

$sql8="SELECT feasibilitystudy_id
       FROM bio_feasibilitystudy
       WHERE leadid=$leadid";
$result8=DB_query($sql8,$db);
$myrow8=DB_fetch_array($result8);
$fsid=$myrow8['feasibilitystudy_id'];  

echo"<td><input type='hidden' name='Feasibilityid' id='feasibilityid' style='width:165px;' value=$fsid style=width:170px>$fsid</td>";
?>
