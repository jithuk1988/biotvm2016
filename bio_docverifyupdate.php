<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Verification');  
include('includes/header.inc');

$leadid=$_GET['leadid'];
 $docno=$_GET['docno'];  
 $refno=$_GET['ref'];
 
  $sql_update="UPDATE  bio_documentlist SET refno='".$refno."' WHERE docno='".$docno."' AND leadid='".$leadid."'";  
  $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
 $result=DB_query($sql_update,$db,$DbgMsg);
// $msg = _('update Successfully');
//$ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
//           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
//           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
//  prnMsg( _('The Sales Leads record has been Updated'),'success');
//  
?>
