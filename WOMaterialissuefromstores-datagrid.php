<?php
      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=6 text-align=left>Item selected is: ".$part."</th></tr><tr>
        <th>" . _('Sl no') . "</th>
        <th>" . _('Date') . "</th>
        <th>" . _('SR no') . "</th>
        <th>" . _('WO no') . "</th>
        <th>" . _('Item') . "</th>
        <th>" . _('SR Qty') . "</th>
        <th>" . _('SR status') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
      while ($myrow=DB_fetch_array($result))   {
      
      
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['reqno'].'" onclick=datagridload(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['reqno'].'" onclick=datagridload(this.id)>';
            $k++;
        }  
        
       $slno++;
       
       $redate=ConvertSQLDate($myrow['reqdate']);
       
               printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $redate,
            $myrow[0],
            $myrow[1],
            $myrow['stockid'],
            $myrow['reqty'],
            $myrow['srstatus']
            );

    $RowIndex = $RowIndex + 1;    
          
      }  
?>
