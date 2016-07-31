<?php

$PageSecurity = 80;
include('includes/session.inc');
  if($_GET['maincat'] )
 {
     echo '<select name="subcat" id="subcat"  style="width:200px"   onchange="view(this.value)" onblur="view(this.value)">';
  $sql = "SELECT `subcategoryid`,`subcategorydescription` FROM `substockcategory` where maincatid='".$_GET['maincat']."'";
    
    $result = DB_query($sql,$db);
    echo '<option></option>';
  //  echo '<option value=>';
    while ($myrow=DB_fetch_array($result))
         {
    
          if ($myrow['subcategoryid']==$_GET['subcat'] )
          {
           echo '<option selected value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'].'</option>';
          } 
          else
          {
           echo '<option value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'].'</option>';
          }
         }}
         if($_GET['subcatid'] )
{
    
  //  echo $_GET['subcatid'];
echo '<select name="CategoryID" style="width:200px" onchange="view2(this.value)" onblur="view2(this.value)">';

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
  if($_GET['subsubcatid'] )
 {

   
  $sql="SELECT
           `stockmaster`.`stockid`,
        `stockmaster`.`description` from `stockmaster` where stockmaster.categoryid='".$_GET['subsubcatid']."'";
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg); echo '<select name="combo2" style="width:200px" onchange="view3(this.value)" onblur="view3(this.value)" >';
        echo "<option></option>";
   while ($myrow=DB_fetch_array($result))
        {
   
              echo '<option value="'. $myrow['stockid'] . '">' . $myrow['description'].'</option>';
    
        }


echo '</select>';
}
if($_GET['itemsel'])
{
   
   $sql2="select `suppname`,supplierid 
from `suppliers` where `suppliers`.`supplierid` in(
select `purchorders`.supplierno from  `purchorders` where `purchorders`.orderno in(
select  `purchorderdetails`.orderno from `purchorderdetails` where  `purchorderdetails`.itemcode='".$_GET['itemsel']."'))
        "; 
          echo '<select name=supply  style="width:200px" >';
       $result2=DB_query($sql2,$db);//
         while($row2=DB_fetch_array($result2))
             { echo  '<option selected value="'. $row2[1] . '">' . $row2[0].'</option>';
         
                
             }  echo'</select>'; 
}
?>
  