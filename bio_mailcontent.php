<?php
 $PageSecurity = 20;
 include('includes/session.inc');
 $title = _('Contents of Email'); 
 $pagetype=3; 
 include('includes/header.inc');
//==========================================================================     
echo "<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table><tr>';
echo'<td valign=top>';
echo"<fieldset style='width:800px; overflow:auto;'>";
echo"<legend><h3>Mail contents</h3></legend>";
echo"<table border=1 width=750px>";
//echo"<tr><td>Task</td><td>Office</td><td>Team</td></tr>";
//echo"<tr>";
//echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
//echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';

 $id=$_GET['mailid'];   
 $sql="SELECT bio_email.from_address,
              bio_email.to_address,
              bio_email.maildate,
              bio_email.subject,
              bio_email.message
         FROM bio_email WHERE bio_email.id=".$id;
 $result=DB_query($sql,$db);  
 $myrow_email=DB_fetch_array($result); 
 $from=$myrow_email['from_address'];
 $to=$myrow_email['to_address'];
 $date=$myrow_email['maildate'];
 $message=$myrow_email['message'];
 $message=nl2br($message);
 $message=str_replace("<br /><br />","<br />",$message);
 //$message=str_replace("<br />\n<br />\n","",$message);

 $subject=$myrow_email['subject'];
 // echo'<tr>'; 
//  echo'<br><th>From</th><td><input type=hidden name=from id=From value="'.$from.'">'.$from.'</td>'; 
//  echo "</tr>"; 
//  echo'<tr><th>To</th><td><input type=hidden name=to id=To value="'.$to.'">'.$to.'</td>'; 
//  echo "</tr>";  
//  echo'<tr><th>Date</th><td><input type=hidden name=date id=Date value="'.$date.'">'.$date.'</td>';         
//  echo "</tr>";              
//  echo'<tr><br><th><b>Subject</th><td><input type=hidden name=subject id=Subject value="'.$subject.'</td>'; 
//  echo "</tr>";  
//  echo'<tr><br><th><b>Message</th><td><input type=hidden name=message id=Message value="'.nl2br($message).'</td>';         
//  echo "</tr>";   

  echo'<tr>'; 
  echo'<br><th>From</th><td>'.$from.'</td>'; 
  echo "</tr>"; 
  echo'<tr><th>To</th><td>'.$to.'</td>';
  echo "</tr>";  
  echo'<tr><th>Date</th><td>'.$date.'</td>';         
  echo "</tr>";              
  echo'<tr><br><th><b>Subject</th><td>'.$subject.'</td>'; 
  echo "</tr>";  
  echo'<tr><br><th><b>Message</th><td>'.$message.'</td>';
  echo "</tr>";   

  echo '</tr>'; 
  echo"</table>";
  echo"</fieldset>";                
?>
