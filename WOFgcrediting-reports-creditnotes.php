<style type="text/css">
table.sample {
    border-width: 1px;
    border-style: hidden;

}
table.sample th {
    
    border-width: 1px;
    border-style: solid;


}
table.sample td {
    
    border-width: 1px;
    border-style: solid;


}
</style>

<?php
  $PageSecurity = 5;

 include('includes/session.inc');
 $wono=$_GET["id"];  
 
        $sql='SELECT stockmoves.trandate,
                    stockmoves.qty,
                    stockmoves.transno,
                    woitems.qtyreqd,
                    woitems.qtyrecd,
                    stockmaster.description,
                    stockmaster.longdescription
             FROM   stockmoves,woitems,stockmaster
             WHERE stockmoves.type=26       AND
                   stockmoves.reference='.$wono.'      AND
                   stockmoves.reference=woitems.wo      AND
                   stockmoves.stockid=stockmaster.stockid';
$result=DB_query($sql, $db);

echo '<table width=100% class="sample">';
$tableheader = "<thead><tr>
        <th colspan=6 text-align=left>Item selected is: ".$myrow8[0]."</th></tr><tr>
        <th>" . _('Sl no') . "</th>
        <th>" . _('FGCN no') . "</th>
        <th>" . _('Received Qty') . "</th>
        <th>" . _('Date') . "</th>
        <th>" . _('Batch') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
      
while($myrow=DB_fetch_array($result))       {
    

        $transno= $myrow['transno'];

                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$wono.'" onclick=datagridload(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="EvenTableRows"  id="'.$wono.'" onclick=datagridload(this.id)>';
            $k++;
        }  
        
       $slno++; 
        printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href='WOFgcrediting-FGcredit.php?id=$transno' target=_blank>print</a></td>
            </tr>",
            $myrow['transno'],
            $myrow['qty'],
            $myrow['trandate']
            );

    $RowIndex = $RowIndex + 1;    
    
}
?>
