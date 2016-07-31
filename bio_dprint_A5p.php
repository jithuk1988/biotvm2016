<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('AdvancePrint');
include('includes/header.inc');
include('includes/sidemenu.php');

echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">PRINT RECEIPT</font></center>'; 
    
echo "<table style='width:95%'><tr><td>";

//----------------------------------------------------- Excel Search --------------------------------------//

echo "<fieldset style='width:80%;'>";
echo "<legend><h3>Export Data</h3></legend>";
echo"<form method='post' action='adv_exel.php'>"; 
echo "<table><tr>";
echo "<td>From Date</td>";
echo '<td><input type="text" id="datefrm" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datefrm" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' value='.date("d/m/Y").'></td>';
echo "<td>&nbsp;&nbsp;</td>";
echo "<td>To Date</td>"; 
echo '<td><input type="text" id="dateto" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="dateto" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' value='.date("d/m/Y").'></td>';
echo "<td>&nbsp;&nbsp;<input type='submit' name='submit' value='Export'</td>";
echo "</tr></table>";
echo"</form>";
echo "</fieldset>";
//---------------------------------------------------- Search -----------------------------------------//

    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>"; 
    
echo"<fieldset style='width:97%;'>";
echo"<legend><h3>Search Customers</h3></legend>";
echo"<table style='border:1px solid #F0F0F0;width:60%'>";
echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>";
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, "'.$_SESSION['DefaultDateFormat'].'")"></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname' id='byname'></td>";
echo"<td><input type='text' name='byplace' id='byplace'></td>";
echo '<td><select name="off" id="off" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_office";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo '</select></td>';
echo '<td><select name="leadsrc" id="leadsrc" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';
$sql1="select * from bio_leadsources";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
}                                                             
echo '</select></td>';
echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
echo"</tr>";
echo"</table>";


echo "<div style='height:350px; overflow:auto;'>";
echo "<table style='border:0px solid #F0F0F0;width:100%'>";
echo "<tr><th style=width:30%>Customer Name</th><th style=width:10%>Contact No</th><th style=width:35%>District</th><th style=width:10%>Category</th><th style=width:35%>Date</th><th style=width:30%>Advance Amount</th></tr>";

 $sql= "SELECT bio_advance.adv_id,
               bio_advance.amount,
               bio_advance.date AS leafdate,
               bio_advance.serialnum,
               bio_advance.bankname,
               bio_advance.paydate AS paydate,
               bio_advance.amount,
               bio_advance.leadid,
               bio_advance.status,
               bio_cashheads.heads,
               bio_cust.cust_id,
               bio_cust.custname AS custname,
               bio_cust.custphone AS custphone,
               bio_cust.custmob AS custmob,
               bio_district.district AS district,
               bio_leads.leadid                            
          FROM bio_advance,
               bio_cust,
               bio_cashheads,
               bio_district,
               bio_leads,
               bio_leadsources,
               bio_office
         WHERE bio_advance.leadid=bio_leads.leadid 
           AND bio_cust.cust_id=bio_leads.cust_id 
           AND bio_district.did=bio_cust.district
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality
           AND bio_cashheads.head_id=bio_advance.head_id
           AND bio_advance.amount!=0
           AND bio_leadsources.id=bio_leads.sourceid  
           AND bio_office.id=bio_leadsources.officeid            
       ";


if(isset($_POST['filterbut']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {    
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql .=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off'];
    //echo $officeid;
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql .= " AND bio_cust.area1 LIKE '%".$_POST['byplace']."%'"; 
    }
    
    if (isset($_POST['off']))    {
    if (($_POST['off']!='')&&($_POST['off']!='0'))
    $sql .=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc'])) {
    if (($_POST['leadsrc']!='ALL') && ($_POST['leadsrc']!=0))
    $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc']."'";
    }
 } 
    $sql.=" ORDER by bio_leads.leadid DESC";   
$result=DB_query($sql,$db);

$k=0;
$i=1;
while($row=DB_fetch_array($result))
{
   
    if($row['custmob']==''){
    $contactNo=$row['custphone'];
    }else{
    $contactNo=$row['custmob']; 
    }   
    
    $date=ConvertSQLDate($row['paydate']);
    $status=$row['status'];
    
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
    
  echo "<td>".$row['custname']."</td><td>".$contactNo."</td><td>".$row['district']."</td><td>".$row['heads']."</td><td>".$date."</td><td>".$row['amount']."</td>"; 
  if($status==0){
  echo "<td><a style=cursor:pointer; id=".$row['adv_id']." onclick=select(this.id)>Orginal</a></td>";   }
  elseif($status==1){
  echo "<td><a style=cursor:pointer; id=".$row['adv_id']." onclick=select(this.id)>Copy</a></td>";   } 
  echo "</tr>"; 
    $i++;
} 
 
echo "</table>";
echo "</form>";
echo "</td></tr>";
echo "</fieldset>"; 
echo"</table>";
//include('includes/footer.inc');  

?>





<script>
function select(str)
{
window.location="bio_print_A5p.php?adv_id=" + str;
}
</script>