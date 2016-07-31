<?php
  $PageSecurity = 11;
  include('includes/session.inc');
$itemcode=$_GET['item'];  

$sql4="SELECT description 
       FROM stockmaster 
       WHERE stockid='".$itemcode."'";
$result4=DB_query($sql4,$db);
$myrow4=DB_fetch_array($result4);

if(isset($_GET['view']))        {
    
if($_GET['view']==1)        {
$where="WHERE womaterialrequest.wono=woitems.wo     AND
              woitems.stockid='".$itemcode."'       AND
              womaterialrequest.statusid=dev_srstatus.srstatusid";    
    
}    
}else       {
$where="WHERE 
              womaterialrequest.wono=woitems.wo     AND
              woitems.stockid='".$itemcode."'       AND
              womaterialrequest.statusid=dev_srstatus.srstatusid";
}

$sql3="SELECT * 
      FROM womaterialrequest,woitems,dev_srstatus
      ".$where."";  
$result3=DB_query($sql3,$db);
                                  ?>
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
if($_GET['id']==1)           {

    echo'<table width="100%">';
    echo'<tr><td colspan=7><h2>Showing  SRs for item :&nbsp;'.$myrow4[0].'</h2></td>';
    echo'<tr><th class="viewheader" width=10%>slno</th>';
    echo'<th class="viewheader">SR no</th>';
    echo'<th class="viewheader">WO no</th>';
    echo'<th class="viewheader">SR quantity</th>';
    echo'<th class="viewheader">date issued</th>';
    echo'<th class="viewheader">SR status</th>';
    echo'<th class="viewheader">Print</th>';
    echo'</tr>';
    $slno=1;
    while($myrow3=DB_fetch_array($result3,$db))     {
        
                            if ($k==1)
        {
            echo '<tr class="EvenTableRows">';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows">';
            $k++;
        } 
        
        
    echo'<td>'.$slno.'</td>';
    echo'<td>'.$myrow3['reqno'].'</td>';
    echo'<td>'.$myrow3['wono'].'</td>';
    echo'<td>'.$myrow3['reqty'].'</td>';
    echo'<td>'.$myrow3['reqdate'].'</td>';
    echo'<td>'.$myrow3['srstatus'].'</td>';
    echo'<td><a onclick="PrintSR('.$myrow3['reqno'].')" style="cursor:pointer;">Print</a></td>';
    echo'</tr>';    
    $slno++;   
    }
    echo'<tr><td><a href="WOStoresrequest-reports-sritem.php?item='.$itemcode.' &view=1 &id=1">view all SRs against this item</a></td></tr>';
    echo'</table>';
    
?>
<script type="text/javascript">
function PrintSR(str)       {
    
myRef=window.open("WOStoresrequest-SR-pdf.php?id=" + str,'estr2',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');
}
</script>
<?php

}else       {
    
$filename="sdfsdfsdf.csv";

    $header= "Slno".","."SR no".","."WO no".","."Quantity".","."SR date".","."Status"."\n";"\n";
    $data='';
    $slno=1;
    
        while($myrow3=DB_fetch_array($result3,$db))     {
    
    $data= $data.$slno.",".$myrow3['reqno'].",".$myrow3['wono'].",".$myrow3['reqty'].",".$myrow3['reqdate'].",".$myrow3['srstatus']."\n";    
    $slno++;    
    } 
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";      
}
?>


