<?php
  $PageSecurity = 80;

include('includes/session.inc');
$filename1="Product register";
$filename ='$filename1'.'.csv';

if(isset($_POST['submit']))
{
    $header= "Stockid".","."Description".","."Size".","."Design".","."Diameter".","."Height".","."Capacity".","."Price"."\n";
    
    $sql1="select stockmaster.stockid,stockmaster.description from stockmaster ";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
        $stk=$row1['stockid'];
        $des=$row1['description'];
        
        $sql2="select stockitemproperties.value
               from stockitemproperties,stockmaster
               where stockitemproperties.stockid='$stk'
               and stockitemproperties.stockid=stockmaster.stockid";
        $result2=DB_query($sql2,$db);
        $data=$data.$stk.",".$des.",";
        
        while($row2=DB_fetch_array($result2))
        {
            $value=$row2['value'];
            $value=str_replace(',','',$value);
            $data=$data.$value.",";
        }
        $data=$data."\n";
    }
    
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";          
}

?>
