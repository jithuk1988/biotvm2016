<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Mails');
include('includes/header.inc');
$collectedBy=$_SESSION['UserID'];
// $host='{tsunamisoftware.co.in/notls/imap4}INBOX'; //Host to connect
// $user='support@support.tsunamisoftware.co.in';
// $pass='tsunami123';
// $from='support@support.tsunamisoftware.co.in';


$host='{localhost/notls/imap4}INBOX'; //Host to connect
$user='info.biotechin';
$pass='bio2345tech';
$from='info@biotechin.org'; //Mail to send from

/*if(isset($_GET['del']))
{
 $id=$_GET['del'];
 $sql1="update bio_email set status=2 WHERE bio_email.id=".$id;
 $result=DB_query($sql1,$db);
 echo 'Email id '.$id.' deleted.<br />';
}*/
echo "<div id=\"showmsg\"></div>";

$mail=@imap_open($host,$user,$pass) or die("Can't connect: " . imap_last_error());



if(isset($_GET['see']) && $_GET['see']!="")
{
    $number=$_REQUEST['see'];

    //echo "<pre>";
    //echo nl2br(strip_tags(imap_body($mail,$number)));
    //echo "</pre><p>\n\n";
    $chead=imap_headerinfo($mail,$number);
    $from = $chead->fromaddress;
    $fromaddress = $chead->from[0]->mailbox . "@" . $chead->from[0]->host;
    $to = $chead->toaddress;
    $toaddress = $chead->to[0]->mailbox . "@" . $chead->to[0]->host;
    $date = $chead->date;
    echo 'From: '.$from.'&lt;'.$fromaddress.'&gt;</br>';
    if ($to!=$toaddress)  {
    echo 'To: '.$to.'&lt;'.$toaddress.'&gt;'.'</br>';
    } else {
      echo 'To: '.$toaddress.'</br>';
    }
    echo "Date: ".$date."</br>";
    echo "------------------------------------------------------------------</br>";
    echo nl2br(imap_fetchbody($mail,$number,1));
    echo "</br>";
    echo "------------------------------------------------------------------</br>";
    echo "<a href='javascript:history.back()'>Back</a>";
    echo "<br><a href='?delete=$number'>Delete</a>";

}
else
{
    if (isset($_GET['create']) && $_GET['create']=="new") {
        if($_POST['send_m']) {
            $to=$_POST['to'];
            $subject=$_POST['title'];
            $message=$_POST['mail'];

            imap_mail($to,$subject,$message,"From: $from");
            echo 'Message sent</br>';
            echo "<a href='bio_email.php'>Back</a></br>";

        }
        ?>
<form method=POST >
To: <input type="text" name="to"><br>
Title:<input type="text" name="title"><p>
Mail:<br>
<textarea name='mail'>
</textarea><p>
<input type="submit" name='send_m'  value='Send'>
</form>
    <?php
    }
    else 
    
    {
      //Read mail
        $mails=imap_num_msg($mail);
        
        if($mails==0) {
            echo "<i>No new mail</i>";
        } else {
            echo $mails." new mail<p>";
        

        $move="INBOX.Archive";
            for($i=1;$i<=$mails;$i++) {
                $chead=imap_headerinfo($mail,$i);
                $mid=ltrim($chead->Msgno);
                $from_cust = $chead->fromaddress;
                $fromaddress_cust = $chead->from[0]->mailbox . "@" . $chead->from[0]->host;
                $to = $chead->toaddress;
                $date = $chead->date;

//                $message=preg_replace(array('/--.*/','/Content-Type:.*/'),'',nl2br(imap_fetchbody($mail,$mid,1)));
//                $message = str_replace("=B4","'", strip_tags($message));
//                $message = str_replace("'","\'", strip_tags($message));

                  $messageNumber = $mid;
                  $structure = imap_fetchstructure($mail, $messageNumber);
                  $flattenedParts = flattenParts($structure->parts);
			//print_r($flattenedParts);
                  $msg=array('plain' =>'', 'html'=>"");
                  $filename='';
                  $attachment='';
		    $message='';
                  $fname=array();
                  $savepath="attach/";
                  foreach($flattenedParts as $partNumber => $part) {
                     switch($part->type) {

                     case 0:
			// the HTML or plain text part of the email
			$message = getPart($mail, $messageNumber, $partNumber, $part->encoding);
			// now do something with the message, e.g. render it
			//print_r($message);
           // echo $message;
   echo  $partNumber;
//          if ($partNumber == 12)
//           {
//               echo "dddddddddd";
//           } 
			 if ($partNumber == 2) {
                        $msg['html'] = $message;
			//echo '<br />html - '.$msg['html'];
                        }
                     //   if ($partNumber == 1.1) {
                     else{
                        $msg['plain']=$message;
                   //   '<br />plain - '.$msg['plain']; 
                        }
                          
                        break;
                     case 1:
			// multi-part headers, can ignore

		        break;
		     case 2:
			// attached message headers, can ignore
		        break;

                     case 3: // application
		     case 4: // audio
		     case 5: // image
		     case 6: // video
		     case 7: // other
			$filename = getFilenameFromPart($part);
			if($filename) {
				// it's an attachment
				//$savepath="attach\\";
				$attachment = getPart($mail, $messageNumber, $partNumber, $part->encoding);
				// now do something with the attachment, e.g. save it somewhere
 				$fname[]=$filename;
			}
			else {
				// don't know what it is
                         }
		break;

	   }
 
      if (($msg['html']!='') || ($msg['plain']!=''))
        {
         if ($msg['plain']) {
            "plm.........";
         $message=nl2br($msg['plain']);
        
         } else  {
             
                      $message=$msg['html']."html............"; 
         }
        }
        $message=str_replace("'","-",$message);    

                //echo "<a href='?see=$mid'>";

                 $subject = $chead->subject;
                //echo "</a>";
                //echo "\n";
               //    
                        // $subject=cleanHTML($subject);
 
               $time= substr($date, -14, -7);
               $convertdate = substr($date, 5, 11);
               $convertdate1=explode(" ",$convertdate);
               $conD= $convertdate1[0];
               $conM= $convertdate1[1];
               $conY= $convertdate1[2];

             if($conM=='Jan'){$conM='01';} if($conM=='Feb'){$conM='02';} if($conM=='Mar'){$conM='03';} if($conM=='Apr'){$conM='04';}
             if($conM=='May'){$conM='05';} if($conM=='Jun'){$conM='06';} if($conM=='Jul'){$conM='07';} if($conM=='Aug'){$conM='08';}
             if($conM=='Sep'){$conM='09';} if($conM=='Oct'){$conM='10';} if($conM=='Nov'){$conM='11';} if($conM=='Dec'){$conM='12';}

               $condate=$conY."-".$conM."-".$conD." ".$time;
                $createdate=date("Y-m-d");

//                $all_files=array();
//               $all_files=0;
//               $attached_filecount=0;


//               $all_files=extract_attachments($mail, $mid);
//               $attached_filecount=count($all_files);
//               print_r($all_files);
            }   
              $subject=str_replace("'","-",$subject); 
$message=str_replace("'"," ",$message); 	
$to=str_replace("'"," ",$to); 
$fromaddress_cust=str_replace("'"," ",$fromaddress_cust); 		
$from_cust=str_replace("'"," ",$from_cust);         		  
              $insert_email="INSERT INTO bio_email (from_name,
                                                     from_address,
                                                     to_address,
                                                     maildate,
                                                     date,
                                                     subject,
                                                     message,
                                                     status,
                                                     createdby,
                                                     createddate)
                                              VALUES('$from_cust',
                                                     '$fromaddress_cust',
                                                     '$to',
                                                     '$date',
                                                     '$condate',
                                                     '$subject',
                                                     '$message',
                                                     0,
                                                     '$collectedBy',
                                                     '$createdate'
                                                     )";
               $result_email=DB_query($insert_email,$db);
               if (!$result_email) {echo "Error inserting email data";}
               $lastmailid=DB_Last_Insert_ID($Conn,'bio_email','id');
               //echo 'last email id '.$lastmailid;
               if($result_email){
                     if(count($fname)>0) {
                         for($j=0;$j<count($fname);$j++) {
                         $filename=$fname[$j];
                         $filename=$savepath.$filename;
                         $filename=new_name_if_fileexists($filename);
                         file_put_contents($filename, $attachment);
                         echo '<br />File '. $filename . ' saved';
                  $sql_attachment="INSERT INTO bio_email_attachments(id, attachment_path)
                                               VALUES('$lastmailid', '".$filename."')";
                         $result_attachment=DB_query($sql_attachment,$db);
                         }
                    }
               }
            imap_mail_move($mail, $mid, $move);
        }
        imap_expunge($mail);
        echo "<p><a href='bio_email.php'>GoTo Inbox</a><p>";
    }
    }
    imap_close($mail);
}

function cleanHTML($html) {
    
$htm_text = nl2br($htm_text);  
$htm_text = str_replace("&nbsp;","",$htm_text);
$htm_text = strip_tags($htm_text,'<br><b><i><a>');
$htm_text=str_replace("<br />
<br />","<br />",$htm_text);
$htm_text=str_replace("<br />
<br />","<br />",$htm_text);
$htm_text=str_replace("<br />
<br />","<br />",$htm_text);
//$htm_text=str_replace("'"," ",$htm_text);
return $html;
}

function extract_attachments($connection, $message_number) {
    $savepath = 'attach/';
    $attachments = array();
    $allfiles=array();
    $structure = imap_fetchstructure($connection, $message_number);

    if(isset($structure->parts) && count($structure->parts)) {

        for($i = 0; $i < count($structure->parts); $i++) {

            $attachments[$i] = array (
                'is_attachment' => false,
                'filename' => '',
                'name' => '',
                'attachment' => '' );

            if($structure->parts[$i]->ifdparameters) {
                foreach($structure->parts[$i]->dparameters as $object) {
                    if(strtolower($object->attribute) == 'filename') {
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['filename'] = $object->value;
                    }
                }
            }

            if($structure->parts[$i]->ifparameters) {
                foreach($structure->parts[$i]->parameters as $object) {
                    if(strtolower($object->attribute) == 'name') {
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['name'] = $object->value;
                    }
                }
            }

            if($attachments[$i]['is_attachment']) {
                $file = $savepath . $attachments[$i]['filename'];
                $attachments[$i]['attachment'] = imap_fetchbody($connection, $message_number, $i+1);

                if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                    $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                }
                elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                    $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                }
                $file=new_name_if_fileexists($file);
//                echo 'file is '.$file.'<br />';
                $allfiles[]=$file;

                file_put_contents($file, $attachments[$i]['attachment']);

            }


        }

    }

//    return $attachments;
return $allfiles;
}

function new_name_if_fileexists($file) {
   if (file_exists($file)) {
      $parts = pathinfo($file);
      $base_fn = $parts ['basename'];
      $fn_ext = $parts ['extension'];
      $file_path = $parts ['dirname'];
      $fn1 = explode(".",$base_fn);
      $fn2 = $fn1[0];
      if ($fn2[strlen($fn2)-1]==")") {
         $fn3=explode("(",$fn2);
         $fn=$fn3[0];
         $fnum1=explode(")",$fn3[1]);
         $fnum=$fnum1[0];
         $fn4=$file_path.'/'.$fn."(".($fnum+1).").".$fn_ext;
         if (file_exists($fn4)) {
           $file = new_name_if_fileexists($fn4);
           return $file;
           }
           else {
             $file=$fn4;
             return  $file;
           }
         }
         else {
           $fn5=$file_path.'/'.$fn2.'(1).'.$fn_ext;
           if (file_exists($fn5)) {
             $file=new_name_if_fileexists($fn5);
             return $file;
             }
           else {
             $file=$fn5;
             return  $file;
             }
         }
      }
   return $file;
}

function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true) {

	    foreach($messageParts as $part) {
	        $flattenedParts[$prefix.$index] = $part;
	        if(isset($part->parts)) {
	            if($part->type == 2) {
	                $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
	            }
	            elseif($fullPrefix) {
	                $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
	            }
	            else {
	                $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix);
	            }
	            unset($flattenedParts[$prefix.$index]->parts);
	        }
	        $index++;
	    }

	    return $flattenedParts;

}

function getPart($connection, $messageNumber, $partNumber, $encoding) {

	    $data = imap_fetchbody($connection, $messageNumber, $partNumber);
	    switch($encoding) {
	        case 0: return $data; // 7BIT
	        case 1: return $data; // 8BIT
	        case 2: return $data; // BINARY
	        case 3: return base64_decode($data); // BASE64
	        case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
	        case 5: return $data; // OTHER
	    }


}

function getFilenameFromPart($part) {

	    $filename = '';

	    if($part->ifdparameters) {
	        foreach($part->dparameters as $object) {
	            if(strtolower($object->attribute) == 'filename') {
	                $filename = $object->value;
	            }
	        }
	    }

	    if(!$filename && $part->ifparameters) {
	        foreach($part->parameters as $object) {
	            if(strtolower($object->attribute) == 'name') {
	                $filename = $object->value;
	            }
	        }
	    }

	    return $filename;

}

//include('bio_emaillist.php');


 if (isset($_POST['submit'])){  
                    //echo $_POST['mainmailcategory'];echo $_POST['submailcategory'];
           if ($_POST['mainmailcategory']==5 AND $_POST['submailcategory']==1){                     
      if($_POST['landline']==""){ $_POST['landline']=""; }    
      if($_POST['phno']==""){  $_POST['phno']=""; }    
      if($_POST['email']==""){ $_POST['email']="";   }     
      if($_POST['Houseno']==""){$_POST['Houseno']="";}    
      if($_POST['HouseName']==""){$_POST['HouseName']="";}
      if($_POST['Area1']==""){$_POST['Area1']=" ";} 
      if($_POST['Area2']==""){$_POST['Area2']="";} 
      if($_POST['Pin']==""){$_POST['Pin']="";}
      if($_POST['country']==""){$_POST['country']="";}    
      if($_POST['State']==""){$_POST['State']=" ";}
      if($_POST['District']==0){$_POST['District']="";}   
      if($_POST['taluk']==""){$_POST['taluk']="";} 
      if($_POST['lsgType']==""){$_POST['lsgType']="";}
      if($_POST['lsgName']==""){$_POST['lsgName']="";}    
      if($_POST['gramaPanchayath']==""){$_POST['gramaPanchayath']="";}
      if($_POST['lsgWard']==""){$_POST['lsgWard']="";}  
      if($_POST['village']==""){$_POST['village']="";}
      
 $sql = "INSERT INTO bio_incident_cust(custname,
                                       landline,
                                       custphone,
                                       custmail,
                                       houseno,
                                       housename,
                                       area1,
                                       area2,
                                       pin,
                                       nationality,
                                       state,
                                       district,
                                       taluk,
                                       LSG_type,
                                       LSG_name,
                                       block_name,
                                       LSG_ward, 
                                       village)
                    VALUES ('" . $_POST['custname'] . "',
                            '".$_POST['landline']."',
                            '" . $_POST['phno'] . "',
                            '" . $_POST['email'] ."',
                            '" . $_POST['Houseno'] . "',
                            '" . $_POST['HouseName'] . "',
                            '" . $_POST['Area1'] . "',
                            '" . $_POST['Area2'] . "',
                            '" . $_POST['Pin'] . "',
                            '" . $_POST['country'] . "',
                            '" . $_POST['State'] . "',
                            '" . $_POST['District'] . "',
                            '".$_POST['taluk']."',
                            '".$_POST['lsgType']."',
                            '".$_POST['lsgName']."', 
                            '".$_POST['gramaPanchayath']."', 
                            '".$_POST['lsgWard']."',
                             '".$_POST['village']."')";                                           
       $result = DB_query($sql,$db);
       
               $cust_id=DB_Last_Insert_ID($Conn,'bio_incident_cust','cust_id');
              $duedate='0000-00-00';
                       if($_POST['District']==""){
                                   $officer=1;   
                                              } 
                           else{                      
$sql2="SELECT *  FROM 
              bio_incidenthandlingoffcr 
              WHERE bio_incidenthandlingoffcr.types=".$_POST['type']."
              AND   bio_incidenthandlingoffcr.nationality=".$_POST['country']."
              AND   bio_incidenthandlingoffcr.state=".$_POST['State']."
              AND   bio_incidenthandlingoffcr.district=".$_POST['District']."  ";
    
  $result2=DB_query($sql2, $db);
  $count=DB_num_rows($result2); 
  $row2=DB_fetch_array($result2) ;   
  $dept=$row2['department'];
  if($count>0){
$officer=$row2['officer'];
  //$dept=$row2['department']; 
  }else{
      $officer=1;   
  }               
 // $dept=$row2['department']; 
  
                       }    
  $sql2="SELECT bio_teammembers.empid,bio_teammembers.teamid,bio_emp.empid,bio_emp.email
         FROM   bio_teammembers,bio_emp
         WHERE  bio_teammembers.teamid=$officer";
  $result2=DB_query($sql2, $db);
  $row2=DB_fetch_array($result2) ;
  $emails=$row2['email']; 
    
     if(isset($emails))    {
    $title1=$_POST['title'];       
    $description=$_POST['description'];
    $to = $emails;
    $subject1="["."C".$ticket."]".":";
    $subject2 = $title1;
    $subject=$subject1.$subject2;
    $priority1=$_POST['priority'];
        $message = " Sir,

           An Incident is receieved with the following details.
                From: ".$email."
                Subject: ".$subject." 
                Content:".$description.";
                Priority: ".$priority2."
                
                
 Biotech Helpdesk"; 
$from = "info@biotechin.org";
$headers = "From:" . $from;

//mail($to,$subject,$message,$headers);
    
     } 
    
             if($_POST['leadid']==""){ $_POST['leadid']=0; }
             if($_POST['orderid']==""){ $_POST['orderid']=0; }  
    $createdate=date("Y-m-d"); 
    $create_date= $_POST['date'];
    
    $from='From:'.$_POST['email'].'          ';
  $title2='Subject:'.$_POST['title'].'           ';  
  $content='Body:'.$_POST['message'];  
 $mail_message= $from.$title2.$content;        
               
 $sql1 = "INSERT INTO bio_incidents(cust_id,
                                    type,
                                    source,
                                    title,
                                    enqtypeid,
                                    description,
                                    priority,
                                    expected_duedate,
                                    handling_officer,
                                    leadid,
                                    orderno,
                                    createdon,
                                    createdby,
                                    status,
                                    emailsource,
                                    email_message,
                                    mainmailcategory,
                                    submailcategory,
                                    emailtype,emailstatus)
                     VALUES ('" . $cust_id . "',
                             '" . $_POST['type'] . "',
                                     2,
                             '" . $_POST['title'] . "',
                             '" . $_POST['enquiry'] . "',
                             '" . $_POST['description'] . "',
                             '" . $_POST['priority'] . "',
                             '" . $duedate . "',
                             '" . $officer ."',
                             " . $_POST['leadid'] . ",
                             " . $_POST['orderid'] .",
                             '" . $create_date . "',
                             '" . $collectedBy . "',
                                      1,
                             '" .  $_POST['emailsource']. "', 
                             '" .  $mail_message. "',
                             '" . $_POST['mainmailcategory'] . "',
                             '" . $_POST['submailcategory'] . "',
                             '" . $_POST['emailtype'] . "',1)";                                           
       $result1 = DB_query($sql1,$db); 
        $ticket=DB_Last_Insert_ID($Conn,'bio_incidents','ticketno'); 
       $msg1= 'Complaint has been created succesfully. Your Ticket no is <b>'.$ticket.'</b>';      
       prnMsg($msg1,'success');   
        
      
   
  //$sql5="SELECT *  FROM   bio_dept  WHERE  bio_dept.deptid=$dept";
//    
//  $result5=DB_query($sql5, $db);
//  $row5=DB_fetch_array($result5) ;
//  $deptname=$row5['deptname'];  
   
    $title1=$_POST['title']; 
    $email=$_POST['email']; 
    $to = $email;
    $subject1="[".$ticket."]".":";
    $subject2 = $title1;
    $subject=$subject1.$subject2;
    $priority1=$_POST['priority'];
    
    $sql6="SELECT *  FROM   bio_priority   WHERE  bio_priority .id=$priority1";
    $result6=DB_query($sql6, $db);
    $row6=DB_fetch_array($result6) ;
    $priority2=$row6['priority']; 
    
    $message = "Dear Customer,

            Your ticket has been received, one of our staff members will review it and reply accordingly. Listed below are details of this ticket, Please make sure the Ticket ID remains in the subject at all times.
            
                Ticket ID: ".$ticket."
                Subject: ".$title1."
                Priority: ".$priority2."
                Status: Awaiting Staff Response
                
 Biotech Helpdesk"; 
$from = "info@biotechin.org";
$headers = "From:" . $from;


//mail($to,$subject,$message,$headers);
 
           
           }

         
else if (($_POST['mainmailcategory']==1 && $_POST['submailcategory']==1) ||  ($_POST['mainmailcategory']==1 && $_POST['submailcategory']==2) ||
($_POST['mainmailcategory']==1 && $_POST['submailcategory']==3) || ($_POST['mainmailcategory']==1 && $_POST['submailcategory']==4) ||
($_POST['mainmailcategory']==1 && $_POST['submailcategory']==5) || ($_POST['mainmailcategory']==1 && $_POST['submailcategory']==6)) {
 
        $date=date("Y-m-d"); 
        //$teamid=$_SESSION['teamid']; 
       
   $emailsource=13; 
   $sql="SELECT * FROM bio_leadsources WHERE sourcetypeid=".$emailsource;
   $result=DB_query($sql,$db);  
   $myrow=DB_fetch_array($result); 
   $sourceid=$myrow['id']; 
   $teamid=$myrow['teamid']; 
   
   $sid=$_POST['emailsource'];  
   $enquiryid=$_POST['enquiry']; 
   $outputtype=$_POST['outputtype'];
          foreach($outputtype as $id1){
          $sourcetype1.=$id1.",";
      } 
      $outputtypeid=substr($sourcetype1,0,-1);
      
   if($_POST['landline']==""){ $_POST['landline']=""; }    
      if($_POST['phno']==""){  $_POST['phno']=""; }    
      if($_POST['email']==""){ $_POST['email']="";   }     
      if($_POST['Houseno']==""){$_POST['Houseno']="";}    
      if($_POST['HouseName']==""){$_POST['HouseName']="";}
      if($_POST['Area1']==""){$_POST['Area1']=" ";} 
      if($_POST['Area2']==""){$_POST['Area2']="";} 
      if($_POST['Pin']==""){$_POST['Pin']="";}
      if($_POST['country']==""){$_POST['country']="";}    
      if($_POST['State']==""){$_POST['State']=" ";}
      if($_POST['District']==0){$_POST['District']="";}   
      if($_POST['taluk']==""){$_POST['taluk']="";} 
      if($_POST['lsgType']==""){$_POST['lsgType']="";}
      if($_POST['lsgName']==""){$_POST['lsgName']="";}    
      if($_POST['gramaPanchayath']==""){$_POST['gramaPanchayath']="";}
      if($_POST['lsgWard']==""){$_POST['lsgWard']="";}  
      if($_POST['village']==""){$_POST['village']="";}
        
   $sqlcust="INSERT INTO `bio_cust` (
                           `custname`,
                           custphone, 
                           `custmob`,
                            custmail, 
                           `houseno`,
                           `housename`,
                           `area1`,
                           `area2`,
                           `pin`,                                                                                                                                                                                                                             
                           `nationality`,
                           `state`,
                           `district`,
                            taluk,
                           LSG_type,
                           LSG_name,
                           block_name,
                           LSG_ward,
                           village
                           ) 
                   VALUES ('".$_POST['custname']."',
                            '".$_POST['landline']."',
                           '".$_POST['phno']."',
                           '".$_POST['email']."',
                           '".$_POST['Houseno']."',
                           '".$_POST['HouseName']."',
                           '".$_POST['Area1']."',
                           '".$_POST['Area2']."',
                           '".$_POST['Pin']."',
                           '".$_POST['country']."',
                           '".$_POST['State']."',
                           '".$_POST['District']."',
                           '".$_POST['taluk']."',
                           '".$_POST['lsgType']."',
                           '".$_POST['lsgName']."',
                           '".$_POST['gramaPanchayath']."',
                           '".$_POST['lsgWard']."',
                           '".$_POST['village']."')"; 
                           
                     $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sqlcust,$db,$ErrMsg,$DbgMsg);  
         $custid=DB_Last_Insert_ID($Conn,'bio_cust','cust_id');
 
    
   $sql="INSERT INTO `bio_leads` (
                  `leaddate`, 
                  `teamid`,
                  `sourceid`, 
                  `enqtypeid`, 
                  `outputtypeid`, 
                  `remarks`,  
                  `cust_id`,
                  `created_by`) 
          VALUES ('$date',
                  '$teamid',
                   '$sid', 
                  ".$enquiryid.", 
                 '".$outputtypeid."',
                 '".$_POST['description']."',
                   $custid,
                  '$_SESSION[UserID]')";        // exit;
  //$result=DB_query($sql, $db);  
//  exit;
//echo$teamid;
//echo$sourceid;//
             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
//  prnMsg( _('The Sales Leads record has been added'),'success');
  $lead=DB_Last_Insert_ID($Conn,'bio_leads','leadid');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
 
 $emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid'];
    
    $sql_desg="SELECT designationid FROM bio_emp WHERE empid=".$emp_ID;
    $result_desg=DB_query($sql_desg,$db);
    $row_desg=DB_fetch_array($result_desg);
    $designation=$row_desg['designationid'];
                        
 
    $sql_cce="SELECT www_users.empid,
                     bio_teammembers.teamid 
                FROM www_users,bio_teammembers 
               WHERE bio_teammembers.empid=www_users.empid";
               
   if($_POST['country']==1 && $_POST['State']==14)        //KERALA
   {                  
       if( $_POST['District']==6 || $_POST['District']==11 || $_POST['District']==12 )    //KLM-PTA-TVM
       {
           if($_POST['submailcategory']==2)
           {
                $sql_cce.=" AND www_users.userid='".ccetvm1."'";  
           }
           else
           {
                $sql_cce.=" AND www_users.userid='".bdm."'";
           }
       }
       elseif( $_POST['District']==1 || $_POST['District']==2 || $_POST['District']==3 || $_POST['District']==7 || $_POST['District']==13 ) //ALP-EKM-IDK-KTM-TRS
       {
           if($_POST['submailcategory']==2)
           {
                $sql_cce.=" AND www_users.userid='".cceeklm1."'";  
           }
           else
           {
                $sql_cce.=" AND www_users.userid='".bdm."'";  
           }                  
       }
       elseif( $_POST['District']==4 || $_POST['District']==5 || $_POST['District']==8 || $_POST['District']==9 || $_POST['District']==10 || $_POST['District']==14 ) //KNR-KSR-KZH-MLP-PLK-WND
       {
           if($_POST['submailcategory']==2)
           {
                $sql_cce.=" AND www_users.userid='".ccekoz1."'";  
           }
           else
           {
                $sql_cce.=" AND www_users.userid='".bdm."'";
           }
       }
   } 
   elseif($_POST['country']==1 && $_POST['State']!=14)     //OUTSIDE KERALA
   {
       $sql_cce.=" AND www_users.userid='".bdm."'";
   }
   elseif($_POST['country']!=1)     //OUTSIDE KERALA
   {
       $sql_cce.=" AND www_users.userid='".bdm."'";
   }
   
   $result_cce=DB_query($sql_cce,$db);
   $row_cce=DB_fetch_array($result_cce);
   $teamid=$row_cce['teamid'];   
   
   $assigned_date=date("Y-m-d");
   
   if($_POST['enquiry']==1 )    {
      $urgency_level=17; 
   }
     else if($_POST['enquiry']==2 )    {
      $urgency_level=19; 
   }
   else
   {
       $urgency_level=31;  
   }
   
  
  
    $sql_schedule="INSERT INTO bio_leadschedule VALUES($lead,$urgency_level)"; 
    $result_schedule=DB_query($sql_schedule,$db);

    $sql_schedule1="SELECT task_master_id,actual_task_day FROM bio_schedule WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$lead) ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
    
        while($row_schedule1=DB_fetch_array($result_schedule1))
        {       
                $taskid=$row_schedule1['task_master_id'];
                $date_interval+=$row_schedule1['actual_task_day'];
                
                //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
                
             $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                         leadid,
                                                         teamid,
                                                         assigneddate,
                                                         duedate,
                                                         assigned_from,
                                                         viewstatus)
                                             VALUES('".$taskid."',
                                                    '".$lead."',
                                                    '".$teamid."',
                                                    '".$assigned_date."',
                                                    '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                                    '".$assignedfrm."',
                                                    1)";
                 $result_leadTask=DB_query($sql_leadTask,$db); 
                
                $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
                $date_interval+=1;                                   
        } 
     
      $email=$_POST['email']; 
      $to = $email;  $subject='Sales Leads';
     $message = "Dear Customer,

            Your Sales Lead has been registered.
                
 Biotech Helpdesk"; 
$from = "info@biotechin.org";
$headers = "From:" . $from;
//mail($to,$subject,$message,$headers);  


 $msg1= 'The Sales Leads record has been added successfully';      
       prnMsg($msg1,'success');   
       
       
 $sql = "INSERT INTO bio_incident_cust(custname,
                                         landline,
                                         custphone,
                                         custmail,
                                         houseno,
                                         housename,
                                         area1,
                                         area2,
                                         pin,
                                         nationality,
                                         state,
                                         district,
                                         taluk,
                                         LSG_type,
                                         LSG_name,
                                         block_name, 
                                         LSG_ward, 
                                         village)
                       VALUES ('" . $_POST['custname'] . "',
                               '".$_POST['landline']."',
                               '" . $_POST['phno'] . "',
                               '" . $_POST['email'] ."',
                               '" . $_POST['Houseno'] . "',
                               '" . $_POST['HouseName'] . "',
                               '" . $_POST['Area1'] . "',
                               '" . $_POST['Area2'] . "',
                               '" . $_POST['Pin'] . "',
                               '" . $_POST['country'] . "',
                               '" . $_POST['State'] . "',
                               '" . $_POST['District'] . "',
                               '".$_POST['taluk']."',
                               '".$_POST['lsgType']."',
                               '".$_POST['lsgName']."', 
                               '".$_POST['gramaPanchayath']."',
                                '".$_POST['lsgWard']."', 
                                '".$_POST['village']."')";                                           
       $result = DB_query($sql,$db);
       
               $cust_id=DB_Last_Insert_ID($Conn,'bio_incident_cust','cust_id');
              $duedate='0000-00-00';
               
 if($_POST['District']==""){
      $officer=1;   
 } 
                           else{                      
$sql2="SELECT *  FROM 
              bio_incidenthandlingoffcr 
              WHERE bio_incidenthandlingoffcr.types=".$_POST['type']."
              AND   bio_incidenthandlingoffcr.nationality=".$_POST['country']."
              AND   bio_incidenthandlingoffcr.state=".$_POST['State']."
              AND   bio_incidenthandlingoffcr.district=".$_POST['District']."  ";
    
  $result2=DB_query($sql2, $db);
  $count=DB_num_rows($result2); 
  $row2=DB_fetch_array($result2) ;   
  $dept=$row2['department'];
  if($count>0){
$officer=$row2['officer'];
  //$dept=$row2['department']; 
  }else{
      $officer=1;   
  }               
 // $dept=$row2['department']; 
  
                       }    
  
      
  $sql2="SELECT bio_teammembers.empid,bio_teammembers.teamid,bio_emp.empid,bio_emp.email
         FROM   bio_teammembers,bio_emp
         WHERE  bio_teammembers.teamid=$officer";
  $result2=DB_query($sql2, $db);
  $row2=DB_fetch_array($result2) ;
  $emails=$row2['email']; 
    
     if(isset($emails))    {
    $title1=$_POST['title'];       
    $description=$_POST['description'];
    $to = $emails;
    $subject1="["."S".$ticket."]".":";
    $subject2 = $title1;
    $subject=$subject1.$subject2;
    $priority1=$_POST['priority'];
        $message = " Sir,

           An Incident is receieved with the following details.
                From: ".$email."
                Subject: ".$subject." 
                Content:".$description.";
                Priority: ".$priority2."
                
                
 Biotech Helpdesk"; 
$from = "info@biotechin.org";
$headers = "From:" . $from;

//mail($to,$subject,$message,$headers);
    
     }  
    
         //    if($_POST['leadid']==""){ $_POST['leadid']=0; }
             if($_POST['orderid']==""){ $_POST['orderid']=0; }  
    $createdate=date("Y-m-d");   
     $create_date= $_POST['date'];   
 
  $from='From:'.$_POST['email'].'          ';
  $title2='Subject:'.$_POST['title'].'       ';  
  $content='Body:'.$_POST['message'];  
 $mail_message= $from.$title2.$content;                 
                            
 $sql1 = "INSERT INTO bio_incidents(cust_id,
                                    type,
                                    source,
                                    title,
                                    enqtypeid,
                                    description,
                                    priority,
                                    expected_duedate,
                                    handling_officer,
                                    leadid,
                                    orderno,
                                    createdon,
                                    createdby,
                                    status,
                                    emailsource,
                                    email_message,
                                    mainmailcategory,
                                    submailcategory,
                                    emailtype)
                     VALUES ('" . $cust_id . "',
                             '" . $_POST['type'] . "',
                                         2, 
                             '" . $_POST['title'] . "',
                             '" . $_POST['enquiry'] . "',
                            '" . $_POST['description'] . "',  
                                         2,
                             '" . $duedate . "',
                             '" . $officer ."',
                             " . $lead . ",
                             " . $_POST['orderid'] .",
                             '" . $create_date . "',
                             '" . $collectedBy . "',
                                      1,
                             '" .  $_POST['emailsource']. "', 
                             '" . $mail_message . "',  
                             '" . $_POST['mainmailcategory'] . "', 
                             '" . $_POST['submailcategory'] . "',
                             '" . $_POST['emailtype'] . "')";                                           
       $result1 = DB_query($sql1,$db); 
      
        
      
   
  //$sql5="SELECT *  FROM   bio_dept  WHERE  bio_dept.deptid=$dept";
//    
//  $result5=DB_query($sql5, $db);
//  $row5=DB_fetch_array($result5) ;
//  $deptname=$row5['deptname'];  

     
     
}    

else  if (($_POST['mainmailcategory']==2 && $_POST['submailcategory']==1) || ($_POST['mainmailcategory']==2 && $_POST['submailcategory']==2) ||
($_POST['mainmailcategory']==2 && $_POST['submailcategory']==3) || ($_POST['mainmailcategory']==2 && $_POST['submailcategory']==4)){                        
    
      if($_POST['landline']==""){ $_POST['landline']=""; }    
      if($_POST['phno']==""){  $_POST['phno']=""; }    
      if($_POST['email']==""){ $_POST['email']="";   }     
      if($_POST['Houseno']==""){$_POST['Houseno']="";}    
      if($_POST['HouseName']==""){$_POST['HouseName']="";}
      if($_POST['Area1']==""){$_POST['Area1']=" ";} 
      if($_POST['Area2']==""){$_POST['Area2']="";} 
      if($_POST['Pin']==""){$_POST['Pin']="";}
      if($_POST['country']==""){$_POST['country']="";}    
      if($_POST['State']==""){$_POST['State']=" ";}
      if($_POST['District']==""){$_POST['District']="";}   
      if($_POST['taluk']==""){$_POST['taluk']="";} 
      if($_POST['lsgType']==""){$_POST['lsgType']="";}
      if($_POST['lsgName']==""){$_POST['lsgName']="";}    
      if($_POST['gramaPanchayath']==""){$_POST['gramaPanchayath']="";}
      if($_POST['lsgWard']==""){$_POST['lsgWard']="";}  
      if($_POST['village']==""){$_POST['village']="";}

    
    $sqlcust="INSERT INTO `bio_businessassodetails_enq` (
                           `custname`,
                            `custphone`, 
                           `custmob`,
                            `custmail`, 
                           `houseno`,
                           `housename`,
                           `area1`,
                           `area2`,
                           `pin`,                                                                                                                                                                                                                             
                           `nationality`,
                           `state`,
                           `district`,
                            `taluk`,
                           LSG_type,
                           LSG_name,
                           block_name,
                           LSG_ward,
                           village) 
                   VALUES ('".$_POST['custname']."',
                          '".$_POST['landline']."',
                           '".$_POST['phno']."',
                           '".$_POST['email']."',
                           '".$_POST['Houseno']."',
                           '".$_POST['HouseName']."',
                           '".$_POST['Area1']."',
                           '".$_POST['Area2']."',
                           '".$_POST['Pin']."',
                           '".$_POST['country']."',
                           '".$_POST['State']."',
                           '".$_POST['District']."',
                           '".$_POST['taluk']."',
                           '".$_POST['lsgType']."',
                           '".$_POST['lsgName']."',
                           '".$_POST['gramaPanchayath']."',
                           '".$_POST['lsgWard']."',
                           '".$_POST['village']."')"; 
  
            $result = DB_query($sqlcust,$db);  
         $custid=DB_Last_Insert_ID($Conn,'bio_businessassodetails_enq','cust_id');
  
            $createdate=date("Y-m-d"); 
            
            
         
         
 $sqlcust="INSERT INTO bio_businessassociates_enq (cust_id,
                                                   description,
                                                   createdby,
                                                   createddate)    
                           VALUES ('" . $custid . "',     
                                   '" . $_POST['description'] . "',
                                   '" . $collectedBy . "',
                                   '" .  $createdate. "')";     
         $result = DB_query($sqlcust,$db);
         

         
                  $b_id=DB_Last_Insert_ID($Conn,'bio_businessassociates_enq','buss_id');
                        
          $msg1= 'Your details have been saved successfully';      
       prnMsg($msg1,'success'); 
       
       
       
    $sql_bdm="SELECT www_users.empid,
                     bio_teammembers.teamid 
                FROM www_users,bio_teammembers 
               WHERE bio_teammembers.empid=www_users.empid";
               
   if($_POST['country']==1 && $_POST['State']==14)        //KERALA
   {                  
       if( $_POST['District']==6 || $_POST['District']==11 || $_POST['District']==12 )    //KLM-PTA-TVM
       {
              $sql_bdm.=" AND www_users.userid='".bdetvm1."'";
       }
       elseif( $_POST['District']==1 || $_POST['District']==2 || $_POST['District']==3 || $_POST['District']==7 || $_POST['District']==13 ) //ALP-EKM-IDK-KTM-TRS
       {
           $sql_bdm.=" AND www_users.userid='".bdeeklm1."'";                    
       }
       elseif( $_POST['District']==4 || $_POST['District']==5 || $_POST['District']==8 || $_POST['District']==9 || $_POST['District']==10 || $_POST['District']==14 ) //KNR-KSR-KZH-MLP-PLK-WND
       {
           $sql_bdm.=" AND www_users.userid='".bdekoz1."'";
       }
   } 
   elseif($_POST['country']==1 && $_POST['State']!=14)     //OUTSIDE KERALA
   {
       $sql_bdm.=" AND www_users.userid='".bde_national."'";
   }elseif($_POST['country']!=1){                           //OUTSIDE INDIA
       $sql_bdm.=" AND www_users.userid='".bde_international."'";
       
   }
    $sql_bdm;
   $result_bdm=DB_query($sql_bdm,$db);
   $row_bdm=DB_fetch_array($result_bdm);
   $teamid=$row_bdm['teamid'];     
   
  
  $sql_team="INSERT INTO bio_dealersteam (buss_id,teamid     )  VALUES ('$b_id','$teamid')";
  DB_query($sql_team,$db);   
 
 
$sql = "INSERT INTO bio_incident_cust(custname,
                                       landline,
                                       custphone,
                                       custmail,
                                       houseno,
                                       housename,
                                       area1,
                                       area2,
                                       pin,
                                       nationality,
                                       state,
                                       district,
                                       taluk,
                                       LSG_type, 
                                       LSG_name,
                                       block_name, 
                                       LSG_ward, 
                                       village)
                    VALUES ('" . $_POST['custname'] . "',
                            '".$_POST['landline']."',
                            '" . $_POST['phno'] . "',
                            '" . $_POST['email'] . "',
                            '" . $_POST['Houseno'] . "',
                            '" . $_POST['HouseName'] . "',
                            '" . $_POST['Area1'] . "',
                            '" . $_POST['Area2'] . "',
                            '" . $_POST['Pin'] . "',
                            '" . $_POST['country'] . "',
                            '" . $_POST['State'] . "',
                            '" . $_POST['District'] . "',
                            '".$_POST['taluk']."',
                            '".$_POST['lsgType']."',
                            '".$_POST['lsgName']."', 
                            '".$_POST['gramaPanchayath']."', 
                            '".$_POST['lsgWard']."',
                             '".$_POST['village']."')";                                           
       $result = DB_query($sql,$db);
       
               $cust_id=DB_Last_Insert_ID($Conn,'bio_incident_cust','cust_id');
              $duedate='0000-00-00';
               
 if($_POST['District']==""){
                                   $officer=1;   
                                              } 
                           else{                      
 $sql2="SELECT *  FROM 
              bio_incidenthandlingoffcr 
              WHERE bio_incidenthandlingoffcr.types=".$_POST['type']."
              AND   bio_incidenthandlingoffcr.nationality=".$_POST['country']."
              AND   bio_incidenthandlingoffcr.state=".$_POST['State']."
              AND   bio_incidenthandlingoffcr.district=".$_POST['District']."  ";
    
  $result2=DB_query($sql2, $db);
  $count=DB_num_rows($result2); 
  $row2=DB_fetch_array($result2) ;   
  $dept=$row2['department'];
  if($count>0){
$officer=$row2['officer'];
  //$dept=$row2['department']; 
  }else{
      $officer=1;   
  }               
 // $dept=$row2['department']; 
  
                       }    
  
      
 $sql2="SELECT bio_teammembers.empid,bio_teammembers.teamid,bio_emp.empid,bio_emp.email
         FROM   bio_teammembers,bio_emp
         WHERE  bio_teammembers.teamid=$officer";
  $result2=DB_query($sql2, $db);
  $row2=DB_fetch_array($result2) ;
  $emails=$row2['email']; 
    
     if(isset($emails))    {
    $title1=$_POST['title'];       
    $description=$_POST['description'];
    $to = $emails;
    $subject1="["."B".$ticket."]".":";
    $subject2 = $title1;
    $subject=$subject1.$subject2;
    $priority1=$_POST['priority'];
        $message = " Sir,

           An Incident is receieved with the following details.
                From: ".$email."
                Subject: ".$subject." 
                Content:".$description.";
                Priority: ".$priority2."
                
                
 Biotech Helpdesk"; 
$from = "info@biotechin.org";
$headers = "From:" . $from;

//mail($to,$subject,$message,$headers);
    
     } 
             if($_POST['leadid']==""){ $_POST['leadid']=0; }
             if($_POST['orderid']==""){ $_POST['orderid']=0; }  
    $createdate=date("Y-m-d");  
     $create_date= $_POST['date'];   
     $from='From:'.$_POST['email'].'          ';
  $title2='Subject:'.$_POST['title'].'              ';  
  $content='Body:'.$_POST['message'];  
 $mail_message= $from.$title2.$content;   
        
 $sql1 = "INSERT INTO bio_incidents(cust_id,
                                    type,
                                    source,
                                    title,
                                    enqtypeid,
                                    description,
                                    priority,
                                    expected_duedate,
                                    handling_officer,
                                    leadid,
                                    orderno,
                                    createdon,
                                    createdby,
                                    status,
                                    emailsource,
                                    email_message,
                                    mainmailcategory,
                                    submailcategory,
                                    buss_id,
                                    emailtype)
                     VALUES ('" . $cust_id . "',
                             '" . $_POST['type'] . "',
                                       2, 
                             '" . $_POST['title'] . "',
                             '" . $_POST['enquiry'] . "',
                             '" . $_POST['description'] . "',       
                                        2,
                             '" . $duedate . "',
                             '" . $officer ."',
                             " . $_POST['leadid'] . ",
                             " . $_POST['orderid'] .",
                             '" . $create_date . "',
                             '" . $collectedBy . "',
                                         1,
                             '" .  $_POST['emailsource']. "',
                             '" . $mail_message . "',        
                             '" . $_POST['mainmailcategory'] . "',
                             '" . $_POST['submailcategory'] . "',
                             '" . $b_id . "',
                             '" . $_POST['emailtype'] . "')";                                                 
       $result1 = DB_query($sql1,$db); 
      
   
  //$sql5="SELECT *  FROM   bio_dept  WHERE  bio_dept.deptid=$dept";
//    
//  $result5=DB_query($sql5, $db);
//  $row5=DB_fetch_array($result5) ;
//  $deptname=$row5['deptname'];  
   
  
            
     }
 else  if (($_POST['mainmailcategory']==3 && $_POST['submailcategory']==1) || ($_POST['mainmailcategory']==3 && $_POST['submailcategory']==2)){                        

 
      if($_POST['landline']==""){ $_POST['landline']=""; }    
      if($_POST['phno']==""){  $_POST['phno']=""; }    
      if($_POST['email']==""){ $_POST['email']="";   }     
      if($_POST['Houseno']==""){$_POST['Houseno']="";}    
      if($_POST['HouseName']==""){$_POST['HouseName']="";}
      if($_POST['Area1']==""){$_POST['Area1']=" ";} 
      if($_POST['Area2']==""){$_POST['Area2']="";} 
      if($_POST['Pin']==""){$_POST['Pin']="";}
      if($_POST['country']==""){$_POST['country']="";}    
      if($_POST['State']==""){$_POST['State']=" ";}
      if($_POST['District']==0){$_POST['District']="";}   
      if($_POST['taluk']==""){$_POST['taluk']="";} 
      if($_POST['lsgType']==""){$_POST['lsgType']="";}
      if($_POST['lsgName']==""){$_POST['lsgName']="";}    
      if($_POST['gramaPanchayath']==""){$_POST['gramaPanchayath']="";}
      if($_POST['lsgWard']==""){$_POST['lsgWard']="";}  
      if($_POST['village']==""){$_POST['village']="";}
     
     $sqlcust="INSERT INTO `bio_internship_jobenquiry` (
                           `custname`,
                           custphone, 
                           `custmob`,
                            custmail, 
                           `houseno`,
                           `housename`,
                           `area1`,
                           `area2`,
                           `pin`,                                                                                                                                                                                                                             
                           `nationality`,
                           `state`,
                           `district`,
                            taluk,
                           LSG_type,
                           LSG_name,
                           block_name,
                           LSG_ward,
                           village) 
                   VALUES ('".$_POST['custname']."',
                           '".$_POST['landline']."',
                           '".$_POST['phno']."',
                           '".$_POST['email']."',
                           '".$_POST['Houseno']."',
                           '".$_POST['HouseName']."',
                           '".$_POST['Area1']."',
                           '".$_POST['Area2']."',
                           '".$_POST['Pin']."',
                           '".$_POST['country']."',
                           '".$_POST['State']."',
                           '".$_POST['District']."',
                           '".$_POST['taluk']."',
                           '".$_POST['lsgType']."',
                           '".$_POST['lsgName']."',
                           '".$_POST['gramaPanchayath']."',
                           '".$_POST['lsgWard']."',
                           '".$_POST['village']."')"; 
  
            $result = DB_query($sqlcust,$db);  
         $custid=DB_Last_Insert_ID($Conn,'bio_internship_jobenquiry','cust_id');
  
                  $createdate=date("Y-m-d"); 
   $sqlcust="INSERT INTO bio_internship_jobs (cust_id,   
                                                     description,
                                                     createdby,
                                                     createddate)    
                           VALUES ('" . $custid . "',
                                   '" . $_POST['description'] . "',
                                   '" . $collectedBy . "',
                                   '" .  $createdate. "')";
         $result = DB_query($sqlcust,$db);
         $enqid=DB_Last_Insert_ID($Conn,'bio_internship_jobs','enq_id');                                                                
      $email=$_POST['email']; 
      $to = $email;  $subject='Business Association Enquiry';
     $message = "Dear Customer,

            Your Business Association Enquiry  has been registered.
                
 Biotech Helpdesk"; 
$from = "info@biotechin.org";
$headers = "From:" . $from;
//mail($to,$subject,$message,$headers);  


          $msg1= 'Your details have been saved successfully';      
       prnMsg($msg1,'success');  

 $sql = "INSERT INTO bio_incident_cust(custname,
                                       landline,
                                       custphone,
                                       custmail,
                                       houseno,
                                       housename,
                                       area1,
                                       area2,
                                       pin,
                                       nationality,
                                       state,
                                       district,
                                       taluk,
                                       LSG_type,
                                       LSG_name,
                                       block_name,
                                       LSG_ward,
                                       village)
                         VALUES ('" . $_POST['custname'] . "',
                         '".$_POST['landline']."',
                         '" . $_POST['phno'] . "',
                         '" . $_POST['email'] . "',
                         '" . $_POST['Houseno'] . "',
                         '" . $_POST['HouseName'] . "',
                         '" . $_POST['Area1'] . "',
                         '" . $_POST['Area2'] . "',
                         '" . $_POST['Pin'] . "',
                         '" . $_POST['country'] . "',
                         '" . $_POST['State'] . "',
                         '" . $_POST['District'] . "',
                         '".$_POST['taluk']."',
                         '".$_POST['lsgType']."',
                         '".$_POST['lsgName']."', 
                         '".$_POST['gramaPanchayath']."',
                          '".$_POST['lsgWard']."', 
                          '".$_POST['village']."')";                                           
       $result = DB_query($sql,$db);
       
               $cust_id=DB_Last_Insert_ID($Conn,'bio_incident_cust','cust_id');
              $duedate='0000-00-00';
               
 if($_POST['District']==""){
                                   $officer=1;   
                                              } 
                           else{                      
$sql2="SELECT *  FROM 
              bio_incidenthandlingoffcr 
              WHERE bio_incidenthandlingoffcr.types=".$_POST['type']."
              AND   bio_incidenthandlingoffcr.nationality=".$_POST['country']."
              AND   bio_incidenthandlingoffcr.state=".$_POST['State']."
              AND   bio_incidenthandlingoffcr.district=".$_POST['District']."  ";
    
  $result2=DB_query($sql2, $db);
  $count=DB_num_rows($result2); 
  $row2=DB_fetch_array($result2) ;   
  $dept=$row2['department'];
  if($count>0){
$officer=$row2['officer'];
  //$dept=$row2['department']; 
  }else{
      $officer=1;   
  }               
 // $dept=$row2['department']; 
  
                       }    
  
      
  $sql2="SELECT bio_teammembers.empid,bio_teammembers.teamid,bio_emp.empid,bio_emp.email
         FROM   bio_teammembers,bio_emp
         WHERE  bio_teammembers.teamid=$officer";
  $result2=DB_query($sql2, $db);
  $row2=DB_fetch_array($result2) ;
  $emails=$row2['email']; 
    
     if(isset($emails))    {
    $title1=$_POST['title'];       
    $description=$_POST['description'];
    $to = $emails;
    $subject1="["."J".$ticket."]".":";
    $subject2 = $title1;
    $subject=$subject1.$subject2;
    $priority1=$_POST['priority'];
        $message = " Sir,

           An Incident is receieved with the following details.
                From: ".$email."
                Subject: ".$subject." 
                Content:".$description.";
                Priority: ".$priority2."
                
                
 Biotech Helpdesk"; 
$from = "info@biotechin.org";
$headers = "From:" . $from;

//mail($to,$subject,$message,$headers);
    
     } 
             if($_POST['leadid']==""){ $_POST['leadid']=0; }
             if($_POST['orderid']==""){ $_POST['orderid']=0; }  
    $createdate=date("Y-m-d");    
     $create_date= $_POST['date'];   
  $from='From:'.$_POST['email'].'          ';
  $title2='Subject:'.$_POST['title'].'           ';  
  $content='Body:'.$_POST['message'];  
 $mail_message= $from.$title2.$content;        
$sql1 = "INSERT INTO bio_incidents(cust_id,
                                   type,
                                   source,
                                   title,
                                   enqtypeid,
                                   description,
                                   priority,
                                   expected_duedate,
                                   handling_officer,
                                   leadid,
                                   orderno,
                                   createdon,
                                   createdby,
                                   status,
                                   emailsource,
                                   email_message,
                                   mainmailcategory,
                                   submailcategory,
                                   enq_id,
                                   emailtype)
                 VALUES ( '" . $cust_id . "',
                          '" . $_POST['type'] . "',
                                    2, 
                          '" . $_POST['title'] . "',
                          '" . $_POST['enquiry'] . "',
                          '" .  $_POST['description']. "',      
                                    2,
                          '" . $duedate . "',
                          '" . $officer ."',
                          " . $_POST['leadid'] . ",
                          " . $_POST['orderid'] .",
                          '" . $create_date . "',
                          '" . $collectedBy . "',
                                    1,
                          '" .  $_POST['emailsource']. "',
                          '" . $mail_message . "',         
                          '" . $_POST['mainmailcategory'] . "',
                          '" . $_POST['submailcategory'] . "',
                          '" . $enqid . "',
                          '" . $_POST['emailtype'] . "')";                                                
       $result1 = DB_query($sql1,$db); 
  
   
  //$sql5="SELECT *  FROM   bio_dept  WHERE  bio_dept.deptid=$dept";
//    
//  $result5=DB_query($sql5, $db);
//  $row5=DB_fetch_array($result5) ;
//  $deptname=$row5['deptname'];  
    }
 
  if(isset($_POST['Mailid']) AND $_POST['Mailid']!="") {    
 
 $incident1=$_POST['Mailid'];  $category1=$_POST['mainmailcategory'];    $category2=$_POST['submailcategory']; 
  $sql6="UPDATE bio_email SET status=1 ,
 mainmailcategory=$category1,  submailcategory=$category2 where id=$incident1";  
 $result6=DB_query($sql6,$db);    
 }
           
   
 }
 
   
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';    
  echo "<table style='width:75%'><tr><td>";  
 echo'<div id="showmaildiv">'; echo '<div id="panel"></div></div><br /></form>';
   echo"<div id='maildiv'>"; 
  echo "<fieldset style='width:95%;'>";     
  echo "<legend><h3>All Mails</h3>";     
  echo "</legend>";
  
  echo "<div style='height:350px; overflow:auto;'>"; 
  echo "<table style='width:100%;' id='mail'>";     
  echo "<thead>
         <tr BGCOLOR =#800000>
         <th>" . _('Sl no') . "</th>
         <th>" . _('From') . "</th>
         <th>" . _('Subject') . "</th>    
         <th>" . _('Date') . "</th>
         <th></th><th></th><th></th>     
         </tr></thead>";  
         
         $select_email="SELECT * FROM bio_email where status=0";
         $result_email=DB_query($select_email,$db);
         $no=1;
         
         while($row=DB_fetch_array($result_email))
         {
               $emailid=$row['id'];
               $title1=$row['subject'];
             //  echo'<input type="hidden" id="sub" name="sub" value="'.$title1.'">';
            echo"<tr style='background:#D0D0D0'>
                     <td>$no</td>
                     <td><b>".$row['from_name']."</b></td>
                     <td><b>".$title1."</b></td> 
                     <td><b>".$row['date']."</b></td>
                     <td><a href='#' id='".$row['id']."' onclick='showDetails(this.id)'>Select</a></td>
                     <td><a href='#' id='".$row['id']."' onclick='showMail(this.id)'>View</a></td>    
                     <td><a href='#' id='".$row['id']."' onclick='delMail(this.id)'>Delete</a></td>    

                 </tr>";    
                                              
                 $no++;   
                            
        }
        
        
      echo "</table></div>";
      echo "</fieldset></div>";  
      echo "</td></tr></table>";

?>
<script type="text/javascript">
function delMail(str0){
    var r=confirm("Are you sure to delete?");
    if (r==true)
    {
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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showmsg").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","bio_msgDelete.php?del=" +str0,true);
xmlhttp.send();

//location.href="?del=" +str0;
    }
    
    
}
function showDetails(str){
     $("#maildiv").hide();    
  //alert(str);  
if (str=="")
  {  
  document.getElementById("panel").innerHTML="";
  return;
  }      
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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("panel").innerHTML=xmlhttp.responseText;  
    //document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_emaildetails.php?mailid=" + str,true);
xmlhttp.send(); 
}


function selectDetails(str1,str2)
{   
//    alert(str1); alert(str2);
    window.open("bio_selectEmails.php?custid=" + str1 + "&mailid=" +str2 );                           
} 


 /*function selectDetails(str1,str2,str3){
              // alert(ticketid) ;

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
   
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showmaildiv").innerHTML=xmlhttp.responseText;


    }
  }
xmlhttp.open("GET","bio_selectEmails.php?emailid=" +str1+ "&maincatid="+str2+ "&subcatid=" +str3,true);
xmlhttp.send();
     
   }             */


 function showmailtype(str){  
      // alert(str); 
 if (str=="")
  {
  document.getElementById("emaildiv").innerHTML="";
  return;
  }
  var str1=document.getElementById("mainmailcategory").value;
  //alert(str1);  
  var str2=document.getElementById("sub").value;  
//show_progressbar('showstate');

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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {                 //  alert(str); 
    document.getElementById("emaildiv").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_emailcategory.php?category=" + str+ "&type=" + str1+ "&title=" + str2,true);
xmlhttp.send();  
 
 } 
 
 
  function showsubmailcategory(str){
//    alert(str); 

if (str=="")
  {
  document.getElementById("showcatagry").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {                 //  alert(str); 
    document.getElementById("showcatagry").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showcategory.php?category=" + str,true);
xmlhttp.send();
}
 
 
    
   function showstate(str){
//    alert(str); 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
  


    function showIncident(str){
        //alert(str1);   
        window.location="bio_incidentRegister.php?id=" + str;
    }
    
   function showMail(str){   
    //alert(str1);   
    myRef=window.open("bio_mailcontent.php?mailid=" + str);  
    }
    
    
    
    function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str1=="")
  {
  document.getElementById("showtaluk").innerHTML="";
  return;
  }
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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?taluk=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("block").innerHTML="";
  return;
  }
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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

  function showgramapanchayath(str){   
   //alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showgramapanchayath").innerHTML="";
  return;
  }
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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("showgramapanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 
   function showmain(str){   
   //alert(str);
     
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("maincat").innerHTML="";
  return;
  }
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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("maincat").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_showmainmail.php?inctype=" + str,true);
xmlhttp.send(); 
} 
    
 function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;

if(f==0){f=common_error('custname','Please enter your Name');  if(f==1){return f; }  }
if(f==0)
{
    var y=document.getElementById('phno').value; 
    var x=document.getElementById('email').value;
    if(x=="" && y==""){ alert("Please enter atleast one contact number");f=1;} 
    if(f==1) { document.getElementById('phno').focus();return f; } }
//if(f==0){f=common_error('country','Please select the Country');  if(f==1){return f; }  } 
//if(f==0){f=common_error('state','Please select the State');  if(f==1){return f; }  }  
//if(f==0){f=common_error('landline','Please enter the Phone Number');  if(f==1){return f; }  } 
        var state=document.getElementById('state').value; 
        var country=document.getElementById('country').value; 
if(country==1 && state==14)  {if(f==0){f=common_error('Districts','Please select the District');  if(f==1){return f; }  }  }
if(f==0){f=common_error('emailtype','Please select the Email Type');  if(f==1){return f; }  }  
if(f==0){f=common_error('emailsource','Please select the Email Source');  if(f==1){return f; }  }  
if(f==0){f=common_error('type','Please select the Incident Type');  if(f==1){return f; }  }    
if(f==0){f=common_error('mainmailcategory','Please select the Email Category');  if(f==1){return f; }  } 
if(f==0){f=common_error('submailcategory','Please select the Sub Category');  if(f==1){return f; }  } 
   var str1=document.getElementById("mainmailcategory").value; 
   var str2=document.getElementById("submailcategory").value;      
   if(str1==5 && str2==1)  {


if(f==0){f=common_error('title','Please enter the Complaint Title');  if(f==1){return f; }  } 
if(f==0){f=common_error('description','Please enter the Description');  if(f==1){return f; }  }  
if(f==0){f=common_error('priority','Please select the Priority');  if(f==1){return f; }  }       
   
   } 
   
   
    if((str1==1 && str2==1) || (str1==1 && str2==2) || (str1==1 && str2==3) ||(str1==1 && str2==4) ||(str1==1 && str2==5) || (str1==1 && str2==6))  {   
if(f==0){f=common_error('enquiry','Please enter the Customer Type');  if(f==1){return f; }  }
var typecnt=document.getElementById('houttype').value;
//alert(typecnt);
if(f==0){
    
var chks = document.getElementsByName('outputtype[]');
var hasChecked = false;

for (var i = 0; i < chks.length; i++)
{
if (chks[i].checked)
{
hasChecked = true;
break;
}
}
if (hasChecked == false)
{
f=1;
alert("Please select at least one Output type");
return f; 
} }

   }
    if((str1==2 && str2==1) || (str1==2 && str2==2) || (str1==2 && str2==3) ||(str1==2 && str2==4) ||(str1==3 && str2==1) || (str1==3 && str2==2))  {   
  
   
 if(f==0){f=common_error('description','Please enter the Description');  if(f==1){return f; }  }   
 
   }


//if(f==0){f=common_error('outputtype','Please select the Output Type');  if(f==1){return f; }  } 
 
//if(f==0){f=common_error('status','Please select the Status');  if(f==1){return f; }  }  
     
}   

 function duplicatemail(str,str1){  
 //      alert(str1); 
 if (str=="")
  {
  document.getElementById("duplicatemails").innerHTML="";
  return;
  }

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
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {                 //  alert(str); 
    document.getElementById("duplicatemails").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_duplicatemails.php?mail=" + str + "&emailid=" +str1,true);
xmlhttp.send();  
 
 } 
  function insdate()
{
    
controlWindow=window.open("incident.php","insdate","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
} 
  function insdate1()
{
    
controlWindow=window.open("pg3.php","insdate1","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
}  
    
    
    </script>  
