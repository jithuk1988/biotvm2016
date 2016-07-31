<?php

$PageSecurity = 80;
include('includes/session.inc');
$title = _('Emails');     
include('includes/header.inc'); 

echo '<a href="index.php">Back to Home</a>';  

echo"<fieldset style='width:65%;'>";
echo"<legend><h3> Emails</h3></legend>";

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo"<table style='border:1px solid #F0F0F0;width:70%'>";


echo"<tr>";

echo"<td>Email<input type=text name=email id=email value=".$_POST['email']."></td> ";  
echo"<td width=40%>Select Category";

echo '<select name="bycategory" id="bycategory" style="width:190px" tabindex=7 onchange="showsubmailcategory(this.value)">';
     
     $sql="SELECT * FROM bio_mainemailcategory WHERE main_catid!=4";        $result=DB_query($sql,$db);   
 
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {
        if ($myrow1['main_catid']==$_POST['bycategory'])
        {
        echo '<option selected value="';
        } else
        {
        if ($f==0)
        {
  echo '<option>';
        }
        echo '<option value="';
        }
        echo $myrow1['main_catid'] . '">'.$myrow1['main_catname'];
        echo '</option>';
        $f++;
   }
   echo '</select></td>'; 
   
   echo"<td id='showcatagry' width=35%></td>"; 
   

   

echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
echo"</tr>";
echo"</table><br />"; 
      
            


if (isset($_POST['filterbut'])){    
 echo "<table class='selection' style='width:100%'>"; 
 
 if( $_POST['bycategory']=='' && $_POST['email']!='' )
 {
     
   echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _('Institution Name/ Customer Name') . '</th> <th>' . _('Contact No') . '</th> <th>' . _('Email Type') . '</th><th>' . _('Category') . '</th></tr>';   
     
     
   $sql="SELECT   bio_incident_cust.custname AS name,    
                  bio_incident_cust.landline AS landline, 
                  bio_incident_cust.custphone AS mobile,             
                  bio_incident_cust.custmail AS email,
                  bio_submailcategory.emailcategory,
                  bio_emailtypes.category
                  
           FROM   bio_incident_cust
      LEFT JOIN   bio_incidents 
             ON   bio_incident_cust.cust_id=bio_incidents.cust_id
           JOIN   bio_submailcategory
             ON   (bio_incidents.submailcategory=bio_submailcategory.sub_catid AND bio_incidents.mainmailcategory=bio_submailcategory.main_catid)
           JOIN   bio_emailtypes
             ON   bio_incidents.emailtype=bio_emailtypes.emailtype_id
      LEFT JOIN   bio_leads
             ON   bio_incidents.leadid=bio_leads.leadid
      LEFT JOIN   bio_cust    
             ON   bio_leads.leadid=bio_cust.cust_id    
          WHERE   bio_incident_cust.custmail='".$_POST['email']."'
 ";
           
     
 }
       
  if ($_POST['bycategory']==1 ){    
   
  echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _('Institution Name/ Customer Name') . '</th> <th>' . _('Contact No') . '</th>
              <th>' . _('Team') . '</th>   <th>' . _ ('Customer Type') . '</th> <th>' . _ ('Lead Date') . '</th> 
              <th>' . _ ('Email status') . '</th>  <th>' . _ ('Lead status') . '</th>  <th>' . _ ('Remarks') . '</th>           
        </tr>';


   $sql="SELECT bio_cust.cust_id,
             bio_cust.custname AS name,    
             bio_cust.custphone AS landline, 
             bio_cust.custmob AS mobile,             
             bio_cust.custmail AS email,
             bio_leads.remarks,
             bio_leads.leaddate,
             bio_leadteams.teamname,
             bio_submailcategory.emailcategory,
             bio_enquirytypes.enquirytype,
             bio_emailstatus.emailstatus,
             bio_status.biostatus        
               
        FROM   bio_cust,
               bio_leads,
               bio_leadteams, 
               bio_incidents,
               bio_submailcategory,
               bio_enquirytypes,
               bio_emailstatus,
               bio_status  
               
         WHERE bio_leadteams.teamid=bio_leads.teamid 
           AND bio_leads.leadid=bio_incidents.leadid
           AND bio_cust.cust_id=bio_leads.cust_id
           AND bio_enquirytypes.enqtypeid=bio_leads.enqtypeid 
           AND bio_emailstatus.id=bio_incidents.emailstatus    
           AND bio_status.statusid=bio_leads.leadstatus  
           AND bio_incidents.submailcategory=bio_submailcategory.sub_catid
           AND bio_incidents.mainmailcategory=bio_submailcategory.main_catid      
           AND bio_submailcategory.main_catid=".$_POST['bycategory']." ";
if($_POST['submailcategory']!=0){            
 $sql .= " AND bio_submailcategory.sub_catid=".$_POST['submailcategory']." ";  
}
if($_POST['email']!=''){
 $sql .= " AND bio_cust.custmail='".$_POST['email']."'";
}


}elseif ($_POST['bycategory']==2){ 

echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _('Customer Name') . '</th><th>' . _('Contact No') . '</th><th>' . _('Email Type') . '</th> <th>' . _ ('Category') . '</th></tr>';
  
  $sql="SELECT bio_incident_cust.cust_id,
               bio_incident_cust.custname AS name,
               bio_incident_cust.landline AS landline,
               bio_incident_cust.custphone AS mobile,                
               bio_businessassociates_enq.emailtype, 
               bio_submailcategory.emailcategory,
               bio_emailtypes.category            
          FROM 
               bio_businessassociates_enq,
               bio_incidents,
               bio_emailtypes, 
               bio_submailcategory,
               bio_incident_cust
               
         WHERE bio_incident_cust.cust_id=bio_incidents.cust_id  
           AND bio_businessassociates_enq.emailtype=bio_emailtypes.emailtype_id 
           AND bio_businessassociates_enq.buss_id=bio_incidents.buss_id           
           AND bio_incidents.submailcategory=bio_submailcategory.sub_catid
           AND bio_incidents.mainmailcategory=bio_submailcategory.main_catid 
           AND bio_submailcategory.main_catid=".$_POST['bycategory']."";
if($_POST['submailcategory']!=0){       
 $sql .= " AND bio_submailcategory.sub_catid=".$_POST['submailcategory']."    "; 
} 
if($_POST['email']!=''){
 $sql .= " AND bio_incident_cust.custmail='".$_POST['email']."'";
}

}elseif ($_POST['bycategory']==3){ 
                                      
  
echo '<tr> <th>' . _('Slno') . '</th> <th>' . _('Customer Name') . '</th> <th>' . _('Contact No') . '</th><th>' . _('Email Type') . '</th>  <th>' . _ ('Category') . '</th></tr>';

  $sql="SELECT bio_incident_cust.cust_id,
               bio_incident_cust.landline AS landline,
               bio_incident_cust.custphone AS mobile,
               bio_incident_cust.custname AS name, 
               bio_internship_jobs.emailtype, 
               bio_submailcategory.emailcategory,
               bio_emailtypes.category, 
               bio_internship_jobs.enq_id
               
          FROM bio_internship_jobs,
               bio_incidents,
               bio_emailtypes, 
               bio_submailcategory,
               bio_incident_cust
               
         WHERE bio_incident_cust.cust_id=bio_incidents.cust_id 
           AND bio_internship_jobs.emailtype=bio_emailtypes.emailtype_id 
           AND bio_internship_jobs.enq_id =bio_incidents.enq_id 
           AND bio_incidents.submailcategory=bio_submailcategory.sub_catid
           AND bio_incidents.mainmailcategory=bio_submailcategory.main_catid
           AND bio_submailcategory.main_catid=".$_POST['bycategory']."";
if($_POST['submailcategory']!=0){            
 $sql .= "  AND bio_submailcategory.sub_catid=".$_POST['submailcategory']."";
}
if($_POST['email']!=''){
 $sql .= " AND bio_incident_cust.custmail='".$_POST['email']."'";
}
           
}elseif ($_POST['bycategory']==5){          
                       
echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _('Ticket No') . '</th> <th>' . _('Customer Name') . '</th>
            <th>' . _('Date') . '</th> <th>' . _ ('Type') . '</th>   
            <th>' . _ ('Priority') . '</th> <th>' . _ ('Team Handling') . '</th>
            <th>' . _('Status') . '</th>       
      </tr>';
      
      
       $sql="SELECT bio_incident_cust.cust_id,
                    bio_incident_cust.custname AS name,
                    bio_incident_cust.landline AS landline, 
                    bio_incident_cust.custphone AS mobile,
                    bio_incident_cust.custmail AS email,                     
                    bio_incidents.ticketno,  
                    bio_priority.priority,
                    bio_incidenttype.type AS intype,
                    bio_leadteams.teamname,  
                    bio_incidentstatus.status,                
                    bio_incidents.createdon
                    
        FROM  bio_incident_cust,bio_incidents,bio_priority,bio_incidenttype,bio_incidentstatus,bio_leadteams,bio_submailcategory 
        
              WHERE bio_incident_cust.cust_id=bio_incidents.cust_id
                AND bio_incidents.priority=bio_priority.id
                AND bio_incidents.type=bio_incidenttype.id
                AND bio_incidents.handling_officer=bio_leadteams.teamid
                AND bio_incidents.status=bio_incidentstatus.id
                AND bio_incidents.mainmailcategory=5 
                AND bio_incidents.submailcategory=1
                AND bio_incidents.source=2
                AND bio_submailcategory.main_catid=".$_POST['bycategory']."";
if($_POST['submailcategory']!=0){                    
      $sql .= " AND bio_submailcategory.sub_catid=".$_POST['submailcategory']."";    
}  
if($_POST['email']!=''){
      $sql .= " AND bio_incident_cust.custmail='".$_POST['email']."'";
}    
}      
      
      
       
//echo $sql;           
$result=DB_query($sql,$db); 

$k=0 ; $slno=0; 

while( $myrow=DB_fetch_array($result) )   {

    $slno++;      
    
    $custname=$myrow['name']; 
    
    if($myrow['landline']=="") {
    $custmob=$myrow['mobile'];
    }else{                       
    $custmob=$myrow['landline'];    
    }
    
    
    if($_POST['bycategory']==1)
    {  

        $teamname=$myrow['teamname'];  
        $remarks=$myrow['remarks'];      
        $enqtype=$myrow['enquirytype'];          
        $leaddate=$myrow['leaddate'];      
        $emailstatus=$myrow['emailstatus'];       
        $leadstatus=$myrow['biostatus']; 
    }
    
    
    if(($_POST['bycategory']==2) || $_POST['bycategory']==3 || $_POST['email']!='') 
    {  
        $category=$myrow['category']; 
        $emailtype=$myrow['emailcategory'];  
    }
    
    
    
    
    if($_POST['bycategory']==5)
    {    
        $ticketno=$myrow['ticketno'];   
        $dat= ConvertSQLDate($myrow['createdon']); 
        $incidenttype=$myrow['intype']; 
        $priority=$myrow['priority'];    
        $handlingteam=$myrow['teamname']; 
        $status=$myrow['status'];                        
    }    
         
    
        

  if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
  } else {
        echo '<tr class="OddTableRows">';
        $k=1;
  }   


                                   echo "<td width=4%'>$slno</td>";     
                                    
if($_POST['bycategory']==5){       echo"<td width='10%'>$ticketno</td>";      
                                   }
                                  
                                   echo "<td width='15%'>$custname</td>";   
                                   
if($_POST['bycategory']==5){       echo"<td width='10%'>$dat</td>";                                        
                                   echo"<td width='10%'>$incidenttype</td>";        
                                   echo"<td width='10%'>$priority</td>";          
                                   echo"<td width='10%'>$handlingteam</td>"; 
                                   echo"<td width='10%'>$status</td>";          
                                   }
                                   echo"<td width='14%'>$custmob</td>";
                                   
if($_POST['bycategory']==2 || $_POST['bycategory']==3 || $_POST['email']!='') {  
                                   echo"<td width='10%'>$category</td>"; 
                                   echo"<td width='10%'>$emailtype</td>";                                                        
                                  }
if($_POST['bycategory']==1){            
        
                                   echo"<td width='10%'>$teamname</td>"; 
                                   echo"<td width='25%'>$enqtype</td>";
                                   echo"<td width='25%'>$leaddate</td>"; 
                                   echo"<td width='25%'>$emailstatus</td>";       
                                   echo"<td width='25%'>$leadstatus</td>"; 
                                   echo"<td width='25%'>$remarks</td>"; 
                            }

        echo'</tr>'; 

 }

           



}
 
 echo '</table>'; 
 echo'</div>';
 echo "</form>";



 echo'</fieldset>';   
?>


  <script>
  function showMailAware(str1,str2){
          //alert(str1)   ;         alert(str2)   ;  
        myRef = window.open("bio_viewmaildetails.php?id=" + str1 +"&category="+ str2,true);
    }
    
    
  function showsubmailcategory(str){
//    alert(str); 

if (str=="")
  {
  document.getElementById("showcatagry").innerHTML="";
  return;
  }
//show_progressbar('showstate');

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {             
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {                 //  alert(str); 
    document.getElementById("showcatagry").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showcategory.php?category=" + str,true);
xmlhttp.send();
}
 

 </script>





