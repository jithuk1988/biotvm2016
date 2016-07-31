<?php
  $PageSecurity = 80;
include('includes/session.inc');

echo '<td>' . _('Feedstock Production per unit') . ':</td>
            <td><input type="text" name="Amount" id="amount" style="width:180px" value="' . $_POST['Amount'] . '"></td>';
?>
