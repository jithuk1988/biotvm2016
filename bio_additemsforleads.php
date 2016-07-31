<?php
  $PageSecurity = 80;
include('includes/session.inc');
include('includes/bio_GetPrice.inc');
$catagory=$_GET['cat'];
$stockid=$_GET['item'];
$user=$_SESSION['UserID'];

echo "<input type=\"hidden\" name=\"PlantSelectFlag\" id=\"plantselectflag\" value='1'>";



$sql_check1="SELECT COUNT(*) FROM bio_temppropitemslead
              WHERE stockid='$stockid'
              AND userid='$user'";

$result_check1=DB_query($sql_check1,$db);
$myrow_check1=DB_fetch_array($result_check1);

   $price=GetPrice($stockid,$db);
   $sqlw="SELECT stockmaster.description as description,
                 stockmaster.longdescription,
                 stockcatproperties.label as label,
                 stockitemproperties.value as value FROM stockmaster
                 inner join  stockcategory on stockmaster.categoryid=stockcategory.categoryid
                 left outer join stockitemproperties on stockmaster.stockid=stockitemproperties.stockid
                 inner join stockcatproperties on stockcatproperties.stkcatpropid=stockitemproperties.stkcatpropid
                 WHERE  stockmaster.stockid='$stockid'";
   $resultw=DB_query($sqlw,$db);
   $itemdesc='';
$a=0;
while ($myroww=DB_fetch_array($resultw))   {
  if ($a==0) {$itemdesc.=$myroww['description'].':</br> '; $a=1;}
 $itemdesc.=$myroww['label'].': '.$myroww['value'].'</br> ';
}
$sql_des="SELECT longdescription FROM stockmaster
          WHERE stockid='".$stockid."'";
$result_des=DB_query($sql_des,$db);
$myrow_des=DB_fetch_array($result_des);
$longdes=$myrow_des['longdescription'];

if($myrow_check1[0]>0){
    prnMsg('Item is already selected.','warn');  
}else{

$sql_sub="SELECT * FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub=DB_query($sql_sub,$db);
$subsidy_count=DB_num_rows($result_sub);

if($subsidy_count>0){
    while($myrow_sub=DB_fetch_array($result_sub)){
    $scheme=$myrow_sub['subsidy_scheme_id'];
    $amount=$myrow_sub['subsidy_amount'];
    $sql_sub1="INSERT INTO bio_temppropsubsidyleads(userid,
                                              stockid,
                                              scheme,
                                              amount) 
                                       VALUES('".$user."',
                                              '".$stockid."',
                                              '".$scheme."',
                                              '".$amount."')";
    $result_sub1=DB_query($sql_sub1,$db);
}

$sql_sub_amount="SELECT SUM(subsidy_amount) FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub_amount=DB_query($sql_sub_amount,$db);
$myrow_sub_amount=DB_fetch_array($result_sub_amount);
$subsidy=$myrow_sub_amount[0];
$netprice=$price-$subsidy;
}
else{
  $subsidy="";
  $netprice=$price;  
}

$sql3="INSERT INTO bio_temppropitemslead (stockid,description,qty,price,tprice,userid,subsidy,netprice) VALUES ('".$stockid."','".$longdes."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",'".$user."','".$subsidy."',".$netprice.")";
$result3=DB_query($sql3,$db);

}


$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitemslead where userid='".$user."'";
$result4=DB_query($sql4,$db);

echo "<input type=\"hidden\" name=\"stock".$k."\" id=\"stock".$k."\" value='".$stockid."'>";
echo "<input type=\"hidden\" name=\"user\" id=\"user\" value='".$user."'>";


echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Unit Price</th><th>Gross Price</th><th>Subsidy</th><th>Net Price</th></tr><tbody>";


$k=0;

while ($myrow4=DB_fetch_array($result4))   {
  $tprice=$myrow4['qty']*$myrow4['price'];
$k++;
$stock[$k]=$myrow4['stockid'];
$item=$myrow4['stockid'];
//$item=urlencode($myrow4['stockid']);
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",'".$user."','".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",'".$user."','".$stockid."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." readonly></td>
       

       </tr>";
}

//<td align=center><a  style='cursor:pointer;'  id='$user' onclick='addSubsidy($user,\"$item\")'>" . _('Manage Subsidy') . "</a></td>

echo "</tbody></table>";

echo"<div id=viewsubsidy>";


echo"</div>";



?>
