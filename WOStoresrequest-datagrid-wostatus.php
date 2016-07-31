<?php
  $PageSecurity = 11;

 include('includes/session.inc');
 $_POST['Wono']=$_GET['r'];  


    $sql ="SELECT womaterialrequest.reqno,
                  womaterialrequest.reqty,
                  womaterialrequest.statusid,
                  dev_srstatus.srstatus
           FROM womaterialrequest,dev_srstatus
           WHERE womaterialrequest.wono=".$_POST['Wono']."      AND
                 womaterialrequest.statusid!=4      AND
                 womaterialrequest.statusid=dev_srstatus.srstatusid";      
    $result=DB_query($sql,$db);

      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=5 text-align=left>Item selected is: ".$part."</th></tr><tr>
        <th>" . _('Sl no:') . "</th>
        <th>" . _('SR no:') . "</th>
        <th>" . _('SR qty') . "</th>
        <th>" . _('status') . "</th>
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
            echo '<tr class="EvenTableRows"  id="'.$myrow['reqno'].'" onclick=loadfromgrid(this.id,1)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['reqno'].'" onclick=loadfromgrid(this.id,1)>';
            $k++;
        }  
        
       $slno++; 
    printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow['reqno'],
            $myrow['reqty'],
            $myrow['srstatus']
            );

    $RowIndex = $RowIndex + 1;
//end of page full new headings if
    }
//end of while loop
    echo'<tfoot><tr>';
    echo'<td colspan=7>Number of records:'.$slno.'</td>';
    echo'</tr></tfoot>';
    echo'</tbody>';
    echo '</table>';

?>
