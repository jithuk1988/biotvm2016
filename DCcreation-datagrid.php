<?php
  $PageSecurity = 11;

// include('includes/session.inc');
 $_POST['StockID ']=$_GET['p'];  
 if($_GET['p']!='')     {
     
  $_SESSION['StockID']=$_GET['p'];  
  $_SESSION['SeasonID']=$_GET['q'];   

 
    $part=$_SESSION['StockID'];
    $SeasonID=$_SESSION['SeasonID'];  
    $where = "";
    if ($part) {

    
    // If part is entered, it overrides demandtype
    $sql="SELECT description 
          FROM stockmaster
          WHERE stockid='$part'";
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_array($result);
    $partname=$myrow[0];

   $sql="SELECT purchorderdetails.orderno,
                 purchorderdetails.quantityord,
                  purchorderdetails.quantityrecd,
                  purchorders.supplierno,
                  suppliers.suppname
           FROM purchorderdetails,purchorders,suppliers
           WHERE purchorderdetails.itemcode='$part' AND
                 purchorderdetails.orderno=purchorders.orderno  AND
                 purchorders.supplierno= suppliers.supplierid
           ORDER BY purchorders.supplierno";
    
        $result=DB_query($sql,$db);
 }
 
 }
      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=6 text-align=left>Item selected is: ".$partname."</th></tr><tr>
        <th>" . _('Sl no') . "</th>
        <th>" . _('PO no') . "</th>
        <th>" . _('Supplier') . "</th>
        <th>" . _('Order Qty') . "</th>
        <th>" . _('Qty Received') . "</th>
        <th>" . _('Qty pending dispatch') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
      while ($myrow=DB_fetch_array($result))   {
        
        $sql2="SELECT SUM(quantity)
               FROM dispatchclearance
               WHERE pono=".$myrow['orderno']." AND
               itemcode='$part'";
        $result2=DB_query($sql2,$db);
        $myrow2=DB_fetch_row($result2);
        

                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['orderno'].'" onclick=datagridload("'.$part.'",this.id,"'.$myrow['quantityord'].'","'.$myrow2[0].'")>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['orderno'].'" onclick=datagridload("'.$part.'",this.id,"'.$myrow['quantityord'].'","'.$myrow2[0].'")>';
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
            $myrow['orderno'],
            $myrow['suppname'],
            $myrow['quantityord'],
            $myrow['quantityrecd'],
            $myrow2[0]
            
            );
            $myrow2[0]=0;

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
