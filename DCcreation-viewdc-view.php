<?php
$PageSecurity = 5;

include('includes/session.inc');
$where='';
$sql8="SELECT description 
       FROM stockmaster
       WHERE stockid='".$_GET['p']."'";
$result8=DB_query($sql8,$db);
$myrow8=DB_fetch_array($result8);

if(($_GET['p']!='') AND ($_GET['q']!=''))       {
    
    $where="WHERE itemcode='".$_GET['p']."'     AND
                  pono=".$_GET['q']."   AND
                  dispatchclearance.itemcode=stockmaster.stockid";
                  
}else if(($_GET['p']!='') AND (($_GET['q']='') OR ($_GET['q']='undefined')))       {
    
    $where="WHERE itemcode='".$_GET['p']."'         AND
                  dispatchclearance.itemcode=stockmaster.stockid"; 
       
}elseif ((($_GET['p']='') OR ($_GET['p']='undefined')) AND ($_GET['q']!=''))       {
    
    $where="WHERE pono='".$_GET['q']."'"; 
       
}

$sql3="SELECT * 
       FROM dispatchclearance,stockmaster
       ".$where."";
$result3=DB_query($sql3,$db);

      echo '<table width=100% border=1 class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=6 text-align=left>Item selected is: ".$myrow8[0]."</th></tr><tr>
        <th>" . _('Sl no') . "</th>
        <th>" . _('PO no') . "</th>
        <th>" . _('Item') . "</th>
        <th>" . _('DC Qty') . "</th>
        <th>" . _('Delivery Date') . "</th>
        <th>" . _('Qty Received') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
      
while($myrow3=DB_fetch_array($result3))       {
    
    
        $part=$myrow3['itemcode'];
        $pono= $myrow3['pono'];
        $quantity=$myrow3['quantity'];
        $deliverydate=$myrow3['deliverydate'];
        $dcno=$myrow3['dispatchclrno'];

                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow3['pono'].'" onclick=datagridload("'.$part.'",this.id,"'.$quantity.'","'.$deliverydate.'","'.$dcno.'")>';
            $k=0;
        } else 
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow3['pono'].'" onclick=datagridload("'.$part.'",this.id,"'.$quantity.'","'.$deliverydate.'","'.$dcno.'")>';
            $k++;
        }  
        
       $slno++; 
        printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href='PDFDcn.php?PONo=$pono &DCNo=$dcno' target=_blank>print</a></td>
            </tr>",
            $myrow3['pono'],
            $myrow3['description'],
            $myrow3['quantity'],
            $myrow3['deliverydate'],
            $myrow3['qtyrecd']
            );

    $RowIndex = $RowIndex + 1;    
    
}
       
?>
