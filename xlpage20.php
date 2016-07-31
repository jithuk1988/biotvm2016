<?php
$PageSecurity = 80;

include('includes/session.inc');


$filename ='$filename1'.'.csv';

//
//$user = explode(',',$_POST['user']);   
//$item = explode(',',$_POST['item']);  
//$quantity = explode(',',$_POST['quantity']);    
//$day = explode(',',$_POST['day']); 

if(isset($_POST['xl']))         {                      //   ConvertSQLDate($myrow[1]); 
                                                           
$sourcetypeid=$_POST['sourcetypeid'];   $sourcetypefrom=FormatDateForSQL($_POST['sourcetypefrom']) ;   $sourcetypeto=FormatDateForSQL($_POST['sourcetypeto']) ; 
 if($_POST['sourcetypeid']=="ALL"){      
$header= "Slno".","."NAME & ADDRESS".","."CONTACT NO & EMAIL".","."DATE & TEAM".","."CUST-TYPE".","."PAYMENT DETAILS".","."REMARKS"."\n";
  $sql1="SELECT bio_cust.cust_id,
  bio_cust.custname,
  bio_cust.houseno,
  bio_cust.housename,
  bio_cust.custphone,
  bio_cust.custmob,
  bio_enquirytypes.enquirytype,
  bio_cust.district, 
  bio_leads.leaddate,
  bio_leadteams.teamname,
  bio_leadsources.sourcename,bio_cust.custmail,bio_leads.advanceamount
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,bio_enquirytypes  
WHERE bio_cust.cust_id=bio_leads.cust_id AND bio_leadteams.teamid=bio_leads.teamid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid 
AND bio_leadsources.id=bio_leads.sourceid
AND bio_leads.leaddate BETWEEN '$sourcetypefrom' AND '$sourcetypeto'"; 
      }
 else{     
$header= "Slno".","."NAME & ADDRESS".","."CONTACT NO & EMAIL".","."DATE & TEAM".","."CUST-TYPE".","."PAYMENT DETAILS".","."REMARKS"."\n";
  $sql1="SELECT bio_cust.cust_id,
  bio_cust.custname,
  bio_cust.houseno,
  bio_cust.housename,
  bio_cust.custphone,
  bio_cust.custmob,
  bio_enquirytypes.enquirytype,
  bio_cust.district, 
  bio_leads.leaddate,
  bio_leadteams.teamname,
  bio_leadsources.sourcename,bio_cust.custmail,bio_leads.advanceamount
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,bio_enquirytypes  
WHERE bio_cust.cust_id=bio_leads.cust_id AND bio_leadteams.teamid=bio_leads.teamid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid 
AND bio_leadsources.id=bio_leads.sourceid AND bio_leads.sourceid=".$sourcetypeid." 
AND bio_leads.leaddate BETWEEN '$sourcetypefrom' AND '$sourcetypeto'";     }

   $result1=DB_query($sql1,$db) ;
   $i=0;
   $slno=0;
   $first='';
   $second='';
   $third='';
   while ($myrow1 = DB_fetch_array($result1)) {
   $slno++;
    

$name= $myrow1[1]."-".$myrow1[2]."-".$myrow1[3]."-".$myrow1[7]; 
$contactno=$myrow1[4]."-".$myrow1[5];   
$dateteam=$myrow1[8]."-".$myrow1[9]; 

//$data=$data.$slno.",".$name.",".$contactno.",".$dateteam.",".$myrow1[6].","."\n";   
  
$first=$slno.",".$myrow1[1].",".$myrow1[4].",".$myrow1[8].",".$myrow1[6].",".$myrow1[12].","."\n";  
$second=","." ".$myrow1[3].",".$myrow1[5].",".$myrow1[9]."\n";  
$third=","." ".$myrow1[7].",".$myrow1[11]."\n";   

$data=$data.$first.$second.$third;     
     $i++; 

    }      
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";   

}                                                                  
?>
