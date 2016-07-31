<?php
  $PageSecurity = 15;  
include('includes/session.inc'); 
if(isset($_GET['off'])){
    
          $office=$_GET['off'];
          $_SESSION[off]=$office;
  $sql="select bio_emp.empid,
            bio_emp.empname,
            bio_emp.deptid,
            bio_dept.deptname           
            from bio_emp,bio_dept 
            where bio_emp.deptid=bio_dept.deptid
            AND bio_emp.offid='$office'";
$result=DB_query($sql,$db);  

    echo'<td>Employee</td><td><select name=deptemp id=deptemp onchange="contact(this.value)">';

echo'<option value=0></option>';
while($row=DB_fetch_array($result))
{       
if ($row['empid']==$_POST['deptemp'])
{
echo '<option selected value="';
} else {

echo '<option value="';
}
echo $row['empid'] . '">'.$row['empname'].','.$row['deptname'];
echo '</option>';
}
echo'</select></td>';




} 

?>
