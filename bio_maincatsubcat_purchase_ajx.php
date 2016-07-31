<?php
$PageSecurity = 80;
include('includes/session.inc');

$maincatid=$_GET['maincatid'];


if($_GET['total'])
{
    echo"<b>".$_GET['total']."</b>";
}

if(!$_GET['subcatid'] AND !$_GET['subsubcatid'] and $_GET['maincatid'])
{
echo'<select name="SubCategoryID" id="SubCategoryID"  style="width:200px"  onblur="Filtercategory2()" onchange="Filtercategory2()">';
$sql = "SELECT `subcategoryid`,`subcategorydescription` FROM substockcategory WHERE substockcategory.maincatid =".$_GET['maincatid']." ";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');//
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

echo '</select></td></tr>';
}
if(isset($_GET['subcatid']) && isset( $_GET['maincatid']) && !isset($_GET['subsubcatid']))
{
    
  //  echo $_GET['subcatid'];
echo '<select name="CategoryID" id="CategoryID" onchange="Filtercategory3()" onblur="Filtercategory3()">';//onChange="ReloadForm(ItemForm.UpdateCategories)

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

echo '</select>';
}
if(isset($_GET['subcatid']) && isset( $_GET['maincatid']) && isset($_GET['subsubcatid']))
{
 $sql = "SELECT `stockid`,`description` FROM `stockmaster` WHERE stockmaster.categoryid ='".$_GET['subsubcatid']."'";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
//echo $sql;
echo '<td id="seleitem"><select name="selitem" id="selitem" style="width:200px" >';
while ($myrow=DB_fetch_array($result)){
    if ( $myrow['stockid']==$_GET['CategoryID']){
        echo '<option selected value="'. $myrow['stockid'] . '">' . $myrow['description'];
    } else {
        echo '<option value="'. $myrow['stockid'] . '">' . $myrow['description'];
    }
    $stockid=$myrow['stockid'];
}

if (!isset($_POST['stockid'])) {
    $_POST['selitem']=$stockid;//
}

echo '</select>';
}   

?>