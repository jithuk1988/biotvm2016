<?php
   $PageSecurity = 80;
 include('includes/session.inc');
 if($_GET['desg']==1){
         $sql="select bio_leadteams.teamid,bio_leadteams.teamname from bio_leadteams,bio_emp, bio_teammembers WHERE 
  office_id='".$_GET['office']."'
   AND bio_emp.designationid=19
   AND bio_emp.empid IN (bio_teammembers.empid)
   AND bio_teammembers.teamid=bio_leadteams.teamid  
   ";
 }
 else if($_GET['desg']==2)
 {
              $sql="select bio_leadteams.teamid,bio_leadteams.teamname from bio_leadteams,bio_emp, bio_teammembers WHERE 
  office_id='".$_GET['office']."'
   AND bio_emp.designationid=9
   AND bio_emp.empid IN (bio_teammembers.empid)
   AND bio_teammembers.teamid=bio_leadteams.teamid  
   ";
 }
else if($_GET['desg']==3)
 {
              $sql="select bio_leadteams.teamid,bio_leadteams.teamname from bio_leadteams,bio_emp, bio_teammembers WHERE 
  office_id='".$_GET['office']."'
   AND bio_emp.designationid=29
   AND bio_emp.empid IN (bio_teammembers.empid)
   AND bio_teammembers.teamid=bio_leadteams.teamid  
   ";
 }
    $result=DB_query($sql,$db);
   echo '<td id=team><select name="Team" id="team"  style="width:160px" onclick="showalert()"">';
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {  
        if ($row['id']==$officeclass)
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
