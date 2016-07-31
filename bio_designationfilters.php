<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Designation filters');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('States/Union Territory Maintenance')
    . '" alt="" />' . _('Designation filters') . '</p>';
echo '<div class="page_help_text">' . _('Assign Filters') . '</div><br />';

if(isset($_GET['delete'])){ $id=$_GET['delete'];
$sql="DELETE FROM bio_filters_user WHERE bio_filters_user.userfilterid = $id";

$result=DB_query($sql,$db);
}
if (isset($_POST['statesub'])){
    
    $desgid=$_POST['Designation'];
    $filterid=$_POST['Filters'];
    
    $sql7="SELECT *
           FROM bio_filters_user
           WHERE desgid=$desgid     AND
                 filterid=$filterid";
    $result7=DB_query($sql7,$db);
    $myrow7=DB_num_rows($result7);
    
    if($myrow7==0)       {
                 
    $sql="INSERT INTO bio_filters_user (desgid,filterid) VALUES($desgid,$filterid)";
    $result=DB_query($sql,$db);
    
    }else {
        
?>
<script>
alert("Data already exists");
</script>
<?php
    }
    
}
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";   
        
 echo"<table width='400px'><tr><td>";
 echo"<fieldset><legend>Add Filters</legend>";
  echo"<table>";   

     
     
     $sql="SELECT * FROM bio_designation ORDER BY desgcode";
    $result=DB_query($sql,$db);
   
    echo"<tr><td>Designation</td><td>";
    
  echo '<select name="Designation" id="designation" style="width:190px">';

  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['desgid']==$_POST['Designation'])
    { 
    echo '<option selected value="';
    } else 
    {
//    if ($f==0)
//        {
//        echo '<option>';
//        }
        echo '<option value="';
    }
    echo $myrow1['desgid'] . '">'.$myrow1['designation'];
    echo '</option>';
   // $f++;
   } 
  echo '</select></td></tr>'; 
  

     $sql="SELECT * FROM bio_filters ORDER BY filtersl";
    $result=DB_query($sql,$db);
   
    echo"<tr><td>Filters</td><td>";
    
  echo '<select name="Filters" id="filters" style="width:190px">';

  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['filtersl']==$_POST['Filters'])
    { 
    echo '<option selected value="';
    } else 
    {
//    if ($f==0)
//        {
//        echo '<option>';
//        }
        echo '<option value="';
    }
    echo $myrow1['filtersl'] . '">'.$myrow1['filterdesc'];
    echo '</option>';
   // $f++;
   } 
  echo '</select></td></tr>'; 

       
  echo"<tr><td></td><td><input type='submit' name='statesub' id='statesub' value='submit'></td></tr>";   echo"</td></tr></table>";  
  echo"</fieldset>";

    echo"<tr><td colspan='2'>";   
    echo"<fieldset><legend>Degignation filters</legend>
    <table width='400px'>";
    echo"<tr style='background:#585858;color:white'>
    <td>Designation</td><td>Filter</td></tr>";
           $sql="SELECT bio_filters_user.userfilterid,
                        bio_filters_user.desgid,
                        bio_filters_user.filterid,
                        bio_designation.designation,
                        bio_filters.filterdesc
                 FROM   bio_filters_user,
                        bio_designation,
                        bio_filters
                 WHERE  bio_designation.desgid =bio_filters_user.desgid    AND
                        bio_filters.filtersl =bio_filters_user.filterid
                 ORDER BY bio_filters_user.desgid";
                        
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];
          $id=$myrow['userfilterid'];
          $designation=$myrow['designation'];
          $filter=$myrow['filterdesc'];
          
          echo"<tr style='background:#A8A4DB'>
          <td>$designation</td><td>$filter</td>
          
          <td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td></tr>";
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
