<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 
 
 $enqid=$_GET['enqid'];
  $page=$_GET['page'];

$sql="SELECT id,body_type,`message` FROM bio_mailbody where id=$enqid ";
$result=DB_query($sql,$db);

  echo '<option value=></option>';
  while($myrow1=DB_fetch_array($result))
  {  
    $message= $myrow1['message'];
  }
  if($page==1)
  {
     echo"<td ><textarea name='Message' id='message' rows=14 cols=46 >$message</textarea></td>"; 
  }
  else if($page==2)
  {
        echo"<td ><textarea name='Message' id='message' rows=25 cols=116 >$message</textarea></td>"; 
  }




?>
