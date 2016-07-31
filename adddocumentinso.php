<?php
  
 $PageSecurity = 80;
include('includes/session.inc');
 $sql_order="SELECT orderno,leadid
                FROM salesorders";
 $result_order=DB_query($sql_order,$db);
 while($row_order=DB_fetch_array($result_order)){
     
     $sql1="SELECT enqtypeid FROM bio_leads WHERE leadid=".$row_order['leadid'];
     $result1=DB_query($sql1,$db);
     $row1=DB_fetch_array($result1);
     
     if($row1['enqtypeid']==1) {
     $sql2="SELECT doc_no FROM bio_document_master WHERE enqtypeid=".$row1['enqtypeid'];
     }elseif($row1['enqtypeid']==2)    {
     $sql2="SELECT doc_no FROM bio_document_master WHERE enqtypeid=".$row1['enqtypeid'];
     }
     $result2=DB_query($sql2,$db);
     
     while($row2=DB_fetch_array($result2)){
         $sql_documents="INSERT INTO bio_documentlist (
                                        leadid,
                                        orderno,
                                        docno,
                                        status)
                                     VALUES (
                                        '" . $row_order['leadid'] ."',
                                        '". $row_order['orderno'] . "',
                                        '". $row2['doc_no'] . "',
                                        0 
                                        )   ";
         DB_query($sql_documents,$db);
         
         
         
     }
     
     
     
     
 }
 
 
 
 
 
  
?>
