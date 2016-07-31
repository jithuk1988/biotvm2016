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
$mino=$_GET['id'];  

$sql="SELECT dev_materialissuedetails.stockid,     
             dev_materialissuedetails.quantity,
             stockmoves.trandate,
             stockmaster.description,
             locations.locationname 
      FROM dev_materialissuedetails,stockmoves,stockmaster,locations
      WHERE dev_materialissuedetails.mino=$mino      AND
            dev_materialissuedetails.stockmoveno=stockmoves.stkmoveno   AND
            dev_materialissuedetails.stockid=stockmaster.stockid        AND
            stockmoves.loccode=locations.loccode";
$result=DB_query($sql,$db);

echo'<table width="100%" class="giof">';
echo'<thead>';
echo'<tr width="100%"><td colspan=4>Material issue note  : &nbsp;'.$mino.'</td></tr>';
echo'<th width="10%">slno</th>';
echo'<th>Item</th>';
echo'<th>Quantity Issued</th>'; 
echo'<th>Issued Date</th>'; 
echo'<th>Issued From</th>';
echo'</tr>';
echo'</thead>';    

echo'<tbody>';

$slno=0;
while($myrow=DB_fetch_array($result))       {
$slno++; 

$issuedate=ConvertSQLDate($myrow['trandate']);  

                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['mino'].'" onclick=datagridload_rpanel(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['mino'].'" onclick=datagridload_rpanel(this.id)>';
            $k++;
        }  
        
echo'<td align="center">
         '.$slno.'
      </td>
      <td align="center">
         '.$myrow['description'].'
      </td>
      <td align="center">
         '.$myrow['quantity'].'
      </td>
      <td align="center">
      '.$issuedate.'   
      </td>
      <td align="center">
      '.$myrow['locationname'].'   
      </td>      

   </tr>';
}
echo'</tbody>';
echo'<tfoot>';
echo'<tr><td colspan=3>No: of records  : &nbsp;'.$slno.'</td>';
echo'<td><input type="button" name="excel" id="mi_excel" value="Excel"></td>';
echo'<td><input type="button" name="pdf" id="mi_pdf" onclick="mipdf('.$mino.')" value="PDF"></td>';
echo'</tr>';
echo'</tfoot>';
echo'</table>';    
?>
<script>
function mipdf(str1)        {
myRef2 = window.open('WOMaterialissuefromstores-issuedetails-pdf.php?id='+ str1,'estr1',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');   
}
</script>
