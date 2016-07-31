<?php

/* $Revision: 1.23 $ */

$PageSecurity = 4;

include('includes/session.inc');


  echo "<fieldset class='right_panel_1'>";     
  echo"<legend><h3>Supplier item</h3>";
  echo"</legend>";
  echo'<table width=100%>';
if((isset($_GET['itemselection']) AND ($_GET['itemselection']==1)))         {
    
    $sql3="SELECT stockid,
                  description
           FROM stockmaster
           WHERE mbflag!='M'";
    $result3=DB_query($sql3,$db);
    
    echo'<tr><td width=50%>Select Item</td><td><select name="StockID" onchange="suppieritemselected(this.value)">';
    echo'<option value=0></option>';
    while($myrow3=DB_fetch_array($result3))         {
    
    echo'<option value='.$myrow3['stockid'].'>'.$myrow3['description'].'';
    echo'</option>';
    }
    echo'</select></td></tr>';

}
echo'</table>';
echo'<table width=100% id="SupplierMaster_right_itemselected">';
echo'</table>';
echo "</fieldset>";

?>
