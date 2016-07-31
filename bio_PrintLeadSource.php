<?php
 $PageSecurity = 80;
include('includes/session.inc');

echo"<form action='xlpage.php' method='post'>";
  $sourcetypeid=$_GET['id'] ;
  $sourcetypefrom=$_GET['from'];
  $sourcetypeto=$_GET['to'];
  $offic=$_GET['offic'];
  $place=$_GET['place'];
  $enqtype=$_GET['etype'];


//$custname1 = implode(',',$custname);
//$houseno1 = implode(',',$houseno); 
//$housename1 = implode(',',$housename); 
//$custphone1 = implode(',',$custphone); 
//$custmob1 = implode(',',$custmob);
//$custtyp1 = implode(',',$custtyp);
//$district1 = implode(',',$district);
//$leaddate1 = implode(',',$leaddate);
//$leadteam1 = implode(',',$leadteam);

echo"<input type='hidden' name='sourcetypeid' value=$sourcetypeid>"; 
echo"<input type='hidden' name='sourcetypefrom' value=$sourcetypefrom>";   
echo"<input type='hidden' name='sourcetypeto' value=$sourcetypeto>"; 
echo"<input type='hidden' name='off' value=$offic>";
echo"<input type='hidden' name='place' value=$place>";     
echo"<input type='hidden' name='EnqType' value=$enqtype>";   
   echo"<input type='submit' id='xl' name='xl' value='Export'> </form>";
   
?>
