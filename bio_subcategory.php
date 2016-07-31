<?php
  $PageSecurity = 80;
include ('includes/session.inc');
$title = _('ITEM SUB CATEGORY');
include ('includes/header.inc');
include ('includes/SQL_CommonFunctions.inc');
if(isset($_GET['delete'])){ 
    $id=$_GET['delete'];
 $sql= "DELETE FROM bio_categorize WHERE bio_categorize.catid=$id";
$result=DB_query($sql,$db); 
}
 if (isset($_POST['submit'])){//Chemical Steel PVC pipe Boughtout _subassembly Bought out spares
          $main=$_POST['main'];
          $sub=$_POST['sub'];
          $des=$_POST['des'];
  echo $sql1="INSERT INTO `substockcategory`(`maincatid`, `subcategoryid`, `subcategorydescription`) VALUES ($main,'$sub','$des')";
      $result1=DB_query($sql1,$db);//
         
  }  
  echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">PRODUCTION LOCATION </font></center>';
    echo'<table width=98% ><tr><td>'; 
echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
echo"<fieldset style='width:400px;height:170px'; overflow:auto;'>";
echo"<legend><h3>ITEM SUBCATEGORY</h3></legend>";

 echo'<table ><tr><td>';
 echo 'Subcategory Code</td><td><input type=text name=sub></td></tr>';
 echo '<tr><td>';
 echo 'Subcategory description</td><td><input type=text name=des></td></tr>';
 echo '<td>MAIN CATEGORY:</td>';
 echo '<td><select name="main">';
 $sql= "SELECT maincatid, 
               maincatname 
        FROM bio_maincategorymaster
        ORDER BY maincatid ";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
echo '<option></option>';
while ($myrow=DB_fetch_array($result)){
    if (isset($_POST['main']) AND $myrow['maincatid']==$_POST['main']){
        echo '<option selected value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    } else {
        echo '<option value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    }
}
 echo '</select></td></tr>';

    echo'<tr><td></td><td><input type=submit name=submit value="submit" onmouseover="test()" onclick="if(valid()==1)return false;"></td></tr>';
  echo "</table>";
   echo"</fieldset>";
    echo"<tr><td colspan='2'>";  
    
     echo"<fieldset  style='width:400px;'><legend>SUB-CATEGORIES</legend>";
     echo ' <div style="height: 400px; width: 100%; overflow: scroll;">';
    echo "<table >";echo"<tr style='background:#585858;color:white'>
    <td>SERIAL NO:</td><td>MAIN CATEGORY</td><td>SUB CATEGORY</td><td>CODE</td><td>DELETE</td></tr>"; //<td>CMCAPACITY</td><td>CAPACITY</td>


  
    $sql="SELECT `substockcategory`.`maincatid`,`substockcategory`.`subcategoryid`,
`substockcategory`.`subcategorydescription`,
`bio_maincategorymaster`.`maincatname`,
`bio_maincategorymaster`.`maincatid` 
FROM `substockcategory`,`bio_maincategorymaster` 
WHERE  `bio_maincategorymaster`.`maincatid` =`substockcategory`.`maincatid`";

  $d=1;  
$result3=DB_query($sql,$db);
              while($myrow3=DB_fetch_array($result3))
          {    //echo $myrow[0];
          $id=$myrow3['subcategoryid'];
          $c=$myrow3['maincatname'];
          $s=$myrow3['subcategorydescription'];
           $g=$myrow3['subcategoryid'];
        //  $h=$myrow3['cmcapacity'];<td>$h</td>
                   echo"<tr style='background:#A8A4DB'><td>$d</td><td>$c</td><td>$s</td><td>$g</td><td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td></tr>";
                   $d=$d+1;
                 }    
             echo"</table></div></fieldset>";
    echo"</td></tr>";
    echo "</form>" ; 
?>
<script>
function dlt(str){
location.href="?delete=" +str;         
}

</script>