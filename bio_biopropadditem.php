<?php

$PageSecurity = 80;
include('includes/session.inc');
$title = _('Add Item');
include('includes/header.inc');
include('includes/bio_GetPrice.inc');
global $lead;
global $first;
global $flag2;

if ($_GET['ledid']) {
 $lead=$_GET['ledid'];
}
if ($_GET['flag2']) {
 $flag2=$_GET['flag2'];
}
$first=$_GET['first'];

//echo $lead;
echo"<br />";
echo "<div style='width:\"700px\" text-align:\"center\"' overflow:auto;'>";
echo "<fieldset style='background: #D6DEF7; width:700px;'><legend>Select Item Category</legend>";
echo "<form name=\"catlist\">";

$sql_cat="SELECT subcatid FROM bio_maincat_subcat";
$result_cat=DB_query($sql_cat,$db);
$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $plant_array=join(",", $cat_arr); 
}


 $sql="SELECT categoryid,categorydescription from stockcategory
                WHERE stockcategory.categoryid IN ($plant_array)";
      $result=DB_query($sql,$db);
      
      echo '<input type="hidden" name="flag2" id="flag2" value='.$flag2.'>';
      echo '<input type="hidden" name="first" id="first" value='.$first.'>';  
      
echo "<table border=1 >";
echo "<tr>";
$sql="SELECT subcategoryid,subcategorydescription FROM `substockcategory` order by subcategoryid";
echo '<td><select name=main5 id=main5 onchange=shower(this.value)>';
     

$rst=DB_query($sql,$db);
echo '<option value=0>Select Main category</option>';
while($myrowc=DB_fetch_array($rst))
{
    

 echo '<option value='.$myrowc[subcategoryid].'>'.$myrowc[subcategorydescription].'</option>';
 }
   echo  '</select>
       </td>';
echo '<td id=sub><select name="caty" id="caty" style="width:300px">';
      echo '<option value=0>Select Sub category</option>';
      /*while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[categoryid].">".$myrow[categorydescription]."</option>";
      }*/
      echo '</select></td>';
      echo '<td>Part description </td>';
      echo '<td><input type="text" name="descr" id="descr" style="width:120px"></td>';
      echo '<td><input type="button" name="search" id="search" value="Search" style="width:100px"></td></tr></table>';
echo"</fieldset>";
echo"</div>";


if ($_GET['categoryid']) {
 $categoryid=$_GET['categoryid'];
if ($_GET['lead']) {
 $lead=$_GET['lead'];
}
//echo 'lead '.$lead;
//echo 'cat '.$categoryid;

echo"<div id='grid' style='width:700px text-align='center'; overflow:auto;'>";
echo"<fieldset style='background: #D6DEF7;width:700px'><legend>Select Item</legend>";
$sql="SELECT stockmaster.stockid FROM stockmaster WHERE stockmaster.categoryid='".$categoryid."'";
  $result=DB_query($sql,$db);
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
}

$sql_des="SELECT longdescription FROM stockmaster
          WHERE stockid='".$stockid."'";
$result_des=DB_query($sql_des,$db);
$myrow_des=DB_fetch_array($result_des);
$longdes=$myrow_des['longdescription'];


 echo "<tr><td width=50>$stockid</td><td width=300>$longdes</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty\" value=1></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price\" value=\"".number_format($price,2)."\"></td>
       <td><a  style='cursor:pointer;'  id='$stockid' onclick=getresult(this.id,$flag2,$first)>" . _('Select') . "</a></td></tr>";
}
echo "</form>";

echo"</fieldset>";
echo"</div>";
}

include "includes/footer.inc";
?>
<script language="JavaScript">

$('#search').click(function() {
  var str=$("#caty").val();
  var str4="&flag2="+$("#flag2").val();
  var str5="&first="+$("#first").val();
  str3="&lead="+<?php echo $lead;?> ;
  if ($("#descr").val()) {
  var str2="&descr="+$("#descr").val();
  } else {var str2="";}

  location.href="bio_biopropadditem.php?categoryid="+str+str2+str3+str4+str5;
});
function getresult(choose,flag2,first){
window.opener.location='bio_proposal.php?stockid='+choose+'&flag2='+flag2+'&first='+first+'&lead='+<?php echo $lead;?>+'&add=1';
window.close();
}

</script>

<script>function shower(str)
{


  /* if (str=="")
  {
  document.getElementById("main5").focus();
  return;
  }*/
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("sub").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET"," bio_biopropadditem_ajax.php?main="+str,true);//alert(str4);
xmlhttp.send();     // alert(str);  //  alert(str2);
}</script>


