<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Countries - Maintenance');

include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Countries list Maintenance')
	. '" alt="" />' . _('Countries Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / delete Countries') . '</div><br />';

if(isset($_GET['delete'])){ $natid=$_GET['delete'];
$sql= "DELETE FROM bio_country WHERE bio_country.cid = $natid";
$result=DB_query($sql,$db); 
}
if (isset($_POST['countrysub'])){
     $country=$_POST['country'];
    $sql="INSERT INTO bio_country(bio_country.country) VALUES('$country')";
    $result=DB_query($sql,$db);   
    
}
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";   
        
 echo"<table><tr><td>";
 echo"<fieldset><legend>Add Country</legend>";
  echo"<table>";   
 echo"<tr><td>Country</td><td><input type='text' name='country' id='country' ></td></tr>";
 
  echo"<tr><td></td><td><input type='submit' name='countrysub' id='countrysub' value='submit'></td></tr>";
  echo"</td></tr></table>";  
  echo"</fieldset>";
  
    echo"<tr><td colspan='2'>";   echo"<fieldset><legend>Countries</legend><table>";echo"<tr style='background:#585858;color:white'><td>Country id</td><td>Country</td></tr>";  
    $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $cid=$myrow[0]; 
          $country=$myrow[1];    
                   echo"<tr style='background:#A8A4DB'><td>$cid</td><td>$country</td><td><a href='#' id='$cid' onclick='dlt(this.id)'>delete</a></td></tr>";      
                 }    
             echo"</table></fieldset>";
    echo"</td></tr>";         
 echo"</table>
 </form>";  
?>
<script>
function dlt(str){
location.href="?delete=" +str;         
 
}

</script>
