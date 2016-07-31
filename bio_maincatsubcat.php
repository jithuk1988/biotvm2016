<?php
$PageSecurity = 80;
include('includes/session.inc');

$maincatid=$_GET['maincatid'];

if(!$_GET['subcatid'] and $_GET['maincatid'])
{
echo'<select name="SubCategoryID" id="SubCategoryID"  style="width:200px"   onchange="Filtercategory2()" onblur="Filtercategory2()">';
$sql = "SELECT `subcategoryid`,`subcategorydescription` FROM substockcategory WHERE substockcategory.maincatid =".$_GET['maincatid']." ";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

while ($myrow=DB_fetch_array($result)){
    if (isset($_POST['SubCategoryID']) AND $myrow['subcategoryid']==$_POST['SubCategoryID']){
        echo '<option selected value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'];
    } else {
        echo '<option value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'];
    }
    $SubCategory=$myrow['categoryid'];
}

if (!isset($_POST['SubCategoryID'])) {
    $_POST['SubCategoryID']=$SubCategory;
}

echo '</select><a target="_blank" href="'. $rootpath . '/bio_subcategory.php?' . SID . '">' . _('Sub-Categories Master') . '</a></td></tr>';
}
if(isset($_GET['subcatid']) && isset( $_GET['maincatid']))
{
    
  //  echo $_GET['subcatid'];
echo '<select name="CategoryID" style="width:200px" >';

$sql = "SELECT categoryid, categorydescription 
        FROM stockcategory,
             bio_maincat_subcat
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid     AND
              bio_maincat_subcat.maincatid =".$_GET['maincatid']." AND bio_maincat_subcat.subcatid ='".$_GET['subcatid']."'";
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

echo '</select><a target="_blank" href="'. $rootpath . '/StockCategories.php?' . SID . '">' . _('Sub Sub-Categories Master') . '</a>';
}
?>