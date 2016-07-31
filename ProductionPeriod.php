<?php
$PageSecurity = 2;
$PricesSecurity = 9;
include('includes/session.inc');
include('includes/SQL_CommonFunctions.inc');  
$snid=$_GET['p'];

$sql_prod="SELECT * FROM productionperiod WHERE seasonid=".$snid;
$result_prod = DB_query($sql_prod,$db);
$myrow_count = DB_num_rows($result_prod);
$myrow_prod=DB_fetch_array($result_prod);

//prnMsg($msg,'success');
if($myrow_count>0){
    
    $pro_sdate=ConvertSQLDate($myrow_prod['startdate']);
$pro_edate=ConvertSQLDate($myrow_prod['enddate']);
echo "<fieldset id='right_panel_1' style='height:140px;'>"; 
echo"<legend><h3>Production Period</h3>";
echo"</legend>";
echo"<table>";
    
echo"<tr>
    <td>". _('Start Date') .":</td>
    <td><input type='Text' name='ProStartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' value='".$pro_sdate."'></td>
    </tr>";
    
echo"<tr>
    <td>". _('End Date') .":</td>
    <td><input type='Text' name='ProEndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' value='".$pro_edate."'></td>
    </tr>";

echo"</table>";
echo"</fieldset>";

}else{
echo "<fieldset id='right_panel_1' style='height:140px;'>"; 
echo"<legend><h3>Production Period</h3>";
echo"</legend>";
echo"<table>";
    
echo"<tr>
    <td>". _('Start Date') .":</td>
    <td><input type='Text' name='ProStartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "'></td>
    </tr>";
    
echo"<tr>
    <td>". _('End Date') .":</td>
    <td><input type='Text' name='ProEndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "'></td>
    </tr>";

echo"</table>";
echo"</fieldset>";
}
echo"<input type='hidden' name='ProdSID' id='prodid' value='".$snid."'>"; 
?>
