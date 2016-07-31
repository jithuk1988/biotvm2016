<?php 
$PageSecurity=80;
include('includes/session.inc');

 $_SESSION["close_compl"];
  if($_SESSION["close_compl"]==3){
echo'<td colspan="2" id="addcom"><center><input type="submit" name="reload" value="Reload"><input type="submit" name="submit2" id="submit2" value="Add New Complaint" onclick=" if(newvalidate()==1)return false"><input type=reset value="Clear"></center></td>';

  }else if($_GET['close_com']==7)
  {
  echo'<td colspan="2" id="addcom"><center><input type="submit" name="reload" value="Reload"><input type="submit" name="submit2" id="submit2" value="Add New Complaint" onclick=" if(newvalidate()==1)return false"><input type=reset value="Clear"></center></td>';

  }

?>