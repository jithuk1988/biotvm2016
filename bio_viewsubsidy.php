<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Add Subsidy');
include('includes/header.inc');

if($_SESSION['LID']!=""){
    $leadid1=$_SESSION['LID'];
    unset($_SESSION['LID']);
}


echo "<fieldset style='width:780px'><legend>Subsidy Details</legend>";
echo "<div id='sellist' style='background: #D6DEF7;'>";
echo "<div id='messageBox'>&nbsp;</div>";
   echo "<table style='width:750px'>";
echo "<tr><th width=50>Sl No.</th><th>Stock Id</th><th width=200>Item Description</th><th>Scheme</th><th>Amount</th></tr><tbody>";
$k=0;
$slno=1;
$sql_1="SELECT bio_temppropsubsidy.stockid,
               bio_temppropsubsidy.amount,
               bio_schemes.scheme,
               stockmaster.longdescription
         FROM bio_temppropsubsidy,bio_schemes,stockmaster
         WHERE bio_temppropsubsidy.scheme=bio_schemes.schemeid
         AND stockmaster.stockid=bio_temppropsubsidy.stockid
         AND bio_temppropsubsidy.leadid=".$leadid1;
$result_1=DB_query($sql_1,$db);
while($myrow_1=DB_fetch_array($result_1)){
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
echo"<tr><td align=center>".$slno."</td><td align=center>".$myrow_1['stockid']."</td><td>".$myrow_1['longdescription']."</td><td align=center>".$myrow_1['scheme']."</td><td align=center>".$myrow_1['amount']."</td>";
$slno++;
}

echo "</tbody></table>";
  echo "</div>";
echo "</fieldset>"; 
?>
