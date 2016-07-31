<?php
  $PageSecurity = 80;
include('includes/session.inc');
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
$fsid=  $_GET['fsid'];  



    
   
    
    
if(isset($_POST['SendMail'])){
    
   $leadid=$_POST['leadid'];  
      $fsid=$_POST['fsid'];    
    $to=$_POST['ToName'];
	$to1='biotechin@hotmail.com';
    $to2='biotechmails@biotechin.org';
    $subject =$_POST['Subject']; 
    
    $message1 =$_POST['Message'];
    
    $message2 =str_replace('\r\n','\n',$message1);
    
    $message3 =str_replace('\n','<br>',$message2);
    $from =$_POST['FromName'];
    
    $filename=$_POST['filename'];
    
    $random_hash = md5(date('r', time()));
    $headers = "From:" . $from . "\nReply-To: ". $from;
//    $headers .= "\nContent-Type: text/html; charset=UTF-8";
    $headers .= "\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
    
 
    $file = file_get_contents('fproposals/'.$filename, true); 
    $attachment1 = chunk_split(base64_encode($file)); //
    
 $message ="

--PHP-mixed-$random_hash
Content-Type: multipart/alternative; boundary=PHP-alt-$random_hash\n\n
--PHP-alt-$random_hash
Content-Type: text/html; charset=UTF-8\n\n
$message3
--PHP-alt-$random_hash--\n\n";     

$message .=" 
--PHP-mixed-$random_hash
Content-Type: application/pdf; name=$filename
Content-Disposition: attachment; filename=$filename
Content-Transfer-Encoding: base64\n\n
$attachment1   
 

--PHP-mixed-$random_hash--\n\n"; 
    
mail($to,$subject,$message,$headers);
mail($to2,$subject,$message,$headers); 
mail($to1,$subject,$message,$headers); 

$sql88 ="SELECT cust_id FROM bio_leads WHERE bio_leads.leadid=".$leadid;
$rest=DB_query($sql88,$db);
    $row_cust=DB_fetch_array($rest);
    $cust=$row_cust[0];   
$sql_update1="UPDATE bio_cust SET custmail='$to' WHERE bio_cust.cust_id=".$cust;
DB_query($sql_update1,$db);    
                            
$sql_update2="UPDATE bio_fsproposal SET emailDate='".date('Y-m-d')."', status=4, emailstatus=1 WHERE fs_propono=".$fsid."";
DB_query($sql_update2,$db);

    
     ?>
      <script>
      
      //var lead=<?php //echo $lead_ID; ?>;
//      window.opener.location='bio_instTaskview.php?lead='+ lead;
window.close();

      </script>
      <?php
}

/*
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
/*       
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

    $sql_emp="SELECT bio_emp.empname
                          FROM bio_emp,bio_teammembers
                         WHERE bio_teammembers.empid=bio_emp.empid 
                           AND bio_teammembers.teamid='".$row1['handling_officer']."'";
    $result_emp=DB_query($sql_emp,$db);
    $myrow_emp=DB_fetch_array($result_emp);
    $empname=$myrow_emp['empname'];
    $from = " info@biotechin.org";
    $custname=$row1['custname'];
    $custmail_id=$row1['custmail'];

    $subject="[".$row1['ticketno']."]".":"."Greeting Mail from Biotech";

    $message=getMessage($leadid,$area,$enqtype,$custname,$empname,$db);
*/       
                        
$sql_cust="SELECT custmail FROM bio_cust,bio_leads WHERE bio_leads.cust_id=bio_cust.cust_id AND bio_leads.leadid=$leadid";
$result_cust=DB_query($sql_cust,$db);
$row_cust=DB_fetch_array($result_cust);


$from = "info@biotechin.org"; 
$custmail_id=$row_cust['custmail'];
$subject="BIOTECH - Feasibility study Payment Request";


    
                                       
echo"<div id=fullbody>";
echo "<form method='post' enctype='multipart/form-data' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<table style="width:70%"><tr><td>';
echo"<div id=fullpanel>"; 
echo"<fieldset style='width:400px;'>"; 
echo"<legend>Compose Mail</legend>";
echo"</legend>";
echo"<table width=100% border=0>";

echo"<tr><td width=15%>From</td>";
echo"<td width=85%><input style='width:300px' type='text' name='FromName' id='fromname' value='$from'></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=15%>To</td>";
echo"<td width=85%><input style='width:300px' type='text' name='ToName' id='toname' value='$custmail_id'></td></tr>";
echo"<tr></tr>";

echo"<tr><td width=15%>Subject</td>";
echo"<td width=85%><input style='width:300px' type='text' name='Subject' id='subject' value='$subject'></td></tr>";
echo"<tr></tr>"; 

echo"<div id=attach></div>";   

// $fileatt      = $_SERVER['DOCUMENT_ROOT']."/fproposal/Feasibility_Request_2012-08-03_8.pdf";
// $fileatt_type = mime_content_type($fileatt);
// $fileatt_name = basename($fileatt);  

$filename=$_GET['filename'];  

         
echo"<tr><td></td><td><b><i><input type=hidden name=filename id=filename value='$filename'>$filename</i></b></td></tr>";

//$fullPath = $path.$_GET['download_file'];

      
//echo"<tr><td width=15%></td>";            
//$path=$_SERVER['DOCUMENT_ROOT']."/fproposal/Feasibility_Request_2012-08-03_8.pdf";    
//echo"<td width=85%>$path</td>";


//echo"<td width=85%><input type='file' name='uploadedfile1[]' id='uploadedfile1[]' value='$fileatt' class='multi'/></td><tr>";
echo"<tr></tr>"; 

/*
echo"<tr><td width=15%></td>";
echo"<td width=85%><input name='uploadedfile1' type='file' /></td><tr>";
echo"<tr></tr>";  
echo"<tr><td width=15%></td>";
echo"<td width=85%><input name='uploadedfile2' type='file' /></td><tr>";
echo"<tr></tr>";  
echo"<tr><td width=15%></td>";
echo"<td width=85%><input name='uploadedfile3' type='file' /></td><tr>";
echo"<tr></tr>";  
                 */
                 
echo"<tr><td width=50%>Mail Body Type</td>";
echo"<td><select name='mailbody' id='mailbody' style='width:150px' onchange='messagefile()'>";
$sql="SELECT id,body_type,`message` FROM bio_mailbody 
        where bio_mailbody.enq_type in(SELECT `enqtypeid` FROM `bio_leads` WHERE `leadid`=$leadid) ";
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


echo"<tr ><td width=15%>Message</td>";
echo"<td width=85% id='messageview'><textarea name='Message' id='message' rows=14 cols=46 >$message</textarea></td></tr>";

echo"<tr></tr>";


   
echo"<tr><td width=15%></td>";
echo"<td width=85% id=gg><input  type='submit' name='SendMail' id='send' value=Send></td></tr>";
echo"<tr></tr>";

echo"<input type=hidden name=leadid value=$leadid>";  
  echo"<input type=hidden name=fsid value=$fsid>";  

 
echo"</table>";
echo"</fieldset>";
echo"</div>";   
echo"</td></tr></table>";

echo "</form>"; 
echo"</div>";



?>

<script type="text/javascript">   
   
$(document).ready(function() {  // wait for document to load
$("uploadedfile1").click(function(){
$('#gg').hide(); 
});
});

function messagefile()
{
    page=1;
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