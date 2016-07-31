<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('directPrint');

$sql1="SELECT * FROM bio_leads
        WHERE leadstatus=0" ;
$result1=DB_query($sql1,$db);
if(!isset($_POST['excelview'])){
echo '<form name="pendingLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    echo'<table width="80%">';
    echo'<tr><td colspan=6>Showing all Pending Leads</td>';
    echo'<tr><td class="viewheader">slno</td>';
//    echo'<td class="viewheader">SR no</td>';
    echo'<td class="viewheader">Lead no</td>';
    echo'<td class="viewheader">Customer Name</td>';
    echo'<td class="viewheader">Team</td>';
    echo'<td class="viewheader">Lead Date</td>';
//    echo'<td class="viewheader">WO status</td>';
    echo'</tr>';
    $slno=1;
    while($myrow3=DB_fetch_array($result1,$db))     {
     $sql2="SELECT teamname FROM bio_leadteams
            WHERE teamid=".$myrow3['teamid'];
     $result2=DB_query($sql2,$db);   
     $myrow2=DB_fetch_array($result2,$db);
        
    $sql4="SELECT custname FROM bio_cust
        WHERE cust_id=".$myrow3['cust_id'];
     $result4=DB_query($sql4,$db);   
     $myrow4=DB_fetch_array($result4,$db);
    
    
        
    echo'<tr>';
    echo'<td>'.$slno.'</td>';
//    echo'<td>'.$myrow3['reqno'].'</td>';
    echo'<td>'.$myrow3['leadid'].'</td>';
    echo'<td>'.$myrow4['custname'].'</td>';
    echo'<td>'.$myrow2['teamname'].'</td>';
    echo'<td>'.$myrow3['leaddate'].'</td>';
//    echo'<td>'.$myrow3['status'].'</td>';
    echo'</tr>';    
    $slno++;   
    }
//    echo'<tr><td><a href="bio_pendingleads_A5p?item='.$itemcode.' &view=1 &id=1 &season='.$season.'">view all WOs against this item</a></td></tr>';
    echo'</table>';

     echo'<input type="submit" name="excelview" id=2 value="view as excel">';
    echo'</form>';
}

   
    
    
 if(isset($_POST['excelview'])){ 
$filename='Pending_Leads_'.Date('d-m-y').'__'.date('H:i').csv;

    $header= "Slno".","."Lead No".","."Customer Name".","."Team".","."Lead Date".","."\n";"\n";
    $data='';
    $slno=1;
    
        while($myrow3=DB_fetch_array($result1,$db))     {
            $sql2="SELECT teamname FROM bio_leadteams
            WHERE teamid=".$myrow3['teamid'];
     $result2=DB_query($sql2,$db);   
     $myrow2=DB_fetch_array($result2,$db);
        
    $sql4="SELECT custname FROM bio_cust
        WHERE cust_id=".$myrow3['cust_id'];
     $result4=DB_query($sql4,$db);   
     $myrow4=DB_fetch_array($result4,$db);
    
    $data= $data.$slno.",".$myrow3['leadid'].",".$myrow4['custname'].",".$myrow2['teamname'].",".$myrow3['leaddate']."\n";    
    $slno++;    
    } 
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";  
 }    
?>