<?php
$PageSecurity = 11; 
include('includes/session.inc');  

$StockID=$_GET['p'];
$Wono=$_GET['q'];
$Srno=$_GET['r'];

$where="WHERE womaterialrequest.statusid!=1        AND
              womaterialrequest.statusid=dev_srstatus.srstatusid";
if(($_GET['p']!='') AND ($_GET['q']!='') AND ($_GET['r']!=''))      {

$where="WHERE womaterialrequest.reqno=".$Srno."   AND
              womaterialrequest.statusid!=1        AND
              womaterialrequest.statusid=dev_srstatus.srstatusid  AND  
              womaterialrequest.wono=".$Wono."    AND
              womaterialrequest.wono=woitems.wo   AND
              woitems.stockid='".$StockID."'";
              
}elseif(($_GET['p']!='') AND ($_GET['q']!=''))      {
$where="WHERE womaterialrequest.wono=".$Wono."    AND
              womaterialrequest.statusid!=1        AND
              womaterialrequest.statusid=dev_srstatus.srstatusid  AND 
              womaterialrequest.wono=woitems.wo   AND
              woitems.stockid='".$StockID."'";
}elseif(($_GET['q']!='') AND ($_GET['r']!=''))      {   
$where="WHERE womaterialrequest.reqno=".$Srno."   AND
              womaterialrequest.statusid!=1        AND
              womaterialrequest.statusid=dev_srstatus.srstatusid  AND 
              womaterialrequest.wono=".$Wono."";
}elseif(($_GET['p']!='') AND ($_GET['r']!=''))      {   
$where="WHERE womaterialrequest.reqno=".$Srno."   AND
              womaterialrequest.statusid!=1        AND
              womaterialrequest.statusid=dev_srstatus.srstatusid  AND 
              womaterialrequest.wono=woitems.wo   AND
              woitems.stockid='".$StockID."'";
}elseif(($_GET['p']!=''))      {   
$where="WHERE womaterialrequest.wono=woitems.wo   AND
              womaterialrequest.statusid!=1        AND
              womaterialrequest.statusid=dev_srstatus.srstatusid  AND 
              woitems.stockid='".$StockID."'";
}elseif(($_GET['p']='') AND ($_GET['q']='') AND ($_GET['r']=''))      {  

$where="WHERE womaterialrequest.reqno=womaterialrequestdetails.reqno AND
              womaterialrequest.statusid!=1        AND
              womaterialrequest.statusid=dev_srstatus.statusid  AND ";
}

echo$sql="SELECT * 
      FROM  womaterialrequest,woitems,dev_srstatus
      ".$where."";
$result=DB_query($sql,$db);

include('WOMaterialissuefromstores-datagrid.php');
?>
