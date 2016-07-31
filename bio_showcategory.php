<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
if($_GET['category']){
         echo "<td>Sub Category</td><td>";
   $cata_id=$_GET['category'];

        $sql="SELECT * FROM bio_submailcategory WHERE main_catid=$cata_id";
    $result1=DB_query($sql,$db);
   
    echo '<select name="submailcategory" id="submailcategory" onchange="showmailtype(this.value)" style="width:200px">';

 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['sub_catid']==$priority) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['sub_catid'].'">'.$row1['emailcategory'];
    echo '</option>';

}    
     echo '</select>';         
        echo'</td>';    
    
  
  
}


  
?>
