<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Add Item');
include('includes/header.inc');
include('includes/bio_GetPrice.inc');
global $lead;
global $first;
global $flag2;

if ($_GET['lead']) {
 $lead=$_GET['lead'];
}
$first=$_GET['first'];                    // 1 -> from approval page   2 -> from cp page
$budgetinitial=$_GET['budgetinitial'];  

if(!isset($_GET['search']))         {

$sql2="SELECT COUNT(*)
      FROM bio_fs_entrydetails
      WHERE leadid=$lead
      AND   sdegradable!=0";

$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_array($result2);

if($myrow2[0]>0)     {
    
 echo'   <div class="info message" style="height:23px">
         <centre><font color="black"><b>The plant deals with slow degradable feedstocks.<br>Predigester recommended.<b></font></centre>
         
</div>';
}
    
/*     
?>
<script>
alert("The plant deals with slow degradable feedstocks..pre digester recommended");
</script>
     
<?php      
} 
*/

$sql="SELECT COUNT(*)
      FROM bio_fs_entrydetails
      WHERE leadid=$lead
      AND   vsdegradable!=0";

$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);

if($myrow[0]>0)     {
    
     echo'<div class="info message" style="height:23px">
         <centre><font color="black"><b>The plant deals with very slow degradable feedstocks.<br>Incenerator recommended.</b></font></centre>
         
</div>';
}

/*    
?>
<script>
alert("The plant deals with very slow degradable feedstocks..incenerator recommended");
</script>     



<?php     
}
*/


}


echo "<input type='hidden' name='Plantid' id='plantid' value='yes'>";
//echo $lead;
echo"<br />";
echo "<div style='width:\"700px\" text-align:\"center\"' overflow:auto;'>";
echo "<fieldset style='background: #D6DEF7; width:700px;'><legend>Select Item Category</legend>";
echo "<form name=\"catlist\">";

      
      echo '<input type="hidden" name="flag2" id="flag2" value='.$flag2.'>';
      echo '<input type="hidden" name="first" id="first" value='.$first.'>';  





/*$sql2="SELECT COUNT(*)
      FROM bio_fs_entrydetails
      WHERE leadid=$lead
      AND   sdegradable!=0";

$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_array($result2);

if($myrow2[0]>0)     {
    
?>
<script>
alert("The plant deals with slow degradable feedstocks..pre digester recommended");
</script>
     
<?php      
}

$sql="SELECT COUNT(*)
      FROM bio_fs_entrydetails
      WHERE leadid=$lead
      AND   vsdegradable!=0";

$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);

if($myrow[0]>0)     {

?>
<script>
alert("The plant deals with very slow degradable feedstocks..incenerator recommended");
</script>     



<?php     
}   */
      
echo "<table border=1 >";
echo "<tr>";
echo'<td>Main Category<select name="MainCategoryID" id="maincatid" onchange="Filtercategory()">';

$sql = "SELECT maincatid, 
               maincatname 
        FROM bio_maincategorymaster
        ORDER BY maincatid DESC";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);





while ($myrow=DB_fetch_array($result)){
    if ($myrow['maincatid']==1){
        echo '<option selected value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    } else {
        echo '<option value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    }
    $MainCategory=$myrow['maincatid'];
}
if (!isset($_POST['MainCategoryID'])) {
    $_POST['MainCategoryID']=$MainCategory;
}

echo '</select></td>';














$sql_planttype="SELECT plant_type FROM bio_fs_entrydetails WHERE leadid=$lead";
$result_planttype=DB_query($sql_planttype,$db);
$row_planttype=DB_fetch_array($result_planttype);
$planttype=$row_planttype['plant_type'];


$sql="SELECT categoryid,categorydescription,maincatid from stockcategory,
             bio_maincat_subcat
             WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid
             AND bio_maincat_subcat.maincatid =1
             order by stockcategory.categorydescription asc";
      $result=DB_query($sql,$db);
echo '<td id="subcat">Sub Category<select name="caty" id="caty" style="width:200px">';
//      echo '<option value=0>Select category</option>';   

    $f=0;
  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['categoryid']==$planttype)  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow['categoryid'] . '">'.$myrow['categorydescription'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td>';



/*$sql="SELECT categoryid,categorydescription,maincatid from stockcategory,
             bio_maincat_subcat
             WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid
             AND bio_maincat_subcat.maincatid =1
             order by stockcategory.categorydescription asc";
      $result=DB_query($sql,$db);
echo '<td id="subcat">Sub Category<select name="caty" id="caty" style="width:200px">';
//      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          if($myrow['maincatid']==1){
             echo "<option selected value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }else{
             echo "<option value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }
          
      }
      echo '</select></td>';*/


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
                 WHERE  stockmaster.stockid='$stockid'
                 order by stockmaster.description asc";
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
       <td><a  style='cursor:pointer;'  id='$stockid' onclick=getresult(this.id,$first)>" . _('Select') . "</a></td></tr>";
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
  str3="&lead="+<?php echo $lead;?>;
  var str5="&first="+$("#first").val();  
  if ($("#descr").val()) {
  var str2="&descr="+$("#descr").val();
  } else {var str2="";}

  location.href="bio_selectplantfor_cp.php?categoryid="+str+str3+str5;
});

function getresult(choose,first){
//    alert(choose);   alert(first); 
    if(first!=5){                                                                                             // for cp
window.opener.location='bio_conceptproposal.php?stockid='+choose+'&lead='+<?php echo $lead;?>+'&first='+first;
    }
    else
    {                                                                                                        // for approval
     window.opener.location='bio_approval.php?stockid='+choose+'&lead='+<?php echo $lead;?>;   
    }
window.close();
}

function Filtercategory()       {

var str= document.getElementById("maincatid").value;
    
if (str=="")
  {
  document.getElementById("propertyvalue").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        
    document.getElementById("subcat").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_maincatsubcatcp.php?maincatid="+str,true);
xmlhttp.send(); 
    

}

</script>

</body></html>


