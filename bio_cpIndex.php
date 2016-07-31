<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _(' Enclosure for Concept Proposal ');
include('includes/header.inc');
$lead_ID=$_GET['leadid'];
$cpid=$_GET['cpid'];

   echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">CONCEPT PROPOSAL INDEX</font></center>'; 
    

echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:50%';><tr><td>";  
echo "<fieldset style='width:70%;height:220px'>";
echo "<legend><h3>Print CP</h3></legend>";   

 //echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
 echo '<form method="post" action="bio_cp_coveringletter.php">';

 
 $sql_date="SELECT fs_date FROM bio_fs_entrydetails WHERE leadid = $lead_ID";
 $result_date=DB_query($sql_date,$db);
 $row_date=DB_fetch_array($result_date);
 $fs_date=ConvertSQLDate($row_date['fs_date']);
 
 $sql_cust="Select bio_cust.cust_id,bio_cust.custname, bio_cust.contactperson, bio_cust.area1,bio_cust.headdesig from bio_cust,bio_leads where bio_leads.cust_id=bio_cust.cust_id and bio_leads.leadid=".$lead_ID;
       $result_cust=DB_query($sql_cust,$db);
       $row_cust=DB_fetch_array($result_cust);
       $custid=    $row_cust['cust_id']; 
        $cont =     $row_cust['contactperson'];
        $orgname   =  $row_cust ['custname'];
        $area1  =       $row_cust['area1'];
        $desg  =       $row_cust['headdesig'];  
 $result_cust=DB_query($sql_cust,$db);
 $row_cust=DB_fetch_array($result_cust); 
 
   
   echo '<table>';
        echo'<tr><td>Organization:</td><td><input style="width:170px" required type=text name=orgname id=orgname value="'.$orgname.'"></td></tr>';
        echo'<tr><td>Designation:</td><td><input style="width:170px" required type=text name=desg id=desg value="'.$desg.'"></td></tr>'; 
        echo'<tr><td>Kind Attention:</td><td><input style="width:170px" type=text required name=cont id=cont value="'.$cont.'"></td></tr>';
        echo'<tr><td>Area:</td><td><input style="width:170px" required type=text name=area1 id=area1 value="'.$area1.'"></td></tr>';
   echo'<tr><td colspan=2>Feasibility conducted date<input type="text" name="feacondate" required id="feacondate"  style=width:175px class=date alt='.$_SESSION['DefaultDateFormat'].' value='.$fs_date.'  onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';
//   echo'<tr><td style="background:#8080FF;color:white;">';
//   echo'<input type="checkbox" id="covlettter" name="covletter" > Covering Letter'; 
//   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
//   echo'<input type="checkbox" id="techdetails" name="techdetails" > Technical Details';
//   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
//   echo'<input type="checkbox" id="terms" name="terms" > Terms and Conditions';
//   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
//   echo'<input type="checkbox" id="finance" name="finance" > Financial Details';
//   echo '</td></tr>';
   echo'<tr><td><input type="submit" name="check" value="Print"></td></tr>'; 
   echo '</table>';
  echo"<input type='hidden' name='LeadID' id='leadid' value='$lead_ID'>"; 
  echo"<input type='hidden' name='custid' id='custid' value='$custid'>";  
  echo"<input type='hidden' name='CpID' id='cpid' value='$cpid'>";
    
 echo '</form>'; 
echo "</fieldset>";
echo "</td></tr></table>";

 

include('includes/footer.inc');  
?>
