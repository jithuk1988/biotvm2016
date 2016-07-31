<?php
$PageSecurity = 80; 
include('includes/session.inc'); 
$title = _('Installation Status');  
include('includes/header.inc');
echo "<fieldset style='width:90%;'>";     
     echo "<legend><h3>CALL STATUS</h3>";
     echo "</legend>";         
     echo "<table style='border:1px solid ;width:90%;'>";
     echo '<tr>
     <td style="border-bottom:1px solid #000000">OrderNo</td>   
                <td style="border-bottom:1px solid #000000">Call NO</td>
                <td style="border-bottom:1px solid #000000">Call date</td>
                <td style="border-bottom:1px solid #000000">Status</td>
                </tr>';
     if($_GET['ordno']!=NULL) {          
  $result="SELECT * FROM bio_calllog WHERE orderno=".$_GET['ordno'];
     }else{ $result="SELECT * FROM bio_calllog";}
                                                                                                                                
   $result_callog=DB_query($result,$db); 
 while($row=DB_fetch_array($result_callog)){
     if($row['status']==1){$status="Success";}
     if($row['status']==2){$status="Customer busy";}
     if($row['status']==3){$status="Customer Not picking";}
     if($row['status']==4){$status="Line Busy";}
                printf("<tr style='background:#A8A4DB'>                
            <td width='150px'>%s<a></td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
            <td width='100px'>%s</td>
                                   </tr>",
        $row['orderno'],
        $row['callno'],        
        $row['call_date'],
        $status       
        ); 

           }
   echo "</table>";            

 
 echo "</fieldset>";

?>
