<?php
$PageSecurity = 80;

include('includes/session.inc');

include('includes/bio_GetPrice.inc');//

  if($_GET['subsubcatid'] )
 {

   
  $sql="SELECT
           `stockmaster`.`stockid`,
        `stockmaster`.`description` from `stockmaster` where stockmaster.categoryid='".$_GET['subsubcatid']."'";
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
    echo '<select name="Item" id="item" style="width:200px">';
        echo "<option></option>";
   while ($myrow=DB_fetch_array($result))
        {
   
              echo '<option value="'. $myrow['stockid'] . '">' . $myrow['description'].'</option>';
    
        }


echo '</select>';
}       
  if($_GET['subcatid'] )
{
    
  //  echo $_GET['subcatid'];
echo '<select name="caty" id="caty" style="width:200px" onchange="document.getElementById("Item").innerHTML="";">';   // view2(this.value)

echo  $sql = "SELECT categoryid, categorydescription 
        FROM stockcategory,
             bio_maincat_subcat
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid    AND bio_maincat_subcat.subcatid ='".$_GET['subcatid']."'";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
echo '<option value=0></option>';
while ($myrow=DB_fetch_array($result)){
    if (!isset($_POST['CategoryID']) or $myrow['categoryid']==$_POST['CategoryID']){
        echo '<option  value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'].'</option>';
    } else {
        echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'].'</option>';
    }
    $Category=$myrow['categoryid'];
}
}

?>


