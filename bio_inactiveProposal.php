<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('directPrint');




 $sql34="Select value from bio_changepolicy where policyname='No of Days to became Proposal Inactive' ";
                          $result34=DB_query($sql34,$db);   
     $myrow34=DB_fetch_array($result34,$db);
      $noday=$myrow34[0];
$sql1="SELECT 
     bio_proposal.leadid, 
     bio_proposal.propdate,
     bio_leads.cust_id, 
     bio_cust.custname, 
     bio_status.biostatus, 
     bio_leads.teamid,
     bio_leadteams.teamname,
      bio_cust.custmob,
     bio_cust.custmail 
     FROM 
     bio_status,
     bio_leads, 
     bio_cust, 
     bio_leadteams, 
     bio_proposal 
     WHERE 
     bio_proposal.leadid=bio_leads.leadid 
     AND bio_cust.cust_id=bio_leads.cust_id 
     AND bio_leadteams.teamid=bio_leads.teamid
     AND bio_leads.leadstatus=1 
     AND bio_proposal.propdate < (CURDATE() - INTERVAL $noday DAY) 
     AND bio_status.statusid=bio_leads.leadstatus";


$result1=DB_query($sql1,$db);

//
//                   $sql1="SELECT * FROM bio_leads
//        WHERE leadstatus=1 and enqtypeid=1" ;
//$result1=DB_query($sql1,$db);


if(!isset($_POST['excelview'])){
    
include('includes/header.inc'); 
include('includes/sidemenu.php');     
          echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">INACTIVE PROPOSALS</font></center>';   
    

echo "<table style='width:60%'><tr><td>";
echo "<fieldset style='width:97%;'>";     
    
    
    
//echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    //echo'<table width="80%">';
    echo "<legend><h3>Showing all Active Proposals</h3>";
echo "</legend>";

    echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
       echo "<div style='height:350px; overflow:auto;'>";
echo "<table style='border:0px solid #F0F0F0;width:100%'>";

     // echo "</legend>";
    echo'<tr><th class="viewheader">slno</td>';
    echo'<th class="viewheader">Lead no</td>';
    echo'<th class="viewheader">Customer Name</td>';
    echo'<th class="viewheader">Team</td>';
    echo'<th class="viewheader">Lead Date</td>';
    echo'<th class="viewheader">Phone No </th>';
    echo'<th class="viewheader">Email</th>'; 
    
    
     echo'<th class="viewheader">Change Status</th>';  
    echo'</tr>';
     $slno=1; 
     $k=0;
    while($myrow3=DB_fetch_array($result1,$db))     {
    
      //$date1=$myrow3['leaddate'];
//      $date2=date("Y-m-d");

//      $datearr1 = split("-",$date1); 
//      $datearr2 = split("-",$date2); 
//      
//      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
//      if($date_diff<10){  
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
     
      
    echo'<tr>';
    $lead=$myrow3['leadid'];
    echo'<td>'.$slno.'</td>';
//    echo'<td>'.$myrow3['reqno'].'</td>';
    echo'<td>'.$myrow3['leadid'].'</td>';
    echo'<td>'.$myrow3['custname'].'</td>';
    echo'<td>'.$myrow3['teamname'].'</td>';
    echo'<td>'.$myrow3['leaddate'].'</td>';
     echo'<td>'.$myrow3['custmob'].'</td>';
    echo'<td>'.$myrow3['custmail'].'</td>';
    
//    echo'<td>'.$myrow3['status'].'</td>';
     echo '<td><a style=cursor:pointer; id='.$myrow3['leadid'].' onclick=showactive(this.id)>' . _('Reminder letter') . '</td></a>';  
    echo "</tr>";
    $slno++;   
      }
    
    //}
//    echo'<tr><td><a href="bio_pendingleads_A5p?item='.$itemcode.' &view=1 &id=1 &season='.$season.'">view all WOs against this item</a></td></tr>';
   // echo"</div>";
//    echo'</table>';
//    echo'<input type="submit" name="excelview" id=2 value="view as excel">';
//    echo'</form>';
//    echo "</td></tr></fieldset></table>";
//     include('includes/footer.inc');  


echo"</div>";
    echo'</table>';
    
    
    
    
    
   if($slno==1){
    echo'<input type="submit" disabled name="excelview" id=2 value="view as excel">';
    
    }
    else
    {
              echo'<input type="submit" name="excelview" id=2 value="view as excel">';

    }
    echo'</form>';
    echo "</td></tr></fieldset></table>";







       print'<script>
    function showactive(str1){
       
         window.location="bio_proposal_reminderletter.php?lead=" + str1;
    }
    </script>';

   
  }  
    
 if(isset($_POST['excelview'])){ 
$filename="sdfsdfsdf.csv";

    $header= "Slno".","."Lead No".","."Customer Name".","."Team".","."Lead Date".","."Phone no".","."Email."."\n";"\n";
    $data='';
    $slno=1;
    
        while($myrow3=DB_fetch_array($result1,$db))     {
            
           // $date1=$myrow3['leaddate'];
//      $date2=date("Y-m-d");

//      $datearr1 = split("-",$date1); 
//      $datearr2 = split("-",$date2); 
//      
//      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
//     $sql8="SELECT value from bio_changepolicy where  policyname='No of Days to became a proposal inactive'";
//      $result4=DB_query($sql8,$db); 
//      $myrow=DB_fetch_array($result4,$db); 
//              
//      if($date_diff>=$myrow['value']){         
//      
//      
//      
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
    
   $data= $data.$slno.",".$myrow3['leadid'].",".$myrow3['custname'].",".$myrow3['teamname'].",".$myrow3['propdate'].",".$myrow3['custmob'].",".$myrow3['custmail'].",\n";    
    $slno++;    
      }
        //} 
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";  
 }    
 
?>
