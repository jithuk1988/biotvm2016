<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Verification');  
include('includes/header.inc');
$lead=$_GET['idpf'];
if(isset($_POST['submit']))
         {
            
 $sql_update1="UPDATE bio_leads SET id_type='".$_POST['idtype']."',id_no='".$_POST['idnum']."' WHERE  leadid='".$_POST['lead']."'";               
                                        DB_query($sql_update1,$db); 
                                        
           echo "<script type=text/javascript>";
           
           echo "window.close()";
           echo "</script>";
         }
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>"; 
    $id=$_GET['idpf'];
    $sql="SELECT id_type,id_no FROM   bio_leads where leadid=".$id;                              
     $result=DB_query($sql,$db);
     $value=DB_fetch_array($result);        
    echo"<fieldset style='width:75%;'>";
    echo"<legend><h3>Edit ID Proof</h3></legend>";
    echo"<table width=100%>";
    echo "<tr><td>ID Type</td><td><input type=text name=idtype value='".$value['id_type']."'></td></tr>";
    echo "<tr><td>ID Number</td><td><input type=text name=idnum value='".$value['id_no']."'></td></tr>";
    echo "<tr><td></td><td><input type=submit name=submit value=update></td></tr>";
    echo "<input type=hidden name=lead value='".$lead."'>";
    echo"</table>";
    echo"</fieldset>";
    echo "</form>";
?>
