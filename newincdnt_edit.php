<?php

/* $Id: CustomerInquiry.php 4572 2011-05-23 10:14:06Z daintree $*/
$PageSecurity = 80;
include('includes/SQL_CommonFunctions.inc');
include('includes/session.inc');
$title = _('Update_Incident');
include('includes/header.inc');

  $tcktno =$_GET['Incdnt'];
  if (isset($_POST['submit'])){
     $tcktno =$_POST['ticket']; 
      $createdate=date("d/m/Y"); 
     
 $description1=$_POST['description'].", "."$createdate " .$_POST['comment'];   
  echo   $status1=$_POST['status'];   
            if($status1==3)
            {
                $closeDate=date("Y-m-d"); 
                         if($_POST['closedate']!="")
                         {
                $saveclose=FormatDateForSQL($_POST['closedate']); 
                         }
                         else 
                         {
                           $saveclose=""; 
                         }
                
            }
               else
            {
                $closeDate='0000-00-00'; 
                $saveclose="";
            }
            
            
                
 if($status1==5){
           
     $sql="SELECT * FROM bio_incidents WHERE ticketno =" .$_POST['ticket'];     
     $result=DB_query($sql,$db); 
     $myrow=DB_fetch_array($result); 
     $flag=$myrow['status_count'];      
      
      $flag++;    
      
       $sql = "UPDATE bio_incidents SET status_count=$flag  WHERE ticketno =" .$_POST['ticket'];     
        $result=DB_query($sql,$db);    
       } 
                
 $sql = "UPDATE bio_incidents
             SET 
                  
                  description='".$description1."',
                  priority='".$_POST['priority']."' ,
                                 status='".$status1."',closeDate='".$closeDate."'     
             WHERE ticketno =" .$tcktno;
             
     $result=DB_query($sql,$db);
     
     
if($status1==3)
{     
        echo  $sqlclose = "UPDATE bio_incidents  SET   manual_closedate='".$saveclose."'   WHERE ticketno =" .$_POST['ticket'];         
     $result=DB_query($sqlclose,$db);
    
 
 $sql_debtorno="SELECT bio_incident_cust.debtorno 
               FROM   bio_incident_cust,bio_incidents 
               WHERE  bio_incident_cust.cust_id=bio_incidents.cust_id 
               AND    bio_incidents.ticketno=".$_POST['ticket'];  
$result_debtorno=DB_query($sql_debtorno,$db);
$row_debtorno=DB_fetch_array($result_debtorno);

$debtorno=$row_debtorno['debtorno'];
if($debtorno!=null)
{
    $sql_orderno="SELECT bio_installation_status.orderno 
               FROM   salesorders,bio_installation_status
               WHERE  salesorders.orderno=bio_installation_status.orderno AND salesorders.debtorno='$debtorno'"; 
                
$result_orderno=DB_query($sql_orderno,$db);
$row_orderno=DB_fetch_array($result_orderno);
$orderno=$row_orderno['orderno'];
    
       $sql_plant_status=" UPDATE `bio_installation_status` SET `plant_status`=1 WHERE `orderno`=$orderno";
                
$result_plant_status=DB_query($sql_plant_status,$db);

}

$sql_oldorder="SELECT debtorno FROM bio_oldorders WHERE debtorno='".$row_debtorno['debtorno']."'";  
$result_oldorder=DB_query($sql_oldorder,$db);



if(DB_num_rows($result_oldorder)>0)
{
    $row_oldorder=DB_fetch_array($result_oldorder); 
    
    $sql_update="UPDATE bio_oldorders SET currentstatus=1 WHERE debtorno='".$row_oldorder['debtorno']."'";
    DB_query($sql_update,$db);
    
}             
                 
}  
?>
<script type="text/javascript">
   
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
       self.close();
</script>
  <?php  
     
 } 
  
// always figure out the SQL required from the inputs available
    $sql1="SELECT 
                   bio_incident_cust.cust_id ,
                   bio_incidents.ticketno,

CASE WHEN bio_incidents.debtorno not like '0' then custbranch.braddress1 
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.area1
end as 'area1', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.faxno
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.landline
end as 'landline', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.LSG_type
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.LSG_type
end as 'LSG_type', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.block_name
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.block_name
end as 'block_name', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.brname 
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.custname 
end as 'custname', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.phoneno 
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.custphone 
end as 'custphone',
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.email 
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.custmail 
end as 'custmail',
           
        
          
                   
                   bio_incident_cust.houseno,
                   bio_incident_cust.housename,
   
                   bio_incident_cust.area2 ,
                   bio_incident_cust.pin,
                   complaint_all.nationality,
                   complaint_all.state ,
                   complaint_all.district,
                   bio_incidents.cust_id,
                   bio_incidents.type,
                   bio_incidents.source,
                   bio_complainttypes.complaint,
                   bio_incidents.title,
                   bio_incidents.description,
                   bio_incidents.priority,
                   bio_incidents.status,
                   bio_incidenttype.type, bio_incidenttype.id,  bio_incidents.manual_closedate FROM      bio_incidents
         LEFT JOIN bio_incidenttype ON bio_incidents.type=bio_incidenttype.id
         LEFT JOIN bio_incident_cust ON bio_incidents.cust_id=bio_incident_cust.cust_id
    LEFT JOIN complaint_all ON complaint_all.ticketno = bio_incidents.ticketno
    LEFT JOIN custbranch ON custbranch.debtorno=bio_incidents.debtorno
    left join bio_complainttypes on bio_complainttypes.id=bio_incidents.title
         WHERE bio_incidents.ticketno=$tcktno";
  
  
  $result1=DB_query($sql1, $db);  
 while($row1=DB_fetch_array($result1) )      {  
      $ph= $row1['custphone'] ;
             if($ph=='')
             {
                 $ph=$row1['landline'] ;
             }
  $cust_id=$row1['cust_id'];
  $custname=$row1['custname'];
  $phno=$row1['custphone'];
  $mailid=$row1['custmail'];
  $type=$row1['type'];   
  $title1=$row1['title'];
  $complaint=$row1['complaint'];
  $description=$row1['description'];
  $priority=$row1['priority'];
  $status=$row1['status'];
  
  if($row1['manual_closedate']!=NULL){
      $closeddate= ConvertSQLDate($row1['manual_closedate']);
  }
  else{
      $closeddate="";    
  }  
 echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '" onsubmit="return closeSelf()">';
echo '<table style=width:550px><tr><td>';
echo '<fieldset style="width:500px">';
echo '<legend><b>Complaints</b></legend>';

echo"<table width=100%>";
echo"<tr><td >Ticket No:</td>";
echo"<td><input type='hidden' name='ticket' id='ticket' value='".$tcktno."'>$tcktno</td></tr>";
echo"<tr><td >Customer Name</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='".$custname."'>$custname</td></tr>";

echo"<tr><td >Phone No</td>";
echo"<td><input type='hidden' name='phno' id='phno' value='".$phno."'>$ph</td></tr>";

echo"<tr><td >Email id</td>";
echo"<td><input type='hidden' name='email' id='email' value='".$mailid."'>$mailid</td></tr>";


echo '<tr><td>Complaint Type</td>';
echo"<td><input type='hidden' name='title' id='title' value='".$type."'>$type</td></tr>"; 
echo"<tr><td >Complaint Title</td>";
echo"<td><input type='hidden' name='title' id='title' value='".$title1."'>$complaint</td></tr>"; 

echo '</select></td></tr>';

echo '<tr><td>Complaint Description</td><td><textarea name="description" id="description" rows="5" cols="50" readonly>'.$description.'</textarea> </td></tr>';
echo"<tr><td></td></tr>";  
echo '<tr><td>Add Comments</td><td><textarea name="comment" id="comment" rows="3" cols="50">'.$comment.'</textarea> </td></tr>';
 echo "<tr><td>Close Date</td><td><input type=text required class=date alt='d/m/Y' value='".$closeddate."' name='closedate' id='closedate' ></input> </td></tr>";
 echo '<tr><td>Priority</td>';                                                                        
echo '<td><select name="priority" id="priority" style="width:100px">';
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
echo '<td><select name="status" id="status" style="width:100px">';
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
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['status'];
    echo '</option>';

}    
     echo '</select></td></tr>';  echo"<tr><td></td></tr>"; 
     echo'<input type="hidden" name="SelectedType" value="'.$id.'">';
     echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Save') . '" ></td></tr>'; 
     
 }
echo"</table>";
echo"</fieldset>";  
echo '</td></tr>';                             
echo '</table></form>';


 
include('includes/footer.inc');
?>