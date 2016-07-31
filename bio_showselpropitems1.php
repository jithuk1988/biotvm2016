<?php
$PageSecurity = 80;
include('includes/session.inc');

include('includes/header.inc');
include('includes/bio_GetPrice.inc');


echo"<fieldset style='background: #D6DEF7;width:95%'><legend>Select Products for proposal</legend>";
echo"<div style='width:500px; overflow:auto;'>";
$lead=$_GET['ledid'];
$_SESSION['lead']=$lead;
 $sql="SELECT enqtypeid from bio_leads WHERE bio_leads.leadid=$lead";
      $result=DB_query($sql,$db);
      $myrow=DB_fetch_array($result);
      $enqtype=$myrow[0];
$sql1="SELECT SUM(weight) from bio_leadfeedstocks  WHERE bio_leadfeedstocks.leadid=$lead";
      $result1=DB_query($sql1,$db);
      $myrow1=DB_fetch_array($result1);
      $weight= $myrow1[0];


// $sql="SELECT stockmaster.description,
// stockmaster.stockid,stockmaster.kgs
// from bio_catenq,
// stockmaster
// WHERE
// bio_catenq.enqid=$enqtype AND
// bio_catenq.catid=stockmaster.categoryid";

$sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
$result_cat=DB_query($sql_cat,$db);
$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $plant_array=join(",", $cat_arr); 
}


$sql="SELECT stockmaster.stockid FROM stockmaster,stockcategory WHERE stockmaster.categoryid=stockcategory.categoryid
AND stockmaster.categoryid IN ($plant_array)";
  $result=DB_query($sql,$db);

// $sqlapp="UPDATE bio_leadfeedstocks SET status = 1 WHERE bio_leadfeedstocks.leadid =$lead";
// $resultapp=DB_query($sqlapp,$db);

// $sqllbl="SELECT DISTINCT(stockcatproperties.label) from stockcatproperties,stockmaster
//          WHERE stockcatproperties.categoryid='DOMP'";

//$sql4="DELETE FROM bio_temppropitems where leadid=$lead";
//echo $sql4;
//$result4=DB_query($sql4,$db);

echo"<table border=1 width=500>";
  while($myrow=DB_fetch_array($result))     {
  $stockid=$myrow[0];
   $price=GetPrice($stockid,$db);
   $sqlw="SELECT stockmaster.description as description,
                 stockcatproperties.label as label,
                 stockitemproperties.value as value FROM stockmaster 
                 inner join  stockcategory on stockmaster.categoryid=stockcategory.categoryid 
                 left outer join stockitemproperties on stockmaster.stockid=stockitemproperties.stockid
                 inner join stockcatproperties on stockcatproperties.stkcatpropid=stockitemproperties.stkcatpropid
                 WHERE  stockmaster.stockid='$stockid'";
   $resultw=DB_query($sqlw,$db);
   $itemdesc='';
echo "<form name=\"sellist\">";
$a=0;
while ($myroww=DB_fetch_array($resultw))   {
  if ($a==0) {$itemdesc.=$myroww['description'].':</br> '; $a=1;}
 $itemdesc.=$myroww['label'].': '.$myroww['value'].'</br> ';
// $price=$myroww['price'];
}

 echo "<tr><td width=50>$stockid</td><td width=300>$itemdesc</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty\" value=1></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price\" value=\"".number_format($price,2)."\"></td>
       <td><a  style='cursor:pointer;'  id='$stockid' onclick=getresult(this.id)>" . _('Select') . "</a></td></tr>";
//       <td><input type=\"checkbox\" name=\"schm[]\" value=\"$stockid\"></td></tr>";
//$sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,leadid,selected) VALUES ('$stockid','$itemdesc',1,$price,$lead,0)";
//echo $sql3;
//$result3=DB_query($sql3,$db);
}
echo "</form>";
//   $sqlnw="SELECT stockitemproperties.value,
//   stockcatproperties.label,stockmaster.stockid
// from stockitemproperties,stockcatproperties,stockmaster
// WHERE
// stockcatproperties.stkcatpropid=stockitemproperties.stkcatpropid
// AND stockmaster.kgs='$kgrange'
// AND stockmaster.stockid=stockitemproperties.stockid";
//
// $resultnw=DB_query($sqlnw,$db);

// echo"<table border=1><tr>";
// $th=0;
// while($myrow123=DB_fetch_array($resultw)){
//     if($th==0){
//         echo"<th></th><th>ProductId</th>";
//     }
//     echo"<th style='color:white'>$myrow123[0]</th>";
//     $th++;}
//     echo"</tr><tr>";
//
//
// $r=1;
// while($myrownw=DB_fetch_array($resultnw)){
//     $nid=$myrownw[2];
//     $property=$myrownw[1];
//     $values=$myrownw[0];
//     if($r==1){
//         $a=1;
//         printf('<td style="background:#000080;color:white"><input type="checkbox" name="schm[]" value="%s"></td>',
//         $nid);
//         printf('<td style="background:#000080;color:white">'.$nid.'</td>');
//     }
//
//     print"<td style='background:#C0C0FF;color:#800000;'>$values</td>";
//     $r++;
//     if($r==7)   {
//         echo"</tr>";$r=1;
//     }
// }
// if($a==1){
//     echo"<tr><td colspan=5><input type='submit' id='$flg' value='Add Item/s' name='choose' onclick='if(checkCheckBoxes(this.id)==1)return false'></td></tr>";
// }
echo"</table>";
echo"</div>";
echo"</fieldset>";
?>
<script language="JavaScript">
function getresult(choose){
/* opener.document.stock.sid.value=choose; */
window.opener.location='bio_proposal.php?stockid='+choose+'&lead='+<?php echo $lead;?>;
window.close();
}
</script>

</body></html>


