<?php
  $PageSecurity = 80;
include('includes/session.inc');
if(isset($_GET['plant']))
{
    

$plant=$_GET['plant'];


  $sql="SELECT stockid,description
        FROM stockmaster
        INNER JOIN     stockcategory ON  stockmaster.categoryid=stockcategory.categoryid
        INNER JOIN   bio_maincat_subcat ON            bio_maincat_subcat.subsubcatid= stockcategory.categoryid
             WHERE bio_maincat_subcat.subcatid =11
             AND stockmaster.categoryid='".$_GET['plant']."'";
             
             $result=DB_query($sql,$db);             
           
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
echo'<select name="plantoption" id="plantoption"  style="width:190px">';      
//      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) 
      {
          echo "<option value=".$myrow['stockid'].">".$myrow[description]."</option>";
      }
      echo '</select>';
}


/////////////////////////////////////////////////
   elseif(isset($_GET['item']))
   {
       
      
      $item=$_GET['item'];


  $sql = "SELECT stockid,description
        FROM stockmaster
        INNER JOIN     stockcategory ON  stockmaster.categoryid=stockcategory.categoryid
        INNER JOIN   bio_maincat_subcat ON            bio_maincat_subcat.subsubcatid= stockcategory.categoryid
             WHERE bio_maincat_subcat.subcatid !=11
             AND stockmaster.categoryid='".$_GET['item']."'"; 
           
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
            echo '<select name="optionalitem" id="optionalitem"  style="width:190px">';
      while ( $myrow = DB_fetch_array ($result) ) 
      {
          echo "<option value=".$myrow['stockid'].">".$myrow['description']."</option>";
      }
      echo '</select>';
   }

   
   ?>


