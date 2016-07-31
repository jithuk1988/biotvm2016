<?php
$PageSecurity = 80;

include('includes/session.inc');

$filename1=date('H:i');
$filename ='lead_details'.'_'.Date($_SESSION['DefaultDateFormat']).'_'.$filename1.'.csv';           
if(isset($_POST['xl']))         
{                      
                                                           
$sourcetypeid=$_POST['sourcetypeid'];   
$sourcetypefrom=FormatDateForSQL($_POST['sourcetypefrom']);   
$sourcetypeto=FormatDateForSQL($_POST['sourcetypeto']); 
$office=$_POST['off']; 
$place=$_POST['place'];
$etype=$_POST['EnqType'];


$header= "Slno".","."LeadNo".","."DATE".","."NAME & ADDRESS".","."CONTACT NO".","."EMAIL".","."PLACE".","."TEAM".","."CUST-TYPE".","."PAYMENT DETAILS".","."REMARKS"."\n";
  $sql1="SELECT bio_cust.cust_id AS custid,  
  bio_cust.custname AS custname,               
  bio_cust.houseno AS houseno,               
  bio_cust.housename AS housename,
  bio_cust.custphone AS custphone,
  bio_cust.custmob AS custmob,
  bio_enquirytypes.enquirytype AS enqtype,
  bio_cust.district AS districtid,
  bio_leads.leadid AS leadid, 
  bio_leads.leaddate AS leaddate,
  bio_leadteams.teamname AS teamname,
  bio_leadsources.sourcename AS sourcename,
  bio_cust.custmail AS custmail,
  bio_leads.advanceamount AS advance,
  bio_cust.state AS state,
  bio_district.district AS district,
  bio_cust.area1 AS area1,
  bio_cust.area2 AS area2,
  bio_office.id AS officeid,
  bio_office.office AS office,
  bio_leads.remarks AS remarks
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,
bio_enquirytypes,
bio_district,
bio_office  
WHERE bio_cust.cust_id=bio_leads.cust_id 
AND bio_leadteams.teamid=bio_leads.teamid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid 
AND bio_leadsources.id=bio_leads.sourceid  
AND bio_district.did=bio_cust.district
AND bio_district.stateid=bio_cust.state
AND bio_district.cid=bio_cust.nationality
AND bio_office.id=bio_leadsources.officeid
";
if($sourcetypeid!='ALL')
{
    $sql1.=" AND bio_leads.sourceid='".$sourcetypeid."'";
}
if(($sourcetypefrom!="--")&&($sourcetypeto!="--"))
{
    $sql1.=" AND bio_leads.leaddate BETWEEN '$sourcetypefrom' AND '$sourcetypeto'";
}
if($place!="")
{
    $sql1.=" AND bio_cust.area1 LIKE '%$place%' ";
}
if($office!="")
{
      $sql1.=" AND bio_leadsources.officeid='$office'";
}
if($etype!="")
{
//    echo"hiiiii";
      $sql1.=" AND bio_leads.enqtypeid='$etype'";
}

$sql1.=" ORDER BY leadid DESC";

//echo $sql1;
 
    

   $result1=DB_query($sql1,$db) ;
   $i=0;
   $slno=0;
   $first='';
   $second='';
   $third='';
   while ($myrow1 = DB_fetch_array($result1)) {
   $slno++;
    

$name= $myrow1[custname];
if($myrow1[houseno]) {$name.="-".$myrow1[houseno];}
if($myrow1[housename]) {$name.="-".$myrow1[housename];}
if($myrow1[district]) {$name.="-".$myrow1[district];}
 
$contactno=$myrow1[custphone]."-".$myrow1[custmob];   
$date=$myrow1[leaddate]; 
$team=$myrow1[teamname];

$place=$myrow1[area1]."-".$myrow1[area2];
$place=str_replace(',',' ',$place); 

$remarks=$myrow1[remarks];  
$remarks=str_replace("\r\n",' ',$remarks);

//$header= "Slno".","."LeadNo".","."DATE".","."NAME & ADDRESS".","."CONTACT NO".","."EMAIL".","."PLACE".","."TEAM".","."CUST-TYPE".","."PAYMENT DETAILS".","."REMARKS"."\n";   
$first=$slno.",".$myrow1[leadid].",".$date.",".$myrow1[custname].",".$myrow1[custphone].",".$myrow1[custmail].",".$place.",".$team.",".$myrow1[enqtype].",".$myrow1[advance].",".$remarks."\n";  

$second=",,,".$myrow1[housename].",".$myrow1[custmob].","."\n";  
$third=",,,"." ".$myrow1[district]."\n";   

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
