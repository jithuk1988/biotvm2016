<?php

/* $Id Feedstocks.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Document List');
include('includes/header.inc');
echo '<a href="index.php">Back to Home</a>';  

 

 if(isset($_GET['delete']))
  { 
      $did=$_GET['delete'];    
      $sql= "delete from bio_documents where bio_documents.doc_no = $did";
     $result=DB_query($sql,$db); 
}
if(isset($_GET['edit'])){
       $eid=$_GET['edit']; 
$sql="SELECT bio_documents.doc_name,bio_documents.doc_description,bio_doc_category.category,bio_documents.doc_category FROM bio_documents, bio_doc_category  WHERE bio_documents.doc_no = $eid";
$result=DB_query($sql,$db);
$myrow2=DB_fetch_array($result);
//$eid=$myrow2['doc_no'];  
$doc_name=$myrow2['doc_name']; 
$doc_description=$myrow2['doc_description'];
$doc_category=$myrow2['doc_category'];
$category= $myrow2['category'];
}

   
if (isset($_POST['submit'])){
 $sql = "INSERT INTO bio_documents( doc_name,
                                               doc_description,
                                               doc_category)
                                  VALUES ('" . $_POST['docname'] . "',
                                          '" . $_POST['description'] . "',
                                          '" . $_POST['category'] . "')";                                           
        $result = DB_query($sql,$db);
        
      $doc_name=$_POST['docname']; 
       $doc_description= $_POST['description'];
       unset($doc_name);
       unset($doc_description); 
       
       
}
      echo '<table style=width:25%><tr><td>';
echo '<fieldset style="height:250px">';
echo '<legend><b>Document List</b></legend>';

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo '<br><br><table class="selection">';
 
 echo '<tr><td>Document Name</td><td><input type="text" name="docname" id="docname" size="30px" value='.$doc_name.'></td></tr>';
 
 echo '<tr><td>Description</td><td><textarea name="description" id="description" rows="3" cols="27">'.$doc_description.'</textarea> </td></tr>';
 
    echo '<tr><td>Category</td>';
echo '<td><select name="category" id="category">';
echo '<option value="0">-SELECT-</option>';
$sql1="select * from bio_doc_category";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{  
    if($category!='')
    {
        echo '<option selected  value="';
        echo  $doc_category. '">'.$category;
    echo '</option>'; 
    }
    echo '<option  value="';
        echo $row1['doc_catid'] . '">'.$row1['category'];
    echo '</option>';
        
       

}

echo '</select></td></tr>';


 
   
  echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Save') . '" onclick=" if(validate()==1)return false"></td></tr>'; 
      echo '</table>';
      echo '</form></fieldset>';

      
             
      
      echo "<fieldset style='width:560px'>";
      echo "<legend><h3>Document List</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr>  <th>' . _('Slno') . '</th>  
                <th>' . _('Document Name') . '</th>

           <th>' . _ ('Category') . '</th> 
      
               </tr>';
               
  $sql1="SELECT 
  bio_documents.doc_no, 
  bio_documents.doc_name,
  bio_doc_category.category  
  FROM 
  bio_documents,
  bio_doc_category 
  where
   bio_documents.doc_category=bio_doc_category.doc_catid";
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
   $eid=$row['doc_no']; 
   $doc_name=$row1['doc_name'];
//   $doc_description=$row1['doc_description'];
   
 //   $sql2="select * from bio_doc_category where bio_doc_category.doc_catid=".$row1['doc_catid']."";
//  $result2=DB_query($sql2,$db);
//  $row2=DB_fetch_array($result2);
  $doc_category=$row1['doc_category'];
//    echo  $doc_cat=$row2['doc_catid'];
        

echo"<tr style='background:#A8A4DB'><td>$slno</td>
                                    <td>$doc_name </td> 
                                  
                                    <td>$doc_category</td>
                                    <td><a href='#' id='$row1[doc_no]' onclick='edit(this.id)'>Edit</a></td>
                                    
                                    <td><a href='#' id='$row1[doc_no]' onclick='dlt(this.id)'>Delete</a></td>      
                                    
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
if(f==0){f=common_error('docname','Please enter the Document Name');  if(f==1){return f; }  }
if(f==0){f=common_error('description','Please enter the Description');  if(f==1){return f; }  }  
if(f==0){f=common_error('category','Please select a Category');  if(f==1){return f; }  }  

}</Script>


  <?php
 echo '</td></tr></table>'; 
  
//include('includes/footer.inc');   
?>