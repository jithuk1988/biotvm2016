<?php
$PageSecurity = 15;
include('includes/session.inc');
$title = _('Department - Maintenance');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Department list Maintenance')
	. '" alt="" />' . _('Department Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / Edit / Delete Department') . '</div><br />';

if(isset($_GET['delete'])){ $did=$_GET['delete'];
$sql= "DELETE FROM bio_dept WHERE bio_dept.deptid = $did";
$result=DB_query($sql,$db); 
}
if (isset($_POST['subdept'])){
     
    $InputError = 0;
    $i=1;   
    $checksql = "SELECT count(*)
             FROM bio_dept
             WHERE deptname = '" . $_POST['dept'] . "'";
     $checkresult=DB_query($checksql, $db);
     $checkrow=DB_fetch_row($checkresult);
     if ($checkrow[0]>0) {
        $InputError = 1;
        echo prnMsg(_('You already have a Department called').' '.$_POST['dept'],'error');
        $Errors[$i] = 'Department';
        $i++;
    }
    else if($_GET['edit'] && $InputError != 1)
    {
        $sql="update bio_dept SET deptname = '" . $_POST['dept'] . "' where deptid = $did";
    }
    else
    { 
    $dept=$_POST['dept'];
    $sql="INSERT INTO bio_dept(bio_dept.deptname) VALUES('$dept')";
    $result=DB_query($sql,$db);   
    }
}
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";   
        
 echo"<table><tr><td>";
 echo"<fieldset><legend>Add Department</legend>";
  echo"<table>";   
 echo"<tr><td>Department</td><td><input type='text' name='dept' id='dept' ></td></tr>";
  echo"<tr><td></td><td><input type='submit' name='subdept' id='subdept' value='submit'></td></tr>";   echo"</td></tr></table>";  
  echo"</fieldset>";
  
    echo"<tr><td colspan='2'>";   echo"<fieldset><legend>Depertment</legend><table>";echo"<tr style='background:#585858;color:white'><td>Department id</td><td>Department</td></tr>";  
    $sql="SELECT * FROM bio_dept ORDER BY deptid";
    $result=DB_query($sql,$db);
              while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $did=$myrow['deptid']; 
          $dept=$myrow['deptname'];    
                   echo"<tr style='background:#A8A4DB'><td>$did</td><td>$dept</td><td><a href='#' id='$did' onclick='edt(this.id)'>Edit</a></td><td><a href='#' id='$did' onclick='dlt(this.id)'>delete</a></td></tr>";      
                 }    
             echo"</table></fieldset>";
    echo"</td></tr>";         
 echo"</table>
 </form>";  
?>
<script>

function edt(str){
    location.href="?edit=" +str;
}

function dlt(str){
location.href="?delete=" +str;         
 
}

</script>
