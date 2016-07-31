<?php
  $PageSecurity = 80;
include('includes/session.inc');

 $str1=$_GET['main'];
 if($str1!="")
 {
     
    $sql="SELECT bio_maincat_subcat.subsubcatid, stockcategory.categorydescription
FROM `bio_maincat_subcat` , stockcategory
WHERE stockcategory.categoryid = bio_maincat_subcat.subsubcatid
AND bio_maincat_subcat.subcatid ='".$str1."'";
$rst=DB_query($sql,$db);
echo '<td id=sub><select name="caty" id="caty" style="width:300px">';
      echo '<option value=0>Select Sub category</option>';

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[subsubcatid]==$sub)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[subsubcatid].'">'.$myrowc[categorydescription].'</option>';
 }
 }
?>
