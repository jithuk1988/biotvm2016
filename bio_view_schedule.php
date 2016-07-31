<?php
  //echo "<h1 >hello </h1>";
  $PageSecurity = 80;
include('includes/session.inc');
$ticketnos=$_GET['ticketno'];
$title = _('View Task Scheduled');
include('includes/header.inc');   
echo" 
   </br><fieldset>
   <legend><b>Scheduled task </b></legend>
   <table style='border:1 class=selection width:80%' >
   <tr>
   <th>Customer Name</th>
   <th>Description</th>
   <th>Contact No</th>
   <th>Task Type</th>
   <th>Customer Type</th>
   <th>Warranty</th>
   <th>Chargable</th>
   <th>Schedule Date</th>
   <th>Assign Team</th>

     ";
/*$sqlr="SELECT bio_incidents.cust_id,bio_incident_cust.custname,bio_incident_cust.custphone,bio_incidents.ticketno,bio_cstask.type,
bio_cstask.scheduleddate,bio_cstask.taskdescription,bio_cstask.team
   FROM bio_incidents
   LEFT JOIN  bio_incident_cust ON bio_incidents.cust_id=bio_incident_cust.cust_id
   LEFT JOIN  bio_cstask ON bio_incidents.ticketno=bio_cstask.ticketno
   WHERE  bio_incidents.ticketno=$ticketnos";  */
 $sql="SELECT bio_cstask.orderno,
        bio_cstask.taskdescription,
        bio_cstask.cstype,
        bio_cstask.scheduleddate,
        bio_cstask.team,
        bio_incidents.cust_id,
        bio_incidents.cust_typ,
        bio_incidents.warr_type,
        bio_incidents.chargable,
        bio_incident_cust.custname,
        bio_incident_cust.custphone,  
        bio_incident_cust.landline   
    FROM bio_cstask
    LEFT JOIN bio_incidents ON bio_incidents.ticketno=bio_cstask.ticketno
    LEFT JOIN bio_incident_cust ON bio_incident_cust.cust_id=bio_incidents.cust_id
    WHERE bio_cstask.ticketno=".$ticketnos;
$result=DB_query($sql,$db);   

while($myrow2=DB_fetch_array($result))
   {    $conno=$myrow2['custphone']."<br>".$myrow2['landline'];  
   
           echo"<tr><td>".$myrow2['custname']."</td>"; 
            echo"<td>".$myrow2['taskdescription']."</td>";
             echo"<td>".$conno."</td>";         
              echo"<td>complaint</td>";    

      if($myrow2['cust_typ']==0){echo"<td></td>";}
      else if($myrow2['cust_typ']==1){echo"<td align='center'>Biotech</td>";}
        else {echo"<td align='center'>Non Biotech</td>";}
        //
      if($myrow2['warr_type']==0){echo"<td></td>";}
      else if($myrow2['warr_type']==1){echo"<td align='center'>Warranty</td>";}
        else if($myrow2['warr_type']==2) {echo"<td align='center'>AMC</td>"; }
        else {echo"<td align='center'>Other</td>";}
//
   if($myrow2['cust_typ']==0){echo"<td></td>";}
   else if($myrow2['chargable']==1){echo"<td align='center'>YES</td>";}
   else{echo"<td align='center'>NO</td>";}
   
 echo"  <td>".ConvertSQLDate($myrow2['scheduleddate'])."</td>";
 $sss="select teamname from bio_leadteams where bio_leadteams.teamid=".$myrow2['team'].""  ;
   $res=DB_query($sss,$db);
      $myroq=DB_fetch_array($res);
   echo"<td align=center>".$myroq['teamname']."</td>";

   }
   
   echo "</table>"  ;
?>
