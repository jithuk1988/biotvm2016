<?php
$PageSecurity = 2;
$PricesSecurity = 9;
include('includes/session.inc');
include('includes/SQL_CommonFunctions.inc');
$sid=$_GET['p'];
$sql_value='SELECT * FROM m_season WHERE season_id='.$sid;
$result_value=DB_query($sql_value,$db);
$myrow_value = DB_fetch_array($result_value);
$sdate=ConvertSQLDate($myrow_value['startdate']);
$ddate=ConvertSQLDate($myrow_value['enddate']);

echo '<table width=100%><tr><td width=50%>'; 
echo "<fieldset id='left_panel_1' style='height:155px;'>"; 
echo"<legend><h3>Season Details</h3>";
echo"</legend>";
echo"<table>";

echo"<tr>
    <td>". _('Season ID') .":</td>
    <td><input type='hidden' name='SeasonID' id='seasonid' value='".$sid."'>$sid</td>
    </tr>";

echo"<tr>
    <td>". _('Season Name') .":</td>
    <td><select name='SeasonName' id='seasonname'>";

$sql_season = 'SELECT m_sub_season.season_sub_id,     
                m_sub_season.seasonname
  FROM m_sub_season';
$result_season = DB_query($sql_season,$db);
while ($myrow = DB_fetch_array($result_season)) {
    if ($myrow['season_sub_id']==$myrow_value['season_sub_id']) {
         echo "<option selected value='" .$myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
    }
    
} //end while loop
    
echo"</select>
    </td>
    </tr>";
    
echo"<tr>
    <td>". _('Start Date') .":</td>
    <td><input type='Text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' value='".$sdate."' style='width:173px;'></td>
    </tr>";
    
echo"<tr>
    <td>". _('End Date') .":</td>
    <td><input type='Text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' value='".$ddate."' style='width:173px;'></td>
    </tr>";

echo"<tr>
    <td>". _('Current Season') .":</td>";
if($myrow_value['is_current']==1){
echo"<td>
    <input type='radio' name='CurrentSeason' id='currentseason' value='1' checked>Yes
    <input type='radio' name='CurrentSeason' id='currentseason' value='0' >No
    </td>
    </tr>";
}
else{
echo"<td>
    <input type='radio' name='CurrentSeason' id='currentseason' value='1'>Yes
    <input type='radio' name='CurrentSeason' id='currentseason' value='0' checked>No
    </td>
    </tr>";  
}
    
    

    
echo"</table>";
echo"</fieldset>";
echo"</td>";


echo'<td>';
echo"<div id='malayalamdate'>";    
/*echo "<fieldset id='right_panel_1' style='height:155px;'>";     
echo"<legend><h3>Malayalam Date</h3>";
echo"</legend>";
echo'<table>';

echo"<tr>
    <td></td>
    <td>&nbsp&nbspDay &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Month &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Year</td>
    </tr>";

echo"<tr>
    <td>". _('Start Date') .":</td>
    <td>
    <input type='Text' name='StartDay' id='startday' size=2% value='".$myrow_value['start_malday']."'>
    <select name='StartMonth' id='startmonth'>";
$sql_malmonth = 'SELECT month_id,     
                malmonthname
  FROM m_sub_malmonth';
$result_malmonth = DB_query($sql_malmonth,$db);
while ($myrow = DB_fetch_array($result_malmonth)) {
    if ($myrow['month_id']==$myrow_value['start_malmonth']) {
         echo "<option selected value='" .$myrow['month_id'] . "'>" . $myrow['malmonthname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['month_id'] . "'>" . $myrow['malmonthname'];
    }
    
} //end while loop    
    


echo"</select>";
echo"<input type='Text' name='StartYear' id='startyear' size=2% value='".$myrow_value['start_malyear']."'>";
echo"</td> 
    </tr>";

echo"<tr>
    <td>". _('End Date') .":</td>
    <td>
    <input type='Text' name='EndDay' id='endday' size=2% value='".$myrow_value['end_malday']."'>
    <select name='EndMonth' id='endmonth'>";
$sql_malmonth = 'SELECT month_id,     
                malmonthname
  FROM m_sub_malmonth';
$result_malmonth = DB_query($sql_malmonth,$db);
while ($myrow = DB_fetch_array($result_malmonth)) {
    if ($myrow['month_id']==$myrow_value['end_malmonth']) {
         echo "<option selected value='" .$myrow['month_id'] . "'>" . $myrow['malmonthname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['month_id'] . "'>" . $myrow['malmonthname'];
    }
    
} //end while loop
echo"</select>";
echo"<input type='Text' name='EndYear' id='endyear' size=2% value='".$myrow_value['end_malyear']."'>";
echo"</td> 
    </tr>";    
    

echo "</table>";
echo "</fieldset>";   */
echo "</div>" ; 
echo"</td></tr></table>";
echo"<input type='hidden' name='SID' id='seasonid' value='".$sid."'>";
?>
