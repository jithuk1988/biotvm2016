<?php
 $PageSecurity = 80;
include('includes/session.inc');
 $id=$_GET['plant'];
 
  $sql="SELECT COUNT(*) FROM bio_plant_temp
                        WHERE plant_id='$id'";
  $result = DB_query($sql,$db);
  $row=DB_fetch_array($result);
  if($row[0]==0){
   $sql_temp="INSERT INTO bio_plant_temp(plant_id)  
                          VALUES ('" .$id. "')";                                              
 $result = DB_query($sql_temp,$db);   
  } 
 
    
  
    
  $sql_plant="SELECT stockcategory.categorydescription,
                    bio_plant_temp.plant_id
               FROM stockcategory,bio_plant_temp
              WHERE stockcategory.categoryid=bio_plant_temp.plant_id";        
    
  $result_plant=DB_query($sql_plant,$db);  
  while($row1=DB_fetch_array($result_plant) )    
  {
  $plant_name=$plant_name.",".$row1['categorydescription'];
  
 $plant_id1=$row1['plant_id'].",".$plant_id1;
  
  } 
 $plant_name1 = substr($plant_name,1);
$categoryid = substr($plant_id1,1);                                        
  
echo "<tr><td>Plant type selected</td>"; 
echo "<td><textarea rows=2 cols=31   name='MultiplePlant' id='multipleplant' value=".$categoryid." style=resize:none;>$plant_name1</textarea></td></tr>"; 
 
echo"<input type='hidden' name='PlantSelection' id='plantselection' value='$categoryid'>";                 

?>
