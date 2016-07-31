<?php
     $PageSecurity = 80;   
include('includes/session.inc');

$title = _('Quantity in Location');

include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Quantity in Location') . '</p>';

    
 $sql="SELECT locations.locationname,locstock.quantity,locations.locationname  
FROM locstock,locations 
        WHERE locstock.loccode=locations.loccode
        AND locstock.stockid='".$_GET['stock']."'"; 
$result=DB_query($sql,$db);

echo "<div style='height:500px;  overflow:scroll;'>";    
echo"<table width=600px>";
echo"<tr><td>Location</td><td>Plant</td><td>Quantity</td></tr>";
while($myrow=DB_fetch_array($result))
{
                      if ($k==1)
                      {
                        echo '<tr class="EvenTableRows">';
                        $k=0;
                      }else 
                      {
                        echo '<tr class="OddTableRows">';
                        $k=1;     
                      }
                      $sql_desc="SELECT description FROM stockmaster WHERE stockid='".$_GET['stock']."'";
                      $result_desc=DB_query($sql_desc,$db);
                      $myrow_desc=DB_fetch_array($result_desc);
                      echo"<td>".$myrow['locationname']."</td>"; 
                      echo"<td>".$myrow_desc['description']."</td>";
                      echo"<td>".$myrow['quantity']."</td>";  
                      echo"</tr>";
} 
 
 echo"</table>"; 
 echo"</div>";
?>
