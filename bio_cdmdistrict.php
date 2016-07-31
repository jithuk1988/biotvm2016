<?php
  $PageSecurity = 80;
include('includes/session.inc');
if($_GET['state'])
{
     $sqdt="Select did,district from bio_district where cid=1 and stateid=".$_GET['state']."";
          $res_dt=DB_query($sqdt,$db);
          echo "<select name='district' id='district'>"; 
           echo '<option value="-1">select</option>';
            while ($md=DB_fetch_array($res_dt))
          {
              echo "<option value=".$md[0].">".$md[1]."</option>";
          }
          echo"</select>";
}
?>
