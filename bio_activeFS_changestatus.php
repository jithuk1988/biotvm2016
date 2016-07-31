<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Active to Passive') . ' / ' . _('Maintenance');     
include('includes/header.inc');
if(isset($_GET['lead'])){
    $lead_id=$_GET['lead'];
    
}
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Enquiry Types')
    . '" alt="" />' . _('Active Setup') . '</p>';
echo '<a href="index.php">Back to Home</a>';


 if(isset($_POST['submit']))
 {   
  if($_POST['status']==1)
   {
    $sql6="UPDATE bio_leads SET leadstatus=16 where leadid=".$_POST['LeadID'];
    $result6=DB_query($sql6,$db);
    $myrow_lead=DB_fetch_array($result6);
   }
 else if($_POST['status']==2)
 {
   $sql6="UPDATE bio_leads SET leadstatus=21 where leadid=".$_POST['LeadID'];
    $result6=DB_query($sql6,$db);
    $myrow_lead=DB_fetch_array($result6);  
 }
   
 header("Location: bio_activefeasibilitystudyproposals.php");
 
 }
   
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<table style=width:25%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="height:250px">'; 
 echo '<legend><b>Reason Master</b></legend>';
 echo '<br><br><table class="selection">'; 
 
 
 

 echo '<tr><td>Category</td>';
echo '<td><select name="category" id="category">';
echo '<option value="0"></option>';
 
 $sql1="select * from bio_taskmaster";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
   echo '<option value='.$row1['taskname_id'].'>'.$row1['taskname'].'</option>'; 
}

echo '<tr><td>Status</td>';
 
echo '<td><select name="status" id="status">';
 echo '<option value="0"></option>';
 $sql1="select * from bio_changestatus";
 $result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
    echo '<option value='.$row1['statusid'].'>'.$row1['status'].'</option>'; 
}









 echo '<tr><td>Reason</td><td><input type="text" name="reason" id="text" value="'.$name.'"></td></tr>'; 
 echo '</select></td></tr>';
 
 
 
  echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick=" if(validate()==1)return false"></td></tr>';   
 echo '</table>';
echo '</form></fieldset>';
  
   echo '</div>';      

    echo'<input type="hidden" name="LeadID" value='.$lead_id.'>';
    ?>
    
    
    <script>
    function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('reason','Please enter the reason');  if(f==1){return f; }  }

  
}
</script>