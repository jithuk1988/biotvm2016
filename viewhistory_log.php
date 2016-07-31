<?php
 $order=$_GET['orderid'];
$PageSecurity = 80; 
include('includes/session.inc'); 
$title = _('Installation Status');  
include('includes/header.inc');
echo "<fieldset style='width:90%;'>";     
     echo "<legend><h3>View History</h3>";
     echo "</legend>"; 
    
          
     echo "<table style='border:1px solid ;width:90%;'>";
     echo "<tr>
        <th>Slno</th>
        <th>Ticket No</th>
        <th>Description</th> 
        <th>Remark</th>
        <th>Incident Type</th>
        <th>complaint</th> 
        <th>complaint Date</th> 
        <th>Close Date</th>
        <th>Priority</th>  
        <th>Status</th> 
        
     
   </tr>  ";         


    
    $sql="SELECT bio_incidenttype.type,
            bio_incidentstatus.status,
            bio_incidents.description,
            bio_incidents.createdon,
            bio_incidents.closeDate,
            bio_priority.priority,
            bio_incidentstatus.status, 
            bio_incidents.ticketno,
            bio_incidents.remarks,
            bio_complainttypes.complaint,
            bio_incident_cust.custname,
            bio_incident_cust.custphone,
            bio_incident_cust.landline 
     FROM bio_incidents 
        LEFT JOIN bio_incidenttype
            ON (bio_incidents.type=bio_incidenttype.id)
        LEFT JOIN bio_complainttypes
            ON (bio_incidents.enqtypeid=bio_complainttypes.enqtypeid AND bio_incidents.title=bio_complainttypes.id  )
        LEFT JOIN bio_priority
            ON (bio_incidents.priority=bio_priority.id)
        LEFT JOIN bio_incidentstatus
            ON (bio_incidents.status=bio_incidentstatus.id)
        LEFT JOIN bio_incident_cust
            ON (bio_incident_cust.cust_id=bio_incidents.cust_id)
     WHERE bio_incidents.orderno=$order";
      $result_history=DB_query($sql,$db);
      
      
    $slno=0;  

 while($row=DB_fetch_array($result_history))
 { 
      $name=$row['custname'];
    
             $conno=$row['custphone'].",&nbsp;".$row['landline'];  
              if ($k==1){
             echo '<tr class="EvenTableRows">';
             $k=0;
         } else {
             echo '<tr class="OddTableRows">';
             $k=1;
         }                           
    $slno++; 
    if($row['closeDate']!=NULL || $row['closeDate']!=0)
    {
          $closee= ConvertSQLDate($row['closeDate']) ;  
    }
    
   echo"<td>".$slno."</td>"; 
   echo"<td>".$row['ticketno']."</td>";
   echo"<td>".$row['description']."</td>";
   echo"<td>".$row['remarks']."</td>";
   echo"<td>".$row['type']."</td>";    
   echo"<td>".$row['complaint']."</td>"; 
  echo"<td>".ConvertSQLDate($row['createdon'])."</td>"; 
   echo"<td>".$closee."</td>"; 
   echo"<td>".$row['priority']."</td>";  
   echo"<td>".$row['status']."</td>";   
 
   echo"</tr>";
   
 }
    echo '<table><td><b> Name :</b>'.$name.'&nbsp;&nbsp;</td>';
        echo "<td><b> Phone NO : </b>".$conno;
    echo '</table>' ;     
 echo "</table>";            
 echo "</fieldset>";
?>
