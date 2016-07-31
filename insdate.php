<?php
   $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('Change Installation Date');
include ('includes/header.inc'); 
 echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>"; 
//$dbno=$_GET['DebtorNo'];

if($_POST['submit'])
{//echo "jjjjjjjjjjjj";
     $date=FormatDateForSQL($_POST['date']);
 $dbno=$_POST['debtor'];
 $sq="SElect orderno from salesorders where debtorno='".$dbno."'";
$res=DB_query($sq,$db);
$mra=DB_num_rows($res);
$mr=DB_fetch_array($res);
if($mra!=0)
{
//  sales    
$sql="SELECT `installed_date` FROM `bio_installation_status` WHERE `orderno`='".$mr[0]."'";
$result=DB_query($sql,$db);
$mr1=DB_fetch_array($result);
if($mr1[0]==null)
{
$sql_ins="INSERT INTO `bio_installation_status`(`orderno`, `installed_date`) VALUES ('".$mr[0]."','".$date."') ";
 $result1=DB_query($sql_ins,$db); 
}else
{
  $sql_up="UPDATE `bio_installation_status` SET `installed_date`='".$date."' WHERE `orderno`='".$mr[0]."'";
 $result1=DB_query($sql_up,$db); 
}

}
else
{
 //   old
$sql1="UPDATE `bio_oldorders` SET `installationdate`='".$date."' WHERE `debtorno`='".$dbno."'";
$result1=DB_query($sql1,$db); 
}
?>
<script type="text/javascript">
window.close();
</script>
<?php

   
}


 echo '<input type="hidden" name="debtor" value="'.$_GET['DebtorNo'].'">';
 echo"<fieldset style='width:90%;'>";
       echo"<legend><h3>Installed date</h3></legend>";
       echo "<center>Installed date<input type='text' name='date' class=date alt=".$_SESSION['DefaultDateFormat']."></center>";
       echo "<center><input type='submit' name='submit'></center>";
       echo '</fieldset>';
echo "</form>";
?>