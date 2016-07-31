<?php
  $PageSecurity = 5;
 
// include('includes/session.inc');
// $_POST['StockID ']=$_GET['p'];  
// if($_GET['p']!='')     {
//     
//  $_SESSION['StockID']=$_GET['p'];  
//  $_SESSION['SeasonID']=$_GET['q'];   
// }

if($_GET['r']==3)        {

$_SESSION['StockID']=$_GET['p'];    
    
}
        
    $part=$_SESSION['StockID'];
    $SeasonID=$_SESSION['SeasonID'];  
    $where = "";
    if ($part) {
        $where = "woitems.stockid='".$part."' AND ";
    
    // If part is entered, it overrides demandtype

    }
      
    $sql ="SELECT woitems.qtyreqd,
                  woitems.wo,
                  woitems.stockid,
       wostatus.statusid
FROM woitems,wostatus

WHERE ".$where."
      woitems.wo=wostatus.wono AND
      wostatus.statusid!=1";      
         $result=DB_query($sql,$db);

      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=5 text-align=left>Item selected is: ".$part."</th></tr><tr>
        <th>" . _('Sl no:') . "</th>
        <th>" . _('WO no:') . "</th>
        <th>" . _('Item') . "</th>
        <th>" . _('WO qty') . "</th> 
        <th>" . _('WO status') . "</th>
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
            echo '<tr class="EvenTableRows"  id="'.$myrow['wo'].'" onclick=loadfromgrid(this.id,2)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['wo'].'" onclick=loadfromgrid(this.id,2)>';
            $k++;
        }  
        
       $slno++; 
        printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow['wo'],
            $myrow['stockid'],
            $myrow['qtyreqd'], 
            $myrow['statusid']
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
