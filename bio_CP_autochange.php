<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Inactive CP'); 
include('includes/header.inc'); 

echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">INACTIVE CP</font></center>';  
  
  
  
  if(isset($_POST['submit']))
     
 {
   $check=$_POST['autocheck'];
//   print_r($check);
   foreach($check as $id)  {
       $autocheck.=$id.",";
   } 
 $checkid=substr($autocheck,0,-1) ;
 $sql6="UPDATE bio_leads SET leadstatus=18 where leadid=".$_POST['LeadID'];
  
  $sql5="UPDATE bio_leads SET leadstatus=18
                WHERE leadid IN ($checkid)";
     $result5=DB_query($sql5,$db);

                         ?>
           <script language="JavaScript">

/* opener.document.stock.sid.value=choose; */
window.close();

</script>

<?php
               
 }  

echo "<table style='width:60%'><tr><td>";
echo "<fieldset style='width:97%;'>";     
echo "<legend><h3>Showing all Passive Leads</h3>";
echo "</legend>";   
echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    echo "<div style='height:350px; overflow:auto;'>";
    echo "<table style='border:0px solid #F0F0F0;width:100%'>";   
    echo'<tr><th class="viewheader">slno</th>';
    echo'<th class="viewheader">Lead no</th>';
    echo'<th class="viewheader">Customer Name</th>';
    echo'<th class="viewheader">Team</th>';
    echo'<th class="viewheader">Lead Date</th>';
      echo'<th class="viewheader">Phone No </th>';
    echo'<th class="viewheader">Email</th>'; 
    
    
    echo'</tr>';


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
 $slno=1;
    $k=0; 
  
 while($myrow3=DB_fetch_array($result1,$db))     {
  
   
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
     
  echo'<td><input type="checkbox" checked="checked" id="check"'.$slno.' name="autocheck[]" value='.$myrow3['lead_id'].'></td>';    
     echo'<td>'.$myrow3['lead_id'].'</td>';
    echo'<td>'.$myrow3['custname'].'</td>';
    echo'<td>'.$myrow3['teamname'].'</td>';
    echo'<td>'.$myrow3['propdate'].'</td>';
      echo'<td>'.$myrow3['custmob'].'</td>';
    echo'<td>'.$myrow3['custmail'].'</td>';
    
    echo'</tr>';    
    $slno++;   
      }
  
   echo"</div>";
  if ($slno==1)
  {
    echo'<td><input type=submit disabled name=submit value="' . _('ADD TO PASSIVE LIST') . '" ></td>';
  }
  else
         {
                 echo'<td><input type=submit  name=submit value="' . _('ADD TO PASSIVE LIST') . '" ></td>';

         }
    
    
    echo'</table>';

     
    echo'</form>';   
?>
