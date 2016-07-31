<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('directPrint');
include('includes/header.inc');

echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">ADVANCE RECIEPT</font></center>'; 
    
echo "<table style='width:60%'><tr><td>";
echo "<fieldset style='width:97%;'>";     
echo "<legend><h3>Domestic Customers</h3>";
echo "</legend>";


echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:0px solid #F0F0F0;width:100%'>";
echo "<tr><th style=width:10%>Sl.No</th><th style=width:10%>Leadid</th><th style=width:30%>Customer Name</th><th style=width:35%>Place</th><th style=width:30%>Advance Amount</th></tr>";


$sql1="select bio_cust.cust_id,
bio_cust.custname,bio_cust.area1,
bio_leads.advanceamount,
bio_leads.leadid 
from 
bio_leads,
bio_cust 
       where bio_cust.cust_id=bio_leads.cust_id and bio_leads.advanceamount!=0 and bio_leads.enqtypeid=1";
$result1=DB_query($sql1,$db);

$k=0;
$i=1;
while($row1=DB_fetch_array($result1))
{
        if ($k==1)
    {
        echo '<tr class="EvenTableRows">';
        $k=0;          
    }
    else 
    {
        echo '<tr class="OddTableRows">';
        $k=1;     
    }
    
    echo "<td>$i</td><td>".$row1['leadid']."</td><td>".$row1['custname']."</td><td>".$row1['area1']."</td><td>".$row1['advanceamount']."</td>"; 
    echo "<td><a href='#' id=".$row1['leadid']." onclick=select(this.id)>Print</a></td>";
    echo "</tr>"; 
    $i++;
}  
echo "</table>";
echo "</td></tr></table>";
?>





<script>
function select(str)

{
window.location="bio_print_A5p.php?leadid=" + str;
}
</script>