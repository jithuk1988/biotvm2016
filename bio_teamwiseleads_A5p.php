<?php
   $PageSecurity = 80;
include('includes/session.inc');
$title = _('Team Wise Pending Leads');
include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Lead Team')
    . '" alt="" />' . _('Lead Team Pending Leads') . '</p>';
echo '<div class="page_help_text">' . _('View Pending Lead Teams') . '</div><br />';
     
                
        echo'<form name="teamPending"  method="post" action="listpendingteam.php">';
    echo'<table class="selection" width="25%">'; 
    echo'<tr></tr>';
    echo '<tr></tr><tr></tr><tr></tr>';
    echo '<tr><td>Select Team</td>';
    echo '<td><select name=select1>';
    echo '<option value=0>Select</option>';
    
    $sql1="select * from bio_leadteams";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
        echo '<option value='.$row1[teamid].'>'.$row1[teamname].'</option>'; 
    }
    echo '</select></td></tr>';
    echo'<tr><td></td><td><input type="submit" name="team" value="View Pending List"></td></tr>';
    echo'</table>';
        echo'</form>';
        
        
 include('includes/footer.inc');      
?>
