<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Registration');  
include('includes/header.inc');
include('includes/sidemenu.php');

echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">LSG REGISTER</font></center>';


  
   //LSG Register Deatails Fieldset........................LSG Register Details Fieldset...............................LSG Register Details Fieldset    
    
      
     echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";     
     echo "<fieldset style='float:center;width:97%;'>";     
     echo "<legend><h3>LSG REgister Details</h3>";
     echo "</legend>";
     
//=======================================================Search     
     echo "<table style='border:1px solid #F0F0F0;width:100%'>";
   
     echo"<tr><td>Date From</td>
              <td>Date To</td>
              <td>Project Name</td>
              <td>District</td>
              <td>Phone Number</td></tr>"; 
              
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname' id='byname'></td>";
echo"<td><input type='text' name='byplace' id='byplace'></td>"; 
echo"<td><input type='text' name='mob' id='mob'></td>";

        

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='Search'></td>";
echo"</tr>";          
echo'</table>';          
   
       
    echo "<table style='border:1px solid #F0F0F0;width:845px;' id='leadreport'>";
    echo "<thead>
                <tr BGCOLOR =#800000>
                <th width='50px'>" . _('SL No') . "</th>
                <th width='100px'>" . _('Project Name:') . "</th>
                <th width='170px'>" . _('LSG Name') . "</th>
                <th width='150px'>" . _('Contact Number') . "</th>
                <th width='85px'>" . _('Project Cost') . "</th>
                <th width='85px'>" . _('Number of Beneficiaries') . "</th> 
                <th width='85px'>" . _('Status') . "</th>
                <th width='110px'>" . _(' ') . "</th>
                </tr></thead>";  
    echo "</table>";            
                
  echo "<div style='height:500px; overflow:auto;'>";   
  echo "<table  style='border:1px solid #F0F0F0;width:850px;' id='LSGregisterdetailshead'>";              
          
  $sql="SELECT `bio_lsgreg`.`id`, 
               `bio_lsgreg`.`project_name`, 
               `bio_corporation`.`corporation`, 
               `bio_municipality`.`municipality`, 
               `bio_panchayat`.`name`, 
               `bio_cust`.`custphone`, 
               `bio_cust`.`custmob`, 
               `bio_cust`.`contactperson`, 
               `bio_cust`.`LSG_type`,
               `bio_cust`.`district`, 
               `bio_leads`.`leaddate`, 
               `bio_leads`.`leadid`, 
               `bio_lsgreg`.`total_projectcost`, 
               `bio_lsgreg`.`num_beneficiaries`, 
               `bio_lsgstatus`.`status`
          FROM `bio_leads`
    INNER JOIN `bio_cust` ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_lsgreg` ON (`bio_leads`.`leadid` = `bio_lsgreg`.`leadid`)
    INNER JOIN `bio_lsgstatus` ON (`bio_lsgreg`.`status` = `bio_lsgstatus`.`id`)
      LEFT JOIN bio_district ON bio_district.did = bio_cust.district
            AND bio_district.stateid = bio_cust.state
            AND bio_district.cid = bio_cust.nationality
      LEFT JOIN bio_corporation ON bio_corporation.district = bio_cust.LSG_name 
            AND bio_corporation.district = bio_cust.district 
            AND bio_corporation.state = bio_cust.state 
            AND bio_corporation.country = bio_cust.nationality
      LEFT JOIN bio_municipality ON bio_municipality.id = bio_cust.LSG_name 
            AND bio_municipality.district = bio_cust.district 
            AND bio_municipality.state = bio_cust.state 
            AND bio_municipality.country = bio_cust.nationality
      LEFT JOIN bio_panchayat ON bio_panchayat.id = bio_cust.block_name 
            AND bio_panchayat.block = bio_cust.LSG_name 
            AND bio_panchayat.district = bio_cust.district 
            AND bio_panchayat.state = bio_cust.state 
            AND bio_panchayat.country = bio_cust.nationality 
          WHERE bio_cust.cust_id=bio_leads.cust_id   
             ";
   

 if(isset($_POST['filterbut1']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off1'];
  //  echo $officeid;
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql .= " AND bio_lsgreg.project_name LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql .= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }
    
    if (isset($_POST['mob'])) {
    if ($_POST['mob']!='') 
    $sql .= " AND bio_cust.custmob LIKE '%".$_POST['mob']."%'"; 
    }
    

 }
 //echo$sql;
 $result=DB_query($sql,$db);  

$k=0; //row colour counter
$slno=0;  
while ($myrow = DB_fetch_array($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
   $slno++;
       $LSG_type=$myrow['LSG_type'];   
       

   if($LSG_type==1){
    $lsg_name=$myrow['corporation']."(C)";   
   } 
    else if($LSG_type==2){  
      $lsg_name=$myrow['municipality']."(M)";
        
    }
    else if($LSG_type==3)
    {
        $lsg_name=$myrow['name']."(P)";
    }
    $id=$myrow['id'];
    $leadid=$myrow['leadid']; 
   printf("<td cellpading=2 width='50px'>%s</td>
            <td width='100px'>%s</td>
            <td width='170px'>%s</td>
            <td width='150px'>%s</td>
            <td width='85px'>%s</td>
            <td width='85px'>%s</td>
            <td width='85px'>%s</td>
            <td width='110px'><a  style='cursor:pointer;'  id='$id' onclick='passid($leadid,$id)'>" . _('StatusChange') . "</a></td></tr>",
            $slno,
            $myrow['project_name'],
            $lsg_name,
            $myrow['custmob'],
            $myrow['total_projectcost'],
            $myrow['num_beneficiaries'],
            $myrow['status']);
} 
echo'</div>';
echo'</table>';       
echo"</fieldset>";
echo"</form>";
?> 
  
 <script type="text/javascript"> 
 
 
  function replace_html(id, content) {
    document.getElementById(id).innerHTML = content;
}
var progress_bar = new Image();
progress_bar.src = '4.gif';
function show_progressbar(id) {
    replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}

 


    function passid(str1,str2)
    {
               myRef = window.open("bio_lsgstatuschange.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");

    }



</script>   
    
    
    
    
       
   
    
    
    
  
       
 
   
   
   
 