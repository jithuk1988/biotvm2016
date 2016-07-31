<?php
  $PageSecurity = 11;
      
 include('includes/session.inc');      
 $_POST['StockID ']=$_GET['item'];  
 if($_GET['item']!='')     {
     
  $_SESSION['StockID']=$_GET['item'];  
  $_SESSION['SeasonID']=$_GET['month'];   
 }
 
    //$part=$_SESSION['StockID'];
   // $SeasonID=$_SESSION['SeasonID'];  
    $part=$_GET['item'];
    $SeasonID=$_GET['month'];  
    $yr=$_GET['year'];
 
    $where = "";
    if ($part) {
        $where = ' WHERE mrpdemands.stockid =' . "'"  .  $part . "' AND 
                         mrpdemands.season_id=$SeasonID     AND
                         mrpdemands.year=$yr     AND  
                         mrpdemands.stockid = stockmaster.stockid";
    
    // If part is entered, it overrides demandtype

 
        
    
    $sql = 'SELECT mrpdemands.demandid,
                   mrpdemands.stockid,
                   mrpdemands.quantity,
                   mrpdemands.statusid,
                   mrpdemands.duedate,
                   stockmaster.description
        FROM mrpdemands,stockmaster
        ' .
         $where    . ' ORDER BY mrpdemands.duedate';      
         $result=DB_query($sql,$db);
 }
      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th>" . _('Sl no:') . "</th>
        <th>" . _('Item') . "</th>
        <th>" . _('Demand Quantity') . "</th>
        <th>" . _('Status') . "</th>
        <th>" . _('Date') . "</th>
        </tr></thead>";
    echo $tableheader;
    $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
    while ($myrow=DB_fetch_array($result))   {
        $slno++; 
         if($myrow['statusid']!=3)
         {
                $a="<input type='text'   value='%s' readonly onchange=autochange(".$myrow['demandid'].",this.value);>";
         }
         else
         {
             $a="<input type='text'  value='%s'  onchange=autochange(".$myrow['demandid'].",this.value,$slno);>";
         }
        $sql5="SELECT status
              FROM dev_mrpdemandstatus
              WHERE statusid=".$myrow['statusid']."";
        $result5=DB_query($sql5,$db);
        $myrow5=DB_fetch_array($result5);
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['demandid'].'" >';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['demandid'].'" >';
            $k++;
        }  
        
       
        printf("<td>$slno</td>
            <td>%s</td>
            <td id='edit1".$slno."'>$a</td>
            <td>%s</td>    
            <td>%s</td>
            </tr>",
            $myrow['description'],
            $myrow['quantity'],
            $myrow5[0],
            $myrow['duedate']
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
