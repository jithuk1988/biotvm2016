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

$sql="SELECT dev_materialissue.mino,
dev_materialissue.date,
dev_materialissue.srno,
womaterialrequest.wono,
woitems.stockid
FROM dev_materialissue,womaterialrequest,woitems
WHERE dev_materialissue.srno=womaterialrequest.reqno    AND
womaterialrequest.wono=woitems.wo   AND
woitems.stockid='$item' 
ORDER BY `dev_materialissue`.`date`  ASC
             ";
$result=DB_query($sql,$db);

echo'<table width="100%" class="giof">';
echo'<thead>';
echo'<tr width="100%"><td colspan=4>Showing issue notes of the item selected  : &nbsp;</td></tr>';
echo'<th width="10%">slno</th>';
echo'<th>Issue date</th>';
echo'<th>Issue No</th>'; 
echo'<th>SR No</th>'; 
echo'<th>Print</th>';
echo'</tr>';
echo'</thead>';    

echo'<tbody>';

$slno=0;
while($myrow=DB_fetch_array($result))       {
$slno++; 


$issuedate=ConvertSQLDate($myrow['date']);  

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
         '.$issuedate.'
      </td>
      <td align="center">
         '.$myrow['mino'].'
      </td>
      <td align="center">
      '.$myrow['srno'].'   
      </td>
      <td align="center">
      <a style="cursor:pointer; color:red;" onclick="printmino('.$myrow['mino'].')">Print</a>   
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
function printmino(str1,str2)        {
    
myRef2 = window.open('WOMaterialissuefromstores-issuedetails-pdf.php?id='+ str1,'estr2',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');   
}
</script>
