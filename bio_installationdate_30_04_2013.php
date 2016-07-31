<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Installation Date');  
include('includes/header.inc');
      
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Installation Date</font></center>';
    
    
    
    if($_POST['submit'])
    {
        if($_POST['debtorno'])
        {
            $date1=FormatDateForSQL($_POST['instdate']);
            $sql_dateupdate="UPDATE bio_oldorders SET installationdate='$date1' WHERE debtorno='".$_POST['debtorno']."'"; 
        DB_query($sql_dateupdate,$db);
            
        }else{
            $result_ordno=DB_query("SELECT orderno FROM salesorders WHERE leadid='".$_POST['leadid']."'",$db);
        $row_ordno=DB_fetch_array($result_ordno);
        $orderno=$row_ordno[0];
         
        $date1=FormatDateForSQL($_POST['instdate']);
        $date = strtotime(date("Y-m-d", strtotime($date1)) . " +3 day");
        $duedate=date('Y-m-d',$date);


        $sql_inststatus="INSERT INTO bio_installation_status (orderno,installed_date,due_date1) VALUES ($orderno,'$date1','$duedate')"; 
        DB_query($sql_inststatus,$db);
             
        $sql_leadstatus="UPDATE bio_leads SET leadstatus='39' WHERE leadid='".$_POST['leadid']."'"; 
        DB_query($sql_leadstatus,$db);   
        }
        
        
    }
    
    
  $leadid=$_GET['leadid'];
  $debtorno=$_GET['debtorno'];  
    
  echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';   
  
  echo "<input type=hidden name=leadid value=$leadid>";
  echo "<input type=hidden name=debtorno value=$debtorno>";
 
  echo '<fieldset style="height:170px">'; 
  echo '<legend><b>Enter Date</b></legend>';
  echo '<br><br><table class="selection">';  
  
  echo '<tr><td>Enter Installation Date</td>';
  echo'<td><input type="text" id="instdate" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="instdate"></td></tr>';   
  echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick="if(validate()==1)return false;"></td></tr>';   
  echo '</table>';
  echo '</form></fieldset>';    
  
  
?>
<script>
function validate()
{
     
 var f=0; 
if(f==0){f=common_error('instdate','Select a date');  if(f==1) { return f; }}     
}
</script>