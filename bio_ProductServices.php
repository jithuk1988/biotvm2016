<?php

/* $Id Feedstocks.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Product Services');
include('includes/header.inc');
echo '<a href="index.php">Back to Home</a>'; // 

 

 if(isset($_GET['delete']))
  { 
      $did=$_GET['delete'];    
      $sql= "DELETE FROM `bio_productservices` WHERE  `id`= $did";
     $result=DB_query($sql,$db); 
}
if(isset($_GET['edit'])){
       $eid=$_GET['edit']; 
$sql="SELECT `id`, `enquiry_type`, `productservices` FROM `bio_productservices` WHERE bio_productservices.id = $eid";
$result=DB_query($sql,$db);
$myrow2=DB_fetch_array($result);
//$eid=$myrow2['doc_no'];  
$type=$myrow2['enquiry_type']; 
$doc_description=$myrow2['productservices'];

$category= $myrow2['category'];
}

 if (isset($_POST['update']))
{
  $sql = "update `bio_productservices` set `enquiry_type`='" . $_POST['type'] . "', 
            `productservices`=  '" . $_POST['description'] . " ' where id='".$_POST['eid']."' ";                                           
        $result = DB_query($sql,$db);

}
 
   
if (isset($_POST['submit'])){
 $sql = "INSERT INTO bio_productservices( enquiry_type,
                                               productservices)
                                  VALUES ('" . $_POST['type'] . "',
                                          '" . $_POST['description'] . "')";                                           
        $result = DB_query($sql,$db);
        
  
       
}
      echo '<table style=width:25%><tr><td>';
echo '<fieldset style="height:250px">';
echo '<legend><b>Product Services</b></legend>';

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo '<br><br><table class="selection">';
 

 echo '<tr><td> Customer Type</td><td>
 <select name="type" id="type" style="width:190px" tabindex=30>';
 $sql1="SELECT * FROM bio_enquirytypes where enqtypeid in (1,2,3,8,7,11,12)"; 
          $result1=DB_query($sql1,$db);
          $f=0;
   
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$_POST['type'] or $myrow1['enqtypeid']==$type) 
    {
    echo '<option selected value="';
    } else {
    if ($f==0) 
    {
    echo '<option>';
    }
    echo '<option value="';
    $f++;
    }
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }  
 echo'</select></td></tr>';

 
 echo '<tr><td>Description</td><td><textarea name="description" id="description" rows="3" cols="27">'.$doc_description.'</textarea> </td></tr>';
 

 
 
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

      
             
      
      echo "<fieldset style='width:560px'>";
      echo "<legend><h3>Product Services list</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr>  <th>' . _('Slno') . '</th>  
                <th>' . _('Product Type') . '</th>
                <th>' . _ ('Product Description') . '</th> 
      
               </tr>';
               
  $sql1="SELECT bio_productservices.id,`enquiry_type`,
            bio_enquirytypes.enquirytype,`productservices` FROM `bio_productservices`,bio_enquirytypes 
  where enqtypeid=enquiry_type
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
   $type=$row1['enquirytype'];
 
//   $doc_description=$row1['doc_description'];
   
 //   $sql2="select * from bio_doc_category where bio_doc_category.doc_catid=".$row1['doc_catid']."";
//  $result2=DB_query($sql2,$db);
//  $row2=DB_fetch_array($result2);
  $doc_category=$row1['productservices'];
//    echo  $doc_cat=$row2['doc_catid'];
        

                                    echo"<td>$slno</td>
                                    <td>$type </td> 
                                  
                                    <td>$doc_category</td>
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
if(f==0){f=common_error('type','Please Select the Product type');  if(f==1){return f; }  }
if(f==0){f=common_error('description','Please enter the Description');  if(f==1){return f; }  }  


}</Script>


  <?php
 echo '</td></tr></table>'; 
  
//include('includes/footer.inc');   
?>