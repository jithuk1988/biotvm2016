<?php
$PageSecurity = 80;
include('includes/session.inc'); 

             
if($_GET['officeid']!="")
{
   echo'<td>Team';        
   echo '<select name="teamname" id="leadteam" style="width:100%">';      
   echo'<option value=0></option>';
    $sql="SELECT * FROM bio_leadteams WHERE bio_leadteams.office_id=".$_GET['officeid'];
    $result=DB_query($sql,$db);
    
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
}    
    
    
if($_GET['enqid']!="")
{    
    $enqid=$_GET['enqid'];
               
    echo '<select name="task" id="task" style="width:100%">';    
    echo'<option value=0></option>'; 
    if($enqid==1){
    $sql="select * from bio_task WHERE taskid IN (18,20,21,1,5,24,25,26) ORDER BY task ASC";
    }elseif($enqid==2){
    $sql="select * from bio_task WHERE taskid IN (27,15,28,2,14,3,29,4,21,5,24,25,26) ORDER BY task ASC";     
    }
    $result=DB_query($sql,$db);
    
    while($row=DB_fetch_array($result))
    {    
        if ($row['taskid']==$_GET['task'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['taskid'] . '">'.$row['task'];
        echo '</option>';
    }
    echo'</select>';  
    
}

?>
