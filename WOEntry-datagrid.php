<?php
  $PageSecurity = 11;

 include('includes/session.inc');
  
if((isset($_GET['p'])) AND (($_GET['p']!="") OR ($_GET['p']!="undefined")))       {

  $_POST['StockID ']=$_GET['p'];    
  $_SESSION['StockID']=$_GET['p'];  
  $_SESSION['SeasonID']=$_GET['q'];   
 }
 
    $part=$_SESSION['StockID'];
    $SeasonID=$_SESSION['SeasonID'];  
    $where = "";
    if ($part) {
        $where = ' WHERE ((mrpdemands.statusid=4) OR (mrpdemands.statusid=5)) AND mrpdemands.stockid =' . "'"  .  $part . "' AND mrpdemands.season_id=$SeasonID";
    
    // If part is entered, it overrides demandtype

  

    $sql = 'SELECT mrpdemands.demandid,
                   mrpdemands.stockid,
                   mrpdemands.quantity,
                   mrpdemands.statusid,
                   mrpdemands.duedate,                 
                   stockmaster.description,
                   dev_mrpdemandstatus.status,
                   workorders.wo
        FROM mrpdemands
        LEFT JOIN stockmaster on mrpdemands.stockid = stockmaster.stockid
        LEFT JOIN dev_mrpdemandstatus on mrpdemands.statusid = dev_mrpdemandstatus.statusid
        LEFT JOIN workorders on mrpdemands.demandid = workorders.demandid' .
        $where    . ' ORDER BY mrpdemands.stockid, mrpdemands.duedate';      
        $result=DB_query($sql,$db);
 }
      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=5 text-align=left>Item selected is: ".$part."</th></tr><tr>
        <th>" . _('Sl no:') . "</th>
        <th>" . _('Demand no:') . "</th>
        <th>" . _('Date') . "</th>
        <th>" . _('WO status') . "</th>
        <th>" . _('WO qty') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
      while ($myrow=DB_fetch_array($result))   {
        
        if($myrow['wo']!='')        {
            
            $sql3="SELECT qtyreqd
                   FROM woitems
                   WHERE wo=".$myrow['wo'];
            $result3=DB_query($sql3,$db);
            $myrow3=DB_fetch_array($result3);
            $woqty=$myrow3[0];
            
            $sql4="SELECT wostatus.statusid,
                          status.status
                   FROM wostatus,status
                   WHERE wono=".$myrow['wo']."    AND
                         wostatus.statusid=status.statusid";
            $result4=DB_query($sql4,$db);
            $myrow4=DB_fetch_array($result4);
            $wostatus=$myrow4[1];
            
            
        }else   {
        
        $woqty="";
        $wostatus="no work orders created";
        }

        $duedate=ConvertSQLDate($myrow['duedate']);
        
        $datagridid=$myrow['demandid']."/".$myrow['wo'];
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$datagridid.'" onclick=showdetails(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$datagridid.'" onclick=showdetails(this.id)>';
            $k++;
        }  
        
       $slno++; 
        printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow['demandid'],
            $duedate,
            $wostatus,
            $woqty
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
