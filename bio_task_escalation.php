<?php
 $PageSecurity = 20;
 include('includes/session.inc');
 $title = _('Escalated Task Report'); 
 $pagetype=3; 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">ESCALATED TASK REPORT</font></center>';
      
//==========================================================================  
    
echo "<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table><tr>';
 
echo'<td valign=top>';    
echo"<fieldset style='width:700px;height:150px'; overflow:auto;'>";
echo"<legend><h3>Select Task</h3></legend>";
echo"<table>";    

echo"<tr><td>Task</td><td>Office</td><td>Team</td></tr>";
echo"<tr>";
//echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
//echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';

    echo '<td><select name="task" id="task"  style="width:100%">';  
    $sql="select * from bio_task";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['taskid']==$_POST['task'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['taskid'] . '">'.$row['task'];
        echo '</option>';
    }
    echo'</select></td>';  
    
      
     
    echo '<td><select name="office" id="office"  style="width:100%">';  
    $sql="select * from bio_office";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['id']==$_POST['Office'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['id'] . '">'.$row['office'];
        echo '</option>';
    }
    echo'</select></td>';    

    
    echo '<td><select name="teamname" id="leadteam"  style="width:100%">';
    $sql="select * from bio_leadteams";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['teamid']==$_POST['teamname'])
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $row['teamid'].'">'.$row['teamname'];
        echo '</option>';
    }
    echo'</select></td>';

echo '</tr>'; 
echo"</table>";


echo"<table>"; 
echo '<tr>';
//echo '<td><a style=cursor:pointer; onclick=passid(1)>' . _('Escalated Task Completed') . '</a></td></tr>';  
echo '<tr><td><a style=cursor:pointer; onclick=passid(0)>' . _('Pending Task') . '</a></td>';
echo'</tr>';
echo"</table>";
echo"</fieldset>"; 



?>

<script type="text/javascript">

function passid(str){
        
 //var str1=document.getElementById('df1').value;   
 //var str2=document.getElementById('dt1').value;        
 var str3=document.getElementById('task').value;   
 var str4=document.getElementById('office').value; 
 var str5=document.getElementById('leadteam').value;  
 

 window.location="bio_task_escalation_report.php?status=" + str + "&task=" + str3 + "&off=" + str4 + "&team=" + str5; 
}

</script>

                                  

