<?php
  $PageSecurity = 80;
include('includes/session.inc');
$team_ID=$_GET['teamid'];

 echo '<td>' . _('Teams') . ':</td>';
    echo '<td><select name="teamname" id="leadteam"  style="width:100%">';
    $sql="select * from bio_leadteams WHERE office_id=$team_ID";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['teamid']==$_POST['teamname'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['teamid'] . '">'.$row['teamname'];
        echo '</option>';
    }
    echo'</select></td>';


?>
