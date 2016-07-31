<?php
$PageSecurity = 11; 
include('includes/session.inc');
$title = _('Materials issue');

//include('includes/header.inc');
$Srno=$_GET['srno'];
$sql="SELECT description 
      FROM stockmaster
      WHERE stockid='".$_GET['id']."'";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$itemname=$myrow[0];

echo'<form action="WOMaterialissuefromstores-picklist.php" method="post">';
echo'<table>';
echo'<tr>';
echo'<td colspan=3>Item selected   :&nbsp;';
echo'<input type="hidden" name="StockID" id="stockid" value="'.$_GET['id'].'">'.$itemname.'</td>';
echo'</tr>';
echo"<tr><td>Issue date:</td><td><input type='Text' name='IssuedDate' value='".$_POST['Duedate']."' id='popupdate' size=25
            maxlength=12 ></td></tr>";
$sql="SELECT quantity,
             serialno
      FROM stockserialitems
      WHERE stockid='".$_GET['id']."'
      ORDER BY ABS(serialno) 
      ASC";
$result=DB_query($sql,$db);


echo'<tr bgcolor="#D0D0D0">';
echo'<td width="10%">Slno</td>';
echo'<td>Quantity</td>';
echo'<td>Batch</td>';
echo'</tr>';
$quantity=0;
$slno=0;
$i=0;
while($myrow=DB_fetch_array($result))       {
$slno++;
echo'<tr bgcolor="#F0F0F0">';  
if($quantity>=$_GET['qty'])     {
    
    break;
}
echo'<td>'.$slno.'</td>';
echo'<td><input type="text" name="Batchqty'.$i.'" value='.$myrow['quantity'].'></td>';
echo'<td><input type="text" name="Batchno'.$i.'" value='.$myrow['serialno'].'></td>';
echo'</tr>';  
$i++;
$quantity+=$myrow['quantity'];    
}
echo'<tr>';
echo'<td>Total</td>';
echo'<td>'.$quantity.'</td>';
echo'<input type="hidden" name="Batchcount" id="batchcount_mi" value="'.$i.'">';
echo'<input type="hidden" name="Srno" id="srno_popup_mi" value="'.$Srno.'">';
echo'<input type="hidden" name="Srqty" id="srqty_popup_mi" value="'.$_GET['qty'].'">';
echo'<td text-align="right"><input type="submit" name="batchessub" value="Submit"></td>';
echo'</tr>';



echo'</table>';
echo"</form>";

?>
<script>
calenderr("popupdate");

function calenderr(str){
        new JsDatePick({
            useMode:2,
            target:str,
            dateFormat:"%d/%m/%Y"
            /*selectedDate:{                This is an example of what the full configuration offers.
                day:5,                        For full documentation about these settings please see the full version of the code.
                month:9,
                year:2006
            },
            yearsRange:[1978,2020],
            limitToToday:false,
            cellColorScheme:"beige",
            dateFormat:"%m-%d-%Y",
            imgPath:"img/",
            weekStartDay:1*/
        });
    };

</script>