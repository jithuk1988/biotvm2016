<?php
  $PageSecurity = 80; 
include('includes/session.inc'); 
$title = _('Plant Delivery date');
include('includes/header.inc');  


echo"<br />";
    
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
   
   
echo"<fieldset style='width:900px;'; overflow:auto;'>";    
echo"<legend>Plant Order Date</legend>"; 



$sql="SELECT debtorsmaster.name,custbranch.phoneno,custbranch.faxno,salesorders.orddate AS orderdate,bio_installation_status.installed_date,bio_plantstatus.plantstatus 
      FROM   debtorsmaster,custbranch,salesorders,bio_installation_status,bio_plantstatus
      WHERE  bio_installation_status.orderno=salesorders.orderno
      AND    salesorders.debtorno= debtorsmaster.debtorno
      AND    custbranch.debtorno= debtorsmaster.debtorno 
      AND    salesorders.currentstatus= bio_plantstatus.id";
if(isset($_GET['year'])){
$sql.=" AND salesorders.orddate BETWEEN '$_GET[year]-01-01' AND '$_GET[year]-12-31' ";
}      
      
$sql.=" UNION 
      
      SELECT debtorsmaster.name,custbranch.phoneno,custbranch.faxno,bio_oldorders.createdon AS orderdate,bio_installation_status.installed_date,bio_plantstatus.plantstatus 
      FROM   debtorsmaster,custbranch,bio_oldorders,bio_installation_status,bio_plantstatus
      WHERE  bio_installation_status.orderno=bio_oldorders.orderno
      AND    bio_oldorders.debtorno= debtorsmaster.debtorno
      AND    custbranch.debtorno= debtorsmaster.debtorno
      AND    bio_oldorders.currentstatus= bio_plantstatus.id";
if(isset($_GET['year'])){
$sql.=" AND bio_oldorders.createdon BETWEEN '$_GET[year]-01-01' AND '$_GET[year]-12-31' ";
}        
//echo $sql;      
$result=DB_query($sql,$db);



      
echo "<div style='height:400px; overflow:auto;'>"; 
echo"<table width=800px>";

if(!isset($_GET['year'])){
    $_GET['year']=date('Y');
}

echo"<tr><td>Select Year&nbsp;<select name='year' id='year' onchange='selectyear(this.value)';>";
for($i=1998;$i<=2020;$i++)
{
    if ($i==$_GET['year'])
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $i.'">'.$i;
    echo '</option>';
}
echo"</select></td></tr>";

echo"<tr><th>Customer Name</th><th>Contact No</th><th width=120px;>Order Date</th><th>Installation Date</th><th>Status</th></tr>";



  
while($myrow=DB_fetch_array($result))
{
              if ($k==1)
              {
                echo '<tr class="EvenTableRows">';
                $k=0;
              }else 
              {
                echo '<tr class="OddTableRows">';
                $k=1;     
              }
              
    if($myrow['phoneno']==""){
       $contactno=$myrow['faxno']; 
    }else{
       $contactno=$myrow['phoneno'];  
    }          
              
echo "<td>".$myrow['name']."</td>
      <td>".$contactno."</td> 
      <td>".ConvertSQLDate($myrow['orderdate'])."</td>
      <td>".ConvertSQLDate($myrow['installed_date'])."</td>
      <td>".$myrow['plantstatus']."</td> 
                  
</tr>";           
}



echo"</table>"; 
echo"</div>";  

echo"</fieldset>";   

echo"</form>";
?>


<script type="text/javascript">  

function selectyear(str)
{
    location.href="?year=" + str;
}

</script>