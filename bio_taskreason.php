<?php
$PageSecurity = 20;
include('includes/session.inc');
$title = _('taskreason') . ' / ' . _('Maintenance');
include('includes/header.inc');
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Enquiry Types')
    . '" alt="" />' . _('Task reason Setup') . '</p>';
echo '<a href="bio_taskreason.php">Back</a>'; 
if(isset($_GET['delete']))
{ 
$did=$_GET['delete'];    
$sql= "delete from bio_taskreason where bio_taskreason.id= $did";
$result=DB_query($sql,$db); 
}

 if(isset($_GET['edit'])){
$rid=$_GET['edit'];
$_SESSION['edit']= $_GET['edit'];
$sql="SELECT * FROM bio_taskreason  WHERE bio_taskreason.id=$rid";
$result=DB_query($sql,$db);
$myrow2=DB_fetch_array($result);
$id=$myrow2['id']; 
$taskid=$myrow2['taskid'];
$reason=$myrow2['reason'];
 $_SESSION['value']=1;
 
//echo "<input type='text' name='edit1' value='".$_SESSION['value']."'/>";
}  

    
    
       if (isset($_POST['submit'])){ 
             
                     if ($_SESSION['value']==1){ 
                         
                     echo $sql_edit=DB_query("update bio_taskreason set taskid='" . $_POST['task'] . "',reason='" .$_POST['reason'] ."' where id='".$_SESSION['edit']."'",$db);    
                     unset($_SESSION['value']);    
                     }else{
   
 $sql = "INSERT INTO bio_taskreason(taskid,
                                      reason
                                      )  
                          VALUES ('" . $_POST['task'] . "',
                                       
                                  '" . $_POST['reason'] . "')";                      
        $result = DB_query($sql,$db);
} 
       }  
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<table style=width:25%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="height:250px">'; 
echo '<legend><b>Task Reason Master</b></legend>';
echo '<br><br><table class="selection">'; 
if($_GET['edit']!=""){
echo '<tr><td>Task id</td><td><input type="text" readonly name="task" id="task" value='.$_GET['edit'].'></td></tr>';
}
 echo '<tr><td>Task id</td>';
echo '<td><select name="task" id="task">';  
echo '<option value="0"></option>'; 
$sql1="select * from bio_task";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
      if ($row1['taskid']==$taskid) 
    {
//      $f=0; 
    echo '<option selected value="';
    
    } else {
//        if ($f==0) 
//        {
//        echo '<option>';
//        }
        echo '<option value="';
//        $f++;
    }
    echo $row1['taskid'] . '">'.$row1['task'];
    echo '</option>';   
}        
echo "</select></td></tr>"; 


   
//echo '<td><select name="Customer" id="customer">';  
//echo '<option value="0"></option>';  
//$sql1="select * from bio_custtype";
//$result1=DB_query($sql1,$db);
//while($row1=DB_fetch_array($result1))
//{
//      if ($row1['typeid']==$type) 
//    {
//       
//    echo '<option selected value="';
//    
//    } else {
//        if ($f==0) 
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//        $f++;
//    }
//    echo $row1['typeid'] . '">'.$row1['enquirytype'];
//    echo '</option>';   
//}
echo '<tr><td>Reason</td><td><input type="text" name="reason" id="reason" value='.$reason.' ></td></tr>'; 
echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick=" if(validate()==1)return false"></td></tr>';   
echo '</table>';
echo '</form></fieldset>'; 
echo '</div>';      
echo "<fieldset style='width:560px'>";
echo "<legend><h3>Task Reason Details</h3></legend>";
echo "<div style='overflow:scroll;height:150px'>";
echo "<table class='selection' style='width:100%'>";
echo '<tr><th>' . _('SL NO') . '</th>  
                <th>' . _('Task name') . '</th>
                <th>' . _('Reason') . '</th>    

                          </tr>';
$sql1="SELECT bio_taskreason.id,
bio_task.task,
             bio_taskreason.reason
                                  FROM              bio_taskreason,bio_task  where bio_task.taskid=bio_taskreason.taskid     ";
$result1=DB_query($sql1, $db);  
$k=0 ;$slno=0; 
while($myrow=DB_fetch_array($result1) ) 
  {  $id=$myrow['id'];
      $task=$myrow['task']; 
     $slno++;
     $reason=$myrow['reason'];
     
    echo"<tr style='background:#A8A4DB'><td>$slno</td>
                                    <td>$task </td>
                                    <td>$reason </td>
                                   
                                    <td><a href='#' id='$id' onclick='edit(this.id)'>Edit</a></td>
                                    <td><a href='#' id='$id' onclick='dlt(this.id)'>Delete</a></td>      
                                    </tr>";  
                                    
                                          
  }     
      
         ?>
<script>
  function dlt(str){
location.href="?delete=" +str;         
 
}
 function edit(str)
 {
  // alert("yyyyyyy");  
location.href="?edit=" +str;         
 
}
 function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('task','Please enter the task');  if(f==1){return f; }  }
if(f==0){f=common_error('reason','Please select the reason');  if(f==1){return f; }  }       
//if(f==0){f=common_error('pvalue','Please enter the value');  if(f==1){return f; }  } 
if(f==0){//
//var x=document.getElementById('pvalue').value;  
//if(isNaN(x)||x.indexOf(" ")!=-1)
//           {  f=1;
//              alert("Please enter Numeric valuethe value"); document.getElementById('pvalue').focus();
//              if(x=""){f=0;}
//              return f; 
//           }
}   
}
 </script>
  <?php
 echo '</td></tr></table>'; 
?>
