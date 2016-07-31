<?php
  $PageSecurity = 80;

include('includes/session.inc');
$filename1='Advance_Institution_'.Date('d-m-y').'__'.date('H:i');
$filename =$filename1.'.csv';

if(isset($_POST['submit']))
{ 
    $fdate=FormatDateForSQL($fdate=$_POST['datefrm']);
    $tdate=FormatDateForSQL($tdate=$_POST['dateto']);
    
    $title1=",,,,,"."Advance Recieved from Institution Customers"."\n"; 
    $title2=",,,,,"."From Date".","."$fdate".","."To Date".","."$tdate"."\n";
    $header= "Slno".","."Leadid".","."Customer Name".","."Place".","."Advance Amount"."\n";
    
    $sql="select bio_cust.cust_id,bio_leads.leadid,
    bio_cust.custname,bio_cust.area1,
    bio_leads.advanceamount
    from bio_leads,bio_cust  
    where bio_cust.cust_id=bio_leads.cust_id
    and leaddate between  '$fdate' and '$tdate'
    and bio_leads.advanceamount!=0 and bio_leads.enqtypeid=2";
    
    $result=DB_query($sql,$db);
    $slno=0;
    
    while($row=DB_fetch_array($result))
{
     $slno++;   
     $lead=$row['leadid'];
     $name=$row['custname'];
     $place=$row['area1'];
     $place=str_replace(',',' ',$place);
     $advance=$row['advanceamount'];
     
     $data=$data.$slno.",".$lead.",".$name.",".$place.",".$advance.","."\n";
}

    header("Content-type: application/x-msdownload"); 
    header("Content-Disposition: attachment; filename=$filename"); 
    header("Pragma: no-cache"); 
    header("Expires: 0");  
    echo "$title1\n$title2\n$header\n$data";   

}

?>
