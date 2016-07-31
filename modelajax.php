<?php
  $PageSecurity = 80;
include('includes/session.inc');
if($_GET['main'])
{
   
   $sql="SELECT subcategoryid,subcategorydescription FROM substockcategory WHERE maincatid='".$_GET['main']."'";
 echo "<select name='combo2'>";
 $result = DB_query($sql,$db);//stockitemproperty.capacity asc
   echo '<option></option>';
     while ($myrow=DB_fetch_array($result))
         {
    
           echo '<option value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'].'</option>';
         }
         echo "</select>";
}
if($_GET['sub'])
{
   
   $sql="SELECT categoryid,categorydescription FROM stockcategory INNER JOIN bio_maincat_subcat  ON stockcategory.categoryid=bio_maincat_subcat.subsubcatid AND bio_maincat_subcat.subcatid='".$_GET['sub']."'";
 echo "<select name='combo3'>";
 $result = DB_query($sql,$db);//stockitemproperty.capacity asc
   echo '<option></option>';
     while ($myrow=DB_fetch_array($result))
         {
    
           echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'].'</option>';
         }
         echo "</select>";
}
if($_GET['cat'])
{
   
   $sql="SELECT stockitemproperty.stockid,stockitemproperty.capacity FROM stockitemproperty INNER JOIN stockmaster ON stockmaster.stockid=stockitemproperty.stockid WHERE stockitemproperty.capacity IS NOT NULL AND stockmaster.categoryid='".$_GET['cat']."' ORDER BY capacity asc";
 echo "<select name='combo3'>";
 $result = DB_query($sql,$db);//stockitemproperty.capacity asc
   echo '<option></option>';
     while ($myrow=DB_fetch_array($result))
         {
    
           echo '<option value="'. $myrow['stockid'] . '">' . $myrow['capacity'].'</option>';
         }
         echo "</select>";
}
?>
