<?php
$PageSecurity = 80;  
include('includes/session.inc');   
  $id=$_GET['category'];
  $type=$_GET['type']; 
 $title1=$_GET['title'];
if(isset($_GET['category'])){
    echo"<table style='width:100%'>"; 
     
     if($type==5 && $id==1) {
        

 
echo"<tr><td >Complaint Title</td>";
echo"<td><input type='text' name='title' id='title' value='".$title1."' style='width:54%'></td></tr>"; 


echo '<tr><td>Complaint Description</td><td><textarea name="description" id="description" rows="5" cols="46">'.$description.'</textarea> </td></tr>';

 echo '<tr><td >Priority</td>'; 
echo '<td><select name="priority" id="priority" style="width:225px" onchange="addPriority(this.value)">';
$sql1="select * from bio_priority";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$priority) 
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
    echo $row1['id'].'">'.$row1['priority'];
    echo '</option>';

}    
     echo '</select></td></tr>';
     
 
      echo '<tr><td>Status</td>';
echo '<td><select name="status" id="status" style="width:225px">';
$sql1="select * from bio_incidentstatus";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$status) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="'; 
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['status'];
    echo '</option>';

}    
     echo '</select></td></tr>';     
        
   //echo"</table>";   
    
}   
if(($type==1 && $id==1) || ($type==1 && $id==2) || ($type==1 && $id==3) || 
   ($type==1 && $id==4) || ($type==1 && $id==5)  || ($type==1 && $id==6))
 {
       
  echo"<tr><td width=30%>Email Subject</td>";
echo"<td><input type='text' name='title' id='title' value='".$title1."' style='width:220px'></td></tr>"; 

    echo '<tr><td>Remarks</td><td><textarea name="description" id="description" rows="5" cols="45">'.$description.'</textarea> </td></tr>';
    
    
   echo '<tr><td>Output Type</td>';
//    echo'<tr><td colspan="2"><table border=0><tr>';
    $sql_out="SELECT * FROM bio_outputtypes";
    $result_out=DB_query($sql_out, $db);
    $j=1;
    echo'<td><table><tr>';
    while($mysql_out=DB_fetch_array($result_out)){
        
    echo'<td><input type="checkbox" id="outputtype"'.$j.' name="outputtype[]" tabindex=23 value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
        $j++;    
            
        if( ($j%2)-1==0 ){
            echo'</tr><tr>';
        }
               
    } 
    echo"</tr>"; 
   echo"<input type='hidden' name='houttype' id='houttype' value='$j'>"; 

   
    }
 if(($type==2 && $id==1) || ($type==2 && $id==2) || ($type==2 && $id==3) || ($type==2 && $id==4)  || 
 ($type==3 && $id==1) || ($type==3 && $id==2))
 
   {  
  
   echo"<tr><td width=35%>Email Subject</td>";
echo"<td><input type='text' name='title' id='title' value='".$title1."' style='width:65%'></td></tr>";  
 echo '<tr><td>Description</td><td><textarea name="description" id="description" rows="11" cols="50">'.$description.'</textarea> </td></tr>';
 
  
     
   
  }
   
      echo"</table>";   
  }

?>
