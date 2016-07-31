<?php
    $PageSecurity = 80;
 include ('includes/session.inc');
$title = _('Leadsource Report');  
include('includes/header.inc'); 
   if($_POST['submit'])
  {
     $create=$_POST['caldate'];
     $createdate=FormatDateForSQL($create);
  
  }    else
  {
      $createdate=date("Y-m-d"); 
  }
   echo '<form id="form1" name="form1" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
 echo '<fieldset><legend>Date searching</legend>
 <table  align="center"  width="70%">
<tr><td><b>Enter date</b></td><td>';
if($_POST['submit'])
{
    echo '<input  style="width:170px" type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.$_POST['caldate'].'>'; 
}     else
{
 echo '<input  style="width:170px" type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.date("d/m/Y").'>'; }
 echo'</td><td><input type="submit" name="submit"/></td> </table> </fieldset>';
 ?>
 <table width="94%" border="1">
  <tr>
    <th rowspan="2" scope="col">slno</th>
    <th rowspan="2" scope="col">Plant Capacity</th>
    <th colspan="6" scope="col">Plant booked</th>
     <th colspan="6" scope="col">Plant supplied  </th>
    <th colspan="6" scope="col">Pending supply</th>
    <th colspan="6" scope="col">Plant stock</th>
  </tr>
  <tr bgcolor="#9D8787" style="font-weight: bold;">
  <?php
  for($a=0;$a<4;$a++)
  {?>
   <td width="6%">TVM</td>
      <td width="6%">EKM</td>
    <td width="6%">KOK</td>
        <td width="6%">NAT</td>
    <td width="6%">INTL</td>
    <td width="6%">TOTAL</td>
  <?php } ?> 
  </tr><tr bgcolor="#CC99CC">
<?php
 $a=array("0.5","0.75","1","1.5","2","3","4","6");  
 $i=0;
 for($j=0;$j<9;$j++)   
 {
    echo "<tr><td>$i</td>"; 
    echo "<td>$a[$i]</td></tr>";
    
   // echo "</tr>";
    $i++;
 }
?>
