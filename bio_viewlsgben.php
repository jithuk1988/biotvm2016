<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('View Beneficiary');  
include('includes/header.inc');
include('includes/sidemenu.php');

echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">VIEW BENEFICIARY</font></center>';


  
   //LSG Register Deatails Fieldset........................LSG Register Details Fieldset...............................LSG Register Details Fieldset    
    
      
     echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";     
     echo "<fieldset style='float:center;width:97%;'>";     
     echo "<legend><h3>Dealer Register Details</h3>";
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
echo"<tr><td colspan=6><a href=bio_rep_lsg.php?leadid=".$_GET['leadid'].">Print List</a></td></tr>";      
echo'</table>';          
   
       
    echo "<table style='border:1px solid #F0F0F0;width:845px;' id='leadreport'>";
    echo "<thead>";
    
$title="<b>Beneficiary List:</b>";  

if($_POST['df1']!=NULL){
$title.=' Date from <b><i>'.$_POST['df1'].' </i></b>';
}
if($_POST['dt1']!=NULL){
 $title.=' to <b><i>'.$_POST['dt1'].' </i></b>';   
}     
if($_POST['byname']!=NULL){
 $title.=' Area <b><i>'.$_POST['byname'].' </i></b>';   
}  
if($_POST['byplace']!=NULL){
 $title.=' District <b><i>'.$_POST['byplace'].' </i></b>';   
}      
       
      
echo "<tr><td colspan='8'><font size='-1'>".$title."</font></td></tr>";     
      
       
      echo"<tr BGCOLOR =#800000>
                <th width='50px'>" . _('SL No') . "</th>
                <th width='100px'>" . _('Customer Name:') . "</th>
                <th width='170px'>" . _('Contact Number') . "</th>
                <th width='150px'>" . _('Area') . "</th>
                 <th width='85px'>" . _('Remarks') . "</th>                     
                <th width='85px'>" . _(' ') . "</th>
                </tr></thead>";  
    echo "</table>";            
                
  echo "<div style='height:500px; overflow:auto;'>";   
  echo "<table  style='border:1px solid #F0F0F0;width:850px;' id='LSGregisterdetailshead'>"; 
          
          
  /* $sql="SELECT `bio_lsgreg`.`id`,                    
               `bio_cust`.`custname`,              
               `bio_cust`.`custphone`, 
               `bio_cust`.`custmob`,                 
               `bio_cust`.`district`, 
               `bio_leads`.`leaddate`, 
               `bio_cust`.`area1`,   
               `bio_leads`.`leadid`, 
               `bio_lsgstatus`.`status`
          FROM 
              bio_leads
    INNER JOIN `bio_cust` ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    INNER JOIN `bio_lsgreg` ON (`bio_leads`.`leadid` = `bio_lsgreg`.`leadid`)
    INNER JOIN `bio_lsgstatus` ON (`bio_lsgreg`.`status` = `bio_lsgstatus`.`id`)
    LEFT JOIN bio_district ON bio_district.did = bio_cust.district
            AND bio_district.stateid = bio_cust.state
            AND bio_district.cid = bio_cust.nationality
    WHERE bio_cust.cust_id=bio_leads.cust_id
    AND bio_leads.leadid in (select leadid from bio_leads where parent_leadid=7527)";    */
    
    
/*    $sql="SELECT `bio_lsgreg`.`id`,                    
               `bio_cust`.`custname`,              
               `bio_cust`.`custphone`, 
               `bio_cust`.`custmob`,                 
               `bio_cust`.`district`, 
               `bio_leads`.`leaddate`, 
               `bio_cust`.`area1`,   
               `bio_leads`.`leadid`, 
               `bio_lsgstatus`.`status`
          FROM 
              bio_leads,bio_lsgreg,,bio_lsgstatus,bio_district
   WHERE bio_leads.cust_id = bio_cust.cust_id   
   AND bio_district.did = bio_cust.district
   AND bio_lsgreg.status = bio_lsgstatus.id
   AND bio_district.stateid = bio_cust.state
   AND bio_district.cid = bio_cust.nationality
   AND bio_leads.leadid in (select leadid from bio_leads where parent_leadid=7527)";    */
   
   

    
    
   /* $sql="SELECT `bio_lsgreg`.`id`,                    
               `bio_cust`.`custname`,              
               `bio_cust`.`custphone`, 
               `bio_cust`.`custmob`,                 
               `bio_cust`.`district`, 
               `bio_leads`.`leaddate`, 
               `bio_cust`.`area1`,   
               `bio_leads`.`leadid`, 
               `bio_lsgstatus`.`status`
          FROM 
              bio_leads,bio_lsgreg,bio_cust,bio_district,bio_lsgstatus  
    WHERE bio_leads.cust_id = bio_cust.cust_id 
    AND bio_leads.leadid = bio_lsgreg.leadid     
    AND bio_district.did = bio_cust.district
    AND bio_lsgreg.status = bio_lsgstatus.id
    AND bio_district.stateid = bio_cust.state
    AND bio_district.cid = bio_cust.nationality
    AND bio_cust.cust_id=bio_leads.cust_id
    AND bio_leads.leadid =".$parent_leadid;      */
                                                    
    
    
     /*
     $sql1="SELECT   
                 `bio_lsgreg`.`id`,  
                 `bio_lsgstatus`.`status`
          FROM 
              bio_leads 
    INNER JOIN `bio_lsgreg` ON (`bio_leads`.`leadid` = `bio_lsgreg`.`leadid`)
    INNER JOIN `bio_lsgstatus` ON (`bio_lsgreg`.`status` = `bio_lsgstatus`.`id`)
    
    WHERE bio_lsgreg.leadid=bio_leads.leadid
    AND bio_lsgreg.status=bio_lsgstatus.id
    AND bio_leads.parent_leadid =".$parent_leadid;     
                                                              */
    
    
                                                 
  
  
    /*$sql2="SELECT `bio_cust`.`custname` ,
                 `bio_cust`.`custphone` ,
                 `bio_cust`.`custmob` ,
                 `bio_cust`.`district` ,
                 `bio_leads`.`leaddate` , 
                 `bio_cust`.`area1` , 
                 `bio_leads`.`leadid`
    FROM bio_leads
    INNER JOIN `bio_cust` ON ( `bio_leads`.`cust_id` = `bio_cust`.`cust_id` )
    LEFT JOIN bio_district ON bio_district.did = bio_cust.district
    AND bio_district.stateid = bio_cust.state
    AND bio_district.cid = bio_cust.nationality
    WHERE bio_cust.cust_id = bio_leads.cust_id
    AND bio_leads.parent_leadid =".$parent_leadid ;            
                                                    */
                                          
                                                    
$lsgid=$_GET['id'];   
  
if($_GET['leadid']==''){
   $_GET['leadid']=$_SESSION['leadid'];
   $parent_leadid=$_GET['leadid']; 
}else{
    $_SESSION['leadid']=$_GET['leadid']; 
    $parent_leadid=$_SESSION['leadid'];
} 
                                              
                                                    
   $sql="SELECT bio_cust.cust_id,bio_cust.custname,bio_cust.custmob,bio_cust.area1,bio_leads.leadid,bio_leads.remarks 
         FROM   bio_cust,bio_leads
         WHERE  bio_cust.cust_id=bio_leads.cust_id        
         AND    bio_leads.leadid IN (SELECT leadid from bio_leads where parent_leadid=$parent_leadid)";
   

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
    $custid=$myrow['cust_id'];
  
  $sql_count="SELECT leadid FROM salesorders WHERE leadid=$leadid";
  $result_count=DB_query($sql_count,$db);   
  $myrow_count=DB_fetch_array($result_count);

  if($myrow_count['leadid']!=null) {        
  $ordered="<td width='85px'>Order Registered</td>";  
  }else{
  $ordered="<td width='85px'><a  style='cursor:pointer;'  id='$id' onclick='lsgOrder($custid,$leadid)'>" . _('Create Order') . "</a></td>";      
  }  
    
    
    printf("<td cellpading=10 width='50px'>%s</td>
            <td width='100px'>%s</td>
            <td width='170px'>%s</td>
            <td width='150px'>%s</td>
            <td width='85px'>%s</td>      
            <td width='85px'><a  style='cursor:pointer;'  id='$id' onclick='passid($leadid)'>" . _('StatusChange') . "</a></td>
            $ordered
            </tr>",
            $slno,
            $myrow['custname'],             
            $myrow['custmob'],
            $myrow['area1'],            
            $myrow['remarks']);
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
               myRef = window.open("bio_lsgstatuschange6.php?leadid=" + str1 + "&id=" + str2,"lsgstatus","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");

    }
    
    function passid1(str1)
    {        //          alert('22');
               myRef = window.open("bio_lsgaddbeneficiary.php?leadid=" + str1 + "&flag=1" ,"lsgbenedit","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
    }

    function lsgOrder(str1,str2)
    {
        //alert(str1);         alert(str2); 
               myRef = window.open("LeadsCustomers.php?custid=" + str1 + "&leadid=" + str2 + "&enquiryid=3"  ,"lsgorder","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");

    }



</script>   
    
    
    
    
       
   
    
    
    
  
       
 
   
   
   
 