<?php
  $PageSecurity = 80;
include('includes/session.inc');//
$title = _('Send Mail');
include('includes/header.inc');
include('includes/bio_greetingmsgs.php');
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Send Email</font></center>';
$leadid=$_GET['leadid'];
$_SESSION['leadid']==$leadid;
    
   
    
    
if(isset($_POST['SendMail'])){
    
    
    //$attach=$_POST['uploadedfile'];   
    
// Where the file is going to be placed 
$target_path = "uploads/emailAttachs/";
/* Add the original filename to our target path.  
Result is "uploads/filename.extension" */
if($_FILES['uploadedfile1']['name']!="")
{
    $target_path1 = $target_path . basename( $_FILES['uploadedfile1']['name']); 
    move_uploaded_file($_FILES['uploadedfile1']['tmp_name'], $target_path1);
    $attach1=$_FILES['uploadedfile1']['name'];  
    $filename1=explode(".",$attach1);
    $extension1=$filename1[1];
}
if($_FILES['uploadedfile2']['name']!="")
{
    $target_path2 = $target_path . basename( $_FILES['uploadedfile2']['name']); 
    move_uploaded_file($_FILES['uploadedfile2']['tmp_name'], $target_path2);
    $attach2=$_FILES['uploadedfile2']['name'];  
    $filename2=explode(".",$attach2);
    $extension2=$filename2[1];
}
if($_FILES['uploadedfile3']['name']!="")
{
    $target_path3 = $target_path . basename( $_FILES['uploadedfile3']['name']); 
    move_uploaded_file($_FILES['uploadedfile3']['tmp_name'], $target_path3);
    $attach3=$_FILES['uploadedfile3']['name'];  
    $filename3=explode(".",$attach3);
    $extension3=$filename3[1];
}




    

    
    $ticket_no=$_POST['TicketNo'];
    $currentdate=Date($_SESSION['DefaultDateFormat']);
    $currentdateSQL=FormatDateForSQL($currentdate);
    
    $to=$_POST['ToName'];
	$to1='biotechin@hotmail.com';
    $to2='biotechmails@biotechin.org'; 
    $subject =$_POST['Subject']; 
    
    $message1 =$_POST['Message'];
    
    $message2 =str_replace('\r\n','\n',$message1);
    
    $message3 =str_replace('\n','<br>',$message2);
    $from =$_POST['FromName'];
    $random_hash = md5(date('r', time()));
    $headers = "From:" . $from . "\nReply-To: ". $from;
//    $headers .= "\nContent-Type: text/html; charset=UTF-8";
    $headers .= "\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
    
if($_FILES['uploadedfile1']['name']!="")
{    
    $attachment1 = chunk_split(base64_encode(file_get_contents("uploads/emailAttachs/".$attach1))); 
}
if($_FILES['uploadedfile2']['name']!="")
{    
    $attachment2 = chunk_split(base64_encode(file_get_contents("uploads/emailAttachs/".$attach2))); 
}
if($_FILES['uploadedfile3']['name']!="")
{    
    $attachment3 = chunk_split(base64_encode(file_get_contents("uploads/emailAttachs/".$attach3))); 
}

if($attach1=="" && $attach2=="" && $attach3=="")
{
$message ="

--PHP-mixed-$random_hash
Content-Type: multipart/alternative; boundary=PHP-alt-$random_hash\n\n
--PHP-alt-$random_hash
Content-Type: text/html; charset=UTF-8\n\n
$message3
--PHP-alt-$random_hash--\n\n";  

}
else
{    
        
$message ="

--PHP-mixed-$random_hash
Content-Type: multipart/alternative; boundary=PHP-alt-$random_hash\n\n
--PHP-alt-$random_hash
Content-Type: text/html; charset=UTF-8\n\n
$message3
--PHP-alt-$random_hash--\n\n";  

if($attach1!="")
{
$message .="

--PHP-mixed-$random_hash
Content-Type: application/$extension1; name=$attach1
Content-Disposition: attachment; filename=$attach1
Content-Transfer-Encoding: base64\n\n
$attachment1 ";
 
} 
if($attach2!="")
{
$message .="

--PHP-mixed-$random_hash
Content-Type: application/$extension2; name=$attach2
Content-Disposition: attachment; filename=$attach2
Content-Transfer-Encoding: base64\n\n
$attachment2  ";
 
} 
if($attach3!="")
{
$message .="

--PHP-mixed-$random_hash
Content-Type: application/$extension3; name=$attach3
Content-Disposition: attachment; filename=$attach3
Content-Transfer-Encoding: base64\n\n
$attachment3 ";  
} 
$message .="
--PHP-mixed-$random_hash--\n\n"; 
}
 
mail($to,$subject,$message,$headers);
 


$sql_msg="SELECT email_message FROM bio_incidents
            WHERE ticketno=$ticket_no";
$result_msg=DB_query($sql_msg, $db);  
$row_msg=DB_fetch_array($result_msg);

$email_message=$row_msg['email_message'];
$email_message=str_replace("'","\'",$email_message);
$reply_message="
From: ".$from."
To: ".$to."
Subject: ".$subject."
Date: ".$currentdate."
Message: ".$_POST['Message'];
 $final_message=$email_message.$reply_message;

  $sql_update_msg="UPDATE bio_incidents SET
                        email_message='".$final_message."',
                        emailstatus=2,
                        lastactiondate='".$currentdateSQL."' WHERE ticketno=$ticket_no";
$result_update_msg=DB_query($sql_update_msg, $db); 
mail($to2,$subject,$message,$headers);  
  mail($to1,$subject,$message,$headers);    
/*echo $sql_leadtask="Update bio_leadtask set taskcompletedstatus=1, taskcompleteddate='".$currentdateSQL."',remarks='Greeting mail Send on $currentdateSQL' where taskid=18 and leadid='".$_SESSION['leadid']."'";
        $result25=DB_query($sql_leadtask, $db); 
     */
    
     ?>
      <script>
      
      //var lead=<?php //echo $lead_ID; ?>;
//      window.opener.location='bio_instTaskview.php?lead='+ lead;
window.close();

      </script>
      <?php
}


//$leadid=$_GET['leadid'];
$sql_area="SELECT bio_cust.`nationality` , bio_cust.`state`
FROM `bio_cust` , bio_leads
WHERE bio_leads.cust_id = bio_cust.cust_id
AND bio_leads.leadid =".$leadid;
           $result_area=DB_query($sql_area, $db);  
$row_area=DB_fetch_array($result_area);

   if ($row_area['nationality']==1 and $row_area['state']==14)
   {
       $area=1;
   }
   else if($row_area['nationality']==1 and $row_area['state']!=14)
   {
       $area=2;
   }
     else if($row_area['nationality']!=1)
   {
        $area=3;
   }

      /* 0=local  1=National 2=international */
   
$sql_enq="SELECT enqtypeid FROM bio_leads
            WHERE leadid=$leadid";
$result_enq=DB_query($sql_enq, $db);  
$row_enq=DB_fetch_array($result_enq);
$enqtype=$row_enq['enqtypeid'];



$sql1="SELECT 
                   bio_incident_cust.cust_id,
                   bio_incidents.ticketno,
                   bio_incident_cust.custname,
                   bio_incident_cust.custmail,
                   bio_incidents.title,
                   bio_incidents.handling_officer,
                   bio_incidents.description
         FROM      bio_incidents,bio_incident_cust
         WHERE     bio_incidents.cust_id=bio_incident_cust.cust_id  
         AND       bio_incidents.leadid=$leadid";
  
$result1=DB_query($sql1, $db);  
$row1=DB_fetch_array($result1);
$ticketno=$row1['ticketno'];

/*$sql_emp="SELECT bio_emp.empname
                      FROM bio_emp,bio_teammembers
                     WHERE bio_teammembers.empid=bio_emp.empid 
                       AND bio_teammembers.teamid='".$row1['handling_officer']."'";*/
$sql_emp="SELECT
    `bio_emp`.`empname`,
    `bio_emp`.`skype_id`,
     www_users.phone
    
FROM
    `www_users`
    INNER JOIN `bio_emp` 
        ON (`www_users`.`empid` = `bio_emp`.`empid`)
        WHERE www_users.userid='".$_SESSION['UserID']."'";
$result_emp=DB_query($sql_emp,$db);
$myrow_emp=DB_fetch_array($result_emp);
$empname=$myrow_emp['empname'];
$from = " info@biotechin.org";
$custname=$row1['custname'];
$custmail_id=$row1['custmail'];
$skype_id= $myrow_emp['skype_id'];
 $phone=$myrow_emp['phone'];

$subject="[".$row1['ticketno']."]".":"."Greeting Mail from Biotech";

$message=getMessage($leadid,$area,$enqtype,$custname,$empname,$db,$skype_id,$phone);

echo"<div id=fullbody>";
echo "<form method='post' enctype='multipart/form-data' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<table style="width:70%"><tr><td>';
echo"<div id=fullpanel>"; 
echo"<fieldset style='width:900px;'>"; 
echo"<legend>Compose Mail</legend>";
echo"</legend>";
echo"<table width=100% border=0>";

echo"<tr><td width=15%>From</td>";
echo"<td width=85%><input style='width:96%' type='text' name='FromName' readonly id='fromname' value='$from'></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=15%>To</td>";
echo"<td width=85%><input style='width:96%' type='text' name='ToName' id='toname' value='$custmail_id'></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=15%>Subject</td>";
echo"<td width=85%><input style='width:96%' type='text' name='Subject' id='subject' value='$subject'></td></tr>";
echo"<tr></tr>";     


echo"<tr><td width=15%></td>";
echo"<td width=85%><input name='uploadedfile1' type='file' /></td><tr>";
echo"<tr></tr>";  
echo"<tr><td width=15%></td>";
echo"<td width=85%><input name='uploadedfile2' type='file' /></td><tr>";
echo"<tr></tr>";  
echo"<tr><td width=15%></td>";
echo"<td width=85%><input name='uploadedfile3' type='file' /></td><tr>";
echo"<tr></tr>";  

echo"<tr><td width=50%>Mail Body Type</td>";
echo"<td><select name='mailbody' id='mailbody' style='width:150px' onchange='messagefile()'>";
$sql="SELECT id,body_type,`message` FROM bio_mailbody 
        where bio_mailbody.enq_type in( SELECT `enqtypeid` FROM `bio_leads` WHERE `leadid`=$leadid)";
$result=DB_query($sql,$db);

  echo '<option value=></option>';
  while($myrow1=DB_fetch_array($result))
  {  
    // $message= $myrow1['message'];
  if ($myrow1['id']==$_POST['mailbody'])  
    {   
    echo '<option selected value="';
    }
     else 
    {
echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['body_type'];
    echo '</option>';
    
   }   
echo"</select></td></tr>";

echo"<tr><td width=15%>Message</td>";
echo"<td width=85% id='messageview'><textarea name='Message' id='message' rows=25 cols=116 >$message</textarea></td></tr>";
echo"<tr></tr>";

echo"<input  type='hidden' name='TicketNo' id='ticketno' value='$ticketno'>";
   
echo"<tr><td width=15%></td>";
echo"<td width=85%><input  type='submit' name='SendMail' id='send' value=Send></td></tr>";
echo"<tr></tr>";
  
 
echo"</table>";
echo"</fieldset>";
echo"</div>";   
echo"</td></tr></table>";

echo "</form>"; 
echo"</div>";
?>
<script type="text/javascript">   

function messagefile()
{
    page=2;
    str=document.getElementById("mailbody").value;

    if (str=="")
  {
  //document.getElementById("messageview").innerHTML="";
  return;
  }
 // show_progressbar("messageview");
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
    //  alert(str);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("messageview").innerHTML=xmlhttp.responseText;
//getservice(str);

    }
  }
xmlhttp.open("GET","bio_mailmessage_ajax.php?enqid=" + str+"&page="+page,true);
xmlhttp.send();


}

</script>
