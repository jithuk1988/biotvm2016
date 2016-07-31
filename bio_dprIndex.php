<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _(' Enclosure for Detailed Project Report ');
include('includes/header.inc');
  $lead_ID=$_GET['leadid'];
$dprid=$_GET['dprid'];
$plantsize=$_GET['plsize']; 
   echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">DPR INDEX</font></center>'; 
    

echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:50%';><tr><td>";  
echo "<fieldset style='width:70%;height:200px'>";
echo "<legend><h3>List of Documents</h3></legend>";   

// echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
 echo '<form method="post" action="bio_dpr_coveringletter200.php">';
   
   echo '<table><tr><td style="background:#8080FF;color:white;">';
   echo'<input type="checkbox" id="covlttr" name="covlttr" > Covering Letter'; 
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo'<input type="checkbox" id="techdtls" name="techdtls" > Technical Details';
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo'<input type="checkbox" id="terms" name="terms" > Terms and Conditions';
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo'<input type="checkbox" id="finance" name="finance" > Financial Details';
   echo '</td></tr>';
   echo "<td>";
   echo '<input type="submit" name="Print" id="print" value="Print">';  
   echo "</td>";    
   echo '</table>';
   echo"<input type='hidden' name='LeadID' id='leadid' value='$lead_ID'>"; 
  echo"<input type='hidden' name='DprID' id='dprid' value='$dprid'>";
  echo"<input type='hidden' name='PlSize' id='plsize' value='$plantsize'>";
   
 echo '</form>'; 
echo "</fieldset>";
echo "</td></tr></table>";

include('includes/footer.inc');  
?>
