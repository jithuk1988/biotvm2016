<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
$sub2=$_get['sub2'];
 $main=$_GET['sub']; 
 $sub=$_GET['item'];
  if($_GET['item'] )
 {echo "<select tabindex='2' id='fgitem' name='Fgitem' style='width:180px;'
                                                                       onchange=datagridload(this.value)>";
      $sql = "SELECT stockmaster.stockid,stockmaster.description FROM stockmaster WHERE stockmaster.categoryid='".$sub."' and stockmaster.mbflag='M'";
        $result = DB_query($sql,$db);
        echo "<option VALUE=0".'>';
        while ($myrow = DB_fetch_array($result)) {
             
                echo "<option VALUE='";
            echo $myrow['stockid'] . "'>" . $myrow['description'];
 }}
  
  if($_GET['sub']) 
  {
      echo '<select name="sub" id="sub" style="width:190px" tabindex=11 onchange="display(this.value)">';
      $sql="SELECT `subcategoryid`,`subcategorydescription` FROM `substockcategory` where maincatid='".$_GET['sub']."'";
$rst=DB_query($sql,$db);
echo '<option value=0></option>';//
while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[subcategoryid]==$sub)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[subcategoryid].'">'.$myrowc[subcategorydescription].'</option>';
 }
  }
  if($_GET['sub2']) 
  {
       echo '<select name="sub" id="sub" style="width:190px" tabindex=11 onchange="display1(this.value)">';
    $sql="SELECT categoryid, categorydescription 
        FROM stockcategory,
             bio_maincat_subcat
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid  
          AND bio_maincat_subcat.subcatid ='".$_GET['sub2']."'";
        $result = DB_query($sql,$db);
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


/*     $sql="SELECT
 maincatid,
  subcatid,
  stockcategory.categorydescription
FROM bio_maincat_subcat,stockcategory
where bio_maincat_subcat.maincatid=".$main." AND stockcategory.categoryid=bio_maincat_subcat.subcatid and stockcategory.stocktype='F'";*/
?>
