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
echo"<tr><td width=40%>Select Category";
//echo '<td style="width:30%">';
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
         //  echo $_POST['bycategory'];            echo $_POST['submailcategory'];
           
       echo "<table class='selection' style='width:100%'>";  
       
$title="View mails:";
       
       if($_POST['bycategory']!=0)
       $result=DB_query("SELECT main_catid,main_catname FROM bio_mainemailcategory WHERE main_catid=".$_POST['bycategory'],$db);
       $myrow=DB_fetch_array($result); 
$title.=" Category <b><i>".$myrow['main_catname']."</i></b>";  
       if($_POST['submailcategory']!=0) {
       $result=DB_query("SELECT * FROM bio_submailcategory WHERE main_catid=".$myrow['main_catid']." AND sub_catid=".$_POST['submailcategory'],$db);  
       $myrow=DB_fetch_array($result); 
       }
$title.=" SubCategory<b><i>".$myrow['emailcategory']."</i></b>";       
              
       
echo "<tr><td colspan='8'><font size='-1'>".$title."</font></td></tr>";  
       
 echo"<input type=hidden name=subcat id=subcat value=".$_POST['submailcategory'].">";      
       
  if ($_POST['bycategory']==1){    
   
  echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _('Institution Name/ Customer Name') . '</th> <th>' . _('Contact No') . '</th>
                     <th>' . _('Team') . '</th>   <th class="subcat">' . _ ('Category') . '</th> 
                    <th>' . _ ('Remarks') . '</th>      
                   
              </tr>';
                               // <th>' . _('View') . '</th>                                                   
$sql="SELECT bio_incident_cust.cust_id,
                    bio_incident_cust.custname,
                    bio_incident_cust.custphone,
                    bio_incident_cust.landline,
                    bio_incident_cust.custmail,
                    bio_leads.remarks,
                    bio_leadteams.teamname,
                    bio_submailcategory.emailcategory
               
        FROM   bio_incident_cust,
               bio_leads,
               bio_leadteams, 
               bio_incidents,
               bio_submailcategory  
               
         WHERE bio_incident_cust.cust_id=bio_incidents.cust_id  
           AND bio_leadteams.teamid=bio_leads.teamid 
           AND bio_leads.leadid=bio_incidents.leadid
           AND bio_incidents.submailcategory=bio_submailcategory.sub_catid
           AND bio_incidents.mainmailcategory=bio_submailcategory.main_catid
           AND bio_submailcategory.main_catid=".$_POST['bycategory']."
           AND bio_submailcategory.sub_catid=".$_POST['submailcategory']." ";   
              $result=DB_query($sql,$db); 
    $k=0 ; $slno=0; 
      while( $myrow=DB_fetch_array($result) )   {
         
  //$dat= ConvertSQLDate($myrow['createdon']);
  $leadid=$myrow['leadid'];  
  
    $custname=$myrow['custname']; 
     if($myrow['landline']=="") {$custmob=$myrow['custphone'];}
   else{ $custmob=$myrow['landline'];    }
    $teamname=$myrow['teamname'];  
    $remarks=$myrow['remarks'];
    $status=$myrow['biostatus'];
    $district=$myrow['district'];       
    $emailtype=$myrow['emailcategory'];       
        
    $slno++;  
  if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
  } else {
        echo '<tr class="OddTableRows">';
        $k=1;
  }   

          echo "<td width=4%'>$slno</td>";

        echo "<td width='15%'>$custname</td>";           
        echo"<td width='14%'>$custmob</td>";
        
        echo"<td width='10%'>$teamname</td>"; 
        echo"<td width='25%' class='subcat'>$emailtype</td>";
        echo"<td width='25%'>$remarks</td>"; 
        // echo" <td><a href='#' id='".$myrow['leadid']."' onclick='showMailLeads(this.id)'>View</a></td>  ";        
           //  echo"  <td><a href='#' id='".$myrow['leadid']."' onclick='showMail(this.id)'>Reply</a></td>";         
        echo'</tr>'; 

 }

           


  }  
  
   if ($_POST['bycategory']==2){ 
               
  
        echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _('Customer Name') . '</th> <th>' . _('Contact No') . '</th>
                    <th>' . _('Email Type') . '</th>  <th class="subcat">' . _ ('Category') . '</th>   
                     
                   
              </tr>';
  
         $sql="SELECT bio_incident_cust.cust_id,
               bio_incident_cust.landline,
               bio_incident_cust.custphone,
               bio_incident_cust.custname, 
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
           AND bio_submailcategory.main_catid=".$_POST['bycategory']."
           AND bio_submailcategory.sub_catid=".$_POST['submailcategory']."    ";  
              $result=DB_query($sql,$db); 
    $k=0 ; $slno=0; 
      while( $myrow=DB_fetch_array($result) )   {
         
      $buss_id=$myrow['buss_id'];
      $custname=$myrow['custname']; 
        if($myrow['landline']=="") {$custmob=$myrow['custphone'];}
   else{ $custmob=$myrow['landline'];    }
      $emailtype=$myrow['emailcategory'];  
      $category=$myrow['category'];       
   //   $dat=ConvertSQLDate($myrow['createddate']); 
      //$dat= ConvertSQLDate($myrow['createddate']);  
    $slno++;  
  if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
  } else {
        echo '<tr class="OddTableRows">';
        $k=1;
  }   

          echo "<td width=4%'>$slno</td>";

        echo "<td width='15%'>$custname</td>";           
        echo"<td width='14%'>$custmob</td>";
        
        echo"<td width='10%'>$category</td>";  
        echo"<td width='25%' class='subcat'>$emailtype</td>";
       // echo" <td><a href='#' id='".$myrow['buss_id']."' onclick='showMailBuss(this.id)'>View</a></td>  ";        
      //  echo" <td><a href='#' id='".$myrow['buss_id']."' onclick='showMail(this.id)'>Reply</a></td>";        
        echo'</tr>'; 

 }
  
  
   }
   
      if ($_POST['bycategory']==3){ 
                                      
  
        echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _('Customer Name') . '</th> <th>' . _('Contact No') . '</th>
              <th>' . _('Email Type') . '</th>  <th class="subcat">' . _ ('Category') . '</th>   
                   
                   
              </tr>';
 $sql="SELECT bio_incident_cust.cust_id,
               bio_incident_cust.landline,
               bio_incident_cust.custphone,
               bio_incident_cust.custname, 
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
           AND bio_submailcategory.main_catid=".$_POST['bycategory']."
           AND bio_submailcategory.sub_catid=".$_POST['submailcategory']."";  
           
              $result=DB_query($sql,$db); 
    $k=0 ; $slno=0; 
      while( $myrow=DB_fetch_array($result) )   {
         
      $enq_id=$myrow['enq_id']; 
     
      $custname=$myrow['custname']; 
        if($myrow['landline']=="") {$custmob=$myrow['custphone'];}
   else{ $custmob=$myrow['landline'];    } 
      $emailtype=$myrow['emailcategory'];  
      $category=$myrow['category'];     
     
   //   $dat=ConvertSQLDate($myrow['createddate']); 
 
    $slno++;  
  if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
  } else {
        echo '<tr class="OddTableRows">';
        $k=1;
  }   

          echo "<td width=4%'>$slno</td>";

        echo "<td width='15%'>$custname</td>";           
        echo"<td width='14%'>$custmob</td>";

        echo"<td width='10%'>$category</td>";  
        echo"<td width='25%' class='subcat'>$emailtype</td>";
        //  echo"    <td><a  style='cursor:pointer;' onclick=showMailAware('$enq_id','$emailcategory')>" . _('View') . "</a></td> "; 
       //  <td><a href='#' id='".$myrow['enq_id']."' onclick='showMailAware(this.id)'>View</a></td> 
      //  echo" <td><a href='#' id='".$myrow['enq_id']."' onclick='showMail(this.id)'>Reply</a></td>";        
        echo'</tr>'; 

 }
  
  
  
   }
  
  
     
    if ($_POST['bycategory']==5){          
                       
        echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _('Ticket No') . '</th> <th>' . _('Customer Name') . '</th>
                     <th>' . _('Date') . '</th>  <th>' . _ ('Type') . '</th>   
                    <th>' . _ ('Priority') . '</th> <th>' . _ ('Team Handling') . '</th>
                    <th>' . _('Status') . '</th> 
                   
              </tr>';
                    // <th>' . _('View') . '</th>  
    $sql="SELECT bio_incident_cust.cust_id,
                    bio_incident_cust.custname,
                    bio_incident_cust.custphone,
                    bio_incident_cust.custmail,
                    bio_incident_cust.landline,  
                    bio_incidents.ticketno,  
                    bio_incidents.type, 
                    bio_incidents.title,
                    bio_incidents.description,
                    bio_incidents.priority,
                    bio_priority.id,
                    bio_priority.priority,
                    bio_incidenttype.id,
                    bio_incidenttype.type AS intype,
                    bio_incidentstatus.id,
                    bio_incidentstatus.status,
                    
                    bio_leadteams.teamname,
                    bio_incidents.createdon,
                    bio_submailcategory.main_catid 
                    
        FROM  bio_incident_cust,bio_incidents,bio_priority,bio_incidenttype,bio_incidentstatus,bio_leadteams,
              bio_submailcategory 
        
              WHERE bio_incident_cust.cust_id=bio_incidents.cust_id
              
                AND bio_incidents.priority=bio_priority.id
                AND bio_incidents.type=bio_incidenttype.id
                AND bio_incidents.handling_officer=bio_leadteams.teamid
                AND bio_incidents.status=bio_incidentstatus.id
                AND bio_incidents.mainmailcategory=5 
                AND bio_incidents.submailcategory=1
                AND bio_incidents.source=2
                AND bio_submailcategory.main_catid=".$_POST['bycategory']."
                AND bio_submailcategory.sub_catid=".$_POST['submailcategory']."";
              $result=DB_query($sql,$db); 
    $k=0 ; $slno=0; 
 while( $myrow3=DB_fetch_array($result) )   {
     
// $result1=DB_query($sql1, $db);           
  $dat= ConvertSQLDate($myrow3['createdon']);
  
  $ticketno=$myrow3['ticketno'];
  $cust_id=$myrow3['cust_id'];
  $custname=$myrow3['custname'];
  $district=$myrow3['district'];   
    if($myrow['landline']=="") {$custmob=$myrow['custphone'];}
   else{ $custmob=$myrow['landline'];    }
  $title1=$myrow3['title'];
  $priority=$myrow3['priority'];
  $type1=$myrow3['intype'];  
  $status=$myrow3['status'];  
  $team1=$myrow3['teamname'];
  
    $slno++;  
  if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
  } else {
        echo '<tr class="OddTableRows">';
        $k=1;
  }  

          echo"<td>$slno</td><td>$ticketno</td>
               <td>$custname</td><td>$dat</td><td>$type1</td>  
               <td>$priority</td><td>$team1</td><td>$status</td>"; 
        //  echo" <td><a href='#' id='".$myrow3['ticketno']."' onclick='showMailIncident(this.id)'>View</a></td>";
         // echo" <td><a href='#' id='".$myrow3['ticketno']."' onclick='showMail(this.id)'>Reply</a></td>";        
          echo'</tr>'; 
   }
         
           }}
 
 echo '</table>'; 
 echo'</div>';
 echo "</form>";



 echo'</fieldset>';   
?>


<script type="text/javascript">   
  
   var subcat=document.getElementById('subcat').value;      
   
   if(subcat!=0)  {      $(".subcat").hide();   }
  
  
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





