<?php
  
$PageSecurity = 80;  
include('includes/session.inc'); 
//echo $_GET['sd'];
// echo "hii";                                      
if(isset($_GET['feedstock'])){
  //exit;
 $feed=$_GET['feedstock'];
    $weight=$_GET['weight'];
        //echo $feed;
//        echo "aaaaaaa";

if($feed==""){$feed=0;}
if($weight==""){$weight=0;}   
$sql="INSERT INTO bio_feedtemp(bio_feedtemp.feedstockid,bio_feedtemp.weight
) VALUES ('".$feed."','".$weight."')";
  //$result=DB_query($sql, $db);  
//  exit;
             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
//  prnMsg( _('The Feedstock record has been added'),'success');  
   $tempflg=DB_Last_Insert_ID($Conn,'bio_feedtemp','temp_id');        
echo"<input type='hidden' id='hidenfeedstock' value='".$tempflg."'>";

echo"<table  style='width:65%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Feed Stock</td><td>Weight</td></tr>";
$sql="SELECT bio_feedstocks.feedstocks,bio_feedtemp.weight,bio_feedtemp.temp_id 
FROM bio_feedtemp,bio_feedstocks WHERE bio_feedstocks.id=bio_feedtemp.feedstockid";
$result1=DB_query($sql, $db);    
$n=1;
while($myrow=DB_fetch_array($result1)){ //echo$myrow[1];
  echo "<tr style='background:#000080;color:white'>
  <td>$n</td><td>$myrow[0]<input type='hidden' id='hfeedstock' value='$myrow[0]'></td>
  <td>$myrow[1]<input type='hidden' id='hfeedweight' value='$myrow[1]'></td>
  <td><a style='cursor:pointer;color:white;' id='$myrow[2]' onclick='editfeedstok(this.id)'>Edit</a ></td>
  <td><a style='cursor:pointer;color:white' id='$myrow[2]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>";
 $n++; 
  
}echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";    
}
 
?>
