<?php

              $SQL='SELECT locationname,loccode
                FROM locations ORDER BY loccode';

              $result1 = DB_query($SQL,$db);
                              
               echo '<tr><td>'. _('Stock Transfer from') . '*</td><td>';
               echo '<select name="fromstoreloc" id="fromstoreloc">';

              while ($myrow1 = DB_fetch_array($result1)) {
               if ($myrow1['loccode'] == $_POST['fromstoreloc']) {
                    echo '<option selected VALUE="' . $myrow1['loccode'] . '">' . $myrow1['locationame'];
                } else {
                    echo '<option value="' . $myrow1['loccode'] . '">' . $myrow1['locationname'];
                    
     }
              }
              
     echo'</select>';
     echo'</td></tr>';  
     
              $SQL='SELECT locationname,loccode
                    FROM locations ORDER BY loccode';

              $result1 = DB_query($SQL,$db);  
     
               echo '<tr><td>'. _('To store') . '*</td><td>';
               echo '<select name="storelocto" id="storelocto">';

                    
   
              while ($myrow1 = DB_fetch_array($result1)) {
               if ($myrow1['loccode'] == $_POST['fromstoreloc']) {
                    echo '<option selected VALUE="' . $myrow1['loccode'] . '">' . $myrow1['locationame'];
                } else {
                    echo '<option value="' . $myrow1['loccode'] . '">' . $myrow1['locationname'];
                    
     }
              }
              
     echo'</select>';
     echo'</td></tr>';  
     
     echo'<tr>';
     echo '<td>Delivery date*</td><td><input type=TEXT class=date name="Deliverydate" id="deliverydate" size=12 
           maxlength=12></td>';
     echo'</tr>';     
?>
