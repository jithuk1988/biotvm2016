<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 

          $desgid=$_GET['desgid'];
          $officeid=$_GET['offid'];
          
          if($desgid==15 || $desgid==17 || $desgid==20)
          {
              if($desgid==15){$assistant_to=4;}
              elseif($desgid==17){$assistant_to=16;}
              elseif($desgid==20){$assistant_to=18;}
              
               echo '<td>' . _('Assistant to') . ':</td>';
               
               $sql7="SELECT bio_emp.empid,
                             bio_emp.empname,
                             bio_emp.designationid,
                             bio_designation.designation 
                        FROM bio_emp,bio_designation 
                       WHERE bio_designation.desgid=bio_emp.designationid
                         AND bio_emp.designationid=$assistant_to
                         AND bio_emp.offid=$officeid";
               $result7=DB_query($sql7,$db);
               
               
                echo '<td ><select name=Assistant_to style="width:100%">';
               
               while($row7=DB_fetch_array($result7))
               {
                 if ($row7['empid']==$_POST['Assistant_to'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row7['empid'] . '">'.$row7['empname'].','.$row7['designation'];   
                 echo '</option>';
               }
               echo'</select></td>';
          }
          else
          {
          $sql="SELECT level FROM bio_designation WHERE desgid=$desgid";
          $result=DB_query($sql,$db);
          $row=DB_fetch_array($result);
          
          $sql2="SELECT desgid FROM bio_designation WHERE level < '".$row['level']."'";
          $result2=DB_query($sql2,$db);
          $desg_arr=array();
            while($row2=DB_fetch_array($result2)) 
            {  
              $desg_arr[]=$row2['desgid'];
              $designation_array=join(",", $desg_arr); 
            }
          $sql3="SELECT empid FROM bio_emp WHERE offid=$officeid AND designationid IN ($designation_array)";
          $result3=DB_query($sql3,$db);
          $emp_arr=array();
          while($row3=DB_fetch_array($result3)) 
          {
              $emp_arr[]=$row3['empid'];
              $employee_array=join(",", $emp_arr);            
          }
        $emp_count=count($employee_array);
       
        if($emp_count>0)
        {
        
        echo '<td>' . _('Reporting Officer') . ':</td>';
         
             $sql4="SELECT bio_emp.empid,
                           bio_emp.empname,
                           bio_designation.designation
                    FROM bio_emp,bio_designation 
                    WHERE bio_emp.designationid=bio_designation.desgid
                    AND bio_emp.empid IN ($employee_array)
                    ";
               $result4=DB_query($sql4,$db);     
               $row_count = DB_num_rows($result4); 
               echo '<td ><select name=Repoff style="width:100%">';      
    
             
            while($row4=DB_fetch_array($result4))
             {       
                 if ($row4['empid']==$_POST['Repoff'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row4['empid'] . '">'.$row4['empname'].','.$row4['designation'];   
                 echo '</option>';
             }
            echo'</select></td>';
}
else
{
           prnMsg('No Reporting officer for this designation','warn');
}              
          }              
          
            

?>
