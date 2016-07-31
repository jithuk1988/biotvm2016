<?php
  $PageSecurity = 80;
include ('includes/session.inc');
$title = _('GRN');
include ('includes/header.inc');
//include ('includes/SQL_CommonFunctions.inc');

  echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">GRN CREATION</font></center>';
    //echo'<table width=98% ><tr><td>'; 
   // echo'<div >'; 
    echo "<form method='post'  action='" . $_SERVER['PHP_SELF'] . "'>";  
    echo"<fieldset style='width:400px;height:155px'; overflow:auto;'>";
    echo"<legend><h3>Purchase</h3></legend>";
    echo '<table><tr><td>';

    echo '' . _('Sub Category fgfdghdf') . ':</td><td id="combo1"><select name="subcat"   style="width:200px"   onchange="view(this.value)"';

    $sql = "SELECT `subcategoryid`,`subcategorydescription` FROM `substockcategory` where maincatid=15";
    $ErrMsg = _('The stock categories could not be retrieved because');
    $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
    $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
    echo '<option></option>';
 
    while ($myrow=DB_fetch_array($result))
         {
    
          if ($myrow['subcategoryid']==$_POST['subcat'] )
          {
           echo '<option selected value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'].'</option>';
          } 
          else
          {
           echo '<option value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'].'</option>';
          }
         }



   echo '</select>';
    echo '</td></tr>
   <tr><td>Subsub Category</td><td id=combonew><select name="CategoryID" style="width:200px" onchange="view2(this.value)">';

   $sql = "SELECT categoryid, categorydescription 
        FROM stockcategory,
             bio_maincat_subcat
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid     AND
              bio_maincat_subcat.maincatid =15 AND bio_maincat_subcat.subcatid ='".$_POST['subcat']."'";
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  
   echo '<option value=0></option>';
while ($myrow=DB_fetch_array($result)){
    if (!isset($_POST['CategoryID']) or $myrow['categoryid']==$_POST['CategoryID']){
        echo '<option  selected value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'].'</option>';
    } else {
        echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'].'</option>';
    }

}

   echo '</select></td></tr></td></tr>
   <tr><td>Item name</td><td id=combo2>';
   echo '<select name="combo2" style="width:200px"  >';

      $sql="SELECT
           `stockmaster`.`stockid`,
        `stockmaster`.`description` from `stockmaster` where `stockmaster`.mbflag='B'";
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
   echo '<option></option>';
   while ($myrow=DB_fetch_array($result))
        {
          if($myrow['stockid']==$_POST['combo2'])
            {
             echo '<option selected value="'. $myrow['stockid'] . '">' . $myrow['description'].'</option>';
            }
            else
             {
              echo '<option value="'. $myrow['stockid'] . '">' . $myrow['description'].'</option>';
              }
    
        }
   echo '</select></td></tr><tr><td>supplier</td><td><select name=supply style="width:200px" >';
    $sql1="select * from  suppliers";
   $result1 = DB_query($sql1,$db);
   echo '<option></option>';
   while ($myrow1=DB_fetch_array($result1))
        {
          if($myrow1['supplierid']==$_POST['supply'])
            {
              echo '<option selected value="'. $myrow1['supplierid'] . '">' . $myrow1['suppname'].'</option>';
            }else
            { echo '<option value="'. $myrow1['supplierid'] . '">' . $myrow1['suppname'].'</option>';
            }
        }
echo '</select></td></tr></table>';
   echo '<center>bjjghn;kljhoui;</center>';//<input type="submit" name="submit" id="submit"/>
   echo '</fieldset></form>';


  
