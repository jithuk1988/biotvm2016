<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Reason Master') . ' / ' . _('Maintenance');
include('includes/header.inc');
  if(isset($_GET['delete']))
  { 
      $natid=$_GET['delete'];    
      $sql= "DELETE FROM bio_reasonmaster WHERE bio_reasonmaster.reasonid = $natid";
     $result=DB_query($sql,$db); 
}




echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Enquiry Types')
    . '" alt="" />' . _('Reason Setup') . '</p>';
//echo '<div class="page_help_text">' . _('Add/edit/delete Reason Master') . '</div><br />';
   

echo '<a href="index">Back to Home</a>';   
 if (isset($_POST['submit'])){
 $name=$_POST['reason'];  
 //unset($_POST['reason']);
  unset($name);

 $sql = "INSERT INTO bio_reasonmaster(reason,status,categoryname)  
                          VALUES ('" . $_POST['reason'] . "','" . $_POST['status'] . "',
                                  " . $_POST['category'] . ")";
                                 
        $result = DB_query($sql,$db);
}     
 
  echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<table style=width:25%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="height:250px">'; 
 echo '<legend><b>Reason Master</b></legend>';
 echo '<br><br><table class="selection">'; 
 
 
 

 echo '<tr><td>Category</td>';
echo '<td><select name="category" id="category">';
echo '<option value="0"></option>'; 
 $sql1="select * from bio_taskmaster";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
   echo '<option value='.$row1['taskname_id'].'>'.$row1['taskname'].'</option>'; 
}
 echo '<tr><td>Status</td>';//edited
 echo '<td><select name="status" id="status">';
 echo '<option value="0"></option>';
 $sql2="select * from bio_reasonstatus";
$result2=DB_query($sql2,$db);
while($row2=DB_fetch_array($result2))
{
   echo '<option value='.$row2['id'].'>'.$row2['status_name'].'</option>'; 
}
 echo '</select></td></tr>';
 echo '<tr><td>Reason</td><td><input type="text" name="reason" id="text" value="'.$name.'"></td></tr>'; 
 echo '</select></td></tr>';
 
  echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick=" if(validate()==1)return false"></td></tr>';   
 echo '</table>';
echo '</form></fieldset>';
  
   echo '</div>';      
   echo "<fieldset style='width:560px'>";
      echo "<legend><h3>Reason Details</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr><th>' . _('Serial No') . '</th> 
      
       <th>' . _('Category name') . '</th>
              <th>' . _('Status') . '</th>
                <th>' . _('Reason') . '</th>
               
                          </tr>';
  
                        
  $sql1="SELECT  bio_reasonmaster.reason,
                      bio_reasonmaster.reasonid,
                      bio_reasonmaster.categoryname,
                      bio_reasonstatus.status_name,
                      bio_taskmaster.taskname              
              FROM    bio_reasonmaster,bio_taskmaster,bio_reasonstatus
              WHERE   bio_reasonmaster.categoryname=bio_taskmaster.taskname_id AND bio_reasonmaster.status=bio_reasonstatus.id";
                      
               
  $result1=DB_query($sql1, $db);  
 $k=0 ;$serialno=0; 
  while($myrow=DB_fetch_array($result1) )
  
  {  $rid=$myrow['reasonid']; 
  $serialno++;
          $reason=$myrow['reason'];  
          $status=$myrow['status_name'];  
          $category=$myrow['taskname']; 
echo"<tr style='background:#A8A4DB'><td>$serialno</td>
                                    <td>$category </td> 
                                    <td>$status</td>
                                    <td>$reason</td>
                                    <td><a href='#' id='$rid' onclick='dlt(this.id)'>Delete</a></td>      
                                    
                                    </tr>";      
   }           ?>
                      <script>
  function dlt(str){
location.href="?delete=" +str;         
 
}

 





 function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('text','Please enter the reason');  if(f==1){return f; }  }

if(f==0){f=common_error('category','Please select the task');  if(f==1){return f; }  }
if(f==0){f=common_error('status','Please select the status');  if(f==1){return f; }  }
}

                              </script>
                              <?php
 echo '</td></tr></table>'; 
  
//include('includes/footer.inc');   
?>
  

