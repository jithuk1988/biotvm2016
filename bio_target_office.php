<?php
 $PageSecurity = 80;
 include('includes/session.inc');
 
 echo '<form action="bio_ptsexel_office.php" method="post">';  
 
 echo"<fieldset style='width:500px;height:150px'; overflow:auto;'>";
 echo"<legend><h3>Select Team</h3></legend>";
 echo"<table>";

 echo"<tr><td>Office*</td>";
 echo '<td><select name="Office" id="office"  style="width:100%" onchange="displayTeams(this.value)">';
 
 $sql="select * from bio_office";
 $result=DB_query($sql,$db);
 
 echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['id']==$_POST['Office'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['id'] . '">'.$row['office'];
        echo '</option>';
    }
 echo'</select></td>';
 echo'</tr>';   
 

 echo"</table>";
 echo"</fieldset>";
 echo'<tr><td colspan=2><p><div class="centre"><input type="submit"  name=exelview VALUE="' . _('Report') . '">'; 
 echo '</form>';  
?>