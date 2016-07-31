<?php
    $PageSecurity = 80;
  include('includes/session.inc');
  
  if(isset($_GET['inst']))
  {
      
     $inst=$_GET['inst'];
     $feed=$_GET['feed'];
     
      $sql_sub1="INSERT INTO bio_tempfeedstock(instid,feedid) VALUES ('".$inst."','".$feed."')";
      DB_query($sql_sub1,$db);
      
      echo"<br />";
      echo"<table  style='width:65%;' border=0>";
      echo"<tr style='background:#D50000;color:white'>
                                              <td>Slno</td>
                                              <td>Institution</td>
                                              <td>Feedstock</td>
                                            </tr>";
                                            
                                            
                            
      $sql_tempselect="SELECT bio_tempfeedstock.temp_id,
                              bio_institution.institution_name,
                              bio_fssources.source
                       FROM   bio_tempfeedstock,bio_fssources,bio_institution
                       WHERE  bio_institution.inst_id=bio_tempfeedstock.instid
                       AND    bio_fssources.id=bio_tempfeedstock.feedid";
                       
      $result_tempselect=DB_query($sql_tempselect,$db); 
      $no=1;        
               
      while($myrow_tempselect=DB_fetch_array($result_tempselect))
      {
            echo "<tr style='background:#000080;color:white'>
                        <td>$no</td>
                        <td>".$myrow_tempselect['institution_name']."<input type='hidden' id='subid' value='".$myrow_tempselect['institution_name']."'></td>
                        <td>".$myrow_tempselect['source']."<input type='hidden' id='number' value='".$myrow_tempselect['source']."'></td>
                        <td><a style='cursor:pointer;color:white;' id='".$myrow_tempselect['temp_id']."' onclick='editfeed(this.id)'>Edit</a ></td>
                        <td><a style='cursor:pointer;color:white' id='".$myrow_tempselect['temp_id']."' onclick='deletfeed(this.id)'>Delete</a></td></tr>";
            $no++;
      }
      echo"</table>"; 
 
     
      echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";
     }   
      
      
      if(isset($_GET['tempid']) || $_GET['tempid']!="")   
      {
           
          $sql_displayedit="SELECT bio_institution.institution_name  
                            FROM   bio_tempfeedstock,bio_institution
                            WHERE  bio_tempfeedstock.instid=bio_tempfeedstock.instid
                            AND    bio_tempfeedstock.temp_id='".$_GET['tempid']."'";
                                   
          $result_displayedit=DB_query($sql_displayedit,$db);     
          $row_displayedit=DB_fetch_array($result_displayedit);
          echo"<td>Edit</td> 
               <td colspan='2'><input type='hidden' id='instname' name='instname' value='".$row_displayedit['institution_name']."'>".$row_displayedit['institution_name']."</td>"; 
                               
         
      }
      
      
      
      
      
       
      
      
  
  
?>
