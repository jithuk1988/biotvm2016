<?php
  $PageSecurity = 80;
include ('includes/session.inc');
$title = _('PRODUCTS');
include ('includes/header.inc');
include ('includes/SQL_CommonFunctions.inc');
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
if($_POST['sub'])

{
    $sourcename=$_POST['t1'];
 $remarks=$_POST['t2'];
    $error=0;
    if($_POST['t1']=="")
    {
       $error=1; 
    }
    
    
    if($_POST['t2']=="")
    {
       $error=1; 
    }
    
    if($error!=1)
    {
        if($_POST['sel'])
        {
            $id=$_POST['sel'];
          $sq3="UPDATE `bio_leadsources` SET `sourcename`='$sourcename',`remarks`='$remarks' WHERE `id`='$id'";
$mas3=DB_query($sq3,$db) ;  
        }else{
 $sq3="INSERT INTO  bio_leadsources( sourcetypeid, sourcename, officeid, benofficeid, costcentreid, teamid, remarks) VALUES (13,'$sourcename',1,'5,4,3,2,1,',21,1,'$remarks')";
$mas3=DB_query($sq3,$db) ;
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
 $sql1= "DELETE FROM `bio_leadsources` WHERE `id`=$id";
$sm1=DB_query($sql1,$db); 
}
  if(isset($_GET['select']))
  { 
    $id=$_GET['select'];
  $sql2= "SELECT sourcename, remarks FROM bio_leadsources WHERE id=$id";
$sm2=DB_query($sql2,$db);
$row2=DB_fetch_array($sm2);
$it=$row2['sourcename'];
$td= $row2['remarks'] ;
echo "<input type='hidden' name='sel' value='$id'>";


  }






echo"<fieldset  style='width:400px;'>";
echo"<legend><h3>Email Source</h3></legend>";

    echo "<table width='400px'>";echo"<tr style='background:#585858;color:white'>";

echo"<table><tr><td>Source Name</td>
<td><input type='text' name='t1' id='t1' value='".$it."'></td></tr>";
echo"<tr><td>Remarks</td>
<td><input type='text' name='t2' id='t2' value='".$td."'></td></tr>";
echo"<tr><td><input type='submit' name='sub' onclick='if(valid()==1){return false;}'></td></tr>";
echo "</table>";
echo"</fieldset>";

 echo"<fieldset  style='width:400px;'>";
echo"<legend><h3>Email Source</h3></legend>";
 echo ' <div style="height: 200px; width: 100%; overflow: scroll;">';
 $sq3=" SELECT * FROM bio_leadsources WHERE sourcetypeid =13 "; 
$mas3=DB_query($sq3,$db) ;
echo"<table width='400px' BORDER=1><tr>
<th>Slno</th>
<th>sourcename</th>
<th>remarks</th><th>Select</th><th>Delete</th></tr>";
$i=1;
while($row3=DB_fetch_array($mas3))
{
    $id=$row3['id'];
     echo "<tr><td>".$i."</td><td>".$row3['sourcename']."</td>
 
    <td>".$row3['remarks']."</td>
   
   
   
   <td><a href='#' id='$id' onclick='slt(this.id)'>select</a></td><td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td>

    
     </tr>";
    $i++;
}
echo"</table>";


?>

<script>

 function dlt(str2)
{

location.href="?delete=" +str2;         
}
function slt(str2) 
{
location.href="?select=" +str2;         
}
function valid()
{
   str=document.getElementById('t1').value;
    str1=document.getElementById('t2').value;
    
    
    
    if(str=="")
    {
        alert("please enter sourcename");
        return 1;
    }
    
     if(str1=="")
    {
        alert("please enter remark");
        return 1;
    }
     
  
    
}
</script>