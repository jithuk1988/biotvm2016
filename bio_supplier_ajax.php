<?php
  $PageSecurity = 80;  
include('includes/session.inc'); //

  $com1=$_GET['first']; 
 $sub=$_GET['second'];
      
if($com1 && $sub )
 {

     echo "<select name='com3' id='com3'style='width:190px'>";
      $sql = "SELECT stockmaster.stockid,stockmaster.description FROM stockmaster WHERE stockmaster.categoryid='".$sub."' AND stockmaster.mbflag='".$com1."' ";
        $result = DB_query($sql,$db);
        echo "<option VALUE=0".'>';
        while ($myrow = DB_fetch_array($result)) {
             
                echo "<option VALUE='";
            echo $myrow['stockid'] . "'>" . $myrow['description'];
 }}
     else{
  if($_GET['first']) 
  { 
 //WHERE stockcategory.categoryid=(SELECT stockmaster.categoryid FROM stockmaster WHERE stockmaster.mbflag=".$com1." )";//".$main." 

 
 echo "<select name='com2' id='com2' style='width:190px' onchange='view2(this.value)'>";
$sql="SELECT * FROM  `stockcategory`";

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
 

     }
    // }
?>
