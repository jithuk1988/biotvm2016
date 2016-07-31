<?php
$PageSecurity = 80;

include('includes/session.inc');

include('includes/bio_GetPrice.inc');

if ($_GET['catid']) {
 $categoryid=$_GET['catid'];
}

 $sql="SELECT stockmaster.stockid,stockmaster.longdescription FROM stockmaster WHERE stockmaster.categoryid='".$categoryid."'";
//$sql="SELECT stockmaster.stockid,stockmaster.longdescription FROM stockmaster WHERE stockmaster.categoryid='".$categoryid."' and stockmaster.stockid in (Select distinct stkcode from salesorderdetails inner join salesorders on salesorderdetails.orderno=salesorders.orderno where stkcode like 'P%' and orddate>'2013-01-01')";
  $result=DB_query($sql,$db);
  
echo'<select name="Item" id="item" style="width:300px">';
      echo '<option value=0>Select Item</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[stockid].">".$myrow[longdescription]."</option>";
      }
      echo '</select>';

?>


