<?php
$PageSecurity = 80;
include('includes/session.inc');
   echo "<select name=employee id=employee>";
   $sql="SELECT bio_office.office,
            www_users.`userid`,
            bio_teammembers.teamid,
            bio_emp.designationid,
            bio_emp.empid , bio_emp.empname
 FROM www_users 
inner join  bio_emp on (www_users.`empid`= bio_emp.empid) 
inner join  bio_office on (bio_office.id = bio_emp.offid)
inner join bio_teammembers on (bio_teammembers.empid = bio_emp.empid)
WHERE  `offid` in ('".$_GET['offid']."')"   ;
      $result=DB_query($sql,$db);                                                            //
      while($row=DB_fetch_array($result))
      {
              echo "<option selected value='".$row['empid']."'>".$row['empname']."</option>";  
      }
   /*     $sql="SELECT bio_office.office,
            www_users.`userid`,
            bio_teammembers.teamid,
            bio_emp.designationid,
            bio_emp.empid
 FROM www_users 
inner join  bio_emp on (www_users.`empid`= bio_emp.empid) 
inner join  bio_office on (bio_office.id = bio_emp.offid)
inner join bio_teammembers on (bio_teammembers.empid = bio_emp.empid)
WHERE  `offid` in ('".$_GET['offid']."')"      */
?>

