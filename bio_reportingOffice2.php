<?php
  $PageSecurity = 15;  
include('includes/session.inc'); 
if(isset($_GET['rept'])){

$office_type= $_GET['rept'];

echo'<td>' . _('Reporting Office') . ':</td>';  

       $sql8 = "SELECT * FROM bio_office
            WHERE officetype=4 and reporting_off=$office_type";

    $result4 = DB_query($sql8,$db);
    $myrow=DB_fetch_array($result4);
}
     $_POST['reportOff']=$myrow['id']; 


            echo '<td><select name="reportOff" id="regionOff" style="width:100%" >';
            $result4=DB_query($sql8,$db);
            echo'<option value=0></option>';
            while($row=DB_fetch_array($result4))
            {       
                if ($row['id']==$_POST['reportOff'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row['id'] . '">'.$row['office'];
                 echo '</option>';
           }
     echo'</select></td>';




?>
