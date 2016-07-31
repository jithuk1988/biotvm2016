<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('ActiveCP');

$sql34="Select value from bio_changepolicy where policyname='No of Days to became CP inactive' ";
                          $result34=DB_query($sql34,$db);   
     $myrow34=DB_fetch_array($result34,$db);  
    $noday=$myrow34[0];
    
   $sql1="   SELECT   bio_conceptproposal.lead_id,
         bio_conceptproposal.createdon,
         bio_leads.cust_id, 
         bio_cust.custname,
         bio_status.biostatus,
         bio_conceptproposal.team_id,
         bio_leadteams.teamname,
         bio_cust.custmob,
         bio_cust.custmail
         
FROM    bio_status,
        bio_leads,
        bio_cust,
        bio_leadteams,
        bio_conceptproposal
WHERE   bio_conceptproposal.lead_id=bio_leads.leadid AND bio_conceptproposal.createdon < (CURDATE() - INTERVAL $noday DAY)  AND bio_leadteams.teamid=bio_conceptproposal.team_id AND bio_leads.leadstatus=4 AND bio_status.statusid=bio_leads.leadstatus AND 
bio_cust.cust_id=bio_leads.cust_id ";                              
    
 $result1=DB_query($sql1,$db);   
if(!isset($_POST['excelview'])) {
  include('includes/header.inc'); 
   
  echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">CONCEPT PROPOSAL ACTIVE LEADS</font></center>';
         



           
 echo "<table style='width:60%'><tr><td>";
 echo "<fieldset style='width:97%;'>";     
 echo "<legend><h3>Showing all Active Leads of Concept Proposal</h3>";
 echo "</legend>";  
 
 echo '<form name="CPactiveLeads"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
 echo "<div style='height:350px; overflow:auto;'>";
 echo "<table style='border:0px solid #F0F0F0;width:100%'>";
//    echo'<tr><td colspan=6>Showing all Pending Leads</td>';
    echo'<tr><th class="viewheader">slno</th>';
    echo'<th class="viewheader">Lead no</th>';
    echo'<th class="viewheader">Customer Name</th>';
    echo'<th class="viewheader">Team</th>';
    echo'<th class="viewheader">CP Date</th>';
    echo'<th class="viewheader">Phone No </th>';
    echo'<th class="viewheader">Email</th>'; 
    
    echo'<th class="viewheader">Lead Status</th>';
    echo'<th class="viewheader">Change Status</th>';  
    echo'</tr>';
    $slno=1;
    $k=0;                       
        while($myrow3=DB_fetch_array($result1,$db))     {
    
      //$sql3="SELECT bio_conceptproposal.createdon FROM bio_conceptproposal
//             WHERE bio_conceptproposal.lead_id=".$myrow3['leadid'];
//      $result3=DB_query($sql3,$db); 
//      $myrow1=DB_fetch_array($result3,$db);         
//      
//      $date1=$myrow1['createdon'];
//      $date2=date("Y-m-d");

//      $datearr1 = split("-",$date1); 
//      $datearr2 = split("-",$date2); 
//      
//      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
//      
//      $sql8="SELECT value from bio_changepolicy where  policyname='No of Days to became CP passive'";
//      $result4=DB_query($sql8,$db); 
//      $myrow=DB_fetch_array($result4,$db); 
//        
//     if($date_diff<=$myrow['value']){          
//     
//     $sql2="SELECT teamname FROM bio_leadteams
//            WHERE teamid=".$myrow3['teamid'];
//     $result2=DB_query($sql2,$db);   
//     $myrow2=DB_fetch_array($result2,$db);
//        
//    $sql4="SELECT custname FROM bio_cust
//        WHERE cust_id=".$myrow3['cust_id'];
//     $result4=DB_query($sql4,$db);   
//     $myrow4=DB_fetch_array($result4,$db);
//     
//     $sql5="SELECT biostatus FROM bio_status
//        WHERE statusid=".$myrow3['leadstatus'];
//     $result5=DB_query($sql5,$db);   
//     $myrow5=DB_fetch_array($result5,$db);
     
    if ($k==1)
    {
        echo '<tr class="EvenTableRows">';
        $k=0;          
    }
    else 
    {
        echo '<tr class="OddTableRows">';
        $k=1;     
    }    
$lead=$myrow3['lead_id'];    
    echo'<td>'.$slno.'</td>';
//    echo'<td>'.$myrow3['reqno'].'</td>';
    echo'<td>'.$myrow3['lead_id'].'</td>';
    echo'<td>'.$myrow3['custname'].'</td>';
    echo'<td>'.$myrow3['teamname'].'</td>';
    echo'<td>'.ConvertSQLDate($myrow3['createdon']).'</td>';
     echo'<td>'.$myrow3['custmob'].'</td>';
    echo'<td>'.$myrow3['custmail'].'</td>';
    
    
    echo'<td align="center">'.$myrow3['biostatus'].'</td>';
    echo '<td><a style=cursor:pointer; id='.$myrow3['lead_id'].' onclick=showactive(this.id)>' . _('Select') . '</td></a>';   
//    echo "<td><a style=cursor:pointer; id=".$row1['leadid']." onclick=select(this.id)>View</a></td>";
    
    echo '</tr>';

    $slno++;   
        } 
 
 
 echo"<table>";
 echo"</div>";
 
 
 
 
if($slno==1){
    echo'<input type="submit" disabled name="excelview" id=2 value="view as excel">';
    
    }
    else
    {
              echo'<input type="submit" name="excelview" id=2 value="view as excel">';

    }
    echo'</form>';
    echo "</td></tr></fieldset></table>";
   
 //include('includes/footer.inc');      
  
   print'<script>
    function showactive(str1){
       
         window.location="bio_activeCP_changestatus.php?lead=" + str1;
    }
    </script>';
    
    
}  


  
   if(isset($_POST['excelview'])){ 
   $filename="sdfsdfsdf.csv";

$header= "Slno".","."Lead No".","."Customer Name".","."Team".","."Lead Date".","."Phone no".","."Email."."\n";"\n";
    $data='';
    $slno=1;
    
        while($myrow3=DB_fetch_array($result1,$db))     {
      //            
//      $sql3="SELECT bio_conceptproposal.createdon FROM bio_conceptproposal
//             WHERE bio_conceptproposal.lead_id=".$myrow3['leadid'];
//      $result3=DB_query($sql3,$db); 
//      $myrow1=DB_fetch_array($result3,$db);         
//      
//      $date1=$myrow1['createdon'];
//      $date2=date("Y-m-d");

//      $datearr1 = split("-",$date1); 
//      $datearr2 = split("-",$date2); 
//      
//      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]);
//      
//      $sql7="SELECT value from bio_changepolicy where  policyname='No of Days to became CP passive'";
//      $result6=DB_query($sql7,$db); 
//      $myrow=DB_fetch_array($result6,$db); 
//              
//      if($date_diff<=$myrow['value']){
//            
//            
//            $sql2="SELECT teamname FROM bio_leadteams
//            WHERE teamid=".$myrow3['teamid'];
//     $result2=DB_query($sql2,$db);   
//     $myrow2=DB_fetch_array($result2,$db);
//        
//    $sql4="SELECT custname FROM bio_cust
//        WHERE cust_id=".$myrow3['cust_id'];
//     $result4=DB_query($sql4,$db);   
//     $myrow4=DB_fetch_array($result4,$db);
//    
     
   $data= $data.$slno.",".$myrow3['lead_id'].",".$myrow3['custname'].",".$myrow3['teamname'].",".ConvertSQLDate($myrow3['createdon']).",".$myrow3['custmob'].",".$myrow3['custmail']."\n";
    $slno++;    
      }
        
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";  
 }                             
?>
