<?php
if(!isset($_GET['p']))      {
echo '<tr><td>' . _('Item') . "*</td><td><select name='StockID' id='wosr-item' onchange='viewsr(this.value,2,3)'>";

            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description
                                        
                FROM stockmaster
                WHERE stockmaster.mbflag='M' AND stockmaster.categoryid !=13
                ORDER BY stockmaster.stockid";
            $result = DB_query($sql,$db); 
$f=0;            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['stockid']==$_SESSION['StockID']) {
                echo "<option selected value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
          
        
        $f++;    
        } //end while loop
            
        echo '</select>'; 
echo"</td></tr>";

echo '<tr style="background-color:lightgrey;"><td width=50%>Enter WO no:&nbsp;</td><td><input type="text" class="number" name="Wono" size=25 maxlength=40 id="wono"></td></tr>';

}else       {
    
$PageSecurity = 11; 
include('includes/session.inc');
                                                                                                           

$wosrflag=$_GET['q'];

if($wosrflag==2)        {
$_POST['Wono']=$_GET['p']; 
$_REQUEST['WO']=$_POST['Wono'];    
$sqlr="SELECT woitems.stockid,
              woitems.qtyreqd,
              woitems.qtyrecd,
              stockmaster.description,
              workorders.requiredby
      FROM woitems,stockmaster,workorders
      WHERE woitems.wo=".$_POST['Wono']." AND
            workorders.wo=woitems.wo    AND
            woitems.stockid=stockmaster.stockid";
$resultr=DB_query($sqlr,$db);
$myrowr=DB_fetch_array($resultr);
$sqls="SELECT sum(reqty)
       FROM womaterialrequest
       WHERE womaterialrequest.wono=".$_POST['Wono'];
$results=DB_query($sqls,$db);
$myrows=DB_fetch_array($results);
$sum=$myrows[0];
if($myrowr[0]!='')       {
$_REQUEST['StockID']=$myrowr[0];
}
$_POST['Reqdate']=ConvertSQLDate($myrowr['requiredby']);


echo '<tr style="background-color:lightgrey;"><td width=50%>WO no:&nbsp;</td>

<td><input type="hidden" class="number" name="Wono" id="wono" size=25 maxlength=40 value="'.$_POST['Wono'].'">'.$_POST['Wono'].'</td></tr>'; 

echo '<input type="hidden" name="Editcheck" id=editcheck value="0">'; 
//echo '<tr><td>' . _('Wo no:') . "</td><td><input type='hidden' id='wono' name='Wono' value='".$_POST['Wono']."'>".$_POST['Wono']."</td></tr>";

echo'<input type=hidden  id="fg" name="FG" value="'.$_REQUEST['StockID'].'">';
echo'<input type=hidden  id="wosr-item"  value="'.$_REQUEST['StockID'].'">';
echo '<tr><td>' . _('Item') . "</td><td>".$myrowr['description']."</td></tr>";   
echo '<tr><td>' . _('Wo Quantity') . "</td><td>".$myrowr['qtyreqd']."</td></tr>";
echo '<tr><td>' . _('Quantity already received') . "</td><td>".$myrowr['qtyrecd']."</td></tr>";
echo '<tr><td>' . _('SR already generated for') . "</td><td>".$sum."</td></tr>"; 
echo'<input type=hidden  id="woqty" name="Woqty" value="'.$myrowr['qtyreqd'].'">';  
echo"<td>Reqd Date</td><td><input type='Text' name='Reqdate' value='".$_POST['Reqdate']."' id='reqdate' size=25
            maxlength=40 ></td>";
echo '<tr><td>' . _('SR Quantity') . "*</td><td><input type='text' id='srqty' name='Srqty' value='".$_POST['Srqty']."' size=25 maxlength=40></td></tr>"; 
echo '<tr><td>' . _('SR type') . "*</td><td><select name='Srtype' onchange='viewsr(this.value,2,3)'>";

            $sql = "SELECT dev_srtype.srtypeid,
                           dev_srtype.srtype
                                        
                FROM dev_srtype
                ORDER BY dev_srtype.srtypeid";
            $result = DB_query($sql,$db); 
$f=0;            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['srtypeid']==$_POST['Srtype']) {
                echo "<option selected value='" . $myrow['srtypeid'] . "'>" . $myrow['srtype']; 
            } 
 echo "<option value='" . $myrow['srtypeid'] . "'>" . $myrow['srtype']; 
          
        
        $f++;    
        } //end while loop
            
        echo '</select>'; 
echo"</td></tr>";



}
if($wosrflag==1)        {
    
   
$_POST['Reqno']=$_GET['p']; 

    
    
$sqlr="SELECT womaterialrequest.wono,    
              womaterialrequest.reqty,     
              womaterialrequest.reqdate,
              womaterialrequest.statusid,
              womaterialrequest.srtype,
              woitems.stockid,
              woitems.qtyreqd,
              woitems.qtyrecd,
              stockmaster.description,
              workorders.requiredby
      FROM womaterialrequest,woitems,stockmaster,workorders
      WHERE reqno=".$_POST['Reqno']."       AND
            womaterialrequest.wono=woitems.wo   AND
            woitems.wo=workorders.wo    AND
            woitems.stockid=stockmaster.stockid"; 
$resultr=DB_query($sqlr,$db);
$myrowr=DB_fetch_array($resultr);

$_POST['Wono']=$myrowr['wono'];    
$_REQUEST['WO']=$_POST['Wono'];

$sqls="SELECT sum(reqty)
       FROM womaterialrequest
       WHERE womaterialrequest.wono=".$_POST['Wono'];
$results=DB_query($sqls,$db);
$myrows=DB_fetch_array($results);
$sum=$myrows[0];
if($myrowr[0]!='')       {
$_REQUEST['StockID']=$myrowr[5];
}
$_POST['Reqdate']=ConvertSQLDate($myrowr['reqdate']);
echo '<tr style="background-color:lightgrey;"><td width=50%>WO no:&nbsp;</td><td><input type="hidden" name="Wono" id="wono" value="'.$_POST['Wono'].'">'.$_POST['Wono'].'</td></tr>'; 
echo '<tr id="reqid"><td width=50%>SR no:&nbsp;</td>
<td><input type="hidden" name="Reqno" id="reqno" value="'.$_POST['Reqno'].'">'.$_POST['Reqno'].'';
//echo'<a style="cursor:pointer;" id="'.$_POST['Reqno'].'" onclick="changeid(this.id)">&nbsp;&nbsp;</a>';
echo'</td>';
echo'</tr>';

echo '<input type="hidden" name="Editcheck" id=editcheck value="1">'; 

echo '<input type="hidden" name="Srtype" value='.$myrowr['srtype'].'>';


//echo '<tr><td>' . _('Wo no:') . "</td><td><input type='hidden' id='wono' name='Wono' value='".$_POST['Wono']."'>".$_POST['Wono']."</td></tr>";
echo'<input type=hidden  id="fg" name="FG" value="'.$_REQUEST['StockID'].'">';
echo'<input type=hidden  id="wosr-item"  value="'.$_REQUEST['StockID'].'">';

echo '<tr><td>' . _('Item') . "</td><td>".$myrowr['description']."</td></tr>";   
echo '<tr><td>' . _('Wo Quantity') . "</td><td>".$myrowr['qtyreqd']."</td></tr>";
echo '<tr><td>' . _('Quantity already received') . "</td><td>".$myrowr['qtyrecd']."</td></tr>"; 
echo '<tr><td>' . _('SR already generated for') . "</td><td>".$sum."</td></tr>";
echo'<input type=hidden  id="woqty" name="Woqty" value="'.$myrowr['qtyreqd'].'">';  
echo"<td>Reqd Date</td><td><input type='Text' class=date alt=".$_SESSION['DefaultDateFormat']." name='Reqdate' value='".$_POST['Reqdate']."' id='reqdate' size=25
            maxlength=40 ></td>";
echo '<tr><td>' . _('SR Quantity') . "*</td><td><input type='text' id='srqty' name='Srqty' value='".$myrowr['reqty']."' size=25 maxlength=40></td></tr>";    

$sql4="SELECT srstatusid,
              srstatus
       FROM dev_srstatus"; 
$result4=DB_query($sql4,$db);  
echo'<tr><td>Status</td><td><select name=Srstatusid>';
while($myrow4=DB_fetch_array($result4))         {
if($myrow4['srstatusid']==$myrowr['statusid'])     {
    
echo '<option selected value='.$myrow4['srtatusid'].'>'.$myrow4['srstatus'].'';     
}else{
echo '<option value='.$myrow4['srstatusid'].'>'.$myrow4['srstatus'].'';
}
    
}
echo'</select></td></tr>';
 


} 
}
?>
