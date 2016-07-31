
  <?php
  $PageSecurity = 15;  
include('includes/session.inc'); 

if(isset($_GET['con'])){
$cont= $_GET['con'];
       $_SESSION[empd]=$_GET['con'];
 $sql = "SELECT * FROM bio_emp
                WHERE empid=$cont";
         
    $result = DB_query($sql,$db);
    $myrow=DB_fetch_array($result);
    //echo'<td>';
echo'<table align=left border="0">';
echo'<tr colspan=2>';

echo '<tr><td width=200px>' . _('Full Name') . ':</td>
    <td><input type="text" name="RealName" value="' .$myrow['empname']  . '" size="36" maxlength="35"></td></tr>';
echo '<tr><td>' . _('Contact No') . ':</td>
    <td><input type="text" name="Phone" value="' . $myrow['c_number']  . '" size="32" maxlength="30"></td></tr>';
echo '<tr><td>' . _('Email Address') .':</td>
    <td><input type="text" name="Email" value="' .  $myrow['email']  .'" size="32" maxlength="55"></td></tr>';
echo'</td></tr>';    
echo'</table>';
// echo'</td>';

 }


?>

