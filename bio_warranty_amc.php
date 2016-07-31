
<?php 

$PageSecurity=80;
$pagetype=1;
include('includes/session.inc');
$title = _('AMC/Warranty');
echo '<head><link href="menu_assets/styles.css" rel="stylesheet" type="text/css"></head>';
include('includes/header.inc'); 
 echo "<div id='cssmenu'>
<ul>
     <li > <a href='bio_nwinstallationstatuses.php'><span>Post installation</span></a></li>
   <li ><a href='bio_marketingtask.php'><span>Marketing task</span></a></li>
    <li class='active '><a href='bio_warranty_amc.php'><span>AMC / Warrenty</span></a></li>
   <li><a href='bio_paypending.php'><span>Payment pending</span></a></li>
   <li><a href='bio_cdmsurvey.php'><span>CDM survey</span></a></li>
       <li><a href='bio_complaintfollow.php'><span>Complaint followup</span></a></li>
</ul>                                      
</div>";

if(isset($_POST['submit']))
{
    
  $createdate=date("Y-m-d"); 
  if(isset($_POST['orderno']))
  {
  $sql="  INSERT INTO `bio_amc_interest`(`orderno`, `status`, `remarks`, `Date`) VALUES ( '".$_POST['orderno']."','".$_POST['Status']."','".$_POST['remarks']."','".$createdate."' )";
  $result=DB_query($sql,$db);
      $msg1= 'Interest Status has been Save succesfully. Your Order no is <b>"'.$_POST['orderno'].'"</b>';      
        prnMsg($msg1,'success'); 
  }
}

if(isset($_GET['orderno'])) 
{
 $sql="SELECT 
 salesorders.orderno,
 salesorders.debtorno,
 custbranch.brname,
 custbranch.`cid`,
 custbranch.`stateid`,
 custbranch.`did`,
concat(custbranch.phoneno,'/',custbranch.faxno) as phone,
custbranch.LSG_type,
stockmaster.description,
bio_installation_status.plant_status,
bio_corporation.corporation,bio_municipality.municipality,bio_panchayat.name
FROM `custbranch`
inner join salesorders on salesorders.orderno='".$_GET['orderno']."'
left join salesorderdetails on salesorderdetails.orderno=salesorders.orderno
left join stockmaster on salesorderdetails.stkcode=stockmaster.stockid 
left join bio_installation_status on  bio_installation_status.orderno=salesorders.orderno
LEFT JOIN bio_corporation ON bio_corporation.district = custbranch.LSG_name AND bio_corporation.district = custbranch.did AND bio_corporation.state = custbranch.stateid AND bio_corporation.country = custbranch.cid
LEFT JOIN bio_municipality ON bio_municipality.id = custbranch.LSG_name AND bio_municipality.district = custbranch.did AND bio_municipality.state = custbranch.stateid AND bio_municipality.country = custbranch.cid
LEFT JOIN bio_panchayat ON bio_panchayat.id = custbranch.block_name AND bio_panchayat.block = custbranch.LSG_name AND bio_panchayat.district = custbranch.did AND bio_panchayat.state = custbranch.stateid AND bio_panchayat.country = custbranch.cid
WHERE custbranch.`debtorno`=salesorders.debtorno ";
$result=DB_query($sql,$db);

 //$myrow2=DB_fetch_row($result);
 while($myrow1=DB_fetch_array($result))
    { 
$custname=$myrow1['brname'];
$phone=$myrow1['phone'];
// $lsgd=$myrow1['LSG_type'];
 $plantname=$myrow1['description'];
 $status=$myrow1['plant_status'];
 $debtorno=$myrow1['debtorno'];
 $cid=$myrow1['cid'];
 $stateid=$myrow1['stateid'];
 $did=$myrow1['did'];
 $orderno=$myrow1['orderno'];
 
      if($myrow1['LSG_type']==1){
         $lsgd=$myrow1['corporation']."(C)";
     }elseif($myrow1['LSG_type']==2){
         $lsgd=$myrow1['municipality']."(M)";
     }elseif($myrow1['LSG_type']==3){
         if($myrow1['block_name']!=0 || $myrow1['LSG_name']!=0){
         $lsgd=$myrow1['name']."(P)";
         }
     }elseif($lastcomp['LSG_type']==0){
         $lsgd="";
     }
     
    }

  


}


echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
   echo'<table width=80% border=2 bordercolor="green">';
  echo'   <tr>
        <th><font color=blue size=3><b>' . _('AMC/Warranty Details') . '</b></font></th>
          
            <th><font color=blue size=3><b>' . _('Enter AMC/Warranty Details') . '</b></font></th>
        </tr>';
echo'<tr>';
echo'<td><table><tr>';
//echo'left';
Echo'<tr><td><b>Customer Name </td><td>: '.$custname.'</td></tr>
<tr><td><b>Phone No </td><td>: '.$phone.'</td></tr>
<tr><td><b>LSGD </td><td><b>: '.$lsgd.'</b></td></tr>
<tr><td><b>Plant name </td><td><b>: '.$plantname.'</b></td></tr>
<tr><td><b>Plant Status </td>';

if(isset($_GET['orderno']) )
 { 
      
  $ord=$_GET['orderno'];
     
     
     
     
          if(isset($ord))
     {       
  $sql="select bio_amc.start_date,bio_amc.end_date
 FROM bio_amc
 LEFT JOIN salesorders ON bio_amc.debtorno=salesorders.debtorno           
   WHERE salesorders.orderno like $ord " ; 
          //  LEFT JOIN salesorders ON bio_amc.debtorno=salesorders.debtorno  
     
  
$result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result);
    $ListCount = DB_num_rows($result);
    
      if($ListCount >0)
  { 
/*    if($start_date!=NULL AND $start_date!='0000-00-00')
  {  */ 
 $start_date=$myrow['start_date'];  
 $end_date=$myrow['end_date'];  

 $valid = "AMC ";
 $flage=2;
 $start_date= ConvertSQLDate($start_date);  
         $end_date= ConvertSQLDate($end_date);

   echo"<tr><td width=40%>AMC Start Date</td>";
   echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$start_date."' style='width:120px' ></td></tr>";
      
   echo"<tr><td width=40%>AMC Expiry Date</td>";
echo"<td><input type='' name='custname' readonly id='custname' tabindex=1 value='".$end_date."' style='width:120px' ></td></tr>";
//  echo"<tr><td width=40%>AMC Status</td>";
//echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$valid."' style='width:120px' ></td></tr>";   
  //  echo"</table>"  ;    
  //}
  }
  else
  {
$sql="select bio_installation_status.installed_date FROM bio_installation_status WHERE  bio_installation_status.orderno like $ord  and bio_installation_status.orderno not in (SELECT `new_orderno` FROM `bio_amc`)  " ; 


 $result=DB_query($sql,$db);
// echo $sql;
 $myroq=DB_fetch_array($result);
   $installed_date=$myroq['installed_date'];
   
   // if($installed_date!=NULL AND $installed_date!='0000-00-00')
 // {  
  $date = strtotime(date("Y-m-d", strtotime($installed_date)) . " +1 year");
   $closedate=date('Y-m-d',$date);
  $today=date("Y-m-d"); 
  
  $installed_date= ConvertSQLDate($installed_date); 
   $closedate= ConvertSQLDate($closedate); 
     echo"<tr><td width=40%>Installed Date</td>";
echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$installed_date."' style='width:120px' ></td></tr>";
echo"<tr><td width=40%>Warranty Expiry Date</td>";
echo"<td><input type='' name='custname' readonly id='custname' tabindex=1 value='".$closedate."' style='width:120px' ></td></tr>";
 // }
  }
  
  
    }

  
 }


echo'</td></table>';

echo'<td><table><tr>';
//echo'right';

Echo'<tr><td><b>Interest Status </td><td>';
echo"<select name='Status' id='Status'  >";
echo '<option value=1">YES</option>';
echo '<option value=0">No</option>';
 
  echo '</select></td>';
  echo'</td></tr>
<tr><td><b>Remark </td><td><textarea name="remarks" cols="30" rows="4"></textarea> </td></tr>';
echo'<input type=hidden name="orderno" id="orderno" value="'.$_GET['orderno'].'"';
echo'</td></table>';
echo'</tr>'; 
echo'<tr><td colspan="2"><center><input type="submit" name="submit" id=""  value="submit"></center></td></tr>';       

  echo'</table>';
    
    
   
    

//echo'<table class=selection>';
     echo"<fieldset ><legend>Search AMC/Warranty End list</legend>";
 echo'<table class=selection ><tr>';//
 
    echo'<td>Name<input type="test" name="names" id="names"></td>';
    echo'<td>Moblie No<input type="test" name="moblie" id="moblie"></td>';
  echo'<td><input type="submit" value="Search"></td>';
 echo'</tr></table>';
    
   echo'</fieldset >'; 
    

   echo"<table><tr><td width=98%>";  

  $today=date("Y-m-d"); 

  $date = strtotime(date("Y-m-d", strtotime($today)) . " -11 month");
    $closedate=date('Y-m-d',$date);
  $date2=strtotime(date("Y-m-d", strtotime($today)) . " -2 year");
    $cancel =date('Y-m-d',$date2);
    $date3 = strtotime(date("Y-m-d", strtotime($today)) . " +1 month");
    $amcend=date('Y-m-d',$date3);
 

  $sql1="
SELECT bio_amc.`new_orderno` as orderno,concat( bio_amc.`start_date`,'(AMC)') as dates ,custbranch.`email`,custbranch.`brname`,concat (custbranch.`phoneno`,'/',custbranch.faxno) as phoneno
FROM `bio_amc`
left join salesorders on  bio_amc.`new_orderno`=salesorders.orderno
left join  custbranch on custbranch.debtorno=salesorders.debtorno

WHERE `end_date` <  '$amcend' 
 ";
 if($_POST['names']!="")
 {
$sql1 .="AND custbranch.`brname` like '%".$_POST['names']."%'"; 
 }
  if($_POST['moblie']!="")
 {
$sql1 .="AND custbranch.`phoneno` like '%".$_POST['moblie']."%'  or  custbranch.`faxno` like '%".$_POST['moblie']."%'"; 
 }
  $sql1 .="AND bio_amc.`new_orderno` not in (select bio_amc_interest.orderno from bio_amc_interest 
  where bio_amc_interest.date like '$today')";
$sql2="
SELECT bio_installation_status.`orderno` as orderno,concat (bio_installation_status.`installed_date`,'(Warranty)') as dates ,custbranch.`email`,custbranch.`brname`,concat (custbranch.`phoneno`,'/',custbranch.faxno) as phoneno
FROM `bio_installation_status`
left join salesorders on bio_installation_status.orderno=salesorders.orderno
left join  custbranch on custbranch.debtorno=salesorders.debtorno

WHERE `installed_date` <  '$closedate' And installed_date > '$cancel'

  ";

 if($_POST['names']!="")
 {
$sql2 .="AND custbranch.`brname` like '%".$_POST['names']."%'"; 
 }
    if($_POST['moblie']!="")
 {

$sql2 .="AND custbranch.`phoneno` like '%".$_POST['moblie']."%'  or  custbranch.`faxno` like '%".$_POST['moblie']."%'"; 
 }
 $sql2 .="  AND bio_installation_status.`orderno` 
not in (
SELECT bio_amc.`new_orderno`  
FROM `bio_amc`
 left join salesorders on bio_amc.`new_orderno`=salesorders.orderno 
left join custbranch on custbranch.debtorno=salesorders.debtorno )";
 $sql2 .="AND bio_installation_status.`orderno`  not in (select bio_amc_interest.orderno from bio_amc_interest where bio_amc_interest.date like '$today ')";
 $sql=$sql1 .'union'. $sql2;


  $sql;
$result3=DB_query($sql,$db);
     $incident3_count=DB_num_rows($result3);
     echo"<fieldset ><legend>AMC/Warranty End list</legend>";
     echo"<div style='height:400px; width:100%; overflow:scroll;'>";     
   
     echo"<table width=100%> ";
     
      echo"<th width=50px>Order id</th><th width=300px>Customer Name</th><th  width=100px>Mobile</th><th  width=300px>Email id</th>";  
         
      while($row_insident3=DB_fetch_array($result3)){
         $order=$row_insident3['orderno'];  
                                                                                                        
            if ($k == 1) {
            echo '<tr onclick=selectOrder('.$order.') class="EvenTableRows">';
            $k = 0;
            } else {
            echo '<tr onclick=selectOrder('.$order.') class="OddTableRows">';
            $k++;
            }                                                                                                                                                                                                                
  
         echo"<td> ".$row_insident3['orderno']."</td>
         
              <td>".$row_insident3['brname']."</td>
              <td>".$row_insident3['phoneno']."</td> 
              <td>".$row_insident3['email']."</td>
           
              <td>" . _('Select') . "</td>";         //<td> ".$row_insident3['dates']."</td>
         echo"</tr>";
         }
         echo"</table>";
         
         echo"</div>"; 
         echo"</fieldset>";  
   echo'</td></tr></table>';

echo"</form>";
?>

<script type="">
function selectOrder(str)
{
//alert("fgbdg");
var order=str;
  location.href="?orderno=" +order;   
}
</script>
