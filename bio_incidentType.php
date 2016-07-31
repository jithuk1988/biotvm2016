<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Incident Type');
include('includes/header.inc');
echo '<a href="index.php">Back to Home</a>';  

 

 if(isset($_GET['delete']))
  { 
      $did=$_GET['delete'];    
      $sql= "delete from bio_incidenttype where bio_incidenttype.id= $did";
     $result=DB_query($sql,$db); 
}
if(isset($_GET['edit'])){
       $eid=$_GET['edit']; 
$sql="SELECT * FROM bio_incidentType  WHERE bio_incidenttype.id = $eid";
$result=DB_query($sql,$db);
$myrow2=DB_fetch_array($result);

$type=$myrow2['type']; 
$description=$myrow2['description'];

}

   
if (isset($_POST['submit'])){
   echo $_POST['SelectedType'];
    
    if ($_POST['SelectedType']!=""){
     $sql = "UPDATE bio_incidenttype
                    SET 
                type='".$_POST['type']."' ,description='".$_POST['description']."'
            WHERE id =" .$_POST['SelectedType'];
     $result=DB_query($sql,$db);
 }
 else {
 $sql = "INSERT INTO bio_incidenttype(type,
                                               description)
                                  VALUES ('" . $_POST['type'] . "',
                                          '" . $_POST['description'] . "')";                                           
        $result = DB_query($sql,$db);
        
       $type=$_POST['type']; 
       $description= $_POST['description'];
       unset($type);
       unset($description); 
       
       
}   }
      echo '<table style=width:25%><tr><td>';
echo '<fieldset style="height:250px">';
echo '<legend><b>Incident Type</b></legend>';

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo '<br><br><table class="selection">';
                                                            
 echo '<tr><td>Incident Name</td><td><input type="text" name="type" id="type" size="30px" value="'.$type.'"></td></tr>';
 
 echo '<tr><td>Description</td><td><textarea name="description" id="description" rows="3" cols="27">'.$description.'</textarea> </td></tr>';
 


        echo '<tr><td><input type="hidden" name="SelectedType" value='.$eid.'>&nbsp;</td></tr>'; 
 
   
  echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Save') . '" onclick=" if(validate()==1)return false"></td></tr>'; 
      echo '</table>';
      echo '</form></fieldset>';

      
             
      
      echo "<fieldset style='width:560px'>";
      echo "<legend><h3>Incident Type</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr>  <th>' . _('Slno') . '</th>  
           <th>' . _('Incident Name') . '</th>
           </tr>';
               
  $sql1="SELECT * FROM 
  bio_incidenttype";
  $result1=DB_query($sql1, $db);  
 $k=0 ;$slno=0; 
  while($row1=DB_fetch_array($result1) )
  
  { 
          if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  
      $slno++;
      $id=$row1['id'];
   $type=$row1['type'];
  $description=$row1['description'];

        

echo"<tr style='background:#A8A4DB'><td>$slno</td>
                                    <td>$type </td> 
                                    <td><a href='#' id='$row1[id]' onclick='edit(this.id)'>Edit</a></td>
                                    
                                    <td><a href='#' id='$row1[id]' onclick='dlt(this.id)'>Delete</a></td>      
                                    
                                    </tr>";     

} 
?>


      
<script>

function dlt(str){
        //alert(str);
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
if(f==0){f=common_error('type','Please enter the Incident Name');  if(f==1){return f; }  }
if(f==0){f=common_error('description','Please enter the Description');  if(f==1){return f; }  }  

}</Script>


  <?php
 echo '</td></tr></table>'; 
  
//include('includes/footer.inc');   
?>