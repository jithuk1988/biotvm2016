<?php
  $PageSecurity = 11;

 include('includes/session.inc');
 $newid=$_GET['idchange'];  
 $oldid=$_GET['idold'];
 
 $sql="UPDATE womaterialrequest
       SET reqno=$newid
       WHERE reqno=$oldid";
 $result=DB_query($sql,$db);
 
 $sql="UPDATE womaterialrequestdetails
       SET reqno=$newid
       WHERE reqno=$oldid";
 $result=DB_query($sql,$db);
 
 echo $newid;
 echo '<input type="hidden" name="Reqno"  value='.$newid.'>'; 
 

?>
