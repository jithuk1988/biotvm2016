<?php
       $sql = "SELECT stockmaster.stockid,
                    stockmaster.description,
                    stockmaster.units
                FROM stockmaster INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                WHERE stockmaster.mbflag!='D'
                AND stockmaster.mbflag!='A'
                AND stockmaster.mbflag!='K'
                AND stockmaster.mbflag!='M'
                and stockmaster.discontinued!=1
                ORDER BY stockmaster.stockid";
       $result=DB_query($sql,$db);
echo '<tr><td width=50%>Select Item&nbsp;</td><td><select name="Item" id="item_sel" style="width:180px;" onchange="showdetails(this.value)">';
echo'<option value=""></option>';
while($myrow=DB_fetch_array($result))       {
if($myrow['stockid']==$item)       {
    
    echo'<option selected value='.$myrow[0].'>'.$myrow[1].'</option>'; 
    
}else       {
echo'<option value='.$myrow[0].'>'.$myrow[1].'</option>';  
}  
}
echo'</td></tr>';
echo'<tr><td width="50%">PO no:</td><td><input type="text" name="Pono" id="pono" value="'.$pono.'" size="25"></td></tr>';
echo'<tr><td width="50%">Delivery date</td><td><input type="text" name="delivery" class=date alt="'.$_SESSION['DefaultDateFormat'].'" id="dcdelivery" value="" size="25"></td></tr>'; 
echo $_GET['u'];
if(($_GET['p']!='') AND (($_GET['u']!=1)))      { 
echo'<tr><td width="50%">Max dispatch qty</td><td><input type="hidden" name="Maxqty" id="maxqty" value="'.$maxdispatchqty.'">'.$maxdispatchqty.'</td></tr>';  
}
echo'<tr><td width="50%">Quantity</td><td><input type="text" name="Qty" id="dcqty" value="'.$dcno.'" size="25">'.$dcno.'</td></tr>';   
?>
