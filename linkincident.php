<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Link Customer');  
include('includes/header.inc');
      
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Link Customer</font></center>';
    
    
    
    if($_POST['submit'])
    {
        if($_POST['dno']!="")
        {
           $dno=$_POST['dno']; 
            $sql_dateupdate="UPDATE bio_incidents SET debtorno='$dno' WHERE ticketno='".$_POST['tick']."'"; 
        DB_query($sql_dateupdate,$db);
            
        }
    }
    
    
  $ticket=$_GET['Incdnt'];  
    
  echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';   
  
  echo "<input type=hidden name='tick' id='tick' value=$ticket>";
                                              
  echo '<fieldset style="height:170px">'; 
  echo '<legend><b>Customer ID</b></legend>';
  echo '<br><br><table class="selection">';  
  
  echo '<tr><td>Enter D/C/L No:</td>';
  echo'<td><input type="text" id="dno"  name="dno" required></td></tr>';   
  echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick="if(validate()==1)return false;"></td></tr>';   
  echo '</table>';
  echo '</form></fieldset>';    
  
  
?>
<script>
function validate()
{
     
 var f=0; 
if(f==0){f=common_error('dno','Enter a D No.');  if(f==1) { return f; }}     
}
</script>