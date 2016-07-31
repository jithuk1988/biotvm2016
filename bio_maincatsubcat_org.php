<?php
$PageSecurity = 40;
include('includes/session.inc');

$maincatid=$_GET['maincatid'];
echo '<select name="CategoryID" onChange="ReloadForm(ItemForm.UpdateCategories)">';

$sql = "SELECT categoryid, categorydescription 
        FROM stockcategory,
             bio_maincat_subcat
        WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid     AND
              bio_maincat_subcat.maincatid =".$_GET['maincatid']."";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

while ($myrow=DB_fetch_array($result)){
    if (!isset($_POST['CategoryID']) or $myrow['categoryid']==$_POST['CategoryID']){
        echo '<option selected value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'];
    } else {
        echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'];
    }
    $Category=$myrow['categoryid'];
}

if (!isset($_POST['CategoryID'])) {
    $_POST['CategoryID']=$Category;
}

echo '</select><a target="_blank" href="'. $rootpath . '/StockCategories.php?' . SID . '">' . _('Add or Modify Stock Categories') . '</a>';
?>
