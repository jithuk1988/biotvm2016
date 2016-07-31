<?php
echo'<tr>';
echo'<th>slno</th>';
echo'<th>Item</th>';
echo'<th>Quantity</th>';
echo'</tr>';
while($myrow6=DB_fetch_array($result6))     {
      
      
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow6['stockid'].'" onclick=bomdetails(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow6['stockid'].'" onclick=bomdetails(this.id)>';
            $k++;
        }  
        
       $slno++;
            $part=$myrow6['stockid'];
            $quantity=$myrow6['qtyrequest'];
            printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow6['description'],
            $myrow6['qtyrequest']
            );

    $RowIndex = $RowIndex + 1;      
    
}  
?>
