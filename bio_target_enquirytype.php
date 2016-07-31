<?php
  $PageSecurity = 80;
include('includes/session.inc'); 
$enqid=$_GET['enqid'];

if($enqid==1|| $enqid==3) { 
      $sql1="select * from bio_dominst_task
       WHERE inst_id IN(0,18,5,20,26,71)";
            
  echo '<td>Task<select name="task" id="task"  style="width:120px" onchange="showValue(this.value)" ></td>';  
    
    $result=DB_query($sql1,$db);
    echo'<option value=0>-SELECT-</option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['inst_id']==$_POST['task'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['inst_id'] . '">'.$row['inst_task'];
        echo '</option>';
    }  echo'</select>';    
    
}   
elseif($enqid==2)
{          //echo '<td>Task<select name="task" id="task"  style="width:120px" onchange="showValue(this.value)"></td>';  
    
           $sql2="select * from bio_dominst_task 
           WHERE inst_id IN(0,27,20,26,5,2,3,28,71)";
            
  echo '<td>Task<select name="task" id="task"  style="width:120px" onchange="showValue(this.value)" ></td>';  
    
    $result=DB_query($sql2,$db);
    echo'<option value=0>-SELECT-</option>';
    while($row1=DB_fetch_array($result))
    {
        if ($row1['inst_id']==$_POST['task'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row1['inst_id'] . '">'.$row1['inst_task'];
        echo '</option>';
    }  echo'</select>';    
    
}  

    /*$result=DB_query($sql1,$db);
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
    }  */
 
 
       
  $sql1="select * from bio_task
            WHERE taskid=27 OR taskid=15 OR taskid=28 OR taskid=2 OR taskid=14 OR taskid=3 OR taskid=4";   
    

?>

