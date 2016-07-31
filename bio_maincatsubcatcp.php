<?php
$PageSecurity = 80;
include('includes/session.inc');

$maincatid=$_GET['maincatid'];


$sql = "SELECT bio_maincat_subcat.subsubcatid, stockcategory.categorydescription
FROM `bio_maincat_subcat` , stockcategory
WHERE stockcategory.categoryid = bio_maincat_subcat.subsubcatid
AND bio_maincat_subcat.subcatid ='".$_GET['maincatid']."'";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
echo'Sub Category<select name="caty" id="caty" style="width:200px">';
//      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[subsubcatid].">".$myrow[categorydescription]."</option>";
      }
      echo '</select>';

?>
