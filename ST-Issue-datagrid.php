<?php

    echo '<table width=100%>';

    $tableheader = '<tr><th>'. _('Select'). '</th>
                        <th>'. _('Request no'). '</th>
                        <th>'. _('Item'). '</th>
                        <th>'. _('Delivery date'). '</th>
                        <th>'. _('Delivery Time'). '</th>
                        <th>'. _('To store'). '</th>
                        <th>'. _('Quantity'). '</th
                        </tr>';
    echo $tableheader;

       $sql2='SELECT  stocktransfer.slno, 
                      stocktransfer.itemcode,
                      stocktransfer.deliverydate,
                      stocktransfer.deliverytime,
                      stocktransfer.tostore,
                      stocktransfer.quantity,
                      stockmaster.description,
                      locations.locationname                                 
                      FROM stocktransfer,stockmaster,locations 
                      WHERE done=0      AND
                      stocktransfer.itemcode=stockmaster.stockid    AND
                      stocktransfer.tostore=locations.loccode
                      order by slno';
                      $result2 = DB_query($sql2,$db);
   
        
             $PropertyCounter=0;
             $k=0;
             
while($myrow2=DB_fetch_array($result2)) {
    
                            if ($k==1)
        {
            echo '<tr class="EvenTableRows">';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows">';
            $k++;
        } 
                    

                  $qty=$myrow2[4];
                  $itemcode= $myrow2['1'];
                    

  echo '<form action="' . $_SERVER['PHP_SELF'] . '?'.SID.'" method=post>';
       printf(" <input class='number' type='hidden' size=6 value='$myrow2[1]' name=".'itemcode' . $PropertyCounter." >
                <input class='number' type='hidden' size=6 value='$myrow2[0]' name=".'reqstno' . $PropertyCounter." >
                <input type=hidden name=".'PropertyCounter' . $PropertyCounter."  value='  $PropertyCounter  '>
            <td><input type=submit name='Submit' value='$PropertyCounter'</td>    
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow2['0'],
            $myrow2[6],
            $myrow2['2'],
            $myrow2['3'],
            $myrow2[7],
            $myrow2['5']
            
            );
            
            
            $PropertyCounter++;
                
    }
    

    echo '</table>
        <input type=hidden name="LinesCounter" value='. $i .'>';
            echo '</form></div>';
        echo '<br><hr>';
    echo '<script  type="text/javascript">defaultControl(document.forms[0].StockID0);</script>';


?>
