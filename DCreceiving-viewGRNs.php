<style>
th {
    font-weight: normal;
    font-size: 12px;
    color: #FFFFFF;
    text-align: center;
    background-color: #000000;
}
.EvenTableRows {
    background-color: #E0E0E0;
}
.EvenTableRows:hover{
    background-color: #F0F0F0;
}   

.OddTableRows {
    background-color: #C0C0C0;
}
.OddTableRows:hover{
    background-color:#DAFCDC;
}
</style>
<?php
$PageSecurity = 11; 
include('includes/session.inc');
$item=$_GET['id'];  

$sql="SELECT grns.grnno,
             grns.deliverydate,
             grns.qtyrecd,
             suppliers.suppname,
             stockmoves.stkmoveno
      FROM grns,suppliers,stockmoves
      WHERE grns.itemcode='$item'       AND
            grns.supplierid=suppliers.supplierid    AND
            stockmoves.type=25      AND
            grns.itemcode=stockmoves.stockid        AND
            grns.grnno=stockmoves.transno
             ";
$result=DB_query($sql,$db);

echo'<table width="100%" class="giof">';
echo'<thead>';
echo'<tr width="100%"><td colspan=4>Showing receipts of the item selected  : &nbsp;'.$mino.'</td></tr>';
echo'<th width="10%">slno</th>';
echo'<th>Delivery date</th>';
echo'<th>Quantity Received</th>'; 
echo'<th>Supplier</th>'; 
echo'<th>Print</th>';
echo'</tr>';
echo'</thead>';    

echo'<tbody>';

$slno=0;
while($myrow=DB_fetch_array($result))       {
$slno++; 

$deliverydate=ConvertSQLDate($myrow['deliverydate']);
$issuedate=ConvertSQLDate($myrow['trandate']);  

                        if ($k==1)
        {
            echo '<tr class="EvenTableRows">';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows">';
            $k++;
        }  
            
echo'<td align="center">
         '.$slno.'
      </td>
      <td align="center">
         '.$deliverydate.'
      </td>
      <td align="center">
         '.$myrow['qtyrecd'].'
      </td>
      <td align="center">
      '.$myrow['suppname'].'   
      </td>
      <td align="center">
      <a style="cursor:pointer; color:red;" onclick="printGRN('.$myrow['stkmoveno'].','.$myrow['grnno'].')">Print</a>   
      </td>      

   </tr>';
}
echo'</tbody>';
echo'<tfoot>';
echo'<tr><td colspan=3>No: of records  : &nbsp;'.$slno.'</td>';
echo'</tr>';
echo'</tfoot>';
echo'</table>';    
?>
<script>
function printGRN(str1,str2)        {
    

myRef2 = window.open('PDFSrecn.php?slno='+ str1 + '&grnno=' + str2,'estr2',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');   
}
</script>
