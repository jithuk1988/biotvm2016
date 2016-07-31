<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  
  $leadid=$_GET['leadid'];
  $custid=$_GET['custid'];         
  $name=$_GET['name'];               
  $place=$_GET['place'];
  $dist=$_GET['dist'];
  $remarks=$_GET['remarks'];
  
//  $sql_search="SELECT ";

  //$sql="UPDATE bio_cust SET area1='". $place ."',
//                            custname='". $name ."',
//                            district='". $dist ."'
//                      WHERE cust_id=$custid";
//  $result=DB_query($sql,$db);                    
  
  $sql1="UPDATE bio_leads SET remarks='". $remarks ."'
                        WHERE leadid=$leadid"; 
  $result1=DB_query($sql1,$db);

?>
