<?php
  $PageSecurity = 2;
$PricesSecurity = 9;

include('includes/session.inc');

$sql1="SELECT season_sub_id
       FROM m_sub_season
       ORDER BY season_sub_id
       DESC LIMIT 1"; 
$result1=DB_query($sql1,$db);
$value1=DB_fetch_array($result1);   
$season_subID=$value1[0]+1; 


echo "<fieldset id='left_panel_1' style='height:150px;width:300px;'>"; 
echo"<legend><h3>Add New Season Name</h3>";
echo"</legend>";
echo"<table>";
    
echo"<tr>
    <td>". _('Season Name ID') .":</td>
    <td><input type='hidden' name='SeasonNameID' id='seasonnameid' value='".$season_subID."'>$season_subID</td>
    </tr>";
    
echo"<tr>
    <td>". _('Season Name') .":</td>
    <td><input type='Text' name='SeasonSubName' id='seasonsubname'></td>
    </tr>";
echo "<tr>
    <td></td>
    <td><input type='Submit' name='addseason' VALUE='" . _('Enter') . "' onclick='if(seasonName()==1)return false'></td>
    </tr>";

echo"</table>";
echo"</fieldset>";



?>
