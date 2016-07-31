<?php
$PageSecurity = 20;
 include('includes/session.inc');
 $title = _('Contents of Email'); 
 include('includes/header.inc');
 
 
 if($_POST['merge'])
 {
    $existing_message=$_POST['Message'];                      //already in bio_incidents
    $currentdate=Date($_SESSION['DefaultDateFormat']);
    $currentdateSQL=FormatDateForSQL($currentdate); 
//    $existing_message2 =str_replace('\r\n','\n',$existing_message1); 
//    $existing_message =str_replace('\n','<br>',$existing_message2); 
    
    $mailaddress=$_POST['mailaddress'];  
    $mailno=$_POST['mailno']; 
    $custid=$_POST['custid']; 
    
      $sql_email="SELECT  bio_email.from_address,
                bio_email.from_name,
                bio_email.to_address,
                bio_email.maildate,
                bio_email.subject,
                bio_email.message
         FROM   bio_email WHERE bio_email.id=".$mailno;
    $result_email=DB_query($sql_email,$db);
    $myrow_email=DB_fetch_array($result_email);
    
    $from=$myrow_email['from_address'];
    $to=$myrow_email['to_address']; 
    $date=$myrow_email['maildate'];
    $title=$myrow_email['subject'];
    $email_msg1=$myrow_email['message'];
    $email_msg =str_replace("'"," ",$email_msg1);
    
$new_msg="---------
    From: ".$from."
    To: ".$to."
    Subject: ".$title."
    Date: ".$currentdate."
    Message: ".$email_msg;
    
    $merged_msg=$existing_message.$new_msg;
    
    $sql_merge="UPDATE bio_incidents SET email_message = '$merged_msg', lastactiondate='$currentdateSQL', emailstatus=3 WHERE cust_id = $custid";
    DB_query($sql_merge,$db);
    
    $sql_status="UPDATE bio_email SET status = 1 WHERE id = $mailno";
    DB_query($sql_status,$db);
    
    ?>
    
 <script>
      
     window.close();

 </script>
 
    <?php
    
 }
 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo "<table style='width:850px' ><tr>";
echo'<td valign=top>';    
echo"<fieldset style='width:700px;height:500px'; overflow:auto;'>";
echo"<legend><h3>Mail contents</h3></legend>";
echo"<table style='width:600px' border=0>";    
echo"<tr>"; 

if($_GET['custid']) 
{
    $custid=$_GET['custid'];
}  else
{
  $custid=$_POST['custid'];  
} 
if($_GET['mailid']) 
{
 $mailno=$_GET['mailid'];
}
else
{
   $mailno=$_POST['mailno'];  
}
 
 
  $sql="SELECT bio_incidents.email_message,
               bio_incident_cust.custmail 
        FROM   bio_incidents,bio_incident_cust 
        WHERE  bio_incidents.cust_id=bio_incident_cust.cust_id 
        AND    bio_incidents.cust_id=".$custid;
  $result=DB_query($sql,$db);  
  $myrow=DB_fetch_array($result);
  
  $message=$myrow['email_message'];
  $custmail=$myrow['custmail'];  
  
  echo"<input type=hidden name=mailaddress value='$custmail'>";
  echo"<input type=hidden name=mailno value='$mailno'>";
  echo"<input type=hidden name=custid value='$custid'>";
  
  echo"<tr><td width=15%>Message</td>";
  echo"<td width=85%><div style='height:330px;overflow:scroll'>".nl2br($message)."</div><textarea style='display:none' name='Message' id='message' rows=25 cols=116 >$message</textarea></td></tr>";
  echo"</tr>";
  
  echo"<tr><td></td><td>";
  echo"<input  type='submit' name='merge' id='merge' value='Merge current mail with existing mail'>"; 
  echo"</td></tr>";
  echo"</table>"; 
  
  
     
  echo"</td></tr>";  
  echo"</fieldset>"; 
  echo"</table>";
  echo"</form>"; 
    
?>
