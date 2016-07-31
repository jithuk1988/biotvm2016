<?php
    echo '<tr><td>' . _('Street') . "</td><td><input type='text' name='Address1' value='".$_POST['Address1']."' size=25 maxlength=40></td></tr>";
    echo '<tr><td>' . _('Suburb/City') . "</td><td><input type='text' name='Address2' value='".$_POST['Address2']."' size=25 maxlength=40></td></tr>";
    echo '<tr><td>' . _('State/Province') . "</td><td><input type='text' name='Address3' value='".$_POST['Address3']."' size=25 maxlength=40></td></tr>";
    echo '<tr><td>' . _('Pincode') . "</td><td><input type='text' name='Address4' value='".$_POST['Address4']."' size=25 maxlength=40></td></tr>"; 
    echo '<tr><td>' . _('Phone No') . "</td><td><input type='text' name='Phone' value='".$_POST['Phone']."' size=25 maxlength=40></td></tr>"; 
    echo '<tr><td>' . _('Mobile No') . "</td><td><input type='text' name='Mobile' size=25 maxlength=40></td></tr>"; 
    echo '<tr><td>' . _('Fax') . "</td><td><input type='text' name='Fax' value='".$_POST['Fax']."' size=25 maxlength=40></td></tr>"; 
    echo '<tr><td>' . _('Email') . "</td><td><input type='text' name='Email' value='".$_POST['Email']."' size=25 maxlength=40></td></tr>";
?>
