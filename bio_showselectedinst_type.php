<?php
 $PageSecurity = 80;
include('includes/session.inc');
 $id=$_GET['inst'];
 
  $sql="SELECT COUNT(*) FROM bio_institution_temp
                        WHERE institution_id=$id";
  $result = DB_query($sql,$db);
  $row=DB_fetch_array($result);
  if($row[0]==0){
   $sql_temp="INSERT INTO bio_institution_temp(institution_id)  
                          VALUES ('" .$id. "')";                                              
 $result = DB_query($sql_temp,$db);   
  } 
 
    
  
    
  $sql_inst="SELECT bio_institution.institution_name,
                    bio_institution_temp.institution_id
               FROM bio_institution,bio_institution_temp
              WHERE bio_institution.inst_id=bio_institution_temp.institution_id";        
    
  $result_inst=DB_query($sql_inst,$db);  
  while($row1=DB_fetch_array($result_inst) )    
  {
  $inst_name=$inst_name.",".$row1['institution_name'];
  
 $inst_id1=$row1['institution_id'].",".$inst_id1;
  
  } 
 $inst_name1 = substr($inst_name,1);
$inst_id = substr($inst_id1,1);                                        
  
echo "<tr><td>Institution type for registration</td>"; 
echo "<td><textarea rows=2 cols=31   name='MultipleInst' id='multipleinst' value=".$inst_id." style=resize:none;>$inst_name1</textarea></td></tr>"; 
 
echo"<input type='hidden' name='Institute' id='institute' value='$inst_id'>";                 

?>
