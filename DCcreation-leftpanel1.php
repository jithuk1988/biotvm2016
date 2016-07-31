<?php
if($flag==1)        {
echo'<tr><td width="50%">DC no:</td><td><input type="hidden" name="Dcno" id="dcno" value="'.$dcno.'">'.$dcno.'</td></tr>';    
echo'<tr><td width="50%">PO no:</td><td><input type="hidden" name="Pono" id="pono" value="'.$pono.'" size="25">'.$pono.'</td></tr>';  
echo'<tr><td width="50%">Item&nbsp;</td><td><input type="hidden" name="Item" id="item_sel" value="'.$item.'" size="25">'.$itemname.'</td></tr>';   
}else{

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
}
echo'<tr><td width="50%">Delivery date</td><td><input type="text" name="delivery" id="dcdelivery" value="'.$delivery.'" size="25"></td></tr>'; 
if(($_GET['p']!='') AND (($flag!=1)))      { 
echo'<tr><td width="50%">Max dispatch qty</td><td><input type="hidden" name="Maxqty" id="maxqty" value="'.$maxdispatchqty.'">'.$maxdispatchqty.'</td></tr>';  
}

echo'<tr><td width="50%">Quantity</td><td><input type="text" name="Qty" id="dcqty" value="'.$dcqty.'" size="25"></td></tr>'; 

if($flag==1)        {

$sql9="SELECT dcstatusid,     
              dcstatus
       FROM dev_dcstatus";
$result9=DB_query($sql9,$db);       
echo '<tr><td width=50%>Status&nbsp;</td><td><select name="DCstatus" id="dc_status" style="width:180px;">';
echo'<option value=""></option>';
while($myrow9=DB_fetch_array($result9))       {
if($myrow9['dcstatusid']==$dcstatus)       {
    
    echo'<option selected value='.$myrow9[0].'>'.$myrow9[1].'</option>'; 
    
}else       {
echo'<option value='.$myrow9[0].'>'.$myrow9[1].'</option>';  
}  
}
echo'</td></tr>';    
    
}

?>
