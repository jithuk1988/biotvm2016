<?php
 $PageSecurity = 80;
 include('includes/session.inc');        
 $title = _('Schedule'); 
 $pagetype=3; 
 include('includes/header.inc');
$tid=$_GET['tid']; 


echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
// include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;   
    text-shadow: 1px 1px 1px #666;"Subtasks</font></center>';


echo'<div id="schedulegrid">';
echo"<fieldset style='width:550px'><legend><h3></h3></legend>"; 
echo'<div id=editpanel></div>';
echo "<div style='height:200px; width:100%; overflow:scroll;'>";   
 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
        <th>Subtask Name</th>
        <th>Start Date</th>
        <th>End date</th>
        <th>Status</th>";


 $sql8="SELECT bio_subtask_master.subtask_name,
               bio_subtask_master.subtask_date,
               bio_subtask_master.status, 
               bio_leadtask.assigneddate,
               bio_subtask_master.subtask_master_id
          FROM  bio_subtask_master,bio_leadtask
         WHERE bio_subtask_master.leadtask_id =$tid     AND
               bio_leadtask.tid=$tid";
  
$resultl= DB_query($sql8,$db);
  
  
  $k=0 ;$slno=0;
  while($myrow = DB_fetch_array($resultl))
   {
      $subid=$myrow['subtask_master_id'];
       
       if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  
      $slno++;
      
      if($myrow['status']==0)       {
          
      $d="<a style='cursor:pointer;' onclick=changestatus($tid,$subid)>Pending";    
      }else     {
          
      $d="<a style='cursor:pointer;' onclick=changestatus($tid,$subid)>done";    
      }
      
      $startdate=ConvertSQLDate($myrow['assigneddate']);
      $enddate=ConvertSQLDate($myrow['subtask_date']);

      printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>   
            </tr>",
        $slno,
        $myrow[0],
        $startdate,
        $enddate,
        $d);
      
      } 
 
echo '<tbody>';
echo"</tr></tbody>
</table>";  
if($slno==0)        {
    
    echo"No subtasks defined";
}
echo"</div>";
echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 
 
 echo'</form>';
?>

<script type="text/javascript"> 
function changestatus(str,str1)      {
    
    
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
      
  if (xmlhttp.readyState==4 && xmlhttp.status==200)        
  
    {
    document.getElementById("editpanel").innerHTML=xmlhttp.responseText;      

    }
  } 
xmlhttp.open("GET","bio_changesubtaskstatus.php?tid=" + str + "&subid=" + str1,true);  
xmlhttp.send();    
}
</script>
                                  

