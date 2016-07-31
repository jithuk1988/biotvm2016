<?php
$PageSecurity = 2;
$PricesSecurity = 9;

include('includes/session.inc');

$demand_id=$_GET['p'];
$sql_demand="SELECT * FROM seasondemands WHERE seasondemandid=".$demand_id;
$result_demand=DB_query($sql_demand,$db);
$myrow_demand=DB_fetch_array($result_demand);


echo '<table width=100%><tr>';
echo'<td width=50%>';
echo'<div id=left_panel_1>';
echo "<fieldset id='left_panel_1' style='height:150px;'>"; 
echo"<legend><h3>Enter Season Demands</h3>";
echo"</legend>";
echo"<table>";

$demandyear=$myrow_demand['year'];
//echo "month id" ;
 $season_id=$myrow_demand['seasonid'];
//echo "<br/>";
$itemcode=$myrow_demand['itemcode'];
$quantity=$myrow_demand['demandquantity'];


echo"<tr>
    <td>". _('Year') .":</td>
    <td><input type='hidden' name='SeasonYear' id='seasonyear' value='".$demandyear."' style='width:190px;'>$demandyear</td>
    </tr>";
    
//$sql_season = 'select t2.seasonname,t2.season_sub_id from m_season t1,
//m_sub_season t2 where t1.season_sub_id=t2.season_sub_id and t1.season_id='.$season_id;

 $sql_season = 'select seasonname,season_sub_id from m_sub_season where season_sub_id='.$season_id;
 
$result_season = DB_query($sql_season,$db);
$myrow = DB_fetch_array($result_season);
//echo "month";
 $sname=$myrow['seasonname'];
//echo "<br/>";
//echo "month id";
 $sid=$myrow['season_sub_id'];

echo"<tr>
    <td>". _('Season') .":</td>
    <td><input type='hidden' name='Season' id='season' value='".$sid."' style='width:190px;'>$sname</td>";
    //echo"<td><select name='Season' id='season' style='width:190px;'>";

//$sql_season = 'SELECT m_sub_season.season_sub_id,     
//                m_sub_season.seasonname
//  FROM m_sub_season';
//$result_season = DB_query($sql_season,$db);
//while ($myrow = DB_fetch_array($result_season)) {
//    if (isset($_GET['Season']) and $myrow['seasonname']==$_GET['Season']) {
//         echo "<option selected value='" .$myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
//    } else {                                                                                                             
//        echo "<option value='" . $myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
//    }
//    
//} //end while loop
//    
//echo"</select>
  echo"</td>
 </tr>";

$sql_item = "SELECT description
  FROM stockmaster WHERE stockid='".$itemcode."'";
$result_item = DB_query($sql_item,$db); 
$myrow = DB_fetch_array($result_item);
$itemcode1=$myrow['description'];
    
echo"<tr>
    <td>". _('Item') .":</td>
    <td><input type='hidden' name='Item' id='item' value='".$itemcode."' style='width:190px;'>$itemcode1</td>";
    //<td><select name='Item' id='item' style='width:190px;'>";
//$userstockid='';
//$sql_item = "SELECT stockid,description
//  FROM stockmaster WHERE mbflag='M' AND categoryid !=13
//                ORDER BY stockid";
//$result_item = DB_query($sql_item,$db); 
//while ($myrow = DB_fetch_array($result_item)) {
//    if (isset($_GET['Item']) and $myrow['description']==$_GET['Item']) {
//         echo "<option selected value='" .$myrow['stockid'] . "'>" . $myrow['description'];
//    } else {                                                                                                             
//        echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description'];
//    }
//    
//} //end while loop
//     
//echo"</select>
//    </td>
echo"</tr>"; 
    
echo"<tr>
    <td>". _('Demand Quantity') .":</td>
    <td><input type='text' name='DemandQty' id='demandqty' style='width:190px;' onkeyup=showDetails('season','item') onkeychange=showDetails('season','item') value='".$quantity."'></td>
    </tr>";   

echo"</table>";
echo"</fieldset>";
echo'</div>';
echo"</td>";


echo'<td>';    
echo'<div id=right_panel_1>';

echo'</div>'; 

echo"<input type='hidden' name='DID' id='demandid' value='".$demand_id."'>";
echo"</td></tr></table>";

?>
