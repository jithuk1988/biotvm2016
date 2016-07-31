<?php

$PageSecurity = 80;  
include('includes/session.inc');
  $tsk=$_GET['task'];
  $desig=$_GET['designation']; 
  $date=$_GET['date'];
if(isset($_GET['delete']))
{
$sql= "DELETE FROM bio_achievementpolicy WHERE designation='".$desig."' 
                                            AND taskid='".$tsk."'
                                            AND effectivedate='".$date."";
$result=DB_query($sql,$db);   
prnMsg(_('deleted') ,'success');   
}

echo '<div style="background-color:#EBEBEB" id="achievedetails" name="achievedetails">';
echo "<fieldset style='float:center;width:50%;'>";     
     echo "<legend><h3>Achievement policy Details</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";

     echo '<tr><td style="border-bottom:1px solid #000000">TASK</td>
     <td style="border-bottom:1px solid #000000">DESIGNATION</td>   
                <td style="border-bottom:1px solid #000000">DATE</td>
                </tr>';
                
//                $sql6="select MAX(effectivedate) as effectivedate from bio_achievementpolicy";
//                $result6=DB_query($sql6,$db);
//                $myrow6=DB_fetch_array($result6);
            
                               $sql="SELECT bio_achievementpolicy.id,                                      
                                      bio_achievementpolicy.taskid,
                                      bio_achievementpolicy.designation,
                                      bio_achievementpolicy.effectivedate FROM 
                                      bio_achievementpolicy 
                                      WHERE                                      
                                            bio_achievementpolicy.designation='".$desig."' 
                                            AND bio_achievementpolicy.taskid='".$tsk."'
                                                                                                                        ORDER BY bio_achievementpolicy.effectivedate ASC";
                                             $result=DB_query($sql,$db);
                                            
echo "</tr>";
         $sql7="SELECT bio_designation.designation from 
                                 bio_designation 
                                 WHERE bio_designation.desgid='".$desig."'";
                                 $result7=DB_query($sql7,$db);
                                 $myrow7=DB_fetch_array($result7); 
 if(DB_num_rows($result)==null){ 
    
          printf("<tr style='background:#A8A4DB'>        
               <td></td><td></td><td></td><td width='50px'><a  style='cursor:pointer;' onclick=add('$id')>" . _('New') . "</a></td></tr> ");        } 
           else{
while($myrow = DB_fetch_array($result))
         {
if($myrow[effectivedate]==$effectdate){}else{
    $effectdate=$myrow[effectivedate];
 if($myrow[taskid]==1){$task="Sale Order Value";}else{$task="Feasibility Charge";} 
          $designt=$myrow[designation];
                                   
    printf("<tr style='background:#A8A4DB'>
            <td cellpading=2 width='150px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='30px'><a  style='cursor:pointer;' onclick=edit('$myrow[taskid]','$myrow[designation]','$myrow[effectivedate]')>" . _('New') . "</a> </td><td width='30px'> <a  style='cursor:pointer;' onclick=edit('$myrow[taskid]','$myrow[designation]','$myrow[effectivedate]')>" . _('Edit') . "</a> </td><td width='30px'> <a  style='cursor:pointer;' onclick=dlt('$myrow[taskid]','$myrow[designation]','$myrow[effectivedate]')>" . _('Delete') . "</a></td>
            </tr>",
        $task,
        $myrow7[designation],
        $myrow[effectivedate]); 
 // <td width='50px'><a  style='cursor:pointer;'  onclick=showCD2('$leadid','$enq')>" . _('Edit') . "</a></td>       
           }
           }
          }
          echo "</table>";
                    
echo "</fiedset>";


echo "</div>";



?>