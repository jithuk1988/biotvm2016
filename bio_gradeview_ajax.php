<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 
 
$enqid=$_GET['enqid'];

if($enqid!=6) {

        echo ' <tr id="gradeview"><td>Grade</td>';
 if($enqid==2)
{
        echo '<td>

 <table width="100%" border="0">
  <tr>
    <td width="5" bgcolor="#339900"><label>
      <input type="radio" name="grade" id="grade" value="A" />A</label></td>
    <td width="5" bgcolor=orange><label>
      <input type="radio" name="grade" id="grade" value="B" />B</label></td>
    <td width="5" bgcolor="#0099CC"><label>
      <input type="radio" name="grade" id="grade" value="C" />C</label></td>
    
 </tr>
</table></td>'; 
}
else 
{
   echo '<td>

 <table width="100%" border="0">
  <tr>
    <td width="5" bgcolor="#339900"><label>
      <input type="radio" name="grade" id="grade" value="A" />
    A</label></td>
    <td width="5" bgcolor=orange><label>
      <input type="radio" name="grade" id="grade" value="B" />
    B</label></td>
    <td width="5" bgcolor="#0099CC"><label>
      <input type="radio" name="grade" id="grade" value="C" />
    C</label></td>
    <td width="5" bgcolor="#CCFF33"><label>
      <input type="radio" name="grade" id="grade" value="D" />
    D</label>
      <td width="5" bgcolor=Pink><label>
     <input type="radio" name="grade" id="grade" value="E" />
    E</label></td>
 </tr>
</table></td>'; 
}
                echo'    </tr>'; 
}
?>
