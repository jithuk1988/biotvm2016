<?php
  $PageSecurity = 80;
include('includes/session.inc');
 $loc1=$_GET['str1'];
 $itm1=$_GET['str2'];
  $str2=   $_GET['combo2'];
  $sub2=$_GET['combo3'];
    if($str2)
 
 {     
     echo "<select name='sub' id='sub' style='width:190px' onchange=sub2(this.value)>";
  echo   $sql="SELECT
 maincatid,
  subcatid,
  stockcategory.categorydescription,
  stockcategory.stocktype
FROM bio_maincat_subcat,stockcategory
where bio_maincat_subcat.maincatid=$str2  AND stockcategory.categoryid=bio_maincat_subcat.subcatid AND stockcategory.stocktype='F'";
$rst=DB_query($sql,$db);
echo '<option value=0></option>';//
while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[subcatid]==$sub)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[subcatid].'">'.$myrowc[categorydescription].'</option>';
 }
  
    echo '</select></td>'; 
 }  
 if($sub2)
 {  
  $sql="SELECT stockmaster.stockid,stockmaster.description FROM stockmaster WHERE stockmaster.categoryid='".$sub2."'";
/*  SELECT `stockmaster`.`stockid`, `stockmaster`.`description`,stockcategory.categoryid, 
`locstock`.`quantity` 
FROM locations 
INNER JOIN `stockcategory` ON (stockcategory.categoryid='".$h."')
inner join bio_maincat_subcat on( bio_maincat_subcat.maincatid=$g AND stockcategory.categoryid=bio_maincat_subcat.subcatid)
INNER JOIN stockmaster ON (stockmaster.categoryid= bio_maincat_subcat.subcatid and stockmaster.stockid=stockmaster.mbflag='B') 
INNER JOIN locstock ON (locstock.stockid = stockmaster.stockid)  

WHERE locstock.loccode=".$_POST['loc1']."
 AND `locstock`.`quantity` >0 
group by stockmaster.stockid  "; */
    $result1=DB_query($sql,$db);
echo '<select name="itm" id="itm" style="width:190px">';
  while($myrow1=DB_fetch_array($result1))
  {

        echo '<option value="';
    echo $myrow1['stockid'] . '">'.$myrow1['description'];
    echo '</option>';
   // $f++;<td>CAPACITY</td><input type="text" name="capacity" id="capacity" style="width:190px">
   }
    echo '</select></td>'; 
 }  
 if($loc1 && $itm1)
 {
 $sql1="SELECT * FROM bio_wo_stocklocation WHERE stockid='".$itm1."' AND loccode='".$loc1."' ";
      $result1=DB_query($sql1,$db);
     //  $k=0;
   while ($myrow = DB_fetch_array($result1)) {
      $count=$myrow['stockid'];
  
                        if($count!=null)
             {
               // $k=1;
                      echo"<input type='hidden' name='stop' id='stop' value='2'>";   
             } 
        
   }      /* if($k==1)
              { echo "<div class=warn>This workcenter already entered its capacity</div>"  ;  
              
              }*/

 }
?>
