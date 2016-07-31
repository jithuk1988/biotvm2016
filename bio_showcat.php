<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
if($_GET['category']){
             
   $cata_id=$_GET['category'];
   
   $sql="SELECT * FROM bio_submailcategory WHERE main_catid=$cata_id";
   $result1=DB_query($sql,$db);

   echo '<td>Sub Category<select name="submailcategory" id="submailcategory" onchange="showmailtype(this.value)" style="width:200px">';

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
     echo '</select></td>';         

    
 
 
 if($cata_id==1)
 {
 

    echo "<td>Email status";              
    $sql="SELECT * FROM bio_emailstatus";
    $result=DB_query($sql,$db);
  
echo '<select name="mailstatus" id="mailstatus" style="width:200px">';

 $f=0;    
while($row=DB_fetch_array($result))
{
    if ($row['id']==$_POST['mailstatus']) 
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
    echo $row['id'].'">'.$row['emailstatus'];
    echo '</option>';

}    
     echo '</select>';         
     echo'</td>';    
     
     
     echo "<td>Lead status";              
    $sql="SELECT * FROM bio_status ";
    $result=DB_query($sql,$db);
  
echo '<select name="leadstatus" id="leadstatus" style="width:200px">';

 $f=0;    
while($row=DB_fetch_array($result))
{
    if ($row['statusid']==$_POST['leadstatus']) 
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
    echo $row['statusid'].'">'.$row['biostatus'];
    echo '</option>';

}    
     echo '</select>';         
     echo'</td>';  

     
}



}
  
?>
