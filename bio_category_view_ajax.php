<?php
$PageSecurity = 80;  
include('includes/session.inc'); 


  $main=$_GET['sub'];
  $sub=$_GET['item'];


 if($_GET['item'] && $_GET['sub'] )
 {
     

 echo "<table><tr><th>SL NO</th><th>StockID</th><th>Item Name</th><th>Unit</th><th>Quantity</th><th>View</th><tr>";
   $sql="select  stockmaster.stockid,
                        stockmaster.description,
                        locstock.quantity,
                        locstock.loccode,
                        stockmaster.units
                from stockmaster,locstock 
                    where stockmaster.stockid=locstock.stockid AND locstock.quantity>=1 AND stockmaster.categoryid='".$sub."' ";//AND locstock.quantity>=1
    $result=DB_query($sql,$db);//
      $slno=1;$k=0;
      while($myrowd=DB_fetch_array($result))
{
      if ($k==0)
          {
            echo '<tr class="EvenTableRows" id="'.$myrowd['stockid'].'"  href="" onclick="qty_in_loc(this.id);return false">';
             $k=1;
          }else 
          {
            echo '<tr class="OddTableRows" id="'.$myrowd['stockid'].'"  href="" onclick="qty_in_loc(this.id);return false">';
             $k=0;     
          } 
         // $stockid= $myrowd['stockid'];
       echo"<td>".$slno."</td>";
         echo "<td>".$myrowd['stockid']."</td> ";
      echo "<td>".$myrowd['description']."</td> ";
      echo"<td>".$myrowd['units']."</td>";
      echo"<td>".$myrowd['quantity']."</td>";
       echo'<td id="'.$myrowd['stockid'].'" ><a >Select</a></td>';//;return 0;
      echo "</tr>";  
      $slno++; 
      }
      
      echo"</table>";
 
 }
 else{
      if($_GET['sub'])
 {
   echo" <td>Main Category</td><td>";
 echo '<select name="main" id="main" style="width:190px" tabindex=11 onchange="showsub(this.value)">';
 $sql="SELECT
  maincatid,
  maincatname
FROM bio_maincategorymaster";

$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[maincatid]==$main)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[maincatid].'">'.$myrowc[maincatname].'</option>';
 }
  echo '</select>';
  echo'</td></tr>'; 
   
   echo"<td >Sub Category</td><td>";
 echo '<select name="sub" id="sub" style="width:190px" tabindex=11 onchange="display(this.value)">';
$sql="SELECT
 maincatid,
  subcatid,
  stockcategory.categorydescription
FROM bio_maincat_subcat,stockcategory
where bio_maincat_subcat.maincatid=".$main." AND stockcategory.categoryid=bio_maincat_subcat.subcatid ";
$rst=DB_query($sql,$db);
echo '<option value=0></option>';//
while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[subcatid]==$sub)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[subcatid].'">'.$myrowc[categorydescription].'</option>';
 }

  echo '</select>';
  echo'</td></tr>'; 
 
/*if($sub!="" && $sub!=null  )
 {


echo"<tr><td>Item Category</td><td>";
 echo '<select name="cat" id="cat" style="width:190px" tabindex=11 onchange="show(3)">';
 $sql="SELECT
  categoryid,
  categorydescription
FROM stockcategory
 ";//where stockcategory.categoryid='".$sub."'
$rst=DB_query($sql,$db);//
while($myrowc=DB_fetch_array($rst))
{
 echo '<option value='.$myrowc[categoryid].'>'.$myrowc[categorydescription].'</option>';
 }
  echo '</select>';
  echo'</td>'; 
  echo'</tr>';
 }

    */
 }
 }
 
?>
