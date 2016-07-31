<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 

      
if($_GET['third'] )
 {

      echo "<select " . (in_array('ComponentCode',$Errors) ?  'class="selecterror"' : '' ) ." tabindex='1' name='Component' style='width:180px;'>";
      $sql = "SELECT stockmaster.stockid,stockmaster.description FROM stockmaster WHERE stockmaster.categoryid='".$_GET['third']."' ";
        $result = DB_query($sql,$db);
        echo "<option VALUE=0".'>';
        while ($myrow = DB_fetch_array($result)) {
             
                echo "<option VALUE='";
            echo $myrow['stockid'] . "'>" . $myrow['description'];
 }}
  if($_GET['second']) 
  { 
 //WHERE stockcategory.categoryid=(SELECT stockmaster.categoryid FROM stockmaster WHERE stockmaster.mbflag=".$com1." )";//".$main."                   
                   
 echo "<select name='com2' id='com2' style='width:190px' onchange='view3(this.value)'>";
     $sql = "SELECT categoryid, categorydescription 
        FROM stockcategory,
             bio_maincat_subcat
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid    AND bio_maincat_subcat.subcatid ='".$_GET['second']."'";
//$sql="SELECT * FROM  `stockcategory`";
// $sql="SELECT * FROM `stockcategory` INNER JOIN `stockmaster` ON `stockcategory`.`categoryid`= `stockmaster`.`categoryid`  WHERE `stockmaster`.`mbflag`='".$com1."')"; 
$rst=DB_query($sql,$db);
echo '<option value=0></option>';//
while($myrowc=DB_fetch_array($rst))
{
    /* if ($myrowc[categoryid]==$com2)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc['categoryid'].'">'.$myrowc['categorydescription'].'</option>';*/

 echo '<option value='.$myrowc[categoryid].'>'.$myrowc[categorydescription].'</option>';
 }
  }
 
     if($_GET['first'])
     {
         echo "<select name='com' id='com' style='width:190px' onchange='view2(this.value)'>";
          $sql = "SELECT `subcategoryid`,`subcategorydescription` FROM `substockcategory`";
           
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
          
    // }
?>
