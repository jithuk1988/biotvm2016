<?php
  $PageSecurity = 11;

  include('includes/session.inc');
  $date=date('d/m/Y'); 
  $filename=$date."Schedule.csv";
        $sql3="SELECT mrpdemands.demandid,     
                    mrpdemands.mrpdemandtype,     
                    mrpdemands.quantity,     
                    mrpdemands.duedate,     
                    mrpdemands.statusid,
                    dev_mrpdemandstatus.status
           FROM mrpdemands,dev_mrpdemandstatus
           WHERE mrpdemands.stockid='".$_GET['id']."'    AND
                 mrpdemands.statusid=dev_mrpdemandstatus.statusid";
    $result3=DB_query($sql3,$db);

    $sql4="SELECT description,
                  longdescription,
                  units 
           FROM stockmaster
           WHERE stockid='".$_GET['id']."'";
    $result4=DB_query($sql4,$db);
    $myrow4=DB_fetch_array($result4);
    
    $header= "$myrow4[1]"."\n"."Slno".","."Demand Date".","."Quantity".","."Status"."\n";"\n";
    $data='';
    $slno=1;
    while($myrow3=DB_fetch_array($result3,$db))     {
    
    $data= $data.$slno.",".$myrow3['duedate'].",".$myrow3['quantity'].",".$myrow3['status']."\n";    
    $slno++;    
    } 
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";     
?>
