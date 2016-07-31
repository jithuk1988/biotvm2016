<?php
 $PageSecurity = 80;
include('includes/session.inc');

echo"<form action='xlpage.php' method='post'>";
  $sourcetypeid=$_GET['id'] ;
  $sourcetypefrom=$_GET['from'];
  $sourcetypeto=$_GET['to'];
  //$sourcetypeid=$_GET['id'] ;   
// $sql1="SELECT bio_cust.cust_id,
//  bio_cust.custname,
//  bio_cust.houseno,
//  bio_cust.housename,
//  bio_cust.custphone,
//  bio_cust.custmob,
//  bio_enquirytypes.enquirytype,
//  bio_cust.district,
//  bio_leads.leaddate,
//  bio_leadteams.teamname,
//  bio_leadsources.sourcename
//FROM bio_cust,
//bio_leads,
//bio_leadteams,
//bio_leadsources,bio_enquirytypes  
//WHERE bio_cust.cust_id=bio_leads.cust_id AND bio_leadteams.teamid=bio_leads.teamid 
//AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid 
//AND bio_leadsources.id=bio_leads.sourceid AND bio_leads.sourceid=".$sourcetypeid;
//   $result1=DB_query($sql1,$db) ;
//   $i=0;
//   while ($myrow1 = DB_fetch_array($result1)) {
//    $custname[$i]=$myrow1[1];    
//    $houseno[$i]=$myrow1[2];
//    $housename[$i]=$myrow1[3]; 
//    $custphone[$i]=$myrow1[4];      
//    $custmob[$i]=$myrow1[5];
//    $custtyp[$i]=$myrow1[6]; 
//    $district[$i]=$myrow1[7];
//    $leaddate[$i]=$myrow1[8];
//    $leadteam[$i]=$myrow1[9];       
//     
//     
//     
//     
//     $i++; 

//    }
//   $founter=$i+1; 
//$custname1 = implode(',',$custname);
//$houseno1 = implode(',',$houseno); 
//$housename1 = implode(',',$housename); 
//$custphone1 = implode(',',$custphone); 
//$custmob1 = implode(',',$custmob);
//$custtyp1 = implode(',',$custtyp);
//$district1 = implode(',',$district);
//$leaddate1 = implode(',',$leaddate);
//$leadteam1 = implode(',',$leadteam);

echo"<input type='hidden' name='sourcetypeid' value=$sourcetypeid>"; 
echo"<input type='hidden' name='sourcetypefrom' value=$sourcetypefrom>";   
echo"<input type='hidden' name='sourcetypeto' value=$sourcetypeto>";   
//echo"<input type='hidden' name='custname' value=$custname1>";
//echo"<input type='hidden' name='houseno' value=$houseno1>"; 
//echo"<input type='hidden' name='housename' value=$housename1>"; 
//echo"<input type='hidden' name='custphone' value=$custphone1>"; 
//echo"<input type='hidden' name='custmob' value=$custmob1>"; 
//echo"<input type='hidden' name='custtyp' value=$custtyp1>";
//echo"<input type='hidden' name='district' value=$district1>";
//echo"<input type='hidden' name='leaddate' value=$leaddate1>"; 
//echo"<input type='hidden' name='leadteam' value=$leadteam1>"; 
   //echo '</tr>';
   
 
	echo"<input type='submit' id='xl' name='xl' value='Export'></form>";
   
?>
