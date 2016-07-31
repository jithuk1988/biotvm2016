<?php

if($_GET['t']==0)       {
$sql="SELECT worequirements.stockid,     
             worequirements.qtypu,
             stockmaster.description
      FROM   worequirements,stockmaster
      WHERE worequirements.wo=".$_REQUEST['WO']." AND
            worequirements.stockid=stockmaster.stockid";
$result=DB_query($sql,$db);
$i=0;
echo '<tr><th>BOM</th>
     <th>Quantity</th></tr>';
while($myrow=DB_fetch_array($result))       {
$Woreqqty=$Woqty*$myrow['qtypu'];   
echo'<tr style="background-color:#ccc;" id='.$myrow['stockid'].'/'.$_REQUEST['WO'].' onclick="selectedbomdetails(this.id)">
<td>'.$myrow['description'].'</td><td>'.$Woreqqty.'</td></tr>'; 

$i++;     
} 
}else if($_GET['t']==1)       {
    

$sql="SELECT womaterialrequestdetails.stockid,     
             womaterialrequestdetails.qtyrequest,
             stockmaster.description
      FROM womaterialrequestdetails,stockmaster
      WHERE reqno=".$_GET['u']."    AND
            womaterialrequestdetails.stockid=stockmaster.stockid";
$result=DB_query($sql,$db);
$i=0;
echo '<tr><th>BOM</th>
     <th>Quantity</th></tr>';
while($myrow=DB_fetch_array($result))       {
  
echo'<tr style="background-color:#ccc;" id='.$myrow['stockid'].'/'.$_REQUEST['WO'].' onclick="selectedbomdetails(this.id)">
<td>'.$myrow['description'].'</td><td>'.$myrow['qtyrequest'].'</td></tr>'; 

$i++;     
} 
}
?>
