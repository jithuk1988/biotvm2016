<?php
if(!isset($_GET['p']))      {
echo '<tr style="background-color:lightgrey;"><td width=50%>Enter WO no:&nbsp;</td><td><input type="text" class="number" name="WO" size=25 maxlength=40 id="wo"></td></tr>';

}else       {
    
$PageSecurity = 11; 
include('includes/session.inc');
                                                                                                           
$_POST['WO']=$_GET['p']; 
$_REQUEST['WO']=$_POST['WO'];

$sqlr="SELECT woitems.stockid,
              woitems.qtyreqd,
              woitems.qtyrecd,
              stockmaster.description,
              woserialnos.serialno
      FROM woitems,stockmaster,woserialnos
      WHERE woitems.wo=".$_POST['WO']." AND
            woitems.stockid=stockmaster.stockid     AND
            woitems.wo=woserialnos.wo";
$resultr=DB_query($sqlr,$db);

            if (DB_num_rows($resultr)!=0){ 
$_REQUEST['StockID']=$myrowr[0];
$_POST['BatchRef']=$myrowr['serialno'];
}

echo '<tr style="background-color:lightgrey;"><td width=50%>Enter WO no:&nbsp;</td><td><input type="text" class="number" name="WO" id="wo" size=25 maxlength=40 value="'.$_POST['WO'].'"></td></tr>'; 

//echo '<tr><td>' . _('Wo no:') . "</td><td><input type='hidden' id='wono' name='Wono' value='".$_POST['Wono']."'>".$_POST['Wono']."</td></tr>";
echo'<input type=hidden  name="StockID" value="'.$_REQUEST['StockID'].'">';     
 

                  $i=0; //the Batch counter
                while ($WOSNRow = DB_fetch_row($resultr)){
                    if (($i/5 -intval($i/5))==0){
                        echo '</tr><tr>';
                    }
                    
                    echo'<input type=hidden  name="StockID" value="'.$WOSNRow[0].'">';  
                    echo '<tr><td>' . _('Item') . "</td><td>".$WOSNRow[3]."</td></tr>"; 
                    echo '<tr><td>' . _('Wo Quantity') . "</td><td>".$WOSNRow[1]."</td></tr>"; 
                    echo '<tr><td>' . _('Quantity received') . "</td><td>".$WOSNRow[2]."</td></tr>";
                    $maxqty=$WOSNRow[1]-$WOSNRow[2];
                    echo'<input type="hidden" id="maxqty" name="maxqty" value='.$maxqty.'>';
//echo"<td>Reqd Date</td><td><input type='Text' class=date alt=".$_SESSION['DefaultDateFormat']." name='Reqdate' value='".$_POST['Reqdate']."' id='reqdate' size=15
//            maxlength=40 ></td>";

echo '<tr><td width=50%>Receive into&nbsp;</td><td><select name="IntoLocation" id="item_sel" style="width:180px;" onchange="showdetails(this.value)">';
if (!isset($_POST['IntoLocation'])){
        $_POST['IntoLocation']=$WORow['loccode'];
}
$LocResult = DB_query('SELECT loccode, locationname FROM locations',$db);
while ($LocRow = DB_fetch_array($LocResult)){
    if ($_POST['IntoLocation'] ==$LocRow['loccode']){
        echo '<option selected value="' . $LocRow['loccode'] .'">' . $LocRow['locationname'];
    } else {
        echo '<option value="' . $LocRow['loccode'] .'">' . $LocRow['locationname'];
    }
}
echo'</select></td></tr>';  
                                  
                    echo '<tr><td>Batch no:</td><td><input type="text" name="BatchRef' . $i . '" value="' . $WOSNRow[4] . '" size=25></td></tr>
                          <tr><td>Receive Quantity</td><td><input type="text" class="number" name="Qty' . $i . '"  size=25>
                                  <input type="hidden" name="QualityText' . $i . '" value="' . $WOSNRow[2] . '">
                                  <input type="hidden" name="QtyReqd' . $i . '" value="' . $WOSNRow[1] . '"></td></tr>';
                    $i++;
                }
echo '<input type="hidden" name="CountOfInputs" value=' . $i . '>';
 
}
?>
