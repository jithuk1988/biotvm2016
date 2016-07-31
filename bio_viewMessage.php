<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Email Message');  
//include('includes/header.inc');

$ticketno=$_SESSION['email'];

 $sql_e='SELECT CONCAT( "From: ", from_name, "</br>", "Date: ", maildate, "</br>", "Subject: ", `subject` , "</br>", "Email Message: ", message ) AS mail
FROM bio_email
WHERE from_address = "'.$ticketno.'"
ORDER BY id DESC
LIMIT 1' ;
              $result=DB_query($sql_e,$db);
if(DB_num_rows($result)==0)
{
$ticketno="%".$ticketno."%";
$sql_e='SELECT CONCAT( "From: ", from_name, "</br>", "Date: ", maildate, "</br>", "Subject: ", `subject` , "</br>", "Email Message: ", message ) AS mail
FROM bio_email
 WHERE message LIKE "'.$ticketno.'"
ORDER BY id DESC
LIMIT 1' ;
}
        $result=DB_query($sql_e,$db);
 $myrow6=DB_fetch_array($result); 
 // $ticketno=$myrow6['ticketno'];
  $mail=$myrow6['mail'];    
echo'<div>';  
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
 
 echo"<input type='hidden' name='leadid' id='leadid' value='$leadid'>";  
echo"<table style='border:1px solid #F0F0F0;width:100%'><tr><td>";

      echo"<fieldset style='width:630px' scrollbar=yes><legend>Eamil Message</legend>";  
      echo"<div style='height:330px;overflow:scroll'>";
               $pos = strpos($mail,"<td>");
                $htm=   strpos($mail,"<html>");
               if($pos==true or $htm==true )
               {
     echo $mail;     
               }
               else
               {
                     echo nl2br($mail);
               }
  //  echo"<tr><td></td><td align=right><input type='submit' name='submit' id='submit' value='Submit' onclick='if(validation()==1)return false;;'></td>";  
    echo"</div>";
            echo"<center><input type='button' name='close' id='close' value='Close It' onclick='window.close()'/><center>"; 
    echo"</fieldset>";
echo"</td></tr>";


echo"</table>";  
 
echo"</form>";
echo'</div>';       
 
?>
<script type="text/javascript"> 

function validation()
{
    var f=0;
    var p=0;
    if(f==0){f=common_error('remarks','Please enter any remarks');  if(f==1){return f; }  }
}

</script>