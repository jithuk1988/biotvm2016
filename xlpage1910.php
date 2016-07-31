<?php
$PageSecurity = 80;

include('includes/session.inc');

$filename1=Date($_SESSION['DefaultDateFormat']);
$filename ='lead_details'.$filename1;
function xlsBOF() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    return;
}

function xlsEOF() {
    echo pack("ss", 0x0A, 0x00);
    return;
}

function xlsWriteNumber($Row, $Col, $Value) {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
return;
}
if(isset($_POST['xl']))
{

$sourcetypeid=$_POST['sourcetypeid'];
$sourcetypefrom=FormatDateForSQL($_POST['sourcetypefrom']);
$sourcetypeto=FormatDateForSQL($_POST['sourcetypeto']);
$office=$_POST['off'];
$place=$_POST['place'];


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
$sql1.=" ORDER BY leadid DESC";

 // Query Database

    // Send Header
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=$filename.xls "); // �?ล้วนี่�?็ชื่อไฟล์
    header("Content-Transfer-Encoding: binary ");

    // XLS Data Cell

                xlsBOF();
                xlsWriteLabel(1,0,"Sl.No");
                xlsWriteLabel(1,1,"Lead NO : ");
                xlsWriteLabel(1,2,"Lead Date");
                xlsWriteLabel(1,3,"Customer");
                xlsWriteLabel(1,4,"Phone");
                xlsWriteLabel(1,5,"Email");
                xlsWriteLabel(1,6,"Place");
                xlsWriteLabel(1,7,"Team");
                xlsWriteLabel(1,8,"Payment");
                xlsWriteLabel(1,9,"Remarks");
                $xlsRow = 2;
    $result1=DB_query($sql1,$db) ;
   $i=0;
   $slno=0;

   while ($myrow1 = DB_fetch_array($result1)) {
         $name= $myrow1[custname];
         if($myrow1[houseno]) {$name.="\r\n$myrow1[houseno]";}
         if($myrow1[housename]) {$name.="\r\n$myrow1[housename]";}
         if($myrow1[district]) {$name.="\r\n$myrow1[district]";}
         $date1=$myrow1[leaddate];
         $dd=explode("-",$date1);
         $date="$dd[2]/$dd[1]/$dd[0]";
         $team=$myrow1[teamname];
         $place="$myrow1[area1]";
         if ($myrow1[advance]==0) {$advance="";} else {$advance=$myrow1[advance];}
         if ($myrow1[contactno]==0) {$contactno="";} else {$contactno=$myrow1[contactno];}
         if ($myrow1[custmail]==0) {$custmail="";} else {$custmail=$myrow1[custmail];}
         ++$slno;
                 // ++$i;
                          xlsWriteNumber($xlsRow,0,"$slno");
                          xlsWriteNumber($xlsRow,1,"$myrow1[leadid]");
                          xlsWriteLabel($xlsRow,2,"$date");
                          xlsWriteLabel($xlsRow,3,"$name");
                          xlsWriteLabel($xlsRow,4,"$contactno");
                          xlsWriteLabel($xlsRow,5,"$custmail");
                          xlsWriteLabel($xlsRow,6,"$place");
                          xlsWriteLabel($xlsRow,7,"$team");
                          xlsWriteLabel($xlsRow,8,"$advance");
                          xlsWriteLabel($xlsRow,9,"$myrow1[remarks]");
                    $xlsRow++;
                    }
                     xlsEOF();
                 exit();




/* $name= $myrow1[custname];
if($myrow1[houseno]) {$name.="\r".$myrow1[houseno];}
if($myrow1[housename]) {$name.="\r".$myrow1[housename];}
if($myrow1[district]) {$name.="\r".$myrow1[district];}
$name.="<br>";
echo $name;
$contactno=$myrow1[custphone]." ".$myrow1[custmob];
$date=$myrow1[leaddate]; 
$team=$myrow1[teamname];
$place=$myrow1[area1]."-".$myrow1[area2]; */
  
//$header= "Slno".","."LeadNo".","."DATE".","."NAME & ADDRESS".","."CONTACT NO".","."EMAIL".","."PLACE".","."TEAM".","."CUST-TYPE".","."PAYMENT DETAILS".","."REMARKS"."\n";   
//$first=$slno.",".$myrow1[leadid].",".$date.",".$name.",".$contactno.",".$myrow1[custmail].",".$place.",".$team.",".$myrow1[enqtype].",".$myrow1[advance].",".$myrow1[remarks]."\n";
// $first=$slno.";".$myrow1[leadid].";".$date.";".$name.";".$contactno.";".$myrow1[custmail].";".$place.";".$team.";".$myrow1[enqtype].";".$myrow1[advance].";".$myrow1[remarks]."\n";

//$second=",,,".$myrow1[houseno].",".$myrow1[custmob].","."\n";
//$third=",,,"." ".$myrow1[district]."\n";

// $data=$data.$first; //.$second.$third;
     $i++; 
//echo $data;
    }      
/* header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";   */





?>
