<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Drop Lead');  
include('includes/header.inc');

$leadid=$_GET['leadid'];

if(isset($_POST['submit']))
  {
      $leadid=$_POST['leadid'];
      $remarks=$_POST['remarks'];
      $sql_remark="SELECT remarks FROM bio_leads WHERE leadid=$leadid"; 
      $result_remark=DB_query($sql_remark,$db);
      $row=DB_fetch_array($result_remark);
      $rem1=$row['remarks'];
$rem1=str_replace("'","-",$rem1); 
      $remark=$rem1."\r\n".date("Y-m-d").":".$remarks;
      
      $sql_append='UPDATE bio_leads SET remarks="'.$remark.'" WHERE leadid='.$leadid;   
      DB_query($sql_append,$db);
      
      $sql2="UPDATE bio_leadtask SET taskcompletedstatus=2 WHERE leadid=$leadid
      AND taskcompletedstatus!=1";    
      DB_query($sql2,$db);     
      $sql3="UPDATE bio_leads SET leadstatus=20 WHERE leadid=$leadid";         
      DB_query($sql3,$db);
      ?>
      <script>
      window.opener.location='bio_instTaskview.php';
      window.close();

      </script>
      <?php         

  }



echo'<div>';  
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
 
 echo"<input type='hidden' name='leadid' id='leadid' value='$leadid'>";
echo"<table style='border:1px solid #F0F0F0;width:100%'><tr><td>";

      echo"<fieldset style='width:450px'><legend>Perform Action</legend>";  
      echo"<table>";
     echo"<tr><td>Remarks:</td>"; 
     echo"<td><textarea rows=2 cols=35 name=remarks id=remarks style=resize:none; value='$remarks' onchange=updateDetails('$cid','$remarks')>$remarks</textarea></td></tr>";     
           
    echo"<tr><td></td><td align=right><input type='submit' name='submit' id='submit' value='Submit' onclick='if(validation()==1)return false;;'></td>";  
    echo"</tr></table>";
    echo"</fieldset>";
echo"</td></tr>";


echo"</table>";  
 
echo"</form>";
echo'</div>';       
 
?>
<script type="text/javascript"> 

function validation()
{
    var f=0;
    var p=0;
    if(f==0){f=common_error('remarks','Please enter any remarks');  if(f==1){return f; }  }
}

</script>