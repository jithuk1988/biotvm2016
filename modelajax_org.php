<?php
  $PageSecurity = 80;
include('includes/session.inc');
if($_GET['subsubcatid'])
{
   
  echo  $sql="SELECT stockitemproperty.`stockid` , stockitemproperty.capacity
FROM `stockitemproperty` , stockmaster
WHERE stockitemproperty.stockid = stockmaster.stockid
AND make LIKE '".$_GET['subsubcatid']."' and capacity is not NULL order by capacity asc ";
 echo "<select name='combo2'>";
 $result = DB_query($sql,$db);//stockitemproperty.capacity asc
   echo '<option></option>';
     while ($myrow=DB_fetch_array($result))
         {
    
           echo '<option value="'. $myrow['stockid'] . '">' . $myrow['capacity'].'</option>';
         }
         echo "</select>";
}
?>
