<?php

$PageSecurity = 80;
include('includes/session.inc');

echo$grade= $_GET['grade'];
$leadid=$_GET['leadid'];

echo $sql="UPDATE bio_leads SET grade = '".$grade."' WHERE leadid='".$leadid."'";
DB_query($sql,$db);

?>
