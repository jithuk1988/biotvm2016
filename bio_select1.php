<?php
  $PageSecurity = 80;
include ('includes/session.inc');
$title = _('PRODUCTS');
include ('includes/header.inc');
include ('includes/SQL_CommonFunctions.inc');
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
if($_POST['sub'])
{
   $fir=$_POST['c1']; 
   $lpg=$_POST['c2'];
   $grid=$_POST['c3'];
  $debtorno=$_POST['sel'];
  $sq31="UPDATE bio_cdmbase SET firewood=0,lpg=0,grid=0 WHERE debtorno='$debtorno' ";
$mas13=DB_query($sq31,$db) ;  
$sq3="UPDATE bio_cdmbase SET firewood='$fir',lpg='$lpg',grid='$grid' WHERE debtorno='$debtorno' ";
$mas3=DB_query($sq3,$db) ;  
$row2=DB_fetch_array($sm2);
          ?>
<script type="text/javascript">
window.close();
</script>
<?php
}
if($_GET['DebtorNo'])
{
$debtorno=$_GET['DebtorNo'];
 $sq1="SELECT  firewood, lpg, grid FROM bio_cdmbase WHERE debtorno='$debtorno'";                                                                                            
$sm2=DB_query($sq1,$db);
$row2=DB_fetch_array($sm2);
 $fire=$row2['firewood'];
 $lpg=$row2['lpg'];
$grid=$row2['grid'];
echo "<input type='hidden' name='sel' value='$debtorno'>";
}
echo"<fieldset  style='width:400px;'>";
echo"<legend><h3>SELECT BASE LINE</h3></legend>";
echo "<table width='400px'>";
echo"<tr style='background:#585858;color:white'>";


if($fire>0)
{
echo'<table><tr><td>firewood<input type="checkbox" name="c1" value="1" checked>';
}
else
{
echo'<table><tr><td>firewood<input type="checkbox" name="c1" value="1">';
}
if($lpg>0)
{
echo'lpg<input type="checkbox" name="c2" value="1" checked>';
}
else
{
echo'lpg<input type="checkbox" name="c2" value="1">';
}
if($grid>0)
{
echo'grid<input type="checkbox" name="c3" value="1" checked></td></tr>';
}
else
{
echo'grid<input type="checkbox" name="c3" value="1"</td></tr>';
}
echo'<tr><td><input type="submit" name="sub"></td></tr></table>';
?>
