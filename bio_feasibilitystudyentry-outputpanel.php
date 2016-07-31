<?php

$PageSecurity = 80;
include('includes/session.inc');


$leadid=$_GET['leadid']; 

$sql9="SELECT COUNT( leadid )
       FROM bio_fsentry_output
       WHERE leadid=$leadid";
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9); 



echo "<fieldset style='width:825px'>";   
echo "<legend><h3>Output Type</h3>";
echo "</legend>"; 
echo "<table style='align:left;width:85%' border=0>";

if($myrow9[0] > 0)      {
    
    
$sql="SELECT * 
      FROM bio_fsentry_output
      WHERE leadid=$leadid";  
$result=DB_query($sql,$db); 
$myrow=DB_fetch_array($result);

$outputtype=$myrow['output'];


$outputtype_id2=explode(',',$outputtype);
    $n=sizeof($outputtype_id2);
    
  echo '<tr><td>Output Type</td>';
    $sql_out="SELECT * FROM bio_outputtypes";
    $result_out=DB_query($sql_out,$db);
    $j=1;
    $f=0;
    
  echo'<td><table><tr>';  
  while($mysql_out=DB_fetch_array($result_out)){
      $f=1;
      for($i=0;$i<$n;$i++)        {
        if($mysql_out[0]==$outputtype_id2[$i]){
           echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].' checked>'.$mysql_out[1].'</td>';
           $j++; 
         $f=0;
        }
      }
      if($f==1){
         echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
         $j++;
         $f=0; 
      }  
        if( ($j%2)-1==0 ){
            echo'</tr><tr>';
        }
  }
  echo"</tr>";  
} 
else{
    


echo '<tr><td>Output Type</td>';

$sql_out="SELECT * FROM bio_outputtypes";
$result_out=DB_query($sql_out, $db);
$j=1;
echo'<td><table><tr>';
while($mysql_out=DB_fetch_array($result_out)){
        
echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
        $j++;    
            
        if( ($j%2)-1==0 ){
            echo'</tr><tr>';
        }
               
    } 
    echo"</tr>";
    
  }   
    
    
      
    echo"</table></td></tr>";        
    echo"<input type='hidden' name='houttype' id='houttype' value='$j'>";




echo "</table>";

echo"<div id='outputs'>"; 
echo"</div>";

 
echo"<div align=center>";
echo"<input type='button' value='Submit' onclick='outputsubmit()'>"; 
echo"</div>";
 
echo"</fieldset>";
  
?>

