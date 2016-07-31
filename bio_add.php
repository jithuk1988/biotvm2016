<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
$fd=$_GET['feedstok'];
$wt=$_GET['weight']; if($wt==""){$wt=0;} 
$ld=$_GET['lead']; $leadid=$ld; 
$sql="SELECT COUNT(*)
FROM bio_leadfeedstocks WHERE feedstockid=$fd
AND leadid=$ld";

             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);  
$myrow1=DB_fetch_array($result);echo$myrow1[0];
if($myrow1[0]!=0){

echo' <script>';
echo'message()';
echo'</script>';
 }
else{$st=0;
$sql="INSERT INTO bio_leadfeedstocks VALUES($ld,$fd,$wt,$st)";

             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);  
   }
           
echo"<div id='shadd'>";
     echo "<table style='align:left'  border=0  >";
  echo "<tr><td>Feed Stock</td>";
//Feedstock
    echo '<td>';

 $sql1="SELECT * FROM bio_feedstocks";
  $result1=DB_query($sql1, $db);
  echo '<select name="feedstockad" id="feedstockad" style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['feedstock']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
  echo "</td>";
  echo "<td>Weight in Kg</td>";
  echo "<td><input type=text name='weightad' id='weightad' style='width:83%'></td>";
  echo "<td>";
 echo '<input type="button" name="edaddfeedstock" id="edaddfeedstock" value="Add" onclick="showCD9()">';
//  echo '<input type="button" name="addfeedstock" id="addfeedstock" value=Add>';
  echo "</td>";
  
  echo "</tr>";
  
  

echo "</table>"; 
//----------------------------
     
     
     

  echo"<table id='editact' style='width:65%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Feed Stock</td><td>Weight</td><td>Update</td></tr>";
      
$sql="SELECT bio_feedstocks.feedstocks,
             bio_leadfeedstocks.weight,bio_leadfeedstocks.feedstockid
      FROM bio_leadfeedstocks,bio_feedstocks 
      WHERE bio_leadfeedstocks.leadid=$leadid AND
            bio_leadfeedstocks.feedstockid=bio_feedstocks.id";
$result1=DB_query($sql, $db);    
$n=1;
while($myrow=DB_fetch_array($result1)){
  echo "<tr style='background:#000080;color:white' >
  <td>$n</td>
  <td>$myrow[0]</td>
  <td>$myrow[1]</td>
  <td><a  style='color:white;cursor:pointer' name='".$leadid."' id='$myrow[2]' onclick='feedupdte1(this.id,this.name)'>Edit</a></td>
  </tr>";
 $n++;
}   echo "<tr id='edittedsho'></tr>";         
 echo '<tr><td colspan="4"><center><input type="submit" name="edit" id="editleads" value="Update"  onclick="return log_in()"></center></td></tr></table>';      
echo"</table>";
echo"</div>";           
           
           
           
           
?>
