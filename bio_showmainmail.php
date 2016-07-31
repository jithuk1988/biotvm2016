<?php
 $PageSecurity = 40;
include('includes/session.inc');

  $inctype=$_GET['inctype'];
  echo '<td width=30%>Email Category&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';  
echo '<td><select name="mainmailcategory" id="mainmailcategory"  onchange="showsubmailcategory(this.value)" style="width:200px">';
if ($inctype==1)
{
$sql1="select * from bio_mainemailcategory where main_catid in (1,2,3,4,6)";
}
else if ($inctype==4)
{
   $sql1="select * from bio_mainemailcategory where main_catid=5"; 
}
$result1=DB_query($sql1,$db);
 $f=0;   
  echo '<option selected value="0">--SELECT--</option>';
while($row1=DB_fetch_array($result1))
{
   
    echo '<option  value="';
    echo $row1['main_catid'].'">'.$row1['main_catname'];
    echo '</option>';

}    
     echo '</select></td>'; 
  ?>