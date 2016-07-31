<?php
$PageSecurity = 80;
include('includes/session.inc');

 
 
 
  if(isset($_GET['plant']))
  {
 $plant=$_GET['plant'];
  $optional=$_GET['optional'];
  $quantity=$_GET['quantity'];
  
 $sql="INSERT INTO bio_temp(plantid,optionalid,quantity) VALUES ('".$plant."','".$optional."','".$quantity."')";  
    DB_query($sql,$db);
  $sqlprnt="Select description from stockmaster where stockid='".$plant."'";
    $mrow=DB_query($sqlprnt,$db);
    $rows=DB_fetch_row($mrow) ;
    echo "<center>Optional Items for <b>".$rows[0]."</b></center>";
 }
  
  
  echo '<div id=display>';
  $sql1="select description,quantity from stockmaster,bio_temp where stockmaster.stockid=bio_temp.optionalid";
  $sel=DB_query($sql1,$db);
  echo "<table style='width:100%'>";
   echo "<thead>
                <tr BGCOLOR =#800000><th>" . _('Sl no:') . "</th>
                <th>" . _('Optional Item') . "</th>
                 <th>" . _('Quantity') . "</th>   
                </tr></thead>";
                $Slno=0;
                while($result=DB_fetch_array($sel))
                {
                     $Slno++;
                    printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>                 
        <td><a onclick='change()'> " . _('Edit') . "</a></td>  
         
        
        </tr>",
        
        $Slno,
        $result[0],
        $result[1],   
        $_SERVER['PHP_SELF'] . '?' . SID,
        $result[0]);
    }
    echo "<table>";
    echo "</div>";
  
?>

