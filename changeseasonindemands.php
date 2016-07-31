<?php
$PageSecurity = 2;
$PricesSecurity = 9;

include('includes/session.inc');

echo '<table width=100%><tr>';
echo'<td width=50%>';
echo'<div id=left_panel_1>';
echo "<fieldset id='left_panel_1' style='height:150px;'>"; 
echo"<legend><h3>Enter Season Demands</h3>";
echo"</legend>";
echo"<table>";

echo"<tr>
    <td>". _('Year') .":</td>
    <td><input type='text' name='SeasonYear' id='seasonyear' value='".$current_year."' style='width:190px;'></td>
    </tr>";
    
echo"<tr>
    <td>". _('Season') .":</td>
    <td><select name='Season' id='season' style='width:190px;' onchange=changeGrid() onkeyup=changeGrid()>";

$sql_season = 'SELECT m_sub_season.season_sub_id, 
                      m_sub_season.seasonname
               FROM m_sub_season';
$result_season = DB_query($sql_season,$db);
while ($myrow = DB_fetch_array($result_season)) {
    if (isset($_GET['Season']) and $myrow['seasonname']==$_GET['Season']) {
         echo "<option selected value='" .$myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
    }
    
} //end while loop
    
echo"</select>
    </td>
    </tr>";
    
echo"<tr>
    <td>". _('Item') .":</td>
    <td><select name='Item' id='item' style='width:190px;'>";
$userstockid='';
$sql_item = "SELECT stockid,description
  FROM stockmaster WHERE mbflag='M' AND categoryid !=13
                ORDER BY stockid";
$result_item = DB_query($sql_item,$db); 
while ($myrow = DB_fetch_array($result_item)) {
    if (isset($_GET['Item']) and $myrow['description']==$_GET['Item']) {
         echo "<option selected value='" .$myrow['stockid'] . "'>" . $myrow['description'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description'];
    }
    
} //end while loop
     
echo"</select>
    </td>
    </tr>"; 
    
echo"<tr>
    <td>". _('Demand Quantity') .":</td>
    <td><input type='Text' name='DemandQty' id='demandqty' style='width:190px;' onkeyup=showDetails('season','item') onkeychange=showDetails('season','item')></td>
    </tr>";   

echo"</table>";
echo"</fieldset>";
echo'</div>';
echo"</td>";


echo'<td>';    
echo'<div id=right_panel_1>';

echo'</div>'; 
echo"</td></tr></table>";

?>
