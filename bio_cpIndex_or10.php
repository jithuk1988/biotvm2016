<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _(' Enclosure for Concept Proposal ');
include('includes/header.inc');

if($_GET['leadid']!=""){
  $lead_ID=$_GET['leadid'];
  $cpid=$_GET['cpid'];  
}
elseif($_SESSION['LeadID']!=""){
  $lead_ID=$_SESSION['LeadID'];
  $cpid=$_SESSION['ConceptProposalID'];  
}



   echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">CONCEPT PROPOSAL INDEX</font></center>'; 
    

echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:50%';><tr><td>";  
echo "<fieldset style='width:70%;height:200px'>";
echo "<legend><h3>List of Documents</h3></legend>";   

 //echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
 echo '<form method="post" action="bio_cp_coveringletter.php">';
   
   echo '<table><tr><td style="background:#8080FF;color:white;">';
   echo'<input type="checkbox" id="covlettter" name="covletter" > Covering Letter'; 
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo'<input type="checkbox" id="techdetails" name="techdetails" > Technical Details';
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo'<input type="checkbox" id="terms" name="terms" > Terms and Conditions';
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo'<input type="checkbox" id="finance" name="finance" > Financial Details';
   echo '</td></tr>';
   echo'<tr><td><input type="submit" name="check" value="Print"></td></tr>'; 
   echo '</table>';
  echo"<input type='hidden' name='LeadID' id='leadid' value='$lead_ID'>"; 
  echo"<input type='hidden' name='CpID' id='cpid' value='$cpid'>";
 echo '</form>'; 
echo "</fieldset>";
echo "</td></tr></table>";

 

include('includes/footer.inc');  
?>
