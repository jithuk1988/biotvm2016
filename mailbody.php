<?php
$PageSecurity = 80;//
include ('includes/session.inc');
$title = _('mailbody ');
include ('includes/header.inc');
include ('includes/SQL_CommonFunctions.inc');
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
if($_POST['sub'])   
{
    $enq_type=$_POST['et'] ;
    $body_type=$_POST['bt'] ;
    $message=$_POST['me']     ;
    if($_POST['sn'])
    {
           $id=$_POST['sn'];   
           $sql4="UPDATE `bio_mailbody` SET  enq_type =  '$enq_type',
                   `body_type` =  '$body_type', message =  '$message' WHERE  `bio_mailbody`.`id` ='$id'";

           $mas4=DB_query($sql4,$db) ;   
    }
     else
      {
  $sql="INSERT INTO bio_mailbody( enq_type, body_type, message ) VALUES ('".$enq_type."','".$body_type."','".$message."')";           
    $mas=DB_query($sql,$db);  
}
}

           if(isset($_GET['delete']))
 { 
    $id=$_GET['delete'];
 $sql2= "DELETE FROM `bio_mailbody` WHERE `id`=$id";
$mas2=DB_query($sql2,$db); 
}
           if(isset($_GET['select']))
  { 
    $id=$_GET['select'];
  $sql3= "SELECT `enq_type`, `body_type`, message FROM `bio_mailbody` WHERE `id`='".$id."'";
$mas3=DB_query($sql3,$db); 
$row3=DB_fetch_array($mas3);
$et=$row3['enq_type'];
$bt= $row3['body_type'] ;
 $me= $row3['message'] ;   
   
             echo"<input type='hidden' name='sn' id='sn' value='".$id."'>";      
  }

 echo"<fieldset  style='width:400px;'>";    
 echo '<legend>MAIL BODY</legend>' ;           
echo '<table width="700px" ><tr><td>enq_type</td><td><select name="et" id="bt">';
       $sql1="SELECT enqtypeid,enquirytype FROM bio_enquirytypes";
      $mas1=DB_query($sql1,$db);   
      while($row1=DB_fetch_array($mas1))
{         
    if($row1['enqtypeid']==$et)
    {
       echo "<option selected value=".$row1['enqtypeid'].">".$row1['enquirytype']."</option>";  
    }    else
    {
        echo "<option value=".$row1['enqtypeid'].">".$row1['enquirytype']."</option>";    
    }
}
   
echo "</td></tr>";
echo '<tr><td>body_type</td><td><input type="text" name="bt" id="bt" value='.$bt.'></td></tr>';
echo '<tr><td>message</td><td><textarea name="me" id="me"  rows=25 cols=116 >'.$me.'</textarea></td></tr>';
echo '<tr><td><input type="submit" name="sub"></td></tr></table></fieldset>';
$sql="SELECT * FROM bio_mailbody";
$mas=DB_query($sql,$db);
  echo"<fieldset  style='width:400px;'>"; 
 echo ' <div style="height: 200px; width: 100%; overflow: scroll;">';     
echo "<table border=1 width='680px'><tr><td>id</td>
<td>enq_type</td>
<td>body_type</td>
<td>message</td></tr>";
$i=1;
while($row=DB_fetch_array($mas))
 {
     $id=$row['id'];
     echo "<tr><td>.$i.</td>
     <td>".$row['enq_type']."</td>
     <td>".$row['body_type']."</td>
     <td>".$row['message']."</td>    
      <td><a href='#' id='$id' onclick='slt(this.id)'>select</a></td>
      <td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td></tr>";
      $i++;
 }  
     echo '</table>'  ;
     echo '</div>';
     echo '</fieldset>';
     
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
</script>
