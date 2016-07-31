<?php
$PageSecurity = 5;

include('includes/session.inc');

$sql="SELECT description
      FROM stockmaster
      WHERE stockid='$part'";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$partname=$myrow[0];
$where='';
if(($_GET['p']!='') AND ($_GET['q']!=''))       {
    
    $where="WHERE dispatchclearance.itemcode='".$_GET['p']."'     AND
                  dispatchclearance.pono=".$_GET['q']."     AND
                  dispatchclearance.itemcode=stockmaster.stockid";
                  
}else if(($_GET['p']!='') AND (($_GET['q']='') OR ($_GET['q']='undefined')))       {
    
    $where="WHERE dispatchclearance.itemcode='".$_GET['p']."'     AND
                  dispatchclearance.itemcode=stockmaster.stockid"; 
       
}elseif ((($_GET['p']='') OR ($_GET['p']='undefined')) AND ($_GET['q']!=''))       {
    
    $where="WHERE dispatchclearance.pono='".$_GET['q']."'     AND
                  dispatchclearance.itemcode=stockmaster.stockid"; 
       
}

$sql3="SELECT * FROM dispatchclearance,stockmaster
       ".$where."";
$result3=DB_query($sql3,$db);

      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=6 text-align=left>Item selected is: ".$partname."</th></tr><tr>
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
