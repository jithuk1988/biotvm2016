<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Product Print');
include('includes/header.inc');

echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">PRODUCT RECIEPT</font></center>'; 
                           
echo "<table style='width:55%'><tr><td>";
echo "<fieldset style='width:97%;'>";     
echo "<legend><h3>PRODUCTS</h3>";
echo "</legend>";

echo "<form method='post' action='plist_xl.php'>";
echo "<input type='submit' name='submit' value='Export'>";
echo "</form>";

echo "<div style='height:300px; overflow:auto;'>";
echo "<table style='border:0px solid #F0F0F0;width:100%'>";



echo "<tr></tr><tr></tr><tr><th style=width:10%>Sl.No</th><th style=width:10%>Size</th><th style=width:20%>Productid</th><th style=width:55%>Description</th></tr>";

$sql1="select stockcatproperties.stkcatpropid from stockcatproperties
      where stockcatproperties.label='Size(cum)'";
      $result1=DB_query($sql1,$db); 
      $row1=DB_fetch_array($result1);
      
$sql2="select stockitemproperties.value,stockitemproperties.stockid from stockitemproperties
       where stockitemproperties.stkcatpropid=".$row1['stkcatpropid']."";      
              
              $result2=DB_query($sql2,$db);
              $slno=1; 
              $k=0;
              while($row2=DB_fetch_array($result2))
              {
                 $size=$row2['value'];
                 $stockid=$row2['stockid'];

$sql3="select stockmaster.description from stockmaster
       where stockmaster.stockid='$stockid'";
                                                
                $result3=DB_query($sql3,$db);
                
                while($row3=DB_fetch_array($result3)) 
                {               
                     if ($k==1)
                    {
                        echo '<tr class="EvenTableRows">';
                        $k=0;          
                    }
                    else 
                    {
                        echo '<tr class="OddTableRows">';
                        $k=1;     
                    }
    
                    echo "<td>$slno</td><td>$size</td><td>$stockid</td><td>".$row3['description']."</td>";
                    //echo "<td><a id= onclick=select(this.id)>Print</a></td><tr>"; 
                     $slno++;
                     echo "</tr>";
                }
                
              }
 echo "</table></fieldset></td></tr></table>";
//-----------------------------------------------------------------------      

?>

     <!-- Not Completed
<script>
function select(str)
{
    window.location="bio_productPDF.php",+str;
}
</script>
            -->
            