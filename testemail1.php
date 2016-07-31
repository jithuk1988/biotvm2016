<?php
$host='{localhost/imap4}INBOX'; //Host to connect
$user='info.biotechin';
$pass='bio2345tech';
$from='info@biotechin.org'; //Mail to send from
//$mail=@imap_open($host,$user,$pass) or die("Can't connect: " . imap_last_error());
//echo 'mailbox connected';
$imap = imap_open("{127.0.0.1:143}INBOX", "info.biotechin", "bio2345tech");
    $message_count = imap_num_msg($imap);
    print $message_count;
    imap_close($imap);
?>
