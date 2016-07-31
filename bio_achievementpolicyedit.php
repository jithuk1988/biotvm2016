<?php

$PageSecurity = 80;  
include('includes/session.inc');
$tsk=$_GET['task'];
$desig=$_GET['designation']; 
$date=$_GET['date'];

echo '<div style="background-color:#EBEBEB" id="achievedetails" name="achievedetails">';
echo "<fieldset style='float:center;width:50%;'>";     
     echo "<legend><h3>Achievement policy Details</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";

     echo '<tr><td style="border-bottom:1px solid #000000">RANGE FROM</td>   
                <td style="border-bottom:1px solid #000000">INCENTIVE</td>
                </tr>';
                
                /*$sql6="select MAX(effectivedate) as effectivedate from bio_achievementpolicy";
                $result6=DB_query($sql6,$db);
                $myrow6=DB_fetch_array($result6);*/
            
$sql="SELECT id,rangefrom,incentive,effectivedate FROM bio_achievementpolicy WHERE
                                            designation='".$desig."' 
                                            AND taskid='".$tsk."'
                                            AND effectivedate='".$date."'                                                                            ORDER BY rangefrom ASC";
                                             $result=DB_query($sql,$db);
                                            
          if(DB_num_rows($result)==""){
                              for($i=0;$i<8;$i++)
                {
                   
            echo '<tr><td><input type="text" name="rangefrom'.$i.'" id="rangefrom'.$i.'" value=></td>   
              <td><input type="text" name="incentive'.$i.'" id="incentive'.$i.'" value=></td>'; 
                }
               echo '<table style="border:1px solid #F0F0F0;width:200px">';
echo '<tr><td><input type="submit" name="clearall" id="clearall" value="Clear all" style="margin-left:40%"></td>'; 
echo '<td><input type="submit" name="submit" id="submit" value="submit" style="margin-left:40%" onclick="if(log_in()==1)return false;"></td></tr>';  
echo '</table>';  
          }else{                                   
                   
//             for($i=0;$i<8;$i++)
//               {                            
                    $i=0;   
               while($myrow = DB_fetch_array($result)) 
                     {
        
        echo '<tr><td><input type="text"  name="rangefrom'.$i.'" id="rangefrom'.$i.'" value='.$myrow[rangefrom].'></td>   
              <td><input type="text"  name="incentive'.$i.'" id="incentive'.$i.'" value='.$myrow[incentive].'></td>';
               echo '<td><input type="text" readonly name="date1" id="date1" value='.$myrow[effectivedate].'></td>';  
echo '<input type="hidden"  name="id'.$i.'" id="id'.$i.'" value='.$myrow[id].'>';  
$i++;
                     }  
                           
//               }
                echo '<table style="border:1px solid #F0F0F0;width:200px">';
       echo '<tr><td><input type="submit" name="clearall" id="clearall" value="Clear all" style="margin-left:40%"></td>'; 
       echo '<td><input type="submit" name="update" id="update" value="update" style="margin-left:40%"></td>'; 
       echo '<td><input type="submit" name="Newcreate" id="Newcreate" value="New Create" style="margin-left:40%"></td></tr>';  
                echo '</table>';  
                 }
        
                                       
               echo '</tr>';                           
          

 
          echo "</table>"; 
          echo "</fieldset>";           
 

echo '<div>';








?>
