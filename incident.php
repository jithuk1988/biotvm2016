<?php
  $PageSecurity = 80;//
include ('includes/session.inc');
$title = _('INCIDENT ');
include ('includes/header.inc');
include ('includes/SQL_CommonFunctions.inc');
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
if($_POST['sub'])
{    
$error=0;
    if($_POST['it']=="" )
    {
       $error=1; 
    }
    if($_POST['td']==""  )
    {
       $error=1;  
    } 
    if($error!=1)         
    {
    $type=$_POST['it'] ;
     $description=$_POST['td'] ;
    if($_POST['hil'])
    {
           $id=$_POST['hil'];   
           $sql_s="UPDATE `bio_incidenttype` SET  `type` =  '$type',
`description` =  '$description' WHERE  `bio_incidenttype`.`id` ='$id'";

      $sm_s=DB_query($sql_s,$db) ;   
    }
     else
      {
          
     $sql="INSERT INTO bio_incidenttype(type, description) VALUES ('$type','$description')";


      $sm=DB_query($sql,$db) ; 
      }  
    }   
  ?>
  <script type="text/javascript">
  window.close();

  </script>   
  <?php                                                                

}
 if(isset($_GET['delete']))
 { 
    $id=$_GET['delete'];
 $sql1= "DELETE FROM `bio_incidenttype` WHERE `id`=$id";
$sm1=DB_query($sql1,$db); 
}
  if(isset($_GET['select']))
  { 
    $id=$_GET['select'];
  $sql2= "SELECT `type`, `description` FROM `bio_incidenttype` WHERE `id`=$id";
$sm2=DB_query($sql2,$db); 
$row2=DB_fetch_array($sm2);
$it=$row2['type'];
$td= $row2['description'] ;
echo "<input type='hidden' name='hil' value='$id'>";
  }
echo "<fieldset>";
echo "<legend>Incident type</legend>";
echo "<table><tr><td>Incident type</td><td><input type='text'' name='it' id='it' value='$it'></td> </tr>";
echo "<tr><td>Type discription</td><td><input type='text'' name='td' id='td' value='$td'></td></tr>";
echo "<tr><td><input type='submit' name='sub' onclick='if(valid()==1){return false;}'></td</tr></table>";


    echo "</fieldset>";
 $sql=" SELECT * FROM  bio_incidenttype"; 
$sm=DB_query($sql,$db) ;
echo"<fieldset  style='width:400px;'>"; 
echo "<legend>Incident type</legend>";

 echo ' <div style="height: 200px; width: 100%; overflow: scroll;">';     
echo "<table width='400px' border=1><tr><th>slno</th>
<th>type</th>
<th>description</th>
<th>Select</th>
<th>Delete</th>

</tr>" ;                                                                                                    
$i=1;
 while($row=DB_fetch_array($sm))
{
    $id=$row['id'] ;
    echo "<tr><td>".$i."</td><td>".$row['type']."</td><td>".$row['description']."</td><td><a href='#' id='$id' onclick='slt(this.id)'>select</a></td><td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td>  </tr>" ;
    
    $i++;     
                                                                                                       
}  
    echo "</table>"; 
    echo "</div>"; 
    echo "</fieldset>";
     
?>
<script type="text/javascript"> 
function dlt(str)
{

location.href="?delete=" +str;         
}
function slt(str) 
{
location.href="?select=" +str;         
}
function valid()
{
 str1=document.getElementById("it").value;   
  str2=document.getElementById("td").value;
  if(str1=="")
  {
      alert("please enter incident type");
      return 1;
  }
   if(str2=="")
  {
      alert("please enter type descripttion");
      return 1;
  }
}
</script>