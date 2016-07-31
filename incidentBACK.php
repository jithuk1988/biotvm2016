<?php
    $PageSecurity = 80;
include('includes/session.inc');


$sql_back="SELECT ticketno,email_message,emailstatus,lastactiondate
           FROM bio_incidents_back
           ";
           $result_back=DB_query($sql_back,$db);
           while($row_back=DB_fetch_array($result_back))
           {
               $message = str_replace("'","\'", $row_back['email_message']);
               $sql_incident="UPDATE bio_incidents SET email_message='".$message."',
                                                       emailstatus='".$row_back['emailstatus']."',
                                                       lastactiondate='".$row_back['lastactiondate']."'
                                                 WHERE ticketno='".$row_back['ticketno']."' ";
               DB_query($sql_incident,$db);                                  
           }


?>
