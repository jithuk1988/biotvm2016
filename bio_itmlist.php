<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
//include('includes/getinstallationdate.php');
        // echo"jjj";

    
               echo "<table name='stockss'>";
   $cat=$_GET['category'];
   $maincat=$_GET['maincategory'];
    $_GET['maincat']; 
 
    /*if($_GET['category'])
    {
        
    
     $sql="SELECT stockmaster.stockid,stockmaster.units,stockmaster.description,sum(locstock.quantity) as qoh from stockmaster,locstock where stockmaster.categoryid='".$cat."' and stockmaster.stockid=locstock.stockid group by locstock.stockid Order by qoh asc";
     $result=DB_query($sql,$db);
                  $slno=1;
                           echo" <center><div id='ajaxloader'><img style='display:none' src='ajax-loader-large.gif' /></div></center>";
                  echo "<tr><th>SL NO</th><th>STOCKID</th><th>DESCRIPTION</th><th>UNITS</th><th>QOH</th><th>Edit</th><th>Adjustment</th><th>Ledger</th></tr>";
     while($row1=DB_fetch_array($result)){
         if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          $adj=$row1['stockid']."&Description=".$row1['description'];
          echo"<td >".$slno."</td>
        <td>".$row1['stockid']."</td>
        <td>".$row1['description']."</td>
        <td>".$row1['units']."</td>
        <td>".$row1['qoh']."</td>
        <td><a href id='".$row1['stockid']."' onclick='edititem(this.id);return false;'>".Edit."</a></td>
        <td><a href id='".$adj."' onclick='adjust(this.id);return false;'>".Adjustment."</a></td>
        <td><a href id='".$row1['stockid']."' onclick='move(this.id);return false;'>".Ledger."</a></td>
        </tr>";
         $slno++;
     }
                 echo '</table>';
    
    //echo '<tr><td>'.$_GET['loc1'].'</td></tr>';


    }
    
    if($_GET['maincategory'])
    {
        
    
     $sql="SELECT stockmaster.stockid,stockmaster.units,stockmaster.description,sum(locstock.quantity) as qoh
 from 
stockmaster,locstock 
where stockmaster.categoryid in (SELECT categoryid
FROM `stockcategory`
WHERE `stocktype` LIKE '".$_GET['maincategory']."')
and stockmaster.stockid=locstock.stockid
group by locstock.stockid Order by qoh asc";

     $result=DB_query($sql,$db);
                  $slno=1;
echo" <center><div id='ajaxloader'><img style='display:none' src='ajax-loader-large.gif' /></div></center>";
echo "<tr><th>SL NO</th><th>STOCKIDyyyyy</th><th>DESCRIPTION</th><th>UNITS</th><th>QOH</th><th>Edit</th><th>Adjustment</th><th>Ledger</th></tr>";
     while($row1=DB_fetch_array($result)){
         if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          $adj=$row1['stockid']."&Description=".$row1['description'];
          echo"<td width='10'>".$slno."</td>
        <td width='10'>".$row1['stockid']."</td>
        <td width='10'>".$row1['description']."</td>
        <td width='10'>".$row1['units']."</td>
        <td width='10'>".$row1['qoh']."</td>
        <td width='10'><a href id='".$row1['stockid']."' onclick='edititem(this.id);return false;'>".Edit."</a></td>
        <td width='10'><a href id='".$adj."' onclick='adjust(this.id);return false;'>".Adjustment."</a></td>
        <td width='10'><a href id='".$row1['stockid']."' onclick='move(this.id);return false;'>".Ledger."</a></td></tr>";
         $slno++;
     }
                 echo '</table>';
    
    //echo '<tr><td>'.$_GET['loc1'].'</td></tr>';


    }*/
     if($_GET['maincat'])
    {
  
// echo"dgfdsgsdf";
// echo"<tr id='showdistrict'><td>Item Category</td><td>";
 echo '<select name="cat" id="cat" style="width:190px" tabindex=11 >';//onchange="showitem(this.value)"
 $sql="SELECT
  categoryid,
  categorydescription
FROM stockcategory where   stocktype='".$_GET['maincat']."'";
$rst=DB_query($sql,$db);
while($myrowc=DB_fetch_array($rst))
{
 echo '<option value='.$myrowc[categoryid].'>'.$myrowc[categorydescription].'</option>';
 }
  echo '</select>';
  echo'</td>'; 
        
    }
    

?>
