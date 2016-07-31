<?php

/* $Id Feedstocks.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Mail body');
include('includes/header.inc');
echo '<a href="index.php">Back to Home</a>';  

 

 if(isset($_GET['delete']))
  { 
      $did=$_GET['delete'];    
      $sql= "DELETE FROM `bio_mailbody` WHERE  `id`= $did";
     $result=DB_query($sql,$db); 
}
if(isset($_GET['edit'])){
       $eid=$_GET['edit']; 
$sql="SELECT `id`, `body_type`, `message`,enq_type FROM `bio_mailbody` WHERE `id` = $eid";
$result=DB_query($sql,$db);
$myrow2=DB_fetch_array($result);
   $enqtype=$myrow2['enq_type'];
//$eid=$myrow2['doc_no'];  
$type=$myrow2['body_type']; 
$doc_description=$myrow2['message'];

//$category= $myrow2['category'];

} 
if (isset($_POST['update']))
{
  $sql = "update `bio_mailbody` set `body_type`='" . $_POST['mailtype'] . "', `message`=  '" . $_POST['description'] . " ',
enq_type='" . $_POST['enq'] . "' where id='".$_POST['eid']."' ";                                           
        $result = DB_query($sql,$db);

}
if (isset($_POST['submit']))
{
    
 $sql = "INSERT INTO `bio_mailbody`(`body_type`, `message`,enq_type) VALUES ('" . $_POST['mailtype'] . "',
                                          '" . $_POST['description'] . "' , '" . $_POST['enq'] . "')";                                           
        $result = DB_query($sql,$db);
        
  
       
}
      echo '<table ><tr><td>';
echo '<fieldset style="height:400px">';
echo '<legend><b>Mail Body</b></legend>';

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo '<br><br><table class="selection">';
    
  echo '<tr><td>Enquiry Type</td><td ><select name="enq" id="enq" style="width:100px" >';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$enqtype)
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
           echo '</option>';  
    }

echo '</select></td>'; 

 echo '<tr><td>Type of Body</td><td><input type=text  name="mailtype" id="mailtype" value='.$type.' ></td></tr>';//style="text-transform:capitalize;width:220px"

 
 echo '<tr><td>Body Description</td><td><textarea name="description" id="description" rows=14 cols=100>'.$doc_description.'</textarea> </td></tr>';
 

 if(isset($_GET['edit']))
 {
  echo'<tr><td></td>
  <td><input type="Submit" name="update" value="' . _('Update') . '" onclick=" if(validate()==1)return false">
  <input type="Submit" name="refresh" value="' . _('Refresh') . '" ></td>
 
  </tr>'; 
 echo'<input type="hidden" name="eid" id="eid" value='.$_GET['edit'].'>'; 
 }
 else {
      echo'<tr><td></td>
      <td><input type="Submit" name="submit" value="' . _('Save') . '" onclick=" if(validate()==1)return false">
      <input type="Submit" name="refresh" value="' . _('Refresh') . '" ></td>
      </tr>'; 
 }
      echo '</table>';
      echo '</form></fieldset>';

      
             
      
      echo "<fieldset style='width:700px'>";
      echo "<legend><h3>Mail Body list</h3></legend>";
      echo "<div style='overflow:scroll;height:400px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr>  <th>' . _('Slno') . '</th>
                <th>' . _('Enquiry Type') . '</th>
                <th>' . _('Mail Body Type') . '</th>
                <th>' . _ ('Mail Body Description') . '</th> 
      
               </tr>';
               
  $sql1="SELECT `id`, `body_type`, `message`,bio_enquirytypes.enquirytype FROM `bio_mailbody` 
  inner join bio_enquirytypes on ( bio_enquirytypes.enqtypeid = bio_mailbody.enq_type)
  
 ";
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
   //$eid=$row['doc_no']; 
   $type=$row1['body_type'];
   $enqtype=$row1['enquirytype'];
 $doc_category=$row1['message'];
 

        

                                    echo"<td height='100'>$slno</td>
                                    <td height='100'>$enqtype </td> 
                                    <td height='100'>$type </td> 
                                  
                                    <td ><div style='height:100px; overflow:auto'>$doc_category</div></td>
                                    <td height='100'><a href='#' id='$row1[id]' onclick='edit(this.id)'>Edit</a></td>
                                    
                                    <td height='100'><a href='#' id='$row1[id]' onclick='dlt(this.id)'>Delete</a></td>      
                                    
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
if(f==0){f=common_error('enq','Please Select the Enquiry type');  if(f==1){return f; }  }
if(f==0){f=common_error('mailtype','Please enter the mail type');  if(f==1){return f; }  }
if(f==0){f=common_error('description','Please enter the Description');  if(f==1){return f; }  }  


}</Script>


  <?php
 echo '</td></tr></table>'; 
  
//include('includes/footer.inc');   
?>
